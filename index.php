<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {

        $stmt = $conn->prepare("SELECT staff_id, full_name, role, password FROM staff WHERE email = ? LIMIT 1");

        if ($stmt) {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $user = $result->fetch_assoc();

                /*
                 * OPTION 1 (Recommended)
                 * Use password_hash() in database
                 */
                if (password_verify($password, $user['password'])) {

                    session_regenerate_id(true);

                    $_SESSION['staff_id'] = $user['staff_id'];
                    $_SESSION['name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: dashboard.php");
                    exit();
                }

                /*
                 * OPTION 2 (Temporary)
                 * If your database still stores plain text passwords,
                 * uncomment below and comment out password_verify()
                 */

                /*
                if ($password === $user['password']) {

                    session_regenerate_id(true);

                    $_SESSION['staff_id'] = $user['staff_id'];
                    $_SESSION['name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: dashboard.php");
                    exit();
                }
                */

                $error = "Invalid Email or Password.";

            } else {
                $error = "Invalid Email or Password.";
            }

            $stmt->close();

        } else {
            $error = "Database error. Please try again.";
        }
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
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            overflow:hidden;
            position:relative;
            background:url('p.avif') no-repeat center center/cover;
        }

        /* DARK OVERLAY */
        body::before{
            content:'';
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.60);
            backdrop-filter:blur(5px);
        }

        /* BIG HEADER */
        .big-head{
            position:absolute;
            top:40px;
            width:100%;
            text-align:center;
            color:white;
            z-index:2;
        }

        .big-head h1{
            font-size:55px;
            font-weight:700;
            letter-spacing:2px;
            text-shadow:0 5px 20px rgba(0,0,0,0.5);
        }

        .big-head p{
            margin-top:10px;
            font-size:20px;
            opacity:0.9;
        }

        /* LOGIN BOX */
        .login-container{
            position:relative;
            z-index:2;
            width:420px;
            padding:45px;
            border-radius:30px;
            background:rgba(255,255,255,0.12);
            backdrop-filter:blur(15px);
            box-shadow:0 10px 40px rgba(0,0,0,0.35);
            border:1px solid rgba(255,255,255,0.15);
            color:white;
        }

        .login-container h2{
            text-align:center;
            margin-bottom:35px;
            font-size:35px;
            font-weight:600;
        }

        /* INPUT */
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

        .input-group{
            position:relative;
        }

        .input-group i{
            position:absolute;
            top:18px;
            left:18px;
            z-index:10;
            color:#2563eb;
            font-size:18px;
        }

        /* BUTTON */
        .btn-login{
            width:100%;
            height:55px;
            border:none;
            border-radius:15px;
            background:linear-gradient(135deg, #06b6d4, #2563eb);
            color:white;
            font-size:18px;
            font-weight:600;
            transition:0.3s;
        }

        .btn-login:hover{
            transform:translateY(-3px);
            background:linear-gradient(135deg, #2563eb, #06b6d4);
            box-shadow:0 8px 20px rgba(37,99,235,0.4);
        }

        /* ERROR */
        .alert{
            border-radius:15px;
            font-size:15px;
            font-weight:500;
        }

        /* FOOTER */
        .footer{
            position:absolute;
            bottom:20px;
            color:white;
            z-index:2;
            font-size:14px;
            opacity:0.8;
        }

        @media(max-width:768px){

            .big-head h1{
                font-size:34px;
            }

            .big-head p{
                font-size:16px;
            }

            .login-container{
                width:90%;
                padding:30px;
            }

            .login-container h2{
                font-size:28px;
            }
        }

    </style>
</head>
<body>

    <!-- BIG HEADER -->
    <div class="big-head">
        <h1>
            <i class="bi bi-hospital-fill"></i>
            HOSPITAL DUTY ROSTER SYSTEM
        </h1>

        <p>Staff Duty Management Platform</p>
    </div>

    <!-- LOGIN BOX -->
    <div class="login-container">

        <h2>
            <i class="bi bi-shield-lock-fill"></i>
            Admin Login
        </h2>

        <?php if(isset($error)){ ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <!-- EMAIL -->
            <div class="mb-4 input-group">
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
            <div class="mb-4 input-group">
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

    </div>

    <!-- FOOTER -->
    <div class="footer">
        © <?php echo date("Y"); ?> Hospital Duty Roster System | All Rights Reserved
    </div>

</body>
</html>