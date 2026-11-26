<?php
class Recurring_jv_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		date_default_timezone_set('Asia/Kuwait');
    }
	
/*----------------------Cash Book Receipt Details-----------------*/
	
	function getAllRecurrJv()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlCashreceiptQuery    = "SELECT * FROM {PRE}RecurringJv 
      WHERE nLocation IN ". $this->session->userdata('accessLoc') ." 
      ORDER BY nRJId DESC
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

    //get Location details
    function getAcAccountsLocation($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nLoc FROM {PRE}RecurringJvSetup WHERE nRecurId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nLoc;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

	function getCountRecurrJv()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}RecurringJv 
        WHERE nLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nRJId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresRecurrJv()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}RecurringJv WHERE nRJId = '".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresRecurrJvSetup()
	{
		$cashDetailsLoc       	  = array();
		$cashDetailsCode      	  = array();
		$cashDetailsSubCode       = array();
		$cashDetailsMode      	  = array();
		$cashDetailsAmount        = array();
		$newValArray          	  = array();
        $cashReceiptDetailID      = array();
		
		$cashDetailsList      	  = $this->db->query("SELECT * FROM {PRE}RecurringJvSetup WHERE nRecurId = '".$this->catId."' AND nLocation IN ".$this->session->userdata('accessLoc') ."  ");
		if ($cashDetailsList->num_rows() > 0)
		{
			foreach ($cashDetailsList->result() as $key=>$row)
			{
                $cashDetailsLoc[]         	  = $row->nLocation;
				$cashDetailsCode[]      	  = $row->nAccount;
				$cashDetailsSubCode[]      	  = $row->nSubAcc;
				$cashDetailsMode[]      	  = $row->vMode;
				$cashDetailsAmount[] 		  = $row->vAmount;
                $cashReceiptDetailID[]     	  = $row->nId;

			}//end for each
		}//end if
		
		$newValArray['acLoc']    	   		= $cashDetailsLoc;
		$newValArray['acCode']    	   		= $cashDetailsCode;
		$newValArray['acSubCode']    	   	= $cashDetailsSubCode;
		$newValArray['acMode']    	   		= $cashDetailsMode;
		$newValArray['acAmount'] 	        = $cashDetailsAmount;
		$newValArray['acDetailID']          = $cashReceiptDetailID;

		return ($newValArray);
	}//end function

    function getCorresRecurrJvSchedule()
    {
        $cashDetailsDate       	  = array();
        $cashDetailsStatus    	  = array();
        $newValArray          	  = array();
        $cashReceiptDetailID      = array();
		$cashDetailsjv = array();
        $cashDetailsList      	  = $this->db->query("SELECT * FROM {PRE}RecurringJvShedule WHERE nRecurId = '".$this->catId."'");
        if ($cashDetailsList->num_rows() > 0)
        {
            foreach ($cashDetailsList->result() as $key=>$row)
            {
                $cashDetailsDate[]         	  = $row->dDate;				$cashDetailsjv[]         	  = $row->jvid;
                $cashDetailsStatus[]      	  = $row->vStatus;
                $cashReceiptDetailID[]     	  = $row->nId;

            }//end for each
        }//end if

        $newValArray['scDate']    	    = $cashDetailsDate;		 $newValArray['jvid']    	    = $cashDetailsjv;
        $newValArray['scStatus']    	= $cashDetailsStatus;
        $newValArray['scId']    	   	= $cashReceiptDetailID;

        return ($newValArray);
    }//end function

    function getDocumentNumber($loc,$date)
    {
       
        $this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
    $type = "Journal Voucher";


     $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='19'");
        //$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv ORDER BY nJVId DESC LIMIT 0,1");
		$this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
   // $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv WHERE vLocation = '". $loc."' AND YEAR(dDate) ='". $date."'  ORDER BY nJVId DESC,dDate DESC LIMIT 0,1");
	     if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= trim($totalNum->vDocNumber);
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE vTitle='".$type."'");
            $docNumber 			= @split('-',$this->txtDocNumber);
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        return($countVal);
    }//end function
	
	function getDocumentNumber_rj($loc,$date)
    {
       
        $this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
    $type = "Journal Voucher";


     $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='20'");
        //$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv ORDER BY nJVId DESC LIMIT 0,1");
		$this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
   // $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RecurringJv WHERE vLocation = '". $loc."' AND YEAR(dDate) ='". $date."'  ORDER BY nJVId DESC,dDate DESC LIMIT 0,1");
	     if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= trim($totalNum->vDocNumber);
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='20'");
            $docNumber 			= @split('-',$this->txtDocNumber);
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        return($countVal);
    }//end function

    function insertJv($scheduleID)
    {
        $this->catId                  = $this->common_model->get_field_name('RecurringJvShedule','nRecurId'," WHERE nId='".$scheduleID."'");

        $showVal	          	  	  = $this->getCorresRecurrJv();
        $this->ddlDivision	  	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  	  = $showVal->nLocation;
        $this->txtDate      	  	  = $showVal->dDate;
        $this->txtDocNumber  	  	  = $this->getDocumentNumber($showVal->nLocation,$showVal->dDate);
        $this->ddlCurrency   	  	  = $showVal->nCurrency;
        $this->txtRate  		  	  = $showVal->vExRate;
        $this->txtTRX              	  = $showVal->vTrxType;
        $this->txtAmountFC     	  	  = $showVal->vAmtFc;
        $this->txtAmountDC		  	  = $showVal->vAmtDc;
        $this->txtStartDate   	  	  = $showVal->dStartDate;
        $this->txtFrequency        	  = $showVal->vFrequency;
        $this->txtNumJv      	  	  = $showVal->vNumJv;
        $this->txtEndDate         	  = $showVal->dEndDate;
        $this->txtRefNumber        	  = $showVal->vRefNum;
		$this->txtRefName        	  = $showVal->vTrxType;
        $this->txtRemarks         	  = $showVal->vRemarks;
        $this->chkActive         	  = $showVal->vActive;		
		$this->document         	  = $showVal->vDocument;
		$emp=$this->common_model->get_field_name('Admin', 'vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");
		$amt=($this->txtAmountDC/$this->txtFrequency);
			
		$datee=$this->common_model->get_field_name('RecurringJvShedule','dDate'," WHERE nId = '".$scheduleID."'");
        $data 		  = array('nUserId' => $this->session->userdata('AbsAdmin'),
		'nDivision' => $this->ddlDivision,'vLocation' => $this->ddlLocation,'dDate' => date('Y-m-d',strtotime($this->input->post('txtDate'))),
                              'vDocNumber' => $this->txtDocNumber,'nCurrency' => $this->ddlCurrency,'vRate' => $this->txtRate,
                              'vAmountDC' => $amt,'vAmountFC' => $amt,'vRemarks' => $this->txtRemarks,'vRefName'=>$this->txtRefName,'vRefNumber'=>$this->document,							  
							  'vEnteredBy'=>$emp,'dDate'=>$datee,
                              'vActive' => $this->chkActive,'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
        $this->db->insert('{PRE}Jv', $data);

        $id			  	              = $this->db->insert_id();


        $setupValArray                = $this->getCorresRecurrJvSetup();

        $locVal     	 = $setupValArray['acLoc'];
        $accCode      	 = $setupValArray['acCode'];
        $accSubCode      = $setupValArray['acSubCode'];
        $modeName     	 = $setupValArray['acMode'];
        $accAmountFC  	 = $setupValArray['acAmount'];

        //insert into budget details
        foreach($locVal as $key => $aitem)
        {
            $accName = @split("-",$accCode[$key]);
            $data 	  = array('nJournalId' => $id,'vACode' => $accName[0],'vAcSubCode' => $accSubCode[$key],
                                'vMode' => $modeName[$key],'nLoc' => $locVal[$key],'nAmount'=> $accAmountFC[$key],								'vFCAmount' => $accAmountFC[$key],'nCurrency'=>$this->ddlCurrency,'vExchange'=>$this->txtRate,
                                'vTable' => $accName[1]);
            $this->db->insert('{PRE}JvDetails', $data);
        }//end foreach

        $data 	  = array('vStatus' => 'Closed','jvid'=>$id);
        $this->db->where('nId',$scheduleID);
        $this->db->update('{PRE}RecurringJvShedule', $data);

    }//end class

	function insertRecurrJv()
	{
		$date=date('Y-m-d',strtotime($this->input->post('txtDate')));
		$loc=$this->input->post('ddlLocation');
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='20'");
        //$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv ORDER BY nJVId DESC LIMIT 0,1");
		$this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
   // $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocument FROM {PRE}RecurringJv WHERE nLocation = '".$loc."' AND YEAR(dDate) ='". $date."'  ORDER BY nRJId DESC,dDate DESC LIMIT 0,1");
	     if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= trim($totalNum->vDocument);
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='20'");
            $docNumber 			= @split('-',$this->txtDocNumber);
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        $doc=$countVal;
		$data = array('nDivision' => $this->input->post('ddlDivision'),'nLocation' => $this->input->post('ddlLocation'),
					  'dDate' => date('Y-m-d',strtotime($this->input->post('txtDate'))),'vTrxType' => $this->input->post('txtTRX'),
                      'vDocument' => $doc,'nCurrency' => $this->input->post('ddlCurrency'),
                      'vExRate' => $this->input->post('txtRate'),'nUserId' => $this->session->userdata('AbsAdmin'),
                      'vAmtDc' => $this->input->post('txtAmountDC'),'vAmtFc' => $this->input->post('txtAmountFC'),
                      'dStartDate' => date('Y-m-d',strtotime($this->input->post('txtStartDate'))),'vFrequency' => $this->input->post('txtFrequency'),
                      'vNumJv' => $this->input->post('txtNumJv'),'dEndDate' => date('Y-m-d',strtotime($this->input->post('txtEndDate'))),
					  'vRefNum' => $this->input->post('txtRefNumber'),'vRemarks' => $this->input->post('txtRemarks'),
					  'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
		$this->db->insert('{PRE}RecurringJv', $data);
		
		$id			  	 = $this->db->insert_id();
        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
		$chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;
		
	    //insert into cash receipt details
		foreach($chkBox as $key => $aitem)
		{
			$accName = @split("-",$accCode[$key]);
			$data 	 = array('nRecurId' => $id,'nLocation' => $locVal[$key],'nAccount' => $accCode[$key],
                             'nSubAcc' => $accSubCode[$key],'vMode' => $modeName[$key],'vAmount' => $accAmount[$key]);
			$this->db->insert('{PRE}RecurringJvSetup', $data);
		}//end foreach

        $schedule     	 = (@array_filter($this->input->post('txtScheduleDate')) != '')?$this->input->post('txtScheduleDate'):0;
        $status      	 = (@array_filter($this->input->post('txtStatus')) != '')?$this->input->post('txtStatus'):0;
        $chkBox2         = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;

        //insert into cash receipt details
        foreach($chkBox2 as $key => $aitem)
        {
            $data 	 = array('nRecurId' => $id,'dDate' => date('Y-m-d',strtotime($schedule[$key])),'vStatus' => $status[$key]);
            $this->db->insert('{PRE}RecurringJvShedule', $data);
        }//end foreach

		$msgVal = 'add_suc';
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("recur_jv/editRecurrJv/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("recur_jv/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end if
	}//end class
	
	function updateRecurrJv()
	{
		$data = array('nDivision' => $this->input->post('ddlDivision'),'nLocation' => $this->input->post('ddlLocation'),
            'dDate' => date('Y-m-d',strtotime($this->input->post('txtDate'))),'vTrxType' => $this->input->post('txtTRX'),
            'vDocument' => $this->input->post('txtDocNumber'),'nCurrency' => $this->input->post('ddlCurrency'),
            'vExRate' => $this->input->post('txtRate'),'nUserId' => $this->session->userdata('AbsAdmin'),
            'vAmtDc' => $this->input->post('txtAmountDC'),'vAmtFc' => $this->input->post('txtAmountFC'),
            'dStartDate' => date('Y-m-d',strtotime($this->input->post('txtStartDate'))),'vFrequency' => $this->input->post('txtFrequency'),
            'vNumJv' => $this->input->post('txtNumJv'),'dEndDate' => date('Y-m-d',strtotime($this->input->post('txtEndDate'))),
            'vRefNum' => $this->input->post('txtRefNumber'),'vRemarks' => $this->input->post('txtRemarks'),
            'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
		$this->db->where('nRJId',$this->input->post('nId'));
		$this->db->update('{PRE}RecurringJv', $data);

		$id			     = $this->input->post('nId');
        $locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlAccount2')) != '')?$this->input->post('ddlAccount2'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount2')) != '')?$this->input->post('ddlSubAccount2'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;
		$chkAccID	     = $this->input->post('idSetupArray');

	    //insert into Account head accounts table
		foreach($chkBox as $key => $aitem)
		{
			if(intval($chkAccID[$key]) != 0)
			{
                $data 	  = array('nLocation' => $locVal[$key],'nAccount' => $accCode[$key],
                    'nSubAcc' => $accSubCode[$key],'vMode' => $modeName[$key],'vAmount' => $accAmount[$key]);
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}RecurringJvSetup', $data);
			}//end if
			else
			{
                $data 	 = array('nRecurId' => $id,'nLocation' => $locVal[$key],'nAccount' => $accCode[$key],
                    'nSubAcc' => $accSubCode[$key],'vMode' => $modeName[$key],'vAmount' => $accAmount[$key]);
                $this->db->insert('{PRE}RecurringJvSetup', $data);
			}//end else
		}//end foreach

        $schedule     	 = (@array_filter($this->input->post('txtScheduleDate')) != '')?$this->input->post('txtScheduleDate'):0;
        $status      	 = (@array_filter($this->input->post('txtStatus')) != '')?$this->input->post('txtStatus'):0;
        $chkBox2         = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;
        $chkAccID2	     = $this->input->post('idScheduleArray');

        //insert into Account head accounts table
        foreach($chkBox2 as $key => $aitem)
        {
            if(intval($chkAccID2[$key]) != 0)
            {
                $data 	  = array('dDate' => date('Y-m-d',strtotime($schedule[$key])),'vStatus' => $status[$key]);
                $this->db->where('nId',$chkAccID2[$key]);
                $this->db->update('{PRE}RecurringJvShedule', $data);
            }//end if
            else
            {
                $data 	 = array('nRecurId' => $id,'dDate' => date('Y-m-d',strtotime($schedule[$key])),'vStatus' => $status[$key]);
                $this->db->insert('{PRE}RecurringJvShedule', $data);
            }//end else
        }//end foreach

		$msgVal = 'edit_suc';
        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
		    redirect(site_url("recur_jv/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
	
	function deleteRecurrJv()
	{
		$this->db->delete('{PRE}RecurringJvSetup', array('nRecurId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}RecurringJv', array('nRJId' => $this->uri->segment(4)));		$this->db->delete('{PRE}RecurringJvShedule', array('nRecurId' => $this->uri->segment(4)));
		redirect(site_url("recur_jv/showlist/".$this->uri->segment(3).'/del_suc'));
	}//end function 
	
	function deleteAllRecurrJv()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nRJId',$val);
					$this->db->update('{PRE}RecurringJv', $data);
					
					$this->db->where('nRecurId',$val);
					$this->db->update('{PRE}RecurringJvSetup', $data);
				}//end foreach
				redirect(site_url("recur_jv/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nRJId',$val);
					$this->db->update('{PRE}RecurringJv', $data);
					
					$this->db->where('nRecurId',$val);
					$this->db->update('{PRE}RecurringJvSetup', $data);
				}//end foreach
				redirect(site_url("recur_jv/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}RecurringJvSetup', array('nRecurId' => $val));
					$this->db->delete('{PRE}RecurringJv', array('nRJId' => $val));					$this->db->delete('{PRE}RecurringJvShedule', array('nRecurId' => $val));
				}//end foreach
				redirect(site_url("recur_jv/showlist/".$this->input->post('nCId').'/del_suc'));
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
										  WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' ORDER BY nId DESC");
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
											WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' AND vSubLedger = 'S' ORDER BY nId DESC");
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

	function getSubAccountValues()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return($typeGroupRow);
	}//end function

    function getAccountMultipleValues()
	{
        $typeGroupRow    = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nCId IN(SELECT nDrAccount FROM {PRE}SalesAccountDetails WHERE vTable = 'C') ORDER BY nCId DESC");

        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId 
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101') 
										   		ORDER BY nId DESC");

        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        if ($accheadGroup->num_rows() > 0)
        {
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        $resultArray = array_merge($typeGroupRow, $accheadGroupRow);
        return ($resultArray);
	}//end function
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

            case "A" :
                $accheadGroupRow    = array();
                $accheadGroupRow[0] = NOVALUE;
            break;
        }//end switch
        return ($accheadGroupRow);
    }//end function
*/


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
														LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' 
                           AND v.nFId = '".$accNameId."'
                           ORDER BY nId DESC");
                    //ahc.vSubLedgerType = 'A' 
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


    function getSubAccount($accGroup)
    {
        $accName   = @split("-",$accGroup);
        $accGroup  =  $accName[0];
        $table     =  $accName[1];

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

            case "A" :
                $accheadGroupRow    = array();
                $accheadGroupRow[0] = NOVALUE;
                break;
        }//end switch
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
        $modeTyperow = array("Dr" => "Debit","Cr" => "Credit");
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
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='20'");
        $fetchCode          	= $this->db->query("SELECT vDocument FROM {PRE}RecurringJv ORDER BY nRJId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocument;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='20'");
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
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId IN ".$this->session->userdata('accessLoc') ."  ORDER BY nPosition DESC");
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
	
	function getActiveTransactions()
	{
		$transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nId = '11'");
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
											ORDER BY nPosition DESC");
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

    function filterAllocation($ddlAccount,$ddlSubAccount)
    {
        $adminGroupRow    = array();
		if($this->uri->segment(4) == 'a')
		{
			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,CASE WHEN ca.vBalAmt > 0 THEN (scd.vCollected - sum(ca.vAllAmt))
												  ELSE scd.vCollected END AS allocAmt FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
													WHERE sad.nDrAccount = '".$ddlAccount."' AND
												  sad.nAcHead = '".$ddlSubAccount."' GROUP BY scd.vTktNumber ORDER BY sc.dDate DESC");
		}//end if
		else
		{
			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
													WHERE sad.nDrAccount = '".$ddlAccount."' AND sad.nAcHead = '".$ddlSubAccount."'
												  AND ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = 'CR'
													 AND sad.nDrAccount = '".$ddlAccount."'
												  GROUP BY scd.nId ORDER BY sc.dDate ASC");
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

	//fetching single details
	function showDetails($cashid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}RecurringJv WHERE nRJId = '".$cashid."'");
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
        $content  = '<table class="reportForm">
						<tr>
							<td><strong>'.DIVISIONNAME.'</strong></td>
							<td>'.$divName.'</td>
							<td><strong>'.LOCATIONNAME.'</strong></td>
							<td>'.$locName.'</td>
						    <td><strong>'.CUSTDATE.'</strong></td>
						    <td>'.$dDate.'</td>
							<td><strong>'.TRANSNAME.'</strong></td>
							<td>'.$row->vDocument.'</strong></td>
							<td><strong>'.CURRNAME.'</strong></td>
							<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</strong></td>
							
					    </tr>

						<tr>
						    <td><strong>'.TRANSMODE.'</strong></td>
						    <td>'.$modeofPay.'</strong></td>
							<td><strong>'.ACCCODENAME.'</strong></td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'").'</td>
						    <td><strong>'.SUBCODE.'</strong></td>
						    <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'").'</td>
						    <td><strong>'.CURRNAME.'</strong></td>
							<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>
							<td><strong>'.RATE.'</strong></td>
							<td>'.$row->vRate.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.AMOUNTDC.'</strong></td>
							<td>'.$row->vAmountDC.'</td>
							<td><strong>'.AMOUNTFC.'</strong></td>
							<td>'.$row->vAmountFC.'</td>
						    <td><strong>'.REFNAME.'</strong></td>
						    <td>'.$row->vRefName.'</td>
						    <td><strong>'.REFDATE.'</strong></td>
						    <td>'.$dRefDate.'</td>
							<td><strong>'.REFNUMBER.'</strong></td>
							<td>'.$row->vRefNumber.'</td>
						</tr>

						<tr>
						    <td><strong>'.ENTEREDBY.'</strong></td>
						    <td>'.$row->vEnteredBy.'</td>
						    <td><strong>'.REMARKS.'</strong></td>
							<td colspan="3">'.$row->vRemarks.'</td>
						    <td><strong>'.INVOICE.'</strong></td>
						    <td>'.$row->vInvoice.'</td>
						    <td><strong>'.INVOICEDATE.'</strong></td>
						    <td>'.$dInvDate.'</td>
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
						
		$viewAccessList1  = $this->db->query("SELECT * FROM {PRE}RecurringJvSetup WHERE nRecurId = '".$cashid."' ORDER BY nId DESC");

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
		$fetchTitle 	  = $this->db->query("SELECT ahc.*,cr.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}RecurringJv cr ON cr.nAcCode=ahc.nId
												 WHERE ahc.nActHId='".$financeId."'");
		$showVal	      = $fetchTitle->row();
		$chkCode		  = $this->getAutomaticCode('RecurringJv',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nRJId');
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
		$this->db->delete('{PRE}RecurringJvSetup', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow             = array();
		$sqlCashreceiptFilterQuery = "SELECT cr.*,d.*,cr.nRJId as crId,d.nId as divId,d.vTitle as divName
									   FROM {PRE}RecurringJv cr LEFT JOIN {PRE}Division d ON cr.nDivision = d.nId
								      ".$extraCheck." ORDER BY cr.nRJId DESC
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
		$tabCount = $this->db->query("SELECT cr.*,d.*,cr.nRJId as crId,d.nId as divId,d.vTitle as divName
										FROM {PRE}RecurringJv cr LEFT JOIN {PRE}Division d ON cr.nDivision = d.nId
									  ".$extraCheck." ORDER BY cr.nRJId DESC");
		return ($tabCount->num_rows());
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
            $this->db->delete('{PRE}CashAllocation',array('nReceiptDId' => $this->uri->segment(5),'vAllType' => 'CR'));
		}//end if
			
		//insert into cash receipt details
		foreach($chkId as $key => $aitem)
		{
			$chksalesId = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$aitem."'");
			$data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId,'nSalesCloseDetId' => $aitem,'vAllType' => 'CR','dDate' => $allDate[$key],
							    'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],
							    'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key],'nReceiptDId' => '0');
			$this->db->insert('{PRE}CashAllocation', $data);
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
									AND nReceiptDId = '".$rid."'  AND vAllType = 'CR'");
		if($chkSql->num_rows() > 0)
			$val = ' checked="checked"';
		else
			$val = '';
		
		return ($val);
	}//end funciton
	
	//get count
	function getJvCnt($date1,$date2)
	{
		$getDate = $this->db->query("SELECT TIMESTAMPDIFF(MONTH, '".$date1."', '".$date2."') 
											as diffCnt");
		if($getDate->num_rows() > 0)
		{
			$rval = $getDate->row();
			$cnt  = $rval->diffCnt;
		}//end if
		else
		{
			$cnt  = 0;
		}//end else
		
		return $cnt;
	}//end function
}//end class
?>
