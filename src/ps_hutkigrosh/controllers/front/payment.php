<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/init.php');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPage;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\prestashop\CmsgateModuleFrontController;
use esas\cmsgate\Registry;

/**
 * @since 1.5.0
 */
class Ps_hutkigroshPaymentModuleFrontController extends CmsgateModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->validateModule();

        $orderWrapper = $this->getOrderWrapper(Tools::getValue(RequestParamsHutkigrosh::ORDER_NUMBER), Tools::getValue(RequestParamsHutkigrosh::ORDER_ID));

        // проверяем, привязан ли к заказу billid, если да,
        // то счет не выставляем, а просто прорисовываем старницу
        if (empty($orderWrapper->getExtId())) {
            $controller = new ControllerHutkigroshAddBill();
            $controller->process($orderWrapper);
        }
        $controller = new ControllerHutkigroshCompletionPage();
        $completionPanel = $controller->process($orderWrapper->getOrderId());
        $data['completionPanel'] = $completionPanel;
        $this->context->smarty->assign($data);
        $this->setTemplate('module:' . Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName() . '/views/templates/front/payment_erip.tpl');
    }


    public function getPageName()
    {
        return "checkout"; //для подключения родного CSS, т.к. многие селекторы начинаются с body#checkout
    }


}
