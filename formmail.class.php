<?
### DIESE DATEI ENTHÄLT _NICHTS_ ZUM ANPASSEN

define("GOBACK","<a href=\"javascript:history.back()\">zur&uuml;ck</a>");

/*

Version History:  
1.4 added comment for some servers who don't accept names in from-mail, just plain email address
1.3 added guestbook-functionality
1.2 get_file_contents was replaced by own function get_file_contents1
1.1 Vars also in reply-email
1.0 

*/
             
function get_day() {
    
    $ind = strftime("%u");  
    
    #echo $ind; die();
    
    switch($ind)  {
       case 1:
         return "Montag";
         break; 
       case 2:
         return "Dienstag";
         break; 
       case 3:
         return "Mittwoch";
         break; 
       case 4:
         return "Donnerstag";
         break; 
       case 5:
         return "Freitag";
         break; 
       case 6:
         return "Samstag";
         break; 
       case 7:
         return "Sonntag";
         break; 
        
    }
    
}
                                    

function get_month() {
    
    $ind = strftime("%m");  
    
    switch($ind)  {
       case 1:
         return "Januar";
         break; 
       case 2:
         return "Februar";
         break; 
       case 3:
         return "März";
         break; 
       case 4:
         return "April";
         break; 
       case 5:
         return "Mai";
         break; 
       case 6:
         return "Juni";
         break; 
       case 7:
         return "Juli";
         break; 
       case 8:
         return "August";
         break; 
       case 9:
         return "September";
         break; 
       case 10:
         return "Oktober";
         break; 
       case 11:
         return "November";
         break; 
       case 12:
         return "Dezember";
         break; 
        
    }
    
}


function get_german_timestamp() {      

      $germandate1 = trim(strftime("%e"));
      $germandate2 = strftime("%Y");
      $germandate3 = strftime("%H:%M");
      
      return get_day() . ", " . $germandate1 . "." . get_month(). " " . $germandate2 . " um " . $germandate3;
}      
             
             
function getmicrotimekey(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    $ret = ((float)$usec + (float)$sec); 
    list($p1, $p2) = explode(".",$ret); 
    $ret = $p1 . $p2 . rand(1000,9999);
    return $ret;
} 
                                      
function rename_server($a,$b) {        
           $f1 = $_SERVER["DOCUMENT_ROOT"];
           #echo $f1.$a;
           rename($f1.$a,$f1.$b);
}                                      
                                      
function file_get_contents1($f,$basewebroot=false) {

    # if $basewebroot is omitted or false, then the absolute filesystem path,
    # e.g. /home/apache/... ist the base for $f (but it can be realtive?!)

    # if $basewebroot is set to true, $f must start with "/" and is relative to webroot


    #echo "************** $f ********************";
    ob_start();
    #echo "@$retval@";

    $f1 = $f;
    if ($basewebroot) {
        $f1 = $_SERVER["DOCUMENT_ROOT"]. $f1;
    }
    
    #if ($f=='index.htm') { echo "*$f1*"; }
    $retval = @readfile($f1);
    if (false !== $retval) { // no readfile error
        $retval = ob_get_contents();
    } else {
       # echo "ERROR file_get_contents1";
    }
    ob_end_clean();
    #if ($f=='index.htm') { echo "@$retval@"; }
    return $retval;
}



###############################################

# included here, so there is only 1 file. Code for formmailer at the end of this file.

