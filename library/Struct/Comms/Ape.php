<?php


/**
 * Facilitates chatting to APE server.
 * @author andre.fourie
 */
class Struct_Comms_Ape
{
	
	
	/**
	 * Speak to the APE server on specified channel.
	 * @param  string $channel
	 * @param  array $message
	 * @return array|boolean
	 */
	static public function speaketh($channel, array $message)
	{
		$cmd = Zend_Json::encode(array(array(
				'cmd' => 'inlinepush',
				'params' =>  array(
						'password'  => APE_KEY,
						'raw'       => 'postmsg',
						'channel'   => $channel,
						'data'      => array('message' => $message)
				)
		)));
		try
		{
			if (false && function_exists('curl_init'))
			{
				#-> Have curl, use it.
				$ch = curl_init('http://' . APE_HOST);
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
				$result = file_get_contents('http://' . APE_HOST, null, stream_context_create(array(
						'http' => array(
								'method'  => 'POST',
								'header'  => 'Content-Type: application/json' . "\r\n"
										   . 'Content-Length: ' . strlen($cmd) . "\r\n",
								'content' => $cmd,
						),
				)));
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

