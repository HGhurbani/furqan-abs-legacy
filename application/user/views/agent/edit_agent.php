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
		window.location.replace('<?php echo base_url().'index.php/agent/showlist/'.$this->uri->segment(3);?>');
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

	$("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});

	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			 ddlFinance: {
					selectNone : true
					},
			 txtCode: {
					required : true,
<?php /*?>					<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/agent/checkCustcode/'.$this->uri->segment(5);?>',type: "post"},
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/agent/checkCustcode'?>',type: "post"},
						<?php
						}//end else
						?>
<?php */?>					},
			 txtName: {
					required : true
			 		},
			 txtEmail: {
					<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/agent/checkEmail/'.$this->uri->segment(5);?>',type: "post"},
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/agent/checkEmail'?>',type: "post"},
						<?php
						}//end else
						?>
					email    : true
			 		}/*,
			 txtAddress: {
					required : true
			 		},
			 txtPhone: {
					required : true,
					number   : true
			 		},
			 txtMobile: {
					required : true,
					number   : true
			 		},
			 ddlIdType: {
					selectNone : true
					},
			 txtCivilId: {
					required : true
			 		},
			 'ddlLocation[]': {
					selectNone : true
					},
			 ddlCountry: {
					selectNone : true
					},
			 ddlCurrency: {
					selectNone : true
					},
			 ddlServCalc: {
					selectNone : true
					},
			 txtServCalc: {
					required : true
			 		},
			 ddlDiscCalc: {
					selectNone : true
					},
			 txtDiscCalc: {
					required : true
			 		},
			 ddlParentAgent: {
					selectNone : true
					},
			 ddlEmpCode: {
					selectNone : true
					},
			 ddlAgentAc: {
					selectNone : true
					}*/
		},
		messages: {
			ddlFinance: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			txtCode: {
					required : "<?php echo TRANSCODE;?><?php echo CANTBEBLANK;?>" <?php /*?>,
					remote   : "<?php echo ACCODEALREADYTAKEN;?>"<?php */?>
					},
			txtName: {
					required : "<?php echo AGENTNAME;?><?php echo CANTBEBLANK;?>"
					},
			txtEmail: {
					email    : "<?php echo VALIDEMAIL;?>",
					remote   : "<?php echo EMAILALREADYTAKEN;?>"
					}/*,
			txtAddress: {
					required : "<?php echo LOCATIONADDR;?><?php echo CANTBEBLANK;?>"
					},
			txtPhone: {
					required : "<?php echo COMPANYPHONE;?><?php echo CANTBEBLANK;?>",
					number   : "<?php echo VALIDPHONE;?>"
					},
			txtMobile: {
					required : "<?php echo CUSTMOBILE;?><?php echo CANTBEBLANK;?>",
					number   : "<?php echo VALIDPHONE;?>"
					},
			ddlIdType: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			txtCivilId: {
					required : "<?php echo IDDETAILS;?><?php echo CANTBEBLANK;?>" 
					},
			'ddlLocation[]': {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlCountry: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlCurrency: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlServCalc: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			txtServCalc: {
					required : "<?php echo SERVAMT;?><?php echo CANTBEBLANK;?>" 
					},
			ddlDiscCalc: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			txtDiscCalc: {
					required : "<?php echo DISCAMT;?><?php echo CANTBEBLANK;?>" 
					},
			ddlParentAgent: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlEmpCode: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlAgentAc: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					}*/
		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
	
    $('#ddlFinance').change(function()
	{
		var nVal  = $('#ddlFinance').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFinance:nVal},
			 url:'<?php echo base_url().'index.php/agent/generateSubLedgerNo/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#code').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					//alert(response)
					$('#code').html(response);
				}
		  });
	});
	
});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

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
<?php echo form_open('agent/editAgent/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtDate" id="txtDate" class="textfield" type="text" value="<?php echo date('Y-m-d');?>" size="20">
            </label></td>
            
            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <?php echo form_dropdown('ddlFinance', $list_finance, $this->ddlFinance, 'id="ddlFinance"');?>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo TRANSCODE;?> <span class="warning">*</span></td>
            <td id="code"><label>
             <input name="txtCode" id="txtCode" class="textfield" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="20">
            </label></td>
            
            <td><?php echo TAXNAME;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <input name="txtName" id="txtName" class="textfield" type="text" value="<?php echo stripslashes($this->txtName);?>" size="40">
            </label></td>
        </tr>
        

        <tr> 
            <td><?php echo CUSTSERVCALC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlServCalc', $list_calculation, $this->ddlServCalc,'id="ddlServCalc"');?>
            </label></td>
 
           <td><?php echo AMOUNT;?></td>
           <td colspan="3"><label>
            <input name="txtAmount" id="txtAmount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAmount);?>" size="20">
           </label></td>
         </tr>
              <tr>
            <td><?php echo LOCATIONNAME;?></td>
            <td colspan="5"><label>
             <?php echo form_multiselect('ddlLocation[]', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="multiDrop"');?>
            </label></td>
        </tr>
    
        <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="5"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
		
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="5"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOAGENT;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
