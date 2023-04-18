<?php 
    include 'header.php';
    
    if(isset($_POST['contact_submit'])) {
    
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
        
        $firstName=$_POST['name'];
        $lastName = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];
        
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer(true);
        $mail->Host = $mailHost;
    	$mail->Username = $mailUsername;  // SMTP username
    	$mail->Password = $mailPassword;			// SMTP password
    	$mail->setFrom($mailsetFrom, $mailsetName);
    	$mail->addReplyTo($email, $firstName);
    	$mail->addAddress($mailaddReplyTo);   // Add a recipient
    	$mail->addBCC($mailaddBCC);
        $mail->isHTML(true);
        $mail->Subject = 'Email from Contact us Form';
        $bodyContent = '<div><b>First Name:</b> '.$firstName.'</div> <div><b>Last Name:</b> '.$lastName.'</div> <div><b>Email: </b>'.$email.'</div> <div><b>Phone:</b> '.$phone.'</div><div><b>Message</b></div><div>'.$message.'</div>';
        $mail->Body    = $bodyContent;
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
                        message: 'Email has been sent Successfully.'
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
    
?>

<body>
   <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12 ">
      <div class="container">
         <div class="col-md-6 contct_us_live">
            <aside class="sidebar">
               <div class="single contact-info">
                  <h3 class="side-title">Contact Form</h3>
                  <form action="" method="POST">
                      <div class="row cntct_us_frst_nam">
                         <div class="col-md-6">
                            <div class="form-group">
                               <label for="form_name">First Name *</label>
                               <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname">
                               <div class="help-block with-errors"></div>
                            </div>
                         </div>
                         <div class="col-md-6">
                            <div class="form-group">
                               <label for="form_lastname">Last Name *</label>
                               <input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
                               <div class="help-block with-errors"></div>
                            </div>
                         </div>
                      </div>
                      <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                               <label for="form_email">Email *</label>
                               <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                               <div class="help-block with-errors"></div>
                            </div>
                         </div>
                         <div class="col-md-12">
                            <div class="form-group">
                               <label for="form_phone">Phone</label>
                               <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Please enter your phone">
                               <div class="help-block with-errors"></div>
                            </div>
                         </div>
                      </div>
                      <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                               <label for="form_message">Message *</label>
                               <textarea id="form_message" name="message" class="form-control" placeholder="Message for me *" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                               <div class="help-block with-errors"></div>
                            </div>
                         </div>
                         <div class="col-md-12">
                             <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div id="recaptcha_element" data-callback="recaptchaCallback"></div>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="col-md-12">
                            <input type="submit" name="contact_submit" id="contact_submit" class="btn btn-success btn-send" disabled="disabled" value="Send message">
                         </div>
                      </div>
                    </form>
               </div>
            </aside>
         </div>
         <div class="col-md-6 contct_us_live">
            <aside class="sidebar">
               <div class="single contact-info">
                  <h3 class="side-title">Contact Information</h3>
                  <ul class="list-unstyled">
                     <!--<li>
                        <div class="icon"><i class="fa fa-map-marker"></i></div>
                        <div class="info"><p>1600 Amphitheatre Parkway St Martin Church</p></div>
                        </li>-->
                     <li>
                        <a href="tel:+18884074699">
                            <div class="icon"><i class="fa fa-phone"></i></div>
                            <div class="info">
                               <p>1-888-407-4699</p>
                            </div>
                        </a>
                     </li>
                     <li>
                        <a href="mailto:support@tvmdlt.com" target="_top">
                            <div class="icon"><i class="fa fa-envelope"></i></div>
                            <div class="info">
                               <p>support@tvmdlt.com</p>
                            </div>
                        </a>
                     </li>
                     <li>
                        <div class="icon"><i class="fa fa-headphones" aria-hidden="true"></i></div>
                        <div class="info">
                           <p style="margin-top: 0px;"><b>Customer Service</b><br/>Do you have a question or need assistance with activation, scheduling a consult or for technical support?</p>
                           <p style="margin-top:10px;">Please fill out the form to the left and click submit.  A representative will respond to your inquiry momentarily and in some cases where we are extremely busy, within 24 hours.  We are continuously working to ensure quality service.  Thank you for visiting to make contact with us today.</p>
                        </div>
                     </li>
                  </ul>
               </div>
            </aside>
         </div>
      </div>
   </div>
</body>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    var onloadCallback = function() {
        grecaptcha.render('recaptcha_element', {
          'sitekey' : '<?=$recaptchaSiteKey?>'
        });
    };
    
    function recaptchaCallback() {
        $('#contact_submit').removeAttr('disabled');
    }
</script>
<?php include 'footer.php';?>