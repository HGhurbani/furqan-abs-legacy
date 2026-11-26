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
		window.location.replace('<?php echo base_url().'index.php/location/showlist/'.$this->uri->segment(3);?>');
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

	$("#txtYearDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	$("#txtYearTo").datepick({ dateFormat: 'yyyy-mm-dd'});
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			ddlDivision: {
						selectNone : true
						},
			txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/location/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/location/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtCode: {
						required : true
					}
		},
		messages: {
			ddlDivision: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
						},
			txtTitle: {
				required : "<?php echo LOCATIONNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo LOCATIONALREADYTAKEN;?>" 
			},
			txtCode: {
				required : "<?php echo CODE;?><?php echo CANTBEBLANK;?>"
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
<?php echo form_open_multipart('location/editLocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
	   <tr>
            <td width="14%" align="left"><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
            <td width="32%" align="left"><label>
             <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
            </label></td>

            <td><?php echo CODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtCode" id="txtCode" class="textfield3" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="40">
            </label></td>

            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle" id="txtTitle" class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LOCATIONADDR;?></td>
            <td><label>
            <textarea name="txtLocaddr" id="txtLocaddr" cols="30" rows="5"><?php echo stripslashes($this->txtLocaddr);?></textarea>
            </label></td>
            
            <td><?php echo LOCATIONPHONE;?></td>
            <td colspan="3"><label>
             <input name="txtLocphone" id="txtLocphone" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtLocphone);?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo MOBILE;?></td>
            <td><label>
            <input name="txtLocmob" id="txtLocmob" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtLocmob);?>">
            </label></td>
            
            <td><?php echo EMAIL;?></td>
            <td colspan="3"><label>
             <input name="txtEmail" id="txtEmail" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtEmail);?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo WEB;?></td>
            <td><label>
            <input name="txtWeb" id="txtWeb" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtWeb);?>">
            </label></td>
 
            <td width="14%" align="left"><?php echo COMPANYCURRENCY;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
              <?php echo form_dropdown('ddlCurrency', $list_currency, $this->ddlCurrency);?>
            </label></td>
        </tr>
        
<?php /*?>        <tr>
            <td><?php echo YEARDATE;?></td>
            <td><label>
             <input name="txtYearDate" id="txtYearDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtYearDate);?>">
            </label></td>

            <td><?php echo YEARTO;?></td>
            <td><label>
             <input name="txtYearTo" id="txtYearTo" class="textfield" type="text" value="<?php echo stripslashes($this->txtYearTo);?>">
            </label></td>
        </tr>
<?php */?>
        <tr>
            <td valign="top"><?php echo IMAGE;?> <span class="warning">*</span></td>
            <td valign="top" colspan="5"><input type="file" name="txtPic" value="<?php echo set_value('txtPic'); ?>"  id="txtPic"/>
             <?php 
            $image_properties = array(
            'src' => 'upload/location/'.$this->txtPic,
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOLOCATION;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
