<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Xo_booking extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('xo_booking_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = XOBOOKINGMANAGEMENT;
        $this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

        //site Name
        $target 				 = '.';
        $this->common_model->_paging($this->xo_booking_model->getAllXobookingCount(), $target,'xo_booking/showlist/'.$this->uri->segment(3));
        $data['list_value'] 	 = $this->xo_booking_model->getAllXobooking();
	$data['list_airline_value'] = $this->xo_booking_model->getActiveAirline(); 
	$data['list_emp_value']     = $this->xo_booking_model->getActiveEmployee(); 
	$data['list_gds_value']     = $this->xo_booking_model->getActiveGDS(); 
	$data['list_loc_value']     = $this->xo_booking_model->getActiveLocations(); 

	$data['list_type_value'] = $this->xo_booking_model->getSearchActiveType();
		
        $this->count_rows   	 = count($data['list_value']);

//User Access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'xo_booking/showlist');

        $this->load->view('general/template_top',$data);
        $this->load->view('xo_booking/xo_booking');
        $this->load->view('general/template_bottom');
    }//end function

    function editXobooking()
    {
        $this->common_model->check_admin_authorization();
        //site Name
        $data['title'] = $this->common_model->get_site_title();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
//User Access aa
		$id 						  = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'xo_booking/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if($this->uri->segment(6) == 'closed')
        $backUrlAddr = trim($this->session->userdata('backURL2'));
      else
        $backUrlAddr = trim($this->session->userdata('backURL'));

      if( $backUrlAddr != '')
        redirect(site_url($backUrlAddr));
      else
        redirect(site_url("xo_booking/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_div_value']	      = $this->xo_booking_model->getActiveDivision();
        $data['list_trans_value']     = $this->xo_booking_model->getActiveTransactions();
        $data['list_airline_value']   = $this->xo_booking_model->getActiveAirline();
		$data['list_tkt_value']       = $this->xo_booking_model->getActiveStock();
        $data['list_gds_value']       = $this->xo_booking_model->getActiveGDS();
        $data['list_type_value']      = $this->xo_booking_model->getActiveType();
        $data['list_emp_value']       = $this->xo_booking_model->getActiveEmployee();
        $data['list_pass_value']      = $this->xo_booking_model->getActiveCustomer();
        $data['list_pass']            = $this->xo_booking_model->getActiveCustomer();
        $data['list_curr_value']      = $this->xo_booking_model->getActiveCurrency();
        $data['list_mode_value']  	  = $this->xo_booking_model->getModeType();
        $data['list_cust_value']  	  = $this->xo_booking_model->getActiveCustomer();
        $data['list_agent_value'] 	  = $this->xo_booking_model->getActiveAgent();
		$data['list_custacc_value']   = $this->xo_booking_model->getActiveCustomerAccounts();
        $data['list_agentacc_value']  = $this->xo_booking_model->getAgentAccounts();
        $data['list_account_name']    = $this->xo_booking_model->getAccounts();
        $data['list_account_value']   = $this->xo_booking_model->getAccounts();
        switch($id)
        {
            case "a":
                $this->showTitle  	  	  	   = ADDNEWXOBOOKING;
                $this->btnVal	  	  	  	   = ADDCONTINUE;
                $this->btnVal2	  	  	  	   = SAVEEXIT;

                $data['list_acchead_value']   = $this->xo_booking_model->getAccountHead();
				$data['list_loc_value']        = $this->xo_booking_model->getLocation();
				$this->ddlDivision 	           = $this->input->post('ddlDivision');
				$this->ddlLocation  	       = $this->input->post('ddlLocation');
                $this->txtDate  	     	   = date('d-m-Y');;
                $this->ddlTrans  	  	  	   = $this->input->post('ddlTrans');
                $this->txtDocNumber  	       = $this->getAutomaticDocNumber();
                $this->ddlType  	  	  	   = $this->input->post('ddlType');
                $this->ddlAirline   	  	   = $this->input->post('ddlAirline');
				$this->ddlTktname   	       = $this->input->post('ddlTktname');
                $this->ddlGDS  	  	  	  	   = $this->input->post('ddlGDS');
                $this->ddlBooked   	  	  	   = $this->input->post('ddlBooked');
                $this->ddlIssued 	  	  	   = $this->input->post('ddlIssued');
				$this->txtPassengerId   	   = $this->input->post('txtPassengerId');
                $this->txtPassengerCon  	   = $this->input->post('txtPassengerCon');
				$this->txtPassRemarks   	   = $this->input->post('txtPassRemarks');
                $this->txtOriginalXo 	  	   = $this->input->post('txtOriginalXo');
                $this->txtSegment 	  	  	   = $this->input->post('txtSegment');
                $this->txtDestination 	  	   = $this->input->post('txtDestination');
                $this->txtGdsPNR 	      	   = $this->input->post('txtGdsPNR');
                $this->txtAirPNR 	      	   = $this->input->post('txtAirPNR');
                $this->ddlCurrency 	      	   = $this->input->post('ddlCurrency');
                $this->txtRate 	      	  	   = $this->input->post('txtRate');

                //get xo details
                $this->txtCarrier 	  	  	   = $this->input->post('txtCarrier');
                $this->txtFlightNo 	  	  	   = $this->input->post('txtFlightNo');
                $this->txtClass  	  	  	   = $this->input->post('txtClass');
                $this->txtTravelDate 	  	   = $this->input->post('txtTravelDate');
                $this->txtSector  	  	  	   = $this->input->post('txtSector');
                $this->txtSector2 	  	  	   = $this->input->post('txtSector2');
                $this->txtStatus 	  	  	   = $this->input->post('txtStatus');
                $this->txtTimeDep  	  	 	   = $this->input->post('txtTimeDep');
                $this->txtTimeArr 	  	  	   = $this->input->post('txtTimeArr');
                $this->txtDay  	  	  	  	   = $this->input->post('txtDay');

                //get pass details
                $this->ddlPassenger 	  	   = $this->input->post('ddlPassenger');
                $this->txtFcTax 	  	  	   = $this->input->post('txtFcTax');
                $this->txtCommTax 	  	  	   = $this->input->post('txtCommTax');
                $this->txtCommAmt 	  	  	   = $this->input->post('txtCommAmt');
                $this->txtORCAmt    	  	   = $this->input->post('txtORCAmt');
                $this->txtFc1Tax   	      	   = $this->input->post('txtFc1Tax');
                $this->txtFc2Tax     	  	   = $this->input->post('txtFc2Tax');
                $this->txtFc3Tax   	  	  	   = $this->input->post('txtFc3Tax');
                $this->txtTotalTax 	  	  	   = $this->input->post('txtTotalTax');
                $this->txtNetVendor   	  	   = $this->input->post('txtNetVendor');
                $this->txtXoSalesAmt   	  	   = $this->input->post('txtXoSalesAmt');

                $this->ddlAccount 	  	  	   = $this->input->post('ddlAccount');
                $this->txtRemarks 	  	  	   = $this->input->post('txtRemarks');
                $this->txtFareTotalAmount 	   = $this->input->post('txtFareTotalAmount');
                $this->txtTax1TotalAmount 	   = $this->input->post('txtTax1TotalAmount');
                $this->txtTax2TotalAmount 	   = $this->input->post('txtTax2TotalAmount');
                $this->txtTax3TotalAmount 	   = $this->input->post('txtTax3TotalAmount');
                $this->txtTotalTaxAmount  	   = $this->input->post('txtTotalTaxAmount');
                $this->txtTotalNetVendorAmount = $this->input->post('txtTotalNetVendorAmount');

                //get billing details
                $this->ddlMode 	  		  	   = $this->input->post('ddlMode');
                $this->ddlAccountName 	  	   = $this->input->post('ddlAccountName');
                $this->txtSubAcName 	  	   = $this->input->post('txtSubAcName');
                $this->ddlAgentAc 	  	  	   = $this->input->post('ddlAgentAc');
                $this->txtLPO   		  	   = $this->input->post('txtLPO');
                $this->txtInvoice 	  	  	   = $this->input->post('txtInvoice');
                $this->txtSalesAmt   	  	   = $this->input->post('txtSalesAmt');
                $this->txtDiscount       	   = $this->input->post('txtDiscount');
                $this->txtDiscountAmt     	   = $this->input->post('txtDiscountAmt');
                $this->txtAgentAmt 	  	  	   = $this->input->post('txtAgentAmt');
                $this->txtCollected 	  	   = $this->input->post('txtCollected');
                $this->txtServicePerc 	  	   = $this->input->post('txtServicePerc');
                $this->txtServiceAmt 	 	   = $this->input->post('txtServiceAmt');
                $this->txtTktName		       = $this->input->post('txtTktName');

                $this->chkValYes  	  	  	   = 'checked="checked"';
                $this->chkValNo	  	  	  	   = '';
                $this->catId	  	  	  	   = '';
                $data['chkAccID']    	  	   = array();
                break;

            case "e":
                $this->showTitle  	  	  		= EDITXOBOOKING;
                $this->btnVal	      	  		= UPDATEXOBOOKING;
                $this->catId	      	  		= $this->uri->segment(5);

                if(intval($this->input->post('nId')) != 0)
                {
                    $this->catId = intval($this->input->post('nId'));
                }//end if

                if(trim($this->input->post('nCheck')) != '')
                {
                    $this->nCheck = $this->input->post('nCheck');
                }//end if

                $showVal	          	  		= $this->xo_booking_model->getCorresXobooking();
                $this->ddlDivision	  	 		= $showVal->nDivision;
                $this->ddlLocation		  		= $showVal->nLocation;
                $this->txtDate  	  	  		=  date('d-m-Y', strtotime($showVal->dDate));
                $this->ddlTrans  	  	  		= $showVal->nTransaction;
                $this->txtDocNumber       		= $showVal->vDocNumber;
                $this->ddlAirline   	  	    = $showVal->nAirline;
				$this->ddlTktname   	        = $showVal->vXoNumber;
                $this->ddlGDS 		      		= $showVal->nGDS;
                $this->ddlType	     	  		= $showVal->vType;
                $this->ddlBooked     	  		= $showVal->vBookedBy;
                $this->ddlIssued       	  		= $showVal->vIssuedBy;
				$this->txtPassengerId   	    = $showVal->vPassengerId;
                $this->txtPassengerCon          = $showVal->vPassenger;
				$this->txtPassRemarks   	    = $showVal->vPassRemarks;
                $this->txtTktName		        = $showVal->vTicktManual;

                $this->ddlPassenger		  		= $showVal->nPassenger;
                $this->txtOriginalXo	  		= $showVal->vOriginalXo;
                $this->txtSegment		  		= $showVal->vSegment;
                $this->txtDestination	  		= $showVal->vDestination;
                $this->txtGdsPNR		  		= $showVal->vGdspnr;
                $this->txtAirPNR		  		= $showVal->vAirpnr;
                $this->ddlCurrency		  		= $showVal->nCurrency;
                $this->txtRate   		  		= $showVal->nRate;

                $this->ddlAccount		        = $showVal->nPassengerAC;
                $this->txtRemarks	            = $showVal->vRemarks;
                $this->txtFareTotalAmount		= $showVal->vGrandFare;
                $this->txtTax1TotalAmount	    = $showVal->vGrandTax1;
                $this->txtTax2TotalAmount		= $showVal->vGrandTax2;
                $this->txtTax3TotalAmount		= $showVal->vGrandTax3;
                $this->txtTotalTaxAmount		= $showVal->vGrandToalTax;
                $this->txtTotalNetVendorAmount  = $showVal->vGrandNetFare;
				$this->txtTotalNetVendor1       = $showVal->vGrandNetFare;

                $data['list_loc_value']         = $this->xo_booking_model->getCorrActiveLocation($this->ddlDivision);

                $data['list_acchead_value']     = $this->xo_booking_model->getActiveAccHead($this->ddlAccount);
                $data['close_status']           = $this->xo_booking_model->getCloseStatus();
                $data['invoice_number']         = $this->xo_booking_model->getInvoiceNumber();
				$data['invoice_id']         = $this->xo_booking_model->getInvoiceid();
                $data['refund_status']          = $this->xo_booking_model->getRefundStatus();
                $data['refund_number']          = $this->xo_booking_model->getRefundInvoiceNumber();

                //get selected xo details
                $xoDetValArray             = $this->xo_booking_model->getCorresXoDetails();

                $data['txtCarrier']        = $xoDetValArray['xoCarrier'];
                $data['txtFlightNo']       = $xoDetValArray['xoFlight'];
                $data['txtClass']          = $xoDetValArray['xoClass'];
                $data['txtTravelDate']     = $xoDetValArray['xoTravelDate'];
                $data['txtSector']         = $xoDetValArray['xoSector1'];
                $data['txtSector2']        = $xoDetValArray['xoSector2'];
                $data['txtStatus']         = $xoDetValArray['xoStatus'];
                $data['txtTimeDep']        = $xoDetValArray['xoTimeDep'];
                $data['txtTimeArr']        = $xoDetValArray['xoTimeArr'];
                $data['txtDay']            = $xoDetValArray['xoDay'];
                $data['chkAccID']    	   = $xoDetValArray['xoId'];

                //get selected xo pass details
                $xoPassValArray            = $this->xo_booking_model->getCorresPassDetails();

                $data['ddlPassenger']      = $xoPassValArray['xoPass'];
                $data['txtFcTax']          = $xoPassValArray['xoFcTax'];
                $data['txtCommTax']        = $xoPassValArray['xoCommTax'];
                $data['txtORCAmt']         = $xoPassValArray['xoORCAmt'];
                $data['txtFc1Tax']         = $xoPassValArray['xoFc1Tax'];
                $data['txtFc2Tax']         = $xoPassValArray['xoFc2Tax'];
                $data['txtFc3Tax']         = $xoPassValArray['xoFc3Tax'];
                $data['txtTotalTax']       = $xoPassValArray['xoTotalTax'];
                $data['txtNetVendor']      = $xoPassValArray['xoNetVendor'];
                $data['txtXoSalesAmt']      = $xoPassValArray['xoSalesAmt'];
                $data['chkPassID']         = $xoPassValArray['xoPId'];
				
                //get selected xo billing details
                $showBillingVal			   = $this->xo_booking_model->getCorresBillingDetails();
                $this->txtInvoice          = $showBillingVal->vInvoice;
                $this->ddlMode       	   = $showBillingVal->vModeofpay;
                $this->ddlAccountName      = $showBillingVal->nAccName;
				$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
                $this->txtSubAcName        = $showBillingVal->vSubAcName;
                $this->ddlAgentAc          = $showBillingVal->nAgentAc;
                $this->txtAgentAmt         = $showBillingVal->vAgentAmt;
                $this->txtLPO              = $showBillingVal->vLPO;
                $this->txtSalesAmt         = $showBillingVal->vSalesAmt;
                $this->txtDiscount         = $showBillingVal->vDiscount;
                $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
                $this->txtCollected        = $showBillingVal->vCollected;
                $this->txtServicePerc      = $showBillingVal->vServicePercent;
                $this->txtServiceAmt       = $showBillingVal->vServiceAmt;
                $this->chkTktBillingID     = $showBillingVal->nXBId;

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

//User access aa
        if ($this->form_validation->run() == FALSE ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
        {
            $this->load->view('general/template_top',$data);
            $this->load->view('xo_booking/edit_xo_booking');
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
                    $this->xo_booking_model->insertXobooking();
                    break;

                case "e":
                    //Update into db
                    $this->xo_booking_model->updateXobooking();
                    break;
            }//end switch

            redirect(site_url("xo_booking/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end else
    }//end function

    //get document number when selected transaction
    function generateDocNo()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlTrans     = $this->input->post('ddlTrans');
        $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$this->ddlTrans."'");
        echo $this->txtDocNumber;
    }//end function

    //get currency rate when selected currency
    function generateCurrRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlCurrency = $this->input->post('ddlCurrency');
        $this->txtRate	   = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->ddlCurrency."'");
        echo $this->txtRate;
    }//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		$this->ddlTktname  = $this->input->post('ddlTktname');
		
		$data['list_loc_value'] = $this->xo_booking_model->filterLocation($this->ddlDivision);
        $this->load->view('xo_booking/show_location',$data);
	}//end function

    function delxobooking()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = XOBOOKINGMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->xo_booking_model->deleteXobooking();
    }//end function

    function deleteRecord()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->passID = $this->input->post('subCode');
        $this->xo_booking_model->deletePassDetails($this->passID);
    }//end function

    function delrecords()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = XOBOOKINGMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->xo_booking_model->deleteAllXobooking();
    }//end function

    function checkEmail()
    {
        switch($this->uri->segment(3))
        {
            case "":
                $extraCond = '';
            break;

            default:
                $extraCond = " AND nCId != '".$this->uri->segment(3)."'";
            break;
        }//end switch
        $this->txtEmail = $this->input->post('txtEmail');
        $txtEmail       = $this->common_model->get_field_name('XoBooking','vEmail'," WHERE vEmail='".mysql_real_escape_string($this->txtEmail)."' 
																".$extraCond." ");
        if($txtEmail == $this->txtEmail)
        {
            echo "false";
        }//end if
        else
        {
            echo "true";
        }//end else
    }//end function

    function checkCustcode()
    {
        switch($this->uri->segment(3))
        {
            case "":
                $extraCond = '';
                break;

            default:
                $extraCond = " AND nCId != '".$this->uri->segment(3)."'";
                break;
        }//end switch
        $this->txtCode = $this->input->post('txtCode');
        $txtCode       = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' 
																".$extraCond." ");
        if($txtCode == $this->txtCode)
        {
            echo "false";
        }//end if
        else
        {
            echo "true";
        }//end else
    }//end function

	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey = $this->input->post('q');
		$items     = $this->xo_booking_model->searchActiveTicketStock($searchKey);
		echo json_encode($items);
	}//end function
	
	//get airline details 
	function airlinesuggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey = $this->input->post('q');
		$items     = $this->xo_booking_model->searchActiveAirline($searchKey);
		echo json_encode($items);											
	}//end function

	function getAutomaticDocNumber()
	{
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='6'");
		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}XoBooking ORDER BY nXId DESC LIMIT 0,1");
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
			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='6'");
			$countVal 			= $this->txtDocNumber;
		endif;
		
		return($countVal);
	}//end function

	//get airline alpha in tkt details
	function checkAirlineAlpha()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$this->txtAirlinecode = $this->input->post('txtAirlinecode');
		$this->txtAlpha       = $this->common_model->get_field_name('Airline','vAlpha'," WHERE nArId = '".$this->txtAirlinecode."'"); 
		$this->txtCarrier1    = $this->txtAlpha;
		echo $this->txtCarrier1;
	}//end function 

	//get xo booking details according to the xo number selected
	function checkXoDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->btnVal	  	  	     = ADDCONTINUE;
		$this->btnVal2	  	  	     = SAVEEXIT;

		$data['list_div_value']	      = $this->xo_booking_model->getActiveDivision();
        $data['list_trans_value']     = $this->xo_booking_model->getActiveTransactions();
        $data['list_airline_value']   = $this->xo_booking_model->getActiveAirline();
        $data['list_gds_value']       = $this->xo_booking_model->getActiveGDS();
        $data['list_type_value']      = $this->xo_booking_model->getActiveType();
        $data['list_emp_value']       = $this->xo_booking_model->getActiveEmployee();
        $data['list_pass_value']      = $this->xo_booking_model->getActiveCustomer();
        $data['list_pass']            = $this->xo_booking_model->getActiveCustomer();
        $data['list_curr_value']      = $this->xo_booking_model->getActiveCurrency();
        $data['list_mode_value']  	  = $this->xo_booking_model->getModeType();
        $data['list_cust_value']  	  = $this->xo_booking_model->getActiveCustomer();
        $data['list_agent_value'] 	  = $this->xo_booking_model->getActiveAgent();
		$data['list_custacc_value']   = $this->xo_booking_model->getActiveCustomerAccounts();
        $data['list_agentacc_value']  = $this->xo_booking_model->getAgentAccounts();
        $data['list_account_name']    = $this->xo_booking_model->getAccounts();
        $data['list_account_value']   = $this->xo_booking_model->getAccounts();
		$data['list_tkt_value']       = $this->xo_booking_model->getActiveStock();

		$this->txtCopyXo           = $this->input->post('txtCopyXo');
		$this->tktId	  		   = $this->common_model->get_field_name('XoBooking','nXId',
																 " WHERE vTicktManual = ".$this->txtCopyXo." ");
		$showCopyVal               = $this->xo_booking_model->getSelectedXoDetails($this->tktId);

		$this->ddlDivision	  	   = $showCopyVal->nDivision;
		$this->ddlLocation	       = $showCopyVal->nLocation;
		$this->txtDate  	  	   = $showCopyVal->dDate;
		$this->ddlTrans  	  	   = $showCopyVal->nTransaction;
		$this->txtDocNumber        = $showCopyVal->vDocNumber;
		$this->ddlAirline   	   = $showCopyVal->nAirline;
		$this->ddlTktname   	   = $showCopyVal->vXoNumber;
		$this->ddlGDS 		       = $showCopyVal->nGDS;
		$this->ddlType	     	   = $showCopyVal->vType;
		$this->ddlBooked     	   = $showCopyVal->vBookedBy;
		$this->ddlIssued       	   = $showCopyVal->vIssuedBy;
		$this->txtOriginalTkt	   = $showCopyVal->vOriginalTkt;
		$this->txtGdsPNR		   = $showCopyVal->vGdspnr;
		$this->txtAirPNR		   = $showCopyVal->vAirpnr;
		$this->ddlCurrency		   = $showCopyVal->nCurrency;
		$this->txtRate   		   = $showCopyVal->nRate;
		$this->txtPassengerId      = $showCopyVal->vPassengerId;
		$this->txtPassengerCon     = $showCopyVal->vPassenger;
		$this->txtPassRemarks      = $showCopyVal->vPassRemarks;

		$data['list_loc_value']    = $this->xo_booking_model->getCorrActiveLocation($this->ddlDivision);

		//get selected xo details
		$xoDetValArray             = $this->xo_booking_model->getCorrescopyXoDetails($this->tktId);

		$data['txtCarrier']        = $xoDetValArray['xoCarrier'];
		$data['txtFlightNo']       = $xoDetValArray['xoFlight'];
		$data['txtClass']          = $xoDetValArray['xoClass'];
		$data['txtTravelDate']     = $xoDetValArray['xoTravelDate'];
		$data['txtSector']         = $xoDetValArray['xoSector1'];
		$data['txtSector2']        = $xoDetValArray['xoSector2'];
		$data['txtStatus']         = $xoDetValArray['xoStatus'];
		$data['txtTimeDep']        = $xoDetValArray['xoTimeDep'];
		$data['txtTimeArr']        = $xoDetValArray['xoTimeArr'];
		$data['txtDay']            = $xoDetValArray['xoDay'];
		$data['chkAccID']    	   = $xoDetValArray['xoId'];

		$this->txtSegment		   = $showCopyVal->vSegment;
		$this->txtDestination	   = $showCopyVal->vDestination;

		//get selected xo pass details
		$xoPassValArray            = $this->xo_booking_model->getCorrescopyPassDetails($this->tktId);

		$data['ddlPassenger']      = $xoPassValArray['xoPass'];
		$data['txtFcTax']          = $xoPassValArray['xoFcTax'];
		$data['txtCommTax']        = $xoPassValArray['xoCommTax'];
		$data['txtORCAmt']         = $xoPassValArray['xoORCAmt'];
		$data['txtFc1Tax']         = $xoPassValArray['xoFc1Tax'];
		$data['txtFc2Tax']         = $xoPassValArray['xoFc2Tax'];
		$data['txtFc3Tax']         = $xoPassValArray['xoFc3Tax'];
		$data['txtTotalTax']       = $xoPassValArray['xoTotalTax'];
		$data['txtNetVendor']      = $xoPassValArray['xoNetVendor'];
		$data['txtXoSalesAmt']      = $xoPassValArray['xoSalesAmt'];
		$data['chkPassID']         = $xoPassValArray['xoPId'];

		$this->ddlAccount		   = $showCopyVal->nPassengerAC;
		$this->txtRemarks	       = $showCopyVal->vRemarks;
		$this->txtFareTotalAmount  = $showCopyVal->vGrandFare;
		$this->txtTax1TotalAmount  = $showCopyVal->vGrandTax1;
		$this->txtTax2TotalAmount  = $showCopyVal->vGrandTax2;
		$this->txtTax3TotalAmount  = $showCopyVal->vGrandTax3;
		$this->txtTotalTaxAmount   = $showCopyVal->vGrandToalTax;
		$this->txtTotalNetVendorAmount = $showCopyVal->vGrandNetFare;
		$this->txtTotalNetVendor1  = $showCopyVal->vGrandNetFare;

        $showBillingVal			   = $this->xo_booking_model->getCorresCopyBillingDetails($this->tktId);
        $this->txtInvoice          = $showBillingVal->vInvoice;
        $this->ddlMode       	   = $showBillingVal->vModeofpay;
        $this->ddlAccountName      = $showBillingVal->nAccName;
        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
        $this->txtSubAcName        = $showBillingVal->vSubAcName;
        $this->ddlAgentAc          = $showBillingVal->nAgentAc;
        $this->txtAgentAmt         = $showBillingVal->vAgentAmt;
        $this->txtLPO              = $showBillingVal->vLPO;
        $this->txtSalesAmt         = $showBillingVal->vSalesAmt;
        $this->txtDiscount         = $showBillingVal->vDiscount;
        $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
        $this->txtCollected        = $showBillingVal->vCollected;
        $this->txtServicePerc      = $showBillingVal->vServicePercent;
        $this->txtServiceAmt       = $showBillingVal->vServiceAmt;
        $this->chkTktBillingID     = $showBillingVal->nXBId;

		$this->load->view('xo_booking/show_copyticket',$data);
	}//end function

	//search by airline,ticket,invoice,issued by,passenger name,from date,to date,accounts
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = XOBOOKINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
		$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
		$this->txtGds      = $this->common_model->searchKeyword('txtGds');
		$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
		$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
		$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
		$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
		$this->txtPnr      = $this->common_model->searchKeyword('txtPnr');
