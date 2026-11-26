<?php
class User_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************User Profile********************************/

	function getAllUserprofile()
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}AdminGroups 
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
	
	function insertUserprofile()
	{
		$data 	= array('vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vActive' => $this->input->post('chkActive'),
						'nPosition' => $this->common_model->maxPos('AdminGroups'));
		$this->db->insert('{PRE}AdminGroups', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("user_profile/editUserprofile/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("user_profile/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function getCorresUserprofile()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}AdminGroups WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateUserprofile()
	{
		$data 	= array('vTitle' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}AdminGroups', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("user_profile/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteUserprofile()
	{
		$this->db->delete('{PRE}AdminGroups', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("user_profile/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllUserprofile()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}AdminGroups', $data); 
				}//end foreach
				redirect(site_url("user_profile/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}AdminGroups', $data); 
				}//end foreach
				redirect(site_url("user_profile/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}AdminGroups', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("user_profile/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterUserprofile($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}AdminGroups ".$extraCheck."
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
	
/*********************User Role******************************/
	function getAllUserrole($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT um.*,count(ur.nModule) as totModules,
													uf.vGroupTitle,uf.nGId as uId,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}Admin_Module_Access ur
													LEFT JOIN {PRE}Admin_Modules um ON um.nMId = ur.nModule 
												LEFT JOIN {PRE}AdminGroups uf ON ur.nGroup = uf.nGId
												".$extraCheck."
													GROUP BY ur.nGroup ORDER BY um.nMId ASC
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

	//get user role count
	function getAllUserRoleCount($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT um.*,count(ur.nModule) as totModules,
													uf.vGroupTitle,uf.nGId as uId,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}Admin_Module_Access ur
													LEFT JOIN {PRE}Admin_Modules um ON um.nMId = ur.nModule 
												LEFT JOIN {PRE}AdminGroups uf ON ur.nGroup = uf.nGId
												".$extraCheck."
													GROUP BY ur.nGroup ORDER BY um.nMId ASC");
			
		return ($adminGroupList->num_rows());
	}//end function	
	
	function getCorresUserrole()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}AdminGroups  WHERE nGId ='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	//get user module access details
	function getSelectedModules()
	{
		$userModuleRow = array();
		$userModule    = $this->db->query("SELECT nModule FROM {PRE}Admin_Module_Access WHERE nGroup = '".$this->catId."'");
		
		if ($userModule->num_rows() > 0)
		{
			foreach ($userModule->result() as $key=>$row)
			{
				$userModuleRow[] = $row->nModule;
			}//end for each
		}//end if
		
		return (array_filter($userModuleRow));
	}//end function
	
	//get user module transaction list access details
	function getSelectedModuleAccess()
	{
		$userModuleAccessRow = array();
		$userModuleAccess    = $this->db->query("SELECT nModule,vAccess FROM {PRE}Admin_Module_Access WHERE nGroup = '".$this->catId."'");
		
		if ($userModuleAccess->num_rows() > 0)
		{
			foreach ($userModuleAccess->result() as $key=>$row)
			{
				$userModuleAccessRow[$row->nModule] = $row->vAccess;
			}//end for each
		}//end if
		
		return (array_filter($userModuleAccessRow));
	}//end function

	//get user module transaction list access details id
	function getSelectedModuleAccessID()
	{
		$userModuleAccessRow = array();
		$userModuleAccess    = $this->db->query("SELECT nGroup FROM {PRE}Admin_Module_Access WHERE nGroup = '".$this->catId."'");
		
		if ($userModuleAccess->num_rows() > 0)
		{
			foreach ($userModuleAccess->result() as $key=>$row)
			{
				$userModuleAccessRow[] = $row->nId;
			}//end for each
		}//end if
		
		return (array_filter($userModuleAccessRow));
	}//end function

	function insertUserrole()
	{
		$chkData =  (@array_filter($this->input->post('chkModule')) != '')?$this->input->post('chkModule'):'';
		
		if(intval($this->input->post('txtUserpro')) == 0)
		{
			//insert into master UserModule
			$data 	       = array('vGroupTitle' => $this->input->post('txtUserpro'),'vActive' => $this->input->post('chkActive'));
			$this->db->insert('{PRE}AdminGroups', $data); 
			$newProfileMID    = $this->db->insert_id();
		}//end if
		else
		{
			$newProfileMID   = $this->input->post('txtUserpro');
		}//end else
		
		for($i=0;$i<count($chkData);$i++)
		{
			//$userAccessVar = 'ddlUserAccess'.$tval;
			$userAccessVar = 'ddlUserAccess'.$chkData[$i];
			$userAccess    = $this->input->post($userAccessVar);
			
			if(trim($chkData[$i]) != '')
			{
				//check data already exists
				$condChk  = " WHERE nGroup = '".$newProfileMID."' AND nModule = '".$chkData[$i]."'";
				$chkVal   = $this->common_model->get_field_name('Admin_Module_Access','nGroup', $condChk);
				
				if(intval($chkVal) == 0)
				{
					$data 	       = array('nModule' => $chkData[$i],
											'vAccess' => $userAccess,'vIPAddress' => $_SERVER['REMOTE_ADDR']);
									
					$this->db->insert('{PRE}Admin_Module_Access', $data); 
				}//end if
			}//end first if
		}//end for loop

		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("user_role/editUserrole/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("user_role/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateUserrole()
	{
		$chkData         =  (@array_filter($this->input->post('chkModule')) != '')?$this->input->post('chkModule'):'';

		$data 	= array('vGroupTitle' => $this->input->post('txtUserpro'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nGId',$this->input->post('nId'));
		$this->db->update('{PRE}AdminGroups', $data);
			
		$newProfileMID = $this->input->post('nId');
		
		for($i=0;$i<count($chkData);$i++)
		{
			$userAccessVar = 'ddlUserAccess'.$chkData[$i];
			$userAccess    = $this->input->post($userAccessVar);
			
			if(trim($chkData[$i]) != '')
			{
				//check data already exists
				$condChk  = " WHERE nGroup = '".$newProfileMID."' AND nModule = '".$chkData[$i]."'";
				$chkVal   = $this->common_model->get_field_name('Admin_Module_Access','nGroup', $condChk);
				
				if(intval($chkVal) == 0)
				{//insert into db
					$data 	       = array('nGroup' => $newProfileMID,'nModule' => $chkData[$i],
											'vAccess' => $userAccess,'vIPAddress' => $_SERVER['REMOTE_ADDR']);
									
					$this->db->insert('{PRE}Admin_Module_Access', $data); 
				}//end if
				else
				{//update into db
					$data 	       = array('nGroup' => $newProfileMID,'nModule' => $chkData[$i],
											'vAccess' => $userAccess,'vIPAddress' => $_SERVER['REMOTE_ADDR']);
					$this->db->where('nGroup',$this->chkUAccessID[$i]);
					$this->db->update('{PRE}Admin_Module_Access', $data);
				}//end else
			}//end first if
		}//end for loop
			
		
		$msgVal = 'edit_suc';
		redirect(site_url("user_role/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function
	
	function deleteUserrole()
	{
		$this->db->delete('{PRE}Admin_Module_Access', array('nUserProfile' => $this->uri->segment(4))); 
		redirect(site_url("user_role/showlist/".$this->uri->segment(3).'/del_suc/'.$transactionId));	
	}//end function 
	
	function deleteAllUserrole()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}UserModule', $data); 
				}//end foreach
				redirect(site_url("user_role/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}UserModule', $data); 
				}//end foreach
				redirect(site_url("user_role/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}UserModule', array('nId' => $val)); 
					$this->db->delete('{PRE}Admin_Module_Access', array('nUAcId' => $val)); 
				}//end foreach
				redirect(site_url("user_role/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getDropUserprofile()
	{
		$typeGroup    = $this->db->query("SELECT nGId,vGroupTitle FROM {PRE}AdminGroups WHERE vActive = '1'  
											 AND nGId != '1' ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nGId] = stripslashes($row->vGroupTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getDropUserAccess()
	{
		$typeGroupRow    = array("0" => SELECT, "F" => "Full Access", "U" => "Update", "E" => "Entry", "Q" => "Query",
									"N" => "No Access");
		return ($typeGroupRow);
	}//end function 
	
	function getDropTypeSearch()
	{
		//active transaction type
		$typeList    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nParentId = '0' 
											ORDER BY nPosition DESC");
		$typeListRow = array();
		
		if ($typeList->num_rows() > 0)
		{
			$typeListRow[0] = ALL;
			
			foreach ($typeList->result() as $key=>$row)
			{
				$typeListRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		
		else
		{
			$typeListRow[0] =NOVALU;
		}//end else
		return ($typeListRow);
	}//end function 
	
	
	function getFilterUseraccess($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT um.*,uf.vGroupTitle,uf.nGId as uId,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}Admin_Module_Access ur
													LEFT JOIN {PRE}Admin_Modules um ON um.nMId = ur.nModule 
												LEFT JOIN {PRE}AdminGroups uf ON ur.nGroup = uf.nGId
												".$extraCheck."
													GROUP BY ur.nGroup ORDER BY um.nMId ASC
												DESC LIMIT ".$this->common_model->_offset." , ".
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
		$tabCount	= $this->db->query("SELECT um.*,uf.vGroupTitle,uf.nGId as uId,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}Admin_Module_Access ur
													LEFT JOIN {PRE}Admin_Modules um ON um.nMId = ur.nModule 
												LEFT JOIN {PRE}AdminGroups uf ON ur.nGroup = uf.nGId
												".$extraCheck."
													GROUP BY ur.nGroup ORDER BY um.nMId ASC
												 ORDER BY nId DESC");
		return($tabCount->num_rows());
	}//end function
	
	//fetching single details
	function showDetails($roleid)
	{
		$viewAccessRow   = array();
													
		$viewAccessList = $this->db->query("SELECT um.*,
													uf.vGroupTitle,uf.nGId as uId,
												CASE 
													WHEN ur.vAccess = 'F' THEN 'Full Access'
												WHEN ur.vAccess = 'U' THEN 'Update Only'
													WHEN ur.vAccess = 'E' THEN 'Entry Only'
												WHEN ur.vAccess = 'Q' THEN 'Query Only'
													ELSE 'No Access' END as Useraccess
												FROM {PRE}Admin_Module_Access ur
													LEFT JOIN {PRE}Admin_Modules um ON um.nMId = ur.nModule 
												LEFT JOIN {PRE}AdminGroups uf ON ur.nGroup = uf.nGId
												WHERE uf.nGId = '".$roleid."'
													ORDER BY um.nMId ASC");

		if ($viewAccessList->num_rows() > 0)
		{
			$content .= '<label><b>'.USERROLENAME.':</b>&nbsp;'.$this->common_model->get_field_name('AdminGroups','vGroupTitle'," WHERE nGId='".$roleid."'").'</label>
					<table class="reportForm">
					<thead>
						<th>'.USERROLEMODULE.'</th>
						<th>'.USERACCESS.'</th>
					</thead>';
			foreach ($viewAccessList->result() as $key=>$row)
			{
				//$viewAccessRow[] = $row;
				$content .= '
					<tr>
						<td>'.$row->vModuleTitle.'</td>
						<td>'.$row->Useraccess.'</td>
					</tr>';
				
			}//end for each
			$content .= '</table>';
		}//end if
		return($content);
	}//end function
 
 	//fetching single details
	function getTotalModuleCount()
	{
		$roleid             = $this->uri->segment(4);
		$adminGroupList     = $this->db->query("SELECT count(nId) as contModules FROM {PRE}Transactions"); 
		$showVal	        = $adminGroupList->row();

		return($showVal->contModules);
	}//end function
	
 	function getAllActiveTransactiontype()
	{
		$typeGroupRow  = array();
		$typeGroupList = $this->db->query("SELECT * FROM {PRE}Transactions WHERE nParentId ='0' AND vActive = '1'
											 ORDER BY nPosition DESC");
			
		if ($typeGroupList->num_rows() > 0)
		{
			foreach ($typeGroupList->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
	
	function getAllActiveTransactionList($typeId)
	{
		$typeGroupRow  = array();
		$typeGroupList = $this->db->query("SELECT * FROM {PRE}Transactions WHERE nParentId !='0' AND vActive = '1'
											 AND nParentId IN (".$typeId.") ORDER BY nPosition DESC");
			
		if ($typeGroupList->num_rows() > 0)
		{
			foreach ($typeGroupList->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function  

	/*------------------Users Management-----------------*/
	
	function getAllMembers()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlUserQuery   	    = "SELECT m.nId as membId,m.vActive as active,up.nGId as roleId,m.*,up.*,
												CASE 
													WHEN m.vType='C' THEN 'Customer' 
												WHEN m.vType='E' THEN 'Employee'
													WHEN m.vType='V' THEN 'Vendor'
												WHEN m.vType='A' THEN 'Agent'
													WHEN m.vType='O' THEN 'Other' END as userType
												FROM {PRE}Members m LEFT JOIN {PRE}AdminGroups up ON m.nUserPro = up.nGId 
											 ORDER BY m.nId DESC
												LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlUserQuery;	
		$adminGroupList 		= $this->db->query($sqlUserQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function getAllUser()
	{		
		//active groups
		$adminGroupRow    = array();
		$adminGroupRow[0] = SELECT;
		return ($adminGroupRow);
	}//end function
	
	function getCorrUser()
	{
		//get user type inserted
		$accessTypeList = $this->db->query("SELECT vType FROM {PRE}Members WHERE nId='".$this->catId."' ORDER BY nId DESC");	
		$getType		= $accessTypeList->row();
		$typeVal		= $getType->vType; 
		
		//get active users corresponding to the type selected
		$adminGroupRow  = array();
		switch($typeVal)
		{
			case "C" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE vActive='1' ORDER BY nCId DESC");
					
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nCId] = $row->vName;
					}//end for each
				} //end if
			break;
			
			case "E" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}EmployeeMaster WHERE vActive='1' ORDER BY nEId DESC");
					
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nEId] = $row->vName;
					}//end for each
				} //end if
			break;
			
			case "V" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive='1' ORDER BY nVId DESC");
					
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nVId] = $row->vName;
					}//end for each
				} //end if
			break;
			
			case "A" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}Agent WHERE vActive='1' ORDER BY nAId DESC");
					
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nAId] = $row->vName;
					}//end for each
				} //end if
			break;
			
			case "O" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}Other WHERE vActive='1' ORDER BY nOId DESC");
					
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nOId] = $row->vName;
					}//end for each
				} //end if
			break;
		}//end switch
		return ($adminGroupRow);
	}//end function
	
	function getCorresUserDetails($user,$type)
	{
		switch($type)
		{
			case "C" :
				$fetchDetails = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nCId ='".$user."'");
			break;
			
			case "E" :
				$fetchDetails = $this->db->query("SELECT * FROM {PRE}EmployeeMaster WHERE nEId ='".$user."'");
			break;

			case "V" :
				$fetchDetails = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nVId ='".$user."'");
			break;

			case "A" :
				$fetchDetails = $this->db->query("SELECT * FROM {PRE}Agent WHERE nAId ='".$user."'");
			break;

			case "O" :
				$fetchDetails = $this->db->query("SELECT * FROM {PRE}Other WHERE nOId ='".$user."'");
			break;
			
		}//end switch
		$showDetails  = $fetchDetails->row();
		return $showDetails;
	}//end function 
	
	function getCorresUsers()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Members WHERE nId='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertUsers()
	{
		$data 	= array('nUserPro' => $this->input->post('txtUserpro'),'vType' => $this->input->post('radType'),
						'nUser' => $this->input->post('ddlUsername'),'vPassword' => md5($this->input->post('txtUPassword')),
						'vLocation' => join(",",$this->input->post('ddlLocation')),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Members', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("users/editUsers/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("users/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateUsers()
	{
		if($this->input->post('txtUPassword')!='')
	    {
			$data 	= array('nUserPro' => $this->input->post('txtUserpro'),'vType' => $this->input->post('radType'),
							'nUser' => $this->input->post('ddlUsername'),'vPassword' => md5($this->input->post('txtUPassword')),
							'vLocation' => join(",",$this->input->post('ddlLocation')),
							'vActive' => $this->input->post('chkActive'));
	    }//end if
		else
		{
			$data 	= array('nUserPro' => $this->input->post('txtUserpro'),'vType' => $this->input->post('radType'),
							'nUser' => $this->input->post('ddlUsername'),'vLocation' => join(",",$this->input->post('ddlLocation')),
							'vActive' => $this->input->post('chkActive'));
		}//end else
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Members', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("users/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function
	
	function deleteUsers()
	{
		$this->db->delete('{PRE}Members', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("users/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllUsers()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Members', $data); 
				}//end foreach
				redirect(site_url("users/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Members', $data); 
				}//end foreach
				redirect(site_url("users/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Members', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("users/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 

	function getAllLocations()
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
	
	function getUsers($user)
	{
		$adminGroupRow  = array();
		switch($user)
		{
			case "C" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nCId] = $row->vName;
					}//end for each
				}//end if
			break;
			
			case "E" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}EmployeeMaster ORDER BY nEId DESC");
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nEId] = $row->vName;
					}//end for each
				}//end if
			break;
			
			case "V" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nVId] = $row->vName;
					}//end for each
				}//end if
			break;
			
			case "A" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nAId] = $row->vName;
					}//end for each
				}//end if
			break;

			case "O" :
				$adminGroupList = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
				if ($adminGroupList->num_rows() > 0)
				{
					$adminGroupRow[0] = SELECT;
					foreach ($adminGroupList->result() as $key=>$row)
					{
						$adminGroupRow[$row->nOId] = $row->vName;
					}//end for each
				}//end if
			break;
		}//end switch
		return ($adminGroupRow);
	}//end function

	function getAllUserType()
	{
		$typeGroupRow    = array("0" => ALL, "A" => "Agent", "E" => "Employee", "C" => "Customer", "V" => "Vendor",
									"O" => "Other");
		return ($typeGroupRow);
	}//end function 

	function getFilterUser($extraCheck,$type)
	{
		switch($type)
		{
			case "0" :
				$this->tableName	= 'Agent';
				$this->userId   	= 'nAId';
			break;
			
			case "A" :
				$this->tableName	= 'Agent';
				$this->userId   	= 'nAId';
			break;

			case "E" :
				$this->tableName	= 'EmployeeMaster';
				$this->userId   	= 'nEId';
			break;
			
			case "C" :
				$this->tableName	= 'CustomerMaster';
				$this->userId   	= 'nCId';
			break;
			
			case "V" :
				$this->tableName	= 'VendorMaster';
				$this->userId   	= 'nVId';
			break;
			
			case "O" :
				$this->tableName	= 'Other';
				$this->userId   	= 'nOId';
			break;
		}//end switch
		$adminGroupRow  		= array();
		$sqlUserFilterQuery   	= "SELECT m.nId as membId,m.vActive as active,m.vType,us.vName,us.vEmail,us.vCode,up.nId as roleId,m.*,up.*,
													CASE 
														WHEN m.vType='C' THEN 'Customer' 
													WHEN m.vType='E' THEN 'Employee'
														WHEN m.vType='V' THEN 'Vendor'
													WHEN m.vType='A' THEN 'Agent'
														WHEN m.vType='O' THEN 'Other' END as userType
													 FROM {PRE}Members m LEFT JOIN {PRE}".$this->tableName." us ON us.".$this->userId." = m.nUser 
													LEFT JOIN {PRE}AdminGroups up ON m.nUserPro = up.nId  ".$extraCheck."
												 ORDER BY m.nId DESC
												LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlUserFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlUserFilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck,$type)
	{
		switch($type)
		{
			case "0" :
				$this->tableName	= 'Agent';
				$this->userId   	= 'nAId';
			break;
			
			case "A" :
				$this->tableName	= 'Agent';
				$this->userId   	= 'nAId';
			break;

			case "E" :
				$this->tableName	= 'EmployeeMaster';
				$this->userId   	= 'nEId';
			break;
			
			case "C" :
				$this->tableName	= 'CustomerMaster';
				$this->userId   	= 'nCId';
			break;
			
			case "V" :
				$this->tableName	= 'VendorMaster';
				$this->userId   	= 'nVId';
			break;
			
			case "O" :
				$this->tableName	= 'Other';
				$this->userId   	= 'nOId';
			break;
		}//end switch

		$tabCount	= $this->db->query("SELECT m.nId as membId,m.vActive as active,m.vType,us.vName,us.vEmail,us.vCode,up.nId as roleId,m.*,up.*,
													CASE 
														WHEN m.vType='C' THEN 'Customer' 
													WHEN m.vType='E' THEN 'Employee'
														WHEN m.vType='V' THEN 'Vendor'
													WHEN m.vType='A' THEN 'Agent'
														WHEN m.vType='O' THEN 'Other' END as userType
													 FROM {PRE}Members m LEFT JOIN {PRE}".$this->tableName." us ON us.".$this->userId." = m.nUser 
													LEFT JOIN {PRE}AdminGroups up ON m.nUserPro = up.nId  ".$extraCheck."
												 ORDER BY m.nId DESC");
		return($tabCount->num_rows());
	}//end function
	
	
	function get_list_of_user_modules($Group = 0)
	{
		// Colors
		$color = '#F6F6F6';
		$moduleArr = array();
		
		if (intval($Group) != 0)
		{
			$adAccess = $this->db->query("SELECT nModule FROM {PRE}Admin_Module_Access WHERE nGroup = '".$Group."'");
			if ($adAccess->num_rows() > 0)
			{
				foreach ($adAccess->result() as $key=>$row)
				{
					$moduleArr[] = $row->nModule;
				}//end for each
			}//end if
		}//end if
		
		//checking lang
		switch($this->session->userdata('lang'))
		{
			case "eng":
				$sTitle  = 'amc.vTitle, am.vModuleTitle';
				$sTitle1 = 'vTitle';
				$sTitle2 = 'vModuleTitle';
			break;
			
			case "ar":
				$sTitle  = 'amc.vTitle2, am.vModuleTitle2';
				$sTitle1 = 'vTitle2';
				$sTitle2 = 'vModuleTitle2';
			break;
		}//end switch
		
		$adModuleList = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
											{PRE}Admin_Modules am WHERE am.nCategory = amc.nCId
										  ORDER BY ".$sTitle);
		
		if ($adModuleList->num_rows() > 0)
		{
			echo '<table width="100%" cellspacing="1" cellpadding="5" bgcolor="#000000">';
			foreach ($adModuleList->result() as $key=>$row)
			{
				if ($row->nCId != $categoryId)
				{
					$bgcolor = $color;
					echo '<tr bgcolor="#CC9933"><td><strong>'.$row->$sTitle1.'</strong></td></tr>';
				}//end if

				if(in_array($row->nMId,$moduleArr)) 
				{ 
					$checked = 'checked'; 
				}//end if
				else 
				{
					$checked = ''; 
				}//end else
				
				if ($row->nMId=='2' || $row->nMId=='3')
						$bgcolor="#FFAD8A"; else $bgcolor="#f0f0f0";
				?>
				<tr bgcolor="<?php echo $bgcolor;?>">
					<td align="left" width="20%">
						<input type="checkbox" value="<?php echo $row->nMId;?>" name="chkModule[]" <?php echo $checked;?>>&nbsp;<?php echo $row->$sTitle2; ?>
					</td>
                    <td align="left" width="80%">
                    	<?php echo form_dropdown('ddlUserAccess'.$row->nMId, $this->getDropUserAccess(), $this->chkUAccess[$row->nMId],'class="showUserAccess"');?>
                   </td>
				</tr>
				<?php
					$categoryId = $row->nCId;	 	
				}//end foreach
			echo '</table>';
		}//end if
	}//end function
	
	function getAllActiveTransactionTypeCat()
	{
		$typeGroupRow  = array();
		$typeGroupList = $this->db->query("SELECT * FROM {PRE}Transactions WHERE vActive = '1'
											 ORDER BY nPosition DESC");
			
		if ($typeGroupList->num_rows() > 0)
		{
			foreach ($typeGroupList->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
}//end class
?>