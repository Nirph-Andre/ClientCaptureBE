<?php

class Api_ReportController extends Struct_Abstract_Controller
{
	
	/**
	 * @var string
	 */
	protected $_defaultObjectName = '';
	
	/**
	 * @var string
	 */
	protected $_nameSpace = '';
	
	/**
	 * @var Struct_Abstract_Report
	 */
	protected $_report = false;
	
	/**
	 * @var array
	 */
	protected $_data   = false;
	
	/**
	 * @var array
	 */
	protected $_options   = false;
	
	
	
	protected function initReps()
	{
		if (!Struct_Registry::isAuthenticated() && !defined('DEBUG_UNITTEST'))
		{
			$this->jsonResult(Struct_ActionFeedback::error(
					'Report requested without authentication.',
					'You are not authenticated, please login.'
			));
		}
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$params = $this->getRequest()->getParams();
		if (count($params) <= 3)
		{
			$params = json_decode(file_get_contents('php://input'), true);
			if (empty($params))
			{
				$this->jsonResult(Struct_ActionFeedback::error(
						'No report context',
						'No report context specified, server could not service the request.'
						));
			}
		}
		else
		{
			unset($params['module']);
			unset($params['controller']);
			unset($params['action']);
		}
		$action = $this->getRequest()->getActionName();
		$this->_options = isset($params['Options'])
			? $params['Options']
			: array();
		list($this->_nameSpace, $this->_data) = each($params);
		
		$class = 'Report_' . $this->_nameSpace;
		$this->_report = new $class();
		return true;
	}

	public function indexAction()
	{
		$this->initReps();
		$this->jsonResult(Struct_ActionFeedback::success());
	}

	public function jsonAction()
	{
		$this->initReps();
		$this->_report->process(
				new Struct_ActionRequest(
						'Report',
						$this->_data,
						$this->_options
				));
		$this->jsonNsResult(array(
				$this->_nameSpace => array(
						'Title'        => $this->_report->getTitle(),
						'Description'  => $this->_report->getDescription(),
						'QueryDetails' => $this->_report->getQueries(),
						'Headers'      => $this->_report->getHeaders(),
						'Fields'       => $this->_report->getFields(),
						'TotalFields'  => $this->_report->getTotalFields(),
						'Notes'        => $this->_report->getNotes(),
						'Data'         => $this->_report->getData()
						))
				);
	}

	public function excelAction()
	{
		$this->initReps();
		$oExcelRep = new Struct_Report_Excel(
				$this->_report->process(
						new Struct_ActionRequest(
								'Report',
								$this->_data,
								$this->_options
						)
				)
		);
		$oExcelRep->output();
	}


}

