<?php
class Sales_closing_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getAllSalesClosing()
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlSalesclosingQuery   = "SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                    scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                   sad ON sc.nSCId = sad.nSalesCloseId GROUP BY sc.nSCId ORDER BY sc.nSCId DESC
								    LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
        session_start();
        $_SESSION['sess_query'] = $sqlSalesclosingQuery;
        $adminGroupList 		= $this->db->query($sqlSalesclosingQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getCountSalesClosing()
    {
        $tabCount = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                    	scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                      sad ON sc.nSCId = sad.nSalesCloseId GROUP BY sc.nSCId ORDER BY sc.nSCId DESC");
		return ($tabCount->num_rows());
    }//end function

    function getSalesDetails($id)
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlSalesclosingQuery   = "SELECT sc.*,scd.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                    scd ON sc.nSCId = scd.nSalesCloseId WHERE sc.nSCId='".$id."' ORDER BY scd.nId DESC";
        $adminGroupList 		= $this->db->query($sqlSalesclosingQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getAllUnclosedTkt()
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlSalesclosingQuery   = "(SELECT tb.nTId,tfd.nAcc as accountName,'Ticket' as sale_type,tb.vIssuedBy,
										tb.nAirline as airlineName,tb.vTicktManual as tktNumber,tb.dDate,
									' ' as lpo,tb.vPassenger,tfd.vSalesAmt,tfd.nNetVendor as netAmt,tfd.vFaceValue as
									profit,tb.vActive,tb.nTId as tableId,
									tb.nLocation,tfd.vFaceValue as fFare,tfd.vTaxTotal as totTax
									 FROM {PRE}TicketBooking tb LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.vConjStatus = 'Yes' AND tb.nTId
									NOT IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')  AND tfd.nNetVendor != ''
									 ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hbd.nCustomerAc as accountName,'Hotel' as
									 sale_type,hv.vIssuedBy,' ' as airlineName,
								  	  hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt,
									hv.vTotalDC as netAmt,' ' as profit,hv.vActive,
									  hv.nHVId as tableId,
									  hv.nLocation,hv.vTotalDc as fFare,'' as totTax
									   FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd 
								    ON hv.nHVId = hbd.nHotelId 
									WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber
		                          		FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')  AND hv.vTotalDC != '' ORDER BY hv.nHVId DESC) UNION ALL 
								  	(SELECT xo.nXId,xo.nPassengerAC as accountName,'Xo' as sale_type,xo.vIssuedBy,
									    xo.nAirline as airlineName,xo.vTicktManual as tktNumber,xo.dDate,
									xbd.vLPO as lpo,xo.vPassenger,xbd.vSalesAmt,xo.vGrandNetFare as netAmt,
								  	    ' ' as profit,xo.vActive,xo.nXId as tableId,
										xo.nLocation,xo.vGrandFare as fFare,xo.vGrandToalTax as totTax
										 FROM {PRE}XoBooking xo
									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId WHERE xo.nXId 
										NOT IN (SELECT scd.vTktNumber
		                           	FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')  AND xo.vGrandNetFare != '' ORDER BY xo.nXId DESC)  ORDER BY YEAR(dDate) ASC,  MONTH(dDate) ASC,  DAY(dDate) ASC, tktNumber ASC  LIMIT "
                                          .$this->common_model->_offset." , ".$this->common_model->_limit;

        session_start();
        $_SESSION['sess_query'] = $sqlSalesclosingQuery;
        $adminGroupList 		= $this->db->query($sqlSalesclosingQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getCountUnclosedTkt()
    {
        $tabCount = $this->db->query("(SELECT tb.nTId,tfd.nAcc as accountName,'Ticket' as sale_type,tb.vIssuedBy,
										tb.nAirline as airlineName,tb.vTicktManual as tktNumber,tb.dDate,
									' ' as lpo,tb.vPassenger,tfd.vSalesAmt,tfd.nNetVendor as netAmt,tfd.vFaceValue as
									profit,tb.vActive,tb.nTId as tableId,
									tb.nLocation,tfd.vFaceValue as fFare,tfd.vTaxTotal as totTax
									 FROM {PRE}TicketBooking tb LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.vConjStatus = 'Yes' AND tb.nTId
									NOT IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')  AND tfd.nNetVendor != ''
									 ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hbd.nCustomerAc as accountName,'Hotel' as
									 sale_type,hv.vIssuedBy,' ' as airlineName,
								  	  hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt,
									hv.vTotalDC as netAmt,' ' as profit,hv.vActive,
									  hv.nHVId as tableId,
									  hv.nLocation,hv.vTotalDc as fFare,'' as totTax
									   FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd 
								    ON hv.nHVId = hbd.nHotelId 
									WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber
		                          		FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')  AND hv.vTotalDC != '' ORDER BY hv.nHVId DESC) UNION ALL 
								  	(SELECT xo.nXId,xo.nPassengerAC as accountName,'Xo' as sale_type,xo.vIssuedBy,
									    xo.nAirline as airlineName,xo.vTicktManual as tktNumber,xo.dDate,
									xbd.vLPO as lpo,xo.vPassenger,xbd.vSalesAmt,xo.vGrandNetFare as netAmt,
								  	    ' ' as profit,xo.vActive,xo.nXId as tableId,
										xo.nLocation,xo.vGrandFare as fFare,xo.vGrandToalTax as totTax
										 FROM {PRE}XoBooking xo
									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId WHERE xo.nXId 
										NOT IN (SELECT scd.vTktNumber
		                           	FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')  AND xo.vGrandNetFare != '' ORDER BY xo.nXId DESC)");
        return ($tabCount->num_rows());
    }//end function

    function getCorresSalesClosing()
    {
		$fetchTitle = $this->db->query("SELECT sc.*,scd.*,sad.* FROM {PRE}SalesClosingDetails scd LEFT JOIN {PRE}SalesClosing sc
										 ON scd.nSalesCloseId = sc.nSCId LEFT JOIN {PRE}SalesAccountDetails sad ON sad.nSalesCloseId = sc.nSCId
										WHERE sc.nSCId = '".$this->uri->segment(6)."'");
        $showVal	= $fetchTitle->row();
        return $showVal;
    }//end function

    function getCorresSalesClosing2()
    {
        $fetchTitle = $this->db->query("SELECT sc.*,scd.*,sad.* FROM {PRE}SalesClosingDetails scd LEFT JOIN {PRE}SalesClosing sc
										 ON scd.nSalesCloseId = sc.nSCId LEFT JOIN {PRE}SalesAccountDetails sad ON sad.nSalesCloseId = sc.nSCId
										WHERE sc.nSCId = '".$this->catId."'");
        $showVal	= $fetchTitle->row();
        return $showVal;
    }//end function

    //get Type details
    function getCorresSalesClosingType($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nId,vType FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[$row->nId] = $row->vType;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getCorresSalesClosingAirline($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nId,nAirline FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[$row->nId] = $row->nAirline;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $acAccRow = array();
        $acAcc    = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$nId."'");

        if ($acAcc->num_rows() > 0)
        {
            foreach ($acAcc->result() as $key=>$row)
            {
                $acAccRow[] = $row->nLoc;
            }//end for each
        }//end if
        return (array_filter($acAccRow));
    }//end function

    function getCorresSalesClosingDetails()
    {
        $salesCloseDetailsRow          = array();
        $salesCloseDetailsType         = array();
        $salesCloseDetailsTicketNumber = array();
        $salesCloseDetailsAirline      = array();
        $salesCloseDetailsEmployee     = array();
        $salesCloseDetailsFare         = array();
        $salesCloseDetailsTax          = array();
        $salesCloseDetailsNetAmt       = array();
        $salesCloseDetailsServPercent  = array();
        $salesCloseDetailsServAmt      = array();
        $salesCloseDetailsDisAmt       = array();
        $salesCloseDetailsCard         = array();
        $salesCloseDetailsComm         = array();
        $salesCloseDetailCommAmt       = array();
        $salesCloseDetailSlsAmt        = array();
        $salesCloseDetailCollected     = array();
        $salesCloseDetailAllocation    = array();
        $salesCloseDetailAgentAmt      = array();
        $salesCloseDetailDrDAc         = array();
        $salesCloseDetailLPO           = array();
        $newValArray          	       = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$this->uri->segment(6)."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsRow[]             = $row->nId;
                $salesCloseDetailsType[]            = $row->vType;
                $salesCloseDetailsTicketNumber[]    = $row->vTktNumber;
                $salesCloseDetailsAirline[]         = $row->nAirline;
                $salesCloseDetailsEmployee[]        = $row->vEmployee;
                $salesCloseDetailsFare[]            = $row->vFare;
                $salesCloseDetailsTax[]             = $row->vTax;
                $salesCloseDetailsNetAmt[]          = $row->vNetAmt;
                $salesCloseDetailsServPercent[]     = $row->vServPercent;
                $salesCloseDetailsServAmt[]         = $row->vServAmt;
                $salesCloseDetailsDisAmt[]          = $row->vDisAmt;
                $salesCloseDetailsCard[]            = $row->nCard;
                $salesCloseDetailsComm[]            = $row->vComm;
                $salesCloseDetailCommAmt[]          = $row->vCommAmt;
                $salesCloseDetailSlsAmt[]           = $row->vSlsAmt;
                $salesCloseDetailCollected[]        = $row->vCollected;
                $salesCloseDetailAllocation[]        = $row->vAllocation;
                $salesCloseDetailAgentAmt[]         = $row->vAgentAmt;
                $salesCloseDetailDrDAc[]            = $row->nDrDAc;
                $salesCloseDetailLPO[]              = $row->vDLPO;
            }//end for each
        }//end if

        $newValArray['sdID']          = $salesCloseDetailsRow;
        $newValArray['sdType']        = $salesCloseDetailsType;
        $newValArray['sdAirline']     = $salesCloseDetailsAirline;
        $newValArray['sdEmployee']    = $salesCloseDetailsEmployee;
        $newValArray['sdTktNumber']   = $salesCloseDetailsTicketNumber;
        $newValArray['sdFace']        = $salesCloseDetailsFare;
        $newValArray['sdTax']         = $salesCloseDetailsTax;
        $newValArray['sdNetAmt']      = $salesCloseDetailsNetAmt;
        $newValArray['sdServPercent'] = $salesCloseDetailsServPercent;
        $newValArray['sdServAmt']     = $salesCloseDetailsServAmt;
        $newValArray['sdDiscAmt']     = $salesCloseDetailsDisAmt;
        $newValArray['sdCard']        = $salesCloseDetailsCard;
        $newValArray['sdCommPer']     = $salesCloseDetailsComm;
        $newValArray['sdCommAmt']     = $salesCloseDetailCommAmt;
        $newValArray['sdSalesAmt']    = $salesCloseDetailSlsAmt;
        $newValArray['sdCollected']   = $salesCloseDetailCollected;
        $newValArray['sdAllocation']   = $salesCloseDetailAllocation;
        $newValArray['sdAgentAmt']    = $salesCloseDetailAgentAmt;
        $newValArray['sdDrAcc']       = $salesCloseDetailDrDAc;
        $newValArray['sdLPO']         = $salesCloseDetailLPO;
        return ($newValArray);
    }//end function

    function getCorresSalesClosingDetails2()
    {
        $salesCloseDetailsRow          = array();
        $salesCloseDetailsType         = array();
        $salesCloseDetailsTicketNumber = array();
        $salesCloseDetailsAirline      = array();
        $salesCloseDetailsEmployee     = array();
        $salesCloseDetailsFare         = array();
        $salesCloseDetailsTax          = array();
        $salesCloseDetailsNetAmt       = array();
        $salesCloseDetailsServPercent  = array();
        $salesCloseDetailsServAmt      = array();
        $salesCloseDetailsDisAmt       = array();
        $salesCloseDetailsCard         = array();
        $salesCloseDetailsComm         = array();
        $salesCloseDetailCommAmt       = array();
        $salesCloseDetailSlsAmt        = array();
        $salesCloseDetailCollected     = array();
        $salesCloseDetailAgentAmt      = array();
        $salesCloseDetailDrDAc         = array();
        $salesCloseDetailLPO           = array();
        $newValArray          	       = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$this->catId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsRow[]             = $row->nId;
                $salesCloseDetailsType[]            = $row->vType;
                $salesCloseDetailsTicketNumber[]    = $row->vTktNumber;
                $salesCloseDetailsAirline[]         = $row->nAirline;
                $salesCloseDetailsEmployee[]        = $row->vEmployee;
                $salesCloseDetailsFare[]            = $row->vFare;
                $salesCloseDetailsTax[]             = $row->vTax;
                $salesCloseDetailsNetAmt[]          = $row->vNetAmt;
                $salesCloseDetailsServPercent[]     = $row->vServPercent;
                $salesCloseDetailsServAmt[]         = $row->vServAmt;
                $salesCloseDetailsDisAmt[]          = $row->vDisAmt;
                $salesCloseDetailsCard[]            = $row->nCard;
                $salesCloseDetailsComm[]            = $row->vComm;
                $salesCloseDetailCommAmt[]          = $row->vCommAmt;
                $salesCloseDetailSlsAmt[]           = $row->vSlsAmt;
                $salesCloseDetailCollected[]        = $row->vCollected;
                $salesCloseDetailAgentAmt[]         = $row->vAgentAmt;
                $salesCloseDetailDrDAc[]            = $row->nDrDAc;
                $salesCloseDetailLPO[]              = $row->vDLPO;
            }//end for each
        }//end if

        $newValArray['sdID']          = $salesCloseDetailsRow;
        $newValArray['sdType']        = $salesCloseDetailsType;
        $newValArray['sdAirline']     = $salesCloseDetailsAirline;
        $newValArray['sdEmployee']    = $salesCloseDetailsEmployee;
        $newValArray['sdTktNumber']   = $salesCloseDetailsTicketNumber;
        $newValArray['sdFace']        = $salesCloseDetailsFare;
        $newValArray['sdTax']         = $salesCloseDetailsTax;
        $newValArray['sdNetAmt']      = $salesCloseDetailsNetAmt;
        $newValArray['sdServPercent'] = $salesCloseDetailsServPercent;
        $newValArray['sdServAmt']     = $salesCloseDetailsServAmt;
        $newValArray['sdDiscAmt']     = $salesCloseDetailsDisAmt;
        $newValArray['sdCard']        = $salesCloseDetailsCard;
        $newValArray['sdCommPer']     = $salesCloseDetailsComm;
        $newValArray['sdCommAmt']     = $salesCloseDetailCommAmt;
        $newValArray['sdSalesAmt']    = $salesCloseDetailSlsAmt;
        $newValArray['sdCollected']   = $salesCloseDetailCollected;
        $newValArray['sdAgentAmt']    = $salesCloseDetailAgentAmt;
        $newValArray['sdDrAcc']       = $salesCloseDetailDrDAc;
        $newValArray['sdLPO']         = $salesCloseDetailLPO;
        return ($newValArray);
    }//end function

    function getCorresSalesClosingAccounts()
    {
        $salesCloseRow          = array();
        $salesCloseAccType      = array();
        $salesCloseAccTktNumbr  = array();
        $salesCloseDrAccMain    = array();
        $salesCloseCard         = array();
        $salesCloseCardComm     = array();
        $salesCloseCardCommAmt  = array();
        $salesCloseAmt          = array();
        $salesCloseCRV          = array();
        $salesCloseRefTkt       = array();
        $salesCloseAcHead       = array();
        $salesCloseAcTable      = array();
        $newValArray          	= array();

        $creditList = $this->db->query("SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId = '".$this->uri->segment(6)."'");
        if ($creditList->num_rows() > 0)
        {
            foreach ($creditList->result() as $key=>$row)
            {
                $salesCloseRow[]         = $row->nId;
                $salesCloseAccType[]     = $row->vTktType;
                $salesCloseAccTktNumbr[] = $row->vAccTktNumber;
                $salesCloseDrAccMain[]   = $row->nDrAccount.'-'.$row->vTable;
                $salesCloseCard[]        = $row->nCard;
                $salesCloseCardComm[]    = $row->vCardComm;
                $salesCloseCardCommAmt[] = $row->vCommAmt;
                $salesCloseAmt[] 		 = $row->vAmt;
                $salesCloseCRV[]         = $row->vCRV;
                $salesCloseRefTkt[]      = $row->vRefTkt;
                $salesCloseAcHead[]      = $row->nAcHead;
                $salesCloseAcTable[]     = $row->vTable;
            }//end for each
        }//end if

        $newValArray['AcID']          = $salesCloseRow;
        $newValArray['AcType']        = $salesCloseAccType;
        $newValArray['AcTktNumber']   = $salesCloseAccTktNumbr;
        $newValArray['AcAccMain']     = $salesCloseDrAccMain;
        $newValArray['AcCard']        = $salesCloseCard;
        $newValArray['AcCardComm']    = $salesCloseCardComm;
        $newValArray['AcCardCommAmt'] = $salesCloseCardCommAmt;
        $newValArray['AcAmt']         = $salesCloseAmt;
        $newValArray['AcCRV']         = $salesCloseCRV;
        $newValArray['AcRefTkt']      = $salesCloseRefTkt;
        $newValArray['AcAcHead']      = $salesCloseAcHead;
        $newValArray['AcTable']       = $salesCloseAcTable;
        return ($newValArray);
    }//end function

    function getCorresSalesClosingAccounts2()
    {
        $salesCloseRow          = array();
        $salesCloseAccType      = array();
        $salesCloseAccTktNumbr  = array();
        $salesCloseDrAccMain    = array();
        $salesCloseCard         = array();
        $salesCloseCardComm     = array();
        $salesCloseCardCommAmt  = array();
        $salesCloseAmt          = array();
        $salesCloseCRV          = array();
        $salesCloseRefTkt       = array();
        $salesCloseAcHead       = array();
        $salesCloseAcTable      = array();
        $newValArray          	= array();

        $creditList = $this->db->query("SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId ='".$this->catId."'");
        if ($creditList->num_rows() > 0)
        {
            foreach ($creditList->result() as $key=>$row)
            {
                $salesCloseRow[]         = $row->nId;
                $salesCloseAccType[]     = $row->vTktType;
                $salesCloseAccTktNumbr[] = $row->vAccTktNumber;
                $salesCloseDrAccMain[]   = $row->nDrAccount.'-'.$row->vTable;
                $salesCloseCard[]        = $row->nCard;
                $salesCloseCardComm[]    = $row->vCardComm;
                $salesCloseCardCommAmt[] = $row->vCommAmt;
                $salesCloseAmt[] 		 = $row->vAmt;
                $salesCloseCRV[]         = $row->vCRV;
                $salesCloseRefTkt[]      = $row->vRefTkt;
                $salesCloseAcHead[]      = $row->nAcHead;
                $salesCloseAcTable[]     = $row->vTable;
            }//end for each
        }//end if

        $newValArray['AcID']          = $salesCloseRow;
        $newValArray['AcType']        = $salesCloseAccType;
        $newValArray['AcTktNumber']   = $salesCloseAccTktNumbr;
        $newValArray['AcAccMain']     = $salesCloseDrAccMain;
        $newValArray['AcCard']        = $salesCloseCard;
        $newValArray['AcCardComm']    = $salesCloseCardComm;
        $newValArray['AcCardCommAmt'] = $salesCloseCardCommAmt;
        $newValArray['AcAmt']         = $salesCloseAmt;
        $newValArray['AcCRV']         = $salesCloseCRV;
        $newValArray['AcRefTkt']      = $salesCloseRefTkt;
        $newValArray['AcAcHead']      = $salesCloseAcHead;
        $newValArray['AcTable']       = $salesCloseAcTable;
        return ($newValArray);
    }//end function

    function getCorresUnclosedSalesDetails($tblId,$type)
    {
        $salesCloseDetailsRow          = array();
        $salesCloseDetailsType         = array();
        $salesCloseDetailsTicketNumber = array();
        $salesCloseDetailsAirline      = array();
        $salesCloseDetailsEmployee     = array();
        $salesCloseDetailsFace         = array();
        $salesCloseDetailsTax          = array();
        $salesCloseDetailsNetAmt       = array();
        $salesCloseDetailsServPercent  = array();
        $salesCloseDetailsServAmt      = array();
        $salesCloseDetailsDisAmt       = array();
        $salesCloseDetailsCard         = array();
        $salesCloseDetailsComm         = array();
        $salesCloseDetailCommAmt       = array();
        $salesCloseDetailSlsAmt        = array();
        $salesCloseDetailCollected     = array();
        $salesCloseDetailAgentAmt      = array();
        $salesCloseDetailDrDAc         = array();
        $salesCloseDetailLPO           = array();

        $salesCloseDetailsAccount      = array();

        $newValArray          	       = array();
        if($type){
            switch($type)
            {
                case "Ticket":
                    $creditDetailsList = $this->db->query("SELECT *,tbd.vSalesAmt as salesVal FROM {PRE}TicketBooking tb LEFT JOIN
                                                           {PRE}TicketBillingDetails tbd ON tbd.nTicketId=tb.nTId
                                                            LEFT JOIN {PRE}TicketFareDetails tfd ON tfd.nTicketId=tb.nTId
														   WHERE tb.nTId='".$tblId."'");
                    if ($creditDetailsList->num_rows() > 0)
                    {
                        foreach ($creditDetailsList->result() as $key=>$row)
                        {
                            $salesCloseDetailsRow             = $row->nTId;
                            $salesCloseDetailsType            = 'Tkt';
                            $salesCloseDetailsTicketNumber    = $row->nTId;
                            $salesCloseDetailsAirline         = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->nAirline."'");
                            $salesCloseDetailsEmployee        = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$row->vIssuedBy."'");
                            $salesCloseDetailsFace            = $row->vFaceValue;
                            $salesCloseDetailsTax             = $row->vTaxTotal;
                            $salesCloseDetailsNetAmt          = $row->nNetVendor;
                            $salesCloseDetailsServPercent     = $row->vServicePercent;
                            $salesCloseDetailsServAmt         = $row->vServiceAmt;
                            $salesCloseDetailsDisAmt          = $row->vDiscountAmt;
                            $salesCloseDetailsCard            = $row->nCard;
                            $salesCloseDetailsComm            = $row->nComm;
                            $salesCloseDetailCommAmt          = $row->nCommAmt;
                            $salesCloseDetailSlsAmt           = $row->salesVal;
                            $salesCloseDetailCollected        = $row->vCollected;
                            $salesCloseDetailAgentAmt         = $row->vAgentAmt;
                            $salesCloseDetailDrDAc            = $row->nDrDAc;
                            $salesCloseDetailLPO              = $row->vLPO;
                            $salesCloseDetailsAccount         = $row->nCustomerAc.'-C';
                        }//end for each
                    }//end if

                    $newValArray['sdID']          = $salesCloseDetailsRow;
                    $newValArray['sdType']        = $salesCloseDetailsType;
                    $newValArray['sdAirline']     = $salesCloseDetailsAirline;
                    $newValArray['sdTktNumber']   = $salesCloseDetailsTicketNumber;
                    $newValArray['sdEmployee']    = $salesCloseDetailsEmployee;
                    $newValArray['sdFace']        = $salesCloseDetailsFace;
                    $newValArray['sdTax']         = $salesCloseDetailsTax;
                    $newValArray['sdNetAmt']      = $salesCloseDetailsNetAmt;
                    $newValArray['sdServPercent'] = $salesCloseDetailsServPercent;
                    $newValArray['sdServAmt']     = $salesCloseDetailsServAmt;
                    $newValArray['sdDiscAmt']     = $salesCloseDetailsDisAmt;
                    $newValArray['sdCard']        = $salesCloseDetailsCard;
                    $newValArray['sdCommPer']     = $salesCloseDetailsComm;
                    $newValArray['sdCommAmt']     = $salesCloseDetailCommAmt;
                    $newValArray['sdSalesAmt']    = $salesCloseDetailSlsAmt;
                    $newValArray['sdCollected']   = $salesCloseDetailCollected;
                    $newValArray['sdAgentAmt']    = $salesCloseDetailAgentAmt;
                    $newValArray['sdDrAcc']       = $salesCloseDetailDrDAc;
                    $newValArray['sdLPO']         = $salesCloseDetailLPO;
                    $newValArray['AcAccMain']     = $salesCloseDetailsAccount;
                break;

                case "Xo":
                    $creditDetailsList = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId = '".$tblId."'
														   ORDER BY xb.nXId DESC");
                    if ($creditDetailsList->num_rows() > 0)
                    {
                        foreach ($creditDetailsList->result() as $key=>$row)
                        {
                            $salesCloseDetailsRow             = $row->nXId;
                            $salesCloseDetailsType            = 'Xo';
                            $salesCloseDetailsTicketNumber    = $row->nXId;
                            $salesCloseDetailsAirline         = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$row->nAirline."'");
                            $salesCloseDetailsEmployee        = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$row->vIssuedBy."'");
                            $salesCloseDetailsFace            = $row->vFCTax;
                            $salesCloseDetailsTax             = $row->vGrandToalTax;
                            $salesCloseDetailsNetAmt          = $row->vGrandNetFare;
                            $salesCloseDetailsServPercent     = $row->vServicePercent;
                            $salesCloseDetailsServAmt         = $row->vServiceAmt;
                            $salesCloseDetailsDisAmt          = $row->vDiscountAmt;
                            $salesCloseDetailsCard            = $row->nCard;
                            $salesCloseDetailsComm            = $row->nComm;
                            $salesCloseDetailCommAmt          = $row->nORCAmt;
                            $salesCloseDetailSlsAmt           = $row->vSalesAmt;
                            $salesCloseDetailCollected        = $row->vCollected;
                            $salesCloseDetailAgentAmt         = $row->vAgentAmt;
                            $salesCloseDetailDrDAc            = $row->nDrDAc;
                            $salesCloseDetailLPO              = $row->vLPO;
                            $salesCloseDetailsAccount         = $row->nCustomerAc.'-C';
                        }//end for each
                    }//end if

                    $newValArray['sdID']          = $salesCloseDetailsRow;
                    $newValArray['sdType']        = $salesCloseDetailsType;
                    $newValArray['sdAirline']     = $salesCloseDetailsAirline;
                    $newValArray['sdTktNumber']   = $salesCloseDetailsTicketNumber;
                    $newValArray['sdEmployee']    = $salesCloseDetailsEmployee;
                    $newValArray['sdFace']        = $salesCloseDetailsFace;
                    $newValArray['sdTax']         = $salesCloseDetailsTax;
                    $newValArray['sdNetAmt']      = $salesCloseDetailsNetAmt;
                    $newValArray['sdServPercent'] = $salesCloseDetailsServPercent;
                    $newValArray['sdServAmt']     = $salesCloseDetailsServAmt;
                    $newValArray['sdDiscAmt']     = $salesCloseDetailsDisAmt;
                    $newValArray['sdCard']        = $salesCloseDetailsCard;
                    $newValArray['sdCommPer']     = $salesCloseDetailsComm;
                    $newValArray['sdCommAmt']     = $salesCloseDetailCommAmt;
                    $newValArray['sdSalesAmt']    = $salesCloseDetailSlsAmt;
                    $newValArray['sdCollected']   = $salesCloseDetailCollected;
                    $newValArray['sdAgentAmt']    = $salesCloseDetailAgentAmt;
                    $newValArray['sdDrAcc']       = $salesCloseDetailDrDAc;
                    $newValArray['sdLPO']         = $salesCloseDetailLPO;
                    $newValArray['AcAccMain']     = $salesCloseDetailsAccount;
                break;

                case "Hotel":
                    $creditDetailsList = $this->db->query("SELECT * FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
                                							on hbd.nHotelId = hv.nHVId WHERE hv.vActive='1' 
														   AND hv.nHVId = '".$tblId."' ORDER BY hv.nHVId DESC");
                    if ($creditDetailsList->num_rows() > 0)
                    {
                        foreach ($creditDetailsList->result() as $key=>$row)
                        {
                            $salesCloseDetailsRow             = $row->nHVId;
                            $salesCloseDetailsType            = 'Htl';
                            $salesCloseDetailsTicketNumber    = $row->nHVId;
                            $salesCloseDetailsAirline         = '0';
                            $salesCloseDetailsEmployee        = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$row->vIssuedBy."'");
                            $salesCloseDetailsFace            = $row->vTotalDC;
                            $salesCloseDetailsTax             = $row->vTaxTotal;
							$salesCloseDetailsNetAmt          = $row->vTotalDC;
                            //$salesCloseDetailsNetAmt          = $row->nNetVendor;
                            $salesCloseDetailsServPercent     = $row->vServicePercent;
                            $salesCloseDetailsServAmt         = $row->vServiceAmt;
                            $salesCloseDetailsDisAmt          = $row->vDiscountAmt;
                            $salesCloseDetailsCard            = $row->nCard;
                            $salesCloseDetailsComm            = $row->nComm;
                            $salesCloseDetailCommAmt          = $row->nCommAmt;
                            $salesCloseDetailSlsAmt           = $row->vSalesAmt;
                            $salesCloseDetailCollected        = $row->vCollected;
                            $salesCloseDetailAgentAmt         = $row->vAgentAmt;
                            $salesCloseDetailDrDAc            = $row->nDrDAc;
                            $salesCloseDetailLPO              = $row->vDLPO;
                            $salesCloseDetailsAccount         = $row->nCustomerAc.'-C';
                        }//end for each
                    }//end if
                    $newValArray['sdID']          = $salesCloseDetailsRow;
                    $newValArray['sdType']        = $salesCloseDetailsType;
                    $newValArray['sdAirline']     = $salesCloseDetailsAirline;
                    $newValArray['sdTktNumber']   = $salesCloseDetailsTicketNumber;
                    $newValArray['sdEmployee']    = $salesCloseDetailsEmployee;
                    $newValArray['sdFace']        = $salesCloseDetailsFace;
                    $newValArray['sdTax']         = $salesCloseDetailsTax;
                    $newValArray['sdNetAmt']      = $salesCloseDetailsNetAmt;
                    $newValArray['sdServPercent'] = $salesCloseDetailsServPercent;
                    $newValArray['sdServAmt']     = $salesCloseDetailsServAmt;
                    $newValArray['sdDiscAmt']     = $salesCloseDetailsDisAmt;
                    $newValArray['sdCard']        = $salesCloseDetailsCard;
                    $newValArray['sdCommPer']     = $salesCloseDetailsComm;
                    $newValArray['sdCommAmt']     = $salesCloseDetailCommAmt;
                    $newValArray['sdSalesAmt']    = $salesCloseDetailSlsAmt;
                    $newValArray['sdCollected']   = $salesCloseDetailCollected;
                    $newValArray['sdAgentAmt']    = $salesCloseDetailAgentAmt;
                    $newValArray['sdDrAcc']       = $salesCloseDetailDrDAc;
                    $newValArray['sdLPO']         = $salesCloseDetailLPO;

                    $newValArray['AcAccMain']     = $salesCloseDetailsAccount;
                break;
            }//end switch
        }//end if
        return ($newValArray);
    }//end function

    function returnSalesPassClosingDetails($sId)
    {
        $salesClosePassenger = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch
                $salesClosePassenger[]    = $this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row->vTktNumber."'");
            }//end for each
        }//end if
        $newVal	 = @join(", ",$salesClosePassenger);
        return ($newVal);
    }//end function

    function returnSalesDateClosingDetails($sId)
    {
        $salesCloseDate = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                    break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                    break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                    break;
                }//end switch
                $salesCloseDate[]    = date('d-m-Y',strtotime($this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row->vTktNumber."'")));
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDate);
        return ($newVal);
    }//end function

    function returnSalesAirClosingDetails($sId)
    {
        $salesCloseAir = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
						$salesCloseAir       = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId = '".$row->vTktNumber."'");
                    break;

                    case "Xo":
						$salesCloseAir       = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId = '".$row->vTktNumber."'");
                    break;

                    case "Htl":
						$salesCloseAir       = '';
                    break;
					
                }//end switch
                $salesCloseAirline[] = $this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId = '".$salesCloseAir."'");
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseAirline);
        return ($newVal);
    }//end function

    function returnSalesNetClosingDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                    break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                    break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                    break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $row->vNetAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnSalesSaleClosingDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                    break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                    break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                    break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $row->vCollected;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnSalesProfitClosingDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $row->vSlsAmt - $row->vNetAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function insertSalesClosing()
    {
		$costOfSalesTkt = 0;
		$CollectedTkt	= 0;
		$costOfSalesHtl = 0;
		$CollectedHtl   = 0;
		$costOfSalesXo  = 0;
		$CollectedXo    = 0;
		
        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
					  'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'vTransMode' => $this->input->post('ddlType'),
					  'vLPO' => $this->input->post('txtLpoMain'),'vInvoiceNumber' => $this->input->post('txtDocNumber'),
					  'nAgentAc' => $this->input->post('ddlAgentAc'),'vTotalFace' => $this->input->post('txtTotalFace'),
					  'vTotalTax' => $this->input->post('txtTotalTax'),'vTotalCommPercent' => $this->input->post('txtTotalCommPercent'),
					  'vTotalCommAmt' => $this->input->post('txtTotalCommAmt'),'vTotalNetAmount' => $this->input->post('txtTotalNetAmount'),
					  'vTotalServPercent' => $this->input->post('txtTotalServPercent'),'vTotalServAmount' => $this->input->post('txtTotalServAmount'),
					  'vTotalSalesAmt' => $this->input->post('txtTotalSalesAmt'),'vTotalDiscAmount' => $this->input->post('txtTotalDiscAmount'),
					  'vTotalCollected' => $this->input->post('txtTotalCollected'),'vTotalAgentAmt' => $this->input->post('txtTotalAgentAmt'),
					  'vPassName' => $this->input->post('txtPassName'), 'vSector1' => $this->input->post('txtSector1'),
					  'vSector2' => $this->input->post('txtSector2'),'vSector3' => $this->input->post('txtSector3'),
					  'vSector4' => $this->input->post('txtSector4'),'vSector5' => $this->input->post('txtSector5'),
					  'vSegment' => $this->input->post('txtSegment'),'vDestination' => $this->input->post('txtDestination'),
					  'vGds' => $this->input->post('txtGds'),'nInvoiceStatus' => $this->input->post('ddlInvoice'),
					  'vRemarks' => $this->input->post('txtRemarks'),'vInvoiceTo' => $this->input->post('txtInvoiceTo'),
                      'vActive' => $this->input->post('chkActive'));
        $this->db->insert('{PRE}SalesClosing', $data);

        $id			  	 = $this->db->insert_id();
		$accTktNumber    = array();

        $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $Employee        = (@array_filter($this->input->post('txtEmployee')) != '')?$this->input->post('txtEmployee'):0;
        $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $Fare      	     = (@array_filter($this->input->post('txtFare')) != '')?$this->input->post('txtFare'):0;
        $Tax      	     = (@array_filter($this->input->post('txtTax')) != '')?$this->input->post('txtTax'):0;
        $Comm       	 = (@array_filter($this->input->post('txtComm')) != '')?$this->input->post('txtComm'):0;
        $CommAmt     	 = (@array_filter($this->input->post('txtCommAmt')) != '')?$this->input->post('txtCommAmt'):0;
        $NetAmt     	 = (@array_filter($this->input->post('txtNetAmt')) != '')?$this->input->post('txtNetAmt'):0;
        $ServPercent   	 = (@array_filter($this->input->post('txtServPercent')) != '')?$this->input->post('txtServPercent'):0;
        $ServAmt     	 = (@array_filter($this->input->post('txtServAmt')) != '')?$this->input->post('txtServAmt'):0;
        $SalesAmt     	 = (@array_filter($this->input->post('txtSalesAmt')) != '')?$this->input->post('txtSalesAmt'):0;
        $DiscAmt     	 = (@array_filter($this->input->post('txtDiscAmt')) != '')?$this->input->post('txtDiscAmt'):0;
        $Collected     	 = (@array_filter($this->input->post('txtCollected')) != '')?$this->input->post('txtCollected'):0;
        $Allocation    = (@array_filter($this->input->post('txtAllocation')) != '')?$this->input->post('txtAllocation'):0;
        $AgentAmt     	 = (@array_filter($this->input->post('txtAgentAmt')) != '')?$this->input->post('txtAgentAmt'):0;
        $lpo         	 = (@array_filter($this->input->post('txtLPO')) != '')?$this->input->post('txtLPO'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

		//create jv 
		$newId = $this->jvInsert($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$this->input->post('txtDocNumber'),$this->input->post('txtDate'));
		
        //insert into sales closed accounts details
        foreach($chkBox as $key => $aitem)
        {
			switch($tktType[$key])
			{
				case "Tkt":
					$salesProfitTkt += ($Collected[$key] - $NetAmt[$key]);
					$CollectedTkt   += $Collected[$key];
					$costOfSalesTkt += $NetAmt[$key];
					$taxTkt			+= $Tax[$key];
					$serviceChrgTkt	 =  '';
					$commTkt		+= $CommAmt[$key];
					$incentiveTkt	+= '';
					$ourChrgTkt		+= '';
					$vendorChrgTkt	+= '';
					$discountTkt    += '';
					$voidChrgTkt	+= '';
				break;
				
				case "Htl":
					$salesProfitHtl += ($Collected[$key] - $NetAmt[$key]);
					$CollectedHtl   += $Collected[$key];
					$costOfSalesHtl += $NetAmt[$key];
					$taxHtl			+= $Tax[$key];
					$serviceChrgHtl	 =  '';
					$commHtl		+= $CommAmt[$key];
					$incentiveHtl	+= '';
					$ourChrgHtl		+= '';
					$vendorChrgHtl	+= '';
					$discountHtl    += '';
					$voidChrgHtl	+= '';
				break;
				
				case "Xo":
					$salesProfitXo  += ($Collected[$key] - $NetAmt[$key]);
					$CollectedXo    += $Collected[$key];
					$costOfSalesXo  += $NetAmt[$key];
					$taxXo			+= $Tax[$key];
					$serviceChrgXo	 =  '';
					$commXo		    += $CommAmt[$key];
					$incentiveXo	+= '';
					$ourChrgXo		+= '';
					$vendorChrgXo	+= '';
					$discountXo     += '';
					$voidChrgXo	    += '';
				break;
			}//end switch
			
			$accTktNumber[] = $tktNumber[$key];
			
            $data = array('nSalesCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
						  'vTktNumber' => $tktNumber[$key],'vNetAmt' => $NetAmt[$key],'vServPercent' => $ServPercent[$key],
						  'vEmployee' => $Employee[$key],'vFare' => $Fare[$key],'vTax' => $Tax[$key],
						  'vServAmt' => $ServAmt[$key],'vDisAmt' => $DiscAmt[$key],'vComm' => $Comm[$key],
						  'vCommAmt' => $CommAmt[$key],'vSlsAmt' => $SalesAmt[$key],'vCollected' => $Collected[$key],
              'vAllocation' => $Allocation[$key],
						  'vAgentAmt' => $AgentAmt[$key],'vDLPO' => $lpo[$key],'vStatus' => 'Closed');
            $this->db->insert('{PRE}SalesClosingDetails', $data);

            switch($tktType[$key])
            {
                case "Tkt":
                    $data 	= array('vLPO' => $lpo[$key],'vSalesAmt' => $SalesAmt[$key],
                                    'vDiscountAmt' => $DiscAmt[$key],'vAgentAmt' => $AgentAmt[$key],
                                    'vCollected' => $Collected[$key],'vServicePercent' => $ServPercent[$key],
                                    'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nTicketId',$tktNumber[$key]);
                    $this->db->update('{PRE}TicketBillingDetails', $data);

                    $data 	= array('vSalesAmt' => $SalesAmt[$key]);
                    $this->db->where('nTicketId',$tktNumber[$key]);
                    $this->db->update('{PRE}TicketFareDetails', $data);
                break;

                case "Htl":
                    $data 	= array('vAgentAmt' => $AgentAmt[$key],'vLPO' => $lpo[$key],
                                    'vSalesAmt' => $SalesAmt[$key],'vDiscountAmt' => $DiscAmt[$key],'vCollected' => $Collected[$key],
                                    'vServicePercent' => $ServPercent[$key],'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nHotelId',$tktNumber[$key]);
                    $this->db->update('{PRE}HotelBillingDetails', $data);
                break;

                case "Xo":
                    $data = array('vLPO' => $lpo[$key],'vSalesAmt' => $SalesAmt[$key],'vDiscountAmt' => $DiscAmt[$key],
                                  'vAgentAmt' => $AgentAmt[$key],'vCollected' => $Collected[$key],
                                  'vServicePercent' => $ServPercent[$key],'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nXoId', $tktNumber[$key]);
                    $this->db->update('{PRE}XoBillingDetails', $data);
                break;
            }//end switch

			//jv details
			$this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$NetAmt[$key],$tktType[$key],$tktNumber[$key]);
        }//end foreach

		//jv sales revenu
		$this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);
		
        $DrAccMain     	 = (@array_filter($this->input->post('ddlDrAccMain')) != '')?$this->input->post('ddlDrAccMain'):0;
        $acCard      	 = (@array_filter($this->input->post('ddlCard')) != '')?$this->input->post('ddlCard'):0;
        $CardComm        = (@array_filter($this->input->post('txtCardComm')) != '')?$this->input->post('txtCardComm'):0;
        $CardCommAmt     = (@array_filter($this->input->post('txtCardCommAmt')) != '')?$this->input->post('txtCardCommAmt'):0;
        $amt             = (@array_filter($this->input->post('txtAmt')) != '')?$this->input->post('txtAmt'):0;
        $CRV   	         = (@array_filter($this->input->post('txtCRV')) != '')?$this->input->post('txtCRV'):0;
        $RefTkt     	 = (@array_filter($this->input->post('txtRefTkt')) != '')?$this->input->post('txtRefTkt'):0;
        $AcHead     	 = (@array_filter($this->input->post('ddlAcHead')) != '')?$this->input->post('ddlAcHead'):0;
        $chkBox2         = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;

        $accTktNumber = @join(",",$accTktNumber);

        //insert into sales closing details
        foreach($chkBox2 as $key2 => $pitem)
        {
            //for ticket accounts
            if(intval($salesProfitTkt) != 0):
				$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
					" WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
			
			
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):
					/*$data      = array('nSalesCloseId' => $id,
					'nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => '4110000','vType' => 'Tkt',
					'vSalesTotalAmt' => $salesProfitTkt,'vTicketNumber' => $accTktNumber,
					'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);*/

					$data     = array('nSalesCloseId' => $id,
					'nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
					'vType' => 'Tkt','vSalesTotalAmt' => $costOfSalesTkt,
					'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

            //for hotel accounts
            if(intval($salesProfitHtl) != 0):
				$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
					" WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
			
			
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):
					/*$data      = array('nSalesCloseId' => $id,
						'nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => '4120000','vType' => 'Htl',
						'vSalesTotalAmt' => $salesProfitHtl,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);*/

					$data      = array('nSalesCloseId' => $id,
						'nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
						'vType' => 'Htl','vSalesTotalAmt' => $costOfSalesHtl,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

            //for xo accounts
            if(intval($salesProfitXo) != 0):
				$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
					" WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
			
			
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):
					/*$data      = array('nSalesCloseId' => $id,
						'nAGId' => 29,'nAccHeadId' => 91,'vAcCode' => '4130000','vType' => 'Xo',
						'vSalesTotalAmt' => $salesProfitXo,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);*/

					$data      = array('nSalesCloseId' => $id,
						'nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
						'vType' => 'Xo','vSalesTotalAmt' => $costOfSalesXo,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->insert('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

			if(count($tktNumber) > 1)
				//call jv total
            	$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$key2],$tktType[$key2],$DrAccMain[$key2],$AcHead[$key2],$acCard[$key2], $CardCommAmt[$key2]);
			else
			{
				if(count($chkBox2) > 1)
				{
					for($i=0;$i<count($chkBox2);$i++):
						//call jv total
            			$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$i],$tktType[0],$DrAccMain[$i],$AcHead[$i],$acCard[$key2], $CardCommAmt[$key2]);
					endfor;
				}//end if 
				else
				{
					//call jv total
            		$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$key2],$tktType[$key2],$DrAccMain[$key2],$AcHead[$key2],$acCard[$key2], $CardCommAmt[$key2]);
				}//end else
			}//end else
			
			$drAccount = @split("-",$DrAccMain[$key2]);
					
			$data = array('nSalesCloseId' => $id,'nDrAccount' => $drAccount[0],
							'nCard' => $acCard[$key2],'vCardComm' => $CardComm[$key2],
							'vCommAmt' => $CardCommAmt[$key2],'vAmt' => $amt[$key2],
							'vCRV' => $CRV[$key2],'vRefTkt' => $RefTkt[$key2],
							'nAcHead' => $AcHead[$key2],'vTable' => $drAccount[1]);
			$this->db->insert('{PRE}SalesAccountDetails', $data);
        }//end foreach

        $msgVal    = 'add_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
        {
            redirect(site_url("sales_closing/editSalesClosing/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if
        if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
        {
//            redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
            redirect(site_url("sales_closing/showUnclosedTicket/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if
    }//end class

    function updateSalesClosing()
    {
        $costOfSalesTkt = 0;
        $CollectedTkt	= 0;
        $costOfSalesHtl = 0;
        $CollectedHtl   = 0;
        $costOfSalesXo  = 0;
        $CollectedXo    = 0;

        $data = array('nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
            'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'vTransMode' => $this->input->post('ddlType'),
            'vLPO' => $this->input->post('txtLpoMain'),'vInvoiceNumber' => $this->input->post('txtDocNumber'),
            'nAgentAc' => $this->input->post('ddlAgentAc'),'vTotalFace' => $this->input->post('txtTotalFace'),
            'vTotalTax' => $this->input->post('txtTotalTax'),'vTotalCommPercent' => $this->input->post('txtTotalCommPercent'),
            'vTotalCommAmt' => $this->input->post('txtTotalCommAmt'),'vTotalNetAmount' => $this->input->post('txtTotalNetAmount'),
            'vTotalServPercent' => $this->input->post('txtTotalServPercent'),'vTotalServAmount' => $this->input->post('txtTotalServAmount'),
            'vTotalSalesAmt' => $this->input->post('txtTotalSalesAmt'),'vTotalDiscAmount' => $this->input->post('txtTotalDiscAmount'),
            'vTotalCollected' => $this->input->post('txtTotalCollected'),'vTotalAgentAmt' => $this->input->post('txtTotalAgentAmt'),
            'vPassName' => $this->input->post('txtPassName'), 'vSector1' => $this->input->post('txtSector1'),
            'vSector2' => $this->input->post('txtSector2'),'vSector3' => $this->input->post('txtSector3'),
            'vSector4' => $this->input->post('txtSector4'),'vSector5' => $this->input->post('txtSector5'),
            'vSegment' => $this->input->post('txtSegment'),'vDestination' => $this->input->post('txtDestination'),
            'vGds' => $this->input->post('txtGds'),'nInvoiceStatus' => $this->input->post('ddlInvoice'),
            'vRemarks' => $this->input->post('txtRemarks'),'vInvoiceTo' => $this->input->post('txtInvoiceTo'),
            'vActive' => $this->input->post('chkActive'));

        $this->db->where('nSCId',$this->input->post('nUId'));
        $this->db->update('{PRE}SalesClosing', $data);

        $id			     = $this->input->post('nUId');
        $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $Employee        = (@array_filter($this->input->post('txtEmployee')) != '')?$this->input->post('txtEmployee'):0;
        $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $Fare      	     = (@array_filter($this->input->post('txtFare')) != '')?$this->input->post('txtFare'):0;
        $Tax      	     = (@array_filter($this->input->post('txtTax')) != '')?$this->input->post('txtTax'):0;
        $Comm       	 = (@array_filter($this->input->post('txtComm')) != '')?$this->input->post('txtComm'):0;
        $CommAmt     	 = (@array_filter($this->input->post('txtCommAmt')) != '')?$this->input->post('txtCommAmt'):0;
        $NetAmt     	 = (@array_filter($this->input->post('txtNetAmt')) != '')?$this->input->post('txtNetAmt'):0;
        $ServPercent   	 = (@array_filter($this->input->post('txtServPercent')) != '')?$this->input->post('txtServPercent'):0;
        $ServAmt     	 = (@array_filter($this->input->post('txtServAmt')) != '')?$this->input->post('txtServAmt'):0;
        $SalesAmt     	 = (@array_filter($this->input->post('txtSalesAmt')) != '')?$this->input->post('txtSalesAmt'):0;
        $DiscAmt     	 = (@array_filter($this->input->post('txtDiscAmt')) != '')?$this->input->post('txtDiscAmt'):0;
        $Collected     	 = (@array_filter($this->input->post('txtCollected')) != '')?$this->input->post('txtCollected'):0;
        $Allocation    = (@array_filter($this->input->post('txtAllocation')) != '')?$this->input->post('txtAllocation'):0;
        $AgentAmt     	 = (@array_filter($this->input->post('txtAgentAmt')) != '')?$this->input->post('txtAgentAmt'):0;
        $lpo         	 = (@array_filter($this->input->post('txtLPO')) != '')?$this->input->post('txtLPO'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;
        $chkAccID	     = $this->input->post('idArray');

        //update jv
        $newId = $this->jvUpdate($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$this->input->post('txtDocNumber'),$this->input->post('txtDate'));

		 //delete jv details
		 $this->db->where('nSjId',$newId);
         $this->db->delete('{PRE}SystemJvDetails');
			
        //insert into Account head accounts table
        foreach($chkBox as $key => $aitem)
        {
            switch($tktType[$key])
            {
                case "Tkt":
                    $salesProfitTkt += ($Collected[$key] - $NetAmt[$key]);
                    $CollectedTkt   += $Collected[$key];
                    $costOfSalesTkt += $NetAmt[$key];
                    $taxTkt			+= $Tax[$key];
                    $serviceChrgTkt	 =  '';
                    $commTkt		+= $CommAmt[$key];
                    $incentiveTkt	+= '';
                    $ourChrgTkt		+= '';
                    $vendorChrgTkt	+= '';
                    $discountTkt    += '';
                    $voidChrgTkt	+= '';
                    break;

                case "Htl":
                    $salesProfitHtl += ($Collected[$key] - $NetAmt[$key]);
                    $CollectedHtl   += $Collected[$key];
                    $costOfSalesHtl += $NetAmt[$key];
                    $taxHtl			+= $Tax[$key];
                    $serviceChrgHtl	 =  '';
                    $commHtl		+= $CommAmt[$key];
                    $incentiveHtl	+= '';
                    $ourChrgHtl		+= '';
                    $vendorChrgHtl	+= '';
                    $discountHtl    += '';
                    $voidChrgHtl	+= '';
                    break;

                case "Xo":
                    $salesProfitXo  += ($Collected[$key] - $NetAmt[$key]);
                    $CollectedXo    += $Collected[$key];
                    $costOfSalesXo  += $NetAmt[$key];
                    $taxXo			+= $Tax[$key];
                    $serviceChrgXo	 =  '';
                    $commXo		    += $CommAmt[$key];
                    $incentiveXo	+= '';
                    $ourChrgXo		+= '';
                    $vendorChrgXo	+= '';
                    $discountXo     += '';
                    $voidChrgXo	    += '';
                    break;
            }//end switch

            $accTktNumber[] = $tktNumber[$key];

            if(intval($chkAccID[$key]) != 0)
            {
                $data 	  = array('vType' => $tktType[$key],'nAirline' => $airline[$key],'vTktNumber' => $tktNumber[$key],
                    'vNetAmt' => $NetAmt[$key],'vServPercent' => $ServPercent[$key],
                    'vEmployee' => $Employee[$key],'vFare' => $Fare[$key],'vTax' => $Tax[$key],
                    'vServAmt' => $ServAmt[$key],'vDisAmt' => $DiscAmt[$key],'vComm' => $Comm[$key],
                    'vCommAmt' => $CommAmt[$key],'vSlsAmt' => $SalesAmt[$key],'vCollected' => $Collected[$key],
                    'vAllocation' => $Allocation[$key],
                    'vAgentAmt' => $AgentAmt[$key],'vDLPO' => $lpo[$key],'vStatus' => 'Closed');
                $this->db->where('nId',$chkAccID[$key]);
                $this->db->update('{PRE}SalesClosingDetails', $data);

                //jv details
				$this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$NetAmt[$key],$tktType[$key],$tktNumber[$key]);
            }//end if
            else
            {
                //if new insert into db
                $data 	  = array('nSalesCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                    'vTktNumber' => $tktNumber[$key],'vNetAmt' => $NetAmt[$key],'vServPercent' => $ServPercent[$key],
                    'vEmployee' => $Employee[$key],'vFare' => $Fare[$key],'vTax' => $Tax[$key],
                    'vServAmt' => $ServAmt[$key],'vDisAmt' => $DiscAmt[$key],'vComm' => $Comm[$key],
                    'vCommAmt' => $CommAmt[$key],'vSlsAmt' => $SalesAmt[$key],'vCollected' => $Collected[$key],
                    'vAgentAmt' => $AgentAmt[$key],'vDLPO' => $lpo[$key],'vStatus' => 'Closed');
                $this->db->insert('{PRE}SalesClosingDetails', $data);

				//jv details
				$this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$NetAmt[$key],$tktType[$key],$tktNumber[$key]);
            }//end else

            switch($tktType[$key])
            {
                case "Tkt":
                    $data 	= array('vLPO' => $lpo[$key],'vSalesAmt' => $SalesAmt[$key],
                                    'vDiscountAmt' => $DiscAmt[$key],'vAgentAmt' => $AgentAmt[$key],
                                    'vCollected' => $Collected[$key],'vServicePercent' => $ServPercent[$key],
                                    'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nTicketId',$tktNumber[$key]);
                    $this->db->update('{PRE}TicketBillingDetails', $data);

                    $data 	= array('vSalesAmt' => $SalesAmt[$key]);
                    $this->db->where('nTicketId',$tktNumber[$key]);
                    $this->db->update('{PRE}TicketFareDetails', $data);
                break;

                case "Htl":
                    $data 	= array('vAgentAmt' => $AgentAmt[$key],'vLPO' => $lpo[$key],
                                    'vSalesAmt' => $SalesAmt[$key],'vDiscountAmt' => $DiscAmt[$key],'vCollected' => $Collected[$key],
                                    'vServicePercent' => $ServPercent[$key],'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nHotelId',$tktNumber[$key]);
                    $this->db->update('{PRE}HotelBillingDetails', $data);
                break;

                case "Xo":
                    $data = array('vLPO' => $lpo[$key],'vSalesAmt' => $SalesAmt[$key],'vDiscountAmt' => $DiscAmt[$key],
                                  'vAgentAmt' => $AgentAmt[$key],'vCollected' => $Collected[$key],
                                  'vServicePercent' => $ServPercent[$key],'vServiceAmt' => $ServAmt[$key]);
                    $this->db->where('nXoId', $tktNumber[$key]);
                    $this->db->update('{PRE}XoBillingDetails', $data);
                break;
            }//end switch

        }//end foreach
		
		//jv sales revenu
		$this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);

        $DrAccMain     	 = (@array_filter($this->input->post('ddlDrAccMain')) != '')?$this->input->post('ddlDrAccMain'):0;
        $acCard      	 = (@array_filter($this->input->post('ddlCard')) != '')?$this->input->post('ddlCard'):0;
        $CardComm        = (@array_filter($this->input->post('txtCardComm')) != '')?$this->input->post('txtCardComm'):0;
        $CardCommAmt     = (@array_filter($this->input->post('txtCardCommAmt')) != '')?$this->input->post('txtCardCommAmt'):0;
        $amt             = (@array_filter($this->input->post('txtAmt')) != '')?$this->input->post('txtAmt'):0;
        $CRV   	         = (@array_filter($this->input->post('txtCRV')) != '')?$this->input->post('txtCRV'):0;
        $RefTkt     	 = (@array_filter($this->input->post('txtRefTkt')) != '')?$this->input->post('txtRefTkt'):0;
        $AcHead     	 = (@array_filter($this->input->post('ddlAcHead')) != '')?$this->input->post('ddlAcHead'):0;
        $chkBox2         = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;
        $chkAcId         = $this->input->post('idAccArray');

        $accTktNumber = @join(",",$accTktNumber);

        //insert into Account head accounts table
        foreach($chkBox2 as $key2 => $aitem)
        {
            //for ticket accounts
            if(intval($salesProfitTkt) != 0):
				$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
				
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):
					/*$data      = array('nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => '4110000',
					'vType' => 'Tkt',
					'vSalesTotalAmt' => $salesProfitTkt,'vTicketNumber' => $accTktNumber,
					'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);*/
	
					$data     = array('nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
					'vType' => 'Tkt','vSalesTotalAmt' => $costOfSalesTkt,
					'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

            //for hotel accounts
            if(intval($salesProfitHtl) != 0):
               $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
					" WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
			
			
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):
					/*$data      = array('nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => '4120000',
					'vType' => 'Htl',
						'vSalesTotalAmt' => $salesProfitHtl,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);*/
		
					$data      = array('nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
						'vType' => 'Htl','vSalesTotalAmt' => $costOfSalesHtl,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

            //for xo accounts
            if(intval($salesProfitXo) != 0):
                $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$AcHead[$key2]."'");
				$AgId     = (intval($AgId) == 0)?0:$AgId;
				$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
					" WHERE nId = '".$acHead."'");
				$acCode   = (intval($acCode) == 0)?0:$acCode;
			
			
				$chkVal  = $this->db->query("SELECT nAGId FROM {PRE}SalesClosedAccounts WHERE
														nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
													AND vDebit = '".$amt[$key2]."'");
				if($chkVal->num_rows() == 0):	
					/*$data      = array('nAGId' => 29,'nAccHeadId' => 91,'vAcCode' => '4130000',
					'vType' => 'Xo',
						'vSalesTotalAmt' => $salesProfitXo,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);*/
		
					$data      = array('nAGId' => $AgId,'nAccHeadId' => $AcHead[$key2],'vAcCode' => $acCode,
						'vType' => 'Xo','vSalesTotalAmt' => $costOfSalesXo,
						'vTicketNumber' => $accTktNumber,'vDebit' => $amt[$key2],'vCredit' => '0');
					$this->db->where('nSalesCloseId',$id);
                	$this->db->update('{PRE}SalesClosedAccounts', $data);
				endif;
            endif;

           if(count($tktNumber) > 1)
				//call jv total
            	$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$key2],$tktType[$key2],$DrAccMain[$key2],$AcHead[$key2],$acCard[$key2], $CardCommAmt[$key2]);
			else
			{
				if(count($chkBox2) > 1)
				{
					for($i=0;$i<count($chkBox2);$i++):
						//call jv total
            			$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$i],$tktType[0],$DrAccMain[$i],$AcHead[$i],$acCard[$key2], $CardCommAmt[$key2]);
					endfor;
				}//end if 
				else
				{
					//call jv total
            		$this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$amt[$key2],$tktType[$key2],$DrAccMain[$key2],$AcHead[$key2],$acCard[$key2], $CardCommAmt[$key2]);
				}//end else
			}//end else
			
			$drAccount = @split("-",$DrAccMain[$key2]);
            if(intval($chkAcId[$key2]) != 0)
            {
                $data 	  = array('nDrAccount' => $drAccount[0],'nCard' => $acCard[$key2],
                                  'vCardComm' => $CardComm[$key2],'vCommAmt' => $CardCommAmt[$key2],
                                  'vAmt' => $amt[$key2],'vCRV' => $CRV[$key2],'vRefTkt' => $RefTkt[$key2],
                                  'nAcHead' => $AcHead[$key2],'vTable' => $drAccount[1]);
                $this->db->where('nId',$chkAcId[$key2]);
                $this->db->update('{PRE}SalesAccountDetails', $data);
            }//end if
            else
            {
                //if new insert into db
                $data = array('nSalesCloseId' => $id,'nDrAccount' => $drAccount[0],'nCard' => $acCard[$key2],
                                'vCardComm' => $CardComm[$key2],'vCommAmt' => $CardCommAmt[$key2],'vAmt' => $amt[$key2],
                                'vCRV' => $CRV[$key2],'vRefTkt' => $RefTkt[$key2],'nAcHead' => $AcHead[$key2],'vTable' => $drAccount[1]);
                $this->db->insert('{PRE}SalesAccountDetails', $data);
            }//end else
        }//end foreach

        $msgVal = 'edit_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != '')
        {
            redirect(site_url($this->session->userdata('backURL')));
        }
        else
        {
            redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }

    }//end function

    function deleteSalesClosing()
    {
        $this->db->delete('{PRE}SalesClosingDetails', array('nSalesCloseId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}SalesAccountDetails', array('nSalesCloseId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}SalesClosing', array('nSCId' => $this->uri->segment(4)));

        $sysId = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId='".$this->uri->segment(4)."' AND vType = 'S'");
        $this->db->delete('{PRE}SystemJvDetails', array('nSjId' => $sysId));
        $this->db->delete('{PRE}SystemJv', array('nId' => $sysId));
        redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/del_suc'));
    }//end function

    function deleteAllSalesClosing()
    {
        $varTypeArray = array();
        $idArray      = array();

        switch($this->input->post('ddlAction'))
        {
            case "yes":
                foreach($this->input->post('del') as $val)
                {
                    //update into db
                    $data 	= array('vActive' => '1');
                    $this->db->where('nSCId',$val);
                    $this->db->update('{PRE}SalesClosing', $data);
                }//end foreach
                redirect(site_url("sales_closing/showlist/".$this->input->post('nCId').'/edit_suc'));
                break;

            case "no":
                foreach($this->input->post('del') as $val)
                {
                    //update into db
                    $data 	= array('vActive' => '0');
                    $this->db->where('nSCId',$val);
                    $this->db->update('{PRE}SalesClosing', $data);
                }//end foreach
                redirect(site_url("sales_closing/showlist/".$this->input->post('nCId').'/edit_suc'));
                break;

            case "delete":
                foreach($this->input->post('del') as $val)
                {
                    $this->db->delete('{PRE}SalesClosingDetails', array('nSalesCloseId' => $val));
                    $this->db->delete('{PRE}SalesAccountDetails', array('nSalesCloseId' => $val));
                    $this->db->delete('{PRE}SalesClosing', array('nSCId' => $val));

                    $sysId = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId='".$val."' AND vType = 'S'");
                    $this->db->delete('{PRE}SystemJvDetails', array('nSjId' => $sysId));
                    $this->db->delete('{PRE}SystemJv', array('nId' => $sysId));
                }//end foreach
                redirect(site_url("sales_closing/showlist/".$this->input->post('nCId').'/del_suc'));
                break;

            default:
                foreach($this->input->post('del') as $valc)
                {
                    $chkVal    = @split("-",$valc);
                    $val	   = $chkVal[0];
                    $type      = $chkVal[1];

                    if(trim($val) != '')
                        $idArray[] = $val;

                    if(trim($type) != '')
                        $varTypeArray[] = $type;
                }//end foreach

                $invoice_id   = @join(",",$idArray);
                $invoice_type = @join(",",$varTypeArray);
                $this->session->set_userdata("selId",$invoice_id);
                $this->session->set_userdata("tType",$invoice_type);

                redirect(site_url("sales_closing/getUnclosedSalesDetails/".$this->input->post('nCId')."/a/unclosed"));
            break;
        }//end switch
    }//end function

    //get account head accounts details
    function getAcAccounts($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vACode;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get sub group details
    function getAcAccountsTitle($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vTitle;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get id
    function getAcAccountsID($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nId;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    function getActiveCustomer()
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($OtherGroupRow,$resultArray);

        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
										   		ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
        return ($resultArray);
    }//end function

    function getCorrActiveCustomer($table)
    {
		$typeGroupRow    = array();
		foreach($table as $key=>$item)
		{
			switch($item)
			{
				case "C" :
					$typeGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
					if ($typeGroup->num_rows() > 0)
					{
						$typeGroupRow[0] = SELECT;
						foreach ($typeGroup->result() as $key=>$row)
						{
							$typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
						}//end for each
					}//end if
				break;

                case "V" :
                    $typeGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
                    if ($typeGroup->num_rows() > 0)
                    {
                        $typeGroupRow[0] = SELECT;
                        foreach ($typeGroup->result() as $key=>$row)
                        {
                            $typeGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
                        }//end for each
                    }//end if
                    break;

                case "AG" :
                    $typeGroup    = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
                    if ($typeGroup->num_rows() > 0)
                    {
                        $typeGroupRow[0] = SELECT;
                        foreach ($typeGroup->result() as $key=>$row)
                        {
                            $typeGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
                        }//end for each
                    }//end if
                    break;

                case "O" :
                    $typeGroup    = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
                    if ($typeGroup->num_rows() > 0)
                    {
                        $typeGroupRow[0] = SELECT;
                        foreach ($typeGroup->result() as $key=>$row)
                        {
                            $typeGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
                        }//end for each
                    }//end if
                    break;

				case "A" :
					$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
															FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
														 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON
														 ahc.nActHId = aca.nId LEFT JOIN {PRE}CustomerMaster C ON C.nFId = ahc.nId
														 WHERE ah.vActive = '1' AND ahc.vSalesType = '1' OR ahc.vSubSale IN ('C','S','A','B')
														 ORDER BY nId DESC");
					if ($typeGroup->num_rows() > 0)
					{
						foreach ($typeGroup->result() as $key1=>$row1)
						{
							$typeGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
						}//end for each
					}//end if
				break;
			}//end switch
		}//end foreach
        return ($typeGroupRow);
    }//end function

    function getActiveAccountHead($accGroup,$table)
    {
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

    function getAccountHead()
    {
        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	    WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
			$accheadGroupRow[0] = SELECT;
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

    //search finance
    function searchActiveFinance($searchKey)
    {
        $typeGroupRow  = array();
        $extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode
							LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle 
						   LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) AND ahc.vSubSale = 'B'";

        if(trim($searchKey) == 0)
        {
            $extaCondition = '';
        }//end if

        $typeGroup = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
										LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 
									   LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
										WHERE ah.vActive = '1' ".$extaCondition." ORDER BY aca.nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[] = $row->vACode.' - '.$row->vTitle;
            }//end for each
        }//end if

        //for json encode
        $result = array();
        foreach ($typeGroupRow as $key=>$value)
        {
            array_push($result, array(
                "name" => $value
            ));
        }//end fuction
        return ($result);
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

    function getLocation()
    {
        $locGroupRow    = array();
        $locGroupRow[0] = SELECT;
        return ($locGroupRow);
    }//end function

    //get location name
    function getLocationName($locArray)
    {
        $locName = array();
        //fetch location name
        foreach($locArray as $val)
        {
            $locName[] = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");
        }//end foreach

        return(@join(",",$locName));
    }//end function

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getActiveTickets()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getTicketType()
    {
        $typeGroupRow = array("0" => SELECT, "Tkt" => "TICKET", "Xo" => "XO", "Htl" => "HOTEL");
        return ($typeGroupRow);
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

    function getTicket($type)
    {
		$val           = ($this->uri->segment(4) != '')?$this->uri->segment(4):'a';

        if($type){
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT tb.* FROM {PRE}TicketBooking tb
													    WHERE tb.nTId NOT IN
													    (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')
													    ORDER BY nTId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each

						 $tcktManul = $this->getManualTicket();
						 if(!empty($tcktManul)):
						 	foreach($tcktManul as $mkey => $mval):
								$adminGroupRow[$mkey] = $mval;
							endforeach;
						endif;

                    } //end if
                    else
                    {
						if($this->getManualTicket()):
                            $adminGroupRow[0] = SELECT;
							$tcktManul = $this->getManualTicket();
							 if(!empty($tcktManul)):
								foreach($tcktManul as $mkey => $mval):
									$adminGroupRow[$mkey] = $mval;
								endforeach;
							endif;
						else:
                      	  $adminGroupRow[0] = NOVALUE;
						endif;
                    }//end else
                    break;

                case "Xo":
                    $adminGroupRow  = array();

                    $adminGroupList = $this->db->query("SELECT xo.* FROM {PRE}XoBooking xo
															WHERE xo.vActive='1' AND xo.nXId NOT IN
															(SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')
															ORDER BY nXId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
															WHERE hv.vActive='1' AND hv.nHVId NOT IN
															(SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
															 ORDER BY nHVId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;
            }//end switch
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else

        return ($adminGroupRow);
    }//end function

    function getClosedTicket($type)
    {
        if($type){
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
/*                    $adminGroupList = $this->db->query("SELECT tb.*,sed.* FROM {PRE}TicketBooking tb
														 LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = tb.vTicketNumber
													    WHERE sed.nId = tb.vTicketNumber AND tb.nTId IN
													    (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')
													    ORDER BY nTId DESC");
*/

                    $adminGroupList = $this->db->query("SELECT tb.* FROM {PRE}TicketBooking tb
													    WHERE tb.nTId IN
													    (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt')
													    ORDER BY nTId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each

						 $tcktManul = $this->getManualTicket();
						 if(!empty($tcktManul)):
						 	foreach($tcktManul as $mkey => $mval):
								$adminGroupRow[$mkey] = $mval;
							endforeach;
						endif;

                    } //end if
                    else
                    {
						if($this->getManualTicket()):
                            $adminGroupRow[0] = SELECT;
							$tcktManul = $this->getManualTicket();
							 if(!empty($tcktManul)):
								foreach($tcktManul as $mkey => $mval):
									$adminGroupRow[$mkey] = $mval;
								endforeach;
							endif;
						else:
                      	  $adminGroupRow[0] = NOVALUE;
						endif;
                    }//end else
                    break;

                case "Xo":
                    $adminGroupRow  = array();

/*                    $adminGroupList = $this->db->query("SELECT xo.*,sed.* FROM {PRE}XoBooking xo
															  LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = xo.vXoNumber
															WHERE xo.vActive='1' AND sed.nId = xo.vXoNumber AND xo.nXId IN
                              (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')
																ORDER BY nXId DESC");*/
                $adminGroupList = $this->db->query("SELECT xo.* FROM {PRE}XoBooking xo
                              WHERE xo.vActive='1' AND xo.nXId IN
                              (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo')
                                ORDER BY nXId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "Htl":
                    $adminGroupList = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
													    	WHERE hv.vActive='1' AND hv.nHVId IN
                                (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl')
													    		 ORDER BY nHVId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;
            }//end switch
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
    }//end function

	function getManualTicket()
    {
		$tktManualRow  = array();
		
		$val = ($this->uri->segment(4) != '')?$this->uri->segment(4):'a';

		switch($val)
		{
			case "a":
				$tktManualList = $this->db->query("SELECT tb.nTId,tb.vTicktManual FROM {PRE}TicketBooking tb
												WHERE tb.nTId NOT IN
												(SELECT vTktNumber FROM {PRE}SalesClosingDetails 
													WHERE vType = 'Tkt')
												ORDER BY tb.nTId DESC");
			break;
			
			case "e":
				$tktManualList = $this->db->query("SELECT tb.nTId,tb.vTicktManual FROM {PRE}TicketBooking tb
												WHERE tb.nTId ORDER BY tb.nTId DESC");
			break;

            case "cl":
				$tktManualList = $this->db->query("SELECT tb.nTId,tb.vTicktManual FROM {PRE}TicketBooking tb
												WHERE tb.nTId ORDER BY tb.nTId DESC");
			break;
			
		}//end switch

		if ($tktManualList->num_rows() > 0)
		{
			foreach ($tktManualList->result() as $key => $row)
			{
				$tktManualRow[$row->nTId] = $row->vTicktManual;
			}//end for each
		} //end if
            
        return ($tktManualRow);
    }//end function
	
    function getCorrActiveLocation($division)
    {
        $corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."'                                              ORDER BY nPosition DESC");
        $corrLocGroupRow = array();

        if ($corrLocGroup->num_rows() > 0)
        {
            //$corrLocGroupRow[0] = SELECT;
            foreach ($corrLocGroup->result() as $key => $row)
            {
                $corrLocGroupRow[$row->nId] = stripslashes($row->vTitle);
            }//end for each
        }//end if
        else
        {
            $corrLocGroupRow[0] = NOVALUE;
        }//end else
        return ($corrLocGroupRow);
    }//end function

    function getTypeValue()
    {
        $typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nId = '1'                                               OR nId = '2' ORDER BY nPosition DESC");
        $typeGroupRow = array();

        if ($typeGroup->num_rows() > 0)
        {
//            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key => $row)
            {
                $typeGroupRow[$row->nId] = stripslashes($row->vTitle);
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        return ($typeGroupRow);
    }//end function

    function getTicketDetails($tkt,$type)
    {
        switch($type){
            case "Tkt":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,tbd.vSalesAmt as salesVal FROM {PRE}TicketBooking tb LEFT JOIN
                                                           {PRE}TicketBillingDetails tbd ON tbd.nTicketId=tb.nTId
                                                            LEFT JOIN {PRE}TicketFareDetails tfd ON tfd.nTicketId=tb.nTId
														   WHERE tb.nTId= '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId = '".$row->vIssuedBy."' ");
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->vFaceValue;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->vServicePercent;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->salesVal;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['accountVal']   = $row->nCustomerAc.'-C';
                        $typeGroupRow['InvoiceTo']    = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->nCustomerAc."'");
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT tb.nGDS,tb.vPassenger,td.* FROM {PRE}TicketBooking tb
													LEFT JOIN {PRE}TicketDetails td
												  ON tb.nTId = td.nTicketId WHERE tb.nTId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if

                break;

            case "Xo":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId = '".$row->vIssuedBy."' ");
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->vFCTax;
                        $typeGroupRow['Tax']          = $row->vGrandToalTax;
                        $typeGroupRow['NetAmt']       = $row->vGrandNetFare;
                        $typeGroupRow['ServPercent']  = $row->vServicePercent;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nORCAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['accountVal']   = $row->nCustomerAc.'-C';
                        $typeGroupRow['InvoiceTo']    = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->nCustomerAc."'");
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId =  '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPass;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if

                break;

            case "Htl":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT * FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
                                							on hbd.nHotelId = hv.nHVId WHERE hv.vActive='1'
														   AND hv.nHVId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key => $row)
                    {
                        $typeGroupRow['Employee']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId = '".$row->vIssuedBy."' ");
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->vTotalDC;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->vServicePercent;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['accountVal']   = $row->nCustomerAc.'-C';
                        $typeGroupRow['InvoiceTo']    = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->nCustomerAc."'");
			$typeGroupRow['checkin']      = $row->dCheckIn;     
			$typeGroupRow['checkout']     = $row->dCheckOut;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
													LEFt JOIN {PRE}HotelBillingDetails hbd
												  ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key => $row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");

                        $typeGroupRow['checkin_perDate']      = $row1->dCheckIn;
                        $typeGroupRow['checkout_perDate']     = $row1->dCheckOut;
                        $typeGroupRow['nights']       = $row1->vNight;
                        $typeGroupRow['hotel']        = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId = '".$row1->vHotelName."' ");
                        $typeGroupRow['country']      = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCountry."' ");
                        $typeGroupRow['city']         = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCity."' ");
                    }//end for each
                }//end if
                break;
        }//end switch
        return ($typeGroupRow);
    }//end function

    function getActiveMode()
    {
        $typeGroupRow = array("0" => SELECT, "Cr" => "Credit", "Ca" => "Cash");
        return ($typeGroupRow);
    }//end function

    function getInvoiceStatus()
    {
        $modeGroupRow = array("N" => "Normal", "E" => "Edited","V" => "Void", "R" => "Refund");
        return ($modeGroupRow);
    }//end function

    function getActiveCards()
    {
        $cardGroup    = $this->db->query("SELECT nCId,vName FROM {PRE}Card WHERE vActive = '1' ORDER BY nCId DESC");
        $cardGroupRow = array();

        if ($cardGroup->num_rows() > 0)
        {
            $cardGroupRow[0] = SELECT;
            foreach ($cardGroup->result() as $key => $row)
            {
                $cardGroupRow[$row->nCId] = $row->vName;
            }//end for each
        }//end if
        else
        {
            $cardGroupRow[0] = NOVALUE;
        }//end else
        return ($cardGroupRow);
    }//end function

    function showTicketDetails($tId,$type)
    {
        switch($type){
            case "Ticket":
                $viewAccessList    = $this->db->query("SELECT tb.*,td.*,tfd.* FROM {PRE}TicketBooking tb
														LEFT JOIN {PRE}TicketDetails td
														ON tb.nTId = td.nTicketId LEFT JOIN {PRE}TicketFareDetails tfd
													   ON tb.nTId = tfd.nTicketId WHERE tb.nTId = '".$tId."'");
                $commVal  		   = $viewAccessList->row();
                switch($commVal->vFare)
                {
                    case "S" :
                        $fare	= 'Special';
                    break;

                    case "N" :
                        $fare	= 'Normal';
                    break;
                }//end switch

                switch($commVal->vType)
                {
                    case "Tkt" :
                        $type	= 'Ticket';
                    break;

                    case "Xo" :
                        $type	= 'Xo';
                    break;

                    case "Htl" :
                        $type	= 'Hotel';
                    break;
                }//end switch

                $content = '<table>
						<tr>
							<td><strong>'.GDS.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$commVal->nGDS."' ").'</td>
							<td><strong>'.ORIGINALTKT.'</strong> :&nbsp;&nbsp;'.$commVal->vOriginalTkt.'</td>
						    <td><strong>'.SEGMENT.'</strong> :&nbsp;&nbsp;'.$commVal->vSegment.'</td>
						    <td><strong>'.DESTINATION.'</strong> :&nbsp;&nbsp;'.$commVal->vDestination.'</td>
							<td><strong>'.GDSPNR.'</strong> :&nbsp;&nbsp;'.$commVal->vGdspnr.'</td>
							<td><strong>'.AIRPNR.'</strong> :&nbsp;&nbsp;'.$commVal->vAirpnr.'</td>
							<td colspan="5"><strong>'.TYPE.'</strong> :&nbsp;&nbsp;'.$type.'</td>
						</tr>

						<tr>
							<td colspan="11"><strong>'.SECTORDETAILS.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.SRNO.'</strong></td>
							<td><strong>'.CARRIER.'</strong></td>
							<td><strong>'.FLIGHTNO.'</strong></td>
							<td><strong>'.TKTCLASS.'</strong></td>
							<td><strong>'.TRAVELDATE.'</strong></td>
							<td><strong>'.SECTOR.'</strong></td>
							<td><strong>&nbsp;</strong></td>
							<td><strong>'.STATUS.'</strong></td>
							<td><strong>'.TIMEDEP.'</strong></td>
							<td><strong>'.TIMEARR.'</strong></td>
							<td><strong>'.DAY.'</strong></td>
						</tr>

						<tr>
							<td>1</td>
							<td>'.$commVal->vCarrier1.'</td>
							<td>'.$commVal->vFlight1.'</td>
							<td>'.$commVal->vClass1.'</td>
							<td>'.$commVal->dTravelDate1.'</td>
							<td>'.$commVal->vSector1.'</td>
							<td>'.$commVal->vSector12.'</td>
							<td>'.$commVal->vStatus1.'</td>
							<td>'.$commVal->vTimeDept1.'</td>
							<td>'.$commVal->vTimeArr1.'</td>
							<td>'.$commVal->vDay1.'</td>
						</tr>

						<tr>
							<td>2</td>
							<td>'.$commVal->vCarrier2.'</td>
							<td>'.$commVal->vFlight2.'</td>
							<td>'.$commVal->vClass2.'</td>
							<td>'.$commVal->dTravelDate2.'</td>
							<td>'.$commVal->vSector2.'</td>
							<td>'.$commVal->vSector22.'</td>
							<td>'.$commVal->vStatus2.'</td>
							<td>'.$commVal->vTimeDept2.'</td>
							<td>'.$commVal->vTimeArr2.'</td>
							<td>'.$commVal->vDay2.'</td>
						</tr>

						<tr>
							<td>3</td>
							<td>'.$commVal->vCarrier3.'</td>
							<td>'.$commVal->vFlight3.'</td>
							<td>'.$commVal->vClass3.'</td>
							<td>'.$commVal->dTravelDate3.'</td>
							<td>'.$commVal->vSector3.'</td>
							<td>'.$commVal->vSector33.'</td>
							<td>'.$commVal->vStatus3.'</td>
							<td>'.$commVal->vTimeDept3.'</td>
							<td>'.$commVal->vTimeArr3.'</td>
							<td>'.$commVal->vDay3.'</td>
						</tr>

						<tr>
							<td>4</td>
							<td>'.$commVal->vCarrier4.'</td>
							<td>'.$commVal->vFlight4.'</td>
							<td>'.$commVal->vClass4.'</td>
							<td>'.$commVal->dTravelDate4.'</td>
							<td>'.$commVal->vSector4.'</td>
							<td>'.$commVal->vSector44.'</td>
							<td>'.$commVal->vStatus4.'</td>
							<td>'.$commVal->vTimeDept4.'</td>
							<td>'.$commVal->vTimeArr4.'</td>
							<td>'.$commVal->vDay4.'</td>
						</tr>

						<tr>
							<td colspan="11"><strong>'.FAREDETAILS.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.FARE.' :</strong></td>
							<td>'.$fare.'</td>

							<td><strong>'.TKTFARECODE.' :</strong></td>
							<td>'.$commVal->vFareCode.'</td>

							<td><strong>'.FACEFARE.' :</strong></td>
							<td>'.$commVal->vFaceValue.'</td>

							<td><strong>'.MARKETFARE.' :</strong></td>
							<td colspan="4">'.$commVal->vMKTFare.'</td>
						</tr>

						<tr>
							<td><strong>'.TAX.' :</strong></td>
							<td>'.$commVal->vTax1.'</td>

							<td><strong>'.TAX.' :</strong></td>
							<td>'.$commVal->vTax2.'</td>

							<td><strong>'.TAX.' :</strong></td>
							<td>'.$commVal->vTax3.'</td>

							<td><strong>'.TOTALTAX.' :</strong></td>
							<td colspan="4">'.$commVal->vTaxTotal.'</td>
						</tr>

						<tr>
							<td><strong>'.FCFARE.' :</strong></td>
							<td>'.$commVal->vFCFare.'</td>

							<td><strong>'.FCTAX.' :</strong></td>
							<td>'.$commVal->vFCTax.'</td>

							<td><strong>'.CURRNAME.' :</strong></td>
							<td>'.$this->common_model->get_field_name('Currency','vTitle', " WHERE nId='".$commVal->nCurrency."' ").'</td>

							<td><strong>'.CURRRATE.' :</strong></td>
							<td colspan="4">'.$this->common_model->get_field_name('Currency','vRate', " WHERE nId='".$commVal->nCurrency."' ").'</td>
						</tr>

						<tr>
							<td><strong>'.COMM.' :</strong></td>
							<td>'.$commVal->nComm.'</td>

							<td><strong>'.COMMAMT.' :</strong></td>
							<td>'.$commVal->nCommAmt.'</td>

							<td><strong>'.GSAPERCENT.' :</strong></td>
							<td>'.$commVal->nGSAComm.'</td>

							<td><strong>'.COMMAMT.' :</strong></td>
							<td colspan="5">'.$commVal->nGSAAmt.'</td>
						</tr>

						<tr>
							<td><strong>'.ORCPERCENT.' :</strong></td>
							<td>'.$commVal->nORC.'</td>

							<td><strong>'.COMMAMT.' :</strong></td>
							<td>'.$commVal->nORCAmt.'</td>

							<td><strong>'.SALESAMT.' :</strong></td>
							<td>'.$commVal->vSalesAmt.'</td>

							<td><strong>'.ACCCODENAME.':</strong></td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$commVal->nAcc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$commVal->nAcc.'"').' )'.'</td>

							<td><strong>'.NETTOVENDOR.':</strong></td>
							<td colspan="5">'.$commVal->nNetVendor.'</td>
						</tr>

						<tr>
							<td colspan="11"><strong>'.PASSENGERCONTACT.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.PASSENGERID.'</strong></td>
							<td>'.$commVal->vPassengerId.'</td>
							<td><strong>'.PASSENGERCONTACT.'</strong></td>
							<td colspan="8">'.$commVal->vPassengerContact.'</td>
						</tr>

						<tr>
							<td colspan="11"><strong>'.CONJUCTIONDETAILS.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.CONJUCTIONTKT.' :</strong></td>
							<td colspan="10">'.$commVal->vConjTkt.'</td>
						</tr>

						<tr>
							<td><strong>'.SRNO.'</strong></td>
							<td><strong>'.CARRIER.'</strong></td>
							<td><strong>'.FLIGHTNO.'</strong></td>
							<td><strong>'.TKTCLASS.'</strong></td>
							<td><strong>'.TRAVELDATE.'</strong></td>
							<td><strong>'.SECTOR.'</strong></td>
							<td><strong>&nbsp;</strong></td>
							<td><strong>'.STATUS.'</strong></td>
							<td><strong>'.TIMEDEP.'</strong></td>
							<td><strong>'.TIMEARR.'</strong></td>
							<td><strong>'.DAY.'</strong></td>
						</tr>

						<tr>
							<td>1</td>
							<td>'.$commVal->vTktCarrier1.'</td>
							<td>'.$commVal->vTktFlight1.'</td>
							<td>'.$commVal->vTktClass1.'</td>
							<td>'.$commVal->dTktTravelDate1.'</td>
							<td>'.$commVal->vTktSector1.'</td>
							<td>'.$commVal->vTktSector12.'</td>
							<td>'.$commVal->vTktStatus1.'</td>
							<td>'.$commVal->vTktTimeDept1.'</td>
							<td>'.$commVal->vTktTimeArr1.'</td>
							<td>'.$commVal->vTktDay1.'</td>
						</tr>

						<tr>
							<td>2</td>
							<td>'.$commVal->vTktCarrier2.'</td>
							<td>'.$commVal->vTktFlight2.'</td>
							<td>'.$commVal->vTktClass2.'</td>
							<td>'.$commVal->dTktTravelDate2.'</td>
							<td>'.$commVal->vTktSector2.'</td>
							<td>'.$commVal->vTktSector22.'</td>
							<td>'.$commVal->vTktStatus2.'</td>
							<td>'.$commVal->vTktTimeDept2.'</td>
							<td>'.$commVal->vTktTimeArr2.'</td>
							<td>'.$commVal->vTktDay2.'</td>
						</tr>

						<tr>
							<td>3</td>
							<td>'.$commVal->vTktCarrier3.'</td>
							<td>'.$commVal->vTktFlight3.'</td>
							<td>'.$commVal->vTktClass3.'</td>
							<td>'.$commVal->dTktTravelDate3.'</td>
							<td>'.$commVal->vTktSector3.'</td>
							<td>'.$commVal->vTktSector33.'</td>
							<td>'.$commVal->vTktStatus3.'</td>
							<td>'.$commVal->vTktTimeDept3.'</td>
							<td>'.$commVal->vTktTimeArr3.'</td>
							<td>'.$commVal->vTktDay3.'</td>
						</tr>

						<tr>
							<td>4</td>
							<td>'.$commVal->vTktCarrier4.'</td>
							<td>'.$commVal->vTktFlight4.'</td>
							<td>'.$commVal->vTktClass4.'</td>
							<td>'.$commVal->dTktTravelDate4.'</td>
							<td>'.$commVal->vTktSector4.'</td>
							<td>'.$commVal->vTktSector44.'</td>
							<td>'.$commVal->vTktStatus4.'</td>
							<td>'.$commVal->vTktTimeDept4.'</td>
							<td>'.$commVal->vTktTimeArr4.'</td>
							<td>'.$commVal->vTktDay4.'</td>
						</tr>';
                $conjId	     = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vConjTkt = '".$commVal->nTId."'");

                $showConjVal = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTDId = '".$conjId."'");

                foreach ($showConjVal->result() as $key1 => $row1)
                {
                    $content .= '
							<tr>
								<td><strong>'.SRNO.'</strong></td>
								<td><strong>'.CARRIER.'</strong></td>
								<td><strong>'.FLIGHTNO.'</strong></td>
								<td><strong>'.TKTCLASS.'</strong></td>
								<td><strong>'.TRAVELDATE.'</strong></td>
								<td><strong>'.SECTOR.'</strong></td>
								<td><strong>&nbsp;</strong></td>
								<td><strong>'.STATUS.'</strong></td>
								<td><strong>'.TIMEDEP.'</strong></td>
								<td><strong>'.TIMEARR.'</strong></td>
								<td><strong>'.DAY.'</strong></td>
							</tr>

							<tr>
								<td>1</td>
								<td>'.$row1->vCarrier1.'</td>
								<td>'.$row1->vFlight1.'</td>
								<td>'.$row1->vClass1.'</td>
								<td>'.$row1->dTravelDate1.'</td>
								<td>'.$row1->vSector1.'</td>
								<td>'.$row1->vSector12.'</td>
								<td>'.$row1->vStatus1.'</td>
								<td>'.$row1->vTimeDept1.'</td>
								<td>'.$row1->vTimeArr1.'</td>
								<td>'.$row1->vDay1.'</td>
							</tr>

							<tr>
								<td>2</td>
								<td>'.$row1->vCarrier2.'</td>
								<td>'.$row1->vFlight2.'</td>
								<td>'.$row1->vClass2.'</td>
								<td>'.$row1->dTravelDate2.'</td>
								<td>'.$row1->vSector2.'</td>
								<td>'.$row1->vSector22.'</td>
								<td>'.$row1->vStatus2.'</td>
								<td>'.$row1->vTimeDept2.'</td>
								<td>'.$row1->vTimeArr2.'</td>
								<td>'.$row1->vDay2.'</td>
							</tr>

							<tr>
								<td>3</td>
								<td>'.$row1->vCarrier3.'</td>
								<td>'.$row1->vFlight3.'</td>
								<td>'.$row1->vClass3.'</td>
								<td>'.$row1->dTravelDate3.'</td>
								<td>'.$row1->vSector3.'</td>
								<td>'.$row1->vSector33.'</td>
								<td>'.$row1->vStatus3.'</td>
								<td>'.$row1->vTimeDept3.'</td>
								<td>'.$row1->vTimeArr3.'</td>
								<td>'.$row1->vDay3.'</td>
							</tr>

							<tr>
								<td>4</td>
								<td>'.$row1->vCarrier4.'</td>
								<td>'.$row1->vFlight4.'</td>
								<td>'.$row1->vClass4.'</td>
								<td>'.$row1->dTravelDate4.'</td>
								<td>'.$row1->vSector4.'</td>
								<td>'.$row1->vSector44.'</td>
								<td>'.$row1->vStatus4.'</td>
								<td>'.$row1->vTimeDept4.'</td>
								<td>'.$row1->vTimeArr4.'</td>
								<td>'.$row1->vDay4.'</td>
							</tr>';
                }//end foreach
                $content .= '</table>';
                break;

            case "Xo":
                $viewAccessList = $this->db->query("SELECT xo.*,xod.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,
														ar.vTitle as airlineName,ar.vAirlineNo,tb.vTicketNumber
													 as tktNumber,gds.vName as gdsName,
													CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN
														xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType
													FROM {PRE}XoBooking xo
													   LEFT JOIN {PRE}Division d ON d.nId = xo.nDivision LEFT JOIN {PRE}Location l ON
														l.nId = xo.nLocation LEFT JOIN {PRE}Transactions t ON t.nId = xo.nTransaction
													LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = xo.vXoNumber LEFT JOIN {PRE}Gds gds
													ON xo.nGDS = gds.nGId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline
													LEFT JOIN {PRE}XoBillingDetails xod ON xod.nXoId = xo.nXId
													WHERE xo.nXId = '".$tId."' ORDER BY xo.nXId DESC");
                foreach ($viewAccessList->result() as $key => $row) {
                    $content .= '
					<table>
						<label><strong>'.PASSENGERDETAILS.'</strong></label>
						<tr>
							<td><strong>'.SRNO.'</strong></td>
							<td><strong>'.PASSENGER.'</strong></td>
							<td><strong>'.FARE.'</strong></td>
							<td><strong>'.COMM.'</strong></td>
							<td><strong>'.COMMAMT.'</strong></td>
							<td><strong>'.FCTAX.':</strong></td>
							<td><strong>'.FCTAX.':</strong></td>
							<td><strong>'.FCTAX.':</strong></td>
							<td><strong>'.TOTALTAX.':</strong></td>
							<td><strong>'.NETTOVENDOR.':</strong></td>
						</tr>';
                    $getPassDetails = $this->db->query("SELECT * FROM {PRE}XoPassDetails WHERE nXoId='".$tId."'");
                    foreach ($getPassDetails->result() as $key2 => $row2) :
                        $content .='<tr>
							<td>'.($key2+1).'</td>
							<td>'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row2->vPass."' ").'('.$this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '".$row2->vPass."' ").')'.'</td>
							<td>'.$row2->vFCTax.'</td>
							<td>'.$row2->nComm.'</td>
							<td>'.$row2->nORCAmt.'</td>
							<td>'.$row2->vTax1.'</td>
							<td>'.$row2->vTax2.'</td>
							<td>'.$row2->vTax3.'</td>
							<td>'.$row2->vTaxTotal.'</td>
							<td>'.$row2->nNetVendor.'</td>
						</tr>';
                    endforeach;

                    $content .='<tr>
							<td><strong>'.GRANDTOTAL.':</strong></td>
							<td>'.$row->vGrandFare.'</td>
							<td></td>
							<td></td>
							<td></td>
							<td>'.$row->vGrandTax1.'</td>
							<td>'.$row->vGrandTax2.'</td>
							<td>'.$row->vGrandTax3.'</td>
							<td>'.$row->vGrandToalTax.'</td>
							<td>'.$row->vGrandNetFare.'</td>
						</tr>';

                    $content .='<tr>
							<td><strong>'.REMARKS.':</strong></td>
							<td>'.$row->vRemarks.'</td>

							<td><strong>'.SEGMENT.' :</strong></td>
							<td>'.$row->vSegment.'</td>

							<td><strong>'.DESTINATION.' :</strong></td>
							<td>'.$row->vDestination.'</td>

							<td><strong>'.GDSPNR.' :</strong></td>
							<td>'.$row->vGdspnr.'</td>

							<td><strong>'.AIRPNR.' :</strong></td>
							<td>'.$row->vAirpnr.'</td>
						</tr>

						<tr>

							<td><strong>'.CURRNAME.' :</strong></td>
							<td>'.$this->common_model->get_field_name('Currency', 'vTitle', " WHERE nId='".$row->nCurrency."' ").'</td>

							<td><strong>'.CURRRATE.' :</strong></td>
							<td colspan="7">'.$this->common_model->get_field_name('Currency', 'vRate', " WHERE nId='".$row->nCurrency."' ").'</td>
						</tr>
						<tr>
							<td colspan="12"><strong>'.TICKETDETAILS.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.CARRIER.'</strong></td>
							<td><strong>'.FLIGHTNO.'</strong></td>
							<td><strong>'.TKTCLASS.'</strong></td>
							<td><strong>'.TRAVELDATE.'</strong></td>
							<td><strong>'.SECTOR.'</strong></td>
							<td><strong>&nbsp;</strong></td>
							<td><strong>'.STATUS.'</strong></td>
							<td><strong>'.TIMEDEP.'</strong></td>
							<td><strong>'.TIMEARR.'</strong></td>
							<td><strong>'.DAY.'</strong></td>
						</tr>';


                    //fetching Ticket details
                    $getTicketDetails = $this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId='".$tId."'");
                    foreach ($getTicketDetails->result() as $key1 => $row1) :
                        $content .='<tr>
								<td>'.$row1->vCarrier.'</td>
								<td>'.$row1->vFlight.'</td>
								<td>'.$row1->vClass.'</td>
								<td>'.$row1->dTravelDate.'</td>
								<td>'.$row1->vSector.'</td>
								<td>'.$row1->vSector2.'</td>
								<td>'.$row1->vStatus.'</td>
								<td>'.$row1->vTimeDept.'</td>
								<td>'.$row1->vTimeArr.'</td>
								<td>'.$row1->vDay.'</td>
							</tr>';
                    endforeach;

                    $content .= '</table>';
                }//end foreach
                break;

            case "Hotel":
                $viewAccessList  = $this->db->query("SELECT hv.*,hbd.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,
														aha.vTitle as accName,aha.vACode as accCode,
														CASE
															WHEN hv.vType = 'N' THEN 'Normal'
															WHEN hv.vType = 'V' THEN 'Void'
															WHEN hv.vType = 'C' THEN 'Conjuction'
														ELSE 'Re issue' END as hvType
													 FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}Division d ON d.nId = hv.nDivision
														LEFT JOIN {PRE}Location l ON l.nId = hv.nLocation LEFT JOIN
													{PRE}Transactions t ON t.nId = hv.nTransaction
														LEFT JOIN {PRE}HotelDetails hd
													ON hv.nHVId = hd.nHotelId LEFT JOIN {PRE}HotelBillingDetails hbd
													 ON hv.nHVId = hbd.nHotelId
														LEFT JOIN {PRE}AccountHeadAccounts aha ON hv.nAcCode = aha.nId
													WHERE hv.nHVId = '".$tId."' ORDER BY hv.nHVId DESC");

                //single fetch
                $commVal  		= $viewAccessList->row();
                switch($commVal->vModeofpay)
                {
                    case "Cr" :
                        $modeofPay	= 'Credit';
                        break;

                    case "Ca" :
                        $modeofPay	= 'Cash';
                        break;

                    case "Card" :
                        $modeofPay	= 'Card';
                        break;
                }//end switch

                $content .= '<table>
								<tr>
									<td colspan="11"><strong>'.ACCNAME.'</strong></td>
								</tr>
								<tr>
									<td><strong>'.DIVISIONNAME.' :</strong>&nbsp;&nbsp;'.$commVal->divName.'</td>
									<td><strong>'.LOCATIONNAME.' :</strong>&nbsp;&nbsp;'.$commVal->locName.'</td>
									<td><strong>'.CUSTDATE.' :</strong>&nbsp;&nbsp;'.$commVal->dDate.'</td>
									<td><strong>'.TRANSNAME.' :</strong>&nbsp;&nbsp;'.$commVal->transName.'</td>
									<td><strong>'.DOCNUMBER.' :</strong>&nbsp;&nbsp;'.$commVal->vDocNumber.'</td>
									<td colspan="6"><strong>'.TYPE.' :</strong>&nbsp;&nbsp;'.$commVal->hvType.'</td>
								</tr>

								<tr>
									<td><strong>'.VOUCHERID.' :</strong>&nbsp;&nbsp;'.$commVal->vVoucherId.'</td>
									<td><strong>'.FILEID.' :</strong>&nbsp;&nbsp;'.$commVal->vFileId.'</td>
									<td><strong>'.BOOKEDBY.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('EmployeeMaster','vName',' WHERE nEId = "'.$commVal->vBookedBy.'" ').'</td>
									<td><strong>'.ISSUEDBY.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('EmployeeMaster','vName',' WHERE nEId = "'.$commVal->vIssuedBy.'" ').'</td>
									<td colspan="7"><strong>'.PASSENGER.' :</strong>&nbsp;&nbsp;'.$commVal->vPassenger.'</td>
								</tr>

								<tr>
									<td><strong>'.COUNTRYNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$commVal->nCountry.'" AND nParentId = "0" ').'</td>
									<td><strong>'.CITYNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$commVal->nCity.'" AND nParentId != "0" ').'</td>
									<td><strong>'.HOTELNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Hotel','vName',' WHERE nHId = "'.$commVal->vHotelName.'" ').'</td>
									<td><strong>'.ROOMTYPE.' :</strong>&nbsp;&nbsp;'.$commVal->vRoomType.'</td>
									<td><strong>'.NOOFUNIT.' :</strong>&nbsp;&nbsp;'.$commVal->vNoOfUnit.'</td>
									<td colspan="6"><strong>'.SERVICES.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Services','vTitle',' WHERE nServId = "'.$commVal->vServices.'" ').'</td>
								</tr>

								<tr>
									<td><strong>'.CHECKIN.' :</strong>&nbsp;&nbsp;'.$commVal->vCheckIn.'</td>
									<td><strong>'.CHECKOUT.' :</strong>&nbsp;&nbsp;'.$commVal->vCheckOut.'</td>
									<td><strong>'.NIGHTS.' :</strong>&nbsp;&nbsp;'.$commVal->vNight.'</td>
									<td><strong>'.ADULT.' :</strong>&nbsp;&nbsp;'.$commVal->vAdult.'</td>
									<td><strong>'.CHILD.' :</strong>&nbsp;&nbsp;'.$commVal->vChild.'</td>
									<td colspan="6"><strong>'.REMARKS.' :</strong>&nbsp;&nbsp;'.$commVal->vRemarks.'</td>
								</tr>

								<tr>
									<td><strong>'.ACCCODENAME.' :</strong>&nbsp;&nbsp;'.($commVal->accName).'( '.($commVal->accCode).' )'.'</td>
									<td><strong>'.CURRNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Currency','vTitle',' WHERE nId = "'.$commVal->nCurrency.'" ').'</td>
									<td colspan="9"><strong>'.CURRRATE.' :</strong>&nbsp;&nbsp;'.$commVal->nRate.'</td>
								</tr>

								<tr>
									<td><strong>'.SRNO.'</strong></td>
									<td><strong>'.ROOMTYPE.'</strong></td>
									<td><strong>'.INDATE.'</strong></td>
									<td><strong>'.OUTDATE.'</strong></td>
									<td><strong>'.NIGHTS.'</strong></td>
									<td><strong>'.NOOFROOMS.'</strong></td>
									<td><strong>'.RATENETFC.'</strong></td>
									<td><strong>'.TOTALINFC.'</strong></td>
									<td><strong>'.RATENETDC.'</strong></td>
									<td><strong>'.TOTALINDC.'</strong></td>
									<td><strong>'.EXCHRATE.'</strong></td>
								</tr>';

                $getHotelDetails = $this->db->query("SELECT *,
																		CASE
																			WHEN vRoomType = 'SGL' THEN 'Single'
																			WHEN vRoomType = 'DBL' THEN 'Double'
																		ELSE 'SUV' END as roomType FROM {PRE}HotelDetails WHERE nHotelId = '".$tId."'");
                foreach ($getHotelDetails->result() as $key2 => $row2) :
                    $content .='<tr>
									<td>'.($key2+1).'</strong></td>
									<td>'.$row2->roomType.'</strong></td>
									<td>'.$row2->dCheckInDate.'</strong></td>
									<td>'.$row2->dCheckOutDate.'</strong></td>
									<td>'.$row2->nNights.'</strong></td>
									<td>'.$row2->nNoOfRooms.'</strong></td>
									<td>'.$row2->nRTNhtFc.'</strong></td>
									<td>'.$row2->vTotalInFc.'</strong></td>
									<td>'.$row2->nRTNhtDc.'</strong></td>
									<td>'.$row2->vTotalInDC.'</strong></td>
									<td>'.$row2->vExchRate.'</strong></td>
								</tr>';
                endforeach;

                $content .='<tr>
							<td colspan="6" align="right"><strong>'.TOTALAMT.'</strong></td>
							<td>'.$commVal->vTotalRTFC.'</strong></td>
							<td>'.$commVal->vTotalFC.'</strong></td>
							<td>'.$commVal->vTotalRTDC.'</strong></td>
							<td colspan="2">'.$commVal->vTotalDC.'</strong></td>
						   </tr>';

                $content .='<tr>
							<td><strong>'.AGENTCONFID.'</strong></td>
							<td>'.$commVal->vAgentConfId.'</td>
							<td><strong>'.HOTELCONFID.'</strong></td>
							<td>'.$commVal->vHotelConfId.'</td>
							<td><strong>'.BOOKINGID.'</strong></td>
							<td colspan="8">'.$commVal->vBookingId.'</td>
						   </tr>';

                $content .='<tr>
							<td colspan="11"><strong>'.BILLINGDETAILS.'</strong></td>
						   </tr>

						   <tr>
							<td><strong>'.INVOICE.':</strong></td>
							<td>'.$commVal->vInvoice.'</td>

							<td><strong>'.MODEOFPAYMENT.':</strong></td>
							<td>'.$modeofPay.'</td>

							<td><strong>'.ACCNAME.':</strong></td>
							<td colspan="6">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$commVal->nAccount.'"').'</td>
						   </tr>

						   <tr>
							<td><strong>'.SUBNAME.':</strong></td>
							<td>'.$commVal->vSubName.'</td>

							<td><strong>'.AGENTAC.':</strong></td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$commVal->nAgentAc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$commVal->nAgentAc.'"').' )'.'</td>

							<td><strong>'.AGENTAMT.':</strong></td>
							<td >'.$commVal->vAgentAmt.'</td>

							<td><strong>'.LPO.':</strong></td>
							<td colspan="5">'.$commVal->vLPO.'</td>
						   </tr>

						   <tr>
							<td><strong>'.SALESAMT.':</strong></td>
							<td>'.$commVal->vSalesAmt.'</td>

							<td><strong>'.DISCOUNT.':</strong></td>
							<td>'.$commVal->vDiscount.'</td>

							<td><strong>'.DISCOUNTAMT.':</strong></td>
							<td colspan="6">'.$commVal->vDiscountAmt.'</td>
						   </tr>

						   <tr>
							<td><strong>'.COLECTED.':</strong></td>
							<td>'.$commVal->vCollected.'</td>

							<td><strong>'.SERVICEPERCENT.':</strong></td>
							<td >'.$commVal->vServicePercent.'</td>

							<td><strong>'.SERVICEAMT.':</strong></td>
							<td colspan="6">'.$commVal->vServiceAmt.'</td>
						   </tr>
						 </table>';
                break;
        }//end switch

        return ($content);
    }//end function

    //fetching single details
    function showDetails($salesID)
    {
        $viewAccessList  = $this->db->query("SELECT * FROM {PRE}SalesClosing WHERE nSCId = '".$salesID."'");
        //single fetch
        $commVal  = $viewAccessList->row();
        $divName  = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$commVal->nDivision."'");
        $locName  = $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$commVal->vLocation."'");

        $content = '<table class="reportForm">
						<tr>
							<td colspan="3"><strong>'.DIVISIONNAME.'</strong> :&nbsp;&nbsp;'.$divName.'</td>
							<td colspan="2"><strong>'.LOCATIONNAME.'</strong> :&nbsp;&nbsp;'.$locName.'</td>
						    <td><strong>'.CUSTDATE.'</strong> :&nbsp;&nbsp;'.$commVal->dDate.'</td>
						    <td><strong>'.SALESTYPE.'</strong> :&nbsp;&nbsp;'.$commVal->vTransMode.'</td>
							<td colspan="3"><strong>'.DOCNUMBER.'</strong> :&nbsp;&nbsp;'.$commVal->vInvoiceNumber.'</td>
						    <td><strong>'.LPO.'</strong> :&nbsp;&nbsp;'.$commVal->vLPO.'</td>
							<td colspan="2"><strong>'.AGENTAC.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$commVal->nAgentAc."' ").'('.$this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '".$commVal->nAgentAc."' ").')'.'</td>
					    </tr>

						<tr>
                            <td><strong>'.TYPE.'</strong></td>
                            <td><strong>'.TICKETNAME.'</strong></td>
                            <td><strong>'.EMPNAME.'</strong></td>
                            <td><strong>'.AIRLINE.'</strong></td>
                            <td><strong>'.FACEFARE.'</strong></td>
                            <td><strong>'.TAX.'</strong></td>
                            <td><strong>'.COMMPERCENT.'</strong></td>
                            <td><strong>'.COMMISSIONAMT.'</strong></td>
                            <td><strong>'.NETAMT.'</strong></td>
                            <td><strong>'.SER.'</strong></td>
                            <td><strong>'.SERAMT.'</strong></td>
                            <td><strong>'.SLSAMT.'</strong></td>
                            <td><strong>'.DISCAMT.'</strong></td>
                            <td><strong>'.COLECTED.'</strong></td>
                            <td><strong>'.AGENTNAME.'</strong></td>
                            <td><strong>'.LPO.'</strong></td>
						</tr>';
        $viewAccessList1  = $this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$salesID."'
											  ORDER BY nId DESC");
        foreach ($viewAccessList1->result() as $key=>$row)
        {
            switch($row->type)
            {
                case "Xo" :
                    $this->_TableName   = 'XoBooking';
                    $this->_extraField  = 'vTicktManual';
                    $this->_tblld       = 'nXId';
                    break;

                case "Ticket" :
                    $this->_TableName   = 'TicketBooking';
                    $this->_extraField  = 'vTicktManual';
                    $this->_tblld       = 'nTId';
                    break;

                case "Hotel" :
                    $this->_TableName   = 'HotelVoucher';
                    $this->_extraField  = 'vTicktManual';
                    $this->_tblld       = 'nHVId';
                    break;
            }//end switch

            $content .= '<tr>
							<td>'.$row->type.'</td>
							<td>'.$this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->_tblld." = '".$row->vTktNumber."'").'</td>
							<td>'.$row->vEmployee.'</td>
							<td>'.$this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."'").'</td>
							<td>'.$row->vFare.'</td>
							<td>'.$row->vTax.'</td>
							<td>'.$row->vComm.'</td>
							<td>'.$row->vCommAmt.'</td>
							<td>'.$row->vNetAmt.'</td>
							<td>'.$row->vServPercent.'</td>
							<td>'.$row->vServAmt.'</td>
							<td>'.$row->vSlsAmt.'</td>
							<td>'.$row->vDisAmt.'</td>
							<td>'.$row->vCollected.'</td>
							<td>'.$row->vAgentAmt.'</td>
							<td>'.$row->vDLPO.'</td>
						</tr>';

        }//end foreach

        $content  .= '
						<tr>
						    <td colspan="3"></td>
						    <td><strong>'.TOTALAMT.'</strong></td>
							<td>'.$commVal->vTotalFace.'</td>
							<td>'.$commVal->vTotalTax.'</td>
							<td>'.$commVal->vTotalCommPercent.'</td>
							<td>'.$commVal->vTotalCommAmt.'</td>
							<td>'.$commVal->vTotalNetAmount.'</td>
							<td>'.$commVal->vTotalServPercent.'</td>
							<td>'.$commVal->vTotalServAmount.'</td>
							<td>'.$commVal->vTotalSalesAmt.'</td>
						    <td>'.$commVal->vTotalDiscAmount.'</td>
						    <td>'.$commVal->vTotalCollected.'</td>
							<td>'.$commVal->vTotalAgentAmt.'</td>
							<td></td>
						</tr>';

        $content .= '<tr>
						<td colspan="2"><strong>'.DRACCNAME.'</strong></td>
						<td colspan="2"><strong>'.ACHEAD.'</strong></td>
						<td colspan="2"><strong>'.CARDNAME.'</strong></td>
						<td colspan="2"><strong>'.COMMPERCENT.'</strong></td>
						<td colspan="2"><strong>'.COMMISSIONAMT.'</strong></td>
						<td colspan="2"><strong>'.AMT.'</strong></td>
						<td colspan="2"><strong>'.CRV.'</strong></td>
						<td colspan="2"><strong>'.REFTKT1.'</strong></td>
					</tr>';

        $viewAccessList2  = $this->db->query("SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId = '".$salesID."'
											  ORDER BY nId DESC");
        foreach ($viewAccessList2->result() as $key1=>$row1)
        {
            switch($row1->vTable)
            {
                case "C" :
                    $accNameId       = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->nDrAccount."' ");
                    break;

                case "V" :
                    $accNameId       = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$row1->nDrAccount."' ");
                    break;

                case "AG" :
                    $accNameId       = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$row1->nDrAccount."' ");
                    break;

                case "O" :
                    $accNameId       = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$row1->nDrAccount."' ");
                    break;

                case "A" :
                    $accNameId       = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row1->nDrAccount."' ");
                    break;
            }//end switch

            $content .= '<tr>
							<td colspan="2">'.$accNameId.'</td>
							<td colspan="2">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row1->nAcHead."'").'</td>
							<td colspan="2">'.$this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$row1->nCard."'").'</td>
							<td colspan="2">'.$row1->vCardComm.'</td>
							<td colspan="2">'.$row1->vCommAmt.'</td>
							<td colspan="2">'.$row1->vAmt.'</td>
							<td colspan="2">'.$row1->vCRV.'</td>
							<td colspan="2">'.$row1->vRefTkt.'</td>
						</tr>';
        }//end foreach

        $content .= '</table>';
        return($content);

    }//end function

    function getSubledgerNo($financeId)
    {
        $fetchTitle = $this->db->query("SELECT ahc.*,sc.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}SalesClosing sc
											ON sc.nAcCode = ahc.nId WHERE ahc.nActHId = '".$financeId."'");
        $showVal	= $fetchTitle->row();
        $chkCode    = $this->getAutomaticCode('SalesClosing',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nSCId');
        return $chkCode;
    }//end function

    function getCommBank($card)
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}Card WHERE nCId = '".$card."'");
        $showVal	= $fetchTitle->row();
        $nCardAc = $showVal->nCardAc . '-A';
        return $nCardAc;
    }//end function

    function getCommAmt($card)
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}Card WHERE nCId = '".$card."'");
        $showVal	= $fetchTitle->row();
        $commAmt	= $showVal->nCommission;
        return $commAmt;
    }//end function

    function getDocNum($type,$loc)
    {
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
        $fetchCode          	= $this->db->query("SELECT vInvoiceNumber FROM {PRE}SalesClosing WHERE vTransMode = '".$type."'
                                                    AND vLocation = '".$loc."' ORDER BY CAST(SUBSTRING_INDEX(vInvoiceNumber,'-',-1) AS UNSIGNED)  DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vInvoiceNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
            $docNumber 			= @split('-',$this->txtDocNumber);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        return($countVal);
    }//end function

    function getAutomaticCode($tableName,$code,$auto,$id)
    {
        if($auto == 1)
        {
            $fetchCode = $this->db->query("SELECT count($id) as totCount FROM {PRE}$tableName");
            $totalNum  = $fetchCode->row();
            switch($totalNum->totCount)
            {
                case "0" :
                    $countVal = $code;
                break;

                default:
                    $countVal = ($totalNum->totCount)+1;
                    $countVal = substr($code,0,strlen($code)-1).$countVal;
                break;
            }//end switch
            return($countVal);
        }//end if
        else
        {
            $this->txtCode = '';
        }//end else
    }//end function

    //function to calcualte online rate
    function onlineCurrencyConverstion()
    {
        $valArray = array();

        //make string to be put in API
        $string = "1".$this->selCurrency."=?".$this->defCurrency;

        //Call Google API
        $google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;

        //Get and Store API results into a variable
        $result = file_get_contents($google_url);

        //Explode result to convert into an array
        $result = explode('"', $result);

        ################################
        # Right Hand Side
        ################################
        $converted_amount = explode(' ', $result[3]);
        $conversion       = $converted_amount[0];
        //$conversion = preg_replace('/[x00-x08x0B-x1F]/', '', $conversion);
        $conversion       = $conversion * $this->fcAmount;
        $conversion       = round($conversion, 2);
        //echo $conversion = number_format($conversion, 2, '.', "");

        //Get text for converted currency
        //$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));

        $varArray['exRate'] = round($converted_amount[0],2);
        $varArray['covAmt'] = $conversion;

        //Make right hand side string
        //$rhs = $conversion.$rhs_text;
        return ($varArray);
    }//end function

    function getRate($cur)
    {
        $fetchTitle = $this->db->query("SELECT vSellingRate FROM {PRE}Currency WHERE nId = '".$cur."'");
        $showVal	= $fetchTitle->row();
        $chkRate    = $showVal->vSellingRate;
        return $chkRate;
    }//end function

    function checkTicket()
    {
        $invoice    = array();
        $fetchTitle = $this->db->query("SELECT vInvoiceNumber FROM {PRE}SalesClosing ");
        foreach ($fetchTitle->result() as $row)
        {
            $invoice[] = $row->vInvoiceNumber;
        }//end if
        return $invoice;
    }//end function

    //function to delete sub and details from db
    function deleteSubAndDetails($ID)
    {
        $this->db->delete('{PRE}SalesClosingDetails', array('nId' => $ID));
    }//end function

    function deleteSubAccountDetails($ID)
    {
        $this->db->delete('{PRE}SalesAccountDetails', array('nId' => $ID));
    }//end function

    //searching by airline name or code/ticket number/invoice id/issued by/passenger name/from and to date/accounts
    function getFilterList($extraCheck_t,$extraCheck_x,$extraCheck_h)
    {
        $adminGroupRow  = array();
        $adminGroupList = $this->db->query("(SELECT tb.nTId,tfd.nAcc as accountName,aha.*,'Ticket' as sale_type,tb.vIssuedBy,
												tb.nAirline as airlineName,tb.vTicktManual as tktNumber,tb.dDate,
											' ' as lpo,tb.vPassenger,tfd.vSalesAmt,tfd.nNetVendor as netAmt,tfd.vFaceValue as
											profit,tb.vActive,tb.nTId as tableId FROM {PRE}TicketBooking tb LEFT JOIN
											 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN {PRE}Airline ar
												 ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId 
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
											NOT IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
											 ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hbd.nCustomerAc as accountName,aha.*,'Hotel' as
											 sale_type,hv.vIssuedBy,' ' as airlineName,
											  hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt,
											hv.vTotalDC as netAmt,' ' as profit,hv.vActive,
											  hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
											ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hbd.nCustomerAc WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber
												FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL
											(SELECT xo.nXId,xbd.nAccName as accountName,aha.*,'Xo' as sale_type,xo.vIssuedBy,
												xo.nAirline as airlineName,xo.vTicktManual as tktNumber,xo.dDate,
											xbd.vLPO as lpo,xo.vPassenger,xbd.vSalesAmt,xo.vGrandNetFare as netAmt,
												' ' as profit,xo.vActive,xo.nXId as tableId FROM {PRE}XoBooking xo
											LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}Airline ar
												 ON ar.nArId = xo.nAirline LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId
												 LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = xbd.nAccName WHERE xo.nXId
												NOT IN (SELECT scd.vTktNumber
											FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck_x."
											ORDER BY xo.nXId DESC)
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
       if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getCountfilterList($extraCheck_t,$extraCheck_x,$extraCheck_h)
    {
        $tabCount = $this->db->query("(SELECT tb.nTId,tfd.nAcc as accountName,aha.*,'Ticket' as sale_type,tb.vIssuedBy,
												tb.nAirline as airlineName,tb.vTicktManual as tktNumber,tb.dDate,
											' ' as lpo,tb.vPassenger,tfd.vSalesAmt,tfd.nNetVendor as netAmt,tfd.vFaceValue as
											profit,tb.vActive,tb.nTId as tableId FROM {PRE}TicketBooking tb LEFT JOIN
											 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId LEFT JOIN {PRE}Airline ar
												 ON ar.nArId = tb.nAirline LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId 
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc WHERE tb.vConjStatus = 'Yes' AND tb.nTId
											NOT IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') ".$extraCheck_t."
											 ORDER BY tb.nTId DESC) UNION ALL (SELECT hv.nHVId,hbd.nCustomerAc as accountName,aha.*,'Hotel' as
											 sale_type,hv.vIssuedBy,' ' as airlineName,
											  hv.vTicktManual as tktNumber,hv.dDate,hbd.vLPO as lpo,hv.vPassenger,hbd.vSalesAmt,
											hv.vTotalDC as netAmt,' ' as profit,hv.vActive,
											  hv.nHVId as tableId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
											ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 
											LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hbd.nCustomerAc WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber
												FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') ".$extraCheck_h." ORDER BY hv.nHVId DESC) UNION ALL
											(SELECT xo.nXId,xbd.nAccName as accountName,aha.*,'Xo' as sale_type,xo.vIssuedBy,
												xo.nAirline as airlineName,xo.vTicktManual as tktNumber,xo.dDate,
											xbd.vLPO as lpo,xo.vPassenger,xbd.vSalesAmt,xo.vGrandNetFare as netAmt,
												' ' as profit,xo.vActive,xo.nXId as tableId FROM {PRE}XoBooking xo
											LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId LEFT JOIN {PRE}Airline ar
												 ON ar.nArId = xo.nAirline LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId
												 LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = xbd.nAccName WHERE xo.nXId
												NOT IN (SELECT scd.vTktNumber
											FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') ".$extraCheck_x."
											ORDER BY xo.nXId DESC)");
        return ($tabCount->num_rows());
    }//end function

    function getClosedFilterList($extraCheck)
    {
        $adminGroupRow  = array();
        $adminGroupList = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                                scd ON sc.nSCId = scd.nSalesCloseId
                                              LEFT JOIN {PRE}SalesAccountDetails sad ON scd.nSalesCloseId = sad.nSalesCloseId
                                            LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = scd.vTktNumber LEFT JOIN {PRE}XoBooking xo
										      ON xo.nXId = scd.vTktNumber LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId = scd.vTktNumber
										   	LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sad.nDrAccount
										   	  LEFT JOIN {PRE}CustomerMaster c ON c.nCId = sad.nDrAccount WHERE sc.vActive = '1' ".$extraCheck."  GROUP BY sc.nSCId
											ORDER BY scd.nSalesCloseId DESC LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getCountclosedFilterList($extraCheck)
    {
        $tabCount = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                            scd ON sc.nSCId = scd.nSalesCloseId
                                         LEFT JOIN {PRE}SalesAccountDetails sad ON scd.nSalesCloseId = sad.nSalesCloseId
                                      LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = scd.vTktNumber LEFT JOIN {PRE}XoBooking xo
										 ON xo.nXId = scd.vTktNumber LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId = scd.vTktNumber
									  LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sad.nDrAccount
										 LEFT JOIN {PRE}CustomerMaster c ON c.nCId = sad.nDrAccount WHERE sc.vActive = '1' ".$extraCheck."
										 GROUP BY sc.nSCId
									  ORDER BY scd.nSalesCloseId DESC");

        return ($tabCount->num_rows());
    }//end function

    //fetching single details from ticket table
    function showTktDetails($tktID)
    {
        $viewAccessList  = $this->db->query("SELECT tb.*,scd.* FROM {PRE}SalesClosingDetails scd LEFT JOIN {PRE}TicketBooking tb
												ON scd.vTktNumber = tb.vTicketNumber");
        $commVal  = $viewAccessList->row();
        $divName  = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$commVal->nDivision."'");
        $locName  = $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$commVal->vLocation."'");

        $content = '<table>
						<tr>
							<td><strong>'.DIVISIONNAME.'</strong> :&nbsp;&nbsp;'.$divName.'</td>
							<td><strong>'.LOCATIONNAME.'</strong> :&nbsp;&nbsp;'.$locName.'</td>
						    <td><strong>'.CUSTDATE.'</strong> :&nbsp;&nbsp;'.$commVal->dDate.'</td>
						    <td><strong>'.SALESTYPE.'</strong> :&nbsp;&nbsp;'.$commVal->vTransMode.'</td>
							<td colspan="4"><strong>'.DOCNUMBER.'</strong> :&nbsp;&nbsp;'.$commVal->vInvoiceNumber.'</td>
						</tr>

						<tr>
						    <td><strong>'.LPO.'</strong> :&nbsp;&nbsp;'.$commVal->vLPO.'</td>
							<td colspan="5"><strong>'.AGENTAC.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$commVal->nAgentAc."' ").'('.$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$commVal->nAgentAc."' ").')'.'</td>
						</tr>

						<tr>
                            <td><strong>'.TYPE.'</strong></td>
                            <td><strong>'.TICKETNAME.'</strong></td>
                            <td><strong>'.EMPNAME.'</strong></td>
                            <td><strong>'.AIRLINE.'</strong></td>
                            <td><strong>'.FACEFARE.'</strong></td>
                            <td><strong>'.TAX.'</strong></td>
                            <td><strong>'.COMMPERCENT.'</strong></td>
                            <td><strong>'.COMMISSIONAMT.'</strong></td>
                            <td><strong>'.NETAMT.'</strong></td>
                            <td><strong>'.SER.'</strong></td>
                            <td><strong>'.SERAMT.'</strong></td>
                            <td><strong>'.SLSAMT.'</strong></td>
                            <td><strong>'.DISCAMT.'</strong></td>
                            <td><strong>'.COLECTED.'</strong></td>
                            <td><strong>'.AGENTNAME.'</strong></td>
                            <td><strong>'.LPO.'</strong></td>
						</tr>';
        $viewAccessList1  = $this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}SalesClosingDetails WHERE nSalesCloseId = '".$salesID."' 
											  ORDER BY nId DESC");
        foreach ($viewAccessList1->result() as $key=>$row)
        {
            switch($row->type)
            {
                case "Xo" :
                    $this->_TableName   = 'XoBooking';
                    $this->_extraField  = 'vXoNumber';
                    break;

                case "Ticket" :
                    $this->_TableName   = 'TicketBooking';
                    $this->_extraField  = 'vTicketNumber';
                    break;

                case "Hotel" :
                    $this->_TableName   = 'HotelVoucher';
                    $this->_extraField  = 'vVoucherId';
                    break;
            }//end switch

            $content .= '<tr>
							<td>'.$row->type.'</td>
							<td>'.$this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->_extraField." = '".$row->vTktNumber."'").'</td>
							<td>'.$row->vEmployee.'</td>
							<td>'.$this->common_model->get_field_name('Airline','vTitle'," WHERE nArId = '".$row->nAirline."'").'</td>
							<td>'.$row->vFare.'</td>
							<td>'.$row->vTax.'</td>
							<td>'.$row->vComm.'</td>
							<td>'.$row->vCommAmt.'</td>
							<td>'.$row->vNetAmt.'</td>
							<td>'.$row->vServPercent.'</td>
							<td>'.$row->vServAmt.'</td>
							<td>'.$row->vSlsAmt.'</td>
							<td>'.$row->vDisAmt.'</td>
							<td>'.$row->vCollected.'</td>
							<td>'.$row->vAgentAmt.'</td>
							<td>'.$row->vDLPO.'</td>
						</tr>';

			$content  .= '
						<tr>
							<td><strong>'.TOTALFARE.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalFace.'</td>
							<td><strong>'.TOTALTAX.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalTax.'</td>
							<td><strong>'.TOTALCOMMP.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalCommPercent.'</td>
							<td><strong>'.TOTALCOMMA.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalCommAmt.'</td>
							<td><strong>'.TOTALNET.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalNetAmount.'</td>
							<td><strong>'.TOTALSERP.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalServPercent.'</td>
							<td colspan="2"><strong>'.TOTALSERA.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalServAmount.'</td>
						</tr>

						<tr>
							<td><strong>'.TOTALSAL.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalSalesAmt.'</td>
						    <td><strong>'.TOTALDISC.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalDiscAmount.'</td>
						    <td><strong>'.TOTALCOLL.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalCollected.'</td>
							<td><strong>'.TOTALAGENT.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalAgentAmt.'</td>
							<td colspan="2"><strong>'.TOTALAGENT.'</strong> :&nbsp;&nbsp;'.$commVal->vDLPO.'</td>
						</tr>

						<tr>
							<td><strong>'.INVOICESTATUS.'</strong> :&nbsp;&nbsp;'.$commVal->iStatus.'</td>
							<td><strong>'.INVOICEREMARKS.'</strong> :&nbsp;&nbsp;'.$commVal->vRemarks.'</td>
						    <td colspan="3">&nbsp</td>
						</tr>';
						
        }//end foreach

		$content .= '<tr>
						<td><strong>'.TYPE.'</strong></td>
						<td><strong>'.TICKETNAME.'</strong></td>
						<td><strong>'.DRACCNAME.'</strong></td>
						<td><strong>'.ACHEAD.'</strong></td>
						<td><strong>'.CARDNAME.'</strong></td>
						<td><strong>'.COMMPERCENT.'</strong></td>
						<td><strong>'.COMMISSIONAMT.'</strong></td>
						<td><strong>'.AMT.'</strong></td>
						<td><strong>'.CRV.'</strong></td>
						<td><strong>'.REFTKT1.'</strong></td>
					</tr>';

        $viewAccessList2  = $this->db->query("SELECT *,CASE WHEN vTktType = 'Tkt' THEN 'Ticket' WHEN vTktType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as tktType FROM {PRE}SalesAccountDetails WHERE nSalesCloseId = '".$salesID."' 
											  ORDER BY nId DESC");
        foreach ($viewAccessList2->result() as $key1=>$row1)
        {
            switch($row1->tktType)
            {
                case "Xo" :
                    $this->_TableName   = 'XoBooking';
                    $this->_extraField  = 'vXoNumber';
                break;

                case "Ticket" :
                    $this->_TableName   = 'TicketBooking';
                    $this->_extraField  = 'vTicketNumber';
                break;

                case "Hotel" :
                    $this->_TableName   = 'HotelVoucher';
                    $this->_extraField  = 'vVoucherId';
                break;
            }//end switch

            $content .= '<tr>
							<td>'.$row1->tktType.'</td>
							<td>'.$this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->_extraField." = '".$row1->vTktNumber."'").'</td>
							<td>'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->nDrAccount."'").'</td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row1->nAcHead."'").'</td>
							<td>'.$this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$row1->nCard."'").'</td>
							<td>'.$row1->vCardComm.'</td>
							<td>'.$row1->vCommAmt.'</td>
							<td>'.$row1->vAmt.'</td>
							<td>'.$row1->vCRV.'</td>
							<td>'.$row1->vRefTkt.'</td>
						</tr>';
        }//end foreach

        $content .= '</table>';
        return($content);
    }//end function

    function getPassengerName($id,$type)
    {
        switch($type)
        {
            case "Xo" :
                $typeGroup = $this->db->query("SELECT xo.vPassenger FROM {PRE}XoBooking xo WHERE xo.nXId = '".$id."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow[] = $row->vPassenger;
                    }//end for each
                }//end if			

            break;

            case "Tkt" :
                $typeGroup = $this->db->query("SELECT tb.vPassenger FROM {PRE}TicketBooking tb WHERE tb.nTId = '".$id."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow[] = $row->vPassenger;
                    }//end for each
                }//end if			
            break;

            case "Htl" :
                $typeGroup = $this->db->query("SELECT hv.vPassenger FROM {PRE}HotelVoucher hv WHERE hv.nHVId = '".$id."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow[] = $row->vPassenger;
                    }//end for each
                }//end if			
            break;
        }//end switch
        return($typeGroupRow);
    }//end function

    //function for jv create
    function jvInsert($id,$division,$location,$invoice,$dateVal)
    {
        //insert into system jv
        $systmJvNo = $invoice;
        $systmJvNo = str_replace('-INV-','-',$systmJvNo);
        $systmJvNo = 'SJV'.$systmJvNo;

        $data      = array('vJVNo' => $systmJvNo,'nSalesCId' => $id,
            'nDivision' => $division,
            'nLocation' => $location,
            'nInvoice' => $invoice,
            'dDate' => date('Y-m-d', strtotime($dateVal)));
        $this->db->insert('{PRE}SystemJv', $data);

        $newId = $this->db->insert_id();

        return ($newId);
    }//end funciton

    //function for jv create
    function jvUpdate($id,$division,$location,$invoice,$dateVal)
    {
        //update system jv
        $newId     = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId = '".$id."'");

        //insert into system jv
        $systmJvNo = $invoice;
        $systmJvNo = str_replace('-INV-','-',$systmJvNo);
        $systmJvNo = 'SJV'.$systmJvNo;

        $data      = array('vJVNo' => $systmJvNo,'nSalesCId' => $id,
            'nDivision' => $division,
            'nLocation' => $location,
            'nInvoice' => $invoice,
            'dDate' => date('Y-m-d', strtotime($dateVal)));
        $this->db->where('nId',$newId);
        $this->db->update('{PRE}SystemJv', $data);

        return ($newId);
    }//end funciton

    //function for create jv
    function jvDetailInsert($id,$newId,$location,$user,$costOfSalesAmt,$type,$tNumber)
    {
        //insert into system jv details for tickets
        //addes sales details
        if($type == 'Tkt'):
            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' => 27,'nAccHeadId' => 73,'vAcCode' =>32211,'vType' => 'Tkt',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);

            $AccHead = $this->common_model->get_field_name('TicketFareDetails','nAccHead'," WHERE nTicketId = " .$tNumber ." ");
            $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
            $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");


            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' =>$AGId ,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode,'vType' => 'Tkt',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if($type == 'Htl'):
            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' => 28,'nAccHeadId' => 83,'vAcCode' =>32311,'vType' => 'Htl',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);

            $AccHead = $this->common_model->get_field_name('HotelVoucher','nAcHead'," WHERE nHVId = " .$tNumber ." ");
            $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
            $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");


            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' => $AGId,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode ,'vType' => 'Htl',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if($type == 'Xo'):
            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' => 29,'nAccHeadId' => 92,'vAcCode' => 32411,'vType' => 'Xo',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);

            $subAcc = $this->common_model->get_field_name('XoBooking','nPassengerAC',"  WHERE nXId  = " .$tNumber ." ");
            $AccHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = " .$subAcc ." ");
            $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
            $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");

            $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                'nLocation' => $location,
                'nAGId' => $AGId,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode,'vType' => 'Xo',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;
    }//end function

    //function for create jv
    function jvDetailUpdate($id,$newId,$location,$user,$costOfSalesAmt,$type,$tNumber)
    {
        //insert into system jv details for tickets
        if($type == 'Tkt' && intval($costOfSalesAmt) != 0):
            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 27,'nAccHeadId' => 73,'vAcCode' => 4110001,'vType' => 'Tkt',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 27,'nAccHeadId' => 73,
										'vType' => 'Tkt'));
            $this->db->update('{PRE}SystemJvDetails', $data);

            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 18,'nAccHeadId' => 32,'vAcCode' => 2204001,'vType' => 'Tkt',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 18,'nAccHeadId' => 32,
										'vType' => 'Tkt'));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;


        //insert into system jv details for hotels
        //addes sales details
        if($type == 'Htl' && intval($costOfSalesAmt) != 0):
            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 28,'nAccHeadId' => 83,'vAcCode' => 4120001,'vType' => 'Htl',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 28,'nAccHeadId' => 83,
										'vType' => 'Htl'));
            $this->db->update('{PRE}SystemJvDetails', $data);

            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 18,'nAccHeadId' => 34,'vAcCode' => 2204003,'vType' => 'Htl',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 18,'nAccHeadId' => 32,
										'vType' => 'Htl'));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;


        //insert into system jv details for hotels
        //addes sales details
        if($type == 'Xo' && intval($costOfSalesAmt) != 0):
            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 29,'nAccHeadId' => 92,'vAcCode' => 4130001,'vType' => 'Xo',
                'vDes' => $user,
                'nDebit' => $costOfSalesAmt,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 29,'nAccHeadId' => 92,
										'vType' => 'Xo'));
            $this->db->update('{PRE}SystemJvDetails', $data);

            $data      = array('nLocation' => $location, 'vNumber' => $tNumber,
                'nAGId' => 18,'nAccHeadId' => 35,'vAcCode' => 2204004,'vType' => 'Xo',
                'vDes' => $user,
                'nDebit' => 0,
                'nCredit' => $costOfSalesAmt);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 18,'nAccHeadId' => 32,
										'vType' => 'Xo'));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;
    }//end function

    //function for create jv
    function jvTotalInsert($id,$newId,$location,$user,$totalAmnt,$tktType,$acId,$acHead,$acCard, $CardCommAmt)
    {

	    /////// Insert Card amount
	if($CardCommAmt != 0 || $CardCommAmt != ''){
		$totalAmnt = $totalAmnt - $CardCommAmt; 
		// get commission account
		$commissionAccount =  $this->common_model->get_field_name('Card','nCommisionAc',' WHERE nCId = ' . $acCard . ' ');
		/*$commissionAgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$commissionAccount."'");
		$commissionAgId     = (intval($commissionAgId) == 0)?0:$commissionAgId;
		$commissionAcCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
				" WHERE nId = '".$commissionAccount."'");
		$commissionAcCode   = (intval($commissionAcCode) == 0)?0:$commissionAcCode;
		$cndtin    = "WHERE nAGId = '".$commissionAgId."' AND nAccHeadId = '".$commissionAccount."'";
	        */
		$data     = array('nSjId' => $newId,
			'nLocation' => $location,
			'nAGId' => '',
			'nAccHeadId' => '',
			'vAcCode' => '',
			'vType' => $tktType,
			'vDes' => $user,
			'vClosedId' => $commissionAccount.'-A',
			'nDrAccount' => $commissionAccount,
			'vTable' => 'A',
			'nDebit' => $CardCommAmt,
			'nCredit' => 0);
		$this->db->insert('{PRE}SystemJvDetails', $data);
	}

        $drAccount = @split("-",$acId);
		switch($tktType)
		{
			case "Tkt":
				$CollectedTkt = $totalAmnt;
				$CollectedHtl = 0;
				$CollectedXo  = 0;
			break;
			
			case "Htl":
				$CollectedTkt = 0;
				$CollectedHtl = $totalAmnt;
				$CollectedXo  = 0;
			break;
			
			case "Xo":
				$CollectedTkt = 0;
				$CollectedHtl = 0;
				$CollectedXo  = $totalAmnt;
			break;
			
			default:
				$CollectedTkt = 0;
				$CollectedHtl = 0;
				$CollectedXo  = 0;
			break;
		}//end switch
		
        //insert into system jv details for tickets
        //added into closed account detials
        if($CollectedTkt != 0):
			//update system jv
			$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$acHead."'");
			$AgId     = (intval($AgId) == 0)?0:$AgId;
			$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
				" WHERE nId = '".$acHead."'");
			$acCode   = (intval($acCode) == 0)?0:$acCode;
	
			$cndtin    = "WHERE nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'";
			//$getSalId  = $this->common_model->get_field_name('SystemJvDetails',"nSjId",$cndtin);
			
			$sqlChk    = $this->db->query("SELECT nAGId FROM {PRE}SystemJvDetails WHERE
												nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
											AND nSjId = '".$newId."' AND nDrAccount = '".$drAccount[0]."'
												AND vTable = '".$drAccount[1]."'");
			if($sqlChk->num_rows() == 0):
				$data     = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
					'vType' => 'Tkt','vDes' => $user,'vClosedId' => $acId,
                    'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
					'nDebit' => $CollectedTkt,
					'nCredit' => 0);
				$this->db->insert('{PRE}SystemJvDetails', $data);
			endif;
        endif;

        //insert into system jv details for hotels
        //added into closed account detials
        if($CollectedHtl != 0):
			//update system jv
			$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$acHead."'");
			$AgId     = (intval($AgId) == 0)?0:$AgId;
			$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
				" WHERE nId = '".$acHead."'");
			$acCode   = (intval($acCode) == 0)?0:$acCode;
	
		
			$sqlChk    = $this->db->query("SELECT nAGId FROM {PRE}SystemJvDetails WHERE
												nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
											AND nSjId = '".$newId."' AND nDrAccount = '".$drAccount[0]."'
												AND vTable = '".$drAccount[1]."'");
			if($sqlChk->num_rows() == 0):
				$data      = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
					'vType' => 'Htl','vDes' => $user,'vClosedId' => $acId,
                    'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
					'nDebit' => $CollectedHtl,
					'nCredit' => 0);
				$this->db->insert('{PRE}SystemJvDetails', $data);
			endif;
        endif;

        //insert into system jv details for hotels
        //added into closed account detials
        if($CollectedXo != 0):
			//update system jv
			$AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
				" WHERE nId = '".$acHead."'");
			$AgId     = (intval($AgId) == 0)?0:$AgId;
			$acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
				" WHERE nId = '".$acHead."'");
			$acCode   = (intval($acCode) == 0)?0:$acCode;
	
		
			$sqlChk    = $this->db->query("SELECT nAGId FROM {PRE}SystemJvDetails WHERE
												nAGId = '".$AgId."' AND nAccHeadId = '".$acHead."'
											AND nSjId = '".$newId."' AND nDrAccount = '".$drAccount[0]."'
												AND vTable = '".$drAccount[1]."'");
			if($sqlChk->num_rows() == 0):
				$data      = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
					'vType' => 'Xo','vDes' => $user,'vClosedId' => $acId,
                    'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
					'nDebit' => $CollectedXo,
					'nCredit' => 0);
				$this->db->insert('{PRE}SystemJvDetails', $data);
			endif;
        endif;
    }//end function

    //function for create jv
    function jvTotalUpdate($id,$newId,$location,$user,$totalAmnt,$tktType,$acId,$acHead)
    {
        $drAccount = @split("-",$acId);

		switch($tktType)
		{
			case "Tkt":
				$CollectedTkt = $totalAmnt;
				$CollectedHtl = 0;
				$CollectedXo  = 0;
			break;
			
			case "Htl":
				$CollectedTkt = 0;
				$CollectedHtl = $totalAmnt;
				$CollectedXo  = 0;
			break;
			
			case "Xo":
				$CollectedTkt = 0;
				$CollectedHtl = 0;
				$CollectedXo  = $totalAmnt;
			break;
			
			default:
				$CollectedTkt = 0;
				$CollectedHtl = 0;
				$CollectedXo  = 0;
			break;
		}//end switch
		
        //added into closed account detials
        if($CollectedTkt != 0):
            //fetch val system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
                " WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
                " WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;


            $data      = array('nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Tkt','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => $CollectedTkt,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => $AgId,'nAccHeadId' => $acHead,
										'vType' => 'Tkt','vAcCode' => $acCode));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        //added into closed account detials
        if($CollectedHtl != 0):
            //update system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
                " WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
                " WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;

            $data      = array('nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Htl','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => $CollectedHtl,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => $AgId,'nAccHeadId' => $acHead,
										'vType' => 'Htl','vAcCode' => $acCode));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //added into closed account detials
        if($CollectedXo != 0):
            //update system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
                " WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
                " WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;

            $data      = array('nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Xo','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => $CollectedXo,
                'nCredit' => 0);
            $this->db->where(array('nSjId' => $newId,'nAGId' => $AgId,'nAccHeadId' => $acHead,
										'vType' => 'Xo','vAcCode' => $acCode));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;
    }//end function
	
	 //function for create jv
    function jvSalesInsert($id,$newId,$location,$user,$CollectedTkt,$CollectedHtl,$CollectedXo)
    {
        //insert into system jv details for tickets
        //addes sales details
        if(intval($CollectedTkt) != 0):
           $data      = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => 4110000,'vType' => 'Tkt',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedTkt);
			$this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedHtl) != 0):
          $data      = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => 4120000,'vType' => 'Htl',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedHtl);
			$this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedXo) != 0):
           $data      = array('nSjId' => $newId,
					'nLocation' => $location,
					'nAGId' => 29,'nAccHeadId' => 91,'vAcCode' => 4130000,'vType' => 'Xo',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedXo);
			$this->db->insert('{PRE}SystemJvDetails', $data);
        endif;
    }//end function

    //function for create jv
    function jvSalesUpdate($id,$newId,$location,$user,$CollectedTkt,$CollectedHtl,$CollectedXo)
    {
        //insert into system jv details for tickets
        if(intval($CollectedTkt) != 0):
            $data = array('nLocation' => $location,
					'nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => 4110000,'vType' => 'Tkt',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedTkt);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 27,'nAccHeadId' => 72,
										'vType' => 'Tkt','vDes' => $user));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;


        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedHtl) != 0):
            $data = array('nLocation' => $location,
					'nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => 4120000,'vType' => 'Htl',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedHtl);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 28,'nAccHeadId' => 82,
										'vType' => 'Htl','vDes' => $user));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;


        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedXo) != 0):
           $data = array('nLocation' => $location,
					'nAGId' => 29,'nAccHeadId' => 91,'vAcCode' => 4130000,'vType' => 'Xo',
					'vDes' => $user,
					'nDebit' => 0,
					'nCredit' => $CollectedXo);
            $this->db->where(array('nSjId' => $newId,'nAGId' => 29,'nAccHeadId' => 91,
										'vType' => 'Xo','vDes' => $user));
            $this->db->update('{PRE}SystemJvDetails', $data);
        endif;
    }//end function
	
	//function for crv
	function getCRVList()
    {
        $typeGroupRow = array();
        $accheadGroup = $this->db->query("(SELECT crd.vACode,crd.vTable,crd.nAmount,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr')
                                  UNION ALL (SELECT cpd.vACode,cpd.vTable,cpd.nAmount,cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr')
                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.nAmount,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr')
                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.nAmount,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr')
                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.nAmount,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr')
                                  UNION ALL (SELECT cvd.vACode,cvd.vTable,cvd.nAmount,cvd.vMode,cv.vDocNumber,cv.vRefName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vMode='Cr')
                                  UNION ALL (SELECT dvd.vACode,dvd.vTable,dvd.nAmount,dvd.vMode,dv.vDocNumber,dv.vRefName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vMode='Cr')
                                  UNION ALL (SELECT acd.vACode,acd.vTable,acd.nAmount,acd.vMode,ac.vDocNumber,ac.vRefName FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vMode='Cr')
                                  UNION ALL (SELECT ade.vACode,ade.vTable,ade.nAmount,ade.vMode,ad.vDocNumber,ad.vRefName FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vMode='Cr')");

/*

        $accheadGroup = $this->db->query("(SELECT crd.vACode,crd.vTable,crd.nAmount,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = cr.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT cpd.vACode,cpd.vTable,cpd.nAmount,cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = cp.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.nAmount,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = bb.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.nAmount,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = bp.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.nAmount,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = jv.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT cvd.vACode,cvd.vTable,cvd.nAmount,cvd.vMode,cv.vDocNumber,cv.vRefName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = cv.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT dvd.vACode,dvd.vTable,dvd.nAmount,dvd.vMode,dv.vDocNumber,dv.vRefName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = dv.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT acd.vACode,acd.vTable,acd.nAmount,acd.vMode,ac.vDocNumber,ac.vRefName FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = ac.vDocNumber LIMIT 0,1)=0 )
                                  UNION ALL (SELECT ade.vACode,ade.vTable,ade.nAmount,ade.vMode,ad.vDocNumber,ad.vRefName FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vMode='Cr'
                                  AND (select count(sad.vCRV) from {PRE}SalesAccountDetails sad where sad.vCRV = ad.vDocNumber LIMIT 0,1)=0 )
                                  ");
*/
        if ($accheadGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->vDocNumber] = $row1->vDocNumber.'-'.$row1->vRefName.'-'.$row1->nAmount;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end if

        return ($typeGroupRow);
    }//end function

	//function for crv
	function getCRV($accName,$accType,$invAccId=0)
	{
		 $typeGroupRow = array();
// angela


     if( $invAccId == 0 || $invAccId == '') {

         $accheadGroupQuery = "(SELECT crd.vACode,crd.vTable,crd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cr.vDocNumber GROUP BY cr.vDocNumber),0) as nAmount,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."'
                                  AND crd.vTable='".$accType."'
AND  (crd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cr.vDocNumber GROUP BY cr.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT cpd.vACode,cpd.vTable,cpd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cp.vDocNumber GROUP BY cp.vDocNumber),0) as nAmount,cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."'
AND  (cpd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cp.vDocNumber GROUP BY cp.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like bb.vDocNumber GROUP BY bb.vDocNumber),0) as nAmount,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."'
AND  (bbd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like bb.vDocNumber GROUP BY bb.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like bp.vDocNumber GROUP BY bp.vDocNumber),0) as nAmount,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."'
AND  (bpd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like bp.vDocNumber GROUP BY bp.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like jv.vDocNumber GROUP BY jv.vDocNumber),0) as nAmount,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."'
AND  (jvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like jv.vDocNumber GROUP BY jv.vDocNumber),0) ) > 0 )


                                  UNION ALL (SELECT cvd.vACode,cvd.vTable,cvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cv.vDocNumber GROUP BY cv.vDocNumber),0) as nAmount,cvd.vMode,cv.vDocNumber,cv.vRefName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vMode='Cr' AND cvd.vACode='".$accName."' AND cvd.vTable='".$accType."'
