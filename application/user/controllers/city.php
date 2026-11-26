<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class City extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('country_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CITYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		if($this->uri->segment('5') != '')
			$this->ddlCountry = $this->common_model->searchKeyword33('5');
			
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlCountry = $this->common_model->searchKeyword33('3');
			
		else
			$this->ddlCountry = $this->common_model->searchKeyword3('ddlCountry');
			
		$this->ddlCountry = (trim($this->uri->segment(3)) == '')?0:$this->ddlCountry;
		
		
		switch($this->ddlCountry)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlCountry');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlCountry');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 default:
					$extraCheck = " AND nParentId ='".$this->ddlCountry."'";
			 break;
		}//end switch

		
		$data['list_country']	= $this->country_model->getDropCountrySearch();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Country','nId'," WHERE nParentId !='0'".$extraCheck), $target,'city/showlist/'.$this->uri->segment(3));
		
		$data['list_value']   = $this->country_model->getCity($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'city/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('city/city');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCity()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		$this->showTitle   = CITYMANAGEMENT;
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'City Name', 'required');
    $id =  $this->uri->segment(4);
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'city/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("city/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//active countries
		$data['list_country'] = $this->country_model->getDropCountry();
		
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle    = ADDNEWCITY;
				$this->btnVal	    = ADDCONTINUE;
				$this->btnVal2	    = SAVEEXIT;
				$this->txtTitle	    = $this->input->post('txtTitle');
				$this->txtTitle2	= $this->input->post('txtTitle2');
				$this->chkValYes    = 'checked="checked"';
				$this->chkValNo	    = '';
				$this->catId	    = '';
				$this->ddlCategory  = $this->input->post('ddlCategory');
			break;
			
			case "e":
				$this->showTitle  = EDITCITY;
				$this->btnVal	  = UPDATECITY;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
				$showVal	       = $this->country_model->getCorresCity();
				$this->txtTitle	   = $showVal->vName;
				$this->txtTitle2   = $showVal->vShortName;
				$this->ddlCategory = $showVal->nParentId;

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
			$this->load->view('city/edit_city');
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
				$data['error_value'] = CITYNAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('city/edit_city');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->country_model->insertCity();
						
					break;
					
					case "e":
						//update into db
						$this->country_model->updateCity();
						
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delcity()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CITYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->country_model->deleteCity();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CITYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->country_model->deleteCityMultiple();
	}//end function 
	
	function filterShowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle   = CITYMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $this->ddlCountry  = $this->common_model->searchKeyword('ddlCountry');
		$this->txtCityname = $this->common_model->searchKeyword('txtCityname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCityname');
			$this->txtCityname = $this->common_model->searchKeyword('txtCityname');
		}//end if
		
		if(!empty($this->txtCityname))
		{
			$extraCheck = " AND(vName LIKE '".$this->txtCityname." %' OR vName LIKE '% ".$this->txtCityname."'  
							OR vName LIKE '%".$this->txtCityname."%'
							OR vShortName LIKE '".$this->txtCityname." %' OR vShortName LIKE '% ".$this->txtCityname."'
							OR vShortName LIKE '%".$this->txtCityname."%')";
							
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nParentId ='".$this->ddlCountry."'";
				break;
			}//end switch
		}//end if
		else
		{
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " AND nParentId ='".$this->ddlCountry."'";
				break;
			}//end switch
		}//end else
		$countFilterCity	=  $this->country_model->getCountfilterCity($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterCity, $target,'city/filterShowlist2/'.$this->uri->segment(3));

		$data['list_value'] = $this->country_model->getFilterCity($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'city/showlist');

		$this->load->view('city/city_filter',$data);
	}//end function
	
	function filterShowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CITYMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$data['list_country'] = $this->country_model->getDropCountrySearch();
	    $this->ddlCountry  	  = $this->common_model->searchKeyword('ddlCountry');
		$this->txtCityname 	  = $this->common_model->searchKeyword('txtCityname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCityname');
			$this->txtCityname = $this->common_model->searchKeyword('txtCityname');
		}//end if
		
		if(!empty($this->txtCityname))
		{
			$extraCheck = " WHERE(vName LIKE '".$this->txtCityname." %' OR vName LIKE '% ".$this->txtCityname."'  
							OR vName LIKE '%".$this->txtCityname."%'
							OR vShortName LIKE '".$this->txtCityname." %' OR vShortName LIKE '% ".$this->txtCityname."'
							OR vShortName LIKE '%".$this->txtCityname."%')";
							
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nParentId ='".$this->ddlCountry."'";
				break;
			}//end switch
		}//end if
		
		else
		{
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " AND nParentId ='".$this->ddlCountry."'";
				break;
			}//end switch
		}//end else
		
		$countFilterCity	=  $this->country_model->getCountfilterCity($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterCity, $target,'city/filterShowlist2/'.$this->uri->segment(3));

		$data['list_value'] = $this->country_model->getFilterCity($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'city/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('city/city');
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
		$txtTitle       	    = $this->common_model->get_field_name('Country','vName'," WHERE  vName='".mysql_real_escape_string($this->txtTitle)."' AND nParentId !='0' ".$extraCond."");
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
