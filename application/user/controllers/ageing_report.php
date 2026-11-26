<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ageing_report extends CI_Controller {



    function __construct()

    {

        parent::__construct();

        $this->load->model('ageing_report_model');

    }



    function showlist()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = AGEINGREPORT;

        $this->btnVal    = REPRINT;

        $this->common_model->check_admin_authorization();



        $data['list_div_value']      = $this->ageing_report_model->getActiveDivision();

        $data['list_loc_value']      = $this->ageing_report_model->getActiveLocations();

        $data['list_acc_group']      = $this->ageing_report_model->getAccountGroup();

        $data['list_customer_value'] = $this->ageing_report_model->getActiveCustomer();

        $data['list_acc_value']      = $this->ageing_report_model->getActiveAcName();

        $data['list_sub_acc']        = $this->ageing_report_model->getActiveSubAccounts();

        $data['list_emp_value']      = $this->ageing_report_model->getActiveEmployee();

        $data['list_agent']          = $this->ageing_report_model->getAgentAccounts();



        if($this->session->userdata('pageName') == $this->uri->segment(1))

        {

            $this->ddlDivision   = $this->session->userdata('ddlDivision');

            $this->ddlLocation   = $this->session->userdata('ddlLocation');

            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

            $this->txtToDate     = $this->session->userdata('txtToDate');

            $this->txtFromDate   = $this->session->userdata('txtFromDate');

            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');

            $this->ddlAccount    = $this->session->userdata('ddlAccount');

            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

            $this->tableType     = $this->session->userdata('tableType');

            $this->ddlEmployee   = $this->session->userdata('ddlEmployee');

            $this->ddlReport     = $this->session->userdata('ddlReport');

            $this->txtSlab1      = $this->session->userdata('txtSlab1');

            $this->txtSlab2      = $this->session->userdata('txtSlab2');

            $this->txtSlab3      = $this->session->userdata('txtSlab3');

            $this->txtSlab4      = $this->session->userdata('txtSlab4');

            $this->pageName      = $this->session->userdata('pageName');

        }

        else

        {

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('ddlAccGroup');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

            $this->session->unset_userdata('tableType');

            $this->session->unset_userdata('ddlEmployee');

            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('txtSlab1');

            $this->session->unset_userdata('txtSlab2');

            $this->session->unset_userdata('txtSlab3');

            $this->session->unset_userdata('txtSlab4');

            $this->session->unset_userdata('pageName');

        }



        if($this->txtSlab1 == 0) $this->txtSlab1 = 30;

        if($this->txtSlab2 == 0) $this->txtSlab2 = 60;

        if($this->txtSlab3 == 0) $this->txtSlab3 = 90;

        if($this->txtSlab4 == 0) $this->txtSlab4 = 120;



        $this->load->view('general/template_top');

        $this->load->view('ageing_report/ageing_report',$data);

        $this->load->view('general/template_bottom');

    }//end function



    function filterDivision()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();



        $this->ddlDivision = $this->input->post('ddlDivision');

        $this->ddlLocation = $this->input->post('ddlLocation');



        $data['list_loc_value'] = $this->ageing_report_model->filterLocation($this->ddlDivision);

        $this->load->view('ageing_report/show_location',$data);

    }//end function



    function filterAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccGroup');



        $this->ddlAccount    = $this->session->userdata('ddlAccount');



        $data['list_acc_value'] = $this->ageing_report_model->filterAccount($accName);

        $this->load->view('ageing_report/show_account', $data);

    }//end function



    function filterSubAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccount');



        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

        $this->tableType = $this->session->userdata('tableType');



        $data['list_sub_acc'] = $this->ageing_report_model->filterSubAccount($accName);

        $this->load->view('ageing_report/show_sub', $data);

    }//end function



    function getReceivable()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = AGEINGREPORT;



        $this->common_model->check_admin_authorization();



        $this->session->unset_userdata('ddlDivision');

        $this->session->unset_userdata('ddlLocation');

        $this->session->unset_userdata('ddlLocation2');

        $this->session->unset_userdata('txtToDate');

        $this->session->unset_userdata('txtFromDate');

        $this->session->unset_userdata('ddlAccGroup');

        $this->session->unset_userdata('ddlAccount');

        $this->session->unset_userdata('ddlSubAccount');

        $this->session->unset_userdata('tableType');

        $this->session->unset_userdata('ddlEmployee');

        $this->session->unset_userdata('ddlReport');

        $this->session->unset_userdata('txtSlab1');

        $this->session->unset_userdata('txtSlab2');

        $this->session->unset_userdata('txtSlab3');

        $this->session->unset_userdata('txtSlab4');

        $this->session->unset_userdata('pageName');



        $this->ddlDivision       = $this->input->post('ddlDivision');

        $this->ddlLocation       = $this->input->post('ddlLocation');

        $this->ddlLocation2      = $this->input->post('ddlLocation2');

        $this->txtToDate         = $this->input->post('txtToDate');

        $this->txtFromDate       = $this->input->post('txtFromDate');

        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');

        $this->ddlAccount        = $this->input->post('ddlAccount');

        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');

        $this->tableType         = $this->input->post('tableType');

        $this->ddlEmployee       = $this->input->post('ddlEmployee');

        $this->ddlReport         = $this->input->post('ddlReport');

        $this->txtSlab1          = $this->input->post('txtSlab1');

        $this->txtSlab2          = $this->input->post('txtSlab2');

        $this->txtSlab3          = $this->input->post('txtSlab3');

        $this->txtSlab4          = $this->input->post('txtSlab4');



        if(empty($this->txtToDate)) $this->txtToDate = date('d-m-Y'); 



        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));

        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));

        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));

        $this->session->set_userdata('txtToDate',$this->input->post('txtToDate'));

        $this->session->set_userdata('txtFromDate',$this->input->post('txtFromDate'));

        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));

        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));

        $this->session->set_userdata('ddlSubAccount',$this->input->post('ddlSubAccount'));

        $this->session->set_userdata('tableType',$this->input->post('tableType'));

        $this->session->set_userdata('ddlEmployee',$this->input->post('ddlEmployee'));

        $this->session->set_userdata('ddlReport',$this->input->post('ddlReport'));

        $this->session->set_userdata('txtSlab1',$this->input->post('txtSlab1'));

        $this->session->set_userdata('txtSlab2',$this->input->post('txtSlab2'));

        $this->session->set_userdata('txtSlab3',$this->input->post('txtSlab3'));

        $this->session->set_userdata('txtSlab4',$this->input->post('txtSlab4'));

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

            $extraCheck_sj = " AND sc.nDivision='".$this->ddlDivision."'";

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

            $extraCheck_sj .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";

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

            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_sj .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

        }//end if



        if ($this->txtToDate != '') {

            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_sj .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";

                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";

                endif;

            endif;

        }//end if



        if($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):

            $data['list_value'] = $this->ageing_report_model->filterAllocation($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        else:

            $data['list_value'] = $this->ageing_report_model->filterAllocation2($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        endif;

        $data['fromDate'] = $this->txtFromDate;

        $data['toDate'] = $this->txtToDate;

*/



    $extra_check = $this->ageing_report_model->get_checks();

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

    $extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C' ,'A') ";

    $extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C','A' ) ";

    $extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C','A' ) ";

    $extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C','A' ) ";

    $extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C','A' ) ";

    $extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C' ,'A') ";

    $extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C','A' ) ";

    $extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C' ,'A') ";

    $extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C' ,'A') ";



    $list_value  = $this->ageing_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Receivable");



   $data['list_value_new'] = $this->ageing_report_model->accountlist($this->ddlAccount,$this->ddlSubAccount,$list_value);

//echo " -------------- " . print_r($list_value);

//echo '******************* ' .print_r($data['list_value']);

    $data['fromDate'] = $this->txtFromDate;

    $data['toDate'] = $this->txtToDate;

    $data['slab1'] = $this->txtSlab1;

    $data['slab2'] = $this->txtSlab2;

    $data['slab3'] = $this->txtSlab3;

    $data['slab4'] = $this->txtSlab4;





        $mydata = $this->load->view("ageing_report/export_files/receivable_report", $data,true);

        $_SESSION['receivable_ageing_report'] = $mydata;



        $this->load->view('ageing_report/receivable_report', $data);

    }//end function
	
	
	function getParentCustomer()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = ParentCustomer;



        $this->common_model->check_admin_authorization();



        $this->session->unset_userdata('ddlDivision');

        $this->session->unset_userdata('ddlLocation');

        $this->session->unset_userdata('ddlLocation2');

        $this->session->unset_userdata('txtToDate');

        $this->session->unset_userdata('txtFromDate');

        $this->session->unset_userdata('ddlAccGroup');

        $this->session->unset_userdata('ddlCustomer');
		$this->session->unset_userdata('ddlAccount');

        $this->session->unset_userdata('ddlSubAccount');

        $this->session->unset_userdata('tableType');

        $this->session->unset_userdata('ddlEmployee');

        $this->session->unset_userdata('ddlReport');

        $this->session->unset_userdata('txtSlab1');

        $this->session->unset_userdata('txtSlab2');

        $this->session->unset_userdata('txtSlab3');

        $this->session->unset_userdata('txtSlab4');

        $this->session->unset_userdata('pageName');



        $this->ddlDivision       = $this->input->post('ddlDivision');

        $this->ddlLocation       = $this->input->post('ddlLocation');

        $this->ddlLocation2      = $this->input->post('ddlLocation2');

        $this->txtToDate         = $this->input->post('txtToDate');

        $this->txtFromDate       = $this->input->post('txtFromDate');

        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');
		$this->ddlCustomer       = $this->input->post('ddlCustomer');

        $this->ddlAccount        = $this->input->post('ddlAccount');

        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');

        $this->tableType         = $this->input->post('tableType');

        $this->ddlEmployee       = $this->input->post('ddlEmployee');

        $this->ddlReport         = $this->input->post('ddlReport');

        $this->txtSlab1          = $this->input->post('txtSlab1');

        $this->txtSlab2          = $this->input->post('txtSlab2');

        $this->txtSlab3          = $this->input->post('txtSlab3');

        $this->txtSlab4          = $this->input->post('txtSlab4');



        if(empty($this->txtToDate)) $this->txtToDate = date('d-m-Y'); 



        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));

        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));

        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));

        $this->session->set_userdata('txtToDate',$this->input->post('txtToDate'));

        $this->session->set_userdata('txtFromDate',$this->input->post('txtFromDate'));

        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));

        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));

        $this->session->set_userdata('ddlSubAccount',$this->input->post('ddlSubAccount'));

        $this->session->set_userdata('tableType',$this->input->post('tableType'));

        $this->session->set_userdata('ddlEmployee',$this->input->post('ddlEmployee'));

        $this->session->set_userdata('ddlReport',$this->input->post('ddlReport'));

        $this->session->set_userdata('txtSlab1',$this->input->post('txtSlab1'));

        $this->session->set_userdata('txtSlab2',$this->input->post('txtSlab2'));

        $this->session->set_userdata('txtSlab3',$this->input->post('txtSlab3'));

        $this->session->set_userdata('txtSlab4',$this->input->post('txtSlab4'));

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

            $extraCheck_sj = " AND sc.nDivision='".$this->ddlDivision."'";

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

            $extraCheck_sj .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";

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

            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_sj .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

        }//end if



        if ($this->txtToDate != '') {

            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_sj .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";

                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";

                endif;

            endif;

        }//end if



        if($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):

            $data['list_value'] = $this->ageing_report_model->filterAllocation($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        else:

            $data['list_value'] = $this->ageing_report_model->filterAllocation2($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        endif;

        $data['fromDate'] = $this->txtFromDate;

        $data['toDate'] = $this->txtToDate;

*/



    $extra_check = $this->ageing_report_model->get_checks();

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



    $list_value  = $this->ageing_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Summary");



   $data['list_value_new'] = $this->ageing_report_model->accountlist($this->ddlAccount,$this->ddlSubAccount,$list_value);

//echo " -------------- " . print_r($list_value);

//echo '******************* ' .print_r($data['list_value']);

    $data['fromDate'] = $this->txtFromDate;

    $data['toDate'] = $this->txtToDate;

    $data['slab1'] = $this->txtSlab1;

    $data['slab2'] = $this->txtSlab2;

    $data['slab3'] = $this->txtSlab3;

    $data['slab4'] = $this->txtSlab4;





        $mydata = $this->load->view("ageing_report/export_files/parent_receivable_report", $data,true);

        $_SESSION['receivable_ageing_report'] = $mydata;



        $this->load->view('ageing_report/parent_receivable_report', $data);

    }//end function



    function getReceivableDetail()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = AGEINGREPORT;



        $this->common_model->check_admin_authorization();



        $this->session->unset_userdata('ddlDivision');

        $this->session->unset_userdata('ddlLocation');

        $this->session->unset_userdata('ddlLocation2');

        $this->session->unset_userdata('txtToDate');

        $this->session->unset_userdata('txtFromDate');

        $this->session->unset_userdata('ddlAccGroup');

        $this->session->unset_userdata('ddlAccount');

        $this->session->unset_userdata('ddlSubAccount');

        $this->session->unset_userdata('tableType');

        $this->session->unset_userdata('ddlEmployee');

        $this->session->unset_userdata('ddlReport');

        $this->session->unset_userdata('txtSlab1');

        $this->session->unset_userdata('txtSlab2');

        $this->session->unset_userdata('txtSlab3');

        $this->session->unset_userdata('txtSlab4');

        $this->session->unset_userdata('pageName');



        $this->ddlDivision       = $this->input->post('ddlDivision');

        $this->ddlLocation       = $this->input->post('ddlLocation');

        $this->ddlLocation2      = $this->input->post('ddlLocation2');

        $this->txtToDate         = $this->input->post('txtToDate');

        $this->txtFromDate       = $this->input->post('txtFromDate');

        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');

        $this->ddlAccount        = $this->input->post('ddlAccount');

        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');

        $this->tableType         = $this->input->post('tableType');

        $this->ddlEmployee       = $this->input->post('ddlEmployee');

        $this->ddlReport         = $this->input->post('ddlReport');

        $this->txtSlab1          = $this->input->post('txtSlab1');

        $this->txtSlab2          = $this->input->post('txtSlab2');

        $this->txtSlab3          = $this->input->post('txtSlab3');

        $this->txtSlab4          = $this->input->post('txtSlab4');



        if(empty($this->txtToDate)) $this->txtToDate = date('d-m-Y'); 



        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));

        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));

        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));

        $this->session->set_userdata('txtToDate',$this->input->post('txtToDate'));

        $this->session->set_userdata('txtFromDate',$this->input->post('txtFromDate'));

        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));

        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));

        $this->session->set_userdata('ddlSubAccount',$this->input->post('ddlSubAccount'));

        $this->session->set_userdata('tableType',$this->input->post('tableType'));

        $this->session->set_userdata('ddlEmployee',$this->input->post('ddlEmployee'));

        $this->session->set_userdata('ddlReport',$this->input->post('ddlReport'));

        $this->session->set_userdata('txtSlab1',$this->input->post('txtSlab1'));

        $this->session->set_userdata('txtSlab2',$this->input->post('txtSlab2'));

        $this->session->set_userdata('txtSlab3',$this->input->post('txtSlab3'));

        $this->session->set_userdata('txtSlab4',$this->input->post('txtSlab4'));

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

            $extraCheck_sj = " AND sc.nDivision='".$this->ddlDivision."'";

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

            $extraCheck_sj .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";

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

            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_sj .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

        }//end if



        if ($this->txtToDate != '') {

            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_sj .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";

                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";

                endif;

            endif;

        }//end if



        if($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):

            $data['list_value'] = $this->ageing_report_model->filterAllocation($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        else:

            $data['list_value'] = $this->ageing_report_model->filterAllocation2($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        endif;

*/



    $extra_check = $this->ageing_report_model->get_checks();

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

    $extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C','A','AG' ) ";

    $extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C' ,'A','AG') ";

    $extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C' ,'A','AG') ";

    $extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C','A' ,'AG') ";

    $extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C','A','AG' ) ";

    $extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C','A' ,'AG') ";

    $extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C','A' ,'AG') ";

    $extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C','A' ,'AG') ";

    $extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C','A','AG' ) ";



    $list_value  = $this->ageing_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Receivable");



    $data['list_value'] = $this->ageing_report_model->accountlist($this->ddlAccount,$this->ddlSubAccount,$list_value);

    $data['fromDate'] = $this->txtFromDate;

    $data['toDate'] = $this->txtToDate;

    $data['slab1'] = $this->txtSlab1;

    $data['slab2'] = $this->txtSlab2;

    $data['slab3'] = $this->txtSlab3;

    $data['slab4'] = $this->txtSlab4;





    $mydata = $this->load->view("ageing_report/export_files/receivable_detail_report", $data,true);

    $_SESSION['receivable_ageing_detail_report'] = $mydata;



        $this->load->view('ageing_report/receivable_detail_report', $data);

    }//end function

	function getReceivableDetail1()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = AGEINGREPORT;



        $this->common_model->check_admin_authorization();



        $this->session->unset_userdata('ddlDivision');

        $this->session->unset_userdata('ddlLocation');

        $this->session->unset_userdata('ddlLocation2');

        $this->session->unset_userdata('txtToDate');

        $this->session->unset_userdata('txtFromDate');

        $this->session->unset_userdata('ddlAccGroup');

        $this->session->unset_userdata('ddlAccount');

        $this->session->unset_userdata('ddlSubAccount');

        $this->session->unset_userdata('tableType');

        $this->session->unset_userdata('ddlEmployee');

        $this->session->unset_userdata('ddlReport');

        $this->session->unset_userdata('txtSlab1');

        $this->session->unset_userdata('txtSlab2');

        $this->session->unset_userdata('txtSlab3');

        $this->session->unset_userdata('txtSlab4');

        $this->session->unset_userdata('pageName');



        $this->ddlDivision       = $this->input->post('ddlDivision');

        $this->ddlLocation       = $this->input->post('ddlLocation');

        $this->ddlLocation2      = $this->input->post('ddlLocation2');

        $this->txtToDate         = $this->input->post('txtToDate');

        $this->txtFromDate       = $this->input->post('txtFromDate');

        $this->ddlAccGroup       = $this->input->post('ddlAccGroup');

        $this->ddlAccount        = $this->input->post('ddlAccount');

        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');

        $this->tableType         = $this->input->post('tableType');

        $this->ddlEmployee       = $this->input->post('ddlEmployee');

        $this->ddlReport         = $this->input->post('ddlReport');

        $this->txtSlab1          = $this->input->post('txtSlab1');

        $this->txtSlab2          = $this->input->post('txtSlab2');

        $this->txtSlab3          = $this->input->post('txtSlab3');

        $this->txtSlab4          = $this->input->post('txtSlab4');



        if(empty($this->txtToDate)) $this->txtToDate = date('d-m-Y'); 



        $this->session->set_userdata('ddlDivision',$this->input->post('ddlDivision'));

        $this->session->set_userdata('ddlLocation',$this->input->post('ddlLocation'));

        $this->session->set_userdata('ddlLocation2',$this->input->post('ddlLocation2'));

        $this->session->set_userdata('txtToDate',$this->input->post('txtToDate'));

        $this->session->set_userdata('txtFromDate',$this->input->post('txtFromDate'));

        $this->session->set_userdata('ddlAccGroup',$this->input->post('ddlAccGroup'));

        $this->session->set_userdata('ddlAccount',$this->input->post('ddlAccount'));

        $this->session->set_userdata('ddlSubAccount',$this->input->post('ddlSubAccount'));

        $this->session->set_userdata('tableType',$this->input->post('tableType'));

        $this->session->set_userdata('ddlEmployee',$this->input->post('ddlEmployee'));

        $this->session->set_userdata('ddlReport',$this->input->post('ddlReport'));

        $this->session->set_userdata('txtSlab1',$this->input->post('txtSlab1'));

        $this->session->set_userdata('txtSlab2',$this->input->post('txtSlab2'));

        $this->session->set_userdata('txtSlab3',$this->input->post('txtSlab3'));

        $this->session->set_userdata('txtSlab4',$this->input->post('txtSlab4'));

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

            $extraCheck_sj = " AND sc.nDivision='".$this->ddlDivision."'";

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

            $extraCheck_sj .= " AND (sc.vLocation='".$this->ddlLocation."' OR sc.vLocation='".$this->ddlLocation2."')";

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

            $extraCheck_cr .= " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cp .= " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bb .= " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_bp .= " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_jv .= " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_cv .= " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_dv .= " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_sj .= " AND sc.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ac .= " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

            $extraCheck_ad .= " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

        }//end if



        if ($this->txtToDate != '') {

            $extraCheck_cr .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cp .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bb .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_bp .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_jv .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_cv .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_dv .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_sj .= " AND sc.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ac .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

            $extraCheck_ad .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";

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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_ad .= " AND (ade.vAcSubCode='".$this->ddlAccount."' OR ade.vAcSubCode='".$this->ddlAccount."')";



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

                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";

                    $extraCheck_ad .= " AND ade.vACode='".$this->ddlSubAccount."'";

                endif;

            endif;

        }//end if



        if($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):

            $data['list_value'] = $this->ageing_report_model->filterAllocation($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        else:

            $data['list_value'] = $this->ageing_report_model->filterAllocation2($this->ddlSubAccount,$this->ddlAccount,$this->tableType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad);

        endif;

*/



    $extra_check = $this->ageing_report_model->get_checks();

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

    $extraCheck_cr .= " AND crd.vTable IN ( 'V' , 'C','A','O','AG' ) ";

    $extraCheck_cp .= " AND cpd.vTable IN ( 'V' , 'C' ,'A','O','AG') ";

    $extraCheck_bb .= " AND bbd.vTable IN ( 'V' , 'C' ,'A','O','AG') ";

    $extraCheck_bp .= " AND bpd.vTable IN ( 'V' , 'C','A','O' ,'AG') ";

    $extraCheck_jv .= " AND jvd.vTable IN ( 'V' , 'C','A','O','AG' ) ";

    $extraCheck_ac .= " AND acd.vTable IN ( 'V' , 'C','A','O','AG' ) ";

    $extraCheck_ad .= " AND ade.vTable IN ( 'V' , 'C','A' ,'O','AG') ";

    $extraCheck_cv .= " AND cvd.vTable IN ( 'V' , 'C','A','O','AG' ) ";

    $extraCheck_dv .= " AND dvd.vTable IN ( 'V' , 'C','A' ,'O','AG') ";



    $list_value  = $this->ageing_report_model->getDetailReport($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_sj_s,$extraCheck_ac,$extraCheck_ac_top_acc,$extraCheck_ad,$extraCheck_ad_top_acc,"Receivable");



    $data['list_value'] = $this->ageing_report_model->accountlist($this->ddlAccount,$this->ddlSubAccount,$list_value);

    $data['fromDate'] = $this->txtFromDate;

    $data['toDate'] = $this->txtToDate;

    $data['slab1'] = $this->txtSlab1;

    $data['slab2'] = $this->txtSlab2;

    $data['slab3'] = $this->txtSlab3;

    $data['slab4'] = $this->txtSlab4;





    $mydata = $this->load->view("ageing_report/export_files/receivable_detail_report1", $data,true);

    $_SESSION['receivable_ageing_detail_report1'] = $mydata;



        $this->load->view('ageing_report/receivable_detail_report1', $data);

    }//end function



}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

