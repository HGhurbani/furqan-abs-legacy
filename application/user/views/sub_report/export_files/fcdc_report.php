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
        <th colspan="16">
            

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
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><?php echo SUBLEDGERSUMMARYREPORT;?></strong></td>
    </tr>
    <tr>
        <th colspan="2" align="right"><?php echo FROMDATE;?></th>
        <th align="center"><?php echo $this->txtFromDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
 <style type="text/css">
  table { font-size: 9px; }
</style>

  <style>
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>

<!--    <tr>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left" colspan="12">&nbsp;</th>
    </tr> -->
    <tr>
        <td height="25px" align="left" colspan="5">&nbsp;</td>
        <td align="center" colspan="4"><?php echo FOREIGNCURRENCY;?></td>
        <td align="center" colspan="3"><?php echo DOMESTICCURRENCY;?></td>
    </tr>
    <tr>
        <td height="25px" align="center"><?php echo SRNO;?></td>
        <td align="center"><?php echo ACCODE;?></td>
        <td align="center"><?php echo ACCCODENAME;?></td>
        <td align="center"><?php echo SUBCODE;?></td>
        <td align="center"><?php echo SUBNAME;?></td>
        <td align="center"><?php echo CURRNAME;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
        <td align="center"><?php echo BALANCE;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
        <td align="center"><?php echo BALANCE;?></td>
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value1)){
        $recoed_present = 1;
        $i = 1;
		$balance = 0;
		$fcBalance = 0;
		$chkAccount = array();
        $chkAccountSub = array();
        foreach($list_value1 as $key=>$item):
 if(!in_array($item->vACode,$chkAccount) || !in_array($item->vAcSubCode,$chkAccountSub)):
				if( $i !=1 ):
                    if(!($totalfcDebit_per_group == 0 && $totalfcCredit_per_group ==0 && $totalfcBalance_per_group == 0 && $totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):
			?>
					<tr>
						<td height="25px" colspan="6" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
						<td align="right"><?php echo $totalfcDebit_per_group ;?>
						<td align="right"><?php echo $totalfcCredit_per_group ;?></td>
						<td align="right"><?php echo $totalfcBalance_per_group ;?></td>
						<td align="right"><?php echo number_format((float)$totalDebit_per_group, 3, ".", "");?></td></td>
						<td align="right"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "");?></td></td>
						<td align="right"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "");?></td></td>
					</tr>
				<?php
                endif;
					$totalfcDebit_per_group 	= 0;
					$totalfcCredit_per_group 	= 0;
					$totalfcBalance_per_group	= 0;
					$totalDebit_per_group 	= 0;
					$totalCredit_per_group 	= 0;
					$totalBalance_per_group	= 0;
					$balance = 0;
                    $fcBalance = 0;
				?>
				<?php endif; 


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

            $open_list_value = array();
            $openBalName = "fcdc";
            $open_list_value =  $this->sub_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable,$openBalName );
            $openbal = $open_list_value['debit'] - $open_list_value['credit'];
            $fcBalance +=  $open_list_value['vFCAmount'] - $open_list_value['vFCAmount_credit'];
