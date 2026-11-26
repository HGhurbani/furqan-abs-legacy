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

// +----------------------------------------------------------------------|

?>
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">
<style>
#popup_content{
	z-index: 1 !important; 
	
}
table.entryForm input[type="text"], table.entryForm input[type="password"], table.entryForm select{
	
	min-width: 168px !important;
}
#toPopup, .toPopup {
	width: 51% !important;
}

</style>
<script language="javascript1.1" type="text/javascript">

var status = false;

$.validator.setDefaults({

    submitHandler: function() {}

});




function getLocation(nVal) {

    $.ajax({

        type:'POST',

        data:{ddlDivision:nVal},

        url:'<?php echo base_url().'index.php/visa_entry/filterDivision/'.$this->uri->segment(3);?>',

        dataType:'html',

		async:false,

        beforeSend: function() {

            $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

        },

        success: function(response) {

            //alert(response)

            $('#loc').html(response);

			status = true;



            var nVal1 = $('#ddlType').val();

            var nVal2 = $('#ddlLocation').val();
			var dt = $('#txtDate').val();

            $.ajax({

                type:'POST',

                data:{ddlType:nVal1,ddlLocation:nVal2,dt:dt},

                url:'<?php echo base_url().'index.php/visa_entry/generateDocNo/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend:function () {

                    $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success:function (response) {
				<?php if($this->uri->segment(4) == 'a'){?>
				
                    $('#txtDocNumber').val(response);
				<?php } ?>

                }

            });



        }

    });



}









$(document).ready(function(){
 var nVal = $('#ddlDivision').val();

        getLocation(nVal);


    $("ul.pgcontent").quickPagination({pageSize:"5"});



    $('#btnCancel').click(function(){

        window.location.replace('<?php echo base_url().'index.php/visa_entry/showlist/'.$this->uri->segment(3);?>');

        return false;

    });



    $("#txtDate").datepick({ dateFormat:'dd-mm-yyyy'});
	
	
	
	
	$(".txtdoe").datepick({ dateFormat:'dd-mm-yyyy'});
	$(".txtdoi").datepick({ dateFormat:'dd-mm-yyyy'});
	$(".txtdob").datepick({ dateFormat:'dd-mm-yyyy'});
	

    



    jQuery.validator.addMethod(

        "selectNone",

        function(value, element) {

            if (element.value == "0" || element.value == "")

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

            ddlType: {

                selectNone : true

            },

            ddlLedger: {

                selectNone : true

            },

            ddlDivision: {

                selectNone : true

            },

            ddlLocation: {

                selectNone : true

			}

        },

        messages: {

            ddlType: {

                selectNone : "<?php echo PLEASESELECTANOPTION;?>"

            },
			

            ddlDivision: {

                selectNone : "<?php echo PLEASESELECTANOPTION;?>"

            },

            ddlLocation: {

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
		 
		$(".chosen-select").chosen("destroy");
		

        var m	 = $('#dataTable > tbody>tr:last').clone(true).find("input").each(function() {

            var id = this.id;
			

            $(this).attr({

                'id': function(_, id2) { return id + i; }

            });

        }).end().find("select").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': function(_, id2) { return id + i; },

                    'value': '0'

                });

                



            }).end().find("a").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': function(_, id2) { return id + i; },

                    'value': ''

                });

            }).end().find("div.toPopup").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': function(_, id2) { return id + i; },

                    'value': ''

                });

//            }).end().insertAfter('#dataTable > tbody>tr:last');

    }).end().find("img").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': 'del'

                });



            }).end().insertAfter('#dataTable > tbody>tr:last');

        i++;
