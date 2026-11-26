<?php
class Manage_info_model extends CI_Model {

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

    function filterLocation($division)
    {
											 //".$this->common_model->getUserLoc()."
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' 
										 AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
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

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
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

    function getVendorAccounts()
    {
        $agentGroup    = $this->db->query("SELECT nVId,vCode,vName FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId DESC");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
        {
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
            {
                $agentGroupRow[$row->nVId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        } //end if
        else
        {
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

    function getAirlineValues()
    {
        $agentGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline WHERE vActive = '1' ORDER BY vTitle  ");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
        {
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
            {
                $agentGroupRow[$row->nArId] = (stripslashes($row->vTitle) . ' (' . stripslashes($row->vAirlineNo) . ') ');
            }//end for each
        } //end if
        else
        {
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

    function getAccountGroup()
    {
        $typeGroup    = $this->db->query("SELECT nId,vTitle,vCode FROM {PRE}FinanceModule WHERE nParent!='0' AND vLevel='2'
                                                    AND vActive = '1' ORDER BY nId ASC");
        $typeGroupRow = array();

        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nId] = stripslashes($row->vCode.' - '.$row->vTitle);
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        return ($typeGroupRow);
    }//end function

    function getActiveAcName()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' ORDER BY nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        return ($typeGroupRow);
    }//end function

    function filterAccount($account)
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' AND ah.nAGroup='".$account."' ORDER BY nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow = array();
            $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' ORDER BY nId DESC");
            if ($typeGroup->num_rows() > 0)
            {
                $typeGroupRow[0] = SELECT;
                foreach ($typeGroup->result() as $key=>$row)
                {
                    $typeGroupRow[$row->ahaId] = $row->vTitle;
                }//end for each
            }//end if
            else
            {
                $typeGroupRow[0] = NOVALUE;
            }//end else
        }//end else
        return ($typeGroupRow);
    }//end function

    function getActiveSubAccounts()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function filterSubAccount($accName)
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $typeGroupRow[0] = SELECT;

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nFId = '".$accName."' ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$accName."' ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent WHERE nFId = '".$accName."' ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'A'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other WHERE nFId = '".$accName."' ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $resultArray = array_merge($OtherGroupRow,$resultArray);

        if(empty($resultArray)):
            $resultArray[0] = NOVALUE;
        endif;

        return ($resultArray);
    }//end function

    function getProfitEmployeeReport($extraCheck,$extraCheck_r)
    {
        session_start();

        $adminGroupListQuery  = "SELECT vLocation, vEmployee, dDate, vInvoiceNumber, vRemarks, tableId, tableName,
                                  SUM(netAmt) as NetAmount, SUM(salesAmt) SalesAmount ,
                                  SUM(NETREF) as NetRefund, SUM(PAIDREF) as PaidRefund FROM 
                                  (( SELECT sc.vLocation,scd.vEmployee, sc.dDate, sc.vInvoiceNumber, sc.vRemarks, sc.nSCId as tableId, 'S' as tableName,
                                     SUM(scd.vNetAmt) as netAmt, SUM(scd.vCollected) as salesAmt ,
                                     0  as NETREF, 0 as PAIDREF
                                  FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck."  GROUP BY
                                 scd.vEmployee, sc.vLocation,  sc.vInvoiceNumber)

                                 UNION ALL ( SELECT DISTINCT rc.vLocation, (SELECT vCode FROM {PRE}EmployeeMaster WHERE nEId= rrd.vIssuedBy) AS vEmployee , rc.dDate, rc.vDocNumber as vInvoiceNumber, rc.vRemarks, rc.nRCId as tableId,'R' as tableName, 
                                     0 as netAmt, 0 as salesAmt,
                                     SUM(rcd.vNetAmt) as NETREF, SUM(rcd.vPaidAmt) as PAIDREF
                                     FROM {PRE}RefundClosing rc
                                     LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
                                     LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd.vTktType
                                     WHERE rc.vActive = '1'  ". $extraCheck_r." GROUP BY rrd.vIssuedBy,rc.vLocation,  rc.vDocNumber   )) as T GROUP BY vEmployee,vLocation,vInvoiceNumber ORDER BY  vEmployee, vInvoiceNumber "; 


/*        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck." GROUP BY
                                 scd.vEmployee ORDER BY scd.vEmployee DESC";
*/

/*        $adminGroupListQuery  = "SELECT vLocation, vEmployee, SUM(netAmt) as NetAmount, SUM(salesAmt) SalesAmount ,
                                 SUM(NETREF) as NetRefund, SUM(PAIDREF) as PaidRefund FROM (( SELECT DISTINCT sc.vLocation,scd.vEmployee,
                                SUM(scd.vNetAmt) as netAmt, SUM(scd.vCollected) as salesAmt ,
                                0  as NETREF, 0 as PAIDREF 
                                  FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck."  GROUP BY
                                 scd.vEmployee, sc.vLocation)

                                 UNION ALL ( SELECT DISTINCT rc.vLocation, (SELECT vCode FROM {PRE}EmployeeMaster WHERE nEId= rrd.vIssuedBy) AS vEmployee ,
                                     0 as netAmt, 0 as salesAmt,
                                     SUM(rcd.vNetAmt) as NETREF, SUM(rcd.vPaidAmt) as PAIDREF 
                                     FROM {PRE}RefundClosing rc 
                                     LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId 
                                     LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd.vTktType
                                     WHERE rc.vActive = '1' ". $extraCheck_r." GROUP BY rrd.vIssuedBy,  rc.vLocation  )) as T GROUP BY vEmployee,vLocation ORDER BY  vEmployee";
*/
// angela

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        $adminGroupSummary = array();
        $adminGroupRow = array();
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['NetAmount'] += $row->NetAmount;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['SalesAmount'] += $row->SalesAmount;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['NetRefund'] += $row->NetRefund;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['PaidRefund'] += $row->PaidRefund;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['Employee'] = $row->vEmployee;
                $adminGroupSummary[$row->vEmployee . "-". $row->vLocation]['Location'] = $row->vLocation;
            }//end for each
        } //end if
        $adminGroupResults['list_values_detail'] = $adminGroupRow;
        $adminGroupResults['list_values_summary'] = $adminGroupSummary;
        return ($adminGroupResults);
    }//end function

    function getProfitCustomerReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();
/*        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,sad.*,sc.vTotalNetAmount as netAmt,sc.vTotalCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND sad.vTable='C' ".$extraCheck."
                                  ORDER BY sad.nDrAccount DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,sad.*,sc.vTotalNetAmount as netAmt,sc.vTotalCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND sad.vTable='C' ".$extraCheck." GROUP BY
                                 sad.nDrAccount ORDER BY sad.nDrAccount DESC";
*/

        $adminGroupListQuery = "
		(SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks,
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' AND sad.vTable='C' ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks ,rcd.nDrAcc as account,rcd.vTable,CASE
              WHEN rcd.vTable = 'C' THEN
              (SELECT nFId FROM {PRE}CustomerMaster WHERE
               nCId = rcd.nDrAcc)
              WHEN rcd.vTable = 'V' THEN
              (SELECT nFId FROM {PRE}VendorMaster WHERE
               nVId = rcd.nDrAcc)
              WHEN rcd.vTable = 'AG' THEN
              (SELECT nFId FROM {PRE}Agent WHERE
               nAId = rcd.nDrAcc)
              WHEN rcd.vTable = 'O' THEN
              (SELECT nFId FROM {PRE}Other WHERE
               nOId = rcd.nDrAcc)
              ELSE
              0
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' AND rcd.vTable='C' ".$extraCheck_rc.") ORDER BY account, subacc,vTable,document ";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->account . "-" . $row->vTable . "-". $row->location;
		
		$adminGroupRowSummary[$id]['account'] = $row->account;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['subacc'] = $row->subacc;
		$adminGroupRowSummary[$id]['vTable'] = $row->vTable;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);
    }//end function

