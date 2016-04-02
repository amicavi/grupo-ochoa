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

class CustomBanners extends Module
{
	public function __construct()
	{
		$this->name = 'custombanners';
		$this->tab = 'front_office_features';
		$this->version = '2.5.3';
		$this->author = 'Amazzing';
		$this->need_instance = 0;
		$this->bootstrap = true;
		$this->module_key = '89d38a87bea7e9c6b04e6c77b9cef1cf';

		parent::__construct();

		$this->displayName = $this->l('Custom banners');
		$this->description = $this->l('Add images, videos or other custom HTML content anywhere on your site');

		$this->banners_dir = $this->_path.'views/img/uploads/';
		$this->banners_dir_local = $this->local_path.'views/img/uploads/';

		$this->db = Db::getInstance();
	}

	public function install()
	{
		if (!$this->prepareDatabase())
		{
			$this->context->controller->errors[] = $this->l('Database table was not installed properly');
			return false;
		}

		if (!parent::install()
			|| !$this->registerHook('displayBackofficeHeader')
			|| !$this->registerHook('displayHeader'))
			return false;

		$this->prepareContent();

		// defined during reset in uninstall() part
		if (isset($this->reserve_hook_data))
		{
			// changing shop context is required for updating positions in other shops
			$original_shop_context = Shop::getContext();
			$original_shop_context_id = null;
			if ($original_shop_context == Shop::CONTEXT_GROUP)
				$original_shop_context_id = $this->context->shop->id_shop_group;
			else if ($original_shop_context == Shop::CONTEXT_SHOP)
				$original_shop_context_id = $this->context->shop->id;

			if (isset($this->reserve_hook_data['positions']))
			{
				foreach ($this->reserve_hook_data['positions'] as $id_shop => $hook_list)
					foreach ($hook_list as $hook_name => $cb_position)
					{
						if ($id_shop != $this->context->shop->id)
						{
							Cache::clean('hook_module_list');
							Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
						}

						$id_hook = Hook::getIdByName($hook_name);
						$this->registerHook($hook_name, array($id_shop));
						$this->updatePosition($id_hook, 0, $cb_position);

						if (isset($this->reserve_hook_data['exceptions'][$id_shop][$hook_name]))
						{
							$except = $this->reserve_hook_data['exceptions'][$id_shop][$hook_name];
							$this->registerExceptions($id_hook, $except, array($id_shop));
						}
					}
			}
			Shop::setContext($original_shop_context, $original_shop_context_id);
		}

		return true;
	}

