<?php
class Visa_entry_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function getFilterList($check){
	  
	  $v_query = "SELECT ve.*  FROM {PRE}VisaEntry ve
				LEFT JOIN {PRE}VisaEntryPassDetails vpd ON vpd.nVId = ve.nVId
				LEFT JOIN {PRE}VisaEntryAmtDetails vad ON vad.vid = ve.nVId
				Where ve.nLocation IN ". $this->session->userdata('accessLoc') .$check." GROUP BY ve.nVId 
      ORDER BY ve.nVId DESC "; 
    
    $visaEntryGroupList 		= $this->db->query($v_query);
    if ($visaEntryGroupList->num_rows() > 0)
    {
      foreach ($visaEntryGroupList->result() as $key=>$row)
      {
        $visaEntryGroupRow[] = $row;
      }//end for each
    }//end if
    return ($visaEntryGroupRow);
	  
  }
  function getVisaEntryQuery(){
    $v_query = "SELECT ve.*, ve.nVId as nId FROM {PRE}VisaEntry ve Where ve.nLocation IN ". $this->session->userdata('accessLoc') ."
      ORDER BY YEAR(ve.dDate) DESC, MONTH(ve.dDate) DESC, DAY(ve.dDate) DESC, ve.vTicktManual DESC "; 
      return $v_query;

  }//end function
  
  function getCRV($accName,$accType,$loc,$iv)
	{
	
		if($iv=='e'){
			
			$accheadGroupQuery = "
		(SELECT crd.vACode,crd.vTable,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."' AND  cr.vLocation='".$loc."'
                                  AND crd.vTable='".$accType."'
								   AND cr.vDocNumber IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cr.vDocNumber ))
UNION ALL (SELECT crd.vACode,crd.vTable,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."' AND  cr.vLocation='".$loc."'
                                  AND crd.vTable='".$accType."'
								   AND cr.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cr.vDocNumber ))

                                 
								UNION ALL (SELECT cpd.vACode,cpd.vTable,
								  cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."' AND  cp.vLocation='".$loc."'
								   AND cp.vDocNumber IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cp.vDocNumber ))
								UNION ALL (SELECT cpd.vACode,cpd.vTable,
								  cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."' AND  cp.vLocation='".$loc."'
								   AND cp.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cp.vDocNumber ))

                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."' AND  bb.vLocation='".$loc."' 
							 AND bb.vDocNumber IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bb.vDocNumber ) )
							 
							 UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."' AND  bb.vLocation='".$loc."' 
							 AND bb.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bb.vDocNumber ) )

                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."' AND  bp.vLocation='".$loc."'
								   AND bp.vDocNumber IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bp.vDocNumber ))
								UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."' AND  bp.vLocation='".$loc."'
								   AND bp.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bp.vDocNumber ))

                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."' AND  jv.vLocation='".$loc."'
								   AND jv.vDocNumber IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like jv.vDocNumber ))
									UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."' AND  jv.vLocation='".$loc."'
								   AND jv.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like jv.vDocNumber ))

 ";
		}else{
			 $accheadGroupQuery = "
		(SELECT crd.vACode,crd.vTable,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."' 
                                  AND crd.vTable='".$accType."'
								  AND cr.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cr.vDocNumber ))

                                 
