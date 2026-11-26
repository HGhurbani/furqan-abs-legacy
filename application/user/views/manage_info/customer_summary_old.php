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
        <th colspan="10">
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
        <th align="left"><?php echo CUSTOMER;?></th>
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
    $this->session->unset_userdata('newDebit');
    if(!empty($list_value)){
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkAccountId = array();
        foreach($list_value as $key => $item):

            if($item->account != '')
            {
                switch($item->vTable)
                {
                    case "C":
                        $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->account."'");
                        break;

                    case "V":
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'");
                        break;

                    case "A":
                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
                        break;

                    case "O":
                        $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->account."'");
                        break;

                    case "AG":
                        $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->account."'");
                        break;
                }
            }
            else
            {
                if($item->subacc == 32 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 34 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 35 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                else:
                    $SubAccountName = '&nbsp';
                    $SubAccountCode = '&nbsp';
                endif;
            }

            if(!in_array($SubAccountCode,$chkAccountId)):
                $getDebitCredit = $this->manage_info_model->getDebitCreditValues($SubAccountCode,$list_value,$item->account);
                $openDebitVal   = $openCredit = 0;
                $openDebit      = $getDebitCredit['wdebit']-$getDebitCredit['wcredit'];
                ?>
                <tr>
                    <td><?php echo $srNo;?></td>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                    <td><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");?></td>
                    <td><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");?></td>
                    <td><?php echo $SubAccountCode;?></td>
                    <td><?php echo $SubAccountName;?></td>
                    <td>&nbsp;</td>
                    <td><?php echo $getDebitCredit['wdebit'];?></td>
                    <td><?php echo $getDebitCredit['wcredit'];?></td>
                    <td><?php echo $openDebit;?></td>
                </tr>
                <?php

                $srNo++;
                $chkAccountId[]  = $SubAccountCode;
                $totalDebit     += $getDebitCredit['wdebit'];
                $totalCredit    += $getDebitCredit['wcredit'];
                $totalBalance   += $openDebit;
            endif;
        endforeach;?>

    <tr>
        <td colspan="7" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalDebit;?></td>
        <td><?php echo $totalCredit;?></td>
        <td><?php echo $totalBalance;?></td>
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
                            <?php $this->load->view('manage_info/show_details');?>
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
