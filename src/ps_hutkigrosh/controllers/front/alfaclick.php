<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/init.php');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAlfaclick;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPage;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\RegistryHutkigroshPrestashop;
use esas\cmsgate\utils\Logger;

/**
 * @since 1.5.0
 */
class Ps_hutkigroshAlfaclickModuleFrontController extends ModuleFrontController
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
            $controller = new ControllerHutkigroshAlfaclick();
            $controller->process($_REQUEST[RequestParamsHutkigrosh::BILL_ID], $_REQUEST[RequestParamsHutkigrosh::PHONE]);
        } catch (Throwable $e) {
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        }
    }


}