    function getProfitByVendorReport($extraCheck_sj,$extraCheck_sj_special)
    {
        session_start();
        $list = $this->getVendorAccNum();

/*
       $adminGroupListRQuery  = "SELECT sjd.nAGId,sjd.nAccHeadId as subacc,sj.nId as sjvId FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj."
                                  AND sj.vType = 'R' AND (sjd.vTable = 'V' OR sjd.nAccHeadId IN
                                  ('32','33','34','35'))
                                  GROUP BY sjd.nSjDId ".$extraCheck_sj_special." ORDER BY subacc ASC";

        $_SESSION['sess_refund_query'] = $adminGroupListRQuery;
		
		$adminGroupListQuery  = "SELECT sj.dDate,sj.nLocation as location,sj.nInvoice as document,sj.vType,sj.nSalesCId,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.vTable,sjd.nAccHeadId as subacc,SUM(sjd.nDebit) as netAmt,SUM(sjd.nCredit)
                                  as salesAmt,'' as issued,sj.nId as tableId,'SJ' as tblName,sjd.vType as tktType,sjd.vNumber as vNumber,
                                  '' as mode,sjd.nSjDId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."')
                                                    END as subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj."
                                  AND (sjd.vTable = 'V' OR sjd.nAccHeadId IN
                                  ('32','33','34','35'))
                                GROUP BY sjd.nSjDId ".$extraCheck_sj_special." ORDER BY subacc ASC";
  */

    $adminGroupListQuery  = "SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                  IF(sjd.nAccHeadId NOT IN ". $list ." ,0, IF(sjd.nDrAccount = '',
                  IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                      WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                  ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O') ,
                  (CASE
                   WHEN sjd.vType = 'Tkt' THEN
                   (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                    nTicketId = sjd.vNumber)
                   WHEN sjd.vType = 'Htl' THEN
                   (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                    nHVId = sjd.vNumber)
                   ELSE
                   (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                    nXId = sjd.vNumber) END)),
                  sjd.nDrAccount))as account,
                  IF(sjd.vTable = '','V', sjd.vTable ) as vTable,

                                  sjd.nAccHeadId as subacc,SUM(sjd.nDebit) as debit,SUM(sjd.nCredit)
                                  as credit,'' as issued,sj.nSalesCId as tableId,
                                  IF( sj.vType = 'S', 'SC','RC') as tblName,
                                  sjd.vNumber as vNumber,sjd.vType as tktType,'' as mode,sjd.nSjDId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE 
                                  (sjd.vTable = 'V' OR sjd.nAccHeadId
                                  IN ".$list.") ".$extraCheck_sj." GROUP BY sjd.nSjDId ".$extraCheck_sj_special." ORDER BY subacc, account,document  ";

        $_SESSION['sess_query'] = $adminGroupListQuery;
 /*
SELECT vLocation, vEmployee, dDate, vInvoiceNumber, vRemarks, tableId, tableName,
                                  SUM(netAmt) as NetAmount, SUM(salesAmt) SalesAmount ,
                                  SUM(NETREF) as NetRefund, SUM(PAIDREF) as PaidRefund FROM
                                  (( SELECT sc.vLocation,scd.vEmployee, sc.dDate, sc.vInvoiceNumber, sc.vRemarks, sc.nSCId as tableId, 'S' as tableName,
                                     SUM(scd.vNetAmt) as netAmt, SUM(scd.vCollected) as salesAmt ,
                                     0  as NETREF, 0 as PAIDREF
                                  FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck."  GROUP BY
                                 scd.vEmployee, sc.vLocation,  sc.vInvoiceNumber)

                                 UNION ALL ( SELECT DISTINCT rc.vLocation, (SELECT vCode FROM {PRE}EmployeeMaster WHERE nEId= rrd.vIssuedBy) AS vEmployee , rc.dDate, rc.vDocNumber as vInvoiceNumber, rc.vRemarks, rc.nRCId as tableId,'R' as tableName,
                                     0 as netAmt, 0 as salesAmt,
                                     SUM(rcd.vNetAmt) as NETREF, SUM(rcd.vPaidAmt) as PAIDREF
                                     FROM {PRE}RefundClosing rc
                                     LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
                                     LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd.vTktType
                                     WHERE rc.vActive = '1'  ". $extraCheck_r." GROUP BY rrd.vIssuedBy,rc.vLocation,  rc.vDocNumber   )) as T GROUP BY vEmployee,vLocation,vInvoiceNumber ORDER BY  vEmployee, vInvoiceNumber 
*/
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              $adminGroupRow[] = $row;

              $id = $row->account. "-". $row->subacc . "-". $row->vTable  . "-". $row->location;
              if($row->tblName == 'SC'){
                $adminGroupSummary[$id]['NetAmount'] += $row->debit + $row->credit;
                $adminGroupSummary[$id]['SalesAmount'] += $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber = ".$row->vNumber."  AND vType = '".$row->tktType."' ");
              }
              else{
                $adminGroupSummary[$id]['NetRefund'] += $row->debit + $row->credit;
                $adminGroupSummary[$id]['PaidRefund'] +=  $this->common_model->get_field_name('RefundClosingDetails','vPaidAmt'," WHERE vType ='".$row->tktType."' AND  nTicketNumber = ".$row->vNumber." ");
              }

              $adminGroupSummary[$id]['Account'] = $row->account;
              $adminGroupSummary[$id]['SubAcc'] = $row->subacc;
              $adminGroupSummary[$id]['vTable'] = $row->vTable;
              $adminGroupSummary[$id]['Location'] = $row->location;
 
            }//end for each
        } //end if
        $adminGroupResults['list_values'] = $adminGroupRow;
        $adminGroupResults['list_values_summary'] = $adminGroupSummary;
        return ($adminGroupResults);
 
    }//end function

    function getAccountSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc)
    {
        session_start();

        $adminGroupListQuery  = "(SELECT sc.dDate as date,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks as
                                  description,sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,sad.vAmt as debit,
                                  '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}SalesClosing sc LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId WHERE
                                  sc.vActive = '1' ".$extraCheck_sc.")
                                  UNION ALL (SELECT rc.dDate as date,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks as
                                  description,rcd.nDrAcc as account,rcd.vTable,CASE
											WHEN rcd.vTable = 'C' THEN
										 		(SELECT nFId FROM {PRE}CustomerMaster WHERE
										 			nCId = rcd.nDrAcc)
											WHEN rcd.vTable = 'V' THEN
										 		(SELECT nFId FROM {PRE}VendorMaster WHERE
										 			 nVId = rcd.nDrAcc)
											WHEN rcd.vTable = 'AG' THEN
										 		(SELECT nFId FROM {PRE}Agent WHERE
										 			 nAId = rcd.nDrAcc)
											WHEN rcd.vTable = 'O' THEN
										 		(SELECT nFId FROM {PRE}Other WHERE
										 			 nOId = rcd.nDrAcc)
											ELSE
												0
										END as subacc,rcd.vPaidAmt as debit,
                                  '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
                                  rc.vActive = '1' ".$extraCheck_rc.")
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,acd.nAmount as debit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,ade.nAmount as debit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account, subacc,vTable, document DESC";

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

    function getCustomerSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc)
    {
        session_start();

        $adminGroupListQuery  = "(SELECT sc.dDate as date,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks as
                                  description,sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,sad.vAmt as debit,
                                  '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}SalesClosing sc LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId WHERE
                                  sc.vActive = '1' AND sad.vTable='C' ".$extraCheck_sc.")
                                  UNION ALL (SELECT rc.dDate as date,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks as
                                  description,rcd.nDrAcc as account,rcd.vTable,CASE
											WHEN rcd.vTable = 'C' THEN
										 		(SELECT nFId FROM {PRE}CustomerMaster WHERE
										 			nCId = rcd.nDrAcc)
											WHEN rcd.vTable = 'V' THEN
										 		(SELECT nFId FROM {PRE}VendorMaster WHERE
										 			 nVId = rcd.nDrAcc)
											WHEN rcd.vTable = 'AG' THEN
										 		(SELECT nFId FROM {PRE}Agent WHERE
										 			 nAId = rcd.nDrAcc)
											WHEN rcd.vTable = 'O' THEN
										 		(SELECT nFId FROM {PRE}Other WHERE
										 			 nOId = rcd.nDrAcc)
											ELSE
												0
										END as subacc,rcd.vPaidAmt as debit,
                                  '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
                                  rc.vActive = '1' AND rcd.vTable='C' ".$extraCheck_rc.")
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND crd.vTable='C' ".$extraCheck_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vTable='C' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vTable='C' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vTable='C' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vTable='C' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vTable='C' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vTable='C' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,acd.nAmount as debit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vTable='C' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,ade.nAmount as debit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vTable='C' ".$extraCheck_ad." ORDER BY account, subacc,vTable, document )
                                  ORDER BY account DESC";

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


    function getVendorAccNum(){
      $list ='';
      $get_acc_list =  $this->db->query("SELECT nActHId FROM {PRE}AccountHeadControls WHERE vSubLedgerType = 'V'");
      if ($get_acc_list->num_rows() > 0)
      {
        foreach ($get_acc_list->result() as $key=>$row)
        {
          $list .= $list!='' ? ',' :'';
          $list .= "'" . $row->nActHId . "'";
          // $adminGroupRow[] = $row;
        }//end for each
      } //end if
      $list = "(" . $list . ")";

      return $list;
    }//end function


    function getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj,$extraCheck_sj_special)
    {
        session_start();
        $list = $this->getVendorAccNum();
                                  //sjd.nDrAccount as account,sjd.vTable,
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,

                  IF(sjd.nAccHeadId NOT IN ". $list ." ,0, IF(sjd.nDrAccount = '',
                  IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                      WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                  ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O') ,
                  (CASE
                   WHEN sjd.vType = 'Tkt' THEN
                   (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                    nTicketId = sjd.vNumber)
                   WHEN sjd.vType = 'Htl' THEN
                   (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                    nHVId = sjd.vNumber)
                   ELSE
                   (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                    nXId = sjd.vNumber) END)),
                  sjd.nDrAccount))as account,
                  IF(sjd.vTable = '','V', sjd.vTable ) as vTable,

                                  sjd.nAccHeadId as subacc,SUM(sjd.nDebit) as debit,SUM(sjd.nCredit)
                                  as credit,'' as issued,sj.nSalesCId as tableId,
                                  IF( sj.vType = 'S', 'SJ','RC') as tblName,
                                  sjd.vNumber as vNumber,'' as mode,sjd.nSjDId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj."
                                  AND (sjd.vTable = 'V' OR sjd.nAccHeadId
                                  IN ".$list.") GROUP BY sjd.nSjDId ".$extraCheck_sj_special.")

                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,'' as credit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,'' as vNumber,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND crd.vTable='V' ".$extraCheck_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,'' as credit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,'' as vNumber,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount,'' as subCode FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vTable='V' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,'' as credit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,'' as vNumber,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount,'' as subCode FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vTable='V' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,'' as credit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,'' as vNumber,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount,'' as subCode FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vTable='V' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,'' as vNumber,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vTable='V' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,'' as vNumber,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vTable='V' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,'' as vNumber,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vTable='V' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,acd.nAmount as debit,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,'' as vNumber,acd.vMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vTable='V' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,ade.nAmount as debit,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,'' as vNumber,ade.vMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vTable='V' ".$extraCheck_ad." ORDER BY account, subacc,vTable, document )
                                  ORDER BY subacc ASC";

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

    function getSaleSummaryReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();
