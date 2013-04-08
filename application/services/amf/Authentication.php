<?php


/**
 * General authentication handling for tablet.
 * @author andre.fourie
 */
class Authentication extends Struct_Abstract_AmfService
{
	
	/**
	 * Handshake.
	 * @return boolean
	 */
	public function ping()
	{
		return true;
	}
	
	/**
	 * Authenticate a user.
	 * @param string $email
	 * @param string $passwordHash
	 * @return Object
	 */
	public function login($email, $passwordHash)
	{
		$sysNope = 'Invalid login attempt.';
		$msgNope = 'Oops, invalid login particulars, please try again.';
		$msgYup  = 'Access granted, welcome back.';
		$result = $this->_getObject(false, 'Profile')
			->view(null, array(
					'email' => $email
					));
		if (empty($result->data)
				|| sha1($passwordHash . 'Salt' . $result->data['password_salt']) != $result->data['password'])
		{
			#-> Peractio
			return Struct_ActionFeedback::error($sysNope, $msgNope)->pack();
		}
		
		#-> Set auth data.
		$result = $this->_getObject(false, 'Profile')
			->view(null, array(
					'email' => $email
			), true);
		$authToken = md5(mt_rand(100000, 999999));
		if(session_id() != '')
		{
			session_destroy();
		}
		ini_set('session.use_cookies', false);
		ini_set('session.use_only_cookies', false);
		session_id($authToken);
		session_start();
		unset($result->data['password']);
		unset($result->data['password_salt']);
		Struct_Registry::setAuthentication($result->data);
		
		#-> Clear context data.
		Struct_Registry::setContext(
				'dataContext',
				array()
		);
		
		#-> Fino.
		$result->data['authToken'] = $authToken;
		return Struct_ActionFeedback::successWithData($result->data)->pack();
	}
	
	/**
	 * Reset profile pin number.
	 * @param string|null $email
	 * @param string|null $mobile
	 * @return Object
	 */
	public function resetPin($email, $mobile)
	{
		return Component_Authentication::reset($email, $mobile);
	}
	
	/**
	 * Authenticate a user automatically.
	 * @param string $email
	 * @param string $finalHash
	 * @return Object
	 */
	public function autoLogin($email, $finalHash)
	{
		$sysNope = 'Invalid login attempt.';
		$msgNope = 'Oops, invalid login particulars, password may have changed recently.';
		$msgYup  = 'Access granted, welcome back.';
		$result = $this->_getObject(false, 'Profile')
			->view(null, array(
					'email' => $email
					));
		if (empty($result->data)
				|| $finalHash != $result->data['password'])
		{
			#-> Peractio
			return Struct_ActionFeedback::error($sysNope, $msgNope)->pack();
		}
		
		#-> Set auth data.
		$authToken = md5(mt_rand(100000, 999999));
		@session_destroy();
		ini_set('session.use_cookies', false);
		ini_set('session.use_only_cookies', false);
		session_id($authToken);
		session_start();
		unset($result->data['password']);
		unset($result->data['password_salt']);
		Struct_Registry::setAuthentication($result->data);
		
		#-> Clear context data.
		Struct_Registry::setContext(
				'dataContext',
				array()
		);
		
		#-> Fino.
		$result->data['authToken'] = $authToken;
		return Struct_ActionFeedback::successWithData($result->data)->pack();
	}
	
	
}

