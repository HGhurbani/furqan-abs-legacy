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

<span class="clears"></span>
	<div class="listcontnts">
	  <table id="dataControl" title="dataControl">
        <tr>
            <td width="31%"><?php echo LOCATIONCTRL;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <?php echo form_dropdown('ddlLocctrl', $list_loc_ctrl, $this->ddlLocctrl,'id="ddlLocctrl"');?>
            </label></td>
        </tr>
	  </table>
	</div>
