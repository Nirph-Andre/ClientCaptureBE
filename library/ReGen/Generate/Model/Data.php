<?php


/**
 * Model generation from database structure.
 * @author andre.fourie
 */
class ReGen_Generate_Model_Data extends CodeSource
{

	protected $js = array();
	protected $jsLookup = array();
	protected $jsValidate = array();

	/**
	 * Construct data model code for data access to all tables in database.
	 * Assumes that Project Context data has been set for correct file locations.
	 * @param string $dbName
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param array  $aliasMap
	 */
	public function setupDataModel(
			$dbName, $dbUser, $dbPass, $aliasMap, $amfMap,
			$forceRefresh = false, $rebuild = false
			)
	{
		#-> Need some more time to do stuff...
		set_time_limit(90);

		#-> Collect list of tables from db.
		$db = new Zend_Db_Adapter_Pdo_Mysql(array(
				'host'	 => 'localhost',
				'username' => $dbUser,
				'password' => $dbPass,
				'dbname'   => $dbName
		));
		$recset = $db
			->select()
			->from('meta_table')
			->query()
			->fetchAll();
		$currentHash = array();
		foreach ($recset as $record)
		{
			$currentHash[$record['name']] = $record['hash'];
		}


		#-> Tools.
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();

		#-> Grab csv and chew up first two rows for breakfast.
		$handle = fopen('c:\Apache2\htdocs\ClientCaptureBE\data\db.csv', "r");
		$data = fgetcsv($handle);
		$data = fgetcsv($handle);

		#-> Prep.
		$statements = array();
		$csvTables = array();
		$csvHashData = array();
		$table = false;
		$displayMeta = array();
		$haveLength = array('DECIMAL', 'CHAR', 'VARCHAR');
		$numericTypes = array('TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT', 'FLOAT', 'DOUBLE', 'FLOAT');
		$insert = array();
		$unique = array();

		#-> Process meta data for tables.
		while (($data = fgetcsv($handle)) !== FALSE)
		{
			$cols = count($data);
			if (!$cols || empty($data[1]))
			{
				// blank line, end of table definition
				if ($table)
				{
					// new, updated or unchanged table?
					$create[] = '  PRIMARY KEY (`id`)' . "\n";
					$create[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';
					$query = implode($create);
					if (!isset($currentHash[$table]))
					{
						// new table
						$statements[] = "DROP TABLE IF EXISTS `$table`;";
						$db->query("DROP TABLE IF EXISTS `$table`;");
						$statements[] = $query;
						$db->query($query);
						$csvTables[] = $table;
						foreach ($insert as $i => $row)
						{
							$query = $this->buildInsert($table, $row);
							$statements[] = $query;
							$db->query($query);
						}
						$db->query("INSERT INTO meta_table (name,created,hash,version) VALUES ('$table','" . date('Y-m-d') . "','" . md5($query) . "', 1)");
					}
					elseif ($currentHash[$table] != md5($query))
					{
						// updated table
						$statements[] = "DROP TABLE IF EXISTS `$table`;";
						$db->query("DROP TABLE IF EXISTS `$table`;");
						$statements[] = $query;
						$db->query($query);
						$csvTables[] = $table;
						foreach ($insert as $i => $row)
						{
							$query = $this->buildInsert($table, $row);
							$statements[] = $query;
							$db->query($query);
						}
						$db->query("UPDATE meta_table SET updated='" . date('Y-m-d') . "', hash='" . md5($query) . "', version=version+1 WHERE name='$table'");
					}
					elseif ($forceRefresh) // force structure and data rebuild
					{
						$statements[] = "DROP TABLE IF EXISTS `$table`;";
						$db->query("DROP TABLE IF EXISTS `$table`;");
						$statements[] = $query;
						$db->query($query);
						foreach ($insert as $i => $row)
						{
							$query = $this->buildInsert($table, $row);
							$statements[] = $query;
							$db->query($query);
						}
					}
				}
				$table = false;
				continue;
			}
			if (!$table)
			{
				$parts = explode(':', $data[0]);
				$table = $parts[0];
				$displayMeta[$table] = array();
				$unique[$table] = array();
				$label = isset($parts[1]) ? $parts[1] : false;
				$create = array();
				$create[] = "CREATE TABLE `$table` (" . "\n";
				$create[] = "  `id` INT NOT NULL AUTO_INCREMENT ," . "\n";
				$insert = array();
			}

			// collect
			$parts = explode(':', $data[2]);
			$field = $data[1];
			$data_type = $parts[0];
			$data_type_extra = isset($parts[1]) ? $parts[1] : false;
			$data_length = $data[3];
			$data_enum = $data[4];
			$data_null = $data[5];
			$data_default = $data[6];
			$display_type = $data[7];
			$display_required = $data[8];
			$display_validation = $data[9];
			$displayMeta[$table][$field] = array(
					'dataType' => $data_type,
					'dataLength' => $data_length,
					'dataEnum' => $data_enum,
					'dataNull' => $data_null,
					'dataDefault' => $data_default,
					'displayType' => $display_type,
					'displayRequired' => (1 == $data_null || 1 == $display_required ? true : false),
					'displayValidation' => $display_validation,
					);
			if (!in_array($data_type, $haveLength))
			{
				$data_length = '';
			}
			if (!empty($data_enum)){
				$data_enum = "'" . str_replace(',', "','", $data_enum) . "'";
			}
			$fieldType = '';
			!empty($data_enum) && $fieldType .= "$data_type($data_enum)";
			empty($fieldType) && !empty($data_length) && $fieldType .= "$data_type($data_length)";
			empty($fieldType) && $fieldType = "$data_type";
			in_array($data_type, $numericTypes)
				&& $fieldType .= " UNSIGNED";

			// data-setup
			for ($i = 10; $i < 40; $i++)
			{
				$rec = $i - 10;
				isset($data[$i])
					&& !empty($data[$i])
					&& $insert[$rec][$field] = $data[$i];
			}

			// build create statements
			$create[$field] = "  `$field` $fieldType " . (1 == $data_null ? 'NOT NULL ' : '') . (strlen($data_default) > 0 ? "DEFAULT '$data_default'" : '') . ',' . "\n";
			if ('UNIQUE' == $data_type_extra)
			{
				$unique[$table][] = $field;
			}
		}

		// blank line, end of table definition
		if ($table)
		{
			// new, updated or unchanged table?
			$create[] = '  PRIMARY KEY (`id`)' . "\n";
			$create[] = ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';
			$query = implode($create);
			if (!isset($currentHash[$table]))
			{
				// new table
				$statements[] = $query;
				$db->query($query);
				$csvTables[] = $table;
				foreach ($insert as $i => $row)
				{
					$query = $this->buildInsert($table, $row);
					$statements[] = $query;
					$db->query($query);
				}
				$db->query("INSERT INTO meta_table (name,created,hash,version) VALUES ('$table','" . date('Y-m-d') . "','" . md5($query) . "', 1)");
			}
			elseif ($currentHash[$table] != md5($query))
			{
				// updated table
				$statements[] = "DROP TABLE IF EXISTS `$table`;";
				$db->query("DROP TABLE IF EXISTS `$table`;");
				$statements[] = $query;
				$db->query($query);
				$csvTables[] = $table;
				foreach ($insert as $i => $row)
				{
					$query = $this->buildInsert($table, $row);
					$statements[] = $query;
					$db->query($query);
				}
				$db->query("UPDATE meta_table SET updated='" . date('Y-m-d') . "', hash='" . md5($query) . "', version=version+1 WHERE name='$table'");
			}
			elseif ($forceRefresh) // force structure and data rebuild
			{
				$statements[] = "DROP TABLE IF EXISTS `$table`;";
				$db->query("DROP TABLE IF EXISTS `$table`;");
				$statements[] = $query;
				$db->query($query);
				foreach ($insert as $i => $row)
				{
					$query = $this->buildInsert($table, $row);
					$statements[] = $query;
					$db->query($query);
				}
			}
		}
		$table = false;


		#-> Collect list of tables from db.
		$dbTables = $db->listTables();


		#-> Map table associations.
		$tables     = array();
		$fieldMeta  = array();
		$dependants = array();
		$dependancies = array();
		$dependancyChain = ReGen_Registry::getContext('dataDependancies');
		$dependancyChain = is_array($dependancyChain)
			? $dependancyChain
			: array();
		foreach($dbTables as $key => $table)
		{
			if ('meta_table' == $table)
			{
				unset($dbTables[$key]);
				continue;
			}
			$mappers = array();
			if (strpos($table, '__')) {
				// link table
				list($tbl1, $tbl2) = explode('__', $table);
				$mappers[$tbl1] = $tbl2;
				$mappers[$tbl2] = $tbl1;
			}
			$tables[$table] = array(
					'Type' => (!empty($mappers) ? 'Link' : 'Basic'),
					'Maps' => $mappers
			);
		}
		foreach($dbTables as $table)
		{
			$fieldMeta[$table] = $db->describeTable($table);
			$tableName = $filter->filter($table);
			$dependancies[$table] = array();
			foreach ($fieldMeta[$table] as $key => $meta)
			{
				#-> Check for references.
				if ('_id' == substr($key, -3))
				{
					#-> Get rid of the _id bit.
					$parts = explode('_', $key);
					array_pop($parts);

					#-> Do we have a direct match?
					$search = implode('_', $parts);
					if (isset($tables[$search]))
					{
						isset($dependants[$search])
							|| $dependants[$search] = array();
						$dependants[$search][$table] = 'Table_' . $tableName;

						#-> Check for depenancy chain requirements.
						if (isset($dependancyChain[$key]))
						{
							$foreignItem = $filter->filter(
									str_replace(array('__', '_id'), array('_', ''), $key)
									);
							$foreignField = $dependancyChain[$key];
							$foreignTable = 'Table_' . $filter->filter(
									str_replace(array('__', '_id'), array('_', ''), $foreignField)
									);
							isset($dependancies[$table][$foreignItem])
								|| $dependancies[$table][$foreignItem] = array();
							$dependancies[$table][$foreignItem][] = array(
									'columns' => $foreignField,
									'refTableClass' => $foreignTable,
									'refColumns' => 'id'
							);
							if (isset($dependancyChain[$foreignField]))
							{
								$foreignField = $dependancyChain[$foreignField];
								$foreignTable = 'Table_' . $filter->filter(
										str_replace(array('__', '_id'), array('_', ''), $foreignField)
										);
								$dependancies[$table][$foreignItem][] = array(
										'columns' => $foreignField,
										'refTableClass' => $foreignTable,
										'refColumns' => 'id'
								);
							}
							if (isset($dependancyChain[$foreignField]))
							{
								$foreignField = $dependancyChain[$foreignField];
								$foreignTable = 'Table_' . $filter->filter(
										str_replace(array('__', '_id'), array('_', ''), $foreignField)
										);
								$dependancies[$table][$foreignItem][] = array(
										'columns' => $foreignField,
										'refTableClass' => $foreignTable,
										'refColumns' => 'id'
								);
							}
						}
					}
					else
					{
						#-> Disect and find it with backwards mini-matching.
						$search = '';
						$srchParts = array();
						while (!empty($parts))
						{
							array_unshift($srchParts, array_pop($parts));
							$search = implode('_', $srchParts);
							if (isset($tables[$search]))
							{
								isset($dependants[$search])
									|| $dependants[$search] = array();
								$dependants[$search][$table] = 'Table_' . $tableName;
							}
						}
					}
				}
			}
		}


		#-> Create Table_*, Object_* and *ManagerController classes.
		#-> Also create index, grid and modal views.
		foreach ($tables as $table => $meta)
		{
			$tableName = $filter->filter(str_replace('__', '_', $table));
			$dependentTables = isset($dependants[$table])
				? $dependants[$table]
				: array();
			$dependencyTables = isset($dependancies[$table])
				? $dependancies[$table]
				: array();
			$isLookupTable = in_array($table, $amfMap['Lookup']);
			$reference = $this->_createJsModel(
					$tableName, $table, $fieldMeta, $tables, $dependentTables,
					$dependencyTables, $displayMeta[$table], $isLookupTable
					);
			if ($rebuild != $table && !in_array($table, $csvTables))
			{
				continue;
			}
			$reference = $this->_createModel($tableName, $table, $fieldMeta, $tables, $dependentTables, $dependencyTables);
			$this->_createDataObject($tableName, $table, $fieldMeta, $unique[$table]);
			$this->_createDataController($tableName, $table, $fieldMeta[$table], $reference, $db, $dependencyTables);
			$this->_createViews($tableName, $table, $fieldMeta[$table], $reference, $db, $dependencyTables);
		}

		#-> Build AMF synch class.
		$this->_buildAmfSynchInterface($tables, $amfMap);
		$this->_writeEmberDataModels();
	}


	private function _writeEmberDataModels()
	{
		if (empty($this->js))
		{
			return;
		}
		return;

		#-> Save js models to file.
		$path = ReGen_Util_FileLocation::getJsLocation('appDataModels');
		file_put_contents(
				$path,
				"\n/* -------------------- Data Models -------------------- */\n"
				. implode("\n\n", $this->js)
				. "\n\n\n\n/* -------------------- Drop-List Data Models -------------------- */\n"
				. implode("\n\n", $this->jsLookup)
				);
		$path = ReGen_Util_FileLocation::getJsLocation('appDataValidation');
		file_put_contents(
				$path,
				"\n/* -------------------- Data Validation -------------------- */\n"
				. implode("\n\n", $this->jsValidate)
				);
	}


	/**
	 * Create js table model.
	 *
	 * @return Nirph_Db_Table_Abstract
	 */
	private function _createJsModel(
			$tableName, $table_name, $fieldMeta, $tables, $dependants, $dependancies, $displayMeta, $isLookupTable
			)
	{
		if (isset($this->js[$tableName]))
		{
			return;
		}
		#-> Grab field meta for this table.
		$fields = $fieldMeta[$table_name];

		#-> Tools.
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		$fltrsep = new Zend_Filter_Word_CamelCaseToSeparator();

		#-> Cater for lookup (drop-list) models.
		if ($isLookupTable)
		{
			$modelName = $tableName . 'DropList';
			$this->jsLookup[] = "
App.$modelName = DS.Model.extend({
	name: DS.attr('string')
});";
		}

		#-> Work through the meta data.
		$jsf = array();
		$jsv = array();
		foreach ($fields as $key => $meta)
		{
			#-> Check for references
			if ('id' == $key)
			{
				continue;
			}
			$dMeta = $displayMeta[$key];
			if ('_id' == substr($key, -3))
			{
				#-> Validation
				$validation = array();
				$dMeta['displayRequired']
					&& $validation[] = 'required';
				$dOpt = array(
						'embedded' => true,
						'display' => true, 'edit' => true,
						'validate' => implode(',', $validation),
						'displayType' => 'select'
						);
				$embedded = $dOpt['embedded']
					? ', ' . Zend_Json::encode(array('embedded' => true))
					: '';

				#-> Get rid of the _id bit.
				$field = lcfirst($filter->filter(substr($key, 0, strlen($key) -3)));
				$parts = explode('_', $key);
				array_pop($parts);
				$dOpt['label'] = ucfirst(implode(' ', $parts));

				#-> Do we have a direct match?
				$search = implode('_', $parts);
				if (isset($tables[$search]))
				{
					$referenceRule = $filter->filter($search);
					$jsf[] = "
	$field: DS.belongsTo('App.$referenceRule'$embedded)";
					$jsv[] = "
	$field: " . Zend_Json::encode($dOpt);
				}
				else
				{
					#-> Disect and find it with backwards mini-matching.
					$search = '';
					$srchParts = array();
					while (!empty($parts))
					{
						array_unshift($srchParts, array_pop($parts));
						$search = implode('_', $srchParts);
						if (isset($tables[$search]))
						{
							$refTable = $filter->filter($search);
							$jsf[] = "
	$field: DS.belongsTo('App.$refTable'$embedded)";
					$jsv[] = "
	$field: " . Zend_Json::encode($dOpt);
							break;
						}
					}
				}
			}
			else
			{
				#-> Validation.
				$validation = array();
				$dMeta['displayRequired']
					&& $validation[] = 'required';
				!empty($dMeta['dataEnum'])
					&& $validation[] = 'custum[enum[\'' . implode("','", explode(',', $dMeta['dataEnum'])) . '\']]';

				#-> DS attribute type.
				$field = lcfirst($filter->filter($key));
				$display = true;
				$edit = true;
				if ('archived' == $key || 'password_salt' == $key
						|| 'created' == $key || 'updated' == $key
						|| 'id' == $key)
				{
					$display = false;
					$edit = false;
				}
				if ('reference_no' == $key)
				{
					#-> This field is always generated on entry creation
					$display = true;
					$edit = false;
				}
				$displayType = 'input:text';
				switch ($meta['DATA_TYPE'])
				{
					case 'TINYINT':
						$displayType = !empty($dMeta['displayType'])
							? 'input:text'
							: 'input:checkbox';
						$type = !empty($dMeta['displayType'])
							? 'number'
							: 'boolean';
						break;
					case 'MEDIUMINT':
						$validation[] = 'custom[number]';
						$validation[] = 'max[16777215]';
						$type = 'number';
						break;
					case 'INT':
						$validation[] = 'custom[number]';
						$validation[] = 'max[4294967295]';
						$type = 'number';
						break;
					case 'BIGINT':
						$validation[] = 'custom[number]';
						$validation[] = 'max[18446744073709551615]';
						$type = 'number';
						break;
					case 'FLOAT':
						$validation[] = 'custom[number]';
						$validation[] = 'max[16777215]';
						$type = 'number';
						break;
					case 'DECIMAL':
						$validation[] = 'custom[number]';
						$validation[] = 'max[16777215]';
						$type = 'number';
						break;
					case 'DATE':
						$validation[] = 'custom[date]';
						$type = 'date';
					case 'DATETIME':
						$validation[] = 'custom[datetime]';
						$type = 'date';
						break;
					case 'VARCHAR':
					case 'CHAR':
						$validation[] = 'maxSize[' . $dMeta['dataLength'] . ']';
						$displayType = $dMeta['dataLength'] >= 200
							? 'textarea:small'
							: 'input:text';
						$type = 'string';
						break;
					case 'TINYTEXT':
						$validation[] = 'maxSize[255]';
						$displayType = 'textarea:small';
						$type = 'string';
						break;
					case 'MEDIUMTEXT':
						$validation[] = 'maxSize[16777215]';
						$displayType = 'textarea:large';
						$type = 'string';
						break;
					case 'LONGTEXT':
						$validation[] = 'maxSize[4294967295]';
						$displayType = 'textarea:large';
						$type = 'string';
						break;
					default:
						$displayType = 'input:text';
						$type = 'string';
						break;
				}
				if ('password' == $key)
				{
					$displayType = 'input:password';
				}
				$dOpt = array(
						'display' => $display, 'edit' => $edit,
						'validate' => implode(',', $validation),
						'displayType' => $displayType,
						'label' => ucfirst($fltrsep->filter($field))
						);
				$jsf[] = "
	$field: DS.attr('$type')";
					$jsv[] = "
	$field: " . Zend_Json::encode($dOpt);
			}

		}

		foreach ($dependants as $depTable => $depClass)
		{
			$field = lcfirst($filter->filter($depTable))
				. ('s' == substr($depTable, -1) ? 'es' : 's');
			$reference = substr($depClass, 6, strlen($depClass) -6);
			$jsf[] = "
	$field: DS.hasMany('App.$reference', {\"embedded\":true})";
		}

		$jsf[] = "
	md5: DS.attr('string')";
		$jsf[] = "
	errors: DS.attr('string')";

		if ('lib_photo' == $table_name)
		{
			$jsf[] = "
	img: function() {
      return new Handlebars.SafeString('<img src=\"/image?id=' + this.get('id') + '\">');
    }.property('id')";
		}

		$js = "
App.$tableName = DS.Model.extend({";
		$js .= implode(",", $jsf);
		$js .= "
});";
		$this->js[$tableName] = $js;

		$js = "
App.Validate.$tableName = {";
		$js .= implode(",", $jsv);
		$js .= "
};";

		#-> All done -oO-
		$this->jsValidate[$tableName] = $js;
	}

	private function _buildAmfSynchInterface($tables, $amfMap)
	{
		#-> Tools.
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();

		foreach ($amfMap as $modelClassName => $tableList)
		{
			#-> Create amf synch class.
			$amfClass = new Zend_CodeGenerator_Php_Class();
			$docblock = new Zend_CodeGenerator_Php_Docblock(array(
					'shortDescription' => 'Data services for tablet devices.'
			));
			$amfClass->setName($modelClassName)
				->setDocblock($docblock)
				->setExtendedClass('Struct_Abstract_AmfService');

			#-> Build code.
			foreach ($tableList as $table => $objectName)
			{
				$objectName = $filter->filter($objectName);
				$table = $filter->filter($table);
				if ('Lookup' != $modelClassName)
				{
					$amfClass->setMethod(array(
							'name' => 'create' . $objectName,
							'visibility' => 'public',
							'parameters' => array(
									array('name' => 'authToken'),
									array('name' => 'data', 'datatype' => 'array')
									),
							'body' => 'return $this->synch($authToken, \''.$table.'\', \'Create\', $data);',
							'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
									'shortDescription' => 'Create a new ' . $objectName . ' entry.',
									'tags' => array(
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'string',
													'paramName'   => 'authToken'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'array',
													'paramName'   => 'data'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Return(
													array('datatype'    => 'array')
													)
											)
							))
					));

					$amfClass->setMethod(array(
							'name' => 'update' . $objectName,
							'visibility' => 'public',
							'parameters' => array(
									array('name' => 'authToken'),
									array('name' => 'data', 'datatype' => 'array')
							),
							'body' => 'return $this->synch($authToken, \''.$table.'\', \'Update\', $data);',
							'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
									'shortDescription' => 'Update existing ' . $objectName . ' entry.',
									'tags' => array(
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'string',
													'paramName'   => 'authToken'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'array',
													'paramName'   => 'data'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Return(
													array('datatype'    => 'array')
											)
									)
							))
					));

					$amfClass->setMethod(array(
							'name' => 'delete' . $objectName,
							'visibility' => 'public',
							'parameters' => array(
									array('name' => 'authToken'),
									array('name' => 'data', 'datatype' => 'array')
							),
							'body' => 'return $this->synch($authToken, \''.$table.'\', \'Delete\', $data);',
							'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
									'shortDescription' => 'Delete a new ' . $objectName . ' entry.',
									'tags' => array(
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'string',
													'paramName'   => 'authToken'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
													'datatype'    => 'array',
													'paramName'   => 'data'
											)),
											new Zend_CodeGenerator_Php_Docblock_Tag_Return(
													array('datatype'    => 'array')
											)
									)
							))
					));
				}

				$amfClass->setMethod(array(
						'name' => 'find' . $objectName,
						'visibility' => 'public',
						'parameters' => array(
								array('name' => 'authToken'),
								array('name' => 'data', 'datatype' => 'array')
						),
						'body' => 'return $this->synch($authToken, \''.$table.'\', \'Find\', $data);',
						'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
								'shortDescription' => 'Find existing ' . $objectName . ' entry by id.',
								'tags' => array(
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'string',
												'paramName'   => 'authToken'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'array',
												'paramName'   => 'data'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Return(
												array('datatype'    => 'array')
										)
								)
						))
				));

				$amfClass->setMethod(array(
						'name' => 'list' . $objectName,
						'visibility' => 'public',
						'parameters' => array(
								array('name' => 'authToken'),
								array('name' => 'options', 'datatype' => 'array')
						),
						'body' => 'return $this->synch($authToken, \''.$table.'\', \'List\', array(), $options);',
						'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
								'shortDescription' => 'key > value list of ' . $objectName . ' entries.',
								'tags' => array(
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'string',
												'paramName'   => 'authToken'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'array',
												'paramName'   => 'options'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Return(
												array('datatype'    => 'array')
										)
								)
						))
				));

				$amfClass->setMethod(array(
						'name' => 'grid' . $objectName,
						'visibility' => 'public',
						'parameters' => array(
								array('name' => 'authToken'),
								array('name' => 'options', 'datatype' => 'array')
						),
						'body' => 'return $this->synch($authToken, \''.$objectName.'\', \'Grid\', array(), $options);',
						'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
								'shortDescription' => 'Full data grid of ' . $objectName . ' entries.',
								'tags' => array(
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'string',
												'paramName'   => 'authToken'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
												'datatype'    => 'array',
												'paramName'   => 'options'
										)),
										new Zend_CodeGenerator_Php_Docblock_Tag_Return(
												array('datatype'    => 'array')
										)
								)
						))
				));
			}

			#-> Save class to file.
			$path = ReGen_Util_FileLocation::getAmfInterfaceLocation($modelClassName);
			$file = new Zend_CodeGenerator_Php_File(array(
					'classes' => array($amfClass)
			));
			file_put_contents($path, $file->generate());
		}
	}


	/**
	 * Create table model file.
	 *
	 * @return Nirph_Db_Table_Abstract
	 */
	private function _createModel($tableName, $table_name, $fieldMeta, $tables, $dependants, $dependancies)
	{
		#-> Grab field meta for this table.
		$fields = $fieldMeta[$table_name];

		#-> Tools.
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();

		#-> Work through the meta data.
		$fieldMap = array();
		$tableFlags = 0;
		$tableReadable = array();
		$tableArchiveField = false;
		$references = array();
		foreach ($fields as $key => $meta)
		{
			$methodProperty = str_replace('_', '', $filter->filter($key));
			$propertyName = $key;
			$fieldMap[$key] = $methodProperty;

			if (0 < strlen($meta['DEFAULT']))
			{
				if ("'" == substr($meta['DEFAULT'], 1, 1))
				{
					$fields[$key]['DEFAULT'] = substr($meta['DEFAULT'], 2, strlen($meta['DEFAULT']) - 4);
				}
			}
			$flags = 0;
			$readable = array();

			#-> Pseudo delete functionality.
			if ('archived' == $key
					|| 'is_archived' == $key)
			{
				$tableFlags |= TABLE_NO_DELETE | TABLE_PSEUDO_DELETE;
				$tableReadable[] = 'TABLE_NO_DELETE';
				$tableReadable[] = 'TABLE_PSEUDO_DELETE';
				$tableArchiveField = $key;
			}

			#-> Default primary.
			if ('id' == $key)
			{
				$flags |= FIELD_AUTOKEY;
				$readable[] = 'FIELD_AUTOKEY';
			}

			#-> Required.
			if (!$meta['NULLABLE']
					&& is_null($meta['DEFAULT'])
					/* && '_id' == substr($key, -3) */) // not sure why its here, uncommenting to see what happens :)
			{
				$flags |= FIELD_REQUIRED;
				$readable[] = 'FIELD_REQUIRED';
			}

			#-> Auto-date magic.
			if ('created_timestamp' == $key
					|| 'created_date' == $key
					|| 'created' == $key)
			{
				if (strtoupper($meta['DATA_TYPE']) == 'DATETIME'
        				|| strtoupper($meta['DATA_TYPE']) == 'TIMESTAMP')
				{
					$flags |= FIELD_INSERT_DATETIME;
					$readable[] = 'FIELD_INSERT_DATETIME';
				}
				elseif (strtoupper($meta['DATA_TYPE']) == 'DATE')
				{
					$flags |= FIELD_INSERT_DATE;
					$readable[] = 'FIELD_INSERT_DATE';
				}
				else
				{
					$flags |= FIELD_INSERT_TIMESTAMP;
					$readable[] = 'FIELD_INSERT_TIMESTAMP';
				}
			}
			if ('modified_timestamp' == $key
					|| 'modified_date' == $key
					|| 'modified' == $key
					|| 'updated_timestamp' == $key
					|| 'updated_date' == $key
					|| 'updated' == $key)
			{
				if (strtoupper($meta['DATA_TYPE']) == 'DATETIME'
        				|| strtoupper($meta['DATA_TYPE']) == 'TIMESTAMP')
				{
					$flags |= FIELD_UPDATE_DATETIME|FIELD_INSERT_DATETIME;
					$readable[] = 'FIELD_UPDATE_DATETIME';
				}
				elseif (strtoupper($meta['DATA_TYPE']) == 'DATE')
				{
					$flags |= FIELD_UPDATE_DATE|FIELD_INSERT_DATE;
					$readable[] = 'FIELD_UPDATE_DATE';
				}
				else
				{
					$flags |= FIELD_UPDATE_TIMESTAMP|FIELD_INSERT_TIMESTAMP;
					$readable[] = 'FIELD_UPDATE_TIMESTAMP';
				}
			}
			$fields[$key]['FLAGS'] = $flags;
			$fields[$key]['FLAG_LIST'] = implode(' | ', $readable);

			#-> Check for references
			if ('_id' == substr($key, -3))
			{
				#-> Get rid of the _id bit.
				$parts = explode('_', $key);
				array_pop($parts);

				#-> Do we have a direct match?
				$search = implode('_', $parts);
				if (isset($tables[$search]))
				{
					$referenceRule = $filter->filter($search);
					$references[$referenceRule] = array(
							'columns'	   => $key,
							'refTableClass' => 'Table_' . $referenceRule,
							'refColumns'	=> 'id'
					);
				}
				else
				{
					#-> Disect and find it with backwards mini-matching.
					$search = '';
					$srchParts = array();
					while (!empty($parts))
					{
						array_unshift($srchParts, array_pop($parts));
						$search = implode('_', $srchParts);
						if (isset($tables[$search]))
						{
							$referenceRule = empty($parts)
								? $filter->filter($search)
								: $filter->filter(implode('_', $parts)) . $filter->filter($search);
							$refTable = $filter->filter($search);
							$references[$referenceRule] = array(
									'columns'	   => $key,
									'refTableClass' => 'Table_' . $refTable,
									'refColumns'	=> 'id'
							);
							break;
						}
					}
				}
			}
		}


		#-> Create table class.
		$tableClass = new Zend_CodeGenerator_Php_Class();
		$docblock = new Zend_CodeGenerator_Php_Docblock(array(
				'shortDescription' => 'Table model for ' . $table_name
		));
		$modelClassName = 'Table_' . $tableName;
		$tableClass->setName($modelClassName)
			->setDocblock($docblock)
			->setExtendedClass('Struct_Abstract_ModelTable');


		#-> Prepare default record array and label format.
		$newRecord = array();
		$stringLabel = false;
		$nameLabel = false;
		$stringLabelForeign = false;
		$nameLabelForeign = false;
		foreach ($fields as $key => $meta)
		{
			if ('varchar' == $meta['DATA_TYPE'])
			{
				!$stringLabel && $stringLabel = "[$key]";
				!$stringLabelForeign && $stringLabelForeign = "[$table_name" . '_' ."$key]";
				if (!$nameLabel && 3 < strlen($key) && 'name' == substr($key, -4))
				{
					$nameLabel = "[$key]";
					$nameLabelForeign = "[$table_name" . '_' ."$key]";
				}
			}
			$newRecord[$key] = (isset($meta['DEFAULT']) && '' != $meta['DEFAULT'])
				? $meta['DEFAULT']
				: new Zend_CodeGenerator_Php_Property_DefaultValue("null");
		}
		$label = ($nameLabel)
			? $nameLabel
			: $stringLabel;
		$label = ($label)
			? $label
			: '';
		$labelForeign = ($nameLabelForeign)
			? $nameLabelForeign
			: $stringLabelForeign;
		$labelForeign = ($labelForeign)
			? $labelForeign
			: '';

		#-> Set class properties.
		$tableClass->setProperty(array(
				'name' => '_name',
				'visibility' => 'protected',
				'defaultValue' => $table_name,
				'docblock' => array(
						'shortDescription' => 'Database table name.'
				)
		));
		$tableClass->setProperty(array(
				'name' => '_referenceMap',
				'visibility' => 'protected',
				'defaultValue' => $references,
				'docblock' => array(
						'shortDescription' => 'Data associations to other tables.'
				)
		));
		$tableClass->setProperty(array(
				'name' => '_dependentTables',
				'visibility' => 'protected',
				'defaultValue' => $dependants,
				'docblock' => array(
						'shortDescription' => 'Tables dependant on this one.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'dependancyChain',
				'visibility' => 'protected',
				'defaultValue' => $dependancies,
				'docblock' => array(
						'shortDescription' => 'Data dependancy chain.'
				)
		));
		$tableClass->setProperty(array(
				'name' => '_metadata',
				'visibility' => 'protected',
				'defaultValue' => $fields,
				'docblock' => array(
						'shortDescription' => 'Field meta data.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'tableFlags',
				'visibility' => 'protected',
				'defaultValue' => $tableFlags,
				'docblock' => array(
						'shortDescription' => (!empty($tableReadable)
								? implode(' | ', $tableReadable)
								: 'No flags')
				)
		));
		$tableClass->setProperty(array(
				'name' => 'archiveField',
				'visibility' => 'protected',
				'defaultValue' => $tableArchiveField,
				'docblock' => array(
						'shortDescription' => 'Field used to flag entry as archived.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'fieldNames',
				'visibility' => 'protected',
				'defaultValue' => $fieldMap,
				'docblock' => array(
						'shortDescription' => 'Field db-name to code-name mapping.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'newRow',
				'visibility' => 'protected',
				'defaultValue' => $newRecord,
				'docblock' => array(
						'shortDescription' => 'Default values for new data entry.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'labelFormat',
				'visibility' => 'protected',
				'defaultValue' => $label,
				'docblock' => array(
						'shortDescription' => 'Label format for list/dropdown display.'
				)
		));
		$tableClass->setProperty(array(
				'name' => 'labelFormatForeign',
				'visibility' => 'protected',
				'defaultValue' => $labelForeign,
				'docblock' => array(
						'shortDescription' => 'Label format for list/dropdown display.'
				)
		));

		#-> Save class to file.
		$path = ReGen_Util_FileLocation::getTableModelLocation($tableName);
	  	$file = new Zend_CodeGenerator_Php_File(array(
				'classes' => array($tableClass)
		));
		file_put_contents($path, $file->generate());

		#-> All done -oO-
		return $references;
	}


	private function _createDataObject($tableName, $table_name, $fieldMeta, $unique)
	{
		#-> Prepare validation meta.
		$validate = array();
		$skipFields = array(
				'created',
				'updated',
				'archive'
				);
		$contactNumFields = array(
				'mobile',
				'telephone',
				'office',
				'fax'
				);
		foreach ($fieldMeta[$table_name] as $field => $meta)
		{
			if (in_array($field, $skipFields))
			{
				continue;
			}
			$validate[$field] = array(
					'required' => !$meta['NULLABLE'] && is_null($meta['DEFAULT']),
					'validators' => array()
					);
			if ('id' == $field)
			{
				$validate[$field]['required'] = false;
				$validate[$field]['validators'][] = array(
						'type' => 'Digits',
						'params' => array()
						);
				continue;
			}
			if ('archived' == $field)
			{
				$validate[$field]['validators'][] = array(
						'type' => 'InArray',
						'params' => array('0', '1')
						);
				continue;
			}
			if ('email' == $field)
			{
				$validate[$field]['validators'][] = array(
						'type' => 'EmailAddress',
						'params' => array()
						);
				continue;
			}
			if ('ip' == $field)
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Ip',
						'params' => array()
						);
				continue;
			}
			if ('company_registration_number' == $field)
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Regex',
						'params' => array('pattern' => '/^\d{4}\/\d{6}\/\d{2}$/')
				);
			}
			if (in_array($field, $contactNumFields))
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => 16)
						);
				$validate[$field]['validators'][] = array(
						'type' => 'Digits',
						'params' => array()
						);
				continue;
			}


			if ('datetime' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Regex',
						'params' => array('pattern' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01]) (0?[0-9]|1[0-9]|2[0-4]):(0?[0-9]|[1-5][0-9])(:(0?[0-9]|[1-5][0-9]))?$/')
						);
				continue;
			}
			if ('date' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Regex',
						'params' => array('pattern' => '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/')
						);
				continue;
			}
			if ('time' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Regex',
						'params' => array('pattern' => '/^(0?[0-9]|1[0-9]|2[0-4]):(0?[0-9]|[1-5][0-9])(:(0?[0-9]|[1-5][0-9]))?$/')
						);
				continue;
			}
			if ('year' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 3000)
						);
				continue;
			}
			if ('decimal' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Float',
						'params' => array()
						);
				continue;
			}
			if ('tinyint' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = is_null($meta['UNSIGNED'])
					? array(
						'type' => 'Between',
						'params' => array('min' => -128, 'max' => 127)
						)
					: array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 255)
						);
				continue;
			}
			if ('smallint' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = is_null($meta['UNSIGNED'])
					? array(
						'type' => 'Between',
						'params' => array('min' => -32768, 'max' => 32767)
						)
					: array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 65535)
						);
				continue;
			}
			if ('mediumint' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = is_null($meta['UNSIGNED'])
					? array(
						'type' => 'Between',
						'params' => array('min' => -8388608, 'max' => 8388607)
						)
					: array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 16777215)
						);
				continue;
			}
			if ('int' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = is_null($meta['UNSIGNED'])
					? array(
						'type' => 'Between',
						'params' => array('min' => -2147483648, 'max' => 2147483647)
						)
					: array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 4294967295)
						);
				continue;
			}
			if ('bigint' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = is_null($meta['UNSIGNED'])
					? array(
						'type' => 'Between',
						'params' => array('min' => -9223372036854775808, 'max' => 9223372036854775807)
						)
					: array(
						'type' => 'Between',
						'params' => array('min' => 0, 'max' => 18446744073709551615)
						);
				continue;
			}
			if ('float' == $meta['DATA_TYPE']
					|| 'double' == $meta['DATA_TYPE']
					|| 'real' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'Float',
						'params' => array()
						);
				continue;
			}
			if ('char' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => $meta['LENGTH'])
						);
				continue;
			}
			if ('varchar' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => $meta['LENGTH'])
						);
				continue;
			}
			if ('tinytext' == $meta['DATA_TYPE']
					|| 'tinyblob' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => 255)
						);
				continue;
			}
			if ('text' == $meta['DATA_TYPE']
					|| 'blob' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => 65535)
						);
				continue;
			}
			if ('mediumtext' == $meta['DATA_TYPE']
					|| 'mediumblob' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => 16777215)
						);
				continue;
			}
			if ('longtext' == $meta['DATA_TYPE']
					|| 'longblob' == $meta['DATA_TYPE'])
			{
				$validate[$field]['validators'][] = array(
						'type' => 'StringLength',
						'params' => array('max' => 4294967295)
						);
				continue;
			}
			if ('enum' == substr($meta['DATA_TYPE'], 0, 4))
			{
				$validate[$field]['validators'][] = array(
						'type' => 'InArray',
						'params' => explode(',', str_replace(array('enum(',')','\\',"'"), '', $meta['DATA_TYPE']))
						);
				continue;
			}
		}

		#-> Create object class.
		$objectClass = new Zend_CodeGenerator_Php_Class();
		$docblock = new Zend_CodeGenerator_Php_Docblock(array(
				'shortDescription' => 'DataAccess Value Object for table ' . $table_name
		));
		$objectClass->setName('Object_' . $tableName)
			->setDocblock($docblock)
			->setExtendedClass('Struct_Abstract_DataAccess');

		#-> Set class properties.
		$objectClass->setProperty(array(
				'name' => '_eventNamespace',
				'visibility' => 'protected',
				'defaultValue' => $tableName,
				'docblock' => array(
						'shortDescription' => 'Namespace used for raising events.'
				)
		));
		$objectClass->setProperty(array(
				'name' => '_table',
				'visibility' => 'protected',
				'defaultValue' => $table_name,
				'docblock' => array(
						'shortDescription' => 'Table this value object owns and may directly modify.'
				)
		));
		$objectClass->setProperty(array(
				'name' => '_uniqueIdentifier',
				'visibility' => 'protected',
				'defaultValue' => $unique,
				'docblock' => array(
						'shortDescription' => 'Unique identification field(s).'
				)
		));
		$objectClass->setProperty(array(
				'name' => '_validation',
				'visibility' => 'protected',
				'defaultValue' => $validate,
				'docblock' => array(
						'shortDescription' => 'Validation meta-data.'
				)
		));

		#-> Save class to file.
		$path = ReGen_Util_FileLocation::getObjectLocation($tableName);
		if (file_exists($path))
		{
			@unlink($path);
		}
		$file = new Zend_CodeGenerator_Php_File(array(
				'classes' => array($objectClass)
		));
		file_put_contents($path, $file->generate());

		#-> All done -oO-
		return $validate;
	}


	private function _createDataController($tableName, $table_name, $fieldMeta, $reference, $db, $dependancies)
	{
		#-> Create controller class.
		$objectClass = new Zend_CodeGenerator_Php_Class();
		$docblock = new Zend_CodeGenerator_Php_Docblock(array(
				'shortDescription' => 'Table model for ' . $table_name
		));
		$module = ucfirst(ReGen_Registry::getContext('entityName'));
		$prePend = 'Default' == $module
			? ''
			: $module . '_';
		$postPend = 'Manager' == $module
			? ''
			: '';
		$objectClass->setName($prePend . $tableName . $postPend . 'Controller')
			->setDocblock($docblock)
			->setExtendedClass('Struct_Abstract_Controller');
		$firstDisplayField = $this->getFirstGridDisplayField($tableName, $fieldMeta, $reference, $db);

		#-> Prepare grid meta.
		$gridSetupMethod = 'setup' . ucfirst($tableName) . 'Grid';
		$cmToUnder = new Zend_Filter_Word_CamelCaseToUnderscore();
		$i = 0;
		$fields = array();
		$fieldToTable = array();
		$fieldLabel = array();
		foreach ($fieldMeta as $field => $meta)
		{
			$i++;
			if ((1 < $i || 'varchar' == $meta['DATA_TYPE'])
					&& 'text' != $meta['DATA_TYPE']
					&& 'mediumtext' != $meta['DATA_TYPE']
					&& 'tinytext' != $meta['DATA_TYPE']
					&& 'blob' != $meta['DATA_TYPE']
					&& 'archived' != $field
					&& 'password' != $field
					&& 'password_salt' != $field
					&& '_mime_type' != substr($field, -10))
			{
				if ('archived' == $field || 'password' == $field || 'password_salt' == $field)
				{
					continue;
				}
				if ('_id' != substr($field, -3))
				{
					$fields[$table_name . '_' . $field] = $field;
					$fieldToTable[$table_name . '_' . $field] = $table_name;
					$fieldLabel[$table_name . '_' . $field] = $field;
				}
				else
				{
					foreach ($reference as $refName => $refMap)
					{
						if ($field == $refMap['columns'])
						{
							$frgnTable = strtolower(
									$cmToUnder->filter(
											str_replace('Table_', '', $refMap['refTableClass'])
											)
									);
							$ref_table_prepend = str_replace(
									$frgnTable . '_id',
									'',
									$field
									);
							$frgnMeta = $db->describeTable($frgnTable);
							$frgnTable = $ref_table_prepend . $frgnTable;
							foreach ($frgnMeta as $fldName => $fldMeta)
							{
								if ('varchar' == $fldMeta['DATA_TYPE'])
								{
									$fields[$frgnTable . '_' . $fldName] = $fldName;
									$fieldToTable[$frgnTable . '_' . $fldName] = $frgnTable;
									$fieldLabel[$frgnTable . '_' . $fldName] = $field;
									break 2;
								}
							}
						}
					}
				}
			}
			if (5 == count($fields))
			{
				break;
			}
		}
		$gridName = lcfirst($tableName) . 'Grid';
		$module = ucfirst(ReGen_Registry::getContext('entityName'));
		$lcModule = strtolower($module);
		$prePend = 'Default' == $module
			? ''
			: $module . '_';
		$postPend = 'Manager' == $module
			? ''
			: '';
		$postpend = !empty($postPend)
			? '-' . strtolower($postPend)
			: '';
		$toCamel = new Zend_Filter_Word_UnderscoreToCamelCase();
		$toDash = new Zend_Filter_Word_UnderscoreToDash();
		$toSep = new Zend_Filter_Word_CamelCaseToSeparator();
		$dashTable = $prePend . $toDash->filter(str_replace('__', '_', $table_name)) . $postpend;
		$dashGrid = $prePend . $toDash->filter(str_replace('__', '_', $table_name)) . '-grid';
		$gridSetupBody = '$sqf = new Struct_Util_SmartQueryFilter();' . "\n"
							. '$response = $sqf->handleGrid(' . "\n"
							. "\t" . '$this->getRequest(), false, $this->_defaultObjectName,' . "\n"
							. "\t" . 'array(), // default filters' . "\n"
							. "\t" . 'array(), // default order' . "\n"
							. "\t" . 'array(), // exclude' . "\n"
							. "\t" . 'array(), // chain' . "\n"
							. "\t" . '10, "Json"' . "\n"
							. ');' . "\n"
							. '$this->view->result = array("' . ucfirst($tableName) . '" => $response->result);' . "\n"
							. '$this->view->data   = array("' . ucfirst($tableName) . '" => $response->data);';
		$i = 1;
		foreach ($fields as $table_field => $field)
		{
			$class = '';
			switch ($i)
			{
				case 3:
				case 4:
					$class = ''; //'hidden-phone';
					break;
				case 5:
					$class = ''; //'visible-desktop';
					break;
			}
			$label = str_replace(array('_lu_open_list_id', '_lu_locked_list_id'), '', $fieldLabel[$table_field]);
			$label = '_id' == substr($label, -3)
				? substr($label, 0, strlen($label) - 3)
				: $label;
			$label = 'lu_' == substr($label, 0, 3)
				? substr($label, 3, strlen($label) - 3)
				: $label;
			$label = $toSep->filter($toCamel->filter($label));
			$searchField = $fieldToTable[$table_field] . '.' . $field;
			/* $gridSetupBody .= "\t->useField('".$table_field."', '".$label."', '".$searchField."')";
			$gridSetupBody .= $i == count($fields)
				? ';'
				: "\n"; */
			$i++;
		}


		#-> Set class properties.
		$objectClass->setProperty(array(
				'name' => '_defaultObjectName',
				'visibility' => 'protected',
				'defaultValue' => 'Object_' . $tableName,
				'docblock' => array(
						'shortDescription' => 'Default object for DataAccess methods.'
				)
		));
		$objectClass->setProperty(array(
				'name' => '_sessionNamespace',
				'visibility' => 'protected',
				'defaultValue' => $tableName,
				'docblock' => array(
						'shortDescription' => 'Default session namespace for the view.'
				)
		));

		#-> Add default data access methods.
		$dataContext = ReGen_Registry::getContext('dataContext');
		$dataContextWhitelist = ReGen_Registry::getContext('dataContextWhitelist');

		$indexBody = '$this->'.$gridSetupMethod.'();';
		foreach ($reference as $refName => $refMap)
		{
			$field = $refMap['columns'];
			if (in_array($field, $dataContext) && !in_array($table_name, $dataContextWhitelist))
			{
				continue;
			}
			if (isset($dependancies[$refName]))
			{
				$parentId = count($dependancies[$refName]) - 1;
				$refMap = $dependancies[$refName][$parentId];
				$objectName = str_replace('Table_', '', $refMap['refTableClass']);
				$indexBody .= "\n" . '$this->dataContext = "list' . $objectName . '";' . "\n"
						. '$this->listDataReturnView("Object_' . $objectName . '");';
			}
			else
			{
				$objectName = str_replace('Table_', '', $refMap['refTableClass']);
				$indexBody .= "\n" . '$this->dataContext = "list' . $refName . '";' . "\n"
						. '$this->listDataReturnView("Object_' . $objectName . '");';
			}
		}

		$itemName = lcfirst($tableName);
		$objectClass->setMethod(array(
				'name' => 'init',
				'visibility' => 'public',
				'parameters' => array(),
				'body' => 'if (!Struct_Registry::isAuthenticated())' . "\n"
								. '{' . "\n"
								. "\t" . 'header("Location: /login");' . "\n"
								. "\t" . 'exit();' . "\n"
								. '}',
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Action controller initializer.'
						))
				));
		$objectClass->setMethod(array(
				'name' => $gridSetupMethod,
				'visibility' => 'protected',
				'parameters' => array(),
				'body' => $gridSetupBody,
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Setup data grid on default value object.'
						))
				));
		$objectClass->setMethod(array(
				'name' => 'indexAction',
				'visibility' => 'public',
				'parameters' => array(),
				'body' => $indexBody,
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Default page view for this theme.'
						))
				));
		$objectClass->setMethod(array(
				'name' => $itemName . 'GridAction',
				'visibility' => 'public',
				'parameters' => array(),
				'body' => '$this->_helper->layout()->disableLayout();' . "\n"
						. '$this->'.$gridSetupMethod.'();',
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Retrieve data grid for display.'
						))
				));
		if (!empty($dependancies))
		{
			$depBody = '$this->listDependancyDataReturnSelectOptions(array(' . "\n";
			$toSep = new Zend_Filter_Word_CamelCaseToSeparator();
			foreach ($dependancies as $refName => $refs)
			{
				array_unshift($refs, $reference[$refName]);
				$parentId = count($refs) - 1;
				for ($i = 0; $i < $parentId; $i++)
				{
					$refMap = $refs[$i];
					$depItemName = str_replace('Table_', '', $refMap['refTableClass']);
					$label = str_replace('Lu ', '', $toSep->filter($depItemName));
					$object =  "Object_$depItemName";
					$depBody .= "\t\t" . '"' . $label . '" => "' . $object . '",' . "\n";
				}
			}
			$depBody .= "\t\t" . '));';
			$objectClass->setMethod(array(
					'name' => 'listDependancySelectAction',
					'visibility' => 'public',
					'parameters' => array(),
					'body' => $depBody,
					'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
							'shortDescription' => 'Handle data dependancy dropdown lists.'
							))
					));
		}
		/* $objectClass->setMethod(array(
				'name' => $itemName . 'SaveAction',
				'visibility' => 'public',
				'parameters' => array(),
				'body' => '$this->saveDataReturnJson();',
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Sava data entry to database.'
						))
				));
		$objectClass->setMethod(array(
				'name' => $itemName . 'DeleteAction',
				'visibility' => 'public',
				'parameters' => array(),
				'body' => '$this->removeDataReturnJson();',
				'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
						'shortDescription' => 'Remove data entry from database.'
						))
				)); */

		#-> Save class to file.
		$path = ReGen_Util_FileLocation::getControllerLocation($tableName . $postPend);
		$file = new Zend_CodeGenerator_Php_File(array(
				'classes' => array($objectClass)
		));
		file_put_contents($path, $file->generate());


		#-> All done -oO-
		return true;
	}

	private function _createViews($itemName, $table_name, $fieldMeta, $reference, $db, $dependancies)
	{
		$this->_createIndexView($itemName, $table_name, $fieldMeta);
		$this->_createGridView($itemName, $table_name, $fieldMeta, $reference, $db);
		$this->_createModalView($itemName, $table_name, $fieldMeta, $reference, $dependancies);
	}

	private function _createIndexView($itemName, $table_name, $fieldMeta)
	{
		#-> Prepare code
		$module = ucfirst(ReGen_Registry::getContext('entityName'));
		$lcModule = strtolower($module);
		$prePend = 'Default' == $module
			? ''
			: $module . '_';
		$postPend = 'Manager' == $module
			? ''
			: '';
		$postpend = !empty($postPend)
			? '-' . strtolower($postPend)
			: '';
		$filter = new Zend_Filter_Word_CamelCaseToSeparator();
		$Item_Name = str_replace('Lu ', '', $filter->filter($itemName));
		$camToDash = new Zend_Filter_Word_CamelCaseToDash();
		$camToScore = new Zend_Filter_Word_CamelCaseToUnderscore();
		$search = array('[table_name]', '[Item Name]', '[ItemName]', '[itemName]', '[item-name]', '[item_name]', '[module]', '[postpend]');
		$replace = array($table_name, $Item_Name, $itemName, lcfirst($itemName), strtolower($camToDash->filter($itemName)), strtolower($camToScore->filter($itemName)), $lcModule, $postpend);

		#-> Create file.
		$path = ReGen_Util_FileLocation::getViewLocation($itemName . $postPend, 'index');
		file_put_contents($path, str_replace($search, $replace, $this->codeIndex));


		#-> All done -oO-
		return true;
	}

	private function getFirstGridDisplayField($itemName, $fieldMeta, $reference, $db)
	{
		$fields = array();
		$fieldToTable = array();
		$i = 0;
		$cmToUnder = new Zend_Filter_Word_CamelCaseToUnderscore();
		$table_name = strtolower($cmToUnder->filter($itemName));
		foreach ($fieldMeta as $field => $meta)
		{
			$i++;
			if ((1 < $i || 'varchar' == $meta['DATA_TYPE'])
					&& 'text' != $meta['DATA_TYPE']
					&& 'mediumtext' != $meta['DATA_TYPE']
					&& 'tinytext' != $meta['DATA_TYPE']
					&& 'blob' != $meta['DATA_TYPE']
					&& 'archived' != $field
					&& 'password' != $field
					&& 'password_salt' != $field
					&& '_mime_type' != substr($field, -10))
			{
				if ('_id' != substr($field, -3))
				{
					return $table_name . '_' . $field;
				}
				else
				{
					foreach ($reference as $refName => $refMap)
					{
						if ($field == $refMap['columns'])
						{
							$frgnTable = strtolower(
									$cmToUnder->filter(
											str_replace('Table_', '', $refMap['refTableClass'])
									)
							);
							$frgnMeta = $db->describeTable($frgnTable);
							foreach ($frgnMeta as $fldName => $fldMeta)
							{
								if ('varchar' == $fldMeta['DATA_TYPE'])
								{
									return $frgnTable . '_' . $fldName;
								}
							}
						}
					}
				}
			}
		}
		return '';
	}

	private function _createGridView($itemName, $table_name, $fieldMeta, $reference, $db)
	{
		#-> What fields will we display?
		$skipFields = array('password', 'password_salt');
		foreach ($skipFields as $field)
		{
			unset($fieldMeta[$field]);
		}
		$module = ucfirst(ReGen_Registry::getContext('entityName'));
		$prePend = 'Default' == $module
			? ''
			: $module . '_';
		$postPend = 'Manager' == $module
			? ''
			: '';
		$fields = array();
		$fieldToTable = array();
		$fieldLabel = array();
		$i = 0;
		$cmToUnder = new Zend_Filter_Word_CamelCaseToUnderscore();
		foreach ($fieldMeta as $field => $meta)
		{
			$i++;
			if ((1 < $i || 'varchar' == $meta['DATA_TYPE'])
					&& 'text' != $meta['DATA_TYPE']
					&& 'mediumtext' != $meta['DATA_TYPE']
					&& 'tinytext' != $meta['DATA_TYPE']
					&& 'blob' != $meta['DATA_TYPE']
					&& 'archived' != $field
					&& 'password' != $field
					&& 'password_salt' != $field
					&& '_mime_type' != substr($field, -10))
			{
				if ('archived' == $field || 'password' == $field || 'password_salt' == $field)
				{
					continue;
				}
				if ('_id' != substr($field, -3))
				{
					$fields[$field] = $field;
					$fieldToTable[$field] = $table_name;
					$fieldLabel[$field] = $field;
				}
				else
				{
					foreach ($reference as $refName => $refMap)
					{
						if ($field == $refMap['columns'])
						{
							$frgnTable = strtolower(
									$cmToUnder->filter(
											str_replace('Table_', '', $refMap['refTableClass'])
											)
									);
							$frgnMeta = $db->describeTable($frgnTable);
							foreach ($frgnMeta as $fldName => $fldMeta)
							{
								if ('varchar' == $fldMeta['DATA_TYPE'])
								{
									$fields[$frgnTable . '_' . $fldName] = $fldName;
									$fieldToTable[$frgnTable . '_' . $fldName] = $frgnTable;
									$fieldLabel[$frgnTable . '_' . $fldName] = $field;
									break 2;
								}
							}
						}
					}
				}
			}
			if (5 == count($fields))
			{
				break;
			}
		}

		#-> Prepare code
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$search = array('[ItemName]', '[itemName]', '[item-name]', '[columns]');
		$replace = array($itemName, lcfirst($itemName), strtolower($filter->filter($itemName)), count($fields));
		$html = '';
		$html .= str_replace($search, $replace, $this->codeGridHeader);
		$toCamel = new Zend_Filter_Word_UnderscoreToCamelCase();
		$toSep = new Zend_Filter_Word_CamelCaseToSeparator();
		$i = 1;
		foreach ($fields as $table_field => $field)
		{
			$class = '';
			switch ($i)
			{
				case 3:
				case 4:
					$class = ''; //'hidden-phone';
					break;
				case 5:
					$class = ''; //'visible-desktop';
					break;
			}
			$label = str_replace(array('_lu_open_list_id', '_lu_locked_list_id'), '', $fieldLabel[$table_field]);
			$label = '_id' == substr($label, -3)
				? substr($label, 0, strlen($label) - 3)
				: $label;
			$label = 'lu_' == substr($label, 0, 3)
				? substr($label, 3, strlen($label) - 3)
				: $label;
			$label = $toSep->filter($toCamel->filter($label));
			$html .= str_replace(
					array('[itemName]', '[Field Name]', '[field_name]', '[class]'),
					array(lcfirst($itemName), $label, "$table_name.$field", $class),
					$this->codeGridColumnHeader
					);
			$i++;
		}
		$html .= str_replace($search, $replace, $this->codeGridSearchPrep);
		$i = 1;
		foreach ($fields as $table_field => $field)
		{
			$class = '';
			switch ($i)
			{
				case 3:
				case 4:
					$class = ''; //'hidden-phone';
					break;
				case 5:
					$class = ''; //'visible-desktop';
					break;
			}
			$html .= str_replace(
					array('[class]', '[ItemName]', '[itemName]', '[foreign_table]', '[field_name]'),
					array($class, $itemName, lcfirst($itemName), $fieldToTable[$table_field], $field),
					$this->codeGridColumnSearch
					);
			$i++;
		}
		$html .= str_replace($search, $replace, $this->codeGridRowPrep);
		$i = 1;
		foreach ($fields as $table_field => $field)
		{
			$class = '';
			switch ($i)
			{
				case 3:
				case 4:
					$class = ''; //'hidden-phone';
					break;
				case 5:
					$class = ''; //'visible-desktop';
					break;
			}
			$html .= str_replace(
					array('[field_name]', '[class]'),
					array($table_field, $class),
					$this->codeGridRowData
					);
			$i++;
		}
		$html .= str_replace($search, $replace, $this->codeGridFooter);

		#-> Create file.
		$path = ReGen_Util_FileLocation::getViewLocation($itemName . $postPend, $filter->filter($itemName) . '-grid');
		file_put_contents($path, $html);


		#-> All done -oO-
		return true;
	}

	private function _createModalView($itemName, $table_name, $fieldMeta, $reference, $dependancies)
	{
//Struct_Debug::errorLog($table_name, $dependancies);
		#-> Prepare code
		#-> What fields will we display?
		$skipFields = array('password', 'password_salt', 'created', 'updated', 'archived');
		foreach ($skipFields as $field)
		{
			unset($fieldMeta[$field]);
		}
		$module = ucfirst(ReGen_Registry::getContext('entityName'));
		$prePend = 'Default' == $module
			? ''
			: $module . '_';
		$postPend = 'Manager' == $module
			? ''
			: '';
		$filter = new Zend_Filter_Word_CamelCaseToDash();
		$item_name = strtolower($filter->filter($itemName));
		$filter = new Zend_Filter_Word_CamelCaseToSeparator();
		$Item_Name = $filter->filter($itemName);
		$search = array('[ItemName]', '[itemName]', '[item-name]', '[Item Name]');
		$replace = array($itemName, lcfirst($itemName), $item_name, $Item_Name);

		#-> Prepare code
		$extraCols = 0;
		foreach ($dependancies as $refName => $refs)
		{
			$extraCols += count($refs);
		}
		$html = '';
		$jsIncludes = '';
		$js = '';
		$cols = count($fieldMeta) - 1 + $extraCols;
		$width = $cols > 6
			? 'modal-wide modal-up'
			: 'modal-thinner';
		$height = $cols > 6
			? 'modal-high'
			: '';
		$span = $cols > 6
			? 6
			: 12;
		$modHeader = str_replace(
				array('[modal-width]', '[modal-height]'),
				array($width, $height),
				$this->codeModalHeader
				);
		$html .= str_replace($search, $replace, $modHeader);
		$modGroup = str_replace(
				array('[counter]', '[span]'),
				array(1, $span),
				$this->codeModalBodyGroup
				);
		$html .= str_replace($search, $replace, $modGroup);
		$midField = $cols > 6
			? ceil($cols / 2) + 1
			: $cols + 2;
		$js .= str_replace($search, $replace, $this->codeModalJsHeader);

		#-> Prepare fields.
		$toCamel = new Zend_Filter_Word_UnderscoreToCamelCase();
		$toSep = new Zend_Filter_Word_CamelCaseToSeparator();
		$toDash = new Zend_Filter_Word_CamelCaseToDash();
		$dataContext = ReGen_Registry::getContext('dataContext');
		$dataContextWhitelist = ReGen_Registry::getContext('dataContextWhitelist');
		$colNum = 1;
		foreach ($fieldMeta as $field => $meta)
		{
			$label = $toSep->filter($toCamel->filter(str_replace(array('_lu_open_list_id', '_lu_locked_list_id', 'lu_'), '', $field)));
			if ('id' == $field)
			{
				continue;
			}
			if (in_array($field, $dataContext) && !in_array($table_name, $dataContextWhitelist))
			{
				continue;
			}
			if ($colNum == $midField)
			{
				$html .= '</div>';
				$modGroup = str_replace(
						array('[counter]', '[span]'),
						array(2, 6),
						$this->codeModalBodyGroup
				);
				$html .= str_replace($search, $replace, $modGroup);
			}
			$required = (false == $meta['NULLABLE'] && is_null($meta['DEFAULT']))
				? 'required'
				: '';
			$label = ' Id' == substr($label, -3)
				? substr($label, 0, strlen($label) - 3)
				: $label;
			$label = empty($required)
				? $label
				: $label . ' *';
			$ForeignItem = '';
			switch ($meta['DATA_TYPE'])
			{
				case 'int':
				case 'smallint':
				case 'mediumint':
					if ('_id' == substr($field, -3))
					{
						#-> Dropdown list populated by other table.
						$validation = !empty($required)
							? "required"
							: '';
						$attributes = '';
						foreach ($reference as $refName => $refMap)
						{
							if ($field == $refMap['columns'])
							{
								$ForeignItem = $refName;
							}
						}
						if (!empty($ForeignItem))
						{
							if (isset($dependancies[$ForeignItem]))
							{
	//Struct_Debug::errorLog("$table_name : $ForeignItem", $dependancies[$ForeignItem]);
								$Field_Name = $toSep->filter(
										$toCamel->filter(
												'_id' == substr($field, -3)
													? substr($field, 0, strlen($field) - 3)
													: $field
												)
										);
								$Field_Name = 'Lu ' == substr($Field_Name, 0, 3)
									? substr($Field_Name, 3, strlen($Field_Name) - 3)
									: $Field_Name;
								array_unshift($dependancies[$ForeignItem], $reference[$refName]);
								$parentId = count($dependancies[$ForeignItem]) - 1;
	//Struct_Debug::errorLog($parentId, $dependancies[$ForeignItem]);
								for ($i = $parentId; $i >= 0; $i--)
								{
									$refMap = $dependancies[$ForeignItem][$i];
									$parentList = 'list' . str_replace('Table_', '', $refMap['refTableClass']);
									$filterField = $dependancies[$ForeignItem][$i]['columns'];
									if ($i == $parentId)
									{
										#-> Parent.
										$childField = $dependancies[$ForeignItem][$i - 1]['columns'];
										$childItem  = $toSep->filter($toCamel->filter(
												str_replace(array('lu_', '_id'), '', $childField)
												));
										$parentTitle  = $toSep->filter($toCamel->filter(
												str_replace(array('lu_', '_id'), '', $filterField)
												));
										$depData = array(
												'dependant' => lcfirst($itemName) . '_' . $childField,
												'item' => $childItem,
												'title' => $parentTitle,
												'filterField' => $filterField
												);
										$instruct = "-- Select $parentTitle --";
										$optionHtml = '<?php echo $utilDisplay->buildDropdownOptions('
												. '$this->data["' . $parentList . '"], false); ?>' . "\n";
										$fieldHtml = str_replace($search, $replace, $this->codeModalFieldDependantSelect);
										$fieldHtml = str_replace(
												array('[Field Name]', '[field_name]', '[table_name]', '[options]', '[Label]',
														'[validation]', '[attributes]', '[dependancy-class]', '[data]', '[Select Instruction]'),
												array($parentTitle, $filterField, $table_name, $optionHtml, $parentTitle,
														$validation, $attributes, 'data-dependant-parent', Zend_Json::encode($depData), $instruct),
												$fieldHtml
												);
										$html .= $fieldHtml;

										$colNum++;
										if ($colNum == $midField)
										{
											$html .= '</div>';
											$modGroup = str_replace(
													array('[counter]', '[span]'),
													array(2, 6),
													$this->codeModalBodyGroup
											);
											$html .= str_replace($search, $replace, $modGroup);
										}
									}
									elseif ($i == 0)
									{
										#-> Child.
										$title  = $toSep->filter($toCamel->filter(
												str_replace(array('lu_', '_id'), '', $filterField)
												));
										$parentField = $dependancies[$ForeignItem][$i + 1]['columns'];
										$depData = array(
												'parent' => lcfirst($itemName) . '_' . $parentField
												);
										$instruct = "--- Select $parentTitle ---";
										$optionHtml = '';
										$fieldHtml = str_replace($search, $replace, $this->codeModalFieldDependantSelect);
										$fieldHtml = str_replace(
												array('[Field Name]', '[field_name]', '[table_name]', '[options]', '[Label]',
														'[validation]', '[attributes]', '[dependancy-class]', '[data]', '[Select Instruction]'),
												array($title, $filterField, $table_name, $optionHtml, $title,
														$validation, $attributes, 'data-dependant-child', Zend_Json::encode($depData), $instruct),
												$fieldHtml
												);
										$html .= $fieldHtml;
									}
									else
									{
										#-> Chain.
										$childField = $dependancies[$ForeignItem][$i - 1]['columns'];
										$childItem  = $toSep->filter($toCamel->filter(
												str_replace(array('lu_', '_id'), '', $childField)
												));
										$title  = $toSep->filter($toCamel->filter(
												str_replace(array('lu_', '_id'), '', $filterField)
												));
										$parentField = $dependancies[$ForeignItem][$i + 1]['columns'];
										$depData = array(
												'parent' => lcfirst($itemName) . '_' . $parentField,
												'dependant' => lcfirst($itemName) . '_' . $childField,
												'item' => $childItem,
												'title' => $title,
												'filterField' => $filterField
												);
										$instruct = "--- Select $parentTitle ---";
										$parentTitle = $title;
										$optionHtml = '';
										$fieldHtml = str_replace($search, $replace, $this->codeModalFieldDependantSelect);
										$fieldHtml = str_replace(
												array('[Field Name]', '[field_name]', '[table_name]', '[options]', '[Label]',
														'[validation]', '[attributes]', '[dependancy-class]', '[data]', '[Select Instruction]'),
												array($title, $filterField, $table_name, $optionHtml, $title,
														$validation, $attributes, 'data-dependant-chain', Zend_Json::encode($depData), $instruct),
												$fieldHtml
												);
										$html .= $fieldHtml;

										$colNum++;
										if ($colNum == $midField)
										{
											$html .= '</div>';
											$modGroup = str_replace(
													array('[counter]', '[span]'),
													array(2, 6),
													$this->codeModalBodyGroup
											);
											$html .= str_replace($search, $replace, $modGroup);
										}
									}
								}
							}
							else
							{
								$Field_Name = $toSep->filter(
										$toCamel->filter(
												'_id' == substr($field, -3)
													? substr($field, 0, strlen($field) - 3)
													: $field
												)
										);
								$Field_Name = 'Lu ' == substr($Field_Name, 0, 3)
									? substr($Field_Name, 3, strlen($Field_Name) - 3)
									: $Field_Name;
								$optionHtml = '<?php echo $utilDisplay->buildDropdownOptions('
										.'$this->data["list' . $ForeignItem . '"], false); ?>' . "\n";
								$fieldHtml = str_replace($search, $replace, $this->codeModalFieldSelect);
								$fieldHtml = str_replace(
										array('[Field Name]', '[field_name]', '[table_name]', '[options]', '[Label]', '[validation]', '[attributes]'),
										array($label, $field, $table_name, $optionHtml, $label, $validation, $attributes),
										$fieldHtml
										);
								$html .= $fieldHtml;
							}
						}
					}
					else
					{
						#-> Text input with numeric validation.
						$validation = "$required digits";
						$attributes = '';
						$fieldHtml = str_replace($search, $replace, $this->codeModalFieldText);
						$fieldHtml = str_replace(
								array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]', '[span]'),
								array($field, $table_name, $label, $validation, $attributes, 4),
								$fieldHtml
								);
						$html .= $fieldHtml;
					}
					break;
				case 'decimal':
					#-> Text input with numeric validation.
					$validation = "$required number";
					$attributes = '';
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldText);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]', '[span]'),
							array($field, $table_name, $label, $validation, $attributes, 4),
							$fieldHtml
					);
					$html .= $fieldHtml;
				case 'tinyint':
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldCheckBox);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]'),
							array($field, $table_name, $label),
							$fieldHtml
					);
					$html .= $fieldHtml;
					break;
				case 'time':
				case 'varchar':
					#-> Text input.
					$span = 12;
					if ($meta['LENGTH'] < 50)
					{
						$span = 10;
					}
					if ($meta['LENGTH'] < 30)
					{
						$span = 8;
					}
					if ($meta['LENGTH'] < 20)
					{
						$span = 6;
					}
					if ($meta['LENGTH'] < 10)
					{
						$span = 4;
					}
					$length = 'time' == $meta['DATA_TYPE']
						? 8
						: $meta['LENGTH'];
					$validation = "$required";
					$attributes = 'maxlength="' . $length . '"';
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldText);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]', '[span]'),
							array($field, $table_name, $label, $validation, $attributes, "$span"),
							$fieldHtml
					);
					$html .= $fieldHtml;
					break;
				case 'tinytext':
				case 'mediumtext':
				case 'text':
					#-> TextArea input.
					switch ($meta['DATA_TYPE'])
					{
						case 'tinytext':	$length = 255; break;
						case 'mediumtext':	$length = 16777215; break;
						case 'text':		$length = 65535; break;
					}
					$validation = "$required";
					$attributes = 'maxlength="' . $length . '"';
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldTextArea);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]'),
							array($field, $table_name, $label, $validation, $attributes),
							$fieldHtml
					);
					$html .= $fieldHtml;
					break;
				case 'date':
					$validation = "$required date";
					$attributes = '';
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldDate);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]'),
							array($field, $table_name, $label, $validation, $attributes),
							$fieldHtml
					);
					$html .= $fieldHtml;
					break;
				case 'timestamp':
				case 'datetime':
					$validation = "$required datetime";
					$attributes = '';
					$fieldHtml = str_replace($search, $replace, $this->codeModalFieldDateTime);
					$fieldHtml = str_replace(
							array('[field_name]', '[table_name]', '[Label]', '[validation]', '[attributes]'),
							array($field, $table_name, $label, $validation, $attributes),
							$fieldHtml
					);
					$fieldJs = str_replace($search, $replace, $this->codeModalJsFieldDateTime);
					$fieldJs = str_replace(
							array('[field_name]', '[table_name]'),
							array($field, $table_name),
							$fieldJs
							);
					$html .= $fieldHtml;
					$js .= $fieldJs;
					$jsIncludes .= '<script src="/js/jquery-ui-timepicker-addon.js"></script>' . "\n";
					break;
				default:
					if ('enum' == substr($meta['DATA_TYPE'], 0, 4)
						|| 'set' == substr($meta['DATA_TYPE'], 0, 3))
					{
						$validation = !empty($required)
							? "required"
							: '';
						$attributes = '';
						$Field_Name = $toSep->filter(
								$toCamel->filter(
										'_id' == substr($field, -3)
											? substr($field, 0, strlen($field) - 3)
											: $field
										)
								);
						$Field_Name = 'Lu ' == substr($Field_Name, 0, 3)
							? substr($Field_Name, 3, strlen($Field_Name) - 3)
							: $Field_Name;
						$options = str_replace(array('enum(', 'set(', ')', '\\', "'"), '', $meta['DATA_TYPE']);
						$options = explode(',', $options);
						$optionHtml = '';
						foreach ($options as $option)
						{
							$optionHtml .= "<option value=\"$option\">$option</option>\n";
						}
						$fieldHtml = str_replace($search, $replace, $this->codeModalFieldSelect);
						$fieldHtml = str_replace(
								array('[Field Name]', '[field_name]', '[table_name]', '[options]', '[Label]', '[validation]', '[attributes]'),
								array($label, $field, $table_name, $optionHtml, $label, $validation, $attributes),
								$fieldHtml
						);
						$html .= $fieldHtml;
					}
					else
					{
						Struct_Debug::errorLog('OOPS', 'Field data type not yet handled! ' . $meta['DATA_TYPE']);
					}
					break;
			}
			$colNum++;
		}
		$html .= str_replace($search, $replace, "\n\t\t\t\t".'<input type="hidden" id="[itemName]_id" name="id" form-id="[itemName]">');
		$html .= str_replace($search, $replace, $this->codeModalFooter);
		$js .= str_replace($search, $replace, $this->codeModalJsFooter);

		#-> Create file.
		$path = ReGen_Util_FileLocation::getViewLocation($itemName . $postPend, strtolower($toDash->filter($itemName)) . '-modal');
		file_put_contents($path, $html . $jsIncludes . $js);


		#-> All done -oO-
		return true;
	}



	protected function buildInsert($table, array $data)
	{
		$query = "INSERT INTO `$table` SET ";
		$fieldSet = array();
		foreach ($data as $field => $value)
		{
			$fieldSet[] = "`$field`='$value'";
		}
		return $query . implode(', ', $fieldSet);
	}


}

