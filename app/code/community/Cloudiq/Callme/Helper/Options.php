<?php

class Cloudiq_Callme_Helper_Options extends Mage_Core_Helper_Abstract {

    const DAY_START_HOUR = 7;
    const DAY_START_MINUTE = 0;
    const DAY_END_HOUR = 23;
    const DAY_END_MINUTE = 30;
    const MINUTE_INCREMENT = 15;

    public function getButtonPositionOptions() {
        return array(
            array("value" => "leftBottom", "label" => $this->__("Left Bottom")),
            array("value" => "leftMiddle", "label" => $this->__("Left Middle")),
            array("value" => "leftTop", "label" => $this->__("Left Top")),
            array("value" => "topLeft", "label" => $this->__("Top Left")),
            array("value" => "topMiddle", "label" => $this->__("Top Middle")),
            array("value" => "topRight", "label" => $this->__("Top Right")),
            array("value" => "rightTop", "label" => $this->__("Right Top")),
            array("value" => "rightMiddle", "label" => $this->__("Right Middle")),
            array("value" => "rightBottom", "label" => $this->__("Right Bottom")),
            array("value" => "bottomRight", "label" => $this->__("Bottom Right")),
            array("value" => "bottomMiddle", "label" => $this->__("Bottom Middle")),
            array("value" => "bottomLeft", "label" => $this->__("Bottom Left")),
        );
    }

    public function getButtonTemplateOptions() {
        return array(
            array("value" => "1", "label" => $this->__("Squared corners no fill")),
            array("value" => "2", "label" => $this->__("Squared corners with fill")),
            array("value" => "3", "label" => $this->__("Lozenge with fill")),
            array("value" => "4", "label" => $this->__("Rounded corners no fill")),
            array("value" => "5", "label" => $this->__("Rounded corners with fill")),
        );
    }

    public function getHoursOptions() {
        $values = array();

        $values[] = array("value" => "--", "label" => "--");

        for ($i = self::DAY_START_HOUR; $i <= self::DAY_END_HOUR; $i++) {
            for ($j = 0; $j < 60; $j = $j + self::MINUTE_INCREMENT) {
                if (($i == self::DAY_START_HOUR && $j < self::DAY_START_MINUTE)
                    || ($i == self::DAY_END_HOUR && $j > self::DAY_END_MINUTE)) {
                    continue;
                }
                $value = (($i < 10) ? "0" . $i : $i) . ":" . (($j < 10) ? "0" . $j : $j);
                $values[] = array("value" => $value, "label" => $this->__($value));
            }
        }
        return $values;
    }

    public function getPagesOptions() {
        return array(
            array("value" => "cms_index_index", "label" => $this->__("Home page")),
            array("value" => "cms_page", "label" => $this->__("All CMS pages")),
            array("value" => "catalog_category_view", "label" => $this->__("Category pages")),
            array("value" => "catalog_product_view", "label" => $this->__("Product pages")),
            array("value" => "catalogsearch_result_index", "label" => $this->__("Search page")),
            array("value" => "checkout_cart_index", "label" => $this->__("Cart page")),
            array("value" => "checkout_onepage_index", "label" => $this->__("Checkout page")),
            array("value" => "customer_account", "label" => $this->__("Account Area pages")),
        );
    }

    public function getSalesCallOptions() {
        return array(
            array("value" => "1", "label" => $this->__("1 minutes")),
            array("value" => "2", "label" => $this->__("2 minutes")),
            array("value" => "3", "label" => $this->__("3 minutes")),
            array("value" => "4", "label" => $this->__("4 minutes")),
            array("value" => "5", "label" => $this->__("5 minutes or more")),
        );
    }
}
