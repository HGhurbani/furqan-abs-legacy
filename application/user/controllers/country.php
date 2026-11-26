<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Country extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('country_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = COUNTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Country','nId'," WHERE nParentId ='0'"), $target,'country/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->country_model->getAllCountry();

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'country/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('country/country');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCountry()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Country Name', 'required');
				
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'country/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("country/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWCOUNTRY;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->txtTitle2  = $this->input->post('txtTitle2');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = $this->uri->segment(5);
			break;
			
			case "e":
				$this->showTitle  = EDITCOUNTRY;
				$this->btnVal	  = UPDATECOUNTRY;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	      = $this->country_model->getCorresCountry();
				$this->txtTitle	  = $showVal->vName;
				$this->txtTitle2  = $showVal->vShortName;
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
			$this->load->view('country/edit_country');
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
			$chkTitle = $this->db->query("SELECT vName FROM {PRE}Country WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = COUNTRYNAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('country/edit_country');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->country_model->insertCountry();
					break;
					
					case "e":
						//Update into db
						$this->country_model->updateCountry();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delcountry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = COUNTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->country_model->deleteCountry();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = COUNTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->country_model->deleteAllCountry();
	}//end function 
	
	function filterByCountry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = COUNTRYMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtCountryname = $this->common_model->searchKeyword('countryname');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('countryname');
			$this->txtCountryname = $this->common_model->searchKeyword('countryname');
		}//end if
		
		if(!empty($this->txtCountryname))
		{
			$extraCheck= " AND (vName LIKE '".$this->txtCountryname." %' OR vName LIKE '% ".$this->txtCountryname."'  
							OR vName LIKE '%".$this->txtCountryname."%'
							OR vShortName LIKE '".$this->txtCountryname." %' OR vShortName LIKE '% ".$this->txtCountryname."'
							OR vShortName LIKE '%".$this->txtCountryname."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Country','nId', " WHERE nParentId ='0'".$extraCheck), $target,'country/filterByCountry2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->country_model->getFilterCountryName($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'country/showlist');

		$this->load->view('country/country_filter',$data);
	}//end function
	
	function filterByCountry2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = COUNTRYMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtCountryname = $this->common_model->searchKeyword('countryname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('countryname');
			$this->txtCountryname = $this->common_model->searchKeyword('countryname');
		}//end if
		
		if(!empty($this->txtCountryname))
		{
			$extraCheck = " AND (vName LIKE '".$this->txtCountryname." %' OR vName LIKE '% ".$this->txtCountryname."'  
							OR vName LIKE '%".$this->txtCountryname."%'
							OR vShortName LIKE '".$this->txtCountryname." %' OR vShortName LIKE '% ".$this->txtCountryname."'
							OR vShortName LIKE '%".$this->txtCountryname."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Country','nId'," WHERE nParentId ='0'".$extraCheck), $target,'country/filterByCountry2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->country_model->getFilterCountryName($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'country/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('country/country');
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
		$txtTitle       	    = $this->common_model->get_field_name('Country','vName'," WHERE  vName='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
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
