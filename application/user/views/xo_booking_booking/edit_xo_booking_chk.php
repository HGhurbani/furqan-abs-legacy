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
        window.location.replace('<?php echo base_url().'index.php/xo_booking/showlist/'.$this->uri->segment(3);?>');
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
    $("#txtTravelDate").datepick({ dateFormat: 'yyyy-mm-dd'});
    $(".txtTravelDate").datepick({ dateFormat: 'yyyy-mm-dd'});

    // validate signup form on keyup and submit
    $("#frmValidate").validate({
        rules: {
            ddlDivision: {
                selectNone : true
            },
            ddlLocation: {
                selectNone : true
            },
            ddlTrans: {
                selectNone : true
            },
            ddlAirline: {
                selectNone : true
            },
            ddlGDS: {
                selectNone : true
            },
            ddlBooked: {
                selectNone : true
            },
            ddlIssued: {
                selectNone : true
            },
            ddlPassenger: {
                selectNone : true
            }
        },
        messages: {
            ddlDivision: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlLocation: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlTrans: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlAirline: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlGDS: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlBooked: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlIssued: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlPassenger: {
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
                    url:'<?php echo base_url().'index.php/xo_booking/deleteRecord/'.$this->uri->segment(3);?>',
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

    $('#ddlTrans').change(function()
    {
        var nVal  = $('#ddlTrans').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlTrans:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/generateDocNo/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#txtDocNumber').val(response);
                }
            });
    });

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlDivision:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                }
            });
    });

    $('#ddlCurrency').change(function()
    {
        var nVal  = $('#ddlCurrency').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCurrency:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/generateCurrRate/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#txtRate').val(response);
                }
            });
    });

    var i = 1;
    $("#addRow2").click(function() {
        var m	      = $('#dataControl tbody>tr:last').clone(true).find("input").each(function() {
            var id    = this.id;
            var cname = $('#'+id).attr('data');
            if(cname == 'txtTotalTax')
            {
                var valChk = id.split('txtTotalTax');
                valChk     = valChk[1];
                valChk     = valChk+i;
            }//end if

            if(cname == 'txtNetVendor')
            {
                var valChk = id.split('txtNetVendor');
                valChk     = valChk[1];
                valChk     = valChk+i;
            }//end if

            $(this).attr({
                'id': function(_, id2) { return id + i; },
                'value': '',
                'title':valChk
            });
        }).end().clone(true).find("select").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
            }).end().insertAfter('#dataControl tbody>tr:last');
        i++;

        var rowCount = $("#dataControl").attr('rows').length;
        m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
        return false;
    });

    $('.del2').live('click',function(){
        var rowCount = $("#dataControl").attr('rows').length;
        if((rowCount)>1)
        {
            $(this).parent().parent().remove();
            var dId = this.id;
            dId     = dId.split('_');
            //delete from db
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/ticket_booking/deleteConjuctionRecord/'.$this->uri->segment(3);?>',
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
            $('#del2_'+rowCount).attr('disabled', true);
        }//end if
    });

    $('#txtSegment').click(function(e)
    {
        var rowCount = $("#dataControl").attr('rows').length;
        $('#txtSegment').val(rowCount);
    });

    $('.chkTotal').click(function(e)
    {
        var id      = this.title;
        var nVal1   = $('#txtFc1Tax'+id).val();
        var nVal2   = $('#txtFc2Tax'+id).val();
        var nVal3   = $('#txtFc3Tax'+id).val();
        var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
        $('#txtTotalTax'+id).val(nTotal);
    });

    $('.chkNet').click(function()
    {
        var id      = this.title;
        var nVal      = $('#txtTotalTax'+id).val();
        var nVal1     = $('#txtFcTax'+id).val();
        var netTotal  = parseFloat(nVal)+parseFloat(nVal1);
        $('#txtNetVendor'+id).val(netTotal);
    });

    $('.txtFcTax').keyup(function()
    {
        var txtTotal = 0;
        $('.txtFcTax').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtFareTotalAmount').val(txtTotal);
    });

    $('.txtFc1Tax').keyup(function()
    {
        var txtTotal = 0;
        $('.txtFc1Tax').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTax1TotalAmount').val(txtTotal);
    });

    $('.txtFc2Tax').keyup(function()
    {
        var txtTotal = 0;
        $('.txtFc2Tax').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTax2TotalAmount').val(txtTotal);
    });

    $('.txtFc3Tax').keyup(function()
    {
        var txtTotal = 0;
        $('.txtFc3Tax').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTax3TotalAmount').val(txtTotal);
    });

    $('.txtTotalTax').click(function()
    {
        var txtTotal = 0;
        $('.txtTotalTax').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalTaxAmount').val(txtTotal);
    });

    $('.txtNetVendor').click(function()
    {
        var txtTotal = 0;
        $('.txtNetVendor').each(function()
        { //Changed to class selector
            var nVal2 = $(this).val();

            if(nVal2 == '')
            {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalNetVendorAmount').val(txtTotal);
    });

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
<?php echo form_open('xo_booking/editXobooking/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nCId', $this->catId);?>
<?php echo form_hidden('nCheck', 'frmPost');?>
<?php echo form_hidden('idArray', $chkAccID);?>
<?php echo form_hidden('idHCArray', $chkAccID2);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>

<tr>
    <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
    </label></td>

    <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
    <td><label><span id="loc">
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
            </span></label></td>

    <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtDate" id="txtDate" class="textfield3" type="text" value="<?php echo date('Y-m-d');?>">
    </label></td>
</tr>

<tr>
    <td><?php echo TRANSNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlTrans', $list_trans_value, $this->ddlTrans,'id="ddlTrans"');?>
    </label></td>

    <td><?php echo DOCNUMBER;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtDocNumber" id="txtDocNumber" class="textfield" type="text" value="<?php echo $this->txtDocNumber;?>" size="20">
    </label></td>

    <td><?php echo TYPE;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType"');?>
    </label></td>
</tr>

<tr>
    <td><?php echo AIRLINENO;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline,'id="ddlAirline"');?>
    </label></td>

    <td><?php echo TICKETNAME;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtXoName" id="txtXoName" class="textfield" type="text" value="<?php echo stripslashes($this->txtXoName);?>" size="40">
    </label></td>

    <td><?php echo GDS;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS,'id="ddlGDS"');?>
    </label></td>
</tr>

<tr>
    <td><?php echo BOOKEDBY;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlBooked', $list_emp_value, $this->ddlBooked,'id="ddlBooked"');?>
    </label></td>

    <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued"');?>
    </label></td>

    <td><?php echo PASSENGER;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlPassenger1', $list_pass, $this->ddlPassenger1,'id="ddlPassenger1"');?>
    </label></td>
</tr>

<tr>
    <td><?php echo ORIGINALTKT;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtOriginalXo" id="txtOriginalXo" class="textfield3" type="text" value="<?php echo stripslashes($this->txtOriginalXo);?>">
    </label></td>

    <td><?php echo SEGMENT;?></td>
    <td><label>
        <input name="txtSegment" id="txtSegment" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSegment);?>">
    </label></td>

    <td><?php echo DESTINATION;?> <span class="warning">*</span></td>
    <td colspan="3"><label>
        <input name="txtDestination" id="txtDestination" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDestination);?>">
    </label></td>
