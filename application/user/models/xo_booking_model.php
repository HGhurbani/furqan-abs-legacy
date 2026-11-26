<?php

class Xo_booking_model extends CI_Model

{



    function __construct()

    {

        // Call the Model constructor

        parent::__construct();

    }



    /*********************Xo Booking Master******************************/



    function getAllXobooking()

    {

        //active groups

        $adminGroupRow 	   = array();

        $sqlXobookingQuery = "SELECT xo.*,xod.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName, 

                                ar.vTitle as airlineName,ar.vAirlineNo as airlineNo,xo.vXoNumber as tktNumber,

							  gds.vName as gdsName,

                              CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN

                                xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType FROM {PRE}XoBooking xo

                              LEFT JOIN {PRE}Division d ON d.nId = xo.nDivision LEFT JOIN {PRE}Location l ON

                                l.nId = xo.nLocation LEFT JOIN {PRE}Transactions t ON t.nId = xo.nTransaction

                               LEFT JOIN {PRE}Gds gds

                                ON xo.nGDS = gds.nGId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline LEFT JOIN

                              {PRE}XoBillingDetails xod ON xod.nXoId = xo.nXId WHERE xo.nLocation IN ". $this->session->userdata('accessLoc') ." ORDER BY  YEAR(xo.dDate) DESC, MONTH(xo.dDate) DESC, DAY(xo.dDate) DESC,nXId DESC

                                LIMIT " . $this->common_model->_offset . " , " . $this->common_model->_limit;

        session_start();

        $_SESSION['sess_query'] = $sqlXobookingQuery;

        $xoGroupList 			= $this->db->query($sqlXobookingQuery);

        if ($xoGroupList->num_rows() > 0) {

            foreach ($xoGroupList->result() as $key => $row) {

                $xoGroupRow[] = $row;

            } //end for each

        }

        //end if

        return ($xoGroupRow);

    }//end function



    //get user xo booking count

    function getAllXobookingCount()

    {

        //active groups

        $xoGroupRow = array();

        $xoGroupList = $this->db->query("SELECT xo.*,xod.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,

											ar.vTitle as airlineName,ar.vAirlineNo as airlineNo,

										 xo.vXoNumber as tktNumber,gds.vName as gdsName,

										  CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN

											xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType FROM {PRE}XoBooking xo

										  LEFT JOIN {PRE}Division d ON d.nId = xo.nDivision LEFT JOIN {PRE}Location l ON

											l.nId = xo.nLocation LEFT JOIN {PRE}Transactions t ON t.nId = xo.nTransaction

										   LEFT JOIN {PRE}Gds gds

											ON xo.nGDS = gds.nGId LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline LEFT JOIN

										  {PRE}XoBillingDetails xod ON xod.nXoId = xo.nXId ORDER BY nXId DESC");

        return ($xoGroupList->num_rows());

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

				$airlineGroupRow[$row->nArId] =  $row->vTitle . " (" .$row->vAirlineNo . ")";

			}//end for each

		}//end if

		else

		{

			$airlineGroupRow[0] = NOVALUE;

		}//end else



		return ($airlineGroupRow);

	}//end function 



    function getActiveGDS()

    {

        $gdsGroupRow = array();

        $gdsGroup 	 = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");

        if ($gdsGroup->num_rows() > 0) 

		{

           // $gdsGroupRow[1] = SELECT;

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





    function getLocation()

    {

        $locGroupRow    = array();

        $locGroupRow[0] = SELECT;

        return ($locGroupRow);

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



    function getActiveTransactions()

    {

        $transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' ORDER BY nPosition DESC");

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

        $typeGroupRow = array("N" => "Normal", "V" => "Void","R" => "Re issue");

        return ($typeGroupRow);

    } //end function



    function getActiveEmployee()

    {

        $empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY vName ASC");

        $empGroupRow = array();



        if ($empGroup->num_rows() > 0) 

		{

            $empGroupRow[0] = SELECT;

            foreach ($empGroup->result() as $key => $row) 

			{

                $empGroupRow[$row->nEId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');

            }//end for each

        } //end if

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

                $custGroupRow[$row->nCId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');

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

                $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');

            }//end for each

        } //end if

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

                $currGroupRow[$row->nId] = (stripslashes($row->vTitle) . ' (' . stripslashes($row->vCurrcode) . ') ');

            }//end for each

        } //end if

        else 

		{

            $currGroupRow[0] = NOVALUE;

        }//end else

        return ($currGroupRow);

    } //end function



	function getAccounts()

	{

		$accGroupRow = array();

		$accGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId ASC");

		if ($accGroup->num_rows() > 0)

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

            foreach ($agentaccGroup->result() as $key => $row) 

			{

                $agentaccGroupRow[$row->ahaId] = $row->vTitle . ' (' . $row->vACode . ' )';

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

        $corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."' 												ORDER BY nPosition DESC");

        $corrLocGroupRow = array();



        if ($corrLocGroup->num_rows() > 0) 

		{

            $corrLocGroupRow[0] = SELECT;

            foreach ($corrLocGroup->result() as $key => $row) 

			{

                $corrLocGroupRow[$row->nId] = stripslashes($row->vTitle);

            }//end for each

        } //end if

        else 

		{

            $corrLocGroupRow[0] = NOVALUE;

        }//end else

        return ($corrLocGroupRow);

    }//end function



    function insertXobooking()

    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='6'");

		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}XoBooking ORDER BY nXId DESC LIMIT 0,1");

		if($fetchCode->num_rows() > 0):

			$totalNum           = $fetchCode->row();

			$this->txtDocNumber	= $totalNum->vDocNumber;

			$docNumber 			= @split('-',$this->txtDocNumber);

				

			#how many chars will be in the string

			$fill   			= $this->txtMinDigit;

			#the number

			$number 			= ($docNumber[3]+1);

			#with str_pad function the zeros will be added

			$lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);

			// The result: 000056

			

			$countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;

		else:

			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='6'");

			$countVal 			= $this->txtDocNumber;

		endif;
		
		$tktNo = @split("-",$this->input->post('ddlTktname'));

					$vman=$this->input->post('txtTktName');
				$ticchk= $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE vTicktManual='".$this->input->post('txtTktName')."'");
			if($vman==$ticchk){
				$vman=$vman+1;
			}else{
				$vman=$this->input->post('txtTktName'); 
			}




		$xoPass1 	 = (@array_filter($this->input->post('ddlPassenger')) != '') ? $this->input->post('ddlPassenger') : 0;

		$xoFcTax1 	 = (@array_filter($this->input->post('txtFcTax')) != '') ? $this->input->post('txtFcTax') : 0;



		$GrandFcTax = 0;

		$Allpass = '';

		foreach($xoPass1 as $key1 => $pitem) {

			if($Allpass != '') {

				$Allpass .= " , ";

			}

			$Allpass .= $pitem ;

			$GrandFcTax += $xoFcTax1[$key1];

		}



