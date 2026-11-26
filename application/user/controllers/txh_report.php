<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Txh_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('txh_report_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle  = TICKETXOHOTELREGISTERREPORTS;
        $this->btnVal     = REPRINT;
        $this->common_model->check_admin_authorization();


        $data['list_gds_value']     = $this->txh_report_model->getActiveGDS();
        $data['list_emp_value']     = $this->txh_report_model->getActiveEmployee();
        $data['list_country_value'] = $this->txh_report_model->getActiveCountry();
//        $data['list_city_value']    = $this->txh_report_model->getCorrActiveCity();
        $data['list_hotel_value']   = $this->txh_report_model->getActiveHotels();
        $data['division']           = $this->txh_report_model->getActiveDivision();
       // $data['location']           = $this->txh_report_model->getActiveLocation();
        $data['list_vendor_value']  = $this->txh_report_model->getActiveVendor();
        $data['list_airline_value'] = $this->txh_report_model->getActiveAirline();
        $data['list_payable_value'] = $this->txh_report_model->getAccounts();
// angela 05
//	$data['list_sub_acc']       = $this->txh_report_model->getSubAccountValues(); 

        $this->chkCurrencyValD      = "checked='checked'";

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
          $data['list_loc_value'] = $this->txh_report_model->filterLocation($this->ddlDivision);
        }
        else {
          reset($data['division']);
          $first_key = key($data['division']);
          $data['list_loc_value'] = $this->txh_report_model->filterLocation($first_key);
        }
 
        if($this->ddlCountry!= ""){
          $data['list_city_value'] = $this->txh_report_model->filterCity($this->ddlCountry);
        }
        else {
          $data['list_city_value']    = $this->txh_report_model->getCorrActiveCity();
        }

        if($this->ddlPayableAccount!= ""){
          $data['list_sub_acc'] = $this->txh_report_model->filterSubAccount($this->ddlPayableAccount);
        }
        else {
          $data['list_sub_acc']       = $this->txh_report_model->getSubAccountValues(); 
        }
        $this->load->view('general/template_top');
        $this->load->view('txh_report/txh_report', $data);
        $this->load->view('general/template_bottom');
    }//end function

	function filterSubAccount() 
	{ 
		$this->load->helper('url'); 
		$this->load->helper(array('form', 'url')); 
		$this->common_model->check_admin_authorization(); 
		$accName = $this->input->post('ddlPayableAccount'); 
		$this->session->unset_userdata('pdfTitle'); 
		$data['list_sub_acc'] = $this->txh_closed_model->filterSubAccount($accName); 
		$this->load->view('txh_report/show_sub',$data); 
	}//end function 
	

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();

        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');


