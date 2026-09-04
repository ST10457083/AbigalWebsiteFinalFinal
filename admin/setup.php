<?php
// Run this script once to create your admin user
require '../includes/db.php';

$email = 'admin@example.com';
$password = 'admin123'; // Change this to something secure!
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $conn = get_db_connection();
    
    // Check if admin already exists
    $check = $conn->query("SELECT id FROM admins WHERE email = '$email'");
    if ($check->num_rows > 0) {
        echo "Admin user already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO admins (email, password_hash) VALUES (?, ?)");
        $stmt->bind_param('ss', $email, $password_hash);
        $stmt->execute();
        echo "Admin user created successfully!<br>";
        echo "Email: $email<br>";
        echo "Password: $password<br>";
        echo "<strong>IMPORTANT: Change this password immediately after logging in!</strong>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>