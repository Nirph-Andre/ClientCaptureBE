<?php


/**
 * For ease of table data handling.
 * @author andre.fourie
 */
abstract class Struct_Abstract_DataAccess
{
	
	/**
	 * Utility to filter names for code use.
	 * @var Zend_Filter_Word_UnderscoreToCamelCase
	 */
	static protected $_underscoreToCamelCase = null;
	
	/**
	 * Current data intent.
	 * @var unknown_type
	 */
	private $_dataIntent = null;
	/**
	 * State of the value data.
	 * @var integer
	 */
	private $_dataState = null;
	/**
	 * Current state of the value object.
	 * @var unknown_type
	 */
	private $_objectState = null;
	
	/**
	 * Request object.
	 * @var Struct_ActionRequest
	 */
	protected $_request = null;
	/**
	 * Value data.
	 * @var array
	 */
	protected $_data = null;
	/**
	 * Taag value data.
	 * @var array
	 */
	protected $_tagData = null;
	/**
	 * Multiple data item value data.
	 * @var array
	 */
	protected $_dataMulti = null;
	/**
	 * Changed values after processing.
	 * @var array
	 */
	protected $_dataChanges = null;
	
	/**
	 * Switch to enable html encoding of return data.
	 * @var boolean
	 */
	protected $htmlEncodeReturnData = false;
	/**
	 * Table this value object owns and may directly modify.
	 * @var string
	 */
	protected $_table = null;
	/**
	 * Validation meta-data.
	 * @var array
	 */
	protected $_validation = null;
	/**
	 * Validation error messages.
	 * @var array
	 */
	protected $_validationMessages = null;
	/**
	 * Validation error messages added by agent.
	 * @var array
	 */
	protected $_agentValidationMessages = null;
	/**
	 * Namespace used for raising events.
	 * @var string
	 */
	protected $_eventNamespace = null;
	/**
	 * Extra join chain links for extended data retrievals.
	 * @var array
	 */
	protected $_chain = array();
	/**
	 * Show/remove password fields from results.
	 * @var boolean
	 */
	protected $_showPassFields = false;
	/**
	 * Cater for temporary custom joins, used for extra filtering capability.
	 * @var array
	 */
	protected $_tempJoin = null;
	/**
	 * Cater for temporary custom joins, used for extra data linkage.
	 * @var array
	 */
	protected $_tempJoinAfter = null;
	/**
	 * To index the records by primary key.
	 * @var boolean
	 */
	protected $_index = false;
	/**
	 * To stack or not stack the data.
	 * @var boolean
	 */
	protected $_flatten = true;
	/**
	 * Field meta.
	 * @var array
	 */
	protected $_meta = null;
	
	
	
	/* ---------------------------------------------------------------------- *\
		Specifically cater for Response Agent Plugins.
		Generally provides intent, state and value transparency.
	\* ---------------------------------------------------------------------- */
	/**
	 * Get current object state
	 * @return integer
	 */
	public function getObjectState()
	{
		return $this->_objectState;
	}
	
	/**
	 * Get current data processing intent.
	 * @return integer
	 */
	public function getDataIntent()
	{
		return $this->_dataIntent;
	}
	
	/**
	 * Get current data state.
	 * @return integer
	 */
	public function getDataState()
	{
		return $this->_dataState;
	}
	
	/**
	 * Retrieve request specifics that the object is currently processing.
	 * @return Struct_ActionRequest
	 */
	public function getRequest()
	{
		return $this->_request;
	}
	
	/**
	 * Retrieve only the input data from the action request.
	 * @return unknown
	 */
	public function getInputData()
	{
		return $this->_request->data;
	}
	
	/**
	 * Retrieve current value data.
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}
	
	/**
	 * Override current value data.
	 * @param string $value
	 * @param unknown $value
	 * @return Struct_Abstract_DataAccess
	 */
	public function overrideData($field, $value)
	{
		$this->_data[$field] = $value;
		return $this;
	}
	
