<?php
class Gds_report_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function
/*
    function getPayableAccount()
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
 */
    function getPayableAccount()
    {
        $empGroup    = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode FROM {PRE}AccountHeadAccounts aha
INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId
WHERE ahc.vSubLedgerType = 'V' ORDER BY aha.vTitle ASC ");
	$empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vACode).')';
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
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
			$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==48 || $aid==46){
					$locGroupRow[0]=SELECT;
				}
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
    function getActiveAccounts()
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
														ORDER BY vName ASC ");
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
 


    function mainSalesArray()
    {
        $mainSalesArrayList = array("All" => "All","Ticket Sales" => "Ticket Sales",
            "XO Sales" => "XO Sales","Hotel Sales" => "Hotel Sales"
        );
        return ($mainSalesArrayList);
    }//end function
    function mainRefundArray()
    {
        $mainRefundArrayList = array("All" => "All","Sales" => "Sales",
            "Refund" => "Refund"
        );
        return ($mainRefundArrayList);
    }//end function
	
    function mainOpenClosedArray()
    {
        $mainOpenClosedArrayList = array("All" => "All", "Open" => "Open",
            "Closed" => "Closed"
        );
        return ($mainOpenClosedArrayList);
    }//end function

    function mainStatusArray()
    {
 	
        $mainStatusArrayList = array("A" => "ALL", "N" => "Normal","C" => "Conjuction", "R" => "Reissued", "V" => "Void"
        );
        return ($mainStatusArrayList);
    }//end function    
/*
    function mainStatusArray()
    {
        $mainStatusArrayList = array("All" => "All","Normal" => "Normal",
            "Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void"
        );
        return ($mainStatusArrayList);
    }//end function
*/
    function mainReportArray()
    {
        $mainReportArrayList = array("Net Fare" => "Net Fare","Sales" => "Sales"
        );
        return ($mainReportArrayList);
    }//end function
    function mainReportTypeArray()
    {
        $mainReportTypeArrayList = array("Details" => "Details","Summary" => "Summary"
        );
        return ($mainReportTypeArrayList);
    }//end function
	
