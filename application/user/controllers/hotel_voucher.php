<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel_voucher extends CI_Controller {



	function __construct()

	{
header_remove('Set-Cookie');
ini_set('memory_limit', '-1');
		parent::__construct();

		$this->load->model('hotel_voucher_model');

	}



	function showlist()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = HOTELVOUCHERMANAGEMENT;

		$this->common_model->check_admin_authorization();



        $this->session->unset_userdata('backURL');



		//site Name

		$target 			= '.';

		$this->common_model->_paging($this->hotel_voucher_model->getAllHotelVoucherCount(), $target,'hotel_voucher/showlist/'.$this->uri->segment(3));

		$data['list_emp_value']     = $this->hotel_voucher_model->getActiveEmployee(); 

		$data['list_city_value']     = $this->hotel_voucher_model->getActiveCity(); 

		$data['list_vendor_value']     = $this->hotel_voucher_model->getAccounts(); 

		$data['list_loc_value']     = $this->hotel_voucher_model->getActiveLocations(); 





		$data['list_value'] = $this->hotel_voucher_model->getAllHotelVoucher();

		$this->count_rows   = count($data['list_value']);

//User Access aa

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel_voucher/showlist');



		$this->load->view('general/template_top',$data);

		$this->load->view('hotel_voucher/hotel_voucher');

		$this->load->view('general/template_bottom');

	}//end function

	

	function editHotelvoucher()

	{

		$this->common_model->check_admin_authorization();





		//site Name

		$data['title'] 	            = $this->common_model->get_site_title(); 

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');

	

		$id 						 = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';



//User Access aa

    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel_voucher/showlist');

    $data['action_access'] = $action_access;

    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){

      if($this->uri->segment(6) == 'closed')

        $backUrlAddr = trim($this->session->userdata('backURL2'));

      else

        $backUrlAddr = trim($this->session->userdata('backURL'));



      if( $backUrlAddr != '')

        redirect(site_url($backUrlAddr));

      else

        redirect(site_url("hotel_voucher/showlist/".$this->uri->segment(3).'/no_access'));

    }



    $data['list_div_value']	     = $this->hotel_voucher_model->getActiveDivision();

		$data['list_country_value']	 = $this->hotel_voucher_model->getActiveCountry();

		$data['list_trans_value']    = $this->hotel_voucher_model->getActiveTransactions();

		$data['list_tkt_value']      = $this->hotel_voucher_model->getActiveStock();

		$data['list_type_value']     = $this->hotel_voucher_model->getActiveType();

		$data['list_emp_value']      = $this->hotel_voucher_model->getActiveEmployee();

		$data['list_accode_value']   = $this->hotel_voucher_model->getAccounts();

		$data['list_pass_value']     = $this->hotel_voucher_model->getActiveCustomer();

		$data['list_curr_value']     = $this->hotel_voucher_model->getActiveCurrency();

		$data['list_room_type']      = $this->hotel_voucher_model->getRoomType();

		$data['list_mode_value']	 = $this->hotel_voucher_model->getActiveMode();

		$data['list_agentacc_value'] = $this->hotel_voucher_model->getAgentAccounts();

		$data['list_services_value'] = $this->hotel_voucher_model->getActiveServices();

		$data['list_transfer_value'] = $this->hotel_voucher_model->getActiveTransfer();
		$data['list_meal_value'] = $this->hotel_voucher_model->getActiveMeal();

		$data['list_custacc_value']  = $this->hotel_voucher_model->getActiveCustomerAccounts();

		

		switch($id)

		{

			case "a":

				$this->showTitle  	  	  = ADDNEWHOTELVOUCHER;

				$this->btnVal	  	  	  = ADDCONTINUE;

				$this->btnVal2	  	  	  = SAVEEXIT;



                $data['list_acchead_value']  = $this->hotel_voucher_model->getActiveAccountHead();

				$data['list_city_value']  = $this->hotel_voucher_model->getCities();

				$data['list_loc_value']   = $this->hotel_voucher_model->getLocation();

                $data['list_hotel_value'] = $this->hotel_voucher_model->getActiveHotels();



				$this->ddlDivision 	      = $this->input->post('ddlDivision');

				$this->ddlLocation  	  = $this->input->post('ddlLocation');

				$this->txtDate  	      = date('d-m-Y');

				$this->ddlTrans  	  	  = $this->input->post('ddlTrans');

				$this->txtDocNumber  	  = $this->getAutomaticDocNumber();

				$this->ddlType  	  	  = $this->input->post('ddlType');

				$this->ddlTktname   	  = $this->input->post('ddlTktname');

				$this->txtFileId  	      = $this->input->post('txtFileId');

				$this->ddlBooked  	  	  = $this->input->post('ddlBooked');

				$this->ddlIssued 	  	  = $this->input->post('ddlIssued');

				$this->ddlPassenger 	  = $this->input->post('ddlPassenger');

				$this->ddlCountry 	      = $this->input->post('ddlCountry');

				$this->ddlCity      	  = $this->input->post('ddlCity');

				$this->ddlHotel 	  	  = $this->input->post('ddlHotel');

				$this->txtRoomType 	  	  = $this->input->post('txtRoomType');

				$this->txtNoOfUnit   	  = $this->input->post('txtNoOfUnit');

				$this->ddlServices 	      = $this->input->post('ddlServices');

				$this->txtCheckIn 	      = $this->input->post('txtCheckIn');

				$this->txtCheckOut 	      = $this->input->post('txtCheckOut');

				$this->txtNights 	      = $this->input->post('txtNights');

				$this->txtAdult 	  	  = $this->input->post('txtAdult');

				$this->txtChild 	  	  = $this->input->post('txtChild');

				$this->txtRemarks   	  = $this->input->post('txtRemarks');

				$this->ddlAcCode 	      = $this->input->post('ddlAcCode');

				$this->ddlAccHead 	      = $this->input->post('ddlAccHead');

				$this->ddlCurrency 	      = $this->input->post('ddlCurrency');

				$this->txtRate 	      	  = $this->input->post('txtRate');

				$this->txtRateNetFcTotal  = $this->input->post('txtRateNetFcTotal');

				$this->txtTotalFc   	  = $this->input->post('txtTotalFc');

				$this->txtRateNetDcTotal  = $this->input->post('txtRateNetDcTotal');

				$this->txtTotalDc 	      = $this->input->post('txtTotalDc');

				$this->txtAgentConfId 	  = $this->input->post('txtAgentConfId');

				$this->txtHotelConfId     = $this->input->post('txtHotelConfId');

				

				//get hotel details

				$this->txtPayRoomType 	  = $this->input->post('txtPayRoomType');

				$this->txtChkinDate 	  = $this->input->post('txtChkinDate');

				$this->txtChkoutDate  	  = $this->input->post('txtChkoutDate');

				$this->txtNight 	 	  = $this->input->post('txtNight');

				$this->txtNoofRooms  	  = $this->input->post('txtNoofRooms');

				$this->txtRateNetFc 	  = $this->input->post('txtRateNetFc');

				$this->txtTotalinFc 	  = $this->input->post('txtTotalinFc');

				$this->txtRateNetDc  	  = $this->input->post('txtRateNetDc');

				$this->txtTotalinDc 	  = $this->input->post('txtTotalinDc');

				$this->txtExchangeRate    = $this->input->post('txtExchangeRate');



				//get billing details

				$this->txtInvoice 	  	  = $this->input->post('txtInvoice');

				$this->ddlMode 	  		  = $this->input->post('ddlMode');

				$this->ddlBillingAcCode   = $this->input->post('ddlBillingAcCode');

				$this->ddlAgentAc 	  	  = $this->input->post('ddlAgentAc');

				$this->txtAgentAmt 	  	  = $this->input->post('txtAgentAmt');

				$this->txtLPO   		  = $this->input->post('txtLPO');

				$this->txtSalesAmt   	  = $this->input->post('txtSalesAmt');

				$this->txtDiscount        = $this->input->post('txtDiscount');

				$this->txtDiscountAmt     = $this->input->post('txtDiscountAmt');

				$this->txtCollected 	  = $this->input->post('txtCollected');

				$this->txtServicePerc 	  = $this->input->post('txtServicePerc');

				$this->txtServiceAmt 	  = $this->input->post('txtServiceAmt');

                $this->txtTktName		  = $this->input->post('txtTktName');



				$this->chkValYes  	  	  = 'checked="checked"';

				$this->chkValNo	  	  	  = '';

				$this->catId	  	  	  = '';

			break;

			

			case "e":

				$this->showTitle  	  	  = EDITHOTELVOUCHER;

				$this->btnVal	      	  = UPDATEHOTELVOUCHER;

				$this->catId	      	  = $this->uri->segment(5);

				

				if(intval($this->input->post('nId')) != 0)

				{

					$this->catId  	  	  = intval($this->input->post('nId'));

				}//end if

				

				$showVal	          	  = $this->hotel_voucher_model->getCorresHotelvoucher();

				$this->ddlDivision	  	  = $showVal->nDivision;

				$this->ddlLocation		  = $showVal->nLocation;

				$this->txtDate  	  	  =  date('d-m-Y', strtotime($showVal->dDate));

				$this->txtBookingId  	  = $showVal->vBookingId;

				$this->ddlTrans  	  	  = $showVal->nTransaction;

				$this->txtDocNumber       = $showVal->vDocNumber;

				$this->ddlType  	  	  = $showVal->vType;

				$this->ddlTktname   	  = $showVal->vVoucherId;

				$this->txtFileId  	      = $showVal->vFileId;

				$this->ddlBooked     	  = $showVal->vBookedBy;

				$this->ddlIssued       	  = $showVal->vIssuedBy;

				$this->txtPassenger		  = $showVal->vPassenger;

				$this->ddlCountry	  	  = $showVal->nCountry;

				$this->ddlCity   		  = $showVal->nCity;

				$this->ddlHotel 	  	  = $showVal->vHotelName;

				$this->txtRoomType 	  	  = $showVal->vRoomType;

				$this->txtNoOfUnit   	  = $showVal->vNoOfUnit;

				$this->ddlServices 	      = $showVal->vServices;

				$this->ddlTransfer 	      = $showVal->nTransfer;

				$this->txtExtraServ 	  = $showVal->vExtraService;

                $this->txtTktName		  = $showVal->vTicktManual;



				$this->txtCheckIn 	      =  date('d-m-Y', strtotime($showVal->dCheckIn));

				$this->txtCheckOut      =  date('d-m-Y', strtotime($showVal->dCheckOut));

				$this->txtNights 	      = $showVal->vNight;

				$this->txtChild 	  	  = $showVal->vChild;

				$this->txtAdult			  = $showVal->vAdult;

				$this->txtRemarks   	  = $showVal->vRemarks;

				$this->ddlAcCode 	      = $showVal->nAcCode; 

				$this->ddlAccHead 	      = $showVal->ddlAccHead;

				$this->ddlCurrency 	      = $showVal->nCurrency;

				$this->txtRate 	      	  = $showVal->nRate;

				$this->txtRateNetFcTotal  = $showVal->vTotalRTFC;

				$this->txtTotalFc   	  = $showVal->vTotalFC;

				$this->txtRateNetDcTotal  = $showVal->vTotalRTDC;

				$this->txtTotalDc 	      = $showVal->vTotalDC;

				$this->txtAgentConfId 	  = $showVal->vAgentConfId;

				$this->txtHotelConfId     = $showVal->vHotelConfId;



                $data['list_acchead_value'] = $this->hotel_voucher_model->getActiveAccHead($this->ddlAcCode);

				$data['list_city_value']  = $this->hotel_voucher_model->getCorrActiveCities($this->ddlCountry);

                $data['list_loc_value']   = $this->hotel_voucher_model->getCorrActiveLocation($this->ddlDivision);

                $data['list_hotel_value'] = $this->hotel_voucher_model->getHotelValues($this->ddlCountry,$this->ddlCity);

                $data['close_status']     = $this->hotel_voucher_model->getCloseStatus();

                $data['invoice_number']   = $this->hotel_voucher_model->getInvoiceNumber();
				$data['invoice_id']      = $this->hotel_voucher_model->getInvoiceid();


                $data['refund_status']    = $this->hotel_voucher_model->getRefundStatus();

                $data['refund_number']    = $this->hotel_voucher_model->getRefundInvoiceNumber();



				//get selected hotel details

                $hotelValArray            = $this->hotel_voucher_model->getCorresHotelDetails();

                $data['txtPayRoomType']   = $hotelValArray['roomType'];

                $data['txtChkinDate']     = $hotelValArray['chkIndate'];

                $data['txtChkoutDate']    = $hotelValArray['chkOutdate'];

                $data['txtNight']         = $hotelValArray['night'];

                $data['txtNoofRooms']     = $hotelValArray['noofRooms'];

                $data['txtRateNetFc']     = $hotelValArray['rateNetFc'];

                $data['txtTotalinFc']     = $hotelValArray['totalFc'];

                $data['txtRateNetDc']     = $hotelValArray['rateNetDc'];

                $data['txtTotalinDc']     = $hotelValArray['totalDc'];

                $data['txtExchangeRate']  = $hotelValArray['exchange'];

                $data['chkHotelID']       = $hotelValArray['hotelId'];



                //get selected hotel billing details

				$showBillingVal			   = $this->hotel_voucher_model->getCorresBillingDetails();

				$this->ddlMode       	   = $showBillingVal->vModeofpay;

				$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;

				$this->txtSubaccName       = $showBillingVal->vSubAcname;

				$this->ddlAgentAc          = $showBillingVal->nAgentAc;

				$this->txtAgentAmt         = $showBillingVal->vAgentAmt;

				$this->txtLPO              = $showBillingVal->vLPO;

				$this->txtSalesAmt         = $showBillingVal->vSalesAmt;

				$this->txtDiscount         = $showBillingVal->vDiscount;

				$this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;

				$this->txtCollected        = $showBillingVal->vCollected;

				$this->txtServicePerc      = $showBillingVal->vServicePercent;

				$this->txtServiceAmt       = $showBillingVal->vServiceAmt;

				$this->chkHotelBillingID   = $showBillingVal->nHBId;



				//get selected hotel transfer details

				$showTransferVal		   = $this->hotel_voucher_model->getCorresTransferDetails();

				$this->txtArrDate          = $showTransferVal->dDate1;

				$this->txtArrFrom          = $showTransferVal->vArrivalFrom;

				$this->txtArrFlight	       = $showTransferVal->vFlightId1;

				$this->txtArrTime          = $showTransferVal->vArrivalTime;

				$this->txtDepDate          = $showTransferVal->dDate2;

				$this->txtDepTo            = $showTransferVal->vDepTo;

				$this->txtDepFlight        = $showTransferVal->vFlightId2;

				$this->txtDepTime          = $showTransferVal->vDepTime;

				$this->txtNoofGuest        = $showTransferVal->nNoofGuest;

				$this->txtVehicle          = $showTransferVal->vVehicle;

				$this->txtDriverCont       = $showTransferVal->vDriverContact;

				$this->txtEmrContact       = $showTransferVal->vEmergencyContact;

				$this->txtPickup           = $showTransferVal->vPickup;

				$this->txtPickupDate       = $showTransferVal->dPickupDate;

				$this->txtDrop             = $showTransferVal->vDrop;

				$this->txtRepHtl           = $showTransferVal->vRepHotel;

				$this->txtCityTour         = $showTransferVal->vCityTour;

				$this->txtDuration         = $showTransferVal->vDurTime;

				$this->txtTransferRemarks  = $showTransferVal->vRemarks;

				$this->chkHotelTransferID  = $showTransferVal->nHTId;

				

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



//User Access aa

		if ($this->form_validation->run() == FALSE ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))

		{

			$this->load->view('general/template_top',$data);

			$this->load->view('hotel_voucher/edit_hotel_voucher');

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

            $this->hotel_voucher_model->insertHotelVoucher();

          break;



          case "e":

            //Update into db

            $this->hotel_voucher_model->updateHotelVoucher();

          break;

        }//end switch

			redirect(site_url("hotel_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal));

			//}//end else

		}//end else	

	}//end function



	function getAutomaticDocNumber()

	{

		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='7'");

		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}HotelVoucher ORDER BY nHVID DESC LIMIT 0,1");

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

			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='7'");

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

		$this->ddlCurrency  = $this->input->post('ddlCurrency'); 

		$this->txtRate		= $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->ddlCurrency."'"); 

		echo $this->txtRate; 

	}//end function



    function getVendorCurrRate()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlCurrency  = $this->input->post('ddlCurrency');



        $this->ddlAcCode = $this->input->post('ddlAcCode');

        $this->vendorCur = $this->common_model->get_field_name('VendorMaster','nCurrency'," WHERE nVId='".$this->ddlAcCode."'");

        $this->ddlCurr   = $this->common_model->get_field_name('Currency','nId'," WHERE nId='".$this->vendorCur."'");

        $this->txtRate 	 = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->vendorCur."'");



        $valArray['currency'] = $this->ddlCurr;

        $valArray['rateVal']  = $this->txtRate;



        echo json_encode($valArray);

	}//end function



    //get agent amount when selected agent account

	function generateAgentAmount()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlAgentAc  = $this->input->post('ddlAgentAc');

		$this->txtAgentAmt = $this->common_model->get_field_name('Agent','vAmount'," WHERE nAId='".$this->ddlAgentAc."'"); 

		$this->txtAgentAmt;

	}//end function

	

	function filterDivision()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		

		$this->common_model->check_admin_authorization();

		//site Name

		$this->ddlDivision = $this->input->post('ddlDivision');

		$this->ddlLocation = $this->input->post('ddlLocation');

		$this->ddlTktname  = $this->input->post('ddlTktname');

		

		$data['list_loc_value'] = $this->hotel_voucher_model->filterLocation($this->ddlDivision);

		$this->load->view('hotel_voucher/show_location',$data);

	}//end function

        function filterCountry()

        {

                $this->load->helper('url');

                $this->load->helper(array('form', 'url'));



                $this->common_model->check_admin_authorization();

                //site Name

                $this->ddlCountry = $this->input->post('ddlCountry');

                $this->ddlCity    = $this->input->post('ddlCity');



                $data['list_city_value'] = $this->hotel_voucher_model->filterCity($this->ddlCountry);

                $this->load->view('hotel_voucher/show_city',$data);

        }//end function



	function filterCountry2()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		

		$this->common_model->check_admin_authorization();

		//site Name

		$this->ddlCountry2 = $this->input->post('ddlCountry2');

		$this->ddlCity2    = $this->input->post('ddlCity2');

		

		$data['list_city_value'] = $this->hotel_voucher_model->filterCity($this->ddlCountry2);

		$this->load->view('hotel_voucher/show_city2',$data);

	}//end function



    function filterHotel()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));



		$this->common_model->check_admin_authorization();

		//site Name

		$this->ddlCountry = $this->input->post('ddlCountry');

		$this->ddlCity    = $this->input->post('ddlCity');



		$data['list_hotel_value'] = $this->hotel_voucher_model->getHotelValues($this->ddlCountry,$this->ddlCity);

		$this->load->view('hotel_voucher/show_hotel',$data);

	}//end function



    function deleteRecord()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();



        $this->hotelID = $this->input->post('subCode');

        $this->hotel_voucher_model->deleteHotelDetails($this->hotelID);

    }//end function

	

	function delhotelvoucher()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = HOTELVOUCHERMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->hotel_voucher_model->deleteHotelVoucher();

	}//end function 

	

	function delrecords()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = HOTELVOUCHERMANAGEMENT;

		$this->common_model->check_admin_authorization();

		$this->hotel_voucher_model->deleteAllHotelVoucher();

	}//end function 



	function getStock()

	{

		$this->load->helper(array('form', 'url'));

		

		$loc                    = $this->input->post('ddlLocation');

		$this->ddlTktname       = $this->input->post('ddlTktname');

		$data['list_tkt_value'] = $this->hotel_voucher_model->getActiveLocStock($loc);

		

		$this->ddlTktname       = $this->hotel_voucher_model->chkActiveVal($this->ddlTktname);

		

		$this->load->view('hotel_voucher/show_stock',$data);

	}//end function



	function getAccountHead()

	{

		$this->load->helper(array('form', 'url'));

		

		$accName                    = $this->input->post('ddlAcCode');

		$data['list_acchead_value'] = $this->hotel_voucher_model->getActiveAccHead($accName);

		$this->load->view('hotel_voucher/show_acchead',$data);

	}//end function

