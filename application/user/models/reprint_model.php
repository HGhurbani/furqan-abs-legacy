<?php
class Reprint_model extends CI_Model {

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

    function getActiveCustomer()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        return ($typeGroupRow);
    }//end function

    function getAgentAccounts()
    {
        $agentGroup    = $this->db->query("SELECT nAId,vCode,vName FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
        {
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
            {
                $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        } //end if
        else
        {
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

    function getActiveEmployees()
    {
        $empGroup    = $this->db->query("SELECT nEId,vName,vCode FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY nEId DESC");
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

    function filterLocation($division)
    {
        $adminGroupRow  = array();
        $adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' AND vActive='1'
												  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            $adminGroupRow[0] = SELECT;
            foreach ($adminGroupList->result() as $key => $row)
            {
                $adminGroupRow[$row->nId] = $row->vTitle;
            }//end for each
        } //end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end funtion
        return ($adminGroupRow);
    }//end function

    function getPreviewValues()
    {
        $sqlTicketBookingQuery  = "SELECT tb.*,gds.vName as gdsName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
									CASE
										WHEN tb.vType = 'N' THEN 'Normal'
									WHEN tb.vType = 'V' THEN 'Void'
										WHEN tb.vType = 'C' THEN 'Conjuction'
									ELSE 'Re issue' END as tbType FROM {PRE}TicketBooking tb
									 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId
									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline WHERE vConjStatus = 'Yes' ORDER BY nTId DESC";
        session_start();
        $_SESSION['tkt_query'] = $sqlTicketBookingQuery;

        $sqlXoBookingQuery = "SELECT xo.*,xod.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,
                                ar.vTitle as airlineName,ar.vAirlineNo as airlineNo,xo.vXoNumber as tktNumber,
							  gds.vName as gdsName,
                              CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN
                                xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType FROM {PRE}XoBooking xo
                              LEFT JOIN {PRE}Division d ON d.nId = xo.nDivision LEFT JOIN {PRE}Location l ON
                                l.nId = xo.nLocation LEFT JOIN {PRE}Transactions t ON t.nId = xo.nTransaction
                               LEFT JOIN {PRE}Gds gds
                                ON xo.nGDS = gds.nGId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline LEFT JOIN
                              {PRE}XoBillingDetails xod ON xod.nXoId = xo.nXId ORDER BY nXId DESC";
        $_SESSION['xo_query'] = $sqlXoBookingQuery;

        $sqlHotelVoucherQuery   = "SELECT hv.*,hbd.*,h.vName as hotelName,aha.vTitle as accName,aha.vACode as accCode,
									CASE WHEN hv.vType = 'N' THEN 'Normal' WHEN hv.vType = 'V' THEN 'Void'
									WHEN hv.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as hvType FROM {PRE}HotelVoucher hv
								   LEFT JOIN {PRE}Hotel h ON h.nHId = hv.vHotelName
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId
								   LEFT JOIN {PRE}AccountHeadAccounts aha ON hv.nAcCode = aha.nId ORDER BY nHVId DESC";
        $_SESSION['htl_query'] = $sqlHotelVoucherQuery;
    }//end function

    //searching by airline name or code/ticket number/invoice id/issued by/passenger name/from and to date/accounts
    function getFilterList($extraCheck,$type)
    {
        $adminGroupRow        = array();
        /*$sqlTicketfilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as                                              airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}TicketBooking tb
										LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
										LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = tb.vTicketNumber
											WHERE tb.vConjStatus = 'Yes'
											".$extraCheck." ORDER BY tb.nTId DESC LIMIT ".$this->common_model->_offset." ,
										      ".$this->common_model->_limit;*/
        switch($type)
        {
            case "ticket":
                $sqlTicketfilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
												airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}TicketBooking tb
										LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
										LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nStock = tb.vTicketNumber
											LEFT JOIN {PRE}SalesClosingDetails scd ON tb.nTId = scd.vTktNumber
											WHERE scd.vType = 'Tkt'
											".$extraCheck." ORDER BY tb.nTId DESC";
                session_start();
                $_SESSION['tkt_query'] = $sqlTicketfilterQuery;
                break;

            case "hotel":
                $sqlHotelfilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
				                                airlineNo,g.vName as gdsName,tb.vVoucherId,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}HotelVoucher tb
										LEFT JOIN {PRE}HotelDetails td ON tb.nHVId = td.nHotelId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
										LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nStock = tb.vVoucherId
											LEFT JOIN {PRE}SalesClosingDetails scd ON tb.nHVId = scd.vTktNumber
											WHERE scd.vType = 'Htl'
											".$extraCheck." ORDER BY tb.nHVId DESC";
                session_start();
                $_SESSION['htl_query'] = $sqlHotelfilterQuery;
                break;

            case "xo":
                $sqlXofilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
				                                airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}XoBooking tb
										LEFT JOIN {PRE}XoDetails td ON tb.nXId = td.nXoId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
										LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nStock = tb.vTicketNumber
											LEFT JOIN {PRE}SalesClosingDetails scd ON tb.nXId = scd.vTktNumber
											WHERE scd.vType = 'Xo'
											".$extraCheck." ORDER BY tb.nXId DESC ";
                session_start();
                $_SESSION['xo_query'] = $sqlXofilterQuery;
                break;

            default:
                $sqlTicketfilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
				                                airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}TicketBooking tb
										LEFT JOIN {PRE}TicketDetails td ON tb.nTId = td.nTicketId
											LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline
										LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId
											LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nStock = tb.vTicketNumber
											LEFT JOIN {PRE}SalesClosingDetails scd ON tb.nTId = scd.vTktNumber
											WHERE scd.vType = 'Tkt'
											".$extraCheck." ORDER BY tb.nTId DESC LIMIT ".$this->common_model->_offset." ,
										      ".$this->common_model->_limit;

                break;
        }//end switch
    }//end function

    function getCountfilterList($extraCheck)
    {
        $tabCount = $this->db->query("SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as                                              airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*
                                            FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails td
											  ON tb.nTId = td.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId LEFT JOIN                                               {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId WHERE tb.vConjStatus = 'Yes'
											".$extraCheck." ORDER BY tb.nTId DESC");
        return ($tabCount->num_rows());
    }//end function

    //fucntion sales type
    function salesType()
    {
        $salesArray = array('TS' => 'Ticket Sales','TR' => 'Ticket Refunds','HS' => 'Hotel Sales',
            'HR' => 'Hotel Refunds','XS' => 'XO Sales',
            'XR' => 'XO Refunds','A' => 'ALL');

        return ($salesArray);
    }//end function

    //fucntion document type
    function docType()
    {
        $docArray = array('UD' => 'Unpost Documents','UDS' => 'Unpost Documents By Staff',
            'UDD' => 'Unpost Documents By Date');

        return ($docArray);
    }//end function
}//end class
?>
