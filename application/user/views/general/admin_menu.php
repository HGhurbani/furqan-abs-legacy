<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of T-Countz Software                      |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+                                                     
/*?>

<div class="tabLinks">
<?php
$PG_TITLE = $this->common_model->ClientFilePathName($_SERVER['PHP_SELF']);

$adminId 		 = $this->common_model->get_set_sessions();
$catId		     = $this->uri->segment(3);
$list_menu_array = array();
$list_menu_array = $this->common_model->get_admin_submenu($adminId,$catId);
if(!empty($list_menu_array))
{
		//checking lang
		switch($this->session->userdata('lang'))
		{
			case "eng":
				$sTitle2 = 'vModuleTitle';
			break;
			
			case "ar":
				$sTitle2 = 'vModuleTitle2';
			break;
		}//end switch

	foreach($list_menu_array as $key=>$item):
	?>
		<a href="<?php echo base_url();?>index.php/<?php echo $item->vFileName;?>/<?php echo $item->nCategory;?>" <?php if($PG_TITLE==$item->vFileName){echo 'class="tabAct"';}?>><?php echo $item->$sTitle2;?></a>
	<?php endforeach;?>
<?php }//end if ?>
</div>
<?php */?>
