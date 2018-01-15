<?php include_once $_SERVER['DOCUMENT_ROOT'].'/conf.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
		<?php
			print '<script src="http://www.livefood.in.ua/ckeditor.js"></script>';
			require_once $_SERVER['DOCUMENT_ROOT'].'/mod/meta_link_haki.html';
			//require_once("./topitems.php");
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
		<div id="content">
		<?php
	
		//var_dump($_SESSION['array_basket']);
		//print "<br />";
		//print "<br />";
		//var_dump($_COOKIE);
		
		//var_dump(array_intersect_key($_SESSION['array_basket'][0], $_SESSION['array_basket'][3]));
		//var_dump(array_unique($_SESSION['array_basket']));
		//print_r($_SESSION['array_basket']);
		//print "<br />";
		//print "<br />";
		
		//$result = array_diff($_SESSION['array_basket'][0], $_SESSION['array_basket'][1]);
		//print_r($result);
		//print "<br />";
		
		//unset ($_SESSION['login_user']);
		
/*		
		$json = json_encode($_SESSION);
		echo $json;
		print "<br />";
		print "<br />";
		
		$auth = new auth($db);
		
		if (isset($_COOKIE['livefood_in_ua']))
		{
			//print "2<br />";
			$data = $auth->strcode(base64_decode($_COOKIE['livefood_in_ua']), '');

			$checksum_aski = substr($data,strlen($data)-8);
			$data = substr($data,0,strlen($data)-8);
			$checksum = crc32($data);
			if (sprintf("%X", $checksum) == $checksum_aski)
			{// checksum correct
				$data = substr($data,4,strlen($data)-8);
				print "read_cookie: ".$data."<br /><br />";
			}
		}
		*/

