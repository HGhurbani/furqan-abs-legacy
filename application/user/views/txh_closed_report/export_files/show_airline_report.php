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
// +----------------------------------------------------------------------+*/ 
?> 
<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script> 
<script type="text/javascript"> 
    $(document).ready(function () { 
        $('#btnCancel').click(function () { 
            window.location.replace('<?php echo base_url() . 'index.php/txh_closed_report/showlist/' . $this->uri->segment(3);?>'); 
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
	<!--<tr> <b> &nbsp; Airline Report </b> </tr>-->
	<tr>
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Airline Report </b></strong></td>
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
        <th align="center"><?php echo LOCATIONNAME;?></th>
        <th align="center"><?php echo $echo;echo GDSPNR;?></th>
        <th align="center"><?php echo 'AIR&nbsp;&nbsp;#';?></th>
        <th align="center"><?php echo TICKET;?></th>
        <th  align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo $echo1;echo $echo1;echo PASSENGERNAME; echo $echo1;?></th>
        <th align="center"><?php echo $echo1;echo SECTOR; echo $echo1;?></th>
        <th  align="center"><?php echo FARE;?></th>
      <th align="center"><?php echo TOTALTAX;?></th>
        <th align="center"><?php echo 'COMM';?></th>
        <th  align="center"><?php echo NETAMT;?></th>
<!--        <th align="left"><?php echo STATUS;?></th>  -->
        <th align="center"><?php echo SALESAMOUNT;?></th>
		<th align="center"><?php echo CASH;?></th>		
        <th align="center"><?php echo BANK;?></th>		
		<th align="center"><?php echo CREDIT;?></th>				
		<th align="center"><?php echo OTHERS;?></th>						

    </tr> 
    </thead> 
    <tbody> 
	
	
	
    <?php
    if(!empty($list_value)){
    $i = 1;
    $chkAirlineId = array();
	$chkTypeArray = array();
	
	$previous_sales_type = "";
	$previous_arlines = "";
	$heading_refund_type = '';
	
    foreach($list_value as $key=>$item):
		
		?>
		
			<?php
			if(!in_array($item->sale_type,$chkTypeArray) && ($previous_sales_type != $item->sale_type || $previous_airline != $item->airline)):
			if( $i !=1 ):
				$heading_refund_type = '';

			?>
			<tr id="bg">
				<td align="right" height="25px" colspan="9"><strong><?php echo TOTALAMT;?></strong></td>
				<td align="right"><?php echo number_format((float)$fareVal_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$taxTotal_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$cmtAmount_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrpe, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totCash_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totBank_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totCredit_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totOthers_per_itemgrp, 3, ".", "");?></td>
				
				<?php
						$fareVal_per_airline 		= 0;
						$taxTotal_per_airline       = 0;
						$cmtAmount_per_airline      = 0;
						$netAmt_per_airline			= 0;
						$totSalesAmt_per_itemgrp	= 0;
						$totCash_per_itemgrp		= 0;
						$totBank_per_itemgrp		= 0;
						$totCredit_per_itemgrp		= 0;
						$totOthers_per_itemgrp		= 0;
						
				?>
	
			</tr>
				<?php endif; 

        if(!in_array($item->airline,$chkAirlineId)): ?>
		            <tr id="bg">
                <td colspan="4"><strong><?php echo stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$item->airline."'"));?></strong></td>
       <?php endif;?>
        	<td><strong><?php echo htmlentities($item->sale_type);
			$previous_sales_type = $item->sale_type;
				$previous_airline = $item->airline;
				$heading_refund_type = '' ;
			?></strong></td>
       
        <?php endif;?>
		
				<?php
					if( $heading_refund_type != $item->refundtype) {
						?>
							 <td colspan="18"><strong><?php echo stripslashes($item->refundtype);
								$heading_refund_type = $item->refundtype;							
							?></strong></td>
						<?php
					}
				?>
		</tr>	
			
        <tr>
            <td height="25px" align="center"><?php echo $this->common_model->_offset + $i;?></td>
            <td align="center"><?php echo stripslashes($item->dDate) ?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
            <td align="center" style="word-break:break-all;"><?php echo stripslashes($item->vGdspnr);?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'"));?></td>
            <td align="left"><?php echo stripslashes($item->tktNumber);?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></td>
            <td align="left" style="word-break:break-all;"><?php echo stripslashes($item->vPassenger);?></td>
            <td align="left"><?php
	
			$sector_t = '';
			if($item->sale_type == 'Xo')
			{
				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->nTId. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->nTId. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$item->nTId. "order by nxdid asc'",$k));
					$l++;
				}
	
			}
			else if($item->sale_type == 'Ticket') {
					$tbl_name = 'TicketDetails';
				
				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$item->nTId."'")));
	
			}
			
			echo $sector_t[0];
			for($j=1;$j<8;$j++) {
				if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
					echo " - " . $sector_t[$j];
			}
		
			?></td>
            <td align="right"><?php echo stripslashes($item->fareVal);?></td>
            <td align="right"><?php echo stripslashes($item->taxTotal);?></td>
            <td align="right"><?php echo stripslashes($item->cmtAmount);?></td>
            <td align="right"><?php echo stripslashes($item->netAmt);?></td>
