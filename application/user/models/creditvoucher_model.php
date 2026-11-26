<?php
class Creditvoucher_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		date_default_timezone_set('Asia/Kuwait');
    }

    /*----------------------Bank Book Entry Details-----------------*/

	function getAllCreditVoucher()
	{
	    //active groups
		$adminGroupRow  		= array();
		$sqlCreditvoucherQuery  = "SELECT cv.*,d.vTitle FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}Division d
												  ON cv.nDivision = d.nId 
                          WHERE cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                          ORDER BY nCVId DESC
											 LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlCreditvoucherQuery;
		$adminGroupList 		= $this->db->query($sqlCreditvoucherQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
                $adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountCreditVoucher()
	{
		$tabCount = $this->db->query("SELECT * FROM {PRE}CreditVoucher 
        WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nCVId DESC");
		return ($tabCount->num_rows());
	}//end function

	function getCorresCreditVoucher()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}CreditVoucher WHERE nCVId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $acAccRow = array();
        $acAcc    = $this->db->query("SELECT nLoc FROM {PRE}CreditVoucherDetails WHERE nCreditId = '".$nId."'");
        if ($acAcc->num_rows() > 0)
        {
            foreach ($acAcc->result() as $key=>$row)
            {
                $acAccRow[] = $row->nLoc;
            }//end for each
        }//end if
        return (array_filter($acAccRow));
    }//end function

	function getCorresCreditVoucherDetails()
	{
        $creditDetailsLoc       	= array();
		$creditDetailsCode      	= array();
		$creditDetailsCodeName     	= array();
        $cashDetailsTable      	    = array();
		$creditDetailsSubCode       = array();
		$creditDetailsMode      	= array();
		$creditDetailsCurr       	= array();
		$creditDetailsRefNumber 	= array();
		$creditDetailsAmountFC  	= array();
		$creditDetailsExchangeRate  = array();
		$creditDetailsAmount        = array();
		$creditDetailsTitle     	= array();
		$creditDetailsDes     	    = array();
        $creditReceiptDetailID      = array();
		$newValArray          	    = array();

		$creditDetailsList      	= $this->db->query("SELECT * FROM {PRE}CreditVoucherDetails WHERE nCreditId ='".$this->catId."' AND nLoc IN ".$this->session->userdata('accessLoc') ."  ");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $creditDetailsLoc[]           = $row->nLoc;
                $cashDetailsTable[]      	  = $row->vTable;
                $creditDetailsCode[]      	  = $row->vACode.'-'.$row->vTable;
                $creditDetailsCodeName[]   	  = $row->vACode;
                $creditDetailsSubCode[]       = $row->vAcSubCode;
                $creditDetailsMode[]      	  = $row->vMode;
                $creditDetailsCurr[]   	      = $row->nCurrency;
                $creditDetailsRefNumber[] 	  = $row->vAcRefNumber;
                $creditDetailsAmountFC[] 	  = $row->vFCAmount;
                $creditDetailsExchangeRate[]  = $row->vExchange;
                $creditDetailsAmount[]  	  = $row->nAmount;
                $creditDetailsTitle[]     	  = $row->vTitle;
				$creditDetailsDes[]     	  = $row->vDes;
                $creditReceiptDetailID[]      = $row->nId;
            }//end for each
        }//end if

        $newValArray['acLoc']    	   		= $creditDetailsLoc;
        $newValArray['acTable']    	   		= $cashDetailsTable;
        $newValArray['acCode']    	   		= $creditDetailsCode;
        $newValArray['acCodeName']    		= $creditDetailsCodeName;
        $newValArray['acSubCode']    	   	= $creditDetailsSubCode;
        $newValArray['acMode']    	   		= $creditDetailsMode;
        $newValArray['acCurr']      		= $creditDetailsCurr;
        $newValArray['acRefNumber']    		= $creditDetailsRefNumber;
        $newValArray['acAmountFC']     		= $creditDetailsAmountFC;
        $newValArray['acExchangeRate'] 	    = $creditDetailsExchangeRate;
        $newValArray['acAmount'] 	        = $creditDetailsAmount;
        $newValArray['acTitle']        		= $creditDetailsTitle;
		$newValArray['acDes']        		= $creditDetailsDes;
        $newValArray['acDetailID']          = $creditReceiptDetailID;
        return ($newValArray);
	}//end function

	function insertCreditVoucher()
	{
    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }
    if($this->input->post('txtRefDate') == '') { $refDate = ''; }
    else { $refDate =  date('Y-m-d', strtotime($this->input->post('txtRefDate'))); }

    if($this->input->post('txtCheqDate') == '') { $cheqDate= ''; }
    else { $cheqDate =  date('Y-m-d', strtotime($this->input->post('txtCheqDate'))); }


    if($this->input->post('txtInvDate') == '') { $txtInvDate= ''; }
    else { $txtInvDate=  date('Y-m-d', strtotime($this->input->post('txtInvDate'))); }


		$data = array('nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
					  'dDate' => $date1,'nTransaction' => $this->input->post('ddlTransaction'),
					  'vDocNumber' => $this->input->post('txtDocNumber'), 'nUserId' => $this->session->userdata('AbsAdmin'),
					  'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
					  'vChequeNumber' => $this->input->post('txtCheqNumber'),'dChequeDate' => $cheqDate,
					  'dRefDate' => $refDate ,'vRefNumber' => $this->input->post('txtRefNumberMain'),
					  'vEnteredBy' => $this->input->post('txtEntered'),'vRefName' => $this->input->post('txtRefName'),
					  'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
					  'dInvoiceDate' => $txtInvDate, 'nTotalDebit' => $this->input->post('txtTotalDebitAmt'),
                      'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),'nTotalBalance' => $this->input->post('txtTotalBalAmt'),
                      'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
		$this->db->insert('{PRE}CreditVoucher', $data);

		$id			  	 = $this->db->insert_id();
        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accRefNumber 	 = (@array_filter($this->input->post('txtRefNumber')) != '')?$this->input->post('txtRefNumber'):0;
        $curr       	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
        $accExchangeRate = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $accAmountFC  	 = (@array_filter($this->input->post('txtAmountFC')) != '')?$this->input->post('txtAmountFC'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;

	    //insert into credit voucher details
		foreach($accCode as $key => $aitem)
		{
            $accName = @split("-",$accCode[$key]);
			$data 	  = array('nCreditId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' => $curr[$key],'nLoc' => $locVal[$key],
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $accExchangeRate[$key],'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
			$this->db->insert('{PRE}CreditVoucherDetails', $data);
		}//end foreach
        $data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nReceiptDId' => $id,'vStatus' =>'1');
        $this->db->where('vAllType','CV');
        $this->db->where('nReceiptDId','0');
        $this->db->update('{PRE}CashAllocation', $data);
		$msgVal = 'add_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;
            default:
                $showId = '';
                break;
        }//end switch

		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("credit_voucher/editCreditVoucher/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal.$showId));
		}//end if

		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("credit_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
	}//end class

	function updateCreditVoucher()
	{

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }
    if($this->input->post('txtRefDate') == '') { $refDate = ''; }
    else { $refDate =  date('Y-m-d', strtotime($this->input->post('txtRefDate'))); }

    if($this->input->post('txtCheqDate') == '') { $cheqDate= ''; }
    else { $cheqDate =  date('Y-m-d', strtotime($this->input->post('txtCheqDate'))); }


    if($this->input->post('txtInvDate') == '') { $txtInvDate= ''; }
    else { $txtInvDate=  date('Y-m-d', strtotime($this->input->post('txtInvDate'))); }

		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
                      'vLocation' => $this->input->post('ddlLocation'),
                      'dDate' => $date1,'nTransaction' => $this->input->post('ddlTransaction'),
                      'vDocNumber' => $this->input->post('txtDocNumber'),
                      'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
                      'vChequeNumber' => $this->input->post('txtCheqNumber'),'dChequeDate' => $cheqDate, 
                      'dRefDate' => $refDate,'vRefNumber' => $this->input->post('txtRefNumberMain'),
                      'vEnteredBy' => $this->input->post('txtEntered'),'vRefName' => $this->input->post('txtRefName'),
                      'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
					  'dInvoiceDate' => $txtInvDate ,'nTotalDebit' => $this->input->post('txtTotalDebitAmt'),
                      'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),'nTotalBalance' => $this->input->post('txtTotalBalAmt'),
                      'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
		$this->db->where('nCVId',$this->input->post('nId'));
		$this->db->update('{PRE}CreditVoucher', $data);

		$id			     = $this->input->post('nId');
        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accRefNumber 	 = (@array_filter($this->input->post('txtRefNumber')) != '')?$this->input->post('txtRefNumber'):0;
        $curr       	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
        $accExchangeRate = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $accAmountFC  	 = (@array_filter($this->input->post('txtAmountFC')) != '')?$this->input->post('txtAmountFC'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
		$chkAccID	     = $this->input->post('idArray');

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
			//checking already exists if yes updated
			if(intval($chkAccID[$key]) != 0)
			{
                $accName = @split("-",$accCode[$key]);
                $data 	  = array('nCreditId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                    'vMode' => $modeName[$key],'nCurrency' => $curr[$key],'nLoc' => $locVal[$key],
                                    'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                    'vExchange' => $accExchangeRate[$key],'nAmount' => $accAmount[$key],
                                    'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}CreditVoucherDetails', $data);
			}//end if
			else
			{
			    //if new insert into db
                $accName = @split("-",$accCode[$key]);
                $data 	  = array('nCreditId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                    'vMode' => $modeName[$key],'nCurrency' => $curr[$key],'nLoc' => $locVal[$key],
                                    'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                    'vExchange' => $accExchangeRate[$key],'nAmount' => $accAmount[$key],
                                    'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->insert('{PRE}CreditVoucherDetails', $data);
			}//end else
		}//end foreach

        $data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nReceiptDId' => $id,'vStatus' =>'1');
        $this->db->where('vAllType','CV');
        $this->db->where('nReceiptDId','0');
        $this->db->update('{PRE}CashAllocation', $data);
		$msgVal = 'edit_suc';
        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;
            default:
                $showId = '';
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
		    redirect(site_url("credit_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
	}//end function

	function deleteCreditVoucher()
	{
		$this->db->delete('{PRE}CreditVoucherDetails', array('nCreditId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}CreditVoucher', array('nCVId' => $this->uri->segment(4)));
		redirect(site_url("credit_voucher/showlist/".$this->uri->segment(3).'/del_suc'));
	}//end function

	function deleteAllCreditVoucher()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nCVId',$val);
					$this->db->update('{PRE}CreditVoucher', $data);

					$this->db->where('nCreditId',$val);
					$this->db->update('{PRE}CreditVoucherDetails', $data);
				}//end foreach
				redirect(site_url("credit_voucher/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;

			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nCVId',$val);
					$this->db->update('{PRE}CreditVoucher', $data);

					$this->db->where('nCreditId',$val);
					$this->db->update('{PRE}CreditVoucherDetails', $data);
				}//end foreach
				redirect(site_url("credit_voucher/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}CreditVoucherDetails', array('nCreditId' => $val));
					$this->db->delete('{PRE}CreditVoucher', array('nCVId' => $val));
				}//end foreach
				redirect(site_url("credit_voucher/showlist/".$this->input->post('nCId').'/del_suc'));
			break;
		}//end switch
	}//end function

 	//get account head accounts details
	function getAcAccounts($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vACode;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function

 	//get sub group details
	function getAcAccountsTitle($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vTitle;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function

	//get id
	function getAcAccountsID($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function

	//search finance
	function searchActiveFinance($searchKey)
	{
		$typeGroupRow = array();
		$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode
												 LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle
												 	LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) AND ahc.vSubSale = 'B'";

		if(trim($searchKey) == 0)
		{
			$extaCondition = '';
		}//end if

		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
											   LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId
											   		LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' ".$extaCondition." ORDER BY aca.nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if

		//for json encode
		$result = array();
		foreach ($typeGroupRow as $key=>$value)
		{
			array_push($result, array("name" => $value));
		}//end fuction
		return ($result);
	}//end function

	function getActiveDivision()
	{
		$divGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1'  AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
		$divGroupRow = array();

		if ($divGroup->num_rows() > 0)
		{
			//$divGroupRow[0] = SELECT;
			foreach ($divGroup->result() as $key=>$row)
			{
				$divGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$divGroupRow[0] = NOVALUE;
		}//end else
		return ($divGroupRow);
	}//end function

	function getActiveLocations()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function

	function getActiveTransactions()
	{
        $transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nId = '13'");
		$transGroupRow = array();

		if ($transGroup->num_rows() > 0)
		{
			//$transGroupRow[0] = SELECT;
			foreach ($transGroup->result() as $key=>$row)
			{
				$transGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$transGroupRow[0] = NOVALUE;
		}//end else
		return ($transGroupRow);
	}//end function

	function getActiveCurrency()
	{
		$currGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' ORDER BY vTitle ASC");
		$currGroupRow = array();

		if ($currGroup->num_rows() > 0)
		{
			$currGroupRow[0] = SELECT;
			foreach ($currGroup->result() as $key=>$row)
			{
				$currGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$currGroupRow[0] = NOVALUE;
		}//end else
		return ($currGroupRow);
	}//end function

	function filterLocation($division)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' AND vActive='1'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		if ($adminGroupList->num_rows() > 0)
		{
			//$adminGroupRow[0] = SELECT;
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[$row->nId] = $row->vTitle;
			}//end for each
		} //end if
		else
		{
			$adminGroupRow[0] = NOVALUE;
		}//end funtion
		return ($adminGroupRow);
	}//end function

	function getCorrActiveLocation($division)
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."' ORDER BY nPosition DESC");
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

    function getAccountMultipleValues()
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
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
										   		ORDER BY vTitle ASC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
        return ($resultArray);
    }//end function

    function getSubAccountMultipleValues()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND ahc.vSubLedger = 'S' ORDER BY nId DESC");
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

    function getMultipleLocation()
    {
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId IN ".$this->session->userdata('accessLoc') ." 
                                  ORDER BY nPosition DESC");
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

	function getActiveMode()
	{
        $modeGroupRow = array("Cr" => "Credit", "Dr" => "Debit");
		return ($modeGroupRow);
	}//end function

	function getActiveAirlines()
	{
		$airlineGroup    = $this->db->query("SELECT nArId,vTitle,vAirlineNo FROM {PRE}Airline WHERE vActive = '1' ORDER BY nArId DESC");
		$airlineGroupRow = array();

		if ($airlineGroup->num_rows() > 0)
		{
			$airlineGroupRow[0] = SELECT;
			foreach ($airlineGroup->result() as $key=>$row)
			{
				$airlineGroupRow[$row->nArId] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';
			}//end for each
		}//end if
		else
		{
			$airlineGroupRow[0] = NOVALUE;
		}//end else
		return ($airlineGroupRow);
	}//end function

	//fetching single details
	function showDetails($creditid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT cv.nCVId,cvd.*,cv.*,cv.nDivision as divId,t.vTitle as transName,c.vTitle as currName,aha.*,
											 aha.vACode as accCode,aha.vTitle as accName,
											  CASE
												WHEN cvd.vMode = 'Cr' THEN 'Credit'
											  ELSE 'Debit' END as mode
											   FROM {PRE}CreditVoucherDetails
												cvd LEFT JOIN {PRE}CreditVoucher cv
											 ON cv.nCVId = cvd.nCreditId LEFT JOIN {PRE}Transactions t ON t.nId = cv.nTransaction
											 LEFT JOIN {PRE}Currency c ON c.nId = cv.nCurrency
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON cvd.vACode = aha.nId
											 WHERE cv.nCVId='".$creditid."' ORDER BY cvd.nId DESC");
		//single fetch
		$commVal    = $viewAccessList->row();
		$divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$commVal->divId."'");
		$locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$commVal->vLocation."'");

        $dDate      = ($commVal->dDate != '0000-00-00')?date('d-m-Y',strtotime($commVal->dDate)):'NIL';
        $dRefDate   = ($commVal->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($commVal->dRefDate)):'NIL';
        $dInvDate   = ($commVal->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($commVal->dInvoiceDate)):'NIL';
        $chequeDate = ($commVal->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($commVal->dChequeDate)):'NIL';
		$transMode  = ($commVal->vTransMode == 'Cr')?'Credit':'Debit';

		$content = '<table class="reportForm">
						<tr>
							<td><strong>'.DIVISIONNAME.' :</strong></td>
							<td>'.$divName.'</td>
							<td><strong>'.LOCATIONNAME.' :</strong></td>
							<td>'.$locName.'</td>
						    <td><strong>'.CUSTDATE.' :</strong></td>
						    <td>'.$dDate.'</td>
							<td><strong>'.TRANSNAME.' :</strong></td>
							<td>'.$commVal->transName.'</td>
							<td><strong>'.DOCNUMBER.' :</strong></td>
							<td>'.$commVal->vDocNumber .'</td>
						</tr>

						<tr>
							<td><strong>'.CURRNAME.' :</strong></td>
							<td>'.$commVal->currName.'</td>
						    <td><strong>'.RATE.' :</strong></td>
						    <td>'.$commVal->vRate.'</td>
						    <td><strong>'.CHEQUEDATE.':</strong></td>
						    <td>'.$chequeDate.'</td>
						    <td><strong>'.CHEQUENUMBER.':</strong></td>
						    <td>'.$commVal->vChequeNumber.'</td>
							<td><strong>'.REFNUMBER.' :</strong></td>
							<td>'.$commVal->vRefNumber.'</td>
						</tr>

						<tr>
						    <td><strong>'.REFDATE.' :</strong></td>
						    <td>'.$dRefDate.'</td>
							<td><strong>'.REFNAME.' :</strong></td>
							<td>'.$commVal->vRefName.'</td>
							<td><strong>'.ENTEREDBY.' :</strong></td>
							<td>'.$commVal->vEnteredBy.'</td>
							<td><strong>'.REMARKS.' :</strong></td>
							<td>'.$commVal->vRemarks.'</td>
						    <td><strong>'.INVOICE.' :</strong></td>
						    <td>'.$commVal->vInvoice.'</td>
						</tr>

						<tr>
						    <td><strong>'.INVOICEDATE.' :</strong></td>
						    <td>'.$dInvDate.'</td>
						    <td colspan="8">&nbsp;</td>
						</tr>

						<tr>
						    <td><strong>'.LOCATIONNAME.'</strong></td>
							<td><strong>'.ACCNAME.'</strong></td>
							<td><strong>'.SUBCODE.'</strong></td>
							<td><strong>'.BUDGETMODE.'</strong></td>
							<td><strong>'.REFNUMBER.'</strong></td>
							<td><strong>'.CURRNAME.'</strong></td>
							<td><strong>'.EXCHANGERATE.'</strong></td>
							<td><strong>'.AMOUNTDC.'</strong></td>
							<td><strong>'.AMOUNTFC.'</strong></td>
							<td><strong>'.TRANDESC.'</strong></td>
						</tr>';

		$viewAccessList1  = $this->db->query("SELECT *,
												CASE
												WHEN vMode = 'Cr' THEN 'Credit'
											  ELSE 'Debit' END as mode FROM {PRE}CreditVoucherDetails WHERE nCreditId = '".$creditid."'
											  ORDER BY nId DESC");

		foreach ($viewAccessList1->result() as $key=>$row)
		{
			$subledgerQuery   = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												  WHERE aca.nId='".$row->vACode."' ORDER BY ahc.nId DESC");
			$subledgerResult    = $subledgerQuery->row();
			$this->txtsubLedger	= $subledgerResult->vSubLedgerType;

			switch($this->txtsubLedger)
			{
				case "C":
					$subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row->vAcSubCode."'");
				break;

				case "V":
					$subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->vAcSubCode."'");
				break;

				case "A":
					$subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row->vAcSubCode."'");
				break;

				case "O":
					$subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row->vAcSubCode."'");
				break;

				case "E":
					$subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row->vAcSubCode."'");
				break;

				case "N":
					$subAcc = '';
				break;
			}//end switch

			$content .= '<tr>
			    <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->nLoc."'").'</td>
				<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row->vACode."'").'</td>
				<td>'.$subAcc.'</td>
				<td>'.$row->mode.'</td>
				<td>'.$row->vAcRefNumber.'</td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>
				<td>'.$row->vExchange.'</td>
				<td>'.$row->nAmount.'</td>
				<td>'.$row->vFCAmount.'</td>
				<td>'.$row->vDes.'</td>
			</tr>';
		}//end foreach

		$content .= '</table>';
	    return($content);
	}//end function

 	//get location name
	function getLocationName($locArray)
	{
		$locName         = array();
		//fetch location name
		foreach($locArray as $val)
        {
			$locName[]   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");
		}//end foreach

		return(@join(",",$locName));
	}//end function

 	function getSubledgerNo($financeId)
	{
		$fetchTitle 	  = $this->db->query("SELECT ahc.*,cv.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}CreditVoucher cv
												ON cv.nAcCode = ahc.nId WHERE ahc.nActHId = '".$financeId."'");
		$showVal	      = $fetchTitle->row();
		$chkCode		  = $this->getAutomaticCode('CreditVoucher',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nCVId');
		return $chkCode;
	}//end function

	function getAutomaticCode($tableName,$code,$auto,$id)
	{
		if($auto == 1)
		{
			$fetchCode = $this->db->query("SELECT count($id) as totCount FROM {PRE}$tableName");
			$totalNum  = $fetchCode->row();
			switch($totalNum->totCount)
			{
				case "0" :
					$countVal = $code;
				break;

				default:
					$countVal = ($totalNum->totCount)+1;
					$countVal = substr($code,0,strlen($code)-1).$countVal;
				break;
			}//end switch
			return($countVal);
		}//end if
		else
		{
			$this->txtCode = '';
		}//end else
	}//end function

	//function to calcualte online rate
	function onlineCurrencyConverstion()
	{
        $valArray = array();

		//make string to be put in API
		$string = "1".$this->selCurrency."=?".$this->defCurrency;
		//Call Google API
		$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;
		//Get and Store API results into a variable
		$result = file_get_contents($google_url);
		//Explode result to convert into an array
		$result = explode('"', $result);
		################################
		# Right Hand Side
		################################
		$converted_amount = explode(' ', $result[3]);
		$conversion = $converted_amount[0];
		//$conversion = preg_replace('/[x00-x08x0B-x1F]/', '', $conversion);
		$conversion = $conversion * $this->fcAmount;
		$conversion = round($conversion, 2);
		//echo $conversion = number_format($conversion, 2, '.', "");
		//Get text for converted currency
		//$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));

		$varArray['exRate'] = round($converted_amount[0],2);
		$varArray['covAmt'] = $conversion;

		//Make right hand side string
		//$rhs = $conversion.$rhs_text;
		return ($varArray);
	}//end function

    function getRate($cur)
    {
        $fetchTitle 	  = $this->db->query("SELECT vRate FROM {PRE}Currency WHERE nId = '".$cur."'");
        $showVal	      = $fetchTitle->row();
        $chkRate		  = $showVal->vRate;
        return $chkRate;
    }//end function

	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}CreditVoucherDetails', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT cv.*,d.*,cv.nCVId as cvId,d.nId as divId,d.vTitle as divName
											FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}Division d ON cv.nDivision = d.nId
											 ".$extraCheck." 
                       AND cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                       ORDER BY cv.nCVId DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT cv.*,d.*,cv.nCVId as cvId,d.nId as divId,d.vTitle as divName
											FROM {PRE}CreditVoucher cv LEFT JOIN {PRE}Division d ON cv.nDivision = d.nId
											 ".$extraCheck." 
                       AND cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                       ORDER BY cv.nCVId DESC");
		return ($tabCount->num_rows());
	}//end function

    function filterSubAccount($accGroup,$table)
    {
        if($accGroup>0):
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
														LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nActHId
													 WHERE ah.vActive = '1' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
// AND ahc.vSubLedgerType = 'A' 
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
        else:
            $accheadGroupRow    = array();
            $accheadGroupRow[0] = NOVALUE;
        endif;
        return ($accheadGroupRow);
    }//end function

	function getSubAccountValues()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return($typeGroupRow);
	}//end function

    function filterAllocation($ddlAccount,$ddlSubAccount)
    {
        $adminGroupRow    = array();
		if($this->uri->segment(4) == 'a')
		{
            if($ddlAccount == 32):
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
                                                        WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '18' AND tfd.nAcc='".$ddlSubAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
            elseif($ddlAccount == 34):
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
                                                        WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '18' AND hv.nAcCode='".$ddlSubAccount."'
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
                                                        WHERE sjd.nAccHeadId = '".$ddlAccount."' AND sjd.nAGId = '18' AND xo.nPassengerAC='".$ddlSubAccount."'
                                                        AND sjd.nCredit != 0
                                                    GROUP BY sjd.nSjDId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
            endif;
		}//end if
		else
		{
			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId AND ca.nDrAccount = '".$this->input->post('ddlAccount2')."' AND 
														 ca.vTable = '".$this->input->post('tableVal')."')
													WHERE ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = 'CV'
												  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");
		}//end else

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
        if($this->uri->segment(4) == 'a')
        {
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
        }//end if
        else
        {
            $adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
													WHERE ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = 'CV'
													 AND sad.nDrAccount = '".$ddlAccount."'
												  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");
        }//end else
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
    }//end function

    function insertMultipleAllocation()
    {
        $allDate     	 = $this->input->post('dDate');
        $allTkt      	 = $this->input->post('vTktNumber');
        $allDebitAmt     = $this->input->post('nDebit');
        $allAmt     	 = $this->input->post('nAllocated');
        $allBalAmt  	 = $this->input->post('nBalance');
        $allLoc       	 = $this->input->post('nLocation');
        $chkId           = $this->input->post('aid');
        //insert into cash receipt details

        foreach($chkId as $key => $aitem)
        {
            $chksalesId = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$aitem."'");
            $data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId,
                             'nSalesCloseDetId' => $aitem,'vAllType' => 'CV','dDate' => $allDate[$key],
                             'nDrAccount' => $this->session->userdata('AccVal'),'vTable' => $this->session->userdata('TableVal'),
                             'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],
                             'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key]);
            $this->db->insert('{PRE}CashAllocation', $data);
        }//end foreach
    }//end class

    function getInvoiceDetails($salesDetId)
    {
        $salesCloseID           = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$salesDetId."'");
        $invoiceNum             = $this->db->query("SELECT * FROM {PRE}SalesClosing WHERE nSCId = '".$salesCloseID."'");
        if ($invoiceNum->num_rows() > 0)
        {
            foreach ($invoiceNum->result() as $key=>$row)
            {
                $invoiceNumRow['invoice'] = $row->vInvoiceNumber;
                $invoiceNumRow['invDate'] = $row->dDate;
                $invoiceNumRow['salesID'] = $row->nSCId;
            }//end for each
        }//end if
        return ($invoiceNumRow);
    }//end function
	//check exist in allocation table
	function getAllocChecked($did,$sid,$rid)
	{
		$chkSql = $this->db->query("SELECT nSalesCloseDetId FROM {PRE}CashAllocation WHERE
									 nSalesCloseDetId = '".$did."' AND nSalesCloseId = '".$sid."'
									AND nReceiptDId = '".$rid."'  AND vAllType = 'CV'");
		if($chkSql->num_rows() > 0)
			$val = ' checked="checked"';
		else
			$val = '';

		return ($val);
	}//end funciton


        function getAccounts($Id)
        {
                $accountListRow = array();
                $accountList    = $this->db->query("SELECT 
                                CASE
                                WHEN  vTable = 'AG' OR  vTable = 'C' OR  vTable = 'V' OR  vTable = 'O' THEN
                                (select vTitle from {PRE}AccountHeadAccounts where nId =  vAcSubCode )
                                ELSE (select ach.vTitle from tbl_AccountHeadAccounts ach where ach.nId = tbl.vACode)
                                END
                                as subAccName,
                                CASE
                                WHEN  vTable = 'AG' THEN (select temp.vName from {PRE}Agent temp where temp.nAId = vACode)
                                WHEN  vTable = 'C' THEN (select temp.vName from {PRE}CustomerMaster temp where temp.nCId = vACode)
                                WHEN  vTable = 'V' THEN (select temp.vName from {PRE}VendorMaster temp where temp.nVId = vACode)
                                WHEN  vTable = 'O' THEN (select temp.vName from {PRE}Other temp where temp.nOId = vACode)
                                ELSE  ''
                                END as  drAccountName,
                                IF( vMode = 'Dr',nAmount,0) as Debit,
                                IF( vMode = 'Cr',nAmount,0) as Credit
                         FROM {PRE}CreditVoucherDetails tbl WHERE nCreditId= ".$Id."");
                if ($accountList->num_rows() > 0)
                {

                        foreach ($accountList->result() as $key=>$row)
                        {
                                $accountListRow[] = $row;
                        }//end for each
                }//end if
                return ($accountListRow);
        }//end function






}//end class

?>
