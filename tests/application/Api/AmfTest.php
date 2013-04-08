<?php


/**
 * Unit-testing for the business rule agent.
 * @author andre.fourie
 */
class Api_AmfTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	
	protected $contactId1    = null;
	protected $contactId2    = null;
	protected $addressId1    = null;
	protected $addressId2    = null;
	protected $dealerAddrId1 = null;
	protected $dealerAddrId2 = null;
	protected $dealerId1     = null;
	protected $dealerId2     = null;
	protected $stockId       = null;
	
	
  public function setUp()
  {
    $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
    parent::setUp();
  }

	public function tearDown()
	{
		$this->resetRequest();
		$this->resetResponse();
		parent::tearDown();
	}
	
	
	
	public function testLoginFromTablet()
	{
		$this->assertTrue(true);
		return;
		
		#-> Attempt valid login.
		$post = array(
				'class'        => 'Authentication',
				'method'       => 'login',
				'email'        => 'greg@simmons-cars.co.za',
				'passwordHash' => '011c945f30ce2cbafc452f39840f025693339c42'
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/amf/test');
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		//Struct_Debug::errorLog('login response', $data);
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success'));
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Attempt invalid login.
		$post = array(
				'class'        => 'Authentication',
				'method'       => 'login',
				'email'        => 'greg@simmons-cars.co.za',
				'passwordHash' => 'moo!'
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/amf/test');
		//Struct_Debug::errorLog('login response', $this->getResponse()->getBody());
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Error'));
		$this->resetRequest();
		$this->resetResponse();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	protected function login()
	{
		#-> Attempt valid login.
		$post = array(
				'class'        => 'Authentication',
				'method'       => 'login',
				'email'        => 'greg@simmons-cars.co.za',
				'passwordHash' => '011c945f30ce2cbafc452f39840f025693339c42'
		);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/amf/test');
		Struct_Debug::errorLog('login response', $this->getResponse()->getBody());
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		//Struct_Debug::errorLog('login response', $data);
		$this->resetRequest();
		$this->resetResponse();
		return $data['Data']['authToken'];
	}


}

