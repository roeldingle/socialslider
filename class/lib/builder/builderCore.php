<?php
class builderCore
{
    static $aAppInfo = array();
    static private $_aAssignForJsData;
    private $_aBuilderUrlInfo = array();
    private $_aArgs = array();
    private $_oController;

    /**
     * Initiate the library
     * @param string $sAppId App ID
     * @param array $aArgs Argument Array
     */
    public function init($oController, $aArgs)
    {
        $this->_setAppInfo($oController, $aArgs);

        $this->_aArgs = $aArgs;

        $this->_aBuilderUrlInfo = $aArgs['usbuilder']['url_info'];
        $this->_aBuilderUrlInfo['front']['url'] = '/';
        $this->_aBuilderUrlInfo['front']['param'] = array('module' => '|Modulecode||Pageexec||Name|');

        $aModuleInfo = $this->getAppInfo();
        if ($aModuleInfo['class_type'] == 'admin' || $aModuleInfo['class_type'] == 'front') $oController->writeJS($this->_getInitJS());
        if ($aModuleInfo['class_type'] == 'admin' && $aModuleInfo['exec_type'] == 'page') $oController->writeCSS($this->_getInitCSS());
    }

    public function getController()
    {
        return $this->_oController;
    }

    public function getAppInfo($sKey = null)
    {
        if ($sKey) {
            return self::$aAppInfo[$sKey];
        } else {
            return self::$aAppInfo;
        }
    }

    private function _setAppInfo($oController, $aArgs)
    {
        if ($oController instanceof Controller_Admin) {
            $aAppInfo = array(
                'class_type' => 'admin',
                'exec_type' => 'page'
            );
        } elseif ($oController instanceof Controller_AdminExec) {
            $aAppInfo = array(
                'class_type' => 'admin',
                'exec_type' => 'exec'
            );
        } elseif ($oController instanceof Controller_Front) {
            $aAppInfo = array(
                'class_type' => 'front',
                'exec_type' => 'page',
            	'seq' => $oController->getSequence(),
                'block' => strtolower(str_replace('frontPage', '', get_class($oController))),
            	'group' => $oController->getOption('block_group')
            );
        } elseif ($oController instanceof Controller_FrontExec) {
            $aAppInfo = array(
                'class_type' => 'front',
                'exec_type' => 'exec'
            );
        } elseif ($oController instanceof Controller_Api) {
            $aAppInfo = array(
                'class_type' => 'api'
            );
        }

        $aAppInfo['lang_code'] = $oController->getLangCode();
        $aAppInfo['app_id'] = ucfirst(APP_ID);
        if (!$aAppInfo['seq']) $aAppInfo['seq'] = $aArgs['seq'];

        $this->_oController = $oController;
        self::$aAppInfo = $aAppInfo;
    }

    /**
     * Returns the script which set a action attribute for the form
     * @param string $sFormName The name of the form
     * @param string $sClassName The name of the class(target of action)
     * @return string Scripts
     */
    public function getFormAction($sFormName, $sClassName)
    {
        $aUrlInfo = $this->_getSpecifiedUrl($sClassName);
        if ($aUrlInfo['param']) {
            foreach($aUrlInfo['param'] as $key => $val) {
                $sActionInput = '<input type="hidden" name="' . $key . '" value="' . $val . '" />';
                $sFormAction .= "$('form[name=\"$sFormName\"]').prepend('$sActionInput');";
            }
        }
        $sFormAction .= "$('form[name=\"$sFormName\"]').attr('action', '" . $aUrlInfo['url'] . "');";

        $this->getController()->writeJs($sFormAction);
    }

    private function _getSpecifiedUrl($sClassName)
    {
        if (preg_match('/^admin/', $sClassName)) {
            $aUrlInfo = $this->_aBuilderUrlInfo['admin'];
        } elseif (preg_match('/^front/', $sClassName)) {
            $aUrlInfo = $this->_aBuilderUrlInfo['front'];
        } elseif (preg_match('/^api/', $sClassName)) {
            $aUrlInfo = $this->_aBuilderUrlInfo['api'];
        }
        $aNewUrlInfo['url'] = $this->_replaceAllInfo($sClassName, $aUrlInfo['url']);
        if (is_array($aUrlInfo['param'])) {
            foreach ($aUrlInfo['param'] as $key => $val) {
                $sNewKey = $this->_replaceAllInfo($sClassName, $key);
                $sNewVal = $this->_replaceAllInfo($sClassName, $val);
                $aNewUrlInfo['param'][$sNewKey] = $sNewVal;
            }
        }
        return $aNewUrlInfo;
    }

