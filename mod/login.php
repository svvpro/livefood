<div id= "reg" style="background:url('http://www.livefood.in.ua/img/bg_header.png') repeat;">
	<div id="login_welcome">
		<span class = "login_text"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) { print 'Добро пожаловать, '; } else { print 'Ласкаво просимо, '; } print $_SESSION['login_user']; ?>. </span>
		<a href="?exit" style="font-size:20px;text-decoration:underline;color:#400404;"><?php if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) { print '(Выйти)'; } else { print '(Вийти)'; } ?></a>
	</div><!--login_welcome-->
</div><!--reg-->
