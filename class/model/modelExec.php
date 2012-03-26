<?php
class modelExec extends Model{
	
	protected $PG_NAME = "socialslider";
	protected $PG_MAIN = null;
	protected $PG_SETTING = null;
	protected $PG_CONTENT = null;
	
	public function init()
	{
		$this->PG_MAIN = $this->PG_NAME.'_main';
		$this->PG_SETTING = $this->PG_NAME.'_settings';
		$this->PG_CONTENT = $this->PG_NAME.'_content';
	
	}
	/*
	 desc: choose the table to use;
	@param $iTable = ex.(1=PG_MAIN || 2=PG_SETTING || 3=PG_CONTENT);
	return $sTable = the tablename;
	*/
	public function chooseTable($iTable)
	{
		switch($iTable)
		{
			case 1;
			$sTable = $this->PG_MAIN;
			break;
			case 2;
			$sTable = $this->PG_SETTING;
			break;
			case 3;
			$sTable = $this->PG_CONTENT;
			break;
		}
		return $sTable;
	}
	/*
	 desc: to give a WHERE condition for the get function
	@param $sWhere = ex.("pm_userid = '".$this->sUser."'");
	return $sWhere = a where condition for the query;
	*/
	public function setWhere($sWhere){
		if($sWhere){
			$sWhere = ($sWhere ? "WHERE ".$sWhere : "");
		}else{
			$sWhere = "";
		}
		return $sWhere;
	}
	/*process aData*/
	public function processArrayData($aData){
		if($aData){
			$sField = "";
			$sValue = "";
			foreach($aData as $field => $value)
			{
				$sField .= "`".$field."`,";
				$sValue .= "'".$value."',";
			}
		}else{
			$sData = "";
		}
		return $sData = "(".substr($sField,0,(strlen($sField)-1)).") VALUES (".substr($sValue,0,(strlen($sValue)-1)).")";
	}
	
	/*process aData for update*/
	public function processAData($aData){
		if($aData){
			foreach($aData as $field => $value)
			{
				$sData .= "".$field."=";
				$sData .= "'".$value."',";
			}
		}else{
			$sData = "";
		}
		return $sData = substr($sData,0,(strlen($sData)-1));
	}
	/*
	 desc: update datas
	@param $iTable = ex.(1=PG_MAIN || 2=PG_SETTING || 3=PG_CONTENT);
	@param $aData = ex.(array(field_name => 'samplefieldname',field_name2 => 'samplefieldname2',...));
	return $bResult = true if the query is executed else false
	*/
	public function updateData($iTable,$aData,$sWhere){
		$this->init();
		$sTable = $this->chooseTable($iTable);
		$sData = $this->processAData($aData);
		$sWhere = $this->setWhere($sWhere);
	
		$sSql = "UPDATE ".$sTable." SET ".$sData." ".$sWhere;
		$bUpdated = $this->query($sSql);
	
		$bResult = isset($bUpdated)?true:false;
		return $bResult;
	}
	
	/*delete data*/
   public function deleteData($iTable,$sWhere){
		$this->init();
		$sTable = $this->chooseTable($iTable);
		$sWhere = $this->setWhere($sWhere);
		$bDeleted = $this->query("DELETE FROM ".$sTable." ".$sWhere);
		$bResult = isset($bDeleted)?true:false;
		return $bResult;
	}
	/*
	 desc: insert datas
	@param $iTable = ex.(1=PG_MAIN || 2=PG_SETTING || 3=PG_CONTENT);
	@param $aData = ex.(array(field_name => 'samplefieldname',field_name2 => 'samplefieldname2',...));
	return $bResult = true if the query is executed else false
	*/
	public function insertData($iTable,$aData)
	{
		$this->init();
		$sTable = $this->chooseTable($iTable);
		$sData = $this->processArrayData($aData);
		$sSql = "INSERT INTO ".$sTable.$sData;
		$bInsert = $this->query($sSql);
		$bResult = isset($bInsert)?true:false;
		return $bResult;
	}
	
	/*delete seq datas*/
	public function deleteContentsBySeq($iTable,$aSeq)
	{
		$this->init();
		$sTable = $this->chooseTable($iTable);
		$sSeqs = implode(',', $aSeq);
		$sQuery = "Delete from ".$sTable." where seq in($sSeqs)";
		$mResult = $this->query($sQuery);
		return $mResult;
	}

}
