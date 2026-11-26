<?php
include('./static/hotel/newfile.php');
class Hotel_insert_model extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getResponse()
	{ 
		$hotelData= array();
		$hotelData=getHotelTickets();
		if($hotelData){
			$hotelArray = json_decode($hotelData,   true);

			foreach ($hotelArray as $hotel_key => $hotel_item) {
				switch($hotel_item['Status']){
					case 'CancelledBooking':
						$this->voidHotel($hotel_item);
						break;

					case 'Invoiced':
						$this->insertHotel($hotel_item);
						break;

					case 'CancelledBookingAndRequestedToRefund': 
						$this->refundHotel($hotel_item);	
						break;

				} //end switch

			}
		}
	}//end funciton

	function insertHotel($hotel_item)
	{
		$i=0;

		//pre processing
		$vTicktManual = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE vTicktManual  = " .$hotel_item['VoucherNumber']. "");
		if($vTicktManual==""){
		$userId =  $this->common_model->get_field_name('Admin','nId'," WHERE nEmpId= '1'");

		// only one division is present
		$division =  $this->common_model->get_field_name('Division','nId'," WHERE vActive = '1'");
		//only one location is present
		$location =  $this->common_model->get_field_name('Location','nId'," WHERE vActive = '1' AND nDivision = " .$division . " ");


		$servId = $this->common_model->get_field_name('Services','nServId'," WHERE vCode = 'HTL'"); 
		$cityId = $this->common_model->get_field_name('Country','nId'," WHERE vName = '". $hotel_item['CityName'] . "'");
		
		
		$countryId = $this->common_model->get_field_name('Country','nId'," WHERE vName = '". $hotel_item['CountryName'] . "'");
		if($countryId == '') {
			$admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "Country Inserted";
			$comment = "Country Name:".$hotel_item['CountryName'];
			//mail($admin_email, "$subject", $comment, "From:" . $email);
			
			$data 	= array('nUserId' => $userId ,
					'vName' =>  $hotel_item['CountryName'] ,
					'vActive' => '1',
					'vShortName' => substr($$hotel_item['CountryName'],0,3),
					'nParentId' => '0', 
					 );
			echo "Insert Country <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->insert('{PRE}Country', $data); 
			$countryId = $this->common_model->get_field_name('Country','nId'," WHERE vName = '". $hotel_item['CountryName'] . "'");
		
		}
		
		if($cityId == '') {
			$admin_email = "balu@abskw.com";
			 $email = "sys@abskw.com";
			 $subject = "CITY Inserted";
			 $comment = $hotel_item['CountryName'].":".$hotel_item['CityName'];
 
			//mail($admin_email, "$subject", $comment, "From:" . $email);
			$data 	= array('nUserId' => $userId ,
					'vName' =>  $hotel_item['CityName'] ,
					'vActive' => '1',
					'vShortName' => substr($$hotel_item['CityName'],0,3),
					'nParentId' => $countryId, 
					 );
			echo "Insert City <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->insert('{PRE}Country', $data); 
			$cityId = $this->common_model->get_field_name('Country','nId'," WHERE vName = '". $hotel_item['CityName'] . "'");
		

		}
		$hotelId = $this->common_model->get_field_name('Hotel','nHId'," WHERE vName = '". $hotel_item['HotelName'] ."' AND
				nCity = " .$cityId . " AND nCountry = " .$countryId  . " ");

		if($hotelId == '') {
			$data 	= array('nUserId' => $userId ,
					'vName' =>  $hotel_item['HotelName'] ,
					'vActive' => '1',
					'nCity' => $cityId ,
					'nCountry' => $countryId, 
					'nService' => $servId 
					//						'vPhone' =>
					//						'vFax' => 
				       );
			echo "Insert Hotel <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->insert('{PRE}Hotel', $data); 
			$hotelId = $this->common_model->get_field_name('Hotel','nHId'," WHERE vName = '". $hotel_item['HotelName'] ."' AND
					nCity = " .$cityId . " AND nCountry = " .$countryId  . " ");


		}
		$issuedId =  $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE ets = '".$hotel_item['IssuedID']. "'");



            if($issuedId ==''){


                $issued_data = array(
				'ets' => $hotel_item['IssuedID'],
					'vCode' => 'ETS('.substr($hotel_item['IssuedID'],0,3).')',
                    'vLocations' => '1',
					
                    'vLocation' => '1');

                $this->db->insert('{PRE}EmployeeMaster', $issued_data);

                $issuedId =$this->db->insert_id();

            }
		

		$accountCodeId = $this->common_model->get_field_name('VendorMaster','nVId'," WHERE vCode = '" . $hotel_item['ProviderId'] . "'");
		if($accountCodeId==''){
			$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
							'nFId' => '34',
							'dDate' => date('Y-m-d'),

						'vCode' => $hotel_item['ProviderId'],
						'vName' => $hotel_item['ProviderName'],
						'vLocation' => '1',
						'nCountry' => '5',
						'nCurrency' => '5',
						'vActive' => '1');

		$this->db->insert('{PRE}VendorMaster', $data); 
		}
		$accountCodeId = $this->common_model->get_field_name('VendorMaster','nVId'," WHERE vCode = '" . $hotel_item['ProviderId'] . "'");
		
		$accountHeadId = 34;//$this->common_model->get_field_name('VendorMaster','nFId'," WHERE vCode = '" . $hotel_item['HotelApiName'] . "'");

		$currencyId =  $this->common_model->get_field_name('Currency','nId'," WHERE vCurrcode = '" . $hotel_item['Currency']. "'");

		$totalRTDC = $hotel_item['NetFarePerNight'] *  $hotel_item['Rate'];
		$totalDC = $totalRTDC * $hotel_item['NumberOfNights'] * $hotel_item['RoomCount'];

		$status = 'N';
		$issuedDate = str_replace('/', '-',$hotel_item['IssuedDate']);
		$checkIn = str_replace('/', '-',$hotel_item['CheckIn']);
		$checkOut = str_replace('/', '-',$hotel_item['CheckOut']);
			if (strpos($hotel_item['Services'],'Breakfast') !== false) {
					$extraser=2;
				}else{
					$extraser=1;
				}
		//insert into hotel voucher master table
		$data   = array(
				'nUserId' => $userId, 
				'nDivision' => $division,
				'nLocation' => $location,
				'dDate' =>date('Y-m-d', strtotime($issuedDate)),
				//				    'nTransaction' =>  not used
				'vDocNumber' => $this->getAutomaticDocNumber(), 
				'vType' => $status, 
//				'vVoucherId' => 
				'vIssuedBy' => $issuedId,
				'vBookedBy' => $issuedId,
				'vPassenger' => $hotel_item['PassengerName'], 
				'nCountry' => $countryId, 
				'nCity' =>  $cityId,
				'vHotelName' => $hotelId,
				'vRoomType' => $hotel_item['RoomType'], 
				'vNoOfUnit' => $hotel_item['RoomCount'],
				'vServices' => $servId,
				//				    'nTransfer' => Not Used
				'vExtraService' => $extraser,
				'dCheckIn' =>  date('Y-m-d h:m:s', strtotime($checkIn)),
				'dCheckOut' => date('Y-m-d h:m:s', strtotime($checkOut)),
				'vNight' =>  $hotel_item['NumberOfNights'],
				'vAdult' =>  $hotel_item['Adult'],
				'vChild' =>  $hotel_item['Child'],
				'vRemarks' => $hotel_item['Remarks'],
				'nAcCode' => $accountCodeId,
				'nAcHead' => $accountHeadId,
				'nCurrency' => $currencyId, 
				'nRate' =>  $hotel_item['Rate'], 
				'vTotalRTFC' => $hotel_item['NetFarePerNight'],
				'vTotalFC' =>  $hotel_item['NetFareTotal'],
				'vTotalRTDC' => $totalRTDC,
				'vTotalDC' =>  $totalDC,
				'vAgentConfId' =>  $hotel_item['AgentConfirmationNumber'], 
				'vHotelConfId' => $hotel_item['HotelConfirmationNumber'],
				'vBookingId' => $hotel_item['vBookingId'],
				'vActive' => '1', 
				'vTicktManual' => $hotel_item['VoucherNumber'],
				'vIssuedEmailId' => $hotel_item['IssuedID']
					);

		//echo "Insert Hotel Voucher <br>"; echo('<pre>');  print_r($data); echo('</pre>');
		$this->db->insert('{PRE}HotelVoucher',   $data);  
		$id	    =  $this->db->insert_id();	
		/*
		//insert into hotel transfer details table
		$data   = array('nHVRId' => $id,  
		'dDate1' => 
		'vArrivalFrom' =>
		'vFlightId1' => 
		'vArrivalTime' =>
		'dDate2' =>
		'vDepTo' =>
		'vFlightId2' =>
		'vDepTime' => 
		'nNoofGuest' =>
		'vVehicle' =>
		'vDriverContact' =>
		'vEmergencyContact' =>
		'vPickup' =>
		'dPickupDate' =>
		'vDrop' =>
		'vRepHotel' =>  
		'vCityTour' =>
		'vDurTime' =>
		'vRemarks' =>
		$this->db->insert('{PRE}HotelTransferDetails',   $data); 
		 */	


		//insert into hotel Details table
		$data 	= array('nHotelId' => $id , 
				'vRoomType' => $hotel_item['RoomType'], 
				'dCheckInDate' => date('Y-m-d h:m:s', strtotime($checkIn)),
				'dCheckOutDate' => date('Y-m-d h:m:s', strtotime($checkOut)),
				'nNights' => $hotel_item['NumberOfNights'],
				'nNoOfRooms' => $hotel_item['RoomCount'],
				'nRTNhtFc' => $hotel_item['NetFarePerNight'],
				'vTotalInFc' => $hotel_item['NetFareTotal'],
				'nRTNhtDc' => $totalRTDC,
				'vTotalInDC' => $totalDC,
				'vExchRate' =>  $hotel_item['Rate']
			       );

		//echo "Insert Hotel Details <br>"; echo('<pre>');  print_r($data); echo('</pre>');
		$this->db->insert('{PRE}HotelDetails',   $data);

		$serviceAmt = $hotel_item['SellingFareTotal'] - $totalDC;
		$servicePercent = ($serviceAmt/ $totalDC) * 100;

		$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vCode ='" .  $hotel_item['AgencyId'] ."'");
		if($customerAc==''){
			$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
						'nFId' => '137',
						'dDate' => date('Y-m-d'),
						'vCode' => $hotel_item['AgencyId'],
						'vName' => $hotel_item['AgencyName'],
						'vLocation' => '1',
						'nCountry' => '5',
						'nCurrency' => '5',
						'vActive' => '1');
echo "Customer Details <br>"; echo('<pre>');  print_r($data); echo('</pre>');
	
		$this->db->insert('{PRE}CustomerMaster', $data); 
		$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vCode ='" .  $hotel_item['AgencyId'] ."'");
		}	   

		//insert into Hotel billing Details table
		$data 	= array(
				'nHotelId' => $id,
				'nCustomerAc' => $customerAc, 
				//			'vModeofpay' =>
				//			'nAgentAc' =>
				//			'vAgentAmt' =>
				//			'vLPO' =>
				'vSalesAmt' =>  $hotel_item['SellingFareTotal'],
				//			'vDiscount' =>
				//			'vDiscountAmt' =>
				//			'vCollected' =>
				'vServicePercent' => $servicePercent,
				'vServiceAmt' => $serviceAmt 
			       );


		//echo "Insert Hotel Billing Details <br>"; echo('<pre>');  print_r($data); echo('</pre>');
		$this->db->insert('{PRE}HotelBillingDetails',   $data);


		echo $cnt.' record is inserted successfully.';
		
		//Sales closing start
		$invNo = $this->getAutomaticInvNo(2,1);
	 $servAmt=$hotel_item['SellingFareTotal']-$totalDC;
	 $servPerc = $servAmt / $totalDC * 100;
	 $issued_date=date('Y-m-d', strtotime($issuedDate));
	
	
	$data = array('nUserId' => $this->session->userdata('AbsAdmin'),
					'nDivision' => 1,
					'vLocation' =>1,

            'dDate' => $issued_date,
			'vTransMode' => 2,
			'vInvoiceNumber' => $invNo,
			'vTotalFace' => $totalDC, 

            'vTotalTax' => 0,

            'vTotalCommPercent' => 0,

            'vTotalCommAmt' => 0,

            'vTotalNetAmount' => $totalDC,

            'vTotalServPercent' => $servPerc, 

            'vTotalServAmount' => $servAmt,

            'vTotalSalesAmt' => $hotel_item['SellingFareTotal'], 

            'vTotalDiscAmount' => 0,

            'vTotalCollected' => $hotel_item['SellingFareTotal'],

            'vTotalAgentAmt' => 0,

            'vPassName' => $hotel_item['PassengerName'], 
			'vGds' => 0,

            'nInvoiceStatus' => 'N',
			'vInvoiceTo' => $hotel_item['AgencyName'], 

            'vActive' => '1','edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
echo "<br>Sales Closing Inserted<br>"; echo('<pre>');  print_r($data); echo('</pre>');
				
                $this->db->insert('{PRE}SalesClosing', $data);
				
				$Salesid  = $this->db->insert_id();
		$getDivison=1;
		$getLocation=1;
        $newId = $this->jvInsert($Salesid,$getDivison,$getLocation,$invNo,$issued_date);

//var_dump();

  $emp=$this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId = '".$issuedId. "'" );


          $data = array('nSalesCloseId' => $Salesid,

              'vType' => 'Htl',

              'nAirline' => 0,

              'vTktNumber' => $id,

              'vNetAmt' => $totalDC,  

              'vServPercent' => $servPerc,

              'vEmployee' => $emp,

              //              'vFare' => $Fare[$key],

              'vTax' => 0, 

              'vServAmt' => $servAmt,

              //              'vDisAmt' => $DiscAmt[$key],

              //              'vComm' => $Comm[$key],

              //              'vCommAmt' => $CommAmt[$key],

              'vSlsAmt' => $hotel_item['SellingFareTotal'], 

              'vCollected' => $hotel_item['SellingFareTotal'],

              'vAllocation' => $hotel_item['SellingFareTotal'],

              //              'vAgentAmt' => $AgentAmt[$key],

              //              'vDLPO' => $lpo[$key],

              'vStatus' => 'Closed');

          $this->db->insert('{PRE}SalesClosingDetails', $data);
echo "<br>SalesClosingDetails Inserted<br>"; echo('<pre>');  print_r($data); echo('</pre>');
				
            $collected=$hotel_item['SellingFareTotal'];
		  $pass_name=$hotel_item['PassengerName'];
		  $customerA=$hotel_item['AgencyId'];
 $this->jvDetailInsert($Salesid,$newId,$getLocation,$pass_name,$totalDC,'Htl',$id,$customerA);
//$this->jvAgentInsert($Salesid,$newId,$getLocation,$pass_name,$totalDC,'Htl',$id,$customerA);
      




        $this->jvSalesInsert($customerA,$Salesid,$newId,$getLocation,$pass_name,$collected,'','');

		$closedAcc =$customerAc; //$this->common_model->get_field_name('AccountHeadAccounts', 'nId', " WHERE vACode = '". $value ."'");

        $closedAccHead = 137;

        $vTable = 'C';



        $data     = array('nSalesCloseId' => $Salesid,

            'nAGId' => 36,

            'nAccHeadId' => $closedAccHead,

            'vAcCode' => 0,

            'vType' => 'Htl',

            'vSalesTotalAmt' => $totalDC,

            'vTicketNumber' => $id,

            'vDebit' => $hotel_item['SellingFareTotal'],

            'vCredit' => '0');

        $this->db->insert('{PRE}SalesClosedAccounts', $data);

echo "<br>SalesClosedAccounts Inserted<br>"; echo('<pre>');  print_r($data); echo('</pre>');
				 


          //call jv total

          $this->jvTotalInsert($Salesid,$newId,$getLocation,$pass_name,$collected,'Htl',$closedAcc."-C",$closedAccHead);

        $data = array('nSalesCloseId' => $Salesid,

            'nDrAccount' => $customerAc,

            //              'nCard' => $acCard[$key2],

            //              'vCardComm' => $CardComm[$key2],

            //              'vCommAmt' => $CardCommAmt[$key2],

            'vAmt' => $collected, 

            //              'vCRV' => $CRV[$key2],

            //              'vRefTkt' => $RefTkt[$key2],

            'nAcHead' => $closedAccHead,

            'vTable' => 'C');

        $this->db->insert('{PRE}SalesAccountDetails', $data);
		echo "<br>SalesAccountDetails Inserted<br>"; echo('<pre>');  print_r($data); echo('</pre>');
				
		//Sales Closing End
		
		
		$admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "Booking Inserted : ".$hotel_item['VoucherNumber'];
			$comment = "Current Date:".date('Y-m-d');
//mail($admin_email, "$subject", $comment, "From:" . $email);
		}
		else{
			echo "Duplicate Entry:".$hotel_item['VoucherNumber'];
			echo "<br>";
			$admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "Duplicate :".$hotel_item['VoucherNumber'];
			$comment = "Current Date:".date('Y-m-d');
//mail($admin_email, "$subject", $comment, "From:" . $email);
		}
	}//end function





	function getAutomaticDocNumber()
	{
		$this->txtMinDigit              = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='7'");
		$fetchCode              = $this->db->query("SELECT vDocNumber FROM {PRE}HotelVoucher ORDER BY nHVID DESC LIMIT 0,1");
		if($fetchCode->num_rows() > 0):
			$totalNum           = $fetchCode->row();
		$this->txtDocNumber     = $totalNum->vDocNumber;
		$docNumber                      = @split('-',$this->txtDocNumber);

#how many chars will be in the string
		$fill                           = $this->txtMinDigit;
#the number
		$number                         = ($docNumber[3]+1);
#with str_pad function the zeros will be added
		$lDigit                         = str_pad($number, $fill, '0', STR_PAD_LEFT);
		// The result: 000056

		$countVal                       = $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
else:
		$this->txtDocNumber     = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='7'");
		$countVal                       = $this->txtDocNumber;
		endif;

		return($countVal);
	}//end function




	function voidHotel($hotel_item){

		// hotel must be booked online
		$issuedId = $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = 'JH'");
		$userId =  $this->common_model->get_field_name('Admin','nId'," WHERE nEmpId= '1'");
		$hotelId = $this->common_model->get_field_name('HotelVoucher','nHVId', " WHERE nUserId = " . $userId . " 
					AND vTicktManual = '" .  $hotel_item['VoucherNumber'] ."'");
		if($hotelId != ''){
			// Update hotel voucher
			$data   = array(
					'vType' => 'V', 
					'vTotalRTFC' => '0',
					'vTotalFC' => '0', 
					'vTotalRTDC' => '0',
					'vTotalDC' => '0',
					'vAgentConfId' => '0',
					'vHotelConfId' => '0',
				       );

			echo "Insert HotelVoucher <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->where('nHVId', $hotelId);
			$this->db->update('{PRE}HotelVoucher', $data); 


			$data 	= array(
					'nRTNhtFc' =>  '0', 
					'vTotalInFc' =>  '0',
					'nRTNhtDc' =>  '0',
					'vTotalInDC' =>  '0',
					'vExchRate' =>  '0',
				       );

			echo "Insert HotelDetails <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->where('nHotelId',$hotelId);
			$this->db->update('{PRE}HotelDetails', $data);

			$data 	= array(
					'vSalesAmt' => '0', 
					'vServicePercent' =>  '0',
					'vServiceAmt' => '0', 
				       );

			echo "Insert HotelBillingDetails <br>"; echo('<pre>');  print_r($data); echo('</pre>');
			$this->db->where('nHotelId',$hotelId);
			$this->db->update('{PRE}HotelBillingDetails', $data);
		}//end if
	} //end function




	function refundHotel($hotel_item) 
	{
		//get hotel id
		$issuedId = $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = 'JH'");
		$userId =  $this->common_model->get_field_name('Admin','nId'," WHERE nEmpId= '1'");
		$hotelId = $this->common_model->get_field_name('HotelVoucher','nHVId', " WHERE nUserId = " . $userId . " 
				AND vTicktManual = '" .  $hotel_item['VoucherNumber'] ."'");

		// First retrieve from sales closing
		$query_s = "SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,hv.*,
				sc.dDate as date
				FROM {PRE}SalesClosing sc LEFT JOIN
				{PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId 
				LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
				LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId = scd.vTktNumber
				WHERE scd.vType = 'Htl' AND scd.vTktNumber = '" .$hotelId ."'
				GROUP BY sc.nSCId";
		echo $query_s;
		$salesClosingValues = $this->db->query($query_s);

		if ($salesClosingValues->num_rows() > 0)
		{
			foreach ($salesClosingValues->result() as $key=>$row)
			{

				$row->vTable;


				//insert into refund table
				$data = array('nDivision' => $row->nDivision,
						'nLocation' => $row->vLocation,
						'dDate' =>  date('d-m-Y'),
						//	'nTransaction' =>
						'vDocNumber' => $this->generateDocNo(),
						'nGDS' => '0',
						'vType' => 'R',
						'vPassenger' =>  $row->vPassenger, 
						//	'vSector' =>
						'nAccount' => $row->nAcCode,
						'nUserId' => $userId,
						//	'vSegment' =>
						//	'vDestination' =>
						'vAcAPID' =>  $row->nDrAccount ."-". $row->vTable,
						'nCurrency' => $row->nCurrency,
						'nRate' => $row->nRate,
						'vSalesDet' => $row->date,
						'vRefundDet' => date('d-m-Y'),
						'vMode' => 'Cr',
						'vActive' => '1'
					     );
				echo "Insert RefundRegister <br>"; echo('<pre>');  print_r($data); echo('</pre>');
				$this->db->insert('{PRE}RefundRegister',  $data); 

				$id = $this->db->insert_id();

				//insert into refund billing table
				$data1 = array('nRefundId' => $id,
						//	'vInvoice' =>
						'vMode' => 'Cr',
						'nAcCode' => $row->nDrAccount ."-". $row->vTable,
						'vAcSubCode' => $row->nAcHead
						//	'nAgentAc' =>
						//	'vLPO' =>
					      );
				echo "Insert RefundRegisterBilling<br>"; echo('<pre>');  print_r($data); echo('</pre>');

				$this->db->insert('{PRE}RefundRegisterBilling',  $data1);
				//insert into RefundRegister Details table
				$data3 = array('nRefundId' => $id,
						'vTktType' => 'Htl', 
						'vNewOld' => 'N',
						'nTktNum' => $hotelId,
						//				'vOrgTktNum' =>
						//				'vAirline' =>
						'vPassenger' =>  $row->vPassenger,
						//				'vRemarks' =>
						'vFareAmt' => $row->vFare,
						//				'vUsedFare' =>
						'vSalesAmt' => $row->vCollected,
						'vServiceAmt' => $row->vServAmt,
						'nCountry' => $row->nCountry,
						//				'vTax' =>
						//				'vUsedTax' =>
						//				'vOurCharge' =>
						'vDiscountAmt' =>  $row->vDisAmt,
						'nCity' => $row->nCity,
						//				'vComm1' =>
						//				'vComm2' =>
						//				'vOthA1' =>
						//				'vOthA2' =>
						'nHotel' => $row->vHotelName,
						//				'vOtherAmt' =>
						//				'vVendorAmt' =>
						//				'vOthA3' =>
						'vAgentAmt' =>  $row->vAgentAmt,
						'vIssuedBy' => $issuedId,
						'vNetAmt1' => $row->vNetAmt,
						'vNetAmt2' => $row->vNetAmt,
						'vPaidTo' => $row->vCollected
							);
				echo "Insert RefundRegisterDetails<br>"; echo('<pre>');  print_r($data); echo('</pre>');

				$this->db->insert('{PRE}RefundRegisterDetails',  $data3);
			}//end foreach
		}//end if
	}//end function




    //get document number when selected transaction
    function generateDocNo()
    {
	    $ddlType = 'R';
	    $type = 3;

        $txtLocation      = $this->common_model->get_field_name('Location','vCode'," ");
        $txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister WHERE
                                                    vType = '".$ddlType."' ORDER BY nRId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$txtDocNumber);
            $fill   			= $txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
            $docNumber 			= @split('-',$txtDocNumber);
            $countVal 			= $docNumber[0].'-'.$txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        return $countVal;
    }//end function

	function getAutomaticInvNo($type,$loc)

    {

        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");

        $fetchCode            = $this->db->query("SELECT vInvoiceNumber FROM {PRE}SalesClosing WHERE vTransMode = '".$type."'

                                                    AND vLocation = '".$loc."' ORDER BY CAST(SUBSTRING_INDEX(vInvoiceNumber,'-',-1) AS UNSIGNED)  DESC LIMIT 0,1");

        if($fetchCode->num_rows() > 0):

            $totalNum           = $fetchCode->row();

            $this->txtDocNumber = $totalNum->vInvoiceNumber;

            $docNumber      = @split('-',$this->txtDocNumber);

            $fill         = $this->txtMinDigit;

            $number       = ($docNumber[3]+1);

            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;

        else:

            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");

            $docNumber      = @split('-',$this->txtDocNumber);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];

        endif;



        return($countVal);

    }//end function
	function jvInsert($id,$division,$location,$invoice,$dateVal)

    {

        //insert into system jv

        $systmJvNo = $invoice;

        $systmJvNo = str_replace('-INV-','-',$systmJvNo);

        $systmJvNo = 'SJV'.$systmJvNo;



        $data      = array('vJVNo' => $systmJvNo,'nSalesCId' => $id,

            'nDivision' => $division,

            'nLocation' => $location,

            'nInvoice' => $invoice,

            'dDate' => date('Y-m-d', strtotime($dateVal)));

        $this->db->insert('{PRE}SystemJv', $data);

echo print_r($data) . "<br><br>";

        $newId = $this->db->insert_id();



        return ($newId);

    }//end funciton
	 function jvDetailInsert($id,$newId,$location,$user,$costOfSalesAmt,$type,$tNumber,$customerA)

    {

        //insert into system jv details for tickets

        //addes sales details

        if($type == 'Htl'):

            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,

                'nLocation' => $location,
				'nAGId' => 28,'nAccHeadId' => 83,'vAcCode' =>32311,'vType' => 'Htl',
               // 'nAGId' => 36,'nAccHeadId' => 137,'vAcCode' =>$customerA,'vType' => 'Htl',

                'vDes' => $user,

                'nDebit' => $costOfSalesAmt,

                'nCredit' => 0);

            $this->db->insert('{PRE}SystemJvDetails', $data);

echo print_r($data) . "<br><br>";
$data      = array('nSjId' => $newId, 'vNumber' => $tNumber,

                'nLocation' => $location,
				'nAGId' => 18,'nAccHeadId' => 34,'vAcCode' =>32311,'vType' => 'Htl',
               // 'nAGId' => 36,'nAccHeadId' => 137,'vAcCode' =>$customerA,'vType' => 'Htl',

                'vDes' => $user,

                'nDebit' => 0,

                'nCredit' => $costOfSalesAmt);

            $this->db->insert('{PRE}SystemJvDetails', $data);

echo print_r($data) . "<br><br>";

	endif;



    }//end function



    //function for create jv

    function jvSalesInsert($customerA,$id,$newId,$location,$user,$CollectedTkt,$CollectedHtl,$CollectedXo)

    {

      //insert into system jv details for tickets

      //addes sales details

        $data      = array('nSjId' => $newId,

            'nLocation' => $location,
			
	'nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => 32310,'vType' => 'Htl',
            //'nAGId' => 36,'nAccHeadId' => 137,'vAcCode' => $customerA,'vType' => 'Htl',

            'vDes' => $user,

            'nDebit' => 0,

            'nCredit' => $CollectedTkt);

      $this->db->insert('{PRE}SystemJvDetails', $data);

      echo print_r($data) . "<br><br>";



    } //end function





    //function for create jv

    function jvTotalInsert($id,$newId,$location,$user,$totalAmnt,$tktType,$acId,$acHead)

    {

        $drAccount = @split("-",$acId);

        $CollectedTkt = $totalAmnt;



        //insert into system jv details for tickets

        //added into closed account detials

      //update system jv

      $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',

        " WHERE nId = '".$acHead."'");

      $AgId     = (intval($AgId) == 0)?0:$AgId;

      $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',

        " WHERE nId = '".$acHead."'");

      $acCode   = (intval($acCode) == 0)?0:$acCode;



      $cndtin    = "WHERE nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'";

      //$getSalId  = $this->common_model->get_field_name('SystemJvDetails',"nSjId",$cndtin);



      $sqlChk    = $this->db->query("SELECT nAGId FROM {PRE}SystemJvDetails WHERE

                        nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'

                      AND nSjId = '".$newId."' AND nDrAccount = '".$drAccount[0]."'

                        AND vTable = '".$drAccount[1]."'");

      if($sqlChk->num_rows() == 0):

        $data     = array('nSjId' => $newId,

          'nLocation' => $location,

          'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,

          'vType' => 'Htl','vDes' => $user,'vClosedId' => $acId,

                    'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],

          'nDebit' => $CollectedTkt,

          'nCredit' => 0);

        $this->db->insert('{PRE}SystemJvDetails', $data);

echo print_r($data) . "<br><br>";



      endif;

    }//end function
	
	


}//end class
?>
