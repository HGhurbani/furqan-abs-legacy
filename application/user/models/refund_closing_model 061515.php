<?php
class Refund_closing_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getAllRefund($screenName,$count, $extraCheckRR = '', $extraCheckRC = '')
    {
        //active groups
        $adminGroupRow  = array();
         $sqlPostingQuery = " SELECT rc.nRCId,rc.dDate, rc.vDocNumber, 'CLOSED' as status, 
               'index.php/refund_closing/editRefundClosing/4/e/' as RLink, 
               'index.php/refund_closing/deleteRefundClosing/4/' as delLink, 
               rcd.nDrAcc as account, rcd.vTable as vTable,
               'C' as vType,  rcd.nAirline as airline,rcd.nTicketNumber as nTktNum, rrd.vNewOld,rrd.vTktType,
               rrd.vIssuedBy, rr.dDate as refDate, rr.nGDS,
               rrd.vPassenger,  rcd.vPaidAmt as paidAmt,
               rcd.vNetAmt as vNetAmt, rcd.vServAmt as profit
               FROM {PRE}RefundRegister rr 
               LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId
               LEFT JOIN {PRE}RefundClosingDetails rcd ON rrd.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd.vTktType 
               INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

               LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = rcd.nTicketNumber
               LEFT JOIN {PRE}XoBooking xo ON xo.nXId = rcd.nTicketNumber 
               LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId = rcd.nTicketNumber
               LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
               LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
               LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = rcd.nDrAcc

               WHERE
               rr.nLocation IN ". $this->session->userdata('accessLoc') ." " .  $extraCheckRC . " 
               GROUP BY rcd.nId ORDER BY rc.dDate ";

         $sqlRegisterQuery = " SELECT rr.nRId as nRCId,'' as dDate, rr.vDocNumber,'OPEN' as status, 
              'index.php/refund_closing/editRefund/4/e/' as RLink , 
              'index.php/refund_closing/deleteRefund/4/' as delLink , 
              SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', 2 ),'-',1) AS account,
              SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', -1 ),'-',2) as vTable,
              rr.vType, rrd.vAirline as airline, rrd.nTktNum as nTktNum, rrd.vNewOld, rrd.vTktType,
              rrd.vIssuedBy, rr.dDate as refDate, rr.nGDS,
              rrd.vPassenger,rrd.vPaidTo as paidAmt, 
              rrd.vNetAmt2 as vNetAmt, rrd.vServiceAmt2 as profit
              FROM {PRE}RefundRegister rr 
              LEFT JOIN {PRE}RefundRegisterBilling rrb ON rr.nRId = rrb.nRefundId
              LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId 

              LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = rrd.nTktNum  
              LEFT JOIN {PRE}XoBooking xo ON xo.nXId = rrd.nTktNum 
              LEFT JOIN {PRE}HotelVoucher hv ON hv.nHVId = rrd.nTktNum 
              LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline
              LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId
              LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId =  SUBSTRING_INDEX(SUBSTRING_INDEX( rrb.nAcCode, '-', 2 ),'-',1)

              WHERE rrd.nTktNum NOT IN (SELECT nTicketNumber FROM {PRE}RefundClosingDetails WHERE vType = rrd.vTktType)
              AND rr.nLocation IN ". $this->session->userdata('accessLoc') ."  " .  $extraCheckRR . "  GROUP BY rrd.nRDId  ";


         switch($screenName)
         {
           case 'open': $sqlRefundQuery = $sqlRegisterQuery; break;
           case 'closed': $sqlRefundQuery = $sqlPostingQuery; break;
           case 'all':  $sqlRefundQuery = $sqlRegisterQuery . " UNION ALL ( ". $sqlPostingQuery . " )";  break;
         }//end switch
         $sqlRefundQuery .= $count == 1 ? '' : " LIMIT " . $this->common_model->_offset . " , " . $this->common_model->_limit;

         $_SESSION['sess_query'] = $sqlRefundQuery;
         $refundGroupList    = $this->db->query($sqlRefundQuery);

         if($count == 1)
           return ($refundGroupList->num_rows());

         if ($refundGroupList->num_rows() > 0)
         {
           foreach ($refundGroupList->result() as $key => $row)
           {
             $refundGroupRow[] = $row;
           }//end for each
         }//end if
         return ($refundGroupRow);
    }//end function
	
	/*function getRefundDetails($id){
		
		 //active groups
        $adminGroupRow  		= array();
        $sqlRefundclosingQuery   = "SELECT rc.*,rcd.* FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails
                                    rcd ON rc.nRCId = rcd.nRefundCloseId WHERE rc.nRCId='".$id."' ORDER BY rcd.nId DESC";
        $adminGroupList 		= $this->db->query($sqlRefundclosingQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        }//end if
        return ($adminGroupRow);
		
	}*/
	
	/*function returnRefundNetClosingDetails($rId)
    {
        $refundCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nId ='".$rId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                    break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                    break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                    break;
                }//end switch
                
                $salesCloseDetailsTicketNumber[]    = $row->vNetAmt;
				
				
            }//end for each
        }//end if

        $newVal	 = @join(", ",$refundCloseDetailsTicketNumber);
        return ($newVal);
    }*///end function
	
	/* function returnRefundSaleClosingDetails($rId)
    {
        $refundCloseDetailsTicketNumber = array();
        //$totalSalesAmt =0;
        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nId ='".$rId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                    break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                    break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                    break;
                }//end switch
                
                $refundCloseDetailsTicketNumber[]    = $row->vCollected;
				
            }//end for each
			
			
			
        }//end if
        
        $newVal	 = @join(", ",$refundCloseDetailsTicketNumber);
        return ($newVal);
		
    }*///end function
   
   
   /*function returnRefundProfitClosingDetails($rId)
    {
        $refundCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nId ='".$rId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $refundCloseDetailsTicketNumber[]    = $row->vCollected - $row->vNetAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$refundCloseDetailsTicketNumber);
        return ($newVal);
    }//end function*/
	
	
	
	
	
	
	function getCorresRefundClosing()
	{
		$fetchTitle = $this->db->query("SELECT rc.nRCId,rc.nDivision,rc.vLocation,rc.dDate,rc.vTransMode,rc.vDocNumber,rc.vLPO,rc.nDrAccount,
		                                       rc.nAgentAccount,rc.vCPV,rc.vNewTkt,rc.nIssued,rc.nCurrency,rc.vRate,rc.vTotalNetAmount,rc.vTotalServAmount,
		                                       rc.vTotalServPercent,rc.vTotalAgentAmt,rc.vTotalPaidAmt,rc.nInvoiceStatus,rc.vPassenger,rc.vRemarks,rc.vActive,
										       rc.nUserId,rr.dDate as refDate,nTktNum,rcd.nTicketNumber,rrd.nRefundId,rr.nRId
										FROM {PRE}RefundClosing rc
										Left JOIN {PRE}RefundClosingDetails   rcd ON rc.nRCId = rcd.nRefundCloseId
                                        Left JOIN {PRE}RefundRegisterDetails  rrd ON rrd.nTktNum = rcd.nTicketNumber
										Left JOIN {PRE}RefundRegister rr	      ON rrd.nRefundId = rr.nRId								   
										WHERE rc.nRCId ='".$this->catId."'");
		        
 
		$showVal	= $fetchTitle->row();
		
		return $showVal;
	}//end function 

    //get Type details
    function getCorresRefundClosingType($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nId,vType FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[$row->nId] = $row->vType;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    function getCorresRefundClosingAirline($nId)
    {
        $userTransRow = array();
        $userTrans    = $this->db->query("SELECT nId,nAirline FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$nId."'");

        if ($userTrans->num_rows() > 0)
        {
            foreach ($userTrans->result() as $key=>$row)
            {
                $userTransRow[$row->nId] = $row->nAirline;
            }//end for each
        }//end if
        return (array_filter($userTransRow));
    }//end function

    //get Location  details
    function getAcAccountsLocation($nId)
    {
        $acAccRow = array();
        $acAcc    = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$nId."'");

        if ($acAcc->num_rows() > 0)
        {
            foreach ($acAcc->result() as $key=>$row)
            {
                $acAccRow[] = $row->nLoc;
            }//end for each
        }//end if
        return (array_filter($acAccRow));
    }//end function

    function getCorresRefundClosingAccountsDetails()
    {
        $fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}RefundClosingAccounts WHERE nRefundId='".$this->catId."'");
        $showVal	      = $fetchTitle->row();
        return $showVal;
    }//end function
	
	function getCorresRefundClosingDetails()
	{
		$refundCloseDetailsRow             = array();
		$refundCloseDetailsType            = array();
		$refundCloseDetailsTicketNumber    = array();
		$refundCloseDetailsAirline         = array();
		$refundCloseDetailsORG             = array();
		$refundCloseDetailsNetAmt          = array();
		$refundCloseDetailsServPercent     = array();
		$refundCloseDetailsServAmt         = array();
		$refundCloseDetailsSalesAmt        = array();
        $refundCloseDetailAgentAmt         = array();
        $refundCloseDetailDrAcc            = array();
        $refundCloseDetailLPO              = array();
		$refundCloseDetailsPaidAmt         = array();
		$newValArray          	           = array();
		
		$refundDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$this->catId."'");
		if ($refundDetailsList->num_rows() > 0)
		{
			foreach ($refundDetailsList->result() as $key=>$row)
			{
				$refundCloseDetailsRow[]             = $row->nId;
                $refundCloseDetailsType[]            = $row->vType;
                $refundCloseDetailsTicketNumber[]    = $row->nTicketNumber;
                $refundCloseDetailsAirline[]         = $row->nAirline;
                $refundCloseDetailsORG[]             = $row->vORG;
                $refundCloseDetailsNetAmt[]          = $row->vNetAmt;
                $refundCloseDetailsServPercent[]     = $row->vServPercent;
                $refundCloseDetailsServAmt[]         = $row->vServAmt;
				$refundCloseDetailsSalesAmt[]        = $row->vSlsAmt;
                $refundCloseDetailAgentAmt[]         = $row->vAgentAmt;
                $refundCloseDetailsPaidAmt[]         = $row->vPaidAmt;
                $refundCloseDetailDrAcc[]            = $row->nDrAcc.'-'.$row->vTable;
                $refundCloseDetailLPO[]              = $row->vLPO;
			}//end for each
		}//end if

        $newValArray['sdID']           = $refundCloseDetailsRow;
        $newValArray['sdType']         = $refundCloseDetailsType;
        $newValArray['sdTktNumber']    = $refundCloseDetailsTicketNumber;
        $newValArray['sdAirline']      = $refundCloseDetailsAirline;
        $newValArray['sdORG']          = $refundCloseDetailsORG;
        $newValArray['sdNetAmt']       = $refundCloseDetailsNetAmt;
        $newValArray['sdServPercent']  = $refundCloseDetailsServPercent;
        $newValArray['sdServAmt']      = $refundCloseDetailsServAmt;
		$newValArray['sdSalesAmt']     = $refundCloseDetailsSalesAmt;
        $newValArray['sdAgentAmt']     = $refundCloseDetailAgentAmt;
        $newValArray['sdPaidAmt']      = $refundCloseDetailsPaidAmt;
        $newValArray['sdDrAcc']        = $refundCloseDetailDrAcc;
        $newValArray['sdLPO']          = $refundCloseDetailLPO;

		return ($newValArray);
	}//end function

 	//get account head accounts details
	function getAcAccounts()
	{
		$userTransRow = array();
		$userTrans    = $this->db->query("SELECT vACode FROM {PRE}AccountHeadAccounts ORDER BY nId DESC");
		//echo $this->db->last_query();

		if ($userTrans->num_rows() > 0)
		{
			$userTransRow[0] = SELECT;
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
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubSale FROM 																																																										                                           {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON
										   ahc.nActHId = aca.nId WHERE ah.vActive = '1'
										  AND ahc.vSubSale = 'B' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
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

    function getActiveCustomer()
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($OtherGroupRow,$resultArray);

        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
										   		ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
        return ($resultArray);
    }//end function

    function getAgentAccounts()
    {
        $agentGroup    = $this->db->query("SELECT nAId,vCode,vName FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
        {
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
            {
                $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        } //end if
        else
        {
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

	//search finance
	function searchActiveFinance($searchKey)
	{
		$typeGroupRow  = array();
		$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode
							 LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle
						   LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) AND ahc.vSubSale = 'B'";

		if(trim($searchKey) == 0)
		{
			$extaCondition = '';
		}//end if

		$typeGroup = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
										LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId
									   LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										WHERE ah.vActive = '1' ".$extaCondition." ORDER BY aca.nId DESC");
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

	function getActiveLocations()
	{
		$typeGroupRow    = array();
		$typeGroupRow[0] = SELECT;
		return ($typeGroupRow);
	}//end function

    function getActiveTickets()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

	function getActiveTransactions()
	{
		$transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' ORDER BY nPosition DESC");
		$transGroupRow = array();

		if ($transGroup->num_rows() > 0)
		{
			$transGroupRow[0] = SELECT;
			foreach ($transGroup->result() as $key=>$row)
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

	function getActiveCurrency()
	{
		$currGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' ORDER BY nPosition DESC");
		$currGroupRow = array();

		if ($currGroup->num_rows() > 0)
		{
			$currGroupRow[0] = SELECT;
			foreach ($currGroup->result() as $key => $row)
			{
				$currGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$currGroupRow[0] = NOVALUE;
		}//end else
		return ($currGroupRow);
	}//end function

	function getTicketType()
	{
        $typeGroupRow = array("0" => SELECT, "Tkt" => "TICKET", "Xo" => "XO", "Htl" => "HOTEL");
        return ($typeGroupRow);
	}//end function

	function filterLocation($division)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Location WHERE nDivision = '".$division."' 
													AND vActive='1' 
												  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		if ($adminGroupList->num_rows() > 0)
		{
			//$adminGroupRow[0] = SELECT;
			foreach ($adminGroupList->result() as $key => $row)
			{
				$adminGroupRow[$row->nId] = $row->vTitle;
			}//end for each
		} //end if
		else
		{
			$adminGroupRow[0] = NOVALUE;
		}//end funtion
		return ($adminGroupRow);
	}//end function

    function getTicket($type)
	{
    $location = "'". $this->input->post('ddlLocation'). "'";
    $division = "'". $this->input->post('ddlDivision'). "'";
    if($division == "''" && $location == "''")
    {
      $division = "'%'";
      $location  = "'%'";
    }

        if($type){
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT tb.* FROM {PRE}TicketBooking tb
														 LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = tb.nTId
                                                         LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
													    WHERE rrd.vTktType = 'Tkt' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND tb.nTId NOT IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')
													    AND tb.nTId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt')
													    ORDER BY nTId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd 
                          LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
                          WHERE  rrd.vTktType = 'Tkt' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND  rrd.nTktNum NOT IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----

                    break;

                case "Xo":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT xo.* FROM {PRE}XoBooking xo
                                                        LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = xo.nXId
                                                        LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
													    WHERE rrd.vTktType = 'Xo' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND xo.nXId NOT IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo')
													    AND xo.nXId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo')
													    ORDER BY nXId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd 
                          LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
                          WHERE  rrd.vTktType = 'Xo' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND  rrd.nTktNum NOT IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----
  
                    break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
                                                        LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = hv.nHVId
                                                        LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
													    WHERE rrd.vTktType = 'Htl' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND hv.nHVId NOT IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
													    AND hv.nHVId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl')
													     ORDER BY nHVId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd 
                          LEFT JOIN {PRE}RefundRegister rr ON rr.nRId = rrd.nRefundId
                          WHERE  rrd.vTktType = 'Htl' AND rr.nDivision like " . $division . " AND rr.nLocation like " .$location . " AND  rrd.nTktNum NOT IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----

                    break;
            }//end switch
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else

		return ($adminGroupRow);
	}//end function


    function getRefundTicket($type)
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');
        if($type)
		{
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,tb.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}TicketBooking tb ON scd.vTktNumber = tb.nTId WHERE scd.vType = 'Tkt' AND sc.nDivision = " .$division . " AND sc.vLocation = " .$location ." AND
														 tb.nTId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Tkt')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                break;

                case "Xo":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,xo.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}XoBooking xo ON scd.vTktNumber = xo.nXId WHERE scd.vType = 'Xo'  AND sc.nDivision = " .$division . " AND sc.vLocation = "  .$location ." AND
														 xo.nXId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Xo')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,hv.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}HotelVoucher hv ON scd.vTktNumber = hv.nHVId WHERE scd.vType = 'Htl'  AND sc.nDivision = " .$division . " AND sc.vLocation = "  .$location ." AND
														 hv.nHVId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Htl')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                 break;
            }//end switch
        }//end if
        else
		{
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
    }//end function


  function getDocumentNumber($type = '',$loc = '')
  {
    $type          = $type == '' ? $this->input->post('ddlType') : $type;
    $loc           = $loc == '' ? $this->input->post('ddlLocation') : $loc;


    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$loc."'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}RefundClosing WHERE vTransMode = '".$type."'
        AND vLocation = '".$loc."' ORDER BY CAST(SUBSTRING_INDEX(vDocNumber,'-',-1) AS UNSIGNED)  DESC LIMIT 0,1");
    if($fetchCode->num_rows() > 0):
      $totalNum           = $fetchCode->row();
    $this->txtDocNumber = $totalNum->vDocNumber;
    $docNumber      = @split('-',$this->txtDocNumber);
    $fill         = $this->txtMinDigit;
    $number       = ($docNumber[3]+1);
    $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
    $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
else:
    $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
    $docNumber      = @split('-',$this->txtDocNumber);
    $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
    endif;

    return $countVal;
  }//end function



    function getClosedTicket($type)
    {
        if($type){
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();

                    $adminGroupList = $this->db->query("SELECT tb.* FROM {PRE}TicketBooking tb
														 LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nTktNum = tb.nTId
													    WHERE rrd.vTktType = 'Tkt' AND tb.nTId IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt')
													    AND tb.nTId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Tkt')
													    ORDER BY nTId DESC");

                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd WHERE  rrd.vTktType = 'Tkt' AND  rrd.nTktNum IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Tkt') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----

                    break;

                case "Xo":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT xo.*,sed.* FROM {PRE}XoBooking xo
														  LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = xo.vXoNumber
													    WHERE xo.vActive='1' AND sed.nId = xo.vXoNumber AND xo.nXId IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo')
													    AND xo.nXId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Xo')
													    ORDER BY nXId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd WHERE  rrd.vTktType = 'Xo' AND  rrd.nTktNum IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Xo') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----

                    break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT hv.*,sed.* FROM {PRE}HotelVoucher hv
														  LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = hv.vVoucherId
													    WHERE hv.vActive='1' AND sed.nId = hv.vVoucherId AND hv.nHVId IN
													    (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl')
													    AND hv.nHVId IN (SELECT rrd.nTktNum FROM {PRE}RefundRegisterDetails rrd WHERE rrd.vTktType = 'Htl')
													     ORDER BY nHVId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
// angela start ----
//                    else
//                    {
                      $adminGroupList = $this->db->query(" SELECT * FROM {PRE}RefundRegisterDetails rrd WHERE  rrd.vTktType = 'Htl' AND  rrd.nTktNum IN
                          (SELECT rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd WHERE rcd.vType = 'Htl') AND rrd.vNewOld = 'O' ");
                      if ($adminGroupList->num_rows() > 0)
                      {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                          $adminGroupRow[$row->nTktNum ] = $row->nTktNum;
                        }//end for each
                      } //end if
                      else {
                        $adminGroupRow[0] = NOVALUE;
                      }//end else
//                    }//end else
// end ----

                    break;
            }//end switch
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
    }//end function

	function getCorrActiveLocation($division)
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
												AND nDivision = '".$division."'
                        AND nId IN ".$this->session->userdata('accessDiv') ."
												ORDER BY nPosition DESC");
		$typeGroupRow = array();

		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key => $row)
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

	function getTypeValue()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND nId = '24'                                               ORDER BY nPosition DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key => $row)
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

    function getTicketDetails($tkt,$type)
    {
        $isTkt = 0;
        switch($type){
            case "Tkt":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nTktNum= '".$tkt."' AND vTktType = 'Tkt'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Airline']      = $row->vAirline;
						$typeGroupRow['OrgTkt']       = $row->vOrgTktNum;
                        $typeGroupRow['NetAmt']       = $row->vNetAmt2;
                        $typeGroupRow['collected']    = $row->vPaidTo;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $refundId                     = $row->nRefundId;
                        $typeGroupRow['PassName']     = $row->vPassenger;
                        $typeGroupRow['IssuedBy']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId =".$row->vIssuedBy." ");

                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT tb.nGDS,tb.vPassenger,td.* FROM {PRE}TicketBooking tb
													LEFT JOIN {PRE}TicketDetails td
												  ON tb.nTId = td.nTicketId WHERE tb.nTId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if
                else
                  $isTkt = 1;

                $typeGroup2    = $this->db->query("SELECT nAcCode,nAgentAc FROM {PRE}RefundRegisterBilling WHERE nRefundId = '".$refundId."'");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['DrAcc']  = $row2->nAcCode;
                        $typeGroupRow['AgentAcc']  = $row2->nAgentAc;
                    }//end for each
                }//end if

                break;

            case "Xo":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nTktNum= '".$tkt."'  AND vTktType = 'Xo'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Airline']      = $row->vAirline;
                        $typeGroupRow['OrgTkt']       = $row->vOrgTktNum;
                        $typeGroupRow['NetAmt']       = $row->vNetAmt2;
                        $typeGroupRow['collected']    = $row->vPaidTo;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $refundId                     = $row->nRefundId;
                        $typeGroupRow['PassName']     = $row->vPassenger;
                        $typeGroupRow['IssuedBy']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId =".$row->vIssuedBy." ");

                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId =  '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPass;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if
                else
                  $isTkt = 1;

                $typeGroup2    = $this->db->query("SELECT nAcCode FROM {PRE}RefundRegisterBilling WHERE nRefundId = '".$refundId."'");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['DrAcc']  = $row2->nAcCode;
                    }//end for each
                }//end if

                break;

            case "Htl":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nTktNum= '".$tkt."'  AND vTktType = 'Htl'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Airline']      = $row->vAirline;
                        $typeGroupRow['OrgTkt']       = $row->vOrgTktNum;
                        $typeGroupRow['NetAmt']       = $row->vNetAmt2;
                        $typeGroupRow['collected']    = $row->vPaidTo;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $refundId                     = $row->nRefundId;
                        $typeGroupRow['PassName']     = $row->vPassenger;
                        $typeGroupRow['IssuedBy']     = $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId =".$row->vIssuedBy." ");
                        $typeGroupRow['hotel']        = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId = '".$row1->nHotel."' ");
                        $typeGroupRow['country']      = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCountry."' ");
                        $typeGroupRow['city']         = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCity."' ");


                    }//end for each
                }//end if
                else
                  $isTkt = 1;

                $typeGroup1    = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
													LEFt JOIN {PRE}HotelBillingDetails hbd
												  ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key => $row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector2;
                        $typeGroupRow['Sector4']      = $row1->vSector22;
                        $typeGroupRow['Sector5']      = $row1->vSector3;
                        $typeGroupRow['Sector6']      = $row1->vSector33;
                        $typeGroupRow['Sector7']      = $row1->vSector4;
                        $typeGroupRow['Sector8']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");

                        $typeGroupRow['checkin']      = $row1->dCheckIn;
                        $typeGroupRow['checkout']     = $row1->dCheckOut;
                        $typeGroupRow['nights']       = $row1->vNight;
                        $typeGroupRow['hotel']        = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId = '".$row1->vHotelName."' ");
                        $typeGroupRow['country']      = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCountry."' ");
                        $typeGroupRow['city']         = $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$row1->nCity."' ");
                    }//end for each
                }//end if
                else
                  $isTkt = 1;

                $typeGroup2    = $this->db->query("SELECT nAcCode FROM {PRE}RefundRegisterBilling WHERE nRefundId = '".$refundId."'");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['DrAcc']  = $row2->nAcCode;
                    }//end for each
                }//end if

                break;
        }//end switch
        if($isTkt == 0){

        }//end if
        return ($typeGroupRow);
    }//end function


    function getRefundTicketDetails($tkt,$type)
    {
        switch($type){
            case "Tkt":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,tbd.vSalesAmt as salesVal,tbd.vServicePercent as ServicePerVal FROM {PRE}TicketBooking tb LEFT JOIN
                                                           {PRE}TicketBillingDetails tbd ON tbd.nTicketId=tb.nTId
                                                            LEFT JOIN {PRE}TicketFareDetails tfd ON tfd.nTicketId=tb.nTId
														   WHERE tb.nTId= '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
						$typeGroupRow['OrgTkt']       = $row->vOriginalTkt;
						$typeGroupRow['Remarks']      = $row->vRemarks;
                        $typeGroupRow['Fare']         = $row->vFaceValue;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['CreditAmt']    = $row->nORCAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->salesVal;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAcc;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT tb.nGDS,tb.vPassenger,td.* FROM {PRE}TicketBooking tb
													LEFT JOIN {PRE}TicketDetails td
												  ON tb.nTId = td.nTicketId WHERE tb.nTId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $row1->nGDS;
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Tkt' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
            break;

            case "Xo":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,xbd.vServicePercent as ServicePerVal,(SELECT SUM(xpd.vFCTax)
                                                    FROM {PRE}XoPassDetails xpd WHERE nXoId = '".$tkt."') as FareVal FROM
                                                    {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->FareVal;
                        $typeGroupRow['Tax']          = $row->vGrandToalTax;
                        $typeGroupRow['NetAmt']       = $row->vGrandNetFare;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nORCAmt;
                        $typeGroupRow['CreditAmt']    = 0;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAccName;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId =  '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPass;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Xo' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
            break;

            case "Htl":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,hbd.vServicePercent as ServicePerVal FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
                                							on hbd.nHotelId = hv.nHVId WHERE hv.vActive='1'
														   AND hv.nHVId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key => $row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->vTotalDC;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['CreditAmt']    = 0;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAcCode;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
													LEFt JOIN {PRE}HotelBillingDetails hbd
												  ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key => $row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");

                        $typeGroupRow['checkin']      = $row1->dCheckIn;
                        $typeGroupRow['checkout']     = $row1->dCheckOut;
                        $typeGroupRow['nights']       = $row1->vNight;
                        $typeGroupRow['hotel']        = $row1->vHotelName;
                        $typeGroupRow['country']      = $row1->nCountry;
                        $typeGroupRow['city']         = $row1->nCity;
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Htl' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
           break;
        }//end switch
        return ($typeGroupRow);
    }//end function



    function getActiveMode()
	{
		$typeGroupRow = array("0" => SELECT, "Cr" => "Credit", "Ca" => "Cash");
		return ($typeGroupRow);
	}//end function

	function getInvoiceStatus()
	{
		$modeGroupRow = array("N" => "Normal", "E" => "Edited","C" => "Cancel", "P" => "Paid");
		return ($modeGroupRow);
	}//end function

	function getActiveAirlines()
	{
		$airlineGroup    = $this->db->query("SELECT nArId,vTitle,vAirlineNo FROM {PRE}Airline WHERE vActive = '1' 
												ORDER BY nArId DESC");
		$airlineGroupRow = array();

		if ($airlineGroup->num_rows() > 0)
		{
			$airlineGroupRow[0] = SELECT;
			foreach ($airlineGroup->result() as $key => $row)
			{
				$airlineGroupRow[$row->nArId] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';
			}//end for each
		}//end if
		else
		{
			$airlineGroupRow[0] = NOVALUE;
		}//end else
		return ($airlineGroupRow);
	}//end function

    function getActiveCards()
    {
        $cardGroup    = $this->db->query("SELECT nCId,vName FROM {PRE}Card WHERE vActive = '1' ORDER BY nCId DESC");
        $cardGroupRow = array();

        if ($cardGroup->num_rows() > 0)
        {
            $cardGroupRow[0] = SELECT;
            foreach ($cardGroup->result() as $key => $row)
            {
                $cardGroupRow[$row->nCId] = $row->vName;
            }//end for each
        }//end if
        else
        {
            $cardGroupRow[0] = NOVALUE;
        }//end else
        return ($cardGroupRow);
    }//end function

	function getActiveEmployees()
	{
		$empGroup    = $this->db->query("SELECT nEId,vName,vCode FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY nEId DESC");
		$empGroupRow = array();

		if ($empGroup->num_rows() > 0)
		{
			$empGroupRow[0] = SELECT;
			foreach ($empGroup->result() as $key => $row)
			{
				$empGroupRow[$row->nEId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$empGroupRow[0] = NOVALUE;
		}//end else
		return ($empGroupRow);
	}//end function

	//fetching single details
	function showDetails($refundID)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}RefundClosing WHERE
		                                     nRCId = '".$refundID."' ORDER BY nRCId DESC");
		//single fetch
		$commVal  = $viewAccessList->row();
		$divName  = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$commVal->nDivision."'");
		$locName  = $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$commVal->vLocation."'");

		$content .= '<table class="reportForm">
						<tr>
							<td colspan="2"><strong>'.DIVISIONNAME.'</strong> :&nbsp;&nbsp;'.$divName.'</td>
							<td colspan="2"><strong>'.LOCATIONNAME.'</strong> :&nbsp;&nbsp;'.$locName.'</td>
						    <td colspan="2"><strong>'.CUSTDATE.'</strong> :&nbsp;&nbsp;'.$commVal->dDate.'</td>
							<td colspan="2"><strong>'.SALESTYPE.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('Transactions','vTitle'," WHERE nId = '".$commVal->vTransMode."' ").'</td>
							<td colspan="2"><strong>'.REFUNDNUMBER.'</strong> :&nbsp;&nbsp;'.$commVal->vDocNumber.'</td>
							<td colspan="2"><strong>'.LPO.'</strong> :&nbsp;&nbsp;'.$commVal->vLPO.'</td>
						</tr>

						<tr>
							<td colspan="2"><strong>'.DRACCNAME.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$commVal->nDrAccount."' ").'</td>
						    <td colspan="2"><strong>'.CPV.'</strong> :&nbsp;&nbsp;'.$commVal->vCPV.'</td>
						    <td colspan="2"><strong>'.NEWTKT.'</strong> :&nbsp;&nbsp;'.$commVal->vNewTkt.'</td>
						    <td colspan="2"><strong>'.ISSUEDBY.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId = '".$commVal->nIssued."' ").'</td>
						    <td colspan="4"><strong>'.SUBAGENTAC.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$commVal->nAgentAccount."' ").'</td>
						</tr>

						<tr>
							<td colspan="3"><strong>'.CURRNAME.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$commVal->nCurrency."' ").'</td>
						    <td colspan="9"><strong>'.CURRRATE.'</strong> :&nbsp;&nbsp;'.$commVal->vRate.'</td>
						</tr>

						<tr>
							<td colspan="2"><strong>'.TOTALNET.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalNetAmount.'</td>
							<td colspan="2"><strong>'.TOTALSERP.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalServPercent.'</td>
							<td colspan="2"><strong>'.TOTALSERA.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalServAmount.'</td>
							<td colspan="2"><strong>'.TOTALSAL.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalSalesAmt.'</td>
							<td colspan="2"><strong>'.TOTALAGENT.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalAgentAmt.'</td>
							<td colspan="2"><strong>'.TOTALPAID.'</strong> :&nbsp;&nbsp;'.$commVal->vTotalPaidAmt.'</td>
						</tr>';


        $viewAccessList2  = $this->db->query("SELECT *,CASE WHEN vModeofpay = 'Cr' THEN 'Credit' WHEN vModeofpay = 'Ca' THEN 'Cash' ELSE 'Knet'
		                                    	END as mode FROM {PRE}RefundClosingAccounts WHERE nRefundId = '".$refundID."'");
        foreach ($viewAccessList2->result() as $key2=>$row2)
        {
            $content .= '<tr>
							<td colspan="2"><strong>'.MODEOFPAYMENT.'</strong> :&nbsp;&nbsp;'.$row2->mode.'</td>
							<td colspan="2"><strong>'.CUSTACCNAME.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row2->nCustomerAc."' ").'</td>
						    <td colspan="2"><strong>'.AGENTAC.'</strong> :&nbsp;&nbsp;'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$row2->nAgentAc."' ").'</td>
							<td colspan="2"><strong>'.AGENTAMT.'</strong> :&nbsp;&nbsp;'.$row2->vAgentAmt.'</td>
							<td colspan="2"><strong>'.LPO.'</strong> :&nbsp;&nbsp;'.$row2->vLPO.'</td>
							<td colspan="2"><strong>'.SALESAMT.'</strong> :&nbsp;&nbsp;'.$row2->vSalesAmt.'</td>
						 </tr>
						 <tr>
							<td colspan="2"><strong>'.DISCOUNT.'</strong> :&nbsp;&nbsp;'.$row2->vDiscount.'</td>
							<td colspan="2"><strong>'.DISCOUNTAMT.'</strong> :&nbsp;&nbsp;'.$row2->vDiscountAmt.'</td>
						    <td colspan="2"><strong>'.COLECTED.'</strong> :&nbsp;&nbsp;'.$row2->vCollected.'</td>
							<td colspan="2"><strong>'.SERVICEPERCENT.'</strong> :&nbsp;&nbsp;'.$row2->vServicePercent.'</td>
							<td colspan="4"><strong>'.SERVICEAMT.'</strong> :&nbsp;&nbsp;'.$row2->vServiceAmt.'</td>
						 </tr>';
        }//end foreach
            $content .= '<tr>
                            <td><strong>'.TYPE.'</strong></td>
                            <td><strong>'.TICKETNAME.'</strong></td>
                            <td><strong>'.AIRLINE.'</strong></td>
                            <td><strong>'.ORIGINALTKT.'</strong></td>
                            <td><strong>'.NETAMT.'</strong></td>
                            <td><strong>'.SER.'</strong></td>
                            <td><strong>'.SERAMT.'</strong></td>
                            <td><strong>'.SLSAMT.'</strong></td>
                            <td><strong>'.AGENTNAME.'</strong></td>
                            <td><strong>'.PAIDAMT.'</strong></td>
                            <td><strong>'.DRACCNAME.'</strong></td>
                            <td><strong>'.LPO.'</strong></td>
						</tr>';
						
		$viewAccessList1  = $this->db->query("SELECT *,CASE WHEN vType = 'Tkt' THEN 'Ticket' WHEN vType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = '".$refundID."' ORDER BY nId DESC");
		foreach ($viewAccessList1->result() as $key1=>$row)
		{
            switch($row->type)
            {
                case "Xo" :
                    $this->_TableName   = 'XoBooking';
                    $this->_extraField  = 'vXoNumber';
                    $this->dbId         = 'nXId';
					$this->tktNumbr     = $this->common_model->get_field_name('XoBooking','vXoNumber', " WHERE nXId = '".$row->nTicketNumber."'");
                break;

                case "Ticket" :
                    $this->_TableName   = 'TicketBooking';
                    $this->_extraField  = 'vTicketNumber';
                    $this->dbId         = 'nTId';
					$this->tktNumbr     = $this->common_model->get_field_name('TicketBooking','vTicketNumber', " WHERE nTId = '".$row->nTicketNumber."'");					
                break;

                case "Hotel" :
                    $this->_TableName   = 'HotelVoucher';
                    $this->_extraField  = 'vVoucherId';
                    $this->dbId         = 'nHVId';
					$this->tktNumbr     = $this->common_model->get_field_name('HotelVoucher','vVoucherId', " WHERE nHVId = '".$row->nTicketNumber."'");					
                break;
            }//end switch
			$content .= '<tr>
							<td>'.$row->type.'</td>
							<td>'.$this->common_model->get_field_name('StockEntryDetails','vCode', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','vStartNo', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','vEndNo', " WHERE nId = '".$this->tktNumbr."'").' - '.$this->common_model->get_field_name('StockEntryDetails','nQuantity', " WHERE nId = '".$this->tktNumbr."'").'</td>
							<td>'.$row->nAirline.'</td>
							<td>'.$row->vORG.'</td>
							<td>'.$row->vNetAmt.'</td>
							<td>'.$row->vServPercent.'</td>
							<td>'.$row->vServAmt.'</td>
							<td>'.$row->vSlsAmt.'</td>
							<td>'.$row->vAgentAmt.'</td>
							<td>'.$row->vPaidAmt.'</td>
							<td>'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row->nDrAcc."' ").'</td>
							<td>'.$row->vLPO.'</td>
						 </tr>';
		}//end foreach

		$content .= '</table>';
	    return($content);
	}//end function

 	//get location name
	function getLocationName($locArray)
	{
		$locName       = array();
		//fetch location name
		foreach($locArray as $val)
		{
			$locName[] = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");
		}//end foreach
		return(@join(",",$locName));
	}//end function

 	function getSubledgerNo($financeId)
	{
		$fetchTitle 	  = $this->db->query("SELECT ahc.*,rc.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}RefundClosing rc
												ON rc.nAcCode = ahc.nId WHERE ahc.nActHId = '".$financeId."'");
		$showVal	      = $fetchTitle->row();
		$chkCode		  = $this->getAutomaticCode('RefundClosing',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nRCId');
		return $chkCode;
	}//end function

	function getAutomaticCode($tableName,$code,$auto,$id)
	{
		if($auto == 1)
		{
			$fetchCode = $this->db->query("SELECT count($id) as totCount FROM {PRE}$tableName");
			$totalNum  = $fetchCode->row();
			switch($totalNum->totCount)
			{
				case "0" :
					$countVal = $code;
				break;

				default:
					$countVal = ($totalNum->totCount)+1;
					$countVal = substr($code,0,strlen($code)-1).$countVal;
				break;
			}//end switch
			return($countVal);
		}//end if
		else
		{
			$this->txtCode = '';
		}//end else
	}//end function

	//function to calcualte online rate
	function onlineCurrencyConverstion()
	{
		$valArray = array();

		//make string to be put in API
		$string = "1".$this->selCurrency."=?".$this->defCurrency;

		//Call Google API
		$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;

		//Get and Store API results into a variable
		$result = file_get_contents($google_url);

		//Explode result to convert into an array
		$result = explode('"', $result);

		################################
		# Right Hand Side
		################################
		$converted_amount = explode(' ', $result[3]);
		$conversion = $converted_amount[0];
		//$conversion = preg_replace('/[x00-x08x0B-x1F]/', '', $conversion);
		$conversion = $conversion * $this->fcAmount;
		$conversion = round($conversion, 2);
		//echo $conversion = number_format($conversion, 2, '.', "");

		//Get text for converted currency
		//$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));

		$varArray['exRate'] = round($converted_amount[0],2);
		$varArray['covAmt'] = $conversion;

		//Make right hand side string
		//$rhs = $conversion.$rhs_text;
		return ($varArray);
	}//end function

    function getRate($cur)
    {
        $fetchTitle = $this->db->query("SELECT vSellingRate FROM {PRE}Currency WHERE nId = '".$cur."'");
        $showVal	= $fetchTitle->row();
        $chkRate	= $showVal->vSellingRate;
        return $chkRate;
    }//end function

	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}RefundClosingDetails', array('nId' => $ID));
	}//end function

	//searching by division name or doc number
	function getFilterList($extraCheck)
	{
		$adminGroupRow  = array();
		
		$adminGroupList = $this->db->query("SELECT rc.*,rcd.*,rcd.vLPO as lpo,aha.vTitle as accName,aha.vACode as accCode,
                                             ar.vTitle as airlineName,ar.vAirlineNo as airlineNo,                                            tb.vTicketNumber,xo.vXoNumber,hv.vVoucherId,
                                             tb.vPassenger,xo.vPassenger,hv.vPassenger
                                            FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd 
											 ON rc.nRCId = rcd.nRefundCloseId
                                            LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = rcd.nTicketNumber 
											 LEFT JOIN {PRE}XoBooking xo
										    ON xo.nXId = rcd.nTicketNumber LEFT JOIN {PRE}HotelVoucher hv 
											 ON hv.nHVId = rcd.nTicketNumber
										   	LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = rcd.nDrAcc LEFT JOIN {PRE}Airline ar
                                             ON ar.nArId = rcd.nAirline LEFT JOIN {PRE}EmployeeMaster em ON rc.nIssued = em.nEId
											WHERE rc.vActive = '1'
                      AND rc.vLocation IN ". $this->session->userdata('accessLoc') ." 
                      ".$extraCheck." GROUP BY rcd.nRefundCloseId ORDER BY rc.nRCId DESC
											 LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
//        echo $this->db->last_query();
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
		$tabCount = $this->db->query("SELECT rc.*,rcd.*,rcd.vLPO as lpo,aha.vTitle as accName,aha.vACode as accCode,
										 ar.vTitle as airlineName,ar.vAirlineNo as airlineNo,
									  tb.vTicketNumber,xo.vXoNumber,hv.vVoucherId,
									     tb.vPassenger,xo.vPassenger,hv.vPassenger
									  FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd 
									     ON rc.nRCId = rcd.nRefundCloseId
									  LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = rcd.nTicketNumber LEFT JOIN {PRE}XoBooking xo
										 ON xo.nXId = rcd.nTicketNumber LEFT JOIN {PRE}HotelVoucher hv 
									  ON hv.nHVId = rcd.nTicketNumber
									     LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = rcd.nDrAcc LEFT JOIN {PRE}Airline ar
									  ON ar.nArId = rcd.nAirline LEFT JOIN {PRE}EmployeeMaster em ON rc.nIssued = em.nEId
									     WHERE rc.vActive = '1' 
                       AND rc.vLocation IN ". $this->session->userdata('accessLoc') ." 
                       ".$extraCheck." ORDER BY rc.nRCId DESC");
		return ($tabCount->num_rows());
	}//end function


/*
    function returnAccountDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$item)
            {
                switch($item->vTable)
                {
                    case "A":
                        $account = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$item->nDrAcc."'");
                        $accCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$item->nDrAcc."'");
                        break;
                    case "C":
                        $account = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$item->nDrAcc."'");
                        $accCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '".$item->nDrAcc."'");
                        break;
                    case "V":
                        $account = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$item->nDrAcc."'");
                        $accCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '".$item->nDrAcc."'");
                        break;
                    case "O":
                        $account = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$item->nDrAcc."'");
                        $accCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId = '".$item->nDrAcc."'");
                        break;
                    case "AG":
                        $account = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$item->nDrAcc."'");
                        $accCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '".$item->nDrAcc."'");
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = stripslashes($account).'('.stripslashes($accCode).')';
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnTypeDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $sType = 'Ticket';
                        break;

                    case "Htl":
                        $sType = 'Hotel';
                        break;

                    case "Xo":
                        $sType = 'Xo';
                        break;
                }//end switch
                $salesCloseDetailsTicketNumber[]    = $sType ;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnTktDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'vTicketNumber';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'vXoNumber';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'vVoucherId';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row->nTicketNumber."'");
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnAirlineDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[] = $row->nAirline;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnLpoDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[] = $row->vLPO;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnPaidDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[] = $row->vPaidAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnDateDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $dateDetails = $this->db->query("SELECT dDate FROM {PRE}RefundRegister r LEFT JOIN {PRE}RefundRegisterDetails rd
                                                       ON r.nRId = rd.nRefundId WHERE rd.vTktType ='".$row->vType."' AND
                                                       nTktNum = '".$row->nTicketNumber."'");
                $rowName  = $dateDetails->row();
                $salesCloseDetailsTicketNumber[]    = date('d-m-Y',strtotime($rowName->dDate));
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnPassengerDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'vPassenger';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row->nTicketNumber."'");
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnNetDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $row->vNetAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnSalesDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vType)
                {
                    case "Tkt":
                        $this->_TableName 	 = 'TicketBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nTId';
                        break;

                    case "Xo":
                        $this->_TableName    = 'XoBooking';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nXId';
                        break;

                    case "Htl":
                        $this->_TableName    = 'HotelVoucher';
                        $this->_extraField   = 'dDate';
                        $this->dbId          = 'nHVId';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $row->vSlsAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnProfitDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[] = $row->vNetAmt-$row->vPaidAmt;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function
*/

	function getDocNum($type)
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Transactions WHERE nId = '".$type."'");
		$showVal	= $fetchTitle->row();
		$typeVal	= $showVal->vSampledoc;
		return $typeVal;
	}//end function

	function getModeType()
	{
		$modeTyperow = array("Cr" => "Credit", "Ca" => "Cash", "KN" => "KNET");
		return($modeTyperow);
	}//end function

	function getActiveCustomerAccounts()
	{
		$custaccGroupRow = array();
		$custaccGroup    = $this->db->query("SELECT aca.*,c.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca 
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN 
											    {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
											  LEFT JOIN {PRE}CustomerMaster c ON c.nFId = ahc.nId WHERE ah.vActive = '1' 
											    AND ahc.vSubLedgerType = 'C' AND ahc.vSalesType = '1' OR ahc.vSubSale IN ('C','S','A','B')
											  ORDER BY nId DESC");
		if ($custaccGroup->num_rows() > 0)
		{
			$custaccGroupRow[0] = SELECT;
			foreach ($custaccGroup->result() as $key=>$row)
			{
				$custaccGroupRow[$row->nCId] = $row->vName.' ('.$row->vACode.' )';
			}//end for each
		}//end if
		else
		{
			$custaccGroupRow[0] = NOVALUE;
		}//end else
		return ($custaccGroupRow);
	}//end function 

	function getAgentsAccounts()
	{
		$agentaccGroupRow = array();
		$agentaccGroup    = $this->db->query("SELECT aca.*,a.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca 
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN 
											    {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
											  LEFT JOIN {PRE}Agent a ON a.nFId = ahc.nId WHERE ah.vActive = '1' 
											    AND ahc.vSubLedgerType = 'A' ORDER BY nId DESC");
		if ($agentaccGroup->num_rows() > 0)
		{
			$agentaccGroupRow[0] = SELECT;
			foreach ($agentaccGroup->result() as $key=>$row)
			{
				$agentaccGroupRow[$row->nAId] = $row->vName.' ('.$row->vCode.' )';
			}//end for each
		}//end if
		else
		{
			$agentaccGroupRow[0] = NOVALUE;
		}//end else
		return ($agentaccGroupRow);
	}//end function

    function insertRefundClosing()
    {
        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
            'dDate' => date('Y-m-d',strtotime($this->input->post('txtDate'))),'vTransMode' => $this->input->post('ddlType'),
            'vDocNumber' => $this->input->post('txtDocNumber'),'vLPO' => $this->input->post('txtLpoMain'),
            'nDrAccount' => $this->input->post('ddlDrAccMain'),'vCPV' => $this->input->post('txtCPV'),
            'vNewTkt' => $this->input->post('txtNewTkt'),'nIssued' => $this->input->post('ddlIssued'),
            'nAgentAccount' => $this->input->post('ddlAgentAc'),'nCurrency' => $this->input->post('ddlCurrency'),
            'vRate' => $this->input->post('txtRate'),'vTotalNetAmount' => $this->input->post('txtTotalNetAmount'),
            'vTotalServPercent' => $this->input->post('txtTotalServPercent'),'vTotalServAmount' => $this->input->post('txtTotalServAmount'),
            'vTotalAgentAmt' => $this->input->post('txtTotalAgentAmt'),
            'vTotalPaidAmt' => $this->input->post('txtTotalPaidAmt'),'nInvoiceStatus' => $this->input->post('ddlInvoice'),
            'vPassenger' => $this->input->post('txtPassenger'),
            'vRemarks' => $this->input->post('txtRemarks'),'vActive' => $this->input->post('chkActive'));
        $this->db->insert('{PRE}RefundClosing', $data);

        $id			  	 = $this->db->insert_id();

        $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $ORGTkt      	 = (@array_filter($this->input->post('txtORG')) != '')?$this->input->post('txtORG'):0;
        $netAmt     	 = (@array_filter($this->input->post('txtNetAmt')) != '')?$this->input->post('txtNetAmt'):0;
        $servPercent   	 = (@array_filter($this->input->post('txtServPercent')) != '')?$this->input->post('txtServPercent'):0;
        $servAmt     	 = (@array_filter($this->input->post('txtServAmt')) != '')?$this->input->post('txtServAmt'):0;
        $agentAmt     	 = (@array_filter($this->input->post('txtAgentAmt')) != '')?$this->input->post('txtAgentAmt'):0;
        $paidAmt         = (@array_filter($this->input->post('txtPaidAmt')) != '')?$this->input->post('txtPaidAmt'):0;
        $DrAcc           = (@array_filter($this->input->post('ddlDrAcc')) != '')?$this->input->post('ddlDrAcc'):0;
        $LPO             = (@array_filter($this->input->post('txtLPO1')) != '')?$this->input->post('txtLPO1'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

        //create jv
        $newId = $this->jvInsert($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$this->input->post('txtDocNumber'),date('Y-m-d',strtotime($this->input->post('txtDate'))));

        //insert into refund closing details
        foreach($chkBox as $key => $aitem)
        {
            $drAccount = @split("-",$DrAcc[$key]);

            switch($tktType[$key])
            {
                case "Tkt":
                    $CollectedTkt   += $paidAmt[$key];
                    break;

                case "Htl":
                    $CollectedHtl   += $paidAmt[$key];
                    break;

                case "Xo":
                    $CollectedXo    += $paidAmt[$key];
                    break;
            }//end switch

            switch($drAccount[1])
            {
                case "C":
                    $AcHead = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId='".$drAccount[0]."'");
                    break;

                case "V":
                    $AcHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId='".$drAccount[0]."'");
                    break;

                case "A":
                    $AcHead = 0;
                    break;

                case "O":
                    $AcHead = $this->common_model->get_field_name('Other','nFId'," WHERE nOId='".$drAccount[0]."'");
                    break;

                case "AG":
                    $AcHead = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId='".$drAccount[0]."'");
                    break;
            }

            $data      = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                                'vLPO' => $LPO[$key],'vPaidAmt' => $paidAmt[$key]);
            $this->db->insert('{PRE}RefundClosingDetails', $data);

            $RRId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE vTktType = '". $tktType[$key] ."' AND nTktNum = ".$tktNumber[$key]." ");
            $data = array('vRefundDet'=>date('Y-m-d'));
            $this->db->where('nRId',$RRId);
            $this->db->update('{PRE}RefundRegister', $data);

            $this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$netAmt[$key],$tktType[$key],$tktNumber[$key]);

            $this->jvAgentInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$agentAmt[$key],$tktType[$key],$tktNumber[$key],$this->input->post('ddlAgentAc'));


            $this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$paidAmt[$key],$tktType[$key],$DrAcc[$key],$AcHead);
        }//end foreach

        $this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);
/*
        $data 	= array('nRefundId' => $id ,'nCustomerAc' => $this->input->post('ddlCustomerAc'),
                        'vModeofpay' => $this->input->post('ddlMode'),'nAgentAc' => $this->input->post('ddlAcAgentAc'),
                        'vAgentAmt' => $this->input->post('txtAcAgentAmt'),'vLPO' => $this->input->post('txAcLPO'),
                        'vSalesAmt' => $this->input->post('txtAcSalesAmt'),'vDiscount' => $this->input->post('txtAcDiscount'),
                        'vDiscountAmt' => $this->input->post('txtAcDiscountAmt'),'vCollected' => $this->input->post('txtAcCollected'),
                        'vServicePercent' => $this->input->post('txtAcServicePer'),'vServiceAmt' => $this->input->post('txtAcServiceAmt'));
        $this->db->insert('{PRE}RefundClosingAccounts', $data);
*/
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
            redirect(site_url("refund_closing/editRefundClosing/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if

        if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
        {
            redirect(site_url("refund_closing/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if
    }//end class

    function updateRefundClosing()
    {
        $data = array('nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
            'dDate' => date('Y-m-d',strtotime($this->input->post('txtDate'))),'vTransMode' => $this->input->post('ddlType'),
            'vDocNumber' => $this->input->post('txtDocNumber'),'vLPO' => $this->input->post('txtLpoMain'),
            'nDrAccount' => $this->input->post('ddlDrAccMain'),'vCPV' => $this->input->post('txtCPV'),
            'vNewTkt' => $this->input->post('txtNewTkt'),'nIssued' => $this->input->post('ddlIssued'),
            'nAgentAccount' => $this->input->post('ddlAgentAc'),'nCurrency' => $this->input->post('ddlCurrency'),
            'vRate' => $this->input->post('txtRate'),'vTotalNetAmount' => $this->input->post('txtTotalNetAmount'),
            'vTotalServPercent' => $this->input->post('txtTotalServPercent'),'vTotalServAmount' => $this->input->post('txtTotalServAmount'),
            'vTotalAgentAmt' => $this->input->post('txtTotalAgentAmt'),'nUserId' => $this->session->userdata('AbsAdmin'),
            'vTotalPaidAmt' => $this->input->post('txtTotalPaidAmt'),'nInvoiceStatus' => $this->input->post('ddlInvoice'),
            'vPassenger' => $this->input->post('txtPassenger'),
            'vRemarks' => $this->input->post('txtRemarks'),'vActive' => $this->input->post('chkActive'));

        $this->db->where('nRCId',$this->input->post('nId'));
        $this->db->update('{PRE}RefundClosing', $data);

        $id			     = $this->input->post('nId');
        $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $ORGTkt      	 = (@array_filter($this->input->post('txtORG')) != '')?$this->input->post('txtORG'):0;
        $netAmt     	 = (@array_filter($this->input->post('txtNetAmt')) != '')?$this->input->post('txtNetAmt'):0;
        $servPercent   	 = (@array_filter($this->input->post('txtServPercent')) != '')?$this->input->post('txtServPercent'):0;
        $servAmt     	 = (@array_filter($this->input->post('txtServAmt')) != '')?$this->input->post('txtServAmt'):0;
        $agentAmt     	 = (@array_filter($this->input->post('txtAgentAmt')) != '')?$this->input->post('txtAgentAmt'):0;
        $paidAmt         = (@array_filter($this->input->post('txtPaidAmt')) != '')?$this->input->post('txtPaidAmt'):0;
        $DrAcc           = (@array_filter($this->input->post('ddlDrAcc')) != '')?$this->input->post('ddlDrAcc'):0;
        $LPO             = (@array_filter($this->input->post('txtLPO1')) != '')?$this->input->post('txtLPO1'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;
        $chkAccID	     = $this->input->post('idArray');

        $newId = $this->jvUpdate($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$this->input->post('txtDocNumber'),date('Y-m-d',strtotime($this->input->post('txtDate'))));

        $this->db->where('nSjId',$newId);
        $this->db->where('nSjId',$newId);
        $this->db->delete('{PRE}SystemJvDetails');

        foreach($chkBox as $key => $aitem)
        {
            switch($tktType[$key])
            {
                case "Tkt":
                    $CollectedTkt   += $paidAmt[$key];
                    break;

                case "Htl":
                    $CollectedHtl   += $paidAmt[$key];
                    break;

                case "Xo":
                    $CollectedXo    += $paidAmt[$key];
                    break;
            }//end switch

            $accTktNumber[] = $tktNumber[$key];

            $drAccount = @split("-",$DrAcc[$key]);

            switch($drAccount[1])
            {
                case "C":
                    $AcHead = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId='".$drAccount[0]."'");
                    break;

                case "V":
                    $AcHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId='".$drAccount[0]."'");
                    break;

                case "A":
                    $AcHead = 0;
                    break;

                case "O":
                    $AcHead = $this->common_model->get_field_name('Other','nFId'," WHERE nOId='".$drAccount[0]."'");
                    break;

                case "AG":
                    $AcHead = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId='".$drAccount[0]."'");
                    break;
            }

            if(intval($chkAccID[$key]) != 0)
            {
                $data = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                                'vLPO' => $LPO[$key],'vPaidAmt' => $paidAmt[$key]);
                $this->db->where('nId',$chkAccID[$key]);
                $this->db->update('{PRE}RefundClosingDetails', $data);

                $this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$netAmt[$key],$tktType[$key],$tktNumber[$key]);
            }//end if
            else
            {
                $data = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                                'vLPO' => $LPO[$key],'vPaidAmt' => $paidAmt[$key]);
                $this->db->insert('{PRE}RefundClosingDetails', $data);

                $this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$netAmt[$key],$tktType[$key],$tktNumber[$key]);

            }//end else

            $RRId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE vTktType = '". $tktType[$key] ."' AND nTktNum = ".$tktNumber[$key]." ");
            $data = array('vRefundDet'=>date('Y-m-d'));
            $this->db->where('nRId',$RRId);
            $this->db->update('{PRE}RefundRegister', $data);


                $this->jvAgentInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$agentAmt[$key],$tktType[$key],$tktNumber[$key],$this->input->post('ddlAgentAc'));

            $this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$paidAmt[$key],$tktType[$key],$DrAcc[$key],$AcHead);

        }//end foreach

        $this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);
/*
        $data 	= array('nCustomerAc' => $this->input->post('ddlCustomerAc'),
            'vModeofpay' => $this->input->post('ddlMode'),'nAgentAc' => $this->input->post('ddlAcAgentAc'),
            'vAgentAmt' => $this->input->post('txtAcAgentAmt'),'vLPO' => $this->input->post('txAcLPO'),
            'vSalesAmt' => $this->input->post('txtAcSalesAmt'),'vDiscount' => $this->input->post('txtAcDiscount'),
            'vDiscountAmt' => $this->input->post('txtAcDiscountAmt'),'vCollected' => $this->input->post('txtAcCollected'),
            'vServicePercent' => $this->input->post('txtAcServicePer'),'vServiceAmt' => $this->input->post('txtAcServiceAmt'));
        $this->db->where('nRefundId',$id);
        $this->db->update('{PRE}RefundClosingAccounts', $data);
*/
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
        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else 
          redirect(site_url("refund_closing/showClosed/".$this->uri->segment(3).'/'.$msgVal.$showId));
    }//end function


    function deleterefund()
    {
        $this->db->delete('{PRE}RefundRegister', array('nRId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundRegisterBilling', array('nRefundId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundRegisterDetails', array('nRefundId' => $this->uri->segment(4)));
        redirect(site_url("refund_closing/showlist/" . $this->uri->segment(3) . '/del_suc'));
    }//end function

    function deleterefundClosing()
    {
        $this->db->delete('{PRE}RefundClosingDetails', array('nRefundCloseId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundClosingAccounts', array('nRefundId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundClosing', array('nRCId' => $this->uri->segment(4)));

        $sysId = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId='".$this->uri->segment(4)."' AND vType = 'R'");
        $this->db->delete('{PRE}SystemJvDetails', array('nSjId' => $sysId));
        $this->db->delete('{PRE}SystemJv', array('nId' => $sysId));
        redirect(site_url("refund_closing/showClosed/".$this->uri->segment(3).'/del_suc'));
    }//end function

    function deleteAllRefundClosing()
    {
        switch($this->input->post('ddlAction'))
        {
          case "yes":
            foreach($this->input->post('del') as $val)
            {
              //update into db
              $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
              $this->db->where('nRCId',$val);
              $this->db->update('{PRE}RefundClosing', $data);
            }//end foreach
          redirect(site_url("refund_closing/showlist/".$this->input->post('nCId').'/edit_suc'));
          break;

          case "no":
            foreach($this->input->post('del') as $val)
            {
              //update into db
              $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
              $this->db->where('nRCId',$val);
              $this->db->update('{PRE}RefundClosing', $data);
            }//end foreach
          redirect(site_url("refund_closing/showlist/".$this->input->post('nCId').'/edit_suc'));
          break;

          case "delete":
            foreach($this->input->post('del') as $val)
            {
              $this->db->delete('{PRE}RefundClosingDetails', array('nRefundCloseId' => $val));
              $this->db->delete('{PRE}RefundClosingAccounts', array('nRefundId' => $val));
              $this->db->delete('{PRE}RefundClosing', array('nRCId' => $val));

              $sysId = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId='".$val."' AND vType = 'R'");
              $this->db->delete('{PRE}SystemJvDetails', array('nSjId' => $sysId));
              $this->db->delete('{PRE}SystemJv', array('nId' => $sysId));
            }//end foreach
          redirect(site_url("refund_closing/showlist/".$this->input->post('nCId').'/del_suc'));
          break;
        }//end switch
    }//end function

    function deleteAllRefund()
    {
      if($this->input->post('btnGo') == 'Apply Action'){
        switch ($this->input->post('ddlAction')) {
            case "yes":
                foreach ($this->input->post('del') as $val) {
                    //update into db
                    $data = array('vActive' => '1');
                    $this->db->where('nRId', $val);
                    $this->db->update('{PRE}RefundRegister', $data);
                }//end foreach
                redirect(site_url("refund_closing/showlist/" . $this->input->post('nCId') . '/edit_suc'));
            break;

            case "no":
                foreach ($this->input->post('del') as $val) {
                    //update into db
                    $data = array('vActive' => '0');
                    $this->db->where('nRId', $val);
                    $this->db->update('{PRE}RefundRegister', $data);
                }//end foreach
                redirect(site_url("refund_closing/showlist/" . $this->input->post('nCId') . '/edit_suc'));
            break;

            case "delete":
                foreach ($this->input->post('del') as $val) {
                    $this->db->delete('{PRE}RefundRegister', array('nRId' => $val));
                    $this->db->delete('{PRE}RefundRegisterBilling', array('nRefundId' => $val));
                    $this->db->delete('{PRE}RefundRegisterDetails', array('nRefundId' => $val));
                }//end foreach
                redirect(site_url("refund_closing/showlist/" . $this->input->post('nCId') . '/del_suc'));
            break;
        }//end switch
      }//end if
      else{
        foreach($this->input->post('del') as $val)
        {
          $this->saveToRefundClosingTable($val);
        }//end foreach
        redirect(site_url("refund_closing/showClosed/".$this->input->post('nCId').'/del_suc'));
      }

    }//end function

    //function for jv create
    function jvInsert($id,$division,$location,$invoice,$dateVal)
    {
        //insert into system jv
        $systmJvNo = $invoice;
        $systmJvNo = str_replace('-INV-','-',$systmJvNo);
        $systmJvNo = 'SJV'.$systmJvNo;

        $data      = array('vJVNo' => $systmJvNo,'nSalesCId' => $id,
            'nDivision' => $division,
            'nLocation' => $location,
            'nInvoice' => $invoice,'vType' => 'R',
            'dDate' => date('Y-m-d', strtotime($dateVal)));
        $this->db->insert('{PRE}SystemJv', $data);

        $newId = $this->db->insert_id();

        return ($newId);
    }//end funciton

    function jvUpdate($id,$division,$location,$invoice,$dateVal)
    {
        //update system jv
        $newId     = $this->common_model->get_field_name('SystemJv','nId'," WHERE nSalesCId = '".$id."' AND vType = 'R'");

        //insert into system jv
        $systmJvNo = $invoice;
        $systmJvNo = str_replace('-INV-','-',$systmJvNo);
        $systmJvNo = 'SJV'.$systmJvNo;

        $data      = array('vJVNo' => $systmJvNo,'nSalesCId' => $id,
            'nDivision' => $division,
            'nLocation' => $location,
            'nInvoice' => $invoice,'vType' => 'R',
            'dDate' => date('Y-m-d', strtotime($dateVal)));
        $this->db->where('nId',$newId);
        $this->db->update('{PRE}SystemJv', $data);

        return ($newId);
    }//end funciton



    function jvAgentInsert($id,$newId,$location,$user,$agentAmt,$type,$tNumber,$agentAcc){
	    //get agent payable id
	    if( intval($agentAmt) != 0) {
		    $agPayableId =  $this->common_model->get_field_name('Agent','nFId'," WHERE nAId = '".$agentAcc."'");
		    $agPayableCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $agPayableId ." ");
		    $agPayableAHAId = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $agPayableId ." ");

		    if($type == 'Tkt' && intval($agentAmt) != 0):
			   // 'nAGId' => 27,'nAccHeadId' => 74,'vAcCode' =>32211,'vType' => 'Tkt',
	//		    'nDrAccount' => '74','vTable' => 'A','vClosedId'=>'74-A',
			$data      = array('nSjId' => $newId, 'vNumber' => 0,
			    'nLocation' => $location,
			    'nDrAccount' => '74','vTable' => 'A','vClosedId'=>'74-A',
			    'vDes' => $user,
			    'nDebit' => 0,
			    'nCredit' => $agentAmt);
			$this->db->insert('{PRE}SystemJvDetails', $data);
			$data      = array('nSjId' => $newId, 'vNumber' => 0,
			    'nLocation' => $location,
			    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=> $agentAcc . '-AG',
			    'nAGId' => $agPayableAHAId ,'nAccHeadId' => $agPayableId ,'vAcCode' => $agPayableCode ,'vType' => 'Tkt',
			    'vDes' => $user,
			    'nDebit' => $agentAmt,
			    'nCredit' => 0);
			    //'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
			    //'nAGId' =>  $agentAcc,'nAccHeadId' =>$agPayableId ,'vAcCode' =>$agPayableCode ,'vType' => 'Tkt',
			$this->db->insert('{PRE}SystemJvDetails', $data);
		    endif;

		  if($type == 'Htl' && intval($agentAmt) != 0):
	//		                      'nAGId' => 28,'nAccHeadId' => 84,'vAcCode' =>32310,'vType' => 'Htl',
			$data      = array('nSjId' => $newId, 'vNumber' =>0,
			    'nLocation' => $location,
			    'nDrAccount' => '84','vTable' => 'A','vClosedId'=>'84-A',
			    'vDes' => $user,
			    'nDebit' => 0,
			    'nCredit' => $agentAmt);
			$this->db->insert('{PRE}SystemJvDetails', $data);
			$data      = array('nSjId' => $newId, 'vNumber' => 0,
			    'nLocation' => $location,
			    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
			    'nAGId' => $agPayableAHAId ,'nAccHeadId' => $agPayableId ,'vAcCode' => $agPayableCode ,'vType' => 'Htl',
			    'vDes' => $user,
			    'nDebit' => $agentAmt,
			    'nCredit' => 0);
			    //'nAGId' => 18,'nAccHeadId' => 71,'vAcCode' =>22114,'vType' => 'Htl',
			$this->db->insert('{PRE}SystemJvDetails', $data);
		    endif;
		  if($type == 'Xo' && intval($agentAmt) != 0):
	//		                      'nAGId' => 29,'nAccHeadId' => 93,'vAcCode' =>32412,'vType' => 'Xo',
			$data      = array('nSjId' => $newId, 'vNumber' => 0,
			    'nLocation' => $location,
			    'nDrAccount' => '93','vTable' => 'A','vClosedId'=>'93-A',
			    'vDes' => $user,
			    'nDebit' => 0,
			    'nCredit' => $agentAmt);
			$this->db->insert('{PRE}SystemJvDetails', $data);
			$data      = array('nSjId' => $newId, 'vNumber' => 0,
			    'nLocation' => $location,
			    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
			    'nAGId' => $agPayableAHAId ,'nAccHeadId' => $agPayableId ,'vAcCode' => $agPayableCode ,'vType' => 'Xo',
			    'vDes' => $user,
			    'nDebit' => $agentAmt,
			    'nCredit' => 0);
			    //'nAGId' => 18,'nAccHeadId' => 71,'vAcCode' =>22114,'vType' => 'Xo',
			$this->db->insert('{PRE}SystemJvDetails', $data);
		    endif;

//angela
		}
    
    }


    /*
    //function to insert agent details (tax)
    function jvAgentInsert($id,$newId,$location,$user,$agentAmt,$type,$tNumber,$agentAcc){
          if($type == 'Tkt' && intval($agentAmt) != 0):
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nAGId' => 27,'nAccHeadId' => 74,'vAcCode' =>32211,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => 0,
                    'nCredit' => $agentAmt);
                $this->db->insert('{PRE}SystemJvDetails', $data);
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
                    'nAGId' => 18,'nAccHeadId' => 71,'vAcCode' =>22114,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => $agentAmt,
                    'nCredit' => 0);
                $this->db->insert('{PRE}SystemJvDetails', $data);
            endif;

          if($type == 'Htl' && intval($agentAmt) != 0):
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nAGId' => 28,'nAccHeadId' => 84,'vAcCode' =>32310,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => 0,
                    'nCredit' => $agentAmt);
                $this->db->insert('{PRE}SystemJvDetails', $data);
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
                    'nAGId' => 18,'nAccHeadId' => 71,'vAcCode' =>22114,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => $agentAmt,
                    'nCredit' => 0);
                $this->db->insert('{PRE}SystemJvDetails', $data);
            endif;
          if($type == 'Xo' && intval($agentAmt) != 0):
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nAGId' => 29,'nAccHeadId' => 93,'vAcCode' =>32412,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => 0,
                    'nCredit' => $agentAmt);
                $this->db->insert('{PRE}SystemJvDetails', $data);
                $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
                    'nLocation' => $location,
                    'nDrAccount' => $agentAcc ,'vTable' => 'AG','vClosedId'=>$agentAcc . '-AG',
                    'nAGId' => 18,'nAccHeadId' => 71,'vAcCode' =>22114,'vType' => 'Tkt',
                    'vDes' => $user,
                    'nDebit' => $agentAmt,
                    'nCredit' => 0);
                $this->db->insert('{PRE}SystemJvDetails', $data);
            endif;

    }
     */

    //function for create jv
    function jvDetailInsert($id,$newId,$location,$user,$costOfSalesAmt,$type,$tNumber)
    {
      $oldNew = $this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE nTktNum = " . $tNumber ." AND vTktType = '" . $type . "'");
      if($oldNew == 'O'){
        $RefId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE  nTktNum = " . $tNumber ." AND vTktType = '" . $type . "'");
        $subAcc = $this->common_model->get_field_name('RefundRegister','nAccount'," WHERE nRId = " .$RefId ." ");
        $AccHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = " .$subAcc ." ");
        $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
        $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");
      }

      //insert into system jv details for tickets
      if($type == 'Tkt' && intval($costOfSalesAmt) != 0):
        $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
            'nLocation' => $location,
//            'nAGId' => 31,'nAccHeadId' => 107,'vAcCode' => 32511,'vType' => 'Tkt',
            'nAGId' => 27,'nAccHeadId' => 73,'vAcCode' =>32211,'vType' => 'Tkt',
            'vDes' => $user,
            'nDebit' => 0,
            'nCredit' => $costOfSalesAmt);
      $this->db->insert('{PRE}SystemJvDetails', $data);

      if($oldNew != 'O'){
        $AccHead = $this->common_model->get_field_name('TicketFareDetails','nAccHead'," WHERE nTicketId = " .$tNumber ." ");
        $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
        $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");
      }



      $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
          'nLocation' => $location,
          'nAGId' =>$AGId ,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode,'vType' => 'Tkt',
          'vDes' => $user,
          'nDebit' => $costOfSalesAmt,
          'nCredit' => 0);
      $this->db->insert('{PRE}SystemJvDetails', $data);
      endif;

      //insert into system jv details for hotels

      if($type == 'Htl' && intval($costOfSalesAmt) != 0):
        $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
            'nLocation' => $location,
//            'nAGId' => 32,'nAccHeadId' => 111,'vAcCode' => 32611,'vType' => 'Htl',
            'nAGId' => 28,'nAccHeadId' => 83,'vAcCode' =>32311,'vType' => 'Htl',
            'vDes' => $user,
            'nDebit' => 0,
            'nCredit' => $costOfSalesAmt);
      $this->db->insert('{PRE}SystemJvDetails', $data);

      if($oldNew != 'O'){
        $AccHead = $this->common_model->get_field_name('HotelVoucher','nAcHead'," WHERE nHVId = " .$tNumber ." ");
        $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
        $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");
      }

      $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
          'nLocation' => $location,
          'nAGId' => $AGId,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode ,'vType' => 'Htl',
          'vDes' => $user,
          'nDebit' => $costOfSalesAmt,
          'nCredit' => 0);
      $this->db->insert('{PRE}SystemJvDetails', $data);
      endif;

      //insert into system jv details for xo
      if($type == 'Xo' && intval($costOfSalesAmt) != 0):
        $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
            'nLocation' => $location,
