<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edit_Profile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('profile_model');
	}

	function editprofile()
	{	
		$this->common_model->check_user_authorization();
		$this->common_model->siteStatus();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->btnVal	   = 'Edit Profile';
		$this->showTitle   = "Edit Profile";
		switch($this->session->userdata('userType'))
		{
			case "Student":
				$this->_TableName  = 'Student';
				$this->_extraCond  = '';
				$this->_extraField = 'vFName' ;
			break;
			
			case "Employee":
				$this->_TableName  = 'Teacher';
				$this->_extraCond  = '';
				$this->_extraField = 'vFName';
			break;
		}//end switch
		
		$data['list_group_value'] = $this->profile_model->getActiveCountry();//active countries
		$data['list_state_value'] = $this->profile_model->getActiveState();//active states
		
		$this->ddlCountry  		  = $this->common_model->get_field_name('Country','nId'," WHERE vActive = '1' AND nParentId = '0' LIMIT 0,1");
		$this->ddlState     	  = $this->common_model->get_field_name('Country','nId'," WHERE vActive = '1' AND nParentId = '".$this->ddlCountry."' LIMIT 0,1");
		$fetchTitle  			  = $this->db->query("SELECT * FROM {PRE}".$this->_TableName." WHERE nId='".$this->session->userdata['StudentUser']."' ");
		$showVal	      		  = $fetchTitle->row();
		$this->txtId  			  = $showVal->nId;
		$this->txtFName	    	  = $showVal->vFName;
		$this->txtLName     	  = $showVal->vLName;
		$this->txtEmail     	  = $showVal->vEmail;
		$this->txtPhone    		  = $showVal->vPhone;
		$this->txtMobile    	  = $showVal->vMobile;
		$this->txtAddress   	  = $showVal->vAddress;
		$this->ddlCountry   	  = $showVal->nCountry;
		$this->ddlState           = $showVal->nState;
		
		$this->load->view('general/template_top',$data);
		$this->load->view('profile/edit_profile',$data);
		$this->load->view('general/template_bottom');
	}//end function 
			
	function edit_table()
	{
		$this->common_model->check_user_authorization();
		$this->common_model->siteStatus();
		switch($this->session->userdata('userType'))
		{
			case "Student":
				$this->_TableName  = 'Student';
			break;
			
			case "Employee":
				$this->_TableName  = 'Teacher';
			break;
		}//end switch
		$this->profile_model->insert_temp_profile();
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>