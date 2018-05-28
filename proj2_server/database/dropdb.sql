-- For use where you can't drop the database (i.e., on topcat)
-- Drop tables in right order, so FKs are honored
drop table if exists administrators;
drop table if exists systemDay;
drop table if exists orderItems;
drop table if exists orders;
drop table if exists customers;
drop table if exists products;
drop table if exists categories;
drop table if exists addresses;
