<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ticket_booking extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('ticket_booking_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle    = TICKETBOOKINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

		//site Name
		$target 			= '.';
		$this->common_model->_paging($this->ticket_booking_model->getAllTicketbookingCount(), $target,'ticket_booking/showlist/'.$this->uri->segment(3));
		$data['list_type_value'] = $this->ticket_booking_model->getSearchActiveType();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee(); 
		$data['list_gds_value']     = $this->ticket_booking_model->getActiveGDS(); 
		$data['list_loc_value']     = $this->ticket_booking_model->getActiveLocations(); 

		$data['list_value']      = $this->ticket_booking_model->getAllTicketbooking();
		$this->count_rows        = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ticket_booking/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('ticket_booking/ticket_booking');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editTicketbooking()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	             = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');

        $id 						 = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ticket_booking/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if($this->uri->segment(6) == 'closed')
        $backUrlAddr = trim($this->session->userdata('backURL2'));
      else
        $backUrlAddr = trim($this->session->userdata('backURL'));

      if( $backUrlAddr != '')
        redirect(site_url($backUrlAddr));
      else
        redirect(site_url("ticket_booking/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_div_value']	     = $this->ticket_booking_model->getActiveDivision();
		$data['list_trans_value']    = $this->ticket_booking_model->getActiveTransactions();
		$data['list_airline_value']  = $this->ticket_booking_model->getActiveAirline();
		$data['list_tkt_value']      = $this->ticket_booking_model->getActiveStock();
		$data['list_gds_value']      = $this->ticket_booking_model->getActiveGDS();
		$data['list_type_value']     = $this->ticket_booking_model->getActiveType();
		$data['list_emp_value']      = $this->ticket_booking_model->getActiveEmployee();
		$data['list_pass_value']     = $this->ticket_booking_model->getActiveCustomer();
		$data['list_curr_value']     = $this->ticket_booking_model->getActiveCurrency();
		$data['list_fare_value']     = $this->ticket_booking_model->getFareType();
		$data['list_account_value']  = $this->ticket_booking_model->getAccounts();
		$data['list_mode_value']  	 = $this->ticket_booking_model->getModeType();
		$data['list_acc_value']      = $this->ticket_booking_model->getAccounts();
		$data['list_acchead_value']  = $this->ticket_booking_model->getAccountHead();
		$data['list_custacc_value']  = $this->ticket_booking_model->getActiveCustomerAccounts();
		$data['list_agentacc_value'] = $this->ticket_booking_model->getAgentAccounts();

        switch($id)
        {
			case "a":
				$this->showTitle  	  	  = ADDNEWTICKETBOOKING;
				$this->btnVal	  	  	  = ADDCONTINUE;
				$this->btnVal2	  	  	  = SAVEEXIT;
				
				$data['list_loc_value']   = $this->ticket_booking_model->getLocation();
				$data['list_curr_value']  = $this->ticket_booking_model->getCurrency();
				$this->ddlDivision 	      = $this->input->post('ddlDivision');
				$this->ddlLocation  	  = $this->input->post('ddlLocation');
				$this->txtDate  	      = date('d-m-Y');
				$this->txtDocNumber  	  = $this->getAutomaticDocNumber();
				$this->ddlType  	  	  = $this->input->post('ddlType');
				$this->ddlAirline   	  = $this->input->post('ddlAirline');
				$this->ddlTktname   	  = $this->input->post('ddlTktname');
				$this->ddlGDS  	  	  	  = $this->input->post('ddlGDS');
				$this->ddlBooked   	  	  = $this->input->post('ddlBooked');
				$this->ddlIssued 	  	  = $this->input->post('ddlIssued');
				$this->txtPassenger 	  = $this->input->post('txtPassenger');
				$this->txtOriginalTkt 	  = $this->input->post('txtOriginalTkt');
				$this->txtSegment 	  	  = $this->input->post('txtSegment');
				$this->txtDestination 	  = $this->input->post('txtDestination');
				$this->txtGdsPNR 	      = $this->input->post('txtGdsPNR');
				$this->txtAirPNR 	      = $this->input->post('txtAirPNR');
				$this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
				$this->ddlCurrency 	      = $this->common_model->get_field_name('Currency','nId'," WHERE nId='".$this->compCurr."'");
				$this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->compCurr."'");
				$this->ddlAccHead         = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nFId = '52'");
				
				//echo $this->ddlLocation;
				
				$this->txtTktName		  = $this->input->post('txtTktName');
				
				$this->chkValYes  	  	  = 'checked="checked"';
				$this->chkValNo	  	  	  = '';
				$this->catId	  	  	  = '';
			break;
			
			case "e":
				$this->showTitle  	  	  = EDITTICKETBOOKING;
				$this->btnVal	      	  = UPDATETICKETBOOKING;
				$this->catId	      	  = $this->uri->segment(5);
				
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if
				//$showval1                  = $this->ticket_booking_model->getAirlineTicketbooking();
				$showVal	          	   = $this->ticket_booking_model->getCorresTicketbooking();
				$this->ddlDivision	  	   = $showVal->nDivision;
				$this->ddlLocation	       = $showVal->nLocation;
				$this->txtDate  	  	   = date('d-m-Y',strtotime($showVal->dDate));
				$this->txtDocNumber        = $showVal->vDocNumber;
				$this->ddlAirline   	   = $showVal->nAirline;
				$this->ddlTktname 	       = $showVal->vTicketNumber; 
				$this->ddlGDS 		       = $showVal->nGDS;
				$this->ddlType	     	   = $showVal->vType;
				$this->ddlBooked     	   = $showVal->vBookedBy;
				$this->ddlIssued       	   = $showVal->vIssuedBy;
				$this->txtPassenger		   = $showVal->vPassenger;
				$this->txtOriginalTkt	   = $showVal->vOriginalTkt;
				$this->txtGdsPNR		   = $showVal->vGdspnr;
				$this->txtAirPNR		   = $showVal->vAirpnr;
				$this->ddlCurrency		   = $showVal->nCurrency;
				$this->txtRate   		   = $showVal->nRate;
				$this->txtTktName		   = $showVal->vTicktManual;
				$data['list_loc_value']    = $this->ticket_booking_model->getCorrActiveLocation($this->ddlDivision);
                $data['close_status']      = $this->ticket_booking_model->getCloseStatus();
                $data['invoice_number']    = $this->ticket_booking_model->getInvoiceNumber();
				 $data['invoice_id']    = $this->ticket_booking_model->getInvoiceid();
                $data['refund_status']     = $this->ticket_booking_model->getRefundStatus();
                $data['refund_number']     = $this->ticket_booking_model->getRefundInvoiceNumber();
				//get selected ticket details
				$showTktVal			       = $this->ticket_booking_model->getCorresTicketDetails();
			    $this->txtCarrier1         = $showTktVal->vCarrier1;
		        $this->txtFlightNo1        = $showTktVal->vFlight1;
			    $this->txtClass1           = $showTktVal->vClass1;
		    //    $this->txtTravelDate1      = date('d-m-Y',strtotime($showTktVal->dTravelDate1));
			    $this->txtSector1          = $showTktVal->vSector1;
		        $this->txtSector12         = $showTktVal->vSector12;
			    $this->txtStatus1          = $showTktVal->vStatus1;
		        $this->txtTimeDep1         = $showTktVal->vTimeDept1;
			    $this->txtTimeArr1         = $showTktVal->vTimeArr1;
          $this->txtDay1             = $showTktVal->vDay1;

          switch($showTktVal->dTravelDate1)
          {
            case "1969-12-31":
              $this->txtTravelDate1 = '';
            break;

            case "1970-01-01":
              $this->txtTravelDate1 = '';
            break;

            case "0000-00-00":
              $this->txtTravelDate1 = '';
            break;

            case "":
              $this->txtTravelDate1 = '';
            break;

            default:
            $this->txtTravelDate1 = date('d-m-Y',strtotime($showTktVal->dTravelDate1));
            break;
          }//end switch

				switch($showTktVal->dTravelDate2)
				{
					case "1969-12-31":
						$this->txtTravelDate2 = '';
					break;
					
					case "1970-01-01":
						$this->txtTravelDate2 = '';
					break;
					
					case "0000-00-00":
						$this->txtTravelDate2 = '';
					break;
					
          case "":
            $this->txtTravelDate2 = '';
          break;

					default:
						$this->txtTravelDate2 = date('d-m-Y',strtotime($showTktVal->dTravelDate2));
					break;
				}//end switch
				
				switch($showTktVal->dTravelDate3)
				{
					case "1969-12-31":
						$this->txtTravelDate3 = '';
					break;
					
					case "1970-01-01":
						$this->txtTravelDate3 = '';
					break;
					
					case "0000-00-00":
						$this->txtTravelDate3 = '';
					break;
          case "":
            $this->txtTravelDate3 = '';
          break;
					
					default:
						$this->txtTravelDate3 = date('d-m-Y',strtotime($showTktVal->dTravelDate3));
					break;
				}//end switch
				
				switch($showTktVal->dTravelDate4)
				{
					case "1969-12-31":
						$this->txtTravelDate4 = '';
					break;
					
					case "1970-01-01":
						$this->txtTravelDate4 = '';
					break;
					
					case "0000-00-00":
						$this->txtTravelDate4 = '';
					break;

          case "":
            $this->txtTravelDate4 = '';
          break;

					default:
						$this->txtTravelDate4 = date('d-m-Y',strtotime($showTktVal->dTravelDate4));
					break;
				}//end switch
				
			    $this->txtCarrier2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vCarrier2;
		        $this->txtFlightNo2        = ($this->txtTravelDate2 == '')?'':$showTktVal->vFlight2;
			    $this->txtClass2           = ($this->txtTravelDate2 == '')?'':$showTktVal->vClass2;
		       // $this->txtTravelDate2      = $showTktVal->dTravelDate2;
			    $this->txtSector2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector2;
		        $this->txtSector22         = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector22;
			    $this->txtStatus2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vStatus2;
		        $this->txtTimeDep2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeDept2;
			    $this->txtTimeArr2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeArr2;
		        $this->txtDay2             = ($this->txtTravelDate2 == '')?'':$showTktVal->vDay2;
			    $this->txtCarrier3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vCarrier3;
		        $this->txtFlightNo3        = ($this->txtTravelDate3 == '')?'':$showTktVal->vFlight3;
			    $this->txtClass3           = ($this->txtTravelDate3 == '')?'':$showTktVal->vClass3;
		        //$this->txtTravelDate3      = $showTktVal->dTravelDate3;
			    $this->txtSector3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector3;
		        $this->txtSector33         = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector33;
			    $this->txtStatus3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vStatus3;
		        $this->txtTimeDep3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeDept3;
			    $this->txtTimeArr3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeArr3;
		        $this->txtDay3             = ($this->txtTravelDate3 == '')?'':$showTktVal->vDay3;
			    $this->txtCarrier4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vCarrier4;
		        $this->txtFlightNo4        = ($this->txtTravelDate4 == '')?'':$showTktVal->vFlight4;
			    $this->txtClass4           = ($this->txtTravelDate4 == '')?'':$showTktVal->vClass4;
		        //$this->txtTravelDate4      = $showTktVal->dTravelDate4;
			    $this->txtSector4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector4;
		        $this->txtSector44         = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector44;
			    $this->txtStatus4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vStatus4;
		        $this->txtTimeDep4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeDept4;
			    $this->txtTimeArr4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeArr4;
		        $this->txtDay4             = ($this->txtTravelDate4 == '')?'':$showTktVal->vDay4;
				$this->txtSegment		   = $showTktVal->vSegment;
				$this->txtDestination	   = $showTktVal->vDestination;

				//get selected ticket fare details
				$showFareVal			   = $this->ticket_booking_model->getCorresFareDetails();
				$this->ddlFare             = $showFareVal->vFare;
				$this->ddlFareCode         = $showFareVal->nFareCode;
				$this->txtFcFare           = $showFareVal->vFCFare;
				$this->txtFcTax            = $showFareVal->vFCTax;
				$this->txtFace             = $showFareVal->vFaceValue;
				$this->txtMktFare          = $showFareVal->vMKTFare;
				$this->txtCommTax          = $showFareVal->nComm;
				$this->txtCommAmt          = $showFareVal->nCommAmt;
				$this->txtGSATax           = $showFareVal->nGSAComm;
				$this->txtGSAAmt           = $showFareVal->nGSAAmt;
				$this->txtORCTax           = $showFareVal->nORC;
				$this->txtORCAmt           = $showFareVal->nORCAmt;
				$this->txtFcTax1           = $showFareVal->vTax1;
				$this->txtFcTax2           = $showFareVal->vTax2;
				$this->txtFcTax3           = $showFareVal->vTax3;
				$this->txtTotalTax         = $showFareVal->vTaxTotal;
				$this->txtNetVendor        = $showFareVal->nNetVendor;
				$this->ddlAccname          = $showFareVal->nAcc;
				$this->ddlAccHead          = $showFareVal->nAccHead;
				$this->txtSalesAmt         = $showFareVal->vSalesAmt;
				
				//get selected conjuction ticket details
				$tktId  				   = $showVal->nTId;
				$this->conjId			   = $this->common_model->get_field_name('TicketBooking','nTId',
																					" WHERE vConjTkt = '".$tktId."'");
				$showConjVal			   = $this->ticket_booking_model->getCorresConjuctionDetails($this->conjId);
	
				$this->txtConjTkt          = $this->common_model->get_field_name('TicketBooking','vTicketNumber',
																					" WHERE nTId = '".$this->conjId."'");
			    $this->txtCarrierTkt1      = $showConjVal->vCarrier1;
		        $this->txtFlightNoTkt1     = $showConjVal->vFlight1;
			    $this->txtClassTkt1        = $showConjVal->vClass1;
		        $this->txtTravelDateTkt1   = date('d-m-Y',strtotime($showConjVal->dTravelDate1));
			    $this->txtSectorTkt1       = $showConjVal->vSector1;
		        $this->txtSectorTkt12      = $showConjVal->vSector12;
			    $this->txtStatusTkt1       = $showConjVal->vStatus1;
		        $this->txtTimeDepTkt1      = $showConjVal->vTimeDept1;
			    $this->txtTimeArrTkt1      = $showConjVal->vTimeArr1;
		        $this->txtDayTkt1          = $showConjVal->vDay1;
			    $this->txtCarrierTkt2      = $showConjVal->vCarrier2;
		        $this->txtFlightNoTkt2     = $showConjVal->vFlight2;
			    $this->txtClassTkt2        = $showConjVal->vClass2;
		        $this->txtTravelDateTkt2   = date('d-m-Y',strtotime($showConjVal->dTravelDate2));
			    $this->txtSectorTkt2       = $showConjVal->vSector2;
		        $this->txtSectorTkt22      = $showConjVal->vSector22;
			    $this->txtStatusTkt2       = $showConjVal->vStatus2;
		        $this->txtTimeDepTkt2      = $showConjVal->vTimeDept2;
			    $this->txtTimeArrTkt2      = $showConjVal->vTimeArr2;
		        $this->txtDayTkt2          = $showConjVal->vDay2;
			    $this->txtCarrierTkt3      = $showConjVal->vCarrier3;
		        $this->txtFlightNoTkt3     = $showConjVal->vFlight3;
			    $this->txtClassTkt3        = $showConjVal->vClass3;
		        $this->txtTravelDateTkt3   = date('d-m-Y',strtotime($showConjVal->dTravelDate3));
			    $this->txtSectorTkt3       = $showConjVal->vSector3;
		        $this->txtSectorTkt33      = $showConjVal->vSector33;
			    $this->txtStatusTkt3       = $showConjVal->vStatus3;
		        $this->txtTimeDepTkt3      = $showConjVal->vTimeDept3;
			    $this->txtTimeArrTkt3      = $showConjVal->vTimeArr3;
		        $this->txtDayTkt3          = $showConjVal->vDay3;
			    $this->txtCarrierTkt4      = $showConjVal->vCarrier4;
		        $this->txtFlightNoTkt4     = $showConjVal->vFlight4;
			    $this->txtClassTkt4        = $showConjVal->vClass4;
		        $this->txtTravelDateTkt4   = date('d-m-Y',strtotime($showConjVal->dTravelDate4));
			    $this->txtSectorTkt4       = $showConjVal->vSector4;
		        $this->txtSectorTkt44      = $showConjVal->vSector44;
			    $this->txtStatusTkt4       = $showConjVal->vStatus4;
		        $this->txtTimeDepTkt4      = $showConjVal->vTimeDept4;
			    $this->txtTimeArrTkt4      = $showConjVal->vTimeArr4;
		        $this->txtDayTkt4          = $showConjVal->vDay4;
				$this->txtSegmentCnjTkt	   = $showConjVal->vSegment;
				$this->txtDestinationCnjTkt= $showConjVal->vDestination;

				//get selected ticket billing details
				$showBillingVal			   = $this->ticket_booking_model->getCorresBillingDetails();
				$this->txtInvoice          = $showBillingVal->vInvoice;
				$this->ddlMode       	   = $showBillingVal->vModeofpay;
				$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
				$this->txtSubaccName       = $showBillingVal->vSubAcname;
				$this->ddlAgentAc          = $showBillingVal->nAgentAc;
				$this->txtAgentAmt         = $showBillingVal->vAgentAmt;
				$this->txtLPO              = $showBillingVal->vLPO;
				$this->txtSalesAmt1        = $showBillingVal->vSalesAmt;
				$this->txtDiscount         = $showBillingVal->vDiscount;
				$this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
				$this->txtCollected        = $showBillingVal->vCollected;
				$this->txtServicePerc      = $showBillingVal->vServicePercent;
				$this->txtServiceAmt       = $showBillingVal->vServiceAmt;
				
				//get selected ticket passenger details
				$this->txtRemarks          = $showFareVal->vRemarks;
				$this->txtPassengerId      = $showFareVal->vPassengerId;
				$this->txtPassengerContact = $showFareVal->vPassengerContact;
				
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
			$this->load->view('ticket_booking/edit_ticket_booking');
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
					$this->ticket_booking_model->insertTicketbooking();
				break;
				
				case "e":
					//Update into db
					$this->ticket_booking_model->updateTicketbooking();
				break;
			}//end switch
			
			redirect(site_url("ticket_booking/showlist/".$this->uri->segment(3).'/'.$msgVal));
			//}//end else
		}//end else	
	}//end function


	function getAutomaticDocNumber()
	{
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='5'");
		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}TicketBooking ORDER BY nTID DESC LIMIT 0,1");
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
			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='5'");
			$countVal 			= $this->txtDocNumber;
		endif;
		
		return($countVal);
	}//end function

	//get currency rate when selected currency
	function generateCurrRate()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$valArray			 = array();
		$this->ddlCurrency   = $this->input->post('ddlCurrency'); 
		$this->txtRate		 = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->ddlCurrency."'"); 
		$this->txtDecimal	 = $this->common_model->get_field_name('Currency','vDecimal'," WHERE nId='".$this->ddlCurrency."'"); 
		$valArray['rate']    = $this->txtRate;
