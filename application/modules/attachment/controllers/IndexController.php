<?php

class Attachment_IndexController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibAttachment';
	
	
	
	public function init()
	{
    if (!Struct_Registry::isAuthenticated())
    {
    	header("Location: /login");
     	exit();
    }
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$id = $this->getRequest()->getParam('id', 'null');
		if (!$id)
		{
			header('Location: /documents/no_document.txt');
			exit(0);
		}
		$document = $this->getObject()
			->view($id)
			->data;
		if (empty($document))
		{
			header('Location: /documents/no_document.txt');
			exit(0);
		}
		else
		{
			header("content-type: " . $document['mime_type']);
			echo $document['document'];
		}
	}
		

}

