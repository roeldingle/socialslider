<?php
class installSequenceDelete
{
    function run($aArgs)
    {
     $dDeleted = common()->modelExec()->deleteContentsBySeq($aArgs['seq']);
    	
    	if ($dDeleted !== false) {
    		return true;
    	} else {
    		return false;
    	} 
    }
}