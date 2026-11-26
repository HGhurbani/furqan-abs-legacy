<?php
require_once 'static/classes/phpgraphlib.php';
require_once 'static/classes/phpgraphlib_pie.php';

class Graph_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//function to create gds bar graph
	function gdsBarGraph()
	{
		$graph = new PHPGraphLib(350,280);
		$data  = array();
		session_start();
		
		$sqlVal = $_SESSION['sess_query'];
		
		//create gds summery array
		$sqlQuery = $this->db->query($sqlVal);
		
		if ($sqlQuery->num_rows() > 0)
		{
			foreach ($sqlQuery->result() as $key=>$row)
			{
				if(trim($row->gdsVal) != ''):
                    $profit = ($row->salesAmt-$row->netAmt);
					
					if($row->netAmt > 0)
                        $profitPercent = round(($profit/$row->netAmt*100),2);
					else
						$profitPercent = 0;

                    $name = $this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$row->gdsVal."'");
					$data[$name] = $profitPercent;
				endif;
			}//end for each
		}//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('GDS Summary Report');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
	}//end function
	
	//function to create sales bar graph
	function salesBarGraph()
    {
        $graph = new PHPGraphLib(350,280);
        $data  = array();
        session_start();

        $sqlVal = $_SESSION['sess_query'];

        //create gds summery array
        $sqlQuery = $this->db->query($sqlVal);

        if ($sqlQuery->num_rows() > 0)
        {
            foreach ($sqlQuery->result() as $key=>$row)
            {
                if(intval($row->vTransMode) != 0):

                    $profit = ($row->salesAmt-$row->netAmt);

                    if($row->netAmt > 0)
                        $profitPercent = round(($profit/$row->netAmt*100),2);
                    else
                        $profitPercent = 0;

                    $name = $row->vType;
                    $data[$name] = $profitPercent;
                endif;
            }//end for each
        }//end if

		@array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Sales Summary Report');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();

        /*$graph->setBackgroundColor("black");
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Sales Summary Report');
        $graph->setTitleColor('yellow');
        $graph->setupYAxis(12, 'yellow');
        $graph->setupXAxis(20, 'yellow');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('white');
        $graph->setTextColor('white');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('yellow');
        $graph->setLine(true);
        $graph->setLineColor('yellow');
        $graph->createGraph();*/
    }//end function
	
	//function to create airline bar graph
	function airlineBarGraph()
	{
		$graph = new PHPGraphLib(350,280);
		$data  = array();
		session_start();
		
		$sqlVal = $_SESSION['sess_query'];
		
		//create gds summery array
		$sqlQuery = $this->db->query($sqlVal);
		
		if ($sqlQuery->num_rows() > 0)
		{
			foreach ($sqlQuery->result() as $key=>$row)
			{
				if(intval($row->nAirline) != 0):
					
					$profit = ($row->salesAmt-$row->netAmt);
					
					if($row->netAmt > 0)
						$profitPercent = round(($profit/$row->netAmt*100),2);
					else
						$profitPercent = 0;
					
					$name             = $this->common_model->get_field_name('Airline','vAlpha'," WHERE 
												vAirlineNo = '".$row->nAirline."'");
					$data[$name] = $profitPercent;
				endif;
			}//end for each
		}//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Airline Summary Report');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
	}//end function
	
	//function to create airline bar graph
	function hotelBarGraph()
	{
		$graph = new PHPGraphLib(350,280);
		$data  = array();
		session_start();
		
		$sqlVal = $_SESSION['sess_query'];
		
		//create gds summery array
		$sqlQuery = $this->db->query($sqlVal);
		
		if ($sqlQuery->num_rows() > 0)
		{
			foreach ($sqlQuery->result() as $key=>$row)
			{
				if(intval($row->vTktNumber) != 0):
					
					$profit = ($row->salesAmt-$row->netAmt);
					
					if($row->netAmt > 0)
						$profitPercent = round(($profit/$row->netAmt*100),2);
					else
						$profitPercent = 0;
					
					$name             = $this->common_model->get_field_name('Hotel','vName',
													" WHERE nHId = '".$row->vTktNumber."'");
					$data[$name] = $profitPercent;
				endif;
			}//end for each
		}//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Hotel Summary Report');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
	}//end function
	
	//function to create location bar graph
	function locBarGraph()
	{
		$graph = new PHPGraphLib(350,280);
		$data  = array();
		session_start();
		
		$sqlVal = $_SESSION['sess_query'];
		
		//create gds summery array
		$sqlQuery = $this->db->query($sqlVal);
		
		if ($sqlQuery->num_rows() > 0)
		{
			foreach ($sqlQuery->result() as $key=>$row)
			{
				if(intval($row->vLocation) != 0):

                    $salesAmt = $this->manage_info_model->getLocationSalesTotal($row->vLocation);
                    $netAmt = $this->manage_info_model->getLocationNetTotal($row->vLocation);

					$profit = ($salesAmt-$netAmt);
					
					if($netAmt > 0)
						$profitPercent = round(($profit/$netAmt*100),2);
					else
						$profitPercent = 0;
					
					$name             = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$row->vLocation."'");
					$data[$name] = $profitPercent;
				endif;
			}//end for each
		}//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Location Summary Report');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
	}//end function
	
	//function to create employee bar graph
	function empBarGraph()
	{
		$graph = new PHPGraphLib(350,280);
		$data  = array();
		session_start();
		
		$sqlVal = $_SESSION['sess_query'];

		//create gds summery array
		$sqlQuery = $this->db->query($sqlVal);
		
		if ($sqlQuery->num_rows() > 0)
		{
			foreach ($sqlQuery->result() as $key=>$row)
			{
				if(trim($row->vEmployee) != ''):
                    $salesAmt   = $this->manage_info_model->getEmployeeSalesTotal($row->vEmployee);
                    $netAmt     = $this->manage_info_model->getEmployeeNetTotal($row->vEmployee);

					$profit = ($salesAmt-$netAmt);
					
					if($netAmt > 0)
						$profitPercent = round(($profit/$netAmt*100),2);
					else
						$profitPercent = 0;
					
					$data[$row->vEmployee] = $profitPercent;
				endif;
			}//end for each
		}//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Profit By Employee');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
	}//end function
	
	//function to create customer bar graph
	function cusBarGraph()
    {
        $graph = new PHPGraphLib(350,280);
        $data  = array();
        session_start();

        $sqlVal = $_SESSION['sess_query'];

        //create gds summery array
        $sqlQuery = $this->db->query($sqlVal);

        if ($sqlQuery->num_rows() > 0)
        {
            foreach ($sqlQuery->result() as $key=>$row)
            {
                if(intval($row->nDrAccount) != 0):

                    $profit = ($row->salesAmt-$row->netAmt);

                    if($row->netAmt > 0)
                        $profitPercent = round(($profit/$row->netAmt*100),2);
                    else
                        $profitPercent = 0;

                    $name = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row->nDrAccount."'");
                    $data[$name] = $profitPercent;
                endif;
            }//end for each
        }//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Profit By Customer');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
    }//end function

    //function to create vendor bar graph
	function ventBarGraph()
    {
        $graph = new PHPGraphLib(350,280);
        $data  = array();
        session_start();

        $sqlVal = $_SESSION['sess_query'];

        //create gds summery array
        $sqlQuery = $this->db->query($sqlVal);

        if ($sqlQuery->num_rows() > 0)
        {
            foreach ($sqlQuery->result() as $key=>$row)
            {
                if(intval($row->nDrAccount) != 0):

                    $profit = ($row->salesAmt-$row->netAmt);

                    if($row->netAmt > 0)
                        $profitPercent = round(($profit/$row->netAmt*100),2);
                    else
                        $profitPercent = 0;

                    $name = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row->nDrAccount."'");
                    $data[$name] = $profitPercent;
                endif;
            }//end for each
        }//end if

        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle('Profit By Vendor');
        $graph->setTitleColor('black');
        $graph->setupYAxis(12, 'black');
        $graph->setupXAxis(20, 'black');
        $graph->setGrid(false);
        $graph->setGradient('silver', 'gray');
        $graph->setBarOutlineColor('black');
        $graph->setTextColor('black');
        $graph->setDataPoints(true);
        $graph->setDataPointColor('black');
        $graph->setLine(true);
        $graph->setLineColor('black');
        $graph->createGraph();
    }//end function

}//end class
?>