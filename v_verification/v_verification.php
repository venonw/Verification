<?php

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

	/* ========================================
							   _
		 _ _ ___ ___ ___ ___  |_|___
		| | | -_|   | . |   |_| |  _|
		 \_/|___|_|_|___|_|_|_|_|_|

	Venon Web Developers, venon.ir
	201905
	version 2.0
	=========================================*/

use Illuminate\Database\Capsule\Manager as Capsule;

function v_verification_config() {
    $configarray = array(
    "name" => "Venon Verification",
    "description" => "verification mobile numbers",
    "version" => "2.0",
    "author" => "Venon Web Developers, Venon.ir",
    "language" => "farsi",
    "fields" => array(
			"License" => array ("FriendlyName" => "کلید لایسنس", "Type" => "text", "Size" => "25", "Description" => "کلید لایسنس خریداری شده ونون را وارد نمایید", "Default" => "venon-", ),
	    "option2" => array ("FriendlyName" => "تایید تلفن همراه", "Type" => "yesno", "Size" => "25", "Description" => "فعال کردن سیستم تایید تلفن همراه", ),
			"voicecall" => array ("FriendlyName" => "فعال سازی پیام صوتی", "Type" => "yesno", "Size" => "25", "Description" => "ارسال پیام صوتی در صورت پشتیبانی وب سرویس", ),
			"option4" => array ("FriendlyName" => "نام فیلد موبایل", "Type" => "text", "Size" => "15", "Description" => "نام دقیق فیلد ایجاد شده موبایل را در این قسمت باید وارد نمایید", "Default" => "", ),
			"option5" => array ("FriendlyName" => "نام کاربری پنل پیامک", "Type" => "text", "Size" => "15", "Description" => "", "Default" => "demo", ),
			"option6" => array ("FriendlyName" => "رمز عبور پنل پیامک", "Type" => "text", "Size" => "15", "Description" => "", "Default" => "demo", ),
			"option7" => array ("FriendlyName" => "شماره پنل پیامک", "Type" => "text", "Size" => "15", "Description" => "", "Default" => "demo", ),
			"option8" => array ("FriendlyName" => "متن ارسال کد فعال سازی", "Type" => "text", "Size" => "30", "Description" => "", "Default" => "کد فعال سازی شما:", ),
			"voicatext" => array ("FriendlyName" => "متن ارسال کد فعال سازی صوتی", "Type" => "text", "Size" => "30", "Description" => "", "Default" => "کُدِ فَعال سازیِ شُما:", ),
			"option9" => array ("FriendlyName" => "صفحات غیرمجاز", "Type" => "textarea", "Size" => "30", "Description" => "در این قسمت کلیه صفحاتی که تمایل ندارید مشتری قبل از تایید فعال سازی به آن دسترسی داشته باشد وارد نمایید بین هر صفحه , قرار دهید.", "Default" => "cart,domaincheker,action=addfunds", ),
			"timeout" => array ("FriendlyName" => "مدت زمان بین هر ارسال پیامک به ثانیه", "Type" => "text", "Size" => "30", "Description" => "", "Default" => "60", ),
			"localkey" => array ("FriendlyName" => "کلید محلی", "Type" => "textarea", "Size" => "30", "Description" => "این قسمت خالی بگذارید، بصورت خودکار پر می شود.", "Default" => "", ),
    ));
    return $configarray;
}

function v_verification_activate() {

    # Create Custom DB Table
    $query = "CREATE TABLE IF NOT EXISTS `mod_v_verification` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`uid` INT NOT NULL ,
	`phone` TEXT NULL ,
	`phonecode` INT NULL ,
	`phonestatus` TEXT NULL
	)";

	$result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'success');
    return array('status'=>'error','description'=>'error');
    return array('status'=>'info','description'=>'');

}

function v_verification_deactivate() {
    # Remove Custom DB Table
    // $query = "DROP TABLE `mod_v_verification`";
		// $result = mysql_query($query);

    # Return Result
    return array('status'=>'success','description'=>'success');
    return array('status'=>'error','description'=>'error');
    return array('status'=>'info','description'=>'');
}

function v_verification_upgrade($vars) {

}


