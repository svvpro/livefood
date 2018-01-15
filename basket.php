<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			//<title>Корзина</title>
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
		?>
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

		<div id = "content">
		<?php
		//var_dump($_SESSION);
		//print "<br />";
		//print "<br />";
		//var_dump($_SESSION['array_basket']);
		//print "<br />";
		//print "<br />";
		
		if (isset($_SESSION['array_basket']))
		{

			print '<div style="width: 800px; border: 0px solid #ECA013;"><div class = "td1">';
			if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[2]["ru"];}else{print $sentence[2]["ua"];}
			print '</div><div class = "td1">';
			if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[4]["ru"];}else{print $sentence[4]["ua"];}
			print '</div><div class = "td1">';
			if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[5]["ru"];}else{print $sentence[5]["ua"];}
			print '</div><div class = "td1">';
			if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[6]["ru"];}else{print $sentence[6]["ua"];}
			print '</div></br>';
			$array1 = [];
			if (count($_SESSION['array_basket']) > 0)
			{
				foreach ($_SESSION['array_basket'] as $arr_item)
				{
					//print ">   ---";
					//print_r($arr_item);
					//print "<br />";
					$i = 1;
					foreach ($array1 as $arr_item1)
					{
						//print "---  >";
						//print_r($arr_item1);
						//print "<br />";
						$i++;
						if ($arr_item['class'] == $arr_item1['class'] && $arr_item['item'] == $arr_item1['item'] && $arr_item['size'] == $arr_item1['size'])
						{
							$array1[$i-2]['cnt'] = $array1[$i-2]['cnt'] + $arr_item['cnt'];
							$i = 0;
							break;
						}
					}
					if ($i > 0)
					{
						//print "save <br />";
						//print_r($array1);
						//print "<br />";

						//print_r($arr_item);
						//print "<br />";
/*
						$result = array_merge($array1, $arr_item);
						$array1 = $result;
												
						$result = $array1 + $arr_item;
						$result = $array1 + $arr_item;
												
						print_r($result);
						print "<br />";
*/
						array_push($array1, $arr_item);
						//print_r($array1);
						//print "<br /><br />";
						
					}
				}
				//print_r($array1);
				//print "<br />";
				$summ = 0;
				foreach ($array1 as $arr_item)
				{
					//print_r ($arr_item);
					//print "<br />";
// [class] => 2 [item] => 21 [size] => 50 [cnt] => 1
					//print '<div class = "td1">'.$arr_item['class'].' '.$arr_item['item'].' '.$arr_item['size'].' (штук)'.'</div>';
					print '<div class = "td1">'.$arr_item['name'].' '.$arr_item['size'].' (штук)'.'</div>';
					print '<div class = "td1">'.$arr_item['cnt'].'</div>';

					$ret = $db->query("select price,price*?i from price where id_size_item = ?i and count = ?i and date_to is null",$arr_item['cnt'],$arr_item['item'],$arr_item['size']);
					
					//$ret = $db->query("select price,price from price where id_size_item = ?i and count = ?i and date_to is null",$arr_item['item'],$arr_item['size']);

					if ($ret)
					{
						while ($row = $ret->fetch_row())
				    {
				    	print '<div class = "td1">'.$row[0].'</div>';
				    	//print '<div class = "td1">'.$arr_item['cnt']*$row[0].'</div>';
				    	print '<div class = "td1">'.$row[1].'</div>';
				    	$summ += $row[1];
				    }
				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
					
					//print '<br />';
				}
				print '<div class = "td1" style="outline:0px solid #F09F09;"></div><div class = "td1" style="outline:0px solid #F09F09;"></div><div class = "td1"><b>';
				
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[0]["ru"];}else{print $sentence[0]["ua"];}
				print '</b></div>';
				print '<div class = "td1"><b>' . $summ . '</b></div>';
			}
			//print '<p><input type="submit" form="data" value="Сохранить Заказ"></p>';
	    print '</div>';
	    
	    print '<br /><div style="width: 800px; border: 0px solid #ECA013;">';
	    print '<p style="text-align:justify"><span style="font-family:arial,sans-serif; font-size:16px">&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color:#FF0000">Внимание!!!</span></strong><br />Функционал корзины реализован не полностью. Заказать товар можно позвонив по телефонам <br />+38/099/524-41-02;<br />+38/067/306-58-02<br />или же отправив сообщение через раздел <a href = "ties.php" title = "контакты">контакты</a>.<br />Отправка товаров в другие города Украины осуществляется транспортной компанией АвтоЛюкс (Новая Почта живые товары не перевозит).</span></p>
<p style="text-align:justify"><span style="font-family:arial,sans-serif; font-size:16px">Есть куръерская доставка по Киеву или забрать заказы можно (по будням):</span></p>
<p style="text-align: justify;"><span style="font-family:arial,sans-serif; font-size:16px">7:30 м. Теремки;</span></p>
<p style="text-align: justify;"><span style="font-family:arial,sans-serif; font-size:16px">8:10 м. Льва Толстого;</span></p>
<p style="text-align: justify;"><span style="font-family:arial,sans-serif; font-size:16px">8:30 м. Петровка; </span></p>
<p style="text-align: justify;"><span style="font-family:arial,sans-serif; font-size:16px">с 10:00 до 21:00 зоомагазин "Лапка", ул. Елизаветы Чавдар, 2 (м. Осокорки). </span></p>
';

	    print '</div>';

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