</tr>

<tr>
    <td><?php echo GDSPNR;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtGdsPNR" id="txtGdsPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGdsPNR);?>">
    </label></td>

    <td><?php echo AIRPNR;?> <span class="warning">*</span></td>
    <td colspan="3"><label>
        <input name="txtAirPNR" id="txtAirPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAirPNR);?>">
    </label></td>
</tr>

<tr>
    <td><?php echo CURRENCY;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');?>
    </label></td>

    <td><?php echo CURRRATE;?> <span class="warning">*</span></td>
    <td colspan="3"><label>
        <input name="txtRate" id="txtRate" class="textfield" type="text" value="<?php echo $this->txtRate;?>" size="20">
    </label></td>
</tr>

<tr>
    <td colspan="7">
        <div class="release_listcontainer">
            <span style="font-size: 12px;font-weight: bold;"><?php echo TICKETDETAILS ?></span>
            <div id="result">
                <span class="clears"></span>
                <div class="listcontnts">
                    <table id="dataTable" title="dataTable">
                        <tr>
                            <td><?php echo SRNO;?></td>
                            <td><?php echo CARRIER;?></td>
                            <td><?php echo FLIGHTNO;?></td>
                            <td><?php echo TKTCLASS;?></td>
                            <td><?php echo TRAVELDATE;?></td>
                            <td><?php echo SECTOR;?></td>
                            <td>&nbsp;</td>
                            <td><?php echo STATUS;?></td>
                            <td><?php echo TIMEDEP;?></td>
                            <td><?php echo TIMEARR;?></td>
                            <td colspan="8"><?php echo DAY;?></td>
                        </tr>
                        <?php
                        if(!empty($txtCarrier))
                        {
                            foreach($txtCarrier as $key => $aitem):
                                ?>
                                <tr>
                                    <td><?php echo $key;?></td>
                                    <td><input name="txtCarrier" id="txtCarrier" class="textfield4" type="text" value="<?php echo stripslashes($aitem);?>"></td>
                                    <td><input name="txtFlightNo" id="txtFlightNo" class="textfield4" type="text" value="<?php echo stripslashes($txtFlightNo[$key]);?>"></td>
                                    <td><input name="txtClass" id="txtClass" class="textfield4" type="text" value="<?php echo stripslashes($txtClass[$key]);?>"></td>
                                    <td><input name="txtTravelDate" id="txtTravelDate" class="textfield4" type="text" value="<?php echo stripslashes($txtTravelDate[$key]);?>"></td>
                                    <td><input name="txtSector" id="txtSector" class="textfield4" type="text" value="<?php echo stripslashes($txtSector[$key]);?>"></td>
                                    <td><input name="txtSector2" id="txtSector2" class="textfield4" type="text" value="<?php echo stripslashes($txtSector2[$key]);?>"></td>
                                    <td><input name="txtStatus" id="txtStatus" class="textfield4" type="text" value="<?php echo stripslashes($txtStatus[$key]);?>"></td>
                                    <td><input name="txtTimeDep" id="txtTimeDep" class="textfield4" type="text" value="<?php echo stripslashes($txtTimeDep[$key]);?>"></td>
                                    <td><input name="txtTimeArr" id="txtTimeArr" class="textfield4" type="text" value="<?php echo stripslashes($txtTimeArr[$key]);?>"></td>
                                    <td><input name="txtDay" id="txtDay" class="textfield4" type="text" value="<?php echo stripslashes($txtDay[$key]);?>"></td>
                                </tr>
                                <?php
                            endforeach;
                        }//end if
                        else
                        {
                            $i = 0;
                            while ($i < 4){?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
                                    <td><input name="txtCarrier[]" id="txtCarrier" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtFlightNo[]" id="txtFlightNo" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtClass[]" id="txtClass" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtTravelDate[]" id="txtTravelDate" class="textfield24 txtTravelDate" type="text" value=""></td>
                                    <td><input name="txtSector[]" id="txtSector" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtSector2[]" id="txtSector2" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtStatus[]" id="txtStatus" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtTimeDep[]" id="txtTimeDep" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtTimeArr[]" id="txtTimeArr" class="textfield4" type="text" value=""></td>
                                    <td><input name="txtDay[]" id="txtDay" class="textfield4" type="text" value=""></td>
                                </tr>
                                <?php $i++;
                            }
                        }//end else
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </td>
</tr>