// Begin Check Function
// function v_verification_check_license($licensekey,$localkey="") {
//     $whmcsurl = "http://venon.ir/";
//     $licensing_secret_key = "verification_20160725"; # Set to unique value of chars
//     $checkdate = date("Ymd"); # Current date
//     $localkeydays = 7; # How long the local key is valid for in between remote checks
//     $allowcheckfaildays = 1; # How many days to allow after local key expiry before blocking access if connection cannot be made
//     $check_token = time() . md5(mt_rand(1000000000, 9999999999) . $licensekey);
//     $domain = $_SERVER['SERVER_NAME'];
//     $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
//     $dirpath = dirname(__FILE__);
//     $verifyfilepath = 'modules/servers/licensing/verify.php';
//     $localkeyvalid = false;
//     if ($localkey) {
//         $localkey = str_replace("\n", '', $localkey); # Remove the line breaks
//         $localdata = substr($localkey, 0, strlen($localkey) - 32); # Extract License Data
//         $md5hash = substr($localkey, strlen($localkey) - 32); # Extract MD5 Hash
//         if ($md5hash == md5($localdata . $licensing_secret_key)) {
//             $localdata = strrev($localdata); # Reverse the string
//             $md5hash = substr($localdata, 0, 32); # Extract MD5 Hash
//             $localdata = substr($localdata, 32); # Extract License Data
//             $localdata = base64_decode($localdata);
//             $localkeyresults = unserialize($localdata);
//             $originalcheckdate = $localkeyresults['checkdate'];
//             if ($md5hash == md5($originalcheckdate . $licensing_secret_key)) {
//                 $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - $localkeydays, date("Y")));
//                 if ($originalcheckdate > $localexpiry) {
//                     $localkeyvalid = true;
//                     $results = $localkeyresults;
//                     $validdomains = explode(',', $results['validdomain']);
//                     if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
//                         $localkeyvalid = false;
//                         $localkeyresults['status'] = "Invalid";
//                         $results = array();
//                     }
//                     $validips = explode(',', $results['validip']);
//                     if (!in_array($usersip, $validips)) {
//                         $localkeyvalid = false;
//                         $localkeyresults['status'] = "Invalid";
//                         $results = array();
//                     }
//                     $validdirs = explode(',', $results['validdirectory']);
//                     if (!in_array($dirpath, $validdirs)) {
//                         $localkeyvalid = false;
//                         $localkeyresults['status'] = "Invalid";
//                         $results = array();
//                     }
//                 }
//             }
//         }
//     }
//     if (!$localkeyvalid) {
//         $postfields = array(
//             'licensekey' => $licensekey,
//             'domain' => $domain,
//             'ip' => $usersip,
//             'dir' => $dirpath,
//         );
//         if ($check_token) $postfields['check_token'] = $check_token;
//         $query_string = '';
//         foreach ($postfields AS $k=>$v) {
//             $query_string .= $k.'='.urlencode($v).'&';
//         }
//         if (function_exists('curl_exec')) {
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $whmcsurl . $verifyfilepath);
//             curl_setopt($ch, CURLOPT_POST, 1);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
//             curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//             $data = curl_exec($ch);
//             curl_close($ch);
//         } else {
//             $fp = fsockopen($whmcsurl, 80, $errno, $errstr, 5);
//             if ($fp) {
//                 $newlinefeed = "\r\n";
//                 $header = "POST ".$whmcsurl . $verifyfilepath . " HTTP/1.0" . $newlinefeed;
//                 $header .= "Host: ".$whmcsurl . $newlinefeed;
//                 $header .= "Content-type: application/x-www-form-urlencoded" . $newlinefeed;
//                 $header .= "Content-length: ".@strlen($query_string) . $newlinefeed;
//                 $header .= "Connection: close" . $newlinefeed . $newlinefeed;
//                 $header .= $query_string;
//                 $data = '';
//                 @stream_set_timeout($fp, 20);
//                 @fputs($fp, $header);
//                 $status = @socket_get_status($fp);
//                 while (!@feof($fp)&&$status) {
//                     $data .= @fgets($fp, 1024);
//                     $status = @socket_get_status($fp);
//                 }
//                 @fclose ($fp);
//             }
//         }
//         if (!$data) {
//             $localexpiry = date("Ymd", mktime(0, 0, 0, date("m"), date("d") - ($localkeydays + $allowcheckfaildays), date("Y")));
//             if ($originalcheckdate > $localexpiry) {
//                 $results = $localkeyresults;
//             } else {
//                 $results = array();
//                 $results['status'] = "Invalid";
//                 $results['description'] = "Remote Check Failed";
//                 return $results;
//             }
//         } else {
//             preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
//             $results = array();
//             foreach ($matches[1] AS $k=>$v) {
//                 $results[$v] = $matches[2][$k];
//             }
//         }
//         if (!is_array($results)) {
//             die("Invalid License Server Response");
//         }
//         if ($results['md5hash']) {
//             if ($results['md5hash'] != md5($licensing_secret_key . $check_token)) {
//                 $results['status'] = "Invalid";
//                 $results['description'] = "MD5 Checksum Verification Failed";
//                 return $results;
//             }
//         }
//         if ($results['status'] == "Active") {
//             $results['checkdate'] = $checkdate;
//             $data_encoded = serialize($results);
//             $data_encoded = base64_encode($data_encoded);
//             $data_encoded = md5($checkdate . $licensing_secret_key) . $data_encoded;
//             $data_encoded = strrev($data_encoded);
//             $data_encoded = $data_encoded . md5($data_encoded . $licensing_secret_key);
//             $data_encoded = wordwrap($data_encoded, 80, "\n", true);
//             $results['localkey'] = $data_encoded;
//         }
//         $results['remotecheck'] = true;
//     }
//     unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
//     return $results;
// }

