<?
class CaptchaStore {

  # in diesem verzeichnis werden die captcha "geheimcodes" gespeichert, es muss schreibberechtigt sein,
  # zB so: "chmod 770 store" im ftp programm
  var $base_dir = "/mnt/sites/example.com/web/scripts/captcha/store/";

  # Dies sollte pro Site unterschiedlich sein und geheim gehalten werden:
  var $site_secret = "sfdjkg43tzskgfh3487"; //zufaellige zeichen
	
# CaptchaStore ist eine Klasse um die Captcha Geheimcodes in Textdateien 
# zu speichern und wieder auszulesen. Außerdem werden 
# alte Textdateien regelmäßig gelöscht.
	

## 3 HAUPTFUNKTIONEN: 1) Speichern, 2) wieder Auslesen und 3) Aufräumen der alten gespeichert Codes

 function store_secret($id,$secret) {
   //speichert den Geheimcode ab, später ist er mit "$id" wieder auszulesen:
   $this->write_file(date('g.i.s.').$this->ext_for_id($id),$secret);
 }

 function retrieve_secret($id) {
   //gibt den Geheimcode zur id $id zurueck
   
   //alle Dateinamen lesen:
   $files = $this->get_filenames_in_dir($this->base_dir);
   $compare = $this->ext_for_id($id); //diese Funktion ext_for_id erzeugt eine geheime Dateiendung
   foreach ($files as $file) {
     if ($file != "index.html") {
     	//entferne den zeitstempel vorne und vergleiche nur die datei endung:
        $parts = explode(".",$file);
        if ($parts[3] == $compare) {
           return $this->read_file($file);
        }
     } 
   }
   //nicht gefunden, also leer string zurueck geben:
   return "";
 }
 
 function cleanup() {
   //loescht alle alten dateien, ausser von dieser vollen stunde und der vorherigen vollen stunde
   $h = date('g'); //Nummer der Studne von 1 bis 12 als String
   if ($h == "1") {
     $h2 = "12";
   } else {
     $h2 = $h - 1;
     $h2 += ""; //in string verwandeln durch addieren von leerstring
   }
   $index_found = false;
   $files = $this->get_filenames_in_dir($this->base_dir);
   foreach ($files as $file) {
     $parts = explode(".",$file);
     //wenn die datei nicht aus der aktuellen stunde ist und nicht aus der vorherigen, dann loesche sie:
     if (($parts[0] != $h) and ($parts[0] != $h2) and ($file != "index.html")) {
     	$this->del_file($file);
     } 
     if ($file == "index.html") {
     	$index_found = true;
     } 
   }
   if (!$index_found) {
     //fuer server, wo Verzeichnisse lesbar sind
     $this->write_file("index.html","<html></html>");
     #echo "INDEX HMTL GESCHRIEBEN";
   }
 }


 ############### crypto magic #########
 
 function ext_for_id($id) { 
   
   #return ($id);  //so ist es nicht so sicher, 
   
   //erzeugt aus einer id eine Datei-Erweiterung:
   return (substr(md5($this->site_secret.$id),0,10));
   
 }

 ############### Basis Dateifunktionen (ohne Zeitstempel: #########

 function write_file($fname,$content) {
	$putname = $this->base_dir.$fname;
	# echo $putname;
	file_put_contents  ( $putname,$content);
 }

 function read_file($fname) {
	return file_get_contents($this->base_dir.$fname);
 }

 
 function del_file($fname) {
 	$full = $this->base_dir.$fname;
 	unlink($full);
 	#FIXME
 	#echo "LOESCHEN: $full<br>";
 }

 
 function get_filenames_in_dir($dirname){
  # gibt alle Dateinamen im Verzeichnis $dirname als array
  $result = array();
  $more = true;
  $handle = opendir($dirname);
  #echo $dirname ."<br>";
  if ($handle) {
    while (true) {
      $file = readdir($handle);
      if ($file === false) break;
      if ($file != "." && $file != "..") {
        $result[] = $file;
      }
    }
  }
  closedir($handle);
  return $result;
 }

 #### fuer Debugging: #########
 
 function echo_filenames(){
 	$files = $this->get_filenames_in_dir($this->base_dir);
	print_r($files);
 }



}  // end class CaptchaStore
?>