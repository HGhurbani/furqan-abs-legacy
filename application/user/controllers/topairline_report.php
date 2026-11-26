<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Topairline_report extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('topairline_report_model');
	}
	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TOPTENAIRLINE;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();
        $data['list_div_value']     = $this->topairline_report_model->getActiveDivision();
        $data['list_loc_value']     = $this->topairline_report_model->getActiveLocation();
        $data['list_airline_value'] = $this->topairline_report_model->getActiveAirline();
        $data['list_payable']       = $this->topairline_report_model->getPayableAccount();
        $data['list_emp_value']     = $this->topairline_report_model->getActiveEmployee();
        $data['list_acc_value']     = $this->topairline_report_model->getActiveAccounts();
        $data['list_sub_acc']       = $this->topairline_report_model->getSubAccountValues();
        $data['list_gds_value']     = $this->topairline_report_model->getActiveGDS();
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
            $this->ddlPayableAccount = $this->session->userdata('ddlPayableAccount');
            $this->ddlAccount       = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount    = $this->session->userdata('ddlSubAccount');
            $this->ddlReportType    = $this->session->userdata('ddlReportType');
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
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlIssued');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('txtSector1');
            $this->session->unset_userdata('txtSector2');
            $this->session->unset_userdata('txtSector3');
            $this->session->unset_userdata('txtSector4');
            $this->session->unset_userdata('txtSector5');
            $this->session->unset_userdata('txtSector6');
            $this->session->unset_userdata('txtSector7');
            $this->session->unset_userdata('ddlGDS');
            $this->session->unset_userdata('txtDestination');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlReportType');
            $this->session->unset_userdata('ddlRefund');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('pageName');
        }

    if($this->ddlDivision != ""){
      $data['list_loc_value'] = $this->topairline_report_model->filterLocation($this->ddlDivision);
    }
    else {
      reset($data['list_div_value']);
      $first_key = key($data['list_div_value']);
      $data['list_loc_value'] = $this->topairline_report_model->filterLocation($first_key);
    }
		$this->load->view('general/template_top');
		$this->load->view('topairline_report/topairline_report',$data);
		$this->load->view('general/template_bottom');
	}//end function
    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		$data['list_loc_value'] = $this->topairline_report_model->filterLocation($this->ddlDivision);
		$this->load->view('topairline_report/show_location',$data);
	}//end function
    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlPayableAccount');
        $data['list_sub_acc'] = $this->topairline_report_model->filterSubAccount($accName);
        $this->load->view('topairline_report/show_sub',$data);
    }//end function
	
	
//////////////// Common checks for closed and open report \\\\\\\\\\\\\
// extrachecks for airline and xo
	function check_airline_common()
	{ 
        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline = '".$this->ddlAirline."'  ";
//            $extraCheck_t .= " AND ".$this->ddlAirline." IN (SELECT nArId FROM {PRE}Airline  WHERE vAirlineNo = tb.nAirline) ";
        }//end if

        if (!empty($this->ddlGDS)) {
            $extraCheck_t .= " AND tb.nGDS='".$this->ddlGDS."'";
        }//end if

        if (!empty($this->ddlPayableAccount)) {
            $extraCheck_t .= " AND tfd.nAccHead='".$this->ddlPayableAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_t .= " AND tfd.nAcc='".$this->ddlSubAccount."'";
        }//end if

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

		return $extraCheck_t;
	}//end function


///////////////// Checks for sales, refund Open	 \\\\\\\\\\\\\\\\\\\\\\\\\\\\

// Sales Check
	function check_airline_open()
	{ 

        if (!empty($this->ddlDivision)) {

            $extraCheck_t = " AND tb.nDivision='".$this->ddlDivision."'";
        }//end if		 
		
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_t .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
		}
		
		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_t .= " AND STR_TO_DATE( tb.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate  . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
        }//end if

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

        if (!empty($this->ddlPayableAccount)) {
			
        }//end if
		$extraCheck_refund_reg .=  " AND rrd.vTktType = 'Tkt' ";
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
		
		$extraCheck_accounts .= " AND scd.vType = 'Tkt' ";
		$extraCheck_accounts_r .= " AND rcd.vType = 'Tkt' ";
