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



function getLocation(nVal){

    var locVal = $('#ddlLocation').val();

    $.ajax({

        type:'POST',

        data:{ddlDivision:nVal},

        url:'<?php echo base_url().'index.php/sales_receipt/filterDivision/'.$this->uri->segment(3);?>',

        dataType:'html',

        beforeSend: function() {

            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

        },

        success: function(response) {

            $('#loc').html(response);

            $('#ddlLocation').val(locVal);

            changeType();

            $('.ddlTkt').each(function(){

                var id     = this.id;

                var nVal   = $('#'+id).val();

                var getId  = id.split('ddlTkt');

                var editable = $("#txtEditable" + getId[1]).val();

                if(editable == 'Y' || editable == '')

                changeTicket(getId[1]);

                });



        }





    });

}





function changeType(){              

 <?php if($this->uri->segment(4) == 'a'):?>

     var loc  = $('#ddlLocation').val();

     $.ajax({

         type:'POST',

         data:{ddlLocation:loc},

         url:'<?php echo base_url().'index.php/sales_receipt/getDocumentNumber/'.$this->uri->segment(3);?>',

         dataType:'html',

         beforeSend: function() {

             $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

         },

         success: function(response) {

             $('#txtDocNumber').val(response.replace(/^\s+|\s+$/g, ''));

         }

     });



<?php endif; ?>

}



function changeTicket(id){

        var loc  = $('#ddlLocation').val();

        var div = $('#ddlDivision').val();



        $.ajax({

            type:'POST',

            url:'<?php echo base_url().'index.php/sales_receipt/filterTicket/'.$this->uri->segment(3);?>',

            data:{ddlLocation:loc,ddlDivision:div},

            dataType:'html',

            beforeSend: function() {

                $('#ddlTkt'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

            },

            success: function(response) {

                //alert(response);

                $('#ddlTkt'+id).html(response);

            }

        });

}







$(document).ready(function(){



    

    $("ul.pgcontent").quickPagination({pageSize:"5"});



	$('#btnCancel').click(function(){

		window.location.replace('<?php echo base_url().'index.php/sales_receipt/showlist/'.$this->uri->segment(3);?>');

		return false;

	});

	

	$("#txtDate1").datepick({ dateFormat: 'dd-mm-yyyy'});



		

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

	$.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg != value;
 }, "Value must not equal arg.");

 // configure your validation
 $("frmGroup").validate({
  rules: {
   ddlCard: { valueNotEquals: "default" }
  },
  messages: {
   ddlCard: { valueNotEquals: "Please select an item!" }
  }  
 });

	// validate signup form on keyup and submit

	$("#frmValidate").validate({

		rules: {

			 ddlDivision: {

					selectNone : true

					},

			 ddlLocation: {

					selectNone : true

					}
		},

		messages: {

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

                var nVal1 = -1;

                nVal1 = id.indexOf('ddlTkt');

                if(nVal1 != -1 ){

                    var getId  = id.split('ddlTkt');

                    changeTicket(getId[1]+i);

				}//end if

            }).end().find("textarea").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': function(_, id2) { return id + i; },

                    'value': ''

                });

            }).end().find("img").each(function() {

                var id = this.id;

                $(this).attr({

                    'id': 'del'

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

                url:'<?php echo base_url().'index.php/sales_receipt/deleteRecord/'.$this->uri->segment(3);?>',

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



    $('#loc').change(function(){

                changeType();

            $('.ddlTkt').each(function(){

                var id     = this.id;

                var nVal   = $('#'+id).val();

                var getId  = id.split('ddlTkt');

                var editable = $("#txtEditable" + getId[1]).val();

                if(editable == 'Y' || editable == '')

                changeTicket(getId[1]);

        });



     });



    $('.ddlTkt').click(function(){

            $('#ddlTkt').each(function(){

                var id     = this.id;

                var nVal   = $('#'+id).val();

                var getId  = nVal.split('-');

                if(nVal != 0){
					tktNum1=getId[1];
//alert(tktNum1);
//alert(id);
                    $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefName/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},

                            dataType:'text',
								//alert(tktNum);
                            success: function(response) {
								
                               $('#txtRefName').val(response.replace(/^\s+|\s+$/g, ''));
							   $('#pass').val(response.replace(/^\s+|\s+$/g, ''));

                            }

                        });
				
						
					 $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefNamt/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},
//alert(getId[0]);
                            dataType:'text',

                            success: function(response) {

                                $('#dcNAmt').val(response.replace(/^\s+|\s+$/g, ''));
								
								
                            }

                        });
	

                    }

                    return false;

                });
				$('#ddlTkt1').each(function(){

                var id     = 'ddlTkt1';//this.id;

                var nVal   = $('#'+id).val();

                var getId  = nVal.split('-');

                if(nVal != 0){
					tktNum1=getId[1];
//alert(tktNum1);
//alert(id);
                    $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefName/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},

                            dataType:'text',
								//alert(tktNum);
                            success: function(response) {
								
                              //  $('#txtRefName').val(response.replace(/^\s+|\s+$/g, ''));
							   $('#pass1').val(response.replace(/^\s+|\s+$/g, ''));
                            }

                        });
				
						
					 $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefNamt/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},
