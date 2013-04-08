<?php


/**
 * Base functionality for amf service classes.
 * @author andre.fourie
 *
 */
class Struct_Abstract_AmfService
{
	
	/**
	 * Remember if we have an auth token.
	 * @var boolean
	 */
	protected $_haveAuth = false;
	
	/**
	 * Load up correct session.
	 * @param string $authToken
	 * @throws Zend_Amf_Exception
	 * @return boolean
	 */
	protected function _setSession($authToken)
	{
		if ($this->_haveAuth || $authToken === false || defined('DEBUG_UNITTEST'))
		{
			return true;
		}
		try
		{
			ini_set('session.use_cookies', false);
			ini_set('session.use_only_cookies', false);
			if ($authToken != session_id())
			{
				if(session_id() != '') {
					session_destroy();
				}
				session_id($authToken);
				session_start();
			}
			if (!Struct_Registry::isAuthenticated())
			{
				throw new Zend_Amf_Exception('Invalid session.');
			}
			$this->_haveAuth = true;
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog(__CLASS__, "$e");
			throw new Zend_Amf_Exception('Invalid authentication token.');
		}
		return true;
	}
	
	
	/**
	 * Retrieve data access object.
	 * @param string $name
	 * @return Struct_Abstract_DataAccess
	 */
	protected function _getObject($authToken, $name)
	{
		$this->_setSession($authToken);
		$class = "Object_$name";
		return new $class();
	}
	
	/**
	 * Used for db synchronization between tablet devices and primary db.
	 * @param string $object
	 * @param string $action
	 * @param array $data
	 * @param array $options
	 * @return array
	 */
	protected function synch($authToken, $object, $action, array $data , array $options = array())
	{
		#-> Set the request action.
		$result = $this->_getObject($authToken, $object)
			->process(new Struct_ActionRequest($action, $data, $options));
		if (!$result->ok())
		{
			return array();
		}
		switch($action)
		{
			case 'Create':
				$result->data = array(array('id' => $result->data['id']));
				return $result->pack();
				break;
			case 'Update':
			case 'Delete':
				$result->data = array(array('id' => $data['id']));
				return $result->pack();
				break;
			case 'Find':
				$result->data = array(array($result->data));
				return $result->pack();
				break;
			case 'List':
			case 'Grid':
				return $result->pack();
				break;
		}
	}
	
	
}

