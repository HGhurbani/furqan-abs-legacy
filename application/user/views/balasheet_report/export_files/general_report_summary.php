<table width="100%" border="0" cellspacing="10" cellpadding="10" class="reportForm" id="reportForm">

    <?php

    if(!empty($list_value)){
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
            <th colspan="9"><h3><?php echo BALANCESHEETSUMMARY;?></h3></th>
        </tr>

        <tr>
            <th colspan="4">
                

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
<tr><td></td></tr>
<tr><td></td></tr>
<tr>
  <th style="color:#3BB3C2;" align="right"><?php echo ASON;?></th>
  <th align="left"><?php echo $toDate;?></th>
  <th align="left" colspan="7">&nbsp;</th>
</tr>
<tr><td></td></tr>

<tr><td></td></tr>
        <tr>
            <th><?php echo ACCODE;?></th>
            <th><?php echo ACCCODENAME;?></th>
            <th><?php echo LASTYEAR;?></th>
            <th><?php echo CURRENTYEAR;?></th>
        </tr>
        <tbody>
        <?php
        foreach($list_value as $key => $item):

            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
            if($mainId=='1'):
                $mainCode = '1';
                $mainName = 'Asset';
            elseif($mainId=='2'):
                $mainCode = '2';
                $mainName = 'Liabilities';
            endif;

            if(!in_array($mainId,$chkAccountId)):?>
                <?php if($j != 0):?>
                    <tr>
                        <td colspan="2" align="right"><strong><?php echo 'Asset '.TOTAL;?></strong></td>
                        <td><strong><?php echo $totalPreviousLoop;?></strong></td>
                        <td><strong><?php echo $totalCurrentLoop;?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">&nbsp;</td>
                    </tr>
                    <?php $flag = true; endif;?>

                <tr>
                    <td align="left"><strong><?php echo $mainCode;?></strong></td>
                    <td align="left"><strong><?php echo $mainName;?></strong></td>
                    <td colspan="2" align="center">&nbsp;</td>
                </tr>
                <?php
                $j = 1;
            endif;
            ?>

            <?php
            $getValues          = $this->balasheet_report_model->getAccHeadValues($item->HeadId);
            $getOpenValues      = $this->balasheet_report_model->getOpenHeadAccValues($item->HeadId);
            $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            if($mainId==2)
                $balanceDebit = $balanceDebit*-1;
            if($balanceDebit != 0 ) {

                if(!in_array($item->GroupId,$chkGroupId)):?>
                    <tr>
                        <td align="left"><strong><?php echo $item->GroupCode;?></strong></td>
                        <td align="left"><strong><?php echo $item->GroupName;?></strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                        </tr>
                        <?php endif;
            }
            ?>
                <?php
            if(!in_array($item->HeadId,$chkHeadId)):?>
                    <?php
                    if($balanceDebit != 0 ) {

                   ?>

               <tr>
                    <td align="left"><?php echo $item->HeadCode;?></td>
                    <td align="left"><?php echo $item->HeadName;?></td>
<!--                    <td><?php //echo $getOpenValues['openDebit'];?></td> -->
                    <td>&nbsp;</td>
                    <td><?php echo $balanceDebit;?></td>
                </tr>
                <?php
                }
                if($flag == true):
                    $totalPreviousLoop  = '';
                    $totalCurrentLoop   = '';
                    $flag               = false;
                endif;

                $chkGroupId[]            = $item->GroupId;
                $chkHeadId[]             = $item->HeadId;
                $chkAccountId[]          = $mainId;

                $totalPrevious          += $getOpenValues['openDebit'];
                $totalCurrent           += $balanceDebit;
                $totalPreviousLoop      += $getOpenValues['openDebit'];
                $totalCurrentLoop       += $balanceDebit;
            endif;

            if(($key+1) == count($list_value)):
                $getPL  = $this->balasheet_report_model->getPLValues();
                ?>
            <tr>
                <td align="left"><strong><?php echo $getPL['acCode'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['acName'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['previous'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['current'];?></strong></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><strong><?php echo $mainName.' '.TOTAL;?></strong></td>
                <td><strong><?php echo $totalPreviousLoop+$getPL['previous'];?></strong></td>
                <td><strong><?php echo $totalCurrentLoop+$getPL['current'];?></strong></td>
            </tr>
                <?php endif;
        endforeach;?>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="4" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

</tbody>
</table>
