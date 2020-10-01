<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate;

use Context;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\PaysystemConnectorHutkigrosh;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormPrestashop;
use esas\cmsgate\view\client\CompletionPanelHutkigroshPrestashop;
use Link;

class RegistryHutkigroshPrestashop extends RegistryHutkigrosh
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorPrestashop();
        $this->paysystemConnector = new PaysystemConnectorHutkigrosh();
    }

    /**
     * Переопделение для упрощения типизации
     * @return RegistryHutkigroshPrestashop
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * Переопделение для упрощения типизации
     * @return ConfigFormPrestashop
     */
    public function getConfigForm()
    {
        return parent::getConfigForm();
    }

    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON,
            [
                ConfigFieldsHutkigrosh::shopName()
            ]);
        $configForm = new ConfigFormPrestashop(
            AdminViewFields::CONFIG_FORM_COMMON,
            $managedFields);
        return $configForm;
    }

    public function getCompletionPanel($orderWrapper)
    {
        return new CompletionPanelHutkigroshPrestashop($orderWrapper);
    }

    function getUrlAlfaclick($orderId)
    {
        return (new Link())->getModuleLink(RegistryHutkigrosh::getRegistry()->getModuleDescriptor()->getModuleMachineName(), 'alfaclick');
//        return Context::getContext()->shop->getBaseURL(true, true)
//            . 'module/' . RegistryHutkigrosh::getRegistry()->getModuleDescriptor()->getModuleMachineName() . "/alfaclick/submit";
    }

    function getUrlWebpay($orderId)
    {
        return Context::getContext()->shop->getBaseURL(true, false) . $_SERVER['REQUEST_URI'];
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "ps_hutkigrosh",
            new VersionDescriptor("1.12.0", "2020-09-23"),
            "Прием платежей через ЕРИП (сервис EPOS)",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-prestashop-hutkigrosh/browse",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }
}