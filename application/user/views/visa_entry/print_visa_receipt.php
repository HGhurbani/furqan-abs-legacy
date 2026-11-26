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
<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

<script src="<?php echo base_url();?>static/js/jquery.validate.js" type="text/javascript"></script>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});


$(document).ready(function() {

        $('#toPopup4').hide();
    $('#btnPrint').click(function(e) {
        window.print() ;
    });

    $('#email').click(function(e) {
        $('#toPopup4').hide();
    });

	jQuery.validator.addMethod( 
	  "selectNone", 
		  function(value, element) { 
			if (element.value == "0") 
			{ 
			  return false; 
			} 
			else return true; 
		  }, 
		  "<?php echo PLEASESELECTANOPTION;?>" 
	); 
	
		// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTo: {
						required : true,
//						email    : true
					},
			txtFrom: {
						required : true,
//						email    : true
					},
			txtBcc: {
//						email    : true
			}
		},
		messages: {
			txtTo: {
				required : "<?php echo TO;?><?php echo CANTBEBLANK;?>",
//				email    : "<?php echo ENTERVALIDEMAIL;?>"
			},
			txtFrom: {
				required : "<?php echo FROM;?><?php echo CANTBEBLANK;?>",
//				email    : "<?php echo ENTERVALIDEMAIL;?>"
			},
			txtBcc: {
//				email    : "<?php echo ENTERVALIDEMAIL;?>"
			}
		},
	 success: function(label2) {
            // set   as text for IE
            label2.html("").addClass("checked");
        } ,
		submitHandler: function(form) {
alert("sdfdsfs");
                $(form).ajaxSubmit();
             }

	});

});
</script>


