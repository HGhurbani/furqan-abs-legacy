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
echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');                                                     
//echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead"');
?>
<script>
$(document).ready(function(e) {
    $('#ddlCurrency').change(function(e) {
        setExchangeRate();
    });
});
</script>
            
