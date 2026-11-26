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
<?php
echo $this->common_model->successMsg($this->uri->segment(4));
if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>

<?php $this->load->view('general/admin_menu');?>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
    <tr bgcolor="<?php echo $this->Color;?>">
      <td><?php echo $this->showLink;?></td>
    </tr>
    </tbody>
  </table>
    </div>
 </div>
</div>

