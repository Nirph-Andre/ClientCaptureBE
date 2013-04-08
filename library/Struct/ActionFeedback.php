<?php


/**
 * Provides structured feedback mechanism for object methods.
 * @author andre.fourie
 */
class Struct_ActionFeedback
{
	
	/**
	 * Operational feedback storage.
	 * @var array
	 */
	public $result = array();
	
	/**
	 * Data feedback storage.
	 * @var array
	 */
	public $data   = array();
	
	
	/**
	 * Constructor
	 * @param  array $result
	 * @param  multi $data
	 * @return Struct_ActionFeedback
	 */
	public function __construct(array $result, $data = array())
	{
		$this->push($result, $data);
	}
	
	/**
	 * Store structured method feedback and provide feedback instance.
	 * @param  array $result
	 * @param  multi $data
	 * @return Struct_ActionFeedback
	 */
	static protected function _publish(array $result, $data = array())
	{
		$result = new Struct_ActionFeedback($result, $data);
		return $result;
	}
	
	/**
	 * Successful request.
	 * @param  array $extraResultData
	 * @return Struct_ActionFeedback
	 */
	static public function success(array $extraResultData = array())
	{
		$result = array();
		$result['Status'] = RESULT_SUCCESS;
		return self::_publish(array_merge($result, $extraResultData));
	}
	
	/**
	 * Successful request with data to return.
	 * @param  multi $data
	 * @param  array $extraResultData
	 * @return Struct_ActionFeedback
	 */
	static public function successWithData($data, array $extraResultData = array())
	{
		$result = array();
		$result['Status'] = RESULT_SUCCESS;
		return self::_publish(array_merge($result, $extraResultData), $data);
		
	}
	
	/**
	 * Failed request.
	 * @param  string 	 $error
	 * @param  string 	 $message
	 * @param  array  	 $extraResultData
	 * @param  Exception $exception
	 * @return Struct_ActionFeedback
	 */
	static public function error($error, $message, array $extraResultData = array(), $exception = null)
	{
		Struct_Debug::errorLog('ERROR', $error);
		if ($exception)
		{
			Struct_Debug::errorLog('EXCEPTION', $exception->getMessage());
			Struct_Debug::errorLog('TRACE', $exception->getTraceAsString());
			!empty($extraResultData)
				&& Struct_Debug::errorLog('DATA', $extraResultData);
		}
		$result = array();
		$result['Status']  = RESULT_ERROR;
		$result['Error']   = $error;
		$result['Message'] = $message;
		return self::_publish(array_merge($result, $extraResultData));
	}
	
	/**
	 * Request denied due to validation errors.
	 * @param  string $error
	 * @param  string $message
	 * @param  array $extraResultData
	 * @return Struct_ActionFeedback
	 */
	static public function validationError($error, $message, array $extraResultData = array())
	{
		$result = array();
		$result['Status'] = RESULT_VALIDATION;
		$result['Error']   = $error;
		$result['Message'] = $message;
		return self::_publish(array_merge($result, $extraResultData));
	}
	
	/**
	 * Set result and data.
	 * @param  array $result
	 * @param  multi $data
	 * @return Struct_ActionFeedback
	 */
	public function push(array $result, $data = array())
	{
		$this->result = $result;
		$this->data = $data;
		return $this;
	}
	
	/**
	 * Check if the call succeeded.
	 * @return boolean
	 */
	public function ok()
	{
		return (empty($this->result) || RESULT_SUCCESS != $this->result['Status'])
			? false
			: true;
	}
	
	/**
	 * Pack result and data into single array.
	 * @return array
	 */
	public function pack()
	{
		$result = $this->result;
		$result['Data'] = $this->data;
		return $result;
	}
	
	/**
	 * Pack result and data into single array, data stacked under namespace.
	 * @return array
	 */
	public function packForNs($nameSpace)
	{
		$result = $this->result;
		$result['Data'] = array($nameSpace => $this->data);
		return $result;
	}
	
}
