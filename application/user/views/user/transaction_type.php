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
        <ul>        
			<?php
					if(!empty($trans_list_value))
					{
			?>
                    <?php foreach($trans_list_value as $key=>$item):?>
                     <?php
							$chkValue = array();
							//checking already selected
							if(in_array($item->nId,$chkTrans))
								$chkValue = array('checked' => 'checked');
					?>
                              <li><?php $data = array(
								  'name'        => 'chkModuleList'.$item->nParentId.'[]',
								  'id'          => 'chkModuleList',
								  'class'       => 'chkModuleList',
								  'value'       => $item->nId,
								  'type'        => 'checkbox'
								);
					
					echo form_input(array_merge((array)$data,(array)$chkValue));?>
                    <?php if(!empty($chkUAccess)){$this->ddlUserAccess = $chkUAccess[$item->nId];}?>
				   <?php echo stripslashes($item->vTitle);?> &nbsp;&nbsp;&nbsp;<?php echo form_dropdown('ddlUserAccess'.$item->nId, $list_user_access, $this->ddlUserAccess);?></li>
                            
                   <?php endforeach;?>
                   <?php }//end if?>
                   <?php if(empty($trans_list_value)) { echo '<li class="warning">Sorry, no records found.</li>';}?>
		</ul>
	</div>