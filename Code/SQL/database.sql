Drop DATABASE gestioneWhitelist;
CREATE DATABASE gestioneWhitelist;
use gestioneWhitelist;

create TABLE type(
    id int(2) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

create TABLE user(
    id int AUTO_INCREMENT PRIMARY KEY,
    username varchar(255),
    name varchar(50),
    surname varchar(50),
    email varchar(50),
    type int,
    password varchar(255),
    setpassword TINYINT,
    FOREIGN KEY (type) REFERENCES type(id)
);