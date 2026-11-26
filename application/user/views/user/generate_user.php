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
<script type="text/javascript" language="javascript">

	$("#ddlUsername").change(function()
	{
		var nVal  = $('#ddlUsername').val();
		var nVal2 = $('#radUser').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlUsername:nVal,radUser:nVal2},
			 url:'<?php echo base_url().'index.php/users/getUserdetails/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#details').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#details').html(response);
				}
		  });
	});
	
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>


        <tr>
            <td width="14%"><?php echo USERNAME;?> <span class="warning">*</span></td>
            <td width="86%"><label>
 <?php echo form_dropdown('ddlUsername', $list_user_name, $this->ddlUsername, 'id="ddlUsername"');?>
 <input type="hidden" name="radUser" id="radUser" value="<?php echo $this->radUser;?>" />
</label></td>
        </tr>

        </tbody></table>
        
<span id="details">
		</span>
