<?php

class Group_entry_model extends CI_Model {



    function __construct()

    {

        // Call the Model constructor
		date_default_timezone_set('Asia/Kuwait');
        parent::__construct();

    }

	

/*----------------------Cash Book Receipt Details-----------------*/

	
function getActive_stocks()

	{
		

	$custGroup    = $this->db->query("(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName, sed.nStocktype
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='26' GROUP BY s.vName )UNION ALL(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName, sed.nStocktype
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='27' GROUP BY s.vName)");
	

		

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0) 

		{

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupRow[$row->nStocktype] = $row->vName ;

            }//end for each

        }//end if

        else 

		{

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);
		

	}//end function
	
	function getAllGroupDetails()

	{		

		//active groups

		$adminGroupRow          = array();

		/*$sqlCashreceiptQuery    = "SELECT cr.*,d.vTitle FROM {PRE}CashReceipt cr LEFT JOIN {PRE}Division d

									ON cr.nDivision = d.nId WHERE

                  cr.vLocation IN ". $this->session->userdata('accessLoc') ." 

                  ORDER BY nCRId DESC

								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;*/
								 


		$sqlGroupDetails    = "SELECT gr.* FROM {PRE}Groups_Entry gr where gr.loc IN ". $this->session->userdata('accessLoc') ." ORDER BY gr.id DESC";

		session_start();

		$_SESSION['sess_query'] = $sqlGroupDetails;	

		$adminGroupList 		= $this->db->query($sqlGroupDetails);



		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if

		return ($adminGroupRow);

	}//end function



    //get Location details

    function getAcAccountsLocation($nId)

    {

        $userTransRow = array();

        $userTrans    = $this->db->query("SELECT nLoc FROM {PRE}CashReceiptDetails WHERE nCashId = '".$nId."'");



        if ($userTrans->num_rows() > 0)

        {

            foreach ($userTrans->result() as $key=>$row)

            {

                $userTransRow[] = $row->nLoc;

            }//end for each

        }//end if



        return (array_filter($userTransRow));

    }//end function



	function getCountCashbookReceipt()

	{		

		$tabCount = $this->db->query("SELECT * FROM {PRE}CashReceipt WHERE

        vLocation IN ". $this->session->userdata('accessLoc') ." 

        ORDER BY nCRId DESC");

		return ($tabCount->num_rows());

	}//end function

	

	function getCorresCashbookReceipt()

	{

		$fetchTitle = $this->db->query("SELECT * FROM {PRE}CashReceipt WHERE nCRId = '".$this->catId."'");

		$showVal	= $fetchTitle->row();

		return $showVal;

	}//end function 

	function getCorresGroups()

	{

		$fetchTitle = $this->db->query("SELECT * FROM {PRE}Groups_Entry WHERE id = '".$this->catId."'");

		$showVal	= $fetchTitle->row();

		return $showVal;

	}//end function 

	

	function getCorresGROUPsDetails()

	{

		
		$cashDetailsLoc       	  = array();

		
		$cashDetailsRAmount 	  = array();
		$cashDetailsBAmount 	  = array();

		
		$cashDetailsDes     	  = array();

		$cashDetailsDate     	  = array();
		$cashDetailsdoc     	  = array();

		

        $cashReceiptDetailID      = array();

		

		$cashDetailsList      	  = $this->db->query("SELECT * FROM {PRE}GroupEntryDetails WHERE nGroupId = '".$this->catId."'");

		if ($cashDetailsList->num_rows() > 0)

		{

			foreach ($cashDetailsList->result() as $key=>$row)

			{

                $cashDetailsLoc[]         	  = $row->id;
				$cashDetailsdoc[]  		  = $row->doc;
				
				$cashDetailsRAmount[]  		  = $row->received; 

				$cashDetailsBAmount[]     	  = $row->balance;

				$cashDetailsDes[]     	      = $row->des;

				$cashDetailsDate[]     	  = date('d-m-Y',strtotime($row->date));

                $cashReceiptDetailID[]     	  = $row->id;



			}//end for each

		}//end if

		

		$newValArray['acLoc']    	   		= $cashDetailsLoc;
		$newValArray['cashDetailsRAmount']    	   		= $cashDetailsRAmount;
		$newValArray['cashDetailsBAmount']    	   		= $cashDetailsBAmount;

		$newValArray['cashDetailsdoc']    	   		= $cashDetailsdoc;
		

		$newValArray['acDes']        		= $cashDetailsDes;

		$newValArray['acDate']        		= $cashDetailsDate;

		$newValArray['acDetailID']          = $cashReceiptDetailID;



		return ($newValArray);

	}//end function



	function insertGroupEntry()

	{

		
    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){

      $date1= '';

    }

    else

    {

      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));

    }



    if($this->input->post('txtRefDate') =='0000-00-00' || $this->input->post('txtRefDate') == '' || $this->input->post('txtRefDate') == 0 ){

      $refDate = '';

    }

    else

    {

      $refDate = date('Y-m-d',strtotime($this->input->post('txtRefDate')));

    }

	echo $vac = $this->common_model->get_field_name('Groups_Entry','groupno'," WHERE groupno ='" .stripslashes($this->input->post('txtDocNumber'))."'");


