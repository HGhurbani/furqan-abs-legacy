<?php
require_once '/home/abstrvlkwit/public_html/demo/static/classes/phpgraphlib.php';
require_once '/home/abstrvlkwit/public_html/demo/static/classes/phpgraphlib_pie.php';
class Graph_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }



	function showBarGraph()
    {
        $graph = new PHPGraphLib(350,280);

/*

//$data = array("Alex"=>99, "Mary"=>98, "Joan"=>70, "Ed"=>90);
$graph->addData(array(100,12,43,342,9));
$graph->setTitle("Test Scores");
$graph->setTextColor("blue");

$graph->createGraph();

*/

        session_start();
	$data = $_SESSION['g_d'];
	$title = $_SESSION['title'];

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