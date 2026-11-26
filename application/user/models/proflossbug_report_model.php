<?php
class Proflossbug_report_model extends CI_Model {

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
                                              WHERE ah.vActive = '1' AND (ahc.vSubSale = 'C' OR ahc.vSubSale = 'B') ORDER BY nId DESC");
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

    function getBudgetDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cr.")

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bb.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_bp.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_jv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_cv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_dv.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ac.")

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$extraCheck_ad.")

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId DESC";
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
	
	 function getBudgetDetail2($extraCheck)
    {
        $adminGroupListQuery  = "(SELECT b.dDate as date,b.vLocation as location,b.vDocNumber as document,
                                  bd.nAHAId as account,bd.nAmount,b.nTotal as total FROM
                                  {PRE}Budget b LEFT JOIN {PRE}BudgetDetails bd ON bd.nBudId = b.nBId WHERE b.vActive='1'
                                  ".$extraCheck.")";
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

    function getBudgetSummary($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND cr.nCRId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'CR')
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cp.nCPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation
                                  ca WHERE ca.vAllType = 'CP') ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bb.nBBId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation
                                  ca WHERE ca.vAllType = 'BR') ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bp.nBPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation
                                  ca WHERE ca.vAllType = 'BP') ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jv.nJVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'JV')
                                  ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cv.nCVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation
                                  ca WHERE ca.vAllType = 'CV') ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dv.nDVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation
                                  ca WHERE ca.vAllType = 'DV') ".$extraCheck_dv." GROUP BY dv.nDVId)";
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

	function getAllMonths()
    {
        $monthGroupRow = array("0" => ALL, "01" => "January", "02" => "February", "03" => "March", "04" => "April",
                                "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September",
                                "10" => "October", "11" => "November", "12" => "December");
        return ($monthGroupRow);
    }//end function
	
	function getAccValues($accId)
    {
        $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

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
                if($debit>$credit):
                    $debit1 = $debit-$credit;
                else:
                    $debit1 = $credit-$debit;
                endif;

                $accValues['debit'] += $debit1;
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
            $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cr." AND aha.nId = '".$accId."')

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bb." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent
                                        as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_bp." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    jvd.nAmount as debit,'' as credit,jvd.vMode as mode
                                    FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (jvd.vACode = aha.nId AND jvd.vTable = 'A'
                                      OR jvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle
                                      IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_jv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cvd.nAmount as debit,'' as credit,cvd.vMode as mode
                                    FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cvd.vACode = aha.nId AND cvd.vTable = 'A'
                                      OR cvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_cv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    dvd.nAmount as debit,'' as credit,dvd.vMode as mode
                                    FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (dvd.vACode = aha.nId AND dvd.vTable = 'A'
                                      OR dvd.vAcSubCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_dv." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle
                                      IN('3','4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ac." AND aha.nId = '".$accId."')

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,
                                        fm.nParent as mainId,ah.vAcname as HeadName,
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                      AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->open_ad." AND aha.nId = '".$accId."')

                                     UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as
                                  mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    sjd.nDebit as debit,sjd.nCredit as credit,'' as mode
                                     FROM {PRE}SystemJv sj
                                        LEFT JOIN {PRE}SystemJvDetails sjd ON sjd.nSjId = sj.nId
                                  LEFT JOIN {PRE}AccountHeadAccounts aha ON (sjd.nDrAccount = aha.nId AND sjd.vTable = 'A'
                                  OR sjd.nAccHeadId = aha.nId)
                                           LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                  LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3','4'))
                                  AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->open_sj." AND aha.nId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

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
                    $debit = ($debit==' ')?$credit:$debit;
                    $accValues['openDebit'] += $debit;
                }//end for each
            } //end if

        endif;
        return ($accValues);
    }//end function
}//end class
?>