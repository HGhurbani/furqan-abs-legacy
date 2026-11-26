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
function checkAll() 
{
	if(document.getElementById('chkAll').checked==false)
	{
		uncheckAll();
	}//end if
	else
	{
		for (var i = 0; i < document.frmGroup.elements.length; i++) 
		{
			var e = document.frmGroup.elements[i];
			if (e.name == "ddlAcc[]") 
			{
				e.checked = true;
			}//end if
		}//end for loop
	}//end else
}//end function	
function uncheckAll() 
{
	for (var i = 0; i < document.frmGroup.elements.length; i++) 
	{
		var e = document.frmGroup.elements[i];
		if (e.name == "ddlAcc[]") 
		{
			e.checked = false;
		}//end if
	}//end for loop
}//end function	

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/travel_setup/showlist/'.$this->uri->segment(3);?>');
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
			 txtAcCode: {
						required : true
					},
			 'ddlLocation[]': {
					selectNone : true
					},
			 'ddlTrans': {
					selectNone : true
					}
		},
		messages: {
			txtAcCode: {
				required : "<?php echo TRAVELACCODE;?><?php echo CANTBEBLANK;?>"
			},
			'ddlLocation[]': {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			'ddlTrans': {
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
						$(this).attr({
						  'id': function(_, id2) { return id + i; },
						  'value': '' ,   
						  'class': 'chkSuggestion'            
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
				 url:'<?php echo base_url().'index.php/travel_setup/deleteRecord/'.$this->uri->segment(3);?>',
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
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox.css" />
<script type="text/javascript">
$(function() {
	function format(acVal) {
		return acVal.name;
	}
	
	$(".chkSuggestion").keypress(function(e) 
	{
		var nId  = this.id;
		var nVal = $('#'+this.id).val();
		$('#'+this.id).autocomplete('<?php echo base_url();?>index.php/travel_setup/suggestionlist', {
			multiple: false,
			dataType: "json",
			parse: function(data) {
				return $.map(data, function(row) {
					return {
						data: row,
						value: row.name,
						result: row.name
					}
				});
			},
			formatItem: function(item) {
				return format(item);
			}
		}).result(function(e, item) 
		{
			var nId  = this.id;
			$.ajax(
		  	{
			 type:'POST',
			 url:'<?php echo base_url().'index.php/travel_setup/getTravelDetails/'.$this->uri->segment(3);?>',
			 data:{txtAcccode:format(item)},
			 dataType:'html',
			 beforeSend: function() {
					$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtAccname'+nId).val(response);
				}
		  });
		});
	});
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
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('travel_setup/editTravelAccount/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkAccID);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td width="86%" align="left" colspan="4"><label>
             <?php echo form_multiselect('ddlLocation[]', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" class="multiDrop" class="multiDrop"');?>
            </label></td>
        </tr>
		
        <tr>
            <td><?php echo TRANSNAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlTrans', $list_trans_value, $this->ddlTrans, 'id="ddlTrans"');?>
            </label></td>
        </tr>
        
        <tr>
        	<td colspan="3">
            <table id="dataTable" title="dataTable">
            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
			<?php
            if(!empty($chkAccCode))
            {
                foreach($chkAccCode as $key => $aitem):
            ?>
            <tr>
                <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td> 
                <td><label>
                 <input name="txtAcccode[]" id="txtAcccode<?php echo $key;?>" class="chkSuggestion" type="text" value="<?php echo stripslashes($aitem).' - '.stripslashes($chkAccTitle[$key]);?>" size="4">
                </label></td>
                
                <td><?php echo TRAVELFLAG;?> <span class="warning">*</span></td>
                <td><?php echo form_dropdown('ddlFlag[]', $list_flag, $chkFlag[$key],'id="ddlFlag"');?></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $traveldetID[$key];?>" title="Delete"/></td>
            </tr>
			<?php 
                endforeach;
            }//end if
            else
            {?>
                <tr>
                    <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td> 
                    <td><label>
                     <input name="txtAcccode[]" id="txtAcccode" class="chkSuggestion" type="text" value="" size="4">
                    </label></td>
                    
                    <td><?php echo TRAVELFLAG;?> <span class="warning">*</span></td>
                    <td><?php echo form_dropdown('ddlFlag[]', $list_flag, $this->ddlFlag,'id="ddlFlag"');?></td>
                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId; ?>" title="Delete"/></td>
                </tr>
            <?php
                }//end else
            ?>
         </table>
       </td>
       </tr>
       
       <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="4"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
      
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="4"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOTRAVELSETUP;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
