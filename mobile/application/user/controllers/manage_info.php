<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_info extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('manage_info_model');
		$this->load->model('profloss_report_model');
		$this->load->model('ac_report_model');
	}

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->manage_info_model->getActiveDivision();
        $data['list_loc_value']     = $this->manage_info_model->getActiveLocations();
        $data['list_acc_group']     = $this->manage_info_model->getAccountGroup();
        $data['list_acc_value']     = $this->manage_info_model->getActiveAcName();
        $data['list_sub_acc']       = $this->manage_info_model->getActiveSubAccounts();
        $data['list_emp_value']     = $this->manage_info_model->getActiveEmployee();
        $data['list_vendor']        = $this->manage_info_model->getVendorAccounts();
        $data['list_airline']       = $this->manage_info_model->getAirlineValues();

		$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
		
        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromDate   = $this->session->userdata('txtFromDate');
            $this->txtFromYear   = $this->session->userdata('txtFromYear');
            $this->txtToYear     = $this->session->userdata('txtToYear');
            $this->txtTillDate   = $this->session->userdata('txtTillDate');
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlEmployee   = $this->session->userdata('ddlEmployee');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount');
            $this->ddlAirline    = $this->session->userdata('ddlAirline');
            $this->ddlVendor     = $this->session->userdata('ddlVendor');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('pageName');
        }

        $this->load->view('general/template_mtop');
        $this->load->view('manage_info/manage_info',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $this->ddlLocation   = $this->session->userdata('ddlLocation');
        $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

        $data['list_loc_value'] = $this->manage_info_model->filterLocation($this->ddlDivision);
        $this->load->view('manage_info/show_location',$data);
    }//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->manage_info_model->filterAccount($accName);
        $this->load->view('manage_info/show_account', $data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount');

        $data['list_sub_acc'] = $this->manage_info_model->filterSubAccount($accName);
        $this->load->view('manage_info/show_sub', $data);
    }//end function
 

    function checksScRc(){

       if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if ($this->ddlLocation!=0 && $this->ddlLocation2!=0) {
		//            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
		//            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
		
		$extraCheck_sc .= " AND (  SELECT nId FROM {PRE}Location WHERE sc.vLocation = nId AND vCode  BETWEEN ( select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . "))";
		$extraCheck_rc .= " AND (  SELECT nId FROM {PRE}Location WHERE rc.vLocation = nId AND vCode BETWEEN ( select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . "))";
        }//end if
        else{
            if ($this->ddlLocation!=0) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if ($this->ddlLocation2!=0) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck_sc .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_sc .= " AND YEAR(sc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_sc .= "AND(sad.nAcHead='".$this->ddlAccount."' OR sad.nDrAccount='".$this->ddlAccount."')";
            $extraCheck_rc .= "AND(rcd.nDrAcc='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)){
            $drAccount = @split("-",$this->ddlSubAccount);
            $extraCheck_sc .= " AND sad.nDrAccount='".$drAccount[0]."' AND sad.vTable='".$drAccount[1]."'";
            $extraCheck_rc .= " AND rcd.nDrAcc='".$drAccount[0]."' AND rcd.vTable='".$drAccount[1]."'";
        }


	return array( 'extraCheck_rc' => $extraCheck_rc, 'extraCheck_sc' => $extraCheck_sc); 
    }

    function getSaleSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));


	$extraCheck = $this->checksScRc();

        $adminGroupResults = $this->manage_info_model->getSaleSummaryReport($extraCheck['extraCheck_sc'],$extraCheck['extraCheck_rc']);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
	$data['list_values_details'] = $adminGroupResults['list_values_details'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/sales_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/sales_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAccountSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

     $extra_checks = array();
        $extra_checks = $this->ac_report_model->checks();

        $extraCheck_cr = $extra_checks['extraCheck_cr'];
        $extraCheck_cp = $extra_checks['extraCheck_cp'];
        $extraCheck_bb = $extra_checks['extraCheck_bb'];
        $extraCheck_bp = $extra_checks['extraCheck_bp'];
        $extraCheck_jv = $extra_checks['extraCheck_jv'];
        $extraCheck_cv = $extra_checks['extraCheck_cv'];
        $extraCheck_dv = $extra_checks['extraCheck_dv'];
        $extraCheck_sj = $extra_checks['extraCheck_sj'];
        $extraCheck_ac = $extra_checks['extraCheck_ac'];
        $extraCheck_ad = $extra_checks['extraCheck_ad'];


        $extraCheck_cr_top_acc = $extra_checks['extraCheck_cr_top_acc'];
        $extraCheck_cp_top_acc = $extra_checks['extraCheck_cp_top_acc'];
        $extraCheck_bb_top_acc = $extra_checks['extraCheck_bb_top_acc'];
        $extraCheck_bp_top_acc = $extra_checks['extraCheck_bp_top_acc'];
        $extraCheck_ac_top_acc = $extra_checks['extraCheck_ac_top_acc'];
        $extraCheck_ad_top_acc = $extra_checks['extraCheck_ad_top_acc'];



        $group_select =" SELECT location , COUNT(*) as count,
                             account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit
                             FROM ( ";

        $groupby = "  ) as temp GROUP BY account, subacc ";

        $orderby =  "  ORDER BY account, subacc ASC";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
     
	 
	 
        //$data['list_value'] = $this->manage_info_model->getAccountSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);

		
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/ac_summary_report', $data); 
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/ac_summary_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
	function getUnpostReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));


	if ($this->ddlLocation!=0) {
                $extraCheck_tb .= " AND tb.nLocation='".$this->ddlLocation."'";
                $extraCheck_xo .= " AND xo.nLocation='".$this->ddlLocation."'";
				 $extraCheck_hv .= " AND hv.nLocation='".$this->ddlLocation."'";
               
        }//end if

        $adminGroupResults = $this->manage_info_model->getUnpostReport($extraCheck_tb,$extraCheck_xo,$extraCheck_hv);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
	$data['list_values_details'] = $adminGroupResults['list_values_details'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/unpost_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/unpost_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAccsountSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

     $extra_checks = array();
        $extra_checks = $this->ac_report_model->checks();

        $extraCheck_cr = $extra_checks['extraCheck_cr'];
        $extraCheck_cp = $extra_checks['extraCheck_cp'];
        $extraCheck_bb = $extra_checks['extraCheck_bb'];
        $extraCheck_bp = $extra_checks['extraCheck_bp'];
        $extraCheck_jv = $extra_checks['extraCheck_jv'];
        $extraCheck_cv = $extra_checks['extraCheck_cv'];
        $extraCheck_dv = $extra_checks['extraCheck_dv'];
        $extraCheck_sj = $extra_checks['extraCheck_sj'];
        $extraCheck_ac = $extra_checks['extraCheck_ac'];
        $extraCheck_ad = $extra_checks['extraCheck_ad'];


        $extraCheck_cr_top_acc = $extra_checks['extraCheck_cr_top_acc'];
        $extraCheck_cp_top_acc = $extra_checks['extraCheck_cp_top_acc'];
        $extraCheck_bb_top_acc = $extra_checks['extraCheck_bb_top_acc'];
        $extraCheck_bp_top_acc = $extra_checks['extraCheck_bp_top_acc'];
        $extraCheck_ac_top_acc = $extra_checks['extraCheck_ac_top_acc'];
        $extraCheck_ad_top_acc = $extra_checks['extraCheck_ad_top_acc'];



        $group_select =" SELECT location , COUNT(*) as count,
                             account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit
                             FROM ( ";

        $groupby = "  ) as temp GROUP BY account, subacc ";

        $orderby =  "  ORDER BY account, subacc ASC";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
     
	 
	 
        //$data['list_value'] = $this->manage_info_model->getAccountSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);

		
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/ac_summary_report', $data); 
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/ac_summary_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getCustomerSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_sc .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sc .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sc .= " AND YEAR(sc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {

            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
            $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
            $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
            $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
            $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

            $extraCheck_sc .= "AND(sad.nAcHead='".$this->ddlAccount."' OR sad.nDrAccount='".$this->ddlAccount."')";
            //$extraCheck_rc .= "AND(rcd.nDrAcc='".$this->ddlAccount."')";
			$extraCheck_rc .= "HAVING subacc='".$this->ddlAccount."'";

        }//end if

        if (!empty($this->ddlSubAccount)){
            $drAccount = @split("-",$this->ddlSubAccount);

            $extraCheck_cr .= " AND crd.vACode='".$drAccount[0]."'";
            $extraCheck_cp .= " AND cpd.vACode='".$drAccount[0]."'";
            $extraCheck_bb .= " AND bbd.vACode='".$drAccount[0]."'";
            $extraCheck_bp .= " AND bpd.vACode='".$drAccount[0]."'";
            $extraCheck_jv .= " AND jvd.vACode='".$drAccount[0]."'";
            $extraCheck_cv .= " AND cvd.vACode='".$drAccount[0]."'";
            $extraCheck_dv .= " AND dvd.vACode='".$drAccount[0]."'";
            $extraCheck_ac .= " AND acd.vACode='".$drAccount[0]."'";
            $extraCheck_ad .= " AND ade.vACode='".$drAccount[0]."'";
            $extraCheck_sc .= " AND sad.nDrAccount='".$drAccount[0]."'";
            $extraCheck_rc .= " AND rcd.nDrAcc='".$drAccount[0]."'";
        }

        $data['list_value'] = $this->manage_info_model->getCustomerSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);
	
		
        if (!empty($this->ddlDivision)) {
            $extraCheck_op_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_ad = " AND ad.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_rc = " AND rc.nDivision='".$this->ddlDivision."'";
			$extraCheck_op_op = " AND op.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_op_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
			$extraCheck_op_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
			$extraCheck_op_op .= " AND (op.vLocation='".$this->ddlLocation."' OR op.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_op_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
				 $extraCheck_op_op .= " AND op.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_op_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
				$extraCheck_op_op .= " AND op.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else
			
			
		  $curdate            = date('Y-m-d',strtotime($this->txtFromDate));

        $this->txtToDate_op = date('Y-m-d',strtotime($curdate." -1 day"));
		$this->txtFromDate_op="01-03-2016";
			

        if ($this->txtFromDate_op !='') {
            $extraCheck_op_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_sc .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
            $extraCheck_op_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate_op))."' ";
			//$extraCheck_op_op .= " AND op.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate_op != '') {
            $extraCheck_op_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_sc .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
            $extraCheck_op_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate_op))."'";
			 //$extraCheck_op_op .= " AND op.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_op_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_jv .= " AND YEAR(jv	.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_sc .= " AND YEAR(sc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
		    //$extraCheck_op_op .= " AND YEAR(op.dDate) >= '" .$this->txtFromYear. "' AND YEAR(op.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {

            $extraCheck_op_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
            $extraCheck_op_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
            $extraCheck_op_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
            $extraCheck_op_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
            $extraCheck_op_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
            $extraCheck_op_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
            $extraCheck_op_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
            $extraCheck_op_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
            $extraCheck_op_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";
			$extraCheck_op_op .= " AND (opd.vACode='".$this->ddlAccount."')";
            $extraCheck_op_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_op_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_op_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_op_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

            $extraCheck_op_sc .= "AND(sad.nAcHead='".$this->ddlAccount."' OR sad.nDrAccount='".$this->ddlAccount."')";
            //$extraCheck_op_rc .= "AND(rcd.nDrAcc='".$this->ddlAccount."')";
			$extraCheck_op_rc .= "HAVING subacc='".$this->ddlAccount."'";

        }//end if

        if (!empty($this->ddlSubAccount)){
            $drAccount = @split("-",$this->ddlSubAccount);

            $extraCheck_op_cr .= " AND crd.vACode='".$drAccount[0]."'";
            $extraCheck_op_cp .= " AND cpd.vACode='".$drAccount[0]."'";
            $extraCheck_op_bb .= " AND bbd.vACode='".$drAccount[0]."'";
            $extraCheck_op_bp .= " AND bpd.vACode='".$drAccount[0]."'";
			//$extraCheck_op_op .= " AND opd.vAcSubCode='".$drAccount[0]."'";
			$extraCheck_op_op .= " AND opd.vAcSubCode='".$drAccount[0]."'";
            $extraCheck_op_jv .= " AND jvd.vACode='".$drAccount[0]."'";
            $extraCheck_op_cv .= " AND cvd.vACode='".$drAccount[0]."'";
            $extraCheck_op_dv .= " AND dvd.vACode='".$drAccount[0]."'";
            $extraCheck_op_ac .= " AND acd.vACode='".$drAccount[0]."'";
            $extraCheck_op_ad .= " AND ade.vACode='".$drAccount[0]."'";
            $extraCheck_op_sc .= " AND sad.nDrAccount='".$drAccount[0]."'";
            $extraCheck_op_rc .= " AND rcd.nDrAcc='".$drAccount[0]."'";
        }
		
		$data['open_value'] = $this->manage_info_model->getCustomerSummaryReport_op($extraCheck_op_cr,$extraCheck_op_cp,$extraCheck_op_bb,$extraCheck_op_bp,$extraCheck_op_jv,$extraCheck_op_cv,$extraCheck_op_dv,$extraCheck_op_ac,$extraCheck_op_ad,$extraCheck_op_sc,$extraCheck_op_rc,$extraCheck_op_sj_special,$extraCheck_op_op);

		
		
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/summary_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/summary_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getVendorSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
			 $extraCheck_op = "AND op.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
			$extraCheck_op .= " AND (op.vLocation='".$this->ddlLocation."' OR op.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
				 $extraCheck_op .= " AND op.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
				 $extraCheck_op .= " AND op.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_sc .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_sj .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sc .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sc .= " AND YEAR(sc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        /*if (!empty($this->ddlAccount)) {

            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
            $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
            $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
            $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
            $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

            $extraCheck_sc .= "AND(sad.nAcHead='".$this->ddlAccount."' OR sad.nDrAccount='".$this->ddlAccount."')";
            $extraCheck_rc .= "AND(rcd.nDrAcc='".$this->ddlAccount."')";

        }//end if

        if (!empty($this->ddlSubAccount)){
            $drAccount = @split("-",$this->ddlSubAccount);

            $extraCheck_cr .= " AND crd.vACode='".$drAccount[0]."'";
            $extraCheck_cp .= " AND cpd.vACode='".$drAccount[0]."'";
            $extraCheck_bb .= " AND bbd.vACode='".$drAccount[0]."'";
            $extraCheck_bp .= " AND bpd.vACode='".$drAccount[0]."'";
            $extraCheck_jv .= " AND jvd.vACode='".$drAccount[0]."'";
            $extraCheck_cv .= " AND cvd.vACode='".$drAccount[0]."'";
            $extraCheck_dv .= " AND dvd.vACode='".$drAccount[0]."'";
            $extraCheck_ac .= " AND acd.vACode='".$drAccount[0]."'";
            $extraCheck_ad .= " AND ade.vACode='".$drAccount[0]."'";
            $extraCheck_sc .= " AND sad.nDrAccount='".$drAccount[0]."'";
            $extraCheck_rc .= " AND rcd.nDrAcc='".$drAccount[0]."'";
        }*/

       //echo $this->ddlAccount;
        if (!empty($this->ddlAccount)) {
			//echo $this->ddlAccount;
			if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
            //if ($this->ddlAccount == 91 || $this->ddlAccount == 101 || $this->ddlAccount == 111):
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                //$extraCheck_op .= " AND (opd.vAcSubCode='".$this->ddlAccount."' OR opd.vACode='".$this->ddlAccount."')";
                
				
				$extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_cr .= " AND crd.vACode='".$drAccount[0]."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$drAccount[0]."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$drAccount[0]."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$drAccount[0]."'";
					 //$extraCheck_op .= " AND opd.vACode='".$drAccount[0]."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$drAccount[0]."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$drAccount[0]."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$drAccount[0]."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$drAccount[0]."'";
                    $extraCheck_ad .= " AND ade.vACode='".$drAccount[0]."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_op .= " AND (opd.vACode='".$this->ddlAccount."')";
                
				$extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
                $closeId        = $this->ddlAccount.'-V';
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_cr .= " AND crd.vACode='".$drAccount[0]."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$drAccount[0]."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$drAccount[0]."'";
					$extraCheck_op .= " AND opd.vAcSubCode='".$drAccount[0]."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$drAccount[0]."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$drAccount[0]."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$drAccount[0]."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$drAccount[0]."'";
                    //$extraCheck_sj .= " AND sjd.nDrAccount = '".$drAccount[0]."' AND sjd.vTable = 'V'";
                    $extraCheck_ac .= " AND acd.vACode='".$drAccount[0]."'";
                    $extraCheck_ad .= " AND ade.vACode='".$drAccount[0]."'";
                endif;
            endif;
        }//end if
		if( !empty($this->ddlSubAccount)){

		$extraCheck_sj .= " AND  IF(sjd.nDrAccount = '', 
						IF ( ( ( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O')  ) ,
                     	( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    	LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    	WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' AND rr.nAccount  = " . $drAccount[0] .  " 
		    	) ,
                   	IF( sjd.vType = 'Tkt', (SELECT nAcc FROM {PRE}TicketFareDetails WHERE nTicketId = sjd.vNumber AND  nAcc = " . $drAccount[0] .  "), 
				IF ( sjd.vType = 'Htl', (SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber AND nAcCode = " . $drAccount[0] .  "),
                               		(SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = sjd.vNumber AND nPassengerAC = " . $drAccount[0] .  " ) 
			    	   )	)  )  ,0 )";
	}
        $data['list_value'] = $this->manage_info_model->getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj,$extraCheck_sj_special,$extraCheck_op);
		
		if (!empty($this->ddlDivision)) {
            $extraCheck_op_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_ad = " AND ad.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_rc = " AND rc.nDivision='".$this->ddlDivision."'";
            $extraCheck_op_sj = " sj.nDivision='".$this->ddlDivision."'";
			 $extraCheck_op_op = " AND op.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_op_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_op_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
			$extraCheck_op_op .= " AND (op.vLocation='".$this->ddlLocation."' OR op.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_op_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
                $extraCheck_op_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
				$extraCheck_op_op .= " AND op.nLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_op_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_op_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
				$extraCheck_op_op .= " AND op.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else
			
		  $curdate            = date('Y-m-d',strtotime($this->txtFromDate));

        $this->txtToDate = date('Y-m-d',strtotime($curdate." -1 day"));
		
		$this->txtFromDate1="01-03-2016";
		

        if ($this->txtFromDate !='') {
            $extraCheck_op_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_sc .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
            $extraCheck_op_sj .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate1))."' ";
			//$extraCheck_op_op .= " AND op.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck_op_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_sc .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_op_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
			//$extraCheck_op_op .= " AND op.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

     if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_op_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_sc .= " AND YEAR(sc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_op_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
			//$extraCheck_op_op .= " AND YEAR(op.dDate) >= '" .$this->txtFromYear . "' AND YEAR(op.dDate) <= '" . $this->txtToYear . "'";
      
		}//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if


        if (!empty($this->ddlAccount)) {
			if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
            //if ($this->ddlAccount == 91 || $this->ddlAccount == 101 || $this->ddlAccount == 111):
                $extraCheck_op_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_op_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_op_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                //$extraCheck_op_op .= " AND (opd.vAcSubCode='".$this->ddlAccount."' OR opd.vACode='".$this->ddlAccount."')";
                
				
				$extraCheck_op_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_op_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_op_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_op_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_op_cr .= " AND crd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_cp .= " AND cpd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_bb .= " AND bbd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_bp .= " AND bpd.vACode='".$drAccount[0]."'";
					 //$extraCheck_op_op .= " AND opd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_jv .= " AND jvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_cv .= " AND cvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_dv .= " AND dvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_sj_special = " HAVING subCode !=''";
                    $extraCheck_op_ac .= " AND acd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_ad .= " AND ade.vACode='".$drAccount[0]."'";
                endif;
            else:
                $extraCheck_op_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_op_op .= " AND (opd.vACode='".$this->ddlAccount."')";
                
				$extraCheck_op_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_op_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_op_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_op_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_op_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_op_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_op_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_op_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
                $closeId        = $this->ddlAccount.'-V';
                $extraCheck_op_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);

                    $extraCheck_op_cr .= " AND crd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_cp .= " AND cpd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_bb .= " AND bbd.vACode='".$drAccount[0]."'";
					$extraCheck_op_op .= " AND opd.vAcSubCode='".$drAccount[0]."'";
                    $extraCheck_op_bp .= " AND bpd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_jv .= " AND jvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_cv .= " AND cvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_dv .= " AND dvd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_sj .= " AND sjd.nDrAccount = '".$drAccount[0]."' AND sjd.vTable = 'V'";
                    $extraCheck_op_ac .= " AND acd.vACode='".$drAccount[0]."'";
                    $extraCheck_op_ad .= " AND ade.vACode='".$drAccount[0]."'";
                endif;
            endif;
        }//end if
		
		//$data['open_value'] = $this->manage_info_model->getVendorSummaryReport_op($extraCheck_op_cr,$extraCheck_op_cp,$extraCheck_op_bb,$extraCheck_op_bp,$extraCheck_op_jv,$extraCheck_op_cv,$extraCheck_op_dv,$extraCheck_op_ac,$extraCheck_op_ad,$extraCheck_op_sj,$extraCheck_op_sj_special,$extraCheck_op_op);

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/payable_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/payable_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAirlineSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

	$extraCheck = $this->checksScRc();

	
	$extraCheck_sc = $extraCheck['extraCheck_sc'];
	$extraCheck_rc = $extraCheck['extraCheck_rc'];

	if(!empty($this->ddlAirline)){
		$extraCheck_sc .= " AND  scd.nAirline = (SELECT vAirlineNo FROM {PRE}Airline WHERE nArId  = ". $this->ddlAirline ." )";
		$extraCheck_rc .= " AND  rcd.nAirline = (SELECT vAirlineNo FROM {PRE}Airline WHERE nArId  = ". $this->ddlAirline ." ) ";
	}

        $adminGroupResults = $this->manage_info_model->getAirlineSummaryReport($extraCheck_sc,$extraCheck_rc);
	
        $data['list_values_details'] = $adminGroupResults['list_values_details'];
        $data['list_value_summary'] = $adminGroupResults['list_values_summary'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/airline_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/airline_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getHotelSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

	
	$extraCheck = $this->checksScRc();
	
        $adminGroupResults = $this->manage_info_model->getHotelSummaryReport($extraCheck['extraCheck_sc'],$extraCheck['extraCheck_rc']);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
        $data['list_values_details'] = $adminGroupResults['list_values_details'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/hotel_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/hotel_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getProfitEmployeeReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

	$extraCheck = $this->checksScRc();
	
	$extraCheck_sc = $extraCheck['extraCheck_sc'];
	$extraCheck_rc = $extraCheck['extraCheck_rc'];

	if(!empty($this->ddlEmployee)){
		//$extraCheck_sc .= " AND (SELECT nEId FROM {PRE}EmployeeMaster WHERE nEId  = ". $this->ddlEmployee ." ) = sc.issue";
		$extraCheck_sc .= " AND scd.issue = ". $this->ddlEmployee ."";
		
		$extraCheck_rc .= " AND rrd.vIssuedBy = ". $this->ddlEmployee ." ";
	}

        $adminGroupResults = $this->manage_info_model->getProfitEmployeeReport($extraCheck_sc,$extraCheck_rc);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
        $data['list_value_detail'] = $adminGroupResults['list_values_detail']; 

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/employee_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/employee_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getProfitCustomerReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));


	$extraCheck = $this->checksScRc();
	
        $adminGroupResults = $this->manage_info_model->getProfitCustomerReport($extraCheck['extraCheck_sc'],$extraCheck['extraCheck_rc']);
	
        $data['list_values_details'] = $adminGroupResults['list_values_details'];
        $data['list_value_summary'] = $adminGroupResults['list_values_summary'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/customer_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/customer_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getProfitByVendorReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
         $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
         $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
//            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
            $extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
        }//end if
		
        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
