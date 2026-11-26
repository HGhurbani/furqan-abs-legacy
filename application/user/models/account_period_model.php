<?php
class Account_period_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getAllAccountperiods()
	{		
		//active groups
		$adminGroupRow     		= array();
		$sqlAccountPeriodsQuery = "SELECT *	FROM {PRE}AccountPeriod	ORDER BY dFDate ASC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlAccountPeriodsQuery;	
									
		$adminGroupList = $this->db->query($sqlAccountPeriodsQuery);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountAccountperiods()
	{		
		$tabCount = $this->db->query("SELECT *	FROM {PRE}AccountPeriod	ORDER BY dFDate ASC");
		return ($tabCount->num_rows());
	}//end function

    function getFilterList($extraCheck)
    {
        $adminGroupRow  			  = array();
        $sqlAccountPeriodsFilterQuery = "SELECT * FROM {PRE}AccountPeriod ".$extraCheck." ORDER BY nId DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
        session_start();
        $_SESSION['sess_query'] 	  = $sqlAccountPeriodsFilterQuery;

        $adminGroupList = $this->db->query($sqlAccountPeriodsFilterQuery);
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
        $tabCount = $this->db->query("SELECT * FROM {PRE}AccountPeriod  ".$extraCheck." ORDER BY nId DESC");
        return ($tabCount->num_rows());
    }//end function
	
	function getCorresAccperiod()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}AccountPeriod WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 

  function insertAccperiod()
  {
    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearRef' => $this->input->post('txtYearref'),
        'dFDate' => $this->input->post('txtFDate'),
        'dTDate' => $this->input->post('txtTDate'),'vStatus' => $this->input->post('ddlStatus'));
    $this->db->insert('{PRE}AccountPeriod', $data);

    $id   = $this->db->insert_id();

    $this->txtStartdate     = ($this->input->post('txtStartdate') != '')?$this->input->post('txtStartdate'):0;
    $this->txtEnddate       = ($this->input->post('txtEnddate') != '')?$this->input->post('txtEnddate'):0;
    $this->ddlSelectStatus  = ($this->input->post('ddlSelectStatus') != '')?$this->input->post('ddlSelectStatus'):0;

    foreach($this->txtStartdate as $key => $item){
      $data   = array('nAcId' => $id,'dSDate' => $this->txtStartdate[$key],'dEDate' => $this->txtEnddate[$key] ,'vActive' => $this->ddlSelectStatus[$key]);
      $this->db->insert('{PRE}AccountPeriodDetails', $data);
    } //end foreach

  }//end function
	

	function insertAccperiod_old()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearRef' => $this->input->post('txtYearref'),
                        'dFDate' => $this->input->post('txtFDate'),
		                'dTDate' => $this->input->post('txtTDate'),'vStatus' => $this->input->post('ddlStatus'));
		$this->db->insert('{PRE}AccountPeriod', $data); 
		
		$id		= $this->db->insert_id();
		
		$this->txtYearref      	= $this->input->post('txtYearref');
		$this->txtFDate         = $this->input->post('txtFDate');
		$this->txtTDate      	= $this->input->post('txtTDate');
		$this->ddlStatus      	= $this->input->post('ddlStatus');
		

		$selectFDate			= @split('-',$this->txtFDate);
		$varYear				= $selectFDate['0'];
		$varMonth				= $selectFDate['1'];
		$varDate				= $selectFDate['2'];
		
		$selectTDate			= @split('-',$this->txtTDate);
		$varLastYear			= $selectTDate['0'];
		$varLastMonth			= $selectTDate['1'];
		$varLastDate			= $selectTDate['2'];
		
		switch(substr($varMonth,0,1))
		{
				case "0":
					$varMonth = substr($varMonth,1,1);
				break;
							
				case "1":
					$varMonth = $varMonth;
				break;
		}//end switch
		
		switch(substr($varLastMonth,0,1))
		{
				case "0":
					$varLastMonth = substr($varLastMonth,1,1);
				break;
							
				case "1":
					$varLastMonth = $varLastMonth;
				break;
		}//end switch

		switch($this->ddlStatus)
		{
			case "A" :
				$status			= 'Active';
			break;
			
			case "O" :
				$status			= 'Open';
			break;

			case "C" :
				$status			= 'Closed';
			break;

		}//end switch

		$chkCount = 0;
		  
	   	for($i=$varMonth;$i<=$varLastMonth;$i++)
		{
			switch($chkCount)
			{
				case "0":
					$varDate = $varDate;
				break;
				
				default:
					$varDate = 1;
				break;
			}//end switch
			
			
			//calcuate total days for each month
			$noTDays = cal_days_in_month(CAL_GREGORIAN, $i, $varLastYear);
			
			//if i is equal to last month date generate date 
			if($i == $varLastMonth)
				$noTDays = $varLastDate;

			$txtFrom     		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$varDate));
			$txtTo       		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$noTDays));
			
			$data 	= array('nAcId' => $id,'dSDate' => $txtFrom,'dEDate' => $txtTo,'vActive' => $this->input->post('ddlStatus'));
			$this->db->insert('{PRE}AccountPeriodDetails', $data);

			$chkCount++;
		}//end for loop


		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("account_period/editAccountperiod/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class

  function updateAccperiod()
  {
    $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearRef' => $this->input->post('txtYearref'),
        'dFDate' => $this->input->post('txtFDate'),'dTDate' => $this->input->post('txtTDate'),
        'vStatus' => $this->input->post('ddlStatus'));
    $this->db->where('nId',$this->input->post('nId'));
    $this->db->update('{PRE}AccountPeriod', $data);

    $id   = $this->input->post('nId');
    $this->db->delete('{PRE}AccountPeriodDetails', array('nAcId' => $id));

    $this->txtStartdate     = ($this->input->post('txtStartdate') != '')?$this->input->post('txtStartdate'):0;
    $this->txtEnddate       = ($this->input->post('txtEnddate') != '')?$this->input->post('txtEnddate'):0;
    $this->ddlSelectStatus  = ($this->input->post('ddlSelectStatus') != '')?$this->input->post('ddlSelectStatus'):0;

    foreach($this->txtStartdate as $key => $item){
      $data   = array('nAcId' => $id,'dSDate' => $this->txtStartdate[$key],'dEDate' => $this->txtEnddate[$key] ,'vActive' => $this->ddlSelectStatus[$key]);
      $this->db->insert('{PRE}AccountPeriodDetails', $data);
    } //end foreach

  }//end function
	
	function updateAccperiod_old()
	{
        $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearRef' => $this->input->post('txtYearref'),
                        'dFDate' => $this->input->post('txtFDate'),'dTDate' => $this->input->post('txtTDate'),
                        'vStatus' => $this->input->post('ddlStatus'));
        $this->db->where('nId',$this->input->post('nId'));
        $this->db->update('{PRE}AccountPeriod', $data);

        $id		= $this->input->post('nId');
        $this->db->delete('{PRE}AccountPeriodDetails', array('nAcId' => $id));

        $this->txtYearref      	= $this->input->post('txtYearref');
        $this->txtFDate         = $this->input->post('txtFDate');
        $this->txtTDate      	= $this->input->post('txtTDate');
        $this->ddlStatus      	= $this->input->post('ddlStatus');

        $selectFDate			= @split('-',$this->txtFDate);
        $varYear				= $selectFDate['0'];
        $varMonth				= $selectFDate['1'];
        $varDate				= $selectFDate['2'];

        $selectTDate			= @split('-',$this->txtTDate);
        $varLastYear			= $selectTDate['0'];
        $varLastMonth			= $selectTDate['1'];
        $varLastDate			= $selectTDate['2'];

        switch(substr($varMonth,0,1))
        {
            case "0":
                $varMonth = substr($varMonth,1,1);
                break;

            case "1":
                $varMonth = $varMonth;
                break;
        }//end switch

        switch(substr($varLastMonth,0,1))
        {
            case "0":
                $varLastMonth = substr($varLastMonth,1,1);
                break;

            case "1":
                $varLastMonth = $varLastMonth;
                break;
        }//end switch

        switch($this->ddlStatus)
        {
            case "A" :
                $status			= 'Active';
                break;

            case "O" :
                $status			= 'Open';
                break;

            case "C" :
                $status			= 'Closed';
                break;

        }//end switch

        $chkCount = 0;

        for($i=$varMonth;$i<=$varLastMonth;$i++)
        {
            switch($chkCount)
            {
                case "0":
                    $varDate = $varDate;
                    break;

                default:
                    $varDate = 1;
                    break;
            }//end switch


            //calcuate total days for each month
            $noTDays = cal_days_in_month(CAL_GREGORIAN, $i, $varLastYear);

            //if i is equal to last month date generate date
            if($i == $varLastMonth)
                $noTDays = $varLastDate;

            $txtFrom     		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$varDate));
            $txtTo       		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$noTDays));

            $data 	= array('nAcId' => $id,'dSDate' => $txtFrom,'dEDate' => $txtTo,'vActive' => $this->input->post('ddlStatus'));
            $this->db->insert('{PRE}AccountPeriodDetails', $data);

            $chkCount++;
        }//end for loop

		$msgVal = 'edit_suc';
		redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function
	
	function deleteAccperiod()
	{
        $this->db->delete('{PRE}AccountPeriodDetails', array('nAcId' => $this->uri->segment(4)));
		$this->db->delete('{PRE}AccountPeriod', array('nId' => $this->uri->segment(4)));
		redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/del_suc'));
	}//end function 
	
	function deleteAllAccperiod()
	{
		switch($this->input->post('ddlAction'))
		{
			case "open":
				foreach($this->input->post('del') as $val)
				{
                    $data = array('vStatus' => 'O');
                    $this->db->where('nId',$val);
                    $this->db->update('{PRE}AccountPeriod', $data);

                    $data2 = array('vActive' => 'O');
                    $this->db->where('nAcId',$val);
                    $this->db->update('{PRE}AccountPeriodDetails', $data2);
				}//end foreach
				redirect(site_url("account_period/showlist/".$this->input->post('nCId').'/edit_suc'));
			break;
			
			case "closed":
				foreach($this->input->post('del') as $val)
				{
					$data = array('vStatus' => 'C');
                    $this->db->where('nId',$val);
                    $this->db->update('{PRE}AccountPeriod', $data);

                    $data2 = array('vActive' => 'C');
                    $this->db->where('nAcId',$val);
                    $this->db->update('{PRE}AccountPeriodDetails', $data2);
				}//end foreach
				redirect(site_url("account_period/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
                    $this->db->delete('{PRE}AccountPeriodDetails', array('nAcId' => $val));
                    $this->db->delete('{PRE}AccountPeriod', array('nId' => $val));
				}//end foreach
				redirect(site_url("account_period/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getDropStatus()
	{
		$typeGroupRow    = array("0" => SELECT, "O" => "Open", "C" => "Closed");
		return ($typeGroupRow);
	}//end function 
	
	function getDropAllStatus()
	{
		$typeGroupRow    = array("0" => ALL, "O" => "Open", "C" => "Closed");
		return ($typeGroupRow);
	}//end function 

  function accPeriodGeneration()
  {

    $this->txtYearref      	= $this->input->post('txtYearref');
    $this->txtFDate         = $this->input->post('txtFDate');
    $this->txtTDate       	= $this->input->post('txtTDate');
    $this->ddlStatus      	= $this->input->post('ddlStatus');
    $this->nAcId            = $this->input->post('nAcId');
    $chkCount = 0;
    $dSDate = array();
    if($this->operation == 'e'){
      $fetchAccPeriodDet = $this->db->query("SELECT * FROM {PRE}AccountPeriodDetails WHERE nAcId = ". $this->nAcId . " ORDER BY dSDate");
      if($fetchAccPeriodDet->num_rows() > 0 ){
        foreach($fetchAccPeriodDet->result() as $key => $row){
          $dSDate[] = $row->dSDate;
          $dEDate[] = $row->dEDate;
          $vActive[] = $row->vActive;
        }// end foreach
      } //end if 
      $checkAccPeriodDate =  $this->db->query("SELECT * FROM {PRE}AccountPeriod  WHERE nId =  ". $this->nAcId . " AND dFDate ='".$this->txtFDate."'
          AND dTDate = '".$this->txtTDate ."' ");
      if($checkAccPeriodDate->num_rows() == 0) $chkDate = 0; 
      else $chkDate = 1;
    } // end if
    if($this->operation == 'a' || $chkDate == 0){

      $selectFDate			= @split('-',$this->txtFDate);
      $varYear				= $selectFDate['0'];
      $varMonth				= $selectFDate['1'];
      $varDate				= $selectFDate['2'];

      $selectTDate			= @split('-',$this->txtTDate);
      $varLastYear			= $selectTDate['0'];
      $varLastMonth			= $selectTDate['1'];
      $varLastDate			= $selectTDate['2'];

      switch(substr($varMonth,0,1))
      {
        case "0":
          $varMonth = substr($varMonth,1,1);
        break;

        case "1":
          $varMonth = $varMonth;
        break;
      }//end switch

      switch(substr($varLastMonth,0,1))
      {
        case "0":
          $varLastMonth = substr($varLastMonth,1,1);
        break;

        case "1":
          $varLastMonth = $varLastMonth;
        break;
      }//end switch

/*      switch($this->ddlStatus)
      {
        case "A" :
          $status			= 'Active';
        break;

        case "O" :
          $status			= 'Open';
        break;

        case "C" :
          $status			= 'Closed';
        break;

      }//end switch
*/
      echo '<thead>
        <th align="left">'.ACCSTARTDATE.'</th>
        <th align="left">'.ACCENDDATE.'</th>
        <th align="left">'.ACCSTATUS.'</th>
        </thead>';

      for($i=$varMonth;$i<=$varLastMonth;$i++)
      {
        switch($chkCount)
        {
          case "0":
            $varDate = $varDate;
          break;

          default:
          $varDate = 1;
          break;
        }//end switch

        //calcuate total days for each month
        $noTDays = cal_days_in_month(CAL_GREGORIAN, $i, $varLastYear);

        //if i is equal to last month date generate date 
        if($i == $varLastMonth)
          $noTDays = $varLastDate;

        $txtFrom     		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$varDate));
        $txtTo       		= date('Y-m-d',strtotime($varYear.'-'.$i.'-'.$noTDays));

        if(in_array($txtFrom,$dSDate)){
           $k = array_search($txtFrom,$dSDate);
           $monthStatus = $vActive[$k];
        }
        else $monthStatus = $this->ddlStatus;

        $oselect = $cselect = "";
        if($monthStatus == 'O') $oselect = " selected ";
        else $cselect = " selected ";
        echo "<tr>
          <td><input name='txtStartdate[]' id='txtStartdate".$chkCount."' value='".$txtFrom."' readonly='readonly' /></td>
          <td><input name='txtEnddate[]' id='txtStartdate".$chkCount."' value='".$txtTo."' readonly='readonly'/></td>
          <td><select name='ddlSelectStatus[]' id ='ddlSelectStatus".$chkCount."' class='selectStatus'>
          <option value='O'  ". $oselect ."> OPEN </option>
          <option value = 'C' " . $cselect . "> CLOSED </option>
          </select> </td>
          </tr>";
        $chkCount++;
      }//end for loop
    } //end if
    else { 
      echo '<thead>
        <th align="left">'.ACCSTARTDATE.'</th>
        <th align="left">'.ACCENDDATE.'</th>
        <th align="left">'.ACCSTATUS.'</th>
        </thead>';
      foreach($dSDate as $key => $item)
      {
        $oselect = $cselect = "";
        if($vActive[$key]  == 'O') $oselect = " selected ";
        else $cselect = " selected ";
        echo "<tr>
          <td><input name='txtStartdate[]' id='txtStartdate".$chkCount."' value='".$dSDate[$key]."' readonly='readonly' /></td>
          <td><input name='txtEnddate[]' id='txtStartdate".$chkCount."' value='".$dEDate[$key]."' readonly='readonly'/></td>
          <td><select name='ddlSelectStatus[]' id ='ddlSelectStatus".$chkCount."' class='selectStatus' >
          <option value='O'  ". $oselect ."> OPEN </option>
          <option value = 'C' " . $cselect . "> CLOSED </option>
          </select> </td>
          </tr>";

        $chkCount++;

      }//end foreach
    } //end if 
  }//end function

	function getSelectStatus()
	{
		$typeGroupRow = array("O" => "Open","C" => "Closed");
		return ($typeGroupRow);
	}//end function 

	function changeStatusOpen($accId)
	{
		$data = array('vStatus' => 'C');
		$this->db->where('nId',$accId);
		$this->db->update('{PRE}AccountPeriod', $data);

        $data2 = array('vActive' => 'C');
		$this->db->where('nAcId',$accId);
		$this->db->update('{PRE}AccountPeriodDetails', $data2);

		redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/edit_suc'));
	}//end function

	function changeStatusClosed($accId)
	{
		$data = array('vStatus' => 'O');
		$this->db->where('nId',$accId);
		$this->db->update('{PRE}AccountPeriod', $data);

        $data2 = array('vActive' => 'O');
        $this->db->where('nAcId',$accId);
        $this->db->update('{PRE}AccountPeriodDetails', $data2);

		redirect(site_url("account_period/showlist/".$this->uri->segment(3).'/edit_suc'));
	}//end function
	
}//end class
?>
