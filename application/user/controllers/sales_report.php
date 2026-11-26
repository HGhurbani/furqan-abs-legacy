<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_report extends CI_Controller {



	function __construct()

	{

		parent::__construct();

		$this->load->model('sales_report_model');

	}



	function showlist()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = SALESREPORT;

        $this->btnVal    = REPRINT;

		$this->common_model->check_admin_authorization();



        $data['list_div_value']     = $this->sales_report_model->getActiveDivision();

        $data['list_loc_value']     = $this->sales_report_model->getActiveLocations();

        $data['list_payable']       = $this->sales_report_model->getPayableAccont();

/*

        $data['list_vendor_value']  = $this->sales_report_model->getActiveVendor();

        $data['list_airline_value'] = $this->sales_report_model->getActiveAirline();

        $data['list_country_value']	= $this->sales_report_model->getActiveCountry();

        $data['list_city_value']    = $this->sales_report_model->getCorrActiveCity();

        $data['list_hotel_value']   = $this->sales_report_model->getActiveHotels();

        $data['list_gds_value']     = $this->sales_report_model->getActiveGDS();

*/

        $data['list_emp_value']     = $this->sales_report_model->getActiveEmployee();

//        $data['list_acc_value']     = $this->sales_report_model->getAccounts();

//        $data['list_sub_acc']       = $this->sales_report_model->getSubAccountValues();



        $data['list_acc_value']     = $this->sales_report_model->getActiveAcName();

        $data['list_sub_acc']       = $this->sales_report_model->getActiveSubAccounts();





        if($this->session->userdata('pageName') == $this->uri->segment(1))

        {

            $this->ddlDivision      = $this->session->userdata('ddlDivision');

            $this->ddlLocation      = $this->session->userdata('ddlLocation');

            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');

            $this->txtToDate        = $this->session->userdata('txtToDate');

            $this->txtFromDate      = $this->session->userdata('txtFromDate');

            $this->ddlIssued        = $this->session->userdata('ddlIssued');

            $this->ddlPayableAccount = $this->session->userdata('ddlPayableAccount');

            $this->ddlAccount       = $this->session->userdata('ddlAccount');

            $this->ddlSubAccount    = $this->session->userdata('ddlSubAccount');

            $this->ddlRefund        = $this->session->userdata('ddlRefund');

            $this->ddlReport        = $this->session->userdata('ddlReport');



/*            			

			$this->ddlCountry       = $this->session->userdata('ddlCountry');

            $this->ddlCity          = $this->session->userdata('ddlCity');

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

            $this->ddlVendor        = $this->session->userdata('ddlVendor');

            $this->ddlSalesType     = $this->session->userdata('ddlSalesType');

*/        }

        else

        {

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

/*

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

            $this->session->unset_userdata('ddlSalesType');

            $this->session->unset_userdata('ddlVendor');

*/

            $this->session->unset_userdata('ddlPayableAccount');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

            $this->session->unset_userdata('ddlRefund');

            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('pageName');

        }



    if($this->ddlDivision != ""){

		$data['list_loc_value'] = $this->sales_report_model->filterLocation($this->ddlDivision);

    }

    else {

      reset($data['list_div_value']);

      $first_key = key($data['list_div_value']);

      $data['list_loc_value'] = $this->sales_report_model->filterLocation($first_key);

    }



    if($this->ddlCountry!= ""){

      $data['list_city_value'] = $this->sales_report_model->filterCity($this->ddlCountry);

    }

    else {

      $data['list_city_value']    = $this->sales_report_model->getCorrActiveCity();

    }





		$this->load->view('general/template_top');

		$this->load->view('sales_report/sales_report',$data);

		$this->load->view('general/template_bottom');

	}//end function



    function filterDivision()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));



		$this->common_model->check_admin_authorization();



		$this->ddlDivision = $this->input->post('ddlDivision');

		$this->ddlLocation = $this->input->post('ddlLocation');



		$data['list_loc_value'] = $this->sales_report_model->filterLocation($this->ddlDivision);

		$this->load->view('sales_report/show_location',$data);

	}//end function



    function filterSubAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccount');



        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

        $this->tableType = $this->session->userdata('tableType');



        $data['list_sub_acc'] = $this->sales_report_model->filterSubAccount($accName);

        $this->load->view('sales_report/show_sub', $data);

    }//end function







