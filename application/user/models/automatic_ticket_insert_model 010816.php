<?php

// include("./static/gds/ticket_result.php");

foreach (glob("./static/gds/tickets/*.php") as $filename)

{

  include($filename);

}

class Automatic_ticket_insert_model extends CI_Model

{



    function __construct()

    {

        // Call the Model constructor

        parent::__construct();
		date_default_timezone_set('Asia/Kuwait');

    }



// function not used anymore

	function getResponse()

	{





		$responseArray = array();

		$responseArray = getGdsTickets();

        

    	return ($responseArray);

	}//end funciton



    function insertTickets()

    {



      foreach (glob("./static/gds/tickets/*.php") as $filename)

      {

        $file = explode("/",$filename);

        $file_date = explode(".",end($file));

        $func_name = "getGdsTickets" . $file_date[0];





        $responseArray = array();

        

        $responseArray = call_user_func($func_name);



        if($responseArray){

          $ticketArray= json_decode($responseArray,   true);

        }



        $cnt = 0;

        foreach ($ticketArray as $ticket_key => $ticket_item) {



          //if ticket is already in the db then do not insert

          $ticket_no = substr($ticket_item['tkt_no'],3);

          $issued_date = date('Y-m-d', strtotime($ticket_item['dateofissue']));

          // check if ticket is present

          $ticketId    =  $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no. "'" ); //"' AND dDate = '".$issued_date."'");
				if($ticket_item['pcc']== "5UA"){
						$nAcc = '1';
                        $nAccHead = '52';
						$iss = 'ACT';//issuer
						$loc = '01';//Employee Location
						$nu = '33';//issuer id from database
						//$getLocation='01';//ticket location			
				}
				if($ticket_item['pcc']== "6QC7"){
			
                        $nAcc = '212';
                        $nAccHead = '52';
						$iss = 'RFO';//issuer
						$loc = '02';//Location
						$nu = '34';//issuer id from database
						//$getLocation='03';//ticket location
			
				}
				if($ticket_item['pcc']== "32AK"){
						$nAcc = '213';
						$nAccHead = '52';
						$iss = 'RCT';//issuer
						$loc = '03';//Location
						$nu = '35';//issuer id from database
						//$getLocation='02';//ticket location
				}
		


          if($ticket_item['tkt_type'] != "Refund" ){

            if($ticketId != '' && $ticket_item['tkt_type'] != "Void"){

              //echo $ticket_no. " is already present in the system...                ";

            }

            else if($ticketId != '' && $ticket_item['tkt_type'] == "Void"){

              $db_tkt_status =  $this->common_model->get_field_name('TicketBooking','vType'," WHERE nTId = '".$ticketId. "'" );

              if($db_tkt_status != 'V'){



                $status = 'V'; 

                $closedId    =  $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vTktNumber= '".$ticketId. "' AND vType='Tkt'" );

                if($closedId != '') { echo "Ticket#" . $ticket_no . " is already closed. Changing status to VOID...    ";}

                // set status of ticket as void

                $data   = array( 'vType' => $status);

                $this->db->where('nTId',$ticketId);

                $this->db->update('{PRE}TicketBooking', $data); 



                // set fare as 0

                $tax1 = $tax2 = $tax3 = $totalTax = 0;

                $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;



                $pass_contactno = '';

                //insert into Ticket Fare Details table

                $data2 	= array('vTax1' => $tax1,

                    'vTax2' => $tax2,

                    'vTax3' => $tax3,

                    'vTaxTotal' => $totalTax,

                    'nNetVendor' => $NetAmount,

                    'nAcc' => $nAcc,

                    'nAccHead' => $nAccHead,

                    'vFCFare' => $fc_fair,

                    'vPassengerContact' => $pass_contactno,

                    'vFaceValue' => $base_fair);

                $this->db->where('nTicketId',$ticketId);

                $this->db->update('{PRE}TicketFareDetails', $data2);



                $cnt++;

              }

            } 

            else {

              //retrieve data for ticket booking

            if( $ticket_item['tkt_type'] == 'Conjunction'){ $status = 'C'; }

            else if( $ticket_item['tkt_type'] == 'Reissued'){ $status = 'R'; } 

            else if( $ticket_item['tkt_type'] == "Void" ){ $status = 'V'; }

            else { $status = 'N'; }

            $inType = $status;



// ----- sign on id -----

            if($ticket_item['signon_id'] == '' || $ticket_item['signon_id'] == '-'){

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// hard coded for alfurqan /////////////

             // $issuer = 0;
			$issuer = $iss;
            }

            else{

              $issuer = substr($ticket_item['signon_id'],-2);

            }

            $issuedCode =  $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vGallileoCD = '".$issuer. "'");



             if($issuedCode ==''){
              //$SignId ='';
              $getDivison = $this->common_model->get_field_name('Division','nId'," ORDER BY nId "); 
              $getLocation = $loc;  
			 // $this->common_model->get_field_name('Location','nId'," WHERE vActive = '1' AND nDivision = " .$getDivison. " ORDER BY nId ");
              if($issuedCode == '') {
                $issued_data = array('vCode' => $issuer,
				'vAmadeusCD'=> $issuer,
                    'vLocations' => $getLocation,
                    'vLocation' => $getLocation );
                //$this->db->insert('{PRE}EmployeeMaster', $issued_data);
                //$SignId = 
				//$this->db->insert_id();
				$SignId = $nu;
              }
            }
            else {
              $SignId = $issuedCode;
              $getLocation=  $this->common_model->get_field_name('EmployeeMaster','vLocation'," WHERE vGallileoCD = '".$issuer. "'");
              $getDivison=  $this->common_model->get_field_name('Location','nDivision'," WHERE nId= " .$getLocation. " ");
            }


// ---- booked if -----

            $BookedId = substr($ticket_item['booked_id'],-2);
            if($BookedId != '' || $BookedId != '-'){
				//$BookedId ='JH';
              $BookedId = $this->common_model->get_field_name('EmployeeMaster','vGallileoCD'," WHERE nEId = '".$SignId. "'");
            }
            $bookedcode = $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vGallileoCD = '".$BookedId. "'");
            if($bookedcode == '' ) { // insert into db
              $div = $this->common_model->get_field_name('Division','nId'," ORDER BY nId ");
              $loc =  $this->common_model->get_field_name('Location','nId'," WHERE vActive = '1' AND nDivision = " .$div. " ORDER BY nId ");
              $booked_data= array('vCode' => $BookedId,
                  'vLocations' => $loc,
                  'vLocation' => $loc );
             // $this->db->insert('{PRE}EmployeeMaster', $booked_data);
             //$bookedcode = $this->db->insert_id();
			   $bookedcode = $nu;
            }





              $getAirline =  $this->common_model->get_field_name('Airline','nArId', " WHERE vAirlineNo = '" . $ticket_item['air_code']. "'");

              if($getAirline == '') {

                  $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),

                      'vTitle' =>''

                      ,'vAirlineNo' => $ticket_item['air_code'],

                      'vAlpha' => $ticket_item['Airline1'],

                      'vActive' => '1');

                  $this->db->insert('{PRE}Airline', $data); 

                  $getAirline= $this->db->insert_id();
		
                } 
			  $pass_name = $ticket_item['pass_name'];

              $gds_PNR = $ticket_item['gds_pnr'];

              $air_PNR = $ticket_item['air_pnr'];







              if(trim($ticket_item['fare']) == "" || trim($ticket_item['fare']) == "-") {

                $currId = 0;

                $companyCurrcode =0;

                $rate = 0;

                $fc_fair= 0;

                $base_fair = 0;

              }

              else {

                $ticket_item['fare'] = trim($ticket_item['fare']);

                $tkt_item_fare_temp = explode(" ",$ticket_item['fare']);

                $new_tkt_item_fare = "";

                foreach($tkt_item_fare_temp as $key => $value){

                  if($value != null){

                    $new_tkt_item_fare .= $value . " ";

                  }}

                $new_tkt_item_fare = trim($new_tkt_item_fare);

                $tkt_item_fare = explode(" ",$new_tkt_item_fare);

                $currId =   $this->common_model->get_field_name('Currency','nId'," WHERE vCurrcode = '" . $tkt_item_fare[1] . "'");

                $companyCurrcode = $this->common_model->getCompanyCurr();

                if($currId  == $companyCurrcode){ 

                  $rate = 1;

                  $fc_fair= $tkt_item_fare[0];

                  $base_fair= $tkt_item_fare[0];

                }

                else {

                  $rate = 0;

                  $fc_fair = $tkt_item_fare[0];

                  $base_fair = $tkt_item_fare[2];



                  /*

                     $rate = end($tkt_item_fare);

                     $parse_rate =  explode("&",$rate); 

                     $rate = $parse_rate[0];



                     $new_tkt_item_fare = "";

                     foreach($tkt_item_fare as $key => $value){

                     if($value != null){

                     $new_tkt_item_fare .= $value . " ";

                     }}

                     $new_tkt_item_fare = trim($new_tkt_item_fare);

                     $tkt_item_fare2 = explode(" ",$new_tkt_item_fare);

                     $fc_fair_array = explode("&",$tkt_item_fare2[2]);

                     $fc_fair = $fc_fair_array[0];

                     $base_fair = $tkt_item_fare2[4];

                   */

                }



                /////// Insert currency if not present 

                if($currId == '') {

                  $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),

                      'vTitle' =>''

                      ,'vCurrcode' => $tkt_item_fare[1],

                      'vRate' => $rate,

                      'vActive' => '1', 

                      'nPosition' => $this->common_model->maxPos('Currency'));

                  $this->db->insert('{PRE}Currency', $data); 

                  $currId= $this->db->insert_id();

                }



              }



