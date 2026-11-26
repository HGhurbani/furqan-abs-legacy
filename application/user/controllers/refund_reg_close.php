<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refund_reg_close extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('refund_reg_close_model');
    }


	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
// User access aa
	$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');
	//$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
		
		$countCreditVoucher   =  $this->refund_closing_model->getCountRefundClosing();
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllRefundClosing();
		$this->count_rows     = count($data['list_value']);


		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_closing');
		$this->load->view('general/template_bottom');
	}//end function

    function showUnclosed()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

		$countCreditVoucher   =  $this->refund_closing_model->getCountUnclosedRefund();
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showUnclosed/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllUnclosedRefund();
		$this->count_rows     = count($data['list_value']);

        
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_register');
		$this->load->view('general/template_bottom');
	}//end function
	


}//end class

?>
