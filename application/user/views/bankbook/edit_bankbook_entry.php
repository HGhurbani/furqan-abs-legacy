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

function getLocation(nVal){
    var locVal = $('#ddlLocation').val();
    $.ajax({
        type:'POST',
        data:{ddlDivision:nVal},
        url:'<?php echo base_url().'index.php/bankbook_entry/filterDivision/'.$this->uri->segment(3);?>',
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

    $.ajax({
        type:'POST',
        data:{ddlDivision:nVal},
        url:'<?php echo base_url().'index.php/bankbook_entry/filterMultipleLocation/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('.ddlLocation2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('.ddlLocation2').html(response);
        }
    });

}


function changeType(){
 <?php if($this->uri->segment(4) == 'a'):?>
     var loc  = $('#ddlLocation').val();
     var dt  = $('#txtDate1').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,dt:dt},
         url:'<?php echo base_url().'index.php/bankbook_entry/getDocumentNumber/'.$this->uri->segment(3);?>',
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
         url:'<?php echo base_url().'index.php/bankbook_entry/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             $('.ddlAccount2').each( function(){
                 var id = this.id;
                 var getId = id.split('ddlAccount2');
                 var acc = $('#ddlAccount2'+getId[1]).val();
                 //$('#ddlAccount2'+getId[1]).html(response);
                 //$('#ddlAccount2'+getId[1]).val(acc);
             });
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/bankbook_entry/getBankAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             var acc = $('#ddlAccount').val();
             $('#ddlAccount').html(response);
             $('#ddlAccount').val(acc);
         }
     });

}


