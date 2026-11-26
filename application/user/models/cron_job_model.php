<?php
class cron_job_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function checkAllocationTable()
	{
        $this->db->delete('{PRE}CashAllocation', array('vStatus' => '0'));
    }//end function

    function checkReconciliation()
	{
        $fetchCode = $this->db->query("SELECT nId,vTktNumber,vType,nNetAmount FROM {PRE}Reconciliation WHERE vStatus='N'");
        if ($fetchCode->num_rows() > 0)
        {
            foreach ($fetchCode->result() as $key=>$row)
            {
                switch($row->vType)
                {
					
                    case "Tkt":
					
                        $ticketId = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual = '".$row->vTktNumber."'");
                        $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE vTicktManual = '".$row->vTktNumber."'");

                        $fetchInv = $this->db->query("SELECT sc.*,scd.vNetAmt FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd ON
                                                      sc.nSCId=scd.nSalesCloseId WHERE scd.vTktNumber = '".$ticketId."' AND scd.vType='Tkt'
                                                      ");//AND MONTH(sc.dDate) = MONTH(CURDATE()) AND YEAR(sc.dDate) = YEAR(CURDATE())
													  
					    $showVal  = $fetchInv->row();
						//echo $row->vType."--".$ticketId."--".$row->nNetAmount."--".$fetchInv->row."<br>";
                        if ($fetchInv->num_rows() > 0)
                        {
							
                            if($row->nNetAmount==$showVal->vNetAmt):
                                $data = array('vStatus' => 'M','vInvoice' =>$showVal->vInvoiceNumber,'dInvoiceDate' => $showVal->dDate,
                                              'vPassenger' => $passenger,'vSystemAmt' => $showVal->vNetAmt);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            else:
                                $data = array('vInvoice' =>$showVal->vInvoiceNumber,'dInvoiceDate' => $showVal->dDate,
                                              'vPassenger' => $passenger,'vSystemAmt' => $showVal->vNetAmt);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            endif;
                        }
                        break;
		case "ACM":     //  					AND MONTH(dDate) = MONTH(CURDATE()) AND YEAR(dDate) = YEAR(CURDATE())                   
					
					
					$fetchInv = $this->db->query("SELECT * FROM {PRE}AcmEntry WHERE vRefNumber = '".$row->vTktNumber."'");
					$showVal  = $fetchInv->row();                        
					if ($fetchInv->num_rows() > 0)
						{                            
					if($row->nNetAmount==$showVal->vAmountDC):  
					$data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate, 
					'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);                                
					$this->db->where('nId',$row->nId);                                
					$this->db->update('{PRE}Reconciliation', $data);                            
					else:                                
					$data = array('vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate, 
					'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);      
					$this->db->where('nId',$row->nId);                                
					$this->db->update('{PRE}Reconciliation', $data);                           
					endif;                        }                    
		break;
                    case "ADM":
					// AND MONTH(dDate) = MONTH(CURDATE()) AND YEAR(dDate) = YEAR(CURDATE())
					
                        $fetchInv = $this->db->query("SELECT * FROM {PRE}AdmEntry WHERE vRefNumber = '".$row->vTktNumber."'");
                        $showVal  = $fetchInv->row();
                        if ($fetchInv->num_rows() > 0)
                        {
                            if($row->nNetAmount==$showVal->vAmountDC):
                                $data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                              'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            else:
                                $data = array('vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,
                                              'vPassenger' => $showVal->vRefName,'vSystemAmt' => $showVal->vAmountDC);
                                $this->db->where('nId',$row->nId);
                                $this->db->update('{PRE}Reconciliation', $data);
                            endif;
                        }
                    break;
					
					case "Rfd": 
                 
					$ticketId = $this->common_model->get_field_name('TicketBooking','nTId'," WHERE vTicktManual= '".$row->vTktNumber."'");  
					$ticketI = $this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE nTktNum = '".$row->vTktNumber."' AND vNewOld = 'O' ");
                    
					if( $ticketId == '' || $ticketI=='O') {                    
					$ticketId = $this->common_model->get_field_name('RefundRegisterDetails','nTktNum'," WHERE nTktNum = '".$row->vTktNumber."' AND vNewOld = 'O' ");
                    $passenger = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE nTktNum = '".$row->vTktNumber."' AND vNewOld = 'O' ");  
					}                  
					else {                    
					$passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE vTicktManual = '".$row->vTktNumber."'");  
					}				                   
					$fetchInv = $this->db->query("SELECT rc.*,rcd.vNetAmt FROM {PRE}RefundClosing rc LEFT JOIN {PRE}RefundClosingDetails rcd ON                      
					rc.nRCId=rcd.nRefundCloseId WHERE rcd.nTicketNumber = '".$ticketId."' AND rcd.vType='Tkt'");
					$showVal  = $fetchInv->row();                  
					$vNetAmt_refund = $showVal->vNetAmt * -1;                  
					if ($fetchInv->num_rows() > 0)                  
					{                    
				if($row->nNetAmount==$vNetAmt_refund):                      
				$data = array('vStatus' => 'M','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate, 
				'vPassenger' => $passenger,'vSystemAmt' => $vNetAmt_refund);                    
				$this->db->where('nId',$row->nId);                    
				$this->db->update('{PRE}Reconciliation', $data);                    
				else:                    
				$data = array('vStatus' => 'N','vInvoice' =>$showVal->vDocNumber,'dInvoiceDate' => $showVal->dDate,  
				'vPassenger' => $passenger,'vSystemAmt' => $vNetAmt_refund);                    
				$this->db->where('nId',$row->nId);                    
				$this->db->update('{PRE}Reconciliation', $data);                   
				endif;                  }                  
				break;
                }
            }//end for each
        } //end if
    }//end function

}//end class
?>