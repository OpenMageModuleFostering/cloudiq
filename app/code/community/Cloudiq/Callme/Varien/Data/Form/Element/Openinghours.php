<?php

class Cloudiq_Callme_Varien_Data_Form_Element_Openinghours extends Varien_Data_Form_Element_Abstract {

    protected $_helper;

    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        $this->setType("openinghours");

        $this->_helper = Mage::helper("cloudiq_callme/options");
    }

    public function getElementHtml() {
        $parts = array("from", "to");
        $values = $this->getValue();

        $this->addClass("opening_hours_select");
        $html = '<div id="' . $this->getHtmlId() . '" ' . $this->serialize($this->getHtmlAttributes()) . '>' . "\n";

        for ($i = 0; $i < count($parts); $i++) {
            $name = $this->getName() . "[" . $parts[$i] . "]";
            $html .= $this->_helper->__($parts[$i]);
            $html .= $this->getSelectHtml($name, $this->_helper->getHoursOptions(), $values[$i]);
        }

        $html .= "</div>\n";
        $html .= $this->getAfterElementHtml();
        return $html;
    }

    protected function getSelectHtml($name, $values, $selected) {
        $html = '<select name="' . $name . '">' . "\n";
        foreach ($values as $value) {
            $html .= '<option value="' . $value["value"] . '"' . (($value["value"] == $selected) ? ' selected="selected"' : '') . '>';
            $html .= $this->_helper->__($value["label"]);
            $html .= "</option>\n";
        }
        $html .= "</select>\n";
        return $html;
    }
}
