<?php


/**
 * Handy static authentication handling.
 * @author andre.fourie
 */
class Component_Authentication
{
	
	/**
	 * Static keeper of the session.
	 * @var Zend_Session_Namespace
	 */
	static private $_session = null;
	
	
	/**
	 * Authenticate user.
	 * @param	string $email
	 * @param	string $password
	 * @return Struct_ActionFeedback
	 */
	static public function login($email, $username, $password)
	{
		#-> Retrieve data
		$oProfile = new Object_Profile();

		$filter = $email
			? array('email' => $email)
			: array('username' => $username);
		$data = $oProfile->view(null, $filter)->data;
		#-> Match?
		if (!empty($data)
				&& sha1(sha1($password).'Salt'.$data['password_salt']) == $data['password'])
		{
			#-> Set auth data
			$data = $oProfile->view(null, $filter, true)->data;
			if ('Suspended' == $data['status'])
			{
				return Struct_ActionFeedback::error(
						'Your account is currently suspended, login not authorized.',
						'Your account is currently suspended, login not authorized.'
				);
			}
			$data['is_admin'] = ('Administrator' == $data['user_type'])
				? true
				: false;
			$data['permissions'] = self::_getPermissions($data);
			Struct_Registry::setAuthentication($data);
			
			#-> Clear context data.
			Struct_Registry::setContext(
					'dataContext',
					array()
					);
			
			#-> Log auth event.
			$oLog = new Object_LibAuthenticationLog();
			$oLog->save(null, array(), array(
					'ip_address' => $_SERVER['REMOTE_ADDR'],
					'profile_id' => $data['id']
					));
			
			#-> Fino
			return Struct_ActionFeedback::successWithData($data);
		}
		else
		{
			#-> Peractio
			return Struct_ActionFeedback::error(
					'Your username and password did not match, please try again.',
					'Your username and password did not match, please try again.'
					);
		}
	}
	
	static public function refreshSession()
	{
		$auth = Struct_Registry::getAuthData();
		$auth['permissions'] = self::_getPermissions($auth);
		Struct_Registry::setAuthentication($auth);
	}
	
	static public function checkCurrentPin($pin)
	{
		$auth = Struct_Registry::getAuthData();
		
		$oProfile = new Object_Profile();
		$filter = array();
		$data = $oProfile->view($auth['id'], $filter)->data;
		
		if (sha1(sha1($pin).'Salt'.$data['password_salt']) != $data['password'])
		{
			return Struct_ActionFeedback::error(
					'Incorrect PIN supplied.',
					'Incorrect PIN supplied.'
					);
		}
		return Struct_ActionFeedback::success();
	}
	
	/**
	 * Log current user out and destry session.
	 * @return void
	 */
	static public function logout()
	{
		Struct_Registry::unsetAuthentication();
			$_SESSION = array();
	}
	
	/**
	 * Reset profile password.
	 * @param string|null $email
	 * @param string|null $mobile
	 * @return Struct_ActionFeedback
	 */
	static public function reset($email, $mobile, $sendToMobile = false)
	{
		#-> Find user.
		$oProfile = new Object_Profile();
		$filter = array();
		if ($email)
		{
			$filter['email'] = $email;
		}
		if (empty($email) && $mobile)
		{
			$filter['mobile'] = $mobile;
		}
		$data = $oProfile->view(null, $filter)->data;
		if (empty($data))
		{
			return Struct_ActionFeedback::error(
					'Invalid Email or Mobile.',
					'We were unable to find your information, please contact Bid4Cars.'
			);
		}
		
		#-> Gen new password and save to db.
		$password = mt_rand(1000, 9999);
		$update['password'] = sha1(sha1($password).'Salt'.$data['password_salt']);
		$oProfile->save($data['id'], array(), $update);
		
		#-> Notify the user.
		$templateParams = array(
				'first_name'  => $data['first_name'],
				'family_name' => $data['family_name'],
				'email'       => $data['email'],
				'pin'         => $password
				);
		$smsTo = $sendToMobile
			? $data['mobile']
			: false;
		Component_Notification::sendFromTemplate(
				$data['email'], $data['mobile'], null, 'Forgot Pin', $templateParams,
				array(), array(), !Component_Config::doSendPinSms()
		);
		return Struct_ActionFeedback::success();
	}
	
	/**
		 * Establish user permissions.
		 * @param array $profile
		 * @return array
		 */
	static private function _getPermissions(array $profile)
	{
		if ('Administrator' == $profile['user_type'])
		{
			#-> Administrator.
			Struct_Registry::setContext('Actor', 'Administrator');
			$allow = array(
					'Home' => array(
							'Dashboard' => true,
							),
					'Example' => array(
							'Item A' => true,
							'Item B' => true
							)
					);
		}
		else
		{
			#-> Registered user.
			Struct_Registry::setContext('Actor', 'User');
			$allow = array(
					'Example A' => array(
							'Item A' => true,
							'Item B' => true
							),
					'Example B' => array(
							'Item A' => true,
							'Item B' => true
							)
					);
		}
		return $allow;
	}
	
	
}