AND  (cvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cv.vDocNumber GROUP BY cv.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT dvd.vACode,dvd.vTable,dvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like dv.vDocNumber GROUP BY dv.vDocNumber),0) as nAmount,dvd.vMode,dv.vDocNumber,dv.vRefName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vMode='Cr' AND dvd.vACode='".$accName."' AND dvd.vTable='".$accType."'
AND  (dvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like dv.vDocNumber GROUP BY dv.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT acd.vACode,acd.vTable,acd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like ac.vDocNumber GROUP BY ac.vDocNumber),0) as nAmount,acd.vMode,ac.vDocNumber,ac.vRefName FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vMode='Cr' AND acd.vACode='".$accName."' AND acd.vTable='".$accType."'
AND  (acd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like ac.vDocNumber GROUP BY ac.vDocNumber),0) ) > 0 )

                                  UNION ALL (SELECT ade.vACode,ade.vTable,ade.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like ad.vDocNumber GROUP BY ad.vDocNumber),0) as nAmount,ade.vMode,ad.vDocNumber,ad.vRefName FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vMode='Cr' AND ade.vACode='".$accName."' AND ade.vTable='".$accType."'
AND  ade.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like ad.vDocNumber GROUP BY ad.vDocNumber),0)  > 0 )";

     }


     else {


         $accheadGroupQuery = "(SELECT crd.vACode,crd.vTable,crd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cr.vDocNumber GROUP BY cr.vDocNumber),0) as nAmount,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."'
                                  AND crd.vTable='".$accType."'
AND ( cr.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (crd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cr.vDocNumber GROUP BY cr.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT cpd.vACode,cpd.vTable,cpd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cp.vDocNumber GROUP BY cp.vDocNumber),0) as nAmount,cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."'
AND ( cp.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (cpd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cp.vDocNumber GROUP BY cp.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like bb.vDocNumber GROUP BY bb.vDocNumber),0) as nAmount,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."'
AND ( bb.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (bbd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like bb.vDocNumber GROUP BY bb.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like bp.vDocNumber GROUP BY bp.vDocNumber),0) as nAmount,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."'
AND ( bp.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (bpd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like bp.vDocNumber GROUP BY bp.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like jv.vDocNumber GROUP BY jv.vDocNumber),0) as nAmount,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."'
AND ( jv.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (jvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like jv.vDocNumber GROUP BY jv.vDocNumber),0) ) > 0 ))


                                  UNION ALL (SELECT cvd.vACode,cvd.vTable,cvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like cv.vDocNumber GROUP BY cv.vDocNumber),0) as nAmount,cvd.vMode,cv.vDocNumber,cv.vRefName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vMode='Cr' AND cvd.vACode='".$accName."' AND cvd.vTable='".$accType."'