/*	
    function getAirlineReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
    function getSalesAirlineReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
        return ($adminGroupRow);
    }//end function
    function getRefundAirlineReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
        return ($adminGroupRow);
    }//end function
    function getAirlineSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId, tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                rcd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nAirline ORDER BY xo.nXId DESC)";
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
    function getSalesAirlineSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                scd WHERE scd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nAirline ORDER BY xo.nXId DESC)";
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
    function getRefundAirlineSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nAirline ORDER BY xo.nXId DESC)";
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
    function getGdsReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                xo.nGDS,xo.vAirpnr,' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
    function getSalesGdsReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                tfd.nNetVendor as collected,tb.nTId as tableId,
                                tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                xo.nGDS,xo.vAirpnr,
                                ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
        return ($adminGroupRow);
    }//end function
    function getRefundGdsReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                    tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                    tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                    tfd.nNetVendor as collected,tb.nTId as tableId,
                                    tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                    {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                    {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                    ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                    LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                    IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                    ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                    'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                    xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                    xo.nGDS,xo.vAirpnr,
                                    ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
        return ($adminGroupRow);
    }//end function
    function getGdsSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                 tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                 tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                 tfd.nNetVendor as collected,tb.nTId as tableId,
                                 tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                 {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                 {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                 ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                 LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                 IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                 IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                 GROUP BY tb.nGDS ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                 'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                 xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                 xo.nGDS,xo.vAirpnr,
                                 ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                 xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                 LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                 LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                 aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                 scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                 rcd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nGDS ORDER BY xo.nXId DESC)";
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
    function getSalesGdsSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                 tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                 tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                 tfd.nNetVendor as collected,tb.nTId as tableId,
                                 tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                 {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                 {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                 ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                 LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                 IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                 GROUP BY tb.nGDS ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                 'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                 xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                 xo.nGDS,xo.vAirpnr,
                                 ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                 xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                 LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                 LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                 aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                 scd WHERE scd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nGDS ORDER BY xo.nXId DESC)";
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
    function getRefundGdsSummaryReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                 tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                 tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                 tfd.nNetVendor as collected,tb.nTId as tableId,
                                 tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                 {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                 {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                 ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                 LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                 IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                 GROUP BY tb.nGDS ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                 'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                 xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                 xo.nGDS,xo.vAirpnr,
                                 ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                 xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                 LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                 LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                 aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                 {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." GROUP BY xo.nGDS ORDER BY xo.nXId DESC)";
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
    function getAirlineSalesTotal($airline)
    {
        $extraCheck_t = $this->session->userdata('extraCheck_t');
        $extraCheck_x = $this->session->userdata('extraCheck_x');
        $adminGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                            AND tb.nAirline = '".$airline."'
                                            GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') ".$extraCheck_x." AND xo.nAirline = '".$airline."' GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['fareVal']  = $row->fareVal;
                $adminGroupRow['taxTotal'] = $row->taxTotal;
                $adminGroupRow['netAmt']   = $row->netAmt;
                $adminGroupRow['salesAmt'] = $row->salesAmt;
                $adminGroupRow['vSegment'] = $row->vSegment;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
    function getAirlineRefundTotal($airline)
    {
        $extraCheck_t = $this->session->userdata('extraCheck_t');
        $extraCheck_x = $this->session->userdata('extraCheck_x');
        $adminGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            AND tb.nAirline = '".$airline."'
                                            GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." AND xo.nAirline = '".$airline."'
                                            GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['fareVal']  = $row->fareVal;
                $adminGroupRow['taxTotal'] = $row->taxTotal;
                $adminGroupRow['netAmt']   = $row->netAmt;
                $adminGroupRow['salesAmt'] = $row->salesAmt;
                $adminGroupRow['vSegment'] = $row->vSegment;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
    function getGDSSalesTotal($gds)
    {
        $extraCheck_t = $this->session->userdata('extraCheck_t');
        $extraCheck_x = $this->session->userdata('extraCheck_x');
        $adminGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                            AND tb.nGDS = '".$gds."'
                                            GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') ".$extraCheck_x." AND xo.nGDS = '".$gds."' GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['fareVal']  = $row->fareVal;
                $adminGroupRow['taxTotal'] = $row->taxTotal;
                $adminGroupRow['netAmt']   = $row->netAmt;
                $adminGroupRow['salesAmt'] = $row->salesAmt;
                $adminGroupRow['vSegment'] = $row->vSegment;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
    function getGDSRefundTotal($gds)
    {
        $extraCheck_t = $this->session->userdata('extraCheck_t');
        $extraCheck_x = $this->session->userdata('extraCheck_x');
        $adminGroupList = $this->db->query("(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tb.nTId as tableId,tb.nGDS,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            AND tb.nGDS = '".$gds."'
                                            GROUP BY tb.nAirline ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,xo.nXId as tableId,xo.nGDS,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM
                                            {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') ".$extraCheck_x." AND xo.nGDS = '".$gds."'
                                            GROUP BY xo.nAirline ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['fareVal']  = $row->fareVal;
                $adminGroupRow['taxTotal'] = $row->taxTotal;
                $adminGroupRow['netAmt']   = $row->netAmt;
                $adminGroupRow['salesAmt'] = $row->salesAmt;
                $adminGroupRow['vSegment'] = $row->vSegment;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
*/


    function getDetailReport($extraChecks,$OpenClosed,$RefundType,$GDS,$orderby)
    {
	
		// set common columns
		$column_t = "tb.nTId as txId, tb.nGDS, tb.nAirline as airline, tb.vTicktManual as tktNumber, tb.vPassenger, tfd.vFaceValue as fareVal , tfd.vTaxTotal as taxTotal, tfd.nNetVendor as netAmt, 'T' vSegment, 'Ticket' as sale_type ";
		$column_t_r = "tb.nTId as txId, tb.nGDS, tb.nAirline as airline, tb.vTicktManual as tktNumber, tb.vPassenger, -tfd.vFaceValue as fareVal , -tfd.vTaxTotal as taxTotal, tfd.nNetVendor as netAmt, 'T' vSegment, 'Ticket' as sale_type ";

		$extraChecks['extraCheck_sales_closed'] .= " AND scd.vTktNumber NOT IN (SELECT tb.nTId FROM
                                            {PRE}TicketBooking tb)";

		$extraChecks['extraCheck_ref_closed'] .= " AND rcd.nTicketNumber  NOT IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt')";
											

		
