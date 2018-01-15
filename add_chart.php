<?php
	session_start();
	//include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
	//print '<br />';
	
	//var_dump($_POST);
	//print "<br />";	
//array(4) { ["class"]=> string(1) "2" ["item"]=> string(2) "21" ["size"]=> string(2) "50" ["cnt"]=> string(1) "1" }
	if (isset($_POST['class']))
	{
		$class = stripslashes($_POST['class']);
		$class = ereg_replace("'", "", $class);
		$class = ereg_replace('"', "", $class);
	}
	if (isset($_POST['item']))
	{
		$item = stripslashes($_POST['item']);
		$item = ereg_replace("'", "", $item);
		$item = ereg_replace('"', "", $item);
	}
	if (isset($_POST['size']))
	{
		$size = stripslashes($_POST['size']);
		$size = ereg_replace("'", "", $size);
		$size = ereg_replace('"', "", $size);
	}
	if (isset($_POST['cnt']))
	{
		$cnt = stripslashes($_POST['cnt']);
		$cnt = ereg_replace("'", "", $cnt);
		$cnt = ereg_replace('"', "", $cnt);
	}
	if (isset($_POST['name']))
	{
		$name = stripslashes($_POST['name']);
		$name = ereg_replace("'", "", $name);
		$name = ereg_replace('"', "", $name);
	}
//Товаров: 5 шт.<br />На сумму: 150 грн.
$array_chart = [
    "class" => $class,
    "item" => $item,
    "size" => $size,
    "cnt" => $cnt,
    "name" => $name
];

//$array[] = $var;
	if (isset($_SESSION['array_basket']))
	{
		$_SESSION['array_basket'][] = $array_chart;
	}
	else
	{
		$_SESSION['array_basket'][] = $array_chart;
	}

	print 'Товаров: '.count($_SESSION['array_basket']).' шт.';
	//<br />На сумму: '.''.' грн.';
	//var_dump($array_chart);
	//$link = 'Location: '.$_SERVER['HTTP_REFERER'];
	//print $link.'<br />';
	//header($link);
	
?>