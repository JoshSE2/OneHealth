

CREATE TABLE symptoms (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    symptom_name VARCHAR(100) NOT NULL,
    solution TEXT NOT NULL
);


CREATE TABLE symptom_history (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname TEXT NOT NULL,
    symptoms TEXT NOT NULL,
    solution TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO symptoms (symptom_name, solution) VALUES
('headache', 'Stay hydrated, rest in a dark room, and take pain relief if needed.'),
('fever', 'Drink fluids, rest, and use a cool compress to lower temperature.'),
('cough', 'Try honey, drink warm fluids, and avoid smoking.'),
('sore-throat', 'Gargle warm salt water, drink herbal tea, and rest your voice.'),
('fatigue', 'Get enough sleep, eat a balanced diet, and reduce stress.'),
('body-ache', 'Apply heat, take pain relievers, and rest properly.'),
('dizziness', 'Sit or lie down, stay hydrated, and avoid sudden movements.'),
('nausea', 'Eat small, bland meals. Ginger tea or peppermint can help. Avoid strong smells.'),
('chest-pain', 'Rest and avoid exertion. If severe or persistent, seek medical attention immediately.'),
('shortness-of-breath', 'Sit upright, try to relax, and breathe slowly. If severe, seek medical help.'),
('abdominal-pain', 'Apply heat to the area, rest, and avoid heavy meals. If severe or persistent, consult a doctor.'),
('diarrhea', 'Stay hydrated with clear fluids. Avoid dairy and high-fiber foods until symptoms improve.'),
('constipation', 'Increase fiber intake, drink plenty of water, and consider light exercise. If persistent, consult a doctor.'),
('skin-rash', 'Avoid scratching, keep the area clean, and apply a cool compress. If severe or spreading, seek medical advice.'),
('allergic-reaction', 'Identify and avoid the allergen. Antihistamines can help. If severe, seek emergency care.'),
('fatigue', 'Get enough sleep, eat a balanced diet, and reduce stress.'),
('vomiting', 'Stop eating solid foods temporarily. Sip clear liquids slowly. When feeling better, try bland foods. If severe, persistent, or blood is present, seek medical attention.'),
('muscle-pain', 'Rest the affected area, apply ice, and take over-the-counter pain relievers. If severe or persistent, consult a doctor.'),
('joint-pain', 'Rest, apply ice, and consider over-the-counter anti-inflammatory medications. If persistent, consult a doctor.'),
('swelling', 'Elevate the affected area, apply ice, and consider over-the-counter anti-inflammatory medications. If persistent or severe, consult a doctor.');