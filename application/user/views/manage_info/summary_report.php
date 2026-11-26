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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Account Summary Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
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
        <th align="center"><?php echo LOCATIONNAME;?></th>
        <th align="center"><?php echo ACCODE;?></th>
        <th align="center"><?php echo ACCCODENAME;?></th>
        <th align="center"><?php echo SUBCODE;?></th>
        <th align="center"><?php echo SUBCODENAME;?></th>
        <th align="center"><?php echo OPENINGBALANCE;?></th>
        <th align="center"><?php echo DEBIT;?></th>
        <th align="center"><?php echo CREDIT;?></th>
        <th align="center"><?php echo BALANCE;?></th>
    </tr>

    </thead>

    <tbody>
    <?php
    $this->session->unset_userdata('newDebit');
    if(true){
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkAccountId = array();
		 if(!empty($list_value)){
        foreach($list_value as $key => $item):

            if($item->account != '')
            {
				//print $item->account;
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
                if(!empty($open_value)){
				$open = $this->manage_info_model->getPayableDebitCreditValues($SubAccountCode,$open_value,$item->account);
					//echo $SubAccountCode."--".$open_value."--".$item->account."<br>";
				}
				 $open_balance      = $open['wdebit']-$open['wcredit'];
				
				?>
                <tr>
                    <td align="center"><?php echo $srNo;?></td>
                    <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                    <td align="center"><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");?></td>
                    <td><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");?></td>
                    <td align="center"><?php echo $SubAccountCode;?></td>
                    <td><?php echo $SubAccountName;?></td>
                    <td align="right"><?php $op=''; 
					echo $open_balance;;?></td>
                    <td align="right"><?php echo number_format((float)$getDebitCredit['wdebit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$getDebitCredit['wcredit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$openDebit+$op+$open_balance, 2, ".", "");?></td>
                </tr>
                <?php

                $srNo++;
                $chkAccountId[]  = $SubAccountCode;
                $totalDebit     += $getDebitCredit['wdebit'];
                $totalCredit    += $getDebitCredit['wcredit'];
                $totalBalance   += $openDebit;
				$totalop   += $open_balance;
            endif;
		 endforeach; } ?>
		<?php 
		
		if(!empty($open_value)){
		foreach($open_value as $key => $item):

            if($item->account != '')
            {
				//print $item->account;
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
			if(!empty($list_value)){
                $getDebitCredit = $this->manage_info_model->getDebitCreditValues($SubAccountCode,$list_value,$item->account);
			}
				$openDebitVal   = $openCredit = 0;
                $openDebit      = $getDebitCredit['wdebit']-$getDebitCredit['wcredit'];
                if(!empty($open_value)){
				$open = $this->manage_info_model->getPayableDebitCreditValues($SubAccountCode,$open_value,$item->account);
					//echo $SubAccountCode."--".$open_value."--".$item->account."<br>";
				}
				 $open_balance      = $open['wdebit']-$open['wcredit'];
				
				?>
                <tr>
                    <td align="center"><?php echo $srNo;?></td>
                    <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                    <td align="center"><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");?></td>
                    <td><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");?></td>
                    <td align="center"><?php echo $SubAccountCode;?></td>
                    <td><?php echo $SubAccountName;?></td>
                    <td align="right"><?php $op=''; 
					/*$op= $this->common_model->get_field_name('OpeningBalanceDetails','vBalDC'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'AND nLoc='".$item->location."'"); 
					//$op1= $this->common_model->get_field_name('OpeningBalanceDetails','vMode'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'AND nLoc='".$item->location."'"); 
					if($op1=='Cr'){
						$op=-$op;
					}else{
						$op=$op;
					}
					 $op=0;*/
					echo $open_balance;?></td>
                    <td align="right"><?php echo number_format((float)$getDebitCredit['wdebit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$getDebitCredit['wcredit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$openDebit+$op+$open_balance, 2, ".", "");?></td>
                </tr>
                <?php

                $srNo++;
                $chkAccountId[]  = $SubAccountCode;
                $totalDebit     += $getDebitCredit['wdebit'];
                $totalCredit    += $getDebitCredit['wcredit'];
                $totalBalance   += $openDebit;
				$totalop   += $open_balance;
            endif;
        endforeach; }
		//$account_list = $this->ac_report_model->accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc);
//var_dump($account_list);
		
		?>

    <tr>
        <td colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><?php echo number_format((float)$totalop, 2, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalDebit, 2, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalCredit, 2, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalDebit-$totalCredit+$totalop, 2, ".", "");?></td>
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