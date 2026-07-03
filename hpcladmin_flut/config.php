<?php
/**
 * Safe request values (avoids PHP 8+ "Undefined array key" on $_POST / $_GET).
 */
function req($key, $default = null) {
	if (isset($_POST[$key])) {
		return $_POST[$key];
	}
	if (isset($_GET[$key])) {
		return $_GET[$key];
	}
	return $default;
}

/** Read form/ajax action (avoids blocked POST key name "action" on some servers). */
function reqAction($default = '') {
	foreach (array('form_action', 'action', 'act') as $key) {
		$val = req($key, null);
		if ($val !== null && $val !== '') {
			return trim((string) $val);
		}
	}
	if (!empty($_SERVER['QUERY_STRING'])) {
		$qs = [];
		parse_str($_SERVER['QUERY_STRING'], $qs);
		foreach (array('form_action', 'action', 'act') as $key) {
			if (!empty($qs[$key])) {
				return trim((string) $qs[$key]);
			}
		}
	}
	// Product add form: submit button present without action param
	if (isset($_POST['submit']) && (isset($_POST['ProductName']) || isset($_POST['TempPrdId']))) {
		return 'Add';
	}
	return $default;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hpcl";

/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    // echo "Successfully Connected";
}
$Proj_Title = "HPCL";
$SiteUrl = "https://rjorg.in/pandavcollege/";
date_default_timezone_set("Asia/Kolkata");

function appPageUrl($page) {
	$userId = $_GET['user_id'] ?? ($_SESSION['Admin']['id'] ?? '');
	$lat = $_GET['lat'] ?? ($_SESSION['Admin']['lat'] ?? '');
	$lng = $_GET['lng'] ?? ($_SESSION['Admin']['lng'] ?? '');
	$params = array_filter([
		'user_id' => $userId,
		'lat' => $lat,
		'lng' => $lng,
	], function ($v) {
		return $v !== '' && $v !== null;
	});
	if (empty($params)) {
		return htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
	}
	$sep = (strpos($page, '?') !== false) ? '&' : '?';
	return htmlspecialchars($page . $sep . http_build_query($params), ENT_QUOTES, 'UTF-8');
}

function getList($sql){
  global $conn;
    $row3 = [];
    $res2 = $conn->query($sql);
    if (!$res2) {
        return $row3;
    }
    while($row2 = $res2->fetch_assoc()){
        $row3[] = $row2;
    }
    return $row3;
}

function getRecord($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    if (!$res2) {
        return null;
    }
	$row2 = $res2->fetch_assoc();
    return $row2;
}

function getRow($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    if (!$res2) {
        return 0;
    }
	$row2 = mysqli_num_rows($res2);
    return $row2;
}

/**
 * Production hosts may not populate $_POST for some ajax POST bodies.
 * For scripts under ajax_files/, merge request data into $_POST when empty.
 * Never replace a real POST body with GET-only params.
 */
function bootstrapAjaxRequest() {
	static $done = false;
	if ($done) {
		return;
	}
	$done = true;

	$script = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
	if (strpos($script, '/ajax_files/') === false) {
		return;
	}

	if (!empty($_POST)) {
		return;
	}

	$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
	$contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

	// Browser form POST (multipart or urlencoded): trust PHP superglobals only.
	// If $_POST is empty here, do not fall back to $_GET (that drops form fields).
	if ($method === 'POST' && (
		stripos($contentType, 'multipart/form-data') !== false
		|| stripos($contentType, 'application/x-www-form-urlencoded') !== false
		|| $contentType === ''
	)) {
		return;
	}

	$raw = file_get_contents('php://input');
	if ($raw !== false && $raw !== '') {
		if (stripos($contentType, 'application/json') !== false) {
			$decoded = json_decode($raw, true);
			if (is_array($decoded) && !empty($decoded)) {
				$_POST = $decoded;
				$_REQUEST = array_merge($_REQUEST, $_POST);
				return;
			}
		}
		$parsed = [];
		parse_str($raw, $parsed);
		if (!empty($parsed)) {
			$_POST = $parsed;
			$_REQUEST = array_merge($_REQUEST, $_POST);
			return;
		}
	}

	if (!empty($_GET)) {
		$_POST = array_merge($_GET, $_POST);
		$_REQUEST = array_merge($_REQUEST, $_POST);
	}
}

bootstrapAjaxRequest();
?>