/*    function filterCountry()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        //site Name

        $this->ddlCountry = $this->input->post('ddlCountry');

        $this->ddlCity    = $this->input->post('ddlCity');



        $data['list_city_value'] = $this->sales_report_model->filterCity($this->ddlCountry);

        $this->load->view('sales_report/show_city',$data);

    }//end function

    function filterSubAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();



        $accName = $this->input->post('ddlAccount');



        $data['list_sub_acc'] = $this->sales_report_model->filterSubAccount($accName);

        $this->load->view('sales_report/show_sub',$data);

    }//end function



	function check_hotel(){



//        if (!empty($this->ddlIssued)) {

//            $extraCheck_h .= " AND hv.vIssuedBy='".$this->ddlIssued."'";

//        }//end if



        if (!empty($this->ddlDivision)) {

            $extraCheck_h = " AND hv.nDivision='".$this->ddlDivision."'";

        }//end if		 

		

		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 

	  	{

			$extraCheck_h .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

		}

		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {

            $extraCheck_h .= " AND sc.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND sc.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

        }//end if



        if (!empty($this->ddlPayableAccount)) {

			$extraCheck_h .= " AND sad.nDrAccount='".$this->ddlPayableAccount."'";

        }//end if



		

		if (!empty($this->ddlSubAccount)) {

			$extraCheck_h .= " AND sad.nAcHead='".$this->ddlSubAccount."'";

        }//end if

		



		if (!empty($this->ddlAccount)) {

			$extraCheck_h .= " AND sad.nDrAccount='".$this->ddlAccount."'";

        }//end if

		

		return $extraCheck_h;

	}	//end function

	

	

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

			$extraCheck_t .= " AND sc.dDate >= STR_TO_DATE(extra_check_accounts'" .$this->txtFromDate . "', '%d-%m-%Y' ) AND sc.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

                        $extraCheck_x .= " AND sc.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND sc.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

        }//end if



//        if (!empty($this->ddlIssued)) {

//            $extraCheck_t .= " AND tb.vIssuedBy='".$this->ddlIssued."'";

//            $extraCheck_x .= " AND xo.vIssuedBy='".$this->ddlIssued."'";

//        }//end if



        if (!empty($this->ddlPayableAccount)) {

			$extraCheck_t .= " AND sad.nDrAccount='".$this->ddlPayableAccount."'";

			$extraCheck_x .= " AND sad.nDrAccount='".$this->ddlPayableAccount."'";

        }//end if

		

		if (!empty($this->ddlSubAccount)) {

			$extraCheck_t .= " AND sad.nAcHead='".$this->ddlSubAccount."'";

			$extraCheck_x .= " AND sad.nAcHead='".$this->ddlSubAccount."'";

        }//end if



		if (!empty($this->ddlAccount)) {

			$extraCheck_t .= " AND sad.nDrAccount='".$this->ddlAccount."'";

			$extraCheck_x .= " AND sad.nDrAccount='".$this->ddlAccount."'";

        }//end if



		return array("check_t" => $extraCheck_t, "check_x" => $extraCheck_x);

	}//end function

		

*/		

		

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

/*			$extraCheck_accounts .= " AND sc.dDate >= STR_TO_DATE(extra_check_accounts'" .$this->txtFromDate . "', '%d-%m-%Y' ) AND sc.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

			$extraCheck_accounts_r .= " AND rc.dDate >= STR_TO_DATE(extra_check_accounts'" .$this->txtFromDate . "', '%d-%m-%Y' ) AND rc.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

*/

			$extraCheck_accounts .= " AND STR_TO_DATE( sc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";

			$extraCheck_accounts_r .= " AND STR_TO_DATE( rc.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";







		}//end if



