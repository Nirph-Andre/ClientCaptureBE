<?php


/**
 * Generic db table handling.
 * @author andre.fourie
 */
abstract class Struct_Abstract_ModelTable extends Zend_Db_Table_Abstract
{


	/**
	 * Table for logging data changes.
	 * @var Table_NirphAuditLog
	 */
	static protected $auditTable = null;

	/**
	 * Field meta data.
	 * @var array
	 */
	protected $_metadata = null;

	/**
	 * Tables dependant on this one.
	 * @var array
	 */
	protected $_dependentTables = null;

	/**
	 * Data dependancy chain.
	 * @var array
	 */
	protected $dependancyChain = array();

	/**
	 * Table flags.
	 * @var array
	 */
	protected $tableFlags = null;

	/**
	 * Field used to flag entry as archived.
	 * @var string
	 */
	protected $archiveField = null;

	/**
	 * Field db-name to code-name mapping.
	 * @var array
	 */
	protected $fieldNames = null;

	/**
	 * Default values for new data entry.
	 * @var array
	 */
	protected $newRow = null;

	/**
	 * Label format for list/dropdown display.
	 * @var string
	 */
	protected $labelFormat = null;

	/**
	 * Label format for list/dropdown display when accessed as foreign table.
	 * @var string
	 */
	protected $labelFormatForeign = null;

	/**
	 * Switch to enable html encoding of return data.
	 * @var boolean
	 */
	protected $htmlEncodeReturnData = false;

	/**
	 * Data tags active on current data filter.
	 * @var array
	 */
	protected $activeDataTags = array();

	/**
	 * Map for extended fields pulled into query.
	 * @var array
	 */
	protected $buildMap = false;

	/**
	 * Recurse map for correct stacking of chained data.
	 * @var array
	 */
	protected $stackMap = false;

	/**
	 * Show/hide password fields in results.
	 * @var boolean
	 */
	protected $showPassFields = false;

	/**
	 * Cater for temporary custom joins, used for extra filtering capability.
	 * @var array
	 */
	protected $tempJoin = null;

	/**
	 * Cater for temporary custom joins, used for extra data linkage.
	 * @var array
	 */
	protected $tempJoinAfter = null;

	/**
	 * To stack or not stack the data.
	 * @var boolean
	 */
	protected $flatten = false;
	/**
	 * To index the records by primary key.
	 * @var boolean
	 */
	protected $index = false;



	/**
	 * Constructor.
	 *
	 * Supported params for $config are:
	 * - db              = user-supplied instance of database connector,
	 *                     or key name of registry instance.
	 * - name            = table name.
	 * - primary         = string or array of primary key(s).
	 * - rowClass        = row class name.
	 * - rowsetClass     = rowset class name.
	 * - referenceMap    = array structure to declare relationship
	 *                     to parent tables.
	 * - dependentTables = array of child tables.
	 * - metadataCache   = cache for information from adapter describeTable().
	 *
	 * @param  mixed $config Array of user-specified config options, or just the Db Adapter.
	 * @return Struct_Abstract_ModelTable
	 */
	public function __construct($config = array())
	{
		$config['referenceMap']  = $this->_referenceMap;
		parent::__construct($config);
		parent::getDefaultAdapter()->setFetchMode(Zend_Db::FETCH_ASSOC);
	}

	/**
	 * Retrieve table name.
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_name;
	}

	/**
	 * Retrieve list of fields for this table.
	 * @return array
	 */
	public function getFieldNames()
	{
		return $this->fieldNames;
	}

	/**
	 * Retrieve field meta data.
	 * @return array
	 */
	public function getFieldMeta()
	{
		return $this->_metadata;
	}

	/**
	 * Retrieve field meta data.
	 * @return array
	 */
	public function getReferenceMap()
	{
		return $this->_referenceMap;
	}

	/**
	 * Retrieve foreign label format.
	 * @return string
	 */
	public function getForeignLabel()
	{
		return $this->labelFormatForeign;
	}

	/**
	 * Encode all return data for html display.
	 * @param  boolean $encode
	 * @return Struct_Abstract_ModelTable
	 */
	public function htmlEncodeDataReturn($encode = true)
	{
		$this->htmlEncodeReturnData = $encode;
		return $this;
	}

	/**
	 * Set to remove/not remove password fields from results.
	 * @param boolean $show
	 * @return Struct_Abstract_DataAccess
	 */
	public function showPasswordFields($show = true)
	{
		$this->showPassFields = $show;
		return $this;
	}

	/**
	 * Retrieve active data flag filters.
	 * @return array
	 */
	public function retrieveFilters()
	{
		$this->activeDataTags = array();
		$this->_buildAutoFilters();
		$tags = $this->activeDataTags;
		$this->activeDataTags = array();
		return $tags;
	}

	/**
	 * Pass back unstacked data results.
	 * @param boolean $flatten
	 * @return Struct_Abstract_ModelTable
	 */
	public function flatten($flatten = true)
	{
		$this->flatten = $flatten;
		return $this;
	}

	/**
	 * Pass back results indexed by primary key.
	 * @param boolean $index
	 * @return Struct_Abstract_ModelTable
	 */
	public function index($index = true)
	{
		$this->index = $index;
		return $this;
	}

