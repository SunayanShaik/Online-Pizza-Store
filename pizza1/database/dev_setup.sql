-- Setup for development machine
-- Create pizzadb, or recreate it
-- Create a user for it
drop database if exists pizzadb; -- only for your server
create database pizzadb; -- only for your own server

GRANT SELECT, INSERT, DELETE, UPDATE
ON pizzadb.*
TO pizza_user@localhost
IDENTIFIED BY 'pa55word';