// End Check Function

function v_verification_output($vars) {

  $modulelink = $vars['modulelink'];
  $version = $vars['version'];
	$LANG = $vars['_lang'];

	// get License
	$venonLicense = $vars['License'];
	$venonlocalkey = $data['localkey'];

  $phoneverify = $vars['option2'];
	$mobilefieldLabel = $vars['option4'];
	$paneluser = $vars['option5'];
	$panelpass = $vars['option6'];
	$panelnumber = $vars['option7'];
	$smstext = $vars['option8'];
	$forbidfiles = $vars['option9'];

	if($_POST['savestatus'] == '1'){
		$update = array("phonestatus"=>$_POST['phonestatus']);
		$where = array("id"=> $_POST['rowid']);
		update_query('mod_v_verification',$update,$where);
	}



	// license check
	// $licenseResult = v_verification_check_license($venonLicense,$venonlocalkey);
	// $licenseResult["status"] = 'Active';

	echo '<div class="contexthelp"><a href="http://venon.ir" target="_blank"><img src="images/icons/help.png" border="0" align="absmiddle">پشتیبانی</a></div>';

	// if ($licenseResult["status"]=="Active") {
    echo '<p>ماژول تایید تلفن همراه</p>';

		echo '<div id="clienttabs hidden-print">
        <ul class="nav nav-tabs admin-tabs hidden-print">
          <li class="'; if ($_GET['go']=="manage" or empty($_GET['go'])){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_verification&go=manage">شماره ها</a></li>
          <li class="'; if ($_GET['go']=="search"){ echo "tabselected active";} else {echo "tab";}; echo'"><a href="addonmodules.php?module=v_verification&go=search">جستجوگر</a></li>
        </ul>
      </div>
      <div id="tab0box" class="tabbox tab-content admin-tabs">
      <div id="tab_content" class="tab-pane active" style="text-align:right;">';

if (($_GET['go']=="manage" or empty($_GET['go']))){

	$pageNum = 1;
	if(isset($_GET['page'])){$pageNum = $_GET['page'];}
	if (isset($_GET['page'])) {$pgadrss = '&page='.$_GET['page'];}

	$count = mysql_query("SELECT `id` FROM `mod_v_verification`");
	$pagination = verification_pagination($count, $pageNum, 25);
	$offset = $pagination['offset'];

	$result = mysql_query("SELECT * FROM `mod_v_verification` ORDER BY  `mod_v_verification`.`id` DESC LIMIT $offset, 25");

	if ($pagination["pre"]) {echo '<a href="addonmodules.php?module=v_verification&go=manage&page='.$pagination['prepage'].'" class="btn btn-sm btn-default">صفحه قبلی</a>';}
	if ($pagination["next"]) {echo '<a style="float:left;" href="addonmodules.php?module=v_verification&go=manage&page='.$pagination['nextpage'].'" class="btn btn-sm btn-default">صفحه بعدی</a>';}

	echo '<br/><hr />
	<form style="display:inline-block; float:left;" class="form-inline" method="GET" action="./addonmodules.php">
	<input type="hidden" name="module" value="v_verification" />
	<input type="hidden" name="go" value="manage" />
		'.$pagination["jumper"].'
	</form>
	'.$pagination["counterText"].' -
	تعداد نتایج '.$pagination["counter"].'
	<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
			<tbody>
				<tr>
					<th>کد</th>
					<th>نام کاربری</th>
					<th>شماره همراه</th>
					<th>کد تایید همراه</th>
					<th>وضعیت تایید همراه</th>
					<th></th>
		</tr>';


while($data = mysql_fetch_array($result)) {
		//user list
		$uid = $data["uid"];
		$userfetch = Capsule::table('tblclients')->where('id', '=', $uid)->first();
		$userfullname = $userfetch->firstname.' '.$userfetch->lastname;

		echo'<tr>
			<form method="POST" action="./addonmodules.php?module=v_verification" />
			<input type="hidden" name="savestatus" value="1" />
			<input type="hidden" name="rowid" value="'.$data["id"].'" />
			<td>'.$data["id"].'</td>
			<td><a href="clientssummary.php?userid='.$data["uid"].'" target="_blank">'.$userfullname.'</a></td>
			<td>'.$data["phone"].'</td>
			<td>'.$data["phonecode"].'</td>
			<td>
				<select name="phonestatus" class="form-input input-sm">
					<option></option>
					<option value="pend" '; if($data["phonestatus"] == 'pend') {echo 'selected';} echo'>معلق</option>
					<option value="active" '; if($data["phonestatus"] == 'active') {echo 'selected';} echo'>فعال</option>
				</select>
			</td>
			<td><input type="submit" value="ذخیره" class="btn btn-success btn-xs" /></td>
			</form>
		</tr>';
	}

	echo '</tbody></table>';
}

if ($_GET['go']=="search" ){
	$q= $_POST['q'];

	if(!empty($q)){
		$result = mysql_query("SELECT * FROM `mod_v_verification` WHERE (`uid` = $q OR `phone` =$q) ORDER BY  `mod_v_verification`.`id` DESC");
	}


	$num_rows = mysql_num_rows($result);
	echo'<div class="col-md-6 hidden-print">
		<p>
		امکان جستجو بر اساس کد کاربری، یا شماره تلفن همراه:
		<form action="addonmodules.php?module=v_verification&go=search" method="POST" class="form-inline">
			<input type="text" name="q" size="40" class="form-control"/>
			<input type="submit" value="جستجو" class="btn btn-info"/>
		</form>
		</p>
	</div>
	<div class="col-md-6 hidden-print"></div>
	<div class="clear cleafix"></div>
		';

	echo '<br/><hr />تعداد نتایج '.$num_rows.'
	<table class="datatable" width="100%" border="0" cellspacing="1" cellpadding="3">
			<tbody>
				<tr>
					<th>کد</th>
					<th>نام کاربری</th>
					<th>شماره همراه</th>
					<th>کد تایید همراه</th>
					<th>وضعیت تایید همراه</th>
					<th></th>
		</tr>';

		while($data = mysql_fetch_array($result)) {
				//user list
				$uid = $data["uid"];
				$userfetch = Capsule::table('tblclients')->where('id', '=', $uid)->first();
				$userfullname = $userfetch->firstname.' '.$userfetch->lastname;

				echo'<tr>
					<form method="POST" action="./addonmodules.php?module=v_verification" />
					<input type="hidden" name="savestatus" value="1" />
					<input type="hidden" name="rowid" value="'.$data["id"].'" />
					<td>'.$data["id"].'</td>
					<td><a href="clientssummary.php?userid='.$data["uid"].'" target="_blank">'.$userfullname.'</a></td>
					<td>'.$data["phone"].'</td>
					<td>'.$data["phonecode"].'</td>
					<td>
						<select name="phonestatus" class="form-input input-sm">
							<option></option>
							<option value="pend" '; if($data["phonestatus"] == 'pend') {echo 'selected';} echo'>معلق</option>
							<option value="active" '; if($data["phonestatus"] == 'active') {echo 'selected';} echo'>فعال</option>
						</select>
					</td>
					<td><input type="submit" value="ذخیره" class="btn btn-success btn-xs" /></td>
					</form>
				</tr>';
			}

		if ($num_rows == 0) {
						echo '<tr><td colspan="10" style="text-align:center;">بدون نتیجه</td></tr>';}
						echo'</tbody></table>
						</div>
						</td></tr></tbody></table>
					</td>
				</tr>
			</tbody>
		</table>';



}

	//end div
	echo '</div></div>';

	// 	if ($licenseResult["localkey"]) {
	// 	# Save Updated Local Key to DB or File
	// 	$localkeydata = $licenseResult["localkey"];
	// 	$update = array("value"=>$localkeydata);
	// 	$where = array("setting"=>'localkey', "module"=> 'v_verification');
	// 	update_query('tbladdonmodules',$update,$where);
	// 	}
	// } elseif ($licenseResult["status"]=="Invalid") {
	// 	echo '<br/><div class="errorbox">لايسنس شما نامعتبر مي باشد، با پشتيباني ونون تماس حاصل نماييد.</div>';
	// } elseif ($licenseResult["status"]=="Expired") {
	// 	echo '<br/><div class="errorbox">لايسنس شما منقضي شده است، براي تمديد يا خريد مجدد به وب سايت ونون مراجعه نماييد.</div>';
	// } elseif ($licenseResult["status"]=="Suspended") {
	// 	echo '<br/><div class="errorbox">لايسنس شما مسدود شده است، جهت پي گيري به واحد پشتيباني ونون تيكت ارسال نماييد.</div>';
	// }

}

