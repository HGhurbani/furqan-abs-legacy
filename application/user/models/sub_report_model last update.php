<?php
class Sub_report_model extends CI_Model {
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
/*
    function getActiveLocations()
    {
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1' ORDER BY nPosition DESC");
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
    }
 */
    function filterLocation($division)
    {
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
//            $locGroupRow[0] = SELECT;
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
                                                FROM tbl_AccountHeadAccounts aca LEFT JOIN tbl_AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN tbl_AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                                LEFT JOIN tbl_CustomerMaster v ON v.nFId = ahc.nId
                                              WHERE ah.vActive = '1' AND ahc.vSubLedgerType IN ('V','C') ORDER BY aca.vTitle ASC ");
        if ($typeGroup->num_rows() > 0)
        {
//            $typeGroupRow[0] = SELECT;
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
	
	// add start
	
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
// end add
/*
    function filterSubAccount($accGroup)
    {
        $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            foreach ($accheadGroup->result() as $key=>$row)
            {
                $accheadGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $accheadGroupRow[0] = NOVALUE;
        }//end else

        return ($accheadGroupRow);
    }//end function
*/
    function getActiveEmployee()
    {
        $empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
											ORDER BY vName ASC");
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




    function getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
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

    function getReceivableOutstanding($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  AND cr.nCRId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'CR') ".$extraCheck_cr."
                                  GROUP BY cr.nCRId)
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
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bp.nBPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca
                                  WHERE ca.vAllType = 'BP') ".$extraCheck_bp." GROUP BY bp.nBPId)
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

    function getPayable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
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

    function getParentCustomer($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
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

    function getEmployeeCustomer($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
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

    function getFCDCReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.vSubCode as subacc,cr.nTotalDebit as debit,cr.nTotalBalance as balance,''
                                  as credit,cr.vEnteredBy as issued, cr.nCRId as tableId,'CR' as tblName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
                                  UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.vSubCode as subacc,cp.nTotalDebit as debit,cp.nTotalBalance as balance,
                                  '' as credit,cp.vEnteredBy as
                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)
                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.vSubCode as subacc,bb.nTotalDebit as debit,bb.nTotalBalance as balance,
                                  '' as credit,bb.vEnteredBy
                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)
                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.vSubCode as subacc,bp.nTotalDebit as debit,bp.nTotalBalance as balance,
                                  '' as credit,bp.vEnteredBy
                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)
                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,'' as subacc,jv.nTotalDebit as debit,jv.nTotalBalance as balance,'' as credit,
                                  jv.vEnteredBy as issued,jv.nJVId as tableId,'JV' as tblName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)
                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,'' as subacc,cv.nTotalDebit as debit,cv.nTotalBalance as balance,'' as credit,
                                  cv.vEnteredBy as issued,cv.nCVId as tableId,'CV' as tblName FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)
                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,'' as subacc,dv.nTotalDebit as debit,dv.nTotalBalance as balance,'' as credit,
                                  dv.vEnteredBy as issued,dv.nDVId as tableId,'DV' as tblName FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
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
		$column['cr'] = " crd.nCurrency, IF(crd.vMode = 'Dr', crd.vFCAmount, 0) as vFCAmount,IF(crd.vMode = 'Cr', crd.vFCAmount, 0) as vFCAmount_credit,
              IF(crd.vMode = 'Dr', crd.nAmount , 0) as debit,IF(crd.vMode = 'Cr', crd.nAmount , 0) as credit,
						  crd.vACode as vACode, crd.vAcSubCode as vAcSubCode, crd.vTable as vTable ";
								  
		$column['cp'] = " cpd.nCurrency, IF(cpd.vMode = 'Dr', cpd.vFCAmount, 0) as vFCAmount,IF(cpd.vMode = 'Cr', cpd.vFCAmount, 0) as vFCAmount_credit, 
              IF(cpd.vMode = 'Dr', cpd.nAmount , 0) as debit,IF(cpd.vMode = 'Cr', cpd.nAmount , 0) as credit ,
						  cpd.vACode as vACode, cpd.vAcSubCode as vAcSubCode, cpd.vTable as vTable ";
                                  
		$column['bb'] = " bbd.nCurrency,  IF(bbd.vMode = 'Dr', bbd.vFCAmount, 0) as vFCAmount,IF(bbd.vMode = 'Cr', bbd.vFCAmount, 0) as vFCAmount_credit, 
              IF(bbd.vMode = 'Dr', bbd.nAmount , 0) as debit,IF(bbd.vMode = 'Cr', bbd.nAmount , 0) as credit ,
							bbd.vACode as vACode, bbd.vAcSubCode as vAcSubCode, bbd.vTable as vTable ";
						         
		$column['bp'] = " bpd.nCurrency, IF(bpd.vMode = 'Dr', bpd.vFCAmount, 0) as vFCAmount,IF(bpd.vMode = 'Cr', bpd.vFCAmount, 0) as vFCAmount_credit,
               IF(bpd.vMode = 'Dr', bpd.nAmount , 0) as debit,IF(bpd.vMode = 'Cr', bpd.nAmount , 0) as credit ,
							bpd.vACode as vACode, bpd.vAcSubCode as vAcSubCode, bpd.vTable as vTable ";
                                  
		$column['jv'] = " jvd.nCurrency,IF(jvd.vMode = 'Dr', jvd.vFCAmount, 0) as vFCAmount,IF(jvd.vMode = 'Cr', jvd.vFCAmount, 0) as vFCAmount_credit,
              IF(jvd.vMode = 'Dr', jvd.nAmount , 0) as debit,IF(jvd.vMode = 'Cr', jvd.nAmount , 0) as credit ,
							jvd.vACode as vACode, jvd.vAcSubCode as vAcSubCode, jvd.vTable as vTable ";
                          
		$column['cv'] = " cvd.nCurrency, IF(cvd.vMode = 'Dr', cvd.vFCAmount, 0) as vFCAmount,IF(cvd.vMode = 'Cr', cvd.vFCAmount, 0) as vFCAmount_credit,
              IF(cvd.vMode = 'Dr', cvd.nAmount , 0) as debit,IF(cvd.vMode = 'Cr', cvd.nAmount , 0) as credit ,
							cvd.vACode as vACode, cvd.vAcSubCode as vAcSubCode, cvd.vTable as vTable ";
                                  
		$column['dv'] = "  dvd.nCurrency, IF(dvd.vMode = 'Dr',dvd.vFCAmount, 0) as vFCAmount,IF(dvd.vMode = 'Cr', dvd.vFCAmount, 0) as vFCAmount_credit,
               IF(dvd.vMode = 'Dr', dvd.nAmount , 0) as debit,IF(dvd.vMode = 'Cr', dvd.nAmount , 0) as credit,
               dvd.vACode as vACode, dvd.vAcSubCode as vAcSubCode, dvd.vTable as vTable ";

    $column['ac'] = " acd.nCurrency, IF( acd.vMode = 'Dr' ,acd.vFCAmount,0) as vFCAmount, IF( acd.vMode = 'Cr' , acd.vFCAmount,0) as vFCAmount_credit, 
      IF( acd.vMode = 'Dr' , acd.nAmount ,0)as debit, IF( acd.vMode = 'Cr' , acd.nAmount ,0)as credit,
IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as  vACode,
      IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as vAcSubCode, acd.vTable  as vTable ";

    $column['ac_top_acc'] =  " ac.nCurrency, IF( ac.vTransMode = 'Dr' , ac.vAmountFC,0) as vFCAmount, IF( ac.vTransMode = 'Cr', ac.vAmountFC,0) as vFCAmount_credit, 
      IF( ac.vTransMode = 'Dr' , ac.vAmountDC,0)as debit,
      IF( ac.vTransMode = 'Cr' , ac.vAmountDC,0)as credit,
      ac.vSubCode as  vACode,ac.nAcCode as vAcSubCode, 'V' as vTable ";

                                  
    $column['ad'] = " ade.nCurrency, IF( ade.vMode = 'Dr' , ade.vFCAmount,0) as vFCAmount, IF( ade.vMode = 'Cr' ,ade.vFCAmount,0) as vFCAmount_credit,
      IF( ade.vMode = 'Dr' , ade.nAmount ,0)as debit,
      IF( ade.vMode = 'Cr' , ade.nAmount ,0)as credit,
      IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as  vACode,
      IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as  vAcSubCode,
      ade.vTable  as vTable ";

    $column['ad_top_acc'] = " ad.nCurrency, IF( ad.vTransMode = 'Dr' ,ad.vAmountFC,0) as vFCAmount, IF( ad.vTransMode = 'Cr' ,ad.vAmountFC,0) as vFCAmount_credit,
      IF( ad.vTransMode = 'Dr' , ad.vAmountDC,0)as debit,
      IF( ad.vTransMode = 'Cr' , ad.vAmountDC,0)as credit,
      ad.vSubCode as vACode, ad.nAcCode as vAcSubCode, 'V' as vTable ";
 
    $list = $this->get_acc_list();

    $column['sjv'] = " CASE WHEN sjd.vType = 'Htl' THEN (SELECT nCurrency FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END  as nCurrency,
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



		$column['sjv_s'] = " '' as nCurrency, '' as vFCAmount, '' as vFCAmount_credit,
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
                                  '' as issued, CONCAT(sj.nSalesCId,'-',sj.vType) as tableId,'SJ' as tblName, '' as vRefNumber, '' as vRefName,sjd.nSjDId as detId,sjd.vType as vType,
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
                                  '' as issued, CONCAT(sj.nSalesCId,'-',sj.vType) as tableId,'SJ_R' as tblName, '' as vRefNumber, '' as vRefName,sjd.nSjDId as detId,
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


 
    function getReceivableDetail($extraCheck_sj)
    {
		$column = $this->get_columns();
		$adminGroupListQuery  = " SELECT '' as mode, sjd.nDebit as debit, " . $column['sjv'] .  ",
					sjd_s.nDrAccount as vACode_s, sjd_s.nAccHeadId as vAcSubCode_s, sjd_s.vTable as vTable_s
					FROM {PRE}SystemJvDetails sjd
                                  LEFT JOIN  {PRE}SystemJv sj ON sj.nId = sjd.nSjId 
				  LEFT JOIN {PRE}SystemJvDetails sjd_s ON sjd.nSjId = sjd_s.nSjId 
				  LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation
				  WHERE 
								  ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd.nAccHeadId ) LIKE 'V'
								  AND ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd_s.nAccHeadId ) LIKE 'C'
								   " .$extraCheck_sj." 
								  
								  ORDER BY  vAcSubCode_s, vACode_s, vTable_s, tblName, date
								  ";
								  

