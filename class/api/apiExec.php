<?php
class apiExec extends Controller_Api
{
	
	
    protected function post($aArgs)
    {

        require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
        
        
        $oExec = new modelExec;
      
	#data to insert
	$aData = array(
		'position' => $aArgs['get_position'],
		'title' => $aArgs['get_title'],
    	'size' => $aArgs['get_size'],
		'count' => $aArgs['get_count'],
		'target' => $aArgs['get_target'],
		'icons' => json_encode($aArgs['get_icon'])
		);
	
    $dDeleted = $oExec->deleteData(2);
    
    return $aResult = ($dDeleted === true) ? $aResult = $oExec->insertData(2,$aData) : "false";
      
    }
    
  
}
