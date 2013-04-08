<?php


require_once('PHPExcel.php');


class Report_ChartExample extends Struct_Abstract_Report
{
	
	/**
	 * @var string
	 */
	protected $_title  = 'Loaded and Sold';
	/**
	 * @var string
	 */
	protected $_subject = 'Vehicles loaded vs vehicles sold.';
	/**
	 * @var string
	 */
	protected $_description = 'Vehicles loaded vs vehicles sold.';
	/**
	 * @var array
	 */
	protected $_notes = array(
			'Confidential information, generated using Bid 4 Cars, for more information visit demo.bid4cars.co.za'
			);
	/**
	 * @var array
	 */
	protected $_headers = array(
			'Month',
			'Loaded',
			'Sold',
			'No-match',
			'SPL/True'
			);
	/**
	 * @var array
	 */
	protected $_fields = array(
			'month',
			'loaded',
			'sold',
			'nomatch',
			'spl'
			);
	/**
	 * @var array
	 */
	protected $_totals = array(
			'loaded',
			'sold',
			'nomatch',
			'spl'
			);
	/**
	 * @var array
	 */
	protected $_currencyFields = array(
			'spl'
			);
	/**
	 * @var array
	 */
	protected $_chart = array(
			'Name'        => 'LoadedVsSold',
			'Type'        => PHPExcel_Chart_DataSeries::TYPE_LINECHART,
			'Grouping'    => PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
			'Order'       => array(0, 1),
			'LabelSource' => 'month',
			'DataSource'  => array('loaded', 'sold')
			);
	
	
	/**
	 * Build the dataset.
	 */
	public function build()
	{
		#-> Base dataset.
		$isUser = Struct_Registry::isUserType('User');
		if ($isUser)
		{
			#-> Non-admin sees less data.
			$this->_headers = array(
					'Month',
					'Loaded',
					'Sold'
					);
			$this->_fields = array(
					'month',
					'loaded',
					'sold'
					);
			$this->_totals = array(
					'loaded',
					'sold'
					);
		}
		elseif (
				(isset($this->_input['dealer_id']) && !empty($this->_input['dealer_id']))
				|| (isset($this->_input['group_id']) && !empty($this->_input['group_id']))
				|| (isset($this->_input['group_division_id']) && !empty($this->_input['group_division_id']))
				|| (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
				)
		{
			$this->_headers = array(
					'Month',
					'Loaded',
					'Sold',
					'SPL/True'
			);
			$this->_fields = array(
					'month',
					'loaded',
					'sold',
					'spl'
			);
			$this->_totals = array(
					'loaded',
					'sold',
					'spl'
			);
		}
		$auth = Struct_Registry::getAuthData();
		$startYear = isset($this->_input['start_year'])
			? $this->_input['start_year']
			: date('Y');
		$endYear = isset($this->_input['end_year'])
			? $this->_input['end_year']
			: date('Y');
		$startMonth = isset($this->_input['start_month'])
			? $this->_input['start_month']
			: 1;
		$endMonth = isset($this->_input['end_month'])
			? $this->_input['end_month']
			: date('m');
		if ($startYear > $endYear || $startMonth > $endMonth)
		{
			$swap = array($startYear, $startMonth);
			$startYear = $endYear;
			$startMonth = $endMonth;
			$endYear = $swap[0];
			$endMonth = $swap[1];
		}
		$endMonth++;
		if ($endMonth > 12)
		{
			$endYear++;
			$endMonth = 1;
		}
		$startDate = "$startYear-$startMonth-01";
		$endDate = "$endYear-$endMonth-01";
		$this->_data = array();
		for ($year = $startYear; $year <= $endYear; $year++)
		{
			$finalMonth = ($year == $endYear)
				? $endMonth
				: 13;
			for ($month = $startMonth; $month < $finalMonth; $month++)
			{
				$monthName = "$year " . date("F", mktime(1, 1, 1, $month, 1, date("Y")));
				$this->_data["$year" . str_pad($month, 2, '0', STR_PAD_LEFT)] = array(
						'month'   => $monthName,
						'loaded'  => 0,
						'sold'    => 0,
						'nomatch' => 0,
						'spl'     => 0
						);
			}
			$startMonth = 1;
		}
		
		#-> Collect loaded totals.
		$tbl = new Zend_Db_Table();
		$select = $tbl->getAdapter()
			->select()
			->from('dealer_stock',
					array(
							'month' => 'DATE_FORMAT(dealer_stock.created, "%Y %M")',
							'odate' => 'DATE_FORMAT(dealer_stock.created, "%Y%m")',
							'total' => 'COUNT(dealer_stock.id)'
							))
			->join('dealer',
					'dealer.id = dealer_stock.dealer_id',
					array());
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select
				->join('dealer_address',
						'dealer_address.dealer_id = dealer_stock.dealer_id AND dealer_address_type_id=1',
						array())
				->join('lib_address',
						'lib_address.id = dealer_address.lib_address_id',
						array())
				->join('lib_city',
						'lib_city.id = lib_address.lib_city_id',
						array());
		}
		if (Struct_Registry::isUserType('User'))
		{
			$select->where('dealer_stock.dealer_id = ?', $auth['dealer']['id']);
		}
		elseif (isset($this->_input['dealer_id']) && !empty($this->_input['dealer_id']))
		{
			$select->where('dealer_stock.dealer_id = ?', $this->_input['dealer_id']);
			$this->_queries['Dealer'] = $this->_getObject('Dealer')
				->view($this->_input['dealer_id'])
				->data['trading_name'];
		}
		if (isset($this->_input['group_id']) && !empty($this->_input['group_id']))
		{
			$select->where('dealer.group_id = ?', $this->_input['group_id']);
			$this->_queries['Group'] = $this->_getObject('Group')
				->view($this->_input['group_id'])
				->data['name'];
		}
		if (isset($this->_input['group_division_id']) && !empty($this->_input['group_division_id']))
		{
			$select->where('dealer.group_division_id = ?', $this->_input['group_division_id']);
			$this->_queries['Division'] = $this->_getObject('GroupDivision')
				->view($this->_input['group_division_id'])
				->data['name'];
		}
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select->where('lib_city.lib_region_id = ?', $this->_input['lib_region_id']);
			$this->_queries['Region'] = $this->_getObject('LibRegion')
				->view($this->_input['lib_region_id'])
				->data['name'];
		}
		$data = $select
			->where('dealer_stock.created >= ?', $startDate)
			->where('dealer_stock.created < ?', $endDate)
			->group(array('odate', 'month'))
			->query(Zend_Db::FETCH_ASSOC)
			->fetchAll();
		foreach ($data as $record)
		{
			$this->_data[$record['odate']]['loaded'] = $record['total'];
		}
		
		
		#-> Collect sold totals.
		$tbl = new Zend_Db_Table();
		$select = $tbl->getAdapter()
			->select()
			->from('dealer_stock',
					array(
							'month' => 'DATE_FORMAT(auction.updated, "%Y %M")',
							'odate' => 'DATE_FORMAT(auction.updated, "%Y%m")',
							'total' => 'COUNT(dealer_stock.id)'
					))
			->join('dealer',
					'dealer.id = dealer_stock.dealer_id',
					array())
			->join('auction',
					"auction.dealer_stock_id = dealer_stock.id AND auction.status='Sold'",
					array());
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select
				->join('dealer_address',
						'dealer_address.dealer_id = dealer_stock.dealer_id AND dealer_address_type_id=1',
						array())
				->join('lib_address',
						'lib_address.id = dealer_address.lib_address_id',
						array())
				->join('lib_city',
						'lib_city.id = lib_address.lib_city_id',
						array());
		}
		if (Struct_Registry::isUserType('User'))
		{
			$select->where('dealer_stock.dealer_id = ?', $auth['dealer']['id']);
		}
		elseif (isset($this->_input['dealer_id']) && !empty($this->_input['dealer_id']))
		{
			$select->where('dealer_stock.dealer_id = ?', $this->_input['dealer_id']);
			$this->_queries['Dealer'] = $this->_getObject('Dealer')
				->view($this->_input['dealer_id'])
				->data['trading_name'];
		}
		if (isset($this->_input['group_id']) && !empty($this->_input['group_id']))
		{
			$select->where('dealer.group_id = ?', $this->_input['group_id']);
			$this->_queries['Group'] = $this->_getObject('Group')
				->view($this->_input['group_id'])
				->data['name'];
		}
		if (isset($this->_input['group_division_id']) && !empty($this->_input['group_division_id']))
		{
			$select->where('dealer.group_division_id = ?', $this->_input['group_division_id']);
			$this->_queries['Division'] = $this->_getObject('GroupDivision')
				->view($this->_input['group_division_id'])
				->data['name'];
		}
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select->where('lib_city.lib_region_id = ?', $this->_input['lib_region_id']);
			$this->_queries['Region'] = $this->_getObject('LibRegion')
				->view($this->_input['lib_region_id'])
				->data['name'];
		}
		$data = $select
			->where('auction.updated >= ?', $startDate)
			->where('auction.updated < ?', $endDate)
			->group(array('odate', 'month'))
			->query(Zend_Db::FETCH_ASSOC)
			->fetchAll();
		foreach ($data as $record)
		{
			$this->_data[$record['odate']]['sold'] = $record['total'];
		}
		
		
		#-> Collect no-match totals.
		if (
				(!isset($this->_input['dealer_id']) || empty($this->_input['dealer_id']))
				&& (!isset($this->_input['group_id']) || empty($this->_input['group_id']))
				&& (!isset($this->_input['group_division_id']) || empty($this->_input['group_division_id']))
				&& (!isset($this->_input['lib_region_id']) || empty($this->_input['lib_region_id']))
				)
		{
			$tbl = new Zend_Db_Table();
			$select = $tbl->getAdapter()
				->select()
				->from('automate',
						array(
								'month' => 'DATE_FORMAT(automate.sale_date, "%Y %M")',
								'odate' => 'DATE_FORMAT(automate.sale_date, "%Y%m")',
								'total' => 'COUNT(automate.id)'
						));
			$data = $select
				->where('automate.stock_id IS NULL')
				->where('automate.sale_date >= ?', $startDate)
				->where('automate.sale_date < ?', $endDate)
				->group(array('odate', 'month'))
				->query(Zend_Db::FETCH_ASSOC)
				->fetchAll();
			foreach ($data as $record)
			{
				$this->_data[$record['odate']]['nomatch'] = $record['total'];
			}
		}
		
		
		#-> Collect SPL totals.
		$tbl = new Zend_Db_Table();
		$select = $tbl->getAdapter()
			->select()
			->from('dealer_stock',
					array(
							'month' => 'DATE_FORMAT(dealer_stock.created, "%Y %M")',
							'odate' => 'DATE_FORMAT(dealer_stock.created, "%Y%m")',
							'total' => 'SUM(automate.sales_total) - SUM(automate.total_cost)'
					))
			->join('dealer',
					'dealer.id = dealer_stock.dealer_id',
					array())
			->join('automate',
					'automate.stock_id = dealer_stock.stock_id',
					array());
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select
				->join('dealer_address',
						'dealer_address.dealer_id = dealer_stock.dealer_id AND dealer_address_type_id=1',
						array())
				->join('lib_address',
						'lib_address.id = dealer_address.lib_address_id',
						array())
				->join('lib_city',
						'lib_city.id = lib_address.lib_city_id',
						array());
		}
		if (Struct_Registry::isUserType('User'))
		{
			$select->where('dealer_stock.dealer_id = ?', $auth['dealer']['id']);
		}
		elseif (isset($this->_input['dealer_id']) && !empty($this->_input['dealer_id']))
		{
			$select->where('dealer_stock.dealer_id = ?', $this->_input['dealer_id']);
			$this->_queries['Dealer'] = $this->_getObject('Dealer')
				->view($this->_input['dealer_id'])
				->data['trading_name'];
		}
		if (isset($this->_input['group_id']) && !empty($this->_input['group_id']))
		{
			$select->where('dealer.group_id = ?', $this->_input['group_id']);
			$this->_queries['Group'] = $this->_getObject('Group')
				->view($this->_input['group_id'])
				->data['name'];
		}
		if (isset($this->_input['group_division_id']) && !empty($this->_input['group_division_id']))
		{
			$select->where('dealer.group_division_id = ?', $this->_input['group_division_id']);
			$this->_queries['Division'] = $this->_getObject('GroupDivision')
				->view($this->_input['group_division_id'])
				->data['name'];
		}
		if (isset($this->_input['lib_region_id']) && !empty($this->_input['lib_region_id']))
		{
			$select->where('lib_city.lib_region_id = ?', $this->_input['lib_region_id']);
			$this->_queries['Region'] = $this->_getObject('LibRegion')
				->view($this->_input['lib_region_id'])
				->data['name'];
		}
		$data = $select
			->where('dealer_stock.created >= ?', $startDate)
			->where('dealer_stock.created < ?', $endDate)
			->group(array('odate', 'month'))
			->query(Zend_Db::FETCH_ASSOC)
			->fetchAll();
		foreach ($data as $record)
		{
			$this->_data[$record['odate']]['spl'] = $record['total'];
		}
	}
	
}

