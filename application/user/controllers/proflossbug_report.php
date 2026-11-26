<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Proflossbug_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('proflossbug_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = PROFITANDLOSSWITHBUDGET;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->proflossbug_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->proflossbug_report_model->getActiveLocations();
        $data['list_acc_group']     = $this->proflossbug_report_model->getAccountGroup();
        $data['list_acc_value']     = $this->proflossbug_report_model->getActiveAcName();
		$data['list_month']         = $this->proflossbug_report_model->getAllMonths();

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
		$this->load->view('proflossbug_report/proflossbug_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->proflossbug_report_model->filterLocation($this->ddlDivision);
		$this->load->view('proflossbug_report/show_location',$data);
	}//end function

    function getBudgetDetail()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = PROFITANDLOSSWITHBUDGET;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtMonth');
        $this->session->unset_userdata('txtYear');
        $this->session->unset_userdata('ddlAccGroup');
        $this->session->unset_userdata('ddlAccount');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->input->post('ddlDivision');
        $this->ddlLocation       = $this->input->post('ddlLocation');
        $this->ddlLocation2      = $this->input->post('ddlLocation2');
        $this->txtMonth          = $this->input->post('txtMonth');
        $this->txtYear           = $this->input->post('txtYear');
        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');
        $this->ddlAccount        = $this->input->post('ddlAccount');
		

        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));
        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));
        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));
        $this->session->set_userdata('txtMonth',$this->input->post('txtMonth'));
        $this->session->set_userdata('txtYear',$this->input->post('txtYear'));
        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));
        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));
        $this->session->set_userdata('pageName',$this->uri->segment(1));

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

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
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
            if (!empty($this->ddlLocation)) {
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

            if (!empty($this->ddlLocation2)) {
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

        if ($this->txtYear != '') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtYear. "' AND YEAR(cr.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtYear. "' AND YEAR(cp.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtYear. "' AND YEAR(bb.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtYear. "' AND YEAR(bp.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtYear. "' AND YEAR(jv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtYear. "' AND YEAR(cv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtYear. "' AND YEAR(dv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtYear. "' AND YEAR(sj.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtYear. "' AND YEAR(ac.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtYear. "' AND YEAR(ad.dDate) <= '" . $this->txtYear . "'";
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

        $data['list_value'] = $this->proflossbug_report_model->getBudgetDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
		
		//for budget
		if (!empty($this->ddlDivision)) {
            $extraCheck2 = " AND b.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck2 .= " AND (b.vLocation='".$this->ddlLocation."' OR b.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck2 .= " AND b.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck2 .= " AND b.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtMonth != '' && $this->txtMonth >0) {
            $extraCheck2 .= " AND b.vMonth = '".$this->txtMonth."'";
        }//end if

        if ($this->txtYear != '') {
            $extraCheck2 .= " AND b.vYear = '".$this->txtYear."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck2 .= " AND bd.nAHAId='".$this->ddlAccount."'";
        }//end if

        $data['blist_value'] = $this->proflossbug_report_model->getBudgetDetail2($extraCheck2);
		
        $this->load->view('proflossbug_report/budget_detail_report', $data);
    }//end function

    function getBudgetSummary()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = PROFITANDLOSSWITHBUDGET;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtMonth');
        $this->session->unset_userdata('txtYear');
        $this->session->unset_userdata('ddlAccGroup');
        $this->session->unset_userdata('ddlAccount');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->input->post('ddlDivision');
        $this->ddlLocation       = $this->input->post('ddlLocation');
        $this->ddlLocation2      = $this->input->post('ddlLocation2');
        $this->txtMonth          = $this->input->post('txtMonth');
        $this->txtYear           = $this->input->post('txtYear');
        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');
        $this->ddlAccount        = $this->input->post('ddlAccount');
		

        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));
        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));
        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));
        $this->session->set_userdata('txtMonth',$this->input->post('txtMonth'));
        $this->session->set_userdata('txtYear',$this->input->post('txtYear'));
        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));
        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));
        $this->session->set_userdata('pageName',$this->uri->segment(1));

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

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
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
            if (!empty($this->ddlLocation)) {
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

            if (!empty($this->ddlLocation2)) {
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

        if ($this->txtYear != '') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtYear. "' AND YEAR(cr.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtYear. "' AND YEAR(cp.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtYear. "' AND YEAR(bb.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtYear. "' AND YEAR(bp.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtYear. "' AND YEAR(jv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtYear. "' AND YEAR(cv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtYear. "' AND YEAR(dv.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtYear. "' AND YEAR(sj.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtYear. "' AND YEAR(ac.dDate) <= '" . $this->txtYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtYear. "' AND YEAR(ad.dDate) <= '" . $this->txtYear . "'";
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

        $data['list_value'] = $this->proflossbug_report_model->getBudgetDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
		
		//for budget
		if (!empty($this->ddlDivision)) {
            $extraCheck2 = " AND b.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck2 .= " AND (b.vLocation='".$this->ddlLocation."' OR b.vLocation='".$this->ddlLocation2."')";
        }//end if
        else{
            if (!empty($this->ddlLocation)) {
                $extraCheck2 .= " AND b.vLocation='".$this->ddlLocation."'";
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck2 .= " AND b.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtMonth != '' && $this->txtMonth >0) {
            $extraCheck2 .= " AND b.vMonth = '".$this->txtMonth."'";
        }//end if

        if ($this->txtYear != '') {
            $extraCheck2 .= " AND b.vYear = '".$this->txtYear."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck2 .= " AND bd.nAHAId='".$this->ddlAccount."'";
        }//end if

        $data['blist_value'] = $this->proflossbug_report_model->getBudgetDetail2($extraCheck2);
		
        $this->load->view('proflossbug_report/budget_detail_report', $data);
    }//end function
    
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>