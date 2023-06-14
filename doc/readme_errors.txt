
Errors
======

-1001  Request content failed           {"results":[{"errorMessage":"Content not found", "errorCode":-1001}], "sessionId":0, "status":"error"}
-1002  Request module name not found    {"results":[{"errorMessage":"Module name not found", "errorCode":-1002}], "sessionId":0, "status":"error"}
-1003  Request module not found         {"results":[{"errorMessage":"Module not found", "errorCode":-1003}], "sessionId":0, "status":"error"}
-1004  Database connection failed       {"results":[{"errorMessage":"Database connection failed", "errorCode":-1004}], "sessionId":0, "status":"error"}
-1005  Authorization failed             {"results":[{"errorMessage":"Authorization failed","errorCode":-1005}], "sessionId":0, "status":"error"}
-1006  User not activated               {"results":[{"errorMessage":"User not activated", "errorCode":-1006}], "sessionId":0, "status":"error"}
-1007  Authorization token has expired  {"results":[{"errorMessage":"Authorization token has expired", "errorCode":-1007}], "sessionId":0, "status":"error"}
-1008  Invalid access token             {"results":[{"errorMessage":"Invalid access token","errorCode":-1008}], "sessionId":0, "status":"error"}
-1009  Invalid username or password     {"results":[{"errorMessage":"Invalid username or password","errorCode":-1009}], "sessionId":0, "status":"error"}
-1010  Invalid update token             {"results":[{"errorMessage":"Invalid update token","errorCode":-1008}], "sessionId":0, "status":"error"}
-1011  Something went wrong             {"results":[{"errorMessage":"Something went wrong! Try again later.", "errorCode":-1011}], "sessionId":0, "status":"error"}

-1100  Database error                   {"results":[{"errorMessage":"<DATABASE_ERROR>","errorCode":-1100}], "sessionId":0, "status":"error"}


Success
=======

{"results":[<RESULTS>], "rows":<ROWS>, "sessionId":0, "status":"success"}