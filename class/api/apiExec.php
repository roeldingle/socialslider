<?php
class apiExec extends Controller_Api
{
	
	
    protected function post($aArgs)
    {

        require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
        
        
    /*sequence*/
		$iSeq = $aArgs['get_seq'];
	
		$oExec = new modelExec;
		$oGet = new modelGet;
      
	#data to insert
	$aData = array(
			'idx' => '',
			'seq' => $iSeq,
		'position' => $aArgs['get_position'],
		'title' => $aArgs['get_title'],
    	'size' => $aArgs['get_size'],
		'count' => $aArgs['get_count'],
		'target' => $aArgs['get_target'],
		'icons' => json_encode($aArgs['get_icon'])
		);
	
     $bSeqExist = $oGet->getRow(2,"seq =".$iSeq);
     
     if(empty($bSeqExist)){
     	$aResult = $oExec->insertData(2,$aData);
     }else{
        $dDeleted = $oExec->deleteData(2,"seq =".$iSeq);
        if($dDeleted === true){
        	$aData['idx'] = $bSeqExist['idx'];
        	$aResult = $oExec->insertData(2,$aData);
        }else{
        	$aResult = "false";
        }
     } 
     
     return $aResult;
      
    }
    
  
}
