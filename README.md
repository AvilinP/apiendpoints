# apiendpoints
API endpoint built with PHP.


## Installation

DROP DATABASE IF EXISTS ecommerce; CREATE DATABASE ecommerce;

DROP TABLE IF EXISTS cart; DROP TABLE IF EXISTS sessions; DROP TABLE IF EXISTS products; DROP TABLE IF EXISTS users;

CREATE TABLE users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, date DATETIME NOT NULL DEFAULT current_timestamp, username VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL) ENGINE = InnoDB;

CREATE TABLE products (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, date DATETIME NOT NULL DEFAULT current_timestamp, product VARCHAR(100) NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(500) NOT NULL) ENGINE = InnoDB;

CREATE TABLE sessions (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, token TEXT NOT NULL, last_used INT NOT NULL, CONSTRAINT sessions_ibfk_1 FOREIGN KEY(user_id) REFERENCES users(id)) ENGINE = InnoDB;

CREATE TABLE cart (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, token TEXT NOT NULL, date DATETIME NOT NULL DEFAULT current_timestamp, CONSTRAINT cart_ibfk_1 FOREIGN KEY(product_id) REFERENCES products(id)) ENGINE = InnoDB;

## How to use

This API endpoints uses GET, so in order to test out the endpoints, please write your information in your web browser's url bar.

* Start by installing the database with the SQL code above.

* Register and log in in order to recieve your token.

* You can add, delete and update products without your token. The valid token is only needed to get all products from the database.

* If you want to add or delete products from cart, you also need a valid token. 

* The token's time frame will be prolonged while you're active on the site. If you leave the session, and your token is not active anymore, you can create a new one when you log in.