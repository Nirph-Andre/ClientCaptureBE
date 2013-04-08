<?php


#-> Basic configs.
define('APPLICATION', 'QuickAssetCapture');

#-> API configs.
define('SMS_USERNAME', '');
define('SMS_PASSWORD', '');
define('SMS_API_ID',   '');


#-> Developer configs.
define('DEV_TEST_EMAIL_ADDRESS', 'andre@nirph.com');
define('DEV_TEST_MOBILE_ADDRESS', '0825732810');
if ('Windows NT' == php_uname("s"))
{
	$isDev = true;
}

#-> Switching configs.
switch (getenv('APPLICATION_ENV'))
{
	case 'development':
		$isDev = true;
		$mailHost = 'matula.local';
		$appHost  = 'matula.local';
		$apeHost = '127.0.0.1:6969';
		break;
	case 'staging':
		$isDev = true;
		$mailHost = 'nirphrdp.com';
		$appHost  = 'qac.nirphrdp.com';
		$apeHost  = '127.0.0.1:6969';
		break;
	default:
		$isDev = false;
		$mailHost = 'mentum.in';
		$appHost  = 'qac.mentum.in';
		$apeHost  = '127.0.0.1:6969';
		break;
}
define('IS_DEV_ENV', $isDev);
define('APP_HOST',   $appHost);
define('MAIL_HOST',  $mailHost);

#-> APE config.
define('APE_HOST', $apeHost);
define('APE_KEY',  'ap3dP2ssk3Y');

#-> XML RPC config.
define('XML_RPC_MAX_REQUESTS', 100);

#-> Default Timezone.
date_default_timezone_set('Africa/Johannesburg');



/**
 * Application Bootstrap
 * @author andre.fourie
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected function _initResourceLoader()
	{
		// This sorts out our custom resource folders and the autoloading for them :)
		$this->_resourceLoader->addResourceType('form', 'forms', 'Form_');
		$this->_resourceLoader->addResourceType('plugin', 'plugins', 'Plugin_');
		
		// Load library constants
		Struct_Config::loadConstants();
		ReGen_Config::loadConstants();
	}
	
	protected function _initPlugins()
	{
		$this->bootstrap('frontController');
		$frontController = $this->getResource('frontController');
		$frontController->registerPlugin(new Application_Plugin_ApplicationLayout());
	}
	
	protected function _initView()
	{
		$view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('Solum');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		return $view;
	}
	
}

