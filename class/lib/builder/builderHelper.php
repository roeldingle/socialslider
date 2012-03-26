<?php
class builderHelper
{
    private $_aFetchData;

    function assign($sKey, $mData)
    {
        $this->_aFetchData[$sKey] = $mData;
    }

    function fetchAssignedData($sTpl)
    {
        if (is_array($this->_aFetchData)) {
            foreach($this->_aFetchData as $key => $val) {
                $sTpl = str_replace('{$' . $key . '}', $val, $sTpl);
            }
        }
        return $sTpl;
    }
}