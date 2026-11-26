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
		window.location.replace('<?php echo base_url().'index.php/stock_transfer/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	$("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	
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
			 ddlLocation: {
					selectNone : true
					},
			 txtDate: {
						required : true
					},
			 txtDocNo: {
						required : true
					},
			 txtYearRef: {
						required : true
					},
			 txtReceived: {
						required : true
					},
			 txtRemarks: {
						required : true
					}
		},
		messages: {
			ddlLocation: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtDate: {
				required : "<?php echo CUSTDATE;?><?php echo CANTBEBLANK;?>"
			},
			txtDocNo: {
				required : "<?php echo DOCNUMBER;?><?php echo CANTBEBLANK;?>"
			},
			txtYearRef: {
				required : "<?php echo YEARREF;?><?php echo CANTBEBLANK;?>"
			},
			txtReceived: {
				required : "<?php echo RECEIVEDBY;?><?php echo CANTBEBLANK;?>"
			},
			txtRemarks: {
				required : "<?php echo REMARKS;?><?php echo CANTBEBLANK;?>"
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
<?php echo form_open('stock_transfer/editStocktransfer/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $stockdetID);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
            </label></td>
            
            <td align="left"><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td align="left"><label>
            <input name="txtDate" id="txtDate" type="text" value="<?php echo stripslashes($this->txtDate);?>">
            </label></td>
        </tr>
		
        <tr>
            <td align="left"><?php echo DOCNUMBER;?> <span class="warning">*</span></td>
            <td align="left"><label>
            <input name="txtDocNo" id="txtDocNo" type="text" value="<?php echo stripslashes($this->txtDocNo);?>">
            </label></td>
            
            <td align="left"><?php echo YEARREF;?> <span class="warning">*</span></td>
            <td align="left"><label>
            <input name="txtYearRef" id="txtYearRef" type="text" value="<?php echo stripslashes($this->txtYearRef);?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
            <td colspan="4"><label>
            <input name="txtIssued" id="txtIssued" type="text" value="<?php echo stripslashes($this->txtIssued);?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo REMARKS;?> <span class="warning">*</span></td>
            <td colspan="4"><label>
            <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="10"><?php echo stripslashes($this->txtRemarks);?></textarea>
            </label></td>
        </tr>
       
        <tr>
        	<td colspan="5">
            <table>
            	<tr>
                    <td colspan="8"><strong><label><?php echo TRANSFERDETAILS;?></label></strong></td>
                </tr>
                <tr>
                    <td><?php echo TOLOCATION;?> <span class="warning">*</span></td> 
                    <td colspan="8"><label>
                      <input name="txtToLoc" id="txtToLoc" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtToLoc);?>" >
                    </label></td>
                 </tr>
                 <tr>
                    <td><?php echo CUSTNAME;?> <span class="warning">*</span></td> 
                    <td colspan="8"><label>
                      <input name="txtCustomer" id="txtCustomer" class="textfieldLarge" type="text" value="<?php echo stripslashes($this->txtCustomer);?>" >
                    </label></td>
                  </tr>
                  <tr>
                    <td><?php echo ISSUEDDTO;?> <span class="warning">*</span></td> 
                    <td colspan="8"><label>
                      <input name="txtIssuedTo" id="txtIssuedTo" type="text" class="textfieldLarge" value="<?php echo stripslashes($this->txtIssuedTo);?>" >
                    </label></td>
                  </tr>
                  <tr>
                    <td><?php echo CODE;?> <span class="warning">*</span></td> 
                    <td colspan="8"><label>
                      <input name="txtCode" id="txtCode" type="text" class="textfield3" value="<?php echo stripslashes($this->txtCode);?>" >
                    </label></td>
                  </tr>
                  <tr>
                    <td><?php echo START;?> <span class="warning">*</span></td> 
                    <td><label>
                     <input name="txtStart" id="txtStart" type="text" class="textfield3" value="<?php echo stripslashes($this->txtStart);?>" >
                    </label></td>
                    <td><?php echo END;?> <span class="warning">*</span></td> 
                    <td><label>
                     <input name="txtEnd" id="txtEnd" type="text" class="textfield3" value="<?php echo stripslashes($this->txtEnd);?>" >
                    </label></td>
                    <td><?php echo STOCKQTY;?> <span class="warning">*</span></td> 
                    <td><label>
                     <input name="txtStockQty" id="txtStockQty" type="text" class="textfield4" value="<?php echo stripslashes($this->txtStockQty);?>" >
                    </label></td>
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
              <input type="button" name="btnCancel" value="<?php echo BACKTOSTOCKTRANSFER;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
