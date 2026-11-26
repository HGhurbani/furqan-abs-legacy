<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site_status extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function editStatus()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		//site Name
		 
		$data['title'] 	    = $this->common_model->get_site_title(); 
		$this->showTitle    = SITESTATUS;
		$this->txtStatus    = $this->common_model->LookUpDisplay('Site Status');
		
		switch($this->txtStatus)
		{
			case "1":
				$this->showTitle   = SITELIVE;
				$this->Color       = "#319A31";
				$this->showLink    = '<a href="'.base_url().'index.php/site_status/editStatus/'.$this->uri->segment(3).'/e/0"><u>'.CLICKHERE.'</u></a> '.TOMAKE;
			break;
			
			case "0":
				$this->showTitle   = SITEDOWN;
				$this->Color       = "#FF3031";
				$this->showLink    = '<a href="'.base_url().'index.php/site_status/editStatus/'.$this->uri->segment(3).'/e/1"><u>'.CLICKHERE.'</u></a> '.TOMAKELIVE;
			break;
		}//end switch
		
		$this->general_model->updateSiteStatus();
		
		$this->load->view('general/template_top',$data);
		$this->load->view('settings/site_status');
		$this->load->view('general/template_bottom');
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>