<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sub_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('sub_report_model');
	}
    
    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SUBLEDGERREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();

        $data['list_div_value']      = $this->sub_report_model->getActiveDivision();
        $data['list_loc_value']      = $this->sub_report_model->getActiveLocations();
//        $data['list_acc_group']      = $this->sub_report_model->getAccountGroup();
//        $data['list_customer_value'] = $this->sub_report_model->getActiveCustomer();
        $data['list_acc_value']      = $this->sub_report_model->getActiveAcName();
        $data['list_sub_acc']        = $this->sub_report_model->getActiveSubAccounts();
        $data['list_emp_value']      = $this->sub_report_model->getActiveEmployee();
//        $data['list_agent']          = $this->sub_report_model->getAgentAccounts();

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
            $this->ddlCustomer   = $this->session->userdata('ddlCustomer');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount');
            $this->ddlEmployee   = $this->session->userdata('ddlEmployee');
            $this->ddlAgent      = $this->session->userdata('ddlAgent');
            $this->ddlReport     = $this->session->userdata('ddlReport');
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
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
        }

        $this->load->view('general/template_top');
        $this->load->view('sub_report/sub_report',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->sub_report_model->filterLocation($this->ddlDivision);
        $this->load->view('sub_report/show_location',$data);
    }//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->sub_report_model->filterAccount($accName);
        $this->load->view('sub_report/show_account', $data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount');
        $this->tableType = $this->session->userdata('tableType');

        $data['list_sub_acc'] = $this->sub_report_model->filterSubAccount($accName);
        $this->load->view('sub_report/show_sub', $data);
    }//end function

	function get_checks()
	{	
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
			$extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
			$extraCheck_sj_s = " AND sj.nDivision='".$this->ddlDivision."'";
		}

    if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {

      $extraCheck_cr .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_cp .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_bb .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_bp .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_jv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_cv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_dv .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_ac .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_ad .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_sj .=  " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      $extraCheck_sj_s .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
    }
/*
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_cr .= " AND STR_TO_DATE( cr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_cp .= " AND STR_TO_DATE( cp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_bb .= " AND STR_TO_DATE( bb.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_bp .= " AND STR_TO_DATE( bp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_jv .= " AND STR_TO_DATE( jv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_cv .= " AND STR_TO_DATE( cv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_dv .= " AND STR_TO_DATE( dv.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_sj .= " AND STR_TO_DATE( sj.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_sj_s .= " AND STR_TO_DATE( sj.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
		}//end if
*/


      if ($this->txtFromDate !='') {
        $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_sj_s .= " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
        $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

      }
      if ($this->txtToDate != '') {
        $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_sj .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_sj_s .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      }//end if

        $extraCheck_ac_top_acc .= $extraCheck_ac; 
        $extraCheck_ad_top_acc .= $extraCheck_ad;

		if (!empty($this->ddlAccount)) {
        $extraCheck_ac_top_acc .= " AND ac.nAcCode='".$this->ddlAccount."'";
        $extraCheck_ad_top_acc .= " AND ad.nAcCode='".$this->ddlAccount."'";

			$extraCheck_cr .= " AND crd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_cp .= " AND cpd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_bb .= " AND bbd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_bp .= " AND bpd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
			$extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";
        $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vAcSubCode = '".$this->ddlAccount."', acd.vACode='".$this->ddlAccount."' )";
        $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vAcSubCode = '".$this->ddlAccount."', ade.vACode='".$this->ddlAccount."' )";

			$extraCheck_sj .= " HAVING vAcSubCode ='".$this->ddlAccount."'";
			$extraCheck_sj_s .= "       GROUP BY sj.nInvoice,vType, vNumber HAVING vAcSubCode ='".$this->ddlAccount."'";

      if (!empty($this->ddlSubAccount)){
        $accounts = explode("-",$this->ddlSubAccount);	
          $extraCheck_ac_top_acc .= " AND ac.vSubCode = '".$accounts[0] ."' AND 'V' = '" . $accounts[1] ."' ";
          $extraCheck_ad_top_acc .= " AND ad.vSubCode = '".$accounts[0] ."' AND 'V' = '" . $accounts[1] ."' ";
        $extraCheck_cr .= " AND crd.vACode='". $accounts[0] ."'";
        $extraCheck_cp .= " AND cpd.vACode='".$accounts[0]."'";
        $extraCheck_bb .= " AND bbd.vACode='".$accounts[0]."'";
        $extraCheck_bp .= " AND bpd.vACode='".$accounts[0]."'";
        $extraCheck_jv .= " AND jvd.vACode='".$accounts[0]."'";
        $extraCheck_cv .= " AND cvd.vACode='".$accounts[0]."'";
        $extraCheck_dv .= " AND dvd.vACode='".$accounts[0]."'";
          $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vACode = '".$accounts[0] ."' AND acd.vTable ='".$accounts[1] ."',1 )";
          $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vACode = '".$accounts[0] ."' AND ade.vTable ='".$accounts[1] ."',1 )";
        $extraCheck_sj .= " AND vACode = '".$accounts[0]."'";
        $extraCheck_sj_s .= " AND vACode = '".$accounts[0]."'";

      }
    }		

		return array("cr" => $extraCheck_cr, "cp" => $extraCheck_cp, "bb" => $extraCheck_bb, 
			"bp" => $extraCheck_bp, "jv" => $extraCheck_jv, "cv" => $extraCheck_cv, "dv" => $extraCheck_dv, 
			"sj" => $extraCheck_sj,"sj_s" => $extraCheck_sj_s,"ac"=>$extraCheck_ac,"ac_top_acc" => $extraCheck_ac_top_acc,
      "ad" => $extraCheck_ad, "ad_top_acc" => $extraCheck_ad_top_acc);
	} //end function


    function getReceivableReports()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
