<?php
class Login_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
	function loginCheck()
	{
	   $data['list_Lang'] = array('eng'=>'English','ar'=>'Arabic');

	   $sqlAdmin = $this->db->query("SELECT u.nId,vUsername,u.vFName,o.nLoggedOn,o.vType FROM {PRE}Admin u
										LEFT JOIN {PRE}Online o ON u.nId = o.nUser WHERE u.vUsername='".$this->input->post('txtUsername')."'
									 AND u.vActive='1' AND u.mobile=1");									
		
		if ($sqlAdmin->num_rows() > 0)
		{
			$sqlAdmin = $this->db->query("SELECT u.nId,vUsername,u.vFName,o.nLoggedOn,o.vType FROM {PRE}Admin u
											LEFT JOIN {PRE}Online o ON u.nId = o.nUser WHERE u.vUsername='".$this->input->post('txtUsername')."'
												AND vPass='".md5($this->input->post('txtPassword'))."'
										  	AND vActive='1'");
			if ($sqlAdmin->num_rows() > 0)
			{
				
				$sqlAdmin = $this->db->query("SELECT u.nId,vUsername,u.vEmail,u.vFName,u.vLName,u.nLocation,g.vGroupTitle,o.nLoggedOn,o.vType 
                         FROM {PRE}Admin u
											   LEFT JOIN {PRE}Online o ON u.nId = o.nUser 
                         LEFT JOIN {PRE}AdminGroups g ON u.nGroupId = g.nGId WHERE vPass='".md5($this->input->post('txtPassword'))."'
											  AND u.vActive='1' AND vUsername='".$this->input->post('txtUsername')."'");
				if ($sqlAdmin->num_rows() > 0)
				{
					if($this->input->post('chkSave') && $this->input->post('chkSave')==1)
					{
						//set cookie in two way
						setcookie('AdminUser',$this->input->post('txtUsername'),time()+60*60*24*30);
						setcookie('AdminPassword',$this->input->post('txtPassword'),time()+60*60*24*30);
					}//end if
					
					$rowAdmin = $sqlAdmin->row_array();					

					//update last login
					$lastLogin = date('Y-m-d H:i:s');
					$data      = array('vLastLogin' => $lastLogin);
					$where     = "nId = '".$rowAdmin['nId']."'";
					$str       = $this->db->update_string('{PRE}Admin', $data, $where); 
					$this->db->query($str);

          $periodVal = $this->common_model->get_account_period();

          $loc = "(" .  $rowAdmin['nLocation'] . ")";
          $divQuery =  $this->db->query("SELECT nDivision FROM {PRE}Location WHERE nId IN " . $loc . " GROUP BY nDivision");
          if($divQuery->num_rows() > 0)
          {
            $div = "";
            foreach($divQuery->result() as $key => $row){
              if($div != "") $div .= ',';
              $div .= $row->nDivision;
            }
          }
          $div = "(" . $div . ")";

					//set session
					$this->load->library('session');
					$newdata = array(
									  'AbsAdmin' => $rowAdmin['nId'],
									  'AccPeriod' => $periodVal,
									  'logged_in'  => TRUE,
									  'showName'   => $rowAdmin['vFName'],
									  'showLName'   => $rowAdmin['vLName'],									  									  'vEmail'   => $rowAdmin['vEmail'],
									  'groupName'   => $rowAdmin['vGroupTitle'],
										'accessLoc' => $loc, 
										'accessDiv' => $div, 
									  'lastLogin'  => date('F d, Y H:i:s',strtotime($lastLogin)),
									  'lang'	   => $this->input->post('ddlLanguage')
									  );
					$this->session->set_userdata($newdata);
					if($rowAdmin['nLoggedOn'] == '')
					{
						//insert into db
						$data 	= array('vType' => 'Admin','nUser' => $rowAdmin['nId'], 'nLoggedOn' => time(),
												'nActiveTill' => time(), 'nIdle' => '0');
						$str 	= $this->db->insert_string('{PRE}Online', $data); 
					}//end if
					
					elseif($rowAdmin['vType'] !='Admin')
					{
						$data 	= array('vType' => 'Admin','nUser' => $rowAdmin['nId'], 'nLoggedOn' => time(),
												'nActiveTill' => time(), 'nIdle' => '0');
						$str 	= $this->db->insert_string('{PRE}Online', $data); 
					}//end elseif
					
					else
					{
						$data 	= array('vType' => 'Admin','nUser' => $rowAdmin['nId'], 'nLoggedOn' => time(),
												'nActiveTill' => time(), 'nIdle' => '0');
						$where 	= " nId = '".$rowUser['nId']."'";
						$str 	= $this->db->update_string('{PRE}Online', $data,$where); 
					}//end else
					$this->db->query($str);									
				date_default_timezone_set('Asia/Kuwait');				
				$admin_email = $this->session->userdata(vEmail); 		
				$email = "loginabs@abskw.com";				
				$subject = "Login Details  : ".date('Y-m-d H:i:s');		
				$comment = "\n\n Common Ip Address : ".$_SERVER['REMOTE_ADDR'];		
				$comment .= "\n\n System Ip Address : ".getHostByName(getHostName());		
				$comment .= "\n\n Login Name : ".$this->session->userdata(showName) . "-" . $this->session->userdata(showLName);		
				//mail($admin_email, "$subject", $comment, "From:" . $email);
					
					
					redirect(site_url("manage_info/showlist/8"));		
					
				}//end if
			}//end if
			else
			{
				$data['error_value'] = "Invalid Password. ";
				$this->load->view('general/template_top1',$data);
				$this->load->view('login/login_form');
				$this->load->view('general/template_bottom1');
			}//end else
						}//end if
		else
		{
			$data['error_value'] = "Invalid Username. ";
			$this->load->view('general/template_top1',$data);
			$this->load->view('login/login_form');
			$this->load->view('general/template_bottom1');
		}//end else
	}//end function
	
	function getCheckinDetails()
	{
		$adminGroupRow = array();
		$sqlLoginQuery = "SELECT l.*,TIMEDIFF(l.dCheckout,l.dCheckin) as TotalTime FROM {PRE}Check l 
						  LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		//$this->session->set_userdata('sess_query', $sqlLoginQuery);
		session_start();
		$_SESSION['sess_query'] = $sqlLoginQuery;
		$adminGroupList         = $this->db->query($sqlLoginQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return($adminGroupRow);
	}//end function
	
	function checkLogin($extraCheck)
	{
		$adminGroupRow  = array();
		$sqlLoginQuery  = "SELECT *,TIMEDIFF(dCheckout,dCheckin) as TotalTime
							FROM {PRE}Check  ".$extraCheck." 
						  LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlLoginQuery;
		$adminGroupList 		= $this->db->query($sqlLoginQuery);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return($adminGroupRow);
	}//end function
	
}//end class
?>
