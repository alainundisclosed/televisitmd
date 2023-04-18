<?php
// define server url
$server_link = 'https://dev.tvmdlt.com';
$www_server_link = 'https://www.dev.tvmdlt.com';
$server_name_without_https = 'dev.tvmdlt.com';

$site_title = 'TelevisitMD Locum Tenens';

$g_tag_id = 'G-TZDH653TLY';
$g_tab_manager_id = 'GTM-KHSNKT7';

// EMAIL CONFIG
$mailHost = 'mail.dialmymd.com';
$mailUsername = 'support@dialmymd.com';  // SMTP username
$mailPassword = 'support1234!@#$';			// SMTP password
$mailSMTPSecure = false;	// Enable TLS encryption, `ssl` also accepted
$mailPort = 587;  
$mailsetFrom = 'support@tvmdlt.com';
$supportEmail = 'support@tvmdlt.com';
$mailaddReplyTo = 'support@tvmdlt.com';
$mailsetName = 'TelevisitMD Locum Tenens';
$mailaddBCC = 'chris@tvmdlt.com';

$glob_provider_office_numbers = ['1-800-204-1227', '800-204-1227', '18002041227', '8002041227', '800 204 1227', '1(800)204-1227', '(800)2041227', '(800) 204 1227', '1.800.204.1227', '800.204.1227'];

$g_provider_status_new = 'New Applicant';
$g_provider_status_pending_pecos = 'Pending PECOS';
$g_provider_status_pecos_approved = 'PECOS Approved';
$g_provider_status_agreement_completed = 'Agreement Completed';
$g_provider_status_pending_cred = 'Pending Corrections/Creds'; // request update by cred (HHC)
$g_provider_status_active = 'Active';
$g_provider_status_subscription_failed = 'Subscription Failed';
$g_provider_status_offboarding_inprocess = 'Offboarding in process';
$g_provider_status_offboarding = 'Offboarding';

$glob_provider_ApprovalStatus = [$g_provider_status_new, $g_provider_status_pending_pecos, $g_provider_status_pecos_approved, $g_provider_status_agreement_completed, $g_provider_status_active, $g_provider_status_subscription_failed, $g_provider_status_offboarding_inprocess, $g_provider_status_offboarding];

$global_notallowed_upload_file = array('php', 'php2', 'php3', 'php4', 'php5', 'html', 'js', 'fla', 'dat', 'exe');

$txt_cred_approve = 'Approve';
$txt_cred_request_resubmit = 'Request Update';

$g_provider_status_pending_approval_cred = 'Pending Approval/Creds'; // upload again or upload the creds(HHC)
$g_provider_status_creds_accepted = 'Creds Accepted';

$saas_user_approved_status = 'Approved/Active';

$credentialing_status_approved = 'APPROVED';
$credentialing_status_pending_approval = 'PENDING APPROVAL';

$paypal_api_url = "https://api-m.sandbox.paypal.com";
$paypal_client_id = "ATzHjVN9uK7laWiNzd7ubhzcN2PYV1uyLsQ48aYkESmKbV6j5gONEpqAndtk0gyzQzwuCi0IytNoyfFd";
$paypal_secret = "EE_68RX6c0EP6D4EuQXoauuNrz58cWOh-Aoj23XMnGYFSUQzBnD5nxZxz6-K8uSiIIfD4Kvs4KdRf1-p";

$saas_api_link = 'https://unidevtest.unimedrx.com/crm/api/TvMDLT';
$hhc_api_link = 'https://dev.harwoodhealthcorp.com/crm/api/TvMDLT';

$billing_guide_msg = 'After you click the subscribe button, you must enter the login email address of the account you are currently logged in to.';

$google_api_key = 'AIzaSyCIGRhFKDd9kCIYP94lo1_hh7fjVJk0fsY';

$v2_site_link = 'https://new.unimedrx.com';
?>