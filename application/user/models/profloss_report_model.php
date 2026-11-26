<?php
class Profloss_report_model extends CI_Model {

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
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==47 || $aid==46){
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

    function getAccountGroup()
    {
        $typeGroup    = $this->db->query("SELECT nId,vTitle,vCode FROM {PRE}FinanceModule WHERE nParent IN (SELECT nId FROM  {PRE}FinanceModule WHERE vTitle IN('3','4')) AND vLevel = '2' AND vActive = '1' ORDER BY vTitle ASC");
        $typeGroupRow = array();

        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
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

    function filterAccount($account)
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aha.vACode,aha.nId as ahaId,aha.vTitle,ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup,ah.vActive,
                                    fm.vTitle as ahead, fm.nId as aid  FROM {PRE}FinanceModule fm
                                 LEFT JOIN {PRE}AccountHead ah ON fm.nId = ah.nAGroup
                                    LEFT JOIN {PRE}AccountHeadAccounts aha ON ah.nAId = aha.nHAId
                                 WHERE fm.nId IN (10,12) AND ah.vActive = '1' AND ah.nAGroup='".$account."' ORDER BY aha.nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nAId] = $row->vAcname;
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
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getAccGroup($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
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
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId DESC, HeadId, AccountId DESC";//print $adminGroupListQuery; 
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

    function getMonthly($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
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
                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY GroupId DESC, HeadId, AccountId DESC";
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 = '-'.$row->debit; break;
                case 'Cr': $debit1 = $row->debit; break;
                default:
                           if($row->debit == '' || $row->debit == 0):
                             $debit1 = $row->credit;
                           else:
                             $debit1 = '-'.$row->debit;
                           endif;
                           break;
              }
              $accValues['debit'] += $debit1;
            }//end for each
        } //end if
        return ($accValues);
    }//end function
	
	
	
	
	//new function for vTitla IN (4)
	function getAccValues1($accId)
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";
//if($accId==194){echo $adminGroupListQuery;}|| $row->AccountId ==170 || $row->AccountId ==194 
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 = $row->debit; break;
                case 'Cr': $debit1 = '-'.$row->debit; break;
                default:
                           if($row->debit == '' || $row->debit == 0):
                             $debit1 = $row->credit;
                           else:
						   if($row->AccountId ==200 ){
							    $debit1 = $row->debit;
						   }else{
						   $debit1 = '-'.$row->debit;
						   }
                           endif;
                           break;
              }
              $accValues['debit'] += $debit1;
            }//end for each
        } //end if
		//if($accId==194){$accValues['debit']=3700;}
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

    function getHeadAccValues($accId)
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND ah.nAId = '".$accId."')

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND ah.nAId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 ='-'. $row->debit; break;
                case 'Cr': $debit1 = $row->debit; break;
                default:

                  if($row->debit == '' || $row->debit == 0):
                      $debit1 = $row->credit;
                  else:
                      $debit1 = '-'.$row->debit;
                  endif;
                  break;
              }

                $accValues['debit'] += $debit1;
            }//end for each
        } //end if
        return ($accValues);
    }//end function
	
	// new function for acc-head
	
	
	
	 function getHeadAccValues1($accId)
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND ah.nAId = '".$accId."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND ah.nAId = '".$accId."')

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND ah.nAId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 = $row->debit; break;
                case 'Cr': $debit1 ='-'. $row->debit; break;
                default:

                  if($row->debit == '' || $row->debit == 0):
                      $debit1 = $row->credit;
                  else:
                      if($row->AccountId ==200){
							    $debit1 = $row->debit;
						   }else{
						   $debit1 = '-'.$row->debit;
						   }
                  endif;
                  break;
              }

                $accValues['debit'] += $debit1;
            }//end for each
        } //end if
        return ($accValues);
    }//end function

    function getOpenHeadAccValues($accId)
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
                                     AND ah.vActive = '1' ".$this->open_cr." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_cp." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_bb." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_bp." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_jv." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_cv." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_dv." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_ac." AND ah.nAId = '".$accId."')

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
                                     AND ah.vActive = '1' ".$this->open_ad." AND ah.nAId = '".$accId."')

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
                                 AND ah.vActive = '1' ".$this->open_sj." AND ah.nAId = '".$accId."') ORDER BY GroupId,HeadId,AccountId ASC";

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

    function getMonthlyAccValues($accId,$date)
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(cr.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cr.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(cp.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cp.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(bb.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(bb.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(bp.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(bp.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(jv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(jv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(cv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(dv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(dv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(ac.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(ac.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(ad.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(ad.dDate,'%Y-%m') <= '".$date."')

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('3')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."'
								 	AND DATE_FORMAT(sj.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(sj.dDate,'%Y-%m') <= '".$date."') ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 ='-'. $row->debit; break;
                case 'Cr': $debit1 = $row->debit; break;
                default:
                    if($row->debit == '' || $row->debit == 0):
                        $debit1 =  $row->credit;
                    else:
                        $debit1 = '-'.$row->debit;
                    endif;
                    break;
              }
                $accValues['debit'] += $debit1;
            }//end for each
        } //end if
		
		$totalVal = $accValues['debit'];
		return ($totalVal);
    }//end function
	
	
	
	// new function for monthly
	
	
	
	 function getMonthlyAccValues1($accId,$date)
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(cr.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cr.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(cp.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cp.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(bb.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(bb.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(bp.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(bp.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."'
									 AND DATE_FORMAT(jv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(jv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(cv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(cv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(dv.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(dv.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(ac.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(ac.dDate,'%Y-%m') <= '".$date."')

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."'
									 	AND DATE_FORMAT(ad.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(ad.dDate,'%Y-%m') <= '".$date."')

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('4')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."'
								 	AND DATE_FORMAT(sj.dDate,'%Y-%m') >= '".$date."' AND DATE_FORMAT(sj.dDate,'%Y-%m') <= '".$date."') ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              switch($row->mode){

                case 'Dr': $debit1 = $row->debit; break;
                case 'Cr': $debit1 = '-'.$row->debit; break;
                default:
                    if($row->debit == '' || $row->debit == 0):
                        $debit1 =  $row->credit;
                    else:
                        if($row->AccountId ==200){
							    $debit1 = $row->debit;
						   }else{
						   $debit1 = '-'.$row->debit;
						   }
                    endif;
                    break;
              }
                $accValues['debit'] += $debit1;
            }//end for each
        } //end if
		
		$totalVal = $accValues['debit'];
		return ($totalVal);
    }//end function


    function getMonthlyOpenAccValues($accId)
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

    function getAllDetails($list_values){
      $list_values2 = $chkGroupId = $chkHeadId = $chkAccountId = $chkMainId = array();	
      $prevGroupId = $prevHeadId = $prevmainId = ''; 
      if(!empty($list_values)){
        foreach($list_values as $key => $item){
          $chkGroupId[] = $item->GroupId;
          $chkHeadId[] = $item->HeadId;
          $chkAccountId[] = $item->AccountId;
          $chkMainId[] = $item->mainId;
          if(!in_array('3',$chkMainId)){
            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
            //if first retrieved is expenses then retrieve income
            if($mainId != '3'){
              $chkMainId[] = '3';
              $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0','3');						
            }
          }

          $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$prevmainId."'");
          //echo print_r($item) . " <br><br><br>";
          if($prevHeadId != $item->HeadId && $prevHeadId != ''){
            // get list of missing account and account head

            $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0',$prevHeadId,$mainId);						
          } //end if

          if($prevmainId != $item->mainId && $prevmainId != ''){

            $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0',$mainId);
          } //end if


          if($prevGroupId != $item->GroupId && $prevGroupId != ''){
            //get list of missing account and account head				
            $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,$prevGroupId,'0',$mainId);						
          } //end if

          //get opening balance
          /*              $openingbalance = getOpeningBalForAcc($item->AccountId,'','');
                          $item->openDebit = $openingbalance['debit'];				
                          $item->openCredit = $openingbalance['credit'];					*/
          $list_values2[] = $item;
          $prevHeadId = $item->HeadId ;
          $prevGroupId =  $item->GroupId;
          $prevAccountId = $item->AccountId;
          $prevmainId = $item->mainId;


        } //end foreach
        $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0',$prevHeadId,$mainId);						
        $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,$prevGroupId,'0',$mainId);						
        $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0',$mainId);						
      }

      if(!in_array('3',$chkMainId)){
          $chkMainId[] = '3';
          $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0','3');						
      }

      if(!in_array('4',$chkMainId)){
        $chkMainId[] = '4';
        $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0','4');						
      }
      return $list_values2;	
    }//end function


  function accountList($search_accGrp,$search_acc,&$chkGroupId,&$chkHeadId,&$chkAccountId,&$list_values2,$accGrpId,$accHeadId,$mainId){
//    $list_values = array();
    // system checks 
    if($search_accGrp != 0){
      $check_accGrp = " AND fm.nId = " . $search_accGrp . " ";
    }
    if($search_acc != 0){
      $check_acc = " AND aha.nId = " . $search_acc . " ";
    }



    $chk_main = " AND ( SELECT fm1.vTitle FROM {PRE}FinanceModule fm1 WHERE fm.nParent = fm1.nId ) = " . $mainId. " ";
//    $chk_main = " " ;// AND fm.nParent = " . $prevmainId . " ";
    if($accHeadId == 0){
      if($accGrpId != 0)
        $extra_check_accGrp = " WHERE fm.nId = " . $accGrpId . " ";   
      else 
        $extra_check_accGrp = " WHERE 1=1 ";   
      $accGrpQuery = "SELECT ah.nAId, ah.vAcname, ah.vHCode, fm.nId, fm.vCode as groupCode, fm.vTitle as groupTitle 
                FROM {PRE}AccountHead ah 
                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup "
                . $extra_check_accGrp .  $check_accGrp . $chk_main . "  ORDER BY nId, nAId ASC ";

      $accGrpQueryList     = $this->db->query($accGrpQuery);
      if ($accGrpQueryList->num_rows() > 0)
      {
        foreach ($accGrpQueryList->result() as $key_g=>$row_g)
        {
          if(!in_array($row_h->nAId,$chkHeadId)){
            $accHeadQuery = " SELECT fm.vTitle as GroupName,  fm.vCode as GroupCode, fm.nId as GroupId, fm.nParent as mainId,
                ah.vAcname as HeadName, ah.vHCode as HeadCode, ah.nAId as HeadId,
                aha.vTitle as AccountName, aha.vACode as AccountCode, aha.nId as AccountId, 
                '0' as debit, '0' as credit, '' as mode
                FROM {PRE}AccountHeadAccounts aha 
                LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId 
                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup
                WHERE ah.nAId = " . $row_g->nAId . " " . $check_acc . $chk_main . " ORDER BY GroupId,HeadId, AccountId ASC ";
            $accHeadQueryList     = $this->db->query($accHeadQuery);
            if ($accHeadQueryList->num_rows() > 0)
            {
              foreach ($accHeadQueryList->result() as $key_h=>$row_h)
              {
                if(!in_array($row_h->AccountId,$chkAccountId)){
                  // insert the account head in the list
                  $chkGroupId[] = $row_h->GroupId;
                  $chkHeadId[] = $row_h->HeadId;
                  $chkAccountId[] = $row_h->AccountId;
//                  if($row_h->openDebit != 0 || $row_h->openCredit != 0)
                    $list_values2[] = $row_h;
                } //end if !in_array($row_h->nId,$chkAccountId

              } //end foreach
            } //end if 
          } // end if !in_array($row_h->nAId,$chkHeadId
        } //end foreach
      } // end if $accGrpQueryList->num_rows() > 0
    } //end if ( $accHeadId == 0)
    else {
      if($accHeadId != 0)
        $extra_check_head = " WHERE fm.nId = " . $accHeadId. " ";   
      else 
        $extra_check_head = " WHERE 1=1 ";   
 
      $accHeadQuery = " SELECT fm.vTitle as GroupName,  fm.vCode as GroupCode, fm.nId as GroupId, fm.nParent as mainId,
                ah.vAcname as HeadName, ah.vHCode as HeadCode, ah.nAId as HeadId,
                aha.vTitle as AccountName, aha.vACode as AccountCode, aha.nId as AccountId,
                '0' as debit, '0' as credit, '' as mode
                FROM {PRE}AccountHeadAccounts aha 
                LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup "
                . $extra_check_head . " " . $check_acc . $chk_main . " ORDER BY GroupId,HeadId, AccountId ASC ";

      $accHeadQueryList     = $this->db->query($accHeadQuery);
      if ($accHeadQueryList->num_rows() > 0)
      {
        foreach ($accHeadQueryList->result() as $key_h=>$row_h)
        {
          if(!in_array($row_h->AccountId,$chkAccountId)){
            // insert the account head in the list
                  $chkGroupId[] = $row_h->GroupId;
                  $chkHeadId[] = $row_h->HeadId;
                  $chkAccountId[] = $row_h->AccountId;
                  //if($row_h->openDebit != 0 || $row_h->openCredit != 0)
                  $list_values2[] = $row_h;
          } //end if !in_array($row_h->nId,$chkAccountId

        } //end foreach
      } //end if 

    } //end if else ( $accHeadId == 0)
//    return $list_values;
  } // end function

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
        $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
        $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
        $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
        $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
        $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        $extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
        $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
        $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
      }//end if

      if ($this->ddlLocation != '0' && $this->ddlLocation != '0') {
        $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
        $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
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
        if ($this->ddlLocation != '0' ) {
          $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
          $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
          $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
          $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
          $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
          $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
          $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
          $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
          $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
          $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
        }//end if

        if ($this->ddlLocation2 != '0' ) {
          $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
          $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
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
      $extraCheck_bb_top_acc = $extraCheck_bb;
      $extraCheck_bp_top_acc = $extraCheck_bp;
      $extraCheck_ac_top_acc = $extraCheck_ac;
      $extraCheck_ad_top_acc = $extraCheck_ad;

      // Account handeling for finance entries
      if (!($this->ddlAccount == '0' || $this->ddlAccount == '') ) {
        // CR CP BR BP ACM ADM 
        $extraCheck_cr_top_acc .= " AND cr.nAcCode='".$this->ddlAccount."'";
        $extraCheck_cp_top_acc .= " AND cp.nAcCode='".$this->ddlAccount."'";
        $extraCheck_bb_top_acc .= " AND bb.nAcCode='".$this->ddlAccount."'";
        $extraCheck_bp_top_acc .= " AND bp.nAcCode='".$this->ddlAccount."'";
        $extraCheck_ac_top_acc .= " AND ac.nAcCode='".$this->ddlAccount."'";
        $extraCheck_ad_top_acc .= " AND ad.nAcCode='".$this->ddlAccount."'";

        // CRD CPD BRD BPD CRV DRV ACM ADM
        $extraCheck_cr .= " AND IF(crd.vAcSubCode !='0' , crd.vAcSubCode = '".$this->ddlAccount."', crd.vACode='".$this->ddlAccount."' )";
        $extraCheck_cp .= " AND IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode = '".$this->ddlAccount."', cpd.vACode='".$this->ddlAccount."' )"; 
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
          $extraCheck_cr .= " AND IF(crd.vAcSubCode !='0' , crd.vACode = '".$this->ddlSubAccount."' AND crd.vTable ='".$this->tableType."',1 )";
          $extraCheck_cp .= " AND IF(cpd.vAcSubCode !='0' , cpd.vACode = '".$this->ddlSubAccount."' AND cpd.vTable ='".$this->tableType."',1 )"; 
          $extraCheck_bb .= " AND IF(bbd.vAcSubCode !='0' , bbd.vACode = '".$this->ddlSubAccount."' AND bbd.vTable ='".$this->tableType."',1 )";
          $extraCheck_bp .= " AND IF(bpd.vAcSubCode !='0' , bpd.vACode = '".$this->ddlSubAccount."' AND bpd.vTable ='".$this->tableType."',1 )";
          $extraCheck_jv .= " AND IF(jvd.vAcSubCode !='0' , jvd.vACode = '".$this->ddlSubAccount."' AND jvd.vTable ='".$this->tableType."',1 )";
          $extraCheck_cv .= " AND IF(cvd.vAcSubCode !='0' , cvd.vACode = '".$this->ddlSubAccount."' AND cvd.vTable ='".$this->tableType."',1 )";
          $extraCheck_dv .= " AND IF(dvd.vAcSubCode !='0' , dvd.vACode = '".$this->ddlSubAccount."' AND dvd.vTable ='".$this->tableType."',1 )";
          $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vACode = '".$this->ddlSubAccount."' AND acd.vTable ='".$this->tableType."',1 )";
          $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vACode = '".$this->ddlSubAccount."' AND ade.vTable ='".$this->tableType."',1 )";

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
      $extra_checks['open_bb_top_acc'] = $extraCheck_bb_top_acc . $openBal_bb_dt;
      $extra_checks['open_bp_top_acc'] = $extraCheck_bp_top_acc . $openBal_bp_dt;
      $extra_checks['open_ac_top_acc'] = $extraCheck_ac_top_acc . $openBal_ac_dt;
      $extra_checks['open_ad_top_acc'] = $extraCheck_ad_top_acc . $openBal_ad_dt;


      $extra_checks['extraCheck_cr']= $extraCheck_cr . $extraCheck_cr_dt;
      $extra_checks['extraCheck_cp']= $extraCheck_cp . $extraCheck_cp_dt;
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
      $extra_checks['extraCheck_bb_top_acc']= $extraCheck_bb_top_acc . $extraCheck_bb_dt;
      $extra_checks['extraCheck_bp_top_acc']= $extraCheck_bp_top_acc . $extraCheck_bp_dt;
      $extra_checks['extraCheck_ac_top_acc']= $extraCheck_ac_top_acc . $extraCheck_ac_dt;
      $extra_checks['extraCheck_ad_top_acc']= $extraCheck_ad_top_acc . $extraCheck_ad_dt;

      $extra_checks['extraCheck_sj_special'] = $extraCheck_sj_special;

      return $extra_checks; 


    }

    function getOpeningBalForAccHead($acchead){
      $adminGroupListQuery = "SELECT nId FROM {PRE}AccountHeadAccounts where nHAId = '". $acchead . "' "; // AND nLocation = '" . $location . "' " ;
      $adminGroupList     = $this->db->query($adminGroupListQuery);
      $debit = 0;
      $credit = 0;

      if ($adminGroupList->num_rows() > 0)
      {
        foreach ($adminGroupList->result() as $key=>$row)
        {
          $openingbal = $this->getOpeningBalForAcc($row->nId,null,null);
          $debit += $openingbal['debit'];
          $credit += $openingbal['credit'];
        }
      }
      $openbal['debit'] = $debit;
      $openbal['credit'] = $credit;

      $openbal['openDebit'] =  $openbal['debit'] -  $openbal['credit'];
      return $openbal;
    }//end function



    function getOpeningBalForAcc($account,$subacc,$table)
    {

      $this->ddlSubAccount = $subacc;
      $this->ddlAccount = $account;
      $this->tableType = $table;


      if( $this->ddlAccount == '0'){

        $temp =  $this->ddlAccount;
        $this->ddlAccount =  $this->ddlSubAccount;
        $this->ddlSubAccount = $temp;

      }

        $extra_checks = array();
        $extra_checks = $this->checks();

        $open_cr = $extra_checks['open_cr'] . " AND YEAR(cr.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_cp = $extra_checks['open_cp'] . " AND YEAR(cp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_bb = $extra_checks['open_bb'] . " AND YEAR(bb.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_bp = $extra_checks['open_bp'] . " AND YEAR(bp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_jv = $extra_checks['open_jv'] . " AND YEAR(jv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_cv = $extra_checks['open_cv'] . " AND YEAR(cv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_dv = $extra_checks['open_dv'] . " AND YEAR(dv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_sj = $extra_checks['open_sj'] . " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_ac = $extra_checks['open_ac'] . " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_ad = $extra_checks['open_ad'] . " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

        $open_cr_top_acc = $extra_checks['open_cr_top_acc'] . " AND YEAR(cr.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_cp_top_acc = $extra_checks['open_cp_top_acc'] . " AND YEAR(cp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_bb_top_acc = $extra_checks['open_bb_top_acc'] . " AND YEAR(bb.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_bp_top_acc = $extra_checks['open_bp_top_acc'] . " AND YEAR(bp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_ac_top_acc = $extra_checks['open_ac_top_acc'] . " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
        $open_ad_top_acc = $extra_checks['open_ad_top_acc'] . " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

        $group_select =" SELECT YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";
        $groupby = "  ) as temp GROUP BY YEAR(date), account,subacc ";
    
        $data = array();
        $data = $this->getReports($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$open_cr_top_acc,$open_cp_top_acc,$open_bb_top_acc,$open_bp_top_acc,$open_ac_top_acc,$open_ad_top_acc,$orderby,$group_select, $groupby);

        $opening_balance['debit'] = 0;
        $opening_balance['credit'] = 0;
        foreach ($data as $key => $item){
          $opening_balance['debit'] += $item->debit;
          $opening_balance['credit'] += $item->credit;
        }

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
        if ($this->ddlLocation != '0' && $this->ddlLocation != '0') {
          $extraCheck_obal .= "  AND ( ob.vLocation='".$this->ddlLocation."' OR ob.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
          if ($this->ddlLocation != '0' ) {
            $extraCheck_obal .= " AND  ob.vLocation='".$this->ddlLocation."' ";
          }//end if
          if ($this->ddlLocation2 != '0') {
            $extraCheck_obal .= " AND  ob.vLocation='".$this->ddlLocation2."' ";

          }//end if
        }
          if(!($this->ddlAccount == '' ||  $this->ddlAccount == '0')){
            $extraCheck_obal .= " AND obd.vACode = '" . $this->ddlAccount. "' ";
            if(!( $this->ddlSubAccount  == '' ||  $this->ddlSubAccount  == '0'))
              $extraCheck_obal .= " AND obd.vAcSubCode = '" . $this->ddlSubAccount . "' ";
          }


          $adminGroupListQuery = " SELECT obd.vBalDebit as debit, obd.vBalCredit as credit,'' as subCode
            FROM {PRE}OpeningBalance ob LEFT JOIN {PRE}OpeningBalanceDetails obd ON ob.nOBId = obd.nBalId
            WHERE " . $extraCheck_obal . " ";
          $adminGroupList 		= $this->db->query($adminGroupListQuery);
          if ($adminGroupList->num_rows() > 0)
          {
            foreach ($adminGroupList->result() as $key=>$row)
            {
              $opening_balance['debit']  += $row->debit;
              $opening_balance['credit']   += $row->credit;
            }//end for each
          } //end if
          $opening_balance['openDebit'] = $opening_balance['debit']  - $opening_balance['credit'];

        return ($opening_balance);

    }//end function


    function getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby)
    {

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


     $cp_query = " (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
              cp.nAcCode as account,cp.vSubCode as subacc, '' as vTable,
              IF( cp.vTransMode = 'Dr' , cp.vAmountDC,0)as debit,
              IF( cp.vTransMode = 'Cr' , cp.vAmountDC,0)as credit,
              cp.nCPId as tableId,'CP' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}CashPayment cp WHERE cp.vActive='1'
            ".$extraCheck_cp_top_acc." )
              UNION ALL
              (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
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


	

}//end class
?>
