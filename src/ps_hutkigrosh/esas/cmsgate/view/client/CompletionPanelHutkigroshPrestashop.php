<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\view\client;

use esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigrosh;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class CompletionPanelHutkigroshPrestashop extends CompletionPanelHutkigrosh
{
    public function getCssClass4MsgSuccess()
    {
        return "alert alert-info";
    }

    public function getCssClass4MsgUnsuccess()
    {
        return "alert alert-danger";
    }

    public function getCssClass4Button()
    {
        return "btn btn-primary";
    }

    public function getCssClass4TabsGroup()
    {
        return "panel-group";
    }

    public function getCssClass4Tab()
    {
        return "panel panel-default checkout-step";
    }

    public function getCssClass4TabHeader()
    {
        return "panel-heading";
    }

    public function getCssClass4TabHeaderLabel()
    {
        return "panel-title";
    }

    public function getCssClass4TabBody()
    {
        return "panel-collapse";
    }

    public function getCssClass4TabBodyContent()
    {
        return "panel-body";
    }


    public function getCssClass4AlfaclickForm()
    {
        return "form-inline";
    }

    public function getCssClass4FormInput()
    {
        return "form-control";
    }

    public function getModuleCSSFilePath()
    {
        return dirname(__FILE__) . "/hiddenRadio.css";
    }

    public function elementTab($key, $header, $body)
    {
        return
            element::section(
                attribute::id("tab-" . $key),
                attribute::clazz("tab " . $this->getCssClass4Tab()),
                element::input(
                    attribute::id("input-" . $key),
                    attribute::type("radio"),
                    attribute::name("tabs2"),
                    attribute::checked($this->isTabChecked($key))
                ),
                element::div(
                    attribute::clazz("tab-header " . $this->getCssClass4TabHeader()),
                    attribute::style("display:inline-block"),
                    element::label(
                        attribute::forr("input-" . $key),
                        attribute::clazz($this->getCssClass4TabHeaderLabel()),
                        attribute::style("text-align:left; font-weight:bold"),
                        element::content($header)
                    )
                ),
                element::div(
                    attribute::clazz("tab-body " . $this->getCssClass4TabBody()),
                    element::div(
                        attribute::id($key . "-content"),
                        attribute::clazz("tab-body-content " . $this->getCssClass4TabBodyContent()),
                        element::content($body)
                    )
                )
            )->__toString();
    }


}