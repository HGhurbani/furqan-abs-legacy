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
	$('tr.Exist').hide();
	
	$(".radType").click(function()
	{
		var nVal = $('.radType:checked').val();
		if(nVal == 'New')
		{
			$('.New').show();
			$('tr.Exist').hide();
		}
		if(nVal == 'Exist')
		{
			$('.New').hide();
			$('tr.Exist').show();
		}
	});

	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/transactions/showlist/'.$this->uri->segment(3);?>');
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
						remote   : {url:'<?php echo base_url().'index.php/transactions/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/transactions/checkTitle'?>',type: "post"}
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
						remote   : {url:'<?php echo base_url().'index.php/transactions/checkCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/transactions/checkCode'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			 ddlLocation: {
				 	selectNone : true
			 		},
			 txtPrefix: {
					required : true
					},
			 ddlYear: {
					selectNone : true
					},
			 txtMindigit: {
					required : true
					},
			 txtSampledoc: {
					required : true
					},
			 ddlYearRes: {
					selectNone : true
					},
			 txtDes: {
					required : true
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo TRANSNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo TRANSALREADYTAKEN;?>" 
			},
			txtCode: {
				required : "<?php echo TRANSCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo TRANSCODEALREADYTAKEN;?>"
			},
			txtPrefix: {
				required : "<?php echo TRANSPREFIX;?><?php echo CANTBEBLANK;?>"
			},
			txtMindigit: {
				required : "<?php echo TRANSMINDIGIT;?><?php echo CANTBEBLANK;?>" 
			},
			txtSampledoc: {
				required : "<?php echo TRANSSAMPLEDOC;?><?php echo CANTBEBLANK;?>" 
			},
			txtDes: {
				required : "<?php echo TRANSDES;?><?php echo CANTBEBLANK;?>"
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
	
	
	
	$('#ddlLocation').change(function()
	{
		sampleCode();
	});

	$('#ddlYear').click(function()
	{
		sampleCode();
	});
	
	$('#txtMindigit').keyup(function()
	{
		sampleCode();
	});
	
	$('#txtPrefix').keyup(function()
	{
		sampleCode();
	});
	
	function sampleCode()
	{
		var nVal  = $('#txtPrefix').val();
		var nVal1 = $('#txtCode').val();
		var nVal2 = $('#ddlYear').val();
		var nVal3 = $('#txtMindigit').val();
		var nVal4 = $('#ddlLocation').val();
		
		$.ajax({
            type:'POST',
            data:{txtPrefix:nVal,txtCode:nVal1,ddlYear:nVal2,txtMindigit:nVal3,ddlLocation:nVal4},
            url:'<?php echo base_url().'index.php/transactions/generateSampledoc/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#txtSampledoc').val(response);
            }
        });
	}//end function
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
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('transactions/editTransactions/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td><?php echo TRANSCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtCode" id="txtCode" class="textfield3" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="4">
            </label></td>
            
            <td><?php echo TRANSNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle" id="txtTitle" class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
       </tr>
        
       <tr>
            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
            </label></td>
            
            <td><?php echo TRANSPREFIX;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtPrefix" id="txtPrefix" class="txtPrefix" type="text" value="<?php echo stripslashes($this->txtPrefix);?>" size="4">
            </label></td>
       </tr>
        
       <tr>
            <td><?php echo TRANSYEAR;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlYear', $list_year, $this->ddlYear, 'id="ddlYear"');?>
            </label></td>
       </tr>
 
       <tr>
            <td><?php echo TRANSMINDIGIT;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtMindigit" id="txtMindigit" class="txtMindigit" type="text" value="<?php echo stripslashes($this->txtMindigit);?>" size="4">
            </label></td>
            
            <td><?php echo TRANSSAMPLEDOC;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtSampledoc" id="txtSampledoc" class="textfield" type="text" value="<?php echo stripslashes($this->txtSampledoc);?>" readonly="readonly" size="40">
            </label></td>
       </tr>

       <tr>
            <td><?php echo TRANSYEARLYRESET;?> <span class="warning">*</span></td>
            <td><label>
              <input type="radio" name="chkYearlyReset" value="Y" <?php echo $this->chkValYearlyYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkYearlyReset" value="N" <?php echo $this->chkValYearlyNo;?>/>&nbsp;<?php echo NO;?></label></td>
              
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
       </tr>
       
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="3"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOTRANS;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
