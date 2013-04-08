<?php

class Api_SmsController extends Struct_Abstract_Controller
{
	
	/**
	 * @var string
	 */
	protected $_defaultObjectName = '';
	
	/**
	 * @var string
	 */
	protected $_nameSpace = '';
	
	/**
	 * @var Struct_Abstract_DataAccess
	 */
	protected $_object = false;
	
	/**
	 * @var array
	 */
	protected $_data   = false;
	
	/**
	 * @var array
	 */
	protected $_options   = false;
	
	
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	public function indexAction()
	{
		$this->jsonResult(Struct_ActionFeedback::success());
	}
	
	public function callbackAction()
	{
		$oLog = new Object_LibNotificationLog();
		$params = $this->getRequest()->getParams();
		$msg = $oLog
			->view(null, array('api_msg_id' => $params['apiMsgId']))
			->data;
		if (!empty($msg))
		{
			$msg['sms_status'] = Struct_Comms_Sms::getStatusText($params['status']);
			$oLog->save($msg['id'], array(), array(
					'sms_status' => Struct_Comms_Sms::getStatusText($params['status'])
					));
		}
		echo 'OK';
	}


}




























