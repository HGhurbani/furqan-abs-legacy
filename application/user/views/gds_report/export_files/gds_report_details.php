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
            window.location.replace('<?php echo base_url() . 'index.php/gds_report/showlist/' . $this->uri->segment(3);?>');
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
        <th style="border:none !important;" align="center" colspan="11">
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
	<!--<tr> <b> &nbsp; Airline Report </b> </tr>-->
	<tr>
        <td colspan="11" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; GDS Detail Repor </b></strong></td>
    </tr>
	<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
        <th align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        <th align="left" colspan="11">&nbsp;</th>
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
        <th align="center"><?php echo $echo;echo CUSTDATE; echo $echo;?></th>
        <th align="center"><?php echo AIRCODE;?></th>
        <th align="center"><?php echo TICKETNO;?></th>
        <th align="center"><?php echo PASSENGER;?></th>
        <th align="center"><?php echo $echo1;echo SECTOR; echo $echo1;?></th>
        <th align="center"><?php echo SEGMENT;?></th>
        <th align="center"><?php echo FACEFARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo NETFARE;?></th>
        <th align="center"><?php echo SALESAMT;?></th>

    </tr>
    </thead>
    <tbody>
    <?php if(!empty($list_value)){
        $i= 1;
        $chkGDSId = array();
		$heading_refund_type = '';		
        foreach($list_value as $key=>$item):
            if(!in_array($item->nGDS,$chkGDSId)):
				$heading_refund_type = '';
				if( $i !=1 ): ?>
					<tr>
						<td align="right" colspan="6"><strong><?php echo TOTALAMT;?></strong></td>
			            <td align="center"><?php echo number_format((float)$segment_per_group, 3, ".", "");?></td>						
						<td align="right"><?php echo number_format((float)$fareVal_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$taxTotal_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$netAmt_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$salesAmt_per_group, 3, ".", "");?></td>
					</tr>						
						<?php
								$segment_per_group 		= 0;
								$fareVal_per_group 		= 0;
								$taxTotal_per_group     = 0;
								$netAmt_per_group       = 0;
								$salesAmt_per_group 	= 0;
						?>
					<?php endif; ?>

			
            <tr>
                <td colspan="3"><strong><?php echo stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$item->nGDS."'"));?></strong></td>
            
                <?php endif;?>

				
		
			<?php
				if( $heading_refund_type != $item->refundtype) {
					?>
						 <td colspan="8"><strong><?php echo stripslashes($item->refundtype);
							$heading_refund_type = $item->refundtype;							
						?></strong></td>
					<?php
				}
			?>
		</tr>
		
        <tr>
            <td align="center"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="center"><?php echo stripslashes($item->dDate);?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'"));?></td>
            <td align="left"><?php echo stripslashes($item->tktNumber);?></td>
            <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
            <td align="left"><?php
	
				$sector_t = '';
				if($item->sale_type == 'Xo')
				{
					$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->txId. "order by nxdid asc'"));
					$l = 0;
					for($k=0;$k<$total_sector_rows;$k++){
					$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->txId. "order by nxdid asc'",$k));
						$l++;
						 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$item->txId. "order by nxdid asc'",$k));
						$l++;
					}
		
				}
				else if($item->sale_type == 'Ticket') {
						$tbl_name = 'TicketDetails';
					
					$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$item->txId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$item->txId."'")));
		
				}
				
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}$se='';
				if($item->vSegment == 'R' && $item->refundtype == 'refund') {
					 
					 $se = $this->common_model->get_field_name('RefundRegister','vSector'," WHERE nRId='".$item->txId."'");
					echo $se;
					 }
			
			?></td>
			
			<td align="center">
				<?php if($item->vSegment == 'T' && $item->refundtype == 'sales') {
					$seg = $this->common_model->get_field_name('TicketDetails','vSegment'," WHERE nTicketId='".$item->txId."'");
					echo $seg;
				}
				elseif($item->vSegment == 'R' && $item->refundtype == 'refund') {
					 $seg='';
					//print  $seg = $this->common_model->get_field_name('RefundRegister','vSegment'," WHERE nRId='".$item->txId."'");
					$seg =-( $this->common_model->get_field_name('RefundRegister','vSegment'," WHERE nRId='".$item->txId."'"));
					echo $seg;
					 }
					 else{
					$seg = -($this->common_model->get_field_name('TicketDetails','vSegment'," WHERE nTicketId='".$item->txId."'"));
					echo $seg;
				 }?>
			</td>
			<td align="right"><?php echo stripslashes($item->fareVal);?></td>
            <td align="right"><?php echo stripslashes($item->taxTotal);?></td>
            <td align="right"><?php echo stripslashes($item->netAmt);?></td>
            <td align="right"><?php echo stripslashes($item->salesAmt);?></td>
			
            
			</tr>
            
            <?php $i = $i + 1;
            $chkGDSId[] = $item->nGDS;
			$segment 		= $segment+ $seg;
			$fareVal        = $fareVal+$item->fareVal;
			$taxTotal       = $taxTotal+$item->taxTotal;
			$netAmt      	= $netAmt+$item->netAmt;
			$salesAmt       = $salesAmt+$item->salesAmt;
			
			$segment_per_group	= $segment_per_group  + $seg;
			$fareVal_per_group 	= $fareVal_per_group  + $item->fareVal;
			$taxTotal_per_group = $taxTotal_per_group + $item->taxTotal;
			$netAmt_per_group   = $netAmt_per_group   + $item->netAmt;
			$salesAmt_per_group	= $salesAmt_per_group + $item->salesAmt;

            ?>
            <?php endforeach;?>
			<tr>
				<td align="right" colspan="6"><strong><?php echo TOTALAMT;?></strong></td>
				<td align="center"><?php echo number_format((float)$segment_per_group, 3, ".", "");?></td>						
						<td align="right"><?php echo number_format((float)$fareVal_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$taxTotal_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$netAmt_per_group, 3, ".", "");?></td>
						<td align="right"><?php echo number_format((float)$salesAmt_per_group, 3, ".", "");?></td>
			</tr>
			<tr><td><br /></td></tr>
			<tr>
				<td align="right" colspan="6"><strong><?php echo GRANDTOTALAMT;?></strong></td>
				<td align="center"><?php echo number_format((float)$segment, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$fareVal, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$taxTotal, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$salesAmt, 3, ".", "");?></td>
			</tr>

			
        <?php }
    else{
        ?>
    <tr>
        <td colspan="11" style="border:0" align="center"><?php echo SORRYNORECORD;?></td>
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
        
        <td style="border:0" colspan="11">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
