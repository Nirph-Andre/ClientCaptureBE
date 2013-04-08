<?php

class Api_AmfController extends Struct_Abstract_Controller
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
	
	

	public function init()
	{
		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$server = new Zend_Amf_Server();
		$server->setProduction(!IS_DEV_ENV);
		$server->addDirectory(APPLICATION_PATH . '/services/amf');
		if(!$amf->production)
		{
			$server->setClass('Zend_Amf_Adobe_Introspector');
			$server->setClass('Zend_Amf_Adobe_DbInspector');
		}
		echo($server->handle());
	}
	
	public function testAction()
	{
		if (!IS_DEV_ENV)
		{
			return false;
		}
		//$_SESSION = array();
		//Struct_Registry::setAuthentication(array('dealer_id' => 1));
		$params = $this->getRequest()->getParams();
		$class  = $params['class'];
		$method = $params['method'];
		unset($params['class']);
		unset($params['method']);
		unset($params['module']);
		unset($params['controller']);
		unset($params['action']);
		include_once(APPLICATION_PATH . '/services/amf/' . $class . '.php');
		$oClass = new $class();
		echo Zend_Json::encode(call_user_func_array(array($oClass, $method), $params));
	}


}

