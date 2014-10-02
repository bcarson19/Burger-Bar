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

# Create a tabel to store most recent orders, should this be most recent or favorites 
CREATE TABLE orderHistory 
(
	username		VARCHAR(30),
	order			VARCHAR(30),
	PRIMARY KEY(username),
	Foreign key (username)references users (username),
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
	Foreign key (username)references users (username),
);

INSERT INTO users VALUES
	("Karoline", "123456789"),
	("Brandon", "test"),
	("Tom", "password"),
	("Nariana", "TEST"),
	("Jacob", "gui"),
	("Michael", "db");

