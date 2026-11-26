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
        <th colspan="13">
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
        <th align="left"><?php echo VENDORNAME;?></th>
        <th align="left"><?php echo CODE;?></th>
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
    if(!empty($list_value_summary)){
        $i = 1;
        $chkAccountId = array();
        foreach($list_value_summary as $key=>$item):

            if($item['Account'] != '')
            {
                switch($item['vTable'])
                {
                    case "C":
                        $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item['Account']."'");
                        $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item['Account']."'");
                        break;

                    case "V":
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item['Account']."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item['Account']."'");
                        break;

                    case "A":
                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item['Account']."'");
                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item['Account']."'");
                        break;

                    case "O":
                        $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item['Account']."'");
                        $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item['Account']."'");
                        break;

                    case "AG":
                        $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item['Account']."'");
                        $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item['Account']."'");
                        break;
                }
            }
/*            else
            {
                if($item->subacc == 32 && trim($item['Account']) == ''):
                    $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 34 && trim($item['Account']) == ''):
                    $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 35 && trim($item['Account']) == ''):
                    $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                else:
                    $SubAccountName = '&nbsp';
                    $SubAccountCode = '&nbsp';
                endif;
            }
*/

                $getNetSalesAmt['netSale'] = $item['NetAmount'];
                $getNetSalesAmt['saleAmt'] =  $item['SalesAmount'];

                $getNetSalesAmt['NETREF'] = $item['NetRefund'];
                $getRfndAmt['PAIDREF'] = $item['PaidRefund']; 



             if(!in_array($SubAccountCode,$chkAccountId)):
				//$getNetSalesAmt = $this->manage_info_model->vendoerNetSales($item->subacc,$item['Account'],$item->vNumber);

                if($getNetSalesAmt['NETREF'] !=0){

                    //$getRfndAmt = $this->manage_info_model->vendoerRefund($item->subacc);

                    $profit1 = ($getNetSalesAmt['NETREF']-$getRfndAmt['PAIDREF']);
                    if($getRfndAmt['PAIDREF'] != 0):
                        $profitPercent1 = round(($profit1/$getRfndAmt['PAIDREF']*100),2);
                    else:
                        $profitPercent1 = '';
                    endif;
                }
                else{
                    $getRfndAmt['PAIDREF'] = 0;
                    $profit1 = 0;
                    $profitPercent1 = 0;
                }

                $profit = ($getNetSalesAmt['saleAmt']-$getNetSalesAmt['netSale']);
                if($getNetSalesAmt['netSale'] > 0)
                    $profitPercent = round(($profit/$getNetSalesAmt['netSale']*100),2);
                else
                    $profitPercent = 0;
                ?>
                <tr>
                    <td><?php echo $SubAccountName;?></td>
                    <td><?php echo $SubAccountCode;?></td>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item['Location']."'");?></td>
                    <td><?php echo $getNetSalesAmt['netSale'];?></td>
                    <td><?php echo $getNetSalesAmt['saleAmt'];?></td>
                    <td><?php echo $profit;?></td>
                    <td><?php echo $profitPercent;?></td>
                    <td><?php echo $getNetSalesAmt['NETREF'];?></td>
                    <td><?php echo $getRfndAmt['PAIDREF'];?></td>
                    <td><?php echo $profit1;?></td>
                    <td><?php echo $profitPercent1;?></td>
                    <td><?php echo $profit+$profit1;?></td>
                    <td><?php echo $profitPercent+$profitPercent1;?></td>
                </tr>
                <?php $i = $i + 1;
                $totalNet           += $getNetSalesAmt['netSale'];
                $totalSales         += $getNetSalesAmt['saleAmt'];
                $totalprofit        += $profit;
                $totalNet2          += $getNetSalesAmt['NETREF'];
                $totalSales2        += $getRfndAmt['PAIDREF'];
                $totalprofit2       += $profit1;
                $totalSumprofit     += $profit+$profit1;
                $totalSumprofitPer  += $profitPercent+$profitPercent1;
                $chkAccountId[]      = $SubAccountCode;
             endif;
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
            <td colspan="3" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
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

    <tr>
        <td colspan="13" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="13" align="center"><img src="<?php echo base_url(); ?>index.php/graph/ventBarGraph" /></td>
    </tr>

    <tr class="P">
        <td>&nbsp;</td>
        <td colspan="12">
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
                            <?php $this->load->view('manage_info/vendor_details');?>
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
        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    </tbody>
</table>

<div id="blackout"></div>
