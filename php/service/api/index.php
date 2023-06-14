<?php

/* 
   The index file of the API
   ProQ. 2023
 */

// Activate | Deactivate log values: true | false
$needLogForDebug = false;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json;charset=utf-8'); //application/json;charset=utf-8

// Get header Authorization
function getAuthorizationHeader() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

// Get access token from header
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

// Get header parameters
function getHeaderValue($keyName) {
    $keyValue = '';
    foreach (getallheaders() as $name => $value) {
        if ($name === $keyName) {
            $keyValue = str_replace("'", "`", $value); /* \\\' */
            break;
        }
    }
    return $keyValue;
}

// Get request value by name
function getRequestValue($keyName) {
    global $postRequestBody;
    $keyValue = '';
    foreach ($_REQUEST as $apiFunctionName => $apiFunctionValues) {
        if ($apiFunctionName === $keyName) {
            $keyValue = $apiFunctionValues;
            break;
        }
    }
    if (!$keyValue && $postRequestBody) {
        foreach ($postRequestBody as $name => $value) {
            if ($name === $keyName) {
                $keyValue = str_replace("'", "`", $value); /* \\\' */
                break;
            }
        }
    }
    return $keyValue;
}

// Get Post request body
function getRequest() {
    global $postRequestBody;
    return $postRequestBody;
}

// Get cookie
function getCookie($cookieName) {
    $cookie = '';
    if (isset($_COOKIE[$cookieName])) {
        $cookie = $_COOKIE[$cookieName];
    }
    return $cookie;
}

// Get param ( <param> = <value>)
function getParam($paramValue, $delim) {
    $param = '';
    $pos = strpos($paramValue, $delim);
    if ($pos > 0) {
        $param = substr($paramValue, 0, $pos);
    }
    return $param;
}

// Get value ( <param> = <value>)
function getValue($paramValue, $delim) {
    $value = '';
    $pos = strrpos($paramValue, $delim);
    if ($pos > 0) {
        $value = substr($paramValue, $pos + 1);
    }
    return $value;
}

// Write to log
$logFilename = ($needLogForDebug == true ? dirname(__FILE__) . '/service.log' : null);

function writeToLog($filename, $logData) {
    $logData = date(DATE_RFC822) . "|" . $logData . PHP_EOL;
    file_put_contents($filename, $logData, FILE_APPEND | LOCK_EX);
}

// Get started API
$requestMethod = $_SERVER['REQUEST_METHOD'];
$queryString = $_SERVER['QUERY_STRING'];
$remoteAddr = $_SERVER['REMOTE_ADDR'];

$postRequestBody = '';
$requestBody = '';
$isContentFounded = false;

if ($requestMethod == 'GET' && count($_REQUEST) > 0) {
    $isContentFounded = true;
}
else if ($requestMethod == 'POST') {
    $requestBody = file_get_contents('php://input');
    if ($requestBody) {
        parse_str($requestBody, $postRequestBody);
        $isContentFounded = true;
    }
}

// Session Id
// 10-digit number
//$sessionId = crc32(mt_rand() + time());
// 9-digit number
$sessionId = mt_rand();

if ($isContentFounded) {
    if ($queryString != '' && $requestBody != '') {
        $paramString = $queryString . "|" . $requestBody;
    } else if ($queryString!='') {
        $paramString = $queryString;
    } else {
        $paramString = $requestBody;
    }
    if ($logFilename) {
        if (strlen($paramString) > 1024) {
            $paramString = substr($paramString, 0, 1024) . " ..";
        }
        writeToLog($logFilename, "Info|Session activated|#" . $sessionId . "|" . $requestMethod . "|" . $paramString . "|" . $remoteAddr);
    }

    $moduleName = getRequestValue('module');

    if ($moduleName) {
        $moduleFilename = dirname(__FILE__) . '/modules/' . $moduleName . '.php';
        if (file_exists($moduleFilename)) {
            if ($logFilename) {
                writeToLog($logFilename, "Info|Module registered|#" . $sessionId . "|" . $moduleName);
            }

            require_once dirname(__FILE__) . '/includes/functions.php';

            require_once dirname(__FILE__) . '/database/repository.php';
            $database = new Repository($logFilename, $sessionId, false);

            /*
              Highly recommended make the new database user wuth restricted priviledges (for Select, Execute and Show View grants)
              for secure database transactions on Insert, Update or Delete content data. Do not use auto-function for create user.
              Make a new user by phpMyAdmin | MySQL® Database Wizard tool on hosting and restrict priviledges (Select, Execute, Show View set On)
             */

            // Define connection parameters
            /*
            $dbHostname = 'localhost';
            $dbUsername = 'database_username';
            $dbPassword = 'database_password';
            $dbDatabase = 'database_schema';
            //$dbCharset = 'utf8';
            //$dbCollate = '';
             */

            $dbHostname = 'localhost';
            $dbUsername = 'xbitoakr_proq';
            $dbPassword = '3_Ueb]&W@]p-';
            $dbDatabase = 'xbitoakr_proq';

            if ($database->connect($dbHostname, $dbUsername, $dbPassword, $dbDatabase /*,$dbCharset, $dbCollate*/)) {
                require_once $moduleFilename;
            } else {
                // Replace database error with $database->getErrorMessage()
                echo '{"results":[{"errorMessage":"Database connection failed", "errorCode":-1004}], "sessionId":' . $sessionId . ', "status":"error"}';
                if ($logFilename) {
                    writeToLog($logFilename, "Error|Database connection failed|#" . $sessionId);
                }
            }
            $database->close();
        } else {
            echo '{"results":[{"errorMessage":"Module not found", "errorCode":-1003}], "sessionId":' . $sessionId . ', "status":"error"}';
            if ($logFilename) {
                writeToLog($logFilename, "Error|Module not found|#" . $sessionId . "|" . $moduleName);
            }
        }
    } else {
        echo '{"results":[{"errorMessage":"Module name not found", "errorCode":-1002}], "sessionId":' . $sessionId . ', "status":"error"}';
        if ($logFilename) {
            writeToLog($logFilename, "Error|Module name not found|#" . $sessionId . "|" . $moduleName);
        }
    }
    if ($logFilename) {
        writeToLog($logFilename, "Info|Session deactivated|#" . $sessionId);
    }
} else {
    echo '{"results":[{"errorMessage":"Content not found", "errorCode":-1001}], "sessionId":' . $sessionId . ', "status":"error"}';
    if ($logFilename) {
        writeToLog($logFilename, "Error|Content not found|#" . $sessionId);
    }
}

?>
