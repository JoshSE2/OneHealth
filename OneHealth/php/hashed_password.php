<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'onehealth_db';

try {
    echo "Attempting database connection...<br>";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!<br>";
    
    // Array of user data
    $users = [
        ['admin', 'admin@onehealth.com', 'Admin123', '+44 7012348067', 'admin'],
        ['Dr. Shirley Taylor', 'shirley.taylor@onehealth.com', 'Shirley123', '+44 7712348067', 'doctor'],
        ['Dr. Camille Smith', 'camille.smith@onehealth.com', 'Camille123', '+44 7725441234', 'doctor'],
        ['Dr. Finley Decaen', 'finley.decaen@onehealth.com', 'Finley123', '+44 7725454321', 'doctor'],
        ['Dr. Adrian Myles', 'adrian.myles@onehealth.com', 'Adrian123', '+44 7725441327', 'doctor'],
        ['saidi', 'saidi@onehealth.com', 'Said123', '+44 7012348068', 'patient']
    ];
    
    // Check if users already exist
    $checkStmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $checkStmt->fetchColumn();
    echo "Current user count: $userCount<br>";
    
    // Option 1: Update existing users
    if ($userCount > 0) {
        echo "Updating existing users with hashed passwords...<br>";
        $stmt = $pdo->prepare("SELECT id, password FROM users");
        $stmt->execute();
        $existingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($existingUsers as $user) {
            // Only hash if not already hashed (simple check - not foolproof)
            if (strlen($user['password']) < 30) {
                $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
                $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->execute([$hashed, $user['id']]);
                echo "Updated password for user ID: {$user['id']}<br>";
            } else {
                echo "Password for user ID: {$user['id']} appears to be already hashed<br>";
            }
        }
    } 
    // Option 2: Insert new users if table is empty
    else {
        echo "Inserting new users with hashed passwords...<br>";
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, phoneNumber, role) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($users as $user) {
            $hashedPassword = password_hash($user[2], PASSWORD_DEFAULT);
            $user[2] = $hashedPassword;
            $stmt->execute($user);
            echo "Added user: {$user[0]}<br>";
        }
    }
    
    echo "<br><strong>Operation completed successfully.</strong>";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>