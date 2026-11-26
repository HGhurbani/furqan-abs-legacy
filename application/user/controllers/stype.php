<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stype extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('stype_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('ServiceType','nSId'," "), $target,'service_type/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->stype_model->getAllStype();

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('service_type/type');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editStype()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Service Type', 'required');
				
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWSTYPE;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = $this->uri->segment(5);
			break;
			
			case "e":
				$this->showTitle  = EDITSTYPE;
				$this->btnVal	  = UPDATESTYPE;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nSId')) != 0)
				{
					$this->catId	  = intval($this->input->post('nSId'));
				}//end if
				
				$showVal	      = $this->stype_model->getCorresStype();
				$this->txtTitle	  = $showVal->vName;
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
			$this->load->view('service_type/edit_type');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nSId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nSId!='".$this->input->post('nSId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vName FROM {PRE}ServiceType WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = STYPENAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('service_type/edit_type');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->stype_model->insertStype();
					break;
					
					case "e":
						//Update into db
						$this->stype_model->updateStype();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delstype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stype_model->deleteStype();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stype_model->deleteAllStype();
	}//end function 
	
	function filterByStype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtStypename = $this->common_model->searchKeyword('stypename');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('stypename');
			$this->txtStypename = $this->common_model->searchKeyword('stypename');
		}//end if
		
		if(!empty($this->txtStypename))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtStypename." %' OR vName LIKE '% ".$this->txtStypename."'  
							OR vName LIKE '%".$this->txtStypename."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('ServiceType','nSId', $extraCheck), $target,'service_type/filterByStype2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->stype_model->getFilterStypeName($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('service_type/type_filter',$data);
	}//end function
	
	function filterByStype2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtStypename = $this->common_model->searchKeyword('stypename');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('stypename');
			$this->txtStypename = $this->common_model->searchKeyword('stypename');
		}//end if
		
		if(!empty($this->txtStypename))
		{
			$extraCheck = " AND (vName LIKE '".$this->txtStypename." %' OR vName LIKE '% ".$this->txtStypename."'  
							OR vName LIKE '%".$this->txtStypename."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('ServiceType','nSId', $extraCheck), $target,'service_type/filterByStype2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->stype_model->getFilterStypeName($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('service_type/type');
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
				$extraCond = " AND nSId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('ServiceType','vName'," WHERE  vName='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
		if($txtTitle    == $this->txtTitle)
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