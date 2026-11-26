<?php
class Monthly_info_model extends CI_Model {
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
    function filterCity($country)
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
    function getAccounts()
    {
        $empGroup    = $this->db->query("SELECT nCId,nFId,vName,vCode FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY vCode ASC");
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
        $mainReportArrayList = array("Summary" => "Summary","Month" => "Month","Profit" => "Profit");
		//"By Year" => "By Year","By Month" => "By Month" 
        return ($mainReportArrayList);
    }//end function
    function mainRefundArray()
    {
        $mainRefundArrayList = array("All" => "All","Sales" => "Sales","Refund" => "Refund");
        return ($mainRefundArrayList);
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
        $mainStatusArrayList = array("Normal" => "Normal","Cunjuction" => "Cunjuction","Reissued" => "Reissued","Void" => "Void");
        return ($mainStatusArrayList);
    }//end function
*/  
    function filterLocation($division)
    {
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY vCode ASC");
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
// columns for sales
	function get_columns_hotel_sales(){
		$columns_h = "'' as cmtAmount,'' as cmtAmount_r,hv.vTotalDC as netAmt,'' as  netAmt_r, hv.nHVId,hv.vDocNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,'' as salesAmt_r,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, hv.nAcCode as account, hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as taxTotal_r,'' as creditAmt,hv.vTotalRTDC as fareVal,'' as fareVal_r ";
		return $columns_h;
	}
	function get_columns_ticket_sales(){
		$columns_t = "tfd.nCommAmt as cmtAmount,'' as cmtAmount_r	,tfd.nNetVendor as netAmt,'' as netAmt_r, tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,'' as salesAmt_r,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate_txh,tfd.nAcc as account, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,'' as taxTotal_r, tfd.nORCAmt as creditAmt, tfd.vFaceValue as fareVal,'' as fareVal_r  ";
		return $columns_t; 											
	}
	function get_columns_xo_sales(){
		$columns_x = "sum(xpd.nORCAmt) as cmtAmount,'' as cmtAmount_r,sum(xpd.nNetVendor) as netAmt,'' as netAmt_r, xo.nXId as nTId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,'' as salesAmt_r,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate_txh,xo.nPassengerAc as account,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,sum(xpd.vTaxTotal) as taxTotal,'' as taxTotal_r, '' as creditAmt, sum(xpd.vFCTax) as fareVal,'' as fareVal_r  ";
		return $columns_x;
	}
// columns for refund
	function get_columns_hotel_refund(){
		$columns_h = "hv.nHVId,hv.vDocNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus,'' as salesAmt,hbd.vSalesAmt as salesAmt_r,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, hv.nAcCode as account, hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as taxTotal_r, '' as creditAmt,'' as fareVal, -hv.vTotalRTDC as fareVal_r ";
		return $columns_h;
	}
	function get_columns_ticket_refund(){
		$columns_t = "tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus,'' as salesAmt, tbd.vSalesAmt as salesAmt_r,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate_txh,tfd.nAcc as account, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,'' as taxTotal,-tfd.vTaxTotal as taxTotal_r, - tfd.nORCAmt as creditAmt,'' as fareVal, -tfd.vFaceValue as fareVal_r  ";
		return $columns_t; 											
	}
	function get_columns_xo_refund(){
		$columns_x = "xo.nXId as nTId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,'' as salesAmt,xbd.vSalesAmt as salesAmt_r,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate_txh,xo.nPassengerAc as account,' ' as lpo,'' as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,'' as taxTotal, -xpd.vTaxTotal as taxTotal_r,'' as creditAmt, '' as fareVal,-xpd.vFCTax as fareVal_r  ";
		return $columns_x;
	}
  function get_columns_old_refund(){
    $columns_o = "rr.nRId as nTId,'' as vDocNumber,rr.nLocation, rrd.vTktType as sale_type,rrd.vIssuedBy as vIssuedBy, ar.nArId as airline, '' as vGdspnr, 'N' as vStatus, '' as salesAmt,rrd.vSalesAmt as salesAmt_r,rrd.vDiscountAmt as discAmt,rrd.vServicePer as ServicePercent,rrd.vServiceAmt as ServiceAmt, rrd.nTktNum as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate_txh, rr.nAccount as account,' ' as lpo,rrd.vPassenger as vPassenger,rrd.vSalesAmt as collected,' ' as profit,rrd.nRDId as tableId,'' as taxTotal, -rrd.vTax as taxTotal_r,'' as creditAmt, '' as fareVal,-rrd.vFareAmt as fareVal_r  ";
    return $columns_o;
  } 
	function get_columns_sales(){
			$columns_s = " 'sales' as refundtype,sc.dDate as dDate,  scd.vCollected as salesAmount,'' as salesAmount_r, CONCAT('index.php/sales_closing/editSalesClosing/4/e/',scd.nId,'/',scd.nSalesCloseId,'/cl' ) as link, scd.nSalesCloseId,
						IF( IFNULL((select sum(sad.vAmt) from {PRE}SalesAccountDetails sad 
						LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = sad.nDrAccount
						where sad.nSalesCloseId = scd.nSalesCloseId and sad.nAcHead = '0' and ahc.vSubSale = 'C') , 0) = 0 , 0, scd.vCollected) as Cash,
            IF( IFNULL((select sum(sad.vAmt) from {PRE}SalesAccountDetails sad
						LEFT JOIN {PRE}AccountHeadControls ahc on ahc.nActHId = sad.nDrAccount
            where sad.nSalesCloseId = scd.nSalesCloseId and sad.nAcHead = '0' and ahc.vSubSale = 'B') , 0) = 0 ,0, scd.vCollected) as Bank,
						IF(IFNULL((select sum(sad.vAmt) from {PRE}SalesAccountDetails sad 
						INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = sad.nAcHead
						where sad.nSalesCloseId = scd.nSalesCloseId and sad.nAcHead != '0'  and ahc.vSubSale = '1') , 0) =0 ,0,scd.vCollected) as Credit,
						IF( IFNULL((select sum(sad.vAmt) from {PRE}SalesAccountDetails sad 
						INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = sad.nDrAccount
						where sad.nSalesCloseId = scd.nSalesCloseId and sad.nAcHead = '0' and ahc.vSubSale = '0') , 0) =0,0,scd.vCollected) as Others, ";
		return $columns_s;
	}
	function get_columns_refund(){
		$columns_r = " 'refund' as refundtype,rc.dDate as dDate, '' as salesAmount, -rcd.vPaidAmt as salesAmount_r, CONCAT('index.php/refund_closing/editRefundClosing/4/e/',rcd.nRefundCloseId) as link, rcd.nRefundCloseId,
						IF( IF( rcd.vTable NOT IN ('V','C','AG','O') , -rcd.vNetAmt,0)=0 , 0, -rcd.vPaidAmt) as Cash,
						IF(IF( rcd.vTable NOT IN ('V','C','AG','O') , -rcd.vNetAmt,0)=0,0, -rcd.vPaidAmt ) as Bank,
						IF((CASE 
							WHEN rcd.vTable = 'AG' AND (
											select ahc.vSubSale from {PRE}Agent temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nAId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'C' AND (
											select ahc.vSubSale from {PRE}CustomerMaster temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nCId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'V' AND (
											select ahc.vSubSale from {PRE}VendorMaster temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nVId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'O' AND (
											select ahc.vSubSale from {PRE}Other temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nOId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							ELSE '0'
						END)=0,0, -rcd.vPaidAmt ) as Credit,
						IF( IF( rcd.vTable NOT IN ('V','C','AG','O'), -rcd.vNetAmt,0)=0,0, -rcd.vPaidAmt ) as Others, 
						'' as cmtAmount,(select -rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount_r,
						'' as netAmt,-rcd.vNetAmt as netAmt_r,
						";
		return $columns_r;
	}
	function get_columns_refund1(){
		$columns_r = " 'refund' as refundtype,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate,'' as salesAmount,  -rcd.vPaidAmt as salesAmount_r, CONCAT('index.php/refund_closing/editRefundClosing/4/e/',rcd.nRefundCloseId) as link, rcd.nRefundCloseId,
						IF( IF( rcd.vTable NOT IN ('V','C','AG','O') , -rcd.vNetAmt,0)=0 , 0, -rcd.vPaidAmt) as Cash,
						IF(IF( rcd.vTable NOT IN ('V','C','AG','O') , -rcd.vNetAmt,0)=0,0, -rcd.vPaidAmt ) as Bank,
						IF((CASE 
							WHEN rcd.vTable = 'AG' AND (
											select ahc.vSubSale from {PRE}Agent temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nAId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'C' AND (
											select ahc.vSubSale from {PRE}CustomerMaster temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nCId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'V' AND (
											select ahc.vSubSale from {PRE}VendorMaster temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nVId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							WHEN rcd.vTable = 'O' AND (
											select ahc.vSubSale from {PRE}Other temp 
											LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = temp.nFId
											where temp.nOId = rcd.nDrAcc ) = '1' THEN -rcd.vNetAmt
							ELSE '0'
						END)=0,0, -rcd.vPaidAmt ) as Credit,
						IF( IF( rcd.vTable NOT IN ('V','C','AG','O') , -rcd.vNetAmt,0)=0,0, -rcd.vPaidAmt ) as Others, 
						'' as cmtAmount,(select -rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount_r,
						'' as netAmt,-rcd.vNetAmt as netAmt_r,
						";
		return $columns_r1;
	}
// for airline reports
    function getAllReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType)
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
$column_r1 = $this->get_columns_refund1();
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = " SELECT " . $column_s . " " . $column_t_s . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
		 								 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
										 WHERE tb.vConjStatus = 'Yes' 
										" .$extraCheck_t." 
										UNION ALL (SELECT " . $column_s . " " . $column_x_s . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										
										WHERE xo.vActive='1' 
										".$extraCheck_x." ) 
										UNION ALL (SELECT " . $column_s . " " . $column_h_s . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
 									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h." )
										UNION ALL (SELECT " . $column_r . " " . $column_t_r . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
									     LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
 										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t.") 
										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 										
										WHERE xo.vActive='1' 
										".$extraCheck_x.") 
										UNION ALL (SELECT " . $column_r . " " . $column_h_r . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										 										
									    WHERE hv.vActive='1' 
										".$extraCheck_h.")
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or.")
										  ORDER BY " . $orderby;
//echo $adminGroupListQuery;
//return;				 
  $x="SELECT " . $column_r1 . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId 
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or."";						
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_t_s . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 									
										 WHERE tb.vConjStatus = 'Yes'
										" .$extraCheck_t." 
										 UNION ALL (SELECT " . $column_r . " " . $column_t_r . "
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
										 WHERE tb.vConjStatus = 'Yes' 
										 " .$extraCheck_t." ) 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Tkt' 
                    ".$extraCheck_or.")
										ORDER BY " . $orderby ;
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_x_s . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x."
										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " 
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.") 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Xo'
                    ".$extraCheck_or.")
										ORDER BY " . $orderby ;
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_h_s . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h."
										UNION ALL(SELECT " . $column_r . " " . $column_h_r . " 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
										".$extraCheck_h.") 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . "
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Htl'
                    ".$extraCheck_or.")
										 ORDER BY " . $orderby . " ";
                session_start();
 //$adminGroupListQuery;
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
	function getAllReport1($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$extra_s,$extra_r)
    {
		//echo $ddlReport;								
		$column_t_s = $this->get_columns_ticket_sales();
		$column_x_s = $this->get_columns_xo_sales();
		$column_h_s = $this->get_columns_hotel_sales();
		$column_t_r = $this->get_columns_ticket_refund();
		$column_x_r = $this->get_columns_xo_refund();
		$column_h_r = $this->get_columns_hotel_refund();
    $column_o_r = $this->get_columns_old_refund();
		$column_s = $this->get_columns_sales();
		$column_r = $this->get_columns_refund();
$column_r1 = $this->get_columns_refund1();
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = " SELECT DISTINCT " . $column_s . " " . $column_t_s . ",sc.issue as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
		 								 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
										LEFT JOIN {PRE}EmployeeMaster em ON sc.issue = em.nEId
										 WHERE tb.vConjStatus = 'Yes' 
										" .$extraCheck_t.$extra_s." 
										UNION ALL (SELECT DISTINCT " . $column_s . " " . $column_x_s . " ,sc.issue as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_s. "GROUP BY xo.nXId) 
										UNION ALL (SELECT " . $column_s . " " . $column_h_s . " ,sc.issue as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}EmployeeMaster em ON sc.issue = em.nEId
 									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h.$extra_s." )
										UNION ALL (SELECT " . $column_r . " " . $column_t_r . ",rrd.vIssuedBy as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
 							INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType			 
										 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
									     LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
 										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t.$extra_r.") 
										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
									    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 										
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_r.") 
										UNION ALL (SELECT " . $column_r . " " . $column_h_r . ",rrd.vIssuedBy as employee 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										 										
									    WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_r.")
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or.$extra_r.")
										  ORDER BY " . $orderby;
//echo $adminGroupListQuery;
//return;				 
  $x="SELECT " . $column_r1 . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId 
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or.$extra_r."";	
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_t_s . ",sc.issue as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 									
										 WHERE tb.vConjStatus = 'Yes'
										" .$extraCheck_t.$extra_s." 
										 UNION ALL (SELECT " . $column_r . " " . $column_t_r . ",rrd.vIssuedBy as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
										 WHERE tb.vConjStatus = 'Yes' 
										 " .$extraCheck_t.$extra_r." ) 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Tkt' 
                    ".$extraCheck_or.$extra_r.")
										ORDER BY " . $orderby ;
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_x_s . " ,sc.issue as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_s." GROUP BY xo.nXId
										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_r.") 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Xo'
                    ".$extraCheck_or.$extra_r.")
										ORDER BY " . $orderby ;
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
                $adminGroupListQuery = "SELECT "  . $column_s . " " . $column_h_s . " ,sc.issue as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h.$extra_s."
										UNION ALL(SELECT " . $column_r . " " . $column_h_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_r.") 
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Htl'
                    ".$extraCheck_or.$extra_r.")
										 ORDER BY " . $orderby . " ";
                session_start();
 //$adminGroupListQuery;
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
function getAllReport_emp($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$extra_s,$extra_r,$ddlReport)
    {
		//echo $ddlReport;								
		$column_t_s = $this->get_columns_ticket_sales();
		$column_x_s = $this->get_columns_xo_sales();
		$column_h_s = $this->get_columns_hotel_sales();
		$column_t_r = $this->get_columns_ticket_refund();
		$column_x_r = $this->get_columns_xo_refund();
		$column_h_r = $this->get_columns_hotel_refund();
    $column_o_r = $this->get_columns_old_refund();
		$column_s = $this->get_columns_sales();
		$column_r = $this->get_columns_refund();
$column_r1 = $this->get_columns_refund1();
				if($ddlReport=="Summary" || $ddlReport=="Month" || $ddlReport=="Profit"){
					$orderby = "employee ASC";
				}
        switch($SalesType)
        {
            case "All":
			
                $adminGroupListQuery = " SELECT DISTINCT " . $column_s . " " . $column_t_s . ",sc.issue as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
		 								 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
										LEFT JOIN {PRE}EmployeeMaster em ON sc.issue = em.nEId
										 WHERE tb.vConjStatus = 'Yes' 
										" .$extraCheck_t.$extra_s." 
										UNION ALL (SELECT DISTINCT " . $column_s . " " . $column_x_s . " ,sc.issue as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_s. "GROUP BY xo.nXId) 
										UNION ALL (SELECT " . $column_s . " " . $column_h_s . " ,sc.issue as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}EmployeeMaster em ON sc.issue = em.nEId
 									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										
									    WHERE hv.vActive='1' 
                                        ".$extraCheck_h.$extra_s." )
										UNION ALL (SELECT " . $column_r . " " . $column_t_r . ",rrd.vIssuedBy as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
 							INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType			 
										 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
									     LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 
 										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t.$extra_r.") 
										UNION ALL (SELECT  DISTINCT " . $column_r . " " . $column_x_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
									    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 										
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_r.") 
										UNION ALL (SELECT " . $column_r . " " . $column_h_r . ",rrd.vIssuedBy as employee 
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
 LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										 										
									    WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_r.")
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'
                    ".$extraCheck_or.$extra_r.")
										  ORDER BY " . $orderby;
//echo $adminGroupListQuery;
//return;				 
	
		
		
		if($ddlReport=="Summary"){
		$group_select =" SELECT  COUNT(*) as count,vIssuedBy as vIssuedBy,refundtype as refundtype,
                             employee as employee,SUM(fareVal) as fareVal,SUM(taxTotal) as taxTotal, SUM(cmtAmount) as cmtAmount ,SUM(netAmt) as netAmt,SUM(salesAmount) as salesAmount,
							 SUM(fareVal_r) as fareVal_r,SUM(taxTotal_r) as taxTotal_r, SUM(cmtAmount_r) as cmtAmount_r ,SUM(netAmt_r) as netAmt_r,SUM(salesAmount_r) as salesAmount_r FROM ( ";
        $groupby = "  ) as temp GROUP BY employee";
		$orderby='';
        $orderby1 =  "  ORDER BY vIssuedBy ";
		$adminGroupListQuery=str_replace("sc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=str_replace("rc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=$group_select.$adminGroupListQuery.$groupby;
		}
		
		if($ddlReport=="Month" || $ddlReport=="Profit"){
		$group_select =" SELECT  COUNT(*) as count,vIssuedBy as vIssuedBy,MONTH(dDate) as dDate,
                              employee as employee,SUM(fareVal) as fareVal,SUM(taxTotal) as taxTotal, SUM(cmtAmount) as cmtAmount ,SUM(netAmt) as netAmt,SUM(salesAmount) as salesAmount,
							 SUM(fareVal_r) as fareVal_r,SUM(taxTotal_r) as taxTotal_r, SUM(cmtAmount_r) as cmtAmount_r ,SUM(netAmt_r) as netAmt_r,SUM(salesAmount_r) as salesAmount_r FROM ( ";
        $groupby = "  ) as temp GROUP BY employee, MONTH(dDate)";
		$orderby='';
        $orderby1 =  "  ORDER BY MONTH(dDate) ASC ";
		//print $adminGroupListQuery;
		//$adminGroupListQuery=str_replace("sc.dDate as dDate,",' ',$adminGroupListQuery);
		//$adminGroupListQuery=str_replace("rc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=$group_select.$adminGroupListQuery.$groupby;
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
                break;
           
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$extra_s,$extra_r)
    {
		$column_t_s = $this->get_columns_ticket_sales();
		$column_x_s = $this->get_columns_xo_sales();
		$column_h_s = $this->get_columns_hotel_sales();
		$column_s = $this->get_columns_sales();
        switch($SalesType)
        {
            case "All":
			   $adminGroupListQuery = "(SELECT DISTINCT" . $column_s . " " . $column_t_s . ",sc.issue as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt' 
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t.$extra_s.") 
										UNION ALL (SELECT DISTINCT " . $column_s . " " . $column_x_s . " ,sc.issue as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_s. "GROUP BY xo.nXId) 
										UNION ALL (SELECT DISTINCT" . $column_s . " " . $column_h_s . " ,sc.issue as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_s.")
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
                break;
            case "Tkt":
                $adminGroupListQuery = "(SELECT DISTINCT" . $column_s . " " . $column_t_s . ",sc.issue as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId AND scd.vType = 'Tkt'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
 										 WHERE tb.vConjStatus = 'Yes'  
										 " .$extraCheck_t.$extra_s." 
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
                break;
            case "Xo":
                $adminGroupListQuery = "(SELECT DISTINCT" . $column_s . " " . $column_x_s . " ,sc.issue as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId AND scd.vType = 'Xo'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_s."
										ORDER BY " . $orderby . "   )";
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
                $adminGroupListQuery = "(SELECT DISTINCT" . $column_s . " " . $column_h_s . " ,sc.issue as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId AND scd.vType = 'Htl'
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
										WHERE hv.vActive='1' 
									    ".$extraCheck_h.$extra_s."
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
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
    function getRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$extra_s,$extra_r)
    {
		$column_t_r = $this->get_columns_ticket_refund();
		$column_x_r = $this->get_columns_xo_refund();
		$column_h_r  = $this->get_columns_hotel_refund();
    $column_o_r = $this->get_columns_old_refund();
		$column_r = $this->get_columns_refund();
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT " . $column_r . " " . $column_t_r . ",rrd.vIssuedBy as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
 										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
 										 WHERE tb.vConjStatus = 'Yes'  
										" .$extraCheck_t.$extra_r.") 
										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_r.") 
										UNION ALL (SELECT " . $column_r . " " . $column_h_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
									    WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_r.")
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1'  
                    ".$extraCheck_or.$extra_r.")
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
                break;
            case "Tkt":
                $adminGroupListQuery = "(SELECT " . $column_r . " " . $column_t_r . ",rrd.vIssuedBy as employee
										 FROM {PRE}TicketBooking tb 
										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 
										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 
										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 
										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
										 WHERE tb.vConjStatus = 'Yes'
										 " .$extraCheck_t.$extra_r." )
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Tkt'
                    ".$extraCheck_or.$extra_r.")
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
                break;
            case "Xo": 
                $adminGroupListQuery = "(SELECT " . $column_r . " " . $column_x_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}XoBooking xo 
										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
										WHERE xo.vActive='1' 
										".$extraCheck_x.$extra_r." )
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Xo'
                    ".$extraCheck_or.$extra_r.")
										ORDER BY " . $orderby . "   ";
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
                $adminGroupListQuery = "(SELECT " . $column_r . " " . $column_h_r . " ,rrd.vIssuedBy as employee
										FROM {PRE}HotelVoucher hv 
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
										 INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum=rcd.nTicketNumber   AND rrd.vTktType = rcd.vType
										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
										WHERE hv.vActive='1' 
										".$extraCheck_h.$extra_r." )
                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",rrd.vIssuedBy as employee
                    FROM {PRE}RefundRegister rr
                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType
                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
                      WHERE rr.vActive='1' AND rrd.vTktType ='Htl'
                    ".$extraCheck_or.$extra_r.")
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
                break;
        }//end switch
        return ($adminGroupRow);
    }//end function
// for hotel reports 
	function get_columns_hotel_report_sales(){
		$columns_h = " '' as cmtAmount,hv.vTotalDC as netAmt,hv.nAcCode as vendor, hv.vTotalFC as vTotalRTFC, hv.vNight, hv.vNoOfUnit,hv.nCurrency as currency, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.nLocation, hv.dDate, hv.vHotelName, htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}
	function get_columns_hotel_report_refund(){
		$columns_h = "hv.nAcCode as vendor,  -rcd.vNetAmt as vTotalRTFC,hv.vNight, hv.vNoOfUnit,hv.nCurrency as currency, hv.vPassenger, hv.vTicktManual, hv.vIssuedBy, hv.nLocation, hv.dDate, hv.vHotelName,  htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, DATE_FORMAT(hv.dCheckIn, '%d-%m-%Y') as dCheckIn,DATE_FORMAT(hv.dCheckOut, '%d-%m-%Y') as dCheckOut, hv.nHVId as tableId,'Hotel' as sale_type "; 
		return $columns_h;
	}
  function get_columns_hotel_old_refund(){
 $columns_o = "rr.nAccount as vendor, -rcd.vNetAmt as vTotalRTFC, '' as vNight, '' as vNoOfUnit,'5' as currency,rrd.vPassenger,rrd.nTktNum as vTicktManual, rrd.vIssuedBy, rr.nLocation, rr.dDate, '' as vHotelName,  htl.vName as vName, crty.vName as Country, cty.vName as City, DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate_txh, '' as dCheckIn,'' as dCheckOut, rrd.nRDId as tableId,'Hotel' as sale_type ";
/*    $columns_o = "rr.nRId as nTId,'' as vDocNumber,rr.nLocation,'RR' as sale_type,rrd.vIssuedBy as vIssuedBy,rrd.vAirline as airline, '' as vGdspnr, 'R' as vStatus, rrd.vSalesAmt as salesAmt,rrd.vDiscountAmt as discAmt,rrd.vServicePer as ServicePercent,rrd.vServiceAmt as ServiceAmt, rrd.nTktNum as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate_txh, rr.nAccount as account,' ' as lpo,rrd.vPassenger as vPassenger,rrd.vSalesAmt as collected,' ' as profit,rrd.nRDId as tableId, -rrd.vTax as taxTotal,'' as creditAmt, -rrd.vFareAmt as fareVal  ";
*/
    return $columns_o;
  }
    function getAllHotelReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$ddlReport)
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
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
								LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
								WHERE hv.vActive='1' 
								".$extraCheck_h."
								UNION ALL(SELECT " . $column_r . " " . $column_h_r . " 
								FROM {PRE}HotelVoucher hv 
								LEFT JOIN {PRE}Hotel htl ON htl.nHId = hv.vHotelName
								LEFT JOIN {PRE}Country cty ON cty.nId = hv.nCity 
								LEFT JOIN {PRE}Country crty ON crty.nId = hv.nCountry
								LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
								INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
								INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
								LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
								WHERE hv.vActive='1' 
								".$extraCheck_h.") 
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
                  ".$extraCheck_or.")
								 ORDER BY " . $orderby . " ";
if($ddlReport=="Summary"){
		$group_select =" SELECT  COUNT(*) as count,
                             vendor as vendor,SUM(vNight) as vNight,SUM(vNoOfUnit) as vNoOfUnit, SUM(netAmt) as netAmt ,SUM(salesAmount) as salesAmount
                             FROM ( ";
        $groupby = "  ) as temp GROUP BY vendor ";
		$orderby='';
        $orderby1 =  "  ORDER BY vendor, refundtype DESC ";
		$adminGroupListQuery=str_replace("sc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=str_replace("rc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=$group_select.$adminGroupListQuery.$groupby;
		}
		session_start();
//echo $adminGroupListQuery;
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
    function getHotelSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby, $SalesType,$ddlReport)
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
										 INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId 
								LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
								WHERE hv.vActive='1' 
								".$extraCheck_h."
								 ORDER BY " . $orderby . "  )";
		if($ddlReport=="Summary"){
		$group_select =" SELECT  COUNT(*) as count,
                             vendor as vendor,SUM(vNight) as vNight,SUM(vNoOfUnit) as vNoOfUnit, SUM(netAmt) as netAmt ,SUM(salesAmount) as salesAmount
                             FROM ( ";
        $groupby = "  ) as temp GROUP BY vendor ";
		$orderby='';
        $orderby1 =  "  ORDER BY vendor, refundtype DESC ";
		$adminGroupListQuery=str_replace("sc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=str_replace("rc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=$group_select.$adminGroupListQuery.$groupby;
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
    function getHotelRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or, $orderby, $SalesType,$ddlReport)
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
								INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'
										 INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId
								INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = hv.nHVId
								LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
								WHERE hv.vActive='1' 								
								".$extraCheck_h.")
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
                  ".$extraCheck_or.")
								 ORDER BY " . $orderby . "  ";
		if($ddlReport=="Summary"){
		$group_select =" SELECT  COUNT(*) as count,
                             vendor as vendor,SUM(vNight) as vNight,SUM(vNoOfUnit) as vNoOfUnit, SUM(netAmt) as netAmt ,SUM(salesAmount) as salesAmount
                             FROM ( ";
        $groupby = "  ) as temp GROUP BY vendor ";
		$orderby='';
        $orderby1 =  "  ORDER BY vendor, refundtype DESC ";
		$adminGroupListQuery=str_replace("sc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=str_replace("rc.dDate as dDate,",' ',$adminGroupListQuery);
		$adminGroupListQuery=$group_select.$adminGroupListQuery.$groupby;
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
/*
    function getGdsReport($extraCheck_t,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
                                            tb.nAirline as airline,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
                                            tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,tb.dDate,tb.nGDS,tb.vAirpnr,' ' as lpo,tb.vPassenger,
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vBookedBy as BookedBy, xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xo.nGDS,xo.vAirpnr,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vBookedBy as BookedBy,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vBookedBy as BookedBy,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            tb.vBookedBy as BookedBy,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vBookedBy as BookedBy,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
                                            tfd.nNetVendor as collected,tfd.vFaceValue as profit,tb.nTId as tableId,tb.vBookedBy as BookedBy,
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
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,
                                            xo.vBookedBy as BookedBy,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
	*/
/* 	
    function getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$SalesType)
    {
        switch($SalesType)
        {
            case "All":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            xbd.nCustomerAc as account,' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                            rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
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
            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                                            'Hotel' as sale_type,hv.vIssuedBy,' ' as airline,hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,
                                            hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,hv.dDate,
                                            hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
                                            hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,'' as netAmt,hv.vTotalFC as fareVal FROM {PRE}HotelVoucher
                                            hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
                                            ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode WHERE
                                            hv.vActive='1' AND hv.nHVId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
                                            ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL (SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN
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
        $adminGroupListQuery = "(SELECT hv.*,hv.nHVId as tableId,'Hotel' as sale_type FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                                            'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
                                            xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,xo.dDate,
                                            ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xo.vGrandToalTax as taxTotal,xpd.nORCAmt as cmtAmount,
                                            xo.vGrandFare as netAmt,xpd.vFCTax as fareVal FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
                                            LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
                                            LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
                                            aha.nId =xbd.nCustomerAc WHERE xo.vActive='1' AND xo.nXId IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails
                                            scd WHERE scd.vType = 'Xo') OR xo.nXId IN (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE
                                            rcd.vType = 'Xo') ".$extraCheck_x." ORDER BY xo.nXId DESC) UNION ALL (SELECT hv.nHVId,hv.vDocNumber,hv.nLocation,
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
            case "Tkt":
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
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
                $adminGroupListQuery = "(SELECT  xo.nXId,xo.vDocNumber,xo.nLocation,
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
                break;
            case "Tkt":
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
                break;
            case "Tkt":
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
                break;
            case "Tkt":
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
*/
}//end class
?>