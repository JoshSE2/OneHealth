CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(15) NOT NULL,
    role ENUM('admin', 'doctor', 'patient') NOT NULL
);

INSERT INTO users (fullname, email, password, phoneNumber, role) VALUES
('admin', 'admin@onehealth.com', 'Admin123', '+44 7012348067', 'admin'),
('Dr. Shirley Taylor', 'shirley.taylor@onehealth.com', 'Shirley123', '+44 7712348067', 'doctor'),
('Dr. Camille Smith', 'camille.smith@onehealth.com', 'Camille123', '+44 7725441234', 'doctor'),
('Dr. Finley Decaen', 'finley@onehealth.com', 'doc123', '+44 7725454321', 'doctor'),
('Dr. Adrian Myles', 'adrian.myles@onehealth.com', 'Adrian123', '+44 7725441327', 'doctor'),
('saidi', 'saidi@onehealth.com', 'Said123', '+44 7012348068', 'patient');