if( $open_list_value['debit'] ==0 && $open_list_value['credit'] ==0 && $item->debit==0 && $item->credit ==0 ) continue;
 $recoed_present +=1;
		?>

           
        <tr>
            <td height="25px" colspan="4" align="left"> <?php echo $subAccName . " - " . $AccountName; ?> </td>
            <td align="right" colspan="2"><?php echo OPENINGBALANCE;?></td>
            <td align="right"><?php echo $open_list_value['vFCAmount'];?></td>
            <td align="right"><?php echo $open_list_value['vFCAmount_credit'];?></td>
            <td v><?php echo $fcBalance;?></td>
            <td align="right"><?php echo number_format((float)$open_list_value['debit'], 3, ".", "");?></td>
                        <td align="right"><?php echo number_format((float)$open_list_value['credit'], 3, ".", "");?></td>
                        <td align="right"><?php echo number_format((float)$balance, 3, ".", "");?></td>
        </tr>
        <?php
		    $totalfcDebit_per_group   += $open_list_value['vFCAmount'];
            $totalfcCredit_per_group  += $open_list_value['vFCAmount_credit'];
 	
            $totalDebit_per_group   = $totalDebit_per_group+$open_list_value['debit'];
            $totalCredit_per_group  = $totalCredit_per_group+$open_list_value['credit'];

            $totalfcDebit  += $open_list_value['vFCAmount'];
            $totalfcCredit += $open_list_value['vFCAmount_credit'];
            $totalfcBalance = $totalfcDebit - $totalfcCredit;
            $totalDebit   = $totalDebit+$open_list_value['debit'];
            $totalCredit  = $totalCredit+$open_list_value['credit'];
            $totalBalance = $totalDebit - $totalCredit;
 
            $balance = $balance + $openbal;	
        endif;	
            $balance = $balance + $item->debit - $item->credit;	
            $fcBalance += $item->vFCAmount - $item->vFCAmount_credit;			
        ?>
        <tr>
            <td height="25px" align="center"><?php echo $key+1;?></td>
			<td align="center"><?php echo $subAccCode ; ?> </td>
			<td><strong><?php echo $subAccName ; ?> </strong></td>
			<td align="center"><?php echo $AccountCode ; ?> </td>			
			<td><strong><?php echo $AccountName ; ?> </strong></td>
            <td align="center"><?php echo $this->common_model->get_field_name('Currency', 'vCurrcode', " WHERE nId='".$item->nCurrency."'");?></td>
            <td align="right"><?php echo $item->vFCAmount;?></td>		
            <td align="right"><?php echo $item->vFCAmount_credit;?></td>		
            <td align="right"><?php echo $fcBalance;?></td>		
            <td align="right"><?php echo $item->debit;?></td>
            <td align="right"><?php echo $item->credit;?></td>
            <td align="right"><?php echo number_format((float)$balance, 3, ".", "");?></td>
        </tr>
            <?php $i = $i + 1;
		    $totalfcDebit_per_group   = $totalfcDebit_per_group+$item->vFCAmount;
            $totalfcCredit_per_group  = $totalfcCredit_per_group+$item->vFCAmount_credit;
            $totalfcBalance_per_group = $fcBalance;
            $totalopenbal  += $openbal;
		    $totalDebit_per_group   = $totalDebit_per_group+$item->debit;
            $totalCredit_per_group  = $totalCredit_per_group+$item->credit;
            $totalBalance_per_group = $balance;
			
            $totalfcDebit   = $totalfcDebit+$item->vFCAmount;
            $totalfcCredit  = $totalfcCredit+$item->vFCAmount_credit;
            $totalfcBalance = $totalfcDebit - $totalfcCredit;
            $totalDebit   = $totalDebit+$item->debit;
            $totalCredit  = $totalCredit+$item->credit;
            $totalBalance = $totalDebit - $totalCredit; // + $totalopenbal;
            $chkAccount[] = $item->vACode;
            $chkAccountSub[] = $item->vAcSubCode;
        endforeach;
        if($recoed_present !=1): 

                    if(!($totalfcDebit_per_group == 0 && $totalfcCredit_per_group ==0 && $totalfcBalance_per_group == 0 && $totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):?>
    <tr>
        <td height="25px" colspan="6" align="right"><strong><?php echo TOTAL;?></strong></td>
        <td align="right"><?php echo $totalfcDebit_per_group ;?></td>
        <td align="right"><?php echo $totalfcCredit_per_group ;?></td>
        <td align="right"><?php echo $totalfcBalance_per_group ;?></td>
        <td align="right"><?php echo number_format((float)$totalDebit_per_group, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "");?></td>
    </tr>
    <?php endif; ?>
    
    <tr>
        <td height="25px" colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><?php echo $totalfcDebit;?></td>
        <td align="right"><?php echo $totalfcCredit;?></td>
        <td align="right"><?php echo $totalfcBalance;?></td>
        <td align="right"><?php echo number_format((float)$totalDebit, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalBalance, 3, ".", "");?></td>
    </tr>
    <?php else:?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php endif;
    }
    else{  ?>
    <tr>
        <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>
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
       
        <td colspan="15">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
