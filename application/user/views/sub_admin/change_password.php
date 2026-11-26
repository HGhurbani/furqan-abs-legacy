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
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/sub_admin/subadminlist/'.$this->uri->segment(3);?>');
	});
	
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
				required : "<?php echo OLDPASSWORD;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo OLDPASSWORDENTEREDNOTCORRECT;?>"
			},
			txtPass: {
				required : "<?php echo PASSWORD;?><?php echo CANTBEBLANK;?>"
			},
			txtCPass: {
				required : "<?php echo CONFIRMPASSWORD;?><?php echo CANTBEBLANK;?>",
				equalTo  : "<?php echo PASSWORDDOESNOTMATCH;?>"
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
<?php
echo $this->common_model->successMsg($this->uri->segment(4));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('change_password/changePass/'.$this->uri->segment(3),$attributes); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
	<tbody>
		<tr>
			<td width="14%" align="left"><?php echo OLDPASSWORD;?> <span class="warning">*</span></td>
			<td width="86%" align="left">
			  <input name="txtOldPass"  class="textfield" type="password" value="" id="txtOldPass" size="40">
		 </td>
		</tr>
		
		<tr>
		  <td valign="top"><?php echo NEWPASSWORD;?> <span class="warning">*</span></td>
		  <td valign="top"><input name="txtPass"  class="textfield" type="password" value="" id="txtPass" size="40"></td>
	   </tr>
	  
	   <tr>
		  <td valign="top"><?php echo CONFIRMPASSWORD;?> <span class="warning">*</span></td>
		  <td valign="top"><input name="txtCPass"  class="textfield" type="password" value="" id="txtCPass" size="40"></td>
	   </tr>
	  
	   <tr class="formButs">
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <input type="reset" value="<?php echo RESET;?>" />
			</label></td>
		</tr>
		
	</tbody>
  </table>
  </form>
  </div>
		
</div>

</div>

