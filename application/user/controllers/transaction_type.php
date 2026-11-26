<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transaction_type extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('transaction_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId'," WHERE nParentId ='0'"), $target,'transaction_type/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->transaction_model->getAllTransactiontype();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('transaction/transaction_type');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editTransactiontype()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Type Name', 'required');
				
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWTRANSTYPE;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITTRANSTYPE;
				$this->btnVal	  = UPDATETYPE;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	      = $this->transaction_model->getCorresTranstype();
				$this->txtTitle	  = $showVal->vTitle;
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
			$this->load->view('transaction/edit_type');
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
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Transactions WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' AND nParentId='0'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = TRANSTYPEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('transaction/edit_type');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->transaction_model->insertTranstype();
					break;
					
					case "e":
						//Update into db
						$this->transaction_model->updateTranstype();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function deltranstype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->transaction_model->deleteTranstype();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->transaction_model->deleteAllTranstype();
	}//end function 
	
	function filterByTranstype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('transtypename');
			$this->txtTranstypename = $this->common_model->searchKeyword('transtypename');
		}//end if
		
		if(!empty($this->txtTranstypename))
		{
			$extraCheck= " AND vTitle LIKE '".$this->txtTranstypename."%'";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId', " WHERE nParentId ='0'".$extraCheck), $target,'transaction_type/filterByTranstype2/'.$this->uri->segment(3));
		$data['list_value'] = $this->transaction_model->getFilterTranstype($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('transaction/transactiontype_filter',$data);
	}//end function
	
	function filterByTranstype2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('transtypename');
			$this->txtTranstypename = $this->common_model->searchKeyword('transtypename');
		}//end if
		
		if(!empty($this->txtTranstypename))
		{
			$extraCheck= " AND vTitle LIKE '".$this->txtTranstypename."%'";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId'," WHERE nParentId ='0'".$extraCheck), $target,'transaction_type/filterByTranstype2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->transaction_model->getFilterTranstype($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('transaction/transaction_type');
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
		$txtTitle       	    = $this->common_model->get_field_name('Transactions','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." AND nParentId='0'");
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
		$table             = "{PRE}Transactions";
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
		redirect(site_url("transaction_type/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>