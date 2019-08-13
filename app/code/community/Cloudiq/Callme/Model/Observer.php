<?php

class Cloudiq_Callme_Model_Observer extends Varien_Object {

    public function observeCloudiqCoreConfigSave($observer) {
        $request = $observer->getRequest();


        /** @var $helper Cloudiq_Callme_Helper_Data */
        $helper = Mage::helper("cloudiq_callme");

        /** @var $config_model Cloudiq_Callme_Model_Config */
        $config_model = Mage::getModel("cloudiq_callme/config");

        $form_data = new Varien_Object($request->getParam("callme"));

        if (count($form_data->getData()) == 0) {
            // Don't save the config model if the callMe tab was not present
            return;
        }

        // Save form data in the admin session to repopulate the form in case of errors while saving
        Mage::getSingleton("adminhtml/session")->setData("callme_form_data", $form_data);

        $helper->updateConfigFromForm($config_model, $form_data);

        // Validate the data, show an error for each field that doesn't pass
        $result = $config_model->validate();
        if (is_array($result)) {
            foreach($result as $field => $message) {
                Mage::getSingleton("adminhtml/session")->addError($helper->__("callMe: " . $message));
            }
            return;
        }

        // Submit the config changes to the API
        $api = Mage::getModel("cloudiq_callme/api_callme");
        $api_result = $api->storeCallme($config_model);
        if (is_array($api_result)) {
            foreach ($api_result as $message) {
                Mage::getSingleton("adminhtml/session")->addError($helper->__("callMe API error: " . $message));
            }
            return;
        }

        $config_model->save();
        Mage::getSingleton("adminhtml/session")->addSuccess($helper->__("callMe: Configuration saved."));
    }

    /**
     * Insert our callMe block if we are on the correct layout handle.
     *
     * @param Varien_Event_Observer $observer
     */
    public function observeControllerActionLayoutLoadBefore(Varien_Event_Observer $observer) {
        /** @var Mage_Core_Model_Layout $layout */
        $layout = Mage::app()->getLayout();

        if ($layout->getArea() != Mage_Core_Model_App_Area::AREA_FRONTEND) {
            return;
        }

        /** @var array $layout_handles */
        $layout_handles = $layout->getUpdate()->getHandles();

        /** @var Cloudiq_Callme_Model_Config $callme_config */
        $callme_config = Mage::getModel('cloudiq_callme/config');

        /** @var Cloudiq_Core_Helper_Config $core_helper */
        $core_helper = Mage::helper('cloudiq_core/config');

        $core_enabled = $core_helper->hasBeenSetUp() && $core_helper->isEnabled();

        if (!$core_enabled || !$callme_config->isConfigured()) {
            return;
        }

        $callme_handles = explode(',', $callme_config->getDisplayOnPages());

        /**
         * Only render the block if we've checked the "Display on all pages" checkbox, or at least one of our current
         * page handles matches the configured handles.
         */
        if ($callme_config->getDisplayOnAll() || count(array_intersect($callme_handles, $layout_handles)) > 0) {
            $layout->getUpdate()->addHandle("cloudiq_callme_button");
        }
    }
}
