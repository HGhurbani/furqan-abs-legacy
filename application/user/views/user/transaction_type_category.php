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
					if(!empty($trans_cat_value))
					{
			?>
                    <?php foreach($trans_cat_value as $key=>$item):?>
                    <?php
							$chkValue = array();
							//checking already selected
							if(in_array($item->nId,$chkTransType))
								$chkValue = array('checked' => 'checked');
					?>
                              <li><?php $data = array(
								  'name'        => 'chkModule[]',
								  'id'          => 'chkModule',
								  'class'       => 'chkModule',
								  'value'       => $item->nId,
								  'type'        => 'checkbox'
								);
					
					echo form_input(array_merge((array)$data,(array)$chkValue));?>
				   <?php echo stripslashes($item->vTitle);?></li>
                   <?php endforeach; ?>
                   <?php }//end if?>
                   <?php if(empty($trans_cat_value)) { echo '<li class="warning">Sorry, no records found.</li>';}?>
		</ul>
	</div>
