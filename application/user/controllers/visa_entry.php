<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Visa_Entry extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('visa_entry_model');
		$this->load->model('refund_closing_model');
		
	}


	function showlist()
  {
    $this->load->helper(array('form', 'url'));
    $this->showTitle = "VISA ENTRY";
    $this->common_model->check_admin_authorization();
	$data['list_visa_type']             = $this->visa_entry_model->visatype();

    $this->session->unset_userdata('backURL');

		//site Name
		$target 			= '.';
		$this->common_model->_paging($this->visa_entry_model->getAllVisaEntryCount(), $target,'visa_entry/showlist/'.$this->uri->segment(3));
		$data['list_value']      = $this->visa_entry_model->getAllVisaEntryDetails();
		$this->count_rows        = count($data['list_value']);

    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

    $this->load->view('general/template_top');
    $this->load->view('visa_entry/visa_entry',$data);
    $this->load->view('general/template_bottom');

  }//end function
  
  function editVisa()
  {
    $this->common_model->check_admin_authorization();
    //site Name
    $data['title'] 	            = $this->common_model->get_site_title();
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
    $id = $this->uri->segment(4);

    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'visa_entry/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("visa_entry/showlist/".$this->uri->segment(3).'/no_access'));
    }

    $data['list_div_value']	            = $this->refund_closing_model->getActiveDivision();
    $data['list_gds_value']             = $this->refund_closing_model->getActiveGDS();
    $data['list_type_value']            = $this->visa_entry_model->getActiveType();
    $data['list_emp_value']             = $this->refund_closing_model->getActiveEmployees();
	$data['list_visa_type']             = $this->visa_entry_model->visatype();
	$data['list_gender_type']             = $this->visa_entry_model->getGender();
	$data['list_mstatus_type']             = $this->visa_entry_model->getmstatus();
	$data['list_agent_type']             = $this->visa_entry_model->getAgent();
	$data['list_customer_type']             = $this->visa_entry_model->getCustomer();
   $data['list_doc']   	  = $this->visa_entry_model->getCRV(239,'C',$this->ddlLocation,$this->uri->segment(4));

    switch($this->uri->segment(4))
    {
      case "a":
        $this->showTitle  	  	  = "ADD NEW VISA ENTRY";
      $this->btnVal	  	  	      = ADDCONTINUE;
      $this->btnVal2	  	  	  = SAVEEXIT;
      $this->btnVal3	      	  = SAVECLOSE;

      
      $data['list_loc_value']   = $this->refund_closing_model->getLocation();
    
      $this->ddlDivision 	      = $this->input->post('ddlDivision');
      $this->ddlLocation  	  = $this->input->post('ddlLocation');
      $this->txtDate  	      = date('d-m-Y');
      $this->txtDocNumber  	  = $this->input->post('txtDocNumber');
      $this->ddlType  	  	  = $this->input->post('ddlType');
      $this->txtPassenger	  	  = $this->input->post('txtPassenger');
      $this->nVisaType   	  	  = $this->input->post('ddlvisatype');
	  $this->txtstk   	  	  = $this->input->post('txtstk');
	  $this->txtvoc   	  	  = $this->input->post('txtvoc');
	  $this->ddlissue   	  	  = $this->input->post('ddlissue');
	  
      

      //get billing details
      $this->txtPass 	  	  = $this->input->post('txtPass');
      $this->txtcontact	  		  = $this->input->post('txtcontact');
      $this->txtdob 	  	  = $this->input->post('txtdob');
      $this->txtpassport 	  	  = $this->input->post('txtpassport');
      $this->txtnation 	  	  = $this->input->post('txtnation');
      $this->txtdoi 		  = $this->input->post('txtdoi');
      $this->txtdoe          	  = $this->input->post('txtdoe');
      $this->ddlgender        = $this->input->post('ddlgender');
      $this->mstatus        = $this->input->post('mstatus');
      $this->ddlAgent  	  = $this->input->post('ddlAgent');
      $this->ddlcustomer 	  = $this->input->post('ddlcustomer');
      

      break;

      case "e":
        $this->showTitle  	  	  = "EDIT VISA ENTRY";
        $this->btnVal	      	  = "UPDATE ENTRY";
        if(!trim($this->session->userdata('backURL2')))
        $this->btnVal3	      	  = UPDATECLOSE;
        $this->catId	      	  = $this->uri->segment(5);

//      $this->common_model->getPHPSelf2($_SERVER['PHP_SELF']);

      if(intval($this->input->post('nId')) != 0)
      {
        $this->catId  	  	  = intval($this->input->post('nId'));
		$this->catI  	  	  = intval($this->input->post('nId'));
      }//end if

      if(trim($this->input->post('nCheck')) != '')
      {
        $this->nCheck  	  	  = $this->input->post('nCheck');
      }//end if

      $showVal	          	      = $this->visa_entry_model->getVisaEntry();
      $this->ddlDivision 	      = $showVal->nDivision;
      $this->ddlLocation  	      = $showVal->nLocation;
      $this->txtDate  	          = date('d-m-Y', strtotime($showVal->dDate));
      $this->txtDocNumber  	  = $showVal->vDocNumber;
      $this->ddlType  	  	  = $showVal->vStatus;
      
	  $this->vTicktManual 	  = $showVal->vTicktManual;
	  $this->issue 	  = $showVal->vIssuedBy;
	  $this->vType 	  = $showVal->nVisaType;
	  $this->txtNetAmt 	  = $showVal->vTotalNetAmt;
	  $this->txtSlsAmt 	  = $showVal->vTotalSellAmt;
	  $this->txtprofit=$showVal->vTotalProfit;
	   $this->remarks=$showVal->remarks;
	  
	  
				
      

      $data['list_loc_value']   = $this->refund_closing_model->getCorrActiveLocation($this->ddlDivision);

      
	 $AmtDetails           = $this->visa_entry_model->getVisaEntryDetails_amt(); 

      $data['list_doc']   	  = $this->visa_entry_model->getCRV(239,'C',$this->ddlLocation,$this->uri->segment(4));
     // $data['chkDetailID_1']      = $AmtDetails ['id'];
      $data['chkAcId']    	  = $AmtDetails['id']; 
	  $data['chkDrAccMain']    	  = $AmtDetails['id'];
		$data['chkdoc']  	      = $AmtDetails['doc'];
      $data['chkslsAmt']  	      = $AmtDetails['slsamt'];
      $data['chkNetamt']  	  = $AmtDetails['netamt'];
      $data['chkProfit']       = $AmtDetails['profit'];
      $data['chkAgent']   = $AmtDetails['acc'];

      $EntryDetails           = $this->visa_entry_model->getVisaEntryDetails();

      
      $data['chkDetailID']      = $EntryDetails ['id'];
      $data['chkAccID']    	  = $EntryDetails['id'];
	  
      $data['vPassName']  	      = $EntryDetails['vPassName'];
      $data['vPhone']  	  = $EntryDetails['vPhone'];
      $data['vPassportNo']       = $EntryDetails['vPassportNo'];
      $data['dDOB']   = $EntryDetails['dDOB'];
      $data['vNationality'] 	  = $EntryDetails['vNationality'];
      $data['dVisaIssueDate'] 	      = $EntryDetails['dVisaIssueDate'];
      $data['dVisaExpiryDate'] 	  = $EntryDetails['dVisaExpiryDate'];
      $data['vGender'] 	  = $EntryDetails['vGender'];
      $data['vMaritalStatus'] 	  = $EntryDetails['vMaritalStatus'];
      $data['nAgentId'] 	      = $EntryDetails['nAgentId'];
      $data['nCustomerId'] 	      = $EntryDetails['nCustomerId'];
	  
	  
	  
     
	  
      

      
    }//end switch

    // User access aa
    if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
    {
      $this->load->view('general/template_top',$data);
      $this->load->view('visa_entry/edit_visa_register');
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
          $this->visa_entry_model->insertVisa();
        break;

        case "e":
          //Update into db
          $this->visa_entry_model->updateVisa();
        break;
      }//end switch
      redirect(site_url("visa_entry/showlist/".$this->uri->segment(3).'/'.$msgVal));
    }//end else
  }//end function