/*        $adminGroupListQuery1  = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                    {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                    ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                    sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck." ORDER BY scd.vType ASC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,CASE WHEN scd.vType = 'Htl'
                                    THEN (SELECT SUM(scd.vNetAmt) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                    scd ON sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND
                                    scd.vType = 'Htl'  GROUP BY scd.vType ORDER BY scd.vType ASC)
                                    WHEN scd.vType = 'Tkt'
                                    THEN (SELECT SUM(scd.vNetAmt) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                    ON sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType = 'Tkt'
                                    GROUP BY scd.vType ORDER BY scd.vType ASC)
                                    ELSE
                                    (SELECT SUM(scd.vNetAmt) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON
                                    sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType = 'Xo'
                                    GROUP BY scd.vType ORDER BY scd.vType ASC) END

                                    as netAmt, CASE WHEN scd.vType = 'Htl'
                                    THEN (SELECT SUM(scd.vCollected) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                    scd ON sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType = 'Htl'
                                    GROUP BY scd.vType ORDER BY scd.vType ASC)
                                    WHEN scd.vType = 'Tkt'
                                    THEN (SELECT SUM(scd.vCollected) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                    ON sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType = 'Tkt'
                                    GROUP BY scd.vType ORDER BY scd.vType ASC)
                                    ELSE
                                    (SELECT SUM(scd.vCollected) FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON
                                    sc.nSCId = scd.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType = 'Xo'
                                    GROUP BY scd.vType ORDER BY scd.vType ASC) END

                                    as salesAmt FROM
                                    {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                    ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                    sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' GROUP BY
                                    scd.vType ORDER BY scd.vType ASC";

 */
		        $adminGroupListQuery = "
		 (SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber,
	   CASE WHEN scd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE scd.vTktNumber = nTId )
	        WHEN scd.vType = 'Xo' THEN ( SELECT nGDS FROM {PRE}XoBooking WHERE scd.vTktNumber = nXId )
		ELSE '' END as gds		
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks , rcd.nAirline, rcd.nDrAcc as account,rcd.vTable,CASE
              WHEN rcd.vTable = 'C' THEN
              (SELECT nFId FROM {PRE}CustomerMaster WHERE
               nCId = rcd.nDrAcc)
              WHEN rcd.vTable = 'V' THEN
              (SELECT nFId FROM {PRE}VendorMaster WHERE
               nVId = rcd.nDrAcc)
              WHEN rcd.vTable = 'AG' THEN
              (SELECT nFId FROM {PRE}Agent WHERE
               nAId = rcd.nDrAcc)
              WHEN rcd.vTable = 'O' THEN
              (SELECT nFId FROM {PRE}Other WHERE
               nOId = rcd.nDrAcc)
              ELSE
              0
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
	      rcd.vType as tktType, rcd.nTicketNumber as vNumber,
	      IF('O' = (SELECT vNewOld FROM {PRE}RefundRegisterDetails WHERE vTktType = rcd.vType AND  rcd.nTicketNumber = nTktNum), 
		 ( SELECT nGDS FROM {PRE}RefundRegisterDetails rrd LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId WHERE rrd.vTktType = rcd.vType AND  rcd.nTicketNumber = rrd.nTktNum),
 		CASE WHEN rcd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE rcd.nTicketNumber = nTId)
	             WHEN rcd.vType = 'Xo' THEN  ( SELECT nGDS FROM {PRE}XoBooking WHERE rcd.nTicketNumber = nXId )
		     ELSE '' END )as gds	
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' ".$extraCheck_rc.") ORDER BY tktType, document";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->tktType ."-".$row->location;
		
		$adminGroupRowSummary[$id]['tktType'] = $row->tktType;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);
    }//end function

    function getAirlineSummaryReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();
