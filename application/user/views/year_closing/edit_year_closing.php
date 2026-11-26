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

$(function() {

    function format(acVal) {

        return acVal.name;

    }



    $(".chkSuggestion").keypress(function(e)

    {

        var nId  = this.id;

        var nVal = $('#'+this.id).val();

        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/year_closing/suggestionlist', {

            multiple: false,

            dataType: "json",

            parse: function(data) {

                return $.map(data, function(row) {

                    return {

                        data: row,

                        value: row.name,

                        result: row.name

                    }

                });

            },

            formatItem: function(item) {

                return format(item);

            }

        }).result(function(e, item)

            {

                var nId  = this.id;

                $.ajax(

                    {

                        type:'POST',

                        url:'<?php echo base_url().'index.php/year_closing/getYearClosingDetails/'.$this->uri->segment(3);?>',

                        data:{txtAcccode:format(item)},

                        dataType:'html',

                        beforeSend: function() {

                            $('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                        },

                        success: function(response) {

                            $('#txtAccname'+nId).val(response);

                        }

                    });

            });

    });

});

$(document).ready(function()

{

	$('#btnCancel').click(function()

	{

		window.location.replace('<?php echo base_url().'index.php/year_closing/showlist/'.$this->uri->segment(3);?>');

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

	$("#dFDate").datepick({ dateFormat: 'yyyy-mm-dd'});

	$("#dTDate").datepick({ dateFormat: 'yyyy-mm-dd'});



	// validate signup form on keyup and submit

	$("#frmValidate").validate({

		rules: {

			 txtName: {

					required : true

			 		},

			 txtAddress: {

					required : true

			 		},

			 txtPhone: {

					required : true,

					number   : true

			 		},

			 txtMobile: {

					required : true,

					number   : true

			 		}

		},

		messages: {

			txtName: {

					required : "<?php echo VENDORNAME;?><?php echo CANTBEBLANK;?>"

					},

			txtAddress: {

					required : "<?php echo LOCATIONADDR;?><?php echo CANTBEBLANK;?>"

					},

			txtPhone: {

					required : "<?php echo COMPANYPHONE;?><?php echo CANTBEBLANK;?>",

					number   : "<?php echo VALIDPHONE;?>"

					},

			txtMobile: {

					required : "<?php echo CUSTMOBILE;?><?php echo CANTBEBLANK;?>",

					number   : "<?php echo VALIDPHONE;?>"

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

        var m	 = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {

            var id = this.id;

            $(this).attr({

                'id': function(_, id2) { return id + i; },

                'value': ''

            });

        }).end().find("select").each(function() {

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

                    url:'<?php echo base_url().'index.php/year_closing/deleteRecord/'.$this->uri->segment(3);?>',

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

});

</script>

<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>

<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.bgiframe.min.js'></script>

<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.ajaxQueue.js'></script>

<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox-compressed.js'></script>

<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.js'></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox.css" />



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

<?php echo form_open('year_closing/editYearClose/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>

<?php echo form_hidden('idArray', $chkID);?>

<?php echo form_hidden('nCheck', 'frmPost');?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">

    <tbody>

        <tr>

            <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>

            <td><label>

                <?php echo form_dropdown('ddlDivision', $list_div, $this->ddlDivision, 'id="ddlDivision"');?>

            </label></td>



            <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>

            <td><label>

                <input name="txtDate" id="txtDate" class="textfield" type="text" value="<?php echo date('Y-m-d');?>">

            </label></td>

        </tr>

        

        <tr>

            <td><?php echo YEARFROM;?> <span class="warning">*</span></td>

            <td><label>

             <input name="txtFYear" id="txtFYear" class="textfield" type="text" value="<?php echo stripslashes($this->txtFYear);?>">

            </label></td>

            

            <td><?php echo YEARTO;?> <span class="warning">*</span></td>

            <td><label>

             <input name="txtTYear" id="txtTYear" class="textfield" type="text" value="<?php echo stripslashes($this->txtTYear);?>">

            </label></td>

        </tr>

        

        <tr>

            <td><?php echo DATEFROM;?> <span class="warning">*</span></td>

            <td><label>

                <input name="dFDate" id="dFDate" class="textfield" type="text" value="<?php echo stripslashes($this->dFDate);?>" size="40">

            </label></td>

            

            <td><?php echo DATETO;?> <span class="warning">*</span></td>

            <td><label>

                <input name="dTDate" id="dTDate" class="textfield" type="text" value="<?php echo stripslashes($this->dTDate);?>" size="40">

            </label></td>

        </tr>

        <tr>
            <td colspan="10">
                <table id="dataTable" title="dataTable">
                    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
                    <?php
                    if(!empty($chkAccCode))
                    {
                        foreach($chkAccCode as $key => $aitem):
                            ?>
                            <tr>
                                <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                                <td><label>
                                    <input name="txtAcccode[]" id="txtAcccode<?php echo $key;?>" class="chkSuggestion" type="text" value="<?php echo stripslashes($aitem).' - '.stripslashes($chkAccCode[$key]);?>">
                                </label></td>

                                <td><?php echo DESCRIPTION;?> <span class="warning">*</span></td>
                                <td><label>
                                    <input name="txtDes[]" id="txtDes<?php echo $key;?>" class="textfield" type="text" value="<?php echo stripslashes($chkDes[$key]);?>">
                                </label></td>

                                <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
                                <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="ddlMode"');?></td>

                                <td><?php echo AMOUNTFC;?> <span class="warning">*</span></td>
								
								<?php 
								if($chkAmountFC[$key]==0 || $chkAmountFC[$key]==''){
									$chkAmountFC[$key]=$chkAmount[$key];
								}
								if($chkAmount[$key]==0 || $chkAmount[$key]==''){
									$chkAmount[$key]=$chkAmountFC[$key];
								}
								?>
                                <td><input name="txtAmountFC[]" id="fcAmt<?php echo $key;?>" class="textfield4 fcRate" type="text" value="<?php echo stripslashes($chkAmountFC[$key]);?>"></td>

                                <td><?php echo AMOUNTDC;?> <span class="warning">*</span></td>
                                <td><input name="txtAmount[]" id="dcAmt<?php echo $key;?>" class="textfield6" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>

                                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkYCId[$key];?>" title="Delete"/></td>
                            </tr>
                            <?php
                        endforeach;
                    }//end if
                    else
                    {?>
				 

					   <tr id="result">

                            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtAcccode[]" id="txtAcccode" class="chkSuggestion" type="text" value="">
                            </label></td>

                            <td><?php echo DESCRIPTION;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtDes[]" id="txtDes" class="textfield" type="text" value="">
                            </label></td>

                            <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
                            <td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode" class="ddlMode"');?></td>

                            <td><?php echo AMOUNTFC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmountFC[]" id="fcAmt" class="textfield4 fcRate" type="text" value=""></td>

                            <td><?php echo AMOUNTDC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmount[]" id="dcAmt" class="textfield6" type="text" value=""></td>

                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
                        </tr>
                        <?php
                    }//end else
                    ?>
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
               <!-- <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
				
                <?php if($this->uri->segment(4) == 'a'){?>
                <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
                <?php }//end if?>
                <input type="reset" value="<?php echo RESET;?>" />-->
                <input type="button" name="btnCancel" value="<?php echo BACKTOYEARCLOSE;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
