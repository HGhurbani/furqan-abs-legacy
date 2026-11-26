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
		window.location.replace('<?php echo base_url().'index.php/division/showlist/'.$this->uri->segment(3);?>');
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
        "Please select an option."
	);
    $("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
    $("#txtDateTo").datepick({ dateFormat: 'yyyy-mm-dd'});
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/division/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/division/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtDivCode: {
						 required : true,
						 <?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/division/checkDivcode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/division/checkDivcode'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			ddlCurrency: {
						 selectNone : true	
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo DIVISIONNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo DIVISIONALREADYTAKEN;?>" 
			},
			txtDivCode: {
				required : "<?php echo DIVISIONCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo DIVISIONCODEALREADYTAKEN;?>" 
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
<?php echo form_open_multipart('division/editDivision/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo COMPANYCURRENCY;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
              <?php echo form_dropdown('ddlCurrency', $list_currency, $this->ddlCurrency);?>
            </label></td>

            <td><?php echo DIVISIONCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtDivCode" id="txtDivCode" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDivCode);?>" size="2">
            </label></td>

            <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle" id="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>

        <tr>
            <td><?php echo ADDRESS;?> <span class="warning">*</span></td>
            <td><label>
                <textarea name="txtAddress" id="txtAddress" rows="5" cols="20"><?php echo stripslashes($this->txtAddress);?></textarea>
            </label></td>

            <td><?php echo PHONE;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <input name="txtPhone" id="txtPhone"  class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtPhone);?>" size="40">
            </label></td>
        </tr>

        <tr>
            <td><?php echo MOBILE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtMobile" id="txtMobile"  class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtMobile);?>" size="40">
            </label></td>

            <td><?php echo EMAIL;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
             <input name="txtEmail" id="txtEmail"  class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtEmail);?>" size="40">
            </label></td>
        </tr>

        <tr>
            <td><?php echo WEBADDR;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtWeb" id="txtWeb"  class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtWeb);?>" size="40">
            </label></td>

<?php /*?>            <td><?php echo YEARDATE;?> <span class="warning">*</span></td>
            <td><label>
                <input name="txtDate" id="txtDate" class="textfield" type="text" value="<?php echo date('Y-m-d');?>" size="20">
            </label></td>

            <td><?php echo YEARTO;?> <span class="warning">*</span></td>
            <td><label>
                <input name="txtDateTo" id="txtDateTo" class="textfield" type="text" value="<?php echo date('Y-m-d');?>" size="20">
            </label></td>
<?php */?>        </tr>

        <tr>
            <td valign="top"><?php echo IMAGE;?> <span class="warning">*</span></td>
            <td valign="top" colspan="5"><input type="file" name="txtPic" value="<?php echo set_value('txtPic'); ?>"  id="txtPic"/>
                <?php
                $image_properties = array(
                    'src' => 'upload/division/'.$this->txtPic,
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
              <input type="button" name="btnCancel" value="<?php echo BACKTODIVISION;?>" id="btnCancel">
            </label></td>
        </tr>

    </tbody>
  </table>
    </form>

    </div>
 </div>
</div>