<?php 


$list_value =  $this->year_closing_model->getAllDetails($search_accGrp,$search_Acc,$list_value);

$chkGroupId = $chkHeadId = $chkAccountId = array();
foreach($list_value as $key => $item):



 ?>
 
 <?php
            $getValues      = $this->year_closing_model->getAccValuesSubGroup($item->HeadId);
			
        $getOpenValues  = $this->year_closing_model->getOpenAccValuesSubGroup($item->HeadId);

        //$getOpenValues =  $this->year_closing_model->getOpeningBalForAccHead($search_location,$search_date,$item->HeadId);
        $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);

            if($getBalance > 0):
                $balanceDebit = $getBalance;
                $balanceCredit = '0.000';
				$mode='Dr';
            else:
                $balanceDebit = '0.000';
				$mode='Cr';
                $balanceCredit = substr($getBalance,1,strlen($getBalance));
            endif;
			
            ?>
			<tr id="result"> <?php if($item->HeadId!=''){?>
			<td><?php echo Location;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtAcccode[]" id="txtAcccode" class="chkSuggestion" type="text" value="<?php echo $item->location;?>">
                            </label></td>
                            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtAcccode[]" id="txtAcccode" class="chkSuggestion" type="text" value="<?php echo $item->HeadId?>">
                            </label></td>

                            <td><?php echo DESCRIPTION;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtDes[]" id="txtDes" class="textfield" type="text" value="<?php echo $item->HeadName;?>">
                            </label></td>

                            <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
                            <td> <input name="txtDes[]" id="txtDes" class="textfield" type="text" value="<?php echo $mode?>"></td>

                            <td><?php echo AMOUNTFC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmountFC[]" id="fcAmt" class="textfield4 fcRate" type="text" value="<?php echo $balanceDebit?>"></td>

                            <td><?php echo AMOUNTDC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmount[]" id="dcAmt" class="textfield6" type="text" value="<?php echo $balanceDebit?>"></td>

                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
			<?php } ?> </tr>
						
<?php
         
		  $chkSubAccountId[]       = $item->subacc . "-" . $item->vTable;

            $chkAccountId[] = $item->AccountId;
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 

        
    endforeach;
	
	
	
	 $list_value = $this->year_closing_model->subAccountList($search_acc,$search_subAcc,$chkAccountId,$chkSubAccountId);

	 $chkHeadId = array();

    $chkAccountId = array();
	
	foreach($list_value as $key => $item):



 ?>
 
 <?php
            $getValues      = $this->year_closing_model->getAccValuesSubGroup($item->HeadId);
			
        $getOpenValues  = $this->year_closing_model->getOpenAccValuesSubGroup($item->HeadId);

        //$getOpenValues =  $this->year_closing_model->getOpeningBalForAccHead($search_location,$search_date,$item->HeadId);
        $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);

            if($getBalance > 0):
                $balanceDebit = $getBalance;
                $balanceCredit = '0.000';
				$mode='Dr';
            else:
                $balanceDebit = '0.000';
				$mode='Cr';
                $balanceCredit = substr($getBalance,1,strlen($getBalance));
            endif;
			
            ?>
			<tr id="result"> <?php var_dump($list_value);?>
                            <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtAcccode[]" id="txtAcccode" class="chkSuggestion" type="text" value="<?php echo $item->HeadId?>">
                            </label></td>

                            <td><?php echo DESCRIPTION;?> <span class="warning">*</span></td>
                            <td><label>
                                <input name="txtDes[]" id="txtDes" class="textfield" type="text" value="<?php echo $item->HeadName;?>">
                            </label></td>

                            <td><?php echo BUDGETMODE;?> <span class="warning">*</span></td>
                            <td> <input name="txtDes[]" id="txtDes" class="textfield" type="text" value="<?php echo $mode?>"></td>

                            <td><?php echo AMOUNTFC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmountFC[]" id="fcAmt" class="textfield4 fcRate" type="text" value="<?php echo $balanceDebit?>"></td>

                            <td><?php echo AMOUNTDC;?> <span class="warning">*</span></td>
                            <td><input name="txtAmount[]" id="dcAmt" class="textfield6" type="text" value="<?php echo $balanceDebit?>"></td>

                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
                        </tr>
						
<?php
         
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 

        
    endforeach;
	
	?>