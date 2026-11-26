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

        $('.txtDoc').click(function()
        {
            var tktId    = this.id;
            var tktType  = this.title;
            var type     = $('#ddlAllocation').val();
            var getId    = tktId.split('txtDoc');
            var getId2 = getId[1].split('-');
			var getDetId = $('#txtDetId'+getId[1]).val();
			var accId    = $('#ddlAccount').val();
			var getAccId = accId.split('-');
            if(accId == 0) getAccId[1] = 'A';
			var subacc   = $('#ddlSubAccount').val();
            var getSubAccId = subacc.split('-');
            //used to store the selected receipt id
/*            $('#txtDoc' + $("#currentId").val()).removeClass('currentvalue');
            $("#currentId").val(getId[1]);
            this.addClass('currentvalue);*/
/*
alert(getId[1]);
alert(tktType);
alert(type);
alert(getAccId[0]);
alert(getAccId[1]);
alert(subacc);
*/
            $.ajax({
                type:'POST',
                data:{tableId:getId2[0],detTableId:getDetId,tktType:tktType,allType:type,ddlAccount:getAccId[0],tableVal:getAccId[1],ddlSubAccount:getSubAccId[0]},
                url:'<?php echo base_url().'index.php/ar_allocation/getAllocationDetails/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#txtDoc' + $("#currentId").val()).removeClass('currentvalue');
                    $("#currentId").val(getId[1]);
                    $('#' +tktId).addClass('currentvalue');
                    $('#dataTableTd2').removeClass('hidden');
                    $('#showDataTable2').html(response);
                    $('#allocReceipt').val( $('#' +tktId).val());
                    $('#allocReceipt_p').val( $('#' +tktId).val());										
					var selectedReceiptId = $("#currentId").val();	
					var credita= $('#txtCreditAmt' +selectedReceiptId).val();							
					var selectedRe=$('#txtAllocatedAmt1' +selectedReceiptId).val();	
					var selectedRemark=$('#remarks' +selectedReceiptId).val();						
					$('#allocReceipt_p_tot').text(selectedRe);
					camt = parseFloat(credita) - parseFloat(selectedRe);
					camt= parseFloat(camt).toFixed(3);
					$('#allocReceipt_p_tot1').text(camt);
					$('#allocReceipt_p_rem').text(selectedRemark);
					$('#allocReceipt_p_rem_p').text(selectedRemark);
					
                }
            });
        });

    });
</script>

<table id="dataTable" title="dataTable" style="border:1px solid #C9C9C9;padding-bottom: 10px;">
    <?php if(!empty($list_allc1)){
		//var_dump($list_allc1);
    ?>
        <tr>
            <th colspan="7" align="left"> <?php echo SALESFINANCEDOCUMENTS; ?> </th>
        </tr>
<thead style="display:block;">

    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><?php echo DOCNUMBER;?></td>
        <td><?php echo AMOUNT;?></td>
        <td><?php echo ALLOCATEDAMT;?></td>
    </tr>
	</thead>
	<tbody style="display: block;height: 300px;overflow-y: auto;overflow-x: hidden;">

    <?php $x=0; foreach($list_allc1 as $key => $item){
        ?>
        <tr>
<?php if($x==0){
	//$k=$item->idVal . "-" .  $item->type;
	} 
$it = $item->idVal . "-" .  $item->type;


if($it==$k){	$ba= $it;
	$item->idVal=$item->idVal+20000 . "-" .  $item->type;
	$k=$item->idVal;
	}elseif($ba==$it){	$item->idVal=$item->idVal+30000 . "-" .  $item->type;	$k=$item->idVal;	}
else{
	$item->idVal=$item->idVal . "-" .  $item->type;
	$k=$item->idVal;}


 ?>
            <td><input name="txtDate1[]" id="txtDate1<?php echo $item->idVal;?>" class="textfield5" type="text" value="<?php echo $item->dDate;?>" readonly="readonly"></td>

            <td><input name="txtDoc[]" id="txtDoc<?php echo $item->idVal;?>" class="txtDoc" type="text" title="<?php echo $item->type;?>" value="<?php echo $item->document;?>" readonly="readonly"></td>
			<input name="remarks" id="remarks<?php echo $item->idVal;?>" class="remarks" type="hidden"  value="<?php echo $item->remarks;?>">

            <input name="txtDetId[]" id="txtDetId<?php echo $item->idVal;?>"  type="hidden"  value="<?php echo $item->detIdVal;?>">

            <td><input name="txtCreditAmt[]" id="txtCreditAmt<?php echo $item->idVal;?>" class="textfield4" type="text" value="<?php echo $item->creditAmt;?>" readonly="readonly"></td>

            <td><input name="txtAllocatedAmt1[]" id="txtAllocatedAmt1<?php echo $item->idVal;?>" class="textfield4" type="text" value="<?php echo $item->allocated;?>" readonly="readonly"></td>
        </tr>
    
    <?php }//end foreach ?>
    <?php }//end if ?>
	
    <input name="currentId"  id="currentId" type="hidden"  value="">
    <?php if(empty($list_allc1)){?>
	<p>No Records</p>
	<?php }?>

        <!--
            <tr>
                <td><?php echo TICKETNO;?></td>
                <td><input type="text" name="txtRefTkt" id="txtRefTkt" class="txtRefTkt"></td>
            </tr>-->
       </tbody> 
