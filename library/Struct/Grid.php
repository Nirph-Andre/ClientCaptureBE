<?php


class Struct_Grid
{
	
	/**
	 * Data object to work with.
	 * @var Struct_Abstract_DataAccess
	 */
	protected $dataObject = true;
	
	/**
	 * Request object to work with.
	 * @var Zend_Controller_Request_Abstract
	 */
	protected $request = true;
	
	/**
	 * Do HTML encoding on return data.
	 * @var boolean
	 */
	protected $htmlEncodeReturnData = false;
	
	/**
	 * Default for including pager functionality.
	 * @var boolen
	 */
	protected $usePager = true;
	
	/**
	 * Default number of records if pager enabled.
	 * @var integer
	 */
	protected $defaultNumRecords = 10;
	
	
	/**
	 * Default for including order functionality.
	 * @var boolen
	 */
	protected $useOrder = true;
	
	/**
	 * Default field to order by if ordering enabled.
	 * @var string
	 */
	protected $defaultOrderField = true;
	
	/**
	 * Default direction to order by if ordering enabled.
	 * @var string
	 */
	protected $defaultOrderDirection = 'ASC';
	
	
	/**
	 * Default for including filter functionality.
	 * @var boolen
	 */
	protected $useFilter = true;
	
	/**
	 * Permanent filters to apply irrelevant of other filters set.
	 * @var array
	 */
	protected $baseFilter = array();
	
	/**
	 * Fields to display.
	 * @var array
	 */
	protected $displayFields = array();
	
	/**
	 * Module we are working in.
	 * @var string
	 */
	protected $viewModule = null;
	
	/**
	 * Controller/theme we are working in.
	 * @var string
	 */
	protected $viewTheme = null;
	
	/**
	 * List action for ajac retrieval of list.
	 * @var string
	 */
	protected $viewListAction = null;
	
	/**
	 * Generated HTML.
	 * @var string
	 */
	protected $viewHtml = null;
	
	/**
	 * Generated Javascript.
	 * @var string
	 */
	protected $viewJs = null;
	
	
	/**
	 * Instantiate a new data grid.
	 * @param  Struct_Abstract_DataAccess $dataObject
	 * @return Struct_Grid
	 */
	public function __construct(
			Struct_Abstract_DataAccess $dataObject,
			Zend_Controller_Request_Abstract $request,
			$viewTheme, $listAction)
	{
		$this->dataObject = $dataObject;
		$this->request = $request;
		list($module, $itemName) = explode('/', $viewTheme);
		$this->viewModule 		= $module;
		$this->viewTheme  		= $itemName;
		$this->viewListAction 	= $listAction;
	}
	
	public function useField($fieldName, $label, $searchField, Struct_Abstract_DataAccess $dataSource = null)
	{
		$this->displayFields[$fieldName] = array(
				'Label' => $label,
				'SearchField' => $searchField,
				'DataSource' => $dataSource
				);
		return $this;
	}
	
	/**
	 * Retrieve hydrated result object from data object. 
	 * @return Struct_ActionFeedback
	 */
	private function getDataResult()
	{
		$smartFilter = Struct_Registry::get('Util.SmartQueryFilter');
		return $smartFilter->handleGrid(
				$this->request,
				$this->htmlEncodeReturnData,
				get_class($this->dataObject),
				$this->baseFilter,
				array($this->defaultOrderField => $this->defaultOrderDirection),
				(!is_null($this->defaultNumRecords) ? $this->defaultNumRecords : 10)
				);
	}
	
	/**
	 * Build index page data.
	 */
	private function _buildIndex()
	{
		
		$data = Struct_Grid_Twitter::buildIndex(
				$this->viewModule . '/' . $this->viewTheme,
				$this->viewListAction,
				$this->dataObject->getTableName(),
				$this->displayFields,
				$this->getDataResult(),
				null
				);
		$this->viewHtml = $data['Html'];
		$this->viewJs = $data['Script'];
	}
	
	/**
	 * Publish index html and javascript.
	 */
	public function publishIndex()
	{
		$this->_buildIndex();
		$this->publishIndexHtml(false);
		$this->publishIndexJs(false);
	}
	
	/**
	 * Publish index html.
	 */
	public function publishIndexHtml($build = true)
	{
		$build && $this->_buildIndex();
		echo $this->viewHtml;
	}
	
	/**
	 * Publish index javascript.
	 */
	public function publishIndexJs($build = true)
	{
		$build && $this->_buildIndex();
		echo $this->viewJs;
	}
	
	/**
	 * Publish grid html and javascript.
	 */
	public function publishGrid()
	{
		$this->params['columns'] = count($this->displayFields);
		$result = $this->getDataResult();
		$data = Struct_Grid_Twitter::buildGrid(
				$this->viewModule . '/' . $this->viewTheme,
				$this->viewListAction,
				$this->dataObject->getTableName(),
				$this->displayFields,
				$result, //$this->getDataResult(),
				null
				);
		echo $data['Html'];
		echo $data['Script'];
// 		echo '<pre>';
// 		var_dump($result->data);
// 		echo '</pre>';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
