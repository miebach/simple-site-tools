<?

$captcha_test_passed = false;

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

if ($captcha_test_passed){ 

######## CAPTCHA-TEST BESTANDEN ANFANG ##########

include 'formmail.class.php';

$fm = new formmail(); # Create a formmail-object


#### Ab hier kann KONFIGURIERT werden:

$fm->set_debug(0); # 1 oder 0 (1 gibt Fehlermeldungen und alle Variablen am Bildschirm aus, 0 für Praxiseinsatz)

$fm->set_chk_Values("Ja","Nein"); #so werden Checkboxen übersetzt


#################################################################

# Das Formular, auf das umgeleitet wird, wenn nicht alle
# Pflichtfelder oder das Email-Feld ausgefüllt sind:

$fm->set_oops("http://example.com/ups.html");

# Das Formular, auf das umgeleitet wird, wenn alle Angaben korrekt waren:

$fm->set_thankyou("http://example.com/danke.html");


#################################################################

#Achtung, da für den Empfaenger keine Formularvariablen sichtbar sind, eruebrigt
#sich die Notwendigkeit von Grossschreibung, Sonderzeichen, etc.

# Absender der Nachricht vom System an den Empfänger,

$fm->set_mail_from_field("email"); # Name der Formular-Variablen!!!

# ...darüber hinaus notwendige Variablen aus dem Formular,

# wenn eins von denen nicht angegeben wird, dann kommt die Fehlerseite (s.u.)

$fm->add_expected_field("nachname");
$fm->add_expected_field("vorname");
$fm->add_expected_field("telefon_privat");
$fm->add_expected_field("adresse");
$fm->add_expected_field("ort");
$fm->add_expected_field("land");


# Hier alle Checkbox-Felder angeben, ganz gleich ob es Pflichtfelder sind oder nicht:

$fm->add_chk("gratis_journal");
$fm->add_chk("unterlagen_zuschicken");
$fm->add_chk("rueckruf_bitte");

# Empfänger des Mailformulars (Das ist i.d.R. der Inhaber der Website)

$fm->set_mail_recipient("Klaus Beispiel <klaus@example.com>");



# Betreff der Nachricht vom System an den Empfänger:

$fm->set_mail_subject("Formular-Anfrage aus example.com");

# Textkörper der Nachricht vom System an den Empfänger:

$fm->set_mail_body("http://example.com/mail.txt");

#In diesem Text werden alle Formularvariablen, die mit # eingeklammert werden, durch ihren Wert ersetzt!!!


#################################################################


# Dies ist die Antwort-Email, die der Website Besucher nach dem abschicken vom System bekommt
# (Absender, den die Mail bekommt,Betreff,Datei mit dem Text):

$fm->set_answer("Klaus Beispiel <klaus@example.com>","Danke Für Ihre Anfrage","http://example.com/antwort.txt");

#Achtung, einige Server erlauben als ersten Parameter nur eine nackte Mail-Adresse, ohne Namen des Absenders:

#$fm->set_answer("klaus@example.com","Danke Für Ihre Anfrage","http://example.com/antwort.txt");





###################################################################


# process mail:

$fm->process();


######## CAPTCHA-TEST BESTANDEN ENDE ##########

}else{ 

######## CAPTCHA-TEST NICHT BESTANDEN: ##########

   die ("<html><body>Das hat nicht geklappt! Vielleicht haben Sie beim Sicherheitscode Gross- und Kleinbuchstaben nicht richtig erwischt.<br />".
        "Bitte gehen Sie zur&uuml;ck auf die Seite und geben Sie den Code nochmals ein. <br />".
        "Danke für die M&uuml;he."); 
}

?>