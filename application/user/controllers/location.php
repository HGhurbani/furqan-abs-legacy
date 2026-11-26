
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Location extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('location_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LOCATIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		if($this->uri->segment('5') != '')
			$this->ddlDivision = $this->common_model->searchKeyword33('5');
			
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlDivision = $this->common_model->searchKeyword33('3');
			
		else
			$this->ddlDivision = $this->common_model->searchKeyword3('ddlDivision');
			
		$this->ddlDivision = (trim($this->uri->segment(3)) == '')?0:$this->ddlDivision;
		
		switch($this->ddlDivision)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlDivision');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlDivision');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
			   
			 default:
					$extraCheck = " AND nDivision ='".$this->ddlDivision."'";
			 break;
		}//end switch

		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Location','nId'," "), $target,'location/showlist/'.$this->uri->segment(3));
		
		$data['list_div_value']	= $this->location_model->getAllDivision(); 
		$data['list_value']   	= $this->location_model->getAllLocation($extraCheck);
		$this->count_rows     	= count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('company/location');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editLocation()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	          = $this->common_model->get_site_title(); 
		$this->load->helper('html');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Title', 'required');
		$data['list_loc_admin']	  = $this->location_model->getLocationAdmin();	
		$data['list_div_value']	  = $this->location_model->getActiveDivision(); 

        $config['upload_path']   = './upload/location/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']		 = '1230000';
        $config['max_width']  	 = '10000';
        $config['max_height']    = '10000';
        $config['image_library'] = 'gd2';
        $config['file_name']     = time().'_'.$_FILES['txtPic']['name'];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $data['list_currency']	 = $this->location_model->getActiveCurrency();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle      = ADDNEWLOCATION;
				$this->btnVal	      = ADDCONTINUE;
				$this->btnVal2	      = SAVEEXIT;				
				$this->ddlDivision    = $this->input->post('ddlDivision');
				$this->txtTitle	      = $this->input->post('txtTitle');
				$this->txtCode        = $this->input->post('txtCode');
				$this->ddlCurrency    = $this->common_model->get_field_name('Company','nCurrency'," WHERE nId = '1'");
				$this->txtLocaddr     = $this->common_model->get_field_name('Company','vAddress'," WHERE nId = '1'");
				$this->txtLocphone    = $this->input->post('txtLocphone');
				$this->txtLocmob      = $this->input->post('txtLocmob');
				$this->txtEmail       = $this->input->post('txtEmail');
				$this->txtWeb         = $this->input->post('txtWeb');
/*				$this->txtYearDate    = $this->input->post('txtYearDate');
				$this->txtYearTo      = $this->input->post('txtYearTo');
*/				$this->chkValYes      = 'checked="checked"';
				$this->chkValNo	      = '';
				$this->catId	      = '';
			break;
			
			case "e":
				$this->showTitle      = EDITLOCATION;
				$this->btnVal	      = UPDATELOCATION;
				$this->catId	      = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          = $this->location_model->getCorresLocation();
				$this->ddlDivision    = $showVal->nDivision;
				$this->txtTitle	      = $showVal->vTitle;
				$this->txtCode        = $showVal->vCode;
				$this->ddlCurrency    = $showVal->nCurrency;
				$this->txtLocaddr     = $this->common_model->removeNewline($showVal->vAddress);
				$this->txtLocphone    = $showVal->vPhone;
				$this->txtLocmob      = $showVal->vMobile;
				$this->txtEmail       = $showVal->vEmail;
				$this->txtWeb         = $showVal->vWeb;