/************************************************
** Title.........: PHP4 Debug Helper
** Author........: Thomas Schüßler <code at atomar dot de>
** Filename......: debuglib.php(s)
** Last changed..: 28.09.2003 11:00
** License.......: Free to use. Postcardware ;)
**
*************************************************
**
** Functions in this library:
**
** print_a( array array [,bool show object vars] [,int returnmode] )
**
**   prints arrays in a readable, understandable form.
**   if mode is defined the function returns the output instead of
**   printing it to the browser
**
**   print_a( $array, #, 1 ) shows also object properties
**   print_a( $array, 1, # ) returns the table as a string
**   print_a( $array, 2, # ) opens a new browser window with a table representing your array
**   print_a( $array, 3, # ) opens a new browser window with a serialized version of your array (save as a textfile and can it for later use ;)
**
** show_vars( [bool verbose] [, bool show_object_vars ] [, int limit] )
**
**   use this function on the bottom of your script to see all
**   superglobals and global variables in your script in a nice
**   formated way
**
**   show_vars() without parameter shows $_GET, $_POST, $_SESSION,
**   $_FILES and all global variables you've defined in your script
**
**   show_vars(1) shows $_SERVER and $_ENV in addition
**   show_vars(#,1) shows also object properties
**   show_vars(#, #, 15) shows only the first 15 entries in a numerical keyed array (or an array with more than 50 entries)  ( standard is 5 )
**   show_vars(#, #, 0) shows all entries
**
**
**
** ** print_result( result_handle ) **
**   prints a mysql_result set returned by mysql_query() as a table
**   this function is work in progress! use at your own risk
**
**
** Happy debugging and feel free to email me your comments.
**
**
**
** History: (starting at 2003-02-24)
**
**   - added tooltips to the td's showing the type of keys and values (thanks Itomic)
** 2003-07-16
**   - pre() function now trims trailing tabulators
** 2003-08-01
**   - silly version removed.. who needs a version for such a thing ;)
** 2003-09-24
**   - changed the parameters of print_a() a bit
**     see above
**   - addet the types NULL and bolean to print_a()
**   - print_a() now replaces trailing spaces in string values with red underscores
** 2003-09-24 (later that day ;)
**   - oops.. fixed the print_a() documentation.. parameter order was wrong
**   - added mode 3 to the second parameter
** 2003-09-25
**   - added a limit parameter to the show_vars() and print_a() functions
**     default for show_vars() is 5
**     show_vars(#,#, n) changes that (0 means show all entries)
**     print_a() allways shows all entries by default
**     print_a(#,#,#, n) changes that
**
**     this parameter is used to limit the output of arrays with a numerical index (like long lists of similiar elements)
**     i added this option for performance reasons
**     it has no effect on arrays where one ore more keys are not number-strings
** 2003-09-27
**   - reworked the pre() and _remove_exessive_leading_tabs() functions
**     they now work like they should :)
**   - some cosmetic changes
************************************************/


# This file must be the first include on your page.

/* used for tracking of generation-time */
{
    $MICROTIME_START = microtime();
    @$GLOBALS_initial_count = count($GLOBALS);
}

/************************************************
** print_a class and helper function
** prints out an array in a more readable way
** than print_r()
**
** based on the print_a() function from
** Stephan Pirson (Saibot)
************************************************/

class Print_a_class {

    # this can be changed to FALSE if you don't like the fancy string formatting
    var $look_for_leading_tabs = TRUE;

    var $output;
    var $iterations;
    var $key_bg_color = '1E32C8';
    var $value_bg_color = 'DDDDEE';
    var $fontsize = '8pt';
    var $keyalign = 'center';
    var $fontfamily = 'Verdana';
    var $show_object_vars;
    var $limit;

    // function Print_a_class() {}

    # recursive function!

    /* this internal function looks if the given array has only numeric values as  */
    function _only_numeric_keys( $array ) {
        $test = TRUE;
        foreach( array_keys( $array ) as $key ) {
            if( !is_numeric( $key ) )    $test = FALSE; /* #TODO# */
        }
        return $test;
    }

    function _handle_whitespace( $string ) {
        $string = str_replace(' ', '&nbsp;', $string);
        $string = preg_replace(array('/&nbsp;$/', '/^&nbsp;/'), '<span style="color:red;">_</span>', $string); /* replace spaces at the start/end of the key with red underscores */
        $string = preg_replace('/\t/', '<span style="color:green;">___</span>', $string); /* replace tabulators with green triple underscores */
        return $string;
    }

