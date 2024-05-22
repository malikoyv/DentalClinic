CREATE DATABASE clinic;

USE clinic;

CREATE TABLE patient (
    patient_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(10) DEFAULT 'patient'
);

CREATE TABLE dentist (
    dentist_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    role VARCHAR(10) DEFAULT 'dentist'
);

CREATE TABLE availability (
    availability_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    dentist_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    FOREIGN KEY (dentist_id) REFERENCES dentist(dentist_id)
);

CREATE TABLE appointment (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    patient_id INT NOT NULL,
    dentist_id INT NOT NULL,
    appointment_date DATETIME NOT NULL,
    status VARCHAR(20) DEFAULT 'scheduled',
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id),
    FOREIGN KEY (dentist_id) REFERENCES dentist(dentist_id)
);