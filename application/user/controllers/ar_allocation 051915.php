
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ar_allocation extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->model('ar_allocation_model');
        $this->load->model('cron_job_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	      = ARAPALLOCATION;
		$this->common_model->check_admin_authorization();

        $this->cron_job_model->checkAllocationTable();

        $data['list_div_value']   = $this->ar_allocation_model->getActiveDivision();
        $data['list_loc_value']   = $this->ar_allocation_model->getLocation();
        $data['list_trans_value'] = $this->ar_allocation_model->getActiveTransactions();
        $data['list_sub_acc']	  = $this->ar_allocation_model->getAccounts();
        $data['list_account']	  = $this->ar_allocation_model->getActiveSubAccounts();
        $data['list_alloc_type']  = $this->ar_allocation_model->getAllocationType();
        //$data['list_allc1']       = $this->ar_allocation_model->getAllocationValue1();

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ar_allocation/showlist');

		$this->load->view('general/template_top');
		$this->load->view('ar_allocation/ar_allocation',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlDivision      = $this->input->post('ddlDivision');
        $this->ddlLocation      = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->ar_allocation_model->filterLocation($this->ddlDivision);
        $this->load->view('ar_allocation/show_location',$data);
    }//end function

    function getAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlType        = $this->input->post('ddlType');

        $data['list_account'] = $this->ar_allocation_model->getActiveAccount($this->ddlType);
        $this->load->view('ar_allocation/show_account',$data);
    }//end function

    function getSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlType        = $this->input->post('ddlType');

        $data['list_sub_acc'] = $this->ar_allocation_model->getActiveSubAccounts();
        $this->load->view('ar_allocation/show_sub',$data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName              = $this->input->post('ddlAccount');
        $accType              = $this->input->post('tableType');

        $data['list_sub_acc'] = $this->ar_allocation_model->filterSubAccount($accName,$accType);
        $this->load->view('ar_allocation/show_sub',$data);
    }//end function

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

      $extraCheck_ac_top = $extraCheck_ac;
      $extraCheck_ad_top = $extraCheck_ad;

      $extraCheck_cr .= " AND crd.vACode = '".$this->ddlAccount."' AND crd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_cp .= " AND cpd.vACode = '".$this->ddlAccount."' AND cpd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_bb .= " AND bbd.vACode = '".$this->ddlAccount."' AND bbd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_bp .= " AND bpd.vACode = '".$this->ddlAccount."' AND bpd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_cv .= " AND cvd.vACode = '".$this->ddlAccount."' AND cvd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_dv .= " AND dvd.vACode = '".$this->ddlAccount."' AND dvd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_jv .= " AND jvd.vACode = '".$this->ddlAccount."' AND jvd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_ac .= " AND acd.vACode = '".$this->ddlAccount."' AND acd.vTable = '".$this->ddlAccType."' ";
      $extraCheck_ad .= " AND aed.vACode = '".$this->ddlAccount."' AND aed.vTable = '".$this->ddlAccType."' ";
      $extraCheck_ac_top .= " AND ac.vSubCode = '".$this->ddlAccount."' AND 'V' = '".$this->ddlAccType."' ";
      $extraCheck_ad_top .= " AND ad.vSubCode = '".$this->ddlAccount."' AND 'V' = '".$this->ddlAccType."' ";
      $extraCheck_rc .= " AND rcd.nDrAcc  = '".$this->ddlAccount."' AND rcd.vTable = '".$this->ddlAccType."' ";

      $extraCheck_rc .= " AND rcd.vTable IN ('C','O')";

      $extraCheck_cr .= " AND crd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_cp .= " AND cpd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_bb .= " AND bbd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_bp .= " AND bpd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_cv .= " AND cvd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_dv .= " AND dvd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_jv .= " AND jvd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_ac .= " AND acd.vAcSubCode = '".$this->ddlSubAccount."' ";
      $extraCheck_ad .= " AND aed.vAcSubCode = '".$this->ddlSubAccount."' ";

		return array("cr" => $extraCheck_cr, "cp" => $extraCheck_cp, "bb" => $extraCheck_bb, 
			"bp" => $extraCheck_bp, "jv" => $extraCheck_jv, "cv" => $extraCheck_cv, "dv" => $extraCheck_dv, 
      "ac"=>$extraCheck_ac,"ac_top_acc" => $extraCheck_ac_top,
      "ad" => $extraCheck_ad, "ad_top_acc" => $extraCheck_ad_top, "rc" => $extraCheck_rc);

    }//end function
    function getAllocationDetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->session->unset_userdata('AccVal');
        $this->session->unset_userdata('TableVal');
        $this->session->unset_userdata('ReceiptId');
        $this->session->unset_userdata('ReceiptType');
        $this->session->unset_userdata('DetReceiptId');

        $this->common_model->check_admin_authorization();
        //		$this->finishbtnVal		= FINISH;
        $this->ddlDivision   = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation   = $this->common_model->searchKeyword3('ddlLocation');

        $this->addAllocbtnVal		= ADDALLOCATION;
        $this->allType          = $this->input->post('allType');
        $this->tableId          = $this->input->post('tableId');
        $this->detTableId          = $this->input->post('detTableId');
        $this->tktType          = $this->input->post('tktType');
        $this->ddlSubAccount = $this->input->post('ddlAccount');
        $this->ddlAccount = $this->input->post('ddlSubAccount');
        $this->ddlAccType = $this->input->post('tableVal');

        $this->session->set_userdata('AccVal',$this->ddlAccount);
        $this->session->set_userdata('TableVal',$this->ddlAccType);
        $this->session->set_userdata('ddlLocation',$this->ddlLocation);
        $this->session->set_userdata('ReceiptId',$this->tableId);
        $this->session->set_userdata('DetReceiptId',$this->detTableId);
        $this->session->set_userdata('ReceiptType',$this->tktType);

