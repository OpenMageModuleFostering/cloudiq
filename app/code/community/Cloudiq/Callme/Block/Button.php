<?php

class Cloudiq_Callme_Block_Button extends Mage_Core_Block_Template {

    const GRADIENT_COLOUR_STEP = 50;

    public function _construct() {
        parent::_construct();

        $this->setArea("frontend");
        $this->setTemplate("cloudiq/callme/button.phtml");
    }

    /**
     * Return the config model.
     *
     * @return Varien_Object
     */
    public function getConfig() {
        if (is_null($this->getData("config"))) {
            $this->setData("config", Mage::getModel("cloudiq_callme/config"));
        }

        return $this->getData("config");
    }

    /**
     * Fetch callMe button properties from the configuration.
     *
     * @param $key string The property to fetch.
     *
     * @return mixed
     */
    protected function _getFromConfig($key) {
        return $this->getConfig()->getData($key);
    }

    /**
     * Return the css class representing the selected callMe template.
     *
     * @return string
     */
    protected function _getTemplateClass() {
        return "cloudiq-callme-template-" . strtolower($this->_getFromConfig("button_template"));
    }

    /**
     * Return the class string for the callMe button based on the config.
     *
     * @return string
     */
    protected function _getButtonClass() {
        $classes = array();
        // Apply the button template
        $classes[] = $this->_getTemplateClass();
        // Position the button
        $classes[] = "cloudiq-callme-position-" . strtolower($this->_getFromConfig("button_position"));
        return implode(" ", $classes);
    }

    /**
     * Return the class string for the callMe pop up based on the config.
     *
     * @return string
     */
    protected function _getPopupClass() {
        $classes = array();
        // Apply the template
        $classes[] = $this->_getTemplateClass();

        return implode(" ", $classes);
    }

    /**
     * Return user selected colour for the button in hex form.
     *
     * @return string
     */
    protected function _getColour() {
        return $this->_getFromConfig("button_colour");
    }

    /**
     * Return the starting colour for a gradient based on user selected button colour.
     *
     * @return string
     */
    protected function _getGradientStartColour() {
        $base_colour = $this->_getColour();
        $colour = "";
        foreach (array(0, 2, 4) as $pos) {
            $rgb_val = hexdec(substr($base_colour, $pos, 2));
            $rgb_val = max(0, min(255, $rgb_val + (self::GRADIENT_COLOUR_STEP / 2)));
            $colour .= str_pad(dechex($rgb_val), 2, "0", STR_PAD_LEFT);
        }
        return $colour;
    }

    /**
     * Return the ending colour for a gradient based on user selected button colour.
     *
     * @return string
     */
    protected function _getGradientEndColour() {
        $base_colour = $this->_getColour();
        $colour = "";
        foreach (array(0, 2, 4) as $pos) {
            $rgb_val = hexdec(substr($base_colour, $pos, 2));
            $rgb_val = max(0, min(255, $rgb_val - (self::GRADIENT_COLOUR_STEP / 2)));
            $colour .= str_pad(dechex($rgb_val), 2, "0", STR_PAD_LEFT);
        }
        return $colour;
    }

    /**
     * Return black or white to contrast the given colour.
     *
     * @param $colour string Colour to contrast in hex form.
     *
     * @return string
     */protected function _getContrastColourBW($colour) {
        // Count the perceptive luminance (human eye favours green colour)
        // Source: http://stackoverflow.com/questions/1855884/determine-font-color-based-on-background-color
        $alpha = 1 - (0.299 * hexdec(substr($colour, 0, 2)) + 0.587 * hexdec(substr($colour, 2, 2)) + 0.114 * hexdec(substr($colour, 4, 2))) / 255;

        if ($alpha < 0.5) {
            // Bright colour, return black to contrast
            return "000000";
        } else {
            return "FFFFFF";
        }
    }

    /**
     * Return the submit URL for the callMe callback request form.
     *
     * @return string
     */
    protected function _getFormUrl() {
        $params = array();
        if (Mage::app()->getStore()->isCurrentlySecure()) {
            // The generated URL should automatically be secure if the "Use secure URLs in Frontend" setting is
            // set in the configuration. However, even if it's not, the page can be accessed over HTTPS if the
            // right base url is set, but in that case the URL generated here will be HTTP and the Ajax submit
            // will fail. Therefore, force a secure URL to be generated if the request was secure.
            $params['_forced_secure'] = true;
        }
        return $this->getUrl('cloudiq/callme/request', $params);
    }

    /**
     * Return the logo to be displayed on the popup if the logo has been enabled.
     *
     * @return string Full URL to the logo or an empty string.
     */
    protected function _getLogoUrl() {
        return ($this->_getFromConfig('pop_up_logo')) ? $this->getSkinUrl(Mage::getStoreConfig('design/header/logo_src'), array("_area" => "frontend")) : "";
    }
}
