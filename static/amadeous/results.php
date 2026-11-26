<?php
$All_Results = $_REQUEST['all_details'];
$All_Results = str_replace("&gt;</","",$All_Results);
$All_Results_copy = $All_Results;
echo $All_Results;

$All_tickets = explode("division",$All_Results);
//$All_tickets = array_unique($All_tickets);
$All_tickets = array_filter($All_tickets);
$All_tickets = array_slice($All_tickets,0);	
$All_tickets_copy = $All_tickets;
echo '<pre>',print_r($All_tickets,1),'</pre>';

$tkt_no_array = $_REQUEST['tkt_no_array'];
$tkt_no_array = explode("_--",$tkt_no_array);
$tkt_no_array_len = sizeof($tkt_no_array);
	if($tkt_no_array_len<=40)
	{ 
	$tkt_no_array;
	}
	else
	{
		$start_value = $tkt_no_array_len - 40;
		for($j=0,$i=$start_value-1;$i<$tkt_no_array_len-1;$i++,$j++)
		{
		$tkt_no_array_soretd[$j] = $tkt_no_array[$i]; 
		}
		$tkt_no_array = $tkt_no_array_soretd;
		/*$tkt_no_array;*/
	}
$tkt_no_array = array_unique($tkt_no_array);
$tkt_no_array = array_filter($tkt_no_array);
$tkt_no_array = array_slice($tkt_no_array,0);	
echo '<pre>',print_r($tkt_no_array,1),'</pre>';

$tkt_Amt_array = $_REQUEST['tkt_no_Amount_array'];
$tkt_Amt_array = explode("__",$tkt_Amt_array);
$tkt_Amt_array_len = sizeof($tkt_Amt_array);
if($tkt_Amt_array_len<=40)
	{ 
	$tkt_Amt_array;
	}
	else
	{
		$start_value = $tkt_Amt_array_len - 40;
		for($j=0,$i=$start_value-1;$i<$tkt_no_array_len-1;$i++,$j++)
		{
		$tkt_Amt_array_soretd[$j] = $tkt_Amt_array[$i]; 
		}
		$tkt_Amt_array = $tkt_Amt_array_soretd;
		/*$tkt_Amt_array;*/
	}
$tkt_Amt_array = array_unique($tkt_Amt_array);	
$tkt_Amt_array = array_filter($tkt_Amt_array);
$tkt_Amt_array = array_slice($tkt_Amt_array,0);	
echo '<pre>',print_r($tkt_Amt_array,1),'</pre>';


// Globel variable
$status_pass_name = 0;
$cnj_status = 0;
// Globel variable 

for($i=0;$i<sizeof($All_tickets_copy)-1;$i++)
{
  $substring_array = explode('Ticket_image',$All_tickets_copy[$i]);
  $single_tickets = $substring_array[1];
   if (strpos($single_tickets, 'AIRLINE PROHIBITS ETKT') !== false)
	{
	 $tkt_no = "AIRLINE PROHIBITS ETKT";
	}
   else
   { 
	$substring_array = explode('TKT-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$tkt_no = $substring_array[0];
	
		if (strpos($tkt_no, '-') !== false)
		{
		 // echo $All_tickets_copy[$i];
		  echo $single_tickets;
		  echo '<br/>';
		  echo '<br/>';
		  //$All_Results =  preg_replace('/'."TKT-".$tkt_no.'/', $single_tickets, $All_Results, 1);
		}
	}
}		





for($i=0;$i<sizeof($All_tickets_copy)-1;$i++)
{
  $substring_array = explode('Ticket_image',$All_tickets_copy[$i]);
  $single_tickets = $substring_array[1];
   if (strpos($single_tickets, 'AIRLINE PROHIBITS ETKT') !== false)
	{
	 $tkt_no = "AIRLINE PROHIBITS ETKT";
	}
   else
   { 
	$substring_array = explode('TKT-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$tkt_no = $substring_array[0];

	if (strpos($tkt_no, '-') !== false)
	{
     
	 if($cnj_status == 0) 
	 {	 
		 if (strpos($All_Results, "TKT-".$tkt_no) !== false)
		 {
		 $All_Results =  preg_replace('/'."TKT-".$tkt_no.'/', "TKT-".$tkt_no." CONJ_1", $All_Results, 1);
		 $cnj_status = 1;
		 }
	 }
	 else
	 {	 
		 if (strpos($All_Results, "TKT-".$tkt_no) !== false)
		 {
			$pos = strrpos($All_Results, "TKT-".$tkt_no);
			if($pos !== false)
			{
			$All_Results = substr_replace($All_Results, "TKT-".$tkt_no." CONJ_2", $pos, strlen("TKT-".$tkt_no));
			}
			$cnj_status = 0;
		  } 
	 }
	
   }
   

 }
 
} 


 // Finale Result String  With Conjetion Ticket


$All_tickets = explode("division",$All_Results);

//Find find_tkt_no
function find_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i)
{
	  $tkt_substring_array = explode('_',$tkt_no_array[$i]);
	  $tkt_no = $tkt_substring_array = str_replace('-','',$tkt_substring_array[0]);
	  $old_tkt_no = $tkt_no;	  
	  $tkt_no_air_code  = substr($tkt_no, 0, 3); 
	  if(strpos($single_tickets_tkt_image,'FO '.$tkt_no_air_code.'-') !== false)
	  {
	    $tkt_substring_array = explode('FO '.$tkt_no_air_code.'-',$single_tickets_tkt_image);
		$orginal_tkt_no  = substr($tkt_substring_array[1], 0, 10); 
		$tkt_no = $old_tkt_no;
	  }
	  
	  return $tkt_no;
}
//Find find_tkt_no


//Find find_org_tkt_no
function find_org_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i)
{
	  $tkt_substring_array = explode('_',$tkt_no_array[$i]);
	  $tkt_no = $tkt_substring_array = str_replace('-','',$tkt_substring_array[0]);
	  $old_tkt_no = $tkt_no;	  
	  $tkt_no_air_code  = substr($tkt_no, 0, 3); 
	  if(strpos($single_tickets_tkt_image,'FO '.$tkt_no_air_code.'-') !== false)
	  {
	    $tkt_substring_array = explode('FO '.$tkt_no_air_code.'-',$single_tickets_tkt_image);
		$orginal_tkt_no  = substr($tkt_substring_array[1], 0, 10); 
		$org_tkt_no = $tkt_no_air_code.$orginal_tkt_no;
	  }
	  else
	  {
	    $org_tkt_no = '-';
	  }
	  
	  return $org_tkt_no;
}
//Find find_org_tkt_no


//Find Gds Pnr  
function find_gdspnr($tkt_no_array,$i)
{
  echo '<pre>',print_r($tkt_no_array,1),'</pre>';
 // echo '<br/>';
echo $i;
  $tkt_substring_array = explode('_',$tkt_no_array[$i]);
  $gds_pnr = $tkt_substring_array = $tkt_substring_array[1];
	
	return $gds_pnr;
}
//Find Gds Pnr 