//		$column_x = "  xo.nXId as txId, xo.nGDS, xo.nAirline as airline, xo.vTicktManual as tktNumber, xpd.vPass as vPassenger, xpd.vFCTax as fareVal, xpd.vTaxTotal as taxTotal, xpd.nNetVendor as netAmt, xo.vSegment,  'Xo' as sale_type ";
	
		// common joins
		$join_t = "{PRE}TicketBooking tb 
						LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId 		
						LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId  ";
						
/*		$join_x = "{PRE}XoBooking xo 
						LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId ";			
*/	
		//build queries
		$query_sales_open    = " ( SELECT " . $column_t . " , tb.dDate, tbd.vSalesAmt as salesAmt,  'sales' as refundtype
									FROM " . $join_t . "
									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId									
									LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 									
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."  " . $extraChecks['extraCheck_t_sales_open'] . " ) ";
									
								
/*								 UNION ALL ( SELECT " . $column_x . " , xo.dDate , xbd.vSalesAmt as salesAmt
									FROM " . $join_x . "								
									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
								    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 																			
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_x_sales_open'] . "
								) ";
								
*/								
		$colum = "tb.nTId as txId, tb.nGDS, tb.nAirline as airline,tb.vTicktManual as tktNumber, tb.vPassenger, -tfd.vFaceValue as fareVal , -tfd.vTaxTotal as taxTotal, -rrd.vNetAmt2 as netAmt, 'T' vSegment, 'Ticket' as sale_type ";
		
						if($GDS==1){
							
		$query_refund_open = " ( SELECT " . $colum . " , rr.dDate, -rrd.vPaidTo as salesAmt,  'refund' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes'  AND rrd.vTktType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . " ) 
							UNION ALL(SELECT rr.nRId as nTId,rr.nGDS as nGDS,ar.nArId as airline,rrd.nTktNum as tktNumber,rrd.vPassenger as vPassenger,-rrd.vFareAmt as fareVal,-rrd.vTax as taxTotal,-rrd.vNetAmt2 as netAmt,'R' vSegment,'Ticket' as sale_type,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate, rrd.vPaidTo as salesAmt,'refund' as refundtype
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          
                          LEFT JOIN tbl_Airline ar ON ar.vAirlineNo = rrd.vAirline
                        
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='1' " .$extraChecks['extraCheck_ref_open']." )	";}
						  elseif($GDS==2){
		$query_refund_open = " ( SELECT " . $colum . " , rr.dDate, -rrd.vPaidTo as salesAmt,  'refund' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes'  AND rrd.vTktType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . " ) 
							UNION ALL(SELECT rr.nRId as nTId,rr.nGDS as nGDS,ar.nArId as airline,rrd.nTktNum as tktNumber,rrd.vPassenger as vPassenger,-rrd.vFareAmt as fareVal,-rrd.vTax as taxTotal,-rrd.vNetAmt2 as netAmt,'R' vSegment,'Ticket' as sale_type,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate, -rrd.vPaidTo as salesAmt,'refund' as refundtype
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          
                          LEFT JOIN tbl_Airline ar ON ar.vAirlineNo = rrd.vAirline
                        
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='2' " .$extraChecks['extraCheck_ref_open']." )	";}
						  elseif($GDS==3){
		$query_refund_open = " ( SELECT " . $colum . " , rr.dDate, -rrd.vPaidTo as salesAmt,  'refund' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes'  AND rrd.vTktType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . " ) 
							UNION ALL(SELECT rr.nRId as nTId,rr.nGDS as nGDS,ar.nArId as airline,rrd.nTktNum as tktNumber,rrd.vPassenger as vPassenger,-rrd.vFareAmt as fareVal,-rrd.vTax as taxTotal,-rrd.vNetAmt2 as netAmt,'R' vSegment,'Ticket' as sale_type,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate, -rrd.vPaidTo as salesAmt,'refund' as refundtype
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          
                          LEFT JOIN tbl_Airline ar ON ar.vAirlineNo = rrd.vAirline
                        
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='3' " .$extraChecks['extraCheck_ref_open']." )	";}
						  elseif($GDS==0){
		$query_refund_open = " ( SELECT " . $colum . " , rr.dDate, -rrd.vPaidTo as salesAmt,  'refund' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes'  AND rrd.vTktType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . " ) 
							UNION ALL(SELECT rr.nRId as nTId,rr.nGDS as nGDS,ar.nArId as airline,rrd.nTktNum as tktNumber,rrd.vPassenger as vPassenger,-rrd.vFareAmt as fareVal,-rrd.vTax as taxTotal,-rrd.vNetAmt2 as netAmt,'R' vSegment,'Ticket' as sale_type,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate, -rrd.vPaidTo as salesAmt,'refund' as refundtype
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          
                          LEFT JOIN tbl_Airline ar ON ar.vAirlineNo = rrd.vAirline
                        
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' " .$extraChecks['extraCheck_ref_open']." )	";}
						 				  
/*								

						  
						  
 UNION ALL ( SELECT " . $column_x . " , rr.dDate, rrd.vPaidTo as salesAmt
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_ref_open'] . "
								) ";
*/																
								
		$query_sales_closed = " ( SELECT " . $column_t . " , sc.dDate, scd.vCollected as salesAmt,'sales' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId 
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE tb.vConjStatus = 'Yes' AND scd.vType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_sales_closed'] . " ) ";
								
/*								 UNION ALL ( SELECT " . $column_x . " , sc.dDate, scd.vCollected as salesAmt
									FROM " . $join_x . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_sales_closed'] . "
								) ";
*/								
								
		$col = "tb.nTId as txId, tb.nGDS, tb.nAirline as airline, tb.vTicktManual as tktNumber, tb.vPassenger, -tfd.vFaceValue as fareVal , -tfd.vTaxTotal as taxTotal, -rcd.vNetAmt as netAmt, 'T' vSegment, 'Ticket' as sale_type ";
		$query_refund_closed = " ( SELECT " . $col . " , rc.dDate, -rcd.vPaidAmt as salesAmt,'refund' as refundtype
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE tb.vConjStatus = 'Yes'  AND rcd.vType = 'Tkt'
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_closed'] . " ) 
								";
/*								 

UNION ALL(SELECT rr.nRId as nTId,rr.nGDS as nGDS,ar.nArId as airline,rrd.nTktNum as tktNumber,rrd.vPassenger as vPassenger,-rrd.vFareAmt as fareVal,-rrd.vTax as taxTotal,-rrd.vNetAmt2 as netAmt,'T' vSegment,'Ticket' as sale_type,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate, rrd.vSalesAmt as salesAmt,'refund' as refundtype
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          
                          LEFT JOIN tbl_Airline ar ON ar.vAirlineNo = rrd.vAirline
                        
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' )
						  
						  
UNION ALL ( SELECT " . $column_x . " , rc.dDate, rcd.vPaidAmt as salesAmt
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE xo.vActive='1' 
									".$extraChecks['extraCheck_x']."   " . $extraChecks['extraCheck_ref_closed'] . "
								) ";
*/
		// select appropriate query
		switch($OpenClosed)
		{
			case 'All':
				switch($RefundType) {
					case 'All':  $adminGroupListQuery =  $query_sales_open . "  UNION ALL  " . $query_refund_open . "  UNION ALL  " . $query_sales_closed . "  UNION ALL  " . $query_refund_closed . "";	break;					
					case 'Sales':  $adminGroupListQuery = " " . $query_sales_open . "  UNION ALL  " . $query_sales_closed . "  ";	break;
					case 'Refund': $adminGroupListQuery =  " " . $query_refund_open . "  UNION ALL  " . $query_refund_closed . " ";	break;
				}
				break;
			case 'Open':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery = " " . $query_sales_open . "  UNION ALL  " . $query_refund_open . " "; break;					
					case 'Sales':  $adminGroupListQuery = $query_sales_open ; break;
					case 'Refund': $adminGroupListQuery =  $query_refund_open ;	break;
				}
				break;
			case 'Closed':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery  ="  " . $query_sales_closed . "  UNION ALL  " . $query_refund_closed . " "; break;					
					case 'Sales':  $adminGroupListQuery  =$query_sales_closed;	break;
					case 'Refund': $adminGroupListQuery  =$query_refund_closed;	break;
				}
				break;
		}
		
		
        $adminGroupListQuery .= " ORDER BY " . $orderby ;
		
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




   
    function getSummaryReport($extraChecks,$OpenClosed,$RefundType,$orderby, $groupby,$GDS, $extra_column_main = ',', $extra_column_t = ',', $extra_column_x = ','  )
    {
		
		
		
		
		
		// set common columns
		$column_t_s = " tb.nGDS " . $extra_column_t . " 
						SUM(tfd.vFaceValue) as fareVal_s , SUM(tfd.vTaxTotal) as taxTotal_s, SUM(tfd.nNetVendor) as netAmt_s, SUM(td.vSegment) as vSegment_s,
						'' as fareVal_r , '' as taxTotal_r, '' as netAmt_r, '' as vSegment_r ";						
/*		$column_x_s = " xo.nGDS " . $extra_column_x . "  
						SUM(xpd.vFCTax) as fareVal_s, SUM(xpd.vTaxTotal) as taxTotal_s, SUM(xpd.nNetVendor) as netAmt_s,  SUM(xo.vSegment) as vSegment_s,  
						'' as fareVal_r , '' as taxTotal_r, '' as netAmt_r, '' as vSegment_r ";						
*/
		$column_t_r = " tb.nGDS " . $extra_column_t . "
					 	'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, 
					SUM(-tfd.vFaceValue) as fareVal_r , SUM(-tfd.vTaxTotal) as taxTotal_r, SUM(-tfd.nNetVendor) as netAmt_r, SUM(-td.vSegment) as vSegment_r ";
/*		$column_x_r = " xo.nGDS " . $extra_column_x . "
						 '' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, 
						 SUM(xpd.vFCTax) as fareVal_r, SUM(xpd.vTaxTotal) as taxTotal_r, SUM(xpd.nNetVendor) as netAmt_r,  SUM(xo.vSegment) as vSegment_r  ";
*/	
		// common joins
		$join_t = "{PRE}TicketBooking tb 
						LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId 
						LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId  ";
						
//		$join_x = "{PRE}XoBooking xo 
//						LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId";			
	
		//build queries
		$query_sales_open    = " ( SELECT " . $column_t_s . "  , SUM(tbd.vSalesAmt) as salesAmt_s, '' as salesAmt_r
									FROM " . $join_t . "
									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId									
									LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 									
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."  " . $extraChecks['extraCheck_t_sales_open'] . " 
									GROUP BY " . $groupby . " ) " ;
