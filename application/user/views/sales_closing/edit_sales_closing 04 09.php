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
// +----------------------------------------------------------------------
?>
<script language="javascript1.1" type="text/javascript">

    $.validator.setDefaults({
        submitHandler: function() {}
    });

    function getTotalFace()
    {
        var txtTotal = 0;
        $('.txtFare').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalFace').val(txtTotal);
    }//end function

    function getTotalTax()
    {
        var txtTotal = 0;
        $('.txtTax').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalTax').val(txtTotal);
    }//end function

    function getTotalNet()
    {
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

    function getTotalServPercent()
    {
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

    function getTotalServAmt()
    {
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

    function getTotalDiscAmt()
    {
        var txtTotal = 0;
        $('.txtDiscAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalDiscAmount').val(txtTotal);
    }//end function

    function getTotalComm()
    {
        var txtTotal = 0;
        $('.txtComm').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalCommPercent').val(txtTotal);
    }//end function

    function getTotalCommAmt()
    {
        var txtTotal = 0;
        $('.txtCommAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalCommAmt').val(txtTotal);
    }//end function

    function getTotalSalesAmt()
    {
        var txtTotal = 0;
        $('.txtSalesAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalSalesAmt').val(txtTotal);
    }//end function

    function getTotalCollected()
    {
        var txtTotal = 0;
        $('.txtCollected').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTotalCollected').val(txtTotal);
		$('#txtAmt').val($('#txtTotalCollected').val());		
    }//end function

    function getTotalAgentAmt()
    {
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

    function getLocation(nVal) {
        $.ajax({
             type:'POST',
             data:{ddlDivision:nVal},
             url:'<?php echo base_url().'index.php/sales_closing/filterDivision/'.$this->uri->segment(3);?>',
             dataType:'html',
             success: function(response) {
                 $('#loc').html(response);
             }
        });
    }

    $(document).ready(function()
    {
         $('#btnPrint').click(function(e) {
         var html = '<table><tr><td></td></tr><tr><td>';
         html += $('.reportForm').html();
         html += '</td></tr></table>';

         window.print();
         return false;
         });

         $("ul.pgcontent").quickPagination({pageSize:"5"});

         $('#btnCancel').click(function() {
         <?php if($this->uri->segment(5) == 'unclosed')
         {?>
             window.location.replace('<?php echo base_url().'index.php/sales_closing/showUnclosedTicket/'.$this->uri->segment(3);?>');
             <?php }//end if
         else {?>
             window.location.replace('<?php echo base_url().'index.php/sales_closing/showlist/'.$this->uri->segment(3);?>');
             <?php }//end else?>
             return false;
         });

         $("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});
         $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
         $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});

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
                 'ddlDrAccMain[]': {
                     selectNone : true
                 },
                 ddlLocation: {
                     selectNone : true
                 },
                 txtDocNumber: {
                     required : true
                 }
             },
             messages: {
                 ddlDivision: {
                     selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                 },
                 'ddlDrAccMain[]': {
                     selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                 },
                 ddlLocation: {
                     selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                 },
                 txtDocNumber: {
                     required : "Document Number can't be blank"
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
				
				if(id.indexOf("txtSalesAmt") != -1 || id.indexOf("txtDiscAmt") != -1 ){
					$(this).attr('tabindex',tabindex_val);
					tabindex_val ++;
				}
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': valDi,
                    'title' : (this.title + i)
                });
            }).end().find("select").each(function() {
                    var id  = this.id;
                    var val = $('#'+id).val();

                    /*var typeVal = $('#ddlTktType'+i).val();
                    var tktVal  = $('#ddlTkt'+i).val();

                    chkTktArray(tktVal,typeVal,2);*/

                    $(this).attr({
                        'id': function(_, id2) { return id + i; },
                        'value': 0
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
                    $.ajax({
                        type:'POST',
                        url:'<?php echo base_url().'index.php/sales_closing/deleteRecord/'.$this->uri->segment(3);?>',
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

            var j = 1;
            $("#addRow2").click(function() {
             var m	 = $('#dataTable2 tbody>tr:last').clone(true).find("input").each(function() {
                 var id   = this.id;
                 var vali = $('#'+id).val();
                 var chki = 'txtCRV'+this.title;

                 if(id == chki)
                     var valDi = vali;
                 else
                     var valDi = '';

                 $(this).attr({
                     'id': function(_, id2) { return id + j; },
                     'value': valDi,
                     'title' : (this.title + j)
                 });
             }).end().find("select").each(function() {
                     var id = this.id;
                     var val = $('#'+id).val();
                     var chk = 'ddlDrAccMain'+this.title;

                     if(id == chk)
                         var valD = val;
                     else
                         var valD = '';

                     $(this).attr({
                         'id': function(_, id2) { return id + j; },
                         'value': valD,
                         'title' : (this.title + j)
                     });
                 }).end().insertAfter('#dataTable2 tbody>tr:last');
             j++;

             var rowCount = $("#dataTable2").attr('rows').length;
             m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
             return false;
            });

            $('.del2').live('click',function(){
             var rowCount = $("#dataTable2").attr('rows').length;
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
                         data:{subCode:dId[1]},
                         url:'<?php echo base_url().'index.php/sales_closing/deleteAccountRecord/'.$this->uri->segment(3);?>',
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

            $('.ddlTktType').change(function(){
             var id     = this.id;
             var nVal   = $('#'+id).val();
             var getId  = id.split('ddlTktType');
             var curVal = $('#ddlTktType'+getId[1]).val();
             $.ajax({
                 type:'POST',
                 url:'<?php echo base_url().'index.php/sales_closing/getTktNumber/'.$this->uri->segment(3);?>',
                 data:{ddlTktType:curVal},
                 dataType:'html',
                 success: function(response) {
                     $('#ddlTkt'+getId[1]).html(response);
                 }
             });
            });

            function getAccountHead(nVal,id)
            {
                 var accId  = nVal.split("-");
                 $.ajax({
                     type:'POST',
                     data:{ddlDrAccMain:accId[0],tableType:accId[1]},
                     url:'<?php echo base_url().'index.php/sales_closing/getAccountHead/'.$this->uri->segment(3);?>',
                     dataType:'html',
                     success: function(response) {
                         $('#ddlAcHead'+id).html(response);
                     }
                 });

                 $.ajax({
                     type:'POST',
                     data:{ddlDrAccMain:accId[0],tableType:accId[1]},
                     url:'<?php echo base_url().'index.php/sales_closing/getCRV/'.$this->uri->segment(3);?>',
                     dataType:'html',
                     success: function(response) {
                         $('#txtCRV'+id).html(response);
                     }
                 });

                 $.ajax({
                     type:'POST',
                     data:{ddlDrAccMain:accId[0],tableType:accId[1]},
                     url:'<?php echo base_url().'index.php/sales_closing/getRefund/'.$this->uri->segment(3);?>',
                     dataType:'html',
                     success: function(response) {
                         $('#txtRefTkt'+id).html(response);
                     }
                 });

                 if(accId[0]>0)
                 {
                     $.ajax({
                         type:'POST',
                         data:{ddlDrAccMain:accId[0],tableType:accId[1]},
                         url:'<?php echo base_url().'index.php/sales_closing/getInvoiceTo/'.$this->uri->segment(3);?>',
                         dataType:'html',
                         success: function(response) {
                             $('#txtInvoiceTo').val(response);
                         }
                     });
                 }
            }

            $("#txtLpoMain").keyup(function(){
             var lpoVal = $("#txtLpoMain").val();
             $('.txtLPO').val(lpoVal);
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

            $('.txtFare').keyup(function () {
             getTotalFace();
            });

            $('.txtTax').keyup(function () {
             getTotalTax();
            });

            $('.txtNetAmt').keyup(function () {

             var id       = this.id;
             var getId    = id.split('txtNetAmt');
             var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
             var servAmt  	= ($('#txtServAmt' + getId[1]).val() != '' ? $('#txtServAmt'+getId[1]).val() : 0);
             var salesAmt    = parseFloat(servAmt)+ parseFloat(netAmt);
             $('#txtSalesAmt'+getId[1]).val(salesAmt);
             getTotalSalesAmt();
             getTotalNet();
            });

            $('.txtServPercent').keyup(function () {
             getTotalServPercent();
            });

            $('.txtServAmt').keyup(function () {
             var id          = this.id;
             var getId       = id.split('txtServAmt');
             var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
             var servAmt  	= ($('#txtServAmt' + getId[1]).val() != '' ? $('#txtServAmt'+getId[1]).val() : 0);
             var salesAmt    = parseFloat(servAmt)+ parseFloat(netAmt);
             $('#txtSalesAmt'+getId[1]).val(salesAmt);
             getTotalSalesAmt();
             getTotalServAmt();
            });

            $('.txtDiscAmt').keyup(function () {

             var id       = this.id;
             var getId    = id.split('txtDiscAmt');
             var salesAmt = ($('#txtSalesAmt' + getId[1]).val() != '' ? $('#txtSalesAmt'+getId[1]).val() : 0);
             var disAmt	 = ($('#txtDiscAmt'+getId[1]).val() != '' ? $('#txtDiscAmt'+getId[1]).val() : 0);
             var collAmt  = (parseFloat(salesAmt)-parseFloat(disAmt));
             $('#txtCollected'+getId[1]).val(collAmt);

             var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
             var collAmt2  	= ($('#txtCollected' + getId[1]).val() != '' ? $('#txtCollected'+getId[1]).val() : 0);
             var agentAmt 	= ($('#txtAgentAmt' + getId[1]).val() != '' ? $('#txtAgentAmt'+getId[1]).val() : 0);
             var commAmt     = ($('#txtCommAmt' + getId[1]).val() != '' ? $('#txtCommAmt' + getId[1]).val() : 0);
             var servAmt     = ((parseFloat(collAmt2)- parseFloat(netAmt)) - parseFloat(commAmt) - parseFloat(agentAmt));
             $('#txtServAmt'+getId[1]).val(servAmt);

             var servAmt2    = ($('#txtServAmt'+getId[1]).val() != '' ? $('#txtServAmt'+getId[1]).val() : 0);
             if(servAmt2 == 0){
                 $('#txtServPercent'+getId[1]).val(servAmt2);
             }//end if
             else if(netAmt == 0){
                 $('#txtServPercent'+getId[1]).val(netAmt);
             }//end else if
             else
             {
                 var servPercent = ((parseFloat(servAmt2)/parseFloat(netAmt))*100);
                 $('#txtServPercent'+getId[1]).val(servPercent);
             }//end else

             getTotalServPercent();
             getTotalServAmt();
             getTotalSalesAmt();
             getTotalCollected();
             getTotalDiscAmt();
            });

            $('.txtComm').keyup(function () {
             getTotalComm();
            });

            $('.txtCommAmt').keyup(function () {
             getTotalCommAmt();
            });

            $('.txtSalesAmt').keyup(function () {
                var id       = this.id;
                var getId    = id.split('txtSalesAmt');
                var salesAmt = ($('#txtSalesAmt' + getId[1]).val() != '' ? $('#txtSalesAmt'+getId[1]).val() : 0);
                var disAmt	 = ($('#txtDiscAmt'+getId[1]).val() != '' ? $('#txtDiscAmt'+getId[1]).val() : 0);
                var collAmt  = (parseFloat(salesAmt)-parseFloat(disAmt));
                $('#txtCollected'+getId[1]).val(collAmt);

                var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
                var collAmt2  	= ($('#txtCollected' + getId[1]).val() != '' ? $('#txtCollected'+getId[1]).val() : 0);
                var agentAmt 	= ($('#txtAgentAmt' + getId[1]).val() != '' ? $('#txtAgentAmt'+getId[1]).val() : 0);
                var commAmt     = ($('#txtCommAmt' + getId[1]).val() != '' ? $('#txtCommAmt' + getId[1]).val() : 0);
                var servAmt     = ((parseFloat(collAmt2)- parseFloat(netAmt)) - parseFloat(commAmt) - parseFloat(agentAmt));
                $('#txtServAmt'+getId[1]).val(servAmt);

                var servAmt2     = ($('#txtServAmt'+getId[1]).val() != '' ? $('#txtServAmt'+getId[1]).val() : 0);
                if(servAmt2 == 0){
                    $('#txtServPercent'+getId[1]).val(servAmt2);
                }//end if
                else if(netAmt == 0){
                    $('#txtServPercent'+getId[1]).val(netAmt);
                }//end else if
                else
                {
                    var servPercent = ((parseFloat(servAmt2)/parseFloat(netAmt))*100);
                    $('#txtServPercent'+getId[1]).val(servPercent);
                }//end else

                getTotalServPercent();
                getTotalServAmt();
                getTotalSalesAmt();
                getTotalCollected();
            });

            $('.txtCollected').keyup(function () {
             getTotalCollected();
            });

            $('.txtAgentAmt').keyup(function () {
             getTotalAgentAmt();
            });

            $('#ddlType').change(function(){
                 var nVal = $('#ddlType').val();
                 var loc  = $('#ddlLocation').val();
                 $.ajax({
                     type:'POST',
                     data:{ddlType:nVal,ddlLocation:loc},
                     url:'<?php echo base_url().'index.php/sales_closing/getDocumentNumber/'.$this->uri->segment(3);?>',
                     dataType:'html',
                     beforeSend: function() {
                         $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                     },
                     success: function(response) {
                         $('#txtDocNumber').val(response);
                     }
                 });
            });

            $('.txtServAmt').click(function(){
             var id       	= this.id;
             var getId    	= id.split('txtServAmt');
             var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
             var collAmt  	= ($('#txtCollected' + getId[1]).val() != '' ? $('#txtCollected'+getId[1]).val() : 0);
             var agentAmt 	= ($('#txtAgentAmt' + getId[1]).val() != '' ? $('#txtAgentAmt'+getId[1]).val() : 0);
             var commAmt     = ($('#txtCommAmt' + getId[1]).val() != '' ? $('#txtCommAmt' + getId[1]).val() : 0);
             var servAmt     = ((parseFloat(collAmt)- parseFloat(netAmt)) - parseFloat(commAmt) - parseFloat(agentAmt));
             $('#txtServAmt'+getId[1]).val(servAmt);
             getTotalServAmt();
            });

            $('.txtServPercent').click(function(){
             var id          = this.id;
             var getId       = id.split('txtServPercent');
             var servAmt     = ($('#txtServAmt'+getId[1]).val() != '' ? $('#txtServAmt'+getId[1]).val() : 0);
             var netAmt	 	= ($('#txtNetAmt'+getId[1]).val() != '' ? $('#txtNetAmt'+getId[1]).val() : 0);
             if(servAmt == 0){
                 $('#txtServPercent'+getId[1]).val(servAmt);
             }//end if
             else if(netAmt == 0){
                 $('#txtServPercent'+getId[1]).val(netAmt);
             }//end else if
             else
             {
                 var servPercent = ((parseFloat(servAmt)/parseFloat(netAmt))*100);
                 $('#txtServPercent'+getId[1]).val(servPercent);
             }//end else
             getTotalServPercent();
            });

            $('.txtCollected').click(function(){
             var id       = this.id;
             var getId    = id.split('txtCollected');
             var salesAmt = ($('#txtSalesAmt' + getId[1]).val() != '' ? $('#txtSalesAmt'+getId[1]).val() : 0);
             var disAmt	 = ($('#txtDiscAmt'+getId[1]).val() != '' ? $('#txtDiscAmt'+getId[1]).val() : 0);
             var collAmt  = (parseFloat(salesAmt)-parseFloat(disAmt));
             $('#txtCollected'+getId[1]).val(collAmt);
             getTotalCollected();
            });

            $('#txtCardCommAmt').click(function(){
             var collAmt  = ($('#txtTotalCollected').val() != '' ? $('#txtTotalCollected').val() : 0);
             var commPer	 = ($('#txtCardComm').val() != '' ? $('#txtCardComm').val() : 0);
             var commAmt  = ((parseFloat(collAmt))*parseFloat(commPer)/100);
             $('#txtCardCommAmt').val(commAmt);
            });

            $('.ddlDrAccMain').change(function(e) {
             var id     = this.id;
             var getId  = id.split('ddlDrAccMain');
             var nVal   = $('#ddlDrAccMain'+getId[1]).val();
             getAccountHead(nVal,getId[1]);
            });

            $('.ddlCard').change(function(){
             var id     = this.id;
             var getId  = id.split('ddlCard');
             var nVal  = $('#ddlCard'+getId[1]).val();
             $.ajax({
                 type:'POST',
                 data:{ddlCard:nVal},
                 url:'<?php echo base_url().'index.php/sales_closing/getCarddetails/'.$this->uri->segment(3);?>',
                 dataType:'html',
                 success: function(response) {
                     $('#txtCardComm'+getId[1]).val(response);
                     var collAmt  = ($('#txtTotalCollected').val() != '' ? $('#txtTotalCollected').val() : 0);
                     var commAmt  = ((parseFloat(collAmt))*parseFloat(response)/100);
                     $('#txtCardCommAmt'+getId[1]).val(commAmt);
                 }
             });
            });

            $('.txtEmployee').click(function(e) {
                 var id      = this.id;
                 var nVal    = $('#'+id).val();
                 var getId   = id.split('txtEmployee');
                 var tktVal  = $('#ddlTkt'+getId[1]).val();
                 var typeVal = $('#ddlTktType'+getId[1]).val();
                 $.ajax({
                     type:'POST',
                     url:'<?php echo base_url().'index.php/sales_closing/getTktValues/'.$this->uri->segment(3);?>',
                     data:{ddlTkt:tktVal,ddlTktType:typeVal},
                     dataType:'html',
                     success: function(response) {
                         if(typeVal == 'Tkt' || typeVal == 'Xo')
                         {
                             $('#hotelDetails').addClass('hidden');
                             $('#ticketDetails').removeClass('hidden');
                         }//end if
                         if(typeVal == 'Htl')
                         {
                             $('#ticketDetails').addClass('hidden');
                             $('#hotelDetails').removeClass('hidden');
                         }//end if

                         var obj = $.parseJSON(response);
                         $('.txtPassName').val(obj.PassName);
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
                     }
                 });
            });

            $('#ddlDivision').change(function()
            {
             var nVal  = $('#ddlDivision').val();
             getLocation(nVal);
            });

        <?php if($this->uri->segment(4) == 'a' && $this->uri->segment(5) == ''):?>
            var nVal  = $('#ddlDivision').val();
            getLocation(nVal);
        <?php endif;?>

        $('#btnSaveExit,#btnGo').click(function(e) {
            var chk = $('#txtTotalCollected').val();
            var txtTotal = 0;
            $('.txtAmt').each(function () {
                var nVal2 = $(this).val();
                if (nVal2 == '') {
                    nVal2 = 0;
                }
                txtTotal = parseFloat(txtTotal)+parseFloat(nVal2);
            });
            if(chk == txtTotal)
            {
                return true;
            }
            else
            {
                alert('Account total amount should be equal to total collected amount');
                return false;
            }
        });

        <?php if($this->uri->segment(5) == 'unclosed'):?>
            $('.ddlDrAccMain').each(function () {
                var id     = this.id;
                var getId  = id.split('ddlDrAccMain');
                var nVal   = $('#ddlDrAccMain'+getId[1]).val();
                getAccountHead(nVal,getId[1]);
            });
            getTotalFace();
            getTotalTax();
            getTotalNet();
            getTotalServPercent();
            getTotalServAmt();
            getTotalDiscAmt();
            getTotalComm();
            getTotalCommAmt();
            getTotalSalesAmt();
            getTotalCollected();
            getTotalAgentAmt();

        <?php endif;?>

        $('.ddlTkt').change(function(){

            var id      = this.id;
            var nVal    = $('#'+id).val();
            var getId   = id.split('ddlTkt');
            var tktVal  = $('#ddlTkt'+getId[1]).val();
            var typeVal = $('#ddlTktType'+getId[1]).val();

			chkTktArray(tktVal,typeVal,'');

            $.ajax({
                type:'POST',
                url:'<?php echo base_url().'index.php/sales_closing/getTktValues/'.$this->uri->segment(3);?>',
                data:{ddlTkt:tktVal,ddlTktType:typeVal},
                dataType:'html',
                success: function(response) {
                    var obj = $.parseJSON(response);
                    $('#txtEmployee'+getId[1]).val(obj.Employee);
                    $('#txtAirline'+getId[1]).val(obj.Airline);
                    $('#txtFare'+getId[1]).val(obj.Fare);
                    $('#txtTax'+getId[1]).val(obj.Tax);
                    $('#txtNetAmt'+getId[1]).val(obj.NetAmt);
                    $('#txtServPercent'+getId[1]).val(obj.ServPercent);
                    $('#txtServAmt'+getId[1]).val(obj.ServAmt);
                    $('#txtDiscAmt'+getId[1]).val(obj.DiscAmt);
                    $('#txtComm'+getId[1]).val(obj.commPer);
                    $('#txtCommAmt'+getId[1]).val(obj.CommAmt);
                    $('#txtSalesAmt'+getId[1]).val(obj.SalesAmt);
                    $('#txtCollected'+getId[1]).val(obj.collected);
                    $('#txtAgentAmt'+getId[1]).val(obj.agentAmt);


                    $('.txtPassName').val(obj.PassName);
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

                    $('#ddlDrAccMain'+getId[1]).val(obj.accountVal);
                    $('#txtInvoiceTo').val(obj.InvoiceTo);

                    var nVal   = obj.accountVal;
                    getAccountHead(nVal,getId[1]);

                    getTotalFace();
                    getTotalTax();
                    getTotalNet();
                    getTotalServPercent();
                    getTotalServAmt();
                    getTotalDiscAmt();
                    getTotalComm();
                    getTotalCommAmt();
                    getTotalSalesAmt();
                    getTotalCollected();
                    getTotalAgentAmt();
                }
            });
        });

		function chkTktArray(id,type,count)
		{
			var ticket  = [];
            var result  = [];

			$('.ddlTkt').each(function () {

                var id      = this.id;
                var getId   = id.split('ddlTkt');
                var tktVal  = $('#ddlTkt'+getId[1]).val();
                var typeVal = $('#ddlTktType'+getId[1]).val();

				ticket.push({tid:tktVal,ttype:typeVal});
            });

			$.each(ticket,function(index,val){
				if(val['ttype'] == type)
				{
					result.push(val['tid']);
				}//end if
			});
			
			if(result.length > 1 && count != 1)
			{
				result.pop(1);
				if(jQuery.inArray(id,result) != -1)
				{
					alert('You have already entered this ticket once');
				}//end if
			}//end if
		}//end function
        
				var tabindex_val = 100; 
		var i = 0;
		while ($('#txtSalesAmt' + i).length > 0){
//			alert("hello " + $('#txtSalesAmt' + i).val());
			$('#txtSalesAmt' + i).attr('tabindex',tabindex_val);
			tabindex_val++;
			$('#txtDiscAmt' + i).attr('tabindex',tabindex_val);
			tabindex_val++;
			i++;
//			alert($('#txtDiscAmt' + i).val());			
		 }


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
<div class="tab-btn-container">
    <a href="<?php echo base_url();?>index.php/sales_closing/showUnclosedTicket/4" class="tab-button active"><?php echo UNCLOSEDTKT;?></a> <a href="<?php echo base_url();?>index.php/sales_closing/showlist/4" class="tab-button"><?php echo CLOSEDTKT;?></a>
</div>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php $chkInv = ($this->uri->segment(5) != '')?$this->uri->segment(5):0;
switch($chkInv)
{
    case "unclosed":
        $frmController = 'getUnclosedSalesDetails';
        break;

    default:
        $frmController = 'editSalesClosing';
        break;
}//end switch
?>
<?php echo form_open('sales_closing/'.$frmController.'/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nUId', $this->updId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>
<?php echo form_hidden('idAccArray', $chkAcId);?>
<?php echo form_hidden('nUrlId',$this->chkUrl);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>

<tr>
    <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision" tabindex="1"');?>
    </label></td>

    <td><?php echo LOCATIONNAME;?></td>
    <td><label>
        <span id="loc">
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" tabindex="2"');?>
        </span>
    </label></td>
</tr>

<tr>
    <td colspan="10">
        <table>
            <tr>
                <td><?php echo CUSTDATE;?></td>
                <td><label>
                    <input name="txtDate" id="txtDate" class="textfield5" type="text" value="<?php echo $this->txtDate;?>" tabindex="3">
                </label></td>

                <td><?php echo SALESTYPE;?></td>
                <td>
                    <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType, 'id="ddlType" class="ddlType" tabindex="4"');?>
                </td>

                <td><?php echo DOCNUMBER;?></td>
                <td><label>
                    <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text" readonly="readonly" value="<?php echo $this->txtDocNumber;?>" size="20">
                </label></td>

                <td><?php echo LPO;?></td>
                <td><label>
                    <input name="txtLpoMain" id="txtLpoMain" type="text" value="<?php echo $this->txtLpoMain;?>" class="pricebox" tabindex="5">
                </label></td>

                <td><?php echo AGENTAC;?></td>
                <td>
                    <label><?php echo form_dropdown('ddlAgentAc', $list_agent_value, $this->ddlAgentAc, 'id="ddlAgentAc" tabindex="6"');?></label>
                </td>
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
            <td><?php echo AIRLINE;?></td>
            <td><?php echo TICKETNAME;?></td>
            <td><?php echo EMPNAME;?></td>
            <td><?php echo FACEFARE;?></td>
            <td><?php echo TAX;?></td>
            <td><?php echo COMMPERCENT;?></td>
            <td><?php echo COMMISSIONAMT;?></td>
            <td><?php echo NETAMT;?></td>
            <td><?php echo SER;?></td>
            <td><?php echo SERAMT;?></td>
            <td><?php echo SLSAMT;?></td>
            <td><?php echo DISCAMT;?></td>
            <td><?php echo COLECTED;?></td>
            <td><?php echo AGENTNAME;?></td>
            <td><?php echo LPO;?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php
        if(!empty($chkType))
        {
            foreach($chkType as $key => $aitem):

                switch($chkType[$key])
                {
                    case "Tkt" :
                        $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$chkTktNumber[$key].'/closed';
                        break;

                    case "Xo" :
                        $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$chkTktNumber[$key].'/closed';
                        break;

                    case "Htl" :
                        $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$chkTktNumber[$key].'/closed';
                        break;
                }//end switch

                ?>
                <tr class="re">
                    <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>

                    <td>
                        <?php echo form_dropdown('ddlTktType[]', $list_type, $chkType[$key], "id='ddlTktType".$key."' class='singleDrop ddlTktType'");?>
                    </td>

					<td><label>
                        <input name="txtAirline[]" id="txtAirline<?php echo $key;?>" class="textfield6 txtAirline" type="text" value="<?php echo stripslashes($chkAirline[$key]);?>" readonly="readonly">
                    </label></td>
                    <?php if($this->uri->segment(5) == 'unclosed'):?>
                        <td>
                            <?php echo form_dropdown('ddlTkt[]', $this->sales_closing_model->getTicket($chkType[$key]), $chkTktNumber[$key],"id='ddlTkt".$key."' class='ddlTkt singleDrop'");?>
                        </td>
                    <?php else:?>
                        <td>
                            <?php echo form_dropdown('ddlTkt[]', $this->sales_closing_model->getClosedTicket($chkType[$key]), $chkTktNumber[$key],"id='ddlTkt".$key."' class='ddlTkt singleDrop'");?>
                        </td>
                    <?php endif;?>
                    <td><label>
                        <input name="txtEmployee[]" id="txtEmployee<?php echo $key;?>" class="textfield6 txtEmployee" type="text" value="<?php echo stripslashes($chkEmployee[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtFare[]" id="txtFare<?php echo $key;?>" class="textfield6 txtFare" type="text" value="<?php echo stripslashes($chkFare[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtTax[]" id="txtTax<?php echo $key;?>" class="textfield6 txtTax" type="text" value="<?php echo stripslashes($chkTax[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtComm[]" id="txtComm<?php echo $key;?>" class="textfield6 txtComm" type="text" value="<?php echo stripslashes($chkComm[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtCommAmt[]" id="txtCommAmt<?php echo $key;?>" class="textfield6 txtCommAmt" type="text" value="<?php echo stripslashes($chkCommAmt[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtNetAmt[]" id="txtNetAmt<?php echo $key;?>" class="textfield6 txtNetAmt" type="text" value="<?php echo stripslashes($chkNetAmt[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtServPercent[]" id="txtServPercent<?php echo $key;?>" class="textfield6 txtServPercent currentvalue" type="text" value="<?php echo stripslashes($chkServPercent[$key]);?>">
                    </label></td>

                    <td><label>
                        <input name="txtServAmt[]" id="txtServAmt<?php echo $key;?>" class="textfield6 txtServAmt currentvalue" type="text" value="<?php echo stripslashes($chkServAmt[$key]);?>">
                    </label></td>

                    <td><label>
                        <input name="txtSalesAmt[]" id="txtSalesAmt<?php echo $key;?>" class="textfield6 txtSalesAmt currentvalue" type="text" value="<?php echo stripslashes($chkSalesAmt[$key]);?>">
                    </label></td>

                    <td><label>
                        <input name="txtDiscAmt[]" id="txtDiscAmt<?php echo $key;?>" class="textfield6 txtDiscAmt currentvalue" type="text" value="<?php echo stripslashes($chkDiscAmt[$key]);?>">
                    </label></td>

                    <td><label>
                        <input name="txtCollected[]" id="txtCollected<?php echo $key;?>" class="textfield6 txtCollected currentvalue" type="text" value="<?php echo stripslashes($chkCollected[$key]);?>" readonly="readonly">
                    </label></td>

                    <td><label>
                        <input name="txtAgentAmt[]" id="txtAgentAmt<?php echo $key;?>" class="textfield6 txtAgentAmt currentvalue" type="text" value="<?php echo stripslashes($chkAgentAmt[$key]);?>">
                    </label></td>

                    <td><label>
                        <input name="txtLPO[]" id="txtLPO<?php echo $key;?>" class="textfield6 txtLPO currentvalue" type="text" value="<?php echo stripslashes($chkLPO[$key]);?>" title="<?php echo $key;?>">
                    </label></td>

                    <td>
                        <?php if($this->uri->segment(7) == 'cl'):?>
                        <a href="<?php echo $path;?>">
                            <img src="<?php echo base_url();?>static/images/pencil.png" alt="edit" />
                        </a>
                        <?php endif;?>
                    </td>

                    <td align="center">
                        <img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/>
                    </td>
                </tr>
                <?php
            endforeach;
        }//end if
        else
        {
            ?>
            <tr class="re">
                <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></td>

                <td><label>
                    <?php echo form_dropdown('ddlTktType[]', $list_type, $this->ddlTktType, 'id="ddlTktType" class="singleDrop ddlTktType"');?>
                </label></td>

				<td><label>
                    <input name="txtAirline[]" id="txtAirline" class="textfield6 txtAirline" type="text" value="" readonly="readonly">
                </label></td>
                
                <td>
                    <?php echo form_dropdown('ddlTkt[]', $list_tkt_value, $this->ddlTkt,'id="ddlTkt" class="ddlTkt singleDrop"');?>
                </td>

                <td><label>
                    <input name="txtEmployee[]" id="txtEmployee" class="textfield6 txtEmployee" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtFare[]" id="txtFare" class="textfield6 txtFare" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtTax[]" id="txtTax" class="textfield6 txtTax" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtComm[]" id="txtComm" class="textfield6 txtComm" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtCommAmt[]" id="txtCommAmt" class="textfield6 txtCommAmt" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtNetAmt[]" id="txtNetAmt" class="textfield6 txtNetAmt" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtServPercent[]" id="txtServPercent" class="textfield6 txtServPercent currentvalue" type="text" value="">
                </label></td>

                <td><label>
                    <input name="txtServAmt[]" id="txtServAmt" class="textfield6 txtServAmt currentvalue" type="text" value="">
                </label></td>

                <td><label>
                    <input name="txtSalesAmt[]" id="txtSalesAmt" class="textfield6 txtSalesAmt currentvalue" type="text" value="">
                </label></td>

                <td><label>
                    <input name="txtDiscAmt[]" id="txtDiscAmt" class="textfield6 txtDiscAmt currentvalue" type="text" value="">
                </label></td>

                <td><label>
                    <input name="txtCollected[]" id="txtCollected" class="textfield6 txtCollected" type="text" value="" readonly="readonly">
                </label></td>

                <td><label>
                    <input name="txtAgentAmt[]" id="txtAgentAmt" class="textfield6 txtAgentAmt currentvalue" type="text" value="">
                </label></td>

                <td><label>
                    <input name="txtLPO[]" id="txtLPO" class="textfield6 txtLPO currentvalue" type="text" value="" title="">
                </label></td>

                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_" title="Delete"/></td>
            </tr>
            <?php
        }//end else
        ?>

        <tr>
            <td colspan="4"></td>
            <td><?php echo TOTALAMOUNT;?></td>
            <td><input name="txtTotalFace" class="textfield6 currentvalue" id="txtTotalFace" type="text" value="<?php echo $this->txtTotalFace;?>" readonly="readonly"></td>
            <td><input name="txtTotalTax" class="textfield6 currentvalue" id="txtTotalTax" type="text" value="<?php echo $this->txtTotalTax;?>" readonly="readonly"></td>
            <td><input name="txtTotalCommPercent" class="textfield6 currentvalue" id="txtTotalCommPercent" type="text" value="<?php echo $this->txtTotalCommPercent;?>" readonly="readonly"></td>
            <td><input name="txtTotalCommAmt" class="textfield6 currentvalue" id="txtTotalCommAmt" type="text" value="<?php echo $this->txtTotalCommAmt;?>" readonly="readonly"></td>
            <td><input name="txtTotalNetAmount" class="textfield6 currentvalue" id="txtTotalNetAmount" type="text" value="<?php echo $this->txtTotalNetAmount;?>" readonly="readonly"></td>
            <td><input name="txtTotalServPercent" class="textfield6 currentvalue" id="txtTotalServPercent" type="text" value="<?php echo $this->txtTotalServPercent;?>" readonly="readonly"></td>
            <td><input name="txtTotalServAmount" class="textfield6 currentvalue" id="txtTotalServAmount" type="text" value="<?php echo $this->txtTotalServAmount;?>" readonly="readonly"></td>
            <td><input name="txtTotalSalesAmt" class="textfield6 currentvalue" id="txtTotalSalesAmt" type="text" value="<?php echo $this->txtTotalSalesAmt;?>" readonly="readonly"></td>
            <td><input name="txtTotalDiscAmount" class="textfield6 currentvalue" id="txtTotalDiscAmount" type="text" value="<?php echo $this->txtTotalDiscAmount;?>" readonly="readonly"></td>
            <td><input name="txtTotalCollected" class="textfield6 currentvalue" id="txtTotalCollected" type="text" value="<?php echo $this->txtTotalCollected;?>" readonly="readonly"></td>
            <td><input name="txtTotalAgentAmt" class="textfield6 currentvalue" id="txtTotalAgentAmt" type="text" value="<?php echo $this->txtTotalAgentAmt;?>" readonly="readonly"></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</td>
</tr>

<tr>
    <td colspan="8">
        <table id="dataTable2" title="dataTable2">
            <tr>
                <td colspan="9">
                    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow2" class="addNew" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><?php echo DRACCNAME;?></td>
                <td><?php echo ACHEAD;?></td>
                <td><?php echo CARDNAME;?></td>
                <td><?php echo COMMPERCENT;?></td>
                <td><?php echo COMMISSIONAMT;?></td>
                <td><?php echo AMT;?></td>
                <td><?php echo CRV;?></td>
                <td><?php echo REFTKT1;?></td>
                <td>&nbsp;</td>
            </tr>

            <?php
            if(!empty($chkDrAccMain))
            {
                foreach($chkDrAccMain as $key2 => $pitem):
                    ?>
                    <tr>
                        <td><input type="checkbox" checked onclick="return false" name="chkbox2[]" id="chkSelect2<?php echo $key2;?>" class="chkSelect2" value="<?php echo $chkAcId[$key2];?>"></td>

                        <td>
                            <?php echo form_dropdown('ddlDrAccMain[]',$list_dracc,$chkDrAccMain[$key2],"id='ddlDrAccMain".$key2."' class='ddlDrAccMain'");?>
                        </td>

                        <?php if($this->uri->segment(5) == 'unclosed'):?>
                        <td>
                            <span id="accHead">
                                <?php echo form_dropdown('ddlAcHead[]', $list_acchead_value,$chkAcHead[$key2],"id='ddlAcHead".$key2."' class='ddlAcHead'");?>
                            </span>
                        </td>
                        <?php else:?>
                        <td>
                            <span id="accHead">
                                <?php echo form_dropdown('ddlAcHead[]',$this->sales_closing_model->getActiveAccountHead($chkDrAccMain[$key2],$chkAcTable[$key2]),$chkAcHead[$key2],"id='ddlAcHead".$key2."' class='ddlAcHead'");?>
                            </span>
                        </td>
                        <?php endif;?>

                        <td><label>
                            <?php echo form_dropdown('ddlCard[]', $list_card_value, $chkCard[$key2],"id='ddlCard".$key2."' class='ddlCard'");?>
                        </label></td>

                        <td><label>
                            <input name="txtCardComm[]" id="txtCardComm<?php echo $key2;?>" class="textfield6 txtCardComm" type="text" value="<?php echo stripslashes($chkCardComm[$key2]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtCardCommAmt[]" id="txtCardCommAmt<?php echo $key2;?>" class="textfield6 txtCardCommAmt" type="text" value="<?php echo stripslashes($chkCardCommAmt[$key2]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtAmt[]" id="txtAmt<?php echo $key2;?>" class="textfield6 txtAmt" type="text" value="<?php echo stripslashes($chkAmt[$key2]);?>">
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('txtCRV[]', $crv_list, $chkCRV[$key2],"id='txtCRV".$key2."'");?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('txtRefTkt[]', $refund_list, $chkRefTkt[$key2],"id='txtRefTkt".$key2."'");?>
                        </label></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $chkAcId[$key2];?>" title="Delete"/></td>
                    </tr>
                    <?php
                endforeach;
            } //end if
            else {
                ?>

                <tr>
                    <td><input type="checkbox" checked onclick="return false" name="chkbox2[]" id="chkSelect2<?php echo $key2;?>" class="chkSelect2" value="<?php echo $chkAcId[$key2];?>"></td>

                    <td>
                        <?php echo form_dropdown('ddlDrAccMain[]',$list_dracc,$this->ddlDrAccMain,"id='ddlDrAccMain' class='ddlDrAccMain'");?>
                    </td>

                    <td>
                    <span id="accHead">
                        <?php echo form_dropdown('ddlAcHead[]', $list_acchead_value,$this->ddlAcHead,"id='ddlAcHead'");?>
                    </span>
                    </td>

                    <td><label>
                        <?php echo form_dropdown('ddlCard[]', $list_card_value, $this->ddlCard,"id='ddlCard' class='ddlCard'");?>
                    </label></td>

                    <td><label>
                        <input name="txtCardComm[]" id="txtCardComm" class="textfield6 txtCardComm" type="text" value="<?php echo stripslashes($this->txtCardComm);?>">
                    </label></td>

                    <td><label>
                        <input name="txtCardCommAmt[]" id="txtCardCommAmt" class="textfield6 txtCardCommAmt" type="text" value="<?php echo stripslashes($this->txtCardCommAmt);?>">
                    </label></td>

                    <td><label>
                        <input name="txtAmt[]" id="txtAmt" class="textfield6 txtAmt" type="text" value="<?php echo stripslashes($this->txtAmt);?>">
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('txtCRV[]', $crv_list, $this->txtCRV,"id='txtCRV'");?>
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('txtRefTkt[]', $refund_list, $this->txtRefTkt,"id='txtRefTkt'");?>
                    </label></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $chkAcId[$key2];?>" title="Delete"/></td>
                </tr>

                <?php
            }//end else

            ?>

        </table>
    </td>
</tr>

<tr>
    <td colspan="12">
        <table>
            <tr>
                <td colspan="12"><a href="#" style="font-size: 12px;font-weight: bold;color: #0A7886;" data-target="ticketDetails" class="showDetails"><?php echo TICKETDETAILS;?></a></td>
            </tr>
            <tr class="hidden" id="ticketDetails">
                <td><?php echo PASSENGER;?></td>
                <td>
                    <input name="txtPassName" id="txtPassName" class="textfield txtPassName" type="text" value="<?php echo $this->txtPassName;?>" tabindex="22">
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
        </table>
    </td>
</tr>

<tr>
    <td colspan="12">
        <table>
            <tr>
                <td colspan="12"><a href="#" style="font-size: 12px;font-weight: bold;color: #0A7886;" data-target="hotelDetails" class="showDetails"><?php echo HOTELDETAILS;?></a></td>
            </tr>
            <tr class="hidden" id="hotelDetails">

                <td><?php echo PASSENGER;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <input name="txtPassName" id="txtPassName" class="textfield txtPassName" type="text" value="<?php echo $this->txtPassName;?>" tabindex="22">
                </td>

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
                    <input name="txtCity" id="txtCity" class="textfield6" type="text" value="<?php echo $this->txtCity;?>">
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

                <td><?php echo INVOICETO;?></td>
                <td><label>
                    <input name="txtInvoiceTo" id="txtInvoiceTo" class="textfield" type="text" value="<?php echo $this->txtInvoiceTo;?>">
                </label></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
    <td colspan="5"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;
        <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9"><label>
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
        <?php if($this->uri->segment(4) == 'a'){?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo BACKTOSALESCLOSING;?>" id="btnCancel">
        <!--<input type="button" name="Print" value="Print" id="Print">-->
    </label>
        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
</tr>
</tbody>
</table>
</form>
</div>