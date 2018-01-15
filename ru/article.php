<?php include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			//<title>Статьи. Живой корм для рептилий, птиц и животных.</title>
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
				//var_dump($_POST);
				//print "<br />";

				//var_dump($_GET);
				//print "<br />";
/*
				if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
				{
					$lang = 'ru';
				}
				else
				{
					$lang = 'ua';
				}
*/
				if (isset($_POST['saveChanges']))
				{
					//var_dump($_POST);
					//print "<br />";
					$summary = str_replace('\\"', '"', $_POST['summary']);
					//print $summary.'<br />';

					$content1 = str_replace('\\"', '"', $_POST['content1']);
					//print $content1.'<br />';
					if (isset($_POST['visible']))
					{
						$article_visible = 1;
					}
					else
					{
						$article_visible = 0;
					}
					
					if (isset($_POST['articleId']) && $_POST['articleId'] != "")
					{
						//$ret = $db->query("INSERT INTO ?n (publicationDate, categoryId, title, summary, content) VALUES (now(),?i,?s,?s,?s)",'articles',1,$_POST['title'],$summary,$content1);
						$ret = $db->query("UPDATE ?n SET title=?s, summary=?s, content=?s, visible=?i WHERE id=?i",'articles',$_POST['title'],$summary,$content1,$article_visible,$_POST['articleId']);
						
						//print "module->reg: ret = " . $ret . "<br />";
						if ($ret != 1)
						{
							//print $ret."<br />";
					    //print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
							print "<div id= \"error\">";
							print "<span id=\"msg_error\">".$ret."</span>";
							print "</div><!--error-->";
						}
					}
					else
					{
						$ret = $db->query("INSERT INTO ?n (publicationDate, categoryId, title, summary, content, visible, page, lang) VALUES (now(),?i,?s,?s,?s,?i,?s,?s)",'articles',1,$_POST['title'],$summary,$content1,$article_visible,'article',$lang);
						
						//print "module->reg: ret = " . $ret . "<br />";
						if ($ret != 1)
						{
							//print $ret."<br />";
					    //print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
							print "<div id= \"error\">";
							print "<span id=\"msg_error\">".$ret."</span>";
							print "</div><!--error-->";
						}
					}
				}

				//if (isset($_POST['cancel']))
				//{
				//}
				
				if ( isset($_GET['newarticle']) || isset($_GET['editarticle']))
				{
					if (isset($_SESSION['status_user']))
					{
						if ($_SESSION['status_user'] == '10')
						{
							
							if (isset($_GET['editarticle']))
							{
								$ret = $db->query("Select c.name,a.title,a.summary,a.content,a.visible,a.lang from categories c, articles a where c.id = a.categoryId and a.id=?i and page = 'article'",$_GET['editarticle']);
					
								//print $ret."<br />";
								if ($ret)
								{
									while ($row = $ret->fetch_row())
							    {
										$article_title = $row[1];
										$article_summary = $row[2];
										$article_content = $row[3];
										//print $row[4].'<br />';
										if ($row[4] == 1)
										{
											$article_visible = "checked";
										}
										else
										{
											$article_visible = "";
										}
										$lang = $row[5];
							    }
							    
							    $ret->close();
								}
								else
								{
									print $ret."<br />";
							    print 'Возникла ошибка.<br />';
								}
							}
							else
							{
								$article_title = "";
								$article_summary = "";
								$article_content = "";
								$article_visible = "";
							}

							// отображаем форму для добавления новой статьи
      				print '<form action="article.php" method="post">';
        			//print '<input type="hidden" name="articleId" value="'.$_GET['editarticle'].'"/>';
        			print '<input type="hidden" name="articleId" value="';
        			if (isset($_GET['editarticle']))
        			{
        				print $_GET['editarticle'];
        			}
        			else
        			{
        				print '';
        			}
        			print '"/>';
        			
        			print '
	        			<div id="article_name">
			            Название статьи: <input type="text" name="title" id="title" placeholder="Название статьи" autofocus maxlength="255" style="width: 800px; border: 1px solid #ECA013;" value="'.$article_title.'" /><br />
			            Язык статьи: <input type="text" name="lang" placeholder="Язык статьи" maxlength="2" style="width: 15px; border: 1px solid #ECA013;" value="'.$lang.'" />
				        </div>
				        
				        <input type="checkbox" name="visible" '.$article_visible.' value=""/> Статья доступна посетителям <br />
				         

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
					}
				}
				elseif (isset($_GET['id']))
				{
					$sql = "Select c.name,a.title,a.summary,a.content from categories c, articles a where c.id = a.categoryId and a.id=?i and visible = 1 and page = 'article'";
					if (isset($_SESSION['status_user']))
					{
						if ($_SESSION['status_user'] == '10')
						{
							print '<p><a href = "?editarticle='.$_GET['id'].'" id = "article"><span class = "class_article">Редактировать статью</span></a></p>';
							// Выводим статью
							$sql = "Select c.name,a.title,a.summary,a.content from categories c, articles a where c.id = a.categoryId and a.id=?i and page = 'article'";
						}
					}
					// Выводим статью
					//$ret = $db->query("Select c.name,a.title,a.summary,a.content from categories c, articles a where c.id = a.categoryId and a.id=?i and visible = 1 and page = 'article'",$_GET['id']);
					$ret = $db->query($sql,$_GET['id']);
		
					//print $ret."<br />";
					print '<div id= "article">';
					if ($ret)
					{
						while ($row = $ret->fetch_row())
				    {
							print '<h1 id = "text_header" >'.$row[1].'</h1>';
							print '<div id= "article_text">'.$row[2].'</div>';
							print '<div id= "article_text">'.$row[3].'</div>';
				    }
				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
					print '</div><!--article-->';
				}
				else
				{
					$sql = "Select a.id,c.name,a.publicationDate,a.title,a.summary,a.lang from categories c, articles a where c.id = a.categoryId and visible = 1 and page = 'article' and lang = '$lang'";
					if (isset($_SESSION['status_user']))
					{
						if ($_SESSION['status_user'] == '10')
						{
							print '<a href = "?newarticle=1" id = "article"><span class = "class_article">Добавить статью</span></a>';
							$sql = "Select a.id,c.name,a.publicationDate,a.title,a.summary,a.lang from categories c, articles a where c.id = a.categoryId and page = 'article'";
						}
					}
					// Выводим перечень статей
					$ret = $db->query($sql);
		
					//print $ret."<br />";
					if ($ret)
					{
						while ($row = $ret->fetch_row())
				    {
							print '<div id= "article">';
							//print '<div id= "head_article">
							print '<a href = "?id='.$row[0].'" id = "article"><span class = "class_article">'.$row[3].'</span></a>';
							
							if (isset($_SESSION['status_user']) && $_SESSION['status_user'] == '10')
							{
								print ' ('.$row[5].')';
							}
							print '<div id= "article_text">'.$row[4].'</div>';
							//print '<span class = "content_text" >'.$row[4].'</span>';
							print '</div><!--article-->';
				    }
				    $ret->close();
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