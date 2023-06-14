<?php

require_once dirname(__FILE__) . '/request_values.php';
require_once dirname(__FILE__) . '/request_auth.php';

$request = getRequest();

$storePackage = json_decode($request);

/*

{"storeId": 1, "userId": 3, "storeName": "Store-1", "description": "Store of packages", "picture": null, "activated": true, "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "storePackages": [{"packageId": 1, "packageName": "Package-1", "description": "Package of data", "packageCode": "P-1", "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "packageOptions": {}, "packageTasks": [{"taskId": 1, "taskType": 1, "taskName": "Task-1", "description": "Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 1, "pureNumber": 1, "handlerInput": "AAA-BBB-CCC", "componentName": "Component-1", "description": "Component of Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}, {"componentId": 3, "pureNumber": 3, "handlerInput": "FFF-XXX-EEE", "componentName": "Component-3", "description": "Component of Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 2, "taskType": 1, "taskName": "Task-2", "description": "Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 2, "pureNumber": 2, "handlerInput": "CCC-DDD-BBB", "componentName": "Component-2", "description": "Component of Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}, {"componentId": 4, "pureNumber": 4, "handlerInput": "WWW-SSS-HHH", "componentName": "Component-4", "description": "Component of Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 3, "taskType": 1, "taskName": "Task-3", "description": "Task 3 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": []}]}, {"packageId": 2, "packageName": "Package-2", "description": "Package of data", "packageCode": "P-2", "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "packageOptions": {}, "packageTasks": [{"taskId": 4, "taskType": 1, "taskName": "Task-1", "description": "Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 5, "pureNumber": 5, "handlerInput": "QQQ-VVV-UUU", "componentName": "Component-5", "description": "Component of Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 5, "taskType": 1, "taskName": "Task-2", "description": "Task 2 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 6, "pureNumber": 6, "handlerInput": "KKK-LLL-III", "componentName": "Component-6", "description": "Component of Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}]}], "storeOptions": {}}

 */

// Store

$storeId = $storePackage->{'storeId'};
$storeName = $storePackage->{'storeName'};
$description = $storePackage->{'description'};
$languageCode = $storePackage->{'languageCode'};

$sqlRequest = "UPDATE `store` SET name = '$storeName', description = '$description', language_code = '$languageCode' WHERE store_id = $storeId";

$isCompleted = $database->query($sqlRequest);

if (!$isCompleted) {

    echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

    exit();
}

// $storeOptions = $storePackage->{'storeOptions'}; // array

// Packages

$storePackages = $storePackage->{'storePackages'}; // array

foreach ($storePackages as $package) {

    $packageId = $package->('packageId');
    $packageName = $package->('packageName');
    $description = $package->{'description'};
    $packageCode = $package->{'code'};
    $languageCode = $package->{'languageCode'};

    $sqlRequest = "UPDATE `package` SET name = '$packageName', description = '$description', code = '$packageCode', language_code = '$languageCode' WHERE package_id = $packageId";

    $isCompleted = $database->query($sqlRequest);

    if (!$isCompleted) {

        echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

        exit();
    }

    // $packageOptions = $package->{'packageOptions'}; // array

    // Tasks

    $packageTasks = $package->{'packageTasks'}; // array

    foreach ($packageTasks as $task) {

        $taskId = $task->('taskId');
        $taskName = $task->('taskName');
        $description = $task->{'description'};
        $languageCode = $task->{'languageCode'};

        $sqlRequest = "UPDATE `task` SET name = '$taskName', description = '$description', language_code = '$languageCode' WHERE task_id = $taskId";

        $isCompleted = $database->query($sqlRequest);

        if (!$isCompleted) {

            echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

            exit();
        }

        // Components

        $taskComponents = $task->{'taskComponents'}; // array

        foreach ($taskComponents as $components) {

            $componentId = $task->('componentId');
            $pureNumber = $task->('pureNumber');
            $handlerInput = $task->('handlerInput');
            $componentName = $task->('componentName');
            $description = $task->{'description'};
            $languageCode = $task->{'languageCode'};

            $sqlRequest = "UPDATE `component` SET pure_number = '$pure_number', handler_input = '$handlerInput', name = '$taskName', description = '$description', language_code = '$languageCode' WHERE component_id = $componentId";

            $isCompleted = $database->query($sqlRequest);

            if (!$isCompleted) {

                echo '{"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":' . $sessionId . ', "status":"error"}';

                exit();
            }

        }

    }

}

// Log to database

echo '{"results":[{"completed":true}], "sessionId":' . $sessionId . ', "status":"success"}';

?>

