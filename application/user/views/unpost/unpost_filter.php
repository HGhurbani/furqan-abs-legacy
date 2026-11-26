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
            window.location.replace('<?php echo base_url().'index.php/unpost_doc/showlist/'.$this->uri->segment(3);?>');
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
        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/unpost_reprt";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/unpost_reprt";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/unpost_reprt";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Unposted Report </b></strong></td>
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
        <th align="center"><?php echo SRNO;?></th>
        <th align="left"><?php echo $echo;echo CUSTDATE; echo $echo;?></th>
        <th align="center"><?php echo LOCATIONNAME;?></th>
        <th align="center"><?php echo 'AIR&nbsp;&nbsp;#';?></th>
        <th align="center"><?php echo TICKETNAME;?></th>
        <th align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo PASSENGER;?></th>
        <th align="center"><?php echo $echo1;echo SECTOR; echo $echo;?></th>
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
        $chkAirlineId = array();		$chkDateVal = array();
        foreach($list_value as $key=>$row):
		?><?php $cur=$row->sale_type;if($cur!=$prev){?>           
		<tr>                
		<td align="left" colspan="12"><strong><?php echo $row->sale_type;?></strong></td>            
		</tr>
		<?php }$prev=$cur;?>                
		
		<?php             $sector = '';            
		switch($row->sale_type)            {               
		case "Xo" :                    
		$sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->nTId."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->nTId."'");                    
		break;                
		case "Ticket" :                    
		$sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->nTId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->nTId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->nTId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->nTId."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->nTId."'");                    
		break;                
		case "Hotel" :                    
		$cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->nTId."'");                    $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->nTId."'");                    $sector  = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'. $this->common_model->get_field_name('Country','vName'," WHERE nId = " . $cityName . " " ). '-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->nTId."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->nTId."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->nTId."'");                    break;                case 'OLDT':                case 'OLDX':                    $RId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE nRDId= " . $row->nTId . " ");                    $sector  = $this->common_model->get_field_name('RefundRegister','vSector'," WHERE nRId = " . $RId . "" );                    break;                case 'OLDH':                    $cityName  = $this->common_model->get_field_name('RefundRegisterDetails','nCity'," WHERE nRDId= " . $row->nTId . " ");                    $hotel     = $this->common_model->get_field_name('RefundRegisterDetails','nHotel'," WHERE nRDId= " . $row->nTId . " ");                    $sector  = $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'") . '-' . $this->common_model->get_field_name('Country','vName'," WHERE nId = " . $cityName . " " );                    break;            }//end switch            ?>
        <tr>
            <td align="center"><?php echo $key+1;?></td>
            <td align="center"><?php echo date('d-m-Y',strtotime($row->dDate1));?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode',' WHERE nId="'.$row->nLocation.'"'));?></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId='".$row->nAirline."' "));?></td>
            <td align="left"><a href="<?php echo base_url().$row->SRlink . $row->tktId;?>"><?php echo stripslashes($row->tktNumber);?></a></td>
            <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId='".$row->vIssuedBy."' "));?></td>
            <td align="left"><?php echo stripslashes($row->vPassenger);?></td>
            <td align="left"><?php echo ($sector=='')?stripslashes($row->vSector1).'-'.stripslashes($row->vSector12).'-'.stripslashes($row->vSector2).'-'.stripslashes($row->vSector22).'-'.stripslashes($row->vSector3).'-'.stripslashes($row->vSector33).'-'.stripslashes($row->vSector4).'-'.stripslashes($row->vSector44):$sector;?></td>
            <td align="right"><?php echo stripslashes($row->fareVal);?></td>
            <td align="right"><?php echo stripslashes($row->taxTotal);?></td>
            <td align="right"><?php echo stripslashes($row->cmtAmount);?></td>
            <td align="right"><?php echo stripslashes($row->netAmt);?></td>
        </tr>

        <?php
            $totalfareVal +=  $row->fareVal;
            $totaltaxTotal +=  $row->taxTotal;
            $totalcmtAmount +=  $row->cmtAmount;
            $totalnetAmt +=  $row->netAmt;
        ?>

            <?php endforeach; ?>
            <tr>
            <td align="right" colspan="8"><strong><?php echo GRANDTOTALAMT;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalfareVal, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totaltaxTotal, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalcmtAmount, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalnetAmt, 3, ".", "");?></td>
            </tr>
    <?php }//end if
    else
    {
        ?>
    <tr>
        <td style="border:0" colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
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
        
        <td style="border:0" colspan="11">
            <input type="button" name="btnCancel" value="<?php echo BACKTOUNPOST;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
