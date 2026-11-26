<?php
class sales_receipt_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function getSalesReceipt($count)
  {
    //active groups
    $adminGroupRow  = array();
    $adminGroupQuery = " SELECT  sr.*, srd.*  FROM {PRE}SalesReceipt sr
      LEFT JOIN {PRE}SalesReceiptDetails srd ON srd.nSalesRId = sr.nSRId 
      WHERE sr.vLocation IN ". $this->session->userdata('accessLoc') ."  ";

    $adminGroupQuery .= $count == 1 ? '' : " LIMIT " . $this->common_model->_offset . " , " . $this->common_model->_limit;

    $_SESSION['sess_query'] = $adminGroupQuery;
    $adminGroupList    = $this->db->query($adminGroupQuery);

    if($count == 1)
      return ($adminGroupList->num_rows());

    if ($adminGroupList->num_rows() > 0)
    {
      foreach ($adminGroupList->result() as $key => $row)
      {
        $adminGroupRow[] = $row;
      }//end for each
    }//end if
    return ($adminGroupRow);
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

  function getActiveCreditCard()
  {
        $adminGroup    = $this->db->query("SELECT * FROM {PRE}Card WHERE vActive = '1' ORDER BY vName ");
        $adminGroupRow = array();
        if ($adminGroup->num_rows() > 0)
        {
            $adminGroupRow[0] = SELECT;
            foreach ($adminGroup->result() as $key => $row)
            {
                $adminGroupRow[$row->nCId] = stripslashes($row->vName); 
            }//end for each
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
  }//end function

  function getActiveTickets()
  {
    $adminGroupRow    = array();
    $adminGroupRow[0] = SELECT;
    return ($adminGroupRow);
  }//end function

  function getLocation()
  {
    $locGroupRow    = array();
    $locGroupRow[0] = SELECT;
    return ($locGroupRow);
  }//end function


  function filterLocation($division)
  {
        $locGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '" . $division . "'
                       AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
    {
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

  function filterTicket($location)
  {
        $adminGroup    = $this->db->query("SELECT nTId as tktId, 'Tkt' as tktType, vTicktManual as tktNumber FROM {PRE}TicketBooking  WHERE nTId NOT IN ( SELECT vTktNumber FROM {PRE}SalesReceiptDetails WHERE  vType ='Tkt') 
AND  nTId NOT IN (SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vType ='Tkt')  AND nLocation = " . $location . " 
            UNION ALL ( SELECT nXId as tktId, 'Xo' as tktType, vTicktManual as tktNumber FROM {PRE}XoBooking WHERE nXId NOT IN ( SELECT vTktNumber FROM {PRE}SalesReceiptDetails WHERE  vType ='Xo') AND nXId NOT IN ( SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vType ='Xo' ) AND nLocation = " . $location . " )
            UNION ALL (SELECT nHVId as tktId, 'Htl' as tktType, vTicktManual as tktNumber FROM {PRE}HotelVoucher WHERE nHVId NOT IN ( SELECT vTktNumber FROM {PRE}SalesReceiptDetails WHERE  vType ='Htl') AND nHVId NOT IN ( SELECT vTktNumber FROM {PRE}SalesClosingDetails WHERE vType ='Htl') AND nLocation = " . $location . "  ) ");

        $adminGroupRow = array();
        if ($adminGroup->num_rows() > 0)
        {
            $adminGroupRow[0] = SELECT;
            foreach ($adminGroup->result() as $key => $row)
            {
                $adminGroupRow[$row->tktId.'-'.$row->tktType] = stripslashes($row->tktNumber);
            }//end for each
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
  }//end function



  function getCorresSalesReceipt()
  {
    $fetchTitle = $this->db->query("SELECT * FROM {PRE}SalesReceipt WHERE nSRId = '".$this->catId."'");
    $showVal  = $fetchTitle->row();
    return $showVal;
  }//end function

	
	function getCorresSalesReceiptDetails()
	{
		$salesRDetailsAmt 	  = array();
		$salesRDetailsCard  	  = array();
		$salesRDetailsTkt  = array();
		$salesRDetailsTktType        = array();
    $salesReceiptDetailID      = array();

		$newValArray          	  = array();
		
		$salesRDetailsList      	  = $this->db->query("SELECT * FROM {PRE}SalesReceiptDetails WHERE nSalesRId = '".$this->catId."'");
		if ($salesRDetailsList->num_rows() > 0)
		{
			foreach ($salesRDetailsList->result() as $key=>$row)
			{
				$salesRDetailsAmt[]      	  = $row->nAmountDr;
				$salesRDetailsCard[]      	  = $row->nCardId;
				$salesRDetailsTkt[]   	      = $row->vTktNumber;
				$salesRDetailsTktType[] 	  = $row->vType;
        $salesReceiptDetailID[]     	  = $row->nId;

			}//end for each
		}//end if
		
		$newValArray['Amt']        		= $salesRDetailsAmt;
		$newValArray['Card']        		= $salesRDetailsCard;
		$newValArray['Tkt']        		= $salesRDetailsTkt;
		$newValArray['TktType']          = $salesRDetailsTktType;
		$newValArray['DetId']          = $salesReceiptDetailID;

		return ($newValArray);
	}//end function


    function getCorresTicketDetails($tkt_list,$tktType_list,$tktAmt_list)
    {
      $tktDetailsAirline = array();
      $tktDetailsTicketNumber = array();
      $tktDetailsPassenger = array();
      $tktDetailsDesc = array();
      $tktDetailsType= array();
      $tktDetailsAmt = array();

        foreach($tkt_list as $key => $value){
          if($value != ''){

            switch($tktType_list[$key]){
              case 'Tkt':
                $creditDetailsList = $this->db->query("SELECT * FROM {PRE}TicketBooking tb LEFT JOIN {PRE}TicketDetails td ON tb.nTId = nTicketId  WHERE nTId =".$value ." ");
                foreach($creditDetailsList->result() as $row => $item){
                  $tktDetailsAmt[] = $tktAmt_list[$key];
                  $tktDetailsType[] = 'Ticket';
                  $tktDetailsAirline[] = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->nAirline."'");
                  $tktDetailsTicketNumber[] = $item->vTicktManual;
                  $tktDetailsPassenger[] = $item->vPassenger;
                  $tktDetailsDesc[] =  $item->vSector1 . "-".  $item->vSector12 ."-" .$item->vSector2 . "-".  $item->vSector22 ."-" .$item->vSector3 . "-".  $item->vSector33 ."-" .$item->vSector4 . "-".  $item->vSector44 ."-"; 
                }//end foreach

                break;

              case 'Htl':
                $creditDetailsList = $this->db->query("SELECT * FROM {PRE}HotelVoucher WHERE nHVId =".$value ." ");
                foreach($creditDetailsList->result() as $row => $item){
                  $tktDetailsAmt[] = $tktAmt_list[$key];
                  $tktDetailsType[] = 'Hotel';
                  $tktDetailsAirline[] = '';
                  $tktDetailsTicketNumber[] = $item->vTicktManual;
                  $tktDetailsPassenger[] = $item->vPassenger;
                  $tktDetailsDesc[] =  $this->common_model->get_field_name('Country','vName'," WHERE nId='".$item->nCountry."'").'-'.
                    $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$vHotelName."'").'-'.
                    date('d-m-Y',strtotime($item->dCheckIn)).'-'.
                    date('d-m-Y',strtotime($item->dCheckOut)).'-'.
                    NHT.' '.$item->vNight;
                }//end foreach
                break;

              case 'Xo':
                $creditDetailsList = $this->db->query("SELECT * FROM {PRE}XoBooking WHERE nXId =".$value ." ");
                foreach($creditDetailsList->result() as $row => $item){
                  $tktDetailsAmt[] = $tktAmt_list[$key];
                  $tktDetailsType[] = 'Xo';
                  $tktDetailsAirline[] = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->nAirline."'");
                  $tktDetailsTicketNumber[] = $item->vTicktManual;
                  $tktDetailsPassenger[] = $item->vPassenger;
                  $sectorDetailsList = $this->db->query("SELECT * FROM {PRE}XoDetails WHERE nXoId = ".$value ." ");
                  $sector = '';
                  foreach($sectorDetailsList as $k => $v)
                    $sector .= $v->vSector . "-";
                  $tktDetailsDesc[] = $sector; 
                }//end foreach
                break;
            } //end switch($tktType_list)
          }// end if($value != '')

        }//end foreach($tkt_list

        $newValArray['sdType']        = $tktDetailsType;
        $newValArray['sdAmt']         = $tktDetailsAmt;
        $newValArray['sdAirline']     = $tktDetailsAirline;
        $newValArray['sdTktNumber']   = $tktDetailsTicketNumber;
        $newValArray['sdPass']        = $tktDetailsPassenger;
        $newValArray['sdDesc']        = $tktDetailsDesc;
        return ($newValArray);
    }//end function



	function insertSalesReceipt()
	{
    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));
    }

		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),
                      'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
                      'dDate' =>$date1 ,
                      'vDocNumber' => $this->input->post('txtDocNumber'),
                      'vRefName' => $this->input->post('txtRefName'),'vEnteredBy' => $this->input->post('txtEntered'),
                      'vRemarks' => $this->input->post('txtRemarks'), 
                      'vTotalAmountDC' => $this->input->post('txtTotalAmountDC'),
                      'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}SalesReceipt', $data);
		$id			  	 = $this->db->insert_id();


        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
        $accCard  	 = (@array_filter($this->input->post('ddlCard')) != '')?$this->input->post('ddlCard'):0;
        $accTkt  	 = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
		
		foreach($accCard as $key => $aitem)
		{
      $accTktVal = @split("-",$accTkt[$key]);
      $CardCode = $this->common_model->get_field_name('Card', 'nCardAc', " WHERE nCId = " . $accCard[$key] ." ");

			$data 	 = array( 'nSalesRId' => $id,
                        'nCardId' => $accCard[$key],
                        'vACodeDr' => $CardCode,
                        'nAmountDr' => $accAmount[$key],
///////////////////////////////// has to be changed
                        'vACodeCr' => 1, 
                        'nAmountCr' => $accAmount[$key],
                        'vType' => $accTktVal[1],
                        'vTktNumber' => $accTktVal[0]);

			$this->db->insert('{PRE}SalesReceiptDetails', $data);
      
      $detailId = $this->db->insert_id();

		}//end foreach
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
			redirect(site_url("sales_receipt/editSalesreceipt/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("sales_receipt/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
		}//end if 
	}//end class
	
	function updateSalesReceipt()
	{
    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){
      $date1= '';
    }
    else
    {
      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));
    }

		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),
                      'nDivision' => $this->input->post('ddlDivision'),'vLocation' => $this->input->post('ddlLocation'),
                      'dDate' =>$date1 ,
                      'vDocNumber' => $this->input->post('txtDocNumber'),
                      'vRefName' => $this->input->post('txtRefName'),'vEnteredBy' => $this->input->post('txtEntered'),
                      'vRemarks' => $this->input->post('txtRemarks'), 
                      'vTotalAmountDC' => $this->input->post('txtTotalAmountDC'),
                      'vActive' => $this->input->post('chkActive'));
    $this->db->where('nSRId',$this->input->post('nId'));
    $this->db->update('{PRE}SalesReceipt', $data);

    $id          = $this->input->post('nId');

        $accAmount  	 = (@array_filter($this->input->post('txtAmount')) != '')?$this->input->post('txtAmount'):0;
        $accCard  	 = (@array_filter($this->input->post('ddlCard')) != '')?$this->input->post('ddlCard'):0;
        $accTkt  	 = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $chkAccID      = $this->input->post('idArray');
        $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;
		