//payable		
		return array("check_a_s" => $extraCheck_accounts, "check_a_r" => $extraCheck_accounts_r);
	}

	
	
	
    function showReport()
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
            $this->session->unset_userdata('ddlAirline');
            $this->session->unset_userdata('ddlPayableAccount');
            $this->session->unset_userdata('ddlIssued');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('txtSector1');
            $this->session->unset_userdata('txtSector2');
            $this->session->unset_userdata('txtSector3');
            $this->session->unset_userdata('txtSector4');
            $this->session->unset_userdata('txtSector5');
            $this->session->unset_userdata('txtSector6');
            $this->session->unset_userdata('txtSector7');
            $this->session->unset_userdata('ddlGDS');
            $this->session->unset_userdata('txtDestination');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlReportType');
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
        $this->ddlReportType     = $this->common_model->searchKeyword3('ddlReportType');
        $this->session->set_userdata('pageName',$this->uri->segment(1));

		$extra_check_sales  =   '';
		$extra_check_refund =   '';	
		
		$extra_check_sales  =  $this->check_airline_common();
		$extra_check_refund =  $extra_check_sales;
		
		if($Report == "NetFare"){
			$extra_check_sales .=  $this->check_airline_open();
			$extra_check_refund .=  $this->check_refund_open();
		}
		else if( $Report == "Sales"){
			$extraCheck_s_r 	 = $this->check_accounts_closed();
			$extra_check_sales 	.= $extraCheck_s_r['check_a_s'];
			$extra_check_refund .=  $extraCheck_s_r['check_a_r'];
		}

		$orderby = " total_amt DESC ";
		$groupby = " airline ";
		$extra_column = " tb.nAirline as airline ";

        $data['list_value'] = $this->topairline_report_model->getReport($extra_check_sales, $extra_check_refund, $extra_column, $orderby, $groupby, $this->ddlRefund, $this->ddlReport);
				  
        $mydata = $this->load->view("topairline_report/export_files/show_airline_report", $data,true);
        $_SESSION['top_airline_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('topairline_report/show_airline_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('topairline_report/show_airline_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
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
    
		$extra_check_sales  =   '';
		$extra_check_refund =   '';	
		
		$extra_check_sales  =  $this->check_airline_common();
		$extra_check_refund =  $extra_check_sales;
		
		if($Report == "NetFare"){
			$extra_check_sales .=  $this->check_airline_open();
			$extra_check_refund .=  $this->check_refund_open();
		}
		else if( $Report == "Sales"){
			$extraCheck_s_r 	 = $this->check_accounts_closed();
			$extra_check_sales 	.= $extraCheck_s_r['check_a_s'];
			$extra_check_refund .=  $extraCheck_s_r['check_a_r'];
		}

		$orderby = " total_amt DESC ";
		$groupby = " Sector , Destination	 ";
		$extra_column = " td.vSector1 as Sector , td.vDestination as Destination		 ";

        $data['list_value'] = $this->topairline_report_model->getReport($extra_check_sales, $extra_check_refund, $extra_column, $orderby, $groupby, $this->ddlRefund, $this->ddlReport);
        $mydata = $this->load->view("topairline_report/export_files/show_sector_report", $data,true);
        $_SESSION['sector_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('topairline_report/show_sector_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('topairline_report/show_sector_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

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
    
		$extra_check_sales  =   '';
		$extra_check_refund =   '';	
		
		$extra_check_sales  =  $this->check_airline_common();
		$extra_check_refund =  $extra_check_sales;
		
		if($Report == "NetFare"){
			$extra_check_sales .=  $this->check_airline_open();
			$extra_check_refund .=  $this->check_refund_open();
		}
		else if( $Report == "Sales"){
			$extraCheck_s_r 	 = $this->check_accounts_closed();
			$extra_check_sales 	.= $extraCheck_s_r['check_a_s'];
			$extra_check_refund .=  $extraCheck_s_r['check_a_r'];
		}

		$orderby = " total_amt DESC ";
		$groupby = " airline , Sector , Destination	 ";
		$extra_column = " tb.nAirline as airline, td.vSector1 as Sector , td.vDestination as Destination ";

        $data['list_value'] = $this->topairline_report_model->getReport($extra_check_sales, $extra_check_refund, $extra_column, $orderby, $groupby, $this->ddlRefund, $this->ddlReport);
        $mydata = $this->load->view("topairline_report/export_files/show_sector_airline_report", $data,true);
        $_SESSION['sector_airline_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            //$this->load->view('general/template_top');
            $this->load->view('topairline_report/show_sector_airline_report', $data);
           // $this->load->view('general/template_bottom');
        else:
            $this->load->view('general/template_top');
            $this->load->view('topairline_report/show_sector_airline_report', $data);
            $this->load->view('general/template_bottom');
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('topairline_report/show_issuedBy',$data);
    }
	

}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
