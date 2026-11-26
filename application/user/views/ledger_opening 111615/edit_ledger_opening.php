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



function getSubValues(){
        var div = $('#ddlDivision').val();
        var loc  = $('#ddlLocation').val();

        var nVal1 = $('#ddlLedger').val();
        var nVal2 = $('#ddlSubLedger').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlLedger:nVal1,ddlSubLedger:nVal2,ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/ledger_opening/filterSubValues/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    //alert(response)
                $('.ddlMACCode').each( function(){
                    var id = this.id;
                    var getId = id.split('ddlMACCode');
                    var acc = $('#ddlMACCode'+getId[1]).val();
                    $('#ddlMACCode'+getId[1]).html(response);
                    $('#ddlMACCode'+getId[1]).val(acc);
                 });
                }
            });
}


function getSubDetails(){
        var div = $('#ddlDivision').val();
        var loc  = $('#ddlLocation').val();

        var nVal = $('#ddlLedger').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlLedger:nVal,ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/ledger_opening/filterSubDetails/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    //alert(response)
                $('.ddlMACCode').each( function(){
                    var id = this.id;
                    var getId = id.split('ddlMACCode');
                    var acc = $('#ddlMACCode'+getId[1]).val();
                    $('#ddlMACCode'+getId[1]).html(response);
                    $('#ddlMACCode'+getId[1]).val(acc);
                 });

                }
            });
}


function getLedger(){
        var loc  = $('#ddlLocation').val();
        var div = $('#ddlDivision').val();

        var nVal = $('#ddlLedger').val();
        if(nVal == 'SL')
        {
            $('#subledger').show();
        }//end if
        else
        {
            $('#subledger').hide();
        }//end else

        $.ajax(
            {
                type:'POST',
                data:{ddlLedger:nVal,ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/ledger_opening/filterLedger/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    //alert(response)
                    var nSub = $('#ddlSubLedger').val();
                    $('#ddlSubLedger').html(response);
                    $('#ddlSubLedger').val(nSub);
                }
            });
}


function getSubAccount(){
        var div = $('#ddlDivision').val();
        var loc  = $('#ddlLocation').val();
        $('.ddlMACCode').each( function(){
            var id    = this.id;
            var getId = id.split('ddlMACCode');
            var nVal  = $('#ddlMACCode'+getId[1]).val();
            $.ajax(
                {
                    type:'POST',
                    data:{ddlMACCode:nVal,ddlLocation:loc,ddlDivision:div},
                    url:'<?php echo base_url().'index.php/ledger_opening/filterSubAccount/'.$this->uri->segment(3);?>',
                    dataType:'html',
                    success: function(response) {
                        var nSub = $('#ddlSubAccount'+getId[1]).val();
                       $('#ddlSubAccount'+getId[1]).html(response);
                       $('#ddlSubAccount'+getId[1]).val(nSub);
                    }
                });
        });
}


