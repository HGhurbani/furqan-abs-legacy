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
?>
<!DOCTYPE html>
<head>
<title>Print Agent Copy</title>
                <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
                <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
                <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
                </head>
                <body>
<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#btnPrint').click(function(e) {
        window.print() ;
    });
});
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">
<tbody>
<tr>
    <td>
        <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>">
    </td>
    <td>
        <span style="font-size: 16px;font-weight: bold;"><?php echo $this->common_model->getCompanyName();?></span><br>
        <span style="font-size: 12px;font-weight: bold;"><?php echo $this->common_model->getCompanyLocation();?></span><br>
        <?php echo $this->common_model->getCompanyAddress();?>
    </td>
    <td>
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTAGENTPAGE;?>" class="btnGeneral">
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo TO;?></td>
    <td></td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo XONO;?></td>
    <td>
        <label><?php echo $this->txtTktName;?></label>
    </td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo GDSPNR;?></td>
    <td><?php echo stripslashes($this->txtGdsPNR);?></td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo CUSTDATE;?></td>
    <td><?php echo $this->txtDate;?></td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo AIRPNR;?></td>
    <td><?php echo stripslashes($this->txtAirPNR);?></td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td colspan="3"><?php echo PRINTXODETAIL;?></td>
</tr>

<tr>
    <td>
        <table width="100%" class="reportForm">
            <tr><td><?php echo PASSENGER;?></td></tr>
            <?php
                foreach ($ddlPassenger as $key1 => $pitem): ?>
                    <tr>
                        <td><label>
                            <?php echo stripslashes($ddlPassenger[$key1]);?>
                        </label></td>
                    </tr>
                <?php
                endforeach;?>
        </table>
    </td>
    <td colspan="2">
        <table width="100%" class="reportForm">
            <tr>
                <td colspan="2"><?php echo SECTOR;?></td>
                <td><?php echo CARRIER;?></td>
                <td><?php echo FLIGHTNO;?></td>
                <td><?php echo TKTCLASS;?></td>
                <td><?php echo TRAVELDATE;?></td>
                <td><?php echo TIMEDEP;?></td>
                <td><?php echo TIMEARR;?></td>
                <td><?php echo STATUS;?></td>
            </tr>
            <?php
            foreach ($txtCarrier as $key => $aitem):
                ?>
                <tr>
                    <td><?php echo stripslashes($txtSector[$key]);?></td>
                    <td><?php echo stripslashes($txtSector2[$key]);?></td>
                    <td><?php echo stripslashes($aitem);?></td>
                    <td><?php echo stripslashes($txtFlightNo[$key]);?></td>
                    <td><?php echo stripslashes($txtClass[$key]);?></td>
                    <td><?php echo stripslashes($txtTravelDate[$key]);?></td>
                    <td><?php echo stripslashes($txtTimeDep[$key]);?></td>
                    <td><?php echo stripslashes($txtTimeArr[$key]);?></td>
                    <td><?php echo stripslashes($txtStatus[$key]);?></td>
                </tr>
                <?php
            endforeach;
            ?>
        </table>
    </td>
</tr>

<tr>
    <td>
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo REMARKS;?>&nbsp;</td>
                <td><label>
                    <?php echo stripslashes($this->txtRemarks);?>
                </label></td>
            </tr>
        </table>
    </td>
    <td colspan="2">
        <table width="100%" class="reportForm">
             <tr>
                 <td><?php echo ISSUEDBY;?> </td>
                 <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?></td>
             </tr>
            <tr>
                <td><?php echo CONTACTPHONE;?> </td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vPhone'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td><?php echo EMAIL;?> </td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vEmail'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo NOTES;?></td>
    <td></td>
</tr>
</tbody>
</table>
</body>
</html>