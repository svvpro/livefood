<?php
class auth {

function __construct($opt)
{
	// подключаемся к бд
	@$this->db = $opt;
	//new SafeMysql();

	unset($opt); // I am paranoid
	
	@$this->ch_loc = 0;

	if (strpos($_SERVER['REQUEST_URI'], '/ru/') !== false)
	{
		@$this->lang = 'ru';
	}
	elseif (strpos($_SERVER['REQUEST_URI'], '/ua/') !== false)
	//else
	{
		@$this->lang = 'ua';
	}
	else
	{
		// В ссылке нет языка. Нужно перейти на страницу с языком
		//print $_SERVER['REQUEST_URI'] . "<br />";
		@$this->lang = 'ru';
	}


	if ( isset($_SERVER['HTTP_REFERER']) )
	{
		if ( strpos($_SERVER['HTTP_REFERER'], 'livefood.in.ua') !== false )
		{
			if (strpos($_SERVER['HTTP_REFERER'], '/ru/') !== false)
			{
				@$this->lang_ref = 'ru';
			}
			//elseif (strpos($_SERVER['HTTP_REFERER'], '/ua/') !== false)
			else
			{
				@$this->lang_ref = 'ua';
			}

		}
		else
		{
			@$this->lang_ref = @$this->lang;
		}
	}
	else
	{
		@$this->lang_ref = @$this->lang;
	}

//print @$this->lang . "<br />";
//print @$this->lang_ref . "<br />";
}
        ###
        #        Регистрация
function reg($login, $passwd, $mail, $tlf)
{
	$table = "users";
	$passwd = md5($passwd.'her8k4'); //~ хеш пароля с солью
	
	$tlf = substr('38'.str_replace(array("(", ")", " ", "-"), "", $tlf),0,13); // номер телефона
	
	$ret = $this->db->query("INSERT INTO ?n (login_user, passwd_user, mail_user, tlf_user, status_user) VALUES (?s,?s,?s,?s,?i)",$table,$login,$passwd,$mail,$tlf,1);
	//print "module->reg: ret = " . $ret . "<br />";
	
	//, user_agent, ip_addr, last_login
	//,$_SERVER['HTTP_USER_AGENT'],$_SERVER['REMOTE_ADDR']
	if ($ret == 1)
	{
		$ret = $this->db->query("SELECT id_user, status_user FROM ?n where login_user=?s",$table,$login);
		if ($ret)
		{
			while ($row = $ret->fetch_row())
	    {
	    	$id_user_db = $row[0];
	    	$status_user_db = $row[1];
	    }
	    $ret->close();

		  //~ пользователь найден в бд, логин совпадает с паролем
		  $_SESSION['id_user']=$id_user_db;
		  $_SESSION['login_user'] = $login;		
		  $_SESSION['status_user'] = $status_user_db;
		  
		  $hash_code = $this->generateCode(4);
		  $hash_code .= $id_user_db;
		  $hash_code .= ";";
		  $hash_code .= $login;
		  $hash_code .= ";";
		  $hash_code .= $status_user_db;
		  $hash_code .= ";";
		  $hash_code .= $this->generateCode(4);
		  $checksum = crc32($hash_code);
			$hash_code .= sprintf("%X", $checksum);
		  //print "hash_code".$hash_code."<br />";
			$hash_code = base64_encode($this->strcode($hash_code, '')); //mypassword
			//print "hash_code: ".$hash_code."<br />";
	
		  //~ ставим куки на 1 год
		  setcookie("livefood_in_ua", $hash_code, time()+3600*24*365,"/");
		  
		  $table = "session";
			$ret = $this->db->query("INSERT INTO ?n (id_user, user_agent_sess, ip_addr_sess, last_login_sess) VALUES (?i,?s,?s,now())",$table,$_SESSION['id_user'],$_SERVER['HTTP_USER_AGENT'],$_SERVER['REMOTE_ADDR']);
			//print "module->reg: ret = " . $ret . "<br />";
			if ($ret != 1)
			{
				print $ret."<br />";
		    print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
			}
			header("Location:/");
			return true;
		}
		else
		{
			print $ret."<br />";
	    print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
	    return false;
		}
	}
	else
	{
		$pos = strpos($ret, "Duplicate entry");
		//print "pos = ".$pos."<br />";
		
		if ($pos === false)
		{
			print $ret."<br />";
	    print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
		}
		else
		{
	    return 'Пользователь с таким логином уже существует. Введите другой логин';
	  }
	  return false;
	}
}


###
#        Проверка авторизации
function check()
{
	//var_dump($_SESSION);
	//var_dump($_SERVER['HTTP_REFERER']);
	//print "1 <br />";
	if ( isset($_SESSION['lang_user']) )
	{
		//print "2 <br />";
		if ( strpos($_SESSION['lang_user'], $this->lang) === false )
		{//Если язык из сессий и текущая страница не совпадают
			//print "3 <br />";
			if (!isset($_SERVER['HTTP_REFERER']))
			{
				//print "4 <br />";
				//$_SESSION['lang_user'] = $_SESSION['lang_user'];
			}
			elseif (strpos($_SERVER['HTTP_REFERER'], 'livefood.in.ua') !== false)
			{// пользователь сменил язык сайта - сохранить сессии, куки и БД
				//print "5 <br />";
				$_SESSION['lang_user'] = $this->lang;
			}
			//save cookie
			$this->save_cookie();
		}
		else
		{
			/*
			if ( isset($_SERVER['HTTP_REFERER']) )
			{
				if (strpos($_SERVER['HTTP_REFERER'], '/ru/') !== false)
				{
					print "ru". "<br />";
				}
				elseif (strpos($_SERVER['HTTP_REFERER'], '/ua/') !== false)
				{
					print "ua". "<br />";
				}
				else
				{
					print "???". "<br />";
				}
			}
			*/
			$this->save_cookie();
		}
	}
	else
	{
		//print "6 <br />";
		$_SESSION['lang_user'] = $this->lang;
	}

	if (isset($_SESSION['id_user']) and isset($_SESSION['login_user']) and $_SESSION['id_user']!="" and $_SESSION['login_user']!="" )
	{
		//~ проверяем наличие кук
		//print "<br />1<br />";
		if (isset($_COOKIE['livefood_in_ua']))
		{
			$data = $this->strcode(base64_decode($_COOKIE['livefood_in_ua']), '');
			$checksum_aski = substr($data,strlen($data)-8);
			$data = substr($data,0,strlen($data)-8);
			$checksum = crc32($data);
			if (sprintf("%X", $checksum) == $checksum_aski)
			{// checksum correct
				$data = substr($data,4,strlen($data)-8);
				if ($GLOBALS['debug'] == 1)
				{
					if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
					{
						echo "Не могу открыть файл debug.txt";
			      exit;
			    }
			    
					fwrite($handle, "read_cookie 1: ".$data."\n");
					//fwrite($handle, "\n");
					fclose($handle);
					unset($handle);
				}
				$array2 = json_decode($data, true);
				$array1 = $_SESSION;
				//$_SESSION = $array1;

				$result = [];
        foreach($array1 as $key => $value) 
        { 
            if(isset($array2[$key])) 
            { 
                if( $value !==  $array2[$key]) 
                { 
                     $result[$key] = $value; 
                } 
            }else 
            { 
                $result[$key] = $value; 
            } 
        }
        if (count($result) > 0)
        {
        	$this->save_cookie();
        }
			}
			else
			{// checksum not correct
				$this->save_cookie();
				//session_unset();
				//session_destroy();

				return false;

			}
		}
		return true;
	}
	elseif ($this->lang == $this->lang_ref)
	{
		//~ проверяем наличие кук
		//print "<br />1<br />";
		if (isset($_COOKIE['livefood_in_ua']))
		{
			//print "2<br />";
			$data = $this->strcode(base64_decode($_COOKIE['livefood_in_ua']), '');

			$checksum_aski = substr($data,strlen($data)-8);
			$data = substr($data,0,strlen($data)-8);
			$checksum = crc32($data);
			if (sprintf("%X", $checksum) == $checksum_aski)
			{// checksum correct
				$data = substr($data,4,strlen($data)-8);

				if ($GLOBALS['debug'] == 1)
				{
					if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
					{
						echo "Не могу открыть файл debug.txt";
			      exit;
			    }
			    
					fwrite($handle, "read_cookie 2: ".$data."\n");
					//fwrite($handle, "\n");
					fclose($handle);
					unset($handle);
				}

				$array1 = json_decode($data, true);
				if (count($array1) > 0)
				{
					$_SESSION = $array1;
				}
				$this->save_cookie();
/*
				else
				{
					$this->save_cookie();
				}
*/
				//var_dump($array1);
				//print "<br />";
				//print "<br />";
			}
			else
			{
				if ($GLOBALS['debug'] == 1)
				{
					if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
					{
						echo "Не могу открыть файл debug.txt";
			      exit;
			    }
			    
					fwrite($handle, "crc32 ERROR\n");
					fwrite($handle, "read_cookie 3: ".$data."\n");
					//fwrite($handle, "\n");
	
					//fwrite($handle, "_COOKIE: ".var_export($_COOKIE, true));
					//fwrite($handle, "\n");
					
					fclose($handle);
					unset($handle);
				}
				unset ($_SESSION['login_user']);
				unset ($_SESSION['id_user']);
				//unset ($_SESSION['lang_user']);
				unset ($_SESSION['status_user']);
				$this->save_cookie();
				session_unset();
				session_destroy();

				return false;

			}
		}
		else
		{
			//print "куков нет <br />";
			return false;
		}
	}	
}

###
#        Авторизация
function authorization()
{
	if ($GLOBALS['debug'] == 1)
	{
		if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
		{
			echo "Не могу открыть файл debug.txt";
	    exit;
	  }
	  
		fwrite($handle, "Start authorization");
		fwrite($handle, "\n");
		
		//fwrite($handle, "GLOBALS: ".var_export($GLOBALS, true));
		//fwrite($handle, "\n");
		
		fclose($handle);
		unset($handle);
	}
	$table = "users";
	$login = $_POST['login_auth'];
	$passwd = md5($_POST['password_auth'].'her8k4'); //~ хеш пароля с солью

	$ret = $this->db->query("SELECT id_user, status_user, lang_user FROM ?n where login_user=?s and passwd_user=?s",$table,$login,$passwd);
	if ( !$ret )
	{
		//print $ret."<br />";
    //print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';

	  //~ пользователь не найден в бд, или пароль не соответствует введенному
	  $_SESSION['error'] = $this->error_print($error);
		//print "Возникла ошибка при авторизации пользователя. Свяжитесь с администрацией<br />";
		return "Вы ввели неправильный логин или пароль.";
	  //return false;

	}
	else
	{
		while ($row = $ret->fetch_row())
    {
    	$id_user_db = $row[0];
    	$status_user_db = $row[1];
    	$lang_user_db = $row[2];
    }
    $ret->close();
    
    if ( (isset($id_user_db)) && ($id_user_db != "") )
    {
    	if ($lang_user_db == "")
    	{
    		$lang_user_db = $this->lang;
    	}
		  //~ пользователь найден в бд, логин совпадает с паролем
		  $_SESSION['id_user']=$id_user_db;
		  $_SESSION['login_user'] = $login;		
		  $_SESSION['status_user'] = $status_user_db;
		  $_SESSION['lang_user'] = $lang_user_db;
		  
		  //var_dump ($_SESSION);
		  
		  $this->save_cookie();
		  $table = "session";
			$ret = $this->db->query("UPDATE ?n SET user_agent_sess=?s, ip_addr_sess=?s, last_login_sess=now() WHERE id_user=?i",$table,$_SERVER['HTTP_USER_AGENT'],$_SERVER['REMOTE_ADDR'],$_SESSION['id_user']);
			//print "module->check: ret = " . $ret . "<br />";
	
			if ($ret != 1)
			{
				print $ret."<br />";
		    print 'Возникла ошибка при регистрации нового пользователя. Свяжитесь с администрацией<br />';
			}

		  return true;
		}
		return "Вы ввели неправильный логин или пароль.";
	}
}

###
#        Выход
function exit_user()
{
	if ($GLOBALS['debug'] == 1)
	{
		if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
		{
			echo "Не могу открыть файл debug.txt";
      exit;
    }
    
		fwrite($handle, "exit_user\n");
		fwrite($handle, "lang_ref: ".$this->lang_ref."\n");
		fwrite($handle, "lang: ".$this->lang."\n");
		//fwrite($handle, "\n");
		fclose($handle);
		unset($handle);
	}
	//~ разрушаем сессию, удаляем куки и отправляем на главную
	unset ($_SESSION['login_user']);
	unset ($_SESSION['id_user']);
	//unset ($_SESSION['lang_user']);
	unset ($_SESSION['status_user']);
	unset ($_SESSION['array_basket']);
	$this->save_cookie();
	session_unset();
	session_destroy();
	//unset ($_SERVER['HTTP_REFERER']);
}	
###
#        Восстановление пароля
function recovery_pass($login, $mail)
{
	$db = new mysql(); //~ создаем новый объект класса
	$login = $db->screening($login);
	$db_inf = $db->query("SELECT * FROM `users` WHERE `login_user`='".$login."';", 'accos', '');
	if ($db->query("SELECT * FROM `users` WHERE `login_user`='".$login."';", 'num_row', '')!=1)
	{
		//~ не найден такой пользователь
		$error[]='Пользователь с таким именем не найден';
		return $this->error_print($error);
	}
	else
	{
		//~ проверка email
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $error[]='Введен не корректный email';
		if ($mail != $db_inf['mail_user']) $error[]='Введенный email не соответствует введенному при регистрации ';
		if (!isset($error))
		{
			//~ восстанавливаем пароль
			$new_passwd = $this->generateCode(8);
			$new_passwd_sql = md5($new_passwd.'lol');
			$message = "Вы запросили восстановление пароля на сайте %sitename% для учетной записи ".$db_inf['login_user']." \nВаш новый пароль: ".$new_passwd."\n\n С уважением администрация сайта %sitename%.";
			if (mail($mail, "Восстановление пароля", $message, "From: webmaster@sitename.ru\r\n"."Reply-To: webmaster@sitename.ru\r\n"."X-Mailer: PHP/" . phpversion()))
			{
			  //~ почта отправлена, обновляем пароль в базе
			  $db->query("UPDATE `users` SET `passwd_user`='".$new_passwd_sql."' WHERE `id_user` = ".$db_inf['id_user'].";", '', '');
			  //~ все успешно - возвращаем положительный ответ
				return 'good';
			}
			else
			{
			  //~ ошибка при отправке письма
			  $error[]='В данный момент восстановление пароля не возможно, свяжитесь с администрацией сайта';
			  return $this->error_print($error);
			}
		}
		else return $this->error_print($error);
	}
}

###
#        Функция генерации случайной строки
function generateCode($length)
{ 
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"; 
	$code = ""; 
	$clen = strlen($chars) - 1;   
	while (strlen($code) < $length)
	{ 
		$code .= $chars[mt_rand(0,$clen)];   
	} 
	return $code; 
}

function strcode($str, $passw="")
{
	$salt = "Dn8*#2n!9j";
	$len = strlen($str);
	$gamma = '';
	$n = $len>100 ? 8 : 2;
	while( strlen($gamma)<$len )
	{
		$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
	}
	return $str^$gamma;
}


