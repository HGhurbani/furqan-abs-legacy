<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chart_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('chart_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CHARTOFACCOUNT;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

    $data['list_div_value'] = $this->chart_report_model->getActiveDivision();
    $data['list_loc_value'] = $this->chart_report_model->getActiveLocation();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('chart_report/chart_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->chart_report_model->filterLocation($this->ddlDivision);
		$this->load->view('chart_report/show_location',$data);
	}//end function

    function getAccountGroup()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CHARTOFACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND aha.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND aha.nLocation='".$this->ddlLocation."'";
        }//end if

        $data['list_value'] = $this->chart_report_model->getAccountGroup();
        //$mydata = $this->load->view("chart_report/export_files/account_group_report", $data,true);
        //$_SESSION['chart_account_group'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('chart_report/account_group_report',$data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('chart_report/account_group_report',$data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAccountHead()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CHARTOFACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND aha.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND aha.nLocation='".$this->ddlLocation."'";
        }//end if

        $data['list_value'] = $this->chart_report_model->getAccountHead($extraCheck);
        //$mydata = $this->load->view("chart_report/export_files/account_head_report", $data,true);
        //$_SESSION['chart_account_head'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('chart_report/account_head_report',$data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('chart_report/account_head_report',$data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAccountHeadGroup()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CHARTOFACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " AND aha.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND aha.nLocation='".$this->ddlLocation."'";
        }//end if

        $data['list_value'] = $this->chart_report_model->getAccountHeadGroup();

        //$mydata = $this->load->view("chart_report/export_files/account_head_group", $data,true);
        //$_SESSION['account_head_group'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('chart_report/account_head_group',$data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('chart_report/account_head_group',$data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
