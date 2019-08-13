<?php
class Cloudiq_Callme_Varien_Data_Form_Element_Colourpicker extends Varien_Data_Form_Element_Abstract {

    public function __construct($attributes=array()) {
        parent::__construct($attributes);
        $this->setType('colourpicker');
    }

    public function getElementHtml() {
        $html = sprintf('<input id="%s" name="%s" class="color input-text" value="%s"/>',
            $this->getHtmlId(),
            $this->getName(),
            $this->getEscapedValue()
        );
        return $html;
    }


}
