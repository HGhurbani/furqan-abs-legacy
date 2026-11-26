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
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/visa_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });
        $('.print_logo').hide();
        $('.print_desc').hide();
        $('#btnPrint').click(function(e) {
            //$('.print_logo').show();
            $('.print_desc').show();
//$('.formButs').hide();
            $('#texxt').printElement();
           $('.print_logo').hide();
            $('.print_desc').hide();
        });
        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
        $('#maincontent').scroll(function() {
            console.log(contentBoxExportPos)
            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
                contentBoxExport.addClass('fixed')
            }
            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
                contentBoxExport.removeClass('fixed')
            }
        })
    });
</script>

<div id="texxt">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
    <thead>
    <?php
    if(!empty($this->ddlLocation)):
        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
        $link   = base_url().'upload/location/'.$logo;
    elseif(!empty($this->ddlLocation2)):
        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation2."'");
        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation2."'");
        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation2."'");
        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation2."'");
        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation2."'");
        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation2."'");
        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation2."'");
        $link   = base_url().'upload/location/'.$logo;
    else:
        $logo   = $this->common_model->get_field_name('Division','vImg'," WHERE nId='".$this->ddlDivision."'");
        $name   = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$this->ddlDivision."'");
        $addr   = $this->common_model->get_field_name('Division','vAddress'," WHERE nId='".$this->ddlDivision."'");
        $phone  = $this->common_model->get_field_name('Division','vPhone'," WHERE nId='".$this->ddlDivision."'");
        $mobile = $this->common_model->get_field_name('Division','vMobile'," WHERE nId='".$this->ddlDivision."'");
        $email  = $this->common_model->get_field_name('Division','vEmail'," WHERE nId='".$this->ddlDivision."'");
        $web    = $this->common_model->get_field_name('Division','vWeb'," WHERE nId='".$this->ddlDivision."'");
        $link   = base_url().'upload/division/'.$logo;
    endif;
    ?>

    <tr>
        <th align="center" colspan="16" style="border:0">
            <div class="print_desc">
                <span style="font-size: 11px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
<tr>
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Visa Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        
    </tr>
   
<style type="text/css">
#br{
	display:none;}

width:100% !important;
  table { font-size: 9px; }
</style>
<style media="print">
  #bg{ background-color:#CCC !important; }
 #br{ display:block !important;}
</style>
<style>

td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>

 <tr id="bg" style="">
        <td height="20px" align="center">#</td>
        <td  align="center"><?php echo CUSTDATE;?></td>
        <td  align="center"><?php echo "Document";?></td>
		<td  align="center"><?php echo "Visa File";?></td>
        <td align="center" ><?php  echo PASSENGER;?></td>
        <td align="center"><?php echo "Remarks";?></td>				
        
       
        <td align="center"><?php echo "Vendor";?></td>
        <td align="center" ><?php echo 'NetAmt';?></td>		
        <td align="center" ><?php echo "SalesAmt";?></td>
		<td align="center" ><?php echo "Profit";?></td>
       
    </tr>
    </thead>
    <tbody>

	<?php
    if(!empty($list_value)){
		$i=1;
	foreach($list_value as $key=>$item){
		
	?>
	<tr style="">
	<?php if($doc==$item->vDocNumber){ ?>
	<td height="20px" align="center"></td>
        <td  align="center"></td>
        <td align="center"></td>
        <td align="center" ></td>
		 <td align="center" ></td>
		  <td align="center" ></td>
	
	
	<?php }else{?>
       <td height="20px" align="center"><?php echo $i;?></td>
        <td  align="center"><?php echo $item->dDate;?></td>
        <td align="center"><?php echo $item->vDocNumber;?></td>
        <td align="center" ><?php  echo $item->vTicktManual;?></td>
	
        <td align="left"><?php echo $item->vPassName;?></td>				
        <td align="center" ><?php echo $item->remarks;?></td>
		<?php $i=$i+1; }?>
        <td align="left"><?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->acc."'");?></td>
        <td align="center"><?php echo $item->netamt;?></td>
        <td align="center" ><?php echo $item->slsamt;?></td>
      
        <td align="center"><?php echo $item->profit;?></td>
        
       
    </tr>
	

	<?php 
	$doc=$item->vDocNumber;
	$netamt=$netamt+$item->netamt;
	$slsamt=$slsamt+$item->slsamt;
	$profit=$profit+$item->profit;
	/*$tot_t=$tot_t+$item->tot_t;
	$h_sgl=$h_sgl+$item->h_sgl;
	$h_dbl=$h_dbl+$item->h_dbl;
	$h_tpl=$h_tpl+$item->h_tpl;
	$h_suite=$h_suite+$item->h_suite;
	$h_oths=$h_oths+$item->h_oths;
	$h_tot=$h_tot+$item->h_tot;
	$totalamt=$totalamt+$item->totalamt;
	$recived=$recived+$item->recived;*/
	
	
	
	} ?> 
	<tr style="" id="bg">
        
        <td align="right" colspan="7">TOTAL</td>				
        <td align="center" ><?php echo $netamt;?></td>
        <td align="center"><?php echo $slsamt;?></td>
        <td align="center"><?php echo $profit;?></td>
       
      
       
    </tr>
	
	<?php }else{
       ?>
<tr>

        <td style="border:0" colspan="17" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

        <?php }//end else

    ?>

  <style type="text/css">
@media print {
input#btnPrint,
input#btnCancel {
display: none;
}
}
</style>
    <tr class="formButs">

        

        <td style="border:0" colspan="12">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>
</div>