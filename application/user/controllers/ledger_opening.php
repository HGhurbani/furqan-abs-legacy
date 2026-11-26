<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ledger_opening extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('ledger_opening_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = LEDGEROPENINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countLedgerOpening	  =  $this->ledger_opening_model->getCountLedgerOpening();
		$target 			  = '.';
		$this->common_model->_paging($countLedgerOpening, $target,'ledger_opening/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->ledger_opening_model->getAllLedgerOpening();
		$this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ledger_opening/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('ledger_opening/ledger_opening');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editLedgeropening()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');
    $id =$this->uri->segment(4);

//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ledger_opening/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("ledger_opening/showlist/".$this->uri->segment(3).'/no_access'));
    }


		$data['list_ledger_value']    	 = $this->ledger_opening_model->getLedgerOptions();
		$data['list_MACCode']            = $this->ledger_opening_model->genAcDlld();
        $data['list_mloc_value']  		 = $this->ledger_opening_model->getMultipleLocation();
		$data['list_div_value']   		 = $this->ledger_opening_model->getActiveDivision();
		$data['list_mode']        		 = $this->ledger_opening_model->getActiveMode();
        $data['list_curr_value']	  	 = $this->ledger_opening_model->getActiveCurrency();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWLEDGEROPENING;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
          reset($data['list_div_value']);
          $first_key = key($data['list_div_value']);
          $data['list_loc_value'] = $this->ledger_opening_model->filterLocation($first_key);


//		        $data['list_loc_value']   = $this->ledger_opening_model->getActiveLocations();
				$data['finance_details']  = $this->ledger_opening_model->getActiveFinance();
                $data['list_subacc']      = $this->ledger_opening_model->getSubAccountValues();
                $data['list_subledger_value']    = $this->ledger_opening_model->getFirstSubLedgerValue();
				
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtOpeningYear	  = (date('Y').' - '.date('Y'));
				$this->txtDate      	  = date('Y-m-d');
				$this->ddlMode  		  = $this->input->post('ddlMode');				
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';

$this->ddlCurrency = $this->common_model->getCompanyCurr();
				$data['chkLoc']      	  = array();
				$data['chkAccCode']  	  = array();
				$data['chkAccSubCode']	  = array();
				$data['chkBalFC']    	  = array();
				$data['chkBalDC'] 	  	  = array();
				$data['chkMode'] 	  	  = array();
				$data['chkBalCredit']  	  = array();
				$data['chkBalDebit']  	  = array();
				$data['chkDetailID'] 	  = array();

			break;
			
			case "e":
				$this->showTitle      	  = EDITLEDGEROPENING;
				$this->btnVal	      	  = UPDATELEDGEROPENING;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->ledger_opening_model->getCorresLedgerOpenings();
				$this->ddlLedger	  	  = $showVal->vLedger;
				$this->ddlSubLedger	  	  = $showVal->vSubLedger;
                $this->ddlDivision	  	  = $showVal->nDivision;
                $this->ddlLocation	  	  = $showVal->vLocation;
				$this->txtOpeningYear	  = $showVal->vYear;
				$this->txtDate      	  = $showVal->dDate;
				$this->ddlMode        	  = $showVal->vMode;
				$this->txtTotalDC    	  = $showVal->vTotalDC;
				$this->txtTotalFC    	  = $showVal->vTotalFC;
				$this->txtTotalC    	  = $showVal->nTotalC;
				$this->txtTotalD    	  = $showVal->nTotalD;
                $data['list_loc_value']   = $this->ledger_opening_model->filterLocation($this->ddlDivision);
                $this->Id                 = $showVal->nOBId;

                if($showVal->vActive == '1')
                {
                    $this->chkValYes  = 'checked="checked"';
                }//end if
                else
                {
                    $this->chkValNo   = 'checked="checked"';
                }//end else

                if($showVal->vLedger == 'SL')
                {
                    $data['list_subledger_value']    = $this->ledger_opening_model->getSubLedgerValues();
                }//end if
                else
                {
                    $data['list_subledger_value']    = $this->ledger_opening_model->getFirstSubLedgerValue();
                }//end else

				$accsubValArray           = $this->ledger_opening_model->getCorresLedgerSubOpeningDetails();
                $data['chkLoc']      	  = $accsubValArray['acLoc'];
                $data['chkAccCode']  	  = $accsubValArray['acAccCode'];
                $data['chkAccSubCode']	  = $accsubValArray['acSubAcc'];
                $data['chkCurr']    	  = $accsubValArray['acCurr'];
                $data['chkExchangeRate']  = $accsubValArray['acRate'];
                $data['chkBalFC']    	  = $accsubValArray['acBalFC'];
                $data['chkBalDC'] 	  	  = $accsubValArray['acBalDC'];
                $data['chkMode'] 	  	  = $accsubValArray['acMode'];
                $data['chkBalCredit']  	  = $accsubValArray['acBalCredit'];
                $data['chkBalDebit']  	  = $accsubValArray['acBalDebit'];
                $data['chkDetailID'] 	  = $accsubValArray['acDetId'];
			break;
		}//end switch
		
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('ledger_opening/edit_ledger_opening');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->ledger_opening_model->insertLedgerOpening();
				break;
				
				case "e":
					//Update into db
					$this->ledger_opening_model->updateLedgerOpening();
				break;
			}//end switch
			redirect(site_url("ledger_opening/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function delledgeropening()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LEDGEROPENINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ledger_opening_model->deleteLedgerOpening();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = LEDGEROPENINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ledger_opening_model->deleteAllLedgerOpening();
	}//end function 
	
	function filterLedger()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		$data['list_MACCode']  = $this->ledger_opening_model->genAcDlld();		
		$this->ddlLedger       = $this->input->post('ddlLedger');
		if($this->ddlLedger == 'SL')
		{
			$data['list_subledger_value'] = $this->ledger_opening_model->getSubLedgerValues();
			$this->load->view('ledger_opening/show_subledger',$data);
		}//end if
		else
		{
            $data['list_subledger_value']    = $this->ledger_opening_model->getFirstSubLedgerValue();
            $this->load->view('ledger_opening/show_subledger',$data);
		}//end else
	}//end function
	
	function filterSubDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		$this->ddlLedger   = $this->input->post('ddlLedger');
        $data['list_MACCode']  = $this->ledger_opening_model->genAcDlld();
        $this->load->view('ledger_opening/show_subdetails',$data);
	}//end function

    function filterSubValues()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
        $this->ddlLedger       = $this->input->post('ddlLedger');
        $this->ddlSubLedger    = $this->input->post('ddlSubLedger');

		if($this->ddlLedger == 'SL')
		{			//$data['list_MACCode']  = $this->ledger_opening_model->genAcDlld();
            $data['list_MACCode']  = $this->ledger_opening_model->genSubAcDlld($this->ddlSubLedger);
			$this->load->view('ledger_opening/show_subdetails',$data);
		}//end if
		else
		{
            $data['list_MACCode']  = $this->ledger_opening_model->genAcDlld();
            $this->load->view('ledger_opening/show_subdetails',$data);
		}//end else
	}//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        echo $this->ddlMACCode = $this->input->post('ddlMACCode');

        $data['list_subacc'] = $this->ledger_opening_model->filterSubAccount($this->ddlMACCode);
        $this->load->view('ledger_opening/show_sub',$data);
    }//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->ledger_opening_model->filterLocation($this->ddlDivision);
		$this->load->view('ledger_opening/show_location',$data);
	}//end function
	
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey =  $this->input->post('q');
		$ledger    =  $this->input->post('ddlLedger');
		$subLedger =  $this->input->post('ddlSubLedger');
		$items     = $this->ledger_opening_model->searchActiveFinance($searchKey,$subLedger,$ledger);
		echo json_encode($items);
	}//end function
	
	//get opening details according to the A/c code selected
	function getLedgerDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function
	
	function getBalanceMode()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlMode  = $this->input->post('ddlMode');
		$this->txtBalDC = $this->input->post('txtBalDC');

		if($this->ddlMode == 'Cr')
		{
			$this->txtBalCredit = $this->txtBalDC;	
			echo $this->txtBalCredit;
		}//end if
		else
		{
			$this->txtBalDebit  = $this->txtBalDC;	
			echo $this->txtBalDebit;
		}//end else
	}//end function
	
	//function to generate ddl
	function generateAccDDL()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->txtId  = $this->input->post('ddlSubLedger');
		
		$list_MACCode = $this->ledger_opening_model->genAcDll($this->txtId);
		
		echo form_dropdown('ddlMACCode[]', $list_MACCode, $this->ddlMACCode,'id="ddlMACCode"');
	}//end function
	
	//fucntion for updaate database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->ledger_opening_model->deleteSubAndDetails($this->subDetID);
	}//end function

	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency = $this->input->post('ddlCurrency');
        $this->txtRate 	   = $this->ledger_opening_model->getRate($this->ddlCurrency);

        echo $this->txtRate;
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
