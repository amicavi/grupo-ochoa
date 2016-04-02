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

class EasyCarousels extends Module
{
	public function __construct()
	{
		$this->name = 'easycarousels';
		$this->tab = 'front_office_features';
		$this->version = '1.7.3';
		$this->author = 'Amazzing';
		$this->need_instance = 0;
		$this->bootstrap = true;
		$this->module_key = 'b277f11ccef2f6ec16aaac88af76573e';

		parent::__construct();

		$this->displayName = $this->l('Easy carousels');
		$this->description = $this->l('Create your own carousels in just a few clicks');

		$this->db = Db::getInstance();
	}

	public function getTypeNames()
	{
		$type_names = array(
			$this->l('Carousels for any page') => array (
				'newproducts' => $this->l('New products'),
				'bestsellers' => $this->l('Bestsellers'),
				'featuredproducts' => $this->l('Featured products'),
				'pricesdrop' => $this->l('On sale'),
				'bymanufacturer' => $this->l('Products by manufacturer'),
				'bysupplier' => $this->l('Products by supplier'),
				'catproducts' => $this->l('Products from selected categories'),
				'manufacturers' => $this->l('Manufacturers'),
				'suppliers' => $this->l('Suppliers'),
			),
			$this->l('Carousels for product page') => array(
				'samecategory' => $this->l('Other products from same category'),
				'samefeature' => $this->l('Other products with same feature'),
				'accessories' => $this->l('Product accessories'),
			),
		);
		return $type_names;
	}

