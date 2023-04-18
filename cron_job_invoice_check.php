<?php
include 'config.php';
require_once('./PHPMailer/PHPMailerAutoload.php');

$query = "SELECT order_id, order_receiver_userid, DATE_ADD(order_date, INTERVAL 5 DAY) dueDate, order_amount_due-order_amount_paid amountDue FROM invoice_order WHERE fully_paid = 0";
$requested_invoice = $db->query($query);

// send sms to the providers
require './twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
// ===== twilio config setting end=====
$twilio = new Client($twilioAccountSid, $twilioAuthtoken);

// send email to the providers
foreach($requested_invoice as $invoice) {
    $userId = $invoice['order_receiver_userid'];
    $db->where('userId', $userId);
    $provider = $db->getOne('prescribers');
    $providerEmail = $provider['email'];
    $providerPhone = $provider['phone'];
    
    $now = date('Y-m-d H:i:s');
    $invoice_due_date = $invoice['dueDate'];
    $invoice_amount_due = $invoice['amountDue'];
    
    // If the due date has already passed
    if($now >= $invoice_due_date) {
        $hdate1 = new DateTime($now);
        $hdate2 = new DateTime($invoice_due_date);
        $hinterval = $hdate1->diff($hdate2);
        $pass_due_days = (int)$hinterval->days + 1;
        $pass_due_days = $pass_due_days == 1 ? $pass_due_days.' day' : $pass_due_days.' days';
        $bodyContent = "<div class='invoice_warning'>Your invoice balance is $pass_due_days past due! Please make a payment now of $$invoice_amount_due to continue. <br> <a href='https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1' target='_blank'>https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1</a></div>";
    } else {
        $bodyContent = "<div class='invoice_warning'>You have an invoice to be paid. Please make a payment now of $$invoice_amount_due to continue. <br> <a href='https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1' target='_blank'>https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1</a></div>";
    }
    
	$mail = new PHPMailer;
	$mail->Host = $mailHost;    // Specify main and backup SMTP servers
    $mail->Username = $mailUsername;  // SMTP username
    $mail->Password = $mailPassword;			// SMTP password
    $mail->setFrom($mailsetFrom, $mailsetName);
    $mail->addReplyTo($mailaddReplyTo, $mailsetName);
    $mail->addAddress($providerEmail);   // Add a recipient
    
	$mail->isHTML(true); 
	$mail->Subject = "You have an invoice to be paid with $site_title";
	
	$mail->Body  = $bodyContent;
	
	if($mail->send()) {
	    echo 'mail sent => '.$providerEmail.'<br>';
	} else {
	    echo 'mail failed';
	}  
	
	$providerMessageBody = "You have an invoice to be paid with $site_title. Please make a payment now of $$invoice_amount_due to continue. <a href='https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1' target='_blank'>https://portal.seamlesschex.com/#/checkout/ebec3440-8a92-11ec-bbac-7f9e8976abd1/9f8c4b50-7583-11ec-b7d6-b57865b972d2/0/1</a>";
    try {
        // remove space, replace -  with '', and remove country code +1
        $providerPhone = (int) filter_var(str_replace('-','', preg_replace('/^\+?1|\|1|\D/', '', $providerPhone)), FILTER_SANITIZE_NUMBER_INT);
        $providerPhone = '+1'.$providerPhone;
        $message = $twilio->messages
                          ->create($providerPhone, // to
                                  ["body" => $providerMessageBody, "from" => $twilio_from_registered_number]
                          );
        
        print($message->sid);
    } catch (Twilio\Exceptions\RestException $e) {
        echo "Couldn't send message to $providerPhone\n".'<br>';
    }
}
?>