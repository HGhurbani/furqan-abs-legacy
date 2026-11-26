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
            <th colspan="9"><h3><?php echo  DAILYREPORT;?></h3></th>
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
    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo SALESTYPE;?></th>
        <th align="left"><?php echo COUNT;?></th>
<!--        <th align="left"><?php echo DESCRIPTION;?></th> -->
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
    </tr>
 

    <?php
    $chkAccount_acc = array();
    $chkAccount_subacc = array();
    if(!empty($list_value)){
        $tablArray = array();?>

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
                    <td><?php echo $openDebit;?></td>
                    <td><?php echo $openCredit;?></td>
                </tr>
        <?php
                $totalopenDebit += $openDebit;
                $totalopenCredit  += $openCredit;

                $totalDebit_perAccc = 0;
                $totalCredit_perAccc = 0;
                $openVal = 0;
            endif;

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

            ?>
                <tr> <td colspan='9'> &nbsp; </td> </tr>
            <tr>
                <th align="left"><?php echo ACCODE;?></th>
                <th align="left"><?php echo ACCCODENAME;?></th>
                <th align="left"><?php echo SUBCODE;?></th>
                <th align="left"><?php echo SUBNAME;?></th>
                <th align="left" colspan="5">&nbsp;</th>
            </tr>
            <tr>
                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");?></th>
                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");?></th>
                <th align="left"><?php echo $accSubCode;?></th>
                <th align="left"><?php echo $accSubName;?></th>
                <th align="left" colspan="1">&nbsp;</th>
                <th align="center" colspan="2"><?php echo TRANSACTION;?></th>
                <th align="center" colspan="2"><?php echo BALANCE;?></th>
            </tr>
            <tr>
                <td colspan="5" align="center"><strong><?php echo YEARCLOSING;?></strong></td>
                <td><?php echo $year_closing['debit'];?></td>
                <td><?php echo $year_closing['credit'];?></td>
                <td><?php echo ($year_closing['debit'] - $year_closing['credit']);?></td>
                <td>&nbsp;</td>
            </tr>
    <?php
            $this->session->unset_userdata('newDebit');
            //$openVal = $this->ac_report_model->getCalDebit($open_list_value['debit'],$open_list_value['credit']);

            $open_list_value = array();
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($item->account, $item->subacc,$item->vTable);

            $totalDebit_perAccc +=  $open_list_value['debit'];
            $totalCredit_perAccc += $open_list_value['credit'];
            $totalDebit +=  $open_list_value['debit'];
            $totalCredit += $open_list_value['credit'];

            $openVal = round($openVal,2) + (round($open_list_value['debit'],2) - round($open_list_value['credit'],2));
        
                if($openVal >= 0):
                    $openCredit = 0;
                    $openDebit = $openVal;
                endif;

                if($openVal <= 0):
                    $openCredit = substr($openVal,1,strlen($openVal));
                    $openDebit = 0;
                endif;

    ?>
            <tr>
                <td colspan="5" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
                <td><?php echo $open_list_value['debit'];?></td>
                <td><?php echo $open_list_value['credit'];?></td>

                <td><?php echo $openDebit;?></td>
                <td><?php echo $openCredit;?></td>
            </tr>
        <?php endif; 

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

            //$openVal = $this->ac_report_model->getCalDebit($debit,$credit);
            $openVal = round($openVal,2) + (round($debit,2) - round($credit,2));

            if($openVal >= 0):
                $openCredit = 0;
                $openDebit = $openVal;
            endif;

            if($openVal <= 0):
                $openCredit = substr($openVal,1,strlen($openVal));
                $openDebit = 0;
            endif;
            ?>
                <tr>
                    <td><?php echo $key+1;?></td>
                    <td><?php echo date("d-M-y", strtotime($item->date));?></td>
                    <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
                    <td><?php echo $item->entry;?></td>
                    <td><?php echo $item->count;?></td>
<!--                    <td><?php echo $item->description;?></td> -->
                    <td><?php echo $debit;?></td>
                    <td><?php echo $credit;?></td>
                    <td><?php echo $openDebit;?></td>
                    <td><?php echo $openCredit;?></td>
                </tr>
                <?php $i = $i + 1;
                $totalDebit += $debit;
                $totalCredit += $credit;
                //$totalopenDebit = $openDebit;
                //$totalopenCredit = $openCredit;

                $totalDebit_perAccc += $debit;
                $totalCredit_perAccc += $credit;
                $totalopenDebit_perAccc = $openDebit;
                $totalopenCredit_perAccc = $openCredit;

                if(!in_array($item->document,$tablArray))
                {
                    switch($item->mainMode)
                    {
                        case "Dr":
                            $cashDebit += $item->mainAmount;
                            break;

                        case "Cr":
                            $cashCredit += $item->mainAmount;
                            break;
                    }//end swtich
                    ?>
                <?php
                }//end if
            $tablArray[]     = $item->document; ?>

<?php        endforeach;?>


            <?php 
            $totalopenDebit += $openDebit;
            $totalopenCredit  += $openCredit;
            $totBal = $totalopenDebit - $totalopenCredit;
            $totalopenDebit = ($totBal > 0) ? $totBal : 0;
            $totalopenCredit = ($totBal < 0) ? $totBal * -1 : 0;
        ?>

        <tr>
            <td colspan="5" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td><?php echo $totalDebit_perAccc;?></td>
            <td><?php echo $totalCredit_perAccc;?></td>
            <td><?php echo $openDebit;?></td>
            <td><?php echo $openCredit;?></td>
        </tr>
        <tr> <td colspan='9'> &nbsp; </td> </tr>

        <?php } 
