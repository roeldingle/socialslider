<?php

class adminPageAdvanced extends Controller_Admin
{
    protected function run($args)
    {
        require_once('builder/builderInterface.php');
        usbuilder()->init($this, $aArgs);
        $sBlocksListHtml = usbuilder()->helper('blocks')->getListUI();
        $this->assign('sBlocksListHtml', $sBlocksListHtml);
        $this->view(__CLASS__);
    }
}

