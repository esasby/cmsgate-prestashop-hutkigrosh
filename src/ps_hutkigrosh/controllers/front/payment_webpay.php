<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/init.php');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPage;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPageWebpay;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshWebpayForm;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\prestashop\CmsgateModuleFrontController;
use esas\cmsgate\Registry;

/**
 * @since 1.5.0
 */
class Ps_hutkigroshPayment_webpayModuleFrontController extends CmsgateModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->validateModule();

        $orderWrapper = $this->getOrderWrapper(Tools::getValue(RequestParamsHutkigrosh::ORDER_NUMBER), Tools::getValue(RequestParamsHutkigrosh::ORDER_ID));

        // проверяем, привязан ли к заказу extId, если да,
        // то счет не выставляем, а просто прорисовываем старницу
        if (empty($orderWrapper->getExtId())) {
            $controller = new ControllerHutkigroshAddBill();
            $controller->process($orderWrapper);
        }
        $controller = new ControllerHutkigroshCompletionPageWebpay();
        $completionPanel = $controller->process($orderWrapper->getOrderId());
        $data['completionPanel'] = $completionPanel;
//        $controller = new ControllerHutkigroshWebpayForm();
//        $webpayResp = $controller->process($orderWrapper);
//        $data['webpayForm'] = $webpayResp->getHtmlForm();
        $this->context->smarty->assign($data);
        $this->setTemplate('module:' . Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName() . '/views/templates/front/payment_webpay.tpl');
    }


    public function getPageName()
    {
        return "checkout"; //для подключения родного CSS, т.к. многие селекторы начинаются с body#checkout
    }


}
