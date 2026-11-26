<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main_group extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MAINGROUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target          = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('FinanceModule','nId'," WHERE nParent ='0'"), 
										$target,'main_group/showlist/'.$this->uri->segment(3));
        $data['list_main_group']= $this->finance_model->getSelectMaingroups();
		$data['list_value']     = $this->finance_model->getAllMaingroups();
        $data['list_div_value']	= $this->finance_model->getActiveDivision();
        $data['list_loc_value'] = $this->finance_model->filterLocation($this->ddlDivision);
		$this->count_rows       = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('finance/main_group');
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->finance_model->filterLocation($this->ddlDivision);
        $this->load->view('finance/show_location',$data);
    }//end function

	function editMaingroups()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	        = $this->common_model->get_site_title();
        $data['list_div_value']	= $this->finance_model->getActiveDivision();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtAccGroupTitle', 'Main Group', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("main_group/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//select main group if already exist		
		$data['list_main_group']  = $this->finance_model->getSelectMaingroups();
		$data['subActive']        = $this->finance_model->getSelectActiveSub();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  	 	= ADDNEWMAINGROUP;
				$this->btnVal	  	 	= ADDCONTINUE;
				$this->btnVal2	  	 	= SAVEEXIT;
				$this->ddlMaingroup	  	= $this->input->post('ddlMaingroup');
				$this->txtAccGroupTitle	= $this->input->post('txtAccGroupTitle');
				$this->txtCode	  	 	= $this->input->post('txtCode');
				$this->chkValYes  	 	= 'checked="checked"';
				$this->chkValNo	  	 	= '';
				
				$this->catId	     	= '';
			break;
			
			case "e":
				$this->showTitle  	 	= EDITMAINGROUP;
				$this->btnVal	  	 	= UPDATEMAINGROUP;
				$this->catId	  	 	= $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  	 	= intval($this->input->post('nId'));
				}//end if
				$this->selectedSubGpID  = $this->uri->segment(6);
				$showVal	      	 	= $this->finance_model->getCorresMaingroups();
				$this->ddlMaingroup	  	= $showVal->vTitle;
				$showVal1	      	 	= $this->finance_model->getCorresAccgroups();
				$this->txtAccGroupTitle = $showVal1->vTitle;
				$this->txtCode	  	 	= $showVal1->vCode;

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
			$this->load->view('finance/edit_maingroup');
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
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}FinanceModule WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' AND nParent='0'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = MAINGROUPEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('finance/edit_maingroup');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->finance_model->insertMaingroup();
					break;
					
					case "e":
						//Update into db
						$this->finance_model->updateMaingroup();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delmaingroup()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MAINGROUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->finance_model->deleteMaingroup();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MAINGROUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->finance_model->deleteAllMaingroup();
	}//end function 
	
	function filterByMaingroup()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MAINGROUPMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('ddlMainGroup');
			$this->ddlMainGroup = $this->common_model->searchKeyword('ddlMainGroup');
			//$this->txtSubGroup  = $this->common_model->searchKeyword('txtSubGroup');
		}//end if
		
		if(!empty($this->ddlMainGroup))
		{
			$extraCheck = " AND (vTitle LIKE '".$this->ddlMainGroup." %' OR vTitle LIKE '% ".$this->ddlMainGroup."'
							OR vTitle LIKE '%".$this->ddlMainGroup."%')";
		}//end if
        /*if(!empty($this->txtSubGroup))
		{
			$extraCheck .= " AND (vCode LIKE '".$this->txtSubGroup." %' OR vCode LIKE '% ".$this->txtSubGroup."'
							OR vCode LIKE '%".$this->txtSubGroup."%' OR vTitle LIKE '".$this->txtSubGroup." %' OR
							vTitle LIKE '% ".$this->txtSubGroup."'	OR vTitle LIKE '%".$this->txtSubGroup."%')
							AND vLevel='2'";
		}//end if*/
		else
		{
			$extraCheck = " ";
		}//end else
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('FinanceModule','nId', " WHERE nParent ='0'".$extraCheck), $target,'main_group/filterByMaingroup2/'.$this->uri->segment(3));
		$data['list_value'] = $this->finance_model->getFilterMaingroup($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('finance/maingroup_filter',$data);
	}//end function
	
	function filterByMaingroup2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = MAINGROUPMANAGEMENT;
		
		$this->common_model->check_admin_authorization();

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('ddlMainGroup');
            $this->ddlMainGroup = $this->common_model->searchKeyword('ddlMainGroup');
            //$this->txtSubGroup  = $this->common_model->searchKeyword('txtSubGroup');
        }//end if

        if(!empty($this->ddlMainGroup))
        {
            $extraCheck = " AND (vTitle LIKE '".$this->ddlMainGroup." %' OR vTitle LIKE '% ".$this->ddlMainGroup."'
							OR vTitle LIKE '%".$this->ddlMainGroup."%')";
        }//end if
        /*if(!empty($this->txtSubGroup))
        {
            $extraCheck .= " AND (vCode LIKE '".$this->txtSubGroup." %' OR vCode LIKE '% ".$this->txtSubGroup."'
							OR vCode LIKE '%".$this->txtSubGroup."%' OR vTitle LIKE '".$this->txtSubGroup." %' OR
							vTitle LIKE '% ".$this->txtSubGroup."'	OR vTitle LIKE '%".$this->txtSubGroup."%')
							AND vLevel='2'";
        }//end if*/
		else
		{
			$extraCheck = " ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('FinanceModule','nId'," WHERE nParent ='0'".$extraCheck), $target,'main_group/filterByMaingroup2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->finance_model->getFilterMaingroup($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('finance/main_group');
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
		$txtTitle       	    = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." AND nParent='0'");
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
		$this->txtGCode 		= $this->input->post('txtGCode');
		$txtGCode       	    = $this->common_model->get_field_name('FinanceModule','vCode'," WHERE  vCode='".$this->txtGCode."' ".$extraCond." AND nParent='0'");
		if($txtGCode    == $this->txtGCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function checkAccTitle()
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
		$this->txtAccGroupTitle = $this->input->post('txtAccGroupTitle');
		$txtAccGroupTitle       = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." AND nParent!='0' AND vLevel = '2'");
		if($txtAccGroupTitle == $this->txtAccGroupTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function checkSubCode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nParent != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode	 		= $this->input->post('txtCode');
		$txtCode	       	    = $this->common_model->get_field_name('FinanceModule','vCode'," WHERE  vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." AND nParent!='0' AND vLevel = '2'");
		if($txtCode == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->finance_model->deleteSubAndDetails($this->subDetID);
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