AND ( cv.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (cvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like cv.vDocNumber GROUP BY cv.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT dvd.vACode,dvd.vTable,dvd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like dv.vDocNumber GROUP BY dv.vDocNumber),0) as nAmount,dvd.vMode,dv.vDocNumber,dv.vRefName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vMode='Cr' AND dvd.vACode='".$accName."' AND dvd.vTable='".$accType."'
AND ( dv.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (dvd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like dv.vDocNumber GROUP BY dv.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT acd.vACode,acd.vTable,acd.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like ac.vDocNumber GROUP BY ac.vDocNumber),0) as nAmount,acd.vMode,ac.vDocNumber,ac.vRefName FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vMode='Cr' AND acd.vACode='".$accName."' AND acd.vTable='".$accType."'
AND ( ac.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (acd.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like ac.vDocNumber GROUP BY ac.vDocNumber),0) ) > 0 ))

                                  UNION ALL (SELECT ade.vACode,ade.vTable,ade.nAmount  - IFNULL(( SELECT SUM(sad1.vAmt) from tbl_SalesAccountDetails sad1 where sad1.vCRV like ad.vDocNumber GROUP BY ad.vDocNumber),0) as nAmount,ade.vMode,ad.vDocNumber,ad.vRefName FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vMode='Cr' AND ade.vACode='".$accName."' AND ade.vTable='".$accType."'
AND ( ad.vDocNumber IN (select sad.vCRV from tbl_SalesAccountDetails sad where sad.nId = ".$invAccId." ) OR (ade.nAmount - IFNULL(( SELECT SUM(vAmt) from tbl_SalesAccountDetails sad where sad.vCRV like ad.vDocNumber GROUP BY ad.vDocNumber),0) ) > 0 ))";

     }

         $accheadGroup = $this->db->query($accheadGroupQuery);
        if ($accheadGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->vDocNumber] = $row1->vDocNumber.'-'.$row1->vRefName.'-'.$row1->nAmount;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end if

        return ($typeGroupRow);
	}//end function
	
	//function for crv
	function getRefundList()
    {
        $typeGroupRow = array();
        $accheadGroup = $this->db->query("SELECT rc.vDocNumber FROM {PRE}RefundClosing rc LEFT JOIN
                                  {PRE}RefundClosingDetails rcd ON
                                  rcd.nRefundCloseId = rc.nRCId WHERE rc.vActive='1'");

        if ($accheadGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->vDocNumber] = $row1->vDocNumber;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end if

        return ($typeGroupRow);
    }//end function

    //function for crv
    function getRefund($accName,$accType)
    {
        $typeGroupRow = array();
        $accheadGroup = $this->db->query("SELECT rc.vDocNumber FROM {PRE}RefundClosing rc LEFT JOIN
                                  {PRE}RefundClosingDetails rcd ON
                                  rcd.nRefundCloseId = rc.nRCId WHERE rc.vActive='1' AND rcd.nDrAcc='".$accName."'
                                  AND rcd.vTable='".$accType."'");

        if ($accheadGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->vDocNumber] = $row1->vDocNumber;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end if

        return ($typeGroupRow);
    }//end function

}//end class
?>
