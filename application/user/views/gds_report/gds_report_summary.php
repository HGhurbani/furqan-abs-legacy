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
<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/gds_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/gds_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/gds_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; GDS Summary Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th colspan="2" align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
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
        <th align="left">&nbsp;</th>
        <th align="center" colspan="5"><?php echo SALES;?></th>
        <th align="center" colspan="5"><?php echo REFUNDS;?></th>
        <th align="center" colspan="5"><?php echo NETSALES;?></th>
    </tr>
    <tr>
        <th align="center"><?php echo GDS;?></th>
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo NETAMT;?></th>
        <th align="center"><?php echo SALESAMT;?></th>
        <th align="center"><?php echo SEGMENT;?></th>
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo NETAMT;?></th>
        <th align="center"><?php echo SALESAMT;?></th>
        <th align="center"><?php echo SEGMENT;?></th>
        <th align="center"><?php echo FARE;?></th>
        <th align="center"><?php echo TAX;?></th>
        <th align="center"><?php echo NETAMT;?></th>
        <th align="center"><?php echo SALESAMT;?></th>
        <th align="center"><?php echo SEGMENT;?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):
/*            $getSales  = $this->gds_report_model->getGDSSalesTotal($item->nGDS);
            $getRefund = $this->gds_report_model->getGDSRefundTotal($item->nGDS);
*/         if(!empty($item->nGDS)):   ?>
        <tr>
            <td><?php echo stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$item->nGDS."'"));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->fareVal_s, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->taxTotal_s, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->netAmt_s, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->salesAmt_s, 3, '.', ''));?></td>
            <td align="center"><?php echo stripslashes($item->vSegment_s);?></td>
            <td align="right"><?php echo stripslashes(number_format($item->fareVal_r, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->taxTotal_r, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->netAmt_r, 3, '.', ''));?></td>
            <td align="right"><?php echo stripslashes(number_format($item->salesAmt_r, 3, '.', ''));?></td>
            <td align="center"><?php echo stripslashes($item->vSegment_r);?></td>
			<?php
				$fareVal_total  = $item->fareVal_s + $item->fareVal_r;
				$taxTotal_total = $item->taxTotal_s + $item->taxTotal_r;
				$netAmt_total   = $item->netAmt_s + $item->netAmt_r;
				$salesAmt_total = $item->salesAmt_s + $item->salesAmt_r;
				$vSegment_total = $item->vSegment_s + $item->vSegment_r;
			?>
            <td align="right"><?php echo number_format($fareVal_total, 3, '.', '');?></td>
			<td align="right"><?php echo number_format($taxTotal_total, 3, '.', '');?></td>
            <td align="right"><?php echo number_format($netAmt_total, 3, '.', '');?></td>
            <td align="right"><?php echo number_format($salesAmt_total, 3, '.', '');?></td>
            <td align="center"><?php echo $vSegment_total;?></td>
        </tr>
            <?php $i = $i + 1;
            ?>
            <?php endif; endforeach;?>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="16" style="border:0" align="center"><?php echo SORRYNORECORD;?></td>
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
        
        <td style="border:0" colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>