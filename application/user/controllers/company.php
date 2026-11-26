<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('company_model');
	}

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = DIVISIONMANAGEMENT;
        $this->common_model->check_admin_authorization();
        //site Name
        $target = '.';
        $this->common_model->_paging($this->common_model->get_total_num_rows('Company','nId'," "), $target,'company/showlist/'.$this->uri->segment(3));
        $data['list_value']   = $this->company_model->getCompanies();
        $this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

        $this->load->view('general/template_top');
        $this->load->view('company/company',$data);
        $this->load->view('general/template_bottom');
    }//end function

	function editCompany()
	{
		$this->load->helper('html');
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtTitle', 'Company Name', 'required');
		$data['list_country']		= $this->company_model->getActiveCountry();
		$data['list_div_value']	    = $this->company_model->getActiveDivision(); 
		$data['list_loc_value']	    = $this->company_model->getActiveLocation(); 
		$data['list_currency']		= $this->company_model->getActiveCurrency(); 
		
		$config['upload_path']   	= './upload/company/';
		$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
		$config['max_size']	     	= '1230000';
		$config['max_width']     	= '10000';
		$config['max_height']    	= '10000';
		$config['image_library'] 	= 'gd2';
		$config['file_name']     	= time().'_'.$_FILES['txtPic']['name'];
		$this->load->library('upload', $config);
		$this->upload->initialize($config);	
		
		$this->showTitle  		    = EDITCOMPANY;
		$this->btnVal	  		    = UPDATECOMPANY;
		$this->catId	  		    = $this->uri->segment(3);
		
		if(intval($this->input->post('nId')) != 0)
		{
			$this->catId = intval($this->input->post('nId'));
		}//end if
		
		$showVal	      	 = $this->company_model->getCorresCompany();
		$this->txtId	  	 = $showVal->nId;
		$this->txtTitle	  	 = $showVal->vTitle;
		$this->txtCode    	 = $showVal->vCode;
		$this->txtAddress    = $this->common_model->removeNewline($showVal->vAddress);
		$this->txtAddress2   = $this->common_model->removeNewline($showVal->vAddress2);
		//$this->txtAddress3   = $this->common_model->removeNewline($showVal->vAddress3);
		$this->ddlCountry    = $showVal->nCountry;
		$this->ddlDivision   = $showVal->nDivision;
		$this->ddlLocation   = $showVal->nLocation;
		$this->txtPhone      = $showVal->vPhone;
		$this->txtFax        = $showVal->vFax;
		$this->txtEmail      = $showVal->vEmail;
		$this->ddlCurrency   = $showVal->nCurrency;
		$this->txtWeb        = $showVal->vWeb;		
		$this->txtFromDate   = $showVal->dFromDate;
		$this->txtToDate     = $showVal->dToDate;
		$this->txtPic	   	 = $showVal->vImg;
		$this->txtThumb	   	 = $this->common_model->getThumbName($showVal->vImg);

		if($showVal->vActive == '1')
		{
			$this->chkValYes  = 'checked="checked"';
		}//end if 
		else
		{
			$this->chkValNo   = 'checked="checked"';
		}//end else

		$dataEdit = array();
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('company/editCompany');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			@list($newwidth,$newheight) = getimagesize($_FILES['txtPic']['tmp_name']);
			if($_FILES['txtPic']['name'] != '')
			{
				if ( ! $this->upload->do_upload('txtPic'))
				{
					$data['error_value'] = $this->upload->display_errors();
		
					$this->load->view('general/template_top',$data);
					$this->load->view('company/editCompany',$data);
					$this->load->view('general/template_bottom');
				}//end if
				else
				{
					$data               = array('upload_data' => $this->upload->data());
					$this->common_model->_createThumbnail($data['upload_data']['file_name'],113,84,'company'); 
					$dataEdit['vImg']   = $data['upload_data']['file_name'];;
					@unlink('./upload/company/'.$this->txtThumb);
					@unlink('./upload/company/'.$this->txtPic);
				}//end else
			}//end if
			else
			{
				$this->extraCond2  = "";
			}//end else
			if($data['error_value']=='')
			{
				//Update into db
				$this->company_model->updateCompany($data,$dataEdit);
			}//end if
		}//end else	
	}//end function
	
	function filterLocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		$this->ddlDivision    	  = $this->input->post('ddlDivision');
		$data['list_loc_value']   = $this->company_model->filterlocation($this->ddlDivision);
		
		$this->load->view('company/show_location',$data);
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>