/*
        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1'  AND
                                 scd.vType='Tkt' ".$extraCheck." ORDER BY scd.nAirline DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1'  AND
                                 scd.vType='Tkt' ".$extraCheck." GROUP BY scd.nAirline ORDER BY scd.nAirline DESC";
        $_SESSION['sess_query'] = $adminGroupListQuery;
 */
	
        $adminGroupListQuery = "
		 (SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' AND scd.vType != 'Htl'  ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks , rcd.nAirline, rcd.nDrAcc as account,rcd.vTable,CASE
              WHEN rcd.vTable = 'C' THEN
              (SELECT nFId FROM {PRE}CustomerMaster WHERE
               nCId = rcd.nDrAcc)
              WHEN rcd.vTable = 'V' THEN
              (SELECT nFId FROM {PRE}VendorMaster WHERE
               nVId = rcd.nDrAcc)
              WHEN rcd.vTable = 'AG' THEN
              (SELECT nFId FROM {PRE}Agent WHERE
               nAId = rcd.nDrAcc)
              WHEN rcd.vTable = 'O' THEN
              (SELECT nFId FROM {PRE}Other WHERE
               nOId = rcd.nDrAcc)
              ELSE
              0
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' AND  rcd.vType != 'Htl' ".$extraCheck_rc.")  ORDER BY nAirline,document";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->nAirline ."-". $row->location;
		
		$adminGroupRowSummary[$id]['airline'] = $row->nAirline;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);
    }//end function

    function getHotelSummaryReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();

/*        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Htl'
                                 ".$extraCheck." ORDER BY scd.vTktNumber DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Htl'
                                 ".$extraCheck." GROUP BY scd.vTktNumber ORDER BY scd.vTktNumber DESC";

        $_SESSION['sess_query'] = $adminGroupListQuery;
*/



        $adminGroupListQuery = "
		(SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber, hv.vHotelName
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   LEFT JOIN {PRE}HotelVoucher hv ON  scd.vType = 'Htl' AND scd.vTktNumber =  hv.nHVId
	   WHERE
           sc.vActive = '1' AND scd.vType = 'Htl'  ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks ,  0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber, hv.vHotelName
	      FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
	      LEFT JOIN {PRE}HotelVoucher hv ON  rcd.vType = 'Htl' AND rcd.nTicketNumber =  hv.nHVId
	      WHERE
	      rc.vActive = '1' AND  rcd.vType = 'Htl'  ".$extraCheck_rc.")

	   UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks ,  0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber, rrd.nHotel as vHotelName
	      FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
	      LEFT JOIN {PRE}RefundRegisterDetails rrd ON  rrd.vTktType = 'Htl' AND rrd.nTktNum = rcd.nTicketNumber AND rrd.vNewOld = 'O'
	      WHERE
	      rc.vActive = '1' AND  rcd.vType = 'Htl'  AND  rrd.vNewOld = 'O' ".$extraCheck_rc.")
	      ORDER BY vHotelName, document
	      ";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->vHotelName ."-". $row->location;
		
		$adminGroupRowSummary[$id]['vHotelName'] = $row->vHotelName;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);

    }//end function

    function getLocationSummaryReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();
