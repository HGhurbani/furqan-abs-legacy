<?php
class Travelsetup_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Travel Flags********************************/
	function getAllFlags()
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Flags 
											 ORDER BY vTitle ASC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function 
	
	function insertFlags()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Flags', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("flags/editFlags/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("flags/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function getCorresFlag()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Flags WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateFlags()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Flags', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("flags/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteFlags()
	{
		$this->db->delete('{PRE}Flags', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("flags/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllFlags()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Flags', $data); 
				}//end foreach
				redirect(site_url("flags/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Flags', $data); 
				}//end foreach
				redirect(site_url("flags/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Flags', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("flags/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterFlags($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Flags ".$extraCheck."
											 ORDER BY vTitle ASC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

/*----------------------Travel Account Setup-----------------*/
	
	function getAllTravelAccount()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlTravelsetupQuery    = "SELECT ts.*,t.vTitle as transName FROM {PRE}TravelSetup ts
									LEFT JOIN {PRE}Transactions t ON ts.nTransaction = t.nId ORDER BY ts.nId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTravelsetupQuery;	
		$adminGroupList 		= $this->db->query($sqlTravelsetupQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountTravelSetup()
	{		
		$tabCount = $this->db->query("SELECT ts.*,t.vTitle as transName,t.vCode FROM {PRE}TravelSetup ts
										LEFT JOIN {PRE}Transactions t ON ts.nTransaction = t.nId
									  ORDER BY ts.nId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresTravelsetup()
	{
		$fetchTitle = $this->db->query("SELECT ts.*,ts.nId as travelId,tsd.*,tsd.nId as tsdId,aha.* FROM {PRE}TravelSetup ts 
										 LEFT JOIN {PRE}TravelSetupDetails tsd ON ts.nId=tsd.nAcId
										LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId=aha.nId WHERE tsd.nAcId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresTravelsetupDetails()
	{
		$tsDetailsRow   = array();
		$tsDetailsCode  = array();
		$tsDetailsFlag  = array();
		$tsDetailsTitle = array();
		$newValArray    = array();
		$travelDetailID = array();
	
		$tsDetailsList  = $this->db->query("SELECT tsd.nAHAId,aha.vACode,aha.vTitle,tsd.nFlag,tsd.nId as dId FROM {PRE}TravelSetup ts 
										 	 LEFT JOIN {PRE}TravelSetupDetails tsd ON ts.nId=tsd.nAcId
											LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId=aha.nId WHERE tsd.nAcId ='".$this->catId."'");
		if ($tsDetailsList->num_rows() > 0)
		{
			foreach ($tsDetailsList->result() as $key=>$row)
			{
				$tsDetailsRow[]   = $row->nAHAId;
				$tsDetailsCode[]  = $row->vACode;
				$tsDetailsFlag[]  = $row->nFlag;
				$tsDetailsTitle[] = $row->vTitle;
				if(!@in_array($row->dId,$travelDetailID))
					$travelDetailID[]         = $row->dId;
				$newtravelValArray[]     	  = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".                                                                                      $row->nAHAId."'");
				if(!@in_array($row->nAHAId,$checkIdValArray,true))
				{
					$travelAcReference[] 	  = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".                                                                                   		$row->nAHAId."'");
					$checkIdValArray[]     	  = $row->nAHAId;  
				}//end if
				
			}//end for each
		}//end if
		
		$newValArray['acId']    	  = $tsDetailsRow;
		$newValArray['acCode']  	  = $tsDetailsCode;
		$newValArray['acFlag']  	  = $tsDetailsFlag;
		$newValArray['acTitle'] 	  = $tsDetailsTitle;
		$newValArray['actravelDetID'] = $travelDetailID;
		
		return ($newValArray);
	}//end function
	
	function insertTravelSetup()
	{
		$data 		= array('nUserId' => $this->session->userdata('AbsAdmin'),'vLocation' => @join(",",$this->input->post('ddlLocation')),'nTransaction' => $this->input->post('ddlTrans'),
							'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}TravelSetup', $data); 
		
		$id			= $this->db->insert_id();

		$accCode    = (@array_filter($this->input->post('txtAcccode')) != '')?$this->input->post('txtAcccode'):@split("[,]",$this->session->userdata('chkAcccode'));
		$accName    = (@array_filter($this->input->post('txtAccname')) != '')?$this->input->post('txtAccname'):@split("[,]",$this->session->userdata('txtAccname'));
		$flagName   = (@array_filter($this->input->post('ddlFlag')) != '')?$this->input->post('ddlFlag'):@split("[,]",$this->session->userdata('ddlFlag'));
	    //insert into travel setup details
		foreach($accCode as $key => $aitem)
		{
			$aitem              = @split(" - ",$aitem);
			$fetchId    		= $this->db->query("SELECT * FROM {PRE}AccountHeadAccounts WHERE vACode = '".$aitem[0]."'");
			$getVal				= $fetchId->row();
			$accId 				= $getVal->nId;
			$data 				= array('nAcId' => $id,'nAHAId' => $accId,'nFlag' => $flagName[$key],
									    'vActive' => $this->input->post('chkActive'));
			$this->db->insert('{PRE}TravelSetupDetails', $data);
		}//end foreach		
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("travel_setup/editTravelAccount/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("travel_setup/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateTravelSetup()
	{
		$data = array('vLocation' => @join(",",$this->input->post('ddlLocation')),'nUserId' => $this->session->userdata('AbsAdmin'),
		              'nTransaction' => $this->input->post('ddlTrans'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}TravelSetup', $data); 

		$id			= $this->input->post('nId');
		$accCode    = (@array_filter($this->input->post('txtAcccode')) != '')?$this->input->post('txtAcccode'):0;
		$accName    = (@array_filter($this->input->post('txtAccname')) != '')?$this->input->post('txtAccname'):0;
		$flagName   = (@array_filter($this->input->post('ddlFlag')) != '')?$this->input->post('ddlFlag'):0;

		$chkAccID	= $this->input->post('idArray');

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
			$aitem              = @split(" - ",$aitem);
			$fetchId    		= $this->db->query("SELECT * FROM {PRE}AccountHeadAccounts WHERE vACode = '".$aitem[0]."'");
			$getVal				= $fetchId->row();
			$accId 				= $getVal->nId;

			//checking already exists if yes updated
			if(intval($chkAccID[$key]) != 0)
			{
				$data 	= array('nAcId' => $id,'nAHAId' => $accId,
								'nFlag' => $flagName[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->where('nAHAId',$chkAccID[$key]);
				$this->db->update('{PRE}TravelSetupDetails', $data); 
			}//end if
			else
			{//if new insert into db
				$data 	= array('nAcId' => $id,'nAHAId' => $accId,
								'nFlag' => $flagName[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}TravelSetupDetails', $data);
			}//end else
		}//end foreach
		
		$msgVal = 'edit_suc';
		redirect(site_url("travel_setup/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function

	function deleteRowLedgerOpening()
	{
		$this->db->delete('{PRE}TravelSetupDetails', array('nAcId' => $this->uri->segment(4)));
	}//end function
	
	function deleteTravelsetup()
	{
		$this->db->delete('{PRE}TravelSetupDetails', array('nAcId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}TravelSetup', array('nId' => $this->uri->segment(4)));
		redirect(site_url("travel_setup/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllTravelsetup()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}TravelSetup', $data); 
					
					$this->db->where('nAcId',$val);
					$this->db->update('{PRE}TravelSetupDetails', $data); 
				}//end foreach
				redirect(site_url("travel_setup/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}TravelSetup', $data); 
					
					$this->db->where('nAcId',$val);
					$this->db->update('{PRE}TravelSetupDetails', $data); 
				}//end foreach
				redirect(site_url("travel_setup/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}TravelSetupDetails', array('nAcId' => $val));
					$this->db->delete('{PRE}TravelSetup', array('nId' => $val));
				}//end foreach
				redirect(site_url("travel_setup/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function
	
 	//get account head accounts details
	function getAcAccounts($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		//echo $this->db->last_query(); 
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vACode;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function

 	//get sub group details
	function getAcAccountsTitle($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vTitle;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function

	//get id
	function getAcAccountsID($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if
		return (array_filter($userTransRow));
	}//end function
	
	function getActiveTransactions()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1'
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getActiveFinance()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId WHERE ah.vActive = '1' 
											ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
	
	//search finance
	function searchActiveFinance($searchKey)
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId WHERE ah.vActive = '1' AND (aca.vACode LIKE ('%".$searchKey."%') OR
											aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode LIKE ( '%".$searchKey."%') OR
											aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle LIKE ('% ".$searchKey."%') OR aca.vTitle 
											LIKE ( '%".$searchKey."%')) ORDER BY nId DESC");
											
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		
		//for json encode
		$result = array();
		foreach ($typeGroupRow as $key=>$value) 
		{
				array_push($result, array(
					"name" => $value
				));
		}//end fuction
		return ($result);
	}//end function 
	
	//get selected finance
	function getSelectedFinance()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nAHAId  FROM {PRE}TravelSetupDetails WHERE nAcId  = '".$this->catId."' 
													AND vActive = '1' ORDER BY nAHAId DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[] = $row->nAHAId;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function 

	//get selected finance flag
	function getSelectedFinanceFlag()
	{
		$selFinanceGroupRow = array();
		$selFinanceGroup    = $this->db->query("SELECT nAHAId,nFlag  FROM {PRE}TravelSetupDetails WHERE nAcId  = '".$this->catId."' 
													AND vActive = '1' ORDER BY nAHAId DESC");
		if ($selFinanceGroup->num_rows() > 0)
		{
			foreach ($selFinanceGroup->result() as $key=>$row)
			{
				$selFinanceGroupRow[$row->nAHAId] = $row->nFlag;
			}//end for each
		}//end if
		return ($selFinanceGroupRow);
	}//end function 
	
	function getCorresFinance($trans)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID=ah.nAId WHERE ah.vActive = '1'
											 AND nId= '".$trans."' ORDER BY nId DESC");
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function filterTrans($trans)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Transactions ORDER BY nPosition DESC");
		
		if ($adminGroupList->num_rows() > 0)
		{
			$adminGroupRow[0] = SELECT;
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[$row->nId] = $row->vTitle;
			}//end for each
		} //end if
		else
		{
			$adminGroupRow[0] = NOVALUE;
		}//end funtion
		return ($adminGroupRow);
	}//end function

	function getActiveLocations()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function
	
	function getSalestype()
	{
		$typeGroupRow    = array("0" => SELECT, "General" => "General");
		return ($typeGroupRow);
	}//end function 
	
	function getActiveFlags()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Flags WHERE vActive = '1' 
											ORDER BY vTitle ASC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($travelid)
	{
		$viewAccessRow   = array();
		
		$viewAccessList  = $this->db->query("SELECT ts.nId as tsId,tsd.nId as tsdId,f.vTitle as flagName,ts.vLocation,
											 t.vTitle as transName,aha.*,aha.vTitle as accName FROM {PRE}TravelSetup 
												ts LEFT JOIN {PRE}TravelSetupDetails tsd
											 ON ts.nId = tsd.nAcId LEFT JOIN {PRE}Transactions t ON ts.nTransaction = t.nId
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId = aha.nId
											 LEFT JOIN {PRE}Flags f ON tsd.nFlag = f.nId
											 WHERE ts.nId='".$travelid."' ORDER BY tsd.nId DESC");
		//single fetch
		$commVal  = $viewAccessList->row();	
		$locId    = array_filter(@split(',',$commVal->vLocation));
		$locName  = $this->getLocationName($locId);			

		$content .= '<table class="reportForm">
						<tr>
							<td colspan="3"><strong>'.ACCNAME.'</strong></td>
						</tr>
						<tr>
							<td>'.TRANSNAME.'&nbsp;&nbsp;'.$commVal->transName.'</td>
							<td colspan="2">'.LOCATIONNAME.'&nbsp;&nbsp;'.$locName.'</td>
						</tr>
						<tr>
							<td>'.ACCCODENAME.'</td>
							<td>'.ACCNAME.'</td>
							<td>'.TRAVELFLAG.'</td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$content .= '<tr>
				<td>'.$row->vACode.'</td>
				<td>'.$row->accName.'</td>
				<td>'.$row->flagName.'</td>
			</tr>';
		}//end foreach
		
		$content .= '</table>';
	    return($content);
	}//end function
 
 	//get location name
	function getLocationName($locArray)
	{
		$locName         = array();
		//fetch location name
		foreach($locArray as $val)
		{
			$locName[]   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");	
		}//end foreach
		
		return(@join(",",$locName));
	}//end function
 
 	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}TravelSetupDetails', array('nId' => $ID));
	}//end function
  
	function getActiveTransaction()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1'
											ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = ALL;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
		
	}//end function

	function getFilterList($extraCheck)
	{
		$adminGroupRow                = array();
		$sqlTravelsetupFilterQuery    = "SELECT aha.vACode,aha.vTitle,ts.*,tsd.nId as tsdId,t.vTitle as transName
									       FROM {PRE}TravelSetup ts LEFT JOIN {PRE}TravelSetupDetails tsd ON ts.nId = tsd.nAcId
								         LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId = aha.nId 
										   LEFT JOIN {PRE}Transactions t ON
									     ts.nTransaction = t.nId ".$extraCheck." GROUP BY ts.nId ORDER BY ts.nId DESC
								           LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTravelsetupFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlTravelsetupFilterQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT aha.vACode,aha.vTitle,ts.*,tsd.nId as tsdId,t.vTitle as transName
											FROM {PRE}TravelSetup ts LEFT JOIN {PRE}TravelSetupDetails tsd ON ts.nId = tsd.nAcId
											 LEFT JOIN {PRE}AccountHeadAccounts aha ON tsd.nAHAId = aha.nId LEFT JOIN {PRE}Transactions t ON
											 ts.nTransaction = t.nId ".$extraCheck." GROUP BY ts.nId ORDER BY ts.nId DESC");
		return ($tabCount->num_rows());
	}//end function
	
}//end class
?>