<?php
class Trial_report_model extends CI_Model {

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

    function filterLocation($division)
    {
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' ORDER BY nPosition DESC");
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

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getAccountGroup()
    {
        $typeGroup    = $this->db->query("SELECT nId,vTitle,vCode FROM {PRE}FinanceModule WHERE nParent!='0' AND vLevel='2'
                                                    AND vActive = '1' ORDER BY vTitle ASC");
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

    function mainPrintArray()
    {
        $mainPrintArrayList = array("Portrait" => "Portrait","Landscape" => "Landscape");

        return ($mainPrintArrayList);
    }//end function

    function getAccGroup($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cr.")

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bb.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_jv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_dv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ac.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ad.")

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId,HeadId,AccountId ASC";
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

    function getAccount($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cr.")

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bb.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_jv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_dv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ac.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ad.")

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId,HeadId,AccountId ASC";
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

    function getSubAccGroup($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cr.")

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bb.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_jv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_dv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ac.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ad.")

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId,HeadId,AccountId ASC";
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

    function getSubAccount($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,
                                   aha.nId as AccountId,crd.nAmount as debit,'' as credit,crd.vACode as sub,crd.vTable,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cr.")

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,cpd.vACode as sub,cpd.vTable,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,bbd.vACode as sub,bbd.vTable,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bb.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,bpd.vACode as sub,bpd.vTable,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vACode as sub,jvd.vTable,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_jv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vACode as sub,cvd.vTable,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vACode as sub,dvd.vTable,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_dv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,acd.vACode as sub,acd.vTable,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ac.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,ade.vACode as sub,ade.vTable,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ad.")

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as sub,sjd.vTable,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId,HeadId,AccountId ASC";
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

    function getAccValues($accId)
    {
        $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."')
                                 ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);   //echo $this->db->last_query().'<br><br>';
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                switch($row->mode)
                {
                    case "Dr":
                        $debit  = $row->debit;
                        $credit = '';
                        break;

                    case "Cr":
                        $debit  = '';
                        $credit = $row->debit;
                        break;

                    default:
                        $debit  = ($row->debit==' ')?'':$row->debit;
                        $credit = ($row->credit==' ')?'':$row->credit;
                        break;
                }

                $accValues['debit'] += $debit;
                $accValues['credit'] += $credit;
            }//end for each
        } //end if
        return ($accValues);
    }//end function

    function getOpenAccValues($accId)
    {
        $accValues = array();
        if(trim($this->txtFromDate) == ''):
            $accValues['openDebit'] = '';
            $accValues['openCredit'] = '';
        else:
            $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cr." AND aha.nId = '".$accId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bb." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_jv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_dv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ac." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ad." AND aha.nId = '".$accId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->open_sj." AND aha.nId = '".$accId."')
                                 ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                switch($row->mode)
                {
                    case "Dr":
                        $debit  = $row->debit;
                        $credit = '';
                        break;

                    case "Cr":
                        $debit  = '';
                        $credit = $row->debit;
                        break;

                    default:
                        $debit  = ($row->debit==' ')?'':$row->debit;
                        $credit = ($row->credit==' ')?'':$row->credit;
                        break;
                }

                $accValues['openDebit'] += $debit;
                $accValues['openCredit'] += $credit;
            }//end for each
        } //end if

        endif;
        return ($accValues);
    }//end function

    function getAccValuesSubGroup($headId)
    {
        $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND ah.nAId = '".$headId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND ah.nAId = '".$headId."')
                                 ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                switch($row->mode)
                {
                    case "Dr":
                        $debit  = $row->debit;
                        $credit = '';
                        break;

                    case "Cr":
                        $debit  = '';
                        $credit = $row->debit;
                        break;

                    default:
                        $debit  = ($row->debit==' ')?'':$row->debit;
                        $credit = ($row->credit==' ')?'':$row->credit;
                        break;
                }

                $accValues['debit'] += $debit;
                $accValues['credit'] += $credit;
            }//end for each
        } //end if
        return ($accValues);
    }//end function

