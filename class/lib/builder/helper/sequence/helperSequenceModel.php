<?php
class helperSequenceModel extends Model
{
    private $_sTableName;

    function modelInit()
    {
        $this->_sTableName = strtolower(APP_ID) . '_sequence';
        //$this->createTable();
    }

    function createTable()
    {
        $sQuery = "
            CREATE TABLE IF NOT EXISTS `" . $this->_sTableName . "` (
              `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'module sequence key',
              `seq` int(10) unsigned NOT NULL COMMENT 'sequence number',
              `label` varchar(250) NOT NULL COMMENT 'sequence label (entitled by admin)',
              `register_date` int(10) unsigned NOT NULL COMMENT 'registered date',
              PRIMARY KEY (`idx`)
            );
        ";
        return $this->query($sQuery);
    }

    function dropTable()
    {
        $sQuery = "
            DROP TABLE IF EXISTS `" . $this->_sTableName . "`;
        ";
        return $this->query($sQuery);
    }

    function getList($aOption = array())
    {
        $aOption['sSortOrder'];
        $sQuery = "SELECT * FROM " . $this->_sTableName;
        $sQuery .= " ORDER BY idx ASC";

		return $this->query($sQuery);
    }

    /**
     * 시퀀스넘버 반환
     * @param string $sModuleCode
     */
    final public function getSeqenceNumber()
    {
        $sQuery = "SELECT seq FROM (" . $this->_sTableName . ") ORDER BY seq ASC";
        $aResult = $this->query($sQuery);

        return $aResult;
    }

    /**
     * 시퀀스 삽입
     * @param array $aData
     */
    final public function insert($aData)
    {
        $sQuery = "INSERT INTO " . $this->_sTableName . " (seq, label, register_date) VALUES(" . $aData['seq'] . ", '" .$aData['label'] . "', " . $aData['register_date'] . ");";
        $mResult = $this->query($sQuery);
        $mResult = usbuilder()->checkResult($mResult);
        return $mResult;
    }

    /**
     * 시퀀스 삭제
     * @param array $aSeq
     * @param string $sModuleCode
     */
    final public function deleteBySeq($aSeq)
    {
        $sSeq = implode(', ', $aSeq);
        $sQuery = "DELETE FROM " . $this->_sTableName . " WHERE seq IN (" . $sSeq . ");";
        $bResult = $this->query($sQuery);
        $mResult = usbuilder()->checkResult($bResult);
        return $mResult;
    }
}