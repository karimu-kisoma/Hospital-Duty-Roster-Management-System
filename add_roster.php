<?php
include 'config.php';

$message = "";

if(isset($_POST['assign'])){

    $staff_id = $_POST['staff'];
    $dept_id = $_POST['department'];
    $date = $_POST['duty_date'];
    $shift = $_POST['shift'];

    $stmt = $conn->prepare("INSERT INTO duty_roster(staff_id, department_id, duty_date, shift) VALUES(?,?,?,?)");
    $stmt->bind_param("iiss",$staff_id,$dept_id,$date,$shift);

    if($stmt->execute()){
        $message = "Duty assigned successfully!";
    } else {
        $message = "Failed to assign duty!";
    }
}

$staffs = $conn->query("SELECT * FROM staff WHERE status='Active'");
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Duty</title>

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
            padding:55px 20px;
            color:white;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border-bottom:1px solid rgba(255,255,255,0.1);
            box-shadow:0 8px 25px rgba(0,0,0,0.25);
        }

        .hero-header h1{
            font-size:52px;
            font-weight:700;
        }

        .hero-header p{
            margin-top:10px;
            font-size:18px;
            opacity:0.9;
        }

        /* FORM CARD */
        .form-container{
            max-width:750px;
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
            font-size:35px;
            font-weight:600;
            margin-bottom:30px;
        }

        label{
            font-weight:500;
            margin-bottom:8px;
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

        /* BUTTON */
        .btn-custom{
            background: linear-gradient(135deg, #06b6d4, #2563eb);
            border:none;
            height:55px;
            border-radius:15px;
            font-size:18px;
            font-weight:600;
            color:white;
            transition:0.3s;
        }

        .btn-custom:hover{
            transform:translateY(-3px);
            background: linear-gradient(135deg, #2563eb, #06b6d4);
        }

        /* BACK BUTTON */
        .back-btn{
            display:inline-block;
            margin-top:25px;
            color:white;
            text-decoration:none;
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

    <!-- BIG HEADER -->
    <div class="hero-header">
        <h1><i class="bi bi-calendar2-check-fill"></i> DUTY ASSIGNMENT SYSTEM</h1>
        <p>Hospital Duty Scheduling Panel</p>
    </div>

    <!-- FORM -->
    <div class="form-container">

        <h2 class="form-title">Assign Staff Duty</h2>

        <?php if($message != ""){ ?>
            <div class="alert alert-success text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <!-- STAFF -->
            <div class="mb-4">
                <label>Select Staff</label>
                <select name="staff" class="form-select">

                    <?php while($s = $staffs->fetch_assoc()){ ?>

                        <option value="<?php echo $s['staff_id']; ?>">
                            <?php echo $s['full_name']; ?>
                        </option>

                    <?php } ?>

                </select>
            </div>

            <!-- DEPARTMENT -->
            <div class="mb-4">
                <label>Select Department</label>
                <select name="department" class="form-select">

                    <?php while($d = $departments->fetch_assoc()){ ?>

                        <option value="<?php echo $d['department_id']; ?>">
                            <?php echo $d['name']; ?>
                        </option>

                    <?php } ?>

                </select>
            </div>

            <!-- DATE -->
            <div class="mb-4">
                <label>Duty Date</label>
                <input type="date" name="duty_date" class="form-control" required>
            </div>

            <!-- SHIFT -->
            <div class="mb-4">
                <label>Select Shift</label>
                <select name="shift" class="form-select">
                    <option>Morning</option>
                    <option>Afternoon</option>
                    <option>Night</option>
                </select>
            </div>

            <!-- BUTTON -->
            <button type="submit" name="assign" class="btn btn-custom w-100">
                <i class="bi bi-check-circle-fill"></i> Assign Duty
            </button>

        </form>

        <!-- BACK -->
        <div class="text-center">
            <a href="dashboard.php" class="back-btn">
                <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
            </a>
        </div>

    </div>

</body>
</html>