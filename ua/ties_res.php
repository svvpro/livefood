<?php
	//var_dump($_SESSION);
	include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			//require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
    	print "<title>Резиновые контакты</title>";
    	print '<meta name="description" content="Купить живой корм по низким ценам: сверчок, зофобас, львинка. Купить. Киев." />';
    	print '<meta name="keywords" content="живой корм, ящерицы, змеи, птицы, зофобас, сверчок, хамелеон, львинка, купить, Киев" />';

		?>

<meta http-equiv="Content-Type" content= "text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<link href="http://livefood.in.ua/style/responsive.css" rel="stylesheet" type="text/css"  media="screen"/>

<link href="http://livefood.in.ua/favicon.png" rel="shortcut icon" type="image/x-icon" />
<link rel="alternate" hreflang="ru" href="http://www.livefood.in.ua/ru/<?php print substr($_SERVER['REQUEST_URI'], 4);?>" />
<link rel="alternate" hreflang="uk" href="http://www.livefood.in.ua/ua/<?php print substr($_SERVER['REQUEST_URI'], 4);?>" />

	<script src="http://www.livefood.in.ua/js/reg.js" type="text/javascript"></script>
  <script type='text/javascript'>
   function loadPage() {
    var w=window.innerWidth;
    var h=window.innerHeight;

	//console.log("Размеры экрана: ", w, h);

  var request;
  if(window.XMLHttpRequest)
  { 
      request = new XMLHttpRequest(); 
  }
  else if(window.ActiveXObject)
  { 
      request = new ActiveXObject("Microsoft.XMLHTTP");  
  }
  else
  { 
      return; 
  } 
  
  request.onreadystatechange = function()
  {
		switch (request.readyState)
		{
		  case 1: break;
		  case 2: break;
		  case 3: break;
		  case 4:
		  {
		   if(request.status==200)
		   {
					//document.getElementById("login_welcome").innerHTML = request.responseText;
					//alert(request.responseText);
					console.log(request.responseText);
			 }
			 else if(request.status==404)
			 {
						alert("Ошибка: запрашиваемый скрипт не найден!");
			 }
			 else alert("Ошибка: сервер вернул статус: "+ request.status);
		   
			 break;
			}
		}		
  }

	request.open("POST","http://www.livefood.in.ua/stat_new.php",true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	//console.log("Send");
	request.send("Width=" + w + "&Height=" + h);
   }
  </script>
</head>
<body onload="loadPage()">

	<div id="wrap">

		<?php
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/header_res.html';
			print "<div id=\"all_center\"><div id=\"left\">";
			if (!isset($_SESSION['login_user'])) require_once $_SERVER['DOCUMENT_ROOT'].'/mod/reg.html';
			else require_once $_SERVER['DOCUMENT_ROOT'].'/mod/login.php';

			
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/left_nav.php';
			print "</div>";	//<!--left-->
			print "<div id=\"right\">";
			//require_once $_SERVER['DOCUMENT_ROOT'].'/mod/nav.html';
			//var_dump($_SESSION);
			
			function get_data($smtp_conn)
			{
				$data="";
				while($str = fgets($smtp_conn,515)) 
				{
					$data .= $str;
					if(substr($str,3,1) == " ")
					{
						break;
					}
				}
				return $data;
			}
			
			if (isset($_POST['input_send']) and isset($_POST['input_code']) )
			{
				$code = $_POST['antirobot_code'];
				$code = base64_decode($code);
				$code = $code ^ "qwer";
				if ( $code == $_POST['input_code'] )
				{
					$tlf = substr('38'.str_replace(array("(", ")", " ", "-"), "", $_POST['tlf']),0,13); // номер телефона
					
					if ( isset($_SESSION['id_user']) )
					{
						$id_user = $_SESSION['id_user'];
						//print "1";
					}
					else
					{
						$id_user = Null;
						//print "2";
					}
					
				
					$ret = $db->query("INSERT INTO ?n (id_user, username, email, tlf, text_message, record_dt) VALUES (?i,?s,?s,?s,?s,now())",'message',$id_user,$_POST['username'],$_POST['email'],$tlf,$_POST['text_mail_message']);
					if ($ret != 1)
					{
						$msg_error = $ret;
					}
					else
					{//Отправим письмо

 
	//$smtp_conn = fsockopen("ssl://64.233.163.109", 465,$errno, $errstr, 10);
	$smtp_conn = fsockopen("localhost", 25,$errno, $errstr, 10);
	if(!$smtp_conn)
	{
		//print "соединение с серверов не прошло";
		fclose($smtp_conn);
		exit;
	}
	$data = get_data($smtp_conn);
	fputs($smtp_conn,"EHLO info\r\n");
	$code = substr(get_data($smtp_conn),0,3);
	if($code != 250)
	{
		//print "ошибка приветсвия EHLO";
		fclose($smtp_conn);
		exit;
	}

	fputs($smtp_conn,"AUTH LOGIN\r\n");
	$code = substr(get_data($smtp_conn),0,3);
	if($code != 334)
	{
	 	//print "сервер не разрешил начать авторизацию";
	 	fclose($smtp_conn);
	 	exit;
	}
	else
	{
		//print $data."<br />";
	}	
	fputs($smtp_conn,base64_encode("info@livefood.in.ua")."\n");
	$code = substr(get_data($smtp_conn),0,3);
	if($code != 334)
	{
		//print "ошибка доступа к такому юзеру";
		fclose($smtp_conn);
		exit;
	}
	else
	{
		//print $data."<br />";
	}
	
	fputs($smtp_conn,base64_encode("Dq4Cfg0Ofn3OFeq")."\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 235)
	{
	 	//print "не правильный пароль<br />";
	 	//print $data."<br />";
	 	fclose($smtp_conn);
	 	exit;
	}
	else
	{
	 		//print $data."<br />";
	}
	$header = "Subject: Новое сообщение с сайта livefood.in.ua";
	$text = "Получено новое сообщение\n";
	$text .= $_POST['text_mail_message']."\n";
	$text .= $_POST['username']."\n";
	$text .= $tlf."\n";
	$text .= $_POST['email']."\n";
	
	$size_msg=strlen($header."n".$text);
	
	fputs($smtp_conn,"mail from:<info@livefood.in.ua>\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 250)
	{
	 	//print "сервер отказал в команде MAIL FROM";
	 	fclose($smtp_conn);
	 	exit;
	}
	else
	{
	 	//print $data."<br />";
	}
	
	fputs($smtp_conn,"rcpt to: my.ovm1@gmail.com\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 250 AND $code != 251)
	{
		//print "Сервер не принял команду RCPT TO";
		fclose($smtp_conn);
		exit;
	}
	else
	{
	 	//print $data."<br />";
	}

	fputs($smtp_conn,"rcpt to: tonithka@gmail.com\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 250 AND $code != 251)
	{
		//print "Сервер не принял команду RCPT TO";
		fclose($smtp_conn);
		exit;
	}
	else
	{
	 	//print $data."<br />";
	}

	fputs($smtp_conn,"DATA\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 354)
	{
		//print "сервер не принял DATA";
		fclose($smtp_conn);
		exit;
	}
	else
	{
	 	//print $data."<br />";
	}
	 	
	//fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
	fputs($smtp_conn,"From: <info@livefood.in.ua>\nTo: Виталий Онищенко<my.ovm1@gmail.com>, Антоніна Кононець<tonithka@gmail.com>\n".$header."\n".$text."\n.\n");
	$data = get_data($smtp_conn);
	$code = substr($data,0,3);
	if($code != 250)
	{
		print "ошибка отправки письма";
		fclose($smtp_conn);
		exit;
	}
	else
	{
	 	//print $data."<br />";
	 	//print "Письмо отправлено.<br />";
	}
	
	fputs($smtp_conn,"QUIT\n");
	fclose($smtp_conn);
					}
				}
				else
				{
					if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {$msg_error = 'Сообщение не отправлено.<br />Проверочный код введен не правильно!';} else {$msg_error = 'Повідомлення не відправлено.<br />Провірочный код введено не правильно!';}
					
				}
			}
		
			if ( $msg_error != "" )
			{
				print "<div id= \"error\">";
				print "<span id=\"msg_error\">".$msg_error."</span>";
				print "</div><!--error-->";
			}
		?>


		</div><!--right-->
		</div><!--all_center-->

			
			<?php
				//require_once $_SERVER['DOCUMENT_ROOT'].'/mod/footer.html';
			?>

	</div><!--wrap-->

</body>
</html>