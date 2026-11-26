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



    function getLocation(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlDivision:nVal},

            url:'<?php echo base_url().'index.php/unpost_doc/filterDivision/'.$this->uri->segment(3);?>',

            dataType:'html',

            beforeSend: function() {

                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

            },

            success: function(response) {

                $('#ddlLocation2').html(response);

                $('#ddlLocation').html(response);

                changeAllAccount();

            }

        });

    }

function changeAllAccount(){

     var loc  = $('#ddlLocation').val();

     var loc2  = $('#ddlLocation2').val();

     var div = $('#ddlDivision').val();



     $.ajax({

         type:'POST',

         data:{ddlLocation:loc,ddlDivision:div,ddlLocation2:loc2},

         url:'<?php echo base_url().'index.php/sales_receipt_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var issued = $('#ddlEmpl').val();

                 $('#ddlEmpl').html(response);

                 $('#ddlEmpl').val(issued);

         }

     });



}



    $(document).ready(function()

    {

        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});

        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});



        getLocation($('#ddlDivision').val());



        $('#btnCancel').click(function() {

            window.location.replace('<?php echo base_url().'index.php/sales_receipt_report/showlist/'.$this->uri->segment(3);?>');

            return false;

        });



        $('#ddlDivision').change(function(){

            var nVal = $('#ddlDivision').val();

            getLocation(nVal);

        });



        $('#loc').change(function(){

            changeAllAccount();

            });



        $('#loc2').change(function(){

            changeAllAccount();

            });







        $('#toPopup1').hide();



        $('.close').click(function(){

            $('#toPopup1').hide();

        });



        $('#btnGo').click(function(){



            var nVal   = $('#ddlDivision').val();

            var nVal2  = $('#ddlLocation').val();

            var nVal3  = $('#ddlLocation2').val();

            var nVal4  = $('#txtFromDate').val();

            var nVal5  = $('#txtToDate').val();

            var nVal6  = $('#ddlEmpl').val();

            //var nVal7  = $('#ddlType').val();

            var nVal8  = $('#ddlDocType').val();

           var nVal9  = $('#ddlSalesType').val();

            var sess_clear = 'y';

//alert(nVal9);
//ddlType:nVal7,ddlDocType:nVal8,ddlSalesType:nVal9,
            $.ajax({

                type:'POST',

                data:{ddlDivision:nVal,ddlLocation:nVal2,ddlLocation2:nVal3,txtFromDate:nVal4,txtToDate:nVal5,ddlEmpl:nVal6,ddlDocType:nVal8,ddlSalesType:nVal9,

                    sess_clear:sess_clear},

                url:'<?php echo base_url().'index.php/sales_receipt_report/getDetailReport/'.$this->uri->segment(3);?>',

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

echo validation_errors('<p class="errorClass">', '</p>');

$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');



if (isset($error_value) && trim($error_value) != '') {

    ?>

<code><?php echo $error_value;?></code>

<?php

}//end if

?>

<?php $this->load->view('general/admin_menu'); ?>

<div class="formHead">

    <h4><?php echo $this->showTitle;?></h4>

</div>

<div class="formBorder">

    <?php echo form_open('sales_receipt_report/filterList/'.$this->uri->segment(3).'/'.$this->uri->segment(4), $attributes); ?>

    <?php echo form_hidden('nId', $this->catId);?>
	<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Sales Receipt Report ...</h3><br>
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

                            <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>

                            <td><label>

                                <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision smallDropdown"');?>

                            </label></td>



                            <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>

                            <td><label>

                                <span id="loc">

                                    <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>

                                </span>

                            </label></td>



                            <td><?php echo LOCATION.'&nbsp;'.TO;?></td>

                            <td><label>

                                <span id="loc2">

                                    <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>

                                </span>

                            </label></td>

                        </tr>



                        <tr>

                            



                            <td><?php echo ACCFROMDATE;?></td>

                            <td>

                                <label><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2" />

                            </td>



                            <td><?php echo ACCTODATE;?></td>

                            <td><label>

                                <input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />

                            </label></td>

                        </tr>



                        <tr>



                            <td><?php echo 'ENTERED BY';?> <span class="warning">*</span></td>

                            <td><label>

                                <?php

if($this->session->userdata('AbsAdmin')==1){
								echo form_dropdown('ddlEmpl', $list_emp, $this->ddluser,'id="ddlEmpl" class="ddlEmpl smallDropdown "');
}else{?>
	<select name="ddlEmpl" id="ddlEmpl" class="ddlEmpl smallDropdown ">
	<option value="<?php echo $this->session->userdata('AbsAdmin');?>"><?php echo $this->common_model->get_field_name('Admin', 'vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");?></option>

</select>
<?php }
								
								?>

                            </label></td>



                            <td><?php echo 'ACCOUNT';?> <span class="warning">*</span></td>

                            <td><label>

                                <?php echo form_dropdown('ddlSalesType', $list_acc_value, $this->ddlSalesType,'id="ddlSalesType" class="ddlSalesType smallDropdown"');?>

                            </label></td>



                           <td><?php echo REPORTTYPE;?> <span class="warning">*</span></td>

                            <td><label>

                                <?php echo form_dropdown('ddlDocType', $doc_type, $this->ddlDocType,'id="ddlDocType" class="ddlDocType smallDropdown"');?>

                            </label></td>



                        </tr>

                    </table>

                </td>

            </tr>

            <tr class="formButs">

                <td>&nbsp;</td>

                <td colspan="9"><label>

                    <span style="font-size: 12px;font-weight: bold;">

                            <input type="button" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />

                    </span>

                    <?php if($this->uri->segment(4) == 'a'){?>

                        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />

                    <?php }//end if?>

                    <input type="reset" value="<?php echo RESET;?>" />

                </label></td>

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

