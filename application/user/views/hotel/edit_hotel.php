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
		window.location.replace('<?php echo base_url().'index.php/hotel/showlist/'.$this->uri->segment(3);?>');
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
						remote   : {url:'<?php echo base_url().'index.php/hotel/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/hotel/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			ddlCountry: {
					selectNone : true
					},
			ddlService: {
					selectNone : true
					},		
			ddlCity: {
					selectNone : true
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo HOTELNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo HOTELNAMEALREADYTAKEN;?>" 
			},

		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
	
	
	$('#ddlCountry').change(function()
	{
		var nVal  = $('#ddlCountry').val();

		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCountry:nVal},
			 url:'<?php echo base_url().'index.php/hotel/showcity/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#showCity').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#showCity').html(response);
				}
		  });
	});
	
	<?php if($this->uri->segment(4) == 'e')
		{?>
		
		var nVal  = <?php echo $this->ddlCountry;?>;

		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCountry:nVal,ddlCity:<?php echo $this->ddlCity;?>},
			 url:'<?php echo base_url().'index.php/hotel/showcity/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#showCity').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#showCity').html(response);
				}
		  });
	<?PHP }//end if?>
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
<?php echo form_open('hotel/editHotel/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo COUNTRYNAME;?> <span class="warning">*</span></td>
            <td width="34%" align="left"><label>
             <?php echo form_dropdown('ddlCountry', $list_country, $this->ddlCountry," id='ddlCountry'");?>
            </label></td>
            <td><?php echo CITYNAME;?> <span class="warning">*</span></td>
            <td>
             <span id="showCity">
            <?php echo form_dropdown('ddlCity', $cityList, $this->ddlCity,'id="ddlCity"');?>
            </span>
            </td>
        </tr>
        
        <tr>
        <td><?php echo SERVICETYPE;?> <span class="warning">*</span></td>
          <td><label>
             <?php echo form_dropdown('ddlService', $list_service, $this->ddlService," id='ddlService'");?>
            </label></td>
          <td><?php echo HOTELNAME;?> <span class="warning">*</span></td>
          <td><label>
             <input name="txtTitle"  class="textfield" type="text" value="<?php echo $this->txtTitle;?>" size="40">
            </label></td>
        </tr>
      
        <tr>
            <td><?php echo PHONE;?></td>
              <td><label>
                 <input name="txtPhone"  class="textfield" type="text" value="<?php echo $this->txtPhone;?>" size="40" id="txtPhone">
                </label></td>
              <td><?php echo FAX;?></td>
              <td><label>
                 <input name="txtFax"  class="textfield" type="text" value="<?php echo $this->txtFax;?>" size="40" id="txtFax">
                </label></td>
        </tr>  
 
 		<tr>
           <td><?php echo ADDRESS;?></td>
           <td colspan="4"><label>
             <textarea name="txtAddress" id="txtAddress"><?php echo $this->txtAddress;?></textarea>
            </label></td>
        </tr>
                   
        <tr>
            <td><?php echo ACTIVE;?> </td>
            <td colspan="3"><label>
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOHOTEL;?>" id="btnCancel">
            </label></td>
        </tr>

    </tbody>
  </table>
  </form>
  </div>