//		$this->ddlLocation2  = $this->input->post('ddlLocation2');

 //       $this->ddlLocation   = $this->session->userdata('ddlLocation');
 //       $this->ddlLocation2  = $this->session->userdata('ddlLocation2');


        $data['list_loc_value'] = $this->txh_report_model->filterLocation($this->ddlDivision);
        $this->load->view('txh_report/show_location', $data);
    }//end function

    function filterCountry()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlCountry  = $this->input->post('ddlCountry');
        $this->ddlCity     = $this->input->post('ddlCity');

        $this->ddlCity = $this->session->userdata('ddlCity');
		
        $data['list_city_value'] = $this->txh_report_model->filterCity($this->ddlCountry);
        $this->load->view('txh_report/show_city', $data);
    }//end function


	// extrachecks for hotel used by payable, employee, and status as well as hotel reports
	function check_hotel(){

        if (!empty($this->ddlDivision)) {
            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
			$extraCheck_h .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
		
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_h .= " AND hv.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND hv.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
			//$extraCheck_h .= " AND hv.nAcHead='".$this->ddlPayableAccount."'";
        }//end if


        if (!empty($this->ddlSubAccount)) {
			//$extraCheck_h .= " AND hv.nAcCode='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
		{
            $extraCheck_h .= " AND hv.vType='".$this->ddlStatus."'";
        }//end if

        if (!empty($this->ddlHotel)) {
            $extraCheck_h .= " AND hv.vHotelName='".$this->ddlHotel."'";
        }//end if

		if (!empty($this->ddlCountry)) {
            $extraCheck_h .= " AND hv.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_h .= " AND hv.nCity='".$this->ddlCity."'";
        }//end if
	
		return $extraCheck_h;
	}	//end function
	
	// extrachecks for airline and xo, used by payable, and status
	function check_airline_xo()
	{ 

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_x .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
		
		
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
                        $extraCheck_x .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
        }//end if
		if ($this->txtCheckIn != '' && $this->txtCheckOut !='') {
				 $extraCheck_t .= " AND td.dTravelDate1 >=  STR_TO_DATE( '" . $this->txtCheckIn . "', '%d-%m-%Y' ) AND td.dTravelDate1 <= STR_TO_DATE( '" . $this->txtCheckOut . "', '%d-%m-%Y' ) ";
        }//end if

        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAccHead='".$this->ddlPayableAccount."'";
           $extraCheck_x .= " AND (SELECT nFId FROM {PRE}VendorMaster where nVId = xo.nPassengerAc) =  ".$this->ddlPayableAccount." ";
        }//end if


        if (!empty($this->ddlSubAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlSubAccount."'";
			$extraCheck_x .= " AND xo.nPassengerAc='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
		{
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
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
            $extraCheck_x .= " AND (xo.vDestination LIKE '".$this->txtDestination." %' OR xo.vDestination LIKE '% ".$this->txtDestination."'
								OR xo.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if
		
		
		return array("check_t" => $extraCheck_t, "check_x" => $extraCheck_x);
	}//end function
	

	function check_old_refund()
	{ 

        if (!empty($this->ddlDivision)) {

            $extraCheck_or = " AND rr.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_or .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      }

    if ($this->txtToDate != '' && $this->txtFromDate !='') {
      $extraCheck_or .= " AND rr.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND rr.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
    } //end if 

        if (!empty($this->ddlIssued)) {
            $extraCheck_or .= " AND rrd.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
        //$extraCheck_or .= " AND rr.nAccount ='".$this->ddlSubAccount."'";
          //$extraCheck_or .= " AND (SELECT nFId FROM {PRE}VendorMaster where nVId = xo.nPassengerAc) = ".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
         // $extraCheck_or .= " AND rr.nAccount ='".$this->ddlSubAccount."'";
        }//end if

//        $airCode = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $this->ddlAirline ."'");

        if (!empty($this->ddlAirline)) {
            $extraCheck_or .= " AND ar.nArId ='".$this->ddlAirline."'";
        }//end if
		
        if (!empty($this->txtSector1)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector1." %' OR rr.vSector LIKE '% ".$this->txtSector1."'
								OR rr.vSector LIKE '%".$this->txtSector1."%' OR rr.vSector2 LIKE '".$this->txtSector1." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector1."'OR rr.vSector2 LIKE '%".$this->txtSector1."%') ";

        }//end if
        if (!empty($this->txtSector2)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector2." %' OR rr.vSector LIKE '% ".$this->txtSector2."'
								OR rr.vSector LIKE '%".$this->txtSector2."%' OR rr.vSector2 LIKE '".$this->txtSector2." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector2."'OR rr.vSector2 LIKE '%".$this->txtSector2."%') ";

        }//end if
        if (!empty($this->txtSector3)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector3." %' OR rr.vSector LIKE '% ".$this->txtSector3."'
								OR rr.vSector LIKE '%".$this->txtSector3."%' OR rr.vSector2 LIKE '".$this->txtSector3." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector3."'OR rr.vSector2 LIKE '%".$this->txtSector3."%') ";

        }//end if
        if (!empty($this->txtSector4)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector4." %' OR rr.vSector LIKE '% ".$this->txtSector4."'
								OR rr.vSector LIKE '%".$this->txtSector4."%' OR rr.vSector2 LIKE '".$this->txtSector4." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector4."'OR rr.vSector2 LIKE '%".$this->txtSector4."%') ";

        }//end if
        if (!empty($this->txtSector5)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector5." %' OR rr.vSector LIKE '% ".$this->txtSector5."'
								OR rr.vSector LIKE '%".$this->txtSector5."%' OR rr.vSector2 LIKE '".$this->txtSector5." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector5."'OR rr.vSector2 LIKE '%".$this->txtSector5."%') ";

        }//end if
        if (!empty($this->txtSector6)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector6." %' OR rr.vSector LIKE '% ".$this->txtSector6."'
								OR rr.vSector LIKE '%".$this->txtSector6."%' OR rr.vSector2 LIKE '".$this->txtSector6." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector6."'OR rr.vSector2 LIKE '%".$this->txtSector6."%') ";

        }//end if
        if (!empty($this->txtSector7)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector7." %' OR rr.vSector LIKE '% ".$this->txtSector7."'
								OR rr.vSector LIKE '%".$this->txtSector7."%' OR rr.vSector2 LIKE '".$this->txtSector7." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector7."'OR rr.vSector2 LIKE '%".$this->txtSector7."%') ";

        }//end if
        if (!empty($this->txtSector8)) {
            $extraCheck_or .= " AND (rr.vSector LIKE '".$this->txtSector8." %' OR rr.vSector LIKE '% ".$this->txtSector8."'
								OR rr.vSector LIKE '%".$this->txtSector8."%' OR rr.vSector2 LIKE '".$this->txtSector8." %'
								OR rr.vSector2 LIKE '% ".$this->txtSector8."'OR rr.vSector2 LIKE '%".$this->txtSector8."%') ";

        }//end if
       if (!empty($this->ddlGDS)) {
            $extraCheck_or .= " AND rr.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->txtDestination)) {
            $extraCheck_or .= " AND (rr.vDestination LIKE '".$this->txtDestination." %' OR rr.vDestination LIKE '% ".$this->txtDestination."'
								OR rr.vDestination LIKE '%".$this->txtDestination."%') ";
        }//end if
		

        if (!empty($this->ddlHotel)) {
            $extraCheck_or .= " AND rrd.nHotel='".$this->ddlHotel."'";
        }//end if

    if (!empty($this->ddlCountry)) {
            $extraCheck_or .= " AND rrd.nCountry='".$this->ddlCountry."'";
        }//end if

        if (!empty($this->ddlCity)) {
            $extraCheck_or .= " AND rrd.nCity='".$this->ddlCity."'";
        }//end if

		
		return $extraCheck_or;
	}//end function
	

	
    function showAirline()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle  = AIRLINEREPORT;
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

