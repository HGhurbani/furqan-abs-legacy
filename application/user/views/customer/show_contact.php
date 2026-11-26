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
            <input name="txtCName" id="txtCName" class="textfield" type="text" value="<?php echo stripslashes($this->txtCName);?>" size="40">
            </label></td>
            
            <td><?php echo CONTACTPHONE;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtCPhone" id="txtCPhone" class="textfield" type="text" value="<?php echo stripslashes($this->txtCPhone);?>" size="40">
            </label></td>
         </tr>
         <tr>
            <td><?php echo CONTACTMOBILE;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtCMobile" id="txtCMobile" class="textfield" type="text" value="<?php echo stripslashes($this->txtCMobile);?>" size="40">
            </label></td>
            
            <td><?php echo CONTACTEMAIL;?> <span class="warning">*</span></td>
            <td><label>
            <input name="txtCEmail" id="txtCEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtCEmail);?>" size="40">
            </label></td>
            
        </tr>
	  </table>
	</div>
