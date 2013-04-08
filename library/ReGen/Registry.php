<?php


class ReGen_Registry extends Zend_Registry
{
	static protected $contextSession = null;
	
	static public function get($index) {
		if ('Util.' == substr($index, 0, 5)) {
			if (!Zend_Registry::isRegistered($index)) {
				list($namespace, $utility) = explode('.', $index);
				$className = 'ReGen_Util_' . ucfirst($utility);
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
	
	static public function setContext($type, $value)
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		self::$contextSession->$type = $value;
	}
	
	static public function getContext($type)
	{
		!is_null(self::$contextSession)
			|| self::$contextSession = new Zend_Session_Namespace(NAMESPACE_CONTEXT);
		return isset(self::$contextSession->$type)
			? self::$contextSession->$type
			: null;
	}
	
}