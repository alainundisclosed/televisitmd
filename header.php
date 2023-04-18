<?php  include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=$site_title?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--	<link rel="shortcut icon" href="assets/images/fav-icone.png"/>-->
    <meta name="keywords" content="Highest Quality, Medical Services, How We Work, A, B, C, Healthcare Services, We live and breathe our values, Meet The Team, Read what our customers say, Contact Us">

    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/animate.css" rel="stylesheet" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../assets/css/jquery-ui.css">
    <link href="assets/fonts/fontawesome-webfont.woff" rel="woff" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="assets/css/bootstrapValidator.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-notify.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/select2-bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/select2.min.css" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.0/jquery-confirm.min.css">

    <script src="https://maps.google.com/maps/api/js?key=AIzaSyA7O591C-K1fqj8e93M0jUZHZsUS_HEktQ"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!--<script src="assets/js/jquery.ui.addresspicker.js"></script>-->
    <script src="assets/js/main-site.js"></script>
    <script type="text/javascript" src="../assets/js/bootstrapValidator.min.js"></script>
    <!-- <script src="../assets/js/jquery-ui.min.js"></script> -->
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/select2.full.js"></script>
    <script src="assets/js/bootstrap-notify.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.0/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="assets/js/arrow13.js"></script>
    
    <noscript>Not seeing a <a href="https://www.scrolltotop.com/">Scroll to Top Button</a>? Go to our FAQ page for more info.</noscript>

    <script type="text/javascript">
        $( document ).ready(function() {
            $( ".multiSelect" ).select2( {
                theme: "bootstrap",
                closeOnSelect: false,
                containerCssClass: ':all:'
            });

            $( ".datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                format: "mm-dd-yyyy",
                yearRange: '1900:2050',
                //minDate : new Date()
                
            });
        });
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?=$g_tag_id?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', '<?=$g_tag_id?>');
    </script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?=$g_tab_manager_id?>');</script>
    <!-- End Google Tag Manager -->
</head>

<?php 

$our_team_link = 'ourteam.php';
if(isset($_SESSION['UserData']) && $_SESSION['UserData']['URole'] == 'Patient'){
    $our_team_link = './crm/patient/addCase.php';
}
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?=$g_tab_manager_id?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
<div class="col-lg-12 col-md-12 col-sm-12 top_header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 top_header_inner">
                <ul>
                    <li <?php if(!isset($_SESSION['UserData'])){ ?> style="border-right: 1px solid #e6e6e6;" <?php } ?>><a href="tel:+18884074699"><i class="fa fa-phone" aria-hidden="true"></i>Phone: 1-888-407-4699</a></li>
                    <?php if(isset($_SESSION['UserData'])){ ?>

                        <li><a href="crm/"><i class="fa fa-lock" aria-hidden="true"></i><?php echo $_SESSION['UserData']['Uname'];?></a></li>
                        <li><a href="logout.php"><i class="fa fa-lock" aria-hidden="true"></i>Logout</a></li>
                    <?php	}else{?>
                        <li style="float: right"><a href="signup.php"><i class="fa fa-lock" aria-hidden="true"></i>Create Account</a></li>
                        <li style="float: right"><a href="login.php"><i class="fa fa-lock" aria-hidden="true"></i>Login</a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-lg-offset-3 col-md-offset-3  col-sm-offset-3 col-lg-3 col-md-3 col-sm-3 top_header_inner top_header_inner_two ">
                <!-- <ul>
                   <li><a class="fb_top_az_one" href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                   <li><a class="fb_top_az_two" href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                   <li><a class="fb_top_az_three" href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                </ul> -->
            </div>
        </div>

    </div>
</div>
<!----------top_header ends---------------->
<?php $activePage = basename($_SERVER['PHP_SELF'], ".php"); ?>
<div class="col-lg-12 col-md-12 col-sm-12 main_nav">
    <nav class="navbar navbar-default main_nav_on_scnd">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed main_nav_bttn" data-toggle="collapse" data-target="#navbar-collapse-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand for_logo_main main_logo_tvmdlt" href="index.php" style="padding: 0 0 10px 0; "><img src="assets/images/dialmymd/dial-my-md-logo.png" alt=""></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse menu_outer_ul_cntnr" id="navbar-collapse-2">
                <ul class="nav navbar-nav navbar-right menu_ul_cntnr">
                    <li><a class="<?= ($activePage == 'index') ? 'header-menu-active':''; ?>" href="index.php">Home</a></li>
<!--                    <li><a href="aboutus.php">About</a></li>-->
                    <!--<li><a class="<?= ($activePage == 'howitworks') ? 'header-menu-active':''; ?>" href="howitworks.php">How it Works</a></li>-->
                    <!--<li><a class="<?= ($activePage == 'ourteam') ? 'header-menu-active':''; ?>" href="<?=$our_team_link?>">Meet the Team</a></li>-->
                    <li><a class="<?= ($activePage == 'contactus') ? 'header-menu-active':''; ?>" href="contactus.php">Contact us</a></li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav><!-- /.navbar -->
</div><!-- /.container-fluid -->
