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
   
    function getLocation(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/reconciliation/filterDivision/'.$this->uri->segment(3);?>',
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

		function getiata(nloc){
			
			if(nloc==1){
				//alert('test');
				$('#txtIATA option[value=86214531]').attr('selected','selected');
			
			}
			if(nloc==5){
				$('#txtIATA option[value=86214531]').attr('selected','selected');
				
			}
			if(nloc==4){
				$('#txtIATA option[value=42202134]').attr('selected','selected');
				
				
			}
		}
    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});

        getLocation($('#ddlDivision').val());
		var nloc =<?php echo $this->session->userdata('accessLoc');?> //$('#ddlLocation').val();
		//alert(nloc);
			getiata(nloc);

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
			var nloc = $('#ddlLocation').val();
			getiata(nloc);
        });

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "Full":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Matched":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "UnMatched":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayUnMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nFromDate       = $('#txtFromDate').val();
            var nToDate         = $('#txtToDate').val();
			var nIATA         = $('#txtIATA').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,txtToDate:nToDate,txtFromDate:nFromDate,txtIATA:nIATA,sess_clear:sess_clear},
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
$('.ddlLocation').change(function(){
   var nloc = $('#ddlLocation').val();
			if(nloc==1){
				//alert('test');
				$('#txtIATA option[value=86214531]').attr('selected','selected');
			
			}
			if(nloc==5){
				$('#txtIATA option[value=86214531]').attr('selected','selected');
				
			}
			if(nloc==4){
				$('#txtIATA option[value=42202134]').attr('selected','selected');
				
				
			}
});
		
        $('#btnProcess,#btnView,#btnViewMatched,#btnViewUnMatched').click(function(e) {
            var idVal    = this.id;
            var fromDate = $('#txtFromDate').val();
            var toDate   = $('#txtToDate').val();
			 var txtIATA   = $('#txtIATA').val();
            if(fromDate != '' && toDate != '')
            {
                switch(idVal)
                {
                    case "btnView":
                        getVal('Full');
                        break;

                    case "btnViewMatched":
                        getVal('Matched');
                        break;

                    case "btnViewUnMatched":
                        getVal('UnMatched');
                        break;
                }//end switch
            }
            else
            {
                alert('Give From date And To Date');
                return false;
            }
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
<?php echo form_open_multipart('reconciliation/process/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating BSP Reconciliation Report ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
<div id="result">
<style>
table.entryForm input[type="text"], table.entryForm input[type="password"], table.entryForm select{
    width: 48%;
    height: 25px
}
</style>
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

                        <td><?php echo LOCATIONNAME;?></td>
                        <td><label>
                            <span id="loc">
                                <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>
                            </span>
                        </label></td> 
                       <td><?php echo IATA;?></td>
                        <td><label><span id="loc">
                        	<select name="txtIATA" id="txtIATA" class="txtIATA smallDropdown">
								<option value="86214531">86214531</option>
								<option value="86214531">86214531</option>
								<option value="42202134">42202134</option>
								</select>
								</span></label>
                        </td>
                    </tr>
					<tr> <td>
                            <input type="hidden" value="" name="" id="abc" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                             </td></tr>
					
					<tr> <!--<td>
                            <input type="radio" value="f" name="txtReport" id="txtReport" checked="checked" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                            <input type="radio" value="u" name="txtReport" id="txtReport2" />&nbsp;<?php /*echo UREPORT;*/?>
                        </td>-->		</tr>
                    <tr>
                        <td><?php echo FROMDATE;?></td>
                        <td>
                            <input name="txtFromDate" id="txtFromDate" class="textfield5" type="text" value="<?php echo $this->txtFromDate;?>">
                        </td>

                         <td><?php echo TODATE;?></td>
                        <td>
                            <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">
                        </td>
                        <!--<td>
                            <input type="radio" value="f" name="txtReport" id="txtReport" checked="checked" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                            <input type="radio" value="u" name="txtReport" id="txtReport2" />&nbsp;<?php /*echo UREPORT;*/?>
                        </td>-->
                        <td><label>
                            <input type="file" name="txtFile" id="txtFile" />
                        </label>
                        </td>
                    </tr>
					<tr> <td>
                            <input type="hidden" value="" name="" id="abc" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                             </td></tr>
							 <tr> <td>
                            <input type="hidden" value="" name="" id="abc" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                             </td></tr>
                </table>
            </td>
        </tr>

        <tr class="formButs">
            <td></td>
            <td colspan="5">
                <input type="submit" name="btnProcess" value="<?php echo PROCESS;?>" id="btnProcess"/>
                <input type="button" name="btnView" id="btnView" value="<?php echo FREPORT;?>" />
                <input type="button" name="btnViewMatched" id="btnViewMatched" value="<?php echo MREPORT;?>" />
                <input type="button" name="btnViewUnMatched" id="btnViewUnMatched" value="<?php echo UREPORT;?>" />
            </td>
        </tr>
        </tbody>
        </table>
</div>
    </form>
</div>