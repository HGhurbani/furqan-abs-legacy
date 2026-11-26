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
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
    submitHandler: function() {}
});

$(document).ready(function(){

    function getLocation(nVal)
    {
        var locVal = $('#ddlLocation').val();
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/acm_entry/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#loc').html(response);
                $('#ddlLocation').val(locVal);
                changeType();
                changeAllAccount();
                }
        });
    }

function changeType(){
    <?php if($this->uri->segment(4) == 'a'):?>
     var loc  = $('#ddlLocation').val();
     var dt  = $('#txtDate').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,dt:dt},
         url:'<?php echo base_url().'index.php/acm_entry/getDocumentNumber/'.$this->uri->segment(3);?>',
         dataType:'html',
         beforeSend: function() {
             $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
         },
         success: function(response) {
             $('#txtDocNumber').val(response);
         }
     });
     <?php endif; ?>
}

function changeAllAccount(){
     var loc  = $('#ddlLocation').val();
     var div = $('#ddlDivision').val();

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/acm_entry/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             $('.ddlAccount2').each( function(){
                 var id = this.id;
                 var getId = id.split('ddlAccount2');
                 var acc = $('#ddlAccount2'+getId[1]).val();
                // $('#ddlAccount2'+getId[1]).html(response);
                // $('#ddlAccount2'+getId[1]).val(acc);
             });
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/acm_entry/getBSPAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             var acc = $('#ddlAccount').val();
             $('#ddlAccount').html(response);
             $('#ddlAccount').val(acc);
         }
     });
        var nVal  = $('#ddlAccount').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAccount:nVal,ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/acm_entry/getSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                  //  $('#ddlSubAccount').html(response);
                }
            });


}




    $('#ddlCurrencyMain').change(function()
    {
        var nVal = $('#ddlCurrencyMain').val();
        $.ajax({
            type:'POST',
            data:{ddlCurrency:nVal},
            url:'<?php echo base_url().'index.php/acm_entry/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#txtRate').val(response);
            }
        });
    });

    $("ul.pgcontent").quickPagination({pageSize:"5"});
	$('#txtDocNumber').click(function(){
		changeType();
	});
	$('#txtDocNumber').keyup(function(){
		changeType();
	});
	$('#txtDate').keyup(function(){
		changeType();
	});
	$('#txtDate1').change(function(){
		changeType();
	});
    $('#btnCancel').click(function(){
        window.location.replace('<?php echo base_url().'index.php/acm_entry/showlist/'.$this->uri->segment(3);?>');
        return false;
    });

$('#btnSaveExit,#btnGo').click(function(e) {
             var primaryDebitAmount = $('#txtAmountDC').val();
            var txtTotal = 0;
			var creditTotal = 0;
			var debitTotal = primaryDebitAmount;
			var  nV=0;
            $('.dcRate').each(function () 
			{
				var rowNo = $(this).attr('data-row');
				var rv = $(".mode"+rowNo).val();
				//alert(rv);
                var nVal2 = $(this).val();
                if (nVal2 == '') {
                   nVal2=0;
                }
				//txtTotal = parseFloat(txtTotal)+parseFloat(nVal2);
				if(rv == 'Cr'){
					creditTotal = (parseFloat(creditTotal)+parseFloat(nVal2)).toFixed(3);
				}
				else if(rv == 'Dr'){
					debitTotal = (parseFloat(debitTotal)+parseFloat(nVal2)).toFixed(3);
				}
            });
			//var debitTotal = debitTotal.toFixed(3);
			//var creditTotal = creditTotal.toFixed(3);
			//alert(creditTotal+"===="+debitTotal);
            if(parseFloat(creditTotal) == parseFloat(debitTotal))
            {
                return true;
            }
            else
            {
                alert('Account total amount should be equal to total collected amount');
                return false;
            }
        });
		
    $("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});
    $("#txtRefDate").datepick({ dateFormat: 'dd-mm-yyyy'});
    $("#txtCheqDate").datepick({ dateFormat: 'dd-mm-yyyy'});

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
            ddlLocation: {
                selectNone : true
            },
            'ddlAccount2[]': {
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
            ddlLocation: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            'ddlAccount2[]': {
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
		$(".chosen-select").chosen("destroy");
        var m	 = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
            var id = this.id;
			var classes = $(this).attr('class');
			var isDcrate = classes.search("dcRate");
			if(isDcrate>=0)
			{
				
				$(this).attr({
				  'data-row': (i+1),
				  'data-name': 'dcrate'+(i+1)
				});
			}
            $(this).attr({
                'id': function(_, id2) { return id + i; },
                'value': ''
            });
        }).end().find("select").each(function() {
			var ids = $(this).attr('id');
				//alert(ids);
				var isDdlmode = ids.search("ddlMode"); 
				//alert(isDdlmode);
				if(isDdlmode >= 0)
				{
					
					$(this).removeClass("mode"+i);
					$(this).addClass("mode"+(i+1));
				}
				//alert(this.id);
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': '0'
                });
            }).end().find("textarea").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
					'class':'txtDes'
                    //'value': ''
                });
            }).end().insertAfter('#dataTable tbody>tr:last');
        i++;
		$(".chosen-select").chosen();
        var rowCount = $("#dataTable").attr('rows').length;
        m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
		 m.find('img[class="del"]').attr({

                        'id': 'del'

                    });
        return false;
    });

    $('.del').live('click',function(){
        var rowCount = $("#dataTable").attr('rows').length;
        if((rowCount)>2)
        {
            $(this).parent().parent().remove();
            var dId = this.id;
            dId     = dId.split('_');
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/acm_entry/deleteRecord/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/acm_entry/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#exRate'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#exRate'+getId[1]).val(response);
            }
        });
    });


    //single amount dc calculation
    $('#txtAmountFCMain').keyup(function(e)
    {
        var nVal   = $('#txtAmountFCMain').val();
        var nVal2  = $('#txtRate').val();
        var total  = parseFloat(nVal) * parseFloat(nVal2);
        $('#txtAmountDC').val(total);
        $('.dcRate').each(function(){
            var temp_id    = this.id;
            var temp_getId = temp_id.split('fcAmt');
             $('#fcAmt' + temp_getId[1]).val(total);
            $('#dcAmt' + temp_getId[1]).val(parseFloat(nVal).toFixed(2));
        });
    });