//Find Air Pnr  
function find_airpnr($single_tickets_rt_pnr,$single_tickets_rt_pnr)
{
    if(strpos($single_tickets_rt_pnr,'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
	{
	$air_pnr = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
	{
	 $air_pnr = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
	{
	$air_pnr = "-";
	}
	else
	{
	echo $single_tickets_rt_pnr;
	$substring_array = explode('/',$single_tickets_rt_pnr);	
	$substring_array = explode(' ',$substring_array[4]);
	$substring_array = array_filter($substring_array, 'strlen');
	$substring_array = array_slice($substring_array, 0);
	echo '<pre>',print_r($substring_array,1),'</pre>';	
	$air_pnr = substr($substring_array[1], 0, 6);
	}
	$gdspnr = find_gdspnr($single_tickets,$single_tickets_rt_pnr);
	
	if($gdspnr == $air_pnr)
	{
	$air_pnr = "-";
	}
	
	return $air_pnr;
}
//Find Air Pnr 

/*
//Find Sign On Id  
function find_signon_id($single_tickets_rt_pnr)
{
echo $single_tickets_rt_pnr;
	if(strpos($single_tickets_rt_pnr,'NO MATCH FOR RECORD LOCATOR') !== false)
	{
	$signon_id = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
	{
	$signon_id = "-";
	}
	else
	{

$substring_array = explode('---',$single_tickets_rt_pnr);
$substring_array = array_filter($substring_array, 'strlen');
$substring_array = array_slice($substring_array, 0);
	
	if($substring_array[0]==' ')
	{
	unset($substring_array[0]);
	$substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
	echo '<pre>',print_r($substring_array,1),'</pre>';
	}
	
	
	$substring_array = explode('1.',$substring_array[1]);
	$substring_array = explode(' ',$substring_array[0]);
	$substring_array = array_filter($substring_array, 'strlen');
	$substring_array = array_slice($substring_array, 0);
	$substring_array = explode('/',$substring_array[3]);
    echo '<pre>',print_r($substring_array,1),'</pre>';
	$booked_id = $substring_array[1];
	$signon_id = substr($booked_id, -2);

	}
	

	
	
	return $signon_id;
}
//Find Sign On Id

//Find Booked Id  
function find_booked_id($single_tickets_rt_pnr)
{
    
	if(strpos($single_tickets_rt_pnr,'NO MATCH FOR RECORD LOCATOR') !== false)
	{
	$booked_id = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
	{
	$booked_id = "-";
	}
	else
	{ //AXR RLR ---
	//$substring_array = explode(' ---',$single_tickets_rt_pnr);
     
	$substring_array = explode('---',$single_tickets_rt_pnr);
	$substring_array = array_filter($substring_array, 'strlen');
	$substring_array = array_slice($substring_array, 0);
	echo '<pre>',print_r($substring_array,1),'</pre>';
	
	if($substring_array[0]==' ')
	{
	unset($substring_array[0]);
	$substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
	echo '<pre>',print_r($substring_array,1),'</pre>';
	}
	 
	$substring_array = explode('1.',$substring_array[1]);
	$substring_array = explode(' ',$substring_array[0]);
	$substring_array = array_filter($substring_array, 'strlen');
	$substring_array = array_slice($substring_array, 0);
	$substring_array = explode('/',$substring_array[3]);
    echo '<pre>',print_r($substring_array,1),'</pre>';
	$booked_id = $substring_array[1];
	$booked_id = substr($booked_id, -2);
	}
	
	
	
	return $booked_id;
}
//Find Booked Id
*/



//Find Sign On Id
function find_signon_id($single_tickets_rt_pnr)
{
//echo $single_tickets_rt_pnr;
    if(strpos($single_tickets_rt_pnr,'NO MATCH FOR RECORD LOCATOR') !== false)
    {
    $signon_id = "-";
    }
    else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
    {
    $signon_id = "-";
    }
    else
    {

$substring_array = explode('---',$single_tickets_rt_pnr);
$substring_array = array_filter($substring_array, 'strlen');
$substring_array = array_slice($substring_array, 0);
    //print_r($substring_array);
    if($substring_array[0]==' ')
    {
    unset($substring_array[0]);
    $substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
    }


    $substring_array = explode('1.',$substring_array[1]);
    $substring_array[0] = preg_replace('!\s+!', ' ', $substring_array[0]);
    $substring_array = explode(' ',trim($substring_array[0]));
    $substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
    $substring_array = explode('/',$substring_array[1]);
    $booked_id = $substring_array[0];
    $signon_id = substr($booked_id, -2);
    }




    return $signon_id;
}
//Find Sign On Id

//Find Booked Id
function find_booked_id($single_tickets_rt_pnr)
{

    if(strpos($single_tickets_rt_pnr,'NO MATCH FOR RECORD LOCATOR') !== false)
    {
    $booked_id = "-";
    }
    else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
    {
    $booked_id = "-";
    }
    else
    {
    $substring_array = explode('---',$single_tickets_rt_pnr);
    $substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);

    if($substring_array[0]==' ')
    {
    unset($substring_array[0]);
    $substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
    }

    $substring_array = explode('1.',$substring_array[1]);
    $substring_array[0] = preg_replace('!\s+!', ' ', $substring_array[0]);
    $substring_array = explode(' ',$substring_array[0]);
    $substring_array = array_filter($substring_array, 'strlen');
    $substring_array = array_slice($substring_array, 0);
    $substring_array = explode('/',$substring_array[1]);
    $booked_id = $substring_array[0];
    $booked_id = substr($booked_id, -2);
    }



    return $booked_id;
}



function find_pass_name($single_tickets,$single_tickets_rt_pnr,$status_pass_name)
{

echo $single_tickets;
if((strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false) and (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false))
{
	if (strpos($single_tickets_rt_pnr, $status_pass_name.'.') !== false)
	{
	$substring_array = explode($status_pass_name.'.',$single_tickets_rt_pnr);
	$substring_array = explode(' ',$substring_array[1]);
	$pass_name = $substring_array[0];
	$status_pass_name = $status_pass_name+1;
	}

}
else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
{
$pass_name = "-";
}
else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
{
$substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$pass_name = $substring_array[0];
}
else if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
{ 
 $substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$pass_name = $substring_array[0];
}
else
{

echo '<pre>',print_r($single_tickets,1),'</pre>';
    $substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$pass_name = $substring_array[0];
}	
	return $pass_name;
}




function find_pass_name_display($single_tickets,$single_tickets_rt_pnr,$status_pass_name)
{
echo '<pre>',print_r($single_tickets,1),'</pre>';

if((strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false) and (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false))
{
	if (strpos($single_tickets_rt_pnr, $status_pass_name.'.') !== false)
	{
	echo $single_tickets_rt_pnr;
	$substring_array = explode($status_pass_name.'.',$single_tickets_rt_pnr);
	$substring_array = explode(' ',$substring_array[1]);
	$pass_name = $substring_array[0];
	$status_pass_name = $status_pass_name+1;
	}

}
else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
{
$pass_name = "-";
}
else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
{
    $substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ST',$substring_array[1]);
	$pass_name = $substring_array[0];
}
else if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
{ 
	$substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ST',$substring_array[1]);
	$pass_name = $substring_array[0];
}
else
{
    $substring_array = explode('1.',$single_tickets);
	$substring_array = explode(' ST',$substring_array[1]);
	$pass_name = $substring_array[0];
}	
	
	if(($pass_name!='-')or($pass_name!=''))
	{

	 if (strpos($pass_name, 'CHD') !== false)
	 {
	 $pass_name = str_replace("CHD"," ",$pass_name);
	 }
	 else if (strpos($pass_name, 'ADT') !== false)
	 {
	 $pass_name = str_replace("ADT"," ",$pass_name);
	 }
	 else if (strpos($pass_name, 'INF') !== false)
	 {
	 $pass_name = str_replace("INF"," ",$pass_name);
	 }
	 else
	 {
	   $pass_name;
	 } 

	
	 $pass_name = $pass_name."_VALUE";
     $pass_name= preg_replace('/( *)/', '', $pass_name);	 
	 
	 if (strpos($pass_name, 'MR_VALUE') !== false)
	 {
	 $pass_name_string = explode('MR_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MR '.$pass_name_string[1].' '.$pass_name_string[0];
	 } 
	 else if (strpos($pass_name, 'HD_VALUE') !== false)
	 {
	 $pass_name_string = explode('HD_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MR '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else if (strpos($pass_name, 'RS_VALUE') !== false)
	 {
	 $pass_name_string = explode('RS_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MRS '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else if (strpos($pass_name, 'TR_VALUE') !== false)
	 {
	 $pass_name_string = explode('TR_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MSTR '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else if (strpos($pass_name, 'MISS_VALUE') !== false)
	 {
	 $pass_name_string = explode('MISS_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MISS '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else if (strpos($pass_name, 'MS_VALUE') !== false)
	 {
	 $pass_name_string = explode('MS_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MS '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else if (strpos($pass_name, 'NF_VALUE') !== false)
	 {
	 $pass_name_string = explode('NF_VALUE',$pass_name);
	 $pass_name_string = explode('/',$pass_name_string[0]);
	 $pass_name = 'MR '.$pass_name_string[1].' '.$pass_name_string[0];
	 }
	 else
	 {
     $pass_name_string = explode('NF_VALUE',$pass_name);
	 $pass_name = str_replace("_VALUE"," ",$pass_name);
	 $pass_name_string = explode('/',$pass_name);
	 $pass_name = $pass_name_string[1].' '.$pass_name_string[0];
	 }
	 
	}
	
	return $pass_name;
}

function find_dateofissue($single_tickets,$single_tickets_rt_pnr)
{
    
	echo $single_tickets_rt_pnr;
	echo $single_tickets_rt_pnr;
	
	if(strpos($single_tickets_rt_pnr,'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
	{
	//$single_tickets_rt_pnr = explode('&#x000A;',$single_tickets_rt_pnr);
	//$single_tickets_rt_pnr = explode('/',$single_tickets_rt_pnr[3]);
	//$date_issue = $single_tickets_rt_pnr[2];
	
	if(strpos($single_tickets_rt_pnr,'--- TST RLR ---') !== false)
	{
	$substring_array = explode('RLR ---',$single_tickets_rt_pnr);
	}
	else if(strpos($single_tickets_rt_pnr,'--- TST ---') !== false)
	{
	$substring_array = explode('TST ---',$single_tickets_rt_pnr);
	}
	else
	{
	$substring_array = explode('MSC ---',$single_tickets_rt_pnr);
	}
	
	echo '<pre>',print_r($substring_array,1),'</pre>';
	
	$substring_array = explode('1.',$substring_array[1]);
	$substring_array = explode(' ',$substring_array[0]);
	$substring_array = array_filter($substring_array, 'strlen');
	$substring_array = array_slice($substring_array, 0);
	
	$substring_array = explode('/',$substring_array[2]);
	//$len_substring = sizeof($substring_array);
	//$substring_array = explode('/',$substring_array[$len_substring-1]);
	$date_issue = $substring_array[0];
	}
	else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$date_issue = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
	{ 
	$substring_array = explode('DOI-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$date_issue = $substring_array[0];
	}
	else
	{
	
	$substring_array = explode('DOI-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$date_issue = $substring_array[0];
	}
	return $date_issue;
}

function find_ticket_type($single_tickets,$single_tickets_rt_pnr,$tkt_no_array,$i)
{   
    echo $single_tickets;
	
	//$ticket_number = find_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i);
	if(strpos($single_tickets,'CONJ_2') !== false)
	{
	 $ticket_type = "Conjunction";
	}
//	else if(strpos($single_tickets_rt_pnr,'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
//	{
//	  $ticket_type = "Void";
//	}
	else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$ticket_type = "Canceld";
	}
	//else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
//	{
//	$ticket_type = "Void";
//	}
	else
	{
	$segments_all = find_SegmentDetail($single_tickets,$single_tickets_rt_pnr,$tkt_no_array,$i);
	$type_code = $segments_all[1][8];	

$tkt_substring_array = explode('_',$tkt_no_array[$i]);
$tkt_no = $tkt_substring_array = str_replace('-','',$tkt_substring_array[0]);
$old_tkt_no = $tkt_no;	  
$tkt_no_air_code  = substr($tkt_no, 0, 3); 


if(strpos($single_tickets,'FO '.$tkt_no_air_code.'-') !== false)
	  {
		$tkt_substring_array = explode('FO '.$tkt_no_air_code.'-',$single_tickets);
echo '<pre>',print_r($tkt_substring_array,1),'</pre>';
		$orginal_tkt_no  = substr($tkt_substring_array[1], 0, 10); 
		$org_tkt_no = $tkt_no_air_code.$orginal_tkt_no;
	  }
	  else
	  {
	    $org_tkt_no = '-';
	  }

   
     $tkt_number_len = strlen($org_tkt_no);
	
	if($tkt_number_len>2)
	{
	  $ticket_type = "Reissued";
	}
	else if($type_code=="O")
	{
	 $ticket_type = "Normal";
	}
	elseif($type_code=="V")
	{
	 $ticket_type = "Void";
	}
	elseif($type_code=="F")
	{
	 $ticket_type = "Refund";
	}
	elseif($type_code=="E")
	{
	 $ticket_type = "Exchange";
	}
	elseif($type_code=="R")
	{
	 $ticket_type = "Refund";
	}
	elseif($type_code=="S")
	{
	 $ticket_type = "Suspended";
	}
	elseif($type_code=="A")
	{
	 $ticket_type = "Airport Controls";
	}
	else
	{
	 $ticket_type = "Normal";
	}
  }	
	return $ticket_type;
	
}

function find_air_code($ticket_no,$single_tickets_rt_pnr)
{
if(strpos($single_tickets_rt_pnr,'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
{
$air_code = substr($ticket_no, 0, 3);
}
else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
{
$air_code = substr($ticket_no, 0, 3);
}
else if (strpos($single_tickets_rt_pnr, 'INVALID FORMAT') !== false)
	{
	$air_code = "-";
	}
else
{
$air_code = substr($ticket_no, 0, 3);
}

return $air_code;
}


function find_iatacode($single_tickets,$single_tickets_rt_pnr)
{


if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
{
  $substring_array = explode('IOI-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$iata_code = $substring_array[0]; 
	if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$iata_code = '-';
	}
} 
else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$iata_code = "-";
	}
else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
	{
	$substring_array = explode('IOI-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$iata_code = $substring_array[0]; 
	}	
else
{
echo '<pre>',print_r($single_tickets,1),'</pre>';
    $substring_array = explode('IOI-',$single_tickets);
	$substring_array = explode(' ',$substring_array[1]);
	$iata_code = $substring_array[0]; 
}
return $iata_code;	
}

function find_totalfare($single_tickets,$single_tickets_rt_pnr,$tkt_Amt_array,$i)
{ 
    if(strpos($single_tickets,'CONJ_2') !== false)
	{
	  $fare = "-";
	}
	else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$fare = "-";
	}
	else if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
	{ 
	
	echo $single_tickets;
    echo '<br/>';
	 
	  
	 $substring_array = explode('FARE  ',$single_tickets);
	if (strpos($substring_array[1],'TOTALTAX ') !== false) {
    $fare_amount_array = explode('TOTALTAX',$substring_array[1]);
    } else if(strpos($substring_array[1],'TAX      ') !== false){
	$fare_amount_array = explode('TAX      ',$substring_array[1]);
	}
	echo '<pre>',print_r($fare_amount_array,1),'</pre>';
	
	$fare = $fare_amount_array[0];
	}
	else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
	{
	$substring_array = explode('FARE  ',$single_tickets);
	if (strpos($substring_array[1],'TOTALTAX ') !== false) {
    $fare_amount_array = explode('TOTALTAX',$substring_array[1]);
    } else if(strpos($substring_array[1],'TAX      ') !== false){
	$fare_amount_array = explode('TAX      ',$substring_array[1]);
	}
	$fare = $fare_amount_array[0];
	}
	else
	{
	$substring_array = explode('FARE  ',$single_tickets);
	if (strpos($substring_array[1],'TOTALTAX ') !== false) {
    $fare_amount_array = explode('TOTALTAX',$substring_array[1]);
    } else if(strpos($substring_array[1],'TAX      ') !== false){
	$fare_amount_array = explode('TAX      ',$substring_array[1]);
	}
	$fare = $fare_amount_array[0];
    }
	
	if(strpos($fare,'IT') !== false)
	{
	 $base_amount = explode('/',$tkt_Amt_array[$i]);
	 $fare = $base_amount[0]-$base_amount[1];
	}
	
	return $fare;
}

function find_NetAmount($single_tickets,$single_tickets_rt_pnr,$tkt_Amt_array,$i)
{        
    if(strpos($single_tickets,'CONJ_2') !== false)
	{
	  $net_amount = "-";
	}
	else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$net_amount = "-";
	}
    else
	{
	$base_amount = explode('/',$tkt_Amt_array[$i]);	
	$net_amount = $base_amount[0];
	}
	
	
	
// For Checking IT in base fare
			if(strpos($single_tickets,'CONJ_2') !== false)
				{
				  $fare = "-";
				}
				else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
				{
				$fare = "-";
				}
				else if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
				{
				 $substring_array = explode('FARE  ',$single_tickets);
				if (strpos($substring_array[1],'TOTALTAX ') !== false) {
				$fare_amount_array = explode('TOTALTAX',$substring_array[1]);
				} else if(strpos($substring_array[1],'TAX      ') !== false){
				$fare_amount_array = explode('TAX      ',$substring_array[1]);
				}
				$fare = $fare_amount_array[0];
				}
				else if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
				{
				$substring_array = explode('FARE  ',$single_tickets);
				if (strpos($substring_array[1],'TOTALTAX ') !== false) {
				$fare_amount_array = explode('TOTALTAX',$substring_array[1]);
				} else if(strpos($substring_array[1],'TAX      ') !== false){
				$fare_amount_array = explode('TAX      ',$substring_array[1]);
				}
				$fare = $fare_amount_array[0];
				}
				else
				{
				$substring_array = explode('FARE  ',$single_tickets);
				if (strpos($substring_array[1],'TOTALTAX ') !== false) {
				$fare_amount_array = explode('TOTALTAX',$substring_array[1]);
				} else if(strpos($substring_array[1],'TAX      ') !== false){
				$fare_amount_array = explode('TAX      ',$substring_array[1]);
				}
				$fare = $fare_amount_array[0];
				}		
// For Checking IT in base fare		

	
	if(strpos($fare,'IT') !== false)
	{
	 $base_amount = explode('/',$tkt_Amt_array[$i]);
	 $net_amount = $base_amount[0];
	}
	
	$amount_split = explode('/',$tkt_Amt_array[$i]);
	if (strpos($amount_split[0], '-') !== false)
	{
	$base_fare = find_totalfare($single_tickets,$single_tickets_rt_pnr,$tkt_Amt_array,$i);
	$base_fare = preg_replace('/[^0-9^.]/','',$base_fare); 
      
	$deduct_amt = explode('-',$tkt_Amt_array[$i]);
	$deduct_amt = array_filter($deduct_amt);
	$deduct_amt = array_slice($deduct_amt,0);
	$cp_charge = $total_fare-$deduct_amt[0]; 

	 echo '<pre>',print_r($tkt_Amt_array,1),'</pre>'; 
	$deduct_amt = explode('-',$tkt_Amt_array[$i]);
	$deduct_amt = array_filter($deduct_amt);
	$deduct_amt = array_slice($deduct_amt,0);
	$net_amount = $base_fare+$deduct_amt[1]-$cp_charges;
	
	
	}
	
	return $net_amount;
}

function find_cp_charges($single_tickets,$single_tickets_rt_pnr,$tkt_Amt_array,$i)
{
	
	$total_fare = find_NetAmount($single_tickets,$single_tickets_rt_pnr,$tkt_Amt_array,$i);	
	$amount_split = explode('/',$tkt_Amt_array[$i]);

	if(strpos($amount_split[0], '-') !== false)
	{
	$deduct_amt = explode('-',$tkt_Amt_array[$i]);
	$deduct_amt = array_filter($deduct_amt);
	$deduct_amt = array_slice($deduct_amt,0);
	$cp_charge = $total_fare-$deduct_amt[0];
	}
	else
	{
	$cp_charge = "-";
	}
	return $cp_charge;
	
}


function find_total_taxes($single_tickets)
{
	
	
	// Total taxes;
	if(strpos($single_tickets, 'TOTALTAX ') !== false)
    {
		$substring_array = explode('TOTALTAX ',$single_tickets);
		$substring_array = explode(' ',$substring_array[1]); 
		$substring_array = array_unique($substring_array);
		$substring_array = array_slice($substring_array,0);
		
        echo '<pre>',print_r($substring_array,1),'</pre>'; 
		
		if (strpos($substring_array[2], 'TOTAL') !== false)
		{
		$total_tax = explode('TOTAL',$substring_array[2]);
		$total_tax = $total_tax[0];
		}
		else if (strpos($substring_array[2], 'TAXES') !== false)
		{	
		$total_tax = explode('TAXES',$substring_array[2]);
		$total_tax = $total_tax[0];
		}
		else
		{
		 $total_tax = $substring_array[1];
		}
		
	} 
	
	else 
	{ 
	   // multple taxes
		$substring_array = explode('TOTAL    ',$single_tickets);
		$substring_array = explode('TAX      ',$substring_array[0]);
	    echo '<pre>',print_r($substring_array,1),'</pre>';
		$tax1 = preg_replace('/[^0-9^.]/','',$substring_array[1]);
		$tax2 = preg_replace('/[^0-9^.]/','',$substring_array[2]);
		$tax3 = preg_replace('/[^0-9^.]/','',$substring_array[3]);
	    $total_tax = $tax1+$tax2+$tax3;	
		
	}
	
	if(strpos($single_tickets,'CONJ_2') !== false)
	{
	 $total_tax = "-";
	}
	
	if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$total_tax = "-";
	}
	
	return $total_tax;
}


function find_All_taxes($single_tickets_tax,$single_tickets)
{
$j=0;
$single_tickets_tax = str_replace("&#x000A;"," ",$single_tickets_tax);
for($i=1;$i<=12;$i++)
{

if($i>=10)
{
$single_tax = explode("TX1".$j,$single_tickets_tax);
$j++;
}
else
{
$single_tax = explode("TX0".$i,$single_tickets_tax);
}

$single_tax = explode(" ",$single_tax[1]);
$single_tax = array_filter($single_tax, 'strlen');
$single_tax = array_slice($single_tax, 0);
$all_taxes[] = $single_tax[1];
}

echo '<pre>',print_r($all_taxes,1),'</pre>';
//$all_tax_array = array_slice($all_tax_array,0);

echo $single_tickets;
echo '<br/>';
echo '<br/>';


if(strpos($single_tickets,'CONJ_2') !== false)
{ 
 $all_taxes = "1"; 
}

if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
	{
	$all_taxes = "1"; 
	}


return $all_taxes;
}


function find_SegmentDetail($single_tickets,$single_tickets_rt_pnr,$tkt_no_array,$arr_cnt)
{
 
   $Conj_status = 0;
   if(strpos($single_tickets, 'AIRLINE PROHIBITS ETKT') !== false)
	{
	$segment_array[0] = "-";
	}
	else if (strpos($single_tickets, 'TICKET NUMBER NOT FOUND') !== false)
		{
		$segment_array[0] = "-";
		}
	else
		{	
		$substring_array = explode('FARE   ',$single_tickets);
		$startIndex=strpos($substring_array[0],' 1 ');
		$endIndex = strlen($substring_array[0]);
		$finalString=substr($substring_array[0],$startIndex,$endIndex);
		
		
		
	if(strpos($single_tickets, '3  LIN ') !== false)
	{
	$finalString = str_replace('3  LIN ','NULL_VAL',$finalString);
	$finalString = str_replace(' NULL_VAL','',$finalString);
	$Conj_status = 1;
	}
	
	if(strpos($single_tickets, '3  LYS ') !== false)
	{
	$finalString = str_replace('3  LYS ','NULL_VAL',$finalString);
	$finalString = str_replace(' NULL_VAL','',$finalString);
	$Conj_status = 1;
	}
	
 

	if(strpos($finalString, '3  VGA ') !== false)
	{
	$finalString = str_replace('3  VGA ','',$finalString);
	$Conj_status = 1;
	}

	if(strpos($finalString, '3  FRA ') !== false)
	{
	$finalString = str_replace('3  FRA ','',$finalString);
	$Conj_status = 1;
	}
	
	if(strpos($finalString, '3  CDG ') !== false)
	{
	$finalString = str_replace('3  CDG ','',$finalString);
	$Conj_status = 1;
	}
	
	
	if(strpos($finalString, 'ARNK') !== false)
	{
	$finalString = str_replace('ARNK','ConjString',$finalString);
	$Conj_status = 1;
	}
		
		$finalString = str_replace("  "," ",$finalString);	
		$substring_array = explode(" ",$finalString);
		
			
		$j=0;
		$d=0;
        $q=1; 
		
		for($i=0;$i<sizeof($substring_array)-2;$i++)
		{
         $first_str_len = strlen($substring_array[$i]);		 
		 if(strpos($substring_array[$i], 'ConjString') !== false)
		 {
		    $j++;
			$q++;
			$d=0;
		 }
		 
		 if(($substring_array[$i]==$q)and($first_str_len==1))
		  {
			$j++;
			$q++;
			$d=0;
		  } 
		   $single_Segment[$j][$d] = $substring_array[$i]; 
		   $d++;
		}
   
	
	if(strpos($single_Segment[3][0], 'ConjString') !== false)
	{
	  unset($single_Segment[3]);
	  $single_Segment = array_filter($single_Segment);
      $single_Segment = array_slice($single_Segment,0);	
	}

       $All_single_Segment = $single_Segment;
		
        foreach ($All_single_Segment as $a) 
        $Total_len += sizeof($a); 					

                    $v = 0;
					$m=1;
					for($i=0;$i<$Total_len;$i++)
					{
					$size_of_single = sizeof($All_single_Segment[$m]);			
		            
				    if($All_single_Segment[$m][$size_of_single-1])
					{
					$NV_Bagage[$v] = $All_single_Segment[$m][$size_of_single-1]."_".$All_single_Segment[$m][$size_of_single-2];
		            }
					
				    $v++;
					$m++;
					}    
		
        
	
		
		for($i=1;$i<sizeof($single_Segment);$i++)
		{
				 
		  $single_Segment[$i] = array_filter($single_Segment[$i]);
          $single_Segment[$i] = array_slice($single_Segment[$i],0);	
		
		}
	

	
		
//===========================Only For Taking Dep , Dest and Arr Time From PNR Entry  ==============================		
		
		//Find all passenger names For Dep Arr Time
			$n = 2;
			for($i=1,$j=0;$i<10;$i++)
			{
				if(strpos($single_tickets_rt_pnr,$i.".") != -1) 
				{
				$explode_str = $i.".";
				$name = explode($explode_str,$single_tickets_rt_pnr);
			
				if(strpos($single_tickets_rt_pnr,$i.".") != -1)
				{
				 if(strpos($single_tickets_rt_pnr,$n.".") != -1)
				   {
					$name = explode($i+1,$name[1]);
					
					$AllpassengerName[$j] = $name[0]; 
				   }	
				  else
					{ 
					  $AllpassengerName = $name[0];
					}
				  $j++;
				}
			  }
			}

			$AllpassengerName = array_filter($AllpassengerName);
			$AllpassengerName = array_slice($AllpassengerName,0);
			
			$AllpassengerName_len1= sizeof($AllpassengerName);
			echo '<pre>',print_r($AllpassengerName,1),'</pre>';
			$first_checking = explode("/",$AllpassengerName[$AllpassengerName_len1-1]);
			
			if(is_numeric($first_checking[0]))
			{	
			array_pop($AllpassengerName);
			}
			
			echo '<pre>',print_r($AllpassengerName,1),'</pre>';
			
			//$AllpassengerName_len1= sizeof($AllpassengerName);
			//$check_last_name = explode(" ",$AllpassengerName[$AllpassengerName_len1-1]);
			//$check_last_name = array_filter($check_last_name);
			//$check_last_name = array_slice($check_last_name,0);
			
			
			
			echo '<pre>',print_r($AllpassengerName,1),'</pre>';

			$AllpassengerName_lenNew= sizeof($AllpassengerName);
				echo '<pre>',print_r($AllpassengerName,1),'</pre>';
			if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
			{
			$substring_dep_arr = substr($substring_array[1],1,3); 
			$pnr_segment[0] = $substring_dep_arr."---";
			$pnr_segment[1]="---"; 
			$pnr_segment[2]="---"; 
			$pnr_segment[3]="---"; 
			$pnr_segment[4]="---"; 
			$pnr_segment[5]="---"; 
			$pnr_segment[6]="---";  
			}
			else
			{
 		
            if($AllpassengerName)
			{ 
		
		   $Last_passengerName_len= strlen($AllpassengerName[$AllpassengerName_lenNew-1]);
		 // This is for Last item is checking last name or not
echo $Last_passengerName_len;
			  if($Last_passengerName_len>40)
			  {
				$pnr_segment = explode($AllpassengerName[$AllpassengerName_lenNew-2],$single_tickets_rt_pnr);
			  }
			  else
			  {
				$pnr_segment = explode($AllpassengerName[$AllpassengerName_lenNew-1],$single_tickets_rt_pnr);
			  }	
		}
						
echo '<pre>',print_r($pnr_segment,1),'</pre>';

			$pnr_segment = explode(" AP ",$pnr_segment[1]);
			$pnr_segment = str_replace("*"," ",$pnr_segment[0]);
			$sement_array_pnr = $pnr_segment;
			$pnr_segment = explode(" ",$pnr_segment);
			$pnr_segment = array_filter($pnr_segment);
			$pnr_segment = array_slice($pnr_segment,0);
			
			}
			


			for($i=0;$i<sizeof($pnr_segment)-1;$i++)
			{
			
				if (strpos($pnr_segment[$i], 'FLWN') !== false)
				{ 
				  array_splice($pnr_segment,$i+1, 0, 'F');
				  array_splice($pnr_segment,$i+2, 0, 'F');
				  array_splice($pnr_segment,$i+3, 0, 'F');
				  array_splice($pnr_segment,$i+4, 0, 'F');
				  array_splice($pnr_segment,$i+5, 0, 'F');
				}	
			
			}
	
	
	$inc = 0;
	$l=1;
  for($i=0;$i<sizeof($pnr_segment);$i++)	
	  {

		$first = strlen($pnr_segment[$l]);
	    if(($first==6)and( preg_match('([0-9]|[0-9])', $pnr_segment[$l]) ) )
		{

		   $first_code = substr($pnr_segment[$l],0,2);
		   $second_code = substr($pnr_segment[$l],2,4); 
		   unset($pnr_segment[$l]);    
		   if($inc==0)
		   {
		   array_splice($pnr_segment,$l+1, 0, $second_code);
		   array_splice($pnr_segment,$l, 0, $first_code);
		   $inc=1;
		   }
		   else
		   {
		   array_splice($pnr_segment,$l+1, 0, $second_code);
		   array_splice($pnr_segment,$l, 0, $first_code);
		
		$firt_value = $pnr_segment[$l+1];
		$second_value = $pnr_segment[$l+2];
		$pnr_segment[$l+1] = $second_value;	
		$pnr_segment[$l+2] = $firt_value;	

			
			$inc=0;	 
		   }
         $l=$l+13;

		} else {
		
		$l=$l+13;
		
		}
	  
	  }	
echo '<pre>',print_r($pnr_segment,1),'</pre>';		


		if ((strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)or(strpos($single_tickets_rt_pnr, 'SECURED PNR') !== false))
		{
					$l=6;
					$f=0;
					$m=1;
					$Total_len = 0;
					foreach ($single_Segment as $a) 
					$Total_len += sizeof($a);
					
								  
						for($i=1;$i<$Total_len;$i++)
						  {  
							if($single_Segment[$m])
							{					
								if($f==0)
								{
								$first_code = substr($single_Segment[$m+1][1],1,3);
							    $second_code = substr($single_Segment[$m][1],1,3);
								
									if($first_code)
									{
									$pnr_segment[$l] = $second_code.$first_code;
									$f=1;
									$l=$l+13;
									}
									else
									{
									   // echo $single_tickets;
										$arr_dep = explode('OD-',$single_tickets);
										$arr_dep = explode(' ',$arr_dep[1]);
										$pnr_segment[$l] = $arr_dep[0];
									}
								
								}
								else
								{
								$pnr_segment[$l] = $first_code.$second_code;
								$f=0;
								$l=$l+13;
								}
							}
							$m++;
						  }				
		
		}
		
		
		
if (strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false)
		{
					$l=9;
					$f=0;
					$m=1;
					$Total_len = 0;
					foreach ($single_Segment as $a) 
					$Total_len += sizeof($a);
					
								  
						for($i=1;$i<$Total_len;$i++)
						  {  
				
								$pnr_segment[$l] = "-";
								$l=$l+13;

							}
							$m++;			
		
		}

	
			
			$k=6;
			$j=1;
			for($i=0;$i<sizeof($pnr_segment)-1;$i++)
			{
				if($j==11){
				$j=0;
				}
				if($j==1)
				{
				
				
				if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
				{
				$DepArr[$i] = $pnr_segment[0];
				}
				else
				{
					$DepArr[$i] = $pnr_segment[$k];
					$k=$k+13;
				}
				
				}
			}

		
		$k=9;
		$j=1;
		for($i=0;$i<sizeof($pnr_segment)-1;$i++)
		{
			if($j==11){
			$j=0;
			}
			if($j==1)
			{
			if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
			{
			$ArrTime[$i] = $pnr_segment[0];
			}
			else if(strpos($single_tickets_rt_pnr, 'SECURED PNR') !== false)
			{
			 $ArrTime[$i] = '-';
			}
			else
			{
				$ArrTime[$i] = $pnr_segment[$k];
				$k=$k+13;
			}
			
			}
		}
	

	
	$Conj_Segment_Details = $pnr_segment;
	$Conj_Segment_Status = $pnr_segment;	





//===========================Only For Taking Dep , Dest and Arr Time From PNR Entry  ==============================
	
	
if($DepArr)
{
$DepArr = array_filter($DepArr);
$DepArr = array_slice($DepArr,0);
}

if($ArrTime)
{
$ArrTime = array_filter($ArrTime);
$ArrTime = array_slice($ArrTime,0);
}		


if(strpos($single_tickets,'CONJ_1') !== false)
{
$single_Segment[1][9] = $DepArr[0]; 
$single_Segment[2][9] = $DepArr[1]; 
$single_Segment[4][9] = $DepArr[2]; 
$single_Segment[5][9] = $DepArr[3]; 
echo '<pre>',print_r($single_Segment,1),'</pre>';
}		
else if(strpos($single_tickets,'CONJ_2') !== false)
{	
$single_Segment[4][9] = $DepArr[4]; 	
$single_Segment[5][9] = $DepArr[5];
$single_Segment[4][10] = $ArrTime[4]; 	
$single_Segment[5][10] = $ArrTime[5];
//$Conj_Segment_Details_multi[5][6];
}
else
{



	
echo '<pre>',print_r($single_Segment,1),'</pre>';		
			$Total_len = 0;
			foreach ($single_Segment as $a) 
			$Total_len += sizeof($a); 
			$DepArr_size = sizeof(DepArr);
					
					$v = 0;
					$j=0;
					$m=1;
					$w =0;
					for($i=0;$i<$Total_len;$i++)
					{
						if($j==9)
						{
						if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
						{
						 
						 if($w==0)
						 {
						 $dep = substr($single_Segment[$m][1],1,3);
						 $dest = substr($single_Segment[$m+1][1],1,3);
						 $w=1;
						 }
						 else
						 {
						 $dep = substr($single_Segment[$m][1],1,3);
						 $dest = substr($single_Segment[$m-1][1],1,3);
						 $w=0;
						 }
						 
						 $single_Segment[$m][9] = $dep.$dest; 
						 
						}
						else
						{						
						$single_Segment[$m][9] = $DepArr[$v]; 
						
						echo '<pre>',print_r($DepArr,1),'</pre>';			
							
						}
						$v++;
						$j=0;
						$m++;
						}
						$j++;
					}
					
}					
						
					
			$Total_len = 0;
			foreach ($single_Segment as $a) 
			$Total_len += sizeof($a); 
					
					$v = 0;
					$j=0;
					$m=1;
					for($i=0;$i<$Total_len+5;$i++)
					{
						if($j==10)
						{
						if (strpos($single_tickets_rt_pnr, 'NO MATCH FOR RECORD LOCATOR') !== false)
						{
						 
						 if($w==0)
						 {
						 $arr = substr($single_Segment[$m+1][5],5,4);
						 $w=1;
						 }
						 else
						 {
						 $arr = substr($single_Segment[$m-1][5],5,4);
						 $w=0;
						 }
						 
						 $single_Segment[$m][10] = $arr; 
						 
						} 
						else 
						{
						$single_Segment[$m][10] = $ArrTime[$v]; 
						}	
						$v++;
						$j=0;
						$m++;
						}
						$j++;   
					}
					
		
		$Total_len = 0;
			foreach ($single_Segment as $a) 
			$Total_len += sizeof($a); 
					
					$v = 0;
					$j=0;
					$m=1;
					for($i=0;$i<$Total_len;$i++)
					{
						if($j==11)
						{
						$single_Segment[$m][11] = $NV_Bagage[$v]; 	
						$v++;
						$j=0;
						$m++;
						}
						$j++;   
					}			
							
	
			$Total_len = 0;
			foreach ($single_Segment as $a) 
			$Total_len += sizeof($a); 
					

					$j=0;
					$m=1;
					for($i=0;$i<$Total_len;$i++)
					{
						
						if($j==8)
						{
						$first = strlen($single_Segment[$m][8]);
							if($first>2)
							{
							$single_Segment[$m][8];	
							unset($single_Segment[$m][8]);
							}
						$j=0;
						$m++;
						}
						$j++;   
					}
	
	
					



       $m=1;
      for($i=0;$i<$Total_len;$i++)	
	  {
	   
	    $first = strlen($single_Segment[$m][2]);
		
	    if(($first==6)or($first==8)and( preg_match('([0-9]|[0-9])', $single_Segment[$m][2]) ) )
		{
		   $first_code = substr($single_Segment[$m][2],0,2);
		   $second_code = substr($single_Segment[$m][2],3,4); 
		   array_splice($single_Segment[$m],2, 0, $first_code);
		   array_splice($single_Segment[$m],3, 0, $second_code);
		   unset($single_Segment[$m][4]);
		   $single_Segment[$m] = array_filter($single_Segment[$m]);
           $single_Segment[$m] = array_slice($single_Segment[$m],0);
		   
		}
		 $m++;
	  
	  }
		


	  }



echo '<pre>',print_r($single_Segment,1),'</pre>';
if(strpos($single_tickets_rt_pnr, 'SECURED PNR') !== false)
{
unset($single_Segment[2]);
}

	  
// Refund Case Only 	  
//		$type_code = $single_Segment[1][8];		  
//		if($type_code = 'R')
//		{
//		$single_Segment[1][1] = "-";
//		}	  
// Refund case only		


echo '<pre>',print_r($Conj_Segment_Details,1),'</pre>';

if(strpos($single_tickets,'CONJ_1') !== false)
{	    
		$j=0;
		$m=0;
		for($i=0;$i<sizeof($Conj_Segment_Details);$i++)
		{		
		if($j==13)
		{
		$j=0;
		$m++;
		}
		$Conj_Segment_Details_multi[$m][$j] = $Conj_Segment_Details[$i];
         $j++;
		}

echo '<pre>',print_r($single_Segment,1),'</pre>';

          unset($single_Segment[3]); 
		  unset($single_Segment[4]);
		  unset($single_Segment[5]);
		  unset($single_Segment[6]);		  
//Third Segment	
		$single_Segment[3][0] = 3;
		$single_Segment[3][2] = $Conj_Segment_Details_multi[2][1];
		$str_len = strlen($Conj_Segment_Details_multi[2][3]);
		if($str_len==1)
		{
		$single_Segment[3][3] = $Conj_Segment_Details_multi[2][2];
		$single_Segment[3][4] = $Conj_Segment_Details_multi[2][3];
		}
        else
		{
		$single_Segment[3][3] = $Conj_Segment_Details_multi[2][3];
		$single_Segment[3][4] = $Conj_Segment_Details_multi[2][2];
		}	
		$single_Segment[3][5] = $Conj_Segment_Details_multi[2][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[3][6] = "OK";
		$single_Segment[3][7] =  "-";
		$single_Segment[3][8] = $Conj_Segment_Details_multi[2][11];
		$single_Segment[3][9] = $Conj_Segment_Details_multi[2][6];
		$single_Segment[3][10] = $Conj_Segment_Details_multi[2][9];

//fourth segment		
		$single_Segment[4][0] = 4;
		$single_Segment[4][2] = $Conj_Segment_Details_multi[3][1];
         $str_len = strlen($Conj_Segment_Details_multi[3][3]);
		if($str_len==1)
		{
		$single_Segment[4][3] = $Conj_Segment_Details_multi[3][2];
		$single_Segment[4][4] = $Conj_Segment_Details_multi[3][3];
		}
        else
		{
		$single_Segment[4][3] = $Conj_Segment_Details_multi[3][3];
		$single_Segment[4][4] = $Conj_Segment_Details_multi[3][2];
		}	
		$single_Segment[4][5] = $Conj_Segment_Details_multi[3][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[4][6] = "OK";
		$single_Segment[4][7] = "-";
		$single_Segment[4][8] = $Conj_Segment_Details_multi[3][11];
		$single_Segment[4][9] = $Conj_Segment_Details_multi[3][6];
		$single_Segment[4][10] = $Conj_Segment_Details_multi[3][9];
	
//ConjString 	
		

		
}
	
if(strpos($single_tickets,'CONJ_2') !== false)
{ 
echo '<pre>',print_r($Conj_Segment_Status,1),'</pre>';
$size_of_pnr = sizeof($Conj_Segment_Status);

if($size_of_pnr<=53)
{
unset($single_Segment);
$single_Segment[0] = "-";
}
else if(($size_of_pnr>53)and($size_of_pnr<68))
{	

        $j=0;
		$m=0;
		for($i=0;$i<sizeof($Conj_Segment_Details);$i++)
		{		
		if($j==13)
		{
		$j=0;
		$m++;
		}
		$Conj_Segment_Details_multi[$m][$j] = $Conj_Segment_Details[$i];
         $j++;
		}	
	
		unset($single_Segment[1]);
		unset($single_Segment[2]);
		unset($single_Segment[3]);	
	
//First Segment	
		$single_Segment[1][0] = 1;
		$single_Segment[1][2] = $Conj_Segment_Details_multi[4][2];
		$single_Segment[1][3] = $Conj_Segment_Details_multi[4][3];
		$single_Segment[1][4] = $Conj_Segment_Details_multi[4][1];
		$single_Segment[1][5] = $Conj_Segment_Details_multi[4][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[1][6] = "OK";
		$single_Segment[1][7] =  "-";
		$single_Segment[1][8] = $Conj_Segment_Details_multi[4][11];
		$single_Segment[1][9] = $Conj_Segment_Details_multi[4][6];
		$single_Segment[1][10] = $Conj_Segment_Details_multi[4][9];
	
		$single_Segment = array_filter($single_Segment);
		$single_Segment = array_slice($single_Segment,0);
		
		unset($single_Segment[3]);
		unset($single_Segment[4]);
}
else if(($size_of_pnr>=68)and($size_of_pnr<105))
{
	

        $j=0;
		$m=0;
		for($i=0;$i<sizeof($Conj_Segment_Details);$i++)
		{		
		if($j==13)
		{
		$j=0;
		$m++;
		}
		$Conj_Segment_Details_multi[$m][$j] = $Conj_Segment_Details[$i];
         $j++;
		}	
	
		unset($single_Segment[1]);
		unset($single_Segment[2]);
		unset($single_Segment[3]);	
	
//First Segment	
		$single_Segment[1][0] = 1;
		$single_Segment[1][2] = $Conj_Segment_Details_multi[4][2];
		$single_Segment[1][3] = $Conj_Segment_Details_multi[4][3];
		$single_Segment[1][4] = $Conj_Segment_Details_multi[4][1];
		$single_Segment[1][5] = $Conj_Segment_Details_multi[4][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[1][6] = "OK";
		$single_Segment[1][7] =  "-";
		$single_Segment[1][8] = $Conj_Segment_Details_multi[4][11];
		$single_Segment[1][9] = $Conj_Segment_Details_multi[4][6];
		$single_Segment[1][10] = $Conj_Segment_Details_multi[4][9];
//Second segment		
		$single_Segment[2][0] = 2;
		$single_Segment[2][2] = $Conj_Segment_Details_multi[5][3];
		$single_Segment[2][3] = "-";
		$single_Segment[2][4] = $Conj_Segment_Details_multi[5][1];
		$single_Segment[2][5] = $Conj_Segment_Details_multi[5][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[2][6] = "OK";
		$single_Segment[2][7] = "-";
		$single_Segment[2][8] = $Conj_Segment_Details_multi[5][11];
		$single_Segment[2][9] = $Conj_Segment_Details_multi[5][6];
		$single_Segment[2][10] = $Conj_Segment_Details_multi[5][9];
	
		$single_Segment = array_filter($single_Segment);
		$single_Segment = array_slice($single_Segment,0);
		
		unset($single_Segment[3]);
		unset($single_Segment[4]);

}
else if($size_of_pnr>=105)
{

        $j=0;
		$m=0;
		for($i=0;$i<sizeof($Conj_Segment_Details);$i++)
		{		
		if($j==13)
		{
		$j=0;
		$m++;
		}
		$Conj_Segment_Details_multi[$m][$j] = $Conj_Segment_Details[$i];
         $j++;
		}	
	    
		echo $Conj_Segment_Details_multi[4][1]; echo '<br/>';//airlne
		echo $Conj_Segment_Details_multi[4][2]; echo '<br/>';//clas
		echo $Conj_Segment_Details_multi[4][3]; echo '<br/>';//tkt no
//First Segment	
		$single_Segment[1][0] = 1;
		$single_Segment[1][2] = $Conj_Segment_Details_multi[4][1];
		$single_Segment[1][3] = $Conj_Segment_Details_multi[4][3];
		$single_Segment[1][4] = $Conj_Segment_Details_multi[4][2];
		$single_Segment[1][5] = $Conj_Segment_Details_multi[4][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[1][6] = "OK";
		$single_Segment[1][7] =  "-";
		$single_Segment[1][8] = $Conj_Segment_Details_multi[4][11];
		$single_Segment[1][9] = $Conj_Segment_Details_multi[4][6];
		$single_Segment[1][10] = $Conj_Segment_Details_multi[4][9];
//Second segment		
		$single_Segment[2][0] = 2;
		$single_Segment[2][2] = $Conj_Segment_Details_multi[5][1];
		$str_len = strlen($Conj_Segment_Details_multi[5][3]);
		if($str_len==1)
		{
		$single_Segment[2][3] = $Conj_Segment_Details_multi[5][2];
		$single_Segment[2][4] = $Conj_Segment_Details_multi[5][3];
		}
        else
		{
		$single_Segment[2][3] = $Conj_Segment_Details_multi[5][3];
		$single_Segment[2][4] = $Conj_Segment_Details_multi[5][2];
		}	
		$single_Segment[2][5] = $Conj_Segment_Details_multi[5][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[2][6] = "OK";
		$single_Segment[2][7] = "-";
		$single_Segment[2][8] = $Conj_Segment_Details_multi[5][11];
		$single_Segment[2][9] = $Conj_Segment_Details_multi[5][6];
		$single_Segment[2][10] = $Conj_Segment_Details_multi[5][9];

//Third segment		
		$single_Segment[3][0] = 3;
		$single_Segment[3][2] = $Conj_Segment_Details_multi[6][1];
		$str_len = strlen($Conj_Segment_Details_multi[6][3]);
		if($str_len==1)
		{
		$single_Segment[3][3] = $Conj_Segment_Details_multi[6][2];
		$single_Segment[3][4] = $Conj_Segment_Details_multi[6][3];
		}
        else
		{
		$single_Segment[3][3] = $Conj_Segment_Details_multi[6][3];
		$single_Segment[3][4] = $Conj_Segment_Details_multi[6][2];
		}	
		$single_Segment[3][5] = $Conj_Segment_Details_multi[6][10].$Conj_Segment_Details_multi[6][8];
		$single_Segment[3][6] = "OK";
		$single_Segment[3][7] = "-";
		$single_Segment[3][8] = $Conj_Segment_Details_multi[6][11];
		$single_Segment[3][9] = $Conj_Segment_Details_multi[6][6];
		$single_Segment[3][10] = $Conj_Segment_Details_multi[6][9];

//Four segment		
		$single_Segment[4][0] = 4;
		$single_Segment[4][2] = $Conj_Segment_Details_multi[7][1];
		$str_len = strlen($Conj_Segment_Details_multi[7][3]);
		if($str_len==1)
		{
		$single_Segment[4][3] = $Conj_Segment_Details_multi[7][2];
		$single_Segment[4][4] = $Conj_Segment_Details_multi[6][3];
		}
        else
		{
		$single_Segment[4][3] = $Conj_Segment_Details_multi[7][3];
		$single_Segment[4][4] = $Conj_Segment_Details_multi[7][2];
		}
		$single_Segment[4][5] = $Conj_Segment_Details_multi[7][10].$Conj_Segment_Details_multi[2][8];
		$single_Segment[4][6] = "OK";
		$single_Segment[4][7] = "-";
		$single_Segment[4][8] = $Conj_Segment_Details_multi[7][11];
		$single_Segment[4][9] = $Conj_Segment_Details_multi[7][6];
		$single_Segment[4][10] = $Conj_Segment_Details_multi[7][9];				
	
		$single_Segment = array_filter($single_Segment);
		$single_Segment = array_slice($single_Segment,0);
		
		unset($single_Segment[5]);
        unset($single_Segment[6]);
		unset($single_Segment[8]);



}
else
{
unset($single_Segment);
$single_Segment[0] = "-";
}
		



		
}

//$tkt_no_for_checking = find_tkt_no($single_tickets,$tkt_no_array,$arr_cnt);
//if($tkt_no_for_checking == 1575292506544)
//{
//unset($single_Segment[2]);
//unset($single_Segment[3]);
//unset($single_Segment[4]);
//} 



return $single_Segment; 

}




?>

<?php


$date_time = $_REQUEST['date_time'];
$file='tickets/' . $date_time . ".php";//$file='tickets/test.php";
$fh = fopen($file, 'w');
$f_data = " <?php function getAmadeusTickets" . $date_time .  "() { \$Valid_Tkt_no   = '[";
fwrite($fh,$f_data);





$sl_no=1;
for($i=0;$i<sizeof($All_tickets)-1;$i++)
{	

  if($i!=0) { $f_data = ","; fwrite($fh,$f_data); }
  $f_data = "{" ;
  fwrite($fh,$f_data);

	$substring_array = explode('Ticket_image',$All_tickets[$i]);
	//echo '<pre>',print_r($substring_array,1),'</pre>';
	$single_tickets_tkt_image = $substring_array[1];                                         //  TKT
	$single_tickets_tax_string_array = explode('Ticket_tax',$single_tickets_tkt_image);     
	$single_tickets_tax =$single_tickets_tax_string_array[1];                              //  TAX
	$single_tickets_rt_pnr = $substring_array[0];                                         //  PNR


if((strpos($single_tickets_rt_pnr, 'TICKET REVALIDATION/REISSUE IS RECOMMENDED') !== false) and (strpos($single_tickets_tkt_image, 'TICKET NUMBER NOT FOUND') !== false))
{
$status_pass_name++;
}

$res = explode("/",$tkt_Amt_array[$i]);

if($res[0]=="EMDS"){
	$pcc = $_REQUEST['pcc'];
	$tkt_no = find_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i);
	$admin_email = "admin@abskw.com,balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "EMDS FORMAT: ".$pcc;
			$comment = "Ticket Date:".date('Y-m-d');
mail($admin_email, "$subject", $comment, "From:" . $email);
}

if($res[0]!="CANN")
{

//echo $single_tickets_tkt_image;
//echo $single_tickets_rt_pnr;

?>

<?php  	$pcc = $_REQUEST['pcc'];
	$f_data ='"pcc":"' .$pcc. '",';   fwrite($fh,$f_data);
    $tkt_no = find_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i);
  $f_data ='"tkt_no":"' .$tkt_no. '"'; 
  fwrite($fh,$f_data);
  $iata_code = find_iatacode($single_tickets_tkt_image,$single_tickets_rt_pnr); 
  $f_data =',"iata_code":"' .$iata_code. '"'; 
  fwrite($fh,$f_data);

  $dateofissue = find_dateofissue($single_tickets_tkt_image,$single_tickets_rt_pnr);  
  $f_data =',"dateofissue":"' .$dateofissue. '"'; 
  fwrite($fh,$f_data);

  $tkt_type = find_ticket_type($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_no_array,$i);
  $f_data =',"tkt_type":"' .$tkt_type. '"'; 
  fwrite($fh,$f_data);

  $air_code = find_air_code($tkt_no,$single_tickets_rt_pnr); 
  $f_data =',"air_code":"' .$air_code. '"'; 
  fwrite($fh,$f_data);

  $tkt_no = find_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i);
  $f_data =',"tkt_no":"' .$tkt_no. '"'; 
  fwrite($fh,$f_data);

  $tkt_no = find_org_tkt_no($single_tickets_tkt_image,$tkt_no_array,$i);
  $f_data =',"original_tkt_no":"' .$tkt_no. '"'; 
  fwrite($fh,$f_data);


 
  $pass_name = find_pass_name_display($single_tickets_tkt_image,$single_tickets_rt_pnr,$status_pass_name);
 // $pass_name = find_pass_name($single_tickets_tkt_image,$single_tickets_rt_pnr,$status_pass_name); 
  $f_data =',"pass_name":"' .$pass_name. '"'; 
  fwrite($fh,$f_data);

  $gds_pnr = find_gdspnr($tkt_no_array,$i); 
  $f_data =',"gds_pnr":"' .$gds_pnr. '"';
  fwrite($fh,$f_data);

  $air_pnr = find_airpnr($single_tickets_rt_pnr,$single_tickets_rt_pnr); 
  $f_data =',"air_pnr":"' .$air_pnr. '"'; 
  fwrite($fh,$f_data);

  $signon_id = find_signon_id($single_tickets_rt_pnr); 
  $f_data =',"signon_id":"' .$signon_id. '"'; 
  fwrite($fh,$f_data);

  $booked_id = find_booked_id($single_tickets_rt_pnr); 
  $f_data =',"booked_id":"' .$booked_id. '"'; 
  fwrite($fh,$f_data);

  if($tkt_type == "Reissued")
		{
		$NetAmount = find_NetAmount($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
		if (strpos($tkt_Amt_array[$i], '-') !== false)
		{
		  $cp_charges = find_cp_charges($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i);
		  $NetAmount = $NetAmount - $cp_charges;
		}
		if($NetAmount==0)
		{
		 $NetAmount = '-';
		} 
		
		$total_tax = find_total_taxes($single_tickets_tkt_image); 
		$NetAmount = find_NetAmount($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
		if((strpos(NetAmount, '-') !== false)or($NetAmount==0))
		{
		  $total_tax = '-';
		}
		$fare = $NetAmount - $total_tax;
		
		
		}
		else
		{
		$fare = find_totalfare($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i);  
		}
  
  
  $f_data =',"fare":"' .$fare. '"'; 
  fwrite($fh,$f_data);

  $NetAmount = find_NetAmount($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
  if (strpos($tkt_Amt_array[$i], '-') !== false)
  {
    $cp_charges = find_cp_charges($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i);
    $NetAmount = $NetAmount - $cp_charges;
  }
  if($NetAmount==0)
  {
    $NetAmount = '-';
  } 
  $f_data =',"NetAmount":"' .$NetAmount. '"'; 
  fwrite($fh,$f_data);

  $cp_charges = find_cp_charges($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
  $f_data =',"cp_charges":"' .$cp_charges. '"'; 
  fwrite($fh,$f_data);

  $All_Taxes_Array = find_All_taxes($single_tickets_tax,$single_tickets_tkt_image); 
  $NetAmount = find_NetAmount($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
		if(($All_Taxes_Array==1)or(strpos(NetAmount, '-') !== false)or($NetAmount==0))
		{
		 
		 $Taxes1 = "-";
		 $Taxes2 = "-";
		 $Taxes345 = "-";
		}
		else
		{
		$Taxes1 = "";
		$j = 1;

			 if($All_Taxes_Array[0]!="")
			 {
			 $tax = preg_replace('/[^0-9^.]/','',$All_Taxes_Array[0]); 
			 $tax = sprintf ("%.3f", $tax);
//			 $Taxes1 .= "Tax ".$j." : ".$tax." <br/>"; 
			 $Taxes1 = $tax; 
			 $j++;
			 }
			 
		$Taxes2 = "";	 

			 if($All_Taxes_Array[1]!="")
			 { 
			 $tax = preg_replace('/[^0-9^.]/','',$All_Taxes_Array[1]);
			 $tax = sprintf ("%.3f", $tax);
			 $Taxes2 = $tax; 
			 }
			 
			 $Taxes345 = 0;
			$j = 1;
			for($k=2;$k<12;$k++)
			{
			 if($All_Taxes_Array[$k]!="")
			 { 
			 $tax = preg_replace('/[^0-9^.]/','',$All_Taxes_Array[$k]);
			 $Taxes345 = bcadd($Taxes345, $tax, 3);
			 $j++;
			 }
			}
			 $Taxes345 = $Taxes345; 

		 
		}
    $f_data =',"Taxes1":"' .$Taxes1. '"';
    fwrite($fh,$f_data);
    $f_data =',"Taxes2":"' .$Taxes2. '"';
    fwrite($fh,$f_data);
    $f_data =',"Taxes345":"' .$Taxes345. '"';
    fwrite($fh,$f_data);

  $total_tax = find_total_taxes($single_tickets_tkt_image); 
  $NetAmount = find_NetAmount($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_Amt_array,$i); 
		if((strpos(NetAmount, '-') !== false)or($NetAmount==0))
		{
		  $total_tax = '-';
		}
  $f_data =',"total_tax":"' .$total_tax. '"';
  fwrite($fh,$f_data);

	$segments_all = find_SegmentDetail($single_tickets_tkt_image,$single_tickets_rt_pnr,$tkt_no_array,$i); 
	
	if($segment_array[0]=="-")
	{ } else {		 
			$slno =1;
			for($j=1,$g=0;$j<sizeof($segments_all);$j++,$g++)
			   { 
               $f_data =',"slno":"' .$slno. '"';
               fwrite($fh,$f_data);
               $Cityorigin = substr($segments_all[$j][9],0,3);
               $f_data =',"Cityorigin' . $j . '":"' .$Cityorigin. '"';
               fwrite($fh,$f_data);

               $CityDesti = substr($segments_all[$j][9],3,6); 
               $f_data =',"CityDesti' . $j . '":"' .$CityDesti. '"';
               fwrite($fh,$f_data);

               $Airline = $segments_all[$j][2];
               $f_data =',"Airline' . $j . '":"' .$Airline. '"';
               fwrite($fh,$f_data);

               $FlightNumber = $segments_all[$j][3];
               $f_data =',"FlightNumber' . $j . '":"' .$FlightNumber. '"';
               fwrite($fh,$f_data);

               $Class = $segments_all[$j][4]; 
               $f_data =',"Class' . $j . '":"' .$Class. '"';
               fwrite($fh,$f_data);

               $DepDate = substr($segments_all[$j][5],0,5);
               $f_data =',"DepDate' . $j . '":"' .$DepDate. '"';
               fwrite($fh,$f_data);

               $DepTime = substr($segments_all[$j][5],5,4);
               $f_data =',"DepTime' . $j . '":"' .$DepTime. '"';
               fwrite($fh,$f_data);

               $status = $segments_all[$j][6];
               $f_data =',"status' . $j . '":"' .$status. '"';
               fwrite($fh,$f_data);

               $ArrTime = $segments_all[$j][10];
               $f_data =',"ArrTime' . $j . '":"' .$ArrTime. '"';
               fwrite($fh,$f_data);

               $fare_basis = $segments_all[$j][7]; 
               $f_data =',"fare_basis' . $j . '":"' .$fare_basis. '"';
               fwrite($fh,$f_data);

				    $NVB_bagage = $segments_all[$j][11]; 
				
				if($NVB_bagage)
				{
				  $NVB_bagage = explode("_",$NVB_bagage);
				  $bk = $NVB_bagage[0];
				  $len_of_NV = strlen($NVB_bagage[1]); 
				  
				  if($NVB_bagage[1])
				  {
					  if($len_of_NV>6)
					  {
					  $NVB = substr($NVB_bagage[1],0,5); 
					  $NVA = substr($NVB_bagage[1],6,5);  
					  }
					  else
					  { 
					  $NVB = substr($NVB_bagage[1],0,5); 		
					  $NVA = "-";
					  }
				  }
				  else
				  {
				      $NVB = "-"; 		
					  $NVA = "-";
				  }  
				} 
				else 
				{ 
				$NVB = "-"; 		
				$NVA = "-"; 
				$bk = "-";
				}

        $f_data =',"NVB' . $j . '":"' .$NVB. '"';
               fwrite($fh,$f_data);
               $f_data =',"NVA' . $j . '":"' .$NVA. '"';
               fwrite($fh,$f_data);
               $f_data =',"bk' . $j . '":"' .$bk. '"';
               fwrite($fh,$f_data);

				$slno++; 
			   }
	}
}	
$sl_no++; 

  $f_data = "}"	;
  fwrite($fh,$f_data);
} // end for


$f_data = "]';  return ( \$Valid_Tkt_no); } ?>";
fwrite($fh,$f_data);
//   $file='tickets/' . date(mdy) . ".php";
//    file_put_contents($file, $f_data);
fclose($fh);

?>	



<?php

// delete older files only in that day
$D_new_files_list = array();
foreach (glob("tickets/*.php") as $filename)
{
  $D_file_name_1 = explode("/", $filename);
  $D_file_name = explode(".", $D_file_name_1[1]);
  $D_file_expansion = explode("_",$D_file_name[0]);
  echo $D_file_expansion[0] . "    " . $D_file_expansion[1] . "<br>";

  $f_l_date = $D_new_files_list[$D_file_expansion[0]];
  if($f_l_date == '') {  $D_new_files_list[$D_file_expansion[0]] = $D_file_expansion[1];  }
  else {
    if( $D_new_files_list[$D_file_expansion[0]] < $D_file_expansion[1] ){
      $D_new_files_list[$D_file_expansion[0]] =  $D_file_expansion[1];

    }
  }

}
// echo print_r($D_new_files_list);

foreach ($D_new_files_list as $key => $values) {

  foreach (glob("tickets/*.php") as $filename){
  $D_file_name_1 = explode("/", $filename);
  $D_file_name = explode(".", $D_file_name_1[1]);
  $D_file_expansion = explode("_",$D_file_name[0]);
   if($key == $D_file_expansion[0] && $values !=  $D_file_expansion[1]){      }

  }
}



  ?>



	
