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
		window.location.replace('<?php echo base_url().'index.php/currency/showlist/'.$this->uri->segment(3);?>');
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
					
			 txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/currency/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/currency/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			 txtCode: {
					required : true,
					<?php
					if($this->uri->segment(5) !='')
					{
					?>
					remote   : {url:'<?php echo base_url().'index.php/currency/checkCurrcode/'.$this->uri->segment(5);?>',type: "post"}
					<?php	
					}//end if
					else
					{
					?>
					remote   : {url:'<?php echo base_url().'index.php/currency/checkCurrcode'?>',type: "post"}
					<?php
					}//end else
					?>
					},
			 txtCoin: {
					required : true
					},
			 txtRate: {
					required : true
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo CURRNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo CURRALREADYTAKEN;?>" 
			},
			txtCode: {
				required : "<?php echo CURRCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo CURRCODEALREADYTAKEN;?>" 
			},
			txtCoin: {
				required : "<?php echo CURRCOIN;?><?php echo CANTBEBLANK;?>" 
			},
			txtRate: {
				required : "<?php echo EXCHRATE;?><?php echo CANTBEBLANK;?>" 
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
	
	$('#txtCode').keyup(function()
	{
		var nVal  = $('#txtCode').val();
		
		$.ajax(
		  {
			 type:'POST',
			 url:'<?php echo base_url().'index.php/currency/getCurrentRate/'.$this->uri->segment(3);?>',
			 data:{curCode:nVal},
			 dataType:'html',
			 beforeSend: function() {
					$('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtRate').val(response);
				}
		  });
	});
});
</script>

<?php
echo $this->common_model->successMsg($this->uri->segment(5));

echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open('currency/editCurrencies/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo CURRCODE;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <input name="txtCode" id="txtCode" class="textfield" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="2">
            </label></td>
        </tr>

        <tr>
            <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle" id="txtTitle" class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo CURRCOIN;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtCoin" id="txtCoin" class="textfield" type="text" value="<?php echo stripslashes($this->txtCoin);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo EXCHRATE;?> <span class="warning">*</span></td>
            <td><label>
              <input name="txtRate" id="txtRate" class="textfield" type="text" value="<?php echo stripslashes($this->txtRate);?>" size="40">
            </label></td>
        </tr>

        <tr>
            <td><?php echo CURRDECIMAL;?> <span class="warning">*</span></td>
            <td><label>
              <input name="txtDecimal" id="txtDecimal" class="textfield" type="text" value="<?php echo stripslashes($this->txtDecimal);?>" size="40">
            </label></td>
        </tr>
 
        <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOCURR;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
