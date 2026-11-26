<?php
class Finance_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Finance Main Group********************************/

	function getAllMaingroups()
	{
		$adminGroupRow          = array();
		$sqlMaingroupQuery      = "SELECT * FROM {PRE}FinanceModule WHERE nParent ='0'
									ORDER BY vTitle ASC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlMaingroupQuery;	
		$adminGroupList 		= $this->db->query($sqlMaingroupQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function
	
	function getCorresMaingroups()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nId='".$this->catId."' AND vLevel='1'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function
	 
	function getCorresAccgroups()
	{
		$fetchTitle1 	  = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent='".$this->catId."' AND vLevel='2'");
		$showVal1	      = $fetchTitle1->row();
		return $showVal1;
	}//end function
	 
	function getCorresAccSubgroups()
	{
		$fetchTitle2 	  = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent='".$this->catId."' AND vLevel='3'");
		$showVal2	      = $fetchTitle2->row();
		return $showVal2;
	}//end function

    function getLocation()
    {
        $locGroupRow    = array();
        $locGroupRow[0] = SELECT;
        return($locGroupRow);
    }//end function

    function insertMaingroup()
    {

		$data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => $this->input->post('ddlMaingroup'),'vActive' => $this->input->post('chkActive'),'vLevel' => '1');
		$this->db->insert('{PRE}FinanceModule', $data);
		$groupId = $this->db->insert_id();
		
		$data 	 = array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $groupId,'vTitle' => $this->input->post('txtAccGroupTitle'),
						 'vCode' => $this->input->post('txtCode'),'vActive' => $this->input->post('chkActive'),
						 'vLevel' => '2');
		$this->db->insert('{PRE}FinanceModule', $data);
        $msgVal  = 'add_suc';
        if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
        {
            redirect(site_url("main_group/editMaingroups/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if
        if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
        {
            redirect(site_url("main_group/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end if
    }//end function
	
	function updateMaingroup()
	{
		$data 	    = array('vTitle' => $this->input->post('ddlMaingroup'),'vActive' => $this->input->post('chkActive'),
            'nUserId' => $this->session->userdata('AbsAdmin'),'vLevel' => '1');
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}FinanceModule', $data); 
		$groupId    = $this->input->post('nId');
		
		$data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $groupId,'vTitle' => $this->input->post('txtAccGroupTitle'),
		                    'vCode' => $this->input->post('txtCode'),'vActive' => $this->input->post('chkActive'),'vLevel' => '2');
		$this->db->where('nParent',$this->input->post('nId'));
		$this->db->update('{PRE}FinanceModule', $data); 
		
		$msgVal = 'edit_suc';
		redirect(site_url("main_group/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteMaingroup()
	{
		$sqDelQuery = "SELECT * FROM {PRE}FinanceModule WHERE nParent='".$this->uri->segment(4)."'";
		$delList 	= $this->db->query($sqDelQuery);
		if ($delList->num_rows() > 0)
		{
			foreach ($delList->result() as $key=>$row)
			{
				$delRow = $row->nId;
                $this->db->delete('{PRE}FinanceModule', array('nParent' => $delRow));
			}//end for each
		} //end if

        $this->db->delete('{PRE}FinanceModule', array('nParent' => $this->uri->segment(4)));
        $this->db->delete('{PRE}FinanceModule', array('nId' => $this->uri->segment(4)));
        redirect(site_url("main_group/showlist/".$this->uri->segment(3).'/del_suc'));
    }//end function
	
	function deleteAllMaingroup()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}FinanceModule', $data); 
				}//end foreach
				redirect(site_url("main_group/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}FinanceModule', $data); 
				}//end foreach
				redirect(site_url("main_group/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
                    $sqDelQuery = "SELECT * FROM {PRE}FinanceModule WHERE nParent='".$val."'";
                    $delList 	= $this->db->query($sqDelQuery);
                    if ($delList->num_rows() > 0)
                    {
                        foreach ($delList->result() as $key=>$row)
                        {
                            $delRow = $row->nId;
                            $this->db->delete('{PRE}FinanceModule', array('nParent' => $delRow));
                        }//end for each
                    } //end if
                    $this->db->delete('{PRE}FinanceModule', array('nParent' => $val));
                    $this->db->delete('{PRE}FinanceModule', array('nId' => $val));
				}//end foreach
				redirect(site_url("main_group/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterMaingroup($extraCheck)
	{
		$adminGroupRow           = array();
		$sqlMaingroupFilterQuery = "SELECT * FROM {PRE}FinanceModule WHERE nParent = '0' ".$extraCheck." ORDER BY vTitle ASC 
										LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query']  = $sqlMaingroupFilterQuery;	
		$adminGroupList 		 = $this->db->query($sqlMaingroupFilterQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
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

    function filterLocation($division)
    {
        $locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."' 
										ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) {
            $locGroupRow[0] = SELECT;
            foreach ($locGroup->result() as $key => $row) {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle);
            }
            //end for each
        } //end if
        else {
            $locGroupRow[0] = NOVALUE;
        }
        //end else
        return ($locGroupRow);
    }//end function

	//fetching single details
	function showSubDetails($subId)
	{
		$viewAccessRow   = array();
		
		$mainGroupName   = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$subId."'"); 
			
		switch($mainGroupName)
	    {
			  case "1" :
				$mainGroup = '1 - Assets';
			  break;
	
			  case "2" :
				$mainGroup = '2 - Liability';
			  break;
	
			  case "3" :
				$mainGroup = '3 - Income';
			  break;
	
			  case "4" :
				$mainGroup = '4 - Expense';
			  break;
	  	}//end switch	
									
		$content .= '<table>
						<tr>
							<td>
					 			<label><b>'.MAINGROUPNAME.' :</b>&nbsp;'.$mainGroup.'</label>
							</td>
						</tr>
					</table><br />';
					
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent = '".$subId."' ORDER BY nId DESC");
		
		$content .= '<table>
						<tr>
							<td colspan="2"><strong>'.SUBGROUP.'</strong></td>
						</tr>
						<tr>
							<td>'.ACCGROUPCODE.'</td>
							<td>'.ACCGROUPNAME.'</td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$content .= '<tr>
				<td>'.$row->vCode.'</td>
				<td>'.$row->vTitle.'</td>
			</tr>';
		}//end foreach
		
		$content .= '</table>';
		return($content);
	}//end function
	
	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}FinanceModule', array('nId' => $ID));
	}//end function

/*********************Finance Account Grouping******************************/

	function getAllAccgrouping($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent!='0' ".$extraCheck."
											 AND vLevel = '2' ORDER BY vTitle ASC
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
	
	function getCorresAccgroup()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertAccgrouping()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $this->input->post('ddlType'),'vTitle' => $this->input->post('txtTitle'),
		                'vCode' => $this->input->post('txtCode'),'vActive' => $this->input->post('chkActive'),'vLevel' => '2');
		$this->db->insert('{PRE}FinanceModule', $data); 
		$id	        = $this->db->insert_id();
		$accCode    =  (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):@split("[,]",$this->session->userdata('chkAccCode'));
		$accName    =  (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):@split("[,]",$this->session->userdata('chkAccTitle'));

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
			$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $id,'vCode' => $aitem,
							'vTitle' => $accName[$key],'vActive' => $this->input->post('chkActive'),'vLevel' => '3');
			$this->db->insert('{PRE}FinanceModule', $data);
		}//end foreach

		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("account_grouping/editAccgrouping/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("account_grouping/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateAccgrouping()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $this->input->post('ddlType'),'vTitle' => $this->input->post('txtTitle'),
		                'vCode' => $this->input->post('txtCode'),'vActive' => $this->input->post('chkActive'),'vLevel' => '2');
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}FinanceModule', $data); 
		
		$id	        = $this->input->post('nId');
		
		$accCode    =  (@array_filter($this->input->post('txtAccCode')) != '')?$this->input->post('txtAccCode'):0;
		$accName    =  (@array_filter($this->input->post('txtAccTitle')) != '')?$this->input->post('txtAccTitle'):0;
		$chkAccID	= $this->input->post('idArray');

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
			//checking already exists if yes updated
			if(intval($chkAccID[$key]) != 0)
			{
				$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $id,'vCode' => $aitem,'vLevel' => '3',
								'vTitle' => $accName[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}FinanceModule', $data); 
			}//end if
			else
			{//if new insert into db
				$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'nParent' => $id,'vCode' => $aitem,'vLevel' => '3',
								'vTitle' => $accName[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}FinanceModule', $data);
			}//end else
		}//end foreach

		$msgVal = 'edit_suc';
		redirect(site_url("account_grouping/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function
	
	function deleteAccgrouping()
	{
		$transactionId	    =   $this->common_model->get_field_name('FinanceModule','nParent'," WHERE nId='".$this->uri->segment(4)."'");
		$this->db->delete('{PRE}FinanceModule', array('nId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}FinanceModule', array('nParent' => $this->uri->segment(4))); 
		redirect(site_url("account_grouping/showlist/".$this->uri->segment(3).'/del_suc/'.$transactionId));	
	}//end function 
	
	function deleteAllAccgrouping()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}FinanceModule', $data); 
				}//end foreach
				redirect(site_url("account_grouping/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}FinanceModule', $data); 
				}//end foreach
				redirect(site_url("account_grouping/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$transactionId	    =   $this->common_model->get_field_name('FinanceModule','nParent'," WHERE nId='".$val."'");
					$this->db->delete('{PRE}FinanceModule', array('nId' => $val)); 
					$this->db->delete('{PRE}FinanceModule', array('nParent' => $val));
				}//end foreach
				redirect(site_url("account_grouping/showlist/".$this->input->post('nCId').'/del_suc/'.$transactionId));	
			break;
		}//end switch
	}//end function 
	
	function getSelectMaingroups()
	{
		$typeGroupRow = array("0" => "Select","1" => "1 Asset","2" => "2 Liability","3" => "3 Income","4" => "4 Expense",);
		return ($typeGroupRow);
	}//end function

    function getSelectActiveSub()
    {
        $typeGroupRow    = array("0" => "No", "1" => "Yes");
        return ($typeGroupRow);
    }//end function
	 
	function getSelectSubgroups($nPId)
	{
		$typeGroup    = $this->db->query("SELECT nId,nParent,vTitle FROM {PRE}FinanceModule WHERE vActive = '1' AND 
											nParent = '".$nPId."' AND vLevel = '2' ORDER BY vTitle ASC");
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
	
	function getActiveMaingroups()
	{
		//active transaction type
		$typeList    = $this->db->query("SELECT nId,vTitle FROM {PRE}FinanceModule WHERE vActive = '1' AND nParent = '0' 
											ORDER BY vTitle ASC");
		$typeListRow = array();
		
		if ($typeList->num_rows() > 0)
		{
			$typeListRow[0] = ALL;
			
			foreach ($typeList->result() as $key=>$row)
			{
				$typeListRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		
		else
		{
			$typeListRow[0] =NOVALU;
		}//end else
		return ($typeListRow);
	}//end function 
	
	function getFilterAccgrouping($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}FinanceModule WHERE nParent!='0' ".$extraCheck."
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
	
 	//get account head accounts details
	function getAcAccounts($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vCode FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");
		//echo $this->db->last_query(); 
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vCode;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

 	//get sub group details
	function getAcAccountsTitle($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vTitle FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vTitle;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function
	
	//get sub group details
	function getAcAccountsActive($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vActive FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->vActive;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

    //get Division  details
    function getAcAccountsDivision($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nDivision FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");
        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nDivision;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nLocation FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[] = $row->nLocation;
            }//end for each
        }//end if

        return (array_filter($userTransRow));
    }//end function

	//get id
	function getAcAccountsID($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT nId FROM {PRE}FinanceModule WHERE nParent = '".$nId."'");
		
		if ($userTrans->num_rows() > 0)
		{
			foreach ($userTrans->result() as $key=>$row)
			{
				$userTransRow[] = $row->nId;
			}//end for each
		}//end if
		
		return (array_filter($userTransRow));
	}//end function

	//fetching single details
	function showDetails($modid)
	{
		$viewAccessRow   = array();
		//$modid           = $this->uri->segment(4);
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}FinanceModule 
												WHERE nParent = '".$modid."' AND vLevel='3' ORDER BY nId DESC");
		$content .= '<table border="0" cellpadding="5" cellspacing="1" bgcolor="#000000" width="100%" class="reportForm">
						<thead bgcolor="#FFFFFF">
							<th>'.ACCCODENAME.'</th>
							<th>'.TITLE.'</th>
						</thead>';
		$content .= '<tr bgcolor="#FFFFFF">
						<td>'.$row->vCode.'</td>
						<td>'.$row->vTitle.'</td>
					 </tr>
					</table>';
	    return($content);
	}//end function
	
}//end class
?>