	/**
	 * Override current tag value data.
	 * @param array $data
	 * @return Struct_Abstract_DataAccess
	 */
	public function overrideTagData(array $data)
	{
		$this->_tagData = $data;
		return $this;
	}
	
	/**
	 * Retrieve current value data for multi-action.
	 * @return array
	 */
	public function getDataCollection()
	{
		return $this->_dataMulti;
	}
	
	/**
	 * Overwrite current value data for multi-action.
	 * @param array $data
	 * @return Struct_Abstract_DataAccess
	 */
	public function setDataCollection(array $data)
	{
		$this->_dataMulti = $data;
		return $this;
	}
	
	/**
	 * Retrieve data that is to change on the persistence layer.
	 * @return array
	 */
	public function getDataChanges()
	{
		return $this->_dataChanges;
	}
	
	/**
	 * Override data that is to change on the persistence layer.
	 * @param string $value
	 * @param unknown $value
	 * @return Struct_Abstract_DataAccess
	 */
	public function overrideDataChange($field, $value)
	{
		$this->_dataChanges[$field] = $value;
		return $this;
	}
	
	/**
	 * Remove field from data changes.
	 * @param string $value
	 * @return Struct_Abstract_DataAccess
	 */
	public function removeDataChange($field)
	{
		unset($this->_dataChanges[$field]);
		return $this;
	}
	
	/**
	 * Retrieve field validation meta-data.
	 * @return array
	 */
	public function getValidationMeta()
	{
		return $this->_validation;
	}
	
	/**
	 * Override field validation specifics.
	 * @param string $field
	 * @param boolean $required
	 * @param array $validators
	 * @return Struct_Abstract_DataAccess
	 */
	public function overrideFieldValidation($field, $required, array $validators)
	{
		$this->_validation[$field] = array(
            'required' => $required,
            'validators' => $validators
            );
		return $this;
	}
	
	/**
	 * Allow agent to set additional validation messages.
	 * Adding messages automatically causes a validation failure.
	 * Only valid if set on .Creating and .Updating events.
	 * @param string $field
	 * @param array $messages
	 * @return Struct_Abstract_DataAccess
	 */
	public function addValidationError($field, array $messages)
	{
		!is_null($this->_agentValidationMessages)
			&& $this->_agentValidationMessages[$field] = $messages;
		return $this;
	}
	
	
	
