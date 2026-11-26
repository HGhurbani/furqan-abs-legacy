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
            window.location.replace('<?php echo base_url().'index.php/group_report/showlist/'.$this->uri->segment(3);?>');
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
        <th style="border:0" align="center" colspan="16">
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
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; GROUP CUSTOMER Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        <th align="CENTER" colspan="4">TICKET TYPE</th>
		 <th align="CENTER" colspan="6">ROOM TYPE</th>
		<th align="CENTER" colspan="6">SALES DETAILS</th>
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
        <td style="width:8%" align="center"><?php echo CUSTDATE;?></td>
        <td style="width:8%" align="center"><?php echo "FILE NUMBER";?></td>
        <td align="center" ><?php  echo PASSENGER;?></td>
        <td align="center"><?php echo "Date of Travel";?></td>				
        <td align="center" ><?php echo "Adult";?></td>
        <td align="center"><?php echo "CHILD";?></td>
        <td align="center"><?php echo "INFANT";?></td>
        <td align="center" ><?php echo "TOTAL";?></td>
     
        <td align="center"><?php echo "SINGLE";?></td>
        <td align="center" ><?php echo "DOUBLE";?></td>
        <td align="center" ><?php echo "TRIPLE";?></td>
       
        <td align="center"><?php echo "SUITE";?></td>
        <td align="center" ><?php echo 'OTHERS';?></td>		
        <td align="center" ><?php echo "TOTAL";?></td>
        <td align="center" ><?php echo 'SALES';?></td>
		<td align="center" ><?php echo 'RECEIVED';?></td>
		<td align="center" ><?php echo 'BALANCE';?></td>
    </tr>
    </thead>
    <tbody>

	<?php
    if(!empty($list_value)){
		$i=1;
	foreach($list_value as $key=>$item){
	?>
	<tr style="">
        <td height="20px" align="center"><?php echo $i;?></td>
        <td style="width:8%" align="center"><?php echo $item->dDate;?></td>
        <td style="width:8%" align="center"><?php echo $item->groupno;?></td>
        <td align="center" ><?php  echo $item->paxname;?></td>
        <td align="center"><?php echo $item->dot;?></td>				
        <td align="center" ><?php echo $item->t_adt;?></td>
        <td align="center"><?php echo $item->t_cld;?></td>
        <td align="center"><?php echo $item->t_inf;?></td>
        <td align="center" ><?php echo $item->tot_t;?></td>
      
        <td align="center"><?php echo $item->h_sgl;?></td>
        <td align="center" ><?php echo $item->h_dbl;?></td>
        <td align="center" ><?php echo $item->h_tpl;?></td>
       
        <td align="center"><?php echo $item->h_suite;?></td>
        <td align="center" ><?php echo $item->h_oths;?></td>		
        <td align="center" ><?php echo $item->h_tot;?></td>
        <td align="center" ><?php echo $item->totalamt;?></td>
		<td align="center" ><?php echo $item->recived;?></td>
		<td align="center" ><?php echo number_format(($item->totalamt - $item->recived),3);?></td>
    </tr>
	

	<?php 
	$t_adt=$t_adt+$item->t_adt;
	$t_cld=$t_cld+$item->t_cld;
	$t_inf=$t_inf+$item->t_inf;
	$tot_t=$tot_t+$item->tot_t;
	$h_sgl=$h_sgl+$item->h_sgl;
	$h_dbl=$h_dbl+$item->h_dbl;
	$h_tpl=$h_tpl+$item->h_tpl;
	$h_suite=$h_suite+$item->h_suite;
	$h_oths=$h_oths+$item->h_oths;
	$h_tot=$h_tot+$item->h_tot;
	$totalamt=$totalamt+$item->totalamt;
	$recived=$recived+$item->recived;
	
	
	
	$i=$i+1;} ?> 
	<tr style="" id="bg">
        
        <td align="center" colspan="5">TOTAL</td>				
        <td align="center" ><?php echo $t_adt;?></td>
        <td align="center"><?php echo $t_cld;?></td>
        <td align="center"><?php echo $t_inf;?></td>
        <td align="center" ><?php echo $tot_t;?></td>
      
        <td align="center"><?php echo $h_sgl;?></td>
        <td align="center" ><?php echo $h_dbl;?></td>
        <td align="center" ><?php echo $h_tpl;?></td>
       
        <td align="center"><?php echo $h_suite;?></td>
        <td align="center" ><?php echo $h_oths;?></td>		
        <td align="center" ><?php echo $h_tot;?></td>
        <td align="center" ><?php echo number_format($totalamt,3);?></td>
		<td align="center" ><?php echo number_format($recived,3);?></td>
		<td align="center" ><?php echo number_format(($totalamt - $recived),3);?></td>
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