<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Meal_type extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('meal_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MEALTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Meals','nMId',$extraCheck), $target,'meal_type/showlist/'.$this->uri->segment(3));
		
		$data['list_value']   = $this->meal_model->getMeals($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('meal_type/meal_type');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editMealtype()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      = MEALTYPEMANAGEMENT;
		$data['title'] 	      = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtName', 'Meal Name', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("meal_type/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle    = ADDNEWMEALTYPE;
				$this->btnVal	    = ADDCONTINUE;
				$this->btnVal2	    = SAVEEXIT;
				$this->txtCode	    = $this->input->post('txtCode');
				$this->txtName	    = $this->input->post('txtName');
				$this->chkValYes    = 'checked="checked"';
				$this->chkValNo	    = '';
				$this->catId	    = '';
			break;
			
			case "e":
				$this->showTitle  = EDITMEALTYPE;
				$this->btnVal	  = UPDATEMEALTYPE;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				$showVal	      = $this->meal_model->getCorresMeals();
				$this->txtCode	  = $showVal->vCode;
				$this->txtName	  = $showVal->vName;

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
			$this->load->view('meal_type/edit_meal_type');
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
					$this->extraCond  = " AND nMId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			
			$chkCode = $this->db->query("SELECT vCode FROM {PRE}Meals WHERE vCode = 
											'".mysql_real_escape_string($this->input->post('txtCode'))."'".$this->extraCond);
			if ($chkCode->num_rows() > 0)
			{
				$data['error_value'] = MEALCODEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('meal_type/edit_meal_type');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
			
				$chkTitle = $this->db->query("SELECT vName FROM {PRE}Meals WHERE 
											vName='".mysql_real_escape_string($this->input->post('txtName'))."'".$this->extraCond);
				if ($chkTitle->num_rows() > 0)
				{
					$data['error_value'] = MEALNAMEEXIST;
					$this->load->view('general/template_top',$data);
					$this->load->view('meal_type/edit_meal_type');
					$this->load->view('general/template_bottom');
				}//end if
				else
				{
					//checking segment
					switch($this->uri->segment(4))
					{
						case "a":
							//insert into db
							$this->meal_model->insertMeals();
							
						break;
						
						case "e":
							//update into db
							$this->meal_model->updateMeals();
							
						break;
					}//end switch
				}//end else
			}//end else
		}//end else	
	}//end function

	function delMeals()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MEALTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->meal_model->deleteMeals();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MEALTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->meal_model->deleteMealsMultiple();
	}//end function 
	
	function filtershowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MEALTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtMealName');
			$this->txtMealName = $this->common_model->searchKeyword('txtMealName');
		}//end if
		
		if(!empty($this->txtMealName))
		{
			$extraCheck = " WHERE (vName LIKE '".$this->txtMealName." %' OR vName LIKE '% ".$this->txtMealName."'  
							OR vName LIKE '%".$this->txtMealName."%'
							OR vCode LIKE '".$this->txtMealName." %' OR vCode LIKE '% ".$this->txtMealName."'
							OR vCode LIKE '%".$this->txtMealName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Meals','nMId',$extraCheck), $target,'meal_type/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->meal_model->getFilterMeals($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('meal_type/meal_type_filter',$data);
	}//end function 
	
	function filtershowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MEALTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtMealName');
			$this->txtMealName = $this->common_model->searchKeyword('txtMealName');
		}//end if
		
		if(!empty($this->txtMealName))
		{
			$extraCheck = " WHERE (vName LIKE '".$this->txtMealName." %' OR vName LIKE '% ".$this->txtMealName."'  
							OR vName LIKE '%".$this->txtMealName."%'
							OR vCode LIKE '".$this->txtMealName." %' OR vCode LIKE '% ".$this->txtMealName."'
							OR vCode LIKE '%".$this->txtMealName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Meals','nMId',$extraCheck), $target,'meal_type/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->meal_model->getFilterMeals($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('meal_type/meal_type');
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
				$extraCond = " AND nMId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtName 		= $this->input->post('txtName');
		$txtName       	    = $this->common_model->get_field_name('Meals','vName'," WHERE  vName='".mysql_real_escape_string($this->txtName)."' ".$extraCond."");
		if($txtName    == $this->txtName)
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
				$extraCond = " AND nMId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 		= $this->input->post('txtCode');
		$txtCode       	    = $this->common_model->get_field_name('Meals','vCode'," WHERE  vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond."");
		if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
