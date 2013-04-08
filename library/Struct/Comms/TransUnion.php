<?php


/**
 * Facilitates chatting to TransUnion JSON api.
 * @author andre.fourie
 */
class Struct_Comms_TransUnion
{
	
	/**
	 * Production URL
	 * @var string
	 */
	static private $liveUrl = 'https://tuaba.transunion.co.za/100/TransUnionAuto.JSon.svc';
	
	/**
	 * Development/staging URL
	 * @var string
	 */
	static private $devUrl  = 'https://devtuaba.transunion.co.za/100/TransUnionAuto.JSon.svc';
	
	/**
	 * Development/staging credentials.
	 * @var unknown_type
	 */
	static private $devCredentials = array(
						'Username' => 'TuabaDev',
						'Password' => '2!2E2A736@'
						);
	
	
	/**
	 * Collect vehicle particulars from VIN number.
	 * @param  string $vin
	 * @param  integer $regYear
	 * @param  integer $mileage
	 * @param  integer $condition
	 * @return array
	 */
	static public function searchByVin($vin, $regYear, $mileage = null, $condition = null)
	{
		$params = array(
				'Credential' => array(),
				'VIN'        => $vin,
				'RegYear'    => $regYear
				);
		if (!is_null($mileage) && !is_null($condition)
				&& is_numeric($mileage) && is_numeric($condition))
		{
			$params['Mileage']   = $mileage;
			$params['Condition'] = $condition;
		}
		return self::_speaketh('SearchByVIN', $params);
	}
	
	/**
	 * Collect vehicle particulars from Registration number.
	 * @param  string  $regNo
	 * @param  integer $regYear
	 * @param  integer $mileage
	 * @param  integer $condition
	 * @return array
	 */
	static public function searchByRegNo($regNo, $regYear, $mileage = null, $condition = null)
	{
		$params = array(
				'Credential' => array(),
				'RegNo'      => $regNo,
				'RegYear'    => $regYear
		);
		if (!is_null($mileage) && !is_null($condition)
				&& is_numeric($mileage) && is_numeric($condition))
		{
			$params['Mileage']   = $mileage;
			$params['Condition'] = $condition;
		}
		return self::_speaketh('SearchByRegNo', $params);
	}
	
	/**
	 * Collect vehicle particulars from MM code.
	 * @param  string  $mmCode
	 * @param  integer $regYear
	 * @param  integer $mileage
	 * @param  integer $condition
	 * @return array
	 */
	static public function searchByMmCode($mmCode, $regYear, $mileage = null, $condition = null)
	{
		$params = array(
				'Credential' => array(),
				'MMCode'     => $mmCode,
				'RegYear'    => $regYear
		);
		if (!is_null($mileage) && !is_null($condition)
				&& is_numeric($mileage) && is_numeric($condition))
		{
			$params['Mileage']   = $mileage;
			$params['Condition'] = $condition;
		}
		return self::_speaketh('SearchByMMCode', $params);
	}
	
	/**
	 * Speak to the TransUnion server.
	 * @param  string $channel
	 * @param  array  $message
	 * @return array|boolean
	 */
	static private function _speaketh($method, array $params)
	{
		$production = ('development' != APPLICATION_ENV)
			? true
			: false;
		$production = true;
		$url = $production
			? self::$liveUrl
			: self::$devUrl;
		$credentials = $production
			? array(
						'Username' => TU_USERNAME,
						'Password' => TU_PASSWORD
						)
			: self::$devCredentials;
		
		$params['Credential'] = $credentials;
		$cmd = Zend_Json::encode($params);
		try
		{
			if (false && $production && function_exists('curl_init'))
			{
				#-> Have curl, use it.
				$ch = curl_init($url . '/' . $method);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $cmd);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($cmd))
				);
				$result = curl_exec($ch);
				curl_close($ch);
				return Zend_Json::decode($result);
			}
			else
			{
				#-> No curl, use the slower option.
				$result = file_get_contents($url . '/' . $method, null, stream_context_create(array(
						'http' => array(
								'method'  => 'POST',
								'header'  => 'Content-Type: application/json' . "\r\n"
										   . 'Content-Length: ' . strlen($cmd) . "\r\n",
								'content' => $cmd,
						),
				)));
 //Struct_Debug::errorLog('TU.url', $url . '/' . $method);
 //Struct_Debug::errorLog('TU.cmd', $cmd);
 //Struct_Debug::errorLog('TU.response', $result);
				return Zend_Json::decode($result);
			}
		}
		catch (Exception $e)
		{
			Struct_Debug::errorLog(__CLASS__, "$e");
			return false;
		}
	}
	
	
}

