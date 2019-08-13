<?php
class Cloudiq_Callme_Test_Config_Base extends EcomDev_PHPUnit_Test_Case_Config {
    /**
     * @test
     */
    public function testBasicConfiguration() {
        $this->assertModuleCodePool('community');
        $this->assertModuleVersion('1.0.0');
    }

    /**
     * @test
     */
    public function testClassAliases() {
        $this->assertHelperAlias('cloudiq_callme', 'Cloudiq_Callme_Helper_Data');
        $this->assertModelAlias('cloudiq_callme/test', 'Cloudiq_Callme_Model_Test');
        $this->assertBlockAlias('cloudiq_callme/test', 'Cloudiq_Callme_Block_Test');
    }

    /**
     * @test
     */
    public function testDataHelperExists() {
        $this->assertInstanceOf('Cloudiq_Callme_Helper_Data', Mage::helper('cloudiq_callme'));
    }
}
