<?php
class MyTest extends Unittest_Testcase
{
    public function testMyValue()
    {
	$this->assertSame("1","1");
    }
}
