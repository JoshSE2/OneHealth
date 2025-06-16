-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2025 at 07:00 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onehealth_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booked_appointments`
--

CREATE TABLE `booked_appointments` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','approved','cancelled') DEFAULT 'pending',
  `doctor_email` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `patient_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked_appointments`
--

INSERT INTO `booked_appointments` (`id`, `patient_name`, `phone`, `appointment_date`, `appointment_time`, `status`, `doctor_email`, `message`, `patient_email`) VALUES
(17, 'Saidi Myekano', '7725448060', '2025-05-18', '12:30:00', 'approved', 'camille.smith@onehealth.com', 'Headache', 'smyekano@onehealth.com');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `doctor_email` varchar(255) NOT NULL,
  `doctor_password` varchar(255) NOT NULL,
  `doctor_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `doctor_name`, `doctor_email`, `doctor_password`, `doctor_phone`) VALUES
(1, 'Dr. Shirley Taylor', 'shirley.taylor@onehealth.com', 'Shirley123', '+44 7712348067'),
(2, 'Dr. Camille Smith', 'camille.smith@onehealth.com', 'Camille123', '+44 7725441234'),
(3, 'Dr. Finley Decaen', 'doctor@onehealth.com', 'doc123', '+44 7725454321'),
(4, 'Dr. Adrian Myles', 'adrian.myles@onehealth.com', 'Adrian123', '+44 7725441327');

-- --------------------------------------------------------

--
-- Table structure for table `heart_rate`
--

CREATE TABLE `heart_rate` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `min_rate` int(11) NOT NULL,
  `max_rate` int(11) NOT NULL,
  `advice` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heart_rate`
--

INSERT INTO `heart_rate` (`id`, `category`, `min_rate`, `max_rate`, `advice`) VALUES
(1, 'extremely low', 0, 30, 'Your heart rate is extremely low. Please consult a healthcare expert immediately and seek emergency care.'),
(2, 'very low', 31, 59, 'Your heart rate is low. This could be normal for some individuals, especially athletes. However, if you feel faint or dizzy, please consult a healthcare provider. Regular check-ups are recommended.'),
(3, 'low', 60, 69, 'Your heart rate is on the lower side of normal. This can be a sign of good cardiovascular fitness. Maintain a balanced diet and regular exercise to keep your heart healthy.'),
(4, 'slightly low', 70, 79, 'Your heart rate is slightly below the typical average but still within normal range. Continue with regular physical activity and a heart-healthy diet.'),
(5, 'normal', 80, 99, 'Your heart rate is within the normal range. Keep up the good work! Regular exercise, balanced nutrition, and adequate sleep will help maintain your cardiovascular health.'),
(6, 'slightly high', 100, 119, 'Your heart rate is slightly elevated. This could be due to recent activity, stress, caffeine, or dehydration. Take a moment to relax, breathe deeply, and hydrate. If it remains elevated at rest, consider consulting a healthcare provider.'),
(7, 'high', 120, 139, 'Your heart rate is high. This could be due to exercise, stress, anxiety, or certain medications. If you are experiencing this at rest, try relaxation techniques and consider scheduling a check-up with your doctor.'),
(8, 'very high', 140, 159, 'Your heart rate is very high. Unless you have just completed intense exercise, this could indicate anxiety, dehydration, or potential heart issues. If this persists, please seek medical advice promptly.'),
(9, 'extremely high', 160, 220, 'Your heart rate is extremely high. If you are not engaged in vigorous exercise, this could be a sign of a serious medical condition. Please consult a healthcare provider immediately and consider emergency care if accompanied by chest pain, shortness of breath, or dizziness.');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `patient_password` varchar(255) NOT NULL,
  `patient_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(6) UNSIGNED NOT NULL,
  `symptom_name` varchar(100) NOT NULL,
  `solution` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `symptom_name`, `solution`) VALUES
(1, 'headache', 'Stay hydrated, rest in a dark room, and take pain relief if needed.'),
(2, 'fever', 'Drink fluids, rest, and use a cool compress to lower temperature.'),
(3, 'cough', 'Try honey, drink warm fluids, and avoid smoking.'),
(4, 'sore-throat', 'Gargle warm salt water, drink herbal tea, and rest your voice.'),
(5, 'fatigue', 'Get enough sleep, eat a balanced diet, and reduce stress.'),
(6, 'body-ache', 'Apply heat, take pain relievers, and rest properly.'),
(7, 'dizziness', 'Sit or lie down, stay hydrated, and avoid sudden movements.'),
(8, 'nausea', 'Eat small, bland meals. Ginger tea or peppermint can help. Avoid strong smells.'),
(9, 'chest-pain', 'Rest and avoid exertion. If severe or persistent, seek medical attention immediately.'),
(10, 'shortness-of-breath', 'Sit upright, try to relax, and breathe slowly. If severe, seek medical help.'),
(11, 'abdominal-pain', 'Apply heat to the area, rest, and avoid heavy meals. If severe or persistent, consult a doctor.'),
(12, 'diarrhea', 'Stay hydrated with clear fluids. Avoid dairy and high-fiber foods until symptoms improve.'),
(13, 'constipation', 'Increase fiber intake, drink plenty of water, and consider light exercise. If persistent, consult a doctor.'),
(14, 'skin-rash', 'Avoid scratching, keep the area clean, and apply a cool compress. If severe or spreading, seek medical advice.'),
(15, 'allergic-reaction', 'Identify and avoid the allergen. Antihistamines can help. If severe, seek emergency care.'),
(16, 'fatigue', 'Get enough sleep, eat a balanced diet, and reduce stress.'),
(17, 'vomiting', 'Stop eating solid foods temporarily. Sip clear liquids slowly. When feeling better, try bland foods. If severe, persistent, or blood is present, seek medical attention.'),
(18, 'muscle-pain', 'Rest the affected area, apply ice, and take over-the-counter pain relievers. If severe or persistent, consult a doctor.'),
(19, 'joint-pain', 'Rest, apply ice, and consider over-the-counter anti-inflammatory medications. If persistent, consult a doctor.'),
(20, 'swelling', 'Elevate the affected area, apply ice, and consider over-the-counter anti-inflammatory medications. If persistent or severe, consult a doctor.');

