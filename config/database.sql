CREATE DATABASE clinic;

USE clinic;

create table patient (
    patient_id int primary key auto_increment not null,
    first_name varchar(50),
    last_name varchar(50),
    email varchar(40) not null,
    password varchar(100) not null,
    role varchar(10)
);

create table dentist (
    dentist_id int primary key auto_increment not null,
    first_name varchar(50),
    last_name varchar(50),
    email varchar(40) not null,
    password varchar(100) not null,
    role varchar(10)
);