	public function prepareDatabase()
	{
		$sql = array();

		$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'custombanners (
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
				  ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

		$sql[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'cb_hook_settings (
					hook_name varchar(64) NOT NULL,
					id_shop int(10) unsigned NOT NULL,
					carousel text NOT NULL,
					PRIMARY KEY (hook_name, id_shop)
				  ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

		return $this->runSql($sql);
	}

	public function prepareContent()
	{
		$this->shop_ids = Shop::getContextListShopID();
		if (file_exists($this->local_path.'defaults/data.zip'))
			$this->importBannersData($this->local_path.'defaults/data.zip');
	}

	public function uninstall()
	{
		$this->shop_ids = Shop::getContextListShopID();
		$dropped_tables = array();
		foreach (array('custombanners', 'cb_hook_settings') as $table)
		{
			$this->db->execute('
				DELETE FROM '._DB_PREFIX_.pSQL($table).'
				WHERE id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')');
			if ($table == 'custombanners')
				$this->deleteShopContextImages($this->shop_ids);
			if (!$this->db->getRow('SELECT * FROM '._DB_PREFIX_.pSQL($table)))
			{
				$this->db->execute('DROP TABLE '._DB_PREFIX_.pSQL($table));
				$dropped_tables[$table] = 1;
			}
		}

		// keeping hook data for shops that are out of context during reset
		if (!isset($dropped_tables['custombanners']))
		{
			$result = $this->db->executeS('
				SELECT cb.hook_name, cb.id_shop, hm.position
				FROM '._DB_PREFIX_.'custombanners cb
				LEFT JOIN '._DB_PREFIX_.'hook h ON cb.hook_name = h.name
				LEFT JOIN '._DB_PREFIX_.'hook_module hm ON h.id_hook = hm.id_hook
				WHERE hm.id_module = '.(int)$this->id.'
				AND cb.id_shop NOT IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
			');
			$hook_positions = array();
			foreach ($result as $r)
				$hook_positions[$r['id_shop']][$r['hook_name']] = $r['position'];

			$result = $this->db->executeS('
				SELECT hme.*, h.name AS hook_name
				FROM '._DB_PREFIX_.'hook_module_exceptions hme
				LEFT JOIN '._DB_PREFIX_.'hook h ON h.id_hook = hme.id_hook
				WHERE id_module = '.(int)$this->id.'
				AND hme.id_shop NOT IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
			');
			$hook_exceptions = array();
			foreach ($result as $r)
				$hook_exceptions[$r['id_shop']][$r['hook_name']][] = $r['file_name'];
			$this->reserve_hook_data = array('positions' => $hook_positions, 'exceptions' => $hook_exceptions);
			// file_put_contents($this->local_path.'defaults/reserve_hook_data.txt', Tools::jsonEncode($this->reserve_hook_data));
		}

		if (!parent::uninstall())
			return false;
		return true;
	}

	public function runSql($sql)
	{
		foreach ($sql as $s)
			if (!$this->db->execute($s))
				return false;
		return true;
	}

	public function getAvailableHooks()
	{
		$methods = get_class_methods(__CLASS__);
		$methods_to_exclude = array('hookDisplayBackOfficeHeader');
		$available_hooks = array();
		foreach ($methods as $m)
			if (Tools::substr($m, 0, 11) === 'hookDisplay' && !in_array($m, $methods_to_exclude))
				$available_hooks[str_replace('hookDisplay', 'display', $m)] = 0;
		ksort($available_hooks);
		return $available_hooks;
	}

	public function hookDisplayBackOfficeHeader()
	{
		if (Tools::getValue('configure') != $this->name)
			return;
		$this->context->controller->addJquery();
		$this->context->controller->addCSS($this->_path.'views/css/back.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/common-classes.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/back.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js');
		if (file_exists(_PS_ROOT_DIR_.'/js/tinymce.inc.js'))
			$this->context->controller->addJS(__PS_BASE_URI__.'js/tinymce.inc.js');
		else
			$this->context->controller->addJS(__PS_BASE_URI__.'js/admin/tinymce.inc.js');
		$this->context->controller->addJqueryUI('ui.sortable');
	}

	public function getContent()
	{
		$this->failed_txt = $this->l('Failed');
		$this->saved_txt = $this->l('Saved');
		$this->shop_ids = Shop::getContextListShopID();

		if (Tools::isSubmit('ajax') && Tools::isSubmit('action'))
		{
			$action_method = 'ajax'.Tools::getValue('action');
			$this->$action_method();
		}

		$iso = $this->context->language->iso_code;
		// Plain js for retro-compatibility. Media::addJsDef for BO works since 1.6.0.11
		$html = '
			<script type="text/javascript">
				var iso = \''.(file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'\';
				var pathCSS = \''._THEME_CSS_DIR_.'\';
				var ad = \''.dirname($_SERVER['PHP_SELF']).'\';
				var failedTxt = \''.$this->failed_txt.'\';
				var savedTxt = \''.$this->saved_txt.'\';
				var areYouSureTxt = \''.$this->l('Are you sure?').'\';
			</script>
		';
		if (Tools::getValue('action') == 'exportBannersData')
			$html .= $this->exportBannersData();
		if (Tools::getValue('action') == 'importBannersData')
		{
			$this->importBannersData();
			$html .= $this->import_response;
		}
		$html .= $this->displayForm();
		return $html;
	}

	private function displayForm()
	{
		$banners = $this->getAllBannersData();
		$hooks = $this->getAvailableHooks();

		$sorted_hooks = array();
		foreach (array_keys($banners) as $hook_name)
			$sorted_hooks[$hook_name] = count($banners[$hook_name]);
		arsort($sorted_hooks);

		foreach ($hooks as $hook_name => $count)
			if (!isset($sorted_hooks[$hook_name]))
				$sorted_hooks[$hook_name] = $count;

		$custom_files = array(
			'css' => $this->l('Add custom CSS'),
			'js' => $this->l('Add custom JS'),
		);

		$this->context->smarty->assign(array(
			'banners' => $banners,
			'hooks' => $sorted_hooks,
			'input_fields' => $this->getBannerFieldNames(),
			'bs_classes' => $this->getBSClasses(),
			'languages' => Language::getLanguages(false),
			'id_lang_current' => $this->context->language->id,
			'iso_lang_current' => $this->context->language->iso_code,
			'multishop_note' => count(Shop::getContextListShopID()) > 1,
			'magic_quotes_warning' => _PS_MAGIC_QUOTES_GPC_,
			'custom_files' => $custom_files,
		));

		$form_html = $this->display($this->local_path, 'views/templates/admin/configure.tpl');

		$this->context->controller->modals[] = array(
			'modal_id' => 'modal_importer_info',
			'modal_class' => 'modal-md',
			'modal_title' => '<i class="icon-file-zip-o"></i> '.$this->l('How to use the importer'),
			'modal_content' => $this->display($this->local_path, 'views/templates/admin/importer-how-to.tpl'),
		);

		foreach ($custom_files as $type => $name)
		{
			$file_path = $this->local_path.'views/'.$type.'/custom/shop'.$this->context->shop->id.'.'.$type;
			$code = file_exists($file_path) ? Tools::file_get_contents($file_path) : '';
			$shop_names = array();
			if (Shop::isFeatureActive())
				foreach (Shop::getContextListShopID() as $id_shop)
					$shop_names[] = $this->db->getValue('SELECT name FROM '._DB_PREFIX_.'shop WHERE id_shop = '.(int)$id_shop);
			$shop_names = implode(', ', $shop_names);
			$this->context->smarty->assign(array(
				'type' => $type,
				'code' => $code,
				'shop_names' => $shop_names,
			));
			$this->context->controller->modals[] = array(
				'modal_id' => 'custom-'.$type.'-form',
				'modal_class' => 'modal-lg file-form',
				'modal_title' => $name,
				'modal_content' => $this->display($this->local_path, 'views/templates/admin/custom-file-form.tpl'),
			);
		}
		return $form_html;
	}

	public function getBannerFieldNames()
	{
		$fields = array(
			'img' => array(
				'display_name' => $this->l('Image'),
			),
			'title' => array(
				'display_name' => $this->l('Title'),
				'tooltip' => $this->l('Displayed next to cursor on hovering the image'),
			),
			'link' => array(
				'display_name' => $this->l('Link'),
				'selector' =>  array(
					'custom' => $this->l('Custom link'),
					'Product' => $this->l('Link to product'),
					'Category' => $this->l('Link to Category'),
					'Manufacturer' => $this->l('Link to Manufacturer'),
					'Supplier' => $this->l('Link to Supplier'),
					'CMS' => $this->l('Link to CMS page'),
					'CMSCategory' => $this->l('Link to CMS category'),
				),
			),
			'html' => array(
				'display_name' => $this->l('HTML'),
			),
			'class' => array(
				'display_name' => $this->l('Custom class'),
				'all_langs' => 1,
			),
			'restricted' => array(
				'display_name' => $this->l('Restrictions'),
				'tooltip' => $this->l('Display banner only for selected products/categories'),
				'selector' => array(
						'product' => $this->l('By products'),
						'category' => $this->l('By categories'),
						'manufacturer' => $this->l('By manufacturers'),
						'supplier' => $this->l('By suppliers'),
						'cms' => $this->l('By CMS pages'),
					),
				'all_langs' => 1,
			),
		);
		return $fields;
	}

	public function getBSClasses()
	{
		$classes = array(
			'lg' => $this->l('displays, wider than 1199px'),
			'md' => $this->l('displays, wider than 991px'),
			'sm' => $this->l('displays, wider than 767px'),
			'xs' => $this->l('displays, wider than 479px'),
			'xxs' => $this->l('displays, narrower than 480px'),
		);
		return $classes;
	}

	public function exportBannersData()
	{
		$languages = Language::getLanguages(false);
		$lang_id_iso = array();
		foreach ($languages as $lang)
			$lang_id_iso[$lang['id_lang']] = $lang['iso_code'];

		$id_shop_default = Configuration::get('PS_SHOP_DEFAULT');
		$id_lang_default = Configuration::get('PS_LANG_DEFAULT');
		$tables_to_export = array(
			'custombanners',
			'cb_hook_settings',
			'hook_module_exceptions',
			'hook_module'
		);
		$export_data = array();
		foreach ($tables_to_export as $table_name)
		{
			$data_from_db = $this->db->executeS('SELECT * FROM '._DB_PREFIX_.pSQL($table_name));
			$ret = $data_from_db;
			switch ($table_name)
			{
				case 'custombanners':
					$ret = array();
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$lang_iso = $d['id_lang'] == $id_lang_default ? 'LANG_ISO_DEFAULT' : $lang_id_iso[$d['id_lang']];
						$ret[$id_shop][$d['id_banner']][$lang_iso] = $d;
					}
				break;
				case 'cb_hook_settings':
					$ret = array();
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$ret[$id_shop] = $d;
					}
				break;
				case 'hook_module_exceptions':
					$data_from_db = $this->db->executeS('
						SELECT hme.*, h.name AS hook_name
						FROM '._DB_PREFIX_.'hook_module_exceptions hme
						LEFT JOIN '._DB_PREFIX_.'hook h ON h.id_hook = hme.id_hook
						WHERE id_module = '.(int)$this->id.'
					');
					$ret = array();
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$ret[$id_shop][$d['hook_name']][] = $d['file_name'];
					}
				break;
				case 'hook_module':
					$ret = array();
					foreach ($data_from_db as $d)
					{
						if ($d['id_module'] != $this->id)
							continue;
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$hook_name = Hook::getNameByid($d['id_hook']);
						$ret[$id_shop][$hook_name] = $d['position'];
					}
				break;
			}
			$export_data[$table_name] = $ret;
		}

		$tmp_zip_file = tempnam($this->local_path.'tmp', 'zip');
		$zip = new ZipArchive();
		$zip->open($tmp_zip_file, ZipArchive::OVERWRITE);
		$zip->addFromString('data.txt', Tools::jsonEncode($export_data));

		$banner_folder_contents = glob($this->banners_dir_local.'*');
		foreach ($banner_folder_contents as $file)
			if (is_file($file) && basename($file) != 'index.php')
				$zip->addFile($file, 'img/'.basename($file));

		foreach (array('css', 'js') as $type)
			foreach (glob($this->local_path.'views/'.$type.'/custom/*') as $file)
			{
				$filename = basename($file, '.'.$type);
				if (!is_file($file) || strpos($filename, 'shop') === false)
					continue;
				$id_shop = str_replace('shop', '', $filename);
				if (!Validate::isInt($id_shop) || $id_shop < 1)
					continue;
				if ($id_shop == $id_shop_default)
					$id_shop = 'id_shop_default';
				$zip->addFile($file, $type.'/shop'.$id_shop.'.'.$type);
			}

		$zip->close();
		$archive_name = 'backup-'.date('d-m-Y').'.zip';
		header('Content-Type: application/zip');
		header('Content-Length: '.filesize($tmp_zip_file));
		header('Content-Disposition: attachment; filename="'.$archive_name.'"');
		readfile($tmp_zip_file);
		unlink($tmp_zip_file);
		return '';
	}

	public function ajaxImportBannersData()
	{
		if ($this->importBannersData())
			$ret = array('upd_html' => utf8_encode($this->import_response.$this->displayForm()));
		else
			$ret = array('errors' => $this->import_response);
		exit(Tools::jsonEncode($ret));
	}

	public function importBannersData($zip_file = false)
	{
		$tmp_zip_file = $this->local_path.'tmp/uploaded.zip';

		if (!$zip_file)
		{
			if (!isset($_FILES['zipped_banners_data']))
				return $this->clearFilesAndSetError($this->l('File not uploaded'));

			$uploaded_file = $_FILES['zipped_banners_data'];

			$type = $uploaded_file['type'];
			$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
			if (!in_array($type, $accepted_types))
				return $this->clearFilesAndSetError($this->l('Please upload a valid zip file'));

			if (!move_uploaded_file($uploaded_file['tmp_name'], $tmp_zip_file))
				return $this->clearFilesAndSetError($this->failed_txt);
		}
		else
			Tools::copy($zip_file, $tmp_zip_file);

		$exctracted_contents_dir = $this->local_path.'tmp/uploaded_extracted/';
		if (!Tools::ZipExtract($tmp_zip_file, $exctracted_contents_dir))
			return $this->clearFilesAndSetError($this->l('An error occured while unzipping archive'));
		if (!file_exists($exctracted_contents_dir.'data.txt'))
			return $this->clearFilesAndSetError($this->l('This is not a valid backup file'));

		$imported_data = Tools::jsonDecode(Tools::file_get_contents($exctracted_contents_dir.'data.txt'), true);
		$languages = Language::getLanguages(false);
		$lang_iso_id = array();
		foreach ($languages as $lang)
			$lang_iso_id[$lang['iso_code']] = $lang['id_lang'];

		$tables_to_fill = array();
		$images_to_copy = array();
		$exceptions_data = array();
		$hooks_data = array();
		$custom_files = array();

		foreach ($this->shop_ids as $id_shop)
		{
			// custombanners
			if (isset($imported_data['custombanners'][$id_shop]))
				$shop_banners = $imported_data['custombanners'][$id_shop];
			else
				$shop_banners = $imported_data['custombanners']['ID_SHOP_DEFAULT'];
			foreach ($shop_banners as $banner_multilang)
				foreach ($lang_iso_id as $iso => $id_lang)
				{
					if (isset($banner_multilang[$iso]))
						$banner_data = $banner_multilang[$iso];
					else
						$banner_data = $banner_multilang['LANG_ISO_DEFAULT'];
					$banner_data['id_shop'] = $id_shop;
					$banner_data['id_lang'] = $id_lang;
					$tables_to_fill['custombanners'][] = $banner_data;

					// mark images that need to be copied
					$content = Tools::jsonDecode($banner_data['content'], true);
					if (isset($content['img']) && $content['img'])
					{
						$img_orig_path = $exctracted_contents_dir.'img/'.$content['img'];
						if (file_exists($img_orig_path))
							$images_to_copy[$img_orig_path] = $this->banners_dir_local.$content['img'];
					}
				}

			// cb_hook_settings
			if ($imported_data['cb_hook_settings'])
			{
				if (isset($imported_data['cb_hook_settings'][$id_shop]))
					$carousel_data = $imported_data['cb_hook_settings'][$id_shop];
				else
					$carousel_data = $imported_data['cb_hook_settings']['ID_SHOP_DEFAULT'];
				$carousel_data['id_shop'] = $id_shop;
				$tables_to_fill['cb_hook_settings'][] = $carousel_data;
			}

			// exceptions
			if ($imported_data['hook_module_exceptions'])
			{
				// just prepare exceptions data on this step. It will be applied after other data is inserted to DB
				if (isset($imported_data['hook_module_exceptions'][$id_shop]))
					$exceptions_data[$id_shop] = $imported_data['hook_module_exceptions'][$id_shop];
				else
					$exceptions_data[$id_shop] = $imported_data['hook_module_exceptions']['ID_SHOP_DEFAULT'];
			}

			// hooks & positions
			if ($imported_data['hook_module'])
			{
				if (isset($imported_data['hook_module'][$id_shop]))
					$hooks_data[$id_shop] = $imported_data['hook_module'][$id_shop];
				else
					$hooks_data[$id_shop] = $imported_data['hook_module']['ID_SHOP_DEFAULT'];
			}

			// custom files
			foreach (array('css', 'js') as $type)
			{
				$dest = $this->local_path.'views/'.$type.'/custom/shop'.$id_shop.'.'.$type;
				if (file_exists($exctracted_contents_dir.$type.'/shop'.$id_shop.'.'.$type))
					$custom_files[$exctracted_contents_dir.$type.'/shop'.$id_shop.'.'.$type] = $dest;
				else if (file_exists($exctracted_contents_dir.$type.'/shopid_shop_default.'.$type))
					$custom_files[$exctracted_contents_dir.$type.'/shopid_shop_default.'.$type] = $dest;
			}
		}

		$sql = array();
		foreach ($tables_to_fill as $table_name => $rows_to_insert)
		{
			$db_columns = $this->db->executeS('SHOW COLUMNS FROM '._DB_PREFIX_.pSQL($table_name));
			foreach ($db_columns as &$col)
				$col = $col['Field'];
			$test_row_columns = array_keys(current($rows_to_insert));
			foreach ($test_row_columns as $k => $col_name)
				if (!isset($db_columns[$k]) || $db_columns[$k] != $col_name)
				{
					$err = $this->l('This archive can not be used for import. Reason: Database tables don\'t match (%s).');
					return $this->clearFilesAndSetError(sprintf($err, _DB_PREFIX_.$table_name));
				}

			$sql[] = 'DELETE FROM '._DB_PREFIX_.pSQL($table_name).' WHERE id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')';
			$rows = array();
			$column_names = array();
			foreach ($rows_to_insert as $row)
			{
				if (!$column_names)
					$column_names = array_keys($row);
				foreach ($row as &$r)
					$r = pSQL((_PS_MAGIC_QUOTES_GPC_ ? addslashes($r) : $r ), true);
				$rows[] = '(\''.implode('\', \'', $row).'\')';
			}
			if (!$rows || !$column_names)
				continue;
			$sql[] = '
				INSERT INTO '._DB_PREFIX_.pSQL($table_name).' ('.implode(', ', array_map('pSQL', $column_names)).')
				VALUES '.implode(', ', $rows).'
			';
		}

		if (!$sql)
			return $this->clearFilesAndSetError($this->l('Nothing to import'));

		if ($imported = $this->runSql($sql))
		{
			$this->deleteShopContextImages($this->shop_ids);
			foreach ($images_to_copy as $original_path => $destination_path)
				Tools::copy($original_path, $destination_path);
			foreach ($custom_files as $original_path => $destination_path)
				Tools::copy($original_path, $destination_path);

			$this->recursiveRemove($this->local_path.'tmp/', true);

			// save original shop context, because it will be changed while setting up hooks & exceptions
			$original_shop_context = Shop::getContext();
			$original_shop_context_id = null;
			if ($original_shop_context == Shop::CONTEXT_GROUP)
				$original_shop_context_id = $this->context->shop->id_shop_group;
			else if ($original_shop_context == Shop::CONTEXT_SHOP)
				$original_shop_context_id = $this->context->shop->id;

			foreach ($hooks_data as $id_shop => $hook_list)
				foreach ($hook_list as $hook_name => $cb_position)
				{
					if ($id_shop != $this->context->shop->id)
					{
						Cache::clean('hook_module_list');
						Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
					}

					$id_hook = Hook::getIdByName($hook_name);

					$this->registerHook($hook_name, array($id_shop));
					$this->updatePosition($id_hook, 0, $cb_position);

					$imported &= $this->unregisterExceptions($id_hook, array($id_shop));
					if (isset($exceptions_data[$id_shop][$hook_name]))
					{
						$except = $exceptions_data[$id_shop][$hook_name];
						$imported &= $this->registerExceptions($id_hook, $except, array($id_shop));
					}
				}

			Shop::setContext($original_shop_context, $original_shop_context_id);
			$this->import_response = $this->displayConfirmation($this->l('Data was successfully  imported'));
		}
		else
			$this->import_response = $this->displayError($this->l('An error occured while importing data'));
		return $imported;
	}

	public function deleteShopContextImages($shop_ids = false)
	{
		if (!$shop_ids)
			$shop_ids = $this->shop_ids;
		$out_of_context = $this->db->executeS('
			SELECT content FROM '._DB_PREFIX_.'custombanners WHERE id_shop NOT IN ('.implode(', ', array_map('intval', $shop_ids)).')
		');
		$imgs_to_keep = array();
		foreach ($out_of_context as $ooc)
		{
			$content = Tools::jsonDecode($ooc['content']);
			if (isset($content->img) && $content->img)
				$imgs_to_keep[] = $content->img;
		}
		$this->recursiveRemove($this->banners_dir_local, true, $imgs_to_keep);
	}

	public function clearFilesAndSetError($error)
	{
		$this->recursiveRemove($this->local_path.'tmp/', true);
		if (Tools::isSubmit('ajax'))
			$this->throwError($error);
		$this->context->controller->errors[] = $error;
		return false;
	}

	public function recursiveRemove($dir, $top_level = false, $files_to_keep = array())
	{
		if ($top_level)
			$files_to_keep[] = 'index.php';
		$structure = glob(rtrim($dir, '/').'/*');
		if (is_array($structure))
		{
			foreach ($structure as $file)
				if (is_dir($file))
					$this->recursiveRemove($file);
				else if (is_file($file) && !in_array(basename($file), $files_to_keep))
					unlink($file);
		}
		if (!$top_level)
			rmdir($dir);
	}

	public function getSingleBannerMultilangData($id_banner)
	{
		$banner_data = $this->db->executeS('
			SELECT * FROM '._DB_PREFIX_.'custombanners
			WHERE id_banner = '.(int)$id_banner.'
			AND id_shop = '.(int)$this->context->shop->id.'
		');
		$sorted = array();
		foreach ($banner_data as $b)
		{
			$content = Tools::jsonDecode($b['content'], true);
			if (isset($content['img']))
				$content['img'] = $this->getBannerSrc($content['img']);
			foreach ($content as $name => $value)
				$sorted['content'][$name][$b['id_lang']] = $value;

			if ($b['id_lang'] == $this->context->language->id)
			{
				if (!isset($content['title']) || !$content['title'])
					$content['title'] = sprintf($this->l('Banner %d'), $b['id_banner']);
				$sorted['title'] = $content['title'];
				$sorted['active'] = $b['active'];
				$sorted['in_carousel'] = $b['in_carousel'];
				$sorted['hook_name'] = $b['hook_name'];
				if (isset($content['img']))
					$sorted['header_img'] = $content['img'];
				else if (isset($content['html']))
					$sorted['header_html'] = 1;
			}
		}
		if (!$sorted)
		{
			$sorted = array(
				'title' => $this->l('New banner'),
				'active' => 1,
				'in_carousel' => 0,
				'hook_name' => Tools::getValue('hook_name'),
			);
		}
		return $sorted;
	}

	public function getAllBannersData()
	{
		$banners_db = $this->db->executeS('
			SELECT * FROM '._DB_PREFIX_.'custombanners WHERE id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).') ORDER BY position
		');
		$sorted = array();
		$id_shop = $this->context->shop->id;
		$id_lang = $this->context->language->id;
		foreach ($banners_db as $b)
		{
			if ($b['id_shop'] == $id_shop || !isset($sorted[$b['hook_name']][$b['id_banner']]))
			{
				if (!$b['content'])
					continue;
				$content = Tools::jsonDecode($b['content'], true);
				if (isset($content['img']))
					$content['img'] = $this->getBannerSrc($content['img']);
				foreach ($content as $k => $val)
					if ($val)
						$sorted[$b['hook_name']][$b['id_banner']]['content'][$k][$b['id_lang']] = $val;

				if ($b['id_lang'] == $id_lang || !isset($sorted[$b['hook_name']][$b['id_banner']]['title']))
				{
					if (!isset($content['title']) || !$content['title'])
						$content['title'] = sprintf($this->l('Banner %d'), $b['id_banner']);
					$sorted[$b['hook_name']][$b['id_banner']]['title'] = $content['title'];
					$sorted[$b['hook_name']][$b['id_banner']]['active'] = $b['active'];
					$sorted[$b['hook_name']][$b['id_banner']]['in_carousel'] = $b['in_carousel'];
					$sorted[$b['hook_name']][$b['id_banner']]['hook_name'] = $b['hook_name'];
					if (isset($content['img']))
						$sorted[$b['hook_name']][$b['id_banner']]['header_img'] = $content['img'];
					else if (isset($content['html']))
						$sorted[$b['hook_name']][$b['id_banner']]['header_html'] = 1;
				}
			}
		}
		return $sorted;
	}

	public function getBannerSrc($img_name)
	{
		$src = '';
		if ($img_name != '' && file_exists($this->banners_dir_local.$img_name))
			$src = $this->banners_dir.$img_name;
		return $src;
	}

	public function callBannerForm($id_banner, $encode = true)
	{
		$this->context->smarty->assign(array(
			'id_banner' => $id_banner,
			'banner' => $this->getSingleBannerMultilangData($id_banner),
			'input_fields' => $this->getBannerFieldNames(),
			'bs_classes' => $this->getBSClasses(),
			'languages' => Language::getLanguages(false),
			'id_lang_current' => $this->context->language->id,
			'multishop_note' => count($this->shop_ids) > 1,
		));
		$banner_form_html = $this->display($this->local_path, 'views/templates/admin/banner-form.tpl');
		if ($encode)
			$banner_form_html = utf8_encode($banner_form_html);
		return $banner_form_html;
	}

	public function ajaxCallSettingsForm()
	{
		$hook_name = Tools::getValue('hook_name');
		$settings_type = Tools::getValue('settings_type');
		$method = 'getHook'.Tools::ucfirst($settings_type).'Settings';
		if (!is_callable(array($this, $method)))
			$this->throwError($this->l('This type of settings is not supported'));

		$this->context->smarty->assign(array(
			'settings' => $this->$method($hook_name),
			'settings_type' => $settings_type,
			'hook_name' => $hook_name,
		));
		$form_html = $this->display($this->local_path, 'views/templates/admin/hook-'.$settings_type.'-form.tpl');
		$ret = array(
			'form_html' => utf8_encode($form_html),
		);
		exit(Tools::jsonEncode($ret));
	}

	public function getHookCarouselSettings($hook_name, $encode = false)
	{
		$default_carousel_settings  = array (
			'p' => 0,
			'n' => 1,
			'n_hover' => 1,
			'a' => 1,
			's' => 250,
			'm' => 1,
			'i' => 5,
			'i_1200' => 4,
			'i_992' => 3,
			'i_768' => 2,
			'i_480' => 1
		);
		$current_settings = $this->db->getValue('
			SELECT carousel FROM '._DB_PREFIX_.'cb_hook_settings
			WHERE hook_name = \''.pSQL($hook_name).'\' AND id_shop = '.(int)$this->context->shop->id.'
		');
		$current_settings = Tools::jsonDecode($current_settings, true);
		foreach ($default_carousel_settings as $k => $s)
			if (!isset($current_settings[$k]))
				$current_settings[$k] = $s;
		if ($encode)
			$current_settings = Tools::jsonEncode($current_settings);
		return $current_settings;
	}

	public function getHookExceptionsSettings($hook_name)
	{
		$current_exceptions = $this->getExceptions(Hook::getIdByName($hook_name));
		$sorted_exceptions = array(
			'core' => array(
				'group_name' => $this->l('Core pages'),
				'values' => array(),
			),
			'modules' => array(
				'group_name' => $this->l('Module pages'),
				'values' => array(),
			),
		);

		$front_controllers = array_keys(Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_));
		foreach ($front_controllers as $fc)
			$sorted_exceptions['core']['values'][$fc] = in_array($fc, $current_exceptions) ? 1 : 0;

		$module_front_controllers = Dispatcher::getModuleControllers('front');
		foreach ($module_front_controllers as $module_name => $controllers)
			foreach ($controllers as $controller_name)
			{
				$key = 'module-'.$module_name.'-'.$controller_name;
				$sorted_exceptions['modules']['values'][$key] = in_array($key, $current_exceptions) ? 1 : 0;
			}

		return $sorted_exceptions;
	}

	public function getHookPositionsSettings($hook_name)
	{
		$hook_modules = Hook::getModulesFromHook(Hook::getIdByName($hook_name));
		$sorted = array();
		foreach ($hook_modules as $m)
		{
			if ($instance = Module::getInstanceByName($m['name']))
			{
				$logo_src = false;
				if (file_exists(_PS_MODULE_DIR_.$instance->name.'/logo.png'))
					$logo_src = _MODULE_DIR_.$instance->name.'/logo.png';
				$sorted[$m['id_module']] = array(
					'name' => $instance->name,
					'position' => $m['m.position'],
					'enabled' => $instance->isEnabledForShopContext(),
					'display_name' => $instance->displayName,
					'description' => $instance->description,
					'logo_src' => $logo_src,
				);
				if ($m['id_module'] == $this->id)
					$sorted[$m['id_module']]['current'] = 1;
			}
		}
		return $sorted;
	}

	public function ajaxSaveHookSettings()
	{
		$hook_name = Tools::getValue('hook_name');
		$id_hook = Hook::getIdByName($hook_name);
		$settings_type = Tools::getValue('settings_type');
		$saved = false;
		if ($settings_type == 'carousel')
		{

			$submitted_settings = Tools::getValue('settings');
			$default_settings = $this->getHookCarouselSettings('default');
			$settings_to_save = array();

			foreach ($default_settings as $k => $s)
				if (isset($submitted_settings[$k]))
					$settings_to_save[$k] = $submitted_settings[$k];
				else
					$settings_to_save[$k] = $s;

			$settings_to_save = Tools::jsonEncode($settings_to_save);

			$rows = array();
			foreach (Shop::getContextListShopID() as $id_shop)
				$rows[] = '(\''.pSQL($hook_name).'\', '.(int)$id_shop.', \''.pSQL($settings_to_save).'\')';
			$insert_query = '
				INSERT INTO '._DB_PREFIX_.'cb_hook_settings (hook_name, id_shop, carousel)
				VALUES '.implode(', ', $rows).'
				ON DUPLICATE KEY UPDATE
				carousel = VALUES(carousel)
			';
			$saved = $this->db->execute($insert_query);
		}
		else if ($settings_type == 'exceptions')
		{
			$exceptions = Tools::getValue('exceptions');
			$saved = $this->unregisterExceptions($id_hook);
			$saved &= $this->registerExceptions($id_hook, $exceptions);
		}
		else if ($settings_type == 'position')
		{
			$id_module = Tools::getValue('id_module');
			$new_position = Tools::getValue('new_position');
			$way = Tools::getValue('way');
			if ($module = Module::getInstanceById($id_module))
				$saved = $module->updatePosition($id_hook, $way, $new_position);
		}
		$ret = array(
			'saved' => $saved
		);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxProcessModule()
	{
		$id_module = Tools::getValue('id_module');
		$hook_name = Tools::getValue('hook_name');
		$act = Tools::getValue('act');
		$module = Module::getInstanceById($id_module);

		$saved = false;
		if (Validate::isLoadedObject($module))
		{
			switch ($act)
			{
				case 'disable':
					$module->disable();
					$saved = !$module->isEnabledForShopContext();
				break;
				case 'unhook':
					$saved = $module->unregisterHook(Hook::getIdByName($hook_name), $this->shop_ids);
				break;
				case 'uninstall':
					if ($id_module != $this->id)
						$saved = $module->uninstall();
				break;
				case 'enable':
					$saved = $module->enable();
				break;
			}
		}
		$ret = array ('saved' => $saved);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxGetCustomCode()
	{
		$file_type = Tools::getValue('file_type');
		$file_path = $this->local_path.'views/'.$file_type.'/custom/shop'.$this->context->shop->id.'.'.$file_type;
		$code = file_exists($file_path) ? Tools::file_get_contents($file_path) : '';
		$ret = array('code' => $code);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxSaveCustomFile()
	{
		$file_type = Tools::getValue('file_type');
		$code = Tools::getValue('code');
		$saved = true;
		foreach (Shop::getContextListShopID() as $id_shop)
		{
			$file_path = $this->local_path.'views/'.$file_type.'/custom/shop'.$id_shop.'.'.$file_type;
			if ($code)
				$saved &= file_put_contents($file_path, $code);
			else if (file_exists($file_path))
				$saved &= unlink($file_path);
		}
		$ret = array('saved' => $saved !== false);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxAddBanner()
	{
		$ret = array('banner_form_html' => $this->callBannerForm(0));
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxCopyToAnotherHook()
	{
		$id_banner = Tools::getValue('id_banner');
		$to_hook = Tools::getValue('to_hook');
		if (!$id_banner || !$to_hook)
			$this->throwError($this->l('Error'));
		$delete_original = Tools::getValue('delete_original');
		$new_id = $this->copyToAnotherHook($id_banner, $to_hook, $delete_original);
		$ret = array(
			'new_banner_form' => $new_id ? $this->callBannerForm($new_id) : false,
			'reponseText' => isset($this->response_text) ? $this->response_text :  $this->l('Failed'),
		);
		exit(Tools::jsonEncode($ret));
	}

	public function copyToAnotherHook($id_banner, $to_hook, $delete_original = false)
	{
		$banner_shop_lang = $this->db->executeS('
			SELECT * FROM '._DB_PREFIX_.'custombanners WHERE id_banner = '.(int)$id_banner.'
		');
		$new_id = $this->getNewId();
		$position = $this->getNextPosition($to_hook);
		$to_insert = array();
		foreach ($banner_shop_lang as $bsl)
		{
			$bsl['id_banner'] = $new_id;
			$bsl['hook_name'] = $to_hook;
			$bsl['position'] = $position;
			$content = Tools::jsonDecode($bsl['content'], true);
			if (isset($content['img']) && $content['img'])
			{
				$ext = pathinfo($content['img'], PATHINFO_EXTENSION);
				$new_img_name = $this->getNewFilename().'.'.$ext;
				Tools::copy($this->banners_dir_local.$content['img'], $this->banners_dir_local.$new_img_name);
				$content['img'] = $new_img_name;
			}
			$bsl['content'] = Tools::jsonEncode($content);
			foreach ($bsl as &$b)
				$b = pSQL($b, true);
			$to_insert[] = '(\''.implode('\', \'', $bsl).'\')';
		}
		if ($to_insert)
			$copied = $this->db->execute('INSERT INTO '._DB_PREFIX_.'custombanners VALUES '.implode(', ', $to_insert));
		else
			$copied = false;

		if ($copied)
		{
			$this->response_text = sprintf($this->l('Copied to %s'), $to_hook);

			foreach ($this->shop_ids as $id_shop)
				if (!$this->isRegisteredInHookConsideringShop($to_hook, $id_shop))
					$this->registerHook($to_hook, array($id_shop));

			if ($delete_original)
			{
				$copied &= $this->deleteBanner($id_banner);
				$this->response_text = sprintf($this->l('Moved to %s'), $to_hook);
			}
		}
		else
			$new_id = false;
		return $new_id;
	}

	public function ajaxBulkAction()
	{
		$action = Tools::getValue('act');
		$banner_ids = Tools::getValue('ids');
		if ($action == 'deleteAll')
		{
			$banners = $this->db->executeS('
				SELECT id_banner FROM '._DB_PREFIX_.'custombanners
				WHERE id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
				GROUP BY id_banner
			');
			$banner_ids = array();
			foreach ($banners as $b)
				$banner_ids[] = $b['id_banner'];
		}
		if (!$banner_ids && $action != 'deleteAll')
			$this->throwError($this->l('Please make a selection'));
		$success = true;
		$this->response_text = $this->saved_txt;
		switch ($action)
		{
			case 'enable':
			case 'disable':
				$active = $action == 'enable';
				$success &= $this->db->execute('
					UPDATE '._DB_PREFIX_.'custombanners SET active = '.(int)$active.'
					WHERE id_banner IN ('.implode(', ', array_map('intval', $banner_ids)).')
					AND id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
				');
			break;
			case 'move':
			case 'copy':
				$additional_params = Tools::getValue('additionalParams');
				if (isset($additional_params['to_hook']))
				{
					$to_hook = $additional_params['to_hook'];
					$delete_original = $action == 'move';
					$html = '';
					foreach ($banner_ids as $id_banner)
					{
						if ($new_id = $this->copyToAnotherHook($id_banner, $to_hook, $delete_original))
							$html .= $this->callBannerForm($new_id);
						else
						{
							$this->response_text = $this->failed_txt;
							$success = false;
							break;
						}
					}
				}
			break;
			case 'delete':
			case 'deleteAll':
				foreach ($banner_ids as $id_banner)
					$success &= $this->deleteBanner($id_banner);
			break;

		}
		$ret = array(
			'success' => $success,
			'reponseText' => $this->response_text,
			'responseHTML' => isset($html) ? $html : false,
		);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxSaveBannerData()
	{
		$data_multilang = Tools::getValue('banner_data');
		if (!$data_multilang)
			$this->throwError($this->l('Please fill in at least one field'));
		$saved_id = $this->saveBannerData($data_multilang);
		$ret = array(
			'banner_form_html' => $saved_id ? $this->callBannerForm($saved_id) : false,
		);
		exit(Tools::jsonEncode($ret));
	}

	/**
	* custombanners table has a composite KEY that cannot be autoincremented
	**/
	public function getNewId()
	{
		$max_id = $this->db->getValue('SELECT MAX(id_banner) FROM '._DB_PREFIX_.'custombanners');
		return (int)$max_id + 1;
	}

	public function getNextPosition($hook_name)
	{
		$position = $this->db->getValue('
			SELECT MAX(position) FROM '._DB_PREFIX_.'custombanners WHERE hook_name = \''.pSQL($hook_name).'\'
		');
		return (int)$position + 1;
	}

	public function saveBannerData($data_multilang)
	{
		$id_banner = Tools::getValue('id_banner');
		$hook_name = Tools::getValue('hook_name');
		$active = Tools::getValue('active');
		$in_carousel = Tools::getValue('in_carousel');
		$position = false;
		if (!$id_banner)
		{
			$id_banner = $this->getNewId();
			$position = $this->getNextPosition($hook_name);
		}

		$lang_shop_rows = array();
		$already_uploaded = array();
		$imgs_to_delete = Tools::getValue('imgs_to_delete', array());

		foreach ($this->shop_ids as $id_shop)
		{
			foreach ($data_multilang as $id_lang => $content)
			{
				$id_lang_source = Tools::getValue('lang_source', $id_lang);
				$content = $data_multilang[$id_lang_source];
				if (isset($content['link']) && $content['link']['type'] != 'custom' && !(int)$content['link']['href'])
				{
					$lang_name = Language::getIsoById($id_lang);
					$error = $this->l('Please specify a proper ID for the link field (%s)');
					if (Tools::isSubmit('ajax'))
						$this->throwError(sprintf($error, $lang_name));
					else
						unset($content['link']);
				}

				if (isset($content['restricted']))
				{
					$ids = explode(',', preg_replace('/\s+/', '', $content['restricted']['ids']));
					foreach ($ids as $k => &$id)
						if (!Validate::isInt($id) || $id < 1)
							unset($ids[$k]);
					$content['restricted']['ids'] = array_unique($ids);
				}

				if (isset($_FILES['banner_img_'.$id_lang_source]))
				{
					if (isset($already_uploaded[$id_lang_source]))
						$img_name = $already_uploaded[$id_lang_source];
					else
					{
						$file = $_FILES['banner_img_'.$id_lang_source];
						$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
						$img_name = $this->getNewFilename().'.'.$ext;
						$this->saveSubmittedBannerImage($file, $img_name);
						$content['img'] = $img_name;
						$already_uploaded[$id_lang_source] = $img_name;
					}
					if ($data_multilang[$id_lang]['img'])
						$imgs_to_delete[$data_multilang[$id_lang]['img']] = 1;
					$content['img'] = $img_name;
				}
				$content = Tools::jsonEncode($content);
				if (_PS_MAGIC_QUOTES_GPC_)
					$content = addslashes($content);

				$row = '('.(int)$id_banner;
				$row .= ', '.(int)$id_shop;
				$row .= ', '.(int)$id_lang;
				$row .= ', \''.pSQL($hook_name).'\'';
				$row .= ', '.(int)$position;
				$row .= ', '.(int)$active;
				$row .= ', '.(int)$in_carousel;
				$row .= ', \''.pSQL($content, true).'\')';
				$lang_shop_rows[] = $row;
			}
		}

		$insert_query = '
			INSERT INTO '._DB_PREFIX_.'custombanners
			VALUES '.implode(', ', $lang_shop_rows).'
			ON DUPLICATE KEY UPDATE
			content = VALUES(content)
		';

		if ($this->db->execute($insert_query))
		{
			foreach (array_keys($imgs_to_delete) as $img_name)
			{
				$banner_shop_lang = $this->db->executeS('
					SELECT content FROM '._DB_PREFIX_.'custombanners WHERE id_banner = '.(int)$id_banner.'
				');
				$image_is_used = false;
				foreach ($banner_shop_lang as $bsl)
				{
					if (!$bsl['content'])
						continue;
					$content = Tools::jsonDecode($bsl['content'], true);
					if (isset($content['img']) && $content['img'] == $img_name)
					{
						$image_is_used = true;
						break;
					}
				}
				if (!$image_is_used)
					unlink($this->banners_dir_local.$img_name);
			}
			foreach ($this->shop_ids as $id_shop)
				if (!$this->isRegisteredInHookConsideringShop($hook_name, $id_shop))
					$this->registerHook($hook_name, array($id_shop));
		}
		else
			$id_banner = false;

		return $id_banner;
	}

	public function isRegisteredInHookConsideringShop($hook_name, $id_shop)
	{
		$sql = 'SELECT COUNT(*)
			FROM '._DB_PREFIX_.'hook_module hm
			LEFT JOIN '._DB_PREFIX_.'hook h ON (h.id_hook = hm.id_hook)
			WHERE h.name = \''.pSQL($hook_name).'\' AND hm.id_shop = '.(int)$id_shop.' AND hm.id_module = '.(int)$this->id;
		return $this->db->getValue($sql);
	}

	public function saveSubmittedBannerImage($file, $banner_name)
	{
		if (!isset($file['tmp_name']) || empty($file['tmp_name']) || isset($this->already_uploaded[$file['tmp_name']]))
			return;

		$max_size = 10485760; // 10 mb

		// Check image validity
		if ($error = ImageManager::validateUpload($file, Tools::getMaxUploadSize($max_size)))
			$this->errors[] = $error;

		// move file directly, without any resizing for preserving max quality
		if (!$this->errors && !move_uploaded_file($file['tmp_name'], $this->banners_dir_local.$banner_name))
			$this->errors[] = Tools::displayError('An error occurred while uploading the image.');

		if ($this->errors)
			$this->throwError($this->errors);

		$this->already_uploaded[$file['tmp_name']] = 1;

		return true;
	}

	public function getNewFilename()
	{
		do $filename = sha1(microtime());
		while (file_exists($this->banners_dir_local.$filename));
		return $filename;
	}

	public function ajaxToggleBannerParam()
	{
		$id_banner = Tools::getValue('id_banner');
		$param_name = Tools::getValue('param_name');
		$param_value = Tools::getValue('param_value');
		if (!$param_name)
			$this->throwError($this->l('Parameters not provided correctly'));

		$update_query = '
			UPDATE '._DB_PREFIX_.'custombanners
			SET '.pSQL($param_name).' = '.(int)$param_value.'
			WHERE id_banner = '.(int)$id_banner.' AND id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
		';
		if (!$this->db->execute($update_query))
			$this->throwError($this->l('Not saved'));
		$ret = array('success' => 1);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxDeleteBanner()
	{
		$id_banner = Tools::getValue('id_banner');
		$deleted = $this->deleteBanner($id_banner);
		$result = array (
			'deleted' => $deleted,
			'responseText' => isset($this->response_text)? $this->response_text : $this->failed_txt,
		);
		exit(Tools::jsonEncode($result));
	}

	public function deleteBanner($id_banner)
	{
		$banner_shop_lang = $this->db->executeS('
			SELECT id_shop, content FROM '._DB_PREFIX_.'custombanners
			WHERE id_banner = '.(int)$id_banner.'
		');
		$hook_name = $this->db->getValue('SELECT hook_name FROM '._DB_PREFIX_.'custombanners WHERE id_banner = '.(int)$id_banner);
		$imgs_to_remove = array();
		$imgs_to_keep = array();
		foreach ($banner_shop_lang as $bsl)
		{
			$content = Tools::jsonDecode($bsl['content'], true);
			if (!isset($content['img']))
				continue;
			if (in_array($bsl['id_shop'], $this->shop_ids))
				$imgs_to_remove[$content['img']] = $content['img'];
			else
				$imgs_to_keep[$content['img']] = $content['img'];
		}
		$imgs_to_remove = array_diff($imgs_to_remove, $imgs_to_keep);
		$deleted = $this->db->execute('
			DELETE FROM '._DB_PREFIX_.'custombanners
			WHERE id_banner = '.(int)$id_banner.'
			AND id_shop IN ('.implode(', ', array_map('intval', $this->shop_ids)).')
		');
		if ($deleted)
		{
			$this->response_text = $this->l('Deleted');
			foreach ($imgs_to_remove as $img_name)
				unlink($this->banners_dir_local.$img_name);
			foreach ($this->shop_ids as $id_shop)
			$hook_is_used = $this->db->getValue('
				SELECT hook_name FROM '._DB_PREFIX_.'custombanners
				WHERE hook_name = \''.pSQL($hook_name).'\' AND id_shop = '.(int)$id_shop.'
			');
			if (!$hook_is_used)
				$this->unregisterHook(Hook::getIdByName($hook_name), array($id_shop));
		}
		return $deleted;
	}

	public function ajaxUpdatePositionsInHook()
	{
		$ordered_ids = Tools::getValue('ordered_ids');
		if (!$ordered_ids)
			$this->throwError($this->failed_txt);

		$languages = Language::getLanguages(false);
		$update_rows = array();
		foreach ($this->shop_ids as $id_shop)
			foreach ($languages as $lang)
				foreach ($ordered_ids as $k => $id_banner)
				{
					$pos = $k + 1;
					$update_rows[] = '('.(int)$id_banner.', '.(int)$id_shop.', '.(int)$lang['id_lang'].', '.(int)$pos.')';
				}

		$update_query = '
			INSERT INTO '._DB_PREFIX_.'custombanners (id_banner, id_shop, id_lang, position)
			VALUES '.implode(', ', $update_rows).'
			ON DUPLICATE KEY UPDATE
			position = VALUES(position)
		';

		$ret = array ('saved' => $this->db->execute($update_query));
		exit(Tools::jsonEncode($ret));
	}

	public function throwError($errors)
	{
		if (!is_array($errors))
			$errors = array($errors);
		$ret = array('errors' => utf8_encode($this->displayError(implode('<br>', $errors))));
		die(Tools::jsonEncode($ret));
	}

	public function getBannersInHook($hook_name)
	{
		$banners_db = $this->db->executeS('
			SELECT * FROM '._DB_PREFIX_.'custombanners
			WHERE hook_name = \''.pSQL($hook_name).'\'
			AND id_shop = '.(int)$this->context->shop->id.'
			AND id_lang = '.(int)$this->context->language->id.'
			AND active = 1
			ORDER BY position ASC
		');

		$controller = Tools::getValue('controller');
		$id = Tools::getValue('id_'.$controller);

		$sorted = array();
		foreach ($banners_db as $b)
		{
			$content = Tools::jsonDecode($b['content'], true);
			if (isset($content['restricted']))
				if ($content['restricted']['type'] != $controller || !in_array($id, $content['restricted']['ids']))
					continue;
			if (isset($content['img']))
				$content['img'] = $this->getBannerSrc($content['img']);
			$content['in_carousel'] = $b['in_carousel'];
			if (isset($content['link']['type']) && $content['link']['type'] != 'custom')
			{
				$get_link_method = 'get'.$content['link']['type'].'Link';
				$id_resource = $content['link']['href'];
				if ((int)$id_resource)
					$content['link']['href'] = $this->context->link->$get_link_method($id_resource);
				else
					unset($content['link']);
			}
			$sorted[$b['id_banner']] = $content;
		}
		return $sorted;
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addJqueryPlugin('bxslider');
		$this->context->controller->addCSS($this->_path.'/views/css/front.css', 'all');
		$this->context->controller->addCSS($this->_path.'/views/css/custom/shop'.$this->context->shop->id.'.css', 'all');
		$this->context->controller->addJS($this->_path.'/views/js/front.js');
		$this->context->controller->addJS($this->_path.'/views/js/custom/shop'.$this->context->shop->id.'.js');
		if (version_compare(_PS_VERSION_, '1.6.11', '<') === true)
			$is_mobile = $this->context->getMobileDetect()->isMobile();
		else
			$is_mobile = $this->context->isMobile();
		Media::addJsDef(array(
			'isMobile' =>$is_mobile,
		));
	}

	/*
	* all hooks are returning displayNativeHook
	*/
	public function displayNativeHook($hook_name)
	{
		$this->context->smarty->assign(array(
			'banners' => $this->getBannersInHook($hook_name),
			'hook_name' => $hook_name,
			'carousel_settings' => $this->getHookCarouselSettings($hook_name, true),
		));
		return $this->display($this->local_path, 'banners.tpl');
	}

	public function hookDisplayBanner()
	{
		return $this->displayNativeHook('displayBanner');
	}

	public function hookDisplayBeforeCarrier()
	{
		return $this->displayNativeHook('displayBeforeCarrier');
	}

	public function hookDisplayBeforePayment()
	{
		return $this->displayNativeHook('displayBeforePayment');
	}

	public function hookDisplayCarrierList()
	{
		return $this->displayNativeHook('displayCarrierList');
	}

	public function hookDisplayCompareExtraInformation()
	{
		return $this->displayNativeHook('displayCompareExtraInformation');
	}

	public function hookDisplayCustomerAccount()
	{
		return $this->displayNativeHook('displayCustomerAccount');
	}

	public function hookDisplayCustomerAccountForm()
	{
		return $this->displayNativeHook('displayCustomerAccountForm');
	}

	public function hookDisplayCustomerAccountFormTop()
	{
		return $this->displayNativeHook('displayCustomerAccountFormTop');
	}

	public function hookDisplayFooter()
	{
		return $this->displayNativeHook('displayFooter');
	}

	public function hookDisplayFooterProduct()
	{
		return $this->displayNativeHook('displayFooterProduct');
	}

	public function hookDisplayHome()
	{
		return $this->displayNativeHook('displayHome');
	}

	public function hookDisplayHomeTab()
	{
		return $this->displayNativeHook('displayHomeTab');
	}

	public function hookDisplayHomeTabContent()
	{
		return $this->displayNativeHook('displayHomeTabContent');
	}

	public function hookDisplayInvoice()
	{
		return $this->displayNativeHook('displayInvoice');
	}

	public function hookDisplayLeftColumn()
	{
		return $this->displayNativeHook('displayLeftColumn');
	}

	public function hookDisplayLeftColumnProduct()
	{
		return $this->displayNativeHook('displayLeftColumnProduct');
	}

	public function hookDisplayMaintenance()
	{
		return $this->displayNativeHook('displayMaintenance');
	}

	public function hookDisplayMobileTopSiteMap()
	{
		return $this->displayNativeHook('displayMobileTopSiteMap');
	}

	public function hookDisplayMyAccountBlock()
	{
		return $this->displayNativeHook('displayMyAccountBlock');
	}

	public function hookDisplayMyAccountBlockfooter()
	{
		return $this->displayNativeHook('displayMyAccountBlockfooter');
	}

	public function hookDisplayNav()
	{
		return $this->displayNativeHook('displayNav');
	}

	public function hookDisplayOrderConfirmation()
	{
		return $this->displayNativeHook('displayOrderConfirmation');
	}

	public function hookDisplayOrderDetail()
	{
		return $this->displayNativeHook('displayOrderDetail');
	}

	public function hookDisplayPayment()
	{
		return $this->displayNativeHook('displayPayment');
	}

	public function hookDisplayPaymentReturn()
	{
		return $this->displayNativeHook('displayPaymentReturn');
	}

	public function hookDisplayPaymentTop()
	{
		return $this->displayNativeHook('displayPaymentTop');
	}

	public function hookDisplayPDFInvoice()
	{
		return $this->displayNativeHook('displayPDFInvoice');
	}

	public function hookDisplayProductButtons()
	{
		return $this->displayNativeHook('displayProductButtons');
	}

	public function hookDisplayProductComparison()
	{
		return $this->displayNativeHook('displayProductComparison');
	}

	public function hookDisplayProductListReviews()
	{
		return $this->displayNativeHook('displayProductListReviews');
	}

	public function hookDisplayProductTab()
	{
		return $this->displayNativeHook('displayProductTab');
	}

	public function hookDisplayProductTabContent()
	{
		return $this->displayNativeHook('displayProductTabContent');
	}

	public function hookDisplayRightColumn()
	{
		return $this->displayNativeHook('displayRightColumn');
	}

	public function hookDisplayRightColumnProduct()
	{
		return $this->displayNativeHook('displayRightColumnProduct');
	}

	public function hookDisplayShoppingCart()
	{
		return $this->displayNativeHook('displayShoppingCart');
	}

	public function hookDisplayShoppingCartFooter()
	{
		return $this->displayNativeHook('displayShoppingCartFooter');
	}

	public function hookDisplayTop()
	{
		return $this->displayNativeHook('displayTop');
	}

	public function hookDisplayTopColumn()
	{
		return $this->displayNativeHook('displayTopColumn');
	}

	public function hookDisplayCustomBanners1()
	{
		return $this->displayNativeHook('displayCustomBanners1');
	}

	public function hookDisplayCustomBanners2()
	{
		return $this->displayNativeHook('displayCustomBanners2');
	}
	public function hookDisplayCustomBanners3()
	{
		return $this->displayNativeHook('displayCustomBanners3');
	}
	public function hookDisplayCustomBanners4()
	{
		return $this->displayNativeHook('displayCustomBanners4');
	}
}