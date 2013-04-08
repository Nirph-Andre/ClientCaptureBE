<?php


#-> Excel lib required.
require_once('PHPExcel.php');


/**
 * For excel report building.
 * @author andre.fourie, tjaart.viljoen
 */
class Struct_Report_Excel
{
	
	/**
	 * Report class
	 * @var Struct_Abstract_Report
	 */
	protected $_objReport   = null;
	/**
	 * Excel writer
	 * @var PHPExcel_Writer_Excel2007
	 */
	protected $_objWriter   = null;
	/**
	 * Excel base class
	 * @var PHPExcel
	 */
	protected $_objPHPExcel = null;
	
	/**
	 * Filename to use for output
	 * @var string
	 */
	protected $_fileName    = null;
	
	/**
	 * Formatting
	 * @var string
	 */
	protected $_format;
	/**
	 * Report query details.
	 * @var array
	 */
	protected $_queries = array();
	/**
	 * Column headers
	 * @var array
	 */
	protected $_headers = array();
	/**
	 * Column fields
	 * @var array
	 */
	protected $_fields  = array();
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
	 * Columns totals accumulated.
	 * @var array
	 */
	protected $_totalAccum = array();
	/**
	 * Notes to display.
	 * @var array
	 */
	protected $_notes = array();
	/**
	 * Chart from report data.
	 * @var array
	 */
	protected $_chart = array();
	/**
	 * Report data
	 * @var array
	 */
	protected $_data    = array();
	/**
	 * Useful mapping
	 * @var array
	 */
	protected $numberToColumn = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
	
	
	/**
	 * Constructor.
	 * @param Struct_Abstract_Report $report
	 */
	public function __construct(Struct_Abstract_Report $report)
	{
		$this->_fileName = str_replace(' ', '-', $report->getTitle());
		$objPHPExcel = new PHPExcel();
		$objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$this->_objReport      = $report;
		$this->_format         = $report->getFormat();
		$this->_queries        = $report->getQueries();
		$this->_headers        = $report->getHeaders();
		$this->_fields         = $report->getFields();
		$this->_totals         = $report->getTotalFields();
		$this->_currencyFields = $report->getCurrencyFields();
		$this->_notes          = $report->getNotes();
		$this->_chart          = $report->getChart();
		$this->_data           = $report->getData();
		$this->_objWriter      = $objWriter;
		$this->_objPHPExcel    = $objPHPExcel;
		$this->_objPHPExcel->getProperties()->setCreator(APPLICATION);
		$this->_objPHPExcel->getProperties()->setLastModifiedBy(APPLICATION);
		$this->_objPHPExcel->getProperties()->setTitle($report->getTitle());
		$this->_objPHPExcel->getProperties()->setSubject($report->getSubject());
		$this->_objPHPExcel->getProperties()->setDescription($report->getDescription());
		$this->_addLogo();
		$numQueries = count($this->_queries);
		$numRecords = count($this->_data);
		$numNotes   = count($this->_notes);
		$numChart   = !empty($this->_chart)
			? 15 : 0;
		$numTotals = (!empty($this->_totals))
			? 1
			: 0;
		if ($numQueries)
		{
			$this->_writeQueries(2);
			$numQueries += 2;
		}
		$this->_createHeaders(2 + $numQueries);
		$this->_writeRecords(3 + $numQueries);
		if (!empty($this->_totals))
		{
			$this->_writeTotals(3 + $numQueries + $numRecords);
		}
		$this->_setCurrencyFormat(3 + $numQueries, $numRecords + $numTotals);
		if ($numChart)
		{
			$this->_writeChart(
					5 + $numQueries + $numRecords,
					3 + $numQueries,
					2 + $numQueries + $numRecords
					);
			$this->_objWriter->setIncludeCharts(true);
			$numChart += 2;
		}
		if ($numNotes)
		{
			$this->_writeNotes(5 + $numQueries + $numRecords + $numChart);
		}
		return $this;
	}
	
	/**
	 * Set formatting.
	 * @param string $format
	 * @return Struct_Abstract_ExcelReport
	 */
	public function setFormat($format)
	{
		$this->_format = $format;
		return $this;
	}
	
	/**
	 * Output result.
	 * @todo  add email, filesystem and ftp as potential targets.
	 * @param null|string $target
	 */
	public function output($target = null)
	{
		if (is_null($target))
		{
			#-> Direct output.
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' .$this->_fileName.'_'. time() . '.xlsx"' );
			$this->_objWriter->save('php://output');
		}
		elseif (strpos($target, '@'))
		{
			#-> Send to email.
			
		}
	}
	