//			echo $adminGroupListQuery;
//			return;								  
											  
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



    function getPayableDetail($extraCheck_sj)
    {
		$column = $this->get_columns();
        $adminGroupListQuery  = " SELECT '' as mode, sjd.nDebit as debit, " . $column['sjv'] . "
								  FROM {PRE}SystemJv sj
								  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
								  LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation
								  WHERE ".$extraCheck_sj." 
								  
								  ORDER BY  vAcSubCode, vACode, vTable, tblName, date
								  ";
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

								  
    function getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$ReportType,$reportName = "receivable")
    { 
    $list = $this->get_acc_list();
		if($ReportType == "Summary"){
			$column = $this->get_columns_summary();
      if($reportName == "receivable") {
        $outer_select = " SELECT SUM(debit) as debit, SUM(credit) as credit, vACode, vAcSubCode, vTable FROM (";
        $group_by = " ) AS T GROUP BY vACode, vAcSubCode, vTable ";
        $order_by = " (select vTitle from {PRE}AccountHeadAccounts WHERE nId='vACode') ";
      }
      if($reportName == "fcdc"){
        $outer_select = " SELECT nCurrency, SUM(vFCAmount) as vFCAmount, SUM(vFCAmount_credit) as vFCAmount_credit, SUM(debit) as debit, SUM(credit) as credit, vACode, vAcSubCode, vTable FROM (";
        $group_by = " ) AS T GROUP BY vACode, vAcSubCode, vTable, nCurrency";
        $order_by = " vAcSubCode, vACode, nCurrency";
      }
      if($reportName == "receivable_outstanding" || $reportName == "fcdc_outstanding"){
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
 
        $column['ac'].=" , '' as alloc_debit, '' as alloc_credit " ;
        $column['ac_top_acc'].=" , '' as alloc_debit, '' as alloc_credit " ;
        $column['ad'].=" , '' as alloc_debit, '' as alloc_credit " ;
        $column['ad_top_acc'].=" , '' as alloc_debit, '' as alloc_credit " ;



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


        if($reportName == "receivable_outstanding"){
          $outer_select = " SELECT SUM(alloc_debit) as alloc_debit, SUM(alloc_credit) as  alloc_credit, SUM(debit) as debit, SUM(credit) as credit, vACode, vAcSubCode, vTable FROM (";
          $group_by = " ) AS T GROUP BY vACode, vAcSubCode, vTable ";
          $order_by = " (select vTitle from {PRE}AccountHeadAccounts WHERE nId='vACode') ";
        }
        else if( $reportName == "fcdc_outstanding"){
          $outer_select = " SELECT SUM(alloc_debit) as alloc_debit, SUM(alloc_credit) as  alloc_credit,nCurrency, SUM(vFCAmount) as vFCAmount, SUM(vFCAmount_credit) as vFCAmount_credit, SUM(debit) as debit, SUM(credit) as credit, vACode, vAcSubCode, vTable FROM (";
          $group_by = " ) AS T GROUP BY vACode, vAcSubCode, vTable, nCurrency";
          $order_by = " vAcSubCode, vACode, nCurrency";

        }
      }
    }
    else if($ReportType == "Detail"){		
		  $column['cr'] = " '' as OutReport, crd.nAmount as debit, ";
		  $column['cp'] = " '' as OutReport, cpd.nAmount as debit, ";
		  $column['bb'] = " '' as OutReport, bbd.nAmount as debit, ";
		  $column['bp'] = " '' as OutReport, bpd.nAmount as debit, ";
		  $column['jv'] = " '' as OutReport, jvd.nAmount as debit, ";
		  $column['cv'] = " '' as OutReport, cvd.nAmount as debit, ";
		  $column['dv'] = " '' as OutReport, dvd.nAmount as debit, ";
		  $column['ac'] = " '' as OutReport, acd.nAmount as debit, ";
		  $column['ac_top_acc'] = " '' as OutReport, ac.vAmountDC as debit, ";
		  $column['ad'] = " '' as OutReport, ade.nAmount as debit, ";
		  $column['ad_top_acc'] = " '' as OutReport, ad.vAmountDC as debit, ";
		  $column['sjv'] = " '' as OutReport, sjd.nDebit as debit, ";
		  $column['sjv_s'] = " '' as OutReport, sjd.nDebit as debit , ";
		  $column = $this->get_columns($column);
		  $outer_select = "";
		  $group_by = "";
		  $order_by = " vAcSubCode, vACode, vTable , date, document ";
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
/*
echo "<br><br>Q1:<br> ( SELECT " . $column['sjv'] . "
      FROM {PRE}SystemJv sj
      LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId
      LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation
      WHERE  ( select vSubLedgerType from tbl_AccountHeadControls where nActHId = sjd.nAccHeadId ) LIKE 'V'
      ".$extraCheck_sj." )";

echo "<br><br>Q2: <br> (SELECT " . $column['sjv_s'] . " <br><br>
      FROM {PRE}SystemJvDetails sjd 
      LEFT JOIN  {PRE}SystemJv sj ON sj.nId = sjd.nSjId
      INNER JOIN {PRE}SystemJvDetails sjd_s ON sjd.nSjId = sjd_s.nSjId
      LEFT JOIN {PRE}Location loc ON loc.nId = sj.nLocation<br><br>
      WHERE ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd.nAccHeadId ) LIKE 'V'
      AND ( select vSubLedgerType FROM {PRE}AccountHeadControls WHERE nActHId = sjd_s.nAccHeadId ) LIKE 'C'
      ".$extraCheck_sj_s." )";
*/

// echo $adminGroupListQuery . " <br><br><br><br><br>";
//if($ReportType == "Summary") return;
/* echo " (SELECT " . $column['dv'] . "
      FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
      dvd.nDebitId = dv.nDVId
      LEFT JOIN {PRE}Location loc ON loc.nId = dv.vLocation
      WHERE dv.vActive='1' ".$extraCheck_dv.")
<br>
      UNION ALL  (SELECT ". $column['ac_top_acc']." FROM
      {PRE}AcmEntry ac
      LEFT JOIN {PRE}Location loc ON loc.nId = ac.vLocation
      WHERE ac.vActive='1'
      ".$extraCheck_ac_top_acc." )
<br>
      UNION ALL
      (SELECT  ". $column['ac']."  FROM
      {PRE}AcmEntry ac
      LEFT JOIN {PRE}Location loc ON loc.nId = ac.vLocation
      LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId WHERE ac.vActive='1'
      ".$extraCheck_ac." )
<br>
      UNION ALL( SELECT " . $column['ad_top_acc']." FROM
      {PRE}AdmEntry ad
      LEFT JOIN {PRE}Location loc ON loc.nId = ad.vLocation
      WHERE ad.vActive='1'
      ".$extraCheck_ad_top_acc." )
<br>
      UNION ALL (SELECT  ". $column['ad']." FROM
      {PRE}AdmEntry ad
      LEFT JOIN {PRE}Location loc ON loc.nId = ad.vLocation
      LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId WHERE ad.vActive='1'
      ".$extraCheck_ad." ) <br><br><br><br>" ; */
// if($ReportType == "Summary") { echo $adminGroupListQuery . " <br><br><br><br><br>"; 
//return; }
//echo $adminGroupListQuery;
//return;
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



    function getDetailReportFilter($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$ReportType)
    {
	
/*								  
								  
		$column = $this->get_columns();
	    $adminGroupListQuery  = "(SELECT " . $column['cr'] . " , 
								  CASE WHEN cr.nAcCode = '".$this->ddlAccount."' THEN cr.vTransMode
                                  ELSE crd.vMode END as mode,
								  cr.vAmountFC as debit
								  FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                  ".$extraCheck_cr." GROUP BY cr.nCRId)
								  
                                  UNION ALL (SELECT " . $column['cp'] . " , 
                                  CASE WHEN cp.nAcCode = '".$this->ddlAccount."' THEN cp.vTransMode
                                   ELSE cpd.vMode END as mode,
								   cp.vAmountFC as debit
                                   FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp.")
                                  
								  UNION ALL (SELECT " . $column['bb'] . ", 
								  CASE WHEN bb.nAcCode = '".$this->ddlAccount."' THEN bb.vTransMode
                                  ELSE bbd.vMode END as mode,
								  bb.vAmountFC as debit
								  FROM {PRE}BankBook bb LEFT
                                  JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb.")
                                  
								  UNION ALL (SELECT " . $column['bp'] . ", 
								  CASE WHEN bp.nAcCode = '".$this->ddlAccount."' THEN bp.vTransMode
                                  ELSE bpd.vMode END as mode,
								  bp.vAmountFC as debit
                                   FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp.")
                                  
								  UNION ALL (SELECT " . $column['jv'] . ", 
								  jvd.vMode as mode,
								  jvd.nAmount as debit
								  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv.")
								  
                                  UNION ALL (SELECT " . $column['cv'] . ", 
								  cvd.vMode as mode,
								  cvd.nAmount as debit
								  FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv.")
                                  
								  UNION ALL (SELECT " . $column['dv'] . ", 
								  dvd.vMode as mode,
								  dvd.nAmount as debit
								  FROM {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv.")
                                  
								  
								  UNION ALL (SELECT " . $column['sjv'] . ", 
								  '' as mode,
								  sjd.nDebit as debit
								  FROM {PRE}SystemJv sj
                                  LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE ".$extraCheck_sj." )
								  
								  ORDER BY  vAcSubCode, vACode, vTable, tblName
								  ";
								  
//		echo $adminGroupListQuery;
//		return;								  
								  
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
 */  
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
	
	
	


    function getReceivableAccountValues($vType,$vNumber,$closedId,$sales_refund)
    {
/*			switch($vType)
			{
				case "Xo" :  $newArray['collected'] = $this->common_model->get_field_name('XoBillingDetails','vCollected'," WHERE nXoId ='".$vNumber."'");	    break;
				case "Tkt" : $newArray['collected'] = $this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId ='".$vNumber."'"); break;
				case "Htl" : $newArray['collected'] = $this->common_model->get_field_name('HotelBillingDetails','vCollected'," WHERE nHotelId='".$vNumber."'"); break;
			}//end switch
*/			
		$newarray['accounts'] = '';
 
		if($sales_refund == 'S')
		{
			$newArray['collected'] = $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber ='".$vNumber."'");	
			$newArray['profit'] = $this->common_model->get_field_name('SalesClosingDetails','vServAmt'," WHERE vTktNumber ='".$vNumber."'");	
			$newArray['profitpercent'] = $this->common_model->get_field_name('SalesClosingDetails','vServPercent'," WHERE vTktNumber ='".$vNumber."'");	
		
		
			$adminGroupListQuery = "SELECT vTable, nDrAccount, nAcHead
								FROM {PRE}SalesAccountDetails sad 
								WHERE sad.nSalesCloseId = " . $closedId ;
			$adminGroupList 		= $this->db->query($adminGroupListQuery);
			if ($adminGroupList->num_rows() > 0)
			{

				$delimeter = '';
				foreach($adminGroupList->result() as $key_a => $item_a){
					switch($item_a->vTable)
					{
						case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item_a->nDrAccount  ."'"); break;
						case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item_a->nDrAccount  ."'"); break;
						case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item_a->nDrAccount  ."'"); break;
						case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item_a->nDrAccount  ."'"); break;
						default: $item_a->nAcHead = $item_a->nDrAccount ;	$drAccountName = "";	break;
					}//end switch
					$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item_a->nAcHead."'");
					if($drAccountName != '' || !empty($drAccountName)){ 
						$newArray['accounts'] .= $delimeter . $drAccountName; 
					}  
					else { 
						$newArray['accounts'] .= $delimeter . $subAccName ; 
					}												
					$delimeter = ", <br> "; 

				} // end foreach
			} // end if 
	
		}
		else {
			$newArray['collected'] = $this->common_model->get_field_name('RefundClosingDetails','-vPaidAmt'," WHERE nTicketNumber ='".$vNumber."'");	
			$newArray['profit'] = $this->common_model->get_field_name('RefundClosingDetails','-ROUND(vServAmt,3)'," WHERE nTicketNumber ='".$vNumber."'");	
			$newArray['profitpercent'] = $this->common_model->get_field_name('RefundClosingDetails','-ROUND(vServPercent,3)'," WHERE nTicketNumber ='".$vNumber."'");	
		} // end if
    	return $newArray;
	} //end functions
	
