<?php
class Budget_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		date_default_timezone_set('Asia/Kuwait');
    }
	
/*----------------------Monthly Budget Setup-----------------*/
	
	function getAllMonthlybudget()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlBudgetQuery         = "SELECT * FROM {PRE}Budget 
      WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
      ORDER BY nBId DESC
											 LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlBudgetQuery;	
		$adminGroupList 		= $this->db->query($sqlBudgetQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountMonthlybudget()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}Budget 
        WHERE vLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nBId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresMonthlybudget()
	{
		$fetchTitle = $this->db->query("SELECT b.*,b.nBId as budgetId,bd.*,bd.nId as bdId,aha.*,b.nDivision as mDivision FROM {PRE}Budget b 
										 LEFT JOIN {PRE}BudgetDetails bd ON b.nBId = bd.nBudId
										LEFT JOIN {PRE}AccountHeadAccounts aha ON bd.nAHAId = aha.nId WHERE bd.nBudId = '".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresMonthlybudgetDetails()
	{
		$tsDetailsRow     = array();
		$tsDetailsCode    = array();
		$tsDetailsMode    = array();
		$tsDetailsAmount  = array();
		$newValArray      = array();
		$budDetailID      = array();
		
		$tsDetailsList    = $this->db->query("SELECT bd.nAHAId,aha.vACode,aha.vTitle,bd.vMode,bd.nAmount,bd.nId as dId
												 FROM {PRE}Budget b 
										 	  LEFT JOIN {PRE}BudgetDetails bd ON b.nBId=bd.nBudId
											 	LEFT JOIN {PRE}AccountHeadAccounts aha ON bd.nAHAId=aha.nId 
											  WHERE bd.nBudId ='".$this->catId."'");
		if ($tsDetailsList->num_rows() > 0)
		{
			foreach ($tsDetailsList->result() as $key=>$row)
			{
				$tsDetailsRow[]     = $row->nAHAId;
				$tsDetailsCode[]    = $row->nAHAId;
				$tsDetailsMode[]    = $row->vMode;
				$tsDetailsAmount[]  = $row->nAmount;
                $budDetailID[] 	    = $row->dId;

			}//end for each
		}//end if
		
		$newValArray['acId']        = $tsDetailsRow;
		$newValArray['acCode']      = $tsDetailsCode;
		$newValArray['acMode']      = $tsDetailsMode;
		$newValArray['acAmount']    = $tsDetailsAmount;
		$newValArray['acbudDetID']  = $budDetailID;
		
		return ($newValArray);
	}//end function
	
	function insertMonthlybudget()
	{

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }

		$data 		= array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
							'dDate' => $date1,'vDocNumber' => $this->input->post('txtDocNumber'),'vMonth' => $this->input->post('ddlMonth'),
							'vYear' => $this->input->post('txtBudYear'),'vYearref' => $this->input->post('txtYearref'),
							'vCopyref' => $this->input->post('txtBudCopyref'),'nTotal' => $this->input->post('txtTotalAmount'),
							'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
		$this->db->insert('{PRE}Budget', $data); 
		
		$id			= $this->db->insert_id();

		$accCode    = (@array_filter($this->input->post('ddlAccount')) != '')?$this->input->post('ddlAccount'):0;
		$modeName   = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
		$accAmount  = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;

	    //insert into budget details
		foreach($accCode as $key => $aitem)
		{
			$data 				= array('nBudId' => $id,'nAHAId' => $accCode[$key],'vMode' => $modeName[$key],
									    'nAmount' => $accAmount[$key],'vActive' => $this->input->post('chkActive'));
			$this->db->insert('{PRE}BudgetDetails', $data);
		}//end foreach		
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("monthly_budget/editMonthlybudget/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("monthly_budget/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateMonthlybudget()
	{

    if($this->input->post('txtDate') =='0000-00-00' || $this->input->post('txtDate') == '' || $this->input->post('txtDate') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate')));
    }


		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
					  'dDate' => $date1 ,'vDocNumber' => $this->input->post('txtDocNumber'),'vMonth' => $this->input->post('ddlMonth'),
					  'vYear' => $this->input->post('txtBudYear'),'vYearref' => $this->input->post('txtYearref'),
					  'vCopyref' => $this->input->post('txtBudCopyref'),'nTotal' => $this->input->post('txtTotalAmount'),
					  'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
		$this->db->where('nBId',$this->input->post('nId'));
		$this->db->update('{PRE}Budget', $data); 

		$id			= $this->input->post('nId');
        $accCode    = (@array_filter($this->input->post('ddlAccount')) != '')?$this->input->post('ddlAccount'):0;
        $modeName   = (@array_filter($this->input->post('ddlMode')) != '')?$this->input->post('ddlMode'):0;
        $accAmount  = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;

		$chkAccID	= $this->input->post('idArray');

	    //insert into Account head accounts table
		foreach($accCode as $key => $aitem)
		{
			//checking already exists if yes updated
			if(intval($chkAccID[$key]) != 0)
			{
				$data = array('nBudId' => $id,'nAHAId' => $accCode[$key],'vMode' => $modeName[$key],
							  'nAmount' => $accAmount[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->where('nId',$chkAccID[$key]);
				$this->db->update('{PRE}BudgetDetails', $data); 
			}//end if
			else
			{
				//if new insert into db
				$data = array('nBudId' => $id,'nAHAId' => $accCode[$key],'vMode' => $modeName[$key],
							  'nAmount' => $accAmount[$key],'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}BudgetDetails', $data);
			}//end else
		}//end foreach
		
		$msgVal = 'edit_suc';

        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
		    redirect(site_url("monthly_budget/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function
	
	function deleteMonthlybudget()
	{
		$this->db->delete('{PRE}BudgetDetails', array('nBudId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}Budget', array('nBId' => $this->uri->segment(4)));
		redirect(site_url("monthly_budget/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllMonthlybudget()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nBId',$val);
					$this->db->update('{PRE}Budget', $data); 
					
					$this->db->where('nBudId',$val);
					$this->db->update('{PRE}BudgetDetails', $data); 
				}//end foreach
				redirect(site_url("monthly_budget/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nBId',$val);
					$this->db->update('{PRE}Budget', $data); 
					
					$this->db->where('nBudId',$val);
					$this->db->update('{PRE}BudgetDetails', $data); 
				}//end foreach
				redirect(site_url("monthly_budget/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}BudgetDetails', array('nBudId' => $val));
					$this->db->delete('{PRE}Budget', array('nBId' => $val));
				}//end foreach
				redirect(site_url("monthly_budget/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function
	
 	//get account head accounts details
	function getAcAccounts($nId)
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts WHERE nId = '".$nId."'");
		
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
	
	function getCorresFinanceDetails($budId)
	{
		$budDetailsRow    = array();
		$budDetailsCode   = array();
		$budDetailsMode   = array();
		$budDetailsAmount = array();
		$newValArray      = array();
		
		$budDetailsList   = $this->db->query("SELECT bd.nAHAId,aha.vACode,aha.vTitle,bd.vMode,bd.nAmount FROM {PRE}Budget b 
										 	    LEFT JOIN {PRE}BudgetDetails bd ON b.nBId = bd.nBudId
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON bd.nAHAId = aha.nId WHERE bd.nBudId = '".$budId."'");
		if ($budDetailsList->num_rows() > 0)
		{
			foreach ($budDetailsList->result() as $key=>$row)
			{
				$budDetailsRow[]    = $row->nAHAId;
				$budDetailsCode[]   = $row->nAHAId;
				$budDetailsMode[]   = $row->vMode;
				$budDetailsAmount[] = $row->nAmount;
			}//end for each
		}//end if
		
		$newValArray['accdId']     = $budDetailsRow;
		$newValArray['accdCode']   = $budDetailsCode;
		$newValArray['accdMode']   = $budDetailsMode;
		$newValArray['accdAmount'] = $budDetailsAmount;
		
		return ($newValArray);
	}//end function

	function getTotalAmount($budId)
	{
		$totalQuery = $this->db->query("SELECT b.nTotal FROM {PRE}Budget b 
										 LEFT JOIN {PRE}BudgetDetails bd ON b.nBId = bd.nBudId
									    WHERE bd.nBudId = '".$budId."'");
		$totalVal	= $totalQuery->row('nTotal');
		return $totalVal;
	}//end function
	
	function getActiveFinanceDetails($accId)
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,bd.* FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId LEFT JOIN {PRE}BudgetDetails bd ON bd.nAHAId = aca.nId
										  WHERE ah.vActive = '1' AND aca.nId = '".$accId."' 
											ORDER BY aca.nId DESC");
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
		
		$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR
											aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode LIKE ( '%".$searchKey."%') OR
											aca.vTitle LIKE ('%".$searchKey."%') OR
											aca.vTitle LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%'))";
											
		if(trim($searchKey) == 0)
		{
			$extaCondition = '';
		}//end if
		
		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
											ON aca.nHAID = ah.nAId WHERE ah.vActive = '1' ".$extaCondition." ORDER BY nId DESC");
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

	function getActiveDivision()
	{
		$locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Division WHERE vActive = '1'  AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
//			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return ($locGroupRow);
	}//end function 
	
	function getActiveLocations()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function

	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
//			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return($locGroupRow);
	}//end function 
	
	function getActiveMode()
	{
		$modeGroupRow = array("0" => SELECT, "Cr" => "Credit", "Dr" => "Debit");
		return ($modeGroupRow);
	}//end function
	
	function getMonths()
	{
		$monthGroupRow = array("0" => SELECT, "01" => "January", "02" => "February", "03" => "March", "04" => "April",
							   "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September",
							   "10" => "October", "11" => "November", "12" => "December");
		return ($monthGroupRow);
	}//end function
	
	function getAutomaticDocNumber($month,$year)
	{
		$fetchCode = $this->db->query("SELECT count(nBId) as totCount FROM {PRE}Budget");	
		$totalNum  = $fetchCode->row();
		switch($totalNum->totCount)
		{
			case "0" :
				$countVal = ($month.$year).'1';
			break;
			
			default:
				$countVal = $month.$year.($totalNum->totCount+1);	
			break;
		}//end switch
		return($countVal);
	}//end function
	
	//fetching single details
	function showDetails($budgetid)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT b.nBId as bId,bd.*,bd.nId as bdId,b.nDivision as divName,b.vLocation,b.vYear,b.vCopyref,b.vDocNumber,
											  b.nTotal,aha.*,aha.vTitle as accName,
											  CASE 
												WHEN bd.vMode = 'Cr' THEN 'Credit'
											  ELSE 'Debit' END as mode FROM {PRE}BudgetDetails 
												bd LEFT JOIN {PRE}Budget b
											 ON b.nBId = bd.nBudId 
											  LEFT JOIN {PRE}AccountHeadAccounts aha ON bd.nAHAId = aha.nId
											 WHERE b.nBId='".$budgetid."' ORDER BY bd.nId DESC");
		//single fetch
		$commVal  = $viewAccessList->row();	
		$locId    = array_filter(@split(',',$commVal->vLocation));
		$locName  = $this->getLocationName($locId);		

		$content .= '<table class="reportForm">
						<tr>
							<td colspan="4"><strong>'.ACCNAME.'</strong></td>
						</tr>
						<tr>
							<td>'.BUDGETYEAR.' :&nbsp;&nbsp;'.$commVal->vYear.'</td>
							<td>'.DIVISIONNAME.' :&nbsp;&nbsp;'.stripslashes($this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$commVal->divName."'")).'</td>
							<td>'.LOCATIONNAME.' :&nbsp;&nbsp;'.$locName.'</td>
							<td colspan="2">'.BUDGETDOCNUMBER.' :&nbsp;&nbsp;'.$commVal->vDocNumber.'</td>
						</tr>
						<tr>
							<td>'.ACCCODENAME.'</td>
							<td>'.ACCNAME.'</td>
							<td>'.BUDGETMODE.'</td>
							<td>'.BUDGETAMOUNT.'</td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$content .= '<tr>
				<td>'.$row->vACode.'</td>
				<td>'.$row->accName.'</td>
				<td>'.$row->mode.'</td>
				<td>'.$row->nAmount.'</td>
			</tr>';
		}//end foreach
			$content .= '<tr>
				<td align="right" colspan="3">'.TOTALAMOUNT.'</td>
				<td>'.$row->nTotal.'</td>
			</tr>';
		
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
		$this->db->delete('{PRE}BudgetDetails', array('nId' => $ID));
	}//end function
 
 	function getAllMonths()
	{
		$monthGroupRow = array("0" => ALL, "01" => "January", "02" => "February", "03" => "March", "04" => "April",
							   "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September",
							   "10" => "October", "11" => "November", "12" => "December");
		return ($monthGroupRow);
	}//end function

 	function getAllYears()
	{
		$startdate = 2000;
		//year to end with - this is set to current year. You can change to specific year
		$enddate   = date("Y");
		$years     = range($startdate,$enddate);
		//print years
		$test[0]   = ALL;
		foreach($years as $year)
		{
		  $test[$year] = $year;
		}
		return $test;
	}//end function

	function getFilterList($extraCheck)
	{
		$adminGroupRow          = array();
		$sqlBudgetFilterQuery   = "SELECT b.*,bd.* FROM {PRE}Budget b LEFT JOIN {PRE}BudgetDetails bd ON b.nBId = bd.nBudId
									WHERE b.vActive = '1' ".$extraCheck."
                  AND b.vLocation IN ". $this->session->userdata('accessLoc') ." 
                  GROUP BY b.nBId ORDER BY b.nBId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlBudgetFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlBudgetFilterQuery);
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
		$tabCount = $this->db->query("SELECT b.*,bd.* FROM {PRE}Budget b LEFT JOIN {PRE}BudgetDetails bd ON b.nBId = bd.nBudId
									   ".$extraCheck." 
                     AND b.vLocation IN ". $this->session->userdata('accessLoc') ." 
                     GROUP BY b.nBId ORDER BY b.nBId DESC");
		return ($tabCount->num_rows());
	}//end function

	function getActiveAccount()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' ORDER BY nId DESC");
		
/*		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
											WHERE ah.vActive = '1' AND aca.nId 
										  NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101') 
										   ORDER BY nId DESC");
*/		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function

}//end class
?>
