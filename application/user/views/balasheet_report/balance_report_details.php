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
header_remove('Set-Cookie');
ini_set('memory_limit', '-1');

?>
<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/balasheet_report/showlist/'.$this->uri->segment(3);?>');
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

<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/balance_report_details";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/balance_report_details";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/balance_report_details";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
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
            <td colspan="9" style="border:0" height="25px" align="left"><strong><?php echo BALANCESHEET;?></strong></td>
        </tr>
      <tr>
          <th style="color:#3BB3C2;" align="right"><?php echo ASON;?></th>
          <td align="left"><?php echo $toDate;?></td>
          <th align="left" colspan="7">&nbsp;</th>
      </tr>
    <style media="print">
	#reportForm:last-child {
     page-break-after: auto;
}

  #bg{   -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
 
</style>
<style>
table { font-size: 9px; }
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>

      </thead>
    <tbody>
<?php $j = 0;
    $flag = false;
    $chkGroupId = $chkHeadId = $chkAccountId = array();?>
        <tr>
            <td colspan="2">
                <table width="100%">
                    <tr id="bg">
                        <td align="center" colspan="2"><strong><?php echo LIABILITIES;?></strong></td>
                        <td align="center"><strong><?php echo BALANCE;?></strong></td>
                    </tr>
<?php $bal4 = $bal3 = 0;
    foreach($list_value as $key => $item):

        $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
        if($mainId=='1'):
            $mainCode = '1';
            $mainName = 'Asset';
        elseif($mainId=='2'):
            $mainCode = '2';
            $mainName = 'Liabilities';