/*		if (!empty($this->ddlPayableAccount)) {

			$extraCheck_accounts .= " AND sad.nDrAccount='".$this->ddlPayableAccount."'";

			$extraCheck_accounts_r .= " AND rcd.nDrAcc='".$this->ddlPayableAccount."'";

		}//end if

*/		



    $list = array();

    $get_acc_list =  $this->db->query( " SELECT nActHId FROM {PRE}AccountHeadControls WHERE nActHId = ".$this->ddlAccount." AND vSubLedgerType != 'N' ");

    if ($get_acc_list->num_rows() > 0)

    {

      foreach ($get_acc_list->result() as $key=>$row)

      {

        $list[] =$row->nActHId;

        // $adminGroupRow[] = $row;

      }//end for each

    } //end if





    if (!empty($this->ddlAccount)) {

      if(!in_array($this->ddlAccount,$list)){

        $extraCheck_accounts .= " AND sad.nDrAccount='".$this->ddlAccount."'";

        $extraCheck_accounts_r .= " AND rcd.nDrAcc='".$this->ddlAccount."'";

      }

      else{

        $subLedgerType = $this->common_model->get_field_name("AccountHeadControls","vSubLedgerType" ," WHERE nActHId = ".$this->ddlAccount." ");

        if($subLedgerType == 'V'){

          $extraCheck_t = " AND tfd.nAccHead = " . $this->ddlAccount ." ";

          $extraCheck_x = " AND xo.nPassengerAC IN ( select nVId FROM {PRE}VendorMaster WHERE nFId = ".  $this->ddlAccount ." )";

          $extraCheck_h = " AND hv.nAcCode =". $this->ddlAccount ." ";

          $extraCheck_or = " AND rr.nAccount IN ( select nVId FROM {PRE}VendorMaster WHERE nFId = ".  $this->ddlAccount ." )";

          if (!empty($this->ddlSubAccount)) {

            $subAc = explode("-",$this->ddlSubAccount);

            $extraCheck_t .= " AND tfd.nAcc =  " . $subAc[0] ." ";

            $extraCheck_x .= " AND xo.nPassengerAC = " . $subAc[0] ." ";

            $extraCheck_h .= " AND hv.nAcHead = " . $subAc[0] ." ";

            $extraCheck_or .= " AND rr.nAccount = " . $subAc[0] ." ";

          }//end if

        }//end if

        else{

          $extraCheck_accounts .= " AND sad.nAcHead ='".$this->ddlAccount."'";



          if (!empty($this->ddlSubAccount)) {

            $subAc = explode("-",$this->ddlSubAccount);

            $extraCheck_accounts .= " AND sad.nDrAccount ='".$subAc[0]."' AND sad.vTable = '". $subAc[1] . "'  ";

            $extraCheck_accounts_r .= " AND rcd.nDrAcc = '".$subAc[0]."' AND rcd.vTable = '". $subAc[1] . "' ";

          }//end if

          else{

            switch($subLedgerType){

              case 'A':

                $extraCheck_accounts_r .= " AND rcd.vTable = 'AG' " ; //AND rcd.nDrAcc IN (select nAId FROM  {PRE}Agent ) ";

                break;

              case 'C' :

                $extraCheck_accounts_r .= " AND rcd.vTable = 'C' "; //AND  rcd.nDrAcc IN (select nCId from {PRE}CustomerMaster ) ";

                break;

              case 'V':

                $extraCheck_accounts_r .= " AND rcd.vTable = 'V' ";// AND  rcd.nDrAcc IN (select nVId FROM {PRE}VendorMaster ) ";

                echo  $subLedgerType ;

                break;

              case 'O':

                $extraCheck_accounts_r .= " AND rcd.vTable = 'O' " ; // AND  rcd.nDrAcc IN (select nOId FROM {PRE}Other ) ";

                break;

            } //end switch



          }//end if 

        }//end if 



      }//end if

    }//end if





		if($this->ddlRefund == 'Sales')

		{

			$extraCheck_accounts_r = '';

		}

		else if ($this->ddlRefund == 'Refund')

		{

			$extraCheck_accounts = '';

		}

		

		return array("check_a_s" => $extraCheck_accounts, "check_a_r" => $extraCheck_accounts_r,"extraCheck_t" => $extraCheck_t ,"extraCheck_x" => $extraCheck_x,"extraCheck_h" => $extraCheck_h,"extraCheck_or" => $extraCheck_or);

	}









	function check_finance() 

	{



        if (!empty($this->ddlDivision)) {

            $extraCheck_finance_cr = " AND cr.nDivision='".$this->ddlDivision."'";

            $extraCheck_finance_cp = " AND cp.nDivision='".$this->ddlDivision."'";
			$extraCheck_finance_bb = " AND bb.nDivision='".$this->ddlDivision."'";
			$extraCheck_finance_bp = " AND bp.nDivision='".$this->ddlDivision."'";

        }//end if		 

		

		

		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 

	  	{

				$extraCheck_finance_cr .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

				$extraCheck_finance_cp .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_finance_bb .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
				$extraCheck_finance_bp .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

		}

		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {

/*			$extraCheck_finance_cr .= " AND cr.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND cr.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

            $extraCheck_finance_cp .= " AND cp.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND cp.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";

*/

			

			$extraCheck_finance_cr .= " AND STR_TO_DATE( cr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' )";

            $extraCheck_finance_cp .= " AND STR_TO_DATE( cp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
			$extraCheck_finance_bb .= " AND STR_TO_DATE( bb.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";
			$extraCheck_finance_bp .= " AND STR_TO_DATE( bp.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";

        }//end if



/*		if (!empty($this->ddlPayableAccount)) {

			$extraCheck_finance_cr .= " AND crd.vACode='".$this->ddlPayableAccount."'";

			$extraCheck_finance_cp .= " AND cpd.vACode='".$this->ddlPayableAccount."'";			

        }//end if

*/		

		if (!empty($this->ddlSubAccount)) {

			$extraCheck_finance_cr .= " AND crd.vAcSubCode='".$this->ddlSubAccount."'";

			$extraCheck_finance_cp .= " AND cpd.vAcSubCode='".$this->ddlSubAccount."'";
			$extraCheck_finance_bb .= " AND bbd.vAcSubCode='".$this->ddlSubAccount."'";
			$extraCheck_finance_bp .= " AND bpd.vAcSubCode='".$this->ddlSubAccount."'";			

        }//end if



		if (!empty($this->ddlAccount)) {

			$extraCheck_finance_cr .= " AND crd.vACode='".$this->ddlAccount."'";

			$extraCheck_finance_cp .= " AND cpd.vACode='".$this->ddlAccount."'";
			$extraCheck_finance_bb .= " AND bbd.vACode='".$this->ddlAccount."'";
			$extraCheck_finance_bp .= " AND bpd.vACode='".$this->ddlAccount."'";			

        }//end if

		

		return array("check_cr" => $extraCheck_finance_cr, "check_cp" => $extraCheck_finance_cp, "check_bbb" => $extraCheck_finance_bb,"check_bpbb" => $extraCheck_finance_bp);

	}



    function filterList()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = SALESREPORT;



        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

