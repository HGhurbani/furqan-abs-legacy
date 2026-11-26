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
      <?php if($this->uri->segment(4) == 'e')
	  {?>
	  	  $.ajax(
		  {
			 type:'POST',
			 data:{ddlTransaction:<?php echo $this->ddlTranstype;?>,ddlTranstype:<?php echo $this->ddlTransaction;?>,ddlSalestype:<?php echo $this->ddlSalestype;?>,ddlFlag:<?php echo $this->ddlFlag;?>},
			 url:'<?php echo base_url().'index.php/travel_setup/generateTravelaccount/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#travel').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				$('#travel').html(response);
				}
		  });

	  <?php }//end if?>
	$('#ddlTransaction').change(function()
	{
	  var nVal  = $('#ddlTransaction').val();
	  var nVal2 = $('#ddlTranstype').val();
	  var nVal3 = $('#ddlSalestype').val();
	  var nVal4 = $('#ddlFlag').val();

	  $.ajax(
		  {
			 type:'POST',
			 data:{ddlTransaction:nVal,ddlTranstype:nVal2,ddlSalestype:nVal3,ddlFlag:nVal4},
			 url:'<?php echo base_url().'index.php/travel_setup/generateTravelaccount/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#travel').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				$('#travel').html(response);
				}
		  });
	});

</script>
<td><?php echo TRANSNAME;?> <span class="warning">*</span></td>
<td>
<?php echo form_dropdown('ddlTransaction', $list_trans_value, $this->ddlTransaction,'id="ddlTransaction"');?>
</td>

<span id="travel">
</span>

