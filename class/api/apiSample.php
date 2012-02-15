<?php
class apiSample extends Controller_Api
{
    protected function get($args)
    {
	return 'get';
    }
    
    protected function post($args)
    {
	return 'post';
    }
    
    protected function put($args)
    {
	return 'put';
    }
    
    protected function delete($args)
    {
	return 'delete';
    }
}
