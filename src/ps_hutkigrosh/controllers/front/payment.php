<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/init.php');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPage;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\RegistryHutkigroshPrestashop;

/**
 * @since 1.5.0
 */
class Ps_hutkigroshPaymentModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName()) {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            die($this->module->l('This payment method is not available.', 'payment'));
        }

        $orderWrapper = null;
        if (!empty($_REQUEST[RequestParamsHutkigrosh::ORDER_NUMBER])) //для случая возврата с webpay
            $orderWrapper = RegistryHutkigroshPrestashop::getRegistry()->getOrderWrapperByOrderNumber($_REQUEST[RequestParamsHutkigrosh::ORDER_NUMBER]);
        else {
            $cart = $this->context->cart;
            if ($cart->id == null || $cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
                Tools::redirect('index.php?controller=order&step=1');
            }
            if (Order::getByCartId($cart->id) == null)
                $this->createOrder(); // принудительное создание заказа, т.к. в prestashop заказ создается только после платежа, до этого момента есть только корзина
            $orderWrapper = RegistryHutkigroshPrestashop::getRegistry()->getOrderWrapperForCurrentUser();
        }


        // проверяем, привязан ли к заказу billid, если да,
        // то счет не выставляем, а просто прорисовываем старницу
        if (empty($orderWrapper->getExtId())) {
            $controller = new ControllerHutkigroshAddBill();
            $controller->process($orderWrapper);
        }
        $controller = new ControllerHutkigroshCompletionPage();
        $completionPanel = $controller->process($orderWrapper->getOrderId());
        $data['completionPanel'] = $completionPanel;
//        $data['language'] = array(
//            'id_lang' => $this->context->language->id,
//            'iso_code' => $this->context->language->iso_code
//        );
        $this->context->smarty->assign($data);
        $this->setTemplate('module:' . Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName() . '/views/templates/front/payment_erip.tpl');


        // $customer = new Customer($cart->id_customer);
        // if (!Validate::isLoadedObject($customer))
        //     Tools::redirect('index.php?controller=order&step=1');

        // $currency = $this->context->currency;
        // $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        // $mailVars = array(
        //     '{bankwire_owner}' => Configuration::get('BANK_WIRE_OWNER'),
        //     '{bankwire_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
        //     '{bankwire_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS'))
        // );

        // $this->module->validateOrder($cart->id, Configuration::get('PS_OS_BANKWIRE'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
        // Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
    }

    public function createOrder()
    {
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);
        $currency = $this->context->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $mailVars = [];
        $this->module->validateOrder(
            (int)$cart->id,
            RegistryHutkigroshPrestashop::getRegistry()->getConfigWrapper()->getBillStatusPending(),
            $total,
            $this->module->displayName,
            null,
            $mailVars,
            (int)$currency->id,
            false,
            $customer->secure_key
        );
    }
}
