<?php
class Customer_report_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
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
    function getActiveLocation()
    {
        $sessionLoc  = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
        $locName     = $sessionLoc->row();
		if($sessionLoc->num_rows() == 0)
			$locId = $this->common_model->getLocLogged();
		else
			$locId = $locName->vLocation;
			
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '".$locId."'
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
//            $locGroupRow[0] = SELECT;
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
    function getActiveCountry()
    {
        $countryGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'
												ORDER BY vName ASC");
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
    function getActivePayable()
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
    function getCorrActiveCity()
    {
        $corrCityGroupRow[0] = NOVALUE;
        return ($corrCityGroupRow);
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
    function getActiveHotels()
    {
        $hotelGroup    = $this->db->query("SELECT nHId,vName FROM {PRE}Hotel WHERE vActive = '1' ORDER BY vName ASC");
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
    function getActiveEmployees()
    {
        $empGroup    = $this->db->query("SELECT nEId,vName,vCode FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY vName ASC");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nEId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function
    function getActiveAcName()
    {
/*        $empGroup    = $this->db->query("SELECT nCId,nFId,vName,vCode FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY nCId DESC");
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
*/  
        $accName[0] = NOVALUE;
        return ($accName);
	 }//end function
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
    function mainRefundArray()
    {
        $mainRefundArrayList = array("All" => "All","Sales" => "Sales","Refund" => "Refund");
        return ($mainRefundArrayList);
    }//end function
    function filterCountry()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlCountry = $this->input->post('ddlCountry');
        $this->ddlCity    = $this->input->post('ddlCity');
        $data['list_city_value'] = $this->customer_report_model->filterCity($this->ddlCountry);
        $this->load->view('customer_report_model/show_city',$data);
    }//end function
    function filterCity($country)
    {
        $cityGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."'
											ORDER BY vName ASC");
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
    function filterAccount($accGroup_val)
    {
	/*
        $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
      */
/*	  	$tableNameId = $this->common_model->get_field_name('AccountHeadControls','vSubLedgerType'," WHERE nActHId = '".$accGroup."' ");
		switch($tableNameId){
			case "C": $accheadGroup = $this->db->query(" SELECT  nCId as Id, vName  from {PRE}CustomerMaster WHERE nFId = ". $accGroup .""); break;
			case "V": $accheadGroup = $this->db->query(" SELECT nVId as Id, vName from {PRE}VendorMaster WHERE nFId = ". $accGroup .""); break;
			case "A": $accheadGroup = $this->db->query(" SELECT  nAId as Id, vName  from {PRE}Agent WHERE nFId = ". $accGroup .""); break;
			case "O": $accheadGroup = $this->db->query(" SELECT  nOId as Id, vName  from {PRE}Other WHERE nFId = ". $accGroup .""); break;
			default: $accheadGroup = array();
		}
		$accheadGroupRow = array();
	    if ( !empty($accheadGroup) && $accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key=>$row)
            {
                $accheadGroupRow[$row->Id] = $row->vName;
            }//end for each
        }//end if
        else
        {
            $accheadGroupRow[0] = NOVALUE;
        }//end else
        return ($accheadGroupRow);
*/

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
 


    }//end function
    function getActiveSubAccounts() 
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
	

/*        $SubAccGroupRow    = array();
        $SubAccGroup    = $this->db->query("SELECT * FROM {PRE}AccountHeadAccounts ORDER BY vTitle ASC");
        if ($SubAccGroup->num_rows() > 0)
        {
            $SubAccGroupRow[0] = SELECT;
            foreach ($SubAccGroup->result() as $key => $row)
            {
                $SubAccGroupRow[$row->nId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $SubAccGroupRow[0] = NOVALUE;
        }//end else
        return ($SubAccGroupRow);
*/
    }//end function
    function mainSalesArray()
    {
        $mainSalesArrayList = array("All" => "All","Tkt" => "Ticket Sales","Xo" => "XO Sales","Htl" => "Hotel Sales");
        return ($mainSalesArrayList);
    }//end function
    function salesType()
    {
        $salesArray = array('TS' => 'Ticket Sales','TR' => 'Ticket Refunds','HS' => 'Hotel Sales','HR' => 'Hotel Refunds','XS' => 'XO Sales','XR' => 'XO Refunds','A' => 'ALL');
        return ($salesArray);
    }//end function
    function mainReportArray()
    {
        $mainReportArrayList = array("Details" => "Details","Summary" => "Summary","By Year" => "By Year","By Month" => "By Month");
        return ($mainReportArrayList);
    }//end function
	
	
/*	
    function getReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
            break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,
                                            xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as
                                            ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,
                                            xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId LEFT JOIN {PRE}EmployeeMaster em
                                            ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId =xbd.nCustomerAc WHERE
                                            xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')  ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM
                                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')  ".$extraCheck_t."
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM
                                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')  ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo')".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')  ".$extraCheck_t."
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo')".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getSalesAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getRefundAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Tkt":
                $adminGroupListQuery  = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
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
                break;
            case "Xo":
                $adminGroupListQuery  = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                break;
            case "Htl":
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getHotelReport($extraCheck_h,$SalesType)
    {
        $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.vHotelName,hv.nLocation,hbd.nCustomerAc as accountName,aha.*,'Hotel' as
											 sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,
											  hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt as salesAmt,
											hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,'' as fareVal FROM
											{PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd	ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE hv.vActive='1' ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
    function getSalesHotelReport($extraCheck_h,$SalesType)
    {
        $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.vHotelName,hv.nLocation,hbd.nCustomerAc as accountName,aha.*,'Hotel' as
											 sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent
											 as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,
											 hv.vPassenger,hbd.vSalesAmt as salesAmt,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,
											 '' as taxTotal,'' as cmtAmount,'' as netAmt,'' as fareVal FROM	{PRE}HotelVoucher hv LEFT JOIN
											 {PRE}HotelBillingDetails hbd	ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON
											 hv.vIssuedBy = em.nEId	LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND
                                            hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') ".$extraCheck_h."
                                            ORDER BY hv.nHVId DESC)";
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
    function getRefundHotelReport($extraCheck_h,$SalesType)
    {
        $adminGroupListQuery  = "(SELECT hv.nHVId,hv.vDocNumber,hv.vHotelName,hv.nLocation,hbd.nCustomerAc as accountName,aha.*,'Hotel' as
											 sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,
											  hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt as salesAmt,
											hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,'' as fareVal FROM
											{PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd	ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON
											hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND
											hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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




	
// columns for sales


	function get_columns_hotel_sales(){
		$columns_h = "'' as cmtAmount,hv.vTotalDC as netAmt, hv.nHVId,hv.vDocNumber,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,  hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as creditAmt,hv.vTotalRTDC as fareVal, '' as passSalesAmt ";

		return $columns_h;
	}
	
	function get_columns_ticket_sales(){
		$columns_t = "tfd.nCommAmt as cmtAmount	,tfd.nNetVendor as netAmt, tb.nTId,tb.vDocNumber,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nORCAmt as creditAmt, tfd.vFaceValue as fareVal, '' as passSalesAmt  ";

		return $columns_t; 											
	}
	
	function get_columns_xo_sales(){
		$columns_x = "xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt, xo.nXId as nTId,xo.vDocNumber,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,'' as creditAmt,xpd.vFCTax as fareVal, xpd.nSalesAmt as passSalesAmt  ";

		return $columns_x;
	}
	
	
// columns for refund


	function get_columns_hotel_refund(){
		$columns_h = "hv.nHVId,hv.vDocNumber,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,  hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as creditAmt,-hv.vTotalRTDC as fareVal, '' as passSalesAmt";

		return $columns_h;
	}
	
	function get_columns_ticket_refund(){
		$columns_t = "tb.nTId,tb.vDocNumber,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-tfd.nORCAmt as creditAmt,-tfd.vFaceValue as fareVal,'' as passSalesAmt  ";

		return $columns_t; 											
	}
	
	function get_columns_xo_refund(){
		$columns_x = "xo.nXId as nTId,xo.vDocNumber,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId, -xpd.vTaxTotal as taxTotal,'' as creditAmt, -xpd.vFCTax as fareVal, -xpd.nSalesAmt as passSalesAmt  ";

		return $columns_x;
	}
	

  function get_columns_old_refund(){
    $columns_o = " rr.nRId as nTId,'' as vDocNumber,rrd.vTktType as sale_type,rrd.vIssuedBy as vIssuedBy, ar.nArId as airline, '' as vGdspnr, 'N' as vStatus, rrd.vSalesAmt as salesAmt,rrd.vDiscountAmt as discAmt,rrd.vServicePer as ServicePercent,rrd.vServiceAmt as ServiceAmt, rrd.nTktNum as tktNumber,' ' as lpo,rrd.vPassenger as vPassenger,rrd.vSalesAmt as collected,' ' as profit,rrd.nRDId as tableId, -rrd.vTax as taxTotal,'' as creditAmt, -rrd.vFareAmt as fareVal,  rrd.vSalesAmt as passSalesAmt ";
    return $columns_o;
  }


	function get_columns_sales(){
			$columns_s = " 'sales' as refundtype,sc.vInvoiceNumber as inv, scd.vSlsAmt as salesAmount, ROUND(scd.vServAmt,3) as Profit, ROUND(scd.vServPercent,3) as ProfitPercent,
				sad.nDrAccount as nDrAccount, sad.nAcHead as nAcHead, sad.vTable as vTable, sc.vLocation as nLocation, DATE_FORMAT(sc.dDate, '%d-%m-%Y') as dDate, 
			 CONCAT('index.php/sales_closing/editSalesClosing/4/e/',scd.nId,'/',scd.nSalesCloseId,'/cl' ) as link, scd.nSalesCloseId  as closedId, ";
			
			

		return $columns_s;
	}
	
	function get_columns_refund(){
		$columns_r = " 'refund' as refundtype,rc.vDocNumber as inv, -rcd.vPaidAmt as salesAmount, ROUND(-rcd.vServAmt,3) as Profit, ROUND(-rcd.vServPercent,3) as ProfitPercent,
		
		rcd.nDrAcc as nDrAccount, 
		
		CASE 
			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)
			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)
			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)
			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)
			ELSE '0'
		END as nAcHead, 
		rcd.vTable as vTable,
		rc.vLocation as nLocation,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate,
CONCAT('index.php/refund_closing/editRefundClosing/4/e/',rcd.nRefundCloseId) as link, rcd.nRefundCloseId as closedId,	(select -rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount,
						-rcd.vNetAmt as netAmt,	";
						
		return $columns_r;
	}


// for airline reports

    function getAllReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {
										
		$column_t_s = $this->get_columns_ticket_sales();
		$column_x_s = $this->get_columns_xo_sales();
		$column_h_s = $this->get_columns_hotel_sales();

		$column_t_r = $this->get_columns_ticket_refund();
		$column_x_r = $this->get_columns_xo_refund();
		$column_h_r = $this->get_columns_hotel_refund();
    $column_o_r = $this->get_columns_old_refund();

		$column_s = $this->get_columns_sales();
		$column_r = $this->get_columns_refund();
				
		 $tkt_sales = " SELECT " . $column_s . " " . $column_t_s . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId  AND scd.vType = 'Tkt'
		 								 LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
										 LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									 										 LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId										 
										 WHERE tb.vConjStatus = 'Yes' 
										" .$extraCheck_t . " " . $extraCheck_accounts_s .  "  " ;

	

		$xo_sales = " SELECT " . $column_s . " " . $column_x_s . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
									 	LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation							
									 	LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId										
									 	WHERE xo.vActive='1' 
										".$extraCheck_x." " . $extraCheck_accounts_s .  "  ";										

		$htl_sales = "	SELECT " . $column_s . " " . $column_h_s . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
 									    LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
										LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									 										LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId										
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h." " . $extraCheck_accounts_s . "  ";									
		
		$tkt_refund = " SELECT " . $column_r . " " . $column_t_r . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId  AND rcd.vType  = 'Tkt'
 										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									 	 LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
		 								 LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t." " . $extraCheck_accounts_r . " ";
										
		$xo_refund = " SELECT " . $column_r . " " . $column_x_r . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId  AND rcd.vType  = 'Xo'
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x." " . $extraCheck_accounts_r . " " ;
		$htl_refund = " SELECT " . $column_r . " " . $column_h_r . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId  AND rcd.vType  = 'Htl'
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 										
									    WHERE hv.vActive='1' 
										".$extraCheck_h." " . $extraCheck_accounts_r . " " ;

    $old_refund = " SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or." ". $extraCheck_accounts_r . " " ;
																								//print $tkt_sales;
        switch($SalesType)
        {
            case "All":
						$query = $tkt_sales . " UNION ALL ( " . $xo_sales  . ") UNION ALL ( " . $htl_sales . ") UNION ALL ( " . $tkt_refund  . ") UNION ALL ( " . $xo_refund . ") UNION ALL ( " . $htl_refund . " ) UNION ALL (  ". $old_refund ." ) ";
						break;
					
			case "Tkt":
						$query = $tkt_sales . " UNION ALL ( " . $tkt_refund  . ")  UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Tkt' )";
						break;			
					
			case "Xo":
						$query = $xo_sales . " UNION ALL ( " . $xo_refund  . ")  UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Xo' )";
						break;			
	
			case "Htl" :
						$query = $htl_sales . " UNION ALL ( " . $htl_refund  . ")  UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Htl' )";
						break;					
		}
		$adminGroupListQuery = $query . " ORDER BY " . $orderby;
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




    function getSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {
										
		$column_t_s = $this->get_columns_ticket_sales();
		$column_x_s = $this->get_columns_xo_sales();
		$column_h_s = $this->get_columns_hotel_sales();

		$column_s = $this->get_columns_sales();
		$tkt_sales = " SELECT " . $column_s . " " . $column_t_s . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId  AND scd.vType = 'Tkt'
										 LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
										 LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									 										 LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId			 
										 WHERE tb.vConjStatus = 'Yes' 
										" .$extraCheck_t . " " . $extraCheck_accounts_s .  "  " ;

		$xo_sales = " SELECT " . $column_s . " " . $column_x_s . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
		 								LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
										LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									 										LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId			
										WHERE xo.vActive='1' 
										".$extraCheck_x . " " . $extraCheck_accounts_s .  "  ";										

		$htl_sales = "	SELECT " . $column_s . " " . $column_h_s . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
 									    LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
										LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									 										LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId			
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h . " " . $extraCheck_accounts_s . "  ";									


		
																								
        switch($SalesType)
        {
            case "All":
						$adminGroupListQuery = $tkt_sales . " UNION ALL ( " . $xo_sales  . ") UNION ALL ( " . $htl_sales . " ) " . " ORDER BY " . $orderby;
						break;
					
			case "Tkt":
						$adminGroupListQuery = $tkt_sales . " ORDER BY " . $orderby;
						break;			
					
			case "Xo":
						$adminGroupListQuery = $xo_sales . " ORDER BY " . $orderby;
						break;			
	
			case "Htl" :
						$adminGroupListQuery = $htl_sales . " ORDER BY " . $orderby;
						break;					
		}

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





    function getRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {
										
		$column_t_r = $this->get_columns_ticket_refund();
		$column_x_r = $this->get_columns_xo_refund();
		$column_h_r  = $this->get_columns_hotel_refund();
    $column_o_r = $this->get_columns_old_refund();
		$column_r = $this->get_columns_refund();

		$tkt_refund = " SELECT " . $column_r . " " . $column_t_r . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId  AND rcd.vType  = 'Tkt'
 										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										 LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
										 LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t." " . $extraCheck_accounts_r . " ";
										
		$xo_refund = " SELECT " . $column_r . " " . $column_x_r . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId  AND rcd.vType  = 'Xo'
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x." " . $extraCheck_accounts_r . " " ;
		$htl_refund = " SELECT " . $column_r . " " . $column_h_r . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId  AND rcd.vType  = 'Htl'
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 										
									    WHERE hv.vActive='1' 
										".$extraCheck_h." " . $extraCheck_accounts_r . " " ;
																								
    $old_refund = " SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or." ". $extraCheck_accounts_r . " " ;

																								
        switch($SalesType)
        {
            case "All":
						$adminGroupListQuery = $tkt_refund. " UNION ALL ( " . $xo_refund . ") UNION ALL ( " . $htl_refund. " )  UNION ALL (  ". $old_refund ."  ) " . " ORDER BY " . $orderby;
						break;
					
			case "Tkt":
						$adminGroupListQuery = $tkt_refund. " UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Tkt' ) ORDER BY " . $orderby;
						break;			
					
			case "Xo":
						$adminGroupListQuery = $xo_refund. "  UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Xo' ) ORDER BY " . $orderby;
						break;			
	
			case "Htl" :
						$adminGroupListQuery = $htl_refund. " UNION ALL (  ". $old_refund ." AND rrd.vTktType ='Htl' ) ORDER BY " . $orderby;
						break;					
		}


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

// for hotel reports 


	function get_columns_hotel_report_sales(){
		$columns_h = " '' as cmtAmount,hv.vTotalDC as netAmt, hv.vTotalRTDC, hv.vNight, hv.vNoOfUnit, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.vHotelName, htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}

	function get_columns_hotel_report_refund(){
		$columns_h = " -hv.vTotalRTDC as vTotalRTDC, hv.vNight, hv.vNoOfUnit, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.vHotelName,  htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}


  function get_columns_hotel_old_refund(){
 $columns_o = " '' as vTotalRTDC, '' as vNight, '' as vNoOfUnit,rrd.vPassenger, rrd.nTktNum as vTicktManual, rrd.vIssuedBy,  '' vHotelName, htl.vName as vName, crty.vName as Country, cty.vName as City, '' as dCheckIn,'' as dCheckOut, rrd.nRDId as tableId,'Hotel' as sale_type ";
    return $columns_o;
  }


    function getAllHotelReport($extraCheck_h, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {

		$column_h_s = $this->get_columns_hotel_report_sales();										
		$column_h_r = $this->get_columns_hotel_report_refund();
    $column_o_r = $this->get_columns_hotel_old_refund();

		$column_s = $this->get_columns_sales();
		$column_r = $this->get_columns_refund();
		$adminGroupListQuery = "SELECT "  . $column_s . " " . $column_h_s . " 
								FROM {PRE}HotelVoucher hv
								LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
								LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
								LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
								LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
								INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
								LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
								LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 									
								LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId			
								WHERE hv.vActive='1' 
								".$extraCheck_h." " . $extraCheck_accounts_s . "
								
								UNION ALL(SELECT " . $column_r . " " . $column_h_r . " 
								FROM {PRE}HotelVoucher hv 
								LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
								LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
								LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
								LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
								INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId  AND rcd.vType  = 'Htl'
								INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
								LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
								LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
								WHERE hv.vActive='1' 
								".$extraCheck_h." " . $extraCheck_accounts_r . ") 
								

                  UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                  FROM {PRE}RefundRegister rr
                  LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                  LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                   LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                  LEFT JOIN {PRE}Hotel htl ON htl.nHId = rrd.nHotel
                  LEFT JOIN {PRE}Country cty ON cty.nId = rrd.nCity
                  LEFT JOIN {PRE}Country crty ON crty.nId = rrd.nCountry
                  INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                   INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                  INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                    LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                    WHERE rr.vActive='1' AND rrd.vTktType ='Htl'
                  ".$extraCheck_or." " .$extraCheck_accounts_r . ")


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




    function getHotelSalesReport($extraCheck_h, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {
		$column_h_s = $this->get_columns_hotel_report_sales();										
		$column_s = $this->get_columns_sales();
		$adminGroupListQuery = "(SELECT " . $column_s . " " . $column_h_s . " 
								FROM {PRE}HotelVoucher hv 
								LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
								LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
								LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
								LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
								INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
								LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 	
								LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 																								
								LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId			
								WHERE hv.vActive='1' 
								".$extraCheck_h." " . $extraCheck_accounts_s . "
							
								 ORDER BY " . $orderby . "  )";
		
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





    function getHotelRefundReport($extraCheck_h, $extraCheck_or, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType)
    {
		$column_h_r = $this->get_columns_hotel_report_refund();
    $column_o_r = $this->get_columns_hotel_old_refund();

		$column_r = $this->get_columns_refund();
		$adminGroupListQuery = "(SELECT " . $column_r . " " . $column_h_r . " 
								FROM {PRE}HotelVoucher hv 
								LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
								LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
								LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
								LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
								INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId  AND rcd.vType  = 'Htl'
								INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
								LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
								LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation
								WHERE hv.vActive='1' 								
								".$extraCheck_h." " . $extraCheck_accounts_r . " )


                  UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                  FROM {PRE}RefundRegister rr
                  LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                  LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                   LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                  LEFT JOIN {PRE}Hotel htl ON htl.nHId = rrd.nHotel
                  LEFT JOIN {PRE}Country cty ON cty.nId = rrd.nCity
                  LEFT JOIN {PRE}Country crty ON crty.nId = rrd.nCountry
                  INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                   INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                  INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                    LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                    WHERE rr.vActive='1' AND rrd.vTktType ='Htl'
                  ".$extraCheck_or." " .$extraCheck_accounts_r . ")

							
								 ORDER BY " . $orderby . "  ";
		
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




	
}//end class
?>
