<?php
include 'config.php';
require_once('./PHPMailer/PHPMailerAutoload.php');

$requested_fields = array(
    'caqh_username_approval_status' => 'CAQH Username',
    'caqh_password_approval_status' => 'CAQH Password',
    'pecos_username_approval_status' => 'PECOS Username',
    'pecos_password_approval_status' => 'PECOS Password',
    'voidedCheck_approval_status' => 'Voided Check',
    'boardCertification_approval_status' => 'Copy of Degree/ Board Certification',
    'copyOfActiveLicenses_approval_status' => 'Copy of all active licenses',
    'cms460Form_approval_status' => 'Copy of CMS 460 form',
    'w9Form_approval_status' => 'W9 Form',
    'cv_resume_approval_status' => 'Curriculum Vitae/ Updated Resume',
);

$query = "select 
    id, 
    email,
    phone,
    allowEmailNotification,
    allowSMSNotification,
    caqh_username_approval_status, 
    caqh_password_approval_status, 
    pecos_username_approval_status, 
    pecos_password_approval_status, 
    voidedCheck_approval_status, 
    boardCertification_approval_status, 
    copyOfActiveLicenses_approval_status, 
    cms460Form_approval_status, 
    w9Form_approval_status, 
    cv_resume_approval_status 
FROM prescribers 
WHERE
    credentialing_status = '".$credentialing_status_pending_approval."'
";

$requested_res = $db->query($query);
$email_needed_providers = [];
$sms_needed_providers = [];
foreach($requested_res as $req) {
    foreach($requested_fields as $field_name => $field_label) {
        if($req['allowEmailNotification'] == 1 && $req[$field_name] == 2) {
            $email_needed_providers[$req['email']][]= $field_label;
            
        }
        
        if($req['allowSMSNotification'] == 1 && $req[$field_name] == 2) {
            $sms_needed_providers[$req['phone']][]= $field_label;
        }
    }
}

// send email to the providers
foreach($email_needed_providers as $providerEmail => $fields) {
    $all_fields = implode(', ', $fields);
    $field_string = substr_replace($all_fields, ' and', strrpos($all_fields, ','), 1);
    
	$mail = new PHPMailer;
	$mail->Host = $mailHost;    // Specify main and backup SMTP servers
    $mail->Username = $mailUsername;  // SMTP username
    $mail->Password = $mailPassword;			// SMTP password
    $mail->setFrom($mailsetFrom, $mailsetName);
    $mail->addReplyTo($mailaddReplyTo, $mailsetName);
    $mail->addAddress($providerEmail);   // Add a recipient
    
	$mail->isHTML(true); 
	$mail->Subject = "Required Credentialing to be submitted/uploaded";
	$bodyContent = "Dear valued provider, <br/> We are waiting for your $field_string information to be submitted/uploaded to the portal.  Please add this as soon as possible so we may begin your credentialing process as to have it completed at the earliest possible time.  The sooner we complete your credentialing the sooner you can start receiving payments from insurance payers.   If you have any questions please do not hesitate to call or email us.  Thank you!";
	
	$mail->Body  = $bodyContent;
	
	if($mail->send()) {
	    echo 'mail sent => '.$providerEmail.'<br>';
	} else {
	    echo 'mail failed';
	}  
}

// send sms to the providers
require './twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
// ===== twilio config setting=====
$accountSid = 'ACc0c58e353a75700787309c841d154b6b';
$apiKeySid = 'SKdf71d49026b87abc7aa2ac16c5384e9e';
$apiKeySecret = 'vkQw60CmSLY9ug61CWRxQoFeVlNEst9m';
$autotoken  = "f86a273da3b1015e4caafca42f35d428";
$from_registered_number = "+19542310288";
// ===== twilio config setting end=====
$twilio = new Client($accountSid, $autotoken);

foreach($sms_needed_providers as $providerPhone => $fields) {
    $all_fields = implode(', ', $fields);
    $field_string = substr_replace($all_fields, ' and', strrpos($all_fields, ','), 1);
    
    $providerMessageBody = "Dear valued provider, We are waiting for your $field_string information to be submitted/uploaded to the portal.  Please add this as soon as possible so we may begin your credentialing process as to have it completed at the earliest possible time.  The sooner we complete your credentialing the sooner you can start receiving payments from insurance payers.   If you have any questions please do not hesitate to call or email us.  Thank you!";
    try {
        // remove space, replace -  with '', and remove country code +1
        $providerPhone = (int) filter_var(str_replace('-','', preg_replace('/^\+?1|\|1|\D/', '', $providerPhone)), FILTER_SANITIZE_NUMBER_INT);
        $providerPhone = '+1'.$providerPhone;
        $message = $twilio->messages
                          ->create($providerPhone, // to
                                  ["body" => $providerMessageBody, "from" => $from_registered_number]
                          );
        
        print($message->sid);
    } catch (Twilio\Exceptions\RestException $e) {
        echo "Couldn't send message to $providerPhone\n".'<br>';
    }
}

$data = array(
    'execute_time' => date('Y-m-d H:i:s')
);
$db->insert('cron_logs', $data);
?>