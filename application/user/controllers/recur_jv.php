<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recur_jv extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('recurring_jv_model');
		$this->load->model('sales_closing_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = RECURRINGJV;
		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

		$countRecurrJv =  $this->recurring_jv_model->getCountRecurrJv();
		$target 			  = '.';
		$this->common_model->_paging($countRecurrJv, $target,'recur_jv/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->recurring_jv_model->getAllRecurrJv();
		$this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'recur_jv/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('recur_jv/recur_jv');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editRecurrJv()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
    $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'recur_jv/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("recur_jv/showlist/".$this->uri->segment(3).'/no_access'));
    }


		$data['list_div_value']   		 = $this->recurring_jv_model->getActiveDivision();
		$data['list_trans_value']    	 = $this->recurring_jv_model->getActiveTransactions();
		$data['list_account']	  	  	 = $this->recurring_jv_model->getActiveAccount();
		$data['list_sub_acc']	  	  	 = $this->recurring_jv_model->getActiveSubAccounts();
		$data['list_account_multiple']   = $this->recurring_jv_model->getAccountMultipleValues();
		$data['list_debit_mode']         = $this->recurring_jv_model->getDebitModeType();
        $data['list_mode']        	     = $this->recurring_jv_model->getModeType();
		$data['list_curr_value']	  	 = $this->recurring_jv_model->getActiveCurrency();
		$data['list_mloc_value']  		 = $this->recurring_jv_model->getMultipleLocation();
		
		switch($id)
		{
			case "a":
				$this->showTitle   		  = ADDNEWRECURRINGJV;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
		        $data['list_loc_value']   = $this->recurring_jv_model->getLocation();
                $this->ddlDivision	  	  = $this->input->post('ddlDivision');
                $this->ddlLocation	  	  = $this->input->post('ddlLocation');
                $this->txtDate      	  = date('d-m-Y');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
				$this->txtDocNumber  	  = $this->getDocumentNumber();
                $this->ddlModeMain 	  	  = $this->input->post('ddlModeMain');
				$this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
				$this->ddlCurrencyMain 	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
                $this->txtRefNumberMain   = $this->input->post('txtRefNumberMain');
                $this->txtRefDate     	  = $this->input->post('txtRefDate');
                $this->ddlAccount		  = $this->input->post('ddlAccount');
                $this->ddlSubAccount   	  = $this->input->post('ddlSubAccount');
                $this->txtAmountDC        = $this->input->post('txtAmountDC');
                $this->txtAmountFCMain 	  = $this->input->post('txtAmountFCMain');
                $this->txtRefName         = $this->input->post('txtRefName');
                $this->txtEntered         = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->session->userdata('AbsAdmin')."' ");
                $this->txtRemarks         = $this->input->post('txtRemarks');
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				
				$this->ddlCurrency  	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->exRate   	      = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");

                $this->chkSelect   		  = 'checked="checked"';

			    $data['chkAccCode']  	  = array();
				$data['chkAccSubCode'] 	  = array();
				$data['chkMode'] 		  = array();
				$data['chkCurr'] 	      = array();
				$data['chkRefNumber'] 	  = array();
				$data['chkAmountFC'] 	  = array();
				$data['chkExchangeRate']  = array();
				$data['chkAmount']        = array();
				$data['chkDes']           = array();
				$data['chkTable']         = array();
                $data['chkDetailID']      = array();
                $data['chkLoc']           = array();

				$data['list_subacc_multiple'] = $this->recurring_jv_model->getSubAccountValues();
			break;
			
			case "e":
				$this->showTitle      	  = EDITCASHBOOKRECEIPT;
				$this->btnVal	      	  = UPDATECASHBOOKRECEIPT;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if
                $data['list_subacc_multiple'] = $this->recurring_jv_model->getSubAccountValues();

				$showVal	          	  	  = $this->recurring_jv_model->getCorresRecurrJv();
				$this->ddlDivision	  	  	  = $showVal->nDivision;
				$this->ddlLocation	  	  	  = $showVal->nLocation;
				$this->txtDate      	  	  = date('d-m-Y',strtotime($showVal->dDate));
				$this->txtDocNumber  	  	  = $showVal->vDocument;
				$this->ddlCurrency   	  	  = $showVal->nCurrency;
				$this->txtRate  		  	  = $showVal->vExRate;
				$this->txtTRX              	  = $showVal->vTrxType;
				$this->txtAmountFC     	  	  = $showVal->vAmtFc;
				$this->txtAmountDC		  	  = $showVal->vAmtDc;
				$this->txtStartDate   	  	  = date('d-m-Y',strtotime($showVal->dStartDate));
				
				$this->txtFrequency        	  = $showVal->vFrequency;
				$this->txtNumJv      	  	  = $showVal->vNumJv;
				$this->txtEndDate         	  = date('d-m-Y',strtotime($showVal->dEndDate));
				$this->txtRefNumber        	  = $showVal->vRefNum;
				$this->txtRemarks         	  = $showVal->vRemarks;
				
		        $data['list_loc_value']   	  = $this->recurring_jv_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                 	  = $showVal->nRJId;

				//get selected details code
                $this->chkSelect   		  	  = 'checked="checked"';
				
				$setupValArray                = $this->recurring_jv_model->getCorresRecurrJvSetup();
				$data['chkLoc']  	      	  = $setupValArray['acLoc'];
			    $data['chkAccCode']  	  	  = $setupValArray['acCode'];
				$data['chkAccSubCode'] 	  	  = $setupValArray['acSubCode'];
				$data['chkMode'] 		  	  = $setupValArray['acMode'];
				$data['chkAmount']        	  = $setupValArray['acAmount'];
				$data['chkDetailID']      	  = $setupValArray['acDetailID'];

                $setupValArray                = $this->recurring_jv_model->getCorresRecurrJvSchedule();
                $data['chkscDate']  	      = $setupValArray['scDate'];
                $data['chkscStatus'] 	  	  = $setupValArray['scStatus'];
                $data['chkscId'] 		  	  = $setupValArray['scId'];			
				$data['chkjvid'] 		  	  = $setupValArray['jvid'];

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
			$this->load->view('recur_jv/edit_recur_jv');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->recurring_jv_model->insertRecurrJv();
				break;
				
				case "e":
					//Update into db
					$this->recurring_jv_model->updateRecurrJv();
				break;
			}//end switch
			redirect(site_url("recur_jv/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

    function getDocumentNumber()
    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='20'");
        $fetchCode          	= $this->db->query("SELECT vDocument FROM {PRE}RecurringJv ORDER BY nRJId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocument;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='20'");
            $countVal 			= $this->txtDocNumber;
        endif;

        return($countVal);
    }//end function

	function delRecurrJv()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = RECURRINGJV;
		$this->common_model->check_admin_authorization();
		$this->recurring_jv_model->deleteRecurrJv();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = RECURRINGJV;
		$this->common_model->check_admin_authorization();
		$this->recurring_jv_model->deleteAllRecurrJv();
	}//end function 
	
	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->recurring_jv_model->filterLocation($this->ddlDivision);
		$this->load->view('recur_jv/show_location',$data);
	}//end function

    function filterSubAccount()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');

		$data['list_subacc_multiple'] = $this->recurring_jv_model->filterSubAccount($accName,$accType);
		$this->load->view('recur_jv/show_sub',$data);
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtRate 	 = $this->recurring_jv_model->getRate($this->ddlCurrency);

        echo $this->txtRate;
    }//end function

	function convertCurrency()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->fcAmount         = $this->input->post('fcAmt');
        $this->ddlCurrencyMain  = $this->input->post('selCurrency');
        $this->selCurrency      = $this->common_model->currencyCode($this->input->post('selCurrency'));
        //default currency code
        $this->defCurrency      = $this->common_model->currencyCode($this->common_model->getCompanyCurr());

        //calculate rate
        $convRate               = $this->sales_closing_model->onlineCurrencyConverstion();

        //print_r($convRate);
        echo json_encode($convRate);
	}//end function

	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		$this->recurring_jv_model->deleteSubAndDetails($this->subDetID);
	}//end function
	
	//function
	function getJvCnt()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$date1       = $this->input->post('date1');
		$date2       = $this->input->post('date2');
		
		echo $jvCnt  = $this->recurring_jv_model->getJvCnt($date1,$date2);
	}//end function
	
	function getEndDate()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$date         = $this->input->post('date');
		$freqency     = ($this->input->post('freqency') - 1);
		
		echo $endDate = date('d-m-Y',strtotime($date ."+".$freqency."months"));
	}//end function
	
	function getDateLoop()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['date']      = $this->input->post('date');
		$data['freqency']  = $this->input->post('freqency');
		$this->catId 	   = $this->input->post('jId');
		
		if($this->uri->segment(4) == 'e')
		{
			$setupValArray                = $this->recurring_jv_model->getCorresRecurrJvSchedule();
			$data['chkscDate']  	      = $setupValArray['scDate'];
			$data['chkscStatus'] 	  	  = $setupValArray['scStatus'];
			$data['chkscId'] 		  	  = $setupValArray['scId'];
		}//end if
			
		$this->load->view('recur_jv/show_date',$data);
	}//end function

    //function for update database
    function insertJv()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->schduleID = $this->input->post('scheduleId');
        $this->recurring_jv_model->insertJv($this->schduleID);

        redirect(site_url("recur_jv/showlist/"));
    }//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
