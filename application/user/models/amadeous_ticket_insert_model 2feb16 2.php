<?php

$date_time = date('Y-m-d');
include('./static/amadeous/tickets/ticket_result_' . $date_time . ".php");
//$file='tickets/' . $date_time . ".php";
class Amadeous_ticket_insert_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function getResponse()
	{
		$responseArray = array();
		$responseArray = getAmadeusTickets();
        
    	return ($responseArray);
	}//end funciton

    function insertTickets($All_tickets,$decduct)
    {
		
		$cnt = 0;				
		for ($i = 0; $i < sizeof($All_tickets)-$decduct; $i++) {

			$ticket_no          = $this->find_tkt_no('T-',$All_tickets[$i]);
			 $com          = $this->commi('FTNR',$All_tickets[$i]);
            $tcktType 			= $this->find_TicketType('VOID',$All_tickets[$i]);
            $gds_PNR            = $this->find_gdspnr('MUC1A',$All_tickets[$i]);
            //$gds_PNR            = substr($gds_PNR,3,strlen($gds_PNR));
            $gds_PNR            = substr($gds_PNR,0,7);

            $iatacode           = $this->find_iatacode('MUC1A',$All_tickets[$i]);
            $air_PNR            = $this->find_airpnr('MUC1A',$All_tickets[$i]);
            //$air_PNR            = substr($air_PNR,3,-2);
            $air_PNR            = substr($air_PNR,3,-1);
            $Aircode            = $this->find_airlinecode1('T-',$All_tickets[$i]);
			$pcc            = $this->find_pcc('MUC1A',$All_tickets[$i],$tcktType);
            $Aircode            = substr(trim($Aircode),0,3);


            $SignId 			= $this->find_issuedid('MUC1A',$All_tickets[$i]);
            //$SignId 			= substr($SignId,strlen(trim($SignId))-2,strlen(trim($SignId)));
            $SignId 			= substr($SignId,4,2);
			if($pcc == "KWIKT2199")
			{
			
				$getLocation = 1;
				$getDivison=1;
				$nAcc = '1';
                $nAccHead = '52';
			
			}
			if($pcc == "KWIKT2605")
			{
			
				$getLocation = 2;
				$getDivison=1;
				$nAcc = '212';
                $nAccHead = '52';
			
			}
			if($pcc == "KWIKT2611")
			{
			
				$getLocation = 3;
				$getDivison=1;
				$nAcc = '213';
				$nAccHead = '52';
			
			}
            $SignId				= $this->getEmplId(trim($SignId),$getLocation);
            $SignId		        = (intval($SignId) != 0 )?$SignId:0;
            //$BookedId 			= $this->find_bookingid('MUC1A',$All_tickets[$i]);
            //$BookedId 	    = substr($BookedId,strlen(trim($BookedId))-2,strlen(trim($BookedId)));
            //$BookedId 			= substr($BookedId,4,2);
            //$BookedId			= $this->getEmplId(trim($BookedId),$getLocation);
            //$BookedId		    = (intval($BookedId) != 0 )?$BookedId:0;

            $issued_date        = $this->find_dateofissue('MUC1A',$All_tickets[$i]);
            $issued_date        = substr($issued_date,0,2)."-".substr($issued_date,2,2)."-".substr($issued_date,4,2);
            $issued_date        = date('Y-m-d',strtotime($issued_date));

            $fare               ='-';
            $fare_fc            = $this->find_fc_fare('K-F',$All_tickets[$i]);

            if (strpos($fare_fc, 'KWD') != false) {
                $fare_fc        = "-";
                $fare           = $this->find_fc_fare('K-F',$All_tickets[$i]);
            }
            else {
                $fare           = $this->find_fc_fare('K-F',$All_tickets[$i]);
            }
			
            $base_fair			= substr($fare,3,strlen($fare));
            $fc_fair			= substr($fare_fc,3,strlen($fare_fc));
            $rate               = $this->find_Rate('K-',$All_tickets[$i]);
            $rate               = (intval($rate) != 0 )?$rate:0;
			$used            = $this->used('K-F',$All_tickets[$i]);
			$refundxt=$this->refundxt('K-F',$All_tickets[$i]);
			$vendor=$this->vendor('K-F',$All_tickets[$i]);
            $cunt =1;
            $taxVal1 = $taxVal2 = $taxVal3 = 0;
            $tax1               = $this->find_All_taxes($All_tickets[$i]);
            for($q=1;$q<=10;$q++)
            {
                if($tax1[$q]!="")
                {
                    if($cunt==1):
                        $taxVal1 = substr($tax1[$q],4,-5);
                    elseif($cunt==2):
                        $taxVal2 = substr($tax1[$q],4,-5);
                    else:
                        $taxVal3 += substr($tax1[$q],4,-5);
                    endif;
                    $cunt++;
                }
            }
            $tax1               = $taxVal1;
            $tax2               = $taxVal2;
            $tax3               = $taxVal3;
            $totalTax           = $tax1+$tax2+$tax3;
			

            $NetAmount          = $tax1 + $tax2 + $tax3 + $base_fair;
			
            $pass_name          = $this->find_pass_name('I-00',$All_tickets[$i]);
            $pass_contactno     = $this->find_pass_contactno('CTC PAX',$All_tickets[$i]);

			$getLocCode         = $this->common_model->get_field_name('EmployeeMaster','vLocation'," WHERE nEId = '".$SignId."'");
			
			//$getLocation		= $this->common_model->get_field_name('Location','nId'," WHERE nId = '".$getLocCode."'");
			//$getDivison 		= $this->common_model->get_field_name('Location','nDivision'," WHERE nId = '".$getLocCode."'");
			$getDivison			= (intval($getDivison) != 0 )?$getDivison:0;
			$getAirline 		= $this->common_model->get_field_name('Airline','nArId'," WHERE  vAirlineNo = '".$Aircode."'");
            $getAirline			= (intval($getAirline) != 0 )?$getAirline:0;

            $segments_all       = $this->find_SegmentDetail($All_tickets[$i]);
		//echo sizeof($segments_all);
		if(substr($tcktType,0,1) != 'C'){
			$seg=sizeof($segments_all);
						$Carrier1='';$Flight1='';$Class1='';$TravelDate1=''; $Sector11='';$Sector12='';$status1='';$time_of_dep1='';$time_of_arraive1='';
						$Carrier2='';$Flight2='';$Class2='';$TravelDate2=''; $Sector21='';$Sector22='';$status2='';$time_of_dep2='';$time_of_arraive2='';
						$Carrier3='';$Flight3='';$Class3='';$TravelDate3=''; $Sector31='';$Sector32='';$status3='';$time_of_dep3='';$time_of_arraive3='';
						$Carrier4='';$Flight4='';$Class4='';$TravelDate4=''; $Sector41='';$Sector42='';$status4='';$time_of_dep4='';$time_of_arraive4='';
						
            for($j=0;$j<sizeof($segments_all);$j++)
            {
				
                $single_segments[$j] = explode(';',$segments_all[$j]);
                if($j==0)
                {
                    $Carrier1           = $this->find_carrier_code($single_segments[$j][5]);
                    $Flight1            = $this->find_flight_no($single_segments[$j][5]);
                    $Class1             = $this->find_class($single_segments[$j][5]);
                    $TravelDate1        = $this->find_date_of_travel($single_segments[$j][5]);
                    $TravelDate1        = date('Y-m-d',strtotime($TravelDate1));
                    $Sector11           = $single_segments[$j][1];
                    $Sector11           = substr($Sector11,4,strlen($Sector11));
                    $Sector12           = $single_segments[$j][3];
                    $status1            = $single_segments[$j][7];
                    $status1            = substr($status1,0,-2);
                    $time_of_dep1       = $this->find_date_of_dep($single_segments[$j][5]);
                    $time_of_dep1       = substr($time_of_dep1,0,2).":".substr($time_of_dep1,2,2);
                    $time_of_arraive1   = $this->find_date_of_arraive($single_segments[$j][5]);
                    $time_of_arraive1   = substr($time_of_arraive1,0,2).":".substr($time_of_arraive1,2,2);
                }
                elseif($j==1)
                {
                    $Carrier2           = $this->find_carrier_code($single_segments[$j][5]);
                    $Flight2            = $this->find_flight_no($single_segments[$j][5]);
                    $Class2             = $this->find_class($single_segments[$j][5]);
                    $TravelDate2        = $this->find_date_of_travel($single_segments[$j][5]);
                    $TravelDate2        = date('Y-m-d',strtotime($TravelDate2));
                    $Sector21           = $single_segments[$j][1];
                    $Sector21           = substr($Sector21,4,strlen($Sector21));
                    $Sector22           = $single_segments[$j][3];
                    $status2            = $single_segments[$j][7];
                    $status2            = substr($status2,0,-2);
                    $time_of_dep2       = $this->find_date_of_dep($single_segments[$j][5]);
                    $time_of_dep2       = substr($time_of_dep2,0,2).":".substr($time_of_dep2,2,2);
                    $time_of_arraive2   = $this->find_date_of_arraive($single_segments[$j][5]);
                    $time_of_arraive2   = substr($time_of_arraive2,0,2).":".substr($time_of_arraive2,2,2);
                }
                elseif($j==2)
                {
                    $Carrier3           = $this->find_carrier_code($single_segments[$j][5]);
                    $Flight3            = $this->find_flight_no($single_segments[$j][5]);
                    $Class3             = $this->find_class($single_segments[$j][5]);
                    $TravelDate3        = $this->find_date_of_travel($single_segments[$j][5]);
                    $TravelDate3        = date('Y-m-d',strtotime($TravelDate3));
                    $Sector31           = $single_segments[$j][1];
                    $Sector31           = substr($Sector31,4,strlen($Sector31));
                    $Sector32           = $single_segments[$j][3];
                    $status3            = $single_segments[$j][7];
                    $status3            = substr($status3,0,-2);
                    $time_of_dep3       = $this->find_date_of_dep($single_segments[$j][5]);
                    $time_of_dep3       = substr($time_of_dep3,0,2).":".substr($time_of_dep3,2,2);
                    $time_of_arraive3   = $this->find_date_of_arraive($single_segments[$j][5]);
                    $time_of_arraive3   = substr($time_of_arraive3,0,2).":".substr($time_of_arraive3,2,2);
                }
                elseif($j==3) 
                {
                    $Carrier4           = $this->find_carrier_code($single_segments[$j][5]);
                    $Flight4            = $this->find_flight_no($single_segments[$j][5]);
                    $Class4             = $this->find_class($single_segments[$j][5]);
                    $TravelDate4        = $this->find_date_of_travel($single_segments[$j][5]);
                    $TravelDate4        = date('Y-m-d',strtotime($TravelDate4));
                    $Sector41           = $single_segments[$j][1];
                    $Sector41           = substr($Sector41,4,strlen($Sector41));
                    $Sector42           = $single_segments[$j][3];
                    $status4            = $single_segments[$j][7];
                    $status4            = substr($status4,0,-2);
                    $time_of_dep4       = $this->find_date_of_dep($single_segments[$j][5]);
                    $time_of_dep4       = substr($time_of_dep4,0,2).":".substr($time_of_dep4,2,2);
                    $time_of_arraive4   = $this->find_date_of_arraive($single_segments[$j][5]);
                    $time_of_arraive4   = substr($time_of_arraive4,0,2).":".substr($time_of_arraive4,2,2);
                }
            }//end for
		}
			if(substr($tcktType,0,1) == 'C')				
					{
						 $seg=sizeof($segments_all);
						$Carrier1='';$Flight1='';$Class1='';$TravelDate1=''; $Sector11='';$Sector12='';$status1='';$time_of_dep1='';$time_of_arraive1='';
						$Carrier2='';$Flight2='';$Class2='';$TravelDate2=''; $Sector21='';$Sector22='';$status2='';$time_of_dep2='';$time_of_arraive2='';
						$Carrier3='';$Flight3='';$Class3='';$TravelDate3=''; $Sector31='';$Sector32='';$status3='';$time_of_dep3='';$time_of_arraive3='';
						$Carrier4='';$Flight4='';$Class4='';$TravelDate4=''; $Sector41='';$Sector42='';$status4='';$time_of_dep4='';$time_of_arraive4='';
						
						for($k=0;$k<$val;$k++)
            {
				
                $single_segments[$k] = explode(';',$segments_all[$k]);
                if($k==0)
                {
                    $Carrier1           = $this->find_carrier_code($single_segments[$k][5]);
                    $Flight1            = $this->find_flight_no($single_segments[$k][5]);
                    $Class1             = $this->find_class($single_segments[$k][5]);
                    $TravelDate1        = $this->find_date_of_travel($single_segments[$k][5]);
                    $TravelDate1        = date('Y-m-d',strtotime($TravelDate1));
                    $Sector11           = $single_segments[$k][1];
                    $Sector11           = substr($Sector11,4,strlen($Sector11));
                    $Sector12           = $single_segments[$k][3];
                    $status1            = $single_segments[$k][7];
                    $status1            = substr($status1,0,-2);
                    $time_of_dep1       = $this->find_date_of_dep($single_segments[$k][5]);
                    $time_of_dep1       = substr($time_of_dep1,0,2).":".substr($time_of_dep1,2,2);
                    $time_of_arraive1   = $this->find_date_of_arraive($single_segments[$k][5]);
                    $time_of_arraive1   = substr($time_of_arraive1,0,2).":".substr($time_of_arraive1,2,2);
                }
                elseif($k==1)
                {
                    $Carrier2           = $this->find_carrier_code($single_segments[$k][5]);
                    $Flight2            = $this->find_flight_no($single_segments[$k][5]);
                    $Class2             = $this->find_class($single_segments[$k][5]);
                    $TravelDate2        = $this->find_date_of_travel($single_segments[$k][5]);
                    $TravelDate2        = date('Y-m-d',strtotime($TravelDate2));
                    $Sector21           = $single_segments[$k][1];
                    $Sector21           = substr($Sector21,4,strlen($Sector21));
                    $Sector22           = $single_segments[$k][3];
                    $status2            = $single_segments[$k][7];
                    $status2            = substr($status2,0,-2);
                    $time_of_dep2       = $this->find_date_of_dep($single_segments[$k][5]);
                    $time_of_dep2       = substr($time_of_dep2,0,2).":".substr($time_of_dep2,2,2);
                    $time_of_arraive2   = $this->find_date_of_arraive($single_segments[$k][5]);
                    $time_of_arraive2   = substr($time_of_arraive2,0,2).":".substr($time_of_arraive2,2,2);
                }
                elseif($k==2)
                {
                    $Carrier3           = $this->find_carrier_code($single_segments[$k][5]);
                    $Flight3            = $this->find_flight_no($single_segments[$k][5]);
                    $Class3             = $this->find_class($single_segments[$k][5]);
                    $TravelDate3        = $this->find_date_of_travel($single_segments[$k][5]);
                    $TravelDate3        = date('Y-m-d',strtotime($TravelDate3));
                    $Sector31           = $single_segments[$k][1];
                    $Sector31           = substr($Sector31,4,strlen($Sector31));
                    $Sector32           = $single_segments[$k][3];
                    $status3            = $single_segments[$k][7];
                    $status3            = substr($status3,0,-2);
                    $time_of_dep3       = $this->find_date_of_dep($single_segments[$k][5]);
                    $time_of_dep3       = substr($time_of_dep3,0,2).":".substr($time_of_dep3,2,2);
                    $time_of_arraive3   = $this->find_date_of_arraive($single_segments[$k][5]);
                    $time_of_arraive3   = substr($time_of_arraive3,0,2).":".substr($time_of_arraive3,2,2);
                }
                elseif($k==3) 
                {
					
                    $Carrier4           = $this->find_carrier_code($single_segments[$k][5]);
                    $Flight4            = $this->find_flight_no($single_segments[$k][5]);
                    $Class4             = $this->find_class($single_segments[$k][5]);
                    $TravelDate4        = $this->find_date_of_travel($single_segments[$k][5]);
                    $TravelDate4        = date('Y-m-d',strtotime($TravelDate4));
                    $Sector41           = $single_segments[$k][1];
                    $Sector41           = substr($Sector41,4,strlen($Sector41));
                    $Sector42           = $single_segments[$k][3];
                    $status4            = $single_segments[$k][7];
                    $status4            = substr($status4,0,-2);
                    $time_of_dep4       = $this->find_date_of_dep($single_segments[$k][5]);
                    $time_of_dep4       = substr($time_of_dep4,0,2).":".substr($time_of_dep4,2,2);
                    $time_of_arraive4   = $this->find_date_of_arraive($single_segments[$k][5]);
                    $time_of_arraive4   = substr($time_of_arraive4,0,2).":".substr($time_of_arraive4,2,2);
                }
            }//end for
						
					}
			
            $ticketValue        = $this->db->query("SELECT * FROM {PRE}TicketBooking WHERE vTicktManual = '".$ticket_no."'"); 			//AND dDate = '".$issued_date."'");
			
			$gds_PNR = (trim($gds_PNR) != '')?$gds_PNR:'0';
			$air_PNR = (trim($air_PNR) != '')?$air_PNR:'0';
			
					if(substr($tcktType,0,1) == 'C')				
					{
						$tax1 = $tax2 = $tax3 = $totalTax = 0;               
						$NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;  
					}
					if(substr($tcktType,0,1) == 'V')				
					{				
						 $status = 'V'; 
//echo $status;						 
						//$closedId    =  $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vTktNumber= '".$ticket_no. "' AND vType='Tkt'" );                if($closedId != '') { echo "Ticket#" . $ticket_no . " is already closed. Changing status to VOID...    ";}               
						$nid=$this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual= '".$ticket_no. "'");           
						$data   = array( 'vType' => $status);                
						$this->db->where('nTId',$nid);                
						$this->db->update('{PRE}TicketBooking', $data);                 
						 echo"<pre>".print_r($data)."</pre>";            
						$tax1 = $tax2 = $tax3 = $totalTax = 0;               
						$NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;                
						$pass_contactno = '';                          
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
						$this->db->where('nTicketId',$nid);                
						$this->db->update('{PRE}TicketFareDetails', $data2);									
						}//end if								
       
							if($tcktType == 'RE-ISSUED'){
			$tax1           = 0;
            $tax2               = 0;
            $tax3               = 0;
            $totalTax           = 0;
			

            $NetAmount          = $tax1 + $tax2 + $tax3 + $base_fair;
				
			}
			
			$tc =$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE  vTicktManual = '".substr($ticket_no,0,10)."'");
           
		  if($tcktType != 'REFUND')
            {
				//echo $tcktType."<br>";
				
				
				$inType = (substr($tcktType,0,1) == 'O')?'N':substr($tcktType,0,1);
				
				if($inType == 'V')
				{
					$tax1 = $tax2 = $tax3 = $base_fair = $tot_fair = 0;										
					$issued_date= date('Y-m-d');
				}//end if ||($tcktType=='RE-ISSUED')(substr($tcktType,0,1) != 'R') &&
					// echo $tc."--".$ticket_no."<br>";
				if($tc!=substr($ticket_no,0,10)):
				//echo $tc."<br>".$ticket_no;
					//insert into ticket booking master table
					$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
										'nDivision' => $getDivison,
										'nLocation' => $getLocation,
                                        'dDate' => $issued_date,
										'nAirline' => $getAirline,
										'nGDS' => '1',
										'vType' => $inType,
										'vIssuedBy' => $SignId,
										'vBookedBy' => $SignId,
										'vPassenger' => $pass_name,
										'vGdspnr' => $gds_PNR,'vAirpnr' => $air_PNR,
										'nCurrency' => '5',
										'nRate' => '1',
										'vConjStatus' => 'Yes',
										'vDocNumber' => $this->getAutomaticDocNumber2(),
										'vTicktManual' => substr($ticket_no,0,10),
										'vOriginalTkt' => substr($ticket_no,13,10));
								
					$this->db->insert('{PRE}TicketBooking', $data);
	
					$id	    = $this->db->insert_id();
			
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
										'vSegment'=> $seg,
										'vDay4' => date('D',strtotime($TravelDate4)));
					$this->db->insert('{PRE}TicketDetails', $data);

					if($com==''){
						$co=0;
					}else{
						$co=$fc_fair/$com;
						$NetAmount=$NetAmount-$co;
					}
					//insert into Ticket Fare Details table
					$data 	= array('nTicketId' => $id ,'vFare' => 'N',
						'vTax1' => $tax1,
						'vTax2' => $tax2,
						'vTax3' => $tax3,
						'vTaxTotal' => $totalTax,
						'nNetVendor' => $NetAmount,
						'nAcc' => $nAcc,
						'nAccHead' => $nAccHead,
						'vFCFare' => $fc_fair,
						'vPassengerContact' => $pass_contactno,
						'vFaceValue' => $base_fair,
						'nComm'=>$com,
						'nCommAmt'=>$co);
					$this->db->insert('{PRE}TicketFareDetails', $data);
						
					//insert into Ticket Billing Details table
					$data 	= array('nTicketId' => $id ,
										'vSalesAmt' => '0',
										'vCollected' => '0');
					$this->db->insert('{PRE}TicketBillingDetails', $data);
					$cnt++;
				endif;
            }
             else{
				 $tid       = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."' ");
				 $rfndValue = $this->db->query("SELECT rd.nTktNum FROM {PRE}RefundRegister r

														LEFT JOIN {PRE}RefundRegisterDetails rd

															ON r.nRId = rd.nRefundId

														WHERE rd.nTktNum = '".$tid."'");
				$rfndValue1 = $this->db->query("SELECT rd.nTktNum FROM {PRE}RefundRegister r

														LEFT JOIN {PRE}RefundRegisterDetails rd

															ON r.nRId = rd.nRefundId

														WHERE rd.nTktNum = '".$ticket_no."'");
				if($rfndValue->num_rows() == 0 && $rfndValue1->num_rows()==0):



						//insert into refund table

						$data = array('nDivision' => $getDivison, 'nLocation' => $getLocation,

									  'dDate' => $issued_date,'nGDS' => '1',

									  'vType' => substr($tcktType,0,1),'vPassenger' => $pass_name,

									  'nCurrency' => '5','nAccount' => $nAcc,

									  'vDocNumber' => $this->getDocNum2($getLocation),

									  'vRefundDet' => $issued_date,

									  'vActive' => '1');
						//echo"<pre>".print_r($data)."</pre>";
						$this->db->insert('{PRE}RefundRegister', $data);



						$id = $this->db->insert_id();

				$tid       = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."' ");
						
						$scid       = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber = '".$tid."' ");
						$acc       = $this->common_model->get_field_name('SalesAccountDetails','nDrAccount'," WHERE nSalesCloseId = '".$scid."' ");
						$tbl       = $this->common_model->get_field_name('SalesAccountDetails','vTable'," WHERE nSalesCloseId = '".$scid."' ");
						$nac=$acc.'-'.$tbl;
						//insert into refund billing table

						$data1 = array('nRefundId' => $id,

										
										'vInvoice' => '0',

										'vMode' => 'Cr',

										'nAcCode' => $nac,
										'vAcSubCode' => '0',
										'nAgentAc' => '0',
										'vLPO'=>'0');

						$this->db->insert('{PRE}RefundRegisterBilling', $data1);



						//insert into refund vendor details table
/*
						$data1 = array('nRefundId' => $id, 'vFareAmt' => $base_fair,

										'vTax' => $totalTax,

										'vIssuedBy' => $SignId, 'vNetAmt1' => $NetAmount);

						$this->db->insert('{PRE}RefundVendorDetails', $data1);*/



					    //insert into RefundRegister Details table
						$tid       = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."' ");
						
						$samt       = $this->common_model->get_field_name('SalesClosingDetails','vSlsAmt'," WHERE vTktNumber = '".$tid."' ");
						$vServAmt       = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE vTktNumber = '".$tid."' ");
						$netAmt=$fc_fair+$refundxt;
						$netRef=$fc_fair-$used-$vendor+$refundxt;
						
						$paid=$samt-$vendor;
						$seramt=$netRef-$paid;
						$serv=($seramt/$netRef)*100;
						
						if($samt==0){
							$newOld='O';
							$ticket_no=$ticket_no;
							$samt=$this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId = '".$tid."' ");
							if($samt==0){
								$samt=0;
							}
							
							$vServAmt=0;
							$paid=$samt-$vendor;
						$seramt=$netRef-$paid;
						$serv=($seramt/$netRef)*100;
						}
						else{
							$newOld='N';
							$ticket_no=$tid;
						}
						
						$data2 = array('nRefundId' => $id, 
										'vTktType' => 'Tkt', 
										'vNewOld' => $newOld,

										'nTktNum' => $ticket_no,
										'vAirline' => $Aircode,
										'vPassenger' => $pass_name,										
										'vIssuedBy'=>$SignId,
										'vFareAmt'=>$fc_fair,
										'vUsedFare'=>$used,
										'vTax'=>$refundxt,
										'vNetAmt1'=>$netAmt,
										'vVendorAmt'=>$vendor,
										'vSalesAmt'=>$samt,
										'vNetAmt2'=>$netRef,
										'vServiceAmt'=>$vServAmt,
										'vServiceAmt2'=>$seramt,
										'vServicePer'=>$serv,
										'vPaidTo'=>$paid);
						//echo"<pre>".print_r($data2)."</pre>";
						$this->db->insert('{PRE}RefundRegisterDetails', $data2);



						$cnt++;	
						endif;
			 }
        }//end for
		echo $cnt.' record is inserted successfully.';
		
		    }//end function

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
            if (strpos($single_tickets, 'KRF') !== false)
            {
                $tkt_status = "REFUND";
            }
			elseif (strpos($single_tickets, 'KFTR') !== false)
            {
                $tkt_status = "RE-ISSUED";
            }
			elseif(strpos($single_tickets, 'KNTY') !== false){
				$tkt_status = "RE-ISSUED";
			}
            else
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

        return $tkt_status;
    }//Find TicketType

    //Find Gds Pnr
    function find_gdspnr($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $gds_pnr = explode(';', $substring_array[1]);
        return $gds_pnr[0];
    }//Find Gds Pnr

    //Find Iata code
    function find_iatacode($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $iata_code = explode(';', $substring_array[1]);
        return $iata_code[3];
    }//Find Iata code

    //Find AIR PNR
    function find_airpnr($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $air_pnr = explode(';', $substring_array[1]);
			//	echo '<pre>',print_r($air_pnr,1),'</pre>';
        $air_pnr = array_filter($air_pnr);
        $air_pnr = array_slice($air_pnr, 0);
        $air_pnr = explode('-',$air_pnr[10]);
		//echo $air_pnr[0];
        return $air_pnr[0];
    }//Find AIR PNR
	function commi($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $comm = explode('/', $substring_array[1]);
 // var_dump($comm);       
	  // var_dump($comm[0]);
	   $ring=$comm[0];
	  $finalString=substr($ring,-3);
	 //var_dump($finalString);
	    $finalStr=substr($finalString,0,2);
	   //var_dump($finalStr);
        return $finalStr;
    }
	//Find AIR PNR
    function find_pcc($find,$single_tickets,$tcktType)
    {
		//echo "Now";
        if($tcktType=='VOID' || $tcktType=='REFUND'){
		$file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
		//echo '<pre>',print_r($substring_array,1),'</pre>';
        $air_pnr = explode(';', $substring_array[1]);
        $air_pnr = array_filter($air_pnr);
				
		$air_pnr = array_slice($air_pnr, 0);
		$air_pnr=$air_pnr[2];
		}
		else{
			//echo"entered";
			if(strpos($single_tickets, 'EMD') !== false){
				//echo"1";
				$file = $single_tickets;
					$substring_array = explode($find,$single_tickets);
					//echo '<pre>',print_r($substring_array,1),'</pre>';
					$air_pnr = explode(';', $substring_array[1]);
					$air_pnr = array_filter($air_pnr);
							
					$air_pnr = array_slice($air_pnr, 0);
					$air_pnr=$air_pnr[2];
			}else{
					
				//echo"2";					
				$file = $single_tickets;
				$substring_array = explode('TKOK',$single_tickets);
				
				$air_pnr = explode('/', $substring_array[1]);
				$air_pnr = array_filter($air_pnr);
				//echo '<pre>',print_r($air_pnr,1),'</pre>';
						
				$air_pnr = array_slice($air_pnr, 0);
				 $air_pnr=substr($air_pnr[1],0,9);
			}
			
		}
		
        return $air_pnr;
    }//Find AIR PNR

    //Find AIRLINE CODE
	
	
    function find_airlinecode1($find,$single_tickets)
    {
        $file = $single_tickets;
        //echo '<pre>',print_r($bookingid,1),'</pre>';
        $startIndex=strrpos($file,'A-');
		//echo"entered";
		 $startInd=strrpos($file,'TMCD');
		 $startI=strrpos($file,'KRF');
		
        if(!empty($startIndex))
        {
           // echo"top";
			$endIndex=strpos($file,'B-');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(' ',$finalString);
            //echo '<pre>',print_r($substring_array,1),'</pre>';
            $last_element = sizeof($substring_array)-1;

            return $substring_array[$last_element];
        }elseif(!empty($startInd)){
			 //echo"down";
			$tkt_no = explode('TMCD', $single_tickets); 
			 //echo '<pre>',print_r($tkt_no),'</pre>';
             $tck = substr($tkt_no[1], 0, 3);
			  return $tck;
		}
		elseif(!empty($startI)){
			 //echo"down";
			$tkt_no = explode('T-', $single_tickets); 
			 //echo '<pre>',print_r($tkt_no),'</pre>';
             $tck = substr($tkt_no[1], 1, 3);
			  return $tck;
		}
else{return "-";}
		
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
            $substring_array = explode(' ',$finalString);
            $substring_array = explode('-',$substring_array[1]);
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
            $substring_array = explode(' ',$finalString);
            $substring_array = explode('-',$substring_array[1]);
			//echo '<pre>',print_r($substring_array,1),'</pre>';
            return $substring_array[1];
        }else{return "-";}
    }//Find ISSUED ID

    //Find DATE OF ISSUE
    /*function find_dateofissue($find,$single_tickets)
    {
        $file = $single_tickets;
        //echo '<pre>',print_r($test,1),'</pre>';
        $startIndex=strpos($file,'D-');
        if(!empty($startIndex))
        {
            $endIndex=strpos($file,'G-');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(';',$finalString);

            return $substring_array[0];
        }else{return "-";}
    }//Find DATE OF ISSUE*/

    function find_dateofissue($find,$single_tickets)
    {
        $file = $single_tickets;
        //echo '<pre>',print_r($test,1),'</pre>';
        $startIndex=strpos($file,'D-');
		$endInde=strpos($file,'U-');
        if(!empty($startIndex)&& !empty($endInde))
        {
			//echo"he";
             $endIndex=strpos($file,'U-');
            $strLength=$endIndex-$startIndex;
			//echo"<br>";
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(';',$finalString);
			//echo"<br>";
			//echo '<pre>',print_r($substring_array,1),'</pre>';
            $numItems = count($substring_array);
            $dateofissue = substr($substring_array[2], 0, 6);
            return $dateofissue;

        }
		elseif(!empty($startIndex))
        {//echo"test";
            $endIndex=strpos($file,'G-');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(';',$finalString);
			//echo '<pre>',print_r($substring_array,1),'</pre>';
            $numItems = count($substring_array);
            $dateofissue = substr($substring_array[$numItems-1], 0, 6);
			  $dateofissue = substr($substring_array[2], 0, 6);
            return $dateofissue;

        }
        else{
			//echo"testba";
            return "-";
        }
    }

    //Find Passenger Fc Fare  // 11-Mar-2014  add new function
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
                $substring_array = explode($find,$single_tickets);
                $substring_array = explode(';', $substring_array[1]);
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
				if (strpos($single_tickets, 'K-FI') !== false)
                {
                    if (strpos($single_tickets, $find) !== false)
                    {
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                       // echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[0];

                        return $fc_fare;


                    }
                }

                elseif (strpos($single_tickets, 'K-F') !== false)
                {
                    if (strpos($single_tickets, $find) !== false)
                    {
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
						$tk=substr($substring_array[0],0,3);
						if($tk=='KWD'){
                        $fc_fare = $substring_array[0];
						}else{
							$fc_fare = $substring_array[1];
						}
                        return $fc_fare;


                    }
                }
                else if (strpos($single_tickets, 'K-') !== false)
                {

                    if (strpos($single_tickets, 'K-') !== false)
                    {
                        $substring_array = explode('K-',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
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

    }//Find Passenger Fc Fare // 11-Mar-2014

    //Find find_tkt_no
    function find_tkt_no($find,$single_tickets)
    {
        $file = $single_tickets; 
        $substring_array = explode($find,$single_tickets);
		//echo '<pre>',print_r($substring_array,1),'</pre>';
		   $emd=$substring_array[3];
		   $tk=substr($substring_array[1], 0, 1);
		   $tk1=substr($substring_array[1], 0, 3);
		   $ck=substr($substring_array[3], 0, 1);
		   //$ck1=substr($substring_array[1], 0, 3);
        $tkt_no = explode(';', $substring_array[1]);
      
		$tkt_no = explode('-', $substring_array[1]);
		//echo '<pre>',print_r($tkt_no[1]),'</pre>';
        if (strpos($single_tickets, 'KFTR') !== false) // to show original ticket and reissued
        {
         if($tk=='K' && $tk1!='KWI')   {
			// echo"Test";
			  $reissed_tkt_no = substr($tkt_no[1], 0, 10);
			
           $org_tkt_no = substr($tkt_no[4], 0, 10);
		 
		     $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
		 }elseif($ck=='K'){
			 $tkt_no = explode('-', $substring_array[3]);

			$reissed_tkt_no = substr($tkt_no[1], 0, 10);
			$org_tkt_no = substr($tkt_no[2], 0, 10);
			 $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
		 }
		 
		 else{
			 
		  $tkt_no = explode('-', $substring_array[2]);
//echo '<pre>',print_r($tkt_no),'</pre>';
			$reissed_tkt_no = substr($tkt_no[1], 0, 10);
			$org_tkt_no = substr($tkt_no[2], 0, 10);
			 $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
		 }
        }
		elseif (strpos($single_tickets, 'EMD') !== false) // to show original ticket and reissued
        {
			
			 $tkt_no = explode('TMCD', $single_tickets); 
			 //echo '<pre>',print_r($tkt_no),'</pre>';
             $reissed_tkt_no = substr($tkt_no[1], 4, 10);
			 
           // $org_tkt_no = substr($tkt_no[4], 0, 10);
		  //echo"<br>";
            $tkt_no = $reissed_tkt_no;
        }
		elseif (strpos($single_tickets, 'KNTY') !== false) // to show original ticket and reissued
        {
             $reissed_tkt_no = substr($tkt_no[1], 0, 10);
			 
            $org_tkt_no = substr($tkt_no[4], 0, 10);
		  //echo"<br>";
            $tkt_no = $reissed_tkt_no." ( ".$org_tkt_no." ) ";
        }
		elseif (strpos($single_tickets, 'KRF') !== false) // to show original ticket and reissued
        {
			$tkt_no = explode('T-', $single_tickets); 
			// echo '<pre>',print_r($tkt_no),'</pre>';
              $tkt_no = substr($tkt_no[1], 5, 10);
			 
            
		  //echo"<br>";
            //$tkt_no = $reissed_tkt_no;
        }
        else  // other tha reissued tickets
        {
			$reissed_tkt_no = substr($tkt_no[1], 0, 10);
            $tkt_no = $reissed_tkt_no;
        }

        return $tkt_no;
    }//Find find_tkt_no

    //Find find_aireline_code
    function find_aireline_code2($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        //$aireline_code = explode(';', $substring_array[1]);
        $aireline_code = explode('-', $substring_array[1]);
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
            $substring_array = explode($find,$single_tickets);
            $contactno = explode('-', $substring_array[1]);
            //echo '<pre>',print_r($substring_array,1),'</pre>';
            $contactno = substr($contactno[0], 0, 9);
            return $contactno;
        }
    }//Find Passenger Contact no

    //Find Passenger Name
    function find_pass_name($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $pass_name = explode(';', $substring_array[1]);
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
                $substring_array = explode($find,$single_tickets);
                $substring_array = explode(';', $substring_array[1]);
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
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
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
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fare = $substring_array[1];
                        return $fare;
                    }
                }

                if (strpos($single_tickets, 'KFTR') !== false)
                {
                    $fare = "-";
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


    //Find Passenger Fc Fare
    /*function find_fc_fare($find,$single_tickets)
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
                $substring_array = explode($find,$single_tickets);
                $substring_array = explode(';', $substring_array[1]);
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
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
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

    }//Find Passenger Fc Fare*/

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
            elseif (strpos($single_tickets, 'K-RK') !== false)
                    {
                        $substring_array = explode('K-RK',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                       // echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }else{
				//echo"entered";
                $substring_array = explode($find,$single_tickets);
				 //echo '<pre>',print_r($substring_array),'</pre>';
                $substring_array = explode(';', $substring_array[1]);
                $substring_array = array_filter($substring_array);
                $substring_array = array_slice($substring_array, 0);
               //echo '<pre>',print_r($substring_array,1),'</pre>';
                $fc_fare = $substring_array[0];
				$tk=substr($fc_fare,0,3);
				if($tk=='KWD'){
					$fc_fare = $substring_array[0];
				}
				else{
					$fc_fare = $substring_array[1];
				}
                return $fc_fare;
            }
			
        }
        else
        {

            if ($pos == false)
            {

                if (strpos($single_tickets, 'EMD') !== false)
                {
                    if (strpos($single_tickets, 'CV-') !== false)
                    {
                        $substring_array = explode('CV-',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[4];

                        return $fc_fare;


                    }
                }
				  elseif (strpos($single_tickets, 'K-FI') !== false)
                {
                    if (strpos($single_tickets, $find) !== false)
                    {
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }
                }else if (strpos($single_tickets, 'K-FA') !== false)
                {
					//echo"AED";
                    if (strpos($single_tickets, $find) !== false)
                    {
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }
                }
				elseif (strpos($single_tickets, 'K-FK') !== false)
                {
					//echo"AED";
                    if (strpos($single_tickets, 'K-FK') !== false)
                    {
                        $substring_array = explode('K-FK',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                         $fc_fare = $substring_array[0];
						 $fc_fare='K'.$fc_fare;
                        return $fc_fare;


                    }
                }
				elseif (strpos($single_tickets, 'K-F') !== false)
                {
					//echo"AED"; 
                    if (strpos($single_tickets, 'K-F') !== false)
                    {
                        $substring_array = explode('K-F',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                         $fc_fare = $substring_array[1];
						//echo $fc_fare='K'.$fc_fare;
                        return $fc_fare;


                    }
                }
				elseif (strpos($single_tickets, 'K-R') !== false)
                {
					//echo"K-R";
                    if (strpos($single_tickets, $find) !== false)
                    {
						//echo"1";
                        $substring_array = explode($find,$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                       // echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }
					if (strpos($single_tickets, 'K-R') !== false)
                    {
						//echo"2";
						
                        $substring_array = explode('K-R',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
						// echo '<pre>',print_r($substring_array),'</pre>';
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
						$tk=substr($substring_array[2], 0, 3);
                       // $fc_fare = $substring_array[1];
						//echo $tk;
						if($tk=='KWD'){
							$fc_fare = $substring_array[2];
						}else{
							$fc_fare = $substring_array[1];
						}
                        return $fc_fare;


                    }
                }
				
				else if (strpos($single_tickets, 'K-RK') !== false)
                    {
						//echo"3";
                        $substring_array = explode('K-RK',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }
                else if (strpos($single_tickets, 'K-') !== false)
                {

                 
					 if (strpos($single_tickets, 'KN-I') !== false)
                    {
                        
						$substring_array = explode('KN-I',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $st=substr($substring_array[0],0,3);
						if($st=='KWD'){
						$fc_fare = $substring_array[0];
						}else{
							$fc_fare = $substring_array[1];
						}
                        return $fc_fare;


                    }
					elseif (strpos($single_tickets, 'KN-S') !== false)
                    {//echo"4";
                        $substring_array = explode('KN-I',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                       // echo '<pre>',print_r($substring_array,1),'</pre>';
                         $st=substr($substring_array[0],0,3);
						if($st=='KWD'){
						$fc_fare = $substring_array[0];
						}else{
							$fc_fare = $substring_array[1];
						}
                        return $fc_fare;


                    }
					else if (strpos($single_tickets, 'KN-Y') !== false)
                    {
                        $substring_array = explode('KN-Y',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }
					elseif (strpos($single_tickets, 'KS-Y') !== false)
                    {
                        $substring_array = explode('KS-Y',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }else if (strpos($single_tickets, 'K-R') !== false)
                    {
						//echo"5";
                        $substring_array = explode('K-R',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[1];

                        return $fc_fare;


                    }elseif (strpos($single_tickets, 'K-RG') !== false)
                    {
                        $substring_array = explode('K-RG',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[2];

                        return $fc_fare;


                    }
					
					
                }
				elseif(strpos($single_tickets, 'RFD') !== false){
					$substring_array = explode('RFD',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[3];

                        return $fc_fare;
					
				}
                else
                {
					//echo"Last";
                    $fc_fare = "-";
                    return $fc_fare;
                }

            }
            else
            {
				//echo"Last1";
                $fc_fare = "-";
                return $fc_fare;
            }

        }

    }
//USED
function used($find,$single_tickets)
    { //echo"useddd";
		if(strpos($single_tickets, 'RFD') !== false){
			//echo"used";
					$substring_array = explode('RFD',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                         $fc_fare = $substring_array[4];

                        return $fc_fare;
					
				}else{
					$fc_fare = "-";
                    return $fc_fare;
				}
	}
	function vendor($find,$single_tickets)
    {
		//echo"vendor";
		if(strpos($single_tickets, 'RFD') !== false){
					/*$substring_array = explode('RFD',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        echo '<pre>',print_r($substring_array,1),'</pre>';
                         $fc_fare = $substring_array[6];*/
						  $endIndex=strpos($single_tickets,'XT');
						 $startIndex=strpos($single_tickets,'RFD');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($single_tickets,$startIndex+2,$strLength-1);
         
			$substring_array = explode(';', $finalString);
            //echo '<pre>',print_r($substring_array,1),'</pre>';
             $last_element = sizeof($substring_array)-1;
		   foreach($substring_array as $k => $val)
			{
				//echo $k;
			  if($val == 'X'){
				 $fc_fare = $substring_array[$k-3];
			  }

                        
					
			}
			return $fc_fare;}
				else{
					return 0;
				}
	}
	function refundxt($find,$single_tickets)
    {
		if(strpos($single_tickets, 'RFD') !== false){
					$substring_array = explode('XT',$single_tickets);
                        $substring_array = explode(';', $substring_array[1]);
                        $substring_array = array_filter($substring_array);
                        $substring_array = array_slice($substring_array, 0);
                        //echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[0];

                        return $fc_fare;
					
				}else{
					return 0;
				}
	}
    //Find Passenger Rate
    function find_Rate($find,$single_tickets)
    {
        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");
        if($pos>100)
        {

            $substring_array = explode($find,$single_tickets);
            $substring_array = explode(';', $substring_array[1]);
            $substring_array = array_filter($substring_array);
            $substring_array = array_slice($substring_array, 0);
            //echo '<pre>',print_r($substring_array,1),'</pre>';
            if (strpos($substring_array[2], 'KFTF') !== false)
            {
                $rate = "-";
                return $rate;
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
                    $rate = "-";
                    return $rate;
                }
                if (strpos($single_tickets, 'KSTI') !== false)
                {
                    $rate = "-";
                    return $rate;
                }
                if (strpos($single_tickets, 'KFTF') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    if (strpos($substring_array[2], 'KFTF') !== false)
                    {
                        $rate = "-";
                        return $rate;
                    }
                    else
                    {
                        $rate = $substring_array[3];
                    }
                    return $rate;
                }
            }
            else
            {
                echo "-";
            }

        }

    }//Find Passenger Fare

    //Find Passenger Net Amount
    /*function find_NetAmount($find,$single_tickets)
    {
        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");

        if($pos>100)
        {
            if(strpos($single_tickets, 'CONJUCTION') !== false)
            {
                echo "-";
            }
            else
            {
                $substring_array = explode($find,$single_tickets);
                $substring_array = explode(';', $substring_array[1]);
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
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[1];
                }
                if (strpos($single_tickets, 'KFTF') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[2];
                }
                if (strpos($single_tickets, 'KFTR') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[1];
                }

                if (strpos($NetAmount, 'KFTF') !== false)
                {
                    $NetAmount = $substring_array[1];
                }
                return $NetAmount;
            }
            else
            {
                echo "-";
            }

        }

    }//Find Passenger Net Amount*/

    function find_NetAmount($find,$single_tickets)
    {
        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");

        if($pos>100)
        {
            if(strpos($single_tickets, 'CONJUCTION') !== false)
            {
                echo "-";
            }
            else
            {
                $substring_array = explode($find,$single_tickets);
                $substring_array = explode(';', $substring_array[1]);
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
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[1];
                }
                if (strpos($single_tickets, 'KFTF') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[2];
                }
                if (strpos($single_tickets, 'KFTR') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[1];
                }
                if (strpos($single_tickets, 'KNTY') !== false)
                {
                    $substring_array = explode($find,$single_tickets);
                    $substring_array = explode(';', $substring_array[1]);
                    $substring_array = array_filter($substring_array);
                    $substring_array = array_slice($substring_array, 0);
                    //echo '<pre>',print_r($substring_array,1),'</pre>';
                    $NetAmount = $substring_array[1];
                }
                if (strpos($NetAmount, 'KFTF') !== false)
                {
                    $NetAmount = $substring_array[1];
                }
                return $NetAmount;
            }
            else
            {
                echo "-";
            }

        }

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
            $segments_all = explode('H-',$finalString);

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
        else if (strpos($single_tickets, 'D-') !== false)
        {
             $startIndex=strpos($file,'D-');
			 //echo"<br>";
             $endIndex=strpos($file,'EMD');
			 //echo"<br>";
			 $startIndex=$startIndex+22;
			 //echo"<br>";
            $strLength=$endIndex-$startIndex;
		   //echo"<br>";
             $finalString=substr($file,$startIndex,$strLength);
			 // echo"<br>";
            $segments_all = explode('U-',$finalString);
  //echo"<br>";
 // var_dump($segments_all);
            for($v=0;$v<sizeof($segments_all);$v++)
            {
                if (strpos($segments_all[$v], 'VOID') == false)
                {
                    $new_segments_all[$v]=$segments_all[$v];
                }
            }
            $new_segments_all = array_filter($new_segments_all);
          // var_dump($new_segments_all);
		   $new_segments_all = array_slice($new_segments_all, 0);
           // echo '<pre>',print_r($new_segments_all,1),'</pre>';

            return $new_segments_all;
        }{ return "1"; }
    }//Find Segment Details

    //Find Carrier Code
    function find_carrier_code($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);
        //echo '<pre>',print_r($seg_row,1),'</pre>';
        return $seg_row[0];
    }//Find Carrier Code

    //Find Flight No
    function find_flight_no($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);
        //echo '<pre>',print_r($seg_row,1),'</pre>';

        return $seg_row[1];

    }//Find Flight No

    //Find Class
    function find_class($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);

        return $seg_row[2];

    }//Find Class

    //Find Date of travel
    function find_date_of_travel($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);

        $date_of_travel = substr($seg_row[4],0,5);
        return $date_of_travel;

    }//Find Date of travel

    //Find Date of Depa
    function find_date_of_dep($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);
        $date_of_travel = substr($seg_row[4],5,4);
        return $date_of_travel;

    }//Find Date of Depa

    //Find Date of Arraive
    function find_date_of_arraive($seg_row)
    {
        $seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);
        return $seg_row[5];
    }//Find Date of Arraive

    //Find total lenght
    function find_len($single_tickets)
    {
        $file = $single_tickets;
        $total_len = explode(';',$file);
        //echo '<pre>',print_r($total_len,1),'</pre>';
        return $total_len[0];
    }//Find total lenght


    //Find All taxes
    /*function find_All_taxes($single_tickets)
    {
        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");

        if($pos>100)
        {
            if(strpos($single_tickets, 'CONJUCTION') !== false)
            {
                echo "-";
            }
            else
            {
                $all_taxes = explode('KFTF', $single_tickets);
                $all_taxes = explode(';', $all_taxes[1]);
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
                    $all_taxes = explode('KSTI', $single_tickets);
                    $all_taxes = explode(';', $all_taxes[1]);
                    //echo '<pre>',print_r($all_taxes,1),'</pre>';
                    return $all_taxes;
                }
                if (strpos($single_tickets, 'KFTF') !== false)
                {
                    $all_taxes = explode('KFTF', $single_tickets);
                    $all_taxes = explode(';', $all_taxes[1]);
                    //echo '<pre>',print_r($all_taxes,1),'</pre>';
                    return $all_taxes;
                }
                if (strpos($single_tickets, 'KFTR') !== false)
                {
                    echo "-";
                }
            }
            else
            {
                echo "-";
            }

        }

    }//Find All taxes*/

    function find_All_taxes($single_tickets)
    {
        $file = $single_tickets;

        $pos = strpos($single_tickets,"VOID");

        if($pos>100)
        {
            if(strpos($single_tickets, 'CONJUCTION') !== false)
            {
                echo "-";
            }
            else
            {
                $all_taxes = explode('KFTF', $single_tickets);
                $all_taxes = explode(';', $all_taxes[1]);
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
                    $all_taxes = explode('KSTI', $single_tickets);
                    $all_taxes = explode(';', $all_taxes[1]);
                    //echo '<pre>',print_r($all_taxes,1),'</pre>';
                    return $all_taxes;
                }
                if (strpos($single_tickets, 'KFTF') !== false)
                {
                    $all_taxes = explode('KFTF', $single_tickets);
                    $all_taxes = explode(';', $all_taxes[1]);
                    //echo '<pre>',print_r($all_taxes,1),'</pre>';
                    return $all_taxes;
                }
                if (strpos($single_tickets, 'KNTY') !== false)
                {
                    $all_taxes = explode('KNTY', $single_tickets);
                    $all_taxes = explode(';', $all_taxes[1]);
                    //echo '<pre>',print_r($all_taxes,1),'</pre>';
                    return $all_taxes;
                }

                if (strpos($single_tickets, 'KFTR') !== false)
                {
                    echo "-";
                }
            }
            else
            {
                echo "-";
            }

        }

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
	function getEmplId($code,$getLocation)
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
			$getLoc = $getLocation;
			//$this->common_model->get_field_name('EmployeeMaster','vLocation', " WHERE nEId = '".$this->session->userdata('AbsAdmin')."'");
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
	function getDocNum2($loc)
    {
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='3'");
		$this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister WHERE

                                                    vType = 'R' AND nLocation = ". $this->txtLocation." ORDER BY nRId DESC LIMIT 0,1");
		if($fetchCode->num_rows() > 0):

            $totalNum           = $fetchCode->row();

            $this->txtDocNumber = $totalNum->vDocNumber;

            $docNumber      = @split('-',$this->txtDocNumber);

            $fill         = $this->txtMinDigit;

            $number       = ($docNumber[3]+1);

            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;

        else:

            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='3'");

            $docNumber      = @split('-',$this->txtDocNumber);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];

        endif;



        return $countVal;
		
    }
	
	
	


}

//end class
?>