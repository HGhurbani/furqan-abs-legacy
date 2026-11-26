<?php
class Welcome_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getFeesDate()
	{
		//class interval
		$cInterval = $this->common_model->LookUpDisplay('ClassFees');
		$cDate     = date('Y-m-d', strtotime(date('Y-m-01') ."+$cInterval days"));
		
		return ($cDate);
	}//end function

}//end class
?>