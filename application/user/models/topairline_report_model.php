<?php
class Topairline_report_model extends CI_Model {
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
/*    function getPayableAccount()
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
//			$locGroupRow[0] = SELECT;
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
        $empGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline WHERE vActive = '1' ORDER BY vTitle ASC");
        $empGroupRow = array();
        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nArId] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';
//                $empGroupRow[$row->vAirlineNo] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';

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
    function mainStatusArray()
	{
		$mainStatusArrayList = array("All" => "All","Normal" => "Normal",
                              "Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void"
                            );
		return ($mainStatusArrayList);
	}//end function
	
	function mainReportArray()
	{
		$mainReportArrayList = array("NetFare" => "Net Fare","Sales" => "Sales"
                              
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
	//get monthly report
	function getMonthWiseReport($txtFrom,$txtTo,$arilId)
	{
        $extraCheck_t = $this->session->userdata('extraCheck_t');
            $extraCheck_x = $this->session->userdata('extraCheck_x');
            $adminGroupList = $this->db->query("(SELECT SUM(tfd.vFaceValue) as totalFare,
                                            	COUNT(ar.nArId) as airCount FROM tbl_TicketBooking tb
											LEFT JOIN tbl_TicketFareDetails tfd ON tb.nTId = tfd.nTicketId
												LEFT JOIN tbl_TicketBillingDetails tbd
													ON tb.nTId = tbd.nTicketId
											LEFT JOIN tbl_Airline ar ON ar.nArId = tb.nAirline
                                            	LEFT JOIN tbl_EmployeeMaster em ON tb.vIssuedBy = em.nEId
											LEFT JOIN tbl_AccountHeadAccounts aha ON aha.nId = tfd.nAcc
                                           	 WHERE tb.vConjStatus = 'Yes' AND tb.dDate BETWEEN '".$txtFrom."'
												AND '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'
											 AND tb.nAirline = '".$arilId."'
												".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC)
														UNION ALL
                                            (SELECT SUM(xo.vGrandFare) as totalFare,
                                            	COUNT(ar.nArId) as airCount FROM tbl_XoBooking xo
                                            LEFT JOIN tbl_XoBillingDetails xbd ON xo.nXId = xbd.nXoId
												LEFT JOIN tbl_Airline ar ON ar.nArId = xo.nAirline
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
	
    function getReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate,tb.nTId as tableId,'Ticket' as sale_type FROM
                                        tbl_TicketBooking tb LEFT JOIN tbl_TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        tbl_TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN tbl_Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN tbl_EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN tbl_AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes'
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate,xo.nXId as tableId,'Xo' as sale_type FROM tbl_XoBooking xo
                                        LEFT JOIN tbl_XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN tbl_Airline ar ON ar.nArId = xo.nAirline
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
    function getSalesReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate,tb.nTId as tableId,'Ticket' as sale_type FROM
                                        tbl_TicketBooking tb LEFT JOIN tbl_TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        tbl_TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN tbl_Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN tbl_EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN tbl_AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate,xo.nXId as tableId,'Xo' as sale_type FROM tbl_XoBooking xo
                                        LEFT JOIN tbl_XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN tbl_Airline ar ON ar.nArId = xo.nAirline
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
    function getRefundReport($extraCheck_t,$extraCheck_x)
    {
        $adminGroupListQuery = "(SELECT tb.nTId,tb.nAirline as airline,tb.dDate,tb.nTId as tableId,'Ticket' as sale_type FROM
                                        tbl_TicketBooking tb LEFT JOIN tbl_TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
                                        tbl_TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN tbl_Airline ar ON ar.nArId = tb.nAirline
                                        LEFT JOIN tbl_EmployeeMaster em ON tb.vIssuedBy = em.nEId LEFT JOIN tbl_AccountHeadAccounts aha ON
                                        aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
                                            IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')
                                            ".$extraCheck_t." GROUP BY ar.nArId ORDER BY tb.nTId DESC) UNION ALL
                                        (SELECT xo.nXId,xo.nAirline as airline,xo.dDate,xo.nXId as tableId,'Xo' as sale_type FROM tbl_XoBooking xo
                                        LEFT JOIN tbl_XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN tbl_Airline ar ON ar.nArId = xo.nAirline
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
	*/
	
	
    function getReport($extraCheck_sales, $extraCheck_refund, $extra_column, $orderby, $groupby, $Refund, $Report)
    {
		if($Report == "NetFare"){
			$sum_column_s = " tfd.nNetVendor " ;
			$join_s = ' LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation ';

			$sum_column_r = " rrd.vNetAmt2 " ;
			$join_r = " INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
						LEFT JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId
						LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation ";
		}
		else if( $Report == "Sales"){
			$sum_column_s = " scd.vCollected " ;
			$join_s = " INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId 
						LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId						
						LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation";

			$sum_column_r = " rcd.vPaidAmt " ;
			$join_r = " INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId 
						LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 
						LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation ";
		}
		
		
		$query_sales = " SELECT "
						  . $extra_column . " , SUM(" . $sum_column_s . ") as total_amt, COUNT(*) as total_count,

						  SUM(CASE WHEN MONTH(tb.dDate) = 1 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Jan,
						  SUM(CASE WHEN MONTH(tb.dDate) = 2 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Feb,
						  SUM(CASE WHEN MONTH(tb.dDate) = 3 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Mar,
						  SUM(CASE WHEN MONTH(tb.dDate) = 4 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Apr,
						  SUM(CASE WHEN MONTH(tb.dDate) = 5 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_May,
						  SUM(CASE WHEN MONTH(tb.dDate) = 6 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Jun,
						  SUM(CASE WHEN MONTH(tb.dDate) = 7 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Jul,
						  SUM(CASE WHEN MONTH(tb.dDate) = 8 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Aug,
						  SUM(CASE WHEN MONTH(tb.dDate) = 9 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Sep,
						  SUM(CASE WHEN MONTH(tb.dDate) = 10 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Oct,
						  SUM(CASE WHEN MONTH(tb.dDate) = 11 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Nov,
						  SUM(CASE WHEN MONTH(tb.dDate) = 12 THEN " . $sum_column_s . " ELSE 0 END) AS Fare_Dec,

						  SUM(CASE WHEN MONTH(tb.dDate) = 1 THEN 1 ELSE 0 END) AS Count_Jan,
						  SUM(CASE WHEN MONTH(tb.dDate) = 2 THEN 1 ELSE 0 END) AS Count_Feb,
						  SUM(CASE WHEN MONTH(tb.dDate) = 3 THEN 1 ELSE 0 END) AS Count_Mar,
						  SUM(CASE WHEN MONTH(tb.dDate) = 4 THEN 1 ELSE 0 END) AS Count_Apr,
						  SUM(CASE WHEN MONTH(tb.dDate) = 5 THEN 1 ELSE 0 END) AS Count_May,
						  SUM(CASE WHEN MONTH(tb.dDate) = 6 THEN 1 ELSE 0 END) AS Count_Jun,
						  SUM(CASE WHEN MONTH(tb.dDate) = 7 THEN 1 ELSE 0 END) AS Count_Jul,
						  SUM(CASE WHEN MONTH(tb.dDate) = 8 THEN 1 ELSE 0 END) AS Count_Aug,
						  SUM(CASE WHEN MONTH(tb.dDate) = 9 THEN 1 ELSE 0 END) AS Count_Sep,
						  SUM(CASE WHEN MONTH(tb.dDate) = 10 THEN 1 ELSE 0 END) AS Count_Oct,
						  SUM(CASE WHEN MONTH(tb.dDate) = 11 THEN 1 ELSE 0 END) AS Count_Nov,
						  SUM(CASE WHEN MONTH(tb.dDate) = 12 THEN 1 ELSE 0 END) AS Count_Dec

						FROM tbl_TicketBooking tb 
						LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
						LEFT JOIN {PRE}TicketDetails td ON  tb.nTId = td.nTicketId " 
						. $join_s . "
						WHERE tb.vConjStatus = 'Yes' " . $extraCheck_sales . " 
						GROUP BY " . $groupby . " ";


		$query_refund = " SELECT "
						  . $extra_column . " , -SUM(" . $sum_column_r . ") as total_amt, -COUNT(*) as total_count,

						  -SUM(CASE WHEN MONTH(tb.dDate) = 1 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Jan,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 2 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Feb,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 3 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Mar,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 4 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Apr,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 5 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_May,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 6 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Jun,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 7 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Jul,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 8 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Aug,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 9 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Sep,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 10 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Oct,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 11 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Nov,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 12 THEN " . $sum_column_r . " ELSE 0 END) AS Fare_Dec,

						  -SUM(CASE WHEN MONTH(tb.dDate) = 1 THEN 1 ELSE 0 END) AS Count_Jan,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 2 THEN 1 ELSE 0 END) AS Count_Feb,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 3 THEN 1 ELSE 0 END) AS Count_Mar,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 4 THEN 1 ELSE 0 END) AS Count_Apr,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 5 THEN 1 ELSE 0 END) AS Count_May,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 6 THEN 1 ELSE 0 END) AS Count_Jun,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 7 THEN 1 ELSE 0 END) AS Count_Jul,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 8 THEN 1 ELSE 0 END) AS Count_Aug,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 9 THEN 1 ELSE 0 END) AS Count_Sep,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 10 THEN 1 ELSE 0 END) AS Count_Oct,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 11 THEN 1 ELSE 0 END) AS Count_Nov,
						  -SUM(CASE WHEN MONTH(tb.dDate) = 12 THEN 1 ELSE 0 END) AS Count_Dec

						FROM tbl_TicketBooking tb 
						LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
						LEFT JOIN {PRE}TicketDetails td  ON  tb.nTId = td.nTicketId " 
						. $join_r . "
						WHERE tb.vConjStatus = 'Yes' " . $extraCheck_refund . " 						
						GROUP BY " . $groupby . " ";



		switch($Refund)
		{
			case 'All':	   $adminGroupListQuery =  " SELECT " . $groupby . " , SUM(total_amt) as total_amt, SUM(total_count) as total_count, 
								SUM(Fare_Jan) as Fare_Jan ,  SUM(Fare_Feb) as Fare_Feb , SUM(Fare_Mar) as Fare_Mar ,  SUM(Fare_Apr) as Fare_Apr ,  
								SUM(Fare_May) as Fare_May ,  SUM(Fare_Jun) as Fare_Jun ,  SUM(Fare_Jul) as Fare_Jul ,  SUM(Fare_Aug) as Fare_Aug ,  
								SUM(Fare_Sep) as Fare_Sep ,   SUM(Fare_Oct) as Fare_Oct ,   SUM(Fare_Nov) as Fare_Nov ,  SUM(Fare_Dec) as Fare_Dec , 
								SUM(Count_Jan) as Count_Jan ,  SUM(Count_Feb) as Count_Feb ,  SUM(Count_Mar) as Count_Mar ,  SUM(Count_Apr) as Count_Apr ,
								SUM(Count_May) as Count_May ,  SUM(Count_Jun) as Count_Jun ,  SUM(Count_Jul) as Count_Jul ,  SUM(Count_Aug) as Count_Aug , 
								SUM(Count_Sep) as Count_Sep ,  SUM(Count_Oct) as Count_Oct ,  SUM(Count_Nov) as Count_Nov ,  SUM(Count_Dec) as Count_Dec 
								FROM ( " 
								. $query_sales . " 
								UNION ALL ( " 
								. $query_refund . " ) ) as s_r 
								GROUP BY " . $groupby . " 
								ORDER BY " . $orderby;	
							break;
			case 'Sales':  $adminGroupListQuery = $query_sales . " ORDER BY " . $orderby;	break;
			case 'Refund': $adminGroupListQuery = $query_refund . " ORDER BY " . $orderby;	break;
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
	
	
}//end class
?>
