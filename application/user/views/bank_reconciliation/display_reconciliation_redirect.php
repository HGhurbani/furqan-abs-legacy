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

        $('#btnProcess,#btnView,#btnViewMatched,#btnViewUnMatched,#btnSave,#btnSaveExit').click(function(e) {
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

                    case "btnSave":
                        getVal('Save');
                        break;

                    case "btnSaveExit":
                        getVal('SaveExit');
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
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "UnMatched":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayUnMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

                case "SaveExit":
                case "Save":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/saveReconciled/'.$this->uri->segment(3);?>';
                    var sess_clear = 'n';
                    break;

            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nLocation2       = $('#ddlLocation2').val();
            var nFromDate       = $('#txtFromDate').val();
            var nToDate         = $('#txtToDate').val();
            var nAccount        = $('#ddlAccount').val();

            if(nval == "Full" || nval == "Matched" || nval == "UnMatched" ){
                $.ajax({
                    type:'POST',
                    data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlAccount:nAccount,sess_clear:sess_clear},
                    url:path,
                    dataType:'html',
                    success:function (response) {
                        $('#result').html(response);
                    }
                });
          }
            else{

                var aid;
                var dDate      = [];
                var vLocation3 = [];
                var docNum     = [];
                var debit      = [];
                var credit     = [];
                var tblName    = [];
                var tblId      = [];
                var vStatus    = [];

            $(".chkAllBox").each(function ()
            {
                //alert($(this).val());
                aid        = $(this).val();
                var nvalChanged = $('#valChanged'+aid).val();
                if(nvalChanged == 'Y'){
//chkAllBox.pus($"
//alert($("input[id='chkAllBox'"+aid+"]").checked);
                    var vChkd= $("#chkAllBox"+aid).is(":checked") == false ? 'N' : 'M';
                    vStatus.push(vChkd);
                    dDate.push($('#dDate'+aid).val());
                    vLocation3.push($('#vLocation3'+aid).val());
                    docNum.push($('#docNum'+aid).val());
                    debit.push($('#debit'+aid).val());
                    credit.push($('#credit'+aid).val());
                    tblName.push($('#tblName'+aid).val());
                    tblId.push($('#tblId'+aid).val());
                }
            });

               $.ajax({
                    type:'POST',
                    data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlAccount:nAccount,dDate:dDate,ddlLocation3:vLocation3,vDocNum:docNum,nDebit:debit,nCredit:credit,tblName:tblName,tblId:tblId,vStatus:vStatus,sess_clear:sess_clear},
                    url:path,
                    dataType:'html',
                    success:function (response) {
                        alert(response);
                        if(nval == "SaveExit"){
                            var path = '<?php echo base_url().'index.php/bank_reconciliation/showlist/5';?>';
                            window.location.replace(path);
                        }

                    }
                });


            }

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
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder"><div id="result">
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

        <tr>
            <th align="left"><?php echo SRNO;?></th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <th align="left"><?php echo LOCATION;?></th>
            <th align="left"><?php echo DOCUMENTNUMBER;?></th>
            <th align="left"><?php echo DESCRIPTION;?></th>
            <th align="left"><?php echo DEBIT;?></th>
            <th align="left"><?php echo CREDIT;?></th>
            <th align="left"><?php echo RECONCILED;?></th>
        </tr>
        <?php
        $chkAccount_acc = array();
        $chkAccount_subacc = array();

        ?>
            <tbody>

            <?php
            $i = 1;
        foreach($list_value as $key=>$item):
            ?>


                <?php
                $display_acc = 0;
        if(!in_array($item->account,$chkAccount_acc)){
            $chkAccount_acc[] = $item->account;
            $chkAccount_subacc[] = $item->subacc;
            $display_acc = 1;
        }
        else{
            if(!in_array($item->subacc,$chkAccount_subacc)){
                $chkAccount_subacc[] = $item->subacc;
                $display_acc = 1;
            }
            else {
                $display_acc = 0;

            }
        }

        if($display_acc == 1):

            $display_acc = 0;
        if($i !=1):
            ?>
                <tr>
                <td colspan="5" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                <td><?php echo $totalDebit_perAccc;?></td>
                <td><?php echo $totalCredit_perAccc;?></td>
                <?php

                $totalDebit_perAccc = 0;
        $totalCredit_perAccc = 0;
        endif;
        ?>
            <tr>
            <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," where nId='".$item->account."'");?></th>
            <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," where nId='".$item->account."'");?></th>
            <?php endif; ?>


            <?php
            switch($item->tblName)
            {
                case "CR":
                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "CP":
                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "BR":
                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "BP":
                case "BPb":
                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "JV":
                    $path = base_url().'index.php/jv/editJv/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "SJ":
                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
                $getTktValue  = $this->bank_reconciliation_model->getTktNumberValues($item->detId,$item->tableId);
                $description = $getTktValue['desc'];
                $ticketNum = $getTktValue['tktVal'];
                break;
                case "CV":
                    $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "DV":
                    $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "AC":
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

                case "AD":
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;
            }//end switch

        switch($item->mode)
        {
            case "Dr":
                $debit = $item->debit;
            $credit = '';
            break;

            case "Cr":
                $debit = '';
            $credit = $item->debit;
            break;

            default:
            $debit = ($item->debit==' ')?'':$item->debit;
            $credit = ($item->credit==' ')?'':$item->credit;
            break;
        }
        ?>
            <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo date("d-M-y", strtotime($item->date));?></td>
            <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
            <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
            <td><?php echo $ticketNum . " " .$description;?></td>
            <td><?php echo $debit;?></td>
            <td><?php echo $credit;?></td>
            <td>  <input type="checkbox" name="chkAllBox" value="<?php echo $item->tableId . '-' . $item->tblName;?>" id="chkAllBox<?php echo $item->tableId . '-' . $item->tblName;?>" <?php if($item->vStatus == 'M' ) echo "checked"; ?> class="chkAllBox">
            </tr>
            <input type="hidden" name="valChanged[]" id="valChanged<?php echo $item->tableId.'-'.$item->tblName;?>" value="N" >
            <input type="hidden" name="dDate[]" id="dDate<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $item->date;?>" >
            <input type="hidden" name="vLocation3[]" id="vLocation3<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $item->location;?>" >
            <input type="hidden" name="docNum[]" id="docNum<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $item->document;?>" >
            <input type="hidden" name="debit[]" id="debit<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $debit;?>" >
            <input type="hidden" name="credit[]" id="credit<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $credit;?>" >
            <input type="hidden" name="tblName[]" id="tblName<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $item->tblName;?>" >
            <input type="hidden" name="tblId[]" id="tblId<?php echo $item->tableId.'-'.$item->tblName;?>" value="<?php echo $item->tableId;?>" >


            <?php $i = $i + 1;
        $totalDebit += $debit;
        $totalCredit += $credit;
        //$totalopenDebit = $openDebit;
        //$totalopenCredit = $openCredit;

        $totalDebit_perAccc += $debit;
        $totalCredit_perAccc += $credit;

       $tablArray[]     = $item->document; ?>

            <?php        endforeach;?>

            <tr>
            <td colspan="5" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td><?php echo $totalDebit_perAccc;?></td>
            <td><?php echo $totalCredit_perAccc;?></td>
            </tr>
            <tr> <td colspan='10'> &nbsp; </td> </tr>


</tbody>
</table>
