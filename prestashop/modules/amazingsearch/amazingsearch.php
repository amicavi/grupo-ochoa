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
include(dirname(__FILE__).'/search.class.php');

class AmazingSearch extends Module
{
	private $limit = 10;
	private $order_by = 'date_add';
	private $order_way = 'DESC';

	public function __construct()
	{
		$this->name = 'amazingsearch';
		$this->tab = 'search_filter';
		$this->version = '1.0.0';
		$this->author = '';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Amazing search');
		$this->description = $this->l('');
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('top') || !$this->registerHook('header'))
			return false;
		return true;
	}

	public function hookHeader()
	{
		$this->context->controller->addCSS(($this->_path).'views/css/amazingsearch.css', 'all');
		$this->context->controller->addJS($this->_path.'views/js/amazingsearch.js');
	}

	public function hookTop()
	{
		$key = $this->getCacheId('amazingsearch-top');
		if (Tools::getValue('search_query') || !$this->isCached('amazingsearch-top.tpl', $key))
		{
			$id_category = null;
			$id_category = (int)Tools::getValue('category_id');
			$this->smarty->assign(array(
				'amazingsearch_type' => 'top',
				'search_query' 		 => (string)Tools::getValue('search_query'),
				'shop_id' 			 => (int)Context::getContext()->shop->id,
				'categories'		 => Category::getSimpleCategories($this->context->language->id),
				'self' 				 =>	 dirname(__FILE__).'/views/templates/hook',
				'id_category' => is_null($id_category) ? 0 : $id_category,
				'lang_id' => $this->context->language->id
				)
			);
		}
		Media::addJsDef(array('amazingsearch_type' => 'top'));
		return $this->display(__FILE__, '/views/templates/hook/amazingsearch-top.tpl', Tools::getValue('search_query') ? null : $key);
	}

	public function ajaxSearch()
	{
		$search_string = Tools::getValue('s');
		$id_category = null;
		$id_category = Tools::getValue('id_category');
		if (empty($id_category))
			$id_category = null;
		$query = Tools::replaceAccentedChars(urldecode($search_string));
		if (empty($search_string)) return false;
		$products = $this->searchProducts($query, (int)Tools::getValue('id_lang'), $id_category);
		$this->smarty->assign(array(
			'products' => $products
		));
		echo Tools::jsonEncode($this->display(__FILE__, '/views/templates/hook/amazingsearch-result.tpl'));
	}

	private function searchProducts($string, $lang = 1, $id_category)
	{
		if (!is_null($id_category))
		{
			$products = Search::find($lang, $string, 1, 10, 'position', 'desc', false, true, null, $id_category);
			return $products['result'];
		}
		else
		{
			$products = Search::find($lang, $string, 1, 10, 'position', 'desc', false);
			return $products['result'];
		}
	}
}