/*            $this->session->unset_userdata('ddlCountry');

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

*/

            $this->session->unset_userdata('ddlPayableAccount');

//            $this->session->unset_userdata('ddlVendor');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

  //          $this->session->unset_userdata('ddlSalesType');

            $this->session->unset_userdata('ddlRefund');

            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');

/*        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');

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

*/

        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');

//        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');

//        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');

        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');



        $this->session->set_userdata('pageName',$this->uri->segment(1));



// get where clause for query 

		$extraCheck_accounts = $this->check_accounts();

		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];

		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];

		

		$extraCheck_accounts = $this->check_finance();

		$extraCheck_finance_cr = $extraCheck_accounts['check_cr'];

		$extraCheck_finance_cp = $extraCheck_accounts['check_cp'];

		$extraCheck_finance_bb = $extraCheck_accounts['check_bbb'];
		$extraCheck_finance_bp = $extraCheck_accounts['check_bpbb'];

//echo $extraCheck_finance_bb;
        switch($this->ddlRefund)

        {

            case 'All':

                $data['list_value'] = $this->sales_report_model->getReport($extraCheck_accounts_s, $extraCheck_finance_cr,$extraCheck_finance_cp,$extraCheck_accounts_r,$extraCheck_finance_bb,$extraCheck_finance_bp);

            break;



            case 'Sales':

                $data['list_value'] = $this->sales_report_model->getSalesReport($extraCheck_accounts_s, $extraCheck_finance_cr,$extraCheck_finance_cp);

            break;



            case 'Refund':

                $data['list_value'] = $this->sales_report_model->getRefundReport($extraCheck_accounts_r, $extraCheck_finance_cr,$extraCheck_finance_cp);

            break;

        }//end

        $mydata = $this->load->view("sales_report/export_files/show_sales_report", $data,true);

        $_SESSION['sales_report'] = $mydata;



        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('sales_report/show_sales_report', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('sales_report/show_sales_report', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

    



    function filterSalesVendor()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = SALESREPORT;



        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

/*            $this->session->unset_userdata('ddlCountry');

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

*/

            $this->session->unset_userdata('ddlPayableAccount');

//            $this->session->unset_userdata('ddlVendor');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

  //          $this->session->unset_userdata('ddlSalesType');

            $this->session->unset_userdata('ddlRefund');

            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');

/*        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');

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

*/

        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');

//        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');

//        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');

        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');



        $this->session->set_userdata('pageName',$this->uri->segment(1));



// get where clause for query 

		$extraCheck_accounts = $this->check_accounts();

		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];

		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];

    $extraCheck_t = $extraCheck_accounts['extraCheck_t'];

    $extraCheck_x = $extraCheck_accounts['extraCheck_x'];

    $extraCheck_h = $extraCheck_accounts['extraCheck_h'];

    $extraCheck_or = $extraCheck_accounts['extraCheck_or'];

		

