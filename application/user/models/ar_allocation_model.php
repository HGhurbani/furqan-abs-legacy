<?php

class Ar_allocation_model extends CI_Model {



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



    function getLocation()

    {

        $locGroupRow    = array();

        $locGroupRow[0] = SELECT;

        return ($locGroupRow);

    }//end function



    function filterLocation($division)

    {

        $locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '" . $division . "'

											 AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {
			//$locGroupRow[0] = SELECT;

            foreach ($locGroup->result() as $key => $row)

            {

                $locGroupRow[$row->nId] = stripslashes($row->vTitle);

            }//end for each

        }//end if

        else

        {

            $locGroupRow[0] = NOVALUE;

        }//end else

        return ($locGroupRow);

    }//end function



    function getActiveTransactions()

    {

        $cmsArrayList = array("RA" => "Receivable Allocation", "PA" => "Payable Allocation");

        return ($cmsArrayList);

    }//end function



    function getAllocationType()

    {

        $cmsArrayList = array("P" => "Pending", "A" => "Allocated", "0" => "All",);

        return ($cmsArrayList);

    }//end function



    function getActiveAccount($ddlType)

    {

        switch($ddlType)

        {

            case "RA" :

                $typeGroupRow    = array();

                $OtherGroupRow   = array();



                $typeGroupRow[0] = SELECT;

                $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");

                if ($typeGroup->num_rows() > 0)

                {

                    foreach ($typeGroup->result() as $key=>$row)

                    {

                        $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

                    }//end for each

                }//end if



                $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");

                if ($OtherGroup->num_rows() > 0)

                {

                    foreach ($OtherGroup->result() as $key=>$row)

                    {

                        $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;

                    }//end for each

                }//end if

                $resultArray = array_merge($typeGroupRow,$OtherGroupRow);

            break;



            case "PA" :

                $vendorGroupRow  = array();

                $AgentGroupRow   = array();



                $vendorGroupRow[0] = SELECT;

                $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");

                if ($vendorGroup->num_rows() > 0)

                {

                    foreach ($vendorGroup->result() as $key=>$row)

                    {

                        $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

                    }//end for each

                }//end if



                $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");

                if ($AgentGroup->num_rows() > 0)

                {

                    foreach ($AgentGroup->result() as $key=>$row)

                    {

                        $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

                    }//end for each

                }//end if

                $resultArray = array_merge($vendorGroupRow,$AgentGroupRow);

            break;

        }

        return ($resultArray);

    }//end function

/*

    function getActiveSubAccounts()

    {

        $locGroupRow    = array();

        $locGroupRow[0] = SELECT;

        return ($locGroupRow);

    }//end function

*/

    function getActiveSubAccounts()

    {

/*        $custGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

                                                WHERE ah.vActive = '1' AND(ahc.vSubSale = 'C' AND vSubLedger = 'S' OR

                                                ahc.vSubSale = 'B' AND vSubLedger = 'S') ORDER BY nId DESC");

*/



        $custGroup = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

                                                FROM tbl_AccountHeadAccounts aca LEFT JOIN tbl_AccountHead ah

                                              ON aca.nHAID = ah.nAId LEFT JOIN tbl_AccountHeadControls ahc ON ahc.nActHId = aca.nId

                                                LEFT JOIN tbl_CustomerMaster v ON v.nFId = ahc.nId

                                              WHERE ah.vActive = '1' AND ahc.vAllocation = '1' AND ahc.vSubLedgerType != 'N'  ORDER BY ahc.vSubLedgerType, aca.vTitle ASC ");





        $custGroupRow = array();



        if ($custGroup->num_rows() > 0)

        {

            $custGroupRow[0] = OTHERACCOUNTS;

            foreach ($custGroup->result() as $key => $row)

            {
				if($row->vSubLedgerType=='A'){
					$row->vSubLedgerType='AG';
				}
                $custGroupRow[$row->ahaId . "-" . $row->vSubLedgerType] = (stripslashes($row->vTitle));

            }//end for each

        }//end if

        else

        {

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);

    }//end function



    function getAccounts()

    {

        $locGroupRow    = array();

        $locGroupRow[0] = NOVALUE;

        return ($locGroupRow);

    }//end function



/*

    function getAccounts()

    {

        $typeGroupRow    = array();

        $vendorGroupRow  = array();

        $AgentGroupRow   = array();

        $OtherGroupRow   = array();

        $accheadGroupRow = array();



        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");

        if ($typeGroup->num_rows() > 0)

        {

            foreach ($typeGroup->result() as $key=>$row)

            {

                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if



        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY vName ASC");

        if ($vendorGroup->num_rows() > 0)

        {

            foreach ($vendorGroup->result() as $key=>$row)

            {

                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);



        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY vName ASC");

        if ($AgentGroup->num_rows() > 0)

        {

            foreach ($AgentGroup->result() as $key=>$row)

            {

                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($AgentGroupRow,$resultArray);



        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY vName ASC");

        if ($OtherGroup->num_rows() > 0)

        {

            foreach ($OtherGroup->result() as $key=>$row)

            {

                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($OtherGroupRow,$resultArray);

/* 

	$accheadGroup = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode FROM {PRE}AccountHeadAccounts aha

			INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId

			WHERE ahc.vSubLedgerType NOT IN ( 'V','C','E','A') ORDER BY aha.vTitle ");



        if ($accheadGroup->num_rows() > 0)

        {

            $accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key1=>$row1)

            {

                $accheadGroupRow[$row1->nId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;

            }//end for each

        }//end if



        else

        {

            $accheadGroupRow[0] = SELECT;

        }

        $resultArray = array_merge($accheadGroupRow,$resultArray);

*/

/*        natcasesort($resultArray);

        $resultArray = array('0'=>SELECT) + $resultArray;

        return ($resultArray);

	

    }//end function

*/





    function filterSubAccount($accName,$table)

