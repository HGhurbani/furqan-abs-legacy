<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gds_report extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('gds_report_model');
	}
    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSAIRLINEREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
        $data['list_div_value']     = $this->gds_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->gds_report_model->getActiveLocation();
        $data['list_airline_value'] = $this->gds_report_model->getActiveAirline();
        $data['list_payable']       = $this->gds_report_model->getPayableAccount();
        $data['list_emp_value']     = $this->gds_report_model->getActiveEmployee();
//        $data['list_acc_value']     = $this->gds_report_model->getActiveAccounts();
        $data['list_sub_acc']       = $this->gds_report_model->getSubAccountValues();
        $data['list_gds_value']     = $this->gds_report_model->getActiveGDS();
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
            $this->ddlOpenClosed    = $this->session->userdata('ddlOpenClosed');			
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
            $this->session->unset_userdata('ddlOpenClosed'); 
            $this->session->unset_userdata('ddlStatus');
            $this->session->unset_userdata('pageName');
        }

    if($this->ddlDivision != ""){
      $data['list_loc_value'] = $this->gds_report_model->filterLocation($this->ddlDivision);
    }
    else {
      reset($data['list_div_value']);
      $first_key = key($data['list_div_value']);
      $data['list_loc_value'] = $this->gds_report_model->filterLocation($first_key);
    }
        $this->load->view('general/template_top');
        $this->load->view('gds_report/gds_report',$data);
        $this->load->view('general/template_bottom');
    }//end function
    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');
        $data['list_loc_value'] = $this->gds_report_model->filterLocation($this->ddlDivision);
        $this->load->view('gds_report/show_location',$data);
    }//end function
    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlPayableAccount');
        $data['list_sub_acc'] = $this->gds_report_model->filterSubAccount($accName);
        $this->load->view('gds_report/show_sub',$data);
    }//end function
	

//////////////// Common checks for closed and open report \\\\\\\\\\\\\
// extrachecks for airline and xo
	function check_airline_xo_common()
	{ 
        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
//            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
//            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
//            $extraCheck_x .= " AND xo.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAccHead='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlSubAccount."'";
        }//end if

        if (!empty($this->ddlStatus) && $this->ddlStatus != 'A')
		{
            $extraCheck_t .= " AND tb.vType='".$this->ddlStatus."'";
		}
	
        if (!empty($this->txtSector1)) {
            $extraCheck_t .= " AND td.vSector1 LIKE '".$this->txtSector1."' ";
        }//end if
        if (!empty($this->txtSector2)) {
            $extraCheck_t .= " AND  td.vSector12 LIKE '".$this->txtSector2."' " ;
        }//end if
        if (!empty($this->txtSector3)) {
            $extraCheck_t .= " AND td.vSector2 LIKE '".$this->txtSector3."' " ;
        }//end if
        if (!empty($this->txtSector4)) {
            $extraCheck_t .= " AND td.vSector22 LIKE '".$this->txtSector4."' " ;
        }//end if
        if (!empty($this->txtSector5)) {
            $extraCheck_t .= " AND td.vSector3 LIKE '".$this->txtSector5."' ";
        }//end if
        if (!empty($this->txtSector6)) {
            $extraCheck_t .= " AND td.vSector33 LIKE '".$this->txtSector6."' ";
        }//end if
        if (!empty($this->txtSector7)) {
            $extraCheck_t .= " AND td.vSector4 LIKE '".$this->txtSector7."'";
        }//end if

//		return array("check_t" => $extraCheck_t, "check_x" => $extraCheck_x);
		return $extraCheck_t;
	}//end function


///////////////// Checks for sales, refund Open	 \\\\\\\\\\\\\\\\\\\\\\\\\\\\

// Sales Check
	function check_airline_xo_open()
	{ 

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
//            $extraCheck_x = " AND xo.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
//				$extraCheck_x .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
		
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_t .= " AND STR_TO_DATE( tb.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate  . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
//			$extraCheck_x .= " AND STR_TO_DATE( xo.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
        }//end if

//		return array("check_t" => $extraCheck_t, "check_x" => $extraCheck_x);
		return $extraCheck_t;
	}//end function


// Refund Check

	function check_refund_open()
	{ 

        if (!empty($this->ddlDivision)) {
            $extraCheck_refund_reg = " AND rr.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
			$extraCheck_refund_reg .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
		
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_refund_reg .= " AND STR_TO_DATE( rr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
        }//end if
		 if (!empty($this->ddlAirline)) {
			$Airline   = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$this->ddlAirline."'");
           
		$extraCheck_refund_reg .= " AND rrd.vAirline='".$Airline."' ";
        }//end if
        if (!empty($this->ddlPayableAccount)) {
			
        }//end if
		return $extraCheck_refund_reg;
	} //end function


