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

$(document).ready(function()
{
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtOldPass: {
						required : true,
						remote   : {url:'<?php echo base_url().'index.php/change_password/checkPass'?>',type: "post"}
					},
			txtPass: {
						required : true
					},
			txtCPass: {
						required : true,
						equalTo  : '#txtPass'
					}
		},
		messages: {
			txtOldPass: {
				required : "Old Password can't be blank",
				remote   : "Old password entered is not correct"
			},
			txtPass: {
				required : "Password can't be blank"
			},
			txtCPass: {
				required : "Confirm Password can't be blank",
				equalTo  : "Password doesn't match"
			}
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
<div class="rhtCol">
<?php
echo $this->common_model->successMsg($this->uri->segment(3));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

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
<div class="listing">
<?php echo form_open('change_password/changePass/'.$this->uri->segment(3),$attributes); ?>
<table>
	<tbody>
		
		<tr>
			<td width="31%" valign="top">Old Password <span class="warning">*</span></td>
			<td width="69%" valign="top"><label>
			  <input name="txtOldPass"  class="textfield" type="password" value="" id="txtOldPass">
		  </label></td>
		</tr>
		<tr>
		  <td valign="top">New Password <span class="warning">*</span></td>
		  <td valign="top"><label><input name="txtPass"  class="textfield" type="password" value="" id="txtPass"></label></td>
	  </tr>
		<tr>
		  <td valign="top">Confirm Password <span class="warning">*</span></td>
		  <td valign="top"><label><input name="txtCPass"  class="textfield" type="password" value="" id="txtCPass"></label></td>
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
</div>

</div>

