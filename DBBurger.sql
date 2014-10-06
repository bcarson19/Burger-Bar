#Initial database for project 

DROP DATABASE IF EXISTS DBBurger;
CREATE DATABASE DBBurger;
USE DBBurger;

# Create a table to create users 
CREATE TABLE users
(
	username	VARCHAR(30),
	pw			VARCHAR(30),
	PRIMARY KEY(username)
);
#Create a databse to store the order, containing the order, price and username
CREATE TABLE foodOrders
(
	username VARCHAR(30),
	orderID INTEGER,
	PRIMARY KEY(username),	
	foreign key (username)references users (username)
);

CREATE TABLE Food #Store all items on the menue 
(	
	name VARCHAR(30),
	orderId INTEGER,
	price	FLOAT(5,2),
	PRIMARY KEY(name)
);

CREATE TABLE orderInProgress 
( 
	orderId INTEGER,
	food INTEGER, 
	PRIMARY key(orderId)
	#foreign key (food) references Food (name)
);
# Create a tabel to store most recent orders, should this be most recent or favorites 
CREATE TABLE orderHistory 
(
	username		VARCHAR(30),
	orderId INTEGER,
	food INTEGER, 
	processes		timestamp, 	
	PRIMARY KEY(username),
	Foreign key (username) references users (username),
	foreign key (orderId) references orderInProgress(orderID)
);



# Create a table to create users 
CREATE TABLE paymentInfo
(
	username	VARCHAR(30),
	paymentId	INTEGER,
	cardNumber 	INTEGER,
	typeOfCard	VARCHAR(30),
	adress 		VARCHAR(30),
	zipCode		VARCHAR(30),
	state		VARCHAR(30),
	expireDate	VARCHAR(30),
	Foreign key (username)references users (username)
);

INSERT INTO users VALUES
	("Karoline", "123456789"),
	("Brandon", "test"),
	("Tom", "password"),
	("Nariana", "TEST"),
	("Jacob", "gui"),
	("Michael", "db");

INSERT INTO paymentInfo VALUES
	("Karoline", 1, 123456789, "Visa", "3669 Asbury Street", "75205", "Dallas", "7/7/12"),
	("Karoline", 2, 987654321, "MasterCard", "3669 Asbury Street", "75205", "Dallas", "7/7/12");



