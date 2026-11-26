<?php
class Ac_report_model extends CI_Model {

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
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp."  GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId) ORDER BY date ASC";

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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY tableId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY tableId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY tableId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY tableId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY tableId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY tableId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY tableId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY tableId) ORDER BY date ASC";
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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY tableId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY tableId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY tableId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY tableId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY tableId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY tableId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY tableId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY tableId) ORDER BY date ASC";
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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY tableId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY tableId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY tableId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY tableId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY tableId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY tableId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY tableId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY tableId) ORDER BY date ASC";
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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY tableId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY tableId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY tableId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY tableId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY tableId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY tableId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY tableId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY tableId) ORDER BY date ASC";
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
echo $adminGroupListQuery;
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
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.vAmountFC as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName,CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                   ELSE bbd.vMode END as mode,bbd.nId as detId,'' as vType,'' as vNumber,
                                  bb.vTransMode as mainMode,bb.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY tableId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.vAmountFC as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName,CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                   ELSE bpd.vMode END as mode,bpd.nId as detId,'' as vType,'' as vNumber,
                                  bp.vTransMode as mainMode,bp.vAmountDC as mainAmount,'' as subCode
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY tableId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jvd.nAmount as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName,jvd.vMode as mode,jvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv."  GROUP BY tableId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cvd.nAmount as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName,cvd.vMode as mode,cvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv."  GROUP BY tableId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dvd.nAmount as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName,dvd.vMode as mode,dvd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv."  GROUP BY tableId)
                                  UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,'' as account,'' as subacc,acd.nAmount as debit,'' as balance,'' as credit,
                                  ac.vEnteredBy as issued,ac.nACId as tableId,'ACM' as tblName,acd.vMode as mode,acd.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac."  GROUP BY tableId)
                                  UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,'' as account,'' as subacc,ade.nAmount as debit,'' as balance,'' as credit,
                                  ad.vEnteredBy as issued,ad.nADId as tableId,'ADM' as tblName,ade.vMode as mode,ade.nId as detId,'' as vType,
                                  '' as vNumber,'' as mainMode,'' as mainAmount,'' as subCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON
                                  ade.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad."  GROUP BY tableId) ORDER BY subacc DESC";
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

      if ($this->ddlLocation != '' && $this->ddlLocation != '') {
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
        if ($this->ddlLocation != '' ) {
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

        if ($this->ddlLocation2 != '' ) {
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


    function getTktNumberValues($vType,$vNumber,$detId,$tableId,$drAcc,$vTable)
    {
      if($vNumber != 0){
        switch($vType)
        {
          case "Xo" :
            $tktnumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$vNumber."'");
          if($tktnumber == '') break;
          $newArray['desc'] =  "XO: " . $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$vNumber."'") . "  " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");
          $airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$vNumber."'");
          $newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;
          break;

          case "Tkt" :
            $tktnumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$vNumber."'");
          if($tktnumber == '') break;
          $newArray['desc'] =  "Tkt: ". $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$vNumber."'") . "  ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
          $airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$vNumber."'");
          $newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;
          break;

          case "Htl" :
            $tktnumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$vNumber."'");
          if($tktnumber == '') break;
          $cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$vNumber."'");
          $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");
          $newArray['desc'] = "Htl: " . $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$vNumber."'")."  " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");
          $newArray['tktVal']  = $tktnumber; 
          break;
        }//end switch
        if($tktnumber == ''){ 
          $newArray['pass'] = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
          $cityName       = $this->common_model->get_field_name('RefundRegisterDetails','nCity'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
          $hotel          = $this->common_model->get_field_name('RefundRegisterDetails','nHotel'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");
          if($cityName != '0' || $hotel != '0'){
            $newArray['desc'] = "Htl: " .$newArray['pass'] . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'");
          }
          else {
            $newArray['desc'] = $vType .": " . $newArray['pass'] ;
          }
          $newArray['tktVal'] =  $this->common_model->get_field_name('RefundRegisterDetails','vAirline', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'") ."-".$vNumber ;
        }
      }
      else{
        $sj_type = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$tableId."'" );
        if($sj_type == 'S')
          return $this->getTktNumberValues_old($detId,$tableId);
        else{
          $rrGroup = $this->db->query("SELECT rrd.vTktType, rrd.vNewOld,rcd.nAirline, rrd.vPassenger, rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd LEFT JOIN {PRE}RefundRegisterDetails rrd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = vType WHERE nRefundCloseId =".$tableId." AND nDrAcc = " . $drAcc . " AND vTable = '" .$vTable ."'");
          $newArray['tktVal'] = '';
          $newArray['desc'] = '';
          $i = 0;
          if ($rrGroup->num_rows() > 0)
          {
            foreach ($rrGroup->result() as $key => $row)
            {
              if($i !=0) {
                $newArray['tktVal'] .= "<br>";
                $newArray['desc'] .= "<br>";
              }
              if($row->vNewOld =='N')
              {
                switch($row->vTktType){
                  case 'Tkt': $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->nTicketNumber."'"); 
                              break;
                  case 'Htl' : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->nTicketNumber."'");
                               break;
                  case 'Xo' : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->nTicketNumber."'");
                              break;
                }// end switch
              } // end if
              else
                $tktNum = $row->nTicketNumber;
              $newArray['tktVal'] .=  $row->nAirline . "-" . $tktNum ;
              $newArray['desc'] .= $row->vPassenger  ;
              $i++;
            }//end foreach
          }//endif 
        }//end else
          
      }
      return $newArray;
    } //end functions




    function getTktNumberValues_old($detId,$tableId)
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

 /*

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
*/
                endif; 
