<?php
include 'config.php';

$message = "";

if(isset($_POST['add'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $dept = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO staff(full_name,email,role,department_id,password) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssis",$name,$email,$role,$dept,$password);

    if($stmt->execute()){
        $message = "Staff added successfully!";
    } else {
        $message = "Failed to add staff!";
    }
}

$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>

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
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #06b6d4);
            min-height:100vh;
        }

        /* BIG HEADER */
        .hero-header{
            text-align:center;
            padding:50px 20px;
            color:white;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border-bottom:1px solid rgba(255,255,255,0.1);
            box-shadow:0 8px 25px rgba(0,0,0,0.2);
        }

        .hero-header h1{
            font-size:50px;
            font-weight:700;
        }

        .hero-header p{
            margin-top:10px;
            font-size:18px;
            opacity:0.9;
        }

        /* FORM CARD */
        .form-container{
            max-width:700px;
            margin:50px auto;
            padding:40px;
            border-radius:25px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            box-shadow:0 10px 35px rgba(0,0,0,0.25);
            color:white;
        }

        .form-title{
            text-align:center;
            margin-bottom:30px;
            font-weight:600;
            font-size:35px;
        }

        .form-control,
        .form-select{
            height:55px;
            border:none;
            border-radius:15px;
            padding-left:15px;
            background: rgba(255,255,255,0.95);
        }

        .form-control:focus,
        .form-select:focus{
            box-shadow:none;
            border:2px solid #38bdf8;
        }

        label{
            margin-bottom:8px;
            font-weight:500;
        }

        /* BUTTON */
        .btn-custom{
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            border:none;
            height:55px;
            border-radius:15px;
            font-size:18px;
            font-weight:600;
            transition:0.3s;
            color:white;
        }

        .btn-custom:hover{
            transform:translateY(-3px);
            background: linear-gradient(135deg, #2563eb, #06b6d4);
        }

        /* BACK BUTTON */
        .back-btn{
            display:inline-block;
            margin-top:25px;
            text-decoration:none;
            color:white;
            font-weight:500;
            transition:0.3s;
        }

        .back-btn:hover{
            color:#ffd166;
        }

        /* ALERT */
        .alert{
            border-radius:15px;
            font-weight:500;
        }

        @media(max-width:768px){

            .hero-header h1{
                font-size:35px;
            }

            .form-container{
                margin:20px;
                padding:25px;
            }

            .form-title{
                font-size:28px;
            }
        }

    </style>
</head>
<body>

    <!-- BIG HEAD -->
    <div class="hero-header">
        <h1><i class="bi bi-person-plus-fill"></i> ADD HOSPITAL STAFF</h1>
        <p>Hospital Staff Registration Panel</p>
    </div>

    <!-- FORM -->
    <div class="form-container">

        <h2 class="form-title">Add New Staff</h2>

        <?php if($message != ""){ ?>
            <div class="alert alert-success text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-4">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="mb-4">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
            </div>

            <div class="mb-4">
                <label>Role</label>
                <select name="role" class="form-select">
                    <option>Doctor</option>
                    <option>Nurse</option>
                    <option>Technician</option>
                    <option>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Department</label>
                <select name="department" class="form-select">

                    <?php while($d = $departments->fetch_assoc()){ ?>

                        <option value="<?php echo $d['department_id']; ?>">
                            <?php echo $d['name']; ?>
                        </option>

                    <?php } ?>

                </select>
            </div>

            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create password" required>
            </div>

            <button type="submit" name="add" class="btn btn-custom w-100">
                <i class="bi bi-check-circle-fill"></i> Add Staff
            </button>

        </form>

        <div class="text-center">
            <a href="dashboard.php" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

    </div>

</body>
</html>