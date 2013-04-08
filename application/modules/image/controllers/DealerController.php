<?php

class Image_DealerController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibPhoto';
	
	
	
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
			header('Location: /images/NoDelareImage.png');
			exit(0);
		}
		$photo = $this->getObject()
			->view($id)
			->data;
		if (empty($photo))
		{
			header('Location: /images/NoDelareImage.png');
			exit(0);
		}
		else
		{
			header("content-type: " . $photo['mime_type']);
			echo $photo['photo'];
		}
	}
		

}

