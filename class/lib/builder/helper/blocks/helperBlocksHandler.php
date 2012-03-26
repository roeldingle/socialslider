<?php
class helperBlocksHandler extends builderHelper
{
    function getParser()
    {
        require_once('builder/helper/blocks/helperBlocksParser.php');
        return getInstance('helperBlocksParser');
    }

    function getList()
    {
        $aInterface = $this->getParser()->getInterface();

        $aFrontBlocks = $aInterface['Front']['Ifs'];
        for ($i = 0; $i < count($aFrontBlocks); ++$i) {
            if (preg_match('/^frontPage/', $aFrontBlocks[$i]['class'])) {
                $aData = array(
                    'name' => str_replace('frontPage', '', $aFrontBlocks[$i]['class']),
                    'description' => $aFrontBlocks[$i]['desc'],
//                	'sample_code' => html_entity_decode($aInterface['Front']['Code-Assist'][$aFrontBlocks[$i]['class']]['SampleHTML'])
                	'sample_code' => $aInterface['Front']['Code-Assist'][$aFrontBlocks[$i]['class']]['SampleHTML']
                );
                $aList[] = $aData;
            }
        }
        return $aList;
    }

    function getListUI()
    {
        $aList = $this->getList();

        $sPath = APP_PATH . '/class/lib/builder/helper/blocks/resource/tpl/addonModuleFunctionsList.tpl';
        $sHtml .= file_get_contents($sPath);

        $sPath = APP_PATH . '/class/lib/builder/helper/blocks/resource/js/addonModuleFunctions.js';
        $sJs .= file_get_contents($sPath);

        $sTableBodyHtml = $this->getTableBodyHtml($aList);
        $sCodesHtml = $this->getCodesHtml($aList);
        $sOptionHtml = $this->getOptionHtml($aList);

        $this->assign('sAppId', ucfirst(APP_ID));
        $this->assign('sTableBodyHtml', $sTableBodyHtml);
        $this->assign('sCodesHtml', $sCodesHtml);
        $this->assign('sOptionHtml', $sOptionHtml);
        $sHtml = $this->fetchAssignedData($sHtml);

        usbuilder()->getController()->writeJS($sJs);

        return $sHtml;
    }

    function getTableBodyHtml($aList)
    {
        $i=1;
        $iCount = count($aList);
        $sModuleCode = ucfirst(APP_ID);
        $iModuleSeq = usbuilder()->getAppInfo('seq');
        foreach($aList as $value){
            $sLastClass = $iCount == ($i-1) ? 'last' : '';
            $sTableBodyHtml .= '
                <tr class="event_mouse_over ' . $sLastClass . '" modulecode="' . $sModuleCode . '" moduleseq="' . $iModuleSeq . '" moduleid="' . $value['name'] . '">
                    <td>' . $i . '</td>
                    <td>' . str_replace('[seq]', $iModuleSeq, $value['name']) . '</td>
                    <td class="table_subtitle">' . $value['description'] . '</td>
                    <td><a href="#layer_01" title="View Sample Code" class="view_code" >View Sample Code</a></td>
                </tr>
            ';
        $i++;
        }
        return $sTableBodyHtml;
    }

    function getCodesHtml($aList)
    {
        $i=1;
        $iCount = count($aList);
        $sModuleCode = ucfirst(APP_ID);
        $iModuleSeq = usbuilder()->getAppInfo('seq');
        foreach($aList as $value){
            $sLastClass = $iCount == ($i-1) ? 'last' : '';
            $sCodesHtml .= '
                <code id="' . $value['name'] .'" class="_code_area" style="display: none;">
                    <xmp>' . $value['sample_code'] .'</xmp>
                </code>
            ';
        $i++;
        }
        return $sCodesHtml;
    }

    function getOptionHtml($aList)
    {
        $i=1;
        $iCount = count($aList);
        $sModuleCode = ucfirst(APP_ID);
        $iModuleSeq = usbuilder()->getAppInfo('seq');
        foreach($aList as $value){
            $sLastClass = $iCount == ($i-1) ? 'last' : '';
            $sOptionHtml .= '
                <option value="' . $value['name'] .'" selected="selected">' . $value['name'] .'</option>
            ';
        $i++;
        }
        return $sOptionHtml;
    }
}