UNION ALL (SELECT cpd.vACode,cpd.vTable,
								  cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."' 
								  AND cp.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like cp.vDocNumber ))
	
	UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."'  
							 AND bb.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bb.vDocNumber ) 
							 )

                                  UNION ALL (SELECT bpd.vACode,bpd.vTable,bpd.vMode,bp.vDocNumber,bp.vRefName FROM
                                  {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails
                                  bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1' AND bpd.vMode='Cr' AND bpd.vACode='".$accName."' AND bpd.vTable='".$accType."' 
								  AND bp.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bp.vDocNumber ))

                                  UNION ALL (SELECT jvd.vACode,jvd.vTable,jvd.vMode,jv.vDocNumber,jv.vRefName
                                  FROM {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId
                                  WHERE jv.vActive='1' AND jvd.vMode='Cr' AND jvd.vACode='".$accName."' AND jvd.vTable='".$accType."'
AND jv.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like jv.vDocNumber )								  )

 ";
		} 
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
	
	function getCRVa($accName,$accType,$loc,$iv)
	{
	
		
		$accheadGroupQuery = "
		(SELECT crd.vACode,crd.vTable,crd.vMode,cr.vDocNumber,cr.vRefName FROM
                                  {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON
                                  crd.nCashId = cr.nCRId WHERE cr.vActive='1' AND crd.vMode='Cr' AND crd.vACode='".$accName."' AND  cr.vLocation='".$loc."'
                                  AND crd.vTable='".$accType."')

                                 
UNION ALL (SELECT cpd.vACode,cpd.vTable,
								  cpd.vMode,cp.vDocNumber,cp.vRefName FROM
                                  {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON
                                  cpd.nCashPId = cp.nCPId WHERE cp.vActive='1' AND cpd.vMode='Cr' AND cpd.vACode='".$accName."' AND cpd.vTable='".$accType."' AND  cp.vLocation='".$loc."')

                                  UNION ALL (SELECT bbd.vACode,bbd.vTable,bbd.vMode,bb.vDocNumber,bb.vRefName FROM
                                  {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON
                                  bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1' AND bbd.vMode='Cr' AND bbd.vACode='".$accName."' AND bbd.vTable='".$accType."' AND  bb.vLocation='".$loc."' 
							 AND bb.vDocNumber NOT IN( SELECT doc from tbl_VisaEntryAmtDetails where doc like bb.vDocNumber ) )

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
	
	function getSubAccountValues()

	{

		$typeGroupRow    = array();

		$typeGroupRow[0] = SELECT;

		return($typeGroupRow);

	}//end function
	function deleteSubAndDetails($ID)

	{

		$this->db->delete('{PRE}VisaEntryPassDetails', array('nVPId' => $ID));

	}//end function
	
	function deleteSubAndDetails1($ID)

	{

		$this->db->delete('{PRE}VisaEntryAmtDetails', array('id' => $ID));

	}//end function
	
	
	function deletevisa()
 
    {

        $this->db->delete('{PRE}VisaEntry', array('nVId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}VisaEntryAmtDetails', array('vid' => $this->uri->segment(4)));

        $this->db->delete('{PRE}VisaEntryPassDetails', array('nVId' => $this->uri->segment(4)));

       

        redirect(site_url("visa_entry/showlist/" . $this->uri->segment(3) . '/del_suc'));

    }//end function
	
  function getAllVisaEntryCount(){


    $visaEntryGroupRow  = array();
    $v_query = $this->getVisaEntryQuery();
    //$v_query = $v_query . "  LIMIT " . $this->common_model->_offset." , ".$this->common_model->_limit;

    session_start();
    $_SESSION['sess_query'] = $v_query;	
    $visaEntryGroupList 		= $this->db->query($v_query);

    if ($visaEntryGroupList->num_rows() > 0)
    {
      foreach ($visaEntryGroupList->result() as $key=>$row)
      {
        $visaEntryGroupRow[] = $row;
      }//end for each
    }//end if
    return ($visaEntryGroupRow);

  }//end function
  
  
  function getVisaEntry(){


    $visaEntryGroupRow  = array();
    $v_query = "SELECT ve.*, ve.nVId as nId FROM {PRE}VisaEntry ve WHERE ve.nVId ='".$this->catId."'";
    
    session_start();
    $_SESSION['sess_query'] = $v_query;	
    $visaEntryGroupList 		= $this->db->query($v_query);

    $showVal	= $visaEntryGroupList->row();
    return ($showVal);

  }//end function
  
  
  function getVisaEntryDetails()

    {
		$vedid = array();

        $vPassName           = array();

        $vPhone          = array();

        $vPassportNo        = array();

        $dDOB        = array();

        $vNationality     = array();

        $dVisaIssueDate       = array();

        $dVisaExpiryDate     = array();
		$vGender     = array();
		$vMaritalStatus     = array();
		$nAgentId     = array();
		$nCustomerId     = array();

        



        $ved = $this->db->query("SELECT * FROM {PRE}VisaEntryPassDetails WHERE nVId = '".$this->catId."'");



        if ($ved->num_rows() > 0) {

            foreach ($ved->result() as $key => $row) {



                $vedid[]         = $row->nVPId;

                $vPassName[]        = $row->vPassName;

                $vPhone[]      = $row->vPhone;

                $vPassportNo[]      = $row->vPassportNo;

                $dDOB[]   = date('d-m-Y', strtotime($row->dDOB));

                $vNationality[]     = $row->vNationality;

                $dVisaIssueDate[]   =  date('d-m-Y', strtotime($row->dVisaIssueDate));

                $dVisaExpiryDate[]     =  date('d-m-Y', strtotime($row->dVisaExpiryDate));



                $vGender[]        = $row->vGender;

                $vMaritalStatus[]    = $row->vMaritalStatus;

                $nAgentId[]     = $row->nAgentId;

                $nCustomerId[] = $row->nCustomerId;

                

            }//end for each

        }//end if



        $newValArray['id']          = $vedid;

        $newValArray['vPassName']        = $vPassName;
        $newValArray['vPhone']       = $vPhone;

        $newValArray['vPassportNo']      = $vPassportNo;

        $newValArray['dDOB']      = $dDOB;

        $newValArray['vNationality']     = $vNationality;

        $newValArray['dVisaIssueDate']   = $dVisaIssueDate;

        $newValArray['dVisaExpiryDate']     = $dVisaExpiryDate;



        $newValArray['vGender']        = $vGender;

        $newValArray['vMaritalStatus']    = $vMaritalStatus;

        $newValArray['nAgentId']     = $nAgentId;

        $newValArray['nCustomerId'] = $nCustomerId;




        return ($newValArray);

    }//end function
  
  
  function getVisaEntryDetails_amt()

    {
		$veid = array();

       
        $slsamt     = array();
		$netamt     = array();
		$profit     = array();
		$acc     = array();
		$doc     = array();
		
        

        $ved = $this->db->query("SELECT * FROM {PRE}VisaEntryAmtDetails WHERE vid = '".$this->catId."'");



        if ($ved->num_rows() > 0) {

            foreach ($ved->result() as $key => $row) {



                $veid[]         = $row->id;

                $slsamt[]        = $row->slsamt;

                $netamt[]      = $row->netamt;

                $profit[]      = $row->profit;


                $acc[]     = $row->acc;
				$doc[]     = $row->doc;

                
                

            }//end for each

        }//end if



        $newValArray['id']          = $veid;

        $newValArray['slsamt']        = $slsamt;
        $newValArray['netamt']       = $netamt;

        $newValArray['profit']      = $profit;

        $newValArray['acc']      = $acc;
		$newValArray['doc']      = $doc;

        




        return ($newValArray);

    }//end function
  
  

  function getAllVisaEntryDetails(){

    $v_query = "SELECT ve.*, ve.nVId as nId FROM {PRE}VisaEntry ve
      ORDER BY ve.nVId DESC "; 
    
    $visaEntryGroupList 		= $this->db->query($v_query);
    if ($visaEntryGroupList->num_rows() > 0)
    {
      foreach ($visaEntryGroupList->result() as $key=>$row)
      {
        $visaEntryGroupRow[] = $row;
      }//end for each
    }//end if
    return ($visaEntryGroupRow);

  } //end function
  
  function getGender()

    {

        $accheadGroupRow = array('M'=>'MALE','F'=>'FEMALE');
		return ($accheadGroupRow);
	}
	function getmstatus()

    {

        $accheadGroupRow = array('S'=>'SINGLE','M'=>'MARRIED');
		return ($accheadGroupRow);
	}
	
  function getAgent()

    {

        $accheadGroupRow = array();

        $accheadGroup    = $this->db->query("SELECT *

                        FROM {PRE}VendorMaster 

                            WHERE nFId!='52' ORDER BY nVId ASC");

        if ($accheadGroup->num_rows() > 0)

        {

            $accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key=>$row)

            {

                $accheadGroupRow[$row->nVId] = $row->vName;

            }//end for each

        }//end if

        else

        {

            $accheadGroupRow[0] = NOVALUE;

        }//end else



        return ($accheadGroupRow);

    } //end function
	
	function getCustomer()

    {

        $accheadGroupRow = array();

        $accheadGroup    = $this->db->query("SELECT *

                        FROM {PRE}CustomerMaster ORDER BY nCId ASC");

        if ($accheadGroup->num_rows() > 0)

        {

            $accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key=>$row)

            {

                $accheadGroupRow[$row->nCId] = $row->vName;

            }//end for each

        }//end if

        else

        {

            $accheadGroupRow[0] = NOVALUE;

        }//end else



        return ($accheadGroupRow);

    } //end function
	
	function getActiveType()

    {

        $accheadGroupRow = array();

        $accheadGroup    = $this->db->query("SELECT *

                        FROM {PRE}VisaSetup 

                            WHERE nCategoryId='2' ORDER BY nVId ASC");

        if ($accheadGroup->num_rows() > 0)

        {

            //$accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key=>$row)

            {

                $accheadGroupRow[$row->nVId] = $row->vVisaItem;

            }//end for each

        }//end if

        else

        {

            $accheadGroupRow[0] = NOVALUE;

        }//end else



        return ($accheadGroupRow);

    } //end function
	
	function insertVisa()

    {

      $sales_dt = strtotime($this->input->post('txtDate')) == '' ?  date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('txtDate')));

        //insert into refund table

        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),

                      'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nUserId' =>$this->session->userdata('AbsAdmin'),

                      'vDocNumber' => $this->input->post('txtDocNumber'),'vIssuedBy' => $this->input->post('ddlissue'),
					'remarks' => $this->input->post('txtRemarks'),
                      'vStatus' => $this->input->post('ddlType'),'nVisaType' => $this->input->post('ddlvisatype'),
					  'vTicktManual'=> $this->input->post('txtvoc'),'vTotalNetAmt'=> $this->input->post('totNetAmt'),
					'vTotalSellAmt'=> $this->input->post('totSlsAmt'),'vTotalProfit'=>$this->input->post('totprofit'),
                      'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin')); 

        $this->db->insert('{PRE}VisaEntry', $data); 



        $id = $this->db->insert_id();



	
        

         $chkBox          = (@array_filter($this->input->post('chkbox')) != '')?$this->input->post('chkbox'):0;


        $txtPass           = (@array_filter($this->input->post('txtPass')) != '') ? $this->input->post('txtPass') : 0;

        $txtcontact       = (@array_filter($this->input->post('txtcontact')) != '') ? $this->input->post('txtcontact') : 0;
		
        $txtdob  	 = (@array_filter($this->input->post('txtdob')) != '')?$this->input->post('txtdob'):0;
		$txtdoi  	 = (@array_filter($this->input->post('txtdoi')) != '')?$this->input->post('txtdoi'):0;
		$txtdoe  	 = (@array_filter($this->input->post('txtdoe')) != '')?$this->input->post('txtdoe'):0;
        $txtpassport    = (@array_filter($this->input->post('txtpassport')) != '') ? $this->input->post('txtpassport') : 0;

        $txtnation     = (@array_filter($this->input->post('txtnation')) != '') ? $this->input->post('txtnation') : 0;

        
        $ddlgender            = (@array_filter($this->input->post('ddlgender')) != '') ? $this->input->post('ddlgender') : 0;

        $mstatus        = (@array_filter($this->input->post('mstatus')) != '') ? $this->input->post('mstatus') : 0;

       // $ddlAgent        = (@array_filter($this->input->post('ddlAgent')) != '') ? $this->input->post('ddlAgent') : 0;

        $ddlcustomer        = (@array_filter($this->input->post('ddlcustomer')) != '') ? $this->input->post('ddlcustomer') : 0;

        
        



        //insert into RefundRegister Details table

        foreach ($chkBox as $key => $item)

		{
			if($txtdob[$key] =='0000-00-00' || $txtdob[$key] == '' || $txtdob[$key] == 0 ){

				  $date1= '';

				}

				else

				{

				  $date1= date('Y-m-d',strtotime($txtdob[$key]));

				}
				if($txtdoi[$key] =='0000-00-00' || $txtdoi[$key] == '' || $txtdoi[$key] == 0 ){

				  $date2= '';

				}

				else

				{

				  $date2= date('Y-m-d',strtotime($txtdoi[$key]));

				}
				if($txtdoe[$key] =='0000-00-00' || $txtdoe[$key] == '' || $txtdoe[$key] == 0 ){

				  $date3= '';

				}

				else

				{

				  $date3= date('Y-m-d',strtotime($txtdoe[$key]));

				}

           

            $data3 = array('nVId' => $id, 'vPassName' => $txtPass[$key], 'vPhone' => $txtcontact[$key],

                            'vPassportNo' => $txtpassport[$key] , 'dDOB' => $date1, 'vNationality' => $txtnation[$key],

                            'dVisaIssueDate' => $date2, 'dVisaExpiryDate' => $date3,'vGender' => $ddlgender[$key],

                            'vMaritalStatus' => $mstatus[$key],'nAgentId' => 0,'nCustomerId' => $ddlcustomer[$key],

                            'vNetAmt' => 0,'vSellAmt' => 0);

            $this->db->insert('{PRE}VisaEntryPassDetails', $data3);

        }//end foreach
		
		
		
		 $chkbox2          = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;


        $txtslsAmt           = (@array_filter($this->input->post('txtslsAmt')) != '') ? $this->input->post('txtslsAmt') : 0;

        $txtNetAmt       = (@array_filter($this->input->post('txtnetAmt')) != '') ? $this->input->post('txtnetAmt') : 0;
		 $doc        = (@array_filter($this->input->post('doc')) != '') ? $this->input->post('doc') : 0;

        
        $ddlAgent        = (@array_filter($this->input->post('ddlAgent')) != '') ? $this->input->post('ddlAgent') : 0;

        $txtPofit        = (@array_filter($this->input->post('txtPofit')) != '') ? $this->input->post('txtPofit') : 0;

        
        



        //insert into RefundRegister Details table

        foreach ($chkbox2 as $key => $item)

		{
			

            $data4 = array('vid' => $id, 'slsamt' => $txtslsAmt[$key], 'netamt' => $txtNetAmt[$key],

                            'profit' => $txtPofit[$key] , 'acc' => $ddlAgent[$key],'doc' => $doc[$key] );

            $this->db->insert('{PRE}VisaEntryAmtDetails', $data4);

        }//end foreach
		
		
		

        if($this->input->post('btnGo') == "Save & Close"){

          $id = $this->saveToRefundClosing();

          $msgVal = 'add_suc';

        }

        else{

          $msgVal = 'add_suc';

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

            redirect(site_url("visa_entry/editVisa/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));

        }//end if

        if ($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != ''){

            redirect(site_url("visa_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));

        }//end if

        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '' && $this->input->post('btnGo') == "Save & Close"){

            redirect(site_url("visa_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));

        }//end if

    }//end function
	
	
	function updateVisa()

    {

      $sales_dt = strtotime($this->input->post('txtDate')) == '' ?  date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('txtDate')));

        //insert into refund table

        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),

                      'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nUserId' =>$this->session->userdata('AbsAdmin'),

                      'vDocNumber' => $this->input->post('txtDocNumber'),'vIssuedBy' => $this->input->post('ddlissue'),
						'remarks' => $this->input->post('txtRemarks'),
                      'vStatus' => $this->input->post('ddlType'),'nVisaType' => $this->input->post('ddlvisatype'),
					  'vTicktManual'=> $this->input->post('txtvoc'),'vTotalNetAmt'=> $this->input->post('totNetAmt'),
					'vTotalSellAmt'=> $this->input->post('totSlsAmt'),'vTotalProfit'=>$this->input->post('totprofit'),
                      'edate' => date('Y-m-d H:i:s'),'eid' =>$this->session->userdata('AbsAdmin')); 

       
		
		$this->db->where('nVId', $this->input->post('nId'));

        $this->db->update('{PRE}VisaEntry', $data);

        $id = $this->input->post('nId');


        
        


        $txtPass           = (@array_filter($this->input->post('txtPass')) != '') ? $this->input->post('txtPass') : 0;

        $txtcontact       = (@array_filter($this->input->post('txtcontact')) != '') ? $this->input->post('txtcontact') : 0;
		
		$txtdob  	 = (@array_filter($this->input->post('txtdob')) != '')?$this->input->post('txtdob'):0;
		
		$txtdoi  	 = (@array_filter($this->input->post('txtdoi')) != '')?$this->input->post('txtdoi'):0;
		$txtdoe  	 = (@array_filter($this->input->post('txtdoe')) != '')?$this->input->post('txtdoe'):0;
        
		
		$txtpassport    = (@array_filter($this->input->post('txtpassport')) != '') ? $this->input->post('txtpassport') : 0;

        $txtnation     = (@array_filter($this->input->post('txtnation')) != '') ? $this->input->post('txtnation') : 0;

        
        $ddlgender            = (@array_filter($this->input->post('ddlgender')) != '') ? $this->input->post('ddlgender') : 0;

        $mstatus        = (@array_filter($this->input->post('mstatus')) != '') ? $this->input->post('mstatus') : 0;

       // $ddlAgent        = (@array_filter($this->input->post('ddlAgent')) != '') ? $this->input->post('ddlAgent') : 0;

        $ddlcustomer        = (@array_filter($this->input->post('ddlcustomer')) != '') ? $this->input->post('ddlcustomer') : 0;
		 $chkAccID	    = $this->input->post('idArray');
        
        


        //insert into RefundRegister Details table

        foreach ($txtPass as $key => $item)

		{
			
			
					if($txtdob[$key] =='0000-00-00' || $txtdob[$key] == '' || $txtdob[$key] == 0 ){

				  $date1= '';

				}

				else

				{

				  $date1= date('Y-m-d',strtotime($txtdob[$key]));

				}
				if($txtdoi[$key] =='0000-00-00' || $txtdoi[$key] == '' || $txtdoi[$key] == 0 ){

				  $date2= '';

				}

				else

				{

				  $date2= date('Y-m-d',strtotime($txtdoi[$key]));

				}
				if($txtdoe[$key] =='0000-00-00' || $txtdoe[$key] == '' || $txtdoe[$key] == 0 ){

				  $date3= '';

				}

				else

				{

				  $date3= date('Y-m-d',strtotime($txtdoe[$key]));

				}

					  
		 if(intval($chkAccID[$key]) != 0){
			 
			 
			 
			 
			 
			  $data3 = array('nVId' => $id, 'vPassName' => $item, 'vPhone' => $txtcontact[$key],

                            'vPassportNo' => $txtpassport[$key] , 'dDOB' => $date1, 'vNationality' => $txtnation[$key],
                            'dVisaIssueDate' => $date2, 'dVisaExpiryDate' => $date3,'vGender' => $ddlgender[$key],

                            'vMaritalStatus' => $mstatus[$key],'nAgentId' => 0,'nCustomerId' => $ddlcustomer[$key],

                            'vNetAmt' => 0,'vSellAmt' => 0);

            
			  $this->db->where('nVPId',$chkAccID[$key]);

                $this->db->update('{PRE}VisaEntryPassDetails', $data3);
		 }else{

		 
		 

            $data3 = array('nVId' => $id, 'vPassName' => $item, 'vPhone' => $txtcontact[$key],

                            'vPassportNo' => $txtpassport[$key] , 'dDOB' => $date1, 'vNationality' => $txtnation[$key],

                            'dVisaIssueDate' => $date2, 'dVisaExpiryDate' => $date3,'vGender' => $ddlgender[$key],

                            'vMaritalStatus' => $mstatus[$key],'nAgentId' => 0,'nCustomerId' => $ddlcustomer[$key],

                            'vNetAmt' => 0,'vSellAmt' => 0);

            $this->db->insert('{PRE}VisaEntryPassDetails', $data3);
		 }

        }//end foreach
		
		$chkbox2          = (@array_filter($this->input->post('chkbox2')) != '')?$this->input->post('chkbox2'):0;


        $txtslsAmt           = (@array_filter($this->input->post('txtslsAmt')) != '') ? $this->input->post('txtslsAmt') : 0;

        $txtNetAmt       = (@array_filter($this->input->post('txtnetAmt')) != '') ? $this->input->post('txtnetAmt') : 0;
		
         $doc        = (@array_filter($this->input->post('doc')) != '') ? $this->input->post('doc') : 0;

        $ddlAgent1        = (@array_filter($this->input->post('ddlAgent')) != '') ? $this->input->post('ddlAgent') : 0;

        $txtPofit        = (@array_filter($this->input->post('txtPofit')) != '') ? $this->input->post('txtPofit') : 0;
		$chkAccID	    = $this->input->post('idAccArray');
        
		
		foreach ($chkbox2 as $key => $item)

		{
			
			
			var_dump($ddlAgent[$key]);	

					  
		 if(intval($chkAccID[$key]) != 0){
			 
			 
			 
			 
			 
			 $data4 = array('vid' => $id, 'slsamt' => $txtslsAmt[$key], 'netamt' => $txtNetAmt[$key],

                            'acc' => $ddlAgent1[$key] , 'profit' => $txtPofit[$key],'doc' => $doc[$key]);

            
			  $this->db->where('id',$chkAccID[$key]);

                $this->db->update('{PRE}VisaEntryAmtDetails', $data4);
		 }else{

		 
		 

            $data4 = array('vid' => $id, 'slsamt' => $txtslsAmt[$key], 'netamt' => $txtNetAmt[$key],

                            'profit' => $txtPofit[$key] , 'acc' => $ddlAgent1[$key],'doc' => $doc[$key]);

            $this->db->insert('{PRE}VisaEntryAmtDetails', $data4);
		 }

        }//end foreach
        

		
		
		

        if($this->input->post('btnGo') == "UPDATE ENTRY"){

         

          $msgVal = 'edit_suc';

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



        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '' && $this->input->post('btnGo') != "UPDATE ENTRY")

		{

            redirect(site_url("visa_entry/editVisa/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));

        }//end if

        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != ''){

            redirect(site_url("visa_entry/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));

        }//end if

       

    }//end function
	
	function visatype()

    {

        $accheadGroupRow = array();

        $accheadGroup    = $this->db->query("SELECT *

                        FROM {PRE}VisaSetup 

                            WHERE nCategoryId='1' ORDER BY nVId ASC");

        if ($accheadGroup->num_rows() > 0)

        {

            $accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key=>$row)

            {

                $accheadGroupRow[$row->nVId] = $row->vVisaItem;

            }//end for each

        }//end if

        else

        {

            $accheadGroupRow[0] = NOVALUE;

        }//end else



        return ($accheadGroupRow);

    } //end function
	
	function getCorresVisa()

	{

		$fetchTitle = $this->db->query("SELECT * FROM {PRE}VisaEntry WHERE nVId = '".$this->catId."'");

		$showVal	= $fetchTitle->row();

		return $showVal;

	}//end function 


}//end class
?>
