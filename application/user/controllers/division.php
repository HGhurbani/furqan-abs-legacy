<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Division extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('division_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DIVISIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Division','nId'," "), $target,'division/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->division_model->getAllDivision();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('company/division');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editDivision()
	{
        $this->load->helper('html');
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Title', 'required');

        $config['upload_path']   = './upload/division/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']		 = '1230000';
        $config['max_width']  	 = '10000';
        $config['max_height']    = '10000';
        $config['image_library'] = 'gd2';
        $config['file_name']     = time().'_'.$_FILES['txtPic']['name'];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $data['list_currency']	   = $this->division_model->getActiveCurrency();
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   = ADDNEWDIVISION;
				$this->btnVal	   = ADDCONTINUE;
				$this->btnVal2	   = SAVEEXIT;
				$this->txtTitle	   = $this->input->post('txtTitle');
				$this->txtDivCode  = $this->input->post('txtDivCode');
				$this->ddlCurrency = $this->common_model->get_field_name('Company','nCurrency'," WHERE nId = '1'");
                $this->txtTitle	   = $this->input->post('txtTitle');
                $this->txtDivCode  = $this->input->post('txtDivCode');
                $this->txtAddress  = $this->common_model->get_field_name('Company','vAddress'," WHERE nId = '1'");
                $this->txtPhone	   = $this->input->post('txtPhone');
                $this->txtMobile   = $this->input->post('txtMobile');
                $this->txtEmail    = $this->input->post('txtEmail');
                $this->txtWeb	   = $this->input->post('txtWeb');
/*                $this->txtDate     = $this->input->post('txtDate');
                $this->txtDateTo   = $this->input->post('txtDateTo');
*/				$this->chkValYes   = 'checked="checked"';
				$this->chkValNo	   = '';
				$this->catId	   = '';
			break;
			
			case "e":
				$this->showTitle   = EDITDIVISION;
				$this->btnVal	   = UPDATEDIVISION;
				$this->catId	   = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
				
				$showVal	       = $this->division_model->getCorresDivision();
				$this->txtTitle	   = $showVal->vTitle;
				$this->txtDivCode  = $showVal->vDivCode;
				$this->ddlCurrency = $showVal->nCurrency;
                $this->txtAddress  = $showVal->vAddress;
                $this->txtPhone	   = $showVal->vPhone;
                $this->txtMobile   = $showVal->vMobile;
                $this->txtEmail    = $showVal->vEmail;
                $this->txtWeb	   = $showVal->vWeb;
/*                $this->txtDate     = $showVal->dYearDate;
                $this->txtDateTo   = $showVal->dYearTo;
*/                $this->txtPic	   = $showVal->vImg;
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
			$this->load->view('company/edit_division');
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
                    $this->load->view('company/edit_division',$data);
                    $this->load->view('general/template_bottom');
                }//end if
                else
                {
                    $data       = array('upload_data' => $this->upload->data());
                    $txtPicName = $data['upload_data']['file_name'];
                    $dataEdit['vImg']   = $txtPicName;
                    @unlink('./upload/division/'.$this->txtPic);
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
                $chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Division WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' ".$this->extraCond);
                if ($chkTitle->num_rows() > 0)
                {
                    $data['error_value'] = DIVISIONEXIST;
                    $this->load->view('general/template_top',$data);
                    $this->load->view('company/edit_division');
                    $this->load->view('general/template_bottom');
                }//end if
                else
                {
                    //checking segment
                    switch($this->uri->segment(4))
                    {
                        case "a":
                            //insert into db
                            $this->division_model->insertDivision($dataEdit);
                            break;

                        case "e":
                            //Update into db
                            $this->division_model->updateDivision($dataEdit);
                            break;
                    }//end switch
                }//end else
            }//end if
		}//end else	
	}//end function

	function deldivision()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DIVISIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->division_model->deleteDivision();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DIVISIONMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->division_model->deleteAllDivision();
	}//end function 
	
	function filterByDivision()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DIVISIONMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtDivision = $this->common_model->searchKeyword('txtDivision');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtDivision');
			$this->txtDivision = $this->common_model->searchKeyword('txtDivision');
		}//end if
		
		if(!empty($this->txtDivision))
		{
			$extraCheck= " WHERE (vTitle LIKE '".$this->txtDivision." %' OR vTitle LIKE '% ".$this->txtDivision."'  
							OR vTitle LIKE '%".$this->txtDivision."%'
							OR vDivCode LIKE '".$this->txtDivision." %' OR vDivCode LIKE '% ".$this->txtDivision."'
							OR vDivCode LIKE '%".$this->txtDivision."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Division','nId',$extraCheck), $target,'division/filterByDivision2/'.$this->uri->segment(3));
		$data['list_value'] = $this->division_model->getFilterDivision($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('company/division_filter',$data);
	}//end function
	
	function filterByDivision2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DIVISIONMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtDivision = $this->common_model->searchKeyword('txtDivision');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtDivision');
			$this->txtDivision = $this->common_model->searchKeyword('txtDivision');
		}//end if
		
		if(!empty($this->txtDivision))
		{
			$extraCheck= " WHERE (vTitle LIKE '".$this->txtDivision." %' OR vTitle LIKE '% ".$this->txtDivision."'  
							OR vTitle LIKE '%".$this->txtDivision."%'
							OR vDivCode LIKE '".$this->txtDivision." %' OR vDivCode LIKE '% ".$this->txtDivision."'
							OR vDivCode LIKE '%".$this->txtDivision."%')";
		}//end if
		else
		{
			$extraCheck= " ";
		}//end else
		//echo $extraCheck; die();
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Division','nId',$extraCheck), $target,'division/filterByDivision2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->division_model->getFilterDivision($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('company/division');
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
		$txtTitle       	    = $this->common_model->get_field_name('Division','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
		if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

	function checkDivcode()
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
		$this->txtDivcode 		= $this->input->post('txtDivCode');
		$txtDivcode       	    = $this->common_model->get_field_name('Division','vDivCode'," WHERE  vDivCode='".mysql_real_escape_string($this->txtDivcode)."' ".$extraCond." ");
		if($txtDivcode    == $this->txtDivcode)
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
		$table             = "{PRE}Division";
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
		redirect(site_url("division/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>