$(document).ready(function(){

    $('#ddlCurrencyMain').change(function()
    {
        var nVal = $('#ddlCurrencyMain').val();
        $.ajax({
            type:'POST',
            data:{ddlCurrency:nVal},
            url:'<?php echo base_url().'index.php/bankbook_entry/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#txtRate').val(response);
            }
        });
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
		
    $('#txtAmountFCMain').keyup(function()
    {
        var nVal  = $('#ddlCurrencyMain').val();
        var fcAmt = $('#txtAmountFCMain').val();
        $.ajax({
            type:'POST',
            data:{fcAmt:fcAmt,selCurrency:nVal},
            url:'<?php echo base_url().'index.php/bankbook_entry/convertCurrency/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#txtAmountDC').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $('#txtAmountDC').val(obj.covAmt);
            }
        });
    });

    //accoutns currency converstion calcualtion
    $('.textfield4').keyup(function(e) {

        var id    = this.id;
        var fcAmt = $('#'+id).val();
        var nVal = $('#ddlCurrencyMain').val();

        $.ajax(
            {
                type:'POST',
                url:'<?php echo base_url().'index.php/bankbook_entry/convertCurrency/'.$this->uri->segment(3);?>',
                data:{fcAmt:fcAmt,selCurrency:nVal},
                dataType:'html',
                beforeSend: function() {
                    $('#txtAmount'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    var obj = jQuery.parseJSON(response);

                    $('#txtAmount'+id).val(obj.covAmt);
                    $('#txtExchange'+id).val(obj.exRate);
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
	$('#txtDate1').keyup(function(){
		changeType();
	});
	$('#txtDate1').change(function(){
		changeType();
	});
	$('#btnCancel').click(function(){
		window.location.replace('<?php echo base_url().'index.php/bankbook_entry/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	$("#txtDate1").datepick({ dateFormat: 'dd-mm-yyyy'});
	$("#txtRefDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$("#txtCheqDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$("#txtInvDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	
		
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
              'value': '0'
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
                var id = this.id;
				var newid = id.split('ddlAccount2');

				if(newid[0] == '')
				{
					if(newid[1] != undefined)
						newid = newid[1];
	
					if(parseInt(newid))
					{
						newid = newid;
						$('.btnAllocation').attr('title',newid+i);
					}//end if
					else
					{
						$('.btnAllocation').attr('title','1');
					}//end esle
				}//end if

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
                url:'<?php echo base_url().'index.php/bankbook_entry/deleteRecord/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/bankbook_entry/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#exRate'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#exRate'+getId[1]).val(response);
            }
        });
    });

    $('#fcAmt').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('fcAmt');
        var exVal =  $('#txtRate').val();
        var fcAmt = $('#fcAmt' + getId[1]).val();
        //var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
        fcAmt = fcAmt != '' ? fcAmt : 0 ;
        var dcVal = parseFloat(fcAmt) / parseFloat(exVal);
        $('#dcAmt' + getId[1]).val(parseFloat(dcVal).toFixed(2));
        //$('#exRate' + getId[1]).val(exVal);
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



    $('.fcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('dcAmt');
        var exVal =  $('#txtRate').val();
        var fcAmt = $('#dcAmt' + getId[1]).val();
        //var dcVal = parseFloat(fcAmt) / parseFloat( exVal);
        fcAmt = fcAmt != '' ? fcAmt : 0 ;
        var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
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


    $('.ddlAccount2').change(function()
    {
        var id    = this.id;
        var getId = id.split('ddlAccount2');
        var nVal  = $('#ddlAccount2'+getId[1]).val();
        var accId  = nVal.split("-");
        $.ajax(
            {
                type:'POST',
                data:{ddlAccount2:accId[0],tableType:accId[1]},
                url:'<?php echo base_url().'index.php/bankbook_entry/filterSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('.btnAllocation').attr('title',getId[1]);
                    $('#ddlSubAccount2'+getId[1]).html(response);
                }
            });
    });

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

	$('#txtRemarks').keyup(function(){
		var nVal = $('#txtRemarks').val();
		$('.txtDes').val(nVal);
	});

    $('#btnAllocation').click(function(){
		$('#toPopup1').show();
        var id     = this.title;
        if(id=='')
        {
            var accVal = $('.ddlAccount2').val();
            var nVal2 = $('.ddlSubAccount2').val();
            var accId  = accVal.split("-");
        }
        else{
            var nVal1  = $('#ddlAccount2'+id).val();
            var accId  = nVal1.split("-");
            var nVal2  = $('#ddlSubAccount2'+id).val();
        }
        $.ajax({
            type:'POST',
            data:{ddlAccount2:accId[0],tableVal:accId[1],ddlSubAccount2:nVal2,idVal:id},
            url:'<?php echo base_url().'index.php/bankbook_entry/getAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
            dataType:'html',
            success: function(response) {
                $('#popup_content').html(response);
            }
        });
    });
});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

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
<?php echo form_open('bankbook_entry/editBankbookentry/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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
             <input name="txtDate1" id="txtDate1" type="text" value="<?php echo $this->txtDate1;?>" class="pricebox">
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
             <?php echo form_dropdown('ddlCurrencyMain', $list_curr_value, $this->ddlCurrencyMain, 'id="ddlCurrencyMain" class="ddlCurrencyMain chosen-select "');?>
            </label></td>

            <td><?php echo RATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtRate" id="txtRate" class="textfield  pricebox" type="text" value="<?php echo stripslashes($this->txtRate);?>" size="2">
            </label></td>
        </tr>

		<tr>
            <td><?php echo REFNUMBER;?></td>
            <td><label>
             <input name="txtRefNumberMain" id="txtRefNumberMain" type="text" value="<?php echo $this->txtRefNumberMain;?>" size="40" class="pricebox">
            </label></td>

            <td><?php echo REFDATE;?></td>
            <td><label>
             <input name="txtRefDate" id="txtRefDate" type="text" value="<?php echo $this->txtRefDate;?>" size="40" class="pricebox">
            </label></td>
        </tr>

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

        <tr>
            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
            </label></td>

            <td><?php echo MODEOFPAYMENT;?> <span class="warning">*</span></td>
            <td><label>
                <label><?php echo form_dropdown('ddlModeMain', $list_debit_mode, $this->ddlModeMain, 'id="ddlModeMain" class="singleDrop"');?></label>
            </label></td>

<!--            <td><?php echo SUBCODE;?></td>
            <td><label>
                <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>
            </label></td> -->
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
            <td><?php echo REFNAME;?></td>
            <td><label>
                <input name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">
            </label></td>

            <td><?php echo ENTEREDBY;?></td>
            <td><label>
                <input name="txtEntered" id="txtEntered" type="text" value="<?php echo $this->txtEntered;?>" class="pricebox">
            </label></td>
        </tr>
 
 		<tr>
            <td><?php echo REMARKS;?></td>
            <td><label>
                <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->txtRemarks;?></textarea>
            </label></td>
        </tr>
               
        <tr>
        	<td colspan="10">
                <table id="dataTable" title="dataTable">
                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew"  tabindex="-1" />
                <tr>
                    <td>&nbsp;</td>
                  <td><?php echo LOCATIONNAME;?></td> 
                    <td><?php echo ACCCODENAME;?></td>
                    <td><?php echo SUBCODE;?></td>
                    <td><?php echo BUDGETMODE;?></td>
                    <td><?php echo REFNUMBER;?></td>
<!--                    <td><?php echo CURRNAME;?></td>
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
                    <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"  tabindex="-1"></td>
                    <td><label>
                        <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $chkLoc[$key], "id='ddlLocation2".$key."'");?>
                    </label> 
                    </td>

                    <td><label>
                        <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $chkAccCode[$key], 'id="ddlAccount2'.$key.'" class="ddlAccount2 chosen-select"');?>
                    </label></td>

                    <td><label>
						<?php echo form_dropdown('ddlSubAccount2[]',$this->bankbook_model->filterSubAccount($chkAccCode[$key],$chkTable[$key]), $chkAccSubCode[$key], 'id="ddlSubAccount2'.$key.'" class="ddlSubAccount2"');?>
                    </label></td>

                    <td><?php $key1=$key+1; echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="ddlMode mode'.$key1.'"');?></td>

                    <td><input name="txtRefNumber[]" id="txtRefNumber<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($chkRefNumber[$key]);?>"></td>

<!--                    <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $chkCurr[$key],'id="ddlCurrency'.$key.'" class="ddlCurrency singleDrop"');?></td>

                    <td><input name="txtExchange[]" id="exRate<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($chkExchangeRate[$key]);?>"></td> -->


                    <td><input name="txtAmount[]" id="fcAmt<?php echo $key;?>" class="textfield5 dcRate" data-row="<?php echo $key+1;?>" data-name="dcrate<?php echo $key+1;?>" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
                    <td><input name="txtAmountFC[]" id="dcAmt<?php echo $key;?>" class="textfield5 fcRate" type="text" value="<?php echo stripslashes($chkAmountFC[$key]);?>" tabindex="-1"></td>

                    <td><textarea name="txtDes[]" id="txtDes<?php echo $key;?>" rows="2" cols="5" style="min-width: 100px;" class="txtDes"><?php echo stripslashes($chkDes[$key]);?></textarea></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                </tr>
                <?php
                    endforeach;
                }//end if
                else
                {
                ?>
                <tr>
                    <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect" class="chkSelect" value=""  tabindex="-1"></td>
                  <td><label>
                        <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $this->ddlLocation2, "id='ddlLocation2' class='ddlLocation2'");?>
                    </label> 
                    </td>

                    <td><label>
                        <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $this->ddlAccount2, 'id="ddlAccount2" class="ddlAccount2 chosen-select"');?>
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('ddlSubAccount2[]', $list_subacc_multiple, $this->ddlSubAccount2, 'id="ddlSubAccount2" class="ddlSubAccount2"');?>
                    </label></td>

                    <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="ddlMode mode1"');?></td>

                    <td><input name="txtRefNumber[]" id="txtRefNumber" class="textfield5" type="text" value=""></td>

<!--                    <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency" class="ddlCurrency singleDrop"');?></td>

                    <td><input name="txtExchange[]" id="exRate" class="textfield5" type="text" value="<?php echo $this->exRate;?>"></td> -->


                    <td><input name="txtAmount[]" id="fcAmt" class="textfield5 dcRate" data-row="1" data-name="dcrate1" type="text" value=""></td>
                    <td><input name="txtAmountFC[]" id="dcAmt" class="textfield5 fcRate" type="text" value="" tabindex="-1"></td>
                    
                    <td><textarea name="txtDes[]" id="txtDes" rows="2" cols="5" style="min-width: 100px;" class="txtDes"></textarea></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_" title="Delete"/></td>
                </tr>
                <?php
                }//end else
                ?>
             </table>
            </td>
       </tr>

        <!--<tr>
            <td><a href="#" class="topopup" id="1"><input type="button" name="btnAllocation" id="btnAllocation" value="<?php echo ALLOCATION;?>" class="btnAllocation" /></a></td>
        </tr>-->

       <!--<tr>
           <td><?php echo INVOICE;?></td>
           <td><label>
            <input name="txtInvoice" id="txtInvoice" type="text" value="<?php echo $this->txtInvoice;?>" class="textfield">
           </label></td>

           <td><?php echo INVOICEDATE;?></td>
           <td><label>
            <input name="txtInvDate" id="txtInvDate" type="text" value="<?php echo $this->txtInvDate;?>" class="textfield">
           </label></td>
       </tr>-->

     <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="5"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
      
       <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="5"><label>
              <?php $val_alloc=$this->common_model->get_field_name('BankBookDetails','sum(nAcAllocatedAmt)'," WHERE nBankbookId = '".$this->catId."'");
				if($val_alloc==0){?>
				<input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" /><?php }else{?>
				<input type="button" onclick="alert('Remove Allocation First');" name="" id="" value="<?php echo $this->btnVal;?>" />
				<?php }?>	
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOBANKBOOKENTRY;?>" id="btnCancel">
            </label>
                <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?>
            </td>
       </tr>
    </tbody>
  </table>

    <div id="toPopup1" class="toPopup">
        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup_content"> <!--your content start-->
        </div> <!--your content end-->
    </div>

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