	/* ---------------------------------------------------------------------- *\
	 *	Standard Interface
	\* ---------------------------------------------------------------------- */
	/**
	 * Process standard format request.
	 * @param Struct_ActionRequest $request
	 */
	public function process(Struct_ActionRequest $request)
	{
		#-> Short-circuit safety check.
		if ( (!isset($request->data['id'])
				|| !$request->data['id']
				|| !is_numeric($request->data['id']))
			&& ('Find' == $request->action
				|| 'Update' == $request->action
				|| 'Delete' == $request->action))
		{
			return Struct_ActionFeedback::error(
					__CLASS__ . ': Data.' . $request->action . ' Requested without id',
					'Server could not process data request.'
					);
		}
		
		#-> Preperations.
		$this->_request = $request;
		$this->_objectState  = OBJECT_STATE_PRE_PROCESSING;
		$this->_data         = $request->data;
		$this->_dataChanges  = array();
		$this->_tagData = array();
		foreach ($this->_data as $field => $value)
		{
			if (is_array($value) || is_object($value)) // tag_*
			{
				$this->_tagData[substr($field, 4, strlen($field) - 4)] = $value;
			}
		}
		
		#-> Processing.
		switch ($request->action)
		{
			case 'Create':
				$this->_dataChanges = array();
				$fields = $this->_getTable()->getFieldNames();
				foreach ($fields as $field => $fieldName)
				{
					isset($this->_data[$field])
						&& $this->_dataChanges[$field] = $this->_data[$field];
				}
				$this->_agentValidationMessages = array();
				$this->_dataState   = DATA_STATE_DIRTY;
				$this->_dataIntent  = DATA_INTENT_CREATE;
				if (!Struct_Event::allow("$this->_eventNamespace.Creating", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Create Denied',
							Struct_Event::getMessage()
							);
				}
				if (!$this->validateData($this->_dataChanges))
				{
					Struct_Debug::errorLog(
							__CLASS__ . ': Data.Create Validation Errors > ' . $this->_table,
							$this->_validationMessages
							);
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Create Validation Errors',
							'Request not completed due to data validation errors.',
							array('Validation' => $this->_validationMessages)
					);
				}
				
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$response = $this->save(null, array(), $this->_dataChanges, $this->_tagData);
				if ($response->ok())
				{
					$this->_data['id'] = $response->data['id'];
					$this->_dataChanges['id'] = $response->data['id'];
				}
				else
				{
					return $response;
				}
				
				#-> Finalization.
				$this->_agentValidationMessages = null;
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.Created", $this);
				$msg = Struct_Event::getMessage();
				return Struct_ActionFeedback::successWithData(
						$this->_dataChanges,
						array('Message' => $msg)
						);
				break;
				
			case 'Update':
				$this->_agentValidationMessages = array();
				$response = $this->view($this->_data['id']);
				if (!$response->ok())
				{
					return $response;
				}
				foreach ($response->data as $field => $value)
				{
					isset($this->_data[$field])
						&& $this->_data[$field] != $value
						&& $this->_dataChanges[$field] = $this->_data[$field];
				}
				$this->_data        = $response->data;
				$this->_dataState   = DATA_STATE_DIRTY;
				$this->_dataIntent  = DATA_INTENT_UPDATE;
				if (!Struct_Event::allow("$this->_eventNamespace.Updating", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Update Denied',
							Struct_Event::getMessage()
							);
				}
				if (empty($this->_dataChanges))
				{
					return Struct_ActionFeedback::successWithData(
							$this->_data,
							array('Message' => '')
					);
				}
				if (!$this->validateData($this->_dataChanges, true))
				{
					Struct_Debug::errorLog(
							__CLASS__ . ': Data.Update Validation Errors > ' .  $this->_table,
							$this->_validationMessages
							);
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Update Validation Errors',
							'Request not completed due to data validation errors.',
							array('Validation' => $this->_validationMessages)
					);
				}
			
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$response = $this->save($this->_data['id'], array(), $this->_dataChanges, $this->_tagData);
				if ($response->ok())
				{
					$this->_data = array_merge($this->_data, $this->_dataChanges);
				}
				else
				{
					return $response;
				}
				
				#-> Finalization.
				$this->_agentValidationMessages = null;
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.Updated", $this);
				$msg = Struct_Event::getMessage();
				return Struct_ActionFeedback::successWithData(
						$this->_data,
						array('Message' => $msg)
				);
				break;
				
			case 'Delete':
				$response = $this->view($this->_data['id']);
				if (!$response->ok())
				{
					return $response;
				}
				$this->_data = $response->data;
				$this->_getTable()->checkTableFlag(TABLE_PSEUDO_DELETE)
					&& $this->_dataChanges['archived'] = 1;
				$this->_dataState   = DATA_STATE_DIRTY;
				$this->_dataIntent  = DATA_INTENT_DELETE;
				if (!Struct_Event::allow("$this->_eventNamespace.Deleting", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Delete Denied',
							Struct_Event::getMessage()
							);
				}
			
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$response = $this->remove($this->_data['id']);
				if ($response->ok())
				{
					$this->_data = array_merge($this->_data, $this->_dataChanges);
				}
				else
				{
					return $response;
				}
				
				#-> Finalization.
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.Deleted", $this);
				$msg = Struct_Event::getMessage();
				return Struct_ActionFeedback::successWithData(
						$this->_data,
						array('Message' => $msg)
				);
				break;
				
