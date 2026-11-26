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

<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/sales_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });
        $('.print_logo').hide();
        $('.print_desc').hide();
        $('#btnPrint').click(function(e) {
            //$('.print_logo').show();
            $('.print_desc').show();
//$('.formButs').hide();
            $('#texxt').printElement();
           $('.print_logo').hide();
            $('.print_desc').hide();
        });
        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
        $('#maincontent').scroll(function() {
            console.log(contentBoxExportPos)
            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
                contentBoxExport.addClass('fixed')
            }
            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
                contentBoxExport.removeClass('fixed')
            }
        })
    });
</script>
<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/sales_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/sales_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/sales_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> 
<div id="texxt">
<script>
$( document ).ready(function() {
   var count = $('#reportForm tr').length;
  
  if($('#reportForm thead tr').length<=30){
	//  alert(30);}
 
  
   $('#counter').html(count);
});
</script><span id="counter"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
    <thead>
    <?php
    if(!empty($this->ddlLocation)):
        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
        $link   = base_url().'upload/location/'.$logo;
    elseif(!empty($this->ddlLocation2)):
        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation2."'");
        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation2."'");
        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation2."'");
        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation2."'");
        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation2."'");
        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation2."'");
        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation2."'");
        $link   = base_url().'upload/location/'.$logo;
    else:
        $logo   = $this->common_model->get_field_name('Division','vImg'," WHERE nId='".$this->ddlDivision."'");
        $name   = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$this->ddlDivision."'");
        $addr   = $this->common_model->get_field_name('Division','vAddress'," WHERE nId='".$this->ddlDivision."'");
        $phone  = $this->common_model->get_field_name('Division','vPhone'," WHERE nId='".$this->ddlDivision."'");
        $mobile = $this->common_model->get_field_name('Division','vMobile'," WHERE nId='".$this->ddlDivision."'");
        $email  = $this->common_model->get_field_name('Division','vEmail'," WHERE nId='".$this->ddlDivision."'");
        $web    = $this->common_model->get_field_name('Division','vWeb'," WHERE nId='".$this->ddlDivision."'");
        $link   = base_url().'upload/division/'.$logo;
    endif;
    ?>

    <tr>
        <th align="center" colspan="16">
            <div class="print_desc">
                <span style="font-size: 11px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
<tr>
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Sales Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
   
<style type="text/css">
#br{
	display:none;}
body{
  -webkit-print-color-adjust:exact;
}
  table { font-size: 9px; }
</style>
<style media="print">
  #bg{ background-color:#CCC !important; }
 #br{ display:block !important;}
</style>

 <tr id="bg" style="">
        <td height="20px" align="center">#</td>
        <td style="width:8%" align="center"><?php echo CUSTDATE;?></td>
        <td style="width:8%" align="center"><?php echo DOCNUMBER; echo "&nbsp;&nbsp;";?></td>
        <td align="center" ><?php  echo "&nbsp;A/C Name";?></td>
        <td align="center"><?php echo "Sub A/C Name";?></td>				
        <td align="center" ><?php echo 'Aircode';echo "&nbsp;&nbsp;";?></td>
        <td align="center"><?php echo TICKET;echo'&nbsp;#';?></td>
        <td align="center"><?php echo 'Issued';echo "&nbsp;&nbsp;";?></td>
        <td align="center" ><?php echo PASSENGER;?></td>
       <!-- <th align="left" style="width:10% !important;"><?php echo SECTOR;?></th>
-->
        <td align="center"><?php echo FARE;?></td>
        <td align="center" ><?php echo TAX;?></td>
        <td align="center" ><?php echo 'ComAmt';echo "&nbsp;&nbsp;";?></td>
        <!--<th align="left"><?php echo CREDITAMT;?></th>-->
        <td align="center"><?php echo NETAMT;?></td>
        <td align="center" ><?php echo 'Sales';?></td>		
        <td align="center" ><?php echo MARGIN;echo "&nbsp;&nbsp;";?></td>
        <td align="center" ><?php echo 'Collected';?></td>
    </tr>
    </thead>
    <tbody>

	<?php
    if(!empty($list_value)){	
        $i= 1;
        $chkType = array();
		$chkAirlineId = array();
		$chknDrAccount = array();
		$count_txh = 0;
		$count_acc = 0;
		$count_max = 0;
		$previousInvoice = '' ;
		$previousDate = '' ;
		$previousSCId = '';
		$previousnId = '';		
		$previousnPosting = '';		
		$chkvInvoiceNumber = array();
		$currentDate = '';
		$heading_refund_type = '';
		foreach($list_value as $key=>$item_main):
		if($item_main->reportTable == 't1' ):
			$currentInvoice = $item_main->vInvoiceNumber;
			$currentDate = $item_main->dDate;
			$xyz = $item_main->dDate;
			$currentSCId = $item_main->nSCId;
			$currentnId = $item_main->nId;		
			$currentnPosting = $item_main->posting;		
//$kkk= $item_main->posting;
 $query = $this->db->query("SELECT * from tbl_AccountHeadControls WHERE nActHId=".$item_main->nDrAccount);
foreach ($query->result() as $row)
{
   //echo $item_main->vAmt;
		if($row->vSubSale == 'C' ):
			//if($currentInvoice != $previousInvoice ){
if($currentInvoice){
				//get rowspan
				if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
?>
				</tr>
  				<tr>
				<?php if( $heading_refund_type != $previousnPosting) {
						?>
							 <td height="20px" colspan="18"><strong><?php 
							 	if($previousnPosting == 'S')
									{ echo 'CASH'; }
							 	else if($previousnPosting == 'B')
									{ echo BANK; $kkk='R'; }
								$heading_refund_type = $previousnPosting;							
							?></strong></td>
						<?php
				}
				?>
 				</tr>
    			<tr>
                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>
                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>

				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>
				 <?php if($previousnPosting == 'R') 
				 {
$kkk='R';
				 	?>
					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				}
				?>
				 </td>
				<?
				//copy invoice details
				$ittr = 0;
					while($ittr <= $count_max ){				
					//print amount
							if ($ittr < $count_acc ) {
							   switch($acc['vTable'][$ittr])
								{
									case "C":
										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "V":
										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "AG":
										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "O":
										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									default:
										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;
										$drAccountName = "";
									break;
								}//end switch
								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");
						?>				
									<td align="left"><?php echo $subAccName  ; //$item->nAcHead;//($subAccName);?></td>
									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>
							<?php		
							}
						    else
							{ ?>
							   <td> </td> <td> </td> 
							<?php
							}
							if ($ittr < $count_txh ) {
				?>
                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;";?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />
<?php
			$sector_t = '';
			if($txh['sale_type'][$ittr] == 'Xo')
			{
				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
				}
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Ticket') {
					$tbl_name = 'TicketDetails';
				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Hotel')
			{
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		
				echo " - ";
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Nights: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Rooms: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " -  ";				
				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	
				echo " - ";				
				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));
			}
?>
				</td>
                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>
                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>
                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>
              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->
                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>
				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				
                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>
<td align="left"><?php echo stripslashes($txh['vAmt'][$ittr]); $totalvAmt = $totalvAmt+stripslashes($txh['vAmt'][$ittr]); ?></td>
 	<tr>
				<?php
							}
							else { echo " <tr> "; } 
						$ittr = $ittr + 1;
						?>
						<?php
					}
				}
				//reset values
				$chkAirlineId = array();
				$chknDrAccount = array();
				$count_max = 0;
				$count_acc = 0;
				$count_txh = 0;	
				$previousInvoice = $currentInvoice;
				$previousDate = $currentDate;		
				$previousSCId = $currentSCId;	
				$previousnId = $currentnId;		
				$previousnPosting = $currentnPosting;							
				$currentInvoice = $item_main->vInvoiceNumber;
				$currentDate = $item_main->dDate;
				$currentSCId = $item_main->nSCId; 
				$currentnId = $item_main->nId; 
				$currentnPosting = $item_main->posting; 			
			}	

			//break up the array into accounts

				if(!in_array($item_main->tableId,$chkAirlineId)):
					$chkAirlineId[] = $item_main->tableId;
					$txh['tableId'][$count_txh] = $item_main->tableId ;
					$txh['nLocation'][$count_txh] = $item_main->nLocation ;
					$txh['sale_type'][$count_txh] = $item_main->sale_type ;
					$txh['vIssuedBy'][$count_txh] = $item_main->vIssuedBy ;
					$txh['airline'][$count_txh] = $item_main->airline ;
					$txh['vGdspnr'][$count_txh] = $item_main->vGdspnr ;
					$txh['vStatus'][$count_txh] = $item_main->vStatus ;
					$txh['salesAmt'][$count_txh]= $item_main->salesAmt ;
					$txh['discAmt'][$count_txh]= $item_main->discAmt ;
					$txh['tktNumber'][$count_txh]= $item_main->tktNumber ;
					$txh['lpo'][$count_txh]= $item_main->lpo ;
					$txh['vPassenger'][$count_txh]= $item_main->vPassenger ;
					$txh['netAmt'][$count_txh]= $item_main->netAmt ;
					$txh['taxTotal'][$count_txh]= $item_main->taxTotal ;
					$txh['cmtAmount'][$count_txh]= $item_main->cmtAmount ;												
					$txh['creditAmt'][$count_txh]= $item_main->creditAmt ;												
					$txh['netAmt'][$count_txh]= $item_main->netAmt ;			
					$txh['fareVal'][$count_txh]= $item_main->fareVal ;												
					$txh['vCollected'][$count_txh]= $item_main->vCollected ;												
					$txh['margin'][$count_txh]= $item_main->margin ;												
