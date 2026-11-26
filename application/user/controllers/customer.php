<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('customer_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CUSTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		//site Name
		$target = '.';
		$this->common_model->_paging($this->customer_model->getAllCustomerCount(), $target,'customer/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->customer_model->getAllCustomer();
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'customer/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('customer/customer');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCustomer()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlFinance', 'A/c Code', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'customer/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("customer/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//get active account grouping
		$data['list_finance']	   = $this->customer_model->getActiveFinance();
		$data['list_country']      = $this->customer_model->getCountry();
		$data['list_currency']     = $this->customer_model->getCurrency();
		$data['list_calculation']  = $this->customer_model->getCalculation();
		$data['list_parent_cust']  = $this->customer_model->getParentCustomer();
		$data['list_employee']     = $this->customer_model->getEmployee();
		$data['list_agent_ac']     = $this->customer_model->getAgentAccounts();
		$data['list_idType_value'] = $this->customer_model->getIdType();
		$data['list_loc_value']    = $this->customer_model->getLocation();
        $id 					   = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
		
		switch($id)
		{
			case "a":
				$this->showTitle  	  	  = ADDNEWCUST;
				$this->btnVal	  	  	  = ADDCONTINUE;
				$this->btnVal2	  	  	  = SAVEEXIT;
			
				$this->ddlFinance 	      = $this->input->post('ddlFinance');
				$this->txtDate  	  	  = $this->input->post('txtDate');
				$this->txtCode  	      = $this->input->post('txtCode');
				$this->txtName  	  	  = $this->input->post('txtName');
				$this->txtAddress  	  	  = $this->input->post('txtAddress');
				$this->txtEmail  	  	  = $this->input->post('txtEmail');
				$this->txtCivilId  	  	  = $this->input->post('txtCivilId');
				$this->txtPhone  	  	  = $this->input->post('txtPhone');
				$this->txtMobile  	  	  = $this->input->post('txtMobile');
				$this->txtFax   	  	  = $this->input->post('txtFax');
				$this->ddlLocation 	  	  = $this->input->post('ddlLocation');
				$this->ddlCountry 	  	  = $this->input->post('ddlCountry');
				$this->ddlCurrency 	  	  = $this->input->post('ddlCurrency');
				$this->ddlServCalc 	  	  = $this->input->post('ddlServCalc');
				$this->txtServCalc  	  = $this->input->post('txtServCalc');
				$this->ddlDiscCalc 	  	  = $this->input->post('ddlDiscCalc');
				$this->txtDiscCalc  	  = $this->input->post('txtDiscCalc');
				$this->ddlParentCust 	  = $this->input->post('ddlParentCust');
				$this->ddlEmpCode 	  	  = $this->input->post('ddlEmpCode');
				$this->txtCreditdays 	  = $this->input->post('txtCreditdays');
				$this->txtAmount 	  	  = $this->input->post('txtAmount');

				$this->chkValYes  	  	  = 'checked="checked"';
				$this->chkValNo	  	  	  = '';
				$this->chkValAlloYes  	  = 'checked="checked"';
				$this->chkValAlloNo	  	  = '';
				$this->chkValRefundYes	  = 'checked="checked"';
				$this->chkValRefundNo 	  = '';
				$this->catId	  	  	  = '';
			break;
			
			case "e":
				$this->showTitle  	  	  = EDITCUST;
				$this->btnVal	      	  = UPDATECUST;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  	  	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->customer_model->getCorresCustomer();
				$this->ddlFinance	  	  = $showVal->nFId;
				$this->txtDate  	  	  = $showVal->dDate;
				$this->txtCode  	  	  = $showVal->vCode;
				$this->txtName      	  = $showVal->vName;
				$this->txtAddress		  = $showVal->vAddress;
				$this->txtEmail 		  = $showVal->vEmail;
				$this->ddlIdType 		  = $showVal->vIdType;
				$this->txtCivilId 		  = $showVal->vCivilId;
				$this->txtPhone     	  = $showVal->vPhone;
				$this->txtMobile     	  = $showVal->vMobile;
				$this->txtFax       	  = $showVal->vFax;
				$this->ddlLocation		  = @split(',',$showVal->vLocation);
				$this->ddlCountry		  = $showVal->nCountry;
				$this->ddlCurrency		  = $showVal->nCurrency;
				$this->ddlServCalc		  = $showVal->vServCalc;
				$this->txtServCalc        = $showVal->vServCal;
				$this->ddlDiscCalc		  = $showVal->vDiscCalc;
				$this->txtDiscCalc        = $showVal->vDiscCal;
				$this->ddlParentCust 	  = $showVal->nParentCust;
				$this->ddlEmpCode    	  = $showVal->nEmpCode;
				$this->txtCreditdays 	  = $showVal->vCreditDays;
				$this->txtAmount     	  = $showVal->vAmount;
				$this->ddlAgentAc    	  = $showVal->nAgentAc;

				
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
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' )
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('customer/edit_customer');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->customer_model->insertCustomer();
				break;
				
				case "e":
					//Update into db
					$this->customer_model->updateCustomer();
				break;
			}//end switch
			redirect(site_url("customer/showlist/".$this->uri->segment(3).'/'.$msgVal));
			//}//end else
		}//end else	
	}//end function
	
	function delcustomer()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CUSTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->customer_model->deleteCustomer();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CUSTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->customer_model->deleteAllcustomer();
	}//end function 

	function checkEmail()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nCId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtEmail 		= $this->input->post('txtEmail');
		$txtEmail       	    = $this->common_model->get_field_name('CustomerMaster','vEmail'," WHERE vEmail='".mysql_real_escape_string($this->txtEmail)."' ".$extraCond." ");
		if($txtEmail    == $this->txtEmail)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function checkCustcode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nCId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 	 = $this->input->post('txtCode');
		$txtCode       	 = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");
		if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	//function for showing Customer Other Details
	function otherdetails()
	{
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$data['list_parent_cust'] = $this->customer_model->getParentCustomer();
		$data['list_employee']    = $this->customer_model->getEmployee();
		
		//set session for contact through list value
		if($this->input->post('txtCName') != 'undefined')	
			$this->session->set_userdata('txtCName',$this->input->post('txtCName'));
			
		if($this->input->post('txtCPhone') != 'undefined')	
			$this->session->set_userdata('txtCPhone',$this->input->post('txtCPhone'));
			
		if($this->input->post('txtCMobile') != 'undefined')	
			$this->session->set_userdata('txtCMobile',$this->input->post('txtCMobile'));
			
		if($this->input->post('txtCEmail') != 'undefined')	
		$this->session->set_userdata('txtCEmail',$this->input->post('txtCEmail'));
		
		$this->ddlParentCust = $this->session->userdata('parentCust');
		$this->ddlEmpCode    = $this->session->userdata('empCode');
		$this->txtCreditdays = $this->session->userdata('creditVal');
		$this->txtAmount     = $this->session->userdata('amountVal');
		
		$this->load->view('customer/show_otherdetails',$data);
	}//end function
	
	//function for showing Customer Contact through
	function contactthrough()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//set session for other details list value
		if($this->input->post('ddlParentCust') != 'undefined')
			$this->session->set_userdata('parentCust',$this->input->post('ddlParentCust'));
			
		if($this->input->post('ddlEmpCode') != 'undefined')
			$this->session->set_userdata('empCode',$this->input->post('ddlEmpCode'));
			
		if($this->input->post('txtCreditdays') != 'undefined')
			$this->session->set_userdata('creditVal',$this->input->post('txtCreditdays'));
			
		if($this->input->post('txtAmount') != 'undefined')
			$this->session->set_userdata('amountVal',$this->input->post('txtAmount'));
			
		$this->txtCName         = $this->session->userdata('txtCName');
		$this->txtCPhone        = $this->session->userdata('txtCPhone');
		$this->txtCMobile       = $this->session->userdata('txtCMobile');
		$this->txtCEmail        = $this->session->userdata('txtCEmail');
		
		$this->catId            = $this->input->post('nChkIDm');
		$this->load->view('customer/show_contact',$data);
	}//end function
	
	//generate subledger no according to the account head selected
	function generateSubLedgerNo()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlFinance   = $this->input->post('ddlFinance');
		if($this->ddlFinance != '0')
			$this->txtCode 	= $this->customer_model->getSubledgerNo($this->ddlFinance);
		$this->load->view('customer/show_financecode');
	}//end function

	function filterByCustomer()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CUSTOMERMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtCustomer = $this->common_model->searchKeyword('txtCustomer');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCustomer');
			$this->txtCustomer = $this->common_model->searchKeyword('txtCustomer');
		}//end if
		
		if(!empty($this->txtCustomer))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtCustomer." %' OR vName LIKE '% ".$this->txtCustomer."'  
							OR vName LIKE '%".$this->txtCustomer."%' OR vEmail LIKE '".$this->txtCustomer." %' OR vEmail LIKE '% ".$this->txtCustomer."'  
							OR vEmail LIKE '%".$this->txtCustomer."%'
							OR vCode LIKE '".$this->txtCustomer." %' OR vCode LIKE '% ".$this->txtCustomer."'
							OR vCode LIKE '%".$this->txtCustomer."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('CustomerMaster','nCId',$extraCheck), $target,'customer/filterByCustomer2/'.$this->uri->segment(3));
		$data['list_value'] = $this->customer_model->getFilterCustomer($extraCheck);
		
		$this->count_rows   = count($data['list_value']);
// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'customer/showlist');

		$this->load->view('customer/customer_filter',$data);
	}//end function
	
	function filterByCustomer2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CustomerMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtCustomer = $this->common_model->searchKeyword('txtCustomer');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCustomer');
			$this->txtCustomer = $this->common_model->searchKeyword('txtCustomer');
		}//end if
		
		if(!empty($this->txtCustomer))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtCustomer." %' OR vName LIKE '% ".$this->txtCustomer."'  
							OR vName LIKE '%".$this->txtCustomer."%' OR vEmail LIKE '".$this->txtCustomer." %' OR vEmail LIKE '% ".$this->txtCustomer."'  
							OR vEmail LIKE '%".$this->txtCustomer."%'
							OR vCode LIKE '".$this->txtCustomer." %' OR vCode LIKE '% ".$this->txtCustomer."'
							OR vCode LIKE '%".$this->txtCustomer."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('CustomerMaster','nCId',$extraCheck), $target,'customer/filterByCustomer2/'.$this->uri->segment(3));
		$data['list_value'] = $this->customer_model->getFilterCustomer($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'customer/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('customer/customer');
		$this->load->view('general/template_bottom');
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
