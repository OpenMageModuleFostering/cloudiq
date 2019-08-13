<?php

class Cloudiq_Callme_Test_Model_Config extends EcomDev_PHPUnit_Test_Case {

    /** @var Cloudiq_Callme_Model_Config */
    protected $_model;

    protected function setUp() {
        parent::setUp();

        $this->_model = Mage::getModel('cloudiq_callme/config');
    }

    protected function tearDown() {
        $this->_model = null;
    }

    /**
     * @test
     */
    public function testClassConstruction() {
        $this->assertInstanceOf('Cloudiq_Callme_Model_Config', $this->_model);
    }

    /**
     * @test
     * @loadFixture validConfigObject.yaml
     */
    public function testModelIsLoadedAfterConstruct() {
        /**
         * Test the fixture loaded
         */
        $this->assertGreaterThan(0, $this->_model->getCollection()->count());

        $this->assertEquals(1, $this->_model->getId());
    }

    /**
     * @test
     * @depends testModelIsLoadedAfterConstruct
     * @loadFixture validConfigObject.yaml
     */
    public function testFixtureIsValid() {
        $this->assertTrue($this->_model->validate());
    }

    /**
     * @test
     * @depends testFixtureIsValid
     * @dataProvider dataProviderSingleValue
     * @loadFixture validConfigObject.yaml
     */
    public function testRequiredField($fieldname) {
        $this->_model->unsetData($fieldname);

        $validation_result = $this->_model->validate();

        $this->assertThat($validation_result, $this->logicalNot($this->equalTo(true)));
        $this->assertTrue(is_array($validation_result));
        $this->assertEquals(1, count($validation_result));
    }

    /**
     * @test
     * @depends testFixtureIsValid
     * @dataProvider dataProviderSingleValue
     * @loadFixture validConfigObject.yaml
     */
    public function testOptionalField($fieldname) {
        $this->_model->unsetData($fieldname);

        $validation_result = $this->_model->validate();

        $this->assertTrue($validation_result);
    }

    /**
     * The standard dataProvider requires an array of arrays in the yaml file.  This data provider allows us to just
     * use an array and, therefore, assumes that the test method using this dataProviderSingleValue accepts only one
     * argument.
     *
     * @param $testname
     * @return array
     */
    public function dataProviderSingleValue($testname) {
        $raw_data = parent::dataProvider($testname);
        $data = array();

        foreach ($raw_data as $item) {
            $data[] = array(
                $item
            );
        }

        return $data;
    }
}
