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
<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>
<script language="javascript1.1" type="text/javascript">
    $.validator.setDefaults({
        submitHandler: function() {}
    });


    function getLocation(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/chart_report/filterDivision/'.$this->uri->segment(3);?>',
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
    $(document).ready(function()
    {
        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        getLocation($('#ddlDivision').val());

        $('#btnAccountGroup').click(function()
        {
            getVal('AccountGroup');
        });

        $('#btnAccount').click(function()
        {
            getVal('AccountHead');
        });

        $('#btnAccountHeadGroup').click(function()
        {
            getVal('AccountHeadGroup');
        });

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "AccountGroup":
                    var path = '<?php echo base_url().'index.php/chart_report/getAccountGroup/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "AccountHead":
                    var path = '<?php echo base_url().'index.php/chart_report/getAccountHead/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "AccountHeadGroup":
                    var path = '<?php echo base_url().'index.php/chart_report/getAccountHeadGroup/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,sess_clear:sess_clear},
                url:path,
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
        }//end function        
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
    <?php echo form_open('chart_report/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>
	<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Chart Of Account ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
    <div id="result">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
            <tbody>

            <tr>
                <td><?php echo DIVISIONNAME;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision"');?>
                </label></td>

                <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>
                <td><label>
                    <span id="loc">
                        <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation"');?>
                    </span>
                </label></td>
            </tr>

            <tr class="formButs">
                <td>&nbsp;</td>
                <td colspan="9">
                    <input type="button" name="btnAccountGroup" id="btnAccountGroup"  value="<?php echo ACCOUNTGROUPLIST;?>" />
                    <input type="button" name="btnAccount" id="btnAccount" value="<?php echo ACCOUNTHEADLIST;?>">
                    <input type="button" name="btnAccountHeadGroup" id="btnAccountHeadGroup" value="<?php echo ACCOUNTHEADWITHGROUPLIST;?>">
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    </form>
</div>
