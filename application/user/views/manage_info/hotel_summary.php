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
ini_set('memory_limit', '-1');
?>
<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/manage_info/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('.print_logo').hide();
        $('.print_desc').hide();

        $('#btnPrint').click(function(e) {
            $('.print_logo').show();
            $('.print_desc').show();
            $('.P').hide();
            $('#reportForm').printElement();
            $('.P').show();
            $('.print_logo').hide();
            $('.print_desc').hide();
            $('.P').show();
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
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Hotel Summary Report </b></strong></td>
    </tr>
	<tr>
        <th align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        <th align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
     <tr>
        <th colspan="7" align="center"><?php echo 'SALES';?></th>
        <th colspan="5" align="center"><?php echo 'REFUND';?></th>
		<th colspan="6" align="center"><?php echo 'NET';?></th>
        
    </tr>
	<style media="print">
  #bg{   
 -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
table {  
display:table-cell !important;}
</style>
<style media="print">

  @media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
}

@media print
{
  table { page-break-inside:auto !important; }
    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
}


</style>
<style>
table {  
width:100% !important;
font-size: 9px; }
td{font-size: 12px;
    border: 0px solid black;
border-collapse: collapse;
}
</style>
<?php 
$echo="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$echo1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
    <tr id="bg">
        <th colspan="2" align="center"><?php echo HOTEL;?></th>
        <!--<th align="center"><?php echo LOCATIONNAME;?></th>-->
        <th align="center"><?php echo 'Count';?></th>
        <th align="center"><?php echo NETSALES;?></th>
        <th align="center"><?php echo SALES;?></th>
        <th align="center"><?php echo PROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
         <th align="center"><?php echo 'Count';?></th>
        <th align="center"><?php echo NETREF;?></th>
        <th align="center"><?php echo PAIDREF;?></th>
        <th align="center"><?php echo PROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
         <th align="center"><?php echo 'Count';?></th>
        <th align="center"><?php echo TOTALPROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
    </tr>

    </thead>

    <tbody>
    <?php
    if(!empty($list_value_summary)){
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkAccountId = array();
        foreach($list_value_summary as $key => $item):
		if($item['vHotelName']!=NULL){
		//var_dump($list_value_summary);
		/*if($item['tblName']=='RC'){
				echo stripslashes($item['detId']);
				$item['vHotelName']=$this->common_model->get_field_name('RefundRegisterDetails','nHotel'," WHERE nRDId = " .stripslashes($item['detId']));
				
			
		}*/

            if(!in_array($item['vHotelName'],$chkAccountId)):

                $getSale['NETREF'] = $item['NETREF'];
                $getSale['PAIDREF'] = $item['PAIDREF']; 
                $getSale['salesAmt'] = $item['salesAmt'];
                $getSale['netAmt'] = $item['netAmt'];


            $profit1 = ($getSale['NETREF']-$getSale['PAIDREF']);
            if($getSale['PAIDREF'] != 0):
                $profitPercent1 = round(($profit1/$getSale['PAIDREF']*100),2);
            else:
                $profitPercent1 = '';
            endif;

            $profit = ($getSale['salesAmt']-$getSale['netAmt']);
            if($getSale['netAmt'] > 0)
                $profitPercent = round(($profit/$getSale['netAmt']*100),2);
            else
                $profitPercent = 0;

                ?>
                <tr>
                    <td colspan="2"><?php  $htl =$this->common_model->get_field_name('Hotel','vName'," WHERE nHId = " .$item['vHotelName'] ." "); echo $htl;?></td>
                    <!--<td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item['location']."'");?></td>-->
                    <td align="center"><?php 
					
					if($this->ddlLocation==0){
						$query=$this->db->query("SELECT * FROM tbl_SalesClosingDetails scd
													LEFT JOIN tbl_HotelVoucher hv
					ON scd.vTktNumber=hv.nHVId where scd.vType='Htl'AND hv.vHotelName='" .$item['vHotelName'] ."'");
					
					}
						else{
							$query=$this->db->query("SELECT * FROM tbl_SalesClosingDetails scd
													LEFT JOIN tbl_HotelVoucher hv
					ON scd.vTktNumber=hv.nHVId where scd.vType='Htl'AND hv.vHotelName='" .$item['vHotelName'] ."' AND hv.nLocation='" .$item['location'] ."'");
						}							
echo $x=$query->num_rows();
					//$x = $this->common_model->get_field_name('HotelVoucher','count(*)'," WHERE vHotelName = " .$item['vHotelName'] ." ");?></td>
                    <td align="right"><?php echo number_format((float)$getSale['netAmt'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$getSale['salesAmt'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$profit, 2, ".", "");?></td>
                    <td align="right"><?php echo $profitPercent;?></td>
                    <td align="center"><?php 
					if($this->ddlLocation==0){
					$query1 = $this->db->query("SELECT * 
FROM tbl_RefundClosingDetails rcd
LEFT JOIN tbl_RefundRegisterDetails rrd ON rcd.nTicketNumber = rrd.nTktNum
LEFT JOIN tbl_RefundRegister rr ON rrd.nRefundId = rr.nRId where rrd.nHotel='".$item['vHotelName'] ."'");
					}else{
						$query1 = $this->db->query("SELECT * 
FROM tbl_RefundClosingDetails rcd
LEFT JOIN tbl_RefundRegisterDetails rrd ON rcd.nTicketNumber = rrd.nTktNum
LEFT JOIN tbl_RefundRegister rr ON rrd.nRefundId = rr.nRId where rr.nLocation='" .$item['location'] ."'AND rrd.nHotel='".$item['vHotelName'] ."'");
				
					}
 echo $y=$query1->num_rows();
					 //$y = $this->common_model->get_field_name('RefundClosingDetails','count(*)'," WHERE vType ='Htl'");?></td>
                    <td align="right"><?php echo number_format((float)$getSale['NETREF'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$getSale['PAIDREF'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$profit1, 2, ".", "");?></td>
                    <td align="right"><?php echo $profitPercent1;?></td>
                    <td align="center"><?php echo $x-$y;?></td>
                    <td align="right"><?php echo number_format((float)$profit+$profit1, 2, ".", "");?></td>
                    <td align="right"><?php if($getSale['netAmt'] !=0 || $getSale['PAIDREF']!=0) echo  round((($profit+$profit1)/($getSale['netAmt']-$getSale['PAIDREF'])*100),2);?></td>

                </tr>
                <?php

            $graph_data[$htl] = ($getSale['netAmt'] !=0 || $getSale['PAIDREF']!=0) ?  round((($profit+$profit1)/($getSale['netAmt']-$getSale['PAIDREF'])*100),2) :0;


                $x1 += $x;
				 $y1 += $y;
				  $totalNet += $getSale['netAmt'];
                $totalSales += $getSale['salesAmt'];
                $totalprofit += $profit;
                $totalNet2 += $getSale['NETREF'];
                $totalSales2 += $getSale['PAIDREF'];
                $totalprofit2 += $profit1;
                $totalSumprofit += $profit+$profit1;

            endif;
		}
        endforeach;

        $totalprofit2           = $totalNet2-$totalSales2;

        if($totalNet !=0)
            $totalprofitPer      = round(($totalprofit/$totalNet*100),2);
        if($totalSales2 != 0)
            $totalprofitPer2     = round(($totalprofit2/$totalSales2*100),2);
        if($totalNet2 != 0 || $totalNet != 0)
            $totalSumprofitPer     = round((($totalprofit+$totalprofit2)/($totalNet-$totalSales2)*100),2);
        ?>
       
        <tr>
            <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td align="center"><?php echo $x1;?></td>
            <td align="right"><?php echo number_format((float)$totalNet, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalSales, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalprofit, 2, ".", "");?></td>
            <td align="right"><?php echo $totalprofitPer;?></td>
            <td align="center"><?php echo $y1;?></td>
            <td align="right"><?php echo number_format((float)$totalNet2, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalSales2, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalprofit2, 2, ".", "");?></td>
            <td align="right"><?php echo $totalprofitPer2;?></td>
            <td align="center"><?php echo $x1-$y1;?></td>
            <td align="right"><?php echo number_format((float)$totalSumprofit, 2, ".", "");?></td>
            <td align="right"><?php echo $totalSumprofitPer;?></td>
        </tr>

    <tr> 
        <?php
        session_start();
        $_SESSION['g_d'] = $graph_data;
        $_SESSION['title'] = "Hotel Summary";
        ?>
      </tr>

    <tr class="P">
        <td>&nbsp;</td>
        <td colspan="9">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint" class="btn-green">
            <a class="popup-link-1">
                <input type="button" name="btnDetails" value="<?php echo DETAILS;?>" id="btnDetails" class="btn-green">
            </a>
            <div class="popup-box" id="popup-box-1">
                <div class="close">X</div>
                <div class="top">
                    <h2>Details</h2>
                </div>
                <div class="bottom">
                    <div class="wa">
                        <div class="scroll">
                            <?php $this->load->view('manage_info/hotel_details');?>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="10" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    </tbody>
</table>
<div id="blackout"></div>
