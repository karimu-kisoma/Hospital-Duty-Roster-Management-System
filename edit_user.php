<?php
session_start();
include 'config.php';

// ADMIN ONLY
if(!isset($_SESSION['staff_id']) || $_SESSION['role'] != 'Admin'){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

// GET USER
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    die("User not found");
}

// UPDATE
if(isset($_POST['update'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $update = $conn->prepare("
        UPDATE staff 
        SET full_name=?, email=?, role=?, status=? 
        WHERE staff_id=?
    ");

    $update->bind_param("ssssi", $fullname, $email, $role, $status, $id);

    if($update->execute()){
        header("Location: user_register.php");
        exit();
    } else {
        $error = "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins', sans-serif;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#06b6d4);
}

/* BIG HEAD */
.big-head{
    position:absolute;
    top:40px;
    text-align:center;
    width:100%;
    color:white;
}

.big-head h1{
    font-size:45px;
    font-weight:700;
}

.big-head p{
    opacity:0.9;
}

/* CARD */
.edit-card{
    width:100%;
    max-width:450px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(15px);
    padding:40px;
    border-radius:25px;
    box-shadow:0 10px 40px rgba(0,0,0,0.35);
    color:white;
    z-index:2;
}

/* INPUTS */
.form-control, select{
    height:50px;
    border-radius:12px;
    border:none;
    margin-bottom:15px;
}

/* BUTTON */
.btn-update{
    background:linear-gradient(135deg,#06b6d4,#2563eb);
    color:white;
    font-weight:600;
    border:none;
    height:50px;
    border-radius:12px;
}

.btn-update:hover{
    transform:translateY(-2px);
}

/* CANCEL */
.btn-cancel{
    background:#dc2626;
    color:white;
    border:none;
    height:50px;
    border-radius:12px;
}

h3{
    text-align:center;
    margin-bottom:25px;
    font-weight:600;
}

.error{
    background:rgba(255,0,0,0.2);
    padding:10px;
    border-radius:10px;
    text-align:center;
    margin-bottom:10px;
}

</style>
</head>

<body>

<!-- BIG HEADER -->
<div class="big-head">
    <h1>
        <i class="bi bi-pencil-square"></i>
        EDIT USER
    </h1>
    <p>Update Staff Information</p>
</div>

<!-- CARD -->
<div class="edit-card">

    <h3>
        <i class="bi bi-person-gear"></i>
        Modify User
    </h3>

    <?php if(isset($error)){ ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="fullname" class="form-control"
               value="<?= $user['full_name'] ?>" required>

        <input type="email" name="email" class="form-control"
               value="<?= $user['email'] ?>" required>

        <select name="role" class="form-control">

            <option <?= $user['role']=="Admin"?"selected":"" ?>>Admin</option>
            <option <?= $user['role']=="Doctor"?"selected":"" ?>>Doctor</option>
            <option <?= $user['role']=="Nurse"?"selected":"" ?>>Nurse</option>
            <option <?= $user['role']=="Technician"?"selected":"" ?>>Technician</option>

        </select>

        <select name="status" class="form-control">

            <option <?= $user['status']=="Active"?"selected":"" ?>>Active</option>
            <option <?= $user['status']=="Inactive"?"selected":"" ?>>Inactive</option>

        </select>

        <button type="submit" name="update" class="btn-update w-100 mb-2">
            Update User
        </button>

        <a href="user_register.php" class="btn-cancel w-100 d-flex align-items-center justify-content-center">
            Cancel
        </a>

    </form>

</div>

</body>
</html>