<?php

CREATE TABLE account (
    AccountID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255),
    Email VARCHAR(255),
    PhoneNumber INT,
    AccPassword VARCHAR(255)
);

CREATE TABLE patientgeneralinformation (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    MiddleInitial CHAR(1),
    BirthDate DATE,
    Age INT,
    Gender VARCHAR(255),
    Email VARCHAR(255),
    PhoneNumber VARCHAR(255)
);

CREATE TABLE patient (
    PatientID INT;
    EmergencyContactName VARCHAR(255),
    EmergencyContactPhone VARCHAR(255),
    BookingType ENUM ('Site', 'Manual') DEFAULT 'Site',
    Department VARCHAR(255),
    Procedures VARCHAR(255),
    AppointmentDT DATE,
    AppointmentTime VARCHAR(255),
    Status ENUM ('Pending', 'Completed', 'In-Session', 'Cancelled') DEFAULT 'Pending',
    Payment ENUM ('Pending', 'Paid') DEFAULT 'Pending',
    Final ENUM ('NO', 'YES') DEFAULT 'NO',
    Amount INT,
    FOREIGN KEY (PatientID) REFERENCES patientgeneralinformation (PatientID) ON DELETE CASCADE
);


?>