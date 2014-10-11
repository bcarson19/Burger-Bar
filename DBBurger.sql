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
#Table to store food 
CREATE TABLE Food #Store all items on the menue 
(	
	name VARCHAR(30),
    id INTEGER,
	price	FLOAT(5,2),
	PRIMARY KEY(name, id)
);
#Create a databse to store the order, containing the order, price and username
CREATE TABLE foodOrders
(
    username VARCHAR(30),
    name   VARCHAR(30),
    orderID INTEGER,
    inCart tinyint, #true if item in unporcesses and still in cart
	#Primary key (orderID) cannot have this
	foreign key (username)references users (username),
    foreign key (name) references Food (name)
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

INSERT INTO food(name, id, price) VALUES
("1/3 lb Beef", 1, 2),
("1/2 lb Beef", 2, 2.25),
("Turkey", 3, 2),
("Veggie", 4, 2),
("White", 5, 0.5),
("Wheat", 6, 0.5),
("Texas Toast", 7, 0.75),
("Cheddar", 8, 0.35),
("American", 9, 0.35),
("Swiss", 10, 0.35),
("Tomatoes", 11, 0),
("Lettuce", 12, 0),
("Onions", 13, 0),
("Pickles", 14, 0),
("Bacon", 15, 0),
("Red Onion", 16, 0),
("Mushroms", 17, 0),
("Jalapenos", 18, 0),
("Ketchup", 19, 0),
("Mustard", 20, 0),
("Mayonnaise", 21, 0),
("BBQ", 22, 0),
("French Fries", 23, 2),
("Tater Tots", 24, 1),
("Onion Rings", 25, 1);

INSERT INTO foodOrders(username, orderID, name, inCart) VALUES
("Karoline", 1, "Turkey", 1),
("Karoline", 1, "Wheat", 1),
("Karoline", 1, "Tomatoes", 1),
("Karoline", 1, "French Fries", 1);



