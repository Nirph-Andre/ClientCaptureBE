<?php


/**
 * Base functionality for xml-rpc service classes.
 * @author andre.fourie
 *
 */
class Struct_Abstract_XmlRpcService
{
	
	/**
	 * Remember if we have checked authentication token already.
	 * @var boolean
	 */
	static protected $_profileId = false;
	
	
	/**
	 * Authenticate caller.
	 * @param string $authToken
	 * @throws Zend_Amf_Exception
	 * @return void
	 */
	protected function _auth($authToken)
	{
		if (self::$_profileId)
		{
			return;
		}
		$oXmlRpcProfile = new Object_LibXmlrpcProfile();
		$profile = $oXmlRpcProfile->view(null, array('auth_token' => $authToken))->data;
		if (empty($profile))
		{
			throw new Retail_Exception('Invalid authentication token.');
		}
		/* if ($profile['requests'] > XML_RPC_MAX_REQUESTS)
		{
			throw new Retail_Exception('Max requests of ' . XML_RPC_MAX_REQUESTS . ' reached.');
		} */
		$oXmlRpcProfile->save($profile['id'], array(), array('requests' => $profile['requests'] + 1));
		self::$_profileId = $profile['id'];
	}
	
	/**
	 * Retrieve data access object.
	 * @param string $name
	 * @return Struct_Abstract_DataAccess
	 */
	protected function _getObject($authToken, $name)
	{
		$this->_auth($authToken);
		$class = "Object_$name";
		return new $class();
	}
	
	
}

