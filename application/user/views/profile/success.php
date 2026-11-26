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
$.validator.setDefaults({
	submitHandler: function() {}
});

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/edit_profile/editProfile'?>');
		return false;
	});
	
});
</script>

<div class="rhtCol">
<h2 class="pageHead">Approval</h2>
    	
        	
                <p class="highlight"><b><?php echo 'You are waiting for the Approval.'; ?></b></p>
            <div class="dashContcols">
			<div align="center"><input type="button" name="btnCancel" value="Back" id="btnCancel"></div></div>
            <div class="clear"></div>
       
    </div>