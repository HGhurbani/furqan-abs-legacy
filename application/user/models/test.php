<?php
class Hotel_voucher_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function getAllHotelVoucher()
	{		
		//active groups
		$hotelVoucherGroupRow  	= array();
		$sqlHotelvoucherQuery   = "SELECT hv.*,hbd.*,h.vName as hotelName,aha.vTitle as accName,aha.vACode as accCode,
									CASE 
										WHEN hv.vType = 'N' THEN 'Normal'
										WHEN hv.vType = 'V' THEN 'Void'
										WHEN hv.vType = 'C' THEN 'Conjuction'
									ELSE 'Re issue' END as hvType FROM {PRE}HotelVoucher hv 
								   LEFT JOIN {PRE}Hotel h ON h.nHId = hv.vHotelName
										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
								   LEFT JOIN {PRE}AccountHeadAccounts aha ON hv.nAcCode = aha.nId ORDER BY nHVId DESC
								        LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlHotelvoucherQuery;	
		$hotelVoucherGroupList 	= $this->db->query($sqlHotelvoucherQuery);
		
		if ($hotelVoucherGroupList->num_rows() > 0)
		{
			foreach ($hotelVoucherGroupList->result() as $key=>$row)
			{
				$hotelVoucherGroupRow[] = $row;
			}//end for each
		}//end if
		return ($hotelVoucherGroupRow);
	}//end function

	//get user hotel voucher count
	function getAllHotelVoucherCount()
	{		
		$hotelVoucherGroupRow  = array();
		$hotelVoucherGroupList = $this->db->query("SELECT hv.*,hbd.*,h.vName as hotelName,aha.vTitle as accName,
													aha.vACode as accCode,
													CASE 
														WHEN hv.vType = 'N' THEN 'Normal'
														WHEN hv.vType = 'V' THEN 'Void'
														WHEN hv.vType = 'C' THEN 'Conjuction'
													ELSE 'Re issue' END as hvType FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}Hotel h 
														ON h.nHId = hv.vHotelName
													LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 
														LEFT JOIN {PRE}AccountHeadAccounts aha ON hv.nAcCode = aha.nId 
													ORDER BY nHVId DESC");
		return ($hotelVoucherGroupList->num_rows());
	}//end function	

	function getActiveAirline()
	{
		$airlineGroupRow = array();
		$airlineGroup    = $this->db->query("SELECT * FROM {PRE}Airline ORDER BY nArId DESC");
		if ($airlineGroup->num_rows() > 0)
		{
			$airlineGroupRow[0] = SELECT;
			foreach ($airlineGroup->result() as $key => $row)
			{
				$airlineGroupRow[$row->nArId] = $row->vTitle.' ( '.$row->vAirlineNo.' )';
			}//end for each
		}//end if
		else
		{
			$airlineGroupRow[0] = NOVALUE;
		}//end else

		return ($airlineGroupRow);
	}//end function 

	function getLocation()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function

    function getActiveAccountHead()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function

	function getCities()
	{
		$cityGroupRow    = array();
		$cityGroupRow[0] = SELECT;
		return ($cityGroupRow);
	}//end function 

	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' ORDER BY nPosition DESC");
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

	function filterCity($country)
	{
		$cityGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."' 
											ORDER BY nId DESC");
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
		return($cityGroupRow);
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

	function getActiveCountry()
	{
		$countryGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0' 
												ORDER BY nId ASC");
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

	function getActiveType()
	{
		$typeGroupRow    = array("0" => SELECT, "N" => "Normal", "V" => "Void", "C" => "Conjuction", "R" => "Re issue");
		return ($typeGroupRow);
	}//end function 

	function getRoomType()
	{
		$roomTypeGroupRow = array("0" => SELECT, "SGL" => "Single", "DBL" => "Double", "SUV" => "SUV");
		return ($roomTypeGroupRow);
	}//end function

	function getActiveMode()
	{
		$modeGroupRow = array("Cr" => "Credit", "Ca" => "Cash", "KN" => "KNET");
		return ($modeGroupRow);
	}//end function	

	function getActiveHotels()
	{
        $locGroupRow    = array();
        $locGroupRow[0] = SELECT;
        return ($locGroupRow);
	}//end function

    function getHotelValues($ddlCountry,$ddlCity)
    {
        $hotelGroup    = $this->db->query("SELECT nHId,vName FROM {PRE}Hotel WHERE vActive = '1' AND nCountry = '".$ddlCountry."'
                                           AND nCity = '".$ddlCity."' ORDER BY nHId DESC");
        $hotelGroupRow = array();

        if ($hotelGroup->num_rows() > 0)
        {
            $hotelGroupRow[0] = SELECT;
            foreach ($hotelGroup->result() as $key => $row)
            {
                $hotelGroupRow[$row->nHId] = (stripslashes($row->vName));
            }//end for each
        }//end if
        else
        {
            $hotelGroupRow[0] = NOVALUE;
        }//end else
        return ($hotelGroupRow);
    }//end function

	function getActiveServices()
	{
		$serviceGroup    = $this->db->query("SELECT nServId,vTitle FROM {PRE}Services WHERE vActive = '1' ORDER BY nServId DESC");
		$serviceGroupRow = array();
		
		if ($serviceGroup->num_rows() > 0)
		{
			$serviceGroupRow[0] = SELECT;
			foreach ($serviceGroup->result() as $key => $row)
			{
				$serviceGroupRow[$row->nServId] = (stripslashes($row->vTitle));
			}//end for each
		}//end if
		else
		{
			$serviceGroupRow[0] = NOVALUE;
		}//end else
		return ($serviceGroupRow);
	}//end function

	function getActiveTransfer()
	{
		$transferGroup    = $this->db->query("SELECT * FROM {PRE}Transfer WHERE vActive = '1' ORDER BY nTId DESC");
		$transferGroupRow = array();
		
		if ($transferGroup->num_rows() > 0)
		{
			$transferGroupRow[0] = SELECT;
			foreach ($transferGroup->result() as $key => $row)
			{
				$transferGroupRow[$row->nTId] = (stripslashes($row->vName.' - '.$row->vCode));
			}//end for each
		}//end if
		else
		{
			$transferGroupRow[0] = NOVALUE;
		}//end else
		return ($transferGroupRow);
	}//end function
	
	function getActiveEmployee()
	{
		$empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1' 
											ORDER BY nEId DESC");
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
		$custGroup    = $this->db->query("SELECT nCId,vCode,vName FROM {PRE}CustomerMaster WHERE vActive = '1' 
											ORDER BY nCId DESC");
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

	function getAccounts()
	{
		$accGroupRow = array();
		$accGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId ASC");
		
/*		$accGroup    = $this->db->query("SELECT aca.*,v.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca 
										 LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls 
										    ahc ON ahc.nActHId = aca.nId LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId
										 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' ORDER BY nId DESC");
*/		if ($accGroup->num_rows() > 0)
		{
			$accGroupRow[0] = SELECT;
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
        if($accNameId !='' && $accNameId !=0)
        {
		$accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId 
										  	    LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId 
											 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' AND v.nFId = '".$accNameId."'
											  ORDER BY nId DESC");
        }
        else{
            $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	    WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' ORDER BY nId DESC");
        }
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
											  LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN 
											    {PRE}AccountHeadControls 
										  	  ahc ON ahc.nActHId = aca.nId WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' 
											  	ORDER BY nId DESC");
		if ($agentaccGroup->num_rows() > 0)
		{
			$agentaccGroupRow[0] = SELECT;
			foreach ($agentaccGroup->result() as $key=>$row)
			{
				$agentaccGroupRow[$row->ahaId] = $row->vTitle.' ('.$row->vACode.' )';
			}//end for each
		}//end if
		else
		{
			$agentaccGroupRow[0] = NOVALUE;
		}//end else
		return ($agentaccGroupRow);
	}//end function 

	function getCorrActiveLocation($division)
	{
		$corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."' 
												ORDER BY nPosition DESC");
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

	function getCorrActiveCities($country)
	{
		$corrCityGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."'                                                ORDER BY nId DESC");
		$corrCityGroupRow = array();
		
		if ($corrCityGroup->num_rows() > 0)
		{
			$corrCityGroupRow[0] = SELECT;
			foreach ($corrCityGroup->result() as $key => $row)
			{
				$corrCityGroupRow[$row->nId] = stripslashes($row->vName);
			}//end for each
		}//end if
		else
		{
			$corrCityGroupRow[0] = NOVALUE;
		}//end else
		return ($corrCityGroupRow);
	}//end function
	
	function insertHotelVoucher()
	{
		$tktNo = @split("-",$this->input->post('ddlTktname'));
		
		//insert into hotel voucher master table
	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
						'nLocation' => $this->input->post('ddlLocation'),
						'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
						'vDocNumber' => $this->input->post('txtDocNumber'),'vType' => $this->input->post('ddlType'),
						'vVoucherId' =>$tktNo[0],'vFileId' => $this->input->post('txtFileId'),
						'vIssuedBy' => $this->input->post('ddlIssued'),'vBookedBy' => $this->input->post('ddlBooked'),
						'vPassenger' => $this->input->post('txtPassenger'),'nCountry' => $this->input->post('ddlCountry'),
						'nCity' => $this->input->post('ddlCity'),'vHotelName' => $this->input->post('ddlHotel'),
						'vRoomType' => $this->input->post('txtRoomType'),'vNoOfUnit' => $this->input->post('txtNoOfUnit'),
						'vServices' => $this->input->post('ddlServices'),'nTransfer' => $this->input->post('ddlTransfer'),
						'vExtraService' => $this->input->post('txtExtraServ'),'dCheckIn' => $this->input->post('txtCheckIn'),
						'dCheckOut' => $this->input->post('txtCheckOut'),'vNight' => $this->input->post('txtNights'),
						'vAdult' => $this->input->post('txtAdult'),'vChild' => $this->input->post('txtChild'),
						'vRemarks' => $this->input->post('txtRemarks'),'nAcCode' => $this->input->post('ddlAcCode'),
                        'nAcHead' => $this->input->post('ddlAccHead'),
						'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
						'vTotalRTFC' => $this->input->post('txtRateNetFcTotal'),'vTotalFC' => $this->input->post('txtTotalFc'),
						'vTotalRTDC' => $this->input->post('txtRateNetDcTotal'),'vTotalDC' => $this->input->post('txtTotalDc'),
						'vAgentConfId' => $this->input->post('txtAgentConfId'),
						'vHotelConfId' => $this->input->post('txtHotelConfId'),
						'vBookingId' => $this->input->post('txtBookingId'),'vActive' => $this->input->post('chkActive'),
                        'vTicktManual' => $this->input->post('txtTktName'));
		$this->db->insert('{PRE}HotelVoucher', $data); 
		
		$id	    = $this->db->insert_id();	

		//insert into hotel transfer details table
	    $data   = array('nHVRId' => $id,'dDate1' => $this->input->post('txtArrDate'),
						'vArrivalFrom' => $this->input->post('txtArrFrom'),
						'vFlightId1' => $this->input->post('txtArrFlight'),'vArrivalTime' => $this->input->post('txtArrTime'),
						'dDate2' => $this->input->post('txtDepDate'),'vDepTo' => $this->input->post('txtDepTo'),
						'vFlightId2' =>$this->input->post('txtDepFlight'),'vDepTime' => $this->input->post('txtDepTime'),
						'nNoofGuest' => $this->input->post('txtNoofGuest'),'vVehicle' => $this->input->post('txtVehicle'),
						'vDriverContact' => $this->input->post('txtDriverCont'),
						'vEmergencyContact' => $this->input->post('txtEmrContact'),
						'vPickup' => $this->input->post('txtPickup'),'dPickupDate' => $this->input->post('txtPickupDate'),
						'vDrop' => $this->input->post('txtDrop'),'vRepHotel' => $this->input->post('txtRepHtl'),
						'vCityTour' => $this->input->post('txtCityTour'),'vDurTime' => $this->input->post('txtDuration'),
						'vRemarks' => $this->input->post('txtTransferRemarks'));
		$this->db->insert('{PRE}HotelTransferDetails', $data); 
	
		//hotel details
		$this->txtPayRoomType 	= ($this->input->post('txtPayRoomType') != '')?$this->input->post('txtPayRoomType'):0;
		$this->txtChkinDate    	= ($this->input->post('txtChkinDate') != '')?$this->input->post('txtChkinDate'):0; 
		$this->txtChkoutDate 	= ($this->input->post('txtChkoutDate') != '')?$this->input->post('txtChkoutDate'):0;
		$this->txtNight		    = ($this->input->post('txtNight') != '')?$this->input->post('txtNight'):0;
		$this->txtNoofRooms 	= ($this->input->post('txtNoofRooms') != '')?$this->input->post('txtNoofRooms'):0;
		$this->txtRateNetFc    	= ($this->input->post('txtRateNetFc') != '')?$this->input->post('txtRateNetFc'):0;
		$this->txtTotalinFc 	= ($this->input->post('txtTotalinFc') != '')?$this->input->post('txtTotalinFc'):0;
		$this->txtRateNetDc     = ($this->input->post('txtRateNetDc') != '')?$this->input->post('txtRateNetDc'):0;
		$this->txtTotalinDc 	= ($this->input->post('txtTotalinDc') != '')?$this->input->post('txtTotalinDc'):0;
		$this->txtExchangeRate  = ($this->input->post('txtExchangeRate') != '')?$this->input->post('txtExchangeRate'):0;
		foreach($this->txtPayRoomType as $key => $aitem)
		{
		   //insert into hotel Details table
			$data 	= array('nHotelId' => $id ,'vRoomType' => $aitem,'dCheckInDate' => $this->txtChkinDate[$key],
							'dCheckOutDate' => $this->txtChkoutDate[$key],'nNights' => $this->txtNight[$key],
							'nNoOfRooms' => $this->txtNoofRooms[$key],'nRTNhtFc' => $this->txtRateNetFc[$key],
							'vTotalInFc' => $this->txtTotalinFc[$key],'nRTNhtDc' => $this->txtRateNetDc[$key],
							'vTotalInDC' => $this->txtTotalinDc[$key],'vExchRate' => $this->txtExchangeRate[$key]);
			$this->db->insert('{PRE}HotelDetails', $data);
		}//end foreach
		
	    //insert into Hotel billing Details table
		$data 	= array('nHotelId' => $id ,'nCustomerAc' => $this->input->post('ddlCustomerAc'),
						'vModeofpay' => $this->input->post('ddlMode'),'nAgentAc' => $this->input->post('ddlAgentAc'),
						'vAgentAmt' => $this->input->post('txtAgentAmt'),'vLPO' => $this->input->post('txtLPO'),
						'vSalesAmt' => $this->input->post('txtSalesAmt'),'vDiscount' => $this->input->post('txtDiscount'),
                        'vDiscountAmt' => $this->input->post('txtDiscountAmt'),'vCollected' => $this->input->post('txtCollected'),                        'vServicePercent' => $this->input->post('txtServicePerc'),
						'vServiceAmt' => $this->input->post('txtServiceAmt'));
		$this->db->insert('{PRE}HotelBillingDetails', $data);
		
		//checking Agentaccount exist or not
		if($this->input->post('ddlAgentAc') != '0')
		{
		   //insert into agent account  table
			$data 	= array('nAgentAc' => $this->input->post('ddlAgentAc'),'vType' => 'Hotel',
							'nUserId' => $this->session->userdata('AbsAdmin'),
							'dDate' => date('Y-m-d'),'nBookingId' => $id,'vAgentAmt' => $this->input->post('txtAgentAmt'));
			$this->db->insert('{PRE}AgentAccount', $data);
		}//end function
		
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
			redirect(site_url("hotel_voucher/editHotelvoucher/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("hotel_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if
	}//end class
	
	function updateHotelVoucher()
	{
		$tktNo = @split("-",$this->input->post('ddlTktname'));

	    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),
                        'nLocation' => $this->input->post('ddlLocation'),
                        'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
                        'vDocNumber' => $this->input->post('txtDocNumber'),'vType' => $this->input->post('ddlType'),
                        'vVoucherId' =>$tktNo[0],'vFileId' => $this->input->post('txtFileId'),
                        'vIssuedBy' => $this->input->post('ddlIssued'),'vBookedBy' => $this->input->post('ddlBooked'),
                        'vPassenger' => $this->input->post('txtPassenger'),'nCountry' => $this->input->post('ddlCountry'),
                        'nCity' => $this->input->post('ddlCity'),'vHotelName' => $this->input->post('ddlHotel'),
                        'vRoomType' => $this->input->post('txtRoomType'),'vNoOfUnit' => $this->input->post('txtNoOfUnit'),
                        'vServices' => $this->input->post('ddlServices'),'nTransfer' => $this->input->post('ddlTransfer'),
                        'vExtraService' => $this->input->post('txtExtraServ'),'dCheckIn' => $this->input->post('txtCheckIn'),
                        'dCheckOut' => $this->input->post('txtCheckOut'),'vNight' => $this->input->post('txtNights'),
                        'vAdult' => $this->input->post('txtAdult'),'vChild' => $this->input->post('txtChild'),
                        'vRemarks' => $this->input->post('txtRemarks'),'nAcCode' => $this->input->post('ddlAcCode'),
                        'nAcHead' => $this->input->post('ddlAccHead'),
                        'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),
                        'vTotalRTFC' => $this->input->post('txtRateNetFcTotal'),'vTotalFC' => $this->input->post('txtTotalFc'),
                        'vTotalRTDC' => $this->input->post('txtRateNetDcTotal'),'vTotalDC' => $this->input->post('txtTotalDc'),
                        'vAgentConfId' => $this->input->post('txtAgentConfId'),
                        'vHotelConfId' => $this->input->post('txtHotelConfId'),
                        'vBookingId' => $this->input->post('txtBookingId'),'vActive' => $this->input->post('chkActive'),
                        'vTicktManual' => $this->input->post('txtTktName'));
		$this->db->where('nHVId',$this->input->post('nId'));
		$this->db->update('{PRE}HotelVoucher', $data); 
		$id	        		 	= $this->input->post('nId');

		//update table hotel transfer details
	    $data   = array('nHVRId' => $id,'dDate1' => $this->input->post('txtArrDate'),
						'vArrivalFrom' => $this->input->post('txtArrFrom'),
						'vFlightId1' => $this->input->post('txtArrFlight'),'vArrivalTime' => $this->input->post('txtArrTime'),
						'dDate2' => $this->input->post('txtDepDate'),'vDepTo' => $this->input->post('txtDepTo'),
						'vFlightId2' =>$this->input->post('txtDepFlight'),'vDepTime' => $this->input->post('txtDepTime'),
						'nNoofGuest' => $this->input->post('txtNoofGuest'),'vVehicle' => $this->input->post('txtVehicle'),
						'vDriverContact' => $this->input->post('txtDriverCont'),
						'vEmergencyContact' => $this->input->post('txtEmrContact'),
						'vPickup' => $this->input->post('txtPickup'),'dPickupDate' => $this->input->post('txtPickupDate'),
						'vDrop' => $this->input->post('txtDrop'),'vRepHotel' => $this->input->post('txtRepHtl'),
						'vCityTour' => $this->input->post('txtCityTour'),'vDurTime' => $this->input->post('txtDuration'),
						'vRemarks' => $this->input->post('txtTransferRemarks'));
	
		$this->db->where('nHVRId',$id);
		$this->db->update('{PRE}HotelTransferDetails', $data); 

		//hotel details
		$this->txtPayRoomType 	= ($this->input->post('txtPayRoomType') != '')?$this->input->post('txtPayRoomType'):0;
		$this->txtChkinDate    	= ($this->input->post('txtChkinDate') != '')?$this->input->post('txtChkinDate'):0; 
		$this->txtChkoutDate 	= ($this->input->post('txtChkoutDate') != '')?$this->input->post('txtChkoutDate'):0;
		$this->txtNight		    = ($this->input->post('txtNight') != '')?$this->input->post('txtNight'):0;
		$this->txtNoofRooms 	= ($this->input->post('txtNoofRooms') != '')?$this->input->post('txtNoofRooms'):0;
		$this->txtRateNetFc    	= ($this->input->post('txtRateNetFc') != '')?$this->input->post('txtRateNetFc'):0;
		$this->txtTotalinFc 	= ($this->input->post('txtTotalinFc') != '')?$this->input->post('txtTotalinFc'):0;
		$this->txtRateNetDc     = ($this->input->post('txtRateNetDc') != '')?$this->input->post('txtRateNetDc'):0;
		$this->txtTotalinDc 	= ($this->input->post('txtTotalinDc') != '')?$this->input->post('txtTotalinDc'):0;
		$this->txtExchangeRate  = ($this->input->post('txtExchangeRate') != '')?$this->input->post('txtExchangeRate'):0;
		$chkHotelID       		= $this->input->post('idHAArray');
		$chkPaymtID       		= $this->input->post('idArray');

        foreach($this->txtPayRoomType as $key => $aitem)
        {
            //checking already exists if yes updated
            if(intval($chkHotelID[$key]) != 0)
            {
                //insert into hotel Details table
                $data 	= array('nHotelId' => $id,'vRoomType' => $aitem,'dCheckInDate' => $this->txtChkinDate[$key],
                    'dCheckOutDate' => $this->txtChkoutDate[$key],'nNights' => $this->txtNight[$key],
                    'nNoOfRooms' => $this->txtNoofRooms[$key],'nRTNhtFc' => $this->txtRateNetFc[$key],
                    'vTotalInFc' => $this->txtTotalinFc[$key],
                    'nRTNhtDc' => $this->txtRateNetDc[$key],'vTotalInDC' => $this->txtTotalinDc[$key],
                    'vExchRate' => $this->txtExchangeRate[$key]);
                $this->db->where('nHDId',$chkHotelID[$key]);
                $this->db->update('{PRE}HotelDetails', $data);
            }//end if

            else
            {
                //if new insert into db
                //insert into hotel Details table
                $data 	= array('nHotelId' => $id,'vRoomType' => $aitem,'dCheckInDate' => $this->txtChkinDate[$key],
                                'dCheckOutDate' => $this->txtChkoutDate[$key],'nNights' => $this->txtNight[$key],
                                'nNoOfRooms' => $this->txtNoofRooms[$key],'nRTNhtFc' => $this->txtRateNetFc[$key],
                                'vTotalInFc' => $this->txtTotalinFc[$key],
                                'nRTNhtDc' => $this->txtRateNetDc[$key],'vTotalInDC' => $this->txtTotalinDc[$key],
                                'vExchRate' => $this->txtExchangeRate[$key]);
                $this->db->insert('{PRE}HotelDetails', $data);
            }//end else
        }//end foreach

	   //insert into billing Details table
		$data 	= array('nHotelId' => $id ,'nCustomerAc' => $this->input->post('ddlCustomerAc'),
						'vModeofpay' => $this->input->post('ddlMode'),'nAgentAc' => $this->input->post('ddlAgentAc'),
						'vAgentAmt' => $this->input->post('txtAgentAmt'),'vLPO' => $this->input->post('txtLPO'),
						'vSalesAmt' => $this->input->post('txtSalesAmt'),'vDiscount' => $this->input->post('txtDiscount'),
                        'vDiscountAmt' => $this->input->post('txtDiscountAmt'),'vCollected' => $this->input->post('txtCollected'),
                        'vServicePercent' => $this->input->post('txtServicePerc'),
						'vServiceAmt' => $this->input->post('txtServiceAmt'));
		$this->db->where('nHotelId',$id);
		$this->db->update('{PRE}HotelBillingDetails', $data);

		$chkAgentID = $this->input->post('idAGTArray');	
		//checking already exists if yes updated
		if(intval($chkAgentID[$key]) != 0)
		{
			//checking Agentaccount exist or not
			if($this->input->post('ddlAgentAc') != '0')
			{
			   //update into agent account  table
				$data 	= array('nAgentAc' => $this->input->post('ddlAgentAc'),'vType' => 'Hotel',
								'nUserId' => $this->session->userdata('AbsAdmin'),
								'dDate' => date('Y-m-d'),'nBookingId' => $id,'vAgentAmt' => $this->input->post('txtAgentAmt'));
				$this->db->where('nAAId',$chkAgentID[$key]);
				$this->db->update('{PRE}AgentAccount', $data); 
			}//end function
		}//end if
		else
		{
			//insert into agent account  table
			$data 	= array('nAgentAc' => $this->input->post('ddlAgentAc'),'vType' => 'Hotel',
							'nUserId' => $this->session->userdata('AbsAdmin'),
							'dDate' => date('Y-m-d'),'nBookingId' => $id,'vAgentAmt' => $this->input->post('txtAgentAmt'));
			$this->db->insert('{PRE}AgentAccount', $data);
		}//end else
		
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
            $data 	  = array('vNetAmt' => $this->input->post('txtTotalDc'),
                                'vServPercent' => $this->input->post('txtServicePerc'),'vEmployee' => $this->input->post('ddlIssued'),
                                'vFare' => $this->input->post('txtTotalDc'),
                                'vServAmt' => $this->input->post('txtServiceAmt'),'vDisAmt' => $this->input->post('txtDiscountAmt'),
                                'vSlsAmt' => $this->input->post('txtSalesAmt'), 'vCollected' => $this->input->post('txtCollected'),
                                'vAgentAmt' => $this->input->post('txtAgentAmt'));
            $this->db->where('vType','Htl');
            $this->db->where('vTktNumber',$this->input->post('nId'));
            $this->db->update('{PRE}SalesClosingDetails', $data);

            redirect(site_url($backUrlAddr));
        }
        else
        {
		    redirect(site_url("hotel_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }
	}//end function

    //fetch corresponding hotel details
    function getCorresHotelDetails()
    {
        $hotelRow        	= array();
        $hotelRoomType   	= array();
        $hotelChkinDate  	= array();
        $hotelChkoutDate 	= array();
        $hotelNight      	= array();
        $hotelNoofRooms  	= array();
        $hotelRateNetFc  	= array();
        $hotelTotalinFc  	= array();
        $hotelRateNetDc  	= array();
        $hotelTotalinDc  	= array();
        $hotelExchangeRate  = array();
        $newValArray     	= array();

        $hotelList = $this->db->query("SELECT * FROM {PRE}HotelDetails WHERE nHotelId = '".$this->catId."' ");
        if ($hotelList->num_rows() > 0) 
		{
            foreach ($hotelList->result() as $key => $row) 
			{
                $hotelRow[] 		 = $row->nHDId;
                $hotelRoomType[] 	 = $row->vRoomType;
                $hotelChkinDate[] 	 = $row->dCheckInDate;
                $hotelChkoutDate[] 	 = $row->dCheckOutDate;
                $hotelNight[] 		 = $row->nNights;
                $hotelNoofRooms[] 	 = $row->nNoOfRooms;
                $hotelRateNetFc[] 	 = $row->nRTNhtFc;
                $hotelTotalinFc[] 	 = $row->vTotalInFc;
                $hotelRateNetDc[] 	 = $row->nRTNhtDc;
                $hotelTotalinDc[] 	 = $row->vTotalInDC;
				$hotelExchangeRate[] = $row->vExchRate;
            }//end for each
        }//end if

        $newValArray['hotelId'] 	 = $hotelRow;
        $newValArray['roomType'] 	 = $hotelRoomType;
        $newValArray['chkIndate'] 	 = $hotelChkinDate;
        $newValArray['chkOutdate'] 	 = $hotelChkoutDate;
        $newValArray['night'] 	     = $hotelNight;
        $newValArray['noofRooms'] 	 = $hotelNoofRooms;
        $newValArray['rateNetFc'] 	 = $hotelRateNetFc;
        $newValArray['totalFc'] 	 = $hotelTotalinFc;
        $newValArray['rateNetDc'] 	 = $hotelRateNetDc;
        $newValArray['totalDc']  	 = $hotelTotalinDc;
        $newValArray['exchange']  	 = $hotelExchangeRate;

        return ($newValArray);
    }//end function

	//fetch corresponding hotel voucher details
	function getCorresHotelvoucher()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}HotelVoucher WHERE nHVId='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 

	//fetch corresponding ticket billing details
	function getCorresBillingDetails()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}HotelBillingDetails WHERE nHotelId = '".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 

	function getCorresTransferDetails()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}HotelTransferDetails WHERE nHTId = '".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function
	
    function deleteHotelDetails($ID)
    {
        $this->db->delete('{PRE}HotelDetails', array('nHDId' => $ID));
    }//end function
	
	function deleteHotelVoucher()
	{
		$this->db->delete('{PRE}HotelVoucher', array('nHVId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}HotelDetails', array('nHotelId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}HotelBillingDetails', array('nHotelId' => $this->uri->segment(4))); 
		 
		redirect(site_url("hotel_voucher/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllHotelVoucher()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nHVId',$val);
					$this->db->update('{PRE}HotelVoucher', $data); 
				}//end foreach
				redirect(site_url("hotel_voucher/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nHVId',$val);
					$this->db->update('{PRE}HotelVoucher', $data); 
				}//end foreach
				redirect(site_url("hotel_voucher/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}HotelVoucher', array('nHVId' => $val)); 
					$this->db->delete('{PRE}HotelDetails', array('nHotelId' => $val));
					$this->db->delete('{PRE}HotelBillingDetails', array('nHotelId' => $val)); 
				}//end foreach
				redirect(site_url("hotel_voucher/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	//fetching single details
	function showDetails($hotelId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT hv.*,hbd.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,
												aha.vTitle as accName,aha.vACode as accCode,
												CASE 
													WHEN hv.vType = 'N' THEN 'Normal'
													WHEN hv.vType = 'V' THEN 'Void'
													WHEN hv.vType = 'C' THEN 'Conjuction'
												ELSE 'Re issue' END as hvType
											 FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}Division d ON d.nId = hv.nDivision 
											 	LEFT JOIN {PRE}Location l ON l.nId = hv.nLocation LEFT JOIN 
											{PRE}Transactions t ON t.nId = hv.nTransaction LEFT JOIN {PRE}HotelBillingDetails hbd 
												ON hv.nHVId = hbd.nHotelId LEFT JOIN {PRE}AccountHeadAccounts aha ON hv.nAcCode = aha.nId
										   	 	WHERE hv.nHVId = '".$hotelId."' ORDER BY hv.nHVId DESC");
		
		//single fetch
		$commVal  		= $viewAccessList->row();	
		switch($commVal->vModeofpay)
		{
			case "Cr" :
				$modeofPay	= 'Credit';
			break;
			
			case "Ca" :
				$modeofPay	= 'Cash';
			break;

			case "Card" :
				$modeofPay	= 'Card';
			break;
		}//end switch
		
		$content .= '<table class="reportForm">
						<tr>
							<td colspan="11"><strong>'.ACCNAME.'</strong></td>
						</tr>
						<tr>
							<td><strong>'.DIVISIONNAME.' :</strong>&nbsp;&nbsp;'.$commVal->divName.'</td>
							<td><strong>'.LOCATIONNAME.' :</strong>&nbsp;&nbsp;'.$commVal->locName.'</td>
						    <td><strong>'.CUSTDATE.' :</strong>&nbsp;&nbsp;'.$commVal->dDate.'</td>
							<td><strong>'.TRANSNAME.' :</strong>&nbsp;&nbsp;'.$commVal->transName.'</td>
						    <td><strong>'.DOCNUMBER.' :</strong>&nbsp;&nbsp;'.$commVal->vDocNumber.'</td>
							<td colspan="6"><strong>'.TYPE.' :</strong>&nbsp;&nbsp;'.$commVal->hvType.'</td>
						</tr>
						
						<tr>
						    <td><strong>'.VOUCHERID.' :</strong>&nbsp;&nbsp;'.$commVal->vVoucherId.'</td>
							<td><strong>'.FILEID.' :</strong>&nbsp;&nbsp;'.$commVal->vFileId.'</td>
							<td><strong>'.TICKETNAME.' :</strong>&nbsp;&nbsp;'.$commVal->vTicktManual.'</td>
						    <td><strong>'.BOOKEDBY.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('EmployeeMaster','vName',' WHERE nEId = "'.$commVal->vBookedBy.'" ').'</td>
							<td><strong>'.ISSUEDBY.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('EmployeeMaster','vName',' WHERE nEId = "'.$commVal->vIssuedBy.'" ').'</td>
							<td colspan="6"><strong>'.PASSENGER.' :</strong>&nbsp;&nbsp;'.$commVal->vPassenger.'</td>
						</tr>
						
						<tr>
						    <td><strong>'.COUNTRYNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$commVal->nCountry.'" AND nParentId = "0" ').'</td>
						    <td><strong>'.CITYNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Country','vName',' WHERE nId = "'.$commVal->nCity.'" AND nParentId != "0" ').'</td>
							<td><strong>'.HOTELNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Hotel','vName',' WHERE nHId = "'.$commVal->vHotelName.'" ').'</td>
						    <td><strong>'.ROOMTYPE.' :</strong>&nbsp;&nbsp;'.$commVal->vRoomType.'</td>
							<td><strong>'.NOOFUNIT.' :</strong>&nbsp;&nbsp;'.$commVal->vNoOfUnit.'</td>
							<td colspan="6"><strong>'.SERVICES.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Services','vTitle',' WHERE nServId = "'.$commVal->vServices.'" ').'</td>
						</tr>
						
						<tr>
						    <td><strong>'.CHECKIN.' :</strong>&nbsp;&nbsp;'.$commVal->vCheckIn.'</td>
							<td><strong>'.CHECKOUT.' :</strong>&nbsp;&nbsp;'.$commVal->vCheckOut.'</td>
						    <td><strong>'.NIGHTS.' :</strong>&nbsp;&nbsp;'.$commVal->vNight.'</td>
						    <td><strong>'.ADULT.' :</strong>&nbsp;&nbsp;'.$commVal->vAdult.'</td>
							<td><strong>'.CHILD.' :</strong>&nbsp;&nbsp;'.$commVal->vChild.'</td>
						    <td colspan="6"><strong>'.REMARKS.' :</strong>&nbsp;&nbsp;'.$commVal->vRemarks.'</td>
						</tr>

						<tr>
						    <td><strong>'.ACCCODENAME.' :</strong>&nbsp;&nbsp;'.($commVal->accName).'( '.($commVal->accCode).' )'.'</td>
							<td><strong>'.CURRNAME.' :</strong>&nbsp;&nbsp;'.$this->common_model->get_field_name('Currency','vTitle',' WHERE nId = "'.$commVal->nCurrency.'" ').'</td>
						    <td colspan="9"><strong>'.CURRRATE.' :</strong>&nbsp;&nbsp;'.$commVal->nRate.'</td>
						</tr>
						
						<tr>
							<td><strong>'.SRNO.'</strong></td>
							<td><strong>'.ROOMTYPE.'</strong></td>
							<td><strong>'.INDATE.'</strong></td>
							<td><strong>'.OUTDATE.'</strong></td>
							<td><strong>'.NIGHTS.'</strong></td>
							<td><strong>'.NOOFROOMS.'</strong></td>
							<td><strong>'.RATENETFC.'</strong></td>
							<td><strong>'.TOTALINFC.'</strong></td>
							<td><strong>'.RATENETDC.'</strong></td>
							<td><strong>'.TOTALINDC.'</strong></td>
							<td><strong>'.EXCHRATE.'</strong></td>
						</tr>';
						
						$getHotelDetails = $this->db->query("SELECT *,
																CASE 
																	WHEN vRoomType = 'SGL' THEN 'Single'
																	WHEN vRoomType = 'DBL' THEN 'Double'
																ELSE 'SUV' END as roomType FROM {PRE}HotelDetails 
															WHERE nHotelId = '".$hotelId."'");
						foreach ($getHotelDetails->result() as $key2 => $row2) :
						$content .='<tr>
							<td>'.($key2+1).'</strong></td>
							<td>'.$row2->roomType.'</strong></td>
							<td>'.$row2->dCheckInDate.'</strong></td>
							<td>'.$row2->dCheckOutDate.'</strong></td>
							<td>'.$row2->nNights.'</strong></td>
							<td>'.$row2->nNoOfRooms.'</strong></td>
							<td>'.$row2->nRTNhtFc.'</strong></td>
							<td>'.$row2->vTotalInFc.'</strong></td>
							<td>'.$row2->nRTNhtDc.'</strong></td>
							<td>'.$row2->vTotalInDC.'</strong></td>
							<td>'.$row2->vExchRate.'</strong></td>
						</tr>';
						endforeach;

       $content .='<tr>
                    <td colspan="6" align="right"><strong>'.TOTALAMT.'</strong></td>
					<td>'.$commVal->vTotalRTFC.'</strong></td>
					<td>'.$commVal->vTotalFC.'</strong></td>
					<td>'.$commVal->vTotalRTDC.'</strong></td>
					<td colspan="2">'.$commVal->vTotalDC.'</strong></td>
				   </tr>';

       $content .='<tr>
					<td><strong>'.AGENTCONFID.'</strong></td>
					<td>'.$commVal->vAgentConfId.'</td>
					<td><strong>'.HOTELCONFID.'</strong></td>
					<td>'.$commVal->vHotelConfId.'</td>
					<td><strong>'.BOOKINGID.'</strong></td>
					<td colspan="8">'.$commVal->vBookingId.'</td>
				   </tr>';

       $content .='<tr>
                    <td colspan="11"><strong>'.BILLINGDETAILS.'</strong></td>
                   </tr>

				   <tr>
					<td><strong>'.MODEOFPAYMENT.':</strong></td>
					<td>'.$modeofPay.'</td>

					<td><strong>'.CUSTACCNAME.':</strong></td>
					<td colspan="9">'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$commVal->nAccount.'"').'</td>
				   </tr>
	
				   <tr>
					<td><strong>'.AGENTAC.':</strong></td>
					<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$commVal->nAgentAc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$commVal->nAgentAc.'"').' )'.'</td>

					<td><strong>'.AGENTAMT.':</strong></td>
					<td >'.$commVal->vAgentAmt.'</td>

					<td><strong>'.LPO.':</strong></td>
					<td colspan="6">'.$commVal->vLPO.'</td>
				   </tr>

				   <tr>
				   	<td><strong>'.SALESAMT.':</strong></td>
					<td>'.$commVal->vSalesAmt.'</td>

					<td><strong>'.DISCOUNT.':</strong></td>
					<td>'.$commVal->vDiscount.'</td>

					<td><strong>'.DISCOUNTAMT.':</strong></td>
					<td colspan="6">'.$commVal->vDiscountAmt.'</td>
				   </tr>	
				   
				   <tr>
				   	<td><strong>'.COLECTED.':</strong></td>
					<td>'.$commVal->vCollected.'</td>
					
					<td><strong>'.SERVICEPERCENT.':</strong></td>
					<td >'.$commVal->vServicePercent.'</td>

					<td><strong>'.SERVICEAMT.':</strong></td>
					<td colspan="6">'.$commVal->vServiceAmt.'</td>
				   </tr>			   
				 </table>';
	    return($content);
	}//end function
	
	function getActiveStock()
	{
		$stockGroupRow = array();
        $stockGroup    = $this->db->query("SELECT se.nSEId,se.nLocation,sed.*,st.nLocation,hv.*,s.nTransaction
											 FROM {PRE}StockEntry se
											LEFT JOIN {PRE}HotelVoucher hv ON se.nLocation = hv.nLocation LEFT JOIN
										   {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock
										    LEFT JOIN {PRE}StockTransfer st ON st.nStockDetId = sed.nId
										   LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype WHERE s.nTransaction = '7'
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
											 		st.nQuantity as tQuantity,s.nTransaction,CASE
													WHEN se.nLocation = '".$loc."' THEN	0 ELSE	st.nStockDetId
												END as stockId,	CASE WHEN se.nLocation = '".$loc."' THEN
														sed.vStartNo ELSE st.vStart	END as startNo,
												CASE WHEN se.nLocation = '".$loc."' THEN sed.vEndNo
													ELSE st.vEnd END as endNo, CASE
													WHEN se.nLocation = '".$loc."' THEN
														sed.nQuantity ELSE sed.nQuantity
												END as avlQty, CASE	WHEN st.nQuantity is null THEN 0
															ELSE st.nQuantity END as balQty FROM {PRE}StockEntry se
												LEFT JOIN {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock
										   LEFT JOIN {PRE}StockTransfer st ON st.nStockDetId = sed.nId
										     LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype
												WHERE sed.vActive = '1' AND	CASE WHEN st.nQuantity is null THEN
																(sed.nQuantity - 0) > 0	ELSE (sed.nQuantity - st.nQuantity) > 0
														END	AND (se.nLocation = '".$loc."' OR st.nLocation = '".$loc."')
											 AND s.nTransaction = '7' ORDER BY sed.nId DESC");
		if($stockGroup->num_rows() > 0)
		{
			$stockGroupRow[0] = SELECT;
			foreach($stockGroup->result() as $key => $row)
			{
				if(intval($row->stockId) != 0)
					$balQty               = $row->balQty;
				else
					$balQty               = $row->avlQty-$row->balQty;
				
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

	function getActiveCustomerAccounts()
	{
		$custaccGroupRow = array();
		$custaccGroup    = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
		if ($custaccGroup->num_rows() > 0)
		{
			$custaccGroupRow[0] = SELECT;
			foreach ($custaccGroup->result() as $key=>$row)
			{
				//$custaccGroupRow[$row->nCId] = $row->vName.' ('.$row->vACode.' )';
				$custaccGroupRow[$row->nCId] = $row->vName.' ('.$row->vCode.' )';
			}//end for each
		}//end if
		else
		{
			$custaccGroupRow[0] = NOVALUE;
		}//end else
		return ($custaccGroupRow);
	}//end function 

    function getCloseStatus()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT nHVId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
												ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.vTktNumber
											FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Htl') 
												AND hv.nHVId='".$this->catId."' ORDER BY hv.nHVId DESC)");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

    function getInvoiceNumber()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT vInvoiceNumber FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd 					                                              ON sc.nSCId=scd.nSalesCloseId
		                                    WHERE scd.vTktNumber='".$this->catId."' AND scd.vType = 'Htl')");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

    function getRefundStatus()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT nHVId FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
								    			ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId NOT IN (SELECT scd.nTicketNumber
		                          			FROM {PRE}RefundClosingDetails scd WHERE scd.vType = 'Htl') 
											    AND hv.nHVId='".$this->catId."'
											ORDER BY hv.nHVId DESC)");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

    function getRefundInvoiceNumber()
    {
        $statusGroupRow = array();
        $statusGroup    = $this->db->query("(SELECT vDocNumber FROM {PRE}RefundClosing sc LEFT JOIN {PRE}RefundClosingDetails scd 
												ON sc.nRCId=scd.nRefundCloseId
		                                    WHERE scd.nTicketNumber='".$this->catId."' AND scd.vType = 'Htl')");
        $showVal	     = $statusGroup->row();
        return $showVal;
    }//end function

	function getNextTicket($tktNo,$start,$separator,$end)
	{
		$tktArray    = array();
		
		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}HotelVoucher WHERE 
											vVoucherId = '".$tktNo."' ORDER BY nHVId DESC LIMIT 0,1");
											
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

	function getAllTicket($tktNo,$start,$separator,$end)
	{
		$tktArray    = array();
		
		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}HotelVoucher WHERE 
											vVoucherId = '".$tktNo."' ORDER BY nHVId DESC");
											
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

	function getSelectedHotelDetails($hotelId)
	{
		$hotelRow = array();
		$hotel    = $this->db->query("SELECT hv.*,hd.*,htd.*,hbd.*,t.vTitle as transName, 
										CASE 
											WHEN hv.vType = 'N' THEN 'Normal'
										WHEN hv.vType = 'V' THEN 'Void'
											WHEN hv.vType = 'C' THEN 'Conjuction'
										ELSE 'Re issue' END as hvType
									     FROM {PRE}HotelVoucher hv LEFT JOIN 
									   {PRE}Transactions t ON t.nId = hv.nTransaction
									   LEFT JOIN {PRE}HotelDetails hd 
										 ON hv.nHVId = hd.nHotelId LEFT JOIN {PRE}HotelTransferDetails htd 
									   ON htd.nHVRId = hv.nHVId LEFT JOIN {PRE}HotelBillingDetails hbd 
										ON hbd.nHotelId = hv.nHVId
									   WHERE nHVId = '".$hotelId."' ORDER BY nHVId DESC");
		if ($hotel->num_rows() > 0)
		{
			$hotelRow = $hotel->row();
		}//end if
		return ($hotelRow);
	}//end function

    function getCorresCopyHotelDetails($hotelId)
    {
        $hotelRow        	= array();
        $hotelRoomType   	= array();
        $hotelChkinDate  	= array();
        $hotelChkoutDate 	= array();
        $hotelNight      	= array();
        $hotelNoofRooms  	= array();
        $hotelRateNetFc  	= array();
        $hotelTotalinFc  	= array();
        $hotelRateNetDc  	= array();
        $hotelTotalinDc  	= array();
        $hotelExchangeRate  = array();
        $newValArray     	= array();

        $hotelList = $this->db->query("SELECT * FROM {PRE}HotelDetails WHERE nHotelId = '".$hotelId."' ");
        if ($hotelList->num_rows() > 0) 
		{
            foreach ($hotelList->result() as $key => $row) 
			{
                $hotelRow[] 		 = $row->nHDId;
                $hotelRoomType[] 	 = $row->vRoomType;
                $hotelChkinDate[] 	 = $row->dCheckInDate;
                $hotelChkoutDate[] 	 = $row->dCheckOutDate;
                $hotelNight[] 		 = $row->nNights;
                $hotelNoofRooms[] 	 = $row->nNoOfRooms;
                $hotelRateNetFc[] 	 = $row->nRTNhtFc;
                $hotelTotalinFc[] 	 = $row->vTotalInFc;
                $hotelRateNetDc[] 	 = $row->nRTNhtDc;
                $hotelTotalinDc[] 	 = $row->vTotalInDC;
				$hotelExchangeRate[] = $row->vExchRate;
            }//end for each
        }//end if

        $newValArray['hotelId'] 	 = $hotelRow;
        $newValArray['roomType'] 	 = $hotelRoomType;
        $newValArray['chkIndate'] 	 = $hotelChkinDate;
        $newValArray['chkOutdate'] 	 = $hotelChkoutDate;
        $newValArray['night'] 	     = $hotelNight;
        $newValArray['noofRooms'] 	 = $hotelNoofRooms;
        $newValArray['rateNetFc'] 	 = $hotelRateNetFc;
        $newValArray['totalFc'] 	 = $hotelTotalinFc;
        $newValArray['rateNetDc'] 	 = $hotelRateNetDc;
        $newValArray['totalDc']  	 = $hotelTotalinDc;
        $newValArray['exchange']  	 = $hotelExchangeRate;

        return ($newValArray);
    }//end function

    function getFilterList($extraCheck)
    {
        $adminGroupRow        = array();
        $sqlTicketfilterQuery = "SELECT hv.*,hbd.*,h.vName as hotelName FROM {PRE}HotelVoucher hv  LEFT JOIN {PRE}Hotel h ON h.nHId = hv.vHotelName
									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId  LEFT JOIN
                                    {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}VendorMaster vm ON
                                    hv.nAcCode = vm.nVId LEFT JOIN
                                    {PRE}Location l ON hv.nLocation = l.nId ".$extraCheck." ORDER BY hv.nHVId DESC
                                    LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
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

    function getCountFilterList($extraCheck)
    {
        $tabCount = $this->db->query("SELECT hv.*,hbd.*,h.vName as hotelName FROM {PRE}HotelVoucher hv  LEFT JOIN {PRE}Hotel h ON h.nHId = hv.vHotelName
									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId  LEFT JOIN
                                    {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId LEFT JOIN {PRE}VendorMaster vm ON
                                    hv.nAcCode = vm.nVId LEFT JOIN {PRE}Location l ON hv.nLocation = l.nId ".$extraCheck." ORDER BY hv.nHVId DESC");
        return ($tabCount->num_rows());
    }//end function

}//end class
?>
