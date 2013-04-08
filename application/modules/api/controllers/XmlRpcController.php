<?php
include_once(APPLICATION_PATH . '/services/xml-rpc/Example.php');


class Api_XmlRpcController extends Struct_Abstract_Controller
{
	
	/**
	 * @var string
	 */
	protected $_defaultObjectName = '';
	
	/**
	 * @var string
	 */
	protected $_nameSpace = '';
	
	/**
	 * @var Struct_Abstract_DataAccess
	 */
	protected $_object = false;
	
	/**
	 * @var array
	 */
	protected $_data   = false;
	
	/**
	 * @var array
	 */
	protected $_options   = false;
	
	/**
	 * @var string
	 */
	protected $_serviceUrl = null;
	
	

	public function init()
	{
		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		Zend_XmlRpc_Server_Fault::attachFaultException('Example_Exception');
		$this->_serviceUrl = 'http://' . APP_HOST . '/api/xml-rpc';
		$server = new Zend_XmlRpc_Server();
		$server->setClass('Example', 'example');
		echo $server->handle();
	}


}