<tr>
    <td colspan="7">
        <div>
            <span style="font-size: 12px;font-weight: bold;"><?php echo BILLINGDETAILS ?></span>
            <span class="clears"></span>
            <div class="listcontnts">
                <table>
                    <tr>
                        <td><?php echo INVOICE;?></td>
                        <td><?php echo MODEOFPAYMENT;?></td>
                        <td><?php echo CUSTNAME;?></td>
                        <td colspan="8"><?php echo AGENTNAME;?></td>
                    </tr>

                    <tr>
                        <td>
                            <label><input name="txtInvoice" id="txtInvoice" class="textfield5" type="text" value="<?php echo stripslashes($this->txtInvoice);?>"></label>
                        </td>
                        <td>
                            <label><?php echo form_dropdown('ddlMode', $list_mode_value, $this->ddlMode,'id="ddlMode" class="ddlSearch"');?></label>
                        </td>
                        <td>
                            <label><?php echo form_dropdown('ddlCustomer', $list_cust_value, $this->ddlCustomer,'id="ddlCustomer"');?></label>
                        </td>

                        <td colspan="8">
                            <label><?php echo form_dropdown('ddlAgent', $list_agent_value, $this->ddlAgent,'id="ddlAgent"');?></label>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo AGENTAC;?></td>
                        <td><label><?php echo form_dropdown('ddlAgentAc', $list_agentacc_value, $this->ddlAgentAc,'id="ddlAgentAc"');?></label></td>

                        <td><?php echo AGENTAMT;?></td>
                        <td><label><input name="txtAgentAmt" id="txtAgentAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAgentAmt);?>"></label></td>

                        <td><?php echo LPO;?></td>
                        <td colspan="8"><label><input name="txtLPO" id="txtLPO" class="textfield5" type="text" value="<?php echo stripslashes($this->txtLPO);?>"></label></td>
                    </tr>

                    <tr>
                        <td><?php echo SALESAMT;?></td>
                        <td><?php echo DISCOUNT;?></td>
                        <td><?php echo DISCOUNTAMT;?></td>
                        <td><?php echo COLECTED;?></td>
                        <td><?php echo SERVICEPERCENT;?></td>
                        <td colspan="8"><?php echo SERVICEAMT;?></td>
                    </tr>

                    <tr>
                        <td><label>
                            <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSalesAmt);?>">
                        </label></td>

                        <td><label>
                            <input name="txtDiscount" id="txtDiscount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDiscount);?>">
                        </label></td>

                        <td><label>
                            <input name="txtDiscountAmt" id="txtDiscountAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDiscountAmt);?>">
                        </label></td>

                        <td><label>
                            <input name="txtCollected" id="txtCollected" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCollected);?>">
                        </label></td>

                        <td><label>
                            <input name="txtServicePerc" id="txtServicePerc" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServicePerc);?>">
                        </label></td>

                        <td><label>
                            <input name="txtServiceAmt" id="txtServiceAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServiceAmt);?>">
                        </label></td>
                    </tr>
                </table>
            </div>
        </div>
    </td>
