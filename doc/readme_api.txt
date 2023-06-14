

Pro Q API examples
==========================

Json hard talks: https://www.sitepoint.com/use-json-data-fields-mysql-databases/


Get User
--------


module = json/get_user

https://proq.cc/service/api/?module=json/get_user&username=admin&password=proq.admin

// https://proq.cc/service/api/json/get_user/?username=admin&password=proq.admin


{"results":[{"userId": 1, "firstName": "admin", "lastName": "", "userEmail": "admin@prog.cc", "userPhone": "", "activated": true, "lastUpdated": 1684680142, "createdAt": 1684680142, "userOptions": {"banned": false, "emailVerified": true, "phoneVerified": false}}], "rows":1, "sessionId":696044385, "status":"success"}


Get Package
-----------

https://proq.cc/service/api/?module=json/get_store_package&username=admin&password=proq.admin&store_name=%25

OR

https://proq.cc/service/api/?module=json/get_store_package&username=admin&password=proq.admin&store_id=1

{"storeId": 1, "userId": 3, "storeName": "Store-1", "description": "Store of packages", "picture": null, "activated": true, "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "storePackages": [{"packageId": 1, "packageName": "Package-1", "description": "Package of data", "packageCode": "P-1", "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "packageOptions": {}, "packageTasks": [{"taskId": 1, "taskType": 1, "taskName": "Task-1", "description": "Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 1, "pureNumber": 1, "handlerInput": "AAA-BBB-CCC", "componentName": "Component-1", "description": "Component of Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}, {"componentId": 3, "pureNumber": 3, "handlerInput": "FFF-XXX-EEE", "componentName": "Component-3", "description": "Component of Task 1 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 2, "taskType": 1, "taskName": "Task-2", "description": "Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 2, "pureNumber": 2, "handlerInput": "CCC-DDD-BBB", "componentName": "Component-2", "description": "Component of Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}, {"componentId": 4, "pureNumber": 4, "handlerInput": "WWW-SSS-HHH", "componentName": "Component-4", "description": "Component of Task 2 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 3, "taskType": 1, "taskName": "Task-3", "description": "Task 3 of Package 1", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": []}]}, {"packageId": 2, "packageName": "Package-2", "description": "Package of data", "packageCode": "P-2", "languageCode": "eng", "lastUpdated": 1685284862, "createdAt": 1685284862, "packageOptions": {}, "packageTasks": [{"taskId": 4, "taskType": 1, "taskName": "Task-1", "description": "Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 5, "pureNumber": 5, "handlerInput": "QQQ-VVV-UUU", "componentName": "Component-5", "description": "Component of Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}, {"taskId": 5, "taskType": 1, "taskName": "Task-2", "description": "Task 2 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955, "taskComponents": [{"componentId": 6, "pureNumber": 6, "handlerInput": "KKK-LLL-III", "componentName": "Component-6", "description": "Component of Task 1 of Package 2", "languageCode": "eng", "lastUpdated": 1685284955, "createdAt": 1685284955}]}]}], "storeOptions": {}}


User SignIn
---------

https://proq.cc/service/api/?module=json/user_signin&username=admin&password=proq.admin

{"results":[{"accessToken":"UAcLX1xRBltfW1lXVFxfWFVcX1dYUVxcXQVQVFtdBAYJCl1VU1RfVw==","updateToken":"AlVVXg9UVAhRDVQFWgpfUlIODQ1XVg5aX1FXW1wNVlVbCFlSAQxbXA=="}], "sessionId":287411912, "status":"success"}


Get Token
---------

https://proq.cc/service/api/?module=json/get_token&token=AlVVXg9UVAhRDVQFWgpfUlIODQ1XVg5aX1FXW1wNVlVbCFlSAQxbXA==

{"results":[{"accessToken":"UQVbWV0DAVVfVlBWDl0NVVcIXg0EVgtfWllTWghZVlUIDQxUAlVcDQ==","updateToken":"U1ELXg9XVlgICFAGCA1aUlJdD11WVlxcDwVdVFFcB1VVDF1WBQhZDw=="}], "sessionId":715007608, "status":"success"}

User SignUp
-----------

https://proq.cc/service/api/?module=json/user_signup&username=tester1&password=tester1&first_name=tester%201&last_name=temp tester&email=tester1@gmail.com&phone=000


{"results":[{"accessToken":"RFFFRVcQABEGEkNWEwUVB0sQBkMEQlxAEFdHAhFTRBIBQVBDXUpBBg==", "updateToken":"F1FHQVcRUEEAQBJcRVVBBxVCUxQFFwdGQAZKA0ZdREJTQFUVB0NCXQ=="}], "sessionId":1369902247, "status":"success"}












