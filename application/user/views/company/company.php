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

<div id="boxes">
    <?php
    $attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
    $this->load->model('common_model');
    echo $this->common_model->successMsg($this->uri->segment(4));
    ?>
    <?php $this->load->view('general/admin_menu');?>
    <div class="formHead">
        <h4><?php echo $this->showTitle;?></h4>
    </div>
    <div class="formBorder">
        <?php echo form_open(base_url().'index.php/company/delrecords',$attributes); ?>
        <?php echo form_hidden('nCId', $this->uri->segment(3));?>
        <div id="result">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
                <thead>
                <tr>
                    <th align="left">#</th>
                    <th align="left"><?php echo COMPANYCODE;?></th>
                    <th align="left"><?php echo COMPANYNAME;?></th>
                    <th align="left"><?php echo ACTIVE;?></th>
                    <th align="left"><?php echo ACTION;?></th>
                </tr>
                </thead>

                <tbody>
                <?php $i= 1;?>
                <?php foreach($list_value as $key=>$item):?>
                <tr>
                    <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
                    <td align="left"><?php echo stripslashes($item->vCode);?></td>
                    <td align="left"><?php echo stripslashes($item->vTitle);?></td>
                    <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
                    <td align="left">
                        <!-- Icons -->
                        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                        <a href="<?php echo base_url();?>index.php/company/editCompany/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                        <?php endif;?>

                    </td>
                </tr>
                    <?php $i= $i+1;?>
                    <?php endforeach;?>
                <?php if(empty($list_value))
                {
                    ?>
                <tr>
                    <td colspan="8" align="center"><?php echo SORRYNORECORD;?></td>
                </tr>
                    <?php }//end if
                ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php echo $this->common_model->p->show();?>
            </div> <!-- End .pagination -->
            <div class="clear"></div>

        </div><!-- Mask to cover the whole screen -->
        </form>
    </div>
</div>
<div id="blackout"></div>
</div>
</div>