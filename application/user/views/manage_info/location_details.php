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
            window.location.replace('<?php echo base_url().'index.php/manage_info/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('.print_logo').hide();
        $('.print_desc').hide();

        $('#btnPrint2').click(function(e) {
            $('.print_logo').show();
            $('.print_desc').show();
            $('.formButs').hide();
            $('.printPop').printElement();
            $('.formButs').show();
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm printPop" id="reportForm">
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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Location Detail Report </b></strong></td>
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
<style media="print">

  @media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
	@page:left{
  @bottom-left {
    content: "Page " counter(page) " of " counter(pages);
  }
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
        <th align="center"><?php echo SRNO;?></th>
        <th align="center"><?php echo $echo;echo CUSTDATE; echo $echo;?></th>
        <th align="center"><?php echo $echo1;echo DOCUMENTNUMBER; echo $echo1;?></th>
        <th align="center"><?php echo $echo1;echo TICKETNO;?></th>
        <th align="center"><?php echo $echo1;echo PASSENGERNAME; echo $echo1;?></th>
        <th align="center"><?php echo $echo1;echo DESCRIPTION;?></th>
        <th align="center"><?php echo NETAMT;?></th>
        <th align="center"><?php echo SALESAMT;?></th>
        <th align="center"><?php echo PROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
        <th align="center"><?php echo NETREF;?></th>
        <th align="center"><?php echo PAIDREF;?></th>
        <th align="center"><?php echo PROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
        <th align="center"><?php echo TOTALPROFIT;?></th>
        <th align="center"><?php echo PROFITPERCENTAGE;?></th>
    </tr>
    </thead>

    <tbody>

    <?php
    if ($list_values_details > 0)
    {
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkCustomer = array();
        foreach($list_values_details as $key=>$item):

            switch($item->tktType)
            {
                case "Tkt":
                    $tktNumber  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$item->vNumber."'");
                    $PassName   = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId = '".$item->vNumber."'");
                break;

                case "Xo":
                    $tktNumber  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$item->vNumber."'");
                    $PassName   = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId = '".$item->vNumber."'");
                break;

                case "Htl":
                    $tktNumber  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$item->vNumber."'");
                    $PassName   = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId = '".$item->vNumber."'");
                break;
            }//end switch
            if($tktNumber == '')
            {
                $tktNumber = $item->vNumber;
                $PassName   = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE vTktType ='".$item->tktType."' AND nTktNum = ".$item->vNumber." AND vNewOld = 'O' ");
            }

            $profit1 = ($item->NETREF-$item->PAIDREF);
            if($item->PAIDREF != 0):
                $profitPercent1 = round(($profit1/$item->PAIDREF*100),2);
            else:
                $profitPercent1 = '';
            endif;

            $profit = ($item->salesAmt-$item->netAmt);
            if($item->netAmt > 0)
                $profitPercent = round(($profit/$item->netAmt*100),2);
            else
                $profitPercent = 0;

            if(!in_array($item->location,$chkCustomer)): ?>

                <?php if($j != 0):?>
                <tr>
                    <td colspan="6" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td align="right"><?php echo number_format((float)$totalNetLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalSalesLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalprofitLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalprofitPerLoop;?></td>
                    <td align="right"><?php echo number_format((float)$totalNet2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalSales2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalprofit2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalprofitPer2Loop;?></td>
                    <td align="right"><?php echo number_format((float)$totalSumprofitLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalSumprofitPerLoop;?></td>
                </tr>
                <tr>
                    <td colspan="16" align="right">&nbsp;</td>
                </tr>
                    <?php $flag = true; endif;?>

            <tr>
                <td colspan="16"><strong><?php echo $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$item->location."'");?></strong></td>

            </tr>
                <?php $j = 1;endif;
                     $item->netAmt =  $item->netAmt == 0 ? 0 :  $item->netAmt;
                     $item->salesAmt =  $item->salesAmt == 0 ? '' :  $item->salesAmt;
                     $item->NETREF =  $item->NETREF == 0 ? '' :  $item->NETREF;
                     $item->PAIDREF =  $item->PAIDREF == 0 ? 0 :  $item->PAIDREF;
                     $item->profit =  $item->profit == 0 ? '' :  $item->profit;
                     $item->profit1 =  $item->profit1 == 0 ? '' :  $item->profit1;
                     $item->profitPercent =  $item->profitPercent == 0 ? '' :  $item->profitPercent;
                     $item->profitPercent1 =  $item->profitPercent1 == 0 ? '' :  $item->profitPercent1;

                ?>
                <tr>
                    <td align="center"><?php echo $key+1;?></td>
                    <td align="center"><?php echo $item->dDate;?></td>
                    <td><?php echo $item->document;?></td>
                    <td><?php echo $tktNumber;?></td>
                    <td><?php echo $PassName;?></td>
                    <td><?php echo $item->vRemarks;?></td>
                    <td align="right"><?php echo $item->netAmt;?></td>
                    <td align="right"><?php echo $item->salesAmt;?></td>
                    <td align="right"><?php echo $profit;?></td>
                    <td align="right"><?php echo $profitPercent;?></td>
                    <td align="right"><?php echo $item->NETREF;?></td>
                    <td align="right"><?php echo $item->PAIDREF;?></td>
                    <td align="right"><?php echo $profit1;?></td>
                    <td align="right"><?php echo $profitPercent1;?></td>
                    <td align="right"><?php echo $profit+$profit1;?></td>
                    <td align="right"><?php if($item->netAmt !=0 || $item->PAIDREF !=0) echo round((($profit+$profit1)/($item->netAmt-$item->PAIDREF)*100),2);?></td>
                </tr>
            <?php

            if($flag == true):
                $totalNetLoop          = 0;
                $totalSalesLoop        = 0;
                $totalprofitLoop       = 0;
                $totalprofitPerLoop    = 0;
                $totalNet2Loop         = 0;
                $totalSales2Loop       = 0;
                $totalprofit2Loop      = 0;
                $totalprofitPer2Loop   = 0;
                $totalSumprofitLoop    = 0;
                $totalSumprofitPerLoop = 0;
                $flag            = false;
            endif;

                $totalNet           += $item->netAmt;
                $totalSales         += $item->salesAmt;
                $totalprofit        += $profit;
                $totalNet2          += $item->NETREF;
                $totalSales2        += $item->PAIDREF;
                $totalSumprofit     += $profit+$profit1;

                $chkCustomer[]      = $item->location;

                $totalNetLoop           += $item->netAmt;
                $totalSalesLoop         += $item->salesAmt;
                $totalprofitLoop        += $profit;
                $totalNet2Loop          += $item->NETREF;
                $totalSales2Loop        += $item->PAIDREF;
                $totalSumprofitLoop     += $profit+$profit1;

            $totalprofit2           = $totalNet2-$totalSales2;
            $totalprofit2Loop       = $totalNet2Loop-$totalSales2Loop;

            if($totalNet !=0)
                $totalprofitPer      = round(($totalprofit/$totalNet*100),2);
            if($totalSales2 != 0)
                $totalprofitPer2     = round(($totalprofit2/$totalSales2*100),2);
            if($totalNetLoop != 0)
                $totalprofitPerLoop  = round(($totalprofitLoop/$totalNetLoop*100),2);
            if($totalSales2Loop != 0)
                $totalprofitPer2Loop = round(($totalprofit2Loop/$totalSales2Loop*100),2);

            if($totalNet2 != 0 || $totalNet != 0)
                $totalSumprofitPer     = round((($totalprofit+$totalprofit2)/($totalNet-$totalSales2)*100),2);
            if($totalNetLoop != 0 || $totalNet2Loop != 0)
                $totalSumprofitPerLoop = round((($totalprofitLoop+$totalprofit2Loop)/($totalNetLoop-$totalSales2Loop)*100),2);

                ?>

        <?php endforeach;?>

                <tr>
                    <td colspan="6" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td align="right"><?php echo number_format((float)$totalNetLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalSalesLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalprofitLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalprofitPerLoop;?></td>
                    <td align="right"><?php echo number_format((float)$totalNet2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalSales2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalprofit2Loop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalprofitPer2Loop;?></td>
                    <td align="right"><?php echo number_format((float)$totalSumprofitLoop, 3, ".", "");?></td>
                    <td align="right"><?php echo $totalSumprofitPerLoop;?></td>
                </tr>

        <tr>
            <td colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalNet, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalSales, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalprofit, 3, ".", "");?></td>
            <td align="right"><?php echo $totalprofitPer;?></td>
            <td align="right"><?php echo number_format((float)$totalNet2, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalSales2, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalprofit2, 3, ".", "");?></td>
            <td align="right"><?php echo $totalprofitPer2;?></td>
            <td align="right"><?php echo number_format((float)$totalSumprofit, 3, ".", "");?></td>
            <td align="right"><?php echo $totalSumprofitPer;?></td>
        </tr>

    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="15">
            <input type="button" name="btnPrint2" value="<?php echo PRINTPAGE;?>" id="btnPrint2">
        </td>
    </tr>

        <?php }
    else{
        ?>
    <tr>
        <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    </tbody>
</table>
