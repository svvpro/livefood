function formfre(a)
{
	//alert("val");
	//alert(document.forms.length);
	//alert(document.forms[0].name);
	//alert(document.forms[1].name);
	
	//alert(document.forms["reg_form"].username.value);
	//alert(document.forms["reg_form"].username.value.length);
	//alert(document.forms["reg_form"].text_login.value);

//	"reg_form"
//	var val = document.forms["add_laying"].type_laying.value
//	<input type="text" name="username" id="text_login" size="15" maxlength="15" />
//	var val = "123";

	if (document.forms["reg_form"].username.value == "" || document.forms["reg_form"].username.value.length < 4 )
	{
		alert("Логин слишком маленький");
		document.forms["reg_form"].username.value = "";
		return false;
	}

	if (document.forms["reg_form"].password.value == "" || document.forms["reg_form"].password.value.length < 4 )
	{
		alert("Пароль должен быть более 4 символов");
		document.forms["reg_form"].password.value = "";
		return false;
	}

	if (document.forms["reg_form"].password2.value != document.forms["reg_form"].password.value)
	{
		alert("Пароли не совпадают");
		document.forms["reg_form"].password2.value = "";
		return false;
	}

	var value = document.forms["reg_form"].email.value;
	
	reg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	if (!value.match(reg))
	{
		alert("Пожалуйста, введите свой настоящий e-mail");
		//document.getElementById('email').value="";
		document.forms["reg_form"].email.value = "";
		return false;
	}

	document.location.href = "";
	return false;
}

function validtlf(f)
{
	//alert("val");
	key = window.event.keyCode;
	//alert(key);
	if (key != 8)
	{
		var val = f.value;
		var reg = /^([0-9\-\)\\ (]+)$/;
		if (!val.match(reg))
		{
			val = val.substring(0, val.length-1);
		}
		
		if (val.length < 2 && val.substring(0, 1) != "(" )
		{
			val = "(" + val;
		}
		if (val.length == 2 && val.substring(1, 2) != "0" )
		{
			val = "(";
		}
		if (val.length == 4)
		{
			val = val+") ";
		}
		if (val.length == 9 || val.length == 12)
		{
			val = val+"-";
		}
		
		f.value = val;
		
		//alert(val.length);
	}
}
