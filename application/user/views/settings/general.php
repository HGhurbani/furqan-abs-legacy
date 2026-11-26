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

	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTitle: {
						required : true
					},
			
			txtEmail: {
						required : true,
						email    : true
					},
		    txtNoPerPage: {
						required : true,
						number:true
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo COMPANYNAME;?><?php echo CANTBEBLANK;?>"
			},
			txtEmail: {
				required : "<?php echo EMAIL;?><?php echo CANTBEBLANK;?>",
				email    : "<?php echo ENTERVALIDEMAILADDRESS;?>"
			},
			txtNoPerPage: {
				required : "<?php echo NOOFRECORDS;?><?php echo CANTBEBLANK;?>",
				number:"<?php echo ENTERVALIDNUMBER;?>"
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
echo validation_errors('<p class="errorClass">','</p>');
echo $this->common_model->successMsg($this->uri->segment(4));
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
<?php echo form_open_multipart('general/editGeneral/'.$this->uri->segment(3).'/e',$attributes); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo COMPANYNAME;?>  <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <input type="text" name="txtTitle" id="txtTitle" class="textfieldLarge" value="<?php echo $this->txtTitle;?>" />
            </label></td>
        </tr>
    
        <tr>
            <td width="14%" align="left"><?php echo EMAIL;?>  <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <input type="text" name="txtEmail" id="txtEmail" value="<?php echo $this->txtEmail;?>" size="43" />
            </label></td>
        </tr>

        <tr>
            <td><?php echo NOOFRECORDS;?> <span class="warning">*</span></td>
            <td><label>
             <input type="text" name="txtNoPerPage" id="txtNoPerPage" value="<?php echo $this->txtNoPerPage;?>" class="pricebox" />
            </label></td>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal; ?>" />
              <input type="reset" value="<?php echo RESET;?>" />
            </label></td>
        </tr>
    </tbody>
  </table>
    </form>

    </div>
 </div>
</div>

