<?php
require_once 'static/classes/phpgraphlib.php';
require_once 'static/classes/phpgraphlib_pie.php';

class Graph_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


	function drawBarGraph()
    {
        $graph = new PHPGraphLib(350,280);
        $data  = array();
        session_start();

        $data = $_SESSION['graph_data'];        
	$title = $_SESSION['graph_title'];



        @array_multisort($data,SORT_DESC);

        $graph->setBackgroundColor('245,245,245');
        $graph->addData($data);
        $graph->setBarColor('255,255,204');
        $graph->setTitle($title);
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