//		$this->txtSector   = $this->common_model->searchKeyword('txtSector');
		$this->ddlType     = $this->common_model->searchKeyword('ddlType');
		$this->ddlLocation     = $this->common_model->searchKeyword('ddlLocation');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirline');
			$this->session->unset_userdata('txtTktName');
			$this->session->unset_userdata('txtGds');
			$this->session->unset_userdata('txtIssuedBy');
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtFromDate');
			$this->session->unset_userdata('txtToDate');
			$this->session->unset_userdata('txtPnr');
//			$this->session->unset_userdata('txtSector');
			$this->session->unset_userdata('ddlType');
			$this->session->unset_userdata('ddLocation');
			
			$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
			$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
			$this->txtInvoice  = $this->common_model->searchKeyword('txtGds');
			$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
			$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
			$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
			$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
			$this->txtPnr      = $this->common_model->searchKeyword('txtPnr');
//			$this->txtSector   = $this->common_model->searchKeyword('txtSector');
			$this->ddlType     = $this->common_model->searchKeyword('ddlType');
			$this->ddlLocation     = $this->common_model->searchKeyword('ddlLocation');
		}//end if
		
		if(!empty($this->txtAirline))
		{
			$extraCheck = " AND ar.nArId = ". $this->txtAirline . " "; 
/*			$extraCheck = " WHERE(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'  
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %' 
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')";*/
		}//end if

		if(!empty($this->txtTktName))
		{
			$extraCheck 	.= " AND (xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% 
										".$this->txtTktName."' OR xo.vTicktManual LIKE '%".$this->txtTktName."%')";
		}//end if

		if(!empty($this->txtGds))
		{
			$extraCheck     .= " AND g.nGId =".$this->txtGds." ";
			/* $extraCheck 	.= " WHERE(g.vName LIKE '".$this->txtGds." %' OR g.vName LIKE '% ".$this->txtGds."'  
									OR g.vName LIKE '%".$this->txtGds."%')"; */
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";
/*			$extraCheck 	.= " WHERE(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %' 
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')";*/
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck 	.= " AND(xo.vPassenger LIKE '".$this->txtPassName." %' OR xo.vPassenger LIKE '% ".$this->txtPassName."'
									OR xo.vPassenger LIKE '%".$this->txtPassName."%')";
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck .= " AND STR_TO_DATE( xo.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $txtFrom . "', '%Y-%m-%d' ) AND STR_TO_DATE('" . $txtTo . "', '%Y-%m-%d' ) ";
			//$extraCheck .= " WHERE xo.dDate >= '".$txtFrom."' AND xo.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->ddlType))
		{
			switch($this->ddlType)
			{
				 case "0":
						$extraCheck .= " ";
				 break;
				   
				 default:
						$extraCheck .= " AND xo.vType ='".$this->ddlType."'";
				 break;
			}//end switch
		}//end if

		if(!empty($this->txtPnr))
		{
			$extraCheck 	.= " AND (xo.vGdspnr LIKE '".$this->txtPnr." %' OR xo.vGdspnr LIKE '% ".$this->txtPnr."'
									OR xo.vGdspnr LIKE '%".$this->txtPnr."%' OR xo.vAirpnr LIKE '".$this->txtPnr." %'
								 OR xo.vAirpnr LIKE '% ".$this->txtPnr."' OR xo.vAirpnr LIKE '%".$this->txtPnr."%')";
		}//end if

		if(!empty($this->ddlLocation)) {
			$extraCheck     .= " AND xo.nLocation = ".$this->ddlLocation." "; 
		}
