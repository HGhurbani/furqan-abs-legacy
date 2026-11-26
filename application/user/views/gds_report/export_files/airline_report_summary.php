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
    <tr><th> Airline Report </th></tr>
    <tr>
        <th align="left">&nbsp;</th>
        <th align="center" colspan="5"><?php echo SALES;?></th>
        <th align="center" colspan="5"><?php echo REFUNDS;?></th>
        <th align="center" colspan="5"><?php echo NETSALES;?></th>
  	</tr>
    <tr>
		<th align="left"><?php echo GDS;?></th>
        <th align="left"><?php echo FARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
        <th align="left"><?php echo SEGMENT;?></th>
        <th align="left"><?php echo FARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
        <th align="left"><?php echo SEGMENT;?></th>
        <th align="left"><?php echo FARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
        <th align="left"><?php echo SEGMENT;?></th>
  </tr>
  
    <tbody>
    <?php
    if(!empty($list_value)){
        $i = 1;
	    $chkAirlineId = array();
    
	    foreach($list_value as $key=>$item):
	        if(!in_array($item->airline,$chkAirlineId)): ?>
		        <tr>
					<td><strong> <?php echo stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$item->airline."'"));?></strong></td>
				<tr>
			<?php endif; ?>
			<tr>
		    <td><?php echo stripslashes($this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$item->nGDS."'")); ?> </td>
            <td><?php echo stripslashes($item->fareVal_s);?></td>
            <td><?php echo stripslashes($item->taxTotal_s);?></td>
            <td><?php echo stripslashes($item->netAmt_s);?></td>
            <td><?php echo stripslashes($item->salesAmt_s);?></td>
            <td><?php echo stripslashes($item->vSegment_s);?></td>
            <td><?php echo stripslashes($item->fareVal_r);?></td>
            <td><?php echo stripslashes($item->taxTotal_r);?></td>
            <td><?php echo stripslashes($item->netAmt_r);?></td>
            <td><?php echo stripslashes($item->salesAmt_r);?></td>
            <td><?php echo stripslashes($item->vSegment_r);?></td>
			<?php
				$fareVal_total  = $item->fareVal_s + $item->fareVal_r;
				$taxTotal_total = $item->taxTotal_s + $item->taxTotal_r;
				$netAmt_total   = $item->netAmt_s + $item->netAmt_r;
				$salesAmt_total = $item->salesAmt_s + $item->salesAmt_r;
				$vSegment_total = $item->vSegment_s + $item->vSegment_r;
			?>
            <td><?php echo $fareVal_total;?></td>
            <td><?php echo $taxTotal_total;?></td>
            <td><?php echo $netAmt_total;?></td>
            <td><?php echo $salesAmt_total;?></td>
            <td><?php echo $vSegment_total;?></td>

<!--            <td><?php echo stripslashes($this->common_model->get_field_name('Airline','vTitle'," WHERE nArId='".$item->airline."'"));?></td>
            <td><?php echo $getSales['fareVal'];?></td>
            <td><?php echo $getSales['taxTotal'];?></td>
            <td><?php echo $getSales['netAmt'];?></td>
            <td><?php echo $getSales['salesAmt'];?></td>
            <td><?php echo $getSales['vSegment'];?></td>
            <td><?php echo $getRefund['fareVal'];?></td>
            <td><?php echo $getRefund['taxTotal'];?></td>
            <td><?php echo $getRefund['netAmt'];?></td>
            <td><?php echo $getRefund['salesAmt'];?></td>
            <td><?php echo $getRefund['vSegment'];?></td>
            <td><?php echo stripslashes($item->fareVal);?></td>
            <td><?php echo stripslashes($item->taxTotal);?></td>
            <td><?php echo stripslashes($item->netAmt);?></td>
            <td><?php echo stripslashes($item->salesAmt);?></td>
            <td><?php echo stripslashes($item->vSegment);?></td>
 --> 		</tr>
            <?php $i = $i + 1;
		        $chkAirlineId[] = $item->airline;
            ?>
            <?php endforeach;?>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    
    </tbody>
</table>
