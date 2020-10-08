<?php
require_once(dirname(__FILE__) . '/init.php');

use esas\cmsgate\prestashop\CmsgatePaymentModule;
use esas\cmsgate\Registry;
use esas\cmsgate\RegistryHutkigroshPrestashop;
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
        $this->controllers = array('payment', 'paymentWebpay', 'alfaclick', 'callback');
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
        $eripOption = new PaymentOption();
        $eripOption->setModuleName($this->name)
            ->setCallToActionText(RegistryHutkigroshPrestashop::getRegistry()->getConfigWrapper()->getPaymentMethodName())
            ->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true))
            ->setAdditionalInformation(RegistryHutkigroshPrestashop::getRegistry()->getConfigWrapper()->getPaymentMethodDetails() . ViewBuilderPrestashop::elementSandboxMessage());
        $webpayOption = new PaymentOption();
        $webpayOption->setModuleName($this->name)
            ->setCallToActionText(RegistryHutkigroshPrestashop::getRegistry()->getConfigWrapper()->getPaymentMethodNameWebpay())
            ->setAction($this->context->link->getModuleLink($this->name, 'payment_webpay', array(), true))
            ->setAdditionalInformation(RegistryHutkigroshPrestashop::getRegistry()->getConfigWrapper()->getPaymentMethodDetailsWebpay() . ViewBuilderPrestashop::elementSandboxMessage());
        $payment_options = [
            $eripOption, $webpayOption
        ];

        return $payment_options;
    }
}
