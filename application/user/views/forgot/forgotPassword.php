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
<!-- jquery alert -->
<script src="<?php echo base_url();?>static/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>static/js/cmxforms.js" type="text/javascript"></script>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

$(document).ready(function()
{
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

	// validate signup form on keyup and submit
	$("#frmValidate2").validate({
		rules: {
			ddlType: {
						selectNone : true
					},
		    txtEmail: {
						required : true,
						email    : true
					}
		},
		
		messages: {
			
			txtEmail: {
				required : "Email can't be blank",
				email    : "Enter valid email address"
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
<?php echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCategory', 'id' => 'frmValidate2');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>    
<div class="formHead">
<h4><?php echo FORGOTPASSWORD;?></h4>
</div>

<div class="formBorder">
<?php echo form_open('forgotpassword/showlist/'.$this->uri->segment(3),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo $this->common_model->successMsg($this->uri->segment(3));?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo EMAIL;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <input name="txtEmail" id="txtEmail" class="textfield" type="text" value="<?php echo $this->txtEmail;?>" size="40">
            </label></td>
        </tr>
		<tr class="formButs">
			<td>&nbsp;</td>
			<td><label>
				<input type="submit" name="button" id="button" value="<?php echo SUBMIT;?>" class="button" />
				<input type="submit" name="button" id="button" value="<?php echo RESET;?>" class="button" />
			</label></td>
		</tr>
    </tbody>
  </table>
</form>
  </div>
</div>

</div>
