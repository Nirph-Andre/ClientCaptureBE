<?php

class Api_UtilController extends Struct_Abstract_Controller
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
	
	public function checkAuth()
	{
		if (!Struct_Registry::isAuthenticated() && !defined('DEBUG_UNITTEST'))
		{
			$this->jsonResult(Struct_ActionFeedback::error(
					'Data call without authentication.',
					'You are not authenticated, please login.'
			));
		}
	}

	public function indexAction()
	{
		$this->jsonResult(Struct_ActionFeedback::success());
	}
	
	public function checkCaptchaAction()
	{
		$capt = $this->getRequest()->getParam('captcha_code', false);
		if (!$capt)
		{
			$this->jsonBasicResult('Answer required.');
		}
		include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
		$securimage = new Securimage();
		$valid = (false == $securimage->check($capt))
			? 'Incorrect answer provided'
			: true;
		$this->jsonBasicResult($valid);
	}
	
	public function checkIdNumberAction()
	{
		$idNum = $this->getRequest()->getParam('id_number', false);
		if (!$idNum)
		{
			$this->jsonBasicResult('ID number required.');
		}
		$valid = Component_Verify::verifyIdNumber($idNum)
			? true
			: 'Invalid ID number.';
		if ($valid == true)
		{
			$oUtil = new Struct_Util_Display();
			$year = (int)substr($idNum, 0, 2);
			$year = $year < substr(date('Y'), 0, 2)
				? '20' . substr($idNum, 0, 2)
				: '19' . substr($idNum, 0, 2);
			$date = $year 
				. '-' . substr($idNum, 2, 2) 
				. '-' . substr($idNum, 4, 2);
			$age = $oUtil->ageFromDate($date);
			if ($age < 18)
			{
				$valid = 'You must be at least 18 to use Bid4Cars.';
			}
		}
		$this->jsonBasicResult($valid);
	}
	
	public function checkUniqueEmailAction()
	{
		$email = $this->getRequest()->getParam('email', false);
		if (!$email)
		{
			$this->jsonBasicResult('E-mail address is required.');
		}
		$oProfile = new Object_Profile();
		$count = $oProfile->count(array('email' => $email), true);
		$valid = (0 == $count)
			? true
			: 'E-mail address already in use.';
		$this->jsonBasicResult($valid);
	}


}




























