<?php
class Manage_info_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		ini_set('memory_limit', '-1');
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
            $aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==48 || $aid==47 || $aid==46){
					$locGroupRow[0]=SELECT;
				}
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

        $adminGroupListQuery  = "SELECT DISTINCT vLocation, vEmployee, dDate, vInvoiceNumber, vRemarks, tableId, tableName,
                                  SUM(netAmt) as NetAmount, SUM(salesAmt) SalesAmount ,
                                  SUM(NETREF) as NetRefund, SUM(PAIDREF) as PaidRefund FROM 
                                  (( SELECT sc.vLocation,scd.issue as vEmployee, sc.dDate, sc.vInvoiceNumber, sc.vRemarks, sc.nSCId as tableId, 'S' as tableName,
                                     SUM(scd.vNetAmt) as netAmt, SUM(scd.vCollected) as salesAmt ,
                                     0  as NETREF, 0 as PAIDREF
                                  FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId  WHERE sc.vActive = '1' ".$extraCheck."  GROUP BY
                                 scd.vEmployee, sc.vLocation,  sc.vInvoiceNumber)

                                 UNION ALL ( SELECT DISTINCT rc.vLocation,rrd.vIssuedBy AS vEmployee , rc.dDate, rc.vDocNumber as vInvoiceNumber, rc.vRemarks, rc.nRCId as tableId,'R' as tableName, 
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
		(SELECT DISTINCT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks,
	       	sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,scd.vCollected as salesAmt, '' as PAIDREF, 			scd.vNetAmt as netAmt,scd.vServAmt as servAmt, '' as NETREF,
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
              END as subacc, 0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, -rcd.vNetAmt as NETREF,rcd.vServAmt as servRef,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber
              FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
              rc.vActive = '1' AND rcd.vTable='C' ".$extraCheck_rc.") ORDER BY account, subacc,vTable,document ";
//print $adminGroupListQuery;
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
		$adminGroupRowSummary[$id]['servAmt'] += $row->servAmt;
        $adminGroupRowSummary[$id]['servRef'] += $row->servRef;

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
                                  as credit,'' as issued,sj.nSalesCId as tableId,COUNT(sjd.vType) as counts,
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
        //print $adminGroupListQuery;
		$adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              $adminGroupRow[] = $row;

              $id = $row->account. "-". $row->subacc . "-". $row->vTable  . "-". $row->location;
			  
              if($row->tblName == 'SC'){
                $adminGroupSummary[$id]['NetAmount'] += $row->debit + $row->credit;
				$adminGroupSummary[$id]['counts'] += $row->counts;
                if($row->vNumber!=0){
				$adminGroupSummary[$id]['SalesAmount'] += $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber = ".$row->vNumber."  AND vType = '".$row->tktType."' ");
				}else{
				$adminGroupSummary[$id]['SalesAmount'] +=$row->debit + $row->credit;	
				}
				}
              else{
                $adminGroupSummary[$id]['NetRefund'] += $row->debit + $row->credit;
				$adminGroupSummary[$id]['countsr'] += $row->counts;
				
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
	
	
	function getCustomerSummaryReport_op($extraCheck_op_cr,$extraCheck_op_cp,$extraCheck_op_bb,$extraCheck_op_bp,$extraCheck_op_jv,$extraCheck_op_cv,$extraCheck_op_dv,$extraCheck_op_ac,$extraCheck_op_ad,$extraCheck_op_sc,$extraCheck_op_rc,$extraCheck_op_sj_special,$extraCheck_op_op)
   {
        session_start();

         $adminGroupListQuery  = "(SELECT sc.dDate as date,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks as
                                  description,sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,sad.vAmt as debit,'' as credit,
                                  '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}SalesClosing sc LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId WHERE
                                  sc.vActive = '1' AND sad.vTable='C' ".$extraCheck_op_sc.")
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
										END as subacc,rcd.vPaidAmt as debit,'' as credit,
                                  '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE
                                  rc.vActive = '1' AND rcd.vTable='C' ".$extraCheck_op_rc.")
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,'' as credit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND crd.vTable='C' ".$extraCheck_op_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,'' as credit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vTable='C' ".$extraCheck_op_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,'' as credit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vTable='C' ".$extraCheck_op_bb.")
                                  
								  UNION ALL (SELECT op.dDate as date,op.vLocation as location,''  as document,'' as
                                  description, opd.vAcSubCode as account,'C' as vTable,opd.vACode as subacc,SUM(opd.vBalDebit) as debit,SUM(opd.vBalCredit) as credit,
                                  '' as issued,op.nOBId as tableId,'OP' as tblName,'' as mode,opd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}OpeningBalance op LEFT JOIN {PRE}OpeningBalanceDetails opd ON
                                  opd.nBalId = op.nOBId WHERE op.vActive='1' AND op.vSubLedger='C' ".$extraCheck_op_op." GROUP BY opd.vAcSubCode)
							
								  
								  
								  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,'' as credit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vTable='C' ".$extraCheck_op_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vTable='C' ".$extraCheck_op_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vTable='C' ".$extraCheck_op_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vTable='C' ".$extraCheck_op_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,acd.nAmount as debit,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vTable='C' ".$extraCheck_op_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,ade.nAmount as debit,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vTable='C' ".$extraCheck_op_ad." ORDER BY account, subacc,vTable, document )
                                  ORDER BY account DESC";
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

 function getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby)
    {

      $list ='';
      $get_acc_list = $this->db->query("SELECT nActHId FROM {PRE}AccountHeadControls WHERE vSubLedgerType = 'V'");
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

        $sj_query = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
              IF(sjd.nAccHeadId = 0, sjd.nDrAccount, sjd.nAccHeadId) as account, 
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
                  sjd.nDrAccount))as subacc,
              IF(sjd.vTable = '','V', sjd.vTable ) as vTable, 
              sjd.nDebit as debit,sjd.nCredit as credit,
              sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
              sjd.vNumber as vNumber
              FROM {PRE}SystemJv sj
              LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE '1' = '1'  ".$extraCheck_sj." ) ";

