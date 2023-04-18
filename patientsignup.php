<?php
date_default_timezone_set('America/New_York');
// ini_set('display_errors', 1);
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

if (isset($_POST['psubmit'])) {
    
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
    
	//echo'<pre>';print_r($_POST); die;
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$fullname = $firstName.' '.$lastName;
// 	$dob = date('m-d-Y', strtotime($_POST['dob']));
    $dob = $_POST['dob'];
	
	$gender = $_POST['gender'];
	$primaryPhone = $_POST['primaryPhone'] ? $_POST['primaryPhone'] : null;
	$email = $_POST['emailid'] ? $_POST['emailid'] : null;
	$password = $_POST['password'];

	$usersData = array(
		'firstName' => $firstName,
		'lastName' => $lastName,
		'Name' => $fullname,
		'EmailAddress' => $email,
		'Password' => md5($password),
		'contact' => $primaryPhone,
		'RoleType' => 'Patient',
		'dateCreated' => date('Y-m-d H:i:s'),
	);
	$userId = $db->insert('users', $usersData);
	//echo '<pre>'; print_r($id1);die;

	$ptData = Array (
    	'userId' =>	$userId,
    	'firstName' => $firstName,
    	'lastName' => $lastName,
    	'Name' => $fullname,
    	'dob' => $dob,
    	'gender' => $gender,
    	'primaryPhone' => $primaryPhone,
        'EmailAddress' => $email,
    	'Password' => md5($password),
    	'RoleType' => 'Patient',
    	'createdAt' => date('Y-m-d H:i:s'),
    	'fromSignupPage' => 1,
    	'addedBy' => $userId
	);

	$id = $db->insert('patients', $ptData);

	
	if ($id){   
        
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
						<h1 style="color:#2d2d2d;font-size: 44px;">Email Confirmation</h1><p style="color:#505050; line-height: 23px;">Hello, you are almost ready to start with televisitMD (Role: Patient), simply click the big yellow button below to verify you email address.</p>
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
        $_SESSION['UserData']['URole']='Patient';
        $_SESSION['UserData']['Uname']=$fullname;
        $_SESSION['UserData']['id']=$userId;
        $_SESSION['UserData']['Phone']=$primaryPhone;
        $_SESSION['UserData']['loggedin_time']=time();
        $update_log = array(
                'last_login' => date('Y-m-d H:i:s')
        );
        $db->where('id',$userId);
        $db->update('users',$update_log);
        
        $ipwhere = $_SERVER['REMOTE_ADDR'].' - '.$_SERVER['SCRIPT_NAME']; //.' - - '.$_SERVER['HTTP_X_FORWARDED_FOR'];
        
        $sdata = Array (
          'uem' => $email,
          'phone' => $primaryPhone,
          'date' => date('Y-m-d H:i:s'),
		  'ref'=> $ipwhere,
		  'userId' => $userId,
		  'userIP' => getUserIP(),
		  'access_device' => $access_device,
          'access_browser' => $access_browser,
		);
    	$db->insert ('logged', $sdata);
    	header("location:crm/patient/profile.php");
   } else { ?>
		<script type="text/javascript">
			$(document).ready(function(){
			$.notify({
					title: '<strong>Error</strong>',
					message: 'Signup failed'
				},{
					type: 'warning',
					delay: 2000,
					offset: 100,
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
<?php } } ?>
<body class="sign_Uppage">
	<div class="col-lg-12 col-md-12 col-sm-12 panel panel-signup1 white_sctnNone">
		<div class="container">
			<h1 class="h1">TelevisitMD Patient Registration</h1>
			<div class="row">
				<div class="col-md-9 panel-body1 cntr_div pt_shdow_cntr_signup">
					<div class="step_message">
						<h3>Create Your Patient Account </h3>
					</div>
					<form id="patientsignup" action="" method="POST" name="patientsignup">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">First Name</label>
									<input type="text" class="form-control" name="firstName" id="firstName" tabindex="1" value="" required="" pattern="^[^<>]*$" maxlength="150">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Last Name</label>
									<input type="text" class="form-control" name="lastName" id="lastName" tabindex="2" value="" required="" pattern="^[^<>]*$" maxlength="150">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Date of Birth</label>
									<input type="text" class="form-control datepicker" name="dob" id="dob" tabindex="3" required="" placeholder="mm-dd-yyyy">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Gender</label>
									<div class="rdio rdio-success">
										<input type="radio" name="gender" id="genderM" value="Male" tabindex="4" required="">
										<label for="genderM" class="color_gry_lyt">Male</label>
									</div>
									<div class="rdio rdio-success">
										<input type="radio" name="gender" id="genderF" value="Female" tabindex="5" required="">
										<label for="genderF" class="color_gry_lyt">Female</label>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Phone Number</label>
									<input type="tel" class="form-control phn" name="primaryPhone" id="primaryPhone" tabindex="6" value="" pattern="^[^<>]*$" maxlength="20">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Email <small>( This will also be your username. )</small></label>
									<input type="email" class="form-control" name="emailid" id="emailid" tabindex="8" value="" >
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Password</label>
									<input type="password" class="form-control" name="password" id="password" tabindex="8" value="" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label color_gry_lyt">Confirm Password</label>
									<input type="password" class="form-control" name="cpassword" id="cpassword" tabindex="8" value="" required="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 accept-terms">
								<input id="acceptTerms" type="checkbox" name="acceptTerms" class="terms-checkbox" required>
								<span class="check-text">
									I have read and agree to the <a href="patientterms.php" target="_blank">Patient Terms</a>.
								</span>
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
						<input type="submit" class="btn btn-block btn-success mt10" tabindex="18" value="CREATE ACCOUNT" name="psubmit" id="psubmit" disabled>
						<!-- <button type="button" id="registerBtn" data-loading-text="Creating..." class="btn btn-block btn-success mt10" tabindex="18"><strong>CREATE ACCOUNT</strong></button> -->
					</form>
				</div>
				<div class="col-md-3 common_wrap">	
					<ul class="feature_list patientreg">					
						<li>
							<span class="ico-wrap">
								<img src="https://scripts.continuouscare.io/static/I2VL0Nd9KIYfgEq0aVpnWYMMGhdxhdUvF24h7oQL850.png" alt="" />
							</span>
							<div>
								<span>Participate better</span> in your own healthcare
							</div>
						</li>
						<li>
							<span class="ico-wrap">
								<img src="https://scripts.continuouscare.io/static/2TEV5vJnSyjsDVeAqty2SRJi5EEKqh4xt7KaK4mLFQ1.png" alt="" />
							</span>
							<div>
								<span>Communicate with your healthcare providers</span> wherever you are
							</div>
						</li>
						<li>
							<span class="ico-wrap">
								<img src="https://scripts.continuouscare.io/static/L2KfD0t2VMuk3W6oVV2ALdgOi5cI3A96hOcRUguQjs4.png" alt="" />
							</span>
							<div>
								<span>Access  your health records</span> whenever you need to
							</div>
						</li>
						<li>
							<span class="ico-wrap">
								<img src="https://scripts.continuouscare.io/static/V2EhPEM0ZKYihLx03uuWOjJVdtqrqWwAWfpcZa4tEqr.png" alt="" />
							</span>
							<div>
								<span>Convenient and cost effective</span> healthcare management
							</div>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script type="text/javascript">
	$('#patientsignup').bootstrapValidator({
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
								// emailid: validator.getFieldElements('username').val()
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
			primaryPhone: {
				validators: {
					remote: {
						url: 'validate.php',
						type: 'POST',
						delay: 1000,
						data: { type: 'primaryPhone' },
				// 		function(validator) {
				// 			return {
				// 				primaryPhone: validator.getFieldElements('username').val()
				// 			};
				// 		},
						message: 'This Phone Number already exists.'
					},
				// 	regexp: {
    // 					regexp: /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/,
    // 					message: 'The value is not a valid phone number'
				// 	}	
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
			acceptTerms: {
				validators: {
					notEmpty: {
                        message: 'Terms is required'
                    }
				}
			}
		}
	});

// 	$("#acceptTerms").on('change', function() {
// 		if ($("#acceptTerms").is(":checked"))
// 			$("input[type='submit']").removeAttr('disabled');
// 		else
// 			$("input[type='submit']").attr('disabled', true);
// 	});
    
    $('#patientsignup').submit(function(event) {
        var phone = $('#primaryPhone').val().trim();
        var email = $('#emailid').val().trim();
        if(phone == '' && email == '') {
            $.alert({
                icon: 'fa fa-warning',
                type: 'red',
                title: 'Alert!',
                content: 'You cannot empty both the email and phone number fields.'
            });
            event.preventDefault();
            return false;
        }
        
    });
    
    var onloadCallback = function() {
        grecaptcha.render('recaptcha_element', {
          'sitekey' : '<?=$recaptchaSiteKey?>'
        });
    };
    
    function recaptchaCallback() {
        $('#psubmit').removeAttr('disabled');
    }
</script>
	<?php include 'footer.php';?>
</body>