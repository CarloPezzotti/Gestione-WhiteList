Drop DATABASE gestioneWhitelist;
CREATE DATABASE gestioneWhitelist;
use gestioneWhitelist;

create TABLE type(
    id int(2) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

create TABLE user(
    username varchar(255) PRIMARY KEY,
    name varchar(50),
    surname varchar(50),
    email varchar(50),
    type int,
    password varchar(255),
    setpassword TINYINT,
    FOREIGN KEY (type) REFERENCES type(id)
);

insert into type values(0,"admin");
insert into type values(0,"user");
