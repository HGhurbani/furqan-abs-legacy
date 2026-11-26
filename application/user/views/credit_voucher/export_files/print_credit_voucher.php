
<?php  date_default_timezone_set('GMT'); ?>

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
        <td colspan="10" align="center"><u><span style="font-size: 14px;font-weight: bold;"><?php echo CREDITVOUCHER;?></span></u></td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><?php echo $this->txtDate;?></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td><?php echo $this->txtRefNumberMain;?></td>
        <td><?php echo REFDATE;?></td>
        <td><?php echo $this->txtRefDate;?></td>
        </tr><tr>
        <td><?php echo CHEQUENUMBER;?></td>
        <td width="100"><?php echo $this->txtCheqNumber;?></td>
        <td><?php echo CHEQUEDATE;?></td>
        <td width="100"><?php echo $this->txtCheqDate;?></td>

        </tr>

    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="9"><?php echo $this->txtRefName;?></td>
    </tr>


	<tr>
		<td><?php
				  // assuming that debit and credit total will be same
				  $cash_debit = 0;
				  foreach($chkLoc as $key => $aitem):
				  	if($chkMode[$key] == 'Dr') {
						$cash_debit = $cash_debit + $chkAmount[$key];
					}
				  endforeach;
    
				  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  echo TOTALAMT . ": " ; 
                  ?>
                  </td>
		<td  colspan="9"><?php 
				   echo " &nbsp;&nbsp;&nbsp; <b><u>" . $cash_debit . " </u></b> " . $strCurCode;
 echo " &nbsp;&nbsp;-&nbsp;&nbsp;  ";
                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($cash_debit - floor($cash_debit)) * $multiple;
                  if($decimal_part == 0) {
                      echo $this->common_model->convert_number_to_words(floor($cash_debit)) . " " . $strCurCode . " " . ONLY;
                  } else {
                      echo $this->common_model->convert_number_to_words(floor($cash_debit)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;
                  } ?>
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
</tbody>
</table>