/*
    $('.dcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('fcAmt');
        var exVal =  $('#txtRate').val();
        var fcAmt = $('#fcAmt' + getId[1]).val();
        var dcVal = parseFloat(fcAmt) * parseFloat(exVal);
        $('#dcAmt' + getId[1]).val(parseFloat(dcVal).toFixed(2));
//        $('#exRate' + getId[1]).val(exVal);
    });

*/

    $('.dcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('fcAmt');
        //var exVal = $('#txtRate' + getId[1]).val();
        var exVal =  $('#txtRate').val();
        var fcAmt = $('#fcAmt' + getId[1]).val();
        fcAmt = fcAmt != '' ? fcAmt : 0 ;
        //var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
        var dcVal = parseFloat(fcAmt) / parseFloat( exVal);
        $('#dcAmt' + getId[1]).val(parseFloat(dcVal).toFixed(2));
 //       $('#exRate' + getId[1]).val(exVal);
    });

    $('.fcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('dcAmt');
        var exVal =  $('#txtRate').val();
        var fcAmt = $('#dcAmt' + getId[1]).val();
        var dcVal = parseFloat(fcAmt) / parseFloat( exVal);
        $('#fcAmt' + getId[1]).val(dcVal);
//        $('#exRate' + getId[1]).val(exVal);
    });

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        getLocation(nVal);
    });
    $('#loc').change(function(){
                changeType();
                changeAllAccount();
     });

getLocation($('#ddlDivision').val()); 


