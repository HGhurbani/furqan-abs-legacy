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
		window.location.replace('<?php echo base_url().'index.php/user_type/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/user_type/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/user_type/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo USERTYPENAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo USERTYPEALREADYTAKEN;?>" 
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
echo $this->common_model->successMsg($this->uri->segment(5));

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
<?php echo form_open('user_type/editUsertype/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table>
    <tbody>
        <tr>
            <td width="31%"><?php echo USERTYPENAME;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <input name="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
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
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOUSERTYPE;?>" id="btnCancel">
            </label></td>
        </tr>

    </tbody>
  </table>
  </form>
  </div>
