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
	$('tr.Exist').hide();
	
	$(".radType").click(function()
	{
		var nVal = $('.radType:checked').val();
		if(nVal == 'New')
		{
			$('.New').show();
			$('tr.Exist').hide();
		}
		if(nVal == 'Exist')
		{
			$('.New').hide();
			$('tr.Exist').show();
		}
	});

	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/main_group/showlist/'.$this->uri->segment(3);?>');
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
<?php /*?>			txtTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtGCode: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkCode'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			 ddlMGroup: {
				 		  selectNone : true
			 		},
<?php */?>			 txtAccGroupTitle: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkAccTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkAccTitle'?>',type: "post"}
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
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkSubCode/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/main_group/checkSubCode'?>',type: "post"}
						<?php
						}//end else
						?>
					}
		},
		
		messages: {
			txtTitle: {
				required : "<?php echo MAINGROUPNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo MAINGROUPALREADYTAKEN;?>" 
			},
			txtGCode: {
				required : "<?php echo MAINGROUPCODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo MAINGROUPCODEALREADYTAKEN;?>" 
			},
			ddlMGroup: {
				 		  selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
			 		},
			txtAccGroupTitle: {
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
	
	var i = 1;
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
			  			var id = this.id;
						$(this).attr({
						  'id': function(_, id2) { return id + i; },
						  'value': ''               
						});
  						}).end().find("select").each(function() {
			  			var id = this.id;
						$(this).attr({
						  'id': function(_, id2) { return id + i; },
						  'value': ''               
						});
  						}).end().insertAfter('#dataTable tbody>tr:last');
 					 i++;

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
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/main_group/deleteRecord/'.$this->uri->segment(3);?>',
				 data:{subCode:dId[1]},
				 dataType:'html',
				 beforeSend: function() {
						//$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
					}
			});
			
		}//end if
		else
		{
			//var src = $(this).attr("src").replace("cross", "cross_grey_small");
			
			$('#del_'+rowCount).attr('disabled', true);
           // $(this).attr("src", src);

		}//end if
			
		});

    $('#ddlDivision').change(function () {
        var nVal = $('#ddlDivision').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlDivision:nVal},
                url:'<?php echo base_url().'index.php/main_group/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#loc').html(response);
                }
            });
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
<?php echo form_open('main_group/editMaingroups/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nSId', $this->subCodeId);?>
<?php echo form_hidden('idArray', $chkAccID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
    	<?php if($this->uri->segment(4) == 'a')
        {?>       
        <tr>
            <td width="14%"><?php echo CHOOSETYPE;?> <span class="warning">*</span></td>
            <td width="86%"><label>
             <input type="radio" name="radType" value="New" <?php echo $this->chkValTypeN;?> class="radType rbtns"/> New&nbsp;&nbsp;
             <input type="radio" name="radType" value="Exist"<?php echo $this->chkValTypeE;?> class="radType rbtns"/> Existing
            </label></td>
        </tr>
        
        <tr class="New">
            <td><?php echo MAINGROUPCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtGCode"  class="textfield" type="text" value="<?php echo stripslashes($this->txtGCode);?>" size="4">
            </label></td>
        </tr>

        <tr class="New">
            <td><?php echo MAINGROUPNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr class="Exist">
            <td><?php echo MAINGROUPNAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlMGroup', $list_main_group, $this->ddlMGroup);?>
            </label></td>
        </tr>
		<?php }//end if
        else {?>  
         <tr>
            <td><?php echo MAINGROUPCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtGCode"  class="textfield" type="text" value="<?php echo stripslashes($this->txtGCode);?>" size="4">
            </label></td>
        </tr>

        <tr>
            <td><?php echo MAINGROUPNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTitle"  class="textfield" type="text" value="<?php echo stripslashes($this->txtTitle);?>" size="40">
            </label></td>
        </tr>
		<?php }//end else?> 
            
		<tr>
        	<td colspan="2"><strong><?php echo ACCGROUPNAME;?></strong></td>
        </tr>
        
        <tr>
            <td><?php echo ACCGROUPCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtCode" id="txtCode" class="textfield" type="text" value="<?php echo stripslashes($this->txtCode);?>" size="4">
            </label></td>
       </tr>

        <tr>
            <td><?php echo ACCGROUPNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtAccGroupTitle" id="txtAccGroupTitle" class="textfield" type="text" value="<?php echo stripslashes($this->txtAccGroupTitle);?>" size="40">
            </label></td>
        </tr>
        
        <tr>
        <td colspan="2">
 		<table id="dataTable" title="dataTable" width="100%">
        <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
        <?php
		if(!empty($chkAccCode))
		{
			foreach($chkAccCode as $key => $aitem):
		?>

            <tr>
               <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
               </label></td>
               
               <td><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
               </label></td>
                <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlSubActive[]', $subActive, $chkAccActive[$key],'id="ddlSubActive" class="textfield21"');?></label></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkAccID[$key];?>" title="Delete"/></td>

                <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlDivision[]', $list_div_value, $chkAccDivision[$key], 'id="ddlDivision"');?>
                </label></td>

                <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
                <td><label><span id="loc"><?php echo form_dropdown('ddlLocation[]', $chkAccLocation[$key], $this->ddlLocation, 'id="ddlLocation"');?></span></label>
                </td>

            </tr>
		<?php 
            endforeach;
        }//end if
        else
        {?>
            <tr>
               <td ><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
               <td ><label>
               <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="" size="4">
               </label></td>
               
               <td ><?php echo ACCNAMETITLE;?> <span class="warning">*</span></td>
               <td ><label>
               <input name="txtAccTitle[]" id="txtAccTitle" class="textfield3" type="text" value="" size="40">
               </label></td>
                <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
                <td ><label>
                    <?php echo form_dropdown('ddlSubActive[]', $subActive, $this->ddlSubActive,'id="ddlSubActive" class="textfield21"');?></label></td>

                <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlDivision[]', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
                </label></td>

                <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
                <td><label><span id="loc"><?php echo form_dropdown('ddlLocation[]', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?></span></label>
                </td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>

            </tr>
		<?php
            }//end else
        ?>

		</table>
        </td>      
        </tr>
       
        <tr>
            <td width="1%"><?php echo ACTIVE;?> <span class="warning">*</span></td>
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOMAINGROUP;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
