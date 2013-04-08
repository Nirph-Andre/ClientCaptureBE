<?php

class Video_PlayerController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_LibVideo';
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
	}

	public function indexAction()
	{
		$this->view->videoId = $this->getRequest()->getParam('id', 0);
		if ($this->view->videoId)
		{
			$this->view->video = $this->getObject()
				->view($this->view->videoId)
				->data;
		}
	}
		

}

