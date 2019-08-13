<?php
class Cloudiq_Callme_Block_Adminhtml_Config_Edit_Tab_Callme extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {
    /** @var $_helper Cloudiq_Callme_Helper_Data */
    protected $_helper;
    /** @var $_config_helper Cloudiq_Core_Helper_Config */
    protected $_config_helper;
    /** @var $_options_helper Cloudiq_Callme_Helper_Options */
    protected $_options_helper;
    /** @var $_config_model Cloudiq_Callme_Model_Config */
    protected $_config_model;
    /** @var $_input_data Varien_Object */
    protected $_input_data;

    public function __construct() {
        parent::__construct();

        $this->_helper = Mage::helper('cloudiq_core');
        $this->_config_helper = Mage::helper('cloudiq_core/config');

        $this->_options_helper = Mage::helper('cloudiq_callme/options');

        $this->_config_model = Mage::getModel('cloudiq_callme/config');

        $_input_data = Mage::getSingleton("adminhtml/session")->getData("callme_form_data", true);
        if ($_input_data && count($_input_data->getData()) > 0) {
            $this->_input_data = $_input_data;
        }
    }

    protected function _prepareForm() {
        $form = new Varien_Data_Form();

        $this->_addServiceFieldset($form);
        $this->_addButtonFieldset($form);
        $this->_addPopupFieldset($form);
        $this->_addCallbackFieldset($form);
        $this->_addPublishedFieldset($form);

        $this->setForm($form);
    }

    protected function _addPublishedFieldset($form) {
        $fieldset = $form->addFieldset('published', array('legend' => $this->_helper->__('Published')));

        $fieldset->addField('callme[published][all]', 'checkbox', array(
            'label' => $this->_helper->__('Display on all pages'),
            'title' => $this->_helper->__('Display on all pages'),
            'name' => 'callme[published][all]',
            'value' => true,
            'checked' => ($this->_input_data) ? $this->_input_data->getData("published/all") : $this->_config_model->getDisplayOnAll()
        ));

        $published_pages_value = $this->_config_model->getDisplayOnPages();
        if ($this->_input_data) {
            $published_pages_value = ($this->_input_data->getData("published/pages")) ? implode(",", $this->_input_data->getData("published/pages")) : "";
        }

        $fieldset->addField('callme[published][pages]', 'multiselect', array(
            'label' => $this->_helper->__('Display on selected pages'),
            'title' => $this->_helper->__('Display on selected pages'),
            'name' => 'callme[published][pages]',
            'note' => 'To select multiple pages hold the CTRL button (on Windows) or CMD button (on Mac) down on your keyboard.',
            'value' => $published_pages_value,
            'values' => $this->_options_helper->getPagesOptions(),
            'disabled' => ($this->_input_data) ? $this->_input_data->getData("published/all") : $this->_config_model->getDisplayOnAll()
        ));
    }

    protected function _addCallbackFieldset($form) {
        $fieldset = $form->addFieldset('callback', array('legend' => $this->_helper->__('Callback settings')));

        $fieldset->addField('callme[callback][number]', 'text', array(
            'label' => $this->_helper->__('Your number'),
            'title' => $this->_helper->__('Your number'),
            'name' => 'callme[callback][number]',
            'note' => 'Please enter a landline number in international format, i.e +44. This is the number called when the consumer makes a request. Once answered you will be connected to the consumer.',
            'value' => ($this->_input_data) ? $this->_input_data->getData("callback/number") : $this->_config_model->getCallbackCallcentreNumber(),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][dtmf]', 'text', array(
            'label' => $this->_helper->__('IVR Menu'),
            'title' => $this->_helper->__('IVR Menu'),
            'name'  => 'callme[callback][dtmf]',
            'note'  => 'Indicate which options should be selected to route the call to the right person if you have an IVR menu in place.',
            'value' => ($this->_input_data) ? $this->_input_data->getData("callback/dtmf") : $this->_config_model->getCallbackDtmfTunnel(),
        ));

        $fieldset->addField('callme[callback][time_zone]', 'select', array(
            'label'  => $this->_helper->__('Call centre time zone'),
            'title'  => $this->_helper->__('Call centre time zone'),
            'name'   => 'callme[callback][time_zone]',
            'value'  => ($this->_input_data) ? $this->_input_data->getData("callback/time_zone") : $this->_config_model->getCallCentreTimeZone(),
            'values' => Mage::getSingleton("adminhtml/system_config_source_locale_timezone")->toOptionArray()
        ));

