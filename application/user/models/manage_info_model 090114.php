<?php
class Manage_info_model extends CI_Model {

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
        $agentGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline WHERE vActive = '1' ORDER BY nArId DESC");
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

    function getProfitEmployeeReport($extraCheck)
    {
        session_start();

        $adminGroupListQuery1  = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck."
                                 ORDER BY scd.vEmployee DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' ".$extraCheck." GROUP BY
                                 scd.vEmployee ORDER BY scd.vEmployee DESC";

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

    function getProfitCustomerReport($extraCheck)
    {
        session_start();
        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,sad.*,sc.vTotalNetAmount as netAmt,sc.vTotalCollected as salesAmt FROM
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

    function getProfitByVendorReport($extraCheck)
    {
        session_start();

        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,sad.*,sc.vTotalNetAmount as netAmt,sc.vTotalCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND sad.vTable='V' ".$extraCheck."
                                 ORDER BY sad.nDrAccount DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,sad.*,sc.vTotalNetAmount as netAmt,sc.vTotalCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND sad.vTable='V' ".$extraCheck." GROUP BY
                                 sad.nDrAccount ORDER BY sc.dDate DESC";
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
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY subacc DESC";
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
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vTable='C' ".$extraCheck_ad.")
                                  ORDER BY subacc DESC";

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

    function getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc)
    {
        session_start();

        $adminGroupListQuery  = "(SELECT sc.dDate as date,sc.vLocation as location,sc.vInvoiceNumber as document,sc.vRemarks as
                                  description,sad.nDrAccount as account,sad.vTable,sad.nAcHead as subacc,sad.vAmt as debit,
                                  '' as issued,sc.nSCId as tableId,'SJ' as tblName,'Dr' as mode,sad.nId as detId,'' as mainMode,
                                  '' as mainAmount,'' as mainAccount FROM
                                  {PRE}SalesClosing sc LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId WHERE
                                  sc.vActive = '1' AND sad.vTable='V' ".$extraCheck_sc.")
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
                                  rc.vActive = '1' AND rcd.vTable='V' ".$extraCheck_rc.")
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,crd.nAmount as debit,
                                  cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,cr.nAcCode as mainAccount FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND crd.vTable='V' ".$extraCheck_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,cpd.nAmount as debit,
                                  cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,cp.nAcCode as mainAccount FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vTable='V' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,bbd.nAmount as debit,
                                  bb.vEnteredBy as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,bb.nAcCode as mainAccount FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vTable='V' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,bpd.nAmount as debit,
                                  bp.vEnteredBy as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,bp.nAcCode as mainAccount FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vTable='V' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,jvd.nAmount as debit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vTable='V' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,cvd.nAmount as debit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cvd.vTable='V' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,dvd.nAmount as debit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dvd.vTable='V' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,acd.nAmount as debit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' AND acd.vTable='V' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,ade.nAmount as debit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,
                                  '' as mainMode,'' as mainAmount,'' as mainAccount FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' AND ade.vTable='V' ".$extraCheck_ad.")
                                  ORDER BY subacc DESC";

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

    function getSaleSummaryReport($extraCheck)
    {
        session_start();
        $adminGroupListQuery1  = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
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

    function getAirlineSummaryReport($extraCheck)
    {
        session_start();

        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1'  AND
                                 (scd.vType='Tkt' OR scd.vType='Tkt') ".$extraCheck."
                                 ORDER BY scd.nAirline DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1'  AND
                                 (scd.vType='Tkt' OR scd.vType='Tkt') ".$extraCheck." GROUP BY
                                 scd.nAirline ORDER BY sc.dDate DESC";
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

    function getHotelSummaryReport($extraCheck)
    {
        session_start();

        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,scd.vNetAmt as netAmt,scd.vCollected as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Htl'
                                 ".$extraCheck." ORDER BY scd.vTktNumber DESC";
        $_SESSION['detail_query'] = $adminGroupListQuery1;

        $adminGroupListQuery  = "SELECT DISTINCT sc.*,scd.*,SUM(scd.vNetAmt) as netAmt,SUM(scd.vCollected) as salesAmt FROM
                                 {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
                                 ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive = '1' AND scd.vType='Htl'
                                 ".$extraCheck." GROUP BY scd.vTktNumber ORDER BY sc.dDate DESC";
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

    function getLocationSummaryReport($extraCheck)
    {
        session_start();

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

    function getProfitGdsReport($extraCheck)
    {
        session_start();

        $adminGroupListQuery1 = "SELECT DISTINCT sc.*,scd.*,CASE WHEN scd.vType='Tkt' THEN
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
    }//end function

    //calculate next debit
    function getCalDebit($deb,$cred)
    {
        $deb2 = ($this->session->userdata('newDebit') == '')?0:$this->session->userdata('newDebit');
        $deb2 = $deb2 + ($deb - $cred);

        $this->session->set_userdata('newDebit',$deb2);

        return ($deb2);
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

    function getRefundEmployeeReport($employee)
    {
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
                        $adminGroupRow['NETREF'] = $row->NETREF;
                        $adminGroupRow['PAIDREF'] = $row->PAIDREF;
                    }//end for each
                } //end if
            }//end for each
        } //end if


        return ($adminGroupRow);
    }//end function

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

    function returnTktDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$sId."'");
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

    function returnPassengerDetails($sId)
    {
        $salesClosePassenger = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId ='".$sId."'");
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

    function getDebitCreditValues($SubAccount,$list_value)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        $this->session->unset_userdata('newDebit');
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

            $mainAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->mainAccount."'");

            switch($item->mainMode)
            {
                case "Dr":
                    $debit += $item->mainAmount;
                    break;

                case "Cr":
                    $credit += $item->mainAmount;
                    break;

                default:
                    $debit = $debit;
                    $credit = $credit;
                    break;
            }//end switch

            if($SubAccountCode == $SubAccount || $mainAccountCode==$SubAccount):
                $cnt++;
                $wcredit += $credit;
                $wdebit  += $debit;
            endif;
        endforeach;

        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;

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

}//end class
?>