/*		
		//print $checksum;//sprintf("%X", $checksum);
		print "<br />";
		print "<br />";		

		print $auth->strcode($json, '');
		print "<br />";
		print "<br />";
		print base64_encode($auth->strcode($json, ''));
		print "<br />";
		print "<br />";
*/

		
		//var_dump($_REQUEST);
		//print "<br />";
		//var_dump($_SERVER);
		//print "<br />";
		if ( isset($_SESSION['login_user']) and $_SESSION['status_user'] == '10' )
		{
			print '<script src="http://www.livefood.in.ua/js/ajax.js"></script>';
			//var_dump($_POST);
			//print "<br />";
			//print_r($_POST);
			//print count($_POST)."<br />";
			//print_r($_SERVER);

			if (isset($_GET['section']))
			{
				$section = stripslashes($_GET['section']);
				$section = ereg_replace("'", "", $section);
				$section = ereg_replace('"', "", $section);
				
				if ($section == '1')
				{
/*
					$i = 0;
					foreach($_POST as $value) 
					{
						if ($i == 0)
						{
							$id_user = $value;
					  	$i++;
						}
						else
						{
							$ret = $db->query("UPDATE ?n SET status_user=?i WHERE id_user=?i",'users',$value,$id_user);
							$i = 0;
						}
					}
*/
					//print "Управление пользователями<br />";
					$ret = $db->query("select login_user,mail_user,tlf_user,status_user,id_user,date_create from users");

					if ($ret)
					{
						//print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<div style="width: 1100px; border: 0px solid #ECA013;"><div class = "td1">Логин пользователя</div><div class = "td1">Email пользователя</div><div class = "td1">Телефон</div><div class = "td1">Дата</div><div class = "td1">Статус пользователя</div></br>';
						while ($row = $ret->fetch_row())
				    {
				    	print '<input type="hidden" name="usr_'.$row[4].'" value="'.$row[4].'" form="data"/>';
				    	
							print '<div class = "td1">'.$row[0].'</div>';
							print '<div class = "td1">'.$row[1].'</div>';
							print '<div class = "td1">'.$row[2].'</div>';
							print '<div class = "td1">'.$row[5].'</div>';
							print '<div class = "td1"><select size="1" id="num_'.$row[4].'" onchange="startAjax('.$section.','.$row[4].',this.id)">';
					    print '<option ';
					    if ($row[3] == '1') {print 'selected ';}
					    print 'value="1">Клиент</option>';
					    print '<option ';
					    if ($row[3] == '10') {print 'selected ';}
					    print 'value="10">Администратор</option>';
					    print '<option ';
					    if ($row[3] == '9') {print 'selected ';}
					    print 'value="9">Шапокляк</option>';

							print '</select></div><br />';
				    }
				    //print '<p><input type="submit" form="data" value="Сохранить изменения"></p>';
				    print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '2')
				{
					print '<table><thead><tr>
					<th scope="col">Группа товаров (UA)</th>
					<th scope="col">Группа товаров (RU)</th>
					</tr></thead><tbody>';
					print '<tr>';
					print '<td><input type="text" name="new_name_item_ua" id="new_name_item_ua" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Название товара (ua)" value="" /></td>';
					print '<td><input type="text" name="new_name_item_ru" id="new_name_item_ru" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Название товара (ru)" value="" /></td>';
				  print '</tr>';
			    print '</tbody></table>';
			    print '<p><input type="submit" name="new_save" id="new_save_buttom'.$section.'" value="Новая категория" onclick="startAjax('.$section.',null,this.id,null)"></p><br /><br />';

					$ret = $db->query("select t1.id_item,t1.to_orders,t1.img,t2.name_item,t3.name_item,t2.content,t3.content,t2.content_2,t3.content_2,t1.category
						from items t1
						left join (
						select * from name_items where lang = 'ua'
						) t2 on t1.id_item = t2.id_item
						left join (
						select * from name_items where lang = 'ru'
						) t3 on t1.id_item = t3.id_item
						order by to_orders desc,t1.id_item");
					if ($ret)
					{
						print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post">';
						print '<table><thead><tr>
						<th scope="col">Группа товаров (UA)</th>
						<th scope="col">Группа товаров (RU)</th>
						<th scope="col">Категория товара</th>
						<th scope="col">Статус отображения группы</th>
						<th scope="col">Рисунок товара</th>
						<th scope="col">Описание группы (UA)</th>
						<th scope="col">Описание группы (RU)</th>
						<th scope="col">Описание товаров (UA)</th>
						<th scope="col">Описание товаров (RU)</th>
						</tr></thead><tbody>';

						while ($row = $ret->fetch_row())
				    {
				    	print '<tr>';
				    	print '<td><input type="text" id="'.$row[0].'_name_item_ua" style="width: 80px; border: solid 1px #EFCF00;" placeholder="Название товара (ua)" value="'.$row[3].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td><input type="text" id="'.$row[0].'_name_item_ru" style="width: 80px; border: solid 1px #EFCF00;" placeholder="Название товара (ru)" value="'.$row[4].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td><input type="text" id="'.$row[0].'_category" style="width: 70px; border: solid 1px #EFCF00;" placeholder="Категория товара" maxlength="6" value="'.$row[9].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td style="width: 110px;"><input type="radio" name="'.$row[0].'_item_visible" id="'.$row[0].'_item_visible" value="1" onchange="startAjax('.$section.','.$row[0].',this.id,null)"';
				    	if ($row[1] == "1") {print ' checked';}
				    	print '>Доступно<br><input type="radio" name="'.$row[0].'_item_visible" id="'.$row[0].'_no_item_visible" value="0" onchange="startAjax('.$section.','.$row[0].',this.id,null)"';
				    	if ($row[1] == "0") {print ' checked';}
				    	print '>Не доступно</td>';
				    	print '<td><input type="text" id="'.$row[0].'_img" style="width: 90px; border: solid 1px #EFCF00;" placeholder="Рисунок товара" maxlength="255" value="'.$row[2].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td><textarea rows="12" cols="18" id="'.$row[0].'_text_ua" onchange="startAjax('.$section.','.$row[0].',this.id,null)">'.$row[5].'</textarea></td>';
				    	print '<td><textarea rows="12" cols="18" id="'.$row[0].'_text_ru" onchange="startAjax('.$section.','.$row[0].',this.id,null)">'.$row[6].'</textarea></td>';
				    	print '<td><textarea rows="12" cols="18" id="'.$row[0].'_txt2_ua" onchange="startAjax('.$section.','.$row[0].',this.id,null)">'.$row[7].'</textarea></td>';
				    	print '<td><textarea rows="12" cols="18" id="'.$row[0].'_txt2_ru" onchange="startAjax('.$section.','.$row[0].',this.id,null)">'.$row[8].'</textarea></td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" name="save" form="data" value="Сохранить изменения"></p><br /><br />';
				    //print '</div>';
				    
				    //mysqli_free_result($ret);
				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '3')
				{

					if (isset($_POST['new_save']))
					{
						$i = 0;
						unset ($num_id_item,$new_descr_item_ru,$new_descr_item_ru);
						foreach($_POST as $key => $value) 
						{
							//print $key . ' - ' . $value . ' - ';
							//print strstr ($key,'_',true) . '<br />';
							if ($i < 4)
							{
						  	$i++;
						  	if (strpos($key, 'new_descr_item_ua') !== false)
								{
									$new_descr_item_ru = $value;
								}
								elseif (strpos($key, 'new_descr_item_ru') !== false)
								{
									$new_descr_item_ru = $value;
								}
								elseif (strpos($key, 'num_id_item') !== false)
								{
									$num_id_item = $value;
								}
							}
						}

						if( isset ($new_descr_item_ru) && isset ($new_descr_item_ru) )
						{
							//print $num_id_item.' = '.$new_descr_item_ru.' = '.$new_descr_item_ru.'<br />';
							$ret = $db->query("begin;");
							$ret = $db->query("INSERT INTO ?n (id_item,to_orders,percent_img) values (?i,0);",'size_items',$num_id_item,100);
							$ret = $db->query("select max(id) from size_items;");
							if ($ret)
							{
								while ($row = $ret->fetch_row())
						    {
						    	$id_item = $row[0];
						    }
						    mysqli_free_result($ret);
						    $ret = $db->query("INSERT INTO ?n (id,descr,lang) values (?i,?s,?s);",'size_items_descr',$id_item,$new_descr_item_ru,'ua');
						    $ret = $db->query("INSERT INTO ?n (id,descr,lang) values (?i,?s,?s);",'size_items_descr',$id_item,$new_descr_item_ru,'ru');
							}
							//$ret = $db->query("rollback;");
							$ret = $db->query("commit;");

							$i = 0;
							unset ($num_id_item,$new_descr_item_ru,$new_descr_item_ru);
						}
					}
/*
					if (isset($_POST['save']))
					{
						//print_r($_POST);
						//print "<br />";
						$i = 0;
						unset ($id_item, $img, $item_visible, $descr_item_ua, $descr_item_ru);
						foreach($_POST as $key => $value) 
						{
							//print $key . ' - ' . $value . ' - ';
							//print strstr ($key,'_',true) . '<br />';
							if ($i < 4)
							{
						  	$i++;
						  	if (strpos($key, 'img') !== false)
								{
									$img = $value;
								}
								elseif (strpos($key, 'item_visible') !== false)
								{
									$item_visible = $value;
								}
								elseif (strpos($key, 'descr_item_ua') !== false)
								{
									$descr_item_ua = $value;
								}
								elseif (strpos($key, 'descr_item_ru') !== false)
								{
									$descr_item_ru = $value;
								}
							}
							
							if( isset ($img) && isset ($item_visible) && isset ($descr_item_ua) && isset ($descr_item_ru) )
							{
								$id_item = strstr ($key,'_',true);
								//print $id_item.' = '.$img.' = '.$item_visible.' = '.$descr_item_ua.' = '.$descr_item_ru.'<br />';
								//$ret = $db->query("UPDATE ?n SET descr=?s,to_orders=?i,percent_img=?i WHERE id=?i",'size_items',$name_item,$item_visible,$img,$id_item);
								$ret = $db->query("UPDATE ?n SET to_orders=?i,percent_img=?i WHERE id=?i",'size_items',$item_visible,$img,$id_item);
								$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ua' and id=?i",'size_items_descr',$descr_item_ua,$id_item);
								$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ru' and id=?i",'size_items_descr',$descr_item_ru,$id_item);

								$i = 0;
								unset ($id_item, $img, $item_visible, $descr_item_ua, $descr_item_ru);
							}
						}
					}
*/
					$ret = $db->query("select t2.id_item,IFNULL(t1.to_orders,0),group_concat(t2.name_item ORDER BY t2.lang desc separator ' / ') name_items
						  from items t1, name_items t2
						  where 1=1
						  and t1.id_item = t2.id_item
						  GROUP By id_item,t1.to_orders
              order by 2 desc, 3");
					if ($ret)
					{
						//print '<form id="new_data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post">';
						print '<table><thead><tr>
						<th scope="col">Категория товаров</th>
						<th scope="col">Товар (UA)</th>
						<th scope="col">Товар (RU)</th>
						</tr></thead><tbody>';
						print '<tr>';
						print '<td><select size="1" name="num_id_item" id="num_id_item"';
			    
						while ($row = $ret->fetch_row())
				    {
				    	print '<option value="'.$row[0].'">'.$row[2].'</option>';
				    }
				    //mysqli_free_result($ret);
						
						print '</select></td>';
						print '<td><input type="text" name="new_descr_item_ua" id="new_descr_item_ua" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Название товара (ua)" value="" /></td>';
						print '<td><input type="text" name="new_descr_item_ru" id="new_descr_item_ru" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Название товара (ru)" value="" /></td>';
					  print '</tr>';
				    print '</tbody></table>';
				    print '<p><input type="submit" name="new_save" id="new_save_buttom'.$section.'" value="Новый товар" onclick="startAjax('.$section.',null,this.id,null)"></p><br /><br />';
				    //print '<p><input type="submit" name="new_save" id="new_save_buttom" value="Новая категория" onclick="startAjax('.$section.',null,this.id,null)"></p><br /><br />';

				  	$ret->close();
					}
			    
                          
					$ret = $db->query("select t2.id, name_items,percent_img,to_orders,t3.descr,t4.descr
						from (
						  select t2.id_item,group_concat(t2.name_item ORDER BY t2.lang desc separator ' / ') name_items
						  from items t1, name_items t2
						  where t1.to_orders = 1
						  and t1.id_item = t2.id_item
						  GROUP By id_item
						) t1
						left join (
						select * from size_items
						) t2 on t1.id_item = t2.id_item
						left join (
						select * from size_items_descr where lang = 'ua'
						) t3 on t3.id = t2.id
						left join (
						select * from size_items_descr where lang = 'ru'
						) t4 on t4.id = t2.id
						order by 2,1");
					if ($ret)
					{
						//print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Категория товаров</th>
						<th scope="col" style="width: 120px;">Статус отображения товара</th>
						<th scope="col" style="width: 100px;">Размер рисунка, %</th>
						<th scope="col">Товар (UA)</th>
						<th scope="col">Товар (RU)</th>
						</tr></thead><tbody>';

						while ($row = $ret->fetch_row())
				    {
				    	print '<tr><td>'.$row[1].'</td>';
				    	print '<td style="width: 120px;"><input type="radio" name="'.$row[0].'_item_visible" id="'.$row[0].'_item_visible" value="1" onchange="startAjax('.$section.','.$row[0].',this.id,null)"';
				    	if ($row[3] == "1") {print ' checked';}
				    	print '>Доступно<br><input type="radio" name="'.$row[0].'_item_visible" id="'.$row[0].'_no_item_visible" value="0" onchange="startAjax('.$section.','.$row[0].',this.id,null)"';
				    	if ($row[3] == "0") {print ' checked';}
				    	print '>Не доступно</td>';
				    	print '<td><input type="text" name="'.$row[0].'_img" id="'.$row[0].'_img" style="width: 100px; border: solid 1px #EFCF00;" placeholder="Размер рисунка" maxlength="5" value="'.$row[2].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td><input type="text" name="'.$row[0].'_descr_item_ua" id="'.$row[0].'_descr_item_ua" style="width: 220px; border: solid 1px #EFCF00;" placeholder="Название товара (ua)" value="'.$row[4].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '<td><input type="text" name="'.$row[0].'_descr_item_ru" id="'.$row[0].'_descr_item_ru" style="width: 220px; border: solid 1px #EFCF00;" placeholder="Название товара (ru)" value="'.$row[5].'" onchange="startAjax('.$section.','.$row[0].',this.id,null)" /></td>';
				    	print '</tr>';
				    }
				    print '</tbody></table><br /><br />';
				    //print '<p><input type="submit" name="save" form="data" value="Сохранить изменения"></p>';
				    //print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '4')
				{
					if (isset($_POST['new_save']))
					{
						//var_dump($_POST);
						//print "<br />";
			
						$i = 0;
						unset ($num_id_item,$num_id,$count,$price);
						foreach($_POST as $key => $value) 
						{
							//print $key . ' - ' . $value . '<br />';
							//print strstr ($key,'_',true) . '<br />';
							if ($i < 5)
							{
						  	$i++;
						  	if (strpos($key, 'num_id_item') !== false)
								{
									$num_id_item = $value;
								}
								elseif (strpos($key, 'num_id') !== false)
								{
									$num_id = $value;
								}
								elseif (strpos($key, 'count') !== false)
								{
									$count = $value;
								}
								elseif (strpos($key, 'price') !== false)
								{
									$price = $value;
								}
							}
						}

						if( isset ($num_id_item) && isset ($num_id) && isset ($count) && isset ($price) )
						{
							//print "num_id_item = " .$num_id_item. "<br />";
							//print "num_id = " .$num_id. "<br />";
							//print "count = " .$count. "<br />";
							//print "price = " .$price. "<br />";							
							$ret = $db->query("select price from ?n WHERE id_size_item=?i and count=?i;",'price',$num_id,$count);
							if ($ret)
							{
								unset ($num_id_item);
								while ($row = $ret->fetch_row())
						    {
						    	$num_id_item = $row[0];
						    }
						    mysqli_free_result($ret);
						    
						    //print $num_id_item ."<br />";
						    
						    if( isset ($num_id_item) )
						    {
						    	//print "UPDATE<br />";
									$ret = $db->query("UPDATE ?n SET date_to=now() WHERE id_size_item=?i and count=?i",'price',$num_id,$count);
									$ret = $db->query("INSERT INTO ?n (id_size_item,count,price,price_old,date_from) VALUES (?i,?i,?i,?i,now())",'price',$num_id,$count,$price,$num_id_item);
								}
								else
								{
									//print "INSERT<br />";
									$ret = $db->query("INSERT INTO ?n (id_size_item,count,price,date_from) VALUES (?i,?i,?i,now())",'price',$num_id,$count,$price);
								}
								
/*
			$ret = $db->query("begin;");
			$ret = $db->query("INSERT INTO ?n (id_item,to_orders,percent_img) values (?i,0,?i);",'size_items',$num_id_item,100);
			$ret = $db->query("select max(id) from size_items;");
			if ($ret)
			{
				while ($row = $ret->fetch_row())
		    {
		    	$id_item = $row[0];
		    }
		    mysqli_free_result($ret);
		    $ret = $db->query("INSERT INTO ?n (id,descr,lang) values (?i,?s,?s);",'size_items_descr',$id_item,$new_descr_item_ua,'ua');
		    $ret = $db->query("INSERT INTO ?n (id,descr,lang) values (?i,?s,?s);",'size_items_descr',$id_item,$new_descr_item_ru,'ru');
			}
			//$ret = $db->query("rollback;");
			$ret = $db->query("commit;");
*/


							}
							$i = 0;
							unset ($num_id_item,$num_id,$count,$price);
						}
					}
/*
					if (isset($_POST['save']))
					{
						print_r($_POST);
						print "<br />";
						$i = 0;
						unset ($id_item, $img, $item_visible, $descr_item_ua, $descr_item_ru);
						foreach($_POST as $key => $value) 
						{
							//print $key . ' - ' . $value . ' - ';
							//print strstr ($key,'_',true) . '<br />';
							if ($i < 4)
							{
						  	$i++;
						  	if (strpos($key, 'count') !== false)
								{
									$count = $value;
								}
								elseif (strpos($key, 'price') !== false)
								{
									$price = $value;
								}
							}
							
							if( isset ($count) && isset ($price) && isset ($id_size_item) )
							{
								$id_item = strstr ($key,'_',true);
								//print $id_item.' = '.$img.' = '.$item_visible.' = '.$descr_item_ua.' = '.$descr_item_ru.'<br />';
								//$ret = $db->query("UPDATE ?n SET descr=?s,to_orders=?i,percent_img=?i WHERE id=?i",'size_items',$name_item,$item_visible,$img,$id_item);
								//$ret = $db->query("UPDATE ?n SET to_orders=?i,percent_img=?i WHERE id=?i",'size_items',$item_visible,$img,$id_item);
								//$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ua' and id=?i",'size_items_descr',$descr_item_ua,$id_item);
								//$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ru' and id=?i",'size_items_descr',$descr_item_ru,$id_item);

								$i = 0;
								unset ($id_item, $img, $item_visible, $descr_item_ua, $descr_item_ru);
							}
						}
					}
*/
					$ret = $db->query("select t2.id_item,IFNULL(t1.to_orders,0),group_concat(t2.name_item ORDER BY t2.lang desc separator ' / ') name_items
						  from items t1, name_items t2
						  where 1=1
						  and t1.id_item = t2.id_item
						  GROUP By id_item,t1.to_orders
              order by 2 desc, 3");
					if ($ret)
					{
						print '<form id="new_data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Категория товаров</th>
						<th scope="col">Товар</th>
						<th scope="col">Количество</th>
						<th scope="col">Цена</th>
						</tr></thead><tbody>';
						print '<tr>';

						print '<td><select size="1" name="num_id_item" id="num_id_item" onchange="startAjax('.$section.',0,this.id)" form="new_data" >';
			    
						while ($row = $ret->fetch_row())
				    {
				    	print '<option value="'.$row[0].'">'.$row[2].'</option>';
				    }
				    //mysqli_free_result($ret);
						
						print '</select></td>';
						print '<td><select size="1" name="num_id" id="num_id" onchange="startAjax('.$section.','.$row[0].',this.id,null)" form="new_data" >';
						print '</select></td>';
						print '<td><script>
						startAjax('.$section.',0,"num_id_item",null);
						</script><input type="text" name="count" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Количество" value="" form="new_data" /></td>';
						print '<td><input type="text" name="price" style="width: 150px; border: solid 1px #EFCF00;" placeholder="Цена" value="" form="new_data" /></td>';
					  print '</tr>';
				    print '</tbody></table>';
				    print '<p><input type="submit" name="new_save" form="new_data" value="Новая цена"></p></form><br /><br />';

				  	$ret->close();
					}

					$ret = $db->query("select t3.id_item,t3.name_items,t3.to_orders,t3.id, t3.descr,IFNULL(t4.count, 0),IFNULL(t4.price, 0)
						from (
							select t1.id_item,t1.name_items,t2.to_orders,t3.id, t3.descr
						    from (
						      select t2.id_item,group_concat(t2.name_item ORDER BY t2.lang desc separator ' / ') name_items
						      from items t1, name_items t2
						      where t1.to_orders = 1
						      and t1.id_item = t2.id_item
						      GROUP By id_item
						    ) t1
						    left join (
						        select * from size_items
						    ) t2 on t1.id_item = t2.id_item
						    left join (
						      select id, group_concat(descr ORDER BY lang desc separator ' / ') descr
						      from size_items_descr
						      group by id
						    ) t3 on t3.id = t2.id
						) t3, (
						    select *
						    from price
						    where date_to is null
						) t4
						where 1=1
						and t3.id = t4.id_size_item");
					if ($ret)
					{
						//print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Категория товаров</th>
						<th scope="col">Товар</th>
						<th scope="col" style="width: 120px;">Статус отображения товара</th>
						<th scope="col">Количество</th>
						<th scope="col">Цена</th>
						</tr></thead><tbody>';

						while ($row = $ret->fetch_row())
				    {
				    	print '<tr><td>'.$row[1].'</td>';
				    	print '<td>'.$row[4].'<input type="hidden" name="'.$row[3].'_id_size_item" value="'.$row[3].'" form="data"/></td>';
				    	//<input type="text" name="'.$row[3].'_descr_item_ua" style="width: 220px; border: solid 1px #f0f0f8;" placeholder="Название товара" value="
				    	if ($row[2] == "1") {print '<td>Доступно</td>';}
				    	if ($row[2] == "0") {print '<td>Не доступно</td>';}
				    	
				    	print '<td><input type="text" name="'.$row[3].'_count" id="'.$row[3].'_count" style="width: 100px; border: solid 1px #EFCF00;" placeholder="Количество" maxlength="5" value="'.$row[5].'" onchange="startAjax('.$section.','.$row[3].',this.id,null)" /></td>';
				    	print '<td><input type="text" name="'.$row[3].'_price" id="'.$row[3].'_price" style="width: 220px; border: solid 1px #EFCF00;" placeholder="Цена" value="'.$row[6].'" onchange="startAjax('.$section.','.$row[3].',this.id,null)" /></td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" name="save" form="data" value="Сохранить изменения"></p></form>';
				    //print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '5')
				{
					/*
					$i = 0;
					foreach($_POST as $value) 
					{
						if ($i == 0)
						{
							$id_user = $value;
					  	$i++;
						}
						else
						{
							$ret = $db->query("UPDATE ?n SET status_user=?i WHERE id_user=?i",'users',$value,$id_user);
							$i = 0;
						}
					}
*/
					//print "Управление пользователями<br />";
					$ret = $db->query("select username,email,tlf,text_message,record_dt from message order by record_dt desc");

					if ($ret)
					{
						print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Имя пользователя</th>
						<th scope="col">Email пользователя</th>
						<th scope="col">Телефон</th>
						<th scope="col">Сообщение</th>
						<th scope="col">Время</th>
						</tr></thead><tbody>';
						//print '<div style="width: 1000px; border: 0px solid #ECA013;"><div class = "td1">Имя пользователя</div><div class = "td1">Email пользователя</div><div class = "td1">Телефон</div><div class = "td1">Сообщение</div><div class = "td1">Время</div></br>';
						while ($row = $ret->fetch_row())
				    {
				    	//print '<input type="hidden" name="usr_'.$row[4].'" value="'.$row[4].'" form="data"/>';
				    	print '<tr><td>'.$row[0].'</td>';
				    	print '<td>'.$row[1].'</td>';
				    	print '<td>'.$row[2].'</td>';
				    	print '<td>'.$row[3].'</td>';
				    	print '<td>'.$row[4].'</td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" form="data" value="Сохранить изменения"></p>';
				    //print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '6')
				{
					$ret = $db->query("select ip_addr,login_user,request,substring(referer,1,60),user_agent,time from visiting_day left join users on user_id = id_user where upper(user_agent) not like '%BOT%' and time > DATE_SUB(now(), INTERVAL 3 DAY) order by time desc;");
					
					//http://rest.db.ripe.net/search?source=ripe&query-string=62.205.159.142

					if ($ret)
					{
						print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Адрес</th>
						<th scope="col">Логин</th>
						<th scope="col">Страница</th>
						<th scope="col">Предыдущая страница</th>
						<th scope="col">Браузер</th>
						<th scope="col" style="width: 140px;">Время</th>
						</tr></thead><tbody>';
						//print '<div style="width: 1000px; border: 0px solid #ECA013;"><div class = "td1">Имя пользователя</div><div class = "td1">Email пользователя</div><div class = "td1">Телефон</div><div class = "td1">Сообщение</div><div class = "td1">Время</div></br>';
						while ($row = $ret->fetch_row())
				    {
				    	//print '<input type="hidden" name="usr_'.$row[4].'" value="'.$row[4].'" form="data"/>';
				    	print '<tr><td>'.$row[0].'</td>';
				    	print '<td>'.$row[1].'</td>';
				    	print '<td>'.$row[2].'</td>';
				    	print '<td>'.$row[3].'</td>';
				    	print '<td>'.$row[4].'</td>';
				    	print '<td style="width: 140px;">'.$row[5].'</td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" form="data" value="Сохранить изменения"></p>';
				    //print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '7')
				{
					$ret = $db->query("select links,lang,title,description,keywords from pages order by 3,1,2 desc;");

					if ($ret)
					{
						print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Страница</th>
						<th scope="col">Язык</th>
						<th scope="col">TITLE</th>
						<th scope="col">DESCRIPTION</th>
						<th scope="col">KEYWORDS</th>
						</tr></thead><tbody>';
						//print '<div style="width: 1000px; border: 0px solid #ECA013;"><div class = "td1">Имя пользователя</div><div class = "td1">Email пользователя</div><div class = "td1">Телефон</div><div class = "td1">Сообщение</div><div class = "td1">Время</div></br>';
						while ($row = $ret->fetch_row())
				    {
				    	//print '<input type="hidden" name="usr_'.$row[4].'" value="'.$row[4].'" form="data"/>';
				    	print '<tr><td>'.$row[0].'</td>';
				    	print '<td>'.$row[1].'</td>';
				    	print '<td><textarea rows="8" cols="30" id="'.$row[0].'_'.$row[1].'_title" onchange="startAjax('.$section.',\''.$row[0].'\',this.id)">'.$row[2].'</textarea></td>';
				    	print '<td><textarea rows="8" cols="30" id="'.$row[0].'_'.$row[1].'_descr" onchange="startAjax('.$section.',\''.$row[0].'\',this.id)">'.$row[3].'</textarea></td>';
				    	print '<td><textarea rows="8" cols="30" id="'.$row[0].'_'.$row[1].'_key" onchange="startAjax('.$section.',\''.$row[0].'\',this.id)">'.$row[4].'</textarea></td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" form="data" value="Сохранить изменения"></p>';
				    //print '</div>';

				    $ret->close();
					}
					else
					{
						print $ret."<br />";
				    print 'Возникла ошибка.<br />';
					}
				}
				elseif ($section == '8')
				{
					$ret = $db->query("select id_sentence,max(case when lang = 'ua' then sentence end) ua, max(case when lang = 'ru' then sentence end) ru
						from multilanguage
						group by id_sentence
						order by id_sentence");
				
					if ($ret)
					{
						print '<form id="data" action="adm.php?'.$_SERVER['QUERY_STRING'].'" method="post"></form>';
						print '<table><thead><tr>
						<th scope="col">Ид</th>
						<th scope="col">Фраза на украинском</th>
						<th scope="col">Фраза на русском</th>
						</tr></thead><tbody>';
						while ($row = $ret->fetch_row())
				    {
				    	//print '<input type="hidden" name="usr_'.$row[4].'" value="'.$row[4].'" form="data"/>';
				    	print '<tr><td>'.$row[0].'</td>';
				    	print '<td>'.$row[1].'</td>';
				    	print '<td>'.$row[2].'</td>';
				    	print '</tr>';
				    }
				    print '</tbody></table>';
				    //print '<p><input type="submit" form="data" value="Сохранить изменения"></p>';
				    //print '</div>';

				    $ret->close();
					}


/*
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

*/
				}
				elseif ($section == '9')
				{
				}
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