<!--            <td align="left"><?php $temp =	 $this->txh_closed_model->mainStatusArray();
									echo $temp[stripslashes($item->vStatus)];?></td>
-->
		
                <td align="right"><?php echo stripslashes($item->salesAmount);?></td>
                <td align="right"><?php echo stripslashes($item->Cash);?></td>
                <td align="right"><?php echo stripslashes($item->Bank);?></td>
                <td align="right"><?php echo stripslashes($item->Credit);?></td>
                <td align="right"><?php echo stripslashes($item->Others);?></td>													
        </tr>
        <?php $i = $i + 1;
        $chkAirlineId[] = $item->airline;
		$fareVal        = $fareVal+$item->fareVal;
		$taxTotal       = $taxTotal+$item->taxTotal;
		$cmtAmount      = $cmtAmount+$item->cmtAmount;
		$netAmt         = $netAmt+$item->netAmt;
        $totSalesAmt    = $totSalesAmt+$item->salesAmount;			
		$totCash        = $totCash+$item->Cash;
		$totBank        = $totBank+$item->Bank;
		$totCredit      = $totCredit+$item->Credit;
		$totOthers      = $totOthers+$item->Others;									

			$fareVal_per_airline 		= $fareVal_per_airline+$item->fareVal;
			$taxTotal_per_airline       = $taxTotal_per_airline+$item->taxTotal;
			$cmtAmount_per_airline      = $cmtAmount_per_airline+$item->cmtAmount;
			$netAmt_per_airline			= $netAmt_per_airline+$item->netAmt;
			$totSalesAmt_per_itemgrp	= $totSalesAmt_per_itemgrp+$item->salesAmount;
			$totCash_per_itemgrp		= $totCash_per_itemgrp+$item->Cash;
			$totBank_per_itemgrp		= $totBank_per_itemgrp+$item->Bank;
			$totCredit_per_itemgrp		= $totCredit_per_itemgrp+$item->Credit;
			$totOthers_per_itemgrp		= $totOthers_per_itemgrp+$item->Others;
	

        ?>

		<?php endforeach;
		
		?>
		<tr id="bg">
			<td height="25px" align="right" colspan="9"><strong><?php echo TOTALAMT;?></strong></td>
			<td align="right"><?php echo number_format((float)$fareVal_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$taxTotal_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$cmtAmount_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_per_airline, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrpe, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totCash_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totBank_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totCredit_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totOthers_per_itemgrp, 3, ".", "");?></td>
		</tr>
		
        <tr id="bg">
            <td height="25px" align="right" colspan="9"><strong><?php echo GRANDTOTALAMT;?></strong></td>
             <td align="right"><?php echo number_format((float)$fareVal, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$taxTotal, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$cmtAmount, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$netAmt, 3, ".", "");?></td>
			<td align="right"><?php echo number_format((float)$totSalesAmt, 3, ".", "");?></td>
			<td align="right"><?php echo number_format((float)$totCash, 3, ".", "");?></td>
			<td align="right"><?php echo number_format((float)$totBank, 3, ".", "");?></td>
			<td align="right"><?php echo number_format((float)$totCredit, 3, ".", "");?></td>
			<td align="right"><?php echo number_format((float)$totOthers, 3, ".", "");?></td>
			
        </tr>
    <?php }
    else{
        ?>
		
    <tr> 
        <td style="border:0" colspan="20" align="center"><?php echo SORRYNORECORD;?></td> 
    </tr> 
        <?php 
    }//end if 
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
                <td style="border:0" colspan="20"> 
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel"> 
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint"> 
        </td> 
    </tr> 
    </tbody> 
</table>
