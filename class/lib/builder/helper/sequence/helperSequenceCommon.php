<?php
class helperSequenceCommon extends helperSequenceHandler
{
    function modelSequence()
    {
        require_once('builder/helper/sequence/model/modelSequence.php');
        $oModel = new modelSequence();
        $oModel->modelInit();
        return $oModel;
    }
}