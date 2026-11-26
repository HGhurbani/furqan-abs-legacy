<?php

class Alloc_report_model extends CI_Model {



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



    function getActiveAcName()

    {

        $typeGroupRow = array();

        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

                                                FROM tbl_AccountHeadAccounts aca LEFT JOIN tbl_AccountHead ah

                                              ON aca.nHAID = ah.nAId LEFT JOIN tbl_AccountHeadControls ahc ON ahc.nActHId = aca.nId

                                                LEFT JOIN tbl_CustomerMaster v ON v.nFId = ahc.nId

                                              WHERE ah.vActive = '1' AND ahc.vAllocation='1'   ORDER BY aca.vTitle ASC ");
//AND ahc.vSubLedgerType IN ('V','C')
  
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



    function mainReportArray()

    {

        $mainReportArrayList = array("Detail" => "Detail","Summary" => "Summary");



        return ($mainReportArrayList);

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



    function getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_rc)

    {

        $adminGroupListQuery  = "(SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,

                                  cr.nAcCode,cr.nTotalDebit as debit,crd.nAcAllocatedAmt as allocated,cr.vAmountDC as credit,cr.vEnteredBy as issued,

                                  cr.nCRId as tableId,'CR' as tblName FROM

                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'

                                  AND cr.nCRId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'CR')

                                  ".$extraCheck_cr." GROUP BY cr.nCRId)



                                  UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as

                                  description,bb.nAcCode,bb.nTotalDebit as debit,bbd.nAcAllocatedAmt as allocated,bb.vAmountDC as credit,bb.vEnteredBy

                                  as issued,bb.nBBId as tableId,'BR' as tblName FROM {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON

                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bb.nBBId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'BR') ".$extraCheck_bb." GROUP BY bb.nBBId)



                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as

                                  description,'',jv.nTotalDebit as debit,jvd.nAcAllocatedAmt as allocated,SUM(jvd.nAmount) as credit,jv.vEnteredBy as issued

                                  ,jv.nJVId as tableId,'JV' as tblName

                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId

                                  WHERE jv.vActive='1' AND jv.nJVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'JV')

                                  ".$extraCheck_jv." GROUP BY jv.nJVId)

                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as

                                  description,'',cv.nTotalDebit as debit,cvd.nAcAllocatedAmt as allocated,SUM(cvd.nAmount) as credit,cv.vEnteredBy as issued

                                   ,cv.nCVId as tableId,'CV' as tblName FROM

                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd

                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cv.nCVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'CV') ".$extraCheck_cv." GROUP BY cv.nCVId)

                               
							      UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as

                                  description,'',dv.nTotalDebit as debit,dvd.nAcAllocatedAmt as allocated,SUM(dvd.nAmount) as credit,dv.vEnteredBy as

                                  issued,dv.nDVId as tableId,'DV' as tblName FROM

                                  {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON

                                  dvd.nDebitId = dv.nDVId WHERE dv.vActive='1' AND dv.nDVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'DV') ".$extraCheck_dv." GROUP BY dv.nDVId)
								  
								 
								 
								  UNION ALL (SELECT rc.dDate as date,rc.vLocation as location,rc.vDocNumber as document,rc.vRemarks as

                                  description,'','' as debit,rcd.nAcAllocatedAmt as allocated,SUM(rcd.vPaidAmt) as credit,'' as

                                  issued,rc.nRCId as tableId,'RC' as tblName FROM

                                  {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON

                                  rcd.nRefundCloseId = rc.nRCId WHERE rc.vActive='1' AND rc.nRCId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'RC') ".$extraCheck_rc." GROUP BY rc.nRCId)";

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

