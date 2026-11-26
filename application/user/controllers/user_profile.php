<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_profile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERROLEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('UserProfile','nId'," "), $target,'user_profile/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->user_model->getAllUserprofile();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('user/user_profile');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editUserprofile()
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
				$this->showTitle  = ADDNEWUSERROLE;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITUSERROLE;
				$this->btnVal	  = UPDATEUSERROLE;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	      = $this->user_model->getCorresUserprofile();
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
			$this->load->view('user/edit_userprofile');
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
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}UserProfile WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' ".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = USERROLEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('user/edit_userprofile');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->user_model->insertUserprofile();
					break;
					
					case "e":
						//Update into db
						$this->user_model->updateUserprofile();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function deluserprofile()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERPROMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->user_model->deleteUserprofile();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERROLEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->user_model->deleteAllUserprofile();
	}//end function 
	
	function filterByUserprofile()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERROLEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtUserpro');
			$this->txtUserpro = $this->common_model->searchKeyword('txtUserpro');
		}//end if
		
		if(!empty($this->txtUserpro))
		{
			$extraCheck= " WHERE vTitle LIKE '".$this->txtUserpro."%'";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('UserProfile','nId',$extraCheck), $target,'user_profile/filterByUserprofile2/'.$this->uri->segment(3));
		$data['list_value'] = $this->user_model->getFilterUserprofile($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('user/userprofile_filter',$data);
	}//end function
	
	function filterByUserprofile2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERROLEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtUserpro');
			$this->txtUserpro = $this->common_model->searchKeyword('txtUserpro');
		}//end if
		
		if(!empty($this->txtUserpro))
		{
			$extraCheck= " WHERE vTitle LIKE '".$this->txtUserpro."%'";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('UserProfile','nId',$extraCheck), $target,'user_profile/filterByUserprofile2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->user_model->getFilterUserprofile($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('user/user_profile');
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
		$txtTitle       	    = $this->common_model->get_field_name('UserProfile','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
		if($txtTitle    == $this->txtTitle)
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
		$this->load->database();
		$oldId             = $this->uri->segment(5);
		$oldPosition       = $this->uri->segment(6);
		$table             = "{PRE}UserProfile";
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
		redirect(site_url("user_profile/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>