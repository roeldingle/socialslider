<?php
class helperSequenceApi extends helperSequenceCommon
{
    function run($aArgs)
    {
        if ($aArgs['action'] == 'add') {
            $mResult = $this->set()->insert($aArgs['label'], $aArgs['seq']);
            if ($aArgs['message'] == 'true') {
                if ($mResult) {
                    usbuilder()->message('Saved successfully', 'success');
                } else {
                    usbuilder()->message('Save failed', 'warning');
                }
            }
        } elseif ($aArgs['action'] == 'delete') {
            $aSeq = explode(',', $aArgs['seq']);
            $mResult = $this->set()->delete($aSeq);
            if ($aArgs['message'] == 'true') {
                if ($mResult) {
                    usbuilder()->message('Deleted successfully', 'success');
                } else {
                    usbuilder()->message('Delete failed', 'warning');
                }
            }
        } elseif ($aArgs['action'] == 'create_table') {
            $mResult = usbuilder()->checkResult($this->set()->createTable());
        } elseif ($aArgs['action'] == 'drop_table') {
            $mResult = usbuilder()->checkResult($this->set()->dropTable());
        }
        return $mResult;
    }
}
