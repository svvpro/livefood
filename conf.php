<?php
	$debug = 0;
	//~ Старт сессии, файл должен быть сохранен без DOM информации
	session_start();
	//ini_set('display_errors',false);
	//error_reporting(E_ALL);
	ini_set('display_errors',true);
	//var_dump ($_COOKIE);
	//print $_COOKIE['id_user']."<br />";
	//print $_COOKIE['code_user']."<br />";
	
	//date_default_timezone_set('Europe/Kiev');
	 
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/module.php';
	// подключаемся к бд
	include_once $_SERVER['DOCUMENT_ROOT'].'/safemysql.class.php';
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/gd_resize.php';
	
	$db = new SafeMysql();

	$auth = new auth($db); //~ Создаем новый объект класса
	if ($debug == 1)
	{
		if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
		{
			echo "Не могу открыть файл debug.txt";
      exit;
    }
    
    $today = date("Y-m-d H:i:s");
    fwrite($handle, "\n\n".$today."\t");
		fwrite($handle, "REQUEST_URI: ".$_SERVER['REQUEST_URI']);
		fwrite($handle, "\n");

		fwrite($handle, "HTTP_REFERER: ");
		if (isset($_SERVER['HTTP_REFERER'])) fwrite($handle, $_SERVER['HTTP_REFERER']);
		fwrite($handle, "\n");

		fwrite($handle, "_SESSION: ".var_export($_SESSION, true));
		fwrite($handle, "\n");
		
		fwrite($handle, "_COOKIE: ".var_export($_COOKIE, true));
		fwrite($handle, "\n");
		
		fwrite($handle, "lang_ref: ".$auth->lang_ref);
		fwrite($handle, "\n");
		
		fwrite($handle, "lang: ".$auth->lang);
		fwrite($handle, "\n");
		
		fclose($handle);
		unset($handle);
	}
	//var_dump($_POST);
	//print "<br />";	
	//var_dump($_GET);
	//print "<br />";	
	//var_dump($_SESSION);
	//print "<br />";
	//var_dump($_COOKIE);
	//print "<br />";
	//print 'HTTP_REFERER: '.$_SERVER['HTTP_REFERER'];
	//print "<br />";
	//print 'REQUEST_URI: '.$_SERVER['REQUEST_URI'];
	//print "<br />";
	//print "<br />";
	//print "<br />";

	if (isset($_GET['exit']))
	{
		$auth->exit_user();
		//print 'exit_user';
	}
	else
	{
	//print 'auth->check';
	//~ Проверка авторизации
		$auth->check();
	}

	//~ Авторизация
	$msg_error = "";
	if (isset($_POST['key_auth']))
	{
		$ret = $auth->authorization();
		if ($ret != 1 )
    {
    	$msg_error = $ret;
    	if ( isset($_SESSION['error']) )
    	{
	      $error = $_SESSION['error'];
	      unset ($_SESSION['error']);
	    }
    }
	}
	
	unset ($auth);
	//print $_SERVER['SCRIPT_FILENAME'];
	//_SERVER["SCRIPT_FILENAME"]

	require_once ($_SERVER['DOCUMENT_ROOT'].'/stat.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/multilanguage.php');

?>