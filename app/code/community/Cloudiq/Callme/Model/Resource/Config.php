<?php

class Cloudiq_Callme_Model_Resource_Config extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('cloudiq_callme/config', 'id');
    }
}
