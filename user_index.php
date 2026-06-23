<?php
session_start();
include 'config.php';

$error = "";

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM staff WHERE email='$email'";
    $result = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password,$user['password'])){

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['fullname'];

        if($user['role'] == "admin"){
            header("Location: admin_dashboard.php");
            exit();
        }else{
            header("Location: view_roster.php");
            exit();
        }

    }else{
        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hospital Duty Roster Login</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Google Font -->
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

/* ANIMATED BACKGROUND */
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

/* LOGIN CARD */
.login-card{
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
.login-card h2{
    text-align:center;
    margin-bottom:35px;
    font-size:34px;
    font-weight:600;
}

/* INPUT GROUP */
.input-group-custom{
    position:relative;
    margin-bottom:25px;
}

.input-group-custom i{
    position:absolute;
    top:18px;
    left:18px;
    color:#2563eb;
    z-index:10;
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
.btn-login{
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

.btn-login:hover{
    transform:translateY(-3px);
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    box-shadow:0 8px 20px rgba(37,99,235,0.4);
}

/* ERROR */
.error-box{
    background:rgba(255,0,0,0.15);
    border:1px solid rgba(255,0,0,0.3);
    color:#ffb3b3;
    padding:12px;
    border-radius:12px;
    text-align:center;
    margin-bottom:20px;
    font-size:14px;
}

/* REGISTER */
.register-text{
    margin-top:25px;
    text-align:center;
    color:white;
    font-size:14px;
}

.register-text a{
    color:#ffd166;
    text-decoration:none;
    font-weight:600;
}

.register-text a:hover{
    text-decoration:underline;
}

/* FOOTER */
.footer{
    position:absolute;
    bottom:20px;
    color:white;
    font-size:14px;
    opacity:0.8;
    z-index:2;
}

/* MOBILE */
@media(max-width:768px){

    .big-head h1{
        font-size:34px;
    }

    .big-head p{
        font-size:16px;
    }

    .login-card{
        width:90%;
        padding:30px;
    }

    .login-card h2{
        font-size:28px;
    }
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

    <p>Duty Management Platform</p>

</div>

<!-- LOGIN CARD -->
<div class="login-card">

    <h2>
        <i class="bi bi-shield-lock-fill"></i>
        User Login
    </h2>

    <?php if($error != ""){ ?>
        <div class="error-box">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <!-- EMAIL -->
        <div class="input-group-custom">

            <i class="bi bi-envelope-fill"></i>

            <input 
                type="email"
                name="email"
                class="form-control"
                placeholder="Enter Email Address"
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="input-group-custom">

            <i class="bi bi-lock-fill"></i>

            <input 
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter Password"
                required
            >

        </div>

        <!-- BUTTON -->
        <button type="submit" name="login" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            Login
        </button>

    </form>

    <!-- REGISTER -->
    <div class="register-text">
        Don't have an account?
        <a href="register.php">Register Here</a>
    </div>

</div>

<!-- FOOTER -->
<div class="footer">
    © <?php echo date("Y"); ?> Hospital Duty Roster System | All Rights Reserved
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>