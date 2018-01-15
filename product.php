<?php
header('Content-Type: text/html; charset=windows-1251');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Форма</TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1251" />
</HEAD>

<?php 
ini_set('display_errors',true);
function verify_array_num($input)
{//функция проверки есть ли во введенной строке буква
//	$verify_status = false;
	$max = strlen($input);
	$output = "";
	$data_text = "0123456789,";
	
	for ($i=0; $i<$max; $i++)
	{
		if ( strstr($data_text, $input{$i} ) )
		{
//			$verify_status = true;
//			break;
			$output .= $input{$i};
		}
	}
	return $output;
}

var_dump($_POST);
//print "<br>";

$keys = array_keys($_POST);
$del = '';
$edit = '';
$edit_ = '';
$addpart = '';
$addlaying = '';
foreach ($keys as $one_str)
{
	if (strpos($one_str, "edit") !== false)
	{
	  $edit = substr($one_str, 4);
	  $edit_ = "val" . $edit;
	  $edit_ = $_POST[$edit_];
	}
	//if (strpos($one_str, "val") !== false)
	//{
	  //$edit_ = substr($one_str, 3);
	//}
	
	if (strpos($one_str, "del") !== false)
	{
	  $del = substr($one_str, 3);
	}

	if (strpos($one_str, "addpart") !== false)
	{
	  $addpart = stripslashes($_POST['addpart']);
	}
	
	
}

print $del . "<br/>";
print $edit . "<br/>";
print $edit_ . "<br/>";
print $addpart . "<br/>";
print $addlaying . "<br/>";
// mysqli
$mysqli = new mysqli("127.0.0.1", "livefooddbuser", "54eweuP39kCCL", "livefooddb");
//$mysqli = new mysqli("localhost", "ovm", "MX6fGajvxb6g", "livefood_data");
/* проверка соединения */
if ($mysqli->connect_errno)
{
    printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
    exit();
}

/* изменение набора символов на utf8 */

if (!$mysqli->set_charset("cp1251")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
} else {
    //printf("Текущий набор символов: %s<br>", $mysqli->character_set_name());
}



if ($del != "")
{// кнопка была нажата
	//$del_part = stripslashes($_POST['del_part']);

	$query = "UPDATE products SET out_date = CURDATE() WHERE id_part=$del";
	//print"$query<br>";
	$result = $mysqli->query($query);
}

if ($addpart != "")
{// кнопка была нажата
	$type = stripslashes($_POST['type']);
	$type = ereg_replace("'", "", $type);
	$type = ereg_replace('"', "", $type);

	$type_1 = stripslashes($_POST['type_1']);
	$type_1 = ereg_replace("'", "", $type_1);
	$type_1 = ereg_replace('"', "", $type_1);
	$type_1 = verify_array_num ($type_1);
		
	$part = stripslashes($_POST['part']);
	$part = ereg_replace("'", "", $part);
	$part = ereg_replace('"', "", $part);
	$part = verify_array_num ($part);

	$part_disc = stripslashes($_POST['part_disc']);
	$part_disc = ereg_replace("'", "", $part_disc);
	$part_disc = ereg_replace('"', "", $part_disc);
	
	$query = "insert into products (id_item, birth_date, comments";
	if ($type_1 != "" )
	{
		$query .= ",id_laying) values ($type,$part,'$part_disc',$type_1)";
	}
	else
	{
		$query .= ") values ($type,$part,'$part_disc')";
	}
	
	print"$query<br>";
	$result = $mysqli->query($query);

	$query = "UPDATE laying SET out_date = $part WHERE id_part=$type_1";
	print"$query<br>";
	$result = $mysqli->query($query);
}

