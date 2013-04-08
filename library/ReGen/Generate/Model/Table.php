<?php


class ReGen_Generate_Model_Table extends Zend_Db_Table
{
	
	
	/**
	 * Generate model by examining the DB structure.
	 *
	 * @return void
	 */
	public function setupMapperPattern($tables)
	{
		try {
			return $this->_createModel($tables);
		} catch (Exception $e) {
			Struct_Debug::errorLog('EXCEPTION', $e->getMessage());
			Struct_Debug::errorLog('TRACE', $e->getTraceAsString());
		}
	}
	
	
	/**
	 * Create table model file.
	 *
	 * @return Nirph_Db_Table_Abstract
	 */
	private function _createModel($tables, $views)
	{
		#-> Safety check.
		$reflectionClass = new ReflectionClass(get_class($this));
		if ($reflectionClass->isAbstract()) return false;
		
		#-> What table is this?
		$classParts = explode('_', get_class($this));
		$tableName = array_pop($classParts);
		
		#-> Tools.
		$filter = new Zend_Filter_Word_UnderscoreToCamelCase();
		
		#-> Work through the meta data.
		$fields = $this->info(Zend_Db_Table::METADATA);
		$fieldMap = array();
		$tableFlags = 0;
		$tableReadable = array();
		$tableArchiveField = false;
		$references = array();
		foreach ($fields as $key => $meta)
		{
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
					&& '_id' == substr($key, -3))
			{
				$flags |= FIELD_REQUIRED;
				$readable[] = 'FIELD_REQUIRED';
			}
			
			#-> Auto-date magic.
			if ('created_timestamp' == $key
					|| 'created_date' == $key
					|| 'created' == $key)
			{
				if (strtoupper($meta['DATA_TYPE']) == 'DATETIME')
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
					|| 'modified' == $key)
			{
				if (strtoupper($meta['DATA_TYPE']) == 'DATETIME')
				{
					$flags |= FIELD_UPDATE_DATETIME;
					$readable[] = 'FIELD_UPDATE_DATETIME';
				}
				elseif (strtoupper($meta['DATA_TYPE']) == 'DATE')
				{
					$flags |= FIELD_UPDATE_DATE;
					$readable[] = 'FIELD_UPDATE_DATE';
				}
				else
				{
					$flags |= FIELD_UPDATE_TIMESTAMP;
					$readable[] = 'FIELD_UPDATE_TIMESTAMP';
				}
			}
			$fields[$key]['FLAGS'] = $flags;
			$fields[$key]['FLAG_LIST'] = implode(' | ', $readable);
			 
			#-> Check for references
			if ('_id' == substr($key, -3))
			{
				#-> Disect and find it with backwards mini-matching.
				$parts = explode('_', $key);
				
				#-> Get rid of the _id bit.
				array_pop($parts);
				
				#-> Do we have a direct match?
				$search = implode('_', $parts);
				if (isset($tables[$search]))
				{
					$referenceRule = $filter->filter($search);
					$references[$referenceRule] = array(
							'columns'       => $key,
							'refTableClass' => 'Table_' . $referenceRule,
							'refColumns'    => 'id'
					);
				}
				else
				{
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
									'columns'       => $key,
									'refTableClass' => 'Table_' . $refTable,
									'refColumns'    => 'id'
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
				'shortDescription' => 'Table model for ' . $this->_name
		));
		$modelClassName = 'Table_' . $tableName;
		$tableClass->setName($modelClassName)
			->setDocblock($docblock)
			->setExtendedClass('Struct_Abstract_ModelTable');
		
		#-> Prepare default record array.
		$newRecord = array();
		foreach ($fields as $key => $meta)
		{
			$newRecord[$key] = (isset($meta['DEFAULT']) && '' != $meta['DEFAULT'])
				? $meta['DEFAULT']
				: new Zend_CodeGenerator_Php_Property_DefaultValue("null");
		}
		
		#-> Set class properties.
		$tableClass->setProperty(array(
				'name' => '_name',
				'visibility' => 'protected',
				'defaultValue' => $this->_name,
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
				'name' => 'fieldMeta',
				'visibility' => 'protected',
				'defaultValue' => $fields,
				'docblock' => array(
						'shortDescription' => 'Field meta data.'
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
		
		#-> Save class to file.
		$path = ReGen_Util_FileLocation::getTableModelLocation($tableName);
		$file = new Zend_CodeGenerator_Php_File(array(
				'classes' => array($tableClass)
		));
		file_put_contents($path, $file->generate());
		
		
		#-> All done -oO-
		return true;
	}
	
	
}