<?php
class Topsector_report_model extends CI_Model {

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
											ORDER BY nEId DESC");
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
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1' ORDER BY nPosition DESC");
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
                $locGroupRow[$row->nId] = stripslashes($row->vCode);
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
											 AND nDivision = '".$division."' ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            $locGroupRow[0] = SELECT;
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vCode);
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
        $empGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline`  WHERE vActive = '1' ORDER BY nArId DESC");
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

    function mainStatusArray()
    {
        $mainStatusArrayList = array("All" => "All","Normal" => "Normal",
            "Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void"
        );
        return ($mainStatusArrayList);
    }//end function

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

    //get monthly report
    function getMonthWiseReport($txtFrom,$txtTo,$arilId)
    {
        $extraCheck_t = $_SESSION['extraCheck_t'];
        $extraCheck_x = $_SESSION['extraCheck_x'];

        $adminGroupList = $this->db->query("(SELECT SUM(tfd.vFaceValue) as totalFare,
                                            	COUNT(ar.nArId) as airCount FROM {PRE}TicketBooking tb
											LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId
												LEFT JOIN {PRE}TicketBillingDetails tbd
													ON tb.nTId = tbd.nTicketId 
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
                                            	LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc
                                           	 WHERE tb.vConjStatus = 'Yes' AND tb.dDate BETWEEN '".$txtFrom."'
												AND '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'
											 AND tb.nAirline = '".$arilId."'
												".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) 	
														UNION ALL
                                            (SELECT SUM(xo.vGrandFare) as totalFare,
                                            	COUNT(ar.nArId) as airCount FROM {PRE}XoBooking xo
                                            LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
												LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
                                            WHERE xo.vActive='1' AND xo.dDate BETWEEN '".$txtFrom."' 
												AND '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'
											AND xo.nAirline = '".$arilId."'
												".$extraCheck_x." GROUP BY ar.nArId ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['count']   = $row->airCount;
                $adminGroupRow['fareval'] = $row->totalFare;
            }//end for each
        } //end if

        return ($adminGroupRow);
    }//end function

    function getMonthWiseReport2($txtFrom,$txtTo,$sector)
    {
        $extraCheck_t = $_SESSION['extraCheck_t'];
        $extraCheck_x = $_SESSION['extraCheck_x'];

        $adminGroupList = $this->db->query("(SELECT SUM(tfd.vFaceValue) as totalFare,
                                            	COUNT(td.vSector1) as airCount FROM {PRE}TicketBooking tb
											LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId
												LEFT JOIN {PRE}TicketBillingDetails tbd
													ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td
													ON tb.nTId = td.nTicketId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
                                            	LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc
                                           	 WHERE tb.vConjStatus = 'Yes' AND tb.dDate BETWEEN '".$txtFrom."'
												AND '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'
											 AND td.vSector1 LIKE '".$sector." %' OR td.vSector1 LIKE '% ".$sector."'
								                OR td.vSector1 LIKE '%".$sector."%'
												".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC)
														UNION ALL
                                            (SELECT SUM(xo.vGrandFare) as totalFare,
                                            	COUNT(xd.vSector) as airCount FROM {PRE}XoBooking xo
                                            LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoDetails xd ON xo.nXId = xd.nXoId
												LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
                                            WHERE xo.vActive='1' AND xo.dDate BETWEEN '".$txtFrom."'
												AND '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'
											AND xd.vSector LIKE '".$sector." %' OR xd.vSector LIKE '% ".$sector."'
								                OR xd.vSector LIKE '%".$sector."%'
												".$extraCheck_x." GROUP BY ar.nArId ORDER BY xo.nXId DESC)");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['count']   = $row->airCount;
                $adminGroupRow['fareval'] = $row->totalFare;
            }//end for each
        } //end if

        return ($adminGroupRow);
    }//end function

    function getSectorReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
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

    function getSectorSalesReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
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

    function getSectorRefundReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as
                                            Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22,
                                            td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1,
                                            xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44,
                                            xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN
                                            {PRE}XoDetails xd ON xd.nXoId=xo.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
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

    function getAirlineReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate FROM
                                        {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate FROM {PRE}XoBooking xo
                                        LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
                                        WHERE xo.vActive='1'
                                            ".$extraCheck_x." GROUP BY ar.nArId ORDER BY xo.nXId DESC)";
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

    function getAirlineSalesReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate FROM
                                        {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate FROM {PRE}XoBooking xo
                                        LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
                                        WHERE xo.vActive='1' AND xo.nXId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')
                                            ".$extraCheck_x." GROUP BY ar.nArId ORDER BY xo.nXId DESC)";


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

    function getAirlineRefundReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate FROM
                                        {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate FROM {PRE}XoBooking xo
                                        LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
                                        WHERE xo.vActive='1'  AND xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                            rcd.vType = 'Xo') ".$extraCheck_x." GROUP BY ar.nArId ORDER BY xo.nXId DESC)";

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