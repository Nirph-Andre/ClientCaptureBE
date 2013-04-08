<?php



class ReGen_Generate_Object_DataAccess
{
	
	
	public function objectFromTable($tableClass, $tableName, $table_name, $fieldMeta, $dependantTables)
	{
		$classPath = ReGen_Util_FileLocation::getObjectLocation($tableName);
		if (file_exists($classPath))
		{
			return true;
		}
		$filePath = ReGen_Util_FileLocation::getTableModelLocation($tableName);
		$parser = new ReGen_Util_TemplateParser();
		$root = $parser->parseFileData($filePath);
		$code = "<?php \n\n";
		$code .= $root->getCodeBlock('ClassHeader')
			->publish(array(
					'className' 		=> 'Object_' . $tableName . 'Data',
					'classDescription' 	=> "Data access functionality for table $table_name.",
					'author' 			=> ReGen_Registry::getContext('username'),
					'table_name' 		=> $tableName
					));
		
		$code .= $root->getCodeBlock('ClassFooter')
			->publish(array());
		
	}
}