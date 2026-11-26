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
            window.location.replace('<?php echo base_url() . 'index.php/topairline_report/showlist/' . $this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/sector_airline_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/sector_airline_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/sector_airline_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
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

        <th style="border:none !important;" align="center" colspan="34">

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
        <td colspan="34" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Top Ten Airline With Sector Report </b></strong></td>
    </tr>
		<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th colspan="2" align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="34">&nbsp;</th>
    </tr>
	<style media="print">
  #bg{   
 -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
table {  
display:table-cell !important;}
</style><style media="print">  @media print {    @page {		margin-left: 0.5cm;				margin-right: 0.5cm;         }	@page:left{  @bottom-left {    content: "Page " counter(page) " of " counter(pages);  }}   }@media print{  table { page-break-inside:auto !important; }    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }    thead { display:table-header-group }    tfoot { display:table-footer-group }}</style>
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
        <th align="left">#</th>
        <th><?php echo AIRCODE; ?></th>
        <th><?php echo $echo1;echo AIRLINE; echo $echo1; ?></th>
        <th><?php echo SECTOR; ?></th>
        <th><?php echo DESTINATION; ?></th>
        <th colspan="2"><?php echo JANUARY; ?></th>
        <th colspan="2"><?php echo FEBRUARY; ?></th>
        <th colspan="2"><?php echo MARCH; ?></th>
        <th colspan="2"><?php echo APRIL; ?></th>
        <th colspan="2"><?php echo MAY; ?></th>
        <th colspan="2"><?php echo JUNE; ?></th>
        <th colspan="2"><?php echo JULY; ?></th>
        <th colspan="2"><?php echo AUGUST; ?></th>
        <th colspan="2"><?php echo SEPTEMBER; ?></th>
        <th colspan="2"><?php echo OCTOBER; ?></th>
        <th colspan="2"><?php echo NOVEMBER; ?></th>
        <th colspan="2"><?php echo DECEMBER; ?></th>
        <th colspan="2"><?php echo TOTAL; ?></th>		
    </tr>
    <tr>
        <th colspan="5"><?php echo TOPTENAIRLINE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
    </tr>
    <tr>
        <td colspan="34">&nbsp;</td>
    </tr>
    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item): 
			if($i == 1 ) { 
				?>	
				<tr>
					<td colspan="34"> <strong>TOP TEN AIRLINES</strong> </td>
				</tr>
				<?php
			}
			else if($i == 11)
			{ 
				?>	
			  	<tr>
        			<td colspan="34">&nbsp;</td>
			    </tr>
				<tr>
					<td colspan="34"> <strong>OTHER AIRLINES</strong> </td>
				</tr>
				<?php
			}
		?>
        <tr>
            
			<td align="center"> <?php echo $i;  ?> </td>
            <td align="center"> <?php echo $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'");?></td>
            <td><?php echo $this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$item->airline."'");?></td>
            <td><?php echo $item->Sector;?></td>
            <td><?php echo $item->Destination;?></td>
			
            <td align="center"><?php  if($item->Count_Jan != 0){ echo stripslashes($item->Count_Jan); } ?></td>
            <td align="right"><?php  if($item->Fare_Jan != 0){ echo stripslashes($item->Fare_Jan); } ?></td>	
					
            <td align="center"><?php  if($item->Count_Feb != 0){ echo stripslashes($item->Count_Feb); } ?></td>
            <td align="right"><?php  if($item->Fare_Feb != 0){ echo stripslashes($item->Fare_Feb); } ?></td>
			
            <td align="center"><?php  if($item->Count_Mar != 0){ echo stripslashes($item->Count_Mar); } ?></td>
            <td align="right"><?php  if($item->Fare_Mar != 0){ echo stripslashes($item->Fare_Mar); } ?></td>
			
            <td align="center"><?php  if($item->Count_Apr != 0){ echo stripslashes($item->Count_Apr); } ?></td>
            <td align="right"><?php  if($item->Fare_Apr != 0){ echo stripslashes($item->Fare_Apr); } ?></td>
			
            <td align="center"><?php  if($item->Count_May != 0){ echo stripslashes($item->Count_May); } ?></td>
            <td align="right"><?php  if($item->Fare_May != 0){ echo stripslashes($item->Fare_May); } ?></td>
			
            <td align="center"><?php  if($item->Count_Jun != 0){ echo stripslashes($item->Count_Jun); } ?></td>
            <td align="right"><?php  if($item->Fare_Jun != 0){ echo stripslashes($item->Fare_Jun); } ?></td>
			
            <td align="center"><?php  if($item->Count_Jul != 0){ echo stripslashes($item->Count_Jul); } ?></td>
            <td align="right"><?php  if($item->Fare_Jul != 0){ echo stripslashes($item->Fare_Jul); } ?></td>
			
            <td align="center"><?php  if($item->Count_Aug != 0){ echo stripslashes($item->Count_Aug); } ?></td>
            <td align="right"><?php  if($item->Fare_Aug != 0){ echo stripslashes($item->Fare_Aug); } ?></td>
			
            <td align="center"><?php  if($item->Count_Sep != 0){ echo stripslashes($item->Count_Sep); } ?></td>
            <td align="right"><?php  if($item->Fare_Sep != 0){ echo stripslashes($item->Fare_Sep); } ?></td>
			
            <td align="center"><?php  if($item->Count_Oct != 0){ echo stripslashes($item->Count_Oct); } ?></td>
            <td align="right"><?php  if($item->Fare_Oct != 0){ echo stripslashes($item->Fare_Oct); } ?></td>
			
            <td align="center"><?php  if($item->Count_Nov != 0){ echo stripslashes($item->Count_Nov); }?></td>
            <td align="right"><?php  if($item->Fare_Nov != 0){ echo stripslashes($item->Fare_Nov); } ?></td>
			
            <td align="center"><?php  if($item->Count_Dec != 0){ echo stripslashes($item->Count_Dec); } ?></td>
            <td align="right"><?php  if($item->Fare_Dec != 0){ echo stripslashes($item->Fare_Dec); } ?></td>
			
            <td align="center"><?php  if($item->total_count != 0){ echo stripslashes($item->total_count); } ?></td>
            <td align="right"><?php  if($item->total_amt != 0){ echo stripslashes($item->total_amt); } ?></td>

    	</tr>
		<?php $i = $i + 1;
        endforeach; ?>

        <?php }
    else{  ?>
        <tr>
            <td style="border:0" colspan="15" align="center"><?php echo SORRYNORECORD;?></td>
        </tr>
        <?php }//end if ?>
		<style type="text/css">
@media print {
input#btnPrint,
input#btnCancel {
display: none;
}
}
</style>
    <tr class="formButs">
       
        <td style="border:0" colspan="34">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
</table>
