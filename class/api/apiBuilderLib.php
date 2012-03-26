<?php
class apiBuilderLib extends Controller_Api
{
    protected function get($aArgs)
    {
        require_once('builder/builderInterface.php');
        usbuilder()->init($this, $aArgs);
        return usbuilder()->apiExecute($aArgs);
    }
}