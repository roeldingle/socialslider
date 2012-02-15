<?php
class apiConf extends Controller_Api
{
    protected function get($aArgs)
    {
        require_once('builder/builderInterface.php');
        return usbuilder()->getConf($aArgs);
    }
}