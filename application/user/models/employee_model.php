<?php
class Employee_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Employee Designation********************************/

	function getAllEmployeedes()
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Employee WHERE vType='DES' 
											 ORDER BY nPosition DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function 
	
	function insertEmployeedes()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vCode' => mysql_real_escape_string($this->input->post('txtCode')),'vType' => 'DES',
						'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Employee'));
		$this->db->insert('{PRE}Employee', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("employee_designation/editEmployeeDes/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("employee_designation/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function getCorresEmpDes()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Employee WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateEmployeedes()
	{
		$data 	= array('vTitle' => $this->input->post('txtTitle'),'vCode' => mysql_real_escape_string($this->input->post('txtCode')),
                        'nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Employee', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("employee_designation/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteEmployeedes()
	{
		$this->db->delete('{PRE}Employee', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("employee_designation/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllEmployeedes()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Employee', $data); 
				}//end foreach
				redirect(site_url("employee_designation/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Employee', $data); 
				}//end foreach
				redirect(site_url("employee_designation/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Employee', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("employee_designation/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterEmployeedes($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Employee WHERE vType='DES' ".$extraCheck."
											 ORDER BY nPosition DESC
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
	
	
/*********************Employee Master******************************/

	function getAllEmployee()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlEmpQuery    		= "SELECT * FROM {PRE}EmployeeMaster ORDER BY nEId DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlEmpQuery;	
									
		$adminGroupList 		= $this->db->query($sqlEmpQuery);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function

	//get user role count
	function getAllEmployeeCount()
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}EmployeeMaster ORDER BY nEId DESC");
		return ($adminGroupList->num_rows());
	}//end function
		
	function getFilterEmployee($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlEmpFilterQuery 		= "SELECT * FROM {PRE}EmployeeMaster ".$extraCheck."
									ORDER BY nEId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;

		session_start();
		$_SESSION['sess_query'] = $sqlEmpFilterQuery;	
									
		$adminGroupList 		= $this->db->query($sqlEmpFilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function getActiveFinance()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM                                           {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
										  WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'E' ORDER BY nId DESC");
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
	
	function getSubledgerNo($financeId)
	{
		$fetchTitle 	  = $this->db->query("SELECT ahc.*,em.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}EmployeeMaster em 
												ON em.nFId = ahc.nId WHERE ahc.nActHId='".$financeId."'");
		$showVal	      = $fetchTitle->row();
		$chkCode		  = $this->getAutomaticCode('EmployeeMaster',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nEId');
		return $chkCode;
	}//end function 

	function getCountry()
	{
		$typeGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId='0'
											ORDER BY vName ASC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getCurrency()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1'
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
	
	function getParentEmployee()
	{
		$typeGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
											ORDER BY nEId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function
	 
	function getDesignation()
	{
		$typeGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Employee WHERE vActive = '1' AND vType='DES'
											ORDER BY nId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = (stripslashes($row->vTitle).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getLocation()
	{
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' 
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
	
	function getSection()
	{
		$typeGroupRow    = array("0" => SELECT, "S" => "Sale", "NA" => "Not Applicable");
		return ($typeGroupRow);
	}//end function 
	
	function getActiveDocuments()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}Employee WHERE vActive = '1' AND vType='DOC'
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
	
	//get selected documents
	function getSelectedDocument()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nDocument FROM {PRE}EmployeeDetails WHERE nEMId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nDocument DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[] = $row->nDocument;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function 

	//get selected document number
	function getSelectedDocNumbr()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nDocument,vDocNumber FROM {PRE}EmployeeDetails WHERE nEMId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nDocument DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[$row->nDocument] = $row->vDocNumber;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function
	
	//get selected document due date
	function getSelectedDuedate()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nDocument,dDueDate FROM {PRE}EmployeeDetails WHERE nEMId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nDocument DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[$row->nDocument] = $row->dDueDate;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function 

	function getEmployeeAccounts()
	{
		$agentaccGroupRow = array();
		$agentaccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca 
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 
											    LEFT JOIN {PRE}AccountHeadControls 
										  	  ahc ON ahc.nActHId = aca.nId WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'E' 
											  	ORDER BY nId DESC");
		if ($agentaccGroup->num_rows() > 0)
		{
			$agentaccGroupRow[0] = SELECT;
			foreach ($agentaccGroup->result() as $key=>$row)
			{
				$agentaccGroupRow[$row->ahaId] = $row->vTitle.' ('.$row->vACode.' )';
			}//end for each
		}//end if
		else
		{
			$agentaccGroupRow[0] = NOVALUE;
		}//end else
		return ($agentaccGroupRow);
	}//end function 

	function getEmployeeReceivableAccounts()
	{
		$agentaccGroupRow = array();
		$agentaccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca 
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 
											    LEFT JOIN {PRE}AccountHeadControls 
										  	  ahc ON ahc.nActHId = aca.nId WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'E' 
											  	ORDER BY nId DESC");
		if ($agentaccGroup->num_rows() > 0)
		{
			$agentaccGroupRow[0] = SELECT;
			foreach ($agentaccGroup->result() as $key=>$row)
			{
				$agentaccGroupRow[$row->ahaId] = $row->vTitle.' ('.$row->vACode.' )';
			}//end for each
		}//end if
		else
		{
			$agentaccGroupRow[0] = NOVALUE;
		}//end else
		return ($agentaccGroupRow);
	}//end function 

	function getIdType()
	{
		$typeGroupRow = array("0" => SELECT, "C" => CIVILID, "PASS" => PASSPORT, "L" => LICENCE, "ID" => IDCARD);
		return ($typeGroupRow);
	}//end function 
	
	function getCalculation()
	{
		$typeGroupRow = array("0" => SELECT, "F" => "Fixed", "P" => "Percentage");
		return ($typeGroupRow);
	}//end function 
	
	function getCorresEmployee()
	{
		$fetchTitle 	  = $this->db->query("SELECT em.*,ed.* FROM {PRE}EmployeeMaster em LEFT JOIN {PRE}EmployeeDetails ed
												ON ed.nEMId=em.nEId WHERE em.nEId ='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertEmployee()
	{
		//insert into Customer master table
	    $data   = array('nFId' => $this->input->post('ddlFinance'),'dDate' => date('Y-m-d'),'nUserId' => $this->session->userdata('AbsAdmin'),
						'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
		                'vAddress' => $this->input->post('txtAddress'),'vEmail' => $this->input->post('txtEmail'),
						'vPhone' => $this->input->post('txtPhone'),'vMobile' => $this->input->post('txtMobile'),
						'vFax' => $this->input->post('txtFax'),'vIdType' => $this->input->post('ddlIdType'),
						'vCivilId' => $this->input->post('txtCivilId'),'nDesignation' => $this->input->post('ddlDesignation'),                        'nGroupMgr' => $this->input->post('ddlGroupMgr'),'nEmpRAc' => $this->input->post('ddlEmpRAc'),
						'nCountry' => $this->input->post('ddlCountry'),'nCurrency' => $this->input->post('ddlCurrency'),
						'vServCalc' => $this->input->post('ddlServCalc'),'vServCal' => $this->input->post('txtServCalc'),
						'vDiscCalc' => $this->input->post('ddlDiscCalc'),'vDiscCal' => $this->input->post('txtDiscCalc'),
						'vLocations' => @join(",",$this->input->post('ddlLocation')),'vLocation' => $this->input->post('ddlLocation1'),                        'vGallileoCD' => $this->input->post('txtGallileoCD'),'vAmadeusCD' => $this->input->post('txtAmadeusCD'),                        'vSabre' => $this->input->post('txtSabre'),'vCreditDays' => $this->input->post('txtCreditdays'),
						'vAmount' => $this->input->post('txtAmount'),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}EmployeeMaster', $data); 
		
		$msgVal = 'add_suc';
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("employee/editEmployee/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("employee/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateEmployee()
	{
	    $data   = array('nFId' => $this->input->post('ddlFinance'),'dDate' => date('Y-m-d'),'nUserId' => $this->session->userdata('AbsAdmin'),
						'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
		                'vAddress' => $this->input->post('txtAddress'),'vEmail' => $this->input->post('txtEmail'),
						'vPhone' => $this->input->post('txtPhone'),'vMobile' => $this->input->post('txtMobile'),
						'vFax' => $this->input->post('txtFax'),'vIdType' => $this->input->post('ddlIdType'),
						'vCivilId' => $this->input->post('txtCivilId'),'nDesignation' => $this->input->post('ddlDesignation'),
            'nGroupMgr' => $this->input->post('ddlGroupMgr'),'nEmpRAc' => $this->input->post('ddlEmpRAc'),
						'nCountry' => $this->input->post('ddlCountry'),'nCurrency' => $this->input->post('ddlCurrency'),
						'vServCalc' => $this->input->post('ddlServCalc'),'vServCal' => $this->input->post('txtServCalc'),
						'vDiscCalc' => $this->input->post('ddlDiscCalc'),'vDiscCal' => $this->input->post('txtDiscCalc'),
						'vLocations' => @join(",",$this->input->post('ddlLocation')),'vLocation' => $this->input->post('ddlLocation1'),
            'vGallileoCD' => $this->input->post('txtGallileoCD'),'vAmadeusCD' => $this->input->post('txtAmadeusCD'),
            'vSabre' => $this->input->post('txtSabre'),'vCreditDays' => $this->input->post('txtCreditdays'),
						'vAmount' => $this->input->post('txtAmount'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nEId',$this->input->post('nId'));
		$this->db->update('{PRE}EmployeeMaster', $data); 
		
		$msgVal = 'edit_suc';
        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
		    redirect(site_url("employee/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
	
	function deleteEmployee()
	{
		$this->db->delete('{PRE}EmployeeMaster', array('nEId' => $this->uri->segment(4))); 
		redirect(site_url("employee/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllemployee()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nEId',$val);
					$this->db->update('{PRE}EmployeeMaster', $data); 
				}//end foreach
				redirect(site_url("employee/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nEId',$val);
					$this->db->update('{PRE}EmployeeMaster', $data); 
				}//end foreach
				redirect(site_url("employee/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}EmployeeMaster', array('nEId' => $val)); 
				}//end foreach
				redirect(site_url("employee/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	//fetching single details
	function showDetails($empId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT *,
												CASE 
													WHEN vIdType = 'C' THEN 'Civil Id'
													WHEN vIdType = 'PASS' THEN 'Passport'
													WHEN vIdType = 'L' THEN 'License'
													ELSE 'Id Card' END as idType FROM {PRE}EmployeeMaster 
											 WHERE nEId ='".$empId."' GROUP BY nEId ORDER BY nEId DESC");
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$locId     = array_filter(@split(',',$this->common_model->get_field_name('EmployeeMaster','vLocations'," WHERE nEId='".$empId."'")));
			$locName   = $this->getLocationName($locId);

			$content .= '<table class="reportForm">
			<label><b>'.PARENTEMPDETAILS.'</strong></b>
			<tr>
				<td><strong>'.LOCATIONADDR.' :</strong></td>
				<td>'.$row->vAddress.'</td>

				<td><strong>'.LOCATIONPHONE.' :</strong></td>
				<td>'.$row->vPhone.'</td>
				
				<td><strong>'.CUSTMOBILE.' :</strong></td>
				<td>'.$row->vMobile.'</td>
				
				<td><strong>'.COMPANYFAX.' :</strong></td>
				<td>'.$row->vFax.'</td>
			</tr>
			
			<tr>
				<td><strong>'.COMPANYCOUNTRY.' :</strong></td>
				<td>'.$this->common_model->get_field_name('Country','vName', " WHERE nId='".$row->nCountry."' AND nParentId='0'").'</td>
				
				<td><strong>'.CURRNAME.' :</strong></td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle', " WHERE nId='".$row->nCurrency."'").'</td>

				<td><strong>'.EMPDESNAME.' :</strong></td>
				<td colspan="3">'.$this->common_model->get_field_name('Employee','vTitle', " WHERE nId='".$row->nDesignation."' AND vType='DES'").'</td>
			</tr>
			
			<tr>
				<td><strong>'.IDTYPE.' :</strong></td>
				<td>'.$row->idType.'</td>
							
				<td><strong>'.IDDETAILS.' :</strong></td>
				<td>'.$row->vCivilId.'</td>

				<td><strong>'.CREDITDAYS.' :</strong></td>
				<td>'.$row->vCreditDays.'</td>

				<td><strong>'.AMOUNT.' :</strong></td>
				<td>'.$row->vAmount.'</td>
			</tr>

			<tr>
				<td><strong>'.CUSTSERVCALC.' :</strong></td>
				<td>'.$row->servCalc.' ('.$row->vServCal.') '.'</td>
				
				<td><strong>'.CUSTDISCCALC.' :</strong></td>
				<td colspan="5">'.$row->discCalc.' ('.$row->vDiscCal.') '.'</td>
			</tr>
			
			<tr>
				<td><strong>'.GALLILEOCD.' :</strong></td>
				<td>'.$row->vGallileoCD.'</td>
				
				<td><strong>'.AMADEUSCD.' :</strong></td>
				<td>'.$row->vAmadeusCD.'</td>
				
				<td><strong>'.SABRE.' :</strong></td>
				<td colspan="3">'.$row->vSabre.'</td>
			</tr> 
			
			<tr>
				<td><strong>'.LOCATIONNAME.' :</strong></td>
				<td>'.$row->vLocation.'</td>

				<td><strong>'.EMPLOANAC.' :</strong></td>
				<td colspan="5">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nGroupMgr."'").'</td>
			</tr>
			
			<tr>
				<td><strong>'.EMPRECAC.' :</strong></td>
				<td colspan="5">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nEmpRAc."'").'</td>
			
				<td><strong>'.LOCATIONNAME.' :</strong></td>
				<td colspan="7">'.$locName.'</td>
			</tr> 
		 </table>';
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
	
}//end class
?>