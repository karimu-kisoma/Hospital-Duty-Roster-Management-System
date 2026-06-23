<?php
session_start();
include 'config.php';

// CHECK LOGIN
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: user_index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];

// FETCH USER DUTIES
$query = "SELECT dr.roster_id, s.full_name, d.name as department, dr.duty_date, dr.shift 
          FROM duty_roster dr
          JOIN staff s ON dr.staff_id = s.staff_id
          JOIN departments d ON dr.department_id = d.department_id
          WHERE dr.staff_id = ?
          ORDER BY dr.duty_date ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Duty Roster</title>

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
    background:linear-gradient(135deg,#0f172a,#1e3a8a,#06b6d4);
    min-height:100vh;
    overflow-x:hidden;
}

/* HEADER */
.hero-header{
    width:100%;
    padding:50px 20px;
    text-align:center;
    color:white;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(10px);
    border-bottom:1px solid rgba(255,255,255,0.1);
    box-shadow:0 8px 25px rgba(0,0,0,0.25);
}

.hero-header h1{
    font-size:55px;
    font-weight:700;
    letter-spacing:2px;
}

.hero-header p{
    margin-top:10px;
    font-size:20px;
    opacity:0.9;
}

/* MAIN CONTAINER */
.main-container{
    max-width:1100px;
    margin:50px auto;
    padding:30px;
}

/* WELCOME CARD */
.welcome-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(12px);
    border-radius:25px;
    padding:35px;
    color:white;
    text-align:center;
    margin-bottom:35px;
    box-shadow:0 10px 35px rgba(0,0,0,0.25);
}

.welcome-card h2{
    font-size:35px;
    font-weight:600;
}

.welcome-card span{
    color:#ffd166;
}

/* TABLE CARD */
.table-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(12px);
    border-radius:25px;
    padding:30px;
    box-shadow:0 10px 35px rgba(0,0,0,0.25);
}

/* TABLE */
.table{
    margin-bottom:0;
    overflow:hidden;
    border-radius:15px;
}

.table thead{
    background:linear-gradient(135deg,#2563eb,#06b6d4);
    color:white;
}

.table thead th{
    border:none;
    padding:16px;
    font-weight:600;
}

.table tbody tr{
    background:rgba(255,255,255,0.92);
    transition:0.3s;
}

.table tbody tr:hover{
    transform:scale(1.01);
    background:#ffffff;
}

.table tbody td{
    padding:15px;
    vertical-align:middle;
    font-weight:500;
}

/* BADGES */
.shift-badge{
    padding:8px 16px;
    border-radius:30px;
    color:white;
    font-size:13px;
    font-weight:600;
}

.morning{
    background:#f59e0b;
}

.afternoon{
    background:#2563eb;
}

.night{
    background:#111827;
}

/* EMPTY MESSAGE */
.empty-box{
    text-align:center;
    color:white;
    font-size:18px;
    padding:40px;
}

/* LOGOUT BUTTON */
.logout-btn{
    display:inline-block;
    margin-top:30px;
    padding:14px 30px;
    border-radius:15px;
    text-decoration:none;
    font-weight:600;
    color:white;
    background:linear-gradient(135deg,#ef4444,#dc2626);
    transition:0.3s;
}

.logout-btn:hover{
    transform:translateY(-3px);
    color:white;
    box-shadow:0 8px 20px rgba(220,38,38,0.4);
}

/* FOOTER */
.footer{
    text-align:center;
    color:white;
    padding:25px;
    opacity:0.8;
}

/* MOBILE */
@media(max-width:768px){

    .hero-header h1{
        font-size:34px;
    }

    .hero-header p{
        font-size:16px;
    }

    .welcome-card h2{
        font-size:26px;
    }

    .main-container{
        padding:15px;
    }
}

</style>
</head>
<body>

<!-- BIG HEADER -->
<div class="hero-header">

    <h1>
        <i class="bi bi-calendar2-check-fill"></i>
        MY DUTY ROSTER
    </h1>

    <p>Hospital Staff Duty Management Dashboard</p>

</div>

<!-- MAIN -->
<div class="main-container">

    <!-- WELCOME -->
    <div class="welcome-card">

        <h2>
            Welcome,
            <span><?php echo htmlspecialchars($fullname); ?></span>
        </h2>

        <p class="mt-3">
            View your assigned hospital duties and shifts below.
        </p>

    </div>

    <!-- TABLE -->
    <div class="table-card">

        <?php if($result->num_rows > 0){ ?>

        <div class="table-responsive">

            <table class="table table-bordered align-middle text-center">

                <thead>
                    <tr>
                        <th><i class="bi bi-person-fill"></i> Staff</th>
                        <th><i class="bi bi-building"></i> Department</th>
                        <th><i class="bi bi-calendar-event"></i> Duty Date</th>
                        <th><i class="bi bi-clock-fill"></i> Shift</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while($row = $result->fetch_assoc()){ ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($row['full_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['department']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['duty_date']); ?>
                        </td>

                        <td>

                            <?php
                                $shift = strtolower($row['shift']);
                            ?>

                            <span class="shift-badge <?php echo $shift; ?>">

                                <?php echo htmlspecialchars($row['shift']); ?>

                            </span>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <?php } else { ?>

            <div class="empty-box">

                <i class="bi bi-calendar-x-fill" style="font-size:60px;"></i>

                <p class="mt-3">
                    No duties assigned yet.
                </p>

            </div>

        <?php } ?>

        <!-- LOGOUT -->
        <div class="text-center">

            <a href="user_logout.php" class="logout-btn">

                <i class="bi bi-box-arrow-right"></i>
                Logout

            </a>

        </div>

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