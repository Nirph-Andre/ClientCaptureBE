<?php


class Report_BasicExample extends Struct_Abstract_Report
{
	
	/**
	 * @var string
	 */
	protected $_title  = 'Vehicles sold on auction Report';
	/**
	 * @var string
	 */
	protected $_subject = 'History of vehicles sold on auction.';
	/**
	 * @var string
	 */
	protected $_description = 'Historical report for vehicles sold on auction.';
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
			'ID',
			'Year',
			'Make',
			'Vehicle',
			'Buyer',
			'Seller',
			'Sold Price',
			'Date',
			'Bids',
			'Stock No.',
			'Registration No.',
			'Reserve Price',
			'B4C profit'
			);
	/**
	 * @var array
	 */
	protected $_fields = array(
			'auction_id',
			'vehicle_year',
			'make_name',
			'model_name',
			'buyer_name',
			'seller_name',
			'current_bid_price',
			'expire_datetime',
			'number_of_bids',
			'stock_number',
			'registration_number',
			'reserve_price',
			'bid_increment'
			);
	/**
	 * @var array
	 */
	protected $_totals = array(
			'current_bid_price',
			'reserve_price',
			'bid_increment'
			);
	/**
	 * @var array
	 */
	protected $_currencyFields = array(
			'current_bid_price',
			'reserve_price',
			'bid_increment'
			);
	
	
	/**
	 * Build the dataset.
	 */
	public function build()
	{
		#-> Collect data.
		$auth = Struct_Registry::getAuthData();
		if (Struct_Registry::isUserType('User'))
		{
			#-> Non-admin sees less data.
			$this->_headers = array(
					'ID',
					'Year',
					'Make',
					'Vehicle',
					'Dealer - Trading name',
					'Date',
					'Winning bid'
					);
			$this->_fields = array(
					'auction_id',
					'vehicle_year',
					'make_name',
					'model_name',
					'seller_name',
					'expire_datetime',
					'current_bid_price'
					);
			$this->_totals = array(
					'current_bid_price'
					);
		}
		$tbl = new Zend_Db_Table();
		$select = $tbl->getAdapter()
			->select()
			->from('auction',
					array('auction_id' => 'id', 'price', 'current_bid_price',
							'expire_datetime', 'number_of_bids', 'bid_increment'
							))
			->join('dealer_stock',
					'dealer_stock.id=auction.dealer_stock_id',
					array())
			->join('dealer',
					'dealer.id=dealer_stock.dealer_id',
					array('seller_name' => 'trading_name'))
			->join(array('buyer' => 'dealer'),
					'buyer.id=auction.sold_to_dealer_id',
					array('buyer_name' => 'trading_name'))
			->join('stock',
					'stock.id=dealer_stock.stock_id',
					array('stock_number', 'vehicle_year', 'registration_number', 'reserve_price'))
			->join('make',
					'make.id=stock.make_id',
					array('make_name' => 'name'))
			->join('model',
					'model.id=stock.model_id',
					array('model_name' => 'name'))
			->where('auction.previous_reserve > ?', 0)
			->where('auction.status = ?', 'Sold');
		if (Struct_Registry::isUserType('User'))
		{
			$select->where('dealer.id = ?', $auth['dealer']['id']);
		}
		elseif (isset($this->_input['dealer_id']) && !empty($this->_input['dealer_id']))
		{
			$select->where('dealer.id = ?', $this->_input['dealer_id']);
			$this->_queries['Seller'] = $this->_getObject('Dealer')
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
		if (isset($this->_input['date_from']) && !empty($this->_input['date_from']))
		{
			$select->where('auction.expire_datetime >= ?', $this->_input['date_from']);
			$this->_queries['From'] = $this->_input['date_from'];
		}
		if (isset($this->_input['date_to']) && !empty($this->_input['date_to']))
		{
			$select->where('auction.expire_datetime < ?', $this->_input['date_to']);
			$this->_queries['Until'] = $this->_input['date_to'];
		}
		$this->_data = $select->order('dealer_stock.id ASC')
			->order('auction.id ASC')
			->query(Zend_Db::FETCH_ASSOC)
			->fetchAll();
	}
	
}

