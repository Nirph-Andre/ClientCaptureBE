<?php

class Struct_Util_SmartQueryFilter
{
	
	public $sessionName = false;
	
	/**
	 * Retrieve Value Object
	 * @param  string $name
	 * @return Struct_Abstract_DataAccess
	 */
	protected function getObject($objectName)
	{
		return new $objectName();
	}
	
	/**
	 * Handle data-grid request.
	 * @param multi $request
	 * @param boolean $htmlEncodeReturnData
	 * @param string  $objectName
	 * @param array   $baseFilter
	 * @param array   $defaultOrder
	 * @param array   $exclude
	 * @param array   $chain
	 * @param integer $defaultNumRecords
	 * @param string  $gridType
	 * @return Struct_ActionFeedback
	 */
	public function handleGrid(
			$request, $htmlEncodeReturnData,
			$objectName = null, array $baseFilter = array(),
			array $defaultOrder = array(), 
			array $exclude = array(), array $chain = array(),
			$defaultNumRecords = null,
			$gridType = 'Json',
			array $options = array(),
			array $defaultGroup = array()
			)
	{
		$session = $this->getGridSession($objectName, $defaultOrder, $defaultNumRecords, $defaultGroup);
		if (is_object($request))
		{
			$filter  = $request->getParam('pg_filter', $session->filter);
			$order   = $request->getParam('pg_order', $session->order);
			$group   = $request->getParam('pg_group', $session->group);
			$records = $request->getParam('pg_records', $session->records);
			$page    = $request->getParam('pg_page', $session->page);
		}
		else
		{
			$filter = isset($request['pg_filter'])
				? $request['pg_filter']
				: $session->filter;
			$order = isset($request['pg_order'])
				? $request['pg_order']
				: $session->order;
			$group = isset($request['pg_group'])
				? $request['pg_group']
				: $session->group;
			$records = isset($request['pg_records'])
				? $request['pg_records']
				: $session->records;
			$page = isset($request['pg_page'])
				? $request['pg_page']
				: $session->page;
		}
		$filter = array_merge($baseFilter, $filter);
		
		if (isset($filter['x']))
		{
			unset($filter['x']);
		}
		if (isset($order['x']))
		{
			unset($order['x']);
		}
		$session->filter  = $filter;
		$session->order   = $order;
		$session->group   = $group;
		$session->records = $records;
		$session->page    = $page;
		
		//$where = $this->filter($filter);
		//$orderBy = $this->order($order);
		$options['where']             = $filter;
		$options['filter']            = $filter;
		$options['order']             = $order;
		$options['group']             = $group;
		$options['numRecordsPerPage'] = $records;
		$options['numPage']           = $page;
		$options['exclude']           = $exclude;
		$options['chain']             = $chain;
		
		return $this->getObject($objectName)
			->htmlEncodeDataReturn($htmlEncodeReturnData)
			->index(true)
			->process(new Struct_ActionRequest('Grid', array(), $options));
	}
	
	/**
	 * Retrieve view session.
	 * @return Zend_Session_Namespace
	 */
	protected function getGridSession($nameSpace, $defaultOrder = array(), $defaultNumRecords = null, $defaultGroup = array())
	{
		$sessionName = $this->sessionName
			? $this->sessionName
			: 'grid' . $nameSpace;
		$session = new Zend_Session_Namespace($sessionName);
		isset($session->filter)
			|| $session->filter = array();
		isset($session->order)
			|| $session->order = $defaultOrder;
		isset($session->group)
			|| $session->group = $defaultGroup;
		isset($session->records)
			|| $session->records = $defaultNumRecords;
		isset($session->page)
			|| $session->page = (!is_null($defaultNumRecords) ? 1 : null);
		return $session;
	}
	
	/**
	 * Convert human input filter to db format filter.
	 * @param  array $filter
	 * @return array
	 */
	public function filter(array $filter)
	{
		$where = array();
		foreach ($filter as $field => $value)
		{
			$search = array('>', '<', '=', '!=', '<>', '<=', '>=');
			if (false !== strpos($value, '%'))
			{
				$where["$field LIKE ?"] = $value;
			}
			elseif ('NOT IN' == substr(strtoupper($value), 0, 6))
			{
				$where["$field NOT IN (?)"] = explode(',', trim(substr($value, 6, strlen($value) - 6)));
			}
			elseif ('IN' == substr(strtoupper($value), 0, 2))
			{
				$where["$field IN (?)"] = explode(',', trim(substr($value, 2, strlen($value) - 2)));
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
		return $where;
	}
	
	/**
	 * Convert grid input order to db format order.
	 * @param  array $order
	 * @return array
	 */
	public function order($order)
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
