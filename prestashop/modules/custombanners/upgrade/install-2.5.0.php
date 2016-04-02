<?php
/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_2_5_0($module_obj)
{
	$files_to_remove = array(
		'/views/templates/admin/exceptions-settings-form.tpl',
		'/views/templates/admin/carousel-settings-form.tpl',
		'/views/templates/admin/positions-settings-form.tpl'
	);
	foreach ($files_to_remove as $file_path)
		if (file_exists(_PS_MODULE_DIR_.$module_obj->name.$file_path))
			unlink(_PS_MODULE_DIR_.$module_obj->name.$file_path);

	if ($module_obj->db->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.'cb_carousel_settings\'')
		&& !$module_obj->db->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.'cb_hook_settings\''))
	{
		$module_obj->db->execute('
			RENAME TABLE '._DB_PREFIX_.'cb_carousel_settings TO '._DB_PREFIX_.'cb_hook_settings
		');
		$columns = $module_obj->db->executeS('SHOW COLUMNS FROM '._DB_PREFIX_.'cb_hook_settings');
		foreach ($columns as $c)
			if ($c['Field'] == 'settings')
				$module_obj->db->execute('ALTER TABLE '._DB_PREFIX_.'cb_hook_settings CHANGE settings carousel text NOT NULL');
	}

	return true;
}