$(".chosen-select").chosen();



        var rowCount = $("#dataTable").attr('rows').length;

        m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');

        return false;

    });



    /*------------------Custom delete row ------------------*/



    $('.del').live('click',function(){

        var rowCount = $("#dataTable").attr('rows').length;

        if((rowCount)>2)

        {

            $(this).parent().parent().remove();

            //delete from the db

            var dId = this.id;

            dId     = dId.split('_');

            //delete from db

            $.ajax(

                {

                    type:'POST',

                    url:'<?php echo base_url().'index.php/visa_entry/deleteRecord/'.$this->uri->segment(3);?>',

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



    $('#ddlDivision').change(function () {

        var nVal = $('#ddlDivision').val();

        getLocation(nVal);

        changeAllAccount();

    });



    $('#loc').change(function () {
		
		 var nVal1 = $('#ddlType').val();

            var nVal2 = $('#ddlLocation').val();
			var dt=$('#txtDate').val();

            $.ajax({

                type:'POST',

                data:{ddlType:nVal1,ddlLocation:nVal2,dt:dt},

                url:'<?php echo base_url().'index.php/visa_entry/generateDocNo/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend:function () {

                    $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success:function (response) {
					<?php if($this->uri->segment(4) == 'a'){?>
                    $('#txtDocNumber').val(response);
				<?php }?>
                }

            });
            changeAllAccount();

    });



	<?php if($this->uri->segment(4) == 'a'):?>

		getLocation($('#ddlDivision').val());

		if(status == true)

		{

			var nVal1 = $('#ddlType').val();

			var nVal2 = $('#ddlLocation').val();
			var dt = $('#txtDate').val();

			$.ajax({

				type:'POST',

				data:{ddlType:nVal1,ddlLocation:nVal2,dt:dt},

				url:'<?php echo base_url().'index.php/visa_entry/generateDocNo/'.$this->uri->segment(3);?>',

				dataType:'html',

				beforeSend:function () {

					$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

				},

				success:function (response) {
			<?php if($this->uri->segment(4) == 'a'){?>
					$('#txtDocNumber').val(response);
			<?php } ?>

				}

			});

		}//end if

    <?php endif;?>

	

 








    

   
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

echo validation_errors('<p class="errorClass">', '</p>');

$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');

if (isset($error_value) && trim($error_value) != '') {

    ?>

<code><?php echo $error_value;?></code>

<?php

}//end if

?>

<div class="formHead">

    <h4><?php echo $this->showTitle;?></h4>

</div>

<div class="formBorder">

<?php echo form_open('visa_entry/editVisa/'.$this->uri->segment(3).'/'.$this->uri->segment(4), $attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>

<?php echo form_hidden('nCId', $this->catId);?>

<?php echo form_hidden('nCheck', 'frmPost');?>

<?php echo form_hidden('idArray', $chkAccID);?>

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

            </span></label></td>



    <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>

    <td><label>

        <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo $this->txtDate;?>">

    </label></td>

</tr>



<tr>
	 <td><?php echo DOCNUMBER;?> <span class="warning">*</span></td>

    <td><label>

        <input name="txtDocNumber" id="txtDocNumber" class="textfield" type="text" value="<?php echo $this->txtDocNumber;?>" size="20">

    </label></td>

    <td><?php echo STATUS;?> <span class="warning">*</span></td>

    <td><label>

        <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType, 'id="ddlType"');?>

    </label></td>



   



    

</tr>

<tr>

<td><?php echo "VISA FILE";?> <span class="warning">*</span></td>

    <td><label>

        <input name="txtvoc" id="txtvoc" class="textfield" type="text" value="<?php echo $this->vTicktManual;?>" size="20">

    </label></td>
	

    <td><?php echo "STAFF";?> <span class="warning">*</span></td>

    <td><label>

        <?php echo form_dropdown('ddlissue', $list_emp_value, $this->issue, "id=ddlissue");?>

    </label></td>



    


    

</tr>
<tr>
<td><?php echo "VISA TYPE";?> <span class="warning">*</span></td>

    <td><label>

        <?php echo form_dropdown('ddlvisatype', $list_visa_type, $this->vType, "id='ddlIssued'");?>

    </label></td>



    


    

</tr>


<tr>

    <td colspan="10">

        <table id="dataTable" title="dataTable">

            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />

            <tbody>

            <tr>

            	<td>&nbsp;</td>


                <td><?php echo PASSENGER;?></td>

               

            </tr>

            <?php

            if (!empty($vPassName)) {

                foreach($vPassName as $key => $aitem):

                    ?>
				

                    <tr>

                        <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>



                        


                        <td><label>

                            <input name="txtPass[]" id="txtPass<?php echo $key;?>" class="textfield txtPass" type="text" value="<?php echo stripslashes($aitem);?>">

                        </label></td>






                        <td><a href="#" class="topopup" id="<?php echo $key;?>"><span style="font-size: 12px;font-weight: bold;"><?php echo DETAILS;?></span></a></td>



                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/>

                            <div id="toPopup<?php echo $key;?>" class="toPopup" style="top: 20%;">

                                <div class="close"></div>

                                <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

                                <div id="popup_content"> <!--your content start-->

                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">

                                <tr>

                                    <td colspan="6"><h6><?php echo "PASSENGER DETAILS";?></h6></td>

                                </tr>



                                    <tr>

                                        <td colspan="6">

                                            <table>

                                                <tr>

                                                    <td><?php echo "CONTACT #";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <input name="txtcontact[]" id="txtcontact<?php echo $key;?>" class="textfield5 txtcontact" type="text" value="<?php echo $vPhone[$key];?>">

                                                    </label></td>



                                                    <td><?php echo "DATE OF BIRTH";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <input name="txtdob[]" id="txtdob<?php echo $key;?>" class="txtdob" type="text" value="<?php echo $dDOB[$key];?>"></label></td>



                                                   

                                                </tr>



                                                <tr>

                                                    <td><?php echo "PASSPORT #";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <input name="txtpassport[]" id="txtpassport<?php echo $key;?>" class="textfield5 txtpassport" type="text" value="<?php echo $vPassportNo[$key];?>">

                                                    </label></td>



                                                    <td><?php echo "NATIONALITY";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <input name="txtnation[]" id="txtnation<?php echo $key;?>" class="textfield5 txtnation" type="text" value="<?php echo $vNationality[$key];?>">

                                                    </label></td>




                                                </tr>



                                                <tr>

                                                    <td><?php echo "DATE OF ISSUE";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                       
                                                        <input name="txtdoi[]" id="txtdoi<?php echo $key;?>" class="txtdoi" type="text" value="<?php echo $dVisaIssueDate[$key];?>">

                                                    </label></td>



                                                    <td><?php echo "DATE OF EXPIRY";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <input name="txtdoe[]" id="txtdoe<?php echo $key;?>" class="txtdoe" type="text" value="<?php echo $dVisaExpiryDate[$key];?>">

                                                    </label></td>




                                                </tr>



                                                <tr>

                                                    <td><?php echo GENDER;?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <?php echo form_dropdown('ddlgender[]', $list_gender_type, $vGender[$key], "id='ddlgender'.$key");?>

                                                    </label></td>


                                                    <td><?php echo "MARTIAL STATUS";?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <?php echo form_dropdown('mstatus[]', $list_mstatus_type, $vMaritalStatus[$key], "id='mstatus'.$key");?>

                                                    </label></td>

                                                </tr>
												 <tr>

                                                    <td><?php echo AGENT;?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <?php echo form_dropdown('ddlAgent[]', $list_agent_type, $nAgentId[$key], "id='ddlAgent'.$key");?>

                                                    </label></td>


                                                    <td><?php echo CUSTOMER;?> <span class="warning">*</span></td>

                                                    <td><label>

                                                        <?php echo form_dropdown('ddlcustomer[]', $list_customer_type, $nCustomerId[$key], "id='ddlcustomer'.$key");?>

                                                    </label></td>

                                                </tr>



                                                

                                            </table>

                                        </td>

                                    </tr>



                                </table>

                                </div> <!--your content end-->

                            </div>

                        </td>

                    </tr>

                    <?php

                endforeach;

            }//end if

            else

            {?>

                <tr>

                    <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></td>

                   
                    <td><label>

                        <input name="txtPass[]" id="txtPass" class="textfield txtPass" type="text">

                    </label></td>





                    <td><a href="#" class="topopup" id=""><span style="font-size: 12px;font-weight: bold;"><?php echo DETAILS;?></span></a></td>



                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_" title="Delete"/>

                        <div id="toPopup" class="toPopup" style="top: 20%;">

                            <div class="close"></div>

                            <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

                            <div id="popup_content"> <!--your content start-->

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">

                            <tr>

                                <td colspan="6"><h6><?php echo "PASSENGER DETAILS";?></h6></td>

                            </tr>

                                <tr>

                                    <td colspan="6">

                                        <table>

                                            <tr>
												<td><?php echo "CONTACT #";?> <span class="warning">*</span></td>

                                                 <td><label>

                                                    <input name="txtcontact[]" id="txtcontact" class="textfield5 txtcontact" type="text">

                                                </label></td>



                                                <td><?php echo "DATE OF BIRTH";?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <input name="txtdob[]" id="txtdob" class="txtdob" type="text"></label>

                                                </td>



                                                

                                            </tr>



                                            <tr>

                                                <td><?php echo "PASSPORT #";?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <input name="txtpassport[]" id="txtpassport" class="textfield5 txtpassport" type="text">

                                                </label></td>



                                                <td><?php echo "NATIONALITY";?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <input name="txtnation[]" id="txtnation" class="textfield5 txtnation" type="text">

                                                </label></td>



                                            </tr>



                                            <tr>

                                                <td><?php echo "DATE OF ISSUE";?> <span class="warning">*</span></td>

                                                <td><label>

                                                      <input name="txtdoi[]" id="txtdoi" class="txtdoi" type="text">

                                                </label></td>



                                                <td><?php echo "DATE OF EXPIRY";?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <input name="txtdoe[]" id="txtdoe" class="txtdoe" type="text">

                                                </label></td>



                                                
												

                                            </tr>
											
											<tr>

                                                <td><?php echo GENDER;?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <?php echo form_dropdown('ddlgender[]', $list_gender_type, $this->ddlgender, 'id="ddlgender"');?>

                                                </label></td>



                                                <td><?php echo "MARTIAL STATUS";?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <?php echo form_dropdown('mstatus[]', $list_mstatus_type, $this->mstatus, 'id="mstatus"');?>

                                                </label></td>

                                            </tr>



                                            <tr>

                                                <td><?php echo AGENT;?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <?php echo form_dropdown('ddlAgent[]', $list_agent_type, $this->ddlAgent, 'id="ddlAgent"');?>

                                                </label></td>



                                                <td><?php echo CUSTOMER;?> <span class="warning">*</span></td>

                                                <td><label>

                                                    <?php echo form_dropdown('ddlcustomer[]', $list_customer_type, $this->ddlcustomer, 'id="ddlcustomer"');?>

                                                </label></td>

                                            </tr>



                                           

                                        </table>

                                    </td>

                                </tr>

                            </table>

                            </div> <!--your content end-->

                        </div>

                    </td>



                </tr>

                <?php

            }//end else

            ?>

            </tbody>

        </table>

    </td>

</tr>








<tr class="formButs">

    <td>&nbsp;</td>

    <td colspan="9"><label>

       
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />

        <?php if ($this->uri->segment(4) == 'a') { ?>

        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>"/>

        <?php }//end if?>

        <input type="reset" value="<?php echo RESET;?>"/>

        <input type="button" name="btnCancel" value="<?php echo BACK;?>" id="btnCancel">

        
    </label>

        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>

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