-- --------------------------------------------------------

--
-- Table structure for table `symptom_history`
--

CREATE TABLE `symptom_history` (
  `id` int(6) UNSIGNED NOT NULL,
  `fullname` text NOT NULL,
  `symptoms` text NOT NULL,
  `solution` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptom_history`
--

INSERT INTO `symptom_history` (`id`, `fullname`, `symptoms`, `solution`, `created_at`) VALUES
(1, 'Jack Mic', 'joint-pain, abdominal-pain', '<strong>abdominal-pain:</strong> Apply heat to the area, rest, and avoid heavy meals. If severe or persistent, consult a doctor.<br><br><strong>joint-pain:</strong> Rest, apply ice, and consider over-the-counter anti-inflammatory medications. If persistent, consult a doctor.', '2025-04-14 07:23:18'),
(2, 'Jack Mic', 'chest-pain', '<strong>chest-pain:</strong> Rest and avoid exertion. If severe or persistent, seek medical attention immediately.', '2025-04-14 07:26:51'),
(3, 'Jack Mic', 'abdominal-pain, headache, sore-throat', '<strong>headache:</strong> Stay hydrated, rest in a dark room, and take pain relief if needed.<br><br><strong>sore-throat:</strong> Gargle warm salt water, drink herbal tea, and rest your voice.<br><br><strong>abdominal-pain:</strong> Apply heat to the area, rest, and avoid heavy meals. If severe or persistent, consult a doctor.', '2025-04-14 07:30:09'),
(4, 'Saidi Myekano', 'sore-throat, dizziness, fever', '<strong>fever:</strong> Drink fluids, rest, and use a cool compress to lower temperature.<br><br><strong>sore-throat:</strong> Gargle warm salt water, drink herbal tea, and rest your voice.<br><br><strong>dizziness:</strong> Sit or lie down, stay hydrated, and avoid sudden movements.', '2025-04-21 19:26:02'),
(5, 'Saidi Myekano', 'abdominal-pain, chest-pain, dizziness', '<strong>dizziness:</strong> Sit or lie down, stay hydrated, and avoid sudden movements.<br><br><strong>chest-pain:</strong> Rest and avoid exertion. If severe or persistent, seek medical attention immediately.<br><br><strong>abdominal-pain:</strong> Apply heat to the area, rest, and avoid heavy meals. If severe or persistent, consult a doctor.', '2025-05-01 11:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `role` enum('admin','doctor','patient') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phoneNumber`, `role`) VALUES
(1, 'Admin Test', 'admin@onehealth.com', '$2y$10$QhAaIfpn.jeiXZhDIPxDNORYWGXKC3TST3x6fRJUulxe9ZgixF3YK', '+44 7012348067', 'admin'),
(2, 'Dr. Shirley Taylor', 'shirley.taylor@onehealth.com', '$2y$10$K7HyCrbfnykn27F5jkUuou1PGcddIMwfWd.L9KAYzg19XBrI5ISse', '+44 7712348067', 'doctor'),
(3, 'Dr. Camille Smith', 'camille.smith@onehealth.com', '$2y$10$mDxn/17q2xfnBpxvyaP8COuY1cjhBZE4RwBkU4Gd5jFDfwZ27g1ji', '+44 7725441234', 'doctor'),
(5, 'Dr. Adrian Myles', 'adrian.myles@onehealth.com', '$2y$10$h5fs7xG7Amw9NYTSD8cVgOJDLyF6tfUKWmekocvWtEfmSuQFjKtru', '+44 7725441327', 'doctor'),
(11, 'Dr. Finley Decaen', 'finley@onehealth.com', 'doc123', '+44 7725454321', 'doctor'),
(12, 'Jack Mic', 'jack@onehealth.com', '$2y$10$5O1Vdlua0X8Oh4xapiuxt.CSYmbM.j4vHIrJDH8M13oZf.5kJ5eIK', '7725448011', 'patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booked_appointments`
--
ALTER TABLE `booked_appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctor_email` (`doctor_email`);

--
-- Indexes for table `heart_rate`
--
ALTER TABLE `heart_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_email` (`patient_email`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `symptom_history`
--
ALTER TABLE `symptom_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booked_appointments`
--
ALTER TABLE `booked_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `heart_rate`
--
ALTER TABLE `heart_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `symptom_history`
--
ALTER TABLE `symptom_history`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