			case 'Find':
				if (!is_numeric($this->_data['id'])
					|| $this->_data['id'] < 1)
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.' . $request->action . ' Requested without id',
							'Server could not process data request.'
					);
				}
			case 'View':
				$this->_dataState   = DATA_STATE_NULL;
				$this->_dataIntent  = DATA_INTENT_FETCH;
				if (!Struct_Event::allow("$this->_eventNamespace.Fetching", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.Fetch Denied',
							Struct_Event::getMessage()
					);
				}
				
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$id = null;
				if (isset($this->_data['id']))
				{
					$id = is_numeric($this->_data['id'])
							&& $this->_data['id'] > 0
						? $this->_data['id']
						: null;
					unset($this->_data['id']);
				}
				$response = $this->view($id, $this->_request->options, true);
				if (!$response->ok())
				{
					return $response;
				}
				$this->_data = $response->data;
				
				#-> Finalization.
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.Fetched", $this);
				return Struct_ActionFeedback::successWithData($this->_data);
				break;
				
			case 'List':
				$this->_dataState   = DATA_STATE_NULL;
				$this->_dataIntent  = DATA_INTENT_FETCH_MULTI;
				if (!Struct_Event::allow("$this->_eventNamespace.FetchingMultiple", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.FetchMulti (list) Denied',
							Struct_Event::getMessage()
					);
				}
				
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$filter = isset($request->options['filter'])
					? $request->options['filter']
					: array();
				$order = isset($request->options['order'])
					? $request->options['order']
					: array();
				$response = $this->listAll($filter, $order);
				if (!$response->ok())
				{
					return $response;
				}
				$this->_dataMulti = $response->data;
				
				#-> Finalization.
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.FetchedMultiple", $this);
				return Struct_ActionFeedback::successWithData($this->_dataMulti);
				break;
				
			case 'Grid':
				$this->_dataState   = DATA_STATE_NULL;
				$this->_dataIntent  = DATA_INTENT_FETCH_MULTI;
				if (!Struct_Event::allow("$this->_eventNamespace.FetchingMultiple", $this))
				{
					return Struct_ActionFeedback::error(
							__CLASS__ . ': Data.FetchMulti (grid) Denied',
							Struct_Event::getMessage()
					);
				}
				
				#-> Process the request.
				$this->_objectState = OBJECT_STATE_PROCESSING;
				$filter = isset($request->options['filter'])
					? $request->options['filter']
					: array();
				$where = isset($request->options['where'])
					? $request->options['where']
					: array();
				$order = isset($request->options['order'])
					? $request->options['order']
					: array();
				$group = isset($request->options['group'])
					? $request->options['group']
					: array();
				$numRecordsPerPage = isset($request->options['numRecordsPerPage'])
					? $request->options['numRecordsPerPage']
					: null;
				$numPage = isset($request->options['numPage'])
					? $request->options['numPage']
					: null;
				$exclude = isset($request->options['exclude'])
					? $request->options['exclude']
					: array();
				$this->_chain = (isset($request->options['chain']))
					? $request->options['chain']
					: array();
				$response = $this->grid(
						$where,
						$order,
						$numRecordsPerPage,
						$numPage,
						$filter,
						$exclude,
						$group
						);
				if (!$response->ok())
				{
					return $response;
				}
				$this->_dataMulti = $response->data;
				$extra = array(
						'Search' => $response->result['Search'],
						'Paging' => $response->result['Paging'],
						'Order'  => $response->result['Order']
						);
				
				#-> Finalization.
				$this->_objectState = OBJECT_STATE_POST_PROCESSING;
				$this->_dataState   = DATA_STATE_CLEAN;
				$this->_dataIntent  = DATA_INTENT_NULL;
				Struct_Event::trigger("$this->_eventNamespace.FetchedMultiple", $this);
				return Struct_ActionFeedback::successWithData($this->_dataMulti, $extra);
				break;
		}
		
		#-> Invalid action requested.
		return Struct_ActionFeedback::error(
				'Invalid data action requested',
				'Server error, could not process request.',
				$request->action
				);
	}
	
	/**
	 * Encode all return data for html display.
	 * @param  boolean $encode
	 * @return Struct_Abstract_DataAccess
	 */
	public function htmlEncodeDataReturn($encode = true)
	{
		$this->htmlEncodeReturnData = $encode;
		return $this;
	}
	
	/**
	 * Retrieve database table name.
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_table;
	}
	
	/**
	 * Unique identification fields for this table (besides id field).
	 * @return array|boolean
	 */
	public function getUniqueIdentifier()
	{
		return $this->_uniqueIdentifier;
	}
	
	/**
	 * Pass back unstacked data results.
	 * @param boolean $flatten
	 * @return Struct_Abstract_DataAccess
	 */
	public function flatten($flatten = true)
	{
		$this->_flatten = $flatten;
		return $this;
	}
	
	/**
	 * Pass back results indexed by primary key.
	 * @param boolean $index
	 * @return Struct_Abstract_DataAccess
	 */
	public function index($index = true)
	{
		$this->_index = $index;
		return $this;
	}
	
	/**
	 * Add a join chain link for extended data retrievals.
	 * @param string $linkedTable
	 * @return Struct_Abstract_DataAccess
	 */
	public function setChainLink($linkedTable)
	{
		$this->_chain[] = $linkedTable;
		return $this;
	}
	
	/**
	 * Add a temporary join to the beginning of the next extended data query.
	 * @param  string $table
	 * @param  string $condition
	 * @param  array $fields
	 * @return Struct_Abstract_DataAccess
	 */
	public function addTempJoin($table, $condition, array $fields = array())
	{
		$this->_tempJoin[] = array(
				'table'     => $table,
				'condition' => $condition,
				'fields'    => $fields
				);
		return $this;
	}
	
	/**
	 * Add a temporary join to the end of the next extended data query.
	 * @param  string $table
	 * @param  string $condition
	 * @param  array $fields
	 * @return Struct_Abstract_DataAccess
	 */
	public function addTempJoinAfter($table, $condition, array $fields = array())
	{
		$this->_tempJoinAfter[] = array(
				'table'     => $table,
				'condition' => $condition,
				'fields'    => $fields
				);
		return $this;
	}
	
	/**
	 * Set to remove/not remove password fields from results.
	 * @param boolean $show
	 * @return Struct_Abstract_DataAccess
	 */
	public function showPasswordFields($show = true)
	{
		$this->_showPassFields = $show;
		return $this;
	}
	
	/**
	 * Save entry.
	 * @param  integer $id
	 * @param  array   $filters
	 * @param  array   $data
	 * @param  array   $tagData
	 * @return Struct_ActionFeedback
	 */
	public function save(
			$id = null, array $filters = array(), array $data, array $tagData = array()
			)
	{
		$where = array();
		if (!is_null($id))
		{
			$where["id = ?"] = $id;
		}
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		foreach ($data as $field => $value)
		{
			if (is_null($value) || 'null' === $value)
			{
				$data[$field] = new Zend_Db_Expr('NULL');
			}
		}
		return $this->_getTable()
			->updateSingle($where, $data, $tagData);
	}
	
	/**
	 * Update multiple entries.
	 * @param  array $filters
	 * @param  array $data
	 * @return Struct_ActionFeedback
	 */
	public function updateMultiple(array $filters, array $data)
	{
		$where = array();
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		return $this->_getTable()
			->updateMulti($where, $data);
	}
	
	/**
	 * Toggle bit-field value.
	 * @param  string  $field
	 * @param  integer $id
	 * @param  array   $filters
	 * @return Struct_ActionFeedback
	 */
	public function toggleBitField($field, $id = null, array $filters = array())
	{
		$record = $this->get__ItemName__($id, $filters);
		if (!$record->ok())
		{
			return $record;
		}
		$value = (1 == $record->data[$field])
			? 0
			: 1;
	
		$where = array();
		if (!is_null($id))
		{
			$where["id = ?"] = $id;
		}
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		return $this->_getTable()
			->updateSingle($where, array($field => $value));
	}
	
	/**
	 * Update field value.
	 * @param  string  $field
	 * @param  integer $id
	 * @param  array   $filters
	 * @param  multi   $value
	 * @return Struct_ActionFeedback
	 */
	public function setFieldValue($field, $id = null, array $filters = array(), $value)
	{
		$where = array();
		if (!is_null($id))
		{
			$where["id = ?"] = $id;
		}
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		return $this->_getTable()
			->updateSingle($where, array($field => $value));
	}
	
	/**
	 * Delete/Archive entry.
	 * @param  integer $id
	 * @param  array   $filters
	 * @return Struct_ActionFeedback
	 */
	public function remove($id = null, array $filters = array())
	{
		$where = array();
		if (!is_null($id))
		{
			$where["id = ?"] = $id;
		}
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		return $this->_getTable()
			->removeSingle($where);
	}
	
	/**
	 * Delete/Archive multiple entries.
	 * @param  array $filters
	 * @return Struct_ActionFeedback
	 */
	public function removeMultiple(array $filters)
	{
		$where = array();
		foreach ($filters as $field => $value)
		{
			$where["$field = ?"] = $value;
		}
		return $this->_getTable()
			->removeMulti($where);
	}
	
	/**
	 * Retrieve entry.
	 * @param  integer $id
	 * @param  array   $filters
	 * @param  boolean $withAssociatedData
	 * @return Struct_ActionFeedback
	 */
	public function view(
			$id = null, array $filters = array(), $withAssociatedData = false, $excludeData = array()
			)
	{
		$where = array();
		$where = $this->smartFilter($filters);
		if (!is_null($id))
		{
			$where[$this->_getTable()->getTableName() . ".id = ?"] = $id;
		}
		return $withAssociatedData
			? $this->_getTable()
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->addTempJoins($this->_tempJoin)
					->addTempJoinsAfter($this->_tempJoinAfter)
					->viewSingleExpanded($where, $excludeData, $this->_chain)
			: $this->_getTable()
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->viewSingle($where);
	}
	
	/**
	 * Retrieve full list of recursively stacked dependancies.
	 * @param array $chain
	 * @return Struct_ActionFeedback
	 */
	public function stackList(array $chain)
	{
		return $this->_getTable()
			->stackList($chain);
	}
	
	/**
	 * List entries by optional dynamic filters and order.
	 * @param  array   $filters
	 * @param  array   $orderSpecs
	 * @param  boolean $allColumns
	 * @return Struct_ActionFeedback
	 */
	public function listAll(array $filters = array(), array $orderSpecs = array(), $allColumns = false)
	{
		$where = !empty($filters)
			? $this->smartFilter($filters)
			: array();
		$order = !empty($orderSpecs)
			? $this->smartOrder($orderSpecs)
			: array();
		if (isset($this->_validation['archived']) && !isset($filters['archived']))
		{
			$where["archived = ?"] = 0;
		}
		return $this->_getTable()
			->htmlEncodeDataReturn($this->htmlEncodeReturnData)
			->listAll($where, $order, $allColumns);
	}
	
	/**
	 * Full grid data for entries by optional dynamic filters, order and paging.
	 * @param  array   $filters
	 * @param  array   $orderSpecs
	 * @param  integer $numRecordsPerPage
	 * @param  integer $numPage
	 * @param  array   $filters
	 * @param  array   $excludeTables
	 * @return Struct_ActionFeedback
	 */
	public function grid(
			array $where = array(), array $order = array(),
			$numRecordsPerPage = null, $numPage = null,
			array $filters = array(), $excludeTables = array(),
			array $group = array()
	)
	{
		/* if (isset($this->_validation['archived']) && !isset($where['archived']))
		{
			$tableName = $this->_getTable()->getTableName();
			$where['`' . $tableName . '`.`archived`'] = 0;
		} */
		$where = !empty($where)
			? $this->smartFilter($where)
			: array();
		$order = !empty($order)
			? $this->smartOrder($order)
			: array();
		return $this->_getTable()
			->showPasswordFields($this->_showPassFields)
			->htmlEncodeDataReturn($this->htmlEncodeReturnData)
			->addTempJoins($this->_tempJoin)
			->addTempJoinsAfter($this->_tempJoinAfter)
			->index($this->_index)
			->flatten($this->_flatten)
			->grid($where, $order, $numRecordsPerPage, $numPage,
					$filters, $excludeTables, $this->_chain, $group);
	}
	
	/**
	 * Count table entries relevant to filters supplied.
	 * @param array $where
	 * @return integer
	 */
	public function count(array $where = array(), $includeArchived = false)
	{
		$where = !empty($where)
			? $this->smartFilter($where)
			: array();
		return $this->_getTable()
			->count($where, $includeArchived);
	}
	
	
	
	/* ---------------------------------------------------------------------- *\
	 *	Internal Utilities
	\* ---------------------------------------------------------------------- */
	/**
	 * Retrieve table instance.
	 * @return Struct_Abstract_ModelTable
	 */
	protected function _getTable()
	{
		if (is_null(self::$_underscoreToCamelCase)) {
			self::$_underscoreToCamelCase = new Zend_Filter_Word_UnderscoreToCamelCase();
		}
		$className = 'Table_'
			. self::$_underscoreToCamelCase->filter(
					str_replace('__', '_', $this->_table)
			);
		return new $className;
	}
	
	/**
	 * Validate data received to ensure integrity.
	 * @param array $data
	 * @return boolean
	 */
	protected function validateData(array $data, $selective = false)
	{
		#-> Run through the fields.
		$valid = true;
		$this->_validationMessages = array();
		is_null($this->_meta)
			&& $this->_meta = $this->_getTable()->getFieldMeta();
		foreach ($this->_validation as $field => $requirements)
		{
			#-> Bypass fields not provided if we are in selective mode.
			if ($selective && !isset($data[$field]))
			{
				continue;
			}
			if (!$this->_validation[$field]['required'] && empty($data[$field]))
			{
				if ($this->_meta[$field]['DATA_TYPE'] == 'int')
				{
					unset($this->_dataChanges[$field]);
					unset($data[$field]);
				}
			}
			
			#-> Check for field required.
			if (isset($requirements['required'])
					&& $requirements['required']
					&& (!isset($data[$field])
							|| !strlen($data[$field])))
			{
				#-> Field Required and not provided or no data.
				$this->_validationMessages[$field]= array('Field is required.');
				$valid = false;
			}
			if ((!isset($requirements['required'])
					|| !$requirements['required'])
					&& (!isset($data[$field])
							|| !strlen($data[$field])))
			{
				#-> Empty string passed, not required, move on.
				continue;
			}
			
			#-> Check for js passing string false/true for tinyint fields.
			if ($this->_meta[$field]['DATA_TYPE'] == 'tinyint' && !is_numeric($this->_dataChanges[$field]))
			{
				$this->_dataChanges[$field] = ('false' == $this->_dataChanges[$field])
					? 0
					: 1;
				$data[$field] = $this->_dataChanges[$field];
			}
			
			#-> Custom field validators.
			if (isset($data[$field])
					&& isset($requirements['validators'])
					&& !empty($requirements['validators']))
			{
				foreach ($requirements['validators'] as $validator)
				{
					$className = 'Zend_Validate_' . $validator['type'];
					$oValidator = new $className($validator['params']);
					if (!$oValidator->isValid($data[$field]))
					{
						$this->_validationMessages[$field] = isset($this->_validationMessages[$field])
															&& is_array($this->_validationMessages[$field])
							? array_merge($this->_validationMessages[$field], $oValidator->getMessages())
							: $oValidator->getMessages();
						$valid = false;
					}
				}
			}
		}
		
		#-> Check for validation errors from agent.
		foreach ($this->_agentValidationMessages as $field => $messages)
		{
			$this->_validationMessages[$field] = isset($this->_validationMessages[$field])
				? $this->_validationMessages[$field] + $messages
				: $messages;
			$valid = false;
		}
		
		return $valid;
	}
	
	/**
	 * Convert human input filter to db format filter.
	 * @param  array $filter
	 * @return array
	 */
	protected function smartFilter(array $filter)
	{
		$where = array();
		foreach ($filter as $field => $valueStr)
		{
			$values = explode(' AND ', $valueStr);
			foreach ($values as $value)
			{
				$parts = explode('.', $field);
				if (count($parts) == 1)
				{
					$field = array_pop($parts);
				}
				else
				{
					$field = array_pop($parts);
					$table = array_pop($parts);
					$field = "$table.$field";
				}
				$search = array('>', '<', '=', '!=', '<>', '<=', '>=');
				if (false !== strpos($value, '%'))
				{
					$where["$field LIKE ?"] = $value;
				}
				elseif ('BETWEEN' == substr(strtoupper($value), 0, 7))
				{
				  	list($x, $y) = explode(',', trim(substr($value, 7, strlen($value) - 2)));
				  	if (is_numeric($x) && is_numeric($y))
				  	{
				  	  $where["$field BETWEEN $x AND $y"] = null;
				  	}
				}
				elseif ('NOT IN' == substr(strtoupper($value), 0, 6))
				{
					$where["$field NOT IN (?)"] = explode(',', trim(substr($value, 6, strlen($value) - 6)));
				}
				elseif ('=NULL' == strtoupper($value))
				{
					$where["$field IS NULL"] = null;
				}
				elseif ('!NULL' == strtoupper($value))
				{
					$where["$field IS NOT NULL"] = null;
				}
				elseif ('IN ' == substr(strtoupper($value), 0, 3))
				{
					$where["$field IN (?)"] = explode(',', trim(substr($value, 3, strlen($value) - 3)));
				}
				elseif ('!=' == substr($value, 0, 2))
				{
					$where["$field != ?"] = trim(substr($value, 2, strlen($value) - 2));
				}
				elseif ('<>' == substr($value, 0, 2))
				{
					$where["$field <> ?"] = trim(substr($value, 2, strlen($value) - 2));
				}
				elseif ('<=' == substr($value, 0, 2))
				{
					$where["$field <= ?"] = trim(substr($value, 2, strlen($value) - 2));
				}
				elseif ('>=' == substr($value, 0, 2))
				{
					$where["$field >= ?"] = trim(substr($value, 2, strlen($value) - 2));
				}
				elseif ('>' == substr($value, 0, 1))
				{
					$where["$field > ?"] = trim(substr($value, 1, strlen($value) - 1));
				}
				elseif ('<' == substr($value, 0, 1))
				{
					$where["$field < ?"] = trim(substr($value, 1, strlen($value) - 1));
				}
				elseif ('=' == substr($value, 0, 1))
				{
					$where["$field = ?"] = trim(substr($value, 1, strlen($value) - 1));
				}
				elseif ('!' == substr($value, 0, 1))
				{
					$where["$field != ?"] = trim(substr($value, 1, strlen($value) - 1));
				}
				else
				{
					$where["$field = ?"] = $value;
				}
			}
		}
		return $where;
	}
	
	/**
	 * Convert grid input order to db format order.
	 * @param  array $order
	 * @return array
	 */
	protected function smartOrder($order)
	{
		$orderBy = array();
		foreach ($order as $field => $direction)
		{
			$direction = 'DESC' == strtoupper($direction)
				? 'DESC'
				: 'ASC';
			$orderBy[] = "$field $direction";
		}
		return $orderBy;
	}
	
	
	
}
