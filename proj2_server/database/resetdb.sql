-- Clean out orders and arrange for first orderID to be 1
-- can run this as svr_user on dev system
delete from orderItems;
delete from orders;
-- This command is ineffective on mysql v. 5.5 and earlier, but we have v. 5.6 
-- Reset auto_increment on table orders so first order will have id 1
ALTER TABLE orders AUTO_INCREMENT = 0;