$(document).ready(function(){

	$('#subledger').hide();
    function getLocation(nVal)
    {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/ledger_opening/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                //alert(response)
                $('#location').html(response);
                getSubValues();
                getSubAccount();

            }
        });
    }

    $("ul.pgcontent").quickPagination({pageSize:"5"});

    $('#btnCancel').click(function(){
        window.location.replace('<?php echo base_url().'index.php/ledger_opening/showlist/'.$this->uri->segment(3);?>');
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
            ddlLedger: {
                selectNone : true
            },
            ddlDivision: {
                selectNone : true
            },
            'ddlLocation': {
                selectNone : true
            }
        },
        messages: {
            ddlLedger: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlDivision: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            'ddlLocation': {
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

    var i = 1;
    $("#addRow").click(function() {
        var m	 = $('#dataTable tbody>tr.re:last').clone(true).find("input").each(function() {
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
            }).end().find("textarea").each(function() {
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

    $('.del').live('click',function(){
        var rowCount = $("#dataTable").attr('rows').length;
        if((rowCount)>3)
        {
            $(this).parent().parent().remove();
            var dId = this.id;
            dId     = dId.split('_');
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/ledger_opening/deleteRecord/'.$this->uri->segment(3);?>',
                    data:{subCode:dId[1]},
                    dataType:'html',
                    success: function(response) {
                    }
                });
        }//end if
        else
        {
            $('#del_'+rowCount).attr('disabled', true);
        }//end if
    });

    $('.ddlCurrency').change(function()
    {
        var id     = this.id;
        var nVal   = $('#'+id).val();
        var getId  = id.split('ddlCurrency');
        var curVal = $('#ddlCurrency'+getId[1]).val();
        $.ajax({
            type:'POST',
            data:{ddlCurrency:curVal},
            url:'<?php echo base_url().'index.php/ledger_opening/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#exRate'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#exRate'+getId[1]).val(response);
            }
        });
    });

    $('.fcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('txtBalDCddlMode');
        var exVal = $('#exRate' + getId[1]).val();
        var fcAmt = $('#txtBalDCddlMode' + getId[1]).val();
        var dcVal = parseFloat(exVal) * parseFloat(fcAmt);
        $('#txtBalFCddlMode' + getId[1]).val(dcVal);
        $('#exRate' + getId[1]).val(exVal);
    });

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        getLocation(nVal);
    });
    $('#location').change(function()
    {
//        getLedger();
//        getSubDetails();
        getSubValues();
        getSubAccount();


    });

	if($('#ddlLedger').val() == 'SL')
	{
		$('#subledger').show();
	}//end if
	else
	{
		$('#subledger').hide();
	}//end else

    $('#ddlLedger').change(function()
    {
        getLedger();
    });

    $('#ddlLedger').change(function()
    {
        getSubDetails();
    });

    $('#ddlSubLedger').change(function()
    {
        getSubValues();
    });

    $('.ddlMode').change(function()
    {
        var nId  = this.id;
        var nVal = $('#'+this.id).val();

        var nVal2 = $('#txtBalDC'+nId).val();
        $.ajax(
            {
                type:'POST',
                data:{ddlMode:nVal,txtBalDC:nVal2},
                url:'<?php echo base_url().'index.php/ledger_opening/getBalanceMode/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    if(nVal == 'Cr')
                    {
                        $('#txtBalCredit'+nId).val(response);
                        $('#txtBalDebit'+nId).val('');
                    }//end if
                    else
                    {
                        $('#txtBalDebit'+nId).val(response);
                        $('#txtBalCredit'+nId).val('');
                    }//end else
                }
            });
    });

    $('.dcAmt').keyup(function()
    {
        var txtTotal = 0;
        $('.dcAmt').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();
            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalDC').val(txtTotal);
    });

    $('.fcAmt').keyup(function()
    {
        var txtTotal = 0;
        $('.fcAmt').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();
            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalFC').val(txtTotal);
    });

    $('.credit').keyup(function()
    {
        var txtTotal = 0;
        $('.credit').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();
            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalC').val(txtTotal);
    });

    $('.debit').keyup(function()
    {
        var txtTotal = 0;
        $('.debit').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();
            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalD').val(txtTotal);
    });

    $('.showAcDetails').change(function()
    {
        getSubAccount();
    });

    getSubValues();
    getSubAccount();

});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>

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
<?php echo form_open('ledger_opening/editLedgeropening/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>
    <?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
<tr>
    <td width="14%" align="left"><?php echo LEDGERNAME;?> <span class="warning">*</span></td>
    <td width="86%" align="left"><label>
        <?php echo form_dropdown('ddlLedger', $list_ledger_value, $this->ddlLedger, 'id="ddlLedger"');?>
    </label></td>
</tr>

<tr id="subledger">
    <td><?php echo SUBLEDGERNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlSubLedger', $list_subledger_value, $this->ddlSubLedger,'id="ddlSubLedger"');?>
    </label></td>
</tr>

<tr>
    <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
    </label></td>
</tr>

<tr>
    <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
    <td><label>
            <span id="location">
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
             </span>
    </label></td>
</tr>

<tr>
    <td><?php echo CUSTDATE;?></td>
    <td><label>
        <input name="txtDate" id="txtDate" type="text" value="<?php echo $this->txtDate;?>" size="10">
    </label></td>
</tr>

<tr>
    <td><?php echo YEARREF;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtOpeningYear" id="txtOpeningYear" type="text" value="<?php echo $this->txtOpeningYear;?>" size="10">
    </label></td>
</tr>

<tr>
    <td colspan="8">
        <table id="dataTable" title="dataTable">
            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
            <tr>
<!--                <td><?php echo LOCATIONNAME;?></td> -->
                <td><?php echo ACCCODENAME;?></td>
                <td><?php echo SUBCODENAME;?></td>
                <td><?php echo CURRNAME;?></td>
                <td><?php echo EXCHANGERATE;?></td>
                <td><?php echo BALANCEDC;?></td>
                <td><?php echo BALANCEFC;?></td>
                <td><?php echo BUDGETMODE;?></td>
                <td><?php echo BALANCECREDIT;?></td>
                <td><?php echo BALANCEDEBIT;?></td>
            </tr>
            <?php
            if(!empty($chkAccCode))
            {
                foreach($chkAccCode as $key => $aitem):
                    ?>
                    <tr class="re">
<!--                        <td><label>
                            <?php //echo form_dropdown('ddlLocation2[]', $list_mloc_value, $chkLoc[$key], "id='ddlLocation2".$key."'");?>
                        </label></td>
-->
                        <td><label>
                            <?php echo form_dropdown('ddlMACCode[]', $list_MACCode, $chkAccCode[$key],'id="ddlMACCode'.$key.'" class="showAcDetails ddlMACCode"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubAccount[]', $this->ledger_opening_model->filterSubAccount($chkAccCode[$key]), $chkAccSubCode[$key], 'id="ddlSubAccount'.$key.'" class="singleDrop ddlSubAccount"');?>
                        </label></td>

                        <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $chkCurr[$key],'id="ddlCurrency'.$key.'" class="ddlCurrency"');?></td>

                        <td><input name="txtExchange[]" id="exRate<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($chkExchangeRate[$key]);?>"></td>

                        <td><input name="txtBalDC[]" id="txtBalDCddlMode<?php echo $key;?>" class="textfield2 fcRate dcAmt" type="text" value="<?php echo stripslashes($chkBalDC[$key]);?>" size="5"></td>

                        <td><input name="txtBalFC[]" id="txtBalFCddlMode<?php echo $key;?>" class="textfield2 fcAmt" type="text" value="<?php echo stripslashes($chkBalFC[$key]);?>" size="5"></td>

                        <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode'.$key.'" class="ddlMode"');?></td>

                        <td><input name="txtBalCredit[]" id="txtBalCreditddlMode<?php echo $key;?>" class="textfield2 credit" type="text" value="<?php echo stripslashes($chkBalCredit[$key]);?>" size="5"></td>

                        <td><input name="txtBalDebit[]" id="txtBalDebitddlMode<?php echo $key;?>" class="textfield2 debit" type="text" value="<?php echo stripslashes($chkBalDebit[$key]);?>" size="5"></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                    </tr>
                    <?php
                endforeach;
            }//end if
            else
            { ?>
                <tr class="re">
<!--                    <td><label>
                        <?php //echo form_dropdown('ddlLocation2[]', $list_mloc_value, $this->ddlLocation2, "id='ddlLocation2'".$i."");?>
                    </label></td>
-->
                    <td><label>
                        <?php echo form_dropdown('ddlMACCode[]', $list_MACCode,$this->ddlMACCode,'id="ddlMACCode" class="showAcDetails ddlMACCode"');?>
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('ddlSubAccount[]', $list_subacc, $this->ddlSubAccount, 'id="ddlSubAccount" class="singleDrop ddlSubAccount"');?>
                    </label></td>

                    <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency" class="ddlCurrency"');?></td>

                    <td><input name="txtExchange[]" id="exRate" class="textfield5" type="text" value="1"></td>

                    <td><input name="txtBalDC[]" id="txtBalDCddlMode" class="textfield2 fcRate dcAmt" type="text" value="" size="5"></td>

                    <td><input name="txtBalFC[]" id="txtBalFCddlMode" class="textfield2 fcAmt" type="text" value="" size="5"></td>

                    <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="ddlMode"');?></td>

                    <td><input name="txtBalCredit[]" id="txtBalCreditddlMode" class="textfield2 credit" type="text" value="" size="5"></td>

                    <td><input name="txtBalDebit[]" id="txtBalDebitddlMode" class="textfield2 debit" type="text" value="" size="5"></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
                </tr>
                <?php
            }//end else
            ?>
            
            <tr class="totalVal">
                <td colspan="3"></td>
                <td><?php echo TOTALAMOUNT;?></td>
                <td><input name="txtTotalDC" id="txtTotalDC" type="text" value="<?php echo $this->txtTotalDC;?>" class="textfield6"></td>
            
                <td><input name="txtTotalFC" id="txtTotalFC" type="text" value="<?php echo $this->txtTotalFC;?>" class="textfield6"></td>
                <td></td>
                <td><input name="txtTotalC" id="txtTotalC" type="text" value="<?php echo $this->txtTotalC;?>" class="textfield6"></td>
            
                <td><input name="txtTotalD" id="txtTotalD" type="text" value="<?php echo $this->txtTotalD;?>" class="textfield6"></td>
                
                <td></td>
            </tr>            
        </table>
    </td>
</tr>

<tr>
    <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
    <td colspan="9"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9"><label>
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
        <?php if($this->uri->segment(4) == 'a'){?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo BACKTOLEDGEROPENING;?>" id="btnCancel">
    </label></td>
</tr>
</tbody>
</table>
</form>
</div>
