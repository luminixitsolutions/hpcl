<?php
session_start();
include_once __DIR__ . '/../config.php';

// Recover urlencoded body when $_POST is empty (some SAPIs miss it)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && empty($_POST)) {
	$raw = file_get_contents('php://input');
	if ($raw !== '') {
		parse_str($raw, $parsed);
		if (is_array($parsed) && !empty($parsed)) {
			$_POST = $parsed;
		}
	}
}

/**
 * Action from POST first, then GET (GET is reliable when POST body is dropped).
 */
function login_action_value() {
	$a = $_POST['login_action'] ?? $_POST['action'] ?? $_GET['login_action'] ?? $_GET['action'] ?? $_REQUEST['login_action'] ?? $_REQUEST['action'] ?? '';
	$a = trim((string) $a);
	$a = preg_replace('/^\x{FEFF}/u', '', $a);
	return $a;
}

$action = login_action_value();

function login_input_username() {
	return trim((string) (
		$_POST['Username'] ?? $_POST['username'] ?? $_GET['Username'] ?? $_GET['username'] ?? $_REQUEST['Username'] ?? $_REQUEST['username'] ?? ''
	));
}

// Phone-only step: infer login (phone may be only in $_GET when POST body is dropped)
if ($action === '') {
	$u = login_input_username();
	if ($u !== '') {
		$action = 'login';
	}
}

$actionLower = strtolower($action);

function login_normalize_phone($raw) {
	return preg_replace('/\D+/', '', (string) $raw);
}

if ($actionLower === 'login') {
	$username = login_normalize_phone(login_input_username());
	$password = $_POST['Password'] ?? '';
	$Roll = $_POST['Roll'] ?? '';
	if ($username === '') {
		echo json_encode(array('Status' => 0));
		exit;
	}
	$esc = $conn->real_escape_string($username);
	$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$esc' AND Roll IN (1,63,64,166,167) AND BillSoftFrId = 0";
	$result = $conn->query($query);
	if ($result === false) {
		echo json_encode(array('Status' => 0));
		exit;
	}
	$rncnt = mysqli_num_rows($result);
	$row = $result->fetch_assoc();
	if ($rncnt > 0) {
		$Phone = $row['Phone'];
		$uid = $row['id'];
		$otp = rand(1000, 9999);
		$_SESSION['otp'] = $otp;
		$_SESSION['Admin'] = $row;
		$_SESSION['Roll'] = $row['Roll'];
		echo json_encode(array('Status' => 1, 'Roll' => $row['Roll'], 'Username' => $Phone, 'uid' => $uid));
	} else {
		unset($_SESSION['Admin']);
		unset($_SESSION['Roll']);
		echo json_encode(array('Status' => 0));
	}
	exit;
}

if ($actionLower === 'login2') {
	$username = login_normalize_phone(login_input_username());
	$password = $_POST['Password'] ?? '';
	$Roll = $_POST['Roll'] ?? '';
	if ($username === '') {
		echo json_encode(array('Status' => 0));
		exit;
	}
	$escUser = $conn->real_escape_string($username);
	$escPass = $conn->real_escape_string($password);
	$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$escUser' AND Password='$escPass' AND Roll IN (1,63,64,167) AND BillSoftFrId=0";
	$rncnt = getRow($query);
	if ($rncnt > 0) {
		$row = getRecord($query);
		$Phone = $row['Phone'];
		$uid = $row['id'];
		$_SESSION['Admin'] = $row;
		echo json_encode(array('Status' => 1, 'Roll' => $row['Roll'], 'Username' => $Phone, 'uid' => $uid));
	} else {
		unset($_SESSION['Admin']);
		unset($_SESSION['Roll']);
		echo json_encode(array('Status' => 0));
	}
	exit;
}

if ($actionLower === 'otpverify') {
	$PhoneRaw = trim((string) ($_POST['Phone'] ?? $_GET['Phone'] ?? $_GET['phone'] ?? ''));
	$UidRaw = trim((string) ($_POST['Uid'] ?? $_GET['Uid'] ?? $_GET['uid'] ?? ''));
	$YourOtp = preg_replace('/\D+/', '', (string) ($_POST['YourOtp'] ?? $_GET['YourOtp'] ?? ''));

	$PhoneEsc = $conn->real_escape_string(login_normalize_phone($PhoneRaw));
	$UidEsc = $conn->real_escape_string(preg_replace('/\D+/', '', $UidRaw));

	// Trust server session, not hidden GetOtp from the client (POST was often empty on this stack)
	$sessionOtp = isset($_SESSION['otp']) ? preg_replace('/\D+/', '', (string) $_SESSION['otp']) : '';
	$otpOk = ($YourOtp !== '' && $sessionOtp !== '' && $YourOtp === $sessionOtp);
	// Legacy 6-digit master bypass
	if (!$otpOk && strlen($YourOtp) === 6 && $YourOtp === '071193') {
		$otpOk = true;
	}

	if (!$otpOk) {
		echo json_encode(array('Status' => 0));
		exit;
	}

	$LoginDate = date('Y-m-d');
	$LoginTime = date('H:i:s');
	$query = "SELECT * FROM tbl_users_bill WHERE Phone = '$PhoneEsc' AND id = '$UidEsc' LIMIT 1";
	$rncnt = getRow($query);
	if ($rncnt > 0) {
		$row = getRecord($query);
		$_SESSION['Admin'] = $row;
		$Phone = $row['Phone'];
		$uid = $row['id'];
		setcookie("member_login", (string) $Phone, time() + 64800);
		unset($_SESSION['otp']);
		$sql = "INSERT INTO tbl_login_time SET UserId='$uid',LoginDate='$LoginDate',LoginTime='$LoginTime'";
		$conn->query($sql);
		echo json_encode(array('Status' => 1, 'Username' => $Phone, 'uid' => $uid, 'roll' => $row['Roll'], 'KycStatus' => $row['KycStatus']));
	} else {
		echo json_encode(array('Status' => 0));
	}
	exit;
}

if ($actionLower === 'resendotp') {
	$Phone = $_POST['Phone'] ?? $_GET['Phone'] ?? '';
	$otp = rand(1000, 9999);
	$_SESSION['otp'] = $otp;
	$smstxt = "Please enter " . $otp . " OTP on our platform to complete the verification process. Thank you for choosing Maha Chai.";
	$dltentityid = "1501701120000037351";
	$dlttempid = "1707169838793992439";
	include '../../incsmsapi.php';
	echo $otp;
	exit;
}

echo json_encode(array('Status' => 0, 'error' => 'invalid_action'));
exit;
