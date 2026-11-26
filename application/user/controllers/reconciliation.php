<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reconciliation extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('reconciliation_model');
        $this->load->model('cron_job_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = RECONCILIATION;
        $this->btnVal    = REPRINT;
        $this->common_model->check_admin_authorization();
		
		$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
		

        $data['list_div_value']     = $this->reconciliation_model->getActiveDivision();
        $data['list_loc_value']     = $this->reconciliation_model->getActiveLocations();

        $this->session->unset_userdata('pdfTitle');

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision      = $this->session->userdata('ddlDivision');
            $this->ddlLocation      = $this->session->userdata('ddlLocation');
            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');
            $this->txtToDate        = $this->session->userdata('txtToDate');
            $this->txtFromDate      = $this->session->userdata('txtFromDate');
            $this->txtReport        = $this->session->userdata('txtReport');
			$this->txtIATA        = $this->session->userdata('txtIATA');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtReport');
			$this->session->unset_userdata('txtIATA');
        }

        $this->load->view('general/template_top');
        $this->load->view('reconciliation/reconciliation',$data);
        $this->load->view('general/template_bottom');
    }//end function

    function displayList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = RECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
          $this->session->unset_userdata('backURL');

          if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
          $this->session->unset_userdata('ddlLocation');
          $this->session->unset_userdata('txtToDate');
          $this->session->unset_userdata('txtFromDate');
		  $this->session->unset_userdata('txtIATA');
          $this->session->unset_userdata('pageName');
          endif;

          $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
          $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
          $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
          $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
          $this->tableType         = $this->common_model->searchKeyword3('tableType');
		   $this->txtIATA         = $this->common_model->searchKeyword3('txtIATA');
        }
        else {

          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->tableType         = $this->session->userdata('tableType');
		   $this->txtIATA         = $this->session->userdata('txtIATA');
        }
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if ($this->txtFromDate !='' && $this->txtToDate != '') {
			$extraCheck = " WHERE `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA` = '".$this->txtIATA."'";
           // $extraCheck = " WHERE dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
		   $extraCheck = " WHERE `from` = '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND `to` = '" .date('Y-m-d',strtotime($this->txtToDate))."' AND `IATA` = '".$this->txtIATA."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {  $extraCheck = " WHERE `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA` = '".$this->txtIATA."'";
           // $extraCheck = " WHERE dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {  $extraCheck = " WHERE `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA` = '".$this->txtIATA."'";
            //$extraCheck = " WHERE dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " WHERE `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA` = '".$this->txtIATA."'";
        }
        $data['list_value']    = $this->reconciliation_model->getAllReconciliation($extraCheck);
        
        if($this->uri->segment(4)=='redirect'):
            $this->load->view('general/template_top');
            $this->load->view('reconciliation/display_reconciliation_redirect',$data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('reconciliation/display_reconciliation',$data);
        endif;

    }//end function

    function displayMatchedList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = RECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
            $this->session->unset_userdata('backURL');

            if(trim($this->session->userdata('backURL')) == ''):
              $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('pageName');
			$this->session->unset_userdata('txtIATA');
            endif;

            $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
            $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
            $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
            $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
            $this->tableType         = $this->common_model->searchKeyword3('tableType');
			$this->txtIATA         = $this->common_model->searchKeyword3('txtIATA');
        }
        else{
          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->tableType         = $this->session->userdata('tableType');
		  $this->txtIATA         = $this->session->userdata('txtIATA');

        }
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if ($this->txtFromDate !='' && $this->txtToDate != '') {  
		$extraCheck = " AND `from` = '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND `to` = '" .date('Y-m-d',strtotime($this->txtToDate))."' AND `IATA`='".$this->txtIATA."'";
            //$extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {  $extraCheck = " AND `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA`='".$this->txtIATA."'";
            //$extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {  $extraCheck = " AND `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA`='".$this->txtIATA."'";
            //$extraCheck = " AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " ";
        }

        $data['list_value']    = $this->reconciliation_model->getAllMatchedReconciliation($extraCheck);
        

        //$this->load->view('general/template_top');
        $this->load->view('reconciliation/display_reconciliation',$data);
        //$this->load->view('general/template_bottom');
    }//end function

    function displayUnMatchedList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = RECONCILIATION;
        $this->common_model->check_admin_authorization();

        $this->cron_job_model->checkReconciliation();

        if($this->input->post('sess_clear') == 'y'){
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('pageName');
			$this->session->unset_userdata('txtIATA');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');
		 $this->txtIATA         = $this->common_model->searchKeyword3('txtIATA');
        }
        else{
          $this->ddlDivision       = $this->session->userdata('ddlDivision');
          $this->ddlLocation       = $this->session->userdata('ddlLocation');
          $this->txtToDate         = $this->session->userdata('txtToDate');
          $this->txtFromDate       = $this->session->userdata('txtFromDate');
          $this->tableType         = $this->session->userdata('tableType');
		   $this->txtIATA         = $this->session->userdata('txtIATA');

        }

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if ($this->txtFromDate !='' && $this->txtToDate != '') {		
           $extraCheck = " AND `from` = '" .date('Y-m-d',strtotime($this->txtFromDate))."' AND `to` = '" .date('Y-m-d',strtotime($this->txtToDate))."' AND `IATA`='".$this->txtIATA."'";
        }//end if
        elseif($this->txtFromDate !='' && $this->txtToDate = '')
        {			$extraCheck = " AND `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA`='".$this->txtIATA."'";
            //$extraCheck = " AND dDate >= '" .date('Y-m-d',strtotime($this->txtFromDate))."'";
        }
        elseif($this->txtFromDate ='' && $this->txtToDate != '')
        {			$extraCheck = " AND `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA`='".$this->txtIATA."'";
            //$extraCheck = " AND dDate <= '" .date('Y-m-d',strtotime($this->txtToDate))."'";
        }
        else
        {
            $extraCheck = " ";
        }

        $data['list_value']    = $this->reconciliation_model->getAllUnMatchReconciliation($extraCheck);
        

        //$this->load->view('general/template_top');
        $this->load->view('reconciliation/display_reconciliation',$data);
        //$this->load->view('general/template_bottom');
    }//end function

    function process()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = RECONCILIATION;
        $this->btnVal = REPRINT;
        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
			 $this->session->unset_userdata('txtIATA');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtReport');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtFromDate       = date('Y-m-d',strtotime($this->common_model->searchKeyword3('txtFromDate')));
        $this->txtToDate         = date('Y-m-d',strtotime($this->common_model->searchKeyword3('txtToDate')));
        $this->txtReport         = $this->common_model->searchKeyword3('txtReport');
		 $this->txtIATA         = $this->common_model->searchKeyword3('txtIATA');
       
        $this->session->set_userdata('pageName',$this->uri->segment(1));

        $this->session->unset_userdata('pdfTitle');
        $this->session->set_userdata('pdfTitle',$_FILES['txtFile']['tmp_name']);

        if(trim($_FILES['txtFile']['tmp_name'])!=''):
		    $fetchVal = $this->reconciliation_model->getPDFText($_FILES['txtFile']['tmp_name'],$this->txtFromDate,$this->txtToDate);
//return;
            //redirect(site_url("reconciliation/displayList/".$this->uri->segment(3).'/redirect'));
//return;
            $extraCheck = " WHERE `from` = '" .$this->txtFromDate."' AND `to` = '" .$this->txtToDate."' AND `IATA`='".$this->txtIATA."'";

        $data['list_value']    = $this->reconciliation_model->getAllReconciliation($extraCheck);
            $this->load->view('general/template_top');
            $this->load->view('reconciliation/display_reconciliation_redirect',$data);
            $this->load->view('general/template_bottom');
 

        else:
            //redirect(site_url("reconciliation/displayList/".$this->uri->segment(3)));
        endif;
    } //end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

        $this->ddlLocation   = $this->session->userdata('ddlLocation');
        $this->ddlLocation2  = $this->session->userdata('ddlLocation2');

		$data['list_loc_value'] = $this->reconciliation_model->filterLocation($this->ddlDivision);
		$this->load->view('ac_report/show_location',$data);
	}//end function

    function saveRecords()
    {
        $this->load->helper(array('form', 'url')); 
        $this->showTitle = RECONCILIATION;
        $this->common_model->check_admin_authorization();
        $this->reconciliation_model->saveRecords();
    }
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
