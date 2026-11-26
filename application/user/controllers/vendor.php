<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Vendor extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('vendor_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VENDORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		//site Name
		$target = '.';
		$this->common_model->_paging($this->vendor_model->getAllVendorCount(), $target,'vendor/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->vendor_model->getAllVendor();
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'vendor/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('vendor/vendor');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editVendor()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlFinance', 'A/c Code', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'vendor/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("vendor/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//get active account grouping
		$data['list_finance']	  	= $this->vendor_model->getActiveFinance();
		$data['list_country']     	= $this->vendor_model->getCountry();
		$data['list_currency']    	= $this->vendor_model->getCurrency();
		$data['list_calculation']   = $this->vendor_model->getCalculation();
		$data['list_parent_vendor'] = $this->vendor_model->getParentVendor();
		$data['list_employee']    	= $this->vendor_model->getEmployee();
		$data['list_loc_value']     = $this->vendor_model->getLocation();
		$data['list_agent_ac']      = $this->vendor_model->getAgentAccounts();
		$data['list_idType_value']  = $this->vendor_model->getIdType();
		$data['list_loc_value']     = $this->vendor_model->getLocation();
        $id 					    = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
		
		switch($id)
		{
			case "a":
				$this->showTitle  	  	  = ADDNEWVENDOR;
				$this->btnVal	  	  	  = ADDCONTINUE;
				$this->btnVal2	  	  	  = SAVEEXIT;
			
				$this->ddlFinance 	      = $this->input->post('ddlFinance');
				$this->txtDate  	  	  = $this->input->post('txtDate');
				$this->txtCode  	  	  = $this->input->post('txtCode');
				$this->txtName  	  	  = $this->input->post('txtName');
				$this->txtAddress  	  	  = $this->input->post('txtAddress');
				$this->txtEmail  	  	  = $this->input->post('txtEmail');
				$this->txtPhone  	  	  = $this->input->post('txtPhone');
				$this->txtMobile  	  	  = $this->input->post('txtMobile');
				$this->txtFax   	  	  = $this->input->post('txtFax');
				$this->ddlCountry 	  	  = $this->input->post('ddlCountry');
				$this->ddlCurrency 	  	  = $this->input->post('ddlCurrency');
				$this->ddlLocation 	  	  = $this->input->post('ddlLocation');

				$this->ddlParentVendor 	  = $this->input->post('ddlParentVendor');
				$this->ddlEmpCode 	  	  = $this->input->post('ddlEmpCode');

				$this->chkValYes  	  	  = 'checked="checked"';
				$this->chkValNo	  	  	  = '';
				$this->chkValAlloYes  	  = 'checked="checked"';
				$this->chkValAlloNo	  	  = '';
				$this->chkValRefundYes	  = 'checked="checked"';
				$this->chkValRefundNo 	  = '';
				$this->catId	  	  	  = '';
			break;
			
			case "e":
				$this->showTitle  	  	  = EDITVENDOR;
				$this->btnVal	      	  = UPDATEVENDOR;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  	  	  = intval($this->input->post('nId'));
				}//end if
				
				if(trim($this->input->post('nCheck')) != '')
				{
					$this->nCheck  	  	  = $this->input->post('nCheck');
				}//end if
				
				$showVal	          	  = $this->vendor_model->getCorresvendor();
				$this->ddlFinance	  	  = $showVal->nFId;
				$this->txtDate  	  	  = $showVal->dDate;
				$this->txtCode  	  	  = $showVal->vCode;
				$this->txtName      	  = $showVal->vName;
				$this->txtAddress		  = $showVal->vAddress;
				$this->txtEmail 		  = $showVal->vEmail;
				$this->txtPhone     	  = $showVal->vPhone;
				$this->txtMobile     	  = $showVal->vMobile;
				$this->txtFax       	  = $showVal->vFax;
				$this->ddlIdType 		  = $showVal->vIdType;
				$this->txtCivilId 		  = $showVal->vCivilId;
				$this->ddlLocation	      = @split(',',$showVal->vLocation);
				$this->ddlCountry		  = $showVal->nCountry;
				$this->ddlCurrency		  = $showVal->nCurrency;
				$this->ddlServCalc		  = $showVal->vServCalc;
				$this->txtServCalc        = $showVal->vServCal;
				$this->ddlDiscCalc		  = $showVal->vDiscCalc;
				$this->txtDiscCalc        = $showVal->vDiscCal;
				$this->ddlParentVendor 	  = $showVal->nParentVendor;
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
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('vendor/edit_vendor');
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
					$this->vendor_model->insertvendor();
				break;
				
				case "e":
					//Update into db
					$this->vendor_model->updatevendor();
				break;
			}//end switch
			redirect(site_url("vendor/showlist/".$this->uri->segment(3).'/'.$msgVal));
			//}//end else
		}//end else	
	}//end function
	
	function delvendor()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VENDORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->vendor_model->deletevendor();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VENDORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->vendor_model->deleteAllvendor();
	}//end function 

	function checkEmail()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nVId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtEmail = $this->input->post('txtEmail');
		$txtEmail       = $this->common_model->get_field_name('VendorMaster','vEmail'," WHERE vEmail='".mysql_real_escape_string($this->txtEmail)."' ".$extraCond." ");
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
				$extraCond = " AND nVId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 	 = $this->input->post('txtCode');
		$txtCode       	 = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");
		if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	//generate subledger no according to the account head selected
	function generateSubLedgerNo()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlFinance   = $this->input->post('ddlFinance');
		if($this->ddlFinance != '0')
			$this->txtCode 	= $this->vendor_model->getSubledgerNo($this->ddlFinance);
		$this->load->view('vendor/show_financecode');
	}//end function

	function filterByVendor()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VENDORMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtVendor = $this->common_model->searchKeyword('txtVendor');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtOther');
			$this->txtVendor = $this->common_model->searchKeyword('txtVendor');
		}//end if
		
		if(!empty($this->txtVendor))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtVendor." %' OR vName LIKE '% ".$this->txtVendor."'  
							OR vName LIKE '%".$this->txtVendor."%' OR vEmail LIKE '".$this->txtVendor." %' OR vEmail LIKE '% ".$this->txtVendor."'  
							OR vEmail LIKE '%".$this->txtVendor."%'
							OR vCode LIKE '".$this->txtVendor." %' OR vCode LIKE '% ".$this->txtVendor."'
							OR vCode LIKE '%".$this->txtVendor."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('VendorMaster','nVId',$extraCheck), $target,'vendor/filterByVendor2/'.$this->uri->segment(3));
		$data['list_value'] = $this->vendor_model->getFilterVendor($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'vendor/showlist');

		$this->load->view('vendor/vendor_filter',$data);
	}//end function
	
	function filterByVendor2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VENDORMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtVendor = $this->common_model->searchKeyword('txtVendor');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtOther');
			$this->txtVendor = $this->common_model->searchKeyword('txtVendor');
		}//end if
		
		if(!empty($this->txtVendor))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtVendor." %' OR vName LIKE '% ".$this->txtVendor."'  
							OR vName LIKE '%".$this->txtVendor."%' OR vEmail LIKE '".$this->txtVendor." %' OR vEmail LIKE '% ".$this->txtVendor."'  
							OR vEmail LIKE '%".$this->txtVendor."%'
							OR vCode LIKE '".$this->txtVendor." %' OR vCode LIKE '% ".$this->txtVendor."'
							OR vCode LIKE '%".$this->txtVendor."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('VendorMaster','nVId',$extraCheck), $target,'vendor/filterByVendor2/'.$this->uri->segment(3));
		$data['list_value'] = $this->vendor_model->getFilterVendor($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'vendor/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('other/other');
		$this->load->view('general/template_bottom');
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
