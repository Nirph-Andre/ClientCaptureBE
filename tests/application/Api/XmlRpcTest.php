<?php

class Api_XmlRpcTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	
	protected $data = array();

    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
        
        #-> Authenticate.
        //Component_Authentication::login('greg@simmons-cars.co.za', '1111');
    }

	public function tearDown()
	{
		$this->resetRequest();
		$this->resetResponse();
		parent::tearDown();
	}
	
	public function testGetVehicleListing()
	{
		$client = new Zend_XmlRpc_Client('http://'.APP_HOST.'/api/xml-rpc/');
		/* 
		$data = $client->call('system.listMethods', array());
		Struct_Debug::errorLog('system.listMethods response', $data);
		$this->assertTrue(is_array($data) && !empty($data));
		*/
		$data = $client->call('system.methodHelp', array('retail.getVehicleListing'));
		Struct_Debug::errorLog('system.methodHelp response', $data);
		
		$data = $client->call('system.methodSignature', array('retail.getVehicleListing'));
		Struct_Debug::errorLog('system.methodSignature response', $data);
		 /*
		
		#-> Bad auth test.
		$errorMessage = false;
		try
		{
			$data = $client->call('retail.getVehicleListing', array('?'));
		}
		catch (Zend_XmlRpc_Client_Exception $e)
		{
			$errorMessage = $e->getMessage();
			//Struct_Debug::errorLog('auth fail response', $errorMessage);
		}
		$this->assertTrue($errorMessage != false);
		
		#-> Good auth test & list vehicles.
		$data = $client->call('retail.getVehicleListing', array('8deba4865385c926b093b676ee39da57853a5c22'));
		Struct_Debug::errorLog('retail.getVehicleListing response', $data);
		$this->assertTrue(is_array($data));
		*/
	}


}

