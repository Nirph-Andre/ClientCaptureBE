<?php



class Example extends Struct_Abstract_XmlRpcService
{
	
	/**
	 * Example xml-rpc method.
	 * @param string $authToken
	 * @return struct
	 */
	public function getVehicleListing($authToken)
	{
		try
		{
			$result = array('Greeting' => 'Hello world!');
			
			#-> Fin.
			return $result;
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog('EXCEPTION', "$e");
			throw new Example_Exception('Oops, something went wrong.');
		}
	}
	
	
}


class Example_Exception extends Zend_Exception {}
