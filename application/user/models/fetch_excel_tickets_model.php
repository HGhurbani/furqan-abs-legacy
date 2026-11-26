<?php
// include("./static/gds/ticket_result.php");
/*foreach (glob("./static/gds/tickets/*.php") as $filename)
  {
  include($filename);
  }*/
class Fetch_excel_tickets_model extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function insertTickets()
  {

    require_once 'static/classes/Classes/PHPExcel/IOFactory.php';

    $excelFile = "static/xlTickets/TicketingSummary.xlsx";
    if(trim($_FILES['txtFile']['tmp_name'])!=''){

      $excelFile = trim($_FILES['txtFile']['tmp_name']);
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load($excelFile);

      //Itrating through all the sheets in the excel workbook and storing the array data
      foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
      }

      $tktNo_index = $gdsPnr_index = $passName_index = $dDate_index = -1;
      $status_index = -1;
      $fcFareCash_index = $tax1_index = -1;
      $commission_index1 = -1;
//      $tax2_index1 = $netFare_index = $agentId_index = -1;

      $message = '';

      foreach($arrayData as $key1 => $item1){
        foreach($item1 as $key2 => $item2){
          foreach($item2 as $key=>$item){
            switch($item){
              case "Ticket#":
                $tktNo_index = $key;
              break;

              case "PNR":
                $gdsPnr_index = $key;
              break;

              case "Passenger Name":
                $passName_index = $key;
              break;

              case "Date of Issue Local":
                $dDate_index = $key;
              break;

              case "Status":
                $status_index = $key;
              break;

              case "Face-Value":
                $fcFareCash_index = $key;
              break;

              case "Tax":
                $tax1_index = $key;
              break;


              case "Comm.":
                $commission_index1 = $key;
              break;

/*              case "Net Fare":
                $netFare_index = $key;
              break;
*/
              case "Agent ID":
                $agentId_index = $key;
              break;
            }
          }
          break;
        }
      }

      if($tktNo_index == -1 ){
        $message .= "Error.. Ticket Number column is missing";
        return;
      }

      $cnt = 0;
      $begin_insert = 0;
      foreach($arrayData as $key1 => $item1){
        foreach($item1 as $key => $item){
          if( $begin_insert == 0){
            $begin_insert = 1;
            continue;
          }
          if( $begin_insert == 1){
            $begin_insert = 2;
            continue;
          }

          // condition for end of file
          if($item[$tktNo_index] == '' ){
            break;
          }

          $tktNo_split = explode(' ',$item[$tktNo_index]);
          $tktNo_split1 = explode('/',$tktNo_split[1]);
          $tktNo = $tktNo_split1[0];
          if($tktNo == NULL || $tktNo == ''){
            continue;
          }

          /*          $TBValue = $tktNo_split1[1];
                      $tValue = $bValue = 0;
                      if($TBValue == '1') $tValue = $TBValue;
                      elseif($TBValue == '2') $bValue = $TBValue;
           */

          $airCode = $tktNo_split[0];
          $airId = $this->common_model->get_field_name('Airline','nArId', " WHERE vAirlineNo = '" . $airCode . "'");

          $gdsPnr = ($gdsPnr_index == -1 ) ? '': $item[$gdsPnr_index];
          $passName = ( $passName_index == -1)? '' :  $item[$passName_index];
          $dDate = ( $dDate_index == -1)? NULL : $item[$dDate_index];
          $status = ( $status_index == -1)? 'Void' :  $item[$status_index];
          $fcFareCash = ( $fcFareCash_index == -1)? 0 :  $item[$fcFareCash_index];
          $tax1 = ( $tax1_index == -1)? 0 :  $item[$tax1_index];
          $commission_1 = ( $commission_index1 == -1)? 0 :  $item[$commission_index1];
//          $netFare = ( $netFare_index == -1)? 0 :  $item[$netFare_index];
          $agentId = ( $agentId_index == -1)? '' :  $item[$agentId_index];

          //// NULL CHECK
          $gdsPnr = ( $gdsPnr == NULL)? '' : $gdsPnr ;
          $passName = ($passName == NULL) ? '' : $passName; 

          $dDate_split = $dDate_split2 = array();
          $dDate_split = explode(' ', $dDate );
          $dDate_split2 = explode('/', $dDate_split[0] );
          if($dDate_split2[1] != ''){
            if($dDate_split2[0] > 12)
              $dDate_split3 = $dDate_split2[0]. '-' . $dDate_split2[1].'-' . $dDate_split2[2]; 
            else
              $dDate_split3 = $dDate_split2[2]. '-' . $dDate_split2[1].'-' . $dDate_split2[0]; 
          }
          else
            $dDate_split3 = $dDate_split[0];
          $dDate = ($dDate == NULL) ?  date('Y-m-d') :date('Y-m-d', strtotime( $dDate_split3 ));

          $status = ($status == NULL) ? '' : $status;
          $fcFareCash = ($fcFareCash == NULL) ? '' : $fcFareCash;
          $tax1 = ($tax1 == NULL) ? '' : $tax1;
          $commission_1 = ($commission_1 == NULL) ? '' : $commission_1;
//          $netFare = ($netFare == NULL) ? '' : $netFare;
          $airCode = ($airCode == NULL) ? '' : $airCode;
          $airId = ($airId == NULL) ? '' : $airId;
          if($airId == ''){
            // insert into aircode
            $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),'vAirlineNo' => '','vAlpha' => $airCode,
                'vTitle' => '', 'vType' =>'',  
                'nAR' => '', 'nSubAr' =>'',  
                'nAp' => '', 'nSubAp' =>'',  
                'nTkt' => '', 'nXo' =>'',  
                'nGsa' => '', 'vName' =>'',  
                'vDesignation' => '', 'vMobile' =>'',  
                'vPhone' => '', 'vEmail' =>'',  
                'vAddress' => '', 'vActive' => 1);
            $this->db->insert('{PRE}Airline', $data);
            $airId          = $this->db->insert_id();
          }


          $agentId = ($agentId == NULL) ? '' : $agentId;
          if($agentId != '') {
            $custAcCode =  substr($agentId,0,-2);
            $custAc =  $this->common_model->get_field_name('CustomerMaster','nCId', " WHERE vCode = '" . $custAcCode. "'");
            if($custAc == '')
              $custAc = 0;

            $agentId = substr($agentId,-2);
            $signId_id =  $this->common_model->get_field_name('EmployeeMaster','nEId'," WHERE vCode = '".$agentId. "'");
            if($signId_id == '' || $signId_id == NULL){
              $getDivison = 1; 
              $getLocation = 1;
              $issued_data = array('vCode' => $agentId,
                  'vLocations' => $getLocation,
                  'vLocation' => $getLocation );
              $this->db->insert('{PRE}EmployeeMaster', $issued_data);
              $signId_id =  $this->db->insert_id();
            } // end if ($signId_id == ''
            $bookedId_id = $signId_id;
            $getLocation=  $this->common_model->get_field_name('EmployeeMaster','vLocation'," WHERE vCode = '".$agentId. "'");
            $getDivison=  $this->common_model->get_field_name('Location','nDivision'," WHERE nId= " .$getLocation. " ");

          } // end if($agentId != '')
          else{
            $bookedId_id = $signId_id = $custAc = 0;
            $getDivison = $getLocation = 1;
          }

          $airType = $this->common_model->get_field_name('Airline','vType', " WHERE nArId= '" . $airId . "'");
          if($airType == 'NB'){
            $accHead = $this->common_model->get_field_name('Airline','nAp', " WHERE nArId= '" . $airId . "'");
            $acc  = $this->common_model->get_field_name('Airline','nSubAp', " WHERE nArId= '" . $airId . "'"); 
            $gds = 4;
          }
          else{
            $accHead = 32;
            $acc = 1;
            $gds = 0;
          }

          switch($status){
            case "ETKT":
              $status = "N";
            break;
            case "REFUND":
              $status = "Refund";
            break;
            case "Void":
              case "VOID":
              $status = "V";
            break;
            case "ReIssue/Reval":
              $status = "R";
            break;
            case "Conjunction":
              $status = "C";
            break;
            default:
            $status = "N";
          }


          if($TicketBooking[$tktNo]['vTicktManual'] == $tktNo){
            $tax1_sys = $TicketFareDetails[$tktNo]['vTax1'];
            $totalTax = $TicketFareDetails[$tktNo]['vTaxTotal'];
            $base_fair = $TicketFareDetails[$tktNo]['vFaceValue'];
            $commAmt = $TicketFareDetails[$tktNo]['nCommAmt'];
          }
          else{
            $tax1_sys =0;
            $totalTax = 0;
            $base_fair = 0;
            $commAmt = 0;
          }

          //hardcode ids
          $currId = $this->common_model->getCompanyCurr();
          $rate = 1;
          $tax2 = 0;
          $tax3 = 0;
          $tax1_sys += $tax1;
          $totalTax += $tax1 + $tax2 + $tax3;
          $fc_fair = 0;
          $pass_contactno = 0;
          $base_fair += $fcFareCash;
          $commAmt += $commission_1; 
          $nCommPercent = 0;
          $orCommision = 0;
          $netFare = $base_fair + $totalTax - $commAmt - $orCommision;

         $TicketTBValue[$tktNo] =  array('T' => $tValue, 'B' => $bValue);
          $TicketBooking[$tktNo]   = array('nUserId' => $this->session->userdata('AbsAdmin'),
              'nDivision' => $getDivison,
              'nLocation' => $getLocation,
              'dDate' => $dDate,
              'nAirline' => $airId,
              'nGDS' => $gds,
              'vType' => $status,
              'vIssuedBy' => $signId_id,
              'vBookedBy' => $bookedId_id,
              'vPassenger' => $passName,
              'vGdspnr' => $gdsPnr ,'vAirpnr' => $gdsPnr,
              'nCurrency' => $currId,
              'nRate' => $rate,
              'vConjStatus' => 'Yes',
              'vDocNumber' => $this->getAutomaticDocNumber2(),
              'vTicktManual' => $tktNo);

          //insert into Ticket Fare Details table
          $TicketFareDetails[$tktNo] = array('nTicketId' => $id ,'vFare' => 'N',
              'vTax1' => $tax1_sys,
              'vTax2' => $tax2,
              'vTax3' => $tax3,
              'vTaxTotal' => $totalTax,
              'nCommAmt' => $commAmt,
              'nComm'=> $nCommPercent,
              'nORCAmt' => $orCommision,
              'nNetVendor' => $netFare,
              'nAcc' => $acc,
              'nAccHead' => $accHead,
              'vFCFare' => $fc_fair,
              'vPassengerContact' => $pass_contactno,
              'vFaceValue' => $base_fair);

          //insert into Ticket Billing Details table
          $TicketBillingDetails[$tktNo] = array('nTicketId' => $id ,
              'nCustomerAc' => $custAc,
              'vSalesAmt' => '0',
              'vCollected' => $netFare);
        }// forloop 2
      } //outermoset foreach
      if(!empty($TicketBooking))
      foreach($TicketBooking as $key => $item){
        $ticketId    =  $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$TicketBooking[$key]['vTicktManual']. "'" );
        if($item['vType'] == 'V'){
          // set fare as 0
          $tax1 = $tax2 = $tax3 = $totalTax = $commAmt = 0;
          $Comm = $ORCAmt = 0;
          $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;

          if($ticketId == ''){
            $this->db->insert('{PRE}TicketBooking', $TicketBooking[$key]);
            $id     = $this->db->insert_id();

            $TicketFareDetails[$key]['nTicketId'] = $id;
            $TicketFareDetails[$key]['vTax1']= $tax1;
            $TicketFareDetails[$key]['vTax2'] = $tax2;
            $TicketFareDetails[$key]['vTax3'] = $tax3;
            $TicketFareDetails[$key]['vTaxTotal'] = $totalTax;
            $TicketFareDetails[$key]['nCommAmt'] = $commAmt;
            $TicketFareDetails[$key]['nComm'] = $Comm;
            $TicketFareDetails[$key]['nORCAmt'] = $ORCAmt;
            $TicketFareDetails[$key]['nNetVendor'] = $NetAmount;
            $TicketFareDetails[$key]['vFCFare'] = $fc_fair;
            $TicketFareDetails[$key]['vPassengerContact'] = $pass_contactno;
            $TicketFareDetails[$key]['vFaceValue'] = $base_fair;

            $this->db->insert('{PRE}TicketFareDetails', $TicketFareDetails[$key]);

            $TicketBillingDetails[$key]['nTicketId'] = $id;
            $this->db->insert('{PRE}TicketBillingDetails', $TicketBillingDetails[$key]);

            //echo " Void " .($TicketBooking[$key]['vTicktManual']) . "<br><br>";
            $cnt++;
          }
          else{
            $db_tkt_status =  $this->common_model->get_field_name('TicketBooking','vType'," WHERE nTId = '".$ticketId. "'" );
            if($db_tkt_status != 'V'){
              $closedId    =  $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vTktNumber= '".$ticketId. "' AND vType='Tkt'" );
              if($closedId != '') { $message .= "Ticket#" . $ticket_no . " is already closed. Changing status to VOID...    ";}
              // set status of ticket as void
              $data   = array( 'vType' => $item['vType']);
              $this->db->where('nTId',$ticketId);
              $this->db->update('{PRE}TicketBooking', $data);


              $pass_contactno = '';
              //insert into Ticket Fare Details table
              $data2  = array('vTax1' => $tax1,
                  'vTax2' => $tax2,
                  'vTax3' => $tax3,
                  'vTaxTotal' => $totalTax,
                  'nNetVendor' => $NetAmount,
                  'nCommAmt' => $commAmt,
                  'nComm' => $Comm,
                  'nORCAmt' => $ORCAmt,
                  'nAcc' => $TicketFareDetails[$key]['nAcc'],
                  'nAccHead' =>  $TicketFareDetails[$key]['nAccHead'],
                  'vFCFare' => $fc_fair,
                  'vPassengerContact' => $pass_contactno,
                  'vFaceValue' => $base_fair);
              $this->db->where('nTicketId',$ticketId);
              $this->db->update('{PRE}TicketFareDetails', $data2);

              //echo " Void " .($TicketBooking[$key]['vTicktManual']) . "<br><br>";
              $cnt++;
            }
          }//end else
        } // endif V
        else if($item['vType'] == 'N'){
          if($ticketId == ''){
            $this->db->insert('{PRE}TicketBooking', $TicketBooking[$key]);
            $id     = $this->db->insert_id();

            $TicketFareDetails[$key]['nTicketId'] = $id;
            $this->db->insert('{PRE}TicketFareDetails', $TicketFareDetails[$key]);

            $TicketBillingDetails[$key]['nTicketId'] = $id;
            $this->db->insert('{PRE}TicketBillingDetails', $TicketBillingDetails[$key]);

            ///echo " Inserted " .($TicketBooking[$key]['vTicktManual']) . "<br><br>";
            $cnt++;
          }

        } //endif N
        else if ($item['vType'] == 'Refund'){
            if( $ticketId == ''){
              //echo "Ticket#" . $TicketBooking[$key]['vTicktManual']. " is an old ticket and not present in the system. Cannot refund it...     " ;
              continue;
            }

            $RefundId    =  $this->common_model->get_field_name('RefundRegisterDetails','nRDId'," WHERE nTktNum = ".$ticketId." AND vTktType = 'Tkt' ");
            if($RefundId == '')
            {

              // First retrieve from sales closing
              $query_s = "SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount,tb.*,tb.nGds as tkt_Gds,tfd.nAcc,
                sc.dDate as date
                  FROM {PRE}SalesClosing sc LEFT JOIN
                  {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
              LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
                LEFT JOIN {PRE}TicketBooking tb ON tb.nTId = scd.vTktNumber
                LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId
                WHERE scd.vType = 'Tkt' AND scd.vTktNumber = '" .$ticketId ."'
                GROUP BY sc.nSCId";
              //          echo $query_s;
              $salesClosingValues = $this->db->query($query_s);

              if ($salesClosingValues->num_rows() > 0)
              {
                foreach ($salesClosingValues->result() as $element =>$row)
                {

                  //insert into refund table
                  $data = array('nDivision' => $row->nDivision,
                      'nLocation' => $row->vLocation,
                      'dDate' =>  $TicketBooking[$key]['dDate'],
                      'nTransaction' =>0,
                      'vDocNumber' => $this->getDocNum2(),
                      'nGDS' => $row->tkt_Gds,
                      'vType' => 'R',
                      'vPassenger' =>  $row->vPassenger,
                      //  'vSector' =>
                      'nAccount' => $row->nAcc,
                      /////////////////////////////// change
                      'nUserId' => 1,
                      //  'vSegment' =>
                      //  'vDestination' =>
                      'vAcAPID' =>  $row->nDrAccount ."-". $row->vTable,
                      'nCurrency' => $row->nCurrency,
                      'nRate' => $row->nRate,
                      'vSalesDet' => $row->date,
//                      'vRefundDet' => $TicketBooking[$key]['dDate'],
                      'vMode' => 'Cr',
                      'vActive' => '1'
                        );
                  //echo "Insert RefundRegister <br>"; echo('<pre>');  print_r($data); echo('</pre>');
                  $this->db->insert('{PRE}RefundRegister',  $data);
                  $id = $this->db->insert_id();


                  $sub_code =( $row->nAcHead == '') ? '':$row->nAcHead;
                  //insert into refund billing table
                  $data1 = array('nRefundId' => $id,
                      //  'vInvoice' =>
                      'vMode' => 'Cr',
                      'nAcCode' => $row->nDrAccount ."-". $row->vTable,
                      'vAcSubCode' => $sub_code,
                      'nAgentAc' => 0
                      //  'vLPO' =>
                      );
                  //echo "Insert RefundRegisterBilling<br>"; echo('<pre>');  print_r($data1); echo('</pre>');

                  $this->db->insert('{PRE}RefundRegisterBilling',  $data1);


                  $airline_number =  $this->common_model->get_field_name('Airline','vAirlineNo', " WHERE  nArId= " . $row->nAirline. "");


                  //insert into RefundRegister Details table
                  $data3 = array('nRefundId' => $id,
                      'vTktType' => 'Tkt',
                      'vNewOld' => 'N',
                      'nTktNum' => $ticketId,
                      //        'vOrgTktNum' =>
                      'vAirline' => $airline_number,
                      'vPassenger' =>  $row->vPassenger,
                      //        'vRemarks' =>
                      'vFareAmt' => $row->vFare,
                      //        'vUsedFare' =>
                      'vSalesAmt' => $row->vCollected,
                      'vServiceAmt' => $row->vServAmt,
                      //            'nCountry' =>
                      'vTax' => $row->vTax,
                      //        'vUsedTax' =>
                      //        'vOurCharge' =>
                      'vDiscountAmt' =>  $row->vDisAmt,
                      //        'nCity' => $row->nCity,
                      //        'vComm1' =>
                      //        'vComm2' =>
                      //        'vOthA1' =>
                      //        'vOthA2' =>
                      //'nHotel' => $row->vHotelName,
                      //        'vOtherAmt' =>
                      //'vVendorAmt' => $ticket_item['cp_charges'],
                      //        'vOthA3' =>
                    'vAgentAmt' =>  $row->vAgentAmt,
                    'vIssuedBy' => $row->vEmployee,
                    'vNetAmt1' => $row->vNetAmt,
                    'vNetAmt2' => $row->vNetAmt,
                    'vPaidTo' => $row->vCollected );
                  //echo "Insert RefundRegisterDetails<br>"; echo('<pre>');  print_r($data3); echo('</pre>');

                  $this->db->insert('{PRE}RefundRegisterDetails',  $data3);
                }//end foreach
                //echo " Refunded " . $TicketBooking[$key]['vTicktManual'] .
                $cnt++;
              }
              else
              {
                $message .= "Ticket#" . $TicketBooking[$key]['vTicktManual'] . " is not Closed in the system... Cannot refund it.                ";
              }//endif
            }// end if($RefundId == '
        } //endif R
      }
        $message .=  "     " . $cnt . " records uploaded in the system.   ";
    }//_FILES['txtFile']['tmp_name'])!=''
    else
      $message .= "Invalid File.. Fetching Failed";

    return $message;
  }//end function


  function getAutomaticDocNumber2()
  {
    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='5'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}TicketBooking ORDER BY nTID DESC LIMIT 0,1");
    if($fetchCode->num_rows() > 0):
      $totalNum           = $fetchCode->row();
    $this->txtDocNumber = $totalNum->vDocNumber;
    $docNumber      = @split('-',$this->txtDocNumber);

#how many chars will be in the string
    $fill         = $this->txtMinDigit;
#the number
    $number       = ($docNumber[3]+1);
#with str_pad function the zeros will be added
    $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
    // The result: 000056

    $countVal       = $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
else:
    $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='5'");
    $countVal       = $this->txtDocNumber;
    endif;

    return($countVal);
  }//end function

  //refund ticket doucment number
  function getDocNum2()
    {
        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='3'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister ORDER BY nRId DESC LIMIT 0,1");
    if($fetchCode->num_rows() > 0):
      $totalNum           = $fetchCode->row();
      $this->txtDocNumber = $totalNum->vDocNumber;
      $docNumber      = @split('-',$this->txtDocNumber);

      #how many chars will be in the string
      $fill         = $this->txtMinDigit;
      #the number
      $number       = ($docNumber[3]+1);
      #with str_pad function the zeros will be added
      $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
      // The result: 000056

      $countVal       = $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
    else:
      $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='3'");
      $countVal       = $this->txtDocNumber;
    endif;

    return($countVal);
    }//end function


} //end class
?>
