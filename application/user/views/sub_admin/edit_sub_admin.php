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
			txtUName: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/sub_admin/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/sub_admin/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>

					},
			<?php
			if($this->uri->segment(4)=='a')
			{
			?>
			txtPass: {
						required : true
					},
			txtCPass: {
						required : true,
						equalTo  : '#txtPass'
					},
			<?php
			}//end if
			?>
			txtFName: {
						required : true
					},
			txtLName: {
						required : true
					},
			txtEmail: {
						required : true,
						email    : true
					},
			ddlLocation: {
				selectNone : true
			},
                       ddlEmpId: {
						required : true
					}
		},
		messages: {
			txtUName: {
				required : "<?php echo USERNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo USERALREADYTAKEN;?>"
			},
			<?php
			if($this->uri->segment(4)=='a')
			{
			?>
			txtPass: {
				required : "<?php echo PASSWORD;?><?php echo CANTBEBLANK;?>"
			},
			txtCPass: {
				required : "<?php echo CONFIRMPASSWORD;?><?php echo CANTBEBLANK;?>",
				equalTo  : "<?php echo PASSWORDDOESNOTMATCH;?>"
			},
			<?php
			}//end if
			?>
			txtFName: {
				required : "<?php echo FIRSTNAME;?><?php echo CANTBEBLANK;?>"
			},
			txtLName: {
				required : "<?php echo LASTNAME;?><?php echo CANTBEBLANK;?>"
			},
			txtEmail: {
				required : "<?php echo EMAIL;?><?php echo CANTBEBLANK;?>",
				email    : "<?php echo ENTERVALIDEMAIL;?>"
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
echo $this->common_model->successMsg($this->uri->segment(5));

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
<?php echo form_open('sub_admin/editSubAdmin/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->adminId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
	<tbody>
		<tr>
			<td align="left"><?php echo GROUPNAME;?></td>
			<td align="left"><label>
			 <?php echo form_dropdown('ddlGroup', $list_group_value, $this->ddlGroup);?>
			</label></td>
		</tr>

		<tr>
                <td><?php echo EMPLOYEE;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlEmpId', $list_emp_value, $this->ddlEmpId, 'id="ddlEmpId"');?>
                </label></td>
		</tr>

		<tr>
			<td valign="top"><?php echo USERNAME;?> <span class="warning">*</span></td>
			<td valign="top"><label>
			  <input name="txtUName" id="txtUName" class="textfield" type="text" value="<?php echo $this->txtUName;?>" size="40">
		  </label></td>
		</tr>

        <tr>
            <td valign="top"><?php echo PASSWORD;?> <span class="warning">*</span></td>
            <td valign="top" colspan="2">
            	<label><input name="txtPass"  class="textfield" type="password" value="" id="txtPass" size="40"></label>
            	<br>
            	<label style="text-transform: none;color: #ff0000;">Once type the password will be updated</label>
            </td>
        </tr>

        <tr>
            <td valign="top"><?php echo CONFIRMPASSWORD;?> <span class="warning">*</span></td>
            <td valign="top"><label><input name="txtCPass"  class="textfield" type="password" value="" id="txtCPass" size="40"></label></td>
        </tr>

      <tr>
		  <td valign="top"><?php echo LOCATION;?> <span class="warning">*</span></td>
<?php echo $this->ddlLocation;?>
          <td valign="top"> <?php echo form_multiselect('ddlLocation[]', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="multiDrop"');?> </td>

	  </tr>
	  <tr>
		  <td valign="top"><?php echo FIRSTNAME;?> <span class="warning">*</span></td>
		  <td valign="top"><label><input name="txtFName"  class="textfield" type="text" value="<?php echo $this->txtFName;?>" size="40"></label></td>
	  </tr>
	  
	  <tr>
		  <td valign="top"><?php echo LASTNAME;?>  <span class="warning">*</span></td>
		  <td valign="top"><label><input name="txtLName"  class="textfield" type="text" value="<?php echo $this->txtLName;?>" size="40"></label></td>
	  </tr>
	  
	  <tr>
		  <td valign="top"><?php echo EMAIL;?> <span class="warning">*</span></td>
		  <td valign="top"><input name="txtEmail"  class="textfield" type="text" value="<?php echo $this->txtEmail;?>" size="40"></td>
	  </tr>
	  
	  <tr>
			<td><?php echo ACTIVE;?></td>
			<td><label>
			<input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
	  </tr>
      
      <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOSUBADMIN;?>" id="btnCancel">
            </label></td>
     </tr>
	</tbody>
</table>
</form>
</div>
</div>

</div>

