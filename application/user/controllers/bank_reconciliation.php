<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bank_Reconciliation extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('bank_reconciliation_model');
        $this->load->model('cron_job_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = BANKRECONCILIATION;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
		
		$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
		

        $data['list_div_value']     = $this->bank_reconciliation_model->getActiveDivision();
        $data['list_loc_value']     = $this->bank_reconciliation_model->getActiveLocations();
        $data['list_account']          = $this->bank_reconciliation_model->getActiveAccount();


        $this->session->unset_userdata('pdfTitle');

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision      = $this->session->userdata('ddlDivision');
            $this->ddlLocation      = $this->session->userdata('ddlLocation');
            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');
            $this->txtToDate        = $this->session->userdata('txtToDate');
            $this->txtFromDate      = $this->session->userdata('txtFromDate');
            $this->ddlAccount       = $this->session->userdata('ddlAccount');
            $this->txtReport        = $this->session->userdata('txtReport');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('txtReport');
        }

        $this->load->view('general/template_top');
        $this->load->view('bank_reconciliation/bank_reconciliation',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function checks(){

      if ($this->ddlDivision != '') {
        $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
        $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
        $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
        $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
        $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
        $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
        $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
        $extraCheck_sj = " AND sj.nDivision='".$this->ddlDivision."'";
        $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
        $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
      }//end if


      $location =  $this->common_model->get_field_name("Location","vCode"," WHERE nId =  " . $this->ddlLocation. " ");
      $location2 =  $this->common_model->get_field_name("Location","vCode"," WHERE nId =  " . $this->ddlLocation2. " ");


      $multiLocGroup = $this->db->query("SELECT nId from {PRE}Location
          WHERE vCode BETWEEN " . $location. " AND  " . $location2. " AND nDivision = " . $this->ddlDivision. "
          AND nId IN ".$this->session->userdata('accessLoc') ." ");

      $multiLoc = "(";
      if($multiLocGroup->num_rows() > 0)
      {
        foreach ($multiLocGroup->result() as $key => $row){
          if($multiLoc != "(") $multiLoc .= ",";
          $multiLoc .=  $row->nId;
        } //en foreach
      }// end if
      $multiLoc .= ")";

      $extraCheck_cr .= " AND cr.vLocation IN " . $multiLoc . " " ;
      $extraCheck_cp .= " AND cp.vLocation IN " . $multiLoc . " " ;
      $extraCheck_bb .= " AND bb.vLocation IN " . $multiLoc . " " ;
      $extraCheck_bp .= " AND bp.vLocation IN " . $multiLoc . " " ;
      $extraCheck_jv .= " AND jv.vLocation IN " . $multiLoc . " " ;
      $extraCheck_cv .= " AND cv.vLocation IN " . $multiLoc . " " ;
      $extraCheck_dv .= " AND dv.vLocation IN " . $multiLoc . " " ;
      $extraCheck_sj .= " AND sj.nLocation IN " . $multiLoc . " " ;
      $extraCheck_ac .= " AND ac.vLocation IN " . $multiLoc . " " ;
      $extraCheck_ad .= " AND ad.vLocation IN " . $multiLoc . " " ;

      $extraCheck_cr_dt = " AND cr.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_cp_dt = " AND cp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_bb_dt = " AND bb.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_bp_dt = " AND bp.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_jv_dt = " AND jv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_cv_dt = " AND cv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_dv_dt = " AND dv.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_sj_dt = " AND sj.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_ac_dt = " AND ac.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";
      $extraCheck_ad_dt = " AND ad.dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' ";

      $extraCheck_cr_dt .= " AND cr.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_cp_dt .= " AND cp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_bb_dt .= " AND bb.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_bp_dt .= " AND bp.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_jv_dt .= " AND jv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_cv_dt .= " AND cv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_dv_dt .= " AND dv.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_sj_dt .= " AND sj.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_ac_dt .= " AND ac.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
      $extraCheck_ad_dt .= " AND ad.dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";


      $extraCheck_bb_top_acc = $extraCheck_bb;
      $extraCheck_bp_top_acc = $extraCheck_bp;

      // Account handeling for finance entries
      if (!($this->ddlAccount == '0' || $this->ddlAccount == '') ) {

        $extraCheck_bb_top_acc .= " AND bb.nAcCode='".$this->ddlAccount."'";
        $extraCheck_bp_top_acc .= " AND bp.nAcCode='".$this->ddlAccount."'";
        // CRD CPD BRD BPD CRV DRV ACM ADM
        $extraCheck_cr .= " AND IF(crd.vAcSubCode !='0' , crd.vAcSubCode = '".$this->ddlAccount."', crd.vACode='".$this->ddlAccount."' )";
        $extraCheck_cp .= " AND IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode = '".$this->ddlAccount."', cpd.vACode='".$this->ddlAccount."' )";
        $extraCheck_bb .= " AND IF(bbd.vAcSubCode !='0' , bbd.vAcSubCode = '".$this->ddlAccount."', bbd.vACode='".$this->ddlAccount."' )";
        $extraCheck_bp .= " AND IF(bpd.vAcSubCode !='0' , bpd.vAcSubCode = '".$this->ddlAccount."', bpd.vACode='".$this->ddlAccount."' )";
        $extraCheck_jv .= " AND IF(jvd.vAcSubCode !='0' , jvd.vAcSubCode = '".$this->ddlAccount."', jvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_cv .= " AND IF(cvd.vAcSubCode !='0' , cvd.vAcSubCode = '".$this->ddlAccount."', cvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_dv .= " AND IF(dvd.vAcSubCode !='0' , dvd.vAcSubCode = '".$this->ddlAccount."', dvd.vACode='".$this->ddlAccount."' )";
        $extraCheck_ac .= " AND IF(acd.vAcSubCode !='0' , acd.vAcSubCode = '".$this->ddlAccount."', acd.vACode='".$this->ddlAccount."' )";
        $extraCheck_ad .= " AND IF(ade.vAcSubCode !='0' , ade.vAcSubCode = '".$this->ddlAccount."', ade.vACode='".$this->ddlAccount."' )";

        $extraCheck_sj_special =  " HAVING account = '". $this->ddlAccount ."'";
      }//end if 

      $extra_checks['extraCheck_cr']= $extraCheck_cr . $extraCheck_cr_dt;
      $extra_checks['extraCheck_cp']= $extraCheck_cp . $extraCheck_cp_dt;
      $extra_checks['extraCheck_bb']= $extraCheck_bb . $extraCheck_bb_dt;
      $extra_checks['extraCheck_bp']= $extraCheck_bp . $extraCheck_bp_dt;
      $extra_checks['extraCheck_jv']= $extraCheck_jv . $extraCheck_jv_dt;
      $extra_checks['extraCheck_cv']= $extraCheck_cv . $extraCheck_cv_dt;
      $extra_checks['extraCheck_dv']= $extraCheck_dv . $extraCheck_dv_dt;
      $extra_checks['extraCheck_sj']= $extraCheck_sj . $extraCheck_sj_dt.$extraCheck_sj_special;
      $extra_checks['extraCheck_ac']= $extraCheck_ac . $extraCheck_ac_dt;
      $extra_checks['extraCheck_ad']= $extraCheck_ad . $extraCheck_ad_dt;

      $extra_checks['extraCheck_bb_top_acc']= $extraCheck_bb_top_acc . $extraCheck_bb_dt;
      $extra_checks['extraCheck_bp_top_acc']= $extraCheck_bp_top_acc . $extraCheck_bp_dt;

      return $extra_checks;
    } //end function

    function displayList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = BANKRECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
          $this->session->unset_userdata('backURL');

          if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
          $this->session->unset_userdata('ddlLocation');
          $this->session->unset_userdata('ddlLocation2');
          $this->session->unset_userdata('txtToDate');
          $this->session->unset_userdata('txtFromDate');
          $this->session->unset_userdata('ddlAccount');
          $this->session->unset_userdata('pageName');
          endif;

          $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
          $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
          $this->ddlLocation2       = $this->common_model->searchKeyword3('ddlLocation2');
          $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
          $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
          $this->ddlAccount= $this->common_model->searchKeyword3('ddlAccount');
//          $this->tableType         = $this->common_model->searchKeyword3('tableType');
        }
        else {

          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->ddlLocation2       = $this->session->userdata('ddlLocation2');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->ddlAccount= $this->session->userdata('ddlAccount');
//          $this->tableType         = $this->session->userdata('tableType');
        }
        $this->session->set_userdata('pageName',$this->uri->segment(1));
/*
        if ($this->txtFromDate !='' && $this->txtToDate != '') {
            $extraCheck = " WHERE dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {
            $extraCheck = " WHERE dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {
            $extraCheck = " WHERE dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " ";
        }
        $data['list_value']    = $this->bank_reconciliation_model->getAllReconciliation($extraCheck);
  */
        $extra_checks = array();
        $extra_checks = $this->checks();

        $extraCheck_cr = $extra_checks['extraCheck_cr'];
        $extraCheck_cp = $extra_checks['extraCheck_cp'];
        $extraCheck_bb = $extra_checks['extraCheck_bb'];
        $extraCheck_bp = $extra_checks['extraCheck_bp'];
        $extraCheck_jv = $extra_checks['extraCheck_jv'];
        $extraCheck_cv = $extra_checks['extraCheck_cv'];
        $extraCheck_dv = $extra_checks['extraCheck_dv'];
        $extraCheck_sj = $extra_checks['extraCheck_sj'];
        $extraCheck_ac = $extra_checks['extraCheck_ac'];
        $extraCheck_ad = $extra_checks['extraCheck_ad'];


        $extraCheck_cr_top_acc = $extra_checks['extraCheck_cr_top_acc'];
        $extraCheck_cp_top_acc = $extra_checks['extraCheck_cp_top_acc'];
        $extraCheck_bb_top_acc = $extra_checks['extraCheck_bb_top_acc'];
        $extraCheck_bp_top_acc = $extra_checks['extraCheck_bp_top_acc'];
        $extraCheck_ac_top_acc = $extra_checks['extraCheck_ac_top_acc'];
        $extraCheck_ad_top_acc = $extra_checks['extraCheck_ad_top_acc'];

        $group_select = 'SELECT T1.*, IF((SELECT count(*) FROM {PRE}BankReconciliation br WHERE br.nReceiptDId = T1.tableId AND br.vReconType = T1.tblName) > 0, "M", "N") as vStatus  FROM (  ';
        $groupby = ' ) T1 '; //LEFT JOIN {PRE}BankReconciliation br ON br.nReceiptDId = T1.tableId AND br.vReconType = T1.tblName';
        $orderby =  "  ORDER BY account, subacc, date,document ASC ";


        $data['list_value']    = $this->bank_reconciliation_model->getAllReconciliation($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$orderby,$group_select, $groupby);
      
        if($this->uri->segment(4)=='redirect'):
            $this->load->view('general/template_top');
            $this->load->view('bank_reconciliation/display_reconciliation_redirect',$data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('bank_reconciliation/display_reconciliation',$data);
        endif;

    }//end function

    function displayMatchedList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = BANKRECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
            $this->session->unset_userdata('backURL');

            if(trim($this->session->userdata('backURL')) == ''):
              $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('pageName');
            endif;

            $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
            $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
            $this->ddlLocation2       = $this->common_model->searchKeyword3('ddlLocation2');
            $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
            $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
            $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
//            $this->tableType         = $this->common_model->searchKeyword3('tableType');
        }
        else{
          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->ddlLocation2       = $this->session->userdata('ddlLocation2');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
//          $this->tableType         = $this->session->userdata('tableType');

        }
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if ($this->txtFromDate !='' && $this->txtToDate != '') {
            $extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {
            $extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {
            $extraCheck = " AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " ";
        }


      $location =  $this->common_model->get_field_name("Location","vCode"," WHERE nId =  " . $this->ddlLocation. " ");
      $location2 =  $this->common_model->get_field_name("Location","vCode"," WHERE nId =  " . $this->ddlLocation2. " ");

      $multiLocGroup = $this->db->query("SELECT nId from {PRE}Location
          WHERE vCode BETWEEN " . $location. " AND  " . $location2. " AND nDivision = " . $this->ddlDivision. "
          AND nId IN ".$this->session->userdata('accessLoc') ." ");

      $multiLoc = "(";
      if($multiLocGroup->num_rows() > 0)
      {
        foreach ($multiLocGroup->result() as $key => $row){
          if($multiLoc != "(") $multiLoc .= ",";
          $multiLoc .=  $row->nId;
        } //en foreach
      }// end if
      $multiLoc .= ")";

        $extraCheck .= " AND nDivision='".$this->ddlDivision."'";
        $extraCheck .= " AND vLocation IN ". $multiLoc ." ";
        $extraCheck .= " AND nAcCode='".$this->ddlAccount."'";

        $data['list_value']    = $this->bank_reconciliation_model->getAllMatchedReconciliation($extraCheck);
        

        //$this->load->view('general/template_top');
        $this->load->view('bank_reconciliation/display_reconciliation',$data);
        //$this->load->view('general/template_bottom');
    }//end function

    function displayUnMatchedList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = BANKRECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2       = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->ddlAccount= $this->common_model->searchKeyword3('ddlAccount');
//        $this->tableType         = $this->common_model->searchKeyword3('tableType');
        }
        else{
          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->ddlLocation2       = $this->session->userdata('ddlLocation2');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->ddlAccount= $this->session->userdata('ddlAccount');
//          $this->tableType         = $this->session->userdata('tableType');

        }

        $this->session->set_userdata('pageName',$this->uri->segment(1));
/*
        if ($this->txtFromDate !='' && $this->txtToDate != '') {
            $extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {
            $extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {
            $extraCheck = " AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " ";
        }

        $data['list_value']    = $this->bank_reconciliation_model->getAllUnMatchReconciliation($extraCheck);
*/        
        $extra_checks = array();
        $extra_checks = $this->checks();

        $extraCheck_cr = $extra_checks['extraCheck_cr'];
        $extraCheck_cp = $extra_checks['extraCheck_cp'];
        $extraCheck_bb = $extra_checks['extraCheck_bb'];
        $extraCheck_bp = $extra_checks['extraCheck_bp'];
        $extraCheck_jv = $extra_checks['extraCheck_jv'];
        $extraCheck_cv = $extra_checks['extraCheck_cv'];
        $extraCheck_dv = $extra_checks['extraCheck_dv'];
        $extraCheck_sj = $extra_checks['extraCheck_sj'];
        $extraCheck_ac = $extra_checks['extraCheck_ac'];
        $extraCheck_ad = $extra_checks['extraCheck_ad'];


        $extraCheck_cr_top_acc = $extra_checks['extraCheck_cr_top_acc'];
        $extraCheck_cp_top_acc = $extra_checks['extraCheck_cp_top_acc'];
        $extraCheck_bb_top_acc = $extra_checks['extraCheck_bb_top_acc'];
        $extraCheck_bp_top_acc = $extra_checks['extraCheck_bp_top_acc'];
        $extraCheck_ac_top_acc = $extra_checks['extraCheck_ac_top_acc'];
        $extraCheck_ad_top_acc = $extra_checks['extraCheck_ad_top_acc'];

        $group_select = 'SELECT T1.* , "N" as vStatus FROM ( ';
        $groupby = ') T1 WHERE T1.tableId NOT IN (SELECT br.nReceiptDId FROM {PRE}BankReconciliation br WHERE br.nReceiptDId = T1.tableId AND br.vReconType = T1.tblName)';
        $orderby =  "  ORDER BY account, subacc, date,document ASC ";

        $data['list_value']    = $this->bank_reconciliation_model->getAllReconciliation($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$orderby,$group_select, $groupby);
 
        //$this->load->view('general/template_top');
        $this->load->view('bank_reconciliation/display_reconciliation',$data);
        //$this->load->view('general/template_bottom');
    }//end function

/*    function process()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = BANKRECONCILIATION;
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
            $this->session->unset_userdata('txtReport');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtFromDate       = date('Y-m-d',strtotime($this->common_model->searchKeyword3('txtFromDate')));
        $this->txtToDate         = date('Y-m-d',strtotime($this->common_model->searchKeyword3('txtToDate')));
        $this->txtReport         = $this->common_model->searchKeyword3('txtReport');
        
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        $this->session->unset_userdata('pdfTitle');
        $this->session->set_userdata('pdfTitle',$_FILES['txtFile']['tmp_name']);

        if(trim($_FILES['txtFile']['tmp_name'])!=''):
		    $fetchVal = $this->bank_reconciliation_model->getPDFText($_FILES['txtFile']['tmp_name'],$this->txtFromDate,$this->txtToDate);
//return;
            //redirect(site_url("reconciliation/displayList/".$this->uri->segment(3).'/redirect'));
//return;
            $extraCheck = " WHERE dDate >= '" .$this->txtFromDate."' AND dDate <= '" .$this->txtToDate."'";

        $data['list_value']    = $this->bank_reconciliation_model->getAllReconciliation($extraCheck);
            $this->load->view('general/template_top');
            $this->load->view('bank_reconciliation/display_reconciliation_redirect',$data);
            $this->load->view('general/template_bottom');
 

        else:
            //redirect(site_url("reconciliation/displayList/".$this->uri->segment(3)));
        endif;
    } //end function
*/
    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

        $this->ddlLocation   = $this->session->userdata('ddlLocation');
        $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

		$data['list_loc_value'] = $this->bank_reconciliation_model->filterLocation($this->ddlDivision);
		$this->load->view('ac_report/show_location',$data);
	}//end function

    function saveReconciled()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = BANKRECONCILIATION;
        $this->common_model->check_admin_authorization();


        if($this->input->post('sess_clear') == 'y'){
          $this->session->unset_userdata('backURL');

          if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
          $this->session->unset_userdata('ddlLocation');
          $this->session->unset_userdata('ddlLocation2');
          $this->session->unset_userdata('txtToDate');
          $this->session->unset_userdata('txtFromDate');
          $this->session->unset_userdata('ddlAccount');
          $this->session->unset_userdata('pageName');
          endif;

          $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
          $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
          $this->ddlLocation2       = $this->common_model->searchKeyword3('ddlLocation2');
          $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
          $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
          $this->ddlAccount= $this->common_model->searchKeyword3('ddlAccount');
//          $this->tableType         = $this->common_model->searchKeyword3('tableType');
        }
        else {

          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->ddlLocation2       = $this->session->userdata('ddlLocation2');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->ddlAccount= $this->session->userdata('ddlAccount');
//          $this->tableType         = $this->session->userdata('tableType');
        }
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        echo $this->bank_reconciliation_model->saveReconciled();
    }


  function getBankAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account'] = $this->common_model->getBankAccountsMultiLoc();
    $this->load->view('bank_reconciliation/show_bankAccounts',$data);
  }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
