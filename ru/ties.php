<?php
//var_dump($_SESSION);
	include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			//<title>Контакты</title>
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
		?>
		<script src= "http://www.livefood.in.ua/js/reg.js" type= "text/javascript"></script>

  <script type='text/javascript'>
   function loadPage() {
    var w=window.innerWidth;
    var h=window.innerHeight;

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
			 }
			 else if(request.status==404)
			 {
						//alert("Ошибка: запрашиваемый скрипт не найден!");
			 }
			 else
			 {
			 		//alert("Ошибка: сервер вернул статус: "+ request.status);
			 }
		   
			 break;
			}
		}		
  }

	request.open("POST","http://www.livefood.in.ua/stat_new.php",true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("Width=" + w + "&Height=" + h);
   }
  </script>

</head>
<body onload="loadPage()">

	<div id="wrap">

		<?php
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/header.html';
			print "<div id=\"all_center\"><div id=\"left\">";
			if (!isset($_SESSION['login_user'])) require_once $_SERVER['DOCUMENT_ROOT'].'/mod/reg.html';
			else require_once $_SERVER['DOCUMENT_ROOT'].'/mod/login.php';

			
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/left_nav.php';
			print "</div>";	//<!--left-->
			print "<div id=\"right\">";
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/nav.html';
			
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
		<div id = "content">
			<?php
				//var_dump($_POST);
				//print "<br />";
				//var_dump($_SERVER);
				//print "<br />";
				//var_dump($_REQUEST);
				//print "<br />";
				?>

			<div id= "telefon">

				<div id="image_telefon"><img id ="img_telefon" src = "../img/telefon_image.png" title = "позвоните нам" alt = "картинка телефона"/></div><!--image_telefon-->

				<div id="nomer">
					<div id= "top_nomer"><p>+38/099/524-41-02</p></div><!--top_nomer-->
					<div id= "bottom_nomer"><p>+38/067/306-58-02</p></div><!--bottom_nomer-->
				</div><!--nomer-->

			</div><!--telefon-->

			<div id = "letter">
				<div id ="image_letter"><img id ="img_letter" src = "../img/letter.png" title = "напиши нам" alt = "картинка письма"/></div><!--image_letter-->
				<div id = "email"><p>&#105;&#110;&#102;&#111;&#64;&#108;&#105;&#118;&#101;&#102;&#111;&#111;&#100;&#46;&#105;&#110;&#46;&#117;&#97;</p></div><!--email-->
			</div><!--letter-->

		<div id="text_block_reg">
			<h1 id= "text_header_block"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Форма обратной связи';} else {print 'Форма зворотнього зв\'язку';}?><br /></h1>
			<p id= "text_header_block1"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Пожалуйста, заполните приведенные ниже поля, а затем нажмите ОТПРАВИТЬ.';} else {print 'Будь ласка, заповніть поля нижче, а потім натисніть ВІДПРАВИТИ';}?></p>
		</div>

			<form id= "mail_form" name= "mail_form" action = "" method = "post" >
				<div id= "mail_login">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Введите ваше имя:  ';} else {print 'Введіть ваше ім\'я:';}?>
					<input type="text" name="username" id="text_mail_login" size="15" maxlength="15" value="" />
				</div><!--mail_login-->
				<div id= "mail_email">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Адрес эл. почты:   ';} else {print 'Електронна адреса: ';}?>
					<input type="text" name="email" id="text_mail_email" size="25" value="" />
				</div><!--mail_email-->
				<div id= "mail_tlf">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Контактный телефон: ';} else {print 'Контактний телефон: ';}?>
					<input size="10" type="text" name="tlf" id="text_mail_tlf" maxlength= "15" onkeyup="validtlf(this)" value="<?php if (isset($_POST['tlf']) and $msg_error != "") print $_POST['tlf'];?>" />
				</div><!--mail_tlf-->
				<span style= "position: relative;left: 235px;top: 1px;margin-left: 80px;"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Пример: (050) 123-45-67';} else {print 'На приклад: (050) 123-45-67';}?></span>
				<div id= "mail_message">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Ваше сообщение: ';} else {print 'Ваше повідомлення: ';}?>
					<textarea name="text_mail_message" cols="61" rows="10" id="text_mail_message"></textarea>
				</div><!--mail_message-->
				<div id= "mail_verify">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Введите код: ';} else {print 'Введіть код: ';}
					
$let_amount = 4;			//Количество символов, которые нужно набрать
//набор символов
$letters = array("0","1","2","3","4","5","6","7","8","9");
$letter = "";
for($i=0;$i < $let_amount;$i++)
{
	//случайный символ
	$letter = $letter.$letters[rand(0,sizeof($letters)-1)];
}
//print $letter.'<br />';
$letter = $letter ^ "qwer";
$letter = base64_encode($letter);
print '<img src="http://www.livefood.in.ua/test.php?code='.$letter.'" alt="Защитный код" />';
?>
&nbsp;&nbsp;&nbsp;<input type="text" name="input_code" id="text_mail_verify" size="10" maxlength="4" value="" /><input type="hidden" name="antirobot_code" value="<?php print $letter; ?>" />

				</div><!--mail_tlf-->

				<div>
					<input id= "input_reg1" type= "submit" name= "input_send" value= "" style="position: relative;left: 305px;margin-top: 15px;background:url('http://www.livefood.in.ua/img/enter_email_<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'ru';} else {print 'ua';}?>.png') no-repeat 0 0;"/>
				</div>
			</form><!--mail_reg-->

		</div><!--content-->

		</div><!--right-->
		</div><!--all_center-->

			
			<?php
				require_once $_SERVER['DOCUMENT_ROOT'].'/mod/footer.html';
			?>

	</div><!--wrap-->

</body>
</html>