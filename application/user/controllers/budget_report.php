<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Budget_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('budget_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = BUDGETREPORT;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']      = $this->budget_report_model->getActiveDivision();
        $data['list_loc_value']      = $this->budget_report_model->getActiveLocations();
        $data['list_acc_group']      = $this->budget_report_model->getAccountGroup();
        $data['list_acc_value']      = $this->budget_report_model->getActiveAcName();
        $data['list_month']          = $this->budget_report_model->getAllMonths();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtMonth      = $this->session->userdata('txtMonth');
            $this->txtYear       = $this->session->userdata('txtYear');
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtMonth');
            $this->session->unset_userdata('txtYear');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('budget_report/budget_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->budget_report_model->filterLocation($this->ddlDivision);
		$this->load->view('budget_report/show_location',$data);
	}//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->budget_report_model->filterAccount($accName);
        $this->load->view('budget_report/show_account', $data);
    }//end function

    function getActual()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = BUDGETREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtMonth');
            $this->session->unset_userdata('txtYear');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtMonth          = $this->common_model->searchKeyword3('txtMonth');
        $this->txtYear           = $this->common_model->searchKeyword3('txtYear');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND b.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck .= " AND (b.vLocation='".$this->ddlLocation."' OR b.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck .= " AND b.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck .= " AND b.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtMonth != '' && $this->txtMonth >0) {
            $extraCheck .= " AND b.vMonth = '".$this->txtMonth."'";
        }//end if

        if ($this->txtYear != '') {
            $extraCheck .= " AND b.vYear = '".$this->txtYear."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND bd.nAHAId='".$this->ddlAccount."'";
        }//end if

        $data['list_value'] = $this->budget_report_model->getActual($extraCheck);

        $mydata = $this->load->view("budget_report/export_files/actual_report", $data,true);
        $_SESSION['actual_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('budget_report/actual_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('budget_report/actual_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

    function getBudget()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = BUDGETREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtMonth');
            $this->session->unset_userdata('txtYear');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtMonth          = $this->common_model->searchKeyword3('txtMonth');
        $this->txtYear           = $this->common_model->searchKeyword3('txtYear');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND b.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck .= " AND (b.vLocation='".$this->ddlLocation."' OR b.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck .= " AND b.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck .= " AND b.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtMonth != '' && $this->txtMonth >0) {
            $extraCheck .= " AND b.vMonth = '".$this->txtMonth."'";
        }//end if

        if ($this->txtYear != '') {
            $extraCheck .= " AND b.vYear = '".$this->txtYear."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND bd.nAHAId='".$this->ddlAccount."'";
        }//end if

        $data['list_value'] = $this->budget_report_model->getBudget($extraCheck);
        $mydata = $this->load->view("budget_report/export_files/show_budget", $data,true);
        $_SESSION['budget_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('budget_report/show_budget', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('budget_report/show_budget', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