/*		if(!empty($this->txtSector))
		{
			$extraCheck = " WHERE(xod.vSector LIKE '".$this->txtSector." %' OR xod.vSector LIKE '% ".$this->txtSector."'  
								OR xod.vSector LIKE '%".$this->txtSector."%'
							OR xod.vSector2 LIKE '".$this->txtSector." %' OR xod.vSector2 LIKE '% ".$this->txtSector."'
								OR xod.vSector2 LIKE '%".$this->txtSector."%')";
		}//end if
*/		
		$countFilterList	=  $this->xo_booking_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$data['list_type_value'] = $this->xo_booking_model->getSearchActiveType();
		$data['list_airline_value'] = $this->xo_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->xo_booking_model->getActiveEmployee(); 
		$data['list_gds_value']     = $this->xo_booking_model->getActiveGDS(); 
		$data['list_loc_value']     = $this->xo_booking_model->getActiveLocations(); 

	$this->common_model->_paging($countFilterList, $target,'xo_booking/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->xo_booking_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);
// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'xo_booking/showlist');

		$this->load->view('xo_booking/xo_booking_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = XOBOOKINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
		$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
		$this->txtGds      = $this->common_model->searchKeyword('txtGds');
		$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
		$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
		$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
		$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
		$this->txtPnr      = $this->common_model->searchKeyword('txtPnr');
		$this->txtSector   = $this->common_model->searchKeyword('txtSector');
		$this->ddlType     = $this->common_model->searchKeyword('ddlType');
		$this->ddlLocation     = $this->common_model->searchKeyword('ddlLocation');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirline');
			$this->session->unset_userdata('txtTktName');
			$this->session->unset_userdata('txtGds');
			$this->session->unset_userdata('txtIssuedBy');
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtFromDate');
			$this->session->unset_userdata('txtToDate');
			$this->session->unset_userdata('txtPnr');
			$this->session->unset_userdata('txtSector');
			$this->session->unset_userdata('ddlType');
			$this->session->unset_userdata('ddlLocation');
			
			$this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
			$this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
			$this->txtInvoice  = $this->common_model->searchKeyword('txtGds');
			$this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
			$this->txtPassName = $this->common_model->searchKeyword('txtPassName');
			$this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
			$this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
			$this->txtPnr      = $this->common_model->searchKeyword('txtPnr');
//			$this->txtSector   = $this->common_model->searchKeyword('txtSector');
			$this->ddlType     = $this->common_model->searchKeyword('ddlType');
		}//end if
		
		if(!empty($this->txtAirline))
		{
			$extraCheck = " AND ar.nArId = ". $this->txtAirline . " "; 
/*			$extraCheck = " WHERE(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'  
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %' 
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')"; */
		}//end if

		if(!empty($this->txtTktName))
		{
			$extraCheck 	.= " AND (xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% 
										".$this->txtTktName."' OR xo.vTicktManual LIKE '%".$this->txtTktName."%')";
		}//end if

		if(!empty($this->txtGds))
		{
			$extraCheck     .= " AND g.nGId =".$this->txtGds." ";
/*			$extraCheck 	.= " WHERE(g.vName LIKE '".$this->txtGds." %' OR g.vName LIKE '% ".$this->txtGds."'  
									OR g.vName LIKE '%".$this->txtGds."%')"; */
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";
/*			$extraCheck 	.= " WHERE(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %' 
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')"; */
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck 	.= " AND (xo.vPassenger LIKE '".$this->txtPassName." %' OR xo.vPassenger LIKE '% ".$this->txtPassName."'
									OR xo.vPassenger LIKE '%".$this->txtPassName."%')";
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck .= " AND STR_TO_DATE( xo.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $txtFrom . "', '%Y-%m-%d' ) AND STR_TO_DATE('" . $txtTo . "', '%Y-%m-%d' ) ";
//			$extraCheck .= " AND xo.dDate >= '".$txtFrom."' AND xo.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->ddlType))
		{
			switch($this->ddlType)
			{
				 case "0":
						$extraCheck .= " ";
				 break;
				   
				 default:
						$extraCheck .= " AND xo.vType ='".$this->ddlType."'";
				 break;
			}//end switch
		}//end if

		if(!empty($this->txtPnr))
		{
			$extraCheck 	.= " AND(xo.vGdspnr LIKE '".$this->txtPnr." %' OR xo.vGdspnr LIKE '% ".$this->txtPnr."'
									OR xo.vGdspnr LIKE '%".$this->txtPnr."%' OR xo.vAirpnr LIKE '".$this->txtPnr." %'
								 OR xo.vAirpnr LIKE '% ".$this->txtPnr."' OR xo.vAirpnr LIKE '%".$this->txtPnr."%')";
		}//end if

		if(!empty($this->ddlLocation)) {
			$extraCheck     .= " AND xo.nLocation = ".$this->ddlLocation." "; 
		}

