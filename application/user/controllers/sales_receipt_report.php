<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sales_receipt_report extends CI_Controller {



    function __construct()

    {

        parent::__construct();

        $this->load->model('sales_receipt_report_model');

    }



    function showlist()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = SALESRECEIPTREPORT;

        $this->btnVal    = REPORT;

        $this->common_model->check_admin_authorization();



        $data['list_div_value']     = $this->sales_receipt_report_model->getActiveDivision();

        $data['list_loc_value']     = $this->sales_receipt_report_model->getActiveLocations();

        $data['list_acc_value']     = $this->sales_receipt_report_model->getActiveAcName();

        $data['list_emp']       = $this->sales_receipt_report_model->getActiveEmployees();
		$data['doc_type']        = $this->sales_receipt_report_model->docType();


        if($this->session->userdata('pageName') == $this->uri->segment(1))

        {

            $this->ddlDivision   = $this->session->userdata('ddlDivision');

            $this->ddlLocation   = $this->session->userdata('ddlLocation');

            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

            $this->txtToDate     = $this->session->userdata('txtToDate');

            $this->txtFromDate   = $this->session->userdata('txtFromDate');

            $this->ddlAccount    = $this->session->userdata('ddlAccount'). '-'. $this->session->userdata('tableType');

            $this->ddlIssued     = $this->session->userdata('ddlIssued');

            $this->session->unset_userdata('tableType');

        }

        else

        {

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlIssued');

            $this->session->unset_userdata('tableType');

            $this->session->unset_userdata('pageName');

        }



        $this->load->view('general/template_top');

        $this->load->view('sales_receipt_report/sales_receipt_report',$data);

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



        $data['list_loc_value'] = $this->sales_receipt_report_model->filterLocation($this->ddlDivision);

        $this->load->view('sales_receipt_report/show_location',$data);

    }//end function



    function filterAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccGroup');



        $this->ddlAccount    = $this->session->userdata('ddlAccount');



        $data['list_acc_value'] = $this->sales_receipt_report_model->filterAccount($accName);

        $this->load->view('sales_receipt_report/show_account', $data);

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

            $this->session->unset_userdata('ddlSalesType');

          $this->session->unset_userdata('ddlEmpl');

            $this->session->unset_userdata('ddlDocType');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision   = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation   = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2  = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate     = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate   = $this->common_model->searchKeyword3('txtFromDate');

        $this->ddlAccount    = $this->common_model->searchKeyword3('ddlSalesType');

        $this->ddlDocType     = $this->common_model->searchKeyword3('ddlDocType');

       $this->ddlIssued     = $this->common_model->searchKeyword3('ddlEmpl');



       $this->session->set_userdata('pageName',$this->uri->segment(1));



        if (!empty($this->ddlDivision)) {

          $extraCheck_sr = " AND sr.nDivision='".$this->ddlDivision."'";

        }//end if



        if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))

        {

          $extraCheck_sr .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

        }



        if ($this->txtToDate != '' && $this->txtFromDate !='') {

//          $extraCheck_sr .= " AND STR_TO_DATE( sr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' ) ";

            $extraCheck_sr .= " AND sr.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND sr.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";



        }//end if



        if (!empty($this->ddlAccount)) {

          

            $extraCheck_sr .= " AND srd.vACodeDr='".$this->ddlAccount."'";

        }//end if



        if (!empty($this->ddlIssued)) {

          $extraCheck_sr .= " AND sr.vEnteredBy ='". $this->common_model->get_field_name('Admin','nId', " WHERE nId = ".$this->ddlIssued)."'";

        }//end if

 if(!empty($this->ddlDocType))
        {
          switch($this->ddlDocType)
            {
                case "UD":
				 $extraCheck_sr.=  "ORDER BY sr.vDocNumber, sr.dDate, CardName ";
				 $page_name="detail_report";
				break;
				 case "UDS":
				   $extraCheck_sr.=  "ORDER BY staff";
				    $page_name="detail_report_staff";
				break;
				 case "UDD":
				  $extraCheck_sr.=  "ORDER BY sr.dDate";
				   $page_name="detail_report_date";
				break;
				
				 
			}
			}



        $groupby = '';
//$orderby =  "sr.vDocNumber, sr.dDate, CardName ";
        $group_select = '';

       // $orderby =  " sr.vDocNumber, sr.dDate, CardName ";



        $data['fromDate'] = $this->txtFromDate;

        $data['toDate'] = $this->txtToDate;

        $data['search_date'] = $this->txtFromDate;

        $data['search_acc'] = $this->ddlAccount;

        //$data['search_tabletype'] = $this->tableType;

 

        $data['list_value'] = $this->sales_receipt_report_model->getReports($extraCheck_sr,$orderby,$group_select, $groupby);



        $mydata = $this->load->view("sales_receipt_report/export_files/detail_report", $data,true);

        $_SESSION['detail_report'] = $mydata;





        if(trim($this->session->userdata('backURL')) != ''):

          $this->load->view('general/template_top');

          $this->load->view('sales_receipt_report/'.$page_name, $data);

          $this->load->view('general/template_bottom');

        else:

          $this->load->view('sales_receipt_report/'.$page_name, $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function
	 function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp'] = $this->sales_receipt_report_model->getActiveEmployees();
      $this->load->view('sales_receipt_report/show_issuedBy',$data);
    }

}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

