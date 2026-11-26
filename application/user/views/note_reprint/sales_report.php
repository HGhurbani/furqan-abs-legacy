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
$cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'"); ?>
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
<tr>	            <td colspan="8" align="center">
<h1><?php   if($item->ddlType == '1') echo "CASH INVOICE";else echo "CREDIT INVOICE";?></h1></td>		
		<!--<td colspan="8" align="center"><h1><?php if($item->vTransMode == '1') echo CASHRECEIPT;else echo PRINTINVOICE;?></h1></td>-->
		</tr>       
		<tr>			
		<td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;"><?php echo CUSTDATE;?>&nbsp;            
		<strong><?php echo $item->dDate;?></strong>
		
		</td>						
		<td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>			
		<td width="300" align="left" style="border: 1px solid black;border-left-style:none; border-bottom: 0px"><?php echo INVOICENO;?>&nbsp;			
		<strong><?php echo $item->vInvoiceNumber?><strong>			</td>				                        
		</tr>		
		<tr>			
		<td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;"><?php echo CLIENT;?>&nbsp;		    
		<strong><?php echo $item->vInvoiceTo;?></strong>			</td>						
		<td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>	
		<td width="300" align="left" style="border: 1px solid black;border-left-style:none; border-bottom: 0px"><?php echo LPO;?>&nbsp;		    
		<strong><?php echo $item->vLPO;?></strong>			</td>				                        </tr>		
		<tr></tr>        <tr>            
		<td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;">			</td>			
		<td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>			
		<td width="300" align="right" style="border: 1px solid black;border-left-style:none; border-bottom: 0px">			</td>		</tr>		
		<style type="text/css">  table { font-size: 10px; }</style>
        <tr>
            <td colspan="3" height="460" valign="top" style="border: 1px solid black; border-top: 0px;"><table cellpadding="0" style="border: 0px" width="100%">  
                  <tr id="nowas">  <style> #nowas{	 border-bottom: 1px solid black !important; }td{    border: 1px solid black;border-collapse: collapse;}</style>                                     <!--<td><?php echo SRNO;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo SINO;?></strong></td><!-- <td><?php echo TTYPE;?></td> -->                                    <!--<td><?php echo AIRLINE;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo CARRIER;?></strong></td>									<!--<td><?php echo TICKETNAME;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo DOCUMENTNUMBER;?></strong></td>									<!--<td><?php echo OTHERDETAILS;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo SECTOR;?></strong></td>							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo TRAVELDATE;?></strong></td>							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo CATEGORY;?></strong></td>									<!--<td><?php echo PASSENGER;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo PASSENGER;?><strong></td>                            																<!--<td><?php echo SLSAMT;?></td>-->							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo AMOUNT;?></strong></td>							<!-- <td id="newfield"><strong><?php echo SALESAMOUNT;?></strong></td> -->                        </tr>
                    <?php
					
                    $getTktValues   = $this->note_reprint_model->getTktDetails($item->nSCId);
					
                    if(!empty($getTktValues))
                    {
						
						$CV=1;
                        foreach($getTktValues as $key2=>$tkt):?>						 <tr>						
						<?php
						   switch($tkt->vType)
                            {
                                case "Xo" :
                                    $type      = 'Xo';
                                    $ticket    = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$tkt->vTktNumber."'");
                                    $passenger = $this->common_model->get_field_name('XoBooking','vPassengerId'," WHERE nXId='".$tkt->vTktNumber."'");
                                    //$otherDet  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$tkt->vTktNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$tkt->vTktNumber."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$tkt->vTktNumber."'")));
                                    $carrierno = $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='".$tkt->nAirline."'").''.
                                                         $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='0".$tkt->nAirline."'").''.
														 $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='00".$tkt->nAirline."'");
											$salesamount = $this->common_model->get_field_name('XoPassDetails','nSalesAmt'," WHERE nXoId='".$tkt->vTktNumber."'");
											$trvldate = date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$tkt->vTktNumber."'")));
											$sectordat = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$tkt->vTktNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$tkt->vTktNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$tkt->vTktNumber."'");
											$classcategory  = $this->common_model->get_field_name('XoDetails','vClass'," WHERE nXoId='".$tkt->vTktNumber."'");
                                            
									break;
                                case "Tkt" :
                                    $type      = 'Ticket';
                                    $ticket    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$tkt->vTktNumber."'");
                                    $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$tkt->vTktNumber."'");
                                    //$otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$tkt->vTktNumber."'")));
                                    $carrierno = $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='".$tkt->nAirline."'").''.
                                                         $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='0".$tkt->nAirline."'").''.
														 $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='00".$tkt->nAirline."'");
											$sectordat = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.
											$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.
											/*$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.*/
											$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$tkt->vTktNumber."'")
											/*.'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$chkTktNumber[$key]."'")*/
											.'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$tkt->vTktNumber."'")
											/*.'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$chkTktNumber[$key]."'")*/.'-'.
											$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$tkt->vTktNumber."'");
											//$sectordat = $this->common_model->get_field_name('SalesClosing','vSector2'," WHERE nSCId='".$chkSCId[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$tkt->vTktNumber."'").'-'.date('dd-mm-yY',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$tkt->vTktNumber."'")));
                                            $classcategory  = $this->common_model->get_field_name('TicketDetails','vClass1'," WHERE nTicketId='".$tkt->vTktNumber."'");
											$trvldate  = date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$tkt->vTktNumber."'")));
			
									
									break;
                                case "Htl" :
                                    $type      = 'Hotel';
									$carrierno = 'Hotel';
									$classcategory='';
                                    $ticket    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$tkt->vTktNumber."'");
                                    $passenger = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$tkt->vTktNumber."'");
                                    //$cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$tkt->vTktNumber."'");
                                    //$hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$tkt->vTktNumber."'");
                                    //$otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$tkt->vTktNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$tkt->vTktNumber."'"))).'-'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$tkt->vTktNumber."'") . ' - Nights:'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$tkt->vTktNumber."'").' - Rooms:'.$this->common_model->get_field_name('HotelVoucher','vNoOfUnit'," WHERE nHVId='".$tkt->vTktNumber."'");
                                    $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$tkt->vTktNumber."'");
                                            $hotel		= $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$tkt->vTktNumber."'");
											$sectordat     = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'");
											$trvldate  = date('d-m-Y',strtotime($this->common_model->get_field_name('HotelDetails','dCheckInDate'," WHERE nHotelId='".$tkt->vTktNumber."'"))).'<br>'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelDetails','dCheckOutDate'," WHERE nHotelId='".$tkt->vTktNumber."'")));
                                            $otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").' - '.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").' - '.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$tkt->vTktNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$tkt->vTktNumber."'"))).' - Nights:'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$tkt->vTktNumber."'").' - Rooms:'.$this->common_model->get_field_name('HotelVoucher','vNoOfUnit'," WHERE nHVId='".$tkt->vTktNumber."'") ;
                                       
									break;
                            }
                            ?><td  style="border:0" height='25px' align="center">    
							<?php echo $CV;  $key+1;?>                                    </td>
							<!-- <td><label>                                       
							<?php echo stripslashes($type);?>                                   
							</label></td> -->                                   
							<td align="center" style="border:0">   
							<!-- <?php echo stripslashes($chkAirline[$key]);?> -->			
							<?php echo $carrierno;?>                                   
							</td>									
							<!--  document field change only the name in screen-->                                    
							<td align="center" style="border:0">                  
							<?php echo $ticket;?>                                   
							</td>						
							<!--  sector field-->		
							<td align="center" style="border:0">     
							<?php echo $sectordat;?>				
							</td>								
							<!--  travel date-->				
							<td align="center" style="border:0">  
							<?php										
							echo $trvldate;								
							?>                                   
							</td>							
							<!-- class -->						
							<td align="center" style="border:0">
							<?php echo $classcategory;?>         
							</td>                                  
							<td align="left" style="border:0">       
							<?php echo $passenger;?>                 
							</td>                                  
							<!--<td>                                    
							<?php echo $otherDet;?>                   
							</td>-->                                  
							<td align="right" style="border:0"><label>
							<?php echo stripslashes($tkt->vSlsAmt);?>       
							</label></td>										
							<!-- <td id="newfield" valign='top'><?php echo $salesamount;?></td> -->		
							</tr>
                            <?php
							$CV=$CV+1;
                        endforeach; 
                    }//end if 
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top:0px;margin:-4px; border: 1px solid black; border-top:0px">
				<table width="100%" class="reportForm">
                    <tr>
                        <td width="53.9%">&nbsp;</td>
                        <td width="35%"><?php echo DRACCNAME;?></td>
                        <td><?php echo AMOUNT;?></td>
                    </tr>
                    <?php
                    $getAccountValues = $this->note_reprint_model->getAccountDetails($item->nSCId);
                    if(!empty($getAccountValues))
                    {
                        $total = 0;
                        foreach($getAccountValues as $key3=>$acc):
                            switch($acc->vTable)
                            {
                                case "C":
                                    $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$acc->nDrAccount."'");
                                    break;
                                case "V":
                                    $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$acc->nDrAccount."'");
                                    break;
                                case "A":
                                    $AccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc->nDrAccount."'");
                                    break;
                                case "O":
                                    $AccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$acc->nDrAccount."'");
                                    break;
                                case "AG":
                                    $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$acc->nDrAccount."'");
                                    break;
                            }
                            $subAcc = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc->nAcHead."'");
                            $card   = $this->common_model->get_field_name('Card','vName'," WHERE nCId = '".$acc->nCard."'");
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <?php echo $AccountName;?>
                                </td>
                                <td>
                                    <?php echo $acc->vAmt;?>
                                </td>
                            </tr>
                            <?php
                            $total += $acc->vAmt;
                        endforeach; ?>
                        <tr>
                        <?php
                        $valCur = $this->common_model->getCompanyCurr();
                        $strCurCode = $this->common_model->currencyCode($valCur);
                        $strCurCoin = $this->common_model->currencyCoin($valCur);
                        $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                        $multiple = '1';
                        for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                        $decimal_part = ($total - floor($total)) * $multiple;
                        if($decimal_part == 0)
                        {
                            ?>
                                <td  align='right' width="53.9%"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . "  " .  ONLY;?></td>  
                                <?php
                        } else {
?>
                                <td align='right' width="53.9%"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td>
<?php } ?>
                            <td align='center'><strong><?php echo TOTALAMT;?></strong></td>
                            <td>
                                <strong><?php echo number_format($total,3);?></strong>
                            </td>
                        </tr>
                        <?php
                    } //end if
                    ?>
                </table>
            </td>
        </tr>
       <tr >
            <td colspan="3" style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">
                <table height="10" class="reportForm">
				
				       
			</td>                         <tr>
							<td valign="bottom" width="400" colspan="2" style="border:none !important;"><strong><?php echo "Remarks: ";?></strong><?php echo $this->txtRemarks;?></td>														<td valign="bottom" colspan="2" style="border:none !important;"></td>
                        </tr>
                        						
                </table>
            
        </tr>
        
        <tr>
            <td colspan="3"style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">                
		<table height="70" class="reportForm">								       			</td> 																		                        <tr>							<td valign="bottom" width="200" colspan="2" style="border:none !important;"><strong><font size="1"><?php echo "PREPARED BY";?></td></strong></font>							<td valign="bottom" width="200" colspan="2" style="border:none !important;" align="bottom"><strong><font size="1"><?php echo "APPROVED BY";?></td></strong></font>							<td valign="bottom" colspan="2" style="border:none !important;"><strong><font size="1"><?php echo "RECEIVED BY";?></td></strong></font>                        </tr>                         <tr>                            
		<td style="border:none !important;"><?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$item->eid."'");?></td>                            <td style="border:none !important;">&nbsp;</td>                            <td style="border:none !important;">&nbsp;</td>                        </tr>						                </table>                    </tr>
    </tbody>
    </table><br>
		<strong style="font-size:9px;"><?php echo "This is a Computer generated document,bears no signature";?><strong>
	
    <?php
    if((count($list_value)) != ($key+1)):?>
        <div colspan="3" align="center" style="display: block; page-break-before: always;">&nbsp;</div>
    <?php endif;?>
<?php
endforeach;
endif;?>
<script>
    $(document).ready(function() {
        $('#btnPrint').click(function(e) {
            window.print() ;
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