</head>
<body>
<?php if($this->uri->segment(4) == 'U') { ?> 
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>
<?php
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php $this->load->helper(array('form', 'url'));
$this->load->library('form_validation');

?>



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
            <input type="button" name="btnPrint" id="btnPrint" value="<?php echo "Print Visa Voucher";?>" class="btnGeneral">
<br>
<br> 
<!-- <div align="center"><a href="<?php echo base_url().'index.php/export_pdf/email_pdf/'.$this->uri->segment(3)."/print_cash_receipt";?>"><img src="<?php echo base_url(); ?>static/images/email_icon.jpg" alt=" " height="35" width="50" /></a> </div> -->

        </td>
    </tr>
	<style>.print_desc{	padding-left:23%;	}input{border:none !important; 	}td{	border:none !important; }</style> 
    <tr>
        <td colspan="10"  align="center"><span style="font-size: 14px;font-weight: bold;"><?php echo "VISA VOUCHER";?></span></td>
    </tr>
 
    <tr>
        <td colspan="2"><span style="font-size: 14px;font-weight: bold;"><?php echo CUSTDATE;?></span></td>
        <td colspan="2"><span style="font-size: 14px;font-weight: bold;"><div ><?php echo $this->txtDate1;?></span></div></td>
		
        <td colspan="2" align="right"><span style="font-size: 14px;font-weight: bold;"><?php echo DOCUMENTNUMBER;?></span></td>
        <td colspan="2" align="right"><span style="font-size: 14px;font-weight: bold;"><div ><?php echo $this->txtDocNumber;?></span></div></td>
		<br>
</tr>
<tr>
        <td style="font-size:14px; " colspan="2"><?php echo "FILE NO"?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="5"><div class="smallBox1"><?php echo $this->vTicktManual; ?></div></td>

    </tr>
<tr>
        <td style="font-size:14px;" colspan="2"><?php echo "LOCATION"?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="5"><div class="smallBox1"><?php 
		$loc=$this->common_model->get_field_name('Location','vCode'," WHERE nId = '". $this->ddlLocation ."'");
		echo $loc; echo"  "; echo $name; ?></div></td>
    </tr>
<tr>
        <td style="font-size:14px; " colspan="2"><?php echo "VISA TYPE"?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="5"><div class="smallBox1">
		
		<?php echo $this->common_model->get_field_name('VisaSetup','vVisaItem'," WHERE nVId = '". $this->nVisaType ."'");
		?></div></td>

    </tr>

  
	<tr>
        <td style="font-size:14px;" colspan="2"><?php echo STATUS;?></td>
        <td style="font-size:14px; font-weight: bold;" colspan="3" style=""><div class="smallBox1"><?php 
		echo $this->common_model->get_field_name('VisaSetup','vVisaItem'," WHERE nVId = '". $this->vStatus ."'");
		 ?></div></td>


    </tr>
    <tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	
<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	
	<tr>
        <td colspan="10" align = "center"><br><span style="font-size: 14px;font-weight: bold;"><?php echo "PASSANGER DETAILS";?></span><br><br><br></td>
    </tr>
	
    <tr id="tp">
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "PASSENGER" ;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "PASSPORT" ;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "DATE OF ISSUE" ;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "DATE OF EXPIRY";?></td>
		<td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "NATIONALITY";?></td>
   
	</tr>
	<?php if(!empty($chkAccID))
    {
    foreach($chkAccID as $key => $aitem){
	?>
	<tr>
            <td style="font-size:14px;"> <?php echo $vPassName[$key]; ?></td>
            <td style="font-size:14px;"> <?php echo $vPassportNo[$key]; ?></td>
            <td style="font-size:14px;" align="left" > <?php echo $dVisaIssueDate[$key] ?> </td>
            
            <td style="font-size:14px;" align="left"> <?php echo $dVisaExpiryDate[$key] ?> </td>
            <td style="font-size:14px;" align="left"> <?php echo $vNationality[$key]; ?> </td>
            
        </tr>
		
	<?php }}?>
		<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>
	

	
    <tr>
        <td colspan="10" align = "center"><br><span style="font-size: 14px;font-weight: bold;"><?php echo ACCOUNTDETAILS;?></span><br><br><br></td>
    </tr>
	
    <tr id="tp">
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo "VENDOR";?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo NETAMT;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo SLSAMT;?></td>
        <td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo PROFIT;?></td>
        <!--<td style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo DES;?></td>
-->
   
	</tr>
	
	<tr>
        <td colspan="10" align = "center"><span style="font-size: 14px;font-weight: bold;"></span></td>
    </tr>


<!-- ------------------------------------------------------------------------ -->

       <?php
        ?>
             

 <?php
    if(!empty($chkDrAccMain))
    {
    foreach($chkDrAccMain as $key => $aitem):
        


        
    ?>
    <tr>
        <td style="font-size:14px;"><?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $chkAgent[$key] ."'");?></td>
        <td style="font-size:14px;"><?php echo $chkNetamt[$key];?></td>
        <td style="font-size:14px;" ><?php echo $chkslsAmt[$key];?></td>
        <td style="font-size:14px;"><?php echo $chkProfit[$key];;?></td>
        <!--<td style="font-size:14px;" ><?php echo $chkDes[$key];?></td>-->
        
    </tr>


        <?php
		$slsAmt=$slsAmt+$chkslsAmt[$key];
		$Netamt=$Netamt+$chkNetamt[$key];
		$Profit=$Profit+$chkProfit[$key];
		
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
<td id="tp" style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo TOTAL;?></td>
 <td id="tp"style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo number_format($Netamt,3 ,'.', '');?></td>
              
		<td id="tp"style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo number_format($slsAmt,3 ,'.', '');?></td>
       <td id="tp"style="font-size:14px; border: 1px solid black !important; border-left-style:none !important; border-right-style:none !important;"><?php echo number_format($Profit,3);?></td>
       
   
	</tr>
	<tr>        <td colspan="10">&nbsp;</td>    </tr>
	
 <tr>
 <?php
                  $valCur = $this->common_model->getCompanyCurr();
                  $strCurCode = $this->common_model->currencyCode($valCur);
                  $strCurCoin = $this->common_model->currencyCoin($valCur);
                  $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                  $multiple = '1';
                  for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                  $decimal_part = ($RAmount - floor($RAmount)) * $multiple;
if($decimal_part == 0) {
?><td id="tp" colspan="10"style="font-size:14px; border: none !important; border-left-style:none !important; border-right-style:none !important;" ><?php echo "RECEIVED : ".$this->common_model->convert_number_to_words(floor($slsAmt)) . " " . $strCurCode . " " . ONLY; ?></td> 
<?php } else { ?>
        <td id="tp" colspan="10" style="font-size:14px; border: none !important; border-left-style:none !important; border-right-style:none !important;"  ><?php echo "RECEIVED : ".$this->common_model->convert_number_to_words(floor($slsAmt)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td>
<?php } ?>
 
 </tr>	<tr>        <td colspan="10">&nbsp;</td>    </tr>	

    <tr>
        <td style="font-size:14px;" colspan="4"><?php echo "ACCOUNTANT";?></td>
        <td></td><td style="font-size:14px;" colspan="1"></td>
        <td></td><td style="font-size:14px;" colspan=""><?php echo "FINANCE CONTROLLER";?></td>
    </tr>
	 <tr>
        <td colspan="10">&nbsp;</td>
    </tr>
	
	


</tbody>
</table>


</body>
</html>
