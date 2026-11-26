<?php
class Bankbook_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*----------------------Bank Book Details-----------------*/
	
	function getAllBankbookEntry()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlCashreceiptQuery    = "SELECT bb.*,d.vTitle FROM {PRE}BankBook bb LEFT JOIN {PRE}Division d
									ON bb.nDivision = d.nId 
                  WHERE bb.vLocation IN ". $this->session->userdata('accessLoc') ." 
                  ORDER BY nBBId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlCashreceiptQuery;	
		$adminGroupList 		= $this->db->query($sqlCashreceiptQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nLoc FROM {PRE}BankBookDetails WHERE nBankbookId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nLoc;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

	function getCountBankbookEntry()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}BankBook 
        WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nBBId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresBankbookEntry()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}BankBook WHERE nBBId = '".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresBankbookEntryDetails()
	{
		$cashDetailsLoc       	  = array();
		$cashDetailsCode      	  = array();
		$cashDetailsTable      	  = array();
		$cashDetailsSubCode       = array();
		$cashDetailsMode      	  = array();
		$cashDetailsCurr     	  = array();
		$cashDetailsRefNumber 	  = array();
		$cashDetailsAmountFC  	  = array();
		$cashDetailsExchangeRate  = array();
		$cashDetailsAmount        = array();
		$cashDetailsTitle     	  = array();
		$cashDetailsDes     	  = array();
		$newValArray          	  = array();
        $cashReceiptDetailID      = array();
		
		$cashDetailsList      	  = $this->db->query("SELECT * FROM {PRE}BankBookDetails WHERE nBankbookId = '".$this->catId."'");
		if ($cashDetailsList->num_rows() > 0)
		{
			foreach ($cashDetailsList->result() as $key=>$row)
			{
                $cashDetailsLoc[]         	  = $row->nLoc;
				$cashDetailsCode[]      	  = $row->vACode.'-'.$row->vTable;
                $cashDetailsTable[]      	  = $row->vTable;
				$cashDetailsSubCode[]      	  = $row->vAcSubCode;
				$cashDetailsMode[]      	  = $row->vMode;
				$cashDetailsCurr[]   	      = $row->nCurrency;
				$cashDetailsRefNumber[] 	  = $row->vAcRefNumber;
				$cashDetailsAmountFC[] 		  = $row->vFCAmount;
				$cashDetailsExchangeRate[]    = $row->vExchange;
				$cashDetailsAmount[]  		  = $row->nAmount; 
				$cashDetailsTitle[]     	  = $row->vTitle;
				$cashDetailsDes[]     	      = $row->vDes;
                $cashReceiptDetailID[]     	  = $row->nId;

			}//end for each
		}//end if
		
		$newValArray['acLoc']    	   		= $cashDetailsLoc;
		$newValArray['acCode']    	   		= $cashDetailsCode;
		$newValArray['acTable']    	   		= $cashDetailsTable;
		$newValArray['acSubCode']    	   	= $cashDetailsSubCode;
		$newValArray['acMode']    	   		= $cashDetailsMode;
		$newValArray['acCurr']      		= $cashDetailsCurr;
		$newValArray['acRefNumber']    		= $cashDetailsRefNumber;
		$newValArray['acAmountFC']     		= $cashDetailsAmountFC;
		$newValArray['acExchangeRate'] 	    = $cashDetailsExchangeRate;
		$newValArray['acAmount'] 	        = $cashDetailsAmount;
		$newValArray['acTitle']        		= $cashDetailsTitle;
		$newValArray['acDes']        		= $cashDetailsDes;
		$newValArray['acDetailID']          = $cashReceiptDetailID;

		return ($newValArray);
	}//end function
	
	function insertBankbookEntry()
	{
    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));
    }
    if($this->input->post('txtRefDate') =='0000-00-00' || $this->input->post('txtRefDate') == '' || $this->input->post('txtRefDate') == 0 ){
      $refDate = '';
    }
    else
    {
      $refDate = date('Y-m-d',strtotime($this->input->post('txtRefDate')));
    }
    if($this->input->post('txtCheqDate') =='0000-00-00' || $this->input->post('txtCheqDate') == '' || $this->input->post('txtCheqDate') == 0 ){
      $cheqDate = '';
    }
    else
    {
      $cheqDate= date('Y-m-d',strtotime($this->input->post('txtCheqDate')));
    }
    if($this->input->post('txtInvDate') =='0000-00-00' || $this->input->post('txtInvDate') == '' || $this->input->post('txtInvDate') == 0 ){
      $invDate= '';
    }
    else
    {
      $invDate= date('Y-m-d',strtotime($this->input->post('txtInvDate')));
    }




    $data 		  = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
        'vLocation' => $this->input->post('ddlLocation'),
        'dDate' => $date1 ,'nTransaction' => $this->input->post('ddlTransaction'),
        'vDocNumber' => $this->input->post('txtDocNumber'),'vTransMode' => $this->input->post('ddlModeMain'),
        'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
        'vRefNumber' => $this->input->post('txtRefNumberMain'),'dRefDate' => $refDate,
        'dChequeDate' => $cheqDate ,'vChequeNumber' => $this->input->post('txtCheqNumber'),
        'nAcCode' => $this->input->post('ddlAccount'),'vSubCode' => $this->input->post('ddlSubAccount'),
        'vAmountDC' => $this->input->post('txtAmountDC'),'vAmountFC' => $this->input->post('txtAmountFCMain'),
        'vRefName' => $this->input->post('txtRefName'),'vEnteredBy' => $this->input->post('txtEntered'),
        'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
        'dInvoiceDate' => $invDate,'nTotalDebit' => $this->input->post('txtTotalDebitAmt'),
        'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),'nTotalBalance' => $this->input->post('txtTotalBalAmt'),
        'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}BankBook', $data); 
		
		$id			  	 = $this->db->insert_id();

		$data 	 = array('nReceiptDId' => $id,'nUserId' => $this->session->userdata('AbsAdmin'),'vStatus' =>'1');
		$this->db->where('vAllType','BR');
		$this->db->where('nReceiptDId','0');
		$this->db->update('{PRE}CashAllocation', $data);
			

