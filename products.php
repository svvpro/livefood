<?php include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<?php
		//<title>Продукция. Виды живого корма</title>
		require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
	?>
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
			
			if ( $msg_error != "" )
			{
				print "<div id= \"error\">";
				print "<span id=\"msg_error\">".$msg_error."</span>";
				print "</div><!--error-->";
			}
		?>
		<div id = "content">
			<?php
				if ( isset($_GET['class']) )
				{//Показ определенного товара (пользователь выбрал определенную категорию товаров)
					$class = stripslashes($_GET['class']);
					$class = ereg_replace("'", "", $class);
					$class = ereg_replace('"', "", $class);
					if ( isset($_GET['item']) /*and isset($_SESSION['status_user']) and $_SESSION['status_user'] == '10'*/ )
					{
print '
<script>
function startAjax(cls,item,cnt,nm)
{
	var id_element;
	id_element = "count_box_" + cnt;
	//alert( id_element );
	//alert ( cls + \' - \' + item + \' - \' + cnt + \' - \' + nm );
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
						/*print_console("<br/><em>4: Обмен завершен.</em>"); */
						document.getElementById("basket_txt").innerHTML = request.responseText;
						
						//document.getElementById("submit_basket").style.display = "block";
						//hide(document.getElementById("submit_basket"));

						//document.getElementById("submit_basket").style.display = "block";
						document.getElementById("submit_basket").style.visibility = "visible";
						
						//var div = document.getElementById("submit_basket");
						//div.style.display = "";
						//div.style.visibility = "visible";
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
	request.open("POST","http://www.livefood.in.ua/add_chart.php",true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("class=" + cls + "&item=" + item + "&size=" + cnt + "&cnt=" + document.getElementById(id_element).value + "&name=" + nm);
}
  function print_console(text)
  {
    document.getElementById("login_welcome").innerHTML += text; 
	}

</script>
';

						$item = stripslashes($_GET['item']);
						$item = ereg_replace("'", "", $item);
						$item = ereg_replace('"', "", $item);

						$content_items = '';
	
						$ret = $db->query("select t2.name_item,t4.descr,t1.img,t3.percent_img,t5.count,t5.price,t5.price_old,t1.category
							from items t1, name_items t2, size_items t3, size_items_descr t4, price t5
							where 1=1
							and t1.id_item = t2.id_item
							and t1.id_item = t3.id_item
							and t3.id = t4.id
							and t4.lang = t2.lang
							and t5.id_size_item = t3.id
							and t1.to_orders = 1
							and t3.to_orders = 1
							and t5.date_to is null
							and t1.id_item = ?i #class
							and t3.id = ?i #item
							and t2.lang = ?s
							order by t5.count",$class,$item,$lang);
						//print $ret."<br />";
						if ($ret)
						{
							$items = array();
							while ($row = $ret->fetch_row())
					    {
					    	$items_tmp = array();
					    	$items_tmp[] = $row[0];
					    	$items_tmp[] = $row[1];
					    	$items_tmp[] = $row[2];
					    	$items_tmp[] = $row[3];
					    	$items_tmp[] = $row[4];
					    	$items_tmp[] = $row[5];
					    	$items_tmp[] = $row[6];
					    	$items_tmp[] = $row[7];
					    	
					    	//$content_items = $row[5];
					    	
					    	$items[] = $items_tmp;
					    }
	
							$x=0;
							while ($x < count($items))
							{
								$resizeObj = new GD_resize();
								ob_start();
								$resizeObj -> resizeImage($_SERVER['DOCUMENT_ROOT'].$items[$x][2],$items[$x][3]);
								$image = ob_get_contents();
								ob_end_clean();
								$bottom_set = 0;
								if (round($resizeObj -> newheight) < 145)
								{
									$bottom_set = (145 - round($resizeObj -> newheight)) / 2;
								}
								//http://www.livefood.in.ua/ua/products.php?class=2&item=21
								//print '<form method="post" action="http://www.livefood.in.ua/add_chart.php">';
								//print '<input type="hidden" name="class" value="'.$class.'" />'; //!!!
								//print '<input type="hidden" name="item" value="'.$item.'" />'; //!!!
								//print '<input type="hidden" name="count" value="'.$items[$x][4].'" />'; //!!!
				
								print '<div class = "buy_products_content" >';
								print '<div class = "buy_products_item" ><img class= "buy_products_item_img" style="bottom:'.$bottom_set.'px;" src = "data:image/png;base64,'.base64_encode($image).'" alt = "фото ('.$items[$x][0].')" />';
								if ($items[$x][7] < 10)
								{
									print '<img class= "products_shkala" src ="http://www.livefood.in.ua/img/scale.png" alt ="Шкала"/>';
								}
								print '</div>';
								print '<div class = "buy_products_block" >';
								print '<div class = "buy_products_name" ><span style="font-size:26px;">'.$items[$x][0].'</span></div>';
								print '<div class = "buy_products_name" >'.$items[$x][1].'</div>';
								print '<div class = "buy_products_name">';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
								{
									print 'Количество: ';
								}
								else
								{
									print 'Кількість: ';
								}
								print $items[$x][4].'</div>';

								print '<div class = "buy_products_name">';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
								{
									print 'Цена: ';
								}
								else
								{
									print 'Ціна: ';
								}
								if (isset($items[$x][6]) and $items[$x][6] > $items[$x][5])
								{
									print '<span class= "old_price">'.$items[$x][6].' грн.</span> ';
								}
								print '<span class= "green_price">'.$items[$x][5].' грн.</span></div>';
								print '</div>';
								print '<div class = "buy_products_block" ><br /><br /><br />';

								print '<div class = "buy_products_name" >';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
								{
									print 'Количество упаковок: ';
								}
								else
								{
									print 'Кількість упаковок: ';
								}
								print '<input type="text" name="count_box_'.$items[$x][4].'" id="count_box_'.$items[$x][4].'" size="5" maxlength="2" value="1" style="position:relative;font-family:Arial,sans-serif;font-size:18px;width:40px;float:right;margin-top:0px;margin-right:40px;outline:1px solid #F09F09;" />'; //!!!
								print '</div>';
								print '<input type= "submit" name= "input_send_'.$item.'_'.$items[$x][4].'" value= "" onclick="startAjax('.$class.','.$item.','.$items[$x][4].',\''.$items[$x][0].' '.$items[$x][1].'\');" style="width:150px;height:33px;border:0;position: relative;left: 70px;margin-top: 5px;background:url(http://www.livefood.in.ua/img/to_chart_';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print 'ru';} else {print 'ua';}
								print '.png) no-repeat 0 0;"/>';
								print '</div>';

								print '</div>';
								print '</form>';
								$x++;
				
								$resizeObj = null;
							}
							
							//print '<div class= "products_article_text">'.$content_items.'</div>';
				
						}
						else
						{
							print $ret."<br />";
					    print 'Возникла ошибка.<br />';
						}
					}
					else
					{
						$content_items = '';
	
						$ret = $db->query("select CONCAT('products.php?class=',t2.id_item,'&item=',t3.id),t2.name_item,t4.descr,t1.img,t3.percent_img,t2.content_2,t1.category
							from items t1, name_items t2, size_items t3, size_items_descr t4
							where 1=1
							and t1.id_item = t2.id_item
							and t1.id_item = t3.id_item
							and t3.id = t4.id
							and t4.lang = t2.lang
							and t1.to_orders = 1
							and t3.to_orders = 1
							and t1.id_item = ?i
							and t2.lang = ?s
							order by 2,1",$class,$lang);
						//print $ret."<br />";
						if ($ret)
						{
							$items = array();
							while ($row = $ret->fetch_row())
					    {
					    	$items_tmp = array();
					    	$items_tmp[] = $row[0];
					    	$items_tmp[] = $row[1];
					    	$items_tmp[] = $row[2];
					    	$items_tmp[] = $row[3];
					    	$items_tmp[] = $row[4];
					    	$items_tmp[] = $row[6];
					    	
					    	$content_items = $row[5];
					    	
					    	$items[] = $items_tmp;
					    }
	
							$x=0;
							while ($x < count($items))
							{
								print '<div class = "goods_content" style="outline:1px solid #F1A71E;" ><div class = "content_name_block"><a class = "class_content" href = "';
								print $items[$x][0].'">'.$items[$x][1].'</a><br />';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
								{
									print 'размер: ';
								}
								else
								{
									print 'розмір: ';
								}
								print $items[$x][2].'</div>';

								$resizeObj = new GD_resize();
								ob_start();
								$resizeObj -> resizeImage($_SERVER['DOCUMENT_ROOT'].$items[$x][3],$items[$x][4]);
								$image = ob_get_contents();
								ob_end_clean();
	
								print '<div class = "products_parent_all_item" ><img class= "products_item" src = "data:image/png;base64,'.base64_encode($image).'" alt = "';
								if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
								{
									print 'Купить Киев ';
								}
								else
								{
									print 'Купити придбати Киів ';
								}
								print $items[$x][1].'" />';
								if ($items[$x][5] < 10)
								{
									print '<img class= "products_shkala" src ="http://www.livefood.in.ua/img/scale.png" alt ="Шкала"/>';
								}
								print '</div>';
								print '</div><!--goods_content-->';
								$x++;
				
								$resizeObj = null;
							}
							
							print '<div class= "products_article_text">'.$content_items.'</div>';
				
						}
						else
						{
							print $ret."<br />";
					    print 'Возникла ошибка.<br />';
						}
					}
				}
				else
				//print $lang."<br />";
				{ //Показ полного списка товаров (пользователь просто зашел в раздел товары)
					$ret = $db->query("select t1.id_item, t2.name_item, t1.img, t2.content from items t1, name_items t2 where t1.id_item = t2.id_item and t2.lang = ?s and to_orders = 1 order by t1.id_item",$lang);
				
					//print $ret."<br />";
					if ($ret)
					{
						while ($row = $ret->fetch_row())
				    {
							print '<div class= "prudact">';
							print '<div class= "prudact_parent_header">';
							print '<div class= "prudact_header">';
							print '<a href= "?class='.$row[0].'" class= "prudact_header_name"/>'.$row[1].'</a></div></div>';
							print '<div class= "prudact_parent_img">';
							print '<div class= "prudact_img">';//print '<a href= "?class='.$row[0].'" class= "prudact_header_name"/><img src= "http://www.livefood.in.ua'.$row[2].'" alt= "'.$row[1].'"/></a>';
							print '<a href= "?class='.$row[0].'" class= "prudact_header_name"/>';

							$resizeObj = new GD_resize();
							ob_start();
							$resizeObj -> resizeImage($_SERVER['DOCUMENT_ROOT'].$row[2],100);
							$image = ob_get_contents();
							ob_end_clean();
							print '<img src = "data:image/png;base64,'.base64_encode($image).'" alt = "'.$row[1].' купить Киев"';
							$resizeObj = null;

							print '</a>';
							print '</div></div>';
							print '<div class= "prudact_text">'.$row[3].'</div>';
							print '</div>';
							
				    }
				    //$ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
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
