<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cms extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function editCms()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtDes', 'Content', 'required');
		
		$this->showTitle    = CMS;
		$this->btnVal	    = UPDATE;
		$chkValue           = ($this->input->post('ddlContent')!='')?$this->input->post('ddlContent'):'Home';
		$this->ddlContent   = $chkValue;
		if($this->uri->segment(5) !='')
		{
			$this->ddlContent = $this->uri->segment(5);
		}//end if
		$this->txtDes	    = $this->common_model->LookUpDisplay($this->ddlContent);

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('settings/cms');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			$this->general_model->updateCMS();
		}//end else	
	}//end function
	
	function filterCms()
	{
		$this->load->helper('url');
		
		$this->common_model->check_admin_authorization();
		
		$this->showTitle    = 'Content Management System';
		$this->btnVal	    = 'Update';
		$this->ddlContent   = $this->input->post('ddlContent');
		$this->txtDes	    = $this->common_model->LookUpDisplay($this->ddlContent);

		$this->load->view('settings/cms_filter');
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>