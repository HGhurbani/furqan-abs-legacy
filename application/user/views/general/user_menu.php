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
<div class="tabLinks">
<?php
$PG_TITLE 		 = $this->common_model->ClientFilePathName($_SERVER['PHP_SELF']);
$adminId 		 = $this->common_model->get_set_sessions();
$catId		     = $this->uri->segment(3);
$list_menu_array = array();
$list_menu_array = $this->common_model->get_user_submenu($adminId,$PG_TITLE);

foreach($list_menu_array as $key=>$item):
?>
	<a href="<?php echo base_url();?>index.php/<?php echo $key;?>" <?php if($PG_TITLE==$item){echo 'class="tabAct"';}?>><?php echo $item;?></a>
<?php endforeach;?>
</div>
