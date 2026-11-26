<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ac_report extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('ac_report_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = ACCOUNTREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->ac_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->ac_report_model->getActiveLocations();
        $data['list_acc_group']     = $this->ac_report_model->getAccountGroup();
        $data['list_acc_value']     = $this->ac_report_model->getActiveAcName();
        $data['list_sub_acc']       = $this->ac_report_model->getActiveSubAccounts();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromDate   = $this->session->userdata('txtFromDate');
            /*$this->txtFromYear   = $this->session->userdata('txtFromYear');
            $this->txtToYear     = $this->session->userdata('txtToYear');*/
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        }

        $this->load->view('general/template_top');
        $this->load->view('ac_report/ac_report',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision   = $this->input->post('ddlDivision');
        $this->ddlLocation   = $this->input->post('ddlLocation');
        $this->ddlLocation2  = $this->input->post('ddlLocation2');

        $this->ddlLocation   = $this->session->userdata('ddlLocation');
        $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

        $data['list_loc_value'] = $this->ac_report_model->filterLocation($this->ddlDivision);
        $this->load->view('ac_report/show_location',$data);
    }//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');		        $loc = $this->input->post('ddlLocation');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->ac_report_model->filterAccount($accName,$loc);
        $this->load->view('ac_report/show_account', $data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
        $this->tableType = $this->session->userdata('tableType');

        $data['list_sub_acc'] = $this->ac_report_model->filterSubAccount($accName);
        $this->load->view('ac_report/show_sub', $data);
    }//end function

    function getDetailReport()
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
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        /*$this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');*/
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));


 
        $data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);

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

        $groupby = '';
        $group_select = '';
        $orderby =  "  ORDER BY account, subacc, date,document ASC ";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;

        $mydata = $this->load->view("ac_report/export_files/detail_report", $data,true);
        $_SESSION['detail_report'] = $mydata;


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/detail_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/detail_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getDailyReport()
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
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');
		 $data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);

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


        $group_select =" SELECT date, 
                              IF(tblName = 'SJ','SALES ENTRY','FINANCE ENTRY') as entry,
                              GROUP_CONCAT(description) as description, location , COUNT(*) as count,
                              account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit
                              FROM ( ";
        $groupby = "  ) as temp GROUP BY account, subacc, date, location, entry"; 

        $orderby =  "  ORDER BY account, subacc, date, location, entry ASC ";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;
 

        $mydata = $this->load->view("ac_report/export_files/daily_report", $data,true);
        $_SESSION['daily_report'] = $mydata;
        $_SESSION['daily_report_pgSz'] = 'A4';

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/daily_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/daily_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getWeeklyReport()
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
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        /*$this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');*/
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');
		$data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);


        $this->session->set_userdata('pageName',$this->uri->segment(1));

/*        $data['list_value'] = $this->ac_report_model->getWeeklyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

		$date_weeks         = $this->ac_report_model->getNoWeeks($this->txtFromDate,$this->txtToDate);
		$data['no_weeks']   = $date_weeks['weeknos'];
		$data['start_date'] = $date_weeks['startDate'];
*/
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


/*        $group_select =" SELECT WEEK(date,1) as t_week_yr, MONTHNAME(date)as t_month1, CONCAT_WS('/',YEAR(date), MONTH(date), '01') as t_month,
                              (WEEK(date,1) - WEEK(CONCAT_WS('/',YEAR(date), MONTH(date), '01'),1) + 1) as t_week1, WEEK(date,1) as t_week,
                              IF(tblName = 'SJ','SALES ENTRY','FINANCE ENTRY') as entry,
                              location , COUNT(*) as count,
                              account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit
                              FROM ( ";*/
        $group_select =" SELECT IF(tblName = 'SJ','SALES ENTRY','FINANCE ENTRY') as entry, 
                              location , COUNT(*) as count,
                             account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit,
                             MONTHNAME(date)as t_monthname, MONTH(date) as t_month, YEAR(date) as t_year,
                             IF((DAY(date) - 7 ) <= 0 , 1 , IF((CEIL((DAY(date) - 7 ) / 7)) > 3 ,4, (CEIL((DAY(date) - 7 ) / 7)) +  1))  as t_week
                             FROM ( ";

// GROUP_CONCAT(description) as description
        $groupby = "  ) as temp GROUP BY account, subacc, t_month, t_week,t_year, location, entry";

        $orderby =  "  ORDER BY account, subacc, t_month, t_week,t_year, location, entry ASC ";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;

        $mydata = $this->load->view("ac_report/export_files/weekly_report", $data,true);
        $_SESSION['weekly_report'] = $mydata;
        $_SESSION['weekly_report_pgSz'] = 'A4';

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/weekly_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/weekly_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getMonthlyReport()
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
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        /*$this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');*/
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
		
        $data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);

