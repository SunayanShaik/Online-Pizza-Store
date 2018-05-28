-- Portable script for creating the pizza database
-- on your dev system:
-- mysql -u root -p < dev_setup.sql    
-- mysql -D pizzadb -u root -p < createdb.sql 
--  or, on topcat:
-- mysql -D <user>db -u <user> -p < createdb.sql 
create table sizes(
id integer auto_increment,
size_name varchar(30) not null,
unique (size_name),
primary key(id)
);

create table toppings(
id integer auto_increment,
topping_name varchar(30) not null,
unique (topping_name),
primary key(id)
);

create table status_values (
status_value varchar(10) primary key
);

create table pizza_orders(
id integer auto_increment,
room_number integer not null,
size varchar(30) not null,
day integer not null,
status varchar(10) references status_values(status_value),
primary key(id)
);

-- toppings for a pizza order
-- Note: we can't use a foreign key to toppings here because the topping
-- might be deleted while the order is still in the system
create table order_topping (
order_id integer not null,
topping varchar(30) not null,
primary key (order_id, topping),
foreign key (order_id) references pizza_orders(id));

-- one-row table doesn't need a primary key
create table pizza_sys_tab (
current_day integer not null
);


insert into pizza_sys_tab values (1);
-- minimal toppings and sizes: one each
insert into toppings values (1,'Pepperoni');
insert into sizes values (1,'small');
insert into status_values values ('Preparing');
insert into status_values values ('Baked');
insert into status_values values ('Finished');


