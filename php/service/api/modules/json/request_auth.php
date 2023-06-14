<?php

$token = getHeaderValue('Authorization'); //getAuthorizationHeader();

if (!$token) {
    $token = getRequestValue('token'); // Request access token
} else {
    $token = getBearerToken($token); // Header access token
}

if ($token) {

    // Decrypt accees token

    $sqlRequest = 'SELECT JSON_OBJECT ( "userId", user_id, "userType", user_type, "username", username, "password", password, "activated", activated, "accessTokenCreated", UNIX_TIMESTAMP ( access_token_created ) ) FROM `user` WHERE access_token = "' . $token . '"';

    $jsonResponse = $database->getStringValue($sqlRequest);

    $currentUser = json_decode($jsonResponse);

} else if ($username) {

    $sqlRequest = 'SELECT JSON_OBJECT ( "userId", user_id, "userType", user_type, "username", username, "password", password, "activated", activated, "accessTokenCreated", UNIX_TIMESTAMP ( access_token_created ) ) FROM `user` WHERE username = "' . $username . '" AND password = "' . $password . '"';

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

    $currentUserType = $currentUser->{'userType'};
    $currentUserUsername = $currentUser->{'username'};
    $currentUserPassword = $currentUser->{'password'};

    // Check the token timelife
    $currentSessionTime = time();
    $tokenCreated = $currentUser->{'accessTokenCreated'};

    $isActualToken = false;

    if ($token) {

        if ($tokenCreated) {
            // Token expired time (in seconds)
            $tokenExpiredTime = $database->getStringValue('SELECT value FROM settings WHERE name = "access_token_expired_time"');

            if (!$tokenExpiredTime) {
                $tokenExpiredTime = 31536000; // 1 year seconds == 31536000 | 900 sec. == 15 min.
            }

            if ($currentSessionTime - $tokenCreated < $tokenExpiredTime) {
                $isActualToken = true;
            }
        }

    } else {
        $isActualToken = true;
    }

    // Token has been expired
    if (!$isActualToken) {

        echo '{"results":[{"errorMessage":"Authorization token has expired", "errorCode":-1007}], "sessionId":' . $sessionId . ', "status":"error"}';

        exit();

    }

    $isCurrentUserActivated = $currentUser->{'activated'};

} else {

    if ($token) {

        echo '{"results":[{"errorMessage":"Invalid access token", "errorCode":-1008}], "sessionId":' . $sessionId . ', "status":"error"}';

        exit();


    } else if ($username) {

        echo '{"results":[{"errorMessage":"Invalid username or password", "errorCode":-1009}], "sessionId":' . $sessionId . ', "status":"error"}';

        exit();

    }
}

?>
