-- create and select the database
DROP DATABASE IF EXISTS proj2_server;
CREATE DATABASE proj2_server;

-- Create a user named svr_user
GRANT SELECT, INSERT, UPDATE, DELETE, ALTER
ON proj2_server.*
TO svr_user@localhost
IDENTIFIED BY 'pa55word';