function getAccountCur()

	{

		$this->load->helper(array('form', 'url'));

		

		$accName                    = $this->input->post('ddlAcCode');

		$data['list_curr_value'] = $this->hotel_voucher_model->getActiveAcccur($accName);

		//return $data;
		$this->load->view('hotel_voucher/show_acccur',$data);

	}//end function


	function filterCustomerAcc()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		

		$this->common_model->check_admin_authorization();

		

		$this->ddlMode 				= $this->input->post('ddlMode');

		$this->ddlCustomerAc 		= $this->input->post('ddlCustomerAc');

		

		$data['list_custacc_value'] = $this->hotel_voucher_model->generateCustAcc($this->ddlMode);

		

		$this->load->view('hotel_voucher/show_custacc',$data);

	}//end function



	//get hotel voucher details according to the tkt number selected

	function checkHotelDetails()

	{ 

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->btnVal	  	  	     = ADDCONTINUE;

		$this->btnVal2	  	  	     = SAVEEXIT;



        $data['list_div_value']	     = $this->hotel_voucher_model->getActiveDivision();

        $data['list_trans_value']    = $this->hotel_voucher_model->getActiveTransactions();

        $data['list_tkt_value']      = $this->hotel_voucher_model->getActiveStock();

        $data['list_type_value']     = $this->hotel_voucher_model->getActiveType();

        $data['list_emp_value']      = $this->hotel_voucher_model->getActiveEmployee();

        $data['list_pass_value']     = $this->hotel_voucher_model->getActiveCustomer();

        $data['list_curr_value']     = $this->hotel_voucher_model->getActiveCurrency();

        $data['list_fare_value']     = $this->hotel_voucher_model->getActiveType();

        $data['list_account_value']  = $this->hotel_voucher_model->getAccounts();

        $data['list_acc_value']      = $this->hotel_voucher_model->getAccounts();

        $data['list_acchead_value']  = $this->hotel_voucher_model->getActiveAccountHead();

        $data['list_custacc_value']  = $this->hotel_voucher_model->getActiveCustomerAccounts();

        $data['list_agentacc_value'] = $this->hotel_voucher_model->getAgentAccounts();

		$data['list_mode_value']	 = $this->hotel_voucher_model->getActiveMode();

		$data['list_accode_value']   = $this->hotel_voucher_model->getAccounts();

				

		$this->txtCopyTkt            = $this->input->post('txtCopyTkt');

		$this->htlId	             = $this->common_model->get_field_name('HotelVoucher','nHVId',

																              " WHERE vTicktManual = ".$this->txtCopyTkt." ");

		$showCopyVal      		  	 = $this->hotel_voucher_model->getSelectedHotelDetails($this->htlId);



		$this->ddlDivision	  	  	 = $showCopyVal->nDivision;

		$this->ddlLocation		  	 = $showCopyVal->nLocation;

		$this->txtDate  	  	  	 = $showCopyVal->dDate;

		$this->txtBookingId  	  	 = $showCopyVal->vBookingId;

		$this->ddlTrans  	  	  	 = $showCopyVal->nTransaction;

		$this->txtDocNumber       	 = $showCopyVal->vDocNumber;

		$this->ddlType  	  	  	 = $showCopyVal->vType;

		$this->ddlTktname   	  	 = $showCopyVal->vVoucherId;

		$this->txtFileId  	      	 = $showCopyVal->vFileId;

		$this->ddlBooked     	  	 = $showCopyVal->vBookedBy;

		$this->ddlIssued       	  	 = $showCopyVal->vIssuedBy;

		$this->txtPassenger		  	 = $showCopyVal->vPassenger;

		$this->ddlCountry	  	  	 = $showCopyVal->nCountry;

		$this->ddlCity   		  	 = $showCopyVal->nCity;

		$this->ddlHotel 	  	  	 = $showCopyVal->vHotelName;

		$this->txtRoomType 	  	  	 = $showCopyVal->vRoomType;

		$this->txtNoOfUnit   	  	 = $showCopyVal->vNoOfUnit;

		$this->ddlServices 	      	 = $showCopyVal->vServices;

		$this->ddlTransfer 	      	 = $showCopyVal->nTransfer;

		$this->txtExtraServ 	  	 = $showCopyVal->vExtraService;

		$this->txtTktName		  	 = $showCopyVal->vTicktManual;



		$this->txtCheckIn 	      	 = $showCopyVal->dCheckIn;

		$this->txtCheckOut 	      	 = $showCopyVal->dCheckOut;

		$this->txtNights 	      	 = $showCopyVal->vNight;

		$this->txtChild 	  	  	 = $showCopyVal->vChild;

		$this->txtAdult			  	 = $showCopyVal->vAdult;

		$this->txtRemarks   	  	 = $showCopyVal->vRemarks;

		$this->ddlAcCode 	      	 = $showCopyVal->nAcCode; 

		$this->ddlCurrency 	      	 = $showCopyVal->nCurrency;

		$this->txtRate 	      	  	 = $showCopyVal->nRate;

		$this->txtRateNetFcTotal   	 = $showCopyVal->vTotalRTFC;

		$this->txtTotalFc   	  	 = $showCopyVal->vTotalFC;

		$this->txtRateNetDcTotal  	 = $showCopyVal->vTotalRTDC;

		$this->txtTotalDc 	      	 = $showCopyVal->vTotalDC;

		$this->txtAgentConfId 	  	 = $showCopyVal->vAgentConfId;

		$this->txtHotelConfId     	 = $showCopyVal->vHotelConfId;



		$data['list_country_value']	 = $this->hotel_voucher_model->getActiveCountry();

		$data['list_hotel_value'] 	 = $this->hotel_voucher_model->getActiveHotels();

		$data['list_services_value'] = $this->hotel_voucher_model->getActiveServices();

		$data['list_transfer_value'] = $this->hotel_voucher_model->getActiveTransfer();

		

		$data['list_city_value']     = $this->hotel_voucher_model->getCorrActiveCities($this->ddlCountry);

		$data['list_loc_value']      = $this->hotel_voucher_model->getCorrActiveLocation($this->ddlDivision);



		$data['close_status']        = $this->hotel_voucher_model->getCloseStatus();

		$data['invoice_number']      = $this->hotel_voucher_model->getInvoiceNumber();
		$data['invoice_id']      = $this->hotel_voucher_model->getInvoiceid();

		$data['refund_status']       = $this->hotel_voucher_model->getRefundStatus();

		$data['refund_number']       = $this->hotel_voucher_model->getRefundInvoiceNumber();



		//get selected hotel details

		$hotelValArray            	 = $this->hotel_voucher_model->getCorresCopyHotelDetails($this->htlId);

		$data['txtPayRoomType']   	 = $hotelValArray['roomType'];

		$data['txtChkinDate']     	 = $hotelValArray['chkIndate'];

		$data['txtChkoutDate']    	 = $hotelValArray['chkOutdate'];

		$data['txtNight']         	 = $hotelValArray['night'];

		$data['txtNoofRooms']     	 = $hotelValArray['noofRooms'];

		$data['txtRateNetFc']     	 = $hotelValArray['rateNetFc'];

		$data['txtTotalinFc']     	 = $hotelValArray['totalFc'];

		$data['txtRateNetDc']     	 = $hotelValArray['rateNetDc'];

		$data['txtTotalinDc']     	 = $hotelValArray['totalDc'];

		$data['txtExchangeRate']  	 = $hotelValArray['exchange'];

		$data['chkHotelID']       	 = $hotelValArray['hotelId'];



		//get selected hotel billing details

		$this->ddlMode       	   	 = $showCopyVal->vModeofpay;

		$this->ddlCustomerAc       	 = $showCopyVal->nCustomerAc;

		$this->txtSubaccName       	 = $showCopyVal->vSubAcname;

		$this->ddlAgentAc          	 = $showCopyVal->nAgentAc;

		$this->txtAgentAmt         	 = $showCopyVal->vAgentAmt;

		$this->txtLPO                = $showCopyVal->vLPO;

		$this->txtSalesAmt         	 = $showCopyVal->vSalesAmt;

		$this->txtDiscount         	 = $showCopyVal->vDiscount;

		$this->txtDiscountAmt      	 = $showCopyVal->vDiscountAmt;

		$this->txtCollected        	 = $showCopyVal->vCollected;

		$this->txtServicePerc      	 = $showCopyVal->vServicePercent;

		$this->txtServiceAmt       	 = $showCopyVal->vServiceAmt;

		$this->chkHotelBillingID   	 = $showCopyVal->nHBId;



		//get selected hotel transfer details

		$this->txtArrDate          	 = $showCopyVal->dDate1;

		$this->txtArrFrom          	 = $showCopyVal->vArrivalFrom;

		$this->txtArrFlight	       	 = $showCopyVal->vFlightId1;

		$this->txtArrTime          	 = $showCopyVal->vArrivalTime;

		$this->txtDepDate          	 = $showCopyVal->dDate2;

		$this->txtDepTo            	 = $showCopyVal->vDepTo;

		$this->txtDepFlight        	 = $showCopyVal->vFlightId2;

		$this->txtDepTime          	 = $showCopyVal->vDepTime;

		$this->txtNoofGuest        	 = $showCopyVal->nNoofGuest;

		$this->txtVehicle          	 = $showCopyVal->vVehicle;

		$this->txtDriverCont       	 = $showCopyVal->vDriverContact;

		$this->txtEmrContact       	 = $showCopyVal->vEmergencyContact;

		$this->txtPickup           	 = $showCopyVal->vPickup;

		$this->txtPickupDate       	 = $showCopyVal->dPickupDate;

		$this->txtDrop             	 = $showCopyVal->vDrop;

		$this->txtRepHtl           	 = $showCopyVal->vRepHotel;

		$this->txtCityTour         	 = $showCopyVal->vCityTour;

		$this->txtDuration         	 = $showCopyVal->vDurTime;

		$this->txtTransferRemarks  	 = $showCopyVal->vRemarks;

		$this->chkHotelTransferID  	 = $showCopyVal->nHTId;

		

		$this->ddlDivision	  	   	 = $showCopyVal->nDivision;

		$this->ddlLocation	       	 = $showCopyVal->nLocation;

		$this->txtDate  	  	   	 = $showCopyVal->dDate;

		$this->ddlTrans  	  	   	 = $showCopyVal->nTransaction;

		$this->txtDocNumber        	 = $showCopyVal->vDocNumber;

		$this->ddlTktname 	         = $showCopyVal->vTicketNumber;

		$this->txtTktName   	   	 = $showCopyVal->vTicktManual;

		$this->ddlType	     	   	 = $showCopyVal->vType;

		$this->ddlBooked     	   	 = $showCopyVal->vBookedBy;

		$this->ddlIssued       	   	 = $showCopyVal->vIssuedBy;

		$this->txtPassenger		   	 = $showCopyVal->vPassenger;

		$this->txtOriginalTkt	   	 = $showCopyVal->vOriginalTkt;

		$this->txtGdsPNR		   	 = $showCopyVal->vGdspnr;

		$this->txtAirPNR		   	 = $showCopyVal->vAirpnr;

		$this->ddlCurrency		   	 = $showCopyVal->nCurrency;

		$this->txtRate   		   	 = $showCopyVal->nRate;

		$data['list_loc_value']    	 = $this->hotel_voucher_model->getCorrActiveLocation($this->ddlDivision);

		

		$this->load->view('hotel_voucher/show_copyhotel',$data);

	}//end function



    function printPreview()

    {

      session_start();

        $this->load->helper(array('form', 'url'));



        //$this->catId   = $this->input->post('id');

		$this->catId   = $this->uri->segment(3);



        $showVal	          	  = $this->hotel_voucher_model->getCorresHotelvoucher();

        $this->ddlDivision	  	  = $showVal->nDivision;

        $this->ddlLocation		  = $showVal->nLocation;

        $this->txtDate  	  	  = $showVal->dDate;

        $this->txtBookingId  	  = $showVal->vBookingId;

        $this->ddlTrans  	  	  = $showVal->nTransaction;

        $this->txtDocNumber       = $showVal->vDocNumber;

        $this->ddlType  	  	  = $showVal->vType;

        $this->ddlTktname   	  = $showVal->vVoucherId;

        $this->txtFileId  	      = $showVal->vFileId;

        $this->ddlBooked     	  = $showVal->vBookedBy;

        $this->ddlIssued       	  = $showVal->vIssuedBy;

        $this->txtPassenger		  = $showVal->vPassenger;

        $this->ddlCountry	  	  = $showVal->nCountry;

        $this->ddlCity   		  = $showVal->nCity;

        $this->ddlHotel 	  	  = $showVal->vHotelName;

        $this->txtRoomType 	  	  = $showVal->vRoomType;

        $this->txtNoOfUnit   	  = $showVal->vNoOfUnit;

        $this->ddlServices 	      = $showVal->vServices;

        $this->ddlTransfer 	      = $showVal->nTransfer;

       $showV = $this->hotel_voucher_model->getcur($showVal->vExtraService);
		$this->txtExtraServ = $showV->vName;

        $this->txtTktName		  = $showVal->vTicktManual;



        $this->txtCheckIn 	      = $showVal->dCheckIn;

        $this->txtCheckOut 	      = $showVal->dCheckOut;

        $this->txtNights 	      = $showVal->vNight;

        $this->txtChild 	  	  = $showVal->vChild;

        $this->txtAdult			  = $showVal->vAdult;

        $this->txtRemarks   	  = $showVal->vRemarks;

        $this->ddlAcCode 	      = $showVal->nAcCode;

        $this->ddlAccHead 	      = $showVal->ddlAccHead;

        $this->ddlCurrency 	      = $showVal->nCurrency;

        $this->txtRate 	      	  = $showVal->nRate;

        $this->txtRateNetFcTotal  = $showVal->vTotalRTFC;

        $this->txtTotalFc   	  = $showVal->vTotalFC;

        $this->txtRateNetDcTotal  = $showVal->vTotalRTDC;

        $this->txtTotalDc 	      = $showVal->vTotalDC;

        $this->txtAgentConfId 	  = $showVal->vAgentConfId;

        $this->txtHotelConfId     = $showVal->vHotelConfId;



        $data['list_acchead_value'] = $this->hotel_voucher_model->getActiveAccHead($this->ddlAcCode);

        $data['list_city_value']  = $this->hotel_voucher_model->getCorrActiveCities($this->ddlCountry);

        $data['list_loc_value']   = $this->hotel_voucher_model->getCorrActiveLocation($this->ddlDivision);

        $data['list_hotel_value'] = $this->hotel_voucher_model->getHotelValues($this->ddlCountry,$this->ddlCity);

        $data['close_status']     = $this->hotel_voucher_model->getCloseStatus();

        $data['invoice_number']   = $this->hotel_voucher_model->getInvoiceNumber();

        $data['refund_status']    = $this->hotel_voucher_model->getRefundStatus();

        $data['refund_number']    = $this->hotel_voucher_model->getRefundInvoiceNumber();



        //get selected hotel details

        $hotelValArray            = $this->hotel_voucher_model->getCorresHotelDetails();

        $data['txtPayRoomType']   = $hotelValArray['roomType'];

        $data['txtChkinDate']     = $hotelValArray['chkIndate'];

        $data['txtChkoutDate']    = $hotelValArray['chkOutdate'];

        $data['txtNight']         = $hotelValArray['night'];

        $data['txtNoofRooms']     = $hotelValArray['noofRooms'];

        $data['txtRateNetFc']     = $hotelValArray['rateNetFc'];

        $data['txtTotalinFc']     = $hotelValArray['totalFc'];

        $data['txtRateNetDc']     = $hotelValArray['rateNetDc'];

        $data['txtTotalinDc']     = $hotelValArray['totalDc'];

        $data['txtExchangeRate']  = $hotelValArray['exchange'];

        $data['chkHotelID']       = $hotelValArray['hotelId'];



        //get selected hotel billing details

        $showBillingVal			   = $this->hotel_voucher_model->getCorresBillingDetails();

        $this->ddlMode       	   = $showBillingVal->vModeofpay;

        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;

        $this->txtSubaccName       = $showBillingVal->vSubAcname;

        $this->ddlAgentAc          = $showBillingVal->nAgentAc;

        $this->txtAgentAmt         = $showBillingVal->vAgentAmt;

        $this->txtLPO              = $showBillingVal->vLPO;

        $this->txtSalesAmt         = $showBillingVal->vSalesAmt;

        $this->txtDiscount         = $showBillingVal->vDiscount;

        $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;

        $this->txtCollected        = $showBillingVal->vCollected;

        $this->txtServicePerc      = $showBillingVal->vServicePercent;

        $this->txtServiceAmt       = $showBillingVal->vServiceAmt;

        $this->chkHotelBillingID   = $showBillingVal->nHBId;



        //get selected hotel transfer details

        $showTransferVal		   = $this->hotel_voucher_model->getCorresTransferDetails();

        $this->txtArrDate          = $showTransferVal->dDate1;

        $this->txtArrFrom          = $showTransferVal->vArrivalFrom;

        $this->txtArrFlight	       = $showTransferVal->vFlightId1;

        $this->txtArrTime          = $showTransferVal->vArrivalTime;

        $this->txtDepDate          = $showTransferVal->dDate2;

        $this->txtDepTo            = $showTransferVal->vDepTo;

        $this->txtDepFlight        = $showTransferVal->vFlightId2;

        $this->txtDepTime          = $showTransferVal->vDepTime;

        $this->txtNoofGuest        = $showTransferVal->nNoofGuest;

        $this->txtVehicle          = $showTransferVal->vVehicle;

        $this->txtDriverCont       = $showTransferVal->vDriverContact;

        $this->txtEmrContact       = $showTransferVal->vEmergencyContact;

        $this->txtPickup           = $showTransferVal->vPickup;

        $this->txtPickupDate       = $showTransferVal->dPickupDate;

        $this->txtDrop             = $showTransferVal->vDrop;

        $this->txtRepHtl           = $showTransferVal->vRepHotel;

        $this->txtCityTour         = $showTransferVal->vCityTour;

        $this->txtDuration         = $showTransferVal->vDurTime;

        $this->txtTransferRemarks  = $showTransferVal->vRemarks;

        $this->chkHotelTransferID  = $showTransferVal->nHTId;



        $mydata = $this->load->view('hotel_voucher/export_files/print_hotel_voucher',$data,true);

        $_SESSION['print_hotel_voucher'] = $mydata;

        $_SESSION['print_hotel_voucher_pgSz'] = 'A4';

        $_SESSION['print_hotel_voucher_lp'] = 'P';



        $this->load->view('general/title');

        $this->load->view('hotel_voucher/print_hotel_voucher',$data);

    }//end function



    function printAgentPreview()

    {

      session_start();

        $this->load->helper(array('form', 'url'));



       // $this->catId   = $this->input->post('id');

		$this->catId   = $this->uri->segment(3);



        $showVal	          	  = $this->hotel_voucher_model->getCorresHotelvoucher();

        $this->ddlDivision	  	  = $showVal->nDivision;

        $this->ddlLocation		  = $showVal->nLocation;

        $this->txtDate  	  	  = $showVal->dDate;

        $this->txtBookingId  	  = $showVal->vBookingId;

        $this->ddlTrans  	  	  = $showVal->nTransaction;

        $this->txtDocNumber       = $showVal->vDocNumber;

        $this->ddlType  	  	  = $showVal->vType;

        $this->ddlTktname   	  = $showVal->vVoucherId;

        $this->txtFileId  	      = $showVal->vFileId;
		 $this->vHotelConfId  	      = $showVal->vHotelConfId;
		

        $this->ddlBooked     	  = $showVal->vBookedBy;

        $this->ddlIssued       	  = $showVal->vIssuedBy;

        $this->txtPassenger		  = $showVal->vPassenger;

        $this->ddlCountry	  	  = $showVal->nCountry;

        $this->ddlCity   		  = $showVal->nCity;

        $this->ddlHotel 	  	  = $showVal->vHotelName;

        $this->txtRoomType 	  	  = $showVal->vRoomType;

        $this->txtNoOfUnit   	  = $showVal->vNoOfUnit;

        $this->ddlServices 	      = $showVal->vServices;

        $this->ddlTransfer 	      = $showVal->nTransfer;

        	 
		$showV = $this->hotel_voucher_model->getcur($showVal->vExtraService);
		$this->txtExtraServ = $showV->vName;
		//$this->db->query("SELECT nId,vTitle,vCurrcode FROM {PRE}Currency WHERE nId='".$showVal->vExtraService."' AND vActive = '1' ");


        $this->txtTktName		  = $showVal->vTicktManual;



        $this->txtCheckIn 	      = $showVal->dCheckIn;

        $this->txtCheckOut 	      = $showVal->dCheckOut;

        $this->txtNights 	      = $showVal->vNight;

        $this->txtChild 	  	  = $showVal->vChild;

        $this->txtAdult			  = $showVal->vAdult;

        $this->txtRemarks   	  = $showVal->vRemarks;

        $this->ddlAcCode 	      = $showVal->nAcCode;

        $this->ddlAccHead 	      = $showVal->ddlAccHead;

        $this->ddlCurrency 	      = $showVal->nCurrency;

        $this->txtRate 	      	  = $showVal->nRate;

        $this->txtRateNetFcTotal  = $showVal->vTotalRTFC;

        $this->txtTotalFc   	  = $showVal->vTotalFC;

        $this->txtRateNetDcTotal  = $showVal->vTotalRTDC;

        $this->txtTotalDc 	      = $showVal->vTotalDC;

        $this->txtAgentConfId 	  = $showVal->vAgentConfId;

        $this->txtHotelConfId     = $showVal->vHotelConfId;



        $data['list_acchead_value'] = $this->hotel_voucher_model->getActiveAccHead($this->ddlAcCode);

        $data['list_city_value']  = $this->hotel_voucher_model->getCorrActiveCities($this->ddlCountry);

        $data['list_loc_value']   = $this->hotel_voucher_model->getCorrActiveLocation($this->ddlDivision);

        $data['list_hotel_value'] = $this->hotel_voucher_model->getHotelValues($this->ddlCountry,$this->ddlCity);

        $data['close_status']     = $this->hotel_voucher_model->getCloseStatus();

        $data['invoice_number']   = $this->hotel_voucher_model->getInvoiceNumber();

        $data['refund_status']    = $this->hotel_voucher_model->getRefundStatus();

        $data['refund_number']    = $this->hotel_voucher_model->getRefundInvoiceNumber();



        //get selected hotel details

        $hotelValArray            = $this->hotel_voucher_model->getCorresHotelDetails();

        $data['txtPayRoomType']   = $hotelValArray['roomType'];

        $data['txtChkinDate']     = $hotelValArray['chkIndate'];

        $data['txtChkoutDate']    = $hotelValArray['chkOutdate'];

        $data['txtNight']         = $hotelValArray['night'];

        $data['txtNoofRooms']     = $hotelValArray['noofRooms'];

        $data['txtRateNetFc']     = $hotelValArray['rateNetFc'];

        $data['txtTotalinFc']     = $hotelValArray['totalFc'];

        $data['txtRateNetDc']     = $hotelValArray['rateNetDc'];

        $data['txtTotalinDc']     = $hotelValArray['totalDc'];

        $data['txtExchangeRate']  = $hotelValArray['exchange'];

        $data['chkHotelID']       = $hotelValArray['hotelId'];



        //get selected hotel billing details

        $showBillingVal			   = $this->hotel_voucher_model->getCorresBillingDetails();

        $this->ddlMode       	   = $showBillingVal->vModeofpay;

        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;

        $this->txtSubaccName       = $showBillingVal->vSubAcname;

        $this->ddlAgentAc          = $showBillingVal->nAgentAc;

        $this->txtAgentAmt         = $showBillingVal->vAgentAmt;

        $this->txtLPO              = $showBillingVal->vLPO;

        $this->txtSalesAmt         = $showBillingVal->vSalesAmt;

        $this->txtDiscount         = $showBillingVal->vDiscount;

        $this->txtDiscountAmt      = $showBillingVal->vDiscountAmt;

        $this->txtCollected        = $showBillingVal->vCollected;

        $this->txtServicePerc      = $showBillingVal->vServicePercent;

        $this->txtServiceAmt       = $showBillingVal->vServiceAmt;

        $this->chkHotelBillingID   = $showBillingVal->nHBId;



        //get selected hotel transfer details

        $showTransferVal		   = $this->hotel_voucher_model->getCorresTransferDetails();

        $this->txtArrDate          =  date('d-m-Y', strtotime($showTransferVal->dDate1));

        $this->txtArrFrom          = $showTransferVal->vArrivalFrom;

        $this->txtArrFlight	       = $showTransferVal->vFlightId1;

        $this->txtArrTime          = $showTransferVal->vArrivalTime;

        $this->txtDepDate          =  date('d-m-Y', strtotime($showTransferVal->dDate2));

        $this->txtDepTo            = $showTransferVal->vDepTo;

        $this->txtDepFlight        = $showTransferVal->vFlightId2;

        $this->txtDepTime          = $showTransferVal->vDepTime;

        $this->txtNoofGuest        = $showTransferVal->nNoofGuest;

        $this->txtVehicle          = $showTransferVal->vVehicle;

        $this->txtDriverCont       = $showTransferVal->vDriverContact;

        $this->txtEmrContact       = $showTransferVal->vEmergencyContact;

        $this->txtPickup           = $showTransferVal->vPickup;

        $this->txtPickupDate       = $showTransferVal->dPickupDate;

        $this->txtDrop             = $showTransferVal->vDrop;

        $this->txtRepHtl           = $showTransferVal->vRepHotel;

        $this->txtCityTour         = $showTransferVal->vCityTour;

        $this->txtDuration         = $showTransferVal->vDurTime;

        $this->txtTransferRemarks  = $showTransferVal->vRemarks;

        $this->chkHotelTransferID  = $showTransferVal->nHTId;



        $mydata = $this->load->view('hotel_voucher/export_files/print_agent_copy',$data,true);

        $_SESSION['print_HV_agent_copy'] = $mydata;

        $_SESSION['print_HV_agent_copy_pgSz'] = 'A4';

        $_SESSION['print_HV_agent_copy_lp'] = 'P';



        $this->load->view('general/title');

        $this->load->view('hotel_voucher/print_agent_copy',$data);

    }//end function



    function filterHotelVoucher()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = HOTELVOUCHERMANAGEMENT;



        $this->common_model->check_admin_authorization();

        $this->txtVoucher  = $this->common_model->searchKeyword('txtVoucher');

        $this->txtVendor   = $this->common_model->searchKeyword('txtVendor');

        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');

        $this->txtCity = $this->common_model->searchKeyword('txtCity');

        $this->txtConf     = $this->common_model->searchKeyword('txtConf');

        $this->txtFromDate     = $this->common_model->searchKeyword('txtFromDate');

        $this->txtToDate     = $this->common_model->searchKeyword('txtToDate');

        $this->txtLocation = $this->common_model->searchKeyword('txtLocation');

        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');



        if($this->input->post('btnSearch') == 'Go')

        {

            $this->session->unset_userdata('txtVoucher');

            $this->session->unset_userdata('txtVendor');

            $this->session->unset_userdata('txtPassName');

            $this->session->unset_userdata('txtConf');

            $this->session->unset_userdata('txtCity');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtLocation');

            $this->session->unset_userdata('txtIssuedBy');



            $this->txtVoucher  = $this->common_model->searchKeyword('txtVoucher');

            $this->txtVendor   = $this->common_model->searchKeyword('txtVendor');

            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');

            $this->txtConf     = $this->common_model->searchKeyword('txtConf');

            $this->txtCity     = $this->common_model->searchKeyword('txtCity');

            $this->txtDate     = $this->common_model->searchKeyword('txtFromDate');

            $this->txtDate     = $this->common_model->searchKeyword('txtToDate');

            $this->txtLocation = $this->common_model->searchKeyword('txtLocation');

            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');

        }//end if



        if(!empty($this->txtVoucher))

        {

            $extraCheck  	= " AND (hv.vTicktManual LIKE '".$this->txtVoucher." %' OR hv.vTicktManual LIKE '% ".$this->txtVoucher."'

									OR hv.vTicktManual LIKE '%".$this->txtVoucher."%') ";

        }//end if



        if(!empty($this->txtVendor))

        {

                $extraCheck 	.= " AND vm.nVId =".$this->txtVendor." ";

        }//end if



        if(!empty($this->txtPassName))

        {

                $extraCheck  .= " AND (hv.vPassenger LIKE '".$this->txtPassName." %' OR hv.vPassenger LIKE '% ".$this->txtPassName."'

									OR hv.vPassenger LIKE '%".$this->txtPassName."%') ";

        }//end if



        if(!empty($this->txtConf))

        {

                $extraCheck  .= " AND (hv.vHotelConfId LIKE '".$this->txtConf." %' OR hv.vHotelConfId LIKE '% ".$this->txtConf."'

									OR hv.vHotelConfId LIKE '%".$this->txtConf."%') ";

        }//end if



        if(!empty($this->txtCity))

        {

		$extraCheck     .= " AND hv.nCity = ".$this->txtCity." "; 

        }//end if





	if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))

		{

			//convet date to mysql format

			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');

			$txtTo   	= $this->common_model->searchKeyword('txtToDate');

		

			$extraCheck .= " AND hv.dDate >= '".$txtFrom."' AND hv.dDate <= '".$txtTo."'";

		}//end if





        if(!empty($this->txtLocation))

        {

		$extraCheck     .= " AND hv.nLocation = ".$this->txtLocation." "; 

        }//end if



        if(!empty($this->txtIssuedBy))

        {

		$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";

        }//end if





        $countFilterList	=  $this->hotel_voucher_model->getCountFilterList($extraCheck);

        $target 			= '.';

        $this->common_model->_paging($countFilterList, $target,'hotel_voucher/filterHotelVoucher2/'.$this->uri->segment(3));



        $data['list_value'] = $this->hotel_voucher_model->getFilterList($extraCheck);

        $this->count_rows   = count($data['list_value']);



	$data['list_emp_value']     = $this->hotel_voucher_model->getActiveEmployee(); 

	$data['list_city_value']     = $this->hotel_voucher_model->getActiveCity(); 

	$data['list_vendor_value']     = $this->hotel_voucher_model->getAccounts(); 

	$data['list_loc_value']     = $this->hotel_voucher_model->getActiveLocations(); 





