<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Airline extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('airline_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = AIRLINEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target             = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Airline','nArId',$extraCheck), $target,'airline/showlist/'.$this->uri->segment(3));
		$data['list_value'] = $this->airline_model->getAirline($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'airline/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('airline/airline');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editAirline()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      		 = AIRLINEMANAGEMENT;
		$data['title'] 	      		 = $this->common_model->get_site_title(); 
		
		$data['list_airline_type']   = $this->airline_model->getAirlineType();		
		$data['list_ar_ac'] 	     = $this->airline_model->getReceivableAccounts();
		$data['list_ap_ac'] 	     = $this->airline_model->getPayableAccounts();
		$data['list_subar_ac'] 	     = $this->airline_model->getCustomerAccounts();
		$data['list_subap_ac'] 	     = $this->airline_model->getVendorAccounts();
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtAirlineName', 'Airline Name', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'airline/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("airline/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle      = ADDNEWAIRLINE;
				$this->btnVal	      = ADDCONTINUE;
				$this->btnVal2	      = SAVEEXIT;
				
				$this->txtAirlineNo	    = $this->input->post('txtAirlineNo');
				$this->txtAlpha	        = $this->input->post('txtAlpha');
				$this->txtAirlineName	= $this->input->post('txtAirlineName');
				$this->ddlAirlineType   = $this->input->post('ddlAirlineType');
				$this->ddlARAc   	    = $this->input->post('ddlARAc');
				$this->ddlSubARAc	    = $this->input->post('ddlSubARAc');
				$this->ddlAPAc   	    = $this->input->post('ddlAPAc');
				$this->ddlSubAPAc	    = $this->input->post('ddlSubAPAc');
				$this->txtTktComm	    = $this->input->post('txtTktComm');
				$this->txtXoComm	    = $this->input->post('txtXoComm');				
				$this->txtGSAComm		= $this->input->post('txtGSAComm');				
				
				$this->chkValYes      	= 'checked="checked"';
				$this->chkValNo	      	= '';
				$this->catId	      	= '';
				$data['chkAccCode']   	= array();
				$data['chkAccTitle']  	= array();
			break;
			
			case "e":
				$this->showTitle  	  	= EDITAIRLINE;
				$this->btnVal	  	  	= UPDATEAIRLINE;
				$this->catId	  	  	= $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   	  	= intval($this->input->post('nId'));
				}//end if
				$showVal	       	  	= $this->airline_model->getCorresAirline();
				$this->txtAirlineNo	    = $showVal->vAirlineNo;
				$this->txtAlpha	        = $showVal->vAlpha;
				$this->txtAirlineName	= $showVal->vTitle;
				$this->ddlAirlineType   = $showVal->vType;
				$this->ddlARAc   	    = $showVal->nAR;
				$this->ddlSubARAc	    = $showVal->nSubAr;
				$this->ddlAPAc   	    = $showVal->nAp;
				$this->ddlSubAPAc	    = $showVal->nSubAp;
				$this->txtTktComm	    = $showVal->nTkt;				
				$this->txtXoComm	    = $showVal->nXo;
				$this->txtGSAComm		= $showVal->nGsa;			
				$this->txtName	        = $showVal->vName;
				$this->txtDesignation	= $showVal->vDesignation;	
				$this->txtMobile	    = $showVal->vMobile;				
				$this->txtPhone 	    = $showVal->vPhone;
				$this->txtEmail 	    = $showVal->vEmail;
				$this->txtAddress 	    = $showVal->vAddress;

				//get selected fare code and value details
			    $data['chkAccCode']     = $this->airline_model->getAirlineCode();
		        $data['chkAccTitle']    = $this->airline_model->getAirlineValue();
				$data['chkAccID']    	= $this->airline_model->getAirlineID();
				
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
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' )
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('airline/edit_airline');
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
					$this->extraCond  = " AND nArId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			
			$chkCode = $this->db->query("SELECT vTitle FROM {PRE}Airline WHERE vTitle = 
											'".mysql_real_escape_string($this->input->post('txtAirlineName'))."'".$this->extraCond);
			if ($chkCode->num_rows() > 0)
			{
				$data['error_value'] = AIRLINENAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('airline/edit_airline');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
			
				$chkTitle = $this->db->query("SELECT vAirlineNo FROM {PRE}Airline WHERE 
											   vAirlineNo = '".mysql_real_escape_string($this->input->post('txtAirlineNo'))."'".$this->extraCond);
				if ($chkTitle->num_rows() > 0)
				{
					$data['error_value'] = AIRLINENOEXIST;
					$this->load->view('general/template_top',$data);
					$this->load->view('airline/edit_airline');
					$this->load->view('general/template_bottom');
				}//end if
				else
				{
					//checking segment
					switch($this->uri->segment(4))
					{
						case "a":
							//insert into db
							$this->airline_model->insertAirline();
						break;
						
						case "e":
							//update into db
							$this->airline_model->updateAirline();
						break;
					}//end switch
				}//end else
			}//end else
		}//end else	
	}//end function

	function delAirline()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = AIRLINEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->airline_model->deleteAirline();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = AIRLINEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->airline_model->deleteAirlineMultiple();
	}//end function 
	
	function filtershowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = AIRLINEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirlineName');
			$this->txtAirlineName = $this->common_model->searchKeyword('txtAirlineName');
		}//end if
		
		if(!empty($this->txtAirlineName))
		{
			$extraCheck = " WHERE (vTitle LIKE '".$this->txtAirlineName." %' OR vTitle LIKE '% ".$this->txtAirlineName."'  
							OR vTitle LIKE '%".$this->txtAirlineName."%'
							OR vAirlineNo LIKE '".$this->txtAirlineName." %' OR vAirlineNo LIKE '% ".$this->txtAirlineName."'
							OR vAirlineNo LIKE '%".$this->txtAirlineName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Airline','nArId',$extraCheck.""), $target,'airline/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->airline_model->getFilterAirline($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'airline/showlist');

		$this->load->view('airline/airline_filter',$data);
	}//end function 
	
	function filtershowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = AIRLINEMANAGEMENT;
		
		$this->common_model->check_admin_authorization();

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtAirlineName');
			$this->txtAirlineName = $this->common_model->searchKeyword('txtAirlineName');
		}//end if
		
		if(!empty($this->txtAirlineName))
		{
			$extraCheck = " WHERE (vTitle LIKE '".$this->txtAirlineName." %' OR vTitle LIKE '% ".$this->txtAirlineName."'  
							OR vTitle LIKE '%".$this->txtAirlineName."%'
							OR vAirlineNo LIKE '".$this->txtAirlineName." %' OR vAirlineNo LIKE '% ".$this->txtAirlineName."'
							OR vAirlineNo LIKE '%".$this->txtAirlineName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
	
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Airline','nArId',$extraCheck.""), $target,'airline/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->airline_model->getFilterAirline($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'airline/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('airline/airline');
		$this->load->view('general/template_bottom');
	}//end function 
	
	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nArId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtAirlineName = $this->input->post('txtAirlineName');
		$txtAirlineName       = $this->common_model->get_field_name('Airline','vTitle'," WHERE vTitle = '".mysql_real_escape_string($this->txtAirlineName)."' ".$extraCond."");
		if($txtAirlineName == $this->txtAirlineName)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

	function checkAirlineNo()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nArId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtAirlineNo = $this->input->post('txtAirlineNo');
		$txtAirlineNo       = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE vAirlineNo = '".mysql_real_escape_string($this->txtAirlineNo)."' ".$extraCond."");
		if($txtAirlineNo == $this->txtAirlineNo)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->airline_model->deleteSubAndDetails($this->subDetID);
	}//end function
	
	//function for update database
	function deleteOtherRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->airline_model->deleteSubAndOtherDetails($this->subDetID);
	}//end function
	
	//function for showing farecodes
	function farecode()
	{
		$this->load->library('session');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//set session for other details value
		$this->session->set_userdata('txtName',$this->input->post('txtName'));
		$this->session->set_userdata('txtDesignation',$this->input->post('txtDesignation'));
		$this->session->set_userdata('txtMobile',$this->input->post('txtMobile'));
		$this->session->set_userdata('txtPhone',$this->input->post('txtPhone'));
		$this->session->set_userdata('txtEmail',$this->input->post('txtEmail'));
		$this->session->set_userdata('txtAddress',$this->input->post('txtAddress'));
		
		$data['chkAccCode']  = array_filter(@split("[,]",$this->session->userdata('chkAccCode')));
		$data['chkAccTitle'] = array_filter(@split("[,]",$this->session->userdata('chkAccTitle')));
		$this->txtAccCode	 = $this->input->post('txtAccCode');
		$this->txtAccTitle	 = $this->input->post('txtAccTitle');
		$this->load->view('airline/show_farecode',$data);
	}//end function
	
	//function for showing other details
	function otherdetails()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//set session for fare code value
		$this->session->set_userdata('chkAccCode',$this->input->post('txtAccCode'));
		//set session for fare value
		$this->session->set_userdata('chkAccTitle',$this->input->post('txtAccTitle'));

		$data['txtName']          = array_filter(@split("[,]",$this->session->userdata('txtName')));
		$data['txtDesignation']   = array_filter(@split("[,]",$this->session->userdata('txtDesignation')));
		$data['txtMobile']        = array_filter(@split("[,]",$this->session->userdata('txtMobile')));
		$data['txtPhone']         = array_filter(@split("[,]",$this->session->userdata('txtPhone')));
		$data['txtEmail']         = array_filter(@split("[,]",$this->session->userdata('txtEmail')));
		$data['txtAddress']       = array_filter(@split("[,]",$this->session->userdata('txtAddress')));
		
		$data['chkAccCode']  	  = array_filter(@split("[,]",$this->session->userdata('chkAccCode')));
		$this->catId              = $this->input->post('nChkIDm');
		
		$this->load->view('airline/show_otherdetails',$data);
	}//end function

	function filterSubArAc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlARAc 		   = $this->input->post('ddlARAc');
		$data['list_subar_ac'] = $this->airline_model->generateSubArAc($this->ddlARAc);
		
		$this->load->view('airline/show_arac',$data);
	}//end function

	function filterSubApAc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->ddlAPAc 		   = $this->input->post('ddlAPAc');
		$data['list_subap_ac'] = $this->airline_model->generateSubApAc($this->ddlAPAc);
		
		$this->load->view('airline/show_apac',$data);
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
