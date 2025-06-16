# 🩺 OneHealth – Online Healthcare Assistant Website

## 🌐 Project Overview

**OneHealth Mate** is a comprehensive online healthcare assistant platform developed to reduce unnecessary hospital visits for minor or common symptoms. The system provides **immediate access to medical advice** and allows patients to **book appointments** with doctors when needed.

By combining smart symptom checking tools with streamlined appointment scheduling and role-based user access, OneHealth improves both user experience and healthcare efficiency.

---

## 👤 User Roles & Features

The website supports **three key user roles**, each with unique functionality:

### 🧑‍⚕️ Patient Features

- 📅 Book appointments with doctors  
- 🔍 View appointment status (Approved / Cancelled / Pending)  
- ❤️ Heart Rate Checker – Receive instant health guidance  
- 🩺 Symptom Checker – Get preliminary advice before seeing a doctor  
- 🧾 View symptom check history  
- ✏️ Update personal information  
- 👁️ View profile details  
- ❌ Delete own account  

---

### 👨‍⚕️ Doctor Features

- 📥 View appointments **booked specifically with them**  
- ✅ Approve / ❌ Cancel appointments  
- ✏️ Update and view profile details  
- ❌ Delete own account  

---

### 🛠️ Admin Features

- 👥 View and manage **all user accounts** (Patients & Doctors)  
- 📅 View **all booked appointments** across the system  
- 🔄 Monitor appointment statuses (Pending / Approved / Cancelled)  
- 📝 Edit user details and roles  
- ❌ Delete any user account  
- 🔐 Manage own admin account (Edit, View, Delete)  

---

## 🧰 Tools & Technologies

Developed using **Visual Studio Code** and powered by the following technologies:

- ⚙️ **PHP** – Backend development  
- 🎨 **HTML & CSS** – Web interface design  
- 🧠 **JavaScript** – Client-side interactivity  
- 🗃️ **MySQL** – Database management  
- 🔐 **Access Control** – Role-based page protection and secure sessions  

---

## 🔐 Security Features

- Pages restricted based on user role (Admin, Doctor, Patient)  
- Session handling to protect user data  
- Admins can manage users and appointments system-wide  
- Patients and doctors only access features relevant to them  

---

## 🚀 How to Get Started

Follow the steps below to run the project on your local machine using XAMPP:

### 🛠️ Installation Steps

1. 🔥 Open **XAMPP Control Panel**, and start **Apache** and **MySQL**.
2. 📦 Extract the downloaded source code `.zip` file.
3. 📁 Copy the extracted folder into your XAMPP **`htdocs`** directory.
4. 🌐 Open your browser and go to: `http://localhost/phpmyadmin`
5. 🛠️ Create a new database named: `onehealth_db`
6. 📂 Import the SQL file:  
   - File location: `/Onehealth_db.sql` (inside the source code root folder)
7. ✅ Visit the website on your browser:  
   `http://localhost/Onehealth/`

---

### 🔑 Login Instructions

You can use the following demo accounts to explore the system:

#### 👨‍⚕️ Doctor
- **Email**: `camille.smith@onehealth.com`  
- **Password**: `Camille123`

#### 🧑 Patient
- **Email**: `jack@onehealth.com`  
- **Password**: `Jack123`

#### 🛠️ Admin
- **Email**: `admin@onehealth.com`  
- **Password**: `Admin123`

---

## 💡 Key Highlights

- ⚡ Fast, responsive, and user-friendly interface  
- 🔍 Reduces wait times for common issues  
- 🧠 Provides intelligent, instant health guidance  
- 🔐 Secure, role-based data access and session handling  
- 🧑‍⚕️ Improves doctor-patient communication  

---

## 🧭 Potential Enhancements

> Future features that can be added:

- 🤖 AI-powered symptom analysis  
- 📩 Email / SMS notifications  
- 📱 Fully responsive mobile-first redesign  
- 🌍 Multilingual support  

---

## 👨‍💻 Author

Developed by **[Saidi Myekano]**, Software Engineering Student passionate about programming and creating things on the web.  

---

## 📎 License

This project is intended for educational and demonstration purposes. Contributions and forks are welcome!

---

