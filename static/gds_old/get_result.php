<?php 
	//data as array form Galileo
	$All_tickets_division = $_REQUEST['All_tickets'];
	$All_tickets = explode("division",$All_tickets_division);

	//Retriving Valid or Generated Tickets
	for($i=0,$j=0;$i<sizeof($All_tickets);$i++):
		$check_valid = explode(" ",$All_tickets[$i]);
		if($check_valid[0]!="UNABLE"):
			$Valid_Tkt_no[$j] = $All_tickets[$i];
			$j++;
		endif;
	endfor;

	//Removing empty space from the result 
	$Valid_Tkt_no = array_filter($Valid_Tkt_no);

	$f_data='<?php
	function getGDSTickets()
	{
		 $Valid_Tkt_no   = array();
		 ';
		 for($i=0;$i<sizeof($Valid_Tkt_no);$i++){
		 $f_data.='$Valid_Tkt_no[] ="'.$Valid_Tkt_no[$i].'";';
		 }
	$f_data.= 'return ($Valid_Tkt_no);
	}
	?>';

	// Please change the file name as based on your requirement
	$file='ticket_result.php';
	file_put_contents($file, $f_data);
?>