<?php
class builderCore
{
    static $sModuleCode = null;

    private $_aBuilderUrlInfo = array();

    /**
     * Initiate the library
     * @param string $sAppId App ID
     * @param array $aArgs Argument Array
     */
    public function init($sAppId, $aArgs)
    {
        $this->_setModuleCode($sAppId);
        $this->_aBuilderUrlInfo = $aArgs['usbuilder']['url_info'];
        $this->_aBuilderUrlInfo['front']['url'] = '/';
        $this->_aBuilderUrlInfo['front']['param'] = array('module' => '|Modulecode||Pageexec||Name|');

        $sInitScript = $this->_getInitScript($aArgs);
        return $sInitScript;
    }

    private function _setModuleCode($sModuleCode)
    {
        self::$sModuleCode = ucfirst($sModuleCode);
    }

    public function getModuleCode()
    {
        return self::$sModuleCode;
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
        return $sFormAction;
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

        $sText = str_replace('|modulecode|', strtolower($this->getModuleCode()), $sText);
        $sText = str_replace('|Modulecode|', ucfirst($this->getModuleCode()), $sText);
        $sText = str_replace('|pageexec|', strtolower($sPageExec), $sText);
        $sText = str_replace('|Pageexec|', ucfirst($sPageExec), $sText);
        $sText = str_replace('|name|', strtolower($sName), $sText);
        $sText = str_replace('|Name|', ucfirst($sName), $sText);
        return $sText;
    }

    /**
     * This returns the redirect script
     * @param string $sReplaceUrl
     * @return string the redirect script
     */
    public function jsMove($sReplaceUrl)
    {
        return "location.href = '$sReplaceUrl';";
    }

    /**
     * Returns the url from the class name
     * @param string $sClassName the class name
     * @return string the Url
     */
    public function getUrl($sClassName)
    {
        $aUrlInfo = $this->_getSpecifiedUrl($sClassName);
        $sUrl = $aUrlInfo['url'];
        $i = 0;
        if (is_array($aUrlInfo['param'])) {
            foreach($aUrlInfo['param'] as $key => $val) {
                if ($i == 0) {
                    $sGlue = '?';
                } else {
                    $sGlue = '&';
                }
                $sUrl .= $sGlue . $key . '=' . $val;
                ++$i;
            }
        }
        return $sUrl;
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
     * @param string $sResultMessage Messages
     * @param string $sResultType success | warning
     */
    public function message($sResultMessage, $sResultType)
    {
        $aMessage['result_message'] = $sResultMessage;
        $aMessage['result_type'] = $sResultType;
        $_SESSION['usbuilder']['function']['message'][] = $aMessage;
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
        $sReplaceKey = '{$usbuilder_pagination}';
        $_SESSION['usbuilder']['pagination']['count'] = $iCount;
        $_SESSION['usbuilder']['pagination']['rows'] = $iRows;
        $_SESSION['usbuilder']['pagination']['replace_key'] = $sReplaceKey;
        return $sReplaceKey;
    }

    private function _getInitScript($aArgs)
    {
        $sInitScript = "
            var usbuilder = {
            		sModuleCode : '" . $this->getModuleCode() . "',
            		_aBuilderUrlInfo : {
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
            		},
                    _replaceAllInfo: function(sClassName, sText) {
                        aInfo = sClassName.match(/[A-Z][a-z]+/gm);
                        pattAdmin = /^admin/;
                        pattApi = /^api/;
                        sPageExec = '';
                        if (pattAdmin.test(sClassName)) {
                            sPageExec = aInfo[0];
                            delete aInfo[0];
                        }
                        sName = aInfo.join('');

                        sText = sText.replace('|modulecode|', this.sModuleCode.toLowerCase());
                        sText = sText.replace('|Modulecode|', this._ucfirst(this.sModuleCode));
                        sText = sText.replace('|pageexec|', sPageExec.toLowerCase());
                        sText = sText.replace('|Pageexec|', this._ucfirst(sPageExec));
                        sText = sText.replace('|name|', sName.toLowerCase());
                        sText = sText.replace('|Name|', this._ucfirst(sName));
                        return sText;
                    },
            		_getSpecifiedUrl : function(sClassName) {
                        pattAdmin = /^admin/;
                        pattFront = /^front/;
                        pattApi = /^api/;
            			if (pattAdmin.test(sClassName)) {
                            aUrlInfo = this._aBuilderUrlInfo['admin'];
                        } else if (pattFront.test(sClassName)) {
                            aUrlInfo = this._aBuilderUrlInfo['front'];
                        } else if (pattApi.test(sClassName)) {
                            aUrlInfo = this._aBuilderUrlInfo['api'];
                        }
                        var aNewUrlInfo = {
                        	'url' : null,
                        	'param' : new Array()
						};
                        aNewUrlInfo['url'] = this._replaceAllInfo(sClassName, aUrlInfo['url']);
                        if (this._is_array(aUrlInfo['param'])) {
                            for (var key in aUrlInfo['param']) {
                                sNewKey = this._replaceAllInfo(sClassName, key);
                                sNewVal = this._replaceAllInfo(sClassName, aUrlInfo['param'][key]);
                                aNewUrlInfo['param'][sNewKey] = sNewVal;
                            }
                        }
                        return aNewUrlInfo;
            		},
            		getUrl : function(sClassName) {
                        aUrlInfo = this._getSpecifiedUrl(sClassName);
                        sUrl = aUrlInfo['url'];
                        i = 0;
                        if (this._is_array(aUrlInfo['param'])) {
                            for (var key in aUrlInfo['param']) {
                                if (i == 0) {
                                    sGlue = '?';
                                } else {
                                    sGlue = '&';
                                }
                                sUrl += sGlue + key + '=' + aUrlInfo['param'][key];
                                ++i;
                            }
                        }
                        return sUrl;
            		},
            		_ucfirst : function(sText) {
            			first = sText.charAt(0);
            			rest = sText.substring(1,sText.length);
            			first = first.toUpperCase();
            			sText = first.concat(rest);

            			return sText;
            		},
            		_is_array : function(input) {
            			return typeof(input)=='object'||(input instanceof Array);
            		}
            };
        ";

        return $sInitScript;
    }

    function getConf($aArgs)
    {
        $oDOMDocument = new DOMDocument();

        if (isset($aArgs['xml'])) {
            $oDOMDocument->load(APP_PATH . '/conf/' . 'conf.' . $aArgs['xml'] .'.xml');
        } elseif (isset($aArgs['lang'])) {
            if (isset($aArgs['filename'])) {
                $oDOMDocument->load(APP_PATH . '/resource/lang/' . $aArgs['lang'] . '/' . $aArgs['filename'] .'.xml');

            } else {
                $oDOMDocument->load(APP_PATH . '/resource/lang/' . $aArgs['lang'] . '/common.xml');
            }
        }
        $sXML = $oDOMDocument->saveXML();

        return $sXML;
    }
}