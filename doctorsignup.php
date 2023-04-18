<?php 
date_default_timezone_set('America/New_York');

include 'header.php';

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

require_once "Mobile-Detect/Mobile_Detect.php";
$detect = new Mobile_Detect;

// Get recruiters
$rec_sql = "SELECT * FROM users WHERE (RoleType = 'Recruiter Manager' OR RoleType = 'Recruiter') AND isDelete = 'No' ORDER BY Name ASC";
$recruiters = $db->query($rec_sql);

function getBrowser() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "N/A";

    $browsers = array(
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Mobile browser'
    );

    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) { $browser = $value; }
    }

    return $browser;
}

// device and browser detec
$isMob = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
$isTab = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet"));

$access_device = '';
$access_browser = '';
if(!$isMob && !$isTab) {
    $access_device = 'Computer';
    $access_browser = getBrowser();
} else {
    // Check for a specific platform with the help of the magic methods:
    if( $detect->isiOS() ){
        $access_device .= 'iOS ';
    } else if( $detect->isAndroidOS() ){
        $access_device .= 'Android ';
    } else {
        $access_device .= 'Other Mobile ';
    }
    
    // Any mobile device (phones or tablets).
    if ( $detect->isMobile() ) {
        $access_device .= 'Mobile';
    } else if( $detect->isTablet() ){
        $access_device .= 'Tablet';
    } else {
        $access_device .= 'Other Type';
    }
    
    $mobile_browsers = ['Chrome', 'iOS', 'UCBrowser', 'Opera', 'Firefox', 'Safari', 'IE', 'Edge'];
    foreach($mobile_browsers as $browser) {
        if($detect->is($browser)) {
            $access_browser = $browser;
            break;
        }    
    }
    if($access_browser == "") {
        $access_browser = "Other Browser";
    }
}