//        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accRefNumber 	 = (@array_filter($this->input->post('txtRefNumber')) != '')?$this->input->post('txtRefNumber'):0;
//        $curr       	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
//        $accExchangeRate = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $accAmountFC  	 = (@array_filter($this->input->post('txtAmountFC')) != '')?$this->input->post('txtAmountFC'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

	    //insert into budget details
        foreach($chkBox as $key => $aitem)
		{
      $allAmt= 0;
      $accName = @split("-",$accCode[$key]);
      $allocQuery =  $this->db->query("SELECT SUM(vAllAmt) as allAmt FROM {PRE}CashAllocation  WHERE nReceiptDId = " . $id. " AND vAllType = 'BR'
          AND nDrAccount = " . $accName[0]. " AND vTable = '" . $accName[1]. "' GROUP BY nReceiptDId, nDrAccount, vTable, vAllType");
      $rowValue = $allocQuery->row_array();
      $allAmt = $rowValue['allAmt'];
      if($allAmt == '') $allAmt = 0;

			$data 	  = array('nBankbookId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                              'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
							  'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                              'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                              'vDes' => $accDes[$key],'vTable' => $accName[1],'nAcAllocatedAmt'=>$allAmt);
			$this->db->insert('{PRE}BankBookDetails', $data);
		}//end foreach
			
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
			redirect(site_url("bankbook/edit_bankbook_entry/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("bankbook_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
	}//end class
	
	function updateBankbookEntry()
	{

    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));
    }
    if($this->input->post('txtRefDate') =='0000-00-00' || $this->input->post('txtRefDate') == '' || $this->input->post('txtRefDate') == 0 ){
      $refDate = '';
    }
    else
    {
      $refDate = date('Y-m-d',strtotime($this->input->post('txtRefDate')));
    }
    if($this->input->post('txtCheqDate') =='0000-00-00' || $this->input->post('txtCheqDate') == '' || $this->input->post('txtCheqDate') == 0 ){
      $cheqDate = '';
    }
    else
    {
      $cheqDate= date('Y-m-d',strtotime($this->input->post('txtCheqDate')));
    }
    if($this->input->post('txtInvDate') =='0000-00-00' || $this->input->post('txtInvDate') == '' || $this->input->post('txtInvDate') == 0 ){
      $invDate= '';
    }
    else
    {
      $invDate= date('Y-m-d',strtotime($this->input->post('txtInvDate')));
    }


		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
                      'dDate' => $date1,'nTransaction' => $this->input->post('ddlTransaction'),
                      'vDocNumber' => $this->input->post('txtDocNumber'),'vTransMode' => $this->input->post('ddlModeMain'),
                      'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
                      'vRefNumber' => $this->input->post('txtRefNumberMain'),'dRefDate' => $refDate,
					  'dChequeDate' => $cheqDate,'vChequeNumber' => $this->input->post('txtCheqNumber'),
                      'nAcCode' => $this->input->post('ddlAccount'),'vSubCode' => $this->input->post('ddlSubAccount'),
                      'vAmountDC' => $this->input->post('txtAmountDC'),'vAmountFC' => $this->input->post('txtAmountFCMain'),
                      'vRefName' => $this->input->post('txtRefName'),'vEnteredBy' => $this->input->post('txtEntered'),
                      'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
					  'dInvoiceDate' => $invDate,'nTotalDebit' => $this->input->post('txtTotalDebitAmt'),
                      'nTotalAllocated' => $this->input->post('txtTotalAlloAmt'),'nTotalBalance' => $this->input->post('txtTotalBalAmt'),
                      'vActive' => $this->input->post('chkActive'));
		$this->db->where('nBBId',$this->input->post('nId'));
		$this->db->update('{PRE}BankBook', $data); 

		$id			     = $this->input->post('nId');
