<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ticket_type extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('ticket_type_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('TicketType','nTTId'," "), $target,'ticket_type/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->ticket_type_model->getAllTicketType();

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('sales_closing/ticket_type');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editTicketType()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Service Type', 'required');
				
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWTICKETTYPE;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = $this->uri->segment(5);
			break;
			
			case "e":
				$this->showTitle  = EDITTICKETTYPE;
				$this->btnVal	  = UPDATETICKETTYPE;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nTTId')) != 0)
				{
					$this->catId	  = intval($this->input->post('nTTId'));
				}//end if
				
				$showVal	      = $this->ticket_type_model->getCorresTicketType();
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
			$this->load->view('sales_closing/edit_ticket_type');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nTTId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nTTId!='".$this->input->post('nTTId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}TicketType WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = TICKETTYPENAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('sales_closing/edit_ticket_type');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->ticket_type_model->insertTicketType();
					break;
					
					case "e":
						//Update into db
						$this->ticket_type_model->updateTicketType();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function deltickettype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ticket_type_model->deleteTickettype();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ticket_type_model->deleteAllTickettype();
	}//end function 
	
	function filterByTickettype()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtTickettypename = $this->common_model->searchKeyword('tickettypename');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('tickettypename');
			$this->txtTickettypename = $this->common_model->searchKeyword('tickettypename');
		}//end if
		
		if(!empty($this->txtTickettypename))
		{
			$extraCheck= " WHERE (vTitle LIKE '".$this->txtTickettypename." %' OR vTitle LIKE '% ".$this->txtTickettypename."'  
							OR vTitle LIKE '%".$this->txtTickettypename."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('TicketType','nTTId', $extraCheck), $target,'ticket_type/filterByTickettype2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->ticket_type_model->getFilterTickettypeName($extraCheck);

		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('sales_closing/ticket_type_filter',$data);
	}//end function
	
	function filterByTickettype2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETTYPEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtTickettypename = $this->common_model->searchKeyword('tickettypename');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('tickettypename');
			$this->txtTickettypename = $this->common_model->searchKeyword('tickettypename');
		}//end if
		
		if(!empty($this->txtTickettypename))
		{
			$extraCheck = " AND (vTitle LIKE '".$this->txtTickettypename." %' OR vTitle LIKE '% ".$this->txtTickettypename."'  
							OR vTitle LIKE '%".$this->txtTickettypename."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('TicketType','nTTId', $extraCheck), $target,'TICKET_TYPE/filterByTickettype2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->ticket_type_model->getFilterTickettypeName($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('sales_closing/ticket_type');
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
				$extraCond = " AND nTTId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('tICKETtYPE','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
		if($txtTitle == $this->txtTitle)
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