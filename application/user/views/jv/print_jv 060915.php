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

            <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTJOURNALVOUCHER;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

        </td>
    </tr>
    <tr>
        <td colspan="10" align="center" align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo JOURNALVOUCHER;?></span></td>
    </tr>
    
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate1;?></div></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>
<!--        <td><?php echo AMOUNTFC;?></td>
        <td><?php echo CURRNAME;?></td>
        <td><?php echo RATE;?></td>
-->		

        <td><?php echo CHEQUENUMBER;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqNumber;?></div></td>
        <td><?php echo CHEQUEDATE;?></td>
        <td width="100"><div class="smallBox"><?php echo $this->txtCheqDate;?></div></td>

        </tr>

        <tr>
        <?php
        $valCur = $this->common_model->getCompanyCurr();
        $strCurCode = $this->common_model->currencyCode($valCur);
        ?>
        <td><?php echo AMOUNTDC;?></td>
        <td  colspan="9"><div class="smallBox">
        <?php   echo " &nbsp;&nbsp;&nbsp;&nbsp;  " . $this->txtAmountDC . " " . $strCurCode;
        echo " &nbsp;&nbsp;&nbsp;&nbsp;  ";
                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($this->txtAmountDC - floor($this->txtAmountDC)) * $multiple;
                  if($decimal_part == 0) {
                      echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY;
                  } else {
                      echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;
                  } ?>
</td> 
        </tr>
    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="9"><div class="smallBox"><?php echo $this->txtRefName; ?></div></td>

<!--        <td><?php echo $this->txtAmountFCMain;?></td>
        <td><?php echo $this->ddlCurrencyMain;?></td>
        <td><?php echo stripslashes($this->txtRate);?></td>
        <td><?php echo $this->txtAmountDC;?></td>
-->		
    </tr>

    <tr>
        <td><?php echo REMARKS;?></td>
        <td colspan="9"><div class="bigBox"><?php echo stripslashes($this->txtRemarks); ?> </div></td>

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
    </tr>
	
    <?php
	$totalDebit=0;
	$totalCredit=0;
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
                //$accountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$account[0]."'");
                //$accountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$account[0]."'");
				$chkAccSubCode[$key] = $account[0];
				$accountName = "";
				$accountCode = "";
            break;
        }//end switch

        $subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkAccSubCode[$key]."'");
        $subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$chkAccSubCode[$key]."'");
    ?>
    <tr>
        <td><div class="smallBox"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='". $chkLoc[$key] ."'"));?></td>
        <td><div class="smallBox"><?php echo $subAccCode;?></td>
        <td colspan="3"><div class="smallBox"><?php echo $subAccName;?></td>
        <td><div class="smallBox"><?php echo $accountCode;?></td>
        <td colspan="2"><div class="smallBox"><?php echo $accountName;?></td>
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
                  $decimal_part = ($this->txtAmountDC - floor($this->txtAmountDC)) * $multiple;
if($decimal_part == 0) {
?>
        <td colspan="7" align="right"><div class="smallBox"> <?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY; ?></div></td> 
<?php } else { ?>
        <td colspan="7" align="right"><div class="smallBox"> <?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></div></td>
<?php } ?>

		 <td align="right"> <?php echo TOTALAMT; ?> </td>
		 <td> <div class="smallBox"> <?php echo $totalDebit; ?></td>
		 <td> <div class="smallBox"> <?php echo $totalCredit; ?></td>		 
	</tr>

    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>


    <tr>
<tr>
        <td colspan="3"><?php echo PREPAREDBY;?></td>
        <td></td><td colspan="3"><?php echo CHECKEDBY;?></td>
        <td></td><td colspan="3"><?php echo MANAGER;?></td>
        <td>&nbsp;</td>
    </tr></tr>

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
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('jv/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
