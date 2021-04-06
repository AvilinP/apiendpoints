# apiendpoints
API endpoint built with PHP.


## Installation

DROP DATABASE IF EXISTS ecommerce; CREATE DATABASE ecommerce;

DROP TABLE IF EXISTS cart; DROP TABLE IF EXISTS sessions; DROP TABLE IF EXISTS products; DROP TABLE IF EXISTS users;

CREATE TABLE users (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, date DATETIME NOT NULL DEFAULT current_timestamp, username VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL) ENGINE = InnoDB;

CREATE TABLE products (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, date DATETIME NOT NULL DEFAULT current_timestamp, product VARCHAR(100) NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(500) NOT NULL) ENGINE = InnoDB;

CREATE TABLE sessions (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, user_id INT NOT NULL, token TEXT NOT NULL, last_used INT NOT NULL, CONSTRAINT sessions_ibfk_1 FOREIGN KEY(user_id) REFERENCES users(id)) ENGINE = InnoDB;

CREATE TABLE cart (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, product_id INT NOT NULL, token TEXT NOT NULL, date DATETIME NOT NULL DEFAULT current_timestamp, CONSTRAINT cart_ibfk_1 FOREIGN KEY(product_id) REFERENCES products(id)) ENGINE = InnoDB;

## Usage

This API endpoints uses GET, so in order to test out the endpoints, please write your information in your web browser's url bar.