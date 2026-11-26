<?php
class Txh_report_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getActiveLocations()
    {
        $typeGroupRow = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getActiveGDS()
    {
        $gdsGroupRow = array();
        $gdsGroup = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");
        if ($gdsGroup->num_rows() > 0) {

 
            $gdsGroupRow[0] = ALL;
            foreach ($gdsGroup->result() as $key => $row) {
                $gdsGroupRow[$row->nGId] = $row->vName;
            }//end for each
            
        }//end if
        else {
            $gdsGroupRow[0] = NOVALUE;
        }//end else
        
        return ($gdsGroupRow);
    }//end function

    function getActiveEmployee()
    {
	
        $empGroup = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
											ORDER BY vName ASC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0) {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row) {
                $empGroupRow[$row->nEId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
            
        } //end if
        else {
            $empGroupRow[0] = NOVALUE;
        }//end else
        
        return ($empGroupRow);
    }//end function

    function getActiveCountry()
    {
 	
        $countryGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'
												ORDER BY vName ASC");
        $countryGroupRow = array();
        if ($countryGroup->num_rows() > 0) {
            $countryGroupRow[0] = SELECT;
            foreach ($countryGroup->result() as $key => $row) {
                $countryGroupRow[$row->nId] = stripslashes($row->vName);
            }//end for each
            
        } //end if
        else {
            $countryGroupRow[0] = NOVALUE;
        } //end else
       
        return ($countryGroupRow);
    } //end function
	
    function getActiveHotels()
    {
 	
        $hotelGroup = $this->db->query("SELECT nHId,vName FROM {PRE}Hotel WHERE vActive = '1' ORDER BY vName ASC");
        $hotelGroupRow = array();

        if ($hotelGroup->num_rows() > 0) {
            $hotelGroupRow[0] = SELECT;
            foreach ($hotelGroup->result() as $key => $row) {
                $hotelGroupRow[$row->nHId] = (stripslashes($row->vName));
            }//end for each
            
        } //end if
        else {
            $hotelGroupRow[0] = NOVALUE;
        }//end else
        
        return ($hotelGroupRow);
    }//end function

    function filterCity($country)
    {
        $cityGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."' ORDER BY nId DESC");
        $cityGroupRow = array();
        if ($cityGroup->num_rows() > 0) {
            $cityGroupRow[0] = SELECT;
            foreach ($cityGroup->result() as $key => $row) {
                $cityGroupRow[$row->nId] = stripslashes($row->vName);
            } //end for each
           
        } //end if
        else {
            $cityGroupRow[0] = NOVALUE;
        }//end else
        
        return ($cityGroupRow);
    } //end function

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
        $sessionLoc = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '" . $this->session->userdata('AbsAdmin') . "'");
        $locName = $sessionLoc->row();

		if($sessionLoc->num_rows() == 0)
			$locId = $this->common_model->getLocLogged();
		else
			$locId = $locName->vLocation;
			
        $locGroup = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '" . $locId. "'
											ORDER BY vCode ASC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) {
            $locGroupRow[0] = SELECT;
            foreach ($locGroup->result() as $key => $row) {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle) . ' (' . stripslashes($row->vCode) . ')';
            }//end for each
            
        } //end if
        else {
            $locGroupRow[0] = NOVALUE;
        } //end else
       
        return ($locGroupRow);
    }//end function

    function filterLocation($division)
    {
        $locGroup = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '" . $division . "'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY vCode ASC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) {
//            $locGroupRow[0] = SELECT;
            foreach ($locGroup->result() as $key => $row) {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle) . ' (' . stripslashes($row->vCode) . ')';
            }//end for each
            
        } //end if
        else {
            $locGroupRow[0] = NOVALUE;
        }
        //end else
        return ($locGroupRow);
    } //end function

    function getCorrActiveCity()
    {
        $corrCityGroupRow[0] = NOVALUE;
        return ($corrCityGroupRow);
    }//end function

    function getActiveVendor()
    {
 	
        $empGroup = $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster  WHERE vActive = '1' ORDER BY vCode ASC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0) {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row) {
                $empGroupRow[$row->nVId] = stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ')';
            }//end for each
            
        } //end if
        else {
            $empGroupRow[0] = NOVALUE;
        } //end else
       
        return ($empGroupRow);
    } //end function

    function getSubAccountValues()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return($typeGroupRow);
    }//end function
 
    function filterSubAccount($accGroup)
    {
	    $accheadGroupRow = array();
	    $accheadGroup= $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster WHERE vActive = '1' AND nFId = ".$accGroup . " 
														ORDER BY vName ASC");
        if ($accheadGroup->num_rows() > 0)
        {
		$accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key=>$row)
            {
                $accheadGroupRow[$row->nVId] = $row->vName .' ('.stripslashes($row->vCode).')';
            }//end for each
        }//end if
        else
        {
            $accheadGroupRow[0] = NOVALUE;
        }//end else
        return ($accheadGroupRow);
    }//end function
