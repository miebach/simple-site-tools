<?
require LIB_DIR."formmail.class.php";

$captcha_test_passed = false;

if ($USE_CAPTCHA) {
  if (isset($_POST["captcha_id"]) and isset($_POST["sicherheitscode"])) {
    $captcha_id      = $_POST["captcha_id"];
    $sicherheitscode = $_POST["sicherheitscode"];
  
    require_once 'captcha/CaptchaStore.php';
    $cs = new CaptchaStore;
    $retrieved = $cs->retrieve_secret($captcha_id);
  
    if ($retrieved == $sicherheitscode) {
      $captcha_test_passed = true;
    }
    $cs->cleanup(); // alte dateien loeschen an dieser stelle (aufraeumen)
       
    #echo "Secret for id $captcha_id = '$retrieved', Benutzereingabe war $sicherheitscode; passed = $captcha_test_passed<hr>";
  }
}
  
$f = new formmail(); # Create a formmail-object

?>