function delvisaentry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = "Deleted";
		$this->common_model->check_admin_authorization();
		$this->visa_entry_model->deletevisa();
	}//end function 

function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->visa_entry_model->deleteSubAndDetails($this->subDetID);
	}//end function
	function deleteRecord2()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->visa_entry_model->deleteSubAndDetails1($this->subDetID);
	}//end function

function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->refund_closing_model->filterLocation($this->ddlDivision);
		$this->load->view('refund_closing/show_location',$data);
	}//end function
	
	function printPreview1()
    {
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->load->library('form_validation');
        session_start();

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

       $showVal	          	  	  = $this->visa_entry_model->getCorresVisa();
				$this->ddlDivision	  	  	  = 1;
				$this->ddlLocation	  	  	  = $showVal->nLocation;

				$this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
				$this->vTicktManual  	  	  = $showVal->vTicktManual;
				$this->txtDocNumber  	  	  = $showVal->vDocNumber;
				$this->remarks 	  	  	  = $showVal->remarks;
				$this->vIssuedBy 	  	  = $showVal->vIssuedBy;
				$this->nVisaType 	  	  = $showVal->nVisaType;
				$this->vStatus 	  	  = $showVal->vStatus;
				$this->vTotalNetAmt 	  	  = $showVal->vTotalNetAmt;
				$this->vTotalSellAmt 	  	  = $showVal->vTotalSellAmt;
				$this->vTotalProfit 	  	  = $showVal->vTotalProfit;
				
				
		 
	 $AmtDetails           = $this->visa_entry_model->getVisaEntryDetails_amt(); 

      
     // $data['chkDetailID_1']      = $AmtDetails ['id'];
      $data['chkAcId']    	  = $AmtDetails['id']; 
	  $data['chkDrAccMain']    	  = $AmtDetails['id'];
	  
      $data['chkslsAmt']  	      = $AmtDetails['slsamt'];
      $data['chkNetamt']  	  = $AmtDetails['netamt'];
      $data['chkProfit']       = $AmtDetails['profit'];
      $data['chkAgent']   = $AmtDetails['acc'];

      $EntryDetails           = $this->visa_entry_model->getVisaEntryDetails();

      
      $data['chkDetailID']      = $EntryDetails ['id'];
      $data['chkAccID']    	  = $EntryDetails['id'];
	  
      $data['vPassName']  	      = $EntryDetails['vPassName'];
      $data['vPhone']  	  = $EntryDetails['vPhone'];
      $data['vPassportNo']       = $EntryDetails['vPassportNo'];
      $data['dDOB']   = $EntryDetails['dDOB'];
      $data['vNationality'] 	  = $EntryDetails['vNationality'];
      $data['dVisaIssueDate'] 	      = $EntryDetails['dVisaIssueDate'];
      $data['dVisaExpiryDate'] 	  = $EntryDetails['dVisaExpiryDate'];
      $data['vGender'] 	  = $EntryDetails['vGender'];
      $data['vMaritalStatus'] 	  = $EntryDetails['vMaritalStatus'];
      $data['nAgentId'] 	      = $EntryDetails['nAgentId'];
      $data['nCustomerId'] 	      = $EntryDetails['nCustomerId'];
				 
		
        $mydata = $this->load->view('cashbook_receipt/export_files/print_cash_receipt',$data,true);
        $_SESSION['print_cash_receipt'] = $mydata;
        $_SESSION['print_cash_receipt_pgSz'] = 'A4';
        $_SESSION['print_cash_receipt_lp'] = 'P';


        $this->load->view('general/title'); 
        $this->load->view('visa_entry/print_visa_receipt',$data);
    }//end function
	
	function getCRV()
    {
		header_remove('Set-Cookie');
        $this->load->helper(array('form', 'url'));

        $accName            = $this->input->post('ddlDrAccMain');
        $accType            = 'V';//$this->input->post('tableType');
        
		$loc         = $this->input->post('ddlloc');
		$this->doc=$this->input->post('ddldoc');
        $data['crv_list']   = $this->visa_entry_model->getCRV($accName,$accType,$loc,$doc);
       $this->load->view('visa_entry/show_crv',$data);
    }//end function
	
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = "VISA ENTRY";
		
		$this->common_model->check_admin_authorization();
		
		$this->txtPassName  = $this->common_model->searchKeyword('txtPassName');
		$this->txtdoc  = $this->common_model->searchKeyword('txtdoc');
		$this->txtfile  = $this->common_model->searchKeyword('txtfile');
		$this->txtstk  = $this->common_model->searchKeyword('txtstk');
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtdoc');
			$this->session->unset_userdata('txtfile');
			$this->session->unset_userdata('txtstk');
			
			$this->txtPassName  = $this->common_model->searchKeyword('txtPassName');
		$this->txtdoc  = $this->common_model->searchKeyword('txtdoc');
		$this->txtfile  = $this->common_model->searchKeyword('txtfile');
		$this->txtstk  = $this->common_model->searchKeyword('txtstk');
		}//end if
		
		if(!empty($this->txtPassName))
		{
			$extraCheck 	= " AND(vpd.vPassName  LIKE '".$this->txtPassName." %' OR vpd.vPassName LIKE '% ".$this->txtPassName."'
									OR vpd.vPassName LIKE '%".$this->txtPassName."%')";
		}//end if
		
		if(!empty($this->txtdoc))
		{
			$extraCheck 	.= " AND(ve.vDocNumber  LIKE '".$this->txtdoc." %' OR ve.vDocNumber LIKE '% ".$this->txtdoc."'
									OR ve.vDocNumber LIKE '%".$this->txtdoc."%')";
		}//end if
		if(!empty($this->txtfile))
		{
			$extraCheck 	.= " AND(ve.vTicktManual  LIKE '".$this->txtfile." %' OR ve.vTicktManual LIKE '% ".$this->txtfile."'
									OR ve.vTicktManual LIKE '%".$this->txtfile."%')";
		}//end if
		if(!empty($this->txtstk))
		{
			$extraCheck 	.= " AND ve. nVisaType=".$this->txtstk." ";
		}//end if
		//$countFilterList	=  $this->group_entry_model->getCountfilterList($extraCheck);
		//$target 			= '.';
		//$this->common_model->_paging($countFilterList, $target,'cashbook_receipt/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->visa_entry_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_receipt/showlist');

		$this->load->view('visa_entry/visa_entry_filter',$data);
	}//end function
	
function generateDocNo()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlType          = 'V';
        $this->ddlLocation      = $this->input->post('ddlLocation');
		$this->dt      = $this->input->post('dt');
		 $date=substr($this->dt,-4);

        if($this->ddlType == 'V'):
            $type = 28;
        
        endif;

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
		 $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}VisaEntry WHERE
                                                     nLocation = ". $this->ddlLocation." AND YEAR(dDate) ='". $date."' ORDER BY  CAST(SUBSTRING_INDEX(vDocNumber,'-',-1) AS UNSIGNED) DESC LIMIT 0,1");
         
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

        echo $countVal;
    }//end function
  

}//end class

?>
