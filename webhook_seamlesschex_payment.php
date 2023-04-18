<?php

include 'config.php';

if(isset($_POST)) {
    $webhook_data = file_get_contents('php://input');
    
    parse_str($webhook_data, $hook_arr);
    
    $event = isset($hook_arr['event']) ? $hook_arr['event'] : null;
    $check_id = isset($hook_arr['data']['check_id']) ? $hook_arr['data']['check_id'] : null;
    $status = isset($hook_arr['data']['status']) ? $hook_arr['data']['status'] : null;
    if(strtolower($status) == 'deleted') {
        $check_id = isset($hook_arr['data']['id']) ? $hook_arr['data']['id'] : null;
    }
    $pass_bv = isset($hook_arr['data']['basic_verification']['pass_bv']) ? $hook_arr['data']['basic_verification']['pass_bv'] : null;
    $amount = isset($hook_arr['data']['amount']) ? $hook_arr['data']['amount'] : null;    
    $email = isset($hook_arr['data']['email']) ? $hook_arr['data']['email'] : null;
    // check if it's recurring or pay fees
    $recurring = isset($hook_arr['data']['recurring']) ? $hook_arr['data']['recurring'] : null;
    
    // get user based on the email address
    if($email) {
        $db->where('EmailAddress', $email);
        $user = $db->getOne('users');
        // get user role
        $userRole = $user['RoleType'];
        // get user id
        $userId = $user['id'];
    }
    
    if($recurring == '1') {
        $data = array(
            'event' => $event,
            'check_id' => $check_id,
            'status' => $status,
            'email' => $email,
            'webhook' => $webhook_data, 
            'addedAt' => date('Y-m-d H:i:s')
        );
        $db->insert('seamlesschex_webhook', $data);
        
        // get user id
        $db->where('check_id', $check_id);
        $subscription_data = $db->getOne('seamlesscheck_thankyou');
        $userId = (isset($subscription_data['userId']) && $subscription_data['userId']) ? $subscription_data['userId'] : $userId;
        $userRole = (isset($subscription_data['userRole']) && $subscription_data['userRole']) ? $subscription_data['userRole'] : $userRole;
        if(strtolower($status) == 'in_review' || strtolower($status) == 'failed' || strtolower($status) == 'void' || strtolower($status) == 'deleted') {
            $data = array(
                'status' => 'Subscription '.$status
            );
            $db->where('check_id', $check_id);
            $db->update('seamlesscheck_thankyou', $data);
            
            // suspend this provider
            $approveData = array(
                'presciberStatus' => $g_provider_status_subscription_failed
            );
            $db->where('userId', $userId);
            $db->update('prescribers', $approveData);
            
            // insert change status log history
            $log_data = array(
                'status' => $g_provider_status_subscription_failed,
                'suspended_reason' => 'Subscription '.$status,
                'changed_date' => date('Y-m-d H:i:s'),
                'userId' => $userId,
                'roleType' => 'Provider',
                'changedBy' => $userId
            );
            $db->insert('statusChangeLogs', $log_data);
            
        } else if(strtolower($status) == 'in_process' && ($event == 'check.created' || $event == 'check.changed')) {
            // update thank you (subscription) db
            if($event == 'check.created') {
                $data = array(
                    'addedAt' => date('Y-m-d H:i:s')
                );
            } else if($event == 'check.changed') {
                $data = array(
                    'updatedAt' => date('Y-m-d H:i:s')
                );
            }
            $data['status'] = 'Subscription Activated';
            if($userId && $userRole) {
                $data['userId'] = $userId;
                $data['userRole'] = $userRole;
            }
            $db->where('check_id', $check_id);
            $db->update('seamlesscheck_thankyou', $data);
            
            // check if the provider status
            $db->where('userId', $userId);
            $prescriber = $db->getOne('prescribers');
            $approvalStatus = $prescriber['presciberStatus'];
            
            if($approvalStatus != $g_provider_status_active) {
                
                // approve this provider
                $approveData = array(
                    'presciberStatus' => $g_provider_status_active,
                    'approvedAt' => date('Y-m-d H:i:s')
                );
                $db->where('userId', $userId);
                $update_id = $db->update('prescribers', $approveData);    
                       
                if($update_id) {
                    // insert change status log history
                    $log_data = array(
                        'status' => $g_provider_status_active,
                        'suspended_reason' => 'Subscription Activated',
                        'changed_date' => date('Y-m-d H:i:s'),
                        'userId' => $userId,
                        'roleType' => 'Provider',
                        'changedBy' => $userId
                    );
                    $db->insert('statusChangeLogs', $log_data);
                }
            }
        }
    } else if($recurring == '0') {
        if($amount == 49.99) {
            $data = array(
                'event' => $event,
                'check_id' => $check_id,
                'status' => $status,
                'email' => $email,
                'webhook' => $webhook_data, 
                'addedAt' => date('Y-m-d H:i:s')
            );
            $db->insert('seamlesschex_webhook', $data);
            exit;
        }
        
        // pay fees
        $data = array(
            'event' => $event,
            'check_id' => $check_id,
            'status' => $status,
            'email' => $email,
            'webhook' => $webhook_data, 
            'usd_amount' => $amount,
            'addedAt' => date('Y-m-d H:i:s')
        );
        $db->insert('seamlesschex_webhook_fees', $data);
        
        if(strtolower($status) == 'in_review' || strtolower($status) == 'failed' || strtolower($status) == 'void' || strtolower($status) == 'deleted') {
            $data = array(
                'status' => 'Payment '.$status
            );
            $db->where('check_id', $check_id);
            $db->update('seamlesscheck_thankyou_fee', $data);
            exit; // can't 
        }
        if(strtolower($status) == 'in_process' && ($event == 'check.created' || $event == 'check.changed')) {
            // get user id
            $db->where('check_id', $check_id);
            $subscription_data = $db->getOne('seamlesscheck_thankyou_fee');
            $userId = (isset($subscription_data['userId']) && $subscription_data['userId']) ? $subscription_data['userId'] : $userId;
            $userRole = (isset($subscription_data['userRole']) && $subscription_data['userRole']) ? $subscription_data['userRole'] : $userRole;
            // update thank you (subscription) db
            if($event == 'check.created') {
                $data = array(
                    'addedAt' => date('Y-m-d H:i:s')
                );
            } else if($event == 'check.changed') {
                $data = array(
                    'updatedAt' => date('Y-m-d H:i:s')
                );
            }
            $data['status'] = 'Paid';
            if($userId && $userRole) {
                $data['userId'] = $userId;
                $data['userRole'] = $userRole;
            }
            $data['usd_amount'] = $amount;
            $db->where('check_id', $check_id);
            $db->update('seamlesscheck_thankyou_fee', $data);
            
            //// get invoice data
            if($userId) {
                $sql = "SELECT * FROM invoice_order WHERE order_amount_paid - order_amount_due < 0 and order_receiver_userid = $userId";
                $invoice_data = $db->query($sql);
                foreach($invoice_data as $invoice) {
                    // if($invoice['order_amount_paid'] + $amount <= $invoice['order_amount_due']) {
                        $i_data = array(
                            'order_amount_paid' => $invoice['order_amount_paid'] + $amount,
                            'paid_date' => date('Y-m-d H:i:s'),
                            'paid_history' => $invoice['paid_history'].'\r\n'.'The provider paid $'.$amount.' on '.date('Y-m-d H:i:s'),
                        );
                        // if fully paid
                        if($invoice['order_amount_paid'] + $amount >= $invoice['order_amount_due']) {
                            $i_data['fully_paid'] = 1;
                        }
                        $db->where('order_id', $invoice['order_id']);
                        $db->update('invoice_order', $i_data);
                        break; // update just 1 row and break
                    // }
                }
            }
        }
    }
}

?>