    function print_a($array, $iteration = FALSE, $key_bg_color = FALSE) {
        $key_bg_color or $key_bg_color = $this->key_bg_color;

        # lighten up the background color for the key td's =)
        if( $iteration ) {
            for($i=0; $i<6; $i+=2) {
                $c = substr( $key_bg_color, $i, 2 );
                $c = hexdec( $c );
                ( $c += 15 ) > 255 and $c = 255;
                isset($tmp_key_bg_color) or $tmp_key_bg_color = '';
                $tmp_key_bg_color .= sprintf( "%02X", $c );
            }
            $key_bg_color = $tmp_key_bg_color;
        }

        # build a single table ... may be nested
        $this->output .= '<table style="border:none;" cellspacing="1">';
        $only_numeric_keys = ($this->_only_numeric_keys( $array ) || count( $array ) > 50);
        $i = 0;
        foreach( $array as $key => $value ) {

            if( $only_numeric_keys && $this->limit && $this->limit == $i++ ) break; /* if print_a() was called with a fourth parameter #TODO# */

            $value_style = 'color:black;';
            $key_style = 'color:white;';

            $type = gettype( $value );
            # print $type.'<br />';

            # change the color and format of the value and set the values title
            $type_title = $type;
            switch( $type ) {
                case 'array':
                    if( empty( $value ) ) $type_title = 'empty array';
                    break;

                case 'object':
                    $key_style = 'color:#FF9B2F;';
                    break;

                case 'integer':
                    $value_style = 'color:green;';
                    break;

                case 'double':
                    $value_style = 'color:blue;';
                    break;

                case 'boolean':
                    $value_style = 'color:#D90081;';
                    break;

                case 'NULL':

                    $value_style = 'color:darkorange;';
                    break;

                case 'string':
                    if( $value == '' ) {
                        $value_style = 'color:darkorange;';
                        $value = "''";
                        $type_title = 'empty string';
                    } elseif( $this->look_for_leading_tabs && _check_for_leading_tabs( $value ) ) {
                        $value = htmlspecialchars( $value );
                        $value = _remove_exessive_leading_tabs( $value );
                        $value = pre( $value, 1 );
                        $value_style = 'color:black;border:1px gray dotted;padding:0px 10px 0px 10px;';
                    } else {
                        $value_style = 'color:black;';
                        $value = nl2br( htmlspecialchars( $value ) );
                        $value = $this->_handle_whitespace( $value );
                    }
                    break;
            }

            $this->output .= '<tr>';
            $this->output .= '<td nowrap="nowrap" align="'.$this->keyalign.'" style="padding:0px 3px 0px 3px;background-color:#'.$key_bg_color.';'.$key_style.';font:bold '.$this->fontsize.' '.$this->fontfamily.';" title="'.gettype( $key ).'['.$type_title.']">';
            $this->output .= $this->_handle_whitespace( $key );
            $this->output .= '</td>';
            $this->output .= '<td nowrap="nowrap" style="background-color:#'.$this->value_bg_color.';font: '.$this->fontsize.' '.$this->fontfamily.'; color:black;">';


            # value output
            if($type == 'array' && preg_match('/#RAS/', $key) ) { /* only used for special recursive array constructs which i use sometimes */
                $this->output .= '<div style="'.$value_style.'">recursion!</div>';
            } elseif($type == 'array') {
                if( ! empty( $value ) ) {
                    $this->print_a( $value, TRUE, $key_bg_color );
                } else {
                    $this->output .= '<span style="color:darkorange;">[]</span>';
                }
            } elseif($type == 'object') {
                if( $this->show_object_vars ) {
                    $this->print_a( get_object_vars( $value ), TRUE, $key_bg_color );
                } else {
                    $this->output .= '<div style="'.$value_style.'">OBJECT</div>';
                }
            } elseif($type == 'boolean') {
                $this->output .= '<div style="'.$value_style.'" title="'.$type.'">'.($value ? 'TRUE' : 'FALSE').'</div>';
            } elseif($type == 'NULL') {
                $this->output .= '<div style="'.$value_style.'" title="'.$type.'">NULL</div>';
            } else {
                $this->output .= '<div style="'.$value_style.'" title="'.$type.'">'.$value.'</div>';
            }

            $this->output .= '</td>';
            $this->output .= '</tr>';
        }

        $entry_count = count( $array );
        $skipped_count = $entry_count - $this->limit;

        if( $only_numeric_keys && $this->limit && count($array) > $this->limit) {
            $this->output .= '<tr title="showing '.$this->limit.' of '.$entry_count.' entries in this array"><td style="text-align:right;color:darkgray;background-color:#'.$key_bg_color.';font:bold '.$this->fontsize.' '.$this->fontfamily.';">...</td><td style="background-color:#'.$this->value_bg_color.';font:'.$this->fontsize.' '.$this->fontfamily.';color:darkgray;">['.$skipped_count.' skipped]</td></tr>';
        }
        $this->output .= '</table>';
    }
}

