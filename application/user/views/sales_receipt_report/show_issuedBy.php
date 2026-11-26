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

if($this->session->userdata('AbsAdmin')==1){
								echo form_dropdown('ddlEmpl', $list_emp, $this->ddluser,'id="ddlEmpl" class="ddlEmpl smallDropdown "');
}else{?>
	<select name="ddlEmpl" id="ddlEmpl" class="ddlEmpl smallDropdown ">
	<option value="<?php echo $this->session->userdata('AbsAdmin');?>"><?php echo $this->common_model->get_field_name('Admin', 'vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");?></option>

</select>
<?php }
								
								?>