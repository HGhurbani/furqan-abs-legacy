<?php
class Profile_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	function getActiveCountry()
	{
		$countryGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'");
		$countryGroupRow = array();
		
		//$countryGroupRow[0] = 'Select';
		if ($countryGroup->num_rows() > 0)
		{
			foreach ($countryGroup->result() as $key=>$row)
			{
				$countryGroupRow[$row->nId] = $row->vName;
			}//end for each
		} //end if
		else
		{
			$countryGroupRow[0] = NOVALU;
		}
		$data['list_group_value'] = $countryGroupRow;
		return $data['list_group_value'];
	}//end function 
	
	function getActiveState()
	{
		$countryGroup = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$this->ddlCountry."'");
		$countryGroupRow = array();
		
		//$countryGroupRow[0] = 'Select';
		if ($countryGroup->num_rows() > 0)
		{
			foreach ($countryGroup->result() as $key=>$row)
			{
				$countryGroupRow[$row->nId] = $row->vName;
			}//end for each
		} //end if
		else
		{
			$countryGroupRow[0] = NOVALU;
		}
		$data['list_state_value'] = $countryGroupRow;
		return $data['list_state_value'];
	}//end function 
	
	function insert_temp_profile()
	{
		$fetchTitle  				= $this->db->query("SELECT * FROM {PRE}".$this->_TableName." WHERE nId='".$this->session->userdata['StudentUser']."' ");
		$showVal	      			= $fetchTitle->row();
		$data 						= array('nUserId' => $this->session->userdata('AbsAdmin'),'nTmpID'=>$this->input->post('txtId'),
                                            'vName' => $this->input->post('txtName'),
											'vCAddress' => $this->input->post('txtAddress'),'vType' =>$this->session->userdata('userType'),
											'vEmail' => $this->input->post('txtEmail'),'vPhone' => $this->input->post('txtPhone'),
											'vMobile' => $this->input->post('txtMobile'),'vAddress' => $this->input->post('txtAddress'),
											'nCountry' => $this->input->post('ddlCountry'),'nState' => $this->input->post('ddlState'));
		$this->db->insert('{PRE}Temp_Profile', $data); 
		$msgVal = 'edit_suc';
		echo 'You are waiting for the Approval.';
		//redirect(site_url("edit_profile/success/".$msgVal));
	}//end function
	 
	function filterstate()
	{
		//active countries
		$stateArr    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$this->input->post('ddlCountry')."'");
		$stateArrRow = array();
		
		$stateArrRow[0] = '--Select--';
		if ($stateArr->num_rows() > 0)
		{
			foreach ($stateArr->result() as $key=>$row)
			{
				$stateArrRow[$row->nId] = $row->vName;
			}//end for each
		} //end if
		else
		{
			$stateArrRow[0] = NOVALU;
		}//end else
			
		$data['list_state_value'] = $stateArrRow;
		return $data['list_state_value'];
    }//end function
	
}//end class