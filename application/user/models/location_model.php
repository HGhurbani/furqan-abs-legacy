<?php
class Location_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Company Location********************************/

	function getAllLocation()
	{
		$adminGroupRow  		= array();
		$sqlLocationQuery   	= "SELECT l.*,l.nId as locId,d.nId as divId,l.vCode as locCode,l.vTitle as locName,d.vTitle as divName,
									l.vActive as locActive,d.vActive as divActive
								   FROM {PRE}Location l LEFT JOIN {PRE}Division d ON l.nDivision = d.nId
									".$extraCheck." ORDER BY l.nPosition DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlLocationQuery;	
		$adminGroupList 		= $this->db->query($sqlLocationQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function 
	
	function insertLocation($dataEdit)
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => mysql_real_escape_string($this->input->post('ddlDivision')),
						'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vCode' => mysql_real_escape_string($this->input->post('txtCode')),
						'nCurrency' => $this->input->post('ddlCurrency'),
						'vAddress' => mysql_real_escape_string($this->input->post('txtLocaddr')),
						'vPhone' => mysql_real_escape_string($this->input->post('txtLocphone')),
						'vMobile' => mysql_real_escape_string($this->input->post('txtLocmob')),
						'vEmail' => mysql_real_escape_string($this->input->post('txtEmail')),
						'vWeb' => mysql_real_escape_string($this->input->post('txtWeb')),
						'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Location'));
		$dataEdit = array_merge((array)$data, (array)$dataEdit);

		$this->db->insert('{PRE}Location', $dataEdit); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("location/editLocation/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("location/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlDivision'));		
		}//end if
	}//end function
	
	function getCorresLocation()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Location WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateLocation($dataEdit)
	{
		$data 	= array('nDivision' => mysql_real_escape_string($this->input->post('ddlDivision')),
						'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vCode' => mysql_real_escape_string($this->input->post('txtCode')),
						'nCurrency' => $this->input->post('ddlCurrency'), 'nUserId' => $this->session->userdata('AbsAdmin'),
						'vAddress' => mysql_real_escape_string($this->input->post('txtLocaddr')),
						'vPhone' => mysql_real_escape_string($this->input->post('txtLocphone')),
						'vMobile' => mysql_real_escape_string($this->input->post('txtLocmob')),
						'vEmail' => mysql_real_escape_string($this->input->post('txtEmail')),
						'vWeb' => mysql_real_escape_string($this->input->post('txtWeb')),
						'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Location'));
        $dataEdit = array_merge((array)$data, (array)$dataEdit);
						
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Location', $dataEdit); 
		$msgVal = 'edit_suc';
		redirect(site_url("location/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlDivision'));					
	}//end function 
	
	function deleteLocation()
	{
	    $locId  = $this->common_model->get_field_name('Location','nDivision'," WHERE nId='".$this->uri->segment(4)."'");
		$rowImg	= $this->common_model->get_field_name('Location','vImg'," WHERE nId ='".$this->uri->segment(4)."'");
		@unlink('./upload/location/'.$rowImg);
		
		$this->db->delete('{PRE}Location', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("location/showlist/".$this->uri->segment(3).'/del_suc/'.$locId));	
	}//end function
	
	function deleteAllLocation()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Location', $data); 
				}//end foreach
				redirect(site_url("location/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Location', $data); 
				}//end foreach
				redirect(site_url("location/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
				    $locId  = $this->common_model->get_field_name('Location','nDivision'," WHERE nId='".$val."'");
					$rowImg	= $this->common_model->get_field_name('Location','vImg'," WHERE nId ='".$val."'");
					@unlink('./upload/location/'.$rowImg);
					
					$this->db->delete('{PRE}Location', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("location/showlist/".$this->input->post('nCId').'/del_suc/'.$locId));	
			break;
		}//end switch
	}//end function 
	
	function getFilterLocation($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlLocationFilterQuery = "SELECT l.*,l.nId as locId,d.nId as divId,l.vCode as locCode,l.vTitle as locName,d.vTitle as divName,
											 l.vActive as locActive,d.vActive as divActive
											FROM {PRE}Location l LEFT JOIN {PRE}Division d ON l.nDivision = d.nId
											 ".$extraCheck." ORDER BY l.nPosition DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlLocationFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlLocationFilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterLocation($extraCheck)
	{
		$tabCount = $this->db->query("SELECT l.*,l.nId as locId,d.nId as divId,l.vCode as locCode,l.vTitle as locName,d.vTitle as divName,
											 l.vActive as locActive,d.vActive as divActive
											FROM {PRE}Location l LEFT JOIN {PRE}Division d ON l.nDivision = d.nId
											 ".$extraCheck." ORDER BY l.nPosition DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getLocationAdmin()
	{
		$typeGroupRow = array("0" => SELECT,"General" => "General");
		return ($typeGroupRow);
	}//end function 
	
	function getActiveDivision()
	{
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1' ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vDivCode).')';
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return ($locGroupRow);
	}//end function 
	
	function getAllDivision()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1' 
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

	//fetching single details
	function showDetails($locId)
	{
		$adminGroupList     = $this->db->query("SELECT l.*,l.nId as locId,d.nId as divId,l.vCode as locCode,l.vTitle as locName,d.vTitle as divName,
													l.vActive as locActive,d.vActive as divActive
								   				FROM {PRE}Location l LEFT JOIN {PRE}Division d ON l.nDivision = d.nId WHERE l.nId = '".$locId."'"); 
		$showVal	        = $adminGroupList->row();
		$content            = '<table class="reportForm">
			<tr>
				<td><strong>'.DIVISIONNAME.' :</strong></td>
				<td>'.$showVal->divName.'</td>
				
				<td><strong>'.CODE.' :</strong></td>
				<td>'.$showVal->locCode.'</td>
				
				<td><strong>'.LOCATIONNAME.' :</strong></td>
				<td>'.$showVal->locName.'</td>
			</tr>
			
			<tr>
				<td><strong>'.LOCATIONADDR.' :</strong></td>
				<td>'.$showVal->vAddress.'</td>
				
				<td><strong>'.LOCATIONPHONE.' :</strong></td>
				<td>'.$showVal->vPhone.'</td>
				
				<td><strong>'.MOBILE.' :</strong></td>
				<td>'.$showVal->vMobile.'</td>
			</tr>
			
			<tr>
				<td><strong>'.EMAIL.' :</strong></td>
				<td>'.$showVal->vEmail.'</td>
				
				<td><strong>'.WEB.' :</strong></td>
				<td colspan="3">'.$showVal->vWeb.'</td>
			</tr>
			
			<tr>
				<td><strong>'.YEARDATE.' :</strong></td>
				<td>'.$showVal->dYearDate.'</td>
				
				<td><strong>'.YEARTO.' :</strong></td>
				<td colspan="3">'.$showVal->dYearTo.'</td>
			</tr>
			
			<tr>
				<td><strong>'.IMAGE.' :</strong></td>
				<td colspan="5"><img src="'.base_url().'/upload/location/'.$showVal->vImg.'" width="60" height="60"/></td>
			</tr>
		 </table>';

		return($content);
	}//end function

}//end class
?>