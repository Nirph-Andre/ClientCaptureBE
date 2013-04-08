<?php
# ! Not currently in use and has no services in relevant folder,
#   add service, replace SomeClass text in this file with relevant name,
#   and all is fine :)
include_once(APPLICATION_PATH . '/services/soap/SomeClass.php');



class Api_SoapController extends Struct_Abstract_Controller
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
		$this->_serviceUrl = 'http://' . APP_HOST . '/api/soap';
		if (isset($_GET['wsdl'])){
		    $autodiscover = new Zend_Soap_AutoDiscover('Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex');
		    $autodiscover->setClass('SomeClass');
		    $autodiscover->handle();
		    $autodiscover->toXml();
		} else {
		    $server = new Zend_Soap_Server($this->_serviceUrl . "?wsdl");
		    $server->setClass('SomeClass');
		    $server->setObject(new SomeClass());
		    $server->handle();
		}
	}


}

