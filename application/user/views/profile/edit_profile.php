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
	$.ajax(
		  {
			 type:'POST',
			 data:{ddlCountry:<?php echo $this->ddlCountry;?>,ddlState:<?php echo $this->ddlState;?>},
			 url:'<?php echo base_url().'index.php/filter_country/filterState/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result1').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#result1').html(response);
				}
		  });
		  
		$('#ddlCountry').change(function()
		{
			var nVal = $('#ddlCountry').val();
			$.ajax(
				  {
					 type:'POST',
					 data:{ddlCountry:nVal},
					 url:'<?php echo base_url().'index.php/filter_country/filterState/'.$this->uri->segment(3);?>',
					 dataType:'html',
					 beforeSend: function() {
							$('#result1').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
						},
						success: function(response) {
							$('#result1').html(response);
						}
				 });
		});  
	
		$.validator.addMethod("phone", function(phone_number, element) {
		var digits = "0123456789";
		var phoneNumberDelimiters = "()- ext.";
		var validWorldPhoneChars = phoneNumberDelimiters + "+";
		var minDigitsInIPhoneNumber = 10;
		s=stripCharsInBag(phone_number,validWorldPhoneChars);
		return this.optional(element) || isInteger(s) && s.length >= minDigitsInIPhoneNumber;
	}, "Please enter a valid phone number");

	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtName: {
						required : true
					},
			txtAddress: {
						required : true
					},	
			txtEmail: {
						required : true
					},	
			txtPhone: {
						required : true,
						phone : true
					},									
		    txtMobile: {
						required : true,
						number   : true
					}
		},
		messages: {
			
			txtName: {
				required : "Contact person can't be blank"
			},
			txtAddress: {
				required : "Contact Address can't be blank"
			},
			txtEmail: {
				required : "Contact Email can't be blank"
			},
			txtPhone: {
				required : "Phone can't be blank",
				phone    : "Please enter valid phone number"
			},
			txtMobile: {
				required : "Mobile can't be blank"
			},
			txtOfAddress: {
				required : "Office Address can't be blank"
			}
		},
	 success: function(label2) { 
            // set   as text for IE 
			 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
               // $(form).ajaxSubmit();
			   showVal();
             } 
		
	});
	
	function showVal()
	{
		var txtName    			= $('#txtName').val();
		var txtAddress   		= $('#txtAddress').val();
		var txtEmail    		= $('#txtEmail').val();
		var txtPhone    		= $('#txtPhone').val();
		var txtMobile    		= $('#txtMobile').val();
		var ddlCountry    		= $('#ddlCountry').val();
		var ddlState    		= $('#ddlState').val();
		var txtId				= $('#txtId').val();
		$.ajax(
			  {
				 type:'POST',
				 data:{txtName:txtName,txtAddress:txtAddress,txtEmail:txtEmail,txtPhone:txtPhone,txtMobile:txtMobile,ddlCountry:ddlCountry,ddlState:ddlState,txtId:txtId},
				 
				 url:'<?php echo base_url().'index.php/edit_profile/edit_table';?>',
				 dataType:'html',
					 beforeSend: function() {
						$('#nResult').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
						var headTitle;
						switch(response)
						{
							case 'You are waiting for the Approval.':
								headTitle = 'Thank you';
							break;
							default:
								headTitle = 'Thank you';
							break;
						}//end switch
						
						$.alerts.dialogClass = 'style_1'; // set custom style class
						jAlert(response, headTitle, function() {
								$.alerts.dialogClass = null; // reset to default
								if(headTitle = 'Thank you')
								{
									location.href = "<?php echo base_url().'index.php/edit_profile/editProfile';?>";
								}//end if
						});
					}
			  });
	}//end function
	
});

</script>
<script src="<?php echo base_url();?>static/plugins/alert/jquery.alerts.js" type="text/javascript"></script>
 <link href="<?php echo base_url();?>static/plugins/alert/jquery.alerts.css" rel="stylesheet" type="text/css">
<div class="rhtCol">
<?php

echo validation_errors('<p class="errorClass">','</p>');

$attributes = array('name' => 'frmCategory', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<h2 class="pageHead"><?php echo $this->showTitle;?></h2>
<?php $this->load->view('general/user_menu');?>
<div class="tabcont">
<h3 class="content-box-header"><?php echo $this->showTitle;?></h3>
<div class="listing">
<?php echo form_open_multipart('edit_profile/edit_table/'.$this->txtId.'/e',$attributes);?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('txtDoc', $this->txtDoc);?>
<table>
	<tbody>
	  <tr>
			<input name="txtId"  class="textfield" id="txtId" type="hidden" value="<?php echo $this->txtId;?>">
			<td width="31%">Country <span class="warning">*</span></td>
			<td width="69%"><label>
			 <?php echo form_dropdown('ddlCountry', $list_group_value, $this->ddlCountry,' id="ddlCountry"');?>
			</label></td>
	  </tr>
			
	  <tr id="result1">
	  </tr>
		
	  <tr>
		  <td>First Name <span class="warning">*</span></td>
		  <td><input name="txtName"  class="textfield" id="txtName" type="text" value="<?php echo $this->txtFName;?>"></td>
	  </tr>
	 
	  <tr>
		  <td valign="top">Email <span class="warning">*</span></td>
		  <td valign="top">
		  <input name="txtEmail" id="txtEmail"  class="textfield" type="text" readonly="readonly" value="<?php echo $this->txtEmail;?>">	
		  </td>
	  </tr>
	  
	  <tr>
		  <td valign="top">Contact Address <span class="warning">*</span></td>
		  <td valign="top">
		  <textarea name="txtAddress" id="txtAddress" rows="3" cols="44"><?php echo stripslashes($this->txtAddress);?></textarea>	
		  </td>
	  </tr>
	 
	  <tr>
		  <td valign="top">Contact Phone <span class="warning">*</span></td>
		  <td valign="top">
		  <input name="txtPhone" id="txtPhone"  class="textfield" type="text" value="<?php echo $this->txtPhone;?>">	
		  </td>
	  </tr>
	  
	  <tr>
		  <td valign="top">Contact Mobile <span class="warning">*</span></td>
		  <td valign="top">
		  <input name="txtMobile" id="txtMobile"  class="textfield" type="text" value="<?php echo $this->txtMobile;?>">	
		  </td>
	  </tr>
	  
	  <tr>
		<td>&nbsp;</td>
		<td><label>
		  <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
		  <input type="reset" value="Reset" />
		</label></td>
	  </tr>
	</tbody>
  </table>
  </form>
  </div>
