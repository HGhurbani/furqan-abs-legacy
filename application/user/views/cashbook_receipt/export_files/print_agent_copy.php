<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
    <tbody>
    <tr>
        <td>
            <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>">
        </td>
        <td colspan="3">
            <span style="font-size: 16px;font-weight: bold;"><?php echo $this->common_model->getCompanyName();?></span><br>
            <span style="font-size: 12px;font-weight: bold;"><?php echo $this->common_model->getCompanyLocation();?></span><br>
            <?php echo $this->common_model->getCompanyAddress();?>
        </td>
        <td colspan="6">

        </td>
    </tr>
    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate1;?></div></td>
        <td colspan="8"><span style="font-size: 14px;font-weight: bold;"><?php echo CASHRECEIPT;?></span></td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td><?php echo REFNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefNumberMain;?></div></td>
        <td><?php echo REFDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtRefDate;?></div></td>
    </tr>

    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="5">&nbsp;</td>
    </tr>

    <tr>
        <td><?php FORPRINT;?></td>
        <td colspan="9"><div class="bigBox"></div></td>
    </tr>
    <tr>
        <td><?php AMOUNT;?></td>
        <td>&nbsp;</td>
        <td colspan="9"><div class="bigBox"></div></td>
    </tr>
    </tbody>
</table>
</body>
</html>
