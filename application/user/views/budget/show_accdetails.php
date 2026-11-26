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
		window.location.replace('<?php echo base_url().'index.php/monthly_budget/showlist/'.$this->uri->segment(3);?>');
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

	$("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			  ddlDivision: {
				  	selectNone : true
			  		},
			 'ddlLocation[]': {
					selectNone : true
					},
			 'ddlTrans': {
					selectNone : true
					}
		},
		messages: {
			ddlDivision: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
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

    $('.txtAmount').keyup(function () {
        var txtTotal = 0;
        $('.txtAmount').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalAmount').val(txtTotal);
    });
	
	/*------------------Custom Add new row for account------------------*/
	var i = 1;
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr.re:last').clone(true).find("input").each(function() {
			  			var id = this.id;
						$(this).attr({
						  'id': function(_, id2) { return id + i; },
						  'value': ''               
						});
  						}).end().insertAfter('#dataTable tbody>tr.re:last');
 					 i++;

		  var rowCount = $("#dataTable").attr('rows').length;
		   m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
		   return false;
	});
	
	/*------------------Custom delete row ------------------*/	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>3)
		{
			$(this).parent().parent().remove();
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/monthly_budget/deleteRecord/'.$this->uri->segment(3);?>',
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
			$('#del_'+rowCount).attr('disabled', true);
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
		var nVal = ($('#'+this.id).val() != '')?$('#'+this.id).val():'0';
		
		$('#'+this.id).autocomplete('<?php echo base_url();?>index.php/monthly_budget/suggestionlist', {
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
			 url:'<?php echo base_url().'index.php/monthly_budget/getMonthlyDetails/'.$this->uri->segment(3);?>',
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


<script language="javascript1.1" type="text/javascript">
$(document).ready(function(e) {
	
    $('#ddlDivision').change(function()
	{
		var nVal  = $('#ddlDivision').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlDivision:nVal},
			 url:'<?php echo base_url().'index.php/monthly_budget/filterDivision/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#loc').html(response);
				}
		  });
	});
	
    $('#ddlMonth').change(function()
	{
		var nVal  = $('#ddlMonth').val();
		var nVal1 = $('#txtBudYear').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlMonth:nVal,txtBudYear:nVal1},
			 url:'<?php echo base_url().'index.php/monthly_budget/generateDocumentNumber/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#docNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#docNumber').html(response);
				}
		  });
	});
	
    $('#txtBudCopyref').keyup(function()
	{
		var nVal  = $('#txtBudCopyref').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtBudCopyref:nVal},
			 url:'<?php echo base_url().'index.php/monthly_budget/checkCopyrefAccounts/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#copyref').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#copyref').html(response);
				}
		  });
	});

});
</script>
<td colspan="6" id="copyref">
 <table id="dataTable" title="dataTable">
    <tr>
        <td colspan="7"><input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" /></td>
    </tr>
    
    <?php
    if(!empty($chkAccCode))
    {
        foreach($chkAccCode as $key => $aitem):
    ?>
    <tr class="re">
        <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td> 
        <td><?php echo form_dropdown('ddlAccount[]', $list_accounts, $chkAccCode[$key],'id="ddlAccount"');?></td>
        
        <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
        <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="singleDrop"');?></td>
        
        <td><?php echo BUDGETAMOUNT;?> <span class="warning">*</span></td>
        <td> <input name="txtAmount[]" id="txtAmount<?php echo $key;?>" class="textfield4" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
        
        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $buddetID[$key];?>" title="Delete"/></td>
    </tr>
    <?php 
        endforeach;
    }//end if
    else
    {?>
        <tr class="re">
            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td> 
            <td><?php echo form_dropdown('ddlAccount[]', $list_accounts, $this->ddlAccount,'id="ddlAccount"');?></td>
            
            <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
            <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="singleDrop"');?></td>
            
            <td><?php echo BUDGETAMOUNT;?> <span class="warning">*</span></td>
            <td> <input name="txtAmount[]" id="txtAmount" class="textfield4" type="text" value=""></td>
        
            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId; ?>" title="Delete"/></td>
        </tr>
    <?php
        }//end else
    ?>

   <tr>
        <td colspan="4"> </td>
       <td><?php echo TOTALAMOUNT;?></td>
       <td colspan="2"><input name="txtTotalAmount" id="txtTotalAmount" type="text" value="<?php echo $this->txtTotalAmount;?>" class="textfield5"></td>
   </tr> 

 </table>
</td>