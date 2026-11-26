<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stock_transfer extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('stock_transfer_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	 = STOCKTRANSFERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countStocktransfer	 =  $this->stock_transfer_model->getCountStockTransfer();
		$target 			 = '.';
		$this->common_model->_paging($countStocktransfer, $target,'stock_transfer/showlist/'.$this->uri->segment(3));
		$data['list_value']  = $this->stock_transfer_model->getAllStockTransfer();
		$this->count_rows    = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('stock/stock_transfer');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editStocktransfer()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   		  		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');
		$data['list_loc_value']   		  = $this->stock_transfer_model->getActiveLocations();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWSTOCKTRANSFER;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtDate   		  = $this->input->post('txtDate');
				$this->txtDocNo     	  = $this->input->post('txtDocNo');
				$this->txtYearRef   	  = date('Y').' - '.date('Y');
				$this->txtIssued    	  = $this->input->post('txtIssued');
				$this->txtRemarks    	  = $this->input->post('txtRemarks');
				$this->txtToLoc   		  = $this->input->post('txtToLoc');
				$this->txtCustomer     	  = $this->input->post('txtCustomer');
				$this->txtIssuedTo   	  = $this->input->post('txtIssuedTo');
				$this->txtCode    	      = $this->input->post('txtCode');
				$this->txtStart    	      = $this->input->post('txtStart');
				$this->txtEnd    	      = $this->input->post('txtEnd');
				$this->txtStockQty    	  = $this->input->post('txtStockQty');
				
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
			break;
			
			case "e":
				$this->showTitle      	  = EDITSTOCKTRANSFER;
				$this->btnVal	      	  = UPDATESTOCKTRANSFER;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->stock_transfer_model->getCorresStockTransfer();

				$this->ddlLocation	  	  = $showVal->nLocation;
				$this->txtDate	      	  = $showVal->dDate;
				$this->txtDocNo        	  = $showVal->vDocNumber;
				$this->txtYearRef         = $showVal->vYearref;
				$this->txtIssued          = $showVal->vIssuedBy;
				$this->txtRemarks         = $showVal->vRemarks;
				$this->txtToLoc   		  = $showVal->vToLocation;
				$this->txtCustomer     	  = $showVal->vCustomer;
				$this->txtIssuedTo   	  = $showVal->vIssuedTo;
				$this->txtCode    	      = $showVal->vCode;
				$this->txtStart    	      = $showVal->vStart;
				$this->txtEnd    	      = $showVal->vEnd;
				$this->txtStockQty    	  = $showVal->nQuantity;

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
			$this->load->view('stock/edit_stock_transfer');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->stock_transfer_model->insertStockTransfer();
				break;
				
				case "e":
					//Update into db
					$this->stock_transfer_model->updateStockTransfer();
				break;
			}//end switch
			redirect(site_url("stock_transfer/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function delstocktransfer()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STOCKTRANSFERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stock_transfer_model->deleteStockTransfer();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STOCKTRANSFERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stock_transfer_model->deleteMultipleStockTransfer();
	}//end function 
	
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	 = STOCKENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearchDate = $this->common_model->searchKeyword('txtSearchDate');
		$this->txtSearch 	 = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearchDate');
			$this->session->unset_userdata('txtSearch');
			$this->txtSearchDate = $this->common_model->searchKeyword('txtSearchDate'); 
			$this->txtSearch     = $this->common_model->searchKeyword('txtSearch');
		}//end if
		if(!empty($this->txtSearch) && ($this->txtSearchDate == ''))
		{
			$extraCheck 		 = " WHERE(vDocNumber LIKE '".$this->txtSearch." %' OR vDocNumber LIKE '% ".$this->txtSearch."'  
							 			OR vDocNumber LIKE '%".$this->txtSearch."%')";
		}//end if
		if(!empty($this->txtSearchDate) && ($this->txtSearch == ''))
		{
			$extraCheck 		 .= " WHERE (dDate = '".$this->txtSearchDate."')";
		}//end else
		if(($this->txtSearch != '') && ($this->txtSearchDate != ''))
		{
			$extraCheck 		 .= " WHERE(vDocNumber LIKE '".$this->txtSearch." %' OR vDocNumber LIKE '% ".$this->txtSearch."'  
							 			OR vDocNumber LIKE '%".$this->txtSearch."%') AND (dDate = '".$this->txtSearchDate."')";
		}//end if
		$countFilterStockEntry	 =  $this->stock_transfer_model->getCountfilterList($extraCheck);
		$target 				 = '.';
		$this->common_model->_paging($countFilterStockEntry, $target,'stock_entry/filterList2/'.$this->uri->segment(3));

		$data['list_value']      = $this->stock_transfer_model->getFilterList($extraCheck);
		$this->count_rows        = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('stock/stock_transfer_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	 = STOCKENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearchDate = $this->common_model->searchKeyword('txtSearchDate');
		$this->txtSearch 	 = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearchDate');
			$this->session->unset_userdata('txtSearch');
			$this->txtSearchDate = $this->common_model->searchKeyword('txtSearchDate'); 
			$this->txtSearch     = $this->common_model->searchKeyword('txtSearch');
		}//end if
		if(!empty($this->txtSearch) && ($this->txtSearchDate == ''))
		{
			$extraCheck 		 = " WHERE(vDocNumber LIKE '".$this->txtSearch." %' OR vDocNumber LIKE '% ".$this->txtSearch."'  
							 			OR vDocNumber LIKE '%".$this->txtSearch."%')";
		}//end if
		if(!empty($this->txtSearchDate) && ($this->txtSearch == ''))
		{
			$extraCheck 		 .= " WHERE (dDate = '".$this->txtSearchDate."')";
		}//end else
		if(($this->txtSearch != '') && ($this->txtSearchDate != ''))
		{
			$extraCheck 		 .= " WHERE(vDocNumber LIKE '".$this->txtSearch." %' OR vDocNumber LIKE '% ".$this->txtSearch."'  
							 			OR vDocNumber LIKE '%".$this->txtSearch."%') AND (dDate = '".$this->txtSearchDate."')";
		}//end if
		$countFilterStockEntry	 =  $this->stock_transfer_model->getCountfilterList($extraCheck);
		$target 				 = '.';
		$this->common_model->_paging($countFilterStockEntry, $target,'stock_entry/filterList2/'.$this->uri->segment(3));

		$data['list_value']      = $this->stock_transfer_model->getFilterList($extraCheck);
		$this->count_rows        = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('stock/stock_transfer');
		$this->load->view('general/template_bottom');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>