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
   
    function getLocation(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/open_report/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#ddlLocation2').html(response);
                $('#ddlLocation').html(response);
            }
        });
    }

    function getAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccGroup:nVal},
            url:'<?php echo base_url().'index.php/open_report/filterAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#Acc').html(response);
            }
        });
    }

    function getSubAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccount:nVal},
            url:'<?php echo base_url().'index.php/open_report/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#subAcc').html(response);
            }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        getLocation($('#ddlDivision').val());

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        //getAccount($('#ddlAccGroup').val());

        $('#ddlAccGroup').change(function(){
            var nVal = $('#ddlAccGroup').val();
            getAccount(nVal);
        });

        getSubAccount($('#ddlAccount').val());

        $('#Acc').click(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });
        $('#ddlAccount').change(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });

        $('#btnReport').click(function () {

            var nDivision   = $('#ddlDivision').val();
            var nLocation   = $('#ddlLocation').val();
            var nLocation2  = $('#ddlLocation2').val();
            var nFromDate   = $('#txtYear').val();
            var nAccount    = $('#ddlAccount').val();
            var nSubAccount = $('#ddlSubAccount').val();
            var accId       = nSubAccount.split("-");
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtYear:nFromDate,
                      ddlAccount:nAccount,ddlSubAccount:accId[0],tableType:accId[1]},
                url:'<?php echo base_url().'index.php/open_report/getReport/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                $('#loading').show();
				$('#result').hide();
				},
				error: function (jqXHR, exception) {
					var msg = '';
					if (jqXHR.status === 0) {
						msg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						msg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						msg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						msg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						msg = 'Time out error.';
					} else if (exception === 'abort') {
						msg = 'Ajax request aborted.';
					} else {
						msg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					$('#img1').hide();
					$('#title').hide();
					$('#error1').html(msg);
				},
				
				success:function (response) {
					$('#loading').hide();
					 $('#result').show();
                    $('#result').html(response);
                }
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
<?php echo form_open('open_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Opening Balance Report ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
     <div id="result">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
            <tbody>

            <tr>
                <td colspan="10">
                    <table>
                        <tr>
                            <td><?php echo DIVISIONNAME;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision smallDropdown"');?>
                            </label></td>

                            <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>
                            <td><label>
                            <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>
                            </label></td>

                            <td><?php echo LOCATION.'&nbsp;'.TO;?></td>
                            <td><label>
                            <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>
                            </label></td>
                        </tr>
                        <tr>

                            <td><?php echo YEAR;?> <span class="warning"></span></td>
                            <td><label>
                            <input name="txtYear" id="txtYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtYear);?>">
                            </label></td>
                        </tr>
                        <tr>
                            <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup" class="chosen-select"');?>
                            </label></td>

                            <td><?php echo ACNAME;?> <span class="warning"></span></td>
                            <td>
                            <span id="Acc">
                                <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
                            </span>
                            </td>

                            <td><?php echo SUBACCOUNT;?></td>
                            <td>
                            <span id="subAcc">
                                <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>
                            </span>
                            </td>
                        </tr>

                        <!--<tr>
                            <td colspan="5"><label>
                            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>              &nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo          FOREIGNCURRENCY;?></label></td>
                        </tr>-->

                    </table>
                </td>
            </tr>

            <tr class="formButs">
                <td>
                    <input type="button" id="btnReport" name="btnReport" value="<?php echo Report;?>" /></td>
            </tr>

            </tbody>
        </table>
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
