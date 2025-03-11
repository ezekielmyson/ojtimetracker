/* Create database */
CREATE DATABASE ojttracker;

/* Create table for users */

CREATE TABLE users_login (
    users_id INT(11) AUTO_INCREMENT NOT NULL,
    email VARCHAR(50) NOT NULL,
    userpassword TINYTEXT NOT NULL,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (users_id)
);

CREATE TABLE users_information (
    userinfo_id INT(11) AUTO_INCREMENT NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    date_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    users_id INT(11) NOT NULL,
    PRIMARY KEY (userinfo_id),
    FOREIGN KEY (users_id) REFERENCES users_login(users_id)
);

CREATE TABLE timelog (
    timelog_id INT(11) AUTO_INCREMENT NOT NULL,
    date DATE NOT NULL,
    date_time_in TIME NOT NULL DEFAULT '00:00:00',
    date_time_out TIME NOT NULL DEFAULT '00:00:00', 
    date_total_time FLOAT NOT NULL,
    users_id INT(11) NOT NULL,
    PRIMARY KEY (timelog_id),
    FOREIGN KEY (users_id) REFERENCES users_login(users_id)
    );