              $ticket_item['DepTime0'] = preg_replace('/[^0-9\-]/', '', $ticket_item['DepTime0']);

              $ticket_item['DepTime1'] = preg_replace('/[^0-9\-]/', '', $ticket_item['DepTime1']);

              $ticket_item['DepTime2'] = preg_replace('/[^0-9\-]/', '', $ticket_item['DepTime2']);

              $ticket_item['DepTime3'] = preg_replace('/[^0-9\-]/', '', $ticket_item['DepTime3']);



              $ticket_item['ArrTime0'] = preg_replace('/[^0-9\-]/', '', $ticket_item['ArrTime0']);

              $ticket_item['ArrTime1'] = preg_replace('/[^0-9\-]/', '', $ticket_item['ArrTime1']);

              $ticket_item['ArrTime2'] = preg_replace('/[^0-9\-]/', '', $ticket_item['ArrTime2']);

              $ticket_item['ArrTime3'] = preg_replace('/[^0-9\-]/', '', $ticket_item['ArrTime3']);



	      $segments = 0;

 

             // ticket details data

              $Carrier1 = $ticket_item['Airline0'];

              $Flight1 = $ticket_item['FlightNumber0'];

              $Class1 = $ticket_item['Class0'];

              $TravelDate1 = $ticket_item['DepDate0']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate0'])):0;

              $Sector11 = $ticket_item['Cityorigin0'];

              $Sector12 = $ticket_item['CityDest0'];

              $status1 = $ticket_item['status0'];

              $time_of_dep1 = ($ticket_item['DepTime0']!='' || $ticket_item['DepTime0']!='-') ? substr($ticket_item['DepTime0'],0,2). ":" . substr($ticket_item['DepTime0'],2):'';

              $time_of_arraive1 = ( $ticket_item['ArrTime0']!='' ||  $ticket_item['ArrTime0']!='-' ) ?  substr($ticket_item['ArrTime0'],0,2). ":" . substr($ticket_item['ArrTime0'],2):'';

              $day1 =  $ticket_item['DepDate0']!='' ? date('D',strtotime($TravelDate1)):0;

	      if($Carrier1 != '') $segments++;



              $Carrier2 = $ticket_item['Airline1'];

              $Flight2 = $ticket_item['FlightNumber1'];

              $Class2 = $ticket_item['Class1'];

              $TravelDate2 = $ticket_item['DepDate1']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate1'])):0;

              $Sector21 = $ticket_item['Cityorigin1'];

              $Sector22 = $ticket_item['CityDest1'];

              $status2 = $ticket_item['status1'];

              $time_of_dep2 = ($ticket_item['DepTime1']!='' ||$ticket_item['DepTime1']!='-' ) ? substr($ticket_item['DepTime1'],0,2). ":" . substr($ticket_item['DepTime1'],2):'';

              $time_of_arraive2 =  ($ticket_item['ArrTime1']!=''|| $ticket_item['ArrTime1']!='-' ) ?  substr($ticket_item['ArrTime1'],0,2). ":" . substr($ticket_item['ArrTime1'],2):'';

              $day2 =  $ticket_item['DepDate1']!='' ? date('D',strtotime($TravelDate2)):0;

	      if($Carrier2 != '') $segments++;



              $Carrier3 = $ticket_item['Airline2'];

              $Flight3 = $ticket_item['FlightNumber2'];

              $Class3 = $ticket_item['Class2'];

              $TravelDate3 = ($ticket_item['DepDate2']!=''||  $ticket_item['DepDate2']!='-' ) ? date('Y-m-d', strtotime($ticket_item['DepDate2'])):0;

              $Sector31 = $ticket_item['Cityorigin2'];

              $Sector32 = $ticket_item['CityDest2'];

              $status3 = $ticket_item['status2'];

              $time_of_dep3 = ($ticket_item['DepTime2']!=''|| $ticket_item['DepTime2']!='-' ) ? substr($ticket_item['DepTime2'],0,2). ":" . substr($ticket_item['DepTime2'],2):'';

              $time_of_arraive3 =  ($ticket_item['ArrTime2']!=''||$ticket_item['ArrTime2']!='-' ) ?  substr($ticket_item['ArrTime2'],0,2). ":" . substr($ticket_item['ArrTime2'],2):'';

              $day3 =  $ticket_item['DepDate2']!='' ? date('D',strtotime($TravelDate3)):0;

	      if($Carrier3 != '') $segments++;



              $Carrier4 = $ticket_item['Airline3'];

              $Flight4 = $ticket_item['FlightNumber3'];

              $Class4 = $ticket_item['Class3'];

              $TravelDate4 = $ticket_item['DepDate3']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate3'])):0;

              $Sector41 = $ticket_item['Cityorigin3'];

              $Sector42 = $ticket_item['CityDest3'];

              $status4 = $ticket_item['status3'];

              $time_of_dep4 = ($ticket_item['DepTime3']!=''||$ticket_item['DepTime3']!='-' ) ? substr($ticket_item['DepTime3'],0,2). ":" . substr($ticket_item['DepTime3'],2):'';

              $time_of_arraive4 = ( $ticket_item['ArrTime3']!=''||$ticket_item['ArrTime3']!='-' )  ?  substr($ticket_item['ArrTime3'],0,2). ":" . substr($ticket_item['ArrTime3'],2):'';

              $day4 =  $ticket_item['DepDate3']!='' ? date('D',strtotime($TravelDate4)):0;

	      if($Carrier4 != '') $segments++;



              // ticket fare details

              $tax1 = ($ticket_item['Taxes1'] == '' || $ticket_item['Taxes1'] == '-' ) ? 0 :  $ticket_item['Taxes1'] ;

              $tax2 = ($ticket_item['Taxes2']  == ''|| $ticket_item['Taxes2']  == '-' ) ? 0 :  $ticket_item['Taxes2'];

              $tax3 = ($ticket_item['Taxes345']  == '' || $ticket_item['Taxes345']  == '-') ? 0 :  $ticket_item['Taxes345'];

              $totalTax = $tax1 + $tax2 + $tax3; 

              //$NetAmount = ($ticket_item['NetAmount']  == ''|| $ticket_item['NetAmount']  == '-' ) ? 0 :  $ticket_item['NetAmount']; 

              $NetAmount = $totalTax + $base_fair;



              // fare calculations above

              $pass_contactno = '';



              /*

              // not used fields

              $ticket_item['iata_code'];

              $ticket_item['slno']

              $ticket_item['cp_charges'];

              $ticket_item['fare_basis1']

              $ticket_item['NVB1']

              $ticket_item['NVA1']

              $ticket_item['bk1']

              $ticket_item['slno']

               */

