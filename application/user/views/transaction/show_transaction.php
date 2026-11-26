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
<script language="javascript1.1" type="text/javascript">
$(document).ready(function(e) {
    $('#ddlTransaction').change(function()
	{
		var nVal = $('#ddlTransaction').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlTransaction:nVal},
			 url:'<?php echo base_url().'index.php/document_setup/filterTransDetails/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result1').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				
					
					$('#result1').html(response);
				}
		  });
	});

});
</script>
<td><?php echo TRANSNAME;?> <span class="warning">*</span></td>
<td>
<?php echo form_dropdown('ddlTransaction', $list_trans_value, $this->ddlTransaction,'id="ddlTransaction"');?>
</td>

<span id="result1"></span>