# helper function.. calls print_a() inside the print_a_class
function print_a( $array, $mode = 0, $show_object_vars = FALSE, $limit = FALSE ) {
    $output = '';

    if( is_array( $array ) or is_object( $array ) ) {
        $pa = &new Print_a_class;
        $show_object_vars and $pa->show_object_vars = TRUE;
        if( $limit ) {
            $pa->limit = $limit;
            // $output .= '<span style="color:red;">showing only '.$limit.' entries for arrays with numeric keys</span>';
        }

        $pa->print_a( $array );

        # $output = $pa->output; unset($pa);
        $output .= $pa->output;
    } else {
        $output .= '<span style="color:red;font-size:small;">print_a( '.gettype( $array ).' )</span>';
    }

    if($mode == 0) {
        print $output;
        return TRUE;
    }

    if($mode == 1) {
        return $output;
    }


    if($mode == 2) {
        $debugwindow_origin = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        print '
            <script type="text/javascript" language="JavaScript">
                var debugwindow;
                debugwindow = window.open("", "T_'.md5($_SERVER['HTTP_HOST']).'", "menubar=no,scrollbars=yes,resizable=yes,width=640,height=480");
                debugwindow.document.open();
                debugwindow.document.write("'.addslashes($output).'");
                debugwindow.document.close();
                debugwindow.document.title = "Debugwindow for : http://'.$debugwindow_origin.'";
                debugwindow.focus();
            </script>
        ';
    }

    if($mode == 3) {
        print '
            <script type="text/javascript" language="JavaScript">
                var debugwindow;
                debugwindow = window.open("", "S_'.md5($_SERVER['HTTP_HOST']).'", "menubar=yes,scrollbars=yes,resizable=yes,width=640,height=480");
                debugwindow.document.open();
                debugwindow.document.write("'.addslashes( serialize($array) ).'");
                debugwindow.document.close();
                debugwindow.document.title = "Debugwindow for : http://'.$debugwindow_origin.'";
                debugwindow.focus();
            </script>
        ';
    }

}