$txh['vAmt'][$count_txh]= $item_main->vAmt ;												
					$totalFare = $totalFare+$item_main->fareVal;
					$totalTax  = $totalTax+$item_main->taxTotal;
					$totalComm = $totalComm+$item_main->cmtAmount;
					$totalCredit = $totalCredit+$item_main->creditAmt;
					$totalNet  = $totalNet+$item_main->netAmt;
					$totalCollected = $totalCollected + $item_main->vCollected;
					$totalMargin = $totalMargin + $item_main->margin;					
					$count_txh = $count_txh + 1;
					endif;
		        if(!in_array($item_main->nDrAccount,$chknDrAccount)):
					$chknDrAccount[] = $item_main->nDrAccount;
					$acc['nDrAccount'][$count_acc] = $item_main->nDrAccount ;
					$acc['nAcHead'][$count_acc] = $item_main->nAcHead ;
					$acc['vTable'][$count_acc] = $item_main->vTable ;
					$acc['nId'][$count_acc] = $item_main->nId ;			
					$count_acc = $count_acc + 1;
				endif;
		// code for rowspan of an invoice
		else:	// t1 else
			break;			
		endif; // t1 check
}
 endif; //
		endforeach;
if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
				?>
				</tr>
  				<tr>
				<?php if( $heading_refund_type != $previousnPosting) {
						?>
							 <td height="20px" colspan="18"><strong><?php 
							 	if($previousnPosting == 'S')
									{ echo SALES; }
							 	else if($previousnPosting == 'R')
									{ echo REFUND; 
									$kkk='R';
								}
								$heading_refund_type = $previousnPosting;							
							?></strong></td>
						<?php
				}
				?>
 				</tr>
    			<tr>
                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>
                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>
				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>
				 				 <?php if($previousnPosting == 'R') 
				 {
$kkk='R';
				 	?>
					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				}
				?>
				</td>
				<?
				//copy invoice details
				$ittr = 0;
					while($ittr < $count_max ){				
					//print amount
							if ($ittr < $count_acc ) {
							   switch($acc['vTable'][$ittr])
								{
									case "C":
										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "V":
										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "AG":
										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "O":
										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									default:
										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;
										$drAccountName = "";
									break;
								}//end switch
								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");
						?>				
									<td height="20px" align="left"><?php echo $subAccName ; //$item->nAcHead;//($subAccName);?></td>
									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>
							<?php		
							}
						    else
							{ ?>
							   <td> </td> <td> </td> 
							<?php
							}
							if ($ittr < $count_txh ) {
				?>
                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />
<?php
			$sector_t = '';
			if($txh['sale_type'][$ittr] == 'Xo')
			{
				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
				}
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Ticket') {
					$tbl_name = 'TicketDetails';
				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Hotel')
			{
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		
				echo " - ";
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Nights: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Rooms: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " -  ";				
				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	
				echo " - ";				
				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));
			}
?>

				</td>
                <td height="20px" align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['fareVal'][$ittr]);}?></td>
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo round(stripslashes($txh['taxTotal'][$ittr]),3);}?></td>
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['cmtAmount'][$ittr]);}?></td>
              <!--  <td align="left"><?php if ($currentInvoice == $previousInvoice){ echo stripslashes($txh['creditAmt'][$ittr]);}?></td>-->
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['netAmt'][$ittr]);}?></td>
				<td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['vCollected'][$ittr]);}?></td>				
                <td align="left"><?php if ($currentInvoice == $previousInvoice){echo round(stripslashes($txh['margin'][$ittr]),3);}?></td>
<?php if ($currentInvoice == $previousInvoice && $kkk!='R' ){?>
<td align="left"><?php echo stripslashes($txh['vAmt'][$ittr]); $totalvAmt = $totalvAmt+stripslashes($txh['vAmt'][$ittr]); ?></td>
 <?php } elseif($kkk=='R'){?>
 <td align="left"><?php $ra=$ra+stripslashes($txh['vCollected'][$ittr]); echo stripslashes($txh['vCollected'][$ittr]);?></td>
  <?php }else{?>
  <td align="left"></td>
 		<?php }?>		<tr>
				<?php
							}
							else { echo " <tr> "; } 
						$ittr = $ittr + 1;
						?>
						<?php
					}
				}
		?>
            <?php
        ?>
		<tr id="bg">		
		<?php
		if($totalFare != 0 ): ?>
			<td height="20px" align="right" colspan="9"><strong><?php echo TOTALAMT; echo"&nbsp;&nbsp;";?></strong></td>
			<td align="left"><?php echo number_format(stripslashes($totalFare),3);?></td>
			<td align="left"><?php echo number_format(stripslashes($totalTax),3);?></td>
			<td align="left"><?php echo number_format(stripslashes($totalComm),3);?></td>
			<!--<td align="left"><?php echo number_format(stripslashes($totalCredit),3);?></td>
-->
			<td align="left"><?php echo number_format(stripslashes($totalNet),3);?></td>
			<td align="left"><?php echo number_format(stripslashes($totalCollected),3);;?></td>
			<td align="left"><?php echo number_format(stripslashes($totalMargin),3);?></td>
            <td align="left"><?php $r=-$ra;$totalvAmt=$totalvAmt-$r; echo number_format(stripslashes($totalvAmt),3);?></td>
		<?php
		else: 
		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>" ;
		
		?>
		<style type="text/css">#reportForm1{
display:none;
}</style>
		<?php endif;
		
		?>
		</tr><tr>
<?php
		$previousInvoice = '' ;
//		$previousDate = '' ;
		//$previousSCId = '';
		//$previousnId = '';		
		//$previousnPosting = '';		
		//$chkvInvoiceNumber = array();
		//$currentDate = '';
		$heading_refund_type = '';
		foreach($list_value as $key=>$item_main):
		if($item_main->reportTable == 't1' && $item_main->posting!='R'):
			$currentInvoice = $item_main->vInvoiceNumber;
			$currentDate = $item_main->dDate;
			$currentSCId = $item_main->nSCId;
			$currentnId = $item_main->nId;		
			$currentnPosting = $item_main->posting;		
$k=1;
 $query = $this->db->query("SELECT * from tbl_AccountHeadControls WHERE nActHId=".$item_main->nDrAccount);
