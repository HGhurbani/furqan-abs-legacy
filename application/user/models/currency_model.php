<?php
class Currency_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*********************Currency******************************/

	function getAllCurrencies()
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Currency 
											 ORDER BY nPosition DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function
	
	function getCorresCurrency()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Currency WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertCurrencies()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => $this->input->post('txtTitle'),'vCurrcode' => $this->input->post('txtCode'),
						'vCoin' => $this->input->post('txtCoin'),'vRate' => $this->input->post('txtRate'),
						'vDecimal' => $this->input->post('txtDecimal'),'vActive' => $this->input->post('chkActive'),
						'nPosition' => $this->common_model->maxPos('Currency'));
		$this->db->insert('{PRE}Currency', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("currency/editCurrencies/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("currency/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function updateCurrencies()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => $this->input->post('txtTitle'),'vCurrcode' => $this->input->post('txtCode'),
						'vCoin' => $this->input->post('txtCoin'),'vRate' => $this->input->post('txtRate'),
						'vDecimal' => $this->input->post('txtDecimal'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Currency', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("currency/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function
	
	function deleteCurrency()
	{
		$this->db->delete('{PRE}Currency', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("currency/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteAllCurrency()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Currency', $data); 
				}//end foreach
				redirect(site_url("currency/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Currency', $data); 
				}//end foreach
				redirect(site_url("currency/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Currency', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("currency/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterCurrency($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Currency ".$extraCheck."
											 ORDER BY nPosition DESC
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($currid)
	{
		$adminGroupList     = $this->db->query("SELECT * FROM {PRE}Currency WHERE nId = '".$currid."'"); 
		$showVal	        = $adminGroupList->row();
		$content            = '<table class="reportForm">
			<tr>
				<td><strong>'.CURRCODE.' :</strong></td>
				<td>'.$showVal->vCurrcode.'</td>
		   </tr>
			<tr>
				<td><strong>'.CURRNAME.' :</strong></td>
				<td>'.$showVal->vTitle.'</td>
			</tr>
			<tr>
				<td><strong>'.CURRCOIN.' :</strong></td>
				<td>'.$showVal->vCoin.'</td>
			</tr>
			<tr>
				<td><strong>'.CURRRATE.' :</strong></td>
				<td>'.$showVal->vRate.'</td>
			</tr>
			<tr>
				<td><strong>'.CURRSELLRATE.' :</strong></td>
				<td>'.$showVal->vSellingRate.'</td>
			</tr>
			<tr>
				<td><strong>'.CURRDECIMAL.' :</strong></td>
				<td>'.$showVal->vDecimal.'</td>
			</tr>
		 </table>';
		return($content);
	}//end function
 
	function countryCityFromIP($ipAddr)
	{
	   //function to find country and city name from IP address
	   //Developed by Roshan Bhattarai 
	   //Visit http://roshanbh.com.np for this script and more.
	  
	  //verify the IP address for the  
	  ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
	  // This notice MUST stay intact for legal use
	  $ipDetail=array(); //initialize a blank array
	  //get the XML result from hostip.info
	  $xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);print_r( $xml);
	  //get the city name inside the node <gml:name> and </gml:name>
	  preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
	  //assing the city name to the array
	  $ipDetail['city']=$match[2]; 
	  //get the country name inside the node <countryName> and </countryName>
	  preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
	  //assign the country name to the $ipDetail array 
	  $ipDetail['country']=$matches[1];
	  //get the country name inside the node <countryName> and </countryName>
	  preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
	  $ipDetail['country_code']=$cc_match[1]; //assing the country code to array
	  //return the array containing city, country and country code
	  return $ipDetail;
	} //end function
	
	function getIpDetails()
	{
		$ip  = $_SERVER['REMOTE_ADDR'];
		if($_SERVER['REMOTE_ADDR'] == '192.168.1.3' || $_SERVER['REMOTE_ADDR'] == '192.168.1.12')
				$ip  = '12.215.42.19';

		require_once('static/classes/geoplugin.class.php');
		$geoplugin = new geoPlugin();
		//locate the IP
		$geoplugin->locate($ip);

		return($geoplugin->currencyCode);
	}//end fucntion
	
	//function to calcualte online rate
	function getCurrentExhRate()
	{
		//make string to be put in API
		$string = "1".$this->sCurrency."=?".$this->pCurrency;
		
		//Call Google API
		$google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;

		//Get and Store API results into a variable
		$result = file_get_contents($google_url);
		 
		//Explode result to convert into an array
		$result = explode('"', $result);
		
		################################
		# Right Hand Side
		################################
		$converted_amount = explode(' ', $result[3]);
		$conversion = $converted_amount[0];
		$conversion = round($converted_amount[0],2);	 

		return ($conversion);
	}//end function
}//end class
?>