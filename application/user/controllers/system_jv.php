<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class System_jv extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('system_jv_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = SYSTEMJVMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countCreditVoucher   =  $this->system_jv_model->getCountSystemJv();
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'system_jv/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->system_jv_model->getAllSystemJv();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('system_jv/system_jv');
		$this->load->view('general/template_bottom');
	}//end function
	
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SYSTEMJVMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtDivision  = $this->common_model->searchKeyword('txtDivision');
        $this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
        $this->txtDate      = $this->common_model->searchKeyword('txtDate');
        $this->txtDoc       = $this->common_model->searchKeyword('txtDoc');
        $this->txtInvo      = $this->common_model->searchKeyword('txtInvo');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtDivision');
            $this->session->unset_userdata('txtLocation');
            $this->session->unset_userdata('txtDate');
            $this->session->unset_userdata('txtDoc');
            $this->session->unset_userdata('txtInvo');
            
            $this->txtDivision  = $this->common_model->searchKeyword('txtDivision');
            $this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
            $this->txtDate      = $this->common_model->searchKeyword('txtDate');
            $this->txtDoc       = $this->common_model->searchKeyword('txtDoc');
            $this->txtInvo      = $this->common_model->searchKeyword('txtInvo');
        }//end if

        if(!empty($this->txtDivision))
        {
            $extraCheck = " AND (d.vTitle LIKE '".$this->txtDivision." %' OR d.vTitle 
										LIKE '% ".$this->txtDivision."'
								OR d.vTitle LIKE '%".$this->txtDivision."%')";
        }//end if

        if(!empty($this->txtLocation))
        {
            $extraCheck .= " AND (l.vTitle LIKE '".$this->txtLocation." %' OR l.vTitle 
										LIKE '% ".$this->txtLocation."'
									OR l.vTitle LIKE '%".$this->txtLocation."%')";
        }//end if

        if(!empty($this->txtDate))
        {
			$extraCheck .= " AND sj.dDate >= '".$this->txtDate."' AND sj.dDate <= '".$this->txtDate."'";
        }//end if

        if(!empty($this->txtDoc))
        {
            $extraCheck .= " AND (sj.vJVNo LIKE '".$this->txtDoc." %' OR sj.vJVNo LIKE '% ".$this->txtDoc."'
									OR sj.vJVNo LIKE '%".$this->txtDoc."%')";
        }//end if

        if(!empty($this->txtInvo))
        {
            $extraCheck 	.= " AND (sj.nInvoice LIKE '".$this->txtInvo." %' OR sj.nInvoice 
											LIKE '% ".$this->txtInvo."'
									OR sj.nInvoice LIKE '%".$this->txtInvo."%')";
        }//end if

        $countFilterList	=  $this->system_jv_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'system_jv/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->system_jv_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('system_jv/system_jv_filter',$data);
	}//end function

	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SYSTEMJVMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtDivision  = $this->common_model->searchKeyword('txtDivision');
        $this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
        $this->txtDate      = $this->common_model->searchKeyword('txtDate');
        $this->txtDoc       = $this->common_model->searchKeyword('txtDoc');
        $this->txtInvo      = $this->common_model->searchKeyword('txtInvo');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtDivision');
            $this->session->unset_userdata('txtLocation');
            $this->session->unset_userdata('txtDate');
            $this->session->unset_userdata('txtDoc');
            $this->session->unset_userdata('txtInvo');
            
            $this->txtDivision  = $this->common_model->searchKeyword('txtDivision');
            $this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
            $this->txtDate      = $this->common_model->searchKeyword('txtDate');
            $this->txtDoc       = $this->common_model->searchKeyword('txtDoc');
            $this->txtInvo      = $this->common_model->searchKeyword('txtInvo');
        }//end if

        if(!empty($this->txtDivision))
        {
            $extraCheck = " AND (d.vTitle LIKE '".$this->txtDivision." %' OR d.vTitle 
										LIKE '% ".$this->txtDivision."'
								OR d.vTitle LIKE '%".$this->txtDivision."%')";
        }//end if

        if(!empty($this->txtLocation))
        {
            $extraCheck .= " AND (l.vTitle LIKE '".$this->txtLocation." %' OR l.vTitle 
										LIKE '% ".$this->txtLocation."'
									OR l.vTitle LIKE '%".$this->txtLocation."%')";
        }//end if

        if(!empty($this->txtDate))
        {
			$extraCheck .= " AND sj.dDate >= '".$this->txtDate."' AND sj.dDate <= '".$this->txtDate."'";
        }//end if

        if(!empty($this->txtDoc))
        {
            $extraCheck .= " AND (sj.vJVNo LIKE '".$this->txtDoc." %' OR sj.vJVNo LIKE '% ".$this->txtDoc."'
									OR sj.vJVNo LIKE '%".$this->txtDoc."%')";
        }//end if

        if(!empty($this->txtInvo))
        {
            $extraCheck 	.= " AND (sj.nInvoice LIKE '".$this->txtInvo." %' OR sj.nInvoice 
											LIKE '% ".$this->txtInvo."'
									OR sj.nInvoice LIKE '%".$this->txtInvo."%')";
        }//end if

        $countFilterList	=  $this->system_jv_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'system_jv/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->system_jv_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('system_jv/system_jv');
		$this->load->view('general/template_bottom');
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>