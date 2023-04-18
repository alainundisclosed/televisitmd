<?php

include 'config.php';

$userId = $_SESSION['UserData']['id'];
$userRole = $_SESSION['UserData']['URole'];

$error = isset($_GET['error']) ? $_GET['error'] : '';

$data = array(
    'error' => $error,
    'userId' => $userId,
    'userRole' => $userRole,
    'addedAt' => date('Y-m-d H:i:s')
);

$db->insert('seamlesscheck_error', $data);
?>
<div class="container">
    <h2 class="text-center" style="text-align: center;padding-top: 150px;font-family: sans-serif;"><?=$error?></h2>
    <div style="margin-top:30px; text-align:center"><button style="width: 130px;height: 30px;" onclick="javascript:history.back()">Go Back</button></div>
</div>
