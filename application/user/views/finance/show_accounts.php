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
<script language="javascript" type="text/javascript">
/*------------------Custom Add new row for account------------------*/

	  $("#addRow").click(function() {
		  var m		   = $('#dataTable tbody>tr:last').clone(true).insertAfter('#dataTable tbody>tr:last');
		  var rowCount = $("#dataTable").attr('rows').length;
		   m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
		   return false;
	});
/*------------------Custom delete row ------------------*/	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>1)
		{
			$(this).parent().parent().remove();
		}//end if
		else
		{
			//var src = $(this).attr("src").replace("cross", "cross_grey_small");
			
			$('#del_'+rowCount).attr('disabled', true);
           // $(this).attr("src", src);

		}//end if
			
		});
</script>
<span class="clears"></span>
	<div class="listcontnts">
    	<table id="dataTable" title="dataTable">
        <tr>
      		<td colspan="5"><input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" /></td>
        </tr>
        <?php
			if(!empty($chkAccCode))
			{
				foreach($chkAccCode as $key => $aitem):
		?>
            <tr>
               <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
               </label></td>
               
               <td><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
               </label></td>
               <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>
            </tr>
            <?php 
				endforeach;
			}//end if
			else
			{?>
            <tr>
               <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="" size="4">
               </label></td>
               
               <td><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="" size="40">
               </label></td>
               <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>
            </tr>
            <?php
				}//end else
			?>
		</table>
	</div>
