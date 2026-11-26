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
function changeAllAccount(loc,id){
    var div = '';
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/credit_voucher/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccount2'+id).val();
                 $('#ddlAccount2'+id).html(response);
                 $('#ddlAccount2'+id).val(acc);
         }
     });
}

    $(document).ready(function()
    {
        $('#ddlCurrency').change(function()
        {
            var nVal = $('#ddlCurrency').val();
            $.ajax({
                type:'POST',
                data:{ddlCurrency:nVal},
                url:'<?php echo base_url().'index.php/recur_jv/getCurrencyRate/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#txtRate').val(response);
                }
            });
        });

        $('#txtAmountFC').keyup(function()
        {
            var nVal  = $('#ddlCurrency').val();
            var fcAmt = $('#txtAmountFC').val();
            $.ajax({
                type:'POST',
                url:'<?php echo base_url().'index.php/recur_jv/convertCurrency/'.$this->uri->segment(3);?>',
                data:{fcAmt:fcAmt,selCurrency:nVal},
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

        $('#btnCancel').click(function(){
            window.location.replace('<?php echo base_url().'index.php/recur_jv/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $("#txtStartDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $(".txtScheduleDate").datepick({ dateFormat: 'dd-mm-yyyy'});

		$('#txtFrequency').keyup(function(){
            var numJv = $('#txtFrequency').val();
            getEndDate(numJv);
			getDateLoop(numJv);
            $('#txtNumJv').val(numJv);
            var chk = parseFloat($('#txtAmountDC').val()) / $('#txtFrequency').val();
            $('#txtAmount').val(chk);
        });

		<?php if($this->uri->segment(4) == 'e'):?>
				getDateLoop($('#txtFrequency').val());
		<?php endif;?>
		
		function getEndDate(freq)
		{
			var date  = $("#txtStartDate").val();
            $.ajax({
                type:'POST',
                url:'<?php echo base_url().'index.php/recur_jv/getEndDate/'.$this->uri->segment(3);?>',
                data:{date:date,freqency:freq},
                dataType:'html',
                success: function(response) {
                    $('#txtEndDate').val(response);
                }
            });
		}//end function
		
		function getDateLoop(freq)
		{
			var date  = $("#txtStartDate").val();
            $.ajax({
                type:'POST',
                url:'<?php echo base_url().'index.php/recur_jv/getDateLoop/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>',
                data:{date:date,freqency:freq,jId:'<?php echo $this->uri->segment(5);?>'},
                dataType:'html',
                success: function(response) {
                    $('#showDateTable').html(response);
                }
            });
		}//end function
		

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
            var m	 = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
            }).end().find("select").each(function() {
                    var id  = this.id;
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
                }).end().find("textarea").each(function() {
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
                        url:'<?php echo base_url().'index.php/recur_jv/deleteRecord/'.$this->uri->segment(3);?>',
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

        $('.fcRate').keyup(function(e)
        {
            var id    = this.id;
            var getId = id.split('dcAmt');
            var exVal = $('#exRate' + getId[1]).val();
            var fcAmt = $('#dcAmt' + getId[1]).val();
            var dcVal = parseFloat(exVal) * parseFloat(fcAmt);
            $('#fcAmt' + getId[1]).val(dcVal);
            $('#exRate' + getId[1]).val(exVal);
        });

        <?php if($this->uri->segment(4) == 'a')
        {?>
            $.ajax({
                type:'POST',
                data:{ddlDivision:$('#ddlDivision').val()},
                url:'<?php echo base_url().'index.php/recur_jv/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                }
            });
        <?php }//end if
        else { ?>
            $.ajax({
                type:'POST',
                data:{ddlDivision:'<?php echo $this->ddlDivision;?>',ddlLocation:'<?php echo $this->ddlLocation;?>'},
                url:'<?php echo base_url().'index.php/recur_jv/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                }
            });
        <?php }//end else?>

        $('#ddlDivision').change(function()
        {
            var nVal  = $('#ddlDivision').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nVal,ddlTktname:0},
                url:'<?php echo base_url().'index.php/recur_jv/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                }
            });
        });

    $('.ddlLocation2').change(function()
    {
        var id    = this.id;
        var getId = id.split('ddlLocation2');
        var loc =  $('#ddlLocation2' + getId[1]).val();
        changeAllAccount(loc, getId[1]);
    });

    $('.ddlLocation2').each(function(){
        var id    = this.id;
        var getId = id.split('ddlLocation2');
        var loc =  $('#ddlLocation2' + getId[1]).val();
        changeAllAccount(loc,getId[1]);
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
                    url:'<?php echo base_url().'index.php/recur_jv/filterSubAccount/'.$this->uri->segment(3);?>',
                    dataType:'html',
                    success: function(response) {
                        $('#ddlSubAccount2'+getId[1]).html(response);
                    }
                });
        });

        //single amount fc calculation
        $('#txtAmountDC').keyup(function(e)
        {
            var nVal   = $('#txtAmountDC').val();
            var nVal2  = $('#txtRate').val();
            var total  = parseFloat(nVal) * parseFloat(nVal2);
            $('#txtAmountFC').val(total);
        });

        $('#txtRemarks').keyup(function(){
            var nVal = $('#txtRemarks').val();
            $('.txtDes').val(nVal);
        });
		
		$('#btnSaveExit,#btnGo').click(function(e) {
			var chk = parseFloat($('#txtAmountDC').val()) / $('#txtFrequency').val();
            var txtTotal = 0;			var txtamt=$('#txtAmount').val();
            $('.txtAmount').each(function () {
                var nVal2 = $(this).val();
                if (nVal2 == '') {
                    nVal2 = 0;
                }
                txtTotal = parseFloat(txtTotal)+parseFloat(nVal2);
            });
			
        });

        $('.btnGeneral').click(function(e) {
			var chk = parseFloat($('#txtAmountDC').val()) / $('#txtFrequency').val();
            var txtTotal = 0;
			var txtamt=$('#txtAmount').val();
            $('.txtAmount').each(function () {
                var nVal2 = $(this).val();
                if (nVal2 == '') {
                    nVal2 = 0;
                }
                txtTotal = parseFloat(txtTotal)+parseFloat(nVal2);
            });
			
        });

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
    <?php echo form_open('recur_jv/editRecurrJv/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>
    <?php echo form_hidden('idSetupArray', $chkDetailID);?>
    <?php echo form_hidden('idScheduleArray', $chkscId);?>
    <div id="result">
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

        <td><?php echo TRANSNAME;?></td>
        <td><label>
            <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text" value="<?php echo $this->txtDocNumber;?>" size="20">
        </label></td>
    </tr>

    <tr>
        <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
        <td><label>
            <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency, 'id="ddlCurrency" class="ddlCurrency"');?>
        </label></td>

        <td><?php echo RATE;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtRate" id="txtRate" class="textfield  pricebox" type="text" value="<?php echo stripslashes($this->txtRate);?>" size="2">
        </label></td>
    </tr>

    

    <tr>
        <td><?php echo AMOUNTDC;?></td>
        <td><label>
            <input name="txtAmountDC" id="txtAmountDC" type="text" value="<?php echo $this->txtAmountDC;?>" class="pricebox">
        </label></td>

        <td><?php echo AMOUNTFC;?></td>
        <td><label>
            <input name="txtAmountFC" id="txtAmountFC" type="text" value="<?php echo $this->txtAmountFC;?>" class="pricebox">
        </label></td>
    </tr>

    <tr>
        <td><?php echo STARTDATE;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtStartDate" id="txtStartDate" type="text" value="<?php echo $this->txtStartDate;?>" class="pricebox">
        </label></td>

        <td><?php echo FREQUENCY;?></td>
        <td><label>
            <input name="txtFrequency" id="txtFrequency" type="text" value="<?php echo $this->txtFrequency;?>" class="pricebox">
        </label></td>
    </tr>

    <tr>
        <td style="display:none;"><?php echo NOOFJV;?></td>
        <td style="display:none;"><label>
            <input name="txtNumJv" id="txtNumJv" type="text" value="<?php echo $this->txtNumJv;?>" readonly="readonly" class="pricebox">
        </label></td>
     <td><?php echo ENDDATE;?></td>
        <td><label>
            <input name="txtEndDate" id="txtEndDate" type="text" value="<?php echo $this->txtEndDate;?>" readonly="readonly" class="pricebox">
        </label></td>
		<td><?php echo REFNUMBER;?></td>
        <td><label>
            <input name="txtRefNumber" id="txtRefNumber" type="text" value="<?php echo $this->txtRefNumber;?>" size="40" class="pricebox">
        </label></td>
    </tr>
<tr>
        <td><?php echo REFNAME;?></td>
        <td><label>
            <input style="width:55%;" name="txtTRX" id="txtTRX" type="text" value="<?php echo $this->txtTRX;?>" class="pricebox">
        </label></td>

    </tr>
    <tr>
        <td><?php echo REMARKS;?></td>
        <td><label>
            <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->txtRemarks;?></textarea>
        </label></td>
    </tr>

    <tr>
        <td colspan="7">
            <table id="dataTable" title="dataTable">
                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
                <tr>
                    <td>&nbsp;</td>
                    <td><?php echo LOCATIONNAME;?></td>
                    <td><?php echo ACCCODENAME;?></td>
                    <td><?php echo SUBCODE;?></td>
                    <td><?php echo BUDGETMODE;?></td>
                    <td><?php echo AMOUNTFC;?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php
                if(!empty($chkLoc))
                {
                    foreach($chkLoc as $key => $aitem):
                        ?>
                        <tr>
                            <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>

                            <td><label>
                                <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $chkLoc[$key], "id='ddlLocation2".$key."' class='ddlLocation2'");?>
                            </label>
                            </td>

                            <td><label>
                                <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $chkAccCode[$key], 'id="ddlAccount2'.$key.'" class="ddlAccount2"');?>
                            </label></td>

                            <td><label>
                                <?php echo form_dropdown('ddlSubAccount2[]', $this->recurring_jv_model->getSubAccount($chkAccCode[$key]), 'id="ddlSubAccount2'.$key.'" class="ddlSubAccount2"');?>
                            </label></td>

                            <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="ddlMode"');?></td>

                            <td><input name="txtAmount[]" id="txtAmount<?php echo $key;?>" class="textfield4 txtAmount" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>

                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                else
                { ?>
                    <tr>
                        <td><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></td>

                        <td><label>
                            <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $this->ddlLocation2, "id='ddlLocation2'  class='ddlLocation2'" );?>
                        </label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $this->ddlAccount2, 'id="ddlAccount2" class="ddlAccount2"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubAccount2[]', $list_subacc_multiple, $this->ddlSubAccount2, 'id="ddlSubAccount2" class="ddlSubAccount2"');?>
                        </label></td>

                        <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="ddlMode"');?></td>

                        <td><input name="txtAmount[]" id="txtAmount" class="textfield4 txtAmount" type="text" value=""></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_>" title="Delete"/></td>
                    </tr>
					
                    <?php
                }//end else
                ?>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="4">
        	<span id="showDateTable">
            
            </span>
            
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
            <input type="button" name="btnCancel" value="<?php echo BACKTORECURRINGJV;?>" id="btnCancel">
        </label></td>
    </tr>
    </tbody>
    </table>
    </div>
    </form>    </form>
</div>
