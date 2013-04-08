<?php

class Image_UploadController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibPhoto';
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$upload_handler = new Component_ImageUpload();
	}
		

}