	/**
	 * Add a temporary join to the beginning of the next extended data query.
	 * @param  string $table
	 * @param  string $condition
	 * @param  array $fields
	 * @return Struct_Abstract_ModelTable
	 */
	public function addTempJoin($table, $condition, array $fields = array())
	{
		$this->tempJoin[] = array(
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
	 * @return Struct_Abstract_ModelTable
	 */
	public function addTempJoinAfter($table, $condition, array $fields = array())
	{
		$this->tempJoinAfter[] = array(
				'table'     => $table,
				'condition' => $condition,
				'fields'    => $fields
				);
		return $this;
	}

	/**
	 * Add temporary joins to the beginning of the next extended data query.
	 * @param  array:null $joins
	 * @return Struct_Abstract_ModelTable
	 */
	public function addTempJoins($joins)
	{
		$this->tempJoin = $joins;
		return $this;
	}

	/**
	 * Add temporary joins to the end of the next extended data query.
	 * @param  array:null $joins
	 * @return Struct_Abstract_ModelTable
	 */
	public function addTempJoinsAfter($joins)
	{
		$this->tempJoinAfter = $joins;
		return $this;
	}

	/**
	 * Check if flag is set on the field.
	 * @param  string  $field
	 * @param  integer $flag
	 * @return boolean
	 */
	public function checkFieldFlag($field, $flag)
	{
		return ($flag == ($this->_metadata[$field]['FLAGS'] & $flag));
	}

	/**
	 * Check if flag is set on the table.
	 * @param  integer $flag
	 * @return boolean
	 */
	public function checkTableFlag($flag)
	{
		return ($flag == ($this->tableFlags & $flag));
	}

	/**
	 * Create new table row entry without saving.
	 * @param  array $data
	 * @return array
	 */
	public function createRow(array $data = array())
	{
		$record = $this->newRow;
		foreach ($data as $field => $value)
		{
			if (isset($record[$field]))
			{
				$record[$field] = $value;
			}
		}
		return $this->_appendAutoData($record);
	}

	/**
	 * Save multilink data for single foreign table.
	 * @param  integer $id
	 * @param  string  $table
	 * @param  array   $linkData
	 * @return Struct_ActionFeedback
	 */
	public function saveLinks($id, $table, array $linkData)
	{
		#-> Safety check.
		if (!isset($this->_dependentTables[$table]))
		{
			return Struct_ActionFeedback::success();
		}

		#-> Establish foreign key and class.
		$contextIdField = $this->_name . '_id';
		$className = $this->_dependentTables[$table];
		$tableClass = new $className();
		$secondaryIdField = false;
		$thirdIdField = false;

		#-> Save link entries.
		foreach ($linkData as $entryData)
		{
			if (!is_array($entryData) || empty($entryData))
			{
				continue;
			}
			if (!$secondaryIdField)
			{
				foreach ($entryData as $field => $value)
				{
					if (strpos($field, '_id'))
					{
						if (!$secondaryIdField)
						{
							$secondaryIdField = $field;
						}
						else
						{
							$thirdIdField = $field;
							break;
						}
					}
				}
			}
			$entryData[$contextIdField] = $id;
			$where = array(
					"$contextIdField = ?" => $id,
					"$secondaryIdField = ?" => $entryData[$secondaryIdField]
			);
			if ($thirdIdField)
			{
				$where["$thirdIdField = ?"] = $entryData[$thirdIdField];
			}
			$result = $tableClass->updateSingle($where, $entryData);
			if (!$result->ok())
			{
				return $result;
			}
		}
		return Struct_ActionFeedback::success();
	}

	protected function cleanAuditData($logData)
	{
		if (isset($logData['video']))
		{
			unset($logData['video']);
		}
		if (isset($logData['photo']))
		{
			unset($logData['photo']);
		}
		if (isset($logData['thumbnail']))
		{
			unset($logData['thumbnail']);
		}
		if (isset($logData['document']))
		{
			unset($logData['document']);
		}
		return $logData;
	}

	/**
	 * Save item entry.
	 * linkData takes multiple foreign tables in format table_name => [tableEntries]
	 * @param  array  $where
	 * @param  array  $data
	 * @param  array  $tagData
	 * @return Struct_ActionFeedback
	 */
	public function updateSingle(array $where, array $data, array $linkData = array())
	{
		#-> Prepare field data.
		$record  = array();
		$logData = $data;
		foreach ($data as $field => $internal)
		{
			if (isset($this->fieldNames[$field]))
			{
				switch($this->_metadata[$field]['DATA_TYPE'])
				{
					case 'int':
					case 'mediumint':
					case 'tinyintint':
						( $this->_metadata[$field]['NULLABLE'] || is_numeric($data[$field]) )
							&& $record[$field] = $data[$field];
						break;
					case 'date':
					case 'datetime':
						if ($this->_metadata[$field]['NULLABLE'] || !empty($data[$field]))
						{
							$record[$field] = $data[$field];
						}
						break;
					default:
						( $this->_metadata[$field]['NULLABLE'] || !is_null($data[$field]) )
							&& $record[$field] = $data[$field];
						break;
				}
			}
		}
		$record = $this->_appendAutoData($record);

		if ( (!isset($record['id']) || null == $record['id']) && !empty($where) )
		{
			$current = $this->viewSingle($where);
			if ($current->data)
			{
				$record['id'] = $current->data['id'];
			}
		}

		if (!isset($record['id']) || null == $record['id'])
		{
			#-> Insert
			if ($this->checkTableFlag(TABLE_NO_INSERT)) {
				return Struct_ActionFeedback::error(
						'Insert not allowed on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA
				);
			}
			if (isset($record['id']))
			{
				unset($record['id']);
			}
			foreach ($this->fieldNames as $field => $internal)
			{
				if ($this->checkFieldFlag($field, FIELD_UPDATE_DATETIME)
						|| $this->checkFieldFlag($field, FIELD_UPDATE_DATE)
						|| $this->checkFieldFlag($field, FIELD_UPDATE_TIMESTAMP))
				{
					if (isset($record[$field]))
					{
						unset($record[$field]);
					}
				}

				if ($this->checkFieldFlag($field, FIELD_INSERT_DATETIME))
				{
					$record[$field] = date('Y-m-d H:i:s');
				}
				if ($this->checkFieldFlag($field, FIELD_INSERT_DATE))
				{
					$record[$field] = date('Y-m-d');
				}
				if ($this->checkFieldFlag($field, FIELD_INSERT_TIMESTAMP))
				{
					$record[$field] = time();
				}
			}
			try
			{
				$id = parent::insert($record);
				if (($id) && Struct_Registry::isAuthenticated())
				{
					$actor = Struct_Registry::getContext('Actor');
					$actor = empty($actor)
						? 'User'
						: $actor;
					'app_audit_log' == $this->_name
						|| $this->_getAuditTable()->save(null, array(), array(
							'customer_context' 	=> $actor,
							'customer_id' 	  	=> Struct_Registry::getAuthParam('id'),
							'action' 			      => 'Add',
							'table_name' 		    => $this->_name,
							'record_id' 		    => $id,
							'data_packet' 		  => serialize($this->cleanAuditData($logData))
					));
				}
				foreach ($linkData as $table => $tableData)
				{
					$result = $this->saveLinks($id, $table, $tableData);
					if (!$result->ok())
					{
						return $result;
					}
				}
				return Struct_ActionFeedback::successWithData(array('id' => $id));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for insert on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						$data, $e
				);
			}
		} else {
			$id = $record['id'];
			if ($this->checkTableFlag(TABLE_NO_UPDATE))
			{
				return Struct_ActionFeedback::error(
						'Update not allowed on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA
				);
			}
			foreach ($this->fieldNames as $field => $internal)
			{
				if ($this->checkFieldFlag($field, FIELD_INSERT_DATETIME)
						|| $this->checkFieldFlag($field, FIELD_INSERT_DATE)
						|| $this->checkFieldFlag($field, FIELD_INSERT_TIMESTAMP))
				{
					if (isset($record[$field]))
					{
						unset($record[$field]);
					}
				}

				if ($this->checkFieldFlag($field, FIELD_UPDATE_DATETIME))
				{
					$record[$field] = date('Y-m-d H:i:s');
				}
				if ($this->checkFieldFlag($field, FIELD_UPDATE_DATE))
				{
					$record[$field] = date('Y-m-d');
				}
				if ($this->checkFieldFlag($field, FIELD_UPDATE_TIMESTAMP))
				{
					$record[$field] = time();
				}
			}
			try
			{
Struct_Debug::errorLog('update date', $record);
				$affectedRows = parent::update($record, array('id = ?' => $id));
				if (($affectedRows) && Struct_Registry::isAuthenticated())
				{
					$actor = Struct_Registry::getContext('Actor');
					$actor = empty($actor)
						? 'User'
						: $actor;
					'app_audit_log' == $this->_name
						|| $this->_getAuditTable()->save(null, array(), array(
							'customer_context' => $actor,
							'customer_id' 	   => Struct_Registry::getAuthParam('id'),
							'action'           => 'Update',
							'table_name'       => $this->_name,
							'record_id'        => $id,
							'data_packet'      => serialize($this->cleanAuditData($logData))
					));
				}
				foreach ($linkData as $table => $tableData)
				{
					$result = $this->saveLinks($id, $table, $tableData);
					if (!$result->ok())
					{
						return $result;
					}
				}
				return Struct_ActionFeedback::successWithData(array('affectedRows' => $affectedRows));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for update on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						$data, $e
				);
			}
		}
	}