/*		if(!empty($this->txtSector))
		{
			$extraCheck = " WHERE(xod.vSector LIKE '".$this->txtSector." %' OR xod.vSector LIKE '% ".$this->txtSector."'  
								OR xod.vSector LIKE '%".$this->txtSector."%'
							OR xod.vSector2 LIKE '".$this->txtSector." %' OR xod.vSector2 LIKE '% ".$this->txtSector."'
								OR xod.vSector2 LIKE '%".$this->txtSector."%')";
		}//end if
*/		
		$countFilterList	= $this->xo_booking_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'xo_booking/filterList2/'.$this->uri->segment(3));
		$data['list_type_value'] = $this->xo_booking_model->getSearchActiveType();
		$data['list_airline_value'] = $this->xo_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->xo_booking_model->getActiveEmployee(); 
		$data['list_gds_value']     = $this->xo_booking_model->getActiveGDS(); 
		$data['list_loc_value']     = $this->xo_booking_model->getActiveLocations(); 

	
		$data['list_value'] = $this->xo_booking_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'xo_booking/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('xo_booking/xo_booking');
		$this->load->view('general/template_bottom');
	}//end function

	function getStock()
	{
		$this->load->helper(array('form', 'url'));
		
		$loc                    = $this->input->post('ddlLocation');
		$this->ddlTktname       = $this->input->post('ddlTktname');
		$data['list_tkt_value'] = $this->xo_booking_model->getActiveLocStock($loc);
		$this->ddlTktname       = $this->xo_booking_model->chkActiveVal($this->ddlTktname);
		
		$this->load->view('xo_booking/show_stock',$data);
	}//end function

	function getAccountHead()
	{
		$this->load->helper(array('form', 'url'));
		
		$accName                    = $this->input->post('ddlAccount');
		$data['list_acchead_value'] = $this->xo_booking_model->getActiveAccHead($accName);
		$this->load->view('xo_booking/show_acchead',$data);
	}//end function

	function filterCustomerAcc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlMode 				= $this->input->post('ddlMode');
		$this->ddlCustomerAc 		= $this->input->post('ddlCustomerAc');
		
		$data['list_custacc_value'] = $this->xo_booking_model->generateCustAcc($this->ddlMode);
		
		$this->load->view('xo_booking/show_custacc',$data);
	}//end function

    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
		$this->catId   = $this->uri->segment(3);

        $showVal	          	  		= $this->xo_booking_model->getCorresXobooking();
        $this->ddlDivision	  	 		= $showVal->nDivision;
        $this->ddlLocation		  		= $showVal->nLocation;
        $this->txtDate  	  	  		= $showVal->dDate;
        $this->ddlTrans  	  	  		= $showVal->nTransaction;
        $this->txtDocNumber       		= $showVal->vDocNumber;
        $this->ddlAirline   	  	    = $showVal->nAirline;
        $this->ddlTktname   	        = $showVal->vXoNumber;
        $this->ddlGDS 		      		= $showVal->nGDS;
        $this->ddlType	     	  		= $showVal->vType;
        $this->ddlBooked     	  		= $showVal->vBookedBy;
        $this->ddlIssued       	  		= $showVal->vIssuedBy;
        $this->txtPassengerId   	    = $showVal->vPassengerId;
        $this->txtPassengerCon          = $showVal->vPassenger;
        $this->txtPassRemarks   	    = $showVal->vPassRemarks;
        $this->txtTktName		        = $showVal->vTicktManual;

        $this->ddlPassenger		  		= $showVal->nPassenger;
        $this->txtOriginalXo	  		= $showVal->vOriginalXo;
        $this->txtSegment		  		= $showVal->vSegment;
        $this->txtDestination	  		= $showVal->vDestination;
        $this->txtGdsPNR		  		= $showVal->vGdspnr;
        $this->txtAirPNR		  		= $showVal->vAirpnr;
        $this->ddlCurrency		  		= $showVal->nCurrency;
        $this->txtRate   		  		= $showVal->nRate;

        $this->ddlAccount		        = $showVal->nPassengerAC;
        $this->txtRemarks	            = $showVal->vRemarks;
        $this->txtFareTotalAmount		= $showVal->vGrandFare;
        $this->txtTax1TotalAmount	    = $showVal->vGrandTax1;
        $this->txtTax2TotalAmount		= $showVal->vGrandTax2;
        $this->txtTax3TotalAmount		= $showVal->vGrandTax3;
        $this->txtTotalTaxAmount		= $showVal->vGrandToalTax;
        $this->txtTotalNetVendorAmount  = $showVal->vGrandNetFare;
        $this->txtTotalNetVendor1       = $showVal->vGrandNetFare;

        $data['list_loc_value']    = $this->xo_booking_model->getCorrActiveLocation($this->ddlDivision);

        $data['close_status']      = $this->xo_booking_model->getCloseStatus();
        $data['invoice_number']    = $this->xo_booking_model->getInvoiceNumber();
        $data['refund_status']     = $this->xo_booking_model->getRefundStatus();
        $data['refund_number']     = $this->xo_booking_model->getRefundInvoiceNumber();

        //get selected xo details
        $xoDetValArray             = $this->xo_booking_model->getCorresXoDetails();

        $data['txtCarrier']        = $xoDetValArray['xoCarrier'];
        $data['txtFlightNo']       = $xoDetValArray['xoFlight'];
        $data['txtClass']          = $xoDetValArray['xoClass'];
        $data['txtTravelDate']     = $xoDetValArray['xoTravelDate'];
        $data['txtSector']         = $xoDetValArray['xoSector1'];
        $data['txtSector2']        = $xoDetValArray['xoSector2'];
        $data['txtStatus']         = $xoDetValArray['xoStatus'];
        $data['txtTimeDep']        = $xoDetValArray['xoTimeDep'];
        $data['txtTimeArr']        = $xoDetValArray['xoTimeArr'];
        $data['txtDay']            = $xoDetValArray['xoDay'];
        $data['chkAccID']    	   = $xoDetValArray['xoId'];

        //get selected xo pass details
        $xoPassValArray            = $this->xo_booking_model->getCorresPassDetails();

        $data['ddlPassenger']      = $xoPassValArray['xoPass'];
        $data['txtFcTax']          = $xoPassValArray['xoFcTax'];
        $data['txtCommTax']        = $xoPassValArray['xoCommTax'];
        $data['txtORCAmt']         = $xoPassValArray['xoORCAmt'];
        $data['txtFc1Tax']         = $xoPassValArray['xoFc1Tax'];
        $data['txtFc2Tax']         = $xoPassValArray['xoFc2Tax'];
        $data['txtFc3Tax']         = $xoPassValArray['xoFc3Tax'];
        $data['txtTotalTax']       = $xoPassValArray['xoTotalTax'];
        $data['txtNetVendor']      = $xoPassValArray['xoNetVendor'];
        $data['txtXoSalesAmt']      = $xoPassValArray['xoSalesAmt'];
        $data['chkPassID']         = $xoPassValArray['xoPId'];

        //get selected xo billing details
        $showBillingVal			   = $this->xo_booking_model->getCorresBillingDetails();
        $this->txtInvoice          = $showBillingVal->vInvoice;
        $this->ddlMode       	   = $showBillingVal->vModeofpay;
        $this->ddlAccountName      = $showBillingVal->nAccName;
        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
        $this->txtSubAcName        = $showBillingVal->vSubAcName;
        $this->ddlAgentAc          = $showBillingVal->nAgentAc;
        $this->txtAgentAmt         = $showBillingVal->vAgentAmt;
        $this->txtLPO              = $showBillingVal->vLPO;
        $this->txtSalesAmt         = $showBillingVal->vSalesAmt;
        $this->txtDiscount         = $showBillingVal->vDiscount;
        $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
        $this->txtCollected        = $showBillingVal->vCollected;
        $this->txtServicePerc      = $showBillingVal->vServicePercent;
        $this->txtServiceAmt       = $showBillingVal->vServiceAmt;
        $this->chkTktBillingID     = $showBillingVal->nXBId;
    
        $mydata = $this->load->view('xo_booking/export_files/print_xo_booking',$data,true);
        $_SESSION['print_xo_booking'] = $mydata;
        $_SESSION['print_xo_booking_pgSz'] = 'A3';
        $_SESSION['print_xo_booking_lp'] = 'P';

        $this->load->view('xo_booking/print_xo_booking',$data);
    }//end function
