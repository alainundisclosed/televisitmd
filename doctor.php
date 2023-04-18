<?php 
    date_default_timezone_set('America/New_York');
    
    include 'header.php';
    
    $avatar_link = 'crm/prescriber/prescriberAvatars/';
    $did = (int)$db->escape($_GET['did']);
    $db->where('id', $did);
    $prescriber = $db->getOne('prescribers');
    
    $photo = $prescriber['avatarUpload'];
    if(!$photo) {
        $avatar = $prescriber['gender'] == 'Female' ? $avatar_link.'doctor-fm.png' : $avatar_link.'doctor-male.png';
    } else {
        $avatar = $avatar_link.$photo;
    }
    
    // $today = date("Y-m-d");
    // $now = date('Y-m-d H:i:s');
    
    // $available_appointment = 1;
    // if(strtotime($now) - strtotime($prescriber['availableHours']) >= 0) {
    //     $available_appointment = 0;
    // }
    
    // if($available_appointment == 1) {
	   //// echo "<option value='{$prescriber['id']}' data-adate='{$a_date}' data-atime='{$a_time}'>{$prescriber['fullName']}</option>";
	   // if(!isset($_SESSION['UserData'])){
    //         $appointment_btn = '<a href="login.php"><button class="btn btn-success ourteam-btn mb-2px"><i class="fa fa-calendar" aria-hidden="true"></i> '.$txt_dr_available_now.'</button></a>';
    //     } else {
    //         $appointment_btn = '<p>&nbsp;</p>';
    //     }
    // } else {
    //     $appointment_btn = '<a href="login.php"><button class="btn btn-warning ourteam-btn mb-2px">'.$txt_dr_next_available.'</button></a>';
    // }
    
    // next availability
    // $nextAvailabilityFrom = $prescriber['nextAvailabilityFrom'];
    // $provider_nextAvailability = '';
    // if($nextAvailabilityFrom) {
    //     $nextAvailibilityDateStrF = explode(' ', $nextAvailabilityFrom);
    //     $nextAvailibilityDate = date("d/m/Y", strtotime($nextAvailibilityDateStrF[0]));
    //     $nextAvailibilityTimeFrom = $nextAvailibilityDateStrF[1];
    //     $nextAvailibilityTimeFrom = explode(':', $nextAvailibilityTimeFrom);
    //     $nextAvailibilityTimeFromHours = $nextAvailibilityTimeFrom[0];
    //     $nextAvailibilityTimeFromMinutes = $nextAvailibilityTimeFrom[1];
    //     $nextAvailibilityTimeFromAMPM = 'AM';
    //     if($nextAvailibilityTimeFromHours >= 12) {
    //       $nextAvailibilityTimeFromHours -= 12; 
    //       $nextAvailibilityTimeFromHours = str_pad($nextAvailibilityTimeFromHours, 2, '0', STR_PAD_LEFT);
    //       $nextAvailibilityTimeFromAMPM = 'PM';
    //     }
        
    //     $nextAvailabilityTo = $prescriber['nextAvailabilityTo'];
    //     $nextAvailibilityDateStrT = explode(' ', $nextAvailabilityTo);
    //     $nextAvailibilityTimeTo = $nextAvailibilityDateStrT[1];
    //     $nextAvailibilityTimeTo = explode(':', $nextAvailibilityTimeTo);
    //     $nextAvailibilityTimeToHours = $nextAvailibilityTimeTo[0];
    //     $nextAvailibilityTimeToMinutes = $nextAvailibilityTimeTo[1];
    //     $nextAvailibilityTimeToAMPM = 'AM';
    //     if($nextAvailibilityTimeToHours >= 12) {
    //       $nextAvailibilityTimeToHours -= 12; 
    //       $nextAvailibilityTimeToHours = str_pad($nextAvailibilityTimeToHours, 2, '0', STR_PAD_LEFT);
    //       $nextAvailibilityTimeToAMPM = 'PM';
    //     }
        
    //     $provider_nextAvailability = $nextAvailibilityDate.' <b>From:</b> '.$nextAvailibilityTimeFromHours.':'.$nextAvailibilityTimeFromMinutes.' '.$nextAvailibilityTimeFromAMPM.' <b>To:</b> '.$nextAvailibilityTimeToHours.':'.$nextAvailibilityTimeToMinutes.' '.$nextAvailibilityTimeToAMPM;
    // }
?>
<style>
.online-light {
    width: 20px;
    height: 20px;
    position: absolute;
    background: #42a042;
    margin-top: 15px;
    border-radius: 50%;
}
</style>
<div class="container">
	<div class="col-lg-12 col-md-12 col-sm-12 hwit_wrk_benefits">
        <div class="container">
	       <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 doctor_profile_inner">
		      <div style="margin-bottom: 15px;">
		          <img src="<?=$avatar?>" class="ourteam-photo" />
		      </div>
		      <h5 class="font-weight-bold ourteam-doctor-name"><?=$prescriber['fullName']?></h5>
			  <!--<div class="ourteam-action-btns">-->
			      <!--<button class="btn btn-danger ourteam-btn"><i class="fa fa-calendar" aria-hidden="true"></i> Book an appointment</button>-->
			      <!--</?=$appointment_btn?>-->
			  <!--</div>-->
	       </div>
	       <div class="col-lg-8 col-md-7 col-sm-5 col-xs-12">
		      <h4 class="mb-0">About Me</h4>
		      <p class="pb-10">
		          <?=$prescriber['aboutMe']?>
		      </p>
		      <h4 class="mb-0">Achievements</h4>
		      <p class="pb-10">
		          <?=$prescriber['achievements']?>
		      </p>
		      <h4 class="mb-0">Specialty</h4>
		      <p class="pb-10">
		          <?=str_replace(';', ', ', substr(substr($prescriber['specialty'], 1), 0, -1))?>
		      </p>
		      <h4 class="mb-0">Domicile State License</h4>
		      <p class="pb-10">
		          <?=$prescriber['state']?>
		      </p>
		      <h4 class="mb-0">Additional States Licensed</h4>
		      <p>
		          <?=str_replace(';', ', ', substr(substr($prescriber['licensedStates'], 1), 0, -1))?>
		      </p>
		      <!--<h4 class="mb-0">Visit Type</h4>-->
		      <!--<p>-->
		      <!--    </?php-->
		      <!--      $visitType = '';-->
		      <!--      if($prescriber['DMEAllowed'] == 1) {-->
		      <!--          $visitType .= 'DME,';-->
		      <!--      }-->
		      <!--      if($prescriber['PharmAllowed'] == 1) {-->
		      <!--          $visitType .= ' Pharmacy,';-->
		      <!--      }-->
		      <!--      if($prescriber['LABAllowed'] == 1) {-->
		      <!--          $visitType .= ' LAB,';-->
		      <!--      }-->
		      <!--      $visitType = substr($visitType, 0, -1);-->
		      <!--      echo $visitType;-->
		      <!--    ?>-->
		      <!--</p>-->
		      <!--<h4 class="mb-0">Patient Direct Dial #</h4>-->
		      <!--<p>-->
		      <!--    </?=$prescriber['pddphone']?>-->
		      <!--</p>-->
		      <!--</?php if($provider_nextAvailability):?>-->
    		  <!--    <h4 class="mb-0">Next Availability</h4>-->
    		  <!--    <p>-->
    		  <!--        </?=$provider_nextAvailability?>-->
    		  <!--    </p>-->
    		  <!--</?php endif;?>-->
	       </div>
	    </div>
	    <div class="split-bar"></div>
	</div>
<?php include 'footer.php';?>