echo print_r($chkBox);
		foreach($accCard as $key => $aitem)
		{
echo $chkAccID[$key] ;
      $accTktVal = @split("-",$accTkt[$key]);
      $CardCode = $this->common_model->get_field_name('Card', 'nCardAc', " WHERE nCId = " . $accCard[$key] ." ");

			$data 	 = array( 'nSalesRId' => $id,
                        'nCardId' => $accCard[$key],
                        'vACodeDr' => $CardCode,
                        'nAmountDr' => $accAmount[$key],
////////////////////// change
                        'vACodeCr' => 1,
                        'nAmountCr' => $accAmount[$key],
                        'vType' => $accTktVal[1],
                        'vTktNumber' => $accTktVal[0]);
      if(intval($chkAccID[$key]) != 0 || $chkAccID[$key] != ''){
echo " upd ---  " . print_r($data)   ; 
        $this->db->where('nId',$chkAccID[$key]);
        $this->db->update('{PRE}SalesReceiptDetails', $data);
      }else

echo " insert ---  " . print_r($data)  ; 
        $this->db->insert('{PRE}SalesReceiptDetails', $data);

		}//end foreach
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
/*
        if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
        else
        redirect(site_url("sales_receipt/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
*/
	}//end class

  function deleteSalesReceipt()
  {
    $this->db->delete('{PRE}SalesReceiptDetails', array('nSalesRId'=> $this->uri->segment(4)));
    $this->db->delete('{PRE}SalesReceipt', array('nSRId' => $this->uri->segment(4)));
    redirect(site_url("sales_receipt/showlist/".$this->uri->segment(3).'/del_suc'));
  }//end function
	
	//function to delete sub and details from db
	function deleteSubAndDetails($ID)
	{
		$this->db->delete('{PRE}SalesReceiptDetails', array('nId' => $ID));
	}//end function


	
	function deleteAllSalesReceipt()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nSRId',$val);
					$this->db->update('{PRE}SalesReceipt', $data); 
					
					$this->db->where('nSalesRId',$val);
					$this->db->update('{PRE}SalesReceiptDetails', $data); 
				}//end foreach
				redirect(site_url("sales_receipt/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nSRId',$val);
					$this->db->update('{PRE}SalesReceipt', $data); 
					
					$this->db->where('nSalesRId',$val);
					$this->db->update('{PRE}SalesReceiptDetails', $data); 
				}//end foreach
				redirect(site_url("sales_receipt/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}SalesReceiptDetails', array('nSalesRId' => $val));
					$this->db->delete('{PRE}SalesReceipt', array('nSRId' => $val));
				}//end foreach
				redirect(site_url("sales_receipt/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function


} //end class
?>
        
