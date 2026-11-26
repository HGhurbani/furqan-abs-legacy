<?php
class Acm_entry_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor		
        parent::__construct();date_default_timezone_set('Asia/Kuwait');
    }
	
/*----------------------Bank Book Entry Details-----------------*/
	
	function getAllAcmEntry()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlCreditvoucherQuery  = "SELECT cv.*,d.vTitle FROM {PRE}AcmEntry cv LEFT JOIN {PRE}Division d
												  ON cv.nDivision = d.nId 
                         WHERE cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                          ORDER BY nACId DESC
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

	function getCountAcmEntry()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}AcmEntry 
        WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nACId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresAcmEntry()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}AcmEntry WHERE nACId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 

    function getDebitModeType()
    {
        $modeTyperow = array("Dr" => "Debit");
        return ($modeTyperow);
    }//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $acAccRow = array();
        $acAcc    = $this->db->query("SELECT nLoc FROM {PRE}AcmEntryDetails WHERE nAcId = '".$nId."'");

        if ($acAcc->num_rows() > 0)
        {
            foreach ($acAcc->result() as $key=>$row)
            {
                $acAccRow[] = $row->nLoc;
            }//end for each
        }//end if
        return (array_filter($acAccRow));
    }//end function
	
	function getCorresAcmEntryDetails()
	{
        $creditDetailsLoc       	= array();
		$creditDetailsCode      	= array();
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
        $creditDetailsTable         = array();
		$newValArray          	    = array();
		
		$creditDetailsList      	= $this->db->query("SELECT * FROM {PRE}AcmEntryDetails WHERE nAcId ='".$this->catId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $creditDetailsLoc[]           = $row->nLoc;
                $creditDetailsCode[]      	  = $row->vACode.'-'.$row->vTable;
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
                $creditDetailsTable[]         = $row->vTable;

            }//end for each
        }//end if

        $newValArray['acLoc']    	   		= $creditDetailsLoc;
        $newValArray['acCode']    	   		= $creditDetailsCode;
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
        $newValArray['acTable']             = $creditDetailsTable;

        return ($newValArray);
	}//end function
	
	function insertAcmEntry()
	{
	$doc=$this->common_model->get_field_name('AcmEntry','vDocNumber'," WHERE vDocNumber = '".$this->input->post('txtDocNumber')."' ");
                    
		if($doc!=$this->input->post('txtDocNumber')){
    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
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


		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
                      'vLocation' => $this->input->post('ddlLocation'),
					  'dDate' => $date1,'nTransaction' => $this->input->post('ddlTransaction'),
                      'vSubCode' => $this->input->post('ddlSubAccount'),'nAcCode' => $this->input->post('ddlAccount'),
					  'vDocNumber' => $this->input->post('txtDocNumber'),'vTransMode' => $this->input->post('ddlModeMain'),
					  'vAmountDC' => $this->input->post('txtAmountDC'),'vAmountFC' => $this->input->post('txtAmountFCMain'),
					  'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
					  'vChequeNumber' => $this->input->post('txtCheqNumber'),'dChequeDate' => $cheqDate, 
					  'dRefDate' => $refDate ,'vRefNumber' => $this->input->post('txtRefNumberMain'),
					  'vEnteredBy' => $this->input->post('txtEntered'),'vRefName' => $this->input->post('txtRefName'),
					  'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
					  'dInvoiceDate' => $invDate ,'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
		$this->db->insert('{PRE}AcmEntry', $data);
		
		$id			  	 = $this->db->insert_id();

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
		
	    //insert into credit voucher details
		foreach($accCode as $key => $aitem)
		{
            $accName = @split("-",$accCode[$key]);
			$data 	  = array('nAcId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
			$this->db->insert('{PRE}AcmEntryDetails', $data);
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
			redirect(site_url("acm_entry/editAcmEntry/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("acm_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
		}
	}//end class
	
	function updateAcmEntry()
	{
		$admin_email = "support@abskw.com";
		$email = "acm@abskw.com";
		$subject = "ACM Updated : ".$this->input->post('txtDocNumber');
		$comment = "\n\nEnter Name : ".$this->common_model->get_field_name('AcmEntry','vEnteredBy'," WHERE nACId = '".$this->input->post('nId')."' ");
		$comment .= "\n\nUpdated Name : ".$this->session->userdata(showName) . "-" . $this->session->userdata(showLName);;
		$comment .= "\n\nEnter Date : ".$this->common_model->get_field_name('AcmEntry','edate'," WHERE nACId = '".$this->input->post('nId')."' ");
		$comment .= "\n\nUpdated Date : ".date('Y-m-d H:i:s');
		$message = '';
		$AcmEntryDetails = $this->db->query("SELECT * FROM {PRE}AcmEntryDetails WHERE nAcId = '".$this->input->post('nId')."' ");
		if ($AcmEntryDetails->num_rows() > 0)

		{

			foreach ($AcmEntryDetails->result() as $key=>$row)

			{
				switch($row->vTable){

					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $row->vACode  ."'"); break;

					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $row->vACode  ."'"); break;

					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $row->vACode  ."'"); break;

					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $row->vACode  ."'"); break;

					default: $row->vAcSubCode = $row->vACode ; $drAccountName = ""; break;

				}//end switch
				
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vAcSubCode."'");

				$message .= "\n\nAcId : ".$row->nAcId;
				
				$message .= "\n\nvACode : ".$drAccountName;
				$message .= "\n\nvAcSubCode : ".$subAccName;
				//$message .= "\n\nCurrency : ".$row->nCurrency;
				$message .= "\n\nvFCAmount : ".$row->vFCAmount;
				$message .= "\n\nAmount : ".$row->nAmount;
				//$message .= "\n\nAcAllocatedAmt : ".$row->nAcAllocatedAmt;
				$message .= "\n\nvDes : ".$row->vDes;
				//$message .= "\n\nvTable : ".$row->vTable;
				
				
			}//end for each

		}//end if
		$acc='';
		
		$comment .= "\n\n Old Details : ".$message;
		
		mail($admin_email, "$subject", $comment, "From:" . $email);

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
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

		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
                        'vLocation' => $this->input->post('ddlLocation'),
                      'dDate' => $date1,'nTransaction' => $this->input->post('ddlTransaction'),
                      'vDocNumber' => $this->input->post('txtDocNumber'),'vTransMode' => $this->input->post('ddlModeMain'),
                      'vAmountDC' => $this->input->post('txtAmountDC'),'vAmountFC' => $this->input->post('txtAmountFCMain'),
                      'vSubCode' => $this->input->post('ddlSubAccount'),'nAcCode' => $this->input->post('ddlAccount'),
                      'nCurrency' => $this->input->post('ddlCurrencyMain'),'vRate' => $this->input->post('txtRate'),
                      'vChequeNumber' => $this->input->post('txtCheqNumber'),'dChequeDate' => $cheqDate,
                      'dRefDate' => $refDate ,'vRefNumber' => $this->input->post('txtRefNumberMain'),
                      'vEnteredBy' => $this->input->post('txtEntered'),'vRefName' => $this->input->post('txtRefName'),
                      'vRemarks' => $this->input->post('txtRemarks'),'vInvoice' => $this->input->post('txtInvoice'),
					  'dInvoiceDate' => $invDate ,'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
		$this->db->where('nACId',$this->input->post('nId'));
		$this->db->update('{PRE}AcmEntry', $data);

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

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
            $accName = @split("-",$accCode[$key]);
			//checking already exists if yes updated
			if(intval($chkAccID[$key]) != 0)
			{
				$data 	= array('nAcId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}AcmEntryDetails', $data);
			}//end if
			else
			{//if new insert into db
				$data 	= array('nAcId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nCurrency' =>  $this->input->post('ddlCurrencyMain'),'nLoc' => $this->input->post('ddlLocation'),
                                'vAcRefNumber' => $accRefNumber[$key],'vFCAmount' => $accAmountFC[$key],
                                'vExchange' => $this->input->post('txtRate') ,'nAmount' => $accAmount[$key],
                                'vDes' => $accDes[$key],'vTable' => $accName[1]);
				$this->db->insert('{PRE}AcmEntryDetails', $data);
			}//end else
		}//end foreach
		
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
		    redirect(site_url("acm_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
	}//end function
	
	function deleteAcmEntry()
	{
		$this->db->delete('{PRE}AcmEntryDetails', array('nAcId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}AcmEntry', array('nACId' => $this->uri->segment(4)));
		redirect(site_url("acm_entry/showlist/".$this->uri->segment(3).'/del_suc'));
	}//end function 
	
	function deleteAllAcmEntry()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nACId',$val);
					$this->db->update('{PRE}AcmEntry', $data);
					
					$this->db->where('nAcId',$val);
					$this->db->update('{PRE}AcmEntryDetails', $data);
				}//end foreach
				redirect(site_url("acm_entry/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nACId',$val);
					$this->db->update('{PRE}AcmEntry', $data);
					
					$this->db->where('nAcId',$val);
					$this->db->update('{PRE}AcmEntryDetails', $data);
				}//end foreach
				redirect(site_url("acm_entry/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}AcmEntryDetails', array('nAcId' => $val));
					$this->db->delete('{PRE}AcmEntry', array('nACId' => $val));
				}//end foreach
				redirect(site_url("acm_entry/showlist/".$this->input->post('nCId').'/del_suc'));
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
			array_push($result, array(
				"name" => $value
			));
		}//end fuction
		return ($result);
	}//end function 
	
	function getActiveDivision()
	{
		$divGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1' 
											 AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
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
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."' 
											ORDER BY nPosition DESC");
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

    function getSubAccountMultipleValues()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND ahc.vSubLedger = 'S' ORDER BY nId ASC");
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
		$viewAccessList  = $this->db->query("SELECT cv.nACId,cvd.*,cv.*,cv.nDivision as divId,t.vTitle as transName,c.vTitle as currName,aha.*,
											 aha.vACode as accCode,aha.vTitle as accName,
											  CASE 
												WHEN cvd.vMode = 'Cr' THEN 'Credit'
											  ELSE 'Debit' END as mode
											   FROM {PRE}AcmEntryDetails
												cvd LEFT JOIN {PRE}AcmEntry cv
											 ON cv.nACId = cvd.nAcId LEFT JOIN {PRE}Transactions t ON t.nId = cv.nTransaction
											 LEFT JOIN {PRE}Currency c ON c.nId = cv.nCurrency
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON cvd.vACode = aha.nId
											 WHERE cv.nACId='".$creditid."' ORDER BY cvd.nId DESC");
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
											  ELSE 'Debit' END as mode FROM {PRE}AcmEntryDetails WHERE nAcId = '".$creditid."' ORDER BY nId DESC");

		
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
		$this->db->delete('{PRE}AcmEntryDetails', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT cv.*,d.*,cv.nACId as cvId,d.nId as divId,d.vTitle as divName
											FROM {PRE}AcmEntry cv LEFT JOIN {PRE}Division d ON cv.nDivision = d.nId
											 ".$extraCheck." 
                      AND cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                       ORDER BY cv.nACId DESC
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
		$tabCount = $this->db->query("SELECT cv.*,d.*,cv.nACId as cvId,d.nId as divId,d.vTitle as divName
											FROM {PRE}AcmEntry cv LEFT JOIN {PRE}Division d ON cv.nDivision = d.nId
											 ".$extraCheck." 
                       AND cv.vLocation IN ". $this->session->userdata('accessLoc') ." 
                       ORDER BY cv.nACId DESC");
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
													  ORDER BY nId ASC");
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

    function getActiveAccount()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
										  WHERE ah.vActive = '1' AND ahc.vSubSale = '1' AND ahc.vPrefix = 'BSP' ORDER BY vTitle DESC");
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

	 function getActiveA()
    {
        $typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '52'
													  ORDER BY vName DESC");
           
           if ($typeGroup->num_rows() > 0)
        {
//            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nVId] = $row->vName;
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
//        $custGroupRow[0] = NOVALUE;

	    $account =   $this->getActiveAccount();
	    foreach ($account as $key=>$row){
		    $custGroupRow = $this->getActiveAccountHead($key);
		    break;
	    }
        return ($custGroupRow);
    }//end function

    function getActiveAccountHead($accGroup)
    {

      $accheadGroupRow    = array();
      $location = $this->input->post('ddlLocation');
      $division = $this->input->post('ddlDivision');
        if($accGroup>0):
            $accheadGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$accGroup."'
													  ORDER BY vName ASC");
            if ($accheadGroup->num_rows() > 0)
            {
                foreach ($accheadGroup->result() as $key=>$row)
                {
                  $locArray = explode(',',$row->vLocation);
                  if(in_array($location, $locArray))
                    $accheadGroupRow[$row->nVId] = $row->vName ;
                }//end for each
            }//end if
            else
            {
              $accheadGroupRow[0] = NOVALUE;
            }//end else
        else:
            $accheadGroupRow[0] = NOVALUE;
        endif;
        if(empty($accheadGroupRow)) $accheadGroupRow[0] = NOVALUE;
        return ($accheadGroupRow);
    }//end function


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
                         FROM {PRE}AcmEntryDetails tbl WHERE nAcId= ".$Id."");
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
