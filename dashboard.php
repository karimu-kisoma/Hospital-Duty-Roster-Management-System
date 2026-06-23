```php id="k2m8xp"
<?php
session_start();

if (!isset($_SESSION['staff_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'Admin') {
    header("Location: staff_dashboard.php");
    exit();
}

include 'config.php';

$name = $_SESSION['name'];
$role = $_SESSION['role'];

/*
|--------------------------------------------------------------------------
| COUNTS
|--------------------------------------------------------------------------
*/

$staff_count = $conn->query("SELECT * FROM staff")->num_rows;

$roster_count = $conn->query("SELECT * FROM duty_roster")->num_rows;

$pending_requests = $conn->query("
SELECT * FROM leave_requests
WHERE status='Pending'
")->num_rows;

$comments_count = $conn->query("
SELECT * FROM roster_comments
")->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hospital Duty Roster Dashboard</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
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
    font-family:'Poppins',sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e3a8a,#06b6d4);
    min-height:100vh;
    overflow-x:hidden;
}

/* HEADER */

.hero-header{
    width:100%;
    padding:60px 20px;
    text-align:center;
    color:white;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(10px);
    border-bottom:1px solid rgba(255,255,255,0.1);
    box-shadow:0 8px 30px rgba(0,0,0,0.2);
}

.hero-header h1{
    font-size:55px;
    font-weight:700;
}

.hero-header p{
    font-size:20px;
    margin-top:10px;
    opacity:0.9;
}

/* DASHBOARD */

.dashboard-container{
    max-width:1300px;
    margin:40px auto;
    padding:20px;
}

/* WELCOME */

.welcome-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(12px);
    border-radius:25px;
    padding:40px;
    color:white;
    text-align:center;
    margin-bottom:35px;
    box-shadow:0 10px 35px rgba(0,0,0,0.25);
}

.welcome-card h2{
    font-size:38px;
    font-weight:600;
}

.welcome-card span{
    color:#ffd166;
}

/* STATS */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:40px;
}

.stat-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(10px);
    border-radius:20px;
    padding:30px;
    text-align:center;
    color:white;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
}

.stat-card i{
    font-size:45px;
    color:#ffd166;
    margin-bottom:15px;
}

.stat-card h3{
    font-size:35px;
    font-weight:700;
}

.stat-card p{
    margin-top:8px;
    opacity:0.9;
}

/* MENU GRID */

.menu-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}

.menu-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(10px);
    border-radius:22px;
    padding:35px 25px;
    text-align:center;
    color:white;
    text-decoration:none;
    transition:0.4s ease;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
    border:1px solid rgba(255,255,255,0.1);
}

.menu-card:hover{
    transform:translateY(-10px) scale(1.03);
    background:rgba(255,255,255,0.18);
    color:white;
}

.menu-card i{
    font-size:55px;
    margin-bottom:20px;
    color:#ffd166;
}

.menu-card h4{
    font-weight:600;
    margin-bottom:10px;
}

.menu-card p{
    font-size:14px;
    opacity:0.85;
}

/* SPECIAL CARDS */

.leave-card{
    border:2px solid #facc15;
}

.comment-card{
    border:2px solid #38bdf8;
}

.logout-card{
    background:linear-gradient(135deg,#ef4444,#dc2626);
}

.logout-card i{
    color:white;
}

/* FOOTER */

footer{
    text-align:center;
    color:white;
    padding:20px;
    margin-top:50px;
    opacity:0.8;
}

@media(max-width:768px){

    .hero-header h1{
        font-size:36px;
    }

    .welcome-card h2{
        font-size:28px;
    }

    .stat-card h3{
        font-size:28px;
    }
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="hero-header">

    <h1>
        <i class="bi bi-hospital"></i>
        HOSPITAL DUTY ROSTER SYSTEM
    </h1>

    <p>
        Admin Dashboard Management Panel
    </p>

</div>

<!-- MAIN -->

<div class="dashboard-container">

    <!-- WELCOME -->

    <div class="welcome-card">

        <h2>
            Welcome, <span><?php echo $name; ?></span>
        </h2>

        <p class="mt-3">
            Logged in as <strong><?php echo $role; ?></strong>
        </p>

    </div>

    <!-- STATS -->

    <div class="stats-grid">

        <div class="stat-card">
            <i class="bi bi-people-fill"></i>
            <h3><?php echo $staff_count; ?></h3>
            <p>Total Staff</p>
        </div>

        <div class="stat-card">
            <i class="bi bi-calendar2-check-fill"></i>
            <h3><?php echo $roster_count; ?></h3>
            <p>Total Duties</p>
        </div>

        <div class="stat-card">
            <i class="bi bi-envelope-paper-fill"></i>
            <h3><?php echo $pending_requests; ?></h3>
            <p>Pending Requests</p>
        </div>

        <div class="stat-card">
            <i class="bi bi-chat-dots-fill"></i>
            <h3><?php echo $comments_count; ?></h3>
            <p>Total Comments</p>
        </div>

    </div>

    <!-- MENU -->

    <div class="menu-grid">

        <!-- MANAGE USERS -->

        <a href="user_register.php" class="menu-card">
            <i class="bi bi-people-fill"></i>
            <h4>Manage Users</h4>
            <p>Create, update and manage all system users.</p>
        </a>

        <!-- ADD STAFF -->

        <a href="add_staff.php" class="menu-card">
            <i class="bi bi-person-plus-fill"></i>
            <h4>Add Staff</h4>
            <p>Register new hospital staff members.</p>
        </a>

        <!-- ASSIGN DUTY -->

        <a href="add_roster.php" class="menu-card">
            <i class="bi bi-calendar2-check-fill"></i>
            <h4>Assign Duty</h4>
            <p>Create and assign staff duty schedules.</p>
        </a>

        <!-- VIEW ROSTER -->

        <a href="adminview_roster.php" class="menu-card">
            <i class="bi bi-table"></i>
            <h4>View Roster</h4>
            <p>View all hospital duty schedules and reports.</p>
        </a>

        <!-- LEAVE REQUESTS -->

        <a href="admin_leave_requests.php" class="menu-card leave-card">
            <i class="bi bi-envelope-paper-fill"></i>
            <h4>Leave Requests</h4>
            <p>Approve or reject staff permission requests.</p>
        </a>

        <!-- COMMENTS -->

        <a href="admin_comments.php" class="menu-card comment-card">
            <i class="bi bi-chat-dots-fill"></i>
            <h4>Staff Comments</h4>
            <p>View and manage comments from staff.</p>
        </a>

        <!-- LOGOUT -->

        <a href="logout.php" class="menu-card logout-card">
            <i class="bi bi-box-arrow-right"></i>
            <h4>Logout</h4>
            <p>Securely sign out from the dashboard.</p>
        </a>

    </div>

</div>

<!-- FOOTER -->

<footer>
    © <?php echo date("Y"); ?>
    Hospital Duty Roster System |
    All Rights Reserved
</footer>

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```
