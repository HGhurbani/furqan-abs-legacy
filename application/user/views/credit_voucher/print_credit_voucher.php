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
<!DOCTYPE html>
<head>
<title>Print Accounts Copy</title>
<link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
</head>
<body>
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function() {

    $('#toPopup4').hide();
    $('#email').click(function(e) {
        $('#toPopup4').show();
    });

    $('#btnPrint').click(function(e) {
        window.print() ;
    });
});
</script>

<?php  date_default_timezone_set('GMT'); ?>

<?php if($this->uri->segment(4) == 'U') { ?>
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">
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

            <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTCREDITVOUCHER;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

        </td>
    </tr>
    <tr>
        <td colspan="10" align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo CREDITVOUCHER;?></span></td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate;?></div></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>

        <td><?php echo CHEQUENUMBER;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqNumber;?></div></td>
        <td><?php echo CHEQUEDATE;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqDate;?></div></td>

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
		<td  colspan="9"><div class="smallBox"><?php 
				   echo " &nbsp;&nbsp;&nbsp;&nbsp;  " . $cash_debit . " " . $strCurCode;
 echo " &nbsp;&nbsp;&nbsp;&nbsp;  ";
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

    <tr>

    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="9"><div class="smallBox"><?php echo $this->txtRefName;?></td> </td>
    </tr>

    <tr>
        <td><?php echo REMARKS;?></td>

        <td colspan="9"><div class="bigBox"><?php echo stripslashes($this->txtRemarks); ?> </div></td>
    </tr>
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





    <tr>
        <td>
            <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>">
        </td>
        <td colspan="3">
            <span style="font-size: 16px;font-weight: bold;"><?php echo $this->common_model->getCompanyName();?></span><br>
            <span style="font-size: 12px;font-weight: bold;"><?php echo $this->common_model->getCompanyLocation();?></span><br>
            <?php echo $this->common_model->getCompanyAddress();?>
        </td>
    </tr>
    <tr>
        <td colspan="8" align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo CREDITVOUCHER;?></span></td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate;?></div></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>
        <td><?php echo CHEQUENUMBER;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqNumber;?></div></td>
        <td><?php echo CHEQUEDATE;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqDate;?></div></td>

        </tr>

    <tr>
        <td><?php
                  // assuming that debit and credit total will be same
/*                  $cash_debit = 0;
                  foreach($chkLoc as $key => $aitem):
                    if($chkMode[$key] == 'Dr') {
                        $cash_debit = $cash_debit + $chkAmount[$key];
                    }
                  endforeach;

                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);*/
                  echo TOTALAMT . ": " ;
                  ?>
                  </td>
        <td  colspan="9"><div class="smallBox"><?php
                   echo " &nbsp;&nbsp;&nbsp;&nbsp;  " . $cash_debit . " " . $strCurCode;
 echo " &nbsp;&nbsp;&nbsp;&nbsp;  ";

                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal =
                  $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple .
                  '0';}
                  $decimal_part = ($cash_debit - floor($cash_debit)) *
                  $multiple;
                  if($decimal_part == 0) {
                      echo
                      $this->common_model->convert_number_to_words(floor($cash_debit))
                      . " " . $strCurCode . " " . ONLY;
                  } else {
                      echo
                      $this->common_model->convert_number_to_words(floor($cash_debit))
                      . " " . $strCurCode . " AND " . $decimal_part. " " .
                      $strCurCoin . "  " .  ONLY;
                  } ?>
</td>
                  

    <tr>


    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="9"><div class="smallBox"><?php echo $this->txtRefName;?></td> </td>

    </tr>

    <tr>
        <td><?php echo REMARKS;?></td>

        <td colspan="9"><div class="bigBox"><?php echo stripslashes($this->txtRemarks); ?></div></td>
    </tr>

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

<!-- credit and debit values -->
<?php $totalDebit = 0; $totalCredit = 0; ?>
<!-- first row is from cashreceipt table -->

	</tr>
    <?php
    if(!empty($chkLoc))
    {
    foreach($chkLoc as $key => $aitem):
	?>
    <tr>
	    <td> <div class="smallBox"> <?php  echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$chkLoc[$key]."'"));?></td>
        <?php switch($chkTable[$key])
				  {
				  	case 'V' : $tableName = "VendorMaster";
								$columnCheck = "nVId";
								break;
				  	case 'C' : $tableName = "CustomerMaster";
								$columnCheck = "nCId";					
								break;
				  	case 'A' : $chkAccSubCode[$key] = $chkAccCodeName[$key];
								$tableName = "";
								$columnCheck = "";					
								break;
				  	case 'O' : $tableName = "Other";
								$columnCheck = "nOId";					
								break;
				  	case 'AG' : $tableName = "Agent";
								$columnCheck = "nAId";					
								break;
								
				  }
			?>
		<td><div class="smallBox"><?php echo stripslashes($this->common_model->get_field_name('AccountHeadAccounts' ,'vACode'," WHERE nId='". $chkAccSubCode[$key]."'")); ?></td>
        <td colspan="3"><div class="smallBox"><?php echo stripslashes($this->common_model->get_field_name('AccountHeadAccounts' ,'vTitle'," WHERE nId='". $chkAccSubCode[$key]."'"));?></td>
		
		<?php if($tableName !='') {	?> <td><div class="smallBox"> <?php echo stripslashes($this->common_model->get_field_name($tableName ,'vCode'," WHERE ". $columnCheck . "='".$chkAccCodeName[$key]."'"));?>
		</td>
        <td colspan="2"> <div class="smallBox"><?php echo stripslashes($this->common_model->get_field_name($tableName,'vName'," WHERE ". $columnCheck . " ='".$chkAccCodeName[$key]."'")) ; 
		?>
		</div></td>
		<?php
		}
		else
		{?>
		
        <td><div class="smallBox"></td>
        <td colspan="2"><div class="smallBox"></td>

		<?php }?>
		
		<?php if($chkMode[$key] == 'Dr')
		{ ?>
        <td><div class="smallBox"><?php echo $chkAmount[$key]; $totalDebit = $totalDebit + $chkAmount[$key];?></td><td><div class="smallBox"></td>
		<?php }
		else if($chkMode[$key] == 'Cr')
		{ ?>
	    <td><div class="smallBox"></td><td><div class="smallBox"><?php echo $chkAmount[$key]; $totalCredit = $totalCredit + $chkAmount[$key]; ?></td>
		<?php } ?>
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
                  $decimal_part = ($totalDebit - floor($totalDebit)) * $multiple;
if($decimal_part == 0) {
?>
        <td colspan="7" align="right"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($totalDebit)) . " " . $strCurCode . " " . ONLY; ?></div></td> 
<?php } else { ?>
        <td colspan="7" align="right"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($totalDebit)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></div></td>
<?php } ?>
		 <td align="right"> <?php echo TOTALAMT; ?> </td>
		 <td> <div class="smallBox"> <?php echo $totalDebit; ?></td>
		 <td> <div class="smallBox"> <?php echo $totalCredit; ?></td>		 
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

<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('credit_voucher/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
