<?php
class Airline_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getAirline($extraCheck)
	{		
		//active groups
		$adminGroupRow 			= array();
		$sqlAirlineQuery        = "SELECT * FROM {PRE}Airline ORDER BY nArId
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlAirlineQuery;	
		$adminGroupList 		= $this->db->query($sqlAirlineQuery);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
			
		return ($adminGroupRow);
	}//end function

	function getAirlineType()
	{
		$typeRow		  = array("0" => SELECT, "B" => "BSP", "NB" => "Non BSP");
		return($typeRow);
	}//end func
	
	//fetch corresponding airline details
	function getCorresAirline()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Airline WHERE nArId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

	function insertAirline()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vAirlineNo' => $this->input->post('txtAirlineNo'),'vAlpha' => $this->input->post('txtAlpha'),
						'vTitle' => $this->input->post('txtAirlineName'),'vType' => $this->input->post('ddlAirlineType'), 
						'nAR' => $this->input->post('ddlARAc'),'nSubAr' => $this->input->post('ddlSubARAc'),
						'nAp' => $this->input->post('ddlAPAc'),'nSubAp' => $this->input->post('ddlSubAPAc'),
						'nTkt' => $this->input->post('txtTktComm'),'nXo' => $this->input->post('txtXoComm'),
						'nGsa' => $this->input->post('txtGSAComm'),'vName' => $this->input->post('txtName'),
						'vDesignation' => $this->input->post('txtDesignation'),'vMobile' => $this->input->post('txtMobile'),
						'vPhone' => $this->input->post('txtPhone'),'vEmail' => $this->input->post('txtEmail'),
						'vAddress' => $this->input->post('txtAddress'),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Airline', $data); 
		
		$id	         = $this->db->insert_id();
		
		$accCode     =  (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):@split("[,]",$this->session->userdata('chkAccCode'));
		$accName     =  (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):@split("[,]",$this->session->userdata('chkAccTitle'));
        //print_r($accName );
	    //insert into Farecode table
		foreach($accCode as $key => $aitem)
		{
			$data 	= array('nAirline' => $id,'vCode' => $aitem,'vValue' => $accName[$key]);
			$this->db->insert('{PRE}FareCode', $data);
		}//end foreach
		
		$msgVal = 'add_suc';

		$this->session->unset_userdata('chkAccCode');
		$this->session->unset_userdata('chkAccTitle');
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("airline/editAirline/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("airline/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCardAc'));		
		}//end if
	}//end function
	
	function updateAirline()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vAirlineNo' => $this->input->post('txtAirlineNo'),'vAlpha' => $this->input->post('txtAlpha'),
						'vTitle' => $this->input->post('txtAirlineName'),'vType' => $this->input->post('ddlAirlineType'), 
						'nAR' => $this->input->post('ddlARAc'),'nSubAr' => $this->input->post('ddlSubARAc'),
						'nAp' => $this->input->post('ddlAPAc'),'nSubAp' => $this->input->post('ddlSubAPAc'),
						'nTkt' => $this->input->post('txtTktComm'),'nXo' => $this->input->post('txtXoComm'),
						'nGsa' => $this->input->post('txtGSAComm'),'vName' => $this->input->post('txtName'),
						'vDesignation' => $this->input->post('txtDesignation'),'vMobile' => $this->input->post('txtMobile'),
						'vPhone' => $this->input->post('txtPhone'),'vEmail' => $this->input->post('txtEmail'),
						'vAddress' => $this->input->post('txtAddress'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nArId',$this->input->post('nId'));
		$this->db->update('{PRE}Airline', $data); 
		
		$id	         = $this->input->post('nId');
		
		$accCode     =  (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):@split("[,]",$this->session->userdata('chkAccCode'));
		$accName     =  (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):@split("[,]",$this->session->userdata('chkAccTitle'));
		$accId       = $this->input->post('idHAArray');
		
	    //insert into Farecode table
		foreach($accCode as $key => $aitem)
		{
			//checking already exists if yes updated
			if(intval($accId[$key]) != 0)
			{
				$data 	= array('nAirline' => $id,'vCode' => $aitem,'vValue' => $accName[$key]);
				$this->db->where('nFId',$accId[$key]);
				$this->db->update('{PRE}FareCode', $data); 
			}//end if
			
			else
			{
				//if new insert into db
				$data 	= array('nAirline' => $id,'vCode' => $aitem,'vValue' => $accName[$key]);
				$this->db->insert('{PRE}FareCode', $data); 
				//function insert FareCode
			}//end else
		}//end foreach
		
		$msgVal = 'edit_suc';
		redirect(site_url("airline/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCardAc'));	
	}//end function
	
	function deleteAirline()
	{
		$this->db->delete('{PRE}Airline', array('nArId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}FareCode', array('nAirline' => $this->uri->segment(4)));		
		redirect(site_url("airline/showlist/".$this->uri->segment(3).'/del_suc/'));
	}//end function 
	
	function deleteAirlineMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}Airline', $data); 
				}//end foreach
				redirect(site_url("airline/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}Airline', $data); 
				}//end foreach
				redirect(site_url("airline/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Airline', array('nArId' => $val));
					$this->db->delete('{PRE}FareCode', array('nAirline' => $val));  
				}//end foreach
				redirect(site_url("airline/showlist/".$this->input->post('nCId').'/del_suc/'));
			break;
		}//end switch
	}//end function 

	function getReceivableAccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 

	function getPayableAccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubLedgerType = 'V' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 

	function getCustomerAccounts()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function 

	function getVendorAccounts()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function 

	function getActiveCustomerAccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 

	function getActiveVendorAccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubLedgerType = 'V' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 
	
 	//get airline fare code details
	function getAirlineCode()
	{
		$airlineCodeRow = array();
		$airlineCode    = $this->db->query("SELECT vCode FROM {PRE}FareCode WHERE nAirline = '".$this->catId."'");
		
		if ($airlineCode->num_rows() > 0)
		{
			foreach ($airlineCode->result() as $key => $row)
			{
				$airlineCodeRow[] = $row->vCode;
			}//end for each
		}//end if
		
		return (array_filter($airlineCodeRow));
	}//end function

 	//get airline fare value details
	function getAirlineValue()
	{
		$airlineValueRow = array();
		$airlineValue    = $this->db->query("SELECT vValue FROM {PRE}FareCode WHERE nAirline = '".$this->catId."'");
		
		if ($airlineValue->num_rows() > 0)
		{
			foreach ($airlineValue->result() as $key => $row)
			{
				$airlineValueRow[] = $row->vValue;
			}//end for each
		}//end if
		
		return (array_filter($airlineValueRow));
	}//end function

	//get airline id
	function getAirlineID()
	{
		$airlineIdRow = array();
		$airlineId    = $this->db->query("SELECT nFId FROM {PRE}FareCode WHERE nAirline = '".$this->catId."'");
		
		if ($airlineId->num_rows() > 0)
		{
			foreach ($airlineId->result() as $key => $row)
			{
				$airlineIdRow[] = $row->nFId;
			}//end for each
		}//end if
		
		return (array_filter($airlineIdRow));
	}//end function

 	//get airline other details contact name
	function getAirlineName()
	{
		$airlineNameRow = array();
		$airlineName    = $this->db->query("SELECT vName FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineName->num_rows() > 0)
		{
			foreach ($airlineName->result() as $key => $row)
			{
				$airlineNameRow[] = $row->vName;
			}//end for each
		}//end if
		
		return (array_filter($airlineNameRow));
	}//end function

    //get airline other details contact designation
	function getAirlineDesignation()
	{
		$airlineDesRow = array();
		$airlineDes    = $this->db->query("SELECT vDesignation FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineDes->num_rows() > 0)
		{
			foreach ($airlineDes->result() as $key => $row)
			{
				$airlineDesRow[] = $row->vDesignation;
			}//end for each
		}//end if
		
		return (array_filter($airlineDesRow));
	}//end function

    //get airline other details contact mobile
	function getAirlineMobile()
	{
		$airlineMobileRow = array();
		$airlineMobile    = $this->db->query("SELECT vMobile FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineMobile->num_rows() > 0)
		{
			foreach ($airlineMobile->result() as $key => $row)
			{
				$airlineMobileRow[] = $row->vMobile;
			}//end for each
		}//end if
		
		return (array_filter($airlineMobileRow));
	}//end function

    //get airline other details contact phone
	function getAirlinePhone()
	{
		$airlinePhoneRow = array();
		$airlinePhone    = $this->db->query("SELECT vPhone FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlinePhone->num_rows() > 0)
		{
			foreach ($airlinePhone->result() as $key => $row)
			{
				$airlinePhoneRow[] = $row->vPhone;
			}//end for each
		}//end if
		
		return (array_filter($airlinePhoneRow));
	}//end function

	function getAirlineEmail()
	{
		$airlineEmailRow = array();
		$airlineEmail    = $this->db->query("SELECT vEmail FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineEmail->num_rows() > 0)
		{
			foreach ($airlineEmail->result() as $key => $row)
			{
				$airlineEmailRow[] = $row->vEmail;
			}//end for each
		}//end if
		
		return (array_filter($airlineEmailRow));
	}//end function

	function getAirlineAddress()
	{
		$airlineAddressRow = array();
		$airlineAddress    = $this->db->query("SELECT vAddress FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineAddress->num_rows() > 0)
		{
			foreach ($airlineAddress->result() as $key => $row)
			{
				$airlineAddressRow[] = $row->vAddress;
			}//end for each
		}//end if
		
		return (array_filter($airlineAddressRow));
	}//end function

	function getAirlineContactID()
	{
		$airlineCIdRow = array();
		$airlineCId    = $this->db->query("SELECT nArcId FROM {PRE}AirlineContact WHERE nAirline = '".$this->catId."'");
		
		if ($airlineCId->num_rows() > 0)
		{
			foreach ($airlineCId->result() as $key => $row)
			{
				$airlineCIdRow[] = $row->nArcId;
			}//end for each
		}//end if
		
		return (array_filter($airlineCIdRow));
	}//end function
	
	function getFilterAirline($extraCheck)
	{
		$adminGroupRow          = array();
		$sqlAirlineFilterQuery  = "SELECT * FROM {PRE}Airline ".$extraCheck." ORDER BY nArId
									LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlAirlineFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlAirlineFilterQuery);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}FareCode', array('nFId' => $ID));
	}//end function

	//function to delete sub and other details from db
	function deleteSubAndOtherDetails($ID)
	{
		$this->db->delete('{PRE}AirlineContact', array('nArcId' => $ID));
	}//end function

	//fetching single details
	function showDetails($arId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT ar.*,fc.*,
												CASE 
													WHEN ar.vType = 'B' THEN 'BSP'
												ELSE 'Non BSP' END as airlineType
											   FROM {PRE}Airline ar LEFT JOIN {PRE}FareCode fc ON ar.nArId = fc.nAirline
											   WHERE ar.nArId = '".$arId."' GROUP BY nFId ORDER BY ar.nArId DESC");
		//single fetch
		$commVal  		 = $viewAccessList->row();	

		$content = '<table class="reportForm">
						<tr>
							<td><label><b>'.AIRLINENO.' :</b></td>
							<td>'.$commVal->vAirlineNo.'</label></td>
							<td><label><b>'.AIRLINEALPHA.' :</b></td>
							<td>'.$commVal->vAlpha.'</label></td>
							<td><label><b>'.AIRLINENAME.' :</b></td>
							<td>'.$commVal->vTitle.'</label></td>
							<td><label><b>'.AIRLINETYPE.' :</b></td>
							<td>'.$commVal->airlineType.'</label></td>
							<td><label><b>'.ARAC.' :</b></td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$commVal->nAR."' ").'</label></td>
						</tr>
						<tr>
							<td><label><b>'.SUBAC.' :</b></td>
							<td>'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$commVal->nSubAr."' ").'</label></td>
							<td><label><b>'.APAC.' :</b></td>
							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$commVal->nAp."' ").'</label></td>
							<td><label><b>'.SUBAC.' :</b></td>
							<td>'.$this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$commVal->nSubAp."' ").'</label></td>
							<td><label><b>'.TKTCOMMISSION.' :</b></td>
							<td>'.$commVal->nTkt.'</label> </td>
							<td><label><b>'.XOCOMMISSION.' :</b></td>
							<td>'.$commVal->nXo.'</label></td>
						</tr>
						<tr>
							<td><label><b>'.GSACOMMISSION.' :</b></td>
							<td>'.$commVal->nGsa.'</label></td>
							<td><label><b>'.NAME.' :</b></td>
							<td>'.$commVal->vName.'</label></td>
							<td><label><b>'.DESIGNATION.' :</b></td>
							<td>'.$commVal->vDesignation.'</label></td>
							<td><label><b>'.MOBILE.' :</b></td>
							<td>'.$commVal->vMobile.'</label></td>
							<td><label><b>'.LANDPHONE.' :</b></td>
							<td>'.$commVal->vPhone.'</label></td>
						</tr>
						<tr>
							<td><label><b>'.EMAIL.' :</b></td>
							<td>'.$commVal->vEmail.'</label></td>
							<td><label><b>'.ADDRESS.' :</b></td>
							<td>'.$commVal->vAddress.'</label></td>
						</tr>

					</table><br />';
					
		$content .= '<table>
						<tr>
							<td colspan="2"><strong>'.FARECODE.'</strong></td>
						</tr>
						<tr>
							<td><b>'.CODE.'</b></td>
							<td><b>'.FAREVALUE.'</b></td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$content .= '<tr>
				<td>'.$row->vCode.'</td>
				<td>'.$row->vValue.'</td>
			</tr>';
		}//end foreach
		

		$content .= '</table>';
	    return($content);
	}//end function

	function generateSubArAc($arAc)
	{
		$subArAccGroupRow = array();
		$subArAccGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nFId = '".$arAc."' ORDER BY nCId DESC");
		if ($subArAccGroup->num_rows() > 0)
		{
			$subArAccGroupRow[0] = SELECT;
			foreach ($subArAccGroup->result() as $key => $row)
			{
				$subArAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$subArAccGroupRow[0] = NOVALUE;
		}//end else

		return($subArAccGroupRow);
	}//end function 

	function generateSubApAc($arAp)
	{
		$subApAccGroupRow = array();
		$subApAccGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$arAp."' ORDER BY nVId DESC");
		if ($subApAccGroup->num_rows() > 0)
		{
			$subApAccGroupRow[0] = SELECT;
			foreach ($subApAccGroup->result() as $key => $row)
			{
				$subApAccGroupRow[$row->nVId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$subApAccGroupRow[0] = NOVALUE;
		}//end else

		return($subApAccGroupRow);
	}//end function 
	
}//end class
?>