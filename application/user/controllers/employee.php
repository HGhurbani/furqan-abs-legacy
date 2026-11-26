<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee extends CI_Controller {



	function __construct()

	{

		parent::__construct();

		$this->load->model('employee_model');

	}



	function showlist()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle 	= EMPMANAGEMENT;

		$this->common_model->check_admin_authorization();

		

		//site Name

		$target 			= '.';

		$this->common_model->_paging($this->employee_model->getAllemployeeCount(), $target,'employee/showlist/'.$this->uri->segment(3));

		$data['list_value'] = $this->employee_model->getAllemployee();

		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('general/template_top',$data);

		$this->load->view('employee/employee');

		$this->load->view('general/template_bottom');

	}//end function

	

	function editEmployee()

	{

		$this->common_model->check_admin_authorization();

		//site Name

		$data['title'] 	   		= $this->common_model->get_site_title(); 

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('ddlFinance', 'A/c Code', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("employee/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//get active account grouping

		$data['list_finance']      = $this->employee_model->getActiveFinance();

		$data['list_country']      = $this->employee_model->getCountry();

		$data['list_currency']     = $this->employee_model->getCurrency();

		//$data['list_section']   = $this->employee_model->getSection();

		$data['list_calculation']  = $this->employee_model->getCalculation();

		$data['list_idType_value'] = $this->employee_model->getIdType();

		$data['list_employee_ac']  = $this->employee_model->getEmployeeAccounts();

		$data['list_employee_rac'] = $this->employee_model->getEmployeeReceivableAccounts();

		$data['list_des_value']    = $this->employee_model->getDesignation();

		$data['list_location']     = $this->employee_model->getLocation();

        $id 					   = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';

		

		switch($id)

		{

			case "a":

				$this->showTitle  	  	  = ADDNEWEMP;

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

				$this->ddlCountry 	  	  = $this->input->post('ddlCountry');

				$this->ddlCurrency 	  	  = $this->input->post('ddlCurrency');

				$this->ddlGroupMgr 	  	  = $this->input->post('ddlGroupMgr');

				$this->ddlEmpRAc 	  	  = $this->input->post('ddlEmpRAc');

				$this->ddlLocation 	  	  = $this->input->post('ddlLocation');



				$this->chkValYes  	  	  = 'checked="checked"';

				$this->chkValNo	  	  	  = '';

				$this->catId	  	  	  = '';

			break;

			

			case "e":

				$this->showTitle  	  	  = EDITEMP;

				$this->btnVal	      	  = UPDATEEMP;

				$this->catId	      	  = $this->uri->segment(5);

				if(intval($this->input->post('nId')) != 0)

				{

					$this->catId  	  	  = intval($this->input->post('nId'));

				}//end if

				

				$showVal	          	  = $this->employee_model->getCorresEmployee();

				$this->ddlFinance	  	  = $showVal->nFId;

				$this->txtDate  	  	  = $showVal->dDate;

				$this->txtCode  	  	  = $showVal->vCode;

				$this->txtName      	  = $showVal->vName;

				$this->txtAddress		  = $showVal->vAddress;

				$this->txtEmail 		  = $showVal->vEmail;

				$this->txtPhone     	  = $showVal->vPhone;

				$this->txtMobile     	  = $showVal->vMobile;

				$this->txtFax             = $showVal->vFax;

				$this->ddlIdType 		  = $showVal->vIdType;

				$this->txtCivilId 		  = $showVal->vCivilId;

				$this->ddlServCalc		  = $showVal->vServCalc;

				$this->txtServCalc        = $showVal->vServCal;

				$this->ddlDiscCalc		  = $showVal->vDiscCalc;

				$this->txtDiscCalc        = $showVal->vDiscCal;

				$this->txtCreditdays 	  = $showVal->vCreditDays;

				$this->txtAmount     	  = $showVal->vAmount;

				$this->txtGallileoCD      = $showVal->vGallileoCD;

				$this->txtAmadeusCD       = $showVal->vAmadeusCD;

				$this->txtSabre       	  = $showVal->vSabre;

				$this->ddlDesignation	  = $showVal->nDesignation;

				$this->ddlGroupMgr		  = $showVal->nGroupMgr;

				$this->ddlEmpRAc		  = $showVal->nEmpRAc;

				$this->ddlCountry		  = $showVal->nCountry;

				$this->ddlCurrency		  = $showVal->nCurrency;

				$this->ddlLocation1       = $showVal->vLocation;

				$this->ddlLocation        = @split(',',$showVal->vLocations);

				

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

			$this->load->view('employee/edit_employee');

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

					$this->employee_model->insertEmployee();

				break;

				

				case "e":

					//Update into db

					$this->employee_model->updateEmployee();

				break;

			}//end switch

			redirect(site_url("employee/showlist/".$this->uri->segment(3).'/'.$msgVal));

			//}//end else

		}//end else	

	}//end function

	

	function delemployee()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = EMPMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->employee_model->deleteEmployee();

	}//end function 

	

	function delrecords()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = EMPMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->employee_model->deleteAllemployee();

	}//end function 



	function checkEmail()

	{

		switch($this->uri->segment(3))

		{

			case "":

				$extraCond = '';

			break;

			

			default:

				$extraCond = " AND nEId != '".$this->uri->segment(3)."'";

			break;

		}//end switch

		$this->txtEmail = $this->input->post('txtEmail');

		$txtEmail       = $this->common_model->get_field_name('EmployeeMaster','vEmail'," WHERE vEmail='".mysql_real_escape_string($this->txtEmail)."' ".$extraCond." ");

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

				$extraCond = " AND nEId != '".$this->uri->segment(3)."'";

			break;

		}//end switch

		$this->txtCode 	 = $this->input->post('txtCode');

		$txtCode       	 = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");

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

			$this->txtCode 	= $this->employee_model->getSubledgerNo($this->ddlFinance);

		$this->load->view('employee/show_financecode');

	}//end function



	function filterByEmployee()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = EMPMANAGEMENT;

		

		$this->common_model->check_admin_authorization();

		$this->txtEmployee = $this->common_model->searchKeyword('txtEmployee');

		

		if($this->input->post('btnSearch') == 'Go')

		{

			$this->session->unset_userdata('txtEmployee');

			$this->txtEmployee = $this->common_model->searchKeyword('txtEmployee');

		}//end if

		

		if(!empty($this->txtEmployee))

		{

			$extraCheck = " WHERE (vName LIKE '".$this->txtEmployee." %' OR vName LIKE '% ".$this->txtEmployee."'  

							OR vName LIKE '%".$this->txtEmployee."%' OR vEmail LIKE '".$this->txtEmployee." %' OR vEmail LIKE '% ".$this->txtEmployee."'  

							OR vEmail LIKE '%".$this->txtEmployee."%'

							OR vCode LIKE '".$this->txtEmployee." %' OR vCode LIKE '% ".$this->txtEmployee."'

							OR vCode LIKE '%".$this->txtEmployee."%')";

		}//end if

		else

		{

			$extraCheck = " ";

		}//end else



		$target = '.';

		$this->common_model->_paging($this->common_model->get_total_num_rows('EmployeeMaster','nEId',$extraCheck), $target,'employee/filterByEmployee2/'.$this->uri->segment(3));

		$data['list_value'] = $this->employee_model->getFilterEmployee($extraCheck);

		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('employee/employee_filter',$data);

	}//end function

	

	function filterByEmployee2()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = EMPMANAGEMENT;

		

		$this->common_model->check_admin_authorization();

		$this->txtEmployee = $this->common_model->searchKeyword('txtEmployee');

		

		if($this->input->post('btnSearch') == 'Go')

		{

			$this->session->unset_userdata('txtEmployee');

			$this->txtEmployee = $this->common_model->searchKeyword('txtEmployee');

		}//end if

		

		if(!empty($this->txtEmployee))

		{

			$extraCheck = " WHERE (vName LIKE '".$this->txtEmployee." %' OR vName LIKE '% ".$this->txtEmployee."'  

							OR vName LIKE '%".$this->txtEmployee."%' OR vEmail LIKE '".$this->txtEmployee." %' OR vEmail LIKE '% ".$this->txtEmployee."'  

							OR vEmail LIKE '%".$this->txtEmployee."%'

							OR vCode LIKE '".$this->txtEmployee." %' OR vCode LIKE '% ".$this->txtEmployee."'

							OR vCode LIKE '%".$this->txtEmployee."%')";

		}//end if

		else

		{

			$extraCheck = " ";

		}//end else



		$target = '.';

		$this->common_model->_paging($this->common_model->get_total_num_rows('EmployeeMaster','nEId',$extraCheck), $target,'employee/filterByEmployee2/'.$this->uri->segment(3));

		$data['list_value'] = $this->employee_model->getFilterEmployee($extraCheck);

		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('general/template_top',$data);

		$this->load->view('employee/employee');

		$this->load->view('general/template_bottom');

	}//end function



}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>
