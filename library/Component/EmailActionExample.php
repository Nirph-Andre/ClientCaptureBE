<?php


/**
 * Email action link handler for Club Offers.
 * @author andre.fourie
 */
class Component_EmailActionExample
{
	
	public function handleExtensionRequest($data)
	{
		#-> Safety check.
		if (!isset($data['response'])
				|| ('Accept' != $data['response'] && 'Reject' != $data['response']))
		{
			throw new Exception('Invalid parameters passed.');
		}
		
		#-> Collect data.
		$oClubOffer   = new Object_DealerClubStockOffer();
		$oDealer      = new Object_Dealer();
		$oDealerClub  = new Object_DealerClub();
		$oDealerStock = new Object_DealerStock();
		$oStock       = new Object_Stock();
		$offer        = $oClubOffer->view($data['id'], array(), true)->data;
		$club         = $oDealerClub->view($offer['dealer_club_stock']['dealer_club_id'])->data;
		$seller       = $oDealer->view($club['dealer_id'], array(), true)->data;
		$dealerStock  = $oDealerStock->view($offer['dealer_club_stock']['dealer_stock_id'])->data;
		$stock        = $oStock->view($dealerStock['stock_id'], array(), true)->data;
		$vehicle      = $stock['make']['name'] . ', ' . $stock['model']['name'] . ', ' . $stock['type']['name'];
		$response     = 'Accept' == $data['response'] 
			? 'accepted'
			: 'rejected';
		$templateParams = array(
				'vehicle'        => $vehicle,
				'buyer'          => $offer['dealer']['trading_name'],
				'seller'         => $seller['trading_name'],
				'amount'         => 'R ' .$offer['price'],
				'status'         => $response
		);
		
		#-> Extend offer?
		if ('Accept' == $data['response'])
		{
			#-> Update offer to reflect new deadline.
			$newDate = date('Y-m-d H:i:s', strtotime($offer['updated']) + ($data['days'] * 86400));
			$update  = array('updated' => $newDate);
			$where   = array('id = ?' => $data['id']);
			$tbl     = new Zend_Db_Table('dealer_club_stock_offer');
			$tbl->update($update, $where);
			$newDate = date('Y-m-d H:i:s', strtotime($offer['dealer_club_stock']['updated']) + ($data['days'] * 86400));
			$update  = array('updated' => $newDate);
			$where   = array('id = ?' => $offer['dealer_club_stock']['id']);
			$tbl     = new Zend_Db_Table('dealer_club_stock');
			$tbl->update($update, $where);
		}
		
		#-> Send acceptance/rejection notification.
		Component_Notification::sendFromTemplate(
				$seller['lib_contact']['email'], false, 'Response to extend the confirmation of offer on ' . $vehicle,
				'Club Stock Offer Extension Response', $templateParams
		);
		
		return $seller['trading_name'] . ' have been notified that the offer extension have been ' . $response . '.';
	}
	
	
}


