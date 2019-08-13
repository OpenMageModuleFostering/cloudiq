<?php

class Cloudiq_Callme_Test_Helper_Options extends EcomDev_PHPUnit_Test_Case {
    /** @var Cloudiq_Callme_Helper_Options */
    protected $_helper;

    protected function setUp() {
        parent::setUp();

        $this->_helper = Mage::helper('cloudiq_callme/options');
    }

    protected function tearDown() {
        $this->_helper = null;
    }

    /**
     * @test
     */
    public function testClassConstruction() {
        $this->assertInstanceOf('Cloudiq_Callme_Helper_Options', $this->_helper);
    }

    /**
     * @test
     */
    public function testGetButtonPositionOptionsValidSourceModel() {
        $this->_assertValidSourceModel($this->_helper->getButtonPositionOptions());
    }

    /**
     * @test
     */
    public function testGetButtonTemplateOptionsValidSourceModel() {
        $this->_assertValidSourceModel($this->_helper->getButtonTemplateOptions());
    }

    /**
     * @test
     */
    public function testGetHoursOptionsValidSourceModel() {
        $this->_assertValidSourceModel($this->_helper->getHoursOptions());
    }

    /**
     * @test
     */
    public function testGetPagesOptionsValidSourceModel() {
        $this->_assertValidSourceModel($this->_helper->getPagesOptions());
    }

    protected function _assertValidSourceModel($array) {
        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) > 0);

        foreach ($array as $child) {
            $this->assertArrayHasKey('label', $child);
            $this->assertArrayHasKey('value', $child);
        }
    }
}
