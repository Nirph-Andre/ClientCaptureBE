<?php

class Video_IndexController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibVideo';
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$id = $this->getRequest()->getParam('id', 'null');
		if (!$id)
		{
			exit(0);
		}
		$document = $this->getObject()
			->view($id)
			->data;
		if (empty($document))
		{
			exit(0);
		}
		else
		{
			header("content-type: " . $document['mime_type']);
			echo $document['video'];
		}
	}
		

}

