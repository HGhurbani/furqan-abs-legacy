<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_role extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERACCESSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		if($this->uri->segment('5') != '')
			$this->ddlType = $this->common_model->searchKeyword33('5');
			
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlType = $this->common_model->searchKeyword33('3');
			
		else
			$this->ddlType = $this->common_model->searchKeyword3('ddlType');
			
		$this->ddlType = (trim($this->uri->segment(3)) == '')?0:$this->ddlType;
		
		switch($this->ddlType)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlType');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlType');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 default:
					$extraCheck = "";
			 break;
		}//end switch

		//get active user profile
		$data['list_userprofile']	= $this->user_model->getDropUserprofile();

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		//site Name
		$target = '.';
		$this->common_model->_paging($this->user_model->getAllUserRoleCount($extraCheck), $target,'user_role/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->user_model->getAllUserrole($extraCheck);
		$this->count_rows     = count($data['list_value']);

		$this->load->view('general/template_top',$data);
		$this->load->view('user/user_role');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editUserrole()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtUserpro', 'User Profile', 'required');
		//get active transaction type
		$data['list_userprofile']	= $this->user_model->getDropUserProfile();
		$data['list_useraccess']	= $this->user_model->getDropUserAccess();
		$data['trans_cat_value']    = array_filter($this->user_model->getAllActiveTransactionTypeCat());
		
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWACCESS;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
				$data['chkTransType']       = array();
			break;
			
			case "e":
				$this->showTitle  = EDITUSERACCESS;
				$this->btnVal	  = UPDATEUSERACCESS;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          = $this->user_model->getCorresUserrole();
				$this->txtUserpro	  = $showVal->vProfile;
				$this->ddlUseraccess  = 'F';
				/*$data['chkTransType'] = $this->user_model->getSelectedModules();
				$data['chkTrans']     = $this->user_model->getSelectedTransactions();
				$data['chkUAccess']   = $this->user_model->getSelectedTransAccess();
				$data['chkUAccessID'] = $this->user_model->getSelectedTransAccessID();*/
				
				//set session value
				if(!empty($data['chkTrans']))
					$this->session->set_userdata('chkTrans',@join(",",$data['chkTrans']));
					
				if(!empty($data['chkTransType']))
					$this->session->set_userdata('chkTransType',@join(",",$data['chkTransType']));
					
				if(!empty($data['chkUAccessID']))
					$this->session->set_userdata('chkUAccessID',@join(",",$data['chkUAccessID']));
		
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
			$this->load->view('user/edit_userrole');
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

/*			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vProfile FROM {PRE}User_Module_Access WHERE vProfile='".mysql_real_escape_string($this->input->post('txtUserpro'))."' ".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = USERROLEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('user/edit_userrole');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
*/				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->user_model->insertUserrole();
					break;
					
					case "e":
						//Update into db
						$this->user_model->updateUserrole();
					break;
				}//end switch
				redirect(site_url("user_role/showlist/".$this->uri->segment(3).'/'.$msgVal));
			//}//end else
		}//end else	
	}//end function

	function deluserrole()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERACCESSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->user_model->deleteUserrole();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERACCESSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->user_model->deleteAllUserrole();
	}//end function 

	function filterByUserrole()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERACCESSMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtUserAccess');
			$this->txtUserAccess = $this->common_model->searchKeyword('txtUserAccess');
		}//end if
		
		if(!empty($this->txtUserAccess))
		{
			$extraCheck= " AND (up.vTitle LIKE '".$this->txtUserAccess." %' OR up.vTitle LIKE '% ".$this->txtUserAccess."'  
							OR up.vTitle LIKE '%".$this->txtUserAccess."%'
							OR t.vTitle LIKE '".$this->txtUserAccess." %' OR t.vTitle LIKE '% ".$this->txtUserAccess."'
							OR t.vTitle LIKE '%".$this->txtUserAccess."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		$countFilterAccess	=  $this->user_model->getCountAccessfilter($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterAccess, $target,'user_role/filterByUserrole2/'.$this->uri->segment(3));
		$data['list_value'] = $this->user_model->getFilterUseraccess($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('user/user_role_filter',$data);
	}//end function
	
	function filterByUserrole2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = USERROLEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtUserAccess');
			$this->txtUserAccess = $this->common_model->searchKeyword('txtUserAccess');
		}//end if
		
		if(!empty($this->txtUserAccess))
		{
			$extraCheck= " AND (up.vTitle LIKE '".$this->txtUser." %' OR up.vTitle LIKE '% ".$this->txtUser."'  
							OR up.vTitle LIKE '%".$this->txtUser."%'
							OR t.vTitle LIKE '".$this->txtUser." %' OR t.vTitle LIKE '% ".$this->txtDivision."'
							OR t.vTitle LIKE '%".$this->txtUser."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		//$countFilterAccess	=  $this->user_model->getCountfilter($extraCheck);
		$countFilterAccess	=  $this->user_model->getCountAccessfilter($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterAccess, $target,'user_role/filterByUserrole2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->user_model->getFilterUseraccess($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('user/user_role');
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
		$this->txtTitle 		= $this->input->post('txtUserpro');
		$txtTitle       	    = $this->common_model->get_field_name('User_Module_Access','vProfile'," WHERE  vProfile='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
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
		$table             = "{PRE}UserRole";
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
		redirect(site_url("user_role/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
	
	function viewUserrole()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		echo $this->user_model->showDetails();
	}//end function

	//function for showing module list
	function modulecategory()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		//set session for transaction list value
		$this->session->set_userdata('chkTrans',$this->input->post('chkdModuleList'));
		
		$data['chkTransType']     = @split("[,]",$this->session->userdata('chkTransType'));
		$data['trans_cat_value']  = array_filter($this->user_model->getAllActiveTransactionTypeCat());

		$this->load->view('user/transaction_type_category',$data);
	}//end function
	
	//function for showing module list under selected category
	function modulelist()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$this->chkTypeId          = $this->input->post('chkdModules');
		$this->ddlUserAccess      = $this->input->post('ddlUserAccess');
		$this->catId              = $this->input->post('nChkIDm');
		
		//set session for transaction category value
		$this->session->set_userdata('chkTransType',$this->chkTypeId);
		
		
		$data['chkTrans']         = @split("[,]",$this->session->userdata('chkTrans'));
		$data['trans_list_value'] = array_filter($this->user_model->getAllActiveTransactionList($this->chkTypeId));
		$data['list_user_access'] = $this->user_model->getDropUserAccess();
		$data['chkUAccess']       = $this->user_model->getSelectedTransAccess();

		$this->load->view('user/transaction_type',$data);
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>