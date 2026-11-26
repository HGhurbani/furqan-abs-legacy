<?php
class Visa_setup_model extends CI_Model {

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function getVisaListDetails(){
    $listQueryRow = array();
    $listQuery = $this->db->query("SELECT vs.nVId as nId, vs.vVisaItem , svs.vVisaItem as vVisaListCategory, vs.vActive FROM {PRE}VisaSetup vs LEFT JOIN {PRE}VisaSetup svs ON  svs.nVId = vs.nCategoryId WHERE vs.nCategoryId != 0 ORDER BY vs.nCategoryId ASC, vs.vVisaItem ASC");
    if($listQuery->num_rows() >0){
      foreach($listQuery->result() as $key => $row)
      {
        $listQueryRow[] = $row;

      }//end for each
    }//end if
    return ($listQueryRow);	
  } //end func


  function getVisaCategory() {
    $categoryGroupRow = array();
    $categoryGroup = $this->db->query("SELECT nVId, vVisaItem from {PRE}VisaSetup where nCategoryId = 0 ");
    if($categoryGroup->num_rows() >0)
    {
      foreach ($categoryGroup->result() as $key => $row)
      {
        $categoryGroupRow[$row->nVId] = stripslashes($row->vVisaItem);
      } //end for each
    }
    else{
      $categoryGroupRow[0] = NOVALUE;

    } //end if
    return $categoryGroupRow;
  } //end function


  function getCorresVisaItem(){
    $fetchVisaItem = $this->db->query("SELECT * FROM {PRE}VisaSetup WHERE nVId = ".$this->catId." ");
    $showVal = $fetchVisaItem->row();
    return $showVal;

  }//end function

  function insertVisaItem()
  {
    $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),
        'vVisaItem' => $this->input->post('txtVisaItem'),
        'vActive' => $this->input->post('chkActive'),
        'nCategoryId' => $this->input->post('ddlVisaCategory'));
    $this->db->insert('{PRE}VisaSetup', $data); 
    $id = $this->db->insert_id();
    $msgVal = 'add_suc';

    if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
    {
      redirect(site_url("visa_setup/editVisaItem/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
    }//end if
    if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
    {
      redirect(site_url("visa_setup/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$id);	
    }//end if
  }//end function

  function updateVisaItem()
  {
    $data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),
        'vVisaItem' => $this->input->post('txtVisaItem'),
        'vActive' => $this->input->post('chkActive'),
        'nCategoryId' => $this->input->post('ddlVisaCategory'));

    $this->db->where('nVId',$this->input->post('nId'));
    $this->db->update('{PRE}VisaSetup', $data); 
    $msgVal = 'edit_suc';
    redirect(site_url("visa_setup/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('nId'));	
  }//end function


  function deleteVisaItem()
  {
    $this->db->delete('{PRE}VisaSetup', array('nVId' => $this->uri->segment(4))); 
    $msgVal = 'del_suc';
      redirect(site_url("visa_setup/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('nId'));	
  }//end function 
	

}//end class
?>
