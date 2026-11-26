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
	$('#ddlFareCode').change(function()
	{
		var nVal = $('#ddlFareCode').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFareCode:nVal},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateFareValue/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					//$('#fcfare').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtFace').val(response);
				}
		  });
	});
	
</script>

<label>
<?php echo form_dropdown('ddlFareCode', $list_farecode_value, $this->ddlFareCode,'id="ddlFareCode" class="singleDrop"');?>
</label>
