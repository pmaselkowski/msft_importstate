<?php

/**
 * Custom Order Reference for PrestaShop
 *
 * This software package is licensed under `proprietary` license[s].
 *
 * @package maslosoft/customorderreference_prestashop
 * @license proprietary
 *
 */


use Maslosoft\ImportStates\Data\DataReader;
use Maslosoft\ImportStates\Data\SubiektReader;
use Maslosoft\ImportStates\Installer;
use Maslosoft\ImportStates\Renderers\StatesRenderer;

if (!defined('_PS_VERSION_'))
{
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class msft_importstates extends Module
{

	private const Pfx = 'MSFT_IMPORT_STATES';
	private const FieldFile = self::Pfx . '_FILE';
	private const UploadPath = _PS_ROOT_DIR_ . '/upload';
	protected $config_form = false;

	public $_html;

	public function __construct()
	{
		$this->name = 'msft_importstates';
		$this->tab = 'billing_invoicing';
		$this->version = '1.0.0';
		$this->author = 'Maslosoft LLC';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Import Store States');
		$this->description = $this->l('Import store states from CSV or other supported formats');

		$this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
	}

	public function install()
	{
		return parent::install()
			&& (new Installer)->install();
	}

	public function uninstall()
	{
		return parent::uninstall();
	}

	public function renderForm()
	{
		$format = [
			'form' => [
				'legend' => [
					'title' => $this->l('Import Store States'),
					'icon' => 'icon-cogs'
				],
//				'input' => [
//					[
//						'type' => 'text',
//						'placeholder' => 'Format',
//						'label' => $this->l('File'),
//						'name' => self::FieldFile,
//						'size' => 50,
//						'required' => true,
//					],
//				],
				'submit' => [
					'title' => $this->l('Apply States'),
					'class' => 'btn btn-default pull-right'
				],
			],
		];

		$helper = new HelperForm();
		$helper->submit_action = 'submit' . $this->name;
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
		$helper->title = $this->displayName;

		return $helper->generateForm([$format]);
	}

	/**
	 * Load the configuration form
	 */
	public function getContent()
	{
		if (Tools::isSubmit('submit' . $this->name))
		{
			if (
				Configuration::updateValue(self::FieldFile, Tools::getValue(self::FieldFile))
			)
			{
				$this->_html .= $this->displayConfirmation($this->l('Configuration was saved successfully'));

			}
			else
			{
				$this->_html .= $this->displayWarning($this->l('Configuration was not saved'));
			}
		}

		$this->_html .= $this->renderForm();
		$filename = 'eksport-produktow.txt';
		$path = realpath(self::UploadPath) . '/' . $filename;
		assert(file_exists($path), sprintf('Could not open file `%s` in path `%s`', $filename, realpath(self::UploadPath)));
		$this->_html .= new StatesRenderer(new SubiektReader($path));
		$this->_html .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
		return $this->_html;
	}
}
