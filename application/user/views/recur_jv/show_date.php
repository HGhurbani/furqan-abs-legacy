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
<script type="text/javascript">
    $(document).ready(function(){
        $('.btnGeneral').click(function(e) {
            var nVal = this.id;
            nVal     = nVal.split('_');
            $.ajax({
                type:'POST',
                data:{scheduleId:nVal[1]},
                url:'<?php echo base_url().'index.php/recur_jv/insertJv/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
                dataType:'html',
                success: function(response) {				
				$('#generate_'+nVal[1]).hide();
			$('#btn_'+nVal[1]).show();	
			$('#btn_'+nVal[1]).val("JV CREATED");				
                }
            });
        });
    });
</script>
    <table id="dataTable2" title="dataTable2">
        <tr>
            <td><?php echo SRNO;?></td>
            <td><?php echo CUSTDATE;?></td>
            <td><?php echo STATUS;?></td>
            <td>&nbsp;</td>
        </tr>
<style>
.btnGeneral1 {
   
    background-color: #2CAB75;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    border: 1px solid #dcdcdc;
    display: inline-block;
    color: #fff;
    font-weight: bold;
    padding: 6px 20px;
    text-decoration: none;
    text-shadow: 1px 1px 0px rgba(0,0,0,0.30);
}
</style>   
   <?php
        if(!empty($chkscId))
        {
            foreach($chkscId as $key => $aitem):
                ?>
                <tr>
                    <td><input type="checkbox" checked onclick="return false" name="chkbox2[]" id="chkSelect2<?php echo $key;?>" class="chkSelect2" value="<?php echo $chkDetailID[$key];?>"></td>

                    <td><input name="txtScheduleDate[]" id="txtScheduleDate<?php echo $key;?>" class="scheduleDate txtScheduleDate" type="text" value="<?php echo date('d-m-Y',strtotime($chkscDate[$key]));?>"></td>

                   

                    <?php 					$jv=$this->common_model->get_field_name('RecurringJvShedule','jvid'," WHERE nId = '".$chkscId[$key]."'");					$jvdoc=$this->common_model->get_field_name('Jv','vDocNumber'," WHERE nJVId = '".$jv."'");					if(!empty($jvdoc)){						$status="CLOSED";					}else{						$status="OPEN";					}					//echo $jv;?>					 <td><input name="txtStatus[]" id="txtStatus<?php echo $key;?>" class="textfield4" type="text" value="<?php echo stripslashes($status);?>"></td>

                    <td align="center">
                        <?php if($chkscDate[$key] < date('Y-m-d') && $chkscStatus[$key] == 'Open') { ?>
						<input type="button" style="display:none;" value="<?php echo GENERATE;?>" class="btnGeneral1" id="btn_<?php echo $chkscId[$key];?>" />
                        
                        <input type="button" value="<?php echo GENERATE;?>" class="btnGeneral" id="generate_<?php echo $chkscId[$key];?>"/>
                        <?php }elseif($chkscDate[$key] < date('Y-m-d') && $status == 'OPEN') {?>						
						<input type="button" style="display:none;" value="<?php echo GENERATE;?>" class="btnGeneral1" id="btn_<?php echo $chkscId[$key];?>" />
                        
						<input type="button" value="<?php echo GENERATE;?>" class="btnGeneral" id="generate_<?php echo $chkscId[$key];?>"/>						<?php }else{							echo $jvdoc; ?>						<?}?>
                    </td>
                </tr>
                <?php
            endforeach;
        }//end if
        else
        {
            for($i = 0; $i< $freqency; $i++):

                switch($i)
                {
                    case "0":
                        $sdate = $date;
                    break;

                    default:
                        $sdate = date('d-m-Y',strtotime($date ."+".$i."months"));
                    break;

                }//end swith
        ?>
            <tr>
                <td><input type="checkbox" checked onclick="return false" name="chkbox2[]" id="chkSelect2" class="chkSelect2" value="" readonly="readonly"></td>

                <td><input name="txtScheduleDate[]" id="txtScheduleDate" class="scheduleDate txtScheduleDate" type="text" value="<?php echo $sdate;?>" readonly="readonly"></td>

                <td><input name="txtStatus[]" id="txtStatus" class="textfield4" type="text" value="Open" readonly="readonly"></td>

                <!--<td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_>" title="Delete"/></td>-->
            </tr>
            <?php
                endfor;
        }//end else
        ?>
    </table>