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
    $('.textfield7').keyup(function()
	{
		var nId   = this.id;
		var nVal  = $('#txtPrefix'+nId).val();
		var nVal1 = $('#'+nId).val();
		var nVal2 = $('#ddlSubAuto'+nId).val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtPrefix:nVal,txtMindigit:nVal1,ddlSubAuto:nVal2},
			 url:'<?php echo base_url().'index.php/account_head/generateSubLedgerNo/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					//$('#result2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtSublno'+nId).val(response);
				}
		  });
	});
	
	$('.Subtype').change(function()
	{
		var nId  = this.id;
		var nVal = $('#'+nId).val();
		if(nVal == 'N')
		{
			$('label#hideDetails').show();
		}//end if
		else
		{
			$('label#hideDetails').hide();
		}//end else
	});
</script>

<span class="clears"></span>
<div class="listcontnts">
<table>
<?php
if(!empty($chkAccCode))
{
	//foreach($ddlSubsale as $key => $acitem):
	foreach($chkAccCode as $key => $acitem):
	$idCnt = $key+1;
?>
<tr>
    <td><?php echo SUBSIDARYSALE;?> <span class="warning">*</span></td>
    <td><label id="hideDetails">
     <?php echo form_dropdown('ddlSubsale[]', $list_sub_sale, $ddlSubsale[$key],"id='ddlSubsale".$idCnt."' class='Subsale'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERNOT;?> <span class="warning">*</span></td>
    <td><label id="hideDetails">
     <?php echo form_dropdown('ddlSubledger[]', $list_sub_ledger, $ddlSubledger[$key],"id='ddlSubledger".$idCnt."' class='Subledger'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERTYPE;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubtype[]', $list_sub_type, $ddlSubtype[$key],"id='ddlSubtype".$idCnt."' class='Subtype'");?>
    </label></td>
    
    <td><?php echo LPO;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlLpo[]', $list_lpo, $ddlLpo[$key],"id='ddlLpo".$idCnt."' class='Lpo'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERAUTO;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubAuto[]', $list_sub_auto, $ddlSubAuto[$key],"id='ddlSubAuto".$idCnt."' class='SubAuto'");?>
    </label></td>
    
   <td><?php echo TRANSPREFIX;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtPrefix[]" id="txtPrefix<?php echo $idCnt;?>" class="textfield6" type="text" value="<?php echo stripslashes($txtPrefix[$key]);?>" size="4">
   </label></td>

   <td><?php echo TRANSMINDIGIT;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtMindigit[]" id="<?php echo $idCnt;?>" class="textfield7" type="text" value="<?php echo stripslashes($txtMindigit[$key]);?>" size="4">
   </label></td>

   <td><?php echo SUBLEDGERNUM;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtSublno[]" id="txtSublno<?php echo $idCnt;?>" class="textfield8" type="text" value="<?php echo stripslashes($txtSublno[$key]);?>" size="40">
   </label></td>
</tr>
<?php 
	endforeach;
}//end if
else
{?>
 <tr>
    <td><?php echo SUBSIDARYSALE;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubsale[]', $list_sub_sale, $this->ddlSubsale,"id='ddlSubsale' class='Subsale'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERNOT;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubledger[]', $list_sub_ledger, $this->ddlSubledger,"id='ddlSubledger' class='Subledger'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERTYPE;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubtype[]', $list_sub_type, $this->ddlSubtype,"id='ddlSubtype' class='Subtype'");?>
    </label></td>
    
    <td><?php echo LPO;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlLpo[]', $list_lpo, $this->ddlLpo,"id='ddlLpo' class='Lpo'");?>
    </label></td>
    
    <td><?php echo SUBLEDGERAUTO;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlSubAuto[]', $list_sub_auto, $this->ddlSubAuto,"id='ddlSubAuto' class='SubAuto'");?>
    </label></td>
    
   <td><?php echo TRANSPREFIX;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtPrefix[]" id="txtPrefix" class="textfield6" type="text" value="<?php echo stripslashes($this->txtPrefix);?>" size="4">
   </label></td>

   <td><?php echo TRANSMINDIGIT;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtMindigit[]" id="txtMindigit" class="textfield7" type="text" value="<?php echo stripslashes($this->txtMindigit);?>" size="4">
   </label></td>

   <td><?php echo SUBLEDGERNUM;?> <span class="warning">*</span></td>
   <td><label>
   <input name="txtSublno[]" id="txtSublno" class="textfield8" type="text" value="<?php echo stripslashes($this->txtSublno);?>" size="40">
   </label></td>
</tr>
<?php
	}//end else
?>
</table>
</div>
