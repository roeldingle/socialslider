<?php
class apiBuilderLib extends Controller_Api
{
    protected function get($aArgs)
    {
        require_once('builder/builderInterface.php');
        return usbuilder()->apiExecute($aArgs);
    }
}