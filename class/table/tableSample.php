<?php
class tableSample extends Table
{
    public $TableName = 'tbl_sample';

    public $PrimariKey = 'seq';
    
    public function add($sData)
    {
	$infoRow = $this->CreateRow();
	
	$infoRow->setData('column', $sData);

	return $infoRow->fetchData();
    }
}
