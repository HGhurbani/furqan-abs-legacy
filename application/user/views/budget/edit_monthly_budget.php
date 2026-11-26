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

function changeAllAccount(){
     var loc  = $('#ddlLocation').val();
     var div = $('#ddlDivision').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/monthly_budget/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             $('.ddlAccount').each( function(){
                 var id = this.id;
                 var getId = id.split('ddlAccount');
                 var acc = $('#ddlAccount'+getId[1]).val();
                 $('#ddlAccount'+getId[1]).html(response);
                 $('#ddlAccount'+getId[1]).val(acc);
             });
         }
     });
}

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

    <?php if($this->uri->segment(4) == 'a')
    {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:$('#ddlDivision').val(),ddlTktname:0},
        url:'<?php echo base_url().'index.php/monthly_budget/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            changeAllAccount();

        }
    });

    <?php }else{//end if ?>
    changeAllAccount();

    <?php }//end if ?>

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
                    changeAllAccount();

                }
            });
    });
    $('#loc').change(function(){
          changeAllAccount();
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
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("ul.pgcontent").quickPagination({pageSize:"5"});
    });     
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

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
<?php echo form_open('monthly_budget/editMonthlybudget/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $buddetID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
    <tr>
        <td colspan="6">
            <table>
                <tr>
                    <td align="left"><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
                    <td align="left"><label>
                     <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
                    </label></td>
            
                    <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
                    <td><label><span id="loc">
                     <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                    </span></label></td>

                    <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
                    <td><label>
                    <input name="txtDate" id="txtDate" type="text" value="<?php echo $this->txtDate;?>" class="textfield4">
                    </label></td>
                </tr>
                
                <tr>
                    <td><?php echo BUDGETMONTH;?> <span class="warning">*</span></td>
                    <td><label>
                      <?php echo form_dropdown('ddlMonth', $list_month_value, $this->ddlMonth, 'id="ddlMonth"');?>
                    </label></td>
                    
                    <td><?php echo BUDGETYEAR;?> <span class="warning">*</span></td>
                    <td><label>
                     <input name="txtBudYear" id="txtBudYear" type="text" value="<?php echo $this->txtBudYear;?>" class="textfield4">
                    </label></td>

                    <td><?php echo BUDGETDOCNUMBER;?> <span class="warning">*</span></td>
                    <td><label id="docNumber">
                     <input name="txtDocNumber" id="txtDocNumber" type="text" value="<?php echo $this->txtDocNumber;?>" class="textfield4">
                    </label></td>
                </tr>
                
                <tr>
<!--                    <td><?php echo YEARREF;?></td>
                    <td><label>
                     <input name="txtYearref" id="txtYearref" type="text" value="<?php echo $this->txtYearref;?>" class="textfield4">
                    </label></td>
-->                
                    <td><?php echo BUDGETCOPYREF;?></td>
                    <td colspan="3"><label>
                     <input name="txtBudCopyref" id="txtBudCopyref" type="text" value="<?php echo $this->txtBudCopyref;?>" class="textfield4">
                    </label></td>
                </tr>
            </table>
        </td>
    </tr>
    
    
    <tr>
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
            <td><?php echo form_dropdown('ddlAccount[]', $list_accounts, $chkAccCode[$key],'id="ddlAccount" class="ddlAccount"');?></td>
            
            <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
            <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="singleDrop"');?></td>
            
            <td><?php echo BUDGETAMOUNT;?> <span class="warning">*</span></td>
            <td> <input name="txtAmount[]" id="txtAmount<?php echo $key;?>" class="txtAmount textfield4" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
            
            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $buddetID[$key];?>" title="Delete"/></td>
        </tr>
        <?php 
            endforeach;
        }//end if
        else
        {?>
            <tr class="re">
                <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td> 
                <td><?php echo form_dropdown('ddlAccount[]', $list_accounts, $this->ddlAccount,'id="ddlAccount" class="ddlAccount"');?></td>
                
                <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
                <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="singleDrop"');?></td>
                
                <td><?php echo BUDGETAMOUNT;?> <span class="warning">*</span></td>
                <td> <input name="txtAmount[]" id="txtAmount" class="txtAmount textfield4" type="text" value=""></td>
            
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
          <input type="button" name="btnCancel" value="<?php echo BACKTOMONTHLYBUDGET;?>" id="btnCancel">
        </label></td>
    </tr>
</tbody>
</table>
</form>
</div>
