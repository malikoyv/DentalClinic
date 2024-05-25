CREATE DATABASE clinic;

USE clinic;

CREATE TABLE patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'patient' NOT NULL
);

CREATE TABLE dentists (
    dentist_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(40) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    role VARCHAR(20) DEFAULT 'dentist' NOT NULL
);

CREATE TABLE availability (
    availability_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    dentist_id INT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT 'Badanie',
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    price DECIMAL DEFAULT 0.0 NULL,
    FOREIGN KEY (dentist_id) REFERENCES dentists(dentist_id)
);

CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    patient_id INT NOT NULL,
    dentist_id INT NOT NULL,
    name VARCHAR(50) NOT NULL DEFAULT 'Badanie',
    appointment_date DATETIME NOT NULL,
    status VARCHAR(30) DEFAULT 'scheduled',
    price DECIMAL DEFAULT 0.0 NULL,
    notes TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id),
    FOREIGN KEY (dentist_id) REFERENCES dentists(dentist_id)
);