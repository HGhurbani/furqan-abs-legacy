<?php ini_set('memory_limit', '-1');
class Unpost_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function filterLocation($division)
	{
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
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
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1' ORDER BY nPosition DESC");
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
        return($locGroupRow);
	}//end function

	function generateCustAcc($mode)
	{
		$custAccGroupRow = array();
		switch($mode)
		{
			case "Cr" :
				$custAccGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");

/*				$custAccGroup    = $this->db->query("SELECT aca.*,c.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca
													  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN
														{PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
													  LEFT JOIN {PRE}CustomerMaster c ON c.nFId = ahc.nId WHERE ah.vActive = '1'
														AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
*/				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
						//$custAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vACode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;

			case "Ca" :
				$custAccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca
													  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN
														{PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
													  WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' ORDER BY nId DESC");
				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vACode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;

			case "KN" :
				$custAccGroup    = $this->db->query("SELECT * FROM {PRE}Card ORDER BY nCId DESC");
				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;
		}//end switch

		return($custAccGroupRow);
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

	//fucntion sales type
	function salesType()
	{
		$salesArray = array('All' => 'All','TS' => 'Ticket Sales','TR' => 'Ticket Refunds','HS' => 'Hotel Sales',
									'HR' => 'Hotel Refunds','XS' => 'XO Sales',
								'XR' => 'XO Refunds');

	    return ($salesArray);
	}//end function

    function getTypeValue()
    {
        $typeGroupRow    = array("N" => "Normal", "V" => "Void", "C" => "Conjuction", "R" => "Re issue", "0" => ALL );
        return ($typeGroupRow);
    }//end function

	//fucntion document type
	function docType()
	{
		$docArray = array('UD' => 'Unpost Documents','UDS' => 'Unpost Documents By Staff',
								'UDD' => 'Unpost Documents By Date');

	    return ($docArray);
	}//end function
/*tb.nTId NOT IN (SELECT scd.vTktNumber FROM
				                        {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')*/
    function getSalesFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
			
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.clstatus='OPEN' ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.nTId DESC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                                        scd WHERE scd.vType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.nHVId DESC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
		                           	 FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck." GROUP BY xo.vTicktManual ORDER BY xo.nXId DESC ";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getRefundFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									    {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.nTId DESC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.nHVId DESC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
				                     {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck." GROUP BY xo.vTicktManual ORDER BY xo.nXId DESC ";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getAllFilterList($extraCheck_tb,$extraCheck_xo,$extraCheck_hv)
    {
        $sqlFilterQuery = "(SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb
                            LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId NOT IN (SELECT scd.vTktNumber FROM
                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                            FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                            scd WHERE scd.vType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM
                            {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
                            FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck_xo.")

                            UNION ALL

                            (SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM
                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
                            td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                            FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy
                            FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck_xo.") ORDER BY dDate DESC";
        session_start();
        $_SESSION['sess_query'] = $sqlFilterQuery;
        $adminGroupList 		= $this->db->query($sqlFilterQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if

        return ($adminGroupRow);

    }//end function

    function getStaffSalesFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId NOT IN (SELECT scd.vTktNumber FROM
				                        {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                                        scd WHERE scd.vType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
		                           	 FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck." GROUP BY ORDER BY xo.nXId xo.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getStaffRefundFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									    {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
				                     {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck." GROUP BY xo.vTicktManual ORDER BY xo.vIssuedBy ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getStaffAllFilterList($extraCheck_tb,$extraCheck_xo,$extraCheck_hv)
    {
        $sqlFilterQuery = "(SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy as vIssuedBy FROM {PRE}TicketBooking tb
                            LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId NOT IN (SELECT scd.vTktNumber FROM
                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,
                            hv.vPassenger,hv.vIssuedBy as vIssuedBy FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                            scd WHERE scd.vType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy as vIssuedBy FROM
                            {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
                            FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck_xo.")

                            UNION ALL

                            (SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy as vIssuedBy FROM
                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
                            td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,hv.vPassenger,hv.vIssuedBy as vIssuedBy
                            FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy as vIssuedBy
                            FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck_xo.") ORDER BY  vIssuedBy ASC";
        session_start();
        $_SESSION['sess_query'] = $sqlFilterQuery;
        $adminGroupList 		= $this->db->query($sqlFilterQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if

        return ($adminGroupRow);

    }//end function

    function getDateSalesFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate as dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId NOT IN (SELECT scd.vTktNumber FROM
				                        {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate as dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                                        scd WHERE scd.vType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate as dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
		                           	 FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck." GROUP BY xo.vTicktManual ORDER BY xo.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getDateRefundFilterList($extraCheck,$type)
    {
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.nLocation,tb.dDate as dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                                        tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,td.vSector1,td.vSector12,td.vSector2,
                                        td.vSector22,td.vSector3,td.vSector33,td.vSector4,td.vSector44,
                                        tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
				                        td ON tb.nTId = td.nTicketId LEFT JOIN
									    {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck." GROUP BY tb.vTicktManual ORDER BY tb.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlTicketfilterQuery;
                $adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT hv.nLocation,hv.dDate as dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                                        hv.vTicktManual,hv.vPassenger,hv.vIssuedBy
                                        FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                                        hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
				                        {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck." GROUP BY hv.vTicktManual ORDER BY hv.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlHotelfilterQuery;
                $adminGroupList 		= $this->db->query($sqlHotelfilterQuery);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow[] = $row;
                    }//end for each
                } //end if
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT xo.nLocation,xo.dDate as dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                                     xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xd.vSector,xd.vSector2,
                                     xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
				                     ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                                     xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
				                     {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck." GROUP BY xo.vTicktManual ORDER BY xo.dDate ASC";
                session_start();
                $_SESSION['sess_query'] = $sqlXofilterQuery;
                $adminGroupList 		= $this->db->query($sqlXofilterQuery);
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

    function getDateAllFilterList($extraCheck_tb,$extraCheck_xo,$extraCheck_hv)
    {
        $sqlFilterQuery = "(SELECT tb.nLocation,tb.dDate as dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy as vIssuedBy FROM {PRE}TicketBooking tb
                            LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId NOT IN (SELECT scd.vTktNumber FROM
                            {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate as dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,hv.vPassenger,hv.vIssuedBy as vIssuedBy
                            FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber	FROM {PRE}SalesClosingDetails
                            scd WHERE scd.vType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate as dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy as vIssuedBy FROM
                            {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId NOT IN (SELECT scd.vTktNumber
                            FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck_xo.")

                            UNION ALL

                            (SELECT tb.nLocation,tb.dDate as dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy as vIssuedBy FROM
                            {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails
                            td ON tb.nTId = td.nTicketId LEFT JOIN
                            {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.nTId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt') ".$extraCheck_tb.")

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate as dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,hv.vPassenger,hv.vIssuedBy as vIssuedBy
                            FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.nHVId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl') ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate as dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy as vIssuedBy
                            FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN {PRE}XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.nXId IN (SELECT rrd.nTktNum FROM
                            {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo') ".$extraCheck_xo.") ORDER BY dDate ASC";
        session_start();
        $_SESSION['sess_query'] = $sqlFilterQuery;
        $adminGroupList 		= $this->db->query($sqlFilterQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if

        return ($adminGroupRow);

    }//end function



    function getUnpostDetails($extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or,$orderby){

       $ticketSales_query = " SELECT tb.dDate as dDate_order, 'index.php/ticket_booking/editTicketbooking/4/e/' as SRlink,tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
        tb.nAirline as nAirline,tb.vGdspnr, tb.vType as vStatus,
        tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,
        tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate1,tfd.nAcc as account,
        ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as
          profit,tb.nTId as tktId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,
        tfd.nORCAmt as creditAmt, tfd.vFaceValue as fareVal , 'sales' as refundtype
          FROM {PRE}TicketBooking tb LEFT JOIN
          {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
      {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
      ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
        LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc
        LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
        WHERE     tb.vConjStatus = 'Yes' AND  tb.clstatus='OPEN' 
        ".$extraCheck_t." ";

/*nTId NOT IN ( SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vTktNumber = tb.nTId AND vType = 'Tkt')*/
      $hotelSales_query = " SELECT hv.dDate as dDate_order, 'index.php/hotel_voucher/editHotelvoucher/4/e/' as SRlink, hv.nHVId as nTId,hv.vDocNumber,hv.nLocation,
        'Hotel' as sale_type,hv.vIssuedBy,' ' as nAirline,hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


        hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate1,
        hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
        hv.nHVId as tktId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt, '' as creditAmt, hv.vTotalRTDC as fareVal ,  'sales' as refundtype
          FROM {PRE}HotelVoucher
          hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
          ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode
          LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
          WHERE
          hv.vActive='1' AND  hv.clstatus='OPEN' 
          ".$extraCheck_h." ";
/*AND hv.nHVId NOT IN ( SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vTktNumber = hv.nHVId AND vType = 'Htl')*/


      $xoSales_query = " SELECT xo.dDate as dDate_order, 'index.php/ticket_booking/editTicketbooking/4/e/' as SRlink, xo.nXId as nTId,xo.vDocNumber,xo.nLocation,
        'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as nAirline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,


        xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate1,
        xo.nPassengerAc as account,
        ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tktId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,
        xpd.nNetVendor as netAmt,'' as creditAmt, xpd.vFCTax as fareVal
          , 'sales' as refundtype
          FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
          LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
          LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
          aha.nId =xbd.nCustomerAc
          LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation
          WHERE xo.vActive='1'  AND  xo.clstatus='OPEN'
          ".$extraCheck_x." ";
/*xo.nXId NOT IN ( SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vTktNumber = xo.nXId AND vType = 'Xo')*/
      $ticketRefund_query = " SELECT rr.dDate as dDate_order,'index.php/refund_register/editRefund/4/e/' as SRlink, tb.nTId ,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy,
        tb.nAirline as nAirline, tb.vGdspnr, tb.vType as vStatus,tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,

        tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate1,tfd.nAcc as account,
        ' ' as lpo,tb.vPassenger,-tfd.nNetVendor as collected,'' as
          profit,rrd.nRefundId as tktId,-tfd.vTaxTotal as taxTotal,-rrd.vComm2 as cmtAmount,
        -rrd.vNetAmt2 as netAmt,
        '' as creditAmt,-tfd.vFaceValue as fareVal,'refund' as refundtype FROM {PRE}TicketBooking tb LEFT JOIN
        {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN
      {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId LEFT JOIN {PRE}Airline ar
      ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
        LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 
        LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation
        INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  tb.nTId 
        INNER JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId 
        WHERE  tb.vConjStatus = 'Yes' AND rrd.vTktType = 'Tkt' 	
        AND  rrd.nTktNum NOT IN (SELECT nTicketNumber FROM {PRE}RefundClosingDetails WHERE nTicketNumber =  rrd.nTktNum AND vType = 'Tkt')
        ".$extraCheck_t." "; 


        $hotelRefund_query = " SELECT  rr.dDate as dDate_order, 'index.php/refund_register/editRefund/4/e/' as SRlink, hv.nHVId as nTId,hv.vDocNumber,hv.nLocation,
        'Hotel' as sale_type,hv.vIssuedBy,' ' as nAirline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,


        hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate1,
        hv.nAcCode as account,hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,
        rrd.nRefundId  as tktId,'' as taxTotal,-rrd.vComm2 as cmtAmount, -rrd.vNetAmt2 as netAmt ,'' as creditAmt, -hv.vTotalRTDC as fareVal, 'refund' as refundtype
          FROM {PRE}HotelVoucher
          hv LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em
          ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 
          LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation
          INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId 
        INNER JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId 
          WHERE hv.vActive='1' AND rrd.vTktType = 'Htl' 
          AND  rrd.nTktNum NOT IN (SELECT nTicketNumber FROM {PRE}RefundClosingDetails WHERE nTicketNumber =  rrd.nTktNum AND vType = 'Htl')
          ".$extraCheck_h." ";


      $xoRefund_query = " SELECT  rr.dDate as dDate_order,'index.php/refund_register/editRefund/4/e/' as SRlink, xo.nXId as nTId,xo.vDocNumber,xo.nLocation,
        'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as nAirline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,
        xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate1,
        xo.nPassengerAc as account,
        ' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,rrd.nRefundId  as tktId,-xpd.vTaxTotal as taxTotal,-rrd.vComm2 as cmtAmount,
        -rrd.vNetAmt2 as netAmt,'' as creditAmt,-xpd.vFCTax as fareVal, 'refund' as refundtype
          FROM {PRE}XoBooking xo LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId
          LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId
          LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId LEFT JOIN {PRE}AccountHeadAccounts aha ON
          aha.nId =xbd.nCustomerAc 
          LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation INNER JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum =  xo.nXId
        INNER JOIN {PRE}RefundRegister rr ON rrd.nRefundId = rr.nRId 
          WHERE  xo.vActive='1' AND rrd.vTktType = 'Xo' 
          AND  rrd.nTktNum NOT IN (SELECT nTicketNumber FROM {PRE}RefundClosingDetails WHERE nTicketNumber =  rrd.nTktNum AND vType = 'Xo')
        ".$extraCheck_x." ";


      $refundOld_query = " SELECT rr.dDate as dDate_order,'index.php/refund_register/editRefund/4/e/' as SRlink, rrd.nRDId as nTId,'' as vDocNumber, rr.nLocation,IF( rrd.vTktType = 'Tkt','OLDT',IF(rrd.vTktType = 'Xo','OLDX','OLDH')) as sale_type,rrd.vIssuedBy as vIssuedBy, ar.nArId as nAirline,  '' as vGdspnr, 'N' as vStatus, rrd.vSalesAmt as salesAmt,rrd.vDiscountAmt as discAmt,rrd.vServicePer as ServicePercent,rrd.vServiceAmt as ServiceAmt, rrd.nTktNum as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate1, rr.nAccount as account,' ' as lpo,rrd.vPassenger as vPassenger,rrd.vSalesAmt as collected,' ' as profit,rrd.nRefundId as tktId, -rrd.vTax as taxTotal,-rrd.vComm2 as cmtAmount, -rrd.vNetAmt2 as netAmt,'' as creditAmt, -rrd.vFareAmt as fareVal, 'refund' as refundtype
        FROM {PRE}RefundRegister rr
        LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'
        LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
        LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
        INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = 32
        LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation
        WHERE rr.vActive='1' AND rrd.vNewOld = 'O'  AND rrd.vNewOld = 'O'
        AND  rrd.nTktNum NOT IN (SELECT nTicketNumber FROM {PRE}RefundClosingDetails WHERE nTicketNumber =  rrd.nTktNum AND vType = rrd.vTktType)
        ".$extraCheck_or." ";



      switch($this->ddlSalesType)
      {
        case "All":
          $query = $ticketSales_query . " UNION ALL ( " . $xoSales_query  . ") UNION ALL ( " . $hotelSales_query . ") UNION ALL ( " . $ticketRefund_query . ") UNION ALL ( " . $xoRefund_query . ") UNION ALL ( " . $hotelRefund_query . ") UNION ALL ( " . $refundOld_query . ") ";
        break;

        case "TS":
          $query = $ticketSales_query;
        break;
        case "TR":
          $query = $ticketRefund_query;
        break;

        case "HS":
          $query = $hotelSales_query;
        break;

        case "HR":
          $query = $hotelRefund_query;
        break;

        case "XS":
          $query = $xoSales_query;
        break;

        case "XR":
          $query = $xoRefund_query;
        break;

      }//end switch

      $adminGroupListQuery = $query . " ORDER BY".$orderby;
	  //vIssuedBy ASC,sale_type ASC,dDate_order ASC,vDocNumber ASC " ; //. $orderby;
      session_start();
      $_SESSION['sess_query'] = $adminGroupListQuery;
      $adminGroupList     = $this->db->query($adminGroupListQuery);
      if ($adminGroupList->num_rows() > 0)
      {
        foreach ($adminGroupList->result() as $key=>$row)
        {
          $adminGroupRow[] = $row;
        }//end for each
      } //end if
      return ($adminGroupRow);
    }// end function









}//end class
?>
