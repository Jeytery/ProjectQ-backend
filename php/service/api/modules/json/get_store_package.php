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

$storeId = getRequestValue('store_id');

if ($storeId) {

    $storeId = " `store`.store_id LIKE '" . $storeId . "'";

}

// Find values

$storeName = getRequestValue('store_name');

if ($storeName) {

    $storeName = " `store`.name LIKE '" . $storeName . "'";

}

$activated = getRequestValue('activated');

if ($activated) {

    $activated = " AND `store`.activated = " . $activated;

}

$results = $database->getStringList(
"SELECT JSON_OBJECT (
    \"storeId\", store_id,
    \"userId\", user_id,
    \"storeName\", name,
    \"description\", description,
    \"picture\", picture,
    \"activated\", activated = 1,
    \"languageCode\", language_code,
    \"lastUpdated\", UNIX_TIMESTAMP( last_updated ),
    \"createdAt\", UNIX_TIMESTAMP( created_at ),

    \"storePackages\", JSON_EXTRACT( IFNULL( (
    SELECT CONCAT( \"[\",
            GROUP_CONCAT(
                JSON_OBJECT (
                    'packageId', `package`.package_id,
                    'packageName', `package`.name ,
                    'description', `package`.description,
                    'packageCode', `package`.code,
                    'languageCode', `package`.language_code,
                    'lastUpdated', UNIX_TIMESTAMP(`package`.last_updated),
                    'createdAt', UNIX_TIMESTAMP(`package`.created_at),
                    'packageOptions', JSON_EXTRACT( IFNULL ( (
                    SELECT CONCAT( \"{\",
                               GROUP_CONCAT( '\"', `options`.name, '\": \"', `options`.value, '\"' ),
                           \"}\" )
                    FROM `options`
                    WHERE `package`.package_id = `options`.object_id AND `options`.object_name = 'package'
                    ), '{}'), '$'),


                    'packageTasks', JSON_EXTRACT( IFNULL( (
                    SELECT CONCAT( \"[\",
                            GROUP_CONCAT(
                                JSON_OBJECT (
                                    'taskId', `task`.task_id,
                                    'taskType', `task`.task_type,
                                    'taskName', `task`.name ,
                                    'description', `task`.description,
                                    'languageCode', `task`.language_code,
                                    'lastUpdated', UNIX_TIMESTAMP(`task`.last_updated),
                                    'createdAt', UNIX_TIMESTAMP(`task`.created_at),

                                    'taskComponents', JSON_EXTRACT( IFNULL( (
                                    SELECT CONCAT( \"[\",
                                            GROUP_CONCAT(
                                                JSON_OBJECT (
                                                    'componentId', `component`.component_id,
                                                    'pureNumber', `component`.pure_number,
                                                    'handlerInput', `component`.handler_input,
                                                    'componentName', `component`.name,
                                                    'description', `component`.description,
                                                    'languageCode', `component`.language_code,
                                                    'lastUpdated', UNIX_TIMESTAMP(`component`.last_updated),
                                                    'createdAt', UNIX_TIMESTAMP(`component`.created_at)
                                                )
                                            ),
                                    \"]\" )
                                       FROM `component`, `task_component`
                                       WHERE `component`.component_id = `task_component`.component_id AND `task`.task_id = `task_component`.task_id
                                   ), '[]'), '$')

                                )
                            ),
                    \"]\" )
                        FROM `task`, `package_task`
                        WHERE `package`.package_id = `package_task`.package_id AND `task`.task_id = `package_task`.task_id
                    ), '[]'), '$')

                )
            ),

    \"]\" )
        FROM `package`, `store_package`
        WHERE `package`.package_id = `store_package`.package_id AND `store`.store_id = `store_package`.store_id
    ), '[]'), '$'),


    \"storeOptions\", JSON_EXTRACT( IFNULL ( (
        SELECT CONCAT( \"{\",
                   GROUP_CONCAT( '\"', `options`.name, '\": \"', `options`.value, '\"' ),
               \"}\" )
        FROM `options`
        WHERE `store`.store_id = `options`.object_id AND `options`.object_name = 'store'
    ), '{}'), '$')
) as result
FROM `store`
WHERE" . $storeId . $storeName . $activated . "
ORDER BY `store`.store_id" . $offsetRows . $sort);

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
