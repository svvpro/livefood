<?php
	session_start();
	// подключаемся к бд
	include_once $_SERVER['DOCUMENT_ROOT'].'/safemysql.class.php';
	$db = new SafeMysql();	

	//var_dump($_POST);
	//print "<br />";	

	if (isset($_POST['section']))
	{
		$section = stripslashes($_POST['section']);
		//$section = ereg_replace("'", "", $section);
		//$section = ereg_replace('"', "", $section);
	}
	if (isset($_POST['par1']))
	{
		$par1 = stripslashes($_POST['par1']);
		//$par1 = ereg_replace("'", "", $par1);
		//$par1 = ereg_replace('"', "", $par1);
	}
	if (isset($_POST['par2']))
	{
		$par2 = stripslashes($_POST['par2']);
		//$par2 = ereg_replace("'", "", $par2);
		//$par2 = ereg_replace('"', "", $par2);
	}
	if (isset($_POST['par3']))
	{
		$par3 = stripslashes($_POST['par3']);
		//$par3 = ereg_replace("'", "", $par3);
		//$par3 = ereg_replace('"', "", $par3);
	}

	if ($section == '1')
	{
		$ret = $db->query("UPDATE ?n SET status_user=?i WHERE id_user=?i",'users',$par3,$par1);
	}
	elseif ($section == '2')
	{
		if (strpos($par2, 'item_visible') !== false)
		{
			if (strpos($par2, 'no_item_visible') !== false)
			{
				$ret = $db->query("UPDATE ?n SET to_orders=?i WHERE id_item=?i",'items',0,$par1);
			}
			else
			{
				$ret = $db->query("UPDATE ?n SET to_orders=?i WHERE id_item=?i",'items',1,$par1);
			}
		}
		elseif (strpos($par2, 'category') !== false)
		{
			$ret = $db->query("UPDATE ?n SET category=?s WHERE id_item=?i",'items',$par3,$par1);
		}
		elseif (strpos($par2, 'img') !== false)
		{
			$ret = $db->query("UPDATE ?n SET img=?s WHERE id_item=?i",'items',$par3,$par1);
		}
  	elseif (strpos($par2, 'name_item_ua') !== false)
		{
			$ret = $db->query("UPDATE ?n SET name_item=?s WHERE lang = 'ua' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'name_item_ru') !== false)
		{
			$ret = $db->query("UPDATE ?n SET name_item=?s WHERE lang = 'ru' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'text_ua') !== false)
		{
			$ret = $db->query("UPDATE ?n SET content=?s WHERE lang = 'ua' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'text_ru') !== false)
		{
			$ret = $db->query("UPDATE ?n SET content=?s WHERE lang = 'ru' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'txt2_ua') !== false)
		{
			$ret = $db->query("UPDATE ?n SET content_2=?s WHERE lang = 'ua' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'txt2_ru') !== false)
		{
			$ret = $db->query("UPDATE ?n SET content_2=?s WHERE lang = 'ru' and id_item=?i",'name_items',$par3,$par1);
		}
		elseif (strpos($par2, 'new_save_buttom2') !== false)
		{
			$ret = $db->query("begin;");
			$ret = $db->query("INSERT INTO items (to_orders) values (0);");
			$ret = $db->query("select max(id_item) from items;");
			if ($ret)
			{
				while ($row = $ret->fetch_row())
		    {
		    	$id_item = $row[0];
		    }
		    mysqli_free_result($ret);
		    $ret = $db->query("INSERT INTO ?n (id_item,name_item,lang) values (?i,?s,?s);",'name_items',$id_item,$par1,'ua');
		    $ret = $db->query("INSERT INTO ?n (id_item,name_item,lang) values (?i,?s,?s);",'name_items',$id_item,$par3,'ru');
			}
			//$ret = $db->query("rollback;");
			$ret = $db->query("commit;");
			$i = 0;
			unset ($par1,$par3);
		}
	}
	elseif ($section == '3')
	{
		if (strpos($par2, 'item_visible') !== false)
		{
			if (strpos($par2, 'no_item_visible') !== false)
			{
				$ret = $db->query("UPDATE ?n SET to_orders=?i WHERE id=?i",'size_items',0,$par1);
			}
			else
			{
				$ret = $db->query("UPDATE ?n SET to_orders=?i WHERE id=?i",'size_items',1,$par1);
			}
		}
		elseif (strpos($par2, 'img') !== false)
		{
			$ret = $db->query("UPDATE ?n SET percent_img=?i WHERE id=?i",'size_items',$par3,$par1);
		}
  	elseif (strpos($par2, 'descr_item_ua') !== false)
		{
			$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ua' and id=?i",'size_items_descr',$par3,$par1);
		}
		elseif (strpos($par2, 'descr_item_ru') !== false)
		{
			$ret = $db->query("UPDATE ?n SET descr=?s WHERE lang = 'ru' and id=?i",'size_items_descr',$par3,$par1);
		}
		elseif (strpos($par2, 'new_save_buttom3') !== false)
		{
			$inputJSON = json_decode( $par3, TRUE ); //convert JSON into array
			
			//var_dump($inputJSON);
			$num_id_item = $inputJSON['id_item'];
			$new_descr_item_ua = $inputJSON['new_descr_item_ua'];
			$new_descr_item_ru = $inputJSON['new_descr_item_ru'];
			
			//print $num_id_item . "\n" . $new_descr_item_ua . "\n" . $new_descr_item_ru;
			
			
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
		}

	}
	elseif ($section == '4')
	{
		if (strpos($par2, 'num_id_item') !== false)
		{
			$ret = $db->query("select t1.id,group_concat(descr ORDER BY lang desc separator ' / ') descr
				from size_items t1, size_items_descr t2
				where 1=1
				and t1.id = t2.id
				and id_item = ?i
				group by t1.id
				order by 1", $par3);
			
			if ($ret)
			{
				while ($row = $ret->fetch_row())
			  {
			  	print $row[1].','.$row[0].',;';
			  }
			}
			
		}
	}
	elseif ($section == '7')
	{
		if (strpos($par2, 'ua_title') !== false)
		{
			$ret = $db->query("UPDATE ?n SET title=?s WHERE lang = 'ua' and links = ?s",'pages',$par3,$par1);
		}
  	elseif (strpos($par2, 'ru_title') !== false)
		{
			$ret = $db->query("UPDATE ?n SET title=?s WHERE lang = 'ru' and links = ?s",'pages',$par3,$par1);
		}
  	elseif (strpos($par2, 'ua_descr') !== false)
		{
			$ret = $db->query("UPDATE ?n SET description=?s WHERE lang = 'ua' and links = ?s",'pages',$par3,$par1);
		}
  	elseif (strpos($par2, 'ru_descr') !== false)
		{
			$ret = $db->query("UPDATE ?n SET description=?s WHERE lang = 'ru' and links = ?s",'pages',$par3,$par1);
		}
  	elseif (strpos($par2, 'ua_key') !== false)
		{
			$ret = $db->query("UPDATE ?n SET keywords=?s WHERE lang = 'ua' and links = ?s",'pages',$par3,$par1);
		}
  	elseif (strpos($par2, 'ru_key') !== false)
		{
			$ret = $db->query("UPDATE ?n SET keywords=?s WHERE lang = 'ru' and links = ?s",'pages',$par3,$par1);
		}
	}

	//$db -> mysqli_close();
?>