/*    function filterCity($country)
    {
        $locGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."' ORDER BY nId DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            $locGroupRow[0] = SELECT;
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return($locGroupRow);
    }//end function
*/ 

    function getActiveAirline()
    {
 	
        $empGroup = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline  WHERE vActive = '1' ORDER BY vTitle ASC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0) {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row) {
                $empGroupRow[$row->nArId] = stripslashes($row->vTitle) . ' (' . stripslashes($row->vAirlineNo) . ')';
            }//end for each
            
        } //end if
        else {
            $empGroupRow[0] = NOVALUE;
        }//end else
        
        return ($empGroupRow);
    }//end function    

    function mainSalesArray()
    {
        $mainSalesArrayList = array("All" => "All", "Tkt" => "Ticket Sales","Xo" => "XO Sales", "Htl" => "Hotel Sales");
        return ($mainSalesArrayList);
    }//end function

    function mainRefundArray()
    {
        $mainRefundArrayList = array("All" => "All", "Sales" => "Sales","Refund" => "Refund");
        return ($mainRefundArrayList);
    } //end function
	
    function mainStatusArray()
    {
 	
        $mainStatusArrayList = array("A" => "ALL", "N" => "Normal","C" => "Conjuction", "R" => "Reissued", "V" => "Void"
        );
        return ($mainStatusArrayList);
    }//end function    

    function mainReportArray()
    {
        $mainReportArrayList = array("Details" => "Details", "Summary" => "Summary",
            "By Year" => "By Year", "By Month" => "By Month"
        );
        return ($mainReportArrayList);
    }//end function
    
    function getAccounts()
    {
        $accGroupRow = array();
         $accGroup= $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode FROM {PRE}AccountHeadAccounts aha
INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId
WHERE ahc.vSubLedgerType = 'V' ORDER BY aha.vTitle ASC ");
			
//        $accGroup = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY vCode ASC");
        if ($accGroup->num_rows() > 0) {
            $accGroupRow[0] = SELECT;
            foreach ($accGroup->result() as $key => $row) {
                $accGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vACode).')';
            }  //end for each
          
        } //end if
        else {
            $accGroupRow[0] = NOVALUE;
        } //end else
       
        return ($accGroupRow);
    }//end function

