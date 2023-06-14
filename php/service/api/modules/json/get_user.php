<?php

require_once dirname(__FILE__) . '/request_values.php';
require_once dirname(__FILE__) . '/request_auth.php';

$offset = getRequestValue('offset');
$rows = getRequestValue('rows');

$offsetRows = "";

if ($rows) {

    if ($offset) {
        $offsetRows = " LIMIT $offset, $rows";
    }
    else {
        $offsetRows = " LIMIT $rows";
    }

}

$sort = getRequestValue('sort');

// Find values

if ($currentUserType == 1) { // Admin type // select value from settings where value = 'usertype_admin'

    $findUsername = getRequestValue('find_username');

    if (!$findUsername) {
        $findUsername = $currentUserUsername;
    }

}
else { // Other types

    $findUsername = $currentUserUsername;

}

$userId = getRequestValue('user_id');

if ($userId) {

    $userId = " AND u.id LIKE '" . $userId . "'";

}

// ? First name | Last name
$findName = getRequestValue('find_name');

if ($findName) {

    $findName = " AND MATCH ( u.first_name, u.last_name ) AGAINST ( '" . $findName . "' )";

}

$email = getRequestValue('email');

if ($email) {

    $email = " AND u.email LIKE '" . $email . "'";

}

$phone = getRequestValue('phone');

if ($phone) {

    $phone = " AND u.phone LIKE '" . $phone . "'";

}

$activated = getRequestValue('activated');

if ($activated) {

    $activated = " AND u.activated = " . $activated;

}

$results = $database->getStringList(
"SELECT JSON_OBJECT (
    \"userId\", user_id,
    \"firstName\", first_name,
    \"lastName\", last_name,
    \"userEmail\", email,
    \"userPhone\", phone,
    \"activated\", activated = 1,
    \"lastUpdatedDate\", last_updated,
    \"lastUpdated\", UNIX_TIMESTAMP( last_updated ),
    \"createdAt\", UNIX_TIMESTAMP( created_at ),
    \"createdAtDate\", created_at,
    \"userOptions\", JSON_EXTRACT( IFNULL( (
    SELECT CONCAT( \"{\",
        GROUP_CONCAT( '\"', `options`.name, '\": ',
            IF( `options`.option_type = 'number' OR `options`.option_type = 'boolean',
                `options`.value, CONCAT( '\"', `options`.value, '\"' )
            )
        ),
    \"}\" )
    FROM `options`
    WHERE `user`.user_id = `options`.object_id AND `options`.object_name = 'user'
    ),'{}'),'$')
) as result
FROM `user`
WHERE username LIKE '" . $findUsername . "'
ORDER BY user_id" . $offsetRows . $sort);

if ($results) {

    $foundRows = $database->getIntValue("SELECT FOUND_ROWS()");
    echo '{"results":[' . $results . '], "rows":' . $foundRows . ', "sessionId":' . $sessionId . ', "status":"success"}';

} else {

    $databaseError = $database->getErrorMessage();

    if ($databaseError) {

        echo '{"results":[{"errorMessage":"' . $databaseError . '", "errorCode":-1100}], "session_id":' . $sessionId . ', "status":"error"}';

    } else {

        echo '{"results":[], "rows":0, "sessionId":' . $sessionId . ', "status":"success"}';

    }

}

?>