// User access aa

    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel_voucher/showlist');



        $this->load->view('hotel_voucher/hotel_voucher_filter',$data);

    }//end function



    function filterHotelVoucher2()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = HOTELVOUCHERMANAGEMENT;



        $this->common_model->check_admin_authorization();

        $this->txtVoucher  = $this->common_model->searchKeyword('txtVoucher');

        $this->txtVendor   = $this->common_model->searchKeyword('txtVendor');

        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');

        $this->txtConf     = $this->common_model->searchKeyword('txtConf');

        $this->txtCity = $this->common_model->searchKeyword('txtCity');

        $this->txtFromDate     = $this->common_model->searchKeyword('txtFromDate');

        $this->txtToDate     = $this->common_model->searchKeyword('txtToDate');

        $this->txtLocation = $this->common_model->searchKeyword('txtLocation');

        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');



        if($this->input->post('btnSearch') == 'Go')

        {

            $this->session->unset_userdata('txtVoucher');

            $this->session->unset_userdata('txtVendor');

            $this->session->unset_userdata('txtPassName');

            $this->session->unset_userdata('txtConf');

            $this->session->unset_userdata('txtCity');

            $this->session->unset_userdata('txtFromDate');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtLocation');

            $this->session->unset_userdata('txtIssuedBy');



            $this->txtVoucher  = $this->common_model->searchKeyword('txtVoucher');

            $this->txtVendor   = $this->common_model->searchKeyword('txtVendor');

            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');

            $this->txtConf     = $this->common_model->searchKeyword('txtConf');

            $this->txtCity     = $this->common_model->searchKeyword('txtCity');

            $this->txtDate     = $this->common_model->searchKeyword('txtFromDate');

            $this->txtDate     = $this->common_model->searchKeyword('txtToDate');

            $this->txtLocation = $this->common_model->searchKeyword('txtLocation');

            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');

        }//end if







        if(!empty($this->txtVoucher))

        {

            $extraCheck  	= " AND (hv.vTicktManual LIKE '".$this->txtVoucher." %' OR hv.vTicktManual LIKE '% ".$this->txtVoucher."'

									OR hv.vTicktManual LIKE '%".$this->txtVoucher."%') ";

        }//end if



        if(!empty($this->txtVendor))

        {

                $extraCheck 	.= " AND vm.nVId =".$this->txtVendor." ";

        }//end if



        if(!empty($this->txtPassName))

        {

                $extraCheck  .= " AND (hv.vPassenger LIKE '".$this->txtPassName." %' OR hv.vPassenger LIKE '% ".$this->txtPassName."'

									OR hv.vPassenger LIKE '%".$this->txtPassName."%') ";

        }//end if



        if(!empty($this->txtConf))

        {

                $extraCheck  .= " AND (hv.vHotelConfId LIKE '".$this->txtConf." %' OR hv.vHotelConfId LIKE '% ".$this->txtConf."'

									OR hv.vHotelConfId LIKE '%".$this->txtConf."%') ";

        }//end if



        if(!empty($this->txtCity))

        {

		$extraCheck     .= " AND hv.nCity = ".$this->txtCity." "; 

        }//end if



	if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))

		{

			//convet date to mysql format

			$txtFrom 	= $this->common_model->searchKeyword('txtFromDate');

			$txtTo   	= $this->common_model->searchKeyword('txtToDate');

		

			$extraCheck .= " AND hv.dDate >= '".$txtFrom."' AND hv.dDate <= '".$txtTo."'";

		}//end if





        if(!empty($this->txtLocation))

        {

		$extraCheck     .= " AND hv.nLocation = ".$this->txtLocation." "; 

        }//end if



        if(!empty($this->txtIssuedBy))

        {

		$extraCheck     .= " AND em.nEId =".$this->txtIssuedBy." ";

        }//end if





        $countFilterList	= $this->hotel_voucher_model->getCountFilterList($extraCheck);

        $target 			= '.';

        $this->common_model->_paging($countFilterList, $target,'hotel_voucher/filterHotelVoucher2/'.$this->uri->segment(3));



        $data['list_value'] = $this->hotel_voucher_model->getFilterList($extraCheck);

        $this->count_rows   = count($data['list_value']);

	$data['list_emp_value']     = $this->hotel_voucher_model->getActiveEmployee(); 

	$data['list_city_value']     = $this->hotel_voucher_model->getActiveCity(); 

	$data['list_vendor_value']     = $this->hotel_voucher_model->getAccounts(); 

	$data['list_loc_value']     = $this->hotel_voucher_model->getActiveLocations(); 







