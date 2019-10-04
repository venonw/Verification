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

function v_verification_hook_check($vars) {
	//fieldlabel
	$mobilefieldlabelq = mysql_query("SELECT `value`,`setting` FROM `tbladdonmodules` WHERE `module` ='v_verification'");

	while ($row = mysql_fetch_array($mobilefieldlabelq)) {
		$value = $row['value'];
		$setting = $row['setting'];
    $rows[$setting]= $value;
	}

	$phoneverify = $rows['option2'];
	$mobilefieldLabel = $rows['option4'];
	$paneluser = $rows['option5'];
	$panelpass = $rows['option6'];
	$panelnumber = $rows['option7'];
	$smstext = $rows['option8'];
	$forbidfiles = $rows['option9'];

	$uid = $_SESSION['uid'];

	if (!empty($uid) AND $phoneverify == 'on') {

		//forbidfiles
		$forbidfilesarray = explode(",",$forbidfiles);

		$filename = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		$query = $_SERVER['QUERY_STRING'];

		//fieldid
		$mobilefieldq = mysql_query("SELECT `id` FROM `tblcustomfields` WHERE `fieldname` ='$mobilefieldLabel'");
		$mobilefield = mysql_fetch_array($mobilefieldq);
		$mobilefield = $mobilefield['id'];

		// sql query
		$check = mysql_query("SELECT * FROM `mod_v_verification` WHERE uid ='$uid'");
		$check = mysql_fetch_array($check);

		// custom field query
		$customfield = mysql_query("SELECT * FROM `tblcustomfieldsvalues` WHERE fieldid = '$mobilefield' AND relid = '$uid'");
		$customfield = mysql_fetch_array($customfield);
		$phone = $customfield['value'];

		if (in_array($filename, $forbidfilesarray) OR in_array($query, $forbidfilesarray)) {
			if($check['phonestatus'] == 'pend') {
				ob_start();
				header('location: index.php?m=v_verification');
				exit();
			}
			if($check['phone'] !== $phone) {
				ob_start();
				header('location: index.php?m=v_verification');
				exit();
			}
			if(empty($check['phone'])) {
				ob_start();
				header('location: index.php?m=v_verification');
				exit();
			}



		}

		if($check['phonestatus'] == 'pend' OR $check['phone'] !== $phone OR empty($check['phone'])) {
			$return = array();
			$return = array("phoneerror" => true);
			return $return;
		}
	}
}
add_hook("ClientAreaPage",1,"v_verification_hook_check");
?>