/*              IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                      WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                  ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O'
                    AND rr.nAccount = '". $this->ddlSubAccount  ."') ,
                  (CASE
                   WHEN sjd.vType = 'Tkt' THEN
                   (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                   WHEN sjd.vType = 'Htl' THEN
                   (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                   ELSE
                   (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END))
              as subCode */

      $cr_query = " (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
              cr.nAcCode as account,cr.vSubCode as subacc, '' as vTable,
              IF( cr.vTransMode = 'Dr' , cr.vAmountDC,0)as debit,
              IF( cr.vTransMode = 'Cr' , cr.vAmountDC,0)as credit,
              cr.nCRId as tableId,'CR' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}CashReceipt cr WHERE cr.vActive='1'
            ".$extraCheck_cr_top_acc." )
              UNION ALL 
              (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
               IF(crd.vAcSubCode !='0' , crd.vAcSubCode, crd.vACode) as account,
               IF(crd.vAcSubCode !='0' , crd.vACode, crd.vAcSubCode) as subacc, crd.vTable  as vTable,
               IF( crd.vMode = 'Dr' , crd.nAmount ,0)as debit,
               IF( crd.vMode = 'Cr' , crd.nAmount ,0)as credit,
               cr.nCRId as tableId,'CR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
               ".$extraCheck_cr." ) "  ;


     $cp_query = " (SELECT DISTINCT  cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
              cp.nAcCode as account,cp.vSubCode as subacc, '' as vTable,
              IF( cp.vTransMode = 'Dr' , cp.vAmountDC,0)as debit,
              IF( cp.vTransMode = 'Cr' , cp.vAmountDC,0)as credit,
              cp.nCPId as tableId,'CP' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId WHERE cp.vActive='1'
            ".$extraCheck_cp_top_acc." )
              UNION ALL
              (SELECT   cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
               IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode, cpd.vACode) as account,
               IF(cpd.vAcSubCode !='0' , cpd.vACode, cpd.vAcSubCode) as subacc, cpd.vTable  as vTable,
               IF( cpd.vMode = 'Dr' , cpd.nAmount ,0)as debit,
               IF( cpd.vMode = 'Cr' , cpd.nAmount ,0)as credit,
               cp.nCPId as tableId,'CP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId WHERE cp.vActive='1'
               ".$extraCheck_cp." ) "  ;


     $bb_query = " (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as description,
              bb.nAcCode as account,bb.vSubCode as subacc, '' as vTable,
              IF( bb.vTransMode = 'Dr' , bb.vAmountDC,0)as debit,
              IF( bb.vTransMode = 'Cr' , bb.vAmountDC,0)as credit,
              bb.nBBId as tableId,'BR' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}BankBook bb WHERE bb.vActive='1'
            ".$extraCheck_bb_top_acc." )
              UNION ALL
              (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as description,
               IF(bbd.vAcSubCode !='0' , bbd.vAcSubCode, bbd.vACode) as account,
               IF(bbd.vAcSubCode !='0' , bbd.vACode, bbd.vAcSubCode) as subacc, bbd.vTable  as vTable,
               IF( bbd.vMode = 'Dr' , bbd.nAmount ,0)as debit,
               IF( bbd.vMode = 'Cr' , bbd.nAmount ,0)as credit,
               bb.nBBId as tableId,'BR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1'
               ".$extraCheck_bb." ) "  ;


     $bp_query = " (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as description,
              bp.nAcCode as account,bp.vSubCode as subacc, '' as vTable,
              IF( bp.vTransMode = 'Dr' , bp.vAmountDC,0)as debit,
              IF( bp.vTransMode = 'Cr' , bp.vAmountDC,0)as credit,
              bp.nBPId as tableId,'BP' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}BankBookPayment bp WHERE bp.vActive='1'
            ".$extraCheck_bp_top_acc." )
              UNION ALL
              (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as description,
               IF(bpd.vAcSubCode !='0' , bpd.vAcSubCode, bpd.vACode) as account,
               IF(bpd.vAcSubCode !='0' , bpd.vACode, bpd.vAcSubCode) as subacc, bpd.vTable  as vTable,
               IF( bpd.vMode = 'Dr' , bpd.nAmount ,0)as debit,
               IF( bpd.vMode = 'Cr' , bpd.nAmount ,0)as credit,
               bp.nBPId as tableId,'BP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1'
               ".$extraCheck_bp." ) "  ;


