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

    function getTotalNet() {
        var txtTotal = 0;
        $('.txtNetAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalNetAmount').val(txtTotal);
    }//end function
	
    function getTotalServPercent() {
        var txtTotal = 0;
        $('.txtServPercent').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalServPercent').val(txtTotal);
    }//end function
	
    function getTotalServAmt() {
        var txtTotal = 0;
        $('.txtServAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalServAmount').val(txtTotal);
    }//end function

    function getTotalAgentAmt() {
        var txtTotal = 0;
        $('.txtAgentAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalAgentAmt').val(txtTotal);
    }//end function
	
    function getTotalPaidAmt() {
        var txtTotal = 0;
        $('.txtPaidAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalPaidAmt').val(txtTotal);
    }//end function

function changeTicket(id){
        var curVal = $('#ddlTktType'+id).val();
        var loc  = $('#ddlLocation').val();
        var div = $('#ddlDivision').val();
        var nVal = $('#ddlTkt' + id).val();
        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/refund_closing/getTktNumber/'.$this->uri->segment(3);?>',
            data:{ddlTktType:curVal,ddlLocation:loc,ddlDivision:div},
            dataType:'html',
            beforeSend: function() {
                $('#ddlTkt'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(responseo);
                $('#ddlTkt'+id).html(response);
                if(nVal != '')
                    $('#ddlTkt' + id).val(nVal);
            }
        });
}
function changeAllAccount(){
     var loc  = $('#ddlLocation').val();
     var div = $('#ddlDivision').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_closing/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlDrAccMain').val();
                 $('#ddlDrAccMain').html(response);
                 $('#ddlDrAccMain').val(acc);
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_closing/getAllAccount_main/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             $('.ddlDrAcc').each( function(){
                 var id = this.id;
                 var getId = id.split('ddlDrAcc');
                 var acc = $('#ddlDrAcc'+getId[1]).val();
                 $('#ddlDrAcc'+getId[1]).html(response);
                 $('#ddlDrAcc'+getId[1]).val(acc);
             });
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_closing/getAgentAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAgentAc').val();
                 $('#ddlAgentAc').html(response);
                 $('#ddlAgentAc').val(acc);
         }
     });

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_closing/getIssuedBy/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlIssued').val();
                 $('#ddlIssued').html(response);
                 $('#ddlIssued').val(issued);
         }
     });


}

    function getLocation(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/refund_closing/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(response)
                $('#loc').html(response);

                var nVal = $('#ddlType').val();
                var nVal2 = $('#ddlLocation').val();
                getDocumentNum(nVal, nVal2);
                changeAllAccount();
            }
        });
    }

        function getDocumentNum(nVal, nVal2) {
            $.ajax({
                 type:'POST',
                 data:{ddlType:nVal,ddlLocation:nVal2},
                 url:'<?php echo base_url().'index.php/refund_closing/getDocumentNumber/'.$this->uri->segment(3);?>',
                 dataType:'html',
                 beforeSend: function() {
                     $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                 },
                 success: function(response){
                     $('#txtDocNumber').val(response);
                 }
            })
        }
        $(document).ready(function()
             {
                 $("ul.pgcontent").quickPagination({pageSize:"5"});

                 $("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});

                 $('#btnCancel').click(function() {
                     window.location.replace('<?php echo base_url().'index.php/refund_closing/showlist/'.$this->uri->segment(3);?>');
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

                 $("#frmValidate").validate({
                     rules: {
                         ddlDivision: {
                             selectNone : true
                         },
                         ddlType: {
                             selectNone : true
                         },
                         'ddlTktType[]': {
                             selectNone : true
                         },
                         'ddlTkt[]': {
                             selectNone : true
                         }
                     },
                     messages: {
                         ddlDivision: {
                             selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                         },
                         ddlType: {
                             selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                         },
                         'ddlTktType[]': {
                             selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                         },
                         'ddlTkt[]': {
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
                     var id   = this.id;
                     var vali = $('#'+id).val();
                     var chki = 'txtLPO'+this.title;

                     if(id == chki)
                         var valDi = vali;
                     else
                         var valDi = '';

                     $(this).attr({
                         'id': function(_, id2) { return id + i; },
                         'value': valDi,
                         'title' : (this.title + i)
                     });
                 }).end().find("select").each(function() {
                     var id = this.id;
                     var val = $('#'+id).val();
                     var chk = 'ddlDrAcc'+this.title;

                     if(id == chk)
                         var valD = val;
                     else
                         var valD = '';

                     $(this).attr({
                         'id': function(_, id2) { return id + i; },
                         'value': valD,
                         'title' : (this.title + i)
                     });
                 }).end().insertAfter('#dataTable tbody>tr.re:last');
                 i++;

                 var rowCount = $("#dataTable").attr('rows').length;
                 m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
                 return false;
             });

             $('.del').live('click',function(){
                 var rowCount = $("#dataTable").attr('rows').length;
                 if((rowCount)>4)
                 {
                     $(this).parent().parent().remove();
                     //delete from the db
                     var dId = this.id;
                     dId     = dId.split('_');
                     //delete from db
                     $.ajax(
                     {
                         type:'POST',
                         url:'<?php echo base_url().'index.php/refund_closing/deleteRecord/'.$this->uri->segment(3);?>',
                         data:{subCode:dId[1]},
                         dataType:'html',
                         beforeSend: function() {
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

             $('#ddlDivision').change(function () {
                 var nVal = $('#ddlDivision').val();
                 getLocation(nVal);
                 $('.ddlTktType').each(function(){
                     var id     = this.id;
                     var nVal   = $('#'+id).val();
                     var getId  = id.split('ddlTktType');
                     changeTicket(getId[1]);
                     });

             });
             $('#loc').change(function(){
                     var nVal = $('#ddlType').val();
                     var nVal2 = $('#ddlLocation').val();
                     getDocumentNum(nVal, nVal2);
                     changeAllAccount();
                        $('.ddlTktType').each(function(){
                            var id     = this.id;
                            var nVal   = $('#'+id).val();
                            var getId  = id.split('ddlTktType');
                            changeTicket(getId[1]);
                        });

             });


            $('.ddlTktType').change(function(){
                    var id     = this.id;
                    var nVal   = $('#'+id).val();
                    var getId  = id.split('ddlTktType');
                    changeTicket(getId[1]);
            });

             $('#ddlType').change(function(){
                 var nVal = $('#ddlType').val();
                 var nVal2 = $('#ddlLocation').val();
                 getDocumentNum(nVal, nVal2);
             });

             $('.ddlTkt').change(function(){
                 var id      = this.id;
                 var nVal    = $('#'+id).val();
                 var getId   = id.split('ddlTkt');
                 var tktVal  = $('#ddlTkt'+getId[1]).val();
                 var typeVal = $('#ddlTktType'+getId[1]).val();
                 $.ajax({
                     type:'POST',
                     url:'<?php echo base_url().'index.php/refund_closing/getTktValues/'.$this->uri->segment(3);?>',
                     data:{ddlTkt:tktVal,ddlTktType:typeVal},
                     dataType:'html',
                     success: function(response) {
                         var obj = $.parseJSON(response);
                         $('#txtAirline'+getId[1]).val(obj.Airline);
                         $('#txtORG'+getId[1]).val(obj.OrgTkt);
                         $('#txtNetAmt'+getId[1]).val(obj.NetAmt);
                         $('#txtAgentAmt'+getId[1]).val(obj.agentAmt);
                         $('#txtPaidAmt'+getId[1]).val(obj.collected);
                         $('#ddlDrAcc'+getId[1]).val(obj.DrAcc);

                         $('#txtPassName').val(obj.PassName);
                         $('#txtSector1').val(obj.Sector1);
                         $('#txtSector2').val(obj.Sector2);
                         $('#txtSector3').val(obj.Sector3);
                         $('#txtSector4').val(obj.Sector4);
                         $('#txtSector5').val(obj.Sector5);
                         $('#txtSegment').val(obj.Segment);
                         $('#txtDestination').val(obj.Destination);
                         $('#txtGds').val(obj.Gds);

                         $('#txtCheckIn').val(obj.checkin);
                         $('#txtCheckOut').val(obj.checkout);
                         $('#txtNights').val(obj.nights);
                         $('#txtHotel').val(obj.hotel);
                         $('#txtCountry').val(obj.country);
                         $('#txtCity').val(obj.city);


                         var nVal = ($('#txtPaidAmt'+getId[1]).val() != '' ? $('#txtPaidAmt'+getId[1]).val() : 0);
                         var nVal2 = ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
                         var ServiceAmt = parseFloat(nVal2) - parseFloat(nVal);
                         var ServicePer = (parseFloat(ServiceAmt) / parseFloat(nVal2)) * 100;

                         $('#txtServPercent'+getId[1]).val(ServicePer.toFixed(3));
                         $('#txtServAmt'+getId[1]).val(ServiceAmt.toFixed(3));

                         getTotalNet();
                         getTotalServPercent();
                         getTotalServAmt();
                         getTotalPaidAmt();
                     }
                 });
             });

             <?php if($this->uri->segment(4) == 'e')
             {?>
                 $.ajax(
                     {
                         type:'POST',
                         data:{ddlCurrency:'<?php echo $this->ddlCurrency;?>'},
                         url:'<?php echo base_url().'index.php/refund_closing/generateCurrRate/'.$this->uri->segment(3);?>',
                         dataType:'html',
                         beforeSend: function() {
                             $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                         },
                         success: function(response) {
                             var obj = jQuery.parseJSON(response);
                             $('#txtRate').val(obj.rate);
                             $('#txtDecimal').val(obj.decimal);
                         }
                     });
                 changeAllAccount();
             <?php }//end if

             else {?>
                 getLocation($('#ddlDivision').val());
                 $.ajax(
                     {
                         type:'POST',
                         data:{ddlCurrency:'<?php echo $this->ddlCurrency;?>'},
                         url:'<?php echo base_url().'index.php/refund_closing/generateCurrRate/'.$this->uri->segment(3);?>',
                         dataType:'html',
                         beforeSend: function() {
                             $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                         },
                         success: function(response) {
                             var obj = jQuery.parseJSON(response);
                             $('#txtRate').val(obj.rate);
                             $('#txtDecimal').val(obj.decimal);
                         }
                     });
             <?php }//end else ?>


             $('#ddlCurrency').change(function()
             {
                 var nVal  = $('#ddlCurrency').val();
                 $.ajax(
                     {
                         type:'POST',
                         data:{ddlCurrency:nVal},
                         url:'<?php echo base_url().'index.php/refund_closing/generateCurrRate/'.$this->uri->segment(3);?>',
                         dataType:'html',
                         beforeSend: function() {
                             $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                         },
                         success: function(response) {
                             var obj = jQuery.parseJSON(response);
                             $('#txtRate').val(obj.rate);
                             $('#txtDecimal').val(obj.decimal);
                         }
                     });
             });

             $('.chkSelect').click(function(){
                 var id     = this.id;
                 var nVal   = $('#'+id).val();
                 var getId  = id.split('chkSelect');
                 if($('#chkSelect'+getId[1]).attr('checked')){
                     $('#chkSelect'+getId[1]).val(getId[1]);
                 }
                 else
                 {
                     $('#chkSelect'+getId[1]).val('0');
                 }
             });

             $('.txtNetAmt').keyup(function () {
                 getTotalNet();
             });

             $('.txtServPercent').keyup(function () {
                 getTotalServPercent();
             });

             $('.txtServAmt').keyup(function () {
                 getTotalServAmt();
             });

             $('.txtAgentAmt').keyup(function () {
                 getTotalAgentAmt();
             });

             $('.txtPaidAmt').keyup(function () {
                 getTotalPaidAmt();
             });

             $('.txtNetAmt').click(function () {
                 getTotalNet();
             });

             $('.txtServPercent').click(function () {
                 getTotalServPercent();
             });

             $('.txtServAmt').click(function () {
                 getTotalServAmt();
             });

             $('.txtAgentAmt').click(function () {
                 getTotalAgentAmt();
             });

             $('.txtPaidAmt').click(function () {
                 getTotalPaidAmt();
             });

             $('.ddlInvoice').change(function(){
                 var typeVal = $('#ddlInvoice').val();
                 if(typeVal == 'V'){
                     $("#txtTotalNetAmount").val('');
                     $("#txtTotalServPercent").val('');
                     $("#txtTotalServAmount").val('');
                     $("#txtTotalPaidAmount").val('');
                     $("#txtTotalAgentAmt").val('');
                     $(".ddlTktType").val('');
                     $(".ddlAirline").val('');
                     $(".ddlTkt").val('');
                     $(".txtNetAmt").val('');
                     $(".txtServPercent").val('');
                     $(".txtServAmt").val('');
                     $(".txtPaidAmt").val('');
                     $(".txtAgentAmt").val('');
                     $(".ddlDrAcc").val('');
                     $(".txtLPO1").val('');
                 }
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
<?php echo form_open('refund_closing/editRefundClosing/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>

<tr>
    <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
    </label></td>

    <td><?php echo LOCATIONNAME;?></td>
    <td><label>
    <span id="loc">
        <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
    </span>
    </label></td>
</tr>

<tr>
    <td colspan="10">
        <table>
            <tr>
                <td><?php echo CUSTDATE;?></td>
                <td><label>
                    <input name="txtDate" id="txtDate" class="textfield5" type="text" value="<?php echo $this->txtDate;?>">
                </label></td>

                <td><?php echo SALESTYPE;?></td>
                <td>
                  <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType, 'id="ddlType"');?>
                </td>

                <td><?php echo REFUNDNUMBER;?></td>
                <td><label>
                    <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                           value="<?php echo $this->txtDocNumber;?>" size="20">
                </label></td>

                <td><?php echo LPO;?></td>
                <td><label>
                    <input name="txtLpoMain" id="txtLpoMain" type="text" value="<?php echo $this->txtLpoMain;?>" class="pricebox">
                </label></td>
            </tr>
            <tr>

                <td><?php echo DRACCNAME;?></td>
                <td><label>
                    <?php echo form_dropdown('ddlDrAccMain', $list_dracc,$this->ddlDrAccMain, 'id="ddlDrAccMain" class="ddlDrAccMain"');?>
                </label></td>

                <td><?php echo CPV;?></td>
                <td><label>
                    <input name="txtCPV" id="txtCPV" type="text" value="<?php echo $this->txtCPV;?>" class="textfield5">
                </label></td>

                <td><?php echo NEWTKT;?></td>
                <td><label>
                    <input name="txtNewTkt" id="txtNewTkt" type="text" value="<?php echo $this->txtNewTkt;?>" class="textfield5">
                </label></td>

                <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlIssued', $list_employee_value, $this->ddlIssued,'id="ddlIssued"');?>
                </label></td>
            </tr>

            <tr>

                <td><?php echo SUBAGENTAC;?></td>
                <td>
                    <label><?php echo form_dropdown('ddlAgentAc', $list_agent_value, $this->ddlAgentAc, 'id="ddlAgentAc"');?></label>
                </td>
                <td><?php echo CURRNAME;?></td>
                <td><label>
                    <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');?>
                </label></td>

                <td><?php echo CURRRATE;?></td>
                <td colspan="3"><label>
                    <input name="txtRate" id="txtRate" class="textfield3" type="text" value="<?php echo $this->txtRate;?>">
                </label></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td colspan="10">
        <table id="dataTable" title="dataTable">
            <tr>
                <td colspan="18">
                    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
                </td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td><?php echo TYPE;?></td>
                <td><?php echo TICKETNAME;?></td>
                <td><?php echo AIRLINE;?></td>
                <td><?php echo ORIGINALTKT;?></td>
                <td><?php echo NETAMT;?></td>
                <td><?php echo SER;?></td>
                <td><?php echo SERAMT;?></td>
                <td><?php echo AGENTAMT;?></td>
                <td><?php echo PAIDAMT;?></td>
                <td><?php echo DRACCNAME;?></td>
                <td><?php echo LPO;?></td>
            </tr>

            <?php
            if(!empty($chkType))
            {
                foreach($chkType as $key => $aitem):
                    ?>
                    <tr class="re">
                        <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>

                        <td>
                            <?php echo form_dropdown('ddlTktType[]', $list_type, $chkType[$key], "id='ddlTktType".$key."' class='singleDrop ddlTktType'");?>
                        </td>

                        <td>
                            <?php echo form_dropdown('ddlTkt[]', $this->refund_closing_model->getClosedTicket($chkType[$key]), $chkTktNumber[$key],"id='ddlTkt".$key."' class='ddlTkt singleDrop'");?>
                        </td>

                        <td><label>
                            <input name="txtAirline[]" id="txtAirline<?php echo $key;?>" class="textfield6 txtAirline" type="text" value="<?php echo stripslashes($chkAirline[$key]);?>" readonly="readonly">
                        </label></td>

                        <td><label>
                            <input name="txtORG[]" id="txtORG<?php echo $key;?>" class="textfield6 txtORG" type="text" value="<?php echo stripslashes($chkORG[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtNetAmt[]" id="txtNetAmt<?php echo $key;?>" class="textfield5 txtNetAmt" type="text" value="<?php echo stripslashes($chkNetAmt[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtServPercent[]" id="txtServPercent<?php echo $key;?>" class="textfield3 txtServPercent currentvalue" type="text" value="<?php echo stripslashes($chkServPercent[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtServAmt[]" id="txtServAmt<?php echo $key;?>" class="textfield3 txtServAmt currentvalue" type="text" value="<?php echo stripslashes($chkServAmt[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtAgentAmt[]" id="txtAgentAmt<?php echo $key;?>" class="textfield6 txtAgentAmt currentvalue" type="text" value="<?php echo stripslashes($chkAgentAmt[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtPaidAmt[]" id="txtPaidAmt<?php echo $key;?>" class="textfield6 txtPaidAmt currentvalue" type="text" value="<?php echo stripslashes($chkPaidAmt[$key]);?>" title="<?php echo $key;?>">
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlDrAcc[]', $list_dracc,$chkDrAcc[$key], "id='ddlDrAcc".$key."' class='ddlDrAcc'");?>
                        </label></td>

                        <td><label>
                            <input name="txtLPO1[]" id="txtLPO1<?php echo $key;?>" class="textfield6 txtLPO1" type="text" value="<?php echo stripslashes($chkLPO[$key]);?>">
                        </label></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                    </tr>
                    <?php
                endforeach;
            } //end if
            else {
                ?>
                <tr class="re">
                    <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></td>

                    <td><label>
                        <?php echo form_dropdown('ddlTktType[]', $list_type, $this->ddlTktType, 'id="ddlTktType" class="singleDrop ddlTktType"');?>
                    </label></td>

                    <td>
                        <?php echo form_dropdown('ddlTkt[]', $list_tkt_value, $this->ddlTkt,'id="ddlTkt" class="ddlTkt singleDrop"');?>
                    </td>

                    <td><label>
                        <input name="txtAirline[]" id="txtAirline" class="textfield6 txtAirline" type="text" value="" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtORG[]" id="txtORG" class="textfield6 txtORG" type="text" value="">
                    </label></td>

                    <td><label>
                        <input name="txtNetAmt[]" id="txtNetAmt" class="textfield5 txtNetAmt" type="text" value="">
                    </label></td>

                    <td><label>
                        <input name="txtServPercent[]" id="txtServPercent" class="textfield3 txtServPercent currentvalue" type="text" value="">
                    </label></td>

                    <td><label>
                        <input name="txtServAmt[]" id="txtServAmt" class="textfield3 txtServAmt currentvalue" type="text" value="">
                    </label></td>

                    <td><label>
                        <input name="txtAgentAmt[]" id="txtAgentAmt" class="textfield6 txtAgentAmt currentvalue" type="text" value="">
                    </label></td>

                    <td><label>
                        <input name="txtPaidAmt[]" id="txtPaidAmt" class="textfield4 txtPaidAmt" type="text" value="">
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('ddlDrAcc[]', $list_dracc,$this->ddlDrAcc, 'id="ddlDrAcc" class="ddlDrAcc"');?>
                    </label></td>

                    <td><label>
                        <input name="txtLPO1[]" id="txtLPO1" class="textfield6 txtLPO1" type="text" value="">
                    </label></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_" title="Delete"/></td>
                </tr>
                <?php
            }//end else

			?>

                <tr>
                    <td colspan="4"></td>
                    <td><?php echo TOTALAMOUNT;?></td>
                    <td><input name="txtTotalNetAmount" class="textfield5 currentvalue" id="txtTotalNetAmount" type="text" value="<?php echo $this->txtTotalNetAmount;?>"></td>
                    <td><input name="txtTotalServPercent" class="textfield5 currentvalue" id="txtTotalServPercent" type="text" value="<?php echo $this->txtTotalServPercent;?>"></td>
                    <td><input name="txtTotalServAmount" class="textfield5 currentvalue" id="txtTotalServAmount" type="text" value="<?php echo $this->txtTotalServAmount;?>"></td>
                    <td><input name="txtTotalAgentAmt" class="textfield5 currentvalue" id="txtTotalAgentAmt" type="text" value="<?php echo $this->txtTotalAgentAmt;?>"></td>
                    <td><input name="txtTotalPaidAmt" class="textfield5 currentvalue" id="txtTotalPaidAmt" type="text" value="<?php echo $this->txtTotalPaidAmt;?>"></td>
                    <td>&nbsp;</td>
                </tr>

            </table>
    </td>
</tr>

<tr>
    <td><?php echo PASSENGER;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
        <input name="txtPassName" id="txtPassName" class="textfield txtPassName" type="text" value="<?php echo $this->txtPassName;?>">
    </td>

    <td><?php echo SECTOR;?></td>
    <td><label>
        <input name="txtSector1" id="txtSector1" class="textfield4 txtSector1" type="text" value="<?php echo $this->txtSector1;?>">
    </label></td>
    <td><label>
        <input name="txtSector2" id="txtSector2" class="textfield4 txtSector2" type="text" value="<?php echo $this->txtSector2;?>">
    </label></td>
    <td><label>
        <input name="txtSector3" id="txtSector3" class="textfield4 txtSector3" type="text" value="<?php echo $this->txtSector3;?>">
    </label></td>
    <td><label>
        <input name="txtSector4" id="txtSector4" class="textfield4 txtSector4" type="text" value="<?php echo $this->txtSector4;?>">
    </label></td>
    <td><label>
        <input name="txtSector5" id="txtSector5" class="textfield4 txtSector5" type="text" value="<?php echo $this->txtSector5;?>">
    </label></td>
</tr>
<tr>
    <td><?php echo SEGMENT;?></td>
    <td><label>
        <input name="txtSegment" id="txtSegment" class="textfield4 txtSegment" type="text" value="<?php echo $this->txtSegment;?>">
    </label></td>

    <td><?php echo DESTINATION;?></td>
    <td><label>
        <input name="txtDestination" id="txtDestination" class="textfield4 txtDestination" type="text" value="<?php echo $this->txtDestination;?>">
    </label></td>

    <td><?php echo GDS;?></td>
    <td><label>
        <input name="txtGds" id="txtGds" class="textfield4 txtGds" type="text" value="<?php echo $this->txtGds;?>">
    </label></td>

</tr>

<tr>
    <td colspan="12">
        <table>
            <tr>
                <td colspan="12"><span style="font-size: 12px;font-weight: bold;"><?php echo HOTELDETAILS;?></span></td>
            </tr>
            <tr>
                <td><?php echo CHECKIN;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <input name="txtCheckIn" id="txtCheckIn" class="textfield4" type="text" value="<?php echo $this->txtCheckIn;?>">
                </td>

                <td><?php echo CHECKOUT;?></td>
                <td><label>
                    <input name="txtCheckOut" id="txtCheckOut" class="textfield4" type="text" value="<?php echo $this->txtCheckOut;?>">
                </label></td>

                <td><?php echo NIGHTS;?></td>
                <td><label>
                    <input name="txtNights" id="txtNights" class="textfield4" type="text" value="<?php echo $this->txtNights;?>">
                </label></td>

                <td><?php echo HOTELNAME;?></td>
                <td><label>
                    <input name="txtHotel" id="txtHotel" class="textfield" type="text" value="<?php echo $this->txtHotel;?>">
                </label></td>

                <td><?php echo COUNTRYNAME;?></td>
                <td><label>
                    <input name="txtCountry" id="txtCountry" class="textfield" type="text" value="<?php echo $this->txtCountry;?>">
                </label></td>

                <td><?php echo CITYNAME;?></td>
                <td><label>
                    <input name="txtCity" id="txtCity" class="textfield" type="text" value="<?php echo $this->txtCity;?>">
                </label></td>

            </tr>
        </table>
    </td>
</tr>

<tr>
    <td colspan="10">
        <table>
            <tr>
                <td><?php echo INVOICESTATUS;?></td>
                <td><label>
                    <?php echo form_dropdown('ddlInvoice', $list_invoice_status, $this->ddlInvoice, 'id="ddlInvoice" class="singleDrop ddlInvoice"');?>
                </label></td>

                <td><?php echo INVOICEREMARKS;?></td>
                <td><label>
                    <textarea name="txtRemarks" id="txtRemarks" rows="3" cols="5" style="min-width: 250px;"><?php echo $this->txtRemarks;?></textarea>
                </label></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo ACTIVE;?></td>
    <td colspan="5"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input
        type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
</tr>

<tr>
    <td colspan="7"><a href="#" class="topopup" id="2"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></a></td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9"><label>
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
        <?php if($this->uri->segment(4) == 'a'){?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo BACKTOREFUNDCLOSING;?>" id="btnCancel">
    </label>
        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
</tr>
</tbody>
</table>

    <!-- Start of 2 Sticky Note -->
    <div id="toPopup2" class="toPopup"> 
        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup_content"> <!--your content start-->
        <?php echo $this->load->view('refund_closing/show_acdetails');?>
        </div> <!--your content end-->
    </div>
    <!-- End of Sticky Note -->

</form>
</div>