//echo $this->tableId . " --- ". $this->detTableId . " -- " . $this->tktType;
//echo $this->allType. " -- " . $this->tableId . " -- " . $this->detTableId . " -- " . $this->tktType. " -- " . $this->ddlSubAccount  . " -- " .$this->ddlAccount . " -- " .$this->ddlAccType ;
       $extra_check = $this->common_checks();
        $extraCheck_cr = $extra_check['cr'];
        $extraCheck_cp = $extra_check['cp'];
        $extraCheck_bb = $extra_check['bb'];
        $extraCheck_bp = $extra_check['bp'];
        $extraCheck_jv = $extra_check['jv'];
        $extraCheck_cv = $extra_check['cv'];
        $extraCheck_dv = $extra_check['dv'];
        $extraCheck_ac = $extra_check['ac'];
        $extraCheck_ac_top = $extra_check['ac_top_acc'];
        $extraCheck_ad = $extra_check['ad'];
        $extraCheck_ad_top = $extra_check['ad_top_acc'];
        $extraCheck_rc = $extra_check['rc'];

        if($this->ddlAccType == "C" || $this->ddlAccType == "O"){
          $extraCheck_cr .= " AND crd.vMode = 'Dr' ";
          $extraCheck_cp .= " AND cpd.vMode = 'Dr' ";
          $extraCheck_bb .= " AND bbd.vMode = 'Dr' ";
          $extraCheck_bp .= " AND bpd.vMode = 'Dr' ";
          $extraCheck_cv .= " AND cvd.vMode = 'Dr' ";
          $extraCheck_dv .= " AND dvd.vMode = 'Dr' ";
          $extraCheck_jv .= " AND jvd.vMode = 'Dr' ";
          $extraCheck_ac .= " AND acd.vMode = 'Dr' ";
          $extraCheck_ad .= " AND aed.vMode = 'Dr' ";
       }
        else{
          $extraCheck_cr .= " AND crd.vMode = 'Cr' ";
          $extraCheck_cp .= " AND cpd.vMode = 'Cr' ";
          $extraCheck_bb .= " AND bbd.vMode = 'Cr' ";
          $extraCheck_bp .= " AND bpd.vMode = 'Cr' ";
          $extraCheck_cv .= " AND cvd.vMode = 'Cr' ";
          $extraCheck_dv .= " AND dvd.vMode = 'Cr' ";
          $extraCheck_jv .= " AND jvd.vMode = 'Cr' ";
          $extraCheck_ac .= " AND acd.vMode = 'Cr' ";
          $extraCheck_ad .= " AND aed.vMode = 'Cr' ";
          $extraCheck_ac_top .= " AND ac.vTransMode = 'Cr' ";
          $extraCheck_ad_top .= " AND ad.vTransMode = 'Cr' ";
        }
        $extraCheck_cr .= " AND crd.nAcAllocatedAmt < crd.nAmount";
        $extraCheck_cp .= " AND cpd.nAcAllocatedAmt < cpd.nAmount";
        $extraCheck_bb .= " AND bbd.nAcAllocatedAmt < bbd.nAmount";
        $extraCheck_bp .= " AND bpd.nAcAllocatedAmt < bpd.nAmount";
        $extraCheck_cv .= " AND cvd.nAcAllocatedAmt < cvd.nAmount";
        $extraCheck_dv .= " AND dvd.nAcAllocatedAmt < dvd.nAmount";
        $extraCheck_jv .= " AND jvd.nAcAllocatedAmt < jvd.nAmount";
        $extraCheck_ac .= " AND acd.nAcAllocatedAmt < acd.nAmount";
        $extraCheck_ad .= " AND aed.nAcAllocatedAmt < aed.nAmount";
        $extraCheck_ac_top .= " AND ac.nAcAllocatedAmt < ac.vAmountDC";
        $extraCheck_ad_top .= " AND ad.nAcAllocatedAmt < ad.vAmountDC";
        $extraCheck_rc .= " AND rcd.nAcAllocatedAmt < rcd.vPaidAmt";

       $data['list_allc2'] = $this->common_model->getAllocatedDetails($this->tktType,$this->tableId,$this->ddlAccount,$this->ddlAccType,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc);
