<?php
class General_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
  	function updateSettings()
	{
	   //checking segment
		switch($this->uri->segment(4))
		{
			case "e":
				//update into db
				$data 	= array('vDes' => $this->input->post('txtTitle'));
				$this->db->where('vName','CompanyName');
				$this->db->update('{PRE}LookUp', $data); 
				
				$data 	= array('vDes' => $this->input->post('txtEmail'));
				$this->db->where('vName','Email');
				$this->db->update('{PRE}LookUp', $data); 

				$data 	= array('vDes' => $this->input->post('txtNoPerPage'));
				$this->db->where('vName','PerPage');
				$this->db->update('{PRE}LookUp', $data); 
				
				$msgVal = 'edit_suc';
			break;
		}//end switch
		redirect(site_url("general/editGeneral/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
  
	function updateCMS()
	{
		switch($this->uri->segment(4))
		{
			case "e":
			//update into db
				$data 	= array('vDes' => $this->input->post('txtDes'));
				$this->db->where('vName',$this->input->post('ddlContent'));
				$this->db->update('{PRE}LookUp', $data); 
				$msgVal = 'edit_suc';
			break;
		}//end switch
		
		redirect(site_url("cms/editCms/".$this->uri->segment(3).'/'.$msgVal.'/'.$this->input->post('ddlContent')));
	}//end function
	
	function updateSiteStatus()
	{
		switch($this->uri->segment(4))
		{
			case "e":
				//update into db
				$data 	= array('vDes' => $this->uri->segment(5));
				$this->db->where('vName','Site Status');
				$this->db->update('{PRE}LookUp', $data); 
				$msgVal = 'edit_suc';
				redirect(site_url("site_status/editStatus/".$this->uri->segment(3).'/'.$msgVal));
			break;
		}//end switch
	}//end function 
	
	function changPass()
	{
		$chkOldPass = $this->db->query("SELECT vPass FROM {PRE}Admin WHERE vPass='".md5($this->input->post('txtOldPass'))."' 
										AND nId='".$this->adminId."'");
		if ($chkOldPass->num_rows() > 0)
		{
			//update db values
			$data 	= array('vPass' => md5($this->input->post('txtPass')));
			$this->db->where('nId',$this->adminId);
			$this->db->update('{PRE}Admin', $data);
			$msgVal = 'edit_suc';

			redirect(site_url("change_password/changepass/".$this->uri->segment(3).'/'.$msgVal));
		}//end if
		else
		{
			$data['error_value'] = "Invalid Password.";
			$this->load->view('general/template_top',$data);
			$this->load->view('sub_admin/change_password');
			$this->load->view('general/template_bottom');
		}//end else

	}//end function

	function updateSubadmin()
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT a.*,ag.vGroupTitle FROM {PRE}Admin a LEFT JOIN {PRE}AdminGroups ag ON (a.nGroupId = ag.nGId)
											 WHERE a.nId!='1' ORDER BY a.vUsername ASC
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

	function countSubadmin()
	{
		$tabCount = $this->db->query("SELECT a.*,ag.vGroupTitle FROM {PRE}Admin a LEFT JOIN {PRE}AdminGroups ag ON (a.nGroupId = ag.nGId)
										WHERE a.nId!='1' ORDER BY a.vUsername ASC");
		return ($tabCount->num_rows());
	}//end function

	function dropAdminGroup()
	{
		$adminGroup    = $this->db->query("SELECT nGId,vGroupTitle FROM {PRE}AdminGroups WHERE vActive = '1' and nGId != '1'");
		$adminGroupRow = array();

		if ($adminGroup->num_rows() > 0)
		{
			foreach ($adminGroup->result() as $key=>$row)
			{
				$adminGroupRow[$row->nGId] = $row->vGroupTitle;
			}//end for each
		} //end if

		return ($adminGroupRow);
	}//end function

	function updateSubadminlist()
	{
    switch($this->uri->segment(4))
		{
			case "a":
				//insert into db
				$data 	= array('vUsername' => $this->input->post('txtUName'),'vActive' => $this->input->post('chkActive'),
								'vFName' => $this->input->post('txtFName'),'vLName' => $this->input->post('txtLName'),
								'vEmail' => $this->input->post('txtEmail'),
                'nLocation' => @join(",",$this->input->post('ddlLocation')),
// added 6/04/14
								'nEmpId' => $this->input->post('ddlEmpId'),
								'nGroupId' => $this->input->post('ddlGroup'),'vPass' => md5($this->input->post('txtPass')));
				$this->db->insert('{PRE}Admin', $data);

				$from	  = $this->common_model->get_field_name('Admin','vEmail'," WHERE nGroupId = '1'");
				$fromName = $this->common_model->get_field_name('Admin','vFName'," WHERE nGroupId = '1'");
				$to		  = $this->input->post('txtEmail');
				$toName	  = $this->input->post('txtName');

				$subject  = 'Your Login Details';
				$htmlbody = '<table width="100%"  border="0" cellpadding="10" cellspacing="1" bgcolor="#000000">
									<tr>
									<td width="41%" height="38" bgcolor="#FFFFFF">Username</td>
									<td width="59%" valign="top" bgcolor="#FFFFFF">'.$this->input->post('txtUName').'</td>
									</tr>
									<tr>
									<td height="38" bgcolor="#FFFFFF">Password </td>
									<td valign="top" bgcolor="#FFFFFF">'.$this->input->post('txtPass').'</td>
									</tr>
				              </table>';

				$mailFormat = read_file('./static/email/emailer.html');
				$arrSearch	= array("{SITE_TITLE}", "{SITE_URL}", "{EMAIL_TITLE}","{EMAIL_CONTENT}");
				$arrReplace	= array($this->common_model->get_site_title(), base_url(), 'Dear'.$this->input->post('txtUName'), $htmlbody);
				$htmlbody	= str_replace($arrSearch, $arrReplace,$mailFormat);
				$this->common_model->send_mail($to,$toName,$from,$fromName,$subject,$htmlbody,$html=true);
				$msgVal = 'add_suc';

				if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
				{
					redirect(site_url("sub_admin/editSubAdmin/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
				}//end if
				if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
				{
					redirect(site_url("sub_admin/subAdminList/".$this->uri->segment(3).'/'.$msgVal));
				}//end if
			break;

			case "e":
				if($this->input->post('txtPass')!='')
				{
					$extraField =" , vPass = '".md5($this->input->post('txtPass'))."'";
				}//end if

                if($this->input->post('txtPass')==''):
                    //insert into db
                    $data 	= array('vUsername' => $this->input->post('txtUName'),'vActive' => $this->input->post('chkActive'),
                        'vFName' => $this->input->post('txtFName'),'vLName' => $this->input->post('txtLName'),
                        'nLocation' => @join(",",$this->input->post('ddlLocation')),
                        'vEmail' => $this->input->post('txtEmail'),'nGroupId' => $this->input->post('ddlGroup').$extraField);
                    $this->db->where('nId',$this->input->post('nId'));
                    $this->db->update('{PRE}Admin', $data);
                else:
                    $data 	= array('vUsername' => $this->input->post('txtUName'),'vActive' => $this->input->post('chkActive'),
                        'vFName' => $this->input->post('txtFName'),'vLName' => $this->input->post('txtLName'),
                        'vEmail' => $this->input->post('txtEmail'),
                        'nLocation' => @join(",",$this->input->post('ddlLocation')),

// added 6/04/14
			'nEmpId' => $this->input->post('ddlEmpId'),
                        'nGroupId' => $this->input->post('ddlGroup'),'vPass' => md5($this->input->post('txtPass')));
                    $this->db->where('nId',$this->input->post('nId'));
                    $this->db->update('{PRE}Admin', $data);
                endif;

				$msgVal = 'edit_suc';
				
				redirect(site_url("sub_admin/subAdminlist/".$this->uri->segment(3).'/'.$msgVal));
			break;
		}//end switch
		
	}//end function 
	
	function updateGroupManager()
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT g.nGId,g.vGroupTitle,vActive,count(am.nModule) as Modules FROM {PRE}AdminGroups g 
												LEFT JOIN {PRE}Admin_Module_Access am ON (am.nGroup = g.nGId) WHERE vAccess != 'N' 
											GROUP BY g.nGId ORDER BY g.nGId ASC
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
	
	function countGroupManager()
	{
		$tabCount = $this->db->query("SELECT g.nGId,g.vGroupTitle,vActive,count(am.nModule) as Modules FROM {PRE}AdminGroups g 
										LEFT JOIN {PRE}Admin_Module_Access am ON (am.nGroup = g.nGId) 
									  GROUP BY g.nGId ORDER BY g.nGId ASC");
		return ($tabCount->num_rows());
	}//end function 
	
	function updateAdminGroup()
	{
		switch($this->uri->segment(4))
		{
			case "a":
				//insert into db
				$data 	= array('vGroupTitle' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}AdminGroups', $data); 
				//newly insterted group id
				$gId 	= $this->db->insert_id();
				$msgVal = 'add_suc';
				
				$ddlModuleArr = $this->input->post('ddlModules');
				$ddlAccessArr = $this->input->post('ddlUserAccess');
				//insert into module access table
				foreach ($ddlModuleArr as $key=>$module)
				{
					//insert into db
//				$data = array('nGroup' => $gId,'nModule' => $module,'vAccess' => $ddlAccessArr[$key]);

					$access = 'ddlUserAccess'.$module;
					$data = array('nGroup' => $gId,'nModule' => $module,
										'vAccess' => $this->input->post($access));
					$this->db->insert('{PRE}Admin_Module_Access', $data); 
				}//end foreach

				if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
				{
					redirect(site_url("admin_group/editAdminGroup/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
				}//end if
				if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
				{
					redirect(site_url("admin_group/groupList/".$this->uri->segment(3).'/'.$msgVal));		
				}//end if
			break;
			
			case "e":
				//insert into db
				$data 	= array('vGroupTitle' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
				$this->db->where('nGId',$this->input->post('nGId'));
				$this->db->update('{PRE}AdminGroups', $data);
				//delete existing module
				$this->db->delete('{PRE}Admin_Module_Access', array('nGroup' => $this->input->post('nGId'))); 
				$gId 	= $this->input->post('nGId');
				$msgVal = 'edit_suc';
				
				$ddlModuleArr = $this->input->post('ddlModules');
                $ddlAccessArr = $this->input->post('ddlUserAccess');

				//insert into module access table
                foreach ($ddlModuleArr as $key=>$module)
				{
					//insert into db
          $access = 'ddlUserAccess'.$module;
//					$data = array('nGroup' => $gId,'nModule' => $module,'vAccess' => $ddlAccessArr[$key]);
					$data = array('nGroup' => $gId,'nModule' => $module,
										'vAccess' => $this->input->post($access));
					$this->db->insert('{PRE}Admin_Module_Access', $data);
				}//end foreach

				redirect(site_url("admin_group/groupList/".$this->uri->segment(3).'/'.$msgVal));
			break;
			
		}//end switch
				
		//redirect(site_url("admin_group/groupList/".$this->uri->segment(3).'/'.$msgVal));
	}//end function 
	
	function updateAdminProfile()
	{
		$data 	= array('vEmail' => $this->input->post('txtEmail'),'vActive' => $this->input->post('chkActive'),'vFName' => $this->input->post('txtFName'),
						'vLName' => $this->input->post('txtLName'));
		$this->db->where('nId',$this->adminId);
		$this->db->update('{PRE}Admin', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("admin_profile/profview/".$this->uri->segment(3).'/'.$msgVal));
	}//end function 
	
	function deleteGroupval()
	{
		$this->db->delete('{PRE}AdminGroups', array('nGId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Admin_Module_Access', array('nGroup' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Admin', array('nGroupId' => $this->uri->segment(4))); 
		redirect(site_url("admin_group/groupList/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllGroupval()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nGId',$val);
					$this->db->update('{PRE}AdminGroups', $data); 
				}//end foreach
				redirect(site_url("admin_group/groupList/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nGId',$val);
					$this->db->update('{PRE}AdminGroups', $data); 
				}//end foreach
				redirect(site_url("admin_group/groupList/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}AdminGroups', array('nGId' => $val)); 
					$this->db->delete('{PRE}Admin_Module_Access', array('nGroup' => $val)); 
					$this->db->delete('{PRE}Admin', array('nGroupId' => $val)); 
				}//end foreach
				redirect(site_url("admin_group/groupList/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 

	function deleteSubadmin()
	{
		$this->db->delete('{PRE}Admin', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("sub_admin/subadminlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllSubadmin()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Admin', $data); 
				}//end foreach
				redirect(site_url("sub_admin/subadminlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Admin', $data); 
				}//end foreach
				redirect(site_url("sub_admin/subadminlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Admin', array('nId' => $val));
				}//end foreach
				redirect(site_url("sub_admin/subadminlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	function updatepaymentSettings()
	{
			$data 	= array('vDes' => $this->input->post('txtEmail'));
			$this->db->where('vName','paypalemail');
			$this->db->update('{PRE}LookUp', $data);  
			
			
			$data 	= array('vDes' => $this->input->post('txtToken'));
			$this->db->where('vName','paypalauthtokenid');
			$this->db->update('{PRE}LookUp', $data); 
			
	}//end function
	
	function updatelookup()
	{
		$data 	= array('vDes' => $this->input->post('chkVal'));
		$this->db->where('vName', 'enablepaypal');
		$this->db->update('{PRE}LookUp', $data);
		
		$data 	= array('vDes' => $this->input->post('chkpayVal'));
		$this->db->where('vName', 'enablepaypalsandbox');
		$this->db->update('{PRE}LookUp', $data);				
	}//end function 
	
	function updateHeadlines()
	{
		$data 	= array('vDes' => $this->input->post('txtHome'));
		$this->db->where('vName', 'HomeHeadline');
		$this->db->update('{PRE}LookUp', $data); 

		$data 	= array('vDes' => $this->input->post('txtAbout'));
		$this->db->where('vName', 'AboutHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtManagement'));
		$this->db->where('vName', 'ManagementHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtGallery'));
		$this->db->where('vName', 'GalleryHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtEvents'));
		$this->db->where('vName', 'EventsHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtNews'));
		$this->db->where('vName', 'NewsHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtApply'));
		$this->db->where('vName',  'ApplyHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtContact'));
		$this->db->where('vName',  'ContactHeadline');
		$this->db->update('{PRE}LookUp', $data); 
		
		$data 	= array('vDes' => $this->input->post('txtLogin'));
		$this->db->where('vName',  'LoginHeadline');
		$this->db->update('{PRE}LookUp', $data); 

	}//end function
	
	function updateBanner()
	{
		$data 	= array('vDes' => $this->vImg);
		$this->db->where('vName',  'SubBanner');
		$this->db->update('{PRE}LookUp', $data); 
	}//end function 

// added 6/04/14

    function getActiveEmployee()
    {
	
        $empGroup = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
											ORDER BY vName ASC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0) {
           // $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row) {
                $empGroupRow[$row->nEId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
            
        } //end if
        else {
            $empGroupRow[0] = NOVALUE;
        }//end else
        
        return ($empGroupRow);
    }//end function



	function getLocation()
	{
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' 
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
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

    function getDropUserAccess()
    {
        $typeGroupRow    = array( "E" => "Entry","F" => "Full Access", "U" => "Update", "E" => "Entry", "Q" => "Query" , "N" => "No Access" );
        return ($typeGroupRow);
    }//end function

    function get_list_of_admin_modules($Group = 0)
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
												AND am.nSubParent = '0' AND am.vActive = '1'
										  ORDER BY ".$sTitle);

        if ($adModuleList->num_rows() > 0)
        {
            echo '<table width="100%" cellspacing="1" cellpadding="5" bgcolor="#000000">';
            foreach ($adModuleList->result() as $key=>$row)
            {
				$subModuleList = $this->db->query("SELECT amc.*, am.* FROM {PRE}Admin_Module_Category amc,
											{PRE}Admin_Modules am WHERE am.nCategory = amc.nCId
												AND am.nSubParent = '".$row->nMId."' AND am.vActive = '1'
										  ORDER BY ".$sTitle);
										  
                if ($row->nCId != $categoryId)
                {
                    $bgcolor = $color;
                    echo '<tr bgcolor="#CC9933"><td colspan="2"><strong>'.$row->$sTitle1.'</strong></td></tr>';
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
                <td width="20%" colspan="2">
                    <input type="checkbox" value="<?php echo $row->nMId;?>" name="ddlModules[]" <?php echo $checked;?> class="checkSub1" data-group="c<?php echo $row->nMId;?>">&nbsp;<strong><?php echo $row->$sTitle2; ?></strong>
                </td>
                <td width="80%">
                	<?php if($subModuleList->num_rows() == 0):?>
                    	<?php //echo form_dropdown('ddlUserAccess[]', $this->getDropUserAccess(), $this->common_model->get_field_name('Admin_Module_Access','vAccess'," WHERE nModule='".$row->nMId."' AND nGroup='".$Group."'"),'class="showUserAccess"');?>
                    	<?php echo form_dropdown('ddlUserAccess'.$row->nMId, $this->getDropUserAccess(), $this->common_model->get_field_name('Admin_Module_Access','vAccess'," WHERE nModule='".$row->nMId."' AND nGroup='".$Group."'"),'class="showUserAccess"');?>
                    <?php endif;?>
                </td>
            </tr>
            <?php
				if($subModuleList->num_rows() > 0):
					foreach($subModuleList->result() as $skey => $sval):
			?>
            
            <tr bgcolor="<?php echo $bgcolor;?>">
            	<td>&nbsp;</td>
                <td width="20%">
                    <input type="checkbox" value="<?php echo $sval->nMId;?>" name="ddlModules[]" <?php echo $checked;?> class="checkSub2 c<?php echo $row->nMId;?>" data-group="">&nbsp;<?php echo $sval->$sTitle2; ?>
                </td>
                <td width="80%">
                    <?php // echo form_dropdown('ddlUserAccess[]', $this->getDropUserAccess(), $this->common_model->get_field_name('Admin_Module_Access','vAccess'," WHERE nModule='".$sval->nMId."' AND nGroup='".$Group."'"),'class="showUserAccess"');?>
                    <?php echo form_dropdown('ddlUserAccess'.$sval->nMId, $this->getDropUserAccess(), $this->common_model->get_field_name('Admin_Module_Access','vAccess'," WHERE nModule='".$sval->nMId."' AND nGroup='".$Group."'"),'class="showUserAccess"');?>
                </td>
            </tr>
            <?php
					endforeach;
				
				endif;
			?>
            <?php
                $categoryId = $row->nCId;
            }//end foreach
            echo '</table>';
        }//end if
    }//end function

}//end class
?>
