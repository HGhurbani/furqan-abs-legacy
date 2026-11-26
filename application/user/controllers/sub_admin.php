<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_admin extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function subAdminList()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		//site Name
		$this->showTitle  = SUBADMINMANAGEMENT;
		$target = '.';
        $count    = $this->general_model->countSubadmin();
		$this->common_model->_paging($count, $target,'sub_admin/subadminlist/'.$this->uri->segment(3));

		$data['list_value']   = $this->general_model->updateSubadmin();

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sub_admin/subadminlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('sub_admin/sub_admin');
		$this->load->view('general/template_bottom');
	}//end function

	function editSubAdmin()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtUName', 'Username Name', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sub_admin/subadminlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("sub_admin/subadminlist/".$this->uri->segment(3).'/no_access'));
    }

		$chkPass = true;
		if($this->uri->segment(4) == 'e')
		{
            $chkPass = false;
			if($this->input->post('txtPass')!='')
			{
				$chkPass = true;
			}//end if
		}//end if

		if($chkPass == true)
		{
			$this->form_validation->set_rules('txtPass', 'Password', 'required|matches[txtCPass]');
			$this->form_validation->set_rules('txtCPass', 'Confirm Password', 'required');
        }//end if

		$this->form_validation->set_rules('txtFName', 'First Name', 'required');
		$this->form_validation->set_rules('txtLName', 'Last Name', 'required');
		$this->form_validation->set_rules('txtEmail', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');

		//active admin groups
		$data['list_group_value']	= $this->general_model->dropAdminGroup();
		$data['list_loc_value']     = $this->general_model->getLocation();
		$data['list_emp_value']     = $this->general_model->getActiveEmployee();
    

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWSUBADMIN;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtUName	  = $this->input->post('txtUName');
				$this->txtFName	  = $this->input->post('txtFName');
				$this->txtLName	  = $this->input->post('txtLName');
				$this->txtEmail	  = $this->input->post('txtEmail');
				$this->ddlLocation	  = $this->input->post('ddlLocation');
				$this->ddlEmpId         = $this->input->post('ddlEmpId');

				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->adminId	  = '';
				$this->ddlGroup   = '';
			break;

			case "e":
				$this->showTitle  = EDITSUBADMIN;
				$this->btnVal	  = UPDATESUBADMIN;
				$this->adminId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->adminId	  = intval($this->input->post('nId'));
				}//end if

				$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Admin WHERE nId='".$this->adminId."'");
				$showVal	      = $fetchTitle->row();
				$this->txtUName	  = $showVal->vUsername;
				$this->txtFName	  = $showVal->vFName;
				$this->txtLName	  = $showVal->vLName;
				$this->txtEmail	  = $showVal->vEmail;
				$this->ddlGroup   = $showVal->nGroupId;
//				$this->ddlLocation = $showVal->nLocation;
        $this->ddlLocation        = @split(',',$showVal->nLocation);
				$this->ddlEmpId         = $showVal->nEmpId;

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

// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' )
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('sub_admin/edit_sub_admin');
			$this->load->view('general/template_bottom');
		}//end if

		else
		{
			switch(intval($this->input->post('nId')))
			{
                case "0":
					$this->extraCond  = "";
				break;

				default:
					$this->extraCond  = " AND nId!='".$this->input->post('nId')."'";
				break;
			}//end switch

			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vUsername FROM {PRE}Admin WHERE vUsername='".$this->input->post('txtUName')."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = SUBADMINEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('sub_admin/edit_sub_admin');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				$this->general_model->updateSubadminlist();
			}//end else
		}//end else
	}//end function

	function delsubadmin()
	{
        $this->load->helper(array('form', 'url'));
		$this->showTitle = 'Sub Admin Management';
		$this->common_model->check_admin_authorization();
		$this->general_model->deleteSubadmin();
	}//end function

	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = 'Sub Admin Management';
		$this->common_model->check_admin_authorization();
		$this->general_model->deleteAllSubadmin();
	}//end function

	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
                $extraCond = '';
            break;

			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;

		}//end switch
		$this->txtTitle 		= $this->input->post('txtUName');
		$txtTitle       	    = $this->common_model->get_field_name('Admin','vUsername'," WHERE vUsername='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
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