$cv_query = " (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as description,
               IF(cvd.vAcSubCode !='0' , cvd.vAcSubCode, cvd.vACode) as account,
               IF(cvd.vAcSubCode !='0' , cvd.vACode, cvd.vAcSubCode) as subacc, cvd.vTable  as vTable,
               IF( cvd.vMode = 'Dr' , cvd.nAmount ,0)as debit,
               IF( cvd.vMode = 'Cr' , cvd.nAmount ,0)as credit,
               cv.nCVId as tableId,'CV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1'
               ".$extraCheck_cv." ) "  ;

$dv_query = " (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as description,
               IF(dvd.vAcSubCode !='0' , dvd.vAcSubCode, dvd.vACode) as account,
               IF(dvd.vAcSubCode !='0' , dvd.vACode, dvd.vAcSubCode) as subacc, dvd.vTable  as vTable,
               IF( dvd.vMode = 'Dr' , dvd.nAmount ,0)as debit,
               IF( dvd.vMode = 'Cr' , dvd.nAmount ,0)as credit,
               dv.nDVId as tableId,'DV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId WHERE dv.vActive='1'
               ".$extraCheck_dv." ) "  ;


     $ac_query = " (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
              ac.nAcCode as account,ac.vSubCode as subacc, 'V' as vTable,
              IF( ac.vTransMode = 'Dr' , ac.vAmountDC,0)as debit,
              IF( ac.vTransMode = 'Cr' , ac.vAmountDC,0)as credit,
              ac.nACId as tableId,'AC' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}AcmEntry ac WHERE ac.vActive='1'
            ".$extraCheck_ac_top_acc." )
              UNION ALL
              (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
               IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as account,
               IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as subacc, acd.vTable  as vTable,
               IF( acd.vMode = 'Dr' , acd.nAmount ,0)as debit,
               IF( acd.vMode = 'Cr' , acd.nAmount ,0)as credit,
               ac.nACId as tableId,'AC' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId WHERE ac.vActive='1'
               ".$extraCheck_ac." ) "  ;

            $ad_query = " (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
              ad.nAcCode as account,ad.vSubCode as subacc, 'V' as vTable,
              IF( ad.vTransMode = 'Dr' , ad.vAmountDC,0)as debit,
              IF( ad.vTransMode = 'Cr' , ad.vAmountDC,0)as credit,
              ad.nADId as tableId,'AD' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}AdmEntry ad WHERE ad.vActive='1'
            ".$extraCheck_ad_top_acc." )
              UNION ALL
              (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
               IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as account,
               IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as subacc, ade.vTable  as vTable,
               IF( ade.vMode = 'Dr' , ade.nAmount ,0)as debit,
               IF( ade.vMode = 'Cr' , ade.nAmount ,0)as credit,
               ad.nADId as tableId,'AD' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId WHERE ad.vActive='1'
               ".$extraCheck_ad." ) "  ;

            $jv_query = " (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as description,
               IF(jvd.vAcSubCode !='0' , jvd.vAcSubCode, jvd.vACode) as account,
               IF(jvd.vAcSubCode !='0' , jvd.vACode, jvd.vAcSubCode) as subacc, jvd.vTable  as vTable,
               IF( jvd.vMode = 'Dr' , jvd.nAmount ,0)as debit,
               IF( jvd.vMode = 'Cr' , jvd.nAmount ,0)as credit,
               jv.nJVId as tableId,'JV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId WHERE jv.vActive='1'
               ".$extraCheck_jv." ) "  ;
