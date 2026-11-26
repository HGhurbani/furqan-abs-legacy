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
      $tax2_index1 = $netFare_index = $agentId_index = -1;

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

              case "Net Fare":
                $netFare_index = $key;
                break;

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
          $airCode = $tktNo_split[0];
          $airId = $this->common_model->get_field_name('Airline','nArId', " WHERE vAirlineNo = '" . $airCode . "'");

          $gdsPnr = ($gdsPnr_index == -1 ) ? '': $item[$gdsPnr_index];
          $passName = ( $passName_index == -1)? '' :  $item[$passName_index];
          $dDate = ( $dDate_index == -1)? NULL : $item[$dDate_index];
          $status = ( $status_index == -1)? 'Void' :  $item[$status_index];
          $fcFareCash = ( $fcFareCash_index == -1)? 0 :  $item[$fcFareCash_index];
          $tax1 = ( $tax1_index == -1)? 0 :  $item[$tax1_index];
          $commission_1 = ( $commission_index1 == -1)? 0 :  $item[$commission_index1];
          $netFare = ( $netFare_index == -1)? 0 :  $item[$netFare_index];
/////////////// ************************************* change to default
          $agentId = ( $agentId_index == -1)? 'MY' :  $item[$agentId_index];

//// NULL CHECK
          $gdsPnr = ( $gdsPnr == NULL)? '' : $gdsPnr ;
          $passName = ($passName == NULL) ? '' : $passName; 
          $dDate_split = explode(' ', $dDate );
          $dDate_split2 = explode('/', $dDate_split[0] );
          $dDate_split3 = $dDate_split2[0]. '-' . $dDate_split2[1].'-' . $dDate_split2[2];
          $dDate = ($dDate == NULL) ?  date('Y-m-d') :date('Y-m-d', strtotime( $dDate_split3 ));
          $status = ($status == NULL) ? '' : $status;
          $fcFareCash = ($fcFareCash == NULL) ? '' : $fcFareCash;
          $tax1 = ($tax1 == NULL) ? '' : $tax1;
          $commission_1 = ($commission_1 == NULL) ? '' : $commission_1;
          $netFare = ($netFare == NULL) ? '' : $netFare;

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


/////////////// ************************************* change to default
          $agentId = ($agentId == NULL) ? 'MY' : $agentId;
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
          }
          $bookedId_id = $signId_id;

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

          $ticketId    =  $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$tktNo. "'" );
          // if ticket is present in the system don't insert again 

          if($status != "Refund" ){
            if($ticketId != '' && $status != "V"){
              //echo $ticket_no. " is already present in the system...                ";
            }
            else if($ticketId != '' && $status == "V"){
              $db_tkt_status =  $this->common_model->get_field_name('TicketBooking','vType'," WHERE nTId = '".$ticketId. "'" );
              if($db_tkt_status != 'V'){

                $status = 'V';
                $closedId    =  $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vTktNumber= '".$ticketId. "' AND vType='Tkt'" );
                if($closedId != '') { $message .= "Ticket#" . $ticket_no . " is already closed. Changing status to VOID...    ";}
                // set status of ticket as void
                $data   = array( 'vType' => $status);
                $this->db->where('nTId',$ticketId);
                $this->db->update('{PRE}TicketBooking', $data);

                // set fare as 0
                $tax1 = $tax2 = $tax3 = $totalTax = 0;
                $NetAmount = $fc_fair = $pass_contactno = $base_fair = 0;

                $pass_contactno = '';
                //insert into Ticket Fare Details table
                $data2  = array('vTax1' => $tax1,
                    'vTax2' => $tax2,
                    'vTax3' => $tax3,
                    'vTaxTotal' => $totalTax,
                    'nNetVendor' => $NetAmount,
                    'nAcc' => '1',
                    'nAccHead' => '32',
                    'vFCFare' => $fc_fair,
                    'vPassengerContact' => $pass_contactno,
                    'vFaceValue' => $base_fair);
                $this->db->where('nTicketId',$ticketId);
                $this->db->update('{PRE}TicketFareDetails', $data2);

                $cnt++;
              }
            }// endif ticketId != '' && $status == "Void")
            else {
              //hardcode ids
              $getDivison = 1;
              $getLocation = 1;
              $gds = 1;
              $currId = $this->common_model->getCompanyCurr();
              $rate = 1;
              $tax2 = 0;
              $tax3 = 0;
              $totalTax = $tax1 + $tax2 + $tax3;
              $fc_fair = 0;
              $pass_contactno = 0;
              $base_fair = $fcFareCash;
              $commAmt = $commission_1; 
              $nCommPercent = 0;
              $orCommision = 0;
              $netFare = $base_fair + $totalTax - $commAmt - $orCommision;

              $data   = array('nUserId' => $this->session->userdata('AbsAdmin'),
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
              $this->db->insert('{PRE}TicketBooking', $data);
              $id     = $this->db->insert_id();

              //insert into Ticket Fare Details table
              $data   = array('nTicketId' => $id ,'vFare' => 'N',
                  'vTax1' => $tax1,
                  'vTax2' => $tax2,
                  'vTax3' => $tax3,
                  'vTaxTotal' => $totalTax,
                  'nCommAmt' => $commAmt,
                  'nComm'=> $nCommPercent,
                  'nORCAmt' => $orCommision,
                  'nNetVendor' => $netFare,
                  'nAcc' => '1',
                  'nAccHead' => '32',
                  'vFCFare' => $fc_fair,
                  'vPassengerContact' => $pass_contactno,
                  'vFaceValue' => $base_fair);
              $this->db->insert('{PRE}TicketFareDetails', $data);

              //insert into Ticket Billing Details table
              $data   = array('nTicketId' => $id ,
                  'vSalesAmt' => '0',
                  'vCollected' => '0');
              $this->db->insert('{PRE}TicketBillingDetails', $data);
              $cnt++;


            }// else 

          } // endif ticket_item['tkt_type'] != "Refund" 
          else{


          } //else
       }// forloop 2
      } //outermoset foreach 
      $message .=$cnt . " Records Inserted ";
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

}

//end class
?>
