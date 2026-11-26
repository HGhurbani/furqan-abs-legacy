<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_profile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function profView()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtFName', 'First Name', 'required');
		$this->form_validation->set_rules('txtLName', 'Last Name', 'required');
		$this->form_validation->set_rules('txtEmail', 'Email', 'required|valid_email');

		$this->showTitle  	= ADMINPROFILE;
		$this->btnVal	  	= UPDATEDETAILS;
		$this->adminId	  	= $this->common_model->get_set_sessions();
		$fetchTitle 	  	= $this->db->query("SELECT vUsername,vEmail,vFName,vLName,vActive FROM {PRE}Admin WHERE nId='".$this->adminId."'");
		$showVal	      	= $fetchTitle->row_array();
		$data['list_value'] = $showVal;
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('sub_admin/admin_profile');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//update details
			$this->general_model->updateAdminProfile();
		}//end else	
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>