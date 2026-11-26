<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Customer_report extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('customer_report_model');
    }
    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();
        $data['list_div_value']         = $this->customer_report_model->getActiveDivision();
        $data['list_loc_value']         = $this->customer_report_model->getActiveLocation();
//        $data['list_vendor_value']      = $this->customer_report_model->getActiveVendor();
        $data['list_airline_value']     = $this->customer_report_model->getActiveAirline();
        $data['list_country_value']     = $this->customer_report_model->getActiveCountry();
//        $data['list_payable']           = $this->customer_report_model->getActivePayable();
        $data['list_city_value']        = $this->customer_report_model->getCorrActiveCity();
        $data['list_emp_value']         = $this->customer_report_model->getActiveEmployee();
        $data['list_hotel_value']       = $this->customer_report_model->getActiveHotels();
//        $data['list_employee']          = $this->customer_report_model->getActiveEmployees();
        $data['list_acc_value']         = $this->customer_report_model->getActiveAcName();
        $data['list_sub_acc']           = $this->customer_report_model->getActiveSubAccounts();
 //       $data['list_gds_value']         = $this->customer_report_model->getActiveGDS();
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
      $data['list_loc_value'] = $this->customer_report_model->filterLocation($this->ddlDivision);
    }
    else {
      reset($data['list_div_value']);
      $first_key = key($data['list_div_value']);
      $data['list_loc_value'] = $this->customer_report_model->filterLocation($first_key);
    }

    if($this->ddlCountry!= ""){
      $data['list_city_value'] = $this->customer_report_model->filterCity($this->ddlCountry);
    }
    else {
      $data['list_city_value']    = $this->customer_report_model->getCorrActiveCity();
    }


        $this->load->view('general/template_top');
        $this->load->view('customer_report/customer_report', $data);
        $this->load->view('general/template_bottom');
    }//end function
    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');
        $data['list_loc_value'] = $this->customer_report_model->filterLocation($this->ddlDivision);
        $this->load->view('customer_report/show_location', $data);
    }//end function
    function filterCountry()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlCountry = $this->input->post('ddlCountry');
        $this->ddlCity = $this->input->post('ddlCity');
        $data['list_city_value'] = $this->customer_report_model->filterCity($this->ddlCountry);
        $this->load->view('customer_report/show_city', $data);
    }//end function
    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlSubAccount');
        $data['list_sub_acc'] = $this->customer_report_model->filterAccount($accName);
        $this->load->view('customer_report/show_sub', $data);
    }//end function


	function check_hotel(){
       if (!empty($this->ddlIssued)) {
            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";
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
	
	// extrachecks for airline and xo
	function check_airline_xo()
	{ 
        if (!empty($this->ddlIssued)) {
            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";
            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";
        }//end if

        if (!empty($this->ddlAirline)) {
            $extraCheck_t .= " AND tb.nAirline='".$this->ddlAirline."'";
            $extraCheck_x .= " AND xo.nAirline='".$this->ddlAirline."'";
        }//end if
		
		return array("check_t" => $extraCheck_t, "check_x" => $extraCheck_x);
	}//end function


	function check_accounts(){

        if (!empty($this->ddlDivision)) {

            $extraCheck_accounts = " AND sc.nDivision='".$this->ddlDivision."'";
            $extraCheck_accounts_r = " AND rc.nDivision='".$this->ddlDivision."'";
        }//end if		 
	
		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 
	  	{
				$extraCheck_accounts .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_accounts_r .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";				
		}		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {
			$extraCheck_accounts .= " AND STR_TO_DATE( sc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";
			$extraCheck_accounts_r .= " AND STR_TO_DATE( rc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtFromDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtToDate . "', '%Y-%m-%d' ) ";



		}//end if
/*
		if (!empty($this->ddlSubAccount)) {
			$extraCheck_accounts .= " AND sad.nAcHead='".$this->ddlSubAccount."'";

			$extraCheck_accounts_r .= "	AND ( 
				IF(rcd.vTable = 'AG' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc), 1,0) 
			OR
				IF(rcd.vTable = 'C' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
			OR  
				IF(rcd.vTable = 'V' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc), 1,0)
			OR
				IF(rcd.vTable = 'O' AND " . $this->ddlSubAccount . " = (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc), 1,0) 
			OR 	
				IF( rcd.vTable != 'AG' AND rcd.vTable != 'C' AND rcd.vTable != 'V' AND rcd.vTable != 'O',1,0) )";

		}//end if

		if (!empty($this->ddlAccount)) {
			$extraCheck_accounts .= " AND sad.nDrAccount='".$this->ddlAccount."'";
			$extraCheck_accounts_r .= " AND rcd.nDrAcc='".$this->ddlAccount."'";
		}//end if
*/	

		
		if (!empty($this->ddlSubAccount)) {

	    $acc = explode("-", $this->ddlSubAccount);
      $accGroup = $acc[0];
	    $table = $acc[1];
    
      //check if vtables are same
      $extraCheck_accounts .= " AND sad.vTable = '". $table ."' ";
      $extraCheck_accounts_r .= "  AND rcd.vTable = '". $table ."' ";
    

			$extraCheck_accounts .= "	AND ( 
					IF((sad.vTable = 'AG' OR sad.vTable = 'C' OR sad.vTable = 'V' OR sad.vTable = 'O') AND sad.nDrAccount= " . $accGroup. " , 1,0)
					OR 	
					IF( sad.vTable != 'AG' AND sad.vTable != 'C' AND sad.vTable != 'V' AND sad.vTable != 'O' AND sad.nDrAccount = " . $accGroup . " ,1,0) )";

			$extraCheck_accounts_r .= " AND rcd.nDrAcc='".$this->ddlSubAccount."'";
			
/*			$extraCheck_accounts_r .= "	 AND ( 
					IF(rcd.vTable = 'AG' AND " . $accGroup . " = (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc), 1,0) 
				OR
					IF(rcd.vTable = 'C' AND " . $accGroup. " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
				OR  
					IF(rcd.vTable = 'V' AND " . $accGroup. " = (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc), 1,0)
				OR
					IF(rcd.vTable = 'O' AND " . $accGroup. " = (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc), 1,0) 
				OR 	
					IF( rcd.vTable != 'AG' AND rcd.vTable != 'C' AND rcd.vTable != 'V' AND rcd.vTable != 'O' AND rcd.nDrAcc = " . $accGroup. " ,1,0) )";
	*/
		
			if(!empty($this->ddlAccount)){
				$extraCheck_accounts .= "	AND ( 
					IF((sad.vTable = 'AG' OR sad.vTable = 'C' OR sad.vTable = 'V' OR sad.vTable = 'O') AND sad.nAcHead = " . $this->ddlAccount . " , 1,0)
					OR 	
					IF( sad.vTable != 'AG' AND sad.vTable != 'C' AND sad.vTable != 'V' AND sad.vTable != 'O',1,0) )";

/*				$extraCheck_accounts_r .= "	AND ( 
					IF((rcd.vTable = 'AG' OR rcd.vTable = 'C' OR rcd.vTable = 'V' OR rcd.vTable = 'O') AND rcd.nDrAcc = " . $this->ddlAccount . " , 1,0)
					OR 	
					IF( rcd.vTable != 'AG' AND rcd.vTable != 'C' AND rcd.vTable != 'V' AND rcd.vTable != 'O',1,0) )";
*/

			$extraCheck_accounts_r .= "	AND ( 
				IF(rcd.vTable = 'AG' AND " . $this->ddlAccount . " = (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc), 1,0) 
			OR
				IF(rcd.vTable = 'C' AND " . $this->ddlAccount . " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
			OR  
				IF(rcd.vTable = 'V' AND " . $this->ddlAccount . " = (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc), 1,0)
			OR
				IF(rcd.vTable = 'O' AND " . $this->ddlAccount . " = (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc), 1,0) 
			OR 	
				IF( rcd.vTable != 'AG' AND rcd.vTable != 'C' AND rcd.vTable != 'V' AND rcd.vTable != 'O',1,0) )";



				} // end if
		} //end if			
		
		
		if($this->ddlRefund == 'Sales')
		{
			$extraCheck_accounts_r = '';
		}
		else if ($this->ddlRefund == 'Refund')
		{
			$extraCheck_accounts = '';
		}
		
		return array("check_a_s" => $extraCheck_accounts, "check_a_r" => $extraCheck_accounts_r);
	}



    function getReport()
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