//            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
//                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
//                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
//            $extraCheck_rc .= " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_sj .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        }//end if

        if ($this->txtToDate != '') {
//            $extraCheck_rc .= " AND rc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
//            $extraCheck_rc .= " AND YEAR(rc.dDate) >= '" .$this->txtFromYear. "' AND YEAR(rc.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            if ($this->ddlAccount == 91 || $this->ddlAccount == 101 || $this->ddlAccount == 111):
//                $extraCheck_rc .= " AND(rcd.nDrAcc='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_sj_special = " HAVING subCode !=''";
                endif;
            else:
//                $extraCheck_rc .= " AND(rcd.nDrAcc='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
                $closeId        = $this->ddlAccount.'-V';
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $drAccount = @split("-",$this->ddlSubAccount);
 //                   $extraCheck_rc .= " AND rcd.nDrAcc='".$drAccount[0]."' AND rcd.vTable='V'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = 'V'";
                endif;
            endif;
        }//end if

//        $this->extraCheck = $extraCheck_rc;

	if( !empty($this->ddlVendor)){

		$extraCheck_sj .= " AND  IF(sjd.nDrAccount = '', 
						IF ( ( ( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O')  ) ,
                     	( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    	LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    	WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' AND rr.nAccount  = " . $this->ddlVendor .  " 
		    	) ,
                   	IF( sjd.vType = 'Tkt', (SELECT nAcc FROM {PRE}TicketFareDetails WHERE nTicketId = sjd.vNumber AND  nAcc = " . $this->ddlVendor .  "), 
				IF ( sjd.vType = 'Htl', (SELECT nAcCode FROM {PRE}HotelVoucher WHERE nHVId = sjd.vNumber AND nAcCode = " . $this->ddlVendor .  "),
                               		(SELECT nPassengerAC FROM {PRE}XoBooking WHERE nXId = sjd.vNumber AND nPassengerAC = " . $this->ddlVendor .  " ) 
			    	   )	)  )  ,0 )";
	}


        $adminGroupResults = $this->manage_info_model->getProfitByVendorReport($extraCheck_sj,$extraCheck_sj_special);
        $data['list_values'] = $adminGroupResults['list_values'];
        $data['list_value_summary'] = $adminGroupResults['list_values_summary'];
        //$this->getProfitByVendorResult = $data;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/vendor_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/vendor_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
    function getLocationSummaryReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

	$extraCheck = $this->checksScRc();
	
        $adminGroupResults = $this->manage_info_model->getLocationSummaryReport($extraCheck['extraCheck_sc'],$extraCheck['extraCheck_rc']);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
        $data['list_values_details'] = $adminGroupResults['list_values_details'];


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/location_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/location_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getProfitGdsReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlVendor');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

	
	$extraCheck = $this->checksScRc();
	
        $adminGroupResults = $this->manage_info_model->getProfitGdsReport($extraCheck['extraCheck_sc'],$extraCheck['extraCheck_rc']);
        $data['list_value_summary'] = $adminGroupResults['list_values_summary']; 
        $data['list_values_details'] = $adminGroupResults['list_values_details'];

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/gds_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/gds_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

	
	function getAccGroup()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtToDate');
        $this->session->unset_userdata('txtFromDate');
        $this->session->unset_userdata('txtFromYear');
        $this->session->unset_userdata('txtToYear');
        $this->session->unset_userdata('ddlAccGroup');
        $this->session->unset_userdata('ddlAccount');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->input->post('ddlDivision');
        $this->ddlLocation       = $this->input->post('ddlLocation');
        $this->ddlLocation2      = $this->input->post('ddlLocation2');
        $this->txtToDate         = $this->input->post('txtToDate');
        $this->txtFromDate       = $this->input->post('txtFromDate');
        $this->txtFromYear       = $this->input->post('txtFromYear');
        $this->txtToYear         = $this->input->post('txtToYear');
        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');
        $this->ddlAccount        = $this->input->post('ddlAccount');

        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));
        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));
        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));
        $this->session->set_userdata('txtToDate',$this->input->post('txtToDate'));
        $this->session->set_userdata('txtFromDate',$this->input->post('txtFromDate'));
        $this->session->set_userdata('txtFromYear',$this->input->post('txtFromYear'));
        $this->session->set_userdata('txtToYear',$this->input->post('txtToYear'));
        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));
        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if(trim($this->txtFromDate) == '')
        {
            $curdate            = date('Y-m-d');
            $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));
        }//end if
        else
        {
            $curdate            = date('Y-m-d',strtotime($this->txtFromDate));
            $this->txtFromDate2 = date('Y-m-d',strtotime($curdate." -1 day"));
        }//end else

        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
        }//end if

        if ($this->ddlLocation !=0 && $this->ddlLocation2 !=0) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_sj .= " AND (sj.nLocation='".$this->ddlLocation."' OR sj.nLocation='".$this->ddlLocation2."')";
            $extraCheck_ac .= " AND (ac.vLocation='".$this->ddlLocation."' OR ac.vLocation='".$this->ddlLocation2."')";
            $extraCheck_ad .= " AND (ad.vLocation='".$this->ddlLocation."' OR ad.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if ($this->ddlLocation!=0) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
            }//end if

            if ($this->ddlLocation2!=0) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_sj .= " AND sj.nLocation='".$this->ddlLocation2."'";
                $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation2."'";
                $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_cr_dt = " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cp_dt = " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bb_dt = " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_bp_dt = " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_jv_dt = " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_cv_dt = " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_dv_dt = " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_sj_dt = " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ac_dt = " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_ad_dt = " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $openBal_cr_dt = " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtFromDate2)) . "' ";
        }//end if

        if ($this->txtToDate != '') {
            $extraCheck_cr_dt .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cp_dt .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bb_dt .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bp_dt .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_jv_dt .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cv_dt .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_dv_dt .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sj_dt .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ac_dt .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ad_dt .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear. "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear. "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear. "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear. "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear. "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear. "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear. "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear. "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear. "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear. "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {
            $extraCheck_cr .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_cp .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_bb .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_bp .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_jv .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_cv .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_dv .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_sj .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_ac .= " AND fm.nId= '".$this->ddlAccGroup."'";
            $extraCheck_ad .= " AND fm.nId= '".$this->ddlAccGroup."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_sj .= " AND (sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";
            endif;
        }//end if

        $this->open_cr = $extraCheck_cr.$openBal_cr_dt;
        $this->open_cp = $extraCheck_cp.$openBal_cp_dt;
        $this->open_bb = $extraCheck_bb.$openBal_bb_dt;
        $this->open_bp = $extraCheck_bp.$openBal_bp_dt;
        $this->open_jv = $extraCheck_jv.$openBal_jv_dt;
        $this->open_cv = $extraCheck_cv.$openBal_cv_dt;
        $this->open_dv = $extraCheck_dv.$openBal_dv_dt;
        $this->open_sj = $extraCheck_sj.$openBal_sj_dt;
        $this->open_ac = $extraCheck_ac.$openBal_ac_dt;
        $this->open_ad = $extraCheck_ad.$openBal_ad_dt;

        $extraCheck_cr .= $extraCheck_cr_dt;
        $extraCheck_cp .= $extraCheck_cp_dt;
        $extraCheck_bb .= $extraCheck_bb_dt;
        $extraCheck_bp .= $extraCheck_bp_dt;
        $extraCheck_jv .= $extraCheck_jv_dt;
        $extraCheck_cv .= $extraCheck_cv_dt;
        $extraCheck_dv .= $extraCheck_dv_dt;
        $extraCheck_sj .= $extraCheck_sj_dt;
        $extraCheck_ac .= $extraCheck_ac_dt;
        $extraCheck_ad .= $extraCheck_ad_dt;

        $this->extraCheck_cr = $extraCheck_cr;
        $this->extraCheck_cp = $extraCheck_cp;
        $this->extraCheck_bb = $extraCheck_bb;
        $this->extraCheck_bp = $extraCheck_bp;
        $this->extraCheck_jv = $extraCheck_jv;
        $this->extraCheck_cv = $extraCheck_cv;
        $this->extraCheck_dv = $extraCheck_dv;
        $this->extraCheck_sj = $extraCheck_sj;
        $this->extraCheck_ac = $extraCheck_ac;
        $this->extraCheck_ad = $extraCheck_ad;

        $list_value = $this->profloss_report_model->getAccGroup($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
        $data['list_value'] =  $this->profloss_report_model->getAllDetails($list_value);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;

        $this->load->view('manage_info/acc_group_report', $data);
    }//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
