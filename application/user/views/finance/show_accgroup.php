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
<td><?php echo ACCGROUPNAME;?> <span class="warning">*</span></td>
<td>
<?php echo form_dropdown('ddlAccgroup', $list_acc_group, $this->ddlAccgroup,'id="ddlAccgroup"');?>
</td>