</tr>
<tr>
    <td colspan="8">
        <span style="font-size: 12px;font-weight: bold;"><?php echo PASSDETAILS ?></span>
            <table>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td><?php echo ACCCODENAME;?></td>
                                <td><label>
                                    <?php echo form_dropdown('ddlAccount', $list_account_value, $this->ddlAccount,'id="ddlAccount"');?>
                                </label></td>

                                <td><?php echo REMARKS;?> <span class="warning">*</span></td>
                                <td><label>
                                    <textarea name="txtRemarks" id="txtRemarks"><?php echo stripslashes($this->txtRemarks);?></textarea>
                                </label></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table id="dataControl" title="dataControl">
                            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow2" class="addNew" />
                            <tr>
                                <td><?php echo SRNO;?></td>
                                <td><?php echo PASSENGER;?></td>
                                <td><?php echo FARE?></td>
                                <td><?php echo COMM;?></td>
                                <td><?php echo COMMAMT;?></td>
                                <td><?php echo FCTAX;?></td>
                                <td><?php echo FCTAX;?></td>
                                <td><?php echo FCTAX;?></td>
                                <td><?php echo TOTALTAX;?></td>
                                <td><?php echo NETFARE;?></td>
                            </tr>
                            <?php
                            if(!empty($txtFcTax))
                            {
                                foreach($txtFcTax as $key => $pitem):
                                    ?>
                                    <tr>
                                        <td><?php echo $key;?></td>

                                        <td><label>
                                            <?php echo form_dropdown('ddlPassenger[]', $list_pass_value, $this->ddlPassenger,'id="ddlPassenger"');?>
                                        </label></td>

                                        <td><label>
                                            <input name="txtFcTax[]" id="txtFcTax" class="textfield5 txtFcTax" type="text" value="<?php echo stripslashes($pitem);?>">
                                        </label></td>

                                        <td><label>
                                            <input name="txtCommTax[]" id="txtCommTax" class="textfield5" type="text" value="<?php echo stripslashes($txtCommTax[$key]);?>">
                                        </label></td>

                                        <td><label>
                                            <input name="txtORCAmt[]" id="txtORCAmt" class="textfield5" type="text" value="<?php echo stripslashes($txtORCAmt[$key]);?>">
                                        </label></td>

                                        <td><label>
                                            <input name="txtFc1Tax[]" id="txtFc1Tax" class="textfield5 txtFc1Tax" type="text" value="<?php echo stripslashes($txtFc1Tax[$key]);?>">
                                        </label></td>

                                        <td><label>
                                            <input name="txtFc2Tax[]" id="txtFc2Tax" class="textfield5 txtFc2Tax" type="text" value="<?php echo stripslashes($txtFc2Tax[$key]);?>">
                                        </label></td>

                                        <td><label>
                                            <input name="txtFc3Tax[]" id="txtFc3Tax" class="chkTax txtFc3Tax" type="text" value="<?php echo stripslashes($txtFc3Tax[$key]);?>" title="">
                                        </label></td>

                                        <td><label>
                                            <input name="txtTotalTax[]" id="txtTotalTax" class="chkTotal txtTotalTax" type="text" value="<?php echo stripslashes($txtTotalTax[$key]);?>" title="" data="txtTotalTax">
                                        </label></td>

                                        <td><label>
                                            <input name="txtNetVendor[]" id="txtNetVendor" class="textfield5 txtNetVendor chkNet"  title="" data="txtNetVendor" type="text" value="<?php echo stripslashes($txtNetVendor[$key]);?>">
                                        </label></td>

                                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $idCnt;?>" title="Delete"/></td>
                                    </tr>
                                    <?php
                                endforeach;
                            }//end if
                            else
                            {?>
                                <tr>
                                    <td>#</td>

                                    <td><label>
                                        <?php echo form_dropdown('ddlPassenger[]', $list_pass_value, $this->ddlPassenger,'id="ddlPassenger"');?>
                                    </label></td>

                                    <td><label>
                                        <input name="txtFcTax[]" id="txtFcTax" class="textfield5 txtFcTax" type="text" value="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtCommTax[]" id="txtCommTax" class="textfield5" type="text" value="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtORCAmt[]" id="txtORCAmt" class="textfield5" type="text" value="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtFc1Tax[]" id="txtFc1Tax" class="textfield5 txtFc1Tax" type="text" value="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtFc2Tax[]" id="txtFc2Tax" class="textfield5 txtFc2Tax" type="text" value="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtFc3Tax[]" id="txtFc3Tax" class="chkTax txtFc3Tax" type="text" value="" title="">
                                    </label></td>

                                    <td><label>
                                        <input name="txtTotalTax[]" id="txtTotalTax" class="chkTotal txtTotalTax" type="text" value="" title="" data="txtTotalTax">
                                    </label></td>

                                    <td><label>
                                        <input name="txtNetVendor[]" id="txtNetVendor" class="textfield5 txtNetVendor chkNet"  title="" data="txtNetVendor" type="text" value="">
                                    </label></td>

                                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $idCnt;?>" title="Delete"/></td>
                                </tr>
                                <?php
                            }//end else
                            ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <table>
                        <tr>
                            <td style="width:100px;"><?php echo GRANDTOTAL;?></td>
                            <td><input name="txtFareTotalAmount" style="margin-left:145px;" class="textfield5" id="txtFareTotalAmount" type="text" value="<?php echo $this->txtFareTotalAmount;?>"></td>
                            <td><input name="txtTax1TotalAmount" style="margin-left:175px;" class="textfield5" id="txtTax1TotalAmount" type="text" value="<?php echo $this->txtTax1TotalAmount;?>"></td>
                            <td><input name="txtTax2TotalAmount" class="textfield5" id="txtTax2TotalAmount" type="text" value="<?php echo $this->txtTax2TotalAmount;?>"></td>
                            <td><input name="txtTax3TotalAmount" class="textfield5" id="txtTax3TotalAmount" type="text" value="<?php echo $this->txtTax3TotalAmount;?>"></td>
                            <td><input name="txtTotalTaxAmount" class="textfield5" id="txtTotalTaxAmount" type="text" value="<?php echo $this->txtTotalTaxAmount;?>"></td>
                            <td><input name="txtTotalNetVendorAmount" class="textfield5" id="txtTotalNetVendorAmount" type="text" value="<?php echo $this->txtTotalNetVendorAmount;?>"></td>
                        </tr>
                    </table>
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
        <input type="button" name="btnCancel" value="<?php echo BACKTOXOBOOKING;?>" id="btnCancel">
    </label></td>
</tr>
</tbody>
</table>
</form>
</div>