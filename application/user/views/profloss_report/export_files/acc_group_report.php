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
    $(document).ready(function () {
        $('#btnCancel').click(function () {
            window.location.replace('<?php echo base_url().'index.php/profloss_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

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



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
    <thead>
    <?php
        if(!empty($list_value)){
			//var_dump($list_value);
            $j = 0;
            $flag = false;
            $chkGroupId = $chkHeadId = $chkAccountId = array();
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
        <th align="center" colspan="9">
            

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
            <td colspan="9" style="border:0" height="25px" align="left"><strong><?php echo PLACCGROUPREPORT;?></strong></td>
        </tr>
    <tr>
        <th style="color:#3BB3C2;" align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $fromDate;?></th>
        <th style="color:#3BB3C2;" align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $toDate;?></th>
    </tr>
    
<style type="text/css">
  table { font-size: 9px; }
</style>

  <style>
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr>
        <td height="25px" align="center"><?php echo ACCGROUPNAME;?></td>
        <td align="center"><?php echo OPENING;?></td>
        <td align="center"><?php echo DURING;?></td>
        <td align="center"><?php echo YEARTODATE;?></td>
    </tr>
    </thead>
    <tbody>

    <?php
        foreach($list_value as $key => $item):
		//var_dump($list_value);

            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
            if($mainId=='3'):
                $mainCode = '3';
                $mainName = 'Income';
            elseif($mainId=='4'):
                $mainCode = '4';
                $mainName = 'Expense';
            endif;

            if(!in_array($mainId,$chkGroupId)):
                if($j != 0):?>
                    <tr>
                        <td height="25px" colspan="1" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                        <td><strong><?php echo number_format((float)$totalPreviousLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalCurrentLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalYearDate, 3, ".", "");?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">&nbsp;</td>
                    </tr>
                    <?php $flag = true;
                    $income1            = $totalPreviousLoop;
                    $income2            = $totalCurrentLoop;
                    $income3            = $totalYearDate;
                endif;?>
                <tr>
                    <td align="left"><h6><?php echo $mainName;?></h6></td>
                    <td colspan="3" align="center">&nbsp;</td>
                </tr>
                <?php
                $j = 1;
            endif;?>
           <tbody>
                <?php
            if(!in_array($item->AccountId,$chkAccountId)):?>
            <tr>
                <?php
				$mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
                if($mainId=='3') 
				{
                $getValues          = $this->profloss_report_model->getAccValues($item->AccountId);
				}else {
				$getValues          = $this->profloss_report_model->getAccValues1($item->AccountId);
					
				}
                $getOpenValues      = $this->profloss_report_model->getOpeningBalForAcc($item->AccountId,'','');
				
            //$getOpenValues      = $this->profloss_report_model->getOpenAccValues($item->AccountId);
//$getOpenValues['openDebit']  = '';
                if($getOpenValues['openDebit'] !=0 || $getValues['debit'] != 0){
                if(!in_array($item->HeadId,$chkHeadId)):
                $chkHeadId[]             = $item->HeadId;?>
                <tr>
                    <td height="25px" align="left"><strong><?php echo $item->HeadName;?></strong></td>
                    <td colspan="3" align="center">&nbsp;</td>
                </tr>
                <?php
                endif;?>

                <td height="25px"><?php echo $item->AccountName;?></td>
                <td><?php echo number_format((float)$getOpenValues['openDebit'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$getValues['debit'], 3, ".", "");?></td>
                <td><?php echo number_format((float)($getOpenValues['openDebit']+$getValues['debit']), 3, ".", "");?></td>
				
            </tr>
                <?php
                }
                if($flag == true):
                    $totalPreviousLoop  = 0;
                    $totalCurrentLoop   = 0;
					$totalYearDate      = 0;
                    $flag               = false;
                endif;

                $chkGroupId[]            = $mainId;
                $chkAccountId[]          = $item->AccountId;

                $totalPrevious          += $getOpenValues['openDebit'];
                $totalCurrent           += $getValues['debit'];
                $totalPreviousLoop      += $getOpenValues['openDebit'];
                $totalCurrentLoop       += $getValues['debit'];
				$totalYearDate          += ($getOpenValues['openDebit']+$getValues['debit']);
				//echo "$totalCurrent";
            endif;

            if(($key+1) == count($list_value)):?>
            <tr>
                <td height="25px" colspan="1" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                <td><strong><?php echo number_format((float)$totalPreviousLoop, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalCurrentLoop, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalYearDate, 3, ".", "");?></strong></td>
            </tr>
                <?php endif;
        endforeach;
		?>
<tr><td style="border:0">&nbsp;</td></tr>
         <tr>
                <td height="25px" colspan="1" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
                <td><strong><?php echo number_format((float)$income1 - $totalPreviousLoop, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$income2 - $totalCurrentLoop, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$income3 - $totalYearDate, 3, ".", "");?></strong></td>
            </tr>
        <?php
    }
    else{
        ?>
    <tr>
        <td colspan="4" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
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
        
        <td style="border:0" colspan="3">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
