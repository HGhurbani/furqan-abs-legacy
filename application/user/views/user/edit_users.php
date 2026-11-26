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
	$(".radType").click(function()
	{
		var nVal = $('.radType:checked').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{radType:nVal},
			 url:'<?php echo base_url().'index.php/users/generateUsers/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#users').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#users').html(response);
				}
		  });
	});
	
	<?php  if($this->uri->segment(4) == 'e')
			{
	?>
				var nVal    = '<?php echo $this->uTypeVal;?>';
				var ddlUser = '<?php echo $this->ddlUsername;?>';
	<?php   }//end if
			else
			{
	?>
				var nVal    = $('.radType:checked').val();
				var ddlUser = '0';
	<?php	}//end else?>	
	
		$.ajax(
		  {
			 type:'POST',
			 data:{radType:nVal,ddlUser:ddlUser},
			 url:'<?php echo base_url().'index.php/users/generateUsers/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#users').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#users').html(response);
				}
		  });
		<?php if($this->uri->segment(4) == 'e')
		{?>
			$.ajax(
			  {
				 type:'POST',
				 data:{txtUsercode:'<?php echo $this->txtUsercode;?>',txtEmail:'<?php echo $this->txtEmail;?>'},
				 url:'<?php echo base_url().'index.php/users/getUserdetails/'.$this->uri->segment(3);?>',
				 dataType:'html',
				 beforeSend: function() {
						$('#details').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
						$('#details').html(response);
					}
			  });

	<?php }//end if?>

	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/users/showlist/'.$this->uri->segment(3);?>');
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
		   ddlUsername: {
						selectNone : true
					},
			<?php if($this->uri->segment(4) == 'a')
			{?>
			txtUPassword: {
						required : true
					},
			txtCPassword: {
						required : true,
						equalTo  : '#txtUPassword'
					},
			<?php }//end if?>
			ddlUserpro: {
						selectNone : true
					},
			'ddlLocation[]': {
						selectNone : true
					}

		},
		messages: {
		   ddlUsername: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			<?php if($this->uri->segment(4) == 'a')
			{?>
			txtUPassword: {
						required : "<?php echo USERSPASSWORD;?><?php echo CANTBEBLANK;?>"
					},
			txtCPassword: {
						required : "<?php echo CONFIRMUSERSPASSWORD;?><?php echo CANTBEBLANK;?>",
						equalTo  : "<?php echo PASSWORDDOESNOTMATCH;?>"
					},
			<?php }//end if?>
			ddlUserpro: {
						selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
		   'ddlLocation[]' : {
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
<?php echo form_open('users/editUsers/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo CHOOSETYPE;?> <span class="warning">*</span></td>
            <td width="86%" align="left">
             <input type="radio" name="radType" value="C"<?php echo $this->chkValTypeC;?> class="radType rbtns"/> <?php echo CUSTNAME;?>&nbsp;&nbsp;
             <input type="radio" name="radType" value="E"<?php echo $this->chkValTypeE;?> class="radType rbtns"/> <?php echo EMPNAME;?>&nbsp;&nbsp;
             <input type="radio" name="radType" value="V"<?php echo $this->chkValTypeV;?> class="radType rbtns"/> <?php echo VENDORNAME;?>&nbsp;&nbsp;
             <input type="radio" name="radType" value="A"<?php echo $this->chkValTypeA;?> class="radType rbtns"/> <?php echo AGENTNAME;?>&nbsp;&nbsp;
             <input type="radio" name="radType" value="O"<?php echo $this->chkValTypeO;?> class="radType rbtns"/> <?php echo OTHER;?>
            </td>
        </tr>
<!--        <tr>
            <td><?php echo USERNAME;?> <span class="warning">*</span></td>
            <td><label id="users">
             <?php echo form_dropdown('ddlUsername', $list_user_name, $this->ddlUsername, 'id="ddlUsername"');?>
            </label></td>
        </tr>
-->        </tbody>
        </table>
        <span id="users">
		</span>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    	<tbody>

        <?php if($this->uri->segment(4) == 'a')
		{?>
        <tr>
            <td width="14%"><?php echo USERSPASSWORD;?> <span class="warning">*</span></td>
            <td width="86%"><label>
             <input name="txtUPassword" id="txtUPassword" class="textfield" type="password" value="<?php echo stripslashes($this->txtUPassword);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo CONFIRMUSERSPASSWORD;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtCPassword" id="txtCPassword" class="textfield" type="password" value="<?php echo stripslashes($this->txtCPassword);?>" size="40">
            </label></td>
        </tr>
        <?php }//end if
		else {?>
        <tr>
            <td width="14%"><?php echo USERSPASSWORD;?> </td>
            <td width="86%"><label>
             <input name="txtUPassword" id="txtUPassword" class="textfield" type="password" value="" size="40">
            </label></td>
        </tr>
		<tr>
        	<td>&nbsp;</td>
            <td><span class="warning">(If you type the new password it will update the old password)</span>
            </td>
        </tr>
        <?php }//end else?>
        
        <tr>
            <td><?php echo USERROLENAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlUserpro', $list_user_pro, $this->ddlUserpro,'id="ddlUserpro"');?>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_multiselect('ddlLocation[]', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" class="multiDrop"');?>
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
                  <input type="button" name="btnCancel" value="<?php echo BACKTOUSERS;?>" id="btnCancel">
                </label></td>
         </tr>
    </tbody>
  </table>
  </form>
  </div>