//        $data['list_allc2'] = $this->ar_allocation_model->getAllocatedDetails($this->tableId,$this->tktType);
//        $data['list_pending2'] = $this->ar_allocation_model->getPendingDetails();
       $list_pending1 = $this->common_model->getPendingDetails($this->ddlAccount,$this->ddlAccType, $this->ddlSubAccount,$extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc);
       $list_pending2 = $this->ar_allocation_model->getFilterList1($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc);

       if(empty($list_pending1) && empty($list_pending2)) $data['list_pending2'] = array();
       else if(!empty($list_pending1) && !empty($list_pending2)) $data['list_pending2'] = array_merge($list_pending1,$list_pending2);
       else{
         if(!empty($list_pending1))  $data['list_pending2'] = $list_pending1;
         else if(!empty($list_pending2)) $data['list_pending2'] = $list_pending2;
       }

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ar_allocation/showlist');
       $this->load->view('ar_allocation/allocated',$data);
    }//end function

    function filterList()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

//        $this->ddlType       = $this->common_model->searchKeyword3('ddlType');
        $this->ddlDivision   = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation   = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlAccount    = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlAccType    = $this->common_model->searchKeyword3('ddlAccType');
        $this->ddlSubAccount = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->ddlAllocation = $this->common_model->searchKeyword3('ddlAllocation');

        if($this->input->post('btnSearch') == 'Go')
        {
//            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
			$this->session->unset_userdata('ddlAccType');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('ddlAllocation');

//            $this->ddlType       = $this->common_model->searchKeyword3('ddlType');
            $this->ddlDivision   = $this->common_model->searchKeyword3('ddlDivision');
            $this->ddlLocation   = $this->common_model->searchKeyword3('ddlLocation');
            $this->ddlAccount    = $this->common_model->searchKeyword3('ddlAccount');
			$this->ddlAccType    = $this->common_model->searchKeyword3('ddlAccType');
            $this->ddlSubAccount = $this->common_model->searchKeyword3('ddlSubAccount');
            $this->ddlAllocation = $this->common_model->searchKeyword3('ddlAllocation');
        }//end if

        $extra_check = $this->common_checks();
        $extraCheck_cr = $extra_check['cr'];
        $extraCheck_cp = $extra_check['cp'];
        $extraCheck_bb = $extra_check['bb'];
        $extraCheck_bp = $extra_check['bp'];
        $extraCheck_jv = $extra_check['jv'];
        $extraCheck_cv = $extra_check['cv'];
        $extraCheck_dv = $extra_check['dv'];
        $extraCheck_ac = $extra_check['ac'];
        $extraCheck_ac_top = $extra_check['ac_top_acc'];
        $extraCheck_ad = $extra_check['ad'];
        $extraCheck_ad_top = $extra_check['ad_top_acc'];
        $extraCheck_rc = $extra_check['rc'];

        if($this->ddlAccType == "C" || $this->ddlAccType == "O"){
          $extraCheck_cr .= " AND crd.vMode = 'Cr' ";
          $extraCheck_cp .= " AND cpd.vMode = 'Cr' ";
          $extraCheck_bb .= " AND bbd.vMode = 'Cr' ";
          $extraCheck_bp .= " AND bpd.vMode = 'Cr' ";
          $extraCheck_cv .= " AND cvd.vMode = 'Cr' ";
          $extraCheck_dv .= " AND dvd.vMode = 'Cr' ";
          $extraCheck_jv .= " AND jvd.vMode = 'Cr' ";
          $extraCheck_ac .= " AND acd.vMode = 'Cr' ";
          $extraCheck_ad .= " AND aed.vMode = 'Cr' ";
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
          $extraCheck_ac_top .= " AND ac.vTransMode = 'Dr' ";
          $extraCheck_ad_top .= " AND ad.vTransMode = 'Dr' ";
        }
        // refund register account checks
        $extraCheck_rc_rr .= $this->ddlAccount." = ( SELECT rr.nAccount FROM {PRE}RefundRegister rr 
                  LEFT JOIN {PRE}RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId 
                  WHERE rr.nAccount = ".$this->ddlAccount." AND 'V' = '".$this->ddlAccType."' AND
                  rrd.vTktType = rcd.vType AND rrd.nTktNum = rcd.nTicketNumber ) ";

        if($this->ddlAllocation == 'P'){
          $extraCheck_cr .= " AND crd.nAcAllocatedAmt < crd.nAmount";
          $extraCheck_cp .= " AND cpd.nAcAllocatedAmt < cpd.nAmount";
          $extraCheck_bb .= " AND bbd.nAcAllocatedAmt < bbd.nAmount";
          $extraCheck_bp .= " AND bpd.nAcAllocatedAmt < bpd.nAmount";
          $extraCheck_cv .= " AND cvd.nAcAllocatedAmt < cvd.nAmount";
          $extraCheck_dv .= " AND dvd.nAcAllocatedAmt < dvd.nAmount";
          $extraCheck_jv .= " AND jvd.nAcAllocatedAmt < jvd.nAmount";
          $extraCheck_ac .= " AND acd.nAcAllocatedAmt < acd.nAmount";
          $extraCheck_ad .= " AND aed.nAcAllocatedAmt < aed.nAmount";
          $extraCheck_ac_top .= " AND ac.nAcAllocatedAmt < ac.vAmountDC";
          $extraCheck_ad_top .= " AND ad.nAcAllocatedAmt < ad.vAmountDC";
          $extraCheck_rc .= " AND rcd.nAcAllocatedAmt < rcd.vPaidAmt";
          $extraCheck_rc_rr .= " AND rcd.nAcAllocatedAmt_rr < rcd.vNetAmt";
        }
        else if($this->ddlAllocation == 'A'){
          $extraCheck_cr .= " AND crd.nAcAllocatedAmt = crd.nAmount";
          $extraCheck_cp .= " AND cpd.nAcAllocatedAmt = cpd.nAmount";
          $extraCheck_bb .= " AND bbd.nAcAllocatedAmt = bbd.nAmount";
          $extraCheck_bp .= " AND bpd.nAcAllocatedAmt = bpd.nAmount";
          $extraCheck_cv .= " AND cvd.nAcAllocatedAmt = cvd.nAmount";
          $extraCheck_dv .= " AND dvd.nAcAllocatedAmt = dvd.nAmount";
          $extraCheck_jv .= " AND jvd.nAcAllocatedAmt = jvd.nAmount";
          $extraCheck_ac .= " AND acd.nAcAllocatedAmt = acd.nAmount";
          $extraCheck_ad .= " AND aed.nAcAllocatedAmt = aed.nAmount";
          $extraCheck_ac_top .= " AND ac.nAcAllocatedAmt = ac.vAmountDC";
          $extraCheck_ad_top .= " AND ad.nAcAllocatedAmt = ad.vAmountDC";
          $extraCheck_rc .= " AND rcd.nAcAllocatedAmt = rcd.vPaidAmt";
          $extraCheck_rc_rr .= " AND rcd.nAcAllocatedAmt_rr = rcd.vNetAmt";

        }



