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
$PG_TITLE = $this->common_model->ClientFilePathName($_SERVER['PHP_SELF']);
if($this->common_model->get_set_sessions()!='' && $PG_TITLE != 'work/index.php' && $PG_TITLE != 'kounts/index.php')
{
	//checking lang
	switch($this->session->userdata('lang'))
	{
		case "eng":
			$sTitle1 = 'vTitle';
			$sTitle2 = 'vModuleTitle';
		break;
		
		case "ar":
			$sTitle1 = 'vTitle2';
			$sTitle2 = 'vModuleTitle2';
		break;
	}//end switch

	$adminId 		 = $this->common_model->get_set_sessions();
	$reqId           = $this->uri->segment(3); 
	$list_menu_value = $this->common_model->get_admin_submenu($adminId, $reqId);
	
	$filedId         = 'nCategory';
	$showTitle       = $sTitle2;
	
	
	switch($reqId)
	{
		case "10":
			$showHead = 'Finance';
		break;
	}//end switch
?>
			<div class="leftMenu">
                <div class="subMenu">
                    <h6><?php echo $showHead;?></h6>
                    <?php 
					if(!empty($list_menu_value)):
					foreach($list_menu_value as $key=>$item):?>
					<?php $check = $reqId;?>
		   				<a href="<?php echo base_url();?>index.php/<?php echo $item->vFileName;?>/<?php echo $item->$filedId;?>" <?php if($item->vFileName == $PG_TITLE||$item->$filedId==$check){ echo 'class="lftActv"';}?>><?php echo $item->$showTitle;?></a>
						
						<!-- start 3rd level submenu -->
                                <?php 
									//check 3rd level sub menu exists
									$list_sub_menu2_array = $this->common_model->get_admin_submenu2($adminId,$item->nCategory,$item->nMId);
									if(!empty($list_sub_menu2_array))
									{
								?>
                               			 <ul>
											 <?php foreach($list_sub_menu2_array as $skey3=>$sitem3):?>
                                             <?php $check2 = $this->uri->segment(3);?>
                                                    <li><a href="<?php echo base_url();?>index.php/<?php echo $sitem3->vFileName;?>/<?php echo $sitem3->nCategory;?>" <?php if($sitem3->vFileName == $PG_TITLE||$sitem3->nCategory==$check2){ echo 'class="lftActv"';}?>><?php echo $sitem3->$sTitle2;?></a></li>
                                            <?php endforeach;?>
                         				 </ul>
                        			 <?php }//end if?>
                                     <!-- end 3rd level submenu --> 
					<?php endforeach; 
					endif;?>
                </div>
            </div>
<?php
}//end if
?>