// shows mysql-result as a table.. # not ready yet :(
function print_result($RESULT) {

    if(!$RESULT) return;

    $fieldcount = mysql_num_fields($RESULT);

    for($i=0; $i<$fieldcount; $i++) {
        $tables[mysql_field_table($RESULT, $i)]++;
    }

    print '
        <style type="text/css">
            .rs_tb_th {
                font-family: Verdana;
                font-size:9pt;
                font-weight:bold;
                color:white;
            }
            .rs_f_th {
                font-family:Verdana;
                font-size:7pt;
                font-weight:bold;
                color:white;
            }
            .rs_td {
                font-family:Verdana;
                font-size:7pt;
            }
        </style>
        <script type="text/javascript" language="JavaScript">
            var lastID;
            function highlight(id) {
                if(lastID) {
                    lastID.style.color = "#000000";
                    lastID.style.textDecoration = "none";
                }
                tdToHighlight = document.getElementById(id);
                tdToHighlight.style.color ="#FF0000";
                tdToHighlight.style.textDecoration = "underline";
                lastID = tdToHighlight;
            }
        </script>
    ';

    print '<table bgcolor="#000000" cellspacing="1" cellpadding="1">';

    print '<tr>';
    foreach($tables as $tableName => $tableCount) {
        $col == '0054A6' ? $col = '003471' : $col = '0054A6';
        print '<th colspan="'.$tableCount.'" class="rs_tb_th" style="background-color:#'.$col.';">'.$tableName.'</th>';
    }
    print '</tr>';

    print '<tr>';
    for($i=0;$i < mysql_num_fields($RESULT);$i++) {
        $FIELD = mysql_field_name($RESULT, $i);
        $col == '0054A6' ? $col = '003471' : $col = '0054A6';
        print '<td align="center" bgcolor="#'.$col.'" class="rs_f_th">'.$FIELD.'</td>';
    }
    print '</tr>';

    mysql_data_seek($RESULT, 0);

    while($DB_ROW = mysql_fetch_array($RESULT, MYSQL_NUM)) {
        $pointer++;
        if($toggle) {
            $col1 = "E6E6E6";
            $col2 = "DADADA";
        } else {
            $col1 = "E1F0FF";
            $col2 = "DAE8F7";
        }
        $toggle = !$toggle;
        print '<tr id="ROW'.$pointer.'" onMouseDown="highlight(\'ROW'.$pointer.'\');">';
        foreach($DB_ROW as $value) {
            $col == $col1 ? $col = $col2 : $col = $col1;
            print '<td valign="top" bgcolor="#'.$col.'" class="rs_td" nowrap>'.nl2br($value).'</td>';
        }
        print '</tr>';
    }
    print '</table>';
    mysql_data_seek($RESULT, 0);
}


######################
# reset the millisec timer
#
function reset_script_runtime() {
    $GLOBALS['MICROTIME_START'] = microtime();
}


######################
# function returns the milliseconds passed
#
function script_runtime() {
    $MICROTIME_END        = microtime();
    $MICROTIME_START    = explode(' ', $GLOBALS['MICROTIME_START']);
    $MICROTIME_END        = explode(' ', $MICROTIME_END);
    $GENERATIONSEC        = $MICROTIME_END[1] - $MICROTIME_START[1];
    $GENERATIONMSEC    = $MICROTIME_END[0] - $MICROTIME_START[0];
    $GENERATIONTIME    = substr($GENERATIONSEC + $GENERATIONMSEC, 0, 8);

    return (float) $GENERATIONTIME;
}


