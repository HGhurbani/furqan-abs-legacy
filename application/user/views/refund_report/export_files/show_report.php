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
    $(document).ready(function () {
        $('#btnCancel').click(function () {
            window.location.replace('<?php echo base_url() . 'index.php/refund_report/showlist/' . $this->uri->segment(3);?>');
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
   <tr style="border:none !important;">

        <th style="border:none !important;" align="center" colspan="20">

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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Refund Report</b></strong></td>
    </tr>
		<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th colspan="2" align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
	<style media="print">
  #bg{   
 -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
table {  
display:table-cell !important;}
</style>
<style>
table {  
width:100% !important;
font-size: 9px; }
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>
<?php 
$echo="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$echo1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
    <tr id="bg">
        <th align="center">#</th>
        <th align="left"><?php echo $echo;echo CUSTDATE; echo $echo;?></th>
        <th align="center"><?php echo $echo1;echo DOCNUMBER;?></th>
        <th align="center"><?php echo AIRCODE;?></th>
        <th align="center"><?php echo $echo;echo TICKETNAME;?></th>
        <th align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo $echo1;echo PASSENGERNAME; echo $echo1;?></th>
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo SALESNETAMT;?></th>
        <th align="center"><?php echo VENDORCHANGE;?></th>
        <th align="center"><?php echo REFUNDNETAMT;?></th>		
        <th align="center"><?php echo RECEIVEDAMOUNT;?></th>
        <th align="center"><?php echo OFFICECHARGE;?></th>
        <th align="center"><?php echo PAIDAMOUNT;?></th>
        <th align="center"><?php echo $echo1;echo ACCOUNTNAME; echo $echo1;?></th>
		<?php
        if(!empty($list_value)){
            foreach($list_value as $key=>$item):
                if($item->register_status !=''){
                    ?>
                        <th align="left"><?php echo STATUS;?></th>
                        <?php	
                }
            break;
            endforeach;
        }
		?>		
    </tr>
    </thead>
    <tbody>
   <?php
    if(!empty($list_value)){
        $i= 1;
        $chkType = array();
		$sub_group_Fare = 0 ; 
		$sub_group_Tax = 0 ; 
		$sub_group_Net = 0 ; 
		$sub_group_Vendor = 0 ; 
		$sub_group_rf_Net = 0 ; 
		$sub_group_Received = 0 ; 
		$sub_group_Offc = 0 ; 
		$sub_group_Paid = 0 ;
		
		$group_Fare = 0 ; 
		$group_Tax = 0 ; 
		$group_Net = 0 ; 
		$group_Vendor = 0 ; 
		$group_rf_Net = 0 ; 
		$group_Received = 0 ; 
		$group_Offc = 0 ; 
		$group_Paid = 0 ;
		$heading_refund_type = '';
				
        foreach($list_value as $key=>$item):
            if(!in_array($item->sale_type,$chkType)):

				if(!($sub_group_Fare == 0 && $sub_group_Tax == 0 && $sub_group_Net == 0 && $sub_group_Vendor == 0 && $sub_group_rf_Net == 0 && $sub_group_Received == 0 && $sub_group_Offc == 0 && $sub_group_Paid == 0 )):
		?>
					<tr>
						<td align="right" colspan="7"><strong><?php echo  REFUND . ' ' . $heading_refund_type . ' ' . TOTALAMT;?></strong></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Fare), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Tax), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Net), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Vendor), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_rf_Net), 3, ".", "");?></td>		
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Received), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Offc), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Paid), 3, ".", "");?></td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr> <td></td></tr>
				<?php endif; 
				$heading_refund_type = '';
				$sub_group_Fare = 0 ; 
				$sub_group_Tax = 0 ; 
				$sub_group_Net = 0 ; 
				$sub_group_Vendor = 0 ; 
				$sub_group_rf_Net = 0 ; 
				$sub_group_Received = 0 ; 
				$sub_group_Offc = 0 ; 
				$sub_group_Paid = 0 ;

				
				if(!($group_Fare == 0 && $group_Tax == 0 && $group_Net == 0 && $group_Vendor == 0 && $group_rf_Net == 0 && $group_Received == 0 && $group_Offc == 0 && $group_Paid == 0 )):
				?>
					<tr>
						<td align="right" colspan="7"><strong><?php echo TOTALAMT;?></strong></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Fare), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Tax), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Net), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Vendor), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_rf_Net), 3, ".", "");?></td>		
						<td align="right"><?php echo number_format((float)stripslashes($group_Received), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Offc), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Paid), 3, ".", "");?></td>
						<td align="right">&nbsp;</td>
					</tr>
					<tr> <td></td></tr>					
				<?php
					$group_Fare = 0 ; 
					$group_Tax = 0 ; 
					$group_Net = 0 ; 
					$group_Vendor = 0 ; 
					$group_rf_Net = 0 ; 
					$group_Received = 0 ; 
					$group_Offc = 0 ; 
					$group_Paid = 0 ;
				endif; ?>
                <tr>
					<?php 
						if($item->sale_type == 'Tkt'){
							?>
		                    <td colspan="17"><strong><?php  echo TICKET;?></strong></td>
							<?php
						}
						else if($item->sale_type == 'Htl'){
							?>
		                    <td colspan="17"><strong><?php  echo HOTEL;?></strong></td>
							<?php
						}
						else if($item->sale_type == 'Xo'){
							?>
		                    <td colspan="17"><strong><?php  echo XO;?></strong></td>
							<?php
						}
						?>
						
                </tr>
            <?php endif;?>
			
			<tr>
				<?php
					if( $heading_refund_type != $item->rfStatus):
						if( $heading_refund_type != ''):
							if(!($sub_group_Fare == 0 && $sub_group_Tax == 0 && $sub_group_Net == 0 && $sub_group_Vendor == 0 && $sub_group_rf_Net == 0 && $sub_group_Received == 0 && $sub_group_Offc == 0 && $sub_group_Paid == 0 )):
					?>
								<tr>
									<td align="right" colspan="7"><strong><?php echo  REFUND . ' ' . $heading_refund_type . ' ' . TOTALAMT;?></strong></td>
									<td align="right"><?php echo number_format((float)stripslashes($sub_group_Fare), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Tax), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Net), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Vendor), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_rf_Net), 3, ".", "");?></td>		
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Received), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Offc), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Paid), 3, ".", "");?></td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr> <td></td></tr>								
							<?php endif; 
									$sub_group_Fare = 0 ; 
									$sub_group_Tax = 0 ; 
									$sub_group_Net = 0 ; 
									$sub_group_Vendor = 0 ; 
									$sub_group_rf_Net = 0 ; 
									$sub_group_Received = 0 ; 
									$sub_group_Offc = 0 ; 
									$sub_group_Paid = 0 ;

						endif;	
						?>
						 <td colspan="17"><strong>
						 <?php
						echo 'REFUND ' . $item->rfStatus;
						$heading_refund_type = $item->rfStatus;							
							?></strong></td>
						<?php
					endif;
				?>
			</tr>
			
            <tr>
                <td align="center"><?php echo $this->common_model->_offset + $i;?></td>
                <td align="center"><?php echo stripslashes($item->dDate);?></td>
                <td align="left"><?php echo stripslashes($item->vDocNumber);?></td>
                <td align="center"><?php echo $item->airline;?></td>
                <td align="left"><?php echo stripslashes($item->tktNumber);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));
				?></td>
                <td align="left" style="word-break:break-all;"><?php echo stripslashes($item->vPassenger);?></td>				
                <td align="right"><?php echo stripslashes($item->fareVal);?></td>
                <td align="right"><?php echo stripslashes($item->taxTotal);?></td>
                <td align="right"><?php echo stripslashes($item->netAmt);?></td>
                <td align="right"><?php echo stripslashes($item->vendor);?></td>
                <td align="right"><?php echo stripslashes($item->rf_netAmt);?></td>				
                <td align="right"><?php echo stripslashes($item->received);?></td>
                <td align="right"><?php echo stripslashes($item->officeCharge);?></td>
                <td align="right"><?php echo stripslashes($item->paidAmt);?></td>
				<?php
				
				   switch($item->vTable)
					{
						case "C":
							$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". stripslashes($item->account)  ."'");
						break;
						case "V":
							$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". stripslashes($item->account)  ."'");
						break;
						case "AG":
							$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". stripslashes($item->account)  ."'");
						break;
						case "O":
							$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". stripslashes($item->account)  ."'");
						break;
						default:
							$drAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='". stripslashes($item->account) ."'"); 
						break;
					}//end switch
												
				?>				
				
                <td align="left"><?php echo $drAccountName;?></td>
				<?php
					if($item->register_status !=''){
					?>
						<td align="left"><?php echo $item->register_status;?></td>
						<?php	
					}
					?>
            </tr>
            <?php $i= $i+1;
            $chkType[]      = $item->sale_type;
            $totalFare      = $totalFare+$item->fareVal;
            $totalTax       = $totalTax+$item->taxTotal;
            $totalNet       = $totalNet+$item->netAmt;
            $totalVendor    = $totalVendor+$item->vendor;
            $totalrf_Net    = $totalrf_Net+$item->rf_netAmt;			
            $totalReceived  = $totalReceived+$item->received;
            $totalOffc      = $totalOffc+$item->officeCharge;
            $totalPaid      = $totalPaid+$item->paidAmt;

            $group_Fare      = $group_Fare+$item->fareVal;
            $group_Tax       = $group_Tax+$item->taxTotal;
            $group_Net       = $group_Net+$item->netAmt;
            $group_Vendor    = $group_Vendor+$item->vendor;
            $group_rf_Net    = $group_rf_Net+$item->rf_netAmt;			
            $group_Received  = $group_Received+$item->received;
            $group_Offc      = $group_Offc+$item->officeCharge;
            $group_Paid      = $group_Paid+$item->paidAmt;
			
            $sub_group_Fare      = $sub_group_Fare+$item->fareVal;
            $sub_group_Tax       = $sub_group_Tax+$item->taxTotal;
            $sub_group_Net       = $sub_group_Net+$item->netAmt;
            $sub_group_Vendor    = $sub_group_Vendor+$item->vendor;
            $sub_group_rf_Net    = $sub_group_rf_Net+$item->rf_netAmt;			
            $sub_group_Received  = $sub_group_Received+$item->received;
            $sub_group_Offc      = $sub_group_Offc+$item->officeCharge;
            $sub_group_Paid      = $sub_group_Paid+$item->paidAmt;
            ?>

        <?php endforeach;


	if(!($sub_group_Fare == 0 && $sub_group_Tax == 0 && $sub_group_Net == 0 && $sub_group_Vendor == 0 && $sub_group_rf_Net == 0 && $sub_group_Received == 0 && $sub_group_Offc == 0 && $sub_group_Paid == 0 )):
	?>
		<tr>
			<td align="right" colspan="7"><strong><?php echo  REFUND . ' ' . $heading_refund_type . ' ' . TOTALAMT;?></strong></td>
			<td align="right"><?php echo number_format((float)stripslashes($sub_group_Fare), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Tax), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Net), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Vendor), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_rf_Net), 3, ".", "");?></td>		
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Received), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Offc), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($sub_group_Paid), 3, ".", "");?></td>
			<td align="right">&nbsp;</td>
		</tr>
	<?php endif; ?>
	<tr><td></td></tr>
	<?php if(!($group_Fare == 0 && $group_Tax == 0 && $group_Net == 0 && $group_Vendor == 0 && $group_rf_Net == 0 && $group_Received == 0 && $group_Offc == 0 && $group_Paid == 0 )):
	?>
		<tr>
			<td align="right" colspan="7"><strong><?php echo TOTALAMT;?></strong></td>
			<td align="right"><?php echo number_format((float)stripslashes($group_Fare), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Tax), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Net), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Vendor), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_rf_Net), 3, ".", "");?></td>		
						<td align="right"><?php echo number_format((float)stripslashes($group_Received), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Offc), 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)stripslashes($group_Paid), 3, ".", "");?></td>
			<td align="right">&nbsp;</td>
		</tr>
	<?php endif; ?>
	<tr> <td> </td></tr>
    <tr>
        <td align="right" colspan="7"><strong><?php echo GRANDTOTALAMT;?></strong></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalFare), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalTax), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalNet), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalVendor), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalrf_Net), 3, ".", "");?></td>		
        <td align="right"><?php echo number_format((float)stripslashes($totalReceived), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalOffc), 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)stripslashes($totalPaid), 3, ".", "");?></td>
        <td align="right">&nbsp;</td>
    </tr>
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
       
        <td style="border:0" colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
