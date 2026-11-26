<table width="100%" border="0" cellspacing="10" cellpadding="10" class="reportForm" id="reportForm">
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
        <th colspan="26">
            

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
    <tr>
        <th><?php echo AIRCODE; ?></th>
        <th><?php echo AIRLINE; ?></th>
        <th colspan="2"><?php echo JANUARY; ?></th>
        <th colspan="2"><?php echo FEBRUARY; ?></th>
        <th colspan="2"><?php echo MARCH; ?></th>
        <th colspan="2"><?php echo APRIL; ?></th>
        <th colspan="2"><?php echo MAY; ?></th>
        <th colspan="2"><?php echo JUNE; ?></th>
        <th colspan="2"><?php echo JULY; ?></th>
        <th colspan="2"><?php echo AUGUST; ?></th>
        <th colspan="2"><?php echo SEPTEMBER; ?></th>
        <th colspan="2"><?php echo OCTOBER; ?></th>
        <th colspan="2"><?php echo NOVEMBER; ?></th>
        <th colspan="2"><?php echo DECEMBER; ?></th>
    </tr>
    <tr>
        <th colspan="2"><?php echo TOPTENAIRLINE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
        <th><?php echo COUNT; ?></th>
        <th><?php echo FARE; ?></th>
    </tr>
    <tr>
        <td colspan="26">&nbsp;</td>
    </tr>
    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):

            switch(substr(date('m',strtotime($item->dDate)),0,1))
            {
                case "0":
                    $varMonth = substr(date('m',strtotime($item->dDate)),1,1);
                    break;

                case "1":
                    $varMonth = date('m',strtotime($item->dDate));
                    break;
            }//end switch

            $varYear = date('Y',strtotime($item->dDate));
            $noDays     = cal_days_in_month(CAL_GREGORIAN, $varMonth, $varYear);
            $txtFrom    = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-01'));
            $txtTo      = date('Y-m-d',strtotime($varYear.'-'.$varMonth.'-'.$noDays));

            $monthVal   = $this->topsector_report_model->getMonthWiseReport($txtFrom,$txtTo,$item->airline);

            ?>
            <tr>
                <td><?php echo $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'");?></td>
                <td><?php echo $this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$item->airline."'");?></td>
                <td><?php if($varMonth=='1') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='1') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='2') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='2') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='3') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='3') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='4') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='4') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='5') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='5') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='6') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='6') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='7') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='7') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='8') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='8') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='9') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='9') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='10') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='10') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='11') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='11') echo $monthVal['fareval'];?></td>
                <td><?php if($varMonth=='12') echo $monthVal['count'];?></td>
                <td><?php if($varMonth=='12') echo $monthVal['fareval'];?></td>
            </tr>
            <?php $i = $i + 1;
        endforeach; ?>
        <tr>
            <td colspan="2" align="right"><strong><?php echo TOTALAMT;?></strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php }
    else{  ?>
        <tr>
            <td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
        </tr>
        <?php }//end if ?>
    
</table>