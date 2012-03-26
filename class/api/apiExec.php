<?php
class apiExec extends Controller_Api
{
	
	
    protected function post($aArgs)
    {

        require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
   
		
	
		$oExec = new modelExec;
		$oGet = new modelGet;
      
	#data to insert
	$aData = array(
		'idx' => '',
		'position' => $aArgs['get_position'],
		'title' => $aArgs['get_title'],
    	'size' => $aArgs['get_size'],
		'count' => $aArgs['get_count'],
		'target' => $aArgs['get_target'],
		'icons' => json_encode($aArgs['get_icon'])
		);
	
    
     $aCheckRow = $oGet->getRow(2,null);
     $aResult = (empty($aCheckRow))?$oExec->insertData(2,$aData):$oExec->updateData(2,$aData,"idx = '".$aCheckRow['idx']."'");
     
     return $aResult;
      
    }
    
    /*
     * $aCheckRow =  $this->oGet->getRow(2,"pts_pm_idx = '".$aUserInfo['pm_idx']."'");
	$aResult = (empty($aCheckRow))?$this->oExec->insertData(2,$aData):$this->oExec->updateData(2,$aData,"pts_pm_idx = '".$aUserInfo['pm_idx']."'");
     * 
     * 
     * */
    
  
}
