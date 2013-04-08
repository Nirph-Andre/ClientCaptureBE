<?php

class Attachment_UploadController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibAttachment';
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$upload_handler = new Component_AttachmentUpload();
	}
		

}

