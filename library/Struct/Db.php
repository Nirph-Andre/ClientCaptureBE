<?php


/**
 * Database utilities.
 * @author andre.fourie
 */
class Struct_Db
{
	
	
	/**
	 * Lock tables for read/write.
	 * @param array $write
	 * @param array $read
	 * @return void
	 */
	static public function lockTables(array $write = array(), array $read = array())
	{
		$locks = array();
		if (empty($write) && empty($read))
		{
			return;
		}
		foreach ($write as $table)
		{
			$locks[] = "$table WRITE";
		}
		foreach ($read as $table)
		{
			$locks[] = "$table READ";
		}
		$tbl = new Zend_Db_Table();
		$tbl->getAdapter()
			->query('LOCK TABLES ' . implode(', ', $locks))
			->execute();
	}
	
	/**
	 * Unlock currently held table locks.
	 * @return void
	 */
	static public function unloackTables()
	{
		$tbl = new Zend_Db_Table();
		$tbl->getAdapter()
			->query('UNLOCK TABLES')
			->execute();
	}
	
	/**
	 * Start db transaction.
	 * @return void
	 */
	static public function startTransaction()
	{
		$tbl = new Zend_Db_Table();
		$tbl->getAdapter()
			->beginTransaction();
	}
	
	/**
	 * Commit current db transaction.
	 * @return void
	 */
	static public function commitTransaction()
	{
		$tbl = new Zend_Db_Table();
		$tbl->getAdapter()
			->commit();
	}
	
	/**
	 * Rollback current db transaction.
	 * @return void
	 */
	static public function rollbackTransaction()
	{
		$tbl = new Zend_Db_Table();
		$tbl->getAdapter()
			->rollBack();
	}
	
}