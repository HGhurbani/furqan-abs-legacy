<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monthly_budget extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('budget_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	      = MONTHLYBUDGETMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

		$data['list_month_value'] = $this->budget_model->getAllMonths();
		$data['list_year_value']  = $this->budget_model->getAllYears();			
		$countMonthlybudget	  	  = $this->budget_model->getCountMonthlybudget();
		$target 			      = '.';

		$this->common_model->_paging($countMonthlybudget, $target,'monthly_budget/showlist/'.$this->uri->segment(3));
		$data['list_value']       = $this->budget_model->getAllMonthlybudget();
		$this->count_rows         = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'monthly_budget/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('budget/monthly_budget');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editMonthlybudget()
	{
		$this->common_model->check_admin_authorization();
		//site Namen
		$data['title'] 	   		     = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');

    $id = $this->uri->segment(4);
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'monthly_budget/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("monthly_budget/showlist/".$this->uri->segment(3).'/no_access'));
    }


		$data['list_div_value']      = $this->budget_model->getActiveDivision();
		$data['list_month_value'] 	 = $this->budget_model->getMonths();
		$data['list_mode']        	 = $this->budget_model->getActiveMode();
		$data['list_accounts']       = $this->budget_model->getActiveAccount();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWMONTHLYBUDGET;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				$data['finance_details']  = $this->budget_model->getActiveFinance();
				$data['list_loc_value']   = $this->budget_model->getActiveLocations();
				
				$this->ddlDivision 		  = $this->input->post('ddlDivision');
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtDate  		  = date('Y-m-d');				
				$this->txtBudYear		  = date('Y');
				$this->ddlMonth 		  = $this->input->post('ddlMonth');				
				$this->txtDocNumber       = $this->input->post('txtDocNumber');
				$this->txtYearref         = $this->input->post('txtYearref');
				$this->txtTotalAmount     = $this->input->post('txtTotalAmount');
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$data['chkAccCode']  	  = array();
				$data['chkMode'] 	  	  = array();
				$data['chkAmount'] 	  	  = array();
			break;
			
			case "e":
				$this->showTitle      	  = EDITMONTHLYBUDGET;
				$this->btnVal	      	  = UPDATEMONTHLYBUDGET;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if

				$showVal	          	  = $this->budget_model->getCorresMonthlybudget();
				$this->ddlDivision 		  = $showVal->mDivision;
				$this->ddlLocation	  	  = $showVal->vLocation;
				$this->txtDocNumber	  	  = $showVal->vDocNumber;
				$this->txtDate            = $showVal->dDate;
				$this->ddlMonth 	  	  = $showVal->vMonth;
				$this->ddlMode        	  = $showVal->vMode;
				$this->txtYearref         = $showVal->vYearref;
				$this->txtBudYear         = $showVal->vYear;
				$this->txtBudCopyref      = $showVal->vCopyref;
				$this->txtTotalAmount     = $showVal->nTotal;
				$data['list_loc_value']   = $this->budget_model->filterLocation($this->ddlDivision);
				
				//get selected details code
				$accValArray              = $this->budget_model->getCorresMonthlybudgetDetails();
			    $data['chkAccCode']  	  = $accValArray['acCode'];
				$data['chkMode'] 		  = $accValArray['acMode'];
				$data['chkAmount'] 		  = $accValArray['acAmount'];
				$data['chkAccID']    	  = $accValArray['acbudDetID'];
				$data['buddetID']         = $accValArray['acbudDetID'];
				
				if($showVal->vActive == '1')
				{
					$this->chkValYes  = 'checked="checked"';
				}//end if 
				else
				{
					$this->chkValNo   = 'checked="checked"';
				}//end else
			break;
		}//end switch
		
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('budget/edit_monthly_budget');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->budget_model->insertMonthlybudget();
				break;
				
				case "e":
					//Update into db
					$this->budget_model->updateMonthlybudget();
				break;
			}//end switch
			redirect(site_url("monthly_budget/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->budget_model->filterLocation($this->ddlDivision);
		$this->load->view('budget/show_location',$data);
	}//end function

	function delmonthlybudget()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MONTHLYBUDGETMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->budget_model->deleteMonthlybudget();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MONTHLYBUDGETMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->budget_model->deleteAllMonthlybudget();
	}//end function 
	
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey =  $this->input->post('q');
		$items     = $this->budget_model->searchActiveFinance($searchKey);
		echo json_encode($items);
	}//end function
	
	//get travel details according to the A/c code selected
	function getMonthlyDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function
	
	//get travel details according to the A/c code selected
	function checkCopyrefAccounts()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$data['list_accounts'] = $this->budget_model->getActiveAccount();
		$data['list_mode']     = $this->budget_model->getActiveMode();
		$this->txtBudCopyref   = $this->input->post('txtBudCopyref');
		$this->budId	       = $this->common_model->get_field_name('Budget','nBId'," WHERE vDocNumber = '".$this->txtBudCopyref."'"); 
		$chkAccdID   		   = $this->budget_model->getCorresFinanceDetails($this->budId);
		$data['chkAccCode']    = $chkAccdID['accdCode'];
		$data['chkMode']       = $chkAccdID['accdMode'];
		$data['chkAmount']     = $chkAccdID['accdAmount'];
		$data['chkAccdID']     = $chkAccdID['accdId'];
		$this->txtTotalAmount  = $this->budget_model->getTotalAmount($this->budId);
		$this->load->view('budget/show_accdetails',$data);
	}//end function
	
	function generateDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlMonth     = $this->input->post('ddlMonth');
		$this->txtBudYear   = $this->input->post('txtBudYear'); 
		
		$this->txtDocNumber = $this->budget_model->getAutomaticDocNumber($this->ddlMonth,$this->txtBudYear);
		
		$this->load->view('budget/show_budget');
	}//end function

	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->budget_model->deleteSubAndDetails($this->subDetID);
	}//end function

	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MONTHLYBUDGETMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		$this->ddlMonth  = $this->common_model->searchKeyword('ddlMonth');
		$this->ddlYear   = $this->common_model->searchKeyword3('ddlYear');

		if(!empty($this->txtSearch))
		{
			$extraCheck = " AND(b.vDocNumber LIKE '".$this->txtSearch." %' OR b.vDocNumber LIKE '% ".$this->txtSearch."'  
							OR b.vDocNumber LIKE '%".$this->txtSearch."%')";

			switch($this->ddlMonth)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vMonth = '".$this->ddlMonth."'";
				break;
			}//end switch

			switch($this->ddlYear)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vYear = '".$this->ddlYear."'";
				break;
			}//end switch
		}//end if

		else
		{
			switch($this->ddlMonth)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vMonth = '".$this->ddlMonth."'";
				break;
			}//end switch

			switch($this->ddlYear)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vYear = '".$this->ddlYear."'";
				break;
			}//end switch
		}//end else

		$countFilterList    =  $this->budget_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'monthly_budget/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->budget_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'monthly_budget/showlist');

		$this->load->view('budget/monthly_budget_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MONTHLYBUDGETMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if

		$this->ddlMonth  = $this->common_model->searchKeyword('ddlMonth');
		$this->ddlYear   = $this->common_model->searchKeyword3('ddlYear');
		
		if(!empty($this->txtSearch))
		{
			$extraCheck = " AND(b.vDocNumber LIKE '".$this->txtSearch." %' OR b.vDocNumber LIKE '% ".$this->txtSearch."'  
							OR b.vDocNumber LIKE '%".$this->txtSearch."%')";

			switch($this->ddlMonth)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vMonth = '".$this->ddlMonth."'";
				break;
			}//end switch

			switch($this->ddlYear)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vYear = '".$this->ddlYear."'";
				break;
			}//end switch
		}//end if

		else
		{
			switch($this->ddlMonth)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vMonth = '".$this->ddlMonth."'";
				break;
			}//end switch

			switch($this->ddlYear)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND b.vYear = '".$this->ddlYear."'";
				break;
			}//end switch
		}//end else
		
		$countFilterList    =  $this->budget_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'monthly_budget/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->budget_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'monthly_budget/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('budget/monthly_budget');
		$this->load->view('general/template_bottom');
	}//end function
  function getAllAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_accounts'] = $this->common_model->getAllAccounts();
    $this->load->view('budget/show_allAccounts',$data);

  }
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
