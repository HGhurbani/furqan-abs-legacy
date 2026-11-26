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

function changeAllAccount(loc,id){
    var div = '';
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/jv/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccount2'+id).val();
                 $('#ddlAccount2'+id).html(response);
                 $('#ddlAccount2'+id).val(acc);
         }
     });
}

function changeType(){
 <?php if($this->uri->segment(4) == 'a'):?>
     var loc  = $('#ddlLocation').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc},
         url:'<?php echo base_url().'index.php/jv/getDocumentNumber/'.$this->uri->segment(3);?>',
         dataType:'html',
         beforeSend: function() {
             $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
         },
         success: function(response) {
             $('#txtDocNumber').val(response);
         }
     });
<?php endif; ?>
}


$(document).ready(function(){

    $('#ddlCurrencyMain').change(function()
    {
        var nVal = $('#ddlCurrencyMain').val();
        $.ajax({
            type:'POST',
            data:{ddlCurrency:nVal},
            url:'<?php echo base_url().'index.php/jv/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#txtRate').val(response);
            }
        });
    });

    $('#txtAmountFCMain').keyup(function()
    {
        var nVal  = $('#ddlCurrencyMain').val();
        var fcAmt = $('#txtAmountFCMain').val();
        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/jv/convertCurrency/'.$this->uri->segment(3);?>',
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
	
 $('#btnSaveExit,#btnGo').click(function(e) {
            var chk = $('#txtAmountDC').val();
            var txtTotal = 0;
            $('.fcRate').each(function () {
                var nVal2 = $(this).val();
                if (nVal2 == '') {
                    nVal2 = 0;
                }
                txtTotal = parseFloat(txtTotal)+parseFloat(nVal2);
            });
			alert(txtTotal );
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
		
    //accoutns currency converstion calcualtion
    $('.textfield4').keyup(function(e) {
        var id    = this.id;
        var fcAmt = $('#'+id).val();
        var nVal = $('#ddlCurrencyMain').val();
        $.ajax({

                type:'POST',
                url:'<?php echo base_url().'index.php/jv/convertCurrency/'.$this->uri->segment(3);?>',
                data:{fcAmt:fcAmt,selCurrency:nVal},
                dataType:'html',
                beforeSend: function() {
                    $('#txtAmount'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $('#txtAmount'+id).val(obj.covAmt);
                    $('#txtExchange'+id).val(obj.exRate);
                }
            });
    });

    $("ul.pgcontent").quickPagination({pageSize:"5"});
    $('#btnCancel').click(function(){
        window.location.replace('<?php echo base_url().'index.php/jv/showlist/'.$this->uri->segment(3);?>');
        return false;
    });

    $("#txtDate1").datepick({ dateFormat: 'dd-mm-yyyy'});
    $("#txtRefDate").datepick({ dateFormat: 'dd-mm-yyyy'});
    $("#txtAprDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	
	jQuery.validator.addMethod("notequal", function(value, element) {
		
		if ($('#new').val() == 0)
            {
                return true;
            }
            else return false;
        }, 
 "* Not Equal ");

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
			new1: {
			required: true,
			notequal: true
			},
            ddlDivision: {
                selectNone : true
            },
			'ddlMode[]': {
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
			'ddlMode[]': {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
			new1: {
                notequal : "<?php echo '* Not Equal '?>"
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
                var id = this.id;
                var newid = id.split('ddlAccount2');
                if(newid[0] == '')
                {
                    if(newid[1] != undefined)
                        newid = newid[1];
                    if(parseInt(newid))
                    {
                        newid = newid;
                        $('.btnAllocation').attr('title',newid+i);
                    }//end if
                    else
                    {
                        $('.btnAllocation').attr('title','1');
                    }//end else
                }//end if

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
            $.ajax({
                    type:'POST',
                    url:'<?php echo base_url().'index.php/jv/deleteRecord/'.$this->uri->segment(3);?>',
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

    $('.ddlCurrency').change(function()
    {
        var id     = this.id;
        var nVal   = $('#'+id).val();
        var getId  = id.split('ddlCurrency');
        var curVal = $('#ddlCurrency'+getId[1]).val();
        $.ajax({
            type:'POST',
            data:{ddlCurrency:curVal},
            url:'<?php echo base_url().'index.php/jv/getCurrencyRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#exRate'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#exRate'+getId[1]).val(response);
            }
        });
    });
/*
    $('dcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('fcAmt');
        var exVal = $('#exRate' + getId[1]).val();
        var fcAmt = $('#fcAmt' + getId[1]).val();
        //var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
        fcAmt = fcAmt != '' ? fcAmt : 1 ;
        var dcVal = parseFloat(fcAmt) / parseFloat(exVal);
        $('#dcAmt' + getId[1]).val(parseFloat(dcVal).toFixed(2));
        $('#exRate' + getId[1]).val(exVal);
    });
*/ 

    $('.dcRate').keyup(function(e)
    {var a=0; var b=0; var c=0;var d=0; var i=0;
        var id    = this.id;
        var getId = id.split('fcAmt');
        var exVal = $('#exRate' + getId[1]).val();
        var fcAmt = $('#fcAmt' + getId[1]).val();
        fcAmt = fcAmt != '' ? fcAmt : 0 ;		var dcVal = parseFloat(fcAmt) / parseFloat( exVal);		
		$('#dcAmt' + getId[1]).val(parseFloat(dcVal).toFixed(2));		
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();		var m2=$('#ddlMode2').val();		var m3=$('#ddlMode3').val();		
        //var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
      a= $('#fcAmt0').val();
		
	b= $('#fcAmt1').val();
	//c= $('#fcAmt2').val();
	//d=$('#fcAmt3').val();
	//c= parseInt(a)- parseInt(b);
      
	   //alert(m1);
	   
	   $('#new').val(b-a);
	     
 //       $('#exRate' + getId[1]).val(exVal);
    });
 

$('#fcAmt2').keyup(function(e)
    {var a=0; var b=0; var c=0;var d=0; var i=0;
       	
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();		var m2=$('#ddlMode2').val();		var m3=$('#ddlMode3').val();		
       		
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	   if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	    if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
	   }
	  
	  
	  // alert (a + ', ' + b);
       $('#new').val(b-a);
   	   
 //       $('#exRate' + getId[1]).val(exVal);
    });
$('#fcAmt3').keyup(function(e)
    {var a=0; var b=0; var c=0;var d=0; var i=0;
       	
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();		var m2=$('#ddlMode2').val();		var m3=$('#ddlMode3').val();		
       		
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	   if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	    if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
	   }
	   if(m3=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt3').val());		
		
	}else {
		  var z=parseInt($('#fcAmt3').val());
		b=b+(z);
	   }
	  
	  
	  // alert (a + ', ' + b);
       $('#new').val(b-a);
 	   	   
 //       $('#exRate' + getId[1]).val(exVal);
    });	
 $('#ddlMode1').change(function(e)
    {
		var a=0; var b=0;
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();
		var m2=$('#ddlMode2').val();
		var m3=$('#ddlMode3').val();		
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			//$('#new').val(a);
  //$('#new').val(0);			
		
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	    if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	    if(m2!=0){ if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
		}}
	  if(m3!=0){ if(m3=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt3').val());		
		
	}else {
		  var z=parseInt($('#fcAmt3').val());
		b=b+(z);
		}}
	  
	   
       $('#new').val(b-a);	
	      
	});
	
	$('#ddlMode0').change(function(e)
    {
		var a=0; var b=0;
	var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();
		var m2=$('#ddlMode2').val();
var m3=$('#ddlMode3').val();		
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			//$('#new').val(a);
  //$('#new').val(0);			
		
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	    if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	   if(m2!=0){ if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
	   }}
	  
	  if(m3!=0){ if(m3=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt3').val());		
		
	}else {
		  var l=parseInt($('#fcAmt3').val());
		b=b+(l);
		}}
	  
	   
       $('#new').val(b-a);	
	   
	   
	});
	
	 $('#ddlMode2').change(function(e)
    {
		var a=0; var b=0;
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();
		var m2=$('#ddlMode2').val();		
		var m3=$('#ddlMode3').val();
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	   if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	    if(m2!=0){ if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
		}}
		if(m3!=0){ if(m3=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt3').val());		
		
	}else {
		  var z=parseInt($('#fcAmt3').val());
		b=b+(z);
		}}
	  
	  
	  alert (a + ', ' + b);
       $('#new').val(b-a);
 	
	      
	});
	$('#ddlMode3').change(function(e)
    {
		var a=0; var b=0;
		var m0=$('#ddlMode0').val();		
		var m1=$('#ddlMode1').val();
		var m2=$('#ddlMode2').val();
var m3=$('#ddlMode3').val();		
		if(m0=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt0').val());	
			
	}else{			
		b=parseInt(b)+parseInt($('#fcAmt0').val());
	   }
	   if(m1=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt1').val());		
		
	}else{	
		var x=parseInt($('#fcAmt1').val());
		b=b+(x);
	   }
	    if(m2!=0){ if(m2=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt2').val());		
		
	}else {
		  var y=parseInt($('#fcAmt2').val());
		b=b+(y);
		}}
		 
		 if(m3!=0){ if(m3=='Cr'){					
			a=parseInt(a)+parseInt($('#fcAmt3').val());		
		
	}else {
		  var z=parseInt($('#fcAmt3').val());
		b=b+(z);
		}}
	  
	  
	  // alert (a + ', ' + b);
       $('#new').val(b-a);	
	  
	});
    
    //single amount dc calculation
    $('#txtAmountFCMain').keyup(function(e)
    {
        var nVal   = $('#txtAmountFCMain').val();
        var nVal2  = $('#txtRate').val();
        var total  = parseFloat(nVal) * parseFloat(nVal2);
        $('#txtAmountDC').val(total);
    });



    $('.fcRate').keyup(function(e)
    {
        var id    = this.id;
        var getId = id.split('dcAmt');
        var exVal = $('#exRate' + getId[1]).val();
        var fcAmt = $('#dcAmt' + getId[1]).val();
        //var dcVal = parseFloat(fcAmt) / parseFloat( exVal);
        fcAmt = fcAmt != '' ? fcAmt : 1 ;
        var dcVal = parseFloat(fcAmt) * parseFloat( exVal);
        $('#fcAmt' + getId[1]).val(dcVal);
        $('#exRate' + getId[1]).val(exVal);
    });

<?php if($this->uri->segment(4) == 'a')

{?>

    $.ajax({
        type:'POST',
        data:{ddlDivision:$('#ddlDivision').val()},
        url:'<?php echo base_url().'index.php/jv/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            changeType();
        }
    });

    <?php }//end if
else {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:'<?php echo $this->ddlDivision;?>',ddlLocation:'<?php echo $this->ddlLocation;?>'},
        url:'<?php echo base_url().'index.php/jv/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            changeType();

        }
    });
    <?php }//end else?>

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

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        $.ajax({
                type:'POST',
                data:{ddlDivision:nVal,ddlTktname:0},
                url:'<?php echo base_url().'index.php/jv/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                    changeType();

                }
            });
    });
    $('#loc').change(function(){
                    changeType();
     });


    $('.ddlAccount2').change(function()
    {
        var id    = this.id;
        var getId = id.split('ddlAccount2');
        var nVal  = $('#ddlAccount2'+getId[1]).val();
        var accId = nVal.split("-");
        $.ajax({
                type:'POST',
                data:{ddlAccount2:accId[0],tableType:accId[1]},
                url:'<?php echo base_url().'index.php/jv/filterSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('.btnAllocation').attr('title',getId[1]);
                    $('#ddlSubAccount2'+getId[1]).html(response);
                }
            });
    });

    //single amount fc calculation
    $('#txtAmountDC').keyup(function(e)
    {
        var nVal   = $('#txtAmountDC').val();
        var nVal2  = $('#txtRate').val();
        var total  = parseFloat(nVal) / parseFloat(nVal2);
        $('#txtAmountFCMain').val(total);
    });
    $('#btnAllocation').click(function(){
		$('#toPopup1').show();
        var id     = this.title;
        if(id=='')
        {
            var accVal = $('.ddlAccount2').val();
            var nVal2 = $('.ddlSubAccount2').val();
            var accId  = accVal.split("-");
        }
        else{
            var nVal1  = $('#ddlAccount2'+id).val();
            var accId  = nVal1.split("-");
            var nVal2  = $('#ddlSubAccount2'+id).val();
        }

        $.ajax({
            type:'POST',
            data:{ddlAccount2:accId[0],tableVal:accId[1],ddlSubAccount2:nVal2,idVal:id},
            url:'<?php echo base_url().'index.php/jv/getAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
            dataType:'html',
            success: function(response) {
                $('#popup_content').html(response);
            }
        });
    });

    $('.chkSelect').click(function(){
        var id    = this.id;
        var getId = id.split('chkSelect');
        $('.btnAllocation').attr('title',getId[1]);
        if ($('#'+id).is(':checked')){
            $("#"+id).attr('disabled', 'disabled');
        }
    });

    <?php
    if(intval($this->uri->segment(6)) != 0 ):
        if($this->uri->segment(5) == 'edit_suc' || $this->uri->segment(5) == 'add_suc'):?>
        window.open('<?php echo base_url().'index.php/jv/printAgentPreview/'.$this->uri->segment(6);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
    <?php endif;endif;?>

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
<?php echo form_open('jv/editJv/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>
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

<!--    <td><?php echo CASHRECEIPTTYPE;?></td>
    <td>
        <?php echo form_dropdown('ddlTransaction', $list_trans_value, $this->ddlTransaction, 'id="ddlTransaction"');?>
    </td>
-->
    <td><?php echo TRANSNAME;?></td>
    <td><label>
        <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text" value="<?php echo $this->txtDocNumber;?>" size="20" readonly="readonly">
    </label></td>
</tr>

<tr>
    <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlCurrencyMain', $list_curr_value, $this->ddlCurrencyMain, 'id="ddlCurrencyMain" class="ddlCurrencyMain chosen-select" ');?>
    </label></td>
    <td><?php echo RATE;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtRate" id="txtRate" class="textfield  pricebox" type="text" value="<?php echo stripslashes($this->txtRate);?>" size="2">
    </label></td>
</tr>
<tr>
    <td><?php echo APPRNUMBER;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtAprNum" id="txtAprNum" type="text" value="<?php echo $this->txtAprNum;?>" size="40" class="pricebox">
    </label></td>

    <td><?php echo APPRDATE;?></td>
    <td><label>
        <input name="txtAprDate" id="txtAprDate" type="text" value="<?php echo $this->txtAprDate;?>" size="40" class="pricebox">
    </label></td>
</tr>

<tr>


    <td><?php echo AMOUNTDC;?></td>
    <td><label>
        <input name="txtAmountDC" id="txtAmountDC" type="text" value="<?php echo $this->txtAmountDC;?>" class="pricebox">
    </label></td>

    <td><?php echo AMOUNTFC;?></td>
    <td><label>
        <input name="txtAmountFCMain" id="txtAmountFCMain" type="text" value="<?php echo $this->txtAmountFCMain;?>" class="pricebox" tabindex="-1">
    </label></td>
</tr>

<tr>
    <td><?php echo REFNUMBER;?></td>
    <td><label>
        <input name="txtRefNumberMain" id="txtRefNumberMain" type="text" value="<?php echo $this->txtRefNumberMain;?>" size="40" class="pricebox">
    </label></td>
    <td><?php echo REFDATE;?></td>
    <td><label>
        <input name="txtRefDate" id="txtRefDate" type="text" value="<?php echo $this->txtRefDate;?>" size="40" class="pricebox">
    </label></td>
</tr>
<tr>
    <td><?php echo REFNAME;?></td>
    <td><label>
        <input name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">
    </label></td>

    <td><?php echo ENTEREDBY;?></td>
    <td><label>
        <input name="txtEntered" id="txtEntered" type="text" value="<?php echo $this->txtEntered;?>" class="pricebox">
    </label></td>
</tr>

<tr>
    <td><?php echo REMARKS;?></td>
    <td><label>
        <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->txtRemarks;?></textarea>
    </label></td>
</tr>

<tr>
    <td colspan="10">
        <table id="dataTable" title="dataTable">
            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew"  tabindex="-1" />
            <tr>
                
                <td><?php echo LOCATIONNAME;?></td>
                <td><?php echo ACCCODENAME;?></td>
                <td><?php echo SUBCODE;?></td>
                <td><?php echo BUDGETMODE;?></td>
                <td><?php echo REFNUMBER;?></td>
                <td><?php echo CURRNAME;?></td>
                <td><?php echo EXCHANGERATE;?></td>
                <td><?php echo AMOUNTDC;?></td>
                <td><?php echo AMOUNTFC;?></td>
            </tr>
            <?php
            if(!empty($chkLoc))
            {
                foreach($chkLoc as $key => $aitem):
                    ?>
                    <tr>
                        <!--<td><input type="checkbox" checked name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>-->
                        <td><label>
                            <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $chkLoc[$key], "id='ddlLocation2".$key."' class='ddlLocation2'");?>
                        </label>
                        </td>
                        <td><label>
                            <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $chkAccCode[$key], "id='ddlAccount2".$key."' class='ddlAccount2'");?>
                        </label></td>
                        <td><label>
                            <?php echo form_dropdown('ddlSubAccount2[]', $this->jv_model->filterSubAccount($chkAccCode[$key],$chkTable[$key]), $chkAccSubCode[$key], 'id="ddlSubAccount2" class="ddlSubAccount2"');?>
                        </label></td>
                        <td><?php echo form_dropdown('ddlMode[]', $list_mode, $chkMode[$key],'id="ddlMode" class="ddlMode"');?></td>
                        <td><input name="txtRefNumber[]" id="txtRefNumber<?php echo $key;?>" class="textfield3" type="text" value="<?php echo stripslashes($chkRefNumber[$key]);?>"></td>
                        <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $chkCurr[$key],'id="ddlCurrency'.$key.'" class="ddlCurrency" class="singleDrop"');?></td>
                        <td><input name="txtExchange[]" id="exRate<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($chkExchangeRate[$key]);?>"></td>
                        <td><input name="txtAmount[]" id="fcAmt<?php echo $key;?>" class="textfield6 dcRate" type="text" value="<?php echo stripslashes($chkAmount[$key]);?>"></td>
                        <td><input name="txtAmountFC[]" id="dcAmt<?php echo $key;?>" class="textfield4 fcRate" type="text" value="<?php echo stripslashes($chkAmountFC[$key]);?>" tabindex="-1"></td>
                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                    </tr>
                    <?php
                endforeach;
            }//end if
            else
            {
                $i = 0;
                while ($i < 4)
                {?>
                <tr>
                   <!-- <td><input type="checkbox" name="chkbox[]" id="chkSelect<?php echo $i;?>" class="chkSelect" value=""  tabindex="-1"></td>-->
                    <td><label>
                        <?php echo form_dropdown('ddlLocation2[]', $list_mloc_value, $this->ddlLocation2,  "id='ddlLocation2".$i."' class='ddlLocation2'");?>
                    </label>
                    </td>
                    <td><label>
                        <?php echo form_dropdown('ddlAccount2[]', $list_account_multiple, $this->ddlAccount2, 'id="ddlAccount2'.$i.'" class="ddlAccount2"');?>
                    </label></td>
                    <td><label>
                        <?php echo form_dropdown('ddlSubAccount2[]', $list_subacc_multiple, $this->ddlSubAccount2, 'id="ddlSubAccount2'.$i.'" class="ddlSubAccount2"');?>
                    </label></td>
                    <?php if($i==0){?>					<td>					<select name="ddlMode[]" id="ddlMode<?php echo $i?>" class="ddlMode">					<option value="Dr">Debit</option>					<option value="Cr">Credit</option>					</select>					</td>					<?php }elseif($i==1){?>					<td><select name="ddlMode[]" id="ddlMode<?php echo $i?>" class="ddlMode">					<option value="Cr">Credit</option>					<option value="Dr">Debit</option>					</select></td>										<?php }else{?>					<td><?php echo form_dropdown('ddlMode[]', $list_mode, $this->ddlMode,'id="ddlMode'.$i.'" class="ddlMode"');?></td>										<?php }?>
                    <td><input name="txtRefNumber[]" id="txtRefNumber<?php echo $i;?>" class="textfield4" type="text" value=""></td>
                    <td><?php echo form_dropdown('ddlCurrency[]', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency'.$i.'" class="ddlCurrency"');?></td>
                    <td><input name="txtExchange[]" id="exRate<?php echo $i;?>" class="textfield5" type="text" value="<?php echo $this->exRate;?>"></td>
                    <td><input name="txtAmount[]" id="fcAmt<?php echo $i;?>" class="textfield6 dcRate" type="text" value=""></td>
                    <td><input name="txtAmountFC[]" id="dcAmt<?php echo $i;?>" class="textfield4 fcRate" type="text" value="" tabindex="-1"></td>
                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
                </tr>
                <?php
                    $i++;
                }//end while
            }//end else
            ?>
        </table>
    </td>
</tr>
<tr></tr>
<!--
<tr>
    <td><a href="#" class="topopup" id="1"><input type="button" name="btnAllocation" id="btnAllocation" value="<?php echo ALLOCATION;?>" class="btnAllocation" /></a></td>
</tr>
-->
<tr></tr>
<tr>
    <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
    <td colspan="5"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>	
		 <?php if($this->uri->segment(4) == 'a'){?>
        <td><input name="new1" id="new" class="textfield4" type="text" value=""><span class="warning">*</span></td>
        <?php }//end if?>
		
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="5"><label>
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
        <?php if($this->uri->segment(4) == 'a'){?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo BACKTOJV;?>" id="btnCancel">
    </label>
        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
</tr>
</tbody>
</table>

<div id="toPopup1" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content"> <!--your content start-->
        <?php //echo $this->load->view('jv/show_allocation');?>
    </div> <!--your content end-->
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