date_default_timezone_set('Asia/Kuwait');

        //insert into xo booking master table

        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'), 

					  'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),

					  'nTransaction' => $this->input->post('ddlTrans'),'vDocNumber' => $countVal,

					  'vXoNumber' => $tktNo[0],'nGDS' => $this->input->post('ddlGDS'),

					  'vType' => $this->input->post('ddlType'),'vIssuedBy' => $this->input->post('ddlIssued'),

					  'vBookedBy' => $this->input->post('ddlBooked'),'vPassengerId' => $this->input->post('txtPassengerId'),

                      'vPassenger' => $Allpass,'clstatus'=>'OPEN',

					  'vPassRemarks' => $this->input->post('txtPassRemarks'),

					  'vOriginalXo' => $this->input->post('txtOriginalXo'),'nAirline' => $this->input->post('ddlAirline'),

					  'vSegment' => $this->input->post('txtSegment'),'vDestination' => $this->input->post('txtDestination'),

					  'vGdspnr' => $this->input->post('txtGdsPNR'),'vAirpnr' => $this->input->post('txtAirPNR'),

					  'nCurrency' => $this->input->post('ddlCurrency'),'nRate' => $this->input->post('txtRate'),

					  'vActive' => $this->input->post('chkActive'),'nPassengerAC' => $this->input->post('ddlAccount'),

					  'vRemarks' => $this->input->post('txtRemarks'),'vGrandFare' => $GrandFcTax,

					  'vGrandTax1' => $this->input->post('txtTax1TotalAmount'),

					  'vGrandTax2' => $this->input->post('txtTax2TotalAmount'),

					  'vGrandTax3' => $this->input->post('txtTax3TotalAmount'),

					  'vGrandToalTax' => $this->input->post('txtTotalTaxAmount'),'vTicktManual' => $vman,

					  'vGrandNetFare' => $this->input->post('txtTotalNetVendorAmount'),'eid' => $this->session->userdata('AbsAdmin'),'edate' => date('Y-m-d H:i:s'));

        $this->db->insert('{PRE}XoBooking', $data);



        $id = $this->db->insert_id();



        //xo details

        $this->txtCarrier 	 = $this->input->post('txtCarrier');

        $this->txtFlightNo 	 = $this->input->post('txtFlightNo');

        $this->txtClass 	 = $this->input->post('txtClass');

        $this->txtTravelDate = $this->input->post('txtTravelDate');

        $this->txtSector 	 = $this->input->post('txtSector');

        $this->txtSector2 	 = $this->input->post('txtSector2');

        $this->txtStatus 	 = $this->input->post('txtStatus');

        $this->txtTimeDep 	 = $this->input->post('txtTimeDep');

        $this->txtTimeArr 	 = $this->input->post('txtTimeArr');

        $this->txtDay 	     = 0;//$this->input->post('txtDay');



        if (!empty ($this->txtCarrier)) 

		{

            foreach ($this->txtCarrier as $key => $aitem) 

            {

              if( $this->txtTravelDate[$key] == '00-00-0000' || $this->txtTravelDate[$key]  == '' || $this->txtTravelDate[$key] == 0){



                $this->txtTravelDate[$key] = '';

              }

              else {

                $this->txtTravelDate[$key] = date('Y-m-d',strtotime( $this->txtTravelDate[$key] ));

              }



                $data = array('nXoId' => $id,'vCarrier' => $aitem,'vFlight' => $this->txtFlightNo[$key],

							  'vClass' => $this->txtClass[$key],'dTravelDate' =>$this->txtTravelDate[$key], 

							  'vSector' => $this->txtSector[$key],'vSector2' => $this->txtSector2[$key],

							  'vStatus' => $this->txtStatus[$key],'vTimeDept' => $this->txtTimeDep[$key],

							  'vTimeArr' => $this->txtTimeArr[$key]);
							  //'vDay' => $this->txtDay[$key]

                $this->db->insert('{PRE}XoDetails', $data);

            }//end foreach

        }//end if



        //xo pass details

        $this->ddlPass      = $this->input->post('ddlPassenger');

        $this->txtFcTax     = $this->input->post('txtFcTax');

        $this->txtCommTax   = $this->input->post('txtCommTax');

        $this->txtORCAmt    = $this->input->post('txtORCAmt');

        $this->txtFc1Tax    = $this->input->post('txtFc1Tax');

        $this->txtFc2Tax    = $this->input->post('txtFc2Tax');

        $this->txtFc3Tax    = $this->input->post('txtFc3Tax');

        $this->txtTotalTax  = $this->input->post('txtTotalTax');

        $this->txtNetVendor = $this->input->post('txtNetVendor');

        $this->txtXoSalesAmt = $this->input->post('txtXoSalesAmt');



        //insert into Xo Pass Details table

        foreach ($this->txtFcTax as $key => $pitem) {

            $data = array('nXoId' => $id,'vPass' => $this->ddlPass[$key],'vFCTax' => $pitem,

						  'nComm' => $this->txtCommTax[$key],'nORCAmt' => $this->txtORCAmt[$key],'vTax1' => $this->txtFc1Tax[$key],

						  'vTax2' => $this->txtFc2Tax[$key],'vTax3' => $this->txtFc3Tax[$key],

						  'vTaxTotal' => $this->txtTotalTax[$key],

						  'nNetVendor' => $this->txtNetVendor[$key],

						  'nSalesAmt' => $this->txtXoSalesAmt[$key]);

            $this->db->insert('{PRE}XoPassDetails', $data);

        }//end foreach



        //billing details

        $this->txtInvoice 	  = $this->input->post('txtInvoice');

        $this->ddlMode 		  = $this->input->post('ddlMode');

        $this->ddlAccountName = $this->input->post('ddlAccountName');

        $this->ddlCustomerAc  = $this->input->post('ddlCustomerAc');;

        $this->ddlAgentAc 	  = $this->input->post('ddlAgentAc');

        $this->txtAgentAmt 	  = $this->input->post('txtAgentAmt');

        $this->txtLPO 		  = $this->input->post('txtLPO');

        $this->txtSalesAmt 	  = $this->input->post('txtCollected');

        $this->txtDiscount    = $this->input->post('txtDiscount');

        $this->txtDiscountAmt = $this->input->post('txtDiscountAmt');

        $this->txtCollected   = $this->input->post('txtSalesAmt');

        $this->txtServicePerc = $this->input->post('txtServicePerc');

        $this->txtServiceAmt  = $this->input->post('txtServiceAmt');



        //insert into Xo Billing Details table

        $data = array('nXoId' => $id,'nAccName' => $this->ddlAccountName,'vModeofpay' => $this->ddlMode,

					  'nCustomerAc' => $this->ddlCustomerAc,'nAgentAc' => $this->ddlAgentAc,'vInvoice' => $this->txtInvoice,

					  'vLPO' => $this->txtLPO,'vSalesAmt' => $this->txtCollected,'vDiscount' => $this->txtDiscount,

					  'vDiscountAmt' => $this->txtDiscountAmt, 'vAgentAmt' => $this->txtAgentAmt, 

					  'vCollected' => $this->txtCollected,

					  'vServicePercent' => $this->txtServicePerc, 'vServiceAmt' => $this->txtServiceAmt);

        $this->db->insert('{PRE}XoBillingDetails', $data);



