<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Year_process extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('year_process_model');
		ini_set('memory_limit', '-1');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = "YEAR PROCESS";
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->year_process_model->getActiveDivision();
        $data['list_loc_value']     = $this->year_process_model->getActiveLocations();
        $data['list_acc_group']     = $this->year_process_model->getAccountGroup();
        $data['list_acc_value']     = $this->year_process_model->getActiveAcName();
		$data['list_Year']     = $this->year_process_model->getYearDetails();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromDate   = $this->session->userdata('txtFromDate');
            $this->txtFromYear   = $this->session->userdata('txtFromYear');
            $this->txtToYear     = $this->session->userdata('txtToYear');
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->pageName      = $this->session->userdata('pageName');
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
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('year_process/year_process',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->year_process_model->filterAccount($accName);
        $this->load->view('year_process/show_account', $data);
    }//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->year_process_model->filterLocation($this->ddlDivision);
		$this->load->view('year_process/show_location',$data);
	}//end function
	
function filterYear()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->nId = $this->input->post('nId');
		
		$data = $this->year_process_model->filterYear($this->nId);
		 echo json_encode($data);
		
	}//end function

    function getSubAccount()
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

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

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

       if ($this->ddlLocation != 0 && $this->ddlLocation2 != 0){
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
            if ($this->ddlLocation != 0) {
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

            if ($this->ddlLocation2 != 0) {
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
            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) <= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) <= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) <= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) <= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) <= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) <= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) <= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) <= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) <= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) >= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) <= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) >= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

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
                $extraCheck_sj .= " AND sjd.nAccHeadId='".$this->ddlAccount."'";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
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
/*
        $data['search_date'] = $this->txtFromDate;
        $data['search_location'] = $this->ddlLocation; 
        $data['list_value'] = $this->year_process_model->getSubAccount($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
*/


        $extra_checks = array();
        $extra_checks = $this->year_process_model->checks();

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
                             account as AccountId,subacc,IF(subacc = 0, '', vTable) as vTable, SUM(debit) as debit ,SUM(credit) as credit, (SELECT nHAId FROM {PRE}AccountHeadAccounts WHERE nId = account) as HeadId 

                             FROM ( ";

        $groupby = "  ) as temp GROUP BY HeadId, AccountId, subacc ";

        $orderby =  "  ORDER BY HeadId,AccountId, subacc ASC";


        $data['list_value'] = $this->year_process_model->getReports($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_cr_top_acc,$extraCheck_cp_top_acc,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$extraCheck_ac_top_acc,$extraCheck_ad_top_acc,$orderby,$group_select, $groupby);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $data['search_date'] = $this->txtFromDate;
        $data['search_acc'] = $this->ddlAccount;
        $data['search_subAcc'] = $this->ddlSubAccount;


        $this->load->view('year_process/sub_acc_report', $data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
