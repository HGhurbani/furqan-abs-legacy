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
echo form_dropdown('ddlTktname', $list_tkt_value, $this->ddlTktname,'id="ddlTktname" class="multipleDrop"');
?>
<script>
$(document).ready(function(e) {
	$('#ddlLocation').change(function(e) {
        var nVal  = $('#ddlLocation').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlLocation:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/getStock/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#locStock').html(response);
                }
            });
    });
	
	$('#ddlTktname').change(function(e) {
		var sVal  = $('#ddlTktname').val();
		//var sVal2 = $("#ddlTktname option[value='"+sVal+"']").text();
		sVal     = sVal.split("-");
		
		$('#txtTktName').val(sVal[1]);
    });
});
</script>
            
