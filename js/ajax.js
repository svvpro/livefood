function startAjax(section,par1,par2)
{
	var text_save = document.getElementById(par2).value;
	//alert ( section + ' - ' + par1 + ' - ' + par2 + ' - ' + text_save);

	if (par2 == 'num_id_item')
	{
		var objSel = document.getElementById("num_id");
		objSel.options.length = 0;
	}

	if (par2 == 'new_save_buttom2')
	{
		par1 = document.getElementById("new_name_item_ua").value;
		text_save = document.getElementById("new_name_item_ru").value;
		//alert ( section + ' - ' + par1 + ' - ' + par2 + ' - ' + text_save);
	}

	if (par2 == 'new_save_buttom3')
	{
		
		//alert( document.getElementById("num_id_item").value);
		text_save = { "new_descr_item_ua": document.getElementById("new_descr_item_ua").value,
			"new_descr_item_ru": document.getElementById("new_descr_item_ru").value,
			"id_item":document.getElementById("num_id_item").value };
		//user = JSON.parse(user);
		text_save = JSON.stringify(text_save);
		//alert ( section + ' - ' + par1 + ' - ' + par2 + ' - ' + text_save + ' - ' + str);
	}

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
				if (section == 3)
				{ //ответ с сервера
					//alert ( section + ' - ' + par1 + ' - ' + par2 + ' - ' + text_save + ' - ' + request.responseText);
				}
				if (par2 == 'num_id_item')
				{
					var str = request.responseText; // ищем в этой строке
					var target = ";"; // цель поиска
					var pos = 0;
					while (true) {
					  var foundPos = str.indexOf(target, pos);
					  
					  if (foundPos == -1) break;
					  
					  var one_str = str.substring(pos, foundPos);
					  //alert( one_str ); // нашли на этой позиции
					  pos = foundPos + 1; // продолжить поиск со следующей

						var subtarget = ","; // цель поиска
						var subpos = 0;
						while (true)
						{
						  var foundPos = one_str.indexOf(subtarget, subpos);
						  
						  if (foundPos == -1) break;
						  
						  //alert ( one_str.substring(subpos, foundPos) );
						  if (subpos == 0)
						  {
						  	var first_str = one_str.substring(subpos, foundPos);
						  }
						  else
						  {
						  	var second_str = one_str.substring(subpos, foundPos);
						  }
						  
						  subpos = foundPos + 1; // продолжить поиск со следующей
						}
						objSel.options[objSel.options.length] = new Option(first_str, second_str);
					}
				}

					//document.getElementById("login_welcome").innerHTML = request.responseText;
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

	request.open("POST","http://www.livefood.in.ua/adm_ajax.php",true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("section=" + section + "&par1=" + par1 + "&par2=" + par2 + "&par3=" + text_save);

}
  function print_console(text)
  {
    document.getElementById("login_welcome").innerHTML += text; 
	}