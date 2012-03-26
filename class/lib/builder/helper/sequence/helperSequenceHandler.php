<?php
class helperSequenceHandler extends builderHelper
{
    function __construct()
    {
        require_once('builder/helper/sequence/helperSequenceCommon.php');
    }

    /**
     * @return helperSequenceGet
     */
    function get($aArgs)
    {
        require_once('builder/helper/sequence/helperSequenceGet.php');
        return getInstance('helperSequenceGet', $aArgs);
    }

    /**
     * @return helperSequenceSet
     */
    function set()
    {
        require_once('builder/helper/sequence/helperSequenceSet.php');
        return getInstance('helperSequenceSet');
    }

    /**
     * @return helperSequenceApi
     */
    function api($aArgs)
    {
        require_once('builder/helper/sequence/helperSequenceApi.php');
        return getInstance('helperSequenceApi')->run($aArgs);
    }

    function isSequence()
    {
        return is_file(APP_PATH . '/conf/conf.admin.seqmenu.xml');
    }
}