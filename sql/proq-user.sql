-- Pro Q (c) Database User
-- Version 1.0.0

-- proq.cc Copyright (c) 2023, MySQL version
-- All rights reserved.
-- Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

-- DROP USER proq_admin;
CREATE USER proq_admin IDENTIFIED BY 'proq_admin';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE, SHOW VIEW, TRIGGER ON proq.* TO 'proq_admin'@'localhost' IDENTIFIED BY 'proq_admin';
FLUSH PRIVILEGES;

-- DROP USER proq_client;
CREATE USER proq_client IDENTIFIED BY 'proq_client';
GRANT SELECT, EXECUTE, SHOW VIEW ON proq.* TO 'proq_client'@'localhost' IDENTIFIED BY 'proq_client';
FLUSH PRIVILEGES;