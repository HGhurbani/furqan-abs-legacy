<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refund_report extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('refund_report_model');
	}
	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDREPORT;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();
        $data['list_div_value']     = $this->refund_report_model->getActiveDivision();
//        $data['list_loc_value']     = $this->refund_report_model->getActiveLocations();
//        $data['list_vendor_value']  = $this->refund_report_model->getActiveVendor();
//        $data['list_payable']       = $this->refund_report_model->getPayableAccont();
        $data['list_airline_value'] = $this->refund_report_model->getActiveAirline();
//        $data['list_country_value']	= $this->refund_report_model->getActiveCountry();
//        $data['list_city_value']    = $this->refund_report_model->getCorrActiveCity();
        $data['list_emp_value']     = $this->refund_report_model->getActiveEmployee();
//        $data['list_hotel_value']   = $this->refund_report_model->getActiveHotels();
        $data['list_acc_value']     = $this->refund_report_model->getAccounts();
       $data['list_sub_acc']       = $this->refund_report_model->getSubAccountValues();
//      $data['list_gds_value']     = $this->refund_report_model->getActiveGDS();
		
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
            $this->session->unset_userdata('pageName');
        }


    if($this->ddlDivision != ""){
		$data['list_loc_value'] = $this->refund_report_model->filterLocation($this->ddlDivision);
    }
    else {
      reset($data['list_div_value']);
      $first_key = key($data['list_div_value']);
      $data['list_loc_value'] = $this->refund_report_model->filterLocation($first_key);
    }

    if($this->ddlCountry!= ""){
      $data['list_city_value'] = $this->refund_report_model->filterCity($this->ddlCountry);
    }
    else {
      $data['list_city_value']    = $this->refund_report_model->getCorrActiveCity();
    }

		$this->load->view('general/template_top');
		$this->load->view('refund_report/refund_report',$data);
		$this->load->view('general/template_bottom');
	}//end function
    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		$data['list_loc_value'] = $this->refund_report_model->filterLocation($this->ddlDivision);
		$this->load->view('refund_report/show_location',$data);
	}//end function
	function filterCountry()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlCountry = $this->input->post('ddlCountry');
		$this->ddlCity    = $this->input->post('ddlCity');
		$data['list_city_value'] = $this->refund_report_model->filterCity($this->ddlCountry);
		$this->load->view('refund_report/show_city',$data);
	}//end function
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
        $this->session->set_userdata('pageName',$this->uri->segment(1));
		
        if (!empty($this->ddlDivision)) {
            $extraCheck_r = " AND rr.nDivision=".$this->ddlDivision." ";
            $extraCheck_c = " AND rc.nDivision=".$this->ddlDivision." ";
        }//end if
		
        if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))  {
            $extraCheck_r .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
            $extraCheck_c .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
        }//end if

		if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_r .= " AND STR_TO_DATE( rr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
            $extraCheck_c .= " AND STR_TO_DATE( rc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
        }//end if

       if (!empty($this->ddlIssued)) {
            $extraCheck_r .= " AND rrd.vIssuedBy ='".$this->ddlIssued."'";
            $extraCheck_c .= " AND rrd.vIssuedBy ='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_r .= " AND rrd.vAirline = (select vAirlineNo from {PRE}Airline where nArId =".$this->ddlAirline.") AND rrd.vTktType IN ('Tkt','Xo') ";
            $extraCheck_c .= " AND rcd.nAirline = (select vAirlineNo from {PRE}Airline where nArId =".$this->ddlAirline.") AND rcd.vType IN ('Tkt','Xo') ";
        }//end if
		
		if (!empty($this->ddlSubAccount) && !empty($this->ddlAccount)) {
			$extraCheck_r .= " AND rrb.vAcSubCode='".$this->ddlSubAccount."'";

			$extraCheck_c .= "	AND ( 
				IF(rcd.vTable = 'AG' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc), 1,0) 
			OR
				IF(rcd.vTable = 'C' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
			OR  
				IF(rcd.vTable = 'V' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
			OR
				IF(rcd.vTable = 'O' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc), 1,0) 
			OR 	
				IF( rcd.vTable != 'AG' AND rcd.vTable != 'C' AND rcd.vTable != 'V' AND rcd.vTable != 'O',1,0) )";

		}//end if


        if (!empty($this->ddlAccount)) {
            //$extraCheck_r .= " AND SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', 2 ),'-',1) = '".$this->ddlAccount."' ";
            $extraCheck_r .= " AND rrb.nAcCode = '".$this->ddlAccount."' ";
            $extraCheck_c .= " AND rcd.nDrAcc ='".$this->ddlAccount."' ";
        }//end if

		
       if (!empty($this->ddlSalesType) && $this->ddlSalesType != 'All') {
            $extraCheck_r .= " AND rrd.vTktType ='".$this->ddlSalesType."'";
            $extraCheck_c .= " AND rcd.vType ='".$this->ddlSalesType."'";
        }//end if
		
		$orderby = " sale_type, rfStatus DESC, vDocNumber ASC";

       $data['list_value'] = $this->refund_report_model->getReport($extraCheck_r,$extraCheck_c, $orderby,$this->ddlRefund);		
       $mydata = $this->load->view("refund_report/export_files/show_report", $data,true);
       $_SESSION['refund_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('refund_report/show_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('refund_report/show_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
	function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');
        $accSubName = $this->input->post('ddlSubAccount');		
        $data['list_sub_acc'] = $this->refund_report_model->filterSubAccount($accName);
        $this->load->view('refund_report/show_sub',$data);
    }//end function
    

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('refund_report/show_issuedBy',$data);
    }


}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
