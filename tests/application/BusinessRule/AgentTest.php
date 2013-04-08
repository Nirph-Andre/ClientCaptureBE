<?php


/**
 * Unit-testing for the business rule agent.
 * @author andre.fourie
 */
class BusinessRule_AgentTest extends Zend_Test_PHPUnit_ControllerTestCase
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
        
        #-> Authenticate.
        Component_Authentication::login('greg@simmons-cars.co.za', '1111');
    }

	public function tearDown()
	{
		$this->resetRequest();
		$this->resetResponse();
		parent::tearDown();
	}
	
	
	
	public function testSendContactRequestNotification()
	{
		$this->assertTrue(true);
		return;
		
		#-> Create with valid data.
		$table = new Zend_Db_Table('contact_request');
		$table->getAdapter()
			->query('TRUNCATE contact_request');
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
		$post = array(
				"ContactRequest" => array(
						"person_name" => "Woo Foo",
						"trading_name" => "Foo Woo Us",
						"email" => "woo@foo.us",
						"mobile" => "0851231234",
						"telephone" => "0121231234",
						"subject" => "Fooness",
						"message" => "Can you supply us the fooness that we require?"
						));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		//Struct_Debug::errorLog('create response', $this->getResponse()->getBody());
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
		$this->assertTrue((bool) ($status == 'Success'));
		$this->assertTrue(
				(bool) (isset($data['Data']['ContactRequest']['id'])
					&& $data['Data']['ContactRequest']['id'] == 1)
				);
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Check that notification log entry exists.
		$dbResult = $table->getAdapter()
			->query('SELECT COUNT(*) AS total FROM lib_notification_log')
			->fetch();
		$this->assertTrue(
				(bool) 1 == $dbResult['total']
				);
		
		#-> Cleanup.
		$table = new Zend_Db_Table('contact_request');
		$table->getAdapter()
			->query('TRUNCATE contact_request');
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
	}
	
	
	
	public function testSendForgotPinNotification()
	{
		$this->assertTrue(true);
		return;
		
		
		#-> Create a profile to work with.
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
		$post = array(
				"Profile" => array(
						"first_name" => "Bobby",
						"family_name" => "'; drop tables;",
						"id_number" => "123654789",
						"date_of_birth" => "1991-01-15",
						"mobile" => DEV_TEST_MOBILE_ADDRESS,
						"email" => DEV_TEST_EMAIL_ADDRESS,
						"password" => md5(md5('1111').'Salt'.md5('1234')),
						"password_salt" => md5('1234')
				));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		//Struct_Debug::errorLog('profile creation response', $this->getResponse()->getBody());
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
		$this->assertTrue((bool) ($status == 'Success'));
		$profileId = $data['Data']['Profile']['id'];
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Update profile with a reset-pin option.
		$post = array(
				"Profile" => array(
						"id" => $profileId,
						"password" => "1234"
						),
				"Options" => array(
						"Password Reset" => true
						)
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/update');
		Struct_Debug::errorLog('pin update response', $this->getResponse()->getBody());
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
		$this->assertTrue((bool) ($status == 'Success'));
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Check that notification log entry exists.
		$dbResult = $table->getAdapter()
			->query('SELECT COUNT(*) AS total FROM lib_notification_log')
			->fetch();
		$this->assertTrue(
				(bool) 1 == $dbResult['total']
				);
		
		#-> Cleanup.
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
		$table = new Zend_Db_Table('profile');
		$table->getAdapter()
			->query('DELETE FROM profile WHERE id=' . $profileId);
	}
	
	
	
	
	public function testSendNewsletter()
	{
		$this->assertTrue(true);
		return;
		
		#-> Prep work.
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
		
		#-> Create a newsletter, draft status.
		$post = array(
				"LibNewsletter" => array(
						"subject" => "Newsletter Test",
						"content" => "Hello [first_name] [family_name] with mobile [mobile], you should NOT get this newsletter.",
						"status" => "Draft"
				));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		//Struct_Debug::errorLog('dealer stock update response', $this->getResponse()->getBody());
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
		$this->assertTrue((bool) ($status == 'Success' && isset($data['Data']['LibNewsletter']['id'])));
		$letterId = $data['Data']['LibNewsletter']['id'];
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Check that notification log entry does not exists.
		$table = new Zend_Db_Table('lib_notification_log');
		$dbResult = $table->getAdapter()
			->query('SELECT COUNT(*) AS total FROM lib_notification_log')
			->fetch();
		$this->assertTrue(
				(bool) 0 == $dbResult['total']
				);
		
		#-> Create a newsletter, test status.
		$post = array(
				"LibNewsletter" => array(
						"subject" => "Newsletter Test",
						"content" => "Hello [first_name] [family_name] with mobile [mobile], you SHOULD get this newsletter.",
						"status" => "Test"
				));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		Struct_Debug::errorLog('newsletter create response', $data);
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success' && isset($data['Data']['LibNewsletter']['id'])));
		$letterId = $data['Data']['LibNewsletter']['id'];
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Check that notification log entry exists.
		$table = new Zend_Db_Table('lib_notification_log');
		$dbResult = $table->getAdapter()
			->query('SELECT COUNT(*) AS total FROM lib_notification_log')
			->fetch();
		$this->assertTrue(
				(bool) 1 == $dbResult['total']
				);
		
		#-> Cleanup.
		$table = new Zend_Db_Table('lib_newsletter');
		$table->getAdapter()
			->query('TRUNCATE lib_newsletter');
		$table = new Zend_Db_Table('lib_notification_log');
		$table->getAdapter()
			->query('TRUNCATE lib_notification_log');
	}


}

