<?php
class Company_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getCorresCompany()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Company");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateCompany($data,$dataEdit)
	{
		$data 	= array('vTitle' => $this->input->post('txtTitle'),'vCode' => $this->input->post('txtCode'),
						'vAddress' => $this->input->post('txtAddress'),'vAddress2' => $this->input->post('txtAddress2'),
						'nCountry' => $this->input->post('ddlCountry'),
						'nDivision' => $this->input->post('ddlDivision'),'nLocation' => $this->input->post('ddlLocation'),
						'vPhone' => $this->input->post('txtPhone'),'vFax' => $this->input->post('txtFax'),
						'vEmail' => $this->input->post('txtEmail'),'nCurrency' => $this->input->post('ddlCurrency'),
						'vWeb' => $this->input->post('txtWeb'),'dFromDate' => $this->input->post('txtFromDate'),
						'dToDate' => $this->input->post('txtToDate'),'vActive' => $this->input->post('chkActive'));
		$dataEdit = array_merge((array)$data, (array)$dataEdit);	
		$this->db->where('nId',$this->txtId);
		$this->db->update('{PRE}Company', $dataEdit); 
		$msgVal = 'edit_suc';
		redirect(site_url("company/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function getActiveCountry()
	{
		$typeGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0' 
											ORDER BY nId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 

	function getActiveDivision()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1' ORDER BY nId DESC");
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

	function getLocation()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function

	function getActiveLocation()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' ORDER BY nId DESC");
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
	
	function getActiveCurrency()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' 
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

	function filterlocation($division)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' ORDER BY vTitle ASC");
		
		if ($adminGroupList->num_rows() > 0)
		{
			$adminGroupRow[0] = SELECT;
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[$row->nId] = $row->vTitle;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
}//end class
?>