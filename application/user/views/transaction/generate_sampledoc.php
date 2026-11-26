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
    $('#txtSampledoc').click(function()
	{
		var nVal  = $('#txtPrefix').val();
		var nVal1 = $('#txtCode').val();
		var nVal2 = $('#ddlYear').val();
		var nVal3 = $('#txtMindigit').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtPrefix:nVal,txtCode:nVal1,ddlYear:nVal2,txtMindigit:nVal3},
			 url:'<?php echo base_url().'index.php/transactions/generateSampledoc/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				
					
					$('#result').html(response);
				}
		  });
	});

</script>
     <input name="txtSampledoc" id="txtSampledoc" class="textfield" type="text" value="<?php echo stripslashes($this->txtSampledoc);?>">
