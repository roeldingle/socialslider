<?php
class helperSequenceGet extends helperSequenceCommon
{
    private $_aFetchData;
    private $_aOption;
    private $_aAddedColumnInfo;
    private $_aSequenceList;

    function __construct($aArgs)
    {
        $this->_aOption = $aArgs;
    }

    function getManageUI()
    {
        $sPath = APP_PATH . '/class/lib/builder/helper/sequence/resource/tpl/sequenceManage.tpl';
        $sTpl .= file_get_contents($sPath);

        $sPath = APP_PATH . '/class/lib/builder/helper/sequence/resource/js/sequenceManage.js';
        $sJs .= file_get_contents($sPath);

        $this->_loadList();

        $iColSpan = 6 + count($this->_aAddedColumnInfo);

        $sHtmlColRows = $this->getHtmlColRows();
        $sHtmlHeadRows = $this->getHtmlHeadRows();
        $sHtmlBodyRows = $this->getHtmlBodyRows();

        $sAddButtonClass = (strlen($this->_aOption['module_name'])<8) ? 'sdk_btn_width_st2' : 'sdk_btn_width_st3';

        $this->_assign('sHtmlColRows', $sHtmlColRows);
        $this->_assign('sHtmlHeadRows', $sHtmlHeadRows);
        $this->_assign('sHtmlBodyRows', $sHtmlBodyRows);
        $this->_assign('sAddButtonClass', $sAddButtonClass);
        $this->_assign('iColspan', $iColSpan);
        $this->_assign('sModuleName', $this->_aOption['module_name']);

        $sJs .= "sequenceManage.setModuleInfo('" . $this->_aOption['module_name'] . "');";

        $sTpl = $this->_fetchAssignedData($sTpl);

        usbuilder()->getController()->writeJS($sJs);

        return $sTpl;
    }

    private function _loadList()
    {
        if (!$this->_aSequenceList) {
            $this->_aSequenceList = $this->modelSequence()->getList();
        }
    }

    function getHtmlColRows()
    {
        $sHtmlRows = '
            <col width="44px" class="' . $class_display_button_sequence_delete . '" />
            <col width="48px" />
            <col width="250px" />
            <col />
        ';
        for ($i = 0; $i < count($this->_aAddedColumnInfo); ++$i) {
            $sHtmlRows .= '<col width="' . $this->_aAddedColumnInfo[$i]['width'] . 'px" />';
        }
        $sHtmlRows .= '
            <col width="110px" />
            <col width="128px" />
        ';
        return $sHtmlRows;
    }

    function getHtmlHeadRows()
    {
        $sHtmlRows = '
        <th class="chk"><input type="checkbox" title="" class="input_chk chk_all" /></th>
        <th>No.</th>
        <th>Module ID</th>
        <th>Module Label</th>';

        for ($i = 0; $i < count($this->_aAddedColumnInfo); ++$i) {
            $sHtmlRows .= '<th> '. $this->_aAddedColumnInfo[$i]['columnName'] . '</th>';
        }

        $sHtmlRows .= '
        <th>Created</th>
        <th>Settings</th>
        ';
        return $sHtmlRows;
    }