	/**
	 * Update multiple item entries.
	 * @param  array  $where
	 * @param  array  $data
	 * @return Struct_ActionFeedback
	 */
	public function updateMulti(array $where, array $data)
	{
		#-> Prepare field data.
		$record  = array();
		$logData = $data;
		foreach ($this->fieldNames as $field => $internal)
		{
			isset($data[$field])
				&& $record[$field] = $data[$field];
		}
		$record = $this->_appendAutoData($record);

		#-> Update entries.
		try
		{
			$table = new Zend_Db_Table($this->_name);
			$affectedRows = $table->update($data, $where);
			return Struct_ActionFeedback::success(array('affectedRows' => $affectedRows));
		}
		catch (Exception $e)
		{
			return Struct_ActionFeedback::error(
					'Exception for multi-update on table: ' . $this->_name,
					ERROR_SYSTEM_INTERNAL_DATA,
					array(), $e
			);
		}
	}

	/**
	 * Remove item entry.
	 * @param  array  $where
	 * @return Struct_ActionFeedback
	 */
	public function removeSingle(array $where)
	{
		#-> Do we have something to delete?
		$record = $this->viewSingle($where);
		if (!$record->data)
		{
			return Struct_ActionFeedback::success(array('affectedRows' => 0));
		}

		#-> Audit log changes.
		if (Struct_Registry::isAuthenticated())
		{
			$logData = $this->checkTableFlag(TABLE_PSEUDO_DELETE)
				? array($this->archiveField => 1)
				: array();
			$actor = Struct_Registry::getContext('Actor');
			$actor = empty($actor)
				? 'User'
				: $actor;
			'app_audit_log' == $this->_name
				|| $this->_getAuditTable()->save(null, array(), array(
					'customer_context' => $actor,
					'customer_id' 	   => Struct_Registry::getAuthParam('id'),
					'action'           => 'Delete',
					'table_name'       => $this->_name,
					'record_id'        => $record->data['id'],
					'data_packet'      => serialize($this->cleanAuditData($logData))
			));
		}

		#-> Delete entry.
		if ($this->checkTableFlag(TABLE_PSEUDO_DELETE))
		{
			#-> Have an archive field, use it.
			try
			{
				$where = $this->_appendArrayAutoFilter(array('id = ?' => $record->data['id']));
				$affectedRows = parent::update(
					array($this->archiveField => 1),
					$where
					);
				return Struct_ActionFeedback::success(array('affectedRows' => $affectedRows));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for pseudo-delete on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						array(), $e
				);
			}
		} else {
			#-> No archiving for this table.
			if ($this->checkTableFlag(TABLE_NO_DELETE))
			{
				return Struct_ActionFeedback::error(
						'Delete not allowed on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA
						);
			}
			try
			{
				$where = $this->_appendArrayAutoFilter(array('id = ?' => $record->data['id']));
				$affectedRows = parent::delete($where);
				return Struct_ActionFeedback::success(array('affectedRows' => $affectedRows));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for delete on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						array(), $e
				);
			}
		}
	}

	/**
	 * Remove multiple item entries.
	 * @param  array  $where
	 * @return Struct_ActionFeedback
	 */
	public function removeMulti(array $where)
	{
		#-> Delete entries.
		if ($this->checkTableFlag(TABLE_PSEUDO_DELETE))
		{
			#-> Have an archive field, use it.
			try
			{
				$where = $this->_appendArrayAutoFilter($where);
				$affectedRows = parent::update(array($this->archiveField => 1), $where);
				return Struct_ActionFeedback::success(array('affectedRows' => $affectedRows));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for pseudo-delete on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						array(), $e
				);
			}
		} else {
			#-> No archiving for this table.
			if ($this->checkTableFlag(TABLE_NO_DELETE))
			{
				return Struct_ActionFeedback::error(
						'Delete not allowed on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA
				);
			}
			try
			{
				$where = $this->_appendArrayAutoFilter($where);
				$affectedRows = parent::delete($where);
				return Struct_ActionFeedback::success(array('affectedRows' => $affectedRows));
			}
			catch (Exception $e)
			{
				return Struct_ActionFeedback::error(
						'Exception for delete on table: ' . $this->_name,
						ERROR_SYSTEM_INTERNAL_DATA,
						array(), $e
				);
			}
		}
	}

	/**
	 * Fetch one entry for a query.
	 * @param  array $where
	 * @param  array $order
	 * @return array
	 */
	public function fetchOne($where = null, $order = null)
	{
		$entries = $this->fetchAll($where, $order, 1, 0);
		return !empty($entries)
			? array_shift($entries)
			: array();
	}

	/**
	 * Fetch entries for a query.
	 * @param  array   $where
	 * @param  array   $order
	 * @param  integer $count
	 * @param  integer $offset
	 * @return array
	 */
	public function fetchAll($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->_flattenRecordSet(parent::fetchAll($where, $order, $count, $offset));
	}

	/**
	 * Retrieve item entry.
	 * @param  array   $where
	 * @paran  boolean $liveOnly
	 * @return Struct_ActionFeedback
	 */
	public function viewSingle(array $where, $liveOnly = true)
	{
		#-> Filtering.
		$where = $this->_appendArrayAutoFilter($where);
		if ($liveOnly && $this->checkTableFlag(TABLE_PSEUDO_DELETE))
		{
			$where[] = $this->_name . '.archived = 0';
		}
		return Struct_ActionFeedback::successWithData($this->fetchOne($where));
	}

	/**
	 * Retrieve item entry with all associated data.
	 * @param  array  $where
	 * @param  array  $excludeTables
	 * @param  array  $chain
	 * @paran  boolean $liveOnly
	 * @return Struct_ActionFeedback
	 */
	public function viewSingleExpanded(
			array $where, $excludeTables = array(), $chain = array(), $liveOnly = true
			)
	{
		#-> Get joined selector.
		$select = $this->_buildJoins($excludeTables, $chain, false);

		#-> Filtering.
		$where = $this->_appendArrayAutoFilter($where);
		foreach ($where as $spec => $value)
		{
			$select->where($spec, $value);
		}
		if ($liveOnly && $this->checkTableFlag(TABLE_PSEUDO_DELETE))
		{
			$select->where($this->_name . '.archived = 0');
		}

		#-> Limit to 1 entry and fetch data.
		$record = $select->limit(1, 0)
			->query()
			->fetch();
		$record = is_object($record) ? $record->toArray() : $record;
		if (!is_array($record))
		{
			return Struct_ActionFeedback::successWithData(
					array()
			);
		}
		$record = $this->_unpackRow($record);
		if ($this->htmlEncodeReturnData)
		{
			foreach ($record as $field => $value)
			{
				$record[$field] = htmlentities($value);
			}
		}

		#-> Flatten data and return to caller.
		return Struct_ActionFeedback::successWithData(
				$record
				);
	}

	/**
	 * Retrieve full list of recursively stacked dependancies.
	 * @param array $chain
	 * @return Struct_ActionFeedback
	 */
	public function stackList(array $chain)
	{
		$recordSet = $this->fetchAll(array('archived = ?' => 0));
		$dataset   = array();
		$subs      = array();
		$subPrep   = array();
		foreach ($this->_dependentTables as $table => $className)
		{
			if (!in_array($table, $chain))
			{
				continue;
			}
			$stackName           = $table . 's';
			$subs[$stackName]    = $className;
			$subPrep[$stackName] = array();
		}
		foreach ($recordSet as $row)
		{
			$dataset[$row['id']] = $row + $subPrep;
		}

		$idField = $this->_name . '_id';
		foreach ($subs as $stackName => $className)
		{
			$oTable = new $className();
			$data = $oTable->stackList($chain)->data;
			foreach ($data as $row)
			{
				isset($row[$idField])
					&& isset($dataset[$row[$idField]])
					&& $dataset[$row[$idField]][$stackName][$row['id']] = $row;
			}
		}

		#-> Return to caller.
		return Struct_ActionFeedback::successWithData($dataset);
	}

	/**
	 * Retrieve list of item entries (dropdown list format).
	 * @param  array   $where
	 * @param  array   $order
	 * @param  boolean $allColumns
	 * @return Struct_ActionFeedback
	 */
	public function listAll(array $where, array $order = array(), $allColumns = false)
	{
		$where = $this->_appendArrayAutoFilter($where);
		$recordSet = $this->fetchAll($where, $order);
		$entries = array();
		if (!$allColumns)
		{
			foreach ($recordSet as $record)
			{
				$search = array();
				$replace = array();
				foreach ($record as $field => $value)
				{
					$search[] = "[$field]";
					$replace[] = $value;
				}
				$entries[] = array('id' => $record['id'], 'name' => str_replace($search, $replace, $this->labelFormat));
			}
		}
		else
		{
			$entries = $recordSet;
		}

		#-> Return to caller.
		return Struct_ActionFeedback::successWithData($entries);
	}

	/**
	 * Retrieve data grid of item entries.
	 * @param  array   $where
	 * @param  array   $order
	 * @param  integer $numRecordsPerPage
	 * @param  integer $numPage
	 * @param  array   $filters
	 * @param  array   $excludeTables
	 * @return Struct_ActionFeedback
	 */
	public function grid(
			array $where, array $order = array(),
			$numRecordsPerPage = null, $numPage = null,
			$filters = array(), $excludeTables = array(),
			$chain = array(), $group = array()
			)
	{
		#-> Get joined selector.
		$liveOnly = !isset($filters['IncludeArchived']) || !$filters['IncludeArchived']
			? true
			: false;
		$this->activeDataTags = array();
		$select = $this->_buildJoins($excludeTables, $chain, $liveOnly);

		#-> Filtering.
		$where = $this->_appendArrayAutoFilter($where);
		foreach ($where as $spec => $value)
		{
			$select->where($spec, $value);
		}
		if ($this->checkTableFlag(TABLE_PSEUDO_DELETE)
				&& $liveOnly)
		{
			$select->where($this->_name . '.' . $this->archiveField . ' = ?', 0);
		}

		#-> Group.
		foreach ($group as $spec)
		{
			$select->group($spec);
		}

		#-> Order.
		$ordering = array();
		foreach ($order as $spec)
		{
			list($field, $direction) = explode(' ', $spec);
			empty($ordering)
				&& $ordering[$field] = $direction;
			$select->order($spec);
		}

		#-> Limit rows fetched.
		if (!is_null($numRecordsPerPage) && !is_null($numPage))
		{
			$select->limit($numRecordsPerPage, ($numPage - 1) * $numRecordsPerPage);
		}

		#-> Fetch data.
		$sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS DISTINCT', $select->assemble());
	//IS_DEV_ENV && Struct_Debug::errorLog('Query', $sql);
		$recordSet = $select->getAdapter()->fetchAll($sql);
	//error_log('DONE');
		$result = $select->getAdapter()->fetchAll("SELECT FOUND_ROWS()");
		$rows = array_shift($result[0]);
		$numPages = !is_null($numRecordsPerPage)
			? ceil($rows / $numRecordsPerPage)
			: '';

		#-> Flatten data and return to caller.
		return Struct_ActionFeedback::successWithData(
				$this->_flattenRecordSet($recordSet, $chain),
				array(
						'Search' => $filters,
						'DataFlags' => $this->activeDataTags,
						'Paging' => array(
								'RecordsPerPage' 	=> $numRecordsPerPage,
								'CurrentPage' 		=> $numPage,
								'TotalPages' 		=> $numPages,
								'TotalRecords' 		=> $rows
						),
						'Order'  => $ordering
						)
				);
	}

	/**
	 * Count table entries relevant to filters supplied.
	 * @param array $where
	 * @return integer
	 */
	public function count(array $where, $includeArchived = false)
	{
		$table = new Zend_Db_Table($this->_name);
		$select = $table->getAdapter()
			->select()
			->from($this->_name, 'COUNT(id) AS total');
		$where = $this->_appendArrayAutoFilter($where);
		foreach ($where as $spec => $value)
		{
			$select->where($spec, $value);
		}
		if (!$includeArchived && $this->checkTableFlag(TABLE_PSEUDO_DELETE))
		{
			$select->where($this->_name . '.' . $this->archiveField . ' = ?', 0);
		}
		return $select
			->query(Zend_Db::FETCH_OBJ)
			->fetch()
			->total;
	}

	/**
	 * Table for logging data changes.
	 * @return Table_NirphAuditLog
	 */
	protected function _getAuditTable()
	{
		!is_null(self::$auditTable)
			|| self::$auditTable = new Object_AppAuditLog();
		return self::$auditTable;
	}

	/**
	 * Establish data tag filters to apply to this table.
	 * @return array
	 */
	protected function _buildAutoFilters()
	{
		$filter = Struct_Registry::getContext('dataContext');
		if (is_null($filter) || empty($filter))
		{
			return array();
		}

		#-> Establish filters.
		$filters = array();
		foreach ($filter as $field => $data)
		{
			$value = $data['value'];
			$label = $data['label'];
			if (strpos($field, ':'))
			{
				list($match, $pattern) = explode(':', $field);
				if ('prepend' == $match)
				{
					#-> Match prepend part
					if (strlen($this->_name) > strlen($pattern)
							&& $pattern == substr(
								$this->_name,
								0,
								strlen($pattern)
								))
					{
						$this->activeDataTags[$field] = $label;
						$filters = array_merge($filters, $value);
					}
				}
				elseif ('postpend' == $match)
				{
					#-> Match on postpend part
					if (strlen($this->_name) > strlen($pattern)
							&& $pattern == substr(
								$this->_name,
								strlen($this->_name) - strlen($pattern),
								strlen($pattern)
								))
					{
						$this->activeDataTags[$field] = $label;
						$filters = array_merge($filters, $value);
					}
				}
				else
				{
					#-> Match on full table name
					if ($pattern == $this->_name)
					{
						$this->activeDataTags[$field] = $label;
						$filters = array_merge($filters, $value);
					}
				}
			}
			elseif (isset($this->fieldNames[$field]))
			{
				$this->activeDataTags[$field] = $label;
				$filters[$field] = $value;
			}
		}

		return $filters;
	}

	/**
	 * Add data context to a new/existing data entry.
	 * @param  array $record
	 * @return array
	 */
	protected function _appendAutoData(array $record)
	{
		$filters = $this->_buildAutoFilters();
		if (empty($filters))
		{
			return $record;
		}
		foreach ($filters as $field => $value)
		{
			if (isset($this->fieldNames[$field]))
			{
				$record[$field] = $value;
			}
		}
		return $record;
	}

	/**
	 * Add data context filtering.
	 * @param  array $where
	 * @return array
	 */
	protected function _appendArrayAutoFilter(array $where)
	{
		$filters = $this->_buildAutoFilters();
		if (empty($filters))
		{
			return $where;
		}
		foreach ($filters as $field => $value)
		{
			if (isset($this->fieldNames[$field]))
			{
				$where["$this->_name.$field = ?"] = $value;
			}
		}
		return $where;
	}

	/**
	 * Add data context filter to a select statement.
	 * @param  Zend_Db_Select $select
	 * @return Zend_Db_Select
	 */
	protected function _appendAutoFilter(Zend_Db_Select $select)
	{
		$filters = $this->_buildAutoFilters();
		if (empty($filters))
		{
			return $select;
		}
		foreach ($filters as $field => $value)
		{
			if (isset($this->fieldNames[$field]))
			{
				$select->where("$field = ?", $value);
			}
		}
		return $select;
	}

	/**
	 * Unpack chain-link request into convenient format.
	 * @param array $chain
	 * @return multitype:|multitype:multitype: NULL
	 */
	protected function _unpackChain(array $chain)
	{
		if (empty($chain))
		{
			return $chain;
		}
		$links = array();
		foreach ($chain as $link)
		{
			$matches = array();
			if (preg_match("/\[([^]]*)\]/", $link, $matches))
			{
				$parent = str_replace($matches[0], '', $link);
				$links[$parent] = explode(',', $matches[1]);
			}
			else
			{
				$links[$link] = array();
			}
		}
		return $links;
	}

	/**
	 * Recursive chain joining, BOORAH!
	 * @param  Zend_Db_Select $select
	 * @param  array $dataContext
	 * @param  array $excludeTables
	 * @param  string $baseTable
	 * @param  string $rootTable
	 * @param  array $referenceMap
	 * @param  array $chain
	 * @param  boolean $liveOnly
	 * @return Zend_Db_Select
	 */
	protected function _extendedJoins(
			Zend_Db_Select $select, array $dataContext, array $excludeTables, $baseTable,
			array $fieldMeta, array $referenceMap, array $chain, $liveOnly = true, &$refs = array()
			)
	{
		$myChain = $chain[$baseTable];
		is_array($this->stackMap)
			|| $this->stackMap = array();
		foreach ($referenceMap as $joinName => $joinSpec)
		{
			$fieldMap    = array();
			$classname   = $joinSpec['refTableClass'];
			$joinTable   = new $classname();
			$tableName   = $joinTable->getTableName();
			$tableFields = $joinTable->getFieldNames();
			$fieldSpec   = array();
			$ref_table_prepend = str_replace(
					$tableName . '_id',
					'',
					$joinSpec['columns']
			);
			if (!empty($myChain) && !in_array($ref_table_prepend . $tableName, $myChain))
			{
				continue;
			}
			if (empty($myChain)
					&& (in_array($tableName, $excludeTables)
							|| in_array($ref_table_prepend . $tableName, $excludeTables)) )
			{
				continue;
			}
			foreach ($tableFields as $dbFieldName => $codeFieldName)
			{
				if (!$this->showPassFields
						&& ('password' == $dbFieldName || 'password_salt' == $dbFieldName
							|| 'photo' == $dbFieldName || 'thumbnail'  == $dbFieldName
							|| 'video' == $dbFieldName || 'document' == $dbFieldName)
				)
				{
					continue;
				}
				if (isset($this->dependancyChain[$joinName])
						&& $dbFieldName == $this->dependancyChain[$joinName][0]['columns'])
				{
					$fieldSpec[$baseTable . '_' . $dbFieldName] = $dbFieldName;
					$tableMeta = $joinTable->getFieldMeta();
					$nullable = $tableMeta[$dbFieldName]['NULLABLE'];
				}
				else
				{
					$fieldSpec[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
					$fieldMap[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
				}
			}
			$this->buildMap[$ref_table_prepend . $tableName] = $fieldMap;
			$this->stackMap[$ref_table_prepend . $tableName] = $baseTable;

			$foreignFilters = $joinTable->retrieveFilters();
			$joinFilters = array();
			foreach ($foreignFilters as $field => $label)
			{
				$filterValue = is_numeric($dataContext[$field]['value'])
					? $dataContext[$field]['value']
					: "'" . $dataContext[$field]['value'] . "'";
				$joinFilters[] = $ref_table_prepend . $tableName . '.' . $field . ' = ' . $filterValue;
				$this->activeDataTags[$field] = $label;
			}
			if ($liveOnly && $joinTable->checkTableFlag(TABLE_PSEUDO_DELETE))
			{
				$joinFilters[] = $ref_table_prepend . $tableName . '.archived = 0';
			}
			$select->joinLeft(
					array ($ref_table_prepend . $tableName => $tableName),
					$ref_table_prepend . "$tableName.id = $baseTable." . $joinSpec['columns']
					. (empty($joinFilters) ? '' : ' AND ' . implode(' AND ', $joinFilters)),
					$fieldSpec
			);
			$refs[] = $ref_table_prepend . $tableName;

			#-> Recurvatus
			if (isset($chain[$ref_table_prepend . $tableName]))
			{
				$select = $this->_extendedJoins(
						$select, $dataContext, $excludeTables, $ref_table_prepend . $tableName,
						$this->getFieldMeta(),
						$joinTable->getReferenceMap(), $chain,
						$liveOnly
				);
			}
		}
		return $select;
	}

	/**
	 * Build joins required to pull in associated data.
	 * @param  array $excludeTables
	 * @param  array $chain
	 * @param  boolean $liveOnly
	 * @return Zend_Db_Select
	 */
	protected function _buildJoins(array $excludeTables = array(), array $chain = array(), $liveOnly = true)
	{
		#-> Select mechinism.
		$chain = $this->_unpackChain($chain);
		$refs = array();
		$keepOuter = false;
		$dataContext = Struct_Registry::getContext('dataContext');
		$table = new Zend_Db_Table($this->_name);
		$fieldSpec = array();
		$this->buildMap = array();
		foreach ($this->fieldNames as $dbFieldName => $codeFieldName)
		{
			if (!$this->showPassFields
					&& ('password' == $dbFieldName || 'password_salt' == $dbFieldName
							|| 'photo' == $dbFieldName || 'thumbnail'  == $dbFieldName
							|| 'video' == $dbFieldName || 'document' == $dbFieldName)
					)
			{
				continue;
			}
			$fieldSpec[$dbFieldName] = $dbFieldName;
		}
		$select = $table->getAdapter()
			->select()
			->from(array ($this->_name => $this->_name), $fieldSpec);

		#-> Handle temporary joins.
		if (is_array($this->tempJoin))
		{
			foreach ($this->tempJoin as $join)
			{
				$fieldSpec = array();
				foreach ($join['fields'] as $field)
				{
					$fieldSpec[$join['table'] . '_' . $field] = $field;
				}
				$select->join($join['table'], $join['condition'], $fieldSpec);
				$this->buildMap[$join['table']] = $fieldSpec;
			}
			$this->tempJoin = null;
		}

		#-> Joined associated tables.
		foreach ($this->_referenceMap as $joinName => $joinSpec)
		{
			$fieldMap    = array();
			$classname   = $joinSpec['refTableClass'];
			$joinTable   = new $classname();
			$tableName   = $joinTable->getTableName();
			if (in_array($tableName, $excludeTables))
			{
				continue;
			}
			$tableFields = $joinTable->getFieldNames();
			$fieldSpec   = array();
			$ref_table_prepend = str_replace(
					$tableName . '_id',
					'',
					$joinSpec['columns']
					);
			foreach ($tableFields as $dbFieldName => $codeFieldName)
			{
				if (!$this->showPassFields
						&& ('password' == $dbFieldName || 'password_salt' == $dbFieldName
							|| 'photo' == $dbFieldName || 'thumbnail'  == $dbFieldName
							|| 'video' == $dbFieldName || 'document' == $dbFieldName)
						)
				{
					continue;
				}
				if (isset($this->dependancyChain[$joinName])
						&& $dbFieldName == $this->dependancyChain[$joinName][0]['columns'])
				{
					$fieldSpec[$this->_name . '_' . $dbFieldName] = $dbFieldName;
					$tableMeta = $joinTable->getFieldMeta();
					$nullable = $tableMeta[$dbFieldName]['NULLABLE'];
				}
				else
				{
					$fieldSpec[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
					$fieldMap[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
				}
			}
			$this->buildMap[$ref_table_prepend . $tableName] = $fieldMap;

			$foreignFilters = $joinTable->retrieveFilters();
			$joinFilters = array();
			foreach ($foreignFilters as $field => $label)
			{
				$filterValue = is_numeric($dataContext[$field]['value'])
					? $dataContext[$field]['value']
					: "'" . $dataContext[$field]['value'] . "'";
				$joinFilters[] = $ref_table_prepend . $tableName . '.' . $field . ' = ' . $filterValue;
				$this->activeDataTags[$field] = $label;
			}
			if ($liveOnly && $joinTable->checkTableFlag(TABLE_PSEUDO_DELETE))
			{
				$joinFilters[] = $ref_table_prepend . $tableName . '.archived = 0';
			}

			$joinType = $this->_metadata[$joinSpec['columns']]['NULLABLE']
				? 'outer'
				: 'inner';
			if ('inner' == $joinType && !$keepOuter)
			{
				$select->join(
						array ($ref_table_prepend . $tableName => $tableName),
						$ref_table_prepend . "$tableName.id = $this->_name." . $joinSpec['columns']
						. (empty($joinFilters) ? '' : ' AND ' . implode(' AND ', $joinFilters)),
						$fieldSpec
						);
			}
			else
			{
				$keepOuter = true;
				$select->joinLeft(
						array ($ref_table_prepend . $tableName => $tableName),
						$ref_table_prepend . "$tableName.id = $this->_name." . $joinSpec['columns']
						. (empty($joinFilters) ? '' : ' AND ' . implode(' AND ', $joinFilters)),
						$fieldSpec
						);
			}
			$refs[] = $ref_table_prepend . $tableName;

			#-> Recurvatus
			if (isset($chain[$ref_table_prepend . $tableName]))
			{
				$keepOuter = true;
				$select = $this->_extendedJoins(
						$select, $dataContext, $excludeTables, $ref_table_prepend . $tableName,
						$this->getFieldMeta(),
						$joinTable->getReferenceMap(), $chain,
						$liveOnly, $refs
						);
			}

			if (isset($this->dependancyChain[$joinName]))
			{
				$chainLen = count($this->dependancyChain[$joinName]);
				foreach ($this->dependancyChain[$joinName] as $joinId => $joinSpec)
				{
					if ($joinId == $chainLen || !is_array($joinSpec) || empty($joinSpec))
					{
						break;
					}
					$classname = $joinSpec['refTableClass'];
					$joinTable = new $classname();
					$prevTable = $ref_table_prepend . $tableName;
					$tableName = $joinTable->getTableName();
					$ref_table_prepend = str_replace(
							$tableName . '_id',
							'',
							$joinSpec['columns']
					);
					if (in_array($ref_table_prepend . $tableName, $refs)){
						continue;
					}
					$tableFields = $joinTable->getFieldNames();
					$fieldSpec   = array();
					$fieldMap    = array();
					foreach ($tableFields as $dbFieldName => $codeFieldName)
					{
						if (!$this->showPassFields
								&& ('password' == $dbFieldName || 'password_salt' == $dbFieldName)
								)
						{
							continue;
						}
						$fieldSpec[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
						$fieldMap[$ref_table_prepend . $tableName . '_' . $dbFieldName] = $dbFieldName;
					}
					$this->buildMap[$ref_table_prepend . $tableName] = $fieldMap;
					$archiveFilter = ($liveOnly && $joinTable->checkTableFlag(TABLE_PSEUDO_DELETE))
						? ' AND ' . $ref_table_prepend . $tableName . '.archived = 0'
						: '';

					$joinType = $nullable
						? 'outer'
						: 'inner';
					if ('inner' == $joinType && !$keepOuter)
					{
						$select->join(
								array ($ref_table_prepend . $tableName => $tableName),
								$ref_table_prepend . "$tableName.id = $prevTable." . $joinSpec['columns'] . $archiveFilter,
								$fieldSpec
						);
					}
					else
					{
						$keepOuter = true;
						$select->joinLeft(
								array ($ref_table_prepend . $tableName => $tableName),
								$ref_table_prepend . "$tableName.id = $prevTable." . $joinSpec['columns'] . $archiveFilter,
								$fieldSpec
						);
					}
					$refs[] = $ref_table_prepend . $tableName;
				}
			}
		}

		#-> Handle temporary joins.
		if (is_array($this->tempJoinAfter))
		{
			foreach ($this->tempJoinAfter as $join)
			{
				$fieldSpec = array();
				foreach ($join['fields'] as $field)
				{
					$fieldSpec[$join['table'] . '_' . $field] = $field;
				}
				$select->joinLeft($join['table'], $join['condition'], $fieldSpec);
				$this->buildMap[$join['table']] = $fieldSpec;
			}
			$this->tempJoinAfter = null;
		}

		#-> Return selector.
		return $select;
	}

	/**
	 * Get rid of the objects, pack everything into an array.
	 * @param  Zend_Db_Table_Rowset_Abstract $recordSet
	 * @param  array $chain
	 * @return array
	 */
	protected function _flattenRecordSet($recordSet, array $chain = array())
	{
		$i = 0;
		$entries = array();
		if ($this->htmlEncodeReturnData)
		{
			foreach ($recordSet as $row) {
				$index = $this->index
					? $row['id']
					: $i;
				$i++;
				$entry = is_object($row)
					? $row->toArray()
					: $row;
				foreach ($entry as $field => $value)
				{
					$entry[$field] = htmlentities($value);
				}
				$entries[$index] = $this->_unpackRow($entry);
			}
		}
		else
		{
			foreach ($recordSet as $row) {
				$index = $this->index
					? $row['id']
					: $i;
				$i++;
				$entries[$index] = is_object($row)
					? $this->_unpackRow($row->toArray())
					: $this->_unpackRow($row);
			}
		}
		$this->buildMap = false;
		return $entries;
	}

	/**
	 * Unpack an extended query into stacked object format.
	 * @param array $row
	 * @return array
	 */
	protected function _unpackRow(array $row)
	{
		if ($this->flatten)
		{
			foreach ($row as $field => $value)
			{
				if ('lib_photo_id' == substr($field, -12))
				{
					$photoField = str_replace('lib_photo_id', 'photo_url', $field);
					$row[$photoField] = strlen($value) > 0
						? 'http://' . APP_HOST . '/image?id=' . $value
						: false;
				}
				if ('archived' == $field
						|| '_archived' == substr($field, -9)
						|| 'mime_type' == substr($field, -9)
						|| 'report_' == substr($field, 0, 7)
						|| strpos($field, '_can_')
						|| strpos($field, '_is_')
						|| strpos($field, '_display_')
						|| strpos($field, '_subscribe_'))
				{
					unset($row[$field]);
				}
			}
			return $row;
		}
		if (!$this->buildMap)
		{
			return $row;
		}
		foreach ($this->buildMap as $baseTable => $fieldMap)
		{
			if (!isset($this->stackMap[$baseTable]))
			{
				#-> Attach to root.
				$row[$baseTable] = array();
				foreach ($fieldMap as $flatField => $actualField)
				{
					$row[$baseTable][$actualField] = $row[$flatField];
					unset($row[$flatField]);
				}
			}
			else
			{
				#-> Attach to nested item.
				$stack = array();
				foreach ($fieldMap as $flatField => $actualField)
				{
					$stack[$actualField] = $row[$flatField];
					unset($row[$flatField]);
				}
				$result = $this->stackNestedData($row, $stack, $baseTable, $this->stackMap[$baseTable]);
				$row = !is_null($result)
					? $result: $row;
			}
		}
		return $row;
	}

	/**
	 * Recursive data stacking for recursive chain joins, BOORAH!
	 * @param array $row
	 * @param array $data
	 * @param string $baseTable
	 * @param string $root
	 * @return array|null
	 */
	protected function stackNestedData(array $row, array $data, $baseTable, $root)
	{
		if (isset($row[$root]))
		{
			$row[$root][$baseTable] = $data;
			if (isset($row[$root][$baseTable . '_id']))
			{
				unset($row[$root][$baseTable . '_id']);
			}
			return $row;
		}
		foreach ($row as $field => $value)
		{
			if (is_array($value))
			{
				$result = $this->stackNestedData($value, $data, $baseTable, $root);
				if (!is_null($result))
				{
					$row[$field] = $result;
					return $row;
				}
			}
		}
		return null;
	}

	/**
	 * Auto-construct joins for all directly related
	 * @return Ambigous <Zend_Db_Select, Zend_Db_Select>
	 */
	protected function _buildDependancyJoins()
	{
		$table = new Zend_Db_Table($this->_name);
		$select = $table->getAdapter()
				->select()
				->from(array ($this->_name => $this->_name));
		foreach ($this->dependancyChain as $chain => $referenceMap)
		{
			$chainLen = count($referenceMap) - 1;
			foreach ($referenceMap as $joinId => $joinSpec)
			{
				if ($joinId == $chainLen)
				{
					break;
				}
				$classname   = $joinSpec['refTableClass'];
				$joinTable   = new $classname();
				$tableName   = $joinTable->getTableName();
				$ref_table_prepend = str_replace(
						$tableName . '_id',
						'',
						$joinSpec['columns']
						);
				$dbFieldName = $referenceMap[$joinId + 1]['columns'];
				/* $joinType = $this->_metadata[$joinSpec['columns']]['NULLABLE']
					? 'outer'
					: 'inner';
				if ('inner' == $joinType)
				{
					$select->join(
							array ($ref_table_prepend . $tableName => $tableName),
							$ref_table_prepend . "$tableName.id = $this->_name." . $joinSpec['columns'],
							$dbFieldName
							);
				}
				else
				{ */
					$select->joinLeft(
							array ($ref_table_prepend . $tableName => $tableName),
							$ref_table_prepend . "$tableName.id = $this->_name." . $joinSpec['columns'],
							$dbFieldName
							);
				//}
			}
		}

		#-> Fin
		return $select;
	}


}
