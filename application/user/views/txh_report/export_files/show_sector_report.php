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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Sector Report </b></strong></td>
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
        <th align="center"><?php echo GDSPNR;?></th>
        <th align="center"><?php echo 'AIR&nbsp;&nbsp;#';?></th>
        <th align="center"><?php echo $echo1;echo TICKET;echo $echo1;?></th>
        <th align="center"><?php echo ISSUEDBY;?></th>
        <th align="center"><?php echo PASSENGERNAME;?></th>
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo 'COMM';?></th>
        <th align="center"><?php echo NETAMT;?></th>
        <th align="center"><?php echo STATUS;?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    if(!empty($list_value)){
        $i= 1;
        $chkSector = array();
        foreach($list_value as $key=>$item):

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

            //if(!in_array($item->account,$chkSector)):?>
                <tr id="bg">
                    <td><strong><?php echo $item->Sector1;?></strong></td>
                    <td><strong><?php echo $item->Sector12;?></strong></td>
                    <td><strong><?php echo $item->Sector2;?></strong></td>
                    <td><strong><?php echo $item->Sector22;?></strong></td>
                    <td><strong><?php echo $item->Sector3;?></strong></td>
                    <td><strong><?php echo $item->Sector33;?></strong></td>
                    <td><strong><?php echo $item->Sector4;?></strong></td>
                    <td><strong><?php echo $item->Sector44;?></strong></td>
                    <td colspan="9">&nbsp;</td>
                </tr>
            <?php// endif;?>
            <tr>
                <td height="25px" align="center"><?php echo $this->common_model->_offset + $i;?></td>
                <td colspan="1" align="center"><?php echo stripslashes($item->dDate);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
                <td align="center"><?php echo stripslashes($item->vGdspnr);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'"));?></td>
                <td colspan="1" align="left"><?php echo stripslashes($item->tktNumber);?></td>
                <td align="center"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></td>
                <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
                <td align="right"><?php echo stripslashes($item->fareVal);?></td>
                <td align="right"><?php echo stripslashes($item->taxTotal);?></td>
                <td align="right"><?php echo stripslashes($item->cmtAmount);?></td>
                <td align="right"><?php echo stripslashes($item->netAmt);?></td>
                <td align="left"><?php $temp =	 $this->txh_report_model->mainStatusArray();
									echo $temp[stripslashes($item->vStatus)];?></td>
            </tr>
            <?php $i = $i + 1;
            $chkSector[] = $item->account;
            $fareVal        = $fareVal+$item->fareVal;
            $taxTotal       = $taxTotal+$item->taxTotal;
            $cmtAmount      = $cmtAmount+$item->cmtAmount;
            $netAmt         = $netAmt+$item->netAmt;
            ?>
            <?php endforeach;?>
    <tr id="bg">
        <td height="25px" align="right" colspan="9"><strong><?php echo TOTALAMT;?></strong></td>
        <td align="right"><?php echo number_format((float)$fareVal, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$taxTotal, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$cmtAmount, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$netAmt, 3, ".", "");?></td>
        <td align="right">&nbsp;</td>
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