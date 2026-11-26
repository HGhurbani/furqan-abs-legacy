<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage_info extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('manage_info_model');
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

        $this->load->view('general/template_top');
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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getSaleSummaryReport($extraCheck_sc);

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
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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
            $extraCheck_rc .= "AND(rcd.nDrAcc='".$this->ddlAccount."')";

        }//end if

        if (!empty($this->ddlSubAccount)){
            $drAccount = @split("-",$this->ddlSubAccount);

            $extraCheck_cr .= " AND crd.vACode='".$drAccount[0]."' AND crd.vTable='".$drAccount[1]."'";
            $extraCheck_cp .= " AND cpd.vACode='".$drAccount[0]."' AND cpd.vTable='".$drAccount[1]."'";
            $extraCheck_bb .= " AND bbd.vACode='".$drAccount[0]."' AND bbd.vTable='".$drAccount[1]."'";
            $extraCheck_bp .= " AND bpd.vACode='".$drAccount[0]."' AND bpd.vTable='".$drAccount[1]."'";
            $extraCheck_jv .= " AND jvd.vACode='".$drAccount[0]."' AND jvd.vTable='".$drAccount[1]."'";
            $extraCheck_cv .= " AND cvd.vACode='".$drAccount[0]."' AND cvd.vTable='".$drAccount[1]."'";
            $extraCheck_dv .= " AND dvd.vACode='".$drAccount[0]."' AND dvd.vTable='".$drAccount[1]."'";
            $extraCheck_ac .= " AND acd.vACode='".$drAccount[0]."' AND acd.vTable='".$drAccount[1]."'";
            $extraCheck_ad .= " AND ade.vACode='".$drAccount[0]."' AND ade.vTable='".$drAccount[1]."'";
            $extraCheck_sc .= " AND sad.nDrAccount='".$drAccount[0]."' AND sad.vTable='".$drAccount[1]."'";
            $extraCheck_rc .= " AND rcd.nDrAcc='".$drAccount[0]."' AND rcd.vTable='".$drAccount[1]."'";
        }

        $data['list_value'] = $this->manage_info_model->getAccountSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/summary_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/summary_report', $data);
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
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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
        }

        $data['list_value'] = $this->manage_info_model->getCustomerSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);

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
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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
        }

        $data['list_value'] = $this->manage_info_model->getVendorSummaryReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_ac,$extraCheck_ad,$extraCheck_sc,$extraCheck_rc);

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/summary_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/summary_report', $data);
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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getAirlineSummaryReport($extraCheck_sc);

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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getHotelSummaryReport($extraCheck_sc);

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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getProfitEmployeeReport($extraCheck_sc);

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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getProfitCustomerReport($extraCheck_sc);

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
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getProfitByVendorReport($extraCheck_sc);

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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getLocationSummaryReport($extraCheck_sc);

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

        if (!empty($this->ddlDivision)) {
            $extraCheck_sc = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_sc .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";
            $extraCheck_rc .= " AND (rc.vLocation='".$this->ddlLocation."' OR rc.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_sc .= " AND sc.vLocation='".$this->ddlLocation2."'";
                $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtFromDate !='') {
            $extraCheck_sc = " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
            $extraCheck_rc = " AND rc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
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

        $this->extraCheck = $extraCheck_rc;

        $data['list_value'] = $this->manage_info_model->getProfitGdsReport($extraCheck_sc);

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('manage_info/gds_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('manage_info/gds_summary', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>