<?php
include 'header.php';

require 'PHPMailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;

		$mail->Host = $mailHost;    // Specify main and backup SMTP servers
		$mail->Username = $mailUsername;  // SMTP username
		$mail->Password = $mailPassword;			// SMTP password
		$mail->setFrom($mailsetFrom, $mailsetName);
		$mail->addReplyTo($mailaddReplyTo, $mailsetName);
		$mail->addAddress('dev.john@televisitmd.com');   // Add a recipient
		$mail->isHTML(true);
		
        $firstName = 'John';
        $lastName = 'Doe';
        
		$bodyContent = 
		    '<html>
                <body style="font-family: Roboto, sans-serif;">
                    <div class="main_center" style="width:100%; margin:0px auto;">
                    	<div class="main_cnntn" style="background-color:#fff;padding: 10px 10px;-webkit-box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);-moz-box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);">
                            <i class="far fa-check-circle" style="color: green;font-size: 90px;"></i>
                    		<div style="text-align:center"><img src="'.get_base_url().'/crm/assets/images/tvmdlt_logo.png" width="150"/></div>
                            <p style="color:#505050; line-height: 17px;text-align:left;">
                                Hello '.$firstName.' '.$lastName.', <br><br>
                                Thank you for registering within the TelevisitMD Locum Tenens network of telehealth/telemedicine providers.  
                                <br><br>
                                We are happy to have you as part of our family and welcome you to enjoy the many benefits TelevisitMD has to offer when utilizing our software as a service which enables you to have freedom and flexibility between your work and personal life as a valued healthcare professional providing quality care to patients.
                                <br><br>
                                To continue and complete your onboarding process, on your own you may follow and finish the steps shared with you in your provider welcome packet.  you can also access your provider packet by <a href="https://drive.google.com/drive/u/0/folders/1TGfqqEYcaK9RLCCr_zcBaRztTFKB8kQh" target="_new">clicking here</a>
                                <br><br>
                                If you prefer some assistance, you may also schedule a time/date that\'s convenient for you, where one of our dedicated account managers will meet with you to help guide you through completing your registration and activation process.  Once your fully activated, you will immediately begin to match with patients, increasing your billables and already on your way to a profitable career forward within the telehealth spectrum.
                                <br><br>
                                To schedule a meeting with your dedicated account manager, <b>   
                                <a href="https://calendly.com/d/zszq-ty8m/onboarding-instruction-demo" target="_new">clicking here:</a></b>
                                <br>
                                <br>
                                If you need personal assistance or support you may call: <b>1-888-407-4699 Monday through Friday 9am - 5:30pm EST.</b>
                                <br>
                                <br>
                                <b>All</b> calls are monitored for quality assurance and training purposes.  If support is busy/unable to answer, please leave a detailed voice message as to why your calling and a dedicated account manager will return your call within 24-48 hours or less.  
                                <br>
                                <br>
                                <b>However, it is highly recommended and strongly suggested with great preference that <strong>ALL</strong> communication should take place through your account while logged inside the portal using our secure message center.   Your secure message center is the fastest turnaround, is available and receives replies 24-7 but, the option to call our 888# is always available M-F 9am - 5:30pm EST.</b>
                                <br>
                                <br>
                                <b>****REMINDER:</b>  All requests for onboarding meetings with a dedicated account manager must be scheduled to the calendar by <b><a href="https://calendly.com/d/zszq-ty8m/onboarding-instruction-demo" target="_new">clicking here</a></b> and can not be called in to our support # as in what we consider a "walk-in" or "on the spot" onboarding.  If its not already scheduled to the calendar it can not be done any other way.
                                <br>
                                <br>
                                We look forward to sharing the caring of patients with you at TelevisitMD.  
                                <br><br>
                                We are also very pleased to begin working endlessly hard for you in our Backoffice, on all the administrative legwork, whether it be verifying a patients insurance, scheduling a patient, staying up to date with your Medmal coverage or any other of the many benefits offered to you by joining the TelevisitMD network and its partners like our RCM company Harwood Healthcare corp. who strive hard to serve you every single day in submitting insurance claims on your behalf and credential you in the best way possible to ensure your billables increase and expand your career into virtual care coordination, remote patient monitoring and telehealth consultations with qualified patients using the TelevisitMD Virtual Practice and care coordination software as a service.
                                <br><br>
                                So without further adieu, WELCOME ABOARD! and a very BIG Cheers to all of us, growing this world in a better way together everyday!
                                <br><br>
                                Now it\'s time to complete patient consults and begin increasing your billables!  
                                <br><br>
                                Please proceed to the completion of your remaining steps to activate and get started today!
                            </p>
                            <div style="text-align:center">
                    		    <a href="'.get_base_url().'/login.php" style="background-color:#fe9800;color:#fff;padding:  10px 17px;display:  inline-block;border: none;margin: 10px 0px 0px 0px;">Login</a>
                    		</div>
                    	</div>
                    </div>
                </body>
            </html>';

		$mail->Subject = 'Email from TelevisitMD Locum Tenens';
		$mail->Body    = $bodyContent;
		if($mail->send()) {
		    echo 'sent';
		} else {
		    echo 'failed';
		}
		
		?>