function v_verification_sidebar($vars) {

    $modulelink = $vars['modulelink'];
    $version = $vars['version'];
	$LANG = $vars['_lang'];

    $sidebar = '<span class="header"><img src="images/icons/addonmodules.png" class="absmiddle" width="16" height="16" /> verification</span>
	<ul class="menu">
        <li><a href="#">Version: '.$version.'</a></li>
    </ul>';
    return $sidebar;

}

function v_verification_clientarea($vars) {

	require_once __DIR__ . '/smsApi.php';

  $modulelink = $vars['modulelink'];
  $version = $vars['version'];
	$LANG = $vars['_lang'];

	$phoneverify = $vars['option2'];
	$mobilefieldLabel = $vars['option4'];
	$paneluser = $vars['option5'];
	$panelpass = $vars['option6'];
	$panelnumber = $vars['option7'];
	$smstext = $vars['option8'];
	$forbidfiles = $vars['option9'];
	$voicecall = $vars['voicecall'];
	$voicatext = $vars['voicatext'];
	$timeout = $vars['timeout'];
	if(!is_numeric($timeout)){ $timeout = 60;}

	$uid = $_SESSION['uid'];

	// user details
	$udetails = mysql_query("SELECT * FROM `tblclients` WHERE id ='$uid'");
	$udetails = mysql_fetch_array($udetails);

	//fieldid
	$mobilefieldq = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `fieldname` ='$mobilefieldLabel'");
	$mobilefield = mysql_fetch_array($mobilefieldq);
	$mobilefield = $mobilefield['id'];

	// custom field query
	$customfield = mysql_query("SELECT * FROM `tblcustomfieldsvalues` WHERE `fieldid` = '$mobilefield' AND `relid` = '$uid'");
	$customfield = mysql_fetch_array($customfield);
	$phone = $customfield['value'];

	if(!is_numeric($phone)) {$phone = false;}

	if(isset($_GET['phone'])) {
		//index.php?m=v_verification&phone
		$phonecode = $_POST['phonecode'];
		$send['sms'] = true;

		$checkphone = mysql_query("SELECT * FROM `mod_v_verification` WHERE phone='$phone'");
		$checkphone = mysql_num_rows($checkphone);
		if ($checkphone > 0) {$checkphone=1;} else {$checkphone='error';}

		$checkphonecode = mysql_query("SELECT * FROM `mod_v_verification` WHERE phone='$phone' AND phonecode='$phonecode'");
		$checkphonecode = mysql_num_rows($checkphonecode);
		if ($checkphonecode == 0) {$checkphonecode='error';}

		if ($checkphonecode !== 'error') {
			$update = array("phonestatus"=>"active");
			$where = array("phone"=>"$phone","phonecode"=>"$phonecode",);
			update_query('mod_v_verification',$update,$where);
			$phoneactive = 1;
		}

	}  else {
		if (!empty($uid)) {
			// sql query
			$userquery = mysql_query("SELECT * FROM `mod_v_verification` WHERE uid ='$uid'");
			$exist = mysql_num_rows($userquery);
			$check = mysql_fetch_array($userquery);

			if ($exist == 0) {
				$table = "mod_v_verification";
				$phonecode = rand(1000, 9999);

				$values = array("uid"=>"$uid","phone"=>"$phone","phonecode"=>"$phonecode","phonestatus"=>"pend");
				$newid = insert_query($table,$values);

				$erroralert = true;

				if ($phoneverify == 'on') {
						$content = "$smstext $phonecode";
						$smsApi = new smsApi($paneluser, $panelpass, $panelnumber);
						$to = array($phone);

						if ($voicecall == 'on') {
							 $result = $smsApi->sendVoice($phone, "$voicatext $phonecode");
						} else {
							$result = $smsApi->send($phone, $content);
						}

						$send['sms'] = true;
				}

			}  elseif ($check['phone'] !== $phone) {
				$changed = true;
				$erroralert = true;
				$phonecode = rand(1000, 9999);

				if ($check['phone'] !== $phone) {
					$update = array("phone"=>"$phone", "phonecode"=>"$phonecode","phonestatus"=>"pend",);
					$where = array("uid"=>"$uid");
					update_query('mod_v_verification',$update,$where);

					if ($phoneverify == 'on') {
						// send sms
						$content = "$smstext $phonecode";
						$smsApi = new smsApi($paneluser, $panelpass, $panelnumber);
						$to = array($phone);
						$from = $panelnumber;

						if ($voicecall == 'on') {
							$result = $smsApi->sendVoice($phone, "$voicatext $phonecode");
						} else {
							$result = $smsApi->send($phone, $content);
						}

						$send['sms'] = true;
					}
				}
			} elseif ($check['phonestatus'] == 'pend') {
				$phonecode = $check['phonecode'];

				if ($phoneverify == 'on') {
					if ($_SESSION['resendTime'] + (10 * $timeout) < time()) {
						if ($_POST['resend'] == 'sms') {
							// send sms
								$content = "$smstext $phonecode";
								$smsApi = new smsApi($paneluser, $panelpass, $panelnumber);
								$to = array($phone);
								$from = $panelnumber;

								if ($voicecall == 'on') {
									$result = $smsApi->sendVoice($phone, "$voicatext $phonecode");
								} else {
									$result = $smsApi->send($phone, $content);
								}
								$_SESSION['resendTime'] = time();
								$_SESSION['resend'] = true;
						}
					} else {
						$_SESSION['resend'] = false;
					}
					$send['sms'] = true;
					$erroralert = true;
				}
			} else {
				$erroralert = 'success';
				$success = true;
			}
		}
	}

  return array(
    'pagetitle' => 'تایید تلفن همراه',
    'breadcrumb' => array('index.php?m=v_verification'=>'تایید تلفن همراه'),
    'templatefile' => 'v_verification',
    'requirelogin' => false, # or false
    'vars' => array(
	    'phoneverify' => $phoneverify,
			'clientsgroup' => $clientsgroup,
			'erroralert' => $erroralert,
			'success' => $success,
			'phone' => $phone,
			'checkphone' => $checkphone,
			'checkphonecode' => $checkphonecode,
			'phoneactive' => $phoneactive,
			'send' => $send,
			'changed' => $changed,
			'err' => $err,
    ),
  );
}

