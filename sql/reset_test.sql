DROP DATABASE IF EXISTS pmc_test;
CREATE DATABASE pmc_test;
USE `pmc_test`;

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
	FOREIGN KEY (seller_id) REFERENCES users(id),
	FOREIGN KEY (buyer_id) REFERENCES users(id),
	FOREIGN KEY (car_id) REFERENCES market(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# user:admin, pwd:Admin777!
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
	"admin@email.org",
	NULL,
	0,
	false
);

# user:a_buyer, pwd:Buyer777!
INSERT INTO users (
	username,
	hash,
	email,
	auth_key,
	role_id,
	auto_login
) VALUES (
	"a_buyer",
	"$2y$12$Je4t8hrfTYhhzDmLeTFwXuRnof2pQYylYKjMp97y0SNMtSH6G9TBi",
	"a_buyer@email.org",
	NULL,
	1,
	true
);

# user:a_seller, pwd:Seller777!
INSERT INTO users (
	username,
	hash,
	email,
	auth_key,
	role_id,
	auto_login
) VALUES (
	"a_seller",
	"$2y$12$2BpdXSIVf83ND7XLUh4dzOEcderzZMprt3Xee19Pcw3LnBmcmWCMu",
	"a_seller@email.org",
	NULL,
	2,
	false
);

INSERT INTO market (
	model,
	year,
	price,
	km,
	seller_id,
	image
) VALUES (
	"Acura MDX",
	2023,
	50745,
	10,
	3,
	'acura.jpg'
);;

INSERT INTO market (
	model,
	year,
	price,
	km,
	seller_id,
	image
) VALUES (
	"Alfa Romeo Giulia Quadrifoglio",
	2023,
	81855,
	20,
	3,
	'alfa.png'
);

INSERT INTO orders (
	seller_id,
	buyer_id,
	car_id
) VALUES (
	3,
	2,
	2
);

