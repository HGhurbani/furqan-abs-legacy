<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_group extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function groupList()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$count	= $this->general_model->countGroupManager();
		$this->common_model->_paging($count, $target,'admin_group/groupList/'.$this->uri->segment(3));
		
		$data['list_value']   = $this->general_model->updateGroupManager();
		$data['module_total'] = $this->common_model->get_total_num_rows('Admin_Modules','nMId'," WHERE vActive = '1' ");

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
//commented to add user access
//        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->showTitle = ADMINGROUPMANAGEMENT;
		$this->load->view('general/template_top',$data);
		$this->load->view('sub_admin/admin_group');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editAdminGroup()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('txtTitle', 'Group Name', 'required');
		$this->form_validation->set_rules('ddlModules', 'Select atleast one', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'admin_group/groupList');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("admin_group/groupList/".$this->uri->segment(3).'/no_access'));
    }

        $data['list_access']	= $this->general_model->getDropUserAccess();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDGROUP;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->GroupId	  = 0;
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITGROUP;
				$this->btnVal	  = UPDATEGROUP;
				$this->GroupId	  = $this->uri->segment(5);
				if(intval($this->input->post('nGId')) != 0)
				{
					$this->GroupId = intval($this->input->post('nGId'));
				}//end if
				
				$fetchTitle 	  = $this->db->query("SELECT vGroupTitle,vActive FROM {PRE}AdminGroups WHERE nGId='".$this->GroupId."'");
				$showVal	      = $fetchTitle->row();
				$this->txtTitle	  = $showVal->vGroupTitle;
				if($showVal->vActive == '1')
				{
					$this->chkValYes  = 'checked="checked"';
				}//end if 
				else
				{
					$this->chkValNo   = 'checked="checked"';
				}//end else
			break;
		}//end switch
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('sub_admin/edit_group');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nGId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nGId!='".$this->input->post('nGId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vGroupTitle FROM {PRE}AdminGroups WHERE vGroupTitle='".$this->input->post('txtTitle')."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = GROUPEXIST;
				$this->load->view('general/template_top',$data);
                $this->load->view('sub_admin/edit_group');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				$this->general_model->updateAdminGroup();
			}//end else
		}//end else	
	}//end function

	function delgroupval()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = 'Admin Group Management';
		$this->common_model->check_admin_authorization();
		$this->general_model->deleteGroupval();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = 'Admin Group Management';
		$this->common_model->check_admin_authorization();
		$this->general_model->deleteAllGroupval();
	}//end function 

	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nGId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('AdminGroups','vGroupTitle'," WHERE  vGroupTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
		if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
