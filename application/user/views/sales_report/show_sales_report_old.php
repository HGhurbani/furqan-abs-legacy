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
            $('.print_logo').show();
            $('.print_desc').show();
            $('#reportForm').printElement();
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
    <a href="<?php echo base_url().'index.php/export_xl/sales_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/sales_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/sales_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> 
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
        <th colspan="16">
            <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100">

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
    <tr>
        <th align="left">#</th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo DOCNUMBER;?></th>
        <th align="left"><?php echo ACCOUNTNAME;?></th>
        <th align="left"><?php echo SUBACCOUNTNAME;?></th>				
        <th align="left"><?php echo AIRCODE;?></th>
        <th align="left"><?php echo TICKETNAME;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo PASSENGER;?></th>
        <th align="left"><?php echo SECTOR;?></th>
        <th align="left"><?php echo FARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo COMMAMT;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo COLLECTED;?></th>		
        <th align="left"><?php echo MARGIN;?></th>
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

		
		foreach($list_value as $key=>$item_main):
		if($item_main->reportTable == 't1'):
			$currentInvoice = $item_main->vInvoiceNumber;
			$currentDate = $item_main->dDate;
			$currentSCId = $item_main->nSCId;
			$currentnId = $item_main->nId;		
			$currentnPosting = $item_main->posting;		
					
			if($currentInvoice != $previousInvoice ){
				//get rowspan

				if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
				?>
				</tr>
    			<tr>
                <?php echo "<td align='left' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1;?></td>
                <?php echo "<td align='left' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>
				

				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td align='left' rowspan=". $count_max . ">"; ?>
				 <?php if($previousnPosting == 'R') 
				 {
				 	?>
					 <a href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
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
        		
                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?></td>
				<td align="left">
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
                <td align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['taxTotal'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>
				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				
                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),2);?></td>
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
					$txh['netAmt'][$count_txh]= $item_main->netAmt ;			
					$txh['fareVal'][$count_txh]= $item_main->fareVal ;												
					$txh['vCollected'][$count_txh]= $item_main->vCollected ;												
					$txh['margin'][$count_txh]= $item_main->margin ;												
					$totalFare = $totalFare+$item_main->fareVal;
					$totalTax  = $totalTax+$item_main->taxTotal;
					$totalComm = $totalComm+$item_main->cmtAmount;
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

		endforeach;


				if($count_txh > $count_acc) {  $count_max =  $count_txh; }
				else {   $count_max =  $count_acc; }	
				//print table if previous is not ''
				if($previousInvoice != '') { 
				?>
				</tr>
    			<tr>
                <?php echo "<td align='left' rowspan=". $count_max . ">";   echo $this->common_model->_offset+$i;  $i= $i+1;?></td>
                <?php echo "<td align='left' rowspan=". $count_max .  ">";  echo stripslashes($previousDate);?></td>
				

				 <?php //				<!-- hardcoding $this->uri->segment(3) to 4 --> 
				 echo "<td align='left' rowspan=". $count_max . ">"; ?>
				 				 <?php if($previousnPosting == 'R') 
				 {
				 	?>
					 <a href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo '4/e/'.$previousSCId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
				 	<?php
				 }
				 else
				 {
				 	?>
				 	<a href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo '4/e/'. $previousnId .'/'.$previousSCId?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($previousInvoice) ;?></a>
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
        		
                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='". $txh['airline'][$ittr] ."'"));?></td>
                <td align="left"><?php echo stripslashes($txh['tktNumber'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$txh['vIssuedBy'][$ittr]."'"));?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($txh['vPassenger'][$ittr]);?></td>
				<td align="left">
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
                <td align="left"><?php echo stripslashes($txh['fareVal'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['taxTotal'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['cmtAmount'][$ittr]);?></td>
                <td align="left"><?php echo stripslashes($txh['netAmt'][$ittr]);?></td>
				<td align="left"><?php echo stripslashes($txh['vCollected'][$ittr]);?></td>				
                <td align="left"><?php echo round(stripslashes($txh['margin'][$ittr]),2);?></td>
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

		<tr>		
		<?php
		if($totalFare != 0 ): ?>
			<td align="right" colspan="10"><strong><?php echo TOTALAMT;?></strong></td>
			<td align="left"><?php echo stripslashes($totalFare);?></td>
			<td align="left"><?php echo stripslashes($totalTax);?></td>
			<td align="left"><?php echo stripslashes($totalComm);?></td>
			<td align="left"><?php echo stripslashes($totalNet);?></td>
			<td align="left"><?php echo stripslashes($totalCollected);?></td>
			<td align="left"><?php echo round(stripslashes($totalMargin),2);?></td>

		<?php
		else:
		  echo "<td colspan='16'>" . SORRYNORECORD . "</td>";
		endif;
		?>
		</tr>
	
	
	
	
	
	<tr><td><br /><br /><br /></td></tr>	
	
	
	
	
	
	<?php

//variables for accounts - t2	
	$display_main_heading_t2 = 1;
	$display_cash_heading_t2 = 1;	
	$display_bank_heading_t2 = 1;	
	$display_credit_heading_t2 = 1;	
	
	$total_cash = 0;
	$total_bank = 0;
	$total_credit_acc = 0;
	$group_total =0;
	
// variables for finance - t3	
	$previous_t3_row = '';
	$current_t3_row = '';
	$i = 1;
	$j = 1;

	$totalDebit = 0;
	$totalCredit = 0;
	$totalReceivedPaid = 0;
	foreach($list_value as $key=>$item_main):
	
////////////////////////// TABLE 2 - FINANCE 
		if(stripslashes($item_main->reportTable) == 't2'):
			if($display_main_heading_t2 == 1):
		?>
			
				<tr>
					<th align="left"> <?php echo '#'; ?> </th>
					<th align="left"><?php echo CUSTDATE;?></th>
					<th align="left"><?php echo LOCATION;?></th>					
					<th align="left"><?php echo ACCOUNTNAME;?></th>
					<th align="left"><?php echo SUBACCOUNTNAME;?></th>				
			        <th align="left"><?php echo COLLECTED;?></th>		
				</tr>
				<?php	
				$display_main_heading_t2 = 0;
			endif;	
			
			if($item_main->nDrAccount == '24' && $item_main->nAcHead == 0){
				if($display_cash_heading_t2 == 1){
					?>
					<tr><td colspan="6"><?php echo CASH; ?> </td>
					<?php
					$display_cash_heading_t2 = 0;
				}
				$total_cash = $total_cash + $item_main->totalCollected;
				
			}
			else if($item_main->nDrAccount != '24' && $item_main->nAcHead == 0){
				if($total_cash !=0){
					?>
					<tr><td colspan="5" align="right"><strong><?php echo TOTALAMT; ?></strong> </td>
					<td><?php echo $total_cash; $group_total = $group_total +  $total_cash; $grandCash = $total_cash; $total_cash = 0; ?> </td>
					</tr>
					<?php
				}
				if($display_bank_heading_t2 == 1){
					?>
					<tr><td><br /> <br /></td></tr>
					<tr><td colspan="6"><?php echo BANK; ?> </td>
					<?php		
					$display_bank_heading_t2 = 0;
				}
				$total_bank = $total_bank + $item_main->totalCollected;
			}
			else { 
				if($total_bank !=0){
					?>
					<tr><td colspan="5" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>
					<td><?php echo $total_bank; $group_total = $group_total + $total_bank ;	 $total_bank = 0;?> </td>
					</tr>
					<?php
				}
				
				if ($display_credit_heading_t2 == 1) {
					?>
					<tr><td><br /> <br /></td></tr>
					<tr><td colspan="6"><?php echo CREDITANDOTHERS; ?> </td>
					<?php			
					$display_credit_heading_t2 = 0;
				}
				$total_credit_acc = $total_credit_acc + $item_main->totalCollected;
			}
			
			?>
			
			<tr>
				<td> <?php echo $j; $j = $j + 1; ?> </td>
				<td><?php echo stripslashes($item_main->dDate); ?></td>
				<td><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='". $item_main->vLocation . "'")); ?></td>
				<?				
				switch($item_main->vTable)
				{
					case "C":
						$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item_main->nDrAccount  ."'");
					break;
					case "V":
						$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item_main->nDrAccount  ."'");
					break;
					case "AG":
						$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item_main->nDrAccount  ."'");
					break;
					case "O":
						$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item_main->nDrAccount  ."'");
					break;
					default:
						$item_main->nAcHead = $item_main->nDrAccount ;
						$drAccountName = "";
					break;
				}//end switch
		
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='". $item_main->nAcHead ."'");
				?>				
						
				<td align="left"><?php echo $subAccName ; //$item->nAcHead;//($subAccName);?></td>
				<td align="left"><?php echo $drAccountName; //$acc['nDrAccount'][$ittr]; //($accountName);?></td>
				<td><?php echo stripslashes($item_main->totalCollected); ?></td>								
			</tr>				
		<?php
		endif; // table 2		
				
				
////////////////////////// TABLE 3 - FINANCE 
		if(stripslashes($item_main->reportTable) == 't3'):
			$current_t3_row = $item_main->financeId;
			if($previous_t3_row == '')
			{
				if($total_credit_acc !=0){
					?>
					<tr><td colspan="5" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>
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
						
				<tr><td><br /><br /><br /></td></tr>	
	
				<tr>
					<th align="left"> <?php echo '#'; ?> </th>
					<th align="left"><?php echo CUSTDATE;?></th>
					<th align="left"><?php echo DOCNUMBER;?></th>
					<th align="left"><?php echo LOCATION;?></th>					
					<th align="left"><?php echo REMARKS;?></th>										
					<th align="left"><?php echo ACCOUNTNAME;?></th>
					<th align="left"><?php echo SUBACCOUNTNAME;?></th>				
			        <th align="left"><?php echo DEBIT;?></th>
			        <th align="left"><?php echo CREDIT;?></th>		
			        <th align="left"><?php echo RECEIVEDPAID;?></th>		
				</tr>
			<?php
				
			}

			if($previous_t3_row =='' || $previous_t3_row != $current_t3_row ){
	?>
				<tr><td></td></tr>
				<tr> 
					<td> <?php echo $i; $i = $i+1; ?> </td>
					
					<td><?php echo stripslashes($item_main->dDate); ?></td>
					<!-- hard coded 5 as uri segment -->
					<td> <a href="<?php echo base_url();?>index.php/<?php echo $item_main->page; ?>/5<?php echo '/e/'.$item_main->financeId;?>" title="<?php echo EDIT;?>"><?php echo stripslashes($item_main->vDocNumber); ?></a></td>
					<td><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='". $item_main->vLocation . "'")); ?></td>
					<td><?php echo stripslashes($item_main->vRemarks); ?></td>
										
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
				<td><?php echo stripslashes($this->common_model->get_field_name($tableName,'vName'," WHERE ". $columnCheck . " ='". $item_main->vACode ."'")) ; ?></td>
				<?php
				}
				else
				{?>
				<td></td>
				<?php }?>
				
		
				<?php 
				if($item_main->vMode == 'Dr')
				{ ?>
					<td><?php echo $item_main->nAmount;
					$totalDebit = $totalDebit + $item_main->nAmount?></td><td></td>
				<?php 
				}
				else if($item_main->vMode == 'Cr')
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
					if($item_main->received != '')
					{ ?>
						<td><?php echo $item_main->received;
						$totalReceivedPaid = $totalReceivedPaid + $item_main->received?></td>
					<?php 
					}
					else 
					{ ?>
						<td><?php echo "-" . $item_main->paid;
						$totalReceivedPaid = $totalReceivedPaid - $item_main->paid?></td>
					<?php 
					}
				}
				else 
				{ 
					?>
					<td></td>
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
					<tr><td colspan="5" align="right"><strong><?php echo TOTALAMT; ?> </strong></td>
					<td><?php echo $total_credit_acc; ?> </td>
					</tr>
					<tr><td></td></tr>
					<tr><td colspan="5" align="right"> <strong><?php echo GROUPTOTAL ?> </strong></td>
					<td><?php $group_total = $group_total + $total_credit_acc; echo $group_total ?> </td>
					</tr>

					<?php
					$total_credit_acc =0;
	}
	if($totalDebit != 0 || $totalCredit !=0 ): ?>
	<tr>
		<td align="right" colspan="7"><strong><?php echo TOTALAMT;?></strong></td>
		<td align="left"><?php echo stripslashes($totalDebit);?></td>
		<td align="left"><?php echo stripslashes($totalCredit);?></td>
		<td align="left"><?php echo stripslashes($totalReceivedPaid);?></td>
	</tr>
	<?php
	endif;
	?>
	<tr> <td><br /><br /></td></tr>
		<th align="right" colspan="5"></th>
		<th align="left"><?php echo DEBIT;?></th>
		<th align="left"><?php echo CREDIT;?></th>
		<th align="left"><?php echo BALANCE;?></th>
	<tr>
		<td align="right" colspan="5"><strong><?php echo CASHINHANDGRANDTOTAL;?></strong></td>
		<td align="left"><?php $grandDebit = $grandCash + $totalCredit ; echo stripslashes($grandDebit);?></td>
		<td align="left"><?php echo stripslashes($totalDebit);?></td>
		<td align="left"><?php $grandTotal = $grandDebit - $totalDebit; echo stripslashes($grandTotal);?></td>
	</tr>
	
    <?php }//end if
    else
    {
        ?>
    <tr>
        <td colspan="17" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end else
    ?>
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="12">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
