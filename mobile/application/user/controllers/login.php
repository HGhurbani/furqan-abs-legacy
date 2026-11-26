<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
	}

	function index()
	{
		$this->load->helper('url');
		$this->load->helper('cookie');
		//site Name
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtUsername', 'Username', 'trim|required|callback__check_login');
		$this->form_validation->set_rules('txtPassword', 'Password', 'required');
		$this->vPass = '';
		
		$data['list_Lang'] = array('eng'=>'English','ar'=>'Arabic');
        $this->ddlLanguage = 'eng';
		
		if($this->input->cookie('AdminPassword')!='')
		{
			$this->vPass  = $this->input->cookie('AdminPassword');
		}//end if
		
		if($this->input->cookie('AdminUser')!='')
		{
			$this->vAdmin = $this->input->cookie('AdminUser');
		}//end if
		else
		{
			$this->vAdmin = $this->input->post('txtUsername');
		}//end else
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top1',$data);
			$this->load->view('login/login_form');
			$this->load->view('general/template_bottom1');
		}//end if
		
		else
		{
		    $this->login_model->loginCheck();
		}//end else
		
	}//end function
	
	function changeLang()
	{
		$this->load->helper('url');
		$this->load->helper('cookie');
		//site Name
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtUsername', 'Username', 'trim|required|callback__check_login');
		$this->form_validation->set_rules('txtPassword', 'Password', 'required');
		$this->vPass = '';
		
		$data['list_Lang'] = array('eng'=>'English','ar'=>'Arabic');
        $this->ddlLanguage = $this->input->post('ddlLanguage');

		$this->common_model->langFolder($this->ddlLanguage);
		//$this->load->library('session');
		//$newdata = array('lang'		=> $this->input->post('ddlLanguage'));
		//$this->session->set_userdata($newdata);
		$this->load->view('login/login_form2',$data);
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>