//            'nAGId' => 33,'nAccHeadId' => 115,'vAcCode' =>32711,'vType' => 'Xo',
            'nAGId' => 29,'nAccHeadId' => 92,'vAcCode' => 32411,'vType' => 'Xo',
            'vDes' => $user,
            'nDebit' => 0,
            'nCredit' => $costOfSalesAmt);
      $this->db->insert('{PRE}SystemJvDetails', $data);

      if($oldNew != 'O'){
        $subAcc = $this->common_model->get_field_name('XoBooking','nPassengerAC',"  WHERE nXId  = " .$tNumber ." ");
        $AccHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = " .$subAcc ." ");
        $AGId  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId= " . $AccHead ." ");
        $AccHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId= " . $AccHead ." ");
      }
      $data      = array('nSjId' => $newId, 'vNumber' => $tNumber,
          'nLocation' => $location,
          'nAGId' => $AGId,'nAccHeadId' => $AccHead,'vAcCode' => $AccHeadCode,'vType' => 'Xo',
          'vDes' => $user,
          'nDebit' => $costOfSalesAmt,
          'nCredit' => 0);
      $this->db->insert('{PRE}SystemJvDetails', $data);
      endif;
    }//end function

    //function for create jv
    function jvTotalInsert($id,$newId,$location,$user,$totalAmnt,$tktType,$acId,$acHead)
    {
        $drAccount = @split("-",$acId);
        switch($tktType)
        {
            case "Tkt":
                $CollectedTkt = $totalAmnt;
                $CollectedHtl = 0;
                $CollectedXo  = 0;
                break;

            case "Htl":
                $CollectedTkt = 0;
                $CollectedHtl = $totalAmnt;
                $CollectedXo  = 0;
                break;

            case "Xo":
                $CollectedTkt = 0;
                $CollectedHtl = 0;
                $CollectedXo  = $totalAmnt;
                break;

            default:
                $CollectedTkt = 0;
                $CollectedHtl = 0;
                $CollectedXo  = 0;
                break;
        }//end switch

        //insert into system jv details for tickets
        //added into closed account detials
        if($CollectedTkt != 0):
            //update system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;

            $data     = array('nSjId' => $newId,
                'nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Tkt','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => 0,
                'nCredit' => $CollectedTkt);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //added into closed account detials
        if($CollectedHtl != 0):
            //update system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;

            $data      = array('nSjId' => $newId,
                'nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Htl','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => 0,
                'nCredit' => $CollectedHtl);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //added into closed account detials
        if($CollectedXo != 0):
            //update system jv
            $AgId     = $this->common_model->get_field_name('AccountHeadAccounts','nHAId',
                " WHERE nId = '".$acHead."'");
            $AgId     = (intval($AgId) == 0)?0:$AgId;
            $acCode   = $this->common_model->get_field_name('AccountHeadAccounts','vACode',
                " WHERE nId = '".$acHead."'");
            $acCode   = (intval($acCode) == 0)?0:$acCode;

            $data      = array('nSjId' => $newId,
                'nLocation' => $location,
                'nAGId' => $AgId,'nAccHeadId' => $acHead,'vAcCode' => $acCode,
                'vType' => 'Xo','vDes' => $user,'vClosedId' => $acId,
                'nDrAccount' => $drAccount[0],'vTable' => $drAccount[1],
                'nDebit' => 0,
                'nCredit' => $CollectedXo);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;
    }//end function

    //function for create jv
    function jvSalesInsert($id,$newId,$location,$user,$CollectedTkt,$CollectedHtl,$CollectedXo)
    {
        //insert into system jv details for tickets
        //addes sales details
        if(intval($CollectedTkt) != 0):
            $data      = array('nSjId' => $newId,
                'nLocation' => $location,
//                'nAGId' => 31,'nAccHeadId' => 106,'vAcCode' =>32510,'vType' => 'Tkt',
                'nAGId' => 27,'nAccHeadId' => 72,'vAcCode' => 32210,'vType' => 'Tkt',
                'vDes' => $user,
                'nDebit' => $CollectedTkt,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedHtl) != 0):
            $data      = array('nSjId' => $newId,
                'nLocation' => $location,
//                'nAGId' => 32,'nAccHeadId' => 110,'vAcCode' =>32610,'vType' => 'Htl',
                'nAGId' => 28,'nAccHeadId' => 82,'vAcCode' => 32310,'vType' => 'Htl',
                'vDes' => $user,
                'nDebit' => $CollectedHtl,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;

        //insert into system jv details for hotels
        //addes sales details
        if(intval($CollectedXo) != 0):
            $data      = array('nSjId' => $newId,
                'nLocation' => $location,
//                'nAGId' => 33,'nAccHeadId' => 114,'vAcCode' => 32710,'vType' => 'Xo',
                'nAGId' => 29,'nAccHeadId' => 91,'vAcCode' =>  32410,'vType' => 'Xo',
                'vDes' => $user,
                'nDebit' => $CollectedXo,
                'nCredit' => 0);
            $this->db->insert('{PRE}SystemJvDetails', $data);
        endif;
    }//end function

    function getAgentAmount($agentAc){
      $amt = $this->common_model->get_field_name('Agent','vAmount'," WHERE nAId = " . $agentAc . " " );
      $servCalc = $this->common_model->get_field_name('Agent','vServCalc'," WHERE nAId = " . $agentAc . " " );
      return $amt . "-" . $servCalc;
    }

