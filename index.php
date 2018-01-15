<?php
/*
	$refer = $_SERVER['QUERY_STRING'];
	if ($refer != '') $refer = '?'.$refer;
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://www.livefood.in.ua/ru/index.php'.$refer);
*/
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: http://www.livefood.in.ua/ru/index.php');
	//exit();
?>