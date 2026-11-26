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

<?php $this->load->view('general/admin_menu');

echo validation_errors('<p class="errorClass">','</p>');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<script language="javascript1.1" type="text/javascript">
$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('quiz');
	});
});
</script>
<?php echo form_open('login'); ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td class="AddTab"><?php echo $this->showTitle;?></td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="tableBorder">
  <tr bgcolor="#FFFFFF">
    <td width="25%" align="left" valign="top"><?php echo GROUPNAME;?> </td>
    <td width="75%" align="left"><input name="txtTitle"  class="textfield" type="text" value="<?php echo $this->txtTitle; ?>"></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td align="left" valign="top"><?php echo MODULEACCESS;?> </td>
    <td align="left"><?php echo $this->common_model->get_list_of_admin_modules();?></td>
  </tr>
  <tr bgcolor="#FFFFFF">
  <td>&nbsp;</td>
    <td align="left" valign="top"><input type="submit" value="<?php echo $this->btnVal; ?>" class="but" name="btnGo"/>
    <input type="reset" value="Reset" class="but" />
    <input type="button" name="btnCancel" value="<?php echo BACKTOGROUP;?>" class="but" id="btnCancel"></td>
  </tr>
</table>
</form>
