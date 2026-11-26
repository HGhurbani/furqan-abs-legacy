<?php
class Country_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	/*********************City******************************/
	function getCity($extraCheck)
	{		
		//active groups
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId!='0' ".$extraCheck." 
											 ORDER BY nId DESC
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
	
	function getCorresCity()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Country WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertCity()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'),
						'vShortName' => $this->input->post('txtTitle2'), 'nParentId' => $this->input->post('ddlCategory'));
		$this->db->insert('{PRE}Country', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("city/editCity/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("city/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCountry'));		
		}//end if
	}//end function
	
	function updateCity()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'),
						'vShortName' => $this->input->post('txtTitle2'), 'nParentId' => $this->input->post('ddlCategory'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Country', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("city/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCountry'));	
	}//end function
	
	function deleteCity()
	{
		$countryId	    =   $this->common_model->get_field_name('Country','nParentId'," WHERE nId='".$this->uri->segment(4)."'");
		$this->db->delete('{PRE}Country', array('nId' => $this->uri->segment(4))); 
		
		redirect(site_url("city/showlist/".$this->uri->segment(3).'/del_suc/'.$countryId));	
	}//end function 
	
	function deleteCityMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Country', $data); 
				}//end foreach
				redirect(site_url("city/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Country', $data); 
				}//end foreach
				redirect(site_url("city/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$countryId	    =   $this->common_model->get_field_name('Country','nParentId'," WHERE nId='".$val."'");
					$this->db->delete('{PRE}Country', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("city/showlist/".$this->input->post('nCId').'/del_suc/'.$countryId));	
			break;
		}//end switch
	}//end function 
	
	function getDropCountry()
	{
		$staffGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
		$staffGroupRow = array();
		
		if ($staffGroup->num_rows() > 0)
		{
			$staffGroupRow[0] = '--Select--';
			foreach ($staffGroup->result() as $key=>$row)
			{
				$staffGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		} //end if
		else
		{
			$staffGroupRow[0] = '--No Value--';
		}//end else
		return ($staffGroupRow);
	}//end function 
	
	function getDropCountrySearch()
	{
		//active categories
		$countryList = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
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
			$countryListRow[0] =NOVALU;
		}
		return ($countryListRow);
	}//end function 
	
	function getFilterCity($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId != '0' ".$extraCheck."
											 ORDER BY nId DESC
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

	function getCountfilterCity($extraCheck)
	{
		$tabCont = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId != '0' ".$extraCheck." ORDER BY nId DESC ");
		return ($tabCont->num_rows());
	}//end function
	
	/********************country********************************/
	function getAllCountry()
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId ='0'
											 ORDER BY nId DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
			
		$data['list_value']   = $adminGroupRow;
		return $data['list_value'];
	}//end function 
	
	function insertCountry()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vActive' => $this->input->post('chkActive'),
						'vShortName' => $this->input->post('txtTitle2'));
		$this->db->insert('{PRE}Country', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("country/editCountry/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("country/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if

	}//end function
	
	function getCorresCountry()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Country WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateCountry()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'),
						'vShortName' => $this->input->post('txtTitle2'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Country', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("country/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteCountry()
	{
		$this->db->delete('{PRE}Country', array('nId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Country', array('nParentId' => $this->uri->segment(4)));
		redirect(site_url("country/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllCountry()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Country', $data); 
				}//end foreach
				redirect(site_url("country/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Country', $data); 
				}//end foreach
				redirect(site_url("country/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Country', array('nId' => $val)); 
					$this->db->delete('{PRE}Country', array('nParentId' => $val)); 
				}//end foreach
				redirect(site_url("country/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterCountryName($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='0' ".$extraCheck."
											 ORDER BY vName ASC
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
	
}//end class
?>