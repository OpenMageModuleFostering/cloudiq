<?php
/**
 * Class Cloudiq_Callme_Adminhtml_CallmeController
 *
 * @package Cloudiq_Callme
 * @author  Meanbee <hello@meanbee.com>
 */
class Cloudiq_Callme_Adminhtml_CallmeController extends Mage_Adminhtml_Controller_Action {

    /**
     * Generate and display a preview of the callMe button from the submitted data.
     * Returns only the button specific, incomplete html as this action is meant to
     * be requested through AJAX.
     */
    public function previewAction() {
        $helper = Mage::helper("cloudiq_callme");

        $form = new Varien_Object($this->getRequest()->getParam("callme"));

        // Only display the button if config data has been submitted
        if (count($form->getData()) == 0) {
            return;
        }

        $config = new Varien_Object();
        $helper->updateConfigFromForm($config, $form);

        /** @var $button Cloudiq_Callme_Block_Button */
        $button = Mage::app()->getLayout()->createBlock("cloudiq_callme/button", "cloudiq_callme.button");
        $button->setConfig($config);
        $button->setDisabled(true);

        $this->getResponse()->setBody($button->toHtml());
    }
}
