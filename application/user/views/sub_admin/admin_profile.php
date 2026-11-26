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
			txtFName: {
						required : true
					},
			txtLName: {
						required : true
					},
			txtEmail: {
						required : true,
						email    : true
					}
		},
		messages: {
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
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

echo $this->common_model->successMsg($this->uri->segment(4));
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
<?php echo form_open('admin_profile/profview/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo ADMINPROFILE;?></td>
            <td width="86%" align="left"><label>
             <b><?php echo $list_value['vUsername'];?></b>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo FIRSTNAME;?></td>
            <td><label>
              <input name="txtFName"  class="textfield" type="text" value="<?php echo $list_value['vFName'];?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LASTNAME;?></td>
            <td><label>
              <input name="txtLName"  class="textfield" type="text" value="<?php echo $list_value['vLName'];?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo EMAIL;?></td>
            <td><label>
              <input name="txtEmail"  class="textfield" type="text" value="<?php echo $list_value['vEmail'];?>" size="40">
            </label></td>
        </tr>
		<?php
        if($this->common_model->get_set_sessions() == '1')
        {
        switch($list_value['vActive'])
        {
        case "1":
        $this->chkValYes = 'checked="checked"';
        break;
        
        case "0":
        $this->chkValNo = 'checked="checked"';
        break;
        }//end switch
        ?>
        <tr>
            <td><?php echo ACTIVE;?></td>
            <td><label>
             <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?> 
             &nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        <?php
        }//end if
        ?>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
              <input type="submit" name="button2" id="button2" value="<?php echo RESET;?>" />
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
