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

    <tr>
        <th colspan="12">
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
        <th align="left"><?php echo SALESTYPE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo NETSALES;?></th>
        <th align="left"><?php echo SALES;?></th>
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
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):

            $getRefund = $this->manage_info_model->getRefundSummaryReport($item->vType);

            $profit1 = ($getRefund['NETREF']-$getRefund['PAIDREF']);
            if($getRefund['PAIDREF'] != 0):
                $profitPercent1 = round(($profit1/$getRefund['PAIDREF']*100),2);
            else:
                $profitPercent1 = '';
            endif;

            $profit = ($item->salesAmt - $item->netAmt);
            if($item->netAmt > 0)
                $profitPercent = round(($profit/$item->netAmt*100),2);
            else
                $profitPercent = 0;
            ?>
                <tr>
                    <td><?php echo $item->vType;?></td>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->vLocation."'");?></td>
                    <td><?php echo $item->netAmt ;?></td>
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
            <?php $i = $i + 1;
                $totalNet += $item->netAmt;
                $totalSales += $item->salesAmt;
                $totalprofit += $profit;
                $totalNet2 += $getRefund['NETREF'];
                $totalSales2 += $getRefund['PAIDREF'];
                $totalprofit2 += $profit1;
                $totalSumprofit += $profit+$profit1;
        endforeach;
        $totalprofit2           = $totalNet2-$totalSales2;

        if($totalNet !=0)
            $totalprofitPer      = round(($totalprofit/$totalNet*100),2);
        if($totalSales2 != 0)
            $totalprofitPer2     = round(($totalprofit2/$totalSales2*100),2);
        if($totalNet2 != 0 || $totalNet != 0)
            $totalSumprofitPer     = round((($totalprofit+$totalprofit2)/($totalNet+$totalSales2)*100),2);
        ?>
        <tr>
            <td colspan="20" align="center">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
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

        <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="12" align="center"><img src="<?php echo base_url(); ?>index.php/graph/salesBarGraph" /></td>
    </tr>

    <tr class="P">
        <td>&nbsp;</td>
        <td colspan="11">
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
                            <?php $this->load->view('manage_info/sales_details');?>
                        </div>
                        <?php //$this->load->view('manage_info/sales_details');?>
                    </div>
                </div>
            </div>
        </td>
    </tr>

        <?php }
    else{
        ?>
    <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    </tbody>
</table>
<div id="blackout"></div>