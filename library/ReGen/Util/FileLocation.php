<?php

/**
 * Utility to establish correct path and name for generated files.
 * 
 * @author andre.fourie
 */
class ReGen_Util_FileLocation
{
	
	static protected $projectLocation 	= null;
	static protected $application 		= null;
	static protected $entity 			= null;
	static protected $applicationBase	= null;
	static protected $moduleBase		= null;
	static protected $libraryBase		= null;
	
	static protected function getContext()
	{
		self::$projectLocation = ReGen_Registry::getContext('projectLocation');
		self::$application = ReGen_Registry::getContext('applicationName');
		self::$entity = ReGen_Registry::getContext('entityName');
		self::$applicationBase = self::$projectLocation . DIRECTORY_SEPARATOR . self::$application;
		self::$moduleBase = self::$applicationBase . LOCATION_MODULE . DIRECTORY_SEPARATOR . self::$entity;
		self::$libraryBase = self::$projectLocation . LOCATION_LIBRARY;
		if (!file_exists(self::$moduleBase))
		{
			mkdir(self::$moduleBase, 0777, true);
		}
	}
	
	static public function getComponentLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$applicationBase . LOCATION_COMPONENT 
			. DIRECTORY_SEPARATOR . $name . '.php';
	}
	
	static public function getConfigLocation()
	{
		self::getContext();
		return self::$applicationBase . LOCATION_CONFIG 
			. DIRECTORY_SEPARATOR . 'application.ini';
	}
	
	static public function getApplicationContextLocation()
	{
		self::getContext();
		return self::$applicationBase 
			. DIRECTORY_SEPARATOR . 'Context.php';
	}
	
	static public function getEntityContextLocation()
	{
		self::getContext();
		return self::$moduleBase
			. DIRECTORY_SEPARATOR . 'Context.php';
	}
	
	static public function getControllerLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$moduleBase . LOCATION_CONTROLLER
			. DIRECTORY_SEPARATOR . $name . 'Controller.php';
	}
	
	static public function getFormLocation($name)
	{
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$name = $filter->filter($name);
		self::getContext();
		return self::$moduleBase . LOCATION_FORM 
			. DIRECTORY_SEPARATOR . $name . '.php';
	}
	
	static public function getLayoutLocation($name)
	{
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$name = $filter->filter($name);
		self::getContext();
		return self::$applicationBase . LOCATION_LAYOUT . DIRECTORY_SEPARATOR 
			. $name . '.phtml';
	}
	
	static public function getTableModelLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$libraryBase . LOCATION_MODEL_TABLE . DIRECTORY_SEPARATOR 
			. $name . '.php';
	}
	
	static public function getViewModelLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$libraryBase . LOCATION_MODEL_VIEW . DIRECTORY_SEPARATOR 
			. $name . '.php';
	}
	
	static public function getObjectLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$libraryBase . LOCATION_OBJECT . DIRECTORY_SEPARATOR 
			. $name . '.php';
	}
	
	static public function getObjectInterfaceLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$moduleBase . LOCATION_OBJECT_INTERFACE . DIRECTORY_SEPARATOR 
			. $name . '.php';
	}
	
	static public function getPluginLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$libraryBase . LOCATION_PLUGIN . DIRECTORY_SEPARATOR 
			. $name . '.php';
	}
	
	static public function getServiceLocation($theme, $name)
	{
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$theme = $filter->filter($theme);
		$name = $filter->filter($name);
		self::getContext();
		return self::$moduleBase . LOCATION_SERVICE . DIRECTORY_SEPARATOR 
			. $theme . DIRECTORY_SEPARATOR
			. $name . '.php';
	}

	static public function getViewLocation($theme, $name)
	{
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$theme = strtolower($filter->filter($theme));
		$name = strtolower($filter->filter($name));
		$path = self::$moduleBase . LOCATION_VIEW . DIRECTORY_SEPARATOR
			. 'scripts' . DIRECTORY_SEPARATOR
			. $theme;
		if (!file_exists($path))
		{
			mkdir($path, 0777, true);
		}
		self::getContext();
		return $path . DIRECTORY_SEPARATOR
			. $name . '.phtml';
	}
	
	static public function getJsLocation($name)
	{
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$name = strtolower($filter->filter($name));
		self::getContext();
		return self::$projectLocation . LOCATION_JS . DIRECTORY_SEPARATOR 
			. $name . '.js';
	}
	
	static public function getAmfInterfaceLocation($name)
	{
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$name = $filter->filter($name);
		self::getContext();
		return self::$applicationBase . LOCATION_AMF . DIRECTORY_SEPARATOR
			. $name . '.php';
	}
	
	
}
