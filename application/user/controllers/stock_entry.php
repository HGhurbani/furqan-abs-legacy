<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stock_entry extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('stock_entry_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	 = STOCKENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countStockentry	 =  $this->stock_entry_model->getCountStockEntry();
		$target 			 = '.';
		$this->common_model->_paging($countStockentry, $target,'stock_entry/showlist/'.$this->uri->segment(3));
		$data['list_value']  = $this->stock_entry_model->getAllStockEntry();
		$this->count_rows    = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'stock_entry/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('stock/stock_entry');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editStockentry()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   		  		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');
    $id = $this->uri->segment(4);
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'stock_entry/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("stock_entry/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_stock_type'] 	      = $this->stock_entry_model->getActiveStockType();
        $data['list_div_value']	          = $this->stock_entry_model->getActiveDivision();
		$data['list_loctra_value']        = $this->stock_entry_model->getTransferLocation(); 
		$data['list_code_value']          = $this->stock_entry_model->getStockcode(); 

        switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWSTOCKENTRY;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				
				$this->ddlDivision 		  = $this->input->post('ddlDivision');
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtDate   		  = date('Y-m-d');
				$this->txtDocNo     	  = $this->getAutomaticDocNumber();
				$this->txtYearRef   	  = date('Y').' - '.date('Y');
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$data['chkStockType'] 	  = array();
				$data['chkStockCode']  	  = array();
				$data['chkStartNo'] 	  = array();
				$data['chkEndNo'] 	      = array();
				$data['chkQuantity'] 	  = array();
                $data['list_loc_value']   = $this->stock_entry_model->getLocation();
				
				$this->ddlLocationTra     = $this->input->post('ddlLocationTra');
				$this->ddlCodeTra         = $this->input->post('ddlCodeTra');
				$this->txtStart           = $this->input->post('txtStart');
				$this->txtEnd             = $this->input->post('txtEnd');
				$this->txtStockTraQty     = $this->input->post('txtStockTraQty');
            break;
			
			case "e":
				$this->showTitle      	  = EDITSTOCKENTRY;
				$this->btnVal	      	  = UPDATESTOCKENTRY;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->stock_entry_model->getCorresStockEntries();
				$this->accId	      	  = $showVal->nId; 
				$this->ddlDivision	  	  = $showVal->nDivision;
                $this->ddlLocation	  	  = $showVal->nLocation;
				$this->txtDate	      	  = $showVal->dDate;
				$this->txtDocNo        	  = $showVal->vDocNumber;
				$this->txtYearRef         = $showVal->vYearref;
				$this->txtReceived        = $showVal->vReceivedBy;
				$this->txtRemarks         = $showVal->vRemarks;
                $data['list_loc_value']   = $this->stock_entry_model->filterLocation($this->ddlDivision);

                //get selected details code
				$stockValArray            = $this->stock_entry_model->getCorresStockEntryDetails();
			    $data['chkStockType']  	  = $stockValArray['stockType'];
		        $data['chkStockCode'] 	  = $stockValArray['stockCode'];
				$data['chkStartNo'] 	  = $stockValArray['stockStartNo'];
				$data['chkEndNo'] 	      = $stockValArray['stockEndNo'];
				$data['chkQuantity'] 	  = $stockValArray['stockQuantity'];
				$data['chkStockID']    	  = $stockValArray['stockId'];
				$data['stockdetID']       = $stockValArray['stockdetID'];

