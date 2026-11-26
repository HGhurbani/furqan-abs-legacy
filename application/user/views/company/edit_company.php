<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of Track Solutions Software               |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+                                                     
?>

<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});
function isInteger(s)
{ 
	var i;
	for (i = 0; i < s.length; i++)
	{
	// Check that current character is number.
		var c = s.charAt(i);
	if (((c < "0") || (c > "9"))) return false;
	}
	// All characters are numbers.
	return true;
}
function stripCharsInBag(s, bag)
{ 
	var i;
	var returnString = "";
	// Search through string's characters one by one.
	// If character is not in bag, append to returnString.
	for (i = 0; i < s.length; i++)
	{
	// Check that current character isn't whitespace.
	var c = s.charAt(i);
	if (bag.indexOf(c) == -1) returnString += c;
	}
	return returnString;
}

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/company/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	$("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd' ,minDate: new Date()});
	$("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	
	jQuery.validator.addMethod( 
	  "selectNone", 
		  function(value, element) { 
			if (element.value == "0") 
			{ 
			  return false; 
			} 
			else return true; 
		  }, 
		  "Please select an option." 
	); 
	
	$.validator.addMethod("phone", function(phone_number, element) {
		var digits = "0123456789";
		var phoneNumberDelimiters = "()- ext.";
		var validWorldPhoneChars = phoneNumberDelimiters + "+";
		var minDigitsInIPhoneNumber = 7;
		s=stripCharsInBag(phone_number,validWorldPhoneChars);
		return this.optional(element) || isInteger(s) && s.length >= minDigitsInIPhoneNumber;
	}, "Please enter a valid phone number");
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/company/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/company/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtCode: {
						required : true
					},
			txtAddress: {
						required : true
					},
			ddlCountry: {
						selectNone : true
					},
			txtPhone: {
						required : true,
						phone    : true
					},
			txtFax: {
						required : true,
						phone    : true
					},
			txtEmail: {
						required : true,
						email    : true
					},
			ddlCurrency: {
						selectNone : true
					},
			txtFromDate: {
						required : true
					},
			txtEndDate: {
						required : true
					}

		},
		messages: {
			txtTitle: {
				required : "<?php echo COMPANYNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo COMPANYNAMEALREADYTAKEN;?>" 
			},
			txtCode: {
						required : "<?php echo COMPANYCODE;?><?php echo CANTBEBLANK;?>"
					},
			txtAddress: {
						required : "<?php echo COMPANYADDR;?><?php echo CANTBEBLANK;?>"
					},
			ddlCountry: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtPhone: {
						required : "<?php echo COMPANYPHONE;?><?php echo CANTBEBLANK;?>",
						phone    : "<?php echo VALIDPHONE;?>"
					},
			txtFax: {
						required : "<?php echo COMPANYFAX;?><?php echo CANTBEBLANK;?>",
						phone    : "<?php echo VALIDPHONE;?>"
					},
			txtEmail: {
						required : "<?php echo COMPANYEMAIL;?><?php echo CANTBEBLANK;?>",
						email    : "<?php echo VALIDEMAIL;?>"
					},
			ddlCurrency: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtFromDate: {
						required : "<?php echo COMPANYFROMDATE;?><?php echo CANTBEBLANK;?>",
					},
			txtEndDate: {
						required : "<?php echo COMPANYTODATE;?><?php echo CANTBEBLANK;?>",
					},

		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<div class="rhtCol">
<?php
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<h2 class="pageHead"><?php echo $this->showTitle;?></h2>
<?php $this->load->view('general/admin_menu');?>
<div class="tabcont">
<div class="listing">
<?php echo form_open('company/editCompany/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table>
    <tbody>
        <tr>
            <td width="31%"><?php echo COMPANYNAME;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <input name="txtTitle" id="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYCODE;?></td>
          <td><label>
             <input name="txtCode" id="txtCode" class="textfield" type="text" value="<?php echo stripslashes($this->txtCode);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYADDR;?></td>
          <td><label>
             <input name="txtAddress" id="txtAddress" class="textfield" type="text" value="<?php echo stripslashes($this->txtAddress);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYADDR2;?></td>
          <td><label>
             <input name="txtAddress2" id="txtAddress2" class="textfield" type="text" value="<?php echo stripslashes($this->txtAddress2);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYADDR3;?></td>
          <td><label>
             <input name="txtAddress3" id="txtAddress3" class="textfield" type="text" value="<?php echo stripslashes($this->txtAddress3);?>">
            </label></td>
        </tr>
        
        <tr>
            <td width="31%"><?php echo COMPANYCOUNTRY;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <?php echo form_dropdown('ddlCountry', $list_country, $this->ddlCountry);?>
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYPHONE;?></td>
          <td><label>
             <input name="txtPhone" id="txtPhone" class="textfield" type="text" value="<?php echo stripslashes($this->txtPhone);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYFAX;?></td>
          <td><label>
             <input name="txtFax" id="txtFax" class="textfield" type="text" value="<?php echo stripslashes($this->txtFax);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYEMAIL;?></td>
          <td><label>
             <input name="txtEmail" id="txtEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtEmail);?>">
            </label></td>
        </tr>
        
        <tr>
            <td width="31%"><?php echo COMPANYCURRENCY;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <?php echo form_dropdown('ddlCurrency', $list_currency, $this->ddlCurrency);?>
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYFROMDATE;?></td>
          <td><label>
             <input name="txtFromDate" id="txtFromDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtFromDate);?>">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYTODATE;?></td>
          <td><label>
             <input name="txtToDate" id="txtToDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtToDate);?>">
            </label></td>
        </tr>
 
        <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        
        <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOCOMPANY;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
 </table>
</form>
</div>
</div>

</div>
