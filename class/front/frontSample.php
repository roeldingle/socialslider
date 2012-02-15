<?php
class frontSample extends Controller_Front
{
    protected function run($args)
    {
	$this->setStatusCode('200');

	$this->assign('name', 'Hong Gil Dong');
	$this->assign('age', '30');
    }
}
