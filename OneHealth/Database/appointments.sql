CREATE DATABASE onehealth_db;

USE onehealth_db;

CREATE TABLE booked_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Pending', 'Approved', 'Cancelled') DEFAULT 'Pending',
    doctor_email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    patient_email VARCHAR(255) NOT NULL,
);



CREATE TABLE heart_rate (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    min_rate INT NOT NULL,
    max_rate INT NOT NULL,
    advice TEXT NOT NULL
);

INSERT INTO heart_rate (category, min_rate, max_rate, advice) VALUES
('extremely low', 0, 30, 'Your heart rate is extremely low. Please consult a healthcare expert immdeiately and seek emergency care.'),
('very low', 31, 59, 'Your heart rate is low. This could be normal for some individuals, especially athletes. However, if you feel faint or dizzy, please consult a healthcare provider. Regular check-ups are recommended.'),
('low', 60, 69, 'Your heart rate is on the lower side of normal. This can be a sign of good cardiovascular fitness. Maintain a balanced diet and regular exercise to keep your heart healthy.'),
('slightly low', 70, 79, 'Your heart rate is slightly below the typical average but still within normal range. Continue with regular physical activity and a heart-healthy diet.'),
('normal', 80, 99, 'Your heart rate is within the normal range. Keep up the good work! Regular exercise, balanced nutrition, and adequate sleep will help maintain your cardiovascular health.'),
('slightly high', 100, 119, 'Your heart rate is slightly elevated. This could be due to recent activity, stress, caffeine, or dehydration. Take a moment to relax, breathe deeply, and hydrate. If it remains elevated at rest, consider consulting a healthcare provider.'),
('high', 120, 139, 'Your heart rate is high. This could be due to exercise, stress, anxiety, or certain medications. If you are experiencing this at rest, try relaxation techniques and consider scheduling a check-up with your doctor.'),
('very high', 140, 159, 'Your heart rate is very high. Unless you have just completed intense exercise, this could indicate anxiety, dehydration, or potential heart issues. If this persists, please seek medical advice promptly.'),
('extremely high', 160, 220, 'Your heart rate is extremely high. If you are not engaged in vigorous exercise, this could be a sign of a serious medical condition. Please consult a healthcare provider immediately and consider emergency care if accompanied by chest pain, shortness of breath, or dizziness.');