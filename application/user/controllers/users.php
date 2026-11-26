<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {



	function __construct()

	{

		parent::__construct();

		$this->load->model('user_model');

	}



	function showlist()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = USERSMANAGEMENT;

		$this->common_model->check_admin_authorization();

		//site Name

		$data['list_type_value']  = $this->user_model->getAllUserType();		

		$target = '.';

		$this->common_model->_paging($this->common_model->get_total_num_rows('Members','nId'," "), $target,'users/showlist/'.$this->uri->segment(3));

		$data['list_value']       = $this->user_model->getAllMembers();

		$this->count_rows         = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);

		$this->load->view('user/users');

		$this->load->view('general/template_bottom');

	}//end function

	

	function editUsers()

	{

		$this->common_model->check_admin_authorization();

		//site Name

		$data['title'] 	   = $this->common_model->get_site_title(); 

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('txtUsercode', 'Code', 'required');

		$data['list_user_pro']    = $this->user_model->getDropUserProfile();

		$data['list_loc_value']   = $this->user_model->getAllLocations();

		

		switch($this->uri->segment(4))

		{

			case "a":

				$this->showTitle    	= ADDNEWUSERS;

				$this->btnVal	    	= ADDCONTINUE;

				$this->btnVal2	    	= SAVEEXIT;

				$data['list_user_name'] = $this->user_model->getAllUser();



				$this->txtUsercode  	= $this->input->post('txtUsercode');

				$this->txtName	    	= $this->input->post('txtName');

				$this->txtUPassword 	= $this->input->post('txtUPassword');

				$this->txtCPassword 	= $this->input->post('txtCPassword');

				$this->ddlUserpro   	= $this->input->post('ddlUserpro');

				$this->ddlLocation  	= $this->input->post('ddlLocation');

				$this->chkValYes    	= 'checked="checked"';

				$this->chkValNo	    	= '';

				$this->chkValTypeC  	= '';

				$this->chkValTypeE		= 'checked="checked"';

				$this->chkValTypeA  	= '';

				$this->chkValTypeV		= '';

				$this->chkValTypeO		= '';

				$this->catId	    	= '';

			break;

			

			case "e":

				$this->showTitle    	= EDITUSERS;

				$this->btnVal	    	= UPDATEUSERS;

				$this->catId	    	= $this->uri->segment(5);

				if(intval($this->input->post('nId')) != 0)

				{

					$this->catId    	= intval($this->input->post('nId'));

				}//end if

		        $data['list_user_name'] = $this->user_model->getCorrUser();

				

				$showVal	        	= $this->user_model->getCorresUsers();

				$this->ddlUsername	    = $showVal->nUser; 

				$this->ddlUserpro   	= $showVal->nUserPro;

				$this->ddlLocation  	= @split(',',$showVal->vLocation);

				//get each user code and email details				

				$showDetails      	    = $this->user_model->getCorresUserDetails($this->ddlUsername,$showVal->vType);

				$this->txtUsercode		= $showDetails->vCode;

				$this->txtEmail			= $showDetails->vEmail;

				$this->uTypeVal         = $showVal->vType;

				

				if($showVal->vType == 'C')

				{

					$this->chkValTypeC  = 'checked="checked"';

				}//end if 

				if($showVal->vType == 'E')

				{

					$this->chkValTypeE   = 'checked="checked"';

				}//end if

				if($showVal->vType == 'V')

				{

					$this->chkValTypeV   = 'checked="checked"';

				}//end if

				if($showVal->vType == 'A')

				{

					$this->chkValTypeA   = 'checked="checked"';

				}//end if

				if($showVal->vType == 'O')

				{

					$this->chkValTypeO   = 'checked="checked"';

				}//end if

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

			$this->load->view('user/edit_users');

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

					$this->user_model->insertUsers();

				break;

				

				case "e":

					//Update into db

					$this->user_model->updateUsers();

				break;

			}//end switch

		}//end else	

	}//end function



	function delusers()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = USERSMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->user_model->deleteUsers();

	}//end function 

	

	function delrecords()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = USERSMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->user_model->deleteAllUsers();

	}//end function

	 

	function generateUsers()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		

		$this->radUser 	        = $this->input->post('radType');

		$this->ddlUsername		= $this->input->post('ddlUser');

		$data['list_user_name'] = $this->user_model->getUsers($this->radUser);

		$this->load->view('user/generate_user',$data);

	}//end function

	

	function getUserdetails()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		

		$this->radUser 	   = $this->input->post('radUser');		

		$this->ddlUsername = $this->input->post('ddlUsername');

		

		$this->txtUsercode = $this->input->post('txtUsercode');		

		$this->txtEmail    = $this->input->post('txtEmail');

	

		switch($this->radUser)

		{

			case "C" :

				$this->txtUsercode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->ddlUsername."'");

				$this->txtEmail    = $this->common_model->get_field_name('CustomerMaster','vEmail'," WHERE nCId='".$this->ddlUsername."'");

			break;

			

			case "E" :

				$this->txtUsercode = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$this->ddlUsername."'");

				$this->txtEmail    = $this->common_model->get_field_name('EmployeeMaster','vEmail'," WHERE nEId='".$this->ddlUsername."'");				

			break;

			

			case "V" :

				$this->txtUsercode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->ddlUsername."'");

				$this->txtEmail    = $this->common_model->get_field_name('VendorMaster','vEmail'," WHERE nVId='".$this->ddlUsername."'");				

			break;

			

			case "A" :

				$this->txtUsercode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->ddlUsername."'");

				$this->txtEmail    = $this->common_model->get_field_name('Agent','vEmail'," WHERE nAId='".$this->ddlUsername."'");				

			break;

			

			case "O" :

				$this->txtUsercode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->ddlUsername."'");

				$this->txtEmail    = $this->common_model->get_field_name('Other','vEmail'," WHERE nOId='".$this->ddlUsername."'");				

			break;

			

		}//end switch

		$this->load->view('user/generate_userdetails');

	}//end function



	function filterByUser()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = USERSMANAGEMENT;

		

		$this->common_model->check_admin_authorization();

		$this->txtUser = $this->common_model->searchKeyword('txtUser');

		$this->ddlType = $this->common_model->searchKeyword('ddlType');

		

		if($this->input->post('btnSearch') == 'Go')

		{

			$this->session->unset_userdata('txtUser');

			$this->txtUser = $this->common_model->searchKeyword('txtUser');

		}//end if

		

		if(!empty($this->txtUser))

		{

			$extraCheck = " WHERE (up.vTitle LIKE '".$this->txtUser." %' OR up.vTitle LIKE '% ".$this->txtUser."'

							OR up.vTitle LIKE '%".$this->txtUser."%' OR us.vName LIKE '".$this->txtUser." %' OR us.vName LIKE '% ".$this->txtUser."'

							OR us.vName LIKE '%".$this->txtUser."%' OR us.vEmail LIKE '".$this->txtUser." %' OR us.vEmail LIKE '% ".$this->txtUser."'

							OR us.vEmail LIKE '%".$this->txtUser."%' OR us.vCode LIKE '".$this->txtUser." %' OR us.vCode LIKE '% ".$this->txtUser."'

							OR us.vCode LIKE '%".$this->txtUser."%')";

		}//end if

		else

		{

			switch($this->ddlType)

			{

				case "0":

					$extraCheck .= " ";

				break;

				

				default:

					$extraCheck = " WHERE m.vType ='".$this->ddlType."'";

				break;

			}//end switch

		}//end else

		

		if(!empty($this->ddlType))

		{

			switch($this->ddlType)

			{

				 case "0":

						$extraCheck .= " WHERE m.vType ='".$this->ddlType."'";

				 break;

				   

				 default:

						$extraCheck .= " AND m.vType ='".$this->ddlType."'";

				 break;

			}//end switch

		}//end if

		

		$countFilterList	=  $this->user_model->getCountfilterList($extraCheck,$this->ddlType);

		$target 			= '.';

		$this->common_model->_paging($countFilterList, $target,'users/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->user_model->getFilterUser($extraCheck,$this->ddlType);

		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('user/users_filter',$data);

	}//end function

	

	function filterByUser2()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = USERSMANAGEMENT;

		

		$this->common_model->check_admin_authorization();

		$this->txtUser = $this->common_model->searchKeyword('txtUser');

		$this->ddlType = $this->common_model->searchKeyword('ddlType');

		

		if($this->input->post('btnSearch') == 'Go')

		{

			$this->session->unset_userdata('txtUser');

			$this->txtUser = $this->common_model->searchKeyword('txtUser');

		}//end if

		

		if(!empty($this->txtUser))

		{

			$extraCheck = " WHERE (up.vTitle LIKE '".$this->txtUser." %' OR up.vTitle LIKE '% ".$this->txtUser."'

							OR up.vTitle LIKE '%".$this->txtUser."%' OR us.vName LIKE '".$this->txtUser." %' OR us.vName LIKE '% ".$this->txtUser."'

							OR us.vName LIKE '%".$this->txtUser."%' OR us.vEmail LIKE '".$this->txtUser." %' OR us.vEmail LIKE '% ".$this->txtUser."'

							OR us.vEmail LIKE '%".$this->txtUser."%' OR us.vCode LIKE '".$this->txtUser." %' OR us.vCode LIKE '% ".$this->txtUser."'

							OR us.vCode LIKE '%".$this->txtUser."%')";

		}//end if

		else

		{

			switch($this->ddlType)

			{

				case "0":

					$extraCheck .= " ";

				break;

				

				default:

					$extraCheck = " WHERE m.vType ='".$this->ddlType."'";

				break;

			}//end switch

		}//end else

		

		if(!empty($this->ddlType))

		{

			switch($this->ddlType)

			{

				 case "0":

						$extraCheck .= " WHERE m.vType ='".$this->ddlType."'";

				 break;

				   

				 default:

						$extraCheck .= " AND m.vType ='".$this->ddlType."'";

				 break;

			}//end switch

		}//end if

		

		$countFilterList	=  $this->user_model->getCountfilterList($extraCheck,$this->ddlType);

		$target 			= '.';

		$this->common_model->_paging($countFilterList, $target,'users/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->user_model->getFilterUser($extraCheck,$this->ddlType);

		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);

		$this->load->view('user/users');

		$this->load->view('general/template_bottom');

	}//end function

	

}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>