<?php

class Cloudiq_Callme_Model_Api_Callme extends Cloudiq_Core_Model_Api_Abstract {

    /**
     * Submit callMe configuration changes to the callMe API. Sends a request
     * to the API with mode="store" and action="callMe". If the request results
     * in a new app being created, the configuration model passed to
     * the method will be updated with a new app ID, but not saved. Returns
     * true if the action was successful or an array of error messages if it
     * failed.
     *
     * @param  Cloudiq_Callme_Model_Config &$config Configuration to submit to the API
     * @return bool|array
     */
    public function storeCallme(Cloudiq_Callme_Model_Config &$config) {
        $parameters = $this->_convertConfigToParameters($config);

        $parameters["mode"] = "store";
        $parameters["action"] = "callme";

        /** @var Cloudiq_Core_Model_Api_Request $request */
        $request = $this->_getRequestObject()->setParameters($parameters);

        $errors = array();

        $response = $request->send(Zend_Http_Client::POST);

        if ($response) {
            if ($response->wasSuccessful()) {
                $callme_response = $response->getResponse()->callMe;

                if (!is_null($callme_response)) {
                    if ($callme_response['status'] == Cloudiq_Core_Model_Api_Response::STATUS_SUCCESS) {
                        // Successful callMe response, check if a new app ID was created
                        $app_id = (int) $callme_response->appId;
                        if (!is_null($app_id)) {
                            $config->setAppId($app_id);
                        }
                    } else {
                        // Unsuccessful callMe response, build an appropriate error message
                        $error_message = "Unsuccessful callMe response: " . $response->getApiStatusCodeDescription($callme_response['status']);
                        if ($callme_response->description) {
                            $error_message .= ": " . $callme_response->description;
                        }
                        $errors[] = $error_message;
                    }
                } else {
                    $errors[] = "Unknown callMe response status.";
                }
            } else {
                // Unsuccessful response
                $errors[] = $response->getErrorMessage();
            }
        } else {
            $errors[] = "Failed to connect to cloud.IQ API";
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    /**
     * Submit a callback request to the API. Returns an array with two elements:
     *  status - boolean flag, true if the callback request was successful, false otherwise
     *  message - response message or error message if the request was unsuccessful
     *
     * @param $app_id           string  The id of the app to assign the request to.
     * @param $consumer_number  string  The phone number for the callback.
     *
     * @return array
     */
    public function requestCallback($app_id, $consumer_number) {
        $parameters = array(
            "mode" => "send",
            "action" => "callback",
            "appId" => $app_id,
            "consumerNumber" => $consumer_number,
            "consumerIp" => Mage::helper("core/http")->getRemoteAddr()
        );

        $result = array(
            "status"  => false,
            "message" => ""
        );

        $response = null;
        $request = $this->_getRequestObject()->setParameters($parameters);

        $response = $request->send(Zend_Http_Client::POST);

        if ($response) {
            if ($response->wasSuccessful()) {
                $callback_response = $response->getResponse()->callback;

                if (!is_null($callback_response)) {
                    if($callback_response['status'] == Cloudiq_Core_Model_Api_Response::STATUS_SUCCESS) {
                        // Successfully submitted a callback request, fetch the response message
                        $result["status"] = true;
                        $result["message"] = sprintf("%s", $callback_response->description);
                    } else {
                        // Unsuccessful callback response, fetch the error message
                        $result["message"] = $response->getApiStatusCodeDescription($callback_response['status']);
                        if ($callback_response->description) {
                            $result["message"] .= sprintf(": %s", $callback_response->description);
                        }
                    }
                }
            } else {
                // Unsuccessful response
                $result["message"] = $response->getErrorMessage();
            }
        } else {
            $result["message"] = "Failed to connect to cloud.IQ API";
        }

        return $result;
    }

    /**
     * Convert Cloudiq_Callme_Model_Config into an array of parameters used by the API
     *
     * @param  Cloudiq_Callme_Model_Config $config
     * @return array
     */
    protected function _convertConfigToParameters(Cloudiq_Callme_Model_Config $config) {
        $parameters = $config->toCamelCaseArray();

        // Remove parameters not used by the API
        unset($parameters["id"],
              $parameters["enabled"],
              $parameters["displayOnAll"],
              $parameters["displayOnPages"]
        );

        // Set constant parameters
        // FIXME: Should only send the logo url to the API if it is enabled in the config
        // but at the moment the API request fails if the logo is empty.
        $parameters["popUpLogo"] = Mage::getDesign()->getSkinUrl(Mage::getStoreConfig('design/header/logo_src'), array("_area" => "frontend"));

        // FIXME: New requirements say there should only be one set of opening hours per day, but the API still uses the period notation
        $parameter_names = array_keys($parameters);
        foreach ($parameter_names as $name) {
            if (preg_match("/^(monday|tuesday|wednesday|thursday|friday|saturday|sunday|bankHoliday)(.*)$/", $name, $matches)) {
                $new_name = $matches[1] . "Period1" . $matches[2];
                $parameters[$new_name] = $parameters[$name];
                unset($parameters[$name]);
            }
        }

        return $parameters;
    }
}