	public function getFields($type)
	{
		$fields = array();
		switch ($type)
		{
			case 'carousel':
				$fields = array (
					'p' => array(
						'name'  => $this->l('Pagination'),
						'value' => 0,
						'type'  => 'switcher'
					),
					'n' => array(
						'name'  => $this->l('Navigation arrows'),
						'value' => 1,
						'type'  => 'switcher'
					),
					'a' => array(
						'name'  => $this->l('Enable autoplay'),
						'value' => 1,
						'type'  => 'switcher'
					),
					'l' => array(
						'name'  => $this->l('Loop'),
						'value' => 1,
						'type'  => 'switcher'
					),
					's' => array(
						'name'  => $this->l('Animation speed (ms)'),
						'value' => 100,
						'type'  => 'text'
					),
					'm' => array(
						'name'    => $this->l('Slides to move'),
						'tooltip' => $this->l('Number of slides moved per transition. Set 0 to move all visible slides'),
						'value'   => 1,
						'type'    => 'text'
					),
					'total' => array(
						'name'  => $this->l('Total items'),
						'value' => 15,
						'type'  => 'text'
					),
					'r' => array(
						'name'    => $this->l('Visible rows'),
						'tooltip' => $this->l('You can rotate several rows at once'),
						'value'   => 1,
						'type'    => 'text'
					),
					'i' => array(
						'name'    => $this->l('Visible columns'),
						'tooltip' => $this->l('Consider it as "visible items", if you have just one row'),
						'value'   => 5,
						'type'    => 'text'
					),
					'min_width' => array(
						'name'    => $this->l('Min slide width (px)'),
						'tooltip' => $this->l('If dynamic slide width becomes less that this value, then number of visible slides will be decreased.'),
						'value'   => 250,
						'type'    => 'text'
					),
					'i_1200' => array(
						'name'    => $this->l('Visible columns on displays < 1200px'),
						'tooltip' => $this->l('If display width is less than 1200px, this number of items will be visible.'),
						'value'   => 4,
						'type'    => 'text'
					),
					'i_992' => array(
						'name'  => $this->l('Visible columns on displays < 992px'),
						'value' => 3,
						'type'  => 'text'
					),
					'i_768' => array(
						'name'  => $this->l('Visible columns on displays < 768px'),
						'value' => 2,
						'type'  => 'text'
					),
					'i_480' => array(
						'name'  => $this->l('Visible columns on displays < 480px'),
						'value' => 1,
						'type'  => 'text'
					),
				);
			break;
			case 'special':
				$sorted_features = array('product_option' => array(0 => '-'));
				foreach (Feature::getFeatures($this->context->language->id) as $f)
					$sorted_features['product_option'][$f['id_feature']] = $f['name'];

				$sorted_manufacturers = array('product_option' => array(0 => '-'));
				foreach (Manufacturer::getManufacturers() as $m)
					$sorted_manufacturers['product_option'][$m['id_manufacturer']] = $m['name'];

				$sorted_suppliers = array('product_option' => array(0 => '-'));
				foreach (Supplier::getSuppliers() as $s)
					$sorted_suppliers['product_option'][$s['id_supplier']] = $s['name'];

				$fields = array(
					'id_feature' => array(
						'name'   => $this->l('Feature to filter by'),
						'value'  => '',
						'type'   => 'select',
						'select' => $sorted_features,
						'class' => 'special_option samefeature',
					),
					'cat_ids' => array(
						'name'    => $this->l('Category ids'),
						'tooltip' => $this->l('Enter category ids, separated by comma (1,2,3 ...)'),
						'value'   => '',
						'type'    => 'text',
						'class' => 'special_option catproducts',
					),
					'id_manufacturer' => array(
						'name'   => $this->l('Manufacturer'),
						'value'  => 0,
						'type'   => 'select',
						'select' => $sorted_manufacturers,
						'class' => 'special_option bymanufacturer',
					),
					'id_supplier' => array(
						'name'   => $this->l('Supplier'),
						'value'  => 0,
						'type'   => 'select',
						'select' => $sorted_suppliers,
						'class' => 'special_option bysupplier',
					),
				);
			break;
			case 'php':
				$fields = array(
					'randomize' => array(
						'name'   => $this->l('Random ordering'),
						'value'  => 0,
						'type'   => 'switcher',
					),
					'consider_cat' => array(
						'name'    => $this->l('Consider category'),
						'tooltip' => $this->l('Show products only from current category, if carousel is displayed on category page'),
						'value'   => 0,
						'type'    => 'switcher',
						'class'   => 'special_option newproducts bestsellers featuredproducts pricesdrop bymanufacturer bysupplier',
					),
				);
			break;
			case 'tpl':
				$sorted_image_types = array();
				foreach (ImageType::getImagesTypes('products') as $t)
					$sorted_image_types['product_option'][$t['name']] = $t['name'];
				foreach (ImageType::getImagesTypes('manufacturers') as $t)
					$sorted_image_types['manufacturer_option'][$t['name']] = $t['name'];
				foreach (ImageType::getImagesTypes('suppliers') as $t)
					$sorted_image_types['supplier_option'][$t['name']] = $t['name'];

				$fields = array (
					'custom_class' => array(
						'name'    => $this->l('Custom class'),
						'tooltip' => $this->l('Custom class that will be added to carousel container'),
						'value'   => '',
						'type'    => 'text'
					),
					'image_type' => array(
						'name'   => $this->l('Image type'),
						'value'  => ImageType::getFormatedName('home'),
						'type'   => 'select',
						'select' => $sorted_image_types,
					),
					'title_one_line' => array(
						'name'  => $this->l('Title in one line'),
						'tooltip' => $this->l('Truncate title if its length overlaps first line'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'title' => array(
						'name'  => $this->l('Title length (symbols)'),
						'tooltip' => $this->l('Set 0 if you don\'t want to display title'),
						'value' => 45,
						'type'  => 'text',
						'class' => 'product_option',
					),
					'description' => array(
						'name'  => $this->l('Description length'),
						'value' => 0,
						'type'  => 'text',
						'class' => 'product_option',
					),
					'product_cat' => array(
						'name'  => $this->l('Product category'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'product_man' => array(
						'name'  => $this->l('Product manufacturer'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'product_option',
					),

					'price' => array(
						'name'  => $this->l('Price'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'add_to_cart' => array(
						'name'  => $this->l('Add to cart button'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'view_more' => array(
						'name'  => $this->l('View more'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'quick_view' => array(
						'name'  => $this->l('Quick view'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'stock' => array(
						'name'  => $this->l('Stock data'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'stickers' => array(
						'name'  => $this->l('Stickers'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'view_all' => array(
						'name'  => $this->l('Link to all items'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'special_option newproducts bestsellers pricesdrop bymanufacturer bysupplier',
					),
					'displayProductDeliveryTime' => array(
						'name'    => $this->l('Delivery time hook'),
						'tooltip' => $this->l('All data hooked to displayProductDeliveryTime'),
						'value'   => 1,
						'type'    => 'switcher',
						'class' => 'product_option',
					),
					'displayProductPriceBlock' => array(
						'name'  => $this->l('Price block hook'),
						'tooltip' => $this->l('All data hooked to displayProductPriceBlock'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
					'displayProductListReviews' => array(
						'name'  => $this->l('Reviews hook'),
						'tooltip' => $this->l('All data hooked to displayProductListReviews'),
						'value' => 1,
						'type'  => 'switcher',
						'class' => 'product_option',
					),
				);
				if (Module::isInstalled('productlistthumbnails'))
					$fields['thumbnails'] = array(
						'name'  => $this->l('Product thumbnails'),
						'value' => 0,
						'type'  => 'switcher',
						'class' => 'product_option',
					);
			break;
		}
		return $fields;
	}

	public function install()
	{
		$installed = true;
		if (!$this->prepareDatabaseTables()
			|| !parent::install()
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayBackofficeHeader'))
			$installed = false;

		if ($installed)
			$this->prepareDemoContent();

		return $installed;
	}

	public function prepareDatabaseTables()
	{
		$sql = array();
		$sql[] = '
			CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'easycarousels (
			id_carousel int(10) unsigned NOT NULL,
			id_shop int(10) unsigned NOT NULL,
			hook_name varchar(128) NOT NULL,
			in_tabs tinyint(1) NOT NULL DEFAULT 1,
			active tinyint(1) NOT NULL DEFAULT 1,
			position tinyint(1) NOT NULL,
			type varchar(128) NOT NULL,
			name_multilang text NOT NULL,
			settings text NOT NULL,
			PRIMARY KEY (id_carousel, id_shop),
			KEY hook_name (hook_name),
			KEY active (active),
			KEY in_tabs (in_tabs)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		$sql[] = '
			CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'ec_hook_settings (
			hook_name varchar(64) NOT NULL,
			id_shop int(10) unsigned NOT NULL,
			display text NOT NULL,
			PRIMARY KEY (hook_name, id_shop)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

		$created = $this->runSql($sql);
		if (!$created)
			$this->context->controller->errors[] = $this->l('Database table was not installed properly');
		return $created;
	}

	public function prepareDemoContent()
	{
		$demo_file_path = $this->local_path.'democontent/carousels.txt';
		if (file_exists($demo_file_path))
			$this->importCarousels($demo_file_path);
	}

	public function uninstall()
	{
		$sql = array();

		$sql[] = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'easycarousels';

		if (!$this->runSql($sql) ||	!parent::uninstall())
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

	public function hookDisplayBackOfficeHeader()
	{
		if (Tools::getValue('configure') != $this->name)
			return;
		$this->context->controller->addJquery();
		$this->context->controller->addCSS($this->_path.'views/css/back.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/common-classes.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/back.js');
		$this->context->controller->addJqueryUI('ui.sortable');
	}

	/**
	* easycarousels table has a composite KEY that cannot be autoincremented
	**/
	public function getNewCarouselId()
	{
		$max_id = $this->db->getValue('SELECT MAX(id_carousel) FROM '._DB_PREFIX_.'easycarousels');
		return (int)$max_id + 1;
	}

	public function getNextPosition($hook_name)
	{
		$max_position = $this->db->getValue('
			SELECT MAX(position) FROM '._DB_PREFIX_.'easycarousels WHERE hook_name = \''.pSQL($hook_name).'\'
		');
		return (int)$max_position + 1;
	}

	public function getContent()
	{
		$this->failed_txt = $this->l('Failed');
		$this->saved_txt = $this->l('Saved');

		if (Tools::isSubmit('ajax') && Tools::isSubmit('action'))
		{
			$action_method = 'ajax'.Tools::getValue('action');
			$this->$action_method();
		}

		// Plain js for retro-compatibility. Media::addJsDef for BO works since 1.6.0.11
		$html = '
			<script type="text/javascript">
				var failedTxt = \''.$this->failed_txt.'\';
				var savedTxt = \''.$this->saved_txt.'\';
				var areYouSureTxt = \''.$this->l('Are you sure?').'\';
			</script>
		';
		if (Tools::getValue('action') == 'exportCarousels')
			$html .= $this->exportCarousels();
		if (Tools::getValue('action') == 'importCarousels')
		{
			$this->importCarousels();
			$html .= $this->import_response;
		}
		$html .= $this->displayForm();
		return $html;
	}

	private function displayForm()
	{
		$carousels = $this->getAllCarousels();
		$hooks = $this->getAvailableHooks();

		$sorted_hooks = array();
		foreach (array_keys($carousels) as $hook_name)
			$sorted_hooks[$hook_name] = count($carousels[$hook_name]);
		arsort($sorted_hooks);

		foreach ($hooks as $hook_name => $count)
			if (!isset($sorted_hooks[$hook_name]))
				$sorted_hooks[$hook_name] = $count;

		$this->context->smarty->assign(array(
			'hooks' => $sorted_hooks,
			'carousels' => $carousels,
			'type_names' => $this->getTypeNames(),
			'multishop_warning' => count(Shop::getContextListShopID()) > 1 ? true : false,
			'id_lang_current' => $this->context->language->id,
			'iso_lang_current' => $this->context->language->iso_code,
		));

		$this->context->controller->modals[] = array(
			'modal_id' => 'modal_importer_info',
			'modal_class' => 'modal-md',
			'modal_title' => '<i class="icon-file-zip-o"></i> '.$this->l('How to use the importer'),
			'modal_content' => $this->display($this->local_path, 'views/templates/admin/importer-how-to.tpl'),
		);
		return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
	}

	public function getAvailableHooks()
	{
		$methods = get_class_methods(__CLASS__);
		$methods_to_exclude = array('hookDisplayBackOfficeHeader', 'hookDisplayHeader');
		$available_hooks = array();
		foreach ($methods as $m)
			if (Tools::substr($m, 0, 11) === 'hookDisplay' && !in_array($m, $methods_to_exclude))
				$available_hooks[str_replace('hookDisplay', 'display', $m)] = 0;
		ksort($available_hooks);
		return $available_hooks;
	}

	public function exportCarousels()
	{
		$languages = Language::getLanguages(false);
		$lang_id_iso = array();
		foreach ($languages as $lang)
			$lang_id_iso[$lang['id_lang']] = $lang['iso_code'];

		$id_shop_default = Configuration::get('PS_SHOP_DEFAULT');
		$id_lang_default = Configuration::get('PS_LANG_DEFAULT');
		$tables_to_export = array(
			'easycarousels',
			'ec_hook_settings',
			'hook_module_exceptions',
			'hook_module'
		);
		$export_data = array();
		foreach ($tables_to_export as $table_name)
		{
			$data_from_db = $this->db->executeS('SELECT * FROM '._DB_PREFIX_.pSQL($table_name));
			$ret = array();
			switch ($table_name)
			{
				case 'easycarousels':
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$name_multilang = Tools::jsonDecode($d['name_multilang']);
						$name_multilang_iso = array();
						foreach ($name_multilang as $id_lang => $name)
						{
							$lang_iso = $id_lang == $id_lang_default ? 'LANG_ISO_DEFAULT' : $lang_id_iso[$id_lang];
							$name_multilang_iso[$lang_iso] = $name;
						}
						$d['name_multilang'] = Tools::jsonEncode($name_multilang_iso);
						$ret[$id_shop][$d['id_carousel']] = $d;
					}
				break;
				case 'ec_hook_settings':
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$ret[$id_shop] = $d;
					}
				case 'hook_module_exceptions':
					$data_from_db = $this->db->executeS('
						SELECT hme.*, h.name AS hook_name
						FROM '._DB_PREFIX_.'hook_module_exceptions hme
						LEFT JOIN '._DB_PREFIX_.'hook h ON h.id_hook = hme.id_hook
						WHERE id_module = '.(int)$this->id.'
					');
					foreach ($data_from_db as $d)
					{
						$id_shop = $d['id_shop'] == $id_shop_default ? 'ID_SHOP_DEFAULT' : $d['id_shop'];
						$ret[$id_shop][$d['hook_name']][] = $d['file_name'];
					}
				break;
				case 'hook_module':
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
		$export_data = Tools::jsonEncode($export_data);
		$file_name = 'carousels-'.date('d-m-Y').'.txt';
		header('Content-disposition: attachment; filename='.$file_name);
		header('Content-type: text/plain');
		echo $export_data;
		exit();
	}

	public function ajaxImportCarousels()
	{
		if ($this->importCarousels())
			$ret = array('upd_html' => utf8_encode($this->import_response.$this->displayForm()));
		else
			$ret = array('errors' => $this->import_response);
		exit(Tools::jsonEncode($ret));
	}

	public function importCarousels($file_path = false)
	{
		if (!$file_path)
		{
			if (!isset($_FILES['carousels_data_file']) || !is_uploaded_file($_FILES['carousels_data_file']['tmp_name']))
				return $this->displayError($this->l('File not uploaded'));
			$file_path = $_FILES['carousels_data_file']['tmp_name'];
		}

		$imported_data = Tools::jsonDecode(Tools::file_get_contents($file_path), true);
		$shop_ids = Shop::getContextListShopID();
		$languages = Language::getLanguages(false);
		$lang_iso_id = array();
		foreach ($languages as $lang)
			$lang_iso_id[$lang['iso_code']] = $lang['id_lang'];

		$tables_to_fill = array();
		$exceptions_data = array();
		$hooks_data = array();

		foreach ($shop_ids as $id_shop)
		{
			$carousels = array();
			if (isset($imported_data['easycarousels'][$id_shop]))
				$carousels = $imported_data['easycarousels'][$id_shop];
			else
				$carousels = $imported_data['easycarousels']['ID_SHOP_DEFAULT'];

			foreach ($carousels as $c)
			{
				$c['id_shop'] = $id_shop;
				$c['name_multilang'] = Tools::jsonDecode($c['name_multilang'], true);
				$name_upd = array();
				foreach ($lang_iso_id as $iso => $id_lang)
					$name_upd[$id_lang] = isset($c['name_multilang'][$iso]) ? $c['name_multilang'][$iso] : $c['name_multilang']['LANG_ISO_DEFAULT'];
				$c['name_multilang'] = Tools::jsonEncode($name_upd);
				$tables_to_fill['easycarousels'][] = $c;
			}

			// ec_hook_settings
			if ($imported_data['ec_hook_settings'])
			{
				if (isset($imported_data['ec_hook_settings'][$id_shop]))
					$settings_data = $imported_data['ec_hook_settings'][$id_shop];
				else
					$settings_data = $imported_data['ec_hook_settings']['ID_SHOP_DEFAULT'];
				$settings_data['id_shop'] = $id_shop;
				$tables_to_fill['ec_hook_settings'][] = $settings_data;
			}

			// exceptions
			if ($imported_data['hook_module_exceptions'])
				if (isset($imported_data['hook_module_exceptions'][$id_shop]))
					$exceptions_data[$id_shop] = $imported_data['hook_module_exceptions'][$id_shop];
				else
					$exceptions_data[$id_shop] = $imported_data['hook_module_exceptions']['ID_SHOP_DEFAULT'];

			// hooks & positions
			if ($imported_data['hook_module'])
				if (isset($imported_data['hook_module'][$id_shop]))
					$hooks_data[$id_shop] = $imported_data['hook_module'][$id_shop];
				else
					$hooks_data[$id_shop] = $imported_data['hook_module']['ID_SHOP_DEFAULT'];
		}

		$sql = array();
		foreach ($tables_to_fill as $table_name => $rows_to_insert)
		{
			$db_columns = $this->db->executeS('SHOW COLUMNS FROM '._DB_PREFIX_.pSQL($table_name));
			foreach ($db_columns as &$col)
				$col = $col['Field'];
			$test_row = current($rows_to_insert);
			foreach (array_keys($test_row) as $k => $col_name)
				if ($db_columns[$k] != $col_name)
				{
					$err = $this->l('This file can not be used for import. Reason: Database tables don\'t match (%s).');
					return $this->throwError(sprintf($err, _DB_PREFIX_.$table_name));
				}

			$sql[] = 'DELETE FROM '._DB_PREFIX_.pSQL($table_name).' WHERE id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')';
			$rows = array();
			$column_names = array();
			foreach ($rows_to_insert as $row)
			{
				if (!$column_names)
					$column_names = array_keys($row);
				foreach ($row as &$r)
					$r = pSQL($r);
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
			$this->throwError($this->l('Nothing to import'));

		if ($imported = $this->runSql($sql))
		{
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

					if (isset($exceptions_data[$id_shop][$hook_name]))
					{
						$except = $exceptions_data[$id_shop][$hook_name];
						$imported &= $this->unregisterExceptions($id_hook, array($id_shop));
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

	public function hookDisplayHeader()
	{
		$this->context->controller->addJqueryPlugin('bxslider');
		$this->context->controller->addCSS($this->_path.'/views/css/front.css', 'all');
		$this->context->controller->addCSS($this->_path.'/views/css/bx-styles.css', 'all');
		$this->context->controller->addJS($this->_path.'/views/js/front.js');
		$this->context->controller->addjqueryPlugin('fancybox');
		if (version_compare(_PS_VERSION_, '1.6.11', '<') === true)
			$is_mobile = $this->context->getMobileDetect()->isMobile();
		else
			$is_mobile = $this->context->isMobile();
		$max_items = $this->context->smarty->tpl_vars['comparator_max_item']->value;
		Media::addJsDef(array(
			'isMobile' => $is_mobile,
			// comparator variables
			'comparator_max_item' => $max_items,
			'comparedProductsIds' => $this->context->smarty->tpl_vars['compared_products']->value,
			'min_item' => $this->l('Please select at least one product'),
			'max_item' => sprintf($this->l('You cannot add more than %d product(s) to the product comparison'), $max_items),
		));
	}

	public function ajaxGetCarouselsInHook()
	{
		// $time_start = microtime(true);
		$hook_name = Tools::getValue('hook_name');
		$id_product = Tools::getValue('id_product');
		$id_category = Tools::getValue('id_category');

		$carousels = $this->getAllCarousels('in_tabs', $hook_name, true, $id_product, $id_category);
		if (!isset ($carousels[0]))
			$carousels[0] = array();
		if (!isset ($carousels[1]))
			$carousels[1] = array();

		$this->context->smarty->assign(
			array(
				'carousels_one_by_one' => $carousels[0],
				'carousels_in_tabs' => $carousels[1],
				'link' => $this->context->link,
				'hook_name' => $hook_name,
				'display_settings' => $this->getHookDisplaySettings($hook_name),
			)
		);
		$carousels_html = $this->display(__FILE__, 'views/templates/hook/carousel.tpl');
		$ret = array(
			'carousels_html' => utf8_encode($carousels_html),
			// 'time_'.$hook_name => microtime(true) - $time_start,
		);
		die(Tools::jsonEncode($ret));
	}

	public function displayNativeHook($hook_name, $id_product = 0, $id_category = 0)
	{
		$params = array(
			'ajaxGetCarouselsInHook' => 1,
			'hook_name' => $hook_name,
			'id_product' => $id_product,
			'id_category' => $id_category,
			'current_cat' => Tools::getValue('controller') == 'category' ? Tools::getValue('id_category') : 0,
		);
		$ajax_path = $this->context->link->getModuleLink($this->name, 'ajax', $params);
		$ret = '<div class="easycarousels" data-ajaxpath="'.$ajax_path.'"></div>';
		return $ret;
	}

	public function hookDisplayHome()
	{
		return $this->displayNativeHook('displayHome');
	}

	public function hookDisplayLeftColumn()
	{
		return $this->displayNativeHook('displayLeftColumn');
	}

	public function hookDisplayRightColumn()
	{
		return $this->displayNativeHook('displayRightColumn');
	}

	public function hookDisplayEasyCarousel1()
	{
		return $this->displayNativeHook('displayEasyCarousel1');
	}

	public function hookDisplayEasyCarousel2()
	{
		return $this->displayNativeHook('displayEasyCarousel2');
	}

	public function hookDisplayEasyCarousel3()
	{
		return $this->displayNativeHook('displayEasyCarousel3');
	}

	public function hookDisplayFooterProduct($params)
	{
		// block native accessories list on product page. Requires override for Product::getAccessories()
		if ($this->db->getValue('SELECT type FROM '._DB_PREFIX_.'easycarousels WHERE type = \'accessories\'
			AND id_shop = '.(int)$this->context->shop->id.' AND active = 1 AND hook_name = \'displayFooterProduct\''))
			$this->context->accessories_displayed = 1;
		return $this->displayNativeHook('displayFooterProduct', $params['product']->id, $params['category']->id);
	}

	public function getStructuredCarouselItems($type, $settings, $id_category, $id_product)
	{
		$items = array();

		$query_extensions = array('join' => '', 'where' => '', 'limit' => '');
		if (!$settings['php']['randomize'])
			$query_extensions['limit'] = 'LIMIT '.(int)$settings['carousel']['total'];
		$current_cat = Tools::getValue('current_cat');
		if ($current_cat && $settings['php']['consider_cat'])
		{
			$query_extensions['join'] = 'LEFT JOIN '._DB_PREFIX_.'category_product cp ON product_shop.id_product = cp.id_product';
			$query_extensions['where'] = 'AND cp.id_category = '.(int)$current_cat;
		}

		switch ($type)
		{
			case 'newproducts':
				$nb_days = Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20;
				$items = $this->db->executeS('
					SELECT product_shop.id_product
					FROM '._DB_PREFIX_.'product_shop product_shop
					'.pSQL($query_extensions['join']).'
					WHERE id_shop = '.(int)$this->context->shop->id.'
					AND product_shop.active = 1
					AND product_shop.date_add > "'.pSQL(date('Y-m-d', strtotime('-'.(int)$nb_days.' DAY'))).'"
					AND product_shop.visibility IN ("both", "catalog")
					'.pSQL($query_extensions['where']).'
					'.pSQL($query_extensions['limit']).'
				');

			break;
			case 'featuredproducts':
				$id_cat_home = $this->context->shop->getCategory();
				$items = $this->db->executeS('
					SELECT p.id_product
					FROM '._DB_PREFIX_.'product p
					INNER JOIN '._DB_PREFIX_.'category_product cp
						ON cp.id_product = p.id_product AND cp.id_category = '.(int)$id_cat_home.'
					'.Shop::addSqlAssociation('product', 'p').'
					'.pSQL($query_extensions['join']).'
					WHERE product_shop.active = 1
					'.pSQL($query_extensions['where']).'
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'pricesdrop':
				$items = $this->db->executeS('
					SELECT sp.id_product
					FROM '._DB_PREFIX_.'specific_price sp
					'.Shop::addSqlAssociation('product', 'sp').'
					'.pSQL($query_extensions['join']).'
					WHERE product_shop.active = 1
					AND sp.id_shop IN (0, '.(int)$this->context->shop->id.')
					AND (sp.from = "0000-00-00 00:00:00" OR sp.from < "'.pSQL(date('Y-m-d G:i:s')).'")
					AND (sp.to = "0000-00-00 00:00:00" OR sp.to > "'.pSQL(date('Y-m-d G:i:s')).'")
					'.pSQL($query_extensions['where']).'
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'bestsellers':
				$items = $this->db->executeS('
					SELECT ps.id_product
					FROM '._DB_PREFIX_.'product_sale ps
					'.Shop::addSqlAssociation('product', 'ps').'
					'.pSQL($query_extensions['join']).'
					WHERE product_shop.active = 1
					'.pSQL($query_extensions['where']).'
					ORDER BY quantity DESC
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'catproducts':
				if (isset($settings['special']['cat_ids']) && $cat_ids = explode(',', $settings['special']['cat_ids']))
				{
					$items = $this->db->executeS('
						SELECT cp.id_product
						FROM '._DB_PREFIX_.'category_product cp
						'.Shop::addSqlAssociation('product', 'cp').'
						WHERE product_shop.active = 1
						AND cp.id_category IN ('.implode(', ', array_map('intval', $cat_ids)).')
						GROUP BY cp.id_product
						'.pSQL($query_extensions['limit']).'
					');
				}
			break;
			case 'bymanufacturer':
				if (isset($settings['special']['id_manufacturer']) && $settings['special']['id_manufacturer'])
				{
					$items = $this->db->executeS('
						SELECT p.id_product
						FROM '._DB_PREFIX_.'product p
						'.Shop::addSqlAssociation('product', 'p').'
						'.pSQL($query_extensions['join']).'
						WHERE product_shop.active = 1
						AND p.id_manufacturer = '.(int)$settings['special']['id_manufacturer'].'
						'.pSQL($query_extensions['where']).'
						'.pSQL($query_extensions['limit']).'
					');
				}
			break;
			case 'bysupplier':
				if (isset($settings['special']['id_supplier']) && $settings['special']['id_supplier'])
				{
					$items = $this->db->executeS('
						SELECT p.id_product
						FROM '._DB_PREFIX_.'product p
						'.Shop::addSqlAssociation('product', 'p').'
						'.pSQL($query_extensions['join']).'
						WHERE product_shop.active = 1
						AND p.id_supplier = '.(int)$settings['special']['id_supplier'].'
						'.pSQL($query_extensions['where']).'
						'.pSQL($query_extensions['limit']).'
					');
				}
			break;
			case 'samecategory':
				$items = $this->db->ExecuteS('
					SELECT p.id_product
					FROM '._DB_PREFIX_.'product p
					INNER JOIN '._DB_PREFIX_.'category_product cp ON cp.id_product = p.id_product AND cp.id_category = '.(int)$id_category.'
					'.Shop::addSqlAssociation('product', 'p').'
					AND p.id_product <> '.(int)$id_product.'
					WHERE product_shop.active = 1
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'samefeature':
				if (isset($settings['id_feature']) && $settings['id_feature'] != 0)
				{
					// executeS for multifeature support
					$feat_vals_db = $this->db->executeS('
						SELECT id_feature_value
						FROM '._DB_PREFIX_.'feature_product
						WHERE id_feature = '.(int)$settings['id_feature'].'
						AND id_product='.(int)$id_product);
					$feat_val_ids = array();
					foreach ($feat_vals_db as $fv)
						$feat_val_ids[$fv['id_feature_value']] = (int)$fv['id_feature_value'];

					if ($feat_val_ids)
					{
						$items = $this->db->ExecuteS('
							SELECT fp.id_product
							FROM '._DB_PREFIX_.'feature_product fp
							'.Shop::addSqlAssociation('product', 'fp').'
							WHERE product_shop.active = 1
							AND id_feature_value IN ('.implode(', ', array_map('intval', $feat_val_ids)).')
							AND fp.id_product <> '.(int)$id_product.'
							'.pSQL($query_extensions['limit']).'
						');
					}
				}
			break;
			case 'accessories':
				$items = $this->db->ExecuteS('
					SELECT a.id_product_2 AS id_product
					FROM '._DB_PREFIX_.'accessory a
					INNER JOIN '._DB_PREFIX_.'product_shop product_shop
						ON (product_shop.id_product = a.id_product_1 AND product_shop.id_shop = '.(int)$this->context->shop->id.')
					WHERE a.id_product_1 = '.(int)$id_product.'
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'manufacturers':
				$items = $this->db->ExecuteS('
					SELECT m.id_manufacturer AS id, m.name
					FROM '._DB_PREFIX_.'manufacturer m
					'.Shop::addSqlAssociation('manufacturer', 'm').'
					'.pSQL($query_extensions['join']).'
					WHERE m.active = 1
					'.pSQL($query_extensions['where']).'
					'.pSQL($query_extensions['limit']).'
				');
			break;
			case 'suppliers':
				$items = $this->db->ExecuteS('
					SELECT s.id_supplier AS id, s.name
					FROM '._DB_PREFIX_.'supplier s
					'.Shop::addSqlAssociation('supplier', 's').'
					'.pSQL($query_extensions['join']).'
					WHERE s.active = 1
					'.pSQL($query_extensions['where']).'
					'.pSQL($query_extensions['limit']).'
				');
			break;
		}

		// not using RAND() in query, because of performance issues
		if ($settings['php']['randomize'])
		{
			shuffle($items);
			$items = array_slice($items, 0, $settings['carousel']['total']);
		}

		$structured_items = array();
		$current_row = 1;
		$current_col = 0;
		foreach ($items as $item)
		{
			if ($current_col >= ceil(count($items) / $settings['carousel']['r']))
			{
				$current_row++;
				$current_col = 0;
			}
			$current_col++;

			if ($type == 'suppliers' || $type == 'manufacturers')
				$item['image_url'] = $this->getImageUrl($item['id'], $type, $settings['tpl']['image_type']);
			else
				$item = $this->getPropertiesById($item['id_product'], $settings['tpl']['product_cat'], $settings['tpl']['product_man']);
			$structured_items[$current_col][$current_row] = $item;
		}
		return $structured_items;
	}

	public function getImageUrl($id, $resource_type, $image_type)
	{
		$dir = $resource_type == 'manufacturers' ? _THEME_MANU_DIR_ : _THEME_SUP_DIR_;
		$url = $dir.$this->context->language->iso_code.'.jpg';
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$dir.$id.'-'.$image_type.'.jpg'))
			$url = $dir.$id.'-'.$image_type.'.jpg';
		return $url;
	}

	public function getPropertiesById($id, $show_cat, $show_man)
	{
		$product = new Product($id, false, $this->context->language->id);
		$product_infos = array();
		$product_infos['id_product'] = $id;
		$product_infos['id_manufacturer'] = $product->id_manufacturer;
		$product_infos['show_price'] = $product->show_price;
		$product_infos['on_sale'] = $product->on_sale;
		$product_infos['name'] = $product->name;
		$product_infos['description_short'] = $product->description_short;
		$product_infos['link_rewrite'] = $product->link_rewrite;
		$product_infos['reference'] = $product->reference;
		$product_infos['available_for_order'] = $product->available_for_order;
		$product_infos['id_category_default'] = $product->id_category_default;
		$product_infos['out_of_stock'] = $product->out_of_stock;
		$product_infos['minimal_quantity'] = $product->minimal_quantity;
		$product_infos['customizable'] = $product->customizable;
		$product_infos['ean13'] = $product->ean13;
		$product_infos['cat_name'] = $show_cat ? $this->getCatNameById($product->id_category_default) : '';
		$product_infos['man_name'] = $show_man ? Manufacturer::getNameById($product->id_manufacturer) : '';
		$image = $product->getCover($id);

		$product_infos['id_image'] = $image['id_image'];
		if ($this->productIsNew($product->date_add))
			$product_infos['new'] = 1;
		$product_infos = Product::getProductProperties($this->context->language->id, $product_infos);
		return $product_infos;
	}

	public function getCatNameById($id_cat)
	{
		$name = $this->db->getValue('
			SELECT name FROM '._DB_PREFIX_.'category_lang
			WHERE id_category = '.(int)$id_cat.'
			AND id_lang = '.(int)$this->context->language->id.'
			AND id_shop = '.(int)$this->context->shop->id);
		return $name;
	}

	public function productIsNew($date_add)
	{
		if (!isset($this->nb_days_new))
		{
			$this->nb_days_new = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
			$this->now = time();
		}
		$diff = floor($this->now - strtotime($date_add)) / 86400;
		return $diff <= $this->nb_days_new;
	}

	public function ajaxCallCarouselForm()
	{
		$id_carousel = (int)Tools::getValue('id_carousel');
		$hook_name = Tools::getValue('hook_name');
		$utf8_encoded_form = $this->renderCarouselForm($id_carousel, $hook_name);
		die(Tools::jsonEncode($utf8_encoded_form));
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

	public function getHookDisplaySettings($hook_name)
	{
		$settings = array(
			'compact_tabs' => 1,
			'custom_class' => ''
		);
		$saved_settings = $this->db->getValue('
			SELECT display FROM '._DB_PREFIX_.'ec_hook_settings
			WHERE hook_name = \''.pSQL($hook_name).'\' AND id_shop = '.(int)$this->context->shop->id
		);

		if ($saved_settings)
		{
			$saved_settings = Tools::jsonDecode($saved_settings);
			foreach ($settings as $name => &$val)
				if (isset($saved_settings->$name))
					$val = $saved_settings->$name;
		}
		return $settings;
	}

	public function ajaxSaveHookSettings()
	{
		$hook_name = Tools::getValue('hook_name');
		$id_hook = Hook::getIdByName($hook_name);
		$settings_type = Tools::getValue('settings_type');
		$saved = false;
		if ($settings_type == 'display')
		{
			$display_settings = Tools::jsonEncode(Tools::getValue('settings'));
			$rows = array();
			foreach (Shop::getContextListShopID() as $id_shop)
				$rows[] = '(\''.pSQL($hook_name).'\', '.(int)$id_shop.', \''.pSQL($display_settings).'\')';
			$saved = $this->db->execute('
				INSERT INTO '._DB_PREFIX_.'ec_hook_settings
				VALUES '.implode(', ', $rows).'
				ON DUPLICATE KEY UPDATE display = VALUES(display)
			');
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
					$saved = $module->unregisterHook(Hook::getIdByName($hook_name));
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

	public function renderCarouselForm($id_carousel, $hook_name, $full = true)
	{
		$carousel_info = $this->db->getRow('
			SELECT * FROM '._DB_PREFIX_.'easycarousels
			WHERE id_carousel = '.(int)$id_carousel
		);

		if ($carousel_info)
		{
			$carousel_info['name_multilang'] = Tools::jsonDecode($carousel_info['name_multilang'], true);
			$carousel_info['name'] = $this->getLangName($carousel_info['name_multilang']);
			$carousel_info['settings'] = Tools::jsonDecode($carousel_info['settings'], true);
		}
		else
		{
			// default carousel settings
			$carousel_info = array (
				'id_carousel' => (int)$id_carousel,
				'active' => 1,
				'type' => 'newproducts',
				'in_tabs' => $this->isColumnHook($hook_name) ? 0 : 1,
				'hook_name' => $hook_name,
			);
		}
		$languages = Language::getLanguages(false);
		$this->context->smarty->assign(array(
			'carousel' => $carousel_info,
			'type_names' => $this->getTypeNames(),
			'fields' => array(
				'php' => $this->getFields('php'),
				'special' => $this->getFields('special'),
				'tpl' => $this->getFields('tpl'),
				'carousel' => $this->getFields('carousel'),
			),
			'languages' => $languages,
			'id_lang_current' => $this->context->language->id,
			'full' => $full,
		));
		$form = $this->display(__FILE__, 'views/templates/admin/carousel-form.tpl');
		return utf8_encode($form);
	}

	public function isColumnHook($hook_name)
	{
			$column_hooks = array('displayLeftColumn', 'displayRightColumn');
			return in_array($hook_name, $column_hooks);
	}

	public function ajaxBulkAction()
	{
		$action = Tools::getValue('act');
		$carousel_ids = Tools::getValue('ids');
		if (!$carousel_ids)
			$this->throwError($this->l('Please make a selection'));
		$shop_ids = Shop::getContextListShopID();
		$success = true;
		$this->response_text = $this->saved_txt;

		switch ($action)
		{
			case 'enable':
			case 'disable':
				$active = $action == 'enable';
				$success &= $this->db->execute('
					UPDATE '._DB_PREFIX_.'easycarousels SET active = '.(int)$active.'
					WHERE id_carousel IN ('.implode(', ', array_map('intval', $carousel_ids)).')
					AND id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')
				');
			break;
			case 'group_in_tabs':
			case 'ungroup':
				$in_tabs = $action == 'group_in_tabs';
				$success &= $this->db->execute('
					UPDATE '._DB_PREFIX_.'easycarousels SET in_tabs = '.(int)$in_tabs.'
					WHERE id_carousel IN ('.implode(', ', array_map('intval', $carousel_ids)).')
					AND id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')
				');
			break;
			case 'delete':
				foreach ($carousel_ids as $id_carousel)
					$success &= $this->deleteCarousel($id_carousel);
			break;

		}
		$ret = array(
			'success' => $success,
			'reponseText' => $this->response_text,
		);
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxSaveCarousel()
	{
		$id_carousel = Tools::getValue('id_carousel');
		if ($id_carousel == 0)
			$id_carousel = $this->getNewCarouselId();

		$params_string = Tools::getValue('carousel_data');
		$params = array();
		parse_str($params_string, $params);

		$hook_name = $params['hook_name'];
		$settings = $params['settings'];

		if (trim($params['name_multilang'][Configuration::get('PS_LANG_DEFAULT')]) == '' && isset($params['in_tabs']))
		{
			$lang_name = $this->db->getValue('
				SELECT name FROM '._DB_PREFIX_.'lang WHERE id_lang = '.(int)Configuration::get('PS_LANG_DEFAULT')
			);
			$this->errors[] = $this->l('Please fill carousel name at least for the following language: ').$lang_name;
		}

		if ($params['type'] == 'catproducts')
		{
			$ids_string = preg_replace('/[^0-9,]/', '', $settings['special']['cat_ids']);
			$ids_string = trim($ids_string, ',');
			$params['settings']['special']['cat_ids'] = $ids_string;
			if ($ids_string == '')
				$this->errors[] = $this->l('Please add at least one category id');
		}
		if ($params['type'] == 'bymanufacturer' && !$settings['special']['id_manufacturer'])
			$this->errors[] = $this->l('Please select a manufacturer');
		if ($params['type'] == 'bysupplier' && !$settings['special']['id_supplier'])
			$this->errors[] = $this->l('Please select a supplier');
		if ($params['type'] == 'samefeature' && !$settings['special']['id_feature'])
			$this->errors[] = $this->l('Please select a feature');

		if (!$this->errors && !$this->saveCarousel($id_carousel, $hook_name, $params))
			$this->errors[] = $this->l('Carousel not saved');

		if ($this->errors)
			$this->throwError($this->errors);

		$result = array(
			'updated_form_header' => $this->renderCarouselForm($id_carousel, false),
			'responseText' => $this->l('Saved'),
		);
		exit(Tools::jsonEncode($result));
	}

	/**
	* @return boolean saved
	**/
	public function saveCarousel($id_carousel, $hook_name, $params)
	{
		$name_multilang = Tools::jsonEncode($params['name_multilang']);

		foreach (array('php', 'tpl', 'carousel', 'special') as $type)
			foreach ($this->getFields($type) as $name => $field)
				if (!isset($params['settings'][$type][$name]))
					$params['settings'][$type][$name] = $field['value'];
		$settings = Tools::jsonEncode($params['settings']);

		$shop_ids = Shop::getContextListShopID();
		$insert_rows = array();
		foreach ($shop_ids as $id_shop)
		{
			$insert_rows[$id_shop] = '(';
			$insert_rows[$id_shop] .= (int)$id_carousel;
			$insert_rows[$id_shop] .= ', '.(int)$id_shop;
			$insert_rows[$id_shop] .= ', \''.pSQL($hook_name).'\'';
			$insert_rows[$id_shop] .= ', '.(int)$params['in_tabs'];
			$insert_rows[$id_shop] .= ', '.(int)$params['active'];
			$insert_rows[$id_shop] .= ', '.(int)$this->getNextPosition($hook_name);
			$insert_rows[$id_shop] .= ', \''.pSQL($params['type']).'\'';
			$insert_rows[$id_shop] .= ', \''.pSQL($name_multilang).'\'';
			$insert_rows[$id_shop] .= ', \''.pSQL($settings).'\'';
			$insert_rows[$id_shop] .= ')';
		}

		$insert_query = '
			INSERT INTO '._DB_PREFIX_.'easycarousels
			VALUES '.implode(',', $insert_rows).'
			ON DUPLICATE KEY UPDATE
			type = VALUES(type),
			name_multilang = VALUES(name_multilang),
			settings = VALUES(settings)
		';
		$saved = $this->db->execute($insert_query);
		if ($saved && !$this->isRegisteredInHook($hook_name))
			$this->registerHook($hook_name);
		return $saved;
	}

	public function ajaxToggleParam()
	{
		$id_carousel = Tools::getValue('id_carousel');
		$param_name = Tools::getValue('param_name');
		$param_value = Tools::getValue('param_value');
		if (!$param_name)
			$this->throwError($this->l('Parameters not provided correctly'));

		$shop_ids = Shop::getContextListShopID();
		$update_query = '
			UPDATE '._DB_PREFIX_.'easycarousels
			SET `'.bqSQL($param_name).'` = '.(int)$param_value.'
			WHERE id_carousel = '.(int)$id_carousel.'
			AND id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')
		';
		$ret = array('success' => $this->db->execute($update_query));
		exit(Tools::jsonEncode($ret));
	}

	public function ajaxDeleteCarousel()
	{
		$id_carousel = Tools::getValue('id_carousel');
		$result = array(
			'deleted' => $this->deleteCarousel($id_carousel),
		);
		die(Tools::jsonEncode($result));
	}

	public function deleteCarousel($id_carousel)
	{
		$shop_ids = Shop::getContextListShopID();
		$delete_query = '
			DELETE FROM '._DB_PREFIX_.'easycarousels
			WHERE id_carousel = '.(int)$id_carousel.'
			AND id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')';
		return $this->db->execute($delete_query);
	}

	public function ajaxUpdatePositionsInHook()
	{
		$ordered_ids = Tools::getValue('ordered_ids');
		if (!$ordered_ids)
			$this->throwError($this->l('Ordering failed'));
		$update_rows = array();
		$shop_ids = Shop::getContextListShopID();
		foreach ($shop_ids as $id_shop)
		{
			foreach ($ordered_ids as $k => $id_carousel)
			{
				if ($id_carousel < 1)
					continue;
				$pos = $k + 1;
				$update_rows[] = '('.(int)$id_carousel.', '.(int)$id_shop.', '.(int)$pos.')';
			}
		}
		$update_query = '
			INSERT INTO '._DB_PREFIX_.'easycarousels (id_carousel, id_shop, position)
			VALUES '.implode(', ', $update_rows).'
			ON DUPLICATE KEY UPDATE
			position = VALUES(position)
		';
		if (!$this->db->execute($update_query))
			$this->throwError($this->l('Ordering failed'));
		$ret = array ('successText' => $this->l('Saved'));
		die(Tools::jsonEncode($ret));
	}

	public function getLangName($name_multilang)
	{
		if (!is_array($name_multilang))
			$name_multilang = Tools::jsonDecode($name_multilang, true);
		if (isset($name_multilang[$this->context->language->id]))
			$name = $name_multilang[$this->context->language->id];
		else if (isset($name_multilang[Configuration::get('PS_LANG_DEFAULT')]))
			$name = $name_multilang[Configuration::get('PS_LANG_DEFAULT')];
		else
			$name = '';
		return $name;
	}

	public function getAllCarousels($sort_by = 'hook_name', $hook_name = false, $front = false, $id_product = 0, $id_category = 0)
	{
		$shop_ids = Shop::getContextListShopID();
		$where = 'WHERE id_shop IN ('.implode(', ', array_map('intval', $shop_ids)).')';
		if ($hook_name)
			$where .= ' AND hook_name = \''.pSQL($hook_name).'\'';
		if ($front)
			$where .= ' AND active = 1';

		$carousels = $this->db->ExecuteS('SELECT * FROM '._DB_PREFIX_.'easycarousels '.$where.' ORDER BY position');

		if ($sort_by)
		{
			$sorted_carousels = array();
			foreach ($carousels as $k => $c)
			{
				// id_carousel, id_shop, in_tabs etc...
				foreach ($c as $name => $value)
					$sorted_carousels[$c[$sort_by]][$k][$name] = $value;
				$sorted_carousels[$c[$sort_by]][$k]['name'] = $this->getLangName($c['name_multilang']);

				if ($front)
				{
					$settings = Tools::jsonDecode($c['settings'], true);
					$sorted_carousels[$c[$sort_by]][$k]['settings'] = $settings;

					if (!$sorted_carousels[$c[$sort_by]][$k]['name'] && $c['in_tabs'])
						foreach ($this->getTypeNames() as $names)
							foreach ($names as $type => $name)
								if ($type == $c['type'])
									$sorted_carousels[$c[$sort_by]][$k]['name'] = $name;
					if (isset($settings['tpl']['view_all']) && $settings['tpl']['view_all'])
						$sorted_carousels[$c[$sort_by]][$k]['view_all_link'] = $this->getLinkToAllItems($c['type'], $settings);
					$items = $this->getStructuredCarouselItems($c['type'], $settings, $id_category, $id_product);
					if (!$items)
						unset($sorted_carousels[$c[$sort_by]][$k]);
					else
						$sorted_carousels[$c[$sort_by]][$k]['items'] = $items;
				}
			}
			$carousels = $sorted_carousels;
		}
		return $carousels;
	}

	public function getLinkToAllItems($carousel_type, $settings)
	{
		$link = '';
		switch ($carousel_type)
		{
			case 'newproducts':
				$link = $this->context->link->getPageLink('new-products');
			break;
			case 'bestsellers':
				$link = $this->context->link->getPageLink('best-sales');
			break;
			case 'pricesdrop':
				$link = $this->context->link->getPageLink('prices-drop');
			break;
			case 'bymanufacturer':
				$link = $this->context->link->getManufacturerLink($settings['special']['id_manufacturer']);
			break;
			case 'bysupplier':
				$link = $this->context->link->getSupplierLink($settings['special']['id_supplier']);
			break;
		}
		return $link;
	}

	public function throwError($errors)
	{
		if (!is_array($errors))
			$errors = array($errors);
		$errors_html = $this->displayError(implode('<br>', $errors));
		if (Tools::isSubmit('ajax'))
			die(Tools::jsonEncode(array('errors' => utf8_encode($errors_html))));
		else
			return $errors_html;
	}
}