/*
    function getAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
 				
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'sales' as refundtype, tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal, tb.vGdspnr
											FROM {PRE}TicketBooking tb 
											LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
											LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes' 
											".$extraCheck_t."
                                            ) 
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'sales' as refundtype, xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal, xo.vGdspnr FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' 
											".$extraCheck_x.")

											UNION ALL(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount, 'refund' as refundtype, rrd.vNetAmt2 as netAmt,tfd.vFaceValue as fareVal, tb.vGdspnr FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	 ".$extraCheck_t.")
                                             
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'refund' as refundtype, rrd.vNetAmt2 as netAmt,xpd.vFCTax as fareVal,xo.vGdspnr
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x."
											)
											
											ORDER BY airline ASC, sale_type ASC, refundtype DESC";
			
		
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr,  tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'sales' as refundtype, tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes' 
											".$extraCheck_t." )
											
									        UNION ALL(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'refund' as refundtype, rrd.vNetAmt2 as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
																		INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	 ".$extraCheck_t.")
											
											ORDER BY airline ASC, refundtype DESC";
											
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt, 
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'sales' as refundtype, xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal 
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' 
											".$extraCheck_x." )
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'refund' as refundtype, rrd.vNetAmt2 as netAmt,xpd.vFCTax as fareVal 
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x."
											)
											
											ORDER BY airline ASC, refundtype DESC";
											
											
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

    function getSalesAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT 'sales' as refundtype, tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr,  tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes'   ".$extraCheck_t."
											)
											
											UNION ALL (SELECT  'sales' as refundtype, xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   
											".$extraCheck_x.")
											 ORDER BY airline ASC, refundtype DESC";
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
                $adminGroupListQuery = "(SELECT 'sales' as refundtype, tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr,  tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes' 

  ".$extraCheck_t."
											 ORDER BY airline ASC, refundtype DESC)";
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
                $adminGroupListQuery = "(SELECT 'sales' as refundtype,  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' 

  ".$extraCheck_x." 											 
  											ORDER BY airline ASC, refundtype DESC)";
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'refund' as refundtype, rrd.vNetAmt2 as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
																		INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	 ".$extraCheck_t." ) 
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr,  xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'refund' as refundtype, rrd.vNetAmt2 as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." )
											ORDER BY airline ASC, refundtype DESC";
                
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'refund' as refundtype, rrd.vNetAmt2 as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId AND rrd.vTktType = 'Tkt'
											
											WHERE  tb.vConjStatus = 'Yes' 	 ".$extraCheck_t."
                                            ORDER BY airline ASC, refundtype DESC)";
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr,  xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'refund' as refundtype, rrd.vNetAmt2 as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." ORDER BY airline ASC, refundtype DESC)";
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

*/
/* 
    function getGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.vBookedBy,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nGDS,xo.vGdspnr,
                                            ' ' as lpo,xpd.vPass,xo.vBookedBy, xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xo.vBookedBy as BookedBy,
                                            xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            WHERE xo.vActive='1'  ".$extraCheck_x." ORDER BY xo.nXId DESC)";
                //echo $this->db->last_query();
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.vBookedBy,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt, tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nGDS,
                                            xo.vGdspnr,
                                            ' ' as lpo,xpd.vPass,xo.vBookedBy, xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt, xo.vBookedBy as BookedBy,
                                            xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            WHERE xo.vActive='1'  ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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

    function getSalesGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt, tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'  ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,
                                            xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nGDS,xo.vGdspnr,' ' as lpo,xpd.vPass,
                                            xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,
                                            xo.vBookedBy as BookedBy,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo
                                            LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId WHERE  xo.vActive='1' 
                                            ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb
                                            LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'    ".$extraCheck_t."
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy,
                                            xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nGDS,xo.vGdspnr,' ' as lpo,xpd.vPass,
                                            xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,
                                            xo.vBookedBy as BookedBy,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo
                                            LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId WHERE  xo.vActive='1' 
                                            ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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

    function getRefundGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nGDS,
                                            xo.vGdspnr,'' as lpo,xpd.vPass, xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xo.vBookedBy as BookedBy,'' as fareVal
                                            FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId WHERE
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

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tb.vBookedBy as BookedBy,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nGDS,xo.vGdspnr,' ' as lpo,xpd.vPass,
                                            xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,
                                            xo.vBookedBy as BookedBy,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo
                                            LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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

*/

//   
// function used by all hotel reports

