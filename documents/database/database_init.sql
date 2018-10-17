CREATE DATABASE IF NOT EXISTS dva231_project;
CREATE USER IF NOT EXISTS 'dva231_project'@'localhost' IDENTIFIED BY 'test123';
GRANT USAGE on *.* TO 'dva231__project'@'localhost' IDENTIFIED BY 'test123'; 
GRANT ALL PRIVILEGES ON dva231_project.* to 'dva231_project'@'localhost';
FLUSH PRIVILEGES; 

USE dva231_project;

CREATE TABLE IF NOT EXISTS user_level (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,		-- user-level-id
	name ENUM('admin','superuser','user') NOT NULL		-- user-level-name
);
INSERT INTO user_level (id, name) VALUES(1, 'admin'),(2, 'superuser'),(3, 'user');

CREATE TABLE IF NOT EXISTS user (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,		-- user-id
	email VARCHAR(255) NOT NULL,						-- email address
	name VARCHAR(100) NOT NULL,							-- username
	password VARCHAR(100) NOT NULL,						-- hash of a password
	level INTEGER NOT NULL,								-- user-level
	settings VARCHAR(255),								-- user settings (JSON)
	FOREIGN KEY (level) REFERENCES user_level(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS town (
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,		-- town-id
	name VARCHAR(255) NOT NULL,							-- town-name
	last_update DATETIME NOT NULL						-- when was the last update
);

CREATE TABLE IF NOT EXISTS user_town (
	user_id INTEGER NOT NULL,							-- reference to user.id
	town_id INTEGER NOT NULL,							-- reference to town.id
	position INTEGER NOT NULL,							-- position on the user view
	FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
	FOREIGN KEY (town_id) REFERENCES town(id) ON DELETE CASCADE
);
