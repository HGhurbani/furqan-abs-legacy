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
        $('#email').hide();
        window.print() ;
        $('#email').show();
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
                        <?php echo FAX;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?>,
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>
				<style type="text/css">
@media print {
input#email
{
display: none;
}
#tp{
	-webkit-print-color-adjust:exact;
	
}
</style>

        <td colspan="2">

           <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTPAYMENTVOUCHER;?>" class="btnGeneral">
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

        </td>
    </tr>
    <style>.print_desc{	padding-left:23%;	}input{border:none !important; 	}td{	border:none !important; }</style> 
    <tr>
        <td colspan="10" align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo PAYMENTVOUCHER;?></span></td>
    </tr>

    <tr>
        <td colspan="2"><span style="font-size: 14px;font-weight: bold;"><?php echo CUSTDATE;?></span></td>
        <td colspan="2"><span style="font-size: 14px;font-weight: bold;"><div ><?php echo $this->txtDate1;?></span></div></td>
		<td colspan="2"><span style="font-size: 14px;font-weight: bold;"><div ></div></span></td>
            
        <td colspan="2" align="right"><span style="font-size: 14px;font-weight: bold;"><?php echo DOCUMENTNUMBER;?></span></td>
        <td colspan="2" align="right"><span style="font-size: 14px;font-weight: bold;"><div ><?php echo $this->txtDocNumber;?></span></div></td>
  </tr>
  <tr>
        <td style="font-size:14px;" colspan="2"><?php echo "LOCATION"?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="5"><div class="smallBox1"><?php 
		$loc=$this->common_model->get_field_name('Location','vCode'," WHERE nId = '". $this->ddlLocation ."'");
		echo $loc; echo"  "; echo $name; ?></div></td>
    </tr>
	<tr>
        <td style="font-size:14px; " colspan="2"><?php echo PAIDTO?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="5"><div class="smallBox1"><?php echo $this->txtRefName;?></div></td>
<!--        <td><?php echo $this->txtAmountFCMain;?></td>
        <td><?php echo $this->ddlCurrencyMain;?></td>
        <td><?php echo stripslashes($this->txtRate);?></td> -->


    </tr>

    <tr>
        <td style="font-size:14px;" colspan="2"><?php echo "FOR";?></td>
        <td style="font-size:14px; font-weight: bold;" style="" colspan="5"><div class="smallBox1"><?php echo stripslashes($this->txtRemarks);?></td>
    </tr>
	<?php    if(!empty($chkLoc))    {    foreach($chkLoc as $key => $aitem):        $account = @split('-',$chkAccCode[$key]);               $Debit_acc1 ='';        $Credit_acc1 ='';        if($chkMode[$key] == 'Dr'){            $Debit_acc1 = $chkAmount[$key];        }else{            $Credit_acc=  $chkAmount[$key];        }        $Debit1 = $Debit1 + $Debit_acc1;        $Credit1 = $Credit1 +  $Credit_acc1;    endforeach;    }//end if	?>   
  <tr>
        <td style="font-size:14px;" colspan="2"><?php echo AMOUNTDC;?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="3" style=""><div class="smallBox1"><?php echo number_format($Debit1,3);?></div></td>

<!--<?php
				  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($this->txtAmountDC - floor($this->txtAmountDC)) * $multiple;
if($decimal_part == 0) {
?>
<td colspan="5"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " " . ONLY; ?></td> 
<?php } else { ?>
<td colspan="4"><div class="smallBox"><?php echo $this->common_model->convert_number_to_words(floor($this->txtAmountDC)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></div></td>
<?php } ?>-->

    </tr>

    

        <tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	<tr>
        <td colspan="2" style="font-size:14px;"><?php echo "BANK NAME & A/C No:";?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="4" width="100"><div  class="smallBox1"><?php 
		$accountName_main= $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->ddlAccount. "'");
        
		echo $accountName_main;?></div></td>
        
        <td colspan="2" width="100"></td>
</tr>
<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
    <tr>
        <td colspan="10" align = "center"><br><span style="font-size: 14px;font-weight: bold;"><?php echo ACCOUNTDETAILS;?></span><br><br><br></td>
    </tr>
	
    <tr id="tp">
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo LOCATIONNAME;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo ACCODE;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"colspan="3"><?php echo ACCNAME;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo SUBCODE;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;" colspan="2"><?php echo SUBNAME;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo DEBIT;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo CREDIT;?></td>
   
	</tr>
	
	<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
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
            <td style="font-size:14px;"> <?php echo $location_main; ?></td>
            <td style="font-size:14px;"> <?php echo $accountCode_main; ?></td>
            <td style="font-size:14px;" align="left" colspan="3"> <?php echo $accountName_main ?> </td>
            <td></td>
            <td colspan="2"></td>
            <td style="font-size:14px;" align="left"> <?php echo $Debit_main; ?> </td>
            <td style="font-size:14px;" align="left"> <?php echo $Credit_main; ?> </td>
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
        <td style="font-size:14px;"><?php echo $location;?></td>
        <td style="font-size:14px;"><?php echo $accountCode;?></td>
        <td style="font-size:14px;" colspan="3"><?php echo $accountName;?></td>
        <td style="font-size:14px;"><?php echo $subAccCode;?></td>
        <td style="font-size:14px;" colspan="2"><?php echo $subAccName;?></td>
        <td style="font-size:14px;"><?php echo $Debit_acc;?></td>
        <td style="font-size:14px;"><?php echo $Credit_acc; ?><td>
    </tr>
<?php
    endforeach;
    }//end if
    ?><tr>        <td colspan="10">&nbsp;</td>    </tr>
	<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr><tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
    <tr>
<?php
                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($Debit - floor($Debit)) * $multiple;
if($decimal_part == 0) {
?><td id="tp"style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;" colspan="7" align="right"><?php echo $this->common_model->convert_number_to_words(floor($Debit)) . " " . $strCurCode . " " . ONLY; ?></td> 
<?php } else { ?>
        <td id="tp" style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;" colspan="7" align="right"><?php echo $this->common_model->convert_number_to_words(floor($Debit)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td>
<?php } ?><td id="tp" style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo TOTAL;?></td>
        <td id="tp"style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo number_format($Debit,3);?></td>
        <td id="tp" style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo number_format($Credit,3);?><td>
    </tr>
 <tr>
        <td colspan="10">&nbsp;</td>
    </tr>	<tr>        <td colspan="10">&nbsp;</td>    </tr>	<tr>        <td colspan="10">&nbsp;</td>    </tr>

    <tr>
        <td style="font-size:14px;" colspan="4"><?php echo "ACCOUNTANT";?></td>
        <td></td><td style="font-size:14px;" colspan="1"></td>
        <td></td><td style="font-size:14px;" colspan="4"><?php echo "FINANCE CONTROLLER";?></td>
    </tr>
	 <tr>
        <td colspan="10">&nbsp;</td>
    </tr>
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr>
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr>
	<tr>
        <td style="font-size:14px;" colspan="3"><?php echo "RECEIVED BY : ";?></td>
        
    </tr>
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr>
	<tr>
        <td style="font-size:14px;" colspan="3"><?php echo "SIGNATURE : ";?></td>
        
    </tr>

    <!-- <tr>
        <td colspan="3"> <?php echo stripslashes($this->txtEntered); ?> </td>
        <td colspan="6">&nbsp;</td>
    </tr> -->


</tbody>
</table>
<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('cashbook_payment/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

