<?php

require_once dirname(__FILE__) . '/request_values.php';

$username = getRequestValue('username');
$password = getRequestValue('password');
$usertype = 2; //getRequestValue('user_type'); // select value from settings where value = 'usertype_customer'
$first_name = getRequestValue('first_name');
$last_name = getRequestValue('last_name');
$email = getRequestValue('email');
$phone = getRequestValue('phone');

$sqlRequest = "INSERT INTO `user` (username, password, user_type, first_name, last_name, email, phone) VALUES ('$username', '$password', '$usertype', '$first_name', '$last_name', '$email', '$phone')";

$isCompleted = $database->query($sqlRequest);

if ($isCompleted) {

    // Make new access token (encrypt)

    $accessToken = getTokenOrPasswordData( $username, 64 );

    $accessToken = getBase64EncodedData( $username, $accessToken );

    // Make new update token

    $updateToken = getTokenOrPasswordData( $username, 64 );

    $updateToken = getBase64EncodedData( $username, $updateToken );

    // save to database

    $sqlRequest = "UPDATE `user` SET access_token = '$accessToken', update_token = '$updateToken', access_token_created = CURRENT_TIMESTAMP WHERE username = '$username'";

    $database->query($sqlRequest);

    // Log to database

    echo '{"results":[{"accessToken":"' . $accessToken . '", "updateToken":"' . $updateToken . '"}], "sessionId":' . $sessionId . ', "status":"success"}';

} else {

    echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

}

?>

