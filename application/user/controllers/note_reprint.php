<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Note_reprint extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('note_reprint_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRIALBALANCE;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->note_reprint_model->getActiveDivision();
        $data['list_loc_value']     = $this->note_reprint_model->getActiveLocations();
        $data['list_acc_value']     = $this->note_reprint_model->getActiveAcName();
        $data['list_sub_acc']       = $this->note_reprint_model->getActiveSubAccounts();
        $data['list_emp']           = $this->note_reprint_model->getActiveEmployees();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromDate   = $this->session->userdata('txtFromDate');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount');
            $this->ddlIssued     = $this->session->userdata('ddlIssued');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlIssued');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('note_reprint/note_reprint',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->note_reprint_model->filterLocation($this->ddlDivision);
		$this->load->view('note_reprint/show_location',$data);
	}//end function

    function getSales()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SALESREPORT;

        $this->common_model->check_admin_authorization();

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND sc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND sc.vLocation='".$this->ddlLocation."'";
        }//end if

        if ($this->txtFromDate !='') {
            $extraCheck .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND sad.nDrAccount='".$this->ddlAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck .= " AND sad.nAcHead='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $issued = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$this->ddlIssued."'");
            $extraCheck 	.= " AND (scd.vEmployee LIKE '".$issued." %' OR scd.vEmployee LIKE '% ".$issued."'
									OR scd.vEmployee LIKE '%".$issued."%') ";
        }//end if

        $data['list_value'] = $this->note_reprint_model->getSales($extraCheck);

        $this->load->view('general/title');
        $this->load->view('note_reprint/sales_report', $data);
    }//end function

    function getRefund()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = REFUNDREPORT;

        $this->common_model->check_admin_authorization();

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND sc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND sc.vLocation='".$this->ddlLocation."'";
        }//end if

        if ($this->txtFromDate !='') {
            $extraCheck .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND sad.nDrAccount='".$this->ddlAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck .= " AND sad.nAcHead='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck 	.= " AND sc.nIssued='".$this->ddlIssued."'";
        }//end if

        $data['list_value'] = $this->note_reprint_model->getRefund($extraCheck);

        $this->load->view('general/title');
        $this->load->view('note_reprint/refund_report', $data);
    }//end function

    function filterSubAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');
        $accType = $this->input->post('tableType');

    $data['list_sub_acc'] = $this->note_reprint_model->filterSubAccount($accName,$accType);
    $this->load->view('note_reprint/show_account',$data);
  }//end function


    function getAllAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_acc_value'] = $this->common_model->getAllAccounts();
      $this->load->view('note_reprint/show_allAccounts',$data);

    }

    function getIssuedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp'] = $this->common_model->getIssuedBy();
      $this->load->view('note_reprint/show_issuedBy',$data);
    }


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