	###
	#        Формирование списка ошибок
	function error_print($error)
	{
        $r='<h2>Произошли следующие ошибки:</h2>'."\n".'<ul>';
        foreach($error as $key=>$value) {
                $r.='<li>'.$value.'</li>';
        }
        return $r.'</ul>';
	}

	function save_cookie()
	{
	  $json = json_encode($_SESSION);
		$json = $this->generateCode(4).$json;
		$json .= $this->generateCode(4);
		$checksum = crc32($json);
		$json .= sprintf("%X", $checksum);

		if ($GLOBALS['debug'] == 1)
		{
			if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
			{
				echo "Не могу открыть файл debug.txt";
	      exit;
	    }
	    
			fwrite($handle, "save_cookie: ".$json."\n");
			fwrite($handle, "lang_ref: ".$this->lang_ref."\n");
			fwrite($handle, "lang: ".$this->lang."\n");
			//fwrite($handle, "\n");
			fclose($handle);
			unset($handle);
		}

		$hash_code = base64_encode($this->strcode($json, '')); //mypassword
		//print "hash_code: ".$hash_code."<br />";
	  //~ ставим куки на 1 год
	  setcookie("livefood_in_ua", $hash_code, time()+3600*24*365,"/");
	  
	  if (isset($_SESSION['id_user']))
	  {
		  $table = "session";
			$ret = $this->db->query("UPDATE ?n SET user_agent_sess=?s, ip_addr_sess=?s, last_login_sess=now() WHERE id_user=?i",$table,$_SERVER['HTTP_USER_AGENT'],$_SERVER['REMOTE_ADDR'],$_SESSION['id_user']);
			//print "module->check: ret = " . $ret . "<br />";
	
			if ($ret != 1)
			{
				print $ret."<br />";
		    print 'Возникла ошибка. Свяжитесь с администрацией<br />';
			}

			$table = "users";
			$ret = $this->db->query("UPDATE ?n SET lang_user=?s WHERE id_user = ?i",$table,$_SESSION['lang_user'],$_SESSION['id_user']);
			if ($ret != 1)
			{
				print $ret."<br />";
		    print 'Возникла ошибка. Свяжитесь с администрацией<br />';
			}
		}

		$link = "";
		if ($this->ch_loc == 0)
		{
			if ( $this->lang == "" && $this->lang_ref == "" )
			{
				//$this->lang = "ru";
				//$this->lang_ref = "ru";
				if ($GLOBALS['debug'] == 1)
				{
					if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
					{
						echo "Не могу открыть файл debug.txt";
			      exit;
			    }
			    
					fwrite($handle, "Lang not set\n");
					//fwrite($handle, "\n");
					fclose($handle);
					unset($handle);
				}

			}
			
			if ( $this->lang != $this->lang_ref )
			{//нажали на ссылку смены языка ()
				//print "нажали на ссылку смены языка <br />";
				//print $_SERVER['REQUEST_URI'] . "<br />";
				//print substr($_SERVER['REQUEST_URI'], 4) . "<br />";
				//$link = 'Location: ';
				//$link .= $this->lang;
				//$link .= $_SERVER['REQUEST_URI'];
				//print $link . "<br />";
				//$link = "";
				//$link .= '/'.substr($_SERVER['REQUEST_URI'], 4);
			}
			elseif ( strpos($_SERVER['REQUEST_URI'], '?exit') !== false )
			{
				$link = 'Location: /';
				$link .= $this->lang;
	  		$link .= '/'.substr($_SERVER['REQUEST_URI'], 4,strpos($_SERVER['REQUEST_URI'], '?exit')-4);
			}
			elseif ( strpos($_SESSION['lang_user'], $this->lang) === false )
			{//Если язык из сессий и текущая страница не совпадают
				$link = 'Location: /';
				if (isset($_SESSION['lang_user']))
				{
					$link .= $_SESSION['lang_user'];
				}
				else
				{
					if ( $this->lang == "" )
					{
						$link .= "ru";
					}
					else
					{
						$link .= $this->lang;
					}
				}
				
				if ( strpos($_SERVER['REQUEST_URI'], '/ru/') !== false || strpos($_SERVER['REQUEST_URI'], '/ua/') !== false )
				{
					$link .= '/'.substr($_SERVER['REQUEST_URI'], 4);
				}
				else
				{
					$link .= $_SERVER['REQUEST_URI'];
				}
			}
			elseif (strpos($_SERVER['REQUEST_URI'], '/ru/') === false and strpos($_SERVER['REQUEST_URI'], '/ua/') === false)
			{
				// В ссылке нет языка. Нужно перейти на страницу с языком
				$link = 'Location: ';
				$link .= $this->lang;
				$link .= $_SERVER['REQUEST_URI'];
				//print $link . "<br />";
				//$link = "";
			}


/*
		$pos = strpos($_SERVER['REQUEST_URI'], '?');
		//print $pos."<br />";
		if ($pos !== false && $pos!=0)
		{
			//$link = 'Location: /'.$_SESSION['lang_user'].'/'.substr($_SERVER['REQUEST_URI'], 4,$pos-4);
			$link = 'Location: /';
			if (isset($_SESSION['lang_user'])) $link .= $_SESSION['lang_user'];
  		else $link .= "ru";
  		if (strpos($_SERVER['REQUEST_URI'], '?exit') !== false)
  			$link .= '/'.substr($_SERVER['REQUEST_URI'], 4,$pos-4);
  		else
  			$link .= '/'.substr($_SERVER['REQUEST_URI'], 4);
		}
		else
		{
			$link = 'Location: /';
			if (isset($_SESSION['lang_user'])) $link .= $_SESSION['lang_user'];
  		else $link .= "ru";
  		$link .= '/'.substr($_SERVER['REQUEST_URI'], 4);
		}
*/
			if ($GLOBALS['debug'] == 1)
			{
				if (!$handle = fopen($_SERVER['DOCUMENT_ROOT'].'/debug.txt', "a"))
				{
					echo "Не могу открыть файл debug.txt";
		      exit;
		    }
		    
				fwrite($handle, "save_cookie goto: ".$link."\n");
				//fwrite($handle, "\n");
				fclose($handle);
				unset($handle);
			}
	
			//session_unset();
			//session_destroy();
			
			if ($link != "")
			{
				header($link);
				$this->ch_loc++;
			}
		}
	}

}

?>