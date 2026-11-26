<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of ABS Account Balancing Solution         |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+
?>
<!DOCTYPE html>
<head>
    <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>


</head>
<body>

<?php if(!empty($list_value)):
    foreach($list_value as $key=>$item):
        ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">
<tbody>


        <?php
        $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
        $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
        $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
        $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
        $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
        $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
        $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");
        $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");?>



    <tr>
        <td colspan="4">
            <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </td>

        
            <td colspan="2" align="right">
            <?php if($key == 0):?>
                <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTINVOICEPAGE;?>" class="btnGeneral">
            <?php endif;?>
            </td>
       
    </tr>


   <tr>	            <td colspan="8" align="center"><h1><?php   if($item->ddlType == '1') echo "CASH INVOICE";else echo "CREDIT NOTE";?></h1></td>				<!--<td colspan="8" align="center"><h1><?php if($item->vTransMode == '1') echo CASHRECEIPT;else echo PRINTINVOICE;?></h1></td>-->		</tr>       		<tr>					<td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;"><?php echo CUSTDATE;?>&nbsp;            		<strong><?php echo $item->dDate;?></strong>				</td>								<td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>					<td width="300" align="left" style="border: 1px solid black;border-left-style:none; border-bottom: 0px"><?php echo "DOCUMENT #:";?>&nbsp;					<strong><?php echo $item->vDocNumber?><strong>			</td>				                        		</tr>
   <tr>            <td colspan="3" height="460" valign="top" style="border: 1px solid black; border-top: 0px;">			<table cellpadding="0" style="border: 0px" width="100%">                    <tr id="nowas">  				  <style> #nowas{	 				  border-bottom: 1px solid black !important; 				  }td{   				  border: 1px solid black;				  border-collapse: collapse;}				  </style>                                     				  <!--<td><?php echo SRNO;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo SINO;?></strong></td>				  <!-- <td><?php echo TTYPE;?></td> -->                                   				  <!--<td><?php echo AIRLINE;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo CARRIER;?></strong></td>					  <!--<td><?php echo TICKETNAME;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo DOCUMENTNUMBER;?></strong></td>													  <!--<td><?php echo OTHERDETAILS;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo SECTOR;?></strong></td>					  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo TRAVELDATE;?></strong></td>									  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo CATEGORY;?></strong></td>													  <!--<td><?php echo PASSENGER;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo PASSENGER;?><strong></td> 				  <!--<td><?php echo SLSAMT;?></td>-->											  <td align="center" style="border-bottom: 1px solid black"><strong><?php echo AMOUNT;?></strong></td>					  <!-- <td id="newfield"><strong><?php echo SALESAMOUNT;?></strong></td> -->                        				  </tr>       
                <?php
                $getTktValues   = $this->note_reprint_model->getRefundDetails($item->nRCId);
                if(!empty($getTktValues))
                {
                    foreach($getTktValues as $key2=>$tkt):
                        switch($tkt->vType)
                        {																								
                            case "Xo" :
                                $type      = 'Xo';								$tc=$this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE nTktNum='".$tkt->nTicketNumber."'");								if($tc=='O'){									 $ticket    = $tkt->nTicketNumber;									  $passenger = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE nTktNum='".$tkt->nTicketNumber."'");									$tdate  ='';																}
                               else{								  $ticket    = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$tkt->nTicketNumber."'");								$passenger = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='".$tkt->nTicketNumber."'");						$tdate  ='';	                              							   }																								
                                 $otherDet  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$tkt->nTicketNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$tkt->nTicketNumber."'");								$classcategory  = $this->common_model->get_field_name('XoDetails','vClass'," WHERE nXoId='".$tkt->nTicketNumber."'");                                           
                                break;

                            case "Tkt" :
                                $type      = 'Ticket';								if(strlen($tkt->nTicketNumber)==10){									 $ticket    =$tkt->nTicketNumber;									 $passenger = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE nTktNum='".$tkt->nTicketNumber."'");									$tdate  ='';																}else{									
                                $ticket    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$tkt->nTicketNumber."'");								$passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$tkt->nTicketNumber."'");								$tdate  =date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$tkt->nTicketNumber."'")));																}
                                
                                $otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$tkt->nTicketNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$tkt->nTicketNumber."'");								  $classcategory  = $this->common_model->get_field_name('TicketDetails','vClass1'," WHERE nTicketId='".$tkt->nTicketNumber."'");										
                               							   break;

                            case "Htl" :
                                $type      = 'Hotel';
                                $ticket    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$tkt->nTicketNumber."'");
                                $passenger = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$tkt->nTicketNumber."'");
                                $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$tkt->nTicketNumber."'");
                                $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$tkt->nTicketNumber."'");
                                $otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'");								$tdate  =date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$tkt->nTicketNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$tkt->nTicketNumber."'"))).'-'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$tkt->nTicketNumber."'");								
                                break;
                        }
                        switch($tkt->vTable)
                        {
                            case "C":
                                $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$tkt->nDrAcc."'");
                                break;

                            case "V":
                                $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$tkt->nDrAcc."'");
                                break;

                            case "A":
                                $AccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$tkt->nDrAcc."'");
                                break;

                            case "O":
                                $AccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$tkt->nDrAcc."'");
                                break;

                            case "AG":
                                $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$tkt->nDrAcc."'");
                                break;
                        }

                        ?>
                        <tr class="re">
                            <td>
                                <?php echo $key2+1;?>
                            </td>

                            <td><label>
                                <?php echo stripslashes($type);?>
                            </label></td>

                            <td>
                                <?php echo $ticket ;//echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$tkt->nAirline."'"));?>
                            </td>

                            <td>
                                <?php echo $otherDet;?>
                            </td>

                            <td>
                                <?php echo $tdate;?>
                            </td>

                            <td>
                                <?php echo $classcategory;?>
                            </td>

                            <td><label>
                                <?php echo $passenger;?>
                            </label></td>

                            <td><label>
                                <?php echo stripslashes($tkt->vPaidAmt);?>
                            </label></td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                ?>
            </table>
        </td>
    </tr>
           		
                <?php
                $getTktValues   = $this->note_reprint_model->getRefundDetails($item->nRCId);
                if(!empty($getTktValues))
                {
                    foreach($getTktValues as $key2=>$tkt):
                      
                        switch($tkt->vTable)
                        {
                            case "C":
                                $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$tkt->nDrAcc."'");
                                break;

                            case "V":
                                $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$tkt->nDrAcc."'");
                                break;

                            case "A":
                                $AccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$tkt->nDrAcc."'");
                                break;

                            case "O":
                                $AccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$tkt->nDrAcc."'");
                                break;

                            case "AG":
                                $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$tkt->nDrAcc."'");
                                break;
                        }

                        ?>
                        <tr >
                           

                            <td colspan="2"style="border: 1px solid black; border-right-style:none; border-top: 0px;" align="right"><label>
                                <?php echo $AccountName;?>
                            </label></td>

                            <td align="right" style="border: 1px solid black; border-left-style:none;border-top: 0px;"><label>
                                <?php $total=$total+$tkt->vPaidAmt;echo stripslashes($tkt->vPaidAmt);?>
                            </label></td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                ?>
            
        
 
	<tr >
        <?php 
                                $valCur = $this->common_model->getCompanyCurr();
								$strCurCode = $this->common_model->currencyCode($valCur);
								$strCurCoin = $this->common_model->currencyCoin($valCur);
								$strCurDecimal = $this->common_model->currencyDecimal($valCur);
								$multiple = '1';
								for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
								$decimal_part = (($total) - floor($total)) * $multiple;
                                $lengdecpart=strlen($decimal_part);
								
                               if($lengdecpart = 3)
                               {
                               ?>
	
				 
				                <td style="border:0"  align="left"><strong><font size="1"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " . ONLY;?></td> </strong></font>
								<td width="200" style="border:0" valign="middle" align="right"><strong><font size="1"><?php echo TOTAL;?></strong></td></font>
                                <td valign="middle" align="right" style="border:0">
								<strong><font size="1"><?php echo $total=number_format((float)$total,3, '.', '');?></strong></font>

								
								</td>
								
								
				
				   
								<?php
								} else 
									if($lengdecpart = 2){
									
								?>
								
								<td style="border:0"  align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='right'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
								
								<?php }else 
								if($lengdecpart = 1){
								
								?>
								
								<td style="border:0"  align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='right'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
				
				
				
								<?php } $total=0;?></td>
    </tr>
    <tr>
        <td><?php echo REMARKS;?></td>
        <td colspan="5"><?php echo $this->txtRemarks;?></td>
    </tr>
	<tr>
        
    </tr>
    
    
	<tr>
        <td colspan="6">
            <table width="100%">
                <tr>
                    <td><?php echo "PREPARED BY";?></td>
                    <td><?php echo "APPROVED BY";?></td>
                    <td><?php echo "RECEIVED BY";?></td>
                </tr>
                <tr>
                    <td><?php //echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$this->session->userdata('AbsAdmin')."'");?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>

</tbody>
</table>
<br>
		<strong style="font-size:9px;"><?php echo "This is a Computer generated document,bears no signature";?><strong>
	

    <?php
    if((count($list_value)) != ($key+1)):?>
        
        <div colspan="6" align="center" style="display: block; page-break-before: always;">&nbsp;</div>
    <?php endif;?>

<?php
endforeach;
endif;?>
<script>
    $(document).ready(function() {
        $('#btnPrint').click(function(e) {
            window.print();
        });
    });
</script>

    <style type="text/css">
        @media print {
            .page-break  { display: block; page-break-before: always; }
        }
    </style>


</body>
</html>