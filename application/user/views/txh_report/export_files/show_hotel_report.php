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
            window.location.replace('<?php echo base_url() . 'index.php/txh_report/showlist/' . $this->uri->segment(3);?>');
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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Hotel Report </b></strong></td>
    </tr>
		<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th align="left"><?php echo TODATE; echo "&nbsp"; echo ":"; echo "&nbsp"; echo $this->txtToDate;?></th>
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
        <th align="left">#</th>
        <th align="left"><?php echo $echo;echo CUSTDATE; echo $echo;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo VOUCHERID;?></th>
        <th align="center"><?php echo $echo1;echo PASSENGERNAME; echo $echo1;?></th>
        <th align="center"><?php echo $echo1;echo $echo1;echo COUNTRYNAME; echo $echo1;?></th>
        <th align="center"><?php echo $echo1;echo $echo1;echo CITYNAME; echo $echo1;?></th>
        <th align="center"><?php echo $echo;echo $echo;echo CHECKIN; echo $echo;?></th>
        <th align="center"><?php echo $echo;echo $echo;echo CHECKOUT; echo $echo;?></th>
        <th align="center"><?php echo NIGHTS;?></th>
		<th align="center"><?php echo NOOFROOMS;?></th>
        <th align="center"><?php echo AMOUNTNIGHT;?></th>
        <th align="center"><?php echo TOTALAMOUNT;?></th>
    </tr>
    </thead>
    <tbody>
    
    <?php
    if(!empty($list_value)){
    $i= 1;
    $chkHotelId = array();
	$heading_refund_type = '';
    foreach($list_value as $key=>$item):
            if($item->refundtype == "refund" ) {
                $path = base_url().'index.php/refund_register/editRefund/4/e/'.$item->tableId;
            }else{

                switch($item->sale_type)
                {
                    case "Ticket":
                            $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$item->tableId;
                        break;

                    case "Xo":
                        $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$item->tableId;
                        break;

                    case "Hotel":
                        $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$item->tableId;
                        break;
                }//end switch
            } // end if

        
        if(!in_array($item->vHotelName,$chkHotelId)):
				$heading_refund_type = '';
								if( $i !=1 ):
			?>
				<tr id="bg">
				<td height="25px" align="right" colspan="10"><strong><?php echo TOTALAMT;?></strong></td>
				<td align="center"><?php echo $Night_per_itemgrp;?></td>
				<td align="center"><?php echo $Rooms_per_itemgrp;?></td>
				<td align="right"><?php echo number_format((float)$AmountPerNight_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$TotalAmt_per_itemgrp, 3, ".", "");?></td>
				<?php
						$Night_per_itemgrp 		= 0;
						$Rooms_per_itemgrp       = 0;
						$AmountPerNight_per_itemgrp      = 0;
						$TotalAmt_per_itemgrp			= 0;
				?>
				
				</tr>
				<?php endif; ?>


		
          <tr id="bg">
                <td colspan="5"><strong><?php echo stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$item->vHotelName."'"));?></strong></td>
           
            <?php endif;
				$Night_per_itemgrp 				= $Night_per_itemgrp+$item->vNight;
				$Rooms_per_itemgrp       		= $Rooms_per_itemgrp+$item->vNoOfUnit;
				$AmountPerNight_per_itemgrp     = $AmountPerNight_per_itemgrp+$item->vTotalRTDC;
				$TotalAmt_per_itemgrp			= $TotalAmt_per_itemgrp+$item->vTotalDC;
	
			?>

			
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
            <td height="25px" align="center"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="center"><?php echo stripslashes($item->dDate);?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$item->vIssuedBy."'"));?></td>
            <td align="left"><?php echo stripslashes($item->vTicktManual);?></td>
            <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$item->nCountry."'"));?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$item->nCity."'")); ?></td>
            <td align="center"><?php echo stripslashes($item->dCheckIn);?></td>
            <td align="center"><?php echo stripslashes($item->dCheckOut);?></td>
            <td align="center"><?php echo stripslashes($item->vNight);?></td>
            <td align="center"><?php echo stripslashes($item->vNoOfUnit);?></td>			
            <td align="right"><?php echo stripslashes($item->vTotalRTDC);?></td>
            <td align="right"><?php echo stripslashes($item->vTotalDC);?></td>
        </tr>
        <?php $i = $i + 1;
        $chkHotelId[] = $item->vHotelName;
		$Night        	= $Night+$item->vNight;
		$Rooms       	= $Rooms+$item->vNoOfUnit;
		$AmountPerNight = $AmountPerNight+$item->vTotalRTDC;
		$TotalAmt       = $TotalAmt+$item->vTotalDC;


        ?>
        <?php endforeach;?>
		
	<tr id="bg">
		<td height="25px" align="right" colspan="10"><strong><?php echo TOTALAMT;?></strong></td>
		<td align="center"><?php echo $Night_per_itemgrp;?></td>
				<td align="center"><?php echo $Rooms_per_itemgrp;?></td>
				<td align="right"><?php echo number_format((float)$AmountPerNight_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$TotalAmt_per_itemgrp, 3, ".", "");?></td>
	</tr>
	
    <tr id="bg">
        <td height="25px" align="right" colspan="10"><strong><?php echo GRANDTOTALAMT;?></strong></td>
        <td align="center"><?php echo $Night;?></td>
        <td align="center"><?php echo $Rooms;?></td>
        <td align="right"><?php echo number_format((float)$AmountPerNight, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$TotalAmt, 3, ".", "");?></td>
    </tr>
	
	
    <?php }
    else{
        ?>
    <tr>
        <td style="border:0" colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
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
        
        <td style="border:0" colspan="13">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>

