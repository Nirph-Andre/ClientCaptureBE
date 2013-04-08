<?php

class Template_DataTest extends Zend_Test_PHPUnit_ControllerTestCase
{
	
	protected $data = array();

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
	
	public function testPopulateTemplates()
	{
		#-> Clear out the templates.
		$table = new Zend_Db_Table('lib_template');
		$table->getAdapter()
			->query('TRUNCATE lib_template');
		$repTable = new Zend_Db_Table('lib_repeater_template');
		$repTable->getAdapter()
			->query('TRUNCATE lib_repeater_template');
		
		#-> Grab a photo, yes i know, this is not the nice way.
		$table->getAdapter()
			->query('TRUNCATE lib_photo');
		/* $table->getAdapter() // use this if you need to populate default pics
			->query('insert into matula.lib_photo (photo,mime_type) '
					. 'select photo,mime_type from matula.x_photo'); */
		
		#-> Populate default templates.
		$templatePath = APPLICATION_PATH . '/../data/templates/';
		$table->insert(array(
				'name' => 'Contact Request',
				'subject' => 'Contact Request',
				'tags' => 'person_name,trading_name,email,mobile,telephone,subject,message',
				'email_template' => file_get_contents($templatePath . 'contact-request.html'),
				'sms_template' => ''
				));
		$table->insert(array(
				'name' => 'Profile Registered',
				'subject' => 'Profile Registered',
				'tags' => 'first_name,family_name,email,pin,user',
				'email_template' => file_get_contents($templatePath . 'new-profile.html'),
				'sms_template' => ''
				));
		$table->insert(array(
				'name' => 'Forgot Pin',
				'subject' => 'Your new pin',
				'tags' => 'first_name,family_name,email,pin',
				'email_template' => file_get_contents($templatePath . 'forgot-pin.html'),
				'sms_template' => file_get_contents($templatePath . 'forgot-pin.txt')
				));
		$table->insert(array(
				'name' => 'Newsletter - Basic',
				'subject' => '',
				'tags' => 'HeaderImageSource,Body,FooterImageSource',
				'email_template' => file_get_contents($templatePath . 'newsletter.html'),
				'sms_template' => ''
				));
		/* $repTable->insert(array( // data repeater example
				'lib_template_id' => $id,
				'group_field' => 'make.name',
				'group_repeater' => file_get_contents($templatePath . 'wishlist-repeater-group.html'),
				'row_repeater_odd' => file_get_contents($templatePath . 'wishlist-repeater-row-odd.html'),
				'row_repeater_even' => file_get_contents($templatePath . 'wishlist-repeater-row-even.html')
				)); */
		
		#-> Be nice to the unit-testing :)
		$this->assertTrue(true);
	}


}

