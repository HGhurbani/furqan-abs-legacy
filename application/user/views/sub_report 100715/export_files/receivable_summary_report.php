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
            window.location.replace('<?php echo base_url().'index.php/sub_report/showlist/'.$this->uri->segment(3);?>');
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
    

    <tr>
        <th align="center" colspan="15">
           

            <div class="print_desc">
                <span style="font-size: 14px;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
    <tr><td style="border:0"></td></tr>
	
	<tr>
        <th colspan="15" style="border:0" height="25px" align="left">&nbsp;<strong><?php echo SUBLEDGERSUMMARYREPORT;?></h3></th>
    </tr>
	<tr><td style="border:0"></td></tr>
    <tr>
        <th style="color:#3BB3C2;" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th style="color:#3BB3C2;" align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
    <tr><td style="border:0"></td></tr>
<style type="text/css">
  table { font-size: 9px; }
</style>

  <style>
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr>
        <th height="25px" align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left"><?php echo OPENINGBALANCE;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
<!--    <tr>
        <th colspan="11" align="center"><?php echo OPENINGBALANCE;?></th>
   		</tr>
-->	
    </thead>

    <tbody>

    <?php
    if(!empty($list_value2)){
        $recoed_present = 1;
        $i = 1;
		$balance = 0;
		$chkAccount = array();
        foreach($list_value2 as $key=>$item):
				switch($item->vTable){
					case "C": $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); 
		  						$AccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '". $item->vACode  ."'"); 
								break;
					case "V": $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'");
								$AccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '". $item->vACode  ."'"); 
								break;
					default: $item->vAcSubCode = $item->vACode ; $AccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
				$subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->vAcSubCode."'");

            $open_list_value =  $this->sub_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable);
            $openbal = $open_list_value['debit'] - $open_list_value['credit'];
                    if( $open_list_value['debit'] ==0 && $open_list_value['credit'] ==0 && $item->debit ==0 && $item->credit == 0) continue;
                    $recoed_present +=1;
		?>
			
        <tr>
            <td height='25px' align="center"><?php echo $key+1;?></td>
			<td align="center"><?php echo $subAccCode ; ?> </td>
			<td><?php echo $subAccName ; ?> </strong></td>
			<td><?php echo $AccountCode ; ?> </td>			
			<td><strong><?php echo $AccountName ; ?> </strong></td>
            <td align="right"><?php echo $openbal;?></td>
            <td align="right"><?php echo $item->debit;?></td>
            <td align="right"><?php echo $item->credit;?></td>
			
            <td align="right"><?php 
						$balance =  $item->debit - $item->credit + $openbal;			
						echo $balance;?></td>
        </tr>
            <?php $i = $i + 1;
            $totalopenbal  += $openbal;
            $totalDebit   = $totalDebit+$item->debit;
            $totalCredit  = $totalCredit+$item->credit;
            $totalBalance = $totalDebit - $totalCredit + $totalopenbal;
        endforeach;
        if($recoed_present !=1): ?>
    <tr>
        <td height="25px" colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><?php echo $totalopenbal;?></td>
        <td align="right"><?php echo $totalDebit;?></td>
        <td align="right"><?php echo $totalCredit;?></td>
        <td align="right"><?php echo $totalBalance;?></td>
    </tr>

    <?php else:?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php endif;
    } 
    else{ ?>
    <tr>
        <td colspan="9" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0">&nbsp;</td>
        <td style="border:0" colspan="8">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
