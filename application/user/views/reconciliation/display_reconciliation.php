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

        $('#btnProcess,#btnView,#btnViewMatched,#btnViewUnMatched').click(function(e) {
            var idVal    = this.id;
            var fromDate = $('#txtFromDate').val();
            var toDate   = $('#txtToDate').val();
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

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "Full":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "Matched":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "UnMatched":
                    var path = '<?php echo base_url().'index.php/reconciliation/displayUnMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nFromDate       = $('#txtFromDate').val();
            var nToDate         = $('#txtToDate').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,txtToDate:nToDate,txtFromDate:nFromDate,sess_clear:sess_clear},
                url:path,
                dataType:'html',
                success:function (response) {
                    $('#result').html(response);
                }
            });
        }//end function

        $('.print_logo').hide();
        $('.print_desc').hide();

        $('#btnPrint').click(function(e) {
            $('.print_logo').show();
            $('.print_desc').show();
            $('#reportForm').printElement();
            $('.print_logo').hide();
            $('.print_desc').hide();
        });

        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
        $('#maincontent').scroll(function() {
            console.log(contentBoxExportPos);
            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
                contentBoxExport.addClass('fixed')
            }
            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
                contentBoxExport.removeClass('fixed')
            }
        })

    });
</script>
<div class="content-box-export side">
    <div class="inner">
        <input type="button" class="btn-green" name="btnView" id="btnView" value="<?php echo FREPORT;?>" />
        <input type="button" class="btn-green" name="btnViewMatched" id="btnViewMatched" value="<?php echo MREPORT;?>" />
        <input type="button" class="btn-green" name="btnViewUnMatched" id="btnViewUnMatched" value="<?php echo UREPORT;?>" />
        <input type="button" class="btn-green"  name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </div>
</div> <!--.content-box-export-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
    <?php
    if(!empty($list_value)){
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
        <thead>
        <tr>
        <th align="center" colspan="16">
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
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; BSP Reconciliation Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><strong><?php echo $this->txtFromDate;?></strong></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><strong><?php echo $this->txtToDate;?></strong></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
   
<style type="text/css">
#br{
	display:none;}
#bg{
  -webkit-print-color-adjust:exact;
}
  table { font-size: 9px; }
</style>
<style media="print">

  @media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
	@page:left{
  @bottom-left {
    content: "Page " counter(page) " of " counter(pages);
  }
}
   
}

@media print
{
  table { page-break-inside:auto !important; }
    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
}


</style>
<style media="print">
  #bg{ background-color:#CCC !important; }
 #br{ display:block !important;}
  td{font-size: 12px;
	  border:none;}
