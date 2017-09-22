<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Sso
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('sso')} (
	`id` int(11) unsigned NOT NULL auto_increment,
	`name` text NOT NULL,
	`cancel_url` text,
	`client_id` text NOT NULL,
	`secret_key` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS {$this->getTable('ssoauth')} (
	`id` int(11) unsigned NOT NULL auto_increment,
	`auth_code` text NOT NULL,
	`name` text NOT NULL,
    `email` text NOT NULL,
	`ip_address` text NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
");
$installer->endSetup();
