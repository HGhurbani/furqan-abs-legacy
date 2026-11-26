<table width="100%" border="0"  cellspacing="10" cellpadding="10" class="reportForm" id="reportForm">

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
            <th colspan="12"><h3><?php echo SUMMARYREPORT;?></h3></th>
        </tr>
        <tr>
            <th colspan="12">

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
        <th colspan="6" align="left">&nbsp;</th>
        <th align="center" colspan="2" align="center"><?php echo OPENING;?></th>
        <th align="center" colspan="2" align="center"><?php echo TRANSACTION;?></th>
        <th align="center" colspan="2" align="center"><?php echo BALANCE;?></th>
    </tr>

    <tr>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left"><?php echo COUNT;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
    </tr>
 

    <?php
    $chkAccount_acc = array();
    $chkAccount_subacc = array();
    $totalopenDebit   = 0; 
    $totalopenCredit  = 0; 
    $totalDebit       = 0; 
    $totalCredit      = 0; 
    $totalbalDebit    = 0; 
    $totalbalCredit   = 0; 

    if(!empty($list_value)){
        $tablArray = array();?>

    <tbody>

        <?php
        $chkAccount_acc = array();
        $chkAccount_subacc = array();
        foreach($list_value as $key=>$item):
            $chkAccount_acc[] = $item->account;
            $chkAccount_subacc[] = $item->subacc;

            ?>

<?php
            $accSubName = '';
            $accSubCode = '';
            switch($item->vTable)
            {
                case "C":
                    $accSubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->subacc."'");
                    break;

                case "V":
                    $accSubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->subacc."'");
                    break;

                case "AG":
                    $accSubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->subacc."'");
                    break;

                case "O":
                    $accSubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->subacc."'");
                    break;
            }


                $open_list_value = array();
                $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($item->account, $item->subacc,$item->vTable,"location");
                $balance = $open_list_value['debit'] +  $item->debit -  $open_list_value['credit']  - $item->credit;
                if($balance > 0 )
                    $balDebit = $balance;
                else 
                    $balCredit = $balance * -1;
           ?>
                <tr>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
                    <td align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");?></td>
                    <td align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");?></td>
                    <td align="left"><?php echo $accSubCode;?></td>
                    <td align="left"><?php echo $accSubName;?></td>
                    <td><?php echo $item->count;?></td>
                    <td><?php echo $open_list_value['debit'];?></td>
                    <td><?php echo $open_list_value['credit'];?></td>
                    <td><?php echo $item->debit;?></td>
                    <td><?php echo $item->credit;?></td>
                    <td><?php echo $balDebit;?></td>
                    <td><?php echo $balCredit;?></td>
                </tr>
<?php        

        $totalopenDebit   += $open_list_value['debit'];
        $totalopenCredit  += $open_list_value['credit'];
        $totalDebit       += $item->debit;
        $totalCredit      += $item->credit;
        $totalbalDebit    += $balDebit;
        $totalbalCredit   += $balCredit;

endforeach;?>

        <?php } 
//displaying remaining accounts
            $account_list = $this->ac_report_model->accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc);
        foreach($account_list as $key => $row){
            $open_list_value = array();
            $openDebit = $openCredit = $openVal = 0;
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($row['accountId'],$row['subAccountId'],$row['vTable']);
            if( $open_list_value['debit'] != 0 ||  $open_list_value['credit'] != 0){
                $balance = $open_list_value['debit'] -  $open_list_value['credit'];
                if($balance > 0 )
                    $balDebit = $balance;
                else 
                    $balCredit = $balance * -1;
 
        ?>
            <tr>
                <td>&nbsp;</td>
                <td align="left"><?php echo $row['accountCode']; ?></td>
                <td align="left"><?php echo $row['accountName']; ?></td>
                <td align="left"><?php echo $row['subAccountCode'];?></td>
                <td align="left"><?php echo $row['subAccountName'];?></td>
                <td>&nbsp;</td>
                <td><?php echo $open_list_value['debit'];?></td>
                <td><?php echo $open_list_value['credit'];?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?php echo $balDebit;?></td>
                <td><?php echo $balCredit;?></td>
                </tr>



    <?php
        $totalopenDebit   += $open_list_value['debit'];
        $totalopenCredit  += $open_list_value['credit'];
        $totalbalDebit    += $balDebit;
        $totalbalCredit   += $balCredit;

            }//endif
        }// end foreach
    if(!($totalDebit ==0 && $totalCredit ==0 && $totalopenDebit ==0 && $totalopenCredit == 0 && $totalbalCredit ==0 && $totalbalDebit ==0)){

            ?>

        <tr>
            <td colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td><?php echo $totalopenDebit;?></td>
            <td><?php echo $totalopenCredit;?></td>
            <td><?php echo $totalDebit;?></td>
            <td><?php echo $totalCredit;?></td>
            <td><?php echo $totalbalDebit;?></td>
            <td><?php echo $totalbalCredit;?></td>
        </tr>
<?php }else { ?>
   <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>


   </tbody>
</table>
