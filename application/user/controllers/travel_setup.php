<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Travel_setup extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('travelsetup_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = TRAVELSETUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$data['list_trans_value']   = $this->travelsetup_model->getActiveTransaction();
		
		$countTravelsetup	        =  $this->travelsetup_model->getCountTravelSetup();
		$target = '.';
		$this->common_model->_paging($countTravelsetup, $target,'travel_setup/showlist/'.$this->uri->segment(3));
		$data['list_value']   		= $this->travelsetup_model->getAllTravelAccount();
		$this->count_rows     		= count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('travel_setup/travel_setup');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editTravelAccount()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');
		$data['list_trans_value'] = $this->travelsetup_model->getActiveTransactions();
		$data['list_loc_value']   = $this->travelsetup_model->getActiveLocations();
		$data['list_flag']        = $this->travelsetup_model->getActiveFlags();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWTRAVEL;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				$data['finance_details']  = $this->travelsetup_model->getActiveFinance();
				
				$this->txtAcCode   		  = $this->input->post('txtAcCode');
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->ddlTrans    		  = $this->input->post('ddlTrans');
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$data['chkAccCode']  	  = array();
				$data['chkAccTitle'] 	  = array();
				$data['chkFlag'] 	  	  = array();
				
			break;
			
			case "e":
				$this->showTitle      	  = EDITTRAVEL;
				$this->btnVal	      	  = UPDATETRAVEL;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->travelsetup_model->getCorresTravelsetup();

				$this->accId	      	  = $showVal->nId; 
				$this->ddlLocation	  	  = @split(',',$showVal->vLocation);
				$this->ddlTrans	      	  = $showVal->nTransaction;
				$this->ddlFlag        	  = $showVal->nFlag;
				//get selected details code
				$accValArray              = $this->travelsetup_model->getCorresTravelsetupDetails();
			    $data['chkAccCode']  	  = $accValArray ['acCode'];
		        $data['chkAccTitle'] 	  = $accValArray ['acTitle'];
				$data['chkFlag'] 		  = $accValArray ['acFlag'];
				$data['chkAccID']    	  = $accValArray ['acId'];
				$data['traveldetID']      = $accValArray['actravelDetID'];
				
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
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('travel_setup/edit_travelsetup');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->travelsetup_model->insertTravelSetup();
				break;
				
				case "e":
					//Update into db
					$this->travelsetup_model->updateTravelSetup();
				break;
			}//end switch
			redirect(site_url("travel_setup/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function deltravelsetup()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRAVELSETUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->travelsetup_model->deleteTravelsetup();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRAVELSETUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->travelsetup_model->deleteAllTravelsetup();
	}//end function 
	
	function filterTransaction()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlTranstype       = $this->input->post('ddlTranstype');
		$this->ddlTransaction     = $this->input->post('ddlTransaction');
		
		$data['list_trans_value'] = $this->travelsetup_model->filterTrans($this->ddlTranstype);
		$this->load->view('travel_setup/show_transaction',$data);
	}//end function

	function generateTravelaccount()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		//$this->ddlTranstype       = $this->input->post('ddlTranstype');
		$this->accID			  = $this->input->post('txtId');
		$data['list_sales_type']  = $this->travelsetup_model->getSalestype();
		$data['list_flag']        = $this->travelsetup_model->getActiveFlags();
		
		$data['travel_details']   = $this->travelsetup_model->travelAccountGeneration($this->accID);
		$this->load->view('travel_setup/show_details',$data);		
	}//end function
	
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey =  $this->input->post('q');
		$items     = $this->travelsetup_model->searchActiveFinance($searchKey);
		
		echo json_encode($items);
	}//end function

	//get travel details according to the A/c code selected
	function getTravelDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->travelsetup_model->deleteSubAndDetails($this->subDetID);
	}//end function

	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRAVELSETUPMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->ddlTransaction = $this->common_model->searchKeyword('ddlTransaction');
		$this->txtSearch 	  = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck = " WHERE(aha.vTitle LIKE '".$this->txtSearch." %' OR aha.vTitle LIKE '% ".$this->txtSearch."'  
							OR aha.vTitle LIKE '%".$this->txtSearch."%'
							OR aha.vACode LIKE '".$this->txtSearch." %' OR aha.vACode LIKE '% ".$this->txtSearch."'
							OR aha.vACode LIKE '%".$this->txtSearch."%')";
		}//end if
		else
		{
			switch($this->ddlTransaction)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " WHERE ts.nTransaction ='".$this->ddlTransaction."'";
				break;
			}//end switch
		}//end else
		
		if(!empty($this->ddlTransaction))
		{
			switch($this->ddlTransaction)
			{
				 case "0":
						$extraCheck .= " WHERE ts.nTransaction ='".$this->ddlTransaction."'";
				 break;
				   
				 default:
						$extraCheck .= " AND ts.nTransaction ='".$this->ddlTransaction."'";
				 break;
			}//end switch
		}//end if
		$countFilterLocation	=  $this->travelsetup_model->getCountfilterList($extraCheck);
		$target 				= '.';
		$this->common_model->_paging($countFilterLocation, $target,'travel_setup/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->travelsetup_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('travel_setup/travel_setup_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRAVELSETUPMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
	    $data['list_trans_value']	= $this->travelsetup_model->getActiveTransaction(); 
	    $this->ddlTransaction 		= $this->common_model->searchKeyword('ddlTransaction');
		$this->txtSearch 			= $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck = " WHERE(aha.vTitle LIKE '".$this->txtSearch." %' OR aha.vTitle LIKE '% ".$this->txtSearch."'  
							OR aha.vTitle LIKE '%".$this->txtSearch."%'
							OR aha.vACode LIKE '".$this->txtSearch." %' OR aha.vACode LIKE '% ".$this->txtSearch."'
							OR aha.vACode LIKE '%".$this->txtSearch."%')";
		}//end if
		else
		{
			switch($this->ddlTransaction)
			{
				case "0":
					$extraCheck .= " ";
				break;
				
				default:
					$extraCheck = " WHERE ts.nTransaction ='".$this->ddlTransaction."'";
				break;
			}//end switch
		}//end else
		
		if(!empty($this->ddlTransaction))
		{
			switch($this->ddlTransaction)
			{
				 case "0":
						$extraCheck .= " WHERE ts.nTransaction ='".$this->ddlTransaction."'";
				 break;
				   
				 default:
						$extraCheck .= " AND ts.nTransaction ='".$this->ddlTransaction."'";
				 break;
			}//end switch
		}//end if
		
		$countFilterLocation	=  $this->travelsetup_model->getCountfilterList($extraCheck);
		$target 				= '.';
		$this->common_model->_paging($countFilterLocation, $target,'travel_setup/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->travelsetup_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('travel_setup/travel_setup');
		$this->load->view('general/template_bottom');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>