<?

   $background_image = 'http://example.com/captcha/captcha.png'


   # Code originally from: http://www.stoppt-den-spam.info/webmaster/captcha-tutorial/index.html

   function randomString($len) { 
      srand(date("s")); 
      //Der String $possible enthält alle Zeichen, die verwendet werden sollen 
      $possible="ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxz23456789"; 
      $str=""; 
      while(strlen($str)<$len) { 
        $str.=substr($possible,(rand()%(strlen($possible))),1); 
      } 
      return($str); 
   } 

   $text = randomString(5);  //Die Zahl bestimmt die Anzahl stellen 

   ##### NEU 27.6.08: #######

   if (isset($_GET["id"])) {
     
     # falls eine id uebergeben wurde (z.b. captcha.php?id=dgte6232g422ws2)
     # dann wird im Captcha Speicher (ein Verzeichnis, wo Textdateien kurzfristig zwischengespeichert werden)
     # auf diesem Server der inhalt von $text so abgespeichert, daß er mit hilfe dieses 
     # Wertes von id (dgte6232g422ws2) wieder hervorgeholt werden kann.
     # Aufruf ohne id macht nur noch zu Demo- und Testzwecken Sinn.
     
     require_once 'CaptchaStore.php';
     $cs = new CaptchaStore;
     $cs->store_secret($id,$text);
   }
   
   ##### NEU ENDE #########

   header('Content-type: image/png'); 
   $img = ImageCreateFromPNG($background_image); //Backgroundimage 
   $color = ImageColorAllocate($img, 0, 0, 0); //Farbe 
   $ttf = $_SERVER['DOCUMENT_ROOT']."/scripts/captcha/XFILES.TTF"; //Schriftart 
   $ttfsize = 25; //Schriftgrösse 
   $angle = rand(0,5); 
   $t_x = rand(5,30); 
   $t_y = 35; 
   imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text); 
   imagepng($img); 
   imagedestroy($img); 
?>