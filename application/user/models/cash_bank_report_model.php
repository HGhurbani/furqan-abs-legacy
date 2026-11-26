<?php
class Cash_bank_report_model extends CI_Model {

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
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
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

    function mainReportArray()
    {
        $mainReportArrayList = array("Details" => "Details","Summary" => "Summary");

        return ($mainReportArrayList);
    }//end function

    function mainTransactionArray()
    {
        $mainTransactionArrayList = array("All" => "All","Cash Receipt" => "Cash Receipt","Cash Payment" => "Cash Payment",
                                          "Bank Deposit" => "Bank Deposit",
                                          "Bank Payment" => "Bank Payment","Credit Note" => "Credit Note","Debit Note" => "Debit Note",
                                          "ACM" => "ACM","ADM" => "ADM","Journal Voucher" => "Journal Voucher");
        return ($mainTransactionArrayList);
    }//end function

    function getActiveCustomerValue()
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

    function getFullRegister($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT cr.nCRId as tableId,'CR' as type,cr.dDate as date,cr.vLocation as location,
                                  cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.nTotalBalance as balance,SUM(crd.nAmount) as debit,
                                  '' as credit,cr.vEnteredBy as issued,
                                  cr.nAcCode as subacc,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,
                                  cr.vRate,cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                 ".$extraCheck_cr." GROUP BY cr.nCRId) UNION ALL
                                 (SELECT cp.nCPId as tableId,'CP' as type,cp.dDate as date,cp.vLocation as location,
                                 cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.nTotalBalance as balance,'' as debit,
                                  SUM(cpd.nAmount) as credit,cp.vEnteredBy as
                                  issued,cp.nAcCode as subacc,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId) UNION ALL
                                  (SELECT bb.nBBId as tableId,'BR' as type,bb.dDate as date,bb.vLocation as location,
                                  bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.nTotalBalance as balance,
                                  SUM(bbd.nAmount) as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as subacc,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId) UNION ALL
                                  (SELECT bp.nBPId as tableId,'BP' as type,bp.dDate as date,bp.vLocation as location,
                                  bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.nTotalBalance as balance,
                                  '' as debit,SUM(bpd.nAmount) as credit,bp.vEnteredBy
                                  as issued,bp.nAcCode as subacc,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId) UNION ALL
                                  (SELECT cv.nCVId as tableId,'CV' as type,cv.dDate as date,cv.vLocation as location,
                                  cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,cv.nTotalBalance as balance,SUM(cvd.nAmount) as debit,
                                  '' as credit,cv.vEnteredBy as issued,
                                  '' as subacc,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId) UNION ALL
                                  (SELECT dv.nDVId as tableId,'DV' as type,dv.dDate as date,dv.vLocation as location,
                                  dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,
                                  '' as credit,dv.vEnteredBy as issued,
                                  '' as subacc,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId) UNION ALL
                                  (SELECT jv.nJVId as tableId,'JV' as type,jv.dDate as date,jv.vLocation as location,
                                  jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,jv.nTotalBalance as balance,SUM(jvd.nAmount) as debit,
                                  '' as credit,jv.vEnteredBy as issued,
                                  '' as subacc,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,
                                  jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId) UNION ALL
                                  (SELECT ac.nACId as tableId,'ACM' as type,ac.dDate as date,ac.vLocation as location,
                                  ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'' as balance,SUM(acd.nAmount) as debit,
                                  '' as credit,ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId) UNION ALL
                                  (SELECT ad.nADId as tableId,'ADM' as type,ad.dDate as date,ad.vLocation as location,
                                  ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'' as balance,'' as debit,
                                  SUM(adm.nAmount) as credit,ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails adm ON
                                  adm.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId) ORDER BY date DESC";
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
    }

    function getCashReceiptRegister($extraCheck_cr)
    {
        $adminGroupListQuery  = "(SELECT cr.nCRId as tableId,'CR' as type,cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.nTotalBalance as balance,SUM(crd.nAmount) as debit,'' as credit,cr.vEnteredBy as issued,
                                  cr.nAcCode as subacc,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,cr.vRate,cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                 ".$extraCheck_cr." GROUP BY cr.nCRId)";
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

    function getCashPaymentRegister($extraCheck_cp)
    {
        $adminGroupListQuery  = "(SELECT cp.nCPId as tableId,'CP' as type,cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.nTotalBalance as balance,'' as debit,SUM(cpd.nAmount) as credit,cp.vEnteredBy as
                                  issued,cp.nAcCode as subacc,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)";
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

    function getBankDepositRegister($extraCheck_bb)
    {
        $adminGroupListQuery  = "(SELECT bb.nBBId as tableId,'BR' as type,bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.nTotalBalance as balance,
                                  SUM(bbd.nAmount) as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as subacc,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)";
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

    function getBankPaymentRegister($extraCheck_bp)
    {
        $adminGroupListQuery  = "(SELECT bp.nBPId as tableId,'BP' as type,bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.nTotalBalance as balance,
                                  '' as debit,SUM(bpd.nAmount) as credit,bp.vEnteredBy
                                  as issued,bp.nAcCode as subacc,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)";
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

    function getCreditVoucherRegister($extraCheck_cv)
    {
        $adminGroupListQuery  = "(SELECT cv.nCVId as tableId,'CV' as type,cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,cv.nTotalBalance as balance,SUM(cvd.nAmount) as debit,'' as credit,cv.vEnteredBy as issued,
                                  '' as subacc,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)";
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

    function getDebitoucherRegister($extraCheck_dv)
    {
        $adminGroupListQuery  = "(SELECT dv.nDVId as tableId,'DV' as type,dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,'' as credit,dv.vEnteredBy as issued,
                                  '' as subacc,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
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

    function getJournalVoucherRegister($extraCheck_jv)
    {
        $adminGroupListQuery  = "(SELECT jv.nJVId as tableId,'JV' as type,jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,jv.nTotalBalance as balance,SUM(jvd.nAmount) as debit,'' as credit,jv.vEnteredBy as issued,
                                  '' as subacc,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)";
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

    function getACMRegister($extraCheck_ac)
    {
        $adminGroupListQuery  = "(SELECT ac.nACId as tableId,'ACM' as type,ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'' as balance,SUM(acd.nAmount) as debit,'' as credit,ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId)";
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

    function getADMRegister($extraCheck_ad)
    {
        $adminGroupListQuery  = "(SELECT ad.nADId as tableId,'ADM' as type,ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'' as balance,'' as debit,SUM(adm.nAmount) as credit,ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails adm ON
                                  adm.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId)";
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

    function getFullTransaction($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad)
    {
        switch($this->ddlReport)
        {
            case "Details":$adminGroupListQuery = "(SELECT cr.nCRId as tableId,'CR' as type,cr.dDate as date,cr.vLocation as location,
                                  cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.nTotalBalance as balance,cr.vAmountDC as debit,
                                  '' as credit,cr.vEnteredBy as issued,
                                  cr.nAcCode as subacc,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,
                                  cr.vRate,cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                 ".$extraCheck_cr." GROUP BY cr.nCRId) UNION ALL
                                 (SELECT cp.nCPId as tableId,'CP' as type,cp.dDate as date,cp.vLocation as location,
                                 cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.nTotalBalance as balance,'' as debit,
                                  cp.vAmountDC as credit,cp.vEnteredBy as
                                  issued,cp.nAcCode as subacc,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId) UNION ALL
                                  (SELECT bb.nBBId as tableId,'BR' as type,bb.dDate as date,bb.vLocation as location,
                                  bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.nTotalBalance as balance,
                                  bb.vAmountDC as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as subacc,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId) UNION ALL
                                  (SELECT bp.nBPId as tableId,'BP' as type,bp.dDate as date,bp.vLocation as location,
                                  bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.nTotalBalance as balance,
                                  '' as debit,bp.vAmountDC as credit,bp.vEnteredBy
                                  as issued,bp.nAcCode as subacc,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId) UNION ALL
                                  (SELECT cv.nCVId as tableId,'CV' as type,cv.dDate as date,cv.vLocation as location,
                                  cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,cv.nTotalBalance as balance,SUM(cvd.nAmount) as debit,
                                  '' as credit,cv.vEnteredBy as issued,
                                  '' as subacc,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId) UNION ALL
                                  (SELECT dv.nDVId as tableId,'DV' as type,dv.dDate as date,dv.vLocation as location,
                                  dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,
                                  '' as credit,dv.vEnteredBy as issued,
                                  '' as subacc,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId) 								  UNION ALL
                                  (SELECT jv.nJVId as tableId,'JV' as type,jv.dDate as date,jv.vLocation as location,
                                  jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,jv.nTotalBalance as balance,'' as debit,
                                  '' as credit,jv.vEnteredBy as issued,
                                  '' as subacc,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,
                                  jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId) UNION ALL
                                  (SELECT ac.nACId as tableId,'ACM' as type,ac.dDate as date,ac.vLocation as location,
                                  ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'' as balance,SUM(acd.nAmount) as debit,
                                  '' as credit,ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId) UNION ALL
                                  (SELECT ad.nADId as tableId,'ADM' as type,ad.dDate as date,ad.vLocation as location,
                                  ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'' as balance,'' as debit,
                                  SUM(adm.nAmount) as credit,ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails adm ON
                                  adm.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId) ORDER BY date DESC";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT cr.nCRId as tableId,'CR' as type,cr.dDate as date,cr.vLocation as location,
                                  cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.nTotalBalance as balance,SUM(crd.nAmount) as debit,'' as credit,
                                  cr.vEnteredBy as issued,
                                  cr.nAcCode as subacc,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,cr.vRate,
                                  cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                 ".$extraCheck_cr." GROUP BY cr.nCRId) UNION ALL (SELECT cp.nCPId as tableId,'CP' as type,cp.dDate as date,
                                 cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.nTotalBalance as balance,'' as debit,
                                  SUM(cpd.nAmount) as credit,cp.vEnteredBy as
                                  issued,cp.nAcCode as subacc,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId) UNION ALL
                                  (SELECT bb.nBBId as tableId,'BR' as type,bb.dDate as date,bb.vLocation as location,
                                  bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.nTotalBalance as balance,
                                  SUM(bbd.nAmount) as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as subacc,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId) UNION ALL
                                  (SELECT bp.nBPId as tableId,'BP' as type,bp.dDate as date,bp.vLocation as location,
                                  bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.nTotalBalance as balance,
                                  '' as debit,SUM(bpd.nAmount) as credit,bp.vEnteredBy
                                  as issued,bp.nAcCode as subacc,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId) UNION ALL
                                  (SELECT cv.nCVId as tableId,'CV' as type,cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,cv.nTotalBalance as balance,SUM(cvd.nAmount) as debit,
                                  '' as credit,cv.vEnteredBy as issued,
                                  '' as subacc,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId) UNION ALL
                                  (SELECT dv.nDVId as tableId,'DV' as type,dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,
                                  '' as credit,dv.vEnteredBy as issued,
                                  '' as subacc,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId) UNION ALL
                                  (SELECT jv.nJVId as tableId,'JV' as type,jv.dDate as date,jv.vLocation as location,
                                  jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,jv.nTotalBalance as balance,SUM(jvd.nAmount) as debit,
                                  '' as credit,jv.vEnteredBy as issued,
                                  '' as subacc,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId) UNION ALL
                                  (SELECT ac.nACId as tableId,'ACM' as type,ac.dDate as date,ac.vLocation as location,ac.vDocNumber
                                  as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'' as balance,SUM(acd.nAmount) as debit,'' as credit,
                                  ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId) UNION ALL
                                  (SELECT ad.nADId as tableId,'ADM' as type,ad.dDate as date,ad.vLocation as location,ad.vDocNumber
                                  as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'' as balance,'' as debit,SUM(adm.nAmount) as credit,
                                  ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails adm ON
                                  adm.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId) ORDER BY date DESC";
                break;
        }//end switch

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

    function getCashReceiptTransaction($extraCheck_cr)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery = "(SELECT cr.nCRId as tableId,cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.vSubCode as subacc,'CR' as type,cr.nTotalBalance as balance,cr.vAmountDC as debit,'' as credit,cr.vEnteredBy as issued,
                                  cr.nAcCode as account,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,cr.vRate,cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr WHERE cr.vActive='1'
                                 ".$extraCheck_cr.")";
            break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT cr.nCRId as tableId,'CR' as type,cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
                                  cr.nAcCode as account,cr.nTotalBalance as balance,SUM(crd.nAmount) as debit,'' as credit,cr.vEnteredBy as issued,
                                  cr.nAcCode as subacc,cr.vRefNumber,cr.dRefDate,cr.nCurrency,cr.vAmountFC as amountFC,cr.vRate,cr.vAmountDC as amountDC FROM
                                 {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
                                 ".$extraCheck_cr." GROUP BY cr.nCRId)";
            break;
        }//end switch

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

    function getCashPaymentTransaction($extraCheck_cp)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery = "(SELECT cp.nCPId as tableId,cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.vSubCode as subacc,'CP' as type,cp.nTotalBalance as balance,cp.vAmountDC as credit,
                                  '' as debit,cp.vEnteredBy as
                                  issued,cp.nAcCode as account,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp WHERE cp.vActive='1' ".$extraCheck_cp.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT cp.nCPId as tableId,'CP' as type,cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as
                                  description,cp.nAcCode as account,cp.nTotalBalance as balance,'' as debit,SUM(cpd.nAmount) as credit,cp.vEnteredBy as
                                  issued,cp.nAcCode as subacc,cp.vRefNumber,cp.dRefDate,cp.nCurrency,cp.vAmountFC as amountFC,cp.vRate,
                                  cp.vAmountDC as amountDC FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' ".$extraCheck_cp." GROUP BY cp.nCPId)";
                break;
        }//end switch
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

    function getBankDepositTransaction($extraCheck_bb)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT bb.nBBId as tableId,bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.vSubCode as subacc,'BR' as type,bb.nTotalBalance as balance,
                                  bb.vAmountDC as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as account,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb WHERE bb.vActive='1' ".$extraCheck_bb.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT bb.nBBId as tableId,'BR' as type,bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as
                                  description,bb.nAcCode as account,bb.nTotalBalance as balance,
                                  SUM(bbd.nAmount) as debit,'' as credit,bb.vEnteredBy
                                  as issued,bb.nAcCode as subacc,bb.vRefNumber,bb.dRefDate,bb.nCurrency,bb.vAmountFC as amountFC,
                                  bb.vRate,bb.vAmountDC as amountDC FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' ".$extraCheck_bb." GROUP BY bb.nBBId)";
                break;
        }//end switch

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

    function getBankPaymentTransaction($extraCheck_bp)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT bp.nBPId as tableId,bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.vSubCode as subacc,'BP' as type,bp.nTotalBalance as balance,
                                  bp.vAmountDC as credit,'' as debit,bp.vEnteredBy
                                  as issued,bp.nAcCode as account,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp WHERE bp.vActive='1' ".$extraCheck_bp.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT bp.nBPId as tableId,'BP' as type,bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as
                                  description,bp.nAcCode as account,bp.nTotalBalance as balance,
                                  '' as debit,SUM(bpd.nAmount) as credit,bp.vEnteredBy
                                  as issued,bp.nAcCode as subacc,bp.vRefNumber,bp.dRefDate,bp.nCurrency,bp.vAmountFC as amountFC,
                                  bp.vRate,bp.vAmountDC as amountDC FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON
                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' ".$extraCheck_bp." GROUP BY bp.nBPId)";
                break;
        }//end switch

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

    function getCreditVoucherTransaction($extraCheck_cv)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT cv.nCVId as tableId,cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as subacc,'CV' as type,cv.nTotalBalance as balance,'' as debit,
                                  '' as credit,cv.vEnteredBy as issued,
                                  '' as account,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv WHERE cv.vActive='1' ".$extraCheck_cv.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT cv.nCVId as tableId,'CV' as type,cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as
                                  description,'' as account,cv.nTotalBalance as balance,SUM(cvd.nAmount) as debit,'' as credit,cv.vEnteredBy as issued,
                                  '' as subacc,cv.vRefNumber,cv.dRefDate,cv.nCurrency,'' as amountFC,cv.vRate,'' as amountDC FROM
                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd
                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' ".$extraCheck_cv." GROUP BY cv.nCVId)";
                break;
        }//end switch

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

    function getDebitoucherTransaction($extraCheck_dv)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT dv.nDVId as tableId,dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as subacc,'DV' as type,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,
                                  '' as credit,dv.vEnteredBy as issued,
                                  '' as account,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
                                  {PRE}DebitVoucher dv WHERE dv.vActive='1' ".$extraCheck_dv.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT dv.nDVId as tableId,'DV' as type,dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as
                                  description,'' as account,dv.nTotalBalance as balance,SUM(dvd.nAmount) as debit,'' as credit,dv.vEnteredBy as issued,
                                  '' as subacc,dv.vRefNumber,dv.dRefDate,dv.nCurrency,'' as amountFC,dv.vRate,'' as amountDC FROM
                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON
                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' ".$extraCheck_dv." GROUP BY dv.nDVId)";
                break;
        }//end switch

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

    function getJournalVoucherTransaction($extraCheck_jv)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT jv.nJVId as tableId,jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as subacc,'JV' as type,jv.nTotalBalance as balance,'' as debit,
                                  '' as credit,jv.vEnteredBy as issued,
                                  '' as account,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv WHERE jv.vActive='1' ".$extraCheck_jv.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT jv.nJVId as tableId,'JV' as type,jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as
                                  description,'' as account,jv.nTotalBalance as balance,SUM(jvd.nAmount) as debit,'' as credit,jv.vEnteredBy as issued,
                                  '' as subacc,jv.vRefNumber,jv.dRefDate,jv.nCurrency,jv.vAmountFC as amountFC,jv.vRate,jv.vAmountDC as amountDC
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' ".$extraCheck_jv." GROUP BY jv.nJVId)";
                break;
        }//end switch

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

    function getACMTransaction($extraCheck_ac)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT ac.nACId as tableId,ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'ACM' as type,'' as balance,ac.vAmountDC as debit,'' as credit,ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac WHERE ac.vActive='1' ".$extraCheck_ac.")";
            break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as
                                  description,ac.nAcCode as account,'' as balance,SUM(acd.nAmount) as debit,'' as credit,ac.vEnteredBy as
                                  issued,ac.vSubCode as subacc,ac.vRefNumber,ac.dRefDate,ac.nCurrency,ac.vAmountFC as amountFC,ac.vRate,
                                  ac.vAmountDC as amountDC FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON
                                  acd.nAcId = ac.nACId WHERE ac.vActive='1' ".$extraCheck_ac." GROUP BY ac.nACId)";
            break;
        }

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

    function getADMTransaction($extraCheck_ad)
    {
        switch($this->ddlReport)
        {
            case "Details":
                $adminGroupListQuery  = "(SELECT ad.nADId as tableId,ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'ADM' as type,'' as balance,ad.vAmountDC as credit,'' as debit,ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad WHERE ad.vActive='1' ".$extraCheck_ad.")";
                break;

            case "Summary":
                $adminGroupListQuery  = "(SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as
                                  description,ad.nAcCode as account,'' as balance,'' as debit,SUM(adm.nAmount) as credit,ad.vEnteredBy as
                                  issued,ad.vSubCode as subacc,ad.vRefNumber,ad.dRefDate,ad.nCurrency,ad.vAmountFC as amountFC,ad.vRate,
                                  ad.vAmountDC as amountDC FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails adm ON
                                  adm.nAdId = ad.nADId WHERE ad.vActive='1' ".$extraCheck_ad." GROUP BY ad.nADId)";
                break;
        }

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

    function getDetailValues($table,$id)
    {
        $adminGroupRow          = array();
        switch($table)
        {
            case "CR":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                             as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                             {PRE}CashReceiptDetails WHERE nCashId='".$id."'";
            break;

            case "CP":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                {PRE}CashPaymentDetails WHERE nCashPId='".$id."'";
            break;

            case "BR":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}BankBookDetails WHERE nBankbookId='".$id."'";
            break;

            case "BP":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}BankBookPaymentDetails WHERE nBankPaymentId='".$id."'";
            break;

            case "JV":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}JvDetails WHERE nJournalId='".$id."'";
            break;

            case "CV":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}CreditVoucherDetails WHERE nCreditId='".$id."'";
            break;

            case "DV":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}DebitVoucherDetails WHERE nDebitId='".$id."'";
            break;

            case "ACM":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}AcmEntryDetails WHERE nAcId='".$id."'";
            break;

            case "ADM":
                $queryval = "SELECT vTable,vACode,vAcSubCode,vMode,nAmount,CASE WHEN vMode = 'Cr' THEN nAmount ELSE 0 END
                                                    as creditVal,CASE WHEN vMode = 'Dr' THEN nAmount ELSE 0 END as debitVal FROM
                                                    {PRE}AdmEntryDetails WHERE nAdId='".$id."'";
            break;
        }//end switch

        $adminGroupList = $this->db->query($queryval);
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
