<?php

#-> Default Timezone.
date_default_timezone_set('Africa/Johannesburg');

# Context Namsespace.
define('NAMESPACE_CONTEXT', 'CONTEXT');

# Feedback Status.
define('RESULT_SUCCESS', 'Success');
define('RESULT_ERROR', 'Error');
define('RESULT_VALIDATION', 'Validation Error');

# Generic system errors.
define('ERROR_SYSTEM_INTERNAL', 'Internal System Error, could not complete request.');
define('ERROR_SYSTEM_INTERNAL_DATA', 'Internal System Error, data could not be updated at this time.');

#-> Table Logic Enforcement Flags.
define('TABLE_NO_INSERT',               1);
define('TABLE_NO_UPDATE',               2);
define('TABLE_NO_DELETE',               4);
define('TABLE_READONLY',               TABLE_NO_INSERT | TABLE_NO_DELETE | TABLE_NO_UPDATE);
define('TABLE_PSEUDO_DELETE',           8);
define('TABLE_TRACK_CHANGES',          16);
define('TABLE_CACHE_RECORD',           32);

#-> Field Flags.
define('FIELD_INSERT_REQUIRED',     1);
define('FIELD_UPDATE_REQUIRED',     2);
define('FIELD_REQUIRED',            FIELD_INSERT_REQUIRED | FIELD_UPDATE_REQUIRED);
define('FIELD_NO_INSERT',           4);
define('FIELD_NO_UPDATE',           8);
define('FIELD_INSERT_DATE',        16);
define('FIELD_INSERT_DATETIME',    32);
define('FIELD_INSERT_TIMESTAMP',   64);
define('FIELD_UPDATE_DATE',       128);
define('FIELD_UPDATE_DATETIME',   256);
define('FIELD_UPDATE_TIMESTAMP',  512);
define('FIELD_TRACK_CHANGES',    1024);
define('FIELD_AUTOKEY',          2048);
define('FIELD_ALLOW_EMPTY',      4096);

#-> Data state for value objects.
define('DATA_STATE_NULL', 0);
define('DATA_STATE_CLEAN', 1);
define('DATA_STATE_DIRTY', 2);

#-> Data intent for value objects.
define('DATA_INTENT_NULL', 0);
define('DATA_INTENT_CREATE', 1);
define('DATA_INTENT_CREATE_MULTI', 2);
define('DATA_INTENT_UPDATE', 3);
define('DATA_INTENT_UPDATE_MULTI', 4);
define('DATA_INTENT_DELETE', 5);
define('DATA_INTENT_DELETE_MULTI', 6);
define('DATA_INTENT_FETCH', 7);
define('DATA_INTENT_FETCH_MULTI', 8);

#-> Object state for value objects.
define('OBJECT_STATE_NULL', 0);
define('OBJECT_STATE_PRE_PROCESSING', 1);
define('OBJECT_STATE_PROCESSING', 2);
define('OBJECT_STATE_POST_PROCESSING', 3);

#-> Template content types
define('TEMPLATE_CONTENT_TYPE_TEXT', 1);
define('TEMPLATE_CONTENT_TYPE_HTML', 2);

#-> Notification types
define('NOTIFICATION_TYPE_SMS', 1);
define('NOTIFICATION_TYPE_EMAIL', 2);
define('NOTIFICATION_TYPE_ALL', 3);

#-> Paging default
define('PAGING_DEFAULT_NUM_RECORDS', 10);

#-> Time Intervals
define('TIME_INTERVAL_NONE', 0);
define('TIME_INTERVAL_SECOND', 1);
define('TIME_INTERVAL_MINUTE', 2);
define('TIME_INTERVAL_HOUR', 3);
define('TIME_INTERVAL_DAY', 4);
define('TIME_INTERVAL_WEEK', 5);
define('TIME_INTERVAL_MONTH', 6);
define('TIME_INTERVAL_YEAR', 7);

#-> Time modifier flags
define('TIME_FLAG_NONE', 0);
define('TIME_FLAG_FIRST_DAY_OF_MONTH', 1);
define('TIME_FLAG_LAST_DAY_OF_MONTH', 2);

#-> Billing stuff
define('BILLING_SUSPENSION_DAYS', 7);

#-> Payment Methods
define('PAYMENT_METHOD_PAYPAL', 'Paypal');
define('PAYMENT_METHOD_CREDIT_CARD', 'CreditCard');

#-> Payment Status
define('PAYMENT_STATUS_PEDNING', 			1);
define('PAYMENT_STATUS_GATEWAY_PENDING', 	2);
define('PAYMENT_STATUS_GATEWAY_HELD', 		4);
define('PAYMENT_STATUS_GATEWAY_FAILED', 	8);
define('PAYMENT_STATUS_DENIED', 			16);
define('PAYMENT_STATUS_CANCELLED', 			32);
define('PAYMENT_STATUS_SUCCESS', 			64);
define('PAYMENT_STATUS_REVERSED', 			128);
define('PAYMENT_STATUS_CHARGEBACK', 		256);



class Struct_Config
{
	
	/**
	 * @var string
	 */
	protected $_namespace = '';
	
	/**
	 * @var array
	 */
	static protected $_data = array();
	
	
	/**
	 * Static function for easy access to file.
	 */
	static public function loadConstants() {}

	/**
	 * Constructor
	 * @param  string $namespace
	 * @return Struct_Config
	 */
	public function __construct($namespace = APPLICATION)
	{
		$this->_namespace = $namespace;
		self::$_data[$namespace] = array();
	}

	/**
	 * Retrieve a config parameter.
	 * @param  multi $key
	 * @return multi
	 */
	public function __get($key)
	{
		return isset(self::$_data[$namespace][$key])
			? self::$_data[$this->_namespace][$key]
			: null;
	}

	/**
	 * Set a config parameter.
	 * @param  multi $key
	 * @param  multi $value
	 * @return Struct_Config
	 */
	public function __set($key, $value)
	{
		self::$_data[$this->_namespace][$key] = $value;
		return $this;
	}
	
	
}