    {

      $location = $this->input->post('ddlLocation');

      $division = $this->input->post('ddlDivision');



      switch($table)

      {

        case "C" :

          $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nFId = '".$accName."' ORDER BY nCId DESC");

          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              $locArray = explode(',',$row->vLocation);

              if(in_array($location, $locArray))

                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

            }//end for each

          }//end if

          break;

        case "V" :



          $typeGroup= $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$accName."' ORDER BY nVId DESC");

          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              $locArray = explode(',',$row->vLocation);

              if(in_array($location, $locArray))

                $typeGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

            }//end for each

          }//end if

          break;

        case "A":

          $typeGroup = $this->db->query("SELECT * FROM {PRE}Agent WHERE nFId = '".$accName."' ORDER BY nAId DESC");

          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              //           $typeGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

              $locArray = explode(',',$row->vLocations);

              if(in_array($location, $locArray))

                $typeGroupRow[$row->nAId.'-'.'A'] = $row->vName.' - '.$row->vCode;

            }//end for each

          }//end if

        break;
		case "AG":

          $typeGroup = $this->db->query("SELECT * FROM {PRE}Agent WHERE nFId = '".$accName."' ORDER BY nAId DESC");

          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              //           $typeGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

              $locArray = explode(',',$row->vLocations);

              if(in_array($location, $locArray))

                $typeGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

            }//end for each

          }//end if

        break;

        case "O" :

          $typeGroup = $this->db->query("SELECT * FROM {PRE}Other WHERE nFId = '".$accName."' ORDER BY nOId DESC");

          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              $locArray = explode(',',$row->vLocation);

              if(in_array($location, $locArray))

                $typeGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;

            }//end for each

          }//end if

          break;

        default:

          $typeGroup = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode,ahc.vDivision, ahc.vLocation FROM {PRE}AccountHeadAccounts aha

              INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId

              WHERE ahc.vSubLedgerType NOT IN ( 'V','C','E','A') AND ahc.vAllocation = '1' ORDER BY aha.vTitle ");



          if ($typeGroup->num_rows() > 0)

          {

            foreach ($typeGroup->result() as $key=>$row)

            {

              //                $accheadGroupRow[$row1->nId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;

              if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))

                $typeGroupRow[$row->nId.'-'.'A'] = $row->vTitle.' - '.$row->vACode;

            }//end for each

          }//end if

        break;

      }

      return $typeGroupRow;

    } //end function