//echo $salesCloseId . ' ----- ' .$row->vNumber . ' ----- ' .print_r($valDetArray) . "<br><br><br>";
            }//end for each
			
            $newArray['desc']   =  @join('<br>',$valDetArray);
            $newArray['tktVal'] =  @join('<br>',$valTktArray);
        }//end if
                  
        return ($newArray);
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

    function accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc){

      /*$adminGroupListQuery = "SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
        FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
        ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
        LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
        WHERE ah.vActive = '1' ORDER BY nId DESC";*/
      $check = '';
      if($search_acc != 0){
        $check = " WHERE aha.nId = " . $search_acc . " ";
      }

      $adminGroupListQuery = " SELECT aha.nId,aha.vTitle, aha.vACode, ahc.vSubLedgerType from {PRE}AccountHeadAccounts aha LEFT JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHid " . $check . " ";
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

            case "AG":
              if($search_subAcc != '0'){
                $check_sub = " AND nAId = " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nAId as tableId, vName, vCode FROM {PRE}Agent WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;

            case "E":
              if($search_subAcc != '0'){
                $check_sub = " AND nEId = " . $search_subAcc . " ";
              }
            $subAccountQuery = "SELECT nEId as tableId, vName, vCode FROM {PRE}EmployeeMaster WHERE nFId = ". $row->nId . " " . $check_sub . " ";
            break;
          }
          if($subAccountQuery != ''){
            $subAccountList     = $this->db->query($subAccountQuery);
            if ($subAccountList->num_rows() > 0)
            {
              foreach ($subAccountList->result() as $key2=>$row2)
              {
                if(!in_array($row2->tableId,$chkAccount_subacc)){
                  $adminGroupRow[$i]['accountId'] =  $row->nId;
                  $adminGroupRow[$i]['accountName'] = $row->vTitle;
                  $adminGroupRow[$i]['accountCode'] = $row->vACode;
                  $adminGroupRow[$i]['vTable'] = $row->vSubLedgerType;

                  $adminGroupRow[$i]['subAccountId'] =  $row2->tableId;
                  $adminGroupRow[$i]['subAccountName'] = $row2->vName;
                  $adminGroupRow[$i]['subAccountCode'] = $row2->vCode;
                  $i ++;
                }
              }
            }
          }
          else {
            if(!in_array($row->nId,$chkAccount_acc)){
              $adminGroupRow[$i]['accountId'] =  $row->nId;
              $adminGroupRow[$i]['accountName'] = $row->vTitle;
              $adminGroupRow[$i]['accountCode'] = $row->vACode;
              $i ++;
            }
          }


        }//end for each
      } //end if
      return ($adminGroupRow);
    } //end function



}//end class

?>
