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
        <th colspan="15"><h3><?php echo SUBLEDGERSUMMARYREPORT;?></h3></th>
    </tr>

    <tr>
        <th colspan="9">

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
    <tr>
        <th style="color:#3BB3C2;" align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $fromDate;?></th>
        <th style="color:#3BB3C2;" align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $toDate;?></th>
        <th align="left" colspan="12">&nbsp;</th>
    </tr>
    <tr><td></td></tr>

    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left"><?php echo OPENINGBALANCE;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
<!--    <tr>
        <th colspan="11" align="center"><?php echo OPENINGBALANCE;?></th>
   		</tr>
-->	
    </thead>

    <tbody>

    <?php
    if(!empty($list_value2)){
        $recoed_present = 1;
        $i = 1;
		$balance = 0;
		$chkAccount = array();
        foreach($list_value2 as $key=>$item):
				switch($item->vTable){
					case "C": $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); 
		  						$AccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '". $item->vACode  ."'"); 
								break;
					case "V": $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'");
								$AccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '". $item->vACode  ."'"); 
								break;
					default: $item->vAcSubCode = $item->vACode ; $AccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
				$subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->vAcSubCode."'");

            $open_list_value =  $this->sub_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable);
            $openbal = $open_list_value['debit'] - $open_list_value['credit'];
                    if( $open_list_value['debit'] ==0 && $open_list_value['credit'] ==0 && $item->debit ==0 && $item->credit == 0) continue;
                    $recoed_present +=1;
		?>
			
        <tr>
            <td><?php echo $key+1;?></td>
			<td><?php echo $subAccCode ; ?> </td>
			<td><strong><?php echo $subAccName ; ?> </strong></td>
			<td><?php echo $AccountCode ; ?> </td>			
			<td><strong><?php echo $AccountName ; ?> </strong></td>
            <td><?php echo $openbal;?></td>
            <td><?php echo $item->debit;?></td>
            <td><?php echo $item->credit;?></td>
			
            <td><?php 
						$balance =  $item->debit - $item->credit + $openbal;			
						echo $balance;?></td>
        </tr>
            <?php $i = $i + 1;
            $totalopenbal  += $openbal;
            $totalDebit   = $totalDebit+$item->debit;
            $totalCredit  = $totalCredit+$item->credit;
            $totalBalance = $totalDebit - $totalCredit + $totalopenbal;
        endforeach;
        if($recoed_present !=1): ?>
    <tr>
        <td colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalopenbal;?></td>
        <td><?php echo $totalDebit;?></td>
        <td><?php echo $totalCredit;?></td>
        <td><?php echo $totalBalance;?></td>
    </tr>

    <?php else:?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php endif;
    } 
    else{ ?>
    <tr>
        <td colspan="9" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
   </tbody>
</table>
