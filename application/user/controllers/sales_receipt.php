<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_receipt extends CI_Controller {



  function __construct()

  {

    parent::__construct();

    $this->load->model('sales_receipt_model');

  }



  function showlist()

  {

    $this->load->helper(array('form', 'url'));

    $this->showTitle    = SALESRECEIPTMANAGEMENT;

    $this->common_model->check_admin_authorization();





        $this->session->unset_userdata('backURL');



    $countSalesReceipt =  $this->sales_receipt_model->getSalesReceipt(1);

    $target         = '.';

    $this->common_model->_paging($countSalesReceipt, $target,'sales_receipt/showlist/'.$this->uri->segment(3));

    $data['list_value']   = $this->sales_receipt_model->getSalesReceipt(0);

    $this->count_rows     = count($data['list_value']);



///User access aa

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_receipt/showlist');



    $this->load->view('general/template_top',$data);

    $this->load->view('sales_receipt/sales_receipt');

    $this->load->view('general/template_bottom');

  }//end function



  //search by division or and doc number

  function filterList()

  {

    $this->load->helper(array('form', 'url'));

    $this->showTitle = SALESRECEIPTMANAGEMENT;



    $this->common_model->check_admin_authorization();

    $this->txtSearch = $this->common_model->searchKeyword('txtSearch');



    if($this->input->post('btnSearch') == 'Go')

    {

      $this->session->unset_userdata('txtSearch');

      $this->txtSearch = $this->common_model->searchKeyword('txtSearch');

    }//end if



    if(!empty($this->txtSearch))

    {

      $extraCheck   = " AND (sr.vDocNumber LIKE '".$this->txtSearch." %' OR sr.vDocNumber LIKE '% ".$this->txtSearch."'

                  OR sr.vDocNumber LIKE '%".$this->txtSearch."%'

                  OR (SELECT count(*) FROM {PRE}Division d WHERE d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'

                OR d.vTitle LIKE '%".$this->txtSearch."%')>0) ";

    }//end if



    $countFilterList =  $this->sales_receipt_model->getSalesReceipt(1,$extraCheck);



    $target       = '.';

    $this->common_model->_paging($countFilterList, $target,'sales_receipt/filterList/'.$this->uri->segment(3));



    $data['list_value']   = $this->sales_receipt_model->getSalesReceipt(0,$extraCheck);



    $this->count_rows   = count($data['list_value']);



//User access aa

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_receipt/showlist');



    $this->load->view('sales_receipt/sales_receipt_filter',$data);

  }//end function



	

	function editSalesreceipt()

	{



		$this->common_model->check_admin_authorization();

		$data['title'] 	   		  = $this->common_model->get_site_title(); 

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');



		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');

    $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';



//User Access aa

    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sales_receipt/showlist');

    $data['action_access'] = $action_access;

    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){

      if(trim($this->session->userdata('backURL')) != '')

        redirect(site_url($this->session->userdata('backURL')));

      else

        redirect(site_url("sales_receipt/showlist/".$this->uri->segment(3).'/no_access'));

    }



		$data['list_div_value']   		 = $this->sales_receipt_model->getActiveDivision();

		$data['list_creditcard']	  	  	 = $this->sales_receipt_model->getActiveCreditCard();

		$data['list_tkt']	  	  	 = $this->sales_receipt_model->getActiveTickets();

		$data['list_loc_value']  		 = $this->sales_receipt_model->getLocation();

		

		switch($id)

		{

			case "a":

        $this->showTitle   		  = ADDNEWSALESRECEIPT;

      $this->btnVal	   		  = ADDCONTINUE;

      $this->btnVal2	   		  = SAVEEXIT;

      $this->ddlDivision	  	  = $this->input->post('ddlDivision');

      $this->ddlLocation	  	  = $this->input->post('ddlLocation');

      $this->txtDate1      	    = date('d-m-Y');

      $this->txtDocNumber  	  = '';// $this->getDocumentNumber();

      $this->txtRefName         = $this->input->post('txtRefName');

      $this->txtEntered         = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->session->userdata('AbsAdmin')."' ");

      $this->txtRemarks         = $this->input->post('txtRemarks');

      $this->txtTotAmt         = $this->input->post('txtTotAmt');

      $this->chkValYes   		  = 'checked="checked"';

      $this->chkValNo	   		  = '';

      $this->catId	   		  = '';



      $data['chkAmt']  	  = array();

      $data['chkCard'] 	  = array();

      $data['chkTkt'] 		  = array();

      $data['chkTktType'] 		  = array();



      break;



      case "e":

        $this->showTitle      	  = EDITSALESRECEIPT;

      $this->btnVal	      	  = UPDATESALESRECEIPT;

      $this->catId	      	  = $this->uri->segment(5);

      if(intval($this->input->post('nId')) != 0)

      {

        $this->catId = intval($this->input->post('nId'));

      }//end if



      $showVal	          	  	  = $this->sales_receipt_model->getCorresSalesReceipt();

      $this->ddlDivision	  	  	  = $showVal->nDivision;

      $this->ddlLocation	  	  	  = $showVal->vLocation;

      $this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));

      $this->txtDocNumber  	  	  = $showVal->vDocNumber;

      $this->txtRefName         	  = $showVal->vRefName;

      $this->txtEntered         	  = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$showVal->vEnteredBy."' ");

      $this->txtTotAmt              = $showVal->vAmountDC;

      $this->txtRemarks         	  = $showVal->vRemarks;
	  $this->vTktNumber         	  = $showVal->vTktNumber;



      $this->Id                 	  = $showVal->nCRId;



      $accValArray              	  = $this->sales_receipt_model->getCorresSalesReceiptDetails();

      $data['chkAmount']        	  = $accValArray['Amt'];

      $data['chkCard']           	  = $accValArray['Card'];

      $data['chkTkt']             = $accValArray['Tkt'];

      $data['chkTktType']             = $accValArray['TktType'];

      $data['chkDetailID']      	  = $accValArray['DetId'];





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

		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate1')))) != 1))

		{

			$this->load->view('general/template_top',$data);

			$this->load->view('sales_receipt/edit_sales_receipt');

			$this->load->view('general/template_bottom');

		}//end if

		else

		{

			//checking segment

			switch($this->uri->segment(4))

			{

				case "a":

					//insert into db

					$this->sales_receipt_model->insertSalesReceipt();

				break;

				

				case "e":

					//Update into db

					$this->sales_receipt_model->updateSalesReceipt();

				break;

			}//end switch

			redirect(site_url("sales_receipt/showlist/".$this->uri->segment(3).'/'.$msgVal));

		}//end else	

	}//end function



  function delSalesreceipt()

  {

    $this->load->helper(array('form', 'url'));

    $this->showTitle = SALESRECEIPTMANAGEMENT;

    $this->common_model->check_admin_authorization();

    $this->sales_receipt_model->deleteSalesReceipt();

  }//end function



	//function for update database

	function deleteRecord()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		

		$this->subDetID = $this->input->post('subCode');

		$this->sales_receipt_model->deleteSubAndDetails($this->subDetID);

	}//end function





  function delrecords()

  {

    $this->load->helper(array('form', 'url'));

    $this->showTitle = SALESRECEIPTMANAGEMENT;

    $this->common_model->check_admin_authorization();

    $this->sales_receipt_model->deleteAllSalesReceipt();

  }//end function





  function filterDivision()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));



    $this->common_model->check_admin_authorization();

    //site Name

    $this->ddlDivision = $this->input->post('ddlDivision');

    $this->ddlLocation = $this->input->post('ddlLocation');



    $data['list_loc_value'] = $this->sales_receipt_model->filterLocation($this->ddlDivision);

    $this->load->view('sales_receipt/show_location',$data);

  }//end function



  function getRefName()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));



    $this->common_model->check_admin_authorization();

    //site Name

    $this->tktNum = $this->input->post('tktNum');

    $this->tktType = $this->input->post('tktType');

    switch($this->tktType)

    {



      case 'Htl':

        $tktPassName = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId = ".$this->tktNum." ");

        break;

      case 'Tkt':

        $tktPassName = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId = ".$this->tktNum." ");

        break;

      case 'Xo':

        $tktPassName = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId = ".$this->tktNum." ");

        break;



    }//end case

    echo ($tktPassName);

  }//end function
  
  function getRefNamt()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));



    $this->common_model->check_admin_authorization();

    //site Name

    $this->tktNum = $this->input->post('tktNum');

    $this->tktType = $this->input->post('tktType');

    switch($this->tktType)

    {



      case 'Htl':

        $tktAmt = $this->common_model->get_field_name('HotelVoucher','vTotalDC'," WHERE nHVId = ".$this->tktNum." ");

        break;

      case 'Tkt':

        $tktAmt = $this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId = ".$this->tktNum." ");

        break;

      case 'Xo':

        $tktAmt = $this->common_model->get_field_name('XoBooking','vGrandNetFare'," WHERE nXId = ".$this->tktNum." ");

        break;



    }//end case

    echo ($tktAmt);

  }//end function





  function filterTicket()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));



    $this->common_model->check_admin_authorization();

    //site Name

    $this->ddlDivision = $this->input->post('ddlDivision');

    $this->ddlLocation = $this->input->post('ddlLocation');



    $data['list_tkt'] = $this->sales_receipt_model->filterTicket($this->ddlLocation);

    $this->load->view('sales_receipt/show_ticket',$data);

  }//end function



    function getDocumentNumber()

    {

      $type = "Sales Receipt";

      $loc = $this->input->post('ddlLocation');



        $type    = $this->common_model->get_field_name('Transactions','nId'," WHERE vTitle='".$type."'");

        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");

        $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}SalesReceipt WHERE vLocation = '".$loc."' ORDER BY CAST(SUBSTRING_INDEX(vDocNumber,'-',-1) AS UNSIGNED)  DESC LIMIT 0,1");

        if($fetchCode->num_rows() > 0):

            $totalNum           = $fetchCode->row();

            $this->txtDocNumber = $totalNum->vDocNumber;

            $docNumber      = @split('-',$this->txtDocNumber);

            $fill         = $this->txtMinDigit;

            $number       = ($docNumber[3]+1);

            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;

        else:

            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");

            $docNumber      = @split('-',$this->txtDocNumber);

            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];

        endif;

        echo trim($countVal);

    }//end function



  function getEmailDetails()

  {

    $this->load->helper('url');

    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();

    $data['ddlLocation'] = $this->input->post('ddlLocation');

    $this->load->view('sales_receipt/show_emailDetails',$data);



  }

  function printPreview()

  {

    session_start();

    $this->load->helper(array('form', 'url'));



    //$this->catId   = $this->input->post('id');

    $this->catId   = $this->uri->segment(3);

      $showVal                    = $this->sales_receipt_model->getCorresSalesReceipt();

      $this->ddlDivision            = $showVal->nDivision;

      $this->ddlLocation            = $showVal->vLocation;

      $this->txtDate             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));

      $this->txtDocNumber         = $showVal->vDocNumber;

      $this->txtRefName             = $showVal->vRefName;

      $this->ddlIssued              = $showVal->vEnteredBy;

      $this->txtTotAmt              = $showVal->vAmountDC;

      $this->txtRemarks             = $showVal->vRemarks;