              //insert into ticket booking master table
				if($inType=='R'){ 
				$org_no = substr($ticket_no,17,10);
			  $ticket_no=substr($ticket_no,0,10);
			   
			  }
			  else{
				   $org_no = ''; 
			  }
              $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),

                  'nDivision' => $getDivison,

                  'nLocation' => $getLocation,

                  'dDate' => $issued_date,

                  'nAirline' => $getAirline,

                  'nGDS' => '3',

                  'vType' => $inType,

                  'vIssuedBy' => $SignId,

                  'vBookedBy' => $bookedcode,

                  'vPassenger' => $pass_name,

                  'vGdspnr' => $gds_PNR,'vAirpnr' => $air_PNR,

                  'nCurrency' => $currId,

                  'nRate' => $rate,

                  'vConjStatus' => 'Yes',

                  'vDocNumber' => $this->getAutomaticDocNumber2(),

                  'vTicktManual' => $ticket_no,'vOriginalTkt' => $org_no,'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));

              $this->db->insert('{PRE}TicketBooking', $data);

              $id	    = $this->db->insert_id();

              //$id = 1;

              //          echo('<pre>');  print_r($data); echo('</pre>');



              //insert into Ticket Details table

              $data 	= array('nTicketId' => $id ,'vCarrier1' => $Carrier1,

                  'vFlight1' => $Flight1,

                  'vClass1' => $Class1,

                  'dTravelDate1' => $TravelDate1,

                  'vSector1' => $Sector11,

                  'vSector12' => $Sector12,

                  'vStatus1' => $status1,

                  'vTimeDept1' => $time_of_dep1,

                  'vTimeArr1' => $time_of_arraive1,

                  'vDay1' => $day1,

                  'vCarrier2' => $Carrier2,

                  'vFlight2' => $Flight2,

                  'vClass2' => $Class2,

                  'dTravelDate2' => $TravelDate2,

                  'vSector2' => $Sector21,

                  'vSector22' => $Sector22,

                  'vStatus2' => $status2,

                  'vTimeDept2' => $time_of_dep2,

                  'vTimeArr2' => $time_of_arraive2,

                  'vDay2' => $day2,

                  'vCarrier3' => $Carrier3,

                  'vFlight3' => $Flight3,

                  'vClass3' => $Class3,

                  'dTravelDate3' => $TravelDate3,

                  'vSector3' => $Sector31,

                  'vSector33' => $Sector32,

                  'vStatus3' => $status3,

                  'vTimeDept3' => $time_of_dep3,

                  'vTimeArr3' => $time_of_arraive3,

                  'vDay3' => $day3,

                  'vCarrier4' => $Carrier4,

                  'vFlight4' => $Flight4,

                  'vClass4' => $Class4,

                  'dTravelDate4' => $TravelDate4,

                  'vSector4' => $Sector41,

                  'vSector44' => $Sector42,

                  'vStatus4' => $status4,

                  'vTimeDept4' => $time_of_dep4,

                  'vTimeArr4' => $time_of_arraive4,

                  'vDay4' => $day4,

		  'vSegment' => $segments );

              $this->db->insert('{PRE}TicketDetails', $data);

              //         echo('<pre>');  print_r($data); echo('</pre>');



              if($status  == 'V'){

                     $tax1 = $tax2 = $tax3 = $totalTax = 0;

                     $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;

               }

					if($status  == 'C'){
				   
				   $tax11 =$tax1; 
				   $tax21 = $tax2;
				   $tax31 = $tax3;
				   $totalTax1 = $totalTax;
				   $NetAmount1 =$NetAmount;
				   $fc_fair1=$fc_fair;
					$pass_contactno1 =$pass_contactno;
					$base_fair1 =$base_fair;
					
                     $tax1 = $tax2 = $tax3 = $totalTax = 0;

                     $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;

               }

              if($status == 'R') {

                     $tax1 = $tax2 = $tax3 = $totalTax = 0;

                     $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;

                     $total_fare_reissued = explode(" ",$ticket_item['total_fare']);

                     $base_fair  = substr($total_fare_reissued[0],0,-1);

                     $NetAmount = $base_fair;

               }



              //insert into Ticket Fare Details table

              $data 	= array('nTicketId' => $id ,'vFare' => 'N',

                  'vTax1' => $tax1,

                  'vTax2' => $tax2,

                  'vTax3' => $tax3,

                  'vTaxTotal' => $totalTax,

                  'nNetVendor' => $NetAmount,

//////// change this account for ticket

                  'nAcc' => $nAcc,

                  'nAccHead' => $nAccHead,

                  'vFCFare' => $fc_fair,

                  'vPassengerContact' => $pass_contactno,

                  'vFaceValue' => $base_fair);

              $this->db->insert('{PRE}TicketFareDetails', $data);

              //         echo('<pre>');  print_r($data); echo('</pre>');



              //insert into Ticket Billing Details table

              $data 	= array('nTicketId' => $id ,

                  'vSalesAmt' => '0',

                  'vCollected' => '0');

              $this->db->insert('{PRE}TicketBillingDetails', $data);

              $cnt++;



/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////

///////                    SALES CLOSING

/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////



        if((substr($ticket_item['signon_id'],0,4) == "7LA99998") OR (substr($ticket_item['booked_id'],0,4) == "7LA9999998") )

		//if(substr($ticket_item['signon_id'],0,4) == "326P")

		



		if($NetAmount != 0):



        $invNo = $this->getAutomaticInvNo(2,$getLocation);



///////////////// change this oservice amount 

        $servAmt = 0.500; 

        $servPerc = $servAmt / $NetAmount * 100;

        $collected = $NetAmount + $servAmt;



        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $getDivison,'vLocation' =>$getLocation,

            'dDate' => $issued_date,'vTransMode' => 2,

            //            'vLPO' =>,

            'vInvoiceNumber' => $invNo,

            //            'nAgentAc' => ddlAgentAc,

            'vTotalFace' => $base_fair, 

            'vTotalTax' => $totalTax,

            'vTotalCommPercent' => 0,

            'vTotalCommAmt' => 0,

            'vTotalNetAmount' => $NetAmount,

            'vTotalServPercent' => $servPerc, 

            'vTotalServAmount' => $servAmt,

            'vTotalSalesAmt' => $collected, 

            'vTotalDiscAmount' => 0,

            'vTotalCollected' => $collected,

            'vTotalAgentAmt' => 0,

            'vPassName' => $pass_name, 

            //            'vSector1' => txtSector1,

            //            'vSector2' => txtSector2,

            //            'vSector3' => txtSector3,

            //            'vSector4' => txtSector4,

            //            'vSector5' => txtSector5,

            //            'vSegment' => txtSegment,

            //            'vDestination' => txtDestination,

            'vGds' => 1,

            'nInvoiceStatus' => 'N',

            //            'vRemarks' => txtRemarks,

            'vInvoiceTo' => $pass_name, 

            'vActive' => '1');

                $this->db->insert('{PRE}SalesClosing', $data);







        $Salesid          = $this->db->insert_id();

        $newId = $this->jvInsert($Salesid,$getDivison,$getLocation,$invNo,$issued_date);





          $salesProfitTkt = 0; 

          //$CollectedTkt   = 0;

          $costOfSalesTkt = 0;

          $taxTkt     = $totalTax;

          $serviceChrgTkt  =  '';

          $commTkt    = 0;

          $incentiveTkt = '';

          $ourChrgTkt   = '';

          $vendorChrgTkt  = '';

          $discountTkt    = '';

          $voidChrgTkt  = '';



          $airlineCode = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId  = " . $getAirline ." ");






  $emp=$this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId = '".$SignId. "'" );


          $data = array('nSalesCloseId' => $Salesid,

              'vType' => 'Tkt',

              'nAirline' => $airlineCode,

              'vTktNumber' => $id,

              'vNetAmt' => $NetAmount,  

              'vServPercent' => $servPerc,

              'vEmployee' => $emp,

              //              'vFare' => $Fare[$key],

              'vTax' => $totalTax, 

              'vServAmt' => $servAmt,

              //              'vDisAmt' => $DiscAmt[$key],

              //              'vComm' => $Comm[$key],

              //              'vCommAmt' => $CommAmt[$key],

              'vSlsAmt' => $collected, 

              'vCollected' => $collected,

              'vAllocation' => $collected,

              //              'vAgentAmt' => $AgentAmt[$key],

              //              'vDLPO' => $lpo[$key],

              'vStatus' => 'Closed');

          $this->db->insert('{PRE}SalesClosingDetails', $data); 

          //echo print_r($data);



          //jv details

          $this->jvDetailInsert($Salesid,$newId,$getLocation,$pass_name,$collected,'Tkt',$id);





        $this->jvSalesInsert($Salesid,$newId,$getLocation,$pass_name,$collected,'','');







        //for ticket accounts



