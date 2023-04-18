<?php

    // Substitute your Twilio Account SID and API Key details 
    require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
    use Twilio\Jwt\AccessToken;
    use Twilio\Jwt\Grants\VideoGrant;


    // $accountSid = 'AC4b8e2ab27158fa75e12d14bd5e018911';
    // $apiKeySid = 'SKc7d1466e031879ca075f9257b4d617a3';
    // $apiKeySecret = 'Ea3bvyIxfFgQM1tWHwkVS5klanmhkMGf';
    $accountSid = 'ACc0c58e353a75700787309c841d154b6b';
    $apiKeySid = 'SKdf71d49026b87abc7aa2ac16c5384e9e';
    $apiKeySecret = 'vkQw60CmSLY9ug61CWRxQoFeVlNEst9m';

    $identity = uniqid();
    //$identity = 'twillofirstAPIKeyName';

    // Create an Access Token
    $token = new AccessToken(
        $accountSid,
        $apiKeySid,
        $apiKeySecret,
        3600,
        $identity
    );

    // Grant access to Video
    $grant = new VideoGrant();
    $grant->setRoom('5rd room');
    $token->addGrant($grant);

    $returndata['token'] =  $token->toJWT();
    // Serialize the token as a JWT
    echo json_encode( $returndata );
 
    // echo json_encode($token->toJWT());

?>