/*
	function get_columns_hotel(){
		$columns_h = " hv.*,htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}
*/

	function get_columns_hotel_report_sales(){
		$columns_h = " '' as cmtAmount,hv.vTotalDC as netAmt, hv.vTotalRTDC, hv.vNight, hv.vNoOfUnit, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.nLocation, hv.dDate, hv.vHotelName, htl.vName as vName, hv.nCity, hv.nCountry, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}

	function get_columns_hotel_report_refund(){
		$columns_h = "  -rrd.vVendorAmt,
						-rrd.vNetAmt2 as netAmt,
						-hv.vTotalRTDC as vTotalRTDC, hv.vNight, hv.vNoOfUnit, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.nLocation, hv.dDate, hv.vHotelName,  htl.vName as vName,  hv.nCity, hv.nCountry, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}	

// function used by all hotel reports	
    function getHotelReport($extraCheck_h, $orderby)
    {
		$column_h_s = $this->get_columns_hotel_report_sales();
		$column_h_r = $this->get_columns_hotel_report_refund();
        $adminGroupListQuery = "SELECT " . $column_h_s . " , 'sales' as refundtype, hv.vTotalDC as vTotalDC
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											WHERE hv.vActive='1' 
											".$extraCheck_h."
											
											UNION ALL (SELECT  " . $column_h_r . " , 'refund' as refundtype, -rrd.vNetAmt2 as vTotalDC  
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
  											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' ".$extraCheck_h." )

											ORDER BY  " . $orderby . "  ";
											
/*
											UNION ALL (SELECT  " . $column_h . " , 'refund' as refundtype, rcd.vNetAmt as vTotalDC  
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
  											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl' ".$extraCheck_h." )
*/
	
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


// function used by all hotel reports
    function getSalesHotelReport($extraCheck_h, $orderby)
    {
		$column_h = $this->get_columns_hotel_report_sales();
        $adminGroupListQuery = "(SELECT  " . $column_h . " , 'sales' as refundtype  
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
											LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId	
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											WHERE hv.vActive='1'  
											".$extraCheck_h."
                                            ORDER BY  " . $orderby . "  )";

		
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

// function used by all hotel reports
    function getRefundHotelReport($extraCheck_h, $orderby)
    {
		$column_h = $this->get_columns_hotel_report_refund();
        $adminGroupListQuery = "(SELECT  " . $column_h . " , 'refund' as refundtype, -rrd.vNetAmt2 as vTotalDC  
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
  											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' ".$extraCheck_h." )
											
											ORDER BY  " . $orderby . "  ";
/*
											UNION ALL (SELECT  " . $column_h . " , 'refund' as refundtype, rcd.vNetAmt as vTotalDC  
											FROM {PRE}HotelVoucher hv 
											LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
  											LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
											LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl' ".$extraCheck_h." )


*/
											
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
    function getHotelCheckInReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE hv.vActive='1' ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getHotelCheckInSalesReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN
											 {PRE}HotelBillingDetails hbd	ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON
											 hv.vIssuedBy = em.nEId	LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											 LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											 WHERE hv.vActive='1' ".$extraCheck_h."
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

    function getHotelCheckInRefundReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha
                                            ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getHotelCheckOutReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE hv.vActive='1' ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getHotelCheckOutSalesReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN
											 {PRE}HotelBillingDetails hbd	ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON
											 hv.vIssuedBy = em.nEId	LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											 LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											 WHERE hv.vActive='1' ".$extraCheck_h."
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

    function getHotelCheckOutRefundReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha
                                            ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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


/*
    function getGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,td.vDestination, tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM {PRE}TicketBooking
                                            tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN {PRE}TicketBillingDetails tbd
                                            ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
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
        return ($adminGroupRow);
    }//end function

    function getSalesGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tb.nGDS,td.vDestination, tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'    ".$extraCheck_t."
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

    function getRefundGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate, tb.nGDS,td.vDestination, tb.vGdspnr,
                                            ' ' as lpo,tb.vPassenger,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,
                                            tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
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
        return ($adminGroupRow);
    }//end function
*/

    function getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $orderby, $SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus,
tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal , 'sales' as refundtype 
											FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'					
											 ".$extraCheck_t." )
                                            
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt,hv.vTotalRTDC as fareVal ,  'sales' as refundtype 
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1'
											 ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  xo.nXId as nTId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,


                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' 
											".$extraCheck_x." ) 
												
											UNION ALL (SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,

                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,-tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
											-rrd.vNetAmt2 as netAmt,
                                            -tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,-rrd.vVendorAmt as cmtAmount, -rrd.vNetAmt2 as netAmt ,-hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,-xpd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
                                             -rrd.vNetAmt2 as netAmt,-xpd.vFCTax as fareVal, 'refund' as refundtype
											 FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." )
											
																										
											ORDER BY " . $orderby . " ";
									
/*						
											UNION ALL (SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,
											rcd.vNetAmt as netAmt,
                                            tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rcd.vType = 'Tkt' 	  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount, rcd.vNetAmt as netAmt ,hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl'
                                            ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                             rcd.vNetAmt as netAmt,xpd.vFCTax as fareVal, 'refund' as refundtype
											 FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = xo.nXId
											WHERE  xo.vActive='1' AND rcd.vType = 'Xo' ".$extraCheck_x." )
*/
												
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
                //echo $this->db->last_query();
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,

                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'		
											 ".$extraCheck_t." )
												
											UNION ALL (SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,

                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
											-rrd.vNetAmt2 as netAmt,
                                            -tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t.") 

                                            ORDER BY " . $orderby . " ";
											
/*											UNION ALL (SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,
											rcd.vNetAmt  as netAmt,
                                            tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rcd.vType = 'Tkt' 	  ".$extraCheck_t.") 
*/
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, 
                                            xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as
                                            ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,xo.nPassengerAc as account,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,
                                            xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId LEFT JOIN {PRE}EmployeeMaster em
                                            ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   ".$extraCheck_x." )
												
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,-xpd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
                                            -rrd.vNetAmt2 as netAmt,-xpd.vFCTax as fareVal, 'refund' as refundtype
											 FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." )
					
											ORDER BY " . $orderby . " ";
																
/*											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                             rcd.vNetAmt as netAmt,xpd.vFCTax as fareVal, 'refund' as refundtype
											 FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId
											WHERE  xo.vActive='1' AND rcd.vType = 'Xo' ".$extraCheck_x." )
					
*/					
	
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hv.vAgentConfId as vGdspnr , hv.vType as vStatus,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt,hv.vTotalRTDC as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE								
                                            hv.vActive='1' 
												 ".$extraCheck_h." )
												
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,-rrd.vVendorAmt as cmtAmount, -rrd.vNetAmt2 as netAmt ,-hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h." )
												 
											ORDER BY " . $orderby . " ";										
											
/*											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount, rcd.vNetAmt as netAmt ,hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl' 
                                            ".$extraCheck_h." )
*/
	
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

    function getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$orderby,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'		
											    ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hv.vAgentConfId as vGdspnr , hv.vType as vStatus,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt,hv.vTotalRTDC as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1'  
											".$extraCheck_h." ) 
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal 
											, 'sales' as refundtype 
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   
											".$extraCheck_x.") ORDER BY  " . $orderby . " ";
										
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vType,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFaceValue as fareVal 
											, 'sales' as refundtype  
											FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'							
											".$extraCheck_t."
                                            ORDER BY  " . $orderby . " )";
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation, xo.vGdspnr, xo.vType as vStatus, 
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vType,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal 
											, 'sales' as refundtype  
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   
											".$extraCheck_x." ORDER BY  " . $orderby . " )";
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt,hv.vTotalRTDC as fareVal 
											, 'sales' as refundtype  
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1'
											".$extraCheck_h." ORDER BY  " . $orderby . " )";
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

    function getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$orderby,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
											-rrd.vNetAmt2 as netAmt,
                                            -tfd.vFaceValue as fareVal, 'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,-rrd.vVendorAmt as cmtAmount, -rrd.vNetAmt2 as netAmt ,-hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,-xpd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
                                            -rrd.vNetAmt2 as netAmt,-xpd.vFCTax as fareVal, 'refund' as refundtype
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
										WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x.")
												
											 ORDER BY  " . $orderby . " ";									
										

