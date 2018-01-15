<?php
	include_once '../conf.php';

	//var_dump($_POST);
	//print "<br />";
			
	if (isset($_POST['username']) and isset($_POST['password']) and isset($_POST['password2']) and isset($_POST['email']) and isset($_POST['tlf']) and ($_POST['password'] == $_POST['password2']) )
	{
		//print "Yes <br />";
		$auth = new auth($db); //~ Создаем новый объект класса
		$ret = $auth->reg($_POST['username'], $_POST['password'], $_POST['email'], $_POST['tlf']);
		//print "dd = " . $dd . "<br />";
		if ($ret == 1 )
    {
			//print '<h2>Регистрация успешна.</h2>';
			$msg_error = "";
    }
    else
    {
    	//print '<h2>Регистрация не успешна.</h2>';
    	$msg_error = $ret;
    }
    unset ($auth);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			print "<title>Регистрация пользователя</title>";
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
		?>
	<script language="JavaScript" src="../js/reg.js" type="text/javascript"></script>
</head>
<body>

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
			
			if ( $msg_error != "" )
			{
				print "<div id= \"error\">";
				print "<span id=\"msg_error\">".$msg_error."</span>";
				print "</div><!--error-->";
			}


		?>

		<div id= "content">
		<?php
/*
			var_dump($_POST);
			print "<br />";
			//var_dump($_SERVER);
			var_dump ($_COOKIE);
			print "<br />";
			var_dump ($_SESSION);
			print "<br />";
*/

			if (!isset($_SESSION['login_user']))
			{
		?>

			<h2 id= "name_table"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Для регистрации вы должны заполнить следующие поля:';} else {print 'Для реєстрації ви повинні заповнити наступні поля:';}?></h2><br />	
				<div id= "error">
					<span id="msg_error"><?php print $msg_error;?></span>
				</div><!--error-->
			<form id= "reg_form" name= "reg_form" action = "" method = "post" onSubmit= "if (!formfre(0)) { print 'error<br />'; return false; }">
				<div id= "login">
				  <?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Имя пользователя:';} else {print 'Им\'я користувача:';}?>
				  <input type="text" name="username" id="text_login" size="15" maxlength="15" value="<?php if (isset($_POST['username']) and $msg_error != "") print $_POST['username'];?>" />
				</div><!--login-->
				<span style="position: relative;left: 200px;margin-top: 0px;"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Не менее 4 и не более 15 символов';} else {print 'Не менше 4 та не більше 15 символів';}?></span>
				<div id= "password">
					Пароль: <input type="password" name="password" id="text_password" size="15" value="<?php if (isset($_POST['password']) and $msg_error != "") print $_POST['password'];?>" />
				</div><!--password-->
				<span style="position: relative;left: 200px;margin-top: 0px;"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Не менее 4 и не более 15 символов';} else {print 'Не менше 4 та не більше 15 символів';}?></span>
				<div id= "password2">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Повторите пароль:';} else {print 'Повторіть пароль:';}?>
					<input type="password" name="password2" id="text_password2" size="15" value="<?php if (isset($_POST['password']) and $msg_error != "") print $_POST['password2'];?>" />
				</div><!--password-->
				<div id= "reg_email">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Адрес эл. почты:';} else {print 'Електронна адреса:';}?>
					<input type="text" name="email" id="text_email" size="25" value="<?php if (isset($_POST['email']) and $msg_error != "") print $_POST['email'];?>" />
				</div><!--reg_email-->
				<div id= "reg_tlf">
					<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Контактный телефон:';} else {print 'Контактний телефон:';}?>
				  <input size="10" type="numeric" name="tlf" id="text_tlf" maxlength= "15" placeholder= "(050) 123-45-67" onkeyup="validtlf(this)" value="<?php if (isset($_POST['tlf']) and $msg_error != "") print $_POST['tlf'];?>" />
				</div><!--reg_tlf-->
				<span style="position: relative;left: 200px;margin-top: 0px;"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'Пример: ';} else {print 'На приклад: ';}?>(050) 123-45-67</span>
				<div>
					<input id= "input_reg1" type= "submit" name= "input_send" value= "" style="position: relative;left: 235px;margin-top: 15px;background:url('http://www.livefood.in.ua/img/enter_email_<?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'ru';} else {print 'ua';}?>.png') no-repeat 0 0;"/>
				</div>
			</form><!--form_reg-->
			<?php
		}
		else
		{
			print "<div id= \"error\">";
			if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
			{
				print '<span id=\"msg_error\">Поздравляем, вы успешно зарегистрированы!</span>';
			}
			else
			{
				print '<span id=\"msg_error\">Наші вітання, вас успішно зареєстровано!</span>';
			}
			print "</div><!--error-->";
		}
			?>

		</div><!--content-->
		</div><!--right-->
		</div><!--all_center-->
			<?php
				require_once $_SERVER['DOCUMENT_ROOT'].'/mod/footer.html';
			?>

	</div><!--wrap-->

</body>
</html>