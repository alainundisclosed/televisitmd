<?php
require_once('config.php');

function getUserIP() {
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR']; 
    if(filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    }
    else {
        $ip = $remote;
    } 
    return $ip;
}

$userId = $_SESSION['UserData']['id'];

$userRole = $_SESSION['UserData']['URole'];

$check_id = isset($_GET['check_id']) ? $_GET['check_id'] : '';

$db->where('check_id', $check_id);
$db->getOne('seamlesscheck_thankyou');
if($db->count == 0) {
    $data = array(
        'check_id' => $check_id,
        'userId' => $userId,
        'userRole' => $userRole,
        'status' => 'Subscription Activated',
        'userIP' => getUserIP(),
        'addedAt' => date('Y-m-d H:i:s')
    );
    
    $db->insert('seamlesscheck_thankyou', $data);
}

// check if the supplier status
$db->where('userId', $userId);
$prescriber = $db->getOne('prescribers');
$approvalStatus = $prescriber['presciberStatus'];

if($approvalStatus != $g_provider_status_active) {
    
    // approve this provider
    $approveData = array(
        'presciberStatus' => $g_provider_status_active,
        'approvedAt' => date('Y-m-d H:i:s')
    );
    $db->where('userId', $userId);
    $update_id = $db->update('prescribers', $approveData);    
           
    if($update_id) {
        // insert change status log history
        $log_data = array(
            'status' => $g_provider_status_active,
            'suspended_reason' => 'Subscription Activated',
            'changed_date' => date('Y-m-d H:i:s'),
            'userId' => $userId,
            'roleType' => 'Provider',
            'changedBy' => $userId
        );
        $db->insert('statusChangeLogs', $log_data);
    }
}

?>
<div class="container">
    <h1 class="text-center" style="text-align: center;padding-top: 150px;font-family: sans-serif;">Thank you for your payment!</h1>
    <h3 class="text-center" style="text-align: center;padding-top: 20px;font-family: sans-serif;">You can close this tab and get back to <?=$site_title?></h3>
    <?php if(!$userId) { ?>
        <h4 class="text-center" style="text-align: center;padding-top: 20px;font-family: sans-serif;">The system cannot find your info. Please create a ticket with your check-id <?=$check_id?>.</h5>
    <?php } ?>
</div>

<?php
    if(!$userId) {
        require 'PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;

		$mail->Host = $mailHost;    // Specify main and backup SMTP servers
// 		$mail->isSMTP();
//      $mail->Port = $mailPort;
//      $mail->SMTPAuth = true;
//      $mail->SMTPSecure = 'ssl';
        
		$mail->Username = $mailUsername;  // SMTP username
		$mail->Password = $mailPassword;			// SMTP password
		$mail->setFrom($mailsetFrom, $mailsetName);
		$mail->addReplyTo($mailaddReplyTo, $mailsetName);
		$mail->addAddress('dev.john@televisitmd.com');   // Add a recipient
		$mail->isHTML(true);
		$bodyContent = $check_id;
		$mail->Subject = 'Check '.$site_title;
		$mail->Body    = $bodyContent;
		$mail->send();
    }
?>