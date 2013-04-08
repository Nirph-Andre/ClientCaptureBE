<?php


/**
 * Global Event handling functionality.
 * @author andre.fourie
 */
class Struct_Event
{
	
	/**
	 * Collection of business rule handlers.
	 * @var array
	 */
	static private $_agents = array();
	/**
	 * Last denial message retrieved from agent.
	 * @var string
	 */
	static private $_lastMessage = null;
	
	
	/* ---------------------------------------------------------------------- *\
	 *	Public Interface
	\* ---------------------------------------------------------------------- */
	/**
	 * Trigger specific system event.
	 * @param  string $event
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return boolean|unknown|NULL
	 */
	static public function trigger($event, Struct_Abstract_DataAccess $originator)
	{
		self::$_lastMessage = null;
		return self::invokeListeners($event, $originator, 'Trigger');
	}
	
	/**
	 * Check allowance of specific system event, typically against permissions agent.
	 * @param  string $event
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return boolean|unknown|NULL
	 */
	static public function allow($event, Struct_Abstract_DataAccess $originator)
	{
		self::$_lastMessage = null;
		return self::invokeListeners($event, $originator, 'Allow');
	}
	
	/**
	 * Retrieve last disallow/error message stored.
	 * @return string
	 */
	static public function getMessage()
	{
		return self::$_lastMessage;
	}
	
	
	/* ---------------------------------------------------------------------- *\
	 *	Internal Utilities
	\* ---------------------------------------------------------------------- */
	/**
	 * Find and invoke all listeners for specific event.
	 * @param  string $domainEvent
	 * @param  Struct_Abstract_DataAccess $originator
	 * @paran  string $eventType
	 * @return boolean|unknown|NULL
	 */
	static private function invokeListeners($domainEvent, Struct_Abstract_DataAccess $originator, $eventType)
	{
		list($category, $event) = explode('.', $domainEvent);
		try
		{
			$ini = new Zend_Config_Ini(APPLICATION_PATH . '/configs/agent.ini', $category);
			$config = $ini->toArray();
			if (!isset($config['Listen'][$event]))
			{
				return true;
			}
			$defaultOptions = $config['Options'];
			$defaultRule = isset($config['Rule'])
				? $config['Rule']
				: null;
			$defaultParams = isset($config['Params'])
				? $config['Params']
				: array();
			$listeners = array();
			foreach ($config['Listen'][$event] as $id => $listener)
			{
				is_array($listener)
					|| $listener = array();
				isset($listener['Rule'])
					|| $listener['Rule'] = $defaultRule;
				isset($listener['Params'])
					|| $listener['Params'] = $defaultParams;
				$listener['Options'] = isset($listener['Options'])
					? array_merge($defaultOptions, $listener['Options'])
					: $defaultOptions;
				$listener['Options']['EventType'] = $eventType;
				$response = self::invokeListener($domainEvent, $listener, $originator);
				if ('Allow' == $eventType && !is_null($response) && !$response)
				{
					return $response;
				}
			}
			return true;
		}
		catch (Exception $e)
		{
			//Before using this debug check for duplicate sections in agent.ini
			//Struct_Debug::errorLog($domainEvent, "$e");
			return true;
		}
	}
	
	/**
	 * Invoke specific event listener to executer business rule.
	 * @param  string $domainEvent
	 * @param  array $listener
	 * @param  Struct_Abstract_DataAccess $originator
	 * @return boolean|unknown|NULL
	 */
	static private function invokeListener($domainEvent, array $listener, Struct_Abstract_DataAccess $originator)
	{
		$agent     = self::invokeAgent($listener['Options']['Agent']);
		$eventType = $listener['Options']['EventType'];
		$rule      = $listener['Rule'];
		$params    = $listener['Params'];
		try
		{
			$response  = $agent->$rule($domainEvent, $originator, $params);
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog(__CLASS__, "$e");
			self::$_lastMessage = 'Server could not process the request.';
			return false;
		}
		$msg = $agent->getLastMessage();
		if (!is_null($msg))
		{
			self::$_lastMessage = $msg;
		}
		return ('Trigger' == $eventType)
			? null
			: $response;
	}
	
	/**
	 * Instantiate agent class and return.
	 * @param  string $agent
	 * @return resource
	 */
	static private function invokeAgent($agent)
	{
		isset(self::$_agents[$agent])
			|| self::$_agents[$agent] = new $agent();
		return self::$_agents[$agent];
	}
	
	
}

