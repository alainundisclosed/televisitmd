<?php include 'config.php';
session_destroy();
session_unset();
header("location:index.php");
exit;
?>
