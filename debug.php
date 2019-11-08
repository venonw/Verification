<?php
/* ========================================
               _
   _ _ ___ ___ ___ ___  |_|___
  | | | -_|   | . |   |_| |  _|
   \_/|___|_|_|___|_|_|_|_|_|

Venon Web Developers, venon.ir
201607
version 1.5
=========================================*/
require_once 'smsApi.php';

$smsApi = new smsApi('username', 'password', '3000');
$result = $smsApi->send("09121111111", 'test text');
