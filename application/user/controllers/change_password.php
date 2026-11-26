<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Change_password extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function changePass()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		$data['title'] 	   = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');


		$this->form_validation->set_rules('txtOldPass', 'Old Password', 'required');
		$this->form_validation->set_rules('txtPass', 'Password', 'required|matches[txtCPass]');
		$this->form_validation->set_rules('txtCPass', 'Confirm Password', 'required');

		$this->showTitle  = CHANGEPASSWORD;
		$this->btnVal	  = CHANGEPASSWORD;
		$this->adminId	  = $this->common_model->get_set_sessions();
        $this->txtUser    = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->adminId."'");
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('sub_admin/change_password');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			$this->general_model->changPass();
		}//end else	
	}//end function
	
    function checkPass()
	{
		$this->adminId	    = $this->common_model->get_set_sessions();
	    $this->txtOldPass 	= md5($this->input->post('txtOldPass'));
		$password 		    = $this->db->query("SELECT vPass FROM {PRE}Admin WHERE 
											     nId='".$this->adminId."'");
		$rowName 			= $password->row();
		$password 		    = $rowName->vPass;							 
 
		
		if($password   == $this->txtOldPass)
		{
			echo "true";
		}//end if
		else 
		{ 
			echo "false";
		}//end else
	
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>