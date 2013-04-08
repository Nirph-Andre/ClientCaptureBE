<?php

class Api_AuthenticationController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Profile';
	protected $_createCheck = array();
	protected $_updateCheck = array();
	protected $_deleteCheck = array();
	

	public function init()
	{
		$this->_helper->layout()->disableLayout();
	}

    public function indexAction()
    {
    	$this->jsonResult(Struct_ActionFeedback::success());
    	$this->_helper->viewRenderer->setNoRender(true);
    }

    public function registerAction()
    {
    	try
    	{
    		#-> Collect input
	    	$request = $this->getRequest();
	    	$username = $request->getParam('username', false);
	    	$email = $request->getParam('email', false);
	    	
	    	#-> Safety check
	    	$filter = array('email' => $email);
	    	$result = $this->getObject()
						->view(null, $filter);
	    	if (!empty($result->data))
	    	{
	    		#-> Peractio
	    		$this->jsonResult(Struct_ActionFeedback::error(
	    				'Invalid Email or Password.',
	    				'Invalid Email or Password.'
	    				));
	    	}
	    	
	    	#-> Register user
	    	$pass = '1111'; //substr(mt_rand(1000000, 9999999), 0, 4);
	    	$salt = sha1(mt_rand(1000000, 9999999));
	    	$data = array(
	    			'display_name' => $username,
	    			'email' => $email,
	    			'password' => sha1(sha1($pass).'Salt'.$salt),
	    			'password_salt' => $salt
	    			);
	    	$this->jsonResult($this->getObject()
						->save(null, $filter, $data));
	    }
	    catch (Exception $e)
	    {
	    	#-> Peractio
    		$this->jsonResult(Struct_ActionFeedback::error(
    				"$e", 'Service Error.', array(), $e
    				));
	    }
    }
		
		public function checkCurrentPinAction()
		{
			$request = $this->getRequest();
			$pin = $request->getParam('pin', false);
			$res = Component_Authentication::checkCurrentPin($pin);
			$this->jsonResult($res);
		}

    public function loginAction()
    {
    	#-> Collect input.
    	$request = $this->getRequest();
    	$params = $request->getParams();
    	if (count($params) <= 3)
    	{
    		$params = json_decode(file_get_contents('php://input'), true);
    	}
    	$email    = isset($params['email'])
    		? trim($params['email'])
    		: false;
    	$username = trim($params['username']);
    	$password = trim($params['password']);
    	$remember = isset($params['remember'])
    		? $params['remember']
    		: 0;
    	
    	#-> Auth.
    	$res = Component_Authentication::login($email, $username, $password);
    	$rem = ($res->ok() && $remember)
    		? $username
    		: '';
    	setcookie( "UserEmail", $rem, strtotime( '+30 days' ) );
    	$this->jsonResult($res);
    }

    public function resetAction()
    {
    	#-> Collect input.
    	$request = $this->getRequest();
    	$params = $request->getParams();
    	if (count($params) <= 3)
    	{
    		$params = json_decode(file_get_contents('php://input'), true);
    	}
    	$email  = $params['email'];
    	$mobile = $params['mobile'];
    	$sendToMobile = $params['sendSms'];
    	
    	#-> Reset pin.
    	$this->jsonResult(
    			Component_Authentication::reset($email, $mobile, $sendToMobile)
    			);
    }
    
    public function loggedInAction()
    {
    	if (!Struct_Registry::isAuthenticated())
    	{
    		#-> Peractio
    		$this->jsonResult(Struct_ActionFeedback::error(
    				'Not logged in.',
    				'Not logged in.'
    		));
    	}
    	#-> Fin
    	$this->jsonResult(Struct_ActionFeedback::successWithData(
    			Struct_Registry::getAuthData()
    			));
    }
    
    public function logoutAction()
    {
    	Component_Authentication::logout();
    	$this->_redirect('/');
    }


}

