<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employee_designation extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = EMPDESMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Employee','nId'," WHERE vType='DES'"), $target,'employee_designation/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->employee_model->getAllEmployeedes();
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('employee/employee_designation');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editEmployeeDes()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Title', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("employee_designation/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   = ADDNEWEMPDES;
				$this->btnVal	   = ADDCONTINUE;
				$this->btnVal2	   = SAVEEXIT;
				$this->txtTitle	   = $this->input->post('txtTitle');
				$this->txtCode     = $this->input->post('txtCode');
				$this->chkValYes   = 'checked="checked"';
				$this->chkValNo	   = '';
				$this->catId	   = '';
			break;
			
			case "e":
				$this->showTitle   = EDITEMPDES;
				$this->btnVal	   = UPDATEEMPDES;
				$this->catId	   = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
				
				$showVal	       = $this->employee_model->getCorresEmpDes();
				$this->txtTitle	   = $showVal->vTitle;
				$this->txtCode 	   = $showVal->vCode;
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
			$this->load->view('employee/edit_employeedesignation');
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
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Employee WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' AND vType='DES' ".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = CUSTAREAEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('employee/edit_employeedesignation');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->employee_model->insertEmployeedes();
					break;
					
					case "e":
						//Update into db
						$this->employee_model->updateEmployeedes();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delemployeedes()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = EMPDESMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->employee_model->deleteEmployeedes();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = EMPDESMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->employee_model->deleteAllEmployeedes();
	}//end function 
	
	function filterByEmployeedes()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = EMPDESMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtEmployeedes = $this->common_model->searchKeyword('txtEmployeedes');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtEmployeedes');
			$this->txtEmployeedes = $this->common_model->searchKeyword('txtEmployeedes');
		}//end if
		
		if(!empty($this->txtEmployeedes))
		{
			$extraCheck= " AND (vTitle LIKE '".$this->txtEmployeedes." %' OR vTitle LIKE '% ".$this->txtEmployeedes."'  
							OR vTitle LIKE '%".$this->txtEmployeedes."%'
							OR vCode LIKE '".$this->txtEmployeedes." %' OR vCode LIKE '% ".$this->txtEmployeedes."'
							OR vCode LIKE '%".$this->txtEmployeedes."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Employee','nId'," WHERE vType='DES'".$extraCheck), $target,'employee_designation/filterByEmployeedes2/'.$this->uri->segment(3));
		$data['list_value'] = $this->employee_model->getFilterEmployeedes($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('employee/employeedesignation_filter',$data);
	}//end function
	
	function filterByEmployeedes2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = EMPDESMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtEmployeedes = $this->common_model->searchKeyword('txtEmployeedes');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtEmployeedes');
			$this->txtEmployeedes = $this->common_model->searchKeyword('txtEmployeedes');
		}//end if
		
		if(!empty($this->txtEmployeedes))
		{
			$extraCheck= " AND (vTitle LIKE '".$this->txtEmployeedes." %' OR vTitle LIKE '% ".$this->txtEmployeedes."'  
							OR vTitle LIKE '%".$this->txtEmployeedes."%'
							OR vCode LIKE '".$this->txtEmployeedes." %' OR vCode LIKE '% ".$this->txtEmployeedes."'
							OR vCode LIKE '%".$this->txtEmployeedes."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Employee','nId'," WHERE vType='DES'".$extraCheck), $target,'employee_designation/filterByEmployeedes2/'.$this->uri->segment(3));
		$data['list_value'] = $this->employee_model->getFilterEmployeedes($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'employee_designation/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('employee/employee_designation');
		$this->load->view('general/template_bottom');
	}//end function

	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Employee','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' AND vType='DES' ".$extraCond." ");
		if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

	function checkCode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 		= $this->input->post('txtCode');
		$txtCode       	    = $this->common_model->get_field_name('Employee','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' AND vType='DES' ".$extraCond." ");
		if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function position()
	{
		$this->load->helper('url');
		//$this->load->database();
		$oldId             = $this->uri->segment(5);
		$oldPosition       = $this->uri->segment(6);
		$table             = "{PRE}Employee";
		$PositionfieldName = 'nPosition'; //db field name
		$IdfieldName       = 'nId'; //db field name
		if($this->uri->segment(4)=='up')
		{
			$this->common_model->OrderUp($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName);
		}//end if
	
		if($this->uri->segment(4)=='down')
		{
			$this->common_model->OrderDown($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName);
		}//end if
		redirect(site_url("employee_designation/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
