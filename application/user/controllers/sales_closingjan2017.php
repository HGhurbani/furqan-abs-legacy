<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales_closing extends CI_Controller {

	function __construct()
	{
		header_remove('Set-Cookie');
		ini_set('memory_limit', '-1');
		parent::__construct();
		$this->load->model('sales_closing_model');
		$this->load->model('ticket_booking_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = SALESCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

		$countSalesClosing    =  $this->sales_closing_model->getCountSalesClosing();
		$target 			  = '.';
		$this->common_model->_paging($countSalesClosing, $target,'sales_closing/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->sales_closing_model->getAllSalesClosing();
		$this->count_rows     = count($data['list_value']);
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline_sc(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee_sc();
//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');

        session_start();
        $data['fetchExcelMsg'] = $_SESSION['fetchExcelMsg'];
        $_SESSION['fetchExcelMsg'] = '';

		$this->load->view('general/template_top',$data);
		$this->load->view('sales_closing/sales_closing');
		$this->load->view('general/template_bottom');
	}//end function

	function showUnclosedTicket()
	{
		header_remove('Set-Cookie');
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = SALESCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

		$countUnclosedTkt     = $this->sales_closing_model->getCountUnclosedTkt();
		$target 			  = '.';
		$this->common_model->_paging($countUnclosedTkt, $target,'sales_closing/showUnclosedTicket/'.$this->uri->segment(3));
		$data['list_value']   = $this->sales_closing_model->getAllUnclosedTkt();		
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		$data['list_airline_value'] = $this->sales_closing_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->sales_closing_model->getActiveEmployee_new(); 
        $this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');
        //$this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

        //session_start();
        //$data['fetchExcelMsg'] = $_SESSION['fetchExcelMsg'];
        //$_SESSION['fetchExcelMsg'] = '';


        $this->load->view('general/template_top',$data);
		$this->load->view('sales_closing/sales_closing1');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editSalesClosing()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
        $id                       = $this->uri->segment(4);
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/no_access'));
    }
		
		$data['list_div_value']   		 = $this->sales_closing_model->getActiveDivision();
		$data['list_type_value']	 	 = $this->sales_closing_model->getTypeValue();
		$data['list_agent_value']        = $this->sales_closing_model->getAgentAccounts();
		$data['list_type']	 	         = $this->sales_closing_model->getTicketType();
		$data['list_tkt_value']          = $this->sales_closing_model->getActiveTickets();
		$data['list_card_value']         = $this->sales_closing_model->getActiveCards();
		$data['list_invoice_status']	 = $this->sales_closing_model->getInvoiceStatus();
		$data['list_acchead_value']	     = $this->sales_closing_model->getAccountHead();
//		$data['crv_list']      	         = $this->sales_closing_model->getCRVList();
        $data['crv_list']      	         = array();
		$data['refund_list']             = $this->sales_closing_model->getRefundList();
		$data['list_dracc']	             = $this->sales_closing_model->getActiveCustomer();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWSALESCLOSING;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				
				$data['list_loc_value']   = $this->sales_closing_model->getLocation();
				$this->ddlDivision 		  = $this->input->post('ddlDivision');
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtDate      	  = date('d-m-Y');
				$this->ddlType      	  = $this->input->post('ddlType');
				$this->ddlTransaction  	  = $this->input->post('ddlTransaction');
				$this->txtDocNumber       = $this->input->post('txtDocNumber');
				//$this->txtDocNumber  	  = $this->getDocNumber();
				$this->txtLpoMain      	  = $this->input->post('txtLpoMain');
				$data['list_emp_value']     = $this->sales_closing_model->getActiveEmploye();
				$this->ddlIssued  		  = $this->input->post('ddlIssued');
				$this->ddlCurrency    	  = $this->input->post('ddlCurrency');
				$this->txtRate        	  = $this->input->post('txtRate');
				$this->ddlAgentAc  		  = $this->input->post('ddlAgentAc');
				$this->txtCTC  		  = $this->input->post('txtCTC');

				$this->txtTotalNetAmount   = $this->input->post('txtTotalNetAmount');
				$this->txtTotalServPercent = $this->input->post('txtTotalServPercent');
				$this->txtTotalDiscAmount  = $this->input->post('txtTotalDiscAmount');
				$this->txtTotalCard		   = $this->input->post('txtTotalCard');
				$this->txtTotalCommPercent = $this->input->post('txtTotalCommPercent');
				$this->txtTotalCommAmt     = $this->input->post('txtTotalCommAmt');
				$this->txtTotalSalesAmt    = $this->input->post('txtTotalSalesAmt');
				$this->txtTotalCollected   = $this->input->post('txtTotalCollected');
                $this->TotalAllocation     = $this->input->post('txtTotalAllocation');
				$this->ddlInvoice          = $this->input->post('ddlInvoice');
				$this->txtRemarks          = $this->input->post('txtRemarks');
				$this->txtInvoiceTo        = $this->input->post('txtInvoiceTo');

				$this->chkSelect2  		  = 'checked="checked"';
				$this->chkSelect   		  = 'checked="checked"';
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$this->updId	      	  = '';
				$data['chkType']    	  = array();
				$data['chkAirline'] 	  = array();
				$data['chkTktNumber'] 	  = array();
				$data['chkFare'] 		  = array();
				$data['chkTax'] 		  = array();
				$data['chkNetAmt'] 		  = array();
				$data['chkServPercent']   = array();
				$data['chkServAmt'] 	  = array();
				$data['chkDiscAmt'] 	  = array();
				$data['chkCard']  		  = array();
				$data['chkComm']          = array();
				$data['chkCommAmt']       = array();
				$data['chkSalesAmt']      = array();
				$data['chkCollected']     = array();
				$data['chkAllocation']    = array();
				$data['chkAgentAmt']      = array();
				$data['chkDrAcc']         = array();
				$data['chkLPO']           = array();
				$data['chkDetailID']      = array();

				$data['chkDrAccMain'] 	  = array();
				$data['chkCard']    	  = array();
				$data['chkCardComm'] 	  = array();
				$data['chkCardCommAmt']	  = array();
				$data['chkAmt']	          = array();
				$data['chkCRV'] 		  = array();
				$data['crv_list'][0] = NOVALUE;
				$data['chkRefTkt'] 		  = array();
				$data['chkAcHead']        = array();
				$data['chkAcId']          = array();
				break;

			case "e":
				$this->showTitle      	  = EDITSALESCLOSING;
				$this->btnVal	      	  = UPDATESALESCLOSING;
				$this->catId	      	  = $this->uri->segment(5);
				$this->updId	      	  = $this->uri->segment(6);

				$this->common_model->getPHPSelf2($_SERVER['PHP_SELF']);

				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if

				if(intval($this->input->post('nUId')) != 0)
				{
					$this->updId = intval($this->input->post('nUId'));
				}//end if

				$showVal	          	   = $this->sales_closing_model->getCorresSalesClosing();
				$this->ddlDivision	  	   = $showVal->nDivision;
				$this->ddlLocation	  	   = $showVal->vLocation;
				$this->txtDate      	   = date('d-m-Y', strtotime($showVal->dDate));
				$this->ddlType      	   = $showVal->vTransMode;
				$this->ddlTransaction  	   = $showVal->nTransaction;
				$this->txtDocNumber  	   = $showVal->vInvoiceNumber;
				$this->txtCTC  		  = $showVal->mobile;
				$this->txtLpoMain      	   = $showVal->vLPO;
//        $data['crv_list']      	         = $this->sales_closing_model->getCRV($,$showVal->vCRV);
				$this->txtCRV        	   = $showVal->vCRV;
				$this->ddlIssued  		   = $showVal->issue;
				
				$this->ddlCurrency    	   = $showVal->nCurrency;
				$this->txtRate        	   = $showVal->vRate;
				$this->ddlAgentAc  		   = $showVal->nAgentAc;
				$this->ddlIssued  		  = $showVal->issue;
				$this->txtTotalFace        = $showVal->vTotalFace;
				$this->txtTotalTax         = $showVal->vTotalTax;
				$this->txtTotalNetAmount   = $showVal->vTotalNetAmount;
				$this->txtTotalServPercent = $showVal->vTotalServPercent;
				$this->txtTotalServAmount  = $showVal->vTotalServAmount;
				$this->txtTotalDiscAmount  = $showVal->vTotalDiscAmount;
				$this->txtTotalCard		   = $showVal->vTotalCard;
				$this->txtTotalCommPercent = $showVal->vTotalCommPercent;
				$this->txtTotalCommAmt     = $showVal->vTotalCommAmt;
				$this->txtTotalSalesAmt    = $showVal->vTotalSalesAmt;
				$this->txtTotalCollected   = $showVal->vTotalCollected;
				$this->txtTotalAgentAmt    = $showVal->vTotalAgentAmt;
                $this->txtTotalAllocation  = $showVal->vTotalAllocation;
				$this->txtPassName         = $showVal->vPassName;
				$this->txtSector1          = $showVal->vSector1;
				$this->txtSector2          = $showVal->vSector2;
				$this->txtSector3          = $showVal->vSector3;
				$this->txtSector4          = $showVal->vSector4;
				$this->txtSector5          = $showVal->vSector5;
				$this->txtSegment          = $showVal->vSegment;
				$this->txtDestination      = $showVal->vDestination;
				$this->txtGds              = $showVal->vGds;
				$this->ddlInvoice          = $showVal->nInvoiceStatus;
				$this->txtRemarks          = $showVal->vRemarks;
				$this->txtInvoiceTo        = $showVal->vInvoiceTo;
				$data['list_loc_value']    = $this->sales_closing_model->getCorrActiveLocation($this->ddlDivision);
				$this->Id                  = $showVal->nSCId;
				$this->chkUrl   		   = $this->uri->segment(6);

				$this->chkSelect2  		  = 'checked="checked"';
				$this->chkSelect2  		  = 'checked="checked"';
				$this->chkSelect   		  = 'checked="checked"';
				$loc=$this->common_model->get_field_name('SalesClosing','vLocation'," WHERE nSCId='".$this->Id."'");
				$data['list_emp_value']     = $this->sales_closing_model->getActiveEmploye(); 

				if($showVal->vActive == '1')
				{
					$this->chkValYes  = 'checked="checked"';
				}//end if
				else
				{
					$this->chkValNo   = 'checked="checked"';
				}//end else

				//get selected details code
				$accValArray              = $this->sales_closing_model->getCorresSalesClosingDetails();
				$data['chkType']    	  = $accValArray ['sdType'];
				$data['chkAirline'] 	  = $accValArray ['sdAirline'];
				$data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
				$data['chkEmployee'] 	  = $accValArray ['sdEmployee'];
				$data['chkFare'] 		  = $accValArray ['sdFace'];
				$data['chkTax'] 		  = $accValArray ['sdTax'];
				$data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
				$data['chkServPercent']   = $accValArray ['sdServPercent'];
				$data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
				$data['chkDiscAmt'] 	  = $accValArray ['sdDiscAmt'];
				$data['chkCard']          = $accValArray ['sdCard'];
				$data['chkComm']          = $accValArray ['sdCommPer'];
				$data['chkCommAmt']    	  = $accValArray ['sdCommAmt'];
				$data['chkSalesAmt']  	  = $accValArray ['sdSalesAmt'];
				$data['chkCollected']  	  = $accValArray ['sdCollected'];
				$data['chkAllocation']    = $accValArray ['sdAllocation'];
				$data['chkAgentAmt']   	  = $accValArray ['sdAgentAmt'];
				$data['chkDrAcc']    	  = $accValArray ['sdDrAcc'];
				$data['chkLPO']        	  = $accValArray ['sdLPO'];
				$data['chkDetailID']      = $accValArray ['sdID'];

				//get selected Account Details
				$saleValArray             = $this->sales_closing_model->getCorresSalesClosingAccounts();
				$data['chkDrAccMain'] 	  = $saleValArray['AcAccMain'];
				$data['chkAcHead']        = $saleValArray['AcAcHead'];
				$data['chkCard']    	  = $saleValArray['AcCard'];
				$data['chkCardComm'] 	  = $saleValArray['AcCardComm'];
				$data['chkCardCommAmt']	  = $saleValArray['AcCardCommAmt'];
				$data['chkAmt']	          = $saleValArray['AcAmt'];
				$data['chkCRV'] 		  = $saleValArray['AcCRV'];
				$data['chkRefTkt'] 		  = $saleValArray['AcRefTkt'];
				$data['chkAcTable']       = $saleValArray['AcTable'];
				$data['chkAcId']          = $saleValArray['AcID'];

				$invoice_id   = @join(",",$data['chkTktNumber']);
				$invoice_type = @join(",",$data['chkType']);
				$this->session->set_userdata("selId",$invoice_id);
				$this->session->set_userdata("tType",$invoice_type);
				break;
		}//end switch
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
		{
//redirect(site_url('sales_closing/editSalesClosing/'.$this->uri->segment(3). "/".$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).'/'.$this->uri->segment(7).'/1'));
			$this->load->view('general/template_top',$data);
			$this->load->view('sales_closing/edit_sales_closing');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->sales_closing_model->insertSalesClosing();
				break;

				case "e":
					//Update into db
					$this->sales_closing_model->updateSalesClosing();
				break;
			}//end switch
			redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

    function getUnclosedSalesDetails()
    {
		header_remove('Set-Cookie');
		ini_set('memory_limit', '-1');
        $this->common_model->check_admin_authorization();
        $data['title'] = $this->common_model->get_site_title();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
        $this->btnVal	   		         = ADDCONTINUE;
        $this->btnVal2	   		         = SAVEEXIT;
		//$this->txtDate                   = strtotime($showVal->dDate);
        $this->txtDate      	         = date('d-m-Y');
        //$this->txtDate      	         = date('d-m-Y', strtotime($showVal->dDate));
		
        $id = $this->uri->segment(4);
        //User Access aa
        $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');
        $data['action_access'] = $action_access;
        if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
          if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
          else
            redirect(site_url("sales_closing/showUnclosedTicket/".$this->uri->segment(3).'/no_access'));
        }

        $data['list_div_value']   		 = $this->sales_closing_model->getActiveDivision();
        $data['list_type_value']	 	 = $this->sales_closing_model->getTypeValue();
        $data['list_dracc']	      	  	 = $this->sales_closing_model->getActiveCustomer();
        $data['list_agent_value']        = $this->sales_closing_model->getAgentAccounts();
        $data['list_type']	 	         = $this->sales_closing_model->getTicketType();
        $data['list_tkt_value']          = $this->sales_closing_model->getActiveTickets();
        $data['list_card_value']         = $this->sales_closing_model->getActiveCards();
        $data['list_invoice_status']	 = $this->sales_closing_model->getInvoiceStatus();
        $data['list_acchead_value']	     = $this->sales_closing_model->getAccountHead();
		$data['crv_list']      			 = $this->sales_closing_model->getCRVList(); 
		$data['refund_list']      		 = $this->sales_closing_model->getRefundList();

        $list_id        				 = @split(",",$this->session->userdata("selId"));
        $list_type      				 = @split(",",$this->session->userdata("tType"));
        $this->chkValYes   		 		 = 'checked="checked"';
        $this->chkValNo	   		  		 = '';
        $this->chkUrl    				 = $this->uri->segment(5);

        switch($list_type[0])
        {
            case "Ticket":
                $this->ddlDivision	  	   = $this->common_model->get_field_name('TicketBooking','nDivision'," WHERE nTId='".$list_id[0]."'");
                $this->ddlLocation	  	   = $this->common_model->get_field_name('TicketBooking','nLocation'," WHERE nTId='".$list_id[0]."'");
                $this->txtInvoiceToVal 	   = $this->common_model->get_field_name('TicketBillingDetails','nCustomerAc'," WHERE nTicketId='".$list_id[0]."'");
            break;

            case "Hotel":
                $this->ddlDivision	  	   = $this->common_model->get_field_name('HotelVoucher','nDivision'," WHERE nHVId='".$list_id[0]."'");
                $this->ddlLocation	  	   = $this->common_model->get_field_name('HotelVoucher','nLocation'," WHERE nHVId='".$list_id[0]."'");
                $this->txtInvoiceToVal	   = $this->common_model->get_field_name('HotelBillingDetails','nCustomerAc'," WHERE nHotelId='".$list_id[0]."'");
            break;

            case "Xo":
                $this->ddlDivision	  	   = $this->common_model->get_field_name('XoBooking','nDivision'," WHERE nXId='".$list_id[0]."'");
                $this->ddlLocation	  	   = $this->common_model->get_field_name('XoBooking','nLocation'," WHERE nXId='".$list_id[0]."'");
                $this->txtInvoiceToVal 	   = $this->common_model->get_field_name('XoBillingDetails','nCustomerAc'," WHERE nXoId='".$list_id[0]."'");
            break;
        }//end switch
        $this->txtInvoiceTo        = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->txtInvoiceToVal."'");
        $data['list_loc_value']    = $this->sales_closing_model->getCorrActiveLocation($this->ddlDivision);

        foreach($list_id as $key => $tktId)
        {
            $tckValArray          = $this->sales_closing_model->getCorresUnclosedSalesDetails($tktId,$list_type[$key]);
            $accValType[]    	  = $tckValArray ['sdType'];
            $accValAirline[] 	  = $tckValArray ['sdAirline'];
            $accValTktNumber[]    = $tckValArray ['sdTktNumber'];
            $accValEmployee[]     = $tckValArray ['sdEmployee'];
            $accValFaceVal[]      = $tckValArray ['sdFace'];
            $accValTaxVal[]       = $tckValArray ['sdTax'];
            $accValNetAmt[] 	  = $tckValArray ['sdNetAmt'];
            $accValServPercent[]  = $tckValArray ['sdServPercent'];
            $accValServAmt[] 	  = $tckValArray ['sdServAmt'];
            $accValDiscAmt[] 	  = $tckValArray ['sdDiscAmt'];
            $accValCard[]         = $tckValArray ['sdCard'];
            $accValComm[]         = $tckValArray ['sdCommPer'];
            $accValCommAmt[]      = $tckValArray ['sdCommAmt'];
            $accValSalesAmt[]     = $tckValArray ['sdSalesAmt'];
            $accValCollected[]    = $tckValArray ['sdCollected'];
            $accValAgentAmt[]     = $tckValArray ['sdAgentAmt'];
            $accValDrAcc[]        = $tckValArray ['sdDrAcc'];
            $accValLPO[]          = $tckValArray ['sdLPO'];
            $accValID[]           = $tckValArray ['sdID'];
		
            $accAcMain[]          = $tckValArray ['AcAccMain'];
        }//end foreach
		$this->ddlIssued	= $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode='".$accValEmployee[0]."'");
	
        $data['chkType']    	  = $accValType;
        $data['chkAccType']    	  = $accValType;
        $data['chkAirline'] 	  = $accValAirline;
        $data['chkTktNumber'] 	  = $accValTktNumber;
        $data['chkAccTktNumber']  = $accValTktNumber;
        $data['chkEmployee'] 	  = $accValEmployee;
        $data['chkFare'] 		  = $accValFaceVal;
        $data['chkTax'] 		  = $accValTaxVal;
        $data['chkNetAmt'] 		  = $accValNetAmt;
        $data['chkServPercent']   = $accValServPercent;
        $data['chkServAmt'] 	  = $accValServAmt;
        $data['chkDiscAmt'] 	  = $accValDiscAmt;
        $data['chkCard']          = $accValCard;
        $data['chkComm']          = $accValComm;
        $data['chkCommAmt']    	  = $accValCommAmt;
        $data['chkSalesAmt']  	  = $accValSalesAmt;
        $data['chkCollected']  	  = $accValCollected;
        $data['chkAgentAmt']   	  = $accValAgentAmt;
        $data['chkDrAcc']    	  = $accValDrAcc;
        $data['chkLPO']        	  = $accValLPO;
        $data['chkDetailID']      = $accValID;

        $data['chkDrAccMain'] 	  = $accAcMain;
		$data['list_emp_value']     = $this->sales_closing_model->getActiveEmploye();
// User access aa
        if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
        {
            $this->load->view('general/template_top',$data);
            $this->load->view('sales_closing/edit_sales_closing');
            $this->load->view('general/template_bottom');
        }//end if

        else
        {
            //checking segment
            switch($this->uri->segment(4))
            {
			    case "a" : 
					$this->sales_closing_model->insertSalesClosing();
                break;

                case "e" : 
					$this->sales_closing_model->updateSalesClosing();
                break;
            }//end switch
            redirect(site_url("sales_closing/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end else
    }//end function

	function delsalesclosing()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SALESCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->sales_closing_model->deleteSalesClosing();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SALESCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->sales_closing_model->deleteAllSalesClosing();
	}//end function

    function getDocNumber()
	{
        $check_sales = $this->sales_closing_model->checkTicket();
        if(count($check_sales) > 0)
		{
            $x = end($check_sales);
            $x++;
            $x;
        }//end if
        return $x;
    }//end function

    function generateDocNo()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlTransaction = $this->input->post('ddlTransaction');
        $check_sales 		  = $this->sales_closing_model->checkTicket();
        if(count($check_sales) > 0)
		{
            $x = end($check_sales);  
            $x++;
            echo $x;
        }//end if
        else
        {
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$this->ddlTransaction."'");
        }//end else
        echo $this->txtDocNumber;
    }//end function

    function getTktNumber()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlTktType       = $this->input->post('ddlTktType');
		$this->loc       = $this->input->post('ddlLocation');
        $data['list_tkt_value'] = $this->sales_closing_model->getTicket($this->ddlTktType,$this->loc);
        $this->load->view('sales_closing/show_tkt',$data);
    }//end function

    function getTktValues()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlTkt     = $this->input->post('ddlTkt');
        $this->ddlTktType = $this->input->post('ddlTktType');

        $tktDetails 	  = $this->sales_closing_model->getTicketDetails($this->ddlTkt,$this->ddlTktType);

        echo json_encode($tktDetails);
    }//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->sales_closing_model->filterLocation($this->ddlDivision);
		$this->load->view('sales_closing/show_location',$data);
	}//end function
