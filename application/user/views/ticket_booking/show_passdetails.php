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
 <tr>
    <td><?php echo PASSENGERID;?></td>
    <td><label>
    <input type="text" name="txtPassengerId" id="txtPassengerId" class="textfield3" value="<?php echo stripslashes($this->txtPassengerId);?>" />
    </label></td>
 </tr>
 
 <tr>
    <td><?php echo PASSENGERCONTACT;?></td>
    <td><label>
    <input type="text" name="txtPassengerContact" id="txtPassengerContact" class="textfield" value="<?php echo stripslashes($this->txtPassengerContact);?>" />
    </label></td>
 </tr>
 
 <tr>
    <td><?php echo REMARKS;?></td>
    <td><label>
    <textarea name="txtRemarks" id="txtRemarks" rows="2" cols="5" style="min-width: 198px;"><?php echo stripslashes($this->txtRemarks);?></textarea>
    </label></td>
 </tr>
</table>         
