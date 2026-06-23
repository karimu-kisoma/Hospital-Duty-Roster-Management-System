<?php
session_start();
include 'config.php';

$error = "";
$success = "";

if(isset($_POST['register'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // check email exists
    $check = $conn->prepare("SELECT id FROM staff WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $error = "Email already exists!";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $role = "user";

        $stmt = $conn->prepare("
            INSERT INTO users(fullname,email,password,role)
            VALUES(?,?,?,?)
        ");

        $stmt->bind_param("ssss", $fullname, $email, $hashed, $role);

        if($stmt->execute()){
            $success = "Account created successfully!";
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hospital Duty Roster Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    overflow:hidden;
    position:relative;
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#06b6d4);
}

/* SAME BACKGROUND EFFECT */
body::before{
    content:'';
    position:absolute;
    width:600px;
    height:600px;
    background:rgba(255,255,255,0.08);
    border-radius:50%;
    top:-200px;
    left:-150px;
    filter:blur(30px);
}

body::after{
    content:'';
    position:absolute;
    width:500px;
    height:500px;
    background:rgba(255,255,255,0.06);
    border-radius:50%;
    bottom:-180px;
    right:-120px;
    filter:blur(30px);
}

/* BIG HEAD */
.big-head{
    position:absolute;
    top:40px;
    text-align:center;
    width:100%;
    color:white;
    z-index:2;
}

.big-head h1{
    font-size:55px;
    font-weight:700;
    letter-spacing:2px;
    text-shadow:0 5px 20px rgba(0,0,0,0.4);
}

.big-head p{
    margin-top:10px;
    font-size:20px;
    opacity:0.9;
}

/* CARD */
.register-card{
    position:relative;
    z-index:2;
    width:100%;
    max-width:430px;
    padding:45px;
    border-radius:30px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(15px);
    border:1px solid rgba(255,255,255,0.15);
    box-shadow:0 10px 40px rgba(0,0,0,0.35);
    color:white;
}

/* TITLE */
.register-card h2{
    text-align:center;
    margin-bottom:35px;
    font-size:34px;
    font-weight:600;
}

/* INPUT */
.input-group-custom{
    position:relative;
    margin-bottom:25px;
}

.input-group-custom i{
    position:absolute;
    top:18px;
    left:18px;
    color:#2563eb;
    font-size:18px;
}

.form-control{
    height:55px;
    border:none;
    border-radius:15px;
    padding-left:50px;
    background:rgba(255,255,255,0.95);
    font-size:15px;
}

.form-control:focus{
    box-shadow:none;
    border:2px solid #38bdf8;
}

/* BUTTON */
.btn-register{
    width:100%;
    height:55px;
    border:none;
    border-radius:15px;
    background:linear-gradient(135deg,#06b6d4,#2563eb);
    color:white;
    font-size:18px;
    font-weight:600;
    transition:0.3s;
}

.btn-register:hover{
    transform:translateY(-3px);
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    box-shadow:0 8px 20px rgba(37,99,235,0.4);
}

/* MESSAGES */
.error-box{
    background:rgba(255,0,0,0.15);
    border:1px solid rgba(255,0,0,0.3);
    color:#ffb3b3;
    padding:12px;
    border-radius:12px;
    text-align:center;
    margin-bottom:15px;
}

.success-box{
    background:rgba(0,255,0,0.15);
    border:1px solid rgba(0,255,0,0.3);
    color:#b7ffb7;
    padding:12px;
    border-radius:12px;
    text-align:center;
    margin-bottom:15px;
}

/* LOGIN LINK */
.login-text{
    margin-top:20px;
    text-align:center;
    color:white;
}

.login-text a{
    color:#ffd166;
    text-decoration:none;
    font-weight:600;
}

.login-text a:hover{
    text-decoration:underline;
}

</style>
</head>

<body>

<!-- BIG HEAD -->
<div class="big-head">

    <h1>
        <i class="bi bi-hospital-fill"></i>
        HOSPITAL DUTY ROSTER SYSTEM
    </h1>

    <p>Create New Account</p>

</div>

<!-- REGISTER CARD -->
<div class="register-card">

    <h2>
        <i class="bi bi-person-plus-fill"></i>
        User Register
    </h2>

    <?php if($error != ""){ ?>
        <div class="error-box"><?= $error ?></div>
    <?php } ?>

    <?php if($success != ""){ ?>
        <div class="success-box"><?= $success ?></div>
    <?php } ?>

    <form method="POST">

        <div class="input-group-custom">
            <i class="bi bi-person-fill"></i>
            <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
        </div>

        <div class="input-group-custom">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="input-group-custom">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" name="register" class="btn-register">
            <i class="bi bi-person-check-fill"></i>
            Register
        </button>

    </form>

    <div class="login-text">
        Already have account?
        <a href="user_index.php">Login</a>
    </div>

</div>

</body>
</html>