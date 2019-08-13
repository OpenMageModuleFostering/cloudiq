<?php
class Cloudiq_Callme_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Update the provided config model with the data from the provided
     * form model. Used for easy translation between form field names and
     * config model property names. Updates the config model in place.
     *
     * @param Varien_Object $config Config object to update.
     * @param Varien_Object $form   Object containing form data.
     */
    public function updateConfigFromForm(Varien_Object &$config, Varien_Object $form) {
        $config->addData(array(
            "enabled" => $form->getData("enabled"),

            "average_sale_value" => $form->getData("service/average_sale_value"),
            "average_sales_call" => $form->getData("service/average_sales_call"),

            "button_position" => $form->getData("button/position"),
            "button_template" => $form->getData("button/template"),
            "button_colour"   => $form->getData("button/colour"),
            "button_heading"  => $form->getData("button/heading"),
            "button_message"  => $form->getData("button/message"),

            "pop_up_logo"        => $form->getData("popup/logo"),
            "pop_up_title"       => $form->getData("popup/title"),
            "pop_up_strapline"   => $form->getData("popup/strap_line"),
            "pop_up_conf_open"   => $form->getData("popup/confirmation_open"),
            "pop_up_conf_closed" => $form->getData("popup/confirmation_closed"),

            "callback_callcentre_number" => $form->getData("callback/number"),
            "callback_dtmf_tunnel"       => $form->getData("callback/dtmf"),
            "call_centre_time_zone"      => $form->getData("callback/time_zone"),

            "monday_from" => $form->getData("callback/monday/from"),
            "monday_to"   => $form->getData("callback/monday/to"),

            "tuesday_from" => $form->getData("callback/tuesday/from"),
            "tuesday_to"   => $form->getData("callback/tuesday/to"),

            "wednesday_from" => $form->getData("callback/wednesday/from"),
            "wednesday_to"   => $form->getData("callback/wednesday/to"),

            "thursday_from" => $form->getData("callback/thursday/from"),
            "thursday_to"   => $form->getData("callback/thursday/to"),

            "friday_from" => $form->getData("callback/friday/from"),
            "friday_to"   => $form->getData("callback/friday/to"),

            "saturday_from" => $form->getData("callback/saturday/from"),
            "saturday_to"   => $form->getData("callback/saturday/to"),

            "sunday_from" => $form->getData("callback/sunday/from"),
            "sunday_to"   => $form->getData("callback/sunday/to"),

            "bank_holiday_from" => $form->getData("callback/bank_holiday/from"),
            "bank_holiday_to"   => $form->getData("callback/bank_holiday/to"),

            "display_on_all"   => ($form->getData("published/all")) ? 1 : 0,
            "display_on_pages" => ($form->getData("published/pages")) ? implode(",", $form->getData("published/pages")) : ""
        ));
    }
}