///////////////////////////////////////////////////
////////////// functions for regund register
//////////////////////////////////////////////////
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


    function getActiveType()
    {
        $typeGroupRow = array("R" => "Refund", "RV" => "Refund - Void");
        return ($typeGroupRow);
    } //end function

    function getNewOld()
    {
        $GroupRow = array("N" => "New", "O" => "Old");
        return ($GroupRow);
    }//end function

    function getCountry()
    {
        $countryGroupRow = array();
        $countryGroup    = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='0' ORDER BY nId DESC");
        if ($countryGroup->num_rows() > 0) {
            $countryGroupRow[0] = SELECT;
            foreach ($countryGroup->result() as $key => $row) {
                $countryGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else {
            $countryGroupRow[0] = SELECT;
        }//end else
        return ($countryGroupRow);
    }//end function

    function getHotel()
    {
        $hotelGroupRow = array();
        $hotelGroup    = $this->db->query("SELECT * FROM {PRE}Hotel ORDER BY nHId DESC");
        if ($hotelGroup->num_rows() > 0) {
            $hotelGroupRow[0] = SELECT;
            foreach ($hotelGroup->result() as $key => $row) {
                $hotelGroupRow[$row->nHId] = $row->vName;
            }//end for each
        }//end if
        else {
            $hotelGroupRow[0] = SELECT;
        }//end else
        return ($hotelGroupRow);
    }//end function

    function getActiveCurrencyRR()
    {
        $currGroup    = $this->db->query("SELECT nId,vTitle,vCurrcode FROM {PRE}Currency WHERE vActive = '1' ORDER BY nId DESC");
        $currGroupRow = array();

        if ($currGroup->num_rows() > 0)
    {
            $currGroupRow[0] = SELECT;
            foreach ($currGroup->result() as $key => $row)
      {
                $currGroupRow[$row->nId] = (stripslashes($row->vTitle) . ' (' . stripslashes($row->vCurrcode) . ') ');
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
            $accGroupRow[0] = SELECT;
            foreach ($accGroup->result() as $key => $row)
      {
                $accGroupRow[$row->nVId] = $row->vName. ' (' . $row->vCode . ' )';
            }//end for each
        }//end if
        else
    {
            $accGroupRow[0] = NOVALUE;
        }//end else
        return ($accGroupRow);
    }//end function


    function getAPIDAccounts()
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($OtherGroupRow,$resultArray);

        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
										   		ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
        return ($resultArray);
    }//end function

    function getAccountHead()
    {
        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                        FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                         ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                            WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
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

  function getLocation()
  {
    $locGroupRow    = array();
    $locGroupRow[0] = SELECT;
    return ($locGroupRow);
  }//end function

    function getCity1()
    {
        $cityGroupRow = array();
        $cityGroup    = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId !='0' ORDER BY nId DESC");
        if ($cityGroup->num_rows() > 0) {
            $cityGroupRow[0] = SELECT;
            foreach ($cityGroup->result() as $key => $row) {
                $cityGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else {
            $cityGroupRow[0] = SELECT;
        }//end else
        return ($cityGroupRow);
    }//end function

    //fetch corresponding refund booking details
    function getCorresRefund()
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}RefundRegister WHERE nRId='" . $this->catId . "'");
        $showVal  = $fetchTitle->row();
        return $showVal;
    }//end function

    //fetch corresponding refund billing details
    function getCorresBillingDetails()
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}RefundRegisterBilling WHERE nRefundId='" . $this->catId . "'");
        $showVal  = $fetchTitle->row();
        return $showVal;
    }//end function


    function getActiveAccountHead($accVal)
    {
        $accValSplited = @split("-",$accVal);
        $accGroup      = $accValSplited[0];
        $table         = $accValSplited[1];

        if($accGroup>0):
            switch($table)
            {
                case "C" :
                    $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "V" :
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
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "AG" :
                    $accNameId       = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "O" :
                    $accNameId       = $this->common_model->get_field_name('Other','nFId'," WHERE nOId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
														FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
													 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
														LEFT JOIN {PRE}Other v ON v.nFId = ahc.nId
													 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'O' AND v.nFId = '".$accNameId."'
													  ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "A" :
                    $accheadGroupRow    = array();
                    $accheadGroupRow[0] = NOVALUE;
                    break;
            }//end switch
        else:
            $accheadGroupRow    = array();
            $accheadGroupRow[0] = NOVALUE;
        endif;
        return ($accheadGroupRow);
    }//end function

    function getCorresRefundDetails()
    {
        $refundDetailsRow           = array();
        $refundDetailsType          = array();
        $refundDetailsNewOld        = array();
        $refundDetailsTktNum        = array();
        $refundDetailsOrgTktNum     = array();
        $refundDetailsAirline       = array();
        $refundDetailsPassenger     = array();
        $refundDetailsRemarks       = array();
        $newValArray                = array();
        $refundDetailsFare          = array();
        $refundDetailsUsedFare      = array();
        $refundDetailsSaleAmt       = array();
        $refundDetailsServiceAmt2   = array();
        $refundDetailsServiceAmt    = array();
        $refundDetailsServicePer2   = array();
        $refundDetailsCountry       = array();
        $refundDetailsTax           = array();
        $refundDetailsUsedTax       = array();
        $refundDetailsOCharge       = array();
        $refundDetailsDiscAmt       = array();
        $refundDetailsCity          = array();
        $refundDetailsComm1         = array();
        $refundDetailsComm2         = array();
        $refundDetailsCreditAmt     = array();
        $refundDetailsOthAdj        = array();
        $refundDetailsOthAdj2       = array();
        $refundDetailsHotel         = array();
        $refundDetailsOtherAmt      = array();
        $refundDetailsVendorAmt     = array();
        $refundDetailsOthAdj3       = array();
        $refundDetailsAgentAmt2     = array();
        $refundDetailsIssued        = array();
        $refundDetailsNetAmt1       = array();
        $refundDetailsNetAmt2       = array();
        $refundDetailsPaidTo        = array();

        $refundDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId = '".$this->catId."'");

        if ($refundDetailsList->num_rows() > 0) {
            foreach ($refundDetailsList->result() as $key => $row) {

                $refundDetailsRow[]         = $row->nRDId;
                $refundDetailsType[]        = $row->vTktType;
                $refundDetailsNewOld[]      = $row->vNewOld;
                $refundDetailsTktNum[]      = $row->nTktNum;
                $refundDetailsOrgTktNum[]   = $row->vOrgTktNum;
                $refundDetailsAirline[]     = $row->vAirline;
                $refundDetailsPassenger[]   = $row->vPassenger;
                $refundDetailsRemarks[]     = $row->vRemarks;

                $refundDetailsFare[]        = $row->vFareAmt;
                $refundDetailsUsedFare[]    = $row->vUsedFare;
                $refundDetailsSaleAmt[]     = $row->vSalesAmt;
                $refundDetailsServiceAmt2[] = $row->vServiceAmt;
                $refundDetailsServiceAmt[]  = $row->vServiceAmt2;
                $refundDetailsServicePer2[] = $row->vServicePer;
                $refundDetailsCountry[]     = $row->nCountry;
                $refundDetailsTax[]         = $row->vTax;
                $refundDetailsUsedTax[]     = $row->vUsedTax;
                $refundDetailsOCharge[]     = $row->vOurCharge;
                $refundDetailsDiscAmt[]     = $row->vDiscountAmt;
                $refundDetailsCity[]        = $row->nCity;
                $refundDetailsComm1[]       = $row->vComm1;
                $refundDetailsComm2[]       = $row->vComm2;
                $refundDetailsCreditAmt[]   = $row->vCreditAmt;
                $refundDetailsOthAdj[]      = $row->vOthA1;
                $refundDetailsOthAdj2[]     = $row->vOthA2;
                $refundDetailsHotel[]       = $row->nHotel;
                $refundDetailsOtherAmt[]    = $row->vOtherAmt;
                $refundDetailsVendorAmt[]   = $row->vVendorAmt;
                $refundDetailsOthAdj3[]     = $row->vOthA3;
                $refundDetailsAgentAmt2[]   = $row->vAgentAmt;
                $refundDetailsIssued[]      = $row->vIssuedBy;
                $refundDetailsNetAmt1[]     = $row->vNetAmt1;
                $refundDetailsNetAmt2[]     = $row->vNetAmt2;
                $refundDetailsPaidTo[]      = $row->vPaidTo;
            }//end for each
        }//end if

        $newValArray['ID']          = $refundDetailsRow;
        $newValArray['Type']        = $refundDetailsType;
        $newValArray['YesNo']       = $refundDetailsNewOld;
        $newValArray['TktNum']      = $refundDetailsTktNum;
        $newValArray['ORGTkt']      = $refundDetailsOrgTktNum;
        $newValArray['Airline']     = $refundDetailsAirline;
        $newValArray['Passenger']   = $refundDetailsPassenger;
        $newValArray['Remarks']     = $refundDetailsRemarks;

        $newValArray['Fare']        = $refundDetailsFare;
        $newValArray['UsedFare']    = $refundDetailsUsedFare;
        $newValArray['SaleAmt']     = $refundDetailsSaleAmt;
        $newValArray['ServiceAmt2'] = $refundDetailsServiceAmt2;
        $newValArray['ServiceAmt']  = $refundDetailsServiceAmt;
        $newValArray['ServicePer2'] = $refundDetailsServicePer2;
        $newValArray['Country']     = $refundDetailsCountry;
        $newValArray['Tax']         = $refundDetailsTax;
        $newValArray['UsedTax']     = $refundDetailsUsedTax;
        $newValArray['OCharge']     = $refundDetailsOCharge;
        $newValArray['DiscAmt']     = $refundDetailsDiscAmt;
        $newValArray['City']        = $refundDetailsCity;
        $newValArray['Comm1']       = $refundDetailsComm1;
        $newValArray['Comm2']       = $refundDetailsComm2;
        $newValArray['CreditAmt']   = $refundDetailsCreditAmt;
        $newValArray['OthAdj']      = $refundDetailsOthAdj;
        $newValArray['OthAdj2']     = $refundDetailsOthAdj2;
        $newValArray['Hotel']       = $refundDetailsHotel;
        $newValArray['OtherAmt']    = $refundDetailsOtherAmt;
        $newValArray['VendorAmt']   = $refundDetailsVendorAmt;
        $newValArray['OthAdj3']     = $refundDetailsOthAdj3;
        $newValArray['AgentAmt2']   = $refundDetailsAgentAmt2;
        $newValArray['Issued']      = $refundDetailsIssued;
        $newValArray['NetAmt1']     = $refundDetailsNetAmt1;
        $newValArray['NetAmt2']     = $refundDetailsNetAmt2;
        $newValArray['PaidTo']      = $refundDetailsPaidTo;

        return ($newValArray);
    }//end function


    function insertRefund()
    {
      $sales_dt = strtotime($this->input->post('txtSdt')) == '' ?  date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('txtSdt')));

        $sectorval = $this->input->post('txtSector1').'-'.$this->input->post('txtSector2').'-'.$this->input->post('txtSector3').'-'.$this->input->post('txtSector4').'-'.$this->input->post('txtSector5');
        //insert into refund table
        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),
                      'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
                      'vDocNumber' => $this->input->post('txtDocNumber'),'nGDS' => $this->input->post('ddlGDS'),
                      'vType' => $this->input->post('ddlType'),'vPassenger' => $this->input->post('txtPassenger'),
					  'vSector' => $sectorval, 'nAccount' => $this->input->post('ddlAccount'),'nUserId' => $this->session->userdata('AbsAdmin'),
                      'vSegment' => $this->input->post('txtSegment'), 'vDestination' => $this->input->post('txtDestination'),
                      'vAcAPID' => $this->input->post('ddlAccApId'),'nCurrency' => $this->input->post('ddlCurrency'),
					  'nRate' => $this->input->post('txtRate'),'vSalesDet' => $sales_dt,
					  'vRefundDet' => '','vMode' => $this->input->post('ddlMode'),
                      'vActive' => $this->input->post('chkActive')); 
        $this->db->insert('{PRE}RefundRegister', $data); 

        $id = $this->db->insert_id();

        //insert into refund billing table
        $data1 = array('nRefundId' => $id, 'vInvoice' => $this->input->post('txtInvoice'),
                        'vMode' => $this->input->post('ddlMode2'),'nAcCode' => $this->input->post('ddlAccountClose'),
                        'vAcSubCode' => $this->input->post('ddlAccountHead'),'nAgentAc' => $this->input->post('ddlAgentAc'),
                        'vLPO' => $this->input->post('txtLPO'));
        $this->db->insert('{PRE}RefundRegisterBilling', $data1);

        $Fare           = (@array_filter($this->input->post('txtFare')) != '') ? $this->input->post('txtFare') : 0;
        $UsedFare       = (@array_filter($this->input->post('txtUsedFare')) != '') ? $this->input->post('txtUsedFare') : 0;
        $SaleAmt        = (@array_filter($this->input->post('txtSaleAmt')) != '') ? $this->input->post('txtSaleAmt') : 0;
        $ServiceAmt2    = (@array_filter($this->input->post('txtServiceAmt2')) != '') ? $this->input->post('txtServiceAmt2') : 0;
        $ServiceAmt     = (@array_filter($this->input->post('txtServiceAmt')) != '') ? $this->input->post('txtServiceAmt') : 0;
        $ServicePer2    = (@array_filter($this->input->post('txtServicePer')) != '') ? $this->input->post('txtServicePer') : 0;
        $Country        = (@array_filter($this->input->post('ddlCountry')) != '') ? $this->input->post('ddlCountry') : 0;
        $Tax            = (@array_filter($this->input->post('txtTax')) != '') ? $this->input->post('txtTax') : 0;
        $UsedTax        = (@array_filter($this->input->post('txtUsedTax')) != '') ? $this->input->post('txtUsedTax') : 0;
        $OCharge        = (@array_filter($this->input->post('txtOCharge')) != '') ? $this->input->post('txtOCharge') : 0;
        $DiscAmt        = (@array_filter($this->input->post('txtDiscAmt')) != '') ? $this->input->post('txtDiscAmt') : 0;
        $City           = (@array_filter($this->input->post('ddlCity')) != '') ? $this->input->post('ddlCity') : 0;
        $Comm1          = (@array_filter($this->input->post('txtComm1')) != '') ? $this->input->post('txtComm1') : 0;
        $Comm2          = (@array_filter($this->input->post('txtComm2')) != '') ? $this->input->post('txtComm2') : 0;
        $CreditAmt = (@array_filter($this->input->post('txtCreditAmt')) != '') ? $this->input->post('txtCreditAmt') : 0;
        $OthAdj         = (@array_filter($this->input->post('txtOthAdj')) != '') ? $this->input->post('txtOthAdj') : 0;
        $OthAdj2        = (@array_filter($this->input->post('txtOthAdj2')) != '') ? $this->input->post('txtOthAdj2') : 0;
        $Hotel          = (@array_filter($this->input->post('ddlHotel')) != '') ? $this->input->post('ddlHotel') : 0;
        $OtherAmt       = (@array_filter($this->input->post('txtOtherAmt')) != '') ? $this->input->post('txtOtherAmt') : 0;
        $VendorAmt      = (@array_filter($this->input->post('txtVendorAmt')) != '') ? $this->input->post('txtVendorAmt') : 0;
        $OthAdj3        = (@array_filter($this->input->post('txtOthAdj3')) != '') ? $this->input->post('txtOthAdj3') : 0;
        $AgentAmt2      = (@array_filter($this->input->post('txtAgentAmt2')) != '') ? $this->input->post('txtAgentAmt2') : 0;
        $Issued         = (@array_filter($this->input->post('ddlIssued')) != '') ? $this->input->post('ddlIssued') : 0;
        $NetAmt1        = (@array_filter($this->input->post('txtNetAmt1')) != '') ? $this->input->post('txtNetAmt1') : 0;
        $NetAmt2        = (@array_filter($this->input->post('txtNetAmt2')) != '') ? $this->input->post('txtNetAmt2') : 0;
        $PaidTo         = (@array_filter($this->input->post('txtPaidTo')) != '') ? $this->input->post('txtPaidTo') : 0;
        $type      	    = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $yesNo      	= (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
        $tktNum         = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $tktNum_O         = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
        $oTkt     	    = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
        $airline     	= (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $passenger      = (@array_filter($this->input->post('txtPass')) != '')?$this->input->post('txtPass'):0;
        $accRefNumber 	= (@array_filter($this->input->post('txtRemarks')) != '')?$this->input->post('txtRemarks'):0;


        //insert into RefundRegister Details table
        foreach ($type as $key => $item)
		{

            if($yesNo[$key] == 'N') {
              $tktVal = $tktNum[$key];
            }
            else if ($yesNo[$key] == 'O'){
              $tktVal = $tktNum_O[$key];
            }
            $data3 = array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                            'nTktNum' => $tktVal , 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                            'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                            'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key],'vServicePer' => $ServicePer2[$key],
                            'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],'vTax' => $Tax[$key],
                            'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
// aust
'vCreditAmt' => $CreditAmt[$key], 
                            'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                            'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                            'vAgentAmt' => $AgentAmt2[$key],'nCountry' => $Country[$key],'vUsedTax' => $UsedTax[$key],
                            'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key],
                            'vPaidTo' => $PaidTo[$key]);
            $this->db->insert('{PRE}RefundRegisterDetails', $data3);
        }//end foreach
        if($this->input->post('btnGo') == "Save & Close"){
          $id = $this->saveToRefundClosing();
          $msgVal = 'add_suc';
        }
        else{
          $msgVal = 'add_suc_reg';
        }

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '' && $this->input->post('btnGo') != "Save & Close")
		{
            redirect(site_url("refund_closing/editRefund/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if
        if ($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != ''){
            redirect(site_url("refund_closing/showAllRefund/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if
        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '' && $this->input->post('btnGo') == "Save & Close"){
            redirect(site_url("refund_closing/showClosed/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if
    }//end function

    function saveToRefundClosing()
    {
        $trans_type = $this->common_model->get_field_name('Transactions','nId'," WHERE vActive = '1' AND nId = '24' ");
        $docNumber = $this->getDocumentNumber($trans_type); 

        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
            'dDate' => date('Y-m-d'),'vTransMode' => $trans_type,
            'vDocNumber' => $docNumber,'vLPO' => $this->input->post('txtLPO'),
            'nDrAccount' => 0,'vCPV' => '',
            'vNewTkt' =>'','nIssued' => '',
            'nAgentAccount' => $this->input->post('ddlAgentAc'),'nCurrency' => '',
            'vRate' => '',

            'vTotalNetAmount' => $this->input->post('txtTotalNetAmount'),
            'vTotalServPercent' => $this->input->post('txtTotalServPercent'),
            'vTotalServAmount' => $this->input->post('txtTotalServAmount'),
            'vTotalAgentAmt' => $this->input->post('txtTotalAgentAmt'),
            'vTotalPaidAmt' => $this->input->post('txtTotalPaidAmt'),
            'vRemarks' => $this->input->post('txtRemarks')[0],

            'nInvoiceStatus' => 'N',
            'vPassenger' => $this->input->post('txtPassenger'),
            'vActive' => $this->input->post('chkActive'));
        $this->db->insert('{PRE}RefundClosing', $data);

        $id			  	 = $this->db->insert_id();



        $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $tktNum_O         = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
        $yesNo        = (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
        $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $ORGTkt      	 = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
        $netAmt     	 = (@array_filter($this->input->post('txtNetAmt2')) != '')?$this->input->post('txtNetAmt2'):0;
        $servPercent   	 = (@array_filter($this->input->post('txtServicePer')) != '')?$this->input->post('txtServicePer'):0;
        $servAmt     	 = (@array_filter($this->input->post('txtServiceAmt')) != '')?$this->input->post('txtServiceAmt'):0;
        $agentAmt     	 = (@array_filter($this->input->post('txtAgentAmt2')) != '')?$this->input->post('txtAgentAmt2'):0;
        $paidAmt         = (@array_filter($this->input->post('txtPaidTo')) != '')?$this->input->post('txtPaidTo'):0;
        $DrAcc           = ($this->input->post('ddlAccountClose') != '')?$this->input->post('ddlAccountClose'):0;
        $LPO             = ($this->input->post('txtLPO') != '')?$this->input->post('txtLPO'):0;
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

        //create jv
        $newId = $this->jvInsert($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$docNumber,date('Y-m-d'));

        //insert into refund closing details
        foreach($chkBox as $key => $aitem)
        {
/*              if($yesNo[$key] == 'N') {
                $tktVal = $tktNum[$key];
              }
              else*/ 
              if ($yesNo[$key] == 'O'){
                $tktNumber[$key] = $tktNum_O[$key];
              }

        $refundClosingId = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE  vType = '".$tktType[$key]."' AND nTicketNumber = ".$tktNumber[$key]." ");
        if($refundClosingId  != '') continue;

            $drAccount = @split("-",$DrAcc);

            switch($tktType[$key])
            {
                case "Tkt":
                    $CollectedTkt   += $paidAmt[$key];
                    break;

                case "Htl":
                    $CollectedHtl   += $paidAmt[$key];
                    break;

                case "Xo":
                    $CollectedXo    += $paidAmt[$key];
                    break;
            }//end switch

            switch($drAccount[1])
            {
                case "C":
                    $AcHead = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId='".$drAccount[0]."'");
                    break;

                case "V":
                    $AcHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId='".$drAccount[0]."'");
                    break;

                case "A":
                    $AcHead = 0;
                    break;

                case "O":
                    $AcHead = $this->common_model->get_field_name('Other','nFId'," WHERE nOId='".$drAccount[0]."'");
                    break;

                case "AG":
                    $AcHead = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId='".$drAccount[0]."'");
                    break;
            }

            $data      = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                                'vLPO' => $LPO,'vPaidAmt' => $paidAmt[$key]);
            $this->db->insert('{PRE}RefundClosingDetails', $data);

            $RRId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE vTktType = '". $tktType[$key] ."' AND nTktNum = ".$tktNumber[$key]." ");
            $data = array('vRefundDet'=>date('Y-m-d'));
            $this->db->where('nRId',$RRId);
            $this->db->update('{PRE}RefundRegister', $data);

            $vTotalNetAmount += $netAmt[$key];
            $vTotalServPercent += $servPercent[$key];
            $vTotalServAmount += $servAmt[$key];
            $vTotalAgentAmt += $agentAmt[$key];
            $vTotalPaidAmt += $paidAmt[$key];

            $this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$netAmt[$key],$tktType[$key],$tktNumber[$key]);

            $this->jvAgentInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$agentAmt[$key],$tktType[$key],$tktNumber[$key],$this->input->post('ddlAgentAc'));


            $this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$paidAmt[$key],$tktType[$key],$DrAcc,$AcHead);
        }//end foreach

        $this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);

        $data = array('vTotalNetAmount' => $vTotalNetAmount,
            'vTotalServPercent' => $vTotalServPercent,
            'vTotalServAmount' => $vTotalServAmount,
            'vTotalAgentAmt' =>$vTotalAgentAmt,
            'vTotalPaidAmt' => $vTotalPaidAmt);

        $this->db->where('nRCId',$id);
        $this->db->update('{PRE}RefundClosing', $data);


        return $id;
    }//end class


    function saveToRefundClosingUpdate()
    {

      $backUrl = explode('/',$this->session->userdata('backURL2'));
      $id = $backUrl[4];
      $docNum = $this->common_model->get_field_name('RefundClosing','vDocNumber', " WHERE nRCId = ".$id ." ");

      $tktType      	 = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
      $tktNumber       = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
      $tktNum_O         = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
      $yesNo        = (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
      $airline      	 = (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
      $ORGTkt      	 = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
      $netAmt     	 = (@array_filter($this->input->post('txtNetAmt2')) != '')?$this->input->post('txtNetAmt2'):0;
      $servPercent   	 = (@array_filter($this->input->post('txtServicePer')) != '')?$this->input->post('txtServicePer'):0;
      $servAmt     	 = (@array_filter($this->input->post('txtServiceAmt')) != '')?$this->input->post('txtServiceAmt'):0;
      $agentAmt     	 = (@array_filter($this->input->post('txtAgentAmt2')) != '')?$this->input->post('txtAgentAmt2'):0;
      $paidAmt         = (@array_filter($this->input->post('txtPaidTo')) != '')?$this->input->post('txtPaidTo'):0;
      $DrAcc           = ($this->input->post('ddlAccountClose') != '')?$this->input->post('ddlAccountClose'):0;
      $LPO             = ($this->input->post('txtLPO') != '')?$this->input->post('txtLPO'):0;
      $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;

      //create jv
      $newId = $this->jvUpdate($id,$this->input->post('ddlDivision'),$this->input->post('ddlLocation'),$docNum,date('Y-m-d',strtotime($this->input->post('txtDate'))));

      $this->db->where('nSjId',$newId);
      $this->db->where('nSjId',$newId);
      $this->db->delete('{PRE}SystemJvDetails');

      $refundClosingIdList = array();
      //insert into refund closing details
      foreach($chkBox as $key => $aitem)
      {
        if ($yesNo[$key] == 'O'){
          $tktNumber[$key] = $tktNum_O[$key];
        }

        $drAccount = @split("-",$DrAcc);

        switch($tktType[$key])
        {
          case "Tkt":
            $CollectedTkt   += $paidAmt[$key];
          break;

          case "Htl":
            $CollectedHtl   += $paidAmt[$key];
          break;

          case "Xo":
            $CollectedXo    += $paidAmt[$key];
          break;
        }//end switch

        switch($drAccount[1])
        {
          case "C":
            $AcHead = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId='".$drAccount[0]."'");
          break;

          case "V":
            $AcHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId='".$drAccount[0]."'");
          break;

          case "A":
            $AcHead = 0;
          break;

          case "O":
            $AcHead = $this->common_model->get_field_name('Other','nFId'," WHERE nOId='".$drAccount[0]."'");
          break;

          case "AG":
            $AcHead = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId='".$drAccount[0]."'");
          break;
        }

        $refundClosingId = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId = ".$id." AND vType = '".$tktType[$key]."' AND nTicketNumber = ".$tktNumber[$key]." ");

        $refundClosingId_Diff = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId != ".$id." AND vType = '".$tktType[$key]."' AND nTicketNumber = ".$tktNumber[$key]." ");

        $refUpdateId = '';
        if($refundClosingId != ''){
          $refUpdateId = $refundClosingId;
          if(!in_array($id,$refundClosingIdList))
            $refundClosingIdList[] = $id;
        }//end if
        elseif($refundClosingId_Diff != ''){
          $refUpdateId = $refundClosingId_Diff;
          $id_Diff = $this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nRefundCloseId != ".$id." AND vType = '".$tktType[$key]."' AND nTicketNumber = ".$tktNumber[$key]." ");
          if(!in_array($id_Diff,$refundClosingIdList))
            $refundClosingIdList[] = $id_Diff;
        } //endif

        if($refUpdateId != ''){

          $data = array('vNetAmt' => $netAmt[$key],
              'vServPercent' => $servPercent[$key],
              'vServAmt' => $servAmt[$key],
              'vAgentAmt' => $agentAmt[$key],
              'nDrAcc' => $drAccount[0],
              'vTable' => $drAccount[1],
              'vPaidAmt' => $paidAmt[$key]);

//          $this->db->where('nRefundCloseId', $id);
          $this->db->where('vType',$tktType[$key]);
          $this->db->where('nTicketNumber', $tktNumber[$key]);
          $this->db->where('nId',$refUpdateId);
          $this->db->update('{PRE}RefundClosingDetails', $data);
        }
        else{
            $data = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                'vLPO' => $LPO,'vPaidAmt' => $paidAmt[$key]);
            $this->db->insert('{PRE}RefundClosingDetails', $data);
        }// end if

            $RRId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE vTktType = '". $tktType[$key] ."' AND nTktNum = ".$tktNumber[$key]." ");
            $data = array('vRefundDet'=>date('Y-m-d'));
            $this->db->where('nRId',$RRId );
            $this->db->update('{PRE}RefundRegister', $data);


        $this->jvDetailInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$netAmt[$key],$tktType[$key],$tktNumber[$key]);

        $this->jvAgentInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$agentAmt[$key],$tktType[$key],$tktNumber[$key],$this->input->post('ddlAgentAc'));


        $this->jvTotalInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$paidAmt[$key],$tktType[$key],$DrAcc,$AcHead);
      }//end foreach

      $this->jvSalesInsert($id,$newId,$this->input->post('ddlLocation'),$this->input->post('txtPassName'),$CollectedTkt,$CollectedHtl,$CollectedXo);

      foreach($refundClosingIdList as $key => $value){
        $totalsQuery = $this->db->query(" SELECT SUM(vNetAmt) as totNet, SUM(vServPercent) as totservPerc, SUM(vServAmt) as totServAmt, SUM(vAgentAmt) as totAgentAmt, SUM(vPaidAmt) as totPaidAmt FROM {PRE}RefundClosingDetails WHERE nRefundCloseId = ".$value." GROUP BY nRefundCloseId");

        $row_total = $totalsQuery->row_array();
        $data = array('vTotalNetAmount' => $row_total['totNet'],
            'vTotalServPercent' =>  $row_total['totservPerc'],
            'vTotalServAmount' =>  $row_total['totServAmt'],
            'vTotalAgentAmt' =>  $row_total['totAgentAmt'],
            'vTotalPaidAmt' =>  $row_total['totPaidAmt']); 

        $this->db->where('nRCId',$value);
        $this->db->update('{PRE}RefundClosing', $data);
      }//endforeach
    }//end class

    function saveToRefundClosingTable($val)
    {

        $this->catId = $val; 
        $refReg = $this->getCorresRefund();
        $refRegBillingVal       = $this->refund_closing_model->getCorresBillingDetails();
        $RefundValArray           = $this->refund_closing_model->getCorresRefundDetails();

        $trans_type = $this->common_model->get_field_name('Transactions','nId'," WHERE vActive = '1' AND nId = '24' ");
        $docNumber = $this->getDocumentNumber($trans_type, $refReg->nLocation); 

        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),
            'nDivision' => $refReg->nDivision,
            'vLocation' => $refReg->nLocation,
            'dDate' => date('Y-m-d'),
            'vTransMode' => $trans_type,
            'vDocNumber' => $docNumber,
            'vLPO' => $refRegBillingVal->vLPO,
            'nDrAccount' => 0,'vCPV' => '',
            'vNewTkt' =>'','nIssued' => '',
            'nAgentAccount' => $refRegBillingVal->nAgentAc,
            'nCurrency' => '',
            'vRate' => '',

            'vTotalNetAmount' => '',
            'vTotalServPercent' => '',
            'vTotalServAmount' => '',
            'vTotalAgentAmt' => '',
            'vTotalPaidAmt' => '',

            'vRemarks' => $this->common_model->get_field_name('RefundRegisterDetails','vRemarks'," WHERE nRefundId =".$val." "),
            'nInvoiceStatus' => 'N',
            'vPassenger' => $refReg->vPassenger,
            'vActive' => '1');
        $this->db->insert('{PRE}RefundClosing', $data);

       $id			  	 = $this->db->insert_id();


        $tktType      	 = (@array_filter($RefundValArray['Type']) != '')?$RefundValArray['Type']:0;
        $tktNumber       = (@array_filter($RefundValArray['TktNum']) != '')?$RefundValArray['TktNum']:0;
        $airline      	 = (@array_filter($RefundValArray['Airline']) != '')?$RefundValArray['Airline']:0;
        $ORGTkt      	 = (@array_filter($RefundValArray['ORGTkt']) != '')?$RefundValArray['ORGTkt']:0;
        $netAmt     	 = (@array_filter($RefundValArray['NetAmt2']) != '')?$RefundValArray['NetAmt2']:0;
        $servPercent   	 = (@array_filter($RefundValArray['ServicePer2']) != '')?$RefundValArray['ServicePer2']:0;
        $servAmt     	 = (@array_filter($RefundValArray['ServiceAmt']) != '')?$RefundValArray['ServiceAmt']:0;
        $agentAmt     	 = (@array_filter($RefundValArray['AgentAmt2']) != '')?$RefundValArray['AgentAmt2']:0;
        $paidAmt         = (@array_filter($RefundValArray['PaidTo']) != '')?$RefundValArray['PaidTo']:0;
        $DrAcc           = ( $refRegBillingVal->nAcCode != '')? $refRegBillingVal->nAcCode:0;
        $LPO             = (  $refRegBillingVal->vLPO != '')? $refRegBillingVal->vLPO:0;

        //create jv
        $newId = $this->jvInsert($id,$refReg->nDivision,$refReg->nLocation,$docNumber,date('Y-m-d'));

        //insert into refund closing details
        foreach($tktNumber as $key => $aitem)
        {
          $refundClosingId = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE  vType = '".$tktType[$key]."' AND nTicketNumber = ".$tktNumber[$key]." ");
          if($refundClosingId  != '') continue;

            $drAccount = @split("-",$DrAcc);

            switch($tktType[$key])
            {
                case "Tkt":
                    $CollectedTkt   += $paidAmt[$key];
                    break;

                case "Htl":
                    $CollectedHtl   += $paidAmt[$key];
                    break;

                case "Xo":
                    $CollectedXo    += $paidAmt[$key];
                    break;
            }//end switch

            switch($drAccount[1])
            {
                case "C":
                    $AcHead = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId='".$drAccount[0]."'");
                    break;

                case "V":
                    $AcHead = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId='".$drAccount[0]."'");
                    break;

                case "A":
                    $AcHead = 0;
                    break;

                case "O":
                    $AcHead = $this->common_model->get_field_name('Other','nFId'," WHERE nOId='".$drAccount[0]."'");
                    break;

                case "AG":
                    $AcHead = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId='".$drAccount[0]."'");
                    break;
            }


            $data      = array('nRefundCloseId' => $id,'vType' => $tktType[$key],'nAirline' => $airline[$key],
                                'nTicketNumber' => $tktNumber[$key],'vNetAmt' => $netAmt[$key],'vServPercent' => $servPercent[$key],
                                'vServAmt' => $servAmt[$key],'vAgentAmt' => $agentAmt[$key],
                                'vORG' => $ORGTkt[$key],'nDrAcc' => $drAccount[0],'vTable' => $drAccount[1],
                                'vLPO' => $LPO,'vPaidAmt' => $paidAmt[$key]);
            $this->db->insert('{PRE}RefundClosingDetails', $data);

            $RRId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE vTktType = '". $tktType[$key] ."' AND nTktNum = ".$tktNumber[$key]." ");
            $data = array('vRefundDet'=>date('Y-m-d'));
            $this->db->where('nRId',$RRId );
            $this->db->update('{PRE}RefundRegister', $data);



            $vTotalNetAmount += $netAmt[$key];
            $vTotalServPercent += $servPercent[$key];
            $vTotalServAmount += $servAmt[$key];
            $vTotalAgentAmt += $agentAmt[$key];
            $vTotalPaidAmt += $paidAmt[$key];

            $this->jvDetailInsert($id,$newId,$refReg->nLocation,$refReg->vPassenger,$netAmt[$key],$tktType[$key],$tktNumber[$key]);

            $this->jvAgentInsert($id,$newId,$refReg->nLocation,$refReg->vPassenger,$agentAmt[$key],$tktType[$key],$tktNumber[$key],$refRegBillingVal-->nAgentAc);


            $this->jvTotalInsert($id,$newId,$refReg->nLocation,$refReg->vPassenger,$paidAmt[$key],$tktType[$key],$DrAcc,$AcHead);
        }//end foreach

        $this->jvSalesInsert($id,$newId,$refReg->nLocation,$refReg->vPassenger,$CollectedTkt,$CollectedHtl,$CollectedXo);

        $data = array('vTotalNetAmount' => $vTotalNetAmount,
            'vTotalServPercent' => $vTotalServPercent,
            'vTotalServAmount' => $vTotalServAmount,
            'vTotalAgentAmt' =>$vTotalAgentAmt,
            'vTotalPaidAmt' => $vTotalPaidAmt);

        $this->db->where('nRCId',$id);
        $this->db->update('{PRE}RefundClosing', $data);

        return;
    }//end class



    function updateRefund()
    {
      $sales_dt = strtotime($this->input->post('txtSdt')) == '' ?  date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('txtSdt')));

        $sectorval = $this->input->post('txtSector1').'-'.$this->input->post('txtSector2').'-'.$this->input->post('txtSector3').'-'.$this->input->post('txtSector4').'-'.$this->input->post('txtSector5');
        //update into RefundRegister table
        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),
                        'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
                        'vDocNumber' => $this->input->post('txtDocNumber'),'nGDS' => $this->input->post('ddlGDS'),
                        'vType' => $this->input->post('ddlType'),'vPassenger' => $this->input->post('txtPassenger'),
                        'vSector' => $sectorval, 'nAccount' => $this->input->post('ddlAccount'), 'nUserId' => $this->session->userdata('AbsAdmin'),
                        'vSegment' => $this->input->post('txtSegment'), 'vDestination' => $this->input->post('txtDestination'),
                        'vAcAPID' => $this->input->post('ddlAccApId'),'nCurrency' => $this->input->post('ddlCurrency'),
                        'nRate' => $this->input->post('txtRate'),'vSalesDet' => '',
                        'vMode' => $this->input->post('ddlMode'),
                        'vActive' => $this->input->post('chkActive'));
        $this->db->where('nRId', $this->input->post('nId'));
        $this->db->update('{PRE}RefundRegister', $data);
        $id = $this->input->post('nId');

        //update refund billing table
        $data1 = array('nRefundId' => $id, 'vInvoice' => $this->input->post('txtInvoice'),
                        'vMode' => $this->input->post('ddlMode2'),'nAcCode' => $this->input->post('ddlAccountClose'),
                        'vAcSubCode' => $this->input->post('ddlAccountHead'),'nAgentAc' => $this->input->post('ddlAgentAc'),
                        'vLPO' => $this->input->post('txtLPO'));
        $this->db->where('nRefundId', $this->input->post('nId'));
        $this->db->update('{PRE}RefundRegisterBilling', $data1);

        $Fare           = (@array_filter($this->input->post('txtFare')) != '') ? $this->input->post('txtFare') : 0;
        $UsedFare       = (@array_filter($this->input->post('txtUsedFare')) != '') ? $this->input->post('txtUsedFare') : 0;
        $SaleAmt        = (@array_filter($this->input->post('txtSaleAmt')) != '') ? $this->input->post('txtSaleAmt') : 0;
        $ServiceAmt2    = (@array_filter($this->input->post('txtServiceAmt2')) != '') ? $this->input->post('txtServiceAmt2') : 0;
        $ServiceAmt     = (@array_filter($this->input->post('txtServiceAmt')) != '') ? $this->input->post('txtServiceAmt') : 0;
        $ServicePer2    = (@array_filter($this->input->post('txtServicePer')) != '') ? $this->input->post('txtServicePer') : 0;
        $Country        = (@array_filter($this->input->post('ddlCountry')) != '') ? $this->input->post('ddlCountry') : 0;
        $Tax            = (@array_filter($this->input->post('txtTax')) != '') ? $this->input->post('txtTax') : 0;
        $UsedTax        = (@array_filter($this->input->post('txtUsedTax')) != '') ? $this->input->post('txtUsedTax') : 0;
        $OCharge        = (@array_filter($this->input->post('txtOCharge')) != '') ? $this->input->post('txtOCharge') : 0;
        $DiscAmt        = (@array_filter($this->input->post('txtDiscAmt')) != '') ? $this->input->post('txtDiscAmt') : 0;
        $City           = (@array_filter($this->input->post('ddlCity')) != '') ? $this->input->post('ddlCity') : 0;
        $Comm1          = (@array_filter($this->input->post('txtComm1')) != '') ? $this->input->post('txtComm1') : 0;
        $Comm2          = (@array_filter($this->input->post('txtComm2')) != '') ? $this->input->post('txtComm2') : 0;
        $CreditAmt      = (@array_filter($this->input->post('txtCreditAmt')) != '') ? $this->input->post('txtCreditAmt') : 0;
