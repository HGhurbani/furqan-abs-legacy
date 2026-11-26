<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_Logout extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
	}

	function showlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = 'CheckIn / CheckOut Details';
		
		$this->common_model->check_admin_authorization();
		//active teachers
		$data['list_staff_category_value'] = $this->login_model->getTeachers();
		$target         				   = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Check','nId',''), $target,
										'login_logout/showlist/'.$this->uri->segment(3));
		$data['list_value']   			   = $this->login_model->getCheckinDetails();
		
		$this->load->view('general/template_top',$data);
		$this->load->view('staff/login_logout');
		$this->load->view('general/template_bottom');
	}//end function
	
	function filterShowlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle  = 'CheckIn / CheckOut Details';
		$this->common_model->check_admin_authorization();
		
		$data['list_staff_category_value'] = $this->login_model->getTeachers();
		$this->ddlStaff 				   = $this->common_model->searchKeyword3('ddlStaff');
		
		switch($this->common_model->searchKeyword3('ddlStaff'))
		{
			case "0":
				$extraCheck = " ";
			break;
			
			default:
				$extraCheck = " WHERE nUser ='".$this->common_model->searchKeyword3('ddlStaff')."'";
			break;
		}//end switch
		
		if($this->common_model->searchKeyword3('txtFrom') != '')
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword3('txtFrom');
			$txtTo   	= $this->common_model->searchKeyword3('txtTo');
			if($extraCheck =='0' || $extraCheck ==" ")
			{
				//$extraCheck = " WHERE dCheckin >= '".$txtFrom."' AND dCheckout <= '".date('Y-m-d',strtotime($txtTo.' +1 day'))."'";
				$extraCheck = " WHERE dCheckin >= '".$txtFrom."' AND dCheckout <= '".date('Y-m-d',strtotime($txtTo))."'";
			}//end if
			else
			{
				$extraCheck.= " AND dCheckin >= '".$txtFrom."' AND dCheckout <= '".date('Y-m-d',strtotime($txtTo))."'";
			}//end else
		}//end if
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Check','nId', $extraCheck), $target,'login_logout/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->login_model->checkLogin($extraCheck);
		
		$this->load->view('staff/login_logout_filter',$data);
	}//end function
	
	function filterShowlist2()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle  = 'CheckIn / CheckOut Details';
		$this->common_model->check_admin_authorization();
		
		$data['list_staff_category_value'] = $this->login_model->getTeachers();
		$this->ddlStaff 				   = $this->common_model->searchKeyword3('ddlStaff');
		
		switch($this->common_model->searchKeyword3('ddlStaff'))
		{
			case "0":
				$extraCheck = " ";
			break;
			
			default:
				$extraCheck = " WHERE nUser ='".$this->common_model->searchKeyword3('ddlStaff')."'";
			break;
		}//end switch
		
		if($this->common_model->searchKeyword3('txtFrom') != '')
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword3('txtFrom');
			$txtTo   	= $this->common_model->searchKeyword3('txtTo');
			if($extraCheck =='0' || $extraCheck ==" " )
			{
				$extraCheck = " WHERE dCheckin >= '".$txtFrom."' AND dCheckout <= '".date('Y-m-d',strtotime($txtTo))."'";
			}//end if
			else
			{
				$extraCheck.= " AND dCheckin >= '".$txtFrom."' AND dCheckout <= '".date('Y-m-d',strtotime($txtTo))."'";
			}//end else
		}//end if
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Check','nId', $extraCheck), $target,'login_logout/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->login_model->checkLogin($extraCheck);

		$this->load->view('general/template_top',$data);
		$this->load->view('staff/login_logout');
		$this->load->view('general/template_bottom');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>