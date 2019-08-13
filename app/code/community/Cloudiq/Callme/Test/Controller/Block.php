<?php

class Cloudiq_Callme_Test_Controller_Block extends EcomDev_PHPUnit_Test_Case_Controller {

    /**
     * @test
     *
     * @loadFixture
     * @dataProvider dataProvider
     */
    public function testDisplayOnAllPages($path, $handle) {
        $this->dispatch($path);
        $this->assertModuleConfigured();
        $this->assertLayoutHandleLoaded($handle);
        $this->assertLayoutBlockRendered('cloudiq_callme.button');
    }

    /**
     * @test
     *
     * @loadFixture
     */
    public function testCallmeDisabled() {
        $this->dispatch('');
        $this->assertModuleConfigured();
        $this->assertLayoutHandleLoaded('cms_index_index');
        $this->assertLayoutBlockNotRendered('cloudiq_callme.button');
    }

    /**
     * @test
     *
     * @loadFixture
     */
    public function testCoreDisabled() {
        $this->dispatch('');
        $this->assertModuleConfigured();
        $this->assertLayoutHandleLoaded('cms_index_index');
        $this->assertLayoutBlockNotRendered('cloudiq_callme.button');
    }

    public function assertModuleConfigured() {
        $this->assertNotNull(Mage::helper('cloudiq_core/config')->isEnabled(), 'Expected core module to be set');
        $this->assertTrue(Mage::helper('cloudiq_core/config')->hasBeenSetUp(), 'Expected core module to be setup correctly');
    }
}