// get where clause for query 
		$extraCheck_h =  $this->check_hotel();
		$extraCheck_t_x = $this->check_airline_xo();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];

		$extraCheck_accounts = $this->check_accounts();
		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];
		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];


// if data has been entered for hotel no airline data should be shown
		if((!empty($this->ddlHotel)) || (!empty($this->ddlCountry)) || (!empty($this->ddlCity)) || (!empty($this->txtCheckIn)) || (!empty($this->txtCheckOut))) {
            $extraCheck_t = " AND tb.nDivision=''";
            $extraCheck_x = " AND xo.nDivision=''";
		}
// if data has been entered for airline no hotel data should be shown		
		if ((!empty($this->ddlAirline)) || (!empty($this->txtSector1)) || (!empty($this->txtSector2)) ||  (!empty($this->txtSector3)) || (!empty($this->txtSector4)) || (!empty($this->txtSector5)) || (!empty($this->txtSector6)) || (!empty($this->txtSector7)) || (!empty($this->ddlGDS)) || (!empty($this->txtDestination))) {
			$extraCheck_h = " AND hv.nDivision=''";
		}

//		$orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, refundtype DESC,  sale_type ASC ";
		$orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, refundtype DESC,  sale_type ASC, tktNumber ASC  ";


        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->customer_report_model->getAllReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->customer_report_model->getSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->customer_report_model->getRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;
        }//end switch


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('customer_report/show_customer_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('customer_report/show_customer_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
    function getAirlineReport()
    { 
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORTBYAIRLINE;
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


// get where clause for query 
		$extraCheck_t_x = $this->check_airline_xo();
		$extraCheck_t = $extraCheck_t_x['check_t'];
		$extraCheck_x = $extraCheck_t_x['check_x'];
		$extraCheck_h = " AND hv.nDivision=''";

		$extraCheck_accounts = $this->check_accounts();
		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];
		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];

		
