<?php
class adminPageManage extends Controller_Admin
{
    protected function run($aArgs)
    {
        require_once('builder/builderInterface.php');
        usbuilder()->init($this, $aArgs);

        //$aAddData[] = $this->_getAddInfo();
        $aOption = array(
            'module_name' => 'Socialslider',
        	'default_class' => 'adminPageSettings'
        );

        //usbuilder()->helper('sequence')->get($aOption)->addColumn($aAddData);
        $sHtml = usbuilder()->helper('sequence')->get($aOption)->getManageUI();
    	$this->assign('manage_ui', $sHtml);

    	$this->view(__CLASS__);
    }

    /**
     * return a set of values for a cusomized column
     * @return array a set of values for a cusomized column
     */
    private function _getAddInfo()
    {
        $aQuestionCount = common()->modelContents()->getSeqCount();

        return array(
            'columnInfo' => array(
                'columnName' => 'Samples',
                'default' => '0',
                'align' => 'right',
                'width' => '110'
            ),
            'columnData' => $aQuestionCount
        );
    }
}
