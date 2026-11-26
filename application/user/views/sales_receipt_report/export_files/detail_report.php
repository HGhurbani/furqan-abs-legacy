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
            <th colspan="13"><h3><?php echo DETAILREPORT;?></h3></th>
        </tr>
        <tr>
            <th colspan="13">

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
                <th align="left"><?php echo FROMDATE;?></th>
                <th align="left"><?php echo $fromDate;?></th>
                <th align="left"><?php echo TODATE;?></th>
                <th align="left"><?php echo $toDate;?></th>
                <th align="left"><?php echo ACCOUNTCODE;?></th>
                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," where nId='".$search_acc."'");?></th>
                <th align="left"><?php echo ACCOUNT;?></th>
                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," where nId='".$search_acc."'");?></th>
                <th colspan='5'>&nbsp;</th>
            </tr>
            <tr><td></td></tr>
        <tr>
            <th align="left"><?php echo SRNO;?></th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <th align="left"><?php echo ISSUEDBY;?></th>
            <th align="left"><?php echo DOCUMENTNUMBER;?></th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <th align="left"><?php echo DOCUMENTNUMBER;?></th>
            <th align="left"><?php echo CARDNAME;?></th>
            <th align="left"><?php echo TICKETNAME;?></th>
            <th align="left"><?php echo PASSNAME;?></th>
            <th align="left"><?php echo DESCRIPTION;?></th>
            <th align="left"><?php echo DEBIT;?></th>
            <th align="left"><?php echo CREDIT;?></th>
            <th align="left"><?php echo BALANCE;?></th>
        </tr>

    <?php
    if(!empty($list_value)){?>

    <tbody>

        <?php
        foreach($list_value as $key=>$item):

            $sector = '';
            switch($item->vType){
                case 'Tkt': 
                    $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
                    break;
                case 'Xo':
                    $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");
                    break;
                default : $sector = '';
            } //end case
            $salesId = $Invpath = $InvDate =$InvNum = $credit = $debit = '';
            if($item->salesClosedId){
                $salesId  = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId='".$item->salesClosedId."'");
                $Invpath = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$item->salesClosedId.'/'.$salesId.'/cl';
                $InvDate =  $this->common_model->get_field_name('SalesClosing','dDate', " WHERE nSCId = ".$salesId ." ");
                $InvDate = date("d-M-y", strtotime($InvDate));
                $InvNum =  $this->common_model->get_field_name('SalesClosing','vInvoiceNumber', " WHERE nSCId = ".$salesId ." ");
                $credit = $item->nAmountDr;
            }// end if

            if($search_tabletype == 'A') $credit = $item->nAmountDr;
            else $debit = $item->nAmountDr;

            if($search_tabletype == 'A' && $item->salesClosedId) $debit = $item->nAmountDr;
?>
    
                <tr>
                    <td><?php echo $key+1;?></td>
                    <td><?php echo date("d-M-y", strtotime($item->dDate));?></td>
                    <td><?php $empId = $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$item->vEnteredBy."' "); echo  stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$empId."'"));
?></td>
                    <td><a href="<?php echo base_url(). $item->path .$item->nSRId;?>"><?php echo $item->vDocNumber;?></a></td>
                    <td><?php echo $InvDate;?></td>
                    <td><a href="<?php echo $Invpath;?>"><?php echo $InvNum;?></a></td>
                    <td><?php echo $item->CardName;?></td>
                    <td><?php echo $item->Number;?></td>
                    <td><?php echo $item->passName;?></td>
                    <td><?php echo $sector. " " . $item->vRemarks;?></td>
                    <td><?php echo $debit;?></td>
                    <td><?php echo $credit;?></td>
                    <td><?php echo $debit - $credit;?></td>
                </tr>
                <?php 
                $i = $i + 1;
                $totalDebit += $debit;
                $totalCredit += $credit;
                //$totalopenDebit = $openDebit;
                //$totalopenCredit = $openCredit;

                endforeach;?>


        <tr>
            <td colspan="10" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td><?php echo $totalDebit;?></td>
            <td><?php echo $totalCredit;?></td>
            <td><?php echo $totalDebit - $totalCredit;?></td>
        </tr>
        <tr> <td colspan='13'> &nbsp; </td> </tr>

<?php }else { ?>
   <tr>
        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

   </tbody>
</table>
