<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/init.php');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAlfaclick;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshNotify;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\utils\Logger;

/**
 * @since 1.5.0
 */
class Ps_hutkigroshCallbackModuleFrontController extends ModuleFrontController
{
    public function initContent() {
        parent::initContent();
        $this->ajax = true; // enable ajax
    }

    /**
     * displayAjax обязательное имя
     */
    public function displayAjax()
    {
        try {
            $billId = Tools::getValue(RequestParamsHutkigrosh::PURCHASE_ID);
            $controller = new ControllerHutkigroshNotify();
            $controller->process($billId);
        } catch (Throwable $e) {
            Logger::getLogger("notify")->error("Exception:", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("notify")->error("Exception:", $e);
        }
    }


}