//$adminGroupListQuery = $sj_query . " " . $orderby;
//return;
//if($group_select != ''){
//echo  $group_select . "  " . $cr_query. " " . $groupby . " " . $orderby . "<br><br><br><br>";
//}
              $adminGroupListQuery = $group_select . "  "  . $sj_query . " UNION ALL ". $cr_query . " UNION ALL " .$cp_query. "
                UNION ALL  " . $bb_query . " UNION ALL ". $bp_query . "
                UNION ALL ". $cv_query ." UNION ALL ". $dv_query ."
                UNION ALL " . $ac_query . " UNION ALL " . $ad_query . " 
                UNION ALL " . $jv_query . " " . $groupby . " " . $orderby;
            
              //echo $adminGroupListQuery . "<br><br><br><br>";
              //return;

        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        $adminGroupRow = array();
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);


    }//end function
	
	function checks(){
      if(trim($this->txtFromDate) == '')
      {
        $curdate            = date('Y-m-d');
        $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));
      }//end if
      else
      {
        $curdate            = date('Y-m-d',strtotime($this->txtFromDate));
        $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));
      }//end else

      if ($this->ddlDivision != '') {
        $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
        $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";		
		$extraCheck_cpd = " AND cp.nDivision='".$this->ddlDivision."'";
        $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
        $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
        $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
        $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
        $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        $extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
        $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
        $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
      }//end if

      if ($this->ddlLocation != 0 && $this->ddlLocation2 != 0) {
		  $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
        $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";		
		//$extraCheck_cp .= " AND (cpd.nLoc='".$this->ddlLocation."')";
        $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
        $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
        $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
        $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
        $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
        $extraCheck_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
        $extraCheck_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
        $extraCheck_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
        
      }//end if
      else{
        if ($this->ddlLocation != 0 ) {
          $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
          $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";		  
		  $extraCheck_cpd .= " AND cp.vLocation='".$this->ddlLocation."'";
          $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
          $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
          $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
          $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
          $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
          $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
          $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
          $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
        }//end if

        if ($this->ddlLocation2 != 0 ) {
          $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
          $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";		  
		  $extraCheck_cpd .= " AND cp.vLocation='".$this->ddlLocation2."'";
          $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
          $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
          $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
          $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
          $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
          $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
          $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
          $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
        }//end if
      }//end else

      if ($this->txtFromDate !='') {
        $extraCheck_cr_dt = " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cp_dt = " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bb_dt = " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bp_dt = " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_jv_dt = " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cv_dt = " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_dv_dt = " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj_dt = " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ac_dt = " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ad_dt = " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

        $openBal_cr_dt = " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_cp_dt = " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_bb_dt = " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_bp_dt = " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_jv_dt = " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_cv_dt = " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_dv_dt = " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_sj_dt = " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_ac_dt = " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $openBal_ad_dt = " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
      }//end if

      if ($this->txtToDate != '') {
        $extraCheck_cr_dt .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cp_dt .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bb_dt .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bp_dt .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_jv_dt .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cv_dt .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_dv_dt .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_sj_dt .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ac_dt .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ad_dt .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      }//end if

      if ($this->ddlAccGroup != '' ) {

      }//end if


      $extraCheck_cr_top_acc = $extraCheck_cr;
      $extraCheck_cp_top_acc = $extraCheck_cp;	  
	  $extraCheck_cpd_top_acc = $extraCheck_cpd;
      $extraCheck_bb_top_acc = $extraCheck_bb;
      $extraCheck_bp_top_acc = $extraCheck_bp;
      $extraCheck_ac_top_acc = $extraCheck_ac;
      $extraCheck_ad_top_acc = $extraCheck_ad;

      // Account handeling for finance entries
      if (!($this->ddlAccount == '0' || $this->ddlAccount == '') ) {
        // CR CP BR BP ACM ADM 
        $extraCheck_cr_top_acc .= " AND cr.nAcCode='".$this->ddlAccount."'";
        $extraCheck_cp_top_acc .= " AND cp.nAcCode='".$this->ddlAccount."'";		
		$extraCheck_cpd_top_acc .= " AND cp.nAcCode='".$this->ddlAccount."'";
        $extraCheck_bb_top_acc .= " AND bb.nAcCode='".$this->ddlAccount."'";
        $extraCheck_bp_top_acc .= " AND bp.nAcCode='".$this->ddlAccount."'";
        $extraCheck_ac_top_acc .= " AND ac.nAcCode='".$this->ddlAccount."'";
        $extraCheck_ad_top_acc .= " AND ad.nAcCode='".$this->ddlAccount."'";

        // CRD CPD BRD BPD CRV DRV ACM ADM
        $extraCheck_cr .= " AND IF(crd.vAcSubCode !='0' , crd.vAcSubCode = '".$this->ddlAccount."', crd.vACode='".$this->ddlAccount."' )";
        $extraCheck_cp .= " AND IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode = '".$this->ddlAccount."', cpd.vACode='".$this->ddlAccount."' )";		
		$extraCheck_cpd .= " AND IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode = '".$this->ddlAccount."', cpd.vACode='".$this->ddlAccount."' )";
        $extraCheck_bb .= " AND IF(bbd.vAcSubCode !='0' , bbd.vAcSubCode = '".$this->ddlAccount."', bbd.vACode='".$this->ddlAccount."' )";
        $extraCheck_bp .= " AND IF(bpd.vAcSubCode !='0' , bpd.vAcSubCode = '".$this->ddlAccount."', bpd.vACode='".$this->ddlAccount."' )";
        $extraCheck_jv .= " AND IF(jvd.vAcSubCode !='0' , jvd.vAcSubCode = '".$this->ddlAccount."', jvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_cv .= " AND IF(cvd.vAcSubCode !='0' , cvd.vAcSubCode = '".$this->ddlAccount."', cvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_dv .= " AND IF(dvd.vAcSubCode !='0' , dvd.vAcSubCode = '".$this->ddlAccount."', dvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vAcSubCode = '".$this->ddlAccount."', acd.vACode='".$this->ddlAccount."' )";
        $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vAcSubCode = '".$this->ddlAccount."', ade.vACode='".$this->ddlAccount."' )";

        $extraCheck_sj_special =  " HAVING account = '". $this->ddlAccount ."'";

        if (!($this->ddlSubAccount == '' || $this->ddlSubAccount =='0') ){

          // CR CP BR BP no need of subaccount. It will always be 0
          //ACM ADM 
          $extraCheck_ac_top_acc .= " AND ac.vSubCode = '".$this->ddlSubAccount."' AND 'V' = '" . $this->tableType ."' ";
          $extraCheck_ad_top_acc .= " AND ad.vSubCode = '".$this->ddlSubAccount."' AND 'V' = '" . $this->tableType ."' ";


          // CRD CPD BRD BPD CRV DRV ACM ADM
          $extraCheck_cr .= " AND IF(crd.vAcSubCode !='0' , crd.vACode = '".$this->ddlSubAccount."' AND crd.vTable ='V',1 )";
          $extraCheck_cp .= " AND IF(cpd.vAcSubCode !='0' , cpd.vACode = '".$this->ddlSubAccount."' AND cpd.vTable ='V',1 )"; 			
		  $extraCheck_cpd .= " AND IF(cpd.vAcSubCode !='0' , cpd.vACode = '".$this->ddlSubAccount."' AND cpd.vTable ='V',1 )"; 
          $extraCheck_bb .= " AND IF(bbd.vAcSubCode !='0' , bbd.vACode = '".$this->ddlSubAccount."' AND bbd.vTable ='V',1 )";
          $extraCheck_bp .= " AND IF(bpd.vAcSubCode !='0' , bpd.vACode = '".$this->ddlSubAccount."' AND bpd.vTable ='V',1 )";
          $extraCheck_jv .= " AND IF(jvd.vAcSubCode !='0' , jvd.vACode = '".$this->ddlSubAccount."' AND jvd.vTable ='V',1 )";
          $extraCheck_cv .= " AND IF(cvd.vAcSubCode !='0' , cvd.vACode = '".$this->ddlSubAccount."' AND cvd.vTable ='V',1 )";
          $extraCheck_dv .= " AND IF(dvd.vAcSubCode !='0' , dvd.vACode = '".$this->ddlSubAccount."' AND dvd.vTable ='V',1 )";
          $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vACode = '".$this->ddlSubAccount."' AND acd.vTable ='V',1 )";
          $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vACode = '".$this->ddlSubAccount."' AND ade.vTable ='V',1 )";

          $extraCheck_sj_special .=  " AND subacc = '". $this->ddlSubAccount."'";

        }
      }//end if

