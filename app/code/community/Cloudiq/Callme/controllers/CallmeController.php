<?php

class Cloudiq_Callme_CallmeController extends Mage_Core_Controller_Front_Action {
    public function requestAction() {
        /** @var Cloudiq_Callme_Model_Config $_config_model */
        $_config_model = Mage::getModel("cloudiq_callme/config");

        $result = array(
            "status" => false,
            "message" => "Request status unknown"
        );

        if ($_config_model->isConfigured()) {
            $phone_number = $this->getRequest()->getParam("phone_number");
            if (!empty($phone_number)) {
                $app_id = $_config_model->getAppId();

                $api_result = Mage::getModel("cloudiq_callme/api_callme")->requestCallback($app_id, $phone_number);

                if (is_array($api_result) && is_bool($api_result["status"])) {
                    $result = $api_result;
                } else {
                    $result["message"] = "API call result unknown";
                }
            } else {
                $result["message"] = "Must specify a phone number to call";
            }
        } else {
            $result["message"] = "callMe module is not available";
        }

        $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody(Zend_Json::encode($result));
    }
}
