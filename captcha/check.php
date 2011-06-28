<?

     die();
     # call this page for debugging only

     require_once 'CaptchaStore.php';
     $cs = new CaptchaStore;

     $id = $_GET["id"];
     
     $secret = $cs->retrieve_secret($id);
     
     echo "Secret for id $id = '$secret'<hr>";

     # store something
     $cs->store_secret(md5(date("His")),"code123");
     $cs->echo_filenames();
     echo "<hr>";

     # do the cleanup:
     $cs->cleanup();

?>