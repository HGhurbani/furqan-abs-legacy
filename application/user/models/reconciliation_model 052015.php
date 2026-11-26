<?php
class Reconciliation_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		ini_set('memory_limit','250M');
        }

    function getActiveDivision()
    {
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1'  AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vDivCode).')';
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return ($locGroupRow);
    }//end function

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function filterLocation($division)
    {
											//".$this->common_model->getUserLoc()."
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' 
										 AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return($locGroupRow);
    }//end function

    function getAirlineReport()
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vTktNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vTktNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC)";
                session_start();
                $_SESSION['sess_query'] = $adminGroupListQuery;
                $adminGroupList 		= $this->db->query($adminGroupListQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
				
        return ($adminGroupRow);
    }//end function

    function getGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vTktNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,td.vDestination,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC)";
        session_start();
                $_SESSION['sess_query'] = $adminGroupListQuery;
                $adminGroupList 		= $this->db->query($adminGroupListQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
        return ($adminGroupRow);
    }//end function

    function getAllReconciliation($extraCheck)
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlCreditvoucherQuery  = "SELECT * FROM {PRE}Reconciliation ".$extraCheck." ORDER BY nId ASC";
        $adminGroupList 		= $this->db->query($sqlCreditvoucherQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getAllUnMatchReconciliation($extraCheck)
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlCreditvoucherQuery  = "SELECT * FROM {PRE}Reconciliation WHERE vStatus='N' ".$extraCheck." ORDER BY nId ASC";
        $adminGroupList 		= $this->db->query($sqlCreditvoucherQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getAllMatchedReconciliation($extraCheck)
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlCreditvoucherQuery  = "SELECT * FROM {PRE}Reconciliation WHERE vStatus='M' ".$extraCheck." ORDER BY nId ASC";
        $adminGroupList 		= $this->db->query($sqlCreditvoucherQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

	//pdf to text conversion
	function getPDFText($name,$fromDt,$toDt)
	{

		include('./static/pdf_text/pdfReader.php');
		$path = $name;

		$pdf = new pdf($path);
		
		$pages = $pdf->get_pages();
		
		while( list($nr,$page) = each($pages) )
		{
			list($width,$height) = $page->get_dimensions();
			$text = $page->get_text();
			$this->getArrayVal($text,$fromDt,$toDt);
		}//end while
	}//end function
	
	//function for extract array
	function getArrayVal($arrVal,$fromDt,$toDt)
	{
		ini_set('memory_limit','250M');
       	$flag = false;
		if(is_array($arrVal)):

			foreach($arrVal as $key => $val):

				switch($key)
				{
					case "6":
						$locName = $this->getLoc($val);
					break;
					
					case "10":
						$frmDate = $this->getFromDate($val);
					break;
					
					case "11":
						$toDate = $this->getToDate($val);
					break;
				}//end switch


				switch(trim($val))
				{
					case "EMD (EMDA-EMDS)":
						$flag = 'V';
					break;
					
					case "WEBLINK SALES":
						$flag = 'W';
					break;

                    case "ADM BSPLINK":
						$flag = 'A';
					break;

					case "ADM EXCESS VOIDING":
						$flag = 'AE';
					break;

                    case "OPATB/ET":
						$flag = 'O';
					break;
					
					case "OPATB2/ET CGN":
						$flag = 'O2';
					break;

					case "ADM MASS UPLOAD":
            $type = "ADM MASS UPLOAD";
						$flag = 'AM';
					break;

					case "ADM MASSLOADING":
            $type = "ADM MASSLOADING";
						$flag = 'AM';
					break;
					
					case "ADM - TICKET INSURANCE":
            		$flag = 'ATI';
					break;
					
					case "ACM MASS UPLOAD":
            		$flag = 'AMU';
					break;
					
          case "REFUND AUTH. BSPLINK":
            $type =  "REFUND AUTH. BSPLINK";
          $flag = 'R';
          break;
          case "RFND AUTHORITY BSPLINK":
            $type =  "RFND AUTHORITY BSPLINK";
          $flag = 'R';
          break;

          case "AUTOMATIC DIRECT REFUND":
            $type =  "AUTOMATIC DIRECT REFUND";
          $flag = 'R';
          break;
		  
		  case "AUTO REFUND":
            $type =  "AUTO REFUND";
          $flag = 'R';
          break;

			            case "SPDR EXCESSIVE VOIDS":
						$flag = 'SEV';
					break;
					
					case "SPDR":
						$flag = 'SEV2';
					break;
				}//end switch
				switch($flag)
				{
					case "V":
						$this->getEMD($val);
					break;
					
					case "W":
						$this->getWEBLINK($val);
					break;

                    case "A":
						$this->getADM(trim($val));
					break;

					case "AE":
						$this->getADM_EXCESS($val);
					break;

                    case "O":
						$this->getOPATB($val);
					break;
					
					case "O2":
						$this->getOPATB2($val);
					break;

					case "AM":
						$this->getADM_MASS(trim($val),$type);
					break;

                    case "R":
						$this->getREFUND(trim($val),$type);
					break;

					case "SEV":
						$this->getSPDR($val);
					break;
					
					case "ATI":
						$this->getADMTI($val);
					break;
					
					case "AMU":
						$this->getAMU($val);
					break;
					
					case "SEV2":
						$this->getSPDR2($val);
					break;
				}//end switch

			endforeach;
		endif;

        $fetchCode = $this->db->query("SELECT nId,vTktNumber,vType,nNetAmount FROM {PRE}Reconciliation WHERE dDate >= '". $fromDt."' AND dDate <= '".$toDt."'");
		//$subticket = substr(vTktNumber,0,10);
        if ($fetchCode->num_rows() > 0)
        {
            foreach ($fetchCode->result() as $key=>$row)
            {
              //remove the current data
              $data = array('vStatus' => 'N','vInvoice' => '','dInvoiceDate' => '',
                  'vPassenger' => '','vSystemAmt' => '');
              $this->db->where('nId',$row->nId);
              $this->db->update('{PRE}Reconciliation', $data);
			  
              $subticket = substr($row->vTktNumber,0,11);
			 // echo "$subticket <br><br>";
			//foreach($subticket as key=>row){
                switch($row->vType)
                {
                  case "Tkt":
                    $ticketId = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE LEFT(vTicktManual, 10) = '".$subticket."'");
                    $ticketStatus = $this->common_model->get_field_name('TicketBooking','vType' , " WHERE LEFT(vTicktManual, 10) = '".$subticket."'");

                  $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE LEFT(vTicktManual, 10) ='".$subticket."'");
                  if($ticketStatus == 'V'){
					 
                    $tkt_date= $this->common_model->get_field_name('TicketBooking','dDate'," WHERE LEFT(vTicktManual, 10) = '".$subticket."'");
                    $data = array('vStatus' => 'M','vInvoice' => '','dInvoiceDate' => '',
                        'vPassenger' => $passenger,'vSystemAmt' => '0');
                    $this->db->where('nId',$row->nId);
                    $this->db->update('{PRE}Reconciliation', $data);
                  }
		   // }
                  else {


                    $fetchInv = $this->db->query("SELECT sc.*,scd.vNetAmt FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON
                        sc.nSCId=scd.nSalesCloseId WHERE scd.vTktNumber = '".$ticketId."' AND scd.vType='Tkt'
                        AND sc.dDate >= '".$fromDt."' AND   sc.dDate <= '".$toDt."'");

                    //AND sc.dDate >= STR_TO_DATE(".$fromDt.", '%Y-%m-%d' ) AND   sc.dDate <= STR_TO_DATE(".$toDt.", '%Y-%m-%d' )");

                    $showVal  = $fetchInv->row();
					if ($fetchInv->num_rows() > 0)
                    {
                      if($row->nNetAmount==$showVal->vNetAmt):
                        $data = array('vStatus' => 'M','vInvoice' =>$showVal->vInvoiceNumber,'dInvoiceDate' => $showVal->dDate,
                            'vPassenger' => $passenger,'vSystemAmt' => $showVal->vNetAmt);
                      $this->db->where('nId',$row->nId);
                      $this->db->update('{PRE}Reconciliation', $data);
                      else:
                      $data = array('vStatus' => 'N','vInvoice' =>$showVal->vInvoiceNumber,'dInvoiceDate' => $showVal->dDate,
                          'vPassenger' => $passenger,'vSystemAmt' => $showVal->vNetAmt);
                      $this->db->where('nId',$row->nId);
                      $this->db->update('{PRE}Reconciliation', $data);
                      endif;
                    }
					
                  }
				  if($row->nNetAmount==0):
				        //$row->vSystemAmt=0;
					    $data = array('vStatus' => 'M','vPassenger' => $passenger,'vSystemAmt' => 0);
                        $this->db->where('nId',$row->nId);
                        $this->db->update('{PRE}Reconciliation', $data);
					  
                      endif;
				  
					
                  break;

                  case "Rfd":
                  $ticketId = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE LEFT(vTicktManual, 10) = '".$subticket."'");
                   
                  if( $ticketId == '') {
                    $ticketId = $this->common_model->get_field_name('RefundRegisterDetails','nTktNum'," WHERE nTktNum = '".$row->vTktNumber."' AND vNewOld = 'O' ");
                    $passenger = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE nTktNum = '".$row->vTktNumber."' AND vNewOld = 'O' "); 
                  }
                  else {
                    $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE LEFT(vTicktManual, 10) = '".$subticket."'");
                  }
                  $fetchInv = $this->db->query("SELECT rc.*,rcd.vNetAmt FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON
                      rc.nRCId=rcd.nRefundCloseId WHERE rcd.nTicketNumber = '".$ticketId."' AND rcd.vType='Tkt'
                      AND rc.dDate >= '".$fromDt."' AND   rc.dDate <= '".$toDt."'");

                  $showVal  = $fetchInv->row();
                  $vNetAmt_refund = $showVal->vNetAmt * -1;
                  if ($fetchInv->num_rows() > 0)
                  {
                    if($row->nNetAmount==$vNetAmt_refund):
                      $data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                          'vPassenger' => $passenger,'vSystemAmt' => $vNetAmt_refund);
                    $this->db->where('nId',$row->nId);
                    $this->db->update('{PRE}Reconciliation', $data);
                    else:
                    $data = array('vStatus' => 'N','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                        'vPassenger' => $passenger,'vSystemAmt' => $vNetAmt_refund);
                    $this->db->where('nId',$row->nId);
                    $this->db->update('{PRE}Reconciliation', $data);
                    endif;
                  }
                  break;

                    case "ADM":
                        $fetchInv = $this->db->query("SELECT * FROM {PRE}AdmEntry WHERE vRefNumber = '".$row->vTktNumber."'
                          AND dDate >= '".$fromDt."' AND   dDate <= '".$toDt."' ");
                        $showVal  = $fetchInv->row();
                        if ($fetchInv->num_rows() > 0)
                        {
                            if($row->nNetAmount==$showVal->vAmountDC):
                                $data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                    'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            else:
                                $data = array('vStatus' => 'N','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                    'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            endif;
                        }
                        break;
						
						case "ACM":
                        $fetchInv = $this->db->query("SELECT * FROM {PRE}AcmEntry WHERE vRefNumber = '".$row->vTktNumber."'
                          AND dDate >= '".$fromDt."' AND   dDate <= '".$toDt."' ");
                        $showVal  = $fetchInv->row();
                        if ($fetchInv->num_rows() > 0)
                        {
                            if($row->nNetAmount==$showVal->vAmountDC):
                                $data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                    'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            else:
                                $data = array('vStatus' => 'N','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                    'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            endif;
                        }
                }//end for switch
            }//end for each
        }//end if
	}//end function
	
	function getEMD($val)
	{
		if($val != 'EMD (EMDA−EMDS)')
      {
        $chk = @split(" ",$val);

        $aircode = @split("[.]",$chk[1]);
        if($aircode[1] == '' && $aircode[0] != 'TOT' && trim($aircode[0]) != ''):

                $chk1 = array();
                $i = 0;
                $j = 0;
                foreach($chk as $k => $v){
                  if(!($v == null || $v == '' )){
                    //echo $k . " => " . $v . " , ";
                    $chk1 = $chk1 + array ('j'.$i  => $j);
                    $j =0;
                    $chk1 = $chk1 + array ( $i => $v );
                    $i++;
                  }else { $j++; }
                }
                $aircode = $chk1[1];
                $tkt =  $chk1[2];

                if(strlen($chk1[3]) == 1 ){
                  $index_val = 4;
                }
                else {
                  $index_val = 3;
                }
                $index_val++;
                $dt = date('Y-m-d',strtotime($chk1[$index_val]));
                $index_val++;
                $amt = $chk1[$index_val];
                $index_val++;
                $index_val++;

                if ($chk1[$index_val] != '|'){
                  $index_val++;
                  if($chk1[$index_val] != '|'){
                    $index_val++;
                  }
                }
                $index_val++;
                if($chk1[$index_val] != '|'){
                  //st.comm rate is ignored
                  if($chk1['j' . $index_val] < 5 ){
                    $index_val++;
                    $comm = ($chk1[$index_val]);
                    $index_val++;
                    if($chk1[$index_val] != '|'){
                      $comm = $comm + $chk1[$index_val];
                      $index_val++;
                    }
                  }
                  else {
                    $comm = $chk1[$index_val];
                    $index_val++;
                  }
                }
                else {
                  $comm = '';
                }
                $index_val++;
                $netAmt = $chk1[$index_val];
                $index_val++;

                $min = strpos($netAmt,'-');
                if($min != false){
                  $netAmt = floatval($netAmt) * -1;
                }
                else{
                  $netAmt = floatval($netAmt);
                }
                $min = strpos($amt,'-');
                if($min != false){
                  $amt = floatval($amt) * -1;
                }
                else{
                  $amt = floatval($amt);
                }
                $tax        = $netAmt-$amt-$comm;
                $code = '';


        $data = array('vAircode'   => $aircode,
            'vTktNumber' => $tkt,
            'vType'      => 'Tkt',
            'vTotalTax'  => $tax,
            'vCommAmt'   => $comm,
            'nNetAmount' => $netAmt,
            'vTypeName'  => 'EMD (EMDA−EMDS)',
            'vFareCode'  => $code,
            'dDate'      => $dt,
            'vFacefare'  => $amt);

//echo print_r($data);
//echo "<br>";
//echo "<br>";
//echo "<br>";

          if (($dt >= $this->txtFromDate)&&($this->txtToDate >= $dt))
          {
            $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'Tkt'");
            if ($fetchTkt->num_rows() == 0)
            {
              //echo $fetchTkt. " ::::::::: ";
              //echo print_r($data);
              $this->db->insert('{PRE}Reconciliation', $data);
            }//end if
          }
        endif;
      } // end if
    } //end function
	
    function getWEBLINK($val)
    {
        if($val != 'WEBLINK SALES')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != 'SALES' && trim($aircode[0]) != '' && $chk[8] !=''):

                $amt        = ($chk[27] != '')?$chk[27]:$chk[28];
                $amt        = ($amt != '')?$amt:$chk[29];
                $tkt        = $chk[8];
                $comm       = $chk[101];
                $netAmt     = ($chk[139] != '')?$chk[139]:$chk[140];
                $netAmt     = ($netAmt != '')?$netAmt:$chk[141];
                $aircode    = $aircode[0];
                $date       = date('Y-m-d',strtotime($chk[18]));
                if(intval($netAmt) != 0):
               		 $tax        = $netAmt-$amt;
				else:
					$tax		 = 0;
				endif;
                $code       = '';

                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'Tkt',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'dDate'      => $date,
                    'vTypeName'  => 'WEBLINK SALES',
                    'vFareCode'  => $code,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'Tkt'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }//end if
    }//end function

    function getADM($val)
    {
        if($val != 'ADM BSPLINK')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[0]);
            if($aircode[1] == '' && $aircode[0] != 'TOT' && trim($aircode[0]) != '' && $chk[7] !=''):

                $amt        = ($chk[30] != '')?$chk[30]:$chk[31];
                $amt        = ($amt != '')?$amt:$chk[32];
                $tkt        = $chk[7];
                $comm       = $chk[101];
                $netAmt     = ($chk[141] =='|')?$chk[145]:$chk[141];
                $netAmt     = ($netAmt != '')?$netAmt:$chk[143];
                $aircode    = $aircode[0];
                $date       = date('Y-m-d',strtotime($chk[21]));
                if(intval($netAmt) != 0):
               		 $tax        = $netAmt-$amt;
				else:
					$tax		 = 0;
				endif;
                $code       = '';

                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'ADM BSPLINK',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function

    function getADM_EXCESS($val)
    {
        if($val != 'ADM EXCESS VOIDING')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != 'EXCESS' && trim($aircode[0]) != '' && $chk[8] !=''):

                $amt        = ($chk[31] != '')?$chk[31]:$chk[32];
                $amt        = ($amt != '')?$amt:$chk[33];
                $tkt        = $chk[8];
                $comm       = $chk[101];
                $netAmt     = ($chk[142] != '')?$chk[142]:$chk[144];
                $netAmt     = ($netAmt != '')?$netAmt:$chk[146];
                $aircode    = $aircode[0];
                $date       = date('Y-m-d',strtotime($chk[22]));
                if(intval($netAmt) != 0):
               		 $tax        = $netAmt-$amt;
				else:
					$tax		 = 0;
				endif;
                $code       = '';

                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'ADM EXCESS VOIDING',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function

	function getOPATB($val)
    {
        if($val != 'OPATB/ET')

      {
        $chk = @split(" ",$val);

        $aircode = @split("[.]",$chk[1]);
        if($aircode[1] == '' && $aircode[0] != 'TOT' && trim($aircode[0]) != ''):

                $chk1 = array();
                $i = 0;
                $j = 0;
                foreach($chk as $k => $v){
                  if(!($v == null || $v == '' )){
                    //echo $k . " => " . $v . " , ";
                    $chk1 = $chk1 + array ('j'.$i  => $j);
                    $j =0;
                    $chk1 = $chk1 + array ( $i => $v );
                    $i++;
                  }else { $j++; }
                }
                $aircode = $chk1[1];
                $tkt =  $chk1[2];

                if(strlen($chk1[3]) == 1 ){
                  $index_val = 4;
                }
                else {
                  $index_val = 3;
                }
                $index_val++;
                $dt = date('Y-m-d',strtotime($chk1[$index_val]));
                $index_val++;
                $amt = $chk1[$index_val];
				$index_val++;
                $index_val++;

                if ($chk1[$index_val] != '|'){
                  $index_val++;
                  if($chk1[$index_val] != '|'){
                    $index_val++;
                  }
                }
                $index_val++;
                if($chk1[$index_val] != '|'){
                  //st.comm rate is ignored
                  if($chk1['j' . $index_val] < 8 ){
                    $index_val++;
                    $comm = ($chk1[$index_val]);
                    $index_val++;
                    if($chk1[$index_val] != '|'){
                      $comm = $comm + $chk1[$index_val];
                      $index_val++;
                    }
                  }
                  else {
                    $comm = $chk1[$index_val];
                    $index_val++;
                  }
                }
                else {
                  $comm = '';
                }
				$index_val++;
                $netAmt = $chk1[$index_val];
                $index_val++;
							
                $min = strpos($netAmt,'-');
                if($min != false){
                  $netAmt = floatval($netAmt) * -1;
                }
                else{
					
                  $netAmt = floatval($netAmt);
				  
                }
			
                $min = strpos($amt,'-');
                if($min != false){
                  $amt = floatval($amt) * -1;
                }
                else{
                  $amt = floatval($amt);
                }
				
                $tax        = $netAmt-$amt+$comm;
                $code = '';


        $data = array('vAircode'   => $aircode,
            'vTktNumber' => $tkt,
            'vType'      => 'Tkt',
            'vTotalTax'  => $tax,
            'vCommAmt'   => $comm,
            'nNetAmount' => $netAmt,
            'vTypeName'  => 'OPATB/ET',
            'vFareCode'  => $code,
            'dDate'      => $dt,
            'vFacefare'  => $amt);

//echo print_r($data);
//echo "<br>";
//echo "<br>";
//echo "<br>";

          if (($dt >= $this->txtFromDate)&&($this->txtToDate >= $dt))
          {
            $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'Tkt'");
            if ($fetchTkt->num_rows() == 0)
            {
              //echo $fetchTkt. " ::::::::: ";
              //echo print_r($data);
              $this->db->insert('{PRE}Reconciliation', $data);
            }//end if
          }
        endif;
      } // end if
    } //end function

    function getADM_MASS($val,$type)
    {
        if(!($val == 'ADM MASS UPLOAD' || $val == 'ADM MASSLOADING'))
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != 'MASS' && trim($aircode[0]) != '' && $chk[8] !=''):

              $chk1 = array();
            $i=0;
            foreach($chk as $k => $v){
              if(!($v == null || $v == '' || $v == '|')){
                //echo $k . " => " . $v . " , ";
                $chk1 = $chk1 + array ( $i => $v );
                $i++;
              }
            }

            $aircode = $chk1[1];
            $tkt     = $chk1[2];
            $date    =  date('Y-m-d',strtotime($chk1[3]));
            $amt     = $chk1[4];
            $netAmt  = $chk1[5];
            $tax = $comm = $code = '';
			
                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => $type,
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM' AND vTypeName = '".$type."' ");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }//end if
            endif;
        }
    }//end function

    function getREFUND($val,$type)
    {
        if(!($val == 'REFUND AUTH. BSPLINK' || $val == 'RFND AUTHORITY BSPLINK' || $val == 'AUTO REFUND'|| $val == 'AUTOMATIC DIRECT REFUND'))
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[0]);
            if($aircode[1] == '' && $aircode[0] != 'TOT' && trim($aircode[0]) != '' && $chk[7] !=''):

                $chk1 = array();
                $i = 0;
                $j = 0;
                foreach($chk as $k => $v){
                  if(!($v == null || $v == '' )){
                    //echo $k . " => " . $v . " , ";
                    $chk1 = $chk1 + array ('j'.$i  => $j);
                    $j =0;
                    $chk1 = $chk1 + array ( $i => $v );
                    $i++;
                  }else { $j++; }
                }

                $aircode = $chk1[0];
                if(strlen($chk1[2]) == 1 ){
                  $index_val = 3; 
                }
                else {
                  $index_val = 2;
                }
                $dt = date('Y-m-d',strtotime($chk1[$index_val]));
                $index_val++;
                $amt = $chk1[$index_val];
                $index_val++;
                $index_val++;

                if ($chk1[$index_val] != '|'){
                  $index_val++;
                  if($chk1[$index_val] != '|'){
                    $index_val++;
                  }
                }
                $index_val++;

                if($chk1[$index_val] != '|'){
                  //st.comm rate is ignored
                  if($chk1['j' . $index_val] < 2 ){
                    $index_val++;
                    $comm = ($chk1[$index_val]);
                    $index_val++;
                    if($chk1[$index_val] != '|'){
                      $comm = $comm + $chk1[$index_val]; 
                      $index_val++;
                    }
                  }
                  else {
                    $comm = $chk1[$index_val];
                    $index_val++;
                  }
                }
                else {
                  $comm = '';
                }
                $index_val++;
                $netAmt = $chk1[$index_val];
                $index_val++;
                $tkt = substr( $chk1[$index_val] , -10); 

                $min = strpos($netAmt,'-');
                if($min != false){
                  $netAmt = floatval($netAmt) * -1;
                }
                else{
                  $netAmt = floatval($netAmt);
                }
                $min = strpos($amt,'-');
                if($min != false){
                  $amt = floatval($amt) * -1;
                }
                else{
                  $amt = floatval($amt);
                }
 
                $tax        = $netAmt-$amt-$comm;
               $code = '';

               $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'Rfd',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => $type, //'REFUND AUTH. BSPLINK',
                    'vFareCode'  => $code,
                    'dDate'      => $dt,
                    'vFacefare'  => $amt);
                if (($dt >= $this->txtFromDate)&&($this->txtToDate >= $dt))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'Rfd' AND vTypeName = '".$type."' ");
                    if ($fetchTkt->num_rows() == 0)
                    {
/*echo print_r($data);
echo "<br>";
echo "<br>";
echo "<br>";*/
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }

    }//end function

    
	function getSPDR($val)
    {
        if($val != 'SPDR EXCESSIVE VOIDS')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != ' TOT' && trim($aircode[0]) != '' && $chk[8] !=''):

              $chk1 = array();
            $i=0;
            foreach($chk as $k => $v){
              if(!($v == null || $v == '' || $v == '|')){
                //echo $k . " => " . $v . " , ";
                $chk1 = $chk1 + array ( $i => $v );
                $i++;
              }
            }
            $aircode = $chk1[1];
            $tkt     = $chk1[2];
            $date    =  date('Y-m-d',strtotime($chk1[3]));
            $amt     = $chk1[4];
            $netAmt  = $chk1[5];
            $tax = $comm = $code = '';
 


                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'SPDR EXCESSIVE VOIDS',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function
	
	function getADMTI($val)
    {
        if($val != 'ADM - TICKET INSURANCE')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != ' TOT' && trim($aircode[0]) != '' && $chk[8] !=''):

              $chk1 = array();
            $i=0;
            foreach($chk as $k => $v){
              if(!($v == null || $v == '' || $v == '|')){
                //echo $k . " => " . $v . " , ";
                $chk1 = $chk1 + array ( $i => $v );
                $i++;
              }
            }
            $aircode = $chk1[1];
            $tkt     = $chk1[2];
            $date    =  date('Y-m-d',strtotime($chk1[3]));
            $amt     = $chk1[4];
            $netAmt  = $chk1[5];
            $tax = $comm = $code = '';
 


                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'ADM - TICKET INSURANCE',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function
	
	function getAMU($val)
    {
        if($val != 'ACM MASS UPLOAD')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != ' TOT' && trim($aircode[0]) != '' && $chk[8] !=''):

              $chk1 = array();
            $i=0;
            foreach($chk as $k => $v){
              if(!($v == null || $v == '' || $v == '|')){
                //echo $k . " => " . $v . " , ";
                $chk1 = $chk1 + array ( $i => $v );
                $i++;
              }
            }
            $aircode = $chk1[1];
            $tkt     = $chk1[2];
            $date    =  date('Y-m-d',strtotime($chk1[3]));
            $amt     = $chk1[4];
            $netAmt  = $chk1[5];
            $tax = $comm = $code = '';
 


                $data = array('vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ACM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'ACM MASS UPLOAD',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ACM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function
	
	function getSPDR2($val)
    {
        if($val != 'SPDR')
        {
            $chk = @split(" ",$val);
            $aircode = @split("[.]",$chk[1]);
            if($aircode[1] == '' && $aircode[0] != ' TOT' && trim($aircode[0]) != '' && $chk[8] !=''):


              $chk1 = array();
            $i=0;
            foreach($chk as $k => $v){
              if(!($v == null || $v == '' || $v == '|')){
               // echo $k . " => " . $v . " , ";
                $chk1 = $chk1 + array ( $i => $v );
                $i++;
              }
            }
//echo print_r($chk1);
            $aircode = $chk1[1];
            $tkt     = $chk1[2];
            $date    =  date('Y-m-d',strtotime($chk1[3]));
            $amt     = $chk1[4];
            $netAmt  = $chk1[5];
            $tax = $comm = $code = '';

                $data = array(
				    'vAircode'   => $aircode,
                    'vTktNumber' => $tkt,
                    'vType'      => 'ADM',
                    'vTotalTax'  => $tax,
                    'vCommAmt'   => $comm,
                    'nNetAmount' => $netAmt,
                    'vTypeName'  => 'SPDR',
                    'vFareCode'  => $code,
                    'dDate'      => $date,
                    'vFacefare'  => $amt);

                if (($date >= $this->txtFromDate)&&($this->txtToDate >= $date))
                {
                    $fetchTkt = $this->db->query("SELECT * FROM {PRE}Reconciliation WHERE vTktNumber = '".$tkt."' AND vType = 'ADM'");
                    if ($fetchTkt->num_rows() == 0)
                    {
                        $this->db->insert('{PRE}Reconciliation', $data);
                    }//end if
                }
            endif;
        }
    }//end function

	//get location
	function getLoc($val)
	{
		$val = @split("[|]",$val);

		return ($val[1]);
	}//end function
	
	//get from date
	function getFromDate($val)
	{
		$val = @split("[|]",$val);
		$val = $val[3];
		$val = @str_replace('FROM','',$val);
		$val = @str_replace('TO','',$val);
		
		$dateSplit = @split("/",trim($val));
		$newDate   = trim($dateSplit[2]).'-'.trim($dateSplit[1]).'-'.trim($dateSplit[0]);
		$newDate   = date('Y-m-d',strtotime($newDate));
		
		return ($newDate);
	}//end function
	
	//get to date
	function getToDate($val)
	{
		$val = @split("[|]",$val);
		
		$dateSplit = @split("/",$val[0]);
		$newDate   = trim($dateSplit[2]).'-'.trim($dateSplit[1]).'-'.trim($dateSplit[0]);
		$newDate   = date('Y-m-d',strtotime($newDate));
		
		return ($newDate);
	}//end function
}//end class
?>
