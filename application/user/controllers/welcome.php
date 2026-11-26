<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('welcome_model');
	}

	function index()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		$this->common_model->getLang();
		$this->load->view('general/template_top2',$data);
		$this->load->view('welcome/welcome');
		$this->load->view('general/template_bottom2');
	}//end function
	
	function showCalendarFilter()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		
		$data['holiday_calendar'] = $this->welcome_model->getHolidays();

		$this->load->view('welcome/cal/holidays_filter',$data);
	}//end function

	function arrangeOrder()
	{
		$this->load->helper('url');
		$this->common_model->check_admin_authorization();
		
		echo $this->input->post('txtOrder');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>