//        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accRefNumber 	 = (@array_filter($this->input->post('txtRefNumber')) != '')?$this->input->post('txtRefNumber'):0;
//        $curr       	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
//        $accExchangeRate = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $accAmountFC  	 = (@array_filter($this->input->post('txtAmountFC')) != '')?$this->input->post('txtAmountFC'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
		$chkAccID	     = $this->input->post('idArray');
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

	    //insert into Account head accounts table
        foreach($chkBox as $key => $aitem)
        {
            $accName = @split("-",$accCode[$key]);
			if(intval($chkAccID[$key]) != 0)
			{
				$data 	= array('nBankbookId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}BankBookDetails', $data); 
			}//end if
			else
			{//if new insert into db
                $data 	= array('nBankbookId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->insert('{PRE}BankBookDetails', $data);
			}//end else
		}//end foreach

        $data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nReceiptDId' => $id,'vStatus' =>'1');
        $this->db->where('vAllType','BR');
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
		    redirect(site_url("bankbook_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
	}//end function
	
	function deleteBankbookEntry()
	{
		$this->db->delete('{PRE}BankBookDetails', array('nBankbookId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}BankBook', array('nBBId' => $this->uri->segment(4)));
    $this->db->delete('{PRE}CashAllocation',array('nReceiptDId' =>  $this->uri->segment(4),'vAllType' => 'BR'));
		redirect(site_url("bankbook_entry/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllBankbookEntry()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nBBId',$val);
					$this->db->update('{PRE}BankBook', $data); 
					
					$this->db->where('nBankbookId',$val);
					$this->db->update('{PRE}BankBookDetails', $data); 
				}//end foreach
				redirect(site_url("bankbook_entry/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nBBId',$val);
					$this->db->update('{PRE}BankBook', $data); 
					
					$this->db->where('nBankbookId',$val);
					$this->db->update('{PRE}BankBookDetails', $data); 
				}//end foreach
				redirect(site_url("bankbook_entry/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}BankBookDetails', array('nBankbookId' => $val));
					$this->db->delete('{PRE}BankBook', array('nBBId' => $val));
				}//end foreach
				redirect(site_url("bankbook_entry/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function

	function getActiveAccount()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
										  WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' ORDER BY vTitle ASC");
		if ($typeGroup->num_rows() > 0)
		{
//			$typeGroupRow[0] = SELECT;
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
											WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' AND vSubLedger = 'S' ORDER BY nId DESC");
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
												WHERE ah.vActive = '1' AND aca.nId
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
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

	function getSubAccountValues()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return($typeGroupRow);
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
                $typeGroupRow[$row->subId] = $row->subName.'-'.$row->subCode;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else
        return ($typeGroupRow);
    }//end function

    function getDebitModeType()
    {
        $modeTyperow = array("Dr" => "Debit");
        return ($modeTyperow);
    }//end function

    function getModeType()
    {
        $modeTyperow = array("Cr" => "Credit", "Dr" => "Debit");
        return ($modeTyperow);
    }//end function

	//search finance
	function searchActiveFinance($searchKey)
	{
		$typeGroupRow = array();
		
		$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode 
								LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle 
						   LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) AND ahc.vSubSale = 'C'";
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
			array_push($result, array(
				"name" => $value
			));
		}//end fuction
		return ($result);
	}//end function 

    function getDocNum()
    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='17'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}BankBook ORDER BY nBBId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
            $countVal 			= $this->txtDocNumber;
        endif;

        return($countVal);
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

	function getActiveLocations()
	{
		$sessionLoc  = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
		$locName     = $sessionLoc->row();
		
		if($sessionLoc->num_rows() == 0)
			$locId = $this->common_model->getLocLogged();
		else
			$locId = $locName->vLocation;
			
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '".$locId."' 
											ORDER BY nPosition DESC");	
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
		return ($locGroupRow);
	}//end function 

	function getLocation()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function 

	function getCorrActiveLocation($division)
	{
		$corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."'                                              ORDER BY nPosition DESC");
		$corrLocGroupRow = array();
		
		if ($corrLocGroup->num_rows() > 0)
		{
			$corrLocGroupRow[0] = SELECT;
			foreach ($corrLocGroup->result() as $key => $row)
			{
			  $corrLocGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
		  $corrLocGroupRow[0] = NOVALUE;
		}//end else
		return ($corrLocGroupRow);
	}//end function

	function getMultipleLocation()
	{
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1'
											 ORDER BY nPosition DESC");
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
	
	function getActiveTransactions()
	{
		$transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nId = '17'");
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
		$currGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' 
											ORDER BY vTitle ASC");
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
        $locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '" . $division . "' 
											 AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) 
		{
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
	
	//fetching single details
	function showDetails($bankid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}BankBook WHERE nBBId = '".$bankid."'");

		//single fetch
        $row        = $viewAccessList->row();
        $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
        $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");

        $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
        $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';
        $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';
        $chequeDate = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';

        switch($row->vTransMode)
        {
            case "Cr" :
                $modeofPay	= 'Credit';
                break;

            case "Ca" :
                $modeofPay	= 'Cash';
                break;

            case "Dr" :
                $modeofPay	= 'Debit';
                break;
        }//end switch

		$content = '<table class="reportForm">
						<tr>
							<td><strong>'.DIVISIONNAME.'</strong></td>
							<td>'.$divName.'</td>
							<td><strong>'.LOCATIONNAME.'</strong></td>
							<td>'.$locName.'</td>
						    <td><strong>'.CUSTDATE.'</strong></td>
						    <td>'.$dDate.'</td>
							<td><strong>'.TRANSNAME.'</strong></td>
							<td>'.$this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'").'</strong></td>
						    <td><strong>'.DOCNUMBER.'</strong></td>
						    <td>'.$row->vDocNumber.'</strong></td>
						</tr>
						<tr>
						    <td><strong>'.TRANSMODE.'</strong></td>
						    <td>'.$modeofPay.'</strong></td>
						    <td><strong>'.CHEQUEDATE.'</strong></td>
						    <td>'.$chequeDate.'</strong></td>
						    <td><strong>'.CHEQUENUMBER.'</strong></td>
						    <td>'.$row->vChequeNumber.'</strong></td>
                            <td><strong>'.ACCCODENAME.'</strong></td>
                            <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'").'</td>
						    <td><strong>'.SUBCODE.'</strong></td>
						    <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'").'</td>
						</tr>
						<tr>
							<td><strong>'.CURRNAME.'</strong></td>
							<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>
							<td><strong>'.RATE.'</strong></td>
							<td>'.$row->vRate.'</strong></td>
						    <td><strong>'.AMOUNTDC.'</strong></td>
						    <td>'.$row->vAmountDC.'</td>
							<td><strong>'.AMOUNTFC.'</strong></td>
							<td>'.$row->vAmountFC.'</td>
						    <td><strong>'.REFNAME.'</strong></td>
						    <td>'.$row->vRefName.'</td>
						</tr>
						<tr>
						    <td><strong>'.REFDATE.'</strong></td>
						    <td>'.$dRefDate.'</td>
                            <td><strong>'.REFNUMBER.'</strong></td>
                            <td>'.$row->vRefNumber.'</td>
						    <td><strong>'.ENTEREDBY.'</strong></td>
						    <td>'.$row->vEnteredBy.'</td>
							<td><strong>'.REMARKS.'</strong></td>
							<td>'.$row->vRemarks.'</td>
						    <td><strong>'.INVOICE.'</strong></td>
						    <td>'.$row->vInvoice.'</td>
						</tr>
						<tr>
						    <td><strong>'.INVOICEDATE.'</strong></td>
						    <td>'.$dInvDate.'</td>
						    <td colspan="8">&nbsp;</td>
						</tr>
						<tr>
						    <td><strong>'.LOCATIONNAME.'</strong></td>
							<td><strong>'.ACCCODENAME.'</strong></td>
							<td><strong>'.SUBCODE.'</strong></td>
							<td><strong>'.BUDGETMODE.'</strong></td>
							<td><strong>'.REFNUMBER.'</strong></td>
							<td><strong>'.CURRNAME.'</strong></td>
							<td><strong>'.EXCHANGERATE.'</strong></td>
							<td><strong>'.AMOUNTDC.'</strong></td>
							<td><strong>'.AMOUNTFC.'</strong></td>
							<td><strong>'.DESCRIPTION.'</strong></td>
						</tr>';
						
		$viewAccessList1  = $this->db->query("SELECT * FROM {PRE}BankBookDetails WHERE nBankbookId = '".$bankid."' ORDER BY nId DESC");
		
		foreach ($viewAccessList1->result() as $key=>$row1)
		{
            switch($row1->vMode)
            {
                case "Cr" :
                    $modeofPay1	= 'Credit';
                    break;

                case "Ca" :
                    $modeofPay1	= 'Cash';
                    break;

                case "Dr" :
                    $modeofPay1	= 'Debit';
                    break;
            }//end switch

			switch($row1->vTable)
			{
				case "C" :
					$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");
					$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");
				break;
	
				case "A" :
				    $accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");
					$subAcc	 = NOVALUE;
				break;
			}//end switch

			$content .= '<tr>
				<td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ").'</td>
				<td>'.$accName.'</td>
				<td>'.$subAcc.'</td>
				<td>'.$modeofPay1.'</td>
				<td>'.$row1->vAcRefNumber.'</td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ").'</td>
				<td>'.$row1->vExchange.'</td>
				<td>'.$row1->nAmount.'</td>
				<td>'.$row1->vFCAmount.'</td>
				<td>'.$row1->vDes.'</td>
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
		$fetchTitle 	  = $this->db->query("SELECT ahc.*,bb.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}BankBook bb ON bb.nAcCode=ahc.nId
												 WHERE ahc.nActHId='".$financeId."'");
		$showVal	      = $fetchTitle->row();
		$chkCode		  = $this->getAutomaticCode('CashReceipt',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nBBId');
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
        $conversion       = $converted_amount[0];
        //$conversion = preg_replace('/[x00-x08x0B-x1F]/', '', $conversion);
        $conversion       = $conversion * $this->fcAmount;
        $conversion       = round($conversion, 2);
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
		$this->db->delete('{PRE}BankBookDetails', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow             = array();
		$sqlCashreceiptFilterQuery = "SELECT bb.*,d.*,bb.nBBId as bbId,d.nId as divId,d.vTitle as divName
									   FROM {PRE}BankBook bb LEFT JOIN {PRE}Division d ON bb.nDivision = d.nId
								      ".$extraCheck." 
                      bb.vLocation IN ". $this->session->userdata('accessLoc') ." 
                      ORDER BY bb.nBBId DESC
									   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query']    = $sqlCashreceiptFilterQuery;	
		$adminGroupList 		   = $this->db->query($sqlCashreceiptFilterQuery);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT bb.*,d.*,bb.nBBId as bbId,d.nId as divId,d.vTitle as divName
										FROM {PRE}BankBook bb LEFT JOIN {PRE}Division d ON bb.nDivision = d.nId
									  ".$extraCheck." 
                    bb.vLocation IN ". $this->session->userdata('accessLoc') ." 
                    ORDER BY bb.nBBId DESC");
		return ($tabCount->num_rows());
	}//end function

    function filterAllocation($ddlAccount,$ddlSubAccount)
    {
        $adminGroupRow    = array();
		if($this->uri->segment(4) == 'a')
		{
			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.vAllTktNumber     ,
                                                    CASE WHEN ca.vBalAmt > 0 THEN (scd.vAllocation - (CASE WHEN
        											(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																AND nSalesCloseDetId = scd.nId 
                    									AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND
														 	vTable = '".$this->input->post('tableVal')."') is null THEN 0
            											ELSE 
             												(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 
																	AND nSalesCloseDetId = scd.nId 
                    										AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND
														 vTable = '".$this->input->post('tableVal')."') END)) ELSE scd.vAllocation END AS allocAmt ,
                                                    CASE WHEN scd.vAllocation - (CASE WHEN
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
                                                        AND  scd.vAllocation > 0
                                                    GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");
		}//end if
		else
		{
			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
													WHERE ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = 'BR'
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
      $chksalesId      = $this->input->post('salesid');

      //delete cash allocation with checked
      if($this->uri->segment(4) == 'e')
      {
        $this->db->delete('{PRE}CashAllocation',array('nReceiptDId' => $this->uri->segment(5),'vAllType' => 'BR'));

      }//end if

      //insert into cash receipt details
      foreach($chkId as $key => $aitem)
      {
        $chksalesId_1 = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$aitem."'");
        $getCashAllocId = $this->common_model->get_field_name('CashAllocation','nCAId'," WHERE nReceiptDId = 0 AND nSalesCloseDetId =". $aitem . " AND nSalesCloseId = ".$chksalesId_1 . "  AND vAllType = 'BR' AND vAllTktNumber = '". $allTkt[$key] . "' " );
        if($getCashAllocId != ''){
          // update if present in db
          $data =  array('vAllAmt' => $allAmt[$key],
              'vBalAmt' => $allBalAmt[$key]);
          $this->db->where('nCAId' ,$getCashAllocId);
          $this->db->update('{PRE}CashAllocation', $data);
        } else{
          $data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId_1,
              'nSalesCloseDetId' => $aitem,'vAllType' => 'BR','dDate' => $allDate[$key],
              'nDrAccount' => $this->session->userdata('AccVal'),'vTable' => $this->session->userdata('TableVal'),
              'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],
              'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key],'nReceiptDId' => '0');
          $this->db->insert('{PRE}CashAllocation', $data);
        }// end if

      }//end foreach	
    }//end function

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
									AND nReceiptDId = '".$rid."'  AND vAllType = 'BR'");
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
                         FROM {PRE}BankBookDetails tbl WHERE nBankbookId= ".$Id."");
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