foreach ($query->result() as $row)
{
  // echo $row->vSubSale;
 			if($row->vSubSale == 'B' ):?>
            	<tr>
				<?php if( $heading_refund_type != $previousnPosting) {
						?>
							 <td colspan="18"><strong><?php 
							 	if($previousnPosting == 'S')
									{ echo 'BANK'; }
							 	else if($previousnPosting == 'B')
									{ echo BANK; }
								$heading_refund_type = $previousnPosting;							
							?></strong></td>
						<?php
				}
				?>
 				</tr>
            <?php 
			if($currentInvoice  ){
				//get rowspan
				if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
?>
				</tr>
  				<tr>
				<?php if( $heading_refund_type != $previousnPosting) {
						?>
							 <td height="20px" colspan="18"><strong><?php 
							 	if($previousnPosting == 'S')
									{ echo CASH; }
							 	else if($previousnPosting == 'B')
									{ echo BANK; }
								$heading_refund_type = $previousnPosting;							
							?></strong></td>
						<?php
				}
				?>
 				</tr>
    			<tr>
                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>
                <?php echo "<td height='20px' align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>
				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>
				 <?php if($previousnPosting == 'R') 
				 {
				 	?>
					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				}
				?>
				 </td>
				<?
				//copy invoice details
				$ittr = 0;
					while($ittr <= $count_max ){				
					//print amount
							if ($ittr < $count_acc ) {
							   switch($acc['vTable'][$ittr])
								{
									case "C":
										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "V":
										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "AG":
										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "O":
										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									default:
										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;
										$drAccountName = "";
									break;
								}//end switch
								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");
						?>				
									<td align="left"><?php echo $subAccName  ; //$item->nAcHead;//($subAccName);?></td>
									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>
							<?php		
							}
						    else
							{ ?>
							   <td> </td> <td> </td> 
							<?php
							}
							if ($ittr < $count_txh ) {
				?>
                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />
<?php
			$sector_t = '';
			if($txh['sale_type'][$ittr] == 'Xo')
			{
				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
				}
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Ticket') {
					$tbl_name = 'TicketDetails';
				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));
				echo $sector_t[0];
			for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
			}
			else if($txh['sale_type'][$ittr] == 'Hotel')
			{
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		
				echo " - ";
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Nights: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " - Rooms: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	
				echo " -  ";				
				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	
				echo " - ";				
				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));
				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));
			}
?>
				</td>
                <td height="20px" align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['fareVal'][$ittr]);}?></td>
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo round(stripslashes($txh['taxTotal'][$ittr]),3);}?></td>
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['cmtAmount'][$ittr]);}?></td>
              <!--  <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['creditAmt'][$ittr]);}?></td>-->
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['netAmt'][$ittr]);}?></td>
				<td align="left"><?php  if ($currentInvoice == $previousInvoice){echo stripslashes($txh['vCollected'][$ittr]);}?></td>				
                <td align="left"><?php  if ($currentInvoice == $previousInvoice){echo round(stripslashes($txh['margin'][$ittr]),3);}?></td>
				<?php if ($currentInvoice == $previousInvoice){?>
<td align="left"><?php echo stripslashes($txh['vAmt'][$ittr]); $totalvAmt1 = $totalvAmt1+stripslashes($txh['vAmt'][$ittr]);?></td>
 <?php } else{?>
 <td align="left"></td>
  <?php }?>
			<tr>
				<?php
							}
							else { echo " <tr> "; } 
						$ittr = $ittr + 1;
						?>
						<?php
					}
				}
				//reset values
				$chkAirlineId = array();
				$chknDrAccount = array();
				$count_max = 0;
				$count_acc = 0;
				$count_txh = 0;	
				$previousInvoice = $currentInvoice;
				$previousDate = $currentDate;		
				$previousSCId = $currentSCId;	
				$previousnId = $currentnId;		
				$previousnPosting = $currentnPosting;							
				$currentInvoice = $item_main->vInvoiceNumber;
				$currentDate = $item_main->dDate;
				$currentSCId = $item_main->nSCId; 
				$currentnId = $item_main->nId; 
				$currentnPosting = $item_main->posting; 			
			}	
			//break up the array into accounts
				if(!in_array($item_main->tableId,$chkAirlineId)):
					$chkAirlineId[] = $item_main->tableId;
					$txh['tableId'][$count_txh] = $item_main->tableId ;
					$txh['nLocation'][$count_txh] = $item_main->nLocation ;
					$txh['sale_type'][$count_txh] = $item_main->sale_type ;
					$txh['vIssuedBy'][$count_txh] = $item_main->vIssuedBy ;
					$txh['airline'][$count_txh] = $item_main->airline ;
					$txh['vGdspnr'][$count_txh] = $item_main->vGdspnr ;
					$txh['vStatus'][$count_txh] = $item_main->vStatus ;
					$txh['salesAmt'][$count_txh]= $item_main->salesAmt ;
					$txh['discAmt'][$count_txh]= $item_main->discAmt ;
					$txh['tktNumber'][$count_txh]= $item_main->tktNumber ;
					$txh['lpo'][$count_txh]= $item_main->lpo ;
					$txh['vPassenger'][$count_txh]= $item_main->vPassenger ;
					$txh['netAmt'][$count_txh]= $item_main->netAmt ;
					$txh['taxTotal'][$count_txh]= $item_main->taxTotal ;
					$txh['cmtAmount'][$count_txh]= $item_main->cmtAmount ;												
					$txh['creditAmt'][$count_txh]= $item_main->creditAmt ;												
					$txh['netAmt'][$count_txh]= $item_main->netAmt ;			
					$txh['fareVal'][$count_txh]= $item_main->fareVal ;												
					$txh['vCollected'][$count_txh]= $item_main->vCollected ;												
					$txh['margin'][$count_txh]= $item_main->margin ;	
					$txh['vAmt'][$count_txh]= $item_main->vAmt ;												
					$totalFare1 = $totalFare1+$item_main->fareVal;
					$totalTax1  = $totalTax1+$item_main->taxTotal;
					$totalComm1 = $totalComm1+$item_main->cmtAmount;
					$totalCredit1 = $totalCredit1+$item_main->creditAmt;
					$totalNet1  = $totalNet1+$item_main->netAmt;
					$totalCollected1 = $totalCollected1 + $item_main->vCollected;
					$totalMargin1 = $totalMargin1 + $item_main->margin;					
					$count_txh = $count_txh + 1;
					endif;
		        if(!in_array($item_main->nDrAccount,$chknDrAccount)):
					$chknDrAccount[] = $item_main->nDrAccount;
					$acc['nDrAccount'][$count_acc] = $item_main->nDrAccount ;
					$acc['nAcHead'][$count_acc] = $item_main->nAcHead ;
					$acc['vTable'][$count_acc] = $item_main->vTable ;
					$acc['nId'][$count_acc] = $item_main->nId ;			
					$count_acc = $count_acc + 1;
				endif;

		// code for rowspan of an invoice
		else:	// t1 else
			break;			
		endif; // t1 check
}
		endif; //
	endforeach;

				if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
				?>
				</tr>
  				<tr>
				<?php if( $heading_refund_type != $previousnPosting) {
						?>
							 <td height="20px" colspan="18"><strong><?php 
							 	if($previousnPosting == 'S')
									{ echo SALES; }
							 	else if($previousnPosting == 'R')
									{ echo REFUND; }
								$heading_refund_type = $previousnPosting;							
							?></strong></td>
						<?php
				}
				?>
 				</tr>
    			<tr>
                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>
                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>
				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>
				 				 <?php if($previousnPosting == 'R') 
				 {
				 	?>
					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				}
				?>
				</td>
				<?
				//copy invoice details
				$ittr = 0;
					while($ittr < $count_max ){				
					//print amount
							if ($ittr < $count_acc ) {
							   switch($acc['vTable'][$ittr])
								{
									case "C":
										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "V":
										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "AG":
										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									case "O":
										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");
									break;
									default:
										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;
										$drAccountName = "";
									break;
								}//end switch
								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");
						?>				
									<td align="left"><?php echo $subAccName ; //$item->nAcHead;//($subAccName);?></td>
									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>
							<?php		
							}
						    else
							{ ?>
							   <td> </td> <td> </td> 
							<?php
							}
							if ($ittr < $count_txh ) {
				?>
                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />

<?php

			$sector_t = '';
			if($txh['sale_type'][$ittr] == 'Xo')
			{
			$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));
					$l++;
				}


				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

	

			}

			else if($txh['sale_type'][$ittr] == 'Ticket') {

					$tbl_name = 'TicketDetails';

				

				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));

	

				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

			

			}

			else if($txh['sale_type'][$ittr] == 'Hotel')

			{

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		

				echo " - ";

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Nights: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Rooms: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " -  ";				

				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	

				echo " - ";				

				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));



			}