/*

    function filterSubAccount($accGroup,$table)

    {

        switch($table)

        {

            case "C" :

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

                break;



            case "V" :

                $accNameId       = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = '".$accGroup."' ");

                $accheadGroupRow = array();

                $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

														LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId

													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' AND v.nFId = '".$accNameId."'

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

                break;



            case "AG" :

                $accNameId       = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId = '".$accGroup."' ");

                $accheadGroupRow = array();

                $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

														LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nId

													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' AND v.nFId = '".$accNameId."'

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

                break;



            case "O" :

                $accNameId       = $this->common_model->get_field_name('Other','nFId'," WHERE nOId = '".$accGroup."' ");

                $accheadGroupRow = array();

                $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

														LEFT JOIN {PRE}Other v ON v.nFId = ahc.nId

													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'O' AND v.nFId = '".$accNameId."'

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

                break;



            case "A" :

                $accheadGroupRow    = array();

                $accheadGroupRow[0] = NOVALUE;

                break;

        }//end switch

        return ($accheadGroupRow);

    }//end function

*/

    function getAllocationValue1()

    {

        $locGroup    = $this->db->query("(SELECT nCRId as idVal,dDate as dDate,vDocNumber as document,'CR' as type,nTotalDebit as creditAmt,

                                            nTotalAllocated as allocated FROM {PRE}CashReceipt ORDER BY nCRId DESC) UNION ALL

                                            (SELECT nCPId as idVal,dDate as dDate,vDocNumber as document,'CP' as type,nTotalDebit as creditAmt,

                                            nTotalAllocated as allocated FROM {PRE}CashPayment ORDER BY nCPId DESC) UNION ALL

								  	        (SELECT nBBId as idVal,dDate as dDate,vDocNumber as document,'BR' as type,nTotalDebit as creditAmt,

								  	        nTotalAllocated as allocated FROM {PRE}BankBook ORDER BY nBBId DESC) UNION ALL (SELECT nBPId as

                                            idVal,dDate as dDate,vDocNumber as document,'BP' as type,nTotalDebit as creditAmt,nTotalAllocated

                                            as allocated FROM {PRE}BankBookPayment ORDER BY nBPId DESC) UNION ALL

                                            (SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,nTotalDebit as creditAmt,nTotalAllocated

                                            as allocated FROM {PRE}CreditVoucher ORDER BY nCVId DESC) UNION ALL

								  	        (SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,nTotalDebit as creditAmt,nTotalAllocated

                                            as allocated FROM {PRE}DebitVoucher ORDER BY nDVId DESC) UNION ALL ( SELECT nJVId as

                                            idVal,dDate as dDate,vDocNumber as document,'JV' as type,nTotalDebit as creditAmt,nTotalAllocated

                                            as allocated FROM {PRE}Jv ORDER BY nJVId DESC)");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {

            foreach ($locGroup->result() as $key => $row)

            {

                $locGroupRow[] = $row;

            }//end for each

        }//end if

        return ($locGroupRow);

    }//end function



    function getAllocatedDetails($tableId,$tktType)

    {
echo "SELECT * FROM {PRE}CashAllocation WHERE nReceiptDId = '".$tableId."'

                                            AND vAllType = '".$tktType."'";
        $invoiceNum   = $this->db->query("SELECT * FROM {PRE}CashAllocation WHERE nReceiptDId = '".$tableId."'

                                            AND vAllType = '".$tktType."'");

        if ($invoiceNum->num_rows() > 0)

        {

            foreach ($invoiceNum->result() as $key=>$row)

            {

                $invoiceNumRow[] = $row;

            }//end for each

        }//end if

        return ($invoiceNumRow);

    }//end function



    function getPendingDetails()

    {

      $account =  $this->ddlAccount;

      $tableVal = $this->tableVal;

      $subAccount = $this->ddlSubAccount;
	  

      $adminGroupList   = $this->db->query("SELECT sc.*,scd.*, sad.vTable,sad.nDrAccount,ca.vAllTktNumber ,

                CASE WHEN ca.vBalAmt > 0 THEN (scd.vAllocation - (CASE WHEN

                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                AND nSalesCloseDetId = scd.nId

                AND nDrAccount = '".$account."' AND

                vTable = '".$tableVal."') is null THEN 0

                ELSE

                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                AND nSalesCloseDetId = scd.nId

                AND nDrAccount = '".$account."' AND

                vTable = '".$tableVal."') END)) ELSE scd.vAllocation END AS allocAmt ,

                CASE WHEN scd.vAllocation - (CASE WHEN

                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                AND nSalesCloseDetId = scd.nId

                AND nDrAccount = '".$account."' AND

                vTable = '".$tableVal."') is null THEN 0

                ELSE

                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                AND nSalesCloseDetId = scd.nId

                AND nDrAccount = '".$account ."' AND

                vTable = '".$tableVal."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                WHERE sad.nDrAccount = '".$account."' AND sad.nAcHead = '".$subAccount."'

                AND scd.vAllocation > 0

                GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
				
				
				



      if ($adminGroupList->num_rows() > 0)

      {

        foreach ($adminGroupList->result() as $key=>$row)

        {

          $adminGroupRow[] = $row;

        }//end for each

      }//end if

      return ($adminGroupRow);

    } //end function



    function getPendingAllocationDetails($ddlAccount,$ddlSubAccount)

    {
		//echo"test";

        $invoiceNum		= $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,

												CASE WHEN ca.vBalAmt > 0 THEN vBalAmt

											  ELSE scd.vCollected END AS allocAmt FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails

												scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails

											  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON ca.nSalesCloseId = sc.nSCId

												WHERE sad.nDrAccount = '".$ddlAccount."' AND

											  sad.nAcHead = '".$ddlSubAccount."' AND (scd.nId NOT IN(SELECT nSalesCloseDetId FROM {PRE}CashAllocation) OR (ca.vBalAmt > 0)

												AND ( sc.vTotalCollected - (SELECT sum(vAllAmt) FROM {PRE}CashAllocation) > 0))

											  ORDER BY sc.dDate DESC");

        if ($invoiceNum->num_rows() > 0)

        {

            foreach ($invoiceNum->result() as $key=>$row)

            {

                $invoiceNumRow[] = $row;

            }//end for each

        }//end if

        return ($invoiceNumRow);

    }//end function



    function getFilterList($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc,$extraCheck_rc_rr = " 1 = 0",$string,$extraCheck_op,$acc)

    {
		
		
		//echo $acc;

	/*echo "(SELECT nOBId as idVal,opd.nId as detIdVal,'' as remarks, dDate as dDate,'OPENBAL' as document,'OP' as type,opd.vBalDC as creditAmt,

        opd.nAcAllocatedAmt as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}OpeningBalance op

          LEFT JOIN {PRE}OpeningBalanceDetails opd ON opd.nBalId = op.nOBId ".$extraCheck_op."

          GROUP BY op.nOBId) ";*/
		  $location =  $this->session->userdata('ddlLocation');
		  
		  if (strpos($extraCheck_cp, 'cpd.nAcAllocatedAmt < cpd.nAmount') !== false) {
				$extraCheck_cp=str_replace('AND cpd.nAcAllocatedAmt < cpd.nAmount','',$extraCheck_cp);
$extraCheck_cr=str_replace('AND crd.nAcAllocatedAmt < crd.nAmount','',$extraCheck_cr);
$extraCheck_bb=str_replace('AND bbd.nAcAllocatedAmt < bbd.nAmount','',$extraCheck_bb);
$extraCheck_bp=str_replace('AND bpd.nAcAllocatedAmt < bpd.nAmount','',$extraCheck_bp);	
$extraCheck_jv=str_replace('AND jvd.nAcAllocatedAmt < jvd.nAmount','',$extraCheck_jv);							
				$pen=" HAVING allocated < creditAmt";
				}
		  
       $locGroupQuery    = "(SELECT nOBId as idVal,opd.nId as detIdVal,'' as remarks, dDate as dDate,'OPENBAL' as document,'OP' as type,opd.vBalDC as creditAmt,

        opd.nAcAllocatedAmt as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}OpeningBalance op

          LEFT JOIN {PRE}OpeningBalanceDetails opd ON opd.nBalId = op.nOBId ".$extraCheck_op."

          )  
	   
	   
	   UNION ALL(SELECT nCPId as idVal,cpd.nId as detIdVal,cpd.vDes as remarks, dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

        SUM(cpd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}CashPayment cp

          LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp."

          GROUP BY cp.nCPId".$pen.") 



          UNION ALL (SELECT nBPId as

              idVal, bpd.nId as detIdVal,bpd.vDes as remarks,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt ,  SUM(bpd.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}BankBookPayment bp

              LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." GROUP BY bp.nBPId ".$pen.") 



          UNION ALL(SELECT nCRId as idVal,crd.nId as detIdVal,crd.vDes as remarks,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

              SUM(crd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}CashReceipt cr

              LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." GROUP BY cr.nCRId ".$pen.") 



          UNION ALL

          (SELECT nBBId as idVal,bbd.nId as detIdVal,bbd.vDes as remarks,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,
	
           SUM(bbd.nAcAllocatedAmt) as allocated, '' as tnumber,'' as txtType, '' as airCode FROM {PRE}BankBook bb

           LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." GROUP BY bb.nBBId ".$pen.") 



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

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." GROUP BY jv.nJVId ".$pen.")



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
		
         
         
         ";
		
		 $rcc =  $this->session->userdata('ddlSubAccount');
	if($rcc==23){	
	
 $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RCC' as type,SUM(rcd.vNetAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rc)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE  rc.vLocation='".$location."' AND ".$extraCheck_rc_rr." GROUP BY rcd.nId )";
	}
	elseif($rcc==53){	
	
				if (strpos($extraCheck_rc_rr, 'rcd.nAcAllocatedAmt_rr < rcd.vNetAmt') !== false) {
				$extraCheck_rc_rr=str_replace('AND rcd.nAcAllocatedAmt_rr < rcd.vNetAmt','',$extraCheck_rc_rr);
				$pen=" HAVING allocated < creditAmt";
				}
 $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RCA' as type,SUM(rcd.vNetAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_ra)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE  rc.vLocation='".$location."' AND ".$extraCheck_rc_rr." GROUP BY rcd.nId ".$pen." )";
	
	}
	
	else{
		 $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RC' as type,SUM(rcd.vNetAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rr)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE  rc.vLocation='".$location."' AND ".$extraCheck_rc_rr." GROUP BY rcd.nId )";

		 
	}
				if($acc!=55){
						
						if($rcc==23){
							//echo $extraCheck_rc;
							if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt < rcd.vPaidAmt') !== false) {
				$extraCheck_rc=str_replace('AND rcd.nAcAllocatedAmt < rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated < creditAmt";
				}
				if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt = rcd.vPaidAmt') !== false) {
				$extraCheck_rc=str_replace('AND rcd.nAcAllocatedAmt = rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated = creditAmt";
				}
						
							 $locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RCC' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rc)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId".$pen." )
";
							
						}
						elseif($rcc==229){
		//echo $rcc;
		
	}
	else{
							if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt < rcd.vPaidAmt') !== false) {
				$extraCheck_rc=str_replace('AND rcd.nAcAllocatedAmt < rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated < creditAmt";
				}
				if (strpos($extraCheck_rc, 'rcd.nAcAllocatedAmt = rcd.vPaidAmt') !== false) {
				$extraCheck_rc=str_replace('AND rcd.nAcAllocatedAmt = rcd.vPaidAmt','',$extraCheck_rc);
				$pen=" HAVING allocated = creditAmt";
				}
						//echo $extraCheck_rc;
						
					
						$locGroupQuery .= " UNION ALL ( SELECT rcd.nId as

              idVal,rcd.nId as detIdVal,'' as remarks,rc.dDate as dDate, rc.vDocNumber as document,'RC' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt_rr)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId".$pen." )
";
						}
						//rc.nRCId as idVal,rcd.nId as detIdVal
				
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

              idVal,sad.nId as detIdVal,CONCAT(sad.vRefTkt, ' - ', sc.vRemarks) as remarks,sc.dDate as dDate, sc.vInvoiceNumber as document,'SC' as type,SUM(sad.vAmt) as creditAmt, 
			  SUM(sad.nAcAllocatedAmt) as allocated,'' as tnumber,'' as txtType,'' as airCode 
			  FROM {PRE}SalesClosing sc 
			   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
		WHERE sad.nDrAccount=".$acc." AND sc.vLocation='".$location."'".$ext." GROUP BY sad.nId )";
		//echo $ex3;
		}
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
		$locGroupQuery .= "UNION ALL ( SELECT sad.nId as

              idVal,sad.nId as detIdVal,sad.vRefTkt as remarks,sc.dDate as dDate, sc.vInvoiceNumber as document,'SC' as type,SUM(sad.vAmt) as creditAmt, 
			  SUM(sad.nAcAllocatedAmt) as allocated,'' as tnumber,'' as txtType,'' as airCode 
			  FROM {PRE}SalesClosing sc 
			   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
		WHERE sad.nDrAccount=".$acc." AND  sad.nAcHead=229 AND sc.vLocation='".$location."'".$ext." GROUP BY sad.nId )";
			
			
			$locGroupQuery .= "UNION ALL ( SELECT sad.nId as

              idVal,sad.nId as detIdVal,sad.vRefTkt as remarks,sc.dDate as dDate, sc.vInvoiceNumber as document,'SC' as type,SUM(sad.vAmt) as creditAmt, 
			  SUM(sad.nAcAllocatedAmt) as allocated,'' as tnumber,'' as txtType,'' as airCode 
			  FROM {PRE}SalesClosing sc 
			   LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
		WHERE sad.nDrAccount=".$acc." AND  sad.nAcHead=54 AND sc.vLocation='".$location."'".$ext." GROUP BY sad.nId )";
			
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



	

	

	function getFilterList1($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc,$extraCheck_op,$acc,$extraCheck_rc_rr = " 1 = 0")

   {/*   UNION ALL ( SELECT nJVId as

              idVal,jvd.nId as detIdVal,'' as remarks,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt, SUM(jvd.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." ORDER BY jv.nJVId)

*/

$locGroupQuery    = "(SELECT nCPId as idVal,cpd.vDes as remarks,cpd.nId as detIdVal, dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

        SUM(cpd.nAcAllocatedAmt) as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}CashPayment cp

          LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp."

          GROUP BY cp.nCPId) 



          UNION ALL (SELECT nBPId as

              idVal,bpd.vDes as remarks, bpd.nId as detIdVal,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt ,  SUM(bpd.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber,  '' as airCode FROM {PRE}BankBookPayment bp

              LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." GROUP BY bp.nBPId) 



          UNION ALL(SELECT nCRId as idVal,crd.vDes as remarks,crd.nId as detIdVal,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

              SUM(crd.nAcAllocatedAmt) as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}CashReceipt cr

              LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." GROUP BY cr.nCRId)
			  
			  
			  UNION ALL(SELECT nOBId as idVal,'' as remarks,opd.nId as detIdVal,dDate as dDate,'OPBAL' as document,'OP' as type,opd.vBalDC as creditAmt,

              opd.nAcAllocatedAmt as allocated, '' as tnumber, '' as txtType, '' as airCode FROM {PRE}OpeningBalance op

              LEFT JOIN {PRE}OpeningBalanceDetails opd ON opd.nBalId = op.nOBId ".$extraCheck_op." ORDER BY op.nOBId) 



          UNION ALL

          (SELECT nBBId as idVal,bbd.vDes as remarks,bbd.nId as detIdVal,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,

           SUM(bbd.nAcAllocatedAmt) as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}BankBook bb

           LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." GROUP BY bb.nBBId) 



          UNION ALL

          (SELECT nCVId as idVal,cvd.vDes as remarks,cvd.nId as detIdVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount)as creditAmt,

          SUM(cvd.nAcAllocatedAmt) 

           as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}CreditVoucher cv

           LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." GROUP BY cv.nCVId) 



          UNION ALL

          (SELECT nDVId as idVal,dvd.vDes as remarks,dvd.nId as detIdVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,

          SUM(dvd.nAcAllocatedAmt) 

           as allocated, '' as tnumber, '' as txtType, '' as airCode FROM {PRE}DebitVoucher dv

           LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." GROUP BY dv.nDVId)



         UNION ALL ( SELECT nJVId as

              idVal,jv.vRemarks as remarks,jvd.nId as detIdVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt, SUM(jvd.nAcAllocatedAmt)

              as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." GROUP BY jv.nJVId)



          UNION ALL ( SELECT ac.nACId as

              idVal,'' as remarks,acd.nId as detIdVal,dDate as dDate,vDocNumber as document,'AC' as type,SUM(acd.nAmount) as creditAmt, SUM(acd.nAcAllocatedAmt)

              as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId ".$extraCheck_ac." GROUP BY ac.nACId)



          UNION ALL ( SELECT ad.nADId as

              idVal,'' as remarks,aed.nId as detIdVal,dDate as dDate,vDocNumber as document,'AD' as type,SUM(aed.nAmount) as creditAmt, SUM(aed.nAcAllocatedAmt)

              as allocated,'' as tnumber,  '' as txtType, '' as airCode FROM {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails aed ON aed.nAdId= ad.nADId ".$extraCheck_ad." GROUP BY ad.nADId)



          UNION ALL ( SELECT ac.nACId as

              idVal,'' as remarks,'0' as detIdVal,dDate as dDate,vDocNumber as document,'ACM' as type,SUM(ac.vAmountDC) as creditAmt, SUM(ac.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber,  '' as airCode FROM {PRE}AcmEntry ac ".$extraCheck_ac_top." GROUP BY ac.nACId)



          UNION ALL ( SELECT ad.nADId as

              idVal,'' as remarks,'0' as detIdVal,dDate as dDate,vDocNumber as document,'ADM' as type,SUM(ad.vAmountDC) as creditAmt, SUM(ad.nAcAllocatedAmt)

              as allocated, '' as txtType,'' as tnumber, '' as airCode FROM {PRE}AdmEntry ad ".$extraCheck_ad_top." GROUP BY ad.nADId)
			  
		
UNION ALL ( SELECT rc.nRCId as

              idVal,'' as remarks,rcd.nId as detIdVal,rc.dDate as dDate, rc.vDocNumber as document,'RCR' as type,SUM(rc.vTotalNetAmount) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE ".$extraCheck_rc_rr." GROUP BY rcd.nId ) ";

      //angela
/*print "UNION ALL ( SELECT rc.nRCId as

              idVal,rcd.nId as detIdVal,rc.dDate as dDate, rc.vDocNumber as document,'RCR' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId )

          UNION ALL ( SELECT rc.nRCId as

              idVal,rcd.nId as detIdVal,rc.dDate as dDate, rc.vDocNumber as document,'RCR' as type,SUM(rc.vTotalNetAmount) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated, rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId WHERE ".$extraCheck_rc_rr." GROUP BY rcd.nId ) ";
 */
    // echo  "<p style='text-transform:none;'><pre>".$extraCheck_rc." </pre></p>";

//      return; WHERE rc.nDivision='1' AND rc.vLocation='1' AND rcd.nDrAcc  = '55' AND rcd.vTable = 'A'  AND rcd.vTable IN ('C','O','A') AND rcd.nAcAllocatedAmt < rcd.vPaidAmt
//echo $locGroupQuery;


//echo $acc;
if($acc==55  ){
	$locGroupQuery.="UNION ALL ( SELECT rc.nRCId as

              idVal,'' as remarks,rcd.nId as detIdVal,rc.dDate as dDate, rc.vDocNumber as document,'RCR' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId )
	  ";
}else{
	$locGroupQuery.="UNION ALL ( SELECT rc.nRCId as

              idVal,'' as remarks,rcd.nId as detIdVal,rc.dDate as dDate, rc.vDocNumber as document,'RCR' as type,SUM(rcd.vPaidAmt) as creditAmt, SUM(rcd.nAcAllocatedAmt)

              as allocated,rcd.nTicketNumber as tnumber,rcd.vType as txtType,rcd.nAirline as airCode FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId ".$extraCheck_rc." GROUP BY rcd.nId )
	  ";
}
      $locGroup    = $this->db->query($locGroupQuery);

      if ($locGroup->num_rows() > 0)

      {

        foreach ($locGroup->result() as $key => $row)

        {

// for Rc retrieve the correct ticket number

          if($row->type == 'RC' || $row->type == 'RCR'){

            switch($row->txtType) {

              case "Xo"  : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $row->document."'"); break;

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

	   

	   

	   

    }





/*

    function getReceivableFilterList($type,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv)

    {

        switch($type)

        {

            case '0':

                $locGroup    = $this->db->query("(SELECT nCRId as idVal,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

													nTotalAllocated as allocated FROM {PRE}CashReceipt cr

												  LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." GROUP BY cr.nCRId) UNION ALL

													(SELECT nBBId as idVal,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,

												  nTotalAllocated as allocated FROM {PRE}BankBook bb

												    LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." GROUP BY bb.nBBId) UNION ALL

													(SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount)as creditAmt,

													nTotalAllocated

												  as allocated FROM {PRE}CreditVoucher cv

												    LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." GROUP BY cv.nCVId) UNION ALL

													(SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,

													nTotalAllocated

												  as allocated FROM {PRE}DebitVoucher dv

												    LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." GROUP BY dv.nDVId)

												    UNION ALL ( SELECT nJVId as

													idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												  as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;



            case 'P':

                $locGroup    = $this->db->query("(SELECT nCRId as idVal,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

												   nTotalAllocated as allocated FROM {PRE}CashReceipt cr

												  LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." AND nCRId NOT IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CR') GROUP BY cr.nCRId) UNION ALL

												  (SELECT nBBId as idVal,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,

												    nTotalAllocated as allocated FROM {PRE}BankBook bb

												  LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." AND nBBId NOT IN (SELECT nReceiptDId FROM

												    {PRE}CashAllocation WHERE vAllType = 'BR') GROUP BY bb.nBBId) UNION ALL

												  (SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}CreditVoucher cv

												  LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." AND nCVId NOT IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CV') GROUP BY cv.nCVId) UNION ALL

												 (SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}DebitVoucher dv

												  LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." AND nDVId NOT IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'DV') GROUP BY dv.nDVId) UNION ALL ( SELECT nJVId as

												  idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." AND nJVId

												   NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV') GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;



            case 'A':

                $locGroup    = $this->db->query("(SELECT nCRId as idVal,dDate as dDate,vDocNumber as document,'CR' as type,SUM(crd.nAmount) as creditAmt,

												   nTotalAllocated as allocated FROM {PRE}CashReceipt cr

												  LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId ".$extraCheck_cr." AND cr.nCRId  IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CR') GROUP BY cr.nCRId) UNION ALL

												  (SELECT nBBId as idVal,dDate as dDate,vDocNumber as document,'BR' as type,SUM(bbd.nAmount) as creditAmt,

												    nTotalAllocated as allocated FROM {PRE}BankBook bb

												  LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId ".$extraCheck_bb." AND bb.nBBId  IN (SELECT nReceiptDId FROM

												    {PRE}CashAllocation WHERE vAllType = 'BR') GROUP BY bb.nBBId) UNION ALL

												  (SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}CreditVoucher cv

												  LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." AND cv.nCVId  IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CV') GROUP BY cv.nCVId) UNION ALL

												 (SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}DebitVoucher dv

												  LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." AND dv.nDVId  IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'DV') GROUP BY dv.nDVId) UNION ALL ( SELECT nJVId as

												  idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." AND jv.nJVId  IN

												   (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV') GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;

        }//end switch

        return ($locGroupRow);

    }//end function



    function getPayableFilterList($type,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv)

    {

        switch($type)

        {

            case '0':

                $locGroup    = $this->db->query("(SELECT nCPId as idVal,dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

												  nTotalAllocated as allocated FROM {PRE}CashPayment cp

												    LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp."

												    GROUP BY cp.nCPId) UNION ALL (SELECT nBPId as

													idVal,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt,nTotalAllocated

												  as allocated FROM {PRE}BankBookPayment bp

												    LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." GROUP BY bp.nBPId) UNION ALL

													(SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount) as creditAmt,nTotalAllocated

												  as allocated FROM {PRE}CreditVoucher cv

												    LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." GROUP BY cv.nCVId) UNION ALL

													(SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,nTotalAllocated

												  as allocated FROM {PRE}DebitVoucher dv

												    LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." GROUP BY dv.nDVId)

												    UNION ALL ( SELECT nJVId as

													idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												  as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;



            case 'P':

                $locGroup    = $this->db->query("(SELECT nCPId as idVal,dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

												    nTotalAllocated as allocated FROM {PRE}CashPayment cp

												  LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp." AND nCPId NOT IN (SELECT nReceiptDId FROM

												    {PRE}CashAllocation WHERE vAllType = 'CP') GROUP BY cp.nCPId) UNION ALL (SELECT nBPId as

												  idVal,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}BankBookPayment bp

												  LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." AND nBPId NOT IN

												  (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'BP') GROUP BY bp.nBPId) UNION ALL

												  (SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}CreditVoucher cv

												  LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." AND nCVId NOT IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CV') GROUP BY cv.nCVId) UNION ALL

												 (SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}DebitVoucher dv

												  LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." AND nDVId NOT IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'DV') GROUP BY dv.nDVId) UNION ALL ( SELECT nJVId as

												  idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." AND nJVId

												   NOT IN (SELECT nReceiptDId FROM {PRE}CashAllocation WHERE vAllType = 'JV') GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;



            case 'A':

                $locGroup    = $this->db->query("(SELECT nCPId as idVal,dDate as dDate,vDocNumber as document,'CP' as type,SUM(cpd.nAmount) as creditAmt,

												    nTotalAllocated as allocated FROM {PRE}CashPayment cp

												  LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId ".$extraCheck_cp." AND cp.nCPId  IN (SELECT nReceiptDId FROM

												    {PRE}CashAllocation WHERE vAllType = 'CP') GROUP BY cp.nCPId) UNION ALL (SELECT nBPId as

												  idVal,dDate as dDate,vDocNumber as document,'BP' as type,SUM(bpd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}BankBookPayment bp

												  LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId ".$extraCheck_bp." AND bp.nBPId  IN (SELECT nReceiptDId FROM

												    {PRE}CashAllocation WHERE vAllType = 'BP') GROUP BY bp.nBPId) UNION ALL

												  (SELECT nCVId as idVal,dDate as dDate,vDocNumber as document,'CV' as type,SUM(cvd.nAmount) as creditAmt,nTotalAllocated

												    as allocated FROM {PRE}CreditVoucher cv

												  LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId ".$extraCheck_cv." AND cv.nCVId  IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'CV') GROUP BY cv.nCVId) UNION ALL

												 (SELECT nDVId as idVal,dDate as dDate,vDocNumber as document,'DV' as type,SUM(dvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}DebitVoucher dv

												  LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId ".$extraCheck_dv." AND dv.nDVId  IN (SELECT nReceiptDId FROM

												   {PRE}CashAllocation WHERE vAllType = 'DV') GROUP BY dv.nDVId) UNION ALL ( SELECT nJVId as

												  idVal,dDate as dDate,vDocNumber as document,'JV' as type,SUM(jvd.nAmount) as creditAmt,nTotalAllocated

												   as allocated FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId ".$extraCheck_jv." AND jv.nJVId  IN

												   (SELECT nReceiptDId FROM

												  {PRE}CashAllocation WHERE vAllType = 'JV') GROUP BY jv.nJVId)");

                if ($locGroup->num_rows() > 0)

                {

                    foreach ($locGroup->result() as $key => $row)

                    {

                        $locGroupRow[] = $row;

                    }//end for each

                }//end if

                break;

        }//end switch

        return ($locGroupRow);

    }//end function

*/



	function getInvoiceDetails($salesDetId)

	{

		$tktType                = $this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nId = '".$salesDetId."'");

		$tktID                  = $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE nId = '".$salesDetId."'");

		$refundID               = $this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE vType = '".$tktType."' AND nTicketNumber = '".$tktID."'");

        $refundInvoice          = $this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$refundID."'");

		$salesCloseID           = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$salesDetId."'");

		$invoiceNum             = $this->db->query("SELECT * FROM {PRE}SalesClosing WHERE nSCId = '".$salesCloseID."'");

		if ($invoiceNum->num_rows() > 0)

		{

			foreach ($invoiceNum->result() as $key=>$row)

			{

				$invoiceNumRow['RefInv']  = $refundInvoice;

				$invoiceNumRow['invoice'] = $row->vInvoiceNumber;

                $invoiceNumRow['invDate'] = $row->dDate;

				$invoiceNumRow['salesID'] = $row->nSCId;

			}//end for each

		}//end if

		return ($invoiceNumRow);

	}//end function



	//check exist in allocation table

	function getAllocChecked($did,$sid,$rid,$allType)

	{

		$chkSql = $this->db->query("SELECT nSalesCloseDetId FROM {PRE}CashAllocation WHERE nSalesCloseDetId = '".$did."' AND nSalesCloseId = '".$sid."'

									AND nReceiptDId = '".$rid."'  AND vAllType = '".$allType."'");

		if($chkSql->num_rows() > 0)

			$val = ' checked="checked"';

		else

			$val = '';



		return ($val);

	}//end funciton



	function updateMultipleAllocation()

	{

        $allDate     	 = $this->input->post('dDate');

        $allTkt      	 = $this->input->post('vTktNumber');

        $allDebitAmt     = $this->input->post('nDebit');

        $allAmt     	 = $this->input->post('nAllocated');

        $allBalAmt  	 = $this->input->post('nBalance');

        $allLoc       	 = $this->input->post('nLocation');

		$allType       	 = $this->input->post('vAllType');

		$recId			 = $this->input->post('nRecId');

		$chkId           = $this->input->post('aid');

		$chksalesId      = $this->input->post('salesid');

		$tableId         = $this->input->post('nTableId');



		//insert into cash receipt details

		foreach($chkId as $key => $aitem)

		{

			$data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId[$key],

                                'nSalesCloseDetId' => $aitem,'vAllType' => $allType[$key],'dDate' => $allDate[$key],

							    'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],

							    'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key],'nReceiptDId' => $recId[$key]);

			$this->db->where('nCAId',$tableId[$key]);

			$this->db->update('{PRE}CashAllocation', $data);

		}//end foreach

	}//end function



    function updateAllocationTable()

	{

        $data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'vStatus' => '1');

        $this->db->where('vStatus','0');

        $this->db->update('{PRE}CashAllocation', $data);



        switch($this->input->post('txtAllTypeVal'))

        {

            case "CR":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                                'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                                'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nCRId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}CashReceipt', $data);

            break;



            case "CP":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nCPId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}CashPayment', $data);

            break;

            case "BR":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nBBId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}BankBook', $data);

            break;



            case "BP":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nBPId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}BankBookPayment', $data);

            break;

            case "CV":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nCVId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}CreditVoucher', $data);

            break;



            case "DV":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nDVId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}DebitVoucher', $data);

            break;



            case "JV":

                $data = array('nTotalDebit' => $this->input->post('txtTotalDebitAmt'),

                    'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),

                    'nTotalBalance' => $this->input->post('txtTotalBalAmt'));

                $this->db->where('nJVId',$this->input->post('txtRecIdVal'));

                $this->db->update('{PRE}Jv', $data);

            break;

        }

	}//end function



	function insertMultipleAllocation()

	{

        $allDate     	 = $this->input->post('dDate');

        $allTkt      	 = $this->input->post('vTktNumber');

        $allDebitAmt     = $this->input->post('nDebit');

        $allAmt     	 = $this->input->post('nAllocated');

        $allBalAmt  	 = $this->input->post('nBalance');

        $allLoc       	 = $this->input->post('nLocation');

		$allType       	 = $this->input->post('vAllType');

		$recId			 = $this->input->post('nRecId');

		$chkId           = $this->input->post('aid');



		//insert into cash receipt details

		foreach($chkId as $key => $aitem)

		{

			$chksalesId = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$aitem."'");

			$data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId,

                                'nSalesCloseDetId' => $aitem,'vAllType' => $allType[$key],'dDate' => $allDate[$key],

                                'nDrAccount' => $this->session->userdata('AccVal'),'vTable' => $this->session->userdata('TableVal'),

							    'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],

							    'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key],'nReceiptDId' => $recId[$key]);

			$this->db->insert('{PRE}CashAllocation', $data);

        }//end foreach

	}//end function



    function filterAllocation($ddlAccount,$ddlSubAccount)

    {

        $adminGroupRow    = array();



        if($ddlAccount == 52):

            $adminGroupList   = $this->db->query("SELECT sc.nSCId,sc.dDate,sc.vLocation,scd.nId,sjd.vNumber as vTktNumber,sjd.vType,

                                                CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END)) ELSE sjd.nCredit END AS allocAmt ,

                                                CASE WHEN sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                    FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                    LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                                    LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)

                                                    LEFT JOIN {PRE}TicketFareDetails tfd ON sjd.vNumber = tfd.nTicketId

                                                LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                    WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '14' AND tfd.nAcc='".$ddlSubAccount."'

                                                    AND sjd.nCredit != 0

                                                GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                    {PRE}CashAllocation)) ORDER BY sc.dDate DESC");

        elseif($ddlAccount == 54):

            $adminGroupList   = $this->db->query("SELECT sc.nSCId,sc.dDate,sc.vLocation,scd.nId,sjd.vNumber as vTktNumber,sjd.vType,

                                                CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END)) ELSE sjd.nCredit END AS allocAmt ,

                                                CASE WHEN sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                    FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                    LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                                    LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)

                                                    LEFT JOIN {PRE}HotelVoucher hv ON sjd.vNumber = hv.nHVId

                                                LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                    WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '14' AND hv.nAcCode='".$ddlSubAccount."'

                                                    AND sjd.nCredit != 0

                                                GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                    {PRE}CashAllocation)) ORDER BY sc.dDate DESC");

        else:

            $adminGroupList   = $this->db->query("SELECT sc.nSCId,sc.dDate,sc.vLocation,scd.nId,sjd.vNumber as vTktNumber,sjd.vType,

                                                CASE WHEN ca.vBalAmt > 0 THEN (sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END)) ELSE sjd.nCredit END AS allocAmt ,

                                                CASE WHEN sjd.nCredit - (CASE WHEN

                                                (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                        vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                    ELSE

                                                        (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                                AND nSalesCloseDetId = scd.nId

                                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                     vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                    FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                    LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                                    LEFT JOIN {PRE}SystemJvDetails sjd ON (sjd.vType=scd.vType AND sjd.vNumber=scd.vTktNumber)

                                                    LEFT JOIN {PRE}XoBooking xo ON sjd.vNumber = xo.nXId

                                                LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                    WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '14' AND xo.nPassengerAC='".$ddlSubAccount."'

                                                    AND sjd.nCredit != 0

                                                GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                    {PRE}CashAllocation)) ORDER BY sc.dDate DESC");

        endif;



        if ($adminGroupList->num_rows() > 0)

        {

            foreach ($adminGroupList->result() as $key=>$row)

            {

                $adminGroupRow[] = $row;

            }//end for each

        }//end if

        return ($adminGroupRow);

    }//end function



    function filterAllocation2($ddlAccount,$ddlSubAccount)

    {

        $adminGroupRow    = array();



        $adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.vAllTktNumber     ,

                                            CASE WHEN ca.vBalAmt > 0 THEN (scd.vCollected - (CASE WHEN

                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                        AND nSalesCloseDetId = scd.nId

                                                AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                    vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                ELSE

                                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                 vTable = '".$this->input->post('tableVal')."') END)) ELSE scd.vCollected END AS allocAmt ,

                                            CASE WHEN scd.vCollected - (CASE WHEN

                                            (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                        AND nSalesCloseDetId = scd.nId

                                                AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                    vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                                ELSE

                                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                                            AND nSalesCloseDetId = scd.nId

                                                    AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                                                 vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                            LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                WHERE sad.nDrAccount = '".$ddlAccount."' AND sad.nAcHead = '".$ddlSubAccount."'

                                            GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                {PRE}CashAllocation)) ORDER BY sc.dDate DESC");

        if ($adminGroupList->num_rows() > 0)

        {

            foreach ($adminGroupList->result() as $key=>$row)

            {

                $adminGroupRow[] = $row;

            }//end for each

        }//end if

        return ($adminGroupRow);

    }//end function



    function deleteAllocation($cId,$rectd,$tktType){

      //change the allocated amount first

      switch($tktType){



        case 'CR':

          $tblId = 'CRId';

          $tableName = 'CashReceipt';

          break;



        case 'CP':

          $tblId = 'CPId';

          $tableName = 'CashPayment';

          break;





        case 'BR':

          $tblId = 'BRId';

          $tableName = 'BankBook';

          break;





        case 'BP':

          $tblId = 'BPId';

          $tableName = 'BankBookPayment';

          break;





        case 'CV':

          $tblId = 'CVId';

          $tableName = 'CreditVoucher';

          break;





        case 'DV':

          $tblId = 'DVId';

          $tableName = 'DebitVoucher';

          break;





        case 'JV':

          $tblId = 'JVId';

          $tableName = 'Jv';

          break;

      }



      $cashAlloc = $this->common_model->get_field_name('CashAllocation','vAllAmt'," WHERE nCAId = " . $cId );

      $receiptAlloc = $this->common_model->get_field_name($tableName,'nTotalAllocated'," WHERE " . $tblId . " = " . $rectd );

return;

      $tableName = '{PRE}' . $tableName;

      $totAlloc = $receiptAlloc - $cashAlloc;

      $data       = array('nTotalAllocated' => $totAlloc );

      $this->db->where($tblId,$rectd);

      $this->db->update($tableName, $data);



      $this->db->delete('{PRE}CashAllocation',array('nCAId' => $cId));

 //redirect(site_url("ar_allocation/showlist/".$this->uri->segment(3)));

    } //end function



}//end class

?>

