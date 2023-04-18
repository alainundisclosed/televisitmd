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
		
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$roleType = $_POST['RoleType'];
	$email = $_POST['emailid'];
	$phone = $_POST['primaryPhone'] ? $_POST['primaryPhone'] : null;
	
	$password = $_POST['password'];

	$data1 = Array(
		'Name' => $firstName.' '.$lastName,
		'firstName' => $firstName,
		'lastName' => $lastName,
		'EmailAddress' => $email,
		'password' => md5($password),
		'RoleType' => $roleType,
		'contact' => $phone,
		'dateCreated' => date('Y-m-d H:i:s'),	
	);
	$user = $db->insert ('users', $data1);

	$data = Array (
		'userId' => $user,
		'approvalStatus' => 'New Applicant',
		'fullName' => $firstName.' '.$lastName,
		'RoleType' => $roleType,
		'email' => $email,
		'phone' => $phone,
		'isMaster' => 1,
		'createdAt' => date('Y-m-d H:i:s'),
	);

	
	$id = $db->insert ('suppliers', $data);
	
	if ($id && $user) { 
		
		$to = $email;
		
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
        $mail->addAddress($to);   // Add a recipient
        $mail->addBCC($mailaddBCC);

		$mail->isHTML(true);

		$bodyContent = '<html><body  style="background-color:#36c1c8; font-family: "Roboto", sans-serif;"><div class="main_center" style="width:100%; max-width:480px; margin:0px auto;">
        	    <div class="main_cnntn" style="border-radius:5px;margin-top: 100px;border-bottom: 6px solid #067075;background-color:#fff;padding: 50px 30px;text-align:center;-webkit-box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);-moz-box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);box-shadow: 0px 0px 35px -2px rgba(0,0,0,0.57);"><i class="far fa-check-circle" style="color: green;font-size: 90px;"></i>
        		        <h1 style="color:#2d2d2d;font-size: 44px;">Email Confirmation</h1><p style="color:#505050; line-height: 23px;">Hello, you are almost ready to start with TelevistMD (Role: Supplier), simply click the big yellow button below to verify you email address.</p>
        				<a href="'.get_base_url().'/login.php" style="background-color:#fe9800;color:#fff;padding:  10px 17px;display:  inline-block;border: none;margin: 10px 0px 0px 0px;">Verify email address</a>
        	   </div>
        	</div></body>
        </html>';

		$mail->Subject = 'Email from TelevistMD';
		$mail->Body    = $bodyContent;
		$mail->send();
		
		/////////////////// login/////////////////////////////
        // register session variables
        $_SESSION['UserData']['Email'] = $email;
        $_SESSION['UserData']['URole']=$roleType;
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
    	
    	header("location:crm/supplier/profile.php");
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
			<h1 class="h1" style="color: #0382cc !important;">Supplier Sign Up</h1>
			<div class="col-md-12">
				<div class="panel-body1 cntr_div pt_shdow_cntr_signup">
					<form id="suppliersignup" action="" method="POST" name="suppliersignup" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Role Type</label>
									<select class="form-control" name="RoleType" id="RoleType" required="" tabindex="1">
										<option value="" selected=""></option>
										<option value="DME">DME</option>
										<option value="Pharmacy">Pharmacy</option>
										<option value="LAB">LAB</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
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
						<input type="submit" class="btn btn-block btn-success mt10" tabindex="8" value="CREATE ACCOUNT" name="Submit">
					</form>
				</div>
			</div>
		</div>
	</div>
    
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script type="text/javascript">
        $('#suppliersignup').bootstrapValidator({
            fields: {
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
</body>