/*										
											UNION ALL (SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,
											rcd.vNetAmt as netAmt,
                                            tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rcd.vType = 'Tkt' 	  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount, rcd.vNetAmt as netAmt ,hv.vTotalRTDC as fareVal, 'refund' as refundtype
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl'
                                            ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                             rcd.vNetAmt as netAmt,xpd.vFCTax as fareVal, 'refund' as refundtype
											 FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = xo.nXId
											WHERE  xo.vActive='1' AND rcd.vType = 'Xo' ".$extraCheck_x." )
*/
							
							
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,'refund' as refundtype, -rrd.vNetAmt2 as  netAmt,
                                            -tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
																		INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t." )

                                            ORDER BY  " . $orderby . " ";
/*
											UNION ALL(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,'refund' as refundtype, rcd.vNetAmt as  netAmt,
                                            tfd.vFaceValue as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rcd.vType = 'Tkt' 	  ".$extraCheck_t.")
											
*/
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,-xpd.vTaxTotal as taxTotal,-rrd.vVendorAmt as cmtAmount,
                                            'refund' as refundtype, -rrd.vNetAmt2 as netAmt,-xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x.")
											
											ORDER BY  " . $orderby . " ";
																						
/*											UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            xo.nPassengerAc as account,
                                            ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            'refund' as refundtype, rcd.vNetAmt as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation 
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = xo.nXId
											WHERE  xo.vActive='1' AND rcd.vType = 'Xo' ".$extraCheck_x.")
											
*/											


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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,-rrd.vVendorAmt as cmtAmount,'refund' as refundtype, -rrd.vNetAmt2 as netAmt,-hv.vTotalRTDC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h." )

											ORDER BY  " . $orderby . " ";
																				