$this->vEnteredBy=$showVal->vEnteredBy;


      $this->Id                     = $showVal->nCRId;





      $accValArray                  = $this->sales_receipt_model->getCorresSalesReceiptDetails();

      $data['chkAmount']            = $accValArray['Amt'];

      $data['chkCard']              = $accValArray['Card'];

      $data['chkTkt']             = $accValArray['Tkt'];

      $data['chkTktType']             = $accValArray['TktType'];

      $data['chkDetailID']          = $accValArray['DetId'];



    //get selected details code

    $accValArray              = $this->sales_receipt_model->getCorresTicketDetails($data['chkTkt'],$data['chkTktType'], $data['chkAmount']);

    $data['chkType']    	  = $accValArray ['sdType'];

    $data['chkAirline'] 	  = $accValArray ['sdAirline'];

    $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];

    $data['chkPass']        	  = $accValArray ['sdPass'];

    $data['chkDesc']      = $accValArray ['sdDesc'];

    $data['chkTAmount']      = $accValArray ['sdAmt'];





    $mydata = $this->load->view('sales_receipt/export_files/print_sales_receipt',$data,true);

   $_SESSION['print_sales_receipt'] = $mydata;

    $_SESSION['print_sales_receipt_pgSz'] = 'A4';

    $_SESSION['print_sales_receipt_lp'] = 'P';

    $data['chkAcId']          = $saleValArray['AcID'];



    $this->load->view('sales_receipt/print_sales_receipt',$data);

  }//end function





} //end class

?>