///////// change the three below for account details

        $closedAcc = 1; //$this->common_model->get_field_name('AccountHeadAccounts', 'nId', " WHERE vACode = '". $value ."'");

        $closedAccHead = 242;

        $vTable = 'C';



        $data     = array('nSalesCloseId' => $Salesid,

            'nAGId' => 0,

            'nAccHeadId' => $closedAccHead,

            'vAcCode' => 0,

            'vType' => 'Tkt',

            'vSalesTotalAmt' => $costOfSalesTkt,

            'vTicketNumber' => $id,

            'vDebit' => 0,

            'vCredit' => '0');

        $this->db->insert('{PRE}SalesClosedAccounts', $data);

//echo print_r($data) . "<br><br>";



          //call jv total

          $this->jvTotalInsert($Salesid,$newId,$getLocation,$pass_name,$collected,'Tkt',$closedAcc."-C",$closedAccHead);

        $data = array('nSalesCloseId' => $Salesid,

            'nDrAccount' => $closedAcc,

            //              'nCard' => $acCard[$key2],

            //              'vCardComm' => $CardComm[$key2],

            //              'vCommAmt' => $CardCommAmt[$key2],

            'vAmt' => $collected, 

            //              'vCRV' => $CRV[$key2],

            //              'vRefTkt' => $RefTkt[$key2],

            'nAcHead' => $closedAccHead,

            'vTable' => $vTable);

        $this->db->insert('{PRE}SalesAccountDetails', $data);



        endif; //if($NetAmount != 0)

        //endif; //if($sign on id ......

	 //endif; //if($sign on id ......

			



/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////


if($ticket_item['tkt_type'] == 'Conjunction'){ 
			$ticket_n=$ticket_no-1;
			$ticketId1=$this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_n."'" ); 
			 //echo $ticket_n."-".$ticketId1." ";
				$data2 	= array('vTax1' => $tax11,
                    'vTax2' => $tax21,
                    'vTax3' => $tax31,
                    'vTaxTotal' => $totalTax1,
                    'nNetVendor' => $NetAmount1,
                    'nAcc' => $nAcc,
                    'nAccHead' => $nAccHead,
                    'vFCFare' => $fc_fair1,
                    'vPassengerContact' => $pass_contactno1,
                    'vFaceValue' => $base_fair1);
                $this->db->where('nTicketId',$ticketId1);
                $this->db->update('{PRE}TicketFareDetails', $data2);
				
			}
            }// end if

          }//end if

          else if($ticket_item['tkt_type'] == "Refund"){

            if( $ticketId == ''){

              echo "Ticket#" . $ticket_no. " is an old ticket and not present in the system. Cannot refund it...     " ;

              continue;

            }

            // check if ticket is already refunded



            $RefundId    =  $this->common_model->get_field_name('RefundRegisterDetails','nRDId'," WHERE nTktNum = ".$ticketId." AND vTktType = 'Tkt' ");

            if($RefundId == '')

            {



              // First retrieve from sales closing

              $query_s = "SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,tb.*,

                sc.dDate as date

                  FROM {PRE}SalesClosing sc LEFT JOIN

                  {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId 

              LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 

                LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = scd.vTktNumber

                WHERE scd.vType = 'Tkt' AND scd.vTktNumber = '" .$ticketId ."'

                GROUP BY sc.nSCId";

              //          echo $query_s;

              $salesClosingValues = $this->db->query($query_s);



              if ($salesClosingValues->num_rows() > 0)

              {

                foreach ($salesClosingValues->result() as $key=>$row)

                {





                  //insert into refund table

                  $data = array('nDivision' => $row->nDivision,

                      'nLocation' => $row->vLocation,

                      'dDate' =>  date('Y-m-d'),

                      'nTransaction' =>0,

                      'vDocNumber' => $this->getDocNum2(),

                      'nGDS' => '3',

                      'vType' => 'R',

                      'vPassenger' =>  $row->vPassenger, 

                      //	'vSector' =>

                      'nAccount' => 1, //$row->nAcCode,

                      'nUserId' => 1,

                      //	'vSegment' =>

                      //	'vDestination' =>

                      'vAcAPID' =>  $row->nDrAccount ."-". $row->vTable,

                      'nCurrency' => $row->nCurrency,

                      'nRate' => $row->nRate,

                      'vSalesDet' => $row->date,

                      'vRefundDet' => date('Y-m-d'),

                      'vMode' => 'Cr',

                      'vActive' => '1'

                      );

                  //echo "Insert RefundRegister <br>"; echo('<pre>');  print_r($data); echo('</pre>');

                  $this->db->insert('{PRE}RefundRegister',  $data); 

                  $id = $this->db->insert_id();



                  $sub_code =( $row->nAcHead == '') ? '':$row->nAcHead;                  

                  //insert into refund billing table

                  $data1 = array('nRefundId' => $id,

                      //	'vInvoice' =>

                      'vMode' => 'Cr',

                      'nAcCode' => $row->nDrAccount ."-". $row->vTable,

                      'vAcSubCode' => $sub_code,

                      'nAgentAc' => 0

                      //	'vLPO' =>

                      );

                  //echo "Insert RefundRegisterBilling<br>"; echo('<pre>');  print_r($data1); echo('</pre>');



                  $this->db->insert('{PRE}RefundRegisterBilling',  $data1);





                  $airline_number =  $this->common_model->get_field_name('Airline','vAirlineNo', " WHERE  nArId= " . $row->nAirline. "");

//currently galelio doesn't send cp charges

//                  $cp_charge = ($ticket_item['cp_charges'] != '' || $ticket_item['cp_charges']  != '-') ? $ticket_item['cp_charges'] : 0; 



                  //insert into RefundRegister Details table

                  $data3 = array('nRefundId' => $id,

                      'vTktType' => 'Tkt', 

                      'vNewOld' => 'N',

                      'nTktNum' => $ticketId,

                      //				'vOrgTktNum' =>

                      'vAirline' => $airline_number, 

                      'vPassenger' =>  $row->vPassenger,

                      //				'vRemarks' =>

                      'vFareAmt' => $row->vFare,

                      //				'vUsedFare' =>

                      'vSalesAmt' => $row->vCollected,

                      'vServiceAmt' => $row->vServAmt,

                      //						'nCountry' =>  

                      'vTax' => $row->vTax,

                      //				'vUsedTax' =>

                      //				'vOurCharge' =>

                      'vDiscountAmt' =>  $row->vDisAmt,

                      //				'nCity' => $row->nCity,

                      //				'vComm1' =>

                      //				'vComm2' =>

                      //				'vOthA1' =>

                      //				'vOthA2' =>

                      //'nHotel' => $row->vHotelName,

                      //				'vOtherAmt' =>

//                      'vVendorAmt' => $cp_charge, 

                      //				'vOthA3' =>

                      'vAgentAmt' =>  $row->vAgentAmt,

                      'vIssuedBy' => $row->vEmployee,

                      'vNetAmt1' => $row->vNetAmt,

                      'vNetAmt2' => $row->vNetAmt,

                      'vPaidTo' => $row->vCollected

                        );

                  //echo "Insert RefundRegisterDetails<br>"; echo('<pre>');  print_r($data3); echo('</pre>');



                  $this->db->insert('{PRE}RefundRegisterDetails',  $data3);

                }//end foreach

              $cnt++;

              }//end if

              else

              {

                echo "Ticket#" . $ticket_no. " is not Closed in the system.... Cannot refund it.                ";

              }

            }//endif

          }// end if



        }//end foreach



      }// end foreach

      if($cnt == 0){ echo " No Records inserted. "; 
	  $admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "No Galileo Records".$cnt;
			$comment = "No fo tickets:".$cnt;
			//mail($admin_email, "$subject", $comment, "From:" . $email);
}
      else { 
	  $admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "Galileo Inserted".$cnt;
			$comment = "No fo tickets:".$cnt;
			mail($admin_email, "$subject", $comment, "From:" . $email);
			echo $cnt.' record is inserted successfully.'; }

      

 // deleting the files

