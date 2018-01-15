<?php include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<?php
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
			if ( isset($_SESSION['status_user']) and $_SESSION['status_user'] == '10' )
			{
				print '<script src="http://www.livefood.in.ua/ckeditor.js"></script>';
			}
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
		<?php
			//var_dump($_POST);
			//print "<br />";
			//var_dump($_GET);
			//print "<br />";
			//var_dump($_SERVER);
			//var_dump ($_COOKIE);
			//print "<br />";
			//var_dump ($_SESSION);
			//print "<br />";
			//if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
			//{
				//$lang = 'ru';
			//}
			//else
			//{
				//$lang = 'ua';
			//}
			if (isset($_POST['saveChanges']))
			{
			//var_dump($_POST);
			//print "<br />";
			//exit (0);
				$summary = str_replace('\\"', '"', $_POST['summary']);
				//print $summary.'<br />';

				$content1 = str_replace('\\"', '"', $_POST['content1']);
				//print $content1.'<br />';
				
				//unset $_GET['editarticle'];
				
				$ret = $db->query("UPDATE ?n SET title=?s, summary=?s, content=?s WHERE page='main' and id=?i",'articles',$_POST['title'],$summary,$content1,$_GET['editarticle']);
				
				//print "module->reg: ret = " . $ret . "<br />";
				if ($ret != 1)
				{
					print "<div id= \"error\">";
					print "<span id=\"msg_error\">".$ret."</span>";
					print "</div><!--error-->";
				}
			}


			if ( isset($_GET['editarticle']) and isset($_SESSION['status_user']) and $_SESSION['status_user'] == '10' )
			{
				//var_dump($_GET);
				//print "<br />";
				$ret = $db->query("Select c.name,a.title,a.summary,a.content from categories c, articles a where c.id = a.categoryId and a.visible = 1 and a.page = 'main' and a.id=?i",$_GET['editarticle']);
	
				//print $ret."<br />";
				if ($ret)
				{
					while ($row = $ret->fetch_row())
			    {
						$article_title = $row[1];
						$article_summary = $row[2];
						$article_content = $row[3];
			    }
			    
			    $ret->close();
				}
				else
				{
					print $ret."<br />";
			    print 'Возникла ошибка.<br />';
				}


				// отображаем форму для добавления новой статьи
				print '<form action="http://www.livefood.in.ua'.$_SERVER['REQUEST_URI'].'" method="post">';
  			print '<input type="hidden" name="articleId" value="'.$_GET['editarticle'].'"/>';
  			
  			print '
    			<div id="article_name">
            Название статьи: <input type="text" name="title" id="title" placeholder="Название статьи" autofocus maxlength="255" style="width: 800px; border: 1px solid #ECA013;" value="'.$article_title.'" />
	        </div>

          <div id="article_name">
            Краткое содержание статьи:
            <textarea name="summary" id="summary" required maxlength="1000" style="height: 5em;">'.$article_summary.'</textarea>
            <script>CKEDITOR.replace("summary", { width: "1000px", height: "100px" });</script>
          </div>

          <div id="article_name">
            Текст статьи:
            <textarea name="content1" id="content1" required maxlength="100000" style="height: 30em;">'.$article_content.'</textarea>
            <script>CKEDITOR.replace("content1", { width: "1000px", height: "400px" });</script>
          </div>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Сохранить статью" />
          <input type="submit" formnovalidate name="cancel" value="Отменить" />
        </div>
 
      </form>';

/*
        <li>
          <label for="categoryId">Article Category</label>
          <select name="categoryId">
            <option value="0"<?php echo !$results['article']->categoryId ? " selected" : ""?>>(none)</option>
          <?php foreach ( $results['categories'] as $category ) { ?>
            <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['article']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
          <?php } ?>
          </select>
        </li>



<?php if ( $results['article']->id ) { ?>
    <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">Delete This Article</a></p>
<?php } ?>
*/ 
			}
			else
			{
				$sql = "Select c.name,a.title,a.summary,a.content, a.lang from categories c, articles a where c.id = a.categoryId and visible = 1 and page = 'main' and lang = '$lang'";
				if (isset($_SESSION['status_user']) && $_SESSION['status_user'] == '10')
				{
					//print '<p><a href = "?editarticle=1" id = "article"><span class = "class_article">Редактировать статью</span></a></p>';
					$sql = "Select c.name,a.title,a.summary,a.content, a.lang, a.id from categories c, articles a where c.id = a.categoryId and visible = 1 and page = 'main'";
				}

				// Выводим статью
				$ret = $db->query($sql);
	
				//print $ret."<br />";
				if ($ret)
				{
					while ($row = $ret->fetch_row())
			    {
			    	if (isset($_SESSION['status_user']) && $_SESSION['status_user'] == '10')
						{
							print '<p><a href = "?editarticle='.$row[5].'" id = "article"><span class = "class_article">Редактировать статью</span></a></p>';
							//$sql = "Select c.name,a.title,a.summary,a.content, a.lang, a.id from categories c, articles a where c.id = a.categoryId and visible = 1 and page = 'main'";
						}
						print '<h1 id = "text_header" >'.$row[1];
						if (isset($_SESSION['status_user']) && $_SESSION['status_user'] == '10')
						{
							print ' ('.$row[4].')';
						}
						print '</h1>';
						print '<div class= "article_text">'.$row[2].'</div>';
						print '<div class= "article_text">'.$row[3].'</div>';
/*
						print '<span class = "content_text" >'.$row[2].'</span>';
						print '<span class = "content_text" >'.$row[3].'</span>';
*/
			    }
			    
			    $ret->close();
				}
				else
				{
					print $ret."<br />";
			    print 'Возникла ошибка.<br />';
				}

		?>
			<div id= "goods">
			<?php
			//var_dump(gd_info());
			//["HOME"]=> string(17) "/srv/www/livefood"
//["DOCUMENT_ROOT"]=> string(7) "/htdocs"
//$_SERVER["HOME"].
			$filename = $_SERVER['DOCUMENT_ROOT'].'/topitems.php';
			
			//print $filename;
//if (file_exists($filename)) {
    //echo "В последний раз файл $filename был изменен: " . date ("F d Y H:i:s.", filemtime($filename));
//}
// Это время касания, установим его на час назад.
//$time = time() - 86401;

// Трогаем файл
//if (!touch($filename,$time, $time)) {
    //echo 'Упс, что-то пошло не так...';
//} else {
    //echo 'Касание файла прошло успешно';
//}

			if (filemtime($filename)+86400 > time())
			{
				require_once($filename);
			}
			else
			{
				//print time();
				//require_once($filename);
				$ret = $db->query("select t1.id_item,t1.id_size_item,t1.img,t1.percent_img,t1.counts,
					t2.name_item name_item_ua,t3.name_item name_item_ru,t4.descr descr_ua,t5.descr descr_ru,c.price_old,c.price
					from (
					  select o.id_size_item,IFNULL(t3.percent_img,100) percent_img,t1.img,t1.id_item,o.counts, count(*) as cnt 
					  from orders o,items t1, size_items t3
					  where 1=1
					  and o.ship_date >= DATE_SUB(now(), INTERVAL 60 DAY)
					  and o.id_item=t1.id_item
					  and o.id_size_item=t3.id
					  and t1.id_item = t3.id_item
					  and t1.to_orders = 1
					  and t3.to_orders = 1
					  group by o.id_item,o.id_size_item,o.counts
					  order by cnt desc LIMIT 6
					) t1
					left join (
					select * from name_items where lang = 'ua'
					) t2 on t1.id_item = t2.id_item
					left join (
					select * from name_items where lang = 'ru'
					) t3 on t1.id_item = t3.id_item
					left join (
					select * from size_items_descr where lang = 'ua'
					) t4 on t4.id = t1.id_size_item
					left join (
					select * from size_items_descr where lang = 'ru'
					) t5 on t5.id = t1.id_size_item
					left join price c on t1.id_size_item=c.id_size_item and t1.counts=c.count and c.date_from < now() and IFNULL(c.date_to,now()+1) > now()");
				
				if ($ret)
				{
					$string = "<?php \$items = array (";
					while ($row = $ret->fetch_row())
			    {
			    	$string .= 'array("products.php?class='.$row[0].'","'.$row[1].'","'.$row[2].'","'.$row[3].'","'.$row[4].'","'.$row[5].'","'.$row[6].'","'.$row[7].'","'.$row[8].'","'.$row[9].'","'.$row[10].'"),';
			    }
			    $string = substr($string, 0, -1);
			    $string .= '); ?>';
					//print $string;
					$handle = fopen($filename, "w");
			    $fwrite = fwrite($handle, $string);
			    fclose($handle);
			    require_once($filename);
				}
			}

			$x=0;
			while ($x < 6)
			{
				print '<div class = "goods_content" ><div class = "content_name_block"><a class = "class_content" href = "';
				print $items[$x][0].'">';
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $items[$x][6];} else {print $items[$x][5];}
				print '</a><br />';
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $items[$x][8];} else {print $items[$x][7];}
				print '</div>';
				
				
				print '<div class = "goods_name_block">';
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[1]["ru"];} else {print $sentence[1]["ru"];}
				if (isset($items[$x][9]) && $items[$x][9] != "" and $items[$x][9] > $items[$x][10] )
				{
					print '<span class= "old_price">';
					print $items[$x][9];
					
					/*
					Отображение скидки отключим
					if (isset($_SESSION['status_user']))
					{
						if ($_SESSION['status_user'] == '4')
						{
							print round($items[$x][9]*90/100);
						}
						elseif ($_SESSION['status_user'] == '3')
						{
							print round($items[$x][9]*93/100);
						}
						elseif ($_SESSION['status_user'] == '2')
						{
							print round($items[$x][9]*95/100);
						}
						else
						{
							print round($items[$x][9]*97/100);
						}
					}
					else
					{
						print $items[$x][9];
					}
					*/
					print ' грн.</span> ';
				}
				print '<span class= "green_price">';
				print $items[$x][10];
				
				/*
				Отображение скидки отключим
				if (isset($_SESSION['status_user']))
				{
					if ($_SESSION['status_user'] == '4')
					{
						print round($items[$x][10]*90/100);
					}
					elseif ($_SESSION['status_user'] == '3')
					{
						print round($items[$x][10]*93/100);
					}
					elseif ($_SESSION['status_user'] == '2')
					{
						print round($items[$x][10]*95/100);
					}
					else
					{
						print round($items[$x][10]*97/100);
					}
				}
				else
				{
					print $items[$x][10];
				}
				*/
				$resizeObj = new GD_resize();

				ob_start();
				$resizeObj -> resizeImage($_SERVER['DOCUMENT_ROOT'].$items[$x][2],$items[$x][3]);
				$image = ob_get_contents();
				ob_end_clean();
				
				print ' грн.</span></div>';
				print '<div class = "goods_name_block">';
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $sentence[3]["ru"];}else{print $sentence[3]["ua"];}
				print $items[$x][4].' шт.</div>';
				//print '<img class= "goods_item" src ="'.$items[$x][6].'" alt = "'.$items[$x][1].'" />';
				//print '<img class= "goods_item" src = "data:image/png;base64,'.$resizeObj -> resizeImage($items[$x][6]).'" alt = "'.$items[$x][1].'" />';
				print '<img class= "goods_item" style="top: ';
				if (round($resizeObj -> newheight) < 114)
				{
					if (round($resizeObj -> newheight) < 20)
					{
						print (90 - round($resizeObj -> newheight));
					}
					else
					{
						print (100 - round($resizeObj -> newheight));
					}
				}
				else
				{
					print 0;
				}
				print 'px;" src = "data:image/png;base64,'.base64_encode($image).'" alt = "';
				//$items[$x][1].'" />';				
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false) {print $items[$x][6].' '.$items[$x][8];} else {print $items[$x][5].' '.$items[$x][7];}
				print '" />';
				
				
				
				//style="top: '. (114 - round($resizeObj -> newheight)) .'px;" 
				//print round($resizeObj -> newheight);
				print '<img class= "goods_shkala" src ="http://www.livefood.in.ua/img/scale.png" alt ="Шкала размера живого корма"/>';
				print '</div><!--goods_content-->';
				$x++;

				$resizeObj = null;
			}
			print '</div><!--goods-->';
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