/*	
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

*/

//checks for opening balance
	function get_checks()
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
/*
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_cr .= " AND STR_TO_DATE( cr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_cp .= " AND STR_TO_DATE( cp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_bb .= " AND STR_TO_DATE( bb.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_bp .= " AND STR_TO_DATE( bp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_jv .= " AND STR_TO_DATE( jv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_cv .= " AND STR_TO_DATE( cv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_dv .= " AND STR_TO_DATE( dv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_sj .= " AND STR_TO_DATE( sj.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_sj_s .= " AND STR_TO_DATE( sj.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
		}//end if
*/


      if ($this->txtFromDate !='') {
        $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj_s .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

      }
/*      if ($this->txtToDate != '') {
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
*/

        $extraCheck_ac_top_acc .= $extraCheck_ac;
        $extraCheck_ad_top_acc .= $extraCheck_ad;

      $extraCheck_sj .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_sj_s .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

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

        $accounts = explode("-",$this->ddlSubAccount);	
          $extraCheck_ac_top_acc .= " AND ac.vSubCode = '".$accounts[0] ."' ";
          $extraCheck_ad_top_acc .= " AND ad.vSubCode = '".$accounts[0] ."' ";
        $extraCheck_cr .= " AND crd.vACode='". $accounts[0] ."'";
        $extraCheck_cp .= " AND cpd.vACode='".$accounts[0]."'";
        $extraCheck_bb .= " AND bbd.vACode='".$accounts[0]."'";
        $extraCheck_bp .= " AND bpd.vACode='".$accounts[0]."'";
        $extraCheck_jv .= " AND jvd.vACode='".$accounts[0]."'";
        $extraCheck_cv .= " AND cvd.vACode='".$accounts[0]."'";
        $extraCheck_dv .= " AND dvd.vACode='".$accounts[0]."'";
          $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vACode = '".$accounts[0] ."' ,1 )";
          $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vACode = '".$accounts[0] ."' ,1 )";
        $extraCheck_sj .= " AND vACode = '".$accounts[0]."'";
        $extraCheck_sj_s .= " AND vACode = '".$accounts[0]."'";

      }
    }		

		return array("cr" => $extraCheck_cr, "cp" => $extraCheck_cp, "bb" => $extraCheck_bb, 
			"bp" => $extraCheck_bp, "jv" => $extraCheck_jv, "cv" => $extraCheck_cv, "dv" => $extraCheck_dv, 
			"sj" => $extraCheck_sj,"sj_s" => $extraCheck_sj_s,"ac"=>$extraCheck_ac,"ac_top_acc" => $extraCheck_ac_top_acc,
      "ad" => $extraCheck_ad, "ad_top_acc" => $extraCheck_ad_top_acc);
	} //end function



    function getOpeningBalForAcc($account,$subacc,$table,$openBalName = "receivable")
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
      $check = $this->get_checks();

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
//      $extraCheck_sj .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
//      $extraCheck_sj_s .= " AND YEAR(sj.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ac .=  " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ac_top_acc .=  " AND YEAR(ac.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ad .=  " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";
      $extraCheck_ad_top_acc .=  " AND YEAR(ad.dDate) =  YEAR('". date('Y-m-d',strtotime($this->txtFromDate))."')";