$files = scandir("static/gds/tickets/");

    // Identify directories
    $source = "static/gds/tickets/";
    $destination = "static/gds/backup/";

    // Cycle through all source files
    foreach ( $files as $file ) {
        if (in_array($file, array(".",".."))) continue;

        // If we copied this successfully, mark it for deletion
        if (copy($source.$file, $destination.$file)) {
            //$delete[] = $source.$file;
        }
    }
    // Delete all successfully-copied files
   
foreach (glob("./static/gds/tickets/*.php") as $filename)
{
  
  //copy($filename, "backup/".$filename);
  unlink($filename);
}











    }//end function











    //Find Gds Pnr

    function find_gdspnr($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        $gds_pnr = @explode(';', $substring_array[1]);

        return $gds_pnr[0];

    }//Find Gds Pnr



    //Find Iata code

    function find_iatacode($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        $iata_code = @explode(';', $substring_array[1]);

        return $iata_code[3];

    }//Find Iata code



    //Find AIR PNR

    function find_airpnr($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        $air_pnr = @explode(';', $substring_array[1]);

        $air_pnr = array_filter($air_pnr);

        $air_pnr = array_slice($air_pnr, 0);

        $air_pnr = @explode('-',$air_pnr[10]);

        return $air_pnr[0];

    }//Find AIR PNR



    //Find AIRLINE CODE

    function find_airlinecode1($find,$single_tickets)

    {

        $file = $single_tickets;

        //echo '<pre>',print_r($bookingid,1),'</pre>';

        $startIndex=strrpos($file,'A-');

        if(!empty($startIndex))

        {

            $endIndex=strpos($file,'B-');

            $strLength=$endIndex-$startIndex;

            $finalString=substr($file,$startIndex+2,$strLength-1);

            $substring_array = @explode(' ',$finalString);

            //echo '<pre>',print_r($substring_array,1),'</pre>';

            $last_element = sizeof($substring_array)-1;



            return $substring_array[$last_element];

        }else{return "-";}

    }//Find AIRLINE CODE





    //Find BOOKING ID

    function find_bookingid($find,$single_tickets)

    {

        $file = $single_tickets;

        //echo '<pre>',print_r($bookingid,1),'</pre>';

        $startIndex=strrpos($file,'C-');

        if(!empty($startIndex))

        {

            $endIndex=strpos($file,'D-');

            $strLength=$endIndex-$startIndex;

            $finalString=substr($file,$startIndex+2,$strLength-1);

            $substring_array = @explode(' ',$finalString);

            $substring_array = @explode('-',$substring_array[1]);

            return $substring_array[0];

        }else{return "-";}

    }//Find BOOKING ID



    //Find ISSUED ID

    function find_issuedid($find,$single_tickets)

    {

        $file = $single_tickets;

        $startIndex=strrpos($file,'C-');

        if(!empty($startIndex))

        {

            $endIndex=strpos($file,'D-');

            $strLength=$endIndex-$startIndex;

            $finalString=substr($file,$startIndex+2,$strLength-1);

            $substring_array = @explode(' ',$finalString);

            $substring_array = @explode('-',$substring_array[1]);

            return $substring_array[1];

        }else{return "-";}

    }//Find ISSUED ID



    function find_dateofissue($find,$single_tickets)

    {

        $file = $single_tickets;

        //echo '<pre>',print_r($test,1),'</pre>';

        $startIndex=strpos($file,'D-');

        if(!empty($startIndex))

        {

            $endIndex=strpos($file,'G-');

            $strLength=$endIndex-$startIndex;

            $finalString=substr($file,$startIndex+2,$strLength-1);

            $substring_array = @explode(';',$finalString);



            $numItems = count($substring_array);

            $dateofissue = substr($substring_array[$numItems-1], 0, 6);

            return $dateofissue;



        }else{return "-";}

    }



    function find_fc_fare1($find,$single_tickets)

    {

        $file = $single_tickets;



        $pos = strpos($single_tickets,"VOID");



        if($pos>100)

        {

            if(strpos($single_tickets, 'CONJUCTION') !== false)

            {

                $fc_fare = "-";

                return $fc_fare;

            }

            else

            {

                $substring_array = @explode($find,$single_tickets);

                $substring_array = @explode(';', $substring_array[1]);

                $substring_array = array_filter($substring_array);

                $substring_array = array_slice($substring_array, 0);

                //echo '<pre>',print_r($substring_array,1),'</pre>';

                $fc_fare = $substring_array[0];

                return $fc_fare;

            }

        }

        else

        {



            if ($pos == false)

            {



                if (strpos($single_tickets, 'K-F') !== false)

                {

                    if (strpos($single_tickets, $find) !== false)

                    {

                        $substring_array = @explode($find,$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fc_fare = $substring_array[0];



                        return $fc_fare;





                    }

                }

                else if (strpos($single_tickets, 'K-') !== false)

                {



                    if (strpos($single_tickets, 'K-') !== false)

                    {

                        $substring_array = @explode('K-',$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fc_fare = $substring_array[11];



                        return $fc_fare;





                    }

                }

                else

                {

                    $fc_fare = "-";

                    return $fc_fare;

                }



            }

            else

            {

                $fc_fare = "-";

                return $fc_fare;

            }



        }



    }



    //Find find_tkt_no

    function find_tkt_no($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        $tkt_no = @explode(';', $substring_array[1]);

        $tkt_no = @explode('-', $substring_array[1]);



        //echo '<pre>',print_r($tkt_no,1),'</pre>';



        $reissed_checking = substr($tkt_no[0], 1, 3);



        $reissed_tkt_no = substr($tkt_no[1], 0, 10);

        $reissed = @explode($reissed_tkt_no,$single_tickets);



        //echo $single_tickets;

        if(strpos($reissed[1], 'FO'.$reissed_checking.'-') !== false)

        {

            $reissed_checking1 = @explode('FO'.$reissed_checking.'-', $substring_array[1]);

            $org_tkt_no = substr($reissed_checking1[1], 0, 10);



            //echo '<pre>',print_r($reissed_checking1,1),'</pre>';

           // $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
			$tkt_no = $reissed_tkt_no;
            return $tkt_no;

        }



        if(strpos($reissed[1], 'FH') !== false)

        {

            if(strpos($tkt_no[4], '/') !== false)

            {

                $org_tkt_no = substr($tkt_no[4], 0, 10);

            }else {

                $org_tkt_no = substr($tkt_no[5], 0, 10);

            }



            $tkt_no = $reissed_tkt_no;

        }

        else  // other tha reissued tickets

        {

            $reissed_tkt_no = substr($tkt_no[1], 0, 10);

            $tkt_no = $reissed_tkt_no;

        }



        return $tkt_no;

    }



    //Find find_aireline_code

    function find_aireline_code2($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        //$aireline_code = explode(';', $substring_array[1]);

        $aireline_code = @explode('-', $substring_array[1]);

        //echo '<pre>',print_r($aireline_code,1),'</pre>';

        return $aireline_code[0];

    }//Find find_aireline_code



    //Find Passenger Contact no

    function find_pass_contactno($find,$single_tickets)

    {

        //echo "find";

        $file = $single_tickets;

        $pos = strpos($file,"CTC PAX");



        if ($pos == false) {

            return "-";

        } else {

            $substring_array = @explode($find,$single_tickets);

            $contactno = @explode('-', $substring_array[1]);

            //echo '<pre>',print_r($substring_array,1),'</pre>';

            $contactno = substr($contactno[0], 0, 9);

            return $contactno;

        }

    }//Find Passenger Contact no



    //Find Passenger Name

    function find_pass_name($find,$single_tickets)

    {

        $file = $single_tickets;

        $substring_array = @explode($find,$single_tickets);

        $pass_name = @explode(';', $substring_array[1]);

        //echo '<pre>',print_r($substring_array,1),'</pre>';

        $pass_name = substr($pass_name[1],2);

        return $pass_name;

    }//Find Passenger Name



    //Find Passenger Fare

    function find_fare($find,$single_tickets)

    {



        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");



        if($pos>100)

        {

            if(strpos($single_tickets, 'CONJUCTION') !== false)

            {

                $fare = "-";

                return $fare;

            }

            else

            {

                $substring_array = @explode($find,$single_tickets);

                $substring_array = @explode(';', $substring_array[1]);

                $substring_array = array_filter($substring_array);

                $substring_array = array_slice($substring_array, 0);

                //echo '<pre>',print_r($substring_array,1),'</pre>';

                $fare = $substring_array[1];

                return $fare;

            }

        }

        else

        {



            if ($pos == false)

            {



                if (strpos($single_tickets, 'KSTI') !== false)

                {

                    if (strpos($single_tickets, $find) !== false)

                    {

                        $substring_array = @explode($find,$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fare = $substring_array[0];

                        return $fare;

                    }

                }

                if (strpos($single_tickets, 'KFTF') !== false)

                {

                    if (strpos($single_tickets, $find) !== false)

                    {

                        $substring_array = @explode($find,$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fare = $substring_array[0];

                        return $fare;

                    }

                }

                $substring_array = @explode('T-',$single_tickets);

                $tkt_no = @explode(';', $substring_array[1]);

                $tkt_no = @explode('-', $substring_array[1]);

                //echo '<pre>',print_r($tkt_no,1),'</pre>';

                $reissed_tkt_no = substr($tkt_no[1], 0, 10);

                $reissed = @explode($reissed_tkt_no,$single_tickets);

                if(strpos($reissed[1], 'FH') !== false)

                {

                    $NetAmount = $this->find_NetAmount('K-',$single_tickets);

                    $fare = $NetAmount;

                    return $fare;

                }



                $substring_array = @explode('T-',$single_tickets);

                $tkt_no = @explode(';', $substring_array[1]);

                $tkt_no = @explode('-', $substring_array[1]);

                //echo '<pre>',print_r($tkt_no,1),'</pre>';

                $reissed_checking = substr($tkt_no[0], 1, 3);

                $reissed_tkt_no = substr($tkt_no[1], 0, 10);

                $reissed = @explode($reissed_tkt_no,$single_tickets);

                //echo $single_tickets;

                if(strpos($reissed[1], 'FO'.$reissed_checking.'-') !== false)

                {

                    $NetAmount = $this->find_NetAmount('K-',$single_tickets);

                    $fare = $NetAmount;

                    return $fare;

                }



            }

            else

            {

                $fare = "-";

                return $fare;

            }

        }

    }//Find Passenger Fare



    function find_fc_fare($find,$single_tickets)

    {



        $file = $single_tickets;



        $pos = strpos($single_tickets,"VOID");



        if($pos>100)

        {

            if(strpos($single_tickets, 'CONJUCTION') !== false)

            {

                $fc_fare = "-";

                return $fc_fare;

            }

            else

            {

                $substring_array = @explode($find,$single_tickets);

                $substring_array = @explode(';', $substring_array[1]);

                $substring_array = array_filter($substring_array);

                $substring_array = array_slice($substring_array, 0);

                //echo '<pre>',print_r($substring_array,1),'</pre>';

                $fc_fare = $substring_array[0];

                return $fc_fare;

            }

        }

        else

        {



            if ($pos == false)

            {



                if (strpos($single_tickets, 'K-F') !== false)

                {

                    if (strpos($single_tickets, $find) !== false)

                    {

                        $substring_array = @explode($find,$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fc_fare = $substring_array[0];



                        return $fc_fare;





                    }

                }

                else if (strpos($single_tickets, 'K-') !== false)

                {



                    if (strpos($single_tickets, 'K-') !== false)

                    {

                        $substring_array = @explode('K-',$single_tickets);

                        $substring_array = @explode(';', $substring_array[1]);

                        $substring_array = array_filter($substring_array);

                        $substring_array = array_slice($substring_array, 0);

                        //echo '<pre>',print_r($substring_array,1),'</pre>';

                        $fc_fare = $substring_array[0];



                        return $fc_fare;





                    }

                }

                else

                {

                    $fc_fare = "-";

                    return $fc_fare;

                }



            }

            else

            {

                $fc_fare = "-";

                return $fc_fare;

            }



        }



    }



    //Find Passenger Rate   // Start -  19-Mar-2014 

	function find_Rate($find,$single_tickets)

    {

        $file = $single_tickets;



        $pos = strpos($single_tickets,"VOID");

        if($pos>100)

        {



            $substring_array = @explode($find,$single_tickets);

            $substring_array = @explode(';', $substring_array[1]);

            $substring_array = array_filter($substring_array);

            $substring_array = array_slice($substring_array, 0);

            //echo '<pre>',print_r($substring_array,1),'</pre>';

            if (strpos($substring_array[2], 'KFTF') !== false)

            {

                //echo $rate = "-";

            }

            else

            {

                $rate = $substring_array[3];

            }

            return $rate;

        }

        else

        {

            if ($pos == false)

            {

                if (strpos($single_tickets, 'KFTR') !== false)

                {

                    //echo $rate = "-";

                }

                if (strpos($single_tickets, 'KSTI') !== false)

                {

                    //echo $rate = "-";

                }

                if (strpos($single_tickets, 'RFDA;') !== false)

                {

                    //echo $rate = "-";

                }

                if (strpos($single_tickets, 'KSTY') !== false)

                {

                    //echo $rate = "-";

                }

                if (strpos($single_tickets, 'KFTF') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';



                    if (strpos($substring_array[2], 'KFTF') !== false)

                    {

                        //echo $rate = "-";

                    }

                    else

                    {

                        //echo $rate = $substring_array[3];

                    }

                    return $rate;

                }

            }

            else

            {

                //echo $rate = "-";

            }



        }







    }



    function find_NetAmount($find,$single_tickets)

    {

        $file = $single_tickets;

        //echo '<pre>',print_r($substring_array,1),'</pre>';

        $pos = strpos($single_tickets,"VOID");



        if($pos>100)

        {

            if(strpos($single_tickets, 'CONJUCTION') !== false)

            {

                $NetAmount = "-";

            }

            else

            {

                $substring_array = @explode($find,$single_tickets);

                $substring_array = @explode(';', $substring_array[1]);

                $substring_array = array_filter($substring_array);

                $substring_array = array_slice($substring_array, 0);

                //echo '<pre>',print_r($substring_array,1),'</pre>';

                return $NetAmount = $substring_array[1];

            }

        }

        else

        {



            if ($pos == false)

            {



                if (strpos($single_tickets, 'KSTI') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $NetAmount = $substring_array[1];

                }

                if (strpos($single_tickets, 'KFTF') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $NetAmount = $substring_array[2];

                }

                if (strpos($single_tickets, 'KFTR') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $Amount = preg_replace("/[^0-9]/","",$substring_array[1]);

                    if($Amount!="")

                    {

                        $NetAmount = $substring_array[1];

                    }

                    else

                    {

                        $NetAmount = $substring_array[2];

                    }



                }

                if (strpos($single_tickets, 'KNTY') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $NetAmount = $substring_array[1];

                }

                if (strpos($single_tickets, 'RFDA;') !== false)

                {

                    $substring_array = @explode('RFDA;',$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $NetAmount = $substring_array[7];

                }

                if (strpos($NetAmount, 'KFTF') !== false)

                {

                    $NetAmount = $substring_array[1];

                }

                if(strpos($single_tickets, 'CONJUCTION') !== false)

                {

                    $NetAmount = "-";

                }

                $substring_array = @explode('T-',$single_tickets);

                $tkt_no = @explode(';', $substring_array[1]);

                $tkt_no = @explode('-', $substring_array[1]);

                //echo '<pre>',print_r($tkt_no,1),'</pre>';



                $reissed_tkt_no = substr($tkt_no[1], 0, 10);

                $reissed = @explode($reissed_tkt_no,$single_tickets);

                if(strpos($reissed[1], 'FH') !== false)

                {

                    $substring_array = @explode($find,$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';



                    if (strpos($substring_array[13], 'KSTY') !== false)

                    {

                        $NetAmount = $substring_array[12];

                    }

                    else

                    {

                        $Amount = preg_replace("/[^0-9]/","",$substring_array[1]);

                        if($Amount!="")

                        {

                            $NetAmount = $substring_array[1];

                        }

                        else

                        {

                            $NetAmount = $substring_array[2];

                        }

                    }

                }



            }

            else

            {

                $NetAmount = "-";

            }



        }

        return $NetAmount;



    }



    //Find Segment Details

    function find_SegmentDetail($single_tickets)

    {

        $file = $single_tickets;

        //echo '<pre>',print_r($bookingid,1),'</pre>';

        if (strpos($single_tickets, 'H-') !== false)

        {

            $startIndex=strpos($file,'H-');

            $endIndex=strpos($file,'K-');

            $strLength=$endIndex-$startIndex;

            $finalString=substr($file,$startIndex,$strLength);

            $segments_all = @explode('H-',$finalString);



            for($v=0;$v<sizeof($segments_all);$v++)

            {

                if (strpos($segments_all[$v], 'VOID') == false)

                {

                    $new_segments_all[$v]=$segments_all[$v];

                }

            }

            $new_segments_all = array_filter($new_segments_all);

            $new_segments_all = array_slice($new_segments_all, 0);

            //echo '<pre>',print_r($new_segments_all,1),'</pre>';



            return $new_segments_all;

        }

        else { return "1"; }

    }//Find Segment Details



    //Find Carrier Code

    function find_carrier_code($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);

        //echo '<pre>',print_r($seg_row,1),'</pre>';

        return $seg_row[0];

    }//Find Carrier Code



    //Find Flight No

    function find_flight_no($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);

        //echo '<pre>',print_r($seg_row,1),'</pre>';



        return $seg_row[1];



    }//Find Flight No



    //Find Class

    function find_class($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);



        return $seg_row[2];



    }//Find Class



    //Find Date of travel

    function find_date_of_travel($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);



        $date_of_travel = substr($seg_row[4],0,5);

        return $date_of_travel;



    }//Find Date of travel



    //Find Date of Depa

    function find_date_of_dep($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);

        $date_of_travel = substr($seg_row[4],5,4);

        return $date_of_travel;



    }//Find Date of Depa



    //Find Date of Arraive

    function find_date_of_arraive($seg_row)

    {

        $seg_row = @explode(' ',$seg_row);

        $seg_row = array_filter($seg_row);

        $seg_row = array_slice($seg_row, 0);

        return $seg_row[5];

    }//Find Date of Arraive



    //Find total lenght

    function find_len($single_tickets)

    {

        $file = $single_tickets;

        $total_len = @explode(';',$file);

        //echo '<pre>',print_r($total_len,1),'</pre>';

        return $total_len[0];

    }//Find total lenght



    function find_All_taxes($single_tickets)

    {

        $file = $single_tickets;



        $pos = strpos($single_tickets,"VOID");



        if($pos>100)

        {

            if(strpos($single_tickets, 'CONJUCTION') !== false)

            {

                //echo "-";

            }

            else

            {

                $all_taxes = @explode('KFTF', $single_tickets);

                $all_taxes = @explode(';', $all_taxes[1]);

                //echo '<pre>',print_r($all_taxes,1),'</pre>';

                return $all_taxes;

            }

        }

        else

        {



            if ($pos == false)

            {



                if (strpos($single_tickets, 'KSTI') !== false)

                {

                    $all_taxes = @explode('KSTI', $single_tickets);

                    $all_taxes = @explode(';', $all_taxes[1]);

                    //echo '<pre>',print_r($all_taxes,1),'</pre>';

                    return $all_taxes;

                }

                if (strpos($single_tickets, 'KFTF') !== false)

                {

                    $all_taxes = @explode('KFTF', $single_tickets);

                    $all_taxes = @explode(';', $all_taxes[1]);

                    //echo '<pre>',print_r($all_taxes,1),'</pre>';

                    return $all_taxes;

                }

                if (strpos($single_tickets, 'KNTY') !== false)

                {

                    $all_taxes = @explode('KNTY', $single_tickets);

                    $all_taxes = @explode(';', $all_taxes[1]);

                    //echo '<pre>',print_r($all_taxes,1),'</pre>';

                    return $all_taxes;

                }

                if (strpos($single_tickets, 'RFDA;') !== false)

                {

                    $substring_array = @explode('RFDA;',$single_tickets);

                    $substring_array = @explode(';', $substring_array[1]);

                    $substring_array = array_filter($substring_array);

                    $substring_array = array_slice($substring_array, 0);

                    //echo '<pre>',print_r($substring_array,1),'</pre>';

                    $all_taxes = $substring_array[6];

                    return $all_taxes;

                }



                if(strpos($single_tickets, 'CONJUCTION') !== false)

                {

                    $all_taxes = "-";

                }



            }

            else

            {

                //echo "-";

            }

        }

    }



    function ConvertToFormat($string)

    {



        if(strpos($string,'-') !== false)

        {

            if($string!='-')

            {

                $intValue = @explode("-",$string);

                $intValue = array_filter($intValue);

                $intValue = array_slice($intValue, 0);

                $Amount = $intValue[1]; //preg_replace("/[^.0-9]/","",$intValue[1]);

            }

            else{

                //echo $Amount = '-';

            }



        }

        else if(strpos($string,' ') !== false)

        {

            $intValue = @explode(" ",$string);

            $intValue = array_filter($intValue);

            $intValue = array_slice($intValue, 0);

            $Amount = $intValue[0]; //preg_replace("/[^.0-9]/","",$string);

        }

        else

        {

            $Amount = $string;

        }



        return $Amount;

    }





    function GetAmount($string)

    {



        if(strpos($string,'-') !== false)

        {

            $intValue = @explode("-",$string);

            $Amount = preg_replace("/[^.0-9]/","",$intValue[1]);

        }

        else

        {

            $Amount = preg_replace("/[^.0-9]/","",$string);

        }



        return $Amount;

    }



    function GetCurrency($string)

    {



        if(strpos($string,'-') !== false)

        {

            $intValue = @explode("-",$string);

            $Amount = preg_replace("/[^A-Z]/","",$intValue[1]);

        }

        else

        {

            $Amount = preg_replace("/[^A-Z]/","",$string);

        }

        $Amount = substr($Amount, -3);

        return $Amount;

    }



	//check exist or not

	function chkPassenger($pname)

	{

		$sqlCheck = $this->db->query("SELECT nEId FROM {PRE}EmployeeMaster WHERE vName = '".$pname."'");

		if($sqlCheck->num_rows() > 0)

		{

			$val = $sqlCheck->row();

			$id  = $val->nEId;

		}//end if

		else

		{

			$this->db->query("INSERT INTO {PRE}EmployeeMaster (dDate,vName) VALUES ('".date('Y-m-d')."',

									'".$pname."')");

			$id = $this->db->insert_id();

		}//end else

		

		return ($id);

	}//end function

	

	//get employee id

	function getEmplId($code)

	{

		$code     = substr($code,-2);

		$sqlCheck = $this->db->query("SELECT nEId FROM {PRE}EmployeeMaster WHERE (vGallileoCD LIKE '".$code." %' 

												OR vAmadeusCD LIKE '%".$code."' OR vSabre LIKE '%".$code."%') ");

		if($sqlCheck->num_rows() > 0)

		{

			$val = $sqlCheck->row();

			$id  = $val->nEId;

		}//end if

		else

		{

			$getLoc = $this->common_model->get_field_name('EmployeeMaster','vLocation', " 

									WHERE nEId = '".$this->session->userdata('AbsAdmin')."'");

			if(intval($getLoc) == 0):

				$getLoc = $this->common_model->get_field_name('Admin','nLocation', " 

									WHERE nId = '".$this->session->userdata('AbsAdmin')."'");

			endif;

			

									

			$data = array('vCode' => $code,'nUserId' => $this->session->userdata('AbsAdmin'), 'vLocation' => $getLoc);

			$this->db->insert('{PRE}EmployeeMaster',$data);

			$id  = $this->db->insert_id();

		}//end else

		

		return ($id);

	}//end function



    function getCurrencyId($code,$rate)

	{

		$sqlCheck = $this->db->query("SELECT nId FROM {PRE}Currency WHERE (vCurrcode LIKE '".$code." %'

												OR vCurrcode LIKE '%".$code."' OR vCurrcode LIKE '%".$code."%') ");

		if($sqlCheck->num_rows() > 0)

		{

			$val = $sqlCheck->row();

			$id  = $val->nId;

		}//end if

        else

        {

            $data = array('vCurrcode' => $code,'vRate' => $rate,'nPosition' => $this->common_model->maxPos('Currency'),

                          'vActive' => '1','nUserId' => $this->session->userdata('AbsAdmin'));

            $this->db->insert('{PRE}Currency',$data);

            $id  = $this->db->insert_id();

        }//end else



		return ($id);

	}//end function

	

	function getAutomaticDocNumber2()

	{

		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='5'");

		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}TicketBooking ORDER BY nTID DESC LIMIT 0,1");

		if($fetchCode->num_rows() > 0):

			$totalNum           = $fetchCode->row();

			$this->txtDocNumber	= $totalNum->vDocNumber;

			$docNumber 			= @split('-',$this->txtDocNumber);

				

			#how many chars will be in the string

			$fill   			= $this->txtMinDigit;

			#the number

			$number 			= ($docNumber[3]+1);

			#with str_pad function the zeros will be added

			$lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);

			// The result: 000056

			

			$countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;

		else:

			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='5'");

			$countVal 			= $this->txtDocNumber;

		endif;

		

		return($countVal);

	}//end function



	//refund ticket doucment number

	function getDocNum2()

    {

        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='3'");

		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister ORDER BY nRId DESC LIMIT 0,1");

		if($fetchCode->num_rows() > 0):

			$totalNum           = $fetchCode->row();

			$this->txtDocNumber	= $totalNum->vDocNumber;

			$docNumber 			= @split('-',$this->txtDocNumber);

				

			#how many chars will be in the string

			$fill   			= $this->txtMinDigit;

			#the number

			$number 			= ($docNumber[3]+1);

			#with str_pad function the zeros will be added

			$lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);

			// The result: 000056

			

			$countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;

		else:

			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='3'");

			$countVal 			= $this->txtDocNumber;

		endif;

		

		return($countVal);

    }//end function



    function find_fareRFDA($find,$single_tickets)

    {

        $substring_array = @explode($find,$single_tickets);

        $substring_array = @explode(';', $substring_array[1]);

        $substring_array = array_filter($substring_array);

        $substring_array = array_slice($substring_array, 0);

        //echo '<pre>',print_r($substring_array,1),'</pre>';

        $fare = $substring_array[4];

        return $fare;

    }



    function arrangeArray($All_tickets)

    {

        $mutiple_ticket_status  = 0;

        $single_ticket_status   = 0;

        $conjetion_status       = 0;

        $All_tickets = array_unique($All_tickets);

        $All_tickets = array_slice($All_tickets,0);



        // For Normal or Multiple tickets Re-arranging

        for($x=0;$x<sizeof($All_tickets);$x++)

        {

            $substring_array = explode("I-00", $All_tickets[$x]);

            if (sizeof($substring_array) < 3) {

                $single_ticket_status = 1;

                //echo "Normal".$x."<br/>";

                $Single_tickets[$x] = $All_tickets[$x];

            }

            else {

                $mutiple_ticket_status = 1;

                //echo "Multi".$x."<br/>";

                $k = $x;

                for ($s = 1; $s < sizeof($substring_array); $s++) {

                    //echo "I-00".$substring_array[$s];

                    $Multi_tickets[$k][$s] = $substring_array[0] . "I-00" . $substring_array[$s];

                    $k++;

                }

            }

        }// For Normal or Multiple tickets Re-arranging



        // Joining arrays with single,multiple tickets

        if (($single_ticket_status == 0) and ($mutiple_ticket_status == 1)) {

            //if only multiple ticket

            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($Multi_tickets));

            $All_tickets = iterator_to_array($it, false);

        } else if (($single_ticket_status == 1) and ($mutiple_ticket_status == 1)) {

            //for joining multiple tickets and single ticket arrays

            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($Multi_tickets));

            $Multi_tickets = iterator_to_array($it, false);

            $All_tickets = array_merge($Multi_tickets, $Single_tickets);

            $All_tickets = array_filter($All_tickets, 'strlen');

            $All_tickets = array_slice($All_tickets, 0);

            //echo '<pre>',print_r($All_tickets,1),'</pre>';

        } else if (($single_ticket_status == 1) and ($mutiple_ticket_status == 0)) {

            //for only single tickets

            $All_tickets = $Single_tickets;

        }



        for ($z = 0; $z < sizeof($All_tickets); $z++) {

            $find = 'T-';

            $single_tickets = $All_tickets[$z];

            $substring_array = explode($find, $single_tickets);

            $tkt_no = explode(';', $substring_array[1]);

            $checking_multiple_status = substr($tkt_no[0], 15, 1);

            if ($checking_multiple_status == '-') {

                $conjetion_status = 1;

                $find = 'T-';

                $single_tickets = $All_tickets[$z];

                $substring_array = explode($find, $single_tickets);

                $tkt_no = explode(';', $substring_array[1]);

                $tkt_no = explode('-', $substring_array[1]);

                $a = substr($tkt_no[1], 0, 8);

                $b = substr($tkt_no[2], 0, 2);

                $generated_new_tkt = $a . $b;

                $air_line_code = $this->find_aireline_code2('T-', $single_tickets); // for making new string with airline code

                $test = $substring_array[0] . "T-" . $air_line_code . "-" . $generated_new_tkt . "-" . "CONJUCTION";

                $total_segment = explode('H-', $test);

                $make_ticket_string = $total_segment[0];

                for ($b = 0; $b < sizeof($total_segment); $b++) // for adding remaining segment to the second ticket

                {

                    if ($b >= 5) {

                        $make_ticket_string .= 'H-' . $total_segment[$b];

                    }

                }

                $Conjetion_array[$z] = $make_ticket_string;

            }

        }

        if ($conjetion_status == 1) // only, if conjetion ticket exists

        {

            $All_tickets = array_merge($All_tickets, $Conjetion_array);

            $All_tickets = array_filter($All_tickets, 'strlen');

            $All_tickets = array_slice($All_tickets, 0);

        }



        return ($All_tickets);

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





    //function for jv create

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

