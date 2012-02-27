<?php
class installSequenceDelete
{
    function run($aArgs)
    {
     $dDeleted = common()->modelExec()->deleteContentsBySeq(2,$aArgs['seq']);
    	
    	if ($dDeleted !== false) {
    		return true;
    	} else {
    		return false;
    	} 
    }
}