/*
        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
/*        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
/*        }//end if

        switch($this->ddlReport)
        {
            case "Detail":
                $data['list_value'] = $this->sub_report_model->getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
            break;

            case "Summary":
                $data['list_value'] = $this->sub_report_model->getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
            break;

            case "Outstanding":
                $data['list_value'] = $this->sub_report_model->getReceivableOutstanding($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
            break;
        }//end switch
*/


// angela
		$extra_check = $this->get_checks();
		$extraCheck_cr = $extra_check['cr'];
		$extraCheck_cp = $extra_check['cp'];
		$extraCheck_bb = $extra_check['bb'];
		$extraCheck_bp = $extra_check['bp'];
		$extraCheck_jv = $extra_check['jv'];
		$extraCheck_cv = $extra_check['cv'];
		$extraCheck_dv = $extra_check['dv'];
		$extraCheck_ac = $extra_check['ac'];
		$extraCheck_ac_top_acc = $extra_check['ac_top_acc'];
		$extraCheck_ad = $extra_check['ad'];
		$extraCheck_ad_top_acc = $extra_check['ad_top_acc'];
		$extraCheck_sj = $extra_check['sj'];
		$extraCheck_sj_s = $extra_check['sj_s'];


		// fcdc will display only Payable (V) & Recievable (C)
		$extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C' ) ";

  
    $list_value  = $this->sub_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$this->ddlReport);

    $accounts = explode("-",$this->ddlSubAccount);
    $sub_data['list_value2'] = $this->sub_report_model->accountList($this->ddlAccount,$accounts[0],$list_value);
    $data['fromDate'] = $this->txtFromDate;
    $data['toDate'] = $this->txtToDate;

