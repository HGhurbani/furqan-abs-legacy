<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
<tbody>

        <?php
            $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
            $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
            $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
            $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
            $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
            $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
            $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
            $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");
            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");
            ?>
            <tr>
                <td colspan="8">
                <table width="100%" border="0" cellspacing="10" cellpadding="10">
                <tr><td>                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">
                </td><td>
                <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
                </div>
                </td></tr></table>
                </td>

        <td colspan="2">

  
        </td>
    </tr>
    <tr>
        <u><td colspan="8" align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo BANKRECEIPT;?></span></td></u>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><?php echo $this->txtDate1;?></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td width="100"><?php echo $this->txtRefNumberMain;?></td>
        <td><?php echo REFDATE;?></td>
        <td width="100"><?php echo $this->txtRefDate;?></td>
        <!--        <td><?php echo AMOUNTFC;?></td>
        <td><?php echo CURRNAME;?></td>
        <td><?php echo RATE;?></td> -->
<tr>
</tr>
        <td><?php echo CHEQUENUMBER;?></td>
        <td width="100"><?php echo $this->txtCheqNumber;?></td>
        <td><?php echo CHEQUEDATE;?></td>
        <td width="100"><?php echo $this->txtCheqDate;?></td>

        </tr> 
<tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="9"><?php echo $this->txtRefName; ?></td>

<!--        <td><?php echo $this->txtAmountFCMain;?></td>
        <td><?php echo $this->ddlCurrencyMain;?></td>
        <td><?php echo stripslashes($this->txtRate);?></td> -->
    </tr>


        <tr>
        <td><?php echo AMOUNTDC;?></td>
       <td colspan="4"><u><b><?php echo $this->txtAmountDC;?></b></u>
&nbsp; - &nbsp; 
    <?php
                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($this->txtAmountDC - floor($this->txtAmountDC)) * $multiple;
if($decimal_part == 0) {
?>
<?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY; ?> 
<?php } else { ?>
<?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?>
<?php } ?>
</td>
</tr>
        <tr>
        <td><?php echo REMARKS;?></td>
        <td colspan="9"><?php echo stripslashes($this->txtRemarks); ?> </td>
    </tr>

    <tr>
        <td colspan="3"><?php echo PREPAREDBY;?></td>
        <td colspan="3"><?php echo CHECKEDBY;?></td>
        <td colspan="3"><?php echo MANAGER;?></td>
    </tr>

    <tr>
        <td colspan="3"> <?php echo stripslashes($this->txtEntered); ?> </td>
        <td colspan="6">&nbsp;</td>
    </tr>

   <tr>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td colspan="15"><hr></td>
    </tr>


<!-- ------------------------------------------------------------------------ -->

        

</tbody>
</table>
