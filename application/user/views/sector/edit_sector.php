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
		window.location.replace('<?php echo base_url().'index.php/sector/showlist/'.$this->uri->segment(3);?>');
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
			 txtCode: {
					required : true
					},
			txtName: {
					required : true
					},
			txtSector: {
					required : true
					},
			 'ddlCounty[]': {
					selectNone : true
					}
		},
		messages: {
			txtCode: {
					required : "<?php echo SECTORCODE.'&nbsp;'.CANTBEBLANK;?>"
					},
			txtName: {
					required : "<?php echo SECTORNAME.'&nbsp;'.CANTBEBLANK;?>"
					},
			'txtSector[]': {
					required : "<?php echo SECTOR.'&nbsp;'.CANTBEBLANK;?>"
					},				
			'ddlCounty[]': {
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
	
	/*------------------Custom Add new row for account------------------*/
	var i = 1;
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
			  			var id = this.id;
						var t = parseInt(id) + parseInt(1);
						var tname = this.name;
						
						var showVal;
						if(this.type == 'checkbox')
						{
							showVal = 1;
							tname   = 'chkDomestic'+t;
						}//end if
						else 
						{
							showVal = '';
							tname   = tname;
						}//end else
						$(this).attr({
						  'id': function(_, id2) { if(this.type == 'checkbox')
						  									return t;
													else
															return id + i; },
						  'name':tname,
						  'value': showVal               
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
		if((rowCount)>2)
		{
			$(this).parent().parent().remove();
			
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/sector/deleteRecord/'.$this->uri->segment(3);?>',
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
});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("ul.pgcontent").quickPagination({pageSize:"5"});
    });     
</script>
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('sector/editSector/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkSecID);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
    <tr>
    	<td colspan="4">
    		<table width="60%">
    			<tr>
			        <td><?php echo SECTORCODE;?>&nbsp;<span class="warning">*</span></td>
			        <td><label>
			         <input name="txtCode" id="txtCode" type="text" value="<?php echo $this->txtCode;?>" class="pricebox">
			        </label></td>
			        <td><?php echo SECTORNAME;?>&nbsp;<span class="warning">*</span></td>            
			        <td><label>
			         <input name="txtName" id="txtName" type="text" value="<?php echo $this->txtName;?>">
			        </label></td>
    			</tr>
    		</table>
    	</td>
    </tr>
    <tr>
        <td colspan="4">
	        <div style=" overflow:auto; max-height:250px;">
	        <table id="dataTable" title="dataTable">
	        	<tr>
	        		<td colspan="9">
				        <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
				        <?php
				        if(!empty($chkCountry))
				        {
				            foreach($chkCountry as $key => $aitem):
							switch($chkDomestic[$key])
							{
								case "0":
									$checkd = '';
								break;
								
								case "1":
									$checkd = 'checked="checked"';
								break;
							}//end switch
				        ?>
	        		</td>
	        	</tr>

	        <tr>
	            <td><?php echo SECCOUNTRY;?> <span class="warning">*</span></td>
	            <td><?php echo form_dropdown('ddlCountry[]', $list_country, $aitem,'id="ddlCountry" class="ddlCountry"');?></td>
	             <td><?php echo SECTOR;?> <span class="warning">*</span></td>
	            <td><input name="txtSector[]" id="txtSector<?php echo $key;?>" class="textfield3" type="text" value="<?php echo stripslashes($chkSector[$key]);?>" size="3" maxlength="3"></td>
	            
	            <td><?php echo SECTORDES;?></td>
	            <td><input name="txtDes[]" id="<?php echo $key;?>" type="text" value="<?php echo stripslashes($chkDes[$key]);?>"></td>
	            
	            <td><?php echo DOMESTIC;?></td>
	            <td><input name="chkDomestic<?php echo $key+1;?>" id="<?php echo $key+1;?>" class="textfield5" type="checkbox" value="1" <?php echo $checkd;?>/></td>
	            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $bankdetID[$key];?>" title="Delete"/></td>
	        </tr>
	        <?php 
	            endforeach;
	        }//end if
	        else
	        {?>
            <tr>
                <td><?php echo SECCOUNTRY;?> <span class="warning">*</span></td>
                <td><?php echo form_dropdown('ddlCountry[]', $list_country, $this->ddlCountry,'id="ddlCountry" class="ddlCountry"');?></td>
                <td><?php echo SECTOR;?> <span class="warning">*</span></td>
                <td><input name="txtSector[]" id="txtSector" class="textfield3" type="text" value="" size="3" maxlength="3"></td>
            
                <td><?php echo SECTORDES;?></td>
                <td><input name="txtDes[]" id="txtDes" type="text" value=""></td>
            
                <td><?php echo DOMESTIC;?></td>
                <td><input name="chkDomestic1" id="1" class="textfield5" type="checkbox" value="1" /></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
            </tr>
        <?php
            }//end else
        ?>
     </table>
     </div>
   </td>
   </tr>
   
   <tr>
        <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
        <td colspan="3"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?></label><label>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
    </tr>
  
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="3"><label>
          <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
          <?php if($this->uri->segment(4) == 'a'){?>
          <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
          <?php }//end if?>
          <input type="reset" value="<?php echo RESET;?>" />
          <input type="button" name="btnCancel" value="<?php echo BACKTOSECTOR;?>" id="btnCancel">
        </label></td>
    </tr>
</tbody>
</table>
  </form>
  </div>