/*									
								
								UNION ALL ( SELECT " . $column_x_s . " , SUM(xbd.vSalesAmt) as salesAmt_s,  '' as salesAmt_r
									FROM " . $join_x . "								
									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
								    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 																			
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_x_sales_open'] . "
									GROUP BY " . $groupby . "
								) ";
								
*/			$column_t_r = " tb.nGDS " . $extra_column_t . "
					 	'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, 
					SUM(-tfd.vFaceValue) as fareVal_r , SUM(-tfd.vTaxTotal) as taxTotal_r, SUM(-rrd.vNetAmt2) as netAmt_r, SUM(-td.vSegment) as vSegment_r ";					
								
		if($GDS==1){
			$query_refund_open = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . " )UNION ALL 

(SELECT rr.nGDS as nGDS,'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, SUM(-rrd.vFareAmt) as fareVal_r,SUM(-rrd.vTax) as taxTotal_r,SUM(-rrd.vNetAmt2) as netAmt_r,SUM(-rr.vSegment) as vSegment_r, '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='1')";	
		}elseif($GDS==2){
			$query_refund_open = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . " )UNION ALL 

(SELECT rr.nGDS as nGDS,'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, SUM(-rrd.vFareAmt) as fareVal_r,SUM(-rrd.vTax) as taxTotal_r,SUM(-rrd.vNetAmt2) as netAmt_r,SUM(-rr.vSegment) as vSegment_r, '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='2')";}
						  elseif($GDS==3){
							  $query_refund_open = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . " )UNION ALL 

(SELECT rr.nGDS as nGDS,'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, SUM(-rrd.vFareAmt) as fareVal_r,SUM(-rrd.vTax) as taxTotal_r,SUM(-rrd.vNetAmt2) as netAmt_r,SUM(-rr.vSegment) as vSegment_r, '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' AND rr.nGDS='3')";}
						  elseif($GDS==0){
							  $query_refund_open='';
							  $query_refund_open = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . " )UNION ALL 

(SELECT rr.nGDS as nGDS,'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, SUM(-rrd.vFareAmt) as fareVal_r,SUM(-rrd.vTax) as taxTotal_r,SUM(-rrd.vNetAmt2) as netAmt_r,SUM(-rr.vSegment) as vSegment_r, '' as salesAmt_s, SUM(-rrd.vPaidTo) as salesAmt_r
                          FROM tbl_RefundRegister rr
                          LEFT JOIN tbl_RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                          LEFT JOIN tbl_Location loc ON loc.nId = rr.nLocation
                          WHERE rr.vActive='1' AND rrd.vNewOld = 'O' GROUP BY rr.nGDS)";}
		
								
/*								  UNION ALL ( SELECT " . $column_x_r . " ,  '' as salesAmt_s, SUM(rrd.vPaidTo) as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . "									
								) ";
*/																
								
		$query_sales_closed = " ( SELECT " . $column_t_s . " , SUM(scd.vCollected) as salesAmt_s,  '' as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId 
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_sales_closed'] . "
									GROUP BY " . $groupby . " ) " ;									
								
/*								 UNION ALL ( SELECT " . $column_x_s . " , SUM(scd.vCollected) as salesAmt_s, '' as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_sales_closed'] . "
									GROUP BY " . $groupby . "									
								) ";
*/								
								
		$query_refund_closed = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(-rcd.vPaidAmt) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE tb.vConjStatus = 'Yes'  
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_closed'] . "
									GROUP BY " . $groupby . " ) ";									
								
/*								 UNION ALL ( SELECT " . $column_x_r. " , '' as salesAmt_s, SUM(rcd.vPaidAmt) as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE xo.vActive='1' 
									".$extraChecks['extraCheck_x']."   " . $extraChecks['extraCheck_ref_closed'] . "
									GROUP BY " . $groupby . "									
								) ";
*/
		// select appropriate query
		switch($OpenClosed)
		{
			case 'All':
				switch($RefundType) {
					//  UNION ALL  " . $query_sales_closed . "  Sales UNION ALL  " . $query_refund_closed . " Refund
					//case 'All':  $adminGroupListQuery =  $query_sales_open . "  UNION ALL  " . $query_refund_open . "";	break;
					case 'All':  $adminGroupListQuery =  $query_sales_open . "  UNION ALL  " . $query_refund_open . "";	break;					
					case 'Sales':  $adminGroupListQuery = " " . $query_sales_open . "";	break;
					case 'Refund': $adminGroupListQuery =  " " . $query_refund_open . " ";	break;
				}
				break;
			case 'Open':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery = " " . $query_sales_open . "  UNION ALL  " . $query_refund_open . " "; break;					
					case 'Sales':  $adminGroupListQuery = $query_sales_open ; break; 
					case 'Refund': $adminGroupListQuery =  $query_refund_open ;	break; 
				}
				break;
			case 'Closed':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery  ="  " . $query_sales_closed . "  UNION ALL  " . $query_refund_closed . " "; break;					
					case 'Sales':  $adminGroupListQuery  =$query_sales_closed;	break;
					case 'Refund': $adminGroupListQuery  =$query_refund_closed;	break;
				}
				break;
		}
		
		
       
	     $adminGroupListQuery_summary .= " SELECT DISTINCT  nGDS " . $extra_column_main . " 
						SUM(fareVal_s) as fareVal_s , SUM(taxTotal_s) as taxTotal_s, SUM(netAmt_s) as netAmt_s, SUM(vSegment_s) as vSegment_s,
						SUM(fareVal_r) as fareVal_r, SUM(taxTotal_r) as taxTotal_r, SUM(netAmt_r) as netAmt_r,  SUM(vSegment_r) as vSegment_r,
						SUM(salesAmt_s) as salesAmt_s, SUM(salesAmt_r) as salesAmt_r 
						FROM ( "
							 . $adminGroupListQuery .
						" ) as s_r GROUP BY " . $groupby . "  ORDER BY " . $orderby  ;
		
		//echo $adminGroupListQuery_summary;
		//return;

		
		session_start();

        $_SESSION['sess_query'] = $adminGroupListQuery_summary;
        $adminGroupList 		= $this->db->query($adminGroupListQuery_summary);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
   function getSummaryReport1($extraChecks,$OpenClosed,$RefundType,$orderby, $groupby, $extra_column_main = ',', $extra_column_t = ',', $extra_column_x = ','  )
    {
		// set common columns
		$column_t_s = " tb.nGDS " . $extra_column_t . " 
						SUM(tfd.vFaceValue) as fareVal_s , SUM(tfd.vTaxTotal) as taxTotal_s, SUM(tfd.nNetVendor) as netAmt_s, SUM(td.vSegment) as vSegment_s,
						'' as fareVal_r , '' as taxTotal_r, '' as netAmt_r, '' as vSegment_r ";						
/*		$column_x_s = " xo.nGDS " . $extra_column_x . "  
						SUM(xpd.vFCTax) as fareVal_s, SUM(xpd.vTaxTotal) as taxTotal_s, SUM(xpd.nNetVendor) as netAmt_s,  SUM(xo.vSegment) as vSegment_s,  
						'' as fareVal_r , '' as taxTotal_r, '' as netAmt_r, '' as vSegment_r ";						
*/
		$column_t_r = " tb.nGDS " . $extra_column_t . "
					 	'' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, 
						SUM(tfd.vFaceValue) as fareVal_r , SUM(tfd.vTaxTotal) as taxTotal_r, SUM(tfd.nNetVendor) as netAmt_r, SUM(td.vSegment) as vSegment_r ";
/*		$column_x_r = " xo.nGDS " . $extra_column_x . "
						 '' as fareVal_s , '' as taxTotal_s, '' as netAmt_s, '' as vSegment_s, 
						 SUM(xpd.vFCTax) as fareVal_r, SUM(xpd.vTaxTotal) as taxTotal_r, SUM(xpd.nNetVendor) as netAmt_r,  SUM(xo.vSegment) as vSegment_r  ";
*/	
		// common joins
		$join_t = "{PRE}TicketBooking tb 
						LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId 
						LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId  ";
						
//		$join_x = "{PRE}XoBooking xo 
//						LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId";			
	
		//build queries
		$query_sales_open    = " ( SELECT " . $column_t_s . "  , SUM(tbd.vSalesAmt) as salesAmt_s, '' as salesAmt_r
									FROM " . $join_t . "
									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId									
									LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 									
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."  " . $extraChecks['extraCheck_t_sales_open'] . " 
									GROUP BY " . $groupby . " ) " ;
/*									
								
								UNION ALL ( SELECT " . $column_x_s . " , SUM(xbd.vSalesAmt) as salesAmt_s,  '' as salesAmt_r
									FROM " . $join_x . "								
									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
								    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 																			
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_x_sales_open'] . "
									GROUP BY " . $groupby . "
								) ";
								
*/								
								
		$query_refund_open = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(rrd.vPaidTo) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . " ) ";									
								
/*								  UNION ALL ( SELECT " . $column_x_r . " ,  '' as salesAmt_s, SUM(rrd.vPaidTo) as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId 
									LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
									LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_ref_open'] . "
									GROUP BY " . $groupby . "									
								) ";
*/																
								
		$query_sales_closed = " ( SELECT " . $column_t_s . " , SUM(scd.vCollected) as salesAmt_s,  '' as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId 
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE tb.vConjStatus = 'Yes' 
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_sales_closed'] . "
									GROUP BY " . $groupby . " ) " ;									
								