//    getLocation($('#ddlDivision').val());

    //single amount fc calculation
    $('#txtAmountDC').keyup(function(e)
    {
        var nVal   = $('#txtAmountDC').val();
        var nVal2  = $('#txtRate').val();
        var total  = parseFloat(nVal) / parseFloat(nVal2);
        $('#txtAmountFCMain').val(parseFloat(total).toFixed(2));
                        $('.dcRate').each(function(){
            var temp_id    = this.id;
            var temp_getId = temp_id.split('fcAmt');
             $('#fcAmt' + temp_getId[1]).val(nVal);
            $('#dcAmt' + temp_getId[1]).val(parseFloat(total).toFixed(2));
        });
    });

    $('.ddlAccount2').change(function()
    {
        var id    = this.id;
        var getId = id.split('ddlAccount2');
        var nVal  = $('#ddlAccount2'+getId[1]).val();
        var accId = nVal.split("-");
        $.ajax(
            {
                type:'POST',
                data:{ddlAccount2:accId[0],tableType:accId[1]},
                url:'<?php echo base_url().'index.php/acm_entry/filterSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#ddlSubAccount2'+getId[1]).html(response);
                }
            });
    });

    $('#ddlAccount').change(function()
    {
        var nVal  = $('#ddlAccount').val();
         var loc  = $('#ddlLocation').val();
         var div = $('#ddlDivision').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAccount:nVal,ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/acm_entry/getSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#ddlSubAccount').html(response);
                }
            });
    });

	<?php if($this->uri->segment(4) == 'a')
	{?>
	$('#txtRemarks').keyup(function(){
		var nVal = $('#txtRemarks').val();
		$('#txtDes0').val(nVal);
		$('.txtDes').val(nVal);
		$('#txtDes2').val(nVal);
		$('#txtDes3').val(nVal);
	});
	<?php }//end if?>

});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
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
<?php echo form_open('acm_entry/editAcmEntry/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
    <tr>
        <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
        <td><label>
            <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
        </label></td>

        <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
        <td><label><span id="loc">
                 <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                </span>
        </label></td>
    </tr>

    <tr>
        <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtDate" id="txtDate" type="text" value="<?php echo $this->txtDate;?>" class="pricebox">
        </label></td>
<!--
        <td><?php echo CASHRECEIPTTYPE;?></td>
        <td>
            <?php echo form_dropdown('ddlTransaction', $list_trans_value, $this->ddlTransaction, 'id="ddlTransaction"');?>
        </td>
-->
        <td><?php echo TRANSNAME;?></td>
        <td><label>
            <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                   value="<?php echo $this->txtDocNumber;?>" size="20" readonly="readonly">
        </label></td>

    </tr>

    <tr>
        <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
        <td><label>
         <?php echo form_dropdown('ddlCurrencyMain', $list_curr_value, $this->ddlCurrencyMain, 'id="ddlCurrencyMain" class="chosen-select"');?>
        </label></td>
        <td><?php echo RATE;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtRate" id="txtRate" class="textfield pricebox" type="text" value="<?php echo stripslashes($this->txtRate);?>" size="2">
        </label></td>

    </tr>

    <tr>
        <td><?php echo MODEOFPAYMENT;?> <span class="warning">*</span></td>
        <td><label>
            <label><?php echo form_dropdown('ddlModeMain', $list_debit_mode, $this->ddlModeMain, 'id="ddlModeMain" class="singleDrop"');?></label>
        </label></td>
        <td><?php echo ENTEREDBY;?></td>
        <td colspan="7"><label>
         <input name="txtEntered" id="txtEntered" type="text" value="<?php echo $this->txtEntered;?>" class="textfield">
        </label></td>
    </tr>

    </tr>
<!--
    <tr>
        <td><?php echo CHEQUENUMBER;?></td>
        <td><label>
            <input name="txtCheqNumber" id="txtCheqNumber" type="text" value="<?php echo $this->txtCheqNumber;?>" class="pricebox">
        </label></td>

        <td><?php echo CHEQUEDATE;?></td>
        <td><label>
            <input name="txtCheqDate" id="txtCheqDate" type="text" value="<?php echo $this->txtCheqDate;?>" class="textfield">
        </label></td>
    </tr>
-->
    <tr>
        <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
        <td><label>
            <?php echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
        </label></td>

        <td><?php echo SUBCODE;?></td>
        <td><label>
            <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>
        </label></td>
    </tr>

    <tr>
        <td><?php echo AMOUNTDC;?></td>
        <td><label>
            <input name="txtAmountDC" id="txtAmountDC" type="text" value="<?php echo $this->txtAmountDC;?>" class="pricebox">
        </label></td>

        <td><?php echo AMOUNTFC;?></td>
        <td><label>
            <input name="txtAmountFCMain" id="txtAmountFCMain" type="text" value="<?php echo $this->txtAmountFCMain;?>" class="pricebox" tabindex="-1">
        </label></td>
    </tr>

    <tr>
        <td><?php echo REFNUMBER;?></td>
        <td><label>
         <input name="txtRefNumberMain" id="txtRefNumberMain" type="text" value="<?php echo $this->txtRefNumberMain;?>" size="40" class="pricebox">
        </label></td>

        <td><?php echo REFDATE;?></td>
        <td colspan="3"><label>
         <input name="txtRefDate" id="txtRefDate" type="text" value="<?php echo $this->txtRefDate;?>" size="40" class="pricebox">
        </label></td>
    </tr>

    <tr>
        <td><?php echo REFNAME;?></td>
        <td><label>
         <input name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">
        </label></td>

    <tr>
        <td><?php echo REMARKS;?></td>
        <td><label>
         <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->txtRemarks;?></textarea>
        </label></td>
    </tr>

    <tr>
        <td colspan="9">
        <table id="dataTable" title="dataTable">
        <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew"  tabindex="-1"/>
            <tr>
<!--                <td><?php echo LOCATIONNAME;?></td> -->
                <td><?php echo ACCCODENAME;?></td>
                <td><?php echo SUBCODE;?></td>
                <td><?php echo BUDGETMODE;?></td>
                <td><?php echo REFNUMBER;?></td>
<!--                <td><?php echo CURRNAME;?></td>
                <td><?php echo EXCHANGERATE;?></td> -->
                <td><?php echo AMOUNTDC;?></td>
                <td><?php echo AMOUNTFC;?></td>
                <td><?php echo DESCRIPTION;?></td>
            </tr>
            <?php
            if(!empty($chkLoc))
            {
                foreach($chkLoc as $key => $aitem):
                    ?>
                    <tr>
<!--                        <td><label>
                            <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $chkLoc[$key], "id='ddlLocation2".$key."' class='singleDrop'");?>
                        </label> -->
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $chkAccCode[$key], 'id="ddlAccount2" class="ddlAccount2 chosen-select"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubAccount2[]', $this->acm_entry_model->filterSubAccount($chkAccCode[$key],$chkTable[$key]), $chkAccSubCode[$key], 'id="ddlSubAccount2" class="ddlSubAccount2"');?>
                        </label></td>

                        <td><?php $key1=$key+1; echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="ddlMode mode'.$key1.'"');?></td>

                        <td><input name="txtRefNumber[]" id="txtRefNumber<?php echo $key;?>" class="textfield3" type="text" value="<?php echo stripslashes($chkRefNumber[$key]);?>"></td>

