<?

define(LIB_DIR,"../");

####### DEBUG ? ############

# TRUE oder FALSE 
# TRUE gibt Fehlermeldungen und alle Variablen am Bildschirm aus
# FALSE für Praxiseinsatz
$DEBUG_MESSAGES=FALSE;

####### KONFIGURATION DER SITE: ########

# Das Formular, auf das umgeleitet wird, wenn nicht alle
# Pflichtfelder oder das Email-Feld ausgefüllt sind:
$REDIRECT_TARGET_OOPS="ups.html";

# Das Formular, auf das umgeleitet wird, wenn alle Angaben korrekt waren:
$REDIRECT_TARGET_THANKYOU="danke.html";

# Empfänger des Mailformulars (Das ist i.d.R. der Inhaber der Website)
$MAIL_ADDRESS_OWNER="Klaus Beispiel <klaus@example.com>";

# Absender des Mailformulars 
# (Das sollte eine Adresse aus einer Domain sein, deren Reverse DNS auf den Server zeigt!)
$MAIL_ADDRESS_SERVER="server@example.com";

# Betreff der Nachricht vom System an den Empfänger:
$MAIL_SUBJECT="Formular-Anfrage aus example.com";

# Textkörper der Nachricht vom System an den Empfänger.
# In diesem Text werden alle Formularvariablen, 
# die mit # eingeklammert werden, durch ihren Wert ersetzt:
$MAIL_BODY="mail.txt";
#$MAIL_BODY="http://example.com/mail.txt";

# Dies ist die Antwort-Email, die der Website Besucher nach dem abschicken vom System bekommt
# (Absender, den die Mail bekommt,Betreff,Datei mit dem Text):

$ANSWER_MAIL_SENDER="Klaus Beispiel <klaus@example.com>";
#Achtung, einige Server erlauben als ersten Parameter nur eine nackte Mail-Adresse, ohne Namen des Absenders:
#$ANSWER_MAIL_SENDER,"klaus@example.com");
$ANSWER_MAIL_SUBJECT="Danke Für Ihre Anfrage";
$ANSWER_MAIL_BODY="antwort.txt";


# Wird der Captcha Test benutzt?
# FALSE -> Nein
# TRUE -> Ja
$USE_CAPTCHA=FALSE;

# Die Seite, die ausgegeben wird, wenn der Captcha test nicht bestanden wurde_
$CAPTCHA_ERROR_HTML="<html><body>Das hat nicht geklappt! Vielleicht haben Sie beim Sicherheitscode Gross- und Kleinbuchstaben nicht richtig erwischt.<br />".
        "Bitte gehen Sie zur&uuml;ck auf die Seite und geben Sie den Code nochmals ein. <br />".
        "Danke für die M&uuml;he.";

include LIB_DIR."config_local.php";

require LIB_DIR."include1.php";

#################################################################

#Achtung, da für den Empfaenger keine Formularvariablen sichtbar sind, eruebrigt
#sich die Notwendigkeit von Grossschreibung, Sonderzeichen, etc.

# Absender der Nachricht vom System an den Empfänger,

#$f->set_mail_from_field("email"); # Name der Formular-Variablen!!!

# ...darüber hinaus notwendige Variablen aus dem Formular,

# wenn eins von denen nicht angegeben wird, dann kommt die Fehlerseite (s.u.)

#$f->add_expected_field("nachname");
#$f->add_expected_field("vorname");
#$f->add_expected_field("telefon_privat");
#$f->add_expected_field("adresse");
#$f->add_expected_field("ort");
#$f->add_expected_field("land");


# Hier alle Checkbox-Felder angeben, ganz gleich ob es Pflichtfelder sind oder nicht:

#$f->add_chk("gratis_journal");
#$f->add_chk("unterlagen_zuschicken");
#$f->add_chk("rueckruf_bitte");

require LIB_DIR."include2.php";

?>