?>





				</td>

                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>

                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>

                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>

              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->

                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>

				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				

                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>
<?php if ($currentInvoice == $previousInvoice){?>
                
<td align="left"><?php echo stripslashes($txh['vAmt'][$ittr]); $totalvAmt1 = $totalvAmt1+stripslashes($txh['vAmt'][$ittr]);?></td>
 <?php } else{?>
 <td align="left"></td>

  <?php }?>
				<tr>





				<?php



							

							}

							else { echo " <tr> "; } 



						$ittr = $ittr + 1;

						?>



						<?php

					}

		

				

				}

		



		?>





            <?php

        ?>



		<tr id="bg">		

		<?php

		if($totalFare != 0 ): ?>

			<td height="20px" align="right" colspan="9"><strong><?php echo TOTALAMT;echo"&nbsp;&nbsp;";?></strong></td>

			<td align="left"><?php echo number_format(stripslashes($totalFare1),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalTax1),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalComm1),3);?></td>

			<!--<td align="left"><?php echo number_format(stripslashes($totalCredit1),3);?></td>
-->
			<td align="left"><?php echo number_format(stripslashes($totalNet1),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalCollected1),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalMargin1),3);?></td>
<td align="left"><?php echo number_format(stripslashes($totalvAmt1),3);?></td>



		<?php

		else:

		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>";

		endif;

		?>

		</tr>

	<tr>
		
<?php


 
		
		$previousInvoice = '' ;

//		$previousDate = '' ;

		//$previousSCId = '';

		//$previousnId = '';		

		//$previousnPosting = '';		

		//$chkvInvoiceNumber = array();



		//$currentDate = '';

		$heading_refund_type = '';

		foreach($list_value as $key=>$item_main):

		if($item_main->reportTable == 't1'&& $item_main->vTable=='C'&&$item_main->posting=='S'):
		
			$currentInvoice = $item_main->vInvoiceNumber;

			$currentDate = $item_main->dDate;

			$currentSCId = $item_main->nSCId;

			$currentnId = $item_main->nId;		

			$currentnPosting = $item_main->posting;		

$k=1;
  
    //$previousnPosting!='R'
			if($currentnPosting):?>
            	<tr>

				<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td height="20px" colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo 'CREDIT'; }

							 	else if($previousnPosting == 'R')

									{ echo 'REFUND'; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}

				?>

 				</tr>
            <?php 
			
			if($currentInvoice ){

				//get rowspan



				if($count_txh > $count_acc) {  $count_max =  $count_txh; }

				else {   $count_max =  $count_acc; }	

				//print table if previous is not ''

				if($previousInvoice != '') { 

?>

				</tr>

  				<tr>

				<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td height="20px" colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo CASH; }

							 	else if($previousnPosting == 'R')

									{ echo BANK; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}
 
				?>

 				</tr>

    			<tr>

                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>

                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>

				



				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 

				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>

				 <?php if($previousnPosting == 'R') 

				 {

				 	?>

					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				 }

				 else

				 {

				 	?>

				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				}

				?>

				 

				 

				 </td>

				 

				<?

				//copy invoice details

				$ittr = 0;

				

					while($ittr <= $count_max ){				

					//print amount



							if ($ittr < $count_acc ) {

			

							   switch($acc['vTable'][$ittr])

								{

									case "C":

										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "V":

										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "AG":

										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "O":

										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									default:

										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");

										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");

										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;

										$drAccountName = "";

									break;

								}//end switch

						

								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");

								

									

						?>				

						

									<td align="left"><?php echo $subAccName  ; //$item->nAcHead;//($subAccName);?></td>

									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>

					

							<?php		

					



							}

						    else

							{ ?>

							   <td> </td> <td> </td> 

							<?php

							}

							if ($ittr < $count_txh ) {



				?>

        		

                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>

                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>

                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>

                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />

				
<?php

			

				

			$sector_t = '';

			if($txh['sale_type'][$ittr] == 'Xo')

			{

				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));

				$l = 0;

				for($k=0;$k<$total_sector_rows;$k++){

				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

				}



				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

	

			}

			else if($txh['sale_type'][$ittr] == 'Ticket') {

					$tbl_name = 'TicketDetails';

				

				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));

	

				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

			

			}

			else if($txh['sale_type'][$ittr] == 'Hotel')

			{

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		

				echo " - ";

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Nights: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Rooms: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " -  ";				

				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	

				echo " - ";				

				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));



			}



?>





				</td>

                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>

                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>

                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>

              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->

                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>

				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				

                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>
                
              
				<tr>





				<?php



							

							}

							else { echo " <tr> "; } 



						$ittr = $ittr + 1;

						?>



						<?php

					}

		

				

				}

				//reset values

				$chkAirlineId = array();

				$chknDrAccount = array();

				$count_max = 0;

				$count_acc = 0;

				$count_txh = 0;	

											

				$previousInvoice = $currentInvoice;

				$previousDate = $currentDate;		

				$previousSCId = $currentSCId;	

				$previousnId = $currentnId;		

				$previousnPosting = $currentnPosting;							

				

				$currentInvoice = $item_main->vInvoiceNumber;

				$currentDate = $item_main->dDate;

				$currentSCId = $item_main->nSCId; 

				$currentnId = $item_main->nId; 

				$currentnPosting = $item_main->posting; 			



			}	

			

			//break up the array into accounts

			

				if(!in_array($item_main->tableId,$chkAirlineId)):

					$chkAirlineId[] = $item_main->tableId;

					$txh['tableId'][$count_txh] = $item_main->tableId ;
