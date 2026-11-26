<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profit_report extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('profit_report_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = PROFITREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();

        $data['list_div_value']     = $this->profit_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->profit_report_model->getActiveLocations();
        $data['list_vendor_value']  = $this->profit_report_model->getActiveVendor();
        $data['list_payable']       = $this->profit_report_model->getPayableAccount();
        $data['list_airline_value'] = $this->profit_report_model->getActiveAirline();
        $data['list_country_value']	= $this->profit_report_model->getActiveCountry();
        $data['list_city_value']    = $this->profit_report_model->getCorrActiveCity();
        $data['list_emp_value']     = $this->profit_report_model->getActiveEmployee();
        $data['list_hotel_value']   = $this->profit_report_model->getActiveHotels();
        $data['list_acc_value']     = $this->profit_report_model->getAccounts();
        $data['list_sub_acc']       = $this->profit_report_model->getSubAccountValues();
        $data['list_gds_value']     = $this->profit_report_model->getActiveGDS();

        $this->chkValYes= "checked='checked'";

		$this->session->unset_userdata('pageName2');

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision      = $this->session->userdata('ddlDivision');
            $this->ddlLocation      = $this->session->userdata('ddlLocation');
            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');
            $this->txtToDate        = $this->session->userdata('txtToDate');
            $this->txtFromDate      = $this->session->userdata('txtFromDate');
            $this->ddlCountry       = $this->session->userdata('ddlCountry');
            $this->ddlCity          = $this->session->userdata('ddlCity');
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
            $this->ddlSalesType     = $this->session->userdata('ddlSalesType');
            $this->ddlRefund        = $this->session->userdata('ddlRefund');
            $this->ddlReport        = $this->session->userdata('ddlReport');
            $this->ddlStatus        = $this->session->userdata('ddlStatus');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        }

    if($this->ddlDivision != ""){
      $data['list_loc_value'] = $this->profit_report_model->filterLocation($this->ddlDivision);
    }
    else {
      reset($data['list_div_value']);
      $first_key = key($data['list_div_value']);
      $data['list_loc_value'] = $this->profit_report_model->filterLocation($first_key);
    }

    if($this->ddlCountry!= ""){
      $data['list_city_value'] = $this->profit_report_model->filterCity($this->ddlCountry);
    }
    else {
      $data['list_city_value']    = $this->profit_report_model->getCorrActiveCity();
    }


        $this->load->view('general/template_top');
        $this->load->view('profit_report/profit_report',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccount');

        $data['list_sub_acc'] = $this->profit_report_model->filterSubAccount($accName);
        $this->load->view('profit_report/show_sub',$data);
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->profit_report_model->filterLocation($this->ddlDivision);
        $this->load->view('profit_report/show_location',$data);
    }//end function

    function filterCountry()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlCountry = $this->input->post('ddlCountry');
        $this->ddlCity    = $this->input->post('ddlCity');

        $data['list_city_value'] = $this->profit_report_model->filterCity($this->ddlCountry);
        $this->load->view('profit_report/show_city',$data);
    }//end function

    function showAirline()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = AIRLINEREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

		if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

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

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_airline_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_airline_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function showGdsBooking()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

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

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_gds_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_gds_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showHotel()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = HOTELREPORTBYHOTEL;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getHotelReport($extraCheck_h);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesHotelReport($extraCheck_h);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundHotelReport($extraCheck_h);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_hotel_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_hotel_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showGdsTicketed()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
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
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getGdsTicketedReport($extraCheck_t);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesGdsTicketedReport($extraCheck_t);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundGdsTicketedReport($extraCheck_t);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_gds_ticketed_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_gds_ticketed_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function showPayable()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_x .= " AND xo.dDate >= '" .$this->txtFromDate . "' AND xo.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_payable_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_payable_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function showHotelCheckIn()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = HOTELREPORTBYHOTEL;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
		$this->session->set_userdata('pageName2',REPORTBYCHECKIN);

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getHotelCheckInReport($extraCheck_h);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getHotelCheckInSalesReport($extraCheck_h);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getHotelCheckInRefundReport($extraCheck_h);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_hotel_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_hotel_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showHotelCheckOut()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = HOTELREPORTBYHOTEL;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));
		$this->session->set_userdata('pageName2',REPORTBYCHECKOUT);

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getHotelCheckOutReport($extraCheck_h);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getHotelCheckOutSalesReport($extraCheck_h);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getHotelCheckOutRefundReport($extraCheck_h);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_hotel_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_hotel_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showEmployee()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = EMPLOYEREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_x .= " AND xo.dDate >= '" .$this->txtFromDate . "' AND xo.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getEmployeeReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getEmployeeSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getEmployeeRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_employee_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_employee_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function showGdsHX()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

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

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_gds_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_gds_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function showHotelCountry()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = HOTELREPORTBYHOTEL;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getHotelReport($extraCheck_h);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesHotelReport($extraCheck_h);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundHotelReport($extraCheck_h);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_country_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_country_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showHotelCity()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = HOTELREPORTBYHOTEL;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getHotelReport($extraCheck_h);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesHotelReport($extraCheck_h);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundHotelReport($extraCheck_h);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_city_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_city_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

    function showSector()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

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

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getSectorReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSectorSalesReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getSectorRefundReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_sector_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_sector_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function showDestination()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
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
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getGdsTicketedReport($extraCheck_t);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesGdsTicketedReport($extraCheck_t);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundGdsTicketedReport($extraCheck_t);
                break;
        }//end

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_destination_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_destination_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function showProfit()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSBOOKINGREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlCountry');
            $this->session->unset_userdata('ddlCity');
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
            $this->session->unset_userdata('ddlHotel');
            $this->session->unset_userdata('txtCheckIn');
            $this->session->unset_userdata('txtCheckOut');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlVendor');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');
        $this->ddlCity           = $this->common_model->searchKeyword3('ddlCity');
        $this->ddlIssued         = $this->common_model->searchKeyword3('ddlIssued');
        $this->ddlAirline        = $this->common_model->searchKeyword3('ddlAirline');
        $this->txtSector1        = $this->common_model->searchKeyword3('txtSector1');
        $this->txtSector2        = $this->common_model->searchKeyword3('txtSector2');
        $this->txtSector3        = $this->common_model->searchKeyword3('txtSector3');
        $this->txtSector4        = $this->common_model->searchKeyword3('txtSector4');
        $this->txtSector5        = $this->common_model->searchKeyword3('txtSector5');
        $this->txtSector6        = $this->common_model->searchKeyword3('txtSector6');
        $this->txtSector7        = $this->common_model->searchKeyword3('txtSector7');
        $this->ddlGDS            = $this->common_model->searchKeyword3('ddlGDS');
        $this->txtDestination    = $this->common_model->searchKeyword3('txtDestination');
        $this->ddlHotel          = $this->common_model->searchKeyword3('ddlHotel');
        $this->txtCheckIn        = $this->common_model->searchKeyword3('txtCheckIn');
        $this->txtCheckOut       = $this->common_model->searchKeyword3('txtCheckOut');
        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');
        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
            $extraCheck_h .= " AND hv.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
            $extraCheck_h .= " OR hv.nLocation='".$this->ddlLocation2."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= '" .$this->txtFromDate . "' AND tb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_x .= " AND xo.dDate >= '" .$this->txtFromDate . "' AND xo.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_h .= " AND hv.dDate >= '" .$this->txtFromDate . "' AND hv.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
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

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_t .= " AND (td.vDestination LIKE '".$this->txtDestination." %' OR td.vDestination LIKE '% ".$this->txtDestination."'
								OR td.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

        if (!empty($this->txtCheckIn)) {
            $extraCheck_h 	.= " AND hv.dCheckIn = '" .$this->txtCheckIn . "'";
        }//end if

        if (!empty($this->txtCheckOut)) {
            $extraCheck_h 	.= " AND hv.dCheckOut = '" .$this->txtCheckOut . "'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlPayableAccount."'";
            $extraCheck_x .= " AND xbd.nCustomerAc='".$this->ddlPayableAccount."'";
            $extraCheck_h .= " AND hv.nAcCode='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus)) {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->profit_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->profit_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->profit_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('profit_report/show_profit_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('profit_report/show_profit_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
