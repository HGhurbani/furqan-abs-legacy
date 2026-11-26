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

    <tr>
        <th colspan="20">
            <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100">

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo DOCUMENTNUMBER;?></th>
        <th align="left"><?php echo TICKETNO;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo SECTOR;?></th>
        <th align="left"><?php echo FARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo COMMAMT;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
        <th align="left"><?php echo PROFIT;?></th>
        <th align="left"><?php echo PROFITPERCENTAGE;?></th>
        <th align="left"><?php echo NETREF;?></th>
        <th align="left"><?php echo PAIDREF;?></th>
        <th align="left"><?php echo PROFIT;?></th>
        <th align="left"><?php echo PROFITPERCENTAGE;?></th>
        <th align="left"><?php echo TOTALPROFIT;?></th>
        <th align="left"><?php echo PROFITPERCENTAGE;?></th>
    </tr>
    </thead>

    <tbody>

    <?php
    $sqlArray = $this->db->query($_SESSION['detail_query']);
    if ($sqlArray->num_rows() > 0)
    {
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkAirline = array();
        foreach($sqlArray->result() as $key=>$item):

            $tktNumber   = $this->manage_info_model->returnTktDetails($item->nSCId);
            $PassName    = $this->manage_info_model->returnPassengerDetails($item->nSCId);

            $getRefund = $this->manage_info_model->getRefundDetailsReport($item->vType,$item->vTktNumber);

            $profit1 = ($getRefund['NETREF']-$getRefund['PAIDREF']);
            if($getRefund['PAIDREF'] != 0):
                $profitPercent1 = round(($profit1/$getRefund['PAIDREF']*100),2);
            else:
                $profitPercent1 = '';
            endif;

            $profit = ($item->salesAmt-$item->netAmt);
            if($item->netAmt > 0)
                $profitPercent = round(($profit/$item->netAmt*100),2);
            else
                $profitPercent = 0;

            if(!in_array($item->nAirline,$chkAirline)): ?>

                <?php if($j != 0):?>
                <tr>
                    <td colspan="7" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td><?php echo $totalFareLoop;?></td>
                    <td><?php echo $totalTaxLoop;?></td>
                    <td><?php echo $totalCommLoop;?></td>
                    <td><?php echo $totalNetLoop;?></td>
                    <td><?php echo $totalSalesLoop;?></td>
                    <td><?php echo $totalprofitLoop;?></td>
                    <td><?php echo $totalprofitPerLoop;?></td>
                    <td><?php echo $totalNet2Loop;?></td>
                    <td><?php echo $totalSales2Loop;?></td>
                    <td><?php echo $totalprofit2Loop;?></td>
                    <td><?php echo $totalprofitPer2Loop;?></td>
                    <td><?php echo $totalSumprofitLoop;?></td>
                    <td><?php echo $totalSumprofitPerLoop;?></td>
                </tr>
                <tr>
                    <td colspan="20" align="right">&nbsp;</td>
                </tr>
                    <?php $flag = true; endif;?>

            <tr>
                <td colspan="20"><strong><?php echo $item->nAirline.' - '.$this->common_model->get_field_name('Airline','vTitle'," WHERE vAirlineNo='".$item->nAirline."'");?></strong></td>
            </tr>
                <?php $j = 1;endif;
            ?>
                <tr>
                    <td><?php echo $key+1;?></td>
                    <td><?php echo $item->dDate;?></td>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->vLocation."'");?></td>
                    <td><?php echo $item->vInvoiceNumber;?></td>
                    <td><?php echo $tktNumber;?></td>
                    <td><?php echo $PassName;?></td>
                    <td>&nbsp;</td>
                    <td><?php echo $item->vFare;?></td>
                    <td><?php echo $item->vTax;?></td>
                    <td><?php echo $item->vCommAmt;?></td>
                    <td><?php echo $item->netAmt;?></td>
                    <td><?php echo $item->salesAmt;?></td>
                    <td><?php echo $profit;?></td>
                    <td><?php echo $profitPercent;?></td>
                    <td><?php echo $getRefund['NETREF'];?></td>
                    <td><?php echo $getRefund['PAIDREF'];?></td>
                    <td><?php echo $profit1;?></td>
                    <td><?php echo $profitPercent1;?></td>
                    <td><?php echo $profit+$profit1;?></td>
                    <td><?php echo $profitPercent+$profitPercent1;?></td>
                </tr>
            <?php

            if($flag == true):
                $totalFareLoop          = 0;
                $totalTaxLoop           = 0;
                $totalCommLoop          = 0;
                $totalNetLoop           = 0;
                $totalSalesLoop         = 0;
                $totalprofitLoop        = 0;
                $totalprofitPerLoop     = 0;
                $totalNet2Loop          = 0;
                $totalSales2Loop        = 0;
                $totalprofit2Loop       = 0;
                $totalprofitPer2Loop    = 0;
                $totalSumprofitLoop     = 0;
                $totalSumprofitPerLoop  = 0;
                $flag            = false;
            endif;

                $totalFare          += $item->vFare;
                $totalTax           += $item->vTax;
                $totalComm          += $item->vCommAmt;
                $totalNet           += $item->netAmt;
                $totalSales         += $item->salesAmt;
                $totalprofit        += $profit;
                $totalNet2          += $getRefund['NETREF'];
                $totalSales2        += $getRefund['PAIDREF'];
                $totalSumprofit     += $profit+$profit1;

                $chkAirline[] = $item->nAirline;

                $totalFareLoop          += $item->vFare;
                $totalTaxLoop           += $item->vTax;
                $totalCommLoop          += $item->vCommAmt;
                $totalNetLoop           += $item->netAmt;
                $totalSalesLoop         += $item->salesAmt;
                $totalprofitLoop        += $profit;
                $totalNet2Loop          += $getRefund['NETREF'];
                $totalSales2Loop        += $getRefund['PAIDREF'];
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
                $totalSumprofitPer     = round((($totalprofit+$totalprofit2)/($totalNet+$totalSales2)*100),2);
            if($totalNetLoop != 0 || $totalNet2Loop != 0)
                $totalSumprofitPerLoop = round((($totalprofitLoop+$totalprofit2Loop)/($totalNetLoop+$totalSales2Loop)*100),2);

            if(($key+1) == $sqlArray->num_rows()):
                ?>
            <tr>
                <td colspan="7" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                <td><?php echo $totalFareLoop;?></td>
                <td><?php echo $totalTaxLoop;?></td>
                <td><?php echo $totalCommLoop;?></td>
                <td><?php echo $totalNetLoop;?></td>
                <td><?php echo $totalSalesLoop;?></td>
                <td><?php echo $totalprofitLoop;?></td>
                <td><?php echo $totalprofitPerLoop;?></td>
                <td><?php echo $totalNet2Loop;?></td>
                <td><?php echo $totalSales2Loop;?></td>
                <td><?php echo $totalprofit2Loop;?></td>
                <td><?php echo $totalprofitPer2Loop;?></td>
                <td><?php echo $totalSumprofitLoop;?></td>
                <td><?php echo $totalSumprofitPerLoop;?></td>
            </tr>
                <?php endif;
        endforeach;?>
        <tr>
            <td colspan="7" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td><?php echo $totalFare;?></td>
            <td><?php echo $totalTax;?></td>
            <td><?php echo $totalComm;?></td>
            <td><?php echo $totalNet;?></td>
            <td><?php echo $totalSales;?></td>
            <td><?php echo $totalprofit;?></td>
            <td><?php echo $totalprofitPer;?></td>
            <td><?php echo $totalNet2;?></td>
            <td><?php echo $totalSales2;?></td>
            <td><?php echo $totalprofit2;?></td>
            <td><?php echo $totalprofitPer2;?></td>
            <td><?php echo $totalSumprofit;?></td>
            <td><?php echo $totalSumprofitPer;?></td>
        </tr>
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="19">
            <input type="button" name="btnPrint2" value="<?php echo PRINTPAGE;?>" id="btnPrint2">
        </td>
    </tr>

        <?php }
    else{
        ?>
    <tr>
        <td colspan="20" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    </tbody>
</table>