DROP DATABASE IF EXISTS pmc;

CREATE DATABASE pmc;
USE `pmc`;

CREATE TABLE roles (
    id INT(2) NOT NULL UNIQUE PRIMARY KEY,
    role VARCHAR(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO roles VALUES (0, "admin");
INSERT INTO roles VALUES (1, "buyer");
INSERT INTO roles VALUES (2, "seller");

CREATE TABLE users (
    id INT(11) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32) NOT NULL UNIQUE,
    hash CHAR(60) NOT NULL,
    email VARCHAR(32) NOT NULL,
    auth_key CHAR(32),
    role_id INT(2) NOT NULL,
    auto_login BOOL NOT NULL DEFAULT false,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX idx_auth_key ON users(auth_key);

CREATE TABLE market (
	id INT(11) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	model VARCHAR(32) NOT NULL,
	year INT(4) NOT NULL,
	price INT(9) NOT NULL,
	km INT(7) NOT NULL,
	seller_id INT(11) NOT NULL,
	image VARCHAR(128),
	FOREIGN KEY (seller_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE orders (
	id INT(11) NOT NULL UNIQUE AUTO_INCREMENT PRIMARY KEY,
	seller_id INT(11) NOT NULL,
	buyer_id INT(11) NOT NULL,
	car_id INT(11) NOT NULL,
	FOREIGN KEY (car_id) REFERENCES market(id),
	FOREIGN KEY (seller_id) REFERENCES users(id),
	FOREIGN KEY (buyer_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# user:admin, pwd: Admin777!
INSERT INTO users (
	username,
	hash,
	email,
	auth_key,
	role_id,
	auto_login
) VALUES (
	"admin",
	"$2y$12$L//8oNPQq7E9TuwWQXVyx.1j3EFOXD1dQzaPdPzIm6KtrK5f17sOW",
	"admin@test.com",
	"",
	0,
	false
);
