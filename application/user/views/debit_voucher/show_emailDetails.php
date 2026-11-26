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
<?php $this->load->helper(array('form', 'url'));
$this->load->library('form_validation');

?>
<?php echo form_open('export_pdf/email_pdf/'.$this->uri->segment(3)."/print_debit_voucher",$attributes2); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">
<tbody>
<tr>
<td>
<?php if($this->uri->segment(4) == 'U') { ?>
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>
</td>
</tr>
    <tr>
      <td><?php echo FROM;?>&nbsp;<span class="warning">*</span></td>
        <td>
            <input name="txtFrom" id="txtFrom" class="textfield5" type="text" value="<?php echo $this->common_model->get_field_name('Location','vEmail'," WHERE nId = ". $location." ");?>" size="40" style="width: 120px;">
        </td>
    </tr>
    <tr>
      <td><?php echo To;?>&nbsp;<span class="warning">*</span></td>
        <td>
            <input name="txtTo" id="txtTo" class="textfield5" type="text" value="" > 
        </td>
    </tr>
    <tr>
      <td><?php echo BCC;?>&nbsp;</td>
        <td>
            <input name="txtBcc" id="txtBcc" class="textfield5" type="text" value="" >
        </td>
    </tr>
    <tr>
      <td><?php echo SUBJECT;?>&nbsp;</td>
        <td>
            <input name="txtSubject" id="txtSubject" class="textfield5" type="text" value="" >
        </td>
    </tr>
 
    <tr>
      <td><?php echo MESSAGE;?>&nbsp;</span></td>
        <td>
                <textarea name="txtMessage" id="txtMessage" rows="5" cols="5"></textarea>
        </td>
    </tr>
        <tr class="formButs">
        <td>&nbsp;</td>
        <td>
                <input type="submit" name="btnSend" id="btnSend" value="<?php echo SEND;?>" />
        </td>
    </tr>
<input type="hidden" name="link" id="link" value="<?php  echo base_url() . 'index.php/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3); ?>" >

</tbody>
</table>