/*
      //account handeling for sjv
      if (!($this->ddlAccount == '0' || $this->ddlAccount == '') ) {
        if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
          $extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";

        if (!($this->ddlSubAccount == '' || $this->ddlSubAccount =='0') ):
          $extraCheck_sj_special = " HAVING subCode !=''";
        endif;
else:
        $extraCheck_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
        $closeId        = $this->ddlAccount.'-'.$this->tableType;
        $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
          OR sjd.vClosedId LIKE '%".$closeId."%')";

        if (!($this->ddlSubAccount == '' || $this->ddlSubAccount =='0') ):
          $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
        endif;
        endif;
      }//end if
*/

      $extra_checks['open_cr'] = $extraCheck_cr.$openBal_cr_dt;
      $extra_checks['open_cp'] = $extraCheck_cp.$openBal_cp_dt;	  
	  $extra_checks['open_cpd'] = $extraCheck_cpd.$openBal_cp_dt;
      $extra_checks['open_bb'] = $extraCheck_bb.$openBal_bb_dt;
      $extra_checks['open_bp'] = $extraCheck_bp.$openBal_bp_dt;
      $extra_checks['open_jv'] = $extraCheck_jv.$openBal_jv_dt;
      $extra_checks['open_cv'] = $extraCheck_cv.$openBal_cv_dt;
      $extra_checks['open_dv'] = $extraCheck_dv.$openBal_dv_dt;
      $extra_checks['open_sj'] = $extraCheck_sj.$openBal_sj_dt.$extraCheck_sj_special;
      $extra_checks['open_ac'] = $extraCheck_ac.$openBal_ac_dt;
      $extra_checks['open_ad'] = $extraCheck_ad.$openBal_ad_dt;

      $extra_checks['open_cr_top_acc'] = $extraCheck_cr_top_acc . $openBal_cr_dt;
      $extra_checks['open_cp_top_acc'] = $extraCheck_cp_top_acc . $openBal_cp_dt;	  
	  $extra_checks['open_cpd_top_acc'] = $extraCheck_cpd_top_acc . $openBal_cp_dt;
      $extra_checks['open_bb_top_acc'] = $extraCheck_bb_top_acc . $openBal_bb_dt;
      $extra_checks['open_bp_top_acc'] = $extraCheck_bp_top_acc . $openBal_bp_dt;
      $extra_checks['open_ac_top_acc'] = $extraCheck_ac_top_acc . $openBal_ac_dt;
      $extra_checks['open_ad_top_acc'] = $extraCheck_ad_top_acc . $openBal_ad_dt;


      $extra_checks['extraCheck_cr']= $extraCheck_cr . $extraCheck_cr_dt;
      $extra_checks['extraCheck_cp']= $extraCheck_cp . $extraCheck_cp_dt;	  
	  $extra_checks['extraCheck_cpd']= $extraCheck_cpd . $extraCheck_cp_dt;
      $extra_checks['extraCheck_bb']= $extraCheck_bb . $extraCheck_bb_dt;
      $extra_checks['extraCheck_bp']= $extraCheck_bp . $extraCheck_bp_dt;
      $extra_checks['extraCheck_jv']= $extraCheck_jv . $extraCheck_jv_dt;
      $extra_checks['extraCheck_cv']= $extraCheck_cv . $extraCheck_cv_dt;
      $extra_checks['extraCheck_dv']= $extraCheck_dv . $extraCheck_dv_dt;
      $extra_checks['extraCheck_sj']= $extraCheck_sj . $extraCheck_sj_dt.$extraCheck_sj_special;
      $extra_checks['extraCheck_ac']= $extraCheck_ac . $extraCheck_ac_dt;
      $extra_checks['extraCheck_ad']= $extraCheck_ad . $extraCheck_ad_dt;

      $extra_checks['extraCheck_cr_top_acc']= $extraCheck_cr_top_acc . $extraCheck_cr_dt; 
      $extra_checks['extraCheck_cp_top_acc']= $extraCheck_cp_top_acc . $extraCheck_cp_dt;	  
	  $extra_checks['extraCheck_cpd_top_acc']= $extraCheck_cpd_top_acc . $extraCheck_cp_dt;
      $extra_checks['extraCheck_bb_top_acc']= $extraCheck_bb_top_acc . $extraCheck_bb_dt;
      $extra_checks['extraCheck_bp_top_acc']= $extraCheck_bp_top_acc . $extraCheck_bp_dt;
      $extra_checks['extraCheck_ac_top_acc']= $extraCheck_ac_top_acc . $extraCheck_ac_dt;
      $extra_checks['extraCheck_ad_top_acc']= $extraCheck_ad_top_acc . $extraCheck_ad_dt;

      $extra_checks['extraCheck_sj_special'] = $extraCheck_sj_special;

      return $extra_checks; 


    }
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


    function getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj,$extraCheck_sj_special,$extraCheck_op)
    {
        session_start();
        $list = $this->getVendorAccNum();
                                  //sjd.nDrAccount as account,sjd.vTable,
								  
			//if($this->ddlSubAccount==''){
				
			if($this->ddlLocation==0){
				
			}	else{
				$loc=" AND cm.vLocation=".$this->ddlLocation."";
			}
			if($this->ddlAccount==0){
				
			}else{
				$id=" AND cm.nFId='".$this->ddlAccount."'";
			}
			if($this->ddlAccount!=''){
				
				   $acc="UNION ALL (SELECT '' as date,cm.vLocation as location,'' as document,'' as
                                  description,cm.nVId as account,'V'as vTable,cm.nFId as subacc,'' as debit,'' as credit,
                                  '' as issued,'' as tableId,'' as tblName,'' as vNumber,'' as mode,'' as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  tbl_VendorMaster cm  WHERE cm.vActive='1' ".$loc.$id.")";
				
			}	//}				
						
			$drAccount = @split("-",$this->ddlSubAccount);					
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
                                                    nTicketId = sjd.vNumber AND nAcc = '".$drAccount[0]."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$drAccount[0]."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$drAccount[0]."') END as subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj."
                                  AND (sjd.vTable = 'V' OR sjd.nAccHeadId
                                  IN ".$list.") GROUP BY sjd.nSjDId ".$extraCheck_sj_special.")".$acc."
								  
									UNION ALL (SELECT op.dDate as date,op.vLocation as location,''  as document,'' as
                                  description, opd.vAcSubCode as account,'V' as vTable,opd.vACode as subacc,'0' as debit,'0' as credit,
                                  '' as issued,op.nOBId as tableId,'OP' as tblName,'' as vNumber,'' as mode,opd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}OpeningBalance op LEFT JOIN {PRE}OpeningBalanceDetails opd ON
                                  opd.nBalId = op.nOBId WHERE op.vActive='1' AND op.vSubLedger='V' ".$extraCheck_op." GROUP BY opd.vAcSubCode)
								  
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
                                  description,ac.vSubCode as account,'V'as vTable,ac.nAcCode as subacc,acd.nAmount as debit,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,'' as vNumber,ac.vTransMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1'  ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.vSubCode as account,'V'as vTable,ad.nAcCode as subacc,ade.nAmount as debit,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,'' as vNumber,ad.vTransMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." ORDER BY account, subacc,vTable, document )
                                  ORDER BY subacc ASC";
		//print $adminGroupListQuery;
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
	
	function getVendorSummaryReport_op($extraCheck_op_cr,$extraCheck_op_cp,$extraCheck_op_bb,$extraCheck_op_bp,$extraCheck_op_jv,$extraCheck_op_cv,$extraCheck_op_dv,$extraCheck_op_ac,$extraCheck_op_ad,$extraCheck_op_sj,$extraCheck_op_sj_special,$extraCheck_op_op)
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
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_op_sj."
                                  AND (sjd.vTable = 'V' OR sjd.nAccHeadId
                                  IN ".$list.") GROUP BY sjd.nSjDId ".$extraCheck_op_sj_special.")

                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,'' as credit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,'' as vNumber,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND crd.vTable='V' ".$extraCheck_op_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,'' as credit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,'' as vNumber,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount,'' as subCode FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vTable='V' ".$extraCheck_op_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,'' as credit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,'' as vNumber,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount,'' as subCode FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vTable='V' ".$extraCheck_op_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,'' as credit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,'' as vNumber,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount,'' as subCode FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vTable='V' ".$extraCheck_op_bp.")								  
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,'' as vNumber,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vTable='V' ".$extraCheck_op_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,'' as vNumber,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vTable='V' ".$extraCheck_op_cv.")
                                  
								  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,'' as vNumber,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vTable='V' ".$extraCheck_op_dv.")
								  
								 
                                  
								  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.vSubCode as account,'V'as vTable,ac.nAcCode as subacc,acd.nAmount as debit,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,'' as vNumber,ac.vTransMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1'  ".$extraCheck_op_ac.")
								 
								 UNION ALL (SELECT op.dDate as date,op.vLocation as location,''  as document,'' as
                                  description, opd.vAcSubCode as account,'V' as vTable,opd.vACode as subacc,SUM(opd.vBalDebit) as debit,SUM(opd.vBalCredit) as credit,
                                  '' as issued,op.nOBId as tableId,'OP' as tblName,'' as vNumber,'' as mode,opd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}OpeningBalance op LEFT JOIN {PRE}OpeningBalanceDetails opd ON
                                  opd.nBalId = op.nOBId WHERE op.vActive='1' AND op.vSubLedger='V' ".$extraCheck_op_op." GROUP BY opd.vAcSubCode)
							
							
								  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.vSubCode as account,'V'as vTable,ad.nAcCode as subacc,ade.nAmount as debit,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,'' as vNumber,ad.vTransMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount,'' as subCode FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_op_ad." ORDER BY account, subacc,vTable, document )
                                  
								  
								  ORDER BY subacc ASC";
								  
								  
		//print $adminGroupListQuery;
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
		 (SELECT distinct  sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
		 '' as account,'' as vTable,'' as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
		 '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,'' as detId,'' as mainMode, 
		 '' as mainAmount,'' as mainAccount,	   scd.vType as tktType, scd.vTktNumber as vNumber,
		 CASE WHEN scd.vType = 'Tkt' THEN ( SELECT nGDS FROM tbl_TicketBooking WHERE scd.vTktNumber = nTId )
		 WHEN scd.vType = 'Xo' THEN ( SELECT nGDS FROM tbl_XoBooking WHERE scd.vTktNumber = nXId )
		 ELSE '' END as gds		           FROM tbl_SalesClosing sc 	   	   
		 LEFT JOIN tbl_SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
		 LEFT JOIN tbl_SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 	   
		 WHERE           sc.vActive = '1' ".$extraCheck_sc.")

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
                
		$id =  $row->tktType;// ."-".$row->location;
		
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
    function getUnpostReport($extraCheck_tb,$extraCheck_xo,$extraCheck_hv)
    {
        session_start();

		         $adminGroupListQuery = "(SELECT tb.nLocation,tb.dDate,tfd.vFaceValue as Fare,tfd.vTaxTotal as totTax,
                            tfd.nNetVendor as netAmt,tfd.nCommAmt as Comm,tb.nTId as tktId,'Tkt' as tktType,
                            tb.nAirline,tb.vTicktManual,tb.vPassenger,tb.vIssuedBy as vIssuedBy FROM tbl_TicketBooking tb
                            LEFT JOIN tbl_TicketDetails td ON tb.nTId = td.nTicketId LEFT JOIN
                            tbl_TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.clstatus='OPEN' AND tb.vType NOT IN('V','C') AND tfd.nNetVendor != '0' ".$extraCheck_tb." )

                            UNION ALL

                            (SELECT hv.nLocation,hv.dDate,hd.vTotalInFc as Fare,'' as totTax,hv.vTotalDC as netAmt,
                            '' as Comm,hv.nHVId as tktId,'Htl' as tktType,'' as nAirline,hv.vTicktManual,
                            hv.vPassenger,hv.vIssuedBy as vIssuedBy FROM tbl_HotelVoucher hv LEFT JOIN tbl_HotelDetails hd ON
                            hv.nHVId = hd.nHotelId WHERE hv.clstatus='OPEN'  AND hv.vTotalDC != '0' ".$extraCheck_hv.")

                            UNION ALL

                            (SELECT xo.nLocation,xo.dDate,xo.vGrandFare as Fare,xo.vGrandToalTax as totTax,
                            xo.vGrandNetFare as netAmt,xpd.nORCAmt as Comm,xo.nXId as tktId,'Xo' as tktType,
                            xo.nAirline,xo.vTicktManual,xpd.vPass as vPassenger,xo.vIssuedBy as vIssuedBy FROM
                            tbl_XoBooking xo LEFT JOIN tbl_XoDetails xd
                            ON xo.nXId = xd.nXoId LEFT JOIN tbl_XoPassDetails xpd ON
                            xpd.nXoId = xo.nXId WHERE xo.clstatus='OPEN' AND xo.vGrandNetFare != '0' ".$extraCheck_xo." GROUP BY xo.nXId) ";

        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->tktType."-".$row->location."-".$row->tktId;
		
		$adminGroupRowSummary[$id]['tktType'] = $row->tktType;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] = $row->netAmt;
		$adminGroupRowSummary[$id]['tktno'] = $row->vTicktManual;
		


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
	       	'' as account,'' as vTable,'' as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,'' as detId,'' as mainMode,
           '' as mainAmount,'' as mainAccount,
	   scd.vType as tktType, scd.vTktNumber as vNumber
           FROM {PRE}SalesClosing sc 
	   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 
	   LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCID = scd.nSalesCloseId
	   WHERE
           sc.vActive = '1' AND scd.vType != 'Htl'  ".$extraCheck_sc.")

          UNION ALL (SELECT rc.dDate,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks , rcd.nAirline, rcd.nDrAcc as account,rcd.vTable,CASE
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
			$i=1;
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
                
		$id =  $row->nAirline;//."-". $row->location;
		
		$adminGroupRowSummary[$id]['airline'] = $row->nAirline;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['counts'] += $i;
		//$adminGroupRowSummary[$id]['countsr'] += $row->netAmt;
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
		(SELECT DISTINCT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks,scd.vCollected as salesAmt,		0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,'' as detId,'' as mainMode,
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
              rcd.vType as tktType, rcd.nTicketNumber as vNumber, rrd.nHotel as vHotelName
	      FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
	      LEFT JOIN {PRE}RefundRegisterDetails rrd ON  rrd.vTktType = 'Htl' AND rrd.nTktNum = rcd.nTicketNumber AND rrd.vNewOld = 'O'
	      WHERE
	      rc.vActive = '1' AND  rcd.vType = 'Htl' AND rrd.vNewOld = 'O'  ".$extraCheck_rc.")
		  UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks ,  0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber, hv.vHotelName
	      FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
	      LEFT JOIN {PRE}HotelVoucher hv ON  rcd.vType = 'Htl' AND rcd.nTicketNumber =  hv.nHVId
	      WHERE
	      rc.vActive = '1' AND  rcd.vType = 'Htl' ".$extraCheck_rc.")
		  
	      ORDER BY vHotelName, document
	      ";
		   /*UNION ALL (SELECT rc.dDate ,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks ,  0 as salesAmt, rcd.vPaidAmt as PAIDREF, 0 as netAmt, rcd.vNetAmt as NETREF,
              '' as issued,rc.nRCId as tableId,'RC' as tblName,'Cr' as mode,rcd.nId as detId,'' as mainMode,
	      '' as mainAmount,'' as mainAccount,
              rcd.vType as tktType, rcd.nTicketNumber as vNumber, hv.vHotelName
	      FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId
	      LEFT JOIN {PRE}HotelVoucher hv ON  rcd.vType = 'Htl' AND rcd.nTicketNumber =  hv.nHVId
	      WHERE
	      rc.vActive = '1' AND  rcd.vType = 'Htl' AND  rrd.vNewOld = 'N' ".$extraCheck_rc.")*/

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
		 (SELECT DISTINCT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	'' as account,'' as vTable,'' as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,'' as detId,'' as mainMode,
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
		 (SELECT DISTINCT sc.dDate,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks, scd.nAirline, 
	       	'' as account,'' as vTable,'' as subacc,scd.vCollected as salesAmt, 0 as PAIDREF, scd.vNetAmt as netAmt, 0 as NETREF,
           '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,'' as detId,'' as mainMode,
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
                
		$id =  $row->gds;//."-". $row->location;
		
		$adminGroupRowSummary[$id]['gds'] = $row->gds;
		$adminGroupRowSummary[$id]['location'] = $row->location;
		//$adminGroupRowSummary[$id]['counts'] += $row->counts;
		$adminGroupRowSummary[$id]['netAmt'] += $row->netAmt;
		$adminGroupRowSummary[$id]['salesAmt'] += $row->salesAmt;
		$adminGroupRowSummary[$id]['NETREF'] += $row->NETREF;
		//$adminGroupRowSummary[$id]['countsr'] += $row->countsr;
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

    function getPayableDebitCreditValues($SubAccount,$open_value)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();
if(!empty($open_value)){
        foreach($open_value as $key=>$item):

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
                if($item->subacc == 91 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 101 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 111 && trim($item->account) == ''):
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
}
        return ($valArray);
    }//end function

    function getDebitCreditValues($SubAccount,$list_value,$acId)
    {
		//echo $SubAccount."---".$list_value['date']."---".$acId."<br>";
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
	
	 function getOpeningBalForAcc($account,$subacc,$table)
    {

      $this->ddlSubAccount = $subacc;
      $this->ddlAccount = $account;
      $this->tableType = $table;

echo $this->ddlSubAccount."==". $this->ddlAccount."==".$this->tableType;
		if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

      

        if ($this->txtFromDate != '') {
            $extraCheck_cr .= " AND cr.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_cp .= " AND cp.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_bb .= " AND bb.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_bp .= " AND bp.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_jv .= " AND jv.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_cv .= " AND cv.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_dv .= " AND dv.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_ac .= " AND ac.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_ad .= " AND ad.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_sc .= " AND sc.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_rc .= " AND rc.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
            $extraCheck_sj .= " AND sj.dDate < '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }//end if

   


        

        if (!empty($this->ddlAccount)) {
            if ($this->ddlAccount == 91 || $this->ddlAccount == 101 || $this->ddlAccount == 111):
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    //$drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
                $closeId        = $this->ddlAccount.'-V';
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = 'V'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if
  
    $data = $this->manage_info_model->getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj,$extraCheck_sj_special);
		$opening_balance['debit'] = 0;
        $opening_balance['credit'] = 0;
        foreach ($data as $key => $item){
          $opening_balance['debit'] += $item->debit;
          $opening_balance['credit'] += $item->credit;
        }
       


        return ($opening_balance);

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

