<?php
include('config.php');

//echo'<pre>';print_r($_POST);
$isExist = false;

switch ($_POST['type']) {
    case 'email':
        $email = $_POST['emailid'];
        // Check the email existence ...
		$db->where('EmailAddress', $email);
		$mail = $db->get('users');
		
		if ($db->count == 0)

        $isExist = true; // or false
        break;
    
    case 'primaryPhone':
        $primaryPhone = $_POST['primaryPhone'];
        // Check the email existence ...
		$db->where('contact', $primaryPhone);
		$mail = $db->get('users');
		
		if ($db->count == 0)

        $isExist = true; // or false
        break;

	case 'company':
        $company = $_POST['company'];
        // Check the companyName existence ...
		$db->where('companyName', $company);
		$mail = $db->get('company');

		if ($db->count == 0)

        $isExist = true; // or false
        break;

    case 'checkpassword':
        $id = $_POST['id'];
        $currentpassword = $_POST['currentpassword'];
        // Check the password existence ...
		$db->where('id', $id);
		$db->where('Password', $currentpassword);
		$password = $db->get('users');
		
		if ($db->count == 0)

        $isExist = false; // or false
		else
		$isExist = true;
		$ipwhere = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		$sdata = Array (
      'uem' => $id,
      'date' => date('Y-m-d h:i:s'),
		  'ref'=> $ipwhere
		  );
		
		$db->insert ('logged', $sdata);
        break;
	
	case 'frgtpswrd':
        $email = $_POST['email'];
        // Check the email existence ...
		$db->where('EmailAddress', $email);
		$mail = $db->get('users');
		
		if ($db->count == 0)

        $isExist = false; // or false
		else
		$isExist = true;
        break;
    
    case 'frgtpswrd_sms':
        $sms = $_POST['sms'];
        // Check the email existence ...
		$db->where('contact', $sms);
		$mail = $db->get('users');
		
		if ($db->count == 0)

        $isExist = false; // or false
		else
		$isExist = true;
        break;
    
    // default:
        
}

// Finally, return a JSON
echo json_encode(array(
    'valid' => $isExist,
));
