<?php


/**
 * Basic controller functionality.
 * @author andre.fourie
 */
class Struct_Abstract_Controller extends Zend_Controller_Action
{
	
	/**
	 * Switch to enable html encoding of return data.
	 * @var boolean
	 */
	protected $htmlEncodeReturnData = false;
	
	/**
	 * Context for data to be placed inside data container.
	 * @var string
	 */
	protected $dataContext = false;
	
	/**
	 * Namespace used when packing results for ember-data. 
	 * @var string
	 */
	protected $_nameSpace = '';
	
	/**
	 * Default object to work with.
	 * @var string
	 */
	protected $_defaultObjectName = null;
	
	/**
	 * Default session namespace for the view.
	 * @var string
	 */
	protected $_sessionNamespace = null;
	
	
	/**
	 * Retrieve Value Object
	 * @param  string $name
	 * @return Struct_Abstract_DataAccess
	 */
	protected function getObject($objectName = null)
	{
		$className = is_null($objectName)
			? $this->_defaultObjectName
			: $objectName;
		return new $className();
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
	 * Store result for view access.
	 * @param mixed $data
	 */
	protected function storeResult($result)
	{
		if ($this->dataContext)
		{
			is_array($this->view->result)
				|| $this->view->result = array();
			$this->view->result[$this->dataContext] = $result;
		}
		else
		{
			$this->view->result = $result;
		}
	}
	
	/**
	 * Store data for view access.
	 * @param mixed $data
	 */
	protected function storeData($data)
	{
		if ($this->dataContext)
		{
			is_array($this->view->data)
				|| $this->view->data = array();
			$this->view->data[$this->dataContext] = $data;
		}
		else
		{
			$this->view->data = $data;
		}
	}
	
	/**
	 * Pack result for view script.
	 * @param Struct_ActionFeedback $result
	 */
	protected function viewResult(Struct_ActionFeedback $result)
	{
		$this->storeResult($result->result);
		$this->storeData($result->data);
	}
	
	/**
	 * Pack result for view script and disable layout.
	 * @param Struct_ActionFeedback $result
	 */
	protected function ajaxResult(Struct_ActionFeedback $result)
	{
		$this->_helper->layout()->disableLayout();
		$this->storeResult($result->result);
		$this->storeData($result->data);
	}
	
	/**
	 * Output json encoded result and nothing else.
	 * @param Struct_ActionFeedback $result
	 */
	protected function jsonResult(Struct_ActionFeedback $result)
	{
		if (defined('DEBUG_UNITTEST'))
		{
			$this->view->data = Zend_Json::encode($result->pack());
			return;
		}
		try
		{
			header('Expires: Fri, 26 Nov 1976 05:00:00 GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header("Pragma: no-cache");
			header('Content-type: application/json; charset=utf-8');
			header('Access-Control-Allow-Origin: *');
		}
		catch(Exception $e) {
			Struct_Debug::errorLog('jsonResult.Exception', "$e");
		}
		echo Zend_Json::encode($result->pack());
		exit(0);
	}
	
	/**
	 * Output json encoded result under specified namespace and nothing else.
	 * @param Struct_ActionFeedback $result
	 */
	protected function jsonNsResult(Struct_ActionFeedback $result)
	{
		if (defined('DEBUG_UNITTEST'))
		{
			$this->view->data = Zend_Json::encode($result->packForNs($this->_nameSpace));
			return;
		}
		try
		{
			header('Expires: Fri, 26 Nov 1976 05:00:00 GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header("Pragma: no-cache");
			header('Content-type: application/json; charset=utf-8');
			header('Access-Control-Allow-Origin: *');
		}
		catch(Exception $e) {
			Struct_Debug::errorLog('jsonNsResult.Exception', "$e");
		}
		echo Zend_Json::encode($result->packForNs($this->_nameSpace));
		exit(0);
	}
	
	/**
	 * Output json encoded result and nothing else.
	 * @param Struct_ActionFeedback $result
	 */
	protected function jsonBasicResult($result)
	{
		if (defined('DEBUG_UNITTEST'))
		{
			$this->view->data = Zend_Json::encode($result);
			return;
		}
		try
		{
			header('Expires: Fri, 26 Nov 1976 05:00:00 GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header("Pragma: no-cache");
			header('Content-type: application/json; charset=utf-8');
			header('Access-Control-Allow-Origin: *');
		}
		catch(Exception $e) {
			Struct_Debug::errorLog('jsonResult.Exception', "$e");
		}
		echo Zend_Json::encode($result);
		exit(0);
	}
	
	/**
	 * On success redirect, else view.
	 * @param string $location
	 * @param Struct_ActionFeedback $result
	 */
	protected function redirectResult($location, Struct_ActionFeedback $result)
	{
		if ($result->ok())
		{
			list($theme, $action) = explode('.', $location);
			return $this->_helper->redirector($action, $theme);
		}
		$this->storeResult($result->result);
		$this->storeData($result->data);
	}
	
	/**
	 * View data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array  $filters
	 * @param array  $extendedData
	 */
	protected function viewDataReturnView($objectName = null, array $filters = array(), $extendedData = true)
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$id = empty($id) ? null : $id;
		$this->viewResult(
				$this->getObject($objectName)
					->view($id, $filters, $extendedData)
				);
	}
	
	/**
	 * View data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array  $filters
	 * @param array  $extendedData
	 */
	protected function viewDataReturnAjax($objectName = null, array $filters = array(), $extendedData = true)
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$id = empty($id) ? null : $id;
		$this->ajaxResult(
				$this->getObject($objectName)
					->view($id, $filters, $extendedData)
				);
	}
	
	/**
	 * View data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array  $filters
	 * @param array  $extendedData
	 */
	protected function viewDataReturnJson($objectName = null, array $filters = array(), $extendedData = true)
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$id = empty($id) ? null : $id;
		$this->jsonResult(
				$this->getObject($objectName)
					->view($id, $filters, $extendedData)
				);
	}
	
	/**
	 * List data from object via standard DataAccess method.
	 * @param string  $objectName
	 * @param array   $baseFilter
	 * @param array   $baseOrder
	 * @param boolean $allColumns
	 */
	protected function listDataReturnView(
			$objectName = null, array $baseFilter = array(), array $baseOrder = array(), $allColumns = false
			)
	{
		$request = $this->getRequest();
		$filter  = $request->getParam('pg_filter', array());
		$filter  = array_merge($baseFilter, $filter);
		$order   = $request->getParam('pg_order', array());
		$order   = array_merge($baseOrder, $order);
		$this->viewResult(
				$this->getObject($objectName)
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->listAll($filter, $order, $allColumns)
				);
	}
	
	/**
	 * Handle dependant data sets for select dropdowns.
	 * @param array $map
	 */
	protected function listDependancyDataReturnSelectOptions(array $map)
	{
		$request = $this->getRequest();
		$depFilter = $request->getParam('dep_filter', array());
		$filter = array($depFilter['field'] => $depFilter['value']);
		$this->listDataReturnSelectOptions(
				$map[$depFilter['item']], $depFilter['item'], $depFilter['selected'], $filter
				);
	}
	
	/**
	 * List data from object via standard DataAccess method and build html select options.
	 * @param string $objectName
	 * @param string $itemName
	 * @param mixed  $selected
	 * @param array  $baseFilter
	 * @param array  $baseOrder
	 */
	protected function listDataReturnSelectOptions(
			$objectName = null, $itemName, $selected, array $filter = array(), array $order = array()
			)
	{
		$result = $this->getObject($objectName)
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->listAll($filter, $order);
		$html = '';
		if ($result->ok())
		{
			$html .= '<option value="">-- Select ' . $itemName . ' --</option>';
			foreach ($result->data as $id => $label)
			{
				$selectedItem = ($selected == $id)
					? ' selected'
					: '';
				$html .= '<option value="' . $id . '" ' . $selectedItem . '>' . $label . '</option>' . "\n";
			}
		}
		else
		{
			$html .= '<option value="">Could not retrieve data!</option>';
		}
		echo $html;
		exit();
	}
	
	/**
	 * List data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array  $baseFilter
	 * @param array  $baseOrder
	 */
	protected function listDataReturnAjax(
			$objectName = null, array $baseFilter = array(), array $baseOrder = array()
			)
	{
		$request = $this->getRequest();
		$filter  = $request->getParam('pg_filter', array());
		$filter = array_merge($baseFilter, $filter);
		$order   = $request->getParam('pg_order', array());
		$order = array_merge($baseOrder, $order);
		$this->ajaxResult(
				$this->getObject($objectName)
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->listAll($filter, $order)
				);
	}
	
	/**
	 * List data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array  $baseFilter
	 * @param array  $baseOrder
	 */
	protected function listDataReturnJson(
			$objectName = null, array $baseFilter = array(), array $baseOrder = array()
			)
	{
		$request = $this->getRequest();
		$filter  = $request->getParam('pg_filter', array());
		$filter = array_merge($baseFilter, $filter);
		$order   = $request->getParam('pg_order', array());
		$order = array_merge($baseOrder, $order);
		$this->jsonResult(
				$this->getObject($objectName)
					->htmlEncodeDataReturn($this->htmlEncodeReturnData)
					->listAll($filter, $order)
				);
	}
	
	/**
	 * Grid data from object via standard DataAccess method.
	 * @param string  $objectName
	 * @param array   $baseFilter
	 * @param array   $defaultOrder
	 * @param integer $defaultNumRecords
	 */
	protected function getGridResult(
			$objectName = null, array $baseFilter = array(),
			array $defaultOrder = array(), $defaultNumRecords = null
			)
	{
		$smartFilter = Struct_Registry::get('Util.SmartQueryFilter');
		return $smartFilter->handleGrid(
			$this->getRequest(), $this->htmlEncodeReturnData,
			$objectName, $baseFilter, $defaultOrder, $defaultNumRecords
			);
	}
	
	/**
	 * Grid data from object via standard DataAccess method.
	 * @param string  $objectName
	 * @param array   $baseFilter
	 * @param array   $defaultOrder
	 * @param integer $defaultNumRecords
	 */
	protected function gridDataReturnView(
			$objectName = null, array $baseFilter = array(),
			array $defaultOrder = array(), $defaultNumRecords = null
			)
	{
		$this->viewResult(
				$this->getGridResult(
						$objectName,
						$baseFilter,
						$defaultOrder,
						$defaultNumRecords
						)
				);
	}
	
	/**
	 * Grid data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array   $baseFilter
	 * @param array   $defaultOrder
	 * @param integer $defaultNumRecords
	 */
	protected function gridDataReturnAjax(
			$objectName = null, array $baseFilter = array(),
			array $defaultOrder = array(), $defaultNumRecords = null
			)
	{
		$this->ajaxResult(
				$this->getGridResult(
						$objectName,
						$baseFilter,
						$defaultOrder,
						$defaultNumRecords
						)
				);
	}
	
	/**
	 * Grid data from object via standard DataAccess method.
	 * @param string $objectName
	 * @param array   $baseFilter
	 * @param array   $defaultOrder
	 * @param integer $defaultNumRecords
	 */
	protected function gridDataReturnJson(
			$objectName = null, array $baseFilter = array(),
			array $defaultOrder = array(), $defaultNumRecords = null
			)
	{
		$this->jsonResult(
				$this->getGridResult(
						$objectName,
						$baseFilter,
						$defaultOrder,
						$defaultNumRecords
						)
				);
	}
	
	/**
	 * Save data to object via standard DataAccess method.
	 * @param string $objectName
	 */
	protected function saveDataReturnJson($objectName = null)
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$id = empty($id) ? null : $id;
		$this->jsonResult(
				$this->getObject($objectName)
					->save($id, array(), $request->getPost())
				);
	}
	
	/**
	 * Save data to object via standard DataAccess method.
	 * @param string $objectName
	 * @param string $location
	 */
	protected function saveDataRedirect($objectName = null, $location)
	{
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$id = empty($id) ? null : $id;
		$this->redirectResult(
				$location,
				$this->getObject($objectName)
					->save($id, array(), $request->getPost())
				);
	}
    
	/**
	 * Remove data from object via standard DataAccess method.
	 * @param string $name
	 */
  public function removeDataReturnJson($objectName = null)
  {
		$request = $this->getRequest();
		$id = $request->getParam('id', null);
		$this->jsonResult(
				$this->getObject($objectName)
					->remove($id)
				);
  }
	
	/**
	 * Clear grid input params.
	 */
	protected function clearPgRequest()
	{
		$this->getRequest()->setParam('pg_filter', array());
		$this->getRequest()->setParam('pg_order', array());
		$this->getRequest()->setParam('pg_records', array());
		$this->getRequest()->setParam('pg_page', array());
	}
	
}
