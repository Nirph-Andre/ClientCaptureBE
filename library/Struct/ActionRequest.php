<?php


/**
 * Provides structured request mechanism for objects.
 * @author andre.fourie
 */
class Struct_ActionRequest
{
	
	/**
	 * Action being requested.
	 * @var string
	 */
	public $action = array();
	
	/**
	 * Options to pass on to object.
	 * @var array
	 */
	public $options = array();
	
	/**
	 * Data to pass on to object.
	 * @var array
	 */
	public $data = array();
	
	
	/**
	 * Constructor
	 * @param  string $request
	 * @param  array $data
	 * @param  array $options
	 * @return Struct_ActionRequest
	 */
	public function __construct($request, array $data = array(), array $options = array())
	{
		$this->action = $request;
		$this->data = $data;
		$this->options = $options;
		$this->data = $data;
	}
	
	
}

