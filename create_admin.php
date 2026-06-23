<?php
include 'config.php';

$full_name = "Admin";
$email = "admin@hospital.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "Admin";

// department_id inaweza kuwa NULL kwa admin
$sql = "INSERT INTO staff (full_name, email, role, department_id, password)
VALUES ('$full_name', '$email', '$role', NULL, '$password')";

if ($conn->query($sql)) {
    echo "Admin created successfully";
} else {
    echo "Error: " . $conn->error;
}
?>