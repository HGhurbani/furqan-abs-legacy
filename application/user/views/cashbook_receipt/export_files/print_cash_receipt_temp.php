<table width="100%" border="0" cellspacing="10" cellpadding="10">
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
                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">
                    <div class="print_desc">
                    <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                        <?php echo $addr;?><br>
                        <?php echo PHONE;?>: <?php echo $phone;?>
                        <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?><br>
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>

        <td colspan="2">
        </td>
    </tr>
    <tr>
        <td colspan="10"  align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo CASHRECEIPT;?></span></td>
    </tr>
 
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate1;?></div></td>
   </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td width="100"><div class="smallBox" ><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>
<!--        <td><?php echo AMOUNTFC;?></td>
        <td><?php echo CURRNAME;?></td>
        <td><?php echo RATE;?></td> -->
        <td><?php echo AMOUNTDC;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtAmountDC;?></div></td>
    </tr>
    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="5"><div class="smallBox"><?php echo $this->txtRefName; ?></div></td>
<!--        <td><?php echo $this->txtAmountFCMain;?></td>
        <td><?php echo $this->ddlCurrencyMain;?></td>
        <td><?php echo stripslashes($this->txtRate);?></td> -->
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
<td colspan="4"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY; ?></td> 
<?php } else { ?>
<td colspan="4"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></div></td>
<?php } ?>
    </tr>

    <tr>
        <td><?php REMARKS;?></td>
        <td colspan="9"><div class="bigBox"><?php echo stripslashes($this->txtRemarks); ?> </div></td>
    </tr>

    <tr>
        <td colspan="3"><?php echo PREPAREDBY;?></td>
        <td colspan="3"><?php echo CHECKEDBY;?></td>
        <td colspan="3"><?php echo MANAGER;?></td>
        <td>&nbsp;</td>
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
                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">
                    <div class="print_desc">
                    <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                        <?php echo $addr;?><br>
                        <?php echo PHONE;?>: <?php echo $phone;?>
                        <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?><br>
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>
</tr>

    <tr>
        <td colspan="10"  align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo CASHRECEIPT;?></span></td>
    </tr>
 
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate1;?></div></td>
   </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>
<!--        <td><?php echo AMOUNTFC;?></td>
        <td><?php echo CURRNAME;?></td>
        <td><?php echo RATE;?></td> -->
        <td><?php echo AMOUNTDC;?></td>
        <td><div class="smallBox"><?php echo $this->txtAmountDC;?></div></td>
    </tr>
    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="5"><div class="smallBox"><?php echo $this->txtRefName; ?></div></td>
<!--        <td><?php echo $this->txtAmountFCMain;?></td>
        <td><?php echo $this->ddlCurrencyMain;?></td>
        <td><?php echo stripslashes($this->txtRate);?></td> -->
   </tr>

 
    </tr>

    <tr>
        <td><?php REMARKS;?></td>
        <td colspan="9"><div class="bigBox"><?php echo stripslashes($this->txtRemarks); ?> </div></td>
    </tr>

<!-- table -->
<tr> <td colspan="10">
<table width ="100%"  >
    <tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"><?php echo ACCOUNTDETAILS;?></span></td>
    </tr>
    <tr>
        <td><?php echo LOCATIONNAME;?></td>
        <td><?php echo ACCODE;?></td>
        <td colspan="3"><?php echo ACCNAME;?></td>
        <td><?php echo SUBCODE;?></td>
        <td colspan="2"><?php echo SUBNAME;?></td>
        <td><?php echo DEBIT;?></td>
        <td><?php echo CREDIT;?></td>
    </tr>
<!-- Main Account -->
        <?php
        $Debit_main ='';
        $Credit_main ='';
        if($this->ddlModeMain == 'Dr'){
            $Debit_main = $this->txtAmountDC;
        }else{
            $Credit_main =  $this->txtAmountDC;
        }
        $Debit = $Debit_main;
        $Credit = $Credit_main;
        $accountName_main= $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->ddlAccount. "'");
        $accountCode_main = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '". $this->ddlAccount ."'");
        $location_main = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '". $this->ddlLocation."'");
        ?>
        <tr>
            <td> <?php echo $location_main; ?></td>
            <td> <?php echo $accountCode_main; ?></td>
            <td align="left" colspan="3"> <?php echo $accountName_main ?> </td>
            <td></td>
            <td colspan="2"></td>
            <td align="left"> <?php echo $Debit_main; ?> </td>
            <td align="left"> <?php echo $Credit_main; ?> </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

<!-- Other Accounts -->
    <?php
    if(!empty($chkLoc))
    {
    foreach($chkLoc as $key => $aitem):
        $account = @split('-',$chkAccCode[$key]);
        switch($account[1])
        {
            case "C":
                $accountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$account[0]."'");
                $accountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '".$account[0]."'");
            break;
            case "V":
                $accountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$account[0]."'");
                $accountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '".$account[0]."'");
            break;
            case "AG":
                $accountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$account[0]."'");
                $accountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '".$account[0]."'");
            break;
            case "O":
                $accountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$account[0]."'");
                $accountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId = '".$account[0]."'");
            break;
            default:
                $accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
                $accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
            break;
        }//end switch

        $subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkAccSubCode[$key]."'");
        $subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$chkAccSubCode[$key]."'");
        $location = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '". $chkLoc[$key] ."'");

        $Debit_acc ='';
        $Credit_acc ='';
        if($chkMode[$key] == 'Dr'){
            $Debit_acc = $chkAmount[$key];
        }else{
            $Credit_acc=  $chkAmount[$key];
        }
        $Debit = $Debit + $Debit_acc;
        $Credit = $Credit +  $Credit_acc;
    ?>
    <tr>
        <td><?php echo $location;?></td>
        <td><?php echo $accountCode;?></td>
        <td colspan="3"><?php echo $accountName;?></td>
        <td><?php echo $subAccCode;?></td>
        <td colspan="2"><?php echo $subAccName;?></td>
        <td><?php echo $Debit_acc;?></td>
        <td><?php echo $Credit_acc; ?><td>
    </tr>
        <?php
    endforeach;
    }//end if
    ?>

    <tr>
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
        <td colspan="7" align="right"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY; ?></td> 
<?php } else { ?>
        <td colspan="7" align="right"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td> 
<?php } ?>
        <td  align="right"><?php echo TOTAL;?></td>
        <td><?php echo $Debit;?></td>
        <td><?php echo $Credit; ?><td>
</table> </td></tr>
    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="3"><?php echo PREPAREDBY;?></td>
        <td colspan="3"><?php echo CHECKEDBY;?></td>
        <td colspan="3"><?php echo MANAGER;?></td>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="3"> <?php echo stripslashes($this->txtEntered); ?> </td>
        <td colspan="6">&nbsp;</td>
    </tr>
</tbody>
</table>
</body>
</html>
