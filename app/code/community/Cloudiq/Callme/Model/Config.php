<?php

/**
 * Class Cloudiq_Callme_Model_Config
 *
 * @method bool getEnabled()
 * @method bool getDisplayOnAll()
 * @method string getDisplayOnPages()
 */

class Cloudiq_Callme_Model_Config extends Mage_Core_Model_Abstract {

    protected $_helper;

    protected function _construct() {
        $this->_init('cloudiq_callme/config');

        $this->_helper = Mage::helper("cloudiq_callme/options");

        $this->load(1);
    }

    /**
     * Validate attribute values, returning true if all fields pass validation or
     * a "field_name => message" array of error messages.
     *
     * @return bool|array
     */
    public function validate() {
        $errors = array();

        // Enabled 0/1
        if (!Zend_Validate::is($this->getEnabled(), "InArray", array("haystack" => array("0", "1")))) {
            $errors["Enabled"] = "Enabled must be set to 0 or 1.";
        }

        // Average sales values must be present
        if (!Zend_Validate::is(trim($this->getAverageSaleValue()), "NotEmpty")) {
            $errors["AverageSaleValue"] = "Average sale value can not be empty.";
        }

        // Average sales call must match a set of values defined in the API
        if (!Zend_Validate::is($this->getAverageSalesCall(), "InArray", array("haystack" => array_map(function ($el) { return $el["value"]; }, $this->_helper->getSalesCallOptions())))) {
            $errors["AverageSalesCall"] = "Invalid average sales call value.";
        }

        // Button Position must match a set of values defined in the API
        if (!Zend_Validate::is($this->getButtonPosition(), "InArray", array("haystack" => array_map(function ($el) { return $el["value"]; }, $this->_helper->getButtonPositionOptions())))) {
            $errors["ButtonPosition"] = "Invalid button position value.";
        }

        // Button Template must match a set of values defined in the API
        if (!Zend_Validate::is($this->getButtonTemplate(), "InArray", array("haystack" => array_map(function ($el) { return $el["value"]; }, $this->_helper->getButtonTemplateOptions())))) {
            $errors["ButtonTemplate"] = "Invalid button template value.";
        }

        // Button Colour must be a hex colour definition
        if (!Zend_Validate::is($this->getButtonColour(), "Regex", array("pattern" => "/^[0-9A-F]{6}$/i"))) {
            $errors["ButtonColour"] = "Button colour must be a hex colour definition.";
        }

        // Button Heading is required
        if (!Zend_Validate::is(trim($this->getButtonHeading()), "NotEmpty")) {
            $errors["ButtonHeading"] = "Button heading can not be empty.";
        }

        // Button Message is required
        if (!Zend_Validate::is(trim($this->getButtonMessage()), "NotEmpty")) {
            $errors["ButtonMessage"] = "Button message can not be empty.";
        }

        // Pop up logo enabled/disabled
        if (!Zend_Validate::is($this->getPopUpLogo(), "InArray", array("haystack" => array("0", "1")))) {
            $errors["PopUpLogo"] = "Pop up logo must be set to 0 or 1.";
        }

        // Pop up title is required
        if (!Zend_Validate::is(trim($this->getPopUpTitle()), "NotEmpty")) {
            $errors["PopUpTitle"] = "Pop up title can not be empty.";
        }

        // Pop up strapline is required
        if (!Zend_Validate::is(trim($this->getPopUpStrapline()), "NotEmpty")) {
            $errors["PopUpStrapline"] = "Pop up strap line can not be empty.";
        }

        // Pop up confirmations are required
        if (!Zend_Validate::is(trim($this->getPopUpConfOpen()), "NotEmpty")) {
            $errors["PopUpConfOpen"] = "Pop up confirmation (open) can not be empty.";
        }
        if (!Zend_Validate::is(trim($this->getPopUpConfClosed()), "NotEmpty")) {
            $errors["PopUpConfClosed"] = "Pop up confirmation (closed) can not be empty.";
        }

        // Callback phone number is required
        if (!Zend_Validate::is(trim($this->getCallbackCallcentreNumber()), "NotEmpty")) {
            $errors["CallbackCallcentreNumber"] = "Callback telephone number can not be empty.";
        }

        // DTMF Tunnel is numeric
        if (Zend_Validate::is(trim($this->getCallbackDtmfTunnel()), "NotEmpty") && !Zend_Validate::is(trim($this->getCallbackDtmfTunnel()), "Int")) {
            $errors["CallbackDtmfTunnel"] = "Callback DTMF tunnel can only contain numbers.";
        }

        // Call centre time zone must match one of the provided options
        if (!Zend_Validate::is(trim($this->getCallCentreTimeZone()), "InArray", array("haystack" => array_map(function ($el) { return $el["value"]; }, Mage::getSingleton("adminhtml/system_config_source_locale_timezone")->toOptionArray())))) {
            $errors["CallCentreTimeZone"] = "Invalid call centre time zone value.";
        }

        // All values in Opening Hours must be times
        foreach (array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday", "bank_holiday") as $day) {
            $openhours_errors = 0;
            foreach (array("from", "to") as $period) {
                $value = $this->getData($day . "_" . $period);
                if ($value !== "--" && !Zend_Validate::is($value, "Regex", array("pattern" => "/^\d{2}:\d{2}$/"))) {
                    $openhours_errors++;
                }
            }
            if ($openhours_errors > 0) {
                $errors[$day] = sprintf("%s Open Hours fields must contain a time value.", ucfirst($day));
            }
        }

        // Display pages values must match a defined set or be empty
        if (trim($this->getDisplayOnPages()) != "") {
            $displaypages_errors = 0;
            foreach (explode(",",$this->getDisplayOnPages()) as $page) {
                if (!Zend_Validate::is($page, "InArray", array("haystack" => array_map(function ($el) { return $el["value"]; }, $this->_helper->getPagesOptions())))) {
                    $displaypages_errors++;
                }
            }
            if ($displaypages_errors > 0) {
                $errors["DisplayOnPages"] = '"Display on selected pages" contains invalid values.';
            }
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    /**
     * Convert object attributes to an array with camelCase names
     *
     * @param  array $attributes array of required attributes
     * @return array
     */
    public function toCamelCaseArray(array $attributes = array()) {
        $data = $this->__toArray($attributes);
        $camelData = array();
        foreach ($data as $key => $value) {
            $camelData[lcfirst($this->_camelize($key))] = $value;
        }
        return $camelData;
    }

    /**
     * Check if the module is enabled and has been configured.
     *
     * @return bool
     */
    public function isConfigured() {
        $core_helper = Mage::helper("cloudiq_core/config");

        return ($core_helper->isEnabled() && $core_helper->hasBeenSetUp() && $this->getEnabled() && !is_null($this->getAppId()));
    }
}
