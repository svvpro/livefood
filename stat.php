<?php
//var_dump($_SERVER);
//var_dump($_SESSION);
//var_dump($_POST);

$addr = $_SERVER['REMOTE_ADDR'];

//$request = stripslashes($_SERVER['REQUEST_URI']);
//$request = preg_replace("'", "", $request);
//$request = preg_replace('"', "", $request);
//
//if ( isset($_SERVER['HTTP_REFERER']) )
//{
//	$reffer = stripslashes($_SERVER['HTTP_REFERER']);
//	$reffer = preg_replace("'", "", $reffer);
//	$reffer = preg_replace('"', "", $reffer);
//}
//else
//{
//	$reffer = "";
//}
//
//
//$metod = stripslashes($_SERVER['REQUEST_METHOD']);
//$metod = preg_replace("'", "", $metod);
//$metod = preg_replace('"', "", $metod);
//
//$agent = stripslashes($_SERVER['HTTP_USER_AGENT']);
//$agent = preg_replace("'", "", $agent);
//$agent = preg_replace('"', "", $agent);

$_SESSION['addr'] = $addr;
$_SESSION['request'] = $request;
$_SESSION['reffer'] = $reffer;
$_SESSION['agent'] = $agent;

if (isset ($_SESSION['id_user']))
{
	$user_id = stripslashes($_SESSION['id_user']);
}
else
{
	$user_id=NULL;
}


if ( $addr != '127.0.0.1' )
{
	/*
	if ( strlen($request) > 255 )
	{
		$request = substr( $request, 0, 255);
	}

	if ( strlen($reffer) > 255 )
	{
		$reffer = substr( $reffer, 0, 255);
	}
	*/

/*
	if ($metod == "POST")
	{
		$cur_from = stripslashes($_POST['cur_from']);
		$cur_from = ereg_replace("'", "", $cur_from);
		$cur_from = ereg_replace('"', "", $cur_from);
		$cur_to = stripslashes($_POST['cur_to']);
		$cur_to = ereg_replace("'", "", $cur_to);
		$cur_to = ereg_replace('"', "", $cur_to);
		
		$request .= "cur_from=".$cur_from."; cur_to=".$cur_to;
	}
*/

	//$request = iconv("CP1251", "UTF-8", $request);
	//$reffer = iconv("CP1251", "UTF-8", $reffer);
	
	//$sql="INSERT INTO visiting (ip_addr,user_id,request,referer,user_agent) VALUES ('$addr','$user_id','$request','$reffer','$agent')";
	//print $sql.'<br />';
	$ret = $db->query("INSERT INTO visiting_day (ip_addr,user_id,request,referer,user_agent,module) VALUES (?s,?i,?s,?s,?s,?s)",$addr,$user_id,$request,$reffer,$agent,'stat');

	if ($ret != 1)
	{
		$msg_error = $ret;
		//print $msg_error;
	}
}

?>

