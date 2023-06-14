<?php

require_once dirname(__FILE__) . '/request_values.php';

$token = getHeaderValue('Authorization'); //getAuthorizationHeader();

if (!$token) {
    $token = getRequestValue('token'); // Request update token
} else {
    $token = getBearerToken($token); // Header update token
}

if ($token) {
    $sqlRequest = 'SELECT JSON_OBJECT ( "userId", user_id, "username", username, "password", password, "activated", activated ) FROM `user` WHERE update_token = "' . $token . '"';

    $jsonResponse = $database->getStringValue($sqlRequest);

    $currentUser = json_decode($jsonResponse);

} else {

    echo '{"results":[{"errorMessage":"Authorization failed", "errorCode":-1005}], "sessionId":' . $sessionId . ', "status":"error"}';

    exit();

}

if ($currentUser) {

    $isUserActivated = $currentUser->{'activated'};

    if (!$isUserActivated) {

        echo '{"results":[{"errorMessage":"User not activated", "errorCode":-1006}], "sessionId":' . $sessionId . ', "status":"error"}';

        exit();

    }

    $currentUserUsername = $currentUser->{'username'};

    // Make new access token (encrypt)

    $accessToken = getTokenOrPasswordData( $currentUserUsername, 64 );

    $accessToken = getBase64EncodedData( $currentUserUsername, $accessToken );

    // Make new update token

    $updateToken = getTokenOrPasswordData( $currentUserUsername, 64 );

    $updateToken = getBase64EncodedData( $currentUserUsername, $updateToken );

    // Save to database

    $sqlRequest = "UPDATE `user` SET access_token = '$accessToken', update_token = '$updateToken', access_token_created = CURRENT_TIMESTAMP WHERE username = '$currentUserUsername'";
    
    $database->query($sqlRequest);

    // Log to database

    echo '{"results":[{"accessToken":"' . $accessToken . '","updateToken":"' . $updateToken . '"}], "sessionId":' . $sessionId . ', "status":"success"}';

} else {

    echo '{"results":[{"errorMessage":"Invalid update token", "errorCode":-1010}], "sessionId":' . $sessionId . ', "status":"error"}';

    exit();

}

?>
