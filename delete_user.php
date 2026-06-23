<?php
session_start();
include 'config.php';

// ADMIN ONLY
if(!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'Admin'){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM staff WHERE staff_id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    header("Location: user_register.php");
    exit();
} else {
    echo "Failed to delete user";
}
?>