/*
        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
        }//end if
	
	
		 if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_x .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
            $extraCheck_x .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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
            $extraCheck_x .= " AND xo.nPassengerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A') {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if


        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundAirlineReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
*/

		$extraCheck_t_x = $this->check_airline_xo();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];
    $extraCheck_or = $this->check_old_refund();

		$extraCheck_h = " AND hv.nDivision=''";
    $extraCheck_or .= " AND ( rrd.vTktType = 'Xo' OR rrd.vTktType = 'Tkt') ";

		$orderby = " airline ASC, sale_type ASC, refundtype DESC";

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;
        }//end switch


        $mydata = $this->load->view("txh_report/export_files/show_airline_report", $data,true);
        $_SESSION['airline_report'] = $mydata;
											
		if(trim($this->session->userdata('backURL')) != ''):
			$this->load->view('general/template_top');
			$this->load->view('txh_report/show_airline_report', $data);
			$this->load->view('general/template_bottom');
		else:
        	$this->load->view('txh_report/show_airline_report', $data);
		endif;
		
		$this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

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
            $this->session->unset_userdata('chkCurrency');
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
        $this->chkCurrencyVal    = $this->common_model->searchKeyword3('chkCurrency');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

// get where clause for query 
		$extraCheck_h =  $this->check_hotel();
		$extraCheck_t_x = $this->check_airline_xo();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];
    $extraCheck_or = $this->check_old_refund();