/*								 UNION ALL ( SELECT " . $column_x_s . " , SUM(scd.vCollected) as salesAmt_s, '' as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId
									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId
									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation										 
									WHERE xo.vActive='1' 																	
									" .$extraChecks['extraCheck_x'] ."   " . $extraChecks['extraCheck_sales_closed'] . "
									GROUP BY " . $groupby . "									
								) ";
*/								
								
		$query_refund_closed = " ( SELECT " . $column_t_r . " ,  '' as salesAmt_s, SUM(rcd.vPaidAmt) as salesAmt_r
									FROM " . $join_t . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE tb.vConjStatus = 'Yes'  
									" .$extraChecks['extraCheck_t']."   " . $extraChecks['extraCheck_ref_closed'] . "
									GROUP BY " . $groupby . " ) ";									
								
/*								 UNION ALL ( SELECT " . $column_x_r. " , '' as salesAmt_s, SUM(rcd.vPaidAmt) as salesAmt_r
									FROM " . $join_x . "
									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId
									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation										 
									WHERE xo.vActive='1' 
									".$extraChecks['extraCheck_x']."   " . $extraChecks['extraCheck_ref_closed'] . "
									GROUP BY " . $groupby . "									
								) ";
*/
		// select appropriate query
		switch($OpenClosed)
		{
			case 'All':
				switch($RefundType) {
					case 'All':  $adminGroupListQuery =  $query_sales_open . "  UNION ALL  " . $query_refund_open . "  UNION ALL  " . $query_sales_closed . "  UNION ALL  " . $query_refund_closed . "";	break;					
					case 'Sales':  $adminGroupListQuery = " " . $query_sales_open . "  UNION ALL  " . $query_sales_closed . "  ";	break;
					case 'Refund': $adminGroupListQuery =  " " . $query_refund_open . "  UNION ALL  " . $query_refund_closed . " ";	break;
				}
				break;
			case 'Open':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery = " " . $query_sales_open . "  UNION ALL  " . $query_refund_open . " "; break;					
					case 'Sales':  $adminGroupListQuery = $query_sales_open ; break; 
					case 'Refund': $adminGroupListQuery =  $query_refund_open ;	break; 
				}
				break;
			case 'Closed':
				switch($RefundType) {
					case 'All':    $adminGroupListQuery  ="  " . $query_sales_closed . "  UNION ALL  " . $query_refund_closed . " "; break;					
					case 'Sales':  $adminGroupListQuery  =$query_sales_closed;	break;
					case 'Refund': $adminGroupListQuery  =$query_refund_closed;	break;
				}
				break;
		}
		
		
        $adminGroupListQuery_summary .= " SELECT nGDS " . $extra_column_main . " 
						SUM(fareVal_s) as fareVal_s , SUM(taxTotal_s) as taxTotal_s, SUM(netAmt_s) as netAmt_s, SUM(vSegment_s) as vSegment_s,
						SUM(fareVal_r) as fareVal_r, SUM(taxTotal_r) as taxTotal_r, SUM(netAmt_r) as netAmt_r,  SUM(vSegment_r) as vSegment_r,
						SUM(salesAmt_s) as salesAmt_s, SUM(salesAmt_r) as salesAmt_r 
						FROM ( "
							 . $adminGroupListQuery .
						" ) as s_r GROUP BY " . $groupby . "  ORDER BY " . $orderby  ;
		
		//echo $adminGroupListQuery_summary;
		//return;

		
		session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery_summary;
        $adminGroupList 		= $this->db->query($adminGroupListQuery_summary);
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