//alert(getId[0]);
                            dataType:'text',

                            success: function(response) {

                                $('#dcNAmt1').val(response.replace(/^\s+|\s+$/g, ''));
								
								
                            }

                        });
	

                    }

                    return false;

                });
				$('#ddlTkt12').each(function(){

                var id     = 'ddlTkt12';//this.id;

                var nVal   = $('#'+id).val();

                var getId  = nVal.split('-');

                if(nVal != 0){
					tktNum1=getId[1];
//alert(tktNum1);
//alert(id);
                    $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefName/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},

                            dataType:'text',
								//alert(tktNum);
                            success: function(response) {
								
                              //  $('#txtRefName').val(response.replace(/^\s+|\s+$/g, ''));
															   $('#pass12').val(response.replace(/^\s+|\s+$/g, ''));
                            }

                        });
				
						
					 $.ajax(

                        {

                            type:'POST',

                            url:'<?php echo base_url().'index.php/sales_receipt/getRefNamt/'.$this->uri->segment(3);?>',

                            data:{tktNum:getId[0],tktType:getId[1]},
//alert(getId[0]);
                            dataType:'text',

                            success: function(response) {

                                $('#dcNAmt12').val(response.replace(/^\s+|\s+$/g, ''));
								
								
                            }

                        });
	

                    }

                    return false;

                });

        });
		



    $('#ddlDivision').change(function() {

        var nVal  = $('#ddlDivision').val();

        getLocation(nVal);

    });



        getLocation($('#ddlDivision').val());





	$('#txtRemarks').keyup(function(){

		var nVal = $('#txtRemarks').val();

		$('.txtDes').val(nVal);

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

<?php echo form_open('sales_receipt/editSalesreceipt/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>

<?php //echo form_hidden('idArray', $chkDetailID);?>



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

             <input name="txtDate1" id="txtDate1" type="text" value="<?php echo $this->txtDate1;?>" class="pricebox">

            </label></td>



            <td><?php echo TRANSNAME;?></td>

            <td><label>

                <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"

                       value="<?php echo $this->txtDocNumber;?>" size="20" readonly="readonly">

            </label></td>





        <tr>

            <td><?php echo REFNAME;?></td>

            <td><label>

                <input name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">

            </label></td>



            <td><?php echo ENTEREDBY;?></td>

            <td><label>

                <input name="txtEntered" id="txtEntered" type="text" value="<?php echo $this->txtEntered;?>" class="pricebox" readonly>

            </label></td>

        </tr>



		<tr>

            <td><?php echo REMARKS;?></td>

            <td><label>

                <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php  echo $this->txtRemarks;?></textarea>

            </label></td>

        </tr>

                

        <tr>

        	<td colspan="5">

                <table id="dataTable" title="dataTable">

                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />

                <tr>

                   

                    <td><?php echo CASHBANK;?><span class="warning">*</span></td>
					<td><?php echo TICKET;?></td>
					
                    <td><?php echo 'Passanger';?></td>
					
                    <td><?php echo 'NET AMT';?></td>
                     <td><?php echo AMOUNT;?></td>

                    <td>&nbsp;<td>

                </tr>

                <?php

                if(!empty($chkCard))

                {

                    foreach($chkCard as $key => $aitem):

                ?>

                <tr>

                    <div class="hidden"><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></div>   

                        <input type="hidden" name="rowId[]" id="rowId" class="rowId" value="<?php echo $chkDetailID[$key];?>"></td>

                                 

                    



                    <td><?php echo form_dropdown('ddlCard[]', $list_creditcard, $chkCard[$key],'id="ddlCard'.$key.'" class="ddlCard"');?></td>



                    <?php 

                    $ticket = '';
						$namt='';
                    switch ($chkTktType[$key])

                    {

                        case "Tkt":

                            $ticket = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTkt[$key]."'");
							$pass = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$chkTkt[$key]."'");
				$namt = $this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId = ".$chkTkt[$key]." ");
$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$chkTkt[$key]."' and vType = 'Tkt' ");

                        break;



                        case "Xo":
							$namt = $this->common_model->get_field_name('XoBooking','vGrandNetFare'," WHERE nXId = ".$chkTkt[$key]." ");
							$pass = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId = ".$chkTkt[$key]." ");
                            $ticket = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTkt[$key]."'");
$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$chkTkt[$key]."' and vType = 'Xo' ");

                        break;



                        case "Htl":
							$pass = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId = ".$chkTkt[$key]." ");
							$namt = $this->common_model->get_field_name('HotelVoucher','vTotalDC'," WHERE nHVId = ".$chkTkt[$key]." ");
                            $ticket = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTkt[$key]."'");
							$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$chkTkt[$key]."' and vType = 'Htl' ");


                        break;

                    }//end switch

                    ?>



  <td> <?php echo form_dropdown('ddlTkt[]', array($chkTkt[$key].'-'.$chkTktType[$key] => $ticket), $chkTkt[$key].'-'.$chkTktType[$key],"id='ddlTkt".$key."' class='ddlTkt'");?></td>

  <td><input name="txtpass[]" id="pass"  type="text" value="<?php echo $pass;?>"></td>
  <td><input name="txtNAmount[]" id="dcNAmt<?php echo $key;?>" class="textfield6" type="text" value="<?php echo $namt;?>"></td>