    private function _replaceAllInfo($sClassName, $sText)
    {
        $aInfo = getClassNameRegexp($sClassName);
        if (preg_match('/^admin/', $sClassName)) {
            $sPageExec = $aInfo[0][0];
            unset($aInfo[0][0]);
        }
        $sName = implode('', $aInfo[0]);

        $sText = str_replace('|modulecode|', strtolower(APP_ID), $sText);
        $sText = str_replace('|Modulecode|', ucfirst(APP_ID), $sText);
        $sText = str_replace('|pageexec|', strtolower($sPageExec), $sText);
        $sText = str_replace('|Pageexec|', ucfirst($sPageExec), $sText);
        $sText = str_replace('|name|', strtolower($sName), $sText);
        $sText = str_replace('|Name|', ucfirst($sName), $sText);
        return $sText;
    }

    /**
     * Move to the redirect url
     * @param string $sRedirectUrl
     */
    public function jsMove($sRedirectUrl)
    {
        $this->getController()->writeJs("location.href = '$sRedirectUrl';");
    }

    /**
     * Returns the url from the class name
     * @param string $sClassName the class name
     * @param string $mSeq true(default) | false(non-sequence) | number
     * @return string the Url
     */
    public function getUrl($sClassName, $mSeq = true)
    {
        $aUrlInfo = $this->_getSpecifiedUrl($sClassName);
        $aAppInfo = $this->getAppInfo();
        $sUrl = $aUrlInfo['url'];
        $i = 0;
        if (is_array($aUrlInfo['param']) && count($aUrlInfo['param']) > 0) {
            foreach($aUrlInfo['param'] as $key => $val) {
                if ($i == 0) {
                    $sGlue = '?';
                } else {
                    $sGlue = '&';
                }
                $sUrl .= $sGlue . $key . '=' . $val;
                ++$i;
            }
            $sGlue = '&';
        } else {
            $sGlue = '?';
        }
        if ($mSeq == true) {
            if ($aAppInfo['seq']) $sUrl .= $sGlue . 'seq=' . $aAppInfo['seq'];
        } elseif ((int)$mSeq > 0 && is_int((int)$mSeq)) {
            $sUrl .= $sGlue . 'seq=' . $mSeq;
        }
        return $sUrl;
    }

    public function getParam($sKey)
    {
        $sResultKey = $this->getParamKey($sKey);
        return $this->_aArgs[$sResultKey];
    }

    public function getParamKey($sKey = null)
    {
        $aAppInfo = $this->getAppInfo();
        if ($aAppInfo['class_type'] == 'front') {
            if ($aAppInfo['seq']) {
                $sResultKey = strtolower(APP_ID) . '_' . $aAppInfo['seq'];
                $iGroup = $this->getController()->getOption("block_group");
                if($iGroup) $sResultKey .= "_".$iGroup;
                $sResultKey .= ':' . $sKey;
            } else {
                $sResultKey = strtolower(APP_ID) . ':' . $sKey;
            }
        } else {
            $sResultKey = $sKey;
        }

        return $sResultKey;
    }

    /**
     * 프론트용 module query
     * @param Array $aOverwriteInfo 직접 지정할 정보
     */
    public function getModuleSelector($aOverwriteInfo = array())
    {
        $aInfo = $this->getAppInfo();
        foreach($aOverwriteInfo as $sKey => $oValue) {
            $aInfo[$sKey] = $oValue;
        }
        $iGroup = $aOverwriteInfo["group"];
        if(empty($iGroup)) $iGroup = $aInfo["group"];

        $sQuery = strtolower($aInfo["app_id"]);
        if($aInfo["seq"]) $sQuery .= ">".$aInfo["seq"];
        if($aInfo["block"]) $sQuery .= ">".strtolower($aInfo["block"]);
        if($iGroup) $sQuery .= "[".$iGroup."]";
        else $sQuery .= "[^]";

        return $sQuery;
    }

