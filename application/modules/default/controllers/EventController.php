<?php

class EventController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Request';
	
	

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$originator = new Foo();
    	var_dump(Struct_Event::trigger('Permissions.Updating', $originator));
    	exit();
    }


}

class Foo extends Struct_Abstract_ValueObject {}