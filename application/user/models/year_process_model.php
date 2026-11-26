<?php

class Year_process_model extends CI_Model {



    function __construct()

    {

        // Call the Model constructor

        parent::__construct();
		ini_set('memory_limit', '-1');

    }
	
	function getYearDetails() 

    {

        $typeGroup    = $this->db->query("SELECT * FROM tbl_AccountPeriod ");

        $typeGroupRow = array();



        if ($typeGroup->num_rows() > 0)

        {

            $typeGroupRow[0] = SELECT;

            foreach ($typeGroup->result() as $key=>$row)

            {

                $typeGroupRow[$row->nId] = stripslashes($row->vYearRef);

            }//end for each

        }//end if

        else

        {

            $typeGroupRow[0] = NOVALUE;

        }//end else

        return ($typeGroupRow);

    }//end function
	
	function getAllYearClose()
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlYearCloseQuery   	= "SELECT yc.*,d.* FROM {PRE}YearClosing yc LEFT JOIN {PRE}Division d ON yc.nDivision = d.nId ORDER BY yc.nYCId DESC";
									
        session_start();
        $_SESSION['sess_query'] = $sqlYearCloseQuery;
        $adminGroupList 		= $this->db->query($sqlYearCloseQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
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

											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {	/*$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==47 || $aid==46){
					$locGroupRow[0]=SELECT;
				}*/
			
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
	
	
	function filterYear($division)

    {

        $locGroup    = $this->db->query("SELECT * FROM {PRE}AccountPeriod WHERE nId = '".$division."'  ");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {	
			
            foreach ($locGroup->result() as $key => $row)

            {

                $locGroupRow['dFDate'] = stripslashes($row->dFDate);
				$locGroupRow['dTDate'] = stripslashes($row->dTDate);

            }//end for each

        }//end if

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

                                 AND ah.vActive = '1' ".$extraCheck_sj." ) ORDER BY HeadId ASC";

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


    function getAccValues($accId)
    {
/*if($accId == 24)
echo "<br><br><br><br>
                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
                      WHEN cpd.vAcSubCode = aha.nId THEN
                        cpd.vMode
                        WHEN cpd.vTable = 'A' AND  cpd.vACode = aha.nId THEN
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."' AND YEAR(cp.dDate) = YEAR(CURDATE()))

                           ";*/
        $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vTable='A' AND crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."' AND YEAR(cr.dDate) = YEAR(CURDATE()))

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vTable='A' AND cpd.vACode = aha.nId THEN
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."' AND YEAR(cp.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bbd.nAmount as debit,'' as credit,CASE
											WHEN bbd.vAcSubCode = aha.nId THEN
										 		bbd.vMode
										    WHEN bbd.vTable = 'A' AND bbd.vACode = aha.nId THEN
										 		bbd.vMode
										    WHEN bb.nAcCode = aha.nId THEN
										 		bb.vTransMode
										END as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."' AND YEAR(bb.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vTable = 'A' AND bpd.vACode = aha.nId THEN
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."' AND YEAR(bp.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."' AND YEAR(jv.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."' AND YEAR(cv.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."' AND YEAR(dv.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vTable = 'A' AND acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."' AND YEAR(ac.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vTable = 'A' AND ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."' AND YEAR(ad.dDate) = YEAR(CURDATE()))

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."' AND YEAR(sj.dDate) = YEAR(CURDATE())) ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
//if($accId == 34)
//echo "<pre>".$adminGroupListQuery . "</pre>";

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
/*if($accId!=39 || $accId!=229){
        $adminGroupListQuery_openBal =  " SELECT obd.vBalDebit as credit, obd.vBalCredit as debit,'' as subCode
          FROM {PRE}OpeningBalance ob LEFT JOIN {PRE}OpeningBalanceDetails obd ON ob.nOBId = obd.nBalId
          WHERE ob.vActive = '1' " . $this->extraCheck_obal . " AND obd.vACode = '".$accId."'  AND YEAR(ob.dDate) = YEAR(CURDATE()) ";
//echo $adminGroupListQuery_openBal; 
        $adminGroupList     = $this->db->query($adminGroupListQuery_openBal);

        if ($adminGroupList->num_rows() > 0)
        {
          foreach ($adminGroupList->result() as $key=>$row){
            $accValues['credit'] +=  $row->debit;
            $accValues['debit'] +=  $row->credit;
          }
        }
}*/
        return ($accValues);
    }//end function
	
	function getAccValues_222($accId)
    {
 $accValues = array();
        $adminGroupListQuery  = "(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                   ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    crd.nAmount as debit,'' as credit,CASE
											WHEN crd.vAcSubCode = aha.nId THEN
										 		crd.vMode
										    WHEN crd.vTable='A' AND crd.vACode = aha.nId THEN
										 		crd.vMode
										    WHEN cr.nAcCode = aha.nId THEN
										 		cr.vTransMode
										END as mode
                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR
                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."' AND YEAR(cr.dDate) = YEAR(CURDATE()))

                                  UNION ALL

                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                  ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    cpd.nAmount as debit,'' as credit,CASE
											WHEN cpd.vAcSubCode = aha.nId THEN
										 		cpd.vMode
										    WHEN cpd.vTable='A' AND cpd.vACode = aha.nId THEN
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."' AND YEAR(cp.dDate) = YEAR(CURDATE()))

									 UNION ALL
                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode
                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.vACode = '".$accId."' AND bbd.nBankbookId = bb.nBBId AND bbd.vTable = 'A'
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'
                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."' )
									 UNION ALL (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,
										 		bb.vTransMode as mode
                                    FROM {PRE}BankBook bb 
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN
                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    bpd.nAmount as debit,'' as credit,CASE
											WHEN bpd.vAcSubCode = aha.nId THEN
										 		bpd.vMode
										    WHEN bpd.vTable = 'A' AND bpd.vACode = aha.nId THEN
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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."' AND YEAR(bp.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_jv." AND aha.nId = '".$accId."' AND YEAR(jv.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_cv." AND aha.nId = '".$accId."' AND YEAR(cv.dDate) = YEAR(CURDATE()))

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
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_dv." AND aha.nId = '".$accId."' AND YEAR(dv.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    acd.nAmount as debit,'' as credit,CASE
											WHEN acd.vAcSubCode = aha.nId THEN
										 		acd.vMode
										    WHEN acd.vTable = 'A' AND acd.vACode = aha.nId THEN
										 		acd.vMode
										    WHEN ac.nAcCode = aha.nId THEN
										 		ac.vTransMode
										END as mode
                                    FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (acd.vACode = aha.nId AND acd.vTable = 'A'
                                      OR acd.vAcSubCode = aha.nId OR ac.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ac." AND aha.nId = '".$accId."' AND YEAR(ac.dDate) = YEAR(CURDATE()))

                                     UNION ALL

                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,fm.nParent as mainId,ah.vAcname as HeadName,
                                        ah.vHCode  as HeadCode,ah.nAId as
                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,
                                    ade.nAmount as debit,'' as credit,
                                        CASE
											WHEN ade.vAcSubCode = aha.nId THEN
										 		ade.vMode
										    WHEN ade.vTable = 'A' AND ade.vACode = aha.nId THEN
										 		ade.vMode
										    WHEN ad.nAcCode = aha.nId THEN
										 		ad.vTransMode
										END as mode
                                    FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId
                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (ade.vACode = aha.nId AND ade.vTable = 'A'
                                      OR ade.vAcSubCode = aha.nId OR ad.nAcCode = aha.nId)
                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId
                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId
                                      WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                     AND ah.vActive = '1' ".$this->extraCheck_ad." AND aha.nId = '".$accId."' AND YEAR(ad.dDate) = YEAR(CURDATE()))

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
                                  WHERE fm.nParent IN (SELECT nId FROM  {PRE}FinanceModule fm WHERE fm.vTitle IN('1','2')) AND fm.vLevel = '2' AND fm.vActive = '1'
                                 AND ah.vActive = '1' ".$this->extraCheck_sj." AND aha.nId = '".$accId."' AND YEAR(sj.dDate) = YEAR(CURDATE())) ORDER BY GroupId,HeadId,AccountId ASC";

        $adminGroupList 		= $this->db->query($adminGroupListQuery);
//if($accId == 34)
//echo "<pre>".$adminGroupListQuery . "</pre>";

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
/*if($accId!=39 || $accId!=229){
        $adminGroupListQuery_openBal =  " SELECT obd.vBalDebit as credit, obd.vBalCredit as debit,'' as subCode
          FROM {PRE}OpeningBalance ob LEFT JOIN {PRE}OpeningBalanceDetails obd ON ob.nOBId = obd.nBalId
          WHERE ob.vActive = '1' " . $this->extraCheck_obal . " AND obd.vACode = '".$accId."'  AND YEAR(ob.dDate) = YEAR(CURDATE()) ";
//echo $adminGroupListQuery_openBal; 
        $adminGroupList     = $this->db->query($adminGroupListQuery_openBal);

        if ($adminGroupList->num_rows() > 0)
        {
          foreach ($adminGroupList->result() as $key=>$row){
            $accValues['credit'] +=  $row->debit;
            $accValues['debit'] +=  $row->credit;
          }
        }
}*/
        return ($accValues);
    }//end function
    function getAccValues1($accId)

    {

        $accValues = array();
if($accId==23 ||$accId==26 || $accId==54 ||$accId==9){
	$ec="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cr.vAmountDC as debit,'' as credit,cr.vTransMode as mode

                                    FROM {PRE}CashReceipt cr 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

								UNION ALL
								(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									crd.nAmount as debit,'' as credit,crd.vMode as mode

                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR

                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')";
}else{
	$ec="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cr.vAmountDC as debit,'' as credit,cr.vTransMode as mode

                                    FROM {PRE}CashReceipt cr 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

								UNION ALL
								(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									crd.nAmount as debit,'' as credit,crd.vMode as mode

                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.vACode = '".$accId."'AND crd.nCashId = cr.nCRId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR

                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')";
									 
									 
									 
									 
}
if($accId==52 || $accId==54){
	$bp="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                    bpd.nAmount as debit,'' as credit,CASE

											WHEN bpd.vAcSubCode = aha.nId THEN

										 		bpd.vMode

										    WHEN bpd.vACode = aha.nId AND bpd.vTable = 'A' THEN

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

                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')";
}
else{
	$bp="(SELECT  fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                    CASE WHEN bpd.vACode = '".$accId."' then bpd.nAmount
										else  bp.vAmountDC
										end as debit,'' as credit,CASE

											WHEN bpd.vAcSubCode = aha.nId THEN

										 		bpd.vMode

										    WHEN bpd.vACode = aha.nId AND bpd.vTable = 'A' THEN

										 		bpd.vMode

										    WHEN bp.nAcCode = aha.nId THEN

										 		bp.vTransMode

										END as mode

                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails

                                       bpd ON  bpd.nBankPaymentId = bp.nBPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'

                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')";
}

if($accId==24 || $accId==33 || $accId==53 ||$accId==54 ){
	$cp="



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cp.vAmountDC as debit,'' as credit,


										CASE

										    WHEN cp.nAcCode = aha.nId THEN

										 		cp.vTransMode

										END as mode
                                    FROM {PRE}CashPayment cp  LEFT JOIN {PRE}AccountHeadAccounts aha ON (cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')
									 
									 UNION ALL



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										cpd.nAmount as debit,'' as credit,cpd.vMode as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'

                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')";
}else{
	$cp="



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cp.vAmountDC as debit,'' as credit,


										CASE

										    WHEN cp.nAcCode = aha.nId THEN

										 		cp.vTransMode

										END as mode
                                    FROM {PRE}CashPayment cp  LEFT JOIN {PRE}AccountHeadAccounts aha ON (cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')
									 
									 UNION ALL



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										cpd.nAmount as debit,'' as credit,cpd.vMode as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.vACode = '".$accId."' AND cpd.nCashPId = cp.nCPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'

                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')";
}
if($accId==26 || $accId==33 ){
	$bd="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,

										 		bb.vTransMode as mode

                                    FROM {PRE}BankBook bb 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
									 
									 
									 
									 UNION ALL



                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode

                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'

                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')";
}else{
	$bd="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,

										 		bb.vTransMode as mode

                                    FROM {PRE}BankBook bb 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
									 
									 
									 
									 UNION ALL



                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode

                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.vACode = '".$accId."' AND bbd.nBankbookId = bb.nBBId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'

                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')



                                     ";
}

       $adminGroupListQuery  = $ec."UNION ALL".$cp."



                                     UNION ALL".$bd."UNION ALL".$bp.



                                      
                                     "UNION ALL



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

										    WHEN acd.vACode = aha.nId AND acd.vTable = 'A' THEN

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

										    WHEN ade.vACode = aha.nId  AND ade.vTable = 'A' THEN

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


//print $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);   //echo $this->db->last_query().'<br><br>';
//var_dump($adminGroupList);
        if ($adminGroupList->num_rows() > 0)

        {

            foreach ($adminGroupList->result() as $key=>$row)

            {

                switch($row->mode)

                {

                    case "Dr":
if($row->AccountId=='24'){
	//echo " Dr:"; echo 
	echo $debit  = $row->debit;
						echo"&nbsp;";
}
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

										    WHEN crd.vACode = aha.nId AND crd.vTable='A' THEN

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

										    WHEN cpd.vACode = aha.nId  AND cpd.vTable='A' THEN

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

										    WHEN bbd.vACode = aha.nId  AND bbd.vTable = 'A' THEN

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

										    WHEN bpd.vACode = aha.nId AND bpd.vTable = 'A' THEN

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

										    WHEN acd.vACode = aha.nId AND acd.vTable = 'A' THEN

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

										    WHEN ade.vACode = aha.nId  AND ade.vTable = 'A' THEN

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

      $this->ddlAccount = $accId;

      $extra_checks = array();

      $extra_checks = $this->checks();



      $extraCheck_cr = $extra_checks['extraCheck_cr'];

      $extraCheck_cp = $extra_checks['extraCheck_cp'];

      $extraCheck_bb = $extra_checks['extraCheck_bb'];

      $extraCheck_bp = $extra_checks['extraCheck_bp'];

      $extraCheck_jv = $extra_checks['extraCheck_jv'];

      $extraCheck_cv = $extra_checks['extraCheck_cv'];

      $extraCheck_dv = $extra_checks['extraCheck_dv'];

      $extraCheck_sj = $extra_checks['extraCheck_sj'];

      $extraCheck_ac = $extra_checks['extraCheck_ac'];

      $extraCheck_ad = $extra_checks['extraCheck_ad'];





      $extraCheck_cr_top_acc = $extra_checks['extraCheck_cr_top_acc'];

      $extraCheck_cp_top_acc = $extra_checks['extraCheck_cp_top_acc'];

      $extraCheck_bb_top_acc = $extra_checks['extraCheck_bb_top_acc'];

      $extraCheck_bp_top_acc = $extra_checks['extraCheck_bp_top_acc'];

      $extraCheck_ac_top_acc = $extra_checks['extraCheck_ac_top_acc'];

      $extraCheck_ad_top_acc = $extra_checks['extraCheck_ad_top_acc'];



      $group_select =" SELECT location , COUNT(*) as count,

        account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit

          FROM ( ";



      $groupby = "  ) as temp GROUP BY account, subacc ";



      $orderby =  "  ORDER BY account, subacc ASC";



      $data = $this->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);



      //echo print_r($data) . "<br><br><br>";

      $accValues = $accValuesDebit = $accValuesCredit = $accValuesSub = $accValuesTable = $accValuesTktId = array();

      if(!empty($data))

      foreach($data as $key => $item){

        $accValuesDebit[] = $item->debit;

        $accValuesCredit[] = $item->credit;

        $accValuesSub[] = $item->subacc;

        $accValuesTable[] = $item->vTable;

        $accValuesTktId[] = '';



      }

      $accValues['debit']   = $accValuesDebit;

      $accValues['credit']  = $accValuesCredit;

      $accValues['sub']     = $accValuesSub;

      $accValues['table']   = $accValuesTable;

      $accValues['tnumber'] = $accValuesTktId;

      return ($accValues);



    }//end function

/*

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

        $totDebit = 0;

        $totCredit = 0;;

        $sub =  '';

        $vtab = '';

        $tnumber = '';

        $prevSub = '';

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



                echo  $row->sub . " != " . $prevSub . " ----- " .  $debit . " ~ " .$credit . "<br>";

                if($prevSub ==  $row->sub || $prevSub ==''){

                  $prevSub =  $row->sub;

                  $vtab = $row->vTable;

                  $tnumber = $row->tnumber;

                  $totDebit += $debit;

                  $totCredit += $credit;



                }

                else{

                  $accValuesDebit[]   = $totDebit;

                  $accValuesCredit[]  = $totCredit;

                  $accValuesSub[]     = $prevSub;

                  $accValuesTable[]   = $vtab;

                  $accValuesTktId[]   = $tnumber;



                  $prevSub =  $row->sub;



                  $totDebit = $debit;

                  $totCredit = $credit;

                  $vtab =  $row->vTable;

                  $tnumber =  $row->tnumber;

                }



            }//end for each



            $accValuesDebit[]   = $totDebit;

            $accValuesCredit[]  = $totCredit;

            $accValuesSub[]     = $prevSub;

            $accValuesTable[]   = $vtab;

            $accValuesTktId[]   = $tnumber;

            $accValues['debit']   = $accValuesDebit;

            $accValues['credit']  = $accValuesCredit;

            $accValues['sub']     = $accValuesSub;

            $accValues['table']   = $accValuesTable;

            $accValues['tnumber'] = $accValuesTktId;

        } //end if

        return ($accValues);

    }//end function

*/

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







    function subAccountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc){

      if($search_acc == '') $search_acc = 0;

      if($search_subAcc == '') $search_subAcc = 0;

      /*$adminGroupListQuery = "SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

        FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

        ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

        LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId

        WHERE ah.vActive = '1' ORDER BY nId DESC";*/

      $check = '';

      if($search_acc != 0){

        $check = " WHERE aha.nId = " . $search_acc . " ";

      }



      $adminGroupListQuery = " SELECT aha.nId,aha.vTitle, aha.vACode, ahc.vSubLedgerType, ah.nAId as HeadId, ah.vAcname as HeadName, ah.vHCode as HeadCode

              FROM {PRE}AccountHeadAccounts aha 

              LEFT JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHid 

              LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

              " . $check . " ORDER BY HeadId,nId";

      $adminGroupList     = $this->db->query($adminGroupListQuery);

      $adminGroupRow = array();

      $i = 0;

      if ($adminGroupList->num_rows() > 0)

      {

        foreach ($adminGroupList->result() as $key=>$row)

        {

          $check_sub = '';

          $subAccountQuery = '';

          switch($row->vSubLedgerType)

          {

            case "C":

              if($search_subAcc != '0'){

                $check_sub = " AND nCId= " . $search_subAcc . " ";

              }

            $subAccountQuery = "SELECT nCId as tableId, vName, vCode FROM {PRE}CustomerMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";

            break;



            case "V":

              if($search_subAcc != '0'){

                $check_sub = " AND nVId = " . $search_subAcc . " ";

              }

            $subAccountQuery = "SELECT nVId as tableId, vName, vCode FROM {PRE}VendorMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";

            break;



            case "A":

              if($search_subAcc != '0'){

                $check_sub = " AND nAId = " . $search_subAcc . " ";

              }

            $subAccountQuery = "SELECT nAId as tableId, vName, vCode FROM {PRE}Agent WHERE nFId = ". $row->nId . " " . $check_sub . " ";

            break;
			
			case "O":

              if($search_subAcc != '0'){

                $check_sub = " AND nOId = " . $search_subAcc . " ";

              }
	$subAccountQuery = "SELECT nOId as tableId,vName, vCode  FROM {PRE}Other WHERE nFId = ". $row->nId . " " . $check_sub . " ";
          
           // $subAccountQuery = "SELECT nAId as tableId, vName, vCode FROM {PRE}Agent WHERE nFId = ". $row->nId . " " . $check_sub . " ";

            break;



            case "E":

              if($search_subAcc != '0'){

                $check_sub = " AND nEId = " . $search_subAcc . " ";

              }

            $subAccountQuery = "SELECT nEId as tableId, vName, vCode FROM {PRE}EmployeeMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";

            break;

          }

//echo $row->nId. " ~~~ " . $subAccountQuery ." ------------------------------------------------- <br><br><br>";

          if($subAccountQuery != ''){

            $subAccountList     = $this->db->query($subAccountQuery);

            if ($subAccountList->num_rows() > 0)

            {

              foreach ($subAccountList->result() as $key2=>$row2)

              {

                if(!in_array($row2->tableId . "-" .$row->vSubLedgerType,$chkAccount_subacc)){

                  $getOpenValues=  $this->year_process_model->getOpeningBalForAcc( $row->nId, $row2->tableId,  $row->vSubLedgerType);

                  if($getOpenValues['debit'] == 0 && $getOpenValues['credit'] ==0)

                    continue;





                  $adminGroupRow[$i]['HeadName'] =  $row->HeadName;

                  $adminGroupRow[$i]['HeadCode'] =  $row->HeadCode;

                  $adminGroupRow[$i]['HeadId'] =  $row->HeadId;

          

                  $adminGroupRow[$i]['AccountId'] =  $row->nId;

                  $adminGroupRow[$i]['accountName'] = $row->vTitle;

                  $adminGroupRow[$i]['accountCode'] = $row->vACode;

                  $adminGroupRow[$i]['vTable'] = $row->vSubLedgerType;



                  $adminGroupRow[$i]['subAccountId'] =  $row2->tableId;

                  $adminGroupRow[$i]['subAccountName'] = $row2->vName;

                  $adminGroupRow[$i]['subAccountCode'] = $row2->vCode;



                  $adminGroupRow[$i]['debit'] = $getOpenValues['debit'];

                  $adminGroupRow[$i]['credit'] = $getOpenValues['credit'];

                  $i ++;

                }

              }

            }

          }

          else {

            if(!in_array($row->nId,$chkAccount_acc)){

              $getOpenValues=  $this->year_process_model->getOpeningBalForAcc( $row->nId, '','');

              if($getOpenValues['debit'] == 0 && $getOpenValues['credit'] ==0)

                continue;

              $adminGroupRow[$i]['debit'] = $getOpenValues['debit'];

              $adminGroupRow[$i]['credit'] = $getOpenValues['credit'];



              $adminGroupRow[$i]['HeadName'] =  $row->HeadName;

              $adminGroupRow[$i]['HeadCode'] =  $row->HeadCode;

              $adminGroupRow[$i]['HeadId'] =  $row->HeadId;



              $adminGroupRow[$i]['AccountId'] =  $row->nId;

              $adminGroupRow[$i]['accountName'] = $row->vTitle;

              $adminGroupRow[$i]['accountCode'] = $row->vACode;



              $i ++;

            }

          }





        }//end for each

      } //end if

      return ($adminGroupRow);

    }

    function getOpeningBalForAccHead($location,$dt,$acchead){

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

      $openbal['openDebit'] = $debit;

      $openbal['openCredit'] = $credit;

      return $openbal;

    }//end function





    function getAllDetails($search_accGrp,$search_Acc,$list_values){

      $list_values2 = $chkGroupId = $chkHeadId = $chkAccountId = array();	

      $prevGroupId = $prevHeadId = ''; 

      if(!empty($list_values))

      foreach($list_values as $key => $item){

        $chkGroupId[] = $item->GroupId;

        $chkHeadId[] = $item->HeadId;

        $chkAccountId[] = $item->AccountId;

        $list_values2[$key] = $item;

        if($prevHeadId != $item->HeadId && $prevHeadId != ''){

          // get list of missing account and account head



          $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0',$prevHeadId);						

        } //end if

        if($prevGroupId != $item->GroupId && $prevGroupId != ''){

          //get list of missing account and account head				

          $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,$prevGroupId,'0');						

        } //end if



        //get opening balance

        /*              $openingbalance = getOpeningBalForAcc($item->AccountId,'','');

                        $item->openDebit = $openingbalance['debit'];				

                        $item->openCredit = $openingbalance['credit'];					*/

        $prevHeadId = $item->HeadId ;

        $prevGroupId =  $item->GroupId;

        $prevAccountId = $item->AccountId;





      } //end foreach



      $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0',$prevHeadId);						

      $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,$prevGroupId,'0');						

      $this->accountList($search_accGrp,$search_Acc,$chkGroupId,$chkHeadId,$chkAccountId,$list_values2,'0','0');						

      return $list_values2;	

    }//end function





  function accountList($search_accGrp,$search_acc,&$chkGroupId,&$chkHeadId,&$chkAccountId,&$list_values2,$accGrpId,$accHeadId){

//    $list_values = array();

    // system checks 

    if($search_accGrp != 0){

      $check_accGrp = " AND fm.nId = " . $search_accGrp . " ";

    }

    if($search_acc != 0){

      $check_acc = " AND aha.nId = " . $search_acc . " ";

    }



    if( $accHeadId == 0){

      if($accGrpId != 0)

        $extra_check_accGrp = " WHERE fm.nId = " . $accGrpId . " ";   

      else 

        $extra_check_accGrp = " WHERE 1=1 ";   

      $accGrpQuery = "SELECT ah.nAId, ah.vAcname, ah.vHCode, fm.nId, fm.vCode as groupCode, fm.vTitle as groupTitle 

                FROM {PRE}AccountHead ah 

                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup "

                . $extra_check_accGrp .  $check_accGrp  . "  ORDER BY nId, nAId ASC ";



      $accGrpQueryList     = $this->db->query($accGrpQuery);

      if ($accGrpQueryList->num_rows() > 0)

      {

        foreach ($accGrpQueryList->result() as $key_g=>$row_g)

        {

          if(!in_array($row_h->nAId,$chkHeadId)){

            $accHeadQuery = " SELECT aha.vTitle as AccountName, aha.vACode as AccountCode, aha.nId as AccountId ,

                ah.nAId as HeadId, ah.vAcname as HeadName, ah.vHCode as HeadCode,

                fm.nId as GroupId, fm.vCode as GroupCode, fm.vTitle as GroupName,

                0 as openDebit, 0 as openCredit

                FROM {PRE}AccountHeadAccounts aha 

                LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId 

                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup

                WHERE ah.nAId = " . $row_g->nAId . " " . $check_acc . " ORDER BY HeadId, AccountId ASC ";

            $accHeadQueryList     = $this->db->query($accHeadQuery);

            if ($accHeadQueryList->num_rows() > 0)

            {

              foreach ($accHeadQueryList->result() as $key_h=>$row_h)

              {

                if(!in_array($row_h->nId,$chkAccountId)){

                  // insert the account head in the list

                  $openingbalance = $this->getOpeningBalForAcc($row_h->AccountId,'','');

                  $row_h->openDebit = $openingbalance['debit'];				

                  $row_h->openCredit = $openingbalance['credit'];					

                  $chkGroupId[] = $row_h->GroupId;

                  $chkHeadId[] = $row_h->HeadId;

                  $chkAccountId[] = $row_h->AccountId;

                  if($row_h->openDebit != 0 || $row_h->openCredit != 0)

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

 

      $accHeadQuery = " SELECT aha.vTitle as AccountName, aha.vACode as AccountCode, aha.nId as AccountId ,  

                ah.nAId as HeadId, ah.vAcname as HeadName, ah.vHCode as HeadCode, 

                fm.nId as GroupId, fm.vCode as GroupCode, fm.vTitle as GroupName,

                0 as openDebit, 0 as openCredit

                FROM {PRE}AccountHeadAccounts aha 

                LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup "

                . $extra_check_head . " " . $check_acc . " ORDER BY HeadId, AccountId ASC ";

      $accHeadQueryList     = $this->db->query($accHeadQuery);

      if ($accHeadQueryList->num_rows() > 0)

      {

        foreach ($accHeadQueryList->result() as $key_h=>$row_h)

        {

          if(!in_array($row_h->nId,$chkAccountId)){

            // insert the account head in the list

                  $openingbalance = $this->getOpeningBalForAcc($row_h->AccountId,'','');

                  $row_h->openDebit = $openingbalance['debit'];				

                  $row_h->openCredit = $openingbalance['credit'];					

                  $chkGroupId[] = $row_h->GroupId;

                  $chkHeadId[] = $row_h->HeadId;

                  $chkAccountId[] = $row_h->AccountId;

                  if($row_h->openDebit != 0 || $row_h->openCredit != 0)

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



      if ($this->ddlLocation != 0 && $this->ddlLocation != 0) {

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

        if ($this->ddlLocation != 0 ) {

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



        if ($this->ddlLocation2 != 0 ) {

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

        if(!empty($data))

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

          if ($this->ddlLocation2 != '0' ) {

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





        return ($opening_balance);



    }//end function
	function getOpeningBalForAcc1($account,$subacc,$table)

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



      //  $group_select =" SELECT YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";

       // $groupby = "  ) as temp GROUP BY YEAR(date), account,subacc ";
$group_select =" SELECT location , COUNT(*) as count,
                             account as AccountId,subacc,IF(subacc = 0, '', vTable) as vTable, SUM(debit) as debit ,SUM(credit) as credit, (SELECT nHAId FROM {PRE}AccountHeadAccounts WHERE nId = account) as HeadId 

                             FROM ( ";

        $groupby = "  ) as temp GROUP BY AccountId ASC";

        $orderby =  "  ORDER BY HeadId,AccountId, subacc ASC";
    

        $data = array();

        $data = $this->getReports1($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$open_cr_top_acc,$open_cp_top_acc,$open_bb_top_acc,$open_bp_top_acc,$open_ac_top_acc,$open_ad_top_acc,$orderby,$group_select, $groupby);



        $opening_balance['debit'] = 0;

        $opening_balance['credit'] = 0;

        if(!empty($data))

        foreach ($data as $key => $item){

          $opening_balance['debit'] += $item->debit;

          $opening_balance['credit'] += $item->credit;
		  if($item->AccountId=='23'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit23'] += $debit;

                 $opening_balance['credit23'] += $credit;
				}
				if($item->AccountId=='25'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit25'] += $debit;

                 $opening_balance['credit25'] += $credit;
				}
				if($item->AccountId=='26'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit26'] += $debit;

                 $opening_balance['credit26'] += $credit;
				}
				if($item->AccountId=='27'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit27'] += $debit;

                 $opening_balance['credit27'] += $credit;
				}
				if($item->AccountId=='24'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['tdebit'] += $debit;

                 $opening_balance['ycredit'] += $credit;
				}
				if($item->AccountId=='33'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit33'] += $debit;

                 $opening_balance['credit33'] += $credit;
				}
				if($item->AccountId=='52'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit52'] += $debit;

                 $opening_balance['credit52'] += $credit;
				}
				if($item->AccountId=='53'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit53'] += $debit;

                 $opening_balance['credit53'] += $credit;
				}
				if($item->AccountId=='54'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit54'] += $debit;

                 $opening_balance['credit54'] += $credit;
				}
				if($item->AccountId=='55'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit55'] += $debit;

                 $opening_balance['credit55'] += $credit;
				}
				if($item->AccountId=='56'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit56'] += $debit;

                 $opening_balance['credit56'] += $credit;
				}
				if($item->AccountId=='57'){
					
					 $debit  =  $item->debit;
					 $credit  = $item->credit;
										
                $opening_balance['debit57'] += $debit;

                 $opening_balance['credit57'] += $credit;
				}

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

          if ($this->ddlLocation2 != '0' ) {

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

//echo $sj_query;

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
	
	
	function getReports1($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby)

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

        $list = "('23','24','25','26','27','28','33','38','41','52','53','54','56','57','61','63')";



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

//echo $sj_query;

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

              echo "<pre>".$adminGroupListQuery . "</pre>";

              //return;





        session_start();

        $_SESSION['sess_query'] = $adminGroupListQuery;

        $adminGroupList 		= $this->db->query($adminGroupListQuery);

        $adminGroupRow = array();

        if ($adminGroupList->num_rows() > 0)

        {

            
			
			foreach ($adminGroupList->result() as $key=>$row)

            { $adminGroupRow[] = $row;
			if(empty($row->debit)){
				$row->debit=0;
				
			}	
			if(empty($row->credit)){
							$row->credit=0;
							
						}				
 
				if($row->AccountId=='23'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit23'] += $debit;

                 $adminGroupRow['credit23'] += $credit;
				}
				if($row->AccountId=='25'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit25'] += $debit;

                 $adminGroupRow['credit25'] += $credit;
				}
				if($row->AccountId=='26'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit26'] += $debit;

                 $adminGroupRow['credit26'] += $credit;
				}
				if($row->AccountId=='27'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit27'] += $debit;

                 $adminGroupRow['credit27'] += $credit;
				}
				if($row->AccountId=='24'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['tdebit'] += $debit;

                 $adminGroupRow['ycredit'] += $credit;
				}
				if($row->AccountId=='33'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit33'] += $debit;

                 $adminGroupRow['credit33'] += $credit;
				}
				if($row->AccountId=='52'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit52'] += $debit;

                 $adminGroupRow['credit52'] += $credit;
				}
				if($row->AccountId=='53'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit53'] += $debit;

                 $adminGroupRow['credit53'] += $credit;
				}
				if($row->AccountId=='54'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit54'] += $debit;

                 $adminGroupRow['credit54'] += $credit;
				}
				if($row->AccountId=='55'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit55'] += $debit;

                 $adminGroupRow['credit55'] += $credit;
				}
				if($row->AccountId=='56'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit56'] += $debit;

                 $adminGroupRow['credit56'] += $credit;
				}
				if($row->AccountId=='57'){
					
					 $debit  =  $row->debit;
					 $credit  = $row->credit;
										
                $adminGroupRow['debit57'] += $debit;

                 $adminGroupRow['credit57'] += $credit;
				}
				
            }//end for each

			  

        } //end if
		//echo $adminGroupRow['tdebit'] ;

        return ($adminGroupRow);





    }//end function







/*



    function getOpeningBalForAcc($location,$account,$subacc,$table,$dt)

    {

      $this->txtFromDate =  $dt; //date('Y-m-d',strtotime($this->txtFromDate));

      $curdate            = date('Y-m-d',strtotime($this->txtFromDate));

      $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));



      $this->ddlSubAccount = $subacc;

      $this->ddlAccount = $account;

      $this->ddlLocation = $location;

      $this->tableType = $table;



      if( $this->ddlAccount == '0'){



        $temp =  $this->ddlAccount;

        $this->ddlAccount =  $this->ddlSubAccount;

        $this->ddlSubAccount = $temp;



      }

      $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";

      $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";

      $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";

      $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";

      $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";

      $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";

      $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";

      $extraCheck_sj .= " sj.nLocation='".$this->ddlLocation."'";

      $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";

      $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";



      if (!empty($this->ddlAccount)) {

        if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):

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

        $closeId        = $this->ddlAccount.'-'.$this->tableType;

        $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'

          OR sjd.vClosedId LIKE '%".$closeId."%')";



        if (!empty($this->ddlSubAccount)):

          $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";

        $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";

        $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";

        endif;

        endif;

      }//end if



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



        $open_cr = $extraCheck_cr.$openBal_cr_dt;

        $open_cp = $extraCheck_cp.$openBal_cp_dt;

        $open_bb = $extraCheck_bb.$openBal_bb_dt;

        $open_bp = $extraCheck_bp.$openBal_bp_dt;

        $open_jv = $extraCheck_jv.$openBal_jv_dt;

        $open_cv = $extraCheck_cv.$openBal_cv_dt;

        $open_dv = $extraCheck_dv.$openBal_dv_dt;

        $open_sj = $extraCheck_sj.$openBal_sj_dt;

        $open_ac = $extraCheck_ac.$openBal_ac_dt;

        $open_ad = $extraCheck_ad.$openBal_ad_dt;





        $open_list_value = $this->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);

        return ($open_list_value);



    }





    function getOpeningBalance($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special)

    {

        if(trim($this->txtFromDate) == ''):

            $debit = $credit = '';

        else:



              $extraCheck_cr .= " AND YEAR(cr.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_cp .= " AND YEAR(cp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_bb .= " AND YEAR(bb.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_bp .= " AND YEAR(bp.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_jv .= " AND YEAR(jv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_cv .= " AND YEAR(cv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_dv .= " AND YEAR(dv.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_sj .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_ac .= " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

              $extraCheck_ad .= " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";



              $extraCheck_obal = "";



              if(!($this->ddlAccount == '' ||  $this->ddlAccount == '0')){

                $extraCheck_obal .= " AND obd.vACode = '" . $this->ddlAccount. "' ";

                if(!( $this->ddlSubAccount  == '' ||  $this->ddlSubAccount  == '0'))

                  $extraCheck_obal .= " AND obd.vAcSubCode = '" . $this->ddlSubAccount . "' ";

              }





            $adminGroupListQuery  = "(SELECT SUM(sjd.nDebit) as credit,SUM(sjd.nCredit) as debit,CASE

                                           WHEN sjd.vType = 'Tkt' THEN

                                                    (SELECT nAcc FROM {PRE}TicketFareDetails WHERE

                                                            nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')

                                           WHEN sjd.vType = 'Htl' THEN

                                                    (SELECT nAcCode FROM {PRE}HotelVoucher WHERE

                                                            nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')

                                           ELSE

                                                   (SELECT nPassengerAC FROM {PRE}XoBooking WHERE

                                                            nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode FROM {PRE}SystemJv sj

                                          LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." GROUP BY sj.nId ".$extraCheck_sj_special.")

										  UNION ALL (SELECT SUM(CASE WHEN crd.vMode = 'Cr' THEN crd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN crd.vMode = 'Dr' THEN crd.nAmount ELSE 0 END) as debit,'' as subCode FROM

										  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'

										  ".$extraCheck_cr." GROUP BY cr.nCRId)

										  UNION ALL (SELECT SUM(CASE WHEN cpd.vMode = 'Cr' THEN cpd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN cpd.vMode = 'Dr' THEN cpd.nAmount ELSE 0 END) as debit,'' as subCode FROM {PRE}CashPayment cp

										  LEFT JOIN {PRE}CashPaymentDetails cpd ON

										  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)

										  UNION ALL (SELECT SUM(CASE WHEN bbd.vMode = 'Dr' THEN bbd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN bbd.vMode = 'Cr' THEN bbd.nAmount ELSE 0 END) as debit,'' as subCode FROM {PRE}BankBook bb

										  LEFT JOIN {PRE}BankBookDetails bbd ON

										  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)

										  UNION ALL (SELECT SUM(CASE WHEN bpd.vMode = 'Dr' THEN bpd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN bpd.vMode = 'Cr' THEN bpd.nAmount ELSE 0 END) as debit,'' as subCode FROM {PRE}BankBookPayment

										  bp LEFT JOIN {PRE}BankBookPaymentDetails

										  bpd ON

										  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)

										  UNION ALL (SELECT SUM(CASE WHEN jvd.vMode = 'Dr' THEN jvd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN jvd.vMode = 'Cr' THEN jvd.nAmount ELSE 0 END) as debit,'' as subCode FROM {PRE}Jv jv LEFT JOIN

										  {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId

										  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)

										  UNION ALL (SELECT SUM(CASE WHEN cvd.vMode = 'Dr' THEN cvd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN cvd.vMode = 'Cr' THEN cvd.nAmount ELSE 0 END) as debit,'' as subCode FROM

										  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd

										  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)

										  UNION ALL (SELECT SUM(CASE WHEN dvd.vMode = 'Dr' THEN dvd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN dvd.vMode = 'Cr' THEN dvd.nAmount ELSE 0 END) as debit,'' as subCode FROM

										  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON

										  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)

										  UNION ALL (SELECT SUM(CASE WHEN acd.vMode = 'Dr' THEN acd.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN acd.vMode = 'Cr' THEN acd.nAmount ELSE 0 END) as debit,'' as subCode FROM

										  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON

										  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId)

										  UNION ALL (SELECT SUM(CASE WHEN ade.vMode = 'Dr' THEN ade.nAmount ELSE 0	END) as credit,

                                          SUM(CASE WHEN ade.vMode = 'Cr' THEN ade.nAmount ELSE 0 END) as debit,'' as subCode FROM

										  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON

										  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId)

                      UNION ALL ( SELECT obd.vBalDebit as credit, obd.vBalCredit as debit,'' as subCode

                          FROM {PRE}OpeningBalance ob LEFT JOIN {PRE}OpeningBalanceDetails obd ON ob.nOBId = obd.nBalId

                          WHERE YEAR(ob.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."') AND ob.nDivision = '".$this->ddlDivision."'

                          AND ( ob.vLocation='".$this->ddlLocation."' OR ob.vLocation='".$this->ddlLocation2."')" . $extraCheck_obal . " )";



//echo $adminGroupListQuery . "<br><br><br><br>"; 

            session_start();

            $credit = 0;

            $debit = 0;

            $_SESSION['sess_opening'] = $adminGroupListQuery;

            $adminGroupList 		= $this->db->query($adminGroupListQuery);

            if ($adminGroupList->num_rows() > 0)

            {

              foreach ($adminGroupList->result() as $key=>$row)

              {

                $credit   += $row->debit;

                $debit  += $row->credit;

              }//end for each

            } //end if



            endif;

            $adminGroupRow['debit']  = $debit;

            $adminGroupRow['credit'] = $credit;



            return ($adminGroupRow);

    }//end function



*/

function getAccValues_123($accId)

    {

        $accValues = array();
if($accId==23 ||$accId==26 || $accId==54 ||$accId==9){
	$ec="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cr.vAmountDC as debit,'' as credit,cr.vTransMode as mode

                                    FROM {PRE}CashReceipt cr 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

								UNION ALL
								(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									crd.nAmount as debit,'' as credit,crd.vMode as mode

                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR

                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')";
}else{
	$ec="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cr.vAmountDC as debit,'' as credit,cr.vTransMode as mode

                                    FROM {PRE}CashReceipt cr 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

								UNION ALL
								(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									crd.nAmount as debit,'' as credit,crd.vMode as mode

                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.vACode = '".$accId."'AND crd.nCashId = cr.nCRId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR

                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')";
									 
									 
									 
									 
}
if($accId==52 || $accId==54){
	$bp="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                    bpd.nAmount as debit,'' as credit,CASE

											WHEN bpd.vAcSubCode = aha.nId THEN

										 		bpd.vMode

										    WHEN bpd.vACode = aha.nId AND bpd.vTable = 'A' THEN

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

                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')";
}
else{
	$bp="(SELECT  fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                    CASE WHEN bpd.vACode = '".$accId."' then bpd.nAmount
										else  bp.vAmountDC
										end as debit,'' as credit,CASE

											WHEN bpd.vAcSubCode = aha.nId THEN

										 		bpd.vMode

										    WHEN bpd.vACode = aha.nId AND bpd.vTable = 'A' THEN

										 		bpd.vMode

										    WHEN bp.nAcCode = aha.nId THEN

										 		bp.vTransMode

										END as mode

                                    FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails

                                       bpd ON  bpd.nBankPaymentId = bp.nBPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable = 'A'

                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')";
}

if($accId==24 || $accId==33 || $accId==53 ||$accId==54 ){
	$cp="



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cp.vAmountDC as debit,'' as credit,


										CASE

										    WHEN cp.nAcCode = aha.nId THEN

										 		cp.vTransMode

										END as mode
                                    FROM {PRE}CashPayment cp  LEFT JOIN {PRE}AccountHeadAccounts aha ON (cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')
									 
									 UNION ALL



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										cpd.nAmount as debit,'' as credit,cpd.vMode as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'

                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')";
}else{
	$cp="



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cp.vAmountDC as debit,'' as credit,


										CASE

										    WHEN cp.nAcCode = aha.nId THEN

										 		cp.vTransMode

										END as mode
                                    FROM {PRE}CashPayment cp  LEFT JOIN {PRE}AccountHeadAccounts aha ON (cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')
									 
									 UNION ALL



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										cpd.nAmount as debit,'' as credit,cpd.vMode as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.vACode = '".$accId."' AND cpd.nCashPId = cp.nCPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'

                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')";
}
if($accId==26 || $accId==33 ){
	$bd="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,

										 		bb.vTransMode as mode

                                    FROM {PRE}BankBook bb 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
									 
									 
									 
									 UNION ALL



                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode

                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'

                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')";
}else{
	$bd="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,

										 		bb.vTransMode as mode

                                    FROM {PRE}BankBook bb 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
									 
									 
									 
									 UNION ALL



                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode

                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.vACode = '".$accId."' AND bbd.nBankbookId = bb.nBBId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'

                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')



                                     ";
}

       $adminGroupListQuery  = $ec."UNION ALL".$cp."



                                     UNION ALL".$bd."UNION ALL".$bp.



                                      
                                     "UNION ALL



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

										    WHEN acd.vACode = aha.nId AND acd.vTable = 'A' THEN

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

										    WHEN ade.vACode = aha.nId  AND ade.vTable = 'A' THEN

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


//print $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);   //echo $this->db->last_query().'<br><br>';
//var_dump($adminGroupList);
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

function getAccValues_1234($accId)

    {
//echo $accId;
        $accValues = array();

	 $ec="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cr.vAmountDC as debit,'' as credit,cr.vTransMode as mode

                                    FROM {PRE}CashReceipt cr 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')

								UNION ALL
								(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                   ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									crd.nAmount as debit,'' as credit,crd.vMode as mode

                                    FROM {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.vACode = '".$accId."'AND crd.nCashId = cr.nCRId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (crd.vACode = aha.nId AND crd.vTable='A' OR

                                      crd.vAcSubCode = aha.nId OR cr.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cr." AND aha.nId = '".$accId."')";
									 
									 
									 
									 

	
	$bp="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bp.vAmountDC as debit,'' as credit,
 CASE WHEN bp.nAcCode = aha.nId THEN bp.vTransMode END as mode FROM tbl_BankBookPayment bp LEFT JOIN tbl_AccountHeadAccounts aha ON (bp.nAcCode = aha.nId) LEFT JOIN tbl_AccountHead ah ON ah.nAId = aha.nHAId LEFT JOIN tbl_FinanceModule fm ON ah.nAGroup = fm.nId WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1' 
 
 AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')

UNION ALL (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										bpd.nAmount as debit,'' as credit,bpd.vMode as mode
                                    FROM tbl_BankBookPayment bp LEFT JOIN tbl_BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId AND bpd.vACode ='".$accId."'

                                      LEFT JOIN tbl_AccountHeadAccounts aha ON (bpd.vACode = aha.nId AND bpd.vTable='A'

                                      OR bpd.vAcSubCode = aha.nId OR bp.nAcCode = aha.nId)

                                               LEFT JOIN tbl_AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN tbl_FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1' 
									  
									 
 AND ah.vActive = '1' ".$this->extraCheck_bp." AND aha.nId = '".$accId."')";



	$cp="



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

									cp.vAmountDC as debit,'' as credit,


										CASE

										    WHEN cp.nAcCode = aha.nId THEN

										 		cp.vTransMode

										END as mode
                                    FROM {PRE}CashPayment cp  LEFT JOIN {PRE}AccountHeadAccounts aha ON (cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')
									 
									 UNION ALL



                                  (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName, ah.vHCode as HeadCode,ah.nAId as HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,


										cpd.nAmount as debit,'' as credit,cpd.vMode as mode
                                    FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.vACode = '".$accId."' AND cpd.nCashPId = cp.nCPId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (cpd.vACode = aha.nId AND cpd.vTable='A'

                                      OR cpd.vAcSubCode = aha.nId OR cp.nAcCode = aha.nId)

                                               LEFT JOIN {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_cp." AND aha.nId = '".$accId."')";

	$bd="(SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId, bb.vAmountDC as debit,'' as credit,

										 		bb.vTransMode as mode

                                    FROM {PRE}BankBook bb 

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')
									 
									 
									 
									 UNION ALL



                                        (SELECT fm.vTitle as GroupName,fm.vCode as GroupCode,fm.nId as GroupId,ah.vAcname as HeadName,

                                        ah.vHCode  as HeadCode,ah.nAId as

                                    HeadId,aha.vTitle as AccountName,aha.vACode as AccountCode,aha.nId as AccountId,

                                     bbd.nAmount as debit,'' as credit,	bbd.vMode as mode

                                    FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.vACode = '".$accId."' AND bbd.nBankbookId = bb.nBBId

                                      LEFT JOIN {PRE}AccountHeadAccounts aha ON (bbd.vACode = aha.nId AND bbd.vTable = 'A'

                                      OR bbd.vAcSubCode = aha.nId OR bb.nAcCode = aha.nId) LEFT JOIN

                                      {PRE}AccountHead ah ON ah.nAId = aha.nHAId

                                      LEFT JOIN {PRE}FinanceModule fm ON ah.nAGroup = fm.nId

                                      WHERE fm.nParent != '0' AND fm.vLevel = '2' AND fm.vActive = '1'

                                     AND ah.vActive = '1' ".$this->extraCheck_bb." AND aha.nId = '".$accId."')



                                     ";


       $adminGroupListQuery  = $ec."UNION ALL".$cp."



                                     UNION ALL".$bd."UNION ALL".$bp.



                                      
                                     "UNION ALL



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

										    WHEN acd.vACode = aha.nId AND acd.vTable = 'A' THEN

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

										    WHEN ade.vACode = aha.nId  AND ade.vTable = 'A' THEN

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


 if($accId==228){
	 //print $adminGroupListQuery; 
 }

        $adminGroupList 		= $this->db->query($adminGroupListQuery);   //echo $this->db->last_query().'<br><br>';
//var_dump($adminGroupList);
        if ($adminGroupList->num_rows() > 0)

        {

            foreach ($adminGroupList->result() as $key=>$row)

            {

                switch($row->mode)

                {

                    case "Dr":
					
                       $debit  = $row->debit;
						//echo  $debit."  ";
                        $credit = '';

                        break;
 


                    case "Cr":

                        $debit  = '';
						$credit = $row->debit;
						if($row->AccountId==8){
							//echo "<br>".$credit = $row->debit;
						}
                   break;



                    default:

                        $debit  = ($row->debit==' ')?'':$row->debit;

                        $credit = ($row->credit==' ')?'':$row->credit;

                        break;

                }



                $accValues['debit'] += $debit;
				$accValues['credit'] += $credit;
if($row->AccountId==8){
              // echo $accValues['credit'] ;
			   }

            }//end for each

        } //end if

        return ($accValues);

    }//end function





}//end class

?>

