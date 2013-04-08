<?php


/**
 * Handy static app-specific config component to make things a tad easier.
 * @author andre.fourie
 */
class Component_Config
{
	
	/**
	 * Static keeper of the session.
	 * @var Zend_Session_Namespace
	 */
	static private $_session = null;
	
	
	/**
	 * Load configs.
	 * @return void
	 */
	static private function _loadConfig()
	{
		if (!is_null(self::$_session) && !defined('DEBUG_UNITTEST'))
		{
			return;
		}
		self::$_session = new Zend_Session_Namespace('App.Config');
		if (!isset(self::$_session->baseConfig) || defined('DEBUG_UNITTEST'))
		{
			$oConfig = new Object_Config();
			self::$_session->baseConfig = $oConfig->view(1)->data;
		}
	}
	
	/**
	 * Retrieve admin email for general admin notifications.
	 * @return string
	 */
	static public function getAdminEmail()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['administrative_email'];
	}
	
	/**
	 * Retrieve email address used as source for sending email.
	 * @return string
	 */
	static public function getEmailSourceAddress()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['notification_source_email'];
	}
	
	/**
	 * Retrieve mobile number used as source for sending sms.
	 * @return string
	 */
	static public function getSmsSourceAddress()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['notification_source_number'];
	}
	
	/**
	 * Grab the country id.
	 * @return string
	 */
	static public function getCountryId()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['country_id'];
	}
	
	/**
	 * Grab the currency prefix.
	 * @return string
	 */
	static public function getCurrencyPrefix()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['currency_prefix'];
	}
	
	/**
	 * Grab the currency id.
	 * @return string
	 */
	static public function getCurrencyId()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['lib_currency_id'];
	}
	
	/**
	 * Grab the vat percentage.
	 * @return string
	 */
	static public function getVatPercentage()
	{
		self::_loadConfig();
		return self::$_session->baseConfig['vat_percentage'];
	}
	
	
}

