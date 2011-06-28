<?
if (($USE_CAPTCHA == FALSE) OR $captcha_test_passed){ 

  ######## CAPTCHA-TEST BESTANDEN ODER NICHT VERWENDET ANFANG ##########
  
  
  
  $f->set_debug($DEBUG_MESSAGES);
  
  $f->set_chk_Values("Ja","Nein"); #so werden Checkboxen übersetzt

  $f->set_oops($REDIRECT_TARGET_OOPS);
  $f->set_thankyou($REDIRECT_TARGET_THANKYOU);
  $f->set_mail_recipient($MAIL_ADDRESS_OWNER);
  $f->set_mail_from($MAIL_ADDRESS_SERVER);
  $f->set_mail_subject($MAIL_SUBJECT);
  $f->set_mail_body($MAIL_BODY);
  $f->set_answer($ANSWER_MAIL_SENDER,$ANSWER_MAIL_SUBJECT,$ANSWER_MAIL_BODY);
  
  # process mail:
  
  $f->process();

  ######## CAPTCHA-TEST BESTANDEN ENDE ##########

}else{ 

   ######## CAPTCHA-TEST NICHT BESTANDEN: ##########

   die ($CAPTCHA_ERROR_HTML); 
}
?>