// aust
        $CreditAmt = (@array_filter($this->input->post('txtCreditAmt')) != '') ? $this->input->post('txtCreditAmt') : 0;
        $OthAdj         = (@array_filter($this->input->post('txtOthAdj')) != '') ? $this->input->post('txtOthAdj') : 0;
        $OthAdj2        = (@array_filter($this->input->post('txtOthAdj2')) != '') ? $this->input->post('txtOthAdj2') : 0;
        $Hotel          = (@array_filter($this->input->post('ddlHotel')) != '') ? $this->input->post('ddlHotel') : 0;
        $OtherAmt       = (@array_filter($this->input->post('txtOtherAmt')) != '') ? $this->input->post('txtOtherAmt') : 0;
        $VendorAmt      = (@array_filter($this->input->post('txtVendorAmt')) != '') ? $this->input->post('txtVendorAmt') : 0;
        $OthAdj3        = (@array_filter($this->input->post('txtOthAdj3')) != '') ? $this->input->post('txtOthAdj3') : 0;
        $AgentAmt2      = (@array_filter($this->input->post('txtAgentAmt2')) != '') ? $this->input->post('txtAgentAmt2') : 0;
        $Issued         = (@array_filter($this->input->post('ddlIssued')) != '') ? $this->input->post('ddlIssued') : 0;
        $NetAmt1        = (@array_filter($this->input->post('txtNetAmt1')) != '') ? $this->input->post('txtNetAmt1') : 0;
        $NetAmt2        = (@array_filter($this->input->post('txtNetAmt2')) != '') ? $this->input->post('txtNetAmt2') : 0;
        $PaidTo         = (@array_filter($this->input->post('txtPaidTo')) != '') ? $this->input->post('txtPaidTo') : 0;
        $type      	    = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $yesNo      	= (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
        $tktNum         = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $tktNum_O       = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
        $oTkt     	    = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
        $airline     	= (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $passenger      = (@array_filter($this->input->post('txtPass')) != '')?$this->input->post('txtPass'):0;
        $accRefNumber 	= (@array_filter($this->input->post('txtRemarks')) != '')?$this->input->post('txtRemarks'):0;
        $chkAccID	    = $this->input->post('idArray');

        //insert into RefundRegister Details table
        foreach($type as $key => $item)
        {
            //checking already exists if yes updated
            if(intval($chkAccID[$key]) != 0)
            {
              if($yesNo[$key] == 'N') {
                $tktVal = $tktNum[$key];
              }
              else if ($yesNo[$key] == 'O'){
                $tktVal = $tktNum_O[$key];
              }

                $data3 	= array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                                'nTktNum' => $tktVal , 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                                'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                                'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key],'vServicePer' => $ServicePer2[$key],
                                'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],
                                'nCountry' => $Country[$key],'vTax' => $Tax[$key],'vUsedTax' => $UsedTax[$key],
                                'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
// aust
'vCreditAmt' => $CreditAmt[$key],
                                'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                                'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                                'vAgentAmt' => $AgentAmt2[$key],
                                'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key], 'vPaidTo' => $PaidTo[$key]);
                $this->db->where('nRDId',$chkAccID[$key]);
                $this->db->update('{PRE}RefundRegisterDetails', $data3);


            }//end if
            else
            {
                //if new insert into db
                $data3 	= array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                                'nTktNum' => $tktNum[$key], 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                                'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                                'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key], 'vServicePer' => $ServicePer2[$key],
                                'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],
                                'nCountry' => $Country[$key],'vTax' => $Tax[$key],'vUsedTax' => $UsedTax[$key],
                                'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
// aust
'vCreditAmt' => $CreditAmt[$key],
                                'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                                'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                                'vAgentAmt' => $AgentAmt2[$key],
                                'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key], 'vPaidTo' => $PaidTo[$key]);
                $this->db->insert('{PRE}RefundRegisterDetails', $data3);
            }//end else
        }//end foreach
        if($this->input->post('btnGo')== "Update Refund Register"){
          $msgVal = 'edit_suc_reg';
        }
        else{
          $id = $this->saveToRefundClosing();
          $msgVal = 'edit_suc';
        }

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != '')
        {
            redirect(site_url($this->session->userdata('backURL')));
        }
        elseif(trim($this->session->userdata('backURL2'))){

          $this->saveToRefundClosingUpdate();
          redirect(site_url($this->session->userdata('backURL2')));
        }
        else
        {
          redirect(site_url("refund_closing/showAllRefund/" . $this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if 
    } //end function



}//end class
?>