//////////////// Checks for sales, refund Closed  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\


	function check_accounts_closed(){

        if (!empty($this->ddlDivision)) {

            $extraCheck_accounts = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_accounts_r = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if		 
	
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_accounts .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

				$extraCheck_accounts_r .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";				
		}		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_accounts .= " AND STR_TO_DATE( sc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
			$extraCheck_accounts_r .= " AND STR_TO_DATE( rc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
		}//end if
		if (!empty($this->ddlAirline)) {
			$Airline   = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$this->ddlAirline."'");
           
		$extraCheck_accounts_r .= " AND rcd.nAirline='".$Airline."' ";
        }//end if
		
//payable		
		
		return array("check_a_s" => $extraCheck_accounts, "check_a_r" => $extraCheck_accounts_r);
	}


	
	
    function showGdsDetails()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
        $array_items = array('extraCheck_t' => '','extraCheck_x' => '');
        $this->session->unset_userdata($array_items);
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
            $this->session->unset_userdata('ddlOpenClosed'); 			
            $this->session->unset_userdata('ddlStatus');
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
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlOpenClosed         = $this->common_model->searchKeyword3('ddlOpenClosed');			
        $this->ddlReportType     = $this->common_model->searchKeyword3('ddlReportType');
        $this->session->set_userdata('pageName',$this->uri->segment(1));

 		$extraChecks = array();
//		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $this->check_airline_xo_common(); // $extraCheck_1['check_t'];
//		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
//		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $this->check_airline_xo_open(); // $extraCheck_2['check_t'];
//		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		
		$extraChecks['extraCheck_ref_open'] = $this->check_refund_open();

		$extraCheck_3 = $this->check_accounts_closed();
		$extraChecks['extraCheck_sales_closed'] = $extraCheck_3['check_a_s'];
		$extraChecks['extraCheck_ref_closed'] = $extraCheck_3['check_a_r'];
		
		$orderby = " nGDS ASC, refundtype DESC, airline ASC ";

        $data['list_value'] = $this->gds_report_model->getDetailReport($extraChecks,$this->ddlOpenClosed,$this->ddlRefund,$this->ddlGDS , $orderby);
