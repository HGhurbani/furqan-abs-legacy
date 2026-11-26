<?php
class Index_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function forgotPwd()
	{
		$newRandId = rand('9999','2325');
		$data 	   = array('vPass' => md5($newRandId));
		$this->db->where('vEmail',$this->input->post('txtEmail'));
		$this->db->update('{PRE}Admin', $data); 
		//for mailing
		$to         = $this->input->post('txtEmail');
		$toName     = $this->input->post('txtName');
		$from      	= $this->common_model->LookUpDisplay('Email');
		$fromName   = 'Administrator';
		$subject  	= FORGOTPASSWORD;
		//$mailFormat = read_file('./static/email/emailer.html');
		$arrSearch	= array("{SITE_TITLE}", "{SITE_URL}", "{EMAIL_TITLE}","{EMAIL_CONTENT}");
		$arrReplace	= array($this->common_model->get_site_title(), base_url(), 'Dear Administrator', $htmlbody);
		$htmlbody	= str_replace($arrSearch, $arrReplace,$mailFormat);
		//$this->common_model->send_mail($to,$toName,$from,$fromName,$subject,$htmlbody,$html=true);
	}//end function 
}//end class
?>