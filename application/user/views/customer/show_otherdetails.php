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
            <td><?php echo PARENTCUST;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlParentCust', $list_parent_cust, $this->ddlParentCust,'id="ddlParentCust"');?>
            </label></td>
            
            <td><?php echo EMPLOYEECODE;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlEmpCode', $list_employee, $this->ddlEmpCode,'id="ddlEmpCode"');?>
            </label></td>
        </tr>
        <tr>
           <td><?php echo CREDITDAYS;?> <span class="warning">*</span></td>
           <td><label>
            <input name="txtCreditdays" id="txtCreditdays" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCreditdays);?>" size="20">
           </label></td>
           
           <td><?php echo AMOUNT;?> <span class="warning">*</span></td>
           <td><label>
            <input name="txtAmount" id="txtAmount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAmount);?>" size="20">
           </label></td>
        </tr>
	  </table>
	</div>
