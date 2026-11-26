<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Account_period extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('account_period_model');
	}

	function showlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = ACCPERIODMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$data['list_status']  = $this->account_period_model->getDropAllStatus();
		$countAccperiod		  = $this->account_period_model->getCountAccountperiods();
		$target 			  = '.';
		$this->common_model->_paging($countAccperiod, $target,'account_period/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->account_period_model->getAllAccountperiods();
		$this->count_rows     = count($data['list_value']);

		$this->txtStartDate   = date('Y').'-01-01';
		$this->txtEndDate     = date('Y').'-12-01';

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_period/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('accountperiod/account_period');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editAccountperiod()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtYearref', 'Year REF', 'required');
		$data['list_status']  = $this->account_period_model->getDropStatus();

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_period/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWACCPERIOD;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtYearef  = $this->input->post('txtYearef');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITACCPERIOD;
				$this->btnVal	  = UPDATEACCPERIOD;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	        = $this->account_period_model->getCorresAccperiod();
				$this->txtYearref	= $showVal->vYearRef;
				$this->txtFDate 	= $showVal->dFDate;
				$this->txtTDate 	= $showVal->dTDate;
				$this->ddlStatus	= $showVal->vStatus;
			break;
		}//end switch
		
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' )
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('accountperiod/edit_account_period');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					$this->account_period_model->insertAccperiod();
				break;
				
				case "e":
					$this->account_period_model->updateAccperiod();
				break;
			}//end switch
			redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function delaccperiod()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCPERIODMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->account_period_model->deleteAccperiod();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCPERIODMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->account_period_model->deleteAllAccperiod();
	}//end function 

	function generateAccperiod()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->txtYearref      	= $this->input->post('txtYearref');
		$this->txtFDate         = $this->input->post('txtFDate');
		$this->txtTDate      	  = $this->input->post('txtTDate');
    $this->operation        = $this->uri->segment(4);
		
		$data['acc_period']		= $this->account_period_model->accPeriodGeneration();
		//$this->load->view('accountperiod/generate_accperiod');
	}//end function

	function filterList()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCPERIODMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target 		 = '.';
		$this->ddlStatus = $this->common_model->searchKeyword('ddlStatus');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtStartDate');
			$this->session->unset_userdata('txtEndDate');
			$this->txtFrom = $txtFrom 	= $this->common_model->searchKeyword('txtStartDate');
			$this->txtTo   = $txtTo   	= $this->common_model->searchKeyword('txtEndDate');
		}//end if
		
		if($this->common_model->searchKeyword('txtStartDate') != '')
		{
			//convet date to mysql format
			$this->txtFrom = $txtFrom 	= $this->common_model->searchKeyword('txtStartDate');
			$this->txtTo   = $txtTo   	= $this->common_model->searchKeyword('txtEndDate');
		
			$extraCheck = " WHERE dFDate >= '".$txtFrom."' AND dTDate >= '".$txtTo."' ";
			
			switch($this->ddlStatus)
			{
				case "0" :
					$extraCheck .= " ";
				break;
				
				default :
					$extraCheck .= " AND vStatus ='".$this->ddlStatus."'";
				break;
			}//end switch
		}//end if
		
		else
		{
			switch($this->ddlStatus)
			{
				case "0" :
					$extraCheck = " ";
				break;
				
				default :
					$extraCheck = " WHERE vStatus ='".$this->ddlStatus."'";
				break;
			}//end switch
		}//end else

		$countfilterList	= $this->account_period_model->getCountfilterList($extraCheck);
		$this->common_model->_paging($countfilterList,$target,'account_period/filterList2/'.$this->uri->segment(3));
		$data['list_value']	= $this->account_period_model->getFilterList($extraCheck);
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_period/showlist');

		$this->load->view('accountperiod/account_period_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCPERIODMANAGEMENT;
		$this->common_model->check_admin_authorization();
	
	    $data['list_status']  = $this->account_period_model->getDropAllStatus();	
		$target 		 	  = '.';
		$this->ddlStatus 	  = $this->common_model->searchKeyword('ddlStatus');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtStartDate');
			$this->session->unset_userdata('txtEndDate');
			$this->txtFrom = $txtFrom 	= $this->common_model->searchKeyword('txtStartDate');
			$this->txtTo   = $txtTo   	= $this->common_model->searchKeyword('txtEndDate');
		}//end if
		
		if($this->common_model->searchKeyword('txtStartDate') != '')
		{
			//convet date to mysql format
			$this->txtFrom = $txtFrom 	= $this->common_model->searchKeyword('txtStartDate');
			$this->txtTo   = $txtTo   	= $this->common_model->searchKeyword('txtEndDate');
		
			$extraCheck = " WHERE dFDate >= '".$txtFrom."' AND dTDate >= '".$txtTo."'";
			switch($this->ddlStatus)
			{
				case "0" :
					$extraCheck .= " ";
				break;
				
				default :
					$extraCheck .= " AND vStatus ='".$this->ddlStatus."'";
				break;
			}//end switch
		}//end if
		
		else
		{
			switch($this->ddlStatus)
			{
				case "0" :
					$extraCheck = " ";
				break;
				
				default :
					$extraCheck = " WHERE vStatus ='".$this->ddlStatus."'";
				break;
			}//end switch
		}//end else
	
		$countfilterList	= $this->account_period_model->getCountfilterList($extraCheck);
		$this->common_model->_paging($countfilterList,$target,'account_period/filterList2/'.$this->uri->segment(3));
		$data['list_value']	= $this->account_period_model->getFilterList($extraCheck);

		$this->load->view('general/template_top',$data);
		$this->load->view('accountperiod/account_period');
		$this->load->view('general/template_bottom');
	}//end function

	function changeStatusOpen()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$id = $this->uri->segment(4);
		$this->account_period_model->changeStatusOpen($id);
	}//end function

	function changeStatusClosed()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$id = $this->uri->segment(4);
		$this->account_period_model->changeStatusClosed($id);
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
