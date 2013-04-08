<?php

class Api_DataTest extends Zend_Test_PHPUnit_ControllerTestCase
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
	
	public function testCreateData()
	{
		#-> Create with valid data.
		$table = new Zend_Db_Table('contact_request');
		$table->getAdapter()
			->query('TRUNCATE contact_request');
		$table = new Zend_Db_Table('contact_request');
		$table->getAdapter()
			->query('TRUNCATE contact_request');
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
		
		
		#-> Create with invalid data.
		$post = array(
				"ContactRequest" => array(
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
		//Struct_Debug::errorLog(' create response', $this->getResponse()->getBody());
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
		$this->assertTrue(
				(bool) (isset($data['Error'])
						&& isset($data['Message']))
		);
		$this->resetRequest();
		$this->resetResponse();
	}
	
	public function testFetchCreatedData()
	{
		#-> Valid fetch
		$this->dispatch('/api/data/find?ContactRequest=1');
		//Struct_Debug::errorLog('fetch response', $this->getResponse()->getBody());
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
				(bool) (isset($data['Data']['ContactRequest']['person_name'])
					&& $data['Data']['ContactRequest']['person_name'] == 'Woo Foo')
				);
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Invalid fetch
		$this->dispatch('/api/data/find?ContactRequest=x');
		//Struct_Debug::errorLog('fetch response', $this->getResponse()->getBody());
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
		$this->assertTrue(
				(bool) (isset($data['Error'])
						&& isset($data['Message']))
		);
		$this->resetRequest();
		$this->resetResponse();
	}
	
	public function testUpdateData()
	{
		#-> Valid update request.
		$moo = '{"ContactRequest":{"id":"1", "person_name":"Woo Foo Moo","trading_name":"Foo Woo Us","email":"woo@foo.us","mobile":"0851231234","telephone":"0121231234","subject":"Fooness","message":"Can you supply us our fooness that we require?"}}';
		$this->request->setMethod('POST')
			->setPost(Zend_Json::decode($moo));
		$this->dispatch('/api/data/update');
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		//Struct_Debug::errorLog('response', $data);
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success'));
		$this->assertTrue(
				(bool) (isset($data['Data']['ContactRequest']['person_name'])
					&& $data['Data']['ContactRequest']['person_name'] == 'Woo Foo Moo')
		);
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Invalid update request.
		$moo = '{"ContactRequest":{"id":"moo", "person_name":"AAARRGGHHH!","trading_name":"IbApiRate","email":"moo.yap","mobile":"..."}}';
		$this->request->setMethod('POST')
			->setPost(Zend_Json::decode($moo));
		$this->dispatch('/api/data/update');
		//Struct_Debug::errorLog('response', $this->getResponse()->getBody());
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
		$this->assertTrue(
				(bool) (isset($data['Error'])
						&& isset($data['Message']))
		);
		$this->resetRequest();
		$this->resetResponse();
	}
	
	public function testFindQueryRequest()
	{
		#-> Add some extra data to mess around with.
		$post = array(
				"ContactRequest" => array(
						"person_name" => "Moo Foo",
						"trading_name" => "Moo Woo Us",
						"email" => "woo@foo.us",
						"mobile" => "0851231234",
						"telephone" => "0121231234",
						"subject" => "Fooness",
						"message" => "Can you supply us the mooness that we require?"
				));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		$this->resetRequest();
		$this->resetResponse();
		$post = array(
				"ContactRequest" => array(
						"person_name" => "Woo Baz",
						"trading_name" => "Foo Baz Us",
						"email" => "woo@foo.us",
						"mobile" => "0851231234",
						"telephone" => "0121231234",
						"subject" => "Bazness",
						"message" => "Can you supply us the fooness that we require?"
				));
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/create');
		$this->resetRequest();
		$this->resetResponse();
		
		#-> Test find query, expect data.
		$post = array(
				"ContactRequest" => array('email' => 'woo@foo.us')
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/find-query');
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
		
		#-> Test find query, expect no data.
		$post = array(
				"ContactRequest" => array('email' => 'woo@foooooobaaaarbaaaz.us')
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/find-query');
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		//Struct_Debug::errorLog('find query response', $data);
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success'));
		$this->assertTrue(
				(bool) (isset($data['Data']['ContactRequest'])
					&& empty($data['Data']['ContactRequest']))
				);
	}
	
	public function testListRequest()
	{
		$post = array(
				"ContactRequest" => array(
						'filter' => array('email' => 'woo@foo.us'),
						'order' => array()
						)
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/list');
		//Struct_Debug::errorLog('list response', $this->getResponse()->getBody());
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
				(bool) (isset($data['Data']['ContactRequest'][1])
					&& $data['Data']['ContactRequest'][1] == 'Woo Foo Moo')
				);
	}
	
	public function testGridRequest()
	{
		$post = array(
				"ContactRequest" => array(
						'filter' => array('email' => 'woo@foo.us'),
						'order' => array('id' => 'DESC')
						)
				);
		$this->request->setMethod('POST')
			->setPost($post);
		$this->dispatch('/api/data/grid-query');
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		Struct_Debug::errorLog('grid response', $data);
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success'));
		$this->assertTrue(
				(bool) (isset($data['Data']['ContactRequest'])
					&& 3 == count($data['Data']['ContactRequest']))
				);
	}
	
	public function testDeleteData()
	{
		$this->dispatch('/api/data/delete?ContactRequest=1');
		//Struct_Debug::errorLog('response', $this->getResponse()->getBody());
		try
		{
			$data = Zend_Json::decode($this->getResponse()->getBody());
		}
		catch (Exception $e)
		{
			$data = array();
		}
		$this->dispatch('/api/data/delete?ContactRequest=2');
		$this->dispatch('/api/data/delete?ContactRequest=3');
		$status = isset($data['Status'])
			? $data['Status']
			: false;
		$this->assertTrue((bool) ($status == 'Success'));
		$this->assertTrue(
				(bool) (isset($data['Data']['ContactRequest']['id'])
					&& $data['Data']['ContactRequest']['id'] == 1)
				);
		$table = new Zend_Db_Table('contact_request');
		$table->getAdapter()
			->query('TRUNCATE contact_request');
	}


}

