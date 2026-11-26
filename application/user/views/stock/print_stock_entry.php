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
    <title>Print Accounts Copy</title>
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
            <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTACCOUNTPAGE;?>" class="btnGeneral">
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" align="center"><h5><?php echo STOCKREPORT;?></h5></td>
    </tr>
    <?php
        if(!empty($chkStockType))
        {

            foreach($chkStockType as $key => $stockitem):
                ?>
        <tr>
            <td><?php echo STOCKTYPE;?></td>
            <td colspan="2">
                <label>
                    <div class="smallBox">
                        <?php echo $this->common_model->get_field_name('Stock','vName'," WHERE nStkId='".$chkStockType[$key]."'");?>
                    </div>
                </label>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <table width="100%" class="reportForm">
                    <tr>
                        <td><?php echo SRNO;?></td>
                        <td><?php echo STOCKTYPE;?></td>
                        <td><?php echo STOCKCODE;?></td>
                        <td><?php echo LOCATIONNAME;?></td>
                        <td><?php echo START;?></td>
                        <td><?php echo END;?></td>
                        <td><?php echo STOCKQTY;?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $key+1;?>
                        </td>

                        <td>
                            <?php echo $this->common_model->get_field_name('Stock','vName'," WHERE nStkId='".$chkStockType[$key]."'");?>
                        </td>

                        <td>
                            <?php echo stripslashes($chkStockCode[$key]);?>
                        </td>

                        <td>
                            <?php //echo stripslashes($chkStartNo[$key]);?>
                        </td>

                        <td>
                            <?php echo stripslashes($chkStartNo[$key]);?>
                        </td>

                        <td>
                            <?php echo stripslashes($chkEndNo[$key]);?>
                        </td>

                        <td>
                            <?php echo stripslashes($chkQuantity[$key]);?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <?php
            $total += $chkQuantity[$key];
        endforeach; ?>
        <tr>
            <td colspan="3">
                <table width="100%" class="reportForm">
                    <tr>
                        <td colspan="2" align="right"><?php echo GRANDTOTAL;?></td>
                        <td align="center"><?php echo $total;?></td>
                    </tr>
                </table>
            </td>
        </tr>
            <?php
        }
    ?>

    </tbody>
</table>
</body>
</html>