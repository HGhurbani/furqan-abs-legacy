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
            <td><?php echo PARENTVENDOR;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlParentVendor', $list_parent_vendor, $this->ddlParentVendor,'id="ddlParentVendor"');?>
            </label></td>
            
            <td><?php echo EMPLOYEECODE;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlEmpCode', $list_employee, $this->ddlEmpCode,'id="ddlEmpCode"');?>
            </label></td>
        </tr>
	  </table>
	</div>
