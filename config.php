<?php 
// session_set_cookie_params ( 2592000, "/",  "", 1, 1 );
// error_reporting(E_ALL);
// error_reporting(1);
// date_default_timezone_set('EST');
date_default_timezone_set('America/New_York');
// ini_set('post_max_size', '100M');
if (session_status() == PHP_SESSION_NONE) {
    $session_saved_path = realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../saved_sessions_tvmdlt');
    ini_set('session.gc_maxlifetime',86400);
    session_save_path($session_saved_path);
    
    ob_start();
    session_start();
}

require_once('variables.php');

require_once ('classes/MysqliDb.php');
$hostname = 'localhost';

$username = 'dev_tvmdlt';
$password = 'Z$CBX87BXSyVJOQ18DDXNS_DTV';
$dbname = 'dev_tvmdlt';

$recaptchaSiteKey = '6LdhZPYcAAAAAC_oTovj6HxieAv9aHlWf52t6GOj';
$recaptchaSecretKey = '6LdhZPYcAAAAAFIQafaxSed3XESOX6Mlcd61qZTX';


// ===== twilio config setting=====
$twilioAccountSid = 'ACc0c58e353a75700787309c841d154b6b';
$twilioApiKeySid = 'SKdf71d49026b87abc7aa2ac16c5384e9e';
$twilioApiKeySecret = 'vkQw60CmSLY9ug61CWRxQoFeVlNEst9m';
$twilioAuthtoken  = "f86a273da3b1015e4caafca42f35d428";
$twilio_from_registered_number = "+19542310288";
// ===== twilio config setting end=====

// ===== fax credentials=====
$faxUserId = '26b3c0a2-0662-4494-b83c-1a942dc13730';
$faxAppID = '5fbd02c4-cca6-441a-b9ed-d8e8df590566';
$faxAPIKey = 'N3FeyACbc85QYWj5';
$faxAuth = base64_encode($faxAppID.':'.$faxAPIKey);
// ===== fax credentials end=====


$db = new MysqliDb ($hostname, $username, $password, $dbname);

function isLoginSessionExpired() {
    if(isset($_SESSION['UserData']['session_duration'])){
        $login_session_duration = $_SESSION['UserData']['session_duration'];
        if(isset($_SESSION['UserData']['loggedin_time']) and isset($_SESSION['UserData']['id'])){
            if(((time() - $_SESSION['UserData']['loggedin_time']) > $login_session_duration)){
                return true;
            }
        }
    }
    return false;
}
function get_base_url(){
    $base_path = '/home/ov1ge12m7lqv/public_html/dev.tvmdlt.com/'; //'/telescrips'; //use this for localhost only
 //     $base_path = '/home/devdialmymd'; //use this for localhost only
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'https';
    }
    if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $base_path;
    else
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
}
?>
