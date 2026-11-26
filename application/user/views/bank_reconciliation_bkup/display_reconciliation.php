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
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "Matched":
                    var path = '<?php echo base_url().'index.php/ban_reconciliation/displayMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "UnMatched":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayUnMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nLocation2       = $('#ddlLocation2').val();
            var nFromDate       = $('#txtFromDate').val();
            var nToDate         = $('#txtToDate').val();
            var nAccount        = $('#ddlAccount').val();

            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocatio2:nLocation2,ntxtToDate:nToDate,txtFromDate:nFromDate,ddlAccount:nAccount,sess_clear:sess_clear},
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
            <th colspan="12">
                <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="80" height="80">

                <div class="print_desc">
                    <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                    <?php echo $addr;?><br>
                    <?php echo PHONE;?>: <?php echo $phone;?>
                    <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                    <?php echo EMAIL;?>: <?php echo $email;?><br>
                    <?php echo WEB;?>: <?php echo $web;?>
                </div>
            </th>
        </tr>
        </thead>

        <thead>
        <tr>
            <th align="left"><?php echo SRNO;?></th>
            <!-- <th align="left"><?php echo TTYPE;?></th> -->
            <th align="left"><?php echo AIRCODE;?></th>
            <th align="left"><?php echo DOCNUMBER;?></th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <!-- <th align="left"><?php echo FARECODE;?></th> -->
            <th align="left"><?php echo GROSSFARE;?></th>
            <th align="left"><?php echo COMMAMT;?></th>
            <th align="left"><?php echo TOTALTAX;?></th>
            <th align="left"><?php echo NETAMT;?></th>
            <th align="left"><?php echo INVOICE;?></th>
            <th align="left"><?php echo INVOICEDATE;?></th>
            <th align="left"><?php echo PASSENGERNAME;?></th>
            <th align="left"><?php echo SYSTEMAMOUNT;?></th>
        </tr>
        </thead>

    <tbody>
    <?php
        $i= 1;$j = 0;
        $flag = false;
        $chkName  = array();
        $classVal = 'class="checkStatus"';
        foreach($list_value as $key=>$item):
            if(!in_array($item->vTypeName,$chkName)):?>
                <?php if($j != 0):?>
                <tr>
                    <td colspan="4" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td><strong><?php echo $totalFaceFare;?></strong></td>
                    <td><strong><?php echo $totalCommAmt;?></strong></td>
                    <td><strong><?php echo $totalTotalTax;?></strong></td>
                    <td><strong><?php echo $totalNetAmount;?></strong></td>
                    <td colspan="3">&nbsp;</td>
                    <td><strong><?php echo $totalSysAmount;?></strong></td>
                </tr>
                <tr>
                    <td colspan="14" align="right">&nbsp;</td>
                </tr>
                    <?php $flag = true; endif;?>

            <tr>
                <td align="left" colspan="12"><strong><?php echo $item->vTypeName;?></strong></td>
            </tr>
                <?php
                $j = 1;
            endif;
            ?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <!-- <td align="left"><?php echo ($item->vType == 'Tkt')?'Ticket':$item->vType;?></td> -->
            <td align="left"><?php echo stripslashes($item->vAircode);?></td>
            <td align="left" <?php echo ($item->vStatus == 'N')?$classVal:'';?>><?php echo stripslashes($item->vTktNumber);?></td>
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
            <!-- <td align="left"><?php echo ($item->vFareCode != '')?$item->vFareCode:'&nbsp';?></td> -->
            <td align="left"><?php echo ($item->vFacefare != '')?$item->vFacefare:'&nbsp';?></td>
            <td align="left"><?php echo ($item->vCommAmt != '')?$item->vCommAmt:'&nbsp';?></td>
            <td align="left"><?php echo ($item->vTotalTax != '')?$item->vTotalTax:'&nbsp';?></td>
            <td align="left"><?php echo ($item->nNetAmount != '')?$item->nNetAmount:'&nbsp';?></td>
            <td align="left"><?php echo ($item->vInvoice != '')?$item->vInvoice:'&nbsp';?></td>
            <td align="left"><?php echo ($item->dInvoiceDate != '0000-00-00')?date('d-m-Y',strtotime($item->dInvoiceDate)):'&nbsp';?></td>
            <td align="left"><?php echo ($item->vPassenger != '')?$item->vPassenger:'&nbsp';?></td>
            <td align="left"><?php echo ($item->vSystemAmt != '')?$item->vSystemAmt:'&nbsp';?></td>
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
            <tr>
                <td colspan="4" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                <td><strong><?php echo $totalFaceFare;?></strong></td>
                <td><strong><?php echo $totalCommAmt;?></strong></td>
                <td><strong><?php echo $totalTotalTax;?></strong></td>
                <td><strong><?php echo $totalNetAmount;?></strong></td>
                <td colspan="3">&nbsp;</td>
                <td><strong><?php echo $totalSysAmount;?></strong></td>
            </tr>
        <?php endif;?>
    <tr>
        <td colspan="4" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><strong><?php echo $totalFaceFare2;?></strong></td>
        <td><strong><?php echo $totalCommAmt2;?></strong></td>
        <td><strong><?php echo $totalTotalTax2;?></strong></td>
        <td><strong><?php echo $totalNetAmount2;?></strong></td>
        <td colspan="3">&nbsp;</td>
        <td><strong><?php echo $totalSysAmount2;?></strong></td>
    </tr>
    <tr>
        <td colspan="11" align="right"><strong><?php echo DIFFERENCE;?></strong></td>
        <td><strong><?php echo $totalNetAmount2-$totalSysAmount2;?></strong></td>
    </tr>
    <?php }
    else{
        ?>
    <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
<!--
<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="11">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr> -->
</tbody>
</table>
