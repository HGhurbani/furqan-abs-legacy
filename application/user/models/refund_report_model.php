<?php
class Refund_report_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	function getActiveGDS()
	{
		$gdsGroupRow = array();
		$gdsGroup    = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");
		if ($gdsGroup->num_rows() > 0)
		{
			$gdsGroupRow[0] = SELECT;
			foreach ($gdsGroup->result() as $key => $row)
			{
				$gdsGroupRow[$row->nGId] = $row->vName;
			}//end for each
		}//end if
		else
		{
			$gdsGroupRow[0] = NOVALUE;
		}//end else
		return ($gdsGroupRow);
	}//end function
	function getActiveEmployee()
	{
		$empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
										ORDER BY vName ASC");
		$empGroupRow = array();
		if ($empGroup->num_rows() > 0)
		{
			$empGroupRow[0] = SELECT;
			foreach ($empGroup->result() as $key => $row)
			{
				$empGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$empGroupRow[0] = NOVALUE;
		}//end else
		return ($empGroupRow);
	}//end function
    function getCorrActiveCity()
    {
        $corrCityGroupRow[0] = NOVALUE;
        return ($corrCityGroupRow);
    }//end function
	function getActiveCountry()
	{
		$countryGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'
											ORDER BY nId DESC");
		$countryGroupRow = array();
		if ($countryGroup->num_rows() > 0)
		{
			$countryGroupRow[0] = SELECT;
			foreach ($countryGroup->result() as $key => $row)
			{
				$countryGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$countryGroupRow[0] = NOVALUE;
		}//end else
		return ($countryGroupRow);
	}//end function

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function
	function getActiveHotels()
	{
		$hotelGroup    = $this->db->query("SELECT nHId,vName FROM {PRE}Hotel WHERE vActive = '1' ORDER BY nHId DESC");
		$hotelGroupRow = array();
		if ($hotelGroup->num_rows() > 0)
		{
			$hotelGroupRow[0] = SELECT;
			foreach ($hotelGroup->result() as $key => $row)
			{
				$hotelGroupRow[$row->nHId] = (stripslashes($row->vName));
			}//end for each
		}//end if
		else
		{
			$hotelGroupRow[0] = NOVALUE;
		}//end else
		return ($hotelGroupRow);
	}//end function
	function filterCountry()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlCountry = $this->input->post('ddlCountry');
		$this->ddlCity    = $this->input->post('ddlCity');
		$data['list_city_value'] = $this->refund_report_model->filterCity($this->ddlCountry);
		$this->load->view('refund_report_model/show_city',$data);
	}//end function
	function filterCity($country)
	{
		$cityGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."'
										ORDER BY nId DESC");
		$cityGroupRow = array();
		if ($cityGroup->num_rows() > 0)
		{
			$cityGroupRow[0] = SELECT;
			foreach ($cityGroup->result() as $key => $row)
			{
				$cityGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$cityGroupRow[0] = NOVALUE;
		}//end else
		return($cityGroupRow);
	}//end function
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
    function getActiveLocation()
	{
		//$sessionLoc  = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
		$sessionLoc  = $this->db->query("SELECT nLocation FROM {PRE}Admin WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
		$locName     = $sessionLoc->row();
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '".$locName->nLocation."'
										ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return ($locGroupRow);
	}//end function
	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
										AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
// to make the first location default
			//$locGroupRow[0] = SELECT;
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
	function getActiveVendor()
    {
        $empGroup    = $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId DESC");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nVId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function
    function getPayableAccont()
    {
        $empGroup    = $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster WHERE vActive = '1'");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nVId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function
	function getActiveAirline()
    {
        $empGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline  WHERE vActive = '1' ORDER BY vTitle ASC");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nArId] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function
	function mainSalesArray()
	{
		$mainSalesArrayList = array("All" => "All","Tkt" => "Ticket Refund",
                              "Xo" => "XO Refund","Htl" => "Hotel Refund"
                            );
		return ($mainSalesArrayList);
	}//end function
	function mainClosedArray()
	{
		$mainRefundArrayList = array("All"=> "All", "Register" => "Register Report","Closed" => "Closed Report");
		return ($mainRefundArrayList);
	}//end function
    function mainStatusArray()
	{
		$mainStatusArrayList = array("Normal" => "Normal","Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void");
		return ($mainStatusArrayList);
	}//end function
	function mainReportArray()
	{
		$mainReportArrayList = array("Details" => "Details","Summary" => "Summary",
                              "By Year" => "By Year","By Month" => "By Month"
                            );
		return ($mainReportArrayList);
	}//end function
/*    function getAccounts()
    {
        $empGroup    = $this->db->query("SELECT nCId,nFId,vName,vCode FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY nCId DESC");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function
*/
    function getAccounts()
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY vName ASC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY vName ASC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY vName ASC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($OtherGroupRow,$resultArray);
 
	$accheadGroup = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode FROM {PRE}AccountHeadAccounts aha
			INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId
			WHERE ahc.vSubLedgerType NOT IN ( 'V','C','E','A') ORDER BY aha.vTitle ");

        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->nId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
	natcasesort($resultArray);
        return ($resultArray);
	
    }//end function


	function getSubAccountValues()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return($typeGroupRow);

    }//end function

    function filterSubAccount($accGroup_val)
    {
	    // split 
	    $acc = explode("-", $accGroup_val);
	    $accGroup = $acc[0];
	    $table = $acc[1];

        if($accGroup>0):
            switch($table)
            {
                case "C" :
                    $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "V" :
                    $accNameId       = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "AG" :
                    $accNameId       = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "O" :
                    $accNameId       = $this->common_model->get_field_name('Other','nFId'," WHERE nOId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}Other v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'O' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "A" :
                    $accheadGroupRow    = array();
                    $accheadGroupRow[0] = NOVALUE;
                    break;
            }//end switch
        else:
            $accheadGroupRow    = array();
            $accheadGroupRow[0] = NOVALUE;
        endif;
        return ($accheadGroupRow);
} 


/*	function filterSubAccount($accGroup)
    {
        $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										    ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
											WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
											ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            foreach ($accheadGroup->result() as $key=>$row)
            {
                $accheadGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $accheadGroupRow[0] = NOVALUE;
        }//end else
        return ($accheadGroupRow);
    }//end function
*/	
	
	function get_columns_register(){
		$columns_r = "  'REGISTER' as rfStatus , 'index.php/refund_register/editRefund/4/e/' as Link , rr.nRId as Id, 						
						SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', 2 ),'-',1) AS account, 
						SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', -1 ),'-',2) as vTable,
						rr.dDate, rr.vDocNumber as vDocNumber, 
						rrd.vTktType as sale_type, rrd.nTktNum as nTktNum, 
						rrd.vAirline as airline, rrd.vNetAmt2 as rf_netAmt,
						rrd.vPaidTo as paidAmt, 
					   	rrd.vPassenger, rrd.vFareAmt as fareVal, 
					   	rrd.vTax as taxTotal, rrd.vIssuedBy, 
						rrd.vSalesAmt as received, rrd.vOurCharge as officeCharge, 
						rrd.vVendorAmt as vendor,
	
						CASE 
							WHEN rrd.vTktType = 'Tkt' THEN (select tfd.nNetVendor from {PRE}TicketFareDetails tfd where tfd.nTicketId = rrd.nTktNum)
							WHEN rrd.vTktType = 'Xo' THEN (select xpd.nNetVendor from {PRE}XoPassDetails xpd where xpd.nXoId = rrd.nTktNum LIMIT 0,1)
							WHEN rrd.vTktType = 'Htl' THEN (select hv.vTotalDC as netAmt from {PRE}HotelVoucher hv where hv.nHVId = rrd.nTktNum) 
							ELSE ''							
							END as netAmt,
							
						CASE 
							WHEN rrd.vTktType = 'Tkt' THEN (select tb.vTicktManual from {PRE}TicketBooking tb where tb.nTId = rrd.nTktNum) 
							WHEN rrd.vTktType = 'Xo' THEN (select xo.vTicktManual from {PRE}XoBooking xo where xo.nXId = rrd.nTktNum) 
							WHEN rrd.vTktType = 'Htl' THEN (select hv.vTicktManual from {PRE}HotelVoucher hv where hv.nHVId = rrd.nTktNum)
							ELSE '' 
							END as tktNumber
													
						";

		return $columns_r;
	}
	
	
	function get_columns_closed(){
		$columns_c = "  'CLOSED' as rfStatus , 'index.php/refund_closing/editRefundClosing/4/e/' as Link , rc.nRCId as Id,
						rcd.nDrAcc as account, rcd.vTable as vTable,
						rc.dDate, rc.vDocNumber as vDocNumber, 
						rcd.vType as sale_type, rcd.nTicketNumber as nTktNum, 
						rcd.nAirline as airline, rcd.vNetAmt as rf_netAmt,
						rcd.vPaidAmt as paidAmt, 
							
					   	rrd.vPassenger, rrd.vFareAmt as fareVal, 
					   	rrd.vTax as taxTotal, rrd.vIssuedBy, 
						rrd.vSalesAmt as received, rrd.vOurCharge as officeCharge, 
						rrd.vVendorAmt as vendor,
						
						CASE 
							WHEN rcd.vType = 'Tkt' THEN (select tfd.nNetVendor from {PRE}TicketFareDetails tfd where tfd.nTicketId = rcd.nTicketNumber)
							WHEN rcd.vType = 'Xo' THEN (select xpd.nNetVendor from {PRE}XoPassDetails xpd where xpd.nXoId = rcd.nTicketNumber LIMIT 0,1)
							WHEN rcd.vType = 'Htl' THEN (select hv.vTotalDC as netAmt from {PRE}HotelVoucher hv where hv.nHVId = rcd.nTicketNumber) 
							ELSE ''							
							END as netAmt,
						CASE 
							WHEN rcd.vType = 'Tkt' THEN (select tb.vTicktManual from {PRE}TicketBooking tb where tb.nTId = rcd.nTicketNumber) 
							WHEN rcd.vType = 'Xo' THEN (select xo.vTicktManual from {PRE}XoBooking xo where xo.nXId = rcd.nTicketNumber) 
							WHEN rcd.vType = 'Htl' THEN (select hv.vTicktManual from {PRE}HotelVoucher hv where hv.nHVId = rcd.nTicketNumber) 
							ELSE ''
							END as tktNumber  				
						";
			
		return $columns_c;
	}
	
	
    function getReport($extraCheck_r,$extraCheck_c,$orderby,$Refund)
    {

		$column_r = $this->get_columns_register();
		$column_c = $this->get_columns_closed();
		
		if($Refund == 'Register')
		{
			$extraColumn_r  = " , CASE 
								WHEN rrd.vTktType = 'Tkt' THEN 
											IF( rrd.vAirline IN (select rcd.nAirline from {PRE}RefundClosingDetails rcd where rcd.vType = 'Tkt') , 'CLOSED','OPEN') 
								WHEN rrd.vTktType = 'Xo' THEN 
											IF( rrd.vAirline IN (select rcd.nAirline from {PRE}RefundClosingDetails rcd where rcd.vType = 'Xo') , 'CLOSED','OPEN') 
								WHEN rrd.vTktType = 'Htl' THEN 
											IF( rrd.vAirline IN (select rcd.nAirline from {PRE}RefundClosingDetails rcd where rcd.vType = 'Htl') , 'CLOSED','OPEN') 							
								ELSE '' 
								END as register_status 
							";
		}
		else {
			$extraColumn_r = " , '' as register_status";
		}
		
		$query_c = "(SELECT  " . $column_c . "  " . $extraColumn_r . " 
						FROM {PRE}RefundClosingDetails rcd 
						LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = rcd.nTicketNumber					
						INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
						LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation															
						WHERE rc.vActive = '1'
						".$extraCheck_c. ")";

		$query_r = "(SELECT " . $column_r . "  " . $extraColumn_r . " 
						FROM {PRE}RefundRegisterDetails rrd 
						INNER JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
						LEFT JOIN {PRE}RefundRegisterBilling rrb ON rr.nRId = rrb.nRefundId
						LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation									
						WHERE rr.vActive = '1' 
						".$extraCheck_r.") ";
									

		if($Refund == 'All')
		{
			$adminGroupListQuery = ($query_c . "	UNION ALL " . $query_r);
		}
		else if($Refund == 'Closed')
		{
			$adminGroupListQuery  = $query_c; 
		}
		else if($Refund == 'Register')
		{
			$adminGroupListQuery  = $query_r;
		}


		$adminGroupListQuery .=" ORDER BY " . $orderby . " ";

//		echo $adminGroupListQuery;
//		return;					

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
/*

    function getRegisterReport($extraCheck_r,$extraCheck_c,$extraCheck_x,$orderby,$SalesType)
    {
										
		$column_r = $this->get_columns_register();
		$column_c = $this->get_columns_closed();

		$adminGroupListQuery = "SELECT " . $column_r . "
									FROM {PRE}RefundRegisterDetails rrd 
									INNER JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
									WHERE rr.vActive = '1' 
									".$extraCheck_r."
									ORDER BY " . $orderby . " ";

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
    function getClosedReport($extraCheck_r,$extraCheck_c,$extraCheck_x,$orderby,$SalesType)
    {
										
		$column_r = $this->get_columns_register();
		$column_c = $this->get_columns_closed();

		$adminGroupListQuery = "SELECT  " . $column_c . " 
									FROM {PRE}RefundClosingDetails rcd 
									INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
									WHERE rc.vActive = '1'
	 							  	".$extraCheck_r. "
									ORDER BY " . $orderby . " ";

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
*/
	
}//end class 
?>
