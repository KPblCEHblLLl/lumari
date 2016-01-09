<?php
 /* Здесь проверяется существование переменных */
  if (isset($_POST['photo'])) {$photo = $_POST['photo'];}
  if (isset($_POST['phone'])) {$phone = $_POST['phone'];}
    if (isset($_POST['name'])) {$name = $_POST['name'];}
	  if (isset($_POST['email'])) {$email = $_POST['email'];}

/* Сюда впишите свою эл. почту */
 $address = "Lumari.ru@yandex.ru";

/* А здесь прописывается текст сообщения, \n - перенос строки */
 $mes = "Тема: Фото: $photo\nТелефон: $phone\nИмя: $name\nПочта: $email";

/* А эта функция как раз занимается отправкой письма на указанный вами email */
$sub='Фотография'; //сабж
$email='<Lumari>'; // от кого
 //$send = mail ($address,$sub,$mes,"Content-type:text/plain; charset = utf-8\r\nFrom:$email"); //old version

$picture = ""; 
  // Если поле выбора вложения не пустое - закачиваем его на сервер 
  if (!empty($_FILES['photo1']['tmp_name'])) 
  { 
    // Закачиваем файл 
    $path = "../email-images/".$_FILES['photo1']['name'];
    if (copy($_FILES['photo1']['tmp_name'], $path)) $picture = $path;
  }
  if(empty($picture)) {
    mail($address, $sub, $mes, "Content-type:text/plain; charset = utf-8\r\nFrom:$email");
  } else {
    send_mail($address, $sub, $mes, $picture);
  }
  
// Вспомогательная функция для отправки почтового сообщения с вложением
  function send_mail($mail_to, $thema, $html, $path)   
  { if ($path) {  
    $fp = fopen($path,"rb");   
    if (!$fp)   
    { print "Cannot open file";   
      exit();   
    }   
    $file = fread($fp, filesize($path));   
    fclose($fp);   
    }  
    $name = $_FILES['photo1']['name']; // в этой переменной надо сформировать имя файла (без всякого пути)  
    $EOL = "\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
    $boundary     = "--".md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.  
    $headers    = "MIME-Version: 1.0;$EOL";   
    $headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";  
    $headers   .= "From: Фотография";  
      
    $multipart  = "--$boundary$EOL";   
    $multipart .= "Content-Type: text/plain; charset=utf-8$EOL";
    $multipart .= "Content-Transfer-Encoding: base64$EOL";   
    $multipart .= $EOL; // раздел между заголовками и телом html-части 
    //$multipart .= chunk_split(base64_encode($html));   
    $multipart .= chunk_split(base64_encode($html)); 

    $multipart .=  "$EOL--$boundary$EOL";   
    $multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";   
    $multipart .= "Content-Transfer-Encoding: base64$EOL";   
    $multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";   
    $multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла 
    $multipart .= chunk_split(base64_encode($file));   

    $multipart .= "$EOL--$boundary--$EOL";   

        if(!mail($mail_to, $thema, $multipart, $headers))   
         {return False;           //если не письмо не отправлено
      }  
    else { //// если письмо отправлено
    // вычищаем отправленное фото
    unlink($path);
    return True;  
    }  
  exit;  
  }

ini_set('short_open_tag', 'On');
header('Refresh: 3; URL=index.html');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="refresh" content="3; url=index.html">
<title>С вами свяжутся</title>
<meta name="generator">
<style type="text/css">
body
{
   background: #fff url(img/zakaz.jpg) top -50% center no-repeat;
}
</style>
<script type="text/javascript">
setTimeout('location.replace("/index.html")', 30000);
/*Изменить текущий адрес страницы через 3 секунды (3000 миллисекунд)*/
</script> 
</head>
</body>
</html>