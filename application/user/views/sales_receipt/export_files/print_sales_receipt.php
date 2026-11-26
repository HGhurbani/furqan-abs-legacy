    <table width="100%" border="0" cellspacing="20" cellpadding="20" class="printForm">
        <tbody>
        <?php
            $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
            $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
            $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
            $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
            $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
            $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
            $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
            $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");
            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");
            ?>
            <tr>
                <td colspan="4">

                    <div class="print_desc">
                    <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                        <?php echo $addr;?><br>
                        <?php echo PHONE;?>: <?php echo $phone;?>
                        <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?><br>
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>

                <td colspan="2" align="right">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" align="center"><h5><?php  echo SALESRECEIPT;?></h5></td>
            </tr>
            <tr>
                <td><?php echo DOCNUMBER;?></td>
                <td><div class="smallBox"><?php echo $this->txtDocNumber;?></div></td>
<!--                <td colspan="4"></td>
            </tr>
            <tr> -->
                <td><?php echo CUSTDATE;?></td>
                <td><div class="smallBox"><?php echo $this->txtDate;?></div></td>
<!--                <td colspan="2">&nbsp;</td> -->
            </tr>
            <tr>
               <td><?php echo TONAME;?></td>
                <td><div class="smallBox"><?php echo $this->txtRefName;?></div></td>
            </tr>
            <tr><td></td></tr>
           <tr>
                <td colspan="6">
                    <table width="100%" class="reportForm">
                        <tr>
                            <td><?php echo SRNO;?></td>
                            <td><?php echo TTYPE;?></td>
                            <td><?php echo AIRLINE;?></td>
                            <td><?php echo TICKETNAME;?></td>
                            <td><?php echo PASSENGER;?></td>
                            <td><?php echo OTHERDETAILS;?></td>
                            <td><?php echo CREDITCARD;?></td>
                            <td><?php echo AMOUNT;?></td>
                        </tr>
                        <?php foreach($chkCard as $k=>$v): ?>
                              <tr>
                                    <td>
                                        <?php echo $k+1;?>
                                    </td>

                                    <td><label>
                                        <?php echo stripslashes($chkType[$k]);?>
                                    </label></td>

                                    <td>
                                        <?php echo stripslashes($chkAirline[$k]);?>
                                    </td>

                                    <td>
                                        <?php echo stripslashes($chkTktNumber[$k]);?>
                                    </td>

                                    <td>
                                        <?php  if(!empty($chkTktNumber[$k])){ echo $chkPass[$k]; } else { echo $this->txtRefName; };?>
                                    </td>

                                    <td>
                                        <?php echo $chkDesc[$k];?>
                                    </td>


                                    <td>
                                        <?php echo $this->common_model->get_field_name('Card','vName', " WHERE nCId = ".$chkCard[$k] ."");?>
                                    </td>

                                    <td>
                                        <?php echo $chkAmount[$k];?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        ?>
                    </table>
                </td>
            </tr>
            <tr><td></td></tr>
            <tr>
                <td><?php echo REMARKS;?></td>
                <td colspan="5"><div class="bigBox"><?php echo $this->txtRemarks;?></div></td>
            </tr>
           <tr>
                <td colspan="6">&nbsp;</td>
            </tr>

            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
                        <tr>
                            <td><?php echo PREPAREDBY;?></td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo APPROVEDBY;?></td>
                            <td><?php echo RECEIVEDBY;?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$this->session->userdata('AbsAdmin')."'");?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
        </tbody>
    </table>
