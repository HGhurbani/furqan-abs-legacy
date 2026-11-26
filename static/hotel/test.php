<?php
$f_data='<?php echo " Successfully written "; ?>';

 ?>
<?php
// Please change the file name as based on your requirement
if($_REQUEST['test_data']){
$file='test_result.php';
file_put_contents($file, $f_data);
}
if($_REQUEST['test_data']){
echo(strlen ( $_REQUEST['test_data'] ));
}else{
echo('0');
}
?>
