<?php
/* ========================================
               _
   _ _ ___ ___ ___ ___  |_|___
  | | | -_|   | . |   |_| |  _|
   \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201905
version 2.0
=========================================*/
class smsApi {

  private $username;
  private $password;
  private $from;

  public function __construct($username, $password, $from) {
      $this->username = $username;
      $this->password = $password;
      $this->from = $from;
  }

  public function send($to, $sms_content) {
    $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding'=>'UTF-8'));

    $parameters['username'] = $this->username;
    $parameters['password'] = $this->password;
    $parameters['to'] = $to;
    $parameters['from'] = $this->from;
    $parameters['text'] = $sms_content;
    $parameters['isflash'] = false;

    $Data  = $sms_client ->SendSimpleSMS2($parameters)->SendSimpleSMS2Result;
  }

  public function sendVoice($to, $sms_content) {
    $sms_client = new SoapClient('http://api.payamak-panel.com/post/Voice.asmx?wsdl', array('encoding'=>'UTF-8'));

    $parameters['username'] = $this->username;
    $parameters['password'] = $this->password;
    $parameters['to'] = $to;
    $parameters['from'] = $this->from;
    $parameters['smsBody'] = $sms_content;
    $parameters['speechBody'] = $sms_content;
    $parameters['isflash'] = false;

    $Data  = $sms_client ->SendSMSWithSpeechText($parameters)->SendSMSWithSpeechTextResult;
  }
}
?>
