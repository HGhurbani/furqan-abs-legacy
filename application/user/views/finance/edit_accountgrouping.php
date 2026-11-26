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
		window.location.replace('<?php echo base_url().'index.php/account_grouping/showlist/'.$this->uri->segment(3);?>');
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
			 ddlType: {
					selectNone : true
					},
					
			 txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/account_grouping/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/account_grouping/checkTitle'?>',type: "post"}
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
						remote   : {url:'<?php echo base_url().'index.php/account_grouping/checkCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/account_grouping/checkCode'?>',type: "post"}
						<?php
						}//end else
						?>
					}
		},
		messages: {
			txtTitle: {
				required : "<?php echo ACCGROUPNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo ACCGROUPALREADYTAKEN;?>" 
			},
			txtCode: {
				required : "<?php echo ACCGROUPCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo ACCGROUPCODEALREADYTAKEN;?>"
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
/*------------------Custom Add new row for account------------------*/
	
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr:last').clone(true).insertAfter('#dataTable tbody>tr:last');
		  var rowCount = $("#dataTable").attr('rows').length;
		   m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
		   return false;
	});
	
/*------------------Custom delete row ------------------*/	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>1)
		{
			$(this).parent().parent().remove();
		}//end if
		else
		{
			//var src = $(this).attr("src").replace("cross", "cross_grey_small");
			
			$('#del_'+rowCount).attr('disabled', true);
           // $(this).attr("src", src);

		}//end if
			
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
<?php echo form_open('account_grouping/editAccgrouping/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkAccID);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="31%"><?php echo MAINGROUPNAME;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <?php echo form_dropdown('ddlType', $list_main_group, $this->ddlType);?>
            </label></td>
        </tr>
        
        <tr>
            <td width="31%"><?php echo ACCGROUPCODE;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <input name="txtCode" id="txtCode" class="textfield" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="4">
            </label></td>
       </tr>

        <tr>
            <td width="31%"><?php echo ACCGROUPNAME;?> <span class="warning">*</span></td>
            <td width="69%"><label>
             <input name="txtTitle" id="txtTitle" class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
        <td colspan="2">
 		<table id="dataTable" title="dataTable">
        <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
        <?php
		if(!empty($chkAccCode))
		{
			foreach($chkAccCode as $key => $aitem):
		?>

            <tr>
               <td width="31%"><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td width="31%"><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
               </label></td>
               
               <td width="31%"><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td width="69%"><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
               </label></td>
               <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>
            </tr>
		<?php 
            endforeach;
        }//end if
        else
        {?>
            <tr>
               <td width="31%"><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td width="31%"><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="" size="4">
               </label></td>
               
               <td width="31%"><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td width="69%"><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="" size="40">
               </label></td>
               <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>
            </tr>
		<?php
            }//end else
        ?>

		</table>
        </td>      
        </tr>
         
        <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        <?php if($this->uri->segment(4) == 'a')
		{?>
            <tr class="formButs">
                <td>&nbsp;</td>
                <td><label>
                  <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
                  <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
                  <input type="reset" value="<?php echo RESET;?>" />
                  <input type="button" name="btnCancel" value="<?php echo BACKTOACCGROUP;?>" id="btnCancel">
                </label></td>
            </tr>
         <?php }//end if
		 else {?>
            <tr class="formButs">
                <td>&nbsp;</td>
                <td><label>
                  <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
                  <input type="reset" value="<?php echo RESET;?>" />
                  <input type="button" name="btnCancel" value="<?php echo BACKTOACCGROUP;?>" id="btnCancel">
                </label></td>
            </tr>
         <?php }//end else?>
    </tbody>
  </table>
  </form>
  </div>