    public function vd($mData, $sKey = null)
    {
        $_SESSION['usbuilder']['vd']['show'] = true;
        if ($sKey) {
            $_SESSION['usbuilder']['vd']['data'][$sKey] = $mData;
        } else {
            $_SESSION['usbuilder']['vd']['data'][] = $mData;
        }
    }

    /**
     * Set a message, then it'll be displayed after the reload
     * @param string $sMessage Messages
     * @param string $sType success | warning
     */
    public function message($sMessage, $sType = 'success')
    {
        $aMessage['message'] = $sMessage;
        $aMessage['type'] = $sType;
        $_SESSION['usbuilder']['function']['message'] = $aMessage;
    }

    /**
     * @param array $aOption
     */
    public function validator($aOption)
    {
        $_SESSION['usbuilder']['function']['validator'][] = $aOption;
    }

    /**
     * Call uipacks
     * @param string $sName The name of uipack | plugin
     * @param string $sParam the plugin name
     */
    public function uipack($sName, $sParam = null)
    {
        if ($sName == 'plugin') {
            $_SESSION['usbuilder']['uipackplugin'][] = $sParam;
        } else {
            $_SESSION['usbuilder']['uipack'][] = $sName;
        }
    }

    /**
     * Returns the pagination(replace key)
     * @param integer $iCount The result count
     * @param integer $iRows The number of rows which you want to display in one pages
     * @return string Pagination(replace key)
     */
    public function pagination($iCount, $iRows)
    {
        $oPagination = $this->helper(__FUNCTION__);

        $iPage = (empty($this->_aArgs['page'])) ? 1 : $this->_aArgs['page'];

        $oPagination->prepare($iPage, $iRows, $iCount);
        $sPagination = $oPagination->buildForUS();

        return $sPagination;
    }

    private function _getInitJS()
    {
        $aAppInfo = $this->getAppInfo();

        $sPath = APP_PATH . '/class/lib/builder/resource/uipack/sdk_popup.js';
        $sInitScript .= file_get_contents($sPath);
        $sPath = APP_PATH . '/class/lib/builder/resource/uipack/sdk_message.js';
        $sInitScript .= file_get_contents($sPath);
        $sPath = APP_PATH . '/class/lib/builder/resource/js/sdk_common.js';
        $sInitScript .= file_get_contents($sPath);
        $sPath = APP_PATH . '/class/lib/builder/resource/js/sdk_multi.js';
        $sInitScript .= file_get_contents($sPath);
        $sInitScript .= "
            aBuilderUrlInfo = {
            			'admin' : {
            				'url' : '" . $this->_aBuilderUrlInfo['admin']['url'] . "',
            				'param' : $.parseJSON('" . json_encode($this->_aBuilderUrlInfo['admin']['param']) . "')
            			},
            			'front' : {
            				'url' : '" . $this->_aBuilderUrlInfo['front']['url'] . "',
            				'param' : $.parseJSON('" . json_encode($this->_aBuilderUrlInfo['front']['param']) . "')
            			},
            			'api' : {
            				'url' : '" . $this->_aBuilderUrlInfo['api']['url'] . "',
            				'param' : '" . $this->_aBuilderUrlInfo['api']['param'] . "'
            			}
    		};

            aAppInfo = $.parseJSON('" . json_encode($aAppInfo) . "');
            usbuilder._setAppInfo(aAppInfo);
            usbuilder._setBuilderUrlInfo(aBuilderUrlInfo);
        ";

        /**
         *
        //Message Print
        $aMessage = $_SESSION['usbuilder']['function']['message'];
        if(is_array($aMessage)){
            $sInitScript .= "sdk_message.show('" . $aMessage['message'] . "','" . $aMessage['type'] . "');";
            unset($_SESSION['usbuilder']['function']['message']);
        }
         */

        return $sInitScript;
    }

    public function setBuilderSession($sKey, $mData)
    {
        $_SESSION['usbuilder']['function'][$sKey] = $mData;
    }