if(true){
	
	$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vName ='" .$this->input->post('txtDocNumber')."'");
		if($customerAc==''){
			$custo = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nFId ='237' ORDER BY nCID DESC LIMIT 0,1");
			$custoc      = @split('RG',$custo);
			$number       = ($custoc[1]+1);
				$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
						'nFId' => '237',
						'dDate' => date('Y-m-d'),
						'vCode' => "RG000".$number,
						'vName' => $this->input->post('txtDocNumber'),
						'vLocation' => $this->input->post('ddlLocation'),
						'nCountry' => '5',
						'nCurrency' => '5',
						'vActive' => '1');
//echo "Customer Details <br>"; echo('<pre>');  print_r($data); echo('</pre>');
	
		$this->db->insert('{PRE}CustomerMaster', $data); 
		$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vName ='" .$this->input->post('txtDocNumber')."'");
		}	   


$t_g = $this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode ='" .$this->input->post('stk_t')."'");
$h_g = $this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode ='" .$this->input->post('stk_h')."'");
		
/*$t_g=$this->input->post('stk_t');
$t_gg 			= @split('-',$t_g);
$t_g=$t_gg[0]; 
$h_g=$this->input->post('stk_h');
$h_gg 			= @split('-',$h_g);
$h_g=$h_gg[0];*/


		
        $this->ddlLocation      = 1;//$this->input->post('ddlLocation');
		$this->dt      = $this->input->post('txtDate1');
		 $date=substr($this->dt,-4);

        

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '1'");
        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='29'");
		  $fetchCode            = $this->db->query("SELECT adoc FROM {PRE}Groups_Entry  ORDER BY  CAST(SUBSTRING_INDEX(adoc,'-',-1) AS UNSIGNED) DESC LIMIT 0,1");
        	if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber = $totalNum->adoc;
            $docNumber      = @split('-',$this->txtDocNumber);
            $fill         = $this->txtMinDigit;
            $number       = ($docNumber[3]+1);
            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='29'");
            $docNumber      = @split('-',$this->txtDocNumber);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;





		$data = array('loc' => $this->input->post('ddlLocation'),

                        'dDate' =>$date1,
						'groupno' => $this->input->post('txtDocNumber'),
						't_bal' => $this->input->post('t_bal'),
						'h_bal' => $this->input->post('h_bal'),
						'status' => $this->input->post('ddlType'),
						'dot' => $refDate,
						'paxname' => $this->input->post('txtRefName'),
						'stk_t' => $this->input->post('stk_t'),
						'stk_h' => $this->input->post('stk_h'),
						'adoc' => $countVal,
						't_g' => $t_g,
						'h_g' => $h_g,
						'ctc1' => $this->input->post('ctc1'),
						'ctc2' => $this->input->post('ctc2'),
						'ctc3' => $this->input->post('ctc3'),
						't_adt' => $this->input->post('t_adt'),
						't_cld' => $this->input->post('t_cld'),
						't_inf' => $this->input->post('t_inf'),
						'tot_t' => $this->input->post('tot_t'),
						'remarks' => $this->input->post('txtRemarks'),
						'h_sgl' => $this->input->post('h_sgl'),
						'h_dbl' => $this->input->post('h_dbl'),
						'h_tpl' => $this->input->post('h_tpl'),
						'h_suite' => $this->input->post('h_suite'),
						'h_oths' => $this->input->post('h_oths'),
						'h_tot' => $this->input->post('h_tot'),
						'totalamt' => $this->input->post('slsamt'),
						'recived' => $this->input->post('rcamt'),
						
						'acc' => $customerAc."-C",
						'acc_head' => 237,
						'vActive' => $this->input->post('chkActive'),
						'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin')
						);

						
		$this->db->insert('{PRE}Groups_Entry', $data);

		$id			  	 = $this->db->insert_id();





    
	
		$txtRDate  	 = (@array_filter($this->input->post('txtRDate')) != '')?$this->input->post('txtRDate'):0;

        $chkRAmount  	 = (@array_filter($this->input->post('chkRAmount')) != '')?$this->input->post('chkRAmount'):0;
		$chkBAmount  	 = (@array_filter($this->input->post('chkBAmount')) != '')?$this->input->post('chkBAmount'):0;

		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
		$chkdoc  	     = (@array_filter($this->input->post('doc')) != '')?$this->input->post('doc'):0;

		$chkAccID	     = $this->input->post('idArray');

		$chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;



		

	    //insert into cash receipt details

		foreach($chkBox as $key => $aitem)

		{

			if($txtRDate[$key] =='0000-00-00' || $txtRDate[$key] == '' || $txtRDate[$key] == 0 ){

				  $date1= '';

				}

				else

				{

				  $date1= date('Y-m-d',strtotime($txtRDate[$key]));

				}

			    //if new insert into db
				$data 	  = array('nGroupId' => $id,'date' => $date1,'received' => $chkRAmount[$key],'doc' => $chkdoc[$key],

                                  'balance' => $chkBAmount[$key],'des' => $accDes[$key],'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin'));
				$this->db->insert('{PRE}GroupEntryDetails', $data);



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

			redirect(site_url("group_entry/editEntry/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	

		}//end if

		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')

		{

			redirect(site_url("group_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
			

		}//end if
}		

	}//end class

	

	function updateGroupEntry()

	{



    if($this->input->post('txtDate1') =='0000-00-00' || $this->input->post('txtDate1') == '' || $this->input->post('txtDate1') == 0 ){

      $date1= '';

    }

    else

    {

      $date1= date('Y-m-d',strtotime($this->input->post('txtDate1')));

    }



    if($this->input->post('txtRefDate') =='0000-00-00' || $this->input->post('txtRefDate') == '' || $this->input->post('txtRefDate') == 0 ){

      $refDate = '';

    }

    else

    {

      $refDate = date('Y-m-d',strtotime($this->input->post('txtRefDate')));

    }

  /* 
$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vName ='" .$this->input->post('txtDocNumber')."'");
		if($customerAc==''){
			$custo = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nFId ='237' ORDER BY nCID DESC LIMIT 0,1");
			$custoc      = @split('RG',$custo);
			$number       = ($custoc[1]+1);
				$data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
						'nFId' => '237',
						'dDate' => date('Y-m-d'),
						'vCode' => "RG000".$number,
						'vName' => $this->input->post('txtDocNumber'),
						'vLocation' => $this->input->post('ddlLocation'),
						'nCountry' => '5',
						'nCurrency' => '5',
						'vActive' => '1');
//echo "Customer Details <br>"; echo('<pre>');  print_r($data); echo('</pre>');
	
		$this->db->insert('{PRE}CustomerMaster', $data); 
		$customerAc = $this->common_model->get_field_name('CustomerMaster','nCId'," WHERE vName ='" .$this->input->post('txtDocNumber')."'");
		}	*/   

/*
$t_g=$this->input->post('stk_t');
$t_gg 			= @split('-',$t_g);
$t_g=$t_gg[0];
$h_g=$this->input->post('stk_h');
$h_gg 			= @split('-',$h_g);
$h_g=$h_gg[0];*/

$t_g = $this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode ='" .$this->input->post('stk_t')."'");
$h_g = $this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode ='" .$this->input->post('stk_h')."'");



		$data = array('loc' => $this->input->post('ddlLocation'),

                        'dDate' =>$date1,
						'groupno' => $this->input->post('txtDocNumber'),
						'status' => $this->input->post('ddlType'),
						'dot' => $refDate,
						't_bal' => $this->input->post('t_bal'),
						'h_bal' => $this->input->post('h_bal'),
						'paxname' => $this->input->post('txtRefName'),
						'stk_t' => $this->input->post('stk_t'),
						'stk_h' => $this->input->post('stk_h'),
						't_g' => $t_g,
						'h_g' => $h_g,
						'ctc1' => $this->input->post('ctc1'),
						'ctc2' => $this->input->post('ctc2'),
						'ctc3' => $this->input->post('ctc3'),
						't_adt' => $this->input->post('t_adt'),
						't_cld' => $this->input->post('t_cld'),
						't_inf' => $this->input->post('t_inf'),
						'tot_t' => $this->input->post('tot_t'),
						'remarks' => $this->input->post('txtRemarks'),
						'h_sgl' => $this->input->post('h_sgl'),
						'h_dbl' => $this->input->post('h_dbl'),
						'h_tpl' => $this->input->post('h_tpl'),
						'h_suite' => $this->input->post('h_suite'),
						'h_oths' => $this->input->post('h_oths'),
						'h_tot' => $this->input->post('h_tot'),
						'totalamt' => $this->input->post('slsamt'),
						
						'recived' => $this->input->post('rcamt'),
						
						'vActive' => $this->input->post('chkActive'),'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));

		$this->db->where('id',$this->input->post('nId'));

		$this->db->update('{PRE}Groups_Entry', $data); 



		$id			     = $this->input->post('nId');

        $txtRDate  	 = (@array_filter($this->input->post('txtRDate')) != '')?$this->input->post('txtRDate'):0;

        $chkRAmount  	 = (@array_filter($this->input->post('chkRAmount')) != '')?$this->input->post('chkRAmount'):0;
		$chkBAmount  	 = (@array_filter($this->input->post('chkBAmount')) != '')?$this->input->post('chkBAmount'):0;

		$accDes  	     = (@array_filter($this->input->post('txtDes')) != '')?$this->input->post('txtDes'):0;
		$chkdoc  	     = (@array_filter($this->input->post('doc')) != '')?$this->input->post('doc'):0;

		$chkAccID	     = $this->input->post('idArray');

		$chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;



	    //insert into Account head accounts table

		foreach($chkBox as $key => $aitem)

		{

			

			//checking already exists if yes updated

			if(intval($chkAccID[$key]) != 0)

			{

				if($txtRDate[$key] =='0000-00-00' || $txtRDate[$key] == '' || $txtRDate[$key] == 0 ){

					  $date1= '';

					}

					else

					{

					  $date1= date('Y-m-d',strtotime($txtRDate[$key]));

					}
				

                $data 	  = array('nGroupId' => $id,'date' => $date1,'received' => $chkRAmount[$key],'doc' => $chkdoc[$key],

                                  'balance' => $chkBAmount[$key],'des' => $accDes[$key],'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));

				$this->db->where('id',$chkAccID[$key]);

				$this->db->update('{PRE}GroupEntryDetails', $data); 

			}//end if

			else

			{
				if($txtRDate[$key] =='0000-00-00' || $txtRDate[$key] == '' || $txtRDate[$key] == 0 ){

				  $date1= '';

				}

				else

				{

				  $date1= date('Y-m-d',strtotime($txtRDate[$key]));

				}

			    //if new insert into db
				$data 	  = array('nGroupId' => $id,'date' => $date1,'received' => $chkRAmount[$key],'doc' => $chkdoc[$key],

                                  'balance' => $chkBAmount[$key],'des' => $accDes[$key],'udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));
				$this->db->insert('{PRE}GroupEntryDetails', $data);

			}//end else

		}//end foreach




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

		    redirect(site_url("group_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));

	}//end function

	

	function deletegroupsentry()

	{

		$this->db->delete('{PRE}GroupEntryDetails', array('id' => $this->uri->segment(4)));

		$this->db->delete('{PRE}Groups_Entry', array('id' => $this->uri->segment(4)));

    

		redirect(site_url("group_entry/showlist/".$this->uri->segment(3).'/del_suc'));	

	}//end function 

	

	function deleteAllCashbookReceipt()

	{

		switch($this->input->post('ddlAction'))

		{

			case "yes":

				foreach($this->input->post('del') as $val)

				{

					//update into db

					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1','udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));

					$data1 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nCRId',$val);

					$this->db->update('{PRE}CashReceipt', $data); 

					

					$this->db->where('nCashId',$val);

					$this->db->update('{PRE}CashReceiptDetails', $data1); 

				}//end foreach

				redirect(site_url("cashbook_receipt/showlist/".$this->input->post('nCId').'/edit_suc'));	

			break;

			

			case "no":

				foreach($this->input->post('del') as $val)

				{

					//update into db

					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1','udate' => date('Y-m-d H:i:s'),'uid' =>$this->session->userdata('AbsAdmin'));

					$data1 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					
					$this->db->where('nCRId',$val);

					$this->db->update('{PRE}CashReceipt', $data); 

					

					$this->db->where('nCashId',$val);

					$this->db->update('{PRE}CashReceiptDetails', $data1); 

				}//end foreach

				redirect(site_url("cashbook_receipt/showlist/".$this->input->post('nCId').'/edit_suc'));	

			break;



			case "delete":

				foreach($this->input->post('del') as $val)

				{

					$this->db->delete('{PRE}CashReceiptDetails', array('nCashId' => $val));

					$this->db->delete('{PRE}CashReceipt', array('nCRId' => $val));

				}//end foreach

				redirect(site_url("cashbook_receipt/showlist/".$this->input->post('nCId').'/del_suc'));	

			break;

		}//end switch

	}//end function



	function getActiveAccount()

	{

		$typeGroupRow = array();

		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

										  	LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId

										  WHERE v.nFid=237 ORDER BY vTitle ASC");

		if ($typeGroup->num_rows() > 0)

		{

//			$typeGroupRow[0] = SELECT;

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

	

    function getActiveSubAccounts()

    {

        $custGroup 	  = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

											WHERE ah.vActive = '1' AND ahc.vSubSale = 'C' AND vSubLedger = 'S' ORDER BY vTitle ASC");

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0)

        {

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row)

            {

                $custGroupRow[$row->ahaId] = (stripslashes($row->vTitle));

            }//end for each

        }//end if

        else

        {

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);

    }//end function



	function getSubAccountValues()

	{

		$typeGroupRow    = array();

		$typeGroupRow[0] = SELECT;

		return($typeGroupRow);

	}//end function



    function getAccountMultipleValues()

	{

        $typeGroupRow    = array();

        $vendorGroupRow  = array();

        $AgentGroupRow   = array();

        $OtherGroupRow   = array();

        $accheadGroupRow = array();



        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");

        if ($typeGroup->num_rows() > 0)

        {

            foreach ($typeGroup->result() as $key=>$row)

            {

                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if



        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY vName ASC");

        if ($vendorGroup->num_rows() > 0)

        {

            foreach ($vendorGroup->result() as $key=>$row)

            {

                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);



        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY vName ASC");

        if ($AgentGroup->num_rows() > 0)

        {

            foreach ($AgentGroup->result() as $key=>$row)

            {

                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($AgentGroupRow,$resultArray);



        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY vName ASC");

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

										   		ORDER BY vTitle ASC");

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



    function filterSubAccount($accGroup,$table)

    {

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

													 WHERE ah.vActive = '1' AND v.nFId = '".$accNameId."'

													  ORDER BY nId DESC");

// AND ahc.vSubLedgerType = 'A'

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



    function getSubAccountMultipleValues()

	{

		$typeGroupRow = array();

		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

											WHERE ah.vActive = '1' AND ahc.vSubLedger = 'S' ORDER BY nId DESC");

		if ($typeGroup->num_rows() > 0)

		{

			$typeGroupRow[0] = SELECT;

			foreach ($typeGroup->result() as $key=>$row)

			{

				$typeGroupRow[$row->subId] = $row->subName.'-'.$row->subCode;

			}//end for each

		}//end if

		else

		{

			$typeGroupRow[0] = NOVALUE;

		}//end else

		return ($typeGroupRow);

	}//end function



    function getDebitModeType()

    {

        $modeTyperow = array("Dr" => "Debit");

        return ($modeTyperow);

    }//end function



    function getModeType()

    {

        $modeTyperow = array("Cr" => "Credit", "Dr" => "Debit");

        return ($modeTyperow);

    }//end function



	//search finance

	function searchActiveFinance($searchKey)

	{

		$typeGroupRow = array();

		

		$extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode 

								LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle 

						   LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) AND ahc.vSubSale = 'C'";

		if(trim($searchKey) == 0)

		{

			$extaCondition = '';

		}//end if

		

		$typeGroup    = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca 

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



    function getDocNum()

    {

		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='11'");

        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CashReceipt ORDER BY nCRId DESC LIMIT 0,1");

        if($fetchCode->num_rows() > 0):

            $totalNum           = $fetchCode->row();

            $this->txtDocNumber	= $totalNum->vDocNumber;

            $docNumber 			= @split('-',$this->txtDocNumber);



            $fill   			= $this->txtMinDigit;

            $number 			= ($docNumber[3]+1);

            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);

            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;

        else:

            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");

            $countVal 			= $this->txtDocNumber;

        endif;



        return($countVal);

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



	function getLocation()

	{

		$locGroupRow    = array();

		$locGroupRow[0] = SELECT;

		return ($locGroupRow);

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



	function getMultipleLocation()

	{

		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' ORDER BY nPosition DESC");

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

	

	function getActiveTransactions()

	{

		$typeGroupRow    = array("N" => "Normal", "C" =>  "CANCEL");
		return ($typeGroupRow);

	}//end function
	function t_quat($qn){
		
		$custGroup    = $this->db->query("SELECT sum(g.tot_t) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_t = sed.vCode

											  WHERE s.nTransaction='26' AND sed.vCode='".$qn."' ORDER BY sed.nId DESC ");
	if ($custGroup->num_rows() > 0) 

		{

           
            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupnQuantity= $row->nQuantity;
				 $custGroupncont= $row->cont;

            }//end for each

        }//end if
		
		$custGroupRow=$custGroupnQuantity-$custGroupncont;
       
        return ($custGroupRow); 
											  
	}
	
	function h_quat($qn){
		
	$custGroup    = $this->db->query("SELECT sum(g.tot_t) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_h = sed.vCode

											  WHERE s.nTransaction='27' AND sed.vCode='".$qn."' ORDER BY sed.nId DESC ");
	if ($custGroup->num_rows() > 0) 

		{

           
            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupnQuantity= $row->nQuantity;
				 $custGroupncont= $row->cont;

            }//end for each

        }//end if
		$custGroupRow=$custGroupnQuantity-$custGroupncont;
       
        return ($custGroupRow); 
											  
	}
	
	
	function getActive_st()

	{
		

		$custGroup    = $this->db->query("SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='26' ORDER BY sed.nId DESC");

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0) 

		{

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupRow[$row->vCode] = (stripslashes($row->vCode) . ' (' . stripslashes($row->nQuantity) . ') ');

            }//end for each

        }//end if

        else 

		{

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);
		

	}//end function
	function getActive_st_v()

	{

		$custGroup    = $this->db->query("SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='27' ORDER BY sed.nId DESC");

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0) 

		{

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupRow[$row->vCode] = (stripslashes($row->vName) . ' (' . stripslashes($row->nQuantity) . ') ');

            }//end for each

        }//end if

        else 

		{

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);

	}//end function

	

	function getActiveCurrency()

	{

		$currGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' 

											ORDER BY vTitle ASC");

		$currGroupRow = array();

		

		if ($currGroup->num_rows() > 0)

		{

			$currGroupRow[0] = SELECT;

			foreach ($currGroup->result() as $key=>$row)

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

	function filterdoc($doc)

	{

        $locGroup    = $this->db->query("SELECT * FROM {PRE}Groups_Entry WHERE  groupno = '".$doc."'");

       

        if ($locGroup->num_rows() > 0) 

		{
		$locGroupRow = "Duplicate";
        
		}//end if

        else 

		{

            $locGroupRow = "Available";

        }//end else

        return $locGroupRow;

	}//end function



    function filterAllocation($ddlAccount,$ddlSubAccount)

    {

        $adminGroupRow    = array();

		if($this->uri->segment(4) == 'a')

		{

           $adminGroupList   = $this->db->query("SELECT sc.*,scd.*, sad.vTable,sad.nDrAccount,ca.vAllTktNumber ,

                                                    CASE WHEN ca.vBalAmt > 0 THEN (scd.vAllocation - (CASE WHEN

        											(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 

																AND nSalesCloseDetId = scd.nId 

                    									AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

														 	vTable = '".$this->input->post('tableVal')."') is null THEN 0

            											ELSE 

             												(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 

																	AND nSalesCloseDetId = scd.nId 

                    										AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

														 vTable = '".$this->input->post('tableVal')."') END)) ELSE scd.vAllocation END AS allocAmt ,

                                                    CASE WHEN scd.vAllocation - (CASE WHEN

        											(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 

																AND nSalesCloseDetId = scd.nId 

                    									AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

														 	vTable = '".$this->input->post('tableVal')."') is null THEN 0

            											ELSE 

             												(SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId 

																	AND nSalesCloseDetId = scd.nId 

                    										AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

														 vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                        WHERE sad.nDrAccount = '".$ddlAccount."' AND sad.nAcHead = '".$ddlSubAccount."'

                                                        AND scd.vAllocation > 0

                                                    GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC");



/*

echo "SELECT sc.*,scd.*, sad.vTable,sad.nDrAccount,ca.vAllTktNumber     ,

                                                    CASE WHEN ca.vBalAmt > 0 THEN (scd.vAllocation - (CASE WHEN

                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                AND nSalesCloseDetId = scd.nId

                                      AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                              vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                  ELSE

                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                  AND nSalesCloseDetId = scd.nId

                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                             vTable = '".$this->input->post('tableVal')."') END)) ELSE scd.vAllocation END AS allocAmt ,

                                                    CASE WHEN scd.vAllocation - (CASE WHEN

                              (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                AND nSalesCloseDetId = scd.nId

                                      AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                              vTable = '".$this->input->post('tableVal')."') is null THEN 0

                                  ELSE

                                    (SELECT SUM(vAllAmt) FROM {PRE}CashAllocation WHERE nSalesCloseId = sc.nSCId

                                  AND nSalesCloseDetId = scd.nId

                                        AND nDrAccount = '".$this->input->post('ddlAccount2')."' AND

                             vTable = '".$this->input->post('tableVal')."') END) > 0 THEN 1 ELSE 0 END AS balCheck

                                                        FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId

                                                        LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

                                                    LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

                                                        WHERE sad.nDrAccount = '".$ddlAccount."' AND sad.nAcHead = '".$ddlSubAccount."'

                                                        AND scd.vAllocation > 0

                                                    GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM

                                                        {PRE}CashAllocation)) ORDER BY sc.dDate DESC";

*/

		}//end if

		else

		{

			$adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails

													scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails

												  sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

													WHERE ca.nReceiptDId =  '".$this->uri->segment(5)."' AND vAllType = 'CR'

													 AND sad.nDrAccount = '".$ddlAccount."'

												  GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");
												  
												 

		}//end else



        if ($adminGroupList->num_rows() > 0)

        {

            foreach ($adminGroupList->result() as $key=>$row)

            {

                $adminGroupRow[] = $row;

            }//end for each

        }//end if

        return ($adminGroupRow);

    }//end function



	//fetching single details

	function showDetails($cashid)

	{

		$viewAccessRow   = array();

		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}CashReceipt WHERE nCRId = '".$cashid."'");

		//single fetch

        $row        = $viewAccessList->row();

        $divName    = $this->common_model->get_field_name('Division','vTitle'," WHERE nId = '".$row->nDivision."'");

        $locName    = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");



        $dDate      = ($row->dDate != '0000-00-00')?date('d-m-Y',strtotime($row->dDate)):'NIL';

        $dRefDate   = ($row->dRefDate != '0000-00-00')?date('d-m-Y',strtotime($row->dRefDate)):'NIL';

        $dInvDate   = ($row->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($row->dInvoiceDate)):'NIL';

        $chequeDate = ($row->dChequeDate != '0000-00-00')?date('d-m-Y',strtotime($row->dChequeDate)):'NIL';



        switch($row->vTransMode)

        {

            case "Cr" :

                $modeofPay	= 'Credit';

                break;



            case "Ca" :

                $modeofPay	= 'Cash';

                break;



            case "Dr" :

                $modeofPay	= 'Debit';

                break;

        }//end switch

        $content  = '<table class="reportForm">

						<tr>

							<td><strong>'.DIVISIONNAME.'</strong></td>

							<td>'.$divName.'</td>

							<td><strong>'.LOCATIONNAME.'</strong></td>

							<td>'.$locName.'</td>

						    <td><strong>'.CUSTDATE.'</strong></td>

						    <td>'.$dDate.'</td>

							<td><strong>'.TRANSNAME.'</strong></td>

							<td>'.$this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'").'</strong></td>

							<td><strong>'.DOCNUMBER.'</strong></td>

							<td>'.$row->vDocNumber.'</strong></td>

					    </tr>



						<tr>

						    <td><strong>'.TRANSMODE.'</strong></td>

						    <td>'.$modeofPay.'</strong></td>

							<td><strong>'.ACCCODENAME.'</strong></td>

							<td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->nAcCode."'").'</td>

						    <td><strong>'.SUBCODE.'</strong></td>

						    <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row->vSubCode."'").'</td>

						    <td><strong>'.CURRNAME.'</strong></td>

							<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId='".$row->nCurrency."'").'</td>

							<td><strong>'.RATE.'</strong></td>

							<td>'.$row->vRate.'</strong></td>

						</tr>



						<tr>

							<td><strong>'.AMOUNTDC.'</strong></td>

							<td>'.$row->vAmountDC.'</td>

							<td><strong>'.AMOUNTFC.'</strong></td>

							<td>'.$row->vAmountFC.'</td>

						    <td><strong>'.REFNAME.'</strong></td>

						    <td>'.$row->vRefName.'</td>

						    <td><strong>'.REFDATE.'</strong></td>

						    <td>'.$dRefDate.'</td>

							<td><strong>'.REFNUMBER.'</strong></td>

							<td>'.$row->vRefNumber.'</td>

						</tr>



						<tr>

						    <td><strong>'.ENTEREDBY.'</strong></td>

						    <td>'.$row->vEnteredBy.'</td>

						    <td><strong>'.REMARKS.'</strong></td>

							<td colspan="3">'.$row->vRemarks.'</td>

						    <td><strong>'.INVOICE.'</strong></td>

						    <td>'.$row->vInvoice.'</td>

						    <td><strong>'.INVOICEDATE.'</strong></td>

						    <td>'.$dInvDate.'</td>

						</tr>



						<tr>

						    <td><strong>'.LOCATIONNAME.'</strong></td>

							<td><strong>'.ACCCODENAME.'</strong></td>

							<td><strong>'.SUBCODE.'</strong></td>

							<td><strong>'.BUDGETMODE.'</strong></td>

							<td><strong>'.REFNUMBER.'</strong></td>

							<td><strong>'.CURRNAME.'</strong></td>

							<td><strong>'.EXCHANGERATE.'</strong></td>

							<td><strong>'.AMOUNTDC.'</strong></td>

							<td><strong>'.AMOUNTFC.'</strong></td>

							<td><strong>'.DESCRIPTION.'</strong></td>

						</tr>';

						

		$viewAccessList1  = $this->db->query("SELECT * FROM {PRE}CashReceiptDetails WHERE nCashId = '".$cashid."' ORDER BY nId DESC");



        foreach ($viewAccessList1->result() as $key=>$row1)

        {

            switch($row1->vMode)

            {

                case "Cr" :

                    $modeofPay1	= 'Credit';

                break;



                case "Ca" :

                    $modeofPay1	= 'Cash';

                break;



                case "Dr" :

                    $modeofPay1	= 'Debit';

                break;

            }//end switch



			switch($row1->vTable)

			{

				case "C" :

					$accName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$row1->vACode."'");

					$subAcc	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vAcSubCode."'");

				break;

	

				case "A" :

				    $accName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row1->vACode."'");

					$subAcc	 = NOVALUE;

				break;

			}//end switch



            $content .= '<tr>

				<td>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row1->nLoc."' ").'</td>

				<td>'.$accName.'</td>

				<td>'.$subAcc.'</td>

				<td>'.$modeofPay1.'</td>

				<td>'.$row1->vAcRefNumber.'</td>

				<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$row1->nCurrency."' ").'</td>

				<td>'.$row1->vExchange.'</td>

				<td>'.$row1->nAmount.'</td>

				<td>'.$row1->vFCAmount.'</td>

				<td>'.$row1->vDes.'</td>

			</tr>';

        }//end foreach

		

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

	

 	function getSubledgerNo($financeId)

	{

		$fetchTitle 	  = $this->db->query("SELECT ahc.*,cr.* FROM {PRE}AccountHeadControls ahc LEFT JOIN {PRE}CashReceipt cr ON cr.nAcCode=ahc.nId

												 WHERE ahc.nActHId='".$financeId."'");

		$showVal	      = $fetchTitle->row();

		$chkCode		  = $this->getAutomaticCode('CashReceipt',$showVal->vSubLedgerNo,$showVal->vSLedgerAuto,'nCRId');

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

        $conversion       = $converted_amount[0];

        //$conversion = preg_replace('/[x00-x08x0B-x1F]/', '', $conversion);

        $conversion       = $conversion * $this->fcAmount;

        $conversion       = round($conversion, 2);

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

        $fetchTitle 	  = $this->db->query("SELECT vRate FROM {PRE}Currency WHERE nId = '".$cur."'");

        $showVal	      = $fetchTitle->row();

        $chkRate		  = $showVal->vRate;

        return $chkRate;

    }//end function

	

	//function to delete sub and details from db

	function deleteSubAndDetails($ID)

	{

		$this->db->delete('{PRE}GroupEntryDetails', array('id' => $ID));

	}//end function



	//searching by division name or doc number

	function getFilterList($extraCheck)

	{

		$adminGroupRow             = array();

		$sqlCashreceiptFilterQuery = "SELECT gr.*

									   FROM {PRE}Groups_Entry gr  WHERE 1=1 

								      ".$extraCheck." AND

                      gr.loc IN ". $this->session->userdata('accessLoc') ." 

                      ORDER BY gr.id DESC";

									  // LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;

		session_start();

		$_SESSION['sess_query']    = $sqlCashreceiptFilterQuery;	

		$adminGroupList 		   = $this->db->query($sqlCashreceiptFilterQuery);

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

		$tabCount = $this->db->query("SELECT cr.*,d.*,cr.nCRId as crId,d.nId as divId,d.vTitle as divName

										FROM {PRE}CashReceipt cr LEFT JOIN {PRE}Division d ON cr.nDivision = d.nId

									  ".$extraCheck." AND

                    cr.vLocation IN ". $this->session->userdata('accessLoc') ." 

                    ORDER BY cr.nCRId DESC");

		return ($tabCount->num_rows());

	}//end function



	function insertMultipleAllocation()

	{

        $allDate     	 = $this->input->post('dDate');

        $allTkt      	 = $this->input->post('vTktNumber');

        $allDebitAmt     = $this->input->post('nDebit');

        $allAmt     	 = $this->input->post('nAllocated');

        $allBalAmt  	 = $this->input->post('nBalance');

        $allLoc       	 = $this->input->post('nLocation');

		$chkId           = $this->input->post('aid');

		$chksalesId      = $this->input->post('salesid');



		//delete cash allocation with checked

		if($this->uri->segment(4) == 'e')

		{

			$this->db->delete('{PRE}CashAllocation',array('nReceiptDId' => $this->uri->segment(5),'vAllType' => 'CR'));



        }//end if



		//insert into cash receipt details

    foreach($chkId as $key => $aitem)

    {

      $chksalesId_1 = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$aitem."'");

      $getCashAllocId = $this->common_model->get_field_name('CashAllocation','nCAId'," WHERE nReceiptDId = 0 AND nSalesCloseDetId =". $aitem . " AND nSalesCloseId = ".$chksalesId_1 . "  AND vAllType = 'CR' AND vAllTktNumber = '". $allTkt[$key] . "' " );

      if($getCashAllocId != ''){

        // update if present in db

        $data =  array('vAllAmt' => $allAmt[$key],

            'vBalAmt' => $allBalAmt[$key]);

        $this->db->where('nCAId' ,$getCashAllocId);

        $this->db->update('{PRE}CashAllocation', $data);

      } else{



        //add if not present in db

        $data 	    = array('nUserId' => $this->session->userdata('AbsAdmin'),'nSalesCloseId' => $chksalesId_1,

            'nSalesCloseDetId' => $aitem,'vAllType' => 'CR','dDate' => $allDate[$key],

            'nDrAccount' => $this->session->userdata('AccVal'),'vTable' => $this->session->userdata('TableVal'),

            'vAllTktNumber' => $allTkt[$key],'vDebitAmt' => $allDebitAmt[$key],'vAllAmt' => $allAmt[$key],

            'vBalAmt' => $allBalAmt[$key],'vLocation' => $allLoc[$key],'nReceiptDId' => '0');

        $this->db->insert('{PRE}CashAllocation', $data);

      }// end if

    }//end foreach	

	}//end function



	/*function getInvoiceDetails($salesDetId)

	{

		$salesCloseID           = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$salesDetId."'");

		$invoiceNum             = $this->db->query("SELECT * FROM {PRE}SalesClosing WHERE nSCId = '".$salesCloseID."'");

		if ($invoiceNum->num_rows() > 0)

		{

			foreach ($invoiceNum->result() as $key=>$row)

			{

				$invoiceNumRow['invoice'] = $row->vInvoiceNumber;

                $invoiceNumRow['invDate'] = $row->dDate;

				$invoiceNumRow['salesID'] = $row->nSCId;

			}//end for each

		}//end if

		return ($invoiceNumRow);

	}*///end function

	

	//check exist in allocation table

	function getAllocChecked($did,$sid,$rid)

	{

		$chkSql = $this->db->query("SELECT nSalesCloseDetId FROM {PRE}CashAllocation WHERE 

									 nSalesCloseDetId = '".$did."' AND nSalesCloseId = '".$sid."' 

									AND nReceiptDId = '".$rid."'  AND vAllType = 'CR'");

		if($chkSql->num_rows() > 0)

			$val = ' checked="checked"';

		else

			$val = '';

		

		return ($val);

	}//end funciton



	

	//new getInvoiceDetails

	function getInvoiceDetails($salesDetId)

    {

        $salesCloseID           = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$salesDetId."'");

        $salestktNumber         = $this->db->query("SELECT vTktNumber FROM {PRE}salesclosingdetails WHERE nSalesCloseId = '".$salesCloseID."'");

            if ($salestktNumber->num_rows() > 0)

            {

				foreach ($salestktNumber->result() as $key=>$row)

               {

				$ticketgetID     = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$row->vTktNumber."'");

				$ids[]=$ticketgetID;

			   }  

			}                                    

      // $ticketID           = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$salestktNumber."'");

         $salesCloseBID      = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId = '".$salesDetId."'");

         $invoiceNum         = $this->db->query("SELECT * FROM {PRE}SalesClosing WHERE nSCId = '".$salesCloseBID."' GROUP BY vInvoiceNumber");

         if ($invoiceNum->num_rows() > 0)

        {

            foreach ($invoiceNum->result() as $key=>$row)

            {

                $invoiceNumRow['invoice'] = $row->vInvoiceNumber;

                $invoiceNumRow['invDate'] = $row->dDate;

                $invoiceNumRow['salesID'] = $row->nSCId;

                $invoiceNumRow['ticketID'] = $ids;

            }//end for each

        }//end if

        return ($invoiceNumRow);

                               

    }//end function


function getCRV($accName,$accType,$loc)
	{
		
		
    /*AND bb.vDocNumber NOT IN( SELECT doc from tbl_GroupEntryDetails where doc like bb.vDocNumber )*/

       $accheadGroupQuery = "(SELECT crd.vACode,crd.vTable,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."' AND  cr.vLocation='".$loc."'
                                  AND crd.vTable='".$accType."')

                                 
UNION ALL (SELECT cpd.vACode,cpd.vTable,
								  cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."' AND  cp.vLocation='".$loc."')

                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."' AND  bb.vLocation='".$loc."'  )

                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."' AND  bp.vLocation='".$loc."')

                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."' AND  jv.vLocation='".$loc."')

 ";
         $accheadGroup = $this->db->query($accheadGroupQuery);
        if ($accheadGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $typeGroupRow[$row1->vDocNumber] = $row1->vDocNumber.'-'.$row1->vRefName;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end if

        return ($typeGroupRow);
	}//end function
	

	function getAccounts($Id)

	{

		$accountListRow = array();		



                $accountList    = $this->db->query("SELECT

				CASE

				WHEN  vTable = 'AG' OR  vTable = 'C' OR  vTable = 'V' OR  vTable = 'O' THEN

				(select vTitle from {PRE}AccountHeadAccounts where nId =  vAcSubCode ) 

				ELSE (select ach.vTitle from tbl_AccountHeadAccounts ach where ach.nId = tbl.vACode)

				END

				as subAccName,

				CASE

                                WHEN  vTable = 'AG' THEN (select temp.vName from {PRE}Agent temp where temp.nAId = vACode)

                                WHEN  vTable = 'C' THEN (select temp.vName from {PRE}CustomerMaster temp where temp.nCId = vACode)

                                WHEN  vTable = 'V' THEN (select temp.vName from {PRE}VendorMaster temp where temp.nVId = vACode)

                                WHEN  vTable = 'O' THEN (select temp.vName from {PRE}Other temp where temp.nOId = vACode)

                                ELSE  '' 

                                END as  drAccountName,

                                IF( vMode = 'Dr',nAmount,0) as Debit,

                                IF( vMode = 'Cr',nAmount,0) as Credit

                         FROM {PRE}CashReceiptDetails tbl WHERE nCashId = ".$Id."");

 



		if ($accountList->num_rows() > 0)

		{



			foreach ($accountList->result() as $key=>$row)

			{

				$accountListRow[] = $row;

			}//end for each

		}//end if

		return ($accountListRow);

	}//end function

/*

  function newAllocation(){

    $adminGroupList   = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,ca.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails

        scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails

        sad ON sc.nSCId = sad.nSalesCloseId LEFT JOIN {PRE}CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)

        WHERE ca.nReceiptDId =  0 AND vAllType = 'CR'

        GROUP BY ca.nCAId ORDER BY ca.nCAId ASC");



    if ($adminGroupList->num_rows() > 0)

    {

      foreach ($adminGroupList->result() as $key=>$row)

      {

        $adminGroupRow[] = $row;

      }//end for each

    }//end if

    return ($adminGroupRow);

  }//end function

*/







}//end class

?>