$l = $item_main->nLocation;
					$txh['nLocation'][$count_txh] = $item_main->nLocation ;

					$txh['sale_type'][$count_txh] = $item_main->sale_type ;

					$txh['vIssuedBy'][$count_txh] = $item_main->vIssuedBy ;

					$txh['airline'][$count_txh] = $item_main->airline ;

					$txh['vGdspnr'][$count_txh] = $item_main->vGdspnr ;

					$txh['vStatus'][$count_txh] = $item_main->vStatus ;

					$txh['salesAmt'][$count_txh]= $item_main->salesAmt ;

					$txh['discAmt'][$count_txh]= $item_main->discAmt ;

					$txh['tktNumber'][$count_txh]= $item_main->tktNumber ;

					$txh['lpo'][$count_txh]= $item_main->lpo ;

					$txh['vPassenger'][$count_txh]= $item_main->vPassenger ;

					$txh['netAmt'][$count_txh]= $item_main->netAmt ;

					$txh['taxTotal'][$count_txh]= $item_main->taxTotal ;

					$txh['cmtAmount'][$count_txh]= $item_main->cmtAmount ;												

					$txh['creditAmt'][$count_txh]= $item_main->creditAmt ;												

					$txh['netAmt'][$count_txh]= $item_main->netAmt ;			

					$txh['fareVal'][$count_txh]= $item_main->fareVal ;												

					$txh['vCollected'][$count_txh]= $item_main->vCollected ;												

					$txh['margin'][$count_txh]= $item_main->margin ;	
					$txh['vAmt'][$count_txh]= $item_main->vAmt ;												

					$totalFare2 = $totalFare2+$item_main->fareVal;

					$totalTax2  = $totalTax2+$item_main->taxTotal;

					$totalComm2 = $totalComm2+$item_main->cmtAmount;

					$totalCredit2 = $totalCredit2+$item_main->creditAmt;

					$totalNet2  = $totalNet2+$item_main->netAmt;

					$totalCollected2 = $totalCollected2 + $item_main->vCollected;

					$totalMargin2 = $totalMargin2 + $item_main->margin;					

					$count_txh = $count_txh + 1;

					endif;



	

		        if(!in_array($item_main->nDrAccount,$chknDrAccount)):

					$chknDrAccount[] = $item_main->nDrAccount;

					$acc['nDrAccount'][$count_acc] = $item_main->nDrAccount ;

					$acc['nAcHead'][$count_acc] = $item_main->nAcHead ;

					$acc['vTable'][$count_acc] = $item_main->vTable ;

					$acc['nId'][$count_acc] = $item_main->nId ;			

					$count_acc = $count_acc + 1;

				endif;

		

		// code for rowspan of an invoice

		else:	// t1 else

			break;			

		endif; // t1 check

 endif; //

		endforeach;





				if($count_txh > $count_acc) {  $count_max =  $count_txh; }

				else {   $count_max =  $count_acc; }	

				//print table if previous is not ''

				if($previousInvoice != '') { 

				?>

				</tr>

  				<tr>

				<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo SALES; }

							 	else if($previousnPosting == 'R')

									{ echo REFUND; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}

				?>

 				</tr>

    			<tr>

                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>

                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>

				



				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 

				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>

				 				 <?php if($previousnPosting == 'R') 

				 {

				 	?>

					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				 }

				 else

				 {

				 	?>

				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				}

				?>

				</td>

				<?

				//copy invoice details

				$ittr = 0;

				

					while($ittr < $count_max ){				

					//print amount



							if ($ittr < $count_acc ) {

			

							   switch($acc['vTable'][$ittr])

								{

									case "C":

										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "V":

										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "AG":

										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "O":

										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									default:

										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");

										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");

										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;

										$drAccountName = "";

									break;

								}//end switch

						

								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");

								

									

						?>				

						

									<td align="left"><?php echo $subAccName ; //$item->nAcHead;//($subAccName);?></td>

									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>

					

							<?php		

					



							}

						    else

							{ ?>

							   <td> </td> <td> </td> 


							<?php

							}

							if ($ittr < $count_txh ) {



				?>

        		

                <td height="20px" align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>

                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>

                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />

<?php

			

				

			$sector_t = '';

			if($txh['sale_type'][$ittr] == 'Xo')

			{

				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));

				$l = 0;

				for($k=0;$k<$total_sector_rows;$k++){

				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

				}



				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

	

			}

			else if($txh['sale_type'][$ittr] == 'Ticket') {

					$tbl_name = 'TicketDetails';

				

				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));

	

				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

			

			}

			else if($txh['sale_type'][$ittr] == 'Hotel')

			{

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		

				echo " - ";

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Nights: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Rooms: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " -  ";				

				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	

				echo " - ";				

				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));


				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));



			}



?>





				</td>

                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>

                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>

                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>

              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->

                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>

				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				

                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>
 
               
               
				<tr>





				<?php



							

							}

							else { echo " <tr> "; } 



						$ittr = $ittr + 1;

						?>



						<?php

					}

		

				

				}

		



		?>





            <?php

        ?>



		<tr id="bg">		

		<?php

		if($totalFare != 0 ): ?>

			<td height="20px" align="right" colspan="9"><strong><?php echo TOTALAMT;echo"&nbsp;&nbsp;";?></strong></td>

			<td align="left"><?php echo number_format(stripslashes($totalFare2),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalTax2),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalComm2),3);?></td>

			<!--<td align="left"><?php echo number_format(stripslashes($totalCredit2),3);?></td>
-->
			<td align="left"><?php echo number_format(stripslashes($totalNet2),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalCollected2),3);?></td>

			<td align="left"><?php echo"&nbsp;&nbsp;";echo number_format(stripslashes($totalMargin2),3);?></td>

			<td align="left"></td>

		<?php

		else:

		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>";

		endif;

		?>

		</tr>


	<tr>
	<td id="br"><br /><br /><br /><br /><br /><br /><br /></td>	
<?php


 
		
		$previousInvoice = '' ;

//		$previousDate = '' ;

		//$previousSCId = '';

		//$previousnId = '';		

		$previousnPosting = '';		

		//$chkvInvoiceNumber = array();



		//$currentDate = '';

		$heading_refund_type = '';

		foreach($list_value as $key=>$item_main):

		if($item_main->reportTable == 't1'&& $item_main->posting=='R' && $item_main->nDrAccount!='24'):
		
			$currentInvoice = $item_main->vInvoiceNumber;

			$currentDate = $item_main->dDate;

			$currentSCId = $item_main->nSCId;

			$currentnId = $item_main->nId;		

			$currentnPosting = $item_main->posting;		

$k=1;
  
    //$previousnPosting!='R'
			if($item_main->posting=='R'):?>
            	<tr>
<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td height="20px" colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo SALES; }

							 	else if($previousnPosting == 'R')

									{ echo REFUND; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}
 
				?>
 				</tr>
            <?php 
			
			if($currentInvoice ){

				//get rowspan



				if($count_txh > $count_acc) {  $count_max =  $count_txh; }

				else {   $count_max =  $count_acc; }	

				//print table if previous is not ''

				if($previousInvoice != '') { 

?>

				</tr>

  				<tr>

				<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td height="20px" colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo SALES; }

							 	else if($previousnPosting == 'R')

									{ echo REFUND; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}
 
				?>

 				</tr>

    			<tr>

                <?php echo "<td height='20px' align='centert' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>

                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>

				



				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 

				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>

				 <?php if($previousnPosting == 'R') 

				 {

				 	?>

					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				 }

				 else

				 {

				 	?>

				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				}

				?>

				 

				 

				 </td>

				 

				<?

				//copy invoice details

				$ittr = 0;

				

					while($ittr <= $count_max ){				

					//print amount



							if ($ittr < $count_acc ) {

			

							   switch($acc['vTable'][$ittr])

								{

									case "C":

										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "V":

										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "AG":

										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "O":

										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									default:

										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");

										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");

										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;

										$drAccountName = "";

									break;

								}//end switch

						

								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");

								

									

						?>				

						

									<td align="left"><?php echo $subAccName  ; //$item->nAcHead;//($subAccName);?></td>

									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>

					

							<?php		

					



							}

						    else

							{ ?>

							   <td> </td> <td> </td> 

							<?php

							}

							if ($ittr < $count_txh ) {



				?>

        		

                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>

                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>

                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>

                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />

				
<?php

			

				

			$sector_t = '';

			if($txh['sale_type'][$ittr] == 'Xo')

			{

				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));

				$l = 0;

				for($k=0;$k<$total_sector_rows;$k++){

				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

				}



				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

	

			}

			else if($txh['sale_type'][$ittr] == 'Ticket') {

					$tbl_name = 'TicketDetails';

				

				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));

	

				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

			

			}

			else if($txh['sale_type'][$ittr] == 'Hotel')

			{

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		

				echo " - ";

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Nights: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Rooms: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " -  ";				

				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	

				echo " - ";				

				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));



			}



