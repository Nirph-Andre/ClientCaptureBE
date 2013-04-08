<?php


/**
 * Base reporting functionality.
 * @author andre.fourie
 *
 */
abstract class Struct_Abstract_Report
{
	
	/**
	 * Report title.
	 * @var string
	 */
	protected $_title  = null;
	/**
	 * Report subject.
	 * @var string
	 */
	protected $_subject = null;
	/**
	 * Report description.
	 * @var string
	 */
	protected $_description = null;
	/**
	 * @var string
	 */
	protected $_format = null;
	/**
	 * Queries applied to the report.
	 * @var array
	 */
	protected $_queries = array();
	/**
	 * Column headers.
	 * @var array
	 */
	protected $_headers = array();
	/**
	 * Fields to pull from dataset.
	 * @var array
	 */
	protected $_fields = array();
	/**
	 * Columns to provide totals for.
	 * @var array
	 */
	protected $_totals = array();
	/**
	 * Columns for currency format.
	 * @var array
	 */
	protected $_currencyFields = array();
	/**
	 * Notes to display at end of report.
	 * @var array
	 */
	protected $_notes = array();
	/**
	 * Chart from report data.
	 * @var array
	 */
	protected $_chart = array();
	/**
	 * @var array
	 */
	protected $_data = array();
	/**
	 * Request object.
	 * @var Struct_ActionRequest
	 */
	protected $_request = null;
	/**
	 * Options to passed.
	 * @var array
	 */
	protected $_options = array();
	/**
	 * Data passed.
	 * @var array
	 */
	protected $_input = array();
	
	
	/* ---------------------------------------------------------------------- *\
	 *	Standard Interface
	\* ---------------------------------------------------------------------- */
	/**
	 * Process standard format request.
	 * @param Struct_ActionRequest $request
	 */
	public function process(Struct_ActionRequest $request)
	{
		$this->_request = $request;
		$this->_input   = $request->data;
		$this->_options = $request->options;
		$this->build();
		return $this;
	}
	
	/**
	 * Build the dataset.
	 */
	public function build() {}
	
	
	/* ---------------------------------------------------------------------- *\
	 * Specifically cater for Excel Report requirements.
	\* ---------------------------------------------------------------------- */
	/**
	 * Retrieve report title.
	 * @return string
	 */
	public function getTitle()
	{
		return $this->_title;
	}
	
	/**
	 * Retrieve report subject.
	 * @return string
	 */
	public function getSubject()
	{
		return $this->_subject;
	}
	
	/**
	 * Retrieve report description.
	 * @return string
	 */
	public function getDescription()
	{
		return $this->_description;
	}
	
	/**
	 * Retrieve report format.
	 * @return string
	 */
	public function getFormat()
	{
		return $this->_format;
	}
	
	/**
	 * Retrieve queries to display at beginning of report.
	 * @return array
	 */
	public function getQueries()
	{
		return $this->_queries;
	}
	
	/**
	 * Retrieve report column headers.
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->_headers;
	}
	
	/**
	 * Retrieve report fields.
	 * @return array
	 */
	public function getFields()
	{
		return $this->_fields;
	}
	
	/**
	 * Retrieve report fields to receive total value at end of report.
	 * @return array
	 */
	public function getTotalFields()
	{
		return $this->_totals;
	}
	
	/**
	 * Retrieve report fields to receive currency formatting.
	 * @return array
	 */
	public function getCurrencyFields()
	{
		return $this->_currencyFields;
	}
	
	/**
	 * Retrieve notes to be added to end of report.
	 * @return array
	 */
	public function getNotes()
	{
		return $this->_notes;
	}
	
	/**
	 * Retrieve chart specifications.
	 * @return array
	 */
	public function getChart()
	{
		return $this->_chart;
	}
	
	/**
	 * Retrieve report data.
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}
	
	
	/* ---------------------------------------------------------------------- *\
	 * Utility methods.
	\* ---------------------------------------------------------------------- */
	/**
	 * Retrieve object.
	 * @param string $name
	 * @return Struct_Abstract_DataAccess
	 */
	protected function _getObject($name)
	{
		$name = 'Object_' . $name;
		return new $name();
	}
	
	
}

