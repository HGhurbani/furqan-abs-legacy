<?php
include('./static/galileo/ticket_result.php');
//$date_time = $_REQUEST['date_time'];
//$file='tickets/' . $date_time . ".php";
class Galileo_ticket_insert_model extends CI_Model
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
			
			 $A11S=$this->A11S('A11S:',$All_tickets[$i]);
			 $OD=$this->OD('OD-',$All_tickets[$i]);

if($A11S=='A11S' && $OD==0){
	//echo test;
			
			 $tcktType 			= $this->find_TicketType('VOID',$All_tickets[$i]);
			$issued_date        = $this->find_dateofissue('NTD:',$All_tickets[$i],$tcktType);
			$ticket_no          = $this->find_tkt_no('A02',$All_tickets[$i],$tcktType);
			$ticket_con          = $this->find_contkt_no('A02',$All_tickets[$i],$tcktType);
			$org_ticket_no          = $this->find_org_tkt_no('TI:',$All_tickets[$i],$tcktType);
            //echo $tcktType."--".$ticket_no."<br>";
			 $pcc            = $this->find_pcc('MUC1A',$All_tickets[$i]);
            $gds_PNR            = $this->find_gdspnr($pcc,$All_tickets[$i],$tcktType);
            //$gds_PNR            = substr($gds_PNR,3,strlen($gds_PNR));
            $gds_PNR            = substr($gds_PNR,0,7);

            $iatacode           = $this->find_iatacode('MUC1A',$All_tickets[$i]);
            $air_PNR            = $this->find_airpnr('A14VL',$All_tickets[$i]);
            //$air_PNR            = substr($air_PNR,3,-2);
            //$air_PNR            = substr($air_PNR,3,-1);
            $Aircode            = $this->find_airlinecode1('T-',$All_tickets[$i]);
			
            $Aircode            = substr(trim($Aircode),0,3);


            $SignId 			= $this->find_issuedid('MUC1A',$All_tickets[$i],$tcktType);
            //$SignId 			= substr($SignId,strlen(trim($SignId))-2,strlen(trim($SignId)));
            //$SignId 			= substr($SignId,4,2);
			//echo "PCC:". $pcc."<BR>";
			if($pcc== "5UA"){
						$nAcc = '1';
                        $nAccHead = '52';
						$iss = 'ACT';//issuer
						$loc = '01';//Employee Location
						$nu = '33';//issuer id from database
						//$getLocation='01';//ticket location			
				}
				if($pcc== "6QC7"){
			
                        $nAcc = '212';
                        $nAccHead = '52';
						$iss = 'RFO';//issuer
						$loc = '02';//Location
						$nu = '34';//issuer id from database
						//$getLocation='03';//ticket location
			
				}
				if($pcc== "32AK"){
						$nAcc = '213';
						$nAccHead = '52';
						$iss = 'RCT';//issuer
						$loc = '03';//Location
						$nu = '35';//issuer id from database
						//$getLocation='02';//ticket location
				}
			
		
            $SignId				= $this->getEmplId(trim($SignId),$getLocation);
            $SignId		        = (intval($SignId) != 0 )?$SignId:0;
            $BookedId 			= $this->find_bookingid('MUC1A',$All_tickets[$i]);
            //$BookedId 	    = substr($BookedId,strlen(trim($BookedId))-2,strlen(trim($BookedId)));
            //$BookedId 			= substr($BookedId,4,2);
            $BookedId			= $this->getEmplId(trim($BookedId),$getLocation);
            $BookedId		    = (intval($BookedId) != 0 )?$BookedId:0;

           // $issued_date        = $this->find_dateofissue('NTD:',$All_tickets[$i]);
           $issued_date        = substr($issued_date,0,2)."-".substr($issued_date,2,3)."-".substr($issued_date,-2);
             $issued_date        = date('Y-m-d',strtotime($issued_date));

            $fare               ='-';
            $fare_fc            = $this->find_fc_fare('K-F',$All_tickets[$i],$tcktType);
			$fare           = $this->find_fc_fare('K-F',$All_tickets[$i],$tcktType);
            /*if (strpos($fare_fc, 'KWD') != false) {
                $fare_fc        = "-";
                $fare           = $this->find_fc_fare('K-F',$All_tickets[$i]);
            }
            else {
                $fare           = $this->find_fc_fare('K-F',$All_tickets[$i]);
            }*/
			//echo $fare."<br>";
             $base_fair			= substr($fare,0,-3);
            $fc_fair			= substr($fare_fc,0,-3);
            $rate               = $this->find_Rate('K-',$All_tickets[$i]);
            $rate               = (intval($rate) != 0 )?$rate:0;

            $cunt =1;
            $taxVal1 = $taxVal2 = $taxVal3 = 0;
            $tax1               = $this->find_newtax1('T1:',$All_tickets[$i],$tcktType);
            $tax2               = $this->find_newtax2('T2:',$All_tickets[$i],$tcktType);
            $tax3               = $this->find_newtax3('T3:',$All_tickets[$i],$tcktType);
            
			if($tcktType=='RE-ISSUED'){
$tax1=0;$tax2=0;$tax3=0;
}
             $totalTax           = $tax1+$tax2+$tax3;

            $NetAmount          = $tax1 + $tax2 + $tax3 + $base_fair;
            $pass_name          = $this->find_pass_name('A02',$All_tickets[$i]);
            $pass_contactno     = $this->find_pass_contactno('CTC PAX',$All_tickets[$i]);

			$getLocCode         = $this->common_model->get_field_name('EmployeeMaster','vLocation'," WHERE nEId = '".$SignId."'");
			
			$getLocation		= $this->common_model->get_field_name('Location','nId'," WHERE nId = '".$getLocCode."'");
			$getDivison 		= $this->common_model->get_field_name('Location','nDivision'," WHERE nId = '".$getLocCode."'");
			$getDivison			= (intval($getDivison) != 0 )?$getDivison:0;
			$getAirline 		= $this->common_model->get_field_name('Airline','nArId'," WHERE  vAirlineNo = '".$Aircode."'");
            $getAirline			= (intval($getAirline) != 0 )?$getAirline:0;
if($tcktType!='VOID'){
            $segments_all       = $this->find_SegmentDetail($All_tickets[$i]);
		//echo sizeof($segments_all);
		$Carrier1='';$Flight1='';$Class1='';$TravelDate1=''; $Sector11='';$Sector12='';$status1='';$time_of_dep1='';$time_of_arraive1='';
						$Carrier2='';$Flight2='';$Class2='';$TravelDate2=''; $Sector21='';$Sector22='';$status2='';$time_of_dep2='';$time_of_arraive2='';
						$Carrier3='';$Flight3='';$Class3='';$TravelDate3=''; $Sector31='';$Sector32='';$status3='';$time_of_dep3='';$time_of_arraive3='';
						$Carrier4='';$Flight4='';$Class4='';$TravelDate4=''; $Sector41='';$Sector42='';$status4='';$time_of_dep4='';$time_of_arraive4='';
			$seg=sizeof($segments_all);
			
            for($j=0;$j<sizeof($segments_all);$j++)
            {
				
                $single_segments[$j] = explode(' ',$segments_all[$j]);
				//echo '<pre>',print_r($single_segments[$j]),'</pre>';
                if($j==0)
                {
                    $Carrier1           = $this->find_carrier_code($single_segments[$j][0]);
                    $Flight1            = $this->find_flight_no($segments_all[$j]);
                    $Class1             = $this->find_class($segments_all[$j]);
                    $TravelDate1        = $this->find_date_of_travel($segments_all[$j]);
                    $TravelDate1        = date('Y-m-d',strtotime($TravelDate1));
					$time_of_dep1       = $this->find_date_of_dep($segments_all[$j]);
                    $time_of_dep1       = substr($time_of_dep1,0,2).":".substr($time_of_dep1,2,2);
                    $time_of_arraive  = $this->find_date_of_arraive($segments_all[$j]);
                   
				   $time_of_arraive1   = substr($time_of_arraive,0,2).":".substr($time_of_arraive,2,2);
//echo $ticket_no.$tcktType." TOA:".$time_of_arraive."<br>";
				   $Sector111           =  explode($time_of_arraive,$segments_all[$j]);//$single_segments[$j][5];
                   // echo '<pre>',print_r($Sector111),'</pre>';
					//echo $single_segments[$j][4];
					 $Sector11           = substr($Sector111[1],2,3);
                   // $Sector12           = $single_segments[$j][12];
					  $Sector12           = substr($Sector111[1],18,3);
                    $status1            = $single_segments[$j][3];
                    $status1            = substr($status1,0,2);
                    
                }
                elseif($j==1)
                {
                    $Carrier2           = $this->find_carrier_code($single_segments[$j][0]);
                    $Flight2            = $this->find_flight_no($segments_all[$j]);
                    $Class2             = $this->find_class($segments_all[$j]);
                    $TravelDate2        = $this->find_date_of_travel($segments_all[$j]);
                    $TravelDate2        = date('Y-m-d',strtotime($TravelDate2));
                    //$Sector21           = $single_segments[$j][5];
                    //$Sector21           = substr($Sector21,1,3);
                    //$Sector22           = $single_segments[$j][12];
					 //$Sector22           = substr($Sector22,0,3);
					 $time_of_arraiv  = $this->find_date_of_arraive($segments_all[$j]);
                    $time_of_arraive2   = substr($time_of_arraiv,0,2).":".substr($time_of_arraiv,2,2);
                    $Sector211           =  explode($time_of_arraiv,$segments_all[$j]);//$single_segments[$j][5];
					// $Sector211           =  explode($single_segments[$j][4],$segments_all[$j]);//$single_segments[$j][5];
                    //echo '<pre>',print_r($Sector11[1]),'</pre>';
					
					 $Sector21           = substr($Sector211[1],2,3);
                   // $Sector12           = $single_segments[$j][12];
					  $Sector22           = substr($Sector211[1],18,3);
                    $status2            = $single_segments[$j][3];
                    $status2            = substr($status2,0,2);
                    $time_of_dep2       = $this->find_date_of_dep($segments_all[$j]);
                    $time_of_dep2       = substr($time_of_dep2,0,2).":".substr($time_of_dep2,2,2);
                }
                elseif($j==2)
                {
                   $Carrier3           = $this->find_carrier_code($single_segments[$j][0]);
                    $Flight3            = $this->find_flight_no($segments_all[$j]);
                    $Class3             = $this->find_class($segments_all[$j]);
                    $TravelDate3        = $this->find_date_of_travel($segments_all[$j]);
                    $TravelDate3        = date('Y-m-d',strtotime($TravelDate2));
                    //$Sector21           = $single_segments[$j][5];
                    //$Sector21           = substr($Sector21,1,3);
                    //$Sector22           = $single_segments[$j][12];
					 //$Sector22           = substr($Sector22,0,3);
					 $time_of_arrai  = $this->find_date_of_arraive($segments_all[$j]);
                    $time_of_arraive3  = substr($time_of_arrai,0,2).":".substr($time_of_arrai,2,2);
                    $Sector311           =  explode($time_of_arrai,$segments_all[$j]);//$single_segments[$j][5];
					 //$Sector311           =  explode($single_segments[$j][4],$segments_all[$j]);//$single_segments[$j][5];
                    //echo '<pre>',print_r($Sector11[1]),'</pre>';
					
					 $Sector31           = substr($Sector311[1],2,3);
                   // $Sector12           = $single_segments[$j][12];
					  $Sector32           = substr($Sector311[1],18,3);
                    $status3            = $single_segments[$j][3];
                    $status3            = substr($status3,0,2);
                    $time_of_dep3       = $this->find_date_of_dep($segments_all[$j]);
                    $time_of_dep3       = substr($time_of_dep3,0,2).":".substr($time_of_dep3,2,2);
                   // $time_of_arraive3   = $this->find_date_of_arraive($segments_all[$j]);
                    //$time_of_arraive3   = substr($time_of_arraive3,0,2).":".substr($time_of_arraive3,2,2);
                }
                elseif($j==3) 
                {
                    $Carrier4           = $this->find_carrier_code($single_segments[$j][0]);
                    $Flight4            = $this->find_flight_no($segments_all[$j]);
                    $Class4             = $this->find_class($segments_all[$j]);
                    $TravelDate4        = $this->find_date_of_travel($segments_all[$j]);
                    $TravelDate4        = date('Y-m-d',strtotime($TravelDate4));
                    /*$Sector41           = $single_segments[$j][5];
                    $Sector41           = substr($Sector41,1,3);
                    $Sector42           = $single_segments[$j][12];
					 $Sector42           = substr($Sector42,0,3);*/
					 $time_of_arra  = $this->find_date_of_arraive($segments_all[$j]);
                    $time_of_arraive1   = substr($time_of_arra,0,2).":".substr($time_of_arra,2,2);
                    $Sector411           =  explode($time_of_arra,$segments_all[$j]);//$single_segments[$j][5];
					// $Sector411           =  explode($single_segments[$j][4],$segments_all[$j]);//$single_segments[$j][5];
                    //echo '<pre>',print_r($Sector11[1]),'</pre>';
					
					 $Sector41           = substr($Sector411[1],2,3);
                   // $Sector12           = $single_segments[$j][12];
					  $Sector42           = substr($Sector411[1],18,3);
                    $status4            = $single_segments[$j][3];
                    $status4            = substr($status4,0,2);
                    $time_of_dep4       = $this->find_date_of_dep($segments_all[$j]);
                    $time_of_dep4       = substr($time_of_dep4,0,2).":".substr($time_of_dep4,2,2);
                    //$time_of_arraive4   = $this->find_date_of_arraive($segments_all[$j]);
                    //$time_of_arraive4   = substr($time_of_arraive4,0,2).":".substr($time_of_arraive4,2,2);
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
						  $ticket_n    =  $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual= '".$ticket_no. "'" );               
						 //echo"test".  $ticket_no."<br>";        
						$data   = array( 'vType' => $status,'source'=>'File');                
						$this->db->where('nTId',$ticket_n);                
						$this->db->update('{PRE}TicketBooking', $data);                 
						/* echo"<pre>";
										var_dump($data);
										echo"</pre>";   */         
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
						$this->db->where('nTicketId',$ticket_n);                
						$this->db->update('{PRE}TicketFareDetails', $data2);									
						}//end if								
       
							
            $tc =$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE  vTicktManual = '".substr($ticket_no,0,10)."'");
           
		  if($tcktType != 'Refund' && is_numeric($ticket_no))
            {
				//echo $tcktType."---".$ticket_no."<br>";
				$inType = (substr($tcktType,0,1) == 'O')?'N':substr($tcktType,0,1);
				
				if($inType == 'V')
				{
					$tax1 = $tax2 = $tax3 = $base_fair = $tot_fair = 0;										
					$issued_date= date('Y-m-d');
				}//end if
					date_default_timezone_set('Asia/Kuwait');
				//if((substr($tcktType,0,1) != 'R')||($tcktType=='RE-ISSUED')):
				if($tc!=substr($ticket_no,0,10)):
					//insert into ticket booking master table
					$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
										'nDivision' => $getDivison,
										'nLocation' => $getLocation,
                                        'dDate' => $issued_date,
										'nAirline' => $getAirline,
										'nGDS' => '3',
										'vType' => $inType,
										'vIssuedBy' => $SignId,
										'vBookedBy' => $BookedId,
										'vPassenger' => $pass_name,
										'vGdspnr' => $gds_PNR,'vAirpnr' => $air_PNR,
										'nCurrency' => '5',
										'nRate' => $rate,
										'vConjStatus' => 'Yes',
										'vDocNumber' => $this->getAutomaticDocNumber2(),
										'vTicktManual' => substr($ticket_no,0,10),
										'vOriginalTkt' => $org_ticket_no,
										'source'=>'File',
										'edate' => date('Y-m-d H:i:s'),
				  'eid' =>$this->session->userdata('AbsAdmin'));
										echo"<pre>";
										var_dump($data);
										echo"</pre>";
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
						'vFaceValue' => $base_fair);
					$this->db->insert('{PRE}TicketFareDetails', $data);

					//insert into Ticket Billing Details table
					$data 	= array('nTicketId' => $id ,
										'vSalesAmt' => '0',
										'vCollected' => '0');
					$this->db->insert('{PRE}TicketBillingDetails', $data);
					if($ticket_con==02){
						$inType='C';
						$ticket_no==substr($ticket_no,0,10);
						$ticket_no=$ticket_no+1;
							//insert into ticket booking master table
					$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
										'nDivision' => $getDivison,
										'nLocation' => $getLocation,
                                        'dDate' => $issued_date,
										'nAirline' => $getAirline,
										'nGDS' => '3',
										'vType' => $inType,
										'vIssuedBy' => $SignId,
										'vBookedBy' => $BookedId,
										'vPassenger' => $pass_name,
										'vGdspnr' => $gds_PNR,'vAirpnr' => $air_PNR,
										'nCurrency' => '5',
										'nRate' => $rate,
										'vConjStatus' => 'Yes',
										'vDocNumber' => $this->getAutomaticDocNumber2(),
										'vTicktManual' => $ticket_no,
										'vOriginalTkt' => $org_ticket_no);
										echo"<pre>";
										var_dump($data);
										echo"</pre>";
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

					//insert into Ticket Fare Details table
					$data 	= array('nTicketId' => $id ,'vFare' => 'N',
						'vTax1' => 0,
						'vTax2' => 0,
						'vTax3' => 0,
						'vTaxTotal' => 0,
						'nNetVendor' => 0,
						'nAcc' => $nAcc,
						'nAccHead' => $nAccHead,
						'vFCFare' => 0,
						'vPassengerContact' => $pass_contactno,
						'vFaceValue' => 0);
					$this->db->insert('{PRE}TicketFareDetails', $data);

					//insert into Ticket Billing Details table
					$data 	= array('nTicketId' => $id ,
										'vSalesAmt' => '0',
										'vCollected' => '0');
					$this->db->insert('{PRE}TicketBillingDetails', $data);
					}
					
					$cnt++;
				endif;
            }
            else
            {		//echo $tcktType;										
                if($tcktType == 'Refund'):

                   // echo "test";
					$tid       = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."' ");
				 $rfndValue = $this->db->query("SELECT rd.nTktNum FROM {PRE}RefundRegister r

														LEFT JOIN {PRE}RefundRegisterDetails rd

															ON r.nRId = rd.nRefundId

														WHERE rd.nTktNum = '".$tid."'");
				$rfndValue1 = $this->db->query("SELECT rd.nTktNum FROM {PRE}RefundRegister r

														LEFT JOIN {PRE}RefundRegisterDetails rd

															ON r.nRId = rd.nRefundId

														WHERE rd.nTktNum = '".$ticket_no."'");
				echo $rfndValue->num_rows()."--". $rfndValue1->num_rows();
				if($rfndValue->num_rows() == 0 && $rfndValue1->num_rows()==0):



						//insert into refund table

						$data = array('nDivision' => $getDivison, 'nLocation' => $getLocation,

									  'dDate' => $issued_date,'nGDS' => '3',

									  'vType' => substr($tcktType,0,1),'vPassenger' => $pass_name,

									  'nCurrency' => '5','nAccount' => $nAcc,

									  'vDocNumber' => $this->getDocNum2($getLocation),

									  'vRefundDet' => $issued_date,

									  'vActive' => '1',
									  'source'=>'File');
						echo"<pre>".print_r($data)."</pre>";
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
						echo"<pre>".print_r($data1)."</pre>";
						$this->db->insert('{PRE}RefundRegisterBilling', $data1);



						//insert into refund vendor details table
/*
						$data1 = array('nRefundId' => $id, 'vFareAmt' => $base_fair,

										'vTax' => $totalTax,

										'vIssuedBy' => $SignId, 'vNetAmt1' => $NetAmount);

						$this->db->insert('{PRE}RefundVendorDetails', $data1);*/



					    //insert into RefundRegister Details table
						$tid       = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$ticket_no."' ");
						$used=0;
						$refundxt=$totalTax;
						$vendor=0;
						$samt       = $this->common_model->get_field_name('SalesClosingDetails','vSlsAmt'," WHERE vTktNumber = '".$tid."' ");
						$vServAmt       = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE vTktNumber = '".$tid."' ");
						$netAmt=$fc_fair+$refundxt;
						$netRef=$fc_fair-$used-$vendor+$refundxt;
						$refundxt=$totalTax;
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
						echo"<pre>".print_r($data2)."</pre>";
						$this->db->insert('{PRE}RefundRegisterDetails', $data2);

                        $cnt++;
                    endif;
                endif;								
            }//end else
}
        }//end for
		echo $cnt.' record is inserted successfully.';
    }//end function

	function A11S($find,$single_tickets)
    {
        $pos = strpos($single_tickets,$find);

        //echo $pos;
     
		if(strpos($single_tickets,'000000000000 KWD00000000  00000000  00000000  00000000  00000000  000000000000')!== false){
			$tkt_status = "0";
		}elseif(strpos($single_tickets, 'A11') !== false)
        {
            $tkt_status = "A11S";
        }
        else
		{
		  $tkt_status = "0";	
		}
		return $tkt_status;
	}
	function OD($find,$single_tickets)
    {
        $pos = strpos($single_tickets,$find);

        //echo $pos;
        if(strpos($single_tickets, 'OD-') !== false)
        {
            $tkt_status = "1";
        }
        else
		{
		  $tkt_status = "0";	
		}
		return $tkt_status;
	}
    //Find TicketType
    function find_TicketType($find,$single_tickets)
    {
        $pos = strpos($single_tickets,$find);

        //echo "hello";//$pos;
        if(strpos($single_tickets, 'CONJUCTION') !== false)
        {
            $tkt_status = "CONJUCTION";
        }
        else
        {
            if (strpos($single_tickets, 'TI:') !== false)
            {
                $tkt_status = "RE-ISSUED";
            } 
			elseif (strpos($single_tickets, 'ZZZZZZ') !== false)
            {
				if (strpos($single_tickets, 'Z6Q') !== false){
					 $tkt_status = "Refund";
				}
				elseif (strpos($single_tickets, 'Z5U') !== false){
					 $tkt_status = "Refund";
				}
				elseif (strpos($single_tickets, 'Z32') !== false){
					 $tkt_status = "Refund";
				}
                else{
					$tkt_status = "VOID";
				}
            }
            else
            {
                $tkt_status = "NORMAL";
            }
        }
 //echo $tkt_status;
        return $tkt_status;
    }//Find TicketType

    //Find Gds Pnr
    function find_gdspnr($find,$single_tickets,$tcktType)
    {
       if($tcktType!='VOID'){
		$file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
		if($substring_array[2]!=''){
		$substring_array = explode(' ',$substring_array[2]);   
		}else{
		$substring_array[1]=0;	
		}
	   } 
       
		//echo '<pre>',print_r($substring_array),'</pre>';
        return $substring_array[1];
    }//Find Gds Pnr

    //Find Iata code
    function find_iatacode($find,$single_tickets)
    {
        /*$file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
        $iata_code = explode(';', $substring_array[1]);
        return $iata_code[3];*/
		$file = $single_tickets;
        $substring_array = explode(' ',$single_tickets);
       
		//echo '<pre>',print_r($substring_array),'</pre>';
      
		//echo $substring_array[13];
		  return $substring_array[13];
    }//Find Iata code

    //Find AIR PNR
    function find_airpnr($find,$single_tickets)
    {
        $file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
		//echo '<pre>',print_r($substring_array),'</pre>';
       /* $air_pnr = explode(';', $substring_array[1]);
        $air_pnr = array_filter($air_pnr);
        $air_pnr = array_slice($air_pnr, 0);
        $air_pnr = explode('-',$air_pnr[10]);*/
		 $air_pnr=$substring_array[1];
		  $air_pnr=str_replace(' ','',$air_pnr);
		 $air_pnr=substr($air_pnr,1,22);
		 $air_pnr=substr($air_pnr,-5);
        return $air_pnr;
    }//Find AIR PNR
	
	//Find AIR PNR
    function find_pcc($find,$single_tickets)
    {
       
        $file = $single_tickets;
       /* $substring_array = explode('DDB',$single_tickets);
       $substring_array=str_replace('  ',' ',$substring_array[1]);
	   $substring_array = explode(' ',$substring_array);
		//echo '<pre>',print_r($substring_array),'</pre>';
      //$pcc=$substring_array[0];
	  $pcc = preg_replace('/\s+/', '', $substring_array[0]);
	  if(substr($pcc,0,3)=='5UA'){
		  $pcc='5UA';
	  }else{
		 $pcc=substr($pcc,0,4);  
	  }
		//echo $substring_array[1];
		  return $pcc;
   */ 
   if(strpos($single_tickets, '5UA') !== false)
            {
                $pcc='5UA';
                return $pcc;
            }elseif(strpos($single_tickets, '32AK') !== false)
            {
                $pcc='32AK';
                return $pcc;
            }else{
				$pcc='6QC7';
				return $pcc;
			}
   }//Find AIR PNR



    //Find AIRLINE CODE
	
	
    function find_airlinecode1($find,$single_tickets)
    {
        $file = $single_tickets;
        //echo '<pre>',print_r($bookingid,1),'</pre>';
		$substring_array = explode(' ',$file);
		//echo '<pre>',print_r($substring_array[1]),'</pre>';
      /*  $startIndex=strrpos($file,'A-');
        if(!empty($startIndex))
        {
            $endIndex=strpos($file,'B-');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(' ',$finalString);
            //echo '<pre>',print_r($substring_array,1),'</pre>';
            $last_element = sizeof($substring_array)-1;

            return $substring_array[$last_element];
        }else{return "-";}*/
		 $air=substr($substring_array[1],2,3);
		 return $air;
    }//Find AIRLINE CODE


    //Find BOOKING ID
    function find_bookingid($find,$single_tickets)
    {
		$file = $single_tickets;
		$substring_array = explode(' ',$file);
		$substring_array = explode('KWD',$file);
			
			//$substring_array = str_replace(' ','',$substring_array[0]);
			
		//$substring_array = explode(' ',$substring_array[0]);
		
		$substring_array = preg_replace('/\s+/', '', $substring_array[0]);	
		//$substring_array = str_replace(' ','',$substring_array[0]);
		  $substring_array = substr($substring_array,-27);
		 $substring_array = substr($substring_array,0,2);
	
		return $substring_array;
	   /*$file = $single_tickets;
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
        }else{return "-";}*/
    }//Find BOOKING ID

    //Find ISSUED ID
    function find_issuedid($find,$single_tickets,$typ)
    {
        $file = $single_tickets;
		$substring_array = explode(' ',$file);
		$substring_array = explode('KWD',$file);
			
			//$substring_array = str_replace(' ','',$substring_array[0]);
			
		//$substring_array = explode(' ',$substring_array[0]);
		
			
		$substring_array = preg_replace('/\s+/', '', $substring_array[0]);
		//echo '<pre>',print_r($substring_array),'</pre>';
		if($typ=='Refund'){
			$substring_array = substr($substring_array,-10);
		   $substring_array = substr($substring_array,0,2);
		} elseif($typ=='VOID'){
			//echo '<pre>',print_r($substring_array),'</pre>';
			$substring_array = substr($substring_array,-10);
		   $substring_array = substr($substring_array,0,2);
		}
		 else{
			 $substring_array = substr($substring_array,-24);
		  $substring_array = substr($substring_array,0,2);
		 }
	
        /*$startIndex=strrpos($file,'C-');
        if(!empty($startIndex))
        {
            $endIndex=strpos($file,'D-');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex+2,$strLength-1);
            $substring_array = explode(' ',$finalString);
            $substring_array = explode('-',$substring_array[1]);
            return $substring_array[1];
        }else{return "-";}*/
		return $substring_array;
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

    function find_dateofissue($find,$single_tickets,$typ)
    {
        $file = $single_tickets;
        echo $typ;
		if($typ=='Refund'){
			//echo '<pre>',print_r($test,1),'</pre>';
		 $substring_array = explode($find,$file);
		 //echo '<pre>',print_r($substring_array),'</pre>';
          $dateofissue = substr($substring_array[0], 0, 37);
		 echo"<br>". $dateofissue =substr($dateofissue, -7);
		}else{
			echo '<pre>',print_r($test,1),'</pre>';
		 $substring_array = explode($find,$file);
		// echo '<pre>',print_r($substring_array),'</pre>';
        $dateofissue = substr($substring_array[1], 0, 7);
		}
		//echo $dateofissue;
            return $dateofissue;
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
                echo '<pre>',print_r($substring_array,1),'</pre>';
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
                       // echo '<pre>',print_r($substring_array,1),'</pre>';
                        $fc_fare = $substring_array[0];

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
//FIND ORG TICKET NO
function find_org_tkt_no($find,$single_tickets,$typ){
$substring_array = explode($find,$single_tickets);
if($typ=='RE-ISSUED'){
//echo '<pre>',print_r($substring_array[1]),'</pre>';
 $org=substr($substring_array[1],3,10);
}
else{
$org=0;
}
return $org;

}
function find_contkt_no($find,$single_tickets,$typ){
	
	$file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
		
        $tkt_no = explode('KWD', $substring_array[1]);
		
       $tkt_no = explode(':', $substring_array[1]);
	
		$tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
      // echo $tkt_no ."<br>";
	

if($typ=='RE-ISSUED'){

		//echo $tkt_no;
			   $reissed_tkt_no = substr($tkt_no,-23);
               $tkt_no =substr($reissed_tkt_no,0,12);
				$tkt_no =substr($tkt_no,-2);
	}elseif($typ=='VOID'){

		//echo $tkt_no ."<br>";
			   $reissed_tkt_no = substr($tkt_no,-18);
               $tkt_no =substr($reissed_tkt_no,0,12);
			   $tkt_no =substr($tkt_no,-2);
	}
	else{
		    $reissed_tkt_no = substr($tkt_no,-20);
			$tkt_no =substr($reissed_tkt_no,0,12);
			 $tkt_no =substr($tkt_no,-2);
	}
       //$tkt_no=0;

        return $tkt_no;
	
}
    //Find find_tkt_no
    function find_tkt_no($find,$single_tickets,$typ)
    {
	$file = $single_tickets;
        $substring_array = explode($find,$single_tickets);
		//echo '<pre>',print_r($substring_array),'</pre>';
		 $reissed_tkt_no = substr($substring_array[1],0,58);
		
		//echo $reissed_tkt_no."<br>";
		$reissed_tkt_no = substr($reissed_tkt_no,-13);
		//echo $reissed_tkt_no."<br>";
			$tkt_no =substr($reissed_tkt_no,0,10);
        /*$tkt_no = explode('KWD', $substring_array[1]);
		
       $tkt_no = explode(':', $substring_array[1]);
	
		$tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
      // echo $tkt_no ."<br>";
	
	if($typ=='VOID'){

			//echo $tkt_no ."<br>";
				   $reissed_tkt_no = substr($tkt_no,-18);
				   $tkt_no =substr($reissed_tkt_no,0,10);
		}
	if($typ=='Refund'){

		//echo $tkt_no ."<br>";
			   $reissed_tkt_no = substr($tkt_no,-27);
               $tkt_no =substr($reissed_tkt_no,0,10);
	}
if($typ=='RE-ISSUED'){

		//echo $tkt_no."<br>";
		$reissed_tkt_no = substr($tkt_no,-23);
			 
                $tkt_no =substr($reissed_tkt_no,0,10);
			     
	}else{
		   $reissed_tkt_no = substr($tkt_no,-20);
			$tkt_no =substr($reissed_tkt_no,0,10);
	}
	
	echo $reissed_tkt_no."--".$tkt_no."--".$typ."<br>";
       //$tkt_no=0;*/

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
        //$pass_name = explode(';', $substring_array[1]);
       
		$pass_name = explode(' ', $substring_array[1]);
         $pass_name = $pass_name[0];
		 //echo '<pre>',print_r($pass_name,1),'</pre>';
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

    function find_fc_fare($find,$single_tickets,$typ)
    {
        $file = $single_tickets;
if($typ=='RE-ISSUED'){
$substring_array1 = explode('A11S', $single_tickets);

 $substring_array1 = explode(' ', ltrim($substring_array1[1]));
//echo '<pre>',print_r($substring_array1),'</pre>';
//$tkt_no = preg_replace('/\s+/', '', $substring_array1[1]);
		
			   $fare = substr($substring_array1[0],0,strlen($substring_array1[0])-1);
                $fare =$fare." KWD";
return $fare;
}elseif($typ=='Refund'){
$find='A07';
		$substring_array = explode($find,$single_tickets);
		$substring_array = explode('KWD',$substring_array[1]);
		 //echo '<pre>',print_r($substring_array),'</pre>';
		 //echo (strlen($substring_array[0]));
		if(strlen($substring_array[0])==2){
			  $fare=str_replace(" ","",$substring_array[1])." KWD";
			return $fare;
		}else{
			//echo "Fare:".str_replace(" ","",$substring_array[2])."<br>";
			 $fare=str_replace(" ","",$substring_array[2])." KWD";
				return $fare;
		}
		//echo $fare;
}
else{
		$substring_array1 = explode('A02', $single_tickets);
		
		//$tkt_no = explode('KWD', $substring_array1[1]);
		
       $tkt_no = explode(':', $substring_array1[1]);
	
		$tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
		
			   $reissed_tkt_no = substr($tkt_no,-5);
               $no =substr($reissed_tkt_no,0,2);
		
		
		$find='A07'.$no;
		$substring_array = explode($find,$single_tickets);
		$substring_array = explode('KWD',$substring_array[1]);
		 //echo '<pre>',print_r($substring_array),'</pre>';
		if(strlen($substring_array[0])==''){
			 $fare=str_replace(" ","",$substring_array[1])." KWD";
			return $fare;
		}else{
			//echo "Fare:".str_replace(" ","",$substring_array[2])."<br>";
			 $fare=str_replace(" ","",$substring_array[2])." KWD";
				return $fare;
		}
}
		
        

    }
	function find_newtax1($find,$single_tickets,$typ)
    {
        $file = $single_tickets;
		$substring_array1 = explode('A02', $single_tickets);
		
		
       $tkt_no = explode(':', $substring_array1[1]);
	
		 $tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
		if($typ=='Refund'){
			
			   $find1='A07';
			   $substring_ar = explode($find1, $single_tickets);
			
		}else{
			$reissed_tkt_no = substr($tkt_no,-5);
               $no =substr($reissed_tkt_no,0,2);
			   $find1='A07'.$no;
			   $substring_ar = explode($find1, $single_tickets);
		}
			   
			  // echo '<pre>',print_r($substring_ar),'</pre>';
			   
		$find='T1:';
		$substring_array = explode($find,$substring_ar[1]);
		$substring_array = explode(' ',$substring_array[1]);
		 //echo '<pre>',print_r($substring_array),'</pre>';
		 
		  $tax1=str_replace(" ","",$substring_array[3]);
		  $tax1=substr($tax1,0,-5);
		 // echo $tax1;
		 return $tax1;
	    }
		function find_newtax2($find,$single_tickets,$typ)
    {
		$substring_array1 = explode('A02', $single_tickets);
		
		
       $tkt_no = explode(':', $substring_array1[1]);
	
		$tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
		
			if($typ=='Refund'){
			
			   $find1='A07';
			   $substring_ar = explode($find1, $single_tickets);
			
		}else{
			$reissed_tkt_no = substr($tkt_no,-5);
               $no =substr($reissed_tkt_no,0,2);
			   $find1='A07'.$no;
			   $substring_ar = explode($find1, $single_tickets);
		}
        $file = $single_tickets;
		$find='T2:';
		$substring_array = explode($find,$substring_ar[1]);
		$substring_array = explode(' ',$substring_array[1]);
		 //echo '<pre>',print_r($substring_array),'</pre>';
		 
		 $tax1=str_replace(" ","",$substring_array[3]);
		  $tax1=substr($tax1,0,-5);
		 return $tax1;
	    }
		function find_newtax3($find,$single_tickets,$typ)
    {
		$substring_array1 = explode('A02', $single_tickets);
		
		
       $tkt_no = explode(':', $substring_array1[1]);
	
		$tkt_no = preg_replace('/\s+/', '', $tkt_no[0]);
		
			if($typ=='Refund'){
			
			   $find1='A07';
			   $substring_ar = explode($find1, $single_tickets);
			
		}else{
			$reissed_tkt_no = substr($tkt_no,-5);
               $no =substr($reissed_tkt_no,0,2);
			   $find1='A07'.$no;
			   $substring_ar = explode($find1, $single_tickets);
		}
        $file = $single_tickets;
		$find='T3:';
		$substring_array = explode($find,$substring_ar[1]);
		$substring_array = explode('XT',$substring_array[1]);
		 // echo '<pre>',print_r($substring_array),'</pre>';
		 
		 $tax1=str_replace(" ","",$substring_array[0]);
		 
		 return $tax1;
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
        if (strpos($single_tickets, 'A04') !== false)
        {
            $startIndex=strpos($file,'A04');
            $endIndex=strpos($file,'A07');
            $strLength=$endIndex-$startIndex;
            $finalString=substr($file,$startIndex,$strLength);
			$finalString= str_replace('A05','A04',$finalString);
			$finalString= str_replace('A06','A04',$finalString);
             $segments_all = explode('A04',$finalString);
			//echo '<pre>',print_r($segments_all),'</pre>';
            for($v=0;$v<sizeof($segments_all);$v++)
            {
                if (strpos($segments_all[$v], 'VOID') == false)
                {
                    $new_segments_all[$v]=$segments_all[$v];
                }
            }
            $new_segments_all = array_filter($new_segments_all);
            $new_segments_all = array_slice($new_segments_all, 0);
            //echo '<pre>',print_r($new_segments_all),'</pre>';

            return $new_segments_all;
        }
        else { return "1"; }
    }//Find Segment Details

    //Find Carrier Code
    function find_carrier_code($seg_row)
    {
	//echo '<pre>',print_r($seg_row),'</pre>';	
        //$seg_row = explode('',$seg_row);
		
        //$seg_row = array_filter($seg_row);
        //$seg_row = array_slice($seg_row, 0);
	  $seg_row=substr($seg_row,2,2);
       
        return $seg_row;
    }//Find Carrier Code

    //Find Flight No
    function find_flight_no($seg_row)
    {
        //$seg_row = explode(' ',$seg_row);
       // echo '<pre>',print_r($seg_row),'</pre>';
        //$seg_row = array_filter($seg_row);
        // $seg_row = array_slice($seg_row, 0);
		 $seg_row=substr($seg_row,19,4);
		//echo $seg_row;
        return $seg_row;

    }//Find Flight No

    //Find Class
    function find_class($seg_row)
    {
        /*$seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
         $seg_row = array_slice($seg_row, 0);
		$seg_row=substr($seg_row[0],-1);*/
		//$seg_row = explode(' ',$seg_row);
        //echo '<pre>',print_r($seg_row),'</pre>';
        //$seg_row = array_filter($seg_row);
        // $seg_row = array_slice($seg_row, 0);
		 $seg_row=substr($seg_row,20,4);
		 $seg_row=substr($seg_row,-1);
        return $seg_row;

    }//Find Class

    //Find Date of travel
    function find_date_of_travel($seg_row)
    {
        //$seg_row = explode(' ',$seg_row);
		//echo '<pre>',print_r($seg_row),'</pre>';
        //$seg_row = array_filter($seg_row);
        //$seg_row = array_slice($seg_row, 0);

         $date_of_travel = substr($seg_row,27,5);
        return $date_of_travel;

    }//Find Date of travel

    //Find Date of Depa
    function find_date_of_dep($seg_row)
    {
        /*$seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);*/
         $date_of_travel = substr($seg_row,32,4);
        return $date_of_travel;

    }//Find Date of Depa

    //Find Date of Arraive
    function find_date_of_arraive($seg_row)
    {
        /*$seg_row = explode(' ',$seg_row);
        $seg_row = array_filter($seg_row);
        $seg_row = array_slice($seg_row, 0);
        return $seg_row[0];*/
		  $date_of_travel = substr($seg_row,37,4);
		  //echo "DOA:".$date_of_travel."<br>";
		  if($date_of_travel==''){
			  $date_of_travel=0;
		  }
        return $date_of_travel;
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

                                                    vType = 'R' AND nLocation = ". $loc." ORDER BY nRId DESC LIMIT 0,1");
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
	
	/*$files = scandir("static/amadeous/tickets/");

    // Identify directories
    $source = "static/amadeous/tickets/";
    $destination = "static/amadeous/backup/";

    // Cycle through all source files
    foreach ( $files as $file ) {
        if (in_array($file, array(".",".."))) continue;

        // If we copied this successfully, mark it for deletion
        if (copy($source.$file, $destination.$file)) {
            //$delete[] = $source.$file;
        }
    }
    // Delete all successfully-copied files
   
foreach (glob("./static/amadeous/tickets/*.php") as $filename)
{
  
  //copy($filename, "backup/".$filename);
  unlink($filename);
}

*/
}

//end class
?>