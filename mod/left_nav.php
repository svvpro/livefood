<?php
	print '<div id= "trapecia1" style="background:url(http://www.livefood.in.ua/img/trapecia1.png);" ></div><!--trapecia1-->';
  if (strpos($_SERVER['REQUEST_URI'], 'adm.php') === false)
  {
	  print '<div id= "left_nav" style="background:url(http://www.livefood.in.ua/img/bg_header.png) repeat;">';
	  if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[7]["ru"]; $lang = 'ru';}else{print $sentence[7]["ua"]; $lang = 'ua';}

		$ret = $db->query("select t1.id_item, t2.name_item from items t1, name_items t2 where t1.id_item = t2.id_item and to_orders = 1 and t2.lang = ?s order by id_item", $lang);
		//print $ret."<br />";
		if ($ret)
		{
			while ($row = $ret->fetch_row())
	    {
				print '<div class= "lines_left"></div><div class="left_navigator">';
				print '<a class= "left_class" href= "products.php?class='.$row[0].'">'.$row[1].'</a>';
				//print '<img class= "img_left" src= "data:image/png;base64,'.base64_encode($image).'" alt= "'.$row[1].'"/>';
				print '</div><!--left_navigator-->';
	    }
	    $ret->close();
		}
		else
		{
			print $ret."<br />";
	    print 'Возникла ошибка.<br />';
		}
	  print '<div class= "lines_left"></div></div><!--left_nav-->';
	}
	else
	{
		if ( isset($_SESSION['login_user']) and $_SESSION['status_user'] == '10' )
		{
		  print '<div id= "left_nav" style="background:url(http://www.livefood.in.ua/img/bg_header.png) repeat;">РАЗДЕЛЫ';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=1">Управление пользователями</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=2">Категории товаров</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=3">Товары</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=4">Прайсы</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=5">Сообщения</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=6">Заходы на сайт</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=7">Описания страниц</a>';
			print '</div><!--left_navigator-->';

			print '<div class= "lines_left"></div><div class="left_navigator">';
			print '<a class= "left_class" href= "adm.php?section=8">Мультиязычность</a>';
			print '</div><!--left_navigator-->';

		  print '<div class= "lines_left"></div></div><!--left_nav-->';
		}

	}
  print '<div id= "trapecia2" style="background:url(http://www.livefood.in.ua/img/trapecia2.png);"></div><!--trapecia2-->';
?>