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
<td><?php echo DOCUMENTNUMBER;?> <span class="warning">*</span></td>
<td width="22%"><label>
 <input name="txtDocNumber" id="txtDocNumber" class="textfield" type="text" value="<?php echo stripslashes($this->txtDocNumber);?>" size="10" readonly="readonly">
</label></td>

<td width="14%" align="left"><?php echo TRANSMODE;?> <span class="warning">*</span></td>    
<td width="51%" align="left"><label>
  <input name="txtMode" id="txtMode" class="textfield" type="text" value="<?php echo stripslashes($this->txtMode);?>" size="10" readonly="readonly">
</label></td>
