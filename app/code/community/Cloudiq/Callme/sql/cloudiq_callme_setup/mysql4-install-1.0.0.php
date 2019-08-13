<?php

$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE `{$installer->getTable('cloudiq_callme/config')}` (
        `id` INT(11) NOT NULL auto_increment,
        `enabled` BOOLEAN NOT NULL DEFAULT FALSE,
        `app_id` INT(11) DEFAULT NULL,
        `average_sale_value` VARCHAR(65) DEFAULT NULL,
        `average_sales_call` VARCHAR(16) DEFAULT '3',
        `button_position` VARCHAR(64) DEFAULT 'rightMiddle',
        `button_template` VARCHAR(65) DEFAULT NULL,
        `button_colour` VARCHAR(6) NOT NULL DEFAULT 'EB5E00',
        `button_heading` VARCHAR(64) DEFAULT 'Free Call Back',
        `button_message` VARCHAR(128) DEFAULT 'We\'d love to talk',
        `pop_up_logo` BOOLEAN DEFAULT FALSE,
        `pop_up_title` VARCHAR(64) DEFAULT 'Free Call Back',
        `pop_up_strapline` VARCHAR(256) DEFAULT 'Call back is a free service',
        `pop_up_conf_open` VARCHAR(512) DEFAULT 'Thank you. We\'re open and will be in touch soon',
        `pop_up_conf_closed` VARCHAR(512) DEFAULT 'Thank you. We\'re closed right now, but will call you when we open',
        `callback_callcentre_number` VARCHAR(13) DEFAULT NULL,
        `callback_dtmf_tunnel` VARCHAR(16) DEFAULT NULL,
        `call_centre_time_zone` VARCHAR(32) DEFAULT 'Europe/London',
        `monday_from` VARCHAR(5) DEFAULT '09:00',
        `monday_to` VARCHAR(5) DEFAULT '17:00',
        `tuesday_from` VARCHAR(5) DEFAULT '09:00',
        `tuesday_to` VARCHAR(5) DEFAULT '17:00',
        `wednesday_from` VARCHAR(5) DEFAULT '09:00',
        `wednesday_to` VARCHAR(5) DEFAULT '17:00',
        `thursday_from` VARCHAR(5) DEFAULT '09:00',
        `thursday_to` VARCHAR(5) DEFAULT '17:00',
        `friday_from` VARCHAR(5) DEFAULT '09:00',
        `friday_to` VARCHAR(5) DEFAULT '17:00',
        `saturday_from` VARCHAR(5) DEFAULT '09:00',
        `saturday_to` VARCHAR(5) DEFAULT '17:00',
        `sunday_from` VARCHAR(5) DEFAULT '09:00',
        `sunday_to` VARCHAR(5) DEFAULT '17:00',
        `bank_holiday_from` VARCHAR(5) DEFAULT '09:00',
        `bank_holiday_to` VARCHAR(5) DEFAULT '17:00',
        `display_on_all` BOOLEAN NOT NULL DEFAULT TRUE,
        `display_on_pages` TEXT DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `app_idx` (`app_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    -- Insert the default config values
    INSERT INTO `{$installer->getTable('cloudiq_callme/config')}` (`id`) VALUES (1);
");

$installer->endSetup();