/*											UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'refund' as refundtype, rcd.vNetAmt as netAmt,hv.vTotalRTDC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId 
											WHERE hv.vActive='1' AND rcd.vType = 'Htl' 
                                            ".$extraCheck_h." )
*/											

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

/*
    function getEmployeeReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {	
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT " . $columns_t . "FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'									AND tb.nTId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Tkt')  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT " . $columns_h . " FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1' AND hv.nHVId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Htl') ".$extraCheck_h." )
											
											
											UNION ALL (SELECT  ". $columns_x . " FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   AND xo.nXId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Xo') ".$extraCheck_x." ) ORDER BY vIssuedBy ASC";
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
                $adminGroupListQuery = "(SELECT " . $columns_t . " FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'									AND tb.nTId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Tkt')  ".$extraCheck_t."
                                            ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT " . $columns_x . "  FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId LEFT JOIN {PRE}EmployeeMaster em
                                            ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE
                                            xo.vActive='1'   AND xo.nXId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Xo') ".$extraCheck_x." ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT " . $columns_h . " FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1' AND hv.nHVId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Htl')  ".$extraCheck_h." ORDER BY vIssuedBy ASC)";
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

    function getEmployeeSalesReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT " . $columns_t . "  FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'									AND tb.nTId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Tkt')     ".$extraCheck_t.")
											
											
											UNION ALL (SELECT " . $columns_h . " FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1'
											AND hv.nHVId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Htl')   
                                            ".$extraCheck_h.")
											
											
											 UNION ALL (SELECT  " . $columns_x . "  FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   AND xo.nXId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Xo') ".$extraCheck_x.") ORDER BY vIssuedBy ASC";
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
                $adminGroupListQuery = "(SELECT " . $columns_t . " FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE 		tb.vConjStatus = 'Yes'									AND tb.nTId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Tkt')     ".$extraCheck_t."
                                            ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT  " . $columns_x . "  FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1'   AND xo.nXId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Xo') ".$extraCheck_x." ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT " . $columns_h . "  FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											WHERE
                                            hv.vActive='1'
											AND hv.nHVId NOT IN 
												(SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd 
												WHERE rrd.vTktType = 'Htl')   
                                            ".$extraCheck_h." ORDER BY vIssuedBy ASC)";
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

    function getEmployeeRefundReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT " . $columns_t . " ,'refund' as refundtype, rrd.vNetAmt2 as netAmt FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
																		INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t.") 
											
											
											UNION ALL (SELECT " . $columns_h . "  ,'refund' as refundtype, rrd.vNetAmt2 as netAmt 
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h.") 
											
											
											UNION ALL (SELECT  " . $columns_x . " ,'refund' as refundtype, rrd.vNetAmt2 as netAmt
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." ) ORDER BY vIssuedBy ASC";
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
                $adminGroupListQuery = "(SELECT " . $columns_t . " ,'refund' as refundtype, rrd.vNetAmt2 as netAmt
											FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
																		INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
											WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	  ".$extraCheck_t."
                                            ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT  " . $columns_x . "  
											,'refund' as refundtype, rrd.vNetAmt2 as netAmt
											FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
											WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' ".$extraCheck_x." ORDER BY vIssuedBy ASC)";
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
                $adminGroupListQuery = "(SELECT " . $columns_h . " ,'refund' as refundtype, rrd.vNetAmt2 as netAmt
											FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
											LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
											INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
											WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
                                            ".$extraCheck_h." ORDER BY vIssuedBy ASC)";
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
*/

// angela
/*
    function getSectorReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT " . $columns_t . "
											 FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) 
											
											UNION ALL (SELECT  " . $columns_x . " FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
											LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
											WHERE tb.vConjStatus = 'Yes' ".$extraCheck_t."
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus,  xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId LEFT JOIN
                                            {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc 
											LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
											WHERE xo.vActive='1' ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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

    function getSectorSalesReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'   ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN
                                            {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1'   ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus,  tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'   ".$extraCheck_t."
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN
                                            {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1'   ".$extraCheck_x." ORDER BY xo.nXId DESC)";
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

    function getSectorRefundReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN
                                            {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tb.vGdspnr, tb.vType as vStatus,  tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN
                                            {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
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
	
*/
// angela
	
}//end class
?>
