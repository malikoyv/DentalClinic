CREATE DATABASE todo;

CREATE TABLE `users` (
  `email` varchar(40) NOT NULL,
  `password` varchar(45) NOT NULL,
  `isAdmin` boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`email`)
);

CREATE TABLE `tasks` (
  `taskid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL,
  `task` varchar(100) NOT NULL,
  `done` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`taskid`),
  CONSTRAINT `email` FOREIGN KEY (`email`) REFERENCES `users` (`email`)
);