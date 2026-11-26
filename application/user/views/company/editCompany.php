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
function isInteger(s)
{ 
	var i;
	for (i = 0; i < s.length; i++)
	{
	  // Check that current character is number.
		var c = s.charAt(i);
	if (((c < "0") || (c > "9"))) return false;
	}
	// All characters are numbers.
	return true;
}
function stripCharsInBag(s, bag)
{ 
	var i;
	var returnString = "";
	// Search through string's characters one by one.
	// If character is not in bag, append to returnString.
	for (i = 0; i < s.length; i++)
	{
	// Check that current character isn't whitespace.
	var c = s.charAt(i);
	if (bag.indexOf(c) == -1) returnString += c;
	}
	return returnString;
}

$(document).ready(function()
{
	$('#ddlDivision').change(function()
	{
		var nVal = $('#ddlDivision').val();
		
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlDivision:nVal},
			 url:'<?php echo base_url().'index.php/company/filterLocation/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#result').html(response);
				}
		  });
	});
		
	$("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	$("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	
	jQuery.validator.addMethod( 
	  "selectNone", 
		  function(value, element) { 
			if (element.value == "0") 
			{ 
			  return false; 
			} 
			else return true; 
		  }, 
		  "Please select an option." 
	); 
	
	$.validator.addMethod("phone", function(phone_number, element) {
		var digits = "0123456789";
		var phoneNumberDelimiters = "()- ext.";
		var validWorldPhoneChars = phoneNumberDelimiters + "+";
		var minDigitsInIPhoneNumber = 7;
		s=stripCharsInBag(phone_number,validWorldPhoneChars);
		return this.optional(element) || isInteger(s) && s.length >= minDigitsInIPhoneNumber;
	}, "Please enter a valid phone number");
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTitle: {
						required : true
					},
			txtCode: {
						required : true
					},
			txtAddress: {
						required : true
					},
			ddlCountry: {
						selectNone : true
					},
			txtPhone: {
						required : true
					},
			txtFax: {
						required : true,
						phone    : true
					},
			txtEmail: {
						required : true,
						email    : true
					},
			ddlCurrency: {
						selectNone : true
					},
			txtFromDate: {
						required : true
					},
			txtToDate: {
						required : true
					},
			txtPic: {
						accept : "gif|jpg|jpeg|png"
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo COMPANYNAME;?><?php echo CANTBEBLANK;?>"<?php /*?>,
				remote   : "<?php echo COMPANYNAMEALREADYTAKEN;?>" <?php */?>
			},
			txtCode: {
						required : "<?php echo COMPANYCODE;?><?php echo CANTBEBLANK;?>"
					},
			txtAddress: {
						required : "<?php echo COMPANYADDR;?><?php echo CANTBEBLANK;?>"
					},
			ddlCountry: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtPhone: {
						required : "<?php echo COMPANYPHONE;?><?php echo CANTBEBLANK;?>"
					},
			txtFax: {
						required : "<?php echo COMPANYFAX;?><?php echo CANTBEBLANK;?>",
						phone    : "<?php echo VALIDPHONE;?>"
					},
			txtEmail: {
						required : "<?php echo COMPANYEMAIL;?><?php echo CANTBEBLANK;?>",
						email    : "<?php echo VALIDEMAIL;?>"
					},
			ddlCurrency: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtFromDate: {
						required : "<?php echo COMPANYFROMDATE;?><?php echo CANTBEBLANK;?>"
					},
			txtToDate: {
						required : "<?php echo COMPANYTODATE;?><?php echo CANTBEBLANK;?>"
					},
			txtPic: {
						accept : "<?php echo INVALIDFILE;?>"
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
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<?php
    echo $this->common_model->successMsg($this->uri->segment(4));
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
	<?php echo form_open_multipart('company/editCompany/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>
   
    <div class="formBorder">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
	   <tr>
          <td width="14%" align="left"><?php echo COMPANYCODE;?></td>
          <td width="36%" align="left"><label>
             <input name="txtCode" id="txtCode" class="textfield3" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="2">
            </label></td>
            
            <td><?php echo COMPANYNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle" id="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYADDR;?></td>
          <td><label>
          	 <textarea name="txtAddress" id="txtAddress" cols="30" rows="5"><?php echo stripslashes($this->txtAddress);?></textarea>
            </label></td>
            
          <td><?php echo COMPANYADDR2;?></td>
          <td><label>
             <textarea name="txtAddress2" id="txtAddress2" cols="30" rows="5"><?php echo stripslashes($this->txtAddress2);?></textarea>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo COMPANYCOUNTRY;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlCountry', $list_country, $this->ddlCountry,'id="ddlCountry"');?>
            </label></td>
            
            <td><?php echo DIVISIONNAME;?> </td>
            <td><label>
             <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
            </label></td>
            
            <td><?php echo LOCATIONNAME;?> </td>
            <td id="result"><label>
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
            </label></td>
        </tr>

        <tr>
          <td><?php echo COMPANYPHONE;?></td>
          <td><label>
             <input name="txtPhone" id="txtPhone" class="textfield" type="text" value="<?php echo stripslashes($this->txtPhone);?>" size="40">
            </label></td>
            
          <td><?php echo COMPANYFAX;?></td>
          <td><label>
             <input name="txtFax" id="txtFax" class="textfield" type="text" value="<?php echo stripslashes($this->txtFax);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYWEB;?></td>
          <td><label>
             <input name="txtWeb" id="txtWeb" class="textfield" type="text" value="<?php echo stripslashes($this->txtWeb);?>" size="40" style="text-transform:lowercase;">
            </label></td>

            <td><?php echo COMPANYCURRENCY;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlCurrency', $list_currency, $this->ddlCurrency);?>
            </label></td>
        </tr>

        <tr>
          <td><?php echo COMPANYEMAIL;?></td>
          <td><label>
             <input name="txtEmail" id="txtEmail" class="textfield" type="text" value="<?php echo stripslashes($this->txtEmail);?>" size="40" style="text-transform:lowercase;">
            </label></td>
        </tr>
        
        <tr>
          <td><?php echo COMPANYFROMDATE;?></td>
          <td><label>
             <input name="txtFromDate" id="txtFromDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtFromDate);?>" size="40">
            </label></td>
        
          <td><?php echo COMPANYTODATE;?></td>
          <td><label>
             <input name="txtToDate" id="txtToDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtToDate);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td valign="top"><?php echo IMAGE;?> <span class="warning">*</span></td>
            <td valign="top"><input type="file" name="txtPic" value="<?php echo set_value('txtPic'); ?>"  id="txtPic"/>
             <br /><br /> <span class="warning"><strong><?php echo '( '.IMAGESIZE.' )';?></strong></span></td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td valign="top"><?php 
            $image_properties = array(
            'src' => 'upload/company/'.$this->txtPic,
            'class' => '',
            'width' => '100',
            'height' => '100',
            'rel' => 'lightbox',
            'onerror'=>"this.src='".base_url()."upload/noimage.jpg'",
            );
            echo img($image_properties);?></td>
        </tr>

        <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="3"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOCOMPANY;?>" id="btnCancel">
            </label></td>
        </tr>
  </table>
    </form>

    </div>
 </div>
</div>
