CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE photos (
	photo_id INT AUTO_INCREMENT PRIMARY KEY,
	photo_name TEXT,
	username VARCHAR(255),
	description VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

-- new tables 
CREATE TABLE albums (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    album_name VARCHAR(255),
    username VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE album_photos (
    album_photo_id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT,
    photo_id INT,
    FOREIGN KEY (album_id) REFERENCES albums(album_id) ON DELETE CASCADE,
    FOREIGN KEY (photo_id) REFERENCES photos(photo_id) ON DELETE CASCADE
);

ALTER TABLE photos ADD COLUMN album_id INT;