?>





				</td>

                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>

                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>

                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>

              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->

                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>

				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				

                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>

				
                <tr>





				<?php



							

							}

							else { echo " <tr> "; } 



						$ittr = $ittr + 1;

						?>



						<?php

					}

		

				

				}

				//reset values

				$chkAirlineId = array();

				$chknDrAccount = array();

				$count_max = 0;

				$count_acc = 0;

				$count_txh = 0;	

											

				$previousInvoice = $currentInvoice;

				$previousDate = $currentDate;		

				$previousSCId = $currentSCId;	

				$previousnId = $currentnId;		

				$previousnPosting = $currentnPosting;							

				

				$currentInvoice = $item_main->vInvoiceNumber;

				$currentDate = $item_main->dDate;

				$currentSCId = $item_main->nSCId; 

				$currentnId = $item_main->nId; 

				$currentnPosting = $item_main->posting; 			



			}	

			

			//break up the array into accounts

			

				if(!in_array($item_main->tableId,$chkAirlineId)):

					$chkAirlineId[] = $item_main->tableId;

					$txh['tableId'][$count_txh] = $item_main->tableId ;

					$txh['nLocation'][$count_txh] = $item_main->nLocation ;

					$txh['sale_type'][$count_txh] = $item_main->sale_type ;

					$txh['vIssuedBy'][$count_txh] = $item_main->vIssuedBy ;

					$txh['airline'][$count_txh] = $item_main->airline ;

					$txh['vGdspnr'][$count_txh] = $item_main->vGdspnr ;

					$txh['vStatus'][$count_txh] = $item_main->vStatus ;

					$txh['salesAmt'][$count_txh]= $item_main->salesAmt ;

					$txh['discAmt'][$count_txh]= $item_main->discAmt ;

					$txh['tktNumber'][$count_txh]= $item_main->tktNumber ;

					$txh['lpo'][$count_txh]= $item_main->lpo ;

					$txh['vPassenger'][$count_txh]= $item_main->vPassenger ;

					$txh['netAmt'][$count_txh]= $item_main->netAmt ;

					$txh['taxTotal'][$count_txh]= $item_main->taxTotal ;

					$txh['cmtAmount'][$count_txh]= $item_main->cmtAmount ;												

					$txh['creditAmt'][$count_txh]= $item_main->creditAmt ;												

					$txh['netAmt'][$count_txh]= $item_main->netAmt ;			

					$txh['fareVal'][$count_txh]= $item_main->fareVal ;												

					$txh['vCollected'][$count_txh]= $item_main->vCollected ;												

					$txh['margin'][$count_txh]= $item_main->margin ;												

					$totalFare3 = $totalFare3+$item_main->fareVal;

					$totalTax3  = $totalTax3+$item_main->taxTotal;

					$totalComm3 = $totalComm3+$item_main->cmtAmount;

					$totalCredit3 = $totalCredit3+$item_main->creditAmt;

					$totalNet3  = $totalNet3+$item_main->netAmt;

					$totalCollected3 = $totalCollected3 + $item_main->vCollected;


					$totalMargin3 = $totalMargin3 + $item_main->margin;					

					$count_txh = $count_txh + 1;

					endif;



	

		        if(!in_array($item_main->nDrAccount,$chknDrAccount)):

					$chknDrAccount[] = $item_main->nDrAccount;

					$acc['nDrAccount'][$count_acc] = $item_main->nDrAccount ;

					$acc['nAcHead'][$count_acc] = $item_main->nAcHead ;

					$acc['vTable'][$count_acc] = $item_main->vTable ;

					$acc['nId'][$count_acc] = $item_main->nId ;			

					$count_acc = $count_acc + 1;

				endif;

		

		// code for rowspan of an invoice

		else:	// t1 else

			break;			

		endif; // t1 check

 endif; //

		endforeach;





				if($count_txh > $count_acc) {  $count_max =  $count_txh; }

				else {   $count_max =  $count_acc; }	

				//print table if previous is not ''

				if($previousInvoice != '') { 

				?>

				</tr>

  				<tr>

				<?php if( $heading_refund_type != $previousnPosting) {

						?>

							 <td colspan="18"><strong><?php 

							 	if($previousnPosting == 'S')

									{ echo SALES; }

							 	else if($previousnPosting == 'R')

									{ echo REFUND; }

								$heading_refund_type = $previousnPosting;							

							?></strong></td>

						<?php

				}

				?>

 				</tr>

    			<tr>

                <?php echo "<td height='20px' align='center' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1; echo"&nbsp;&nbsp;";?></td>

                <?php echo "<td align='center' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>

				



				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 

				 echo "<td height='20px' align='center' rowspan=". $count_max . ">"; ?>

				 				 <?php if($previousnPosting == 'R') 

				 {

				 	?>

					 <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				 }

				 else

				 {

				 	?>

				 	<a style="text-decoration:none;" href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>

				 	<?php

				}

				?>

				</td>

				<?

				//copy invoice details

				$ittr = 0;

				

					while($ittr < $count_max ){				

					//print amount



							if ($ittr < $count_acc ) {

			

							   switch($acc['vTable'][$ittr])

								{

									case "C":

										$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "V":

										$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "AG":

										$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									case "O":

										$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $acc['nDrAccount'][$ittr]  ."'");

									break;

									default:

										//$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");

										//$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");

										$acc['nAcHead'][$ittr] = $acc['nDrAccount'][$ittr] ;

										$drAccountName = "";

									break;

								}//end switch

						

								$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$acc['nAcHead'][$ittr]."'");

								

									

						?>				

						

									<td align="left"><?php echo $subAccName ; //$item->nAcHead;//($subAccName);?></td>

									<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>

					

							<?php		

					



							}

						    else

							{ ?>

							   <td> </td> <td> </td> 


							<?php

							}

							if ($ittr < $count_txh ) {



				?>

        		

                <td height="20px" align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>

                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]); echo "&nbsp;&nbsp;";?></td>

                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>

                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?><br />

<?php

			

				

			$sector_t = '';

			if($txh['sale_type'][$ittr] == 'Xo')

			{

				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'"));

				$l = 0;

				for($k=0;$k<$total_sector_rows;$k++){

				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$txh['tableId'][$ittr]. "order by nxdid asc'",$k));

					$l++;

				}



				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

	

			}

			else if($txh['sale_type'][$ittr] == 'Ticket') {

					$tbl_name = 'TicketDetails';

				

				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$txh['tableId'][$ittr]."'")));

	

				echo $sector_t[0];

				for($j=1;$j<8;$j++) {

					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )

						echo " - " . $sector_t[$j];

				}

			

			}

			else if($txh['sale_type'][$ittr] == 'Hotel')

			{

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));		

				echo " - ";

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Nights: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " - Rooms: ";				

				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));	

				echo " -  ";				

				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));

				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	

				echo " - ";				

				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $txh['tableId'][$ittr]."'"));


				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));



			}



?>





				</td>

                <td height="20px" align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>

                <td align="left"><?php echo round(stripslashes($txh['taxTotal'][$ittr]),3);?></td>

                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>

              <!--  <td align="left"><?php echo stripslashes($txh['creditAmt'][$ittr]);?></td>-->

                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>

				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				

                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),3);?></td>

				<tr>





				<?php



							

							}

							else { echo " <tr> "; } 



						$ittr = $ittr + 1;

						?>



						<?php

					}

		

				

				}

		



		?>





            <?php

        ?>



		<tr id="bg">		

		<?php

		if($totalFare != 0 ): ?>

			<td height="20px" align="right" colspan="9"><strong><?php echo TOTALAMT;echo"&nbsp;&nbsp;";?></strong></td>

			<td align="left"><?php echo number_format(stripslashes($totalFare3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalTax3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalComm3),3);?></td>

			<!--<td align="left"><?php echo number_format(stripslashes($totalCredit3),3);?></td>
-->
			<td align="left"><?php echo number_format(stripslashes($totalNet3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalCollected3),3);?></td>

			<td align="left"><?php echo"&nbsp;&nbsp;"; echo number_format(stripslashes($totalMargin3),3);?></td>
<td align="left"></td>


		<?php

		else:

		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>";

		endif;

		?>

		</tr>


	<tr id="bg">		

		<?php

		if($totalFare != 0 ): ?>

			<td height="20px" align="right" colspan="9"><strong><?php echo 'Grand Total';echo"&nbsp;&nbsp;";?></strong></td>

			<td align="left"><?php echo number_format(stripslashes($totalFare+$totalFare1+$totalFare2+$totalFare3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalTax+$totalTax1+$totalTax2+$totalTax3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalComm+$totalComm1+$totalComm2+$totalComm3),3);?></td>

			<!--<td align="left"><?php echo number_format(stripslashes($totalCredit+$totalCredit1+$totalCredit2+$totalCredit3),3);?></td>
-->
			<td align="left"><?php echo number_format(stripslashes($totalNet+$totalNet1+$totalNet2+$totalNet3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalCollected+$totalCollected1+$totalCollected2+$totalCollected3),3);?></td>

			<td align="left"><?php echo number_format(stripslashes($totalMargin+$totalMargin1+$totalMargin2+$totalMargin3),3);?></td>
<td align="left"><?php echo number_format(stripslashes($totalvAmt+$totalvAmt1),3);?></td>



		<?php

		else:

		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>";

		endif;

		?>

		</tr>

</table>
	

	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm1">

    
 
<?php
	foreach($list_value as $key=>$item_main):

	