    function getOpenAccValuesSubGroup($headId)
    {
        $accValues = array();
        if(trim($this->txtFromDate) == ''):
            $accValues['openDebit'] = '';
            $accValues['openCredit'] = '';
        else:
            $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cr." AND ah.nAId = '".$headId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cp." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bb." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode
                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bp." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_jv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_dv." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ac." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ad." AND ah.nAId = '".$headId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->open_sj." AND ah.nAId = '".$headId."')
                                 ORDER BY GroupId,HeadId,AccountId ASC";

            $adminGroupList 		= $this->db->query($adminGroupListQuery);
            if ($adminGroupList->num_rows() > 0)
            {
                foreach ($adminGroupList->result() as $key=>$row)
                {
                    switch($row->mode)
                    {
                        case "Dr":
                            $debit  = $row->debit;
                            $credit = '';
                            break;

                        case "Cr":
                            $debit  = '';
                            $credit = $row->debit;
                            break;

                        default:
                            $debit  = ($row->debit==' ')?'':$row->debit;
                            $credit = ($row->credit==' ')?'':$row->credit;
                            break;
                    }

                    $accValues['openDebit'] += $debit;
                    $accValues['openCredit'] += $credit;
                }//end for each
            } //end if

        endif;
        return ($accValues);
    }//end function

    function getAllSubAccValues($accId)
    {
        $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(crd.nAmount) as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode,
										CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vACode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vACode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vSubCode
										END as sub,
										CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vTable
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vTable
										    WHEN cr.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."' GROUP BY sub)

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(cpd.nAmount) as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode,
										CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vACode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vACode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vSubCode
										END as sub,
										CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vTable
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vTable
										    WHEN cp.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(bbd.nAmount) as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode,
										CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vACode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vACode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vSubCode
										END as sub,
										CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vTable
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vTable
										    WHEN bb.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(bpd.nAmount) as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode,
										CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vACode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vACode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vSubCode
										END as sub,
										CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vTable
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vTable
										    WHEN bp.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,

                                    '' as tnumber FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(jvd.nAmount) as debit,'' as credit,jvd.vMode as mode,jvd.vACode as sub,jvd.vTable,
                                    '' as tnumber FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(cvd.nAmount) as debit,'' as credit,cvd.vMode as mode,cvd.vACode as sub,cvd.vTable,
                                    '' as tnumber FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(dvd.nAmount) as debit,'' as credit,dvd.vMode as mode,dvd.vACode as sub,dvd.vTable,
                                    '' as tnumber FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(acd.nAmount) as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode,acd.vACode as sub,acd.vTable,
                                    '' as tnumber FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(ade.nAmount) as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode,ade.vACode as sub,ade.vTable,
                                    '' as tnumber FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(sjd.nDebit) as debit,SUM(sjd.nCredit) as credit,'' as mode,sjd.nDrAccount as sub,sjd.vTable,
                                     sjd.vNumber as tnumber FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."' GROUP BY sub)
                                 ORDER BY vTable,sub ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);//if($accId==24){echo $this->db->last_query().'<br><br>';}
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                switch($row->mode)
                {
                    case "Dr":
                        $debit  = $row->debit;
                        $credit = '';
                        break;

                    case "Cr":
                        $debit  = '';
                        $credit = $row->debit;
                        break;

                    default:
                        $debit  = ($row->debit==' ')?'':$row->debit;
                        $credit = ($row->credit==' ')?'':$row->credit;
                        break;
                }