if ($addlaying != "")
{
	$type_laying = stripslashes($_POST['type_laying']);
	$type_laying = ereg_replace("'", "", $type_laying);
	$type_laying = ereg_replace('"', "", $type_laying);
	
	$laying = stripslashes($_POST['laying']);
	$laying = ereg_replace("'", "", $laying);
	$laying = ereg_replace('"', "", $laying);
	$laying = verify_array_num ($laying);

	$laying_disc = stripslashes($_POST['laying_disc']);
	$laying_disc = ereg_replace("'", "", $laying_disc);
	$laying_disc = ereg_replace('"', "", $laying_disc);

	$type_laying_1 = stripslashes($_POST['type_laying_1']);
	$type_laying_1 = ereg_replace("'", "", $type_laying_1);
	$type_laying_1 = ereg_replace('"', "", $type_laying_1);
	$type_laying_1 = verify_array_num ($type_laying_1);

	$query = "insert into laying (id_item, in_date, comments";
	if ($type_laying_1 != "" )
	{
		$query .= ", id_products) values ($type_laying,$laying,'$laying_disc',$type_laying_1)";
	}
	else
	{
		$query .= ") values ($type_laying,$laying,'$laying_disc')";
	}
//  out_date,
	print"$query<br>";
	$result = $mysqli->query($query);

}

	$query = "select products.id_part, items.name_item, products.birth_date, products.comments from products, items where products.out_date is null and products.id_item=items.id_item order by products.birth_date desc";
	//print"$query<br>";
	print "<div class=\"left_block\">";
	print "<FORM name=\"close\" method=\"POST\" action=\"\">";
	print "<TABLE width=\"100%\" align=\"center\" border=\"1\"><TR align=\"center\"><TD colspan=\"4\">Открытые партии</TD></TR>";
	$cricket = "";

	if ($result = $mysqli->query($query))
	{
	    /* выборка данных и помещение их в массив */
	    while ($row = $result->fetch_row())
	    {
	        print "<TR><TD>$row[1]</TD><TD>$row[2]</TD><TD><input name=\"val$row[0]\" type=\"text\" value=\"$row[3]\" style=\"width:445px\" /></TD><TD><input type=\"submit\" value=\"Редактировать\" name=\"edit$row[0]\"></TD><TD><input type=\"submit\" value=\"Закрыть\" name=\"del$row[0]\"></TD></TR>";
	        if ($row[1]=="Сверчок")
	        {
	        	$cricket .= "<option value=\"$row[0]\">$row[2]</option>";
	        }
	    }
	    /* очищаем результирующий набор */
	    $result->close();
	}
	print "</TABLE></FORM></div>";

	$query = "select id_part,in_date, id_item from laying where out_date is null order by in_date";
	//print"$query<br>";
	$cricket_laying = "";

	if ($result = $mysqli->query($query))
	{
	    /* выборка данных и помещение их в массив */
	    while ($row = $result->fetch_row())
	    {
	        if ($row[2]==1)
	        {
	        	$cricket_laying .= "<option value=\"$row[0]\">$row[1]</option>";
	        }
	    }
	    /* очищаем результирующий набор */
	    $result->close();
	}

?>

<script language="JavaScript">
<!-- hide

function add_laying_display()
{
	var val = document.forms["add_laying"].type_laying.value
	//alert(val);
	if (val == 1)
	{
		document.forms["add_laying"].type_laying_1.style.display = "";

		for(var i=0;i<document.all.add_laying_t1.rows.length; i++)
		{
			if (document.all.add_laying_t1.rows[i].id == "hideRow")
			{
				document.all.add_laying_t1.rows[i].style.display = "";
			}
		}
	}
	else
	{
		document.forms["add_laying"].type_laying_1.style.display = "none";
		for(var i=0;i<document.all.add_laying_t1.rows.length; i++)
		{
			if (document.all.add_laying_t1.rows[i].id == "hideRow")
			{
				document.all.add_laying_t1.rows[i].style.display = "none";
			}
		}
	}
}

function add_part_display()
{
	var val = document.forms["add_part"].type.value
	//alert(val);
	if (val == 1)
	{
		document.forms["add_part"].type_1.style.display = "";

		for(var i=0;i<document.all.add_part_t1.rows.length; i++)
		{
			if (document.all.add_part_t1.rows[i].id == "hideRow")
			{
				document.all.add_part_t1.rows[i].style.display = "";
			}
		}
	}
	else
	{
		document.forms["add_part"].type_1.style.display = "none";
		for(var i=0;i<document.all.add_part_t1.rows.length; i++)
		{
			if (document.all.add_part_t1.rows[i].id == "hideRow")
			{
				document.all.add_part_t1.rows[i].style.display = "none";
			}
		}
	}
	
	//alert(val1);
	/*
	myWin= open("", "displayWindow", "width=300,height=400,status=no,toolbar=no,menubar=no,location=no");
//	var total = document.forms[1].xml_list_1.value;
	myWin.document.open();
	myWin.document.write("<html><head><title>Выбор родительского стада</title></head><body><textarea rows=\"23\" cols=\"33\">");
	//myWin.document.write(val);
	myWin.document.write("</textarea></body></html>");
	myWin.document.close();
	*/
	//newwindow=window.open("", "displayWindow", "width=300,height=400,status=no,toolbar=no,menubar=no,location=no");
	//newwindow.close();
}// --></script>