	/**
	 * Add logo to top of spreadsheet.
	 */
	private function _addLogo()
	{
		$totalCols = count($this->_headers);
	
		// Add a drawing to the worksheet
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Logo');
		$objDrawing->setDescription('Logo');
		$objDrawing->setPath(APPLICATION_PATH . '/../public/images/logo.png');
		$objDrawing->setHeight(56);
		
		$this->_objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(56);
		$style_logo = array(
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'222222'),
				)
		);
		$style_header = array(
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'bbbbbb'),
				),
				'font' => array(
						'bold' => true,
				)
		);
	
		$convertTotalToString = $this->numberToColumn[$totalCols-1];
		$this->_objPHPExcel->getActiveSheet()->getStyle("A1:{$convertTotalToString}1")->applyFromArray($style_logo);
	
		$objDrawing->setWorksheet($this->_objPHPExcel->getActiveSheet());
	}
	
	/**
	 * Set text wrapping for specified column.
	 * @param integer $column
	 * @param boolean $wrap
	 */
	private function _setColumnWrapping($column, $wrap = true)
	{
		$this->_objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setWrapText($wrap);
	}
	
	private function _setCurrencyFormat($fromRow, $numRows)
	{
		foreach ($this->_currencyFields as $field)
		{
			$endRow = $fromRow + $numRows;
			$labelInd = array_search($field, $this->_fields);
			$labelInd = $this->numberToColumn[$labelInd];
			$this->_objPHPExcel
				->getActiveSheet()
				->getStyle($labelInd . $fromRow . ':' . $labelInd . $endRow)
				->getNumberFormat()
				->setFormatCode("R #,##0.00;[red]R -#,##0.00");
		}
	}
	
	/**
	 * Insert report query details.
	 * @param integer $lineNumber
	 */
	private function _writeQueries($lineNumber)
	{
		$convertTotalToString = $this->numberToColumn[count($this->_headers)-1];
		$this->_objPHPExcel->getActiveSheet()->setCellValue('A'.$lineNumber, 'Filters applied to report:');
		$style_header = array(
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb'=>'aaaaaa'),
				),
				'font' => array(
						'bold' => true,
				)
		);
		$this->_objPHPExcel->getActiveSheet()->getStyle("A$lineNumber:$convertTotalToString".$lineNumber)->applyFromArray($style_header);
		$lineNumber++;
		
		foreach ($this->_queries as $param => $value)
		{
			$this->_objPHPExcel->getActiveSheet()->setCellValue('A'.$lineNumber, $param);
			$this->_objPHPExcel->getActiveSheet()->setCellValue('B'.$lineNumber, $value);
			$this->_objPHPExcel->getActiveSheet()->getStyle('A'.$lineNumber)->applyFromArray(
							array(
									'font' => array('bold' => true),
									'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
									'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb'=>'dddddd'),
									))
							);
			$this->_objPHPExcel->getActiveSheet()->getStyle('A'.$lineNumber.':'.$convertTotalToString.$lineNumber)->applyFromArray(
							array(
									'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
									'fill' => array(
											'type' => PHPExcel_Style_Fill::FILL_SOLID,
											'color' => array('rgb'=>'dddddd'),
									))
							);
			$lineNumber++;
		}
	}
	
	/**
	 * Insert report totals.
	 * @param integer $lineNumber
	 */
	private function _writeTotals($lineNumber)
	{
		$i = 0;
		$convertTotalToString = $this->numberToColumn[count($this->_headers)-1];
		$this->_objPHPExcel->getActiveSheet()->getStyle($this->numberToColumn[$i].$lineNumber.':'.$convertTotalToString.$lineNumber)->applyFromArray(
				array(
						'font' => array('bold' => true),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT),
						'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'dddddd')
						)
				)
		);
		foreach ($this->_fields as $field)
		{
			if (isset($this->_totalAccum[$field]))
			{
				$this->_objPHPExcel->getActiveSheet()->setCellValue(
						$this->numberToColumn[$i].$lineNumber,
						$this->_totalAccum[$field]
						);
			}
			$i++;
		}
	}
	
	/**
	 * Insert report notes.
	 * @param integer $lineNumber
	 */
	private function _writeNotes($lineNumber)
	{
		foreach ($this->_notes as $note)
		{
			$this->_objPHPExcel->getActiveSheet()->setCellValue('A'.$lineNumber, $note);
			$this->_objPHPExcel->getActiveSheet()->getStyle('A'.$lineNumber)->applyFromArray(
							array(
									'font' => array('bold' => true),
									'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
									)
							);
			$lineNumber++;
		}
	}
	
	/**
	 * Insert column headers.
	 * @param integer $lineNumber
	 */
	private function _createHeaders($lineNumber)
	{
		$i = 0;
	
		foreach($this->_headers as $key => $val)
		{
			$this->_objPHPExcel->getActiveSheet()->setCellValue($this->numberToColumn[$i].$lineNumber, $val);
			$this->_objPHPExcel->getActiveSheet()->getStyle($this->numberToColumn[$i].$lineNumber)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT) ) );
			$i++;
		}
	
		#-> Set column sizes
		$this->_objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
		for ($i = 1; $i < count($this->_headers); $i++)
		{
			$this->_objPHPExcel->getActiveSheet()->getColumnDimension($this->numberToColumn[$i])->setAutoSize(true);
		}
		$this->_objPHPExcel->getActiveSheet()->duplicateStyleArray(
				array(
						'font' => array('bold' => true),
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
						'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'aaaaaa')
						)
				),
				$this->numberToColumn[0]. $lineNumber .':'.$this->numberToColumn[$i - 1]. $lineNumber
		);
	}
	
	/**
	 * Write data rows starting at specified line number.
	 * @param integer $lineNumber
	 */
	private function _writeRecords($lineNumber)
	{
		//[green]$#,##0.00;[red]$(-#,##0.00)
		if (!empty($this->_totals))
		{
			foreach ($this->_totals as $field)
			{
				$this->_totalAccum[$field] = 0;
			}
		}
	  $i = 0;
		foreach ($this->_data as $record)
		{
	    if($i > 30000)
	    {
	        return;
	    }
	    // Totals
	    if (!empty($this->_totals))
	    {
	    	foreach ($this->_totals as $field)
	    	{
	    		$this->_totalAccum[$field] += $record[$field];
	    	}
	    }
			// Format record
			$tmpRecord = array();
			foreach($this->_fields as $field)
			{
				$tmpRecord[] = $record[$field];
			}
			$this->_insertValues($tmpRecord, $lineNumber);
			$lineNumber++;
			$i++;
		}
	}
	
	/**
	 * Insert cell values for a data row.
	 * @param array $values
	 * @param integer $lineNumber
	 * @param integer $offset
	 */
	private function _insertValues($values, $lineNumber, $offset = 0)
	{
		$i = 0 + $offset;
		foreach ($values as $key => $val)
		{
			$output = $values[$key];
			if (strpos($val, "<br />") || strpos($val, "<br/>") || strpos($val, "<br>"))
			{
				$this->_setColumnWrapping($this->numberToColumn[$i], true);
			}
			$output = str_replace(array("<br />", "<br/>", "<br>"), "\r", $output);
			$replacable = array("</u>", "<u>", "</b>", "<b>", "</i>", "<i>", "\\", "(", ")");
			foreach($replacable as $val)
			{
				$output = str_replace(
						array($val, '/', '&', '�'),
						array('', ' - ', 'and', ' degrees'),
						$output
				);
			}
			$type = is_numeric($output)
				? PHPExcel_Cell_DataType::TYPE_NUMERIC
				: PHPExcel_Cell_DataType::TYPE_STRING;
			$this->_objPHPExcel->getActiveSheet()->getCell($this->numberToColumn[$i].$lineNumber)
					->setValueExplicit(
							utf8_encode($output), $type
							);
			$i++;
		}
	}
	
	private function _writeChart($lineNumber, $dataStart, $dataEnd)
	{
		$dataCount  = $dataEnd - $dataStart;
		$labelRow   = $dataStart - 1;
		$labelInd   = array_search($this->_chart['LabelSource'], $this->_fields);
		$labelInd   = $this->numberToColumn[$labelInd];
		$labels     = array();
		$categories = array();
		$values     = array();
		foreach ($this->_chart['DataSource'] as $field)
		{
			$fieldInd = array_search($field, $this->_fields);
			$fieldInd = $this->numberToColumn[$fieldInd];
			$labels[] = new PHPExcel_Chart_DataSeriesValues(
					'String',
					'Worksheet!$' . $fieldInd . '$' . $labelRow,
					null, 1
					);
			$categories[] = new PHPExcel_Chart_DataSeriesValues(
					'String',
					'Worksheet!$' . $labelInd . '$' . $dataStart . ':$' . $labelInd . '$' . $dataEnd,
					null, $dataCount
					);
			$values[] = new PHPExcel_Chart_DataSeriesValues(
					'Number',
					'Worksheet!$' . $fieldInd . '$' . $dataStart . ':$' . $fieldInd . '$' . $dataEnd,
					null, $dataCount
					);
		}
		
		$sheet = $this->_objPHPExcel->getActiveSheet();
		$series = new PHPExcel_Chart_DataSeries(
				$this->_chart['Type'],                          // plotType
				$this->_chart['Grouping'],                      // plotGrouping
				$this->_chart['Order'],                         // plotOrder
				$labels,                                        // plotLabel
				$categories,                                    // plotCategory
				$values                                         // plotValues
		);
		$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
		$plotarea = new PHPExcel_Chart_PlotArea(null, array($series));
		$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, null, false);
		$chart = new PHPExcel_Chart(
				$this->_chart['Name'],                          // name
				null,                                           // title
				$legend,                                        // legend
				$plotarea,                                      // plotArea
				true,                                           // plotVisibleOnly
				0,                                              // displayBlanksAs
				null,                                           // xAxisLabel
				null                                            // yAxisLabel
		);
		$chart->setTopLeftPosition('A' . $lineNumber);
		$chart->setBottomRightPosition('J' . ($lineNumber + 15));
		$sheet->addChart($chart);
	}
	
	
}