/*		$extraCheck_accounts = $this->check_finance();

		$extraCheck_finance_cr = $extraCheck_accounts['check_cr'];

		$extraCheck_finance_cp = $extraCheck_accounts['check_cp'];

	*/	



		$orderby = " account ASC , refundtype DESC ";



        switch($this->ddlRefund)

        {

            case 'All':

                $data['list_value'] = $this->sales_report_model->getAllReport_sales_vendor($extraCheck_accounts_s, $extraCheck_accounts_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or);

            break;



            case 'Sales':

                $data['list_value'] = $this->sales_report_model->getSalesReport_sales($extraCheck_accounts_s, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h);

            break;



            case 'Refund':

                $data['list_value'] = $this->sales_report_model->getRefundReport_sales($extraCheck_accounts_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or);

            break;

        }//end



        $mydata = $this->load->view("sales_report/export_files/show_sales_report_profit", $data,true);

        $_SESSION['sales_report_profit'] = $mydata;



        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('sales_report/show_sales_report_profit', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('sales_report/show_sales_report_profit', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

 

    function filterSales()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = SALESREPORT;



        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

/*            $this->session->unset_userdata('ddlCountry');

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

*/

            $this->session->unset_userdata('ddlPayableAccount');

//            $this->session->unset_userdata('ddlVendor');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

  //          $this->session->unset_userdata('ddlSalesType');

            $this->session->unset_userdata('ddlRefund');

            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');

/*        $this->ddlCountry        = $this->common_model->searchKeyword3('ddlCountry');

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

*/

        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');

//        $this->ddlVendor         = $this->common_model->searchKeyword3('ddlVendor');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');

//        $this->ddlSalesType      = $this->common_model->searchKeyword3('ddlSalesType');

        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');



        $this->session->set_userdata('pageName',$this->uri->segment(1));



// get where clause for query 

		$extraCheck_accounts = $this->check_accounts();

		$extraCheck_accounts_s = $extraCheck_accounts['check_a_s'];

		$extraCheck_accounts_r = $extraCheck_accounts['check_a_r'];

    $extraCheck_t = $extraCheck_accounts['extraCheck_t'];

    $extraCheck_x = $extraCheck_accounts['extraCheck_x'];

    $extraCheck_h = $extraCheck_accounts['extraCheck_h'];

    $extraCheck_or = $extraCheck_accounts['extraCheck_or'];

		

/*		$extraCheck_accounts = $this->check_finance();

		$extraCheck_finance_cr = $extraCheck_accounts['check_cr'];

		$extraCheck_finance_cp = $extraCheck_accounts['check_cp'];

	*/	



		$orderby = " (SELECT vCode FROM {PRE}VendorMaster WHERE nVId = account) ASC , refundtype DESC ,tktNumber ASC ";



        switch($this->ddlRefund)

        {

            case 'All':

                $data['list_value'] = $this->sales_report_model->getAllReport_sales($extraCheck_accounts_s, $extraCheck_accounts_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or);

            break;



            case 'Sales':

                $data['list_value'] = $this->sales_report_model->getSalesReport_sales($extraCheck_accounts_s, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h);

            break;



            case 'Refund':

                $data['list_value'] = $this->sales_report_model->getRefundReport_sales($extraCheck_accounts_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or);

            break;

        }//end



        $mydata = $this->load->view("sales_report/export_files/show_sales_report_nonVendor", $data,true);

        $_SESSION['sales_report_nonVendor'] = $mydata;



        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('sales_report/show_sales_report_nonVendor', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('sales_report/show_sales_report_nonVendor', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

 





}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