/*
    function printAgentPreview()
    {
        $this->load->helper(array('form', 'url'));
        session_start();
        //$this->catId   = $this->input->post('id');
		$this->catId   = $this->uri->segment(3);

        $showVal	          	  		= $this->xo_booking_model->getCorresXobooking();
        $this->ddlDivision	  	 		= $showVal->nDivision;
        $this->ddlLocation		  		= $showVal->nLocation;
        $this->txtDate  	  	  		= $showVal->dDate;
        $this->ddlTrans  	  	  		= $showVal->nTransaction;
        $this->txtDocNumber       		= $showVal->vDocNumber;
        $this->ddlAirline   	  	    = $showVal->nAirline;
        $this->ddlTktname   	        = $showVal->vXoNumber;
        $this->ddlGDS 		      		= $showVal->nGDS;
        $this->ddlType	     	  		= $showVal->vType;
        $this->ddlBooked     	  		= $showVal->vBookedBy;
        $this->ddlIssued       	  		= $showVal->vIssuedBy;
        $this->txtPassengerId   	    = $showVal->vPassengerId;
        $this->txtPassengerCon          = $showVal->vPassenger;
        $this->txtPassRemarks   	    = $showVal->vPassRemarks;
        $this->txtTktName		        = $showVal->vTicktManual;

        $this->ddlPassenger		  		= $showVal->nPassenger;
        $this->txtOriginalXo	  		= $showVal->vOriginalXo;
        $this->txtSegment		  		= $showVal->vSegment;
        $this->txtDestination	  		= $showVal->vDestination;
        $this->txtGdsPNR		  		= $showVal->vGdspnr;
        $this->txtAirPNR		  		= $showVal->vAirpnr;
        $this->ddlCurrency		  		= $showVal->nCurrency;
        $this->txtRate   		  		= $showVal->nRate;

        $this->ddlAccount		        = $showVal->nPassengerAC;
        $this->txtRemarks	            = $showVal->vRemarks;
        $this->txtFareTotalAmount		= $showVal->vGrandFare;
        $this->txtTax1TotalAmount	    = $showVal->vGrandTax1;
        $this->txtTax2TotalAmount		= $showVal->vGrandTax2;
        $this->txtTax3TotalAmount		= $showVal->vGrandTax3;
        $this->txtTotalTaxAmount		= $showVal->vGrandToalTax;
        $this->txtTotalNetVendorAmount  = $showVal->vGrandNetFare;
        $this->txtTotalNetVendor1       = $showVal->vGrandNetFare;

        $data['list_loc_value']    = $this->xo_booking_model->getCorrActiveLocation($this->ddlDivision);

        $data['close_status']      = $this->xo_booking_model->getCloseStatus();
        $data['invoice_number']    = $this->xo_booking_model->getInvoiceNumber();
        $data['refund_status']     = $this->xo_booking_model->getRefundStatus();
        $data['refund_number']     = $this->xo_booking_model->getRefundInvoiceNumber();

        //get selected xo details
        $xoDetValArray             = $this->xo_booking_model->getCorresXoDetails();

        $data['txtCarrier']        = $xoDetValArray['xoCarrier'];
        $data['txtFlightNo']       = $xoDetValArray['xoFlight'];
        $data['txtClass']          = $xoDetValArray['xoClass'];
        $data['txtTravelDate']     = $xoDetValArray['xoTravelDate'];
        $data['txtSector']         = $xoDetValArray['xoSector1'];
        $data['txtSector2']        = $xoDetValArray['xoSector2'];
        $data['txtStatus']         = $xoDetValArray['xoStatus'];
        $data['txtTimeDep']        = $xoDetValArray['xoTimeDep'];
        $data['txtTimeArr']        = $xoDetValArray['xoTimeArr'];
        $data['txtDay']            = $xoDetValArray['xoDay'];
        $data['chkAccID']    	   = $xoDetValArray['xoId'];

        //get selected xo pass details
        $xoPassValArray            = $this->xo_booking_model->getCorresPassDetails();

        $data['ddlPassenger']      = $xoPassValArray['xoPass'];
        $data['txtFcTax']          = $xoPassValArray['xoFcTax'];
        $data['txtCommTax']        = $xoPassValArray['xoCommTax'];
        $data['txtORCAmt']         = $xoPassValArray['xoORCAmt'];
        $data['txtFc1Tax']         = $xoPassValArray['xoFc1Tax'];
        $data['txtFc2Tax']         = $xoPassValArray['xoFc2Tax'];
        $data['txtFc3Tax']         = $xoPassValArray['xoFc3Tax'];
        $data['txtTotalTax']       = $xoPassValArray['xoTotalTax'];
        $data['txtNetVendor']      = $xoPassValArray['xoNetVendor'];
        $data['txtXoSalesAmt']      = $xoPassValArray['xoSalesAmt'];
        $data['chkPassID']         = $xoPassValArray['xoPId'];

        //get selected xo billing details
        $showBillingVal			   = $this->xo_booking_model->getCorresBillingDetails();
        $this->txtInvoice          = $showBillingVal->vInvoice;
        $this->ddlMode       	   = $showBillingVal->vModeofpay;
        $this->ddlAccountName      = $showBillingVal->nAccName;
        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
        $this->txtSubAcName        = $showBillingVal->vSubAcName;
        $this->ddlAgentAc          = $showBillingVal->nAgentAc;
        $this->txtAgentAmt         = $showBillingVal->vAgentAmt;
        $this->txtLPO              = $showBillingVal->vLPO;
        $this->txtSalesAmt         = $showBillingVal->vSalesAmt;
        $this->txtDiscount         = $showBillingVal->vDiscount;
        $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
        $this->txtCollected        = $showBillingVal->vCollected;
        $this->txtServicePerc      = $showBillingVal->vServicePercent;
        $this->txtServiceAmt       = $showBillingVal->vServiceAmt;
        $this->chkTktBillingID     = $showBillingVal->nXBId;

        $mydata = $this->load->view('xo_booking/export_files/print_agent_copy',$data,true);
        $_SESSION['print_XO_agent_copy'] = $mydata;
        $_SESSION['print_XO_agent_copy_pgSz'] = 'A4';
        $_SESSION['print_XO_agent_copy_lp'] = 'P';

        $this->load->view('xo_booking/print_agent_copy',$data);
    }//end function
*/
    function getIssuedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedBy();
      $this->load->view('xo_booking/show_issuedBy',$data);
    }

    function getBookedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedBy();
      $this->load->view('xo_booking/show_bookedBy',$data);
    }

    function getVendorAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_account_value'] = $this->common_model->getVendorMasterAccounts();
      $this->load->view('xo_booking/show_vendorAccounts',$data);

    }

    function getCustAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_custacc_value'] = $this->common_model->getCustomerMasterAccounts();
      $this->load->view('xo_booking/show_customerAccounts',$data);

    }


  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('xo_booking/show_emailDetails',$data);

  }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