    function getHtmlBodyRows()
    {
        $sSettingLink = 'ExtensionPageSetting';
        $sLabelLink = usbuilder()->getUrl($this->_aOption['default_class']);
        $sDateTimeFormat = 'm/d/Y';
        $sModuleCode = ucfirst(APP_ID);
        if(count($this->_aSequenceList)) {
        for ($i = 0; $i < count($this->_aSequenceList); ++$i) {
            $sHtmlRows .= '<tr class="event_mouse_over ' . $this->_aSequenceList[$i]['class_last'] . '">
               	<td><input type="checkbox" title="" class="input_chk" name="aListCheck[]" value="' . $this->_aSequenceList[$i]['seq'] .'" /></td>
                <td>' . ($i + 1) . '</td>
                <td>' . $sModuleCode . '_' .$this->_aSequenceList[$i]['seq'] . '</td>
                <td class="table_subtitle"><a href="' . $sLabelLink . '&seq=' . $this->_aSequenceList[$i]['seq'] . '" title="Open ' . $this->_aSequenceList[$i]['label'] . '">' . $this->_aSequenceList[$i]['label'] . '</a></td>';

            for($j = 0; $j < count($this->_aAddedColumnInfo); ++$j) {
                $sHtmlRows .= '<td class="' . $this->_aAddedColumnInfo[$j]['align'] . '"' . '>';
                if ($this->_aAddedColumnInfo[$j]['moduleParam']) {
                    $sHtmlRows .= '<a href="/admin/sub/?module=' . $this->_aAddedColumnInfo[$j]['moduleParam'] . '&seq=' . $this->_aSequenceList[$i]['seq'] . '">';
                    $sHtmlRows .=  $this->_aSequenceList[$i][$this->_aAddedColumnInfo[$j]['columnName']] ? $this->_aSequenceList[$i][$this->_aAddedColumnInfo[$j]['columnName']] : $this->_aAddedColumnInfo[$j]['default'];
                    $sHtmlRows .= '</a>';
                } else {
                    $sHtmlRows .=  $this->_aSequenceList[$i][$this->_aAddedColumnInfo[$j]['columnName']] ? $this->_aSequenceList[$i][$this->_aAddedColumnInfo[$j]['columnName']] : $this->_aAddedColumnInfo[$j]['default'];
                }
                    $sHtmlRows .=  '</td>';
                }
                $sHtmlRows .=  '<td>' . date($sDateTimeFormat, $this->_aSequenceList[$i]['register_date']) . '</td>';
                $sHtmlRows .=  '<td><a href="/admin/sub/?module=' . $sSettingLink . '&code=' . $sModuleCode . '&moduleseq=' . $this->_aSequenceList[$i]['seq'] . '" title="Edit ' . $this->_aSequenceList[$i]['module_label'] . '">' . Settings . '</a></td>';
                $sHtmlRows .=  '</tr>';
            }
        } else {
            $sHtmlRows .=  '
            <tr>
                <td colspan="{$iColspan}" class="not_fnd">There\'s no {$sModuleName}.</td>
            </tr>
            ';
        }
        return $sHtmlRows;
    }


    /**
     * 칼럼 추가 // 사용자가 호출
     * @param array $aData // 추가하려는 칼럼의 데이터
     * @param array $aColumnInfo // 추가하려는 칼럼의 정보
     */
    final public function addColumn($aColumnData)
    {
        $this->_loadList();

        foreach($aColumnData as $aVal)
        {
            $this->_addEachColumn($aVal['columnData'],$aVal['columnInfo']);
        }
    }

    /**
     * 개별 칼럼 추가 // 프로그램내에서만 호출
     * @param array $aData // 추가하려는 칼럼의 데이터
     * @param array $aColumnInfo // 추가하려는 칼럼의 정보
     */
    final private function _addEachColumn($aData, $aColumnInfo = array())
    {
        if(is_null($aColumnInfo['columnName'])) $aColumnInfo['columnName'] =  'Added Column';
        if(is_null($aColumnInfo['default'])) $aColumnInfo['default'] =  '0';
        if(is_null($aColumnInfo['align'])) $aColumnInfo['align'] =  'center';
        if(is_null($aColumnInfo['width'])) $aColumnInfo['width'] =  '110';

        for ($j=0; $j<count($aData); ++$j) {
            for ($i=0;$i<count($this->_aSequenceList);++$i) {
                if ($aData[$j]['seq'] == $this->_aSequenceList[$i]['seq']) {
                    $this->_aSequenceList[$i][$aColumnInfo['columnName']] = $aData[$j]['value'];
                }
            }
        }

        switch ($aColumnInfo['align']) {
            case 'left':
                $aColumnInfo['align'] = 'table_subtitle';
                break;
            case 'right':
                $aColumnInfo['align'] = 'rgt';
                break;
            case 'center':
                $aColumnInfo['align'] = null;
                break;
        }

        $this->_aAddedColumnInfo[] = array(
            'columnName' => $aColumnInfo['columnName'],
            'default' => $aColumnInfo['default'],
            'default' => $aColumnInfo['default'],
            'align' => $aColumnInfo['align'],
            'width' => $aColumnInfo['width'],
            'moduleParam' => $aColumnInfo['moduleParam']
        );
    }

    function _assign($sKey, $mData)
    {
        $this->_aFetchData[$sKey] = $mData;
    }

    function _fetchAssignedData($sTpl)
    {
        if (is_array($this->_aFetchData)) {
            foreach($this->_aFetchData as $key => $val) {
                $sTpl = str_replace('{$' . $key . '}', $val, $sTpl);
            }
        }
        return $sTpl;
    }
}