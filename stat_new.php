<?php
	//~ Старт сессии, файл должен быть сохранен без DOM информации
	session_start();
//var_dump($_SERVER);
//var_dump($_SESSION);
//var_dump($_POST);


include_once $_SERVER['DOCUMENT_ROOT'].'/safemysql.class.php';
$db = new SafeMysql();

if ( isset($_POST['Width']) )
{
	$width = stripslashes($_POST['Width']);
	$width = ereg_replace("'", "", $width);
	$width = ereg_replace('"', "", $width);
}
else
{
	$width = "";
}

if ( isset($_POST['Height']) )
{
	$height = stripslashes($_POST['Height']);
	$height = ereg_replace("'", "", $height);
	$height = ereg_replace('"', "", $height);
}
else
{
	$height = "";
}

$_SESSION['width'] = $width;
$_SESSION['height'] = $height;

$addr = $_SERVER['REMOTE_ADDR'];
if (isset ($_SESSION['id_user']))
{
	$user_id = stripslashes($_SESSION['id_user']);
}
else
{
	$user_id=NULL;
}

//var_dump($_SESSION);

if ( $addr != '127.0.0.1' )
{
	$ret = $db->query("INSERT INTO visiting_day (ip_addr,user_id,request,referer,user_agent,width,height,module) VALUES (?s,?i,?s,?s,?s,?i,?i,?s)",$_SESSION['addr'],$user_id,$_SESSION['request'],$_SESSION['reffer'],$_SESSION['agent'],$_SESSION['width'],$_SESSION['height'],'stat_new');

	if ($ret != 1)
	{
		$msg_error = $ret;
		//print $msg_error;
	}
}

?>

