<?php

class Util_UnitTestResultController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Admin';
	
	

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		$this->_helper->layout()->disableLayout();
    }


}