//		$valArray['decimal'] = str_repeat(0,$this->txtDecimal);
		
		echo json_encode($valArray);
	}//end function

	//get fare code when selected fare
	function generateFareCode()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->ddlFare     = $this->input->post('ddlFare');
		$this->ddlFareCode = $this->input->post('ddlFareCode');
		$this->ddlAirline  = $this->input->post('ddlAirline');
		if($this->ddlFare == 'S')
		{
		  $data['list_farecode_value'] = $this->ticket_booking_model->getFareCode($this->ddlAirline);
		  $this->load->view('ticket_booking/show_farecode',$data);
		}//end if
		else
		{
			;
		}//end else
	}//end function

	//get fare value when selected fare code
	function generateFareValue()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->ddlFareCode = $this->input->post('ddlFareCode');
		$this->txtFace     = $this->common_model->get_field_name('FareCode','vValue'," WHERE nFId='".$this->ddlFareCode."'"); 
		echo $this->txtFace; 
	}//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		$this->ddlTktname  = $this->input->post('ddlTktname');

		$data['list_loc_value'] = $this->ticket_booking_model->filterLocation($this->ddlDivision);
		$this->load->view('ticket_booking/show_location',$data);
	}//end function

	function filterCustomerAcc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlMode 				= $this->input->post('ddlMode');
		$this->ddlCustomerAc 		= $this->input->post('ddlCustomerAc');
		$data['list_custacc_value'] = $this->ticket_booking_model->generateCustAcc($this->ddlMode);
		
		$this->load->view('ticket_booking/show_custacc',$data);
	}//end function
	
	function delticketbooking()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETBOOKINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ticket_booking_model->deleteTicketbooking();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETBOOKINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->ticket_booking_model->deleteAllTicketbooking();
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
		$txtCode       = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");
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
		$items     = $this->ticket_booking_model->searchActiveTicketStock($searchKey);
		echo json_encode($items);
	}//end function
	
	//get airline details 
	function airlinesuggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey = $this->input->post('q');
		$items     = $this->ticket_booking_model->searchActiveAirline($searchKey);
		echo json_encode($items);
	}//end function

	//get ticket booking details according to the tkt number selected
	function checkTicketDetails()
	{ 
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->btnVal	  	  	     = ADDCONTINUE;
		$this->btnVal2	  	  	     = SAVEEXIT;

        $data['list_div_value']	     = $this->ticket_booking_model->getActiveDivision();
        $data['list_trans_value']    = $this->ticket_booking_model->getActiveTransactions();
        $data['list_airline_value']  = $this->ticket_booking_model->getActiveAirline();
        $data['list_tkt_value']      = $this->ticket_booking_model->getActiveStock();
        $data['list_gds_value']      = $this->ticket_booking_model->getActiveGDS();
        $data['list_type_value']     = $this->ticket_booking_model->getActiveType();
        $data['list_emp_value']      = $this->ticket_booking_model->getActiveEmployee();
        $data['list_pass_value']     = $this->ticket_booking_model->getActiveCustomer();
        $data['list_curr_value']     = $this->ticket_booking_model->getActiveCurrency();
        $data['list_fare_value']     = $this->ticket_booking_model->getFareType();
        $data['list_account_value']  = $this->ticket_booking_model->getAccounts();
        $data['list_mode_value']  	 = $this->ticket_booking_model->getModeType();
        $data['list_acc_value']      = $this->ticket_booking_model->getAccounts();
        $data['list_acchead_value']  = $this->ticket_booking_model->getAccountHead();
        $data['list_custacc_value']  = $this->ticket_booking_model->getActiveCustomerAccounts();
        $data['list_agentacc_value'] = $this->ticket_booking_model->getAgentAccounts();
		$this->txtCopyTkt            = $this->input->post('txtCopyTkt');
		$this->tktId	             = $this->common_model->get_field_name('TicketBooking','nTId',
																              " WHERE vTicktManual = ".$this->txtCopyTkt." ");
		$showCopyVal      		   = $this->ticket_booking_model->getSelectedTicketDetails($this->tktId);
		
		$this->ddlDivision	  	   = $showCopyVal->nDivision;
		$this->ddlLocation	       = $showCopyVal->nLocation;
		$this->txtDate  	  	   = date('d-m-Y',strtotime($showCopyVal->dDate));
		$this->ddlTrans  	  	   = $showCopyVal->nTransaction;
		$this->txtDocNumber        = $showCopyVal->vDocNumber;
		$this->ddlAirline   	   = $showCopyVal->nAirline;		
		$this->ddlTktname 	       = $showCopyVal->vTicketNumber;
		$this->txtTktName   	   = $showCopyVal->vTicktManual;
		$this->ddlGDS 		       = $showCopyVal->nGDS;
		$this->ddlType	     	   = $showCopyVal->vType;
		$this->ddlBooked     	   = $showCopyVal->vBookedBy;
		$this->ddlIssued       	   = $showCopyVal->vIssuedBy;
		$this->txtPassenger		   = $showCopyVal->vPassenger;
		$this->txtOriginalTkt	   = $showCopyVal->vOriginalTkt;
		$this->txtGdsPNR		   = $showCopyVal->vGdspnr;
		$this->txtAirPNR		   = $showCopyVal->vAirpnr;
		$this->ddlCurrency		   = $showCopyVal->nCurrency;
		$this->txtRate   		   = $showCopyVal->nRate;
		$data['list_loc_value']    = $this->ticket_booking_model->getCorrActiveLocation($this->ddlDivision);
		
		//get selected ticket details
		$this->txtCarrier1         = $showCopyVal->vCarrier1;
		$this->txtFlightNo1        = $showCopyVal->vFlight1;
		$this->txtClass1           = $showCopyVal->vClass1;
		$this->txtTravelDate1      = date('d-m-Y',strtotime($showCopyVal->dTravelDate1));
		$this->txtSector1          = $showCopyVal->vSector1;
		$this->txtSector12         = $showCopyVal->vSector12;
		$this->txtStatus1          = $showCopyVal->vStatus1;
		$this->txtTimeDep1         = $showCopyVal->vTimeDept1;
		$this->txtTimeArr1         = $showCopyVal->vTimeArr1;
		$this->txtDay1             = $showCopyVal->vDay1;
		$this->txtCarrier2         = $showCopyVal->vCarrier2;
		$this->txtFlightNo2        = $showCopyVal->vFlight2;
		$this->txtClass2           = $showCopyVal->vClass2;
		$this->txtTravelDate2      = date('d-m-Y',strtotime($showCopyVal->dTravelDate2));
		$this->txtSector2          = $showCopyVal->vSector2;
		$this->txtSector22         = $showCopyVal->vSector22;
		$this->txtStatus2          = $showCopyVal->vStatus2;
		$this->txtTimeDep2         = $showCopyVal->vTimeDept2;
		$this->txtTimeArr2         = $showCopyVal->vTimeArr2;
		$this->txtDay2             = $showCopyVal->vDay2;
		$this->txtCarrier3         = $showCopyVal->vCarrier3;
		$this->txtFlightNo3        = $showCopyVal->vFlight3;
		$this->txtClass3           = $showCopyVal->vClass3;
		$this->txtTravelDate3      = date('d-m-Y',strtotime($showCopyVal->dTravelDate3));
		$this->txtSector3          = $showCopyVal->vSector3;
		$this->txtSector33         = $showCopyVal->vSector33;
		$this->txtStatus3          = $showCopyVal->vStatus3;
		$this->txtTimeDep3         = $showCopyVal->vTimeDept3;
		$this->txtTimeArr3         = $showCopyVal->vTimeArr3;
		$this->txtDay3             = $showCopyVal->vDay3;
		$this->txtCarrier4         = $showCopyVal->vCarrier4;
		$this->txtFlightNo4        = $showCopyVal->vFlight4;
		$this->txtClass4           = $showCopyVal->vClass4;
		$this->txtTravelDate4      = date('d-m-Y',strtotime($showCopyVal->dTravelDate4));
		$this->txtSector4          = $showCopyVal->vSector4;
		$this->txtSector44         = $showCopyVal->vSector44;
		$this->txtStatus4          = $showCopyVal->vStatus4;
		$this->txtTimeDep4         = $showCopyVal->vTimeDept4;
		$this->txtTimeArr4         = $showCopyVal->vTimeArr4;
		$this->txtDay4             = $showCopyVal->vDay4;
		$this->txtSegment		   = $showCopyVal->vSegment;
		$this->txtDestination	   = $showCopyVal->vDestination;

		//get selected ticket fare details
		$this->ddlFare             = $showCopyVal->vFare;
		$this->ddlFareCode         = $showCopyVal->nFareCode;
		$this->txtFcFare           = $showCopyVal->vFCFare;
		$this->txtFcTax            = $showCopyVal->vFCTax;
		$this->txtFace             = $showCopyVal->vFaceValue;
		$this->txtMktFare          = $showCopyVal->vMKTFare;
		$this->txtCommTax          = $showCopyVal->nComm;
		$this->txtCommAmt          = $showCopyVal->nCommAmt;
		$this->txtGSATax           = $showCopyVal->nGSAComm;
		$this->txtGSAAmt           = $showCopyVal->nGSAAmt;
		$this->txtORCTax           = $showCopyVal->nORC;
		$this->txtORCAmt           = $showCopyVal->nORCAmt;
		$this->txtFcTax1           = $showCopyVal->vTax1;
		$this->txtFcTax2           = $showCopyVal->vTax2;
		$this->txtFcTax3           = $showCopyVal->vTax3;
		$this->txtTotalTax         = $showCopyVal->vTaxTotal;
		$this->txtNetVendor        = $showCopyVal->nNetVendor;
		$this->txtRemarks          = $showCopyVal->vRemarks;
		$this->txtPassengerId      = $showCopyVal->vPassengerId;
		$this->txtPassengerContact = $showCopyVal->vPassengerContact;
		$this->ddlAccname          = $showCopyVal->nAcc;
		$this->txtSalesAmt         = $showCopyVal->vSalesAmt;
		
		//get selected conjuction ticket details
		$tktId  				   = $showCopyVal->nTId;
		$this->conjId			   = $this->common_model->get_field_name('TicketBooking','nTId',
																			" WHERE vConjTkt = '".$tktId."'");
		$showConjVal			   = $this->ticket_booking_model->getSelectedConjuctionDetails($this->conjId);
		$this->txtConjTkt          = $this->common_model->get_field_name('TicketBooking','vTicketNumber',
																			" WHERE nTId = '".$this->conjId."'");
		$this->txtCarrierTkt1      = $showConjVal->vCarrier1;
		$this->txtFlightNoTkt1     = $showConjVal->vFlight1;
		$this->txtClassTkt1        = $showConjVal->vClass1;
		$this->txtTravelDateTkt1   = date('d-m-Y',strtotime($showConjVal->dTravelDate1));
		$this->txtSectorTkt1       = $showConjVal->vSector1;
		$this->txtSectorTkt12      = $showConjVal->vSector12;
		$this->txtStatusTkt1       = $showConjVal->vStatus1;
		$this->txtTimeDepTkt1      = $showConjVal->vTimeDept1;
		$this->txtTimeArrTkt1      = $showConjVal->vTimeArr1;
		$this->txtDayTkt1          = $showConjVal->vDay1;
		$this->txtCarrierTkt2      = $showConjVal->vCarrier2;
		$this->txtFlightNoTkt2     = $showConjVal->vFlight2;
		$this->txtClassTkt2        = $showConjVal->vClass2;
		$this->txtTravelDateTkt2   = date('d-m-Y',strtotime($showConjVal->dTravelDate2));
		$this->txtSectorTkt2       = $showConjVal->vSector2;
		$this->txtSectorTkt22      = $showConjVal->vSector22;
		$this->txtStatusTkt2       = $showConjVal->vStatus2;
		$this->txtTimeDepTkt2      = $showConjVal->vTimeDept2;
		$this->txtTimeArrTkt2      = $showConjVal->vTimeArr2;
		$this->txtDayTkt2          = $showConjVal->vDay2;
		$this->txtCarrierTkt3      = $showConjVal->vCarrier3;
		$this->txtFlightNoTkt3     = $showConjVal->vFlight3;
		$this->txtClassTkt3        = $showConjVal->vClass3;
		$this->txtTravelDateTkt3   = date('d-m-Y',strtotime($showConjVal->dTravelDate3));
		$this->txtSectorTkt3       = $showConjVal->vSector3;
		$this->txtSectorTkt33      = $showConjVal->vSector33;
		$this->txtStatusTkt3       = $showConjVal->vStatus3;
		$this->txtTimeDepTkt3      = $showConjVal->vTimeDept3;
		$this->txtTimeArrTkt3      = $showConjVal->vTimeArr3;
		$this->txtDayTkt3          = $showConjVal->vDay3;
		$this->txtCarrierTkt4      = $showConjVal->vCarrier4;
		$this->txtFlightNoTkt4     = $showConjVal->vFlight4;
		$this->txtClassTkt4        = $showConjVal->vClass4;
		$this->txtTravelDateTkt4   = date('d-m-Y',strtotime($showConjVal->dTravelDate4));
		$this->txtSectorTkt4       = $showConjVal->vSector4;
		$this->txtSectorTkt44      = $showConjVal->vSector44;
		$this->txtStatusTkt4       = $showConjVal->vStatus4;
		$this->txtTimeDepTkt4      = $showConjVal->vTimeDept4;
		$this->txtTimeArrTkt4      = $showConjVal->vTimeArr4;
		$this->txtDayTkt4          = $showConjVal->vDay4;
		$this->txtSegmentCnjTkt	   = $showConjVal->vSegment;
		$this->txtDestinationCnjTkt= $showConjVal->vDestination;

		//get selected ticket billing details
		$this->ddlMode       	   = $showCopyVal->vModeofpay;
		$this->ddlCustomerAc       = $showCopyVal->nCustomerAc;
		$this->ddlAgentAc          = $showCopyVal->nAgentAc;
		$this->txtAgentAmt         = $showCopyVal->vAgentAmt;
		$this->txtLPO              = $showCopyVal->vLPO;
		$this->txtSalesAmt1        = $showCopyVal->vSalesAmt;
		$this->txtDiscount         = $showCopyVal->vDiscount;
		$this->txtDiscountAmt      = $showCopyVal->vDiscountAmt;
		$this->txtCollected        = $showCopyVal->vCollected;
		$this->txtServicePerc      = $showCopyVal->vServicePercent;
		$this->txtServiceAmt       = $showCopyVal->vServiceAmt;
		
		$this->load->view('ticket_booking/show_copyticket',$data);
	}//end function

	//get ticket booking details according to the tkt number selected
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
	
	//search by airline,ticket,invoice,issued by,passenger name,from date,to date,accounts
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETBOOKINGMANAGEMENT;
		
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
/*			$extraCheck = " AND(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'  
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %' 
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')";*/
		}//end if

		if(!empty($this->txtTktName))
		{
			$extraCheck 	.= " AND(tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% 
										".$this->txtTktName."' OR tb.vTicktManual LIKE '%".$this->txtTktName."%')";
		}//end if

		if(!empty($this->txtGds))
		{
			$extraCheck     .= " AND g.nGId =".$this->txtGds." ";
//			$extraCheck 	.= " AND(g.vName LIKE '".$this->txtGds." %' OR g.vName LIKE '% ".$this->txtGds."'  
//									OR g.vName LIKE '%".$this->txtGds."%')";
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";
/*			$extraCheck 	.= " AND(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %' 
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')";*/
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%')";
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck .= " AND tb.dDate >= '".$txtFrom."' AND tb.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->ddlType))
		{
			switch($this->ddlType)
			{
				 case "0":
						$extraCheck .= " ";
				 break;
				   
				 default:
						$extraCheck .= " AND tb.vType ='".$this->ddlType."'";
				 break;
			}//end switch
		}//end if

		if(!empty($this->txtPnr))
		{
			$extraCheck 	.= " AND(tb.vGdspnr LIKE '".$this->txtPnr." %' OR tb.vGdspnr LIKE '% ".$this->txtPnr."'
									OR tb.vGdspnr LIKE '%".$this->txtPnr."%' OR tb.vAirpnr LIKE '".$this->txtPnr." %'
								 OR tb.vAirpnr LIKE '% ".$this->txtPnr."' OR tb.vAirpnr LIKE '%".$this->txtPnr."%')";
		}//end if

		if(!empty($this->ddlLocation)) {
			$extraCheck     .= " AND tb.nLocation = ".$this->ddlLocation." "; 
}
/*		if(!empty($this->txtSector))
		{
			$extraCheck = " AND(td.vSector1 LIKE '".$this->txtSector." %' OR td.vSector1 LIKE '% ".$this->txtSector."'  
								OR td.vSector1 LIKE '%".$this->txtSector."%'
							OR td.vSector12 LIKE '".$this->txtSector." %' OR td.vSector12 LIKE '% ".$this->txtSector."'
								OR td.vSector12 LIKE '%".$this->txtSector."%' OR td.vSector2 LIKE '".$this->txtSector." %' 
							OR td.vSector2 LIKE '% ".$this->txtSector."'
								OR td.vSector2 LIKE '%".$this->txtSector."%' OR td.vSector22 LIKE '".$this->txtSector." %' 
							OR td.vSector22 LIKE '% ".$this->txtSector."'
								OR td.vSector22 LIKE '%".$this->txtSector."%' OR td.vSector3 LIKE '".$this->txtSector." %' 
							OR td.vSector3 LIKE '% ".$this->txtSector."'
								OR td.vSector3 LIKE '%".$this->txtSector."%' OR td.vSector33 LIKE '".$this->txtSector." %' 
							OR td.vSector33 LIKE '% ".$this->txtSector."'
								OR td.vSector33 LIKE '%".$this->txtSector."%' OR td.vSector4 LIKE '".$this->txtSector." %' 
							OR td.vSector4 LIKE '% ".$this->txtSector."'
								OR td.vSector4 LIKE '%".$this->txtSector."%' OR td.vSector44 LIKE '".$this->txtSector." %' 
							OR td.vSector44 LIKE '% ".$this->txtSector."'
								OR td.vSector44 LIKE '%".$this->txtSector."%')";
		}//end if
*/		
		$countFilterList	=  $this->ticket_booking_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'ticket_booking/filterList2/'.$this->uri->segment(3));

		$data['list_type_value'] = $this->ticket_booking_model->getSearchActiveType();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee(); 
		$data['list_gds_value']     = $this->ticket_booking_model->getActiveGDS(); 
		$data['list_loc_value']     = $this->ticket_booking_model->getActiveLocations(); 
		$data['list_value'] = $this->ticket_booking_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ticket_booking/showlist');

		$this->load->view('ticket_booking/ticket_booking_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TICKETBOOKINGMANAGEMENT;
		
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
			$this->ddlType     = $this->common_model->searchKeyword('ddlLocation');
		}//end if
		
		if(!empty($this->txtAirline))
		{

			$extraCheck = " AND ar.nArId = ". $this->txtAirline . " "; 
/*			$extraCheck = " AND(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'  
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %' 
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')"; */
		}//end if

		if(!empty($this->txtTktName))
		{
			$extraCheck 	.= " AND(tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% 
										".$this->txtTktName."' OR tb.vTicktManual LIKE '%".$this->txtTktName."%')";
		}//end if

		if(!empty($this->txtGds))
		{
			$extraCheck     .= " AND g.nGId =".$this->txtGds." ";
/*			$extraCheck 	.= " AND(g.vName LIKE '".$this->txtGds." %' OR g.vName LIKE '% ".$this->txtGds."'  
									OR g.vName LIKE '%".$this->txtGds."%')";*/
		}//end if

		if(!empty($this->txtIssuedBy))
		{
			$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";

/*			$extraCheck 	.= " AND(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %' 
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')"; */
		}//end if

		if(!empty($this->txtPassName))
		{
			$extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%')";
		}//end if

		if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
		{
			//convet date to mysql format
			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
			$txtTo   	= $this->common_model->searchKeyword('txtToDate');
		
			$extraCheck .= " AND tb.dDate >= '".$txtFrom."' AND tb.dDate <= '".$txtTo."'";
		}//end if

		if(!empty($this->ddlType))
		{
			switch($this->ddlType)
			{
				 case "0":
						$extraCheck .= " ";
				 break;
				   
				 default:
						$extraCheck .= " AND tb.vType ='".$this->ddlType."'";
				 break;
			}//end switch
		}//end if

		if(!empty($this->txtPnr))
		{
			$extraCheck 	.= " AND(tb.vGdspnr LIKE '".$this->txtPnr." %' OR tb.vGdspnr LIKE '% ".$this->txtPnr."'
									OR tb.vGdspnr LIKE '%".$this->txtPnr."%' OR tb.vAirpnr LIKE '".$this->txtPnr." %'
								 OR tb.vAirpnr LIKE '% ".$this->txtPnr."' OR tb.vAirpnr LIKE '%".$this->txtPnr."%')";
		}//end if
		if(!empty($this->ddlLocation)) {
			$extraCheck     .= " AND tb.nLocation = ".$this->ddlLocation." "; 
		}