//echo print_r($data) . "<br><br>";

        $newId = $this->db->insert_id();



        return ($newId);

    }//end funciton





    //function for create jv

    function jvDetailInsert($id,$newId,$location,$user,$costOfSalesAmt,$type,$tNumber)

    {

        //insert into system jv details for tickets

        //addes sales details

        if($type == 'Tkt'):

            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,

                'nLocation' => $location,

                'nAGId' => 27,'nAccHeadId' => 73,'vAcCode' =>311110,'vType' => 'Tkt',

                'vDes' => $user,

                'nDebit' => $costOfSalesAmt,

                'nCredit' => 0);

            $this->db->insert('{PRE}SystemJvDetails', $data);

//echo print_r($data) . "<br><br>";



            $AccHead = $this->common_model->get_field_name('TicketFareDetails','nAccHead'," WHERE nTicketId = " .$tNumber ." ");

            $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");

            $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");





            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,

                'nLocation' => $location,

                'nAGId' =>$AGId ,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode,'vType' => 'Tkt',

                'vDes' => $user,

                'nDebit' => 0,

                'nCredit' => $costOfSalesAmt);

            $this->db->insert('{PRE}SystemJvDetails', $data);

//echo print_r($data) . "<br><br>";



        endif;



    }//end function



    //function for create jv

    function jvSalesInsert($id,$newId,$location,$user,$CollectedTkt,$CollectedHtl,$CollectedXo)

    {

      //insert into system jv details for tickets

      //addes sales details

        $data      = array('nSjId' => $newId,

            'nLocation' => $location,

            'nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => 311109,'vType' => 'Tkt',

            'vDes' => $user,

            'nDebit' => 0,

            'nCredit' => $CollectedTkt);

      $this->db->insert('{PRE}SystemJvDetails', $data);

      //echo print_r($data) . "<br><br>";



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

          'vType' => 'Tkt','vDes' => $user,'vClosedId' => $acId,

                    'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],

          'nDebit' => $CollectedTkt,

          'nCredit' => 0);

        $this->db->insert('{PRE}SystemJvDetails', $data);

//echo print_r($data) . "<br><br>";



      endif;

    }//end function





}

//end class

?>

