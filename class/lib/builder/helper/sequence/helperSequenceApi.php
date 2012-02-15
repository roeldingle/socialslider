<?php
class helperSequenceApi extends helperSequenceCommon
{
    function run($aArgs)
    {
            $_SESSION['aaaaaaa'] = 'bbbbbbbbbb';
        if ($aArgs['action'] == 'add') {
            $mResult = $this->set()->insert($aArgs['label'], $aArgs['seq']);
            if ($mResult) {
                usbuilder()->message('Saved successfully', 'success');
            } else {
                usbuilder()->message('Save failed', 'warning');
            }
        } elseif ($aArgs['action'] == 'delete') {
            $aSeq = explode(',', $aArgs['seq']);
            $mResult = $this->set()->delete($aSeq);
            if ($mResult) {
                usbuilder()->message('Deleted successfully', 'success');
            } else {
                usbuilder()->message('Delete failed', 'warning');
            }
        }
        return $mResult;
    }
}
