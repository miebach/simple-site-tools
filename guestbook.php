<?

include 'formmail.class.php';

$gb = new formmail(); # Create a formmail-object

#### Ab hier kann KONFIGURIERT werden:

$gb->set_debug(0); # 1 oder 0 (1 gibt Fehlermeldungen und alle Variablen am Bildschirm aus, 0 f�r Praxiseinsatz)
$gb->set_chk_Values("Ja","Nein"); #so werden Checkboxen �bersetzt
#################################################################
# Das Formular, auf das umgeleitet wird, wenn nicht alle
# Pflichtfelder oder das Email-Feld ausgef�llt sind:

$gb->set_oops("ups.html");

# Das Formular, auf das umgeleitet wird, wenn alle Angaben korrekt waren:

$gb->set_thankyou("danke.html");

# Die Datei mit dem Html-Code f�r einen neuen G�stebucheintrag, 

$gb->set_entry_file("entry.txt");   # this contains html code for new entries
$gb->set_guestbook_file("index.htm");    
$gb->set_guestbook_marker('<!--GB_START-->');  # all new entries are added after this marker in the file   


$gb->set_store_path('/gaestebuch/entries/'); # absolute path, where entries are stored, until they are confirmed

#################################################################
#Achtung, da f�r den Empf�nger keine Formularvariablen sichtbar sind, er�brigt
#sich die Notwendigkeit von Grossschreibung, Sonderzeichen, etc. Keep it simple!

# Absender der Nachricht vom System an den Empf�nger,
$gb->set_mail_from_field("email"); # Name der Formular-Variablen!!!

# ...dar�ber hinaus notwendige Variablen aus dem Formular,
# wenn eins von denen nicht angegeben wird, dann kommt die Fehlerseite (s.u.)
$gb->add_expected_field("realname");
$gb->add_expected_field("comments");

# Hier alle Checkbox-Felder angeben, ganz gleich ob es Pflichtfelder sind oder nicht:
#$gb->add_chk("gratis_journal");
#$gb->add_chk("Unterlagen_zuschicken");

# Empf�nger des Mailformulars (Das ist i.d.R. der Inhaber der Website)
$gb->set_mail_recipient("Klaus Beispiel <klaus@example.com>");

# Betreff der Nachricht vom System an den Empf�nger:
$gb->set_mail_subject("G�stebucheintrag auf example.com");

# Textk�rper der Nachricht vom System an den Empf�nger (Systeminhaber):
#$gb->set_mail_body("http://example.com/mail.txt");
$gb->set_mail_body("mail.txt");
#In diesem Text werden alle Formularvariablen, die mit # eingeklammert werden, durch ihren Wert ersetzt!!!

#################################################################

# Dies ist die Antwort-Email (Absender,Betreff,Datei mit dem Text):
# Antworten sind Momentan nicht vorgesehen
#$gb->set_answer("Klaus Beispiel <klaus@example.com>","Danke F�r Ihre Anfrage","http://example.com/antwort.txt");

###################################################################

# process mail:
$gb->process_gb();

?>