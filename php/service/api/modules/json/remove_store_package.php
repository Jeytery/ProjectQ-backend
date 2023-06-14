<?php

require_once dirname(__FILE__) . '/request_values.php';
require_once dirname(__FILE__) . '/request_auth.php';

$packageId = getRequestValue('package_id');

$sqlRequest = "DELETE FROM `package` WHERE package_id = $packageId";

$isCompleted = $database->query($sqlRequest);

if ($isCompleted) {

    // Log to database

    echo '{"results":[{"completed":true}], "sessionId":' . $sessionId . ', "status":"success"}';

} else {

    echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

}


?>

