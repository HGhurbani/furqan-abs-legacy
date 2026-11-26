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
<tr>
	<td><?php echo TRANSPREFIX;?> <span class="warning">*</span></td>
	<td><label>
	 <input name="txtPrefix" id="txtPrefix"  class="textfield" type="text" value="<?php echo stripslashes($this->txtPrefix);?>" size="2" readonly="readonly">
	</label></td>
</tr>

<tr>
	<td><?php echo TRANSMINDIGIT;?> <span class="warning">*</span></td>
	<td><label>
	 <input name="txtMindigit" id="txtMindigit" class="textfield" type="text" value="<?php echo stripslashes($this->txtMindigit);?>" size="2" readonly="readonly">
	</label></td>
</tr>

<tr>
	<td><?php echo TRANSSAMPLEDOC;?> <span class="warning">*</span></td>
	<td><label>
	 <input name="txtSampledoc" id="txtSampledoc" class="textfield" type="text" value="<?php echo stripslashes($this->txtSampledoc);?>" size="40" readonly="readonly">
	</label></td>
</tr>



