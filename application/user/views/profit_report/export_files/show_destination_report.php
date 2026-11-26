<table width="100%" border="0" cellspacing="10" cellpadding="10" class="reportForm">
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
        <th colspan="16">
            
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
  <tr><th> <b> &nbsp; Destination Report </b> </th></tr>

    <tr>
        <th align="left">#</th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo CHECKIN;?></th>
        <th align="left"><?php echo CHECKOUT;?></th>
        <th align="left"><?php echo NIGHTS;?></th>
        <th align="left"><?php echo VOUCHERID;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo CITYNAME;?></th>
        <th align="left"><?php echo COUNTRYNAME;?></th>
        <th align="left"><?php echo HOTELNAME;?></th>
        <th align="left"><?php echo AMOUNTNIGHT;?></th>
        <th align="left"><?php echo TOTALAMOUNT;?></th>
        <th align="left"><?php echo PROFIT;?></th>
        <th align="left"><?php echo PROFITPERCENTAGE;?></th>
    </tr>
    <tbody>
    
    <?php
    if(!empty($list_value)){
    $i= 1;
    $chkDestination = array();
    foreach($list_value as $key=>$item):

        switch($item->sale_type)
        {
            case "Ticket":
                $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$item->tableId;
                break;

            case "Xo":
                $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$item->tableId;
                break;

            case "Hotel":
                $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$item->tableId;
                break;
        }//end switch

        $profit           = $item->netAmt-$item->salesAmt;
        if($profit >0 && $item->netAmt >0)
            $profitPercentage = round((($profit*100)/$item->netAmt),2);
        else
            $profitPercentage = 0;

        if(!in_array($item->vDestination,$chkDestination)):?>
            <tr>
                <td colspan="17"><strong><?php echo $item->vDestination;?></strong></td>
            </tr>
        <?php endif;?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->dDate);?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$item->vIssuedBy."'"));?></td>
            <td align="left"><?php echo stripslashes($item->dCheckIn);?></td>
            <td align="left"><?php echo stripslashes($item->dCheckOut);?></td>
            <td align="left"><?php echo stripslashes($item->vNight);?></td>
            <td align="left"><a href="<?php echo $path;?>"><?php echo stripslashes($item->vDocNumber);?></a></td>
            <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$item->nCity."'"));?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Country','vName'," WHERE nId='".$item->nCountry."'"));?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$item->vHotelName."'"));?></td>
            <td align="left"><?php echo stripslashes($item->vTotalRTFC);?></td>
            <td align="left"><?php echo stripslashes($item->vTotalRTFC);?></td>
            <td align="left"><?php echo stripslashes($profit);?></td>
            <td align="left"><?php echo stripslashes($profitPercentage);?></td>
        </tr>
        <?php $i = $i + 1;
        $chkDestination[] = $item->vDestination;
        $cmtAmount      = $cmtAmount+$item->vTotalRTFC;
        $netAmt         = $netAmt+$item->vTotalRTFC;
        $totProfit      = $totProfit+$profit;
        $totProfitPer   = $totProfitPer+$profitPercentage;
        ?>
        <?php endforeach;?>
        <tr>
            <td align="right" colspan="12"><strong><?php echo TOTALAMT;?></strong></td>
            <td align="left"><?php echo $cmtAmount;?></td>
            <td align="left"><?php echo $netAmt;?></td>
            <td align="left"><?php echo $totProfit;?></td>
            <td align="left"><?php echo $totProfitPer;?></td>
        </tr>
    <?php }
    else{
        ?>
    <tr>
        <td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
     
    </tbody>
</table>

