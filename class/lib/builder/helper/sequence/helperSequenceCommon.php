<?php
class helperSequenceCommon extends helperSequenceHandler
{
    function modelSequence()
    {
        require_once('builder/helper/sequence/helperSequenceModel.php');
        $oModel = new helperSequenceModel();
        $oModel->modelInit();
        return $oModel;
    }
}