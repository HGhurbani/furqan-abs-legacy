<?php
class Accounthead_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*********************Account Head******************************/
	function getAllAcchead()
	{
		$adminGroupRow          = array();
		$sqlAccheadQuery        = "SELECT ach.*,aca.*,acc.* FROM {PRE}AccountHead ach
									LEFT JOIN {PRE}AccountHeadAccounts aca ON ach.nAId = aca.nHAId 
								   LEFT JOIN {PRE}AccountHeadControls acc ON ach.nAId = acc.nHAId 
 									WHERE ach.vActive = '1' GROUP BY ach.nAId ORDER BY ach.vHCode ASC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlAccheadQuery;	
		$adminGroupList 		= $this->db->query($sqlAccheadQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	//get user role count
	function getAllAccheadCount()
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT ach.*,aca.*,acc.* FROM {PRE}AccountHead ach
												LEFT JOIN {PRE}AccountHeadAccounts aca ON ach.nAId = aca.nHAId 
											LEFT JOIN {PRE}AccountHeadControls acc ON ach.nAId = acc.nHAId 
 												WHERE ach.vActive = '1' GROUP BY ach.nAId ORDER BY ach.vHCode DESC");
			
		return ($adminGroupList->num_rows());
	}//end function	
	
	function getAccgrouping()
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

	function getActiveDivision()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1' 
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = ALL;
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
	
	function getActiveLocation()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = ALL;
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
	
	function getSubsidarySale()
	{
		$typeGroupRow = array("0" => "No","1" => 'Yes',"C" => "Cash A/C", "S" => "Sales", "P" => "P & L Earnings","A" => "Advance",                               "B" => "Bank", "CM" => "Commission");
		return ($typeGroupRow);
	}//end function 
	
	function getSubsidaryLedger()
	{
		$typeGroupRow = array("O" => "Other","S" => 'Sub Ledger');
		return ($typeGroupRow);
	}//end function 
	
	function getSubsidaryType()
	{
		$typeGroupRow = array("N" => "Normal","C" => "Customer","O" => 'Others', "V" => "Vendor", "E" => "Employee", "A" => "Agent");
		return ($typeGroupRow);
	}//end function
	 
	function getLpo()
	{
		$typeGroupRow = array("0" => "No","1" => 'Yes');
		return ($typeGroupRow);
	}//end function

    function getSalesType()
    {
        $typeGroupRow = array("0" => "No","1" => 'Yes');
        return ($typeGroupRow);
    }//end function
	
	function getSubsidaryAuto()
	{
		$typeGroupRow = array("0" => "Manual","1" => 'Auto');
		return ($typeGroupRow);
	}//end function 
	
	function filterAccgroup($maingroup)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent!='0'
											 AND nParent = '".$maingroup."' AND vActive='1' ORDER BY vTitle ASC");
		
		if ($adminGroupList->num_rows() > 0)
		{
			$adminGroupRow[0] = SELECT;
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
	
	function filterLocation($division)
	{
		$adminGroupRow  = array();
		if($division == '' || $division == '0')
		{
			$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive='1' 
												ORDER BY nPosition DESC");
		}//end if
		
		else
		{
			$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' AND vActive='1' 
												ORDER BY nPosition DESC");
		}//end else
		if ($adminGroupList->num_rows() > 0)
		{
			$adminGroupRow[0] = ALL;
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
	
	function getCorresAcchead()
	{
		$fetchTitle 	  = $this->db->query("SELECT ach.*,aca.*,acc.* FROM {PRE}AccountHead ach LEFT JOIN {PRE}AccountHeadAccounts                                                aca ON ach.nAId = aca.nHAId LEFT JOIN {PRE}AccountHeadControls acc 
		                                      ON acc.nHAId = ach.nAId AND acc.nActHId = aca.nId WHERE nAId ='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

  function getCorresAccheadControls(){

    $chkAccCode = array();
    $chkAccTitle = array(); 
    $chkAccID = array();
    $chkAccDivision = array();
    $chkAccLocation = array();
    $chkAllflag = array();
    $chkRefund = array();
    $chkAccSubsale = array();
    $chkAccSubledger = array();
    $chkAccSubtype = array();
    $chkAccSubAuto = array();
    $chkAccSaleType = array();
    $chkAccPrefix = array();
    $chkAccMindigit = array();
    $chkAccSublno = array();
    $chkAccID2 = array();


    $accHeadControlList      	  = $this->db->query("SELECT aca.vACode,aca.vTitle, aca.nId as accheadId,
        acc.vDivision , acc.vLocation, acc.vAllocation, acc.vRefund, acc.vSubSale, acc.vSubLedger,acc.vSubLedgerType,acc.vSLedgerAuto,acc.vSalesType,acc.vPrefix,acc.nMinDigit,acc.vSubLedgerNo,acc.nId as accHeadCtrlId
        FROM {PRE}AccountHead ach LEFT JOIN {PRE}AccountHeadAccounts                                                
        aca ON ach.nAId = aca.nHAId LEFT JOIN {PRE}AccountHeadControls acc
        ON acc.nHAId = ach.nAId AND acc.nActHId = aca.nId WHERE nAId ='".$this->catId."'");

      if ($accHeadControlList->num_rows() > 0)
      {
        foreach ($accHeadControlList->result() as $key=>$row)
        {
          $chkAccCode[]       = $row->vACode;
          $chkAccTitle[]     = $row->vTitle; 
          $chkAccID[]         = $row->accheadId;
          $chkAccDivision[]   = $row->vDivision;
          $chkAccLocation[]   = $row->vLocation;
          $chkAllflag[]       = $row->vAllocation;
          $chkRefund[]        = $row->vRefund;

          $chkAccSubsale[]    = $row->vSubSale;
          $chkAccSubledger[]  = $row->vSubLedger;
          $chkAccSubtype[]    = $row->vSubLedgerType;
          $chkAccSubAuto[]    = $row->vSLedgerAuto;
          $chkAccSaleType[]   = $row->vSalesType;
          $chkAccPrefix[]     = $row->vPrefix;
          $chkAccMindigit[]   = $row->nMinDigit;
          $chkAccSublno[]     = $row->vSubLedgerNo;
          $chkAccID2[]        = $row->accHeadCtrlId;

        }//end for each
      }//end if

    $data['chkAccCode']       =   $chkAccCode;
    $data['chkAccTitle']      =  $chkAccTitle; 
    $data['chkAccID']       =   $chkAccID;
    $data['chkAccDivision']   =  $chkAccDivision;
    $data['chkAccLocation']   =   $chkAccLocation;
    $data['chkAllflag']       =   $chkAllflag;
    $data['chkRefund']        =  $chkRefund;

    $data['chkAccSubsale']    =  $chkAccSubsale;
    $data['chkAccSubledger']  =  $chkAccSubledger;
    $data['chkAccSubtype']    =   $chkAccSubtype;
    $data['chkAccSubAuto']    =  $chkAccSubAuto;
    $data['chkAccSaleType']   =   $chkAccSaleType;
    $data['chkAccPrefix']     =  $chkAccPrefix;
    $data['chkAccMindigit']   =  $chkAccMindigit;
    $data['chkAccSublno']     =  $chkAccSublno;
    $data['chkAccID2']        =  $chkAccID2;
    return ($data);
  }

    function getAllocationFlag()
    {
        $allocationList = array("0" => "No", "1" => "Yes");
        return ($allocationList);
    }//end function

    function getRefundFlag()
    {
        $refundList = array("0" => "No", "1" => "Yes");
        return ($refundList);
    }//end function

	function insertAcchead()
	{
		//insert into Account head master table
	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nAGroup' => $this->input->post('ddlAccgroup'),
                        'vHCode' => $this->input->post('txtClscode'),
		                'vAcname' => $this->input->post('txtAcchead'),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}AccountHead', $data);
		$id	    = $this->db->insert_id();

        $subSale            = (@array_filter($this->input->post('ddlSubsale')) != '')?$this->input->post('ddlSubsale'):@split("[,]",$this->session->userdata('chkAccSubsale'));
        $subLedger          = (@array_filter($this->input->post('ddlSubledger')) != '')?$this->input->post('ddlSubledger'):@split("[,]",$this->session->userdata('chkAccSubledger'));
        $subLedgerType      = (@array_filter($this->input->post('ddlSubtype')) != '')?$this->input->post('ddlSubtype'):@split("[,]",$this->session->userdata('chkAccSubtype'));
        $sLedgerAuto        = (@array_filter($this->input->post('ddlSubAuto')) != '')?$this->input->post('ddlSubAuto'):@split("[,]",$this->session->userdata('chkAccSubAuto'));
        $SalesType          = (@array_filter($this->input->post('ddlSalesType')) != '')?$this->input->post('ddlSalesType'):@split("[,]",$this->session->userdata('chkAccSalesType'));
        $prefix             = (@array_filter($this->input->post('txtPrefix')) != '')?$this->input->post('txtPrefix'):@split("[,]",$this->session->userdata('chkAccPrefix'));
        $minDigit           = (@array_filter($this->input->post('txtMindigit')) != '')?$this->input->post('txtMindigit'):@split("[,]",$this->session->userdata('chkAccMindigit'));
        $subLedgerNumber    = (@array_filter($this->input->post('txtSublno')) != '')?$this->input->post('txtSublno'):@split("[,]",$this->session->userdata('chkAccSublno'));
        $allocationFlag     = (@array_filter($this->input->post('ddlAllflag')) != '')?$this->input->post('ddlAllflag'):@split("[,]",$this->session->userdata('chkAllflag'));
        $RefundFlag         = (@array_filter($this->input->post('ddlRefund')) != '')?$this->input->post('ddlRefund'):@split("[,]",$this->session->userdata('chkRefund'));

		$accCode            = (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):@split("[,]",$this->session->userdata('chkAccCode'));
		$accName            = (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):@split("[,]",$this->session->userdata('chkAccTitle'));
        $division           = (@array_filter($this->input->post('ddlDivision')) != '')?$this->input->post('ddlDivision'):@split("[,]",$this->session->userdata('chkAccDivision'));
        $location           = (@array_filter($this->input->post('ddlLocation')) != '')?$this->input->post('ddlLocation'):@split("[,]",$this->session->userdata('chkAccLocation'));

	    //insert into Account head accounts and Account head controls table
		foreach($accCode as $key => $aitem)
		{
			$data 	= array('nHAId' => $id,'vACode' => $aitem,'vTitle' => $accName[$key]);
			$this->db->insert('{PRE}AccountHeadAccounts', $data);

            $ahaid	= $this->db->insert_id();

            $data1 	= array('nHAId' => $id,'nActHId' => $ahaid,'vSubSale' =>$subSale[$key],'vSalesType' =>$SalesType[$key],
							'vSubLedger' => $subLedger[$key],'vSubLedgerType' => $subLedgerType[$key],
							'vSLedgerAuto' => $sLedgerAuto[$key],'vAllocation' => $allocationFlag[$key],
                            'vRefund' => $RefundFlag[$key],'vDivision' => $division[$key],
                            'vLocation' => $location[$key],'vPrefix' => $prefix[$key],
                            'nMinDigit' => $minDigit[$key],'vSubLedgerNo' => $subLedgerNumber[$key]);
            $this->db->insert('{PRE}AccountHeadControls', $data1);
		}//end foreach

		$msgVal = 'add_suc';

		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("account_head/editAcchead/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("account_head/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateAcchead()
	{
        //update Account head table
        $id	    = $this->input->post('nId');
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nAGroup' => $this->input->post('ddlAccgroup'),
                        'vHCode' => $this->input->post('txtClscode'),
                        'vAcname' => $this->input->post('txtAcchead'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nAId',$id);
		$this->db->update('{PRE}AccountHead', $data);
		
		$accCode            = (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):0;
		$accName            = (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):0;
        $division           = (@array_filter($this->input->post('ddlDivision')) != '')?$this->input->post('ddlDivision'):0;
        $location           = (@array_filter($this->input->post('ddlLocation')) != '')?$this->input->post('ddlLocation'):0;

        $subSale            = (@array_filter($this->input->post('ddlSubsale')) != '')?$this->input->post('ddlSubsale'):0;
        $subLedger          = (@array_filter($this->input->post('ddlSubledger')) != '')?$this->input->post('ddlSubledger'):0;
        $subLedgerType      = (@array_filter($this->input->post('ddlSubtype')) != '')?$this->input->post('ddlSubtype'):0;
        $sLedgerAuto        = (@array_filter($this->input->post('ddlSubAuto')) != '')?$this->input->post('ddlSubAuto'):0;
        $SalesType          = (@array_filter($this->input->post('ddlSalesType')) != '')?$this->input->post('ddlSalesType'):0;
        $prefix             = (@array_filter($this->input->post('txtPrefix')) != '')?$this->input->post('txtPrefix'):0;
        $minDigit           = (@array_filter($this->input->post('txtMindigit')) != '')?$this->input->post('txtMindigit'):0;
        $subLedgerNumber    = (@array_filter($this->input->post('txtSublno')) != '')?$this->input->post('txtSublno'):0;
        $allocationFlag     = (@array_filter($this->input->post('ddlAllflag')) != '')?$this->input->post('ddlAllflag'):@split("[,]",$this->session->userdata('chkAllflag'));
        $RefundFlag         = (@array_filter($this->input->post('ddlRefund')) != '')?$this->input->post('ddlRefund'):@split("[,]",$this->session->userdata('chkRefund'));

        $accId              = $this->input->post('idHAArray');
        $conId              = $this->input->post('idHCArray');
	    //update Account head accounts and Account head controls table
		foreach($accCode as $key => $aitem)
		{
            //checking already exists if yes updated
			if(intval($accId[$key]) != 0)
			{
				$data 	= array('nHAId' => $id,'vACode' => $aitem,'vTitle' => $accName[$key]);
				$this->db->where('nId',$accId[$key]);
				$this->db->update('{PRE}AccountHeadAccounts', $data);

                $data1 	= array('nHAId' => $id,'vSubSale' =>$subSale[$key],'vSalesType' =>$SalesType[$key],
                                'vSubLedger' => $subLedger[$key],'vSubLedgerType' => $subLedgerType[$key],
                                'vSLedgerAuto' => $sLedgerAuto[$key],'vAllocation' => $allocationFlag[$key],
                                'vRefund' => $RefundFlag[$key],'vDivision' => $division[$key],
                                'vLocation' => $location[$key],
                                'vPrefix' => $prefix[$key],'nMinDigit' => $minDigit[$key],
                                'vSubLedgerNo' => $subLedgerNumber[$key]);
                $this->db->where('nId',$conId[$key]);
                $this->db->update('{PRE}AccountHeadControls', $data1);
			}//end if
			else
			{
                //if newinsert into Account head accounts table
                $data 	= array('nHAId' => $id,'vACode' => $aitem,'vTitle' => $accName[$key],'nDivision' => $division[$key], 'nLocation' => $location[$key]);
                $this->db->insert('{PRE}AccountHeadAccounts', $data);

                $ahaid	    = $this->db->insert_id();

                $data1 	= array('nHAId' => $id,'nActHId' => $ahaid,'vSubSale' =>$subSale[$key],'vSubLedger' => $subLedger[$key],
                                'vSubLedgerType' => $subLedgerType[$key],'vSLedgerAuto' => $sLedgerAuto[$key],
                                'vPrefix' => $prefix[$key],'nMinDigit' => $minDigit[$key],'vSalesType' =>$SalesType[$key],
                                'vSubLedgerNo' => $subLedgerNumber[$key]);
                $this->db->insert('{PRE}AccountHeadControls', $data1);
			}//end else
		}//end foreach
		
		$msgVal = 'edit_suc';
		redirect(site_url("account_head/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function
	
	function deleteAcchead()
	{
		$this->db->delete('{PRE}AccountHead', array('nAId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}AccountHeadAccounts', array('nHAId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}AccountHeadControls', array('nHAId' => $this->uri->segment(4))); 

		redirect(site_url("account_head/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllacchead()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nAId',$val);
					$this->db->update('{PRE}AccountHead', $data); 
				}//end foreach
				redirect(site_url("account_head/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nAId',$val);
					$this->db->update('{PRE}AccountHead', $data); 
				}//end foreach
				redirect(site_url("account_head/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}AccountHead', array('nAId' => $val)); 
					$this->db->delete('{PRE}AccountHeadAccounts', array('nHAId' => $val)); 
					$this->db->delete('{PRE}AccountHeadControls', array('nHAId' => $val)); 
				}//end foreach
				redirect(site_url("account_head/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterUseraccess($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT um.*,ur.nId as roleId,up.vTitle as proName,t.vTitle as transName,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}UserModule um
													LEFT JOIN {PRE}User_Module_Access ur ON um.nId = ur.nUAcId 
												LEFT JOIN {PRE}UserProfile up ON um.nProfile = up.nId 
													LEFT JOIN {PRE}Transactions t ON ur.nTrModule = t.nId 
												WHERE t.nParentId = ur.nTrType ".$extraCheck."
													GROUP BY ur.nUAcId ORDER BY um.nId DESC LIMIT ".$this->common_model->_offset." , ".
												$this->common_model->_limit);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function
	
	function getCountAccessfilter($extraCheck)
	{
		$tabCount	= $this->db->query("SELECT um.*,ur.nId as roleId,up.vTitle as proName,t.vTitle as transName,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}UserModule um
													LEFT JOIN {PRE}User_Module_Access ur ON um.nId = ur.nUAcId 
												LEFT JOIN {PRE}UserProfile up ON um.nProfile = up.nId 
													LEFT JOIN {PRE}Transactions t ON ur.nTrModule = t.nId 
												WHERE t.nParentId = ur.nTrType ".$extraCheck."
													GROUP BY ur.nUAcId ORDER BY nId DESC");
		return($tabCount->num_rows());
	}//end function
	
	//Show details as popup
	function showDetails($accid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}AccountHead WHERE nAId = '".$accid."' ORDER BY nAId DESC");

        $accId     = $this->common_model->get_field_name('AccountHead','nAGroup'," WHERE nAId='".$accid."'");
        $accGroup  = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$accId."'");
        $clsCode   = $this->common_model->get_field_name('AccountHead','vHCode'," WHERE nAId='".$accid."'");
        $accHead   = $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId='".$accid."'");

        foreach ($viewAccessList->result() as $key=>$row)
        {
            $content .= '<table class="reportForm">
                <tr>
                    <td colspan="11">
                        <label><b>'.ACCGROUPNAME.' :</b>&nbsp;'.$accGroup.'</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label><b>'.SUBCLSCODE.' :</b>&nbsp;'.$clsCode.'</label>
                    </td>
                    <td  colspan="8">
                        <label><b>'.ACCHEADNAME.' :</b>&nbsp;'.$accHead.'</label>
                    </td>
                </tr>

                <tr>
                    <td colspan="11"><strong>'.ACCNAME.'</strong></td>
                </tr>
                <tr>
                    <td><strong>' . ACCCODENAME . '</strong></td>
                    <td><strong>' . ACCNAME . '</strong></td>
                    <td><strong>' . DIVISIONNAME . '</strong></td>
                    <td><strong>' . LOCATIONNAME . '</strong></td>
                    <td><strong>' . SUBSIDARYSALE . '</strong></td>
                    <td><strong>' . SUBLEDGERNOT . '</strong></td>
                    <td><strong>' . SUBLEDGERTYPE . '</strong></td>
                    <td><strong>' . SUBLEDGERAUTO . '</strong></td>
                    <td><strong>' . TRANSPREFIX . '</strong></td>
                    <td><strong>' . TRANSMINDIGIT . '</strong></td>
                    <td><strong>' . SUBLEDGERNUM . '</strong></td>
                    <td><strong>' . ALLOCATIONFLAG . '</strong></td>
                    <td><strong>' . REFUNDCONTROL . '</strong></td>
                </tr>';
                $getAcDetails=$this->db->query("SELECT aca.*,acc.*,CASE WHEN acc.vSubSale = '1' THEN 'Yes' WHEN acc.vSubSale = '0' THEN 'No'
                                                WHEN acc.vSubSale = 'C' THEN 'Cash A/C' WHEN acc.vSubSale = 'S' THEN 'Sales' WHEN
                                                acc.vSubSale = 'P' THEN 'P & L Earing' WHEN acc.vSubSale = 'A' THEN 'Advance' ELSE 'Bank' END
                                                as SubSale,CASE WHEN acc.vAllocation = '1' THEN 'Yes' ELSE 'No' END as Allocation,CASE WHEN
                                                acc.vRefund = '1' THEN 'Yes' ELSE 'No' END as Refund,
                                                CASE vSLedgerAuto WHEN acc.vSubLedger = 'O' THEN 'Other' ELSE 'Sub Ledger' END
                                                as SubLedger, CASE WHEN acc.vSubLedgerType = 'C' THEN 'Customer' WHEN acc.vSubLedgerType = 'O'
                                                THEN 'Others' WHEN acc.vSubLedgerType = 'V' THEN 'Vendor' WHEN acc.vSubLedgerType = 'E' THEN '
                                                Employee' WHEN acc.vSubLedgerType = 'A' THEN 'Agent' ELSE 'Normal' END as SubLedgerType, CASE
                                                WHEN acc.vSLedgerAuto = '1' THEN 'Yes' ELSE 'No' END as SLedgerAuto FROM {PRE}AccountHeadAccounts
                                                aca LEFT JOIN {PRE}AccountHeadControls acc ON aca.nId = acc.nActHId WHERE acc.nHAId = '".$accid."'");
                foreach ($getAcDetails->result() as $key2 => $row2) :
                    $content .='<tr>
                        <td>'.$row2->vACode.'</td>
                        <td>'.$row2->vTitle.'</td>
                        <td>'.$this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$row2->vDivision."'").'</td>
                        <td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId='".$row2->vLocation."'").'</td>
                        <td>'.$row2->SubSale.'</td>
                        <td>'.$row2->SubLedger.'</td>
                        <td>'.$row2->SubLedgerType.'</td>
                        <td>'.$row2->SLedgerAuto.'</td>
                        <td>'.$row2->vPrefix.'</td>
                        <td>'.$row2->nMinDigit.'</td>
                        <td>'.$row2->vSubLedgerNo.'</td>
                        <td>'.$row2->Allocation.'</td>
                        <td>'.$row2->Refund.'</td>
                    </tr>';
                endforeach;
            $content .=' </table>';
        }//end foreach
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

    function getAcSublno()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSubLedgerNo FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSubLedgerNo;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcSubsale()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSubSale FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSubSale;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcSubledger()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSubLedger FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSubLedger;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcSubtype()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSubLedgerType FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSubLedgerType;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcSubAuto()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSLedgerAuto FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSLedgerAuto;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcSaleType()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vSalesType FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vSalesType;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcPrefix()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vPrefix FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vPrefix;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcMindigit()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nMinDigit FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nMinDigit;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function
	
 	//get account head accounts details
	function getAcAccounts()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vACode;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

 	//get account head control details
	function getAcAccountsTitle()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vTitle;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

    //get Division  details
    function getAcAccountsDivision()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vDivision FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vDivision;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get Location  details
    function getAcAccountsLocation()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vLocation FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vLocation;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

    function getAcAccountsAllocation()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vAllocation FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vAllocation;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    function getAcAccountsRefund()
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT vRefund FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->vRefund;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get id
	function getAcAccountsID()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadAccounts WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

    function getAcAccountsID2()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");

		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if

		return (array_filter($userTransRow));
	}//end function

 	//get account head control details
	function getAcControls1()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vSubSale FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vSubSale;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls2()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vSubLedger FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vSubLedger;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls3()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vSubLedgerType FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vSubLedgerType;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls4()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vLPO FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vLPO;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls5()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vSLedgerAuto FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vSLedgerAuto;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls6()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vPrefix FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vPrefix;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls7()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nMinDigit FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nMinDigit;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControls8()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vSubLedgerNo FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vSubLedgerNo;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	function getAcControlsID()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadControls WHERE nHAId = '".$this->catId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function
	
	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}AccountHeadAccounts', array('nId' => $ID));
		$this->db->delete('{PRE}AccountHeadControls', array('nActHId' => $ID));
	}//end function

	function getFilterList($extraCheck)
	{
		$adminGroupRow          = array();
		$sqlAccheadFilterQuery  = "SELECT aha.vACode,aha.vTitle,ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup,ah.vActive
									FROM {PRE}AccountHead ah LEFT JOIN {PRE}AccountHeadAccounts aha ON ah.nAId = aha.nHAId 
								   LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup ".$extraCheck." 
								    AND ah.vActive = '1' GROUP BY ah.nAId ORDER BY ah.nAId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlAccheadFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlAccheadFilterQuery);

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
		$tabCount = $this->db->query("SELECT aha.vACode,aha.vTitle,ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup,ah.vActive
										FROM {PRE}AccountHead ah LEFT JOIN {PRE}AccountHeadAccounts aha ON ah.nAId = aha.nHAId 
									  LEFT JOIN {PRE}FinanceModule fm ON fm.nId = ah.nAGroup ".$extraCheck." 
									    AND ah.vActive = '1' GROUP BY ah.nAId ORDER BY ah.nAId DESC");
		return ($tabCount->num_rows());
	}//end function

}//end class
?>
