<?php
class Ac_report_model extends CI_Model {

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
        $locGroupRow[0] = NOVALUE;
        return($locGroupRow);
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

    function getActiveSubAccounts()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = NOVALUE;
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

    function getAllFinanceMainAccounts()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
										  WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' OR ahc.vSubSale = 'C' ORDER BY nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[] = $row->ahaId;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[] = 0;
        }//end else
        return ($typeGroupRow);
    }//end function

    function getDetailReportFilter($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";

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

    function getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode
                                           FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL
                                  (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,crd.nAmount as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr.")
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cpd.nAmount as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bbd.nAmount as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bpd.nAmount as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";

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

    function getDailyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";
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

    function getWeeklyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";
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

    function getMonthlyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";
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

    function getYearlyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY date ASC";
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

    function getSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.vTable,sjd.nAccHeadId as subacc,SUM(sjd.nDebit) as debit,'' as balance,SUM(sjd.nCredit)
                                  as credit,'' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,
                                  sjd.vNumber as vNumber,sjd.vType as vType,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as subCode
                                                    FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj."
                                  GROUP BY sj.nId ".$extraCheck_sj_special.")
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  crd.vACode as account,crd.vTable,crd.vAcSubCode as subacc,SUM(crd.nAmount) as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,crd.vMode as mode,crd.nId as detId,
                                  '' as vNumber,'' as vType,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cpd.vACode as account,cpd.vTable,cpd.vAcSubCode as subacc,SUM(cpd.nAmount) as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,cpd.vMode as mode,cpd.nId as detId,
                                  '' as vNumber,'' as vType,cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode FROM {PRE}CashPayment cp LEFT
                                  JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bbd.vACode as account,bbd.vTable,bbd.vAcSubCode as subacc,SUM(bbd.nAmount) as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,bbd.vMode as mode,bbd.nId as detId,
                                  '' as vNumber,'' as vType,bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bpd.vACode as account,bpd.vTable,bpd.vAcSubCode as subacc,SUM(bpd.nAmount) as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,bpd.vMode as mode,bpd.nId as detId,
                                  '' as vNumber,'' as vType,bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode FROM {PRE}BankBookPayment
                                  bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,jvd.vACode as account,jvd.vTable,jvd.vAcSubCode as subacc,SUM(jvd.nAmount) as debit,
                                  jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,
                                  '' as vNumber,'' as vType,'' as mainMode,'' as mainAmount,'' as subCode
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,cvd.vACode as account,cvd.vTable,cvd.vAcSubCode as subacc,SUM(cvd.nAmount) as debit,
                                  cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,
                                  '' as vNumber,'' as vType,'' as mainMode,'' as mainAmount,'' as subCode FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,dvd.vACode as account,dvd.vTable,dvd.vAcSubCode as subacc,SUM(dvd.nAmount) as debit,
                                  dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,
                                  '' as vNumber,'' as vType,'' as mainMode,'' as mainAmount,'' as subCode FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,acd.vACode as account,acd.vTable,acd.vAcSubCode as subacc,SUM(acd.nAmount) as debit,
                                  '' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,
                                  '' as vNumber,'' as vType,'' as mainMode,'' as mainAmount,'' as subCode FROM
                                  {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ade.vACode as account,ade.vTable,ade.vAcSubCode as subacc,SUM(ade.nAmount) as debit,
                                  '' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,
                                  '' as vNumber,'' as vType,'' as mainMode,'' as mainAmount,'' as subCode FROM
                                  {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId) ORDER BY subacc DESC";
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

    function getSummaryReportFilter($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special)
    {
        $adminGroupListQuery  = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
                                  sjd.nDrAccount as account,sjd.nAccHeadId as subacc,sjd.nDebit as debit,'' as balance,sjd.nCredit as credit,
                                  '' as issued,sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
                                  sjd.vNumber as vNumber,'' as mainMode,'' as mainAmount,CASE
                                   WHEN sjd.vType = 'Tkt' THEN
                                            (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                                                    nTicketId = sjd.vNumber AND nAcc = '".$this->ddlSubAccount."')
                                   WHEN sjd.vType = 'Htl' THEN
                                            (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                                                    nHVId = sjd.vNumber AND nAcCode = '".$this->ddlSubAccount."')
                                   ELSE
                                           (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                                                    nXId = sjd.vNumber AND nPassengerAC = '".$this->ddlSubAccount."') END as
                                           subCode FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
                                  UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.vAmountFC as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName,
                                  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                   ELSE crd.vMode END as mode,crd.nId as detId,
                                  '' as vType,'' as vNumber,cr.vTransMode as mainMode,cr.vAmountDC as mainAmount,'' as subCode FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.vAmountFC as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName,
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,cpd.nId as detId,'' as vType,'' as vNumber,
                                  cp.vTransMode as mainMode,cp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac.")
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad.") ORDER BY subacc DESC";
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

    //calculate next debit
    function getCalDebit($deb,$cred)
    {
        $deb2 = ($this->session->userdata('newDebit') == '')?'':$this->session->userdata('newDebit');
        $deb3 = round($deb2,2) + (round($deb,2) - round($cred,2));

        $this->session->set_userdata('newDebit',$deb3);

        return ($deb3);
    }//end function

    function getOpeningBalForAcc($location,$account,$subacc,$date)
    {
      $this->txtFromDate =  $date; //date('Y-m-d',strtotime($this->txtFromDate));
      $curdate            = date('Y-m-d',strtotime($this->txtFromDate));
      $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));


      $this->ddlSubAccount = $subacc;
      $this->ddlAccount = $account;
      $this->ddlLocation = $location;

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

//extra chech for year
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
                          WHERE ob.vActive = '1' AND YEAR(ob.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."') AND ob.nDivision = '".$this->ddlDivision."' 
                          AND ( ob.vLocation='".$this->ddlLocation."' OR ob.vLocation='".$this->ddlLocation2."')" . $extraCheck_obal . " )";


// ob.vSubLedger = '" . $this->tableType ."'

echo $adminGroupListQuery . " <br><br><br><br>";

//return;
            session_start();
            $debit = 0;
            $credit = 0;
            $_SESSION['sess_opening'] = $adminGroupListQuery;
            $adminGroupList 		= $this->db->query($adminGroupListQuery);
            if ($adminGroupList->num_rows() > 0)
            {
                foreach ($adminGroupList->result() as $key=>$row)
                {
                    $credit += $row->debit;
                    $debit  += $row->credit;
                }//end for each
            } //end if

        endif;
        $adminGroupRow['debit']  = $debit;
        $adminGroupRow['credit'] = $credit;


        return ($adminGroupRow);
    }//end function

    function getYearClosing()
    {
        $debit = $credit = '';
        $adminGroupListQuery = "SELECT SUM(ycd.vDCBal) as DCTot,SUM(ycd.vFCBal) as FCTot FROM {PRE}YearClosing yc LEFT JOIN {PRE}YearClosingDetails ycd ON yc.nYCId = ycd.nYearCId
                                            WHERE yc.vFYear <= YEAR(CURDATE()) OR yc.vTYear>= YEAR(CURDATE()) GROUP BY ycd.nYearCId";
        session_start();
        $_SESSION['sess_year'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $debit   += $row->DCTot;
                $credit  += $row->FCTot;
            }//end for each
        } //end if

        $adminGroupRow['debit']  = $debit;
        $adminGroupRow['credit'] = $credit;

        return ($adminGroupRow);
    }

    //fucntion to get number of weeks
    function getNoWeeks($from,$to)
    {
        $startDateUnix = strtotime($from);
        $endDateUnix = strtotime($to);

        $weekNumbers = array();
        $startDate   = array();
        $newArray    = array();
        $key         = 1;

        while ($startDateUnix < $endDateUnix)
        {
            $startDate[]   = date('Y-m-d',$startDateUnix);
            $startDateUnix2 = strtotime('+7 days', $startDateUnix);
            if(date('M',$startDateUnix)==date('M',$startDateUnix2))
            {
                $startDateUnix = strtotime('+7 days', $startDateUnix);
                $weekNumbers[] = date('M',$startDateUnix).' - '.$key;
            }
            else{
                $key         = 1;
                $startDateUnix = strtotime('+7 days', $startDateUnix);
                $weekNumbers[] = date('M',$startDateUnix).' - '.$key;
            }
            $key++;
        }

        $newArray['weeknos']   = $weekNumbers;
        $newArray['startDate'] = $startDate;

        return ($newArray);
    }//end function

    //fucntion to get number of weeks
    function getNoMonth($from,$to)
    {
        $startDateUnix = strtotime($from);
        $endDateUnix = strtotime($to);

        $currentDateUnix = $startDateUnix;

        $monthNumbers = array();
        $startDate   = array();
        $newArray    = array();

        while ($currentDateUnix < $endDateUnix)
        {
            $startDate[]   = date('Y-m-d',$currentDateUnix);
            $monthNumbers[] = date('M', $currentDateUnix);
            $currentDateUnix = strtotime('+1 month', $currentDateUnix);
        }

        $newArray['monthnos']   = $monthNumbers;
        $newArray['startDate'] = $startDate;

        return ($newArray);
    }//end function

    //fucntion to get number of weeks
    function getNoYears($from,$to)
    {
        $startDateUnix = strtotime($from);
        $endDateUnix = strtotime($to);

        $currentDateUnix = $startDateUnix;

        $yearNumbers = array();
        $startDate   = array();
        $newArray    = array();

        while ($currentDateUnix < $endDateUnix)
        {
            $startDate[]   = date('Y-m-d',$currentDateUnix);
            $yearNumbers[] = date('Y', $currentDateUnix);
            $currentDateUnix = strtotime('+1 year', $currentDateUnix);
        }

        $newArray['yearnos']   = $yearNumbers;
        $newArray['startDate'] = $startDate;

        return ($newArray);
    }//end function

    //get count, credit n debit
    function getWeekCntCreditDebit($stype,$sDate,$eDate,$list_value)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        foreach($list_value as $key=>$item):
            if($item->date >= $sDate && $item->date < $eDate):
                switch($item->tblName)
                {
                    case "CR":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "CP":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BR":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BP":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "JV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "SJ":
                        $salesType   = SALESENTRY;
                        break;

                    case "CV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "DV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ACM":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ADM":
                        $salesType   = FINANCEENTRY;
                        break;
                }//end switch

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
                }

                if($stype==$salesType)
                {
                    $cnt++;
                    $wcredit += $credit;
                    $wdebit  += $debit;
                }
            endif;endforeach;

        $valArray['cnt']     = $cnt;
        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;
        $openDebit           = $wdebit-$wcredit;

        if($openDebit > 0):
            $valArray['openCredit'] = 0;
            $valArray['openDebit']  = $openDebit;
        else:
            $valArray['openDebit'] = 0;
            $valArray['openCredit'] = substr($openDebit,1);
        endif;

        return ($valArray);
    }//end function

    //get count, credit n debit
    function getDailyCntCreditDebit($stype,$sDate,$list_value)
    {
        $cnt = $wdebit = $wcredit = 0;
        $valArray = array();

        $this->session->unset_userdata('newDebit');
        foreach($list_value as $key=>$item):
            if($item->date == $sDate):
                switch($item->tblName)
                {
                    case "CR":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "CP":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BR":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "BP":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "JV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "SJ":
                        $salesType   = SALESENTRY;
                        break;

                    case "CV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "DV":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ACM":
                        $salesType   = FINANCEENTRY;
                        break;

                    case "ADM":
                        $salesType   = FINANCEENTRY;
                        break;
                }//end switch

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
                }

                if($stype==$salesType)
                {
                    $cnt++;
                    $wcredit += $credit;
                    $wdebit  += $debit;
                }

            endif;endforeach;

        $valArray['cnt']     = $cnt;
        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;
        $openDebit           =  $wdebit-$wcredit;

        if($openDebit > 0):
            $valArray['openCredit'] = 0;
            $valArray['openDebit']  = $openDebit;
        else:
            $valArray['openDebit'] = 0;
            $valArray['openCredit'] = substr($openDebit,1);
        endif;

        return ($valArray);
    }//end function

    //get count, credit n debit
    function getSummaryCntCreditDebit($SubAccount,$list_value)
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
            }

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
                $cnt++;
                $wcredit += $credit;
                $wdebit  += $debit;
            endif;
        endforeach;

        $valArray['cnt']     = $cnt;
        $valArray['wcredit'] = $wcredit;
        $valArray['wdebit']  = $wdebit;
        $openDebit           = $wdebit-$wcredit;

        if($openDebit > 0):
            $valArray['openCredit'] = 0;
            $valArray['openDebit']  = $openDebit;
        else:
            $valArray['openDebit'] = 0;
            $valArray['openCredit'] = substr($openDebit,1);
        endif;

        return ($valArray);
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

}//end class

?>