// angela
		 
		/* 
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->gds_report_model->getGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
            case 'Sales':
                $data['list_value'] = $this->gds_report_model->getSalesGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
            case 'Refund':
                $data['list_value'] = $this->gds_report_model->getRefundGdsReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
		*/
        $mydata = $this->load->view("gds_report/export_files/gds_report_details", $data,true);
        $_SESSION['gds_report_details'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('gds_report/gds_report_details', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('gds_report/gds_report_details', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
    function showGdsSummary()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = GDSREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
        $array_items = array('extraCheck_t' => '','extraCheck_x' => '');
        $this->session->unset_userdata($array_items);
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
            $this->session->unset_userdata('ddlOpenClosed'); 			
            $this->session->unset_userdata('ddlStatus');
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
		$this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlOpenClosed         = $this->common_model->searchKeyword3('ddlOpenClosed');		 
        $this->ddlReportType     = $this->common_model->searchKeyword3('ddlReportType');
        $this->session->set_userdata('pageName',$this->uri->segment(1));

// angela
 		$extraChecks = array();
//		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $this->check_airline_xo_common(); // $extraCheck_1['check_t'];
//		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
//		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $this->check_airline_xo_open(); // $extraCheck_2['check_t'];
//		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		


/* 		$extraChecks = array();
		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $extraCheck_1['check_t'];
		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $extraCheck_2['check_t'];
		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
*/		
		$extraChecks['extraCheck_ref_open'] = $this->check_refund_open();

		$extraCheck_3 = $this->check_accounts_closed();
		$extraChecks['extraCheck_sales_closed'] = $extraCheck_3['check_a_s'];
		$extraChecks['extraCheck_ref_closed'] = $extraCheck_3['check_a_r'];
		
		$orderby = " nGDS ASC";
		$groupby = " nGDS  ";

        $data['list_value'] = 
		$this->gds_report_model->getSummaryReport($extraChecks,$this->ddlOpenClosed,$this->ddlRefund, $orderby, $groupby, $this->ddlGDS);
		/*
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->gds_report_model->getGdsSummaryReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
            case 'Sales':
                $data['list_value'] = $this->gds_report_model->getSalesGdsSummaryReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
            case 'Refund':
                $data['list_value'] = $this->gds_report_model->getRefundGdsSummaryReport($extraCheck_t,$extraCheck_x,$this->ddlSalesType);
                break;
        }//end
		*/

        $mydata = $this->load->view("gds_report/export_files/gds_report_summary", $data,true);
        $_SESSION['gds_report_summary'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('gds_report/gds_report_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('gds_report/gds_report_summary', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
    function showAirlineDetails()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = AIRLINEREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
        $array_items = array('extraCheck_t' => '','extraCheck_x' => '');
        $this->session->unset_userdata($array_items);
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
            $this->session->unset_userdata('ddlOpenClosed'); 			
            $this->session->unset_userdata('ddlStatus');
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
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');		
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlOpenClosed         = $this->common_model->searchKeyword3('ddlOpenClosed'); 		
        $this->ddlReportType     = $this->common_model->searchKeyword3('ddlReportType');
        $this->session->set_userdata('pageName',$this->uri->segment(1));

// angela
 		$extraChecks = array();
//		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $this->check_airline_xo_common(); // $extraCheck_1['check_t'];
//		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
//		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $this->check_airline_xo_open(); // $extraCheck_2['check_t'];
//		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		

/* 		$extraChecks = array();
		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $extraCheck_1['check_t'];
		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $extraCheck_2['check_t'];
		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		
*/		$extraChecks['extraCheck_ref_open'] = $this->check_refund_open();

		$extraCheck_3 = $this->check_accounts_closed();
		$extraChecks['extraCheck_sales_closed'] = $extraCheck_3['check_a_s'];
		$extraChecks['extraCheck_ref_closed'] = $extraCheck_3['check_a_r'];
		
		$orderby = "airline ASC, refundtype DESC , nGDS ASC ";

        $data['list_value'] = $this->gds_report_model->getDetailReport($extraChecks,$this->ddlOpenClosed,$this->ddlRefund, $orderby);
/*
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->gds_report_model->getAirlineReport($extraCheck_t,$extraCheck_x);
                break;
            case 'Sales':
                $data['list_value'] = $this->gds_report_model->getSalesAirlineReport($extraCheck_t,$extraCheck_x);
                break;
            case 'Refund':
                $data['list_value'] = $this->gds_report_model->getRefundAirlineReport($extraCheck_t,$extraCheck_x);
                break;
        }//end
 */
        $mydata = $this->load->view("gds_report/export_files/airline_report_details", $data,true);
        $_SESSION['airline_report_details'] = $mydata;

        $this->load->view('gds_report/airline_report_details', $data);
    }//end function
    function showAirlineSummary()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = AIRLINEREPORT;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
        $array_items = array('extraCheck_t' => '','extraCheck_x' => '');
        $this->session->unset_userdata($array_items);
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
            $this->session->unset_userdata('ddlOpenClosed'); 			
            $this->session->unset_userdata('ddlStatus');
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
        $this->ddlStatus         = $this->common_model->searchKeyword3('ddlStatus');		
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlOpenClosed         = $this->common_model->searchKeyword3('ddlOpenClosed'); 		
        $this->ddlReportType     = $this->common_model->searchKeyword3('ddlReportType');
        $this->session->set_userdata('pageName',$this->uri->segment(1));


// angela

 		$extraChecks = array();
//		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $this->check_airline_xo_common(); // $extraCheck_1['check_t'];
//		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
//		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $this->check_airline_xo_open(); // $extraCheck_2['check_t'];
//		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		

/* 		$extraChecks = array();
		$extraCheck_1 = $this->check_airline_xo_common();
		$extraChecks['extraCheck_t'] = $extraCheck_1['check_t'];
		$extraChecks['extraCheck_x'] = $extraCheck_1['check_x'];
		
		$extraCheck_2 = $this->check_airline_xo_open();
		$extraChecks['extraCheck_t_sales_open'] = $extraCheck_2['check_t'];
		$extraChecks['extraCheck_x_sales_open'] = $extraCheck_2['check_x'];
		
*/
		$extraChecks['extraCheck_ref_open'] = $this->check_refund_open();

		$extraCheck_3 = $this->check_accounts_closed();
		$extraChecks['extraCheck_sales_closed'] = $extraCheck_3['check_a_s'];
		$extraChecks['extraCheck_ref_closed'] = $extraCheck_3['check_a_r'];
		
		$orderby = "airline ASC , nGDS ASC";
		$groupby = " airline, nGDS  ";
		$extra_column_main = " , airline as airline , " ;
		$extra_column_t = " , tb.nAirline as airline, ";
		$extra_column_x = ", xo.nAirline as airline, "; 

        $data['list_value'] = $this->gds_report_model->getSummaryReport1($extraChecks,$this->ddlOpenClosed,$this->ddlRefund, $orderby, $groupby, $extra_column_main, $extra_column_t, $extra_column_x  );
/*		
        $newdata = array('extraCheck_t' =>$extraCheck_t,'extraCheck_x' =>$extraCheck_x);
        $this->session->set_userdata($newdata);
        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->gds_report_model->getAirlineSummaryReport($extraCheck_t,$extraCheck_x);
                break;
            case 'Sales':
                $data['list_value'] = $this->gds_report_model->getSalesAirlineSummaryReport($extraCheck_t,$extraCheck_x);
                break;
            case 'Refund':
                $data['list_value'] = $this->gds_report_model->getRefundAirlineSummaryReport($extraCheck_t,$extraCheck_x);
                break;
        }//end
*/
        $mydata = $this->load->view("gds_report/export_files/airline_report_summary", $data,true);
        $_SESSION['airline_report_summary'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('gds_report/airline_report_summary', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('gds_report/airline_report_summary', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('gds_report/show_issuedBy',$data);
    }


}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