<td><input name="txtAmount[]" id="dcAmt<?php echo $key;?>" class="textfield6" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
                            <input type="hidden" name="txtEditable[]" id="txtEditable<?php echo $key;?>" value="N" />



                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>

                </tr>

                <?php

                    endforeach;

                }//end if

                else

                { ?>

                <tr>

                        <div class="hidden"><input type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></div>

                        <input type="hidden" name="rowId[]" id="rowId" class="rowId" value=""></td>

                     

                   


                    <td><?php echo form_dropdown('ddlCard[]', $list_creditcard, '','id="ddlCard" class="ddlCard"');?></td>

                    <td><?php echo form_dropdown('ddlTkt[]', $list_tkt,'','id="ddlTkt" class="ddlTkt"');?></td>
				 
				  <td><input name="txtpass[]" id="pass"  type="text" value=""></td>
				  <td><input name="txtNAmount[]" id="dcNAmt" class="textfield6" type="text" value=""></td>
				 <td><input name="txtAmount[]" id="dcAmt<?php echo $key;?>" class="textfield6 dcAmt" type="text" required="required" value=""><span class="warning">*</span></td>

                    <input type="hidden" name="txtEditable[]" id="txtEditable" value="Y" />





                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_>" title="Delete"/></td>

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

      <?php 
	 $aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			
	
               if(!empty($status_s_k)){
	
	$b=1;
              }
              else {

	$b=0;
               }?>

        <tr class="formButs">

            <td>&nbsp;</td>

            <td colspan="5"><label>
<?php if($b==0 || $aid==1){ ?>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
<?php }else{?>
 <input type="button"  value="<?php echo $this->btnVal;?>" />
<?php }?>
              <?php if($this->uri->segment(4) == 'a'){?>

                <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />

              <?php }//end if?>

              <input type="reset" value="<?php echo RESET;?>" />

              <input type="button" name="btnCancel" value="<?php echo BACKTOSALESRECEIPT;?>" id="btnCancel">

            </label>

                <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>

        </tr>

    </tbody>

  </table>



  </form>

  </div>