class CodeSource
{

	protected $codeIndex = '
<?php
$utilDisplay = new Struct_Util_Display();
?>

<!-- [ItemName]: Data Grid -->
<span id="[ItemName]Grid">
<?php include_once("[item-name]-grid.phtml");?>
</span>
<!-- [End] [ItemName]: Data Grid -->

<!-- [ItemName]: Modal Form -->
<?php include_once("[item-name]-modal.phtml");?>
<!-- [End] [ItemName]: Modal Form -->


<script type="text/javascript">

	var theme = "[module]/[item-name][postpend]";

	$(document).ready(function() {
		$("#nav[ItemName]").addClass("active");
		searchHandler["srch-[itemName]"] = {
				action: theme + "/[item-name]-grid",
				container: "[ItemName]Grid"
		};
    searchStack["srch-[itemName]"] = wrapFunction(search, this, ["srch-[itemName]"]);
	});

	function add[ItemName](id) {
		//$("#[itemName]ModalHeading").html("Add New [Item Name]");
		mode = "create";
		clearForm("[itemName]", "modal[ItemName]");
	}

	function edit[ItemName](id) {
		//$("#[itemName]ModalHeading").html("Edit [Item Name]");
		mode = "update";
		populateForm("[itemName]", "[table_name]", [itemName]Data[id], "modal[ItemName]");
	}

</script>';

