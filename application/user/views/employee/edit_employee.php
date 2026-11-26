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
		window.location.replace('<?php echo base_url().'index.php/employee/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	$("#txtDueDate").datepick({ dateFormat: 'yyyy-mm-dd' ,minDate: new Date()});
	
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
						remote   : {url:'<?php echo base_url().'index.php/employee/checkCustcode/'.$this->uri->segment(5);?>',type: "post"},
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/employee/checkCustcode'?>',type: "post"},
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
						remote   : {url:'<?php echo base_url().'index.php/employee/checkEmail/'.$this->uri->segment(5);?>',type: "post"},
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/employee/checkEmail'?>',type: "post"},
						<?php
						}//end else
						?>
					email    : true
			 		},
			 txtGallileoCD: {
					required : true
			 		},
			 txtAmadeusCD: {
					required : true
			 		},
			 txtSabre: {
					required : true
			 		},
			 'ddlLocation[]': {
					selectNone : true
					},
			 ddlCurrency: {
					selectNone : true
					}
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
					required : "<?php echo EMPNAME;?><?php echo CANTBEBLANK;?>"
					},
			txtEmail: {
					email    : "<?php echo VALIDEMAIL;?>",
					remote   : "<?php echo EMAILALREADYTAKEN;?>"
					},
			 txtGallileoCD: {
					required : "<?php echo GALLILEOCD;?><?php echo CANTBEBLANK;?>" 
			 		},
			 txtAmadeusCD: {
					required : "<?php echo AMADEUSCD;?><?php echo CANTBEBLANK;?>" 
			 		},
			 txtSabre: {
					required : "<?php echo SABRE;?><?php echo CANTBEBLANK;?>" 
			 		},
			'ddlLocation[]': {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlCurrency: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
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
	
    $('#ddlFinance').change(function()
	{
		var nVal  = $('#ddlFinance').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFinance:nVal},
			 url:'<?php echo base_url().'index.php/employee/generateSubLedgerNo/'.$this->uri->segment(3);?>',
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
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('employee/editEmployee/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nCheck', 'frmPost');?>
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
            
            <td><?php echo EMPNAME;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <input name="txtName" id="txtName" class="textfield" type="text" value="<?php echo stripslashes($this->txtName);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LOCATIONADDR;?></td>
            <td><label>
             <textarea name="txtAddress" id="txtAddress" rows="5" cols="40"><?php echo stripslashes($this->txtAddress);?></textarea>
            </label></td>
            
            <td><?php echo COMPANYEMAIL;?></td>
            <td colspan="3"><label>
             <input name="txtEmail" id="txtEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtEmail);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LOCATIONPHONE;?></td>
            <td><label>
             <input name="txtPhone" id="txtPhone" class="textfield" type="text" value="<?php echo stripslashes($this->txtPhone);?>" size="40">
            </label></td>
            
            <td><?php echo CUSTMOBILE;?></td>
            <td><label>
             <input name="txtMobile" id="txtMobile" class="textfield" type="text" value="<?php echo stripslashes($this->txtMobile);?>" size="40">
            </label></td>

            <td><?php echo COMPANYFAX;?></td>
            <td><label>
             <input name="txtFax" id="txtFax" class="textfield" type="text" value="<?php echo stripslashes($this->txtFax);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo COMPANYCOUNTRY;?></td>
            <td><label>
             <?php echo form_dropdown('ddlCountry', $list_country, $this->ddlCountry,'id="ddlCountry"');?>
            </label></td>
        
            <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <?php echo form_dropdown('ddlCurrency', $list_currency, $this->ddlCurrency,'id="ddlCurrency"');?>
            </label></td>
        </tr>

        <tr>
            <td><?php echo IDTYPE;?></td>
            <td><label>
             <?php echo form_dropdown('ddlIdType', $list_idType_value, $this->ddlIdType,'id="ddlIdType"');?>
            </label></td>
        
            <td><?php echo IDDETAILS;?></td>
            <td colspan="3"><label>
             <input name="txtCivilId" id="txtCivilId" class="textfield" type="text" value="<?php echo stripslashes($this->txtCivilId);?>" size="40">
            </label></td>
        </tr>


        <tr> 
           <td><?php echo CREDITDAYS;?></td>
           <td><label>
            <input name="txtCreditdays" id="txtCreditdays" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCreditdays);?>" size="20">
           </label></td>
           
           <td><?php echo AMOUNT;?></td>
           <td colspan="3"><label>
            <input name="txtAmount" id="txtAmount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAmount);?>" size="20">
           </label></td>
         </tr>
        
        <tr>
            <td><?php echo CUSTSERVCALC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlServCalc', $list_calculation, $this->ddlServCalc,'id="ddlServCalc"');?>
            </label></td>
            
             <td><?php echo SERVAMT;?></td>
			 <td colspan="5"><label><input name="txtServCalc" id="txtServCalc" class="textfield2" type="text" value="<?php echo stripslashes($this->txtServCalc);?>"></label></td>
        </tr>

		<tr>
            <td><?php echo CUSTDISCCALC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlDiscCalc', $list_calculation, $this->ddlDiscCalc,'id="ddlDiscCalc"');?>
            </label></td>

            <td><?php echo DISCAMT;?></td>
            <td colspan="5"><label><input name="txtDiscCalc" id="txtDiscCalc" class="textfield2" type="text" value="<?php echo stripslashes($this->txtDiscCalc);?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo GALLILEOCD;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtGallileoCD" id="txtGallileoCD" class="textfield" type="text" value="<?php echo stripslashes($this->txtGallileoCD);?>" size="40">
            </label></td>
            
            <td><?php echo AMADEUSCD;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtAmadeusCD" id="txtAmadeusCD" class="textfield" type="text" value="<?php echo stripslashes($this->txtAmadeusCD);?>" size="40">
            </label></td>
            
            <td><?php echo SABRE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtSabre" id="txtSabre" class="textfield" type="text" value="<?php echo stripslashes($this->txtSabre);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LOCATIONNAME;?></td>
            <td><label>
                <?php echo form_dropdown('ddlLocation1', $list_location, $this->ddlLocation1,'id="ddlLocation1"');?>
             <!--<input name="txtLocation" id="txtLocation" class="textfield" type="text" value="<?php /*echo stripslashes($this->txtLocation);*/?>" size="40">-->
            </label></td>
            
            <td><?php echo EMPDESNAME;?></td>
            <td><label>
             <?php echo form_dropdown('ddlDesignation', $list_des_value, $this->ddlDesignation,'id="ddlDesignation"');?>
            </label></td>
            
            <td><?php echo EMPLOANAC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlGroupMgr', $list_employee_ac, $this->ddlGroupMgr,'id="ddlGroupMgr"');?>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo EMPRECAC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlEmpRAc', $list_employee_rac, $this->ddlEmpRAc,'id="ddlEmpRAc"');?>
            </label></td>
        
            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td colspan="5"><label>
             <?php echo form_multiselect('ddlLocation[]', $list_location, $this->ddlLocation,'id="ddlLocation" class="multiDrop"');?>
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOEMP;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