function getFilterList($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc,$extraCheck_rc_rr = " 1 = 0",$string,$extraCheck_op,$acc)

    {
		
		  $location =  $this->session->userdata('ddlLocation');
		//echo $acc;  
       $locGroupQuery    = "(SELECT nOBId as idVal,opd.nId as detIdVal,'' as remarks, dDate as dDate,'OPENBAL' as document,'OP' as type,opd.vBalDC as creditAmt,

        opd.nAcAllocatedAmt as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}OpeningBalance op

          LEFT JOIN {PRE}OpeningBalanceDetails opd ON opd.nBalId = op.nOBId ".$extraCheck_op."

          )  
	   
	   
	   UNION ALL(SELECT nCPId as idVal,cpd.nId as detIdVal,cpd.vDes as remarks, dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

        SUM(cpd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}CashPayment cp

          LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp."

          GROUP BY cp.nCPId) 



          UNION ALL (SELECT nBPId as

              idVal, bpd.nId as detIdVal,bpd.vDes as remarks,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt ,  SUM(bpd.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}BankBookPayment bp

              LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." GROUP BY bp.nBPId) 



          UNION ALL(SELECT nCRId as idVal,crd.nId as detIdVal,crd.vDes as remarks,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

              SUM(crd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}CashReceipt cr

              LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." GROUP BY cr.nCRId) 



          UNION ALL

          (SELECT nBBId as idVal,bbd.nId as detIdVal,bbd.vDes as remarks,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,
	
           SUM(bbd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}BankBook bb

           LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." GROUP BY bb.nBBId) 



          UNION ALL

          (SELECT nCVId as idVal,cvd.nId as detIdVal,cvd.vDes as remarks,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount)as creditAmt,

          SUM(cvd.nAcAllocatedAmt) 

           as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}CreditVoucher cv

           LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." GROUP BY cv.nCVId) 



          UNION ALL

          (SELECT nDVId as idVal,dvd.nId as detIdVal,dvd.vDes as remarks,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,

          SUM(dvd.nAcAllocatedAmt) 

           as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}DebitVoucher dv

           LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." GROUP BY dv.nDVId)



          UNION ALL ( SELECT nJVId as

              idVal,jvd.nId as detIdVal,jv.vRemarks as remarks,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt, SUM(jvd.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." GROUP BY jv.nJVId)



          UNION ALL ( SELECT ac.nACId as

              idVal,acd.nId as detIdVal,acd.vDes as remarks,dDate as dDate,vDocNumber as document,'AC' as type,SUM(acd.nAmount) as creditAmt, SUM(acd.nAcAllocatedAmt)

              as allocated, '' as txtType, '' as tnumber,'' as airCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId ".$extraCheck_ac." GROUP BY ac.nACId)



          UNION ALL ( SELECT ad.nADId as

              idVal,aed.nId as detIdVal,aed.vDes as remarks,dDate as dDate,vDocNumber as document,'AD' as type,SUM(aed.nAmount) as creditAmt, SUM(aed.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails aed ON aed.nAdId= ad.nADId ".$extraCheck_ad." GROUP BY ad.nADId)



          UNION ALL ( SELECT ac.nACId as

              idVal,'0' as detIdVal,'' as remarks,dDate as dDate,vDocNumber as document,'ACM' as type,SUM(ac.vAmountDC) as creditAmt, SUM(ac.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}AcmEntry ac ".$extraCheck_ac_top." GROUP BY ac.nACId)



          UNION ALL ( SELECT ad.nADId as

              idVal,'0' as detIdVal,'' as remarks,dDate as dDate,vDocNumber as document,'ADM' as type,SUM(ad.vAmountDC) as creditAmt, SUM(ad.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}AdmEntry ad ".$extraCheck_ad_top." GROUP BY ad.nADId)
		
         
         
          UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RC' as type,SUM(rcd.vNetAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rr)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE  rc.vLocation='".$location."' AND ".$extraCheck_rc_rr." GROUP BY rcd.nId )";
		
				//echo $rcc=$this->session->userdata('ddlAccount');
				



				 if($acc!=55){
					 if($this->session->userdata('ddlAccount')==23){
					
					if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt = rcd.vPaidAmt') !== false) {
				$extraCheck_r=str_replace('AND rcd.nAcAllocatedAmt = rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated = creditAmt";
				}
						
							 $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RCC' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rc)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_r." GROUP BY rcd.nId".$pen." )
";
				}else{
					if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt = rcd.vPaidAmt') !== false) {
				$extraCheck_rc=str_replace('AND rcd.nAcAllocatedAmt = rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated = creditAmt";
				}
				  $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RC' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rr)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId ".$pen.")
				";}
			 }

		
		if($string==1){
				//echo $extraCheck_dv;
				if (strpos($extraCheck_dv, 'dvd.nAcAllocatedAmt = dvd.nAmount') !== false) {
				//echo"Allocated";	
				$ext=" AND sad.nAcAllocatedAmt = sad.vAmt";
				}
				elseif (strpos($extraCheck_dv, 'dvd.nAcAllocatedAmt < dvd.nAmount') !== false) {
				//echo"Pending";	
				$ext=" AND sad.nAcAllocatedAmt < sad.vAmt";
				}
				else {
				//echo"All";	
				$ext='';
				}
				/*UNION ALL ( SELECT rc.nRCId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RC' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rr)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId )
*/ //echo //$acc =  $this->session->userdata('ddlSubAccount');
			
			 
			 
			$locGroupQuery .= "UNION ALL ( SELECT sad.nId as

              idVal,sad.nId as detIdVal,sad.vRefTkt as remarks,sc.dDate as dDate, sc.vInvoiceNumber as document,'SC' as type,SUM(sad.vAmt) as creditAmt, 
			  SUM(sad.nAcAllocatedAmt) as allocated,'' as tnumber,'' as txtType,'' as airCode 
			  FROM {PRE}SalesClosing sc 
			   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
		WHERE sad.nDrAccount=".$acc." AND sc.vLocation='".$location."'".$ext." GROUP BY sad.nId )";
		//echo $ex3;
		}
		/*
			 echo " ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,rc.dDate as dDate, rcd.nTicketNumber as document,'RCR' as type,SUM(rcd.vNetAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated, rcd.vType as txtType, rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE ".$extraCheck_rc_rr." GROUP BY rcd.nId ) "; 

      //angela
*/
//      echo  "<p style='text-transform:none;'><pre>".$locGroupQuery." </pre></p>";

//      return;

 
			$locGroupQuery .= "UNION ALL ( SELECT sad.nId as

              idVal,sad.nId as detIdVal,sad.vRefTkt as remarks,sc.dDate as dDate, sc.vInvoiceNumber as document,'SC' as type,SUM(sad.vAmt) as creditAmt, 
			  SUM(sad.nAcAllocatedAmt) as allocated,'' as tnumber,'' as txtType,'' as airCode 
			  FROM {PRE}SalesClosing sc 
			   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
		WHERE sad.nDrAccount=".$acc." AND  sad.nAcHead=53 AND sc.vLocation='".$location."'".$ext." GROUP BY sad.nId )";
		
		$locGroup    = $this->db->query($locGroupQuery);

      if ($locGroup->num_rows() > 0)

      {

        foreach ($locGroup->result() as $key => $row)

        {

// for Rc retrieve the correct ticket number

          if($row->type == 'RC' || $row->type == 'RCR'){

            switch($row->txtType) {

              case "Xo" : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $row->document."'"); break;

              case "Tkt" : $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $row->document."'"); break;

              case "Htl" : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $row->document."'"); break;

            }

            if($tktNum != '')  $row->document = $tktNum;

            if($row->txtType != 'Htl') $row->document = $row->document;

          }

          $locGroupRow[] = $row;

        }//end for each

      }//end if

      else{

        $locGroupRow = array();

      }

      return ($locGroupRow);

    }//end function

    function getPayable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv)

    {
		print "(SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as

                                  description,cp.nAcCode,cp.nTotalDebit as debit,cp.nTotalAllocated as allocated,cp.vAmountDC as credit,

                                  cp.vEnteredBy as

                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON

                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cp.nCPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'CP') ".$extraCheck_cp." GROUP BY cp.nCPId)";

        $adminGroupListQuery  = "(SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as

                                  description,cp.nAcCode,cp.nTotalDebit as debit,cp.nTotalAllocated as allocated,cp.vAmountDC as credit,

                                  cp.vEnteredBy as

                                  issued,cp.nCPId as tableId,'CP' as tblName FROM {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON

                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cp.nCPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'CP') ".$extraCheck_cp." GROUP BY cp.nCPId)

                                  UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as

                                  description,bp.nAcCode,bp.nTotalDebit as debit,bp.nTotalAllocated as allocated,bp.vAmountDC as credit,bp.vEnteredBy

                                  as issued,bp.nBPId as tableId,'BP' as tblName FROM {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON

                                  bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bp.nBPId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca

                                  WHERE ca.vAllType = 'BP') ".$extraCheck_bp." GROUP BY bp.nBPId)

                                  UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as

                                  description,'',jv.nTotalDebit as debit,jv.nTotalAllocated as allocated,SUM(jvd.nAmount) as credit,jv.vEnteredBy as issued

                                  ,jv.nJVId as tableId,'JV' as tblName

                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId

                                  WHERE jv.vActive='1' AND jv.nJVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation ca WHERE ca.vAllType = 'JV')

                                  ".$extraCheck_jv." GROUP BY jv.nJVId)

                                  UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as

                                  description,'',cv.nTotalDebit as debit,cv.nTotalAllocated as allocated,SUM(cvd.nAmount) as credit,cv.vEnteredBy as issued

                                   ,cv.nCVId as tableId,'CV' as tblName FROM

                                  {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd

                                  ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1' AND cv.nCVId IN (SELECT ca.nReceiptDId FROM {PRE}CashAllocation

                                  ca WHERE ca.vAllType = 'CV') ".$extraCheck_cv." GROUP BY cv.nCVId)

                                  UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as

                                  description,'',dv.nTotalDebit as debit,dv.nTotalAllocated as allocated,SUM(dvd.nAmount) as credit,dv.vEnteredBy as

                                  issued,dv.nDVId as tableId,'DV' as tblName FROM

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



    function getAllocation($tableId,$tblName)

    {

		$adminGroupRow  = array();

		

        $adminGroupList = $this->db->query("SELECT * FROM {PRE}CashAllocation WHERE nReceiptDId='".$tableId."' AND vAllType='".$tblName."'");



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