////////////////////////// TABLE 2 - FINANCE 

		
////////////////////////// TABLE  - FINANCE 

		if(stripslashes($item_main->reportTable) == 't3'):

			$current_t3_row = $item_main->vDocNumber;

			if($previous_t3_row == '')

			{

				if($total_credit_acc !=0){

					?>

					<tr><td height="20px" colspan="5" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>

					<td><?php echo $total_credit_acc; ?> </td>

					</tr>

					<tr><td></td></tr>

					<tr><td colspan="5" align="right"> <strong><?php echo GROUPTOTAL ?> </strong></td>

					<td><?php  $group_total = $group_total + $total_credit_acc; echo $group_total ?> </td>

					</tr>

					<?php

					$total_credit_acc =0;

				}

				?>

						
 <tr><td colspan="18" align="center"><strong><?php echo 'Finance Details'; ?> </strong></td></tr>	

				<!--<tr><td><br /><br /><br /></td></tr>	
-->
	

				<tr id="bg">

					<td height="20px" align="center"> <?php echo '#'; ?> </td>
					<td align="center"><?php echo LOCATION;?></td>					

					<td align="center"><?php echo CUSTDATE;?></td>

					<td align="center"><?php echo DOCNUMBER;?></td>

					
					<td align="center"><?php echo ACCOUNTNAME;?></td>
                    
                    <td align="center"><?php echo REMARKS;?></td>										

					

					<!--<th align="left" style="word-break:break-all;"><?php echo SUBACCOUNTNAME;?></th>				
-->
			        <td align="center"><?php echo DEBIT;?></td>

			        <td align="center"><?php echo CREDIT;?></td>		
<td align="center"></td>		

			      <!--  <th align="left"><?php echo RECEIVEDPAID;?></th>		
-->
				</tr>

			<?php

				

			}

			?>				

				<tr>

					<?php
					$it='CASH';

						if( $heading_refund_type != $it) {

							?>

								 <td colspan="18"><strong><?php echo 'CASH';

									$heading_refund_type = $it;							

								?></strong></td>

							<?php

						}

					?>

				</tr>

				

				<?php





			if($previous_t3_row == '' || $previous_t3_row != $current_t3_row ){

	?>

				<tr><td></td></tr>

				<tr> 

					<td height="20px" align="center"> <?php echo $i; $i = $i+1; ?> </td>

					<td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='". $item_main->vLocation . "'")); ?></td>

					<td align="center"><?php echo stripslashes($item_main->dDate);  ?></td>

					<!-- hard coded 5 as uri segment -->

					<td> <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/<?php echo $item_main->page; ?>/5<?php echo '/e/'.$item_main->financeId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($item_main->vDocNumber); ?></a></td>

					
										

				<?php 

			}

			else 

			{

				?>

					<td></td>

					<td></td>

					<td></td>

					<td></td>

					<td></td>

				<?php

			}

			?>

				

				

				

	    <?php switch($item_main->vTable)

				  {

				  	case 'V' : $tableName = "VendorMaster";

								$columnCheck = "nVId";

								break;

				  	case 'C' : $tableName = "CustomerMaster";

								$columnCheck = "nCId";					

								break;

				  	case 'A' : $item_main->vAcSubCode = $item_main->vACode;

								$tableName = "";

								$columnCheck = "";					

								break;

				  	case 'O' : $tableName = "Other";

								$columnCheck = "nOId";					

								break;

				  }

			?>
   <td><?php echo stripslashes($this->common_model->get_field_name('AccountHeadAccounts' ,'vTitle'," WHERE nId='". $item_main->vAcSubCode ."'"));?></td>

				

				<?php if($tableName !='') {	?> 

<!--<td><?php echo stripslashes($this->common_model->get_field_name($tableName,'vName'," WHERE ". $columnCheck . " ='". $item_main->vACode ."'")) ; ?></td>

-->				<?php

				}

				else

				{?>

				<!--<td></td>-->

				<?php }?>

				<td><?php echo stripslashes($item_main->vRemarks); ?></td>


		

				<?php 

				if($item_main->vMode == 'Cr')

				{ ?>

					<td><?php echo $item_main->nAmount;

					$totalDebit = $totalDebit + $item_main->nAmount?></td><td></td>

				<?php 

				}

				else if($item_main->vMode == 'Dr')

				{ ?>

					<td></td><td><?php echo $item_main->nAmount;

					$totalCredit = $totalCredit + $item_main->nAmount?></td>

				<?php 

				}

				else

				{ ?>

					<td></td><td></td>

				<?php

				}

				?>			

				<?php

				if($previous_t3_row =='' || $previous_t3_row != $current_t3_row ){

					?>

					

					

					

					<?php 

					

					

				}

			

				$previous_t3_row = $current_t3_row;

				?>

					

				

				</tr>		

	<?php

			

		endif;
endforeach;

	

	// print this if t3 is not present

	if($total_credit_acc !=0){

					?>

					<tr><td height="20px" colspan="4" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>

					<td><?php echo $total_credit_acc; ?> </td>

					</tr>

					<tr><td></td></tr>

					<tr><td colspan="4" align="right"> <strong><?php echo GROUPTOTAL ?> </strong></td>

					<td><?php $group_total = $group_total + $total_credit_acc; echo $group_total ?> </td>

					</tr>



					<?php

					$total_credit_acc =0;

	}

	if($totalDebit != 0 || $totalCredit !=0 ): ?>

	<tr id="bg">

		<td height="20px" align="right" colspan="6"><strong><?php echo TOTALAMT; echo"&nbsp;&nbsp;";?></strong></td>

		<td align="left"><?php echo number_format(stripslashes($totalDebit),3);?></td>

		<td align="left"><?php echo number_format(stripslashes($totalCredit),3);?></td>

		<td align="left"><?php echo number_format(stripslashes($totalDebit-$totalCredit),3);?></td>
<td align="left"></td>
	</tr>

	<?php

	endif;

	?>

	

<?php
	foreach($list_value as $key=>$item_main):

if(stripslashes($item_main->reportTable) == 't5'):

			$current_t5_row = $item_main->vDocNumber;

			if($previous_t3_row == '')

			{

				if($total_credit_acc !=0){

					?>

					<tr><td height="20px" colspan="5" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>

					<td><?php echo $total_credit_acc; ?> </td>

					</tr>

					<tr><td></td></tr>

					<tr><td height="20px" colspan="5" align="right"> <strong><?php echo GROUPTOTAL ?> </strong></td>

					<td><?php  $group_total = $group_total + $total_credit_acc; echo $group_total ?> </td>

					</tr>

					<?php

					$total_credit_acc =0;

				}

				?>

						

				<tr><td><br /><br /><br /></td></tr>	

	

				<tr id="bg">

					<th height="20px" align="center"> <?php echo '#'; ?> </th>
					<th align="center"><?php echo LOCATION;?></th>					

					<th align="center"><?php echo CUSTDATE;?></th>

					<th align="center"><?php echo DOCNUMBER;?></th>

					
													

					<th align="center"><?php echo ACCOUNTNAME;?></th>
                    <th align="center"><?php echo REMARKS;?></th>		

					<!--<th align="left" style="word-break:break-all;"><?php echo SUBACCOUNTNAME;?></th>				
-->
			        <th align="center"><?php echo DEBIT;?></th>

			        <th align="center"><?php echo CREDIT;?></th>		

			      <!--  <th align="left"><?php echo RECEIVEDPAID;?></th>		
-->
				</tr>

			<?php

				

			}

			?>				

				<tr>

					<?php
					$it='TEST';

						if( $heading_refund_type != $it) {

							?>

								 <td height="20px" colspan="18"><strong><?php echo 'BANK';

									$heading_refund_type = $it;							

								?></strong></td>

							<?php

						}

					?>

				</tr>

				

				<?php





			if($previous_t5_row == '' || $previous_t5_row != $current_t5_row ){

	?>

				<tr><td></td></tr>

				<tr> 

					<td height="20px" align="center"> <?php echo $i; $i = $i+1; ?> </td>

					<td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='". $item_main->vLocation . "'")); ?></td>

					<td align="center"><?php echo stripslashes($item_main->dDate);  ?></td>

					<!-- hard coded 5 as uri segment -->

					<td> <a style="text-decoration:none;" href="<?php echo base_url();?>index.php/<?php echo $item_main->page; ?>/5<?php echo '/e/'.$item_main->financeId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($item_main->vDocNumber); ?></a></td>

					

					
										

				<?php 

			}

			else 

			{

				?>

					<td></td>

					<td></td>

					<td></td>

					<td></td>

					<td></td>

				<?php

			}

			?>

				

				

				

	    <?php switch($item_main->vTable)

				  {

				  	case 'V' : $tableName = "VendorMaster";

								$columnCheck = "nVId";

								break;

				  	case 'C' : $tableName = "CustomerMaster";

								$columnCheck = "nCId";					

								break;

				  	case 'A' : $item_main->vAcSubCode = $item_main->vACode;

								$tableName = "";

								$columnCheck = "";					

								break;

				  	case 'O' : $tableName = "Other";

								$columnCheck = "nOId";					

								break;

				  }

			?>
   <td><?php echo stripslashes($this->common_model->get_field_name('AccountHeadAccounts' ,'vTitle'," WHERE nId='". $item_main->vAcSubCode ."'"));?></td>

				

				<?php if($tableName !='') {	?> 

<!--<td><?php echo stripslashes($this->common_model->get_field_name($tableName,'vName'," WHERE ". $columnCheck . " ='". $item_main->vACode ."'")) ; ?></td>

-->				<?php

				}

				else

				{?>

				<!--<td></td>-->

				<?php }?>

				