    private function _getInitCSS()
    {
        $sPath = APP_PATH . '/class/lib/builder/resource/css/sdk_common.css';
        $sInitCss .= file_get_contents($sPath);

        return $sInitCss;
    }

    public function apiExecute($aArgs)
    {
        if ($aArgs['mode'] == 'conf') {
            $oDOMDocument = new DOMDocument();
            $sPath = APP_PATH . '/conf/' . 'conf.' . $aArgs['xml'] .'.xml';
            if (file_exists($sPath)) {
                $oDOMDocument->load($sPath);
                $mResult = $oDOMDocument->saveXML();
            } else {
                $mResult = false;
            }
        } elseif ($aArgs['mode'] == 'lang') {
            if (!$aArgs['lang']) $aArgs['lang'] = $this->getAppInfo('lang_code');
            $oDOMDocument = new DOMDocument();
            if (!empty($aArgs['name'])) {
                $sPath = APP_PATH . '/resource/lang/' . $aArgs['lang'] . '/' . $aArgs['name'] .'.xml';
            } else {
                $sPath = APP_PATH . '/resource/lang/' . $aArgs['lang'] . '/common.xml';
            }
            if (file_exists($sPath)) {
                $oDOMDocument->load($sPath);
                $mResult = $oDOMDocument->saveXML();
            } else {
                $mResult = false;
            }
        } elseif ($aArgs['mode'] == 'helper') {
            $mResult = usbuilder()->helper($aArgs['helpername'])->api($aArgs);
        } elseif ($aArgs['mode'] == 'install') {
            $sPath = APP_PATH . '/install/install.sql';
            $sQuery .= file_get_contents($sPath);
            $aResult = $this->Dump($sQuery);
            $mResult = $aResult['Result'];
        } elseif ($aArgs['mode'] == 'uninstall') {
            $sPath = APP_PATH . '/install/uninstall.sql';
            $sQuery .= file_get_contents($sPath);
            $aResult = $this->Dump($sQuery);
            $mResult = $aResult['Result'];
        }
        return $mResult;
    }

    private function _query($sQuery)
    {
        require_once('builder/builderModel.php');
        $oModel = getInstance('builderModel');
        return $oModel->executeQuery($sQuery);
    }

    public function helper($sHelperName)
    {
        require_once('builder/builderHelper.php');
        require_once('builder/helper/' . $sHelperName .'/helper' . ucfirst($sHelperName) . 'Handler.php');
        $oHelper = getInstance('helper' . ucfirst($sHelperName) . 'Handler');
        return $oHelper;
    }

    /**
     * 반환값의 참 거짓을 따져서 반환
     * @param $mData 임의의 반환값
     */
    public function checkResult($mData)
    {
        if (is_null($mData)) {
            $mResult = $mData;
        } else if(is_array($mData)) {
            if (count($mData) == 0) {
                $mResult = true;
            } else {
                $mResult = $mData;
            }
        } else if(is_int($mData)) {
            $mResult = true;
        } else if($mData===true) {
            $mResult = true;
        } else {
            $mResult = false;
        }
        return $mResult;
    }

    public function Dump($sSqlDump)
    {
        $sSqlDump = trim($sSqlDump);
        if (strpos($sSqlDump, ";\r\n")>0 || strpos($sSqlDump, ";\n")>0) {
            if (strpos($sSqlDump, ";\r\n")>0) {
                $aSqlDump = explode(";\r\n", $sSqlDump);
            } else if (strpos($sSqlDump, ";\n")>0) {
                $aSqlDump = explode(";\n", $sSqlDump);
            }
        } else {
            $aSqlDump = array($sSqlDump);
        }

        $aSqlResult = array();
        $aSqlResult['Result'] = false;
        $aSqlResult['Query'] = array();

        $bResult = true;

        foreach ($aSqlDump as $sSql) {
            if ($sSql) {
                $mResult = $this->_query($sSql);

                if ($mResult!==false) {
                    $aSqlResult['Query'][] = array(true, $sSql, '');
                } else {
                    $aSqlResult['Query'][] = array(false, $sSql, mysql_error($this->RES));
                    $bResult = false;
                }
            }
        }

        $aSqlResult['Result'] = $bResult;

        return $aSqlResult;
    }
}