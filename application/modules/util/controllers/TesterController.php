<?php




class Util_TesterController extends Struct_Abstract_Controller
{
	
	
	protected $_defaultObjectName = 'Object_Admin';
	
	

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		#-> Try something new today ;)
		$client = new Zend_XmlRpc_Client('http://bid4cars.local/api/xml-rpc');
		var_dump($client->call('retail.getVehicleListing', array(
				'fgo09t083rht2tmpwgj45jkf234rt2it4j034r9q'
				)));
		exit(0);
	}


}