//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ar_allocation/showlist');
        $data['list_allc1']  = $this->ar_allocation_model->getFilterList($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad,$extraCheck_ac_top,$extraCheck_ad_top,$extraCheck_rc,$extraCheck_rc_rr);
        //                $data['list_pending2'] = $this->common_model->getPendingDetails($this->ddlAccount,$this->ddlAccType, $this->ddlSubAccount);

        $this->load->view('ar_allocation/finance_list',$data);
    }//end function
	
	//allocated ticket fetching function
	function getInvoiceDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$this->salesDId = $this->input->post('salesDetailId');
		$invDetails		= $this->ar_allocation_model->getInvoiceDetails($this->salesDId);
		echo json_encode($invDetails);
	}//end function

    function updateAllocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		$this->ar_allocation_model->updateMultipleAllocation();
	}//end function

    function insertAllocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
    echo $this->common_model->addAllocation();
//		$this->ar_allocation_model->insertMultipleAllocation();
	}//end function

    function editAllocation()
	{
/*        $this->load->helper(array('form', 'url'));
        $this->showTitle 	      = ARAPALLOCATION;
        $this->common_model->check_admin_authorization();

        $this->ar_allocation_model->updateAllocationTable();

        $data['list_div_value']   = $this->ar_allocation_model->getActiveDivision();
        $data['list_loc_value']   = $this->ar_allocation_model->getLocation();
        $data['list_trans_value'] = $this->ar_allocation_model->getActiveTransactions();
        $data['list_account']	  = $this->ar_allocation_model->getAccounts();
        $data['list_sub_acc']	  = $this->ar_allocation_model->getActiveSubAccounts();
        $data['list_alloc_type']  = $this->ar_allocation_model->getAllocationType();
        //$data['list_allc1']       = $this->ar_allocation_model->getAllocationValue1();

        $this->load->view('general/template_top');
        $this->load->view('ar_allocation/ar_allocation',$data);
        $this->load->view('general/template_bottom');
*/
    echo $this->common_model->editAllocation();
	}//end function
	
    function deleteAllocation(){
/*      $cId = $this->input->post('caid');
      $rectd = $this->input->post('rectd');
      $tktType = $this->input->post('tktType');
      $this->ar_allocation_model->deleteAllocation($cId,$rectd,$tktType);
*/  
       echo $this->common_model->delAllocation();
    } //end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
