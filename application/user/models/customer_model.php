<?php
class Customer_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*********************Customer-Subledger Master******************************/

	function getAllCustomer()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlCustomerQuery   	= "SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC
									LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlCustomerQuery;	
		$adminGroupList 		= $this->db->query($sqlCustomerQuery);
        
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
	function getAllCustomerCount()
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
		return ($adminGroupList->num_rows());
	}//end function	

	function getFilterCustomer($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlCustomerFilterQuery = "SELECT * FROM {PRE}CustomerMaster ".$extraCheck." ORDER BY nCId DESC
									LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlCustomerFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlCustomerFilterQuery);
		
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
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca 
											LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId WHERE ah.vActive = '1' 
											AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
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
		$fetchTitle 	  = $this->db->query("SELECT vSubLedgerNo,vSLedgerAuto FROM {PRE}AccountHeadControls WHERE nActHId='".$financeId."'");
		$showVal	      = $fetchTitle->row();
        $code             = $showVal->vSubLedgerNo;
        $auto             = $showVal->vSLedgerAuto;
        if($auto == 1)
        {
            $fetchCode = $this->db->query("SELECT COUNT(nCId) as totCount FROM {PRE}CustomerMaster WHERE nFId='".$financeId."'");
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
        }//end if
        return($countVal);
	}//end function

	function getLocation()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
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
	
	function getParentCustomer()
	{
		$typeGroup    = $this->db->query("SELECT nCId,vCode,vName FROM {PRE}CustomerMaster WHERE vActive = '1'
											ORDER BY nCId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nCId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function
	 
	function getEmployee()
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
	
	function getAgentAccounts()
	{
        $agentaccGroupRow = array();
        $agentaccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
												LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls
										  	  ahc ON ahc.nActHId = aca.nId WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' ORDER BY nId DESC");
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
	
	function getCorresCustomer()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nCId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertCustomer()
	{
		//condition to check whether parent customer exist or not
		switch($this->input->post('ddlParentCust'))
		{
			case "0" :
				$parentCustId	= 0;
			break;
			
			default:
				$parentCustId	= $this->input->post('ddlParentCust');
			break;
		}//end switch

		//insert into Customer master table
	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nFId' => $this->input->post('ddlFinance'),'dDate' => date('Y-m-d'),
						'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
		                'vAddress' => $this->input->post('txtAddress'),'vEmail' => $this->input->post('txtEmail'),
						'vIdType' => $this->input->post('ddlIdType'),'vCivilId' => $this->input->post('txtCivilId'),
						'vPhone' => $this->input->post('txtPhone'),'vMobile' => $this->input->post('txtMobile'),'vFax' => $this->input->post('txtFax'),
						'vLocation' => @join(",",$this->input->post('ddlLocation')),'nCountry' => $this->input->post('ddlCountry'),
						'nCurrency' => $this->input->post('ddlCurrency'),'vServCalc' => $this->input->post('ddlServCalc'),
						'vServCal' => $this->input->post('txtServCalc'),'vDiscCalc' => $this->input->post('ddlDiscCalc'),
						'vDiscCal' => $this->input->post('txtDiscCalc'),'nParentCust' => $parentCustId,
						'nEmpCode' => $this->input->post('ddlEmpCode'),'nAgentAc' => $this->input->post('ddlAgentAc'),
						'vCreditDays' => $this->input->post('txtCreditdays'),'vAmount' => $this->input->post('txtAmount'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}CustomerMaster', $data); 
		
		$msgVal = 'add_suc';
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("customer/editCustomer/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("customer/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateCustomer()
	{
		//condition to check whether parent customer exist or not
		switch($this->input->post('ddlParentCust'))
		{
			case "0" :
				$parentCustId	= 0;
			break;
			
			default:
				$parentCustId	= $this->input->post('ddlParentCust');
			break;
		}//end switch

	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nFId' => $this->input->post('ddlFinance'),'dDate' => date('Y-m-d'),
						'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
		                'vAddress' => $this->input->post('txtAddress'),'vEmail' => $this->input->post('txtEmail'),
						'vIdType' => $this->input->post('ddlIdType'),'vCivilId' => $this->input->post('txtCivilId'),
						'vPhone' => $this->input->post('txtPhone'),'vMobile' => $this->input->post('txtMobile'),'vFax' => $this->input->post('txtFax'),
						'vLocation' => @join(",",$this->input->post('ddlLocation')),'nCountry' => $this->input->post('ddlCountry'),
						'nCurrency' => $this->input->post('ddlCurrency'),'vServCalc' => $this->input->post('ddlServCalc'),
						'vServCal' => $this->input->post('txtServCalc'),'vDiscCalc' => $this->input->post('ddlDiscCalc'),
						'vDiscCal' => $this->input->post('txtDiscCalc'),'nParentCust' => $parentCustId,
						'nEmpCode' => $this->input->post('ddlEmpCode'),'nAgentAc' => $this->input->post('ddlAgentAc'),
						'vCreditDays' => $this->input->post('txtCreditdays'),'vAmount' => $this->input->post('txtAmount'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->where('nCId',$this->input->post('nId'));
		$this->db->update('{PRE}CustomerMaster', $data); 
		
		$msgVal = 'edit_suc';
        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
		    redirect(site_url("customer/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
	
	function deleteCustomer()
	{
		$this->db->delete('{PRE}CustomerMaster', array('nCId' => $this->uri->segment(4))); 
		redirect(site_url("customer/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllcustomer()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}CustomerMaster', $data); 
				}//end foreach
				redirect(site_url("customer/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}CustomerMaster', $data); 
				}//end foreach
				redirect(site_url("customer/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}CustomerMaster', array('nCId' => $val)); 
				}//end foreach
				redirect(site_url("customer/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	//fetching single details
	function showDetails($custId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT *,
												CASE 
													WHEN vServCalc = 'F' THEN 'Fixed'
													ELSE 'Percentage' END as servCalc,
												CASE vDiscCalc
													WHEN vDiscCalc = 'F' THEN 'Fixed'
													ELSE 'Percentage' END as discCalc,
												CASE vIdType
													WHEN  vIdType = 'C' THEN 'Civil Id'
													WHEN  vIdType = 'PASS' THEN 'Passport'
													WHEN  vIdType = 'L' THEN 'Licence'
													ELSE 'Id Card' END as idType
												FROM {PRE}CustomerMaster WHERE nCId ='".$custId."' ORDER BY nCId DESC");
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$locId     = array_filter(@split(',',$this->common_model->get_field_name('CustomerMaster','vLocation'," WHERE nCId='".$custId."'")));
			$locName   = $this->getLocationName($locId);

			$content .= '<table class="reportForm">
			<label><b>'.PARENTCUSTDETAILS.'</strong></b>
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

				<td><strong>'.IDTYPE.' :</strong></td>
				<td>'.$row->idType.'</td>
							
				<td><strong>'.IDDETAILS.' :</strong></td>
				<td>'.$row->vCivilId.'</td>
			</tr>

			<tr>
				<td><strong>'.CREDITDAYS.' :</strong></td>
				<td>'.$row->vCreditDays.'</td>

				<td><strong>'.AMOUNT.' :</strong></td>
				<td colspan="5">'.$row->vAmount.'</td>
			</tr> 
			
			<tr>
				<td><strong>'.CUSTSERVCALC.' :</strong></td>
				<td>'.$row->servCalc.' ('.$row->vServCal.') '.'</td>
				
				<td><strong>'.CUSTDISCCALC.' :</strong></td>
				<td colspan="5">'.$row->discCalc.' ('.$row->vDiscCal.') '.'</td>
			</tr>
			
			<tr>
				<td><strong>'.PARENTCUST.' :</strong></td>
				<td>'.$this->common_model->get_field_name('CustomerMaster','vName', " WHERE nCId='".$row->nParentCust."' ").'( '.$this->common_model->get_field_name('CustomerMaster','vName', " WHERE nCId='".$row->nParentCust."' ").'  )'.'</td>

				<td><strong>'.AGENTAC.' :</strong></td>
				<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle', " WHERE nId='".$row->nAgentAc."'").'</td>
			
				<td><strong>'.EMPLOYEECODE.' :</strong></td>
				<td colspan="5">'.$this->common_model->get_field_name('Employee','vTitle', " WHERE nId='".$row->nEmpCode."' AND vType='DES'").'</td>
			</tr> 
			<tr>
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
			$locName[]   = $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$val."'");
		}//end foreach
		
		return(@join(",",$locName));
	}//end function
	
}//end class
?>