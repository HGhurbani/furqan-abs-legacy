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
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>

<tr>
    <td width="14%"><?php echo USERSCODE;?> <span class="warning">*</span></td>
    <td width="86%"><label>
     <input name="txtUsercode" id="txtUsercode" class="textfield" type="text" value="<?php echo stripslashes($this->txtUsercode);?>" readonly="readonly">
    </label></td>
</tr>

<tr>
    <td><?php echo COMPANYEMAIL;?> <span class="warning">*</span></td>
    <td><label>
     <input name="txtEmail" id="txtEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtEmail);?>" readonly="readonly" size="40">
    </label></td>
</tr>
</tbody></table>