/*        else:
            if($mainId=='4'){
                $getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
                $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
                $bal4 += ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            }
            else if($mainId=='3'){
                $getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
                $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
                $bal3 += ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            }
            $chkGroupId[]            = $mainId; 
*/        endif;

        
        ?>

        <?php
        if (substr( $item->HeadCode,0,1) == '2'):
        if(!in_array($item->AccountId,$chkAccountId)):
            $getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
            $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
            $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
			
        endif;
        if($mainId==2)
            $balanceDebit = $balanceDebit*-1;
		//echo $item->AccountId."---".$item->HeadId."<br>";

        if($balanceDebit != 0):
        if(!in_array($item->AccountId,$chkAccountId)):?>
        <?php if($item->HeadId!=$to):?>
        <?php if($mainId==2):?>
        <tr id="bg">
            <td align="left"><strong><?php  echo $item->HeadCode;?></strong></td>
            <td align="left"><strong><?php echo $item->HeadName;?></strong></td>
            <td colspan="2" align="center">&nbsp;</td>
        </tr>
            <?php $to=$item->HeadId; endif; endif;endif;
        endif;
        ?>
            <tbody>
                <?php
                if(!in_array($item->AccountId,$chkAccountId)):?>
                <?php
                 if($balanceDebit != 0){
                ?>
            <?php if($mainId==2){?>
				<tr>
                <td><?php  echo $item->AccountCode;?></td>
                <td><?php echo $item->AccountName;?></td>

                <td><?php if(substr($balanceDebit,0,1)=='-'){
					echo "(".number_format((float)-$balanceDebit, 2, ".", "").")";
				}else{
					echo number_format((float)$balanceDebit, 2, ".", "");
				}
				
				?></td>
            </tr><?php $tt+= $balanceDebit;}?>
            
            <?php
                  }
            if($flag == true):
                $totalPreviousLoop  = '';
                $totalCurrentLoop   = '';
                $flag               = false;
            endif;

            $chkGroupId[]            = $mainId;
            $chkHeadId[]             = $item->HeadId;
            $chkAccountId[]          = $item->AccountId;

            //$totalPrevious          += $getOpenValues['openDebit'];
            $totalCurrent           += $balanceDebit;
            //$totalPreviousLoop      += $getOpenValues['openDebit'];
            $totalCurrentLoop       += $balanceDebit;
        endif;

        if(($key+1) == count($list_value)):
            $getPL  = $this->balasheet_report_model->getPLValues();
            ?>
            <tr>
                <td align="left"><strong><?php echo $getPL['acCode'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['acName'];?></strong></td>
                
				<td align="left"><strong><?php 
				if(substr($getPL['current'],0,1)=='-'){					
				echo "(".number_format((float)-$getPL['current'], 2, ".", "").")";				
				}else{					
				echo number_format((float)$getPL['current'], 2, ".", "");				
				}			?></strong></td>
				
            </tr>
           
        <?php endif;
            endif;
    endforeach; 
	$tt=$tt+$getPL['current'];?>
                    
                </table>
            </td>
            <td colspan="2">
                <table width="100%">
                    <tr id="bg"><?php $j = 0;
    $flag = false;
    $chkGroupId = $chkHeadId = $chkAccountId = array();?>
 
                        <td align="center" colspan="2"><strong><?php echo ASSETS;?></strong></td>
                        <td align="center"><strong><?php echo BALANCE;?></strong></td>
                    </tr>
                    <?php $bal4 = $bal3 = 0;
    foreach($list_value as $key => $item):

        $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
        if($mainId=='1'):
            $mainCode = '1';
            $mainName = 'Asset';
        elseif($mainId=='2'):
            $mainCode = '2';
            $mainName = 'Liabilities';
/*        else:
            if($mainId=='4'){
                $getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
                $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
                $bal4 += ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            }
            else if($mainId=='3'){
                $getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
                $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
                $bal3 += ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            }
            $chkGroupId[]            = $mainId; 
*/        endif;

        
        ?>

        <?php
        if (substr( $item->HeadCode,0,1) == '1'):
        if(!in_array($item->AccountId,$chkAccountId)):
		if($item->AccountId=="1" || $item->AccountId=="2"  || $item->AccountId=="3" || $item->AccountId=="4" ){
            $getValues          = $this->balasheet_report_model->getAccValues_123($item->AccountId);
			}else{			
			$getValues          = $this->balasheet_report_model->getAccValues($item->AccountId);
			}
			if($item->AccountId=="8" || $item->AccountId=="9" || $item->AccountId=="10" || $item->AccountId=="11" || $item->AccountId=="12" || $item->AccountId=="13" || $item->AccountId=="222" || $item->AccountId=="228" || $item->AccountId=="235"){            
		$getValues          = $this->balasheet_report_model->getAccValues_1234($item->AccountId);			
			
			}
			if($item->AccountId==222){
			$getValues          = $this->balasheet_report_model->getAccValues_222($item->AccountId);
				
			}
			
            $getOpenValues      = $this->balasheet_report_model->getOpenAccValues($item->AccountId);
            $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
        endif;
        if($mainId==2)
            //$balanceDebit = $balanceDebit*-1;		

        if($balanceDebit != 0):
        if(!in_array($item->AccountId,$chkAccountId)):?>
        <?php if($item->HeadId!=$to):?>
        <tr id="bg">
            <td align="left"><strong><?php echo $item->HeadCode;?></strong></td>
            <td align="left"><strong><?php echo $item->HeadName;?></strong></td>
            <td colspan="2" align="center">&nbsp;</td>
        </tr>
            <?php $to=$item->HeadId; endif; endif;
        endif;
        ?>
       
            <tbody>
                <?php
                if(!in_array($item->AccountId,$chkAccountId)):?>
                <?php
                 if($balanceDebit != 0){
                ?>
            <?php if($mainId==1){?>
				<tr>
                <td><?php echo $item->AccountCode;?></td>
                <td><?php echo $item->AccountName;?></td>

                <td><?php if(substr($balanceDebit,0,1)=='-'){
					echo "(".number_format((float)-$balanceDebit, 2, ".", "").")";
				}else{
					echo number_format((float)$balanceDebit, 2, ".", "");
				}
				
				?></td>
            </tr><?php $tt1+= $balanceDebit;}?>
            
            <?php
                  }
            if($flag == true):
                $totalPreviousLoop  = '';
                $totalCurrentLoop   = '';
                $flag               = false;
            endif;

            $chkGroupId[]            = $mainId;
            $chkHeadId[]             = $item->HeadId;
            $chkAccountId[]          = $item->AccountId;

            //$totalPrevious          += $getOpenValues['openDebit'];
            $totalCurrent           += $balanceDebit;
            //$totalPreviousLoop      += $getOpenValues['openDebit'];
            $totalCurrentLoop       += $balanceDebit;
        endif;

        if(($key+1) == count($list_value)):
            $getPL  = $this->balasheet_report_model->getPLValues();
            ?>
            <tr id="bg">
                <td align="left"><strong><?php echo $getPL['acCode'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['acName'];?></strong></td>
                <td align="left"><strong><?php echo number_format((float)$getPL['current'], 2, ".", "");?></strong></td>
                
            </tr>
           
        <?php endif;
            endif;
    endforeach; ?>
                </table>
            </td>
        </tr>


      <tr id="bg">
          <td width="37%" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
          <td width="11%" align="left"><strong><?php echo number_format((float)$tt, 2, ".", "");?></strong></td>
          <td width="39%" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
          <td width="14%" align="left"><strong><?php echo number_format((float)$tt1, 2, ".", "");?></strong></td>
      </tr>
	  
	  <style type="text/css">
@media print {
input#btnPrint,
input#btnCancel {
display: none;
}
}
</style>
      <tr class="formButs">
        <td style="border:0" colspan="4">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
