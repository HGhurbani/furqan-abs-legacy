<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Balasheet_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('balasheet_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = BALANCESHEET;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value'] = $this->balasheet_report_model->getActiveDivision();
		$data['list_loc_value'] = $this->balasheet_report_model->getActiveLocations();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromYear   = $this->session->userdata('txtFromYear');
            $this->ddlReport     = $this->session->userdata('ddlReport');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('balasheet_report/balasheet_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->balasheet_report_model->filterLocation($this->ddlDivision);
		$this->load->view('balasheet_report/show_location',$data);
	}//end function

    function getBalanceGeneral()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtToDate');
        $this->session->unset_userdata('txtFromYear');
        $this->session->unset_userdata('ddlReport');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

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

        if ($this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_cp .= " AND YEAR(cp.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_bb .= " AND YEAR(bb.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_bp .= " AND YEAR(bp.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_jv .= " AND YEAR(jv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_cv .= " AND YEAR(cv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_dv .= " AND YEAR(dv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_sj .= " AND YEAR(sj.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_ac .= " AND YEAR(ac.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_ad .= " AND YEAR(ad.dDate) <= '" .$this->txtFromYear . "' ";
        }//end if

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

        //echo $this->ddlReport;exit;
        switch($this->ddlReport)
        {
            case "Details":
                $page = 'general_report_details';
                break;

            case "Summary":
                $page = 'general_report_summary';
                break;
        }//end switch

        $data['list_value'] = $this->balasheet_report_model->getBalanceGeneral($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
        $this->load->view('balasheet_report/'.$page, $data);
    }//end function

    function getBalanceT()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('ddlDivision');
        $this->session->unset_userdata('ddlLocation');
        $this->session->unset_userdata('ddlLocation2');
        $this->session->unset_userdata('txtToDate');
        $this->session->unset_userdata('txtFromYear');
        $this->session->unset_userdata('ddlReport');
        $this->session->unset_userdata('pageName');

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

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

        if ($this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_cp .= " AND YEAR(cp.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_bb .= " AND YEAR(bb.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_bp .= " AND YEAR(bp.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_jv .= " AND YEAR(jv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_cv .= " AND YEAR(cv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_dv .= " AND YEAR(dv.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_sj .= " AND YEAR(sj.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_ac .= " AND YEAR(ac.dDate) <= '" .$this->txtFromYear . "' ";
            $extraCheck_ad .= " AND YEAR(ad.dDate) <= '" .$this->txtFromYear . "' ";
        }//end if

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

        $data['list_value'] = $this->balasheet_report_model->getBalanceT($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
        $this->load->view('balasheet_report/balance_report_details', $data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>