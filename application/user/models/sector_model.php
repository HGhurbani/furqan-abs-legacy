<?php
class Sector_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	
	function getAllSector()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlBankbookQuery   	= "SELECT * FROM {PRE}Sector ORDER BY  vName 
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlBankbookQuery;	
		$adminGroupList 		= $this->db->query($sqlBankbookQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountSector()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}Sector ORDER BY nSecId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresSector()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Sector WHERE nSecId = '".$this->catId."' ");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresSectorDetails()
	{
		$sectorDetailsRow = array();
		$sectorCountry    = array();
		$sectorSector 	  = array();
		$sectorDes  	  = array();
		$sectorDomestic   = array();
		$newValArray      = array();

		
		$sectorDetailsList      	  = $this->db->query("SELECT sd.nCountry, sd.vSector, sd.vName, sd.vDomestic, 
																sd.nSecDId FROM {PRE}Sector s
										 	 	 	  				LEFT JOIN {PRE}SectorMasterDetails sd ON s.nSecId = sd.nSector
											      		 		WHERE sd.nSector = '".$this->catId."'");
		if ($sectorDetailsList->num_rows() > 0)
		{
			foreach ($sectorDetailsList->result() as $key=>$row)
			{
				$sectorDetailsRow[] = $row->nSecDId;
				$sectorCountry[]    = $row->nCountry;
				$sectorSector[]     = $row->vSector;
				$sectorDes[] 	    = $row->vName;
				$sectorDomestic[]   = $row->vDomestic;
			}//end for each
		}//end if
		
		$newValArray['scId']      	  = $sectorDetailsRow;
		$newValArray['scCountry']     = $sectorCountry;
		$newValArray['scSector']      = $sectorSector;
		$newValArray['scDes']     	  = $sectorDes;
		$newValArray['scDomestic'] 	  = $sectorDomestic;
		
		return ($newValArray);
	}//end function
	
	function insertSector()
	{
		$data 		  = array('nUserId' => $this->session->userdata('AbsAdmin'),'vRegCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
							  'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Sector', $data); 
		
		$id			  	 = $this->db->insert_id();

		$secCountry     	 = (@array_filter($this->input->post('ddlCountry')) != '')?$this->input->post('ddlCountry'):@split("[,]",$this->session->userdata('ddlCountry'));
		$secSector 	 = (@array_filter($this->input->post('txtSector')) != '')?$this->input->post('txtSector'):@split("[,]",$this->session->userdata('txtSector'));
		$secDes  	 = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):@split("[,]",$this->session->userdata('txtDes'));
		$secDomestic = (@array_filter($this->input->post('chkDomestic')) != '')?$this->input->post('chkDomestic'):@split("[,]",$this->session->userdata('chkDomestic'));
		
	    //insert into budget details
		foreach($secCountry as $key => $aitem)
		{
			$chkDomesticVal = $key+1;
			$chkDomesticVal = 'chkDomestic'.$chkDomesticVal;
			$domesticVal    = ($this->input->post($chkDomesticVal) != '')?$this->input->post($chkDomesticVal):'0';
			
			$data 	         = array('nSector' => $id,'nCountry' => $aitem, 'vSector' => $secSector[$key],																                               'vName' => $secDes[$key], 'vDomestic' => $domesticVal); 
			$this->db->insert('{PRE}SectorMasterDetails', $data);
		}//end foreach	

		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("sector/editSector/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("sector/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateSector()
	{
		$data 		  = array('vRegCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
                              'nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nSecId',$this->input->post('nId'));
		$this->db->update('{PRE}Sector', $data); 

		$id			 = $this->input->post('nId');
		$secCountry  = (@array_filter($this->input->post('ddlCountry')) != '')?$this->input->post('ddlCountry'):0;
		$secSector 	 = (@array_filter($this->input->post('txtSector')) != '')?$this->input->post('txtSector'):0;
		$secDes  	 = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
		$secDomestic = (@array_filter($this->input->post('chkDomestic')) != '')?$this->input->post('chkDomestic'):0;
		$chkSecID	 = $this->input->post('idArray');

	    //insert into Account head accounts table
		foreach($secCountry as $key => $aitem)
		{
			$chkDomesticVal = $key+1;
			$chkDomesticVal = 'chkDomestic'.$chkDomesticVal;
			$domesticVal    = ($this->input->post($chkDomesticVal) != '')?$this->input->post($chkDomesticVal):'0';
			
			//checking already exists if yes updated
			if(intval($chkSecID[$key]) != 0)
			{
				$data 	= array('nSector' => $id,'nCountry' => $aitem,'vSector' => $secSector[$key], 
				                'vName' => $secDes[$key],'vDomestic' => $domesticVal);
				$this->db->where('nSecDId',$chkSecID[$key]);
				$this->db->update('{PRE}SectorMasterDetails', $data); 
			}//end if
			else
			{//if new insert into db
				$data 	= array('nSector' => $id,'nCountry' => $aitem, 'vSector' => $secSector[$key], 
								'vName' => $secDes[$key],'vDomestic' => $this->input->post($chkDomesticVal));
				$this->db->insert('{PRE}SectorMasterDetails', $data);
			}//end else
		}//end foreach
		
		$msgVal = 'edit_suc';
		redirect(site_url("sector/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function
	
	function deleteSector()
	{
		$this->db->delete('{PRE}SectorDetails', array('nSector' => $this->uri->segment(4)));
		$this->db->delete('{PRE}Sector', array('nSecId' => $this->uri->segment(4)));
		redirect(site_url("sector/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllSector()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nSecId',$val);
					$this->db->update('{PRE}Sector', $data); 
				}//end foreach
				redirect(site_url("sector/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nSecId',$val);
					$this->db->update('{PRE}Sector', $data); 
				}//end foreach
				redirect(site_url("sector/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}SectorDetails', array('nSector' => $val));
					$this->db->delete('{PRE}Sector', array('nSecId' => $val));
				}//end foreach
				redirect(site_url("sector/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function
	
	function getActiveCountry()
	{
		$countryGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
		$countryGroupRow = array();
		
		if ($countryGroup->num_rows() > 0)
		{
			$countryGroupRow[0] = SELECT;
			foreach ($countryGroup->result() as $key=>$row)
			{
				$countryGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		} //end if
		else
		{
			$countryGroupRow[0] = NOVALUE;
		}
			
		return ($countryGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($sectorid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT s.vRegCode,s.vName as secName,c.vName as countryName,sd.vSector,
												sd.vName, sd.vDomestic FROM {PRE}Sector s 
													LEFT JOIN {PRE}SectorMasterDetails sd ON s.nSecId = sd.nSector
												LEFT JOIN {PRE}Country c ON sd.nCountry = c.nId 
												WHERE s.nSecId = '".$sectorid."' AND c.nParentId = '0'");
		//single fetch
		$commVal  = $viewAccessList->row();	

		$content .= '<table class="reportForm">
						<tr>
							<td colspan="4"><strong>'.SECTOR.'</strong></td>
						</tr>
						<tr>
						    <td><b>'.SECTORCODE.' :&nbsp;&nbsp;</b>'.$commVal->vRegCode.'</td>
							<td colspan="3"><b>'.SECTORNAME.' :&nbsp;&nbsp;</b>'.$commVal->secName.'</td>
						</tr>
						<tr>
							<td><b>'.SECCOUNTRY.'</b></td>
							<td><b>'.SECTOR.'</b></td>
							<td><b>'.SECTORDES.'</b></td>
							<td><b>'.DOMESTIC.'</b></td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$content .= '<tr>
				<td>'.$row->countryName.'</td>
				<td>'.$row->vSector.'</td>
				<td>'.$row->vName.'</td>
				<td>'.($row->vDomestic == '1'?'Yes':'No').'</td>
			</tr>';
		}//end foreach
		
		$content .= '</table>';
	    return($content);
	}//end function
 
	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}SectorDetails', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlBankbookFilterQuery = "SELECT * FROM {PRE}Sector ".$extraCheck." ORDER BY vName
									LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlBankbookFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlBankbookFilterQuery);
		
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
		$tabCount = $this->db->query("SELECT * FROM {PRE}Sector ".$extraCheck." ORDER BY vName");
		return ($tabCount->num_rows());
	}//end function

}//end class
?>