/*	
  //get agent amount
function getAgentAmount(){

        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->agentAcc = $this->input->post('agentAcc');
        $agentDetails = $this->sales_closing_model->getAgentDetails($this->agentAcc);
        echo json_encode($agentDetails);
}
*/
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey = $this->input->post('q');
		$items     = $this->sales_closing_model->searchActiveFinance($searchKey);
		echo json_encode($items);
	}//end function
	
	//get cash receipt details according to the A/c code selected
	function getCreditVoucherDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function
	
	//get Trans Desc
	function getTranDesc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtRefName  = $this->input->post('txtRefName');
		$this->txtRemarks  = $this->input->post('txtRemarks'); 
		$this->txtTranDesc = ($this->txtRefName.' - '.$this->txtRemarks);
		echo $this->txtTranDesc;
	}//end function

	//generate subledger no according to the account head selected
	function generateSubLedgerNo()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlFinance      = $this->input->post('ddlFinance');
		if( $this->ddlFinance != '0')
			$this->txtSubCode1 = $this->sales_closing_model->getSubledgerNo($this->ddlFinance);
		
		echo $this->txtSubCode1;
	}//end function
	
 	function getTransactionDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch transaction details
		$this->ddlTransaction   = $this->input->post('ddlTransaction');
		$this->transId			= $this->common_model->get_field_name('Transactions','nId'," WHERE nId = '".$this->ddlTransaction."'");
		$this->txtDocNumber     = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId = '".$this->transId."'");
		$this->txtMode          = $this->common_model->get_field_name('Transactions','vTranmode'," WHERE nId = '".$this->transId."'");
		
		$this->load->view('sales_closing/show_transaction_details');
	}//end function
	
 	function getCurrencyDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch currency details
		$this->ddlCurrency      = $this->input->post('ddlCurrency');
		$this->transId			= $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->ddlCurrency."'");
		$this->txtRate          = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->transId."'");
		
		$this->load->view('sales_closing/show_currency_details');
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtExchange 	 = $this->sales_closing_model->getRate($this->ddlCurrency);

        echo $this->txtExchange;
    }//end function
	
	function convertCurrency()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch currency details
		$this->fcAmount         = $this->input->post('fcAmt');
		$this->ddlCurrencyMain  = $this->input->post('selCurrency');
		//selected currency code
		$this->selCurrency      = $this->common_model->currencyCode($this->input->post('selCurrency'));
		//default currency code
		$this->defCurrency      = $this->common_model->currencyCode($this->common_model->getCompanyCurr());
		
		//calculate rate
		$convRate               = $this->sales_closing_model->onlineCurrencyConverstion();
		
		//print_r($convRate);
		echo json_encode($convRate);
	}//end function

		//get card commision percentage
	function getCardBankdetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlCard     = $this->input->post('ddlCard');
		$this->txtCardBank = $this->sales_closing_model->getCommBank($this->ddlCard);
		
		echo $this->txtCardBank;
	}//end function

	//get card commision percentage
	function getCarddetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlCard     = $this->input->post('ddlCard');
		$this->txtCardComm = $this->sales_closing_model->getCommAmt($this->ddlCard);
		
		echo $this->txtCardComm;
	}//end function

	function getDocumentNumber()
	{
		header_remove('Set-Cookie');
		
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlType      ='2'; //$this->input->post('ddlType');
        $this->ddlLocation  = $this->input->post('ddlLocation');
		$this->txtDocNumber = $this->sales_closing_model->getDocNum($this->ddlType,$this->ddlLocation);
		
		echo $this->txtDocNumber;
	}//end function
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->sales_closing_model->deleteSubAndDetails($this->subDetID);
	}//end function

    function deleteAccountRecord()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->subDetID = $this->input->post('subCode');

        $this->sales_closing_model->deleteSubAccountDetails($this->subDetID);
    }//end function

	//search by airline,ticket,invoice,issued by,passenger name,from date,to date,accounts
	function filterList()
	{
		header_remove('Set-Cookie');
		ini_set('memory_limit', '-1');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SALESCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
		$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
		$this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
		$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
		$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
		$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
		$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
		$this->txtAccname  = $this->common_model->searchKeyword('txtAccname');		
		$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirline');
			$this->session->unset_userdata('txtTktName');
			$this->session->unset_userdata('txtInvoice');
			$this->session->unset_userdata('txtIssuedBy');
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtFromDate');
			$this->session->unset_userdata('txtToDate');
			$this->session->unset_userdata('txtAccname');			
			$this->session->unset_userdata('txtLocation');
			$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
			$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
			$this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
			$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
			$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
			$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
			$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
			$this->txtAccname  = $this->common_model->searchKeyword('txtAccname');			
			$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
		}//end if
		
		if(!empty($this->txtAirline))
		{
			/*$extraCheck_t = " AND (ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%') ";

            $extraCheck_x = " AND (ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%') ";
*/
			$extraCheck_t = " AND (ar.nArId='".$this->txtAirline."')";
			$extraCheck_x = " AND (ar.nArId='".$this->txtAirline."')";
            $extraCheck_h = "  ";
		}//end if
		if(!empty($this->txtLocation))        {            		
		$extraCheck_t 	.= " AND (tb.nLocation=".$this->txtLocation.") ";            
		$extraCheck_x 	.= " AND (xo.nLocation=".$this->txtLocation.") ";            
		$extraCheck_h 	.= " AND (hv.nLocation=".$this->txtLocation.") ";        
		}//end if
		if(!empty($this->txtTktName)) 
		{
			$extraCheck_t 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%') ";

            $extraCheck_x 	.= " AND (xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%') ";

            $extraCheck_h 	.= " AND (hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%') ";
		}//end if

		if(!empty($this->txtInvoice))
		{
			$extraCheck_t 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";

            $extraCheck_x 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";

            $extraCheck_h 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck_t 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%') ";

            $extraCheck_x 	.= " AND (xo.vPassenger LIKE '".$this->txtPassName." %' OR xo.vPassenger LIKE '% ".$this->txtPassName."'
									OR xo.vPassenger LIKE '%".$this->txtPassName."%') ";

            $extraCheck_h 	.= " AND (hv.vPassenger LIKE '".$this->txtPassName." %' OR hv.vPassenger LIKE '% ".$this->txtPassName."'
                                    OR hv.vPassenger LIKE '%".$this->txtPassName."%') ";
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck_t 	.=" AND (em.nEId= '".$this->txtIssuedBy."')";
			$extraCheck_x 	.=" AND (em.nEId= '".$this->txtIssuedBy."')";
			$extraCheck_h 	.=" AND (em.nEId= '".$this->txtIssuedBy."')";
			/*$extraCheck_t 	.= " AND (em.nEId LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";
            $extraCheck_x 	.= " AND (em.nEId LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";
            $extraCheck_h 	.= " AND (em.nEId LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";*/
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck_t.= " AND tb.dDate >= '".$txtFrom."' AND tb.dDate <= '".$txtTo."'";
			$extraCheck_x.= " AND xo.dDate >= '".$txtFrom."' AND xo.dDate <= '".$txtTo."'";
			$extraCheck_h.= " AND hv.dDate >= '".$txtFrom."' AND hv.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->txtAccname))
		{
			$extraCheck_t 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";

            $extraCheck_x 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";

            $extraCheck_h 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";
		}//end if

		$countFilterList	=  $this->sales_closing_model->getCountfilterList($extraCheck_t,$extraCheck_x,$extraCheck_h);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'sales_closing/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->sales_closing_model->getFilterList($extraCheck_t,$extraCheck_x,$extraCheck_h);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');

		$this->load->view('sales_closing/sales_closing_filter1',$data);
	}//end function

	function filterList2()
	{
		header_remove('Set-Cookie');
		ini_set('memory_limit', '-1');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SALESCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
		$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
		$this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
		$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
		$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
		$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
		$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
		$this->txtAccname  = $this->common_model->searchKeyword('txtAccname');		
		$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');		
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee(); 
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirline');
			$this->session->unset_userdata('txtTktName');
			$this->session->unset_userdata('txtInvoice');
			$this->session->unset_userdata('txtIssuedBy');
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtFromDate');
			$this->session->unset_userdata('txtToDate');
			$this->session->unset_userdata('txtAccname');			
			$this->session->unset_userdata('txtLocation');
			$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
			$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
			$this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
			$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
			$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
			$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
			$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
			$this->txtAccname  = $this->common_model->searchKeyword('txtAccname');			
			$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
		}//end if
		
		if(!empty($this->txtAirline))
		{
			$extraCheck_t = " AND (ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%') ";

            $extraCheck_x = " AND (ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%') ";

            $extraCheck_h = "  ";
		}				            		
		$extraCheck_t 	.= " AND (tb.nLocation=".$this->txtLocation.") ";            
		$extraCheck_x 	.= " AND (xo.nLocation=".$this->txtLocation.") ";            
		$extraCheck_h 	.= " AND (hv.nLocation=".$this->txtLocation.") ";         		
		if(!empty($this->txtTktName))
		{
			$extraCheck_t 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%') ";

            $extraCheck_x 	.= " AND (xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%') ";

            $extraCheck_h 	.= " AND (hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%') ";
		}//end if

		if(!empty($this->txtInvoice))
		{
			$extraCheck_t 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";

            $extraCheck_x 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";

            $extraCheck_h 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck_t 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%') ";

            $extraCheck_x 	.= " AND (xo.vPassenger LIKE '".$this->txtPassName." %' OR xo.vPassenger LIKE '% ".$this->txtPassName."'
									OR xo.vPassenger LIKE '%".$this->txtPassName."%') ";

            $extraCheck_h 	.= " AND (hv.vPassenger LIKE '".$this->txtPassName." %' OR hv.vPassenger LIKE '% ".$this->txtPassName."'
                                    OR hv.vPassenger LIKE '%".$this->txtPassName."%') ";
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck_t 	.= " AND (em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";
            $extraCheck_x 	.= " AND (em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";
            $extraCheck_h 	.= " AND (em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%') ";
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck_t.= " AND tb.dDate >= '".$txtFrom."' AND tb.dDate <= '".$txtTo."'";
			$extraCheck_x.= " AND xo.dDate >= '".$txtFrom."' AND xo.dDate <= '".$txtTo."'";
			$extraCheck_h.= " AND hv.dDate >= '".$txtFrom."' AND hv.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->txtAccname))
		{
			$extraCheck_t 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";

            $extraCheck_x 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";

            $extraCheck_h 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%') ";
		}//end if

		$countFilterList	= $this->sales_closing_model->getCountfilterList($extraCheck_t,$extraCheck_x,$extraCheck_h);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'sales_closing/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->sales_closing_model->getFilterList($extraCheck_t,$extraCheck_x,$extraCheck_h);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');

		$this->load->view('general/template_top',$data);
		$this->load->view('sales_closing/sales_closing1');
		$this->load->view('general/template_bottom');
	}//end function

    function closedFilterList()
    {
		ini_set('memory_limit', '-1');
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SALESCLOSINGMANAGEMENT;

        $this->common_model->check_admin_authorization();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
		$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline_sc(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee_sc();

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');
			 $this->session->unset_userdata('txtLocation');

            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
			$this->txtLocation  = $this->common_model->searchKeyword('txtLocation');
        }//end if 

        if(!empty($this->txtAirline))
        {
           /* $extraCheck = " AND (scd.nAirline LIKE '".$this->txtAirline." %' OR scd.nAirline LIKE '% ".$this->txtAirline."'
									OR scd.nAirline LIKE '%".$this->txtAirline."%') ";*/
			$extraCheck = " AND (scd.nAirline='".$this->txtAirline."')";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%') ";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								   OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' ) ";
        }//end if

		if(!empty($this->txtLocation))        {             		
		$extraCheck 	.= " AND (sc.vLocation=".$this->txtLocation.") ";            
		        
		}//end if
        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND (scd.vEmployee LIKE '".$this->txtIssuedBy." %' OR scd.vEmployee LIKE '% ".$this->txtIssuedBy."'
									OR scd.vEmployee LIKE '%".$this->txtIssuedBy."%') ";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND sc.dDate >= '".$txtFrom."' AND sc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								 OR c.vName LIKE '".$this->txtAccname." %' OR c.vName LIKE '% ".$this->txtAccname."'
									OR c.vName LIKE '%".$this->txtAccname."%') ";
        }//end if
        $countFilterList	=  $this->sales_closing_model->getCountclosedFilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'sales_closing/closedFilterList2/'.$this->uri->segment(3));
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline_sc(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee(); 
        $data['list_value'] = $this->sales_closing_model->getClosedFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');
		
        $this->load->view('sales_closing/sales_closing_filter',$data);
    }//end function

    function closedFilterList2()
    {
		
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SALESCLOSINGMANAGEMENT;

        $this->common_model->check_admin_authorization();
		$data['list_loc_value']   = $this->sales_closing_model->filLocation();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline_sc(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee_sc();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
		

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');			$this->session->unset_userdata('txtLocation');			
            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
        }//end if

        if(!empty($this->txtAirline))
        {
            $extraCheck = " AND (scd.nAirline LIKE '".$this->txtAirline." %' OR scd.nAirline LIKE '% ".$this->txtAirline."'
									OR scd.nAirline LIKE '%".$this->txtAirline."%') ";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%') ";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								   OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' ) ";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND (scd.vEmployee LIKE '".$this->txtIssuedBy." %' OR scd.vEmployee LIKE '% ".$this->txtIssuedBy."'
									OR scd.vEmployee LIKE '%".$this->txtIssuedBy."%') ";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND sc.dDate >= '".$txtFrom."' AND sc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								 OR c.vName LIKE '".$this->txtAccname." %' OR c.vName LIKE '% ".$this->txtAccname."'
									OR c.vName LIKE '%".$this->txtAccname."%') ";
        }//end if

        $countFilterList	= $this->sales_closing_model->getCountclosedFilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'sales_closing/closedFilterList2/'.$this->uri->segment(3));

        $data['list_value'] = $this->sales_closing_model->getClosedFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_closing/showUnclosedTicket');

        $this->load->view('general/template_top',$data);
        $this->load->view('sales_closing/sales_closing');
        $this->load->view('general/template_bottom');
    }//end function

    function getAccountHead()
    {
        $this->load->helper(array('form', 'url'));

        $accName                    = $this->input->post('ddlDrAccMain');
		$accType                    = $this->input->post('tableType');
        $data['list_acchead_value'] = $this->sales_closing_model->getActiveAccountHead($accName,$accType);
        $this->load->view('sales_closing/show_acchead',$data);
    }//end function

    function getCRV()
    {
		header_remove('Set-Cookie');
        $this->load->helper(array('form', 'url'));

        $accName            = $this->input->post('ddlDrAccMain');
        $accType            = $this->input->post('tableType');
        $tktNumbers         = $this->input->post('tktList');
        $tktNumType         = $this->input->post('tktTypeList');
		$loc         = $this->input->post('ddlloc');
		//$loc=$this->session->userdata('ddlLocation');
        $invAccId = $this->input->post('invaccid');
        $data['crv_list']   = $this->sales_closing_model->getCRV($accName,$accType,$invAccId,$tktNumbers,$tktNumType,$loc);
//        $data['crv_list']   = $this->sales_closing_model->getCRV($accName,$accType,$invAccId);
        $this->load->view('sales_closing/show_crv',$data);
    }//end function

    function getRefund()
    {
        $this->load->helper(array('form', 'url'));
		$loc         = $this->input->post('ddlloc');
        $accName             = $this->input->post('ddlDrAccMain');
        $accType             = $this->input->post('tableType');
        $data['refund_list'] = $this->sales_closing_model->getRefund($accName,$accType,$loc);
        $this->load->view('sales_closing/show_refund',$data);
    }//end function

    function getInvoiceTo()
    {
        $this->load->helper(array('form', 'url'));

        $accName             = $this->input->post('ddlDrAccMain');
        $accType             = $this->input->post('tableType');
        switch($accType)
        {
            case "C" :
                $typeGroup    = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$accName."'");
                break;

            case "V" :
                $typeGroup    = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$accName."'");
                break;

            case "AG" :
                $typeGroup    = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$accName."'");
                break;

            case "O" :
                $typeGroup    = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$accName."'");
                break;

            case "A" :
                $typeGroup    = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$accName."'");
                break;
        }//end switch
        echo $typeGroup;
    }//end function

    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
		$this->catId   = $this->uri->segment(3);

        $showVal	          	   = $this->sales_closing_model->getCorresSalesClosing2();
        $this->ddlDivision	  	   = $showVal->nDivision;
        $this->ddlLocation	  	   = $showVal->vLocation;
        $this->txtDate      	   = date('d-m-Y', strtotime($showVal->dDate));
        $this->ddlType      	   = $showVal->vTransMode;
        $this->ddlTransaction  	   = $showVal->nTransaction;
        $this->txtDocNumber  	   = $showVal->vInvoiceNumber;

        $this->issue  	   = $showVal->eid;
		$this->txtLpoMain      	   = $showVal->vLPO;
        $this->txtCRV        	   = $showVal->vCRV;
        $this->ddlIssued  		   = $showVal->vIssuedBy;
        $this->ddlCurrency    	   = $showVal->nCurrency;
        $this->txtRate        	   = $showVal->vRate;
        $this->ddlAgentAc  		   = $showVal->nAgentAc;

        $this->txtTotalFace        = $showVal->vTotalFace;
        $this->txtTotalTax         = $showVal->vTotalTax;
        $this->txtTotalNetAmount   = $showVal->vTotalNetAmount;
        $this->txtTotalServPercent = $showVal->vTotalServPercent;
        $this->txtTotalServAmount  = $showVal->vTotalServAmount;
        $this->txtTotalDiscAmount  = $showVal->vTotalDiscAmount;
        $this->txtTotalCard		   = $showVal->vTotalCard;
        $this->txtTotalCommPercent = $showVal->vTotalCommPercent;
        $this->txtTotalCommAmt     = $showVal->vTotalCommAmt;
        $this->txtTotalSalesAmt    = $showVal->vTotalSalesAmt;
        $this->txtTotalCollected   = $showVal->vTotalCollected;
        $this->txtTotalAgentAmt    = $showVal->vTotalAgentAmt;

        $this->txtPassName         = $showVal->vPassName;
        $this->txtSector1          = $showVal->vSector1;
        $this->txtSector2          = $showVal->vSector2;
        $this->txtSector3          = $showVal->vSector3;
        $this->txtSector4          = $showVal->vSector4;
        $this->txtSector5          = $showVal->vSector5;
        $this->txtSegment          = $showVal->vSegment;
        $this->txtDestination      = $showVal->vDestination;
        $this->txtGds              = $showVal->vGds;
        $this->ddlInvoice          = $showVal->nInvoiceStatus;
        $this->txtRemarks          = $showVal->vRemarks;
        $this->txtInvoiceTo        = $showVal->vInvoiceTo;
        $data['list_loc_value']    = $this->sales_closing_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                  = $showVal->nSCId;
        $this->chkUrl   		   = $this->uri->segment(6);

        $this->chkSelect2  		  = 'checked="checked"';
        $this->chkSelect   		  = 'checked="checked"';

        if($showVal->vActive == '1')
        {
            $this->chkValYes  = 'checked="checked"';
        }//end if
        else
        {
            $this->chkValNo   = 'checked="checked"';
        }//end else

        //get selected details code
        $accValArray              = $this->sales_closing_model->getCorresSalesClosingDetails2();
        $data['chkType']    	  = $accValArray ['sdType'];
        $data['chkAirline'] 	  = $accValArray ['sdAirline'];
        $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
        $data['chkEmployee'] 	  = $accValArray ['sdEmployee'];
        $data['chkFare'] 		  = $accValArray ['sdFace'];
        $data['chkTax'] 		  = $accValArray ['sdTax'];
        $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
        $data['chkServPercent']   = $accValArray ['sdServPercent'];
        $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
        $data['chkDiscAmt'] 	  = $accValArray ['sdDiscAmt'];
        $data['chkCard']          = $accValArray ['sdCard'];
        $data['chkComm']          = $accValArray ['sdCommPer'];
        $data['chkCommAmt']    	  = $accValArray ['sdCommAmt'];
        $data['chkSalesAmt']  	  = $accValArray ['sdSalesAmt'];
        $data['chkCollected']  	  = $accValArray ['sdCollected'];
        $data['chkAgentAmt']   	  = $accValArray ['sdAgentAmt'];
        $data['chkDrAcc']    	  = $accValArray ['sdDrAcc'];
        $data['chkLPO']        	  = $accValArray ['sdLPO'];
        $data['chkDetailID']      = $accValArray ['sdID'];

        //get selected Account Details
        $saleValArray             = $this->sales_closing_model->getCorresSalesClosingAccounts2();
        $data['chkDrAccMain'] 	  = $saleValArray['AcAccMain'];
        $data['chkAcHead']        = $saleValArray['AcAcHead'];
        $data['chkCard']    	  = $saleValArray['AcCard'];
        $data['chkCardComm'] 	  = $saleValArray['AcCardComm'];
        $data['chkCardCommAmt']	  = $saleValArray['AcCardCommAmt'];
        $data['chkAmt']	          = $saleValArray['AcAmt'];
        $data['chkCRV'] 		  = $saleValArray['AcCRV'];
        $data['chkRefTkt'] 		  = $saleValArray['AcRefTkt'];
        $data['chkAcTable']       = $saleValArray['AcTable'];
    
        //$mydata = $this->load->view('sales_closing/export_files/print_sales_closing',$data,true);
        $_SESSION['print_sales_closing'] = $mydata;
        $_SESSION['print_sales_closing_pgSz'] = 'Letter';
        $_SESSION['print_sales_closing_lp'] = 'P';
        $data['chkAcId']          = $saleValArray['AcID'];

        $this->load->view('sales_closing/print_sales_closing',$data);
    }//end function

  function getAllAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_dracc'] = $this->sales_closing_model->getActiveCustomer();
    $this->load->view('sales_closing/show_allAccounts',$data);

  }
    function getAgentAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_agent_value'] = $this->common_model->getAgentAccounts();
      $this->load->view('sales_closing/show_agentAccounts',$data);

    }


  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('sales_closing/show_emailDetails',$data);

  }
  function getIssuedBy()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_emp_value'] = $this->common_model->getIssuedBy();

      $this->load->view('sales_closing/show_issuedBy',$data);

    }


  function getAgentAmount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $ddlAgentAc = $this->input->post('ddlAgentAc');
    echo $this->sales_closing_model->getAgentAmount($ddlAgentAc); 
  }


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