######################
# function shows all superglobals and script defined global variables
# show_vars() without the first parameter shows all superglobals except $_ENV and $_SERVER
# show_vars(1) shows all
# show_vars(#,1) shows object properties in addition
#
function show_vars($show_all_vars = FALSE, $show_object_vars = FALSE, $limit = 500) {
    if($limit === 0) $limit = FALSE;

    function _script_globals() {
        global $GLOBALS_initial_count;

        $varcount = 0;

        foreach($GLOBALS as $GLOBALS_current_key => $GLOBALS_current_value) {
            if(++$varcount > $GLOBALS_initial_count) {
                /* die wollen wir nicht! */
                if ($GLOBALS_current_key != 'HTTP_SESSION_VARS' && $GLOBALS_current_key != '_SESSION') {
                    $script_GLOBALS[$GLOBALS_current_key] = $GLOBALS_current_value;
                }
            }
        }

        unset($script_GLOBALS['GLOBALS_initial_count']);
        return $script_GLOBALS;
    }

    if(isset($GLOBALS['no_vars'])) return;

    $script_globals = _script_globals();
    print '
        <style type="text/css">
        .vars-container {
            font-family: Verdana, Arial, Helvetica, Geneva, Swiss, SunSans-Regular, sans-serif;
            font-size: 8pt;
            padding:5px;
        }
        .varsname {
            font-weight:bold;
        }
        </style>
    ';

    print '<br />
        <div style="border-style:dotted;border-width:1px;padding:2px;font-family:Verdana;font-size:10pt;font-weight:bold;">
        DEBUG <span style="color:red;font-weight:normal;font-size:9px;">(runtime: '.script_runtime().' sec)</span>
    ';

    $vars_arr['script_globals'] = array('global script variables', '#7ACCC8');
    $vars_arr['_GET'] = array('$_GET', '#7DA7D9');
    $vars_arr['_POST'] = array('$_POST', '#F49AC1');
    $vars_arr['_FILES'] = array('$_FILES', '#82CA9C');
    $vars_arr['_SESSION'] = array('$_SESSION', '#FCDB26');
    $vars_arr['_COOKIE'] = array('$_COOKIE', '#A67C52');

    if($show_all_vars) {
        $vars_arr['_SERVER'] =  array('SERVER', '#A186BE');
        $vars_arr['_ENV'] =  array('ENV', '#7ACCC8');
    }

    foreach($vars_arr as $vars_name => $vars_data) {
        if($vars_name != 'script_globals') global $$vars_name;
        if($$vars_name) {
            print '<div class="vars-container" style="background-color:'.$vars_data[1].';"><span class="varsname">'.$vars_data[0].'</span><br />';
            print_a($$vars_name, FALSE, $show_object_vars, $limit);
            print '</div>';
        }
    }
    print '</div>';
}


######################
# function prints/returns strings wrapped between <pre></pre>
#
function pre( $string, $return_mode = FALSE, $tabwidth = 3 ) {
    $tab = str_repeat('&nbsp;', $tabwidth);
    $string = preg_replace('/\t+/em', "str_repeat( ' ', strlen('\\0') * $tabwidth );", $string); /* replace all tabs with spaces */

    $out = '<pre>'.$string."</pre>\n";

    if($return_mode) {
        return $out;
    } else {
        print $out;
    }
}

function _check_for_leading_tabs( $string ) {
    return preg_match('/^\t/m', $string);
}

function _remove_exessive_leading_tabs( $string ) {
    /* remove whitespace lines at start of the string */
    $string = preg_replace('/^\s*\n/', '', $string);
    /* remove whitespace at end of the string */
    $string = preg_replace('/\s*$/', '', $string);

    # kleinste Anzahl von führenden TABS zählen
    preg_match_all('/^\t+/', $string, $matches);
    $minTabCount = strlen(@min($matches[0]));

    # und entfernen
    $string = preg_replace('/^\t{'.$minTabCount.'}/m', '', $string);

    return $string;
}


class formmail {

    var $debug = 0;
    var $mail_from="";
    var $mail_recipient="";
    var $mail_subject="";
    var $mail_body="";
    var $mail_headers="";

    var $answer_from ="";
    var $answer_subject="";
    var $answer_body="";
    var $answer_headers="";

    var $expected_fields = array();
    var $chk_fields = array();

    var $chk0 = "No";
    var $chk1 = "Yes";

    var $thankyou="";
    var $oops="";    
    

    function formmail() {
    }


    function set_chk_Values($yes,$no) {
       $this->chk0 = $no;
       $this->chk1 = $yes;
    }

    function set_debug($onoff) {
        $this->debug = $onoff;
    }

    function add_expected_field($name) {
        $this->expected_fields[] = $name;
    }

    function add_chk($name) {
        $this->chk_fields[] = $name;
    }

    function set_mail_from($mail_from){
      $this->mail_from = $mail_from;
    }

    function set_mail_from_field($mail_from_field){
      # get mail address from post var:
      $this->mail_from = $_POST[$mail_from_field];
    }


