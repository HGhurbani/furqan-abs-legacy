<?php
class Profit_report_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

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

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
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

    function getPayableAccount()
    {
        $empGroup    = $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY vName ASC");
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
 
    } //end function
/*    function filterSubAccount($accGroup)
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
    function filterCity($country)
    {
        $locGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."' ORDER BY vName ASC");
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

/*    function getAccounts()
    {
        $empGroup    = $this->db->query("SELECT nCId,nFId,vName,vCode FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY vName ASC");
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

    function mainSalesArray()
    {
        $mainSalesArrayList = array("All" => "All","Ticket Sales" => "Ticket Sales","XO Sales" => "XO Sales","Hotel Sales" => "Hotel Sales");
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

    function mainRefundArray()
    {
        $mainRefundArrayList = array("All" => "All","Sales" => "Sales","Refund" => "Refund");
        return ($mainRefundArrayList);
    }//end function

    function mainStatusArray()
    {
        $mainStatusArrayList = array("Normal" => "Normal","Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void");
        return ($mainStatusArrayList);
    }//end function

    function filterLocation($division)
    {
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
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

    function getAirlineReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,
                                            tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as
                                            taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails
                                            xbd ON xo.nXId = xbd.nXoId
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
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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

    function getGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
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
                                            xo.nGDS,xo.vAirpnr,
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
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
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
                break;

            case "Xo":
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xo.nGDS,
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
                break;

            case "Htl":
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
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
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
                                            xo.nGDS,xo.vAirpnr,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xo.nGDS,xo.vAirpnr,
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

    function getRefundGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
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
                                            xo.nGDS,xo.vAirpnr,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xo.nGDS,xo.vAirpnr,
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

    function getHotelReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM
                                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') OR hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getSalesHotelReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN
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

    function getRefundHotelReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha
                                            ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tfd.nAcc as account,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') OR tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                            rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,'',' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            OR hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
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

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tfd.nAcc as account,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
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
                break;

            case "Xo":
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                break;

            case "Htl":
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            OR hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
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

    function getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')  ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,'',' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tfd.nAcc as account,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
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

    function getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tfd.nAcc as account,
                                            ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,tfd.vFaceValue as
                                            profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
                                            tfd.vFCFare as fareVal FROM {PRE}TicketBooking tb LEFT JOIN
                                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
                                            LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')  ".$extraCheck_t."
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,'',' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate, tfd.nAcc as account,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
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

    function getGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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

    function getSalesGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId, td.vDestination,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
                                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                            {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}TicketDetails td ON td.nTicketId=tb.nTId
                                            LEFT JOIN {PRE}Airline ar
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
        return ($adminGroupRow);
    }//end function

    function getRefundGdsTicketedReport($extraCheck_t)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,  td.vDestination,
                                            tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFCFare as fareVal FROM
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
        return ($adminGroupRow);
    }//end function

    function getHotelCheckInReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM
                                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') OR hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN
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

    function getHotelCheckInRefundReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha
                                            ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM
                                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') OR hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN
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

    function getHotelCheckOutRefundReport($extraCheck_h)
    {
        $adminGroupListQuery = "(SELECT hv.*,'Hotel' as sale_type,hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
                                            LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha
                                            ON aha.nId = hv.nAcCode WHERE hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber
                                            FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC)";
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

    function getEmployeeReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            ORDER BY tb.nTId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                            rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,'',' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            OR hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
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

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                break;

            case "Htl":
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            OR hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
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

    function getEmployeeSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
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

    function getEmployeeRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                $adminGroupListQuery = "(SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
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

    function getSectorReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                break;

        }//end switch
        return ($adminGroupRow);
    }//end function

    function getSectorSalesReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                break;

            case "Htl":
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function

    function getSectorRefundReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.vAirpnr,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                break;

            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,tb.vAirpnr,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,xo.vAirpnr,
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
                break;

            case "Htl":
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function

}//end class
?>
