<?php
class Ageing_report_model extends CI_Model {

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

    function getActiveCustomer()
    {
        $empGroup    = $this->db->query("SELECT nCId,vCode,vName FROM {PRE}CustomerMaster  WHERE vActive = '1' ORDER BY nCId DESC");
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

    function getAccountGroup()
    {
        $typeGroup    = $this->db->query("SELECT nId,vTitle,vCode FROM {PRE}FinanceModule WHERE nParent!='0' AND vLevel='2'
                                                    AND vActive = '1' AND nParent IN (3,7) ORDER BY vTitle ASC");
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
        /*$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' ORDER BY nId DESC");*/

        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' OR ahc.vSubLedgerType = 'C'
                                              OR ahc.vSubLedgerType = 'V' OR ahc.vSubLedgerType = 'O' ORDER BY nId DESC");

        if ($typeGroup->num_rows() > 0)
        {
           // $typeGroupRow[0] = SELECT;
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

    function getActiveSubAccounts()
    {
        $custGroup 	  = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                WHERE ah.vActive = '1' AND(ahc.vSubSale = 'C' AND vSubLedger = 'S' OR
                                                ahc.vSubSale = 'B' AND vSubLedger = 'S') ORDER BY nId DESC");
        $custGroupRow = array();

        if ($custGroup->num_rows() > 0)
        {
            $custGroupRow[0] = SELECT;
            foreach ($custGroup->result() as $key => $row)
            {
                $custGroupRow[$row->ahaId] = (stripslashes($row->vTitle));
            }//end for each
        }//end if
        else
        {
            $custGroupRow[0] = NOVALUE;
        }//end else
        return ($custGroupRow);
    }//end function

    function getActiveEmployee()
    {
        $empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY nEId DESC");
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

    function mainReportArray()
    {
        $mainReportArrayList = array("Detail" => "Detail","Summary" => "Summary","Outstanding" => "Outstanding");
        return ($mainReportArrayList);
    }//end function

    function filterAllocation($ddlAccount,$ddlSubAccount,$type,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupRow    = array();
        if($ddlAccount>0 && $ddlAccount!=''):
            $extraCondition = " AND nDrAccount = '".$ddlAccount."' AND vTable = '".$type."'";
            $extra_tkt = " AND tfd.nAcc='".$ddlAccount."'";
            $extra_htl = " AND hv.nAcCode='".$ddlAccount."'";
            $extra_xo  = " AND xo.nPassengerAC='".$ddlAccount."'";
        else:
            $extraCondition = $this->getAccountTypeId($ddlSubAccount);
            $extra_tkt = '';
            $extra_xo  = '';
            $extra_htl = '';
        endif;

        if($ddlSubAccount == 32):
            $adminGroupListQuery   = "(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,'A' as vTable,sc.nSCId as tableId,
                                        sc.vInvoiceNumber as document,scd.nId as detId,
                                        sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.vNumber as vNumber,scd.vCollected as debit,'' as description,
                                        CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END)) ELSE sjd.nCredit END AS allocAmt ,
                                        CASE WHEN sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END) > 0 THEN 1 ELSE 0 END AS balCheck
                                            FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                            LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                            LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                            LEFT JOIN {PRE}TicketFareDetails tfd ON sjd.vNumber = tfd.nTicketId
                                        LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                            WHERE sjd.nAccHeadId = '".$ddlSubAccount."' AND sjd.nAGId = '18' ".$extra_tkt."
                                            AND sjd.nCredit != 0  ".$extraCheck_sj."
                                        GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                            {PRE}CashAllocation)))
                                            UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
                                      cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
                                            crd.vACode as account,crd.vAcSubCode as subacc,'' as vNumber,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                            ".$extraCheck_cr." AND cr.nCRId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CR'))
                                      UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
                                      cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,cpd.vAcSubCode as subacc,'' as vNumber,
                                            cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
                                            {PRE}CashPayment cp LEFT
                                      JOIN {PRE}CashPaymentDetails cpd ON
                                            cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."
                                            AND cp.nCPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CP'))
                                      UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
                                      bb.vDocNumber as document, bbd.nId as detId,
                                      bbd.vACode as account,bbd.vAcSubCode as subacc,'' as vNumber,
                                            bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBook bb LEFT
                                      JOIN {PRE}BankBookDetails bbd ON
                                            bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb."
                                            AND bb.nBBId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BR'))
                                      UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
                                      bp.vDocNumber as document,bpd.nId as detId,
                                      bpd.vACode as account,bpd.vAcSubCode as subacc,'' as vNumber,
                                            bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBookPayment
                                      bp LEFT JOIN {PRE}BankBookPaymentDetails
                                            bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp."
                                            AND bp.nBPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP'))
                                      UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
                                      jv.vDocNumber as document,jvd.nId as detId,
                                      jvd.vACode as account,jvd.vAcSubCode as subacc,'' as vNumber,
                                            jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
                                      FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                            WHERE jv.vActive='1' ".$extraCheck_jv."
                                            AND jv.nJVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV'))
                                      UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
                                      cv.vDocNumber as document,cvd.nId as detId,
                                      cvd.vACode as account,cvd.vAcSubCode as subacc,'' as vNumber,
                                            cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                            ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."
                                            AND cv.nCVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CV'))
                                      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
                                      dv.vDocNumber as document,dvd.nId as detId,
                                      dvd.vACode as account,dvd.vAcSubCode as subacc,'' as vNumber,
                                            dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                            dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."
                                            AND dv.nDVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'DV'))
                                      UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
                                      ac.vDocNumber as document,acd.nId as detId,
                                      acd.vACode as account,acd.vAcSubCode as subacc,'' as vNumber,
                                            acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                            acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                      UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
                                      ad.vDocNumber as document,ade.nId as detId,
                                      ade.vACode as account,ade.vAcSubCode as subacc,'' as vNumber,
                                            ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                      ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account DESC";
        elseif($ddlSubAccount == 34):
            $adminGroupListQuery   = "(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,'A' as vTable,sc.nSCId as tableId,
                                        sc.vInvoiceNumber as document,scd.nId as detId,
                                        sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.vNumber as vNumber,scd.vCollected as debit,'' as description,
                                        CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END)) ELSE sjd.nCredit END AS allocAmt ,
                                        CASE WHEN sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END) > 0 THEN 1 ELSE 0 END AS balCheck
                                            FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                            LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                            LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                            LEFT JOIN {PRE}HotelVoucher hv ON sjd.vNumber = hv.nHVId
                                        LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                            WHERE sjd.nAccHeadId = '".$ddlSubAccount."' AND sjd.nAGId = '18' ".$extra_htl."
                                            AND sjd.nCredit != 0  ".$extraCheck_sj."
                                        GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                            {PRE}CashAllocation)))
                                            UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
                                      cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
                                            crd.vACode as account,crd.vAcSubCode as subacc,'' as vNumber,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                            ".$extraCheck_cr." AND cr.nCRId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CR'))
                                      UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
                                      cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,cpd.vAcSubCode as subacc,'' as vNumber,
                                            cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
                                            {PRE}CashPayment cp LEFT
                                      JOIN {PRE}CashPaymentDetails cpd ON
                                            cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."
                                            AND cp.nCPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CP'))
                                      UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
                                      bb.vDocNumber as document, bbd.nId as detId,
                                      bbd.vACode as account,bbd.vAcSubCode as subacc,'' as vNumber,
                                            bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBook bb LEFT
                                      JOIN {PRE}BankBookDetails bbd ON
                                            bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb."
                                            AND bb.nBBId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BR'))
                                      UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
                                      bp.vDocNumber as document,bpd.nId as detId,
                                      bpd.vACode as account,bpd.vAcSubCode as subacc,'' as vNumber,
                                            bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBookPayment
                                      bp LEFT JOIN {PRE}BankBookPaymentDetails
                                            bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp."
                                            AND bp.nBPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP'))
                                      UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
                                      jv.vDocNumber as document,jvd.nId as detId,
                                      jvd.vACode as account,jvd.vAcSubCode as subacc,'' as vNumber,
                                            jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
                                      FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                            WHERE jv.vActive='1' ".$extraCheck_jv."
                                            AND jv.nJVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV'))
                                      UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
                                      cv.vDocNumber as document,cvd.nId as detId,
                                      cvd.vACode as account,cvd.vAcSubCode as subacc,'' as vNumber,
                                            cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                            ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."
                                            AND cv.nCVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CV'))
                                      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
                                      dv.vDocNumber as document,dvd.nId as detId,
                                      dvd.vACode as account,dvd.vAcSubCode as subacc,'' as vNumber,
                                            dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                            dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."
                                            AND dv.nDVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'DV'))
                                      UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
                                      ac.vDocNumber as document,acd.nId as detId,
                                      acd.vACode as account,acd.vAcSubCode as subacc,'' as vNumber,
                                            acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                            acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                      UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
                                      ad.vDocNumber as document,ade.nId as detId,
                                      ade.vACode as account,ade.vAcSubCode as subacc,'' as vNumber,
                                            ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                      ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account DESC";
        else:
            $adminGroupListQuery   = "(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,'A' as vTable,sc.nSCId as tableId,
                                        sc.vInvoiceNumber as document,scd.nId as detId,
                                        sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.vNumber as vNumber,scd.vCollected as debit,'' as description,
                                        CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END)) ELSE sjd.nCredit END AS allocAmt ,
                                        CASE WHEN sjd.nCredit - (CASE WHEN
                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") is null THEN 0
                                            ELSE
                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                        AND nSalesCloseDetId = scd.nId
                                                ".$extraCondition.") END) > 0 THEN 1 ELSE 0 END AS balCheck
                                            FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                            LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                            LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)
                                            LEFT JOIN {PRE}XoBooking xo ON sjd.vNumber = xo.nXId
                                        LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                            WHERE sjd.nAccHeadId = '".$ddlSubAccount."' AND sjd.nAGId = '18' ".$extra_xo."
                                            AND sjd.nCredit != 0  ".$extraCheck_sj."
                                        GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                            {PRE}CashAllocation)))
                                            UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
                                      cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
                                            crd.vACode as account,crd.vAcSubCode as subacc,'' as vNumber,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                            ".$extraCheck_cr." AND cr.nCRId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CR'))
                                      UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
                                      cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,cpd.vAcSubCode as subacc,'' as vNumber,
                                            cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
                                            {PRE}CashPayment cp LEFT
                                      JOIN {PRE}CashPaymentDetails cpd ON
                                            cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."
                                            AND cp.nCPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CP'))
                                      UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
                                      bb.vDocNumber as document, bbd.nId as detId,
                                      bbd.vACode as account,bbd.vAcSubCode as subacc,'' as vNumber,
                                            bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBook bb LEFT
                                      JOIN {PRE}BankBookDetails bbd ON
                                            bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb."
                                            AND bb.nBBId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BR'))
                                      UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
                                      bp.vDocNumber as document,bpd.nId as detId,
                                      bpd.vACode as account,bpd.vAcSubCode as subacc,'' as vNumber,
                                            bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBookPayment
                                      bp LEFT JOIN {PRE}BankBookPaymentDetails
                                            bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp."
                                            AND bp.nBPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP'))
                                      UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
                                      jv.vDocNumber as document,jvd.nId as detId,
                                      jvd.vACode as account,jvd.vAcSubCode as subacc,'' as vNumber,
                                            jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
                                      FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                            WHERE jv.vActive='1' ".$extraCheck_jv."
                                            AND jv.nJVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV'))
                                      UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
                                      cv.vDocNumber as document,cvd.nId as detId,
                                      cvd.vACode as account,cvd.vAcSubCode as subacc,'' as vNumber,
                                            cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                            ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."
                                            AND cv.nCVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CV'))
                                      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
                                      dv.vDocNumber as document,dvd.nId as detId,
                                      dvd.vACode as account,dvd.vAcSubCode as subacc,'' as vNumber,
                                            dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                            dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."
                                            AND dv.nDVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'DV'))
                                      UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
                                      ac.vDocNumber as document,acd.nId as detId,
                                      acd.vACode as account,acd.vAcSubCode as subacc,'' as vNumber,
                                            acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                            acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                      UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
                                      ad.vDocNumber as document,ade.nId as detId,
                                      ade.vACode as account,ade.vAcSubCode as subacc,'' as vNumber,
                                            ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                      ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account DESC";
        endif;

        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function filterAllocation2($ddlAccount,$ddlSubAccount,$type,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupRow    = array();
        if($ddlAccount>0 && $ddlAccount!=''):
            $adminGroupListQuery   = "(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,sad.vTable,sc.nSCId as tableId,
                                        sc.vInvoiceNumber as document,scd.nId as detId,
                                        sad.nDrAccount as account,scd.vCollected as debit,'' as description,
                                    CASE WHEN ca.vBalAmt > 0 THEN (scd.vCollected - (CASE WHEN
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$ddlAccount."' AND
                                            vTable = '".$type."') is null THEN 0
                                        ELSE
                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            AND nDrAccount = '".$ddlAccount."' AND
                                         vTable = '".$type."') END)) ELSE scd.vCollected END AS allocAmt,
                                    CASE WHEN scd.vCollected - (CASE WHEN
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                AND nSalesCloseDetId = scd.nId
                                        AND nDrAccount = '".$ddlAccount."' AND
                                            vTable = '".$type."') is null THEN 0
                                        ELSE
                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            AND nDrAccount = '".$ddlAccount."' AND
                                         vTable = '".$type."') END) > 0 THEN 1 ELSE 0 END AS balCheck
                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                        WHERE sad.nDrAccount = '".$ddlAccount."' AND sad.nAcHead = '".$ddlSubAccount."'  ".$extraCheck_sj."
                                    GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                        {PRE}CashAllocation)))
                                      UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
                                      cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
                                            crd.vACode as account,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                            ".$extraCheck_cr." AND cr.nCRId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CR'))
                                      UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
                                      cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,
                                            cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
                                            {PRE}CashPayment cp LEFT
                                      JOIN {PRE}CashPaymentDetails cpd ON
                                            cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."
                                            AND cp.nCPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CP'))
                                      UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
                                      bb.vDocNumber as document, bbd.nId as detId,
                                      bbd.vACode as account,
                                            bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBook bb LEFT
                                      JOIN {PRE}BankBookDetails bbd ON
                                            bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb."
                                            AND bb.nBBId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BR'))
                                      UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
                                      bp.vDocNumber as document,bpd.nId as detId,
                                      bpd.vACode as account,
                                            bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBookPayment
                                      bp LEFT JOIN {PRE}BankBookPaymentDetails
                                            bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp."
                                            AND bp.nBPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP'))
                                      UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
                                      jv.vDocNumber as document,jvd.nId as detId,
                                      jvd.vACode as account,
                                            jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
                                      FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                            WHERE jv.vActive='1' ".$extraCheck_jv."
                                            AND jv.nJVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV'))
                                      UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
                                      cv.vDocNumber as document,cvd.nId as detId,
                                      cvd.vACode as account,
                                            cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                            ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."
                                            AND cv.nCVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CV'))
                                      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
                                      dv.vDocNumber as document,dvd.nId as detId,
                                      dvd.vACode as account,
                                            dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                            dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."
                                            AND dv.nDVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'DV'))
                                      UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
                                      ac.vDocNumber as document,acd.nId as detId,
                                      acd.vACode as account,
                                            acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                            acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                      UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
                                      ad.vDocNumber as document,ade.nId as detId,
                                      ade.vACode as account,
                                            ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                      ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account DESC";
        else:
            if($ddlSubAccount>0 && $ddlSubAccount!=''):
                $extraCondition = $this->getAccountTypeId($ddlSubAccount);
            else:
                $extraCondition = '';
            endif;

            $adminGroupListQuery   = "(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,sad.vTable,sc.nSCId as tableId,
                                        sc.vInvoiceNumber as document,scd.nId as detId,
                                        sad.nDrAccount as account,scd.vCollected as debit,'' as description,
                                    CASE WHEN ca.vBalAmt > 0 THEN (scd.vCollected - (CASE WHEN
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                AND nSalesCloseDetId = scd.nId ".$extraCondition.") is null THEN 0
                                        ELSE
                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") END)) ELSE scd.vCollected END AS allocAmt,
                                    CASE WHEN scd.vCollected - (CASE WHEN
                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                AND nSalesCloseDetId = scd.nId
                                        ".$extraCondition.") is null THEN 0
                                        ELSE
                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId
                                                    AND nSalesCloseDetId = scd.nId
                                            ".$extraCondition.") END) > 0 THEN 1 ELSE 0 END AS balCheck
                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                        WHERE sad.nAcHead = '".$ddlSubAccount."'  ".$extraCheck_sj."
                                    GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                        {PRE}CashAllocation)))
                                      UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
                                      cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
                                            crd.vACode as account,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                            ".$extraCheck_cr." AND cr.nCRId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CR'))
                                      UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
                                      cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,
                                            cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
                                            {PRE}CashPayment cp LEFT
                                      JOIN {PRE}CashPaymentDetails cpd ON
                                            cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."
                                            AND cp.nCPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CP'))
                                      UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
                                      bb.vDocNumber as document, bbd.nId as detId,
                                      bbd.vACode as account,
                                            bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBook bb LEFT
                                      JOIN {PRE}BankBookDetails bbd ON
                                            bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb."
                                            AND bb.nBBId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BR'))
                                      UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
                                      bp.vDocNumber as document,bpd.nId as detId,
                                      bpd.vACode as account,
                                            bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM {PRE}BankBookPayment
                                      bp LEFT JOIN {PRE}BankBookPaymentDetails
                                            bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp."
                                            AND bp.nBPId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP'))
                                      UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
                                      jv.vDocNumber as document,jvd.nId as detId,
                                      jvd.vACode as account,
                                            jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
                                      FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                            WHERE jv.vActive='1' ".$extraCheck_jv."
                                            AND jv.nJVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV'))
                                      UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
                                      cv.vDocNumber as document,cvd.nId as detId,
                                      cvd.vACode as account,
                                            cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                            ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."
                                            AND cv.nCVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'CV'))
                                      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
                                      dv.vDocNumber as document,dvd.nId as detId,
                                      dvd.vACode as account,
                                            dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                            dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."
                                            AND dv.nDVId NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'DV'))
                                      UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
                                      ac.vDocNumber as document,acd.nId as detId,
                                      acd.vACode as account,
                                            acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                            acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                      UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
                                      ad.vDocNumber as document,ade.nId as detId,
                                      ade.vACode as account,
                                            ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
                                      {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                      ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY account DESC";
        endif;

        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function getAccountTypeId($accName)
    {
        $typeGroupRow    = array();
        $typeIdRow  = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nFId = '".$accName."' ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeIdRow[]    = $row->nCId;
                $typeGroupRow[] = "'C'";
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$accName."' ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $typeIdRow[]    = $row->nVId;
                $typeGroupRow[] = "'V'";
            }//end for each
        }//end if

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent WHERE nFId = '".$accName."' ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $typeIdRow[]    = $row->nAId;
                $typeGroupRow[] = "'AG'";
            }//end for each
        }//end if

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other WHERE nFId = '".$accName."' ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $typeIdRow[]    = $row->nOId;
                $typeGroupRow[] = "'O'";
            }//end for each
        }//end if

        $idValues = " AND nDrAccount IN(".@join(',',$typeIdRow).") AND vTable IN(".@join(',',$typeGroupRow).")";
        return ($idValues);
    }//end function

	//get date differece
	function getAgeingDateDiff($date)
	{
		$sqlDate = $this->db->query("SELECT DATEDIFF( '".date('Y-m-d')."', '".$date."' ) AS DiffDate");
		if($sqlDate->num_rows() > 0)
		{
			$dateDiff = $sqlDate->row();
			$dateDiff = $dateDiff->DiffDate;
		}//end if

		return ($dateDiff);
	}//end function

    function filterAccount($account)
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' AND ah.nAGroup='".$account."'  AND ahc.vSubLedgerType = 'A'
                                              OR ahc.vSubLedgerType = 'C'
                                              OR ahc.vSubLedgerType = 'V' OR ahc.vSubLedgerType = 'O' ORDER BY nId DESC");
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
                                              WHERE ah.vActive = '1'  AND ahc.vSubLedgerType = 'A' OR ahc.vSubLedgerType = 'C'
                                              OR ahc.vSubLedgerType = 'V' OR ahc.vSubLedgerType = 'O' ORDER BY nId DESC");
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



    function getTktNumberValues($vType,$vNumber)
    {
            $newArray['currency']	  =  "";
            $newArray['fc']  	 	  =  "";
            $newArray['exchangerate'] =  "";

			switch($vType)
			{
				case "Xo" :
          $tktnumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$vNumber."'");
          if($tktnumber == '') break;
					$newArray['conf']   = $this->common_model->get_field_name('XoBooking','vGdspnr'," WHERE nXId ='".$vNumber."'");
					$newArray['issued'] = $this->common_model->get_field_name('XoBooking','vIssuedBy'," WHERE nXId ='".$vNumber."'");
					$newArray['pass'] = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$vNumber."'");
					$newArray['desc']  = "XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");
					$airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$vNumber."'");
					$newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;
					break;

				case "Tkt" :
          $tktnumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$vNumber."'");
          if($tktnumber == '') break;
					$newArray['conf']   = $this->common_model->get_field_name('TicketBooking','vGdspnr'," WHERE nTId ='".$vNumber."'");
					$newArray['issued'] = $this->common_model->get_field_name('TicketBooking','vIssuedBy'," WHERE nTId ='".$vNumber."'");
					$newArray['pass'] = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$vNumber."'"); 
					$newArray['desc']  = "Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
					$airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$vNumber."'");
					$newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;
					break;

				case "Htl" :
          $tktnumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$vNumber."'");
          if($tktnumber == '') break;
					$cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$vNumber."'");
					$hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");
					$newArray['issued'] = $this->common_model->get_field_name('HotelVoucher','vIssuedBy'," WHERE nHVId='".$vNumber."'");							
					$newArray['conf']  = $this->common_model->get_field_name('HotelVoucher','vAgentConfId'," WHERE nHVId='".$vNumber."'");
					$newArray['fc'] = $this->common_model->get_field_name('HotelVoucher','vTotalFC'," WHERE nHVId='".$vNumber."'");
					$newArray['currency'] = $this->common_model->get_field_name('HotelVoucher','nCurrency'," WHERE nHVId='".$vNumber."'");
					$newArray['exchangerate'] = $this->common_model->get_field_name('HotelVoucher','nRate'," WHERE nHVId='".$vNumber."'");							
					$newArray['pass'] = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$vNumber."'");
					$newArray['desc']  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");
					$newArray['tktVal']  = $tktnumber; 
					break;
			}//end switch
      if($tktnumber == ''){ 
        $newArray['issued'] = $this->common_model->get_field_name('RefundRegisterDetails','vIssuedBy'," WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
//          $newArray['fc'] = $this->common_model->get_field_name('RefundRegisterDetails',
//          $newArray['currency'] = $this->common_model->get_field_name('RefundRegisterDetails',
//          $newArray['exchangerate'] = $this->common_model->get_field_name('RefundRegisterDetails',
        $newArray['pass'] = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
        $cityName       = $this->common_model->get_field_name('RefundRegisterDetails','nCity'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
        $hotel          = $this->common_model->get_field_name('RefundRegisterDetails','nHotel'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
        if($cityName != '0' || $hotel != '0'){
          $newArray['desc'] = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'");
        }
        else {
          $newArray['desc'] = $vType;
        }
        $newArray['tktVal'] =  $this->common_model->get_field_name('RefundRegisterDetails','vAirline', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'") ."-".$vNumber ;
      }
		return $newArray;
	} //end functions
	



/*
    function getTktNumberValues($detId)
    {
        $newArray = $valTktArray = $valDetArray = $valPassArray = array();
        $sjGroup       = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE nId='".$detId."' ORDER BY nId ASC");
        if ($sjGroup->num_rows() > 0)
        {
            foreach ($sjGroup->result() as $key => $row)
            {
                switch($row->vType)
                {
                    case "Xo" :
                        $detVal  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->vTktNumber."'");
                        if(!in_array($detVal,$valDetArray))
                            $valDetArray[] = $detVal;

                        $tktVal  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->vTktNumber."'");
                        if(!in_array($tktVal,$valTktArray))
                            $valTktArray[] = $tktVal;

                        $pass  = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$row->vTktNumber."'");
                        if(!in_array($pass,$valPassArray))
                            $valPassArray[] = $pass;

                        break;

                    case "Tkt" :
                        $detVal  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->vTktNumber."'");

                        if(!in_array($detVal,$valDetArray))
                            $valDetArray[] = $detVal;

                        $airline = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$row->vTktNumber."'");
                        $tktVal  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->vTktNumber."'");
                        if(!in_array($tktVal,$valTktArray))
                            $valTktArray[] = $tktVal;

                        $pass  = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$row->vTktNumber."'");
                        if(!in_array($pass,$valPassArray))
                            $valPassArray[] = $pass;

                        break;

                    case "Htl" :
                        $cityName= $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->vTktNumber."'");
                        $hotel   = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->vTktNumber."'");
                        $detVal  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->vTktNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->vTktNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->vTktNumber."'");
                        if(!in_array($detVal,$valDetArray))
                            $valDetArray[] = $detVal;

                        $tktVal  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->vTktNumber."'");
                        if(!in_array($tktVal,$valTktArray))
                            $valTktArray[] = $tktVal;

                        $pass  = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$row->vTktNumber."'");
                        if(!in_array($pass,$valPassArray))
                            $valPassArray[] = $pass;
                        break;
                }//end switch
            }//end for each

            $newArray['desc']    =  @join('<br>',$valDetArray);
            $newArray['tktVal']  =  @join('<br>',$valTktArray);
            $newArray['passVal'] =  @join('<br>',$valPassArray);
        }//end if

        return ($newArray);
    }//end function
*/
    function getAllocation($type,$tblName,$account,$vTable)
    {
        $adminGroupList   = $this->db->query("SELECT sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc
                                                LEFT JOIN {PRE}SalesClosingDetails
                                                scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                              sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON
                                              (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
                                                WHERE ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = '".$tblName."'
                                                 AND sad.nDrAccount = '".$account."'
                                              GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");
    }//end function

    function getSummaryCntCreditDebit($SubAccount,$list_value)
    {
        $date1  = '';
        $date2  = '';
        $date3  = '';
        $date4  = '';
        $date5  = '';
        $wdebit = '';

        $valArray = array();

        foreach($list_value as $key=>$item):

            switch($item->tblName)
            {
                case "CR":
                    $salesType   = 'Finance';
                    break;

                case "CP":
                    $salesType   = 'Finance';
                    break;

                case "BR":
                    $salesType   = 'Finance';
                    break;

                case "BP":
                    $salesType   = 'Finance';
                    break;

                case "JV":
                    $salesType   = 'Finance';
                    break;

                case "SJ":
                    $salesType   = 'Sales';
                    break;

                case "CV":
                    $salesType   = 'Finance';
                    break;

                case "DV":
                    $salesType   = 'Finance';
                    break;

                case "ACM":
                    $salesType   = 'Finance';
                    break;

                case "ADM":
                    $salesType   = 'Finance';
                    break;
            }//end switch

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

            if($SubAccountCode == $SubAccount):
                $datePerd   = $this->getAgeingDateDiff($item->date);
                if($salesType=='Sales'):
                    $date1 += ($datePerd <= 30)?$item->allocAmt:'';
                    $date2 += ($datePerd >= 31 && $datePerd <= 60)?$item->allocAmt:'';
                    $date3 += ($datePerd >= 61 && $datePerd <= 90)?$item->allocAmt:'';
                    $date4 += ($datePerd >= 91 && $datePerd <= 120)?$item->allocAmt:'';
                    $date5 += ($datePerd > 120)?$item->allocAmt:'';
                else:
                    $wdebit += $item->allocAmt;
                endif;
            endif;
        endforeach;

        $valArray['date1']  = $date1;
        $valArray['date2']  = $date2;
        $valArray['date3']  = $date3;
        $valArray['date4']  = $date4;
        $valArray['date5']  = $date5;
        $valArray['wdebit'] = $wdebit;

        return ($valArray);
    }//end function



//checks for opening balance
	function get_checks($checkType="Normal")
	{	
			$extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
			$extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
			$extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
			$extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
			$extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
			$extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
			$extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
        $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
			$extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
			$extraCheck_sj_s = " AND sj.nDivision='".$this->ddlDivision."'";


      $extraCheck_cr .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_cp .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_bb .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_bp .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_jv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_cv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_dv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_ac .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_ad .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_sj .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_sj_s .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

      if ($this->txtFromDate !=''  && $checkType!= "OB") {
        $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj_s .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      }

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

      if($this->txtFromDate !=''  && $checkType == "OB"){
        $extraCheck_cr = " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_cp = " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_bb = " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_bp = " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_jv = " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_cv = " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_dv = " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_sj = " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_sj_s = " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_ac = " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        $extraCheck_ad = " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
      }
      if ($this->txtToDate != '' && $checkType!= "OB") {
        $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_sj_s .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      }//end if

        $extraCheck_ac_top_acc .= $extraCheck_ac;
        $extraCheck_ad_top_acc .= $extraCheck_ad;

      //$extraCheck_sj .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      //$extraCheck_sj_s .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

      //		if (!empty($this->ddlAccount)) {
        if (!($this->ddlAccount == '0' || $this->ddlAccount == '') ) {

          $extraCheck_ac_top_acc .= " AND ac.nAcCode='".$this->ddlAccount."'";
          $extraCheck_ad_top_acc .= " AND ad.nAcCode='".$this->ddlAccount."'";


          $extraCheck_cr .= " AND crd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_cp .= " AND cpd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_bb .= " AND bbd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_bp .= " AND bpd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";
          $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vAcSubCode = '".$this->ddlAccount."', acd.vACode='".$this->ddlAccount."' )";
          $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vAcSubCode = '".$this->ddlAccount."', ade.vACode='".$this->ddlAccount."' )";

          $extraCheck_sj .= " HAVING vAcSubCode ='".$this->ddlAccount."'";
          $extraCheck_sj_s .= " HAVING vAcSubCode ='".$this->ddlAccount."'";

          //      if (!empty($this->ddlSubAccount)){
          if (!($this->ddlSubAccount == '' || $this->ddlSubAccount =='0') ){

            $extraCheck_ac_top_acc .= " AND ac.vSubCode = '".$this->ddlSubAccount ."' ";
            $extraCheck_ad_top_acc .= " AND ad.vSubCode = '".$this->ddlSubAccount ."' ";
            $extraCheck_cr .= " AND crd.vACode='". $this->ddlSubAccount ."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vACode = '".$this->ddlSubAccount ."' ,1 )";
            $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vACode = '".$this->ddlSubAccount ."' ,1 )";
            $extraCheck_sj .= " AND vACode = '".$this->ddlSubAccount."'";
            $extraCheck_sj_s .= " AND vACode = '".$this->ddlSubAccount."'";

          }
        }		
        return array("cr" => $extraCheck_cr, "cp" => $extraCheck_cp, "bb" => $extraCheck_bb, 
          "bp" => $extraCheck_bp, "jv" => $extraCheck_jv, "cv" => $extraCheck_cv, "dv" => $extraCheck_dv, 
          "sj" => $extraCheck_sj,"sj_s" => $extraCheck_sj_s,"ac"=>$extraCheck_ac,"ac_top_acc" => $extraCheck_ac_top_acc,
          "ad" => $extraCheck_ad, "ad_top_acc" => $extraCheck_ad_top_acc);

	} //end function



    function get_acc_list(){                               
      $list ='';
      $get_acc_list =  $this->db->query("SELECT nActHId FROM {PRE}AccountHeadControls WHERE vSubLedgerType != 'N'");
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

function get_columns_summary() 
	{  	
		$column['cr'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "',cr.dDate) as date, crd.nCurrency, IF(crd.vMode = 'Dr', crd.vFCAmount, 0) as vFCAmount,IF(crd.vMode = 'Cr', crd.vFCAmount, 0) as vFCAmount_credit,
              IF(crd.vMode = 'Dr', crd.nAmount , 0) as debit,IF(crd.vMode = 'Cr', crd.nAmount , 0) as credit,
						  crd.vACode as vACode, crd.vAcSubCode as vAcSubCode, crd.vTable as vTable ";
								  
		$column['cp'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', cp.dDate) as date, cpd.nCurrency, IF(cpd.vMode = 'Dr', cpd.vFCAmount, 0) as vFCAmount,IF(cpd.vMode = 'Cr', cpd.vFCAmount, 0) as vFCAmount_credit, 
              IF(cpd.vMode = 'Dr', cpd.nAmount , 0) as debit,IF(cpd.vMode = 'Cr', cpd.nAmount , 0) as credit ,
						  cpd.vACode as vACode, cpd.vAcSubCode as vAcSubCode, cpd.vTable as vTable ";
                                  
		$column['bb'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', bb.dDate) as date, bbd.nCurrency,  IF(bbd.vMode = 'Dr', bbd.vFCAmount, 0) as vFCAmount,IF(bbd.vMode = 'Cr', bbd.vFCAmount, 0) as vFCAmount_credit, 
              IF(bbd.vMode = 'Dr', bbd.nAmount , 0) as debit,IF(bbd.vMode = 'Cr', bbd.nAmount , 0) as credit ,
							bbd.vACode as vACode, bbd.vAcSubCode as vAcSubCode, bbd.vTable as vTable ";
						         
		$column['bp'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', bp.dDate) as date, bpd.nCurrency, IF(bpd.vMode = 'Dr', bpd.vFCAmount, 0) as vFCAmount,IF(bpd.vMode = 'Cr', bpd.vFCAmount, 0) as vFCAmount_credit,
               IF(bpd.vMode = 'Dr', bpd.nAmount , 0) as debit,IF(bpd.vMode = 'Cr', bpd.nAmount , 0) as credit ,
							bpd.vACode as vACode, bpd.vAcSubCode as vAcSubCode, bpd.vTable as vTable ";
                                  
		$column['jv'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', jv.dDate) as date, jvd.nCurrency,IF(jvd.vMode = 'Dr', jvd.vFCAmount, 0) as vFCAmount,IF(jvd.vMode = 'Cr', jvd.vFCAmount, 0) as vFCAmount_credit,
              IF(jvd.vMode = 'Dr', jvd.nAmount , 0) as debit,IF(jvd.vMode = 'Cr', jvd.nAmount , 0) as credit ,
							jvd.vACode as vACode, jvd.vAcSubCode as vAcSubCode, jvd.vTable as vTable ";
                          
		$column['cv'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', cv.dDate) as date, cvd.nCurrency, IF(cvd.vMode = 'Dr', cvd.vFCAmount, 0) as vFCAmount,IF(cvd.vMode = 'Cr', cvd.vFCAmount, 0) as vFCAmount_credit,
              IF(cvd.vMode = 'Dr', cvd.nAmount , 0) as debit,IF(cvd.vMode = 'Cr', cvd.nAmount , 0) as credit ,
							cvd.vACode as vACode, cvd.vAcSubCode as vAcSubCode, cvd.vTable as vTable ";
                                  
		$column['dv'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', dv.dDate) as date,  dvd.nCurrency, IF(dvd.vMode = 'Dr',dvd.vFCAmount, 0) as vFCAmount,IF(dvd.vMode = 'Cr', dvd.vFCAmount, 0) as vFCAmount_credit,
               IF(dvd.vMode = 'Dr', dvd.nAmount , 0) as debit,IF(dvd.vMode = 'Cr', dvd.nAmount , 0) as credit,
               dvd.vACode as vACode, dvd.vAcSubCode as vAcSubCode, dvd.vTable as vTable ";

    $column['ac'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', ac.dDate) as date, acd.nCurrency, IF( acd.vMode = 'Dr' ,acd.vFCAmount,0) as vFCAmount, IF( acd.vMode = 'Cr' , acd.vFCAmount,0) as vFCAmount_credit, 
      IF( acd.vMode = 'Dr' , acd.nAmount ,0)as debit, IF( acd.vMode = 'Cr' , acd.nAmount ,0)as credit,
IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as  vACode,
      IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as vAcSubCode, acd.vTable  as vTable ";

    $column['ac_top_acc'] =  " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', ac.dDate) as date, ac.nCurrency, IF( ac.vTransMode = 'Dr' , ac.vAmountFC,0) as vFCAmount, IF( ac.vTransMode = 'Cr', ac.vAmountFC,0) as vFCAmount_credit, 
      IF( ac.vTransMode = 'Dr' , ac.vAmountDC,0)as debit,
      IF( ac.vTransMode = 'Cr' , ac.vAmountDC,0)as credit,
      ac.vSubCode as  vACode,ac.nAcCode as vAcSubCode, 'V' as vTable ";

                                  
    $column['ad'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', ad.dDate) as date, ade.nCurrency, IF( ade.vMode = 'Dr' , ade.vFCAmount,0) as vFCAmount, IF( ade.vMode = 'Cr' ,ade.vFCAmount,0) as vFCAmount_credit,
      IF( ade.vMode = 'Dr' , ade.nAmount ,0)as debit,
      IF( ade.vMode = 'Cr' , ade.nAmount ,0)as credit,
      IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as  vACode,
      IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as  vAcSubCode,
      ade.vTable  as vTable ";

    $column['ad_top_acc'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', ad.dDate) as date, ad.nCurrency, IF( ad.vTransMode = 'Dr' ,ad.vAmountFC,0) as vFCAmount, IF( ad.vTransMode = 'Cr' ,ad.vAmountFC,0) as vFCAmount_credit,
      IF( ad.vTransMode = 'Dr' , ad.vAmountDC,0)as debit,
      IF( ad.vTransMode = 'Cr' , ad.vAmountDC,0)as credit,
      ad.vSubCode as vACode, ad.nAcCode as vAcSubCode, 'V' as vTable ";
 
    $list = $this->get_acc_list();

    $column['sjv'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', sj.dDate) as date, CASE WHEN sjd.vType = 'Htl' THEN (SELECT nCurrency FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END  as nCurrency,
      IF(sjd.nDebit != 0, CASE WHEN sjd.vType = 'Htl' THEN (SELECT vTotalFC FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END,0) as vFCAmount, 
      IF(sjd.nCredit !=0, CASE WHEN sjd.vType = 'Htl' THEN (SELECT vTotalFC FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END,0) as vFCAmount_credit,
      sjd.nDebit as debit, sjd.nCredit as credit,
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
                                          sjd.nDrAccount))as vACode,
                                  IF(sjd.nAccHeadId = 0, sjd.nDrAccount, sjd.nAccHeadId) as vAcSubCode,
                                  IF(sjd.vTable = '','V', sjd.vTable ) as vTable ";



		$column['sjv_s'] = " DATEDIFF('". date('Y-m-d',strtotime($this->txtToDate)). "', sj.dDate) as date, '' as nCurrency, '' as vFCAmount, '' as vFCAmount_credit,
IF( sj.vType = 'S', (SELECT scd.vAllocation FROM {PRE}SalesClosingDetails scd WHERE sjd.vType = scd.vType AND scd.vTktNumber = sjd.vNumber),0) as debit, 
IF( sj.vType = 'R',( SELECT rcd.vPaidAmt FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = sjd.vType AND rcd.nTicketNumber = sjd.vNumber),0) as credit,
                    IF(sjd_s.nAccHeadId NOT IN ". $list ." ,0, IF(sjd_s.nDrAccount = '',
                          IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                                WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                            ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                              LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                              WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O') ,
                            (CASE
                             WHEN sjd_s.vType = 'Tkt' THEN
                             (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                              nTicketId = sjd_s.vNumber)
                             WHEN sjd_s.vType = 'Htl' THEN
                             (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                              nHVId = sjd_s.vNumber)
                             ELSE
                             (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                              nXId = sjd_s.vNumber) END)),
                          sjd_s.nDrAccount))as  vACode,
                  IF(sjd_s.nAccHeadId = 0, sjd_s.nDrAccount, sjd_s.nAccHeadId) as vAcSubCode,
                  IF(sjd_s.vTable = '','V', sjd_s.vTable ) as vTable ";

//								sjd_s.nDrAccount as vACode, sjd_s.nAccHeadId as vAcSubCode, sjd_s.vTable as vTable ";
		 
		return 	$column;										
	
	}// end function
	


	
	function get_columns($column = array()) 
	{
		$column['cr'] .= " cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName, cr.vRefNumber, cr.vRefName, crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode, 
								  crd.nCurrency, crd.vFCAmount, crd.vExchange,
								  crd.vMode as mode,  
								  crd.vACode as vACode, crd.vAcSubCode as vAcSubCode, crd.vTable as vTable ";
								  
		$column['cp'] .= " cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as issued,cp.nCPId as tableId,'CP' as tblName, cp.vRefNumber, cp.vRefName,cpd.nId as detId,
								  '' as vType,'' as vNumber, cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode, 
								  cpd.nCurrency, cpd.vFCAmount, cpd.vExchange,
								  cpd.vMode as mode,  
								  cpd.vACode as vACode, cpd.vAcSubCode as vAcSubCode, cpd.vTable as vTable ";
                                  
		$column['bb'] .= " bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName, bb.vRefNumber, bb.vRefName,
								  bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode, 
								  bbd.nCurrency, bbd.vFCAmount, bbd.vExchange,
								  bbd.vMode as mode, 
								  bbd.vACode as vACode, bbd.vAcSubCode as vAcSubCode, bbd.vTable as vTable ";
                                  
		$column['bp'] .= " bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName, bp.vRefNumber, bp.vRefName,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode, 
								  bpd.nCurrency, bpd.vFCAmount, bpd.vExchange,
								  bpd.vMode as mode, 
								  bpd.vACode as vACode, bpd.vAcSubCode as vAcSubCode, bpd.vTable as vTable ";

		$column['jv'] .= " jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName, jv.vRefNumber, jv.vRefName,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode, 
								  jvd.nCurrency, jvd.vFCAmount, jvd.vExchange,
								  jvd.vMode as mode,
								  jvd.vACode as vACode, jvd.vAcSubCode as vAcSubCode, jvd.vTable as vTable ";
                                  
		$column['cv'] .= " cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName, cv.vRefNumber, cv.vRefName,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode, 
								  cvd.nCurrency, cvd.vFCAmount, cvd.vExchange,
								  cvd.vMode as mode,
								  cvd.vACode as vACode, cvd.vAcSubCode as vAcSubCode, cvd.vTable as vTable ";
                                  
		$column['dv'] .= " dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName, dv.vRefNumber, dv.vRefName,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode, 
								  dvd.nCurrency, dvd.vFCAmount, dvd.vExchange,
								  dvd.vMode as mode,
                  dvd.vACode as vACode, dvd.vAcSubCode as vAcSubCode, dvd.vTable as vTable ";

    $column['ac'] .= " ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description, 
      '' as account,'' as subacc, '' as balance,'' as credit, ac.vEnteredBy as issued, 
      ac.nACId as tableId, 'AC' as tblName, ac.vRefNumber,ac.vRefName,'' as detId, '' as vType,
      '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode,
      acd.nCurrency,acd.vFCAmount, acd.vExchange, acd.vMode as mode, 
      IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as  vACode,
      IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as vAcSubCode,
      acd.vTable  as vTable " ;

    $column['ac_top_acc'] .= "  ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
      '' as account,'' as subacc, '' as balance,'' as credit, ac.vEnteredBy as issued, 
      ac.nACId as tableId, 'AC' as tblName, ac.vRefNumber,ac.vRefName,'' as detId, '' as vType,
      '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode,
      ac.nCurrency,ac.vAmountFC, ac.vRate as vExchange,
      ac.vTransMode as mode, ac.vSubCode as  vACode ,  ac.nAcCode vAcSubCode, 'V' as vTable "; 

        $column['ad_top_acc'] .= " ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
      '' as account,'' as subacc, '' as balance,'' as credit, ad.vEnteredBy  as issued, 
      ad.nADId as tableId, 'AD' as tblName, ad.vRefNumber, ad.vRefName,'' as detId, '' as vType,
      '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode,
      ad.nCurrency, ad.vAmountFC, ad.vRate as vExchange,
      ad.vTransMode as mode, ad.vSubCode as vACode, ad.nAcCode vAcSubCode, 'V' as vTable ";

    $column['ad'] .= "  ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
      '' as account,'' as subacc, '' as balance,'' as credit, ad.vEnteredBy  as issued, 
      ad.nADId as tableId,'AD' as tblName,  ad.vRefNumber, ad.vRefName,'' as detId, '' as vType,
      '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode,
      ade.nCurrency,  ade.vFCAmount, ade.vExchange, ade.vMode  as mode, 
      IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as  vACode, 
      IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as  vAcSubCode,  
      ade.vTable ";





    $list = $this->get_acc_list();
		$column['sjv'] .= " sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName, '' as vRefNumber, '' as vRefName,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode, 
                                  '' as nCurrency, '' as vFCAmount, '' as vExchange,
                                  '' as mode,
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
                                          sjd.nDrAccount))as vACode,
                                  IF(sjd.nAccHeadId = 0, sjd.nDrAccount, sjd.nAccHeadId) as vAcSubCode, 
                                  IF(sjd.vTable = '','V', sjd.vTable ) as vTable ";




/*								 IF( sjd.vNumber !=0, CASE 
										WHEN sjd.vType = 'Tkt' THEN ( select nAcc FROM {PRE}TicketFareDetails  WHERE nTicketId = sjd.vNumber) 
										WHEN sjd.vType = 'Xo'  THEN ( select nPassengerAC FROM {PRE}XoBooking WHERE nXId = sjd.vNumber )
										WHEN sjd.vType = 'Htl' THEN ( select nAcCode FROM  {PRE}HotelVoucher WHERE nHVId = sjd.vNumber )
										END	,sjd.nDrAccount ) as vACode, sjd.nAccHeadId as vAcSubCode, 
								 (select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd.nAccHeadId) as vTable ";								  
*/
		$column['sjv_s'] .= " sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ_R' as tblName, '' as vRefNumber, '' as vRefName,sjd.nSjDId as detId,
								  sjd.vType as vType, sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                            nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode, 
                  '' as nCurrency, '' as vFCAmount, '' as vExchange,
                  '' as mode,
                    IF(sjd_s.nAccHeadId NOT IN ". $list ." ,0, IF(sjd_s.nDrAccount = '',
                          IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                                WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                            ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                              LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                              WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O') ,
                            (CASE
                             WHEN sjd_s.vType = 'Tkt' THEN
                             (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                              nTicketId = sjd_s.vNumber)
                             WHEN sjd_s.vType = 'Htl' THEN
                             (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                              nHVId = sjd_s.vNumber)
                             ELSE
                             (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                              nXId = sjd_s.vNumber) END)),
                          sjd_s.nDrAccount))as vACode,
                  IF(sjd_s.nAccHeadId = 0, sjd_s.nDrAccount, sjd_s.nAccHeadId) as vAcSubCode,
                  IF(sjd_s.vTable = '','V', sjd_s.vTable ) as vTable ";



//   								  sjd_s.nDrAccount as vACode, sjd_s.nAccHeadId as vAcSubCode, sjd_s.vTable as vTable ";								 

		return 	$column;										
	} //end function


		  
    function getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$ReportType)
    { 
      $list = $this->get_acc_list();
      if($ReportType == "Summary"){
        $column = $this->get_columns_summary();

        $column['cr'].=" , IFNULL( IF(crd.vMode = 'Dr', 
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CR' AND nReceiptDId = cr.nCRId GROUP BY cr.nCRId),0),0)  as alloc_debit,
          IFNULL( IF(crd.vMode = 'Cr',
                (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CR' AND nReceiptDId = cr.nCRId GROUP BY cr.nCRId),0),0)  as alloc_credit ";
        $column['cp'].=" , IFNULL(IF(cpd.vMode = 'Dr',
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CP' AND nReceiptDId = cp.nCPId GROUP BY cp.nCPId),0),0) as alloc_debit, 
          IFNULL(IF(cpd.vMode = 'Cr',
                (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CP' AND nReceiptDId = cp.nCPId GROUP BY cp.nCPId),0),0) as alloc_credit ";
        $column['bb'].=" ,  IFNULL(IF(bbd.vMode = 'Dr', 
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BR' AND nReceiptDId = bb.nBBId GROUP BY bb.nBBId),0),0) as alloc_debit
          , IFNULL(IF(bbd.vMode = 'Cr', 
                (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BR' AND nReceiptDId = bb.nBBId GROUP BY bb.nBBId),0),0) as alloc_credit ";
        $column['bp'].=" ,  IFNULL(IF(bpd.vMode = 'Dr',
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BP' AND nReceiptDId = bp.nBPId GROUP BY bp.nBPId),0),0) as alloc_debit
            , IFNULL(IF(bpd.vMode = 'Cr',
                  (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BP' AND nReceiptDId = bp.nBPId GROUP BY bp.nBPId),0),0) as alloc_credit ";
        $column['jv'].=", IFNULL(IF(jvd.vMode = 'Dr', 
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='JV' AND nReceiptDId = jv.nJVId GROUP BY jv.nJVId),0),0) as alloc_debit
          , IFNULL(IF(jvd.vMode = 'Cr', 
                (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='JV' AND nReceiptDId = jv.nJVId GROUP BY jv.nJVId),0),0) as alloc_credit ";
        $column['cv'].=" , IFNULL(IF(cvd.vMode = 'Dr',
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CV' AND nReceiptDId = cv.nCVId GROUP BY cv.nCVId),0),0) as alloc_debit
            , IFNULL(IF(cvd.vMode = 'Cr',
                  (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CV' AND nReceiptDId = cv.nCVId GROUP BY cv.nCVId),0),0) as alloc_credit ";
        $column['dv'] .= " , IFNULL(IF(dvd.vMode = 'Dr',
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='DV' AND nReceiptDId = dv.nDVId GROUP BY dv.nDVId),0),0) as alloc_debit
            , IFNULL(IF(dvd.vMode = 'Cr',
                  (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='DV' AND nReceiptDId = dv.nDVId GROUP BY dv.nDVId),0),0) as alloc_credit ";
 
        $column['ac'].=" , 0 as alloc_debit, '' as alloc_credit " ;
        $column['ac_top_acc'].=" , 0 as alloc_debit, '' as alloc_credit " ;
        $column['ad'].=" , 0 as alloc_debit, '' as alloc_credit " ;
        $column['ad_top_acc'].=" , 0 as alloc_debit, '' as alloc_credit " ;



// MIN(vBalAmt)
        $column['sjv'].= " ,IF(sjd.nDebit =0,0, ((SELECT SUM(vAllAmt) FROM {PRE}CashAllocation
                    WHERE nSalesCloseId = sj.nSalesCId
                    AND vAllTktNumber =
                    IFNULL(IF
                        (sjd.vType = 'Tkt',
                         (SELECT vTicktManual FROM {PRE}TicketBooking WHERE
                          nTId = sjd.vNumber),
                         IF( sjd.vType = 'Htl',
                           (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
                            nHVId = sjd.vNumber),
                           (SELECT vTicktManual FROM {PRE}XoBooking WHERE
                            nXId = sjd.vNumber))),sjd.vNumber)
                    AND vTable = IF(sjd.vTable = '','V', sjd.vTable )
                    AND nDrAccount =
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
                          sjd.nDrAccount))
                    GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount)))as alloc_debit, 
                          IF(sjd.nCredit=0,0, ((SELECT SUM(vAllAmt) FROM {PRE}CashAllocation
                    WHERE nSalesCloseId = sj.nSalesCId
                    AND vAllTktNumber =
                    IFNULL(IF
                        (sjd.vType = 'Tkt',
                         (SELECT vTicktManual FROM {PRE}TicketBooking WHERE
                          nTId = sjd.vNumber),
                         IF( sjd.vType = 'Htl',
                           (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
                            nHVId = sjd.vNumber),
                           (SELECT vTicktManual FROM {PRE}XoBooking WHERE
                            nXId = sjd.vNumber))),sjd.vNumber)
                    AND vTable = IF(sjd.vTable = '','V', sjd.vTable )
                    AND nDrAccount =
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
                          sjd.nDrAccount))
                    GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount)))as alloc_credit ";

        $column['sjv_s'].= " ,IF(sj.vType = 'S' , ((SELECT SUM(vAllAmt) FROM {PRE}CashAllocation
                    WHERE nSalesCloseId = sj.nSalesCId
                    AND vAllTktNumber =
                    IFNULL(IF
                        (sjd.vType = 'Tkt',
                         (SELECT vTicktManual FROM {PRE}TicketBooking WHERE
                          nTId = sjd.vNumber),
                         IF( sjd.vType = 'Htl',
                           (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
                            nHVId = sjd.vNumber),
                           (SELECT vTicktManual FROM {PRE}XoBooking WHERE
                            nXId = sjd.vNumber))),sjd.vNumber)
                    AND vTable = IF(sjd_s.vTable = '','V', sjd_s.vTable )
                    AND nDrAccount =
                    IF(sjd_s.nAccHeadId NOT IN ". $list ." ,0, IF(sjd_s.nDrAccount = '',
                          IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                                WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                            ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                              LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                              WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O') ,
                            (CASE
                             WHEN sjd_s.vType = 'Tkt' THEN
                             (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                              nTicketId = sjd_s.vNumber)
                             WHEN sjd_s.vType = 'Htl' THEN
                             (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                              nHVId = sjd_s.vNumber)
                             ELSE
                             (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                              nXId = sjd_s.vNumber) END)),
                          sjd_s.nDrAccount))
                    GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount)),0)as alloc_debit,
                          IF(sj.vType = 'R', ((SELECT SUM(vAllAmt) FROM {PRE}CashAllocation
                    WHERE nSalesCloseId = sj.nSalesCId
                    AND vAllTktNumber =
                    IFNULL(IF
                        (sjd.vType = 'Tkt',
                         (SELECT vTicktManual FROM {PRE}TicketBooking WHERE
                          nTId = sjd.vNumber),
                         IF( sjd.vType = 'Htl',
                           (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
                            nHVId = sjd.vNumber),
                           (SELECT vTicktManual FROM {PRE}XoBooking WHERE
                            nXId = sjd.vNumber))),sjd.vNumber)
                    AND vTable = IF(sjd_s.vTable = '','V', sjd_s.vTable )
                    AND nDrAccount =
                    IF(sjd_s.nAccHeadId NOT IN ". $list ." ,0, IF(sjd_s.nDrAccount = '',
                          IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                                WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                            ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                              LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                              WHERE sjd_s.vNumber= rrd.nTktNum AND sjd_s.vType = vTktType AND rrd.vNewOld = 'O') ,
                            (CASE
                             WHEN sjd_s.vType = 'Tkt' THEN
                             (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                              nTicketId = sjd_s.vNumber)
                             WHEN sjd_s.vType = 'Htl' THEN
                             (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                              nHVId = sjd_s.vNumber)
                             ELSE
                             (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                              nXId = sjd_s.vNumber) END)),
                          sjd_s.nDrAccount))
                    GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount)),0)as alloc_credit
 ";

          $outer_select = " SELECT SUM(IF(date <= " . $this->txtSlab1 . ",debit - IFNULL(alloc_debit,0),0)) as s1, 
                  SUM(IF( " . $this->txtSlab1 . " < date AND date <=  " . $this->txtSlab2 . ",debit - IFNULL(alloc_debit,0),0)) as s2,
                  SUM(IF( " . $this->txtSlab2 . " < date AND date <=  " . $this->txtSlab3 . ",debit - IFNULL(alloc_debit,0),0)) as s3,
                  SUM(IF( " . $this->txtSlab3 . " < date AND date <=  " . $this->txtSlab4 . ",debit - IFNULL(alloc_debit,0),0)) as s4,
				  SUM(IF( " . $this->txtSlab4 . " < date,debit - IFNULL(alloc_debit,0),0)) as s5,
                  SUM(debit - IFNULL(alloc_debit,0)) as alloc_debit, SUM(credit - IFNULL(alloc_credit,0)) as  alloc_credit, 
                  SUM(debit) as debit, SUM(credit) as credit, vACode, vAcSubCode, vTable FROM (";
          $group_by = " ) AS T GROUP BY vACode, vAcSubCode, vTable";
          $order_by = " (select vTitle from {PRE}AccountHeadAccounts WHERE nId='vACode'),vACode,vAcSubCode,date ";

      }
      else {

        $column['cr']=" 'O' as OutReport, crd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CR' AND nReceiptDId = cr.nCRId GROUP BY cr.nCRId),0)  as debit,";
        $column['cp']=" 'O' as OutReport, cpd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CP' AND nReceiptDId = cp.nCPId GROUP BY cp.nCPId),0) as debit, ";
        $column['bb']=" 'O' as OutReport, bbd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BR' AND nReceiptDId = bb.nBBId GROUP BY bb.nBBId),0) as debit, ";
        $column['bp']="'O' as OutReport, bpd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BP' AND nReceiptDId = bp.nBPId GROUP BY bp.nBPId),0) as debit, ";
        $column['jv']="'O' as OutReport, jvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='JV' AND nReceiptDId = jv.nJVId GROUP BY jv.nJVId),0) as debit, ";
        $column['cv']="'O' as OutReport, cvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CV' AND nReceiptDId = cv.nCVId GROUP BY cv.nCVId),0) as debit, ";
        $column['dv']="'O' as OutReport, dvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='DV' AND nReceiptDId = dv.nDVId GROUP BY dv.nDVId),0) as debit, ";
        $column['ac'] = " 'O' as OutReport, acd.nAmount as debit, ";
        $column['ac_top_acc'] = " 'O' as OutReport, ac.vAmountDC as debit, ";
        $column['ad'] = " 'O' as OutReport, ade.nAmount as debit, ";
        $column['ad_top_acc'] = " 'O' as OutReport, ad.vAmountDC as debit, ";


        $column['sjv']="'O' as OutReport, sjd.nDebit as debit, ";
        $column['sjv_s']= "'O' as OutReport, sjd.nDebit as debit, ";
        $column = $this->get_columns($column);
        $outer_select = "";
        $group_by = "";
        $order_by = " vAcSubCode, vACode, vTable , date, document ";
      }
		$adminGroupListQuery  =  "  " . $outer_select . "
			(SELECT " . $column['cr'] . "
			FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId 
			LEFT JOIN {PRE}Location loc ON loc.nId = cr.vLocation
			WHERE cr.vActive='1'
			".$extraCheck_cr.")
								  
			UNION ALL (SELECT " . $column['cp'] . "
			FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId 
			LEFT JOIN {PRE}Location loc ON loc.nId = cp.vLocation
			WHERE cp.vActive='1' ".$extraCheck_cp.")
								  
			UNION ALL (SELECT " . $column['bb'] . "
			FROM {PRE}BankBook bb LEFT
			JOIN {PRE}BankBookDetails bbd ON
			bbd.nBankbookId = bb.nBBId 
			LEFT JOIN {PRE}Location loc ON loc.nId = bb.vLocation
			WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  
			UNION ALL (SELECT " . $column['bp'] . "
			FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
			bpd ON bpd.nBankPaymentId = bp.nBPId 
			LEFT JOIN {PRE}Location loc ON loc.nId = bp.vLocation
			WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  
			UNION ALL (SELECT " . $column['jv'] . "
			FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
			LEFT JOIN {PRE}Location loc ON loc.nId = jv.vLocation					  
			WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  
			UNION ALL (SELECT " . $column['cv'] . "
			FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
			ON cvd.nCreditId = cv.nCVId 
			LEFT JOIN {PRE}Location loc ON loc.nId = cv.vLocation						  
			WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  
			UNION ALL (SELECT " . $column['dv'] . "
			FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
			dvd.nDebitId = dv.nDVId
			LEFT JOIN {PRE}Location loc ON loc.nId = dv.vLocation
			WHERE dv.vActive='1' ".$extraCheck_dv.") 
                            
      UNION ALL (SELECT ". $column['ac_top_acc']." FROM
      {PRE}AcmEntry ac 
      LEFT JOIN {PRE}Location loc ON loc.nId = ac.vLocation
      WHERE ac.vActive='1'
      ".$extraCheck_ac_top_acc." )

      UNION ALL
      (SELECT  ". $column['ac']."  FROM
      {PRE}AcmEntry ac 
      LEFT JOIN {PRE}Location loc ON loc.nId = ac.vLocation
      LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId WHERE ac.vActive='1'
      ".$extraCheck_ac." ) 

      UNION ALL (SELECT " . $column['ad_top_acc']." FROM
      {PRE}AdmEntry ad 
      LEFT JOIN {PRE}Location loc ON loc.nId = ad.vLocation
      WHERE ad.vActive='1'
      ".$extraCheck_ad_top_acc." )

      UNION ALL (SELECT  ". $column['ad']." FROM
      {PRE}AdmEntry ad 
      LEFT JOIN {PRE}Location loc ON loc.nId = ad.vLocation
      LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId WHERE ad.vActive='1'
      ".$extraCheck_ad." ) 

			UNION ALL ( SELECT " . $column['sjv'] . "	
			FROM {PRE}SystemJv sj
			LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId
			LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation
			WHERE  ( select vSubLedgerType from tbl_AccountHeadControls where nActHId = sjd.nAccHeadId ) LIKE 'V'
			".$extraCheck_sj." )
								  
			UNION ALL (SELECT " . $column['sjv_s'] . " 								  
			FROM {PRE}SystemJvDetails sjd 
			LEFT JOIN  {PRE}SystemJv sj ON sj.nId = sjd.nSjId 
			INNER JOIN {PRE}SystemJvDetails sjd_s ON sjd.nSjId = sjd_s.nSjId
			LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation
			WHERE ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd.nAccHeadId ) LIKE 'V'
			AND ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd_s.nAccHeadId ) LIKE 'C' 
			".$extraCheck_sj_s." )
			
		       	" . $group_by . 
	 	        " ORDER BY  " . $order_by . " " ;

        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;


//if($ReportType == "Summary"){
//echo $adminGroupListQuery . " <br><br><br> " ;
//return;
//}
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


    function getOpeningBalForAcc($account,$subacc,$table)
    {

      //echo $account  ." --- ". $subacc ." --- ".$table .  " <br><br><br> ";


      $this->ddlSubAccount = $subacc;
      $this->ddlAccount = $account;
      $this->tableType = $table;


      if( $this->ddlAccount == '0'){

        $temp =  $this->ddlAccount;
        $this->ddlAccount =  $this->ddlSubAccount;
        $this->ddlSubAccount = $temp;

      }

      $extra_checks = array();
      $check = $this->get_checks("OB");

      $extraCheck_cr = $check['cr'];
      $extraCheck_cp = $check['cp'];
      $extraCheck_bb = $check['bb'];
      $extraCheck_bp = $check['bp'];
      $extraCheck_jv = $check['jv'];
      $extraCheck_cv = $check['cv'];
      $extraCheck_dv = $check['dv'];
      $extraCheck_ac = $check['ac'];
      $extraCheck_ac_top_acc = $check['ac_top_acc'];
      $extraCheck_ad = $check['ad'];
      $extraCheck_ad_top_acc = $check['ad_top_acc'];
      $extraCheck_sj = $check['sj'];
      $extraCheck_sj_s = $check['sj_s'];


      $extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C' ) ";
      $extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C' ) ";



      $extraCheck_cr .= " AND YEAR(cr.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_cp .=  " AND YEAR(cp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_br .= " AND YEAR(bb.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_bp .= " AND YEAR(bp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_jv .= " AND YEAR(jv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_cv .= " AND YEAR(cv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_dv .= " AND YEAR(dv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_sj = " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')" . $extraCheck_sj;
      $extraCheck_sj_s = " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')" . $extraCheck_sj_s;
      $extraCheck_ac .=  " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ac_top_acc .=  " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ad .=  " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ad_top_acc .=  " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

//        $group_select =" SELECT YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";
//        $groupby = "  ) as temp GROUP BY YEAR(date), account,subacc ";
    
        $data = array();
//        $data = $this->getReports($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$open_cr_top_acc,$open_cp_top_acc,$open_bb_top_acc,$open_bp_top_acc,$open_ac_top_acc,$open_ad_top_acc,$orderby,$group_select, $groupby,$openBalName);

        $data = $this->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Summary");

        $opening_balance['debit'] = 0;
        $opening_balance['credit'] = 0;
        $opening_balance['alloc_debit'] =0;
        $opening_balance['alloc_credit'] = 0;
        $opening_balance['s1'] = 0;
        $opening_balance['s2'] = 0;
        $opening_balance['s3'] = 0;
        $opening_balance['s4'] = 0;
        $opening_balance['s5'] = 0;

        if(!empty($data)){
          foreach ($data as $key => $item){
            $opening_balance['alloc_debit'] += $item->alloc_debit;
            $opening_balance['alloc_credit'] += $item->alloc_credit;
            $opening_balance['debit'] += $item->debit;
            $opening_balance['credit'] += $item->credit;
            $opening_balance['s1'] += $item->s1;
            $opening_balance['s2'] += $item->s2;
            $opening_balance['s3'] += $item->s3;
            $opening_balance['s4'] += $item->s4;
            $opening_balance['s5'] += $item->s5;

          }
        }

          //$opening_balance['debit'] = $opening_balance['debit'] - $opening_balance['alloc_debit'] ;
          //$opening_balance['credit'] =  $opening_balance['credit'] -  $opening_balance['alloc_credit'];

        $extraCheck_obal = "";
        if ($this->txtFromDate !='') {
          $extraCheck_obal .= " YEAR(ob.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        }
        else {
          $extraCheck_obal .= " YEAR(ob.dDate) =   YEAR('". date('Y-m-d')."')";
        }

        if ($this->ddlDivision != '') {
          $extraCheck_obal .= " AND ob.nDivision = '".$this->ddlDivision."'";
        }
        if ($this->ddlLocation != '' && $this->ddlLocation != '') {
          $extraCheck_obal .= "  AND ( ob.vLocation='".$this->ddlLocation."' OR ob.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
          if ($this->ddlLocation != '' ) {
            $extraCheck_obal .= " AND  ob.vLocation='".$this->ddlLocation."' ";
          }//end if
          if ($this->ddlLocation2 != '' ) {
            $extraCheck_obal .= " AND  ob.vLocation='".$this->ddlLocation2."' ";

          }//end if
        }
          if(!($this->ddlAccount == '' ||  $this->ddlAccount == '0')){
            $extraCheck_obal .= " AND obd.vACode = '" . $this->ddlAccount. "' ";
            if(!( $this->ddlSubAccount  == '' ||  $this->ddlSubAccount  == '0'))
              $extraCheck_obal .= " AND obd.vAcSubCode = '" . $this->ddlSubAccount . "' ";
          }

          $adminGroupListQuery = " SELECT  DATEDIFF('".date('Y-m-d',strtotime($this->txtToDate)) ."',ob.dDate) as date, 
            obd.vBalDebit as debit, obd.vBalCredit as credit,'' as subCode
            FROM {PRE}OpeningBalance ob LEFT JOIN {PRE}OpeningBalanceDetails obd ON ob.nOBId = obd.nBalId
            WHERE " . $extraCheck_obal . " ";

          $adminGroupList 		= $this->db->query($adminGroupListQuery);
          if ($adminGroupList->num_rows() > 0)
          {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              $slab_days = $row->date;
              if($slab_days <= $this->txtSlab1 )     { $opening_balance['s1'] += $row->debit; }
              else if($this->txtSlab1 < $slab_days && $slab_days <= $this->txtSlab2){ $opening_balance['s2'] += $row->debit; }
              else if($this->txtSlab2 < $slab_days && $slab_days <= $this->txtSlab3){ $opening_balance['s3'] += $row->debit; }
              else if($this->txtSlab3 < $slab_days && $slab_days <= $this->txtSlab4){ $opening_balance['s4'] += $row->debit; }
              else if($slab_days < $this->txtSlab4){ $opening_balance['s5'] = $debit; }

              $opening_balance['alloc_debit']  += $row->debit;
              $opening_balance['alloc_credit']   += $row->credit;
            }//end for each
          } //end if


        return ($opening_balance);

    }//end function


    function accountList($search_acc,$search_subAcc,$list_values){
      $check = '';
      $prevAcc =$prevSubAcc ='';
      $chkAccount_subacc = array();
      $list_values2 = array();
      if($search_acc != 0){
        $check = " WHERE aha.nId = " . $search_acc . " ";
      }
      if(!empty($list_values)){
        foreach($list_values as $key => $item){
          $chkAccount_subacc[] = $item->vACode;
          $list_values2[] = $item;
        }
      }
      $adminGroupListQuery = " SELECT aha.nId,aha.vTitle, aha.vACode, ahc.vSubLedgerType from {PRE}AccountHeadAccounts aha LEFT JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHid " . $check . " ";
      $adminGroupList     = $this->db->query($adminGroupListQuery);
      $adminGroupRow = array();
      $i = 0;
      if ($adminGroupList->num_rows() > 0)
      {
        foreach ($adminGroupList->result() as $key=>$row)
        {
          $common_columns = " vName, vCode, '". $search_acc ."' as vAcSubCode, '". $row->vSubLedgerType . "'  as vTable, 0 as debit, 0 as credit, '' as mode,  '' as tblName ";
          $check_sub = '';
          $subAccountQuery = '';
          switch($row->vSubLedgerType)
          {
            case "C":
              if($search_subAcc != '0'){
                $check_sub = " AND nCId= " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nCId as vACode, " . $common_columns . " FROM {PRE}CustomerMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;

            case "V":
              if($search_subAcc != '0'){
                $check_sub = " AND nVId = " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nVId as vACode,  " . $common_columns . " FROM {PRE}VendorMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;

            case "A":
              if($search_subAcc != '0'){
                $check_sub = " AND nAId = " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nAId as vACode, " . $common_columns . " FROM {PRE}Agent WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;

            case "E":
              if($search_subAcc != '0'){
                $check_sub = " AND nEId = " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nEId as vACode, " . $common_columns . " FROM {PRE}EmployeeMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;
          }
          if($subAccountQuery != ''){
            $subAccountList     = $this->db->query($subAccountQuery);
            if ($subAccountList->num_rows() > 0)
            {
              foreach ($subAccountList->result() as $key2=>$row2)
              {
                if(!in_array($row2->vACode,$chkAccount_subacc)){
                  $list_values2[] = $row2;
                }
              }
            }
          }
        }//end for each
      } //end if
      return ($list_values2);
    } //end function




}//end class
?>