// if data has been entered for hotel no airline data should be shown
		if((!empty($this->ddlHotel)) || (!empty($this->ddlCountry)) || (!empty($this->ddlCity)) || (!empty($this->txtCheckIn)) || (!empty($this->txtCheckOut))) {
            $extraCheck_t = " AND tb.nDivision=''";
            $extraCheck_x = " AND xo.nDivision=''";
            $extraCheck_or .= " AND rrd.vTktType = 'Htl' ";
		}
// if data has been entered for airline no hotel data should be shown		
		if ((!empty($this->ddlAirline)) || (!empty($this->txtSector1)) || (!empty($this->txtSector2)) ||  (!empty($this->txtSector3)) || (!empty($this->txtSector4)) || (!empty($this->txtSector5)) || (!empty($this->txtSector6)) || (!empty($this->txtSector7)) || (!empty($this->ddlGDS)) || (!empty($this->txtDestination))) {
			$extraCheck_h = " AND hv.nDivision=''";
      $extraCheck_or .= " AND ( rrd.vTktType = 'Xo' OR rrd.vTktType = 'Tkt') ";
		}

		$orderby = "account ASC,dDate ASC,tktNumber ASC,refundtype ASC";
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;
        }//end switch

        if($this->chkCurrencyVal == 'F'){
          $link_pay = 'txh_report/show_payable_report_fc';
          $link_pay_export = 'txh_report/export_files/show_payable_report_fc';
        }
        else{
          $link_pay = 'txh_report/show_payable_report';
          $link_pay_export = 'txh_report/export_files/show_payable_report';
        }
        $mydata = $this->load->view($link_pay_export, $data,true);
        $_SESSION['account_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view($link_pay, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view($link_pay, $data);
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

// get where clause for query 
		$extraCheck_h =  $this->check_hotel();
		$extraCheck_t_x = $this->check_airline_xo();
    $extraCheck_or = $this->check_old_refund();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];

// if data has been entered for hotel no airline data should be shown
		if((!empty($this->ddlHotel)) || (!empty($this->ddlCountry)) || (!empty($this->ddlCity)) || (!empty($this->txtCheckIn)) || (!empty($this->txtCheckOut))) {
            $extraCheck_t = " AND tb.nDivision=''";
            $extraCheck_x = " AND xo.nDivision=''";
            $extraCheck_or .= " AND rrd.vTktType = 'Htl' ";
		}
// if data has been entered for airline no hotel data should be shown		
		if ((!empty($this->ddlAirline)) || (!empty($this->txtSector1)) || (!empty($this->txtSector2)) ||  (!empty($this->txtSector3)) || (!empty($this->txtSector4)) || (!empty($this->txtSector5)) || (!empty($this->txtSector6)) || (!empty($this->txtSector7)) || (!empty($this->ddlGDS)) || (!empty($this->txtDestination))) {
			$extraCheck_h = " AND hv.nDivision=''";
      $extraCheck_or .= " AND ( rrd.vTktType = 'Xo' OR rrd.vTktType = 'Tkt') ";
		}
		
/*
		$columns_t = "tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt, tfd.vFaceValue as fareVal  ";


		$columns_x = "xo.nXId as nTId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal  ";
											
											
		$columns_h = "hv.nHVId,hv.vDocNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate, hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt,hv.vTotalRTDC as fareVal  ";


        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getEmployeeReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getEmployeeSalesReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getEmployeeRefundReport($columns_t,$columns_x,$columns_h,$extraCheck_t,$extraCheck_h,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
*/

		$orderby = " vIssuedBy ASC, refundtype DESC";
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;
        }//end switch

        $mydata = $this->load->view("txh_report/export_files/show_employee_report", $data,true);
        $_SESSION['employee_report'] = $mydata;


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_employee_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_employee_report', $data);
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

 		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_x .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}

