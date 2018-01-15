<?php include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<?php
		//<title>Живой корм для рептилий и птиц. Сверчок, зофобас.</title>
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
			if ( isset($_SESSION['status_user']) and $_SESSION['status_user'] == '10' )
			{
				print '<script src="http://www.livefood.in.ua/ckeditor.js"></script>';
			}
		?>
</head>
<body>
	<div id="wrap">
		<?php
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/header.html';
			print '<div id="all_center"><div id="left">';
			if (!isset($_SESSION['login_user'])) require_once $_SERVER['DOCUMENT_ROOT'].'/mod/reg.html';
			else require_once $_SERVER['DOCUMENT_ROOT'].'/mod/login.php';

			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/left_nav.php';
			print '</div>';	//<!--left-->
			print '<div id="right">';
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/nav.html';
			
			if ( $msg_error != "" )
			{
				print "<div id= \"error\">";
				print "<span id=\"msg_error\">".$msg_error."</span>";
				print "</div><!--error-->";
			}
		?>
		<div id="content" >
		</div><!--content-->
		</div><!--right-->
		</div><!--all_center-->
			
			<?php
				require_once $_SERVER['DOCUMENT_ROOT'].'/mod/footer.html';
			?>

	</div><!--wrap-->

</body>
</html>