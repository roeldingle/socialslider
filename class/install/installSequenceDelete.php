<?php
class installSequenceDelete
{
    function run($aArgs)
    {
    	return true;
/*         $bResult = common()->modelContents()->deleteContentsBySeq($aArgs['seq']);
        if ($bResult !== false) {
            return true;
        } else {
            return false;
        } */
    }
}