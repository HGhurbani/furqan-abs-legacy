<?php
// include("./static/amadeous/ticket_result.php");
foreach (glob("./static/amadeous/tickets/*.php") as $filename)
{
  include($filename);
}
class Amadeous_ticket_insert_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

// function not used anymore
	function getResponse()
	{


		$responseArray = array();
		$responseArray = getAmadeusTickets();
        
    	return ($responseArray);
	}//end funciton

    function insertTickets($All_tickets)
    {

      foreach (glob("./static/amadeous/tickets/*.php") as $filename)
      {
        $file = explode("/",$filename);
        $file_date = explode(".",end($file));
        $func_name = "getAmadeusTickets" . $file_date[0];


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
                    'nAcc' => '1',
                    'nAccHead' => '32',
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
              $issuer = 'ST';
            }
            else{
              $issuer = substr($ticket_item['signon_id'],0,2);
            }

            $issuedCode =  $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = '".$issuer. "'");

            if($issuedCode ==''){
              $SignId ='';
              $getDivison = $this->common_model->get_field_name('Division','nId'," ORDER BY nId "); 
              $getLocation =  $this->common_model->get_field_name('Location','nId'," WHERE vActive = '1' AND nDivision = " .$getDivison. " ORDER BY nId ");
              if($issuedCode == '') {
                $issued_data = array('vCode' => $issuer,
                    'vLocations' => $getLocation,
                    'vLocation' => $getLocation );
                $this->db->insert('{PRE}EmployeeMaster', $issued_data);
                $SignId =  $this->db->insert_id();
              }
            }
            else {
              $SignId = $issuedCode;
              $getLocation=  $this->common_model->get_field_name('EmployeeMaster','vLocation'," WHERE vCode = '".$issuer. "'");
              $getDivison=  $this->common_model->get_field_name('Location','nDivision'," WHERE nId= " .$getLocation. " ");
            }


// ---- booked if -----
            $BookedId = substr($ticket_item['booked_id'],-2);
            if($BookedId != '' || $BookedId != '-'){
              $BookedId = 'ST';
            }
            $bookedcode = $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = '".$BookedId. "'");
            if($bookedcode == '' ) { // insert into db
              $div = $this->common_model->get_field_name('Division','nId'," ORDER BY nId ");
              $loc =  $this->common_model->get_field_name('Location','nId'," WHERE vActive = '1' AND nDivision = " .$div. " ORDER BY nId ");
              $booked_data= array('vCode' => $BookedId,
                  'vLocations' => $loc,
                  'vLocation' => $loc );
              $this->db->insert('{PRE}EmployeeMaster', $booked_data);
              $bookedcode = $this->db->insert_id();
            }


              $getAirline =  $this->common_model->get_field_name('Airline','nArId', " WHERE vAirlineNo = '" . $ticket_item['air_code']. "'");
              $pass_name = $ticket_item['pass_name'];
              $gds_PNR = $ticket_item['gds_pnr'];
              $air_PNR = $ticket_item['air_pnr'];
              $orig_tkt =  $ticket_item["original_tkt_no"] !='' ? substr($ticket_item["original_tkt_no"],-10) : '';


              if($ticket_item['fare'] == "" || $ticket_item['fare'] == "-") {
                $currId = 0;
                $companyCurrcode =0;
                $rate = 0;
                $fc_fair= 0;
                $base_fair = 0;
              }
              else {
                $ticket_item['fare'] = trim($ticket_item['fare']);
                $tkt_item_fare = explode(" ",$ticket_item['fare']);

                $currId =   $this->common_model->get_field_name('Currency','nId'," WHERE vCurrcode = '" . $tkt_item_fare[1] . "'");
                $companyCurrcode = $this->common_model->getCompanyCurr();
                if($currId  == $companyCurrcode){ 
                  $rate = 1;
                  $fc_fair= end($tkt_item_fare);
                  $base_fair = end($tkt_item_fare);
                }
                else {
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



              // ticket details data
              $Carrier1 = $ticket_item['Airline1'];
              $Flight1 = $ticket_item['FlightNumber1'];
              $Class1 = $ticket_item['Class1'];
              $TravelDate1 = $ticket_item['DepDate1']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate1'])):0;
              $Sector11 = $ticket_item['Cityorigin1'];
              $Sector12 = $ticket_item['CityDesti1'];
              $status1 = $ticket_item['status1'];
              $time_of_dep1 = $ticket_item['DepTime1']!='' ? substr($ticket_item['DepTime1'],0,2). ":" . substr($ticket_item['DepTime1'],2):'';
              $time_of_arraive1 =  $ticket_item['ArrTime1']!='' ?  substr($ticket_item['ArrTime1'],0,2). ":" . substr($ticket_item['ArrTime1'],2):'';

              $Carrier2 = $ticket_item['Airline2'];
              $Flight2 = $ticket_item['FlightNumber2'];
              $Class2 = $ticket_item['Class2'];
              $TravelDate2 = $ticket_item['DepDate2']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate2'])):0;
              $Sector21 = $ticket_item['Cityorigin2'];
              $Sector22 = $ticket_item['CityDesti2'];
              $status2 = $ticket_item['status2'];
              $time_of_dep2 = $ticket_item['DepTime2']!='' ? substr($ticket_item['DepTime2'],0,2). ":" . substr($ticket_item['DepTime2'],2):'';
              $time_of_arraive2 =  $ticket_item['ArrTime2']!='' ?  substr($ticket_item['ArrTime2'],0,2). ":" . substr($ticket_item['ArrTime2'],2):'';


              $Carrier3 = $ticket_item['Airline3'];
              $Flight3 = $ticket_item['FlightNumber3'];
              $Class3 = $ticket_item['Class3'];
              $TravelDate3 = $ticket_item['DepDate3']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate3'])):0;
              $Sector31 = $ticket_item['Cityorigin3'];
              $Sector32 = $ticket_item['CityDesti3'];
              $status3 = $ticket_item['status3'];
              $time_of_dep3 = $ticket_item['DepTime3']!='' ? substr($ticket_item['DepTime3'],0,2). ":" . substr($ticket_item['DepTime3'],2):'';
              $time_of_arraive3 =  $ticket_item['ArrTime3']!='' ?  substr($ticket_item['ArrTime3'],0,2). ":" . substr($ticket_item['ArrTime3'],2):'';

              $Carrier4 = $ticket_item['Airline4'];
              $Flight4 = $ticket_item['FlightNumber4'];
              $Class4 = $ticket_item['Class4'];
              $TravelDate4 = $ticket_item['DepDate4']!='' ? date('Y-m-d', strtotime($ticket_item['DepDate4'])):0;
              $Sector41 = $ticket_item['Cityorigin4'];
              $Sector42 = $ticket_item['CityDesti4'];
              $status4 = $ticket_item['status4'];
              $time_of_dep4 = $ticket_item['DepTime4']!='' ? substr($ticket_item['DepTime4'],0,2). ":" . substr($ticket_item['DepTime4'],2):'';
              $time_of_arraive4 =  $ticket_item['ArrTime4']!='' ?  substr($ticket_item['ArrTime4'],0,2). ":" . substr($ticket_item['ArrTime4'],2):'';


              // ticket fare details
              $tax1 = ($ticket_item['Taxes1'] == '' || $ticket_item['Taxes1'] == '-' ) ? 0 :  $ticket_item['Taxes1'] ;
              $tax2 = ($ticket_item['Taxes2']  == ''|| $ticket_item['Taxes2']  == '-' ) ? 0 :  $ticket_item['Taxes2'];
              $tax3 = ($ticket_item['Taxes345']  == '' || $ticket_item['Taxes345']  == '-') ? 0 :  $ticket_item['Taxes345'];
              $totalTax = $tax1 + $tax2 + $tax3; 
              $NetAmount = ($ticket_item['NetAmount']  == ''|| $ticket_item['NetAmount']  == '-' ) ? 0 :  $ticket_item['NetAmount']; 
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
              $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
                  'nDivision' => $getDivison,
                  'nLocation' => $getLocation,
                  'dDate' => $issued_date,
                  'nAirline' => $getAirline,
                  'nGDS' => '1',
                  'vType' => $inType,
                  'vIssuedBy' => $SignId,
                  'vBookedBy' => $bookedcode,
                  'vPassenger' => $pass_name,
                  'vGdspnr' => $gds_PNR,'vAirpnr' => $air_PNR,
                  'vOriginalTkt' => $orig_tkt,
                  'nCurrency' => $currId,
                  'nRate' => $rate,
                  'vConjStatus' => 'Yes',
                  'vDocNumber' => $this->getAutomaticDocNumber2(),
                  'vTicktManual' => $ticket_no);
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
                  'vDay1' => date('D',strtotime($TravelDate1)),
                  'vCarrier2' => $Carrier2,
                  'vFlight2' => $Flight2,
                  'vClass2' => $Class2,
                  'dTravelDate2' => $TravelDate2,
                  'vSector2' => $Sector21,
                  'vSector22' => $Sector22,
                  'vStatus2' => $status2,
                  'vTimeDept2' => $time_of_dep2,
                  'vTimeArr2' => $time_of_arraive2,
                  'vDay2' => date('D',strtotime($TravelDate2)),
                  'vCarrier3' => $Carrier3,
                  'vFlight3' => $Flight3,
                  'vClass3' => $Class3,
                  'dTravelDate3' => $TravelDate3,
                  'vSector3' => $Sector31,
                  'vSector33' => $Sector32,
                  'vStatus3' => $status3,
                  'vTimeDept3' => $time_of_dep3,
                  'vTimeArr3' => $time_of_arraive3,
                  'vDay3' => date('D',strtotime($TravelDate3)),
                  'vCarrier4' => $Carrier4,
                  'vFlight4' => $Flight4,
                  'vClass4' => $Class4,
                  'dTravelDate4' => $TravelDate4,
                  'vSector4' => $Sector41,
                  'vSector44' => $Sector42,
                  'vStatus4' => $status4,
                  'vTimeDept4' => $time_of_dep4,
                  'vTimeArr4' => $time_of_arraive4,
                  'vDay4' => date('D',strtotime($TravelDate4)));
              $this->db->insert('{PRE}TicketDetails', $data);
              //        echo('<pre>');  print_r($data); echo('</pre>');


              if($ticket_item['tkt_type'] == "Void"){
                $tax1 = $tax2 = $tax3 = $totalTax = 0;
                $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;
                $pass_contactno = '';
              }

              //insert into Ticket Fare Details table
              $data 	= array('nTicketId' => $id ,'vFare' => 'N',
                  'vTax1' => $tax1,
                  'vTax2' => $tax2,
                  'vTax3' => $tax3,
                  'vTaxTotal' => $totalTax,
                  'nNetVendor' => $NetAmount,
                  'nAcc' => '1',
                  'nAccHead' => '32',
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
                      'dDate' =>  date('Y-d-m'),
                      'nTransaction' =>0,
                      'vDocNumber' => $this->getDocNum2(),
                      'nGDS' => '1',
                      'vType' => 'R',
                      'vPassenger' =>  $row->vPassenger, 
                      //	'vSector' =>
                      'nAccount' => 0, //$row->nAcCode,
                      'nUserId' => 1,
                      //	'vSegment' =>
                      //	'vDestination' =>
                      'vAcAPID' =>  $row->nDrAccount ."-". $row->vTable,
                      'nCurrency' => $row->nCurrency,
                      'nRate' => $row->nRate,
                      'vSalesDet' => $row->date,
                      'vRefundDet' => date('Y-d-m'),
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
                      'vVendorAmt' => $ticket_item['cp_charges'], 
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
      if($cnt == 0){ echo " No Records inserted. "; }
      else { echo $cnt.' record is inserted successfully.'; }
      
 // deleting the files

foreach (glob("./static/amadeous/tickets/*.php") as $filename)
{
  unlink($filename);
}


    }//end function





/*
            }
            else
            {
                if(substr($tcktType,0,3) == 'Ref'):

                    $ticketNumberVal = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."'");
                    $rfndValue = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE vTktNumber ='".$ticketNumberVal."' AND vType='Tkt'");
                    if($rfndValue->num_rows() > 0):

                        $showVal	= $rfndValue->row();

                        //insert into refund table
                        $data = array('nDivision' => $getDivison, 'nLocation' => $getLocation,
                                        'dDate' => $issued_date,'nGDS' => '1','nUserId' => $this->session->userdata('AbsAdmin'),
                                        'vType' => substr($tcktType,0,1),'vPassenger' => $pass_name,
                                        'nCurrency' =>$currId,'nAccount' => '1','vDocNumber' => $this->getDocNum2(),
                                        'vRefundDet' => $issued_date,'vActive' => '1');
                        $this->db->insert('{PRE}RefundRegister', $data);

                        $id = $this->db->insert_id();

                        $data1 = array('nRefundId' => $id);
                        $this->db->insert('{PRE}RefundRegisterBilling', $data1);

                        $data2 = array('nRefundId' => $id, 'vTktType' => 'Tkt', 'vNetAmt1' => $showVal->vNetAmt,
                            'nTktNum' => $showVal->vTktNumber,'vAirline' => $showVal->nAirline,'vSalesAmt' => $showVal->vSlsAmt,
                            'vFareAmt' => $showVal->vFare,
                            'vServiceAmt' => $showVal->vServAmt,'vTax' => $showVal->vTax,
                            'vDiscountAmt' => $showVal->vDisAmt,'vComm1' => $showVal->vCommAmt,
                            'vAgentAmt' => $showVal->vAgentAmt);
                        $this->db->insert('{PRE}RefundRegisterDetails', $data2);

                        $cnt++;
                    endif;
                endif;
            }//end else
        }//end for
*/

    //Find TicketType
    function find_TicketType($find,$single_tickets)
    {
        $pos = strpos($single_tickets,$find);

        //echo $pos;
        if(strpos($single_tickets, 'CONJUCTION') !== false)
        {
            $tkt_status = "CONJUCTION";
        }
        else
        {
            $substring_array = @explode('T-',$single_tickets);
            $tkt_no = @explode(';', $substring_array[1]);
            $tkt_no = @explode('-', $substring_array[1]);

            $reissed_checking = substr($tkt_no[0], 1, 3);
            $reissed_tkt_no = substr($tkt_no[1], 0, 10);
            $reissed = @explode($reissed_tkt_no,$single_tickets);



            $reissed_tkt_no = substr($tkt_no[1], 0, 10);
            $reissed = @explode($reissed_tkt_no,$single_tickets);
            if(strpos($reissed[1], 'FH') !== false)
            {
                $tkt_status = "RE-ISSUED";
            }
            else if(strpos($reissed[1], 'FO'.$reissed_checking.'-') !== false)
            {
                $reissed_checking1 = @explode('FO'.$reissed_checking.'-', $substring_array[1]);
                $org_tkt_no = substr($reissed_checking1[1], 0, 10);
                //echo '<pre>',print_r($reissed_checking1,1),'</pre>';
                $tkt_status = "RE-ISSUED";
                return $tkt_status;
            } else
            {
                if (($pos == false) or ($pos>100))
                {
                    $tkt_status = "NORMAL";
                }
                else
                {
                    $tkt_status = "VOID";
                }
            }
        }

        if(strpos($single_tickets, 'RFDA;') !== false)
        {
            $tkt_status = "Refund";
        }

        return $tkt_status;
    }

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
            $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
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

            $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
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
		$sqlCheck = $this->db->query("SELECT nEId FROM {PRE}EmployeeMaster WHERE (vCode LIKE '".$code." %' 
												OR vCode LIKE '%".$code."' OR vCode LIKE '%".$code."%') ");
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
}

//end class
?>
