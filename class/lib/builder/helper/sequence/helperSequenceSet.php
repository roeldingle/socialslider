<?php
class helperSequenceSet extends helperSequenceCommon
{
    private $_aModuleInfo;

    /**
     * 인스턴스 초기화
     */
    function __construct($aArgs = null)
    {
        $this->_aModuleInfo = $aArgs;
    }

    /**
     * 시퀀스 삭제
     * @param $sModuleCode 모듈 코드
     * @param $aSeq 삭제하려는 시퀀스값
     */
    public function delete($aSeq)
    {
        $aModuleInfo['mode'] = 'delete';
        $aModuleInfo['seq'] = $aSeq;

        require_once('install/installSequenceDelete.php');
        $oInstallSequenceDelete = getInstance('installSequenceDelete');
        $mResult = $oInstallSequenceDelete->run($aModuleInfo);
        //$mResult = helper()->install($aModuleInfo)->deleteSequence();
        //$mResult = $mResult && $this->_deleteRelatedAddon($sModuleCode, $aSeq);

        usbuilder()->setBuilderSession('admin_menu', $aModuleInfo);
        $mResult = $mResult && $this->modelSequence()->deleteBySeq($aSeq);

        return $mResult;
    }

    /**
     * 시퀀스 생성
     * @param $sModuleCode 모듈코드
     * @param $sModuleLabel 생성하려는 모듈 라벨(널값 입력시 기본값으로 세팅)
     * @param $iNextSequenceNo 생성하려는 시퀀스 넘버 (널값 입력시 시퀀스 생성규칙에 따라 넘버 자동할당)
     * @return 생성결과(성공이면 생성된 시퀀스 넘버, 실패면 false)
     */
    public function insert($sModuleLabel = null, $iNextSequenceNo = null)
    {
        $sModuleCode = $this->_aModuleInfo['module_code'];
        if (is_null($iNextSequenceNo) || empty($iNextSequenceNo)) $iNextSequenceNo = $this->_getNextSequenceNo();
        if (!$sModuleLabel) $sModuleLabel = "New " . ucfirst(APP_ID) . " " . $iNextSequenceNo;

        $aData = array(
            'label' => $sModuleLabel,
            'seq' => $iNextSequenceNo,
            'register_date' => time()
        );

        $aModuleInfo = $this->_aModuleInfo;
        $aModuleInfo['mode'] = 'add';
        $aModuleInfo['seq'] = $iNextSequenceNo;
        $aModuleInfo['seq_label'] = $sModuleLabel;
        $mResult = $this->modelSequence()->insert($aData);

        usbuilder()->setBuilderSession('admin_menu', $aModuleInfo);

        //$mResult = $mResult && $this->_insertRelatedAddon($sModuleCode, $sModuleLabel, $iNextSequenceNo);
        require_once('install/installSequenceCreate.php');
        $oInstallSequenceCreate = getInstance('installSequenceCreate');
        $mResult = $mResult && $oInstallSequenceCreate->run($aModuleInfo);

        if ($mResult == true) $mResult = $iNextSequenceNo;

        return $mResult;
    }

    /**
     * 시퀀스의 모듈라벨 수정
     * @param $iSeq 시퀀스넘버
     * @param $sModuleLabel 새로운 모듈라벨
     * @param $sModuleCode 모듈 코드
     */
    public function modifyLabel($iSeq, $sModuleLabel)
    {
        $bResult = true;
        $sModuleCode = $this->_aModuleInfo['module_code'];
        /** 시퀀스 메뉴 수정 로직 **/
        if ($this->hasConf('admin.seqmenu', $sModuleCode)) {
            $aMenuData = array("menu_name"=>$sModuleLabel);
            $bResult = getInstance('addonAdminmenuDataSet')->updateModuleSeqMenus($sModuleCode, $iSeq, $aMenuData);
        }
        /** 시퀀스 라벨 수정 **/
        $bResult = $bResult && $this->_getSelfModel()->modifySequenceLabel($sModuleCode, $iSeq, $sModuleLabel);
        return $bResult;
    }

    /**
     * 시퀀스존재여부 반환
     * @param string $sModuleCode 검사하려는 모듈코드
     * @param integer $iSeq 입력 시: 시퀀스 존재여부 검사 / 미입력 시 : 시퀀스모듈인지만 검사
     */
    final public function isSequence($iSeq = null)
    {
        $sModuleCode = ucfirst($this->_aModuleInfo['module_code']);

        if ($iSeq != null && is_integer((int)$iSeq)) {
            //$mTemp = $this->modelSequence()->getSeqenceInformation($sModuleCode, $iSeq);
            if (count($mTemp)) {
                return is_array($mTemp);
            } else {
                return false;
            }
        } else {
            return $this->hasConf('admin.seqmenu', $sModuleCode);
        }
    }

    /**
     * 생성될 다음 시퀀스 번호를 반환
     * @param string $sModuleCode
     */
    private function _getNextSequenceNo()
    {
        $aSequenceNo = $this->modelSequence()->getSeqenceNumber();

        $iNextSequenceNo = 1;
        if ($aSequenceNo[0]['seq']<=1) {
            for ($i=0; $i<count($aSequenceNo)-1 && $iNextSequenceNo == 1; ++$i) {
                if (($aSequenceNo[$i+1]['seq']-$aSequenceNo[$i]['seq'])>1) {
                    $iNextSequenceNo = $aSequenceNo[$i]['seq'] + 1;
                }
            }

            if ($iNextSequenceNo == 1) {
                $iNextSequenceNo = $aSequenceNo[count($aSequenceNo)-1]['seq'] + 1;
            }
        }
        return $iNextSequenceNo;
    }

