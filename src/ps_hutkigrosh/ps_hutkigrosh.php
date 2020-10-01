<?php
require_once(dirname(__FILE__) . '/init.php');

use esas\cmsgate\prestashop\CmsgatePaymentModule;
use esas\cmsgate\Registry;
use esas\cmsgate\view\ViewBuilderPrestashop;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_Hutkigrosh extends CmsgatePaymentModule
{
    public function __construct()
    {
        parent::__construct();
        $this->controllers = array('payment', 'alfaclick');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('paymentOptions')) {
            return false;
        }
        return true;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return [];
        }
        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)
            ->setCallToActionText(Registry::getRegistry()->getConfigWrapper()->getPaymentMethodName())
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true))
            ->setAdditionalInformation(Registry::getRegistry()->getConfigWrapper()->getPaymentMethodDetails() . ViewBuilderPrestashop::elementSandboxMessage());
        $payment_options = [
            $newOption,
        ];

        return $payment_options;
    }
}
