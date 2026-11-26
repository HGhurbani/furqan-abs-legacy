<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session_Check extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function storeSessionVal()
	{
		$this->load->helper('url');
		ob_start();
		$this->load->library('session');
	
		 if (!isset($this->session)) 
		 {
            $this->load->library('session');
        }
		
		if(!$this->session->userdata('AbsAdmin') && $this->session->userdata('logged_in') == FALSE && current_url() != base_url().'index.php/login')
		{
			redirect(site_url("login"));		
		}//end if
		if($this->session->userdata('AbsAdmin') && $this->session->userdata('logged_in') == TRUE && current_url() == base_url().'index.php/logout')
		{
			$this->session->sess_destroy();
			redirect(site_url("login"));
		}//end if
		if($this->session->userdata('AbsAdmin') && $this->session->userdata('logged_in') == TRUE && current_url() != base_url().'index.php/logout')
		{
			//$data['LastLogin'] = $this->session->userdata('AbsAdmin');
			//$data['LastLogin'] = $this->session->userdata('lastLogin');
			$this->load->model('common_model');    
			
			//site Name
		/* 
		 */
       // $this->CI->load->helper('form');
       // $this->CI->load->helper('url');
        
		
			//$CI =& get_instance();
			//Session_val_model::Model();
			//$this->load->model('session_val_model');
		//$data['LastLogin'] = $this->session_val_model->get_set_sessions();
		}//end if
		else
		{
			$this->load->database();
			$sqlAdmin = $this->db->query("SELECT nId,vFName,vLName FROM {PRE}Admin WHERE vPass='".md5($this->input->post('txtPassword'))."'
													AND vActive='1' AND vUsername='".$this->input->post('txtUsername')."'");
				if ($sqlAdmin->num_rows() > 0)
				{
					if($this->input->post('chkSave') && $this->input->post('chkSave')==1)
					{
						//set cookie in two way
/*						$this->input->set_cookie('AdminUser', $this->input->post('txtUsername'), time()+60*60*24*30, base_url(), '/', 'abcindo_');
						$this->input->set_cookie('AdminPassword', $this->input->post('txtPassword'), time()+60*60*24*30, base_url(), '/', 'abcindo_');
*/						setcookie('AdminUser',$this->input->post('txtUsername'),time()+60*60*24*30);
						setcookie('AdminPassword',$this->input->post('txtPassword'),time()+60*60*24*30);
					}//end if
					
						$rowAdmin = $sqlAdmin->row_array();
						//update last login
						$lastLogin = date('Y-m-d H:i:s');
						$data = array('vLastLogin' => $lastLogin);
						$where = "nId = '".$rowAdmin['nId']."'";
						$str = $this->db->update_string('{PRE}Admin', $data, $where); 
						$this->db->query($str);
							
						//set session
						$this->load->library('session');
						$newdata = array(
											  'AbsAdmin'  => $rowAdmin['nId'],
											  'logged_in' => TRUE,
											  'showName' => $rowAdmin['vFName'],
											  'lastLogin' => date('F d, Y H:i:s',strtotime($lastLogin))
										  );
						$this->session->set_userdata($newdata);
	
						redirect(site_url("welcome"));						
					}//end if
		}//end else
		
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>