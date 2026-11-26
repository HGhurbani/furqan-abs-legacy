<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Flags extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('travelsetup_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = FLAGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Flags','nId'," "), $target,'flags/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->travelsetup_model->getAllFlags();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('travel_setup/flags');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editFlags()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Title', 'required');
				
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWFLAG;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITFLAG;
				$this->btnVal	  = UPDATEFLAG;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	      = $this->travelsetup_model->getCorresFlag();
				$this->txtTitle	  = $showVal->vTitle;
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
			$this->load->view('travel_setup/edit_flags');
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
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Flags WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' ".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = FLAGEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('travel_setup/edit_flags');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->travelsetup_model->insertFlags();
					break;
					
					case "e":
						//Update into db
						$this->travelsetup_model->updateFlags();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delflags()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = FLAGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->travelsetup_model->deleteFlags();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = FLAGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->travelsetup_model->deleteAllFlags();
	}//end function 
	
	function filterByFlags()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = FLAGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtFlag');
			$this->txtFlag = $this->common_model->searchKeyword('txtFlag');
		}//end if
		
		if(!empty($this->txtFlag))
		{
			$extraCheck= " WHERE vTitle LIKE '".$this->txtFlag."%'";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Flags','nId',$extraCheck), $target,'flags/filterByFlags2/'.$this->uri->segment(3));
		$data['list_value'] = $this->travelsetup_model->getFilterFlags($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('travel_setup/flags_filter',$data);
	}//end function
	
	function filterByFlags2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = FLAGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtFlag');
			$this->txtFlag = $this->common_model->searchKeyword('txtFlag');
		}//end if
		
		if(!empty($this->txtFlag))
		{
			$extraCheck = " WHERE vTitle LIKE '".$this->txtFlag."%'";
		}//end if
		else
		{
			$extraCheck = " ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Flags','nId',$extraCheck), $target,'flags/filterByFlags2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->travelsetup_model->getFilterFlags($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('travel_setup/flags');
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
		$txtTitle       	    = $this->common_model->get_field_name('Flags','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
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