<?php 
	print "<div class=\"center_block\">";
	print "<FORM name=\"add_part\" method=\"POST\" action=\"\">";
	print "<TABLE id=\"add_part_t1\" width=\"100%\" align=\"center\" border=\"1\"><TR align=\"center\"><TD>Добавить партию</TD></TR>";
	print "<TR><TD>Выберите вид насекомого (товара):</TD></TR>";
	print "<TR><TD><select size=\"4\" name=\"type\" onchange=\"add_part_display()\">";

	$query = "select id_item, name_item from items";
	print $query."<br>";
	if ($result = $mysqli->query($query))
	{
	    // выборка данных и помещение их в массив
	    while ($row = $result->fetch_row())
	    {
	        print "<option value=\"$row[0]\">$row[1]</option>";
	    }
	    // очищаем результирующий набор
	    $result->close();
	}
  print "</select></TD></TR><TR id='hideRow' style=\"display:none\"><TD>Выберите партию яйца:</TD></TR><TR id='hideRow' style=\"display:none\"><TD><select style=\"display:none\" size=\"4\" name=\"type_1\">";
  print $cricket_laying;
  print "</select></TD></TR><TR><TD><input name=\"part\" type=\"text\" placeholder=\"Введите номер партии (ггммдд)\" style=\"width:245px\"/></TD></TR>";
  print "<TR><TD><textarea name=\"part_disc\" placeholder=\"Введите дополнительную информацию о партии\" style='width:245px;height:74px;' /></textarea></TD></TR>";
  print "<TR><TD align=\"center\"><input type=\"submit\" value=\"Добавить\" name=\"addpart\"></TD></TR>";
	print "</TABLE></FORM></div>";



	print "<div class=\"right_block\">";
	print "<FORM name=\"add_laying\" method=\"POST\" action=\"\">";
	print "<TABLE id=\"add_laying_t1\" width=\"100%\" align=\"center\" border=\"1\"><TR align=\"center\"><TD>Добавить кладку яиц</TD></TR>";
	print "<TR><TD>Выберите вид насекомого (товара):</TD></TR>";
	print "<TR><TD><select size=\"4\" name=\"type_laying\" onchange=\"add_laying_display()\">";

	$query = "select id_item, name_item from items";
	print $query."<br>";
	if ($result = $mysqli->query($query))
	{
	    while ($row = $result->fetch_row())
	    {
	        print "<option value=\"$row[0]\">$row[1]</option>";
	    }
	    $result->close();
	}
  print "</select></TD></TR><TR id='hideRow' style=\"display:none\"><TD>Выберите родителей:</TD></TR><TR id='hideRow' style=\"display:none\"><TD><select style=\"display:none\" size=\"4\" name=\"type_laying_1\">";
  print $cricket;
  print "</select><TR><TD><input name=\"laying\" type=\"text\" placeholder=\"Введите дату откладки яиц (ггммдд)\" style=\"width:245px\" /></TD></TR>";
  print "<TR><TD><textarea name=\"laying_disc\" placeholder=\"Введите дополнительную информацию\" style='width:245px;height:74px;' /></textarea></TD></TR>";
  print "<TR><TD align=\"center\"><input type=\"submit\" value=\"Добавить\" name=\"addlaying\"></TD></TR>";
	print "</TABLE></FORM></div>";

/*
	print "<div class=\"stat_block\"><FORM name=\"stat\" method=\"POST\" action=\"\">";
	print "<TABLE width=\"100%\" align=\"center\" border=\"1\"><TR align=\"center\"><TD colspan=\"4\">Статистика</TD></TR>";
	print "<TR><TD>11</TD><TD>2</TD><TD>3</TD><TD><input type=\"submit\" value=\"Добавить\" name=\"delrow[0]\"></TD></TR>";
	print "</TABLE></FORM></div>";

<input type=button value=\"Получить список персонажей для добавления в КПК\" onClick=\"openWin3()\">

*/


	/* закрываем подключение */
	$mysqli->close();
?>