/*				$this->ddlLocationTra     = $showVal->tranLoc;
				$this->ddlCodeTra         = $showVal->vCodeTra;
				$this->txtStart           = $showVal->vStart;
				$this->txtEnd             = $showVal->vEnd;
				$this->txtStockTraQty     = $showVal->tranQty;
*/
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
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('stock/edit_stock_entry');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->stock_entry_model->insertStockEntry();
				break;
				
				case "e":
					//Update into db
					$this->stock_entry_model->updateStockEntry();
				break;
			}//end switch
			redirect(site_url("stock_entry/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function getAutomaticDocNumber()
	{
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='8'");
		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}StockEntry ORDER BY nSEID DESC LIMIT 0,1");
		if($fetchCode->num_rows() > 0):
			$totalNum           = $fetchCode->row();
			$this->txtDocNumber	= $totalNum->vDocNumber;
			$docNumber 			= @split('-',$this->txtDocNumber);
				
			#how many chars will be in the string
			$fill   			= $this->txtMinDigit;
			#the number
			$number 			= ($docNumber[3]+1);
			#with str_pad function the zeros will be added
			$lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			// The result: 000056
			
			$countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
		else:
			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='8'");
			$countVal 			= $this->txtDocNumber;
		endif;
		
		return($countVal);
	}//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->stock_entry_model->filterLocation($this->ddlDivision);
        $this->load->view('stock/show_location',$data);
    }//end function

	function delstockentry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STOCKENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stock_entry_model->deleteStockEntry();
	}//end function

	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = STOCKENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->stock_entry_model->deleteMultipleStockEntry();
	}//end function 
	
	//get stock code when selected stock type
	function getStockCode()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->ddlStockType = $this->input->post('ddlStockType'); 
		$this->txtStockCode	= $this->common_model->get_field_name('Stock','vCode'," WHERE nStkId='".$this->ddlStockType."'");

        $fetchCode          	= $this->db->query("SELECT vCode FROM {PRE}StockEntryDetails WHERE 
										nStocktype = '".$this->ddlStockType."' ORDER BY nId DESC LIMIT 0,1");
										
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vCode;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $number 			= ($docNumber[1]+1);
            $lDigit 			= str_pad($number, 1, '0', STR_PAD_LEFT);
            $this->txtStockCode	= $docNumber[0].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Stock','vPrefix'," 
							WHERE nStkId = '".$this->ddlStockType."'");
            $this->txtStockCode	= $this->txtDocNumber.'-1';
        endif;

        echo $this->txtStockCode;
	}//end function
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		$this->stock_entry_model->deleteSubAndDetails($this->subDetID);
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
		$countFilterStockEntry	 =  $this->stock_entry_model->getCountfilterList($extraCheck);
		$target 				 = '.';
		$this->common_model->_paging($countFilterStockEntry, $target,'stock_entry/filterList2/'.$this->uri->segment(3));

		$data['list_value']      = $this->stock_entry_model->getFilterList($extraCheck);
		$this->count_rows        = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'stock_entry/showlist');

		$this->load->view('stock/stock_entry_filter',$data);
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
		$countFilterStockEntry	 =  $this->stock_entry_model->getCountfilterList($extraCheck);
		$target 				 = '.';
		$this->common_model->_paging($countFilterStockEntry, $target,'stock_entry/filterList2/'.$this->uri->segment(3));

		$data['list_value']      = $this->stock_entry_model->getFilterList($extraCheck);
		$this->count_rows        = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'stock_entry/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('stock/stock_entry');
		$this->load->view('general/template_bottom');
	}//end function

    function getStockValues()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlCodeTra     = $this->input->post('ddlCodeTra');
        $this->stkDetails     = $this->stock_entry_model->getStockDetails($this->ddlCodeTra);
		echo json_encode($this->stkDetails);
    }//end function
	
	//check start number
	function getStartNo()
	{
		$this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
		echo $this->stock_entry_model->checkStartNo();
	}//end function
	
	//check start number
	function getEndNo()
	{
		$this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
		echo $this->stock_entry_model->checkEndNo();
	}//end function

    function printPreview()
    {
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  = $this->stock_entry_model->getCorresStockEntries();
        $this->accId	      	  = $showVal->nId;
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->nLocation;
        $this->txtDate	      	  = $showVal->dDate;
        $this->txtDocNo        	  = $showVal->vDocNumber;
        $this->txtYearRef         = $showVal->vYearref;
        $this->txtReceived        = $showVal->vReceivedBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $data['list_loc_value']   = $this->stock_entry_model->filterLocation($this->ddlDivision);

        //get selected details code
        $stockValArray            = $this->stock_entry_model->getCorresStockEntryDetails();
        $data['chkStockType']  	  = $stockValArray['stockType'];
        $data['chkStockCode'] 	  = $stockValArray['stockCode'];
        $data['chkStartNo'] 	  = $stockValArray['stockStartNo'];
        $data['chkEndNo'] 	      = $stockValArray['stockEndNo'];
        $data['chkQuantity'] 	  = $stockValArray['stockQuantity'];
        $data['chkStockID']    	  = $stockValArray['stockId'];
        $data['stockdetID']       = $stockValArray['stockdetID'];

        /*				$this->ddlLocationTra     = $showVal->tranLoc;
                        $this->ddlCodeTra         = $showVal->vCodeTra;
                        $this->txtStart           = $showVal->vStart;
                        $this->txtEnd             = $showVal->vEnd;
                        $this->txtStockTraQty     = $showVal->tranQty;
        */

        $this->load->view('stock/print_stock_entry',$data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