	protected $codeGridHeader = '<?php
!isset($utilDisplay)
	&& $utilDisplay = new Struct_Util_Display();
$pager = $utilDisplay->buildPager(
		"[ItemName]Grid",
		"[item-name]-grid",
		$this->result["[ItemName]"]["Paging"]["CurrentPage"],
		$this->result["[ItemName]"]["Paging"]["TotalPages"],
		$this->result["[ItemName]"]["Paging"]["RecordsPerPage"]
		);
list($order, $direction) = each($this->result["[ItemName]"]["Order"]);
?>
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>';
	protected $codeGridColumnHeader = '
					<th class="[class]"><b>[Field Name]</b>
						<div class="pull-right">
							<i class="icon-<?php echo "[field_name]" == $order && "ASC" == $direction ? "" : "circle-"; ?>arrow-down handy" onClick="order(\'[itemName]\', \'[field_name]\', \'asc\');"></i>
							<i class="icon-<?php echo "[field_name]" == $order && "DESC" == $direction ? "" : "circle-"; ?>arrow-up handy" onClick="order(\'[itemName]\', \'[field_name]\', \'desc\');"></i>
						</div>
					</th>';
	protected $codeGridSearchPrep = '
					<th width="50px" class="">
						&nbsp;&nbsp;
						<i class="icon-plus handy" onClick="add[ItemName]();"></i>
					</th>
				</tr>
				<tr class="">';
	protected $codeGridColumnSearch = '
					<td class="[class]"><div class="row-fluid"><input
						id="a" name="srch_[itemName]_[foreign_table].[field_name]" type="text" class="span12 srch-[itemName]"
						value="<?php echo isset($this->result[\'[ItemName]\'][\'Search\'][\'[foreign_table].[field_name]\']) ? $this->result[\'[ItemName]\'][\'Search\'][\'[foreign_table].[field_name]\'] : ""; ?>"></div></td>';
	protected $codeGridRowPrep = '
					<td width="50px" class="">
						&nbsp;&nbsp;
						<i class="icon-search handy" onClick="search(\'srch-[itemName]\');"></i>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->data["[ItemName]"] as $id => $record) { ?>
				<tr>';
	protected $codeGridRowData = '
					<td><?php echo $record["[field_name]"]; ?></td>';
	protected $codeGridFooter = '
					<td>
						<button type="button" class="btn btn-mini" onClick="edit[ItemName](<?php echo $id; ?>);">Edit</button>
					</td>
				</tr>
				<?php } ?>
				<?php if (empty($this->data["[ItemName]"])) { ?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="[columns]">No items to display</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $pager; ?>
<script type="text/javascript">

	var [itemName]Data = <?php echo Zend_Json::encode($this->data["[ItemName]"]); ?>;

	$(document).ready(function() {
		$("#srch-action").click(function () {
		  searchStack["srch-[itemName]"]();
		});
		$(".srch-[itemName]").keypress(function(e) {
		    if(e.which == 13) {
		    	searchStack["srch-[itemName]"]();
		    }
		});
	});

</script>';



	protected $codeModalHeader = '<!-- [ItemName]: Modal Form -->
<div class="row-fluid">
  <div class="span12">
	<div class="modal [modal-width] hide fade" id="modal[ItemName]">
	  <div class="modal-header">
		<button class="close" data-dismiss="modal">x</button>
		<h3 id="[itemName]ModalHeading">Edit [Item Name]</h3>
	  </div>
		<form id="[itemName]Form" class="modal-form">
		  <div class="modal-body [modal-height]">';
	protected $codeModalBodyGroup = '
			  <div class="span[span]">';
	protected $codeModalFooter = '
			  </div>
		  </div>
		  <div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>&nbsp;
			<button type="submit" class="btn btn-success btn-warning btn-save"
		  	data-loading-text="saving..."
		  	data-complete-text="&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;">
		  		&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;
		  	</button>&nbsp;
		  </div>
		</form>
	</div>
  </div>
</div>
<!-- [End] [ItemName]: Modal Form -->';
	protected $codeModalJsHeader = '
<script type="text/javascript">
	$(document).ready(function() {';
	protected $codeModalJsFooter = '
		$("#[itemName]Form").validate({
		  submitHandler: function(form) {
			  doUpdate("[itemName]", "[itemName]", "api/data/" + mode,
			  	"[ItemName]Grid", theme + "/[item-name]-grid",
					false, true, false, {});
		  }
		});
	});
</script>';










	protected $codeModalFieldCheckBox = '
		  		<div class="control-group">
		    		<label class="checkbox" for="[itemName]_[field_name]">
			    		<input type="checkbox" id="[itemName]_[field_name]" name="[field_name]"
			    			value="[field_name]" form-id="[itemName]" checked>
			    		&nbsp;<b>[Label]</b>
		    		</label>
					</div>';
	protected $codeModalJsFieldCheckBox = '';

	protected $codeModalFieldText = '
		  		<div class="control-group">
		    		<label for="[itemName]_[field_name]"><b>[Label]</b></label>
						<input id="[itemName]_[field_name]" name="[field_name]" type="text"
							form-id="[itemName]" class="span[span] [validation]" [attributes]>
					</div>';
	protected $codeModalJsFieldText = '';

	protected $codeModalFieldTextArea = '
		  		<div class="control-group">
			  		<label for="[itemName]_[field_name]"><b>[Label]</b></label>
						<textarea id="[itemName]_[field_name]" name="[field_name]" rows="5"
							form-id="[itemName]" class="span12 [validation]" [attributes]></textarea>
					</div>';
	protected $codeModalJsFieldTextArea = '';

	protected $codeModalFieldSelect = '
		  		<div class="control-group">
				  	<label for="[itemName]_[field_name]"><b>[Label]</b></label>
						<select id="[itemName]_[field_name]" name="[field_name]"
							form-id="[itemName]" class="span12 [validation]" [attributes]>
							<option value="">-- Select [Field Name] --</option>
							[options]
						</select>
					</div>';
	protected $codeModalJsFieldSelect = '';

	protected $codeModalFieldDependantSelect = '
		  		<div class="control-group">
				  	<label for="[itemName]_[field_name]"><b>[Label]</b></label>
						<select id="[itemName]_[field_name]" name="[field_name]"
							form-id="[itemName]" class="span12 [dependancy-class] [validation]" [attributes]
							data=\'[data]\'>
							<option value="">[Select Instruction]</option>
							[options]
						</select>
					</div>';
	protected $codeModalJsFieldDependantSelect = '';

	protected $codeModalFieldDate = '
		  		<div class="control-group">
				  	<label for="[itemName]_[field_name]"><b>[Label]</b></label>
	        	<input id="[itemName]_[field_name]" name="[field_name]" type="text"
	            	form-id="[itemName]" class="span4 datepicker [validation]" [attributes]>
					</div>';
	protected $codeModalJsFieldDate = '';

	protected $codeModalFieldDateTime = '
		  		<div class="control-group">
				  	<label for="[itemName]_[field_name]"><b>[Label]</b></label>
						<input id="[itemName]_[field_name]" name="[field_name]" type="text"
							form-id="[itemName]" class="span6 datepicker [validation]" [attributes]>
					</div>';
	protected $codeModalJsFieldDateTime = '';





}




































