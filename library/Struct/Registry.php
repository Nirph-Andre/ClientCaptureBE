<?php


/**
 * Global Registry functionality and context data handler.
 * @author andre.fourie
 */
class Struct_Registry extends Zend_Registry
{
	
	/**
	 * Session handler for data caching.
	 * @var Zend_Session_Namespace
	 */
	static protected $contextSession = null;
	
	/**
	 * getter method, basically same as offsetGet().
	 *
	 * This method can be called from an object of type Zend_Registry, or it
	 * can be called statically.  In the latter case, it uses the default
	 * static instance stored in the class.
	 *
	 * @param string $index - get the value associated with $index
	 * @return mixed
	 * @throws Zend_Exception if no entry is registerd for $index.
	 */
	static public function get($index) {
		if ('Util.' == substr($index, 0, 5)) {
			if (!Zend_Registry::isRegistered($index)) {
				list($namespace, $utility) = explode('.', $index);
				$className = 'Struct_Util_' . ucfirst($utility);
				$util = new $className();
				Zend_Registry::set($index, $util);
			}
		} elseif ('Config.' == substr($index, 0, 7)) {
			if (!Zend_Registry::isRegistered($index)) {
				list($config, $namespace) = explode('.', $index);
				$util = new ReGen_Config($namespace);
				Zend_Registry::set($index, $util);
			}
		}
		return Zend_Registry::get($index);
	}
	
	/**
	 * Store context item.
	 * @param  string $type
	 * @param  mixed  $value
	 * @return Struct_Registry
	 */
	static public function setContext($type, $value)
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		isset(self::$contextSession->context)
			|| self::$contextSession->context = array();
		self::$contextSession->context[$type] = $value;
	}
	
	/**
	 * Retrieve context item value.
	 * @param  string $type
	 * @return mixed
	 */
	static public function getContext($type)
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		isset(self::$contextSession->context)
			|| self::$contextSession->context = array();
		return isset(self::$contextSession->context[$type])
			? self::$contextSession->context[$type]
			: null;
	}
	
	/**
	 * Remove context item.
	 * @param  string $type
	 * @return mixed
	 */
	static public function unlinkContext($type)
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		isset(self::$contextSession->context)
			|| self::$contextSession->context = array();
		if (isset(self::$contextSession->context[$type]))
		{
			unset(self::$contextSession->context[$type]);
		}
	}
	
	/**
	 * Retrieve all context data.
	 * @return array
	 */
	static public function getContextData()
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		isset(self::$contextSession->context)
			|| self::$contextSession->context = array();
		return self::$contextSession->context;
	}
	
	/**
	 * Set authentication data, this flags this session as logged in an authenticated.
	 * @param  mixed $data
	 * @return Struct_Registry
	 */
	static public function setAuthentication($data)
	{
		return self::setContext('AUTHENTICATION', $data);
	}
	
	/**
	 * Check for specified user type.
	 * @param  string $type 'User','Dealer Principle','Regional Manager','Administrator'
	 * @return boolean
	 */
	static public function isUserType($type)
	{
		$authData = self::getContext('AUTHENTICATION');
		return is_null($authData)
				|| $authData['user_type'] != $type
			? false
			: true;
	}
	
	/**
	 * Unset authentication data, this flags this session as NOT authenticated.
	 * @return Struct_Registry
	 */
	static public function unsetAuthentication()
	{
		return self::unlinkContext('AUTHENTICATION');
	}
	
	/**
	 * Check if session is authenticated.
	 * @return boolean
	 */
	static public function isAuthenticated()
	{
		return is_null(self::getContext('AUTHENTICATION'))
			? false
			: true;
	}
	
	/**
	 * Retrieve authentication data.
	 * @param  string $param
	 * @return mixed
	 */
	static public function getAuthParam($param)
	{
		$authData = self::getContext('AUTHENTICATION');
		return isset($authData[$param])
			? $authData[$param]
			: null;
	}
	
	/**
	 * Set authentication data parameter.
	 * @param  string $param
	 * @param  unknown $value
	 * @return mixed
	 */
	static public function setAuthParam($param, $value)
	{
		$authData = self::getContext('AUTHENTICATION');
		$authData[$param] = $value;
		return self::setContext('AUTHENTICATION', $authData);
	}
	
	/**
	 * Retrieve authentication data.
	 * @return mixed
	 */
	static public function getAuthData()
	{
		return self::getContext('AUTHENTICATION');
	}
	
}