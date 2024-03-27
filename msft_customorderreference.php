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

use Maslosoft\CustomOrderReference\Constants;
use Maslosoft\CustomOrderReference\Installer;
use Maslosoft\CustomOrderReference\Numberer;

if (!defined('_PS_VERSION_'))
{
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class msft_customorderreference extends Module
{
	private const FieldFormat = 'MSFT_ORDERREFERENCE_FORMAT';
	private const FieldSeparate = 'MSFT_ORDERREFERENCE_SEPARATE';
	private const FieldUpdate = 'MSFT_ORDERREFERENCE_UPDATE';

	protected $config_form = false;

	public $_html;

	public function __construct()
	{
		$this->name = 'msft_customorderreference';
		$this->tab = 'billing_invoicing';
		$this->version = '1.0.0';
		$this->author = 'Maslosoft LLC';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Order Reference');
		$this->description = $this->l('Changes the order reference of any order upon validation of the order.');

		$this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
	}

	public function install()
	{
		return parent::install()
			&& $this->registerHook('actionObjectOrderAddAfter')
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
					'title' => $this->l('Order Reference'),
					'icon' => 'icon-cogs'
				],
				'input' => [
					[
						'type' => 'text',
						'placeholder' => 'Format',
						'label' => $this->l('Format'),
						'name' => self::FieldFormat,
						'size' => 50,
						'required' => true,
					],
					[
						'type' => 'switch',
						'label' => $this->l('Separate numbering for each store'),
						'name' => self::FieldSeparate,
						'size' => 50,
						'required' => true,
						'values' => array(
							array(
								'value' => true,
								'label' => 'Enabled'
							),
							array(
								'value' => false,
								'label' => 'Disabled'
							)
						),
					],
					[
						'type' => 'switch',
						'label' => $this->l('Reset numbers and update existing orders'),
						'hint' => $this->l('This is will change existing orders too, not recommended on production environments'),
						'name' => self::FieldUpdate,
						'size' => 50,
						'required' => true,
						'values' => array(
							array(
								'value' => true,
								'label' => 'Yes'
							),
							array(
								'value' => false,
								'label' => 'No'
							)
						),
					],
				],
				'submit' => [
					'title' => $this->l('Save'),
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
		$helper->fields_value[self::FieldFormat] = Configuration::get(self::FieldFormat);
		$helper->fields_value[self::FieldSeparate] = self::isSeparate();
		$helper->fields_value[self::FieldUpdate] = Configuration::get(self::FieldUpdate);

		return $helper->generateForm([$format]);
	}

	private static function isSeparate(): bool
	{
		return Configuration::get(self::FieldSeparate, null, null, null, false);
	}

	private static function isFormatEmpty(): bool
	{
		$format = trim(Configuration::get(self::FieldFormat));
		if (empty($format))
		{
			return true;
		}
		return false;
	}

	private static function getFormat(): string
	{
		return Configuration::get(self::FieldFormat);
	}

	/**
	 * Load the configuration form
	 */
	public function getContent()
	{
		if (Tools::isSubmit('submit' . $this->name))
		{
			if (
				Configuration::updateValue(self::FieldFormat, Tools::getValue(self::FieldFormat))
				&& Configuration::updateValue(self::FieldSeparate, Tools::getValue(self::FieldSeparate))
				&& Configuration::updateValue(self::FieldUpdate, Tools::getValue(self::FieldUpdate))
			)
			{
				$this->_html .= $this->displayConfirmation($this->l('Configuration was saved successfully'));

				if(Configuration::get(self::FieldUpdate))
				{
					$results = [];
					$results[] = Db::getInstance()->delete(Constants::Table);
					$collection = new PrestaShopCollection(Order::class);
					$count = 0;
					foreach($collection->getAll() as $order)
					{
						$results[] = $this->updateReference($order);
						$count++;
					}
					if(count($results) === array_sum($results))
					{
						$this->_html .= $this->displayConfirmation($this->l('Order numbers were updated successfully'));
					}
				}
			}
			else
			{
				$this->_html .= $this->displayWarning($this->l('Configuration was not saved'));
			}
		}

		$this->_html .= $this->renderForm();
		$preview_reference = $this->getRandomReference();
		$this->context->smarty->assign([
			'preview_reference' => $preview_reference,
		]);
		$this->_html .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
		return $this->_html;
	}

	public function hookActionObjectOrderAddAfter(array $params)
	{
		$order = new Order((int)$params['object']->id);
		$this->updateReference($order);
		$params['object']->reference = $order->reference;
	}

	private function updateReference(Order $order)
	{
		// Don't apply if empty format, but count new numbers
		$numberer = $this->numberer();
		$numberer->setStoreId($order->id_shop);
		$numberer->next($order, !self::isFormatEmpty());
		$numberer->save();
		if (!self::isFormatEmpty())
		{
			return Db::getInstance()->update('orders', ['reference' => $order->reference], 'id_order=' . (int)$order->id, 1);
		}
		return false;
	}

	public function getRandomReference(): string
	{
		$id_order = Db::getInstance()->getValue('SELECT id_order FROM ' . _DB_PREFIX_ . 'orders ORDER BY RAND()');
		$order = new Order($id_order);

		// Don't apply if empty format, but count new numbers
		$this->numberer()->next($order, !self::isFormatEmpty());
		return $order->reference;
	}

	private function numberer(): Numberer
	{
		$numberer = new Numberer(self::getFormat(), self::isSeparate());
		$numberer->setStoreId(Context::getContext()->shop->id);
		return $numberer;
	}
}
