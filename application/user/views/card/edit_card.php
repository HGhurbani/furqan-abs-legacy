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
	$(function() {
		function format(acVal) {
			return acVal.name;
		}

		$(".chkSuggestion").keypress(function(e)
		{
			var nId  = this.id;
			var nVal = $('#'+this.id).val();
			$('#'+this.id).autocomplete('<?php echo base_url();?>index.php/cashbook_receipt/suggestionlist', {
				multiple: false,
				dataType: "json",
				parse: function(data) {
					return $.map(data, function(row) {
						return {
							data: row,
							value: row.name,
							result: row.name
						}
					});
				},
				formatItem: function(item) {
					return format(item);
				}
			}).result(function(e, item)
				{
					var nId  = this.id;
					$.ajax(
						{
							type:'POST',
							url:'<?php echo base_url().'index.php/cashbook_receipt/getCashreceiptDetails/'.$this->uri->segment(3);?>',
							data:{txtAcccode:format(item)},
							dataType:'html',
							beforeSend: function() {
								$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
							},
							success: function(response) {
								$('#txtAccname'+nId).val(response);
							}
						});
				});
		});
	});
	

       $('#ddlCardType').click(function () {

           var nVal = $('#ddlCardType').val();
        $.ajax({
            type:'POST',
            data:{ddlCardType:nVal},
            url:'<?php echo base_url().'index.php/card/filterCardAcccounts/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#ddlCardAc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },

            success: function(response) {
                $('#ddlCardAc').html(response);
            }

        });
        });



	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/card/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
			jQuery.validator.addMethod( 
	  "selectNone", 
		  function(value, element) { 
			if (element.value == "0") 
			{ 
			  return false; 
			} 
			else return true; 
		  }, 
		  "<?php echo PLEASESELECTANOPTION;?>" 
	); 
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtCode: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/card/checkCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/card/checkCode'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtName: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/card/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/card/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},					
			ddlCardAc: {
					selectNone : true
					},
			ddlCommision: {
					selectNone : true
					}
		},
		messages: {
			txtCode: {
				required : "<?php echo CARDCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo CARDCODEALREADYTAKEN;?>" 
			},
			txtName: {
				required : "<?php echo CARDNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo CARDNAMEALREADYTAKEN;?>" 
			}

		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
});
</script>
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCategory', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('card/editcard/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
	<tr>
        <td width="9%" align="left"><?php echo CARDTYPE;?> <span class="warning">*</span></td>
          <td width="24%" align="left"><label>
             <?php echo form_dropdown('ddlCardType', $list_card_type, $this->ddlCardType," id='ddlCardType'");?>
        </label></td>
 
    </tr>
    <tr>
        <td width="9%" align="left"><?php echo CARDCODE;?> <span class="warning">*</span></td>
          <td width="24%" align="left"><label>
             <input name="txtCode"  class="textfield" type="text" value="<?php echo $this->txtCode;?>" size="40" id="txtCode">
        </label></td>
          <td width="9%" align="left"><?php echo CARDNAME;?> <span class="warning">*</span></td>
          <td width="24%" align="left"><label>
             <input name="txtName"  class="textfield" type="text" value="<?php echo $this->txtName;?>" size="40" id="txtName">
        </label></td>
			<td width="9%" align="left"><?php echo COMMISSION;?></td>
			 <td width="25%" align="left"><label>
             <input name="txtCommission"  class="textfield5" type="text" value="<?php echo $this->txtCommission;?>" size="40" id="txtCommission">
        </label></td>
      </tr>      
	
        <tr>
            <td><?php echo ACCOUNT;?> <span class="warning">*</span></td>
            <td align="left"><label>
             <?php echo form_dropdown('ddlCardAc', $list_card_ac, $this->ddlCardAc," id='ddlCardAc'");?>
          </label></td>
            <td align="left"><?php echo SUBAC;?></td>
            <td colspan="3" align="left">
				<?php echo form_dropdown('ddlBanksubAc', $list_bank_subac, $this->ddlBanksubAc," id='ddlBanksubAc'");?>
		  	</td>
        </tr>
        
        <tr>
        <td><?php echo COMMISSIONAC;?> <span class="warning">*</span></td>
          <td align="left"><label>
             <?php echo form_dropdown('ddlCommision', $list_comm_ac, $this->ddlCommision," id='ddlCommision'");?>
          </label></td>
            <td align="left"><?php echo SUBAC;?></td>
           <td colspan="3" align="left">
  				<?php echo form_dropdown('ddlCommsubAc', $list_comm_subac, $this->ddlCommsubAc," id='ddlCommsubAc'");?>
		   </td>
      </tr>
      
      <tr>
        <td><?php echo ACTIVE;?> </td>
        <td colspan="5" align="left"><label>
          <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
      </tr>
      
      <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="5" align="left"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
               <?php if($this->uri->segment(4) == 'a')
              {?>
                 <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOCARD;?>" id="btnCancel">
          </label></td>
      </tr>
    </tbody>
  </table>
  </form>
</div>
