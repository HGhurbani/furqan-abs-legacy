<?php
class Db_backup_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getAllBackUps()
    {
        $adminGroupRow  = array();
        $adminGroupList = $this->db->query("SELECT * FROM {PRE}Db ORDER BY nId DESC LIMIT 0,7");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function deleteBackUp()
    {
        $file_name = $this->common_model->get_field_name('Db','vFile'," WHERE nId='".$this->uri->segment(4)."'");
        @unlink('static/db_backup/'.$file_name);

        $this->db->delete('Db', array('nId' => $this->uri->segment(4)));
        redirect(site_url('db_backup/showlist/'.$this->uri->segment(3)));
    }//end function

    function delrecords()
    {
        switch($this->input->post('ddlAction'))
        {
            case "delete":
                foreach($this->input->post('del') as $val)
                {
                    $file_name = $this->common_model->get_field_name('Db','vFile'," WHERE nId='".$val."'");
                    @unlink('static/db_backup/'.$file_name);

                    $this->db->delete('Db', array('nId' => $val));
                }//end foreach
                break;
        }//end switch
        redirect(site_url('db_backup/showlist/'.$this->uri->segment(3)));
    }//end function

    function db_backup()
	
    {
		$fl1= './static/db_backup/backup_'.date("l").'.sql';
		$nId = $this->common_model->get_field_name('Db','nId'," WHERE vFile='".$fl1."'");
		 $this->db->delete('Db', array('nId' => $nId));
		
		foreach (glob("./static/db_backup/*.sql") as $filename)
		{
		  
		  $date_time = date('Y-m-d');
		  $fl= './static/db_backup/backup_'.date("l").'.sql';
		  if($filename==$fl){
			  unlink($filename);
		  }
		 
		}
		
        // Load the DB utility class
        $this->load->dbutil();
        $prefs = array(
            'ignore'      => array(),           // List of tables to omit from the backup
            'format'      => 'txt',             // gzip, zip, txt
            'filename'    => 'backup_'.date("l").'.sql',    // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
            'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
            'newline'     => "\n"               // Newline character used in backup file
        );
        // Backup your entire database and assign it to a variable
        $backup =& $this->dbutil->backup($prefs);

        $this->load->helper('file');		
		$vf="backup_".date("l").'.sql';
        write_file('static/db_backup/'.$vf, $backup);

        $file_name = 'backup_'.date('Y-m-d').'_'.time().'.sql';
        $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vFile' =>$vf,'dDate' => date('Y-m-d'));
        $this->db->insert('{PRE}Db', $data);
        redirect(site_url('db_backup/showlist/'.$this->uri->segment(3)));
    }//end function

}//end class
?>