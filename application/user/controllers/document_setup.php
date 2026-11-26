<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Document_setup extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('document_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DOCUMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Document','nDId'," "), $target,'document_setup/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->document_model->getAllDocument();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('transaction/document');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editDocument()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtYearref', 'Year REF', 'required');
		$data['list_loc_value']		   = $this->document_model->getActiveLocations();
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   	   	   = ADDNEWDOCUMENT;
				$this->btnVal	   	   	   = ADDCONTINUE;
				$this->btnVal2	   	   	   = SAVEEXIT;
				$data['trans_details']     = $this->document_model->getActiveTransactions();

				$this->txtYearref	   	   = $this->input->post('txtYearref');
				//$this->txtPrevYearref   = $this->input->post('txtPrevYearref');
				$this->ddlLocation     	   = $this->input->post('ddlLocation');
				$this->ddlTranstype    	   = $this->input->post('ddlTranstype');
				$this->ddlTransaction  	   = $this->input->post('ddlTransaction');
				$this->txtPrefix  	   	   = $this->input->post('txtPrefix');
				$this->txtCurryear     	   = $this->input->post('txtCurryear');
				$this->txtMindigit     	   = $this->input->post('txtMindigit');
				$this->txtNumber       	   = $this->input->post('txtNumber');
				$this->txtSampledoc    	   = $this->input->post('txtSampledoc');
				$this->chkValYes       	   = 'checked="checked"';
				$this->chkValNo	       	   = '';
				$this->catId	       	   = '';
				$data['selectd_trans']     = array();
				$data['selectd_prefix']    = array();
				$data['selectd_mindigit']  = array();	
				$data['selectd_number']    = array();			
				$data['selectd_sampledoc'] = array();
			break;
			
			case "e":
				$this->showTitle       	   = EDITDOCUMENT;
				$this->btnVal	       	   = UPDATEDOCUMENT;
				$this->catId	   	   	   = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   	   	   = intval($this->input->post('nId'));
				}//end if
				
				$showVal	       	   	   = $this->document_model->getCorresDocument();
				$this->txtYearref	   	   = $showVal->vYearref;
				/*$this->txtPrevYearref    = $showVal->dPrevYearref;*/
				$this->ddlLocation     	   = @split(',',$showVal->vLocation);
				$this->ddlTranstype    	   = $showVal->nTranstype;
				$this->ddlTransaction  	   = $showVal->nTransaction;
				$this->txtPrefix  	   	   = $showVal->vPrefix;
				$this->txtCurryear     	   = $showVal->nCurryear;
				$this->txtMindigit     	   = $showVal->vMindigit;
				$this->txtNumber       	   = $showVal->nNumber;
				$this->txtSampledoc    	   = $showVal->vSampledoc;
				$data['trans_details']     = $this->document_model->getActiveTransactions();
				$data['selectd_trans'] 	   = $this->document_model->getSelectedTrans();
				$data['selectd_prefix']    = $this->document_model->getSelectedPrefix();
				$data['selectd_mindigit']  = $this->document_model->getSelectedMindigit();
				$data['selectd_number']    = $this->document_model->getSelectedNumber();			
				$data['selectd_sampledoc'] = $this->document_model->getSelectedSampledoc();

				if($showVal->vActive == '1')
				{
					$this->chkValYes  	  = 'checked="checked"';
				}//end if 
				else
				{
					$this->chkValNo   	  = 'checked="checked"';
				}//end else
			break;
		}//end switch
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('transaction/edit_document');
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
					$this->document_model->insertDocument();
				break;
				
				case "e":
					//Update into db
					$this->document_model->updateDocument();
				break;
			}//end switch
		}//end else	
	}//end function

	function deldocument()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DOCUMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->document_model->deleteDocument();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DOCUMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->document_model->deleteAllDocument();
	}//end function 
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