if(isset($_POST['Submit'])){
	//echo '<pre>'; print_r($_POST); print_r($_FILES); 
    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    } else {
        $captcha = false;
    }
    
    if (!$captcha) {
        //Do something with error
        echo '<p style="text-align:center">reCAPTCHA verification failed</p>'; exit;
    } else {
        $secretKey = $recaptchaSecretKey;
        // post request to server
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
        $response = file_get_contents($url);
        $responseKeys = json_decode($response,true);
        // should return JSON with success as true
        if(!$responseKeys["success"]) {
              echo '<p style="text-align:center">reCAPTCHA verification failed</p>'; exit;
        }
    }
		
	$firstName = $db->escape($_POST['firstName']);
	$lastName = $db->escape($_POST['lastName']);
	$degree = $db->escape($_POST['degree']);
	$email = $db->escape($_POST['emailid']);
	$phone = $_POST['primaryPhone'] ? $db->escape($_POST['primaryPhone']) : null;
	$recruiter = $_POST['recruiter'];
	
	$password = $_POST['password'];

	$data1 = Array(
		'Name' => $firstName.' '.$lastName,
		'firstName' => $firstName,
		'lastName' => $lastName,
		'EmailAddress' => $email,
		'password' => md5($password),
		'RoleType' => 'Provider',
		'contact' => $phone,
		'dateCreated' => date('Y-m-d H:i:s'),
	);
	$user = $db->insert ('users', $data1);

	$data = Array (
		'userId' => $user,
		'presciberStatus' => 'New Applicant',
		'fullName' => $firstName.' '.$lastName.','.$degree,
		'firstName' => $firstName,
		'lastName' => $lastName,
		'degree' => $degree,
		'email' => $email,
		'phone' => $phone,
		'recruiter' => $recruiter,
		'dateCreated' => date('Y-m-d H:i:s'),
	);

	
    //echo '<pre>'; print_r($data); die;
	$id = $db->insert ('prescribers', $data);
	
	if ($id && $user) { 
		
		$to = $email;
		
		require 'PHPMailer/PHPMailer.php';
		
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
                                To continue and complete your onboarding process on your own, you may follow and finish the steps shared with you in your provider welcome packet.  You can also access your provider packet by <b><a href="https://drive.google.com/drive/u/1/folders/1Sk3j7SqBiPq6_OaNgr5D7M7qlFaR3mta" target="_new">clicking here.</a></b>
                                <br><br>
                                If you prefer some assistance, you may also schedule a time/date that\'s convenient for you, where one of our dedicated account managers will meet with you to help guide you through completing your registration and activation process.  Once you\'re fully activated, you will immediately begin to match with patients, increasing your billables and already on your way to a profitable career forward within the telehealth spectrum.
                                <br><br>
                                To schedule a meeting with your dedicated account manager, start by <b>   
                                <a href="https://calendly.com/roszena/onboarding" target="_new">clicking here.</a></b>
                                <br>
                                <br>
                                If you need personal assistance or support you may call: <b>1-888-407-4699 Monday through Friday 9am - 5:30pm EST.</b>
                                <br>
                                <br>
                                <b>All</b> calls are monitored for quality assurance and training purposes.  If support is busy/unable to answer, please leave a detailed voice message as to why you\'re calling and a dedicated account manager will return your call within 24-48 hours or less.  
                                <br>
                                <br>
                                <b>However, it is highly recommended and strongly suggested with great preference that <strong>ALL</strong> communication should take place through your account while logged inside the portal using our secure message center.   Your secure message center is the fastest turnaround, is available and receives replies 24-7 but, the option to call our 888# is always available M-F 9am - 5:30pm EST.</b>
                                <br>
                                <br>
                                <b>****REMINDER:</b>  All requests for onboarding meetings with a dedicated account manager must be scheduled to the calendar by <b><a href="https://calendly.com/roszena/onboarding" target="_new">clicking here</a></b> and can not be called in to our support # as in what we consider a "walk-in" or "on the spot" onboarding.  If its not already scheduled to the calendar it can not be done any other way.
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
                    	</div>
                    </div>
                </body>
            </html>';

		$subject = 'Email from TelevisitMD Locum Tenens';
		
		$sendMail = new SendEmail();
        $sendMail->send($to, $supportEmail, $subject, $bodyContent);
		
		/////////////////// login/////////////////////////////
        // register session variables
        $_SESSION['UserData']['Email'] = $email;
        $_SESSION['UserData']['URole']='Provider';
        $_SESSION['UserData']['Uname']=$firstName.' '.$lastName;
        $_SESSION['UserData']['id']=$user;
        $_SESSION['UserData']['Phone']=$phone;
        $_SESSION['UserData']['loggedin_time']=time();
        $update_log = array(
                'last_login' => date('Y-m-d H:i:s')
        );
        $db->where('id',$user);
        $db->update('users',$update_log);
        
        $ipwhere = $_SERVER['REMOTE_ADDR'].' - '.$_SERVER['SCRIPT_NAME']; //.' - - '.$_SERVER['HTTP_X_FORWARDED_FOR'];
        
        $sdata = Array (
          'uem' => $email,
          'phone' => $phone,
          'date' => date('Y-m-d H:i:s'),
		  'ref'=> $ipwhere,
		  'userId' => $user,
		  'userIP' => getUserIP(),
		  'access_device' => $access_device,
          'access_browser' => $access_browser,
		);
    	$db->insert ('logged', $sdata);
        
    	header("location:crm/prescriber/profile.php");
   } else{ ?> 
				
<script type="text/javascript">
$(document).ready(function(){
$.notify({
	title: '<strong>Error</strong>',
	message: '<?php echo "Sign Up Failed.. Please Try Again! "; ?>'
},{
	type: 'danger',
	delay: 2000,
	offset: 100,
	url_target: "_self",
	allow_dismiss: true,
	placement: {
		from: "top",
		align: "center"
	},
	animate: {
		enter: 'animated bounceInDown',
		exit: 'animated bounceOutUp'
	}
});
});
</script>
<?php  } }  ?>
<body class="sign_Uppage">
    	<div class="col-lg-12 col-md-12 col-sm-12 panel panel-signup1 white_sctnNone">
		<div class="container">
			<h1 class="h1" style="color: #0382cc !important;">Sign up as Healthcare Provider</h1>
			<div class="col-md-12">
				<div class="panel-body1 cntr_div pt_shdow_cntr_signup">
					<form id="patientsignup" action="" method="POST" name="patientsignup" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Degree</label>
									<select class="form-control" name="degree" id="degree" required="" tabindex="1">
										<option value="" selected=""></option>
										<option value="M.D.">M.D.</option>
										<option value="D.O.">D.O.</option>
										<option value="NP">NP</option>
										<option value="PA-C">PA-C</option>
										<option value="ARNP">ARNP</option>
										<option value="DPM">DPM</option>
									</select>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label class="control-label color_gry_lyt">First Name</label>
									<input type="text" class="form-control" name="firstName" id="firstName" tabindex="2" value="" required="" pattern="^[^<>]*$" maxlength="150">
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Last Name</label>
									<input type="text" class="form-control" name="lastName" id="lastName" tabindex="3" value="" required="" pattern="^[^<>]*$" maxlength="150">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Email</label>
									<input type="email" class="form-control" name="emailid" id="email" tabindex="4" value="" required="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Phone Number</label>
									<input type="tel" class="form-control phn" name="primaryPhone" id="primaryPhone" tabindex="5" value="" required="" pattern="^[^<>]*$" maxlength="20">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Password</label>
									<input type="password" class="form-control" name="password" id="password" tabindex="6" value="" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Confirm Password</label>
									<input type="password" class="form-control" name="cpassword" id="cpassword" tabindex="7" value="" required="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Recruiter POC</label>
									<select name="recruiter" id="recruiter" class="form-control multiSelect" tabindex="8" required>
                                        <option value="">Choose...</option>
                    					<?php foreach($recruiters as $recruiter){ ?>
                    						<option value="<?php echo $recruiter['id'];?>"><?php echo $recruiter['Name'];?></option>
                    					<?php } ?>
                    					<option value="None">None</option>
                    				</select> 
								</div>
							</div>
						</div>
                        <div class="row">
							<div class="col-sm-12">
        						<div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div id="recaptcha_element" data-callback="recaptchaCallback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<input type="submit" class="btn btn-block btn-success mt10" tabindex="9" value="CREATE ACCOUNT" name="Submit">
					</form>
				</div>
			</div>
		</div>
	</div>
    
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script type="text/javascript">
        $('#patientsignup').bootstrapValidator({
            fields: {
				company: {
                message: 'The company is not valid',
                validators: {
                    remote: {
                        url: 'validate.php',
						type: 'POST',
						delay: 1000,
                        data: { type: 'company'},
						function(validator) {
                            return {
                                email: validator.getFieldElements('email').val()
                            };
                        },
                        message: 'This company already exists.'
                    }
                }
            },
	            emailid: {
                validators: {
                    remote: {
                        url: 'validate.php',
						type: 'POST',
						delay: 1000,
                        data: { type: 'email' },
						function(validator) {
                            return {
                                emailid: validator.getFieldElements('username').val()
                            };
                        },
                        message: 'This email already exists.'
                    },
					regexp: {
				    regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
				    message: 'The value is not a valid email address'
					}	
                }
            },
	            cpassword: {
                validators: {
                    identical: {
                        field: 'password',
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
            primaryPhone: {
				validators: {
					remote: {
						url: 'validate.php',
						type: 'POST',
						delay: 1000,
						data: { type: 'primaryPhone' },
						message: 'This Phone Number already exists.'
					}
				}
			},
        }
    });

    var onloadCallback = function() {
        grecaptcha.render('recaptcha_element', {
          'sitekey' : '<?=$recaptchaSiteKey?>'
        });
    };
    
    function recaptchaCallback() {
        // $('#psubmit').removeAttr('disabled');
    }
</script>
<?php include 'footer.php';?>
<style>
    .select2-selection {
        height: 35px !important;
    }
</style>
</body>