    /**
     * 관련된 애드온의 레코드 삭제
     * @param string $sModuleCode 모듈코드
     * @param array $aSeq 삭제하려는 시퀀스 넘버
     */
    private function _deleteRelatedAddon($sModuleCode, $aSeq)
    {
        $bResult = true;
        foreach ($aSeq as $iVal) {
            /* 시퀀스 메뉴 삭제 */
            if (addon()->adminmenu()->data()->set()->isUseAdminSeqMenu($sModuleCode)) {
                /* @var $oAdminMenuDataSet addonAdminmenuDataSet */
                $oAdminMenuDataSet = getInstance('addonAdminmenuDataSet');
                $bResult = $bResult && $oAdminMenuDataSet->delModuleSeqMenus($sModuleCode, $iVal);
            }

            /* 모듈 항목정보를 삭제 */
            if (addon()->field()->data()->set()->isUseField($sModuleCode)) {
                /* @var $oFieldDataSet addonFieldDataSet */
                $oFieldDataSet = getInstance('addonFieldDataSet');
                $bResult = $bResult && $oFieldDataSet->delete($sModuleCode, $iVal);
            }

            /* URL Rewrite 설정 삭제 */
            if ($this->hasConf('urlrewrite', $sModuleCode)) {
                if(addon()->urlrewrite()->config()->sequence()->delete($sModuleCode, $iVal) === false) $bResult = false;
            }

            /* Category 삭제 */
            /* @var $oAddonFieldDataSet addonFieldDataSet */
            $oAddonFieldDataSet = getInstance('addonFieldDataSet');
            if ($oAddonFieldDataSet->isUseCategory($sModuleCode)) {
                /* @var $oAddonCategoryManagerData addonCategoryManagerData */
                $oAddonCategoryManagerData = getInstance('addonCategoryManagerData');
                $bResult = $bResult && $oAddonCategoryManagerData->deleteCategory($iVal, $sModuleCode);
            }
        }
        return $bResult;
    }

    /**
     * 관련된 애드온의 레코드 생성
     * @param string $sModuleCode 모듈코드
     * @param $iSeq : 생성하려는 시퀀스 넘버
     */
    private function _insertRelatedAddon($sModuleCode, $sModuleLabel, $iSeq)
    {
        $bResult = true;

		/* @var $oAdminMenuDataSet addonAdminmenuDataSet */
        $oAdminMenuDataSet = getInstance('addonAdminmenuDataSet');
        /* 추가된 시퀀스의 메뉴 추가 */
        if ($oAdminMenuDataSet->isUseAdminSeqMenu($sModuleCode)) {
            $aMenuData = array("menu_name"=>$sModuleLabel);
            $bResult = $bResult && $oAdminMenuDataSet->addModuleSeqMenus($sModuleCode, $iSeq, $aMenuData);
        }

        /* 모듈 항목정보를 세팅 */
        /* @var $oFieldDataSet addonFieldDataSet */
        $oFieldDataSet = getInstance('addonFieldDataSet');
        if ($oFieldDataSet->isUseField($sModuleCode)) {
            $bResult = $bResult && $oFieldDataSet->setting($sModuleCode, $iSeq);
        }

        /* URL Rewrite 설정 생성 */
        if ($this->hasConf('urlrewrite', $sModuleCode)) {
            /* @var $oUrlrewriteConfigSequence addonUrlrewriteConfigSequence */
            $oUrlrewriteConfigSequence = getInstance('addonUrlrewriteConfigSequence');
            $bResult = $bResult && $oUrlrewriteConfigSequence->create($sModuleCode, $iSeq);
        }

        return $bResult;

    }

    /**
     * xml parser
     * @param string $sXMLFilePath XML파일경로
     */
    public function _parseXML($sXMLFilePath)
    {
        $oUtilXml = new utilXml();
        $oUtilXml->load($sXMLFilePath);

        return getInstance('addonSequenceUtilXmltoarray')->parse($oUtilXml->saveXML());
    }

    /**
     * 해당 Conf 파일을 가지고 있는지 반환(가지고 있으면 true, 가지고 있지 않으면 false)
     * @param string $sFileName 파일명(prefix와 확장자 제외)
     * @param string $sModuleCode 모듈코드
     */
    final private function hasConf($sFileName = 'admin.seqmenu', $sModuleCode)
    {
        if ($sFileName == 'admin.seqmenu' || $sFileName == 'admin.menu') {
            $sSubDir = 'Adminmenu';
        } elseif ($sFileName == 'field') {
            $sSubDir = 'Field';
        }

        $sXMLFilePath = getModuleConfPath(ucwords($sModuleCode), 'conf.' . $sFileName . '.xml', $sSubDir, 'Service');
        return is_file($sXMLFilePath);
    }

    function createTable()
    {
        return $this->modelSequence()->createTable();
    }

    function dropTable()
    {
        return $this->modelSequence()->dropTable();
    }
}