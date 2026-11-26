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
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/airline/deleteRecord/'.$this->uri->segment(3);?>',
				 data:{subCode:dId[1]},
				 dataType:'html',
				 beforeSend: function() {
						//$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
					}
			});
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
    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
    <?php
        if(!empty($chkAccCode))
        {
            foreach($chkAccCode as $key => $aitem):
    ?>
        <tr>
           <td><?php echo CODE;?></td>
           <td><label>
           <input name="txtAccCode[]" id="txtAccCode" class="textfield22" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
           </label></td>
           
           <td><?php echo FAREVALUE;?></td>
           <td><label>
           <input name="txtAccTitle[]" id="txtAccTitle" class="textfield33" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
           </label></td>
           <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkAccID[$key]; ?>" title="Delete"/></td>
        </tr>
        <?php 
            endforeach;
        }//end if
        else
        {?>
        <tr>
           <td><?php echo CODE;?></td>
           <td><label>
           <input name="txtAccCode[]" id="txtAccCode" class="textfield22" type="text" value="" size="4">
           </label></td>
           
           <td><?php echo FAREVALUE;?></td>
           <td><label>
           <input name="txtAccTitle[]" id="txtAccTitle" class="textfield33" type="text" value="" size="40">
           </label></td>
           <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nArId;?>" title="Delete"/></td>
        </tr>
        <?php
         }//end else
        ?>
 </table>
</div>