// User access aa

    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'hotel_voucher/showlist');



        $this->load->view('general/template_top',$data);

        $this->load->view('hotel_voucher/hotel_voucher');

        $this->load->view('general/template_bottom');

    }//end function

    



	

    function editHotel()

{

	$this->common_model->check_admin_authorization();

//	$this->load->helper(array('form', 'url'));



//	$this->showTitle      = HOTELMANAGEMENT;

//	$data['title'] 	      = $this->common_model->get_site_title(); 

	//$data['cityList']     = array();

	//active countries

/*	$data['list_country_value'] = $this->hotel_model->getDropCountry();

	//active service type

	$data['list_services_value'] = $this->hotel_model->getDropService();



	$this->load->helper(array('form', 'url'));

	$this->load->library('form_validation');

	$this->form_validation->set_rules('txtTitle2', 'Hotel Name', 'required');





//	$this->showTitle    = ADDNEWHOTEL;

//	$this->btnVal	    = ADDCONTINUE;

	$this->btnVal2	    = SAVEEXIT;

	$this->txtTitle2	    = $this->input->post('txtTitle2');

	$this->txtPhone2 	= $this->input->post('txtPhone2');

	$this->txtFax2 	    = $this->input->post('txtFax2');

//	$this->chkValYes2    = 'checked="checked"';

//	$this->chkValNo2	    = '';

	$this->catId	    = '';

	$this->ddlCountry2   = $this->input->post('ddlCountry2');

	$this->ddlCity2      = $this->input->post('ddlCity2');

	$this->ddlService2   = $this->input->post('ddlService2');

//	$data['cityList']	= $this->hotel_model->getDropCity();





	if ($this->form_validation->run() == FALSE)

	{

		$this->load->view('hotel_voucher/add_hotel');

	}//end if

	else

	{

/*		switch(intval($this->input->post('nId')))

		{

			case "0":

				$this->extraCond  = "";

			break;



			default:

			$this->extraCond  = " AND nHId!='".$this->input->post('nId')."'";

			break;

		}//end switch

*/

		//check name already exits or not

//("SELECT vName FROM {PRE}Hotel WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle2'))."' and nCity = ".mysql_real_escape_string($this->input->post('ddlCity2')));



		$chkTitle = $this->db->query("SELECT vName FROM {PRE}Hotel WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle2'))."'  and nCity = ".mysql_real_escape_string($this->input->post('ddlCity2')));

		if ($chkTitle->num_rows() > 0)

		{

			$data['error_value'] = HOTELNAMEEXIST;

//			$this->load->view('hotel_voucher/add_hotel');

		}//end if

		else

		{

			//insert into db

			$this->hotel_voucher_model->insertHotel();



		}//end else 

//	}//end else	

}//end function



    function getIssuedBy()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_emp_value'] = $this->common_model->getIssuedBy();

      $this->load->view('hotel_voucher/show_issuedBy',$data);

    }



    function getBookedBy()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_emp_value'] = $this->common_model->getIssuedBy();

      $this->load->view('hotel_voucher/show_bookedBy',$data);

    }



    function getVendorAccount()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_accode_value'] = $this->common_model->getVendorMasterAccounts();

      $this->load->view('hotel_voucher/show_vendorAccounts',$data);



    }



    function getCustAccount()

    {

      $this->load->helper('url');

      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();

      $data['list_custacc_value'] = $this->common_model->getCustomerMasterAccounts();

      $this->load->view('hotel_voucher/show_customerAccounts',$data);



    }









  function getEmailDetails()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();

    $data['ddlLocation'] = $this->input->post('ddlLocation');

    $this->load->view('hotel_voucher/show_emailDetails',$data);



  }



}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

