<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Db_backup extends CI_Controller {

	function __construct()
	{		ini_set('memory_limit', '-1');
		parent::__construct();
		$this->load->model('db_backup_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = DATABASEBACKUP;
        $this->btnVal    = CREATEBACKUP;
		$this->common_model->check_admin_authorization();

        $target = '.';
		
		//$this->common_model->get_total_num_rows('Db','nId'," ")
		
        $this->common_model->_paging(7, $target,'db_backup/showlist/'.$this->uri->segment(3));
        $data['list_value'] = $this->db_backup_model->getAllBackUps();

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top');
		$this->load->view('db_backup/db_backup',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function create_backup()
    { 
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->showTitle = 'Backup Management';
        //$this->common_model->check_admin_authorization();

        $this->db_backup_model->db_backup();

    }//end function

    function deleteBackUp()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->showTitle = 'Backup Management';
        $this->common_model->check_admin_authorization();

        $this->common_model->check_admin_authorization();
        $this->db_backup_model->deleteBackUp();
    }//end function

    function delrecords()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->showTitle = 'Backup Management';
        $this->common_model->check_admin_authorization();
        $this->db_backup_model->delrecords();
    }//end function

    function downloadFile()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->helper('download');
        $this->fileVal  = $this->common_model->get_field_name('Db','vFile'," WHERE nId='".$this->uri->segment(4)."'");
        $data 		= file_get_contents(base_url().'static/db_backup/'.$this->fileVal); // Read the file's contents
        $name 		= 'db_backup.sql';
        force_download($name, $data);
    }//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>