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
echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
<script>
$(document).ready(function(e) {
	var nVal  = $('#ddlLocation').val();
	$.ajax(
		{
			type:'POST',
			data:{ddlLocation:nVal,ddlTktname:'<?php echo $this->ddlTktname;?>'},
			url:'<?php echo base_url().'index.php/ticket_booking/getStock/'.$this->uri->segment(3);?>',
			dataType:'html',
			beforeSend: function() {
				$('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
			},
			success: function(response) {
				$('#locStock').html(response);
			}
		});
			
	var sVal = $('#ddlTktname').val();
	sVal     = sVal.split("-");
	
	$('#txtTktName').val(sVal[1]);
	//load location default
	
    $('#ddlLocation').change(function(e) {
        var nVal  = $('#ddlLocation').val();

		$.ajax(
		  {
			 type:'POST',
			 data:{ddlLocation:nVal},
			 url:'<?php echo base_url().'index.php/ticket_booking/getStock/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#locStock').html(response);
				}
		  });
    });
});
</script>
            
