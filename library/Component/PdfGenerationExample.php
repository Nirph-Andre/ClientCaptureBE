<?php


/**
 * Handle pdf exports of vehicle details, with main photo included.
 * @author andre.fourie
 */
class Component_PdfGenerationExample
{
	
	/**
	 * Handle pdf export of vehicle details, with main photo included.
	 * @param integer $stockId
	 * @return string
	 */
	public function toPdf($stockId, $preview = false)
	{
		#-> Grab lots of data.
    	$oStock = new Object_Stock();
    	$oStockAcc = new Object_StockAccessory();
    	$oStockDamage = new Object_StockDamage();
    	$oVehicleType = new Object_VehicleType();
    	$oPhoto = new Object_LibPhoto();
    	$stock = $oStock
	    	->view($stockId, array(), true)
	    	->data;
      $vehicleTypeMap = array(
        		'T' => 'Agricultural',
        		'A' => 'Auto or Passenger',
        		'C' => 'Motorcycle',
        		'B' => 'Light Commercial',
        		'M' => 'Medium Commercial',
        		'H' => 'Heavy Commercial',
        		'Z' => 'Bus'
        		);
    	$stock['vehicle_type'] = $vehicleTypeMap[$oVehicleType->view($stock['type']['vehicle_type_id'])->data['name']];
    	$photo = !is_null($stock['main_lib_photo']['id'])
    		? $oPhoto->view($stock['main_lib_photo']['id'])->data
    		: false;
    	
    	$vehicle = $stock['make']['name'] . ', ' . $stock['model']['name'] . ', ' . $stock['type']['name'] . '.';
    	$accessories = $oStockAcc->grid(
    			array('stock_id' => $stockId), array(),
    			null, null,
    			array(), array('stock')
    	)->data;
    	$damages = $oStockDamage->grid(
    			array('stock_id' => $stockId), array(),
    			null, null,
    			array(), array('stock')
    	)->data;
    	
    	$mainImage = '<img src="'.APPLICATION_PATH.'/../public/images/vehicle-no-image.jpg" style="padding:0;margin:0;width:100%;" />';
    	if ($preview)
    	{
    		if ($photo)
    		{
    			$mainImage = '<img src="/image?id=' . $photo['id'] . '" style="padding:0;margin:0;width:100%;" />';
    		}
    		else
    		{
    			$mainImage = '<img src="/images/vehicle-no-image.jpg" style="padding:0;margin:0;width:100%;" />';
    		}
    	}
    	elseif ($photo)
    	{
    		switch($photo['mime_type'])
    		{
    			case 'image/jpeg': $ext = '.jpg'; break;
    			case 'image/pjpeg': $ext = '.jpg'; break;
    			case 'image/bmp': $ext = '.bmp'; break;
    			case 'image/x-windows-bmp': $ext = '.bmp'; break;
    			case 'image/gif': $ext = '.gif'; break;
    			case 'image/png': $ext = '.png'; break;
    		}
    		$name = 'stock-main-'.mt_rand(1000000, 9999999) . $ext;
	    	$picFile = APPLICATION_PATH.'/../public/files/' . $name;
	    	$res = file_put_contents($picFile, $photo['photo']);
	    	$mainImage = '<img src="'.$picFile.'" style="padding:0;margin:0;width:100%;" />';
    	}
    	
    	$extras = array();
    	foreach ($accessories as $extra)
    	{
    		$extras[] = $extra['accessory']['name'];
    	}
    	$extras = implode(', ', $extras);
    	
    	$currency = Component_Config::getCurrencyPrefix();
    	
    	$damagesList = '';
    	if (!empty($damages))
    	{
			$damagesList .= '
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;">&nbsp;</td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">&nbsp;</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;width:23%;">&nbsp;</td>
					<td style="padding:5px 0 7px 0;margin:0;width:77%;"><b>DAMAGES</b></td>
				</tr>';
    		foreach ($damages as $damage)
    		{
    			$component = $damage['damage']['name'] . ', ' . $damage['damage_component']['name']
    						. ' at estimated cost of ' . $currency . number_format($damage['estimated_price']);
    			$damagesList .= '
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Item:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$component.'</td>
				</tr>';
    		}
    	}
    	$logo = $preview
    		? '/images/bid4cars-logo-new.jpg'
    		: APPLICATION_PATH.'/../public/images/bid4cars-logo-new.jpg';
    	$width = $preview
    		? '800px'
    		: '100%';
    	$halfWidth = $preview
    		? '390px'
    		: '49%';
    	$container = $preview
    		? 'div'
    		: 'page';
    	
    	#-> Grab a template.
    	$pdfTemplate = '
<' . $container . ' style="padding:0;margin:0;width:' . $width . ';font-size:12px;">
	<div style="position:absolute;top:0;left:0;padding:0;margin:0;width:' . $width . ';height:9%;">
		<img src="' . $logo . '" style="padding:0;margin:0;width:' . $width . ';" />
	</div>
	<div style="position:absolute;top:110px;left:0;padding:0;margin:0;width:' . $halfWidth . ';height:90%;display:block;overflow:hidden;">
		<div style="position:absolute;top:0px;left:0;padding:0;margin:0;width:100%;display:block;overflow-x:hidden;">
			<table cellpadding="3px" cellspacing="0" style="padding:0;margin:0;width:100%;">
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Reference #:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['reference_no'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Stock #:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['stock_number'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Registration:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['registration_number'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>MM Code:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['mm_code'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Natis:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['natis']['name'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Trade:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$currency.number_format($stock['trade_price']).'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Retail:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$currency.number_format($stock['retail_price']).'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>VIN:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['vin_number'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Engine #:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['engine_number'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>FSH:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['full_service_history']['name'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>FSH Notes:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['full_service_history_notes'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Papers:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['vehicle_paper']['name'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Remarks</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['remarks'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Previous Repairs:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['previous_repairs_noted'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Faults Explanation:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['faults_explenation'].'</td>
				</tr>'.$damagesList.'
			</table>
		</div>
	</div>
	<div style="position:absolute;top:110px;left:410px;padding:0;margin:0;width:' . $halfWidth . ';height:90%;display:block;overflow:hidden;">
		'.$mainImage.'
		<div style="position:absolute;top:300px;left:0;padding:0;margin:0;width:100%;display:block;overflow-x:hidden;">
			<table cellpadding="3px" cellspacing="0" style="padding:0;margin:0;width:100%;">
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Vehicle:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$vehicle.'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Type:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['vehicle_type'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Exterior:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['base_color']['name'].', '.$stock['specific_color']['name'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Interior:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['interior']['name'].', '.$stock['interior_color']['name'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Year:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['vehicle_year'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>KM:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$stock['km'].'</td>
				</tr>
				<tr>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:23%;"><b>Accessories:</b></td>
					<td style="padding:5px 0 7px 0;margin:0;border-top: solid 1px #999;width:77%;">'.$extras.'</td>
				</tr>
			</table>
		</div>
	</div>
</' . $container . '>';
    	
    	#-> Lets try a pdf -oO-
    	if ($preview)
    	{
    		return $pdfTemplate;
    	}
    	$html2pdf = new Html2Pdf_Main('P', 'A4', 'en');
    	$html2pdf->WriteHTML($pdfTemplate);
    	$result = $html2pdf->Output('vehicle.pdf', 'S');
    	if (isset($picFile))
    	{
    		unlink($picFile);
    	}
    	return $result;
	}
	
	
}