/*
        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck."
                                 ORDER BY sc.vLocation DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.* FROM {PRE}SalesClosing sc LEFT JOIN
                                 {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN
                                 {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1'
								 ".$extraCheck." GROUP BY sc.vLocation ORDER BY sc.vLocation DESC";

 */
	        $adminGroupListQuery = "
		 (SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber,
	   CASE WHEN scd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE scd.vTktNumber = nTId )
	        WHEN scd.vType = 'Xo' THEN ( SELECT nGDS FROM {PRE}XoBooking WHERE scd.vTktNumber = nXId )
		ELSE '' END as gds		
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks , rcd.nAirline, rcd.nDrAcc as account,rcd.vTable,CASE
              WHEN rcd.vTable = 'C' THEN
              (SELECT nFId FROM {PRE}CustomerMaster WHERE
               nCId = rcd.nDrAcc)
              WHEN rcd.vTable = 'V' THEN
              (SELECT nFId FROM {PRE}VendorMaster WHERE
               nVId = rcd.nDrAcc)
              WHEN rcd.vTable = 'AG' THEN
              (SELECT nFId FROM {PRE}Agent WHERE
               nAId = rcd.nDrAcc)
              WHEN rcd.vTable = 'O' THEN
              (SELECT nFId FROM {PRE}Other WHERE
               nOId = rcd.nDrAcc)
              ELSE
              0
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
	      rcd.vType as tktType, rcd.nTicketNumber as vNumber,
	      IF('O' = (SELECT vNewOld FROM {PRE}RefundRegisterDetails WHERE vTktType = rcd.vType AND  rcd.nTicketNumber = nTktNum), 
		 ( SELECT nGDS FROM {PRE}RefundRegisterDetails rrd LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId WHERE rrd.vTktType = rcd.vType AND  rcd.nTicketNumber = rrd.nTktNum),
 		CASE WHEN rcd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE rcd.nTicketNumber = nTId)
	             WHEN rcd.vType = 'Xo' THEN  ( SELECT nGDS FROM {PRE}XoBooking WHERE rcd.nTicketNumber = nXId )
		     ELSE '' END )as gds	
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' ".$extraCheck_rc.") ORDER BY location, document";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->location;
		
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);

    }//end function

    function getProfitGdsReport($extraCheck_sc,$extraCheck_rc)
    {
        session_start();

/*        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,CASE WHEN scd.vType='Tkt' THEN
                                         (SELECT nGDS FROM {PRE}TicketBooking WHERE nTId = scd.vTktNumber)
                                    WHEN scd.vType = 'Xo'  THEN
                                        (SELECT nGDS FROM {PRE}XoBooking WHERE nXId = scd.vTktNumber)
                                    ELSE 0 END as gdsVal,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                	 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 	ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                	 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Tkt'
									".$extraCheck." ORDER BY gdsVal DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,CASE WHEN scd.vType='Tkt' THEN
                                         (SELECT nGDS FROM {PRE}TicketBooking WHERE nTId = scd.vTktNumber)
                                    WHEN scd.vType = 'Xo'  THEN
                                        (SELECT nGDS FROM {PRE}XoBooking WHERE nXId = scd.vTktNumber)
                                    ELSE 0 END as gdsVal,
									SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                	 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 	ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                	 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Tkt'
									".$extraCheck." GROUP BY gdsVal ORDER BY sc.dDate DESC";
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
*/


        $adminGroupListQuery = "
		 (SELECT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber,
	   CASE WHEN scd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE scd.vTktNumber = nTId )
	        WHEN scd.vType = 'Xo' THEN ( SELECT nGDS FROM {PRE}XoBooking WHERE scd.vTktNumber = nXId )
		ELSE '' END as gds		
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' AND scd.vType != 'Htl'  ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks , rcd.nAirline, rcd.nDrAcc as account,rcd.vTable,CASE
              WHEN rcd.vTable = 'C' THEN
              (SELECT nFId FROM {PRE}CustomerMaster WHERE
               nCId = rcd.nDrAcc)
              WHEN rcd.vTable = 'V' THEN
              (SELECT nFId FROM {PRE}VendorMaster WHERE
               nVId = rcd.nDrAcc)
              WHEN rcd.vTable = 'AG' THEN
              (SELECT nFId FROM {PRE}Agent WHERE
               nAId = rcd.nDrAcc)
              WHEN rcd.vTable = 'O' THEN
              (SELECT nFId FROM {PRE}Other WHERE
               nOId = rcd.nDrAcc)
              ELSE
              0
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
	      rcd.vType as tktType, rcd.nTicketNumber as vNumber,
	      IF('O' = (SELECT vNewOld FROM {PRE}RefundRegisterDetails WHERE vTktType = rcd.vType AND  rcd.nTicketNumber = nTktNum), 
		 ( SELECT nGDS FROM {PRE}RefundRegisterDetails rrd LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId WHERE rrd.vTktType = rcd.vType AND  rcd.nTicketNumber = rrd.nTktNum),
 		CASE WHEN rcd.vType = 'Tkt' THEN ( SELECT nGDS FROM {PRE}TicketBooking WHERE rcd.nTicketNumber = nTId)
	             WHEN rcd.vType = 'Xo' THEN  ( SELECT nGDS FROM {PRE}XoBooking WHERE rcd.nTicketNumber = nXId )
		     ELSE '' END )as gds	
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' AND rcd.vType != 'Htl'  ".$extraCheck_rc.") ORDER BY gds, document";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->gds ."-". $row->location;
		
		$adminGroupRowSummary[$id]['gds'] = $row->gds;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		$adminGroupRowSummary[$id]['PAIDREF'] += $row->PAIDREF;


            }//end for each
        } //end if

	$adminGroupResults['list_values_details'] = $adminGroupRow;
	$adminGroupResults['list_values_summary'] = $adminGroupRowSummary;

        return ($adminGroupResults);

    }//end function

    function getRefundSummaryReport($type)
    {
        $adminGroupList = $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rcd.vType='".$type."'
                                            ".$this->extraCheck." GROUP BY
                                            rcd.vType ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getRefundDetailsReport($type,$id)
    {
        $adminGroupList = $this->db->query("SELECT rcd.vNetAmt as NETREF,rcd.vPaidAmt as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rcd.vType='".$type."'
                                            AND rcd.nTicketNumber='".$id."' ".$this->extraCheck." ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
/*
    function getRefundEmployeeReport($employee)
    {
      $adminGroupRow['NETREF']  = 0;
      $adminGroupRow['PAIDREF'] = 0;
        $salesGroupListQuery  = "SELECT * FROM {PRE}SalesClosingDetails WHERE vEmployee='".$employee."'";
        $salesGroupList 		= $this->db->query($salesGroupListQuery);
        if ($salesGroupList->num_rows() > 0)
        {
            foreach ($salesGroupList->result() as $key1=>$row1)
            {
                $adminGroupList = $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rcd.vType='".$row1->vType."'
                                            AND rcd.nTicketNumber='".$row1->vTktNumber."' ".$this->extraCheck." ORDER BY rc.dDate DESC");

                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                        $adminGroupRow['NETREF'] += $row->NETREF;
                        $adminGroupRow['PAIDREF'] += $row->PAIDREF;
                    }//end for each
                } //end if
            }//end for each
        } //end if

        $employeeCode = $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = '". $employee ."' "); 
        $adminGroupList = $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
            {PRE}RefundClosing rc 
            LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId 
            LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd.vTktType AND vNewOld = 'O'
            WHERE rc.vActive = '1' AND vIssuedBy = ". $employeeCode . "  " .$this->extraCheck." ORDER BY rc.dDate DESC");

        if ($adminGroupList->num_rows() > 0)
        {
          foreach ($adminGroupList->result() as $key=>$row)
          {
            $adminGroupRow['NETREF'] += $row->NETREF;
            $adminGroupRow['PAIDREF'] += $row->PAIDREF;
          }//end for each
        } //end if



        return ($adminGroupRow);
    }//end function
*/
    function getRefundLocationReport($loc)
    {
        $adminGroupList 		= $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rc.vLocation='".$loc."'
                                            ".$this->extraCheck." GROUP BY
                                            rc.vLocation ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getRefundCustomerReport($acc)
    {
        $adminGroupList 		= $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rcd.nDrAcc='".$acc."'
                                           AND rcd.vTable='C' ".$this->extraCheck." GROUP BY
                                            rc.vLocation ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getRefundVendorReport($acc)
    {
        $adminGroupList = $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND rcd.nDrAcc='".$acc."'
                                           AND rcd.vTable='V' ".$this->extraCheck." GROUP BY
                                            rc.vLocation ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getRefundHotelSummaryReport($type,$tkt)
    {
        $adminGroupList = $this->db->query("SELECT SUM(rcd.vNetAmt) as NETREF,SUM(rcd.vPaidAmt) as PAIDREF FROM
                                            {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd
                                            ON rc.nRCId = rcd.nRefundCloseId WHERE rc.vActive = '1' AND
                                            rcd.vType='".$type."' AND rcd.nTicketNumber='".$tkt."'
                                            ".$this->extraCheck." GROUP BY
                                            rcd.nTicketNumber ORDER BY rc.dDate DESC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow['NETREF'] = $row->NETREF;
                $adminGroupRow['PAIDREF'] = $row->PAIDREF;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function returnTktDetails($sId,$tblName = 'S')
    {
        $salesCloseDetailsTicketNumber = array();

        if($tblName == 'S')
          $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$sId."'");
        else
          $creditDetailsList = $this->db->query("SELECT * , nTicketNumber as vTktNumber FROM {PRE}RefundClosingDetails WHERE nRefundCloseId='".$sId."'");

        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $salesTktNumber  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$row->vTktNumber."'");
                        break;

                    case "Xo":
                        $salesTktNumber  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$row->vTktNumber."'");
                        break;

                    case "Htl":
                        $salesTktNumber  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$row->vTktNumber."'");
                        break;
                }//end switch
                if($salesTktNumber =='')
                  $salesTktNumber  = $row->vTktNumber;


                $salesCloseDetailsTicketNumber[]    = $salesTktNumber;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnTktNumber($Id,$type)
    {

        $salesCloseDetailsTicketNumber = array();

        switch($type)
        {
            case "SJ":
                $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$Id."'");
                if ($creditDetailsList->num_rows() > 0)
                {
                    foreach ($creditDetailsList->result() as $key=>$row)
                    {
                        switch($row->vType)
                        {
                            case "Tkt":
                                $salesTktNumber  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$row->vTktNumber."'");
                                break;

                            case "Xo":
                                $salesTktNumber  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$row->vTktNumber."'");
                                break;

                            case "Htl":
                                $salesTktNumber  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$row->vTktNumber."'");
                                break;

                        }//end switch

                        $salesCloseDetailsTicketNumber[]    = $salesTktNumber;
                    }//end for each
                }//end if
            break;

            case "RC":
                $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$Id."'");
                if ($creditDetailsList->num_rows() > 0)
                {
                    foreach ($creditDetailsList->result() as $key=>$row)
                    {
                        switch($row->vType)
                        {
                            case "Tkt":
                                $salesTktNumber  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$row->nTicketNumber."'");
                                break;

                            case "Xo":
                                $salesTktNumber  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$row->nTicketNumber."'");
                                break;

                            case "Htl":
                                $salesTktNumber  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$row->nTicketNumber."'");
                                break;

                        }//end switch
                        if($salesTktNumber == ''){
                          $salesTktNumber = $row->nTicketNumber;
                        }

                        $salesCloseDetailsTicketNumber[]    = $salesTktNumber;
                    }//end for each
                }//end if
            break;

            default:
                $salesTktNumber = '';

        }

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnPassengerDetails($sId,$tblName = 'S')
    {
        $salesClosePassenger = array();

        if($tblName == 'S')
          $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$sId."'");
        else
          $creditDetailsList = $this->db->query("SELECT * , nTicketNumber as vTktNumber FROM {PRE}RefundClosingDetails WHERE nRefundCloseId='".$sId."'");
        $salesClosePassenger = array();
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
                $passenger = $this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row->vTktNumber."'");