//displaying remaining accounts
            $account_list = $this->ac_report_model->accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc);
        foreach($account_list as $key => $row){
            $open_list_value = array();
            $openDebit = $openCredit = $openVal = 0;
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($row['accountId'],$row['subAccountId'],$row['vTable']);
            if( $open_list_value['debit'] != 0 ||  $open_list_value['credit'] != 0){

                $totalDebit_perAccc +=  $open_list_value['debit'];
                $totalCredit_perAccc += $open_list_value['credit'];
                $totalDebit +=  $open_list_value['debit'];
                $totalCredit += $open_list_value['credit'];


                $openVal = round($openVal,2) + (round($open_list_value['debit'],2) - round($open_list_value['credit'],2));

                if($openVal >= 0):
                    $openCredit = 0;
                $openDebit = $openVal;
                endif;

                if($openVal <= 0):
                    $openCredit = substr($openVal,1,strlen($openVal));
                $openDebit = 0;
                endif;
                $totalopenDebit += $openDebit;
                $totalopenCredit  += $openCredit;
                $totBal = $totalopenDebit - $totalopenCredit;
                $totalopenDebit = ($totBal > 0) ? $totBal : 0;
                $totalopenCredit = ($totBal < 0) ? $totBal * -1 : 0;

?>

            <thead>
            <tr>
                <th align="left"><?php echo ACCODE;?></th>
                <th align="left"><?php echo ACCCODENAME;?></th>
                <th align="left"><?php echo SUBCODE;?></th>
                <th align="left"><?php echo SUBNAME;?></th>
                <th align="left" colspan="5">&nbsp;</th>
            </tr>
            <tr>
                <th align="left"><?php echo $row['accountCode']; ?></th>
                <th align="left"><?php echo $row['accountName']; ?></th>
                <th align="left"><?php echo $row['subAccountCode'];?></th>
                <th align="left"><?php echo $row['subAccountName'];?></th>
                <th align="left" colspan="1">&nbsp;</th>
                <th align="center" colspan="2"><?php echo transaction;?></th>
                <th align="center" colspan="2"><?php echo balance;?></th>
            </tr>
             <tr>
                <td colspan="5" align="center"><strong><?php echo yearclosing;?></strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
                <td><?php echo $open_list_value['debit'];?></td>
                <td><?php echo $open_list_value['credit'];?></td>

                <td><?php echo $openDebit;?></td>
                <td><?php echo $openCredit;?></td>
            </tr>
            </thead>
                <td colspan='9'>&nbsp;</td>



<?php
            }//endif
        }// end foreach
    if(!($totalDebit ==0 && $totalCredit ==0 && $totalopenDebit ==0 && $totalopenCredit == 0)){

            ?>

        <tr>
            <td colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td><?php echo $totalDebit;?></td>
            <td><?php echo $totalCredit;?></td>
            <td><?php echo $totalopenDebit;?></td>
            <td><?php echo $totalopenCredit;?></td>
        </tr>
<?php }else { ?>
   <tr>
        <td colspan="9" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>



    </tbody>
</table>
