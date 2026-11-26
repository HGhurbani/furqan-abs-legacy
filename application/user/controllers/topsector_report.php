<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Topsector_report extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('topsector_report_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = TOPTENSECTOR;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->topsector_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->topsector_report_model->getActiveLocation();
        $data['list_airline_value'] = $this->topsector_report_model->getActiveAirline();
        $data['list_payable']       = $this->topsector_report_model->getPayableAccount();
        $data['list_emp_value']     = $this->topsector_report_model->getActiveEmployee();
        $data['list_acc_value']     = $this->topsector_report_model->getActiveAccounts();
        $data['list_sub_acc']       = $this->topsector_report_model->getSubAccountValues();
        $data['list_gds_value']     = $this->topsector_report_model->getActiveGDS();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision      = $this->session->userdata('ddlDivision');
            $this->ddlLocation      = $this->session->userdata('ddlLocation');
            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');
            $this->txtToDate        = $this->session->userdata('txtToDate');
            $this->txtFromDate      = $this->session->userdata('txtFromDate');
            $this->ddlIssued        = $this->session->userdata('ddlIssued');
            $this->ddlAirline       = $this->session->userdata('ddlAirline');
            $this->txtSector1       = $this->session->userdata('txtSector1');
            $this->txtSector2       = $this->session->userdata('txtSector2');
            $this->txtSector3       = $this->session->userdata('txtSector3');
            $this->txtSector4       = $this->session->userdata('txtSector4');
            $this->txtSector5       = $this->session->userdata('txtSector5');
            $this->txtSector6       = $this->session->userdata('txtSector6');
            $this->txtSector7       = $this->session->userdata('txtSector7');
            $this->ddlGDS           = $this->session->userdata('ddlGDS');
            $this->txtDestination   = $this->session->userdata('txtDestination');
            $this->ddlHotel         = $this->session->userdata('ddlHotel');
            $this->txtCheckIn       = $this->session->userdata('txtCheckIn');
            $this->txtCheckOut      = $this->session->userdata('txtCheckOut');
            $this->ddlPayableAccount = $this->session->userdata('ddlPayableAccount');
            $this->ddlVendor        = $this->session->userdata('ddlVendor');
            $this->ddlAccount       = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount    = $this->session->userdata('ddlSubAccount');
            $this->ddlRefund        = $this->session->userdata('ddlRefund');
            $this->ddlReport        = $this->session->userdata('ddlReport');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlIssued');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('txtSector1');
            $this->session->unset_userdata('txtSector2');
            $this->session->unset_userdata('txtSector3');
            $this->session->unset_userdata('txtSector4');
            $this->session->unset_userdata('txtSector5');
            $this->session->unset_userdata('txtSector6');
            $this->session->unset_userdata('txtSector7');
            $this->session->unset_userdata('ddlGDS');
            $this->session->unset_userdata('txtDestination');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
        }

        $this->load->view('general/template_top');
        $this->load->view('topsector_report/topsector_report',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->topsector_report_model->filterLocation($this->ddlDivision);
        $this->load->view('topsector_report/show_location',$data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccount');

        $data['list_sub_acc'] = $this->topsector_report_model->filterSubAccount($accName);
        $this->load->view('topsector_report/show_sub',$data);
    }//end function

    function showSectorReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle  = TOPTENSECTOR;
        $this->btnVal     = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

		if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlIssued');
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('txtSector1');
            $this->session->unset_userdata('txtSector2');
            $this->session->unset_userdata('txtSector3');
            $this->session->unset_userdata('txtSector4');
            $this->session->unset_userdata('txtSector5');
            $this->session->unset_userdata('txtSector6');
            $this->session->unset_userdata('txtSector7');
            $this->session->unset_userdata('ddlGDS');
            $this->session->unset_userdata('txtDestination');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_x .= " AND xo.dDate >= '" .$this->txtFromDate . "' AND xo.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->txtSector1)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector1." %' OR td.vSector1 LIKE '% ".$this->txtSector1."'
								OR td.vSector1 LIKE '%".$this->txtSector1."%' OR td.vSector12 LIKE '".$this->txtSector1." %'
								OR td.vSector12 LIKE '% ".$this->txtSector1."' OR td.vSector12 LIKE '%".$this->txtSector1."%' OR
								td.vSector2 LIKE '".$this->txtSector1." %' OR td.vSector2 LIKE '% ".$this->txtSector1."'
								OR td.vSector2 LIKE '%".$this->txtSector1."%' OR td.vSector22 LIKE '".$this->txtSector1." %'
								OR td.vSector22 LIKE '% ".$this->txtSector1."' OR td.vSector22 LIKE '%".$this->txtSector1."%' OR
								td.vSector3 LIKE '".$this->txtSector1." %' OR td.vSector3 LIKE '% ".$this->txtSector1."'
								OR td.vSector3 LIKE '%".$this->txtSector1."%' OR td.vSector33 LIKE '".$this->txtSector1." %' OR
								td.vSector33 LIKE '% ".$this->txtSector1."'	OR td.vSector33 LIKE '%".$this->txtSector1."%'  OR
								td.vSector4 LIKE '".$this->txtSector1." %' OR td.vSector4 LIKE '% ".$this->txtSector1."' OR td.vSector4
								LIKE '%".$this->txtSector1."%' OR td.vSector44 LIKE '".$this->txtSector1." %' OR td.vSector44
								LIKE '% ".$this->txtSector1."' OR td.vSector44 LIKE '%".$this->txtSector1."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector1." %' OR xd.vSector LIKE '% ".$this->txtSector1."'
								OR xd.vSector LIKE '%".$this->txtSector1."%' OR xd.vSector2 LIKE '".$this->txtSector1." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector1."'OR xd.vSector2 LIKE '%".$this->txtSector1."%') ";
        }//end if

        if (!empty($this->txtSector2)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector2." %' OR td.vSector1 LIKE '% ".$this->txtSector2."'
								OR td.vSector1 LIKE '%".$this->txtSector2."%' OR td.vSector12 LIKE '".$this->txtSector2." %'
								OR td.vSector12 LIKE '% ".$this->txtSector2."' OR td.vSector12 LIKE '%".$this->txtSector2."%' OR
								td.vSector2 LIKE '".$this->txtSector2." %' OR td.vSector2 LIKE '% ".$this->txtSector2."'
								OR td.vSector2 LIKE '%".$this->txtSector2."%' OR td.vSector22 LIKE '".$this->txtSector2." %'
								OR td.vSector22 LIKE '% ".$this->txtSector2."' OR td.vSector22 LIKE '%".$this->txtSector2."%' OR
								td.vSector3 LIKE '".$this->txtSector2." %' OR td.vSector3 LIKE '% ".$this->txtSector2."'
								OR td.vSector3 LIKE '%".$this->txtSector2."%' OR td.vSector33 LIKE '".$this->txtSector2." %' OR
								td.vSector33 LIKE '% ".$this->txtSector2."'	OR td.vSector33 LIKE '%".$this->txtSector2."%'  OR
								td.vSector4 LIKE '".$this->txtSector2." %' OR td.vSector4 LIKE '% ".$this->txtSector2."' OR td.vSector4
								LIKE '%".$this->txtSector2."%' OR td.vSector44 LIKE '".$this->txtSector2." %' OR td.vSector44
								LIKE '% ".$this->txtSector2."' OR td.vSector44 LIKE '%".$this->txtSector2."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector2." %' OR xd.vSector LIKE '% ".$this->txtSector2."'
								OR xd.vSector LIKE '%".$this->txtSector2."%' OR xd.vSector2 LIKE '".$this->txtSector2." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector2."'OR xd.vSector2 LIKE '%".$this->txtSector2."%') ";
        }//end if

        if (!empty($this->txtSector3)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector3." %' OR td.vSector1 LIKE '% ".$this->txtSector3."'
								OR td.vSector1 LIKE '%".$this->txtSector3."%' OR td.vSector12 LIKE '".$this->txtSector3." %'
								OR td.vSector12 LIKE '% ".$this->txtSector3."' OR td.vSector12 LIKE '%".$this->txtSector3."%' OR
								td.vSector2 LIKE '".$this->txtSector3." %' OR td.vSector2 LIKE '% ".$this->txtSector3."'
								OR td.vSector2 LIKE '%".$this->txtSector3."%' OR td.vSector22 LIKE '".$this->txtSector3." %'
								OR td.vSector22 LIKE '% ".$this->txtSector3."' OR td.vSector22 LIKE '%".$this->txtSector3."%' OR
								td.vSector3 LIKE '".$this->txtSector3." %' OR td.vSector3 LIKE '% ".$this->txtSector3."'
								OR td.vSector3 LIKE '%".$this->txtSector3."%' OR td.vSector33 LIKE '".$this->txtSector3." %' OR
								td.vSector33 LIKE '% ".$this->txtSector3."'	OR td.vSector33 LIKE '%".$this->txtSector3."%'  OR
								td.vSector4 LIKE '".$this->txtSector3." %' OR td.vSector4 LIKE '% ".$this->txtSector3."' OR td.vSector4
								LIKE '%".$this->txtSector3."%' OR td.vSector44 LIKE '".$this->txtSector3." %' OR td.vSector44
								LIKE '% ".$this->txtSector3."' OR td.vSector44 LIKE '%".$this->txtSector3."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector3." %' OR xd.vSector LIKE '% ".$this->txtSector3."'
								OR xd.vSector LIKE '%".$this->txtSector3."%' OR xd.vSector2 LIKE '".$this->txtSector3." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector3."'OR xd.vSector2 LIKE '%".$this->txtSector3."%') ";
        }//end if

        if (!empty($this->txtSector4)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector4." %' OR td.vSector1 LIKE '% ".$this->txtSector4."'
								OR td.vSector1 LIKE '%".$this->txtSector4."%' OR td.vSector12 LIKE '".$this->txtSector4." %'
								OR td.vSector12 LIKE '% ".$this->txtSector4."' OR td.vSector12 LIKE '%".$this->txtSector4."%' OR
								td.vSector2 LIKE '".$this->txtSector4." %' OR td.vSector2 LIKE '% ".$this->txtSector4."'
								OR td.vSector2 LIKE '%".$this->txtSector4."%' OR td.vSector22 LIKE '".$this->txtSector4." %'
								OR td.vSector22 LIKE '% ".$this->txtSector4."' OR td.vSector22 LIKE '%".$this->txtSector4."%' OR
								td.vSector3 LIKE '".$this->txtSector4." %' OR td.vSector3 LIKE '% ".$this->txtSector4."'
								OR td.vSector3 LIKE '%".$this->txtSector4."%' OR td.vSector33 LIKE '".$this->txtSector4." %' OR
								td.vSector33 LIKE '% ".$this->txtSector4."'	OR td.vSector33 LIKE '%".$this->txtSector4."%'  OR
								td.vSector4 LIKE '".$this->txtSector4." %' OR td.vSector4 LIKE '% ".$this->txtSector4."' OR td.vSector4
								LIKE '%".$this->txtSector4."%' OR td.vSector44 LIKE '".$this->txtSector4." %' OR td.vSector44
								LIKE '% ".$this->txtSector4."' OR td.vSector44 LIKE '%".$this->txtSector4."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector4." %' OR xd.vSector LIKE '% ".$this->txtSector4."'
								OR xd.vSector LIKE '%".$this->txtSector4."%' OR xd.vSector2 LIKE '".$this->txtSector4." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector4."'OR xd.vSector2 LIKE '%".$this->txtSector4."%') ";
        }//end if

        if (!empty($this->txtSector5)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector5." %' OR td.vSector1 LIKE '% ".$this->txtSector5."'
								OR td.vSector1 LIKE '%".$this->txtSector5."%' OR td.vSector12 LIKE '".$this->txtSector5." %'
								OR td.vSector12 LIKE '% ".$this->txtSector5."' OR td.vSector12 LIKE '%".$this->txtSector5."%' OR
								td.vSector2 LIKE '".$this->txtSector5." %' OR td.vSector2 LIKE '% ".$this->txtSector5."'
								OR td.vSector2 LIKE '%".$this->txtSector5."%' OR td.vSector22 LIKE '".$this->txtSector5." %'
								OR td.vSector22 LIKE '% ".$this->txtSector5."' OR td.vSector22 LIKE '%".$this->txtSector5."%' OR
								td.vSector3 LIKE '".$this->txtSector5." %' OR td.vSector3 LIKE '% ".$this->txtSector5."'
								OR td.vSector3 LIKE '%".$this->txtSector5."%' OR td.vSector33 LIKE '".$this->txtSector5." %' OR
								td.vSector33 LIKE '% ".$this->txtSector5."'	OR td.vSector33 LIKE '%".$this->txtSector5."%'  OR
								td.vSector4 LIKE '".$this->txtSector5." %' OR td.vSector4 LIKE '% ".$this->txtSector5."' OR td.vSector4
								LIKE '%".$this->txtSector5."%' OR td.vSector44 LIKE '".$this->txtSector5." %' OR td.vSector44
								LIKE '% ".$this->txtSector5."' OR td.vSector44 LIKE '%".$this->txtSector5."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector5." %' OR xd.vSector LIKE '% ".$this->txtSector5."'
								OR xd.vSector LIKE '%".$this->txtSector5."%' OR xd.vSector2 LIKE '".$this->txtSector5." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector5."'OR xd.vSector2 LIKE '%".$this->txtSector5."%') ";
        }//end if

        if (!empty($this->txtSector6)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector6." %' OR td.vSector1 LIKE '% ".$this->txtSector6."'
								OR td.vSector1 LIKE '%".$this->txtSector6."%' OR td.vSector12 LIKE '".$this->txtSector6." %'
								OR td.vSector12 LIKE '% ".$this->txtSector6."' OR td.vSector12 LIKE '%".$this->txtSector6."%' OR
								td.vSector2 LIKE '".$this->txtSector6." %' OR td.vSector2 LIKE '% ".$this->txtSector6."'
								OR td.vSector2 LIKE '%".$this->txtSector6."%' OR td.vSector22 LIKE '".$this->txtSector6." %'
								OR td.vSector22 LIKE '% ".$this->txtSector6."' OR td.vSector22 LIKE '%".$this->txtSector6."%' OR
								td.vSector3 LIKE '".$this->txtSector6." %' OR td.vSector3 LIKE '% ".$this->txtSector6."'
								OR td.vSector3 LIKE '%".$this->txtSector6."%' OR td.vSector33 LIKE '".$this->txtSector6." %' OR
								td.vSector33 LIKE '% ".$this->txtSector6."'	OR td.vSector33 LIKE '%".$this->txtSector6."%'  OR
								td.vSector4 LIKE '".$this->txtSector6." %' OR td.vSector4 LIKE '% ".$this->txtSector6."' OR td.vSector4
								LIKE '%".$this->txtSector6."%' OR td.vSector44 LIKE '".$this->txtSector6." %' OR td.vSector44
								LIKE '% ".$this->txtSector6."' OR td.vSector44 LIKE '%".$this->txtSector6."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector6." %' OR xd.vSector LIKE '% ".$this->txtSector6."'
								OR xd.vSector LIKE '%".$this->txtSector6."%' OR xd.vSector2 LIKE '".$this->txtSector6." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector6."'OR xd.vSector2 LIKE '%".$this->txtSector6."%') ";
        }//end if

        if (!empty($this->txtSector7)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector7." %' OR td.vSector1 LIKE '% ".$this->txtSector7."'
								OR td.vSector1 LIKE '%".$this->txtSector7."%' OR td.vSector12 LIKE '".$this->txtSector7." %'
								OR td.vSector12 LIKE '% ".$this->txtSector7."' OR td.vSector12 LIKE '%".$this->txtSector7."%' OR
								td.vSector2 LIKE '".$this->txtSector7." %' OR td.vSector2 LIKE '% ".$this->txtSector7."'
								OR td.vSector2 LIKE '%".$this->txtSector7."%' OR td.vSector22 LIKE '".$this->txtSector7." %'
								OR td.vSector22 LIKE '% ".$this->txtSector7."' OR td.vSector22 LIKE '%".$this->txtSector7."%' OR
								td.vSector3 LIKE '".$this->txtSector7." %' OR td.vSector3 LIKE '% ".$this->txtSector7."'
								OR td.vSector3 LIKE '%".$this->txtSector7."%' OR td.vSector33 LIKE '".$this->txtSector7." %' OR
								td.vSector33 LIKE '% ".$this->txtSector7."'	OR td.vSector33 LIKE '%".$this->txtSector7."%'  OR
								td.vSector4 LIKE '".$this->txtSector7." %' OR td.vSector4 LIKE '% ".$this->txtSector7."' OR td.vSector4
								LIKE '%".$this->txtSector7."%' OR td.vSector44 LIKE '".$this->txtSector7." %' OR td.vSector44
								LIKE '% ".$this->txtSector7."' OR td.vSector44 LIKE '%".$this->txtSector7."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector7." %' OR xd.vSector LIKE '% ".$this->txtSector7."'
								OR xd.vSector LIKE '%".$this->txtSector7."%' OR xd.vSector2 LIKE '".$this->txtSector7." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector7."'OR xd.vSector2 LIKE '%".$this->txtSector7."%') ";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

		session_start();
		$_SESSION['extraCheck_t'] = $extraCheck_t;
		$_SESSION['extraCheck_x'] = $extraCheck_x;

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->topsector_report_model->getSectorReport($extraCheck_t,$extraCheck_x);
                break;

            case 'Sales':
                $data['list_value'] = $this->topsector_report_model->getSectorSalesReport($extraCheck_t,$extraCheck_x);
                break;

            case 'Refund':
                $data['list_value'] = $this->topsector_report_model->getSectorRefundReport($extraCheck_t,$extraCheck_x);
                break;
        }//end

        $this->load->view('topsector_report/show_sector_report', $data);
    }//end function

    function showAirlineReport()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle  = TOPTENSECTOR;
        $this->btnVal     = REPRINT;
        $this->common_model->check_admin_authorization();

        $this->ddlDivision       = $this->input->post('ddlDivision');
        $this->ddlLocation       = $this->input->post('ddlLocation');
        $this->ddlLocation2      = $this->input->post('ddlLocation2');
        $this->txtToDate         = $this->input->post('txtToDate');
        $this->txtFromDate       = $this->input->post('txtFromDate');
        $this->ddlAirline        = $this->input->post('ddlAirline');
        $this->ddlPayableAccount = $this->input->post('ddlPayableAccount');
        $this->ddlIssued         = $this->input->post('ddlIssued');
        $this->ddlAccount        = $this->input->post('ddlAccount');
        $this->ddlSubAccount     = $this->input->post('ddlSubAccount');
        $this->txtSector1        = $this->input->post('txtSector1');
        $this->txtSector2        = $this->input->post('txtSector2');
        $this->txtSector3        = $this->input->post('txtSector3');
        $this->txtSector4        = $this->input->post('txtSector4');
        $this->txtSector5        = $this->input->post('txtSector5');
        $this->txtSector6        = $this->input->post('txtSector6');
        $this->txtSector7        = $this->input->post('txtSector7');
        $this->txtDestination    = $this->input->post('txtDestination');
        $this->ddlGDS            = $this->input->post('ddlGDS');
        $this->ddlRefund         = $this->input->post('ddlRefund');
        $this->ddlReport         = $this->input->post('ddlReport');

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_x .= " AND xo.dDate >= '" .$this->txtFromDate . "' AND xo.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->txtSector1)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector1." %' OR td.vSector1 LIKE '% ".$this->txtSector1."'
								OR td.vSector1 LIKE '%".$this->txtSector1."%' OR td.vSector12 LIKE '".$this->txtSector1." %'
								OR td.vSector12 LIKE '% ".$this->txtSector1."' OR td.vSector12 LIKE '%".$this->txtSector1."%' OR
								td.vSector2 LIKE '".$this->txtSector1." %' OR td.vSector2 LIKE '% ".$this->txtSector1."'
								OR td.vSector2 LIKE '%".$this->txtSector1."%' OR td.vSector22 LIKE '".$this->txtSector1." %'
								OR td.vSector22 LIKE '% ".$this->txtSector1."' OR td.vSector22 LIKE '%".$this->txtSector1."%' OR
								td.vSector3 LIKE '".$this->txtSector1." %' OR td.vSector3 LIKE '% ".$this->txtSector1."'
								OR td.vSector3 LIKE '%".$this->txtSector1."%' OR td.vSector33 LIKE '".$this->txtSector1." %' OR
								td.vSector33 LIKE '% ".$this->txtSector1."'	OR td.vSector33 LIKE '%".$this->txtSector1."%'  OR
								td.vSector4 LIKE '".$this->txtSector1." %' OR td.vSector4 LIKE '% ".$this->txtSector1."' OR td.vSector4
								LIKE '%".$this->txtSector1."%' OR td.vSector44 LIKE '".$this->txtSector1." %' OR td.vSector44
								LIKE '% ".$this->txtSector1."' OR td.vSector44 LIKE '%".$this->txtSector1."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector1." %' OR xd.vSector LIKE '% ".$this->txtSector1."'
								OR xd.vSector LIKE '%".$this->txtSector1."%' OR xd.vSector2 LIKE '".$this->txtSector1." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector1."'OR xd.vSector2 LIKE '%".$this->txtSector1."%') ";
        }//end if

        if (!empty($this->txtSector2)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector2." %' OR td.vSector1 LIKE '% ".$this->txtSector2."'
								OR td.vSector1 LIKE '%".$this->txtSector2."%' OR td.vSector12 LIKE '".$this->txtSector2." %'
								OR td.vSector12 LIKE '% ".$this->txtSector2."' OR td.vSector12 LIKE '%".$this->txtSector2."%' OR
								td.vSector2 LIKE '".$this->txtSector2." %' OR td.vSector2 LIKE '% ".$this->txtSector2."'
								OR td.vSector2 LIKE '%".$this->txtSector2."%' OR td.vSector22 LIKE '".$this->txtSector2." %'
								OR td.vSector22 LIKE '% ".$this->txtSector2."' OR td.vSector22 LIKE '%".$this->txtSector2."%' OR
								td.vSector3 LIKE '".$this->txtSector2." %' OR td.vSector3 LIKE '% ".$this->txtSector2."'
								OR td.vSector3 LIKE '%".$this->txtSector2."%' OR td.vSector33 LIKE '".$this->txtSector2." %' OR
								td.vSector33 LIKE '% ".$this->txtSector2."'	OR td.vSector33 LIKE '%".$this->txtSector2."%'  OR
								td.vSector4 LIKE '".$this->txtSector2." %' OR td.vSector4 LIKE '% ".$this->txtSector2."' OR td.vSector4
								LIKE '%".$this->txtSector2."%' OR td.vSector44 LIKE '".$this->txtSector2." %' OR td.vSector44
								LIKE '% ".$this->txtSector2."' OR td.vSector44 LIKE '%".$this->txtSector2."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector2." %' OR xd.vSector LIKE '% ".$this->txtSector2."'
								OR xd.vSector LIKE '%".$this->txtSector2."%' OR xd.vSector2 LIKE '".$this->txtSector2." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector2."'OR xd.vSector2 LIKE '%".$this->txtSector2."%') ";
        }//end if

        if (!empty($this->txtSector3)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector3." %' OR td.vSector1 LIKE '% ".$this->txtSector3."'
								OR td.vSector1 LIKE '%".$this->txtSector3."%' OR td.vSector12 LIKE '".$this->txtSector3." %'
								OR td.vSector12 LIKE '% ".$this->txtSector3."' OR td.vSector12 LIKE '%".$this->txtSector3."%' OR
								td.vSector2 LIKE '".$this->txtSector3." %' OR td.vSector2 LIKE '% ".$this->txtSector3."'
								OR td.vSector2 LIKE '%".$this->txtSector3."%' OR td.vSector22 LIKE '".$this->txtSector3." %'
								OR td.vSector22 LIKE '% ".$this->txtSector3."' OR td.vSector22 LIKE '%".$this->txtSector3."%' OR
								td.vSector3 LIKE '".$this->txtSector3." %' OR td.vSector3 LIKE '% ".$this->txtSector3."'
								OR td.vSector3 LIKE '%".$this->txtSector3."%' OR td.vSector33 LIKE '".$this->txtSector3." %' OR
								td.vSector33 LIKE '% ".$this->txtSector3."'	OR td.vSector33 LIKE '%".$this->txtSector3."%'  OR
								td.vSector4 LIKE '".$this->txtSector3." %' OR td.vSector4 LIKE '% ".$this->txtSector3."' OR td.vSector4
								LIKE '%".$this->txtSector3."%' OR td.vSector44 LIKE '".$this->txtSector3." %' OR td.vSector44
								LIKE '% ".$this->txtSector3."' OR td.vSector44 LIKE '%".$this->txtSector3."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector3." %' OR xd.vSector LIKE '% ".$this->txtSector3."'
								OR xd.vSector LIKE '%".$this->txtSector3."%' OR xd.vSector2 LIKE '".$this->txtSector3." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector3."'OR xd.vSector2 LIKE '%".$this->txtSector3."%') ";
        }//end if

        if (!empty($this->txtSector4)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector4." %' OR td.vSector1 LIKE '% ".$this->txtSector4."'
								OR td.vSector1 LIKE '%".$this->txtSector4."%' OR td.vSector12 LIKE '".$this->txtSector4." %'
								OR td.vSector12 LIKE '% ".$this->txtSector4."' OR td.vSector12 LIKE '%".$this->txtSector4."%' OR
								td.vSector2 LIKE '".$this->txtSector4." %' OR td.vSector2 LIKE '% ".$this->txtSector4."'
								OR td.vSector2 LIKE '%".$this->txtSector4."%' OR td.vSector22 LIKE '".$this->txtSector4." %'
								OR td.vSector22 LIKE '% ".$this->txtSector4."' OR td.vSector22 LIKE '%".$this->txtSector4."%' OR
								td.vSector3 LIKE '".$this->txtSector4." %' OR td.vSector3 LIKE '% ".$this->txtSector4."'
								OR td.vSector3 LIKE '%".$this->txtSector4."%' OR td.vSector33 LIKE '".$this->txtSector4." %' OR
								td.vSector33 LIKE '% ".$this->txtSector4."'	OR td.vSector33 LIKE '%".$this->txtSector4."%'  OR
								td.vSector4 LIKE '".$this->txtSector4." %' OR td.vSector4 LIKE '% ".$this->txtSector4."' OR td.vSector4
								LIKE '%".$this->txtSector4."%' OR td.vSector44 LIKE '".$this->txtSector4." %' OR td.vSector44
								LIKE '% ".$this->txtSector4."' OR td.vSector44 LIKE '%".$this->txtSector4."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector4." %' OR xd.vSector LIKE '% ".$this->txtSector4."'
								OR xd.vSector LIKE '%".$this->txtSector4."%' OR xd.vSector2 LIKE '".$this->txtSector4." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector4."'OR xd.vSector2 LIKE '%".$this->txtSector4."%') ";
        }//end if

        if (!empty($this->txtSector5)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector5." %' OR td.vSector1 LIKE '% ".$this->txtSector5."'
								OR td.vSector1 LIKE '%".$this->txtSector5."%' OR td.vSector12 LIKE '".$this->txtSector5." %'
								OR td.vSector12 LIKE '% ".$this->txtSector5."' OR td.vSector12 LIKE '%".$this->txtSector5."%' OR
								td.vSector2 LIKE '".$this->txtSector5." %' OR td.vSector2 LIKE '% ".$this->txtSector5."'
								OR td.vSector2 LIKE '%".$this->txtSector5."%' OR td.vSector22 LIKE '".$this->txtSector5." %'
								OR td.vSector22 LIKE '% ".$this->txtSector5."' OR td.vSector22 LIKE '%".$this->txtSector5."%' OR
								td.vSector3 LIKE '".$this->txtSector5." %' OR td.vSector3 LIKE '% ".$this->txtSector5."'
								OR td.vSector3 LIKE '%".$this->txtSector5."%' OR td.vSector33 LIKE '".$this->txtSector5." %' OR
								td.vSector33 LIKE '% ".$this->txtSector5."'	OR td.vSector33 LIKE '%".$this->txtSector5."%'  OR
								td.vSector4 LIKE '".$this->txtSector5." %' OR td.vSector4 LIKE '% ".$this->txtSector5."' OR td.vSector4
								LIKE '%".$this->txtSector5."%' OR td.vSector44 LIKE '".$this->txtSector5." %' OR td.vSector44
								LIKE '% ".$this->txtSector5."' OR td.vSector44 LIKE '%".$this->txtSector5."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector5." %' OR xd.vSector LIKE '% ".$this->txtSector5."'
								OR xd.vSector LIKE '%".$this->txtSector5."%' OR xd.vSector2 LIKE '".$this->txtSector5." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector5."'OR xd.vSector2 LIKE '%".$this->txtSector5."%') ";
        }//end if

        if (!empty($this->txtSector6)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector6." %' OR td.vSector1 LIKE '% ".$this->txtSector6."'
								OR td.vSector1 LIKE '%".$this->txtSector6."%' OR td.vSector12 LIKE '".$this->txtSector6." %'
								OR td.vSector12 LIKE '% ".$this->txtSector6."' OR td.vSector12 LIKE '%".$this->txtSector6."%' OR
								td.vSector2 LIKE '".$this->txtSector6." %' OR td.vSector2 LIKE '% ".$this->txtSector6."'
								OR td.vSector2 LIKE '%".$this->txtSector6."%' OR td.vSector22 LIKE '".$this->txtSector6." %'
								OR td.vSector22 LIKE '% ".$this->txtSector6."' OR td.vSector22 LIKE '%".$this->txtSector6."%' OR
								td.vSector3 LIKE '".$this->txtSector6." %' OR td.vSector3 LIKE '% ".$this->txtSector6."'
								OR td.vSector3 LIKE '%".$this->txtSector6."%' OR td.vSector33 LIKE '".$this->txtSector6." %' OR
								td.vSector33 LIKE '% ".$this->txtSector6."'	OR td.vSector33 LIKE '%".$this->txtSector6."%'  OR
								td.vSector4 LIKE '".$this->txtSector6." %' OR td.vSector4 LIKE '% ".$this->txtSector6."' OR td.vSector4
								LIKE '%".$this->txtSector6."%' OR td.vSector44 LIKE '".$this->txtSector6." %' OR td.vSector44
								LIKE '% ".$this->txtSector6."' OR td.vSector44 LIKE '%".$this->txtSector6."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector6." %' OR xd.vSector LIKE '% ".$this->txtSector6."'
								OR xd.vSector LIKE '%".$this->txtSector6."%' OR xd.vSector2 LIKE '".$this->txtSector6." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector6."'OR xd.vSector2 LIKE '%".$this->txtSector6."%') ";
        }//end if

        if (!empty($this->txtSector7)) {
            $extraCheck_t .= " AND (td.vSector1 LIKE '".$this->txtSector7." %' OR td.vSector1 LIKE '% ".$this->txtSector7."'
								OR td.vSector1 LIKE '%".$this->txtSector7."%' OR td.vSector12 LIKE '".$this->txtSector7." %'
								OR td.vSector12 LIKE '% ".$this->txtSector7."' OR td.vSector12 LIKE '%".$this->txtSector7."%' OR
								td.vSector2 LIKE '".$this->txtSector7." %' OR td.vSector2 LIKE '% ".$this->txtSector7."'
								OR td.vSector2 LIKE '%".$this->txtSector7."%' OR td.vSector22 LIKE '".$this->txtSector7." %'
								OR td.vSector22 LIKE '% ".$this->txtSector7."' OR td.vSector22 LIKE '%".$this->txtSector7."%' OR
								td.vSector3 LIKE '".$this->txtSector7." %' OR td.vSector3 LIKE '% ".$this->txtSector7."'
								OR td.vSector3 LIKE '%".$this->txtSector7."%' OR td.vSector33 LIKE '".$this->txtSector7." %' OR
								td.vSector33 LIKE '% ".$this->txtSector7."'	OR td.vSector33 LIKE '%".$this->txtSector7."%'  OR
								td.vSector4 LIKE '".$this->txtSector7." %' OR td.vSector4 LIKE '% ".$this->txtSector7."' OR td.vSector4
								LIKE '%".$this->txtSector7."%' OR td.vSector44 LIKE '".$this->txtSector7." %' OR td.vSector44
								LIKE '% ".$this->txtSector7."' OR td.vSector44 LIKE '%".$this->txtSector7."%') ";

            $extraCheck_x .= " AND (xd.vSector LIKE '".$this->txtSector7." %' OR xd.vSector LIKE '% ".$this->txtSector7."'
								OR xd.vSector LIKE '%".$this->txtSector7."%' OR xd.vSector2 LIKE '".$this->txtSector7." %'
								OR xd.vSector2 LIKE '% ".$this->txtSector7."'OR xd.vSector2 LIKE '%".$this->txtSector7."%') ";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if


		session_start();
		$_SESSION['extraCheck_t'] = $extraCheck_t;
		$_SESSION['extraCheck_x'] = $extraCheck_x;

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->topsector_report_model->getAirlineReport($extraCheck_t,$extraCheck_x);
                break;

            case 'Sales':
                $data['list_value'] = $this->topsector_report_model->getAirlineSalesReport($extraCheck_t,$extraCheck_x);
                break;

            case 'Refund':
                $data['list_value'] = $this->topsector_report_model->getAirlineRefundReport($extraCheck_t,$extraCheck_x);
                break;
        }//end

        $this->load->view('topsector_report/show_sector_airline_report', $data);

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>