/*

		//checking Agentaccount exist or not

		if($this->input->post('ddlAgentAc') != '0')

		{

		   //insert into agent account  table

			$data 	= array('nAgentAc' => $this->input->post('ddlAgentAc'),'vType' => 'Xo',

							'nUserId' => $this->session->userdata('AbsAdmin'),

							'dDate' => date('Y-m-d'),'nBookingId' => $id,'vAgentAmt' => $this->input->post('txtAgentAmt'));

			$this->db->insert('{PRE}AgentAccount', $data);

		}//end function

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



        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '') {

            redirect(site_url("xo_booking/editXobooking/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));

        }//end if

        if ($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '') {

            redirect(site_url("xo_booking/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));

        }//end if

    }//end function



    function updateXobooking()

    {
		
		

		$tktNo = @split("-",$this->input->post('ddlTktname'));



        $xoPass1 	 = (@array_filter($this->input->post('ddlPassenger')) != '') ? $this->input->post('ddlPassenger') : 0;

        $xoFcTax1 	 = (@array_filter($this->input->post('txtFcTax')) != '') ? $this->input->post('txtFcTax') : 0;

		

		$GrandFcTax = 0;

		$Allpass = '';

		foreach($xoPass1 as $key1 => $pitem) {

			if($Allpass != '') {

				$Allpass .= " , ";

			}

			$Allpass .= $pitem ;

			$GrandFcTax += $xoFcTax1[$key1];

		}
		date_default_timezone_set('Asia/Kuwait');
        //update into XOBilling table

        $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'nDivision' => $this->input->post('ddlDivision'),

					  'nLocation' => $this->input->post('ddlLocation'),'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),

					  'nTransaction' => $this->input->post('ddlTrans'), 'vDocNumber' => $this->input->post('txtDocNumber'),

					  'vXoNumber' => $tktNo[0],'nGDS' => $this->input->post('ddlGDS'),

					  'vType' => $this->input->post('ddlType'), 'vIssuedBy' => $this->input->post('ddlIssued'),

					  'vBookedBy' => $this->input->post('ddlBooked'),'vPassengerId' => $this->input->post('txtPassengerId'),

                      'vPassenger' => $Allpass ,'clstatus'=>$this->input->post('clstatus'),

					  'vPassRemarks' => $this->input->post('txtPassRemarks'),

					  'vOriginalXo' => $this->input->post('txtOriginalXo'), 'nAirline' => $this->input->post('ddlAirline'),

					  'vSegment' => $this->input->post('txtSegment'), 'vDestination' => $this->input->post('txtDestination'),

					  'vGdspnr' => $this->input->post('txtGdsPNR'), 'vAirpnr' => $this->input->post('txtAirPNR'),

					  'nCurrency' => $this->input->post('ddlCurrency'), 'nRate' => $this->input->post('txtRate'),

					  'vActive' => $this->input->post('chkActive'), 'nPassengerAC' => $this->input->post('ddlAccount'),

					  'vRemarks' => $this->input->post('txtRemarks'), 'vGrandFare' => $GrandFcTax,

					  'vGrandTax1' => $this->input->post('txtTax1TotalAmount'), 

					  'vGrandTax2' => $this->input->post('txtTax2TotalAmount'),

				      'vGrandTax3' => $this->input->post('txtTax3TotalAmount'), 

					  'vGrandToalTax' => $this->input->post('txtTotalTaxAmount'),'vTicktManual' => $this->input->post('txtTktName'),

					  'vGrandNetFare' => $this->input->post('txtTotalNetVendorAmount'),
					  'uid' => $this->session->userdata('AbsAdmin'),'udate' => date('Y-m-d H:i:s'));

        $this->db->where('nXId', $this->input->post('nId'));

        $this->db->update('{PRE}XoBooking', $data);



        $id = $this->input->post('nId');



        //xo details

        $xoCarrier    = (@array_filter($this->input->post('txtCarrier')) != '') ? $this->input->post('txtCarrier') : 0;

        $xoFlightNo   = (@array_filter($this->input->post('txtFlightNo')) != '') ? $this->input->post('txtFlightNo') : 0;

        $xoClass 	  = (@array_filter($this->input->post('txtClass')) != '') ? $this->input->post('txtClass') : 0;

        $xoTravelDate= (@array_filter($this->input->post('txtTravelDate')) != '') ? $this->input->post('txtTravelDate') : 0;

        $xoSector     = (@array_filter($this->input->post('txtSector')) != '') ? $this->input->post('txtSector') : 0;

        $xoSector2    = (@array_filter($this->input->post('txtSector2')) != '') ? $this->input->post('txtSector2') : 0;

        $xoStatus  	  = (@array_filter($this->input->post('txtStatus')) != '') ? $this->input->post('txtStatus') : 0;

        $xoTimeDep 	  = (@array_filter($this->input->post('txtTimeDep')) != '') ? $this->input->post('txtTimeDep') : 0;

        $xoTimeArr 	  = (@array_filter($this->input->post('txtTimeArr')) != '') ? $this->input->post('txtTimeArr') : 0;

        $xoDay     	  = (@array_filter($this->input->post('txtDay')) != '') ? $this->input->post('txtDay') : 0;

        $chkAccID  	  = $this->input->post('idArray');



        foreach ($xoCarrier as $key => $aitem) 

		{

			//checking already exists if yes updated

			if(intval($chkAccID[$key]) != 0)

			{

              if($xoTravelDate[$key] == '00-00-0000' || $xoTravelDate[$key]  == '' || $xoTravelDate[$key] == 0){



                $xoTravelDate[$key] = '';

              }

              else {

                $xoTravelDate[$key] = date('Y-m-d',strtotime( $xoTravelDate[$key] ));

              }





			   //insert into xo Details table

				$data = array('nXoId' => $id, 'vCarrier' => $aitem, 'vFlight' => $xoFlightNo[$key],

							  'vClass' => $xoClass[$key], 'dTravelDate' =>  $xoTravelDate[$key],

							  'vSector' => $xoSector[$key], 'vSector2' => $xoSector2[$key], 'vStatus' => $xoStatus[$key],

							  'vTimeDept' => $xoTimeDep[$key], 'vTimeArr' => $xoTimeArr[$key]);
							  //, 'vDay' => $xoDay[$key]

				$this->db->where('nXDId', $chkAccID[$key]);

				$this->db->update('{PRE}XoDetails', $data);

			}//end if

			

			else

			{

				//if new insert into db

			   //insert into xo Details table

				$data = array('nXoId' => $id, 'vCarrier' => $aitem, 'vFlight' => $xoFlightNo[$key],

						  'vClass' => $xoClass[$key], 'dTravelDate' =>  date('Y-m-d', strtotime($xoTravelDate[$key])),

						  'vSector' => $xoSector[$key], 'vSector2' => $xoSector2[$key], 'vStatus' => $xoStatus[$key],

						  'vTimeDept' => $xoTimeDep[$key], 'vTimeArr' => $xoTimeArr[$key]);//'vDay' => $xoDay[$key]

				$this->db->insert('{PRE}XoDetails', $data);

			}//end else



        }//end foreach



        //xo pass details

        $xoPass 	 = (@array_filter($this->input->post('ddlPassenger')) != '') ? $this->input->post('ddlPassenger') : 0;

        $xoFcTax 	 = (@array_filter($this->input->post('txtFcTax')) != '') ? $this->input->post('txtFcTax') : 0;

        $xoCommTax 	 = (@array_filter($this->input->post('txtCommTax')) != '') ? $this->input->post('txtCommTax') : 0;

        $xoORCAmt 	 = (@array_filter($this->input->post('txtORCAmt')) != '') ? $this->input->post('txtORCAmt') : 0;

        $xoFc1Tax 	 = (@array_filter($this->input->post('txtFc1Tax')) != '') ? $this->input->post('txtFc1Tax') : 0;

        $xoFc2Tax 	 = (@array_filter($this->input->post('txtFc2Tax')) != '') ? $this->input->post('txtFc2Tax') : 0;

        $xoFc3Tax 	 = (@array_filter($this->input->post('txtFc3Tax')) != '') ? $this->input->post('txtFc3Tax') : 0;

        $xoTotalTax  = (@array_filter($this->input->post('txtTotalTax')) != '') ? $this->input->post('txtTotalTax') : 0;

        $xoNetVendor = (@array_filter($this->input->post('txtNetVendor')) != '') ? $this->input->post('txtNetVendor') : 0;

        $xoSalesAmt = (@array_filter($this->input->post('txtXoSalesAmt')) != '') ? $this->input->post('txtXoSalesAmt') : 0;

        $chkPassID   = $this->input->post('idPArray');



		foreach($xoPass as $key1 => $pitem)

		{

			//checking already exists if yes updated

			if(intval($chkPassID[$key1]) != 0)

			{

			   //insert into xo pass Details table

				$data = array('nXoId' => $id, 'vPass' => $pitem, 'vFCTax' => $xoFcTax[$key1],

						  'nComm' => $xoCommTax[$key1], 'nORCAmt' => $xoORCAmt[$key1], 'vTax1' => $xoFc1Tax[$key1],

						  'vTax2' => $xoFc2Tax[$key1], 'vTax3' => $xoFc3Tax[$key1], 'vTaxTotal' => $xoTotalTax[$key1],

						  'nNetVendor' => $xoNetVendor[$key1],

						  'nSalesAmt' => $xoSalesAmt[$key1]);

				$this->db->where('nXPId', $chkPassID[$key1]);

				$this->db->update('{PRE}XoPassDetails', $data);

			}//end if

			

			else

			{

				//if new insert into db

			   //insert into xo pass Details table

				$data = array('nXoId' => $id, 'vPass' => $pitem, 'vFCTax' => $xoFcTax[$key1],

						  'nComm' => $xoCommTax[$key1], 'nORCAmt' => $xoORCAmt[$key1], 'vTax1' => $xoFc1Tax[$key1],

						  'vTax2' => $xoFc2Tax[$key1], 'vTax3' => $xoFc3Tax[$key1], 'vTaxTotal' => $xoTotalTax[$key1],

						  'nNetVendor' => $xoNetVendor[$key1],

						  'nSalesAmt' => $xoSalesAmt[$key1]);

				$this->db->insert('{PRE}XoPassDetails', $data);

			}//end else



		}//end foreach



        //billing details

        $this->txtInvoice 	  = $this->input->post('txtInvoice');

        $this->ddlMode 		  = $this->input->post('ddlMode');

        $this->ddlAccountName = $this->input->post('ddlAccountName');

        $this->ddlCustomerAc  = $this->input->post('ddlCustomerAc');

        $this->ddlAgentAc     = $this->input->post('ddlAgentAc');

        $this->txtAgentAmt 	  = $this->input->post('txtAgentAmt');

        $this->txtLPO 		  = $this->input->post('txtLPO');

        $this->txtSalesAmt 	  = $this->input->post(' txtCollected');

        $this->txtDiscount 	  = $this->input->post('txtDiscount');

        $this->txtDiscountAmt = $this->input->post('txtDiscountAmt');

        $this->txtCollected   = $this->input->post('txtSalesAmt');

        $this->txtServicePerc = $this->input->post('txtServicePerc');

        $this->txtServiceAmt  = $this->input->post('txtServiceAmt');



        //update into Xo Billing Details table

        $data = array('nXoId' => $id,'nAccName' => $this->ddlAccountName,'vModeofpay' => $this->ddlMode,

					  'nCustomerAc' => $this->ddlCustomerAc,'nAgentAc' => $this->ddlAgentAc,'vInvoice' => $this->txtInvoice,

					  'vLPO' => $this->txtLPO,'vSalesAmt' => $this->txtCollected,'vDiscount' => $this->txtDiscount,

					  'vDiscountAmt' => $this->txtDiscountAmt,'vAgentAmt' => $this->txtAgentAmt,'vCollected' => $this->txtCollected,

					  'vServicePercent' => $this->txtServicePerc,'vServiceAmt' => $this->txtServiceAmt);

        $this->db->where('nXoId', $this->input->post('nId'));

        $this->db->update('{PRE}XoBillingDetails', $data);

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

			$data 	= array('nAgentAc' => $this->input->post('ddlAgentAc'),'vType' => 'Xo',

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
$admin_email = "support@abskw.com";
		$email = "xobooking@abskw.com";
		$comment='';
		$subject = "XO Booking Updated : ".$this->input->post('txtTktName');
		$eid=$this->common_model->get_field_name('XoBooking','eid'," WHERE nXId = '".$this->input->post('nId')."'");
		$comment .= "\n\nEntered Name : ".$this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$eid."' ");;
		$comment .= "\n\nEnter Date : ".$this->common_model->get_field_name('XoBooking','edate'," WHERE nXId = '".$this->input->post('nId')."'");
		$comment .= "\n\nUpdated Name : ".$this->session->userdata(showName) . "-" . $this->session->userdata(showLName);;
		$comment .= "\n\nUpdated Date : ".date('Y-m-d H:i:s');
		$message = '';
		$hotelList = $this->db->query("SELECT * FROM {PRE}SalesClosingDetails WHERE vType='Xo' AND vTktNumber = '".$this->input->post('nId')."' ");
		if ($hotelList->num_rows() > 0)

		{

			foreach ($hotelList->result() as $key=>$row)

			{
				$message .= "\n\nSalesCloseId : ".$row->nSalesCloseId;
				//$message .= "\n\nType : ".$row->vType;
				$message .= "\n\nvTktNumber : ".$tktNum1 = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $row->vTktNumber."'");
				//$message .= "\n\n Airline : ".$row->nAirline;
				$message .= "\n\nEmployee : ".$row->vEmployee;
				//$message .= "\n\nFare : ".$row->vFare;
				$message .= "\n\nNetAmt : ".$row->vNetAmt;
				//$message .= "\n\nSalesAmt : ".$row->vSlsAmt;
				$message .= "\n\nCollected : ".$row->vCollected;
				//$message .= "\n\nIssued : ".$row->issue;
				
				
			}//end for each

		}//end if
		$message1='';
		$hotelList1 = $this->db->query("SELECT * FROM {PRE}XoBooking WHERE  nXId='". $row->vTktNumber."'");
		if ($hotelList1->num_rows() > 0)

		{

			foreach ($hotelList1->result() as $key=>$row)

			{
				$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $row->nPassengerAC  ."'"); 

				$nFId = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = '". $row->nPassengerAC  ."'");
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$nFId."'");

				$message1 .= "\n\nAcCode : ".$drAccountName;
				$message1 .= "\n\nAcHead : ".$subAccName;
				
				$message1 .= "\n\nNetAmt : ".$row->vGrandNetFare;
				$message1 .= "\n\nIssued : ".$this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' ");
				
				
			}//end for each

		}//end if
		
		
		$comment .= "\n\n Xo A/c Details : ".$message1;
		$comment .= "\n\n Sales Closing Details : ".$message;
		mail($admin_email, "$subject", $comment, "From:" . $email);
          

$empcode = $this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$this->input->post('ddlIssued')."' ");

$ac=$this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$this->input->post('ddlAirline')."' ");

            $data 	  = array('nAirline' => $ac,'vNetAmt' => $this->input->post('txtTotalNetVendorAmount'),

                                'vServPercent' => $this->input->post('txtServicePerc'),'vEmployee' => $empcode ,

                                'vTax' => $this->input->post('txtTotalTaxAmount'),

                                'vServAmt' => $this->input->post('txtServiceAmt'),'vDisAmt' => $this->input->post('txtDiscountAmt'),

                                //'vSlsAmt' => $this->input->post('txtSalesAmt'), 

                                 'vCollected' => $this->input->post('txtSalesAmt'));

                                //'vAgentAmt' => $this->input->post('txtAgentAmt'));

            $this->db->where('vType','Xo');

            $this->db->where('vTktNumber',$this->input->post('nId'));

            $this->db->update('{PRE}SalesClosingDetails', $data);



            redirect(site_url($backUrlAddr));

        }

        else

        {

            redirect(site_url("xo_booking/showlist/" . $this->uri->segment(3) . '/' . $msgVal.$showId));

        }

    }//end function



    function getCarrierTkt()

    {

        $carrierRow = array();

        $carrier 	= $this->db->query("SELECT vTktCarrier FROM {PRE}XoDetails WHERE nXoId = '".$this->catId."'");



        if ($carrier->num_rows() > 0) {

            foreach ($carrier->result() as $key => $row) {

                $carrierRow[] = $row->vTktCarrier;

            }//end for each

        }//end if

        return (array_filter($carrierRow));

    }//end function



    function getFlightNoTkt()

    {

        $flightNoRow = array();

        $flightNo 	 = $this->db->query("SELECT vTktFlight FROM {PRE}XoDetails WHERE nXoId = '".$this->catId."'");



        if ($flightNo->num_rows() > 0) {

            foreach ($flightNo->result() as $key => $row) {

                $flightNoRow[] = $row->vTktFlight;

            }//end for each

        }//end if

        return (array_filter($flightNoRow));

    }//end function



    function getNetVendor()

    {

        $NetVendorRow = array();

        $NetVendor 	  = $this->db->query("SELECT nNetVendor FROM {PRE}XoPassDetails WHERE nXoId = '".$this->catId."'");



        if ($NetVendor->num_rows() > 0) {

            foreach ($NetVendor->result() as $key => $row) {

                $NetVendorRow[] = $row->nNetVendor;

            }//end for each

        }//end if

        return (array_filter($NetVendorRow));

    }//end function



    function getTotalTax()

    {

        $TotalTaxRow = array();

        $TotalTax 	 = $this->db->query("SELECT vTaxTotal FROM {PRE}XoPassDetails WHERE nXoId = '".$this->catId."'");



        if ($TotalTax->num_rows() > 0) {

            foreach ($TotalTax->result() as $key => $row) {

                $TotalTaxRow[] = $row->vTaxTotal;

            }//end for each

        }//end if

        return (array_filter($TotalTaxRow));

    }//end function



    function getFc3Tax()

    {

        $Fc3TaxRow = array();

        $Fc3Tax    = $this->db->query("SELECT vTax3 FROM {PRE}XoPassDetails WHERE nXoId = '".$this->catId."'");



        if ($Fc3Tax->num_rows() > 0) {

            foreach ($Fc3Tax->result() as $key => $row) {

                $Fc3TaxRow[] = $row->vTax3;

            }//end for each

        }//end if

        return (array_filter($Fc3TaxRow));

    }//end function



    function getFc2Tax()

    {

        $Fc2TaxRow = array();

        $Fc2Tax    = $this->db->query("SELECT vTax2 FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");



        if ($Fc2Tax->num_rows() > 0) {

            foreach ($Fc2Tax->result() as $key => $row) {

                $Fc2TaxRow[] = $row->vTax2;

            }//end for each

        }//end if

        return (array_filter($Fc2TaxRow));

    }//end function



    function getFc1Tax()

    {

        $Fc1TaxRow = array();

        $Fc1Tax    = $this->db->query("SELECT vTax1 FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");



        if ($Fc1Tax->num_rows() > 0) {

            foreach ($Fc1Tax->result() as $key => $row) {

                $Fc1TaxRow[] = $row->vTax1;

            }//end for each

        }//end if

        return (array_filter($Fc1TaxRow));

    }//end function



    function getORCAmt()

    {

        $ORCAmtRow = array();

        $ORCAmt    = $this->db->query("SELECT nORCAmt FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");



        if ($ORCAmt->num_rows() > 0) {

            foreach ($ORCAmt->result() as $key => $row) {

                $ORCAmtRow[] = $row->nORCAmt;

            }//end for each

        }//end if

        return (array_filter($ORCAmtRow));

    }//end function



    function getPass()

    {

        $PassRow = array();

        $Pass    = $this->db->query("SELECT vPass FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");



        if ($Pass->num_rows() > 0) {

            foreach ($Pass->result() as $key => $row) {

                $PassRow[] = $row->vPass;

            }//end for each

        }//end if

        return (array_filter($PassRow));

    }//end function



    function getFcTax()

    {

        $FcTaxRow = array();

        $FcTax    = $this->db->query("SELECT vFCTax FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");

        if ($FcTax->num_rows() > 0) {

            foreach ($FcTax->result() as $key => $row) {

                $FcTaxRow[] = $row->vFCTax;

            }//end for each

        }//end if

        return (array_filter($FcTaxRow));

    }//end function



    function getCommTax()

    {

        $CommTaxRow = array();

        $CommTax    = $this->db->query("SELECT nComm FROM {PRE}XoPassDetails WHERE nXoId = '" . $this->catId . "'");



        if ($CommTax->num_rows() > 0) {

            foreach ($CommTax->result() as $key => $row) {

                $CommTaxRow[] = $row->nComm;

            }//end for each

        }//end if

        return (array_filter($CommTaxRow));

    }//end function



    //get xo carrier details

    function getCarrier()

    {

        $carrierRow = array();

        $carrier    = $this->db->query("SELECT vCarrier FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($carrier->num_rows() > 0) {

            foreach ($carrier->result() as $key => $row) {

                $carrierRow[] = $row->vCarrier;

            }//end for each

        }//end if

        return (array_filter($carrierRow));

    }//end function



    //get xo flight details

    function getFlightNo()

    {

        $flightNoRow = array();

        $flightNo 	 = $this->db->query("SELECT vFlight FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($flightNo->num_rows() > 0) {

            foreach ($flightNo->result() as $key => $row) {

                $flightNoRow[] = $row->vFlight;

            }//end for each

        }//end if

        return (array_filter($flightNoRow));

    }//end function



    //get xo class details

    function getClass()

    {

        $classRow = array();

        $class 	  = $this->db->query("SELECT vClass FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($class->num_rows() > 0) {

            foreach ($class->result() as $key => $row) {

                $classRow[] = $row->vClass;

            }//end for each

        }//end if

        return (array_filter($classRow));

    }//end function



    //get xo travel date

    function getTravelDate()

    {

        $travelDateRow = array();

        $travelDate    = $this->db->query("SELECT dTravelDate FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($travelDate->num_rows() > 0) {

            foreach ($travelDate->result() as $key => $row) {

                $travelDateRow[] = $row->dTravelDate;

            }//end for each

        }//end if

        return (array_filter($travelDateRow));

    }//end function



    //get xo sector

    function getSector()

    {

        $sectorRow = array();

        $sector    = $this->db->query("SELECT vSector FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($sector->num_rows() > 0) {

            foreach ($sector->result() as $key => $row) {

                $sectorRow[] = $row->vSector;

            }//end for each

        }//end if

        return (array_filter($sectorRow));

    }//end function



    //get xo sector 2

    function getSector2()

    {

        $sector2Row = array();

        $sector2 	= $this->db->query("SELECT vSector2 FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($sector2->num_rows() > 0) {

            foreach ($sector2->result() as $key => $row) {

                $sector2Row[] = $row->vSector2;

            }//end for each

        }//end if

        return (array_filter($sector2Row));

    }//end function



    //get xo status

    function getStatus()

    {

        $statusRow = array();

        $status    = $this->db->query("SELECT vStatus FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($status->num_rows() > 0) {

            foreach ($status->result() as $key => $row) {

                $statusRow[] = $row->vStatus;

            }//end for each

        }//end if

        return (array_filter($statusRow));

    }//end function



    //get xo time departure

    function getTimeDep()

    {

        $timeDepRow = array();

        $timeDep    = $this->db->query("SELECT vTimeDept FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($timeDep->num_rows() > 0) {

            foreach ($timeDep->result() as $key => $row) {

                $timeDepRow[] = $row->vTimeDept;

            }//end for each

        }//end if

        return (array_filter($timeDepRow));

    }//end function



    //get xo time arrival

    function getTimeArr()

    {

        $timeArrRow = array();

        $timeArr    = $this->db->query("SELECT vTimeArr FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($timeArr->num_rows() > 0) {

            foreach ($timeArr->result() as $key => $row) {

                $timeArrRow[] = $row->vTimeArr;

            }//end for each

        }//end if

        return (array_filter($timeArrRow));

    }//end function



    //get xo day

    function getDay()

    {

        $dayRow = array();

        $day    = $this->db->query("SELECT vDay FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($day->num_rows() > 0) {

            foreach ($day->result() as $key => $row) {

                $dayRow[] = $row->vDay;

            }//end for each

        }//end if

        return (array_filter($dayRow));

    }//end function



    //get xo details id

    function getXoID()

    {

        $xoIdRow = array();

        $xoId    = $this->db->query("SELECT nXDId FROM {PRE}XoDetails WHERE nXoId = '" . $this->catId . "'");



        if ($xoId->num_rows() > 0) {

            foreach ($xoId->result() as $key => $row) {

                $xoIdRow[] = $row->nFId;

            }//end for each

        }//end if

        return (array_filter($xoIdRow));

    }//end function



    //get airline other details contact name

    function getAirlineName()

    {

        $airlineNameRow = array();

        $airlineName    = $this->db->query("SELECT vName FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineName->num_rows() > 0) {

            foreach ($airlineName->result() as $key => $row) {

                $airlineNameRow[] = $row->vName;

            } //end for each

        }//end if



        return (array_filter($airlineNameRow));

    }//end function



    //get airline other details contact designation

    function getAirlineDesignation()

    {

        $airlineDesRow = array();

        $airlineDes    = $this->db->query("SELECT vDesignation FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineDes->num_rows() > 0) {

            foreach ($airlineDes->result() as $key => $row) {

                $airlineDesRow[] = $row->vDesignation;

            }//end for each

        }//end if



        return (array_filter($airlineDesRow));

    }//end function



    //get airline other details contact mobile

    function getAirlineMobile()

    {

        $airlineMobileRow = array();

        $airlineMobile    = $this->db->query("SELECT vMobile FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineMobile->num_rows() > 0) {

            foreach ($airlineMobile->result() as $key => $row) {

                $airlineMobileRow[] = $row->vMobile;

            }//end for each

        }//end if



        return (array_filter($airlineMobileRow));

    }//end function



    //get airline other details contact phone

    function getAirlinePhone()

    {

        $airlinePhoneRow = array();

        $airlinePhone    = $this->db->query("SELECT vPhone FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlinePhone->num_rows() > 0) {

            foreach ($airlinePhone->result() as $key => $row) {

                $airlinePhoneRow[] = $row->vPhone;

            }//end for each

        }//end if

        return (array_filter($airlinePhoneRow));

    }//end function



    function getAirlineEmail()

    {

        $airlineEmailRow = array();

        $airlineEmail    = $this->db->query("SELECT vEmail FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineEmail->num_rows() > 0) {

            foreach ($airlineEmail->result() as $key => $row) {

                $airlineEmailRow[] = $row->vEmail;

            }//end for each

        }//end if

        return (array_filter($airlineEmailRow));

    }//end function



    function getAirlineAddress()

    {

        $airlineAddressRow = array();

        $airlineAddress    = $this->db->query("SELECT vAddress FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineAddress->num_rows() > 0) {

            foreach ($airlineAddress->result() as $key => $row) {

                $airlineAddressRow[] = $row->vAddress;

            }//end for each

        }//end if

        return (array_filter($airlineAddressRow));

    }//end function



    function getAirlineContactID()

    {

        $airlineCIdRow = array();

        $airlineCId    = $this->db->query("SELECT nArcId FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");



        if ($airlineCId->num_rows() > 0) {

            foreach ($airlineCId->result() as $key => $row) {

                $airlineCIdRow[] = $row->nArcId;

            }//end for each

        }//end if

        return (array_filter($airlineCIdRow));

    }//end function



    //fetch corresponding xo booking details

    function getCorresXobooking()

    {

        $fetchTitle = $this->db->query("SELECT * FROM {PRE}XoBooking WHERE nXId='" . $this->catId . "'");

        $showVal    = $fetchTitle->row();

        return $showVal;

    }//end function



    function getCorresXoDetails()

    {

        $xoDetailsRow 		 = array();

        $xoDetailsCarrier 	 = array();

        $xoDetailsFlight 	 = array();

        $xoDetailsClass 	 = array();

        $xoDetailsTravelDate = array();

        $xoDetailsSector1 	 = array();

        $xoDetailsSector2 	 = array();

        $xoDetailsStatus  	 = array();

        $xoDetailsTimeDep 	 = array();

        $xoDetailsTimeArr 	 = array();

        $xoDetailsDay 		 = array();

        $xoDetailID 		 = array();

        $newValArray 		 = array();



        $xoDetailsList = $this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId = '".$this->catId."'");

        if ($xoDetailsList->num_rows() > 0) {

            foreach ($xoDetailsList->result() as $key => $row) {

                $xoDetailsRow[] 	   = $row->nXDId;

                $xoDetailsCarrier[]    = $row->vCarrier;

                $xoDetailsFlight[] 	   = $row->vFlight;

                $xoDetailsClass[] 	   = $row->vClass;

if($row->dTravelDate == '0000-00-00' || $row->dTravelDate == '' || $row->dTravelDate == 0){

                $xoDetailsTravelDate[] = ''; 

}

else {

                $xoDetailsTravelDate[] =  date('d-m-Y', strtotime($row->dTravelDate));

} 

                $xoDetailsSector1[]    = $row->vSector;

                $xoDetailsSector2[]    = $row->vSector2;

                $xoDetailsStatus[]     = $row->vStatus;

                $xoDetailsTimeDep[]    = $row->vTimeDept;

                $xoDetailsTimeArr[]    = $row->vTimeArr;

                $xoDetailsDay[] 	   = $row->vDay;



                if (!@in_array($row->dId, $xoDetailID))

                    $xoDetailID[] = $row->dId;

            }//end for each

        }//end if



        $newValArray['xoId'] 		 = $xoDetailsRow;

        $newValArray['xoCarrier'] 	 = $xoDetailsCarrier;

        $newValArray['xoFlight'] 	 = $xoDetailsFlight;

        $newValArray['xoClass'] 	 = $xoDetailsClass;

        $newValArray['xoTravelDate'] = $xoDetailsTravelDate;

        $newValArray['xoSector1']    = $xoDetailsSector1;

        $newValArray['xoSector2'] 	 = $xoDetailsSector2;

        $newValArray['xoStatus']  	 = $xoDetailsStatus;

        $newValArray['xoTimeDep'] 	 = $xoDetailsTimeDep;

        $newValArray['xoTimeArr'] 	 = $xoDetailsTimeArr;

        $newValArray['xoDay'] 		 = $xoDetailsDay;

        $newValArray['xoDetID'] 	 = $xoDetailID;

        return ($newValArray);

    }//end function



    //fetch corresponding xo pass details

    function getCorresPassDetails()

    {

        $xoPassRow       = array();

        $xoPassPass      = array();

        $xoPassFcTax     = array();

        $xoPassCommTax   = array();

        $xoPassORCAmt    = array();

        $xoPassFc1Tax    = array();

        $xoPassFc2Tax    = array();

        $xoPassFc3Tax    = array();

        $xoPassTotalTax  = array();

        $xoPassNetVendor = array();

        $xoPassXoSalesAmt = array();

        $newValArray     = array();



        $xoPassList = $this->db->query("SELECT * FROM {PRE}XoPassDetails WHERE nXoId = '".$this->catId."'");

        if ($xoPassList->num_rows() > 0) {

            foreach ($xoPassList->result() as $key => $row) {

                $xoPassRow[]       = $row->nXPId;

                $xoPassPass[]      = $row->vPass;

                $xoPassFcTax[]     = $row->vFCTax;

                $xoPassCommTax[]   = $row->nComm;

                $xoPassORCAmt[]    = $row->nORCAmt;

                $xoPassFc1Tax[]    = $row->vTax1;

                $xoPassFc2Tax[]    = $row->vTax2;

                $xoPassFc3Tax[]    = $row->vTax3;

                $xoPassTotalTax[]  = $row->vTaxTotal;

                $xoPassNetVendor[] = $row->nNetVendor;

                $xoPassXoSalesAmt[] = $row->nSalesAmt;

            }//end for each

        }//end if



        $newValArray['xoPId'] 		= $xoPassRow;

        $newValArray['xoPass'] 		= $xoPassPass;

        $newValArray['xoFcTax'] 	= $xoPassFcTax;

        $newValArray['xoCommTax'] 	= $xoPassCommTax;

        $newValArray['xoORCAmt'] 	= $xoPassORCAmt;

        $newValArray['xoFc1Tax'] 	= $xoPassFc1Tax;

        $newValArray['xoFc2Tax'] 	= $xoPassFc2Tax;

        $newValArray['xoFc3Tax'] 	= $xoPassFc3Tax;

        $newValArray['xoTotalTax']  = $xoPassTotalTax;

        $newValArray['xoNetVendor'] = $xoPassNetVendor;

        $newValArray['xoSalesAmt'] = $xoPassXoSalesAmt;



        return ($newValArray);

    }//end function



    function deletePassDetails($ID)

    {

        $this->db->delete('{PRE}XoPassDetails', array('nXPId' => $ID));

    }//end function



    //fetch corresponding xo billing details

    function getCorresBillingDetails()

    {

        $fetchTitle = $this->db->query("SELECT * FROM {PRE}XoBillingDetails WHERE nXoId = '".$this->catId."'");

        $showVal 	= $fetchTitle->row();

        return $showVal;

    }//end function



    function deleteXobooking()

    {

        $this->db->delete('{PRE}XoBooking', array('nXId' => $this->uri->segment(4)));

        $this->db->delete('{PRE}XoDetails', array('nXoId' => $this->uri->segment(4)));

        $this->db->delete('{PRE}XoPassDetails', array('nXoId' => $this->uri->segment(4)));

        $this->db->delete('{PRE}XoBillingDetails', array('nXoId' => $this->uri->segment(4)));

        redirect(site_url("xo_booking/showlist/" . $this->uri->segment(3) . '/del_suc'));

    }//end function



    function deleteAllXobooking()

    {

        switch ($this->input->post('ddlAction')) {

            case "yes":

                foreach ($this->input->post('del') as $val) {

                    //update into db

                    $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1','uid' => $this->session->userdata('AbsAdmin'),'udate' => date('Y-m-d H:i:s'));

                    $this->db->where('nXId', $val);

                    $this->db->update('{PRE}XoBooking', $data);

                }//end foreach

                redirect(site_url("xo_booking/showlist/" . $this->input->post('nXId') . '/edit_suc'));

                break;



            case "no":

                foreach ($this->input->post('del') as $val) {

                    //update into db

                    $data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0','uid' => $this->session->userdata('AbsAdmin'),'udate' => date('Y-m-d H:i:s'));

                    $this->db->where('nXId', $val);

                    $this->db->update('{PRE}XoBooking', $data);

                }//end foreach

                redirect(site_url("xo_booking/showlist/" . $this->input->post('nXId') . '/edit_suc'));

                break;



            case "delete":

                foreach ($this->input->post('del') as $val) {

                    $this->db->delete('{PRE}XoBooking', array('nXId' => $val));

                    $this->db->delete('{PRE}XoDetails', array('nXoId' => $val));

                    $this->db->delete('{PRE}XoPassDetails', array('nXoId' => $val));

                    $this->db->delete('{PRE}XoBillingDetails', array('nXoId' => $val));

                }//end foreach

                redirect(site_url("xo_booking/showlist/".$this->input->post('nXId').'/del_suc'));

                break;

        }//end switch

    }//end function



    //fetching single details

    function showDetails($xoId)

    {

        $viewAccessRow  = array();

        $viewAccessList = $this->db->query("SELECT xo.*,xbd.*,d.vTitle as divName,l.vTitle as locName,t.vTitle as transName,

                                            	ar.vTitle as airlineName,ar.vAirlineNo,xo.vXoNumber as tktNumber,

											gds.vName as gdsName,

                                            CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN

                                            	xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType FROM {PRE}XoBooking xo

                                            LEFT JOIN {PRE}Division d ON d.nId = xo.nDivision LEFT JOIN {PRE}Location l ON

                                            	l.nId = xo.nLocation LEFT JOIN {PRE}Transactions t ON t.nId = xo.nTransaction

                                            LEFT JOIN {PRE}Gds gds ON xo.nGDS = gds.nGId 

											    LEFT JOIN {PRE}Airline ar ON ar.nArId = xo.nAirline LEFT JOIN

                                            {PRE}XoBillingDetails xbd ON xbd.nXoId = xo.nXId WHERE nXId = '".$xoId."'

                                            ORDER BY nXId DESC");

        foreach ($viewAccessList->result() as $key => $row) {

            $content .= '<table class="reportForm">

                <label><strong>'.XOBOOKINGDETAILS.'</strong></label>

                <tr>

                    <td><strong>'.DIVISIONNAME.':</strong></td>

                    <td>'.$row->divName.'</td>



                    <td><strong>'.LOCATIONNAME.':</strong></td>

                    <td>'.$row->locName.'</td>



                    <td><strong>'.CUSTDATE.':</strong></td>

                    <td colspan="5">'.$row->dDate.'</td>

                </tr>



                <tr>

                    <td><strong>'.DOCNUMBER.':</strong></td>

                    <td>'.$row->vDocNumber.'</td>



                    <td><strong>'.TYPE.':</strong></td>

                    <td colspan="7">'.$row->xoType.'</td>

                </tr>



                <tr>

                    <td><strong>'.AIRLINENAME.' :</strong></td>

                    <td>'.$row->airlineName.' (' . $row->vAirlineNo . ')' . '</td>



                    <td><strong>'.XONAME.' :</strong></td>

				    <td>'.$row->vTicktManual.'</td>



                    <td><strong>'.GDS.' :</strong></td>

                    <td colspan="5">'.$row->gdsName.'</td>

                </tr>



                <tr>

                    <td><strong>'.BOOKEDBY.' :</strong></td>

                    <td>'.$this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vBookedBy."' ").'</td>



                    <td><strong>'.ISSUEDBY.' :</strong></td>

                    <td colspan="7">'.$this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$row->vIssuedBy."' ").'</td>

                </tr>



                <tr>

                    <td><strong>'.ORIGINALTKT.' :</strong></td>

                    <td>'.$row->vOriginalXo.'</td>



                    <td><strong>'.SEGMENT.' :</strong></td>

                    <td>'.$row->vSegment.'</td>



                    <td><strong>'.DESTINATION.' :</strong></td>

                    <td colspan="5">'.$row->vDestination.'</td>

                </tr>



                <tr>

                    <td><strong>'.GDSPNR.' :</strong></td>

                    <td>'.$row->vGdspnr.'</td>



                    <td><strong>'.AIRPNR.' :</strong></td>

                    <td>'.$row->vAirpnr.'</td>



                    <td><strong>'.CURRNAME.' :</strong></td>

                    <td>'.$this->common_model->get_field_name('Currency', 'vTitle', " WHERE nId='".$row->nCurrency."' ").'</td>



                    <td><strong>'.CURRRATE.' :</strong></td>

                    <td colspan="5">'.$this->common_model->get_field_name('Currency', 'vRate', " WHERE nId='".$row->nCurrency."' ").'</td>

                </tr>



                <tr>

                    <td colspan="12"><strong>'.TICKETDETAILS.'</strong></td>

                </tr>



                <tr>

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

                </tr>';



            

				//fetching Ticket details

                $getTicketDetails = $this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId = '".$xoId."'");

                foreach ($getTicketDetails->result() as $key1 => $row1) :

					 $content .='<tr>

						<td>'.$row1->vCarrier.'</td>

						<td>'.$row1->vFlight.'</td>

						<td>'.$row1->vClass.'</td>

						<td>'.$row1->dTravelDate.'</td>

						<td>'.$row1->vSector.'</td>

						<td>'.$row1->vSector2.'</td>

						<td>'.$row1->vStatus.'</td>

						<td>'.$row1->vTimeDept.'</td>

						<td>'.$row1->vTimeArr.'</td>

						<td>'.$row1->vDay.'</td>

					</tr>';

                endforeach;



                $content .='<tr>

                    <td colspan="12"><strong>'.PASSENGERDETAILS.'</strong></td>

                </tr>



                <tr>

                    <td><strong>'.SRNO.'</strong></td>

                    <td><strong>'.PASSENGER.'</strong></td>

                    <td><strong>'.FARE.'</strong></td>

                    <td><strong>'.COMM.'</strong></td>

                    <td><strong>'.COMMAMT.'</strong></td>

                    <td><strong>'.FCTAX.':</strong></td>

                    <td><strong>'.FCTAX.':</strong></td>

                    <td><strong>'.FCTAX.':</strong></td>

                    <td><strong>'.TOTALTAX.':</strong></td>

                    <td><strong>'.NETTOVENDOR.':</strong></td>

                </tr>';

                $getPassDetails = $this->db->query("SELECT * FROM {PRE}XoPassDetails WHERE nXoId='".$xoId."'");

                foreach ($getPassDetails->result() as $key2 => $row2) :

                $content .='<tr>

                    <td>'.($key2+1).'</td>

                    <td>'.$this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row2->vPass."' ").'</td>

                    <td>'.$row2->vFCTax.'</td>

                    <td>'.$row2->nComm.'</td>

                    <td>'.$row2->nORCAmt.'</td>

                    <td>'.$row2->vTax1.'</td>

                    <td>'.$row2->vTax2.'</td>

                    <td>'.$row2->vTax3.'</td>

                    <td>'.$row2->vTaxTotal.'</td>

                    <td>'.$row2->nNetVendor.'</td>

                </tr>';

                endforeach;



                $content .='<tr>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td><strong>'.GRANDTOTAL.':</strong></td>

                    <td><strong>'.$row->vGrandFare.'</strong></td>

                    <td><strong>'.$row->vGrandTax1.'</strong></td>

                    <td><strong>'.$row->vGrandTax2.'</strong></td>

                    <td><strong>'.$row->vGrandTax3.'</strong></td>

                    <td><strong>'.$row->vGrandToalTax.'</strong></td>

                    <td><strong>'.$row->vGrandNetFare.'</strong></td>

                </tr>

				

				<tr>

                    <td><strong>'.ACCCODENAME.':</strong></td>

                    <td>'.$this->common_model->get_field_name('VendorMaster','vName'," WHERE nFId = '".$row->nPassengerAC."' ").'</td>

                    <td><strong>'.NETFARE.':</strong></td>

                    <td colspan="7">'.$row->vGrandNetFare.'</td>

					

                </tr>';



				$content .='<tr>

                    <td><strong>'.REMARKS.':</strong></td>

                    <td colspan="10">'.$row->vRemarks.'</td>

				</tr>';

								

                $content .='<tr>

                    <td colspan="12"><strong>'.ACDETAILS.'</strong></td>

                </tr>



                <tr>

                    <td><strong>'.MODEOFPAYMENT.':</strong></td>

                    <td>'.$row->vModeofpay.'</td>



                    <td><strong>'.CUSTACCNAME.':</strong></td>

                    <td colspan="7">'.$this->common_model->get_field_name('CustomerMaster','vName',' WHERE nCId = "'.$row->nAccName.'"').'</td>

                </tr>



                <tr>

                    <td><strong>'.AGENTAC.':</strong></td>

                    <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->nAgentAc.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->nAgentAc.'"').' )'.'</td>



                    <td><strong>'.AGENTAMT.':</strong></td>

                    <td >'.$row->vAgentAmt.'</td>



                    <td><strong>'.LPO.':</strong></td>

                    <td colspan="5">'.$row->vLPO.'</td>

                </tr>



                <tr>

                    <td><strong>'.SALESAMT.'</strong></td>

                    <td><strong>'.DISCOUNT.'</strong></td>

                    <td><strong>'.DISCOUNTAMT.'</strong></td>

                    <td><strong>'.COLECTED.'</strong></td>

                    <td><strong>'.SERVICEPERCENT.'</strong></td>

                    <td colspan="5"><strong>'.SERVICEAMT.'</strong></td>

                </tr>



                <tr>

                    <td>'.$row->vSalesAmt.'</td>

                    <td>'.$row->vDiscount.'</td>

                    <td>'.$row->vDiscountAmt.'</td>

                    <td>'.$row->vCollected.'</td>

                    <td>'.$row->vServicePercent.'</td>

                    <td colspan="5">'.$row->vServiceAmt.'</td>

                </tr>

				

		    </table>';

        }//end foreach

        return ($content);

    }//end function



    function getPassType()

    {

        $passTyperow = array("N" => "Normal", "S" => "Special");

        return ($passTyperow);

    }//end function



    function getModeType()

    {

        $modeTyperow = array("Cr" => "Credit", "Ca" => "Cash", "KN" => "KNET");

        return ($modeTyperow);

    }//end function



	//search stock entry ticket

	function searchActiveTicketStock($searchKey)

	{

		$typeGroupRow  = array();

		$extaCondition = " AND (sed.vStartNo LIKE ('%".$searchKey."%') OR sed.vStartNo LIKE ('% ".$searchKey."%') OR sed.vStartNo 

							LIKE ( '%".$searchKey."%') OR sed.vEndNo LIKE ('%".$searchKey."%') OR sed.vEndNo 

						   LIKE ('% ".$searchKey."%') OR sed.vEndNo LIKE ( '%".$searchKey."%'))";

									 		  

		if(trim($searchKey) == 0)

		{

			$extaCondition = '';

		}//end if

		

		$typeGroup    = $this->db->query("SELECT se.*,sed.* FROM {PRE}StockEntry se LEFT JOIN {PRE}StockEntryDetails sed 

												ON se.nSEId = sed.nStock WHERE sed.vActive = '1' ".$extaCondition." 

										  ORDER BY se.nSEId DESC");

		if ($typeGroup->num_rows() > 0)

		{

			foreach ($typeGroup->result() as $key=>$row)

			{

				$typeGroupRow[] = $row->vStartNo.' - '.$row->vEndNo;

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

		

		$typeGroup = $this->db->query("SELECT * FROM {PRE}Airline WHERE vActive = '1' ".$extaCondition." ORDER BY nArId DESC");

		if ($typeGroup->num_rows() > 0)

		{

			foreach ($typeGroup->result() as $key=>$row)

			{

				$typeGroupRow[] = $row->vAirlineNo.' - '.$row->vTitle;

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



	function getSelectedXoDetails($xoId)

	{

		$xoRow = array();

		$xo    = $this->db->query("SELECT xob.*,xod.*,xobd.*,xpd.*,

												t.vTitle as transName,ar.vAirlineNo as airlineNo,ar.vTitle as airlineName,

											 gds.vName as gdsName, 

												CASE 

													WHEN xob.vType = 'N' THEN 'Normal'

												WHEN xob.vType = 'V' THEN 'Void'

													WHEN xob.vType = 'C' THEN 'Conjuction'

												ELSE 'Re issue' END as xoType

											  FROM {PRE}XoBooking xob LEFT JOIN 

											  {PRE}Transactions t ON t.nId = xob.nTransaction

												 LEFT JOIN {PRE}Gds gds ON xob.nGDS = gds.nGId

											  LEFT JOIN {PRE}Airline ar ON ar.nArId = xob.nAirline LEFT JOIN {PRE}XoDetails xod 

											     ON xob.nXId = xod.nXoId LEFT JOIN {PRE}XoBillingDetails xobd 

											  ON xobd.nXoId = xob.nXId LEFT JOIN {PRE}XoPassDetails xpd ON xpd.nXoId = xob.nXId

											  WHERE nXId = '".$xoId."' ORDER BY nXId DESC");



		if ($xo->num_rows() > 0)

		{

			$xoRow = $xo->row();

		}//end if

		return ($xoRow);

	}//end function



    function getCorrescopyXoDetails($tktId)

    {

        $xoDetailsRow 		 = array();

        $xoDetailsCarrier 	 = array();

        $xoDetailsFlight 	 = array();

        $xoDetailsClass 	 = array();

        $xoDetailsTravelDate = array();

        $xoDetailsSector1 	 = array();

        $xoDetailsSector2 	 = array();

        $xoDetailsStatus  	 = array();

        $xoDetailsTimeDep 	 = array();

        $xoDetailsTimeArr 	 = array();

        $xoDetailsDay 		 = array();

        $xoDetailID 		 = array();

        $newValArray 		 = array();



        $xoDetailsList = $this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId = '".$tktId."'");

        if ($xoDetailsList->num_rows() > 0) {

            foreach ($xoDetailsList->result() as $key => $row) {

                $xoDetailsRow[] 	   = $row->nXDId;

                $xoDetailsCarrier[]    = $row->vCarrier;

                $xoDetailsFlight[] 	   = $row->vFlight;

                $xoDetailsClass[] 	   = $row->vClass;

                $xoDetailsTravelDate[] =  date('d-m-Y', strtotime($row->dTravelDate));

                $xoDetailsSector1[]    = $row->vSector;

                $xoDetailsSector2[]    = $row->vSector2;

                $xoDetailsStatus[]     = $row->vStatus;

                $xoDetailsTimeDep[]    = $row->vTimeDept;

                $xoDetailsTimeArr[]    = $row->vTimeArr;

                $xoDetailsDay[] 	   = $row->vDay;



                if (!@in_array($row->dId, $xoDetailID))

                    $xoDetailID[] = $row->dId;

            }//end for each

        }//end if



        $newValArray['xoId'] 		 = $xoDetailsRow;

        $newValArray['xoCarrier'] 	 = $xoDetailsCarrier;

        $newValArray['xoFlight'] 	 = $xoDetailsFlight;

        $newValArray['xoClass'] 	 = $xoDetailsClass;

        $newValArray['xoTravelDate'] = $xoDetailsTravelDate;

        $newValArray['xoSector1']    = $xoDetailsSector1;

        $newValArray['xoSector2'] 	 = $xoDetailsSector2;

        $newValArray['xoStatus']  	 = $xoDetailsStatus;

        $newValArray['xoTimeDep'] 	 = $xoDetailsTimeDep;

        $newValArray['xoTimeArr'] 	 = $xoDetailsTimeArr;

        $newValArray['xoDay'] 		 = $xoDetailsDay;

        $newValArray['xoDetID'] 	 = $xoDetailID;

        return ($newValArray);

    }//end function



    //fetch corresponding xo pass details

    function getCorrescopyPassDetails($tktId)

    {

        $xoPassRow       = array();

        $xoPassPass      = array();

        $xoPassFcTax     = array();

        $xoPassCommTax   = array();

        $xoPassORCAmt    = array();

        $xoPassFc1Tax    = array();

        $xoPassFc2Tax    = array();

        $xoPassFc3Tax    = array();

        $xoPassTotalTax  = array();

        $xoPassNetVendor = array();

        $xoPassXoSalesAmt = array();

        $newValArray     = array();



        $xoPassList = $this->db->query("SELECT * FROM {PRE}XoPassDetails WHERE nXoId = '".$tktId."'");

        if ($xoPassList->num_rows() > 0) {

            foreach ($xoPassList->result() as $key => $row) {

                $xoPassRow[]       = $row->nXPId;

                $xoPassPass[]      = $row->vPass;

                $xoPassFcTax[]     = $row->vFCTax;

                $xoPassCommTax[]   = $row->nComm;

                $xoPassORCAmt[]    = $row->nORCAmt;

                $xoPassFc1Tax[]    = $row->vTax1;

                $xoPassFc2Tax[]    = $row->vTax2;

                $xoPassFc3Tax[]    = $row->vTax3;

                $xoPassTotalTax[]  = $row->vTaxTotal;

                $xoPassNetVendor[] = $row->nNetVendor;

                $xoPassXoSalesAmt[] = $row->nSalesAmt;

            }//end for each

        }//end if



        $newValArray['xoPId'] 		= $xoPassRow;

        $newValArray['xoPass'] 		= $xoPassPass;

        $newValArray['xoFcTax'] 	= $xoPassFcTax;

        $newValArray['xoCommTax'] 	= $xoPassCommTax;

        $newValArray['xoORCAmt'] 	= $xoPassORCAmt;

        $newValArray['xoFc1Tax'] 	= $xoPassFc1Tax;

        $newValArray['xoFc2Tax'] 	= $xoPassFc2Tax;

        $newValArray['xoFc3Tax'] 	= $xoPassFc3Tax;

        $newValArray['xoTotalTax']  = $xoPassTotalTax;

        $newValArray['xoNetVendor'] = $xoPassNetVendor;

        $newValArray['xoSalesAmt'] = $xoPassXoSalesAmt;



        return ($newValArray);

    }//end function



	function getActiveStock()

	{

		$stockGroupRow = array();

		$stockGroup    = $this->db->query("SELECT se.nSEId,se.nLocation,sed.*,st.nLocation,xo.*,s.nTransaction

											 FROM {PRE}StockEntry se

											LEFT JOIN {PRE}XoBooking xo ON se.nLocation = xo.nLocation LEFT JOIN

										   {PRE}StockEntryDetails sed ON se.nSEId = sed.nStock

										    LEFT JOIN {PRE}StockTransfer st ON st.nStockDetId = sed.nId

										   LEFT JOIN {PRE}Stock s ON s.nStkId = sed.nStocktype WHERE s.nTransaction = '6'

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

											 AND s.nTransaction = '6' ORDER BY sed.nId DESC");

			if($stockGroup->num_rows() > 0)

			{

			//$stockGroupRow[1] = SELECT;

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

        if($this->nextTkt == '') continue;



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



	function getSearchActiveType()

	{

		$typeGroupRow    = array("0" => ALL, "N" => "Normal", "V" => "Void", "C" => "Conjuction", "R" => "Re issue");

		return ($typeGroupRow);

	}//end function 



	//searching by airline name or code/ticket number/invoice id/issued by/passenger name/from and to date/accounts

	function getFilterList($extraCheck)

	{

		$adminGroupRow        = array();

		$sqlTicketfilterQuery = "SELECT xo.*,xo.dDate as date,

			CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN

				xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END, 

				ar.vTitle as airlineName,ar.vAirlineNo as  airlineNo,

				g.vName as gdsName,xo.vXoNumber,xo.vPassenger,

				em.*,sed.*,

CASE WHEN xo.vType = 'N' THEN 'Normal' WHEN xo.vType = 'V' THEN 'Void' WHEN

                                xo.vType = 'C' THEN 'Conjuction' ELSE 'Re issue' END as xoType 

 FROM {PRE}XoBooking xo

				LEFT JOIN {PRE}Airline ar

				ON ar.nArId = xo.nAirline LEFT JOIN {PRE}Gds g ON xo.nGDS = g.nGId 

				LEFT JOIN  {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

				LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = xo.vXoNumber

			 WHERE xo.vActive='1'

       AND xo.nLocation IN ". $this->session->userdata('accessLoc') ." 

       ".$extraCheck."  ORDER BY  YEAR(xo.dDate) DESC, MONTH(xo.dDate) DESC, DAY(xo.dDate) DESC, xo.nXId DESC LIMIT ".$this->common_model->_offset." , 

								 ".$this->common_model->_limit;

//echo $sqlTicketfilterQuery;

//return;

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

		$tabCount = $this->db->query("SELECT xo.*,ar.vTitle as airlineName,ar.vAirlineNo as                                          airlineNo,g.vName as gdsName,xo.vXoNumber,xo.vPassenger,em.*,sed.*

										FROM {PRE}XoBooking xo 



										  LEFT JOIN {PRE}Airline ar

										ON ar.nArId = xo.nAirline LEFT JOIN {PRE}Gds g ON xo.nGDS = g.nGId LEFT JOIN                                               {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

										LEFT JOIN {PRE}StockEntryDetails sed ON sed.nId = xo.vXoNumber WHERE

                    xo.nLocation IN ". $this->session->userdata('accessLoc') ." 



										".$extraCheck." ORDER BY xo.nXId DESC ");

		return ($tabCount->num_rows());

	}//end function



    function getCorresCopyBillingDetails($id)

    {

        $fetchTitle = $this->db->query("SELECT * FROM {PRE}XoBillingDetails WHERE nXBId = '".$id."'");

        $showVal 	= $fetchTitle->row();

        return $showVal;

    }//end function



    function getCloseStatus()

    {

        $statusGroupRow = array();

        $statusGroup    = $this->db->query("(SELECT nXId FROM {PRE}XoBooking xo

											LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId WHERE xo.nXId

											 NOT IN (SELECT scd.vTktNumber

		                           			FROM {PRE}SalesClosingDetails scd WHERE scd.vType = 'Xo') AND xo.nXId='".$this->catId."'

		                           	    	 ORDER BY xo.nXId DESC)");

        $showVal	     = $statusGroup->row();

        return $showVal;

    }//end function


	function getInvoiceid()

    {

        $statusGroupRow = array();

        $statusGroup    = $this->db->query("(SELECT nSCId FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd                                                    ON sc.nSCId=scd.nSalesCloseId

		                                    WHERE scd.vTktNumber='".$this->catId."' AND scd.vType = 'Xo')");

        $showVal	     = $statusGroup->row();

        return $showVal;

    }//end function

    function getInvoiceNumber()

    {

        $statusGroupRow = array();

        $statusGroup    = $this->db->query("(SELECT vInvoiceNumber FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd                                                    ON sc.nSCId=scd.nSalesCloseId

		                                    WHERE scd.vTktNumber='".$this->catId."' AND scd.vType = 'Xo')");

        $showVal	     = $statusGroup->row();

        return $showVal;

    }//end function



    function getRefundStatus()

    {

        $statusGroupRow = array();

        $statusGroup    = $this->db->query("(SELECT nXId FROM {PRE}XoBooking xo

												LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId WHERE xo.nXId

											NOT IN (SELECT scd.nTicketNumber

		                           				FROM {PRE}RefundClosingDetails scd WHERE scd.vType = 'Xo') 

											AND xo.nXId='".$this->catId."' ORDER BY xo.nXId DESC)");

        $showVal	     = $statusGroup->row();

        return $showVal;

    }//end function



    function getRefundInvoiceNumber()

    {

        $statusGroupRow = array();

        $statusGroup    = $this->db->query("(SELECT vDocNumber FROM {PRE}RefundClosing sc LEFT JOIN {PRE}RefundClosingDetails scd 

												ON sc.nRCId=scd.nRefundCloseId

		                                    WHERE scd.nTicketNumber='".$this->catId."' AND scd.vType = 'Xo')");

        $showVal	     = $statusGroup->row();

        return $showVal;

    }//end function



	function getNextTicket($tktNo,$start,$separator,$end)

	{

		$tktArray    = array();

		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}XoBooking WHERE 

											vXoNumber = '".$tktNo."' ORDER BY nXId DESC 

												LIMIT 0,1");

		if($tktSql->num_rows() > 0)

		{

			foreach($tktSql->result() as $tkey => $trow)

			{

				if($separator == ","):

					if($trow->vTicktManual == $start)

						$nextNo = '';//$end;

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

		$tktSql      = $this->db->query("SELECT vTicktManual FROM {PRE}XoBooking WHERE 

											vXoNumber = '".$tktNo."' ORDER BY nXId DESC");

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