//                $data['list_value']  = $this->sub_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$this->ddlReport);
        switch($this->ddlReport)
        {
            case "Detail":
                $page = 'receivable_detail_report';
                break;

            case "Summary":
                $page = 'receivable_summary_report';
                $_SESSION[$page . "_pgSz"] = 'A4';
                break;

            case "Outstanding":
                $page = 'receivable_detail_report';
                break;
        }//end switch

        $mydata = $this->load->view("sub_report/export_files/".$page, $sub_data,true);
        $_SESSION[$page] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/'.$page, $sub_data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/'.$page, $sub_data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getPayable()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
        }//end if

        $data['list_value'] = $this->sub_report_model->getPayable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $mydata = $this->load->view("sub_report/export_files/payable_report", $data,true);
        $_SESSION['payable_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/payable_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/payable_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getReceivableDetail()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
/*
        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
 /*       }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
 /*       }//end if
*/


		$check = $this->get_checks();
		$extraCheck_sj = $check['sj_s'];
		$data['list_value'] = $this->sub_report_model->getReceivableDetail($extraCheck_sj);
    $data['fromDate'] = $this->txtFromDate;
    $data['toDate'] = $this->txtToDate;

//        $data['list_value'] = $this->sub_report_model->getReceivableDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
    $mydata = $this->load->view("sub_report/export_files/receivable_net_report", $data,true);
    $_SESSION['receivable_net_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/receivable_net_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/receivable_net_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getPayableDetail()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

/*        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
/*        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
/*        }//end if

        $data['list_value'] = $this->sub_report_model->getReceivableDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
*/
//angela pay

		$check = $this->get_checks();
		$extraCheck_sj = " ( select vSubLedgerType from tbl_AccountHeadControls where nActHId = sjd.naccheadid ) LIKE 'V' ";
		$extraCheck_sj .= $check['sj'];
		$data['list_value'] = $this->sub_report_model->getPayableDetail($extraCheck_sj);
    $data['fromDate'] = $this->txtFromDate;
    $data['toDate'] = $this->txtToDate;
				
    $mydata = $this->load->view("sub_report/export_files/payable_net_report", $data,true);
    $_SESSION['payable_net_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/payable_net_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/payable_net_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getParentCustomer()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
        }//end if

        switch($this->ddlReport)
        {
            case "Detail":
                $page = 'parent_customer_report';
                break;

            case "Summary":
                $page = 'parent_customer_summary';
                break;

            case "Outstanding":
                $page = 'parent_customer_report';
                break;
        }//end switch

        $data['list_value'] = $this->sub_report_model->getParentCustomer($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $mydata = $this->load->view("sub_report/export_files/".$page, $data,true);
        $_SESSION[$page] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getEmployeeCustomer()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
        }//end if

        switch($this->ddlReport)
        {
            case "Detail":
                $page = 'employee_customer_report';
                break;

            case "Summary":
                $page = 'employee_customer_summary';
                break;

            case "Outstanding":
                $page = 'employee_customer_report';
                break;
        }//end switch

        $data['list_value'] = $this->sub_report_model->getEmployeeCustomer($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $mydata = $this->load->view("sub_report/export_files/".$page, $data,true);
        $_SESSION[$page] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getSubAgent()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
        }//end if

        $data['list_value'] = $this->sub_report_model->getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $mydata = $this->load->view("sub_report/export_files/sub_agent_report", $data,true);
        $_SESSION['sub_agent_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/sub_agent_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/sub_agent_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

/*
    function getFCDCReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
/*        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
/*        }//end if

        switch($this->ddlReport)
        {
            case "Detail":
                $page = 'fcdc_detail_report';
                break;

            case "Summary":
                $page = 'fcdc_report';
                break;

            case "Outstanding":
                $page = 'fcdc_report';
                break;
        }//end switch

        $data['list_value'] = $this->sub_report_model->getFCDCReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
*/
    function getSubAgentDetail()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
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
        }//end if

        if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
            $extraCheck_cr .= " AND (cr.vLocation='".$this->ddlLocation."' OR cr.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cp .= " AND (cp.vLocation='".$this->ddlLocation."' OR cp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bb .= " AND (bb.vLocation='".$this->ddlLocation."' OR bb.vLocation='".$this->ddlLocation2."')";
            $extraCheck_bp .= " AND (bp.vLocation='".$this->ddlLocation."' OR bp.vLocation='".$this->ddlLocation2."')";
            $extraCheck_jv .= " AND (jv.vLocation='".$this->ddlLocation."' OR jv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_cv .= " AND (cv.vLocation='".$this->ddlLocation."' OR cv.vLocation='".$this->ddlLocation2."')";
            $extraCheck_dv .= " AND (dv.vLocation='".$this->ddlLocation."' OR dv.vLocation='".$this->ddlLocation2."')";
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
            }//end if

            if (!empty($this->ddlLocation2)) {
                $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation2."'";
                $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation2."'";
                $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation2."'";
                $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation2."'";
            }//end if
        }//end else

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_jv .= " AND jvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_cv .= " AND cvd.vAcSubCode='".$this->ddlAccount."'";
            $extraCheck_dv .= " AND dvd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
            $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
            $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
            $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlEmployee)) {
            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/
        }//end if

        if (!empty($this->ddlAgent)) {
            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/
        }//end if

        $data['list_value'] = $this->sub_report_model->getReceivableDetail($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
        $mydata = $this->load->view("sub_report/export_files/agent_detail_report", $data,true);
        $_SESSION['agent_detail_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/agent_detail_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/agent_detail_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function




// angela
    function getFCDCReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

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
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
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
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

// angela
		$check = $this->get_checks();
		$extraCheck_cr = $check['cr'];
		$extraCheck_cp = $check['cp'];
		$extraCheck_bb = $check['bb'];
		$extraCheck_bp = $check['bp'];
		$extraCheck_jv = $check['jv'];
		$extraCheck_cv = $check['cv'];
		$extraCheck_dv = $check['dv'];
		$extraCheck_ac = $check['ac'];
		$extraCheck_ac_top_acc = $check['ac_top_acc'];
		$extraCheck_ad = $check['ad'];
		$extraCheck_ad_top_acc = $check['ad_top_acc'];
		$extraCheck_sj = $check['sj'];
		$extraCheck_sj_s = $check['sj_s'];
		
		
		// fcdc will display only Payable (V) & Recievable (C)
		$extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C' ) ";
		$extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C' ) ";

    $list_value = $this->sub_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$this->ddlReport,"fcdc");

    $accounts = explode("-",$this->ddlSubAccount);
    $data['list_value1'] = $this->sub_report_model->accountList($this->ddlAccount,$accounts[0],$list_value);
    $data['fromDate'] = $this->txtFromDate;
    $data['toDate'] = $this->txtToDate;
 
	
//                $data['list_value']  = $this->sub_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,$this->ddlReport,"fcdc");

        switch($this->ddlReport)
        {
            case "Detail":
                $page = 'fcdc_detail_report';
                break;

            case "Summary":
                $page = 'fcdc_report';
                $_SESSION[$page . "_pgSz"] = 'A4';
                break;

            case "Outstanding":
                $page = 'fcdc_detail_report';
                break;
        }//end switch

//        $data['list_value'] = $this->sub_report_model->getFCDCReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);
        $mydata = $this->load->view("sub_report/export_files/".$page, $data,true);
        $_SESSION[$page] = $mydata;
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('sub_report/show_issuedBy',$data);
    }


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
