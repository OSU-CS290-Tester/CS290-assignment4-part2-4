// Micheal Willard
// Oregon State University
// CS 290-400
// Winter 2015
// Assignment 4 Part 2

CREATE TABLE movieDB(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) UNIQUE NOT NULL,
  category VARCHAR(255),
  length INT,
  rented BOOLEAN NOT NULL DEFAULT 0
)ENGINE=InnoDB;
