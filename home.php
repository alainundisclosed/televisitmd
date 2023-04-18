<?php 
include 'header.php'; 
if(empty($_SESSION['PatientData'])){
	header("location:index.php");
	exit;
}
?>
<div class="aftrlogin_bttncntr">

  <a href="consultToDoctor.php">Consult to Doctor</a>

</div>
<?php //include 'footer.php'; ?>