    function test_mail_from_field(){

      # if no mail address given, error:
      if (!$this->mail_from) {
        if ($this->debug) {
            echo "Keine Email-Adresse angegeben";
            die();
        }
        $this->refer($this->oops);
      }

      #check for valid mail address:
      if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $this->mail_from)) {
        if ($this->debug) {
            echo "Die Email-Adresse $this->mail_from wurde als ungültig erkannt";
            die();
        }
        $this->refer($this->oops);
      }
    }


    function set_mail_subject($mail_subject){
      $this->mail_subject = $mail_subject;
    }

    function set_mail_body($mail_body_file) {
      $this->mail_body = file_get_contents1($mail_body_file);
      if (!$this->mail_body) {
        die ("Aus der Datei $mail_body_file konnte nicht gelesen werden (get_file_contents).");
      }
    }

    function replace_vars($x,$additional=array()) {
       
       $wrk = $x;
           #print_a($additional);
           
           $ar = array_merge($_POST,$additional);
        
        #replaces all vars from $_POST in the mail with their values:
       
        foreach ($ar as $n=>$v) {
         # handle checkboxes. If checked the var appears, but if not it is not there!
             #echo "$n<br>";
          if (in_array($n,$this->chk_fields)) {
             $v1 = $this->chk1; # the word for "yes"
          } else {
             $v1 = $v;
          }
          
          $wrk = str_replace ('#'.$n.'#',stripslashes($v1),$wrk);
          #echo "#$n#,$v,<br>";
        }

        #Now handle the checkboxes that were not checked:
          #If it is still in the mailbody it means that it was not checked and not checked means no!
        $v1 = $this->chk0; # "No"
        foreach ($this->chk_fields as $n) {
          $wrk = str_replace ('#'.$n.'#',stripslashes($v1),$wrk);
          #echo "#$n#,$v,<br>";
        }
          return $wrk;
    }

    function set_mail_recipient($mail_recipient){
      $this->mail_recipient = $mail_recipient;
    }

    function set_thankyou($thankyou){
      $this->thankyou = $thankyou;
    }

    function set_oops($oops) {
      $this->oops=$oops;
    }

    function set_answer($answer_from,$answer_subject,$answer_body_file) {
      $this->answer_from = $answer_from;
      $this->answer_subject = $answer_subject;
      $this->answer_body = file_get_contents1($answer_body_file);
      if (!$this->answer_body) {
        die ("Aus der Datei $answer_body_file konnte nicht gelesen werden (get_file_contents).");
      }
    }

    function refer($target) {
        header("Location: $target");
        exit;
    }

    function process() {  # this is the main func for formmails. 
             
       $this->test_mail_from_field();

       # check if all fields ok
       foreach ($this->expected_fields as $n) {
          if (!$_POST[$n]) {
              #if one es missing goto error page immedeately:
               $this->refer($this->oops);
               exit;
          };
        }

       # enter field values into email:
       $this->mail_body = $this->replace_vars($this->mail_body);

       # enter field values into answer:
       if ($this->answer_from) {
         $this->answer_body = $this->replace_vars($this->answer_body);
       }

       # 1) send formmail
       $this->mail_headers = "From: $this->mail_from\r\n"
              ."Reply-To: $this->mail_from\r\n"
              ."X-Mailer: PHP/" . phpversion();

       if (!mail ($this->mail_recipient,$this->mail_subject,$this->mail_body,$this->mail_headers)) {
         die('Fehler beim versenden der E-Mail. Bitte probieren Sie es nocheinmal. '.GOBACK);
       }

       # 2) send reply
           if ($this->answer_from) {

                 $this->answer_headers = "From: $this->answer_from\r\n"
                        ."Reply-To: $this->answer_from\r\n"
                        ."X-Mailer: PHP/" . phpversion();
                 #this time the recipient is the sender of the form!
                     # V1.4 some servers don't accept names in from-mail, just plain email address!!
                     mail ($this->mail_from,$this->answer_subject,$this->answer_body,$this->answer_headers);
                     # 3) refer to thankyou-page
          }

       #show_vars();
       if ($this->debug) {
          print_a($_POST);
          print_a($this);
          show_vars();
          die();
       }
       $this->refer($this->thankyou);
    }

    
} #class formmail
?>