//        $group_select =" SELECT YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";
//        $groupby = "  ) as temp GROUP BY YEAR(date), account,subacc ";
    
        $data = array();
//        $data = $this->getReports($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$open_cr_top_acc,$open_cp_top_acc,$open_bb_top_acc,$open_bp_top_acc,$open_ac_top_acc,$open_ad_top_acc,$orderby,$group_select, $groupby,$openBalName);

        $data = $this->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Summary",$openBalName);

        $opening_balance['debit'] = 0;
        $opening_balance['credit'] = 0;
        $opening_balance['alloc_debit'] =0;
        $opening_balance['alloc_credit'] = 0;
        $opening_balance['vFCAmount'] = 0;
        $opening_balance['vFCAmount_credit'] = 0;

        if(!empty($data)){
          foreach ($data as $key => $item){

            if($openBalName == "receivable_outstanding" || $openBalName == "fcdc_outstanding"){

              $opening_balance['alloc_debit'] += $item->alloc_debit;
              $opening_balance['alloc_credit'] += $item->alloc_credit;
            }
            if($openBalName == "fcdc" || $openBalName == "fcdc_outstanding"){
              $opening_balance['vFCAmount'] += $item->vFCAmount;
              $opening_balance['vFCAmount_credit'] += $item->vFCAmount_credit;
            }
            $opening_balance['debit'] += $item->debit;
            $opening_balance['credit'] += $item->credit;

          }
        }

          $opening_balance['debit'] = $opening_balance['debit'] - $opening_balance['alloc_debit'] ;
          $opening_balance['credit'] =  $opening_balance['credit'] -  $opening_balance['alloc_credit'];

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




    function getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby, $openBalName = "OB")
    {

      $list = $this->get_acc_list();

      $column['cr'] = $column['cp'] =  $column['bb'] = $column['bp'] =  $column['jv'] = $column['cv'] =  $column['dv'] = $column['sjv'] =  $column['sjv_s'] = ''; 

      if($openBalName == "OBwithAlloc" || $openBalName == "OBwithFCDCAlloc"){
      $column['cr']="  crd.nAmount - IFNULL(
        (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CR' AND nReceiptDId = cr.nCRId GROUP BY cr.nCRId),0)  as alloc,";
      $column['cp']="  cpd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CP' AND nReceiptDId = cp.nCPId GROUP BY cp.nCPId),0) as alloc, ";
      $column['bb']="  bbd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BR' AND nReceiptDId = bb.nBBId GROUP BY bb.nBBId),0) as alloc, ";
      $column['bp']=" bpd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='BP' AND nReceiptDId = bp.nBPId GROUP BY bp.nBPId),0) as alloc, ";
      $column['jv']=" jvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='JV' AND nReceiptDId = jv.nJVId GROUP BY jv.nJVId),0) as alloc, ";
      $column['cv']=" cvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='CV' AND nReceiptDId = cv.nCVId GROUP BY cv.nCVId),0) as alloc, ";
      $column['dv']=" dvd.nAmount - IFNULL(
          (Select SUM(vAllAmt) FROM {PRE}CashAllocation WHERE vAllType='DV' AND nReceiptDId = dv.nDVId GROUP BY dv.nDVId),0) as alloc, ";
//IF(sjd.nDebit != 0 , 1, -1) 
      $column['sjv']=" ((SELECT MIN(vBalAmt) FROM {PRE}CashAllocation 
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
                    GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount))as alloc, ";

      $column['ac_top'] = " '' as alloc, " ;
      $column['ac'] = " '' as alloc, " ;
      $column['ad_top'] = " '' as alloc, " ;
      $column['ad'] = " '' as alloc, " ;

      $group_select =" SELECT SUM(alloc),  YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";

      }

      if($openBalName == "OBwithFCDC" || $openBalName == "OBwithFCDCAlloc"){
        $column['cr'] .= " IF(crd.vMode = 'Dr', crd.vFCAmount, 0) as vFCAmount,IF(crd.vMode = 'Cr', crd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['cp'] .= " IF(cpd.vMode = 'Dr', cpd.vFCAmount, 0) as vFCAmount,IF(cpd.vMode = 'Cr', cpd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['bb'] .= "  IF(bbd.vMode = 'Dr', bbd.vFCAmount, 0) as vFCAmount,IF(bbd.vMode = 'Cr', bbd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['bp'] .= " IF(bpd.vMode = 'Dr', bpd.vFCAmount, 0) as vFCAmount,IF(bpd.vMode = 'Cr', bpd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['jv'] .= "IF(jvd.vMode = 'Dr', jvd.vFCAmount, 0) as vFCAmount,IF(jvd.vMode = 'Cr', jvd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['cv'] .= " IF(cvd.vMode = 'Dr', cvd.vFCAmount, 0) as vFCAmount,IF(cvd.vMode = 'Cr', cvd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['dv'] .= " IF(dvd.vMode = 'Dr',dvd.vFCAmount, 0) as vFCAmount,IF(dvd.vMode = 'Cr', dvd.vFCAmount, 0) as vFCAmount_credit, ";

        $column['sjv'] .= " IF(sjd.nDebit != 0, CASE WHEN sjd.vType = 'Htl' THEN (SELECT vTotalFC FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END,0) as vFCAmount,
IF(sjd.nCredit !=0, CASE WHEN sjd.vType = 'Htl' THEN (SELECT vTotalFC FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber) ELSE 0 END,0) as vFCAmount_credit, ";

        $column['ac_top'] .= " '' as vFCAmount, '' as vFCAmount_credit, " ;
        $column['ac'] .= " '' as vFCAmount, '' as vFCAmount_credit, " ;
        $column['ad_top'] .= " '' as vFCAmount, '' as vFCAmount_credit, " ;
        $column['ad'] .= " '' as vFCAmount, '' as vFCAmount_credit, " ;

        $group_select =" SELECT SUM(vFCAmount), SUM(vFCAmount_credit), YEAR(date) as year, account,subacc, SUM(debit) as debit ,SUM(credit) as credit FROM ( ";
        $groupby = "  ) as temp GROUP BY YEAR(date), account,subacc ";

      }

        $sj_query = "(SELECT " . $column['sjv'] . " sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
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

      $cr_query = "(SELECT " . $column['cr'] . " cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
               IF(crd.vAcSubCode !='0' , crd.vAcSubCode, crd.vACode) as account,
               IF(crd.vAcSubCode !='0' , crd.vACode, crd.vAcSubCode) as subacc, crd.vTable  as vTable,
               IF( crd.vMode = 'Dr' , crd.nAmount ,0)as debit,
               IF( crd.vMode = 'Cr' , crd.nAmount ,0)as credit,
               cr.nCRId as tableId,'CR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
               ".$extraCheck_cr." ) "  ;


     $cp_query = "(SELECT " . $column['cp'] . " cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
               IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode, cpd.vACode) as account,
               IF(cpd.vAcSubCode !='0' , cpd.vACode, cpd.vAcSubCode) as subacc, cpd.vTable  as vTable,
               IF( cpd.vMode = 'Dr' , cpd.nAmount ,0)as debit,
               IF( cpd.vMode = 'Cr' , cpd.nAmount ,0)as credit,
               cp.nCPId as tableId,'CP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId WHERE cp.vActive='1'
               ".$extraCheck_cp." ) "  ;


     $bb_query = "(SELECT " . $column['bb'] . " bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as description,
               IF(bbd.vAcSubCode !='0' , bbd.vAcSubCode, bbd.vACode) as account,
               IF(bbd.vAcSubCode !='0' , bbd.vACode, bbd.vAcSubCode) as subacc, bbd.vTable  as vTable,
               IF( bbd.vMode = 'Dr' , bbd.nAmount ,0)as debit,
               IF( bbd.vMode = 'Cr' , bbd.nAmount ,0)as credit,
               bb.nBBId as tableId,'BR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1'
               ".$extraCheck_bb." ) "  ;


     $bp_query = "(SELECT " . $column['bp'] . " bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as description,
               IF(bpd.vAcSubCode !='0' , bpd.vAcSubCode, bpd.vACode) as account,
               IF(bpd.vAcSubCode !='0' , bpd.vACode, bpd.vAcSubCode) as subacc, bpd.vTable  as vTable,
               IF( bpd.vMode = 'Dr' , bpd.nAmount ,0)as debit,
               IF( bpd.vMode = 'Cr' , bpd.nAmount ,0)as credit,
               bp.nBPId as tableId,'BP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1'
               ".$extraCheck_bp." ) "  ;


    $cv_query = "(SELECT " . $column['cv'] . " cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as description,
               IF(cvd.vAcSubCode !='0' , cvd.vAcSubCode, cvd.vACode) as account,
               IF(cvd.vAcSubCode !='0' , cvd.vACode, cvd.vAcSubCode) as subacc, cvd.vTable  as vTable,
               IF( cvd.vMode = 'Dr' , cvd.nAmount ,0)as debit,
               IF( cvd.vMode = 'Cr' , cvd.nAmount ,0)as credit,
               cv.nCVId as tableId,'CV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1'
               ".$extraCheck_cv." ) "  ;

    $dv_query = "(SELECT " . $column['dv'] . " dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as description,
               IF(dvd.vAcSubCode !='0' , dvd.vAcSubCode, dvd.vACode) as account,
               IF(dvd.vAcSubCode !='0' , dvd.vACode, dvd.vAcSubCode) as subacc, dvd.vTable  as vTable,
               IF( dvd.vMode = 'Dr' , dvd.nAmount ,0)as debit,
               IF( dvd.vMode = 'Cr' , dvd.nAmount ,0)as credit,
               dv.nDVId as tableId,'DV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId WHERE dv.vActive='1'
               ".$extraCheck_dv." ) "  ;


     $ac_query = "(SELECT ". $column['ac_top']." ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
              ac.nAcCode as account,ac.vSubCode as subacc, 'V' as vTable,
              IF( ac.vTransMode = 'Dr' , ac.vAmountDC,0)as debit,
              IF( ac.vTransMode = 'Cr' , ac.vAmountDC,0)as credit,
              ac.nACId as tableId,'AC' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}AcmEntry ac WHERE ac.vActive='1'
            ".$extraCheck_ac_top_acc." )
              UNION ALL
              (SELECT  ". $column['ac']."  ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
               IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as account,
               IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as subacc, acd.vTable  as vTable,
               IF( acd.vMode = 'Dr' , acd.nAmount ,0)as debit,
               IF( acd.vMode = 'Cr' , acd.nAmount ,0)as credit,
               ac.nACId as tableId,'AC' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId WHERE ac.vActive='1'
               ".$extraCheck_ac." ) "  ;

            $ad_query = " (SELECT " . $column['ad_top']." ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
              ad.nAcCode as account,ad.vSubCode as subacc, 'V' as vTable,
              IF( ad.vTransMode = 'Dr' , ad.vAmountDC,0)as debit,
              IF( ad.vTransMode = 'Cr' , ad.vAmountDC,0)as credit,
              ad.nADId as tableId,'AD' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}AdmEntry ad WHERE ad.vActive='1'
            ".$extraCheck_ad_top_acc." )
              UNION ALL
              (SELECT  ". $column['ad']." ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
               IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as account,
               IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as subacc, ade.vTable  as vTable,
               IF( ade.vMode = 'Dr' , ade.nAmount ,0)as debit,
               IF( ade.vMode = 'Cr' , ade.nAmount ,0)as credit,
               ad.nADId as tableId,'AD' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId WHERE ad.vActive='1'
               ".$extraCheck_ad." ) "  ;

            $jv_query = "(SELECT " . $column['jv'] . " jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as description,
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
            //angela
             // echo $adminGroupListQuery . "<br><br><br><br>";
             // return;

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
var_dump(urlencode($input));
$output = rtrim(strtr(base64_encode(gzdeflate($input, 9)), '+/', '-_'), '=');
var_dump($output);

?>

