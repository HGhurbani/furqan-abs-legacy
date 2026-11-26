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
<!-- error value -->
<?php echo validation_errors('<p class="errorClass">','</p>'); 
$attributes = array('name' => 'frmLogin', 'id' => 'frmValidate2', 'class' => "cmxform");
?>
<?php
if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery-1.3.1.min.js"></script>
<script src="<?php echo base_url();?>static/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>static/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>static/js/cmxforms.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript1.1">
$.validator.setDefaults({
	submitHandler: function() {
	
	}
});

$(document).ready(function() {
	// validate signup form on keyup and submit
	$("#frmValidate2").validate({
		rules: {
			txtUsername: {
						required : true
					},
			txtPassword:{
				required : true
				}
		},
		messages: {
			txtUsername: {
				required : "<?php echo USERNAME;?><?php echo CANTBEBLANK;?>"
			},
			txtPassword: {
				required : "<?php echo PASSWORD;?><?php echo CANTBEBLANK;?>"
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
<!-- error value -->
<?php echo form_open('login',$attributes); ?>
<p>
    <label><input type="radio" name="ddlLanguage" id="ddlLanguage" value="eng" checked="checked"> English</label>
    <label><input type="radio" name="ddlLanguage" id="radio2" value="ar"> Arabic</label>
</p>
<div class="clearfix"></div>

<p>
    <input style="font-weight: bolder; color:#000 !important;" type="text" name="txtUsername" id="txtUsername" value="<?php echo $this->vAdmin;?>" class="usernameLogin"/>
</p>

<p>
    <input style="font-weight: bolder; color:#000 !important;" type="password" name="txtPassword" id="txtPassword" value="<?php echo $this->vPass;?>" class="usernameLogin" />
    <label class="label"><input type="checkbox" name="chkSave" value="1" /> <?php echo REMEMBERME;?></label>
    <a href="<?php echo base_url();?>index.php/forgotpassword/showlist" class="forgot"><?php echo FORGOTPASSWORD;?>?</a>
</p>

<p>
    <input type="submit" name="button" id="button" value="<?php echo LOGIN;?>" class="login">
</p>

</form>
<!-- Footer Section Start -->
<?php $this->load->view('general/footer1');?>
<!-- Footer Section End -->
                