
CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_name VARCHAR(255) NOT NULL,
    doctor_email VARCHAR(255) NOT NULL UNIQUE,
    doctor_password VARCHAR(255) NOT NULL,
    doctor_phone VARCHAR(15) NOT NULL
);

INSERT INTO doctors (doctor_name, doctor_email, doctor_password, doctor_phone) VALUES
('Dr. Shirley Taylor', 'shirley.taylor@onehealth.com', 'Shirley123', '+44 7712348067'),
('Dr. Camille Smith', 'camille.smith@onehealth.com', 'Camille123', '+44 7725441234'),
('Dr. Finley Decaen', 'finley.decaen@onehealth.com', 'Finley123', '+44 7725454321'),
('Dr. Adrian Myles', 'adrian.myles@onehealth.com', 'Adrian123', '+44 7725441327');

/*create table for patients*/
CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    patient_email VARCHAR(255) NOT NULL UNIQUE,
    patient_password VARCHAR(255) NOT NULL,
    patient_phone VARCHAR(15) NOT NULL
);