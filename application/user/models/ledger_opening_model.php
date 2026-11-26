<?php
class Ledger_opening_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		date_default_timezone_set('Asia/Kuwait');
    }
	
/*----------------------Ledger Opening Balance-----------------*/
	
	function getAllLedgerOpening()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlLedgerQuery         = "SELECT * FROM {PRE}OpeningBalance 
      WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
      ORDER BY nOBId DESC
									 LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlLedgerQuery;	
		$adminGroupList 		= $this->db->query($sqlLedgerQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountLedgerOpening()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}OpeningBalance 
        WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nOBId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresLedgerOpenings()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}OpeningBalance WHERE nOBId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function

	function getCorresLedgerSubOpeningDetails()
	{
		$openSubDetailsRow       = array();
        $openSubDetailsLocation  = array();
        $openSubDetailsAccount   = array();
        $openSubDetailsSubAcc    = array();
        $openDetailsMode         = array();
        $openDetailsCurr         = array();
        $openDetailsRate         = array();
        $openDetailsBalDC        = array();
        $openDetailsBalFC        = array();
        $openDetailsBalCredit    = array();
        $openDetailsBalDebit     = array();
        $newValArray             = array();


		$openDetailsList      	 = $this->db->query("SELECT * FROM {PRE}OpeningBalanceDetails WHERE nBalId ='".$this->catId."' AND nLoc IN ".$this->session->userdata('accessLoc') ." ");
		if ($openDetailsList->num_rows() > 0)
		{
			foreach ($openDetailsList->result() as $key=>$row)
			{
				$openSubDetailsRow[]       = $row->nId;
				$openSubDetailsLocation[]  = $row->nLoc;
				$openSubDetailsAccount[]   = $row->vACode;
				$openSubDetailsSubAcc[]    = $row->vAcSubCode;
				$openDetailsMode[]         = $row->vMode;
				$openDetailsCurr[]         = $row->nCurrency;
				$openDetailsRate[]         = $row->vRate;
				$openDetailsBalDC[]        = $row->vBalDC;
				$openDetailsBalFC[]        = $row->vBalFC;
				$openDetailsBalCredit[]    = $row->vBalCredit;
				$openDetailsBalDebit[]     = $row->vBalDebit;
			}//end for each
		}//end if
		
		$newValArray['acDetId']        	   = $openSubDetailsRow;
		$newValArray['acLoc']      	       = $openSubDetailsLocation;
		$newValArray['acAccCode']      	   = $openSubDetailsAccount;
		$newValArray['acSubAcc']       	   = $openSubDetailsSubAcc;
		$newValArray['acMode']      	   = $openDetailsMode;
		$newValArray['acCurr']      	   = $openDetailsCurr;
		$newValArray['acRate']     	       = $openDetailsRate;
		$newValArray['acBalFC']      	   = $openDetailsBalFC;
		$newValArray['acBalDC']     	   = $openDetailsBalDC;
		$newValArray['acBalCredit']    	   = $openDetailsBalCredit;
		$newValArray['acBalDebit']   	   = $openDetailsBalDebit;

		return ($newValArray);
	}//end function
	
	function insertLedgerOpening()
	{

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }


        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vLedger' => $this->input->post('ddlLedger'),'vSubLedger' => $this->input->post('ddlSubLedger'),
                      'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
                      'vYear' => $this->input->post('txtOpeningYear'),'dDate' => $date1, 
					  'vTotalDC' => $this->input->post('txtTotalDC'),'vTotalFC' => $this->input->post('txtTotalFC'),
                      'nTotalC' => $this->input->post('txtTotalC'),'nTotalD' => $this->input->post('txtTotalD'),
                      'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
        $this->db->insert('{PRE}OpeningBalance', $data);

        $id			  	 = $this->db->insert_id();

        ///$locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlMACCode')) != '')?$this->input->post('ddlMACCode'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount')) != '')?$this->input->post('ddlSubAccount'):0;
        $accCurr     	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
        $accRate     	 = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $balanceFC     	 = (@array_filter($this->input->post('txtBalFC')) != '')?$this->input->post('txtBalFC'):0;
        $balanceDC     	 = (@array_filter($this->input->post('txtBalDC')) != '')?$this->input->post('txtBalDC'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $balanceCredit 	 = (@array_filter($this->input->post('txtBalCredit')) != '')?$this->input->post('txtBalCredit'):0;
        $balanceDebit  	 = (@array_filter($this->input->post('txtBalDebit')) != '')?$this->input->post('txtBalDebit'):0;

        //insert into budget details nCurrency' => $accCurr[$key],'vRate' => $accRate[$key]
        foreach($accCode as $key => $aitem)
        {
            $data 	  = array('nBalId' => $id,'nLoc' => $this->input->post('ddlLocation'),'vACode' => $accCode[$key],
                              'vAcSubCode' => $accSubCode[$key],'vMode' => $modeName[$key],
							  'nCurrency' => 5,'vRate' => 1,
                              'vBalDC' => $balanceDC[$key],'vBalFC' => $balanceFC[$key],
                              'vBalCredit' => $balanceCredit[$key],'vBalDebit' => $balanceDebit[$key]);
            $this->db->insert('{PRE}OpeningBalanceDetails', $data);
        }//end foreach

		$msgVal = 'add_suc';
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("ledger_opening/editLedgeropening/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("ledger_opening/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateLedgerOpening()
	{

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }



        $data = array('vLedger' => $this->input->post('ddlLedger'),'vSubLedger' => $this->input->post('ddlSubLedger'),
                        'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
                        'vYear' => $this->input->post('txtOpeningYear'),'dDate' => $date1,
						'vTotalDC' => $this->input->post('txtTotalDC'),'vTotalFC' => $this->input->post('txtTotalFC'),
                        'nTotalC' => $this->input->post('txtTotalC'),'nTotalD' => $this->input->post('txtTotalD'),
                        'nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
        $this->db->where('nOBId',$this->input->post('nId'));
        $this->db->update('{PRE}OpeningBalance', $data);

        $id			     = $this->input->post('nId');
        //$locVal     	 = (@array_filter($this->input->post('ddlLocation2')) != '')?$this->input->post('ddlLocation2'):0;
        $accCode      	 = (@array_filter($this->input->post('ddlMACCode')) != '')?$this->input->post('ddlMACCode'):0;
        $accSubCode      = (@array_filter($this->input->post('ddlSubAccount')) != '')?$this->input->post('ddlSubAccount'):0;
        $accCurr     	 = (@array_filter($this->input->post('ddlCurrency')) != '')?$this->input->post('ddlCurrency'):0;
        $accRate     	 = (@array_filter($this->input->post('txtExchange')) != '')?$this->input->post('txtExchange'):0;
        $balanceFC     	 = (@array_filter($this->input->post('txtBalFC')) != '')?$this->input->post('txtBalFC'):0;
        $balanceDC     	 = (@array_filter($this->input->post('txtBalDC')) != '')?$this->input->post('txtBalDC'):0;
        $modeName     	 = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $balanceCredit 	 = (@array_filter($this->input->post('txtBalCredit')) != '')?$this->input->post('txtBalCredit'):0;
        $balanceDebit  	 = (@array_filter($this->input->post('txtBalDebit')) != '')?$this->input->post('txtBalDebit'):0;
        $chkAccID	     = $this->input->post('idArray');

        //insert into Account head accounts table
        foreach($accCode as $key => $aitem)
        {
            //checking already exists if yes updated
            if(intval($chkAccID[$key]) != 0)
            {
                $data 	= array('nBalId' => $id,'nLoc' =>  $this->input->post('ddlLocation'),'vACode' => $accCode[$key],
                                'vAcSubCode' => $accSubCode[$key],'vMode' => $modeName[$key],
							    'nCurrency' => 5,'vRate' => 1,
                                'vBalDC' => $balanceDC[$key],'vBalFC' => $balanceFC[$key],
                                'vBalCredit' => $balanceCredit[$key],'vBalDebit' => $balanceDebit[$key]);
                $this->db->where('nId',$chkAccID[$key]);
                $this->db->update('{PRE}OpeningBalanceDetails', $data);
            }//end if
            else
            {
                //if new insert into db
                $data 	= array('nBalId' => $id,'nLoc' =>  $this->input->post('ddlLocation'),'vACode' => $accCode[$key],
                                'vAcSubCode' => $accSubCode[$key],'vMode' => $modeName[$key],
                                'vBalDC' => $balanceDC[$key],'vBalFC' => $balanceFC[$key],
                                'vBalCredit' => $balanceCredit[$key],'vBalDebit' => $balanceDebit[$key]);
                $this->db->insert('{PRE}OpeningBalanceDetails', $data);
            }//end else
        }//end foreach

		$msgVal = 'edit_suc';
		redirect(site_url("ledger_opening/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function

	function deleteLedgerOpening()
	{
		$this->db->delete('{PRE}OpeningBalanceDetails', array('nBalId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}OpeningBalance', array('nOBId' => $this->uri->segment(4)));
		redirect(site_url("ledger_opening/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllLedgerOpening()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nOBId',$val);
					$this->db->update('{PRE}OpeningBalance', $data); 
					
					$this->db->where('nBalId',$val);
					$this->db->update('{PRE}OpeningBalanceDetails', $data); 
				}//end foreach
				redirect(site_url("ledger_opening/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nOBId',$val);
					$this->db->update('{PRE}OpeningBalance', $data); 
					
					$this->db->where('nBalId',$val);
					$this->db->update('{PRE}OpeningBalanceDetails', $data); 
				}//end foreach
				redirect(site_url("ledger_opening/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}OpeningBalanceDetails', array('nBalId' => $val));
					$this->db->delete('{PRE}OpeningBalance', array('nOBId' => $val));
				}//end foreach
				redirect(site_url("ledger_opening/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function

    function getSubAccountValues()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return($typeGroupRow);
    }//end function

    function filterSubAccount($account)
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');
      $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
          ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
          WHERE aca.nId = '".$account."' ORDER BY ahc.nId DESC");
      $subledgerResult    = $subledgerQuery->row();
      $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

      $typeGroupRow       = array();
      switch($this->txtsubLedger)
      {
        case "C":
          $typeGroup    = $this->db->query("SELECT cm.* FROM {PRE}AccountHeadAccounts aca
              LEFT JOIN {PRE}CustomerMaster cm ON cm.nFId = aca.nId WHERE cm.vActive = '1'
              AND aca.nId = '".$account."' ORDER BY cm.nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
          $typeGroupRow[0] = SELECT;
          foreach ($typeGroup->result() as $key=>$row)
          {
            $locArray = explode(',',$row->vLocation);
            if($division != "" && $location != ""){
              if(in_array($location, $locArray))
                $typeGroupRow[$row->nCId] = $row->vName.' ( '.$row->vCode.' )';
            }else{
              $typeGroupRow[$row->nCId] = $row->vName.' ( '.$row->vCode.' )';
            }
          }//end for each
        }//end if
        else
        {
          $typeGroupRow[0] = NOVALUE;
        }//end else
        break;

        case "V":
          $typeGroup    = $this->db->query("SELECT vm.* FROM {PRE}AccountHeadAccounts aca
              LEFT JOIN {PRE}VendorMaster vm ON vm.nFId = aca.nId WHERE vm.vActive = '1'
              AND aca.nId = '".$account."' ORDER BY vm.nVId DESC");
        if ($typeGroup->num_rows() > 0)
        {
          $typeGroupRow[0] = SELECT;
          foreach ($typeGroup->result() as $key=>$row)
          {
            $locArray = explode(',',$row->vLocation);
            if($division != "" && $location != ""){
              if(in_array($location, $locArray))
                $typeGroupRow[$row->nVId] = $row->vName.' ( '.$row->vCode.' )';
            }else{
              $typeGroupRow[$row->nVId] = $row->vName.' ( '.$row->vCode.' )';
            }
          }//end for each
        }//end if
        else
        {
          $typeGroupRow[0] = NOVALUE;
        }//end else
        break;

        case "A":
          $typeGroup    = $this->db->query("SELECT a.* FROM {PRE}AccountHeadAccounts aca
              LEFT JOIN {PRE}Agent a ON a.nFId = aca.nId WHERE a.vActive = '1'
              AND aca.nId = '".$account."' ORDER BY a.nAId DESC");
        if ($typeGroup->num_rows() > 0)
        {
          $typeGroupRow[0] = SELECT;
          foreach ($typeGroup->result() as $key=>$row)
          {
            $locArray = explode(',',$row->vLocations);
            if($division != "" && $location != ""){
              if(in_array($location, $locArray))
                $typeGroupRow[$row->nAId] = $row->vName.' ( '.$row->vCode.' )';
            }else{
              $typeGroupRow[$row->nAId] = $row->vName.' ( '.$row->vCode.' )';
            }//end if

          }//end for each
        }//end if
        else
        {
          $typeGroupRow[0] = NOVALUE;
        }//end else
        break;

        case "O":
          $typeGroup    = $this->db->query("SELECT o.* FROM {PRE}AccountHeadAccounts aca
              LEFT JOIN {PRE}Other o ON o.nFId = aca.nId WHERE o.vActive = '1'
              AND aca.nId = '".$account."' ORDER BY o.nOId DESC");
        if ($typeGroup->num_rows() > 0)
        {
          $typeGroupRow[0] = SELECT;
          foreach ($typeGroup->result() as $key=>$row)
          {
            $locArray = explode(',',$row->vLocations);
            if($division != "" && $location != ""){
              if(in_array($location, $locArray))
                $typeGroupRow[$row->nOId] = $row->vName.' ( '.$row->vCode.' )';
            }else{
              $typeGroupRow[$row->nOId] = $row->vName.' ( '.$row->vCode.' )';
            }
          }//end for each
        }//end if
        else
        {
          $locArray = explode(',',$row->vLocation);
          if(in_array($location, $locArray))
            $typeGroupRow[0] = NOVALUE;
        }//end else
        break;
        /*
           case "E":
           $typeGroup    = $this->db->query("SELECT e.* FROM {PRE}AccountHeadAccounts aca
           LEFT JOIN {PRE}EmployeeMaster e ON e.nFId = aca.nId WHERE e.vActive = '1'
           AND aca.nId = '".$account."' ORDER BY e.nEId DESC");
           if ($typeGroup->num_rows() > 0)
           {
           $typeGroupRow[0] = SELECT;
           foreach ($typeGroup->result() as $key=>$row)
           {
           $typeGroupRow[$row->nEId] = $row->vName.' ( '.$row->vCode.' )';
           }//end for each
           }//end if
           else
           {
           $typeGroupRow[0] = NOVALUE;
           }//end else
           break;
         */
        case "N":
          $typeGroupRow[0] = NOVALUE;
        break;
      }//end switch

      if(empty($typeGroupRow)) {
		  $typeGroupRow[0] = NOVALUE;}
		  if(!empty($typeGroupRow)){
			  return ($typeGroupRow);
		  }else{
			  $typeGroupRow[0] = NOVALUE;
			  return ($typeGroupRow);
		  }

      
    }//end function

 	//get account head accounts details
	function getAcAccounts($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		//echo $this->db->last_query(); 
		
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

    function getMultipleLocation()
    {
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1'
            AND nId IN ".$this->session->userdata('accessLoc') ." 
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
	
	function getActiveFinance()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId WHERE ah.vActive = '1' 
										  ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
	
	function getActiveFinanceDetails($accId)
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,bd.* FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId LEFT JOIN {PRE}BudgetDetails bd ON bd.nAHAId = aca.nId
										  WHERE ah.vActive = '1' AND aca.nId = '".$accId."' 
											ORDER BY aca.nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
	
	//search finance
	function searchActiveFinance($searchKey,$subLedger,$ledger)
	{
		$typeGroupRow = array();
		if($ledger == 'GL')
		{
			$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode 
												 LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle 
												 	LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) ";
									 		  
			if(trim($searchKey) == 0)
			{
				$extaCondition = '';
			}//end if

			$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca 
											  	 LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 
												 	LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
												WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'N'
													".$extaCondition." ORDER BY aca.nId DESC");
		}//end if
		else
		{
			$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode 
									 		  LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle 
											  	LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) ";
									 		 
			if(trim($searchKey) == 0)
			{
				$extaCondition = '';
			}//end if

			$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca 
								   				  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 
												  		LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
												  WHERE ah.vActive = '1' AND ahc.vSubLedgerType = '".$subLedger."'
														".$extaCondition." ORDER BY aca.nId DESC");
		}//end else
		
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
	
	//get selected finance
	function getSelectedFinance()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nAHAId  FROM {PRE}TravelSetupDetails WHERE nAcId  = '".$this->catId."' 
													AND vActive = '1' ORDER BY nAHAId DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[] = $row->nAHAId;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function 

	//get selected finance flag
	function getSelectedFinanceFlag()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nAHAId,nFlag  FROM {PRE}TravelSetupDetails WHERE nAcId  = '".$this->catId."' 
													AND vActive = '1' ORDER BY nAHAId DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[$row->nAHAId] = $row->nFlag;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
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

    function getActiveCurrency()
    {
        $currGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' AND nId='5'
											ORDER BY nPosition DESC");
        $currGroupRow = array();

        if ($currGroup->num_rows() > 0)
        {
            //$currGroupRow[0] = SELECT;
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

	function getActiveLocations()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
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
	
	function getLedgerOptions()
	{
		//$ledgerGroupRow = array("0" => SELECT, "GL" => "General Ledger", "SL" => "Sub Ledger");
		$ledgerGroupRow = array("GL" => "General Ledger", "SL" => "Sub Ledger");
		return ($ledgerGroupRow);
	}//end function
	
	function getFirstSubLedgerValue()
	{
		$subledgerGroupRow = array("0" => NOVALUE);
		return ($subledgerGroupRow);
	}//end function

    function getSubLedgerValues()
	{
		$subledgerGroupRow = array("0" => SELECT, "C" => "Customer",  "V" => "Vendor", "A" => "Agent", "O" => "Other");
		return ($subledgerGroupRow);
	}//end function
	
	function getActiveMode()
	{
		$modeGroupRow = array("0" => SELECT, "Cr" => "Credit", "Dr" => "Debit");
		return ($modeGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($openid)
	{
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}OpeningBalance WHERE nOBId='".$openid."'");
        $row        = $viewAccessList->row();

        $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");
        $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");
        $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';
        $status     = ($row->vActive == '1')?'Yes':'No';

        switch($row->vLedger)
        {
            case "SL" :
                $ledger	= 'Sub Ledger';
            break;

            case "GL" :
                $ledger	= 'General Ledger';
            break;
        }//end switch

        switch($row->vSubLedger)
        {
            case "C" :
                $subLedger	= 'Customer';
                break;

/*            case "E" :
                $subLedger	= 'Employee';
                break;
*/
            case "V" :
                $subLedger	= 'Vendor';
                break;

            case "A" :
                $subLedger	= 'Agent';
                break;

            case "O" :
                $subLedger	= 'Other';
                break;
        }//end switch

        $content  = '<table class="reportForm">
						<tr>
							<td><strong>'.LEDGERNAME.'</strong> :&nbsp;&nbsp;'.$ledger.'</td>
							<td colspan="3"><strong>'.SUBLEDGERNAME.'</strong> :&nbsp;&nbsp;'.$subLedger.'</td>
							<td colspan="3"><strong>'.DIVISIONNAME.'</strong> :&nbsp;&nbsp;'.$divName.'</td>
							<td colspan="3"><strong>'.LOCATIONNAME.'</strong> :&nbsp;&nbsp;'.$locName.'</strong></td>
						</tr>

						<tr>
							<td><strong>'.CUSTDATE.'</strong> :&nbsp;&nbsp;'.$dDate.'</strong></td>
						    <td colspan="2"><strong>'.YEARREF.'</strong> :&nbsp;&nbsp;'.$row->vYear.'</strong></td>
							<td colspan="2"><strong>'.ACTIVE.'</strong> :&nbsp;&nbsp;'.$status.'</td>
						    <td colspan="6">&nbsp;&nbsp;</td>
						</tr>

						<tr>
						    <td><strong>'.LOCATIONNAME.'</strong></td>
							<td><strong>'.ACCCODENAME.'</strong></td>
							<td><strong>'.SUBCODE.'</strong></td>
							<td><strong>'.CURRNAME.'</strong></td>
							<td><strong>'.EXCHANGERATE.'</strong></td>
							<td><strong>'.BALANCEDC.'</strong></td>
							<td><strong>'.BALANCEFC.'</strong></td>
							<td><strong>'.BUDGETMODE.'</strong></td>
							<td><strong>'.BALANCECREDIT.'</strong></td>
							<td><strong>'.BALANCEDEBIT.'</strong></td>
						</tr>';

        $viewAccessList1  = $this->db->query("SELECT * FROM {PRE}OpeningBalanceDetails WHERE nBalId='".$openid."' ORDER BY nId DESC");

        foreach ($viewAccessList1->result() as $key=>$row1)
        {
            switch($row1->vMode)
            {
                case "Cr" :
                    $modeofPay1	= 'Credit';
                    break;

                case "Dr" :
                    $modeofPay1	= 'Debit';
                    break;
            }//end switch

            $subledgerQuery = $this->db->query("SELECT ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										     ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE aca.nId='".$row1->vACode."' ORDER BY ahc.nId DESC");
            $subledgerResult    = $subledgerQuery->row();
            $this->txtsubLedger	= $subledgerResult->vSubLedgerType;

            switch($this->txtsubLedger)
            {
                case "C":
                    $subAcc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row1->vAcSubCode."'");
                    break;

                case "V":
                    $subAcc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row1->vAcSubCode."'");
                    break;

                case "A":
                    $subAcc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row1->vAcSubCode."'");
                    break;

                case "O":
                    $subAcc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row1->vAcSubCode."'");
                    break;
/*
                case "E":
                    $subAcc = $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$row1->vAcSubCode."'");
                    break;
*/
                case "N":
                    $subAcc = '';
                    break;
            }//end switch

            $content .= '<tr>
				<td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ").'</td>
				<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'").'</td>
				<td>'.$subAcc.'</td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ").'</td>
				<td>'.$row1->vRate.'</td>
				<td>'.$row1->vBalDC.'</td>
				<td>'.$row1->vBalFC.'</td>
				<td>'.$modeofPay1.'</td>
				<td>'.$row1->vBalCredit.'</td>
				<td>'.$row1->vBalDebit.'</td>
			</tr>';
        }//end foreach
        $content .= '<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><strong>'.TOTALAMOUNT.'</strong></td>
				<td>'.$row->vTotalDC.'</td>
				<td>'.$row->vTotalFC.'</td>
				<td></td>
				<td>'.$row->nTotalC.'</td>
				<td>'.$row->nTotalD.'</td>
			</tr>';
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

	function genAcDlld()
	{
    $division = $this->input->post('ddlDivision');
    $location = $this->input->post('ddlLocation');

		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType, ahc.vDivision, ahc.vLocation
                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                                              WHERE ah.vActive = '1' ORDER BY aca.vTitle");
    if ($typeGroup->num_rows() > 0)
    {
      $typeGroupRow[0] = SELECT;
      foreach ($typeGroup->result() as $key=>$row)
      {
        if($division != "" && $location != ""){
          if(($row->vDivision == 0 && $row->vLocation == 0) || ($row->vDivision == 0 && $row->vLocation == $location) || ($row->vDivision == $division &&  $row->vLocation == 0 ) ||  ($row->vDivision == $division &&  $row->vLocation == $location))
            $typeGroupRow[$row->ahaId] = $row->vTitle;
        }else{
          $typeGroupRow[$row->ahaId] = $row->vTitle;
        } //end if
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function

    //generate ddl
    function genSubAcDlld($ddlSubLedger)
    {
        $typeGroupRow = array();
        switch($ddlSubLedger)
        {
            case "C" :
                $typeGroup       = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND (ahc.vSubLedgerType = 'C')
										  ORDER BY nId DESC");
            break;
/*
            case "E" :
                $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND (ahc.vSubLedgerType = 'E')
										  ORDER BY nId DESC");
            break;
*/
            case "V" :
                $typeGroup       = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND (ahc.vSubLedgerType = 'V')
										  ORDER BY nId DESC");
            break;

            case "A" :
                $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND (ahc.vSubLedgerType = 'A')
										  ORDER BY nId DESC");
            break;
            case "O" :
                $typeGroup       = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND (ahc.vSubLedgerType = 'O')
										  ORDER BY nId DESC");
            break;
        }//end switch
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

   	//generate ddl
	function genAcDll($newCode)
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND ahc.vSubLedgerType = '".$newCode."' ORDER BY nId DESC");
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
	
	//fucntion to delete sub and detaisl from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}OpeningBalanceDetails', array('nId' => $ID));
	}//end function

	function getActiveSubledgerAccount()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
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

    function getRate($cur)
    {
        $fetchTitle 	  = $this->db->query("SELECT vRate FROM {PRE}Currency WHERE nId = '".$cur."'");
        $showVal	      = $fetchTitle->row();
        $chkRate		  = $showVal->vRate;
        return $chkRate;
    }//end function
	
}//end class
?>
