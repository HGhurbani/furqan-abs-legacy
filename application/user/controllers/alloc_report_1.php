<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alloc_report extends CI_Controller {



    function __construct()

    {

        parent::__construct();

        $this->load->model('alloc_report_model');

		 $this->load->model('ar_allocation_model');

    }



    function showlist()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = ALLOCATIONREPORT;

        $this->btnVal    = REPRINT;

        $this->common_model->check_admin_authorization();



        $data['list_div_value']      = $this->alloc_report_model->getActiveDivision();

        $data['list_loc_value']      = $this->alloc_report_model->getActiveLocations();

        $data['list_acc_group']      = $this->alloc_report_model->getAccountGroup();

        $data['list_acc_value']      = $this->alloc_report_model->getActiveAcName();

        $data['list_sub_acc']        = $this->alloc_report_model->getActiveSubAccounts();

        $data['list_customer_value'] = $this->alloc_report_model->getActiveCustomerValue();

        $data['list_emp_value']      = $this->alloc_report_model->getActiveEmployee();

        $data['list_agent']          = $this->alloc_report_model->getAgentAccounts();



        if($this->session->userdata('pageName') == $this->uri->segment(1))

        {

            $this->ddlDivision   = $this->session->userdata('ddlDivision');

            $this->ddlLocation   = $this->session->userdata('ddlLocation');

            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

            $this->txtToDate     = $this->session->userdata('txtToDate');

            $this->txtFromDate   = $this->session->userdata('txtFromDate');

            $this->txtFromYear   = $this->session->userdata('txtFromYear');

            $this->txtToYear     = $this->session->userdata('txtToYear');

            $this->txtTillDate   = $this->session->userdata('txtTillDate');

            $this->ddlCustomer   = $this->session->userdata('ddlCustomer');

            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');

            $this->ddlAccount    = $this->session->userdata('ddlAccount');

            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

            $this->tableType     = $this->session->userdata('tableType');

            $this->ddlEmployee   = $this->session->userdata('ddlEmployee');

            $this->ddlAgent      = $this->session->userdata('ddlAgent');

            $this->pageName      = $this->session->userdata('pageName');

        }

        else

        {

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('txtFromYear');

            $this->session->unset_userdata('txtToYear');

            $this->session->unset_userdata('txtTillDate');

            $this->session->unset_userdata('ddlAccGroup');

            $this->session->unset_userdata('ddlCustomer');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

            $this->session->unset_userdata('tableType');

            $this->session->unset_userdata('ddlEmployee');

            $this->session->unset_userdata('ddlAgent');

            $this->session->unset_userdata('pageName');

        }



        $this->load->view('general/template_top');

        $this->load->view('alloc_report/alloc_report',$data);

        $this->load->view('general/template_bottom');

    }//end function



    function filterDivision()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();



        $this->ddlDivision = $this->input->post('ddlDivision');

        $this->ddlLocation = $this->input->post('ddlLocation');



        $data['list_loc_value'] = $this->alloc_report_model->filterLocation($this->ddlDivision);

        $this->load->view('alloc_report/show_location',$data);

    }//end function



    function filterAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccGroup');



        $this->ddlAccount    = $this->session->userdata('ddlAccount');



        $data['list_acc_value'] = $this->alloc_report_model->filterAccount($accName);

        $this->load->view('alloc_report/show_account', $data);

    }//end function



    function filterSubAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlAccount');



         $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

        $this->tableType = $this->session->userdata('tableType');



        $data['list_sub_acc'] = $this->alloc_report_model->filterSubAccount($accName);

        $this->load->view('alloc_report/show_sub', $data);

    }//end function



    function getReceivable()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = ALLOCATIONREPORT;



        $this->common_model->check_admin_authorization();



        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('txtFromYear');

            $this->session->unset_userdata('txtToYear');

            $this->session->unset_userdata('txtTillDate');

            $this->session->unset_userdata('ddlAccGroup');

            $this->session->unset_userdata('ddlCustomer');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

            $this->session->unset_userdata('tableType');

            $this->session->unset_userdata('ddlEmployee');

            $this->session->unset_userdata('ddlAgent');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');

        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');

        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');

        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');

        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');

        $this->ddlCustomer       = $this->common_model->searchKeyword3('ddlCustomer');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');

        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');

        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');



        $this->session->set_userdata('pageName',$this->uri->segment(1));



        if (!empty($this->ddlDivision)) {

            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";

            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";

            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";

            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";

            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";

            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";

            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
			$extraCheck_rc = " AND rc.nDivision='".$this->ddlDivision."'";

        }//end if



        if (!empty($this->ddlLocation)) {

            $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";

            $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";

            $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";

            $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";

            $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";

            $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";

            $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
			 $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";

        }//end if



        /*if (!empty($this->ddlLocation2)) {

            $extraCheck_cr .= " OR cr.vLocation='".$this->ddlLocation2."'";

            $extraCheck_cp .= " OR cp.vLocation='".$this->ddlLocation2."'";

            $extraCheck_bb .= " OR bb.vLocation='".$this->ddlLocation2."'";

            $extraCheck_bp .= " OR bp.vLocation='".$this->ddlLocation2."'";

            $extraCheck_jv .= " OR jv.vLocation='".$this->ddlLocation2."'";

            $extraCheck_cv .= " OR cv.vLocation='".$this->ddlLocation2."'";

            $extraCheck_dv .= " OR dv.vLocation='".$this->ddlLocation2."'";

        }//end if*/



        if ($this->txtToDate != '' && $this->txtFromDate !='') {

            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";

            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
			 $extraCheck_rc .= " AND rc.dDate >= '" .$this->txtFromDate . "' AND rc.dDate <= '" . $this->txtToDate . "'";

        }//end if



        if ($this->txtToYear != '' && $this->txtFromYear !='') {

            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";

            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
			 $extraCheck_rc .= " AND rc.YEAR(dDate) >= '" .$this->txtFromYear . "' AND rc.YEAR(dDate) <= '" . $this->txtToYear . "'";

        }//end if



        if (!empty($this->ddlCustomer)) {



        }//end if



        if (!empty($this->ddlAccGroup)) {



        }//end if



        if (!empty($this->ddlAccount)) {

            $extraCheck_cr .= " AND crd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_cp .= " AND cpd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_bb .= " AND bbd.vAcSubCode='".$this->ddlAccount."'";

            $extraCheck_bp .= " AND bpd.vAcSubCode='".$this->ddlAccount."'";
			
        }//end if



        if (!empty($this->ddlSubAccount)) {

            $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";

            $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";

            $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";

            $extraCheck_rc .= " AND rcd.nDrAcc='".$this->ddlSubAccount."'";

        }//end if



        if (!empty($this->ddlEmployee)) {

            /*$extraCheck_cr .= " AND cr.nAcCode='".$this->ddlEmployee."'";

            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlEmployee."'";

            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlEmployee."'";

            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlEmployee."'";*/

        }//end if



        if (!empty($this->ddlAgent)) {

            /*$extraCheck_cr .= " AND cr.vSubCode='".$this->ddlAgent."'";

            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlAgent."'";

            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlAgent."'";

            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlAgent."'";*/

        }//end if



        switch($this->ddlReport)

        {

            case "Detail":

                $page = 'fcdc_detail_report';

                break;



            case "Summary":

                $page = 'fcdc_report';

                break;



            case "Outstanding":

                $page = 'fcdc_report';

                break;

        }//end switch



        $data['list_value'] = $this->alloc_report_model->getReceivable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_rc);

        $data['fromDate'] = $this->txtFromDate;

        $data['toDate'] = $this->txtToDate;

        $mydata = $this->load->view("alloc_report/export_files/receivable_report", $data,true);

        $_SESSION['receivable_allocation_report'] = $mydata;



        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('alloc_report/receivable_report', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('alloc_report/receivable_report', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function



    function getPayable()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = ALLOCATIONREPORT;



        $this->common_model->check_admin_authorization();



        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('txtFromYear');

            $this->session->unset_userdata('txtToYear');

            $this->session->unset_userdata('txtTillDate');

            $this->session->unset_userdata('ddlAccGroup');

            $this->session->unset_userdata('ddlCustomer');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

            $this->session->unset_userdata('tableType');

            $this->session->unset_userdata('ddlEmployee');

            $this->session->unset_userdata('ddlAgent');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');

        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');

        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');

        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');

        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');

        $this->ddlCustomer       = $this->common_model->searchKeyword3('ddlCustomer');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');

        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');

        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');



        $this->session->set_userdata('pageName',$this->uri->segment(1));



       $extra_check = $this->common_checks();

        $extraCheck_cr = $extra_check['cr'];

        $extraCheck_cp = $extra_check['cp'];

        $extraCheck_bb = $extra_check['bb'];

        $extraCheck_bp = $extra_check['bp'];

        $extraCheck_jv = $extra_check['jv'];

        $extraCheck_cv = $extra_check['cv'];

        $extraCheck_dv = $extra_check['dv'];

        $extraCheck_ac = $extra_check['ac'];
		$extraCheck_op = $extra_check['op'];

        $extraCheck_ac_top = $extra_check['ac_top_acc'];

        $extraCheck_ad = $extra_check['ad'];

        $extraCheck_ad_top = $extra_check['ad_top_acc'];

        $extraCheck_rc = $extra_check['rc'];
		$acc=$this->ddlAccount;
if($this->ddlAccount==55){
	$string=1;
}
if($this->ddlAccount!=55){

        if($this->tableType == "C" || $this->tableType == "O" || $this->tableType == "A"){

          $extraCheck_cr .= " AND crd.vMode = 'Cr' ";

          $extraCheck_cp .= " AND cpd.vMode = 'Cr' ";

          $extraCheck_bb .= " AND bbd.vMode = 'Cr' ";

          $extraCheck_bp .= " AND bpd.vMode = 'Cr' ";

          $extraCheck_cv .= " AND cvd.vMode = 'Cr' ";

          $extraCheck_dv .= " AND dvd.vMode = 'Cr' ";

          $extraCheck_jv .= " AND jvd.vMode = 'Cr' ";

          $extraCheck_ac .= " AND acd.vMode = 'Cr' ";

          $extraCheck_ad .= " AND aed.vMode = 'Cr' ";
		   $extraCheck_op .= " AND opd.vMode = 'Cr' ";

        }

        else{



          $extraCheck_cr .= " AND crd.vMode = 'Dr' ";

          $extraCheck_cp .= " AND cpd.vMode = 'Dr' ";

          $extraCheck_bb .= " AND bbd.vMode = 'Dr' ";

          $extraCheck_bp .= " AND bpd.vMode = 'Dr' ";

          $extraCheck_cv .= " AND cvd.vMode = 'Dr' ";

          $extraCheck_dv .= " AND dvd.vMode = 'Dr' ";

          $extraCheck_jv .= " AND jvd.vMode = 'Dr' ";

          $extraCheck_ac .= " AND acd.vMode = 'Dr' ";

          $extraCheck_ad .= " AND aed.vMode = 'Dr' ";

		  $extraCheck_op .= " AND opd.vMode = 'Dr' ";

          $extraCheck_ac_top .= " AND ac.vTransMode = 'Dr' ";

          $extraCheck_ad_top .= " AND ad.vTransMode = 'Dr' ";

}}else{
	
	$extraCheck_cr .= " AND crd.vMode = 'Dr' ";

          $extraCheck_cp .= " AND cpd.vMode = 'Dr' ";

          $extraCheck_bb .= " AND bbd.vMode = 'Dr' ";

          $extraCheck_bp .= " AND bpd.vMode = 'Dr' ";

          $extraCheck_cv .= " AND cvd.vMode = 'Dr' ";

          $extraCheck_dv .= " AND dvd.vMode = 'Dr' ";

          $extraCheck_jv .= " AND jvd.vMode = 'Dr' ";

          $extraCheck_ac .= " AND acd.vMode = 'Dr' ";

          $extraCheck_ad .= " AND aed.vMode = 'Dr' ";
		   $extraCheck_op .= " AND opd.vMode = 'Dr' ";

          $extraCheck_ac_top .= " AND ac.vTransMode = 'Dr' ";

          $extraCheck_ad_top .= " AND ad.vTransMode = 'Dr' ";
}

        

        $extraCheck_rc_rr .= $this->ddlAccount." = ( SELECT rr.nAccount FROM {PRE}RefundRegister rr 

                  LEFT JOIN {PRE}RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId 

                  WHERE rr.nAccount = ".$this->ddlAccount."  AND 'V' = '".$this->tableType."' AND 

                  rrd.vTktType = rcd.vType AND rrd.nTktNum = rcd.nTicketNumber ) ";


/*


          $extraCheck_cr .= " AND crd.nAcAllocatedAmt <= crd.nAmount";

          $extraCheck_cp .= " AND cpd.nAcAllocatedAmt <= cpd.nAmount";

          $extraCheck_bb .= " AND bbd.nAcAllocatedAmt <= bbd.nAmount";

          $extraCheck_bp .= " AND bpd.nAcAllocatedAmt <= bpd.nAmount";

          $extraCheck_cv .= " AND cvd.nAcAllocatedAmt <= cvd.nAmount";

          $extraCheck_dv .= " AND dvd.nAcAllocatedAmt <= dvd.nAmount";

          $extraCheck_jv .= " AND jvd.nAcAllocatedAmt <= jvd.nAmount";

          $extraCheck_ac .= " AND acd.nAcAllocatedAmt <= acd.nAmount";

          $extraCheck_ad .= " AND aed.nAcAllocatedAmt <= aed.nAmount";

          $extraCheck_ac_top .= " AND ac.nAcAllocatedAmt <= ac.vAmountDC";

          $extraCheck_ad_top .= " AND ad.nAcAllocatedAmt <= ad.vAmountDC";

          $extraCheck_rc .= " AND rcd.nAcAllocatedAmt <= rcd.vPaidAmt";

          $extraCheck_rc_rr .= "AND  rcd.nAcAllocatedAmt_rr <= rcd.vNetAmt";
		   $extraCheck_op .= " AND opd.nAcAllocatedAmt <= opd.vBalDC";

*/
      


        switch($this->ddlReport)

        {

            case "Detail":

                $page = 'fcdc_detail_report';

                break;



            case "Summary":

                $page = 'fcdc_report';

                break;



            case "Outstanding":

                $page = 'fcdc_report';

                break;

        }//end switch

$data['list_value']  = $this->alloc_report_model->getFilterList($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc,$extraCheck_rc_rr,$string,$extraCheck_op,$acc);


       // $data['list_value'] = $this->alloc_report_model->getPayable($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv);

        $data['fromDate'] = $this->txtFromDate;

        $data['toDate'] = $this->txtToDate;
		
 $data['ddlAccount'] = $this->ddlAccount;
  $data['ddlSubAccount'] = $this->ddlSubAccount;
   $data['ddltype'] =$this->tableType;
        $mydata = $this->load->view("alloc_report/export_files/payable_report", $data,true);

        $_SESSION['payable_allocation_report'] = $mydata;



        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('alloc_report/payable_report', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('alloc_report/payable_report', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

    function getIssuedByMultiLoc()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();

      $this->load->view('alloc_report/show_issuedBy',$data);

    }

 function common_checks(){

      $extraCheck_cr = " WHERE cr.nDivision='".$this->ddlDivision."'";

      $extraCheck_cp = " WHERE cp.nDivision='".$this->ddlDivision."'";

      $extraCheck_bb = " WHERE bb.nDivision='".$this->ddlDivision."'";

      $extraCheck_bp = " WHERE bp.nDivision='".$this->ddlDivision."'";

      $extraCheck_jv = " WHERE jv.nDivision='".$this->ddlDivision."'";

      $extraCheck_cv = " WHERE cv.nDivision='".$this->ddlDivision."'";

      $extraCheck_dv = " WHERE dv.nDivision='".$this->ddlDivision."'";

      $extraCheck_ac = " WHERE ac.nDivision='".$this->ddlDivision."'";

      $extraCheck_ad = " WHERE ad.nDivision='".$this->ddlDivision."'";

      $extraCheck_rc = " WHERE rc.nDivision='".$this->ddlDivision."'";
	  $extraCheck_op = " WHERE op.nDivision='".$this->ddlDivision."'";


if($this->ddlLocation!=0){
      $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";

      $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";

      $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";

      $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";

      $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";

      $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";

      $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";

      $extraCheck_ac.= " AND ac.vLocation='".$this->ddlLocation."'";

      $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";

      $extraCheck_rc .= " AND rc.vLocation='".$this->ddlLocation."'";
	  $extraCheck_op .= " AND op.vLocation='".$this->ddlLocation."'";
}


      $extraCheck_ac_top = $extraCheck_ac;

      $extraCheck_ad_top = $extraCheck_ad;
	 // echo $this->ddlAccount."--".$this->ddlSubAccount;
	  if($this->ddlAccount==39 || $this->ddlAccount==211 || $this->ddlAccount==55){
	$this->tableType='A';
	  }else{
		  
		  $a=$this->ddlAccount;
	$this->ddlAccount=$this->ddlSubAccount;
	$this->ddlSubAccount=$a;
	  }

      $extraCheck_cr .= " AND crd.vACode = '".$this->ddlAccount."' AND crd.vTable = '".$this->tableType."' ";

      $extraCheck_cp .= " AND cpd.vACode = '".$this->ddlAccount."' AND cpd.vTable = '".$this->tableType."' ";

      $extraCheck_bb .= " AND bbd.vACode = '".$this->ddlAccount."' AND bbd.vTable = '".$this->tableType."' ";

      $extraCheck_bp .= " AND bpd.vACode = '".$this->ddlAccount."' AND bpd.vTable = '".$this->tableType."' ";

      $extraCheck_cv .= " AND cvd.vACode = '".$this->ddlAccount."' AND cvd.vTable = '".$this->tableType."' ";

      $extraCheck_dv .= " AND dvd.vACode = '".$this->ddlAccount."' AND dvd.vTable = '".$this->tableType."' ";

      $extraCheck_jv .= " AND jvd.vACode = '".$this->ddlAccount."' AND jvd.vTable = '".$this->tableType."' ";

      $extraCheck_ac .= " AND acd.vACode = '".$this->ddlAccount."' AND acd.vTable = '".$this->tableType."' ";

      $extraCheck_ad .= " AND aed.vACode = '".$this->ddlAccount."' AND aed.vTable = '".$this->tableType."' ";

      $extraCheck_ac_top .= " AND ac.vSubCode = '".$this->ddlAccount."' AND 'V' = '".$this->tableType."' ";

      $extraCheck_ad_top .= " AND ad.vSubCode = '".$this->ddlAccount."' AND 'V' = '".$this->tableType."' ";
	if($this->ddlAccount=='39' || $this->ddlAccount=='211' || $this->ddlAccount=='55'){
		$extraCheck_op .= " AND opd.vACode = '".$this->ddlAccount."'";
	}else{
		$extraCheck_op .= " AND opd.vAcSubCode = '".$this->ddlAccount."'";
	} 
	 //$extraCheck_op .= " AND opd.vAcSubCode = '".$this->ddlAccount."'";

      $extraCheck_rc .= " AND rcd.nDrAcc  = '".$this->ddlAccount."' AND rcd.vTable = '".$this->tableType."' ";



      $extraCheck_rc .= " AND rcd.vTable IN ('C','O','A')";



      $extraCheck_cr .= " AND crd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_cp .= " AND cpd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_bb .= " AND bbd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_bp .= " AND bpd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_cv .= " AND cvd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_dv .= " AND dvd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_jv .= " AND jvd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_ac .= " AND acd.vAcSubCode = '".$this->ddlSubAccount."' ";

      $extraCheck_ad .= " AND aed.vAcSubCode = '".$this->ddlSubAccount."' ";
	  
	  if($this->ddlAccount=='39' || $this->ddlAccount=='211' || $this->ddlAccount=='55'){
		$extraCheck_op .= " AND opd.vAcSubCode = '".$this->ddlSubAccount."'";
	}else{
		$extraCheck_op .= " AND opd.vACode = '".$this->ddlSubAccount."'";
	} //$extraCheck_op .= " AND opd.vACode = '".$this->ddlSubAccount."' ";



		return array("cr" => $extraCheck_cr, "cp" => $extraCheck_cp, "bb" => $extraCheck_bb, 

			"bp" => $extraCheck_bp, "jv" => $extraCheck_jv, "cv" => $extraCheck_cv, "dv" => $extraCheck_dv, 

      "ac"=>$extraCheck_ac,"ac_top_acc" => $extraCheck_ac_top,

      "ad" => $extraCheck_ad, "ad_top_acc" => $extraCheck_ad_top, "rc" => $extraCheck_rc,"op" => $extraCheck_op);



    }//end function



}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

