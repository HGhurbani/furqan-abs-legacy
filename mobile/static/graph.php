<?php require_once '/home/jalilgroup/public_html/abs/static/classes/phpgraphlib.php';
require_once '/home/jalilgroup/public_html/abs/static/classes/phpgraphlib_pie.php';
$graph = new PHPGraphLib(350,280);
 session_start();
$data = $_SESSION['g_d'];
	$title = $_SESSION['title'];
		//$data = array("Alex"=>99, "Mary"=>98, "Joan"=>70, "Ed"=>90);
//$graph->addData(array(100,12,43,342,9));
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
        $graph->createGraph(); ?>