</style>

 <tr id="bg" style="">
            <td height="20px" align="center">#</td>
            <!-- <th align="left"><?php echo TTYPE;?></th> -->
            <td align="center"><?php echo AIRCODE;?></td>
            <td align="center"><?php echo DOCNUMBER;?></td>
            <td align="center"><?php echo CUSTDATE;?></td>
            <!-- <th align="left"><?php echo FARECODE;?></th> -->
            <td align="center"><?php echo GROSSFARE;?></td>
            <td align="center"><?php echo COMMAMT;?></td>
            <td align="center"><?php echo TOTALTAX;?></td>
            <td align="center"><?php echo NETAMT;?></td>
            <td align="center"><?php echo INVOICE;?></td>
            <td align="center"><?php echo INVOICEDATE;?></td>
            <td align="center"><?php echo PASSENGERNAME;?></td>
            <td align="center"><?php echo SYSTEMAMOUNT;?></td>
        </tr>
        </thead>

    <tbody>
    <?php
        $i= 1;$j = 0;
        $flag = false;
        $chkName  = array();
        $classVal = 'class="checkStatus"';		$tkt=0; $acm=0; $adm=0; $rft=0;
        foreach($list_value as $key=>$item):						if($item->vType=='Tkt'){				$tkt += $item->vSystemAmt;			}			if($item->vType=='ACM'){				$acm += $item->vSystemAmt;			}			if($item->vType=='ADM'){				$adm += $item->vSystemAmt;			}			if($item->vType=='Rfd'){				$rft += $item->vSystemAmt;			}
            if(!in_array($item->vTypeName,$chkName)):?>
                <?php if($j != 0):?>
                <tr id="bg">
                    <td align="right" height="25px" colspan="4"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td align="right"><strong><?php echo number_format((float) $totalFaceFare, 3, ".", "");?></strong></td>
                    <td align="right"><strong><?php echo number_format((float) $totalCommAmt, 3, ".", "");?></strong></td>
                    <td align="right"><strong><?php echo number_format((float) $totalTotalTax, 3, ".", "");?></strong></td>
                    <td align="right"><strong><?php echo number_format((float) $totalNetAmount, 3, ".", "");?></strong></td>
                    <td colspan="3">&nbsp;</td>
                    <td align="right"><strong><?php echo number_format((float) $totalSysAmount, 3, ".", "");?></strong></td>
                </tr>
                
                    <?php $flag = true; endif;?>

            <tr>
                   <td colspan="12"><strong><?php echo $item->vTypeName;?></strong></td>
            </tr>
                <?php
                $j = 1;
            endif;
            ?>
        <tr>
            <td height="25px" align="center"><?php echo $this->common_model->_offset+$i;?></td>
            <!-- <td align="left"><?php echo ($item->vType == 'Tkt')?'Ticket':$item->vType;?></td> -->
            <td align="center"><?php echo stripslashes($item->vAircode);?></td>
            <td align="center" <?php echo ($item->vStatus == 'N')?$classVal:'';?>><?php echo stripslashes($item->vTktNumber);?></td>
            <td colspan="1" align="center"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
            <!-- <td align="left"><?php echo ($item->vFareCode != '')?$item->vFareCode:'&nbsp';?></td> -->
            <td align="right"><?php echo ($item->vFacefare != '')?$item->vFacefare:'&nbsp';?></td>
            <td align="right"><?php echo ($item->vCommAmt != '')?$item->vCommAmt:'&nbsp';?></td>
            <td align="right"><?php echo ($item->vTotalTax != '')?$item->vTotalTax:'&nbsp';?></td>
            <td align="right"><?php echo ($item->nNetAmount != '')?$item->nNetAmount:'&nbsp';?></td>
            <td align="center"><?php echo ($item->vInvoice != '')?$item->vInvoice:'&nbsp';?></td>
            <td align="center"><?php echo ($item->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($item->dInvoiceDate)):'&nbsp';?></td>
            <td align="left"><?php echo ($item->vPassenger != '')?$item->vPassenger:'&nbsp';?></td>
            <td align="right"><?php echo ($item->vSystemAmt != '')?$item->vSystemAmt:'&nbsp';?></td>
        </tr>
            <?php
            $i         = $i+1;

            if($flag == true):
                $totalFaceFare  = '';
                $totalCommAmt   = '';
                $totalTotalTax  = '';
                $totalNetAmount = '';
                $totalSysAmount = '';
                $flag           = false;
            endif;
			
            $totalFaceFare     += $item->vFacefare;
            $totalCommAmt      += $item->vCommAmt;
            $totalTotalTax     += $item->vTotalTax;
            $totalNetAmount    += $item->nNetAmount;
            $totalSysAmount    += $item->vSystemAmt;
            $totalFaceFare2    += $item->vFacefare;
            $totalCommAmt2     += $item->vCommAmt;
            $totalTotalTax2    += $item->vTotalTax;
            $totalNetAmount2   += $item->nNetAmount;
            $totalSysAmount2   += $item->vSystemAmt;
            $chkName[]          = $item->vTypeName;
            ?>
            <?php endforeach;
        if(($key+1) == count($list_value)): ?>
            <tr id="bg">
                <td align="right" height="25px" colspan="4"><strong><?php echo TOTALAMOUNT;?></strong></td>
                <td align="right"><strong><?php echo number_format((float) $totalFaceFare, 3, ".", "");?></strong></td>
                <td align="right"><strong><?php echo number_format((float) $totalCommAmt, 3, ".", "");?></strong></td>
                <td align="right"><strong><?php echo number_format((float) $totalTotalTax, 3, ".", "");?></strong></td>
                <td align="right"><strong><?php echo number_format((float) $totalNetAmount, 3, ".", "");?></strong></td>
                <td colspan="3">&nbsp;</td>
                <td align="right"><strong><?php echo number_format((float) $totalSysAmount, 3, ".", "");?></strong></td>
            </tr>
        <?php endif;?>
    <tr>
        <td align="right" height="25px" colspan="4"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><strong><?php echo number_format((float) $totalFaceFare2, 3, ".", "");?></strong></td>
        <td align="right"><strong><?php echo number_format((float) $totalCommAmt2, 3, ".", "");?></strong></td>
        <td align="right"><strong><?php echo number_format((float) $totalTotalTax2, 3, ".", "");?></strong></td>
        <td align="right"><strong><?php echo number_format((float) $totalNetAmount2, 3, ".", "");?></strong></td>
        <td colspan="3">&nbsp;</td>
        <td align="right"><strong><?php echo number_format((float) $totalSysAmount2, 3, ".", "");?></strong></td>
    </tr>
    <tr id="bg">
        <td style="color:red;" align="right" height="25px" colspan="11"><strong><?php echo DIFFERENCE;?></strong></td>
       <td style="color:red;" align="right"><strong><?php echo number_format((float) $totalNetAmount2-$totalSysAmount2, 3, ".", "");?></strong></td>
    </tr>	 
	<tr id="bg">        
	<td align="right" height="25px" colspan="11"><strong><?php echo "TICKET";?></strong></td>       
	<td  align="right"><strong><?php echo $tkt; ?></strong></td>    
	</tr>	
	<tr id="bg">        
	<td align="right" height="25px" colspan="11"><strong><?php echo "ADM";?></strong></td>       
	<td  align="right"><strong><?php echo $adm; ?></strong></td>   
	</tr>	
	<tr id="bg">        
	<td align="right" height="25px" colspan="11"><strong><?php echo "REFUND";?></strong></td>       
	<td  align="right"><strong><?php echo $rft;?></strong></td>    
	</tr>	
	<tr id="bg">        
	<td align="right" height="25px" colspan="11"><strong><?php echo "ACM";?></strong></td>       
	<td  align="right"><strong><?php echo $acm;?></strong></td>    </tr>
<tr id="bg">        
	<td align="right" height="25px" colspan="11"><strong><?php echo "TOTAL";?></strong></td>       
	<td  align="right"><strong><?php echo $tkt+$adm+$rft+$acm;?></strong></td>    </tr>	
    <?php }
    else{
        ?>
    <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
	<style type="text/css">
@media print {
input#btnPrint,
input#btnCancel {
display: none;
}
}
</style>
<!--
<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="11">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr> -->
</tbody>
</table>
