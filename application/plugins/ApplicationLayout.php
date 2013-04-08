<?php


class Application_Plugin_ApplicationLayout extends Zend_Controller_Plugin_Abstract
{
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		Zend_Layout::getMvcInstance()->setLayout('BootstrapLayout');
	}
	
}