if($passenger == ''){
$passenger = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE vTktType = '".$row->vType."' AND nTktNum = ".$row->vTktNumber ." "); 
}
                $salesClosePassenger[] = $passenger; 
            }//end for each
        }//end if
        $newVal	 = @join(", ",$salesClosePassenger);
        return ($newVal);
    }//end function

    function getTktNumberValues($detId,$tableId)
    {
        $newArray = $valTktArray = $valDetArray = array();
        $salesCloseId  = $this->common_model->get_field_name('SystemJvDetails','nSjId'," WHERE nSjDId='".$detId."'");
        $sjGroup       = $this->db->query("SELECT * FROM {PRE}SystemJvDetails WHERE nSjId='".$salesCloseId."' ORDER BY nSjDId ASC");
        if ($sjGroup->num_rows() > 0)
        {
            foreach ($sjGroup->result() as $key => $row)
            {
                if($row->vNumber>0):
                    switch($row->vType)
                    {
                        case "Xo" :
                            $detVal  = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$row->vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->vNumber."'");
                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;

                            $airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$row->vNumber."'");
                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->vNumber."'");
                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;

                        case "Tkt" :
                            $detVal = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->vNumber."'");

                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;

                            $airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$row->vNumber."'");
                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->vNumber."'");
                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;

                        case "Htl" :
                            $cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->vNumber."'");
                            $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->vNumber."'");
                            $detVal  = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->vNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->vNumber."'");
                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;
                            $tktVal  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->vNumber."'");

                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;
                    }//end switch
                else:
                    switch($row->vType)
                    {
                        case "Xo" :
                            $ticketId       = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjId='".$tableId."' AND vType = '".$row->vType."' ORDER BY nSjDId ASC");
                            $detVal  = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$ticketId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$ticketId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$ticketId."'");
                            $airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$ticketId."'");

                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;

                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$ticketId."'");
                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;

                        case "Tkt" :
                            $ticketId       = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjId='".$tableId."' AND vType = '".$row->vType."' ORDER BY nSjDId ASC");
                            $detVal  = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$ticketId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$ticketId."'");

                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;

                            $airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$ticketId."'");
                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$ticketId."'");
                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;

                        case "Htl" :
                            $ticketId       = $this->common_model->get_field_name('SystemJvDetails','vNumber'," WHERE nSjId='".$tableId."' AND vType = '".$row->vType."' ORDER BY nSjDId ASC");
                            $cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$ticketId."'");
                            $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$ticketId."'");
                            $detVal  = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$ticketId."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$ticketId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$ticketId."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$ticketId."'");
                            if(!in_array($detVal,$valDetArray))
                                $valDetArray[] = $detVal;

                            $tktVal  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$ticketId."'");

                            if(!in_array($tktVal,$valTktArray))
                                $valTktArray[] = $tktVal;
                            break;
                    }//end switch
                endif;
            }//end for each

            $newArray['desc']   =  @join('<br>',$valDetArray);
            $newArray['tktVal'] =  @join('<br>',$valTktArray);
        }//end if

        return ($newArray);
    }//end function

    function getPayableDebitCreditValues($SubAccount,$list_value)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        foreach($list_value as $key=>$item):

            switch($item->mode)
            {
                case "Dr":
                    $debit = $item->debit;
                    $credit = 0;
                    break;

                case "Cr":
                    $debit = 0;
                    $credit = $item->debit;
                    break;

                default:
                    $debit = ($item->debit==' ')?0:$item->debit;
                    $credit = ($item->credit==' ')?0:$item->credit;
                    break;
            } //end switch

            if($item->account != '')
            {
                switch($item->vTable)
                {
                    case "C":
                        $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->account."'");
                        break;

                    case "V":
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'");
                        break;

                    case "A":
                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
                        break;

                    case "O":
                        $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->account."'");
                        break;

                    case "AG":
                        $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->account."'");
                        break;
                }
            }
            else
            {
                if($item->subacc == 32 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 34 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 35 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                else:
                    $SubAccountName = '&nbsp';
                    $SubAccountCode = '&nbsp';
                endif;
            }


            $mainAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->mainAccount."'");

            if($mainAccountCode == $SubAccount):
                switch($item->mainMode)
                {
                    case "Dr":
                        $debit += $item->mainAmount;
                        break;

                    case "Cr":
                        $credit += $item->mainAmount;
                        break;
                }//end switch
            endif;

            if($SubAccountCode == $SubAccount || $mainAccountCode == $SubAccount):
                $cnt++;
                $wcredit += $credit;
                $wdebit  += $debit;
            endif;
        endforeach;

        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;

        return ($valArray);
    }//end function

    function getDebitCreditValues($SubAccount,$list_value,$acId)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        foreach($list_value as $key=>$item):
		
			if($item->account == $acId):
			//check same account or not
				switch($item->mode)
				{
					case "Dr":
						$debit  = $item->debit;
						$credit = 0;
					break;
	
					case "Cr":
						$debit  = 0;
						$credit = $item->debit;
					break;
	
					default:
						$debit  = ($item->debit==' ')?0:$item->debit;
						$credit = ($item->credit==' ')?0:$item->credit;
					break;
				} //end switch
			
                $wcredit += $credit;
                $wdebit  += $debit;
			endif;
        endforeach;

		$mainAccArray        = $this->getMainAccountVal($SubAccount,$list_value,$acId);
		$mainAccArray        = @array_filter($mainAccArray);
		
        $valArray['wcredit'] = $wcredit + $mainAccArray['wcredit'];
        $valArray['wdebit']  = $wdebit  + $mainAccArray['wdebit'];
		
		
        return ($valArray);
    }//end function
	
	function getMainAccountVal($SubAccount,$list_value,$acId)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        foreach($list_value as $key=>$item):
			//checking main account amount
			if($item->mainAccount == $acId):
			 
			 	$mainAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
											" WHERE nId='".$item->mainAccount."'");
				switch($item->mainMode)
				{
					case "Dr":
						$debit  = $item->mainAmount;
						$credit = 0;
					break;
	
					case "Cr":
						$debit  = 0;
						$credit = $item->mainAmount;
					break;
						
					default:
						$debit  = 0;
						$credit = 0;
					break;
				}//end switch
				
				
				$wcredit += $credit;
                $wdebit  += $debit;
			endif;
        endforeach;

		
        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;
		
        return ($valArray);
    }//end function
	
	function getMainAccountValDetails($SubAccount,$list_value,$acId)
    {
        $cnt = $wdebit  = $wcredit = 0;
		$chkTblArray    = $chkNotTblArray = $docArray = $dateArray = array();
        $valArray       = array();

        foreach($list_value as $key=>$item):
			//checking main account amount
			 if($item->mainAccount == $acId):
			 	$mainAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
											" WHERE nId='".$item->mainAccount."'");
				switch($item->mainMode)
				{
					case "Dr":
						$debit  = $item->mainAmount;
						$credit = 0;
					break;
	
					case "Cr":
						$debit  = 0;
						$credit = $item->mainAmount;
					break;
						
					default:
						$debit  = 0;
						$credit = 0;
					break;
				}//end switch
				
				
				$wcredit    += $credit;
                $wdebit     += $debit;
				$id          = true;
				$docArray[]  = $item->document;
				$dateArray[] = date('d-m-Y',strtotime($item->date));
			endif;
        endforeach;

		
        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;
		$valArray['show']    = $id;
		$valArray['doc']     = @join(",",$docArray);
		$valArray['date']    = @join(",",$dateArray);
		
        return ($valArray);
    }//end function

	function getLocationNetTotal($loc)
	{
		$sqlLoc = $this->db->query("SELECT SUM(scd.vNetAmt) as netAmt FROM {PRE}SalesClosing sc 
										LEFT JOIN {PRE}SalesClosingDetails
											 scd ON sc.nSCId = scd.nSalesCloseId 
									WHERE sc.vActive = '1' AND sc.vLocation = '".$loc."'
										 GROUP BY sc.vLocation ORDER BY sc.vLocation ASC");
		if($sqlLoc->num_rows() > 0)
		{
			$sqlLocRow = $sqlLoc->row();
			return ($sqlLocRow->netAmt);
		}//end if
	}//end funciton
	
	function getLocationSalesTotal($loc)
	{
		$sqlLoc = $this->db->query("SELECT SUM(scd.vCollected) as salesAmt FROM {PRE}SalesClosing sc 
										LEFT JOIN {PRE}SalesClosingDetails
											 scd ON sc.nSCId = scd.nSalesCloseId 
									WHERE sc.vActive = '1' AND sc.vLocation = '".$loc."'
										 GROUP BY sc.vLocation ORDER BY sc.vLocation ASC");
		if($sqlLoc->num_rows() > 0)
		{
			$sqlLocRow = $sqlLoc->row();
			return ($sqlLocRow->salesAmt);
		}//end if
	}//end funciton
/*
    function getEmployeeNetTotal($emp)
    {
        $sqlLoc = $this->db->query("SELECT SUM(scd.vNetAmt) as netAmt FROM {PRE}SalesClosing sc
										LEFT JOIN {PRE}SalesClosingDetails
											 scd ON sc.nSCId = scd.nSalesCloseId
									WHERE sc.vActive = '1' AND scd.vEmployee = '".$emp."'
										 GROUP BY scd.vEmployee ORDER BY scd.vEmployee ASC");
        if($sqlLoc->num_rows() > 0)
        {
            $sqlLocRow = $sqlLoc->row();
            return ($sqlLocRow->netAmt);
        }//end if
    }//end funciton

    function getEmployeeSalesTotal($emp)
    {
        $sqlLoc = $this->db->query("SELECT SUM(scd.vCollected) as salesAmt FROM {PRE}SalesClosing sc
										LEFT JOIN {PRE}SalesClosingDetails
											 scd ON sc.nSCId = scd.nSalesCloseId
									WHERE sc.vActive = '1' AND scd.vEmployee = '".$emp."'
										 GROUP BY scd.vEmployee ORDER BY scd.vEmployee ASC");
        if($sqlLoc->num_rows() > 0)
        {
            $sqlLocRow = $sqlLoc->row();
            return ($sqlLocRow->salesAmt);
        }//end if
    }//end funciton
*/	
	//get net sales and sales amonunt from system jv
	function vendoerNetSales($subAcId,$acId,$tNumber)
	{
		$dataArray = array();
		
		//get Net sale
		$sqlNet = $this->db->query("SELECT *,nCredit,nDebit,
										 CASE WHEN vType = 'Htl' THEN 
										 	(SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = vNumber) 
 										  WHEN vType = 'Tkt' THEN (SELECT nAcc FROM {PRE}TicketFareDetails 
										  	WHERE nTicketId = vNumber)
										  ELSE  (SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = vNumber)
										  	END AS accId FROM {PRE}SystemJvDetails	
										  WHERE nAccHeadId = '".$subAcId."' HAVING accId = '".$acId."'");
		if($sqlNet->num_rows() > 0):
			foreach($sqlNet->result() as $key => $val):
				$netTotal    += $val->nCredit;
				$netRefTotal += $val->nDebit;
			endforeach;
			$dataArray['netSale'] = $netTotal;
			$dataArray['NETREF']  = $netRefTotal;
		endif;
		
		//get sale total
        $getSalesCloseId = $this->getSysJvId($subAcId);
        if(!empty($getSalesCloseId)):
            foreach($getSalesCloseId as $sales => $closeId):
                $sqlNet = $this->db->query("SELECT scd.vSlsAmt as saleTotal,
                                             CASE WHEN sjv.vType = 'Htl' THEN
                                                (SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = sjv.vNumber)
                                              WHEN sjv.vType = 'Tkt' THEN (SELECT nAcc FROM {PRE}TicketFareDetails
                                                WHERE nTicketId = sjv.vNumber)
                                              ELSE  (SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = sjv.vNumber)
                                                END AS accId FROM {PRE}SystemJvDetails sjv
                                                LEFT JOIN {PRE}SystemJv sj ON sj.nId =  sjv.nSjId
                                              LEFT JOIN {PRE}SalesClosingDetails scd ON
                                              (sj.nSalesCId = scd.nSalesCloseId AND sjv.vNumber = scd.vTktNumber
                                                    AND sjv.vType = scd.vType)
                                              WHERE sjv.nAccHeadId = '".$subAcId."'
                                                AND scd.nSalesCloseId = '".$closeId."' HAVING accId = '".$acId."'");

                if($sqlNet->num_rows() > 0):
                    foreach($sqlNet->result() as $key => $val):
                        $salAmt  += $val->saleTotal;
                    endforeach;

                    $dataArray['saleAmt'] = $salAmt;
                endif;
            endforeach;
        endif;
		return ($dataArray);
	}//end function

    function getSysJvId($id)
    {
        $sqlNet = $this->db->query("SELECT DISTINCT sj.nSalesCId FROM {PRE}SystemJv sj LEFT JOIN {PRE}SystemJvDetails sjd
									ON sj.nId = sjd.nSjId WHERE sjd.nAccHeadId = '".$id."' AND sj.vType='S'");
        if($sqlNet->num_rows() > 0):
            foreach($sqlNet->result() as $key => $val):
                $row[] = $val->nSalesCId;
            endforeach;
        endif;
        return ($row);
    }//end function

	//get cutomer net n sale amount
	function getCustomerNetSaleAmt($acId)
	{
		$totalArray = array();
		$sql        = $this->db->query("SELECT SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt 
										FROM {PRE}SalesAccountDetails sad  LEFT JOIN {PRE}SalesClosingDetails scd ON
										sad.nSalesCloseId = scd.nSalesCloseId WHERE sad.nDrAccount = '".$acId."' AND
										sad.vTable = 'C' GROUP BY sad.nDrAccount");
		if($sql->num_rows() > 0):
			$val                    = $sql->row();
			$totalArray['netAmt']   = $val->netAmt;
			$totalArray['salesAmt'] = $val->salesAmt;
		endif;
		
		return ($totalArray);
	}//end function
/*	
	function getRefundId($acId)
	{
		$sql = $this->db->query($_SESSION['sess_refund_query']);
		if($sql->num_rows() > 0):
			foreach($sql->result() as $rkey => $rval):
				if($rval->subacc == $acId):
					return ($tktId = $this->getRefundTktId($rval->nAGId,$rval->sjvId));
				endif;
			endforeach;
		endif;
	}//end function
*/
	function getRefundTktId($acId,$sjvId)
	{
        $refundArray = array();
		$sql = $this->db->query("SELECT vNumber,vType FROM {PRE}SystemJvDetails WHERE nAGId = '".$acId."'
											AND nSjId = '".$sjvId."'");
		if($sql->num_rows() > 0):
			$val = $sql->row();
            $refundArray['vNumber']   = $val->vNumber;
            $refundArray['vType'] = $val->vType;
		endif;

        return ($refundArray);
	}//end function
	
/*
	//get net sales and sales amonunt from system jv
	function vendoerRefund($subAcId)
	{
		$dataArray = array();

		$rfnD = false;
		$rfnD = $this->getRefundId($subAcId);

		if($rfnD == true):
				$sqlNet = $this->db->query("SELECT vPaidAmt as saleTotal FROM {PRE}RefundClosingDetails WHERE
				                            nTicketNumber = '".$rfnD['vNumber']."' AND vType = '".$rfnD['vType']."' ");
		
				if($sqlNet->num_rows() > 0):
					foreach($sqlNet->result() as $key => $val):
						$salAmt  += $val->saleTotal;
					endforeach;
		
					$dataArray['PAIDREF'] = $salAmt;
				endif;
		endif;

		return ($dataArray);
	}//end function
*/
    //get net sales and sales amonunt from system jv
    function vendorRefundDetails($type,$tktNumber)
    {
        $dataArray = array();
        $sqlNet = $this->db->query("SELECT vPaidAmt as saleTotal FROM {PRE}RefundClosingDetails WHERE
                                        nTicketNumber = '".$type."' AND vType = '".$tktNumber."' ");
        if($sqlNet->num_rows() > 0):
            foreach($sqlNet->result() as $key => $val):
                $salAmt  += $val->saleTotal;
            endforeach;

            $dataArray['PAIDREF'] = $salAmt;
        endif;

        return ($dataArray);
    }//end function

    //get net sales and sales amonunt from system jv
    function vendoerNetSalesDetails($subAcId,$acId,$tNumber,$saleId,$vendorId,$type,$tktNumber,$closeType)
    {
        if($closeType=='S'):
            $dataArray = array();
            //get Net sale
            $sqlNet = $this->db->query("SELECT *,nCredit,
										 CASE WHEN vType = 'Htl' THEN
										 	(SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = vNumber)
 										  WHEN vType = 'Tkt' THEN (SELECT nAcc FROM {PRE}TicketFareDetails
										  	WHERE nTicketId = vNumber)
										  ELSE  (SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = vNumber)
										  	END AS accId FROM {PRE}SystemJvDetails
										  WHERE nAccHeadId = '".$subAcId."' AND vType = '".$type."'
										  AND vNumber = '".$tktNumber."' HAVING accId = '".$vendorId."'");
            if($sqlNet->num_rows() > 0):
                foreach($sqlNet->result() as $key => $val):
                    $netTotal    += $val->nCredit;
                endforeach;
                $dataArray['netSale'] = $netTotal;
            endif;

            //get sale total
            $sqlNet = $this->db->query("SELECT scd.vSlsAmt as saleTotal,
										 CASE WHEN sjv.vType = 'Htl' THEN
										 	(SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = sjv.vNumber)
 										  WHEN sjv.vType = 'Tkt' THEN (SELECT nAcc FROM {PRE}TicketFareDetails
										  	WHERE nTicketId = sjv.vNumber)
										  ELSE  (SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = sjv.vNumber)
										  	END AS accId FROM {PRE}SystemJvDetails sjv
											LEFT JOIN {PRE}SystemJv sj ON sj.nId =  sjv.nSjId
										  LEFT JOIN {PRE}SalesClosingDetails scd ON
										  (sj.nSalesCId = scd.nSalesCloseId AND sjv.vNumber = scd.vTktNumber
										  		AND sjv.vType = scd.vType)
										  WHERE sjv.nAccHeadId = '".$subAcId."' AND sjv.vType = '".$type."'
										  AND sjv.vNumber = '".$tktNumber."'
										  	AND scd.nSalesCloseId = '".$saleId."' HAVING accId = '".$vendorId."'");

            if($sqlNet->num_rows() > 0):
                foreach($sqlNet->result() as $key => $val):
                    $salAmt  += $val->saleTotal;
                endforeach;

                $dataArray['saleAmt'] = $salAmt;
            endif;
        else:
            $dataArray = array();
            //get Net sale
            $sqlNet = $this->db->query("SELECT nDebit,
										 CASE WHEN vType = 'Htl' THEN
										 	(SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = vNumber)
 										  WHEN vType = 'Tkt' THEN (SELECT nAcc FROM {PRE}TicketFareDetails
										  	WHERE nTicketId = vNumber)
										  ELSE  (SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = vNumber)
										  	END AS accId FROM {PRE}SystemJvDetails
										  WHERE nAccHeadId = '".$subAcId."' AND vType = '".$type."'
										  AND vNumber = '".$tktNumber."' HAVING accId = '".$vendorId."'");
            if($sqlNet->num_rows() > 0):
                foreach($sqlNet->result() as $key => $val):
                    $netRefTotal += $val->nDebit;
                endforeach;
                $dataArray['NETREF']  = $netRefTotal;
            endif;
        endif;

        return ($dataArray);
    }//end function

}//end class
?>

