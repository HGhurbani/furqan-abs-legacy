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
		window.location.replace('<?php echo base_url().'index.php/stock/showlist/'.$this->uri->segment(3);?>');
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
						remote   : {url:'<?php echo base_url().'index.php/stock/checkCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/stock/checkCode'?>',type: "post"}
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
						remote   : {url:'<?php echo base_url().'index.php/stock/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/stock/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},					
			ddlTrans: {
					selectNone : true
					}
		},
		messages: {
			txtCode: {
				required : "<?php echo STOCKCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo STOCKCODEALREADYTAKEN;?>" 
			},
			txtName: {
				required : "<?php echo STOCKNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo STOCKNAMEALREADYTAKEN;?>" 
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
<?php echo form_open('stock/editstock/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
	<tr>
        <td width="9%" align="left"><?php echo STOCKCODE;?> <span class="warning">*</span></td>
          <td width="24%" align="left"><label>
             <input name="txtCode"  class="textfield" type="text" value="<?php echo $this->txtCode;?>" size="40" id="txtCode">
        </label></td>
          <td width="9%" align="left"><?php echo STOCKNAME;?> <span class="warning">*</span></td>
          <td width="24%" align="left"><label>
             <input name="txtName"  class="textfield" type="text" value="<?php echo $this->txtName;?>" size="40" id="txtName">
        </label></td>
			<td width="9%" align="left"><?php echo AIRLINE;?></td>
			 <td width="25%" align="left"><label>
             <?php echo form_dropdown('ddlAirline', $list_airline, $this->ddlAirline," id='ddlAirline'");?>
        </label></td>
      </tr>      
	
        <tr>
		 <td width="9%" align="left"><?php echo GDS;?></td>
          <td width="24%" align="left"><label>
             <?php echo form_dropdown('ddlGds', $list_gds, $this->ddlGds," id='ddlGds'");?>
          </label></td>
            <td width="9%" align="left"><?php echo TRANSNAME;?> <span class="warning">*</span></td>
            <td width="24%" align="left"><label>
             <?php echo form_dropdown('ddlTrans', $list_trans, $this->ddlTrans," id='ddlTrans'");?>
          </label></td>

            <td width="9%" align="left"><?php echo PREFIX;?> <span class="warning">*</span></td>
            <td width="25%" align="left"><label>
                <input name="txtPrefix"  class="textfield" type="text" value="<?php echo $this->txtPrefix;?>" id="txtPrefix">
            </label></td>

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
				  <input type="button" name="btnCancel" value="<?php echo BACKTOSTOCK;?>" id="btnCancel">
			  </label></td>
			</tr>
    </tbody>
  </table>
  </form>
</div>