/*		if(!empty($this->txtSector))
		{
			$extraCheck = " AND(td.vSector1 LIKE '".$this->txtSector." %' OR td.vSector1 LIKE '% ".$this->txtSector."'  
								OR td.vSector1 LIKE '%".$this->txtSector."%'
							OR td.vSector12 LIKE '".$this->txtSector." %' OR td.vSector12 LIKE '% ".$this->txtSector."'
								OR td.vSector12 LIKE '%".$this->txtSector."%' OR td.vSector2 LIKE '".$this->txtSector." %' 
							OR td.vSector2 LIKE '% ".$this->txtSector."'
								OR td.vSector2 LIKE '%".$this->txtSector."%' OR td.vSector22 LIKE '".$this->txtSector." %' 
							OR td.vSector22 LIKE '% ".$this->txtSector."'
								OR td.vSector22 LIKE '%".$this->txtSector."%' OR td.vSector3 LIKE '".$this->txtSector." %' 
							OR td.vSector3 LIKE '% ".$this->txtSector."'
								OR td.vSector3 LIKE '%".$this->txtSector."%' OR td.vSector33 LIKE '".$this->txtSector." %' 
							OR td.vSector33 LIKE '% ".$this->txtSector."'
								OR td.vSector33 LIKE '%".$this->txtSector."%' OR td.vSector4 LIKE '".$this->txtSector." %' 
							OR td.vSector4 LIKE '% ".$this->txtSector."'
								OR td.vSector4 LIKE '%".$this->txtSector."%' OR td.vSector44 LIKE '".$this->txtSector." %' 
							OR td.vSector44 LIKE '% ".$this->txtSector."'
								OR td.vSector44 LIKE '%".$this->txtSector."%')";
		}//end if
*/		
		$countFilterList	= $this->ticket_booking_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'ticket_booking/filterList2/'.$this->uri->segment(3));
		$data['list_type_value'] = $this->ticket_booking_model->getSearchActiveType();
		$data['list_airline_value'] = $this->ticket_booking_model->getActiveAirline(); 
		$data['list_emp_value']     = $this->ticket_booking_model->getActiveEmployee(); 
		$data['list_gds_value']     = $this->ticket_booking_model->getActiveGDS(); 
		$data['list_loc_value']     = $this->ticket_booking_model->getActiveLocations(); 

	

		$data['list_value'] = $this->ticket_booking_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'ticket_booking/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('ticket_booking/ticket_booking');
		$this->load->view('general/template_bottom');
	}//end function
	
	function getStock()
	{
		$this->load->helper(array('form', 'url'));
		
		$loc                    = $this->input->post('ddlLocation');
		$this->ddlTktname       = $this->input->post('ddlTktname');
		$data['list_tkt_value'] = $this->ticket_booking_model->getActiveLocStock($loc);
		$this->ddlTktname       = $this->ticket_booking_model->chkActiveVal($this->ddlTktname);
		
		$this->load->view('ticket_booking/show_stock',$data);
	}//end function

	function getAccountHead()
	{
		$this->load->helper(array('form', 'url'));
		
		$accName                    = $this->input->post('ddlAccname');
		$data['list_acchead_value'] = $this->ticket_booking_model->getActiveAccHead($accName);
		$this->load->view('ticket_booking/show_acchead',$data);
	}//end function
	
	function printPreview()
	{
    session_start();
		$this->load->helper(array('form', 'url'));
		
		//$this->catId   = $this->input->post('id');
		$this->catId   = $this->uri->segment(3);
		
		$showVal	          	   = $this->ticket_booking_model->getCorresTicketbooking();
		$this->ddlDivision	  	   = $showVal->nDivision;
		$this->ddlLocation	       = $showVal->nLocation;
		$this->txtDate  	  	   = date('d-m-Y',strtotime($showVal->dDate));
		$this->txtDocNumber        = $showVal->vDocNumber;
		$this->ddlAirline   	   = $showVal->nAirline;
		$this->ddlTktname 	       = $showVal->vTicketNumber; 
		$this->ddlGDS 		       = $showVal->nGDS;
		$this->ddlType	     	   = $showVal->vType;
		$this->ddlBooked     	   = $showVal->vBookedBy;
		$this->ddlIssued       	   = $showVal->vIssuedBy;
		$this->txtPassenger		   = $showVal->vPassenger;
		$this->txtOriginalTkt	   = $showVal->vOriginalTkt;
		$this->txtGdsPNR		   = $showVal->vGdspnr;
		$this->txtAirPNR		   = $showVal->vAirpnr;
		$this->ddlCurrency		   = $showVal->nCurrency;
		$this->txtRate   		   = $showVal->nRate;
		$this->txtTktName		   = $showVal->vTicktManual;
		$data['list_loc_value']    = $this->ticket_booking_model->getCorrActiveLocation($this->ddlDivision);
		$data['close_status']      = $this->ticket_booking_model->getCloseStatus();
		$data['invoice_number']    = $this->ticket_booking_model->getInvoiceNumber();
		$data['refund_status']     = $this->ticket_booking_model->getRefundStatus();
		$data['refund_number']     = $this->ticket_booking_model->getRefundInvoiceNumber();
		//get selected ticket details
		$showTktVal			       = $this->ticket_booking_model->getCorresTicketDetails();
		$this->txtCarrier1         = $showTktVal->vCarrier1;
		$this->txtFlightNo1        = $showTktVal->vFlight1;
		$this->txtClass1           = $showTktVal->vClass1;
		$this->txtTravelDate1      = date('d-m-Y',strtotime($showTktVal->dTravelDate1));
		$this->txtSector1          = $showTktVal->vSector1;
		$this->txtSector12         = $showTktVal->vSector12;
		$this->txtStatus1          = $showTktVal->vStatus1;
		$this->txtTimeDep1         = $showTktVal->vTimeDept1;
		$this->txtTimeArr1         = $showTktVal->vTimeArr1;
		$this->txtDay1             = $showTktVal->vDay1;
		
		switch($showTktVal->dTravelDate2)
		{
			case "1969-12-31":
				$this->txtTravelDate2 = '';
			break;
			
			case "1970-01-01":
				$this->txtTravelDate2 = '';
			break;
			
			case "0000-00-00":
				$this->txtTravelDate2 = '';
			break;
			
			default:
				$this->txtTravelDate2 = date('d-m-Y',strtotime($showTktVal->dTravelDate2));
			break;
		}//end switch
		
		switch($showTktVal->dTravelDate3)
		{
			case "1969-12-31":
				$this->txtTravelDate3 = '';
			break;
			
			case "1970-01-01":
				$this->txtTravelDate3 = '';
			break;
			
			case "0000-00-00":
				$this->txtTravelDate3 = '';
			break;
			
			default:
				$this->txtTravelDate3 = date('d-m-Y',strtotime($showTktVal->dTravelDate3));
			break;
		}//end switch
		
		switch($showTktVal->dTravelDate4)
		{
			case "1969-12-31":
				$this->txtTravelDate4 = '';
			break;
			
			case "1970-01-01":
				$this->txtTravelDate4 = '';
			break;
			
			case "0000-00-00":
				$this->txtTravelDate4 = '';
			break;
			
			default:
				$this->txtTravelDate4 = date('d-m-Y',strtotime($showTktVal->dTravelDate4));
			break;
		}//end switch
				
		$this->txtCarrier2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vCarrier2;
		$this->txtFlightNo2        = ($this->txtTravelDate2 == '')?'':$showTktVal->vFlight2;
		$this->txtClass2           = ($this->txtTravelDate2 == '')?'':$showTktVal->vClass2;
	   // $this->txtTravelDate2      = $showTktVal->dTravelDate2;
		$this->txtSector2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector2;
		$this->txtSector22         = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector22;
		$this->txtStatus2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vStatus2;
		$this->txtTimeDep2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeDept2;
		$this->txtTimeArr2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeArr2;
		$this->txtDay2             = ($this->txtTravelDate2 == '')?'':$showTktVal->vDay2;
		$this->txtCarrier3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vCarrier3;
		$this->txtFlightNo3        = ($this->txtTravelDate3 == '')?'':$showTktVal->vFlight3;
		$this->txtClass3           = ($this->txtTravelDate3 == '')?'':$showTktVal->vClass3;
		//$this->txtTravelDate3      = $showTktVal->dTravelDate3;
		$this->txtSector3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector3;
		$this->txtSector33         = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector33;
		$this->txtStatus3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vStatus3;
		$this->txtTimeDep3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeDept3;
		$this->txtTimeArr3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeArr3;
		$this->txtDay3             = ($this->txtTravelDate3 == '')?'':$showTktVal->vDay3;
		$this->txtCarrier4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vCarrier4;
		$this->txtFlightNo4        = ($this->txtTravelDate4 == '')?'':$showTktVal->vFlight4;
		$this->txtClass4           = ($this->txtTravelDate4 == '')?'':$showTktVal->vClass4;
		//$this->txtTravelDate4      = $showTktVal->dTravelDate4;
		$this->txtSector4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector4;
		$this->txtSector44         = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector44;
		$this->txtStatus4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vStatus4;
		$this->txtTimeDep4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeDept4;
		$this->txtTimeArr4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeArr4;
		$this->txtDay4             = ($this->txtTravelDate4 == '')?'':$showTktVal->vDay4;
		$this->txtSegment		   = $showTktVal->vSegment;
		$this->txtDestination	   = $showTktVal->vDestination;

		//get selected ticket fare details
		$showFareVal			   = $this->ticket_booking_model->getCorresFareDetails();
		$this->ddlFare             = $showFareVal->vFare;
		$this->ddlFareCode         = $showFareVal->nFareCode;
		$this->txtFcFare           = $showFareVal->vFCFare;
		$this->txtFcTax            = $showFareVal->vFCTax;
		$this->txtFace             = $showFareVal->vFaceValue;
		$this->txtMktFare          = $showFareVal->vMKTFare;
		$this->txtCommTax          = $showFareVal->nComm;
		$this->txtCommAmt          = $showFareVal->nCommAmt;
		$this->txtGSATax           = $showFareVal->nGSAComm;
		$this->txtGSAAmt           = $showFareVal->nGSAAmt;
		$this->txtORCTax           = $showFareVal->nORC;
		$this->txtORCAmt           = $showFareVal->nORCAmt;
		$this->txtFcTax1           = $showFareVal->vTax1;
		$this->txtFcTax2           = $showFareVal->vTax2;
		$this->txtFcTax3           = $showFareVal->vTax3;
		$this->txtTotalTax         = $showFareVal->vTaxTotal;
		$this->txtNetVendor        = $showFareVal->nNetVendor;
		$this->ddlAccname          = $showFareVal->nAcc;
		$this->ddlAccHead          = $showFareVal->nAccHead;
		$this->txtSalesAmt         = $showFareVal->vSalesAmt;
				
		//get selected conjuction ticket details
		$tktId  				   = $showVal->nTId;
		$this->conjId			   = $this->common_model->get_field_name('TicketBooking','nTId',
																			" WHERE vConjTkt = '".$tktId."'");
		$showConjVal			   = $this->ticket_booking_model->getCorresConjuctionDetails($this->conjId);

		$this->txtConjTkt          = $this->common_model->get_field_name('TicketBooking','vTicketNumber',
																			" WHERE nTId = '".$this->conjId."'");
		$this->txtCarrierTkt1      = $showConjVal->vCarrier1;
		$this->txtFlightNoTkt1     = $showConjVal->vFlight1;
		$this->txtClassTkt1        = $showConjVal->vClass1;
		$this->txtTravelDateTkt1   = date('d-m-Y',strtotime($showConjVal->dTravelDate1));
		$this->txtSectorTkt1       = $showConjVal->vSector1;
		$this->txtSectorTkt12      = $showConjVal->vSector12;
		$this->txtStatusTkt1       = $showConjVal->vStatus1;
		$this->txtTimeDepTkt1      = $showConjVal->vTimeDept1;
		$this->txtTimeArrTkt1      = $showConjVal->vTimeArr1;
		$this->txtDayTkt1          = $showConjVal->vDay1;
		$this->txtCarrierTkt2      = $showConjVal->vCarrier2;
		$this->txtFlightNoTkt2     = $showConjVal->vFlight2;
		$this->txtClassTkt2        = $showConjVal->vClass2;
		$this->txtTravelDateTkt2   = date('d-m-Y',strtotime($showConjVal->dTravelDate2));
		$this->txtSectorTkt2       = $showConjVal->vSector2;
		$this->txtSectorTkt22      = $showConjVal->vSector22;
		$this->txtStatusTkt2       = $showConjVal->vStatus2;
		$this->txtTimeDepTkt2      = $showConjVal->vTimeDept2;
		$this->txtTimeArrTkt2      = $showConjVal->vTimeArr2;
		$this->txtDayTkt2          = $showConjVal->vDay2;
		$this->txtCarrierTkt3      = $showConjVal->vCarrier3;
		$this->txtFlightNoTkt3     = $showConjVal->vFlight3;
		$this->txtClassTkt3        = $showConjVal->vClass3;
		$this->txtTravelDateTkt3   = date('d-m-Y',strtotime($showConjVal->dTravelDate3));
		$this->txtSectorTkt3       = $showConjVal->vSector3;
		$this->txtSectorTkt33      = $showConjVal->vSector33;
		$this->txtStatusTkt3       = $showConjVal->vStatus3;
		$this->txtTimeDepTkt3      = $showConjVal->vTimeDept3;
		$this->txtTimeArrTkt3      = $showConjVal->vTimeArr3;
		$this->txtDayTkt3          = $showConjVal->vDay3;
		$this->txtCarrierTkt4      = $showConjVal->vCarrier4;
		$this->txtFlightNoTkt4     = $showConjVal->vFlight4;
		$this->txtClassTkt4        = $showConjVal->vClass4;
		$this->txtTravelDateTkt4   = date('d-m-Y',strtotime($showConjVal->dTravelDate4));
		$this->txtSectorTkt4       = $showConjVal->vSector4;
		$this->txtSectorTkt44      = $showConjVal->vSector44;
		$this->txtStatusTkt4       = $showConjVal->vStatus4;
		$this->txtTimeDepTkt4      = $showConjVal->vTimeDept4;
		$this->txtTimeArrTkt4      = $showConjVal->vTimeArr4;
		$this->txtDayTkt4          = $showConjVal->vDay4;
		$this->txtSegmentCnjTkt	   = $showConjVal->vSegment;
		$this->txtDestinationCnjTkt= $showConjVal->vDestination;

		//get selected ticket billing details
		$showBillingVal			   = $this->ticket_booking_model->getCorresBillingDetails();
		$this->txtInvoice          = $showBillingVal->vInvoice;
		$this->ddlMode       	   = $showBillingVal->vModeofpay;
		$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
		$this->txtSubaccName       = $showBillingVal->vSubAcname;
		$this->ddlAgentAc          = $showBillingVal->nAgentAc;
		$this->txtAgentAmt         = $showBillingVal->vAgentAmt;
		$this->txtLPO              = $showBillingVal->vLPO;
		$this->txtSalesAmt1        = $showBillingVal->vSalesAmt;
		$this->txtDiscount         = $showBillingVal->vDiscount;
		$this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
		$this->txtCollected        = $showBillingVal->vCollected;
		$this->txtServicePerc      = $showBillingVal->vServicePercent;
		$this->txtServiceAmt       = $showBillingVal->vServiceAmt;
		
		//get selected ticket passenger details
		$this->txtRemarks          = $showFareVal->vRemarks;
		$this->txtPassengerId      = $showFareVal->vPassengerId;
		$this->txtPassengerContact = $showFareVal->vPassengerContact;
	
        $mydata = $this->load->view('ticket_booking/export_files/print_ticket_booking',$data,true);
        $_SESSION['print_ticket_booking'] = $mydata;
        $_SESSION['print_ticket_booking_pgSz'] = 'A4';
        $_SESSION['print_ticket_booking_lp'] = 'P';
			
    $this->load->view('general/title');
		$this->load->view('ticket_booking/print_ticket_booking',$data);
	}//end function

    function printAgentPreview()
	{
		$this->load->helper(array('form', 'url'));
    session_start();

		//$this->catId   = $this->input->post('id');
		$this->catId   = $this->uri->segment(3);

		$showVal	          	   = $this->ticket_booking_model->getCorresTicketbooking();
		$this->ddlDivision	  	   = $showVal->nDivision;
		$this->ddlLocation	       = $showVal->nLocation;
		$this->txtDate  	  	   = date('d-m-Y',strtotime($showVal->dDate));
		$this->txtDocNumber        = $showVal->vDocNumber;
		$this->ddlAirline   	   = $showVal->nAirline;
		$this->ddlTktname 	       = $showVal->vTicketNumber;
		$this->ddlGDS 		       = $showVal->nGDS;
		$this->ddlType	     	   = $showVal->vType;
		$this->ddlBooked     	   = $showVal->vBookedBy;
		$this->ddlIssued       	   = $showVal->vIssuedBy;
		$this->txtPassenger		   = $showVal->vPassenger;
		$this->txtOriginalTkt	   = $showVal->vOriginalTkt;
		$this->txtGdsPNR		   = $showVal->vGdspnr;
		$this->txtAirPNR		   = $showVal->vAirpnr;
		$this->ddlCurrency		   = $showVal->nCurrency;
		$this->txtRate   		   = $showVal->nRate;
		$this->txtTktName		   = $showVal->vTicktManual;
		$data['list_loc_value']    = $this->ticket_booking_model->getCorrActiveLocation($this->ddlDivision);
		$data['close_status']      = $this->ticket_booking_model->getCloseStatus();
		$data['invoice_number']    = $this->ticket_booking_model->getInvoiceNumber();
		$data['refund_status']     = $this->ticket_booking_model->getRefundStatus();
		$data['refund_number']     = $this->ticket_booking_model->getRefundInvoiceNumber();
		//get selected ticket details
		$showTktVal			       = $this->ticket_booking_model->getCorresTicketDetails();
		$this->txtCarrier1         = $showTktVal->vCarrier1;
		$this->txtFlightNo1        = $showTktVal->vFlight1;
		$this->txtClass1           = $showTktVal->vClass1;
		$this->txtTravelDate1      = date('d-m-Y',strtotime($showTktVal->dTravelDate1));
		$this->txtSector1          = $showTktVal->vSector1;
		$this->txtSector12         = $showTktVal->vSector12;
		$this->txtStatus1          = $showTktVal->vStatus1;
		$this->txtTimeDep1         = $showTktVal->vTimeDept1;
		$this->txtTimeArr1         = $showTktVal->vTimeArr1;
		$this->txtDay1             = $showTktVal->vDay1;

		switch($showTktVal->dTravelDate2)
		{
			case "1969-12-31":
				$this->txtTravelDate2 = '';
			break;

			case "1970-01-01":
				$this->txtTravelDate2 = '';
			break;

			case "0000-00-00":
				$this->txtTravelDate2 = '';
			break;

			default:
				$this->txtTravelDate2 = date('d-m-Y',strtotime($showTktVal->dTravelDate2));
			break;
		}//end switch

		switch($showTktVal->dTravelDate3)
		{
			case "1969-12-31":
				$this->txtTravelDate3 = '';
			break;

			case "1970-01-01":
				$this->txtTravelDate3 = '';
			break;

			case "0000-00-00":
				$this->txtTravelDate3 = '';
			break;

			default:
				$this->txtTravelDate3 = date('d-m-Y',strtotime($showTktVal->dTravelDate3));
			break;
		}//end switch

		switch($showTktVal->dTravelDate4)
		{
			case "1969-12-31":
				$this->txtTravelDate4 = '';
			break;

			case "1970-01-01":
				$this->txtTravelDate4 = '';
			break;

			case "0000-00-00":
				$this->txtTravelDate4 = '';
			break;

			default:
				$this->txtTravelDate4 = date('d-m-Y',strtotime($showTktVal->dTravelDate4));
			break;
		}//end switch

		$this->txtCarrier2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vCarrier2;
		$this->txtFlightNo2        = ($this->txtTravelDate2 == '')?'':$showTktVal->vFlight2;
		$this->txtClass2           = ($this->txtTravelDate2 == '')?'':$showTktVal->vClass2;
	   // $this->txtTravelDate2      = $showTktVal->dTravelDate2;
		$this->txtSector2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector2;
		$this->txtSector22         = ($this->txtTravelDate2 == '')?'':$showTktVal->vSector22;
		$this->txtStatus2          = ($this->txtTravelDate2 == '')?'':$showTktVal->vStatus2;
		$this->txtTimeDep2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeDept2;
		$this->txtTimeArr2         = ($this->txtTravelDate2 == '')?'':$showTktVal->vTimeArr2;
		$this->txtDay2             = ($this->txtTravelDate2 == '')?'':$showTktVal->vDay2;
		$this->txtCarrier3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vCarrier3;
		$this->txtFlightNo3        = ($this->txtTravelDate3 == '')?'':$showTktVal->vFlight3;
		$this->txtClass3           = ($this->txtTravelDate3 == '')?'':$showTktVal->vClass3;
		//$this->txtTravelDate3      = $showTktVal->dTravelDate3;
		$this->txtSector3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector3;
		$this->txtSector33         = ($this->txtTravelDate3 == '')?'':$showTktVal->vSector33;
		$this->txtStatus3          = ($this->txtTravelDate3 == '')?'':$showTktVal->vStatus3;
		$this->txtTimeDep3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeDept3;
		$this->txtTimeArr3         = ($this->txtTravelDate3 == '')?'':$showTktVal->vTimeArr3;
		$this->txtDay3             = ($this->txtTravelDate3 == '')?'':$showTktVal->vDay3;
		$this->txtCarrier4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vCarrier4;
		$this->txtFlightNo4        = ($this->txtTravelDate4 == '')?'':$showTktVal->vFlight4;
		$this->txtClass4           = ($this->txtTravelDate4 == '')?'':$showTktVal->vClass4;
		//$this->txtTravelDate4      = $showTktVal->dTravelDate4;
		$this->txtSector4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector4;
		$this->txtSector44         = ($this->txtTravelDate4 == '')?'':$showTktVal->vSector44;
		$this->txtStatus4          = ($this->txtTravelDate4 == '')?'':$showTktVal->vStatus4;
		$this->txtTimeDep4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeDept4;
		$this->txtTimeArr4         = ($this->txtTravelDate4 == '')?'':$showTktVal->vTimeArr4;
		$this->txtDay4             = ($this->txtTravelDate4 == '')?'':$showTktVal->vDay4;
		$this->txtSegment		   = $showTktVal->vSegment;
		$this->txtDestination	   = $showTktVal->vDestination;

		//get selected ticket fare details
		$showFareVal			   = $this->ticket_booking_model->getCorresFareDetails();
		$this->ddlFare             = $showFareVal->vFare;
		$this->ddlFareCode         = $showFareVal->nFareCode;
		$this->txtFcFare           = $showFareVal->vFCFare;
		$this->txtFcTax            = $showFareVal->vFCTax;
		$this->txtFace             = $showFareVal->vFaceValue;
		$this->txtMktFare          = $showFareVal->vMKTFare;
		$this->txtCommTax          = $showFareVal->nComm;
		$this->txtCommAmt          = $showFareVal->nCommAmt;
		$this->txtGSATax           = $showFareVal->nGSAComm;
		$this->txtGSAAmt           = $showFareVal->nGSAAmt;
		$this->txtORCTax           = $showFareVal->nORC;
		$this->txtORCAmt           = $showFareVal->nORCAmt;
		$this->txtFcTax1           = $showFareVal->vTax1;
		$this->txtFcTax2           = $showFareVal->vTax2;
		$this->txtFcTax3           = $showFareVal->vTax3;
		$this->txtTotalTax         = $showFareVal->vTaxTotal;
		$this->txtNetVendor        = $showFareVal->nNetVendor;
		$this->ddlAccname          = $showFareVal->nAcc;
		$this->ddlAccHead          = $showFareVal->nAccHead;
		$this->txtSalesAmt         = $showFareVal->vSalesAmt;

		//get selected conjuction ticket details
		$tktId  				   = $showVal->nTId;
		$this->conjId			   = $this->common_model->get_field_name('TicketBooking','nTId',
																			" WHERE vConjTkt = '".$tktId."'");
		$showConjVal			   = $this->ticket_booking_model->getCorresConjuctionDetails($this->conjId);

		$this->txtConjTkt          = $this->common_model->get_field_name('TicketBooking','vTicketNumber',
																			" WHERE nTId = '".$this->conjId."'");
		$this->txtCarrierTkt1      = $showConjVal->vCarrier1;
		$this->txtFlightNoTkt1     = $showConjVal->vFlight1;
		$this->txtClassTkt1        = $showConjVal->vClass1;
		$this->txtTravelDateTkt1   = date('d-m-Y',strtotime($showConjVal->dTravelDate1));
		$this->txtSectorTkt1       = $showConjVal->vSector1;
		$this->txtSectorTkt12      = $showConjVal->vSector12;
		$this->txtStatusTkt1       = $showConjVal->vStatus1;
		$this->txtTimeDepTkt1      = $showConjVal->vTimeDept1;
		$this->txtTimeArrTkt1      = $showConjVal->vTimeArr1;
		$this->txtDayTkt1          = $showConjVal->vDay1;
		$this->txtCarrierTkt2      = $showConjVal->vCarrier2;
		$this->txtFlightNoTkt2     = $showConjVal->vFlight2;
		$this->txtClassTkt2        = $showConjVal->vClass2;
		$this->txtTravelDateTkt2   = date('d-m-Y',strtotime($showConjVal->dTravelDate2));
		$this->txtSectorTkt2       = $showConjVal->vSector2;
		$this->txtSectorTkt22      = $showConjVal->vSector22;
		$this->txtStatusTkt2       = $showConjVal->vStatus2;
		$this->txtTimeDepTkt2      = $showConjVal->vTimeDept2;
		$this->txtTimeArrTkt2      = $showConjVal->vTimeArr2;
		$this->txtDayTkt2          = $showConjVal->vDay2;
		$this->txtCarrierTkt3      = $showConjVal->vCarrier3;
		$this->txtFlightNoTkt3     = $showConjVal->vFlight3;
		$this->txtClassTkt3        = $showConjVal->vClass3;
		$this->txtTravelDateTkt3   = date('d-m-Y',strtotime($showConjVal->dTravelDate3));
		$this->txtSectorTkt3       = $showConjVal->vSector3;
		$this->txtSectorTkt33      = $showConjVal->vSector33;
		$this->txtStatusTkt3       = $showConjVal->vStatus3;
		$this->txtTimeDepTkt3      = $showConjVal->vTimeDept3;
		$this->txtTimeArrTkt3      = $showConjVal->vTimeArr3;
		$this->txtDayTkt3          = $showConjVal->vDay3;
		$this->txtCarrierTkt4      = $showConjVal->vCarrier4;
		$this->txtFlightNoTkt4     = $showConjVal->vFlight4;
		$this->txtClassTkt4        = $showConjVal->vClass4;
		$this->txtTravelDateTkt4   = date('d-m-Y',strtotime($showConjVal->dTravelDate4));
		$this->txtSectorTkt4       = $showConjVal->vSector4;
		$this->txtSectorTkt44      = $showConjVal->vSector44;
		$this->txtStatusTkt4       = $showConjVal->vStatus4;
		$this->txtTimeDepTkt4      = $showConjVal->vTimeDept4;
		$this->txtTimeArrTkt4      = $showConjVal->vTimeArr4;
		$this->txtDayTkt4          = $showConjVal->vDay4;
		$this->txtSegmentCnjTkt	   = $showConjVal->vSegment;
		$this->txtDestinationCnjTkt= $showConjVal->vDestination;

		//get selected ticket billing details
		$showBillingVal			   = $this->ticket_booking_model->getCorresBillingDetails();
		$this->txtInvoice          = $showBillingVal->vInvoice;
		$this->ddlMode       	   = $showBillingVal->vModeofpay;
		$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
		$this->txtSubaccName       = $showBillingVal->vSubAcname;
		$this->ddlAgentAc          = $showBillingVal->nAgentAc;
		$this->txtAgentAmt         = $showBillingVal->vAgentAmt;
		$this->txtLPO              = $showBillingVal->vLPO;
		$this->txtSalesAmt1        = $showBillingVal->vSalesAmt;
		$this->txtDiscount         = $showBillingVal->vDiscount;
		$this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;
		$this->txtCollected        = $showBillingVal->vCollected;
		$this->txtServicePerc      = $showBillingVal->vServicePercent;
		$this->txtServiceAmt       = $showBillingVal->vServiceAmt;

		//get selected ticket passenger details
		$this->txtRemarks          = $showFareVal->vRemarks;
		$this->txtPassengerId      = $showFareVal->vPassengerId;
		$this->txtPassengerContact = $showFareVal->vPassengerContact;

    $mydata = $this->load->view('ticket_booking/export_files/print_agent_copy',$data,true);
    $_SESSION['print_TB_agent_copy'] = $mydata;
    $_SESSION['print_TB_agent_copy_pgSz'] = 'A4';
    $_SESSION['print_TB_agent_copy_lp'] = 'P';

    $this->load->view('general/title');
		$this->load->view('ticket_booking/print_agent_copy',$data);
	}//end function

    function getIssuedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedBy();
      $this->load->view('ticket_booking/show_issuedBy',$data);
    }

    function getBookedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedBy();
      $this->load->view('ticket_booking/show_bookedBy',$data);
    }

    function getVendorAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_acc_value'] = $this->common_model->getVendorMasterAccounts();
      $this->load->view('ticket_booking/show_vendorAccounts',$data);

    }

    function getCustAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_custacc_value'] = $this->common_model->getCustomerMasterAccounts();
      $this->load->view('ticket_booking/show_customerAccounts',$data);

    }



  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('ticket_booking/show_emailDetails',$data);

  }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