/*
        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
        }//end if
*/
        if ($this->txtToDate != '' && $this->txtFromDate !='') {
$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
                        $extraCheck_x .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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
            $extraCheck_x .= " AND xo.nPassengerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
 {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_gds_report", $data,true);
        $_SESSION['gds_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_gds_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_gds_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

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

		$extraCheck_h =  $this->check_hotel();
    $extraCheck_or = $this->check_old_refund();
		$orderby = " vName ASC ,  refundtype DESC  ";

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;
        }//end

        $mydata = $this->load->view("txh_report/export_files/show_hotel_report", $data,true);
        $_SESSION['hotel_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_hotel_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_hotel_report', $data);
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
$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
 {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getGdsTicketedReport($extraCheck_t);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesGdsTicketedReport($extraCheck_t);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundGdsTicketedReport($extraCheck_t);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_gds_ticketed_report", $data,true);
        $_SESSION['gds_ticketed_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_gds_ticketed_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_gds_ticketed_report', $data);
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

		$extraCheck_h =  $this->check_hotel();
    $extraCheck_or = $this->check_old_refund();

		if ((!empty($this->txtCheckIn)) != '' && (!empty($this->txtCheckOut))){
				$extraCheck_h .= " AND dCheckIn >=  STR_TO_DATE( '" . $this->txtCheckIn . "', '%d-%m-%Y' ) AND dCheckIn <= STR_TO_DATE( '" . $this->txtCheckOut . "', '%d-%m-%Y' ) ";
        }//end if

		$orderby = " YEAR(dCheckIn) DESC, MONTH(dCheckIn) DESC, DAY(dCheckIn) ASC, vName ASC ,  refundtype DESC  ";


        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_hotel_checkin_report", $data,true);
        $_SESSION['hotel_checkin_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_hotel_checkin_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_hotel_checkin_report', $data);
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

		$extraCheck_h =  $this->check_hotel();
    $extraCheck_or = $this->check_old_refund();
		if ((!empty($this->txtCheckIn)) != '' && (!empty($this->txtCheckOut))){
				$extraCheck_h .= " AND dCheckOut >=  STR_TO_DATE( '" . $this->txtCheckIn . "', '%d-%m-%Y' ) AND dCheckOut <= STR_TO_DATE( '" . $this->txtCheckOut . "', '%d-%m-%Y' ) ";
        }//end if
		
   	    $orderby = " YEAR(dCheckOut) DESC, MONTH(dCheckOut) DESC, DAY(dCheckOut) ASC, vName ASC ,  refundtype DESC  ";
		

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;
        }//end

        $mydata = $this->load->view("txh_report/export_files/show_hotel_checkout_report", $data,true);
        $_SESSION['hotel_checkout_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_hotel_checkout_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_hotel_checkout_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    } //end function

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
$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
                        $extraCheck_x .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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
            $extraCheck_x .= " AND xo.nPassengerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
 {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_gds_report", $data,true);
        $_SESSION['gds_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_gds_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_gds_report', $data);
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

		$extraCheck_h =  $this->check_hotel();
    $extraCheck_or = $this->check_old_refund();
		$orderby = " Country, City, vName ASC ,  refundtype DESC  ";

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_country_report", $data,true);
        $_SESSION['country_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_country_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_country_report', $data);
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

		$extraCheck_h =  $this->check_hotel();
    $extraCheck_or = $this->check_old_refund();
		$orderby = " City, vName ASC ,  refundtype DESC  ";
		
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundHotelReport($extraCheck_h, $extraCheck_or,$orderby);
                break;
        }//end

        $mydata = $this->load->view("txh_report/export_files/show_city_report", $data,true);
        $_SESSION['city_report'] = $mydata;
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_city_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_city_report', $data);
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

 		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_x .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
/*
        if (!empty($this->ddlLocation)) {
            $extraCheck_t .= " AND tb.nLocation='".$this->ddlLocation."'";
            $extraCheck_x .= " AND xo.nLocation='".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlLocation2)) {
            $extraCheck_t .= " OR tb.nLocation='".$this->ddlLocation2."'";
            $extraCheck_x .= " OR xo.nLocation='".$this->ddlLocation2."'";
        }//end if

  */
        if ($this->txtToDate != '' && $this->txtFromDate !='') {
$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
                        $extraCheck_x .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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
            $extraCheck_x .= " AND xo.nPassengerAc='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
 {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
            $extraCheck_x .= " AND xo.vType='".$this->ddlStatus."'";
        }//end if
		
		$columns_t = " tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent,tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate,' ' as lpo,tb.vPassenger, tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,td.vSector1 as Sector1,td.vSector12 as Sector12,td.vSector2 as Sector2,td.vSector22 as Sector22, td.vSector3 as Sector3,td.vSector33 as Sector33,td.vSector4 as Sector4,td.vSector44 as Sector44, tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt,tfd.vFaceValue as fareVal ";
		
		$columns_x = " xo.nXId,xo.vDocNumber,xo.nLocation, 'Xo' as sale_type,xo.vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt, xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,xpd.vPass,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xd.vSector as Sector1, xd.vSector2 as Sector12,'' as Sector2,'' as Sector22,'' as Sector3,'' as Sector33,'' as Sector4,'' as Sector44, xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt,xpd.vFCTax as fareVal ";
		
		

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getSectorReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSectorSalesReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getSectorRefundReport($columns_t, $columns_x, $extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end switch

        $mydata = $this->load->view("txh_report/export_files/show_sector_report", $data,true);
        $_SESSION['sector_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_sector_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_sector_report', $data);
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

 		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_x .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
$extraCheck_t .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
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

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
 {
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
        }//end if

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getGdsTicketedReport($extraCheck_t);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesGdsTicketedReport($extraCheck_t);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundGdsTicketedReport($extraCheck_t);
                break;
        }//end
        $mydata = $this->load->view("txh_report/export_files/show_destination_report", $data,true);
        $_SESSION['destination_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_destination_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_destination_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function showStatus()
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

// get where clause for query 
		$extraCheck_h =  $this->check_hotel();
		$extraCheck_t_x = $this->check_airline_xo();
    $extraCheck_or = $this->check_old_refund();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];

// if data has been entered for hotel no airline data should be shown
		if((!empty($this->ddlHotel)) || (!empty($this->ddlCountry)) || (!empty($this->ddlCity)) || (!empty($this->txtCheckIn)) || (!empty($this->txtCheckOut))) {
            $extraCheck_t = " AND tb.nDivision=''";
            $extraCheck_x = " AND xo.nDivision=''";
            $extraCheck_or .= " AND rrd.vTktType = 'Htl' ";
		}
// if data has been entered for airline no hotel data should be shown		
		if ((!empty($this->ddlAirline)) || (!empty($this->txtSector1)) || (!empty($this->txtSector2)) ||  (!empty($this->txtSector3)) || (!empty($this->txtSector4)) || (!empty($this->txtSector5)) || (!empty($this->txtSector6)) || (!empty($this->txtSector7)) || (!empty($this->ddlGDS)) || (!empty($this->txtDestination))) {
			$extraCheck_h = " AND hv.nDivision=''";
      $extraCheck_or .= " AND ( rrd.vTktType = 'Xo' OR rrd.vTktType = 'Tkt') ";
		}
		
		$orderby = " vStatus ASC, refundtype DESC ";

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->txh_report_model->getPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->txh_report_model->getSalesPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->txh_report_model->getRefundPayableReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_or,$orderby,$this->ddlSalesType);
                break;
        }//end switch
        $mydata = $this->load->view("txh_report/export_files/show_status_report", $data,true);
        $_SESSION['status_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('txh_report/show_status_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('txh_report/show_status_report', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    } //end function

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('txh_report/show_issuedBy',$data);
    }

}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