        $fieldset->addField('callme[callback][open_hours_label]', 'label', array(
            'label' => $this->_helper->__('Open Hours'),
            'title' => $this->_helper->__('Open Hours'),
            'name' => 'callme[callback][open_hours_label]',
        ));

        $fieldset->addType('openinghours', 'Cloudiq_Callme_Varien_Data_Form_Element_Openinghours'); 

        $fieldset->addField('callme[callback][monday]', 'openinghours', array(
            'label' => $this->_helper->__('Monday'),
            'title' => $this->_helper->__('Monday'),
            'name' => 'callme[callback][monday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/monday")) : array(
                $this->_config_model->getMondayFrom(),
                $this->_config_model->getMondayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][tuesday]', 'openinghours', array(
            'label' => $this->_helper->__('Tuesday'),
            'title' => $this->_helper->__('Tuesday'),
            'name' => 'callme[callback][tuesday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/tuesday")) : array(
                $this->_config_model->getTuesdayFrom(),
                $this->_config_model->getTuesdayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][wednesday]', 'openinghours', array(
            'label' => $this->_helper->__('Wednesday'),
            'title' => $this->_helper->__('Wednesday'),
            'name' => 'callme[callback][wednesday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/wednesday")) : array(
                $this->_config_model->getWednesdayFrom(),
                $this->_config_model->getWednesdayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][thursday]', 'openinghours', array(
            'label' => $this->_helper->__('Thursday'),
            'title' => $this->_helper->__('Thursday'),
            'name' => 'callme[callback][thursday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/thursday")) : array(
                $this->_config_model->getThursdayFrom(),
                $this->_config_model->getThursdayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][friday]', 'openinghours', array(
            'label' => $this->_helper->__('Friday'),
            'title' => $this->_helper->__('Friday'),
            'name' => 'callme[callback][friday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/friday")) : array(
                $this->_config_model->getFridayFrom(),
                $this->_config_model->getFridayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][saturday]', 'openinghours', array(
            'label' => $this->_helper->__('Saturday'),
            'title' => $this->_helper->__('Saturday'),
            'name' => 'callme[callback][saturday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/saturday")) : array(
                $this->_config_model->getSaturdayFrom(),
                $this->_config_model->getSaturdayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][sunday]', 'openinghours', array(
            'label' => $this->_helper->__('Sunday'),
            'title' => $this->_helper->__('Sunday'),
            'name' => 'callme[callback][sunday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/sunday")) : array(
                $this->_config_model->getSundayFrom(),
                $this->_config_model->getSundayTo(),
            ),
            'required' => true,
        ));

        $fieldset->addField('callme[callback][bank_holiday]', 'openinghours', array(
            'label' => $this->_helper->__('Bank Holiday'),
            'title' => $this->_helper->__('Bank Holiday'),
            'name' => 'callme[callback][bank_holiday]',
            'value' => ($this->_input_data) ? array_values($this->_input_data->getData("callback/bank_holiday")) : array(
                $this->_config_model->getBankHolidayFrom(),
                $this->_config_model->getBankHolidayTo(),
            ),
            'required' => true,
        ));
    }

    protected function _addPopupFieldset($form) {
        $fieldset = $form->addFieldset('popup', array('legend' => $this->_helper->__('Popup Settings (Click the preview button to view changes)')));

        $fieldset->addField('callme[popup][logo]', 'select', array(
            'label'    => $this->_helper->__('Show company logo?'),
            'title'    => $this->_helper->__('Show company logo?'),
            'name'     => 'callme[popup][logo]',
            'value'    => ($this->_input_data) ? $this->_input_data->getData("popup/logo") : $this->_config_model->getPopUpLogo(),
            'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'required' => true,
        ));

        $fieldset->addField('callme[popup][title]', 'text', array(
            'label' => $this->_helper->__('Title'),
            'title' => $this->_helper->__('Title'),
            'name' => 'callme[popup][title]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("popup/title") : $this->_config_model->getPopUpTitle(),
            'required' => true,
        ));

        $fieldset->addField('callme[popup][strap_line]', 'text', array(
            'label' => $this->_helper->__('Strap line'),
            'title' => $this->_helper->__('Strap line'),
            'name' => 'callme[popup][strap_line]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("popup/strap_line") : $this->_config_model->getPopUpStrapline(),
            'required' => true,
        ));

        $fieldset->addField('callme[popup][confirmation_open]', 'text', array(
            'label' => $this->_helper->__('Confirmation - Open'),
            'title' => $this->_helper->__('Confirmation - Open'),
            'name' => 'callme[popup][confirmation_open]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("popup/confirmation_open") : $this->_config_model->getPopUpConfOpen(),
            'required' => true,
        ));

        $fieldset->addField('callme[popup][confirmation_closed]', 'text', array(
            'label' => $this->_helper->__('Confirmation - Closed'),
            'title' => $this->_helper->__('Confirmation - Closed'),
            'name' => 'callme[popup][confirmation_closed]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("popup/confirmation_closed") : $this->_config_model->getPopUpConfClosed(),
            'required' => true,
        ));
    }

    protected function _addButtonFieldset($form) {
        $fieldset = $form->addFieldset('button', array('legend' => $this->_helper->__('Button Settings (Please see the preview shown on this page)')));

        $fieldset->addField('callme[button][position]', 'select', array(
            'label' => $this->_helper->__('Position'),
            'title' => $this->_helper->__('Position'),
            'name' => 'callme[button][position]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("button/position") : $this->_config_model->getButtonPosition(),
            'values' => $this->_options_helper->getButtonPositionOptions(),
            'required' => true,
        ));

        $fieldset->addField('callme[button][template]', 'select', array(
            'label' => $this->_helper->__('Template'),
            'title' => $this->_helper->__('Template'),
            'name' => 'callme[button][template]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("button/template") : $this->_config_model->getButtonTemplate(),
            'values' => $this->_options_helper->getButtonTemplateOptions(),
            'required' => true,
        ));

        $fieldset->addType('colourpicker','Cloudiq_Callme_Varien_Data_Form_Element_Colourpicker');
        $fieldset->addField('callme[button][colour]', 'colourpicker', array(
            'label' => $this->_helper->__('Colour'),
            'title' => $this->_helper->__('Colour'),
            'name' => 'callme[button][colour]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("button/colour") : $this->_config_model->getButtonColour(),
            'required' => true,
        ));

        $fieldset->addField('callme[button][heading]', 'text', array(
            'label' => $this->_helper->__('Button heading'),
            'title' => $this->_helper->__('Button heading'),
            'name' => 'callme[button][heading]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("button/heading") : $this->_config_model->getButtonHeading(),
            'required' => true,
        ));

        $fieldset->addField('callme[button][message]', 'text', array(
            'label' => $this->_helper->__('Button message'),
            'title' => $this->_helper->__('Button message'),
            'name' => 'callme[button][message]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("button/message") : $this->_config_model->getButtonMessage(),
            'required' => true,
        ));

        $fieldset->addField('callme[button][preview_url]', 'hidden', array(
            'label' => $this->_helper->__('Preview'),
            'title' => $this->_helper->__('Preview'),
            'name'  => 'callme[button][preview]',
            'value' => Mage::helper("adminhtml")->getUrl('*/callme/preview'),
        ));
    }

    protected function _addServiceFieldset($form) {
        $fieldset = $form->addFieldset('service', array('legend' => $this->_helper->__('Service')));
        $fieldset->addField('callme[enabled]', 'select', array(
            'label' => $this->_helper->__('Enable?'),
            'title' => $this->_helper->__('Enable?'),
            'name' => 'callme[enabled]',
            'value' => ($this->_input_data) ? $this->_input_data->getData("enabled") : $this->_config_model->getEnabled(),
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray()
        ));

        $fieldset->addField('callme[service][average_sale_value]', 'text', array(
            'label'    => $this->_helper->__('Average sale value'),
            'title'    => $this->_helper->__('Average sale value'),
            'name'     => 'callme[service][average_sale_value]',
            'note'     => 'Please estimate the average value of a sale on your site. This is used for reporting.',
            'value'    => ($this->_input_data) ? $this->_input_data->getData("service/average_sale_value") : $this->_config_model->getAverageSaleValue(),
            'required' => true,
        ));

        $fieldset->addField('callme[service][average_sales_call]', 'select', array(
            'label'    => $this->_helper->__('Average sales call duration'),
            'title'    => $this->_helper->__('Average sales call duration'),
            'name'     => 'callme[service][average_sales_call]',
            'note'     => 'Please estimate how long a sales call would usually take. This is used for reporting.',
            'value'    => ($this->_input_data) ? $this->_input_data->getData("service/average_sales_call") : $this->_config_model->getAverageSalesCall(),
            'values'   => $this->_options_helper->getSalesCallOptions(),
            'required' => true,
        ));
    }

    public function getTabLabel() {
        return $this->__('callMe Settings');
    }

    public function getTabTitle() {
        return $this->__('callMe Settings');
    }

    public function canShowTab() {
        // Show this if we have an API token
        return $this->_config_helper->hasBeenSetUp();
    }

    public function isHidden() {
        // Hide this if we don't have an API token
        return !$this->_config_helper->hasBeenSetUp();
    }
}
