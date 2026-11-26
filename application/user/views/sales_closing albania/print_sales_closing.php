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
    </head>
    <body>
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

    <script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
    <script>
    $(document).ready(function() {

    $('#toPopup4').hide();
    $('#email').click(function(e) {
        $('#toPopup4').show();
    });

        $('#btnPrint').click(function(e) {
            window.print() ;
        });

    });
	
	
	
    </script>
	<?php if($this->uri->segment(4) == 'U') { ?>
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>

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
            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");
            ?>

<style type="text/css">
@media print {
input#email
{
display: none;
}
</style>
		<tr>
			<td colspan="8" align="right">
			<input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTINVOICEPAGE;?>" class="btnGeneral">
			<br><br>
			<div align="right"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

			</td>
		</tr>
		
        <tr>
	            <td colspan="8" align="center"><h1><?php if($this->ddlType == '1') echo CASHRECEIPT;else echo PRINTINVOICE;?></h1></td>
        </tr>
        <tr>
			<td width="300" style="border: 1px solid black; border-bottom: 0px"><?php echo CUSTDATE;?>&nbsp;
            <strong><?php echo $this->txtDate;?></strong>
			</td>
			
			<td colspan="1">&nbsp;</td>
		
			    
				
            <td width="300"  style="border: 1px solid black; border-bottom: 0px"><?php echo INVOICENO;?>&nbsp;
			<strong><?php echo $this->txtDocNumber;?><strong>
			</td>
				
                
        </tr>
        <tr>
            <td colspan="8" style="border: 1px solid black"><?php echo CLIENT;?>&nbsp;
		    <strong><?php echo $this->txtInvoiceTo;?></strong>
			</td>
		</tr>
      
	  
	    <tr>
                
            <td colspan="8" style="border: 1px solid black;  border-top: 0px"><?php echo LPO;?>&nbsp;
		    <strong><?php echo $this->txtLpoMain;?></strong>
			</td>

		<tr>
		
 <style type="text/css">
  table { font-size: 8px; }
</style>
<td colspan="8" height="520" valign="top" style="border: 1px solid black; border-top: 0px;">
<table cellpadding="0" style="border: 0px" width="100%">  
  <tr>
  <style>
td{
border: 1px solid black;
border-collapse: collapse;
}
</style> 
                                    <!--<td><?php echo SRNO;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo SINO;?></strong></td>
                                    <!-- <td><?php echo TTYPE;?></td> -->
                                    <!--<td><?php echo AIRLINE;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo CARRIER;?></strong></td>
									<!--<td><?php echo TICKETNAME;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo DOCUMENTNUMBER;?></strong></td>
									<!--<td><?php echo OTHERDETAILS;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo SECTOR;?></strong></td>
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo TRAVELDATE;?></strong></td>
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo CATEGORY;?></strong></td>
									<!--<td><?php echo PASSENGER;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo PASSENGER;?><strong></td>
                            
							
									<!--<td><?php echo SLSAMT;?></td>-->
							<td align="center" style="border-bottom: 1px solid black"><strong><?php echo AMOUNT;?></strong></td>
							<!-- <td id="newfield"><strong><?php echo SALESAMOUNT;?></strong></td> -->
                        </tr>
												
						<?php
                        if(!empty($chkType))
                        {
							
                            foreach($chkType as $key => $aitem):
                                ?>
                                <tr>
                                    <?php
									switch($chkType[$key])
                                    {
                                        case "Xo" :
                                            $type      = 'Xo';
                                            $ticket    = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId='".$chkTktNumber[$key]."'");
											$carrierno = $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='".$chkAirline[$key]."'").''.
                                                         $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='0".$chkAirline[$key]."'").''.
														 $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='00".$chkAirline[$key]."'");
											$salesamount = $this->common_model->get_field_name('XoPassDetails','nSalesAmt'," WHERE nXoId='".$chkTktNumber[$key]."'");
											$trvldate = date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$chkTktNumber[$key]."'")));
											$sectordat = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$chkTktNumber[$key]."'");
											$classcategory  = $this->common_model->get_field_name('XoDetails','vClass'," WHERE nXoId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$chkTktNumber[$key]."'")));
											
											$typeGroupRow=array();
											$typeGroup    = $this->db->query("SELECT vSector,vSector2 FROM {PRE}XoDetails WHERE nXoId='".$chkTktNumber[$key]."'");
											if ($typeGroup->num_rows() > 0){
												foreach ($typeGroup->result() as $key=>$row)
												{
													$typeGroupRow[] = $row;
												}//end for each
											}//end if
											
                                            break;

                                        case "Tkt" :
                                            $type      = 'Ticket';
                                            $ticket    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$chkTktNumber[$key]."'");
											//$passenger = $this->common_model->get_field_name('Airline','vAlpha'," WHERE vAirlineNo='".$chkAirline[$key]."'");

                                            $carrierno = $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='".$chkAirline[$key]."'").''.
                                                         $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='0".$chkAirline[$key]."'").''.
														 $this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='00".$chkAirline[$key]."'");
											$sectordat = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.
											$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.
											/*$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.*/
											$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$chkTktNumber[$key]."'")
											/*.'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$chkTktNumber[$key]."'")*/
											.'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$chkTktNumber[$key]."'")
											/*.'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$chkTktNumber[$key]."'")*/.'-'.
											$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$chkTktNumber[$key]."'");
											//$sectordat = $this->common_model->get_field_name('SalesClosing','vSector2'," WHERE nSCId='".$chkSCId[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.date('dd-mm-yY',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$chkTktNumber[$key]."'")));
                                            $classcategory  = $this->common_model->get_field_name('TicketDetails','vClass1'," WHERE nTicketId='".$chkTktNumber[$key]."'");
											$trvldate  = date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$chkTktNumber[$key]."'")));
											break;

                                        case "Htl" :
										    $carrierno = 'Hotel';
                                            //$type      = 'Hotel';
                                            $ticket    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $hotel		= $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$chkTktNumber[$key]."'");
											$sectordat     = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'");
											$trvldate  = date('d-m-Y',strtotime($this->common_model->get_field_name('HotelDetails','dCheckInDate'," WHERE nHotelId='".$chkTktNumber[$key]."'"))).'<br>'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelDetails','dCheckOutDate'," WHERE nHotelId='".$chkTktNumber[$key]."'")));
                                            $otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").' - '.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").' - '.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$chkTktNumber[$key]."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$chkTktNumber[$key]."'"))).' - Nights:'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$chkTktNumber[$key]."'").' - Rooms:'.$this->common_model->get_field_name('HotelVoucher','vNoOfUnit'," WHERE nHVId='".$chkTktNumber[$key]."'") ;
                                            break;
                                    }//end switch
                                    ?>
									
							        <td  style="border:0" height='25px' align="center">
                                       <?php echo $key+1;?>
                                    </td>

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

                                    <td align="center" style="border:0">
                                        <?php echo $passenger;?>
                                    </td>

                                    <!--<td>
                                        <?php echo $otherDet;?>
                                    </td>-->

                                    <td align="right" style="border:0"><label>
                                        <?php echo stripslashes($chkCollected[$key]);?>
                                    </label></td>
									
									<!-- <td id="newfield" valign='top'><?php echo $salesamount;?></td> -->
									
                                </tr>
                                <?php
                            endforeach;
                        }//end if
                        ?>
                    </table>
                </td>
            </tr>
			<?php
                        if(!empty($chkDrAccMain))
                        {
                            $total = 0;
                            foreach($chkDrAccMain as $key2 => $pitem):

                                switch($chkAcTable[$key2])
                                {
                                    case "C" :
                                        $dracc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "V" :
                                        $dracc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "AG" :
                                        $dracc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "O" :
                                        $dracc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "A" :
                                        $dracc = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkDrAccMain[$key2]."'");
                                        break;
                                }//end switch

                ?>
				
				            <!--<tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <?php echo $dracc;?>
                                    </td>

                                    <td>
                                        <?php echo $chkAmt[$key2];?>
                                    </td>
                                </tr>
                                <?php
                            $total += $chkAmt[$key2];
                            endforeach; ?> -->
								
		<tr >
			<td colspan="8" style="padding-top:0px;margin:-4px; border: 1px solid black; border-top:0px">
				<table width="100%" class="reportForm">
				
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
	
				
				                <td style="border:0"  colspan="2" align="left"><strong><font size="1"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " . ONLY;?></td> </strong></font>
								<td width="200" style="border:0" valign="middle" align="right"><strong><font size="1"><?php echo TOTAL;?></strong></td></font>
                                <td valign="middle" align="right" style="border:0">
								<strong><font size="1"><?php echo $total=number_format((float)$total,3, '.', '');?></strong></font>

								
								</td>
								
								
				
				   
								<?php
								} else 
									if($lengdecpart = 2){
									
								?>
								
								<td style="border:0" colspan="2" align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='center'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
								
								<?php }else 
								if($lengdecpart = 1){
								
								?>
								
								<td style="border:0" colspan="2" align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='center'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
				
				
				
								<?php } ?>
								
		</tr>
				
				
				 <!--<td width="100" colspan="6" valign="top" align='right' height="50" style="border:0"><strong><?php echo TOTAL;?></strong></td>-->


                <!--<td align='right' width="53.9%"><strong><?php echo $this->common_model->convert_number_to_words($total).' '.$cVal.' '.ONLY;?></strong></td> -->

				<!--<td style="border:0" colspan="2" align="right" valign="top" style="border:0"><strong><?php echo $total;?></strong></td>-->            
				  
				
				

                             

				</table>                        
			</td>                                                               
		
		                        
                                <?php
					    } //end if
								?>
		<tr >
            <td colspan="8" style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">
                <table height="70" class="reportForm">
				
				       
			</td> 

				
				
				
                        <tr>
							<td valign="bottom" width="200" colspan="2" style="border:0"><strong><font size="1"><?php echo PREPAREDBY;?></td></strong></font>
							<td valign="bottom" width="200" colspan="2" style="border:0" align="bottom"><strong><font size="1"><?php echo APPROVEDBY;?></td></strong></font>
							<td valign="bottom" colspan="2" style="border:0"><strong><font size="1"><?php echo RECEIVEDBY;?></td></strong></font>
                        </tr>
                        <!-- <tr>
                            <td><?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$this->session->userdata('AbsAdmin')."'");?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr> -->
                </table>
            
        </tr>
    </tbody>
    </table>
	
	
	
	
	<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('sales_closing/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>
   

</body>
</html>
			
			
			
			
			
			