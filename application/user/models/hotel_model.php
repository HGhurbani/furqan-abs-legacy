<?php
class Hotel_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getHotel($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT h.*,c.vName as country, s.vName as service FROM {PRE}Hotel h 
												LEFT JOIN {PRE}ServiceType s ON h.nService = s.nSId
													LEFT JOIN {PRE}Country c ON h.nCountry = c.nId ".$extraCheck." 
											 	ORDER BY vName DESC
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
	
	function getCorresHotel()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Hotel WHERE nHId='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertHotel()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'),
						'nCity' => $this->input->post('ddlCity'), 'nCountry' => $this->input->post('ddlCountry'),
						'nService' => $this->input->post('ddlService'), 'vPhone' => $this->input->post('txtPhone'), 
						'vFax' => $this->input->post('txtFax'));
		$this->db->insert('{PRE}Hotel', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("hotel/editHotel/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("hotel/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCountry'));		
		}//end if
	}//end function
	
	function updateHotel()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'),
						'nCity' => $this->input->post('ddlCity'), 'nCountry' => $this->input->post('ddlCountry'),
						'nService' => $this->input->post('ddlService'), 'vPhone' => $this->input->post('txtPhone'), 
						'vFax' => $this->input->post('txtFax'));
		$this->db->where('nHId',$this->input->post('nId'));//var_dump($this->input->post('nId'));
		$this->db->update('{PRE}Hotel', $data); //var_dump($data);
		$msgVal = 'edit_suc';
		redirect(site_url("hotel/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCountry'));	
	}//end function
	
	function deleteHotel()
	{
		$this->db->delete('{PRE}Hotel', array('nHId' => $this->uri->segment(4))); 
		redirect(site_url("hotel/showlist/".$this->uri->segment(3).'/del_suc/'.$countryId));	
	}//end function 
	
	function deleteHotelMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nHId',$val);
					$this->db->update('{PRE}Hotel', $data); 
				}//end foreach
				redirect(site_url("hotel/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nHId',$val);
					$this->db->update('{PRE}Hotel', $data); 
				}//end foreach
				redirect(site_url("hotel/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Hotel', array('nHId' => $val)); 
				}//end foreach
				redirect(site_url("hotel/showlist/".$this->input->post('nCId').'/del_suc/'.$countryId));	
			break;
		}//end switch
	}//end function 
	
	function getDropCountry()
	{
		$countryGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
		$countryGroupRow = array();
		
		if ($countryGroup->num_rows() > 0)
		{
			$countryGroupRow[0] = SELECT;
			foreach ($countryGroup->result() as $key => $row)
			{
				$countryGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$countryGroupRow[0] = NOVALUE;
		}//end else
		return ($countryGroupRow);
	}//end function 
	
	function getDropCity()
	{
		$cityGroupRow = array("0" => SELECT);
		return ($cityGroupRow);
	}//end function 
	
	function getDropAllCity()
	{
		$citGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId != '0'");
		$citGroupRow = array();
		
		if ($citGroup->num_rows() > 0)
		{
			$citGroupRow[0] = SELECT;
			foreach ($citGroup->result() as $key => $row)
			{
				$citGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$citGroupRow[0] = NOVALUE;
		}//end else
		return ($citGroupRow);
	}//end function 
	
	function getDropService()
	{
		$serviceGroup    = $this->db->query("SELECT nSId,vName FROM {PRE}ServiceType WHERE vActive = '1' ORDER BY vName DESC");
		$serviceGroupRow = array();
		
		if ($serviceGroup->num_rows() > 0)
		{
//			$serviceGroupRow[0] = SELECT;
			foreach ($serviceGroup->result() as $key => $row)
			{
				$serviceGroupRow[$row->nSId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$serviceGroupRow[0] = NOVALUE;
		}//end else
		return ($serviceGroupRow);
	}//end function 
	
	function getDropCountrySearch()
	{
		//active countries
		$countryList    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
		$countryListRow = array();
		
		if ($countryList->num_rows() > 0)
		{
			$countryListRow[0] = ALL;
			
			foreach ($countryList->result() as $key=>$row)
			{
				$countryListRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		} //end if
		else
		{
			$countryListRow[0] = NOVALUE;
		}//end else
		return ($countryListRow);
	}//end function 
	
	function getFilterHotel($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT h.*,c.vName as country,s.vName as service FROM {PRE}Hotel h 
											 LEFT JOIN {PRE}ServiceType s ON h.nService = s.nSId
											LEFT JOIN {PRE}Country c ON h.nCountry = c.nId ".$extraCheck." 
											 ORDER BY h.vName DESC LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountFilterHotel($extraCheck)
	{
		$tabCount = $this->db->query("SELECT h.*,c.vName as country,s.vName as service FROM {PRE}Hotel h 
										LEFT JOIN {PRE}ServiceType s ON h.nService = s.nSId
									  LEFT JOIN {PRE}Country c ON h.nCountry = c.nId ".$extraCheck." 
										ORDER BY h.vName DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCityList($countyId)
	{
		$cityGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$countyId."' ORDER BY vName");
		$cityGroupRow = array();
		
		if ($cityGroup->num_rows() > 0)
		{
			$cityGroupRow[0] = SELECT;
			foreach ($cityGroup->result() as $key => $row)
			{
				$cityGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$cityGroupRow[0] = NOVALUE;
		}//end else
		return ($cityGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($hotelId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT h.*,h.vName as hotelName,st.vName as servName FROM {PRE}Hotel h 
												LEFT JOIN {PRE}ServiceType st ON h.nService = st.nSId
											 WHERE h.nHId = '".$hotelId."' GROUP BY nHId ORDER BY h.nHId DESC");
		//single fetch
		$commVal  		 = $viewAccessList->row();	

		$content .= '<table class="reportForm">
						<tr>
							<td>
								 <label><b>'.COUNTRYNAME.' :</b>&nbsp;'.$this->common_model->get_field_name('Country','vName'," WHERE nId = '".$commVal->nCountry."' ").'</label>
							</td>
						</tr>
						<tr>	
							<td>
					 			<label><b>'.CITYNAME.' :</b>&nbsp;'.$this->common_model->get_field_name('Country','vName'," WHERE nId = '".$commVal->nCity."' ").'</label>
							</td>
						</tr>
						<tr>	
							<td>
					 			<label><b>'.SERVICETYPE.' :</b>&nbsp;'.$commVal->servName.'</label>
							</td>
						</tr>
						
						<tr>	
							<td>
					 			<label><b>'.HOTELNAME.' :</b>&nbsp;'.$commVal->hotelName.'</label>
							</td>
						</tr>
						<tr>	
							<td>
					 			<label><b>'.PHONE.' :</b>&nbsp;'.$commVal->vPhone.'</label>
							</td>
						</tr>
						<tr>	
							<td>
					 			<label><b>'.AGENTFAX.' :</b>&nbsp;'.$commVal->vFax.'</label>
							</td>
						</tr>
						
						<tr>	
							<td>
					 			<label><b>'.ADDRESS.' :</b>&nbsp;'.$commVal->vAddress.'</label>
							</td>
						</tr>						
						</table>';
		
	    return($content);
	}//end function
	 
}//end class
?>
