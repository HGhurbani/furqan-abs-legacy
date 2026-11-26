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
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->ac_report_model->filterAccount($accName);
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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

        /*if ($this->txtToYear != '' && $this->txtFromYear !='') {
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
        }//end if*/

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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']         = $this->ddlAccount;
        $data['SubName']      = $SubName;
        $data['SubCode']      = $SubCode;
        session_start();
        $_SESSION['ddlAccount']     = $this->ddlAccount;
        $_SESSION['SubName']  = $SubName;
        $_SESSION['SubCode']  = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

		$this->session->unset_userdata('newDebit');
        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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

        $detVal  = $this->ac_report_model->getAllFinanceMainAccounts();
        if (!empty($this->ddlAccount)):

            if(!in_array($this->ddlAccount,$detVal)):
                $data['list_value']  = $this->ac_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
            else:
                $data['list_value']  = $this->ac_report_model->getDetailReportFilter($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
            endif;
        else:
            $data['list_value']  = $this->ac_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);
        endif;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('ac_report/detail_report2', $data);
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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

            $openBal_cr_dt = " AND cr.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .$this->txtFromDate2. "' ";
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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']         = $this->ddlAccount;
        $data['SubName']      = $SubName;
        $data['SubCode']      = $SubCode;
        session_start();
        $_SESSION['ddlAccount']     = $this->ddlAccount;
        $_SESSION['SubName']  = $SubName;
        $_SESSION['SubCode']  = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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

        $data['list_value'] = $this->ac_report_model->getDailyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

            $openBal_cr_dt = " AND cr.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .$this->txtFromDate2. "' ";
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

        /*if ($this->txtToYear != '' && $this->txtFromYear !='') {
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
        }//end if*/

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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']         = $this->ddlAccount;
        $data['SubName']      = $SubName;
        $data['SubCode']      = $SubCode;
        session_start();
        $_SESSION['ddlAccount']     = $this->ddlAccount;
        $_SESSION['SubName']  = $SubName;
        $_SESSION['SubCode']  = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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

        $data['list_value'] = $this->ac_report_model->getWeeklyReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

		$date_weeks         = $this->ac_report_model->getNoWeeks($this->txtFromDate,$this->txtToDate);
		$data['no_weeks']   = $date_weeks['weeknos'];
		$data['start_date'] = $date_weeks['startDate'];

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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

            $openBal_cr_dt = " AND cr.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .$this->txtFromDate2. "' ";
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

        /*if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
        }//end if*/

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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']         = $this->ddlAccount;
        $data['SubName']      = $SubName;
        $data['SubCode']      = $SubCode;
        session_start();
        $_SESSION['ddlAccount']     = $this->ddlAccount;
        $_SESSION['SubName']  = $SubName;
        $_SESSION['SubCode']  = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

            $openBal_cr_dt = " AND cr.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .$this->txtFromDate2. "' ";
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

        /*if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
        }//end if*/

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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']     = $this->ddlAccount;
        $data['SubName']        = $SubName;
        $data['SubCode']        = $SubCode;
        session_start();
        $_SESSION['ddlAccount'] = $this->ddlAccount;
        $_SESSION['SubName']    = $SubName;
        $_SESSION['SubCode']    = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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
            $extraCheck_sj = " sj.nDivision='".$this->ddlDivision."'";
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

            $openBal_cr_dt = " AND cr.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cp_dt = " AND cp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_bb_dt = " AND bb.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_bp_dt = " AND bp.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_jv_dt = " AND jv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_cv_dt = " AND cv.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_dv_dt = " AND dv.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_sj_dt = " AND sj.dDate <= '" .$this->txtFromDate2. "' ";
            $openBal_ac_dt = " AND ac.dDate <= '" .$this->txtFromDate2 . "' ";
            $openBal_ad_dt = " AND ad.dDate <= '" .$this->txtFromDate2. "' ";
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

        /*if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND YEAR(cr.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cr.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND YEAR(cp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND YEAR(bb.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bb.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND YEAR(bp.dDate) >= '" .$this->txtFromYear . "' AND YEAR(bp.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND YEAR(jv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(jv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND YEAR(cv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(cv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND YEAR(dv.dDate) >= '" .$this->txtFromYear . "' AND YEAR(dv.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_sj .= " AND YEAR(sj.dDate) >= '" .$this->txtFromYear . "' AND YEAR(sj.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND YEAR(ac.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ac.dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND YEAR(ad.dDate) >= '" .$this->txtFromYear . "' AND YEAR(ad.dDate) <= '" . $this->txtToYear . "'";
        }//end if*/

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
                $extraCheck_ac .= " AND (acd.vACode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (ade.vACode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj_special = " HAVING subCode !=''";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
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
                $closeId        = $this->ddlAccount.'-'.$this->tableType;
                $extraCheck_sj .= " OR sjd.vClosedId LIKE '".$closeId." %' OR sjd.vClosedId LIKE '% ".$closeId."'
                                        OR sjd.vClosedId LIKE '%".$closeId."%')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_sj .= " AND sjd.nDrAccount = '".$this->ddlSubAccount."' AND sjd.vTable = '".$this->tableType."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->tableType)
        {
            case "C":
                $SubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlSubAccount."'");
                break;

            case "V":
                $SubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlSubAccount."'");
                break;

            case "A":
                $SubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlSubAccount."'");
                break;

            case "O":
                $SubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->ddlSubAccount."'");
                $SubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlSubAccount."'");
                break;
        }

        $data['ddlAccount']   = $this->ddlAccount;
        $data['SubName']      = $SubName;
        $data['SubCode']      = $SubCode;
        session_start();
        $_SESSION['ddlAccount']     = $this->ddlAccount;
        $_SESSION['SubName']  = $SubName;
        $_SESSION['SubCode']  = $SubCode;

        $open_cr = $extraCheck_cr.$openBal_cr_dt;
        $open_cp = $extraCheck_cp.$openBal_cp_dt;
        $open_bb = $extraCheck_bb.$openBal_bb_dt;
        $open_bp = $extraCheck_bp.$openBal_bp_dt;
        $open_jv = $extraCheck_jv.$openBal_jv_dt;
        $open_cv = $extraCheck_cv.$openBal_cv_dt;
        $open_dv = $extraCheck_dv.$openBal_dv_dt;
        $open_sj = $extraCheck_sj.$openBal_sj_dt;
        $open_ac = $extraCheck_ac.$openBal_ac_dt;
        $open_ad = $extraCheck_ad.$openBal_ad_dt;

        $data['open_list_value']  = $this->ac_report_model->getOpeningBalance($open_cr,$open_cp,$open_bb,$open_bp,$open_jv,$open_cv,$open_dv,$open_sj,$open_ac,$open_ad,$extraCheck_sj_special);
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
