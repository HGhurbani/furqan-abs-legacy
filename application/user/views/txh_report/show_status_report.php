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

<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/status_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/status_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/status_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Status Report </b></strong></td>
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
        <th align="center"><?php echo GDSPNR;?></th>
        <th align="center"><?php echo 'AIR&nbsp;&nbsp;#';?></th>
        <th align="center"><?php echo $echo1;echo TICKET;echo $echo1;?></th>
        <th align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo PASSENGERNAME;?></th>
      
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo 'COMM';?></th>
        <th align="center"><?php echo NETAMT;?></th>
      </tr>
    </thead>
    <tbody>

    <?php
    if(!empty($list_value)){
        $i= 1;
        $chkStatus = array();
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

            
            if(!in_array($item->vStatus,$chkStatus)):
					$heading_refund_type = '';
									if( $i !=1 ):
			?>
				<tr id="bg">
				<td height="25px" align="right" colspan="8"><strong><?php echo TOTALAMT;?></strong></td>
				<td align="right"><?php echo number_format((float)$fareVal_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$taxTotal_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$cmtAmount_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_per_itemgrp, 3, ".", "");?></td>
				<?php
						$fareVal_per_itemgrp 		= 0;
						$taxTotal_per_itemgrp       = 0;
						$cmtAmount_per_itemgrp      = 0;
						$netAmt_per_itemgrp			= 0;
				?>
				
				</tr>
				<?php endif; ?>

                <tr id="bg">
                    <td colspan="4">
                        <strong>
                            <?php
                            switch($item->vStatus)
                            {
                                case "N": echo "Normal";
                                break;

                                case "C": echo "Conjuction";
                                break;

                                case "R": echo "Reissued";
                                break;

                                case "V": echo "Void";
                                break;
                            }//end switch
                            ?>
                        </strong>
                    </td>
               
            <?php endif;
				$fareVal_per_itemgrp 		= $fareVal_per_itemgrp+$item->fareVal;
				$taxTotal_per_itemgrp       = $taxTotal_per_itemgrp+$item->taxTotal;
				$cmtAmount_per_itemgrp      = $cmtAmount_per_itemgrp+$item->cmtAmount;
				$netAmt_per_itemgrp			= $netAmt_per_itemgrp+$item->netAmt;
	
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
                <td height="25px" align="center"><?php echo $this->common_model->_offset + $i;?></td>
                <td colspan="1" align="center"><?php echo stripslashes($item->dDate);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
                <td align="center"><?php echo stripslashes($item->vGdspnr);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'"));?></td>
                <td colspan="1" align="left"><a href="<?php echo $path;?>"><?php echo stripslashes($item->tktNumber);?></a></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></td>
                
                <td colspan="1" align="left"><?php echo stripslashes($item->vPassenger);?><br><?php 

			$sector_t = '';
			if($item->sale_type == 'Xo')
			{

				$total_sector_rows = stripslashes($this->common_model->get_total_num_rows( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->tableId. "order by nxdid asc'"));
				$l = 0;
				for($k=0;$k<$total_sector_rows;$k++){
				$sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector', " WHERE nXoId ='".$item->tableId. "order by nxdid asc'",$k));
					$l++;
					 $sector_t[$l] = stripslashes($this->common_model->get_field_name( 'XoDetails' , 'vSector2', " WHERE nXoId ='".$item->tableId. "order by nxdid asc'",$k));
					$l++;
				}
	
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
				

			}
			else if($item->sale_type == 'Ticket') {
					$tbl_name = 'TicketDetails';
				
				$sector_t = array ( stripslashes($this->common_model->get_field_name( $tbl_name , 'vSector1', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector12', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector2' ," WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector22', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector3', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector33', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector4', " WHERE nTicketId='".$item->nTId."'")) , stripslashes($this->common_model->get_field_name($tbl_name, 'vSector44', " WHERE nTicketId='".$item->nTId."'")));
				echo $sector_t[0];
				for($j=1;$j<8;$j++) {
					if($sector_t[$j] != $sector_t[$j-1] && $sector_t[$j] != '' && $sector_t[$j] != '0' )
						echo " - " . $sector_t[$j];
				}
				
	
			}
			else if($item->sale_type == 'Hotel')
			{
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckIn, '%d/%m/%Y')"," WHERE nHVId='". $item->tableId."'"));		
				echo " - ";
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"DATE_FORMAT(dCheckOut, '%d/%m/%Y')"," WHERE nHVId='". $item->tableId."'"));	
				echo " - Nights: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNight"," WHERE nHVId='". $item->tableId."'"));	
				echo " - Rooms: ";				
				echo stripslashes($this->common_model->get_field_name('HotelVoucher',"vNoOfUnit"," WHERE nHVId='". $item->tableId."'"));	
				echo " -  ";				
				$hotel_id =	stripslashes($this->common_model->get_field_name('HotelVoucher',"vHotelName"," WHERE nHVId='". $item->tableId."'"));
				echo stripslashes($this->common_model->get_field_name('Hotel',"vName"," WHERE nHId='". $hotel_id ."'"));	


				echo " - ";				
				
				$nCity = stripslashes($this->common_model->get_field_name('HotelVoucher',"nCity"," WHERE nHVId='". $item->tableId."'"));
				echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$nCity."'"));

			}

					
		?></td>
                <td align="right"><?php echo stripslashes($item->fareVal);?></td>
                <td align="right"><?php echo stripslashes($item->taxTotal);?></td>
                <td align="right"><?php echo stripslashes($item->cmtAmount);?></td>
                <td align="right"><?php echo stripslashes($item->netAmt);?></td>
            </tr>
            <?php $i = $i + 1;
            $chkStatus[] = $item->vStatus;
            $fareVal        = $fareVal+$item->fareVal;
            $taxTotal       = $taxTotal+$item->taxTotal;
            $cmtAmount      = $cmtAmount+$item->cmtAmount;
            $netAmt         = $netAmt+$item->netAmt;
            ?>
            <?php endforeach;?>
			
	<tr id="bg">
		<td height="25px" align="right" colspan="8"><strong><?php echo TOTALAMT;?></strong></td>
		<td align="right"><?php echo number_format((float)$fareVal_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$taxTotal_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$cmtAmount_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_per_itemgrp, 3, ".", "");?></td>
		
	</tr>
	
			
    <tr id="bg">
        <td height="25px" align="right" colspan="8"><strong><?php echo GRANDTOTALAMT;?></strong></td>
        <td align="right"><?php echo number_format((float)$fareVal, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$taxTotal, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$cmtAmount, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$netAmt, 3, ".", "");?></td>
    </tr>
    <?php }
    else{
        ?>
    <tr>
        <td style="border:0" colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
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
        
        <td style="border:0" colspan="13">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
