CREATE DATABASE tweet_academy;

USE tweet_academy;

CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    mail VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    avatar VARCHAR(2000) DEFAULT NULL,
    banner VARCHAR(2000) DEFAULT NULL,
    bio VARCHAR(160) DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    preferences INT
);

CREATE TABLE IF NOT EXISTS preferences (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) references user(id),
    darkmode ENUM('white', 'dark', 'auto'),
    lang VARCHAR(255)
);

ALTER TABLE user ADD FOREIGN KEY (preferences) REFERENCES preferences(id);

CREATE TABLE IF NOT EXISTS tweets (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_user INT NOT NULL,
    message VARCHAR(140),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    parent INT,
    FOREIGN KEY (parent) REFERENCES tweets(id)
);

CREATE TABLE IF NOT EXISTS image (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_tweet INT NOT NULL,
    FOREIGN KEY (id_tweet) REFERENCES tweets(id),
    url varchar(2000) NOT NULL
);

CREATE TABLE IF NOT EXISTS impression (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_tweet INT NOT NULL,
    FOREIGN KEY (id_tweet) REFERENCES tweets(id),
    id_user INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES tweets(id),
    date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    type ENUM ('like', 'retweet') NOT NULL
);

CREATE TABLE IF NOT EXISTS follow (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_follower INT NOT NULL,
    FOREIGN KEY (id_follower) REFERENCES user(id),
    id_following INT NOT NULL,
    FOREIGN KEY (id_following) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS private_message (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    sender INT NOT NULL,
    FOREIGN KEY (sender) REFERENCES user(id),
    receiver INT NOT NULL,
    Foreign Key (receiver) REFERENCES user(id),
    message VARCHAR(10000) NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    reaction VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS hashtag (
    hashtag VARCHAR(140) PRIMARY KEY NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    occurences INT NOT NULL,
    weekly_occurences INT NOT NULL
);

CREATE TABLE IF NOT EXISTS hashtag_relation(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    tweet_id INT NOT NULL,
    Foreign Key (tweet_id) REFERENCES tweets(id),
    hashtag_id VARCHAR(140) NOT NULL,
    Foreign Key (hashtag_id) REFERENCES hashtag(hashtag)
);