/*				$this->txtYearDate    = $showVal->dYearDate;
				$this->txtYearTo      = $showVal->dYearTo;
*/                $this->txtPic	      = $showVal->vImg;

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
		
        $dataEdit = array();
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('company/edit_location');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
            if($_FILES['txtPic']['name']!='')
            {
                if ( ! $this->upload->do_upload('txtPic'))
                {
                    $data['error_value'] = $this->upload->display_errors();
                    $this->load->view('general/template_top',$data);
                    $this->load->view('company/edit_location',$data);
                    $this->load->view('general/template_bottom');
                }//end if
                else
                {
                    $data       		= array('upload_data' => $this->upload->data());
                    $txtPicName 		= $data['upload_data']['file_name'];
                    $dataEdit['vImg']   = $txtPicName;
                    @unlink('./upload/location/'.$this->txtPic);
                }//end else
            }//end if
            else
            {
                $this->extraCond2  = "";
            }//end else
			
            if($data['error_value']=='')
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
				$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Location WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' ".$this->extraCond);
				if ($chkTitle->num_rows() > 0)
				{
					$data['error_value'] = LOCATIONEXIST;
					$this->load->view('general/template_top',$data);
					$this->load->view('company/edit_location');
					$this->load->view('general/template_bottom');
				}//end if
				else
				{
					//checking segment
					switch($this->uri->segment(4))
					{
						case "a":
							//insert into db
							$this->location_model->insertLocation($dataEdit);
						break;
						
						case "e":
							//Update into db
							$this->location_model->updateLocation($dataEdit);
						break;
					}//end switch
				}//end else
			}//end if
		}//end else	
	}//end function

	function dellocation()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LOCATIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->location_model->deleteLocation();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LOCATIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->location_model->deleteAllLocation();
	}//end function 
	
	function filterByLocation()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LOCATIONMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $this->ddlDivision = $this->common_model->searchKeyword('ddlDivision');
		$this->txtLocation = $this->common_model->searchKeyword('txtLocation');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtLocation');
			$this->txtLocation = $this->common_model->searchKeyword('txtLocation');
		}//end if
		
		if(!empty($this->txtLocation))
		{
			$extraCheck = " WHERE(l.vTitle LIKE '".$this->txtLocation." %' OR l.vTitle LIKE '% ".$this->txtLocation."'  
							OR l.vTitle LIKE '%".$this->txtLocation."%'
							OR l.vPrefix LIKE '".$this->txtLocation." %' OR l.vPrefix LIKE '% ".$this->txtLocation."'
							OR l.vPrefix LIKE '%".$this->txtLocation."%')";
							
			switch($this->ddlDivision)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nDivision ='".$this->ddlDivision."'";
				break;
			}//end switch
		}//end if
		
		else
		{
			switch($this->ddlDivision)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " WHERE nDivision ='".$this->ddlDivision."'";
				break;
			}//end switch
		}//end else
		
		$countFilterLocation	=  $this->location_model->getCountfilterLocation($extraCheck);
		$target 				= '.';
		$this->common_model->_paging($countFilterLocation, $target,'location/filterByLocation2/'.$this->uri->segment(3));

		$data['list_value'] = $this->location_model->getFilterLocation($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('company/location_filter',$data);
	}//end function
	
	function filterByLocation2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LOCATIONMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $data['list_div_value']	= $this->location_model->getAllDivision(); 
	    $this->ddlDivision 		= $this->common_model->searchKeyword('ddlDivision');
		$this->txtLocation 		= $this->common_model->searchKeyword('txtLocation');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtLocation');
			$this->txtLocation = $this->common_model->searchKeyword('txtLocation');
		}//end if
		
		if(!empty($this->txtLocation))
		{
			$extraCheck = " WHERE(l.vTitle LIKE '".$this->txtLocation." %' OR l.vTitle LIKE '% ".$this->txtLocation."'  
							OR l.vTitle LIKE '%".$this->txtLocation."%'
							OR l.vPrefix LIKE '".$this->txtLocation." %' OR l.vPrefix LIKE '% ".$this->txtLocation."'
							OR l.vPrefix LIKE '%".$this->txtLocation."%')";
							
			switch($this->ddlDivision)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck .= " AND nDivision ='".$this->ddlDivision."'";
				break;
			}//end switch
		}//end if
		
		else
		{
			switch($this->ddlDivision)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " WHERE nDivision ='".$this->ddlDivision."'";
				break;
			}//end switch
		}//end else
		
		$countFilterLocation	=  $this->location_model->getCountfilterLocation($extraCheck);
		$target 				= '.';
		$this->common_model->_paging($countFilterLocation, $target,'location/filterByLocation2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->location_model->getFilterLocation($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('company/location');
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
		$txtTitle       	    = $this->common_model->get_field_name('Location','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
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
		$table             = "{PRE}Location";
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
		redirect(site_url("location/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>