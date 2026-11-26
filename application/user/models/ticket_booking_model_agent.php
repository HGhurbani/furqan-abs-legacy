<?php
class Ticket_booking_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
ini_set('memory_limit','512M');
    }
	
/*---------------- Ticket Booking Master -----------------*/

	function getAllTicketbooking()
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlTicketbookingQuery  = "SELECT tb.*,gds.vName as gdsName, tb.vIssuedBy,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
									CASE 
										WHEN tb.vType = 'N' THEN 'Normal'
									WHEN tb.vType = 'V' THEN 'Void'
										WHEN tb.vType = 'C' THEN 'Conjuction'
									ELSE 'Re issue' END as tbType FROM {PRE}TicketBooking tb 
									 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId 
									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline WHERE vConjStatus = 'Yes' AND tb.nLocation IN ". $this->session->userdata('accessLoc') ."  
									ORDER BY  YEAR(tb.dDate) DESC, MONTH(tb.dDate) DESC, DAY(tb.dDate) DESC, vTicktManual DESC 
								     LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTicketbookingQuery;	
		$ticketGroupList 		= $this->db->query($sqlTicketbookingQuery);
		
		if ($ticketGroupList->num_rows() > 0)
		{
			foreach ($ticketGroupList->result() as $key=>$row)
			{
				$ticketGroupRow[] = $row;
			}//end for each
		}//end if
		return ($ticketGroupRow);
	}//end function

	//get user ticket booking count
	function getAllTicketbookingCount()
	{		
		//active groups
		$ticketGroupRow  = array();
		$ticketGroupList = $this->db->query("SELECT tb.*,gds.vName as gdsName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
												CASE 
													WHEN tb.vType = 'N' THEN 'Normal'
												WHEN tb.vType = 'V' THEN 'Void'
													WHEN tb.vType = 'C' THEN 'Conjuction'
												ELSE 'Re issue' END as tbType FROM {PRE}TicketBooking tb 
											 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId 
												LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline WHERE vConjStatus = 'Yes'
                        AND tb.nLocation IN ". $this->session->userdata('accessLoc') ." 

											ORDER BY vTicktManual DESC");
		return ($ticketGroupList->num_rows());
	}//end function	

	function getActiveAirline()
	{
		$airlineGroupRow = array();
		$airlineGroup    = $this->db->query("SELECT * FROM {PRE}Airline ORDER BY vTitle ASC");
		if ($airlineGroup->num_rows() > 0)
		{
			$airlineGroupRow[0] = SELECT;
			foreach ($airlineGroup->result() as $key => $row)
			{
				$airlineGroupRow[$row->nArId] = $row->vTitle . " (" .$row->vAirlineNo . ")";
			}//end for each
		}//end if
		else
		{
			$airlineGroupRow[0] = NOVALUE;
		}//end else

		return ($airlineGroupRow);
	}//end function 

	function getActiveStock()
	{
		$stockGroupRow = array();
		$stockGroup    = $this->db->query("SELECT se.nSEId,se.nLocation,sed.*,st.nLocation,tb.*,s.nTransaction
											 FROM {PRE}StockEntry se 
											LEFT JOIN {PRE}TicketBooking tb ON se.nLocation = tb.nLocation LEFT JOIN 
										   {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock 
										    LEFT JOIN {PRE}StockTransfer st ON st.nStockDetId = sed.nId
										   LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype WHERE s.nTransaction = '5'
										    ORDER BY sed.nId DESC");
		if($stockGroup->num_rows() > 0)
		{
			$stockGroupRow[0] = SELECT;
			foreach($stockGroup->result() as $key => $row)
			{
				$stockGroupRow[$row->nId] = $row->vCode.' - '.$row->vStartNo.' - '.$row->vEndNo.' - '.$row->nQuantity;
			}//end for each
		}//end if
		else
		{
			$stockGroupRow[0] = NOVALUE;
		}//end else

		return ($stockGroupRow);
	}//end function 

	function getActiveLocStock($loc)
	{
		$stockGroupRow = array();
		$stockGroup    = $this->db->query("SELECT se.nSEId,se.nLocation,sed.*,st.nLocation,st.vStart,st.vEnd,
										   st.nQuantity as tQuantity,s.nTransaction,CASE WHEN se.nLocation = '".$loc."' THEN 0 ELSE
										   st.nStockDetId END as stockId, CASE WHEN se.nLocation = '".$loc."' THEN	sed.vStartNo
										   ELSE st.vStart END as startNo, CASE WHEN se.nLocation = '".$loc."' THEN
										   sed.vEndNo ELSE	st.vEnd	END as endNo,CASE WHEN se.nLocation = '".$loc."' THEN
										   sed.nQuantity ELSE sed.nQuantity END as avlQty,CASE WHEN st.nQuantity is null THEN	0                                                ELSE
										   st.nQuantity END as balQty FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed ON                                              se.nSEId = sed.nStock
										   LEFT JOIN {PRE}StockTransfer st ON st.nStockDetId = sed.nId LEFT JOIN {PRE}Stock s ON                                               s.nStkId = sed.nStocktype
										   WHERE sed.vActive = '1' AND CASE	WHEN st.nQuantity is null THEN (sed.nQuantity - 0) > 0
										   ELSE (sed.nQuantity - st.nQuantity) > 0 END AND (se.nLocation = '".$loc."' 
										      OR st.nLocation = '".$loc."')
										   AND s.nTransaction = '5' ORDER BY sed.nId DESC");
		if($stockGroup->num_rows() > 0)
		{
			$stockGroupRow[0] = SELECT;
			foreach($stockGroup->result() as $key => $row)
			{
				if(intval($row->stockId) != 0)
					$balQty                   = $row->balQty;
				else
					$balQty                   = $row->avlQty-$row->balQty;
				
				if($balQty > 2 )
					$separator = ' -';
				else
					$separator = ',';
					
				//get all tickets
				$this->allTkt  = $this->getAllTicket($row->nId,$row->startNo,$separator,$row->endNo);
				//get next ticket
				$this->nextTkt = $this->getNextTicket($row->nId,$row->startNo,$separator,$row->endNo);	
				$stockGroupRow[$row->nId.'-'.$this->nextTkt.'-'.$this->allTkt] = $row->vCode.' - '.$row->startNo.$separator.$row->endNo.' - '.$balQty;
			}//end for each
		}//end if
		else
		{
			$stockGroupRow[0] = NOVALUE;
		}//end else

		//set session value
		$_SESSION['tktChkVal'] = $stockGroupRow;
		
		return ($stockGroupRow);
	}//end function 
	
	function getActiveGDS()
	{
		$gdsGroupRow = array();
		$gdsGroup    = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");
		if ($gdsGroup->num_rows() > 0)
		{
			$gdsGroupRow[0] = SELECT;
			foreach ($gdsGroup->result() as $key => $row)
			{
				$gdsGroupRow[$row->nGId] = $row->vName;
			}//end for each
		}//end if
		else
		{
			$gdsGroupRow[0] = NOVALUE;
		}//end else
		return ($gdsGroupRow);
	}//end function 
	
	function getLocation()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function 

	function filterFarecode($fare)
	{
		$fareCodeGroup    = $this->db->query("SELECT nFId,vCode FROM {PRE}FareCode ORDER BY nFId DESC");
		$fareCodeGroupRow = array();
		if ($fareCodeGroup->num_rows() > 0)
		{
			$fareCodeGroupRow[0] = SELECT;
			foreach ($fareCodeGroup->result() as $key => $row)
			{
				$fareCodeGroupRow[$row->nFId] = stripslashes($row->vCode);
			}//end for each
		}//end if
		else
		{
			$fareCodeGroupRow[0] = NOVALUE;
		}//end else
		return($fareCodeGroupRow);
	}//end function 

	function getActiveLocations()
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1' AND nId IN ".$this->session->userdata('accessLoc') ."  ORDER BY vTitle ASC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return($locGroupRow);
	}//end function 

	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			//$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return($locGroupRow);
	}//end function 

	function getActiveDivision()
	{
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1' AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
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

	function getActiveLocation()
	{
		$sessionLoc  = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
		$locName     = $sessionLoc->row();
		
		if($sessionLoc->num_rows() == 0)
			$locId = $this->common_model->getLocLogged();
		else
			$locId = $locName->vLocation;
		
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '".$locId."' 
											ORDER BY nPosition DESC");	
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return ($locGroupRow);
	}//end function 
	
	function getActiveTransactions()
	{
		$transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1'
											ORDER BY nPosition DESC");
		$transGroupRow = array();
		
		if ($transGroup->num_rows() > 0)
		{
			$transGroupRow[0] = SELECT;
			foreach ($transGroup->result() as $key => $row)
			{
				$transGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$transGroupRow[0] = NOVALUE;
		}//end else
		return ($transGroupRow);
	}//end function 

	function generateCustAcc($mode)
	{
		$custAccGroupRow = array();
		switch($mode)
		{
			case "Cr" :
				$custAccGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;
			
			case "Ca" :
				$custAccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
														FROM {PRE}AccountHeadAccounts aca 
													  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN 
														{PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
													  WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' ORDER BY nId DESC");	
				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vACode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;
			
			case "KN" :
				$custAccGroup    = $this->db->query("SELECT * FROM {PRE}Card ORDER BY nCId DESC");
				if ($custAccGroup->num_rows() > 0)
				{
					$custAccGroupRow[0] = SELECT;
					foreach ($custAccGroup->result() as $key => $row)
					{
						$custAccGroupRow[$row->nCId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
					}//end for each
				}//end if
				else
				{
					$custAccGroupRow[0] = NOVALUE;
				}//end else
			break;
		}//end switch

		return($custAccGroupRow);
	}//end function 

	function getActiveType()
	{
		$typeGroupRow    = array("N" => "Normal", "V" => "Void", "C" => "Conjuction", "R" => "Re issue");
		return ($typeGroupRow);
	}//end function 

	function getSearchActiveType()
	{
		$typeGroupRow    = array("0" => ALL, "N" => "Normal", "V" => "Void", "C" => "Conjuction", "R" => "Re issue");
		return ($typeGroupRow);
	}//end function 

	function getActiveEmployee()
	{
		$empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1' 
											ORDER BY vName ASC");
		$empGroupRow = array();
		
		if ($empGroup->num_rows() > 0)
		{
			$empGroupRow[0] = SELECT;
			foreach ($empGroup->result() as $key => $row)
			{
				$empGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$empGroupRow[0] = NOVALUE;
		}//end else
		return ($empGroupRow);
	}//end function 

	function getActiveCustomer()
	{
		$custGroup    = $this->db->query("SELECT nCId,vCode,vName FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY nCId DESC");
		$custGroupRow = array();
		
		if ($custGroup->num_rows() > 0)
		{
			$custGroupRow[0] = SELECT;
			foreach ($custGroup->result() as $key => $row)
			{
				$custGroupRow[$row->nCId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$custGroupRow[0] = NOVALUE;
		}//end else
		return ($custGroupRow);
	}//end function 

	function getActiveAgent()
	{
		$agentGroup    = $this->db->query("SELECT nAId,vCode,vName FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
		$agentGroupRow = array();
		
		if ($agentGroup->num_rows() > 0)
		{
			$agentGroupRow[0] = SELECT;
			foreach ($agentGroup->result() as $key => $row)
			{
				$agentGroupRow[$row->nAId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
			}//end for each
		}//end if
		else
		{
			$agentGroupRow[0] = NOVALUE;
		}//end else
		return ($agentGroupRow);
	}//end function 

	function getActiveCurrency()
	{
		$currGroup    = $this->db->query("SELECT nId,vTitle,vCurrcode FROM {PRE}Currency WHERE vActive = '1' ORDER BY nId DESC");
		$currGroupRow = array();
		
		if ($currGroup->num_rows() > 0)
		{
			$currGroupRow[0] = SELECT;
			foreach ($currGroup->result() as $key => $row)
			{
				$currGroupRow[$row->nId] = (stripslashes($row->vTitle).' ('.stripslashes($row->vCurrcode).') ');
			}//end for each
		}//end if
		else
		{
			$currGroupRow[0] = NOVALUE;
		}//end else
		return ($currGroupRow);
	}//end function

	function getCurrency()
	{
		$currGroup    = $this->db->query("SELECT nId,vTitle,vCurrcode FROM {PRE}Currency WHERE vActive = '1' ORDER BY nId DESC");
		$currGroupRow = array();
		
		if ($currGroup->num_rows() > 0)
		{
			$currGroupRow[0] = SELECT;
			foreach ($currGroup->result() as $key => $row)
			{
				$currGroupRow[$row->nId] = (stripslashes($row->vTitle).' ('.stripslashes($row->vCurrcode).') ');
			}//end for each
		}//end if
		else
		{
			$currGroupRow[0] = NOVALUE;
		}//end else
		return ($currGroupRow);
	}//end function
	
	function getAccounts()
	{
		$accGroupRow = array();
		$accGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId ASC");
		if ($accGroup->num_rows() > 0)
		{
			//$accGroupRow[0] = SELECT;
			foreach ($accGroup->result() as $key=>$row)
			{
				$accGroupRow[$row->nVId] = $row->vName.' ('.$row->vCode.' )';
			}//end for each
		}//end if
		else
		{
			$accGroupRow[0] = NOVALUE;
		}//end else
		return ($accGroupRow);
	}//end function 

	function getAccountHead()
	{
		$accheadGroupRow = array();
		$accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
										  	    WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' ORDER BY nId DESC");
		if ($accheadGroup->num_rows() > 0)
		{
			//$accheadGroupRow[0] = SELECT;
			foreach ($accheadGroup->result() as $key=>$row)
			{
				$accheadGroupRow[$row->ahaId] = $row->vTitle;
			}//end for each
		}//end if
		else
		{
			$accheadGroupRow[0] = NOVALUE;
		}//end else

		return ($accheadGroupRow);
	}//end function 

	function getActiveAccHead($accGroup)
	{
		$accNameId       = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = '".$accGroup."' ");
		$accheadGroupRow = array();
		$accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
										  	    LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId 
											 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' AND v.nFId = '".$accNameId."'
											  ORDER BY nId DESC");
		if ($accheadGroup->num_rows() > 0)
		{
			//$accheadGroupRow[0] = SELECT;
			foreach ($accheadGroup->result() as $key=>$row)
			{
				$accheadGroupRow[$row->ahaId] = $row->vTitle;
			}//end for each
		}//end if
		else
		{
			$accheadGroupRow[0] = NOVALUE;
		}//end else

		return ($accheadGroupRow);
	}//end function 
	
	function getAgentAccounts()
	{
		$agentaccGroupRow = array();
        $agentaccGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls
										        ahc ON ahc.nActHId = aca.nId WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' 
											  ORDER BY nId DESC");
		if ($agentaccGroup->num_rows() > 0)
		{
			$agentaccGroupRow[0] = SELECT;
			foreach ($agentaccGroup->result() as $key=>$row)
			{
				$agentaccGroupRow[$row->nAId] = $row->vTitle.' ('.$row->vACode.' )';
			}//end for each
		}//end if
		else
		{
			$agentaccGroupRow[0] = NOVALUE;
		}//end else
		return ($agentaccGroupRow);
	}//end function

    function getCloseStatus()
	{
		$statusGroupRow = array();
		$statusGroup    = $this->db->query("(SELECT nTId FROM {PRE}TicketBooking tb LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.vConjStatus = 'Yes' AND tb.nTId
									NOT IN (SELECT scd.vTktNumber FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Tkt') 
									 AND tb.nTId='".$this->catId."' ORDER BY tb.nTId DESC)");
        $showVal	     = $statusGroup->row();
        return $showVal;
	}//end function

    function getInvoiceNumber()
	{
		$statusGroupRow = array();
		$statusGroup    = $this->db->query("(SELECT vInvoiceNumber FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd                                                  ON sc.nSCId=scd.nSalesCloseId
		                                    WHERE scd.vTktNumber='".$this->catId."' AND scd.vType = 'Tkt')");
        $showVal	     = $statusGroup->row();
        return $showVal;
	}//end function

    function getRefundStatus()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT nTId FROM {PRE}TicketBooking tb LEFT JOIN
									 {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId WHERE tb.vConjStatus = 'Yes' AND tb.nTId
									NOT IN (SELECT scd.nTicketNumber FROM {PRE}RefundClosingDetails scd WHERE scd.vType = 'Tkt') 
									 AND tb.nTId='".$this->catId."' ORDER BY tb.nTId DESC)");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

    function getRefundInvoiceNumber()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT vDocNumber FROM {PRE}RefundClosing sc LEFT JOIN {PRE}RefundClosingDetails scd 
												ON sc.nRCId=scd.nRefundCloseId
		                                    WHERE scd.nTicketNumber='".$this->catId."' AND scd.vType = 'Tkt')");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

	function getActiveCustomerAccounts()
	{
		$custaccGroupRow = array();
		$custaccGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
											
		if ($custaccGroup->num_rows() > 0)
		{
			$custaccGroupRow[0] = SELECT;
			foreach ($custaccGroup->result() as $key=>$row)
			{
				$custaccGroupRow[$row->nCId] = $row->vName.' ('.$row->vCode.' )';
			}//end for each
		}//end if
		else
		{
			$custaccGroupRow[0] = NOVALUE;
		}//end else
		return ($custaccGroupRow);
	}//end function 

	function getFareCode($airline)
	{
		$farecodeGroup    = $this->db->query("SELECT nFId,vCode FROM {PRE}FareCode WHERE nAirline = '".$airline."' 
												ORDER BY nFId DESC");
		$farecodeGroupRow = array();
		
		if ($farecodeGroup->num_rows() > 0)
		{
			$farecodeGroupRow[0] = SELECT;
			foreach ($farecodeGroup->result() as $key => $row)
			{
				$farecodeGroupRow[$row->nFId] = $row->vCode;
			}//end for each
		}//end if
		else
		{
			$farecodeGroupRow[0] = NOVALUE;
		}//end else
		return ($farecodeGroupRow);
	}//end function

	function getCorrActiveLocation($division)
	{
		$corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."'                                              ORDER BY nPosition DESC");
		$corrLocGroupRow = array();
		
		if ($corrLocGroup->num_rows() > 0)
		{
			$corrLocGroupRow[0] = SELECT;
			foreach ($corrLocGroup->result() as $key => $row)
			{
			  $corrLocGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
		  $corrLocGroupRow[0] = NOVALUE;
		}//end else
		return ($corrLocGroupRow);
	}//end function
	
	function insertTicketbooking()
	{
		$tktNo = @split("-",$this->input->post('ddlTktname'));
		
		//insert into ticket booking master table
	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
						'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),
						'nAirline' => $this->input->post('ddlAirline'),'vDocNumber' => $this->input->post('txtDocNumber'),
		                'vTicketNumber' => $tktNo[0],'nGDS' => $this->input->post('ddlGDS'),
						'vType' => $this->input->post('ddlType'),'vIssuedBy' => $this->input->post('ddlIssued'),
						'vBookedBy' => $this->input->post('ddlBooked'),'vPassenger' => $this->input->post('txtPassenger'),
						'vOriginalTkt' => $this->input->post('txtOriginalTkt'),
						'vGdspnr' => $this->input->post('txtGdsPNR'),'vAirpnr' => $this->input->post('txtAirPNR'),
						'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
						'vActive' => $this->input->post('chkActive'),'vConjStatus' => 'Yes',
						'vConjTkt' => $this->input->post('txtConjTkt'), 'vTicktManual' => $this->input->post('txtTktName'));
		$this->db->insert('{PRE}TicketBooking', $data); 

		$id	    = $this->db->insert_id();	
		
	   //insert into Ticket Details table
	   $data 	= array('nTicketId' => $id ,'vCarrier1' => $this->input->post('txtCarrier1'),
		                'vFlight1' => $this->input->post('txtFlightNo1'),
						'vClass1' => $this->input->post('txtClass1'),'dTravelDate1' => date('Y-m-d',strtotime($this->input->post('txtTravelDate1'))),
						'vSector1' => $this->input->post('txtSector1'),'vSector12' => $this->input->post('txtSector12'),
						'vStatus1' => $this->input->post('txtStatus1'),'vTimeDept1' => $this->input->post('txtTimeDep1'),
						'vTimeArr1' => $this->input->post('txtTimeArr1'),'vDay1' => $this->input->post('txtDay1'),
						'vCarrier2' => $this->input->post('txtCarrier2'),'vFlight2' => $this->input->post('txtFlightNo2'),
						'vClass2' => $this->input->post('txtClass2'),'dTravelDate2' => date('Y-m-d',strtotime($this->input->post('txtTravelDate2'))),
						'vSector2' => $this->input->post('txtSector2'),'vSector22' => $this->input->post('txtSector22'),
						'vStatus2' => $this->input->post('txtStatus2'),'vTimeDept2' => $this->input->post('txtTimeDep2'),
						'vTimeArr2' => $this->input->post('txtTimeArr2'),'vDay2' => $this->input->post('txtDay2'),
						'vCarrier3' => $this->input->post('txtCarrier3'),'vFlight3' => $this->input->post('txtFlightNo3'),
						'vClass3' => $this->input->post('txtClass3'),'dTravelDate3' => date('Y-m-d',strtotime($this->input->post('txtTravelDate3'))),
						'vSector3' => $this->input->post('txtSector3'),'vSector33' => $this->input->post('txtSector33'),
						'vStatus3' => $this->input->post('txtStatus3'),'vTimeDept3' => $this->input->post('txtTimeDep3'),
						'vTimeArr3' => $this->input->post('txtTimeArr3'),'vDay3' => $this->input->post('txtDay3'),
						'vCarrier4' => $this->input->post('txtCarrier4'),'vFlight4' => $this->input->post('txtFlightNo4'),
						'vClass4' => $this->input->post('txtClass4'),'dTravelDate4' => date('Y-m-d',strtotime($this->input->post('txtTravelDate4'))),
						'vSector4' => $this->input->post('txtSector4'),'vSector44' => $this->input->post('txtSector44'),
						'vStatus4' => $this->input->post('txtStatus4'),'vTimeDept4' => $this->input->post('txtTimeDep4'),
						'vTimeArr4' => $this->input->post('txtTimeArr4'),'vDay4' => $this->input->post('txtDay4'),
						'vSegment' => $this->input->post('txtSegment'),'vDestination' => $this->input->post('txtDestination'));
		$this->db->insert('{PRE}TicketDetails', $data);

		if($this->input->post('txtConjTkt') != '')
		{
			//insert into ticket booking master table
			$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
							'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d'),
							'nAirline' => $this->input->post('ddlAirline'),'vDocNumber' => $this->input->post('txtDocNumber'),
							'vTicketNumber' => $this->input->post('txtConjTkt'),'nGDS' => $this->input->post('ddlGDS'),
							'vType' => $this->input->post('ddlType'),'vIssuedBy' => $this->input->post('ddlIssued'),
							'vBookedBy' => $this->input->post('ddlBooked'),'vPassenger' => $this->input->post('txtPassenger'),
							'vOriginalTkt' => $this->input->post('txtOriginalTkt'),
							'vGdspnr' => $this->input->post('txtGdsPNR'),'vAirpnr' => $this->input->post('txtAirPNR'),
							'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
							'vActive' => $this->input->post('chkActive'),'vConjStatus' => 'No',
							'vConjTkt' => $id);
			$this->db->insert('{PRE}TicketBooking', $data); 
			
			$conjId	= $this->db->insert_id();	
		   //insert into Ticket Conjuction Details into ticket table
		   $data 	= array('nTicketId' => $conjId ,'vCarrier1' => $this->input->post('txtCarrierTkt1'),
							'vFlight1' => $this->input->post('txtFlightNoTkt1'),
							'vClass1' => $this->input->post('txtClassTkt1'),
							'dTravelDate1' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt1'))),
							'vSector1' => $this->input->post('txtSectorTkt1'),'vSector12' => $this->input->post('txtSectorTkt12'),
							'vStatus1' => $this->input->post('txtStatusTkt1'),'vTimeDept1' => $this->input->post('txtTimeDepTkt1'),
							'vTimeArr1' => $this->input->post('txtTimeArrTkt1'),'vDay1' => $this->input->post('txtDayTkt1'),
							'vCarrier2' => $this->input->post('txtCarrierTkt2'),'vFlight2' => $this->input->post('txtFlightNoTkt2'),
							'vClass2' => $this->input->post('txtClassTkt2'),
							'dTravelDate2' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt2'))),
							'vSector2' => $this->input->post('txtSectorTkt2'),'vSector22' => $this->input->post('txtSectorTkt22'),
							'vStatus2' => $this->input->post('txtStatusTkt2'),'vTimeDept2' => $this->input->post('txtTimeDepTkt2'),
							'vTimeArr2' => $this->input->post('txtTimeArrTkt2'),'vDay2' => $this->input->post('txtDayTkt2'),
							'vCarrier3' => $this->input->post('txtCarrierTkt3'),'vFlight3' => $this->input->post('txtFlightNoTkt3'),
							'vClass3' => $this->input->post('txtClassTkt3'),
							'dTravelDate3' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt3'))),
							'vSector3' => $this->input->post('txtSectorTkt3'),'vSector33' => $this->input->post('txtSectorTkt33'),
							'vStatus3' => $this->input->post('txtStatusTkt3'),'vTimeDept3' => $this->input->post('txtTimeDepTkt3'),
							'vTimeArr3' => $this->input->post('txtTimeArrTkt3'),'vDay3' => $this->input->post('txtDayTkt3'),
							'vCarrier4' => $this->input->post('txtCarrierTkt4'),'vFlight4' => $this->input->post('txtFlightNoTkt4'),
							'vClass4' => $this->input->post('txtClassTkt4'),
							'dTravelDate4' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt4'))),
							'vSector4' => $this->input->post('txtSectorTkt4'),'vSector44' => $this->input->post('txtSectorTkt44'),
							'vStatus4' => $this->input->post('txtStatusTkt4'),'vTimeDept4' => $this->input->post('txtTimeDepTkt4'),
							'vTimeArr4' => $this->input->post('txtTimeArrTkt4'),'vDay4' => $this->input->post('txtDayTkt4'),
							'vSegment' => $this->input->post('txtSegmentCnjTkt'),
							'vDestination' => $this->input->post('txtDestinationCnjTkt'));
			$this->db->insert('{PRE}TicketDetails', $data);
		}//end if
	   
	   //insert into Ticket Fare Details table
		$data 	= array('nTicketId' => $id ,'vFare' => $this->input->post('ddlFare'),
						'nFareCode' => $this->input->post('ddlFareCode'),
						'vFCFare' => $this->input->post('txtFcFare'),'vFCTax' => $this->input->post('txtFcTax'),
						'vFaceValue' => $this->input->post('txtFace'),'vMKTFare' => $this->input->post('txtMktFare'),
						'nComm' => $this->input->post('txtCommTax'),'nCommAmt' => $this->input->post('txtCommAmt'),
						'nGSAComm' => $this->input->post('txtGSATax'),'nGSAAmt' => $this->input->post('txtGSAAmt'),
						'nORC' => $this->input->post('txtORCTax'),'nORCAmt' => $this->input->post('txtORCAmt'),
						'vTax1' => $this->input->post('txtFcTax1'),'vTax2' => $this->input->post('txtFcTax2'),
						'vTax3' => $this->input->post('txtFcTax3'),'vTaxTotal' => $this->input->post('txtTotalTax'),
						'nNetVendor' => $this->input->post('txtNetVendor'),'vRemarks' => $this->input->post('txtRemarks'),
						'nAcc' => $this->input->post('ddlAccname'),'nAccHead' => $this->input->post('ddlAccHead'),
						'vSalesAmt' => $this->input->post('txtSalesAmt'),'vPassengerId' => $this->input->post('txtPassengerId'),
						'vPassengerContact' => $this->input->post('txtPassengerContact'));
		$this->db->insert('{PRE}TicketFareDetails', $data);

	   //insert into Ticket Billing Details table
        $data 	= array('nTicketId' => $id ,'vModeofpay' => $this->input->post('ddlMode'),
                        'nCustomerAc' => $this->input->post('ddlCustomerAc'),
                        'nAgentAc' => $this->input->post('ddlAgentAc'),'vLPO' => $this->input->post('txtLPO'),
                        'vSalesAmt' => $this->input->post('txtSalesAmt1'),'vDiscount' => $this->input->post('txtDiscount'),
                        'vDiscountAmt' => $this->input->post('txtDiscountAmt'),'vAgentAmt' => $this->input->post('txtAgentAmt'),
                        'vCollected' => $this->input->post('txtCollected'),'vServicePercent' => $this->input->post('txtServicePerc'),
                        'vServiceAmt' => $this->input->post('txtServiceAmt'));
		$this->db->insert('{PRE}TicketBillingDetails', $data);
		
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
			redirect(site_url("ticket_booking/editTicketbooking/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("ticket_booking/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
	}//end class
	
	function updateTicketbooking()
	{
		$tktNo = @split("-",$this->input->post('ddlTktname'));
		
	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
						'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),
						'nAirline' => $this->input->post('ddlAirline'),'vDocNumber' => $this->input->post('txtDocNumber'),
		                'vTicketNumber' => $tktNo[0],'nGDS' => $this->input->post('ddlGDS'),
						'vType' => $this->input->post('ddlType'),'vIssuedBy' => $this->input->post('ddlIssued'),
						'vBookedBy' => $this->input->post('ddlBooked'),'vPassenger' => $this->input->post('txtPassenger'),
						'vOriginalTkt' => $this->input->post('txtOriginalTkt'),
						'vGdspnr' => $this->input->post('txtGdsPNR'),'vAirpnr' => $this->input->post('txtAirPNR'),
						'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
						'vActive' => $this->input->post('chkActive'),'vTicktManual' => $this->input->post('txtTktName'));
		$this->db->where('nTId',$this->input->post('nId'));
		$this->db->update('{PRE}TicketBooking', $data); 
		
		$id	= $this->input->post('nId');
		
	    //update ticket details table
		$data 	= array('nTicketId' => $id ,'vCarrier1' => $this->input->post('txtCarrier1'),
						'vFlight1' => $this->input->post('txtFlightNo1'),
						'vClass1' => $this->input->post('txtClass1'),'dTravelDate1' => date('Y-m-d',strtotime($this->input->post('txtTravelDate1'))),
						'vSector1' => $this->input->post('txtSector1'),'vSector12' => $this->input->post('txtSector12'),
						'vStatus1' => $this->input->post('txtStatus1'),'vTimeDept1' => $this->input->post('txtTimeDep1'),
						'vTimeArr1' => $this->input->post('txtTimeArr1'),'vDay1' => $this->input->post('txtDay1'),
						'vCarrier2' => $this->input->post('txtCarrier2'),'vFlight2' => $this->input->post('txtFlightNo2'),
						'vClass2' => $this->input->post('txtClass2'),'dTravelDate2' => date('Y-m-d',strtotime($this->input->post('txtTravelDate2'))),
						'vSector2' => $this->input->post('txtSector2'),'vSector22' => $this->input->post('txtSector22'),
						'vStatus2' => $this->input->post('txtStatus2'),'vTimeDept2' => $this->input->post('txtTimeDep2'),
						'vTimeArr2' => $this->input->post('txtTimeArr2'),'vDay2' => $this->input->post('txtDay2'),
						'vCarrier3' => $this->input->post('txtCarrier3'),'vFlight3' => $this->input->post('txtFlightNo3'),
						'vClass3' => $this->input->post('txtClass3'),'dTravelDate3' => date('Y-m-d',strtotime($this->input->post('txtTravelDate3'))),
						'vSector3' => $this->input->post('txtSector3'),'vSector33' => $this->input->post('txtSector33'),
						'vStatus3' => $this->input->post('txtStatus3'),'vTimeDept3' => $this->input->post('txtTimeDep3'),
						'vTimeArr3' => $this->input->post('txtTimeArr3'),'vDay3' => $this->input->post('txtDay3'),
						'vCarrier4' => $this->input->post('txtCarrier4'),'vFlight4' => $this->input->post('txtFlightNo4'),
						'vClass4' => $this->input->post('txtClass4'),'dTravelDate4' => date('Y-m-d',strtotime($this->input->post('txtTravelDate4'))),
						'vSector4' => $this->input->post('txtSector4'),'vSector44' => $this->input->post('txtSector44'),
						'vStatus4' => $this->input->post('txtStatus4'),'vTimeDept4' => $this->input->post('txtTimeDep4'),
						'vTimeArr4' => $this->input->post('txtTimeArr4'),'vDay4' => $this->input->post('txtDay4'),
						'vSegment' => $this->input->post('txtSegment'),'vDestination' => $this->input->post('txtDestination'));
		$this->db->where('nTicketId',$id);
		$this->db->update('{PRE}TicketDetails', $data); 

		if($this->input->post('txtConjTkt') != '')
		{
			//update ticket booking master table
			$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
							'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d'),
							'nAirline' => $this->input->post('ddlAirline'),'vDocNumber' => $this->input->post('txtDocNumber'),
							'vTicketNumber' => $this->input->post('txtConjTkt'),'nGDS' => $this->input->post('ddlGDS'),
							'vType' => $this->input->post('ddlType'),'vIssuedBy' => $this->input->post('ddlIssued'),
							'vBookedBy' => $this->input->post('ddlBooked'),'vPassenger' => $this->input->post('txtPassenger'),
							'vOriginalTkt' => $this->input->post('txtOriginalTkt'),
							'vGdspnr' => $this->input->post('txtGdsPNR'),'vAirpnr' => $this->input->post('txtAirPNR'),
							'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
							'vActive' => $this->input->post('chkActive'),'vConjStatus' => 'No',
							'vConjTkt' => $id);
			$this->db->where('nTId',$this->input->post('nCId'));
			$this->db->update('{PRE}TicketBooking', $data); 
			$conjId	= $this->input->post('nCId');
			
		   //update Ticket Conjuction Details into ticket table
		    $data 	= array('nTicketId' => $conjId ,'vCarrier1' => $this->input->post('txtCarrierTkt1'),
							'vFlight1' => $this->input->post('txtFlightNoTkt1'),
							'vClass1' => $this->input->post('txtClassTkt1'),
							'dTravelDate1' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt1'))),
							'vSector1' => $this->input->post('txtSectorTkt1'),'vSector12' => $this->input->post('txtSectorTkt12'),
							'vStatus1' => $this->input->post('txtStatusTkt1'),'vTimeDept1' => $this->input->post('txtTimeDepTkt1'),
							'vTimeArr1' => $this->input->post('txtTimeArrTkt1'),'vDay1' => $this->input->post('txtDayTkt1'),
							'vCarrier2' => $this->input->post('txtCarrierTkt2'),'vFlight2' => $this->input->post('txtFlightNoTkt2'),
							'vClass2' => $this->input->post('txtClassTkt2'),
							'dTravelDate2' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt2'))),
							'vSector2' => $this->input->post('txtSectorTkt2'),'vSector22' => $this->input->post('txtSectorTkt22'),
							'vStatus2' => $this->input->post('txtStatusTkt2'),'vTimeDept2' => $this->input->post('txtTimeDepTkt2'),
							'vTimeArr2' => $this->input->post('txtTimeArrTkt2'),'vDay2' => $this->input->post('txtDayTkt2'),
							'vCarrier3' => $this->input->post('txtCarrierTkt3'),'vFlight3' => $this->input->post('txtFlightNoTkt3'),
							'vClass3' => $this->input->post('txtClassTkt3'),
							'dTravelDate3' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt3'))),
							'vSector3' => $this->input->post('txtSectorTkt3'),'vSector33' => $this->input->post('txtSectorTkt33'),
							'vStatus3' => $this->input->post('txtStatusTkt3'),'vTimeDept3' => $this->input->post('txtTimeDepTkt3'),
							'vTimeArr3' => $this->input->post('txtTimeArrTkt3'),'vDay3' => $this->input->post('txtDayTkt3'),
							'vCarrier4' => $this->input->post('txtCarrierTkt4'),'vFlight4' => $this->input->post('txtFlightNoTkt4'),
							'vClass4' => $this->input->post('txtClassTkt4'),
							'dTravelDate4' => date('Y-m-d',strtotime($this->input->post('txtTravelDateTkt4'))),
							'vSector4' => $this->input->post('txtSectorTkt4'),'vSector44' => $this->input->post('txtSectorTkt44'),
							'vStatus4' => $this->input->post('txtStatusTkt4'),'vTimeDept4' => $this->input->post('txtTimeDepTkt4'),
							'vTimeArr4' => $this->input->post('txtTimeArrTkt4'),'vDay4' => $this->input->post('txtDayTkt4'),
							'vSegment' => $this->input->post('txtSegmentCnjTkt'),
							'vDestination' => $this->input->post('txtDestinationCnjTkt'));
			$this->db->where('nTicketId',$conjId);
			$this->db->update('{PRE}TicketDetails', $data);
		}//end if

	   //update ticket fare Details table
		$data 	= array('vFare' => $this->input->post('ddlFare'),
						'nFareCode' => $this->input->post('ddlFareCode'),
						'vFCFare' => $this->input->post('txtFcFare'),'vFCTax' => $this->input->post('txtFcTax'),
						'vFaceValue' => $this->input->post('txtFace'),'vMKTFare' => $this->input->post('txtMktFare'),
						'nComm' => $this->input->post('txtCommTax'),'nCommAmt' => $this->input->post('txtCommAmt'),
						'nGSAComm' => $this->input->post('txtGSATax'),'nGSAAmt' => $this->input->post('txtGSAAmt'),
						'nORC' => $this->input->post('txtORCTax'),'nORCAmt' => $this->input->post('txtORCAmt'),
						'vTax1' => $this->input->post('txtFcTax1'),'vTax2' => $this->input->post('txtFcTax2'),
						'vTax3' => $this->input->post('txtFcTax3'),'vTaxTotal' => $this->input->post('txtTotalTax'),
						'nNetVendor' => $this->input->post('txtNetVendor'),'vRemarks' => $this->input->post('txtRemarks'),
						'nAcc' => $this->input->post('ddlAccname'),'nAccHead' => $this->input->post('ddlAccHead'),
						'vSalesAmt' => $this->input->post('txtSalesAmt'),'vPassengerId' => $this->input->post('txtPassengerId'),
						'vPassengerContact' => $this->input->post('txtPassengerContact'));
		$this->db->where('nTicketId',$id);
		$this->db->update('{PRE}TicketFareDetails', $data);

	   //update billing Details table
		$data 	= array('vModeofpay' => $this->input->post('ddlMode'),
						'nCustomerAc' => $this->input->post('ddlCustomerAc'),
						'nAgentAc' => $this->input->post('ddlAgentAc'),'vLPO' => $this->input->post('txtLPO'),
						'vSalesAmt' => $this->input->post('txtSalesAmt1'),'vDiscount' => $this->input->post('txtDiscount'),
						'vDiscountAmt' => $this->input->post('txtDiscountAmt'),'vAgentAmt' => $this->input->post('txtAgentAmt'),
						'vCollected' => $this->input->post('txtCollected'),'vServicePercent' => $this->input->post('txtServicePerc'),
						'vServiceAmt' => $this->input->post('txtServiceAmt'));
		$this->db->where('nTicketId',$id);
		$this->db->update('{PRE}TicketBillingDetails', $data);
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

        if($this->uri->segment(6) == 'closed')
        {
            $backUrlAddr = trim($this->session->userdata('backURL2'));
        }
        else
        {
            $backUrlAddr = trim($this->session->userdata('backURL'));
        }

        if( $backUrlAddr != '')
        {
          $empcode = $this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$this->input->post('ddlIssued')."' ");
            $data 	  = array('nAirline' => $this->input->post('ddlAirline'),'vNetAmt' => $this->input->post('txtNetVendor'),
                                'vServPercent' => $this->input->post('txtServicePerc'),'vEmployee' => $empcode,
                                'vFare' => $this->input->post('txtFace'),'vTax' => $this->input->post('txtTotalTax'),
                                'vServAmt' => $this->input->post('txtServiceAmt'),'vDisAmt' => $this->input->post('txtDiscountAmt'),
                                'vComm' => $this->input->post('txtCommTax'),'vCommAmt' => $this->input->post('txtCommAmt'),
                                'vSlsAmt' => $this->input->post('txtSalesAmt1'),// 'vCollected' => $this->input->post('txtCollected'),
                                'vAgentAmt' => $this->input->post('txtAgentAmt'));
            $this->db->where('vType','Tkt');
            $this->db->where('vTktNumber',$this->input->post('nId'));
            $this->db->update('{PRE}SalesClosingDetails', $data);

            redirect(site_url($backUrlAddr));
        }
		else
        {
            redirect(site_url("ticket_booking/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }
	}//end function

	//fetch corresponding ticket booking details
	function getCorresTicketbooking()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}TicketBooking WHERE nTId = '".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

	//fetch corresponding ticket details
	function getCorresTicketDetails()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTicketId = '".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

	//fetch corresponding ticket fare details
	function getCorresFareDetails()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}TicketFareDetails WHERE nTicketId = '".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	//fetch corresponding conjuction details
	function getCorresConjuctionDetails($conjId)
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTDId = '".$conjId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

	function getCorresBillingDetails()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}TicketBillingDetails WHERE nTicketId = '".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function deleteTicketbooking()
	{
		$this->db->delete('{PRE}TicketBooking', array('nTId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}TicketDetails', array('nTicketId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}TicketFareDetails', array('nTicketId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}TicketBillingDetails', array('nTicketId' => $this->uri->segment(4)));

		redirect(site_url("ticket_booking/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllTicketbooking()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nTId',$val);
					$this->db->update('{PRE}TicketBooking', $data); 
				}//end foreach
				redirect(site_url("ticket_booking/showlist/".$this->input->post('nId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nTId',$val);
					$this->db->update('{PRE}TicketBooking', $data); 
				}//end foreach
				redirect(site_url("ticket_booking/showlist/".$this->input->post('nId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}TicketBooking', array('nTId' => $val)); 
					$this->db->delete('{PRE}TicketDetails', array('nTicketId' => $val));
					$this->db->delete('{PRE}TicketFareDetails', array('nTicketId' => $val));
                    $this->db->delete('{PRE}TicketBillingDetails', array('nTicketId' => $val));
				}//end foreach
				redirect(site_url("ticket_booking/showlist/".$this->input->post('nId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	//fetching single details
	function showDetails($ticketId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT tb.*,td.*,tfd.*,d.vTitle as divName,l.vTitle as locName,
												t.vTitle as transName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
											 gds.vName as gdsName, 
												CASE 
													WHEN tb.vType = 'N' THEN 'Normal'
												WHEN tb.vType = 'V' THEN 'Void'
													WHEN tb.vType = 'C' THEN 'Conjuction'
												ELSE 'Re issue' END as tbType
											  FROM {PRE}TicketBooking tb LEFT JOIN {PRE}Division d ON d.nId = tb.nDivision 
												  LEFT JOIN {PRE}Location l ON l.nId = tb.nLocation LEFT JOIN 
											  {PRE}Transactions t ON t.nId = tb.nTransaction
												 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId
											  LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline LEFT JOIN {PRE}TicketDetails td 
											     ON tb.nTId = td.nTicketId LEFT JOIN {PRE}TicketFareDetails tfd 
											  ON tfd.nTicketId = tb.nTId 
											  WHERE nTId = '".$ticketId."' ORDER BY nTId DESC");

		foreach ($viewAccessList->result() as $key => $row)
		{
			switch($row->vFare)
			{
				case "S" :
					$fare	= 'Special';
				break;
				
				case "N" :
					$fare	= 'Normal';
				break;
			}//end switch
			
			$content = '<table class="reportForm">
			<label><strong>'.TICKETBOOKINGDETAILS.'</strong></label>
			<tr>
				<td><strong>'.DIVISIONNAME.' :</strong></td>
				<td colspan="2">'.$row->divName.'</td>
				
				<td><strong>'.LOCATIONNAME.' :</strong></td>
				<td colspan="3">'.$row->locName.'</td>

				<td><strong>'.TYPE.' :</strong></td>
				<td>'.$row->tbType.'</td>

				<td><strong>'.CUSTDATE.' :</strong></td>
				<td>'.date('d-m-Y',strtotime($row-DEdDate)).'</td>
			</tr>

			<tr>
				<td><strong>'.DOCNUMBER.' :</strong></td>
				<td>'.$row->vDocNumber.'</td>

				<td><strong>'.GDS.' :</strong></td>
				<td>'.$row->gdsName.'</td>

				<td><strong>'.AIRLINENAME.' :</strong></td>
				<td colspan="2">'.$row->airlineNo.' - '.$row->airlineName.'</td>

				<td><strong>'.TICKETNAME.' :</strong></td>
				<td>'.$row->vTicktManual.'</td>

				<td><strong>'.ORIGINALTKT.' :</strong></td>
				<td>'.$row->vOriginalTkt.'</td>
            </tr>

			<tr>
				<td><strong>'.PASSENGER.' :</strong></td>
				<td>'.$row->vPassenger.'</td>
                <td>&nbsp;</td>

				<td><strong>'.ISSUEDBY.' :</strong></td>
				<td>'.$this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vIssuedBy."' ").'</td>

				<td><strong>'.BOOKEDBY.' :</strong></td>
				<td>'.$this->common_model->get_field_name('EmployeeMaster','vName', " WHERE nEId='".$row->vBookedBy."' ").'</td>

				<td><strong>'.GDSPNR.' :</strong></td>
				<td>'.$row->vGdspnr.'</td>
			
				<td><strong>'.AIRPNR.' :</strong></td>
				<td>'.$row->vAirpnr.'</td>
            </tr>

			<tr>
				<td><strong>'.SEGMENT.' :</strong></td>
				<td>'.$row->vSegment.'</td>

				<td><strong>'.DESTINATION.':</strong></td>
				<td>'.$row->vDestination.'</td>
				<td colspan="7">&nbsp;</td>
			</tr>
			
			<tr>
				<td><strong>'.SRNO.'</strong></td>
				<td><strong>'.CARRIER.'</strong></td>
				<td><strong>'.FLIGHTNO.'</strong></td>
				<td><strong>'.TKTCLASS.'</strong></td>
				<td><strong>'.TRAVELDATE.'</strong></td>
				<td><strong>'.SECTOR.'</strong></td>
				<td><strong>&nbsp;</strong></td>
				<td><strong>'.STATUS.'</strong></td>
				<td><strong>'.TIMEDEP.'</strong></td>
				<td><strong>'.TIMEARR.'</strong></td>
				<td><strong>'.DAY.'</strong></td>
			</tr>
			
			<tr>
				<td>1</td>
				<td>'.$row->vCarrier1.'</td>
				<td>'.$row->vFlight1.'</td>
				<td>'.$row->vClass1.'</td>
				<td>'.date('d-m-Y',strtotime($row->dTravelDate1)).'</td>
				<td>'.$row->vSector1.'</td>
				<td>'.$row->vSector12.'</td>
				<td>'.$row->vStatus1.'</td>
				<td>'.$row->vTimeDept1.'</td>
				<td>'.$row->vTimeArr1.'</td>
				<td>'.$row->vDay1.'</td>
			</tr>

			<tr>
				<td>2</td>
				<td>'.$row->vCarrier2.'</td>
				<td>'.$row->vFlight2.'</td>
				<td>'.$row->vClass2.'</td>
				<td>'.date('d-m-Y',strtotime($row->dTravelDate2)).'</td>
				<td>'.$row->vSector2.'</td>
				<td>'.$row->vSector22.'</td>
				<td>'.$row->vStatus2.'</td>
				<td>'.$row->vTimeDept2.'</td>
				<td>'.$row->vTimeArr2.'</td>
				<td>'.$row->vDay2.'</td>
			</tr>

			<tr>
				<td>3</td>
				<td>'.$row->vCarrier3.'</td>
				<td>'.$row->vFlight3.'</td>
				<td>'.$row->vClass3.'</td>
				<td>'.date('d-m-Y',strtotime($row->dTravelDate3)).'</td>
				<td>'.$row->vSector3.'</td>
				<td>'.$row->vSector33.'</td>
				<td>'.$row->vStatus3.'</td>
				<td>'.$row->vTimeDept3.'</td>
				<td>'.$row->vTimeArr3.'</td>
				<td>'.$row->vDay3.'</td>
			</tr>

			<tr>
				<td>4</td>
				<td>'.$row->vCarrier4.'</td>
				<td>'.$row->vFlight4.'</td>
				<td>'.$row->vClass4.'</td>
				<td>'.date('d-m-Y',strtotime($row->dTravelDate4)).'</td>
				<td>'.$row->vSector4.'</td>
				<td>'.$row->vSector44.'</td>
				<td>'.$row->vStatus4.'</td>
				<td>'.$row->vTimeDept4.'</td>
				<td>'.$row->vTimeArr4.'</td>
				<td>'.$row->vDay4.'</td>
			</tr>

			<tr>
				<td colspan="11"><strong>'.CONJUCTIONDETAILS.'</strong></td>
			</tr>

			<tr>
				<td><strong>'.CONJUCTIONTKT.' :</strong></td>
				<td colspan="10">'.$row->vConjTkt.'</td>
			</tr>';

			$conjId	     = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vConjTkt = '".$row->nTId."'");

			$showConjVal = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTDId = '".$conjId."'");
			foreach ($showConjVal->result() as $key1 => $row1)
			{
				$content .= '
							<tr>
								<td><strong>'.SRNO.'</strong></td>
								<td><strong>'.CARRIER.'</strong></td>
								<td><strong>'.FLIGHTNO.'</strong></td>
								<td><strong>'.TKTCLASS.'</strong></td>
								<td><strong>'.TRAVELDATE.'</strong></td>
								<td><strong>'.SECTOR.'</strong></td>
								<td><strong>&nbsp;</strong></td>
								<td><strong>'.STATUS.'</strong></td>
								<td><strong>'.TIMEDEP.'</strong></td>
								<td><strong>'.TIMEARR.'</strong></td>
								<td><strong>'.DAY.'</strong></td>
							</tr>
							
							<tr>
								<td>1</td>
								<td>'.$row1->vCarrier1.'</td>
								<td>'.$row1->vFlight1.'</td>
								<td>'.$row1->vClass1.'</td>
								<td>'.date('d-m-Y',strtotime($row1->dTravelDate1)).'</td>
								<td>'.$row1->vSector1.'</td>
								<td>'.$row1->vSector12.'</td>
								<td>'.$row1->vStatus1.'</td>
								<td>'.$row1->vTimeDept1.'</td>
								<td>'.$row1->vTimeArr1.'</td>
								<td>'.$row1->vDay1.'</td>
							</tr>
				
							<tr>
								<td>2</td>
								<td>'.$row1->vCarrier2.'</td>
								<td>'.$row1->vFlight2.'</td>
								<td>'.$row1->vClass2.'</td>
								<td>'.date('d-m-Y',strtotime($row1->dTravelDate2)).'</td>
								<td>'.$row1->vSector2.'</td>
								<td>'.$row1->vSector22.'</td>
								<td>'.$row1->vStatus2.'</td>
								<td>'.$row1->vTimeDept2.'</td>
								<td>'.$row1->vTimeArr2.'</td>
								<td>'.$row1->vDay2.'</td>
							</tr>
				
							<tr>
								<td>3</td>
								<td>'.$row1->vCarrier3.'</td>
								<td>'.$row1->vFlight3.'</td>
								<td>'.$row1->vClass3.'</td>
								<td>'.date('d-m-Y',strtotime($row1->dTravelDate3)).'</td>
								<td>'.$row1->vSector3.'</td>
								<td>'.$row1->vSector33.'</td>
								<td>'.$row1->vStatus3.'</td>
								<td>'.$row1->vTimeDept3.'</td>
								<td>'.$row1->vTimeArr3.'</td>
								<td>'.$row1->vDay3.'</td>
							</tr>
				
							<tr>
								<td>4</td>
								<td>'.$row1->vCarrier4.'</td>
								<td>'.$row1->vFlight4.'</td>
								<td>'.$row1->vClass4.'</td>
								<td>'.date('d-m-Y',strtotime($row1->dTravelDate4)).'</td>
								<td>'.$row1->vSector4.'</td>
								<td>'.$row1->vSector44.'</td>
								<td>'.$row1->vStatus4.'</td>
								<td>'.$row1->vTimeDept4.'</td>
								<td>'.$row1->vTimeArr4.'</td>
								<td>'.$row1->vDay4.'</td>
							</tr>';
			}//end foreach

			$content .= '<tr>
				<td><strong>'.SEGMENT.' :</strong></td>
				<td>'.$showConjVal->vCnjSegment.'</td>

				<td><strong>'.DESTINATION.' :</strong></td>
				<td>'.$showConjVal->vCnjDestination.'</td>

				<td><strong>'.REMARKS.' :</strong></td>
				<td>'.$row->vRemarks.'</td>
				<td colspan="5">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="11"><strong>'.PASSENGERCONTACT.'</strong></td>
			</tr>

			<tr>
				<td><strong>'.PASSENGERID.'</strong></td>
				<td>'.$row->vPassengerId.'</td>
				<td><strong>'.PASSENGERCONTACT.'</strong></td>
				<td>'.$row->vPassengerContact.'</td>
				<td colspan="7">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="11"><strong>'.FAREDETAILS.'</strong></td>
			</tr>

			<tr>
				<td><strong>'.FARE.' :</strong></td>
				<td>'.$fare.'</td>

				<td><strong>'.TKTFARECODE.' :</strong></td>
				<td>'.$row->vFareCode.'</td>

				<td><strong>'.FACEFARE.' :</strong></td>
				<td>'.$row->vFaceValue.'</td>
				
				<td><strong>'.MARKETFARE.' :</strong></td>
				<td>'.$row->vMKTFare.'</td>

				<td><strong>'.TAX.' :</strong></td>
				<td>'.$row->vTax1.'</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
				<td><strong>'.TAX.' :</strong></td>
				<td>'.$row->vTax2.'</td>

				<td><strong>'.TAX.' :</strong></td>
				<td>'.$row->vTax3.'</td>

				<td><strong>'.TOTALTAX.' :</strong></td>
				<td>'.$row->vTaxTotal.'</td>

				<td><strong>'.FCFARE.' :</strong></td>
				<td>'.$row->vFCFare.'</td>
			
				<td><strong>'.FCTAX.' :</strong></td>
				<td>'.$row->vFCTax.'</td>
				<td>&nbsp;</td>
			</tr>
            <tr>
				<td><strong>'.CURRNAME.' :</strong></td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle', " WHERE nId='".$row->nCurrency."' ").'</td>
				
				<td><strong>'.CURRRATE.' :</strong></td>
				<td>'.$this->common_model->get_field_name('Currency','vRate', " WHERE nId='".$row->nCurrency."' ").'</td>

				<td><strong>'.COMM.' :</strong></td>
				<td>'.$row->nComm.'</td>
				
				<td><strong>'.COMMAMT.' :</strong></td>
				<td>'.$row->nCommAmt.'</td>

				<td><strong>'.GSAPERCENT.' :</strong></td>
				<td>'.$row->nGSAComm.'</td>
				<td>&nbsp;</td>
		    </tr>

			<tr>
				<td><strong>'.COMMAMT.' :</strong></td>
				<td>'.$row->nGSAAmt.'</td>

				<td><strong>'.ORCPERCENT.' :</strong></td>
				<td>'.$row->nORC.'</td>

				<td><strong>'.COMMAMT.' :</strong></td>
				<td>'.$row->nORCAmt.'</td>
			
				<td><strong>'.SALESAMT.' :</strong></td>
				<td>'.$row->vSalesAmt.'</td>
			
				<td><strong>'.ACCCODENAME.':</strong></td>
				<td>'.$this->common_model->get_field_name('VendorMaster','vName',' WHERE nVId = "'.$row->nAcc.'"').'( '.$this->common_model->get_field_name('VendorMaster','vCode',' WHERE nVId = "'.$row->nAcc.'"').' )'.'</td>
                <td>&nbsp;</td>
            </tr>

			<tr>
				<td><strong>'.ACCHEAD.':</strong></td>
				<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nAccHead.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->nAccHead.'"').' )'.'</td>

				<td><strong>'.NETTOVENDOR.':</strong></td>
				<td>'.$row->nNetVendor.'</td>
				<td colspan="7">&nbsp;</td>
			</tr>
		  </table>';
		}//end foreach
	    return($content);
	}//end function
	
	function getFareType()
	{
		$fareTyperow = array("N" => "Normal", "S" => "Special");
		return($fareTyperow);
	}//end function
	
	function getModeType()
	{
		$modeTyperow = array("Cr" => "Credit", "Ca" => "Cash", "KN" => "Card");
		return($modeTyperow);
	}//end function

	//search stock entry ticket
	function searchActiveTicketStock($searchKey)
	{
		$typeGroupRow  = array();
		$extaCondition = " AND (vTicketNumber LIKE ('%".$searchKey."%') OR vTicketNumber LIKE ('% ".$searchKey."%') OR vTicketNumber
												 LIKE ( '%".$searchKey."%'))";													
									 		  
		if(trim($searchKey) == 0)
		{
			$extaCondition = '';
		}//end if
		
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}TicketBooking WHERE vActive = '1' ".$extaCondition." 
													  ORDER BY nTId DESC");										  
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row->vTicketNumber;
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

	//search airline details	
	function searchActiveAirline()
	{
		$typeGroupRow  = array();
		$extaCondition = " AND (vAirlineNo LIKE ('%".$searchKey."%') OR vAirlineNo LIKE ('% ".$searchKey."%') OR vAirlineNo 
												 LIKE ( '%".$searchKey."%') OR vTitle LIKE ('%".$searchKey."%') OR vTitle 
												 	LIKE ('% ".$searchKey."%') OR vTitle LIKE ( '%".$searchKey."%'))";
									 		  
		if(trim($searchKey) == 0)
		{
			$extaCondition = '';
		}//end if
		
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}Airline WHERE vActive = '1' ".$extaCondition." ORDER BY nArId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row->vAirlineNo.'-'.$row->vTitle;
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

    //view conjuction details
    function viewConjuctionDetails()
    {
		$this->load->view('ticket_booking/show_conjuction');
    }//end function

	function getSelectedTicketDetails($ticketId)
	{
		$ticketRow = array();
		$ticket    = $this->db->query("SELECT tb.*,td.*,tfd.*,tbd.*,
												t.vTitle as transName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,
											 gds.vName as gdsName, 
												CASE 
													WHEN tb.vType = 'N' THEN 'Normal'
												WHEN tb.vType = 'V' THEN 'Void'
													WHEN tb.vType = 'C' THEN 'Conjuction'
												ELSE 'Re issue' END as tbType
											  FROM {PRE}TicketBooking tb LEFT JOIN 
											  {PRE}Transactions t ON t.nId = tb.nTransaction
												 LEFT JOIN {PRE}Gds gds ON tb.nGDS = gds.nGId
											  LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline LEFT JOIN {PRE}TicketDetails td 
											     ON tb.nTId = td.nTicketId LEFT JOIN {PRE}TicketFareDetails tfd 
											  ON tfd.nTicketId = tb.nTId LEFT JOIN {PRE}TicketBillingDetails tbd 
											    ON tbd.nTicketId = tb.nTId
											  WHERE nTId = '".$ticketId."' ORDER BY nTId DESC");
		if ($ticket->num_rows() > 0)
		{
			$ticketRow = $ticket->row();
		}//end if
		return ($ticketRow);
	}//end function

	function getSelectedConjuctionDetails($ticketId)
	{
		$cnjticketRow = array();
		$cnjticket    = $this->db->query("SELECT * FROM {PRE}TicketDetails WHERE nTDId = '".$ticketId."'
											   ORDER BY nTDId DESC");
		if ($cnjticket->num_rows() > 0)
		{
			$cnjticketRow = $cnjticket->row();
		}//end if
		return ($cnjticketRow);
	}//end foreach
	
	//searching by airline name or code/ticket number/invoice id/issued by/passenger name/from and to date/accounts
	function getFilterList($extraCheck)
	{
		$adminGroupRow        = array();
        $sqlTicketfilterQuery = "SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
        airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*,sed.*
                                            FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails td 
											  ON tb.nTId = td.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId LEFT JOIN
                                            {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId
											LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = tb.vTicketNumber
											   WHERE tb.vConjStatus = 'Yes' AND
                         tb.nLocation IN ". $this->session->userdata('accessLoc') ." 

											".$extraCheck." 
											ORDER BY  YEAR(tb.dDate) DESC, MONTH(tb.dDate) DESC, DAY(tb.dDate) DESC, tb.nTId DESC LIMIT ".$this->common_model->_offset." , 
										      ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTicketfilterQuery;	
		$adminGroupList 		= $this->db->query($sqlTicketfilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT tb.*,td.*,ar.vTitle as airlineName,ar.vAirlineNo as
		airlineNo,g.vName as gdsName,tb.vTicketNumber,tb.vPassenger,em.*
                                            FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails td 
											  ON tb.nTId = td.nTicketId LEFT JOIN {PRE}Airline ar
                                            ON ar.nArId = tb.nAirline LEFT JOIN {PRE}Gds g ON tb.nGDS = g.nGId LEFT JOIN
                                            {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId WHERE tb.vConjStatus = 'Yes'
                                            AND tb.nLocation IN ". $this->session->userdata('accessLoc') ." 

											".$extraCheck." ORDER BY tb.nTId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getNextTicket($tktNo,$start,$separator,$end)
	{
		$tktArray    = array();
		
		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}TicketBooking WHERE 
											vTicketNumber = '".$tktNo."' ORDER BY nTId DESC 
												LIMIT 0,1");
											
		if($tktSql->num_rows() > 0)
		{
			foreach($tktSql->result() as $tkey => $trow)
			{
				if($separator == ","):
					if($trow->vTicktManual == $start)
						$nextNo = $end;
					else
						$nextNo = $start;
				else:
					for($i = $start;$i<=$end;$i++)
					{
						if(trim($trow->vTicktManual) == $i):
							$nextNo = $i+1;
							break;
						endif;
					}//end for loop
				endif;
			}//end foreach
		}//end if
		else
		{
			$nextNo = $start;
		}//end else
		
		return ($nextNo);
	}//end function
	
	function getAllTicket($tktNo,$start,$separator,$end)
	{
		$tktArray    = array();
		
		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}TicketBooking WHERE 
											vTicketNumber = '".$tktNo."' ORDER BY nTId DESC");
											
		if($tktSql->num_rows() > 0)
		{
			foreach($tktSql->result() as $tkey => $trow)
			{
				$tktArray[] = $trow->vTicktManual;
			}//end foreach
		}//end if
		
		return (@join(",",$tktArray));
	}//end function
	
	//fucntion to check active value
	function chkActiveVal($id)
	{
		foreach($_SESSION['tktChkVal'] as $key => $val):
			$chkVal = @split("-",$key);
			
			if($chkVal[0] == $id)
				return $key;
		endforeach;
	}//end function
	
}//end class
?>
