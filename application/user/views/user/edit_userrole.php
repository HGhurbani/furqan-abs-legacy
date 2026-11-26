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
		window.location.replace('<?php echo base_url().'index.php/user_role/showlist/'.$this->uri->segment(3);?>');
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
			 txtUserpro: {
					selectNone : true/*,
					<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/user_role/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/user_role/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>*/
					},
		 ddlUsertype: {
					selectNone : true
					},
			 ddlUseraccess: {
					selectNone : true
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo USERROLENAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo USERROLEALREADYTAKEN;?>" 
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
	
	$('#ddlUseraccess').change(function(e) {
        $('.showUserAccess').val($('#ddlUseraccess').val());
    });
});

function checkAll() 
{
	if(document.getElementById('chkAll').checked==false)
	{
		uncheckAll();
	}//end if
	else
	{
		for (var i = 0; i < document.frmUserRole.elements.length; i++) 
		{
			var e = document.frmUserRole.elements[i];
			if (e.name == "chkModule[]") 
			{
				e.checked = true;
			}//end if
		}//end for loop
	}//end else
}//end function	

function uncheckAll() 
{
	for (var i = 0; i < document.frmUserRole.elements.length; i++) 
	{
		var e = document.frmUserRole.elements[i];
		if (e.name == "chkModule[]") 
		{
			e.checked = false;
		}//end if
	}//end for loop
}//end function	
</script>
<?php
echo $this->common_model->successMsg($this->uri->segment(5));

echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmUserRole', 'id' => 'frmValidate');

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
<?php echo form_open('user_role/editUserrole/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo USERROLENAME;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
            <input name="txtUserpro"  class="textfield" type="text" value="<?php echo stripslashes($this->txtUserpro);?>" size="40" id="txtUserpro">
            </label><?php if($this->uri->segment(4) == 'a'):?>&nbsp;&nbsp;or&nbsp;&nbsp;
            <?php echo form_dropdown('txtUserpro', $list_userprofile, $this->txtUserpro, 'id="txtUserpro"');?>
            <?php endif;?>
            </td>
        </tr>
        
        <tr>
            <td><?php echo USERROLEACCESS;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlUseraccess', $list_useraccess, $this->ddlUseraccess, 'id="ddlUseraccess"');?>
            </label></td>
        </tr>
        <tr>
            <td><?php echo USERROLEMODULE;?> <span class="warning">*</span></td>
            <td>
                <input type="checkbox" name="chkAll"  id="chkAll" onclick="checkAll();"/>
                <strong><?php echo SELECTALL;?> </strong><br />
                <?php echo $this->user_model->get_list_of_user_modules($this->txtProId);?>
           </td>
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOUSERACCESS;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
