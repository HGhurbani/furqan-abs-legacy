<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hotel extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('hotel_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
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
					$extraCheck = " WHERE nCountry ='".$this->ddlCountry."'";
			 break;
		}//end switch

		
		$data['list_country']	= $this->hotel_model->getDropCountrySearch();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Hotel','nHId',$extraCheck), $target,'hotel/showlist/'.$this->uri->segment(3));
		
		
		$data['list_value']   = $this->hotel_model->getHotel($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('hotel/hotel');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editHotel()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      = HOTELMANAGEMENT;
		$data['title'] 	      = $this->common_model->get_site_title(); 
		//$data['cityList']     = array();
		//active countries
		$data['list_country'] = $this->hotel_model->getDropCountry();
		//active service type
		$data['list_service'] = $this->hotel_model->getDropService();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Hotel Name', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("hotel/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle    = ADDNEWHOTEL;
				$this->btnVal	    = ADDCONTINUE;
				$this->btnVal2	    = SAVEEXIT;
				$this->txtTitle	    = $this->input->post('txtTitle');
				$this->txtPhone 	= $this->input->post('txtPhone');
				$this->txtFax 	    = $this->input->post('txtFax');
				$this->chkValYes    = 'checked="checked"';
				$this->chkValNo	    = '';
				$this->catId	    = '';
				$this->ddlCountry   = $this->input->post('ddlCountry');
				$this->ddlCity      = $this->input->post('ddlCity');
				$this->ddlService   = $this->input->post('ddlService');
				$data['cityList']	= $this->hotel_model->getDropCity();
			break;
			
			case "e":
				$this->showTitle  = EDITHOTEL;
				$this->btnVal	  = UPDATEHOTEL;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
				$showVal	       = $this->hotel_model->getCorresHotel();
				$this->txtTitle	   = $showVal->vName;
				$this->txtPhone    = $showVal->vPhone;
				$this->txtFax 	   = $showVal->vFax;
				$this->txtAddress  = $showVal->vAddress;
				$this->ddlCountry  = $showVal->nCountry;
				$this->ddlCity     = $showVal->nCity;
				$this->ddlService  = $showVal->nService;
				$data['cityList']  = $this->hotel_model->getDropAllCity();

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
			$this->load->view('hotel/edit_hotel');
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
					$this->extraCond  = " AND nHId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			
			$chkTitle = $this->db->query("SELECT vName FROM {PRE}Hotel WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = HOTELNAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('hotel/edit_hotel');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->hotel_model->insertHotel();
						
					break;
					
					case "e":
						//update into db
						$this->hotel_model->updateHotel();
						
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delhotel()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->hotel_model->deleteHotel();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->hotel_model->deleteHotelMultiple();
	}//end function 
	
	//show city
	function showcity()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->ddlCountry = $this->input->post('ddlCountry');
		$this->ddlCity    = $this->input->post('ddlCity');
		
		$data['cityList'] = $this->hotel_model->getCityList($this->ddlCountry);
		
		$this->load->view('hotel/show_city',$data);
	}//end function
	
	function filtershowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $this->ddlCountry   = $this->common_model->searchKeyword('ddlCountry');
		$this->txtHotelname = $this->common_model->searchKeyword('txtHotelname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtHotelname');
			$this->txtHotelname = $this->common_model->searchKeyword('txtHotelname');
		}//end if

		if(!empty($this->txtHotelname))
		{
			$extraCheck = " WHERE (h.vName LIKE '".$this->txtHotelname." %' OR h.vName LIKE '% ".$this->txtHotelname."'  
							OR h.vName LIKE '%".$this->txtHotelname."%')";
							
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nCountry ='".$this->ddlCountry."'";
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
					$extraCheck .= " WHERE nCountry = '".$this->ddlCountry."'";
				break;
			}//end switch
		}//end else

		$target = '.';
		$data['countHotel'] = $this->hotel_model->getCountFilterHotel($extraCheck);
		$this->common_model->_paging($countHotel, $target,'hotel/filterShowlist2/'.$this->uri->segment(3));
		$data['list_value'] = $this->hotel_model->getFilterHotel($extraCheck);

		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel/showlist');

		$this->load->view('hotel/hotel_filter',$data);
	}//end function 
	
	function filtershowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = HOTELMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $this->ddlCountry   = $this->common_model->searchKeyword('ddlCountry');
		$this->txtHotelname = $this->common_model->searchKeyword('txtHotelname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtHotelname');
			$this->txtHotelname = $this->common_model->searchKeyword('txtHotelname');
		}//end if

		if(!empty($this->txtHotelname))
		{
			$extraCheck .= " WHERE (h.vName LIKE '".$this->txtHotelname." %' OR h.vName LIKE '% ".$this->txtHotelname."'  
							OR h.vName LIKE '%".$this->txtHotelname."%')";
							
			switch($this->ddlCountry)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nCountry ='".$this->ddlCountry."'";
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
					$extraCheck = " WHERE nCountry = '".$this->ddlCountry."'";
				break;
			}//end switch
		}//end else
		
		$target = '.';
		$data['countHotel'] = $this->hotel_model->getCountFilterHotel($extraCheck);
		$this->common_model->_paging($countHotel, $target,'hotel/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->hotel_model->getFilterHotel($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('hotel/hotel');
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
				$extraCond = " AND nHId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Hotel','vName'," WHERE  vName='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
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
