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
            <td><?php echo CONTACTNAME;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtVName" id="txtVName" class="textfield" type="text" value="<?php echo stripslashes($this->txtVName);?>" size="40">
            </label></td>
            
            <td><?php echo CONTACTPHONE;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtVPhone" id="txtVPhone" class="textfield" type="text" value="<?php echo stripslashes($this->txtVPhone);?>" size="40">
            </label></td>
         </tr>
         <tr>
            <td><?php echo CONTACTMOBILE;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtVMobile" id="txtVMobile" class="textfield" type="text" value="<?php echo stripslashes($this->txtVMobile);?>" size="40">
            </label></td>
            
            <td><?php echo CONTACTEMAIL;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtVEmail" id="txtVEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtVEmail);?>" size="40">
            </label></td>
            
        </tr>
	  </table>
	</div>