                $accValuesDebit[]   += $debit;
                $accValuesCredit[]  += $credit;
                $accValuesSub[]     = $row->sub;
                $accValuesTable[]   = $row->vTable;
				$accValuesTktId[]   = $row->tnumber;
            }//end for each

            $accValues['debit']   = $accValuesDebit;
            $accValues['credit']  = $accValuesCredit;
            $accValues['sub']     = $accValuesSub;
            $accValues['table']   = $accValuesTable;
			$accValues['tnumber'] = $accValuesTktId;
        } //end if
        return ($accValues);
    }//end function

    function getAllSubOpenValues($accId)
    {
        $accValues = array();
        if(trim($this->txtFromDate) == ''):
            $accValues['openDebit'] = '';
            $accValues['openCredit'] = '';
        else:
            $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(crd.nAmount) as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode,
										CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vACode
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vACode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vSubCode
										END as sub,
										CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vTable
										    WHEN crd.vACode = aha.nId THEN
										 		crd.vTable
										    WHEN cr.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cr." AND aha.nId = '".$accId."' GROUP BY sub)

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(cpd.nAmount) as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vMode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vTransMode
										END as mode,
										CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vACode
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vACode
										    WHEN cp.nAcCode = aha.nId THEN
										 		cp.vSubCode
										END as sub,
										CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vTable
										    WHEN cpd.vACode = aha.nId THEN
										 		cpd.vTable
										    WHEN cp.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                                                      cpd.nCashPId = cp.nCPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'
                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cp." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(bbd.nAmount) as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode,
										CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vACode
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vACode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vSubCode
										END as sub,
										CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vTable
										    WHEN bbd.vACode = aha.nId THEN
										 		bbd.vTable
										    WHEN bb.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,
                                    '' as tnumber FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bb." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(bpd.nAmount) as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vMode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vTransMode
										END as mode,
										CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vACode
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vACode
										    WHEN bp.nAcCode = aha.nId THEN
										 		bp.vSubCode
										END as sub,
										CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vTable
										    WHEN bpd.vACode = aha.nId THEN
										 		bpd.vTable
										    WHEN bp.nAcCode = aha.nId THEN
										 		'A'
										END as vTable,

                                    '' as tnumber FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                       bpd ON bpd.nBankPaymentId = bp.nBPId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'
                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bp." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(jvd.nAmount) as debit,'' as credit,jvd.vMode as mode,jvd.vACode as sub,jvd.vTable,
                                    '' as tnumber FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_jv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(cvd.nAmount) as debit,'' as credit,cvd.vMode as mode,cvd.vACode as sub,cvd.vTable,
                                    '' as tnumber FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(dvd.nAmount) as debit,'' as credit,dvd.vMode as mode,dvd.vACode as sub,dvd.vTable,
                                    '' as tnumber FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_dv." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(acd.nAmount) as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode,acd.vACode as sub,acd.vTable,
                                    '' as tnumber FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ac." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(ade.nAmount) as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode,ade.vACode as sub,ade.vTable,
                                    '' as tnumber FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ad." AND aha.nId = '".$accId."' GROUP BY sub)

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    SUM(sjd.nDebit) as debit,SUM(sjd.nCredit) as credit,'' as mode,sjd.nDrAccount as sub,sjd.vTable,
                                     sjd.vNumber as tnumber FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->open_sj." AND aha.nId = '".$accId."' GROUP BY sub)
                                 ORDER BY vTable,sub ASC";

            $adminGroupList 		= $this->db->query($adminGroupListQuery);
            if ($adminGroupList->num_rows() > 0)
            {
                foreach ($adminGroupList->result() as $key=>$row)
                {
                    switch($row->mode)
                    {
                        case "Dr":
                            $debit  = $row->debit;
                            $credit = '';
                            break;

                        case "Cr":
                            $debit  = '';
                            $credit = $row->debit;
                            break;

                        default:
                            $debit  = ($row->debit==' ')?'':$row->debit;
                            $credit = ($row->credit==' ')?'':$row->credit;
                            break;
                    }

                    $accValuesOpenDebit[]  = $debit;
                    $accValuesOpenCredit[] = $credit;
                }//end for each

                $accValues['openDebit']  = $accValuesOpenDebit;
                $accValues['openCredit'] = $accValuesOpenCredit;
            } //end if

        endif;
        return ($accValues);
    }//end function

}//end class
?>