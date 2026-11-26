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
echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead"');?>
<script>
$(document).ready(function(e) {
    $('#ddlAccname').change(function(e) {
        var nVal  = $('#ddlAccname').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAccname:nVal},
                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountHead/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accHead').html(response);
                }
            });
    });
});
</script>
            
