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
            window.location.replace('<?php echo base_url() . 'index.php/customer_report/showlist/' . $this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/hotel_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/hotel_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/hotel_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
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
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
	<tr>
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Customer Report By Hotel </b></strong></td>
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
</style><style media="print">  @media print {    @page {		margin-left: 0.5cm;				margin-right: 0.5cm;         }	@page:left{  @bottom-left {    content: "Page " counter(page) " of " counter(pages);  }}   }@media print{  table { page-break-inside:auto !important; }    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }    thead { display:table-header-group }    tfoot { display:table-footer-group }}#dt{width:8%!important;	}#doc{width:15%!important;	}#txt{	text-transform: capitalize;}#ac{	text-transform: capitalize;} /* #bg{ background-color:#CCC !important; }#des{	width:25%!important;}*/</style>
<style>
table {  
width:100% !important;
font-size: 9px; }
td{font-size: 12px;
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
        <th align="center"><?php echo LOCATIONNAME;?></th>
        <th align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo VOUCHERID;?></th>
        <th align="center"><?php echo $echo1;echo PASSENGERNAME; echo $echo;?></th>
        <th align="center"><?php echo $echo1;echo CITYNAME; echo $echo;?></th>
        <th align="center"><?php echo $echo1;echo COUNTRYNAME; echo $echo;?></th>
        <th align="center"><?php echo $echo1;echo CHECKIN;?></th>
        <th align="center"><?php echo $echo1;echo CHECKOUT;?></th>
        <th align="center"><?php echo NIGHTS;?></th>
		<th align="center"><?php echo NOOFROOMS;?></th>
        <th align="center" style="word-break:break-all;"><?php echo AMOUNTNIGHT;?></th>
        <th align="center"><?php echo TOTALAMOUNT;?></th>
        <th align="center"><?php echo SALESAMOUNT;?></th>
		<th align="center"><?php echo PROFIT;?></th>		
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>		
		</tr>
    </thead>
    <tbody>
    
    <?php
    if(!empty($list_value)){
    $i= 1;
    $chkHotelId = array();
	$chkAccount = array();
	$heading_refund_type = '';
			
    foreach($list_value as $key=>$item): 
		

    
			// DISPLAY ACCOUNT
			if(!in_array($item->nDrAccount,$chkAccount)):
				//DISPLAY TOTAL OF ACCOUNT
				if( $i !=1 ):
					?>
					<tr>
					<td align="right" colspan="10"><strong><?php echo TOTALAMT;?></strong></td>
					<td align="center"><?php echo $Night_per_itemgrp;?></td>
					<td align="center"><?php echo $Rooms_per_itemgrp;?></td>
					<td align="right"><?php echo number_format((float)$AmountPerNight_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$TotalAmt_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$totProfit_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo round($totProfitPercent_per_itemgrp,3); echo '%';?></td> 
					<?php
						$Night_per_itemgrp 		= 0;
						$Rooms_per_itemgrp       = 0;
						$AmountPerNight_per_itemgrp      = 0;
						$TotalAmt_per_itemgrp			= 0;
						$totSalesAmt_per_itemgrp	= 0;
						$totProfit_per_itemgrp		= 0;
						$totProfitPercent_per_itemgrp		= 0; ?>
				
					</tr>
				<?php endif; 

				$chkHotelId = array();
				switch($item->vTable){
					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->nDrAccount  ."'"); break;
					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->nDrAccount  ."'"); break;
					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->nDrAccount  ."'"); break;
					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->nDrAccount  ."'"); break;
					default: $item->nAcHead = $item->nDrAccount ; $drAccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->nAcHead."'");
				if($drAccountName != '' || !empty($drAccountName)){
						?><tr> <td colspan="18"><strong><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td></tr> <?php
				}else { ?>
						<tr><td colspan="18"><strong><?php echo $subAccName; ?></strong></td> </tr><?php 
				}												
			endif;
		
		// DISPLAY HOTEL	
		if(!in_array($item->vHotelName,$chkHotelId)):
				$heading_refund_type = ''; ?>
		
            <tr>
			<td colspan="5"><strong>
			<?php echo stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$item->vHotelName."'"));?></strong></td>
			 <?php
		endif; ?>
		
		<!-- DISPLAY REFUND HEADING -->
		 <?php if( $heading_refund_type != $item->refundtype) {	?>
						<td colspan="22"><strong><?php echo stripslashes($item->refundtype);
						$heading_refund_type = $item->refundtype;							
						?></strong></td> <?php
					} ?>
		</tr>	


		<!-- DISPLAY DATA -->
		<tr>
            <td align="center"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="center"><?php echo stripslashes($item->dDate);?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$item->vIssuedBy."'"));?></td>
            <td align="left"><a href="<?php echo base_url(). "" . $item->link;?>"><?php echo stripslashes($item->vTicktManual);?></a></td>
            <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
			<td align="left"><?php echo stripslashes($item->City);?></td>
            <td align="left"><?php echo stripslashes($item->Country);?></td>
            <td align="center"><?php echo stripslashes($item->dCheckIn);?></td>
            <td align="center"><?php echo stripslashes($item->dCheckOut);?></td>
            <td align="center"><?php echo stripslashes($item->vNight);?></td>
            <td align="center"><?php echo stripslashes($item->vNoOfUnit);?></td>			
            <td align="right"><?php echo stripslashes($item->vTotalRTDC);?></td>
            <td align="right"><?php echo stripslashes($item->netAmt);?></td>
			<td align="right"><?php echo stripslashes($item->salesAmount);?></td>
			<td align="right"><?php echo stripslashes($item->Profit);?></td>
			<td align="right"><?php echo round(stripslashes($item->ProfitPercent),3);?></td>
	</tr>
        <?php $i = $i + 1;
		$chkAccount[]   = $item->nDrAccount;
        $chkHotelId[]   = $item->vHotelName;
		
		$Night_per_itemgrp 				= $Night_per_itemgrp+$item->vNight;
		$Rooms_per_itemgrp       		= $Rooms_per_itemgrp+$item->vNoOfUnit;
		$AmountPerNight_per_itemgrp     = $AmountPerNight_per_itemgrp+$item->vTotalRTDC;
		$TotalAmt_per_itemgrp			= $TotalAmt_per_itemgrp+$item->netAmt;
		$totSalesAmt_per_itemgrp	= $totSalesAmt_per_itemgrp+$item->salesAmount;
		$totProfit_per_itemgrp		= $totProfit_per_itemgrp+$item->Profit;
		//$totProfitPercent_per_itemgrp		= $totProfitPercent_per_itemgrp+$item->ProfitPercent; 
		$totProfitPercent_per_itemgrp		= (($totProfit_per_itemgrp)/($TotalAmt_per_itemgrp))*100;

		$Night        	= $Night+$item->vNight;
		$Rooms       	= $Rooms+$item->vNoOfUnit;
		$AmountPerNight = $AmountPerNight+$item->vTotalRTDC;
		$TotalAmt       = $TotalAmt+$item->netAmt;
		$totSalesAmt    = $totSalesAmt+$item->salesAmount;			
		$totProfit        = $totProfit+$item->Profit;
		$totProfitPercent        = $totProfitPercent+$item->ProfitPercent; 
		?>

        <?php endforeach;?>
		
	<tr>
		<td align="right" colspan="10"><strong><?php echo TOTALAMT;?></strong></td>
		<td align="center"><?php echo $Night_per_itemgrp;?></td>
		<td align="center"><?php echo $Rooms_per_itemgrp;?></td>
		<td align="right"><?php echo number_format((float)$AmountPerNight_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$TotalAmt_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrp, 3, ".", "");?></td>
					<td align="right"><?php echo number_format((float)$totProfit_per_itemgrp, 3, ".", "");?></td>
		<td align="right"><?php echo round($totProfitPercent_per_itemgrp,3); echo '%';?></td> 
	</tr>
	<tr><td><br /></td></tr>
    <tr>
        <td align="right" colspan="10"><strong><?php echo GRANDTOTALAMT;?></strong></td>
        <td align="center"><?php echo $Night;?></td>
        <td align="center"><?php echo $Rooms;?></td>
        <td align="right"><?php echo number_format((float)$AmountPerNight, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$TotalAmt, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totSalesAmt, 3, ".", "");?></td>		
        <td align="right"><?php echo number_format((float)$totProfit, 3, ".", "");?></td>
		<?php $totProfitPercent		= (($totProfit)/($TotalAmt))*100;?>
			<td align="right"><?php echo round($totProfitPercent, 3); echo '%';?></td> 
        
		</tr>
    <?php }
    else{
        ?>
    <tr>
        <td style="border:0" colspan="20" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
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
        
        <td style="border:0" colspan="18">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