//		$orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, airline ASC, sale_type ASC, refundtype DESC  ";
         $orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, airline ASC, sale_type ASC, refundtype DESC, tktNumber ASC  ";		

		

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->customer_report_model->getAllReport($extraCheck_t,$extraCheck_h,$extraCheck_x, $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->customer_report_model->getSalesReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->customer_report_model->getRefundReport($extraCheck_t,$extraCheck_h,$extraCheck_x,$extraCheck_accounts_s,$extraCheck_accounts_r, $orderby,$this->ddlSalesType);
                break;
        }//end switch
		
        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('customer_report/show_airline_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('customer_report/show_airline_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
    function getHotelReport()
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


// get where clause for query 
		$extraCheck_h =  $this->check_hotel();

		$extraCheck_accounts = $this->check_accounts();
		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];
		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];


//		$orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, vName ASC, refundtype DESC ";
		$orderby = " nAcHead ASC , nDrAccount ASC, vTable ASC, vName ASC, refundtype DESC, tktNumber ASC ";

        switch($this->ddlRefund)
        {
            case 'All':
                $data['list_value'] = $this->customer_report_model->getAllHotelReport($extraCheck_h,  $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $this->ddlSalesType);
                break;

            case 'Sales':
                $data['list_value'] = $this->customer_report_model->getHotelSalesReport($extraCheck_h,  $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType, $this->ddlSalesType);
                break;

            case 'Refund':
                $data['list_value'] = $this->customer_report_model->getHotelRefundReport($extraCheck_h,  $extraCheck_accounts_s,$extraCheck_accounts_r, $orderby, $SalesType ,$this->ddlSalesType);
                break;
        }//end switch
	

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('customer_report/show_hotel_report', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('customer_report/show_hotel_report', $data);
        endif;
        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function
}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
