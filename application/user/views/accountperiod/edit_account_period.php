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

    function genAc(){
        var nVal  = $('#txtTDate').val();
        var nVal2 = $('#txtFDate').val();
        var nVal3 = $('#txtYearref').val();
        var nVal4 = $('#ddlStatus').val();
        var nAcId = $('#acId').val();
        $.ajax({
            type:'POST',
            data:{txtTDate:nVal,txtFDate:nVal2,txtYearref:nVal3,ddlStatus:nVal4,nAcId:nAcId},
            url:'<?php echo base_url().'index.php/account_period/generateAccperiod/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>',
            dataType:'html',
            beforeSend: function() {
                $('#accperiod').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                    $('#accperiod').html(response);
            }
        });
    }

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/account_period/showlist/'.$this->uri->segment(3);?>');
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
	
    $("#txtFDate").datepick({ dateFormat:'yyyy-mm-dd',onSelect:function(){ genAc(); }});
    $("#txtTDate").datepick({ dateFormat:'yyyy-mm-dd',onSelect:function(){ genAc(); }});


	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			 txtYearref: {
			    required : true
					},
			 txtFDate: {
					required : true
					},
			 txtTDate: {
					required : true
					},
			 ddlStatus: {
				 	selectNone : true
			 		}
		},
		messages: {
			txtYearref: {
				required : "<?php echo ACCYEARREF;?><?php echo CANTBEBLANK;?>"
			},
			txtFDate: {
				required : "<?php echo ACCFROMDATE;?><?php echo CANTBEBLANK;?>"
			},
			txtTDate: {
				required : "<?php echo ACCTODATE;?><?php echo CANTBEBLANK;?>"
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
	
    $('#ddlStatus').change(function()
    {
    <?php if($this->uri->segment(4) == 'a'){?>
        genAc();
    <?php } //endif 
    else { ?>
        var nVal1 = $('#ddlStatus').val();
        $('.selectStatus').each( function () {
                $(this).val(nVal1);
                });
        <?php } //end if ?>
    });

    <?php if($this->uri->segment(4) == 'e'){?>
        genAc();
    <?php }//end if?>
	
});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

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
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('account_period/editAccountperiod/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <input name="acId" id="acId" class="textfield" type="hidden" value="<?php echo $this->catId; ?>">
        <tr>
            <td width="14%" align="left"><?php echo ACCYEARREF;?> <span class="warning">*</span></td>
            <td width="86%" align="left"><label>
             <input name="txtYearref" id="txtYearref" class="textfield" type="text" value="<?php echo date('Y');?>-<?php echo date('Y');?>">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo ACCFROMDATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtFDate" id="txtFDate" class="textfield" type="text" value="<?php echo stripslashes($this->txtFDate);?>">
            </label></td>
        </tr>

        <tr>
            <td><?php echo ACCTODATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtTDate" id="txtTDate" class="txtPrefix" type="text" value="<?php echo stripslashes($this->txtTDate);?>">
            </label></td>
        </tr>
        <tr>
            <td><?php echo ACCSTATUS;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlStatus', $list_status, $this->ddlStatus, 'id="ddlStatus"');?>
            </label></td>
        </tr>
        
        <tr>
        	<td colspan="2" id="accperiod">
            </td>
        </tr>
        
        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOACCPERIOD;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