/*
        $data['year_closing']     = $this->ac_report_model->getYearClosing();

        $extraCheck_cr .= $extraCheck_cr_dt;
        $extraCheck_cp .= $extraCheck_cp_dt;
        $extraCheck_bb .= $extraCheck_bb_dt;
        $extraCheck_bp .= $extraCheck_bp_dt;
        $extraCheck_jv .= $extraCheck_jv_dt;
        $extraCheck_cv .= $extraCheck_cv_dt;
        $extraCheck_dv .= $extraCheck_dv_dt;
        $extraCheck_sj .= $extraCheck_sj_dt.$extraCheck_sj_special;
        $extraCheck_ac .= $extraCheck_ac_dt;
        $extraCheck_ad .= $extraCheck_ad_dt;

        $data['list_value'] = $this->ac_report_model->getMonthlyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

		$date_weeks         = $this->ac_report_model->getNoMonth($this->txtFromDate,$this->txtToDate);
		$data['no_weeks']   = $date_weeks['monthnos'];
		$data['start_date'] = $date_weeks['startDate'];
*/

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


        $group_select =" SELECT IF(tblName = 'SJ','SALES ENTRY','FINANCE ENTRY') as entry, 
                              location , COUNT(*) as count,
                             account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit,
                             MONTHNAME(date)as t_monthname, MONTH(date) as t_month, YEAR(date) as t_year
                             FROM ( ";

        $groupby = "  ) as temp GROUP BY account, subacc, t_month, t_year, location, entry";

        $orderby =  "  ORDER BY account, subacc, t_month, t_year, location, entry ASC ";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;


        $mydata = $this->load->view("ac_report/export_files/monthly_report", $data,true);
        $_SESSION['monthly_report'] = $mydata;
        $_SESSION['monthly_report_pgSz'] = 'A4';


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/monthly_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/monthly_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getYearlyReport()
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
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        /*$this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');*/
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
		
        $data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);

/*
        $data['year_closing']     = $this->ac_report_model->getYearClosing();

        $extraCheck_cr .= $extraCheck_cr_dt;
        $extraCheck_cp .= $extraCheck_cp_dt;
        $extraCheck_bb .= $extraCheck_bb_dt;
        $extraCheck_bp .= $extraCheck_bp_dt;
        $extraCheck_jv .= $extraCheck_jv_dt;
        $extraCheck_cv .= $extraCheck_cv_dt;
        $extraCheck_dv .= $extraCheck_dv_dt;
        $extraCheck_sj .= $extraCheck_sj_dt.$extraCheck_sj_special;
        $extraCheck_ac .= $extraCheck_ac_dt;
        $extraCheck_ad .= $extraCheck_ad_dt;

        $data['list_value'] = $this->ac_report_model->getYearlyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

		$date_weeks         = $this->ac_report_model->getNoYears($this->txtFromDate,$this->txtToDate);
		$data['no_weeks']   = $date_weeks['yearnos'];
		$data['start_date'] = $date_weeks['startDate'];
*/

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


        $group_select =" SELECT IF(tblName = 'SJ','SALES ENTRY','FINANCE ENTRY') as entry, 
                              location , COUNT(*) as count,
                             account,subacc,vTable, SUM(debit) as debit ,SUM(credit) as credit,
                             YEAR(date) as t_year
                             FROM ( ";

        $groupby = "  ) as temp GROUP BY account, subacc, t_year , location, entry";

        $orderby =  "  ORDER BY account, subacc, t_year, location, entry ASC ";


        $data['list_value'] = $this->ac_report_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;

        $mydata = $this->load->view("ac_report/export_files/yearly_report", $data,true);
        $_SESSION['yearly_report'] = $mydata;
        $_SESSION['yearly_report_pgSz'] = 'A4';


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/yearly_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/yearly_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getSummaryReport()
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
            /*$this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');*/
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        /*$this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');*/
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
		
        $data['year_closing']     = $this->ac_report_model->getYearClosing($this->ddlAccount,$this->ddlSubAccount,$this->txtFromDate,$this->txtToDate);

/*
        $data['year_closing']     = $this->ac_report_model->getYearClosing();

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

        $detVal  = $this->ac_report_model->getAllFinanceMainAccounts();
        if (!empty($this->ddlAccount)):
            if(!in_array($this->ddlAccount,$detVal)):
                $data['list_value'] = $this->ac_report_model->getSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special);
                $page = 'summary_report_filter';
            else:
                $data['list_value'] = $this->ac_report_model->getSummaryReportFilter($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special);
                $page = 'summary_report_filter';
            endif;
        else:
            $data['list_value'] = $this->ac_report_model->getSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_sj_special);
            $page = 'summary_report_filter';
        endif;

        $data['search_date'] = trim($this->txtFromDate);
*/

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
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;


        $page = "summary_report_filter";

        $mydata = $this->load->view('ac_report/export_files/'.$page, $data,true);
        $_SESSION['summary_report'] = $mydata;
        $_SESSION['summary_report_pgSz'] = 'A4';

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('ac_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