<td><?php echo stripslashes($item_main->vRemarks); ?></td>

		

				<?php 

				if($item_main->vMode == 'Dr')

				{ ?>

					<td height="20px"><?php echo $item_main->nAmount;

					$totalDebit1 = $totalDebit1 + $item_main->nAmount?></td><td></td>

				<?php 

				}

				else if($item_main->vMode == 'Cr')

				{ ?>

					<td></td><td><?php echo $item_main->nAmount;

					$totalCredit1 = $totalCredit1 + $item_main->nAmount?></td>

				<?php 

				}

				else

				{ ?>

					<td></td><td></td>

				<?php

				}

				?>			


				<?php

				if($previous_t5_row =='' || $previous_t5_row != $current_t5_row ){

					?>

					

					

					

					<?php 

					

					

				}

			

				$previous_t5_row = $current_t5_row;

				?>

					

				

				</tr>		

	<?php

			

		endif;


	endforeach;

	

	// print this if t3 is not present

	if($total_credit_acc !=0){

					?>

					<tr><td height="20px" colspan="4" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>

					<td><?php echo $total_credit_acc; ?> </td>

					</tr>

					<tr><td></td></tr>

					<tr><td colspan="4" align="right"> <strong><?php echo GROUPTOTAL ?> </strong></td>

					<td><?php $group_total = $group_total + $total_credit_acc; echo $group_total ?> </td>

					</tr>



					<?php

					$total_credit_acc =0;

	}

	if($totalDebit != 0 || $totalCredit !=0 ): ?>

	<tr id="bg">

		<td height="20px" align="right" colspan="6"><strong><?php echo TOTALAMT; echo"&nbsp;&nbsp;";?></strong></td>

		<td align="left"><?php echo number_format(stripslashes($totalDebit1),3);?></td>

		<td align="left"><?php echo number_format(stripslashes($totalCredit1),3);?></td>

		<td align="left"><?php echo number_format(stripslashes($totalDebit1-$totalCredit1),3);?></td>

	</tr>

	<?php

	endif;

	?>

	<tr> <td><br /><br /></td></tr>
    <tr>
<td id="br"><br /><br /><br /><br /><br /><br /></td>
					
								 <td  align="center" colspan="18"><strong>CASH & BANK SUMMARY</strong></td>

					<br />		
				</tr>

<tbody>
<tr id="bg">
<td height="20px" align="center">LOC</td>
<td align="center">DATE</td>
<td align="center">A/C NAME</td>
<td align="center">DEBIT</td>
<td align="center">CREDIT</td>
</tr>
<tr>

					<br />
								 <td height="20px" colspan="18"><strong>SALES</strong></td>

							
				</tr><tr>
<td height="20px" align="center"><?php echo $l;?></td>
<td align="center"><?php echo $this->txtFromDate;?></td>
<td height="20px" align="left">CASH</td>
<td align="left"><?php echo number_format($totalvAmt, 3, '.', ''); ?></td>
<td align="left"></td>
</tr>  
<tr>
<td height="20px" align="center"><?php echo $l;?></td>
<td align="center"><?php echo $this->txtFromDate;?></td>
<td height="20px" align="left">BANK</td>
<td align="left"><?php echo number_format($totalvAmt1, 3, '.', '');?></td>
<td align="left"></td>
</tr>
<tr>

					<br />
								 <td height="20px" colspan="18"><strong>RECEIPT & PAYMENT</strong></td>

							
				</tr>
  

<tr>
<td height="20px" align="center"><?php echo $l;?></td>
<td align="center"><?php echo $this->txtFromDate;?></td>
<td height="20px" align="left">CASH</td>
<td align="left"><?php echo number_format($totalDebit, 3, '.', '');?></td>
<td align="left"><?php echo number_format($totalCredit, 3, '.', '');?></td>
</tr>  
<tr>
<td height="20px" align="center"><?php echo $l;?></td>
<td align="center"><?php echo $this->txtFromDate;?></td>
<td height="20px" align="left">BANK</td>
<td align="left"><?php echo number_format($totalDebit1, 3, '.', '');?></td>
<td align="left"><?php echo number_format($totalCredit1, 3, '.', '');?></td>
</tr>  
<tr id="bg">
<td height="20px" align="left"></td>
<td align="left"></td>
<td height="20px" align="left">TOTAL</td>
<td align="left"><?php echo number_format($tot=$totalvAmt1+$totalvAmt+$totalDebit+$totalDebit1, 3, '.', '');?></td>
<td align="left"><?php echo number_format($tot1=$totalCredit+$totalCredit1, 3, '.', '');?></td>
</tr>
<tr>
<td></td>
<br /></br>
</tr>


<tr id="bg">
<td height="20px" align="left"></td>
<td align="left"></td>
<td height="20px" align="left">TOTAL CASH</td>
<td align="left"><?php echo number_format($tot3=$totalvAmt+$totalDebit-$totalCredit, 3, '.', '');?></td>
<td align="left"></td>
</tr>
<tr id="bg">
<td height="20px" align="left"></td>
<td align="left"></td>
<td height="20px" align="left">TOTAL BANK</td>
<td align="left"><?php echo number_format($tot4=$totalvAmt1+$totalDebit1-$totalCredit1, 3, '.', '');?></td>
<td align="left"></td>
</tr>
<tr id="bg">
<td height="20px" align="left"></td>
<td align="left"></td>
<td height="20px" align="left">GRAND TOTAL </td>
<td align="left"><?php echo number_format($tot=$tot3+$tot4, 3, '.', '');?></td>
<td align="left"></td>

</tr>  

</tbody>
	<!--	<th align="right" colspan="5"></th>

		<th align="left"><?php echo DEBIT;?></th>

		<th align="left"><?php echo CREDIT;?></th>

		<th align="left"><?php echo BALANCE;?></th>

	<tr>

		<td align="right" colspan="5"><strong><?php echo CASHINHANDGRANDTOTAL;?></strong></td>

		<td align="left"><?php $grandDebit = $grandCash + $totalCredit ; echo stripslashes($grandDebit);?></td>

		<td align="left"><?php echo stripslashes($totalDebit);?></td>

		<td align="left"><?php $grandTotal = $grandDebit - $totalDebit; echo stripslashes($grandTotal);?></td>

	</tr>-->

	

    <?php }//end if

    else

    {

        ?>

    <tr>

        <td style="border:0" colspan="17" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

	


	

	

	


        <?php }//end else

    ?>

  <style type="text/css">
@media print {
input#btnPrint,
input#btnCancel {
display: none;
}
}
</style>
    <tr class="formButs">

        

        <td style="border:0" colspan="12">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>
</div>