function verification_pagination($query, $pageNum, $limit) {
	$counter = mysql_num_rows($query);
	$pagination = array();

	// counting the offset
	$offset = ($pageNum - 1) * $limit;
	$nextpage = $pageNum +1;
	$prepage = $pageNum -1;
	$ifnext = $counter/($limit*$pageNum);
	$totalPage = ceil($counter/$limit);
	if($totalPage == 0) {$totalPage = 1;}

	if ($counter > $limit AND $pageNum > 1) {$pagination['pre'] = true;}
	if ($counter > $limit AND $ifnext > 1) {$pagination['next'] = true;}

	if ($counter > $limit) {
		$i = 1;
		$jumper = '<select name="page" onchange="submit()" class="form-control input-sm">';
			while ($i <= $totalPage) {
				$jumper .= '<option value="'.$i.'" ';
				if($pageNum == $i) {$jumper .= 'selected="selected"';}
				$jumper .= '>'.$i.'</option>';
				$i++;
			}
		$jumper .= '</select><input type="submit" value="برو" class="btn btn-sm btn-default">';

		$pagination['jumper'] = $jumper;
	}

	$pagination['counterText'] = 'صفحه '.$pageNum.' از مجموع '.$totalPage.' صفحه ';
	$pagination['counter'] = $counter;
	$pagination['nextpage'] = $nextpage;
	$pagination['prepage'] = $prepage;
	$pagination['offset'] = $offset;

	return $pagination;
}

?>
