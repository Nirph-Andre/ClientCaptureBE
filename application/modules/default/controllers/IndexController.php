<?php

class IndexController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Request';
	
	

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	Struct_Registry::setContext('dataContext', array());
    }


}

