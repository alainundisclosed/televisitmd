<?php
date_default_timezone_set('America/New_York');

include 'header.php';

////////////////////////////////////////////////////
require 'twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;
$twilio = new Client($twilioAccountSid, $twilioAuthtoken);
////////////////////////////////////////////////////        

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

if(isset($_POST['psubmit'])){
    
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
    $password=$_POST['password'];
    
    $logintype = $_POST['logintype'];
    if($logintype == 1) {
        $email=$_POST['email'];
        $db->where ('EmailAddress', $email);
    } else if($logintype == 2) {
        $phone=$_POST['phonenumber'];
        $db->where ('contact', $phone);
    }
    $db->where ('Password', md5($password));
    $db->where('isDelete', 'no');
    
    $result = $db->getOne('users');
    
    if ($db->count == 0) { ?>
        <script type="text/javascript">
            $.notify({
                icon: 'glyphicon glyphicon-warning-sign',
                message: 'Login Authentication Failed!'
            },{
                type: "danger",
                allow_dismiss: true,
                placement: {
                    from: "top",
                    align: "center"
                },
                offset: 50,
                spacing: 10,
                delay: 8000,
                timer: 1000,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
        </script>
    <?php } else{
        // regenerate session id
        session_regenerate_id(true);
        
        $_SESSION['UserData']['Email'] = $result['EmailAddress'] ? $result['EmailAddress'] : null;
        $_SESSION['UserData']['URole']=$result['RoleType'];
        $_SESSION['UserData']['Uname']=$result['Name'];
        $_SESSION['UserData']['id']=$result['id'];
        $_SESSION['UserData']['Phone']=$result['contact'] ? $result['contact'] : null;
        $_SESSION['UserData']['session_duration']= 604800;//session expire duration 1 week
        // $_SESSION['UserData']['title']=$result1['title'];
        // $_SESSION['UserData']['topbarcolor']=$result1['topbarcolor'];
        // $_SESSION['UserData']['logoPath']=$result1['logoPath'];
        // $_SESSION['UserData']['logoPermission']=$result1['logoPermission'];
        $_SESSION['UserData']['loggedin_time']=time();
        $update_log = array(
                'last_login' => date('Y-m-d H:i:s'),
                'access_device' => $access_device,
                'access_browser' => $access_browser,
        );
        $db->where('id',$result['id']);
        $db->update('users',$update_log);
        
        $ipwhere = $_SERVER['REMOTE_ADDR'].' - '.$_SERVER['SCRIPT_NAME']; //.' - - '.$_SERVER['HTTP_X_FORWARDED_FOR'];
        
        $sdata = Array (
          'uem' => $result['EmailAddress'] ? $result['EmailAddress'] : null,
          'phone' => $result['contact'] ? $result['contact'] : null,
          'date' => date('Y-m-d H:i:s'),
		  'ref'=> $ipwhere,
		  'userId' => $result['id'],
		  //'companyId' => $result['companyId'],
		  'userIP' => getUserIP(),
		  'access_device' => $access_device,
          'access_browser' => $access_browser,
		);
    	$db->insert ('logged', $sdata);
    	
        // if($result['EmailAddress'] == 'dr.steve@pc-wizard.net' || 'chris@telescrips.com'){
        // 	$_SESSION['UserData']['SAU'] = 'yes';
        // }
      
        
        //print_r($sdata);
        
    }
}
if(isset($_SESSION['UserData'])){
    if($_SESSION['UserData']['URole'] == 'Super Admin'){
        // header("location:crm/admin/");
        header("location:crm/superadmin/prescribers.php");
    }
    elseif($_SESSION['UserData']['URole'] == 'Admin'){
        // header("location:crm/admin/");
        header("location:crm/admin/prescribers.php");
    }
    elseif($_SESSION['UserData']['URole'] == 'Provider'){
        header("location:crm/prescriber/profile.php");
    }
    elseif($_SESSION['UserData']['URole'] == 'Staff'){
        header("location:crm/staff/");
    }
    elseif($_SESSION['UserData']['URole'] == 'Patient'){
        header("location:crm/patient/cases.php");
	}
	elseif($_SESSION['UserData']['URole'] == 'DME' || $_SESSION['UserData']['URole'] == 'LAB' || $_SESSION['UserData']['URole'] == 'Pharmacy'){
        header("location:crm/supplier/cases.php");
	}
	elseif($_SESSION['UserData']['URole'] == 'Biller'){
        header("location:crm/biller/cases.php");
	}
	elseif($_SESSION['UserData']['URole'] == 'Recruiter'){
        header("location:crm/recruiter");
	}
	elseif($_SESSION['UserData']['URole'] == 'Recruiter Manager'){
        header("location:crm/recruitermanager");
	}
	elseif($_SESSION['UserData']['URole'] == 'Marketer'){
        header("location:crm/marketer/cases.php");
	}
	elseif($_SESSION['UserData']['URole'] == 'Agent'){
        header("location:crm/agent/cases.php");
	}
	elseif($_SESSION['UserData']['URole'] == 'PCC'){
        header("location:crm/pcc/cases.php");
	}
	
}

?>


<body class="">

<section>
    <div class="col-lg-12 col-md-12 col-sm-12 panel panel-signin-mtm white_sctnNone " style="    background-image: url('<?php echo get_base_url(); ?>/assets/images/dialmymd/slider2.jpg') !important;
    background-repeat: no-repeat;
    background-size: cover !important;
    background-position: left center;
    padding: 150px 0px;" >
        <div class="panel-body1 l_white_sctn" style="max-width:600px">
            <!--<div class="col-xs-12 text-center">
                <div class="logo text-center" style="margin-top:-20px;padding-bottom:20px;">
                    <img src="/assets/images/logo-alternative.png" style="max-width:251px; min-width:225px;">
                </div>
            </div>-->
            <div class="row mtm-signin-existing">
                <div class="col-md-12 col-sm-12 login_content">
                    <h4 class="text-center mb5">Already a Member?</h4>
                    <p class="text-center">Sign in to your Account</p>
    
                    <form action="" method="POST">
                        <!--<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">-->
                        <!--<input type="hidden" name="action" value="validate_captcha">-->
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input id="349" type="radio" value="1" name="logintype" class="logintype" checked>
                                <label for="349" style="font-weight:500; cursor:pointer">Login with Email</label>
                                &nbsp;
                                <input id="350" type="radio" value="2" name="logintype" class="logintype">
                                <label for="350" style="font-weight:500; cursor:pointer">Login with Phone Number</label>
                            </div>
                        </div>
                        <div class="col-sm-12" id="email_row">
                            <div class="form-group">
                                <label class="control-label color_gry_lyt">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12" id="phonenum_row" style="display:none;">
                            <div class="form-group">
                                <label class="control-label color_gry_lyt">Phone Number</label>
                                <input type="tel" name="phonenumber" id="phonenumber" class="form-control" placeholder="Phone Number" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label color_gry_lyt">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div id="recaptcha_element" data-callback="recaptchaCallback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" tabindex="18" value="Sign In" name="psubmit" id="psubmit" disabled style="width:100%;margin:15px 0px;">
                                    <!-- <button type="submit" class="btn btn-success">Sign In <i class="fa fa-angle-right ml5"></i></button> -->
                                    <div class="pull-left mt10 log_infrgt_line">
                                        <a href="#" data-target="#frgt_pswrd" data-toggle="modal">Forgot Password?</a>
    <!--                                    <p>Not Registered?<a href="signup.php"> Create an Account</a></p>-->
                                    </div>
                                </div>
    
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="frgt_pswrd" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width:500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h2 class="text-center">Reset Password?</h2>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                
                                <p class="narrator_email">Enter your Registered Email and click continue to Reset your Account Password.</p>
                                <p class="narrator_sms" style="display:none">Enter your Registered Phone Number and click continue to Reset your Account Password.</p>
                                <form method="post" name="frgtpswrd" id="frgtpswrd">
                                    <div class="panel-body">
                                        <fieldset>
                                            <div class="form-group">
                                                <input id="351" type="radio" value="1" name="resettype" class="resettype" checked>
                                                <label for="351" style="font-weight:500; cursor:pointer">Email</label>
                                                &nbsp;
                                                <input id="352" type="radio" value="2" name="resettype" class="resettype">
                                                <label for="352" style="font-weight:500; cursor:pointer">SMS</label>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control input-sm" placeholder="E-mail Address" name="email" type="email" id="reset_email" required>
                                                <input class="form-control input-sm" placeholder="Phone Number" name="sms" type="tel" id="reset_sms" style="display:none">
                                            </div>
                                            <input class="btn btn-sm btn-primary btn-block" value="Continue" type="submit" name="sendMail">
                                        </fieldset>
                                    </div>
                                </form>
                                <!--<p>Please check your mail to Reset Password.</p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
</body>
<?php
if(isset($_POST['sendMail'])){
    $resettype = $_POST['resettype'];
    if($resettype == 1) {
        $email=$_POST['email'];
        $db->where ('EmailAddress', $email);
    } else if($resettype == 2) {
        $sms=$_POST['sms'];
        $db->where ('contact', $sms);
    }
    
    $users = $db->getOne('users');

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
    
//    $mail->addAddress($to);   // Add a recipient
    // $mail->addAddress(''.$to.'');    
    //$mail->addCC('cc@example.com');
    // $mail->addBCC('chris@televisitmd.com');

    $mail->isHTML(true);
    
    
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 16; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
  }
    $newpwd = implode($pass); //turn the array into a string
    
        $data = array(
        'Password' => md5($newpwd),
        // 'updatedAt' => $db->now()
        'updatedAt' => date('Y-m-d H:i:s')
    );
//loginnew.php
    if($resettype == 1) {
        $db->where('EmailAddress', $to);
    } else if($resettype == 2) {
        $db->where('contact', $sms);
    }
    if($db->update('users', $data)){
        if($resettype == 1) {
            echo('Successfully changed password for ' . $to);
        } else if($resettype == 2) {
            echo('Successfully changed password for ' . $sms);
        }
    }
//'.get_base_url().'
    $bodyContent = '<html><body  style="background-color:#36c1c8; font-family: "Roboto", sans-serif;"><div class="main_center" style="width:100%; max-width:480px; margin:0px auto;">
	    <div class="main_cnntn" style="border-radius:5px;margin: 50px 0px 100px;border-bottom:6px solid #436CAC;background-color:#fff;padding:50px 30px;border-top: 1px solid #436CAC;border-left: 1px solid #436CAC;border-right: 1px solid #436CAC;text-align:center;-webkit-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.51);-moz-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.51);box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.51);">
	    	
	    	   <div style="margin-bottom: 15px; display: inline-block;">
	    	    <img src="https://tvmdlt.com/assets/images/dialmymd/dial-my-md-logo.png">
		        <h1 style="color:#2d2d2d;font-size:30px;">Your Password has been Reset </h1><p style="color:#505050; line-height: 23px;">
		        You may log in with your new combination <br>Email : '.$to.'<br> Password : '.$newpwd.'</p>
				<a href="'.$server_link.'/login.php" style="background-color:#1ea514;color:#fff;padding:  10px 17px;display:  inline-block;border: none;margin: 10px 0px 0px 0px;">Login</a>
	   </div>
	</div></body>
</html>';

    $mail->Subject = 'Email from TelevisitMD Locum Tenens';
    $mail->Body    = $bodyContent;
    
    if($resettype == 1) {
        if(!$mail->send()) {
            ?>
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                Email could not be sent.
            </div>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $.notify({
                        title: '<strong>Success</strong>',
                        message: 'Email has been sent Successfully. Please check your Inbox.'
                    },{
                        type: 'success',
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
            <?php
        }
    } else if($resettype == 2) {
        $reset_pwd_msg = 'Your Password has been Reset! You may log in with your new combination ( Number: '.$sms.', Password: '.$newpwd.' ) by clicking the following link '.$server_link.'/login.php';
        $reset_pwd_phone_number = $sms;
        
        if($reset_pwd_phone_number) {
            $message = $twilio->messages
                              ->create($reset_pwd_phone_number, // to
                                      ["body" => $reset_pwd_msg, "from" => $twilio_from_registered_number]
                              );
            
            print($message->sid);
        }
        
        ?> 
        <script>
            $(document).ready(function(){
                $.notify({
                    title: '<strong>Success</strong>',
                    message: 'SMS has been sent Successfully. Please check your phone.'
                },{
                    type: 'success',
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
        <?php
    }
}

if(isset($_GET['msg']) && $_GET['msg'] == 'success2'){
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $.notify({
                title: '<strong>Success</strong>',
                message: 'Password Changed Successfully. Please login with your new credentials and continue with our services.'
            },{
                type: 'success',
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
<?php } ?>
<script type="text/javascript">
    $('#frgtpswrd').bootstrapValidator({
        fields: {
            email: {
                validators: {
                    remote: {
                        url: 'validate.php',
                        type: 'POST',
                        delay: 1000,
                        data: { type: 'frgtpswrd' },
                        function(validator) {
                            return {
                                email: validator.getFieldElements('username').val()
                            };
                        },
                        message: 'This is not registered Email.'
                    },
                    regexp: {
                        regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                        message: 'The value is not a valid email address'
                    }
                }
            },
            sms: {
                validators: {
                    remote: {
                        url: 'validate.php',
                        type: 'POST',
                        delay: 1000,
                        data: { type: 'frgtpswrd_sms' },
                        message: 'This is not registered Phone Number.'
                    }
                }
            },
        }
    });
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?render=</?=$recaptchaSiteKey?>"></script>-->
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    var onloadCallback = function() {
        grecaptcha.render('recaptcha_element', {
          'sitekey' : '<?=$recaptchaSiteKey?>'
        });
    };
    
    function recaptchaCallback() {
        $('#psubmit').removeAttr('disabled');
    }
    
    $('.logintype').click(function() {
        var login_type = $(this).val();
        if(login_type == '1') {
            $('#email_row').show();
            $('#email').attr('required', true);
            
            $('#phonenum_row').hide();
            $('#phonenumber').removeAttr('required');
        } else if(login_type == '2') {
            $('#phonenum_row').show();
            $('#phonenumber').attr('required', true);
            
            $('#email_row').hide();
            $('#email').val('');
            $('#email').removeAttr('required');
        }
    });
    
    $('.resettype').click(function() {
        var resettype = $(this).val();
        
        if(resettype == '1') {
            $('.narrator_email').show();
            $('#reset_email').show();
            $('#reset_email').attr('required', true);
            
            $('.narrator_sms').hide();
            $('#reset_sms').hide();
            $('#reset_sms').removeAttr('required');
        } else if(resettype == '2') {
            $('.narrator_sms').show();
            $('#reset_sms').show();
            $('#reset_sms').attr('required', true);
            
            $('.narrator_email').hide();
            $('#reset_email').hide();
            $('#reset_email').removeAttr('required');
        }
    });
    // grecaptcha.ready(function() {
    // // do request for recaptcha token
    // // response is promise with passed token
    //     grecaptcha.execute('<?=$recaptchaSiteKey?>', {action:'validate_captcha'})
    //               .then(function(token) {
    //         // add token value to form
    //         document.getElementById('g-recaptcha-response').value = token;
    //         $('#psubmit').removeAttr('disabled');
    //     });
    // });
    
</script>