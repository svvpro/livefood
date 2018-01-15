<?php
	include_once 'conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<title>Главная</title>
		<?php
			require_once "mod/meta_link_haki.html";
		?>
</head>
<body>

	<div id="wrap">

		<?php
			require_once "mod/header.html";
			print "<div id=\"all_center\"><div id=\"left\">";
			if (!isset($_SESSION['login_user'])) require_once "mod/reg.html";
			else require_once "mod/login.php";

			
			require_once "mod/left_nav.php";
			print "</div>";	//<!--left-->
			print "<div id=\"right\">";
			require_once "mod/nav.html";
			
			if ( $msg_error != "" )
			{
				print "<div id= \"error\">";
				print "<span id=\"msg_error\">".$msg_error."</span>";
				print "</div><!--error-->";
			}
		?>

		<div id = "content">

			<div id = "error_text">
				<p>На этой странице ведутся ремонтные работы.</p> <a href = "/">Перейти на главную страницу</a>
			</div><!--error_text-->

			<div id = "error_img">
				<img src = "/img/profilaktika.jpg" alt = "картинка профилактики"/>
			</div>


		</div><!--content-->
		</div><!--right-->
		</div><!--all_center-->
			
			<?php
				require_once "mod/footer.html";
			?>

	</div><!--wrap-->

</body>
</html>