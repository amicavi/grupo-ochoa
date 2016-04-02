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

function upgrade_module_2_0_0($module_obj)
{
	$images_to_copy = glob(_PS_MODULE_DIR_.'/'.$module_obj->name.'/img/uploads/*');
	foreach ($images_to_copy as $img_path)
		if (basename($img_path) != 'index.php')
			Tools::copy($img_path, $module_obj->banners_dir_local.basename($img_path));

	$directories_to_remove = array('/css', '/js', '/img', '/views/templates/front');
	foreach ($directories_to_remove as $dir_path)
		recursiveRemove(_PS_MODULE_DIR_.$module_obj->name.$dir_path);

	$files_to_remove = array('/views/templates/admin/carousel-form.tpl');
	foreach ($files_to_remove as $file_path)
		if (file_exists(_PS_MODULE_DIR_.$module_obj->name.$file_path))
			unlink(_PS_MODULE_DIR_.$module_obj->name.$file_path);

	updateDB($module_obj->db);

	return true;
}

function updateDB($db_instance)
{
	$banners_data = $db_instance->executeS('
		SELECT * FROM '._DB_PREFIX_.'custombanners cb
		LEFT JOIN '._DB_PREFIX_.'custombanners_shop_lang cbsl ON cb.id_banner = cbsl.id_banner
	');

	$rows = array();
	foreach ($banners_data as $b)
	{
		$row = array();
		$row['id_banner'] = (int)$b['id_banner'];
		$row['id_shop'] = (int)$b['id_shop'];
		$row['id_lang'] = (int)$b['id_lang'];
		$row['hook_name'] = '\''.pSQL($b['hook_name']).'\'';
		$row['position'] = (int)$b['position'];
		$row['active'] = (int)$b['active'];
		$row['in_carousel'] = 0;
		$content = Tools::jsonDecode($b['banner_content'], true);
		if ($b['banner_img_name'])
			$content['img'] = $b['banner_img_name'];
		if (isset($content['link']) && $content['link'])
		{
			$new_version_link = array(
				'type' => 'custom',
				'href' => $content['link'],
			);
			if (isset($content['target_blank']))
			{
				$new_version_link['_blank'] = 1;
				unset($content['target_blank']);
			}
			$content['link'] = $new_version_link;
		}
		if (isset($content['in_carousel']))
		{
			if ($content['in_carousel'])
				$row['in_carousel'] = (int)$content['in_carousel'];
			unset($content['in_carousel']);
		}

		$content = Tools::jsonEncode($content);
		$row['content'] = '\''.pSQL($content, true).'\'';
		$rows[] = '('.implode(', ', $row).')';
	}
	$result = true;
	$result &= $db_instance->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'custombanners_tmp');
	$result &= $db_instance->execute('
		CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custombanners_tmp (
				id_banner int(10) unsigned NOT NULL,
				id_shop int(10) unsigned NOT NULL,
				id_lang int(10) unsigned NOT NULL,
				hook_name varchar(64) NOT NULL,
				position int(10) NOT NULL,
				active tinyint(1) NOT NULL,
				in_carousel tinyint(1) NOT NULL,
				content text NOT NULL,
				PRIMARY KEY (id_banner, id_shop, id_lang),
				KEY hook_name (hook_name),
				KEY active (active)
			  ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
	');

	if ($rows)
		$result &= $db_instance->execute('INSERT INTO '._DB_PREFIX_.'custombanners_tmp VALUES '.implode(', ', $rows));
	$new_banners_data = $db_instance->executeS('SELECT * FROM '._DB_PREFIX_.'custombanners_tmp');
	if (count($banners_data) == count($new_banners_data))
	{
		$result &= $db_instance->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'custombanners');
		$result &= $db_instance->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'custombanners_shop_lang');
		$result &= $db_instance->execute('RENAME TABLE '._DB_PREFIX_.'custombanners_tmp TO '._DB_PREFIX_.'custombanners');
	}
	else
		$result = false;
	return $result;
}

function recursiveRemove($dir, $keep_top_level = false)
{
	$files_to_keep = array();
	if ($keep_top_level)
		$files_to_keep = array('index.php');
	$structure = glob(rtrim($dir, '/').'/*');
	if (is_array($structure))
	{
		foreach ($structure as $file)
			if (is_dir($file))
				recursiveRemove($file);
			else if (is_file($file) && !in_array(basename($file), $files_to_keep))
				unlink($file);
	}
	if (!$keep_top_level && is_dir($dir))
		rmdir($dir);
}