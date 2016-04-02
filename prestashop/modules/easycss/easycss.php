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

class EasyCSS extends Module
{
	public function __construct()
	{
		if (!defined('_PS_VERSION_'))
			exit;
		$this->name = 'easycss';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Amazzing';
		$this->need_instance = 0;
		$this->bootstrap = true;
		$this->module_key = '';

		parent::__construct();

		$this->displayName = $this->l('Easy css');
		$this->description = $this->l('Easy css');

		$this->db = Db::getInstance();
		$this->img_dir = $this->_path.'views/img/uploads/';
		$this->img_dir_local = $this->local_path.'views/img/uploads/';
		$this->custom_css_local = $this->local_path.'views/css/custom.css';
	}

	public function install()
	{
		$installed = parent::install()
		&& $this->registerHook('displayHeader')
		&& $this->registerHook('displayBackofficeHeader');
		return $installed;
	}

	public function uninstall()
	{
		$uninstalled = parent::uninstall();
		return $uninstalled;
	}

	public function hookDisplayBackOfficeHeader()
	{
		if (Tools::getValue('configure') == $this->name)
		{
			$this->context->controller->addJquery();
			$this->context->controller->addCSS($this->_path.'views/css/back.css', 'all');
			$this->context->controller->addJS($this->_path.'views/js/back.js');
		}
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

		$html = '
			<script type="text/javascript">
				var failedTxt = \''.$this->failed_txt.'\';
				var savedTxt = \''.$this->saved_txt.'\';
				var imgWarning = \''.$this->l('Please choose a valid image').'\';
				var areYouSureTxt = \''.$this->l('Are you sure?').'\';
			</script>
		';

		$html .= $this->displayForm();
		return $html;
	}

	private function displayForm()
	{
		$this->context->smarty->assign(array(
			'rules' => $this->getRules(),
		));
		return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
	}

	public function getRules($add_path = true)
	{
		$lines = file($this->custom_css_local);
		$rules = array();
		foreach ($lines as $line)
		{
			$line = explode(' {', $line);
			$selector = array_shift($line);
			preg_match('#\(../(.*?)\)#', $line[0], $match);
			if ($add_path)
				$rules[$selector] = $this->_path.'views/'.$match[1];
			else
				$rules[$selector] = basename($match[1]);
		}
		return $rules;
	}

	public function ajaxSaveRule()
	{
		$selector = Tools::getValue('selector');
		if (empty($selector))
			$this->throwError($this->l('Please fill in the selector field'));
		$selector_prev = Tools::getValue('selector_prev');
		$rules = $this->getRules(false);
		$prev_img = $rules[$selector_prev];
		if ($selector_prev && $selector_prev != $selector)
		{
			// keep ordering
			$position = array_search($selector_prev, array_keys($rules));
			$rules = array_slice($rules, 0, $position, true) + array($selector => $prev_img) + array_slice($rules, $position + 1, null, true);
		}
		$saved_img = !empty($_FILES['img']) ? $this->saveUploadedImage($_FILES['img'], $this->img_dir_local) : '';
		if ($saved_img)
		{
			$rules[$selector] = $saved_img;
			unlink($this->img_dir_local.$prev_img);
		}

		if (empty($rules[$selector]))
			$this->throwError($this->l('No image to save'));

		$ret = array(
			'saved' => $this->saveCSS($rules, $this->custom_css_local),
			'img' => $this->img_dir.$rules[$selector],
		);
		exit(Tools::jsonEncode($ret));
	}

	public function saveCSS($rules, $file)
	{
		foreach ($rules as $selector => &$rule)
			$rule = $selector.' {background-image:url(../img/uploads/'.$rule.')}';
		return file_put_contents($file, implode("\n", $rules));
	}

	public function saveUploadedImage($file, $location)
	{
		if (!isset($file['tmp_name']) || empty($file['tmp_name']) || !empty($this->errors))
			return '';

		$max_size = 10485760; // 10 mb

		// Check image validity
		if ($error = ImageManager::validateUpload($file, Tools::getMaxUploadSize($max_size)))
			$this->throwError($error);

		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$new_img_name = $this->getNewFilename($location).'.'.$ext;

		if (!move_uploaded_file($file['tmp_name'], $location.$new_img_name))
			$this->throwError($this->l('Error: image was not copied'));

		return $new_img_name;
	}

	public function getNewFilename($location, $prefix = '')
	{
		do
			$filename = uniqid($prefix);
		while (file_exists($location.$filename));
		return $filename;
	}

	public function ajaxDeleteRule()
	{
		$selector = Tools::getValue('selector');
		$rules = $this->getRules(false);
		unlink($this->img_dir_local.$rules[$selector]);
		unset($rules[$selector]);
		$saved = $this->saveCSS($rules, $this->custom_css_local);
		$ret = array(
			'deleted' => $saved,
		);
		exit(Tools::jsonEncode($ret));
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS($this->_path.'views/css/custom.css', 'all');
	}

	public function throwError($errors, $render_html = true)
	{
		if (!is_array($errors))
			$errors = array($errors);
		$html = '<div class="thrown-errors">'.$this->displayError(implode('<br>', $errors)).'</div>';
		if (!Tools::isSubmit('ajax'))
			return $html;
		if ($render_html)
			$errors = utf8_encode($html);
		die(Tools::jsonEncode(array('errors' => $errors)));
	}
}
