<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Services extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('service_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SERVICEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		if($this->uri->segment('5') != '')
			$this->ddlService = $this->common_model->searchKeyword33('5');
			
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlService = $this->common_model->searchKeyword33('3');
			
		else
			$this->ddlService = $this->common_model->searchKeyword3('ddlService');
			
		$this->ddlService = (trim($this->uri->segment(3)) == '')?0:$this->ddlService;
		
		
		switch($this->ddlService)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlService');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlService');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 default:
					$extraCheck = " WHERE nService ='".$this->ddlService."'";
			 break;
		}//end switch

		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Services','nServId',$extraCheck), $target,'services/showlist/'.$this->uri->segment(3));
		
		
		$data['list_value']   = $this->service_model->getServices($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('services/services');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editServices()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      = SERVICEMANAGEMENT;
		$data['title'] 	      = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Service Name', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("services/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle    = ADDNEWSERVICES;
				$this->btnVal	    = ADDCONTINUE;
				$this->btnVal2	    = SAVEEXIT;
				$this->txtCode	    = $this->input->post('txtCode');
				$this->txtTitle	    = $this->input->post('txtTitle');
				$this->chkValYes    = 'checked="checked"';
				$this->chkValNo	    = '';
				$this->catId	    = '';
			break;
			
			case "e":
				$this->showTitle  = EDITSERVICES;
				$this->btnVal	  = UPDATESERVICES;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
				$showVal	       = $this->service_model->getCorresServices();
				$this->txtCode	   = $showVal->vCode;
				$this->txtTitle	   = $showVal->vTitle;

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
			$this->load->view('services/edit_services');
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
					$this->extraCond  = " AND nServId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Services WHERE vTitle = '".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = SERVICENAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('services/edit_services');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->service_model->insertServices();
					break;
					
					case "e":
						//update into db
						$this->service_model->updateServices();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delservices()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SERVICEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->service_model->deleteServices();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SERVICEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->service_model->deleteServiceMultiple();
	}//end function 
	
	function filtershowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SERVICEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtServicename');
			$this->txtServicename = $this->common_model->searchKeyword('txtServicename');
		}//end if
		if(!empty($this->txtServicename))
		{
			$extraCheck = " WHERE (vTitle LIKE '".$this->txtServicename." %' OR vTitle LIKE '% ".$this->txtServicename."'  
							OR vTitle LIKE '%".$this->txtServicename."%' OR vCode LIKE '".$this->txtServicename." %' 
							OR vCode LIKE '% ".$this->txtServicename."' OR vCode LIKE '%".$this->txtServicename."%')";
		}//end if
	
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Services','nServId',$extraCheck), $target,'services/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->service_model->getFilterServices($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('services/service_filter',$data);
	}//end function 
	
	function filtershowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SERVICEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtServicename');
			$this->txtServicename = $this->common_model->searchKeyword('txtServicename');
		}//end if
		if(!empty($this->txtServicename))
		{
			$extraCheck = " WHERE (vTitle LIKE '".$this->txtServicename." %' OR vTitle LIKE '% ".$this->txtServicename."'  
							OR vTitle LIKE '%".$this->txtServicename."%' OR vCode LIKE '".$this->txtServicename." %' 
							OR vCode LIKE '% ".$this->txtServicename."' OR vCode LIKE '%".$this->txtServicename."%')";
		}//end if
	
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Services','nServId',$extraCheck), $target,'services/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->service_model->getFilterServices($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'meal_type/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('services/services');
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
				$extraCond = " AND nServId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Services','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
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
				$extraCond = " AND nServId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 		= $this->input->post('txtCode');
		$txtCode       	    = $this->common_model->get_field_name('Services','vCode'," WHERE  vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond."");
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
