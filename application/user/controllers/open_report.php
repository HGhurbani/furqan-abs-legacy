<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Open_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('open_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = OPENINGBALANCE;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']      = $this->open_report_model->getActiveDivision();
        $data['list_loc_value']      = $this->open_report_model->getActiveLocations();
        $data['list_acc_group']      = $this->open_report_model->getAccountGroup();
        $data['list_acc_value']      = $this->open_report_model->getActiveAcName();
        $data['list_sub_acc']        = $this->open_report_model->getActiveSubAccounts();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtYear       = $this->session->userdata('txtYear');
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
            $this->tableType     = $this->session->userdata('tableType');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtYear');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('open_report/open_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->open_report_model->filterLocation($this->ddlDivision);
		$this->load->view('open_report/show_location',$data);
	}//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->open_report_model->filterAccount($accName);
        $this->load->view('open_report/show_account', $data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
        $this->tableType = $this->session->userdata('tableType');

        $data['list_sub_acc'] = $this->open_report_model->filterSubAccount($accName);
        $this->load->view('open_report/show_sub', $data);
    }//end function

    function getReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtYear');
        $this->session->unset_userdata('ddlAccGroup');
        $this->session->unset_userdata('ddlAccount');
        $this->session->unset_userdata('ddlSubAccount');
        $this->session->unset_userdata('tableType');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->input->post('ddlDivision');
        $this->ddlLocation       = $this->input->post('ddlLocation');
        $this->ddlLocation2      = $this->input->post('ddlLocation2');
        $this->txtYear           = $this->input->post('txtYear');
        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');
        $this->ddlAccount        = $this->input->post('ddlAccount');
        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');
        $this->tableType         = $this->input->post('tableType');

        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));
        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));
        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));
        $this->session->set_userdata('txtYear',$this->input->post('txtYear'));
        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));
        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));
        $this->session->set_userdata('ddlSubAccount',$this->input->post('ddlSubAccount'));
        $this->session->set_userdata('tableType',$this->input->post('tableType'));
        $this->session->set_userdata('pageName',$this->uri->segment(1));


        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND ob.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck .= " AND (ob.vLocation='".$this->ddlLocation."' OR ob.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck .= " AND ob.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck .= " AND ob.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtYear != '') {
            $extraCheck .= " AND YEAR(ob.dDate) = '" .$this->txtYear . "'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND obd.vACode='".$this->ddlAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck .= " AND obd.vAcSubCode='".$this->ddlSubAccount."'";
        }//end if

        $data['list_value'] = $this->open_report_model->getReport($extraCheck);
        $this->load->view('open_report/show_open_report', $data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>