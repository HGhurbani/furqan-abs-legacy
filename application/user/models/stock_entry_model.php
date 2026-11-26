<?php
class Stock_entry_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();		date_default_timezone_set('Asia/Kuwait');
    }
	
/*----------------------Stock Entry Details-----------------*/
	
	function getAllStockEntry()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlStockentryQuery     = "SELECT * FROM {PRE}StockEntry 
      WHERE nLocation IN ". $this->session->userdata('accessLoc') ." 
      ORDER BY nSEId DESC
								    LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlStockentryQuery;	
		$adminGroupList 		= $this->db->query($sqlStockentryQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountStockEntry()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}StockEntry
        WHERE nLocation IN ". $this->session->userdata('accessLoc') ." 
        ORDER BY nSEId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresStockEntries()
	{
		$fetchTitle = $this->db->query("SELECT se.*,sed.* FROM {PRE}StockEntry se 
										 LEFT JOIN {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock
										WHERE sed.nStock ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function getCorresStockEntryDetails()
	{
		$seDetailsRow     = array();
		$seDetailsStype   = array();
		$seDetailsCode    = array();
		$seDetailsStartNo = array();
		$seDetailsEndNo   = array();
		$seDetailsQty     = array();
		$newValArray      = array();
		$seDetailID       = array();
	
		$seDetailsList    = $this->db->query("SELECT se.*,se.nSEId,sed.nStock,sed.vCode,                                                sed.vStartNo,sed.vEndNo,sed.nQuantity,sed.nStocktype,sed.nId as dId 
											  FROM {PRE}StockEntry se 
										 	 	LEFT JOIN {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock
											  WHERE sed.nStock ='".$this->catId."'");
		if ($seDetailsList->num_rows() > 0)
		{
			foreach ($seDetailsList->result() as $key => $row)
			{
				$seDetailsRow[]       = $row->nStock;
				$seDetailsSType[]     = $row->nStocktype;
				$seDetailsCode[]      = $row->vCode;
				$seDetailsStartNo[]   = $row->vStartNo;
				$seDetailsEndNo[]     = $row->vEndNo;
				$seDetailsQty[]       = $row->nQuantity;
				$seDetailID[]         = $row->dId;
				if(!@in_array($row->dId,$seDetailID))
					$seDetailID[]  = $row->dId;
			}//end for each
		}//end if
		
		$newValArray['stockId']    	  = $seDetailsRow;
		$newValArray['stockType']  	  = $seDetailsSType;
		$newValArray['stockCode']  	  = $seDetailsCode;
		$newValArray['stockStartNo']  = $seDetailsStartNo;
		$newValArray['stockEndNo']    = $seDetailsEndNo;
		$newValArray['stockQuantity'] = $seDetailsQty;
		$newValArray['stockdetID']    = $seDetailID;
		
		return ($newValArray);
	}//end function
	
	function insertStockEntry()
	{
		if(intval($this->input->post('txtStockTraQty')) == 0)
		{
			$data 		   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nLocation' => $this->input->post('ddlLocation'),'nDivision' => $this->input->post('ddlDivision'),                                   'dDate' => $this->input->post('txtDate'),'vDocNumber' => $this->input->post('txtDocNo'),
								   'vYearref' => $this->input->post('txtYearRef'),'vActive' => $this->input->post('chkActive'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
			$this->db->insert('{PRE}StockEntry', $data); 
			
			$id			   = $this->db->insert_id();
	
			$stockType     = (@array_filter($this->input->post('ddlStockType')) != '')?$this->input->post('ddlStockType'):@split("[,]",$this->session->userdata('ddlStockType'));
			$stockCode     = (@array_filter($this->input->post('txtStockCode')) != '')?$this->input->post('txtStockCode'):@split("[,]",$this->session->userdata('txtStockCode'));
			$stockStartNo  = (@array_filter($this->input->post('txtStartNo')) != '')?$this->input->post('txtStartNo'):@split("[,]",$this->session->userdata('txtStartNo'));
			$stockEndNo    = (@array_filter($this->input->post('txtEndNo')) != '')?$this->input->post('txtEndNo'):@split("[,]",$this->session->userdata('txtEndNo'));
			$stockQty      = (@array_filter($this->input->post('txtStockQty')) != '')?$this->input->post('txtStockQty'):@split("[,]",$this->session->userdata('txtStockQty'));
	
			//insert into travel setup details
			foreach($stockType as $key => $stockitem)
			{
				$data 	   = array('nStock' => $id,'nStocktype' => $stockitem,'vCode' => $stockCode[$key],
								   'vStartNo' => $stockStartNo[$key],'vEndNo' => $stockEndNo[$key],'nQuantity' => $stockQty[$key],                                   'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}StockEntryDetails', $data);
			}//end foreach		
		}//end if
		
		if(intval($this->input->post('txtStockTraQty')) != 0):
			$data 	= array('dDate' => date('Y-m-d'),'nStockDetId' => $this->input->post('ddlCodeTra'),
							'nLocation' => $this->input->post('ddlLocationTra'),
				            'vStart' => $this->input->post('txtStart'),
							'nQuantity' => $this->input->post('txtStockTraQty'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
			$this->db->insert('{PRE}StockTransfer', $data);
		endif;

		$msgVal = 'add_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("stock_entry/editStockentry/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("stock_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
	}//end function
	
	function updateStockEntry()
	{
		if(intval($this->input->post('txtStockTraQty')) == 0)
		{
			$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nLocation' => $this->input->post('ddlLocation'),'nDivision' => $this->input->post('ddlDivision'),
						  'dDate' => $this->input->post('txtDate'),'vDocNumber' => $this->input->post('txtDocNo'),
						  'vYearref' => $this->input->post('txtYearRef'),'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
			$this->db->where('nSEId',$this->input->post('nId'));
			$this->db->update('{PRE}StockEntry', $data); 
	
			$id			   = $this->input->post('nId');
			$stockType     = (@array_filter($this->input->post('ddlStockType')) != '')?$this->input->post('ddlStockType'):0;
			$stockCode     = (@array_filter($this->input->post('txtStockCode')) != '')?$this->input->post('txtStockCode'):0;
			$stockStartNo  = (@array_filter($this->input->post('txtStartNo')) != '')?$this->input->post('txtStartNo'):0;
			$stockEndNo    = (@array_filter($this->input->post('txtEndNo')) != '')?$this->input->post('txtEndNo'):0;
			$stockQty      = (@array_filter($this->input->post('txtStockQty')) != '')?$this->input->post('txtStockQty'):0;
			$chkStockID	   = $this->input->post('idArray'); 
	
			//insert into Stock entry details
			foreach($stockType as $key => $stockitem)
			{
				//checking already exists if yes updated
				if(intval($chkStockID[$key]) != 0)
				{
					
					$data 	= array('nStock' => $id,'nStocktype' => $stockitem,'vCode' => $stockCode[$key],
									'vStartNo' => $stockStartNo[$key],'vEndNo' => $stockEndNo[$key],
									'nQuantity' => $stockQty[$key],'vActive' => $this->input->post('chkActive'));
					$this->db->where('nId',$chkStockID[$key]);
					$this->db->update('{PRE}StockEntryDetails', $data); 
				}//end if
				else
				{//if new insert into db
					$data 	= array('nStock' => $id,'nStocktype' => $stockitem,'vCode' => $stockCode[$key],
									'vStartNo' => $stockStartNo[$key],'vEndNo' => $stockEndNo[$key],'nQuantity' => $stockQty[$key],                                    'vActive' => $this->input->post('chkActive'));
					$this->db->insert('{PRE}StockEntryDetails', $data);
				}//end else
			}//end foreach
		}//end if
		
		if(intval($this->input->post('txtStockTraQty')) != 0):
			$data 	= array('dDate' => date('Y-m-d'),'nStockDetId' => $this->input->post('ddlCodeTra'),
							'nLocation' => $this->input->post('ddlLocationTra'),'vStart' => $this->input->post('txtStart'),
							'nQuantity' => $this->input->post('txtStockTraQty'),'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
			$this->db->insert('{PRE}StockTransfer', $data);
		endif;

		$msgVal = 'edit_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

		redirect(site_url("stock_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
	}//end function

	function deleteStockEntry()
	{
		$this->db->delete('{PRE}StockEntryDetails', array('nStock' => $this->uri->segment(4)));
		$this->db->delete('{PRE}StockTransfer', array('nStockDetId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}StockEntry', array('nSEId' => $this->uri->segment(4)));
		redirect(site_url("stock_entry/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteMultipleStockEntry()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nSEId',$val);
					$this->db->update('{PRE}StockEntry', $data); 
					
					$this->db->where('nStock',$val);
					$this->db->update('{PRE}StockEntryDetails', $data); 
				}//end foreach
				redirect(site_url("stock_entry/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nSEId',$val);
					$this->db->update('{PRE}StockEntry', $data); 
					
					$this->db->where('nStock',$val);
					$this->db->update('{PRE}StockEntryDetails', $data); 
				}//end foreach
				redirect(site_url("stock_entry/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}StockEntryDetails', array('nStock' => $val));
					$this->db->delete('{PRE}StockTransfer', array('nStockDetId' => $val));
					$this->db->delete('{PRE}StockEntry', array('nSEId' => $val));
				}//end foreach
				redirect(site_url("stock_entry/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function
	
	function getActiveStockType()
	{
		$typeGroup    = $this->db->query("SELECT nStkId,vName FROM {PRE}Stock WHERE vActive = '1' 
											ORDER BY vName DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nStkId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 

    function getTransferLocation()
    {
        $locGroup = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nId IN ".$this->session->userdata('accessLoc') ."  ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) {
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

    function filterLocation($division)
    {
        $locGroup = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
										AND nDivision = '" . $division . "'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0) {
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

    function getActiveDivision()
    {
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1'  AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
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

    function getLocation()
    {
        $locGroupRow = array();
        $locGroupRow[0] = SELECT;
        return ($locGroupRow);
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

    function getStockcode()
	{
		$stockcodeGroup    = $this->db->query("SELECT sd.nId,sd.vCode FROM {PRE}StockEntryDetails sd
													LEFT JOIN {PRE}StockTransfer sf ON sd.nId = sf.nStockDetId
														WHERE sd.vActive = '1' AND 
														CASE
															WHEN sf.nQuantity is null THEN
																(sd.nQuantity - 0) > 0
															ELSE 
																(sd.nQuantity - sf.nQuantity) > 0
														END
											    ORDER BY sd.nId DESC");
		
		$stockcodeGroupRow = array();
		
		if ($stockcodeGroup->num_rows() > 0)
		{
			$stockcodeGroupRow[0] = SELECT;
			foreach ($stockcodeGroup->result() as $key=>$row)
			{
				$stockcodeGroupRow[$row->nId] = stripslashes($row->vCode);
			}//end for each
		}//end if
		
		else
		{
			$stockcodeGroupRow[0] = NOVALUE;
		}//end else
		return ($stockcodeGroupRow);
	}//end function
	
	function getDetails($stockid)
	{
		$viewAccessRow   = array();

		$viewAccessList  = $this->db->query("SELECT se.nSEId as seId,
												sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype
											  WHERE se.nSEId='".$stockid."' ORDER BY sed.nId DESC");
			
		
		if ($viewAccessList->num_rows() > 0)
		{
			foreach ($viewAccessList->result() as $key=>$row)
			{
				$viewAccessRow[] = $row;
			}//end for each
		} //end if
		return ($viewAccessRow);
	}//end function
	

	//fetching single details
	function showDetails($stockid)
	{
		$viewAccessRow   = array();

		$viewAccessList  = $this->db->query("SELECT se.nSEId as seId,
												sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype
											  WHERE se.nSEId='".$stockid."' ORDER BY sed.nId DESC");
		
		//single fetch
		$commVal  = $viewAccessList->row();	
		$content .= '<table class="reportForm">
						<tr>
							<td colspan="5"><strong>'.STOCKENTRYDETAILS.'</strong></td>
						</tr>
						
						<tr>
							<td><strong>'.DIVISIONNAME.'&nbsp;:&nbsp;</strong>'.$this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$commVal->nDivision."' ").'</td>
							<td colspan="4"><strong>'.LOCATIONNAME.'&nbsp;:&nbsp;</strong>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$commVal->nLocation."' ").'</td>
						</tr>
						
						<tr>
						    <td><strong>'.CUSTDATE.'&nbsp;:&nbsp;</strong>'.$commVal->dDate.'</td>
							<td><strong>'.YEARREF.'&nbsp;:&nbsp;</strong>'.$commVal->vYearRef.'</td>
							<td colspan="3"><strong>'.DOCNUMBER.'&nbsp;:&nbsp;</strong>'.$commVal->vDocNumber.'</td>
						</tr>
						
						<tr>
							<td><strong>'.STOCKTYPE.'</strong></td>
							<td><strong>'.STOCKCODE.'</strong></td>
							<td><strong>'.START.'</strong></td>
							<td><strong>'.END.'</strong></td>
							<td><strong>'.STOCKQTY.'</strong></td>
						</tr>';
		
		foreach ($viewAccessList->result() as $key => $row)
		{
			$content .= '<tr>
				<td>'.$row->vName.'</td>
				<td>'.$row->vCode.'</td>
				<td>'.$row->vStartNo.'</td>
				<td>'.$row->vEndNo.'</td>
				<td>'.$row->nQuantity.'</td>
			</tr>';
		}//end foreach

		$transferList  = $this->db->query("SELECT sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,
											 st.nLocation as traLoc,st.vStart,st.nQuantity as traQty
										   FROM {PRE}StockEntryDetails sed
										     LEFT JOIN {PRE}StockTransfer st ON sed.nId = st.nStockDetId
										   WHERE st.nStockDetId = '".$stockid."' ORDER BY st.nSTId DESC");	
		$content .= '<tr>
						<td colspan="5"><strong>'.TRANSFERDETAILS.'</strong></td>
					</tr>
					
		            <tr>
						<td><strong>'.TOLOCATION.'</strong></td>
						<td><strong>'.CODE.'</strong></td>
						<td><strong>'.START.'</strong></td>
						<td><strong>'.END.'</strong></td>
						<td><strong>'.STOCKQTY.'</strong></td>
					 </tr>';
	   foreach ($transferList->result() as $key1 => $row1)
	   {
		$content .=	 '<tr>
						<td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->traLoc."' ").'</td>
						<td>'.$row1->vCode.'</td>
						<td>'.$row1->vStart.'</td>
						<td>'.$row1->vEndNo.'</td>
						<td>'.$row1->traQty.'</td>
					  </tr>';
	   }//end foreach
		
		$content .= '</table>';
	    return($content);
	}//end function
 
 	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}StockEntryDetails', array('nId' => $ID));
	}//end function
  
	function getFilterList($extraCheck)
  {
    if($extraCheck == '')
      $extraCheck .= " WHERE ";
    else 
      $extraCheck .= " AND ";

    $extraCheck .= " nLocation IN ". $this->session->userdata('accessLoc') ."   ";

		$adminGroupRow            = array();
		$sqlStockEntryFilterQuery = "SELECT se.*,sed.* FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed 
									  ON se.nSEId = sed.nStock
									 ".$extraCheck." GROUP BY sed.nStock ORDER BY se.nSEId DESC
								      LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query']   = $sqlStockEntryFilterQuery;	
		$adminGroupList 		  = $this->db->query($sqlStockEntryFilterQuery);
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
    if($extraCheck == '')
      $extraCheck .= " WHERE ";
    else 
      $extraCheck .= " AND ";

    $extraCheck .= " nLocation IN ". $this->session->userdata('accessLoc') ."   ";

		$tabCount = $this->db->query("SELECT se.*,sed.* FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed 
										ON se.nSEId = sed.nStock 
									 ".$extraCheck." GROUP BY sed.nStock ORDER BY se.nSEId DESC");
		return ($tabCount->num_rows());
	}//end function

    function getStockDetails($stockCode)
    {
		$typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT se.*,sed.*,
											CASE
												WHEN sf.nQuantity is null THEN
													 0
												ELSE 
													 sf.nQuantity
										    END as avlQty
										  FROM {PRE}StockEntry se 
										    LEFT JOIN {PRE}StockEntryDetails sed ON sed.nStock = se.nSEId
										  LEFT JOIN {PRE}StockTransfer sf ON sed.nId = sf.nStockDetId
										    WHERE sed.nId = '".$stockCode."'");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
/*				if(intval($row->avlQty) != 0):
					$typeGroupRow['Start']       = ($row->vStartNo+$row->avlQty)+1;
					$typeGroupRow['StockTraQty'] = ($row->nQuantity-$row->avlQty)-1;
				else:
					$typeGroupRow['Start']       = $row->vStartNo;
					$typeGroupRow['StockTraQty'] = $row->nQuantity;
				endif;
*/				$typeGroupRow['Start']       = $row->vStartNo;
				$typeGroupRow['StockTraQty'] = $row->nQuantity;
				$typeGroupRow['End']             = $row->vEndNo;
				
			}//end for each
		}//end if
		return ($typeGroupRow);
    }//end function

	//check stock code
	function checkStartNo()
	{
		$chkCode = $this->db->query("SELECT vStartNo FROM {PRE}StockEntryDetails
										WHERE vStartNo = '".$this->input->post('txtStartNo')."'");
		if($chkCode->num_rows() > 0)
		{
			echo 'Already exists';
		}//end if
		else
		{
			;
		}//end else
		
	}//end function
	
	//check stock code
	function checkEndNo()
	{
		$chkCode = $this->db->query("SELECT vEndNo FROM {PRE}StockEntryDetails
										WHERE vEndNo = '".$this->input->post('txtEndNo')."'");
		if($chkCode->num_rows() > 0)
		{
			echo 'Already exists';
		}//end if
		else
		{
			;
		}//end else
		
	}//end function
}//end class
?>
