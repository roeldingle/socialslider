<?php
class modelGet extends Model{
	
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
	
	/*
	desc: get a single row in database;
		can be given a single WHERE condition
	@param $iTable = ex.(1=PG_MAIN || 2=PG_SETTING || 3=PG_CONTENT);
	@param $sWhere = ex.("field = 'samplefieldname'");
	return $sTable = single row array;
	*/
	public function getRow($iTable,$sWhere)
	{
		
		$this->init();
		$sTable = $this->chooseTable($iTable);
		$sWhere = $this->setWhere($sWhere);
		$sSql = "SELECT * FROM ".$sTable." ".$sWhere;
		
		$mResult = $this->query($sSql, "row");
		return $mResult;
	
	}
		
	
}
