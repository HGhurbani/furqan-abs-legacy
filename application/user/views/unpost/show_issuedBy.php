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
?><?php  //print $item->vIssuedBy;	$em=$this->common_model->get_field_name('Admin', 'nEmpId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");	$gp=$this->common_model->get_field_name('Admin', 'nGroupId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");			//$no=$this->session->userdata('AbsAdmin');			//$this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F');			?>
<?php if($gp!=40){?>			<?php echo form_dropdown('ddlEmpl', $emp_value, $this->ddlEmpl,'id="ddlEmpl" class="ddlEmpl smallDropdown"');?>			<?php }else{?>			<td>			<select name="ddlEmpl" id="ddlEmpl" class="ddlEmpl smallDropdown" disabled="true">	<option value="<?php echo $em;?>"><?php echo $this->common_model->get_field_name('Admin', 'vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");?></option></select></td>			<?php } ?>