<!--                        <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $chkCurr[$key],'id="ddlCurrency'.$key.'" class="ddlCurrency" class="singleDrop"');?></td>

                        <td><input name="txtExchange[]" id="exRate<?php echo $key;?>" class="textfield5 " type="text" value="<?php echo stripslashes($chkExchangeRate[$key]);?>"></td> -->


                        <td><input name="txtAmount[]" id="fcAmt<?php echo $key;?>" class="textfield6 dcRate" type="text" data-row="<?php echo $key+1;?>" data-name="dcrate<?php echo $key+1;?>" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
                        <td><input name="txtAmountFC[]" id="dcAmt<?php echo $key;?>" class="textfield4 fcRate" type="text" value="<?php echo stripslashes($chkAmountFC[$key]);?>" tabindex="-1"></td>

                        <td><textarea name="txtDes[]" id="txtDes<?php echo $key;?>" rows="2" class="txtDes" cols="5" style="min-width: 100px;"><?php echo stripslashes($chkDes[$key]);?></textarea></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                    </tr>
                    <?php
                endforeach;
            }//end if
            else
            {
				$i = 0;
				while ($i < 1) 
				{?>
                <tr>
<!--                    <td><label>
                        <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $this->ddlLocation2, "id='ddlLocation2".$i."' class='singleDrop'");?>
                    </label> -->
                    </td>

                    <td><label>
                        <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $this->ddlAccount2, 'id="ddlAccount2'.$i.'" class="ddlAccount2 chosen-select"');?>
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('ddlSubAccount2[]', $list_subacc_multiple, $this->ddlSubAccount2, 'id="ddlSubAccount2'.$i.'" class="ddlSubAccount2"');?>
                    </label></td>

                    <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode'.$i.'" class="ddlMode mode1"');?></td>

                    <td><input name="txtRefNumber[]" id="txtRefNumber<?php echo $i;?>" class="textfield4" type="text" value=""></td>

<!--                    <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency'.$i.'" class="ddlCurrency" class="singleDrop"');?></td>

                    <td><input name="txtExchange[]" id="exRate<?php echo $i;?>" class="textfield5" type="text" value="<?php echo $this->exRate;?>"></td> -->


                    <td><input name="txtAmount[]" id="fcAmt<?php echo $i;?>" class="textfield6 dcRate" type="text" data-row="1" data-name="dcrate1" value=""></td>
                    <td><input name="txtAmountFC[]" id="dcAmt<?php echo $i;?>" class="textfield4 fcRate" type="text" value="" tabindex="-1"></td>

                    <td><textarea name="txtDes[]" id="txtDes<?php echo $i;?>" rows="2" cols="5" class="txtDes" style="min-width: 100px;"></textarea></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
                </tr>
                <?php
					$i++;
                 	}//end while
				
                }//end else
                ?>
     </table>
   </td>
   </tr>
<!--
   <tr>
       <td><?php echo INVOICE;?></td>
       <td><label>
        <input name="txtInvoice" id="txtInvoice" type="text" value="<?php echo $this->txtInvoice;?>" class="textfield">
       </label></td>

       <td><?php echo INVOICEDATE;?></td>
       <td><label>
        <input name="txtInvDate" id="txtInvDate" type="text" value="<?php echo $this->txtInvDate;?>" class="textfield">
       </label></td>
   </tr>
-->
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
          <input type="button" name="btnCancel" value="<?php echo BACKTOACMENTRY;?>" id="btnCancel">
        </label>
            <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?>
        </td>
   </tr>
   </tbody>
  </table>
  </form>
<script src="<?php echo base_url();?>static/plugins/dropdown/chosen.jquery.js" type="text/javascript"></script>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>
  </div>
