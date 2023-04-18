<?php

include 'config.php';

$providerPlanId = 'P-7WU20292X5332345XMG6NCNI';

if(isset($_POST)) {
        
    $webhook_json_data = file_get_contents('php://input');
    $webhook_arr_data = json_decode($webhook_json_data, true);
    
    // check resource_type if it's subscription or sale
    $resource_type = $webhook_arr_data['resource_type'];
    if($resource_type == 'subscription') {
        
        $subscription_id = $webhook_arr_data['resource']['id'];
        
        // insert subscription raw data
        $subscription_data = array(
            'subscriptionId' => $subscription_id, 
            'event_type' => $webhook_arr_data['event_type'],
            'subscriptions' => $webhook_json_data,
            'addedAt' => date('Y-m-d H:i:s')
        );
        $db->insert('paypal_webhook_subscriptions', $subscription_data);
        
        $plan_id = $webhook_arr_data['resource']['plan_id'];
        $status_update_time = $webhook_arr_data['resource']['status_update_time'];
        $now = date('Y-m-d H:i:s');
        
        if($plan_id == $providerPlanId) {
            // get provider user id
            $db->where('subscriptionID', $subscription_id);
            $providerMonthlyMembershipHistory = $db->getOne('providerMonthlyMembershipHistory');
            $providerUserId = $providerMonthlyMembershipHistory['providerUserId'];
            
            // check if the supplier status
            $db->where('userId', $providerUserId);
            $prescriber = $db->getOne('prescribers');
            $approvalStatus = $prescriber['presciberStatus'];
        }
        
        // if activated
        if($webhook_arr_data['event_type'] == 'BILLING.SUBSCRIPTION.ACTIVATED') {
            $email_address = $webhook_arr_data['resource']['subscriber']['email_address'];
            $currency_code = isset($webhook_arr_data['resource']['billing_info']['last_payment']['amount']['currency_code']) ? $webhook_arr_data['resource']['billing_info']['last_payment']['amount']['currency_code'] : null;
            $value = isset($webhook_arr_data['resource']['billing_info']['last_payment']['amount']['value']) ? $webhook_arr_data['resource']['billing_info']['last_payment']['amount']['value'] : null;
            $next_billing_time = $webhook_arr_data['resource']['billing_info']['next_billing_time'];
            
            // check plain id
            if($plan_id == $providerPlanId) {
                // update monthly membership provider
                $monthly_subscritpion_data = array(
                    'plan_id' => $plan_id,
                    'status_update_time' => $status_update_time,
                    'email_address' => $email_address,
                    'currency_code' => $currency_code,
                    'value' => $value,
                    'next_billing_time' => $next_billing_time,
                );
                $db->where('subscriptionID', $subscription_id);
                $db->update('providerMonthlyMembershipHistory', $monthly_subscritpion_data);
                
                // approve this provider
                if($approvalStatus != $g_provider_status_active) {
                    
                    $approveData = array(
                        'presciberStatus' => $g_provider_status_active,
                        'approvedAt' => date('Y-m-d H:i:s')
                    );
                    
                    $db->where('userId', $providerUserId);
                    $update_id = $db->update('prescribers', $approveData);
                    
                    // insert change status log history
                    $log_data = array(
                        'status' => 'Approved/Active',
                        'suspended_reason' => $webhook_arr_data['summary'],
                        'changed_date' => date('Y-m-d H:i:s'),
                        'userId' => $providerUserId,
                        'roleType' => 'Provider',
                        'changedBy' => $providerUserId
                    );
                    $db->insert('statusChangeLogs', $log_data);
                }
            }
        } else if($webhook_arr_data['event_type'] == 'BILLING.SUBSCRIPTION.SUSPENDED' || $webhook_arr_data['event_type'] == 'BILLING.SUBSCRIPTION.CANCELLED' || $webhook_arr_data['event_type'] == 'BILLING.SUBSCRIPTION.EXPIRED' || $webhook_arr_data['event_type'] == 'BILLING.SUBSCRIPTION.PAYMENT.FAILED') {
            // check plain id
            if($plan_id == $providerPlanId) {
                // suspend this provider
                $approveData = array(
                    'presciberStatus' => $g_provider_status_subscription_failed
                );
                $db->where('userId', $providerUserId);
                $update_id = $db->update('prescribers', $approveData);
                
                // insert change status log history
                $log_data = array(
                    'status' => $g_provider_status_subscription_failed,
                    'suspended_reason' => $webhook_arr_data['summary'],
                    'changed_date' => date('Y-m-d H:i:s'),
                    'userId' => $providerUserId,
                    'roleType' => 'Provider',
                    'changedBy' => $providerUserId
                );
                $db->insert('statusChangeLogs', $log_data);
            }
        }
    } else if($resource_type == 'sale') {
        ///////
        // get paypal access token
        //////
        $curl = curl_init("$paypal_api_url/v1/oauth2/token");
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERPWD, "$paypal_client_id:$paypal_secret");
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json'
        ));
        $response = curl_exec($curl);
        $result = json_decode($response, true);
        curl_close($curl);
        
        $access_token = $result['access_token'];
        
        $billing_agreement_id = $webhook_arr_data['resource']['billing_agreement_id'];
        // check the order if the order id is valid
        $url = "$paypal_api_url/v1/billing/subscriptions/$billing_agreement_id";
        $curl = curl_init();
        $auth_header = "Authorization: Bearer ".$access_token;
        // Send HTTP request to get the token
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
             $auth_header,
            'Content-Type: application/json',
            
          ),
        ));
        
        $jresponse = curl_exec($curl);
        $response = json_decode($jresponse, true);
        
        $plan_id = $response['plan_id'];
        
        if($webhook_arr_data['event_type'] == 'PAYMENT.SALE.COMPLETED') {
            // update next billing date
            $next_billing_time = $response['billing_info']['next_billing_time'];
            $data = array(
                'status_update_time' => $response['status_update_time'],
                'currency_code' => $webhook_arr_data['resource']['amount']['currency'],
                'value' => $webhook_arr_data['resource']['amount']['total'],
                'next_billing_time' => $next_billing_time
            );
            $db->where('subscriptionID', $billing_agreement_id);
            // check plan id
            if($plan_id == $providerPlanId) {
                $db->update('providerMonthlyMembershipHistory', $data);
            }
        }
        
        // insert sales raw data
        $sales_data = array(
            'subscriptionId' => $billing_agreement_id, 
            'event_type' => $webhook_arr_data['event_type'], 
            'sales' => $webhook_json_data, 
            'addedAt' => date('Y-m-d H